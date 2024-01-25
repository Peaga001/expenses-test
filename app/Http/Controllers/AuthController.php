<?php

namespace App\Http\Controllers;

//Requests
use App\Http\Requests\Auth\{
    Register,
    Login
};

//Resources
use App\Http\Resources\UserResource;

//Models
use App\Models\User;

//Miscellaneous
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Register $request): object
    {
        /* @var User $user */

        $user = User::query()->make($request->all());
        $user->save();

        $token = $user->createToken($user->getAttribute('name').'-AuthToken')->plainTextToken;

        $user->setAttribute('token', $token);

        return response([
            'message' => 'Usuário criado com sucesso!',
            'data'    => new UserResource($user)
        ])->setStatusCode(
            Response::HTTP_CREATED
        );
    }


    public function login(Login $request): object
    {
        /* @var User $user */

        $user = User::query()->firstWhere('email', $request->input('email'));

        if(!$user || !Hash::check($request->input('password'), $user->getAttribute('password'))){
            return response([
                'message' => 'Usuário ou senha inválido(s)!'
            ])->setStatusCode(
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $user->createToken($user->getAttribute('name').'-AuthToken')->plainTextToken;

        $user->setAttribute('token', $token);

        return response([
            'message' => 'Login feito com sucesso!',
            'data'    => new UserResource($user)
        ])->setStatusCode(
            Response::HTTP_CREATED
        );
    }

    public function logout(Request $request): object
    {
        $request->user('sanctum')->tokens()->delete();

        return response([
            'message' => 'Logout feito com sucesso!'
        ])->setStatusCode(
            Response::HTTP_OK
        );
    }
}
