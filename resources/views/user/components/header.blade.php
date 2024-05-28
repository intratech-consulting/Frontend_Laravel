<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="user/components/header.css">
  <style>
    body {
      margin: 0;
      font-family: 'Manrope', Arial, sans-serif;
    }

    .header {
      background-color: #333;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .flash-success {
        padding: 20px;
        background-color: #33a43a;
        color: white;
        margin: 20px 0px;
        border-radius: 4px;
    }

    .flash-error {
        padding: 20px;
        background-color: rgb(235, 47, 47);
        color: white;
        margin: 20px 0px;
        border-radius: 4px;
    }

    .flash-remove{
        padding: 20px;
        background-color: #FF5733;
        color: white;
        margin: 20px 0px;
        border-radius: 4px;
    }

    .home {
      text-decoration: none;
      color: #fff;
    }

    a:hover {
      color: #25b4b1;
    }

    .header .logo-container {
      display: flex;
      align-items: center;
    }

    .header img {
      width: 150px;
      height: auto;
    }

    .header h2 {
      margin: 0;
      margin-left: 20px;
      cursor: pointer;
      font-size: 24px;
    }

    .nav {
      list-style-type: none;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
    }

    .nav li {
      margin: 0 10px;
    }

    .nav a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 3px;
    }

    .nav a:hover {
      background-color: #25b4b1;
      color: #fff;
    }

    .connect a {
      background-color: #e30613;
      border-radius: 3px;
      padding: 8px 12px;
    }

    .logout-button {
      background-color: #e30613;
      color: #fff;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 3px;
      cursor: pointer;
    }

    .logout-button:hover {
      background-color: #b5040a;
    }

    .user-menu {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-avatar {
      height: 40px;
      width: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .user-name {
      font-weight: 500;
    }

    .user-actions {
      display: flex;
      gap: 10px;
    }

    .user-actions a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
    }
  </style>
</head>
<body>

<header class="header">

  <div class="logo-container">
    <a href="{{ url('/') }}"><img src="https://login.ehb.be/themes/ehb/images/logo-ehb-small.svg" alt="Logo" class="logo"></a>
    <a href="{{ url('home') }}" class="home"><h2>Hackathon Desiderius</h2></a>
  </div>


  <nav>
    <ul class="nav">
        <li><a href="{{ url('events') }}">Evenementen</a></li>

        <li><a href="{{ url('planning') }}">Planning</a></li>

        @if (Route::has('login'))
            @auth('web')
                <li class="user-menu">
                    <span class="user-name">{{ Auth::guard('web')->user()->name }}</span>
                    <div class="user-actions">
                        <a href="{{ url('/mijnReservaties') }}">Mijn reservaties</a>
                        <a href="{{ url('/profile') }}">Profiel</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-button">Uitloggen</button>
                        </form>
                    </div>
                </li>
            @elseauth('company')
                <li class="user-menu">
                    <span class="user-name">{{ Auth::guard('company')->user()->name }}</span>
                    <div class="user-actions">
                        <a href="{{ url('/company-profile') }}">Profiel</a>
                        <a href="{{ url('/register-speaker') }}">Registreer Werknemers</a>
                        <form method="POST" action="{{ route('company.logout') }}">
                            @csrf
                            <button type="submit" class="logout-button">Uitloggen</button>
                        </form>
                    </div>
                </li>
            @else
                <li class="connect"><a href="{{ route('login') }}">Inloggen</a></li>
                <li class="connect"><a href="{{ url('registration') }}">Registreren</a></li>
            @endauth
        @endif
    </ul>
</nav>
</header>

<div class="flex-grow ml-56 p-8">
    @if (session('success'))
        <div class="flash-success">
            {{session('success')}}
        </div>
    @endif

    @if (session('remove'))
        <div class="flash-remove">
            {{session('remove')}}
        </div>
    @endif

    @if (session('error'))
        <div class="flash-error">
            {{session('error')}}
        </div>
    @endif
</div>
</body>
</html>
