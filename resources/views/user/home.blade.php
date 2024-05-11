<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="{{ asset('user/home.css') }}">
  <style>
    .hackathon-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    padding: 30px;
    }

    a {
      text-decoration: none;
    }

    .hero-section {
      position: relative;
      display: flex;
      max-width: 100%;
    }

    .hero-content {
      align-self: start;
      z-index: 10;
      display: flex;
      margin-top: 79px;
      flex-direction: column;
      gap: 20px;
      margin-right: 40px;
      flex-grow: 1;
      flex-basis: 0;
      width: fit-content;
    }

    .hero-title {
      color: var(--Black, #121212);
      letter-spacing: 0.96px;
      font: 700 48px/51px Manrope, sans-serif;
    }

    .hero-description {
      color: var(--Grey, #696969);
      letter-spacing: 0.54px;
      margin-top: 26px;
      font: 400 18px/22px Manrope, sans-serif;
    }

    .hero-cta {
      border-radius: 14px;
      background-color: #e30613;
      margin-top: 31px;
      width: 40%;
      max-width: 100%;
      align-items: center;
      color: #fff;
      justify-content: center;
      padding: 20px 60px;
      font: 500 17px Poppins, sans-serif;
      display: flex;
      justify-content: center;
    }

    .hero-image-container {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      position: relative;
      min-height: 671px;
      align-items: end;
      flex-grow: 1;
      flex-basis: 0;
      width: fit-content;
      padding: 16px 60px 33px;
    }

    .hero-background {
      position: absolute;
      z-index: -10;
      inset: 0;
      width: 100%;
      object-fit: cover;
      object-position: center;
    }

    .hero-image {
      aspect-ratio: 0.94;
      object-fit: contain;
      object-position: center;
      width: 100%;
      margin: 79px 40px 0 0;
    }

    .features-section {
      background-color: #fff;
      display: flex;
      margin: 115px auto 0;
      width: 100%;
      align-items: center;
      justify-content: center;
    }

    .features-container {
      display: flex;
      width: 1073px;
      max-width: 100%;
      flex-direction: column;
      gap: 20px;
      margin: 0 81px 32px 81px;
    }

    .features-title {
      color: var(--Black, #121212);
      text-align: center;
      letter-spacing: 0.72px;
      align-self: center;
      margin-top: -78px;
      font: 700 48px/51px Manrope, sans-serif;
    }

    .features-list {
      display: flex;
      margin-top: 96px;
      gap: 20px;
    }

    .feature-item {
      align-self: start;
      display: flex;
      flex-direction: column;
      gap: 20px;
      flex: 1;
    }

    .feature-divider {
      background-color: #e30613;
      height: 3px;
    }

    .feature-content {
      display: flex;
      width: 173px;
      max-width: 100%;
      flex-direction: column;
      gap: 11px;
      margin: 43px 0 0 46px;
    }

    .feature-icon {
      aspect-ratio: 1;
      object-fit: auto;
      object-position: center;
      width: 100px;
      fill: #e30613;
    }

    .feature-name {
      color: var(--Black, #121212);
      letter-spacing: 0.54px;
      margin-top: 34px;
      font: 700 18px/156% Manrope, sans-serif;
    }

    .feature-description {
      color: var(--Grey, #696969);
      letter-spacing: 0.36px;
      margin-top: 11px;
      font: 400 12px/133% Manrope, sans-serif;
    }

    .cta-section {
      background-color: #fafafa;
      display: flex;
      margin-top: 89px;
      width: 100%;
      align-items: center;
      font-size: 22px;
      color: var(--Black, #121212);
      letter-spacing: 0.44px;
      justify-content: center;
    }

    .cta-container {
      display: flex;
      width: 100%;
      max-width: 1650px;
      gap: 20px;
      justify-content: space-between;
      margin: 43px 0 54px;
    }

    .cta-content {
      display: flex;
      flex-direction: column;
      align-items: start;
      gap: 14px;
    }

    .cta-title {
      letter-spacing: 0.81px;
      align-self: stretch;
      font: 700 54px/70px Manrope, sans-serif;
    }

    .cta-description {
      color: var(--Grey, #696969);
      font-family: Manrope, sans-serif;
      font-weight: 400;
      line-height: 30px;
      align-self: stretch;
      margin: 26px 81px 0 0;
    }

    .cta-benefit {
      justify-content: center;
      display: flex;
      margin-top: 26px;
      gap: 12px;
      font-weight: 600;
      line-height: 91%;
    }

    .cta-benefit-icon {
      aspect-ratio: 1;
      object-fit: auto;
      object-position: center;
      width: 24px;
    }

    .cta-benefit-text {
      font-family: Manrope, sans-serif;
      margin: auto 0;
    }

    .cta-button {
      justify-content: center;
      border-radius: 10px;
      background-color: #e30613;
      margin-top: 146px;
      color: var(--White, #fff);
      letter-spacing: 0.54px;
      padding: 16px 36px;
      font: 500 18px/111% Manrope, sans-serif;
    }

  </style>
</head>
<body>

@include('user.components.header')

  <main class="hackathon-container">
    <section class="hero-section">
      <div class="hero-content">
        <h1 class="hero-title">Test your knowledge with our hackathons</h1>
        <p class="hero-description">
          It's important to take the time to know your skills and improve them with projects and learning how to work in a team.
        </p>
        <a href="{{url('about')}}" class="hero-cta">About us</a>
      </div>
      <div class="hero-image-container">
        <img src="https://images.unsplash.com/photo-1582192730841-2a682d7375f9?q=80&w=2748&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="hero-background" />
      </div>
    </section>
    
    <section class="features-section">
      <div class="features-container">
        <h2 class="features-title">80+ Hackathon Horizons: Explore, Innovate, Disrupt!</h2>
        <div class="features-list">
          <div class="feature-item">
            <div class="feature-divider"></div>
            <div class="feature-content">
              <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/8eb0a50c093d6da24be5fc6b9aa04f8232d8f64f6055b4637ec0c8a840d22705?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="feature-icon" />
              <h3 class="feature-name">Explore</h3>
              <p class="feature-description">Venture into new tech realms.</p>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-divider"></div>
            <div class="feature-content">
              <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/7e863eca1c6abb33c8a0fd00da5f97d4db6b4e0d7e581edae9e87bb4cae589b3?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="feature-icon" />
              <h3 class="feature-name">Innovate</h3>
              <p class="feature-description">Pioneer game-changing solutions.</p>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-divider"></div>
            <div class="feature-content">
              <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/316b7e3118b8bd7d4b27af7881b2066f8e50908c608de5ba2f2f8e85e3c691ad?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="feature-icon" />
              <h3 class="feature-name">Disrupt</h3>
              <p class="feature-description">Challenge norms with bold ideas.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
     
    <section class="cta-section">
      <div class="cta-container">
        <div class="cta-content">
          <h2 class="cta-title">Join the Hackathon Community Today!</h2>
          <p class="cta-description">
            Unlock endless possibilities by becoming a member of our vibrant hackathon community. Whether you're an individual coder eager to participate or a company ready to host an innovative event, we provide the platform to connect, collaborate, and create. Join us now and be part of something extraordinary!
          </p>
          <div class="cta-benefit">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/9ff9861741df2b7b331c157420c56522fa59c63a59ee4dd09b1fb2ef6ffc238c?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="cta-benefit-icon" />
            <span class="cta-benefit-text">Connect with tech enthusiasts</span>
          </div>
          <div class="cta-benefit">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/9ff9861741df2b7b331c157420c56522fa59c63a59ee4dd09b1fb2ef6ffc238c?apiKey=faa644a41149444c9c3e35e1f35c0dc5&" alt="" class="cta-benefit-icon" />
            <span class="cta-benefit-text">Develop skills and network</span>
          </div>
          <a href="{{url('contact')}}" class="cta-button">Contact us</a>
        </div>
      </div>
    </section>
  </main>

@include('user.components.footer')

</body>
</html>
