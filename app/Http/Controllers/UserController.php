<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the createUserForm
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showCreateUser()
    {
        return view('createUser');
    }

    public function createUser(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return view('createUser', ['success' => 'User created successfully']);
    }

    public function showUsers()
    {
        $users = User::all();

        return view('showUsers', ['users' => $users]);
    }

    /**
     * Deletes a user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteUser($idUser)
    {
        User::destroy($idUser);

        return response()->json([
            'success' => true
        ]);
    }
}

