<?php
include ("lock.php");
$tbl_dt = 'users';
$name_dt = 'адресов';
$title_here = "Страница получения ".$name_dt; include("header.html");
$resultru = mysql_query("SELECT email FROM ".$tbl_dt." WHERE lang='RU'",$db);
//mail.ru - facebook, yandex - twitter, gmail - blogger email LIKE '%yandex%' AND email not LIKE '%gmail%' AND 

if (!$resultru)
{
    echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
    exit(mysql_error());
}
if (mysql_num_rows($resultru) > 0)
{
    $myrowru = mysql_fetch_array($resultru);
    $mails = 0;
    $mailsbr = 0;
    do
    {
        $mails += 1; $mailsbr += 1;
//        if ($mails == 4){echo "<p align='center'>$mails</p>"; $mails = 0;}
//        if ($mailsbr == 4){$br = "</br>"; $mailsbr = 0;}else{$br = "";}
        printf ("%s,",$myrowru["email"].$br); // "<p align='center'>%s,</p>"
    }
    while ($myrowru = mysql_fetch_array($resultru));
} else {
    echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
    exit();
}
include_once ("footer.html");
?>
