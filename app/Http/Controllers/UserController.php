<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class UserController extends Controller
{
    public function registration(Request $request){
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user ->password = Hash::make($request->password);
        $user->phone_no = $request->phone_no;
        $user->save();

        return response()->json(['status' =>TRUE, 'log'=>"user Created Successfully"], 200);
    }

    function index(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        //print_r($user);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
            return response($response, 201);
    }
}
