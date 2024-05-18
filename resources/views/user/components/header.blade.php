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

    .connect a:hover {
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
      align-items: center;
      gap: 10px;
    }

    .user-actions a,
    .user-actions form button {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 12px;
      background-color: #e30613;
      border-radius: 3px;
      border: none;
      cursor: pointer;
      display: inline-block;
      font-family: 'Manrope', Arial, sans-serif;
    }

    .user-actions a:hover,
    .user-actions form button:hover {
      background-color: #b5040a;
    }

    .user-actions form {
      margin: 0;
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
      <li><a href="{{ url('about') }}">Over ons</a></li>
      <li><a href="{{ url('contact') }}">Contact</a></li>
      @if(Route::has('login'))
        @auth
        <li class="user-menu">
          <span class="user-name">{{ Auth::user()->name }}</span>
          <div class="user-actions">
            <a href="{{ url('/profile') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
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

</body>
</html>
