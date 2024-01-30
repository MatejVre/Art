<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

    require 'password.php';
    $host = "devweb2022.cis.strath.ac.uk"; //boilerplate code from the lectures -> not my own invention!
    $user = "vib20191";
    $pass = get_password();
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    //Uses session password to keep Cara logged in
    if(isset($_SESSION["sessionpassword"])){
        $sessionpassword = $_SESSION["sessionpassword"];
        $password = $_SESSION["sessionpassword"];
    }
    else{
        $sessionpassword = "";
        $password =  isset($_POST["password"]) && ($_POST["password"] !== "") ? $_POST["password"] : "";
    }

    if(isset($_POST["delete"])){
        $id = $_POST["delete"];
        $id = preg_replace('/[^0-9]/', '', $id);
        $statement = "DELETE FROM `orders` WHERE `id`=?";
        $res = $conn->prepare($statement);
        $res->bind_param("i", $id);
        $res->execute();
    }

    if(($sessionpassword === "YouAskMeHow22") || ($password === "YouAskMeHow22")) {
        $_SESSION["sessionpassword"] = $password;
        ?>
<table>
    <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Address</th>
        <th>Postcode</th>
        <th>City</th>
        <th>Order ID</th>
        <th>Image ID</th>
    </tr>
    <?php
        echo "<p>Hello Cara!</p><br>";
        echo "<a href='insert.php'>Go to insertion page</a>";
        $sql = "SELECT * FROM `orders`";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                echo "<tr>".
                "<td><b>".$row["name"]."</b></td>".
                "<td><b>".$row["phone"]."</b></td>".
                "<td><b>".$row["email"]."</b></td>".
                "<td><b>".$row["address"]."</b></td>".
                "<td><b>".$row["postcode"]."</b></td>".
                "<td><b>".$row["city"]."</b></td>".
                "<td><b>".$id."</b></td>".
                "<td><b>".$row["imageID"]."</b></td>".
                   "<td><form  action='admin.php' method='post'>
                            <input type='submit' value='DELETE'>
                            <input type='hidden' name='delete' value='<?php echo $id?>'>
                        </form></td>".
                "</tr>";

            }
        }
    }


    else {
        ?>
        <form action = "admin.php" method = "post">
        <label for="password" > Password: </label >
            <input type = "password" name = "password" id = "password" >
        </form >
   <?php
    }
        ?>
</body>
</html>
