<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if ($request->newFileName) {
            $fw = fopen($request->newFileName, "a");
            $data = file_get_contents($request->file('data'));
            fwrite($fw, $data);
            fclose($fw);

            return 200;
        }
       
        return 400;
    }
    
}
