<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;
}
