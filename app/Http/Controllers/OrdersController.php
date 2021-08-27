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

    public function placeOrders(Request $request){

        $orders = DB::table('orders')
            ->where('orders.user_id', '=',$request->user_id)
            ->whereIn('orders.status', ['0','1'] )
            ->get();

        if($orders){
            return response()->json(['status' =>FALSE, 'log'=>"You have already placed an order" ], 200);
        }
        $carts = DB::table('carts')
        ->where('user_id', '=', $request->user_id)
        ->select('carts.*', DB::raw('COUNT(carts.food_id) as total'))
        ->groupBy('food_id')
        ->get();
        foreach ($carts as $cart) {

             DB::table('orders')->insert([
                'user_id' => $request->user_id,
                'food_id' => $cart->food_id,
                'count' => $cart->total,
                'address_id' => $request->address_id,
                'status' => 0,
                'created_at' => Carbon::now()
            ]);

        }

       
        DB::table('carts')->where('user_id', '=', $request->user_id)->delete();


        return response()->json(['status' =>TRUE, 'log'=>"order placed Successfully", $data = $carts ], 200);
    }


    public function addAddress(Request $request){

        DB::table('address')->insert([
            'user_id' => $request->user_id,
            'phone_no' => $request->phone_no,
            'description' => $request->description,
            'created_at' => Carbon::now()
        ]);

        return response()->json(['status' =>TRUE, 'log'=>"Address added Successfully"], 200);
    }

    public function getAddress($user_id){

        $data = DB::table('address')
        ->where('user_id','=', $user_id)
        ->get();

        return response()->json(['status' =>TRUE, 'data'=>$data ], 200);
    }

    public function getAddressById($user_id, $address_id){
        $data = DB::table('address')
        ->where('user_id','=', $user_id)
        ->where('id','=', $address_id)
        ->get();

        return response()->json(['status' =>TRUE, 'data'=>$data ], 200);
    }


    public function getLastAddress($user_id){
        $data = DB::table('address')
        ->where('user_id','=', $user_id)
        ->orderBy('id','desc')
        ->first();

        return response()->json(['status' =>TRUE, 'data'=>$data ], 200);
    }

    public function getPlacedOrders($user_id){

        $orders = DB::table('orders')
            ->join('foods', 'foods.id','=','orders.food_id')
            ->where('orders.user_id', '=',$user_id)
            ->select('orders.id as order_id','orders.*','foods.*', 'orders.created_at as order_date')
            ->orderBy('orders.created_at','desc')
            ->get();

            return response()->json(['status' =>TRUE, 'data'=>$orders ], 200);
    }
}
