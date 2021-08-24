<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersController extends Controller
{
    //
    public function addToCart(Request $request){
        
        DB::table('carts')->insert([
            'user_id' => $request->user_id,
            'food_id' => $request->food_id,
            'created_at' => Carbon::now()
            
        ]);

        return response()->json(['status' =>TRUE, 'log'=>"Data Inserted Successfully"], 200);
    }

    public function countCart($user_id){
        $total = DB::table('carts')
        ->where('user_id','=',$user_id)
        ->count();

        return response()->json(['status' => TRUE, 'total' => $total], 200);
    }

    public function removefromcart(Request $request){
        try{
            $cartid = DB::table('carts')->where('user_id', '=', $request->user_id)
            ->where('food_id', '=', $request->food_id)
            ->orderBy('created_at','asc')
            ->select('carts.id')
            ->first();

            DB::table('carts')->where('id','=', (array)$cartid)->delete();
            
        }catch (Throwable $e) {
            #report($e);
    
            return response()->json(['status' =>TRUE, 'log'=>"No data exists"], 404);
        }

        return response()->json(['status' =>TRUE, 'log'=>"Data Deleted Successfully"], 200);
    }

    public function getAllOrders($user_id){
        $orders = DB::table('carts')
        ->join('users', 'users.id','=','carts.user_id')
        ->join('foods','foods.id','=','carts.food_id')
        ->where('carts.user_id', '=',$user_id)
        ->select('foods.*', DB::raw('COUNT(carts.user_id) as total'))
        ->groupBy('carts.user_id')
        ->get();

        $total = DB::table('carts')
        ->join('foods','carts.food_id','=','foods.id')
        ->select(DB::raw("SUM(foods.price) as totalprice"))
        ->get();

        return response()->json(['status' =>TRUE, 'data'=>$orders, 'totalprice' => $total[0]->totalprice], 200);
    }
}
