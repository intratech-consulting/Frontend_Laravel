<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="user/contact.css">
  <style>
    .contact-section {
        max-width: 700px;
        margin: 5vh auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .contact-header {
        text-align: center;
        margin-bottom: 20px;
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
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        resize: none;
        height: 100px;
    }

    button {
        padding: 10px 20px;
        background-color: #e30613;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
        display: block;
        width: 100%;
        text-align: center;
    }

    button:hover {
        background-color: #b5040a;
    }
  </style>
</head>
<body>

@include('user.components.header')

<div class="contact-section">
  <header class="contact-header">
    <h2 class="text-lg font-medium text-gray-900">Contact</h2>
  </header>

  <form>
    <div class="form-group">
      <label for="voornaam">Voornaam</label>
      <input type="text" id="voornaam" name="voornaam" required>
    </div>
    <div class="form-group">
      <label for="achternaam">Achternaam</label>
      <input type="text" id="achternaam" name="achternaam">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="phone">Telefoon Nummer</label>
      <input type="text" id="phone" name="phone" required>
    </div>
    <div class="form-group">
      <label for="onderwerp">Onderwerp</label>
      <input type="text" id="onderwerp" name="onderwerp" required>
    </div>
    <div class="form-group">
      <label for="bericht">Bericht</label>
      <textarea id="bericht" name="bericht" required></textarea>
    </div>
    <button type="submit">Send</button>
  </form>
</div>

@include('user.components.footer')

</body>
</html>
