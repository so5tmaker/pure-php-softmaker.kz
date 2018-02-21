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
/* Если существует в глобальном массиве $_POST['title'] опр. ячейка, то мы создаем простую переменную из неё. Если переменная пустая, то уничтожаем переменную.   */
if (isset($_POST['meta_d']))      {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
if (isset($_POST['meta_k']))      {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
if (isset($_POST['date']))        {$date = $_POST['date']; if ($date == '') {unset($date);}}
if (isset($_POST['description'])) {$description = $_POST['description']; if ($description == '') {unset($description);}}
if (isset($_POST['text']))        {$text = $_POST['text']; if ($text == '') {unset($text);}}
if (isset($_POST['author']))      {$author = $_POST['author']; if ($author == '') {unset($author);}}

if (isset($_POST['img']))      {$img = $_POST['img']; if ($img == '') {unset($img);}}

if (isset($_POST['cat']))      {$cat = $_POST['cat']; if ($cat == '') {unset($cat);}}

if (isset($_POST['secret']))      {$secret = $_POST['secret']; if ($secret == '') {unset($secret);}}

if (isset($_POST['name_dt']))      {$name_dt = $_POST['name_dt']; if ($name_dt == '') {unset($name_dt);}}

if (isset($_POST['tbl_dt']))      {$tbl_dt = $_POST['tbl_dt']; if ($tbl_dt == '') {exit("Не могу найти таблицу для добавления данных!");}}

?>
<? $title_here = "Обработчик"; include("header.html"); ?>
<?php $img='default'; $secret="0";
if (isset($title) && isset($meta_d) && isset($meta_k) && isset($date) && isset($description) && isset($text) && isset($author) && isset($img) && isset($cat) && isset($secret))
{
    /* Здесь пишем что можно заносить информацию в базу */
    $result = mysql_query ("INSERT INTO ".$tbl_dt." (title,meta_d,meta_k,date,description,text,author,mini_img,cat,secret) VALUES ('$title', '$meta_d','$meta_k','$date','$description','$text','$author','$img','$cat','$secret')");
    if ($result == 'true') {echo "<p>Добавление ".$name_dt." прошло успешно!</p>";}
    else {echo "<p>Добавление ".$name_dt." не прошло!</p>";}
}		 
else 
{
    echo "<p>Вы ввели не всю информацию, поэтому добаления ".$name_dt." не будет!.</p>";
//    echo 'title '.'meta_d '.'meta_k '.'date '.'description '.'text '.'author '.'img '.'cat '.'secret';
//    echo '/n'.$title.' '.$meta_d.' '.$meta_k.' '.$date.' '.$description.' '.$text.' '.$author.' '.$img.' '.$cat.' '.$secret;
}
?>
<? include("footer.html");?>
       
