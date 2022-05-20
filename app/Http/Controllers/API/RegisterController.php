<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input , [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password',
        ]);

        if($validator->fails())
        {
            return $this->sendError('Validated Error' , $validator->errors());
        }

        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->api_token = Str::random(length:60);
        $user->save();

        return $this->sendResponse($user , 'You Are Registered Successfully');

    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email'=>$request->email , 'password'=>$request->password]))
        {
            $user = Auth::user();
            // $user->api_token = Str::random(length:60);
            return $this->sendResponse($user , 'User Login Successfully');
        }
        else
        {
            return $this->sendError('Unauthorized');
        }
    }
}
