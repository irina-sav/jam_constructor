<?php

error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

if(!empty($_GET)) {
    $page = $_GET["p"];
}
else {
    $page = "stranitsa1";
}
$content = file_get_contents("./pages/$page.html");
$color = @$_POST["color"] ?? "#ffff00";
$text = (isset($_POST["text"]) && $_POST["text"]) ? $_POST["text"] : $page;

addLog("date");