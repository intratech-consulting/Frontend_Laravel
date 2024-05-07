<?php

namespace App\Http\Controllers;

 class headerController
{
     public function about()
    {
      /*  if(Auth::id()) 
        {
            if(Auth::user()->typeUser=='1') 
            {*/
                return view('user.about');
/*
            } 
            else
            {

                $room = room::all();

                return view('user.home', compact('room'));

            }
        }
        else
        {
                            $room = room::all();

                return view('user.home', compact('room'));

        }
*/
    }

public function home()
    {
   return view('user.home');
    }

public function planning()
    {
   return view('user.planning');
    }


public function contact()
    {
   return view('user.contact');
    }

public function events()
    {
   return view('user.event');
    }
}
