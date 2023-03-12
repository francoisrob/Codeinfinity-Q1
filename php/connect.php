<?php
session_start();
include_once('../vendor/autoload.php');
$MongoUri = 'mongodb://localhost:27017';

try {
    $client = new MongoDB\Client($MongoUri);
} catch (MongoDB\Driver\Exception $e) {
    $_SESSION['Message'] = '<p type="error">*Server connection error: ' . $e->getMessage() . '</p>';
    $_SESSION = '<p>Connection to MongoDB failed: ' . $e->getMessage() . '</p>';
    exit();
}
$collection = $client->codeinfinity->users;

// Input from post
$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
$surname = htmlspecialchars($_POST['surname'], ENT_QUOTES);
$id = filter_var($_POST['userid'], FILTER_SANITIZE_NUMBER_INT);
$dob = date('d/m/Y', strtotime($_POST['userdob']));

// Check if all fields are filled in
if (isempty($name, $surname, $id, $dob)) {
    $_SESSION['Message'] = '<p type="error">*You have to fill in all the fields.</p>';
    header('Location: /index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if ID number is valid
    if (!validdob($dob, $id)) {
        setnsdob($name, $surname, $dob);
        $_SESSION['Message'] = '<p type="error">*Your ID number does not match your date of birth.</p>';
        header('Location: /index.php');
        exit();
    }

    // Check if ID number is already in use
    if (!validid($id, $collection)) {
        setnsdob($name, $surname, $dob);
        $_SESSION['Message'] = '<p type="error">*This ID number is invalid.</p>';
        header('Location: /index.php');
        exit();
    }

    $insert = $collection->insertOne([
        'name' => $name,
        'surname' => $surname,
        'id' => $id,
        'dob' => $dob
    ]);
    //success
    session_unset();
    $_SESSION['Message'] = '<p>You have successfully registered!</p>';
    header('Location: /index.php');
} else {
    header('Location: /index.php');
}

//Functions
function isempty($name, $surname, $id, $dob)
{
    if (empty($name) || empty($surname) || empty($id) || empty($dob)) {
        return true;
    } else {
        return false;
    }
}

// Checks if ID number is already in use
function validid($id, $collection)
{
    if (strlen($id) == 13) {
        $find = $collection->findOne(['id' => $id]);
        if ($find) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

// Checks if ID number matches date of birth
function validdob($dob, $id)
{
    $iddob = substr($id, 0, 6);
    $dob = substr($dob, 8, 2) . substr($dob, 3, 2) . substr($dob, 0, 2);
    if ($dob == $iddob) {
        $dobvalid = true;
    } else {
        $dobvalid = false;
    }
    return $dobvalid;
}

// Sets vars to stay in form
function setnsdob($name, $surname, $dob)
{
    $_SESSION['name'] = $name;
    $_SESSION['surname'] = $surname;
    $_SESSION['userdob'] = $dob;
}
?>