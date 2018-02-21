<?php 
require_once "admin/config.php";
connectToDB();
$ShowLogo=mysql_query("SELECT Sale FROM Admin", $link) or die (mysql_error($link));
while($data = mysql_fetch_array($ShowLogo)){
if ($data['Sale']=="NO"){ header("Location:notsale.php"); exit;}
}
mysql_close($link);
require_once "admin/function.php";
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!--<meta charset="windows-1251">-->
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="css/normalize.css"/>
    <link href="admin/addons/lightbox/css/lightbox.css" type="text/css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css"/>

    <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
    <script type="text/javascript" src="admin/addons/lightbox/js/lightbox.js"></script>
	<script type="text/javascript" src="http://scriptjava.net/source/scriptjava/scriptjava.js"></script>
    <script type="text/javascript" src="js/my.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('a[data-lightbox]').lightbox();
        })
    </script>
    
    <!--/* Скрипт для закрытия внешние ссылки от индексации*/-->
    <script type="text/javascript">
    function GoTo(link, s){window.open(link.replace("_","http"+s+"://"));
    }
    </script>

    <!--[if lt IE 9]>
        <script src="common/scripts/html5shiv.js"></script>
    <![endif]-->