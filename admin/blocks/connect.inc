<?php

require_once dirname(__FILE__)."/bd.php";

$dbh = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(mysqli_connect_errno())
{
	error("������ ����������� � MySQL database: ������ �� ".DB_HOST." ����������!");
}

$versql = $dbh->server_info;
list($major, $minor) = explode(".", $versql);
$ver = $major.".".$minor;

$db_charset = DB_CHARSET;

if((float)$ver >= 4.1 AND !empty($db_charset))
{
    $dbh->query("SET NAMES '".DB_CHARSET."'");
}

?>