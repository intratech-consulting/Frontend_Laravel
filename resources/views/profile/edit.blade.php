<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//unpkg.com/alpinejs" defer></script>
    <title>Profile</title>
    <style>
        .registration-card {
            width: 700px;
            padding: 50px;
            margin: 5vh auto;
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

        .forgot {
            color: #333;
            text-decoration: none;
        }

        .div-forgot {
            margin-top: 5px;
        }

        input[type="date"] {
            width: calc(100% - 22px);
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
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #b5040a;
        }

        .flex.items-center.gap-4 {
            margin-top: 20px;
        }

        .profile-section,
        .update-password-section,
        .delete-account-section {
            max-width: 700px;
            margin: 5vh auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .profile-header,
        .update-password-header,
        .delete-account-header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

@include('user.components.header')

<main class="hackathon-container">
    <section class="space-y-6">
        <div class="registration-card">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </section>
</main>

@include('user.components.footer')

</body>
</html>
