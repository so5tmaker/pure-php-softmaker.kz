<?php 
include ("lock.php");
if (isset($_POST['id'])) {$id = $_POST['id'];}
if (isset($_POST['name_dt'])) {$name_dt = $_POST['name_dt'];}
if (isset($_POST['tbl_dt'])) {$tbl_dt = $_POST['tbl_dt'];}

?>
<? $title_here = "����������"; include("header.html"); ?>
<?php

if (isset($id))
{
    $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
    if ($result == 'true') {echo "<p>�������� ".$name_dt." ������ �������!</p>";}
else {echo "<p>�������� ".$name_dt." �� ������!</p>";}
}		 
else 
{
    echo "<p>�� ��������� ������ ���� ��� ��������� id � �������, �������� ".$name_dt." ���������� (������ ����� �� �� ������� ����������� �� ���������� ����).</p>";
}
?>
<? include("footer.html");?>
