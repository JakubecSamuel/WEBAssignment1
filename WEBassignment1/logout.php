<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Uvolni vsetky session premenne.
session_unset();

// Vymaz vsetky data zo session.
session_destroy();

// Ak nechcem zobrazovat obsah, presmeruj pouzivatela na hlavnu stranku.
// header('location:index.php');

?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OAuth2 cez Google</title>

    <style>
        html {
            max-width: 70ch;
            padding: 3em 1em;
            margin: auto;
            line-height: 1.75;
            font-size: 1.25em;
        }

        h1,h2,h3,h4,h5,h6 {
            margin: 3em 0 1em;
        }

        p,ul,ol {
            margin-bottom: 2em;
            color: #1d1d1d;
            font-family: sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
</head>
<body>
    <main>
        <h1>Boli ste úspešne odhlásení.</h1>
        <a role="button" href="prihlasovanie.php" class="secondary">Späť na hlavnú stránku</a>
    </main>
</body>
</html>