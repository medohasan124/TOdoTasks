<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){


        $validate = Validator::make($request->all(),[
            'email' => 'required' ,
            'password' => 'required' ,
        ]);


         if($validate->fails()){
            return response()->json($validate->errors() ,422);
         }



        $credintial = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);




        if($credintial){

             $user = auth()->user() ;
             $data['user'] = new UserResource($user);
             $data['token'] = $user->createToken('Token')->plainTextToken;
             return response()->json($data , 200);
        }else{
            return response()->json(['message' => 'Invalid credentials'], 401);
        }



    }



    public function register(Request $request)
    {
        /*
    register
    1 - make validator name email password
    2 if fails return error
    3 merge password request
    4 - create user at DB
    5 create data and token
    6 return reponse api
    */

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ]);



        if ($validator->fails()) {

            return response()->json(['message' =>$validator->errors()->first()], 422);
        }



        $request->merge([
            'password' => bcrypt($request->password),
        ]);

        $user = User::create($request->all());
        $data['user'] = new UserResource($user);
        $data['token'] = $user->createToken('registertoken')->plainTextToken;
        return response()->json($data, 201);
    }
}


