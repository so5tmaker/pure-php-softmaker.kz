<? include ("lock.php");

require_once 'Gdata/Blogger.php';
//require_once "block/global.inc.php";

// Функция предназначена для получения значения любого поля (cat) по id
function get_fld_by_id($id, $tbl, $fld = 'cat', $add_clause = "")
{   
    global $db;
    $result = mysql_query("SELECT ".$fld." FROM ".$tbl." WHERE (id=$id) ".$add_clause,$db);
    $myrow = mysql_fetch_array($result);
    $num_rows = mysql_num_rows($result);
    // если количество записей не равно нулю
    if ($num_rows!=0)
    {
        return $myrow[$fld];
    } else { 
        return '0';
    }
}

// функция выдаёт ссылку, меняяя язык домена
function get_other_lang_link($lang){
    $link = "http://www.softmaker.kz/";
    if ($lang == "EN") {
        $link = str_replace("www", 'en', $link);
    }
     return $link;
 } // get_other_lang_link

if (isset($_GET['sec'])) {
    $sec = $_GET['sec'];
}  else {
    if (isset($_POST['sec'])) {$sec = $_POST['sec'];}
}
$tbl_dt = "data"; $action = "data.php";
if ($sec == '1') {
    $name_dt = "статьи"; $filename = 'articles';
} elseif ($sec == '2') {
    $name_dt = "закачки"; $filename = 'files'; 
}
$translit = TRUE;
// делим файл на 6 частей: 3 с методом GET и 3 с методом POST
if (isset($_GET['mode'])){// 1-я часть 3 метода GET
    if ($_GET['mode'] == 'new') {// добавление нового значения
        $title_here = "Страница добавления ".$name_dt; include("header.html");
        echo "<h3 align='center'>Добавление ".$name_dt."</h3>";
        ?>
        <form name="form1" method="post" action="<? echo $action ?>">
        <p>
           <label>Введите название <? echo $name_dt ?> (лучше не более 70 символов)<br>
             <input type="text" name="title" id="title" size="<? echo $SizeOfinput ?>">
             </label>
         </p>
         <p>
           <label>Введите транслитерацию названия <? echo $name_dt ?><br>
             <input type="text" name="name" id="name" size="<? echo $SizeOfinput ?>">
             </label>
         </p>
         <p>
             <input name="button" type="button" value="Транслитерировать" size="<? echo $SizeOfinput ?>" onclick="SetTranslitRuToLat()">
         </p>
         <p>
           <label>Введите краткое описание <? echo $name_dt ?> (лучше не более 160 символов)<br>
           <input type="text" name="meta_d" id="meta_d" size="<? echo $SizeOfinput ?>">
           </label>
         </p>
         <p>
           <label>Введите ключевые слова для <? echo $name_dt ?><br>
           <input type="text" name="meta_k" id="meta_k" size="<? echo $SizeOfinput ?>">
           </label>
         </p>
         <p>
           <label>Введите дату добавления <? echo $name_dt ?><br>
           <input name="date" type="text" id="date" value="<?php $date = date("Y-m-d"); echo $date; ?>">
           </label>
         </p>
         <p>
           <label>Ведите краткое описание <? echo $name_dt ?> с тэгами абзацев
           <textarea name="description" id="description" cols="<? echo $ColsOfarea ?>" rows="5"></textarea>
           </label>
         </p>
         <p>
           <label>Введите полный текст <? echo $name_dt ?> с тэгами
           <textarea name="text" id="text" cols="<? echo $ColsOfarea ?>" rows="20"></textarea>
           </label>
         </p>
         <p>
           <label>Введите автора <? echo $name_dt ?><br>
           <input type="text" name="author" id="author" size="<? echo $SizeOfinput ?>">
           </label>
         </p>
         
         <p>
           <label>Введите где лежит миниатюра<br>
           <input type="text" name="img" id="img" size="<? echo $SizeOfinput ?>">
           </label>
         </p>
        <p>
          <label>Введите где лежит файл <? echo $name_dt ?><br>
          <input type="text" name="file" id="file" size="<? echo $SizeOfinput ?>">
          </label>
        </p>
        <p>
            <label>
            <input type="checkbox" name="faq" id="faq">
            Заметка будет включать ответы на часто задаваемые вопросы (FAQ)
            </label>
        </p>
         <p>
            <label>
                <input type="checkbox" name="phpcolor" id="phpcolor">
                У <? echo $name_dt ?> будем окрашивать PHP код?
            </label>
         </p>
         <p>
            <label>
            <input type="checkbox" name="ChangeBlogger" id="ChangeBlogger" >
            Изменять <? echo $name_dt ?> в Blogger?
            </label>
         </p>
         <p>
            <label>
            <input type="checkbox" name="notprohibit" id="notprohibit" >
             Не запрещать скрытие <? echo $name_dt ?> для посетителей
            </label>
         </p>
         <p>
           <? 
                $result = mysql_query("SELECT title,id FROM categories WHERE (sec='".$sec."' OR sec IN (SELECT id FROM sections WHERE id_lang='".$sec."')) ORDER BY id_lang,id",$db);

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
                  $cat_list = $cat_list."<option value='".$myrow["id"]."'>".$myrow["title"]."</option>";
                }
                while ($myrow = mysql_fetch_array($result));
                }
                else
                {
                echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
                exit();
                }
                ?>
                <label>Выберите категорию <? echo $name_dt ?><br><?
                echo "<select name='cat'>";
                echo $cat_list;
                echo "</select></label><br>";
                ?>
         </p>
         <p>
           <label>Выберите язык <? echo $name_dt ?><br>
           <select name="lang">
           <?
                foreach ($langs as $val) {
                   printf ("<option value='%s'>%s</option>",$val,$val);
                }
           ?>
           </select>
           </label>
         </p>
         <p>
           <label>Выберите название <? echo $name_dt ?> на русском языке<br>
           <select name="id_lang">
          <? 
                printf ("<option value='%s'>%s</option>",0,"<<Название ".$name_dt." не выбрано>>");
                $resultru = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE lang='RU'",$db);

                if (!$resultru)
                {
                    echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
                    exit(mysql_error());
                }
                if (mysql_num_rows($resultru) > 0)
                {
                    $myrowru = mysql_fetch_array($resultru);
                    do
                    {
                        printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                    }
                    while ($myrowru = mysql_fetch_array($resultru));
                } else {
                    echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
                    exit();
                }
           ?>
           </select>
           </label>
         </p>
         <p>Добавлять в секретный раздел?<br>
           <label><strong>Да</strong>
            <input type="radio" name="secret" id="secret" value="1">
           </label>&nbsp;&nbsp;
           <label><strong>Нет</strong>
            <input type="radio" checked name="secret" id="secret" value="0">
           </label>
           &nbsp;&nbsp;Если да, то этой записи будет присвоено 500 просмотров
         </p>
         <p>
           <label>Введите цену <? echo $name_dt ?><br>
             <input type="text" name="price" id="price" size="<? echo $SizeOfinput ?>">
             </label>
         </p>
         <input name="mode" type="hidden" value="add">
         <input name="sec" type="hidden" value="<? echo $sec ?>">
         <p>
           <label>
           <input type="submit" name="submit" id="submit" value="Занесение  <? echo $name_dt ?> в базу">
           </label>
         </p>
         </form>
        <?
        }elseif ($_GET['mode'] == 'edit') {// редактирование значения
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "Страница редактирования ".$name_dt; include("header.html");
            if (!isset($_GET['cat'])AND(!isset($_GET['id']))) {
                echo "<h3 align='center'>Выберите категорию для ".$name_dt."</h3>";
                $result = mysql_query("SELECT title,id FROM categories  WHERE (sec='".$sec."' OR sec IN (SELECT id FROM sections WHERE id_lang='".$sec."')) ORDER BY id_lang,id",$db);
                $myrow = mysql_fetch_array($result);
                do
                {
                printf ("<p><a href='%s?sec=".$sec."&cat=%s&mode=edit'>%s</a></p>",$action,$myrow["id"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
            
            }elseif (isset($_GET['cat'])AND(!isset($_GET['id']))) {
                echo "<h3 align='center'>Редактирование ".$name_dt."</h3>";
                if (isset($_GET['cat'])) {
                    $cat = $_GET['cat'];
                }else{
                    exit("Не могу найти категорию ".$name_dt." для редактирования!");
                }
                $result = mysql_query("SELECT title,id,cat,lang,text FROM ".$tbl_dt." WHERE cat=$cat ORDER BY `date` DESC",$db);
                $myrow = mysql_fetch_array($result);
                do
                {
//                    if ((substr_count($myrow["text"], '<!--more-->') > 1)OR($myrow["faq"] == '1')) 
//                    {continue;}          
                    printf ("<p><a href='%s?sec=".$sec."&cat=%s&id=%s&lang=%s&mode=edit'>%s</a></p>",$action,$myrow["cat"],$myrow["id"],$myrow["lang"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
            }else{
                echo "<h3 align='center'>Редактирование ".$name_dt."</h3>";
                $result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE id=$id");
                $myrow = mysql_fetch_array($result);

                $result2 = mysql_query("SELECT id,title FROM categories  WHERE (sec='".$sec."' OR sec IN (SELECT id FROM sections WHERE id_lang='".$sec."')) ORDER BY id_lang,id");
                $myrow2 = mysql_fetch_array($result2);

                $count = mysql_num_rows($result2);
                
//                echo create_thumb($myrow);
                
                echo "<form name='form1' method='post' action='$action'>";
                do
                {
                    if ($myrow['cat'] == $myrow2['id'])
                    {
                        $cat_list = $cat_list."<option value='".$myrow2["id"]."' selected>".$myrow2["title"]."</option>";
                    }else{
                        $cat_list = $cat_list."<option value='".$myrow2["id"]."'>".$myrow2["title"]."</option>";
                    }
                }
                while ($myrow2 = mysql_fetch_array($result2));
                echo "<p>Выберите категорию для ".$name_dt."<br><select name='cat' size='$count'>";
                echo $cat_list;
                echo "</select></p>";            
                ?>
                <p>
                   <label>Выберите язык <? echo $name_dt ?><br>
                   <select name="lang">
                   <?
                        foreach ($langs as $val) {
                            if ($myrow["lang"] == $val)
                            {
                                printf ("<option value='%s' selected>%s</option>",$myrow["lang"],$myrow["lang"]);
                            }else{
                                printf ("<option value='%s'>%s</option>",$val,$val);
                            }
                        }
                   ?>
                   </select>
                   </label>
                 </p>
                 <p>
                   <label>Выберите название <? echo $name_dt ?> на русском языке<br>
                   <select name="id_lang">
                  <? 
                       if (isset($_GET['lang'])){$lang = strtoupper($_GET['lang']);}else{$lang = "RU";}
                       if ($lang == "RU"){printf ("<option value='%s' selected>%s</option>",0,"<<Название ".$name_dt." не выбрано>>");
                       } else {printf ("<option value='%s'>%s</option>",0,"<<Название ".$name_dt." не выбрано>>");}
                        $resultru = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE lang='RU'",$db);
                        $was = 0;
                        if (!$resultru)
                        {
                            echo "<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>";
                            exit(mysql_error());
                        }
                        if (mysql_num_rows($resultru) > 0)
                        {
                            $myrowru = mysql_fetch_array($resultru);
                            do
                            {
                                if ($myrowru["id"] == $myrow['id_lang'])
                                {
                                    printf ("<option value='%s' selected>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                                    $was = 1;
                                }else{
                                    printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                                }
                            }
                            while ($myrowru = mysql_fetch_array($resultru));
                            if ($was == 0 AND $lang != "RU"){printf ("<option value='%s' selected>%s</option>",0,"<<Название ".$name_dt." не выбрано>>");}
                        } else {
                            echo "<p>Информация по запросу не может быть извлечена в таблице нет записей.</p>";
                            exit();
                        }
                   ?>
                   </select>
                   </label>
                 </p>
                <?
                echo "<p>Добавлять в секретный раздел?<br>
                <label><strong>Да</strong><input type='radio'";
                if ($myrow['secret'] == 1) { echo " checked ";}
                echo "name='secret' id='secret' value='1'></label>&nbsp;&nbsp;<label><strong>Нет</strong>
                <input type='radio'"; if ($myrow['secret'] == 0) { echo " checked ";} 
                echo "name='secret' id='secret' value='0'></label>&nbsp;&nbsp;Если да, то этой записи будет присвоено 500 просмотров</p> ";
                
                If ($myrow[phpcolor] == '1'){
                    $phpcolor = "checked";
                }else{$phpcolor = "";}
                
                If ($myrow[faq] == '1'){
                    $faq = "checked";
                }else{$faq = "";}
                
                If ($myrow[notprohibit] == '1'){
                    $notprohibit = "checked";
                }else{$notprohibit = "";}

//                print <<<HERE
                ?>
                 <p>
                   <label>Введите название <? echo $name_dt ?> (лучше не более 70 символов)<br>
                     <input value="<? echo $myrow[title] ?>" type="text" name="title" id="title" size="<? echo $SizeOfinput ?>">
                     </label>
                 </p>
                 <p>
                   <label>Введите транслитерацию названия <? echo $name_dt ?><br>
                     <input value="<? echo $myrow[name] ?>" type="text" name="name" id="name" size="<? echo $SizeOfinput ?>">
                     </label>
                 </p>
                 <p>
                     <input name="button" type="button" value="Транслитерировать" size="<? echo $SizeOfinput ?>" onclick="SetTranslitRuToLat()">
                 </p>
                 <p>
                   <label>Введите краткое описание <? echo $name_dt ?> (лучше не более 160 символов)<br>
                   <input value="<? echo $myrow[meta_d] ?>" type="text" name="meta_d" id="meta_d" size="<? echo $SizeOfinput ?>">
                   </label>
                 </p>
                 <p>
                   <label>Введите ключевые слова для <? echo $name_dt ?><br>
                   <input value="<? echo $myrow[meta_k] ?>" type="text" name="meta_k" id="meta_k" size="<? echo $SizeOfinput ?>">
                   </label>
                 </p>
                 <p>
                   <label>Введите дату добавления <? echo $name_dt ?><br>
                   <input value="<? echo $myrow[date] ?>" name="date" type="text" id="date">
                   </label>
                 </p>
                 <p>
                   <label>Ведите краткое описание <? echo $name_dt ?> с тэгами абзацев
                   <textarea name="description" id="description" cols="<? echo $ColsOfarea ?>" rows="5"><? echo $myrow[description] ?></textarea>
                   </label>
                 </p>
                 <p> 
                   <label>Введите полный текст <? echo $name_dt ?> с тэгами абзацев
                   <textarea name="text" id="text" cols="<? echo $ColsOfarea ?>" rows="20"><?
                   if ($rus !== false){
                       echo $myrow[text];
                   }else{echo stripslashes(htmlspecialchars($myrow[text]));}
                    
                   ?></textarea>
                   </label>
                 </p>
                 <p>
                   <label>Введите автора <? echo $name_dt ?><br>
                   <input value="<? echo $myrow[author] ?>" type="text" name="author" id="author" size="<? echo $SizeOfinput ?>">
                   </label>
                 </p>
                 <p>
                   <label>Введите где лежит миниатюра<br>
                   <input value="<? echo $myrow[mini_img] ?>" type="text" name="img" id="img" size="<? echo $SizeOfinput ?>">
                   </label>
                 </p>
                 <p>
                   <label>Введите где лежит файл <? echo $name_dt ?><br>
                   <input value="<? echo $myrow[file] ?>" type="text" name="file" id="file" size="<? echo $SizeOfinput ?>">
                   </label>
                 </p>
                 <?php
                 if ($sec == '2') {
                 ?>
                    
                 <?php
                }  else {
                ?>
                    <p>
                       <label>
                       <input type="checkbox" name="faq" id="faq" <? echo $faq ?>>
                       Заметка будет включать ответы на часто задаваемые вопросы (FAQ)
                       </label>
                    </p>
                <?php
                }
                ?>
                 <p>
                    <label>
                    <input type="checkbox" name="phpcolor" id="phpcolor" <? echo $phpcolor ?>>
                    У <? echo $name_dt ?> будем окрашивать PHP код?
                    </label>
                 </p>
                 <p>
                    <label>
                    <input type="checkbox" name="ChangeBlogger" id="ChangeBlogger" >
                    Изменять <? echo $name_dt ?> в Blogger?
                    </label>
                 </p>
                 <p>
                    <label>
                    <input type="checkbox" name="notprohibit" id="notprohibit" <? echo $notprohibit ?>>
                     Не запрещать скрытие <? echo $name_dt ?> для посетителей
                    </label>
                 </p>
                 <p>
                   <label>Введите цену <? echo $name_dt ?><br>
                     <input value="<? echo $myrow[price] ?>"type="text" name="price" id="price" size="<? echo $SizeOfinput ?>">
                     </label>
                 </p>
                <input name="id" type="hidden" value="<? echo $myrow[id] ?>">
                <input name="mode" type="hidden" value="update">
                <input name="sec" type="hidden" value="<? echo $sec ?>">
                <input name="blog_id" type="hidden" value="<? echo $myrow[blog_id] ?>">
                 <p>
                   <label>
                   <input type="submit" name="submit" id="submit" value="Сохранить изменения">
                   </label>
                 </p>
               </form>
               <?
//               $url = "http://www.softmaker.kz/";
               $cat_name = get_fld_by_id($myrow["cat"], 'categories', 'name');
               $url = get_other_lang_link($lang);
               $link = $url.$filename."/".$cat_name."/".$myrow["name"].".html";
               echo "<h2 align='center'>Добавление анонса ".$name_dt." в Rambler каталог</h2>";

               include_once("counters/rambler_anons.php");

            }
        }elseif ($_GET['mode'] == 'del') {// удаление значения
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "Страница удаления ".$name_dt; include("header.html");
            if (!isset($_GET['cat'])AND(!isset($_GET['id']))) {
                echo "<h3 align='center'>Выберите категорию для ".$name_dt."</h3>";
                $result = mysql_query("SELECT title,id FROM categories  WHERE (sec='".$sec."' OR sec IN (SELECT id FROM sections WHERE id_lang='".$sec."')) ORDER BY id_lang,id",$db);
                $myrow = mysql_fetch_array($result);
                do
                {
                    printf ("<p><a href='%s?sec=".$sec."&cat=%s&mode=del'>%s</a></p>",$action,$myrow["id"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
            
            }elseif (isset($_GET['cat'])AND(!isset($_GET['id']))) {
                echo "<h3 align='center'>Страница удаления ".$name_dt."</h3>";
                if (isset($_GET['cat'])) {$cat = $_GET['cat'];}else{exit("Не могу найти категорию ".$name_dt." для редактирования!");}
                ?>
                <p><strong>Выберите элемент для удаления <? echo $name_dt;?></strong></p>
                <form action="<? echo $action;?>" method="post">
                <?
                    $result = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE cat=$cat ORDER BY `date` DESC",$db);
                    $myrow = mysql_fetch_array($result);
                    do
                    {
                        printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>",$myrow["id"],$myrow["title"]);
                    }
                    while ($myrow = mysql_fetch_array($result));
                    ?>
                    <input name="cat" type="hidden" value="<? echo $cat;?>">
                    <input name="mode" type="hidden" value="drop">
                    <input name="sec" type="hidden" value="<? echo $sec ?>">
                    <p> <input name="submit" type="submit" value="Удаление <? echo $name_dt;?>"></p>
                </form>
                <?
           }
      }//if ($_GET['mode'] == 'del')
 }//if (isset($_GET['mode']))
 
 if (isset($_POST['mode'])){// 2-я часть 3 метода POST
    if ($_POST['mode'] == 'add') {// добавление нового значения
        if (isset($_POST['sec'])) {$sec = $_POST['sec'];}
        $title_here = "Страница добавления ".$name_dt; include("header.html");
        if (isset($_POST['name']))        {$name = $_POST['name'];if ($name == ''){unset($name);}}
        if (isset($_POST['title']))       {$title = $_POST['title'];if ($title == ''){unset($title);}}
        if (isset($_POST['meta_d']))      {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
        if (isset($_POST['meta_k']))      {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
        if (isset($_POST['date']))        {$date = $_POST['date']; if ($date == '') {unset($date);}}
        if (isset($_POST['description'])) {$description = $_POST['description']; if ($description == '') {unset($description);}}
        if (isset($_POST['text']))        {$text = $_POST['text']; if ($text == '') {unset($text);}}
        if (isset($_POST['author']))      {$author = $_POST['author']; if ($author == '') {unset($author);}}
        if (isset($_POST['img']))         {$img = $_POST['img']; if ($img == '') {unset($img);}}
        if (isset($_POST['cat']))         {$cat = $_POST['cat']; if ($cat == '') {unset($cat);}}
        if (isset($_POST['lang']))        {$lang = $_POST['lang']; if ($lang == '') {unset($lang);}}
        if (isset($_POST['id_lang']))     {$id_lang = $_POST['id_lang']; if ($id_lang == '') {unset($id_lang);}}
        if (isset($_POST['secret']))      {$secret = $_POST['secret']; 
        if (isset($_POST['price']))      {$price = $_POST['price'];} 
            if ($secret == '1'){$view = 500;}else{$view = 0;} 
            if ($secret == '') {unset($secret);}
        }
        if (isset($_POST['file']))    {$file = $_POST['file']; if ($file == '') {unset($file);}}
        if ($sec == '2') {
            $faq = '';$faq_fld = '';
        } else {
//            $file = "post";
            if (isset($_POST['faq'])){$faq = "'1',";}else{$faq = "'0',";}
            $faq_fld = 'faq,'; 
        }
        
        if (isset($_POST['phpcolor']))    {$phpcolor = '1';}else{$phpcolor = '0';}
        
        if (isset($_POST['notprohibit']))    {$notprohibit = '1';}else{$notprohibit = '0';}
        
        if (isset($_POST['ChangeBlogger']))    {$ChangeBlogger = '1';}else{$ChangeBlogger = '0';}

        if (isset($name) && isset($title) && isset($meta_d) && isset($meta_k) && isset($date) && isset($description) && isset($text) && isset($author) && isset($img) && isset($cat) && isset($secret))
        {
            $blog_id = ''; $message = TRUE;
            if (!$rus AND $faq != "'1'," AND $ChangeBlogger == '1')
            {
                try { 
                    $message = CreateSmartMessage($_POST);
                    $newBlog = new Blog();
                    $newBlog->promptForBlogID();
                    $blog_id = $newBlog->createPost(strip_tags($meta_d), $message[body], $date, $message[label]);
                } catch (Exception $e) {
                    echo '<p align=center>ERROR:' . $e->getMessage() . '</p>';  
                }
            }
            if ($message != FALSE) { // если добавление в blogger прошло успешно
                $text = addslashes($text);
                /* Здесь пишем что можно заносить информацию в базу */
                $id = get_id($tbl_dt);
                if ($view == 500){$view_1 = ',view'; $view_2 = ",'$view'";}else{$view_1 = ""; $view_2 = "";}
                $query_txt = "INSERT INTO ".$tbl_dt." (id,name,title,meta_d,meta_k,date,description,text$view_1,author,lang,id_lang,mini_img,cat,secret,file,$faq_fld phpcolor,notprohibit,blog_id,price) VALUES ($id,'$name','$title', '$meta_d','$meta_k','$date','$description','$text'$view_2,'$author','$lang','$id_lang','$img','$cat','$secret','$file',$faq'$phpcolor','$notprohibit','$blog_id','$price')";
//                if ($sec == '1') {
//                    $current = array(",'post'", ",file");
//                    $change   = array("", "");
//                    $query_txt = str_replace($current, $change, $query_txt);
//                }    
                $result = mysql_query ($query_txt);
                if ($result == 'true') 
                {
//                    CreateSitemap();
                    echo "<p align='center'>Добавление ".$name_dt." успешно завершено!</p>";
                }
                else {echo "<p align='center'>Добавление ".$name_dt." не прошло!</p>";}
            }
        } else {
            echo "<p align='center'>Вы ввели не всю информацию, поэтому добавление ".$name_dt." невозможно.</p>";
        }
     }elseif ($_POST['mode'] == 'update') {// редактирование значения
        if (isset($_POST['sec'])) {$sec = $_POST['sec'];}
        $title_here = "Страница редактирования ".$name_dt; include("header.html");
        if (isset($_POST['name']))        {$name = $_POST['name'];if ($name == ''){unset($name);}}
        if (isset($_POST['title']))       {$title = $_POST['title'];if ($title == ''){unset($title);}}
        if (isset($_POST['meta_d']))      {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
        if (isset($_POST['meta_k']))      {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
        if (isset($_POST['date']))        {$date = $_POST['date']; if ($date == '') {unset($date);}}
        if (isset($_POST['description'])) {$description = $_POST['description']; if ($description == '') {unset($description);}}
        if (isset($_POST['text']))        {$text = $_POST['text']; if ($text == '') {unset($text);}}
        if (isset($_POST['author']))      {$author = $_POST['author']; if ($author == '') {unset($author);}}
        if (isset($_POST['id']))          {$id = $_POST['id'];}
        if (isset($_POST['cat']))         {$cat = $_POST['cat']; if ($cat == '') {unset($cat);}}
        if (isset($_POST['lang']))        {$lang = $_POST['lang']; if ($lang == '') {unset($lang);}}
        if (isset($_POST['id_lang']))     {$id_lang = $_POST['id_lang']; if ($id_lang == '') {unset($id_lang);}}
        if (isset($_POST['img']))         {$img = $_POST['img']; if ($img == '') {unset($img);}}
        if (isset($_POST['price']))      {$price = $_POST['price'];}
        if (isset($_POST['secret']))      {$secret = $_POST['secret'];
            if ($secret == '1'){$view = 500;}else{$view = 0;} 
            if ($secret == '') {unset($secret);}
        }
        if (isset($_POST['file']))    {$file = $_POST['file']; if ($file == '') {unset($file);}}
        if ($sec == '2') {     
            $faq = "";
        } else {
//            $file = "post";
            if (isset($_POST['faq'])){$faq = "faq='1',";}else{$faq = "faq='0',";}
        }
        if (isset($_POST['phpcolor']))    {$phpcolor = '1';}else{$phpcolor = '0';}
        if (isset($_POST['notprohibit'])) {$notprohibit = '1';}else{$notprohibit = '0';}
        
        $blog_id = '';
        if (isset($_POST['blog_id'])) {
            $blog_id = $_POST['blog_id']; 
        }
        
        if (isset($_POST['ChangeBlogger'])) {
            $ChangeBlogger = '1';
        }else{$ChangeBlogger = '0';}
        
        if (isset($name) && isset($title) && isset($meta_d) && isset($meta_k) 
                && isset($date) && isset($description) && isset($text) 
                && isset($author) && isset($cat) 
                && isset($img) && isset($blog_id)) {
            if (!$rus AND $ChangeBlogger == '1') {
                try {
                    $message = CreateSmartMessage($_POST);
                    $newBlog = new Blog();
                    $newBlog->promptForBlogID();
                    if (!empty($blog_id)){
                        $newBlog->updatePost($blog_id, strip_tags($meta_d), $message[body], False);
                    } else {
                        $blog_id = $newBlog->createPost(strip_tags($meta_d), $message[body], $date, $message[label]);
                    }
                } catch (Exception $e) {
                    echo '<p align=center>ERROR:' . $e->getMessage() . '</p>';  
                }
            }

            $text = addslashes($text);
            if ($view == 500){$view_1 = ", view='$view'";}else{$view_1 = "";}
            /* Здесь пишем что можно заносить информацию в базу */
            $query_txt = "UPDATE ".$tbl_dt." SET name='$name', title='$title', meta_d='$meta_d', meta_k='$meta_k', date='$date', description='$description', text='$text'$view_1, author='$author', lang='$lang', id_lang='$id_lang', cat='$cat', mini_img='$img', secret='$secret', file='$file', $faq phpcolor='$phpcolor', notprohibit='$notprohibit', blog_id='$blog_id', price='$price' WHERE id='$id'";
//            if ($sec == '1') {
//                $current = array(", file='post'");
//                $change   = array("");
//                $query_txt = str_replace($current, $change, $query_txt);
//            }    
            $result = mysql_query ($query_txt);
            if ($result == 'true') {
//                CreateSitemap();
                echo "<p align='center'>Обновление ".$name_dt." успешно завершено!</p>";
//                del_cache($id,$filename);
            }else{
                echo "<p align='center'>Обновление ".$name_dt." не прошло!</p>";
                echo $query_txt;
            }
        } else {
            if (!isset($blog_id)){
                echo "<p align='center'>Вы не ввели blog_id, поэтому обновление ".$name_dt." невозможно.</p>";
            }  else {
                echo "<p align='center'>Вы ввели не всю информацию, поэтому обновление ".$name_dt." невозможно.</p>";
            }
        }
    }elseif ($_POST['mode'] == 'drop') {// удаление значения
        if (isset($_POST['id'])) {$id = $_POST['id'];}
        if (isset($_POST['cat'])) {$cat = $_POST['cat'];}
        $title_here = "Страница удаления ".$name_dt; include("header.html");
        if (isset($id)){
            $rec_exists = FALSE;
            if ($sec == '1') {
                $result_post = mysql_query("SELECT * FROM faq WHERE (post=$id)", $db);
                $num_rows = mysql_num_rows($result_post);
                // если количество записей не равно нулю
                if ($num_rows!=0)
                {
                    $rec_exists = TRUE;
                }
            }
            if (!$rec_exists) {
                $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
                if ($result == 'true') {
//                    CreateSitemap();
                    echo "<p align='center'>Удаление ".$name_dt." успешно завершено!</p>";
//                    del_cache($id,'files');
                }else{echo "<p align='center'>Удаление ".$name_dt." не прошло!</p>";}
            }else{
                echo "<p align='center'>Удаление ".$name_dt." не прошло! Существуют записи в подчинённой таблице! Сначала удалите их!</p>";
            }
        }else{
            echo "<p>Вы запустили данный файл без параметра id и поэтому, удаление ".$name_dt." невозможно (скорее всего Вы не выбрали радиокнопку на предыдущем шаге).</p>";
        }
    }
    $distination = $DIR."tmp/$name.pdf";
    if (file_exists($distination)){
        unlink ($distination);
    }
}//if (isset($_POST['mode']))
include_once ("footer.html");?>
