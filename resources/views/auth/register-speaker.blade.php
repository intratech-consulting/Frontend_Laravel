<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Speaker</title>

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
    <h2>Register Page Speaker</h2>

    <form method="POST" action="{{ route('register_test') }}">
        @csrf

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="telephone">Telephone</label>
            <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}">
        </div>

        <div class="form-group">
            <label for="birthday">Birthday</label>
            <input id="birthday" type="date" name="birthday" value="{{ old('birthday') }}">
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <input id="country" type="text" name="country" value="{{ old('country') }}">
        </div>

        <div class="form-group">
            <label for="state">State</label>
            <input id="state" type="text" name="state" value="{{ old('state') }}">
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <input id="city" type="text" name="city" value="{{ old('city') }}">
        </div>

        <div class="form-group">
            <label for="zip">ZIP Code</label>
            <input id="zip" type="text" name="zip" value="{{ old('zip') }}">
        </div>

        <div class="form-group">
            <label for="street">Street</label>
            <input id="street" type="text" name="street" value="{{ old('street') }}">
        </div>

        <div class="form-group">
            <label for="house_number">House Number</label>
            <input id="house_number" type="text" name="house_number" value="{{ old('house_number') }}">
        </div>

        <div class="form-group">
            <label for="invoice">Invoice</label>
            <select id="invoice" name="invoice">
                <option value="Yes">Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <div class="button-container">
            <button type="submit">Register</button>
        </div>
    </form>
</div>


@include('user.components.footer')

</body>
</html>
