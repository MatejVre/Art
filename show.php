<?php

//img GET parameter sets the ID - code here extracts only the digits
$which = isset($_GET["id"])?$_GET["id"]:'';
$which = preg_replace('/[^0-9]/', '', $which);
if (strlen($which)===0) { die("No valid img id given"); }

try {
    //connect to the database
    require 'password.php';
    $host = "devweb2022.cis.strath.ac.uk";//change year for devweb
    $user = "vib20191";
    $pass = get_password();
    $conn = new PDO("mysql:host={$host};dbname={$user}",$user,$pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*** The sql statement ***/
    $stmt = $conn->prepare("SELECT image FROM art WHERE id=:id");
    $stmt->bindParam(':id', $which);
    $stmt->execute();

    /*** Process results - we can only have 0 or 1 result from the select **/
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $data = $row["image"];
        //output MIME-TYPE header - must have no outputs before this
        header("Content-type: image/jpeg");
        // output the image
        echo $data;
    } else {
        die("Select failed - no matching rows");//FIXME only show error during debugging
    }
} catch (PDOEXCEPTION $e) {
    die($e->getMessage());//FIXME only show error during debugging
}

?> 