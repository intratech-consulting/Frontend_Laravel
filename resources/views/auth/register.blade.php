<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gast Registreren</title>

    <style>


        .registration-card {
            width: 700px;
            padding: 50px;
            margin: 5vh 20vw;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .registration-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
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

        input[type="date"] {
            width: calc(100% - 22px); /* Adjust width for date input */
        }

        .error-message {
            color: #e30613;
            font-size: 14px;
            margin-top: 5px;
        }

        .button-container {
            margin-top: 20px;
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

<div class="registration-card">
    <h2>Gast Registreren</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div class="form-group">
                <label for="first_name">Voornaam</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="last_name">Achternaam</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div class="form-group">
                <label for="birthday">Geboortedatum</label>
                <input id="birthday" type="date" name="birthday" value="{{ old('birthday') }}">
            </div>

            <div class="form-group">
                <label for="telephone">Telefoon</label>
                <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div class="form-group">
                <label for="street">Straat</label>
                <input id="street" type="text" name="street" value="{{ old('street') }}">
            </div>

            <div class="form-group">
                <label for="house_number">Huisnummer</label>
                <input id="house_number" type="text" name="house_number" value="{{ old('house_number') }}">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div class="form-group">
                <label for="city">Stad</label>
                <input id="city" type="text" name="city" value="{{ old('city') }}">
            </div>

            <div class="form-group">
                <label for="zip">Postcode</label>
                <input id="zip" type="text" name="zip" value="{{ old('zip') }}">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div class="form-group">
                <label for="state">Provincie</label>
                <input id="state" type="text" name="state" value="{{ old('state') }}">
            </div>

            <div class="form-group">
                <label for="country">Land</label>
                <input id="country" type="text" name="country" value="{{ old('country') }}">
            </div>
        </div>



        <div class="form-group">
            <label for="invoice">Factuur</label>
            <select id="invoice" name="invoice">
                <option value="No">Nee</option>
                <option value="Yes">Ja</option>
            </select>
        </div>

        <div style="display: flex; justify-content: space-between; width: 100%;">
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Wachtwoord bevestigen</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>
        </div>

        <input type="hidden" name="user_role" value="individual">

        <div class="button-container">
            <button type="submit">Registreer</button>
        </div>
    </form>
</div>


@include('user.components.footer')

</body>
</html>
