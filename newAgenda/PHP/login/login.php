<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../../CSS/notebook.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Log in</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../CSS/root.css">

    <!-- JS -->
    <script src="../../JS/login.js" defer></script>
</head>
<body>
    <?php ini_set('display_errors', 0); ?>

    <?php include_once '../connectDatabase.php'; ?>


    <section class="main">
        <div class="main-login-wrapper">

            <div class="signup-wrapper">
                <h1>Nieuw hier?</h1>
                <form method="POST" action="" autocomplete="off">
                    <input type="text" name="username" placeholder="Gebruikersnaam" class="input-sign-up"><br>

                    <input type="email" name="email" placeholder="E-mail" class="input-sign-up"><br>

                    <input type="password" name="password" placeholder="Wachtwoord" id="password" class="input-sign-up"><br>

                    <button type="submit" name="signup-submit" id="submit-button" class="submit-button"><h2>Registreer nu</h2></button>
                </form>
            </div>

            <div class="login-wrapper">
                <h1>Log in:</h1>
                <form method="post" autocomplete="off">
                    <input type="text" name="username" placeholder="Gebruikersnaam" class="login-input">

                    <input type="password" name="password" placeholder="Wachtwoord" class="login-input">

                    <button type="submit" name="login-submit" class="submit-button"><h2>Log in</h2></button>
                </form>
            </div>


            <?php
                //start the session
                if (session_status() == PHP_SESSION_NONE) {
                    session_set_cookie_params(31536000);
                    session_start(); //Start the session if it doesn't exist
                }
                //log in form
                if (isset($_POST['login-submit'])) {

                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";
                    $result = mysqli_query($connection, $sql);


                    if (mysqli_num_rows($result) > 0) {
                        //after the query is done execute another query to get the id of the user
                        $sql2 = "SELECT id FROM login WHERE username='$username' AND password='$password'";
                        $result2 = mysqli_query($connection, $sql2);
                        $row = mysqli_fetch_assoc($result2);
                        $id = $row['id'];
                        echo $id;

                        $sql = "SELECT email FROM login WHERE username='$username' AND password='$password'";
                        $result = mysqli_query($connection, $sql);
                        $row = mysqli_fetch_assoc($result);
                        if(mysqli_num_rows($result) > 0){
                            $_SESSION['email'] = $row['email'];
                        }

                        //set the session variable to the id of the user
                        $_SESSION['userID'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['password'] = $password;

                        //redirect to index.php
                        header("Location: ../agenda/index.php");
                    } else {
                        echo "<h1>Wachtwoord of gebruikersnaam verkeerd!</h1>";
                        $_SESSION['username'] = '';
                    }
                }


                //sign up form
                if (isset($_POST['signup-submit'])) {

                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    //check if username already exists, or the email is already in use
                    $existsNameSQL = "SELECT * FROM login WHERE username='$username'";
                    $existsEmailSQL = "SELECT * FROM login WHERE email='$email'";
                    $result = mysqli_query($connection, $existsNameSQL);
                    $result2 = mysqli_query($connection, $existsEmailSQL);

                    if (mysqli_num_rows($result) == 0 && mysqli_num_rows($result2) == 0) {
                        //insert data into database
                        $sql = "INSERT INTO login (id, username, email, password, ColorTheme) VALUES ('', '$username' , '$email', '$password', 1)";

                        //check if the query is executed
                        if (mysqli_query($connection, $sql)) {
                            echo "aanmelding gelukt!";

                            //get the userID of the user
                            $sql2 = "SELECT id FROM login WHERE username='$username'";
                            $result2 = mysqli_query($connection, $sql2);
                            $row = mysqli_fetch_assoc($result2);
                            $id = $row['id'];

                            //after the query is done execute another query to insert the deafualt colors into the database
                            $sql2 = "SELECT id FROM login WHERE username='$username'";
                            $result2 = mysqli_query($connection, $sql2);
                            $row = mysqli_fetch_assoc($result2);
                            $id = $row['id'];

                            //after the query is done execute another query to insert the deafualt colors into the database
                            $sql1 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#000000', 'Slaap')";
                            $sql2 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#04052EFF', 'School')";
                            $sql3 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#140152FF', 'Werk')";
                            $sql4 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#22007CFF', 'Sport')";
                            $sql5 = "INSERT INTO kleuren (id, userID, kleur, functie) VALUES ('', '$id', '#0D00A4FF', 'Vrije tijd')";

                            $result1 = mysqli_query($connection, $sql1);
                            $result2 = mysqli_query($connection, $sql2);
                            $result3 = mysqli_query($connection, $sql3);
                            $result4 = mysqli_query($connection, $sql4);
                            $result5 = mysqli_query($connection, $sql5);

                            //get the data from the database
                            $sql = "SELECT * FROM kleuren WHERE userID='$id'";
                            $result = mysqli_query($connection, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $kleurID = $row['id'];
                                $kleurUserID = $row['userID'];
                                $kleurKleur = $row['kleur'];
                                $kleurFunctie = $row['functie'];

                            }


                            $sql1 = "INSERT INTO access (id, userID, accesUserID) VALUES ('', '$id', '$id')";
                            $result1 = mysqli_query($connection, $sql1);

                            //get the data from the database
                            $sql = "SELECT * FROM access WHERE userID='$id'";
                            $result = mysqli_query($connection, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $accessID = $row['id'];
                                $accessUserID = $row['userID'];
                                $accessAccesUserID = $row['accesUserID'];

                                

                            }

                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                        }


                        //get the data from the database
                        $sql = "SELECT * FROM login WHERE username='$username' AND password='$password'";
                        $result = mysqli_query($connection, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $userID = $row['id'];
                            $userName = $row['username'];
                            $userEmail = $row['email'];
                            $userPassword = $row['password'];
                            $userColorTheme = $row['ColorTheme'];


                        }

                    } else {
                        echo "<label>Gebruikersnaam of email zijn al in gebruik!</label>";
                    }
                }
            ?>
        </div>
    </section>
</body>
</html>

<script>
    if(window.history.replaceState){
        window.history.replaceState(null, null, window.location.href);
    }
</script>