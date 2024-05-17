<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord vergeten</title>
    <style>
        .forgot-password-card {
            width: 500px;
            padding: 50px;
            margin: 5vh auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .container
        {
        min-height: 100vh;

        }

        .forgot-password-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .forgot-password-card .message {
            margin-bottom: 20px;
            text-align: center;
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .error-message {
            color: #e30613;
            font-size: 14px;
            margin-top: 5px;
        }

        .button-container {
            margin-top: 20px;
            text-align: center;
        }

        button {
            padding: 10px 20px;
            background-color: #e30613;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #b5040a;
        }
    </style>
</head>
<body>

@include('user.components.header')

<div class="container">

    <div class="forgot-password-card">
        <h2>Wachtwoord vergeten?</h2>
        <div class="message">
            {{ __('Geen probleem. Laat ons gewoon je e-mailadres weten en we sturen je een e-mail met een link om je wachtwoord opnieuw in te stellen, zodat je een nieuwe kunt kiezen.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="button-container">
                <button type="submit">Email wachtwoordresetlink</button>
            </div>
        </form>
    </div>

</div>

@include('user.components.footer')


</body>
</html>
