<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question 1</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <form action="/php/connect.php" method="post">
        <?php
        session_start();
        if (isset($_SESSION['Message'])) {
            echo $_SESSION['Message'];
            unset($_SESSION['Message']);
        }
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
        $id = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
        $dob = isset($_SESSION['userdob']) ? $_SESSION['userdob'] : '';
        echo
            '<label for="name">Name:</label>
        <input type="text" name="name" id="name" value="' . htmlspecialchars($name) . '" required> <br>
        <label for="surname">Surname:</label>
        <input type="text" name="surname" id="surname" value="' . htmlspecialchars($surname) . '" required> <br>
        <label for="userid">ID Number:</label>
        <input type="number" name="userid" id="userid" value="' . htmlspecialchars($id) . '" required> <br>
        <label for="userdob">Date of Birth:</label>
        <input type="date" name="userdob" id="userdob" value="' . htmlspecialchars($dob) . '" required> <br>
        <button type="submit">Submit</button>
        <button type="button" onclick="cancelform()">Cancel</button>
        </form>'
            ?>
        <script>
            function cancelform() {
                <?php session_unset(); ?>
                location.reload();
            }
        </script>
</body>

</html>