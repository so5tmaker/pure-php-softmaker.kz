<?php
include ("lock.php");
$mode = filter_input(INPUT_GET, 'mode');
$send_get = filter_input(INPUT_GET, 'send');
if (isset($mode)){// считаю кол-во сообщений
    $title_here = "Количество отправленых писем";
    include("header.html");
    if ($_GET['mode'] == 'mailcount') {
        $co4 = mysql_query("SELECT * FROM `users` WHERE DATE_FORMAT(`last_mail`, '%Y-%c-%d') = CURDATE() AND (lang='".$lang."')");
        $count4 = mysql_num_rows($co4);
        print "<p align=center>Всего отправлено писем: ".$count4."</p>";
    }
}  elseif (isset($send_get)) {
    $title_here = "Отправка письма всем активным пользователям сайта";
    include("header.html");
    ?>
    <form name='add_form' method="post" action="RunBackgroundJob.php">
     <p>
       <label>Тема письма:<br>
           <input type="text" name="theme" id="theme" size="<? echo $SizeOfinput ?>" >
       </label>
     </p>
     <p>
     <label>Текст письма:<br>
       <textarea name="body" id="body" cols="<? echo $ColsOfarea ?>" rows="20"></textarea>
     </label>
     </p>
     <p>
       <label>
       <input type="submit" name="submit" id="submit" value="Отправить всем">
       </label>
     </p>
    </form>
<?
}  else {
    error_reporting(1);
    $submit = filter_input(INPUT_POST, 'submit');
    $theme = filter_input(INPUT_POST, 'theme');
    $body = filter_input(INPUT_POST, 'body');
    if ($theme == '') {unset($theme);}
    if ($body == '') {unset($body);}
    if (isset($submit)){
        if (isset($theme) && isset($body)) { 
            $title_here = "Отправка письма всем активным пользователям сайта";
            require_once '../mail/send_mails_to_all.php';
        }else{
            include("header.html");
            echo "<p align=center>Вы ввели не всю информацию, поэтому письмо не может быть отправлено!</p>";
            include_once ("footer.html");
            return;
        }
    }else{
        $title_here = "Оповещение о новых статьях";
        require_once '../mail/mail_notify.php';
    }
    require_once 'stop.php';
    del_stop();

    ignore_user_abort(1);  // Игнорировать обрыв связи с браузером
    set_time_limit(0);       // Время работы скрипта неограниченно
    include("header.html");
    //$day_start = mktime(0, 0, 0, 6, 15, 2006);
    //$day_end   = mktime(24, 0, 0, date("m"), date("d"), date("y"));
    //$start_time = microtime();                               // Узнаем время запуска скрипта
    //$start_array = explode(' ',$start_time);             // Разделяем секунды и миллисекунды
    //$start_time = $start_array[1] + $start_array[0]; // Сумируем секунды и миллисекунды получаем стартовое время скрипта
    //do{
    if (isset($submit)){
        $letter[] = $theme;
        $letter[] = $body;
        $ret = send_mails($letter);
    }else{
        $ret = send_mails();
    }

    if ($ret == 'stop'){
      echo 'Найден файл stop.txt!';
      echo '<h4 align=center>Отправка писем завершена!</h4>';
    } 
    if ($ret == 'stop1'){
    echo 'Количество статей равно нулю!';
    echo '<h4 align=center>Отправка писем завершена!</h4>';
    } 
    if ($ret == 'stop2'){
    echo 'Количество пользователей равно нулю!';
    echo '<h4 align=center>Отправка писем завершена!</h4>';
    } 
    if ($ret == 'stop3'){
    echo 'Количество писем меньше максимального их числа!';
    echo '<h4 align=center>Отправка писем завершена!</h4>';
    }
    if ($ret == 'stop4'){
    echo 'Ошибка отправки писем!';
    echo '<h4 align=center>Отправка писем завершена!</h4>';
    }
    if ($ret !== ''){
        echo 'Ошибка отправки писем!';
        echo $ret;
    }
    //  break;}  //Остановка скрипта, работающего в фоновом режиме
    //} while( true );
}
include_once ("footer.html");
?>

