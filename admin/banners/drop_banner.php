<?php 
include ("lock.php");
if (isset($_POST['id'])) {$id = $_POST['id'];}
if (isset($_POST['name_dt'])) {$name_dt = $_POST['name_dt'];}
if (isset($_POST['tbl_dt'])) {$tbl_dt = $_POST['tbl_dt'];}

?>
<? $title_here = "Обработчик"; include("header.html"); ?>
<?php

if (isset($id))
{
    $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
    if ($result == 'true') {echo "<p>Удаление ".$name_dt." прошло успешно!</p>";}
else {echo "<p>Удаление ".$name_dt." не прошло!</p>";}
}		 
else 
{
    echo "<p>Вы запустили данный файл без параметра id и поэтому, удаление ".$name_dt." невозможно (скорее всего Вы не выбрали радиокнопку на предыдущем шаге).</p>";
}
?>
<? include("footer.html");?>
