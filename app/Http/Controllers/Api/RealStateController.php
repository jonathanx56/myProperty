<?php

namespace App\Http\Controllers\Api;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;
use Illuminate\Http\Request;

class RealStateController extends Controller
{
    private $realState;
    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    /**
     * @SWG\Get(
     *   tags={"real-states"},
     *   path="/api/v1/real-states",
     *   summary="List Real-state",
     *   operationId="list",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *   security={{"default": {}}},
     * )
     *
     */
    public function index()
    {
        $user = auth('api')->user();
        $realState = $user->real_state()->paginate(10);
        return response()->json($realState, 200);
    }

     /**
     * @SWG\Post(
     *   tags={"real-states"},
     *   path="/api/v1/real-states",
     *   security={{"default": {}}},
     *   summary="Create Real-state",
     *   operationId="create",
     *   consumes={"multipart/form-data"},
     *   produces={"text/plain, application/json"},
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          description="-   Enter the user id",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="title",
     *          in="query",
     *          required=true,
     *          description="-   Enter the title",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="query",
     *          required=true,
     *          description="-   Enter the description",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="content",
     *          in="query",
     *          required=true,
     *          description="-   Enter the content",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="price",
     *          in="query",
     *          required=true,
     *          description="-   Enter the price",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="slug",
     *          in="query",
     *          required=true,
     *          description="-   Enter the slug",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="bedrooms",
     *          in="query",
     *          required=true,
     *          description="-   Enter the bedrooms",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="bathrooms",
     *          in="query",
     *          required=true,
     *          description="-   Enter the bathrooms",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="property_area",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property area",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="total_property_area",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property total property area",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="categories[]",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property categories",
     *          type="array",
     *          collectionFormat="multi",
     *          @SWG\Items(
     *              type="integer",
     *              format="int32"
     *          )
     *      ),
     *      @SWG\Parameter(
     *          name="address_id",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property address",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="images",
     *          in="formData",
     *          required=true,
     *          description="-   Enter the images",
     *          type="file",
     *      ),
     * )
     *
     */
    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try {
            $data['user_id'] = auth('api')->user()->id;

            $realState = $this->realState->create($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            $imagesUploaded = [];
            if($images) {
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

            return response()->json([
                'data' => $realState->title.' Create success!'
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    /**
     * @SWG\Get(
     *   tags={"real-states"},
     *   path="/api/v1/real-states/{id}",
     *   summary="Show Real-state",
     *   operationId="show",
     *   security={{"default": {}}},
     *   @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="-   Enter the id",
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
            $user = auth('api')->user();
            $realState = $user->real_state()->with('photos')->findOrFail($id);
            return response()->json(
                [
                    'data'  =>  $realState
                ], 201
            );
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = new ApiMessages("Real-state not found for this user!");
            return response()->json($message, 404);
        }catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message, 401);
        }
    }

     /**
     * @SWG\Put(
     *   tags={"real-states"},
     *   path="/api/v1/real-states/{id}",
     *   security={{"default": {}}},
     *   summary="Update Real-state",
     *   operationId="update",
     *   consumes={"multipart/form-data"},
     *   produces={"text/plain, application/json"},
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="-   Enter the real-state id",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          description="-   Enter the user id",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="title",
     *          in="query",
     *          required=true,
     *          description="-   Enter the title",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="query",
     *          required=true,
     *          description="-   Enter the description",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="content",
     *          in="query",
     *          required=true,
     *          description="-   Enter the content",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="price",
     *          in="query",
     *          required=true,
     *          description="-   Enter the price",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="slug",
     *          in="query",
     *          required=true,
     *          description="-   Enter the slug",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="bedrooms",
     *          in="query",
     *          required=true,
     *          description="-   Enter the bedrooms",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="bathrooms",
     *          in="query",
     *          required=true,
     *          description="-   Enter the bathrooms",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="property_area",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property area",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="total_property_area",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property total property area",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="categories[]",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property categories",
     *          type="array",
     *          collectionFormat="multi",
     *          @SWG\Items(
     *              type="integer",
     *              format="int32"
     *          )
     *      ),
     *      @SWG\Parameter(
     *          name="address_id",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property address",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="images",
     *          in="formData",
     *          required=true,
     *          description="-   Enter the images",
     *          type="file",
     *      ),
     * )
     *
     */
    public function update(RealStateRequest $request, $id)
    {
        $data = $request->all();
        $images = $request->file('images');

        try {
            $user = auth('api')->user();

            $realState = $user->real_state()->findOrFail($id);
            $realState->update($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }

            $imagesUploaded = [];
            if($images){
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' =>false];
                }
                // createMany Cria varios
                $realState->photos()->createMany($imagesUploaded);
            }

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
     * @SWG\Delete(
     *   tags={"real-states"},
     *   path="/api/v1/real-states/{id}",
     *   summary="Delete Real-state",
     *   operationId="delete",
     *   security={{"default": {}}},
     *   @SWG\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="-   Enter the id",
     *      type="integer",
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     * )
    */
    public function destroy($id)
    {
        try {
            $user = auth('api')->user();

            $realState = $user->real_state()->findOrFail($id);
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
