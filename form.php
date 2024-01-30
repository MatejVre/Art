<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Form</title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.onscroll = function() {scrollFunction()}; //not my own invention -> https://www.w3schools.com/howto/howto_js_shrink_header_scroll.asp
        function scrollFunction() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                document.getElementById("header").style.fontSize = "1em";

            } else {
                document.getElementById("header").style.fontSize = "2.5em";
            }
        }
    </script>
</head>
<body>
<div id="header">
    <h1>Cara's Art Store</h1>

</div>
<a href="index.php">Home Page</a>
<?php

    function sendEmail($email, $CustomerName, $name){
        $subject = "Order Confirmation";
        $header = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
        $emailMessage = "<p>Dear ".$CustomerName.",<br><br>Thank you for ordering \"".$name."\"!<br><br>We will try to get your order to you as soon as possible! <br><br>
                        If you would wish to order again, you can do so by clicking <a href='https://devweb2022.cis.strath.ac.uk/~vib20191/313a2/index.php'>HERE</a>";
        mail($email, $subject, wordwrap($emailMessage) , $header);
    }

    function safePost($conn, $name){
        return isset($_POST[$name])? $conn -> real_escape_string(strip_tags($_POST[$name])): "";
    }
    //fields coming from the main website
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://devweb2022.cis.strath.ac.uk/~vib20191/313a2/index.php"){
        $name = $_POST["name"];
        $_SESSION["sessionName"] = $name;
        $date = $_POST["date"];
        $_SESSION["sessionDate"] = $date;
        $width = $_POST["width"];
        $_SESSION["sessionWidth"] = $width;
        $height = $_POST["height"];
        $_SESSION["sessionHeight"] = $height;
        $price = $_POST["price"];
        $_SESSION["sessionPrice"] = $price;
        $description = $_POST["description"];
        $_SESSION["sessionDescription"] = $description;
        $id = $_POST["id"];
        $_SESSION["sessionId"] = $id;
    }
    else{
        $name = $_SESSION["sessionName"];
        $date = $_SESSION["sessionDate"];
        $width = $_SESSION["sessionWidth"];
        $height = $_SESSION["sessionHeight"];
        $price = $_SESSION["sessionPrice"];
        $description = $_SESSION["sessionDescription"];
        $id = $_SESSION["sessionId"];
    }
    require 'password.php';
    $host = "devweb2022.cis.strath.ac.uk"; //boilerplate code from the lectures -> not my own invention!
    $user = "vib20191";
    $pass = get_password();
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    $customerName = isset($_POST["Cname"]) && ($_POST["Cname"] !== "") ? $_POST["Cname"] : "";
    $customerPN = isset($_POST["phone-number"]) && ($_POST["phone-number"] !== "") ? $_POST["phone-number"] : "";
    $customerEmail = isset($_POST["email"]) && ($_POST["email"] !== "") ? $_POST["email"] : "";
    $customerAddress = isset($_POST["address"]) && ($_POST["address"] !== "") ? $_POST["address"] : "";
    $customerPostCode = isset($_POST["postcode"]) && ($_POST["postcode"] !== "") ? $_POST["postcode"] : "";
    $customerCity = isset($_POST["city"]) && ($_POST["city"] !== "") ? $_POST["city"] : "";

    if($customerName && $customerPN && $customerEmail && $customerAddress && $customerPostCode && $customerCity) {
        $dname = safePost($conn, "Cname");
        $dphone = safePost($conn, "phone-number");
        $dmail = safePost($conn, "email");
        $daddres = safePost($conn, "address");
        $dpostcode = safePost($conn, "postcode");
        $dcity = safePost($conn, "city");
        $dimageID = $conn->real_escape_string(strip_tags($id));
        $sql = "INSERT INTO `orders` (`name`, `phone`, `email`, `address`, `postcode`, `city`, `id`, `imageID`)".
        "VALUES ('$dname', '$dphone', '$dmail', '$daddres', '$dpostcode', '$dcity', null, '$dimageID');";
        $conn->query($sql);
        echo "<p id='center'>Thank you for your order!</p>";
        sendEmail($customerEmail, $customerName, $name);
    }
    else{
        echo "<div class='description'>".
            "<img src='https://devweb2022.cis.strath.ac.uk/~vib20191/313a2/show.php?id=<?php echo $id?>'>".
            "<p>Title: ".$name."</p>".
            "<p>Date of creation: ".$date."</p>".
            "<p>Width: ".$width."</p>".
            "<p>Height: ".$height."</p>".
            "<p>Price: ".$price."</p>".
            "<p>".$description."</p>"."</div>";
?>
    <div>
        <h2>Order</h2>
    </div>
    <form action="form.php" method="post" onsubmit="return check()" id="form">
        <label for="name">Name: </label>
            <input id="name" type="text" name="Cname" value="<?php echo $customerName?>"><br>
        <label for="phone-number">Phone-number: </label>
            <input id="phone-number" type="tel" name="phone-number" value="<?php echo $customerPN?>"><br>
        <label for="email">Email: </label>
            <input id="email" type="email" name="email" value="<?php echo $customerEmail?>"><br>
        <label for="address">Address: </label>
            <input id="address" type="text" name="address" value="<?php echo $customerAddress?>"><br>
        <label for="postcode">Postcode: </label>
            <input id="postcode" type="text" name="postcode" value="<?php echo $customerPostCode?>"><br>
        <label for="city">City: </label>
            <input id="city" type="text" name="city" value="<?php echo $customerCity?>"><br>
        <input class="submit" type="submit" value="ORDER">
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
                const phone = document.getElementById("phone-number");
                const email = document.getElementById("email");
                const address = document.getElementById("address");
                const postcode = document.getElementById("postcode");
                const city = document.getElementById("city");

                if(name.value === ""){
                    errors += "•Please enter your name.\n";
                }if(phone.value === ""){
                    errors += "•Please enter your phone number.\n";
                }if(email.value === ""){
                    errors += "•Please enter your email.\n";
                }if(address.value === ""){
                    errors += "•Please enter your address.\n";
                }if(postcode.value === ""){
                    errors += "•Please enter your postcode.\n";
                }if(city.value === ""){
                    errors += "•Please enter your city.\n";
                }
                if(isNaN(phone.value) && (phone.value !== "")){
                    errors += "•Phone number has to be a number.\n"
                }
                if(phone.value.toString().length > 11){
                    errors += "•Phone number is too long.\n"
                }
                if(errors !==""){
                    window.alert(errors);
                }

                return (errors === "");
            }
        </script>
</body>
</html>
