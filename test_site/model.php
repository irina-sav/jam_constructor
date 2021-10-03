<?php

$bdConnect = mysqli_connect("31.31.196.95", "u1433184_jam", "hG0uI8rU9ksS3f", "u1433184_jambase");

if (!$bdConnect) {
    die('<br>' . 'Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}
echo '<br>' . 'Соединение установлено... ' . mysqli_get_host_info($bdConnect) . "\n";
mysqli_query($bdConnect, "SET NAMES utf8");
echo '<br>';
// $data = mysqli_query($bdConnect, "select * from `customer` where `phone` like '%22%'");
$data = mysqli_query($bdConnect, "select * from `orders`");

$printData = mysqli_fetch_all($data, MYSQLI_ASSOC);
function printCode($code) {
    echo "<pre>";
    print_r($code);
    echo "</pre>";
}
foreach($printData as $onePrintData ) {
   if ($onePrintData["amount"] > 2000) {
    printCode($onePrintData);
    mysqli_query($bdConnect, "update `orders` set `complete` = 'complete' where `id`= {$onePrintData['id']}");
   }
    
}
printCode($printData);



