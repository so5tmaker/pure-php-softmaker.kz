<?php
include ("lock.php");
// использовалась для создания статей и закачек в Blogger
// Запись была в righttd.php:
//<p align="center" class="title">Посты в блог</p>
//<div id="coolmenu">
//<a href="CreatePost.php?sec=1">Добавить статью</a>
//<a href="CreatePost.php?sec=2">Добавить закачку</a>
//</div>	
if (isset($_GET['sec'])) {
    $sec = $_GET['sec'];
}  else {
    if (isset($_POST['sec'])) {$sec = $_POST['sec'];}
}
if ($sec == '1') {
    $name_dt = "статьи"; $filename = 'articles'; $tbl_dt = "data"; $action = "data.php";
    $faq_qry = "AND faq=0";
} elseif ($sec == '2') {
    $name_dt = "закачки"; $filename = 'files'; $tbl_dt = "files"; $action = "data.php";
    $faq_qry = "";
} else {
    $name_dt = "статьи"; $filename = 'articles'; $tbl_dt = "data"; $action = "data.php";
    $faq_qry = "AND faq=0";
}
$faq_qry = "";
$title_here = "Страница добавления ".$name_dt;
include("header.html");

function CreatePost($myrow) {
  
    global $filename, $sec, $tbl_dt, $db;

    // Формирование сообщения из базы
    $cat = $myrow[cat];
    $result_cat = mysql_query("SELECT title, name FROM categories WHERE (id=$cat)", $db);
    $myrow_cat = mysql_fetch_array($result_cat);
    $num_rows = mysql_num_rows($result_cat);
    // если количество записей не равно нулю
    if ($num_rows!=0)
    {
        $cat_name = $myrow_cat[name];
        $label = $myrow_cat[title];
    } else { 
        return FALSE;
    }
    if ($cat_name == '0')
    {
        return FALSE;
    }
    $was = FALSE;
    if ($sec !== '2') {
        if ($myrow[faq] == '1') {
            $was = TRUE;
            $post = $myrow[id];
            $result_faq = mysql_query("SELECT answer, question FROM faq WHERE (post=$post)", $db);
            $myrow_faq = mysql_fetch_array($result_faq);
            $num_rows = mysql_num_rows($result_faq);
            // если количество записей не равно нулю
            if ($num_rows!=0)
            {
                if ($myrow["phpcolor"] == '1') {
                    $text = get_highlight_code($myrow_faq[answer]);
                    $text = str_replace("pre>", "p>", $text);
                } else {
                    $text = get_highlight_code($myrow_faq[answer]);    
                }
                $text = "<h4 align=center>".$myrow_faq[question]."</h4>".$text;
                $image = "";
            } else { 
                echo "<p align=center>Сообщение не создано! Наверное нет вопросов для заметки - " . $myrow[title] . '</p>';
                return FALSE;
            }
        }
    } 
    $link = get_other_lang_link($myrow[lang]);
    if (!$was) {
        $matches = null;
        // Вырезает из текста все теги img вместе с атрибутами $myrow[text]
        preg_match_all('/<img[^>]+>/i', stripslashes($myrow[text]), $matches);
        $bodytag = $matches[0];
        $bodytag = str_replace("../../", $link, $bodytag);
        $bodytag = str_replace("src", " border='1' src", $bodytag);

        if ($bodytag[0] != null){
            $image = "<p align=center>"
            ."<a href='$link$filename/$cat_name/".$myrow[name].".html'". 
            $open.$bodytag[0]."</a>"
            ."</p>";
        }
        // Убираю лишние переводы строк из текста
        $image = str_replace("\r\n",'',$image);
        $image = str_replace("\n",'',$image);
        $text = $myrow[description];
    }
       
    $footer = "<p align=center>"
    .get_foreign_equivalent("Читать далее")." <a href='$link$filename/$cat_name/".$myrow[name].".html'" 
    .$open
    ."«$myrow[title]»</a>"
    ."</p>";
    $title = "<H1 align=center>".$myrow[meta_d]."</H1>";
    $body1 = $title.$image.$text.$footer;
    echo $body1;
    $body = $image.$text.$footer;
    
    
//    return TRUE;
    // загрузка библиотек Zend Gdata 
    require_once 'Zend/Loader.php';
    Zend_Loader::loadClass('Zend_Gdata');
    Zend_Loader::loadClass('Zend_Gdata_Query');
    Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    Zend_Loader::loadClass('Zend_Gdata_Feed');

    // задание учетных данных для аутентификации ClientLogin
    $user = "softmaker.kz@gmail.com";
    $pass = "]budetgoo";

    try {
    // регистрация 
    // инициализация объекта службы
    $client = Zend_Gdata_ClientLogin::getHttpClient(
      $user, $pass, 'blogger');
    $service = new Zend_Gdata($client);

    // Получаем id блога
    $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/default/blogs');
    $feed = $service->getFeed($query);

    //текст id имеет форму: tag:blogger.com,1999:user-blogID.blogs
    // У нас он самый первый в массиве entries поэтому его индекс ноль
    $idText = explode('-', $feed->entries[0]->id->text);
    //    echo $idText[2];
    // задание идентификатора блога
    $id = $idText[2];

    // создание нового объекта записи
    // заполнение его данными    
    $uri = 'http://www.blogger.com/feeds/' . $id . '/posts/default';
    $entry = $service->newEntry();
    $entry->title = $service->newTitle(iconv('windows-1251', 'UTF-8',$myrow[meta_d]));
    $entry->content = $service->newContent(iconv('windows-1251', 'UTF-8', $body));
    $entry->content->setType('text');

    //zend gdata blogger ярлыки
    $labels = $entry->getCategory();
    $newLabel = $service->newCategory(iconv('windows-1251', 'UTF-8', $label), 'http://www.blogger.com/atom/ns#');
    $labels[] = $newLabel; // Append the new label to the list of labels. 
    $entry->setCategory($labels);
    
    // Установим дату
    $entry->published = $service->newPublished($myrow[date].'T00:00:00.000-00:00');

    // сохранение записи на сервере
    // получение уникального идентификатора новой заметки
    $response = $service->insertEntry($entry, $uri);
    $arr = explode('-', $response->getId());
    $id = $arr[2];
    echo '<p align=center>Сообщение успешно добавлено с ID: ' . $id . '</p>';
    
    $query_txt = "UPDATE ".$tbl_dt." SET blog_id='$id' WHERE id='$myrow[id]'";
    $result = mysql_query ($query_txt);
    if ($result == 'true') {
        echo "<p align='center'>Обновление ".$name_dt." успешно завершено!</p>";
    }else{
        echo "<p align='center'>Обновление ".$name_dt." не прошло!</p>";
    }

    } catch (Exception $e) {
    die('<p align=center>ERROR:' . $e->getMessage() . '</p>');  
    }
}

function PrintList($list)
  {
    echo "<ul>\n";
  
    for($a=0;$a<count($list);$a++)
    {
      echo "  <li>".$list[$a][2]."</li>\n";    
    }
  
    echo "</ul>\n";
  } 

$result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE blog_id='' AND lang='RU' $faq_qry ORDER by date LIMIT 0 , 50"); // id=8 AND 
$myrow = mysql_fetch_array($result);
do
{
    if (!CreatePost($myrow))
    {
        continue;
    }
}
while ($myrow = mysql_fetch_array($result));

include_once ("footer.html");
?>