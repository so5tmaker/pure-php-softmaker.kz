<?
//require_once 'blocks/db.php';
//require_once '../config.php';
require_once("blocks/bd.php");
//$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//
//$sql = "SELECT * FROM `userlist`";
//$result = $mysqli->query($sql);
///* ассоциативный массив */
//$row = $result->fetch_array(MYSQLI_ASSOC);
//printf ("%s (%s)\n", $row["id"], $row["user"]);

$db=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);
$sql = "SELECT * FROM `userlist`";
$result = mysql_query($sql, $db);
$row = mysql_fetch_array($result);
printf ("%s (%s)\n", $row["id"], $row["user"]);

echo "<br>DOCUMENT_ROOT ".$_SERVER['DOCUMENT_ROOT'];

/** Абсолютный путь к директории проекта. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

echo "<br>ABSPATH ".ABSPATH;

echo phpinfo();