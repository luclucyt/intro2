<?php
    session_start();
    include 'db.php';
    if(!isset($_SESSION['admin'])) {
        header("Location: login.php");
        exit();
    }
    if($_SESSION['admin'] == false){
        header("Location: student.php");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | punten</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/admin.css">

    <!-- JS -->
    <script src="../JS/beantwoord.js" defer></script>
    
    <!-- Font -->
    <link href="https://fonts.cdnfonts.com/css/nimbus-sans-l" rel="stylesheet">
</head>

<body>
    <form method="POST" action="">
        <select name="student">
            <?php 
                $sql = "SELECT * FROM studenten";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['userID'] . "'>" . $row['naam'] . "</option>";
                }
            ?>
        </select>

        <input type="number" name="punten" value="1" placeholder="punten hoeveelheid">

        <textarea name="reden" placeholder="reden..."></textarea>

        <input type="submit" value="Verstuur" name="change-score">
    </form>

    <?php
        if(isset($_POST['change-score'])){
            $studentID = $_POST['student'];

            $sql = "SELECT * FROM studenten WHERE userID = '$studentID'";
            $result = mysqli_query($conn, $sql);

            $row = mysqli_fetch_assoc($result);
            $student = $row['naam'];

            $datum = date("Y-m-d");


            $tijd = date("H:i:s");

            $punten = $_POST['punten'];
            $reden = $_POST['reden'];


            $sql = "INSERT INTO `punten` (`id`, `userID`, `naam`, `datum`, `tijd`, `punten`, `reden`) VALUES (NULL, '$studentID', '$student', '$datum', '$tijd', '$punten', '$reden')";
            $result = mysqli_query($conn, $sql);
            if(!$result) {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            $sql = "UPDATE studenten SET puntenTotaal = puntenTotaal + $punten WHERE userID = $studentID";
            $result = mysqli_query($conn, $sql);
            if(!$result) {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    ?>
</body>
</html>