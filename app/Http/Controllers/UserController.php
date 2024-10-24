<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        return response()->json(['message' => 'Logged in successfully'], 200);
    }

    public function update(Request $request,$id){
        $user= User::find($id);
        $request->validate([
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:users',
            'password' => 'sometimes|required',
        ]);
        $user->update($request->all());
        return $user;
    }
    public function delete($id){
        return User::destroy($id);
    }

    public function show($id){
     return User::find($id);
    }

    public function search($name){
        return User::where('name','like','%' . $name . '%')->get();
    }
}
