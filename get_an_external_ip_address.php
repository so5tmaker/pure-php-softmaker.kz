<?php
if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
// $_SERVER['REMOTE_ADDR']= $_SERVER["HTTP_CF_CONNECTING_IP"]; also possible, no else needed then
}else{
    $ip=$_SERVER['REMOTE_ADDR'];
//when not using cloudflare
}
echo $ip;
echo phpinfo();
?>
