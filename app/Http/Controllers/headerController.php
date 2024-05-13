<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RecievePlanningController;
use Illuminate\Routing\Controller;



 class headerController extends Controller
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

// Activate the planning RabbitMQ consumer
    $consumer = new RecievePlanningController();
    $consumer->consume();

     $event = event::all();
   return view('user.event', compact('events'));
    }



public function registration()
    {
   return view('auth.role-register');
    }

public function show_events()
    {
   return view('user.event-create');
    }
}
