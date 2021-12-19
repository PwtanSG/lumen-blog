<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $this->validate($request, [
            'name'=> 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:255'
        ]);

        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app('hash')->make($request->password);
            if($user->save()){
                return $this->login($request);
            }
        }catch(\Exception $e){
                return response()->json(['status'=>'Error', 'message'=>$e->getMessage()]);
        }
    }
    public function login(Request $request)
    {

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    } 

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }    

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function myposts()
    {
        $uid = auth()->user()->id;
        $posts = User::find($uid)->getPosts;
        //return $posts;
        return response()->json($posts);
    }

    public function userposts()
    {
        $uid = auth()->user()->id;
        $posts = User::with('getPosts')->get();
        //return $posts;
        return response()->json($posts);
    }
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
