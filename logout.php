<?php
//Страница выхода еще проще. Эта страница не нуждается в HTML.
//Функция этой страницы выйти из приложения с помощью функции logout().
//После выхода пользователь редиректится на главную.

//logout.php
require_once 'blocks/global.inc.php';
$userTools = new UserTools();
$userTools->logout();
if (strstr($_SERVER['HTTP_REFERER'], 'uid') == FALSE){
    // удалить файл из кэша
    del_cache($tbl);
    //удачный вход, редирект на страницу
    header("Location: ".$_SERVER['HTTP_REFERER']);
}else{ // проверяю откуда пришли, если после активации, то index.php
        header("Location: index.php");
}

?>
