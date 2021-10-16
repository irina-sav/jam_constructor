<?php
try {
    if(@$_POST["color"] && @$_POST["text"]) {
   
        if(strlen($_POST["text"]) > 5){
            exit(json_encode($_POST));
        }
        
        throw new Exception("Недостаточно символов");
    }
    elseif(@$_POST["user"]){
        if(strlen($_POST["user"]) > 2){
            $bdConnect = mysqli_connect("31.31.196.95", "u1433184_jam", "hG0uI8rU9ksS3f", "u1433184_jambase");
            mysqli_query($bdConnect, "SET NAMES utf8");

            if (!$bdConnect) {
                throw new Exception('<br>' . 'Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
            }
            $data = mysqli_query($bdConnect, "select `phone` from `customer` where `name` like '%{$_POST["user"]}%'");
            $printData = mysqli_fetch_all($data, MYSQLI_ASSOC);
                        
            exit(print_r($printData[0]['phone'], true));
        }   
        throw new Exception("Недостаточно символов имени");
    }
    throw new Exception("Не заполнены поля");

}
catch(Exception $e) {
    echo("error: " . $e->getMessage());
} //вместо Ecxeption можно использовать Throwable