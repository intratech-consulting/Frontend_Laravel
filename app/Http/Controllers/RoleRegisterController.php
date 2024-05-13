<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleRegisterController extends Controller
{
       public function register_speaker()
    {
   return view('auth.register-speaker');
    }

 public function register_company()
    {
   return view('auth.register-company');
    }

}
