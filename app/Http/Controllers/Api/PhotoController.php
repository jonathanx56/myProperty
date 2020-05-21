<?php

namespace App\Http\Controllers\Api;

use App\ApiMessages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealStatePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public $realStatePhoto;
    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($id, $realStateid)
    {

        $photo = $this->realStatePhoto
            ->where('real_state_id', $realStateid)
            ->where('is_thumb', true)
            ->first();

        if($photo){
            $photo->update(['is_thumb' => false]);
        }

        $photoIsThumb = $this->realStatePhoto->findOrFail($id);
        $photoIsThumb->update(['is_thumb' => true]);

        return response()->json(
            [
                'data'  => [
                    'msg'   => 'Thumb update success!'
                ]
            ],200);

        try {

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage);
            return response()->json([$message->getMessage()],401);
        }
    }

    public function delete($id)
    {
        try {
            $photo = $this->realStatePhoto->findOrFail($id);

            if($photo->is_thumb){
                $message = new ApiMessages('Cant delete image because is thumb');
                return response()->json([$message], 401);
            }
            if($photo)
            {
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }

            return response()->json(
                [
                    'data'  => [
                        'msg'   => 'photo delete success!'
                    ]
                ],200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }


    }
}
