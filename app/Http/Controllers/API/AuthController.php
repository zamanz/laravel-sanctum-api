<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['user','logout']);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json(['user' => $user],200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request) : JsonResponse
    {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken($request->email);

            return response()->json(['token' => $token->plainTextToken], 201);
        }
        else{

            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request,[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            //create token
            $token = $user->createToken($request->email);

            return response()->json(['token' => $token->plainTextToken], 201);
        }
        else{
            //error message
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    protected function credentials($request)
    {
        return $request->only('email', 'password');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' =>'You are Successfully Logged out'], 200);
    }

}
