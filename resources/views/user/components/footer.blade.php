<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user/components/footer.css">
    <style>


    .footer {
      left: 0;
      bottom: 0;
      width: 100%; /* Occupy full width of the viewport */
      background-color: #333;
      color: #fff;
      padding: 20px 0; /* Increased padding top and bottom */
      text-align: center;
    }

    .footer-content {
      display: flex;
      align-items: center;
      justify-content: center; /* Center horizontally */
    }

    .footer-content span {
      margin: 0 auto; /* Center the copyright text */
    }

    .footer-content a {
      margin-right: 40px;
      color: #fff;
    }

    .footer-content a:hover {
      color: #25b4b1;
    }


    </style>
</head>
<body>

<footer class="footer">
  <div class="footer-content">
    <span>&copy; Erasmushogeschool Brussel 2024</span>

    <a href="{{url('privacy')}}">Privacy</a>
  </div>
</footer>

</body>
</html>
