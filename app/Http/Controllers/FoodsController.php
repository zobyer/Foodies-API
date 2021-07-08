<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodsController extends Controller
{
    public function storefoods(Request $request){
        //$str = $request->image;
        //$image = base64_encode($request->file('image'));
        // DB::table('foods')->insert([
        //     'name' => 'check 1',
        //     'description' => 'check1',
        //     'zilla' => 'check',
        //     'image' => $image,
        //     'price' => 120.30
        // ]);
        if($request->hasFile('image')){
            $image = $request->file('image')->store('images');
            return response()->json(['found',$image], 200);
        }

        return response()->json('not found', 200);
    }

    public function getfoodbyid($id){
        $dirname = storage_path()."/app/images/7BEW4pn5ejdVYEYQeuEsTjVftqOtpPOLHexYl9ZE.png";
        $data = file_get_contents($dirname);
        $data = base64_encode($data);
        //return response()->download([$dirname], 200);
        return response()->json($data, 200);
    }

}
