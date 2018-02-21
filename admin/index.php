<? 
include_once ("lock.php");
//require_once dirname(__FILE__)."/blocks/authenticate.inc";
//echo dirname(__FILE__);
//require_once dirname(__FILE__)."/lock.php";
//require_once "blocks/authenticate.inc";

$text = 'Добро пожаловать в админский блок.';
if (isset($_GET['mode'])){
    if ($_GET['mode'] == 'del') {
        // удалю старые сессии
        $name_dt = 'сессий';
        $result = mysql_query ('DELETE FROM `session`',$db);
        $kolvo = mysql_affected_rows();
        if ($result == 'true') {$text = "Удаление ".$name_dt." (".$kolvo.") успешно завершено!";}
        else {$text = "Удаление ".$name_dt." не прошло!";}
        del_all_cache();
        // удалю логи ошибок
        $name_dt = 'логов ошибок';
        $result = mysql_query ('DELETE FROM `error_log`',$db);
        $kolvo = mysql_affected_rows();
        if ($result == 'true') {$text = $text."</br>Удаление ".$name_dt." (".$kolvo.") успешно завершено!";}
        else {$text = $text."</br>Удаление ".$name_dt." не прошло!";}
    }
    if ($_GET['mode'] == 'delusers') {
                
        $name_dt = 'пользователей сайта';
        $sql = 'DELETE FROM `users` WHERE (`active` = 0) AND (TO_DAYS( NOW( ) ) - TO_DAYS( `join_date` )) > 10'; 
        $result = mysql_query ($sql,$db);
        $kolvo = mysql_affected_rows();
        if ($result == 'true') {$text = "Удаление ".$name_dt." (".$kolvo.") успешно завершено!";}
        else {$text = "Удаление ".$name_dt." не прошло!";}
    }
}
$title_here = "Главная страница блока администратора"; include_once ("header.html");

?>

<p align="center"><? 
//codbanner(1);  
echo $text."<br>$url"; 
//phpinfo();
?></p>
<!--Подключаем нижний графический элемент-->  
<?  include_once ("footer.html");?>

