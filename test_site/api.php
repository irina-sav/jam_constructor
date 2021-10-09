<?php
try {if(@$_POST["color"] && @$_POST["text"]) {
   
    if(strlen($_POST["text"]) > 5){
        exit(json_encode($_POST));
    }
    throw new Exception("Недостаточно символов");
    // else {
    //     echo("error: Недостаточно символов");
    // }
}
throw new Exception("Не заполнены поля");
// else {
//     echo("error: Не заполнены поля");
// }
}
catch(Exception $e) {
    echo("error: " . $e->getMessage());
} //вместо Ecxeption можно использовать Throwable