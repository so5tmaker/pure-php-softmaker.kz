<? include ("lock.php");?>
<? $name_dt = "друга"; $tbl_dt = "friends"; $action = "friend.php";
// делим файл на 6 частей: 3 с методом GET и 3 с методом POST
if (isset($_GET['mode'])){// 1-я часть 3 метода GET
    if ($_GET['mode'] == 'new') {// добавление нового значения
        $title_here = "Страница добавления ".$name_dt; include("header.html");
        echo "<h3 align='center'>Добавление ".$name_dt."</h3>";
        ?>
       <form name="form1" method="post" action="<? echo $action ?>">
         <p>
           <label>Введите название сайта <? echo $name_dt ?><br>
             <input type="text" name="title" id="title" size="<? echo $SizeOfinput ?>">
             </label>
         </p>
         <p>
           <label>Введите ссылку на сайт <? echo $name_dt ?><br>
           <input type="text" name="link" id="link" size="<? echo $SizeOfinput ?>">
           </label>
         </p>
         <input name="mode" type="hidden" value="add">
         <p>
           <label>
           <input type="submit" name="submit" id="submit" value="Занести сайт в базу">
           </label>
         </p>
       </form>
        <?
        }elseif ($_GET['mode'] == 'edit') {// редактирование значения
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "Страница редактирования ".$name_dt; include("header.html");
            if (!isset($id))
            {
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
                echo "<h3 align='center'>Редактирование ".$name_dt."</h3>";
                print <<<HERE
                        <form name='form1' method='post' action='$action'>
                         <p>
                           <label>Введите название сайта $name_dt<br>
                             <input value="$myrow[title]" type="text" name="title" id="title" size="$SizeOfinput">
                             </label>
                         </p>
                         <p>
                           <label>Введите ссылку на сайт $name_dt<br>
                            <input value="$myrow[link]" type="text" name="link" id="link" size="$SizeOfinput">
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
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "Страница удаления ".$name_dt; include("header.html");
            ?>
            <p><strong>Выберите <? echo $name_dt ?> для удаления </strong></p>
            <form action="<? echo $action ?>" method="post">
            <? 
            $result = mysql_query("SELECT title,id FROM ".$tbl_dt);      
            $myrow = mysql_fetch_array($result);
            do 
            {
                printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>",$myrow["id"],$myrow["title"]);
            }
            while ($myrow = mysql_fetch_array($result));
            ?>
            <input name="mode" type="hidden" value="drop">
            <p> <input name="submit" type="submit" value="Удаление <? echo $name_dt;?>"></p>
            </form>
            <?
      }//if ($_GET['mode'] == 'del')
 }//if (isset($_GET['mode']))
 
 if (isset($_POST['mode'])){// 2-я часть 3 метода POST
    if ($_POST['mode'] == 'add') {// добавление нового значения
        $title_here = "Страница добавления ".$name_dt; include("header.html");
        if (isset($_POST['title'])) {$title = $_POST['title']; if ($title == '') {unset($title);}}
        if (isset($_POST['link']))  {$link = $_POST['link'];   if ($link == '' ) {unset($link); }}
        if (isset($title) && isset($link))
        {
            $id = get_id($tbl_dt);
            $result = mysql_query ("INSERT INTO ".$tbl_dt." (id,title,link) VALUES ($id,'$title', '$link')");
            if ($result == 'true') {echo "<p align='center'>Добавление ".$name_dt." успешно завершено!</p>";}
            else {echo "<p align='center'>Добавление ".$name_dt." не прошло!</p>";}
        }		 
        else 
        {
            echo "<p align='center'>Вы ввели не всю информацию, поэтому добавление ".$name_dt." невозможно.</p>";
        }
     }elseif ($_POST['mode'] == 'update') {// редактирование значения
        $title_here = "Страница редактирования ".$name_dt; include("header.html");
        if (isset($_POST['title'])) {$title = $_POST['title']; if ($title == '') {unset($title);}}
        if (isset($_POST['link']))  {$link = $_POST['link']; if ($link == '') {unset($link);}}
        if (isset($_POST['id']))    {$id = $_POST['id'];}

        if (isset($title) && isset($link)){
        $result = mysql_query ("UPDATE ".$tbl_dt." SET title='$title', link='$link'  WHERE id='$id'");            
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
        if (isset($_POST['id'])) {$id = $_POST['id'];}
        $title_here = "Страница удаления ".$name_dt; include("header.html");
        if (isset($id)){
            $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
            if ($result == 'true') {echo "<p align='center'>Удаление ".$name_dt." успешно завершено!</p>";}
            else {echo "<p align='center'>Удаление ".$name_dt." не прошло!</p>";}
        }else{
            echo "<p>Вы запустили данный фаил без параметра id и поэтому, удаление ".$name_dt." невозможно (скорее всего Вы не выбрали радиокнопку на предыдущем шаге).</p>";
        }
    }
}//if (isset($_POST['mode']))
include_once ("footer.html");?>
