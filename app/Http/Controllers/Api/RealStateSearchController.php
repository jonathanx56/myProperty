<?php

namespace App\Http\Controllers\Api;
use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;
use Illuminate\Http\Request;

class RealStateSearchController extends Controller
{
    public $realState;
    public $filtersAndConditions;
    public function __construct(RealState $realState)
    {
        $this->realState = $realState;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $realStates = $this->realState;
        $realStateRepository = new RealStateRepository($realStates);

        if($request->has('fields'))
        {
            $realStateRepository->selectFilter($request->fields);
        }

        if($request->has('conditions'))
        {
            $realStateRepository->selectConditions($request->conditions);
        }

        $realStateRepository->setLocation($request->all(['state','city']));


        return response()->json([
            'data'  =>  $realStateRepository->getResult()->paginate(5)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $realState = $this->realState->with('address')->with('photos')->findOrFail($id);

            return response()->json([
                'data' => $realState
            ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

}
