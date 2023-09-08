<?php
    include 'db.php';
    if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false) {
        // header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student | Punten</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/student.css">

    <!-- JS -->
    <script src="../JS/root.js" defer></script>
</head>

<body>
    <div class="header"><h1> Welkom <?= $_SESSION['name'] ?>, </h1></div>
    
    
    <div class="leaderboard">
    <?php 
        $sql = "SELECT * FROM studenten ORDER BY puntenTotaal DESC";
        $result = mysqli_query($conn, $sql);

        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='student'>";
                echo "<div>";
                    echo "<div class='rank'>" . $i . ". </div>";
                    echo "<div class='name'>" . $row['naam'] . "</div>";
                echo "</div>";
                echo "<div class='points'>" . $row['puntenTotaal'] . "</div>";
            echo "</div>";
            $i++;
        } 
    ?>

    </div>
    
    <div class="overview">
        <?php
            $sql = "SELECT * FROM punten ORDER BY datum DESC, tijd DESC";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='student'>";
                    echo "<div class='name'>" . $row['naam'] . "</div>";
                    echo "<div class='points'>" . $row['punten'] . "</div>";
                echo "</div>";
            }
        ?>
    </div>
</body>
</html>