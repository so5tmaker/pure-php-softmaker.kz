<?
$name_dt = "баннера";
$tbl_dt = "banners";
?>
<? include ("../lock.php");?>
<? $title_here = "Страница добавления ".$name_dt; include("../header.html");?>
<form name_dt="form1" method="post" action="add_banner.php">
 <p>
   <label>Введите ссылку на статью <? echo $name_dt ?><br>
       <input type="text" name="href" id="href" size="<? echo $SizeOfinput ?>" >
     </label>
 </p>
 <p>
   <label>Введите ссылку на изображение <? echo $name_dt ?><br>
   <input type="text" name="imgsrc" id="imgsrc" size="<? echo $SizeOfinput ?>">
   </label>
 </p>
 <p>
   <label>Введите замещающий текст для изображения <? echo $name_dt ?><br>
   <input type="text" name="alt" id="alt" size="<? echo $SizeOfinput ?>">
   </label>
 </p>
 <? $size = getimagesize("http://softmaker.kz/img/5.jpg");
        print_r($size);
        $size[3] //=> width="570" height="128"
 ?>
 <p>
 <label>Введите количество <? echo $name_dt ?><br>
   <input type="text" name="author" id="author">
   </label>
 </p>
  
 <p>
   <label>Выберите категорию <? echo $name_dt ?><br>
   
   <select name="cat">
   
   <?
   
        $result = mysql_query("SELECT title,id FROM categories",$db);

        if (!$result)
        {
            echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@profcentre.kz. <br> <strong>Код ошибки:</strong></p>";
            exit(mysql_error());
        }
        $num_rows = mysql_num_rows($result);
        if ($num_rows > 0)
        {
            $myrow = mysql_fetch_array($result);
            do
            {
            printf ("<option value='%s'>%s</option>",$myrow["id"],$myrow["title"]);
            }
            while ($myrow = mysql_fetch_array($result));
        }
        else
        {
//            echo "<p>Для добавления ".$name_dt." нужно добавить хотя бы одну категорию к разделу!</p>";
//            echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
//            exit();
        }
        ?>

   </select>
   </label>
 </p>
 <?if ($num_rows == 0) echo "<p style='color: red' ><label>Для добавления ".$name_dt." нужно добавить хотя бы одну категорию к разделу!</label></p>";?>
<!-- <p>Добавлять в секретный раздел?<br>
   <label><strong>Да</strong>
   <input type="radio" name="secret" id="secret" value="1">
   </label>
   
    <label><strong>Нет</strong>
   <input type="radio" checked name="secret" id="secret" value="0">
   </label>
 </p>-->
 <input name="name_dt" type="hidden" value="<? echo $name_dt ?>">
 <input name="tbl_dt" type="hidden" value="<? echo $tbl_dt ?>">
 <p>
   <label>
   <input type="submit" name="submit" id="submit" value="<? echo "Занесение ".$name_dt." в базу" ?>">
   </label>
 </p>
</form>
<? include("../footer.html");?>
