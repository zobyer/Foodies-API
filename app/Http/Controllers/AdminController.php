<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    //
    public function fetchAllPendingOrders(Request $request){
        $orders = DB::table('orders')
        ->join('users', 'users.id','=','orders.user_id')
        ->join('address','address.id','=','orders.address_id')
        ->join('foods','foods.id','=','orders.food_id')
        ->where('orders.status', '=',$request->state)
        ->select('orders.id as order_id','foods.name as food_name','foods.zilla as zilla', 'foods.price as price', 'orders.count as amount', 'orders.status as status',
        'address.description as description', 'address.phone_no as phone_no', 'orders.created_at as order_date')
        ->get();

        return response()->json(['status' =>TRUE, 'data'=>$orders], 200);
    }

    public function updateStatus(Request $request){

        DB::table('orders')->where('id', $request->id)->update([
            'status' => $request->state
            ]);

        return response()->json(['status' =>TRUE, 'data'=>'order state updated'], 200);
    }
}
