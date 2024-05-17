<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="user/contact.css">
  <style>
    .div {
      border-radius: 10px;
      box-shadow: 0px 0px 60px 30px rgba(0, 0, 0, 0.03);
      background-color: #fff;
      padding: 10px 50px 10px 10px;
    }

    .container {
        margin: 0 auto;
        min-height: 100vh;
        padding: 20px;
    }

    @media (max-width: 991px) {
      .div {
        padding-right: 20px;
      }
    }

    .div-2 {
      gap: 20px;
      display: flex;
    }

    @media (max-width: 991px) {
      .div-2 {
        flex-direction: column;
        align-items: stretch;
        gap: 0px;
      }
    }

    .column {
      display: flex;
      flex-direction: column;
      line-height: normal;
      width: 45%;
      margin-left: 0px;
    }

    @media (max-width: 991px) {
      .column {
        width: 100%;
      }
    }

    .div-3 {
      border-radius: 10px;
      background-color: rgba(1, 28, 43, 1);
      display: flex;
      flex-grow: 1;
      flex-direction: column;
      font-size: 16px;
      color: #fff;
      font-weight: 400;
      width: 100%;
      padding: 70px 45px;
    }

    @media (max-width: 991px) {
      .div-3 {
        max-width: 100%;
        margin-top: 40px;
        padding: 0 20px;
      }
    }

    .div-4 {
      align-self: start;
      display: flex;
      gap: 20px;
    }

    .img {
      aspect-ratio: 1;
      object-fit: auto;
      object-position: center;
      width: 24px;
    }

    .div-5 {
      font-family: Poppins, sans-serif;
      flex-grow: 1;
      flex-basis: auto;
      margin: auto 0;
    }

    .div-6 {
      display: flex;
      margin-top: 50px;
      gap: 20px;
      white-space: nowrap;
    }

    @media (max-width: 991px) {
      .div-6 {
        margin-top: 40px;
        white-space: initial;
      }
    }

    .div-7 {
      font-family: Poppins, sans-serif;
      flex-grow: 1;
      flex-basis: auto;
    }

    .div-8 {
      background-color: rgba(255, 249, 249, 0.13);
      border-radius: 50%;
      align-self: end;
      margin-top: 271px;
      width: 138px;
      height: 138px;
    }

    @media (max-width: 991px) {
      .div-8 {
        margin-top: 40px;
      }
    }

    .column-2 {
      display: flex;
      flex-direction: column;
      line-height: normal;
      width: 55%;
      margin-left: 20px;
    }

    @media (max-width: 991px) {
      .column-2 {
        width: 100%;
      }
    }

    .div-9 {
      display: flex;
      margin-top: 55px;
      margin-left: 90px;
      flex-direction: column;
      font-size: 12px;
      color: #8d8d8d;
      font-weight: 500;
      line-height: 167%;
    }

    @media (max-width: 991px) {
      .div-9 {
        max-width: 100%;
        margin-top: 40px;
        margin-left: 0px;
      }
    }

    .div-10 {
      display: flex;
      gap: 20px;
      white-space: nowrap;
    }

    @media (max-width: 991px) {
      .div-10 {
        flex-wrap: wrap;
        white-space: initial;
      }
    }

    .div-11 {
      align-self: start;
      display: flex;
      flex-direction: column;
      flex: 1;
      flex-grow: 1;
      flex-basis: 0;
      width: fit-content;
    }

    @media (max-width: 991px) {
      .div-11 {
        white-space: initial;
      }
    }

    .div-12 {
      font-family: Poppins, sans-serif;
    }

    .form-input {
      display: flex;
      flex-direction: column;
      position: relative;
      margin-top: 20px;
      border-radius: 3px;
      border-width: 1px;
      border-style: solid;
      border-color: #ccc;
      padding: 10px;
    }

    .div-13 {
      display: flex;
      flex-direction: column;
      flex: 1;
      flex-grow: 1;
      flex-basis: 0;
      width: fit-content;
    }

    @media (max-width: 991px) {
      .div-13 {
        white-space: initial;
      }
    }

    .div-14 {
      font-family: Poppins, sans-serif;
    }

    .div-15 {
      display: flex;
      margin-top: 49px;
      gap: 20px;
    }

    @media (max-width: 991px) {
      .div-15 {
        flex-wrap: wrap;
        margin-top: 40px;
      }
    }

    .div-16 {
      display: flex;
      flex-direction: column;
      white-space: nowrap;
      flex: 1;
      flex-grow: 1;
      flex-basis: 0;
      width: fit-content;
    }

    @media (max-width: 991px) {
      .div-16 {
        white-space: initial;
      }
    }

    .div-17 {
      font-family: Poppins, sans-serif;
    }

    .div-18 {
      display: flex;
      flex-direction: column;
      flex: 1;
      flex-grow: 1;
      flex-basis: 0;
      width: fit-content;
    }

    .div-19 {
      font-family: Poppins, sans-serif;
    }

    .div-20 {
      font-family: Poppins, sans-serif;
      margin-top: 36px;
    }

    @media (max-width: 991px) {
      .div-20 {
        max-width: 100%;
      }
    }

    .div-21 {
      font-family: Poppins, sans-serif;
      margin-top: 36px;
    }

    @media (max-width: 991px) {
      .div-21 {
        max-width: 100%;
      }
    }

    .form-text-area {
      display: flex;
      flex-direction: column;
      position: relative;
      margin-top: 20px;
      border-radius: 3px;
      border-width: 1px;
      border-style: solid;
      border-color: #ccc;
      padding: 10px;
    }

    .div-22 {
      border-radius: 5px;
      box-shadow: 0px 0px 14px 0px rgba(0, 0, 0, 0.12);
      background-color: #011c2a;
      align-self: end;
      color: #fff;
      white-space: nowrap;
      text-align: center;
      justify-content: center;
      margin: 70px 77px 0 0;
      padding: 15px 48px;
      font: 16px Poppins, sans-serif;
    }

    @media (max-width: 991px) {
      .div-22 {
        white-space: initial;
        margin: 40px 10px 0 0;
        padding: 0 20px;
      }
    }

  </style>
</head>
<body>

@include('user.components.header')

<div class="container">

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

</div>

@include('user.components.footer')

</body>
</html>
