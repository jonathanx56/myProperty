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
        $user = auth('api')->user();
        $realState = $user->real_state()->paginate(10);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
