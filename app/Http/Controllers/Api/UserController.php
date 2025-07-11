<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Host;
use App\Models\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {

        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(["message"=> "Usuário não encontrado"], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'pass' => 'required|string|min:6',
            'typeuser' => 'required|integer',
        ]);

        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function getLoginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pass' => 'required|string',
            'remember' => 'boolean',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->pass,
        ];

        $remember = (bool) $request->input('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return response()->json([
                'message'=> 'Login realizado com sucesso!',
                'user'=> Auth::user(),
            ], 200);
        }

        throw ValidationException::withMessages([
            'email' => ['Usuário inválido'],
        ]);
    }

    public function getAuthenticatedUser(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $shareToken = null;

            if ($user->typeuser === UserType::Host) {
                $shareToken = $user->host?->share_token;
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'firstaccess' => $user->firstaccess,
                'typeUserValue' => $user->typeuser->value,
                'typeUserLabel' => $user->typeuser->label(),
                'share_token' => $shareToken,
            ]);
        }

        return response()->json(['message'=> 'Usuário não autenticado'], 401);
    }

    public function logoutUser(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
