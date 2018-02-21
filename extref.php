<?php 
//extref.php
require_once 'blocks/global.inc.php';

$ref[1] = "http://1popov.ru/softmaker/disc8";
$ref[2] = "http://1popov.ru/softmaker/disc11";
$ref[3] = "http://www.000webhost.com/338284.html";
$ref[4] = "http://nicedit.com";
$ref[5] = "http://www.microsoft.com/downloads/ru-ru/details.aspx?familyId=967225EB-207B-4950-91DF-EEB5F35A80EE&hash=mz5ItOxL4ET3ruLbAqeJFCScW7SAS2rGDeNLdA6bTPrlSmGwVPnkdCvHCcVoQnn8V7OaYGDlDhg%2bbDx2Ddo37w%3d%3dhttp://www.microsoft.com/downloads/ru-ru/details.aspx?familyId=967225EB-207B-4950-91DF-EEB5F35A80EE&hash=mz5ItOxL4ET3ruLbAqeJFCScW7SAS2rGDeNLdA6bTPrlSmGwVPnkdCvHCcVoQnn8V7OaYGDlDhg%2bbDx2Ddo37w%3d%3d";
$ref[6] = "http://www.microsoft.com/downloads/ru-ru/details.aspx?FamilyID=60cb5b6c-6532-45e0-ab0f-a94ae9ababf5http://www.microsoft.com/downloads/ru-ru/details.aspx?FamilyID=60cb5b6c-6532-45e0-ab0f-a94ae9ababf5";
$ref[7] = "http://www.webdesign.org";
$ref[8] = "http://synapse.ararat.cz/doku.php";
$ref[9] = "http://search.yahoo.com/search;_ylt=A0geusmmVM1LjbsABDBXNyoA?p=%D0%B0%D0%BA%D1%82%D0%B5%D1%80+%D0%90%D1%80%D0%BA%D0%B0%D0%B4%D0%B8%D0%B9+%D0%A0%D0%B0%D0%B9%D0%BA%D0%B8%D0%BD&fr2=sb-top&fr=fp-pull-web-t&sao=1";
$ref[10] = "http://www.google.com/webhp?hl=ru#hl=ru&source=hp&q=%D0%B0%D0%BA%D1%82%D0%B5%D1%80+%D0%90%D1%80%D0%BA%D0%B0%D0%B4%D0%B8%D0%B9+%D0%A0%D0%B0%D0%B9%D0%BA%D0%B8%D0%BD&btnG=%D0%9F%D0%BE%D0%B8%D1%81%D0%BA+%D0%B2+Google&lr=lang_ru&aq=f&aqi=&aql=&oq=%D0%B0%D0%BA%D1%82%D0%B5%D1%80+%D0%90%D1%80%D0%BA%D0%B0%D0%B4%D0%B8%D0%B9+%D0%A0%D0%B0%D0%B9%D0%BA%D0%B8%D0%BD&gs_rfai=&fp=c8966e1eddbe7a15";
$ref[11] = "http://www.elated.com";
$ref[12] = "http://www.phptoys.com";
$ref[13] = "http://www.ozgrid.com"; 
$ref[14] = "http://code.activestate.com/komodo/remotedebugging/"; 

if (isset($_GET['num'])){
    $num=$_GET['num'];
}else{
    if (isset($_GET['ref'])){
        $URL=$ref[$_GET['ref']];
        header("Location:$URL");
        exit();
    }else{
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
}
 
$n=0;$text="";
$title = get_foreign_equivalent("Переход по внешней ссылке")." - $num - softmaker.kz";
//$meta_k = 'ip адрес получить узнать get external ip address';
//$meta_d = 'ip адрес узнать или получить свой внешний get your external ip address';
require_once ("header.html");

//AdvTopPosition();
$link = get_lang_link();
$reftxt = get_foreign_equivalent("Внимание! Вы собираетесь перейти по внешней ссылке. Чтобы завершить переход нажмите")
. " <a href='$link/extref.php?ref=$num'>".get_foreign_equivalent('здесь')."</a>.";
// get_foreign_equivalent("Переход по внешней ссылке")
echo "<h1 class='post_title2'>".$reftxt."</h1>";

//Функция выводит форму подписки на статьи по почте
show_form_subscribe_by_mail();

require_once ("blocks/centraltd.php");

require_once ("footer.html");?>
