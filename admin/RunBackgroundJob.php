<?php
include ("lock.php");
$mode = filter_input(INPUT_GET, 'mode');
$send_get = filter_input(INPUT_GET, 'send');
if (isset($mode)){// ������ ���-�� ���������
    $title_here = "���������� ����������� �����";
    include("header.html");
    if ($_GET['mode'] == 'mailcount') {
        $co4 = mysql_query("SELECT * FROM `users` WHERE DATE_FORMAT(`last_mail`, '%Y-%c-%d') = CURDATE() AND (lang='".$lang."')");
        $count4 = mysql_num_rows($co4);
        print "<p align=center>����� ���������� �����: ".$count4."</p>";
    }
}  elseif (isset($send_get)) {
    $title_here = "�������� ������ ���� �������� ������������� �����";
    include("header.html");
    ?>
    <form name='add_form' method="post" action="RunBackgroundJob.php">
     <p>
       <label>���� ������:<br>
           <input type="text" name="theme" id="theme" size="<? echo $SizeOfinput ?>" >
       </label>
     </p>
     <p>
     <label>����� ������:<br>
       <textarea name="body" id="body" cols="<? echo $ColsOfarea ?>" rows="20"></textarea>
     </label>
     </p>
     <p>
       <label>
       <input type="submit" name="submit" id="submit" value="��������� ����">
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
            $title_here = "�������� ������ ���� �������� ������������� �����";
            require_once '../mail/send_mails_to_all.php';
        }else{
            include("header.html");
            echo "<p align=center>�� ����� �� ��� ����������, ������� ������ �� ����� ���� ����������!</p>";
            include_once ("footer.html");
            return;
        }
    }else{
        $title_here = "���������� � ����� �������";
        require_once '../mail/mail_notify.php';
    }
    require_once 'stop.php';
    del_stop();

    ignore_user_abort(1);  // ������������ ����� ����� � ���������
    set_time_limit(0);       // ����� ������ ������� �������������
    include("header.html");
    //$day_start = mktime(0, 0, 0, 6, 15, 2006);
    //$day_end   = mktime(24, 0, 0, date("m"), date("d"), date("y"));
    //$start_time = microtime();                               // ������ ����� ������� �������
    //$start_array = explode(' ',$start_time);             // ��������� ������� � ������������
    //$start_time = $start_array[1] + $start_array[0]; // �������� ������� � ������������ �������� ��������� ����� �������
    //do{
    if (isset($submit)){
        $letter[] = $theme;
        $letter[] = $body;
        $ret = send_mails($letter);
    }else{
        $ret = send_mails();
    }

    if ($ret == 'stop'){
      echo '������ ���� stop.txt!';
      echo '<h4 align=center>�������� ����� ���������!</h4>';
    } 
    if ($ret == 'stop1'){
    echo '���������� ������ ����� ����!';
    echo '<h4 align=center>�������� ����� ���������!</h4>';
    } 
    if ($ret == 'stop2'){
    echo '���������� ������������� ����� ����!';
    echo '<h4 align=center>�������� ����� ���������!</h4>';
    } 
    if ($ret == 'stop3'){
    echo '���������� ����� ������ ������������� �� �����!';
    echo '<h4 align=center>�������� ����� ���������!</h4>';
    }
    if ($ret == 'stop4'){
    echo '������ �������� �����!';
    echo '<h4 align=center>�������� ����� ���������!</h4>';
    }
    if ($ret !== ''){
        echo '������ �������� �����!';
        echo $ret;
    }
    //  break;}  //��������� �������, ����������� � ������� ������
    //} while( true );
}
include_once ("footer.html");
?>

