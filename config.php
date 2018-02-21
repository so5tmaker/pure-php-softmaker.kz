<?php
//«десь € провер€ю путь к файлу исполн€емого в данный момент скрипта,
//чтобы определить какую базу мне нужно локальную или удаленную
$ROOT = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //$SERVER = $_SERVER['DOCUMENT_ROOT'];
$SCRIPT = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
$SERVER = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'); //$SERVER = $_SERVER['SCRIPT_FILENAME'];
$USER_AGENT = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'); // $_SERVER['HTTP_USER_AGENT'];
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');
// хочу найти домашнюю папку локального сайта
$PHP_SELF = filter_input(INPUT_SERVER, 'PHP_SELF');
$cut_slash = substr($PHP_SELF, 1); // отсекаю первый слэш
$pos = strpos($cut_slash, "/"); // нахожу позицию первого вхождени€ символа "/" ."/" .'/'
$rest = substr($cut_slash, 0, $pos); // возвращает, например "phpbloguser"
$rest1 = ($rest=="") ? $rest : "/".$rest;
$rest_ = ($rus !== false) ? $rest1 : '' ;

$d = dirname(__FILE__);
/** јбсолютный путь к директории проекта. */
if ( !defined('ABSPATH') )
	define('ABSPATH', str_replace('/blocks', '', $d) . '/');

// получаю полный корневой путь
$DIR = ABSPATH;

