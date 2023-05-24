<?php
require_once('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
try{
  $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query = "SELECT person.id, person.name, person.surname, COUNT(*) AS gold_medals
    FROM person
    JOIN placement ON person.id = placement.person_id
    JOIN game ON game.id = placement.games_id
    WHERE placement.placing = 1 AND (person.name LIKE :searchTerm
    OR person.surname LIKE :searchTerm
    OR game.year LIKE :searchTerm
    OR game.city LIKE :searchTerm
    OR game.type LIKE :searchTerm
    OR placement.discipline LIKE :searchTerm
    OR placement.placing LIKE :searchTerm)
    GROUP BY person.id
    ORDER BY gold_medals DESC
    LIMIT 10";
$stmt = $db->prepare($query);
$stmt->bindValue(':searchTerm', "%{$searchTerm}%");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


$query2 = "SELECT person.id, person.name, person.surname, game.year, game.city, game.type, placement.discipline, placement.placing 
FROM person 
JOIN placement ON person.id = placement.person_id 
JOIN game ON game.id = placement.games_id 
WHERE placement.placing = 1 AND (person.name LIKE :searchTerm 
OR person.surname LIKE :searchTerm 
OR game.year LIKE :searchTerm 
OR game.city LIKE :searchTerm 
OR game.type LIKE :searchTerm 
OR placement.discipline LIKE :searchTerm 
OR placement.placing LIKE :searchTerm)";
$stmt2 = $db->prepare($query2);
$stmt2->bindValue(':searchTerm', "%{$searchTerm}%");
$stmt2->execute();
$results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Tabuľky</title>
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
    <div class="container-md">
    <h1>Zadanie 1</h1><br>
    <h3>Top 10</h1>
    <table id ="tabulka1" class="table">
        <thead>
            <tr><th onclick="sortTable(0, 'tabulka1')">Meno</th><th onclick="sortTable(1, 'tabulka1')">Priezvisko</th><th onclick="sortTable(2, 'tabulka1')">Počet medailí</th></tr>
        </thead>
        <tbody>
        <?php 
        foreach($results as $result){
            echo "<tr><td><a href='detail.php?id=" . $result["id"] . "'>" . $result["name"] . "</a></td><td>" . $result["surname"] . "</td><td>" . $result["gold_medals"] . "</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <br><h3>Víťazi</h3>
    <table id ="tabulka2" class="table">
        <thead>
            <tr><th onclick="sortTable(0, 'tabulka2')">Meno</th><th onclick="sortTable(1, 'tabulka2')">Priezvisko</th><th onclick="sortTable(2, 'tabulka2')">Rok</th><th onclick="sortTable(3, 'tabulka2')">Mesto</th><th onclick="sortTable(4, 'tabulka2')">Typ</th><th onclick="sortTable(5, 'tabulka2')">Disciplína</th></tr>
        </thead>
        <tbody>
        <?php 
        foreach($results2 as $result){
            echo "<tr><td><a href='detail.php?id=" . $result["id"] . "'>" . $result["name"] . "</a></td><td>" . $result["surname"] . "</td><td>" . $result["year"] . "</td><td>" . $result["city"] . "</td><td>" . $result["type"] . "</td><td>"
          . $result["discipline"] . "</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <form method="get" class="hladac">
    <input type="text" name="search" id="search" placeholder="Search...">
    </form>
    </div>
  </body>
  <script src="scripty.js"></script>
</html>