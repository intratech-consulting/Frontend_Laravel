<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="user/components/header.css">
</head>
<body>

<header class="header">
  <div class="logo-container">
    <a href=""><img src="https://login.ehb.be/themes/ehb/images/logo-ehb-small.svg" alt="Logo" class="logo"></a>
   <a href="{{url('home')}}" class="home"> <h2 >Hackathon Desiderius</h2> </a>
  </div>
  <nav>
    <ul class="nav">
      <li><a href="{{url('planning')}}">Planning</a></li>
      <li><a href="{{url('about')}}">About</a></li>
      <li><a href="{{url('contact')}}">Contact</a></li>
    </ul>
  </nav>
</header>

</body>
</html>
