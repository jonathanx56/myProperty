<?php

namespace App\Http\Controllers\Api;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;
use Illuminate\Http\Request;

class RealStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $realState;
    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }
    public function index()
    {
        $realState = $this->realState->paginate(10);
        return response()->json($realState, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RealStateRequest $request)
    {
        try {
            $data = $request->all();
            $realState = $this->realState->create($data);

            return response()->json([
                'data' => $realState->title.' Create success!'
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

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
            $realState = $this->realState->findOrFail($id);
            return response()->json(
                [
                    'data'  =>  $realState
                ], 201
            );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message, 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RealStateRequest $request, $id)
    {

        try {
            $realState = $this->realState->findOrFail($id);
            $data = $request->all();
            $realState->update($data);
            return response()->json(
                [
                    'data'  => 'Update success!'
                ], 201
            );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);
            $realState->delete();
            return response()->json(
                [
                    'data'  => 'The '.$realState->title.' delete success!'
                ], 200
                );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }



    }
}
