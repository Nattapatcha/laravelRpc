<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class FilesController extends Controller
{
    //
    public function images($file)
    {
        return response()->file(storage_path('images\\') . $file);
    }

    public function download($file)
    {
        return response()->file(storage_path('download\\') . $file);
    }
}
