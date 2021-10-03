<?php
require "loger.php";
require "controller.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
           background-color: <?= $color?>;
     
        }
        h1 { 
            text-transform: uppercase;
            font-weight: 900;
            color: red;
            text-align: center;
        }
        p {
            color: black;
            font-style: italic;
        }
        input {
            margin: 10px;
        }

    </style>
</head>
<body>
<h1>
    <?= $text?>
</h1>
<div>
    <?= $content?>
</div>
<form action="" method="post">
    <input type="color" name="color" value="<?=$color?>"> 
    <br>
    <input type="text" name="text">
    <br>
    <button  id="button">изменить цвет</button>
</form>
<script src="./test.js"></script>
</body>
</html>