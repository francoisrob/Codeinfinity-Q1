<?php
function showForm()
{
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
    $id = isset($_POST['userid']) ? $_POST['userid'] : '';
    $dob = isset($_POST['userdob']) ? $_POST['userdob'] : '';

    return
        '<form action="/php/connect.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="' . htmlspecialchars($name) . '" required> <br>
        <label for="surname">Surname:</label>
        <input type="text" name="surname" id="surname" value="' . htmlspecialchars($surname) . '" required> <br>
        <label for="userid">ID Number:</label>
        <input type="number" name="userid" id="userid" value="' . htmlspecialchars($id) . '" required> <br>
        <label for="userdob">Date of Birth:</label>
        <input type="date" name="userdob" id="userdob" value="' . htmlspecialchars($dob) . '" required> <br>
        <button type="submit">Submit</button>
        <button type="reset">Cancel</button>
        </form>
 ';
}