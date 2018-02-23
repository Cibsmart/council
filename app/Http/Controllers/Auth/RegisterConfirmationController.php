<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function redirect;
use function route;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        User::where('confirmation_token',request('token'))
            ->firstOrFail()
            ->confirm();

        return redirect(route('threads.index'))
            ->with('flash', 'Your Account is Now Confirmed! You May Post to the Forum');
    }
}
