<?php

namespace App\Http\Controllers\Api;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @SWG\Get(
     *   tags={"category"},
     *   path="/api/v1/categories",
     *   summary="List Categories",
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
        return $this->category->paginate(10);
    }

    /**
     * @SWG\Get(
     *   tags={"category"},
     *   path="/api/v1/categories/{id}/real-states",
     *   summary="List Real-state by category",
     *   operationId="list",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *   security={{"default": {}}},
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="-   Enter the id",
     *          type="integer",
     *      ),
     * )
     *
     */
    public function realStates($id)
    {
        try {
            $category = $this->category->findOrFail($id);

            return response()->json(
                [
                    'data'  =>  $category->realStates
                ],200
            );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @SWG\Post(
     *   tags={"category"},
     *   path="/api/v1/categories",
     *   security={{"default": {}}},
     *   summary="Create category",
     *   operationId="create",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          description="-   Enter the name",
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
     *          name="slug",
     *          in="query",
     *          required=true,
     *          description="-   Enter the slug",
     *          type="string",
     *      ),
     * )
     *
     */
    public function store(CategoryRequest $request)
    {

        try {
            $data = $request->all();
            $category = $this->category->create($data);

            return response()->json(
                [
                    'data'  => 'Category created success!'
                ], 201);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    /**
     * @SWG\Get(
     *   tags={"category"},
     *   path="/api/v1/categories/{id}",
     *   summary="Show Category",
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
            $category = $this->category->findOrFail($id);
            return response()->json($category, 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }


    /**
     * @SWG\Put(
     *   tags={"category"},
     *   path="/api/v1/categories/{id}",
     *   security={{"default": {}}},
     *   summary="Update category",
     *   operationId="update",
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error"),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="-   Enter the id",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          description="-   Enter the name",
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
     *          name="slug",
     *          in="query",
     *          required=true,
     *          description="-   Enter the slug",
     *          type="string",
     *      ),
     * )
     *
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $category = $this->category->findOrFail($id);
            $category->update($data);
            return response()->json(
                [
                    'data'  =>  'Category updated Success!'
                ], 201
            );
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = new ApiMessages("Category not found!");
            return response()->json($message, 404);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @SWG\Delete(
     *   tags={"category"},
     *   path="/api/v1/categories/{id}",
     *   summary="Delete Category",
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
            $category = $this->category->findOrFail($id);
            $category->delete();
            return response()->json(
                [
                    'data'  =>  'Category delete!'
                ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
