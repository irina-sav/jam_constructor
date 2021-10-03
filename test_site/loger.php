<?php
function addLog($info) {
    $date = date("d.m.Y H:i:s");
    $oneLog = $date . " ==> " . print_r($info, true) . "\n";
    file_put_contents("logs/log.txt", $oneLog, FILE_APPEND);
   
}
