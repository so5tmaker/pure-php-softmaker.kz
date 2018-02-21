<? require_once 'blocks/global.inc.php';
$n=0;$text="";
$title = get_foreign_equivalent("Узнать свой внешний ip адрес");
$meta_k = 'ip адрес получить узнать get external ip address';
$meta_d = 'ip адрес узнать или получить свой внешний get your external ip address';
require_once ("header.html");

AdvTopPosition();

if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
    $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
}else{
    $ip=$_SERVER['REMOTE_ADDR'];
//when not using cloudflare
}

echo "<h1 class='post_title2'>".get_foreign_equivalent("Ваш внешний ip адрес").": $ip</h1>";

//Функция устанавливает кнопки социальных сетей
show_social_buttons();

//Функция выводит форму подписки на статьи по почте
show_form_subscribe_by_mail();

require_once ("blocks/centraltd.php");      

require_once ("footer.html");?>
