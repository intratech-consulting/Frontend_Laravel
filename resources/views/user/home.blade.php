<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="user/home.css">
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
