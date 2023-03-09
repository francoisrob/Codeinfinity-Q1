<?php
include_once('../vendor/autoload.php');
require('form.php');
$MongoUri = 'mongodb://localhost:27017';

try {
    $client = new MongoDB\Client($MongoUri);
} catch (MongoDB\Driver\Exception $e) {
    echo 'Connection to MongoDB failed: ' . $e->getMessage();
    exit();
}
;
$collection = $client->codeinfinity->users;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Input from post
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $surname = htmlspecialchars($_POST['surname'], ENT_QUOTES);
    $id = filter_var($_POST['userid'], FILTER_SANITIZE_NUMBER_INT);
    $dob = date('Y-m-d', strtotime($_POST['userdob']));

    // Check if all fields are filled in
    if (isempty($name, $surname, $id, $dob)) {
        echo 'You have to fill in all the fields.';
        exit();
    }

    // Check if ID number is valid
    if (!validdob($dob, $id)) {
        echo 'Your ID number does not match your date of birth.';
        exit();
    }

    // Check if ID number is already in use
    if (!validid($id, $collection)) {
        echo 'This ID number is already in use.';
        unset($_POST['userid']);
        echo showForm();
        exit();
    }

    $insert = $collection->insertOne([
        'name' => $name,
        'surname' => $surname,
        'id' => $id,
        'dob' => $dob
    ]);
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
function validdob($dob, $id)
{
    $iddob = substr($id, 0, 6);
    $dob = date('Ymd', strtotime($dob));
    $dob = substr($dob, 2, 8);
    if ($dob == $iddob) {
        return true;
    } else {
        return false;
    }
}
?>