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
     * @SWG\Get(
     *   tags={"real-states-search"},
     *   path="/api/v1/search",
     *   summary="Get Search Real-state for external use",
     *   operationId="Search Real-state",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *		@SWG\Parameter(
     *          name="conditions",
     *          in="query",
     *          required=false,
     *          description="-   Follow the format below attribute:caracter:value",
     *
     *          type="array",
     *          @SWG\Items(
     *              type="string"
     *          )
     *      ),
     *      @SWG\Parameter(
     *          name="fields",
     *          in="query",
     *          required=false,
     *          description="-   Enter the name of the fields separated by commas",
     *          type="array",
     *          @SWG\Items(
     *          type="string"
     *          )
     *      ),
     * )
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
     * @SWG\Get(
     *   tags={"real-states-search"},
     *   path="/api/v1/search/{real_state_id}",
     *   summary="Show real-states with andress and photo for external use",
     *   operationId="show",
     *   security={{"default": {}}},
     *   @SWG\Parameter(
     *      name="real_state_id",
     *      in="path",
     *      required=true,
     *      description="-   Enter the real_state_id",
     *      type="integer",
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
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
