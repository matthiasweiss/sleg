<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function index($name)
    {
        return User::where('name', 'like', '%' . $name . '%')->get();
    }
}
