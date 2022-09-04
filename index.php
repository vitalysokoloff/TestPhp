<?php
include 'functions.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>PHP Test</title>
    <link rel="stylesheet" href="../css/styles.css">    
</head>

<body>
    <header>
        <div class="rim">
            <form action="" method="get">
                <input type="text" id="request" name="request" value="Text" minlength="3" maxlength="200">
                <input type="submit" value="Find">
            </form> 
        </div>
    </header>
    <?php
        if (array_key_exists('request', $_GET)){
            Find($_GET['request']);
        }
    ?>
    <footer>
            <div class="rim">
                PHP Test by Vitaly Sokoloff (vitaliysokoloff@gmail.com) | 2022
            </div> 
        </footer>
</body>
</html>

