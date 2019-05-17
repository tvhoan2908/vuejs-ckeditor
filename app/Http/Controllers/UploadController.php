<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // dd($request->file('file'));
        $imageName = time().'.'. $request->file('file')->getClientOriginalExtension();
        $request->file('file')->move(public_path('images'), $imageName);

        return response()->json(['url' => 'images/' . $imageName, 'result' => 'success']);
    }
}
