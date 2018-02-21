<? include ("lock.php");?>
<? $name_dt = "текста"; $tbl_dt = "settings"; $action = "text.php";
// делим файл на 6 частей: 3 с методом GET и 3 с методом POST
if (isset($_GET['mode'])){// 1-я часть 3 метода GET
    if ($_GET['mode'] == 'new') {// добавление нового значения
        $title_here = "Страница добавления ".$name_dt; include("header.html");
        echo "<h3 align='center'>Добавление ".$name_dt."</h3>";
        ?>
       
        <?
        }elseif ($_GET['mode'] == 'edit') {// редактирование значения
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "Страница редактирования ".$name_dt; include("header.html");
            if (!isset($id)){
                $result = mysql_query("SELECT title,id FROM ".$tbl_dt);      
                $myrow = mysql_fetch_array($result);
                do 
                {
                    printf ("<p><a href='%s?id=%s&mode=edit'>%s</a></p>",$action,$myrow["id"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
            }else{
                $result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE id=$id");      
                $myrow = mysql_fetch_array($result);
                print <<<HERE
                <form name="form1" method="post" action="$action">
                 <p>
                   <label>Введите название страницы (тэг title)<br>
                     <input value="$myrow[title]" type="text" name="title" id="title" size="$SizeOfinput">
                     </label>
                 </p>
                 <p>
                   <label>Введите краткое описание страницы<br>
                   <input value="$myrow[meta_d]" type="text" name="meta_d" id="meta_d" size="$SizeOfinput">
                   </label>
                 </p>
                 <p>
                   <label>Введите ключевые слова для страницы<br>
                   <input value="$myrow[meta_k]" type="text" name="meta_k" id="meta_k" size="$SizeOfinput">
                   </label>
                 </p>
                 <p>
                   <label>Введите полный текст страницы с тэгами
                   <textarea name="text" id="text" cols="$ColsOfarea" rows="20">$myrow[text]</textarea>
                   </label>
                 </p>
                    <input name="id" type="hidden" value="$myrow[id]">
                 <p>
                 <input name="mode" type="hidden" value="update">
                   <label>
                   <input type="submit" name="submit" id="submit" value="Сохранить изменения">
                   </label>
                 </p>
               </form>
HERE;
         }
     }elseif ($_GET['mode'] == 'del') {// удаление значения
     }//if ($_GET['mode'] == 'del')
 }//if (isset($_GET['mode']))
 if (isset($_POST['mode'])){// 2-я часть 3 метода POST
    if ($_POST['mode'] == 'add') {// добавление нового значения
        $title_here = "Страница добавления ".$name_dt; include("header.html");
     }elseif ($_POST['mode'] == 'update') {// редактирование значения
        $title_here = "Страница редактирования ".$name_dt; include("header.html");
        if (isset($_POST['title'])) {$title = $_POST['title']; if ($title == '') {unset($title);}}
        if (isset($_POST['meta_d'])) {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
        if (isset($_POST['meta_k'])) {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
        if (isset($_POST['text']))   {$text = $_POST['text']; if ($text == '') {unset($text);}}
        if (isset($_POST['id']))     {$id = $_POST['id'];}

        if (isset($title) && isset($meta_d) && isset($meta_k) && isset($text)){
        $result = mysql_query ("UPDATE ".$tbl_dt." SET title='$title', meta_d='$meta_d', meta_k='$meta_k', text='$text' WHERE id='$id'");          
        if ($result == 'true') {
                echo "<p align='center'>Обновление ".$name_dt." успешно завершено!</p>";
            }else{
                echo "<p align='center'>Обновление ".$name_dt." не прошло!</p>";
            }
        }		 
        else 
        {
            echo "<p align='center'>Вы ввели не всю информацию, поэтому обновление ".$name_dt." невозможно.</p>";
        }
    }elseif ($_POST['mode'] == 'drop') {// удаление значения
    }
}//if (isset($_POST['mode']))
include_once ("footer.html");?>
