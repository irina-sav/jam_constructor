<?php
try {
    if(@$_POST["componentId"]){
        if(is_numeric($_POST["componentId"])){
            $bdConnect = mysqli_connect("31.31.196.95", "u1433184_jam", "hG0uI8rU9ksS3f", "u1433184_jambase");
            mysqli_query($bdConnect, "SET NAMES utf8");

            if (!$bdConnect) {
                throw new Exception('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
            }
            $data = mysqli_query($bdConnect, "select * from `components` where `id` = {$_POST["componentId"]}");
            $printData = mysqli_fetch_assoc($data);
            if(empty($printData)){
                throw new Exception("не найдено в БД");
            }
           
            exit(json_encode($printData, 256));
          
        }   
        throw new Exception("некорректное значение componentId");
    }
    throw new Exception("некорректный запрос");

}
catch(Exception $e) {
    echo("error: " . $e->getMessage());
}