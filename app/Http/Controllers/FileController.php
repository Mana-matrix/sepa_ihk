<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image as Image;

class FileController extends Controller
{
    public function showImage($user_id, $type = null)
    {

        if ($user_id === 'default')
            return Image::make(storage_path("app/clients/default.jpg"))->response()->header('file_exists','true');
        $storagePath = storage_path("app/clients/$type/$user_id.jpg");

        if (file_exists($storagePath))
            return Image::make($storagePath)->response()->header('file_exists','true');
        else
            return Image::make(storage_path("app/clients/default.jpg"))->response()->header('file_exists','false');

    }

    public function exists($user_id, $type = null)
    {
        if($type=='portfolios')
            $ext='pdf';
        else
            $ext='jpg';
        $storagePath = storage_path("app/clients/$type/$user_id.$ext");
        return  json_encode(['exists'=>file_exists($storagePath)]);

    }
}
