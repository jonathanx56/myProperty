<?php

namespace App\Http\Controllers\Api;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAndProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @SWG\Get(
     *   tags={"user"},
     *   path="/api/v1/users",
     *   summary="List Users",
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
        $users = $this->user->paginate('10');

        return response()->json($users, 200);
    }

    /**
     * @SWG\Post(
     *   tags={"user"},
     *   path="/api/v1/users",
     *   security={{"default": {}}},
     *   summary="Create User",
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
     *          name="email",
     *          in="query",
     *          required=true,
     *          description="-   Enter the email",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          description="-   Enter the password",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *          description="-   Enter the password_confirmation",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="profile[phone]",
     *          in="query",
     *          required=true,
     *          description="-   Enter the property profile",
     *          type="array",
     *          collectionFormat="multi",
     *          @SWG\Items(
     *              type="string",
     *          )
     *      ),
     *
     * )
     */
    public function store(UserAndProfileRequest $request)
    {
        $data = $request->all();

        try {
            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $user = $this->user->create($data);
            $user->profile()->create($profile);

            return response()->json(
            [
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!'
                ]
            ],200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }

    /**
     * @SWG\Get(
     *   tags={"user"},
     *   path="/api/v1/users/{id}",
     *   summary="Show User",
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
            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

            return response()->json(
            [
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserAndProfileRequest $request, $id)
    {
        $data = $request->all();

        try {
            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $user = $this->user->findOrFail($id);
            $user->update($data);

            $user->profile()->update($profile);

            return response()->json(
                [
                    'data'  => 'Update Success!'
                ]
            );

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * @SWG\Delete(
     *   tags={"user"},
     *   path="/api/v1/users/{id}",
     *   summary="Delete User",
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
            $user = $this->user->findOrFail($id);
            $user->delete();
            return response()->json(
                [
                    'data'  => 'The '.$user->name.' delete success!'
                ], 200
                );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }

    }
}
