<?php
//require_once 'blocks/global.inc.php';
$text = 'Добро пожаловать в админский блок.';
if (isset($_GET['mode'])){
    if ($_GET['mode'] == 'set') {
//        remote_connect();
        set_stop();
    }
    if ($_GET['mode'] == 'del') {
        del_stop();
    }
}
// Функция предназначена для записи файла
function set_stop($deep='')
{
        $filename = $deep.'stop.txt';
        $somecontent = "Добавить это к файлу\n";
        $fp = fopen($filename, 'w'); 
        fwrite($fp, $somecontent); 
        fclose($fp);
}

// Функция предназначена для удаления файла
function del_stop()
{
    $filename = 'stop.txt';
    if (file_exists($filename)){
        unlink ($filename);
    }
} // del_stop
//$title_here = "Главная страница блока администратора"; include_once ("header.html");
?>
<p align="center"><? // echo $text; //phpinfo();?></p>
<!--Подключаем нижний графический элемент-->  
<? //  include_once ("footer.html");?>