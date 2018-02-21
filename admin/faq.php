<? include ("lock.php");
require_once 'Gdata/Blogger.php';
$name_dt = "вопроса"; $tbl_dt = "faq";
// делим файл на 6 частей: 3 с методом GET и 3 с методом POST

if (isset($_GET['mode'])){// 1-я часть 3 метода GET
    if ($_GET['mode'] == 'new') {// добавление нового значения
        $title_here = "Страница добавления ".$name_dt; include("header.html");
        if (!isset($_GET['post'])) {

            echo "<p align='center'>Выберите заметку</p>";

            $result = mysql_query("SELECT title,id,cat FROM data WHERE faq='1' ORDER BY `date` DESC",$db);

            if (!$result)
            {
            echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
            exit(mysql_error());
            }
            if (mysql_num_rows($result) > 0)
            {
            $myrow = mysql_fetch_array($result);
            do
            {
            printf ("<p><a href='faq.php?cat=%s&post=%s&mode=new&title=%s'>%s</a></p>",$myrow["cat"],$myrow["id"],$myrow["title"],$myrow["title"]);
            }
            while ($myrow = mysql_fetch_array($result));
            }
            else
            {
            echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
            exit();
            }
       }else{
           if (isset($_GET['post'])) {$post = $_GET['post'];}else{exit('Не могу найти id заметки!');}
           if (isset($_GET['cat'])) {$cat = $_GET['cat'];}else{exit('Не могу найти cat заметки!');}
           echo "<p>Добавление ".$name_dt." для заметки ".$_GET['title']."</p>";
            ?>
            <form name='add_form' method="post" action="faq.php">
             <p>
               <label>Введите вопрос<br>
                   <input type="text" name="question" id="question" size="<? echo $SizeOfinput ?>" >
               </label>
             </p>
             <p>
             <label>Введите ответ на вопрос<br>
               <textarea name="answer" id="answer" cols="<? echo $ColsOfarea ?>" rows="5"></textarea>
             </label>
             </p>
             <p>
                <label>Введите дату добавления <? echo $name_dt ?><br>
                <input name="date" type="text" id="date" value="<?php $date = date("Y-m-d"); echo $date; ?>">
                </label>
              </p>
              <p>
                <label>
                <input type="checkbox" name="ChangeBlogger" id="ChangeBlogger" >
                Изменять запись <? echo $name_dt ?> в Blogger?
                </label>
             </p>
             <input name="post" type="hidden" value="<?echo $post?>">
             <input name="cat" type="hidden" value="<?echo $cat?>">
             <input name="mode" type="hidden" value="add">
              <p>
               <label>
               <input type="submit" name="submit" id="submit" value="<? echo "Ввести" ?>">
               </label>
             </p>
            </form>
            <?}
        }elseif ($_GET['mode'] == 'edit') {// редактирование значения
            $title_here = "Страница редактирования ".$name_dt; include("header.html");
            if (!isset($_GET['post'])AND(!isset($_GET['id']))) {

                echo "<p align='center'>Выберите заметку</p>";

                $result = mysql_query("SELECT title,id,cat FROM data WHERE faq='1' ORDER BY `date` DESC",$db);

                if (!$result)
                {
                echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
                exit(mysql_error());
                }
                if (mysql_num_rows($result) > 0)
                {
                $myrow = mysql_fetch_array($result);
                do
                {
                printf ("<p><a href='faq.php?cat=%s&post=%s&mode=edit&title=%s'>%s</a></p>",$myrow["cat"],$myrow["id"],$myrow["title"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
                }
                else
                {
                echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
                exit();
                }
            }elseif (isset($_GET['post'])AND(!isset($_GET['id']))) {
                echo "<p align='center'>Редактирование ".$name_dt." для заметки ".$_GET['title']."</p>";

                if (isset($_GET['post'])) {$post = $_GET['post'];}else{exit('Не могу найти id заметки для редактирования!');}
                if (isset($_GET['cat'])) {$cat = $_GET['cat'];}else{exit('Не могу найти cat заметки для редактирования!');}

                $result = mysql_query("SELECT * FROM faq WHERE post=".$_GET['post']." ORDER BY id DESC",$db);

                if (!$result)
                {
                echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
                exit(mysql_error());
                }
                if (mysql_num_rows($result) > 0)
                {
                    $myrow = mysql_fetch_array($result);
                    do
                    {
                    printf ("<p><a href='faq.php?id=%s&post=%s&cat=%s&mode=edit'>%s</a></p>",$myrow["id"],$post,$cat,$myrow["question"]);
                    }
                    while ($myrow = mysql_fetch_array($result));
                }
            }else{
                $id=$_GET['id'];
                if (isset($_GET['post'])) {$post = $_GET['post'];}else{exit('Не могу найти id заметки для редактирования!');}
                if (isset($_GET['cat'])) {$cat = $_GET['cat'];}else{exit('Не могу найти cat заметки для редактирования!');}
                $result = mysql_query("SELECT * FROM faq WHERE id=$id");
                $myrow = mysql_fetch_array($result);?>
                <form name='edit_form' method="post" action="faq.php">
                 <p>
                   <label>Введите название <? echo $name_dt ?><br>
                       <input type="text" name="question" id="question" size="<? echo $SizeOfinput ?>" value="<?echo $myrow['question']?>">
                   </label>
                 </p>
                 <p>
                 <label>Введите ответ на вопрос<br>
                   <textarea name="answer" id="answer" cols="<?echo $ColsOfarea?>" rows="5">
                       <?
                       if ($rus !== false){
                           echo $myrow[answer];
                       }else{echo stripslashes(htmlspecialchars($myrow[answer]));}

                       ?>
                   </textarea>
                 </label>
                 </p>
                 <p>
                    <label>Введите дату добавления <? echo $name_dt ?><br>
                    <input name="date" type="text" id="date" value="<?php echo $myrow['date'] ?>">
                    </label>
                  </p>
                  <p>
                    <label>
                    <input type="checkbox" name="ChangeBlogger" id="ChangeBlogger" >
                    Изменять запись <? echo $name_dt ?> в Blogger?
                    </label>
                 </p>
                 <input name="mode" type="hidden" value="update">
                 <input name="id" type="hidden" value="<?echo $id?>">
                 <input name="post" type="hidden" value="<?echo $post?>">
                 <input name="cat" type="hidden" value="<?echo $cat?>">
                 <input name="blog_id" type="hidden" value="<? echo $myrow[blog_id] ?>">
                  <p>
                   <label>
                   <input type="submit" name="submit" id="submit" value="<? echo "Ввести" ?>">
                   </label>
                 </p>
                </form>
            <?
            }
        }elseif ($_GET['mode'] == 'del') {// удаление значения
            $title_here = "Страница удаления ".$name_dt; include("header.html");
            if (!isset($_GET['post'])AND(!isset($_GET['id']))) {

                echo "<p align='center'>Выберите заметку</p>";

                $result = mysql_query("SELECT title,id,cat FROM data WHERE faq='1' ORDER BY `date` DESC",$db);

                if (!$result)
                {
                echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
                exit(mysql_error());
                }
                if (mysql_num_rows($result) > 0)
                {
                $myrow = mysql_fetch_array($result);
                do
                {
                printf ("<p><a href='faq.php?cat=%s&post=%s&mode=del&title=%s'>%s</a></p>",$myrow["cat"],$myrow["id"],$myrow["title"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
                }
                else
                {
                echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
                exit();
                }
            }else{?>
                <p align='center'><strong>Выберите элемент для удаления <? echo $name_dt;?></strong></p>
                <form action="faq.php" method="post">
                <?
                if (isset($_GET['post'])) {$post = $_GET['post'];}else{exit('Не могу найти id заметки для удаления!');}
                if (isset($_GET['cat'])) {$cat = $_GET['cat'];}else{exit('Не могу найти cat заметки для удаления!');}
                $result = mysql_query("SELECT id,question FROM ".$tbl_dt." WHERE post=".$post);
                $myrow = mysql_fetch_array($result);
                do
                {
                    printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>",$myrow["id"],$myrow["question"]);
                }
                while ($myrow = mysql_fetch_array($result));
                ?>
                <input name="cat" type="hidden" value="<? echo $cat;?>">
                <input name="post" type="hidden" value="<?echo $post?>">
                <input name="mode" type="hidden" value="drop">
                <p> <input name="submit" type="submit" value="Удаление <? echo $name_dt;?>"></p>
                </form>
                <?
      }//if ($_GET['mode'] == 'del')
   }//if (isset($_GET['mode']))
}
 if (isset($_POST['mode'])){// 2-я часть 3 метода POST
    if ($_POST['mode'] == 'add') {// добавление нового значения
        $title_here = "Страница добавления вопроса"; include("header.html");
        if (isset($_POST['cat'])) {$cat = $_POST['cat'];}else{exit('Не могу найти cat заметки для удаления!');}
        if (isset($_POST['post'])){$post = $_POST['post']; if ($post == '') {unset($post);}}
        if (isset($_POST['question'])){$question = $_POST['question']; if ($question == '') {unset($question);}}
        if (isset($_POST['answer'])){$answer = $_POST['answer']; if ($answer == '') {unset($answer);}}
        if (isset($_POST['date']))  {$date = $_POST['date']; if ($date == '') {unset($date);}}
        
        if (isset($_POST['ChangeBlogger']))    {$ChangeBlogger = '1';}else{$ChangeBlogger = '0';}
        
        if (isset($answer) && isset($question) && isset($post) && isset($date)) { 
            /* Здесь пишем что можно заносить информацию в базу */
            $id = get_id($tbl_dt);
            $faq = array (
            "id"  => $id,
            "answer" => $answer,
            "question"  => $question,
            "date" => $date,
            );
            $result_post = mysql_query("SELECT * FROM data WHERE (id=$post)", $db);
            $myrow_post = mysql_fetch_array($result_post);
            $num_rows = mysql_num_rows($result_post);
            // если количество записей не равно нулю
            if ($num_rows!=0) {
                $blog_id = ''; $message = TRUE;
                if (!$rus AND $ChangeBlogger == '1') {
                    try {
                        $filename = 'articles';
                        $message = CreateSmartMessage($myrow_post, $faq);
                        $newBlog = new Blog();
                        $newBlog->promptForBlogID();
                        $blog_id = $newBlog->createPost(strip_tags($message[title]), $message[body], $date, $message[label]);
                    } catch (Exception $e) {
                        echo '<p align=center>ERROR:' . $e->getMessage() . '</p>';
                        $blog_id = '';
                    }
                }
                if ($message != FALSE) { 
                // если добавление в blogger прошло успешно
                    $answer = addslashes($answer);
                    $result = mysql_query ("INSERT INTO $tbl_dt (id,post,question,answer,date,blog_id) VALUES ($id,'$post','$question','$answer','$date','$blog_id')");

                    if ($result == 'true') {
                        echo "<p align='center'>Добавление ".$name_dt." успешно завершено!</p>";
                    }else{
                        echo "<p align='center'>Добавление ".$name_dt." не прошло!</p>";
                    }
                }
            } else { 
                echo "<p align='center'>Статьи с id: ".$post." не существует!</p>";
            }
         } else {
            echo "<p>Вы ввели не всю информацию, поэтому вопрос в базу не может быть добавлен.</p>";
         }
    } elseif ($_POST['mode'] == 'update') {// редактирование значения
        if (isset($_POST['id'])) {$id = $_POST['id'];}else{exit('Не могу найти id заметки для редактирования!');}
        if (isset($_POST['post'])) {$post = $_POST['post'];}else{exit('Не могу найти post заметки для редактирования!');}
        if (isset($_POST['cat'])) {$cat = $_POST['cat'];}else{exit('Не могу найти cat заметки для редактирования!');}
        $title_here = "Страница редактирования вопроса"; include("header.html");
        if (isset($_POST['question'])){$question = $_POST['question']; if ($question == '') {unset($question);}}
        if (isset($_POST['answer'])){$answer = $_POST['answer']; if ($answer == '') {unset($answer);}}
        if (isset($_POST['date']))        {$date = $_POST['date']; if ($date == '') {unset($date);}}

        $blog_id = '';
        if (isset($_POST['blog_id'])) {
            $blog_id = $_POST['blog_id']; 
        }

        if (isset($_POST['ChangeBlogger'])) {
            $ChangeBlogger = '1';
        }else{$ChangeBlogger = '0';}

        if (isset($answer) && isset($question) && isset($date)){ // && isset($blog_id)
            $faq = array (
            "id"  => $id,
            "answer" => $answer,
            "question"  => $question,
            "date" => $date,
            );
            $result_post = mysql_query("SELECT * FROM data WHERE (id=$post)", $db);
            $myrow_post = mysql_fetch_array($result_post);
            $num_rows = mysql_num_rows($result_post);
            // если количество записей не равно нулю
            if ($num_rows!=0) {
                if (!$rus AND $ChangeBlogger == '1')
                {
                    try {
                        $filename = 'articles';
                        $message = CreateSmartMessage($myrow_post, $faq);
                        $newBlog = new Blog();
                        $newBlog->promptForBlogID();
                        if (!empty($blog_id)){
                            $newBlog->updatePost($blog_id, strip_tags($message[title]), $message[body], False);
                        } else {
                            $blog_id = $newBlog->createPost(strip_tags($message[title]), $message[body], $date, $message[label]);
                        }
                    } catch (Exception $e) {
                        echo '<p align=center>ERROR:' . $e->getMessage() . '</p>';  
                    }
                }
                $answer = addslashes($answer);
                $result = mysql_query ("UPDATE faq SET question='$question', answer='$answer', date='$date', blog_id='$blog_id' WHERE id='$id'");
                if ($result == 'true') {
                    echo "<p align='center'>Обновление вопроса прошло успешно!</p>";
//                    del_cache($post,'articles');
                }
                else {echo "<p align='center'>Обновление вопроса не прошло!</p>";}
            } else { 
                echo "<p align='center'>Статьи с id: ".$post." не существует!</p>";
            }
        } else {
            echo "<p align='center'>Вы ввели не всю информацию, поэтому обновление ".$name_dt." невозможно.</p>";
        }
    }elseif ($_POST['mode'] == 'drop') {// удаление значения
        if (isset($_POST['id'])) {$id = $_POST['id'];}else{exit('Не могу найти id заметки для удаления!');}
        if (isset($_POST['post'])) {$post = $_POST['post'];}else{exit('Не могу найти post заметки для удаления!');}
        if (isset($_POST['cat'])) {$cat = $_POST['cat'];}else{exit('Не могу найти cat заметки для удаления!');}

        $title_here = "Обработчик"; include("header.html");
        if (isset($id))
        {
            $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
            if ($result == 'true') {echo "<p align='center'>Удаление ".$name_dt." прошло успешно!</p>";
            del_cache($post,'articles');
            }
        else {echo "<p align='center'>Удаление ".$name_dt." не прошло!</p>";}
        }
        else
        {
            echo "<p align='center'>Вы запустили данный файл без параметра id и поэтому, удаление ".$name_dt." невозможно (скорее всего Вы не выбрали радиокнопку на предыдущем шаге).</p>";
        }
    }
}//if (isset($_POST['mode']))
include_once ("footer.html");?>
