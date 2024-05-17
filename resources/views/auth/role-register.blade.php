<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registraties</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .container
        {
        min-height: 100vh;

        }

        .card {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .navigation-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
        }

        .navigation-item {
            text-align: center;
        }

        .navigation-item img {
            width: 100%;
            height: 30vh;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }



        .navigation-item img:hover {
            transform: scale(1.05);
        }

        .navigation-link {
            display: block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .navigation-link:hover {
            background-color: #555;

        }

        .long-image {
            margin-top: 20px;
            text-align: center;
        }

        .long-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .long-image img:hover {
            transform: scale(1.05);
        }



  
    </style>
</head>
<body>
@include('user.components.header')

<div class="container">
    <div class="card">
        <div class="navigation-grid">
            <div class="navigation-item">
                <a href="{{ route('register') }}">
                    <img src="https://images.unsplash.com/photo-1572021335469-31706a17aaef?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Employee">
                </a>
                <a href="{{ route('register') }}" class="navigation-link">Gast/a>
            </div>
            <div class="navigation-item">
            <a href="{{url('register_company')}}">
                <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Company">
                </a>
            <a href="{{url('register_company')}}" class="navigation-link">Bedrijf</a>
            </div>
        </div>
    
    </div>

</div>
@include('user.components.footer')

</body>
</html>
