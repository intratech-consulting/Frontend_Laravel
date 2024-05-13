<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy</title>
  <link rel="stylesheet" href="user/privacy.css">
  <style>
    *{
      margin: 0;
      padding: 0;
    }

    .privacy-container {
      padding: 50px;
      max-width: 994px;
      display: flex;
      flex-direction: column;
      justify-content: center; /* Centers vertically */
      align-items: center; /* Centers horizontally */
      margin: 0 auto; /* Centers horizontally within parent */
    }

    .privacy-title {
      color: #1f1f1f;
      width: 100%;
      font: 700 29px Raleway, sans-serif;
    }

    @media (max-width: 991px) {
      .privacy-title {
        max-width: 100%;
      }
    }

    .privacy-content {
      color: #313131;
      letter-spacing: 0.6px;
      margin-top: 44px;
      width: 100%;
      font: 400 15px/26px Public Sans, -apple-system, Roboto, Helvetica, sans-serif;
    }

    @media (max-width: 991px) {
      .privacy-content {
        max-width: 100%;
        margin-top: 40px;
      }
    }

  </style>
</head>
<body>

@include('user.components.header')


  <section class="privacy-container">

    <h2 class="privacy-title">Privacy Policy</h2>

    <div class="privacy-content">
      <h2>Informatie die we verzamelen:</h2>

      We verzamelen persoonlijke informatie zoals je naam, e-mailadres en andere gegevens die je verstrekt bij het registreren als lid of het organiseren van een hackathon. Daarnaast kunnen we niet-persoonlijke informatie verzamelen zoals browsertype, besturingssysteem en IP-adres voor analytische doeleinden.
      <br><br>

      <h2>Hoe we jouw informatie gebruiken:</h2>

      We gebruiken de informatie die je verstrekt om je deelname aan hackathons te vergemakkelijken, om met je te communiceren over aankomende evenementen en om onze diensten te verbeteren. Jouw persoonlijke informatie kan ook worden gebruikt voor administratieve doeleinden, zoals accountbeheer en probleemoplossing.
      <br><br>

      <h2>Openbaarmaking van informatie:</h2>

      We verkopen, verhandelen of anderszins overdragen jouw persoonlijke informatie niet aan derden zonder jouw toestemming, behalve zoals vereist door de wet of om de doeleinden uiteengezet in dit privacybeleid te vervullen. Niet-persoonlijke informatie kan echter worden gedeeld met vertrouwde derde dienstverleners voor analytische en marketingdoeleinden.
      <br><br>

      <h2>Beveiligingsmaatregelen:</h2>

      We implementeren verschillende beveiligingsmaatregelen om jouw persoonlijke informatie te beschermen en ongeautoriseerde toegang, openbaarmaking of wijziging te voorkomen. Deze maatregelen omvatten encryptie, firewalls en regelmatige beveiligingsaudits.
      <br><br>

      <h2>Jouw Rechten:</h2>

      Je hebt het recht om op elk moment toegang te krijgen tot, correcties aan te brengen of je persoonlijke informatie te verwijderen. Je kunt je ook afmelden voor onze communicatie of verzoeken dat we de verwerking van je gegevens beperken. Neem contact met ons op als je vragen of zorgen hebt over je privacyrechten.
      <br><br>

      <h2>Erasmushogeschool Brussel:</h2>

      Hackathon Desiderius is gecreëerd door studenten van de Erasmushogeschool Brussel als onderdeel van een project om innovatie en samenwerking in de techgemeenschap te bevorderen. We zijn trots op de steun en expertise van onze academische instelling bij het ontwikkelen en onderhouden van dit platform.
      <br><br>

      <h2>Neem contact met ons op:</h2>

      
      Als je vragen of zorgen hebt over ons privacybeleid of de behandeling van jouw persoonlijke informatie, neem dan contact met ons op via <a href="mailto:lenno.lemmens@student.ehb.be">lenno.lemmens@student.ehb.be</a> .
    </div>
  </section>


@include('user.components.footer')


</body>
</html>
