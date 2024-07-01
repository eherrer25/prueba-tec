<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        # By default we are using here auth:api middleware
        $this->middleware('auth:api', ['except' => ['login','register','refresh']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        // ]);

        $credentials = request()->only('email', 'password');

        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();

        return $this->respondWithToken($token,$user);
    }

    public function register(Request $request)
    {
        
        // $this->validator($request->all())->validate();
        $inputs = $request->validate([
              'name' => ['required', 'string', 'max:255'],
              'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
              'password' => ['required', 'string', 'min:8'],
            ]);
        
        $user = User::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => Hash::make($inputs['password']),
        ]);

        $user->assignRole('guest');

        return response()->json([
            'message' => 'Usuario registrado con éxito!',
            'user'     => $user
        ],200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        # Here we just get information about current user
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user = Auth::guard('api')->user();
        $token = Auth::guard('api')->refresh();
        
        return $this->respondWithToken($token,$user);
    //    return response()->json([
    //         'status' => 'success',
    //         'user' => Auth::guard('api')->user(),
    //         'authorisation' => [
    //             'token' => Auth::guard('api')->refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user)
    {
        $rol = $user->roles()->first()->name;

        return response()->json([
            'user' => $user,
            'rol' => $rol,
            'token' => $token,
            'token_type' => 'Bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
