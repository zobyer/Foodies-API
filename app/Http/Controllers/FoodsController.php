<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodsController extends Controller
{
    public function storeFood(Request $request){
        
        

        if($request->hasFile('image')){
            $image = $request->file('image')->store('images');
        }

        DB::table('foods')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'zilla' => $request->zilla,
            'image' => $image,
            'price' => $request->price,
            'point' => $request->point
        ]);
        return response()->json(['status' =>TRUE, 'log'=>"Data Inserted Successfully"], 200);
    }

    public function getFoodbyId($id){
        $food = DB::table('foods')
                ->where('id', $id)
                ->first();
        //$dirname = storage_path()."/app/images/C8oOvfSBJ0mHiKL6bLdaGn1waqcZFIu9LS7buceN.png";
        if( $food ){
            $dirname = storage_path()."/app/".$food->image;
            $img = file_get_contents($dirname);
            $img_data = base64_encode($img);
            return response()->json(["status" => TRUE,"food" => $food,"image" => $img_data], 200);
        }
        
        return response()->json(["status" => FALSE,"log" => "Food not found"], 200);
    }

    public function getAllFoods(){
        $foods = DB::table('foods')
                ->get();
     
        foreach($foods as $food){
            $dirname = storage_path()."/app/".$food->image;
            $img = file_get_contents($dirname);
            $img_data = base64_encode($img);
            $food->image = "data:image/png;base64,".$img_data;
        }
        return response()->json(["status" => TRUE,"foods" => $foods], 200);
    }

    public function getFamousFoods(){
        $foods = DB::table('foods')
        ->orderBy('point', 'DESC')
        ->take(10)
        ->get();

        foreach($foods as $food){
            $dirname = storage_path()."/app/".$food->image;
            $img = file_get_contents($dirname);
            $img_data = base64_encode($img);
            $food->image = "data:image/png;base64,".$img_data;
        }
        return response()->json(["status" => TRUE,"foods" => $foods], 200);
    }

    public function searchByCity(Request $request){
       // return response()->json([$request['name']], 200);
        $foods = DB::table('foods')
                ->where('zilla', 'like', '%' .$request['name']. '%')->get();

        foreach($foods as $food){
            $dirname = storage_path()."/app/".$food->image;
            $img = file_get_contents($dirname);
            $img_data = base64_encode($img);
            $food->image = "data:image/png;base64,".$img_data;
        }

        return response()->json(["status" => TRUE,"foods" => $foods], 200);
    }

    public function searchByFood(Request $request){
        // return response()->json([$request['name']], 200);
         $foods = DB::table('foods')
                 ->where('name', 'like', '%' .$request['name']. '%')->get();

        foreach($foods as $food){
            $dirname = storage_path()."/app/".$food->image;
            $img = file_get_contents($dirname);
            $img_data = base64_encode($img);
            $food->image = "data:image/png;base64,".$img_data;
        }
 
         return response()->json(["status" => TRUE,"foods" => $foods], 200);
     }

}
