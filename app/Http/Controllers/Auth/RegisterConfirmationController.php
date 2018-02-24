<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function redirect;
use function route;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token',request('token'))->first();

        if(! $user)
        {
            return redirect(route('threads.index'))
                ->with('flash', 'Unknown Token');
        }

        $user->confirm();

        return redirect(route('threads.index'))
            ->with('flash', 'Your Account is Now Confirmed! You May Post to the Forum');
    }
}
