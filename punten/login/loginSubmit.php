<?php

session_start();

$_SESSION['mail'] = $_POST['mail'];
$_SESSION['password'] = $_POST['password'];

require __DIR__ . '/vendor/autoload.php';

use LdapRecord\Container;
use LdapRecord\Connection;
use LdapRecord\Models\Entry;
use LdapRecord\Models\ActiveDirectory\User;

// Create a new connection:
try {
    $connection = new Connection([
        'hosts' => ['dc01.ict.lab.locals', 'dc02.ict.lab.locals'],
        'port' => 389,
        'base_dn' => 'dc=ict,dc=lab,dc=locals',
        'username' => null,
        'password' => null,
    ]);
} catch (\LdapRecord\Configuration\ConfigurationException $e) {
    $_SESSION['loggedIn'] = false;
    header("Location: ../PHP/login.php");
    exit();
}

try {
    $connection->connect();

} catch (\LdapRecord\Auth\BindException $e) {
    $error = $e->getDetailedError();

    echo $error->getErrorCode();
    echo $error->getErrorMessage();
    echo $error->getDiagnosticMessage();
    die();
}


if(str_contains($_POST['mail'], '@glr.nl')) {
    $user = str_replace('@glr.nl', '', $_POST['mail']);
}
else {
    $user = $_POST['mail'];
}

$ad_suffix = '@ict.lab.locals';
$password = $_POST['password'];


try {
    if($_SESSION['key'] != $_POST['key']) {
        echo "You are not allowed to login";
        $_SESSION['loggedIn'] = false;
        exit();
    }

    $connection->auth()->bind($user.$ad_suffix, $password);

    // Further bound operations...
    $ldapuser = $connection->query()
        ->where('samaccountname', '=', $user)
        ->firstOrFail();

    if(str_contains($user, '@glr.nl')) {
        $user = str_replace('@glr.nl', '', $user);
    }

    //if the user is a number it is a student (no admin rights)
    if(is_numeric($user)) {
        $_SESSION['admin'] = false;
    }
    else {
        $_SESSION['admin'] = true;
    }

    $_SESSION['mail'] = $user . "@glr.nl";
    $_SESSION['name'] = $ldapuser['displayname'][0];
    $_SESSION['loggedIn'] = true;
    if($_SESSION['admin']) {
        header("Location: ../PHP/admin.php");
    }
    else {
        header("Location: ../PHP/student.php");
    }

    die("You are logged in");
} catch (Exception $e) {
    echo $e->getMessage();
    $_SESSION['loggedIn'] = false;
    $_SESSION['error'] = "Gebruikersnaam of wachtwoord is onjuist";
    header("Location: ../PHP/login.php");
    exit();
}
