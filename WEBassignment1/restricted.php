<?php

require_once 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Ak je pouzivatel prihlaseny, ziskam data zo session, pracujem s DB etc...
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $email = $_SESSION['email'];
    $fullname = $_SESSION['fullname'];
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];

} else if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $email = $_SESSION['email'];
    $fullname = $_SESSION['name'];
}else{
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: prihlasovanie.php');
}

$meno = $surname = $birth_day = $birth_place = $birth_country = $death_day = $death_place = $death_country = '';
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["add"])) {
        $meno = $_POST['meno'];
        $surname = $_POST['surname'];
        $birth_day = $_POST['birth_day'];
        $birth_place = $_POST['birth_place'];
        $birth_country = $_POST['birth_country'];
        $death_day = empty($_POST['death_day']) ? NULL : $_POST['death_day'];
        $death_place = empty($_POST['death_place']) ? NULL : $_POST['death_place'];
        $death_country = empty($_POST['death_country']) ? NULL : $_POST['death_country'];

        $conn = new mysqli($hostname, $username, $password, $dbname);
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO person (name, surname, birth_day, birth_place, birth_country, death_day, death_place, death_country) VALUES ('$meno', '$surname', '$birth_day', '$birth_place', '$birth_country', ";
        $sql .= $death_day ? "'$death_day', " : "NULL, ";
        $sql .= $death_place ? "'$death_place', " : "NULL, ";
        $sql .= $death_country ? "'$death_country')" : "NULL)";
        
    
        if ($conn->query($sql) === TRUE) {
        echo "Do databázy si pridal nového športovca!";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    } else if (isset($_POST["placement"])) {
        $person_id = $_POST['person_id'];
        $game_id = $_POST['game_id'];
        $placing = $_POST['placing'];
        $discipline = $_POST['discipline'];


        // Insert the new record into the placement table
        $insert_query = "INSERT INTO placement (person_id, games_id, placing, discipline) 
                        VALUES ('$person_id', '$game_id', '$placing', '$discipline')";
        $result = mysqli_query($conn, $insert_query);

        // Check if the insert was successful
        if ($result) {
            echo "Do databázy si pridal umiestnenie športovca!";
        } else {
            echo "Error adding record: " . mysqli_error($conn);
        }
        }
  }
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zabezpečená stránka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="mojstyle.css" />
</head>
<body>
<nav>
    <div>
        <a href="index.php"><b>Tabuľky</b></a>
    </div>
</nav>
<main>
    <h3>Vitaj <?php echo $fullname ?></h3>
    <p>Si prihlaseny pod emailom: <?php echo $email?></p>
    <a class="btn btn-success" href="logout.php" role="button">Odhlásenie</a>
    <a class="btn btn-success" href="prihlasovanie.php" role="button">Späť na hl. stránku</a>
</main>
<h2>Pridávanie športovca</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="meno">Meno:</label>
    <input type="text" name="meno" id="meno" required>
    <br>
    <label for="surname">Priezvisko:</label>
    <input type="text" name="surname" id="surname" required>
    <br>
    <label for="birth_day">Dátum narodenia:</label>
    <input type="date" name="birth_day" id="birth_day" required>
    <br>
    <label for="birth_place">Miesto narodenia:</label>
    <input type="text" name="birth_place" id="birth_place" required>
    <br>
    <label for="birth_country">Rodná krajina:</label>
    <input type="text" name="birth_country" id="birth_country" required>
    <br>
    <label for="death_day">Deň úmrtia:</label>
    <input type="date" name="death_day" id="death_day">
    <br>
    <label for="death_place">Miesto úmrtia:</label>
    <input type="text" name="death_place" id="death_place">
    <br>
    <label for="death_country">Krajina úmrtia:</label>
    <input type="text" name="death_country" id="death_country">
    <br>
    <br>
    <input class="btn btn-primary" role="button" type="submit" name="add" value="Pridaj športovca">
</form>
<h2>Pridanie umiestnenia</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="person_id">Športovec</label>
    <select name="person_id" id="person_id">
        <option value="">Vyber športovca:</option>
        <?php
        // Query the person table in the database
        $sql = "SELECT id,name,surname FROM person";
        $result = mysqli_query($conn, $sql);
        // Loop over the results and create an option for each person ID
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['id'] . "'>" . $row['id'] . " - " . $row['name'] . " " .$row['surname'] . "</option>";
        }
        ?>
    </select>
    <label for="game_id">Olympíjska hra</label>
    <select name="game_id" id="game_id">
        <option value="">Vyber OH:</option>
        <?php
        // Query the game table to get all existing game IDs
        $query = "SELECT id,type,year,city,country FROM game";
        $result = mysqli_query($conn, $query);
        // Loop through the result set and add each game ID as an option in the select box
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row["id"] . '">' . $row["id"] . " - " . $row['type'] . " - " . $row['year'] . " - " . $row['city'] . " - " . $row['country'] . '</option>';
            }
        }
        ?>
    </select><br>
    <label for="placing">Umiestnenie</label>
    <input type="text" name="placing"><br>
    <label for="discipline">Disciplína</label>
    <input type="text" name="discipline"><br><br>
    <input type="submit" class="btn btn-primary" role="button" name="placement" value="Pridaj umiestnenie">
</form>
</body>
</html>