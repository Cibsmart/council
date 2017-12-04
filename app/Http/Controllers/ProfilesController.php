<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $profileUser = $user;

        $threads = $user->threads()->paginate(30);

        return view('profiles.show', compact('profileUser', 'threads'));
    }
}
