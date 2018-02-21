<?php 

if(!isset($ROOT)){
    $d = dirname(__FILE__);
    $ROOT = str_replace('/admin/blocks', '', $d);
} 
/** Абсолютный путь к директории проекта. */
if ( !defined('ABSPATH') )
	define('ABSPATH', $ROOT . '/');
// получаю полный корневой путь
$DIR = ABSPATH;

//Здесь я проверяю путь к файлу исполняемого в данный момент скрипта,
//чтобы определить какую базу мне нужно локальную или удаленную
$SCRIPT = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
$SERVER = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'); //$SERVER = $_SERVER['SCRIPT_FILENAME'];
$USER_AGENT = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'); // $_SERVER['HTTP_USER_AGENT'];
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');
// хочу найти домашнюю папку локального сайта
$PHP_SELF = filter_input(INPUT_SERVER, 'PHP_SELF');
$cut_slash = substr($PHP_SELF, 1); // отсекаю первый слэш
$pos = strpos($cut_slash, "/"); // нахожу позицию первого вхождения символа "/" ."/" .'/'
$rest = substr($cut_slash, 0, $pos); // возвращает, например "phpbloguser"
$rest1 = ($rest=="") ? $rest : "/".$rest;
$rest_ = ($rus !== false) ? $rest1 : '' ;

if ($rus !== false){$url = "http://".$HOST."/".$rest."/";}else{$url = 'http://www.softmaker.kz/';}

if ($rus !== false)
{
    // ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
    /** Имя базы данных для проекта */
    define('DB_NAME', 'phpblog');

    /** Имя пользователя MySQL */
    define('DB_USER', 'bloguser');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD', '12345');

    /** Имя сервера MySQL */
    define('DB_HOST', 'localhost');
}
else
{

    // ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
    /** Имя базы данных для проекта */
    define('DB_NAME', 'db1088065_db');

    /** Имя пользователя MySQL */
    define('DB_USER', 'u1088065_root');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD', ']budetr');

    /** Имя сервера MySQL */
    define('DB_HOST', 'mysql677.cp.idhost.kz');

}

define('DB_CHARSET', 'cp1251');

$db=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);

mysql_query("SET NAMES '".DB_CHARSET."'");

// получаю строку для управления вложенностью папок
function deep() {
    global $rest_, $PHP_SELF;
    $ending = str_replace($rest_, "",$PHP_SELF);
    $deep_count = substr_count($ending, '/');
    $deep = '';
    for ($i = 2; $i <= $deep_count; $i++) {
        $deep .= '../';
    }
    return $deep;
}