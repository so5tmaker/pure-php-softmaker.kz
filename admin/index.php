<? 
include_once ("lock.php");
//require_once dirname(__FILE__)."/blocks/authenticate.inc";
//echo dirname(__FILE__);
//require_once dirname(__FILE__)."/lock.php";
//require_once "blocks/authenticate.inc";

$text = '����� ���������� � ��������� ����.';
if (isset($_GET['mode'])){
    if ($_GET['mode'] == 'del') {
        // ����� ������ ������
        $name_dt = '������';
        $result = mysql_query ('DELETE FROM `session`',$db);
        $kolvo = mysql_affected_rows();
        if ($result == 'true') {$text = "�������� ".$name_dt." (".$kolvo.") ������� ���������!";}
        else {$text = "�������� ".$name_dt." �� ������!";}
        del_all_cache();
        // ����� ���� ������
        $name_dt = '����� ������';
        $result = mysql_query ('DELETE FROM `error_log`',$db);
        $kolvo = mysql_affected_rows();
        if ($result == 'true') {$text = $text."</br>�������� ".$name_dt." (".$kolvo.") ������� ���������!";}
        else {$text = $text."</br>�������� ".$name_dt." �� ������!";}
    }
    if ($_GET['mode'] == 'delusers') {
                
        $name_dt = '������������� �����';
        $sql = 'DELETE FROM `users` WHERE (`active` = 0) AND (TO_DAYS( NOW( ) ) - TO_DAYS( `join_date` )) > 10'; 
        $result = mysql_query ($sql,$db);
        $kolvo = mysql_affected_rows();
        if ($result == 'true') {$text = "�������� ".$name_dt." (".$kolvo.") ������� ���������!";}
        else {$text = "�������� ".$name_dt." �� ������!";}
    }
}
$title_here = "������� �������� ����� ��������������"; include_once ("header.html");

?>

<p align="center"><? 
//codbanner(1);  
echo $text."<br>$url"; 
//phpinfo();
?></p>
<!--���������� ������ ����������� �������-->  
<?  include_once ("footer.html");?>

