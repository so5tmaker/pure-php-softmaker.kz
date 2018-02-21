<?php
include ("lock.php");
if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
}else{ //when not using cloudflare
    $ip=$_SERVER['REMOTE_ADDR'];
}
$mytext = trim(fgets($fp, 999));
echo "ftp://softmaker:]budets@".$ip;
?>

