<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="{{ asset('/user/home.css') }}">
  <style>
    body {
      margin: 0;
      font-family: Manrope, sans-serif;
    }

    a {
      text-decoration: none;
    }

    .hackathon-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
      padding: 30px;
    }

    .hero-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 40px;
      padding: 20px;
      background: #f0f0f0;
    }

    .hero-content {
      flex: 1;
    }

    .hero-title {
      color: #121212;
      font-weight: 700;
      font-size: 48px;
      margin: 0 0 20px;
    }

    .hero-description {
      color: #696969;
      font-weight: 400;
      font-size: 18px;
      margin: 0 0 20px;
    }

    .hero-cta {
      display: inline-block;
      padding: 15px 30px;
      border-radius: 10px;
      background-color: #e30613;
      color: #fff;
      font-weight: 500;
      font-size: 18px;
    }

    .hero-section {
      display: flex;
      max-width: 100%;
    }

    .hero-content {
      align-self: start;
      z-index: 10;
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-right: 40px;
      flex-grow: 1;
      flex-basis: 0;
    }

    .hero-image-container {
      display: flex;
      flex-direction: column;
      min-height: 671px;
      align-items: flex-end; /* Adjusted alignment */
      padding: 16px 60px 33px;
    }

    .hero-image {
      width: 100%;
      max-width: 600px; /* Adjust the max-width as needed */
      margin-top: 79px;
    }

    .features-section {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
        }

        .features-container {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .features-title {
            color: #121212;
            font-weight: 700;
            font-size: 36px;
        }

        .features-list {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .feature-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }

        .feature-divider {
            width: 60px;
            height: 3px;
            background-color: #e30613;
        }

        .feature-content {
            text-align: center;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
        }

        .feature-name {
            color: #121212;
            font-weight: 700;
            font-size: 18px;
            margin: 20px 0 10px;
        }

        .feature-description {
            color: #696969;
            font-weight: 400;
            font-size: 14px;
        }

    

    .cta-section {
      background-color: #fafafa;
      padding: 60px 20px;
      text-align: center;
    }

    .cta-container {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 40px;
    }

    .cta-title {
      color: #121212;
      font-weight: 700;
      font-size: 54px;
    }

    .cta-description {
      color: #696969;
      font-weight: 400;
      font-size: 18px;
      margin: 0 0 20px;
    }

    .cta-benefit {
      display: flex;
      justify-content: center;
      gap: 12px;
      font-weight: 600;
    }

    .cta-benefit-icon {
      width: 24px;
      height: 24px;
    }

    .cta-button {
      display: inline-block;
      padding: 15px 30px;
      border-radius: 10px;
      background-color: #e30613;
      color: #fff;
      font-weight: 500;
      font-size: 18px;
    }
  </style>
</head>
<body>

@include('user.components.header')

<main class="hackathon-container">
<section class="hero-section">
  <div class="hero-content">
    <h1 class="hero-title">Probeer je kennis uit met onze hackathons.</h1>
    <p class="hero-description">Het is belangrijk om de tijd te nemen om je vaardigheden te leren kennen en ze te verbeteren met projecten en door te leren hoe je in een team kunt werken.</p>
    <a href="{{url('about')}}" class="hero-cta">Over ons</a>
  </div>
  <div class="hero-image-container">
    <img src="https://images.unsplash.com/photo-1582192730841-2a682d7375f9?q=80&w=2748&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="hero-image" />
  </div>
</section>

  <section class="features-section">
    <div class="features-container">
      <h2 class="features-title">80+ Hackathon Horizons: Verken, Innoveer, Verstoor!</h2>
      <div class="features-list">
        <div class="feature-item">
          <div class="feature-divider"></div>
          <div class="feature-content">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/8eb0a50c093d6da24be5fc6b9aa04f8232d8f64f6055b4637ec0c8a840d22705?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="feature-icon" />
            <h3 class="feature-name">Verkennen</h3>
            <p class="feature-description">Waag je in nieuwe technologische gebieden.</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-divider"></div>
          <div class="feature-content">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/7e863eca1c6abb33c8a0fd00da5f97d4db6b4e0d7e581edae9e87bb4cae589b3?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="feature-icon" />
            <h3 class="feature-name">Innoveren</h3>
            <p class="feature-description">Baanbrekende oplossingen pionieren.</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-divider"></div>
          <div class="feature-content">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/316b7e3118b8bd7d4b27af7881b2066f8e50908c608de5ba2f2f8e85e3c691ad?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="feature-icon" />
            <h3 class="feature-name">Verstoren</h3>
            <p class="feature-description">Normen uitdagen met gedurfde ideeën.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="cta-container">
      <div class="cta-content">
        <h2 class="cta-title">Doe vandaag nog mee met de hackathon-community!</h2>
        <p class="cta-description">
          Ontgrendel eindeloze mogelijkheden door lid te worden van onze levendige hackathon-community. Of je nu een individuele programmeur bent die graag wil deelnemen of een bedrijf dat klaar staat om een innovatief evenement te organiseren, wij bieden het platform om te verbinden, samen te werken en te creëren. Sluit je nu aan en maak deel uit van iets buitengewoons!
        </p>
        <div class="cta-benefit">
          <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/9ff9861741df2b7b331c157420c56522fa59c63a59ee4dd09b1fb2ef6ffc238c?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="cta-benefit-icon" />
          <span class="cta-benefit-text">Verbind met technologieliefhebbers.</span>
        </div>
        <div class="cta-benefit">
          <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/9ff9861741df2b7b331c157420c56522fa59c63a59ee4dd09b1fb2ef6ffc238c?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="cta-benefit-icon" />
          <span class="cta-benefit-text">Ontwikkel vaardigheden en bouw een netwerk op.</span>
        </div>
        <a href="{{ url('registration') }}" class="cta-button">Registreren</a>
      </div>
    </div>
  </section>
</main>

@include('user.components.footer')

</body>
</html>
