<?php
session_start(["cookie_lifetime" => 5]);
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
$color = @$_POST["color"] ?? @$_SESSION['saveColor'] ?? "#ffff00";
$text = (isset($_POST["text"]) && $_POST["text"]) ? $_POST["text"] : ($_SESSION['saveText'] ?? $page);

addLog($_POST);

$_SESSION['saveColor'] = $color;
$_SESSION['saveText'] = $text;

