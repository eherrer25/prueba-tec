<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        try{
            $users = User::with('roles')->get();

            return response()->json(['data'=>$users],200);

            // return $this->successResponse(['data'=>$users], 200, true);

        } catch (Exception $e) {
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function show(string $id){

        $user = User::find($id);

        if ($user){

            return response()->json([
                'Message: ' => 'Usuario encontrado.',
                'data' => $user
            ], 200);

        }else {

            return response(['Message: ' => 'Usuario no encontrado',], 404);
        }
    }

    public function store(Request $request){

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
            'message: ' => 'Success!',
            'user'     => $user
        ],200);

    }

    public function update(Request $request, string $id){

        $user = User::find($id);

        if($user){

           $input = $request->validate([
              'name' => ['required', 'string', 'max:255'],
              'password' => ['sometimes','nullable','min:8'],
            ]);

            $user->name = $input['name'];
            if($input['password']){
                $user->password = $input['password'];
            }

            if($user->save()){

                return response()->json(['message' => 'Usuario actualizado correctamente.','data' => $user], 200);

            }else {

                return response(['message' => 'No pudimos actualizar el usuario.'], 500);

            }
        }else {

            return response(['message' => 'Usuario no encontrado.'], 404);
        }

    }

    public function destroy(string $id){

        $user = User::find($id);

        if($user){

            $user->delete();

            return response()->json(['message' => 'Usuario eliminado.'], 200);

        }else {

            return response(['message' => 'Usuario no encontrado.',], 404);
        }

    }


}
