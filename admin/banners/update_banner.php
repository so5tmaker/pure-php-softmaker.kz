<?php
include ("lock.php");
if (isset($_POST['title']))       
{
    $title = $_POST['title'];
    if ($title == '')
    {
        unset($title);
    }
}
$img='default'; $secret="0";
/* Если существует в глобальном массиве $_POST['title'] опр. ячейка, то мы создаем простую переменную из неё. Если переменная пустая, то уничтожаем переменную.   */
if (isset($_POST['meta_d']))      {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
if (isset($_POST['meta_k']))      {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
if (isset($_POST['date']))        {$date = $_POST['date']; if ($date == '') {unset($date);}}
if (isset($_POST['description'])) {$description = $_POST['description']; if ($description == '') {unset($description);}}
if (isset($_POST['text']))        {$text = $_POST['text']; if ($text == '') {unset($text);}}
if (isset($_POST['author']))      {$author = $_POST['author']; if ($author == '') {unset($author);}}
if (isset($_POST['id']))      {$id = $_POST['id'];}

if (isset($_POST['cat']))      {$cat = $_POST['cat']; if ($cat == '') {unset($cat);}}

if (isset($_POST['img']))      {$img = $_POST['img']; if ($img == '') {unset($img);}}

if (isset($_POST['secret']))      {$secret = $_POST['secret']; if ($secret == '') {unset($secret);}}

if (isset($_POST['name_dt']))      {$name_dt = $_POST['name_dt']; if ($name_dt == '') {unset($name_dt);}}

if (isset($_POST['tbl_dt']))      {$tbl_dt = $_POST['tbl_dt']; if ($tbl_dt == '') {exit("Не могу найти таблицу для добавления данных!");}}

?>
<? $title_here = "Обработчик"; include("header.html"); ?>
<?php 
if (isset($title) && isset($meta_d) && isset($meta_k) && isset($date) && isset($description) && isset($text) && isset($author) && isset($cat) && isset($img))
{
/* Здесь пишем что можно заносить информацию в базу */
$result = mysql_query ("UPDATE ".$tbl_dt." SET title='$title', meta_d='$meta_d', meta_k='$meta_k', date='$date', description='$description', text='$text', author='$author', cat='$cat', mini_img='$img', secret='$secret' WHERE id='$id'");
if ($result == 'true') {echo "<p>Обновление ".$name_dt." прошло успешно!</p>";}
else {echo "<p>Обновление ".$name_dt." не прошло!</p>";}
}		 
else 
{
echo "<p>Вы ввели не всю информацию, поэтому обновления ".$name_dt." не будет!</p>";
}
?>
<? include("footer.html");?>
