<?php
//require_once 'blocks/global.inc.php';
$text = '����� ���������� � ��������� ����.';
if (isset($_GET['mode'])){
    if ($_GET['mode'] == 'set') {
//        remote_connect();
        set_stop();
    }
    if ($_GET['mode'] == 'del') {
        del_stop();
    }
}
// ������� ������������� ��� ������ �����
function set_stop($deep='')
{
        $filename = $deep.'stop.txt';
        $somecontent = "�������� ��� � �����\n";
        $fp = fopen($filename, 'w'); 
        fwrite($fp, $somecontent); 
        fclose($fp);
}

// ������� ������������� ��� �������� �����
function del_stop()
{
    $filename = 'stop.txt';
    if (file_exists($filename)){
        unlink ($filename);
    }
} // del_stop
//$title_here = "������� �������� ����� ��������������"; include_once ("header.html");
?>
<p align="center"><? // echo $text; //phpinfo();?></p>
<!--���������� ������ ����������� �������-->  
<? //  include_once ("footer.html");?>