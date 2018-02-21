<?php
include_once("afunction.php");

//Здесь я проверяю путь к файлу исполняемого в данный момент скрипта,
//чтобы определить какую базу мне нужно локальную или удаленную
$ROOT = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //$SERVER = $_SERVER['DOCUMENT_ROOT'];
$SCRIPT = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
$SERVER = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'); //$SERVER = $_SERVER['SCRIPT_FILENAME'];
$USER_AGENT = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'); // $_SERVER['HTTP_USER_AGENT'];
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');

if ($rus !== false){$url = "http://".$HOST."/".$rest."/";}else{$url = 'http://'.$HOST.'/';}

if ($rus !== false)
{
    // ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
    /** Имя базы данных для проекта */
    define('BRO_DB_NAME', 'phpblog');

    /** Имя пользователя MySQL */
    define('BRO_DB_USER', 'bloguser');

    /** Пароль к базе данных MySQL */
    define('BRO_DB_PASSWORD', '12345');

    /** Имя сервера MySQL */
    define('BRO_DB_HOST', 'localhost');
}
else
{

    /** Все значения в кавычках */

    /** имя базы данных для скрипта БаннерБро */
    define('BRO_DB_NAME', 'db1088065_db');
    /** имя пользователя MySQL */
    define('BRO_DB_USER', 'u1088065_root');
    /** пароль к базе данных MySQL */
    define('BRO_DB_PASSWORD', ']budetr');
    /** имя сервера MySQL */
    define('BRO_DB_HOST', 'mysql677.cp.idhost.kz');

}

//mysql_query("SET NAMES 'cp1251'");
//mysql_query("SET NAMES 'utf8'"); 
//mysql_query("SET CHARACTER SET 'utf8';"); 
//mysql_query("SET SESSION collation_connection = 'utf8_general_ci';");

?>