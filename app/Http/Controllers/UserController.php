<?php

namespace App\Http\Controllers;


use App\Models\User;


class UserController extends Controller
{


    /**
     * Fetchs the user information which we have provide the id.
     * If the user not exist launche the error 404 .
     */
    public static function getUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404);
        }
        $user->tasks;
        return view('user.detail', ['user' => $user]);
    }

}