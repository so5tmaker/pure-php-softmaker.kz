<?php 

if(!isset($ROOT)){
    $d = dirname(__FILE__);
    $ROOT = str_replace('/admin/blocks', '', $d);
} 
/** ���������� ���� � ���������� �������. */
if ( !defined('ABSPATH') )
	define('ABSPATH', $ROOT . '/');
// ������� ������ �������� ����
$DIR = ABSPATH;

//����� � �������� ���� � ����� ������������ � ������ ������ �������,
//����� ���������� ����� ���� ��� ����� ��������� ��� ���������
$SCRIPT = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
$SERVER = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'); //$SERVER = $_SERVER['SCRIPT_FILENAME'];
$USER_AGENT = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'); // $_SERVER['HTTP_USER_AGENT'];
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');
// ���� ����� �������� ����� ���������� �����
$PHP_SELF = filter_input(INPUT_SERVER, 'PHP_SELF');
$cut_slash = substr($PHP_SELF, 1); // ������� ������ ����
$pos = strpos($cut_slash, "/"); // ������ ������� ������� ��������� ������� "/" ."/" .'/'
$rest = substr($cut_slash, 0, $pos); // ����������, �������� "phpbloguser"
$rest1 = ($rest=="") ? $rest : "/".$rest;
$rest_ = ($rus !== false) ? $rest1 : '' ;

if ($rus !== false){$url = "http://".$HOST."/".$rest."/";}else{$url = 'http://www.softmaker.kz/';}

if ($rus !== false)
{
    // ** ��������� MySQL: ��� ���������� ����� �������� � ������ �������-���������� ** //
    /** ��� ���� ������ ��� ������� */
    define('DB_NAME', 'phpblog');

    /** ��� ������������ MySQL */
    define('DB_USER', 'bloguser');

    /** ������ � ���� ������ MySQL */
    define('DB_PASSWORD', '12345');

    /** ��� ������� MySQL */
    define('DB_HOST', 'localhost');
}
else
{

    // ** ��������� MySQL: ��� ���������� ����� �������� � ������ �������-���������� ** //
    /** ��� ���� ������ ��� ������� */
    define('DB_NAME', 'db1088065_db');

    /** ��� ������������ MySQL */
    define('DB_USER', 'u1088065_root');

    /** ������ � ���� ������ MySQL */
    define('DB_PASSWORD', ']budetr');

    /** ��� ������� MySQL */
    define('DB_HOST', 'mysql677.cp.idhost.kz');

}

define('DB_CHARSET', 'cp1251');

$db=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);

mysql_query("SET NAMES '".DB_CHARSET."'");

// ������� ������ ��� ���������� ������������ �����
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