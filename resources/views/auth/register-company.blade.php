<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bedrijf registreren</title>

    <style>
        .registration-card {
            width: 700px;
            padding: 50px;
            margin: 5vh auto;
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
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        select {
            width: 100%;
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

<div class="registration-card">
    <h2>Bedrijf registreren</h2>

    <form method="POST" action="{{ route('create_company') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Naam</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="telephone">Telefoon</label>
            <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}" required>
            @error('telephone')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

         <div class="form-group">
            <label for="logo">Logo</label>
            <input id="logo" type="file" name="logo" accept="image/*">
            <small class="error-message">{{ $errors->first('logo') }}</small>
             @error('logo')
             <span class="error-message">{{ $message }}</span>
             @enderror
        </div>

        <div class="form-group">
            <label for="street">Straat</label>
            <input id="street" type="text" name="street" value="{{ old('street') }}" required>
            @error('street')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="house_number">Huisnummer</label>
            <input id="house_number" type="text" name="house_number" value="{{ old('house_number') }}" required>
            @error('house_number')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="city">Stad</label>
            <input id="city" type="text" name="city" value="{{ old('city') }}" required>
            @error('city')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="zip">Postcode</label>
            <input id="zip" type="text" name="zip" value="{{ old('zip') }}" required>
            @error('zip')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="state">Provincie</label>
            <input id="state" type="text" name="state" value="{{ old('state') }}" required>
            @error('state')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="country">Land</label>
            <input id="country" type="text" name="country" value="{{ old('country') }}" required>
            @error('country')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="sponsor">Sponsor</label>
            <input id="sponsor" name="sponsor" type="checkbox" value="1" {{ old('sponsor') ? 'checked' : '' }}>
            <small class="error-message">{{ $errors->first('sponsor') }}</small>
        </div>

        <div class="form-group">
            <label for="invoice">Iban</label>
            <input id="invoice" type="text" name="invoice" value="{{ old('invoice') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Wachtwoord</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Wachtwoord bevestigen</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            @error('password_confirmation')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="button-container">
            <button type="submit">Registreer</button>
        </div>
    </form>
</div>


    @include('user.components.footer')

</body>
</html>
