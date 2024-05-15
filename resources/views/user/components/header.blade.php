<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="user/components/header.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .header {
      background-color: #333;
      color: #fff;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .home
    {
    text-decoration: none;
    color: #fff;
    }

    a:hover
    {
    color: #25b4b1;
    }

    li a:hover
    {
    color: #25b4b1;

    }

    .header .logo-container {
      display: flex; 
      align-items: center; 
    }

    .header img {
      width: 200px;
      height: 60px;
    }

    .header h2 {
      margin: 0;
    margin-left: 40px;
      cursor: pointer;
    }



    .nav {
      list-style-type: none;
      margin: 0;
      padding: 0;
    margin-right: 40px;
      display: flex;
    }

    .nav li {
      margin-right: 10px;
      padding: 8px;
      font-weight: bold;

    }

    .nav li:last-child {
      margin-right: 0;
    }

    .nav a {
      color: #fff;
      text-decoration: none;
    }

.connect
{
background-color: #e30613;
border-radius: 3px
}
  </style>
</head>
<body>

<header class="header">
  <div class="logo-container">
    <a href=""><img src="https://login.ehb.be/themes/ehb/images/logo-ehb-small.svg" alt="Logo" class="logo"></a>
   <a href="{{url('home')}}" class="home"> <h2 >Hackathon Desiderius</h2> </a>
  </div>
  <nav>
    <ul class="nav">
      <li><a href="{{url('events')}}">Evenementen</a></li>
      <li><a href="{{url('planning')}}">Planning</a></li>
      <li><a href="{{url('about')}}">Over ons</a></li>
      <li><a href="{{url('contact')}}">Contact</a></li>

      @if(Route::has('login'))
      @auth
      <x-app-layout>
      </x-app-layout>
      @else
      <li class="connect"><a href="{{ route('login') }}">Inloggen</a></li>
      <li class="connect"><a href="{{url('registration')}}">Registreren</a></li>
      @endauth
      @endif		
    </ul>
  </nav>
</header>

</body>
</html>
