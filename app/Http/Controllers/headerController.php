<?php

namespace App\Http\Controllers;

//use App\Http\Controllers\RecievePlanningController;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


use Illuminate\Http\Request;
use App\Services\RabbitMQSendToExhangeService;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;





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

 if (Auth::check()) {
        $user = Auth::user(); 
        $calendar_link = $user->calendar_link;
    } else {
        // default calendar link
        $calendar_link = "https://calendar.google.com/calendar/u/0/embed?src=7385a4a57d6b8bc946bd2d8daee469edd3098611077851dd48530aeafd315b80@group.calendar.google.com&ctz=Europe/Brussels";
    }

    return view('user.planning', ['calendar_link' => $calendar_link]);

    }


public function contact()
    {
   return view('user.contact');
    }

public function events()
    {

        // Activate the planning RabbitMQ consumer
        /* $consumer = new RecievePlanningController();
            $consumer->consume();
        */

        // $event =  event::all();
        $event = Event::with(['users', 'companies'])->get();

        return view('user.event', compact('event'));
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
