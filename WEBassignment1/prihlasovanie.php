<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once 'vendor/autoload.php';
require_once 'config.php';

// Inicializacia Google API klienta
$client = new Google\Client();

// Definica konfiguracneho JSON suboru pre autentifikaciu klienta.
// Subor sa stiahne z Google Cloud Console v zalozke Credentials.
$client->setAuthConfig('../../client_secret.json');

// Nastavenie URI, na ktoru Google server presmeruje poziadavku po uspesnej autentifikacii.
$redirect_uri = "https://site111.webte.fei.stuba.sk/oh/redirect.php";
$client->setRedirectUri($redirect_uri);

// Definovanie Scopes - rozsah dat, ktore pozadujeme od pouzivatela z jeho Google uctu.
$client->addScope("email");
$client->addScope("profile");

// Vytvorenie URL pre autentifikaciu na Google server - odkaz na Google prihlasenie.
$auth_url = $client->createAuthUrl();

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OAuth2 cez Google</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="mojstyle.css" />
</head>
<body>
    <nav>
      <div>
        <a href="index.php"><b>Tabuľky</b></a>
        <a href="register.php"><b>Registrácia</b></a>
        <a href="prihlasovanie.php"><b>Prihlásanie</b></a>
      </div>
    </nav>
    <main>
        <?php
        // Ak som prihlaseny, existuje session premenna.
        if ((isset($_SESSION['access_token']) && $_SESSION['access_token']) || (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)) {
            // Vypis relevantne info a uvitaciu spravu.
            echo '<h3>Vitaj ' . $_SESSION['name'] . '</h3>';
            echo '<p><b>Si prihlaseny ako: ' . $_SESSION['email'] . '</b></p>';
            echo '<p><a class="btn btn-success" role="button" href="restricted.php" style="margin-right: 5px">Zabezpečená stránka</a>';
            echo '<a class="btn btn-success" role="button" href="logout.php">Odhlásenie</a></p>';
        } else {
            // Ak nie som prihlaseny, zobraz mi tlacidlo na prihlasenie.
            echo '<h3>Nie ste momentálne prihlásený.</h3>';
            echo '<a class="btn btn-success" role="button" href="login.php">Vlastné prihlásenie</a><br><br>';
            echo '<a class="btn btn-success" role="button" href="' . filter_var($auth_url, FILTER_SANITIZE_URL) . '">Google prihlasenie</a>';
        }
        ?>
    </main>
</body>
</html>