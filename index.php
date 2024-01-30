<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Front Page</title>
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

<?php


    require 'password.php';
    $host = "devweb2022.cis.strath.ac.uk"; //boilerplate code from the lectures -> not my own invention!
    $user = "vib20191";
    $pass = get_password();
    $dbname = $user;
    $conn = new mysqli($host, $user, $pass, $dbname);

    $sql = "SELECT * FROM `art`";
    $result = $conn->query($sql);

    function checkID($id, $conn){
        $id = preg_replace('/[^0-9]/','',$id);
        $stmt = "SELECT * FROM orders WHERE imageID=$id";
        $result = $conn->query($stmt);
        if(mysqli_num_rows($result) === 0){
            return false;
        }
        return true;
    }

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $name = $row["name"];
            $date = $row["dateOfCompletion"];
            $width = $row["width"];
            $height = $row["height"];
            $price = $row["price"];
            $description = $row["description"];
            $id = $row["id"];
            $sold = false;

        ?>
<div class="image">

    <img id="thumbnail" src="https://devweb2022.cis.strath.ac.uk/~vib20191/313a2/show.php?id=<?php echo $id?>" alt="<?php echo $description?>">
    <b><p class="title"><?php echo $name?></p></b>
    <p>
        <?php if(checkID($id, $conn)){
            $sold = true;
            echo "SOLD";
        }
        else{
            echo "£".$price;
        }?>
        <form action="form.php" method="post">
        <input type="hidden" name="name" value="<?php echo $name;?>">
        <input type="hidden" name="date" value=<?php echo $date;?>>
        <input type="hidden" name="width" value=<?php echo $width;?>>
        <input type="hidden" name="height" value=<?php echo $height;?>>
        <input type="hidden" name="price" value=<?php echo $price;?>>
        <input type="hidden" name="description" value="<?php echo $description;?>">
        <input type="hidden" name="id" value=<?php echo $id?>>
        <?php
            if(!$sold){
                echo "<input class='submit' type='submit' value='ORDER'>";
            }
        ?>
        </form>
    </p>
</div>
<?php
    }
    }

?>
</body>
</html>

