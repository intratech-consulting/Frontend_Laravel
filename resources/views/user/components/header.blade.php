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



    .connect {
        all: unset;
        background-color: #e30613;
        border-radius: 3px;
        padding: 8px 12px;
        cursor: pointer;
        border: none;
        color: white;
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

    .nav a:hover {
        background-color: #25b4b1;
        color: #fff;
    }

    .nav button:hover {
        background-color: #25b4b1;
        color: #fff;
    }
  </style>
</head>
<body>

<header class="header">

  <div class="logo-container">
    <a href="{{ url('home') }}"><img src="https://login.ehb.be/themes/ehb/images/logo-ehb-small.svg" alt="Logo" class="logo"></a>
    <a href="{{ url('home') }}" class="home"><h2>Hackathon Desiderius</h2></a>
  </div>


  <nav>
    <ul class="nav">
        <li><a href="{{ url('events') }}">Evenementen</a></li>

        <li><a href="{{ url('planning') }}">Planning</a></li>

        @if (Route::has('login'))
            @auth('web')
                <li class="user-menu">

                    <div class="user-actions">
                        <li><a href="{{ url('/mijnReservaties') }}">Mijn reservaties</a></li>
                        <li><a href="{{ url('/profile') }}">Profiel</a></li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="connect">Uitloggen</button>
                        </form>
                    </div>
                </li>
            @elseauth('company')
                <li class="user-menu">
                    <div class="user-actions">
                        <li><a href="{{ url('/company-profile') }}">Profiel</a></li>
                        <li><button type="button" class="connect" onclick="window.location.href='{{ route('register_speaker') }}'">Registreer Werknemers</button></li>
                        <form method="POST" action="{{ route('company.logout') }}">
                            @csrf
                            <button type="submit" class="connect">Uitloggen</button>
                        </form>
                    </div>
                </li>
            @else
                <li><button type="button" class="connect" onclick="window.location.href='{{ route('login') }}'">Inloggen</button></li>
                <li><button type="button" class="connect" onclick="window.location.href='{{ url('registration') }}'">Registreren</button></li>
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
