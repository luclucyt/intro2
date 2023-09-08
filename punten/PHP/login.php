<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | punten</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/login.css">

</head>

<?php 
    include 'db.php';
    //genarete a session key
    $key = 1;
    $_SESSION['key'] = $key;


    if(isset($_SESSION['mail']) && isset($_SESSION['password'])){
        $mail = $_SESSION['mail'];
        $password = $_SESSION['password'];
    }else{
        $mail = "";
        $password = "";
    }

    if(isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    }else{
        $error = "";
    }
?>

<body>
    <main>
        <div class="login-wrapper">
            <div>
                <h1>Login:</h1>
                <form action="../login/loginSubmit.php" method="POST">
                    <input type='hidden' name='key' value='<?= $key ?>'>
                    
                    <input type="text" name="mail" placeholder="012345@glr.nl" value="<?= $mail ?>"><br>
                    <input type="password" name="password" placeholder="School Wachtwoord" value="<?= $password ?>"><br>

                    <?= $error ?>

                    <input type="submit" name="submitLogin" value="Login">
                </form>
            </div>
        </div>
    </main>
</body>
</html>