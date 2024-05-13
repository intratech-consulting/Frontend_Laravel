<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Over ons</title>
  <link rel="stylesheet" href="/user/about.css">
  <style>
    .about-us-section {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 52px;
    }

    .about-us-container {
      width: 100%;
      max-width: 1036px;
    }

    @media (max-width: 991px) {
      .about-us-container {
        max-width: 100%;
      }
    }

    .about-us-content {
      display: flex;
      gap: 20px;
    }

    @media (max-width: 991px) {
      .about-us-content {
        flex-direction: column;
        align-items: stretch;
        gap: 0;
      }
    }

    .about-us-image-column {
      display: flex;
      flex-direction: column;
      line-height: normal;
      width: 39%;
      margin-left: 0;
    }

    @media (max-width: 991px) {
      .about-us-image-column {
        width: 100%;
      }
    }

    .about-us-image {
      aspect-ratio: 1.2;
      object-fit: cover;
      object-position: center;
      width: 100%;
      margin-top: -1px;
      min-height: 20px;
      min-width: 20px;
      overflow: hidden;
    }

    .about-us-text-column {
      display: flex;
      flex-direction: column;
      line-height: normal;
      width: 61%;
      margin-left: 20px;
    }

    @media (max-width: 991px) {
      .about-us-text-column {
        width: 100%;
      }
    }

    .about-us-text-container {
      display: flex;
      flex-direction: column;
      align-self: stretch;
      margin: auto 0;
      padding: 0 20px;
    }

    @media (max-width: 991px) {
      .about-us-text-container {
        max-width: 100%;
        margin-top: 40px;
      }
    }

    .about-us-title {
      color: #b1b1b1;
      font: 700 29px Raleway, sans-serif;
    }

    @media (max-width: 991px) {
      .about-us-title {
        max-width: 100%;
      }
    }

    .about-us-description {
      color: #313131;
      letter-spacing: 0.6px;
      margin-top: 47px;
      font: 400 15px/26px Public Sans, -apple-system, Roboto, Helvetica, sans-serif;
    }

    @media (max-width: 991px) {
      .about-us-description {
        max-width: 100%;
        margin-top: 40px;
      }
    }

    .section-divider {
      border: 1px solid rgba(209, 209, 209, 1);
      background-color: #d1d1d1;
      align-self: stretch;
      min-height: 1px;
      margin-top: 145px;
      width: 100%;
    }

    @media (max-width: 991px) {
      .section-divider {
        max-width: 100%;
        margin-top: 40px;
      }
    }

    .mission-vision-section {
      margin-top: 134px;
      width: 100%;
      max-width: 1083px;
    }

    @media (max-width: 991px) {
      .mission-vision-section {
        max-width: 100%;
        margin-top: 40px;
      }
    }

    .mission-vision-content {
      display: flex;
      gap: 20px;
    }

    @media (max-width: 991px) {
      .mission-vision-content {
        flex-direction: column;
        align-items: stretch;
        gap: 0;
      }
    }

    .mission-vision-text-column {
      display: flex;
      flex-direction: column;
      line-height: normal;
      width: 56%;
      margin-left: 0;
    }

    @media (max-width: 991px) {
      .mission-vision-text-column {
        width: 100%;
      }
    }

    .mission-vision-text-container {
      display: flex;
      margin-top: 18px;
      flex-direction: column;
      padding: 0 20px;
    }

    @media (max-width: 991px) {
      .mission-vision-text-container {
        max-width: 100%;
        margin-top: 40px;
      }
    }

    .mission-vision-title {
      color: #b1b1b1;
      font: 700 29px Raleway, sans-serif;
    }

    @media (max-width: 991px) {
      .mission-vision-title {
        max-width: 100%;
      }
    }

    .mission-vision-description {
      color: #313131;
      letter-spacing: 0.6px;
      margin-top: 69px;
      font: 400 15px/26px Public Sans, -apple-system, Roboto, Helvetica, sans-serif;
    }

    @media (max-width: 991px) {
      .mission-vision-description {
        max-width: 100%;
        margin-top: 40px;
      }
    }

    .mission-vision-image-column {
      display: flex;
      flex-direction: column;
      line-height: normal;
      width: 44%;
      margin-left: 20px;
    }

    @media (max-width: 991px) {
      .mission-vision-image-column {
        width: 100%;
      }
    }

  </style>
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
          <h2 class="about-us-title">Over ons</h2>
          <p class="about-us-description">
Bij Hackathon Desiderius zijn we toegewijd aan het bevorderen van een bloeiende gemeenschap van vernieuwers, probleemoplossers en technologieliefhebbers. Ons platform dient als een centrale plek waar individuen en bedrijven samenkomen om te verkennen, samen te werken en baanbrekende oplossingen te creëren via hackathons.            <br />
            <br />
Met een passie voor technologie en een toewijding aan excellentie, streven we ernaar om een inclusieve en ondersteunende omgeving te bieden waar iedereen zijn creativiteit kan ontketenen en een betekenisvolle impact kan hebben.          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="section-divider"></div>
  <div class="mission-vision-section">
    <div class="mission-vision-content">
      <div class="mission-vision-text-column">
        <div class="mission-vision-text-container">
          <h2 class="mission-vision-title">Missie en Visie</h2>
          <p class="mission-vision-description">
Onze missie is om innovatie te democratiseren door een platform te bieden waar iedereen, ongeacht achtergrond of expertise, kan deelnemen, leren en bijdragen aan de vooruitgang van technologie.  <br />
            <br />
We zien een wereld voor ons waar hackathons dienen als katalysatoren voor positieve verandering, waarbij innovatie wordt gestimuleerd, samenwerking wordt bevorderd en individuen en organisaties worden in staat gesteld om complexe uitdagingen op te lossen en de toekomst van technologie vorm te geven.          </p>
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
