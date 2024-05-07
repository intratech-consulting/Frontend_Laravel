<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About us</title>
  <link rel="stylesheet" href="user/about.css">
</head>
<body>

@include('user.components.header')


<section class="about-us-section">
  <div class="about-us-container">
    <div class="about-us-content">
      <div class="about-us-image-column">
        <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="About Us" class="about-us-image" />
      </div>
      <div class="about-us-text-column">
        <div class="about-us-text-container">
          <h2 class="about-us-title">ABOUT US</h2>
          <p class="about-us-description">
At Hackathon Desiderius, we're dedicated to fostering a thriving community of innovators, problem solvers, and technology enthusiasts. Our platform serves as a hub where individuals and companies come together to explore, collaborate, and create groundbreaking solutions through hackathons.
            <br />
            <br />
           With a passion for technology and a commitment to excellence, we strive to provide an inclusive and supportive environment where everyone can unleash their creativity and make a meaningful impact.
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="section-divider"></div>
  <div class="mission-vision-section">
    <div class="mission-vision-content">
      <div class="mission-vision-text-column">
        <div class="mission-vision-text-container">
          <h2 class="mission-vision-title">Mission and Vision</h2>
          <p class="mission-vision-description">
Our mission is to democratize innovation by providing a platform where anyone, regardless of background or expertise, can participate, learn, and contribute to the advancement of technology.            <br />
            <br />
We envision a world where hackathons serve as catalysts for positive change, driving innovation, fostering collaboration, and empowering individuals and organizations to solve complex challenges and shape the future of technology.
          </p>
        </div>
      </div>
      <div class="mission-vision-image-column">
        <img src="https://images.unsplash.com/photo-1591115765373-5207764f72e7?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Mission and Vision" class="about-us-image" />
      </div>
    </div>
  </div>
</section>


@include('user.components.footer')


</body>
</html>
