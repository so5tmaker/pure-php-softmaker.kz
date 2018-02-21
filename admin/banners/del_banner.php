<?php include ("lock.php");
if (isset($_GET['id'])) {$id = $_GET['id'];}
if (isset($_GET['name_dt'])) {$name_dt = $_GET['name_dt'];}
if (isset($_GET['tbl_dt'])) {$tbl_dt = $_GET['tbl_dt'];}

?>
<? $title_here = "Страница удаления ".$name_dt; include("header.html");?>
<p><strong>Выберите элемент для удаления <? echo $name_dt;?></strong></p>
<form action="drop_data.php" method="post">
<?

$result = mysql_query("SELECT title,id FROM ".$tbl_dt);
$myrow = mysql_fetch_array($result);
do 
{
    printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>",$myrow["id"],$myrow["title"]);

}
while ($myrow = mysql_fetch_array($result));
?>
<input name="name_dt" type="hidden" value="<? echo $name_dt?>">
<input name="tbl_dt" type="hidden" value="<? echo $tbl_dt?>">
<p> <input name="submit" type="submit" value="Удаление <? echo $name_dt;?>"></p>
</form>
<? include("footer.html");?>
