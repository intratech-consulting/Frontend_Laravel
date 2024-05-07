<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="user/contact.css">
</head>
<body>

@include('user.components.header')


<div class="div">
  <div class="div-2">
    <div class="column">
      <div class="div-3">
        <div class="div-4">
          <img loading="lazy" src="https://cdn.builder.io/api/v1/image/assets/TEMP/02fac0a126519b919d8dfd6271a983dbd20aa4d5acedd9bba073ad37c1d23f0e?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" class="img" />
          <div class="div-5">+32 485 56 71 81</div>
        </div>
        <div class="div-6">
          <img loading="lazy" src="https://cdn.builder.io/api/v1/image/assets/TEMP/f33a12af71453b99485ac9031fecc220eb9409e1999afd1ffbb8eef1e6b8f8f2?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" class="img" />
          <div class="div-7">Contact@test.com</div>
        </div>
        <div class="div-8"></div>
      </div>
    </div>
    <div class="column-2">
      <form class="div-9">
        <div class="div-10">
          <div class="div-11">
            <div class="div-12">Voornaam</div>
            <input type="text" placeholder="" name="voornaam" class="form-input" data-el="form-input" required />
          </div>
          <div class="div-13">
            <div class="div-14">Achternaam</div>
            <input type="text" placeholder="" name="achternaam" class="form-input" data-el="form-input-2" />
          </div>
        </div>
        <div class="div-15">
          <div class="div-16">
            <div class="div-17">Email</div>
            <input type="email" placeholder="" name="email" class="form-input" data-el="form-input-3" required />
          </div>
          <div class="div-18">
            <div class="div-19">Telefoon Nummer</div>
            <input type="text" placeholder="" name="phone" class="form-input" data-el="form-input-4" required />
          </div>
        </div>
        <div class="div-20">Onderwerp</div>
        <input type="text" placeholder="" name="onderwerp" class="form-input" data-el="form-input-5" required />
        <div class="div-21">Bericht</div>
        <textarea placeholder="" name="bericht" class="form-text-area" data-el="form-text-area" required></textarea>
        <button type="submit" class="div-22">Send</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
