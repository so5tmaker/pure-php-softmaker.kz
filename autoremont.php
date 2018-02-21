<? 
require_once 'blocks/global.inc.php';

$n=0;
$title = "Ремонт автомобилей";
include_once ("header.html");
 
AdvTopPosition();
        ?>
   
        <h1 class='post_title2'>Ремонт автомобилей.</h1>
        <p align="center" class='post_comment'>
        Ремонт гидравлики (редукторов, насосов, реек), механических реек,
реставрация шлангов высокого давления, замена ГРМ,
реставрация ходовой. Мелкосрочный ремонт двигателя.<br><br>
Быстро! Качественно! Недорого!<br>
        <br>Телефоны в Алматы: моб. +7 705 105 98 64</p>
        <br>

<?

//Функция устанавливает кнопки социальных сетей
show_social_buttons();

//Функция выводит форму подписки на статьи по почте
show_form_subscribe_by_mail();

include_once ("blocks/centraltd.php");

include_once ("footer.html");?>
