<?php
function addLog($info) {
    $date = date("d.m.Y H:i:s");
    file_put_contents("logs/log.txt", print_r($date, true));
}
