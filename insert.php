<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload image</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php

    function safePost($conn, $name){
        return isset($_POST[$name])? $conn -> real_escape_string(strip_tags($_POST[$name])): "";
    }
    require 'password.php';
    $host = "devweb2022.cis.strath.ac.uk"; //boilerplate code from the lectures -> not my own invention!
    $user = "vib20191";
    $pass = get_password();
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    if(isset($_SESSION["sessionpassword"])){
        $sessionpassword = $_SESSION["sessionpassword"];
        $password = $_SESSION["sessionpassword"];
    }
    else{
        $sessionpassword = "";
        $password =  isset($_POST["password"]) && ($_POST["password"] !== "") ? $_POST["password"] : "";
    }
    if(($sessionpassword === "YouAskMeHow22") || ($password === "YouAskMeHow22")) {
    $_SESSION["sessionpassword"] = $password;

    echo "<a href='admin.php'>Go to main admin page</a>";

    $name = isset($_POST["name"]) && ($_POST["name"] !== "") ? $_POST["name"] : "";
    $date = isset($_POST["date"]) && ($_POST["date"] !== "") ? $_POST["date"] : "";
    $width = isset($_POST["width"]) && ($_POST["width"] !== "") ? $_POST["width"] : "";
    $height = isset($_POST["height"]) && ($_POST["height"] !== "") ? $_POST["height"] : "";
    $price = isset($_POST["price"]) && ($_POST["price"] !== "") ? $_POST["price"] : "";
    $description = isset($_POST["description"]) && ($_POST["description"] !== "") ? $_POST["description"] : "";

    if(isset($_FILES["image"])) {
        if (is_uploaded_file($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name']) != false) {
            $info = getimagesize($_FILES['image']['tmp_name']);
            $type = $info['mime'];
            $imgfp = file_get_contents($_FILES['image']['tmp_name']);
            $dims = $info[3];
            //$name = $_FILES['image']['name'];
            $maxsize = 99999999;
            if ($name && $date && $width && $height && $price && $description && isset($_FILES["image"])) {

                $dname = safePost($conn, $name);
                $ddate = safePost($conn, $date);
                $dwidth = safePost($conn, $width);
                $dheight = safePost($conn, $height);
                $dprice = safePost($conn, $price);
                $ddescription = safePost($conn, $description);

                $stmt = $conn->prepare("INSERT INTO art (name, dateOfCompletion, width, height, price, description, image) VALUES (?,?,?,?,?,?,?)");
                ;
                $stmt->bind_param('ssdddsb', $name, $date, $width, $height, $price, $description, $imgfp);
                $stmt->send_long_data(6,$imgfp);
                $stmt->execute();
                echo "success!";
            }
        }
    }
?>
<form enctype="multipart/form-data" action="insert.php" method="post" onsubmit="return check()">
    <label for="name">Name: </label>
        <input id="name" type="text" name="name"><br>
    <label for="date">Date: </label>
        <input id="date" type="date" name="date"><br>
    <label for="width">Width: </label>
        <input id="width" type="text" name="width"><br>
    <label for="height">Height: </label>
        <input id="height" type="text" name="height"><br>
    <label for="price">Price: </label>
        <input id="price" type="text" name="price"><br>
    <label for="description">Description: </label>
        <input id="description" type="text" name="description"><br>
    <label for="image">Image: </label>
        <input id="image" type="file" name="image"><br>
        <input class="submit" type="submit" value="Submit">
<?php
}
else {
    ?>
    <form action = "insert.php" method = "post">
        <label for="password" > Password: </label >
        <input type = "password" name = "password" id = "password" >
    </form >
    <?php
}
?>
    <script>
        const DEBUG  = true;
        function debug(s){
            if(DEBUG){
                console.log(s);
            }
        }
        function check(){
            debug("checking form");

            let errors = "";
            const name = document.getElementById("name");
            const phone = document.getElementById("date");
            const email = document.getElementById("width");
            const address = document.getElementById("height");
            const price = document.getElementById("price");
            const postcode = document.getElementById("description");


            if(name.value === ""){
                errors += "•Please enter a name.\n";
            }if(date.value === ""){
                errors += "•Please enter a date.\n";
            }if(width.value === ""){
                errors += "•Please enter a width.\n";
            }if(height.value === ""){
                errors += "•Please enter a height\n";
            }if(description.value === ""){
                errors += "•Please enter a description.\n";
            }if(price.value === ""){
                errors += "•Please enter a price.\n";
            }if(isNaN(width.value) && (width.value !== "")){
                errors += "•Width has to be a number.\n"
            }
            if(isNaN(height.value) && (height.value !== "")){
                errors += "•Height has to be a number.\n"
            }
            if(isNaN(price.value) && (price.value !== "")){
                errors += "•Price has to be a number.\n"
            }
            if(name.value.length > 20){
                errors += "•Name has to be shorter than 21 characters.\n"
            }
            if(errors !==""){
                window.alert(errors);
            }
            return (errors === "");
        }
    </script>
</form>
</body>
</html>