<?php
require_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_GET["id"];

try{
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT person.id, person.name, person.surname, game.year, game.city, game.type, placement.discipline, placement.placing FROM person JOIN placement ON person.id = placement.person_id 
    JOIN game ON game.id = placement.games_id WHERE person.id = $id";
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e){
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hello!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="mojstyle.css" />
  </head>
  <script src="scripty.js"></script>
  <body>
  <nav>
      <div>
        <a href="index.php"><b>Tabuľky</b></a>
        <a href="register.php"><b>Registrácia</b></a>
        <a href="prihlasovanie.php"><b>Prihlásanie</b></a>
      </div>
    </nav>
    <div class="container-md">
    <h1>Zadanie 1</h1><br>
    <h3><?php
    foreach($results as $result){
        echo $result["name"];
        break;
    }
    ?>
    </h3>
    <table id ="tabulka1" class="table">
        <thead>
            <tr><th onclick="sortTable(0, 'tabulka2')">Meno</th><th onclick="sortTable(1, 'tabulka2')">Priezvisko</th><th onclick="sortTable(2, 'tabulka2')">Rok</th><th onclick="sortTable(3, 'tabulka2')">Mesto</th><th onclick="sortTable(4, 'tabulka2')">Typ</th><th onclick="sortTable(5, 'tabulka2')">Disciplína</th></tr>
        </thead>
        <tbody>
        <?php 
        foreach($results as $result){
            echo "<tr><td>" . $result["name"] . "</td><td>" . $result["surname"] . "</td><td>" . $result["year"] . "</td><td>" . $result["city"] . "</td><td>" . $result["type"] . "</td><td>"
          . $result["discipline"] . "</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <a class="btn btn-primary" href="index.php" role="button">Späť</a>
    </div>
  </body>
</html>