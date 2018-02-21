<?php
include ("bd.php");
$fromglobal = 1;
$max_mails = 140;
$email_quant = 5;

//global.inc.php необходим для каждой страницы сайта.
//Почему? Таким образом мы разместим все обычные операции,
//которые нам понадобятся на странице. К примеру,
//мы начнем session_start(). Соединение с БД также откроется.

$session = filter_input(INPUT_COOKIE, 'session'); //$_COOKIE['session'];
if(!$session) //make sure this hasn't already been established
{
   $session = md5(uniqid(rand()));  //creates a random session value

// sets a cookie with the value of session.  On my pages, I do a simple test to see if
// the cookie exists on the user's machine ( if($ShoppingCart) ) if it does exist,
// I don't send the session ID's around with every page they visit, I just use the values
// in the cookie.

//set the cookie to remain for 2 hours
   SetCookie("session","$session",time()+7200);
}

require_once $DIR.'classes/DB.class.php';
require_once $DIR.'classes/User.class.php';
require_once $DIR.'classes/UserTools.class.php';
require_once $DIR.'classes/Link.class.php';
require_once $DIR.'classes/Advs.class.php';

//Тут происходит несколько вещей. Прежде всего, мы открываем соединение с базой.
//connect to the database
$db1 = new DB();
$db1->connect();

// Инициализирую объект для вывода рекламы
$advs = new Advs();

//initialize UserTools object
$userTools = new UserTools();

//После соединения, мы начинаем функцию session_start().
//Функция создает сессию или продолжает текущую,
//если пользователь уже залогинен. Поскольку наше
//приложение рассчитано на то, чтобы пользователи
//входили\выходили, эта функция обязательна на каждой странице.
//start the session
session_start();

//Далее мы проверяем залогинен ли юзер.
//Если да - мы обновим $_SESSION['user'],
//чтобы отображать самую последнюю информацию о юзере.
//К примеру, если пользователь меняет свой емейл,
//в сессии будет храниться еще старый.
//Но с помощью авто обновления такого не случится.
//refresh session variables if logged in
if(isset($_SESSION['logged_in'])) {
    $user = unserialize($_SESSION['user']);
    $_SESSION['user'] = serialize($userTools->get($user->id));
    $logon = 1;
}else {
    $logon = 0;
}

$softmaker_link = "<a title='SoftMaker.kz - все для начинающих программистов | 
Нюансы программирования, разработки в системе 1С, PHP, Delphi, работа с MySQL, HTML, CSS' 
href='http://www.softmaker.kz'>SoftMaker.kz</a>";

$go_to_home = "Перейти на главную страницу блога SoftMaker.kz - советы по программированию и разработке в системе 1С, PHP, Delphi, работа с MySQL, HTML, CSS";

// Проверка языка сайта
$langs[0] = "RU";
$langs[1] = "EN";

if (strstr($SERVER, 'en.')) {
    $lang = "EN";
} else {
    $lang = "RU";
}

$coding = "windows-1251";
$opp_lang = ($lang == "RU") ?  "EN" : "RU";

$open = 'target=_blank title="'.get_foreign_equivalent("Откроется в новом окне").'"';


$notices = 5; // переменная для указания кол-ва описаний статей на странице
$gap = 2; // количество описаний статей после, которых выводить рекламу
$home = basename($SERVER, ".php");
$filename = str_replace($rest_.'/', "", str_replace(".php", "", $PHP_SELF));
if (strstr($SERVER, 'articles.php') OR strstr($SERVER, 'view_post.php')) {
    $razdel = get_foreign_equivalent("Статьи");
    $tbl = 'data';
}elseif (strstr($SERVER, 'about.php')) {
    $razdel = get_foreign_equivalent("Обратная связь");
}elseif (strstr($SERVER, 'sitemap.php')) {
    $razdel = get_foreign_equivalent("Карта сайта");
}elseif (strstr($SERVER, 'feedback.php')) {
    $razdel = get_foreign_equivalent("Обратная связь");
}

$root = root();
function root() {
    if(isset($_SESSION['logged_in'])) 
    { 
        $user = unserialize($_SESSION['user']); 
        $username = $user->username;
        if (strtolower(trim($username)) == "root" or strtolower(trim($username)) == "softmaker")
        {
            return true;
        }    
    }
    return false;
}

function change_alphabet($str) { 
    // ищу совпадения в строке $str из массива $kz_alphabet_wrong и
    //  заменяю найденные символы символами из массива $kz_alphabet 
    
    // массив алфавита синхронизации с казахскими символами
    $kz_alphabet =  array(
        0 => '&#1240;',
        1 => '&#1170;',
        2 => '&#1178;',
        3 => '&#1186;',
        4 => '&#1256;',
        5 => '&#1200;',
        6 => '&#1198;',
        7 => '&#1241;',
        8 => '&#1171;',
        9 => '&#1179;',
        10 => '&#1187;',
        11 => '&#1257;',
        12 => '&#1201;',
        13 => '&#1199;'
        );
    $kz_alphabet_wrong =  array(
        0 => '&amp;#1240;',
        1 => '&amp;#1170;',
        2 => '&amp;#1178;',
        3 => '&amp;#1186;',
        4 => '&amp;#1256;',
        5 => '&amp;#1200;',
        6 => '&amp;#1198;',
        7 => '&amp;#1241;',
        8 => '&amp;#1171;',
        9 => '&amp;#1179;',
        10 => '&amp;#1187;',
        11 => '&amp;#1257;',
        12 => '&amp;#1201;',
        13 => '&amp;#1199;'
        );
    
    return str_replace($kz_alphabet_wrong, $kz_alphabet, $str);

}

//Функция выводит список случайных статей текущей категории
function show_random_list() {
    global $db, $id_score, $cat, $lang, $filename, $tbl; 
    $empty = 0;
    // Выберем статьи из текущей категории
    $result = mysql_query("SELECT title,id FROM ".$tbl." WHERE (cat=$cat)AND(lang='".$lang."')",$db);
    $myrow = mysql_fetch_array($result);
    do 
    {
            if ($id_score <> $myrow["id"])
            {
                    $subs[$myrow["id"]] = $myrow["title"];
                    $empty += 1;
                    $firstid = $myrow["id"];
            }
    }
    while ($myrow = mysql_fetch_array($result));
    if ($empty < 5)
            {
                    $rand = $empty;
            }
            else
            {
                    $rand = 5;	
            }
    if ($empty !== 0)
    { ?>
          <p><?php echo get_foreign_equivalent("Вы также можете прочитать похожие статьи из текущей категории");?>:</p>
      <?
            echo "<div style='margin-left: 10px'>";
            // А теперь выбираем из полученного массива случайные значения
            // и выводим ссылки на наши статьи
            if ($empty !== 1)
            {
                    $shfl = array_rand($subs, $rand);
                    for ($x=0; $x<count($shfl); $x++) 
                    {
                            $shsubs[] = $subs[$shfl[$x]];
                            printf ("<p><a href='".$filename.".php?cat=%s&id=%s&lang=%s'>%s</a></p>",$cat,$shfl[$x],$lang,$shsubs[$x]);
                    }
            }elseif ($empty == 1)
            {printf ("<p><a href='".$filename.".php?cat=%s&id=%s&lang=%s'>%s</a></p>",$cat,$firstid,$lang,$subs[$firstid]);}
            echo "</div >";
    ?>
    <p>&nbsp;</p>
    <?
    }
}   // show_random_list()

//Функция выводит Заголовок
function show_title($cat_name, $mode = 1, $fname = '') {
    global $filename, $rest_, $cat, $open;

    $fnvalue = ($fname == '') ? $filename : $fname;
    
    if ($mode == 0) {
        return "<p><strong>".get_foreign_equivalent("Вы можете прочесть эту же статью на другом языке").":</strong></p>";
    } elseif ($mode == 1) {
        $cat_nm = get_fld_by_id($cat, 'categories', 'name');
        $path = "$rest_/$fnvalue/$cat_nm/";
        $cat_name = "<a $open href='$path'>$cat_name</a>";
        return "<p><strong>".get_foreign_equivalent("Вы также можете прочитать похожие статьи из текущей категории")." ".$cat_name.":</strong></p>";
    } elseif ($mode == 2) {
        $cat_nm = $cat_name[0];
        $cat_name = $cat_name[1];
        $path = "$rest_/$fnvalue/$cat_nm/";
        $cat_name = "<a $open href='$path'>$cat_name</a>";
        return "<p><strong>".get_foreign_equivalent("Вы также можете прочитать статьи из соседней категории")." ".$cat_name.":</strong></p>";
    }elseif ($mode == 3) {
        $path = "$rest_/$fnvalue/";
        $cat_name = "<a $open href='$path'>$cat_name</a>";
        return "<p><strong>".get_foreign_equivalent("Вы также можете прочитать статьи из соседнего раздела")." ".$cat_name.":</strong></p>";
    }
}

//Функция устанавливает кнопки социальных сетей
function show_social_buttons(){
    echo "<p class='social' >".get_foreign_equivalent("Буду признателен, если воспользуетесь этими кнопочками:")."</p>";
?>

<script type="text/javascript">(function() {
          if (window.pluso)if (typeof window.pluso.start == "function") return;
          var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
          s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
          s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
          var h=d[g]('head')[0] || d[g]('body')[0];
          h.appendChild(s);
          })();</script>
        <div class="pluso" style="margin-left: 10px;"
             data-options="medium,square,line,horizontal,counter,theme=01" 
             data-services="vkontakte,odnoklassniki,facebook,twitter,google,
             googlebookmark,blogger,moimir,evernote,livejournal,liveinternet,
             bobrdobr,moikrug,moemesto,yandex,yazakladki,email,print" 
             data-background="transparent">
        </div>
  
<?
}

// Функция предназначена для получения количества пользователей сайта
function quant_users(){
    global $db;
    $result = mysql_query ("SELECT COUNT(*) FROM users",$db);
    $sum = mysql_fetch_array($result);
    return "<p class='comments'>".get_foreign_equivalent("Пользователей").": <strong>".($sum[0])."</strong></p>";
}
$quant_users_str = quant_users();
// Функция предназначена для получения количества читателей сайта
function quant_readers(){
    global $db;
    $result = mysql_query ("SELECT COUNT(*) FROM users WHERE cats <> '' and active=1",$db);
    $sum = mysql_fetch_array($result);
    return "<p class='comments'>".get_foreign_equivalent("Подписчиков").": <strong>".($sum[0])."</strong></p>";
}
$quant_readers_str = quant_readers();
//Функция выводит форму подписки на статьи по почте
function show_form_subscribe_by_mail($style = TRUE){
    global $quant_readers_str, $lang;
    $feed_lang = ($lang=="EN") ? $lang : "";
    $form_style = '';
    if ($style){$form_style = 'style="border:1px solid #70AECF;padding:3px;text-align:center; margin: 10px;"';}
?>
    <form <? echo $form_style; ?>
          action="http://feedburner.google.com/fb/a/mailverify" method="post" 
      target="popupwindow" 
      onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<? echo $feed_lang; ?>Softmakerkz', 'popupwindow', 'scrollbars=yes,width=550,height=520');
          return true">
        <input name="email" type="text" 
               value="<? echo get_foreign_equivalent("Введите Ваш e-mail");?>" 
               class="inputtext1" 
               onfocus="if (this.value == '<? echo get_foreign_equivalent("Введите Ваш e-mail");?>') {this.value = ''; this.className='inputtextact';}" 
               onblur="if (this.value == '') {this.value = '<? echo get_foreign_equivalent("Введите Ваш e-mail"); ?>'; this.className='inputtext1';}" 
         />
    </p>
    <input type="hidden" value="<? echo $feed_lang; ?>Softmakerkz" name="uri"/>
    <input type="hidden" name="loc" value="<? echo ($lang=="EN") ? "en_US" : "ru_RU"; ?>"/>
    <input type="submit" value="<? echo get_foreign_equivalent("Подписаться");?>" />
    <?
    // Количество читателей блога
    echo $quant_readers_str; ?>
</form>
<?

}
 //Функция выводит статью на другом языке
function show_other_lang_post(){
    global $db, $filename, $tbl, $id_score, $lang, $id_lang, $open, $deep;
    
    $link = get_other_lang_link();
    
    $ret = "";
    
    if ($lang == 'RU') {
        $field = "id_lang";
        $id_loc = $id_score;
    } else {
        $field = "id";
        $id_loc = $id_lang;
    }
    // Выберем статью на другом языке по $id
    $result = mysql_query("SELECT name,title,cat FROM ".$tbl." WHERE (".$field."=$id_loc) LIMIT 0 , 1",$db);
    $num_rows = mysql_num_rows($result);

    if ($num_rows != 0) {
        $myrow = mysql_fetch_array($result);
        $ret = show_title("",0);
        do 
        {
            $cat_name = get_fld_by_id($myrow[cat], 'categories', 'name');
            if ($cat_name == '0')
            {
                return;
            }
            $path = "$link/$filename/$cat_name/$myrow[name].html";
            $ret .= "<p>
                    <img width='10' height='10' src='".$deep."img/point.png'>
                    <a $open href='$path'>$myrow[title]</a>
                 </p>";
        } while ($myrow = mysql_fetch_array($result));
    }
    return $ret;
}

function set_tbl_link($id_cat, $tbl, $link) {
    global $db, $filename, $lang, $opp_lang;
    if ($lang == 'RU') {
        $field = "id_lang";
        $id_loc = $id_cat;
    } else {
        $field = "id"; // найдем id русской статьи
        $id_loc = get_fld_by_id($id_cat, $tbl, 'id_lang', " LIMIT 0 , 1");
        if ($id_loc == '0') {
            return $link.'/'.$filename.'/';
        }
    }
    
    if ($tbl != 'categories') {
        $query = "SELECT name, cat FROM ".$tbl." WHERE (".$field."=$id_loc) LIMIT 0 , 1";
    } else {
        $query = "SELECT name FROM ".$tbl." WHERE (".$field."=$id_loc) LIMIT 0 , 1";
    }
    
    // Выберем статью на другом языке по $id
    $result = mysql_query($query,$db);
    $num_rows = mysql_num_rows($result);
    
    if ($num_rows != 0) {
        $myrow = mysql_fetch_array($result);
        do 
        {
            if ($tbl != 'categories') {
                $cat_name = get_fld_by_id($myrow[cat], 'categories', 'name');
                if ($cat_name == '0')
                {
                    return $link.'/'.$filename.'/';
                }
                return $link.'/'.$filename.'/'.$cat_name.'/'.$myrow[name].'.html#top';
            } else {
                return $link.'/'.$filename.'/'.$myrow[name].'/';
            }
            
        } while ($myrow = mysql_fetch_array($result));
    } else {
        return $link.'/'.$filename.'/';
    }
} // set_tbl_link

// функция выдаёт ссылку, меняяя язык домена
function get_other_lang_link(){
     global $lang, $opp_lang, $HOST, $rest_;
     if (strstr($HOST, '192.168.') <> FALSE) {
        $link = $rest_;
        if ($lang == "RU") {
            $link = str_replace("/", "/".strtolower($opp_lang).'.', $rest_);
        } else {
            $link = str_replace(strtolower($lang).'.', "", $rest_);
        }
    } else {
        $link = "http://www.softmaker.kz";
        if ($lang == "RU") {
            $link = str_replace("www", strtolower($opp_lang), $link);
        }
     }
     return $link;
 } // get_other_lang_link
 
// функция выдаёт ссылку, меняя язык домена без http://
function get_lang_link_no_http(){
    global $lang;
    $link = "www.softmaker.kz";
    if ($lang <> "RU") {
        $link = str_replace("www", strtolower($lang), $link);
    }
    return $link;
 } // get_lang_link
 
 // функция выдаёт ссылку, меняя язык домена не смотря на расположение домена
function get_lang_link_(){
    global $lang;
    $link = "http://www.softmaker.kz/";
    if ($lang <> "RU") {
        $link1 = str_replace("www", strtolower($lang), $link);
    }
    return $link;
 } // get_lang_link

// функция вsдаёт ссылку, меняя язык домена
function get_lang_link(){
     global $lang, $opp_lang, $HOST, $rest_;
     if (strstr($HOST, '192.168.') <> FALSE) {
        $link = $rest_;
        if ($lang <> "RU") {
            $link = str_replace("/", "/".strtolower($lang).'.', $rest_);
        } else {
            $link = str_replace(strtolower($lang).'.', "", $rest_);
        }
    } else {
        $link = "http://www.softmaker.kz";
        if ($lang <> "RU") {
            $link = str_replace("www", strtolower($lang), $link);
        }
     }
     return $link;
 } // get_lang_link 

//Функция выводит ссылку на другом языке
function show_other_lang_link(){
    global $filename, $tbl;
    
    $link = get_other_lang_link();
    
    if ($filename == 'articles' OR $filename == 'files')  {
        if (isset($_GET['file_name']))
        {
            $id = get_fld_by_name($_GET['file_name'], $tbl, 'id');
            if ($id == '0')
            {
                return $link.'/'.$filename.'/';
            }
            return set_tbl_link($id, $tbl, $link);
        }  
        if (isset($_GET['cat_name'])) {
            $cat = get_fld_by_name($_GET['cat_name'], 'categories', 'id');
            if ($cat == '0')
            {
                return $link.'/'.$filename.'/';
            }
            return set_tbl_link($cat, 'categories', $link);
        } else {
            return $link.'/'.$filename."/";
        }
    } else {
        return ($filename == 'index') ? $link.'/' : $link.'/'.$filename.".php";
    }
} // show_other_lang_link

// Функция выводит анонсы статей и файлов
function print_post_notice($result, $post_type, $cat) {
    global $open, $rest_, $deep, $lang, $gap,
    $cat_name1, $page, $total, $advs, $Link;
    
    echo_error($result);
    
    if (mysql_num_rows($result) > 0){
        $myrow = mysql_fetch_array($result);

        if (!isset($cat)) {
            $cat_here = "";
        } else {
            $cat_here = "cat=".$cat."&";
        }
        $amnt_answrs = 0;
        do {
            $cat_name = $myrow[catname];   
            $path = $rest_."/$post_type/".$cat_name."/".$myrow["name"].".html";
            $comments = "<a $open href='".$path."#comm'>".
                    get_foreign_equivalent("Комментариев:")." ".
                    quant_comment($myrow["id"],$myrow["cat"])."</a>";
            printf ("<table align='center' class='post'>

                     <tr>
             <td class='post_title'>
                     <p class='post_name'><img class='mini' align='left' src='%s'><a $open href='%s'>%s</a></p>
                     <p class='post_adds'>".get_foreign_equivalent("Добавлен").": %s</p>
                     <p class='post_adds'>".get_foreign_equivalent("Автор").": %s</p></td>
             </tr>

                     <tr>
             <td>%s <p class='post_view'>".get_foreign_equivalent("Просмотров").": %s &nbsp;&nbsp; $comments </p></td>
             </tr>

                     </table>",$deep.$myrow["mini_img"], $path, $myrow["title"], $myrow["date"],$myrow["author"],$myrow["description"], $myrow["view"]);

            $amnt_answrs+=1;
//            if (($amnt_answrs == 1) OR ($amnt_answrs == 5)) {echo retArticlesTopPosition($cat);}
            if (($amnt_answrs == $gap)) {$advs->show('center', $Link);}
        } while ($myrow = mysql_fetch_array($result));
        
        $advs->show('bottom', $Link);
        // Выводим закладок страниц в подвале
        echo_bookmarks($cat_name1, $page, $total);
    } else {
        echo_no_records();
    }
}

// Проверяю какой секции принадлежит статья
function IsItPost($file) {
    if (!isset($file) OR $file == '') {
        return FALSE;
    }else{
        return TRUE;
    }
}

// Создание описаний статей с изображениями
function CreateSmartMessage($myrow, $faq = '') {
  
    global $db, $open, $deep, $rest_; 
    
    $name = $myrow[name];
    // получаю переменные $filename, $razdel
    $data[file_name] = $name;
    $Link = new Link($data);
    $ret_arr = $Link->getSection();
    $filename = $ret_arr[name];
    $razdel   = $ret_arr[title];
    
    $anchor = "#mdl";
    // Формирование сообщения из базы
    $text = $myrow[text];
    $description = $myrow[description];
    $title = $myrow[title];
    $cat = $myrow[cat];
    $meta_d = $myrow[meta_d];
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
    
    $link = get_lang_link_();
    $matches = null;
    // Вырезает из текста все теги img вместе с атрибутами $myrow[text]
    preg_match_all('/<img[^>]+>/i', stripslashes($text), $matches);
    $bodytag = $matches[0];
    $bodytag = str_replace($link, "../../", $bodytag);
    // Меняю расположение в зависимости от вложения
    $bodytag = str_replace("../../", $deep, $bodytag);
    $bodytag = str_replace('data', "files", $bodytag);

    if ($bodytag[0] != null){
        $image = "<p align=center>"
        ."<a $open href='$rest_/$filename/$cat_name/".$myrow[name].".html$anchor'>" 
        .$bodytag[0]."</a>"
        ."</p>";
        // Убираю лишние переводы строк из текста
        $image = str_replace("\r\n",'',$image);
        $image = str_replace("\n",'',$image);
        $image = str_replace("\n",'',$image);
    } else {
        // папка с миниатюрами
        // Меняю расположение в зависимости от вложения
        $part_file = 'img/thumb/';
        $cat_name_png = str_replace("1s", "odins", $cat_name);
        // временная папка по названию категории
        $mini_img_temp = $part_file."temp/$cat_name_png.png";
        // уже должна лежать миниатюра default.png
        $default_name = $part_file.'default.png'; 
        // миниатюра по транслитерированному названию статьи
        $distination = $part_file.$myrow[name].'.png'; 
        // ищем миниатюру по названию статьи
        if (!file_exists($distination)){ 
            $distination = $mini_img_temp;
            // ищем миниатюру по названию категории
            if (!file_exists($distination)){ 
                // не нашли берём по умолчанию
                $distination = $default_name;
            }
        }

        // Теперь получаем размеры исходного изображения.
        $size = getimagesize($distination);
        $image_size = $size[3];
        $img = "<img $image_size title='$myrow[title]'
        alt='$meta_d' src='$deep$distination' >";
        $image = "<p align=center>"
        ."<a $open href='$rest_/$filename/$cat_name/".$myrow[name].".html$anchor'> 
        $img</a>
        </p>";
    }

    $meta_d_p = "<p>".$meta_d."</p>";
    $href = "$rest_/$filename/$cat_name/$name.html";
    $href_cat = "$rest_/$filename/$cat_name/";
    $note = get_foreign_equivalent("Комментарии к этой заметке");
    $quant = get_foreign_equivalent("Комментариев:")." ".quant_comment($myrow[id], $cat);
    $view = get_foreign_equivalent("Просмотров");
    $category = get_foreign_equivalent("Категория");
    $title_a = "<a href='$href' $open>$title</a>";
    $comments = "<a title='$note $title' $open href='$href#comm'>$quant</a>";
    $date = $myrow[mdate];
    $comment = "<div class='post_top'>
        <span class='commentp'>
            $date | $view: $myrow[view] | $comments
        </span>
    </div>";
    $footer = "<p><a href='$href#top'" 
    .$open."'>"
    .get_foreign_equivalent("Читать далее")."...</a>"
    ."</p>";
    $title1 = "<h1 align=center>".$title_a."</h1>";
    $bottom =
    "<div class='post_bottom'>
        <span class='category'>
        $category:
        <b>
        <a rel='category tag' title='$razdel - $label - $title' href='$href_cat#top'>$label</a>
        </b>
        </span>
    </div>";
    echo $title1.$comment.$meta_d_p.$image.$description.$footer.$bottom;
} // CreateSmartMessage

//********************************** Создание миниатюр *************************
function create_image($source, $distination, $thumb_size, $check_dist = FALSE) {
     if ($check_dist){
         if (file_exists($distination)){
             return TRUE;
         }
     }
     // проверяю есть ли на самом деле файл исходного изображения - $source,
    if (file_exists($source)){
        // Отделяю расширение файла. 
        // Для этого использую функцию PHP explode для разделения имени файла 
        // на части между точками, а затем используем функцию count для 
        // определения последней части исходной строки, 
        // которая является расширением файла. 
        // Это нужно для определения формата изображения.
        $stype = explode(".", $source);
        $stype = $stype[count($stype)-1];
        switch($stype) {
            case 'gif':
            $image = imagecreatefromgif($source);
            break;
            case 'jpg':
            $image = imagecreatefromjpeg($source);
            break;
            case 'png':
            $image = imagecreatefrompng($source);
            break;
        }

        // Теперь получаем размеры исходного изображения.
        $size = getimagesize($source);
        $image_width = $size[0];    // Ширина исходного изображения 
        $image_height = $size[1];    // Высота исходного изображения

        // можно ещё так:
    //                $image_width = imagesx($image);
    //                $image_height = imagesy($image);

        $thumb_width = $thumb_size[0]; // Ширина миниатюры
        $thumb_height = $thumb_size[1]; // Высота миниатюры

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    //              imagecreatetruecolor  создает полноцветное изображение указанного в 
    //              параметрах размера и возвращает его идентификатор.
        imagealphablending($thumb, false);
    //                imagealphablending позволяет выбрать один из двух способов работы с альфа-каналом.
    //                Если второй параметр этой функции установлен в true, то при рисовании полупрозрачным 
    //                цветом новое изображение частично накладывается на старое. Если же он установлен в 
    //                false, то происходит полное замещение пикселей исходного изображения на пиксели 
    //                нового. Ясно, что нам нужен именно второй режим.
        imagesavealpha($thumb, true);
    //                Функция imagesavealpha необходима для того, чтобы при вызове функции 
    //                imagePNG альфа-канал был сохранен в результирующее изображение. 
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height); 
    //                imagecopyresampled копирует прямоугольную область из первого изображения во второе. 
    //                Она принимает на вход идентификаторы изображений, координаты верхнего левого угла 
    //                области во втором изображении, координаты верхнего левого угла области в первом 
    //                изображении, ширину и высоту области во втором изображении и ширину и высоту области 
    //                в первом. Если размеры областей различны, копируемое изображение растягивается или 
    //                сжимается. При этом применяется сглаживание, так что качество картинки остается 
    //                удовлетворительным.
        imagePNG($thumb,$distination,9);
    //                Сохраняем миниатюру по указанному пути - $distination.
    //                Так как мы хотим сохранить прозрачность исходного изображения, 
    //                миниатюру приходится сохранять в формате PNG.  

        //освобождаем память
        imagedestroy($image);
        imagedestroy($thumb);
        return TRUE;
    } else {return FALSE;}
}

// Параметр - $myrow массив с обработанным рядом результата запроса:
// $result = mysql_query("SELECT * FROM data WHERE id=$id");
// $myrow = mysql_fetch_array($result);
// Параметр - $deep нужен, если скрипт работает в папке /admin, a миниатюра в корневой. 
// Если скрипт в той же папке, то $deep = ''
 function create_thumb($myrow, $cat_name, $only_path = FALSE){
    global $deep;
    $thumb_size = array();
    $thumb_size[] = 200;
    $thumb_size[] = 150;
    // Нужна, подключённая библиотека GD !!!
    // Почти на всех хостинговых площадках эта библиотека установлена. 
    $link = get_lang_link_();
    $mini_img = $myrow[mini_img];
    $cat_name = str_replace("1s", "odins", $cat_name);
    $mini_img_temp = 'img/thumb/temp/'.$cat_name.'.png';
    // ищу сначала в папке с миниатюрами
    $part_file = 'img/thumb/';
    
    $default_name = $part_file.'default.png'; // уже должна лежать миниатюра default.png
    $distination = $part_file.$myrow[name].'.png'; // миниатюра по транслитерированному названию статьи
    try{
        if (!file_exists($distination)){
            $matches = null;
            // Вырезает из текста только src, т.е. только ссылку на изображение
            preg_match_all('/<img[^>]+src=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/', 
            stripslashes($myrow[text]), $matches);
            $bodytag = $matches[2];
            // заменяю http://?.softmaker.kz/ на ""
            $bodytag = str_replace($link, "", $bodytag);
            // заменяю ../../ на ""
            $bodytag = str_replace("../../", "", $bodytag); 
            if (!empty($bodytag)){ // если в статье есть хотя бы одно изображение
                $source = $bodytag[0]; // Исходный файл 
                $source = str_replace($link, "", $source);
            // проверяю есть ли на самом деле файл исходного изображения,
            // который получили из текста статьи
            if (create_image($source, $distination, $thumb_size)){
                // после создания миниатюры проверяю наличие 
                // в папке temp мини изображения для категории,
                // если есть, то удаляю его
//                if (file_exists($mini_img_temp)){
//                    unlink ($mini_img_temp);
//                }
            } else { // если файл исходного изображения не существует
                $distination = $default_name;
            } 
          } else { // если в статье нет ни одного изображения
              $source = $mini_img;
              $distination = $mini_img_temp;
              // то проверяю наличие мини изображения для категории
              if (!create_image($source, $distination, $thumb_size, TRUE)){
              // если нет мини изображения для категории ставлю дефолтное
                  $distination = $default_name;
              }
          } 
             
        }
    } catch (Exception $exc) {
           $distination = $default_name;
           // если возникла ошибка скрипта
    }
    
    if ($only_path)
    {
        return $distination;
    }
    $distination = '<p class="frstp" align=center><img width="'.$thumb_size[0].'" height="'.$thumb_size[1].'" 
	alt="'.$myrow[meta_d].'" src="'.$deep.$distination.'" ></p>';
    $rest = substr($myrow[title], 0, 57);
    $distination = $distination."<p class='scndp' align=center> 
          $rest... 
          </p>";
    return $distination;
 }
 
function mini_tbl() {
    global $mini, $open;
    $td_quant = 3;
    $i = 1;

//    echo '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >';
    $begin = '<div class="parent">';
    foreach ($mini as $k => $v) {
        if ($i == 1){
                $tr_1 = '<ol class="sdfhju">';
        } else {
            $tr_1 = '';
        } 
        $str .= $tr_1.'<li class="rle"><a href="'.$v[path].  
          '" '.$open.'
          >'.$v[dist].'</a></li>';
        if ($i == $td_quant) {
            $tr_2 = '</ol>';
            $i = 1;
            echo $str.$tr_2;
            $str = '';
        } else {
            $i+=1;
        }
    }

    $mini = array(); 
    return $begin.$str.'</ol></div><p class="clear"></p>';
}
 
 //Функция выводит список статей в миниатюрах
function print_mini($result, $nameoffile = "") {
    global $filename, $arr_id, $rest_, $mini;
    $myrow = mysql_fetch_array($result);
    do 
    {
        if ($nameoffile == ""){
            $nameoffile = $filename;
        }
        if ($arr_id == null){
            $arr_id[] = 0;
        }
        $cat_name = get_fld_by_id($myrow[cat], 'categories', 'name');
        if ($cat_name == '0')
        {
            return;
        }
        $path = "$rest_/$nameoffile/$cat_name/$myrow[name].html";
        if (!in_array($myrow[id], $arr_id)){
            $distination = create_thumb($myrow, $cat_name);
            $mini1[path] = $path;
            $mini1[dist] = $distination;
            $mini[] = $mini1;
            $arr_id[] = $myrow[id];
        }
    } while ($myrow = mysql_fetch_array($result));
}

//Функция выводит список статей текущей категории в миниатюрах
function show_mini() {
    global $db, $id_score, $cat, $lang, $cat_title; 
    $empty = 0; 
    $num_elements = 3; // количество выводимых статей
    $left_elements = 0; // количество оставшихся статей для вывода
    $arr_id = array();
    $mini = array();
    $ret = "";
    
//    mysql_query("SET NAMES 'cp1251'");
    // 1. Выберем статьи из текущей категории по id меньше текущего
    $result = mysql_query(get_query($num_elements-1,"<","DESC"),$db);
    $num_rows = mysql_num_rows($result);
    
    if ($num_rows != 0) {
        $empty = 1;
        echo show_title($cat_title);
        print_mini($result);
    } else { // Это первая статья по id
        // 1.1 Выбираем все $num_elements статей за ней из текущей категории по id больше текущего
        $result = mysql_query(get_query($num_elements,">", "ASC"),$db);
        $num_rows = mysql_num_rows($result);
        if ($num_rows != 0) {
            echo show_title($cat_title);
            print_mini($result);
            $ret = mini_tbl();
        }
        return $ret;
    }
        
    $left_elements = $num_elements - $num_rows;
    
    // 2. Выберем оставшиеся статьи из текущей категории по id больше текущего
    $result = mysql_query(get_query($left_elements,">"),$db);
    $num_rows = mysql_num_rows($result);
    if ($num_rows != 0) {
        if ($empty == 0) {
           echo show_title($cat_title);
        }
        print_mini($result);
    } else { // Это последняя статья по id
        // 2.1 Выбираем первую статью по id
        $result = mysql_query(get_query(1,"<"),$db);
        $num_rows = mysql_num_rows($result);
        if ($num_rows != 0) {
            if ($empty == 0) {
                echo show_title($cat_title);
            }
            print_mini($result);
        }
    }
    return mini_tbl();
}   // show_mini()

//Функция выводит список статей из соседней категории в миниатюрах
function show_cat_mini($sec) {
    global $db, $id_score, $cat, $lang, $filename, $tbl; 
    $num_elements = 3; // количество выводимых статей
    $arr_id = array();
    
    // 1. Найдём соседнюю категорию по id больше текущей
    $res = mysql_query(get_query_cat(">"),$db);
    $num_rows = mysql_num_rows($res);
    $ret = "";
    if ($num_rows != 0) { 
        $myrow_ = mysql_fetch_array($res);
        do 
        {
            $cat_title = $myrow_[title];
            $cat_name = $myrow_[name];
            $cat_id = $myrow_[id];
        } while ($myrow_ = mysql_fetch_array($res));
        
        $res_cat = mysql_query(get_query_next_cat($num_elements, $cat_id),$db);
        $num_rows = mysql_num_rows($res_cat);
        if ($num_rows != 0) {
            $arr_cat = array($cat_name, $cat_title);
            echo show_title($arr_cat, 2);
        }
        do 
        {
            print_mini($res_cat);
        } while ($myrow_cat = mysql_fetch_array($res_cat));
    } else { // Это последняя категория по id
        // 1.1 Выбираем первую категорию по id
        $res = mysql_query(get_query_cat("<"),$db);
        $num_rows = mysql_num_rows($res);
        if ($num_rows != 0) {
            $myrow_ = mysql_fetch_array($res);
            do 
            {
                $cat_title = $myrow_[title];
                $cat_name = $myrow_[name];
                $cat_id = $myrow_[id];
            } while ($myrow_ = mysql_fetch_array($res));
            $res_cat = mysql_query(get_query_next_cat($num_elements, $cat_id),$db);
            $num_rows = mysql_num_rows($res_cat);
            if ($num_rows != 0) {
                $arr_cat = array($cat_name, $cat_title);
                echo show_title($arr_cat, 2);
            }
            do 
            {
                print_mini($res_cat);
            } while ($myrow_cat = mysql_fetch_array($res_cat));
        }
    }
    return mini_tbl();
}   // show_cat_mini();

//Функция выводит список статей из соседнего раздела в миниатюрах
function show_sec_mini($sec) {
    global $db, $id_score, $lang; 
    $num_elements = 3; // количество выводимых статей
    $ret = "";
//    $ret_arr = $Link->getSection();
//    $filename = $ret_arr[name];
//    $sec_name = $ret_arr[title];
    $sec_res = mysql_query("SELECT * FROM sections WHERE lang='".$lang."' AND id<>".$sec." LIMIT 0 , 1",$db);

    echo_error($sec_res);

    if (mysql_num_rows($sec_res) > 0)
    {
        $sec_row = mysql_fetch_array($sec_res);
    do
    {
        $sec_name = $sec_row["title"];
        $filename = $sec_row[name];
//        $filename = str_replace(".php", "", $sec_row["file"]);
    }
    while ($sec_row = mysql_fetch_array($sec_res));
    } else {
        echo_no_records();
    }
    
    // Найдём соседние категории по sec
    $res = mysql_query(get_query_sec(),$db);
    $num_rows = mysql_num_rows($res);
    
    if ($num_rows != 0) { 
        $myrow_ = mysql_fetch_array($res);
        // сформируем список категорий текущего раздела
        $cat_list = "";
        do 
        {
            if ($cat_list == "") {
                $cat_list = $myrow_[id];
            }  else {
                $cat_list = $cat_list.",".$myrow_[id];
            }
        } while ($myrow_ = mysql_fetch_array($res));
 
        $res_cat = mysql_query(get_query_next_sec('data', $id_score, $num_elements, $cat_list),$db);
        $num_rows = mysql_num_rows($res_cat);
        if ($num_rows != 0) {
            echo show_title($sec_name, 3, $filename);
        }
        do 
        {
//            print_list($myrow_cat, $filename);
            print_mini($res_cat, $filename);
        } while ($myrow_cat = mysql_fetch_array($res_cat));
        $ret = mini_tbl();
    } 
    return $ret;
}   // show_sec_mini()

//********************************** Конец создания миниатюр *******************

 
//Функция формирует запрос
function get_query($num_elements, $sign, $order = 'ASC') {
    global $id_score, $cat, $lang;
    return "SELECT text,title,id,cat,name,mini_img,cat,meta_d FROM data WHERE (cat=$cat) AND(lang='".$lang."')AND(id".$sign.$id_score.") ORDER BY id ".$order." LIMIT 0 , ".$num_elements;
}

//Функция выводит список статей
function print_list($myrow, $nameoffile = "") {
    global $filename, $arr_id, $rest_, $deep, $open;
    $ret = '';
    if ($nameoffile == ""){
        $nameoffile = $filename;
    }
    if ($arr_id == null){
        $arr_id[] = 0;
    }
    $cat_name = get_fld_by_id($myrow[cat], 'categories', 'name');
    if ($cat_name == '0')
    {
        return;
    }
    $path = "$rest_/$nameoffile/$cat_name/$myrow[name].html";
    if (!in_array($myrow[id], $arr_id)){
        $ret .= "<p>
                    <img width='10' height='10' src='".$deep."img/point.png'>
                    <a $open href='$path'>$myrow[title]</a>
                 </p>";
        $arr_id[] = $myrow[id];
    }
    return $ret;
}

//Функция выводит список статей текущей категории
function show_list() {
    global $db, $id_score, $cat, $lang, $filename, $tbl, $cat_title; 
    $empty = 0; 
    $num_elements = 10; // количество выводимых статей
    $left_elements = 0; // количество оставшихся статей для вывода
    $arr_id = array();
    
    // 1. Выберем статьи из текущей категории по id меньше текущего
    $result = mysql_query(get_query($num_elements-1,"<","DESC"),$db);
    $num_rows = mysql_num_rows($result);
    
    if ($num_rows != 0) {
        $empty = 1;
//        show_title($cat_title);
        $myrow = mysql_fetch_array($result);
        do 
        {
            $ret .= print_list($myrow);
        } while ($myrow = mysql_fetch_array($result));
    } else { // Это первая статья по id
        // 1.1 Выбираем все $num_elements статей за ней из текущей категории по id больше текущего
        $result = mysql_query(get_query($num_elements,">", "ASC"),$db);
        $num_rows = mysql_num_rows($result);
        if ($num_rows != 0) {
//            show_title($cat_title);
            $myrow = mysql_fetch_array($result);
            do 
            {
                $ret .= print_list($myrow);
            } while ($myrow = mysql_fetch_array($result));
        }
        return $ret;
    }
        
    $left_elements = $num_elements - $num_rows;
    
    // 2. Выберем оставшиеся статьи из текущей категории по id больше текущего
    $result = mysql_query(get_query($left_elements,">"),$db);
    $num_rows = mysql_num_rows($result);
    if ($num_rows != 0) {
        if ($empty == 0) {
//            show_title($cat_title);
        }
        $myrow = mysql_fetch_array($result);
        do 
        {
            $ret .= print_list($myrow);
        } while ($myrow = mysql_fetch_array($result));
    } else { // Это последняя статья по id
        // 2.1 Выбираем первую статью по id
        $result = mysql_query(get_query(1,"<"),$db);
        $num_rows = mysql_num_rows($result);
        if ($num_rows != 0) {
            if ($empty == 0) {
//                show_title($cat_title);
            }
            $myrow = mysql_fetch_array($result);
            do 
            {
                $ret .= print_list($myrow);
            } while ($myrow = mysql_fetch_array($result));
        }
    }
    return $ret;
}   // show_list()

//Функция формирует запрос на получение соседней категории
function get_query_cat($sign, $order = 'ASC') {
    global $sec, $cat, $lang;
    return "SELECT title,id,name FROM categories WHERE (id".$sign.$cat.")AND(lang='".$lang."')AND(sec='".$sec."') AND (turnon=1) ORDER BY id ".$order." LIMIT 0 , 1";
}

//Функция формирует запрос на статьи из соседней категории
function get_query_next_cat($num_elements, $cat) {
    global $lang, $tbl;
//    $even_odd = $cat/2 - round($cat/2);
//    if ($even_odd == 0){
//        $order = 'ASC';
//    } else {
        $order = 'DESC';
//    }
//    $order = 'ASC';
    return "SELECT text,title,id,cat,name,mini_img,cat,meta_d FROM ".$tbl." WHERE (cat=".$cat.")AND(lang='".$lang."') ORDER BY id ".$order." LIMIT 0 , ".$num_elements;
}

//Функция выводит список статей из соседней категории
function show_cat_list($sec) {
    global $db, $id_score, $cat, $lang, $filename, $tbl; 
    $num_elements = 10; // количество выводимых статей
    $arr_id = array();
    
    // 1. Найдём соседнюю категорию по id больше текущей
    $res = mysql_query(get_query_cat(">"),$db);
    $num_rows = mysql_num_rows($res);
    
    if ($num_rows != 0) { 
        $myrow_ = mysql_fetch_array($res);
        do 
        {
            $cat_title = $myrow_[title];
            $cat_name = $myrow_[name];
            $cat_id = $myrow_[id];
        } while ($myrow_ = mysql_fetch_array($res));
        
        $res_cat = mysql_query(get_query_next_cat($num_elements, $cat_id),$db);
        $num_rows = mysql_num_rows($res_cat);
        if ($num_rows != 0) {
            $arr_cat = array($cat_name, $cat_title);
            show_title($arr_cat, 2);
        }
        $myrow_cat = mysql_fetch_array($res_cat);
        do 
        {
            print_list($myrow_cat);
        } while ($myrow_cat = mysql_fetch_array($res_cat));
    } else { // Это последняя категория по id
        // 1.1 Выбираем первую категорию по id
        $res = mysql_query(get_query_cat("<"),$db);
        $num_rows = mysql_num_rows($res);
        if ($num_rows != 0) {
            $myrow_ = mysql_fetch_array($res);
            do 
            {
                $cat_title = $myrow_[title];
                $cat_name = $myrow_[name];
                $cat_id = $myrow_[id];
            } while ($myrow_ = mysql_fetch_array($res));
            $res_cat = mysql_query(get_query_next_cat($num_elements, $cat_id),$db);
            $num_rows = mysql_num_rows($res_cat);
            if ($num_rows != 0) {
                $arr_cat = array($cat_name, $cat_title);
                show_title($arr_cat, 2);
            }
            $myrow_cat = mysql_fetch_array($res_cat);
            do 
            {
                print_list($myrow_cat);
            } while ($myrow_cat = mysql_fetch_array($res_cat));
        }
    }
//    echo "<p>&nbsp;</p>";
}   // show_cat_list()

//Функция формирует запрос на получение соседнего раздела
function get_query_sec($sign = '<>', $order = 'ASC') {
    global $sec, $lang;
//    return "SELECT title,id FROM categories WHERE (lang='".$lang."')AND(sec'".$sign.$sec."') ORDER BY id ".$order." LIMIT 0 , 1";
      return "SELECT text,title,id,cat_tbl FROM categories WHERE (lang='".$lang."')AND(sec".$sign.$sec.") AND (turnon=1) ORDER BY id ".$order;
}

//Функция формирует запрос на статьи из соседнего раздела
function get_query_next_sec($tbl, $id, $num_elements, $cat_list) {
    global $lang;
    $even_odd = $id/2 - round($id/2);
    if ($even_odd == 0){
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
    return "SELECT text,title,id,cat,name,mini_img,cat,meta_d FROM ".$tbl." WHERE ( cat IN (".$cat_list."))AND(lang='".$lang."') ORDER BY id ".$order." LIMIT 0 , ".$num_elements;
}

//Функция выводит список статей из соседнего раздела
function show_sec_list($sec) {
    global $db, $id_score, $lang; 
    $num_elements = 10; // количество выводимых статей
    
    $sec_res = mysql_query("SELECT * FROM sections WHERE lang='".$lang."' AND id<>".$sec." LIMIT 0 , 1",$db);

    echo_error($sec_res);

    if (mysql_num_rows($sec_res) > 0)
    {
        $sec_row = mysql_fetch_array($sec_res);
    do
    {
        $sec_name = $sec_row["title"];
        $filename = str_replace(".php", "", $sec_row["file"]);
    }
    while ($sec_row = mysql_fetch_array($sec_res));
    } else {
        echo_no_records();
    }
    
    // Найдём соседние категории по sec
    $res = mysql_query(get_query_sec(),$db);
    $num_rows = mysql_num_rows($res);
    
    if ($num_rows != 0) { 
        $myrow_ = mysql_fetch_array($res);
        // сформируем список категорий текущего раздела
        $cat_list = "";
        do 
        {
            if ($cat_list == "") {
                $cat_list = $myrow_[id];
                $tbl = $myrow_[cat_tbl]; 
            }  else {
                $cat_list = $cat_list.",".$myrow_[id];
            }
        } while ($myrow_ = mysql_fetch_array($res));
 
        $res_cat = mysql_query(get_query_next_sec($tbl, $id_score, $num_elements, $cat_list),$db);
        $num_rows = mysql_num_rows($res_cat);
        if ($num_rows != 0) {
            show_title($sec_name, 3);
        }
        $myrow_cat = mysql_fetch_array($res_cat);
        do 
        {
            print_list($myrow_cat, $filename);
        } while ($myrow_cat = mysql_fetch_array($res_cat));
    } 
}   // show_sec_list()

//Функция выводит "хлебные крошки"
function show_breadcrumbs($level, $cat_name = '', $ext = '') {
    global $razdel, $id_score, $cat, $cat_title, $go_to_home, $lang, $filename, $deep, $rest_; 
    $strelka = "<img width='4' height='5' class ='arr_bc' src='".$deep."img/ArrowBlue.gif'>";
    if ($ext == '') {
        $ext = '/';
    }
    ?>
    <p class="breadcrumb">
    <?
    if ($level >= 1) {
    ?>
        <a href="<? echo $rest_; ?>/" 
            title="<? echo $go_to_home?>">
            SoftMaker.kz
        </a>
        <? echo $strelka;?>
        <a href="<? echo $rest_.'/'.$filename.$ext;?>" 
            title="Перейти к разделу '<? echo $razdel;?>'">
            <? echo $razdel; ?>
        </a>
    <? 
    }
    if ($level >= 2) {
        echo $strelka;?>
        <a href="<? echo $rest_.'/'.$filename.'/'.$cat_name.'/';?>" 
            title="Перейти к категории '<? echo $cat_title;?>'">
            <? echo $cat_title;?>
        </a>
    <? 
    }
    if ($level >= 3) {
        if (strlen($title) > 70) {
            $title = substr($title, 0, 70)."..."; 
        }
        echo $strelka;?>
        <a href="<? echo $filename;?>.php?cat=<? echo $cat;?>&id=<? echo $id_score;?>&lang=<? echo $lang;?>" 
            title="<? echo $title;?>">
            <? echo $title;?>
        </a>
    <?
    }
    ?>
    </p>
    <?
}

//Функция проверяет сколько раз была нажата кнопка 
//отправки сообщений в промежутке в $secundy секунд jkghjfhdghf
function check_submit($my_post, $secundy = 60, $mode = 'comment') {
    global $today, $session, $raznica;

    if(isset($_SESSION['logged_in'])) 
    { 
        $user = unserialize($_SESSION['user']); 
        $username = $user->username;
        if (strtolower(trim($username)) == "root" or strtolower(trim($username)) == "softmaker") 
        {
            return 1;
        }    
    }
    
    $time = strtotime ("now");
    
    $query = "DELETE  FROM `session`  WHERE (time - ".$time.") > 36000";
    $query_result = mysql_query ($query);

    $seconds = $today['seconds'];
    $minutes = $today['minutes'];
    $hours = $today['hours'];
    $mday = $today['mday'];
    $month = $today['month'];
    $year = $today['year'];
    if (array_key_exists("sub_mail", $_POST)) {
    
        $result = mysql_query("SELECT * FROM session WHERE session='$session' AND mode='$mode'");

        echo_error($result);

        if (mysql_num_rows($result) > 0)
        {
            $myrow = mysql_fetch_array($result);
            $from=$myrow['time'];//время от
            $to=strtotime("$today[mday] $today[month] $today[year] $today[hours] hours $today[minutes] minutes");//время до
            $raznica=($to - $from); // - секунды
            if (round($raznica) < $secundy) {
                  return 0;
            }
        }else{
            $myrow = mysql_fetch_array($result);
            $INSERT = mysql_query ("INSERT INTO session (session,time,mode) VALUES ('$session', '$time', '$mode')");
            return 1;
        }
    $result = mysql_query ("UPDATE session SET time='$time' WHERE session='$session'");
    return 1;
    }
}

// ********************** Раскрашиваем код PHP *******************************

function unhtmlentities ($str)
{
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($str, $trans_tbl);
}

function highlight_code($code) 
{ 
  // если до этого $code обрабатывали функцией
  // htmlspecaialchars, здесь нужно дописать код, который отменяет ее действие 
  $code = unhtmlentities ($code);
//  if(!strpos($code,"<?") && substr($code,0,2)!="<?") {
//    $code="<?php\\n".trim($code)."\\n?)>"; 
//  }  
  $code = trim($code); 
  return highlight_string($code,true);
}

function get_highlight_code($code, $br = true) 
{ 
    $string = $code;
    $CountOfEntry = preg_match_all("/<pre>(.+?)<\\/pre>/is", $string, $matches, PREG_PATTERN_ORDER);
    if ($CountOfEntry != 0) {
        for ($i = 0; $i < $CountOfEntry; $i++) {
            // меняю код в тексте на строку "<pre></pre>"
            $pre = str_ireplace($matches[0][$i],"<pre>".$i."</pre>",$string);
            $string = $pre;
        }
        for ($i = 0; $i < $CountOfEntry; $i++) {
            // меняю код в тексте на строку "<pre></pre>"
            $matches_str = $matches[0][$i];
            if (stristr($matches_str, '&lt;?') == FALSE AND stristr($matches_str, '<?php') == FALSE){
                $string = str_ireplace("<pre>".$i."</pre>",$matches_str,$string);
                continue;
            }
            $matches_str = str_ireplace("<pre>","",$matches_str);
            $matches_str = str_ireplace("</pre>","",$matches_str);
            $matches_str = str_ireplace("<strong>","",$matches_str);
            $matches_str = str_ireplace("</strong>","",$matches_str);
            $highlight_code = highlight_code($matches_str);
            if ($br) {
                $highlight_code = str_ireplace("<br />","",$highlight_code);
            }
            $pre = str_ireplace("<pre>".$i."</pre>","<pre>".$highlight_code."</pre>",$string);
            $string = $pre;
        }
        return $string;
    }
  return $code;
}

// Функция предназначена для вывода сообщения об ошибке запроса
function echo_error($result) 
{ 
    if (!$result) {
        $err_str = mysql_error();
        echo get_foreign_equivalent("<p>Запрос на выборку данных из базы не прошел. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>");
        echo("<p>".$err_str."</p>");
    }
} // echo_error

function MovedPermanently($link) 
{
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: $link");
    exit ('');
}

// Функция предназначена для вывода сообщения о пустой таблице
function echo_no_records() 
{
    global $lang, $rest_;
    //редирект на страницу ошибки 404
    $redirect = get_foreign_equivalent("Выполняется перенаправление на указанную страницу подождите...");
    $wait = get_foreign_equivalent("Если не хотите ждать нажмите здесь.");
    header("HTTP/1.1 404 Not Found");
    header( "Refresh: 2; url=404.php#mdl" );
    echo "<table align='center' width='100%' height='100%'>
            <tr>
            <td valign='middle' align='center'>
                <p align='center'><img src='".$rest_."/img/dic/loading1.gif' width='16' height='16'><br>".
                    $redirect 
                ."<br>
                <a href='".$rest_."/404.php#mdl'>".
                    $wait
                ."</a>
                </p>
            </td>
            </tr>
          </table>";
    exit ('');
}

// ********************** Функции для работы с мультиязыковым сайтом *******************************

// Функция предназначена для получения перевода иностранного слова с русского
function get_foreign_equivalent($word) 
{ 
    global $lang, $db;
    
    if ($lang == 'RU'){return $word;}
    
    $res_lang = mysql_query("SELECT ".$lang." FROM catalog WHERE RU='".$word."'",$db);
//    echo $word;
    echo_error($res_lang);

    if (mysql_num_rows($res_lang) > 0)

    {
        $myrow_lang = mysql_fetch_array($res_lang);
        do
        {   
//            echo "да";
            return $myrow_lang[$lang];
        }
        while ($myrow_lang = mysql_fetch_array($res_lang));
    }
//    echo "нет";
    return $word;
} // get_foreign_equivalent

// Функция предназначена для получения количества комментариев на статью
function quant_comment($id,$cat)
{
    global $db;
    $res_kol = mysql_query ("SELECT COUNT(*) FROM comments WHERE post='$id' and cat='$cat'",$db);
    $sum_kol = mysql_fetch_array($res_kol);
    return $sum_kol[0];
}

// Функция предназначена для вывода закладок страниц в подвале
function echo_bookmarks($cat_name, $page, $total) 
{ 
    global $filename, $rest_;
   
    if (isset($cat_name)) {
        $path = "$rest_/$filename/$cat_name";
    }elseif ($filename == 'index') {
        $path = "$rest_";
    } else {
        $path = "$rest_/$filename";
    }
    
    // Проверяем нужны ли стрелки назад
    if ($page != 1) $pervpage = '<a href='.$path.'/1/>'.get_foreign_equivalent("Первая").'</a> | <a href='.$path.'/'. ($page - 1) .'/>'.get_foreign_equivalent("Предыдущая").'</a> | ';
    // Проверяем нужны ли стрелки вперед
    if ($page != $total) $nextpage = ' | <a href='.$path.'/'. ($page + 1) .'/>'.get_foreign_equivalent("Следующая").'</a> | <a href='.$path.'/' .$total. '/>'.get_foreign_equivalent("Последняя").'</a>';

    // Находим две ближайшие станицы с обоих краев, если они есть
    if($page - 5 > 0) $page5left = ' <a href='.$path.'/'. ($page - 5) .'/>'. ($page - 5) .'</a> | ';
    if($page - 4 > 0) $page4left = ' <a href='.$path.'/'. ($page - 4) .'/>'. ($page - 4) .'</a> | ';
    if($page - 3 > 0) $page3left = ' <a href='.$path.'/'. ($page - 3) .'/>'. ($page - 3) .'</a> | ';
    if($page - 2 > 0) $page2left = ' <a href='.$path.'/'. ($page - 2) .'/>'. ($page - 2) .'</a> | ';
    if($page - 1 > 0) $page1left = '<a href='.$path.'/'. ($page - 1) .'/>'. ($page - 1) .'</a> | ';

    if($page + 5 <= $total) $page5right = ' | <a href='.$path.'/'. ($page + 5) .'/>'. ($page + 5) .'</a>';
    if($page + 4 <= $total) $page4right = ' | <a href='.$path.'/'. ($page + 4) .'/>'. ($page + 4) .'</a>';
    if($page + 3 <= $total) $page3right = ' | <a href='.$path.'/'. ($page + 3) .'/>'. ($page + 3) .'</a>';
    if($page + 2 <= $total) $page2right = ' | <a href='.$path.'/'. ($page + 2) .'/>'. ($page + 2) .'</a>';
    if($page + 1 <= $total) $page1right = ' | <a href='.$path.'/'. ($page + 1) .'/>'. ($page + 1) .'</a>';

    // Вывод меню если страниц больше одной
    if ($total > 1) {
        Error_Reporting(E_ALL & ~E_NOTICE);
        echo "<div class=\"pstrnav\">";
        echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<span class=currPage>'.$page.'</span>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
        echo "</div>";
    }else{
        echo "</br>";
    }
}

// Функция предназначена для получения файла, сохранённого в кэше
function get_cache()
{
    global $logon;
    if (isset($_GET['file_name'])) {
        $filename = 'cache/'.$_GET['file_name'].'_'.$logon.'.cache';
        if (file_exists($filename)){
            $today = strtotime(date("m.d.y")); 
            $filedate = strtotime(date("m.d.y", filectime($filename)));
            // если прошло больше суток с момента создания файла, то удаляю его
            if (($today - $filedate) >= 60) {
                if (file_exists($filename)){
                    unlink ($filename);
                }
            } else {
                if ($logon){ // если юзер зашёл меняем имя в файле
                    $body = file_get_contents($filename);
                    $Hello = get_foreign_equivalent("Здравствуйте");
                    preg_match("/$Hello, <strong>([^<]+)<*/i", $body, $found);
                    $user = unserialize($_SESSION['user']);
                    $bodytag = str_ireplace($found[1], $user->username."!", $body);
                    file_put_contents($filename, $bodytag);
                } 
                readfile($filename); exit();
            }
        } 
        ob_start();
    }
}

// Функция предназначена для записи файла из буфера в кэш
function set_cache()
{
    global $logon;
    if (isset($_GET['file_name'])) {
        $filename = 'cache/'.$_GET['file_name'].'_'.$logon.'.cache';
        $buffer = ob_get_contents();
        ob_end_flush(); 
        $fp = fopen($filename, 'w'); 
        fwrite($fp, $buffer); 
        fclose($fp);
    }
}

// Функция предназначена для удаления файла кэша
function del_cache($tbl)
{
    global $logon;
    $QUERY_STRING = $_SERVER['QUERY_STRING'];
    $arr=parse_url($QUERY_STRING);
    parse_str($arr['query'], $arr2);
    $file_name = $arr2['file_name'];
    if (isset($_POST['id'])) {
        $file_name = get_fld_by_id($_POST['id'], $tbl, 'name');

        $filename0 = 'cache/'.$file_name.'_0.cache';
        $filename1 = 'cache/'.$file_name.'_1.cache';
        // удалю два файла
        if (file_exists($filename0)){ 
            unlink ($filename0);
        }
        if (file_exists($filename1)){
            unlink ($filename1);
        }
    }
} // del_cache

function get_cat_rows($user) {
    require_once 'classes/DB.class.php';

    $db_class = new DB();
    $cat_array = $db_class->select('categories', "lang='$user->lang' AND (turnon=1)", "id, title");
//    $resultArray = array();
//    foreach($cat_array as $k => $v) {
//        $resultArray[] = $v[id];
//    }
//    $cats_str = implode(",", $resultArray);
    $cat_rows = '';
    $user_cats_array = explode(",", $user->cats);
    foreach($cat_array as $k => $v) {
        $yesno = (in_array($v[id], $user_cats_array) == FALSE) ? "" : "checked" ;
        $cat_rows .= 
        "<tr>
            <td><p><strong>$v[title]</strong></p>
            </td>
            <td><input id='cat-$v[id]' name='cats[]' value='$v[id]' type='checkbox' $yesno >
            </td>
         </tr>";
    }
    $header_str = get_foreign_equivalent("Уведомлять меня о выходе новых статей в следующих категориях");
    $header = 
            "<tr>
            <td colspan=2>
            <h2>
            <strong>$header_str:</strong>
            </h2>
            </td>
         </tr>";
    return $header.$cat_rows;
} // get_cat_rows

function get_all_cat_rows() {
    global $lang;
    require_once 'classes/DB.class.php';

    $db_class = new DB();
    $cat_array = $db_class->select('categories', "lang='$lang' AND (turnon=1)", "id, title");
    $cat_rows = '';
    foreach($cat_array as $k => $v) {
        $cat_rows .= 
        "<tr>
            <td><p><strong>$v[title]</strong></p>
            </td>
            <td><input id='cat-$v[id]' name='cats[]' value='$v[id]' type='checkbox' checked >
            </td>
         </tr>";
        if ($v[id]==5){
            $cat_rows .= "<tr><td><p><span class=nolink onclick=\"GoTo('_scloud.ru/?ref=TzE3f','s')\">
                    <span>
                    &#160;&#160;&#10149;Аренда 1С в облаке! Первые 14 дней бесплатно!
                    </span>
            </span></p></td></tr>";  
            $cat_rows .= "<tr><td><p><span class=nolink onclick=\"GoTo('_virtual1c.net/?affid=119254','')\">
                    <span>
                    &#160;&#160;&#10149;БУХГАЛТЕРИЯ ОНЛАЙН! первый месяц бесплатно!
                    </span>
            </span></p></td></tr>";
        }
    }
    $header_str = get_foreign_equivalent("Уведомлять меня о выходе новых статей в следующих категориях");
    $header = 
            "<tr>
            <td colspan=2>
            <h2>
            <strong>$header_str:</strong>
            </h2>
            </td>
         </tr>";
    return $header.$cat_rows;
} // get_all_cat_rows

function tbl_info($tbl, $id, $fld_list, $nolang = '0', $fld = 'id') {
    global $db, $lang;
    $lang_list = '';
    if ($nolang == '0') {
        $lang_list = "AND (lang='$lang')";
    }
    // получаю информацию о категории
    $result = mysql_query("SELECT $fld_list FROM $tbl WHERE ($fld='$id') $lang_list",$db);

    echo_error($result);

    if (mysql_num_rows($result) > 0) {
        return mysql_fetch_array($result);
    }
}

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
// Функция предназначена для получения значения любого поля (cat) по name
function get_fld_by_name($name, $tbl, $fld = 'cat', $add_clause = "")
{   
    global $db, $lang;
    $result = mysql_query("SELECT ".$fld." FROM ".$tbl." WHERE (name='$name') AND (lang='$lang')".$add_clause,$db);
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

// Функция предназначена для получения номера записи таблицы
function get_id($tbl_dt)
{   // отсортируем по убыванию
    $result = mysql_query("SELECT id FROM ".$tbl_dt." ORDER BY `id` DESC");    
    $myrow = mysql_fetch_array($result);
    $num_rows = mysql_num_rows($result);
    
    // если количество записей совпадает с последним id, тогда...
    if ($num_rows==$myrow['id'])
    { // ...возьмём самый первый элемент в выборке с наибольшим значением id,
     // увеличив на единицу это значение
       return $myrow['id'] + 1;
    } else { // отсортируем по возрастанию, затем...
        $result1 = mysql_query("SELECT id FROM ".$tbl_dt." ORDER BY `id` ASC");    
        $myrow1 = mysql_fetch_array($result1);
        $i = 1;
        do // ...ищем "пустой" id и добавляем запись по этому id
        {
            if ($i == $myrow1['id']) {
                $i++;
                continue;
            } else {
                return $i;
            }
        }
        while ($myrow1 = mysql_fetch_array($result1));
    }
} // get_id

// Функция предназначена для проверки переменной на существование и тип число
function get_set_of_var($var, $num = 1)
{   
    global $langs;
    /* Проверяем, существует ли переменная */
    if (isset($var)) 
    {
        /* если проверка на число */
        if ($num == 1) 
        {
            /* Проверяем, является ли переменная числом */
            if (preg_match("|^[\d]+$|", $var)) 
            {
                return TRUE;
            }
        } else {
            if ($var <> '' And in_array(strtoupper($var), $langs)) 
            {
                return TRUE;
            } 
//            else {
//                if (in_array (strtoupper($var), $langs)) {
//                    return TRUE;
//                }
//            }
        }
    }
    return FALSE;
}

function kama_content_adv_file($text, $cat){
    //Код вашей рекламы
    $adsense = retArticlesTopPosition($cat);
    $pattern = 'Содержание</h2>';
    $i = preg_match($pattern, $text);
    if ($i<>0){
        $count = null;
        return preg_replace("~$pattern~", $pattern.$adsense, trim($text), 1, $count);
    }
    $pattern = '<p align="center">';
    $i = preg_match($pattern, $text);
    if ($i<>0){
        $count = null;
        return preg_replace("~$pattern~", $adsense.$pattern, trim($text), 1, $count);
    }
    $pattern = '<ul>';
    $i = preg_match($pattern, $text);
    if ($i<>0){
        $count = null;
        return preg_replace("~$pattern~", $adsense.$pattern, trim($text), 1, $count);
    }
    $count = null;
    return preg_replace('~<!--more-->~', $adsense, trim($text), 1, $count);
}

function kama_content_advertise($text, $cat){
    //Код вашей рекламы
    $adsense = retArticlesTopPosition($cat);
    $count = null;
    $ret = preg_replace('~<!--more-->~', $adsense, trim($text), 2, $count);
    return preg_replace('~<!--more-->~', getPopovRotator(), trim($ret), 1, $count);;
    
}

function getPopovRotator(){
    $ret = "
    <div id='PM_rotator9908' style='display: block; margin-left: auto ; margin-right: auto ; margin-bottom: 23px ; padding: 0; width: 730px; height: 92px;'></div>
    <script type='text/javascript' src='http://1.photoshop-master.org/rotator/rotator2.js/9908'></script>";
    return $ret;
}

function get_IPCOUNTRY()
{
    global $lang;
    if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) 
    {   
        if ($_SERVER['HTTP_CF_IPCOUNTRY'] == 'KZ') 
        {
            return 0;
        } else { return  -1;}
    } else { return -1;}
}

// Функция предназначена для получения блока верхней рекламы на index.php и files.php
function AdvTopPosition()
{
    $catid1 = get_IPCOUNTRY();
    ?>
     <div class="adv_div" align="center">
        <?php
        if ($catid1==120) {
        ?>
            <a href="articles.php?cat=29&id=88&lang=RU" target="_blank">
            <img width="468" height="60" title="Переводчик английского языка"
            alt="Переводчик английского в Алматы" src="banner/NadinBanner.gif" >
            </a>
        <?php
        } else {
            /* ГлавВерхПолнБан 728x90 03.07.2013 */
        ?>
            <script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle"
                 style="display:inline-block;width:728px;height:90px"
                 data-ad-client="ca-pub-7017401012475874"
                 data-ad-slot="5040822566"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php
        }
        ?>
    </div>
    <?php
} // AdvTopPosition

function ins_adv_file($text, $cat)
{
    if (substr_count($text, '<!--more-->') >= 1)
    {
        return kama_content_advertise($text, $cat);
    }
    
    $tag = "</h2>";
    $pieces = explode($tag, $text);

    $result  = count($pieces);
    if ($result==1){ 
        $tag = "</p>";
        $pieces = explode($tag, $text);
    }
    $result  = count($pieces);
    if ($result==1){ 
        $tag = "</li>";
        $pieces = explode($tag, $text);
    }

    $txt='';$i=0;
    $arr = array(); $arr1 = array();
    foreach ($pieces as $piece) {
        $txt.= $piece;
        if (strlen(strip_tags($txt))>1200)
        {
            $arr[] = substr($piece, -250).$tag; 
            $arr1[] = substr($piece, -250).$tag.retArticlesTopPosition($cat);
            $txt=''; $i+=1;
        }
        if ($i==2) {break;}
    }
    if ($i==0) 
    {
        return str_replace($arr, $arr1, $text).retArticlesTopPosition($cat);
     } else {return str_replace($arr, $arr1, $text);}
}// ins_adv_file

function ins_adv($text, $cat)
{
    if (substr_count($text, '<!--more-->') >= 1)
    {
        return kama_content_advertise($text, $cat);
    }
    
    $tag = "</h2>";
    $pieces = explode($tag, $text);

    $result  = count($pieces);
    if ($result==1){ 
        $tag = "</p>";
        $pieces = explode($tag, $text);
    }
    $result  = count($pieces);
    if ($result==1){ 
        $tag = "</li>";
        $pieces = explode($tag, $text);
    }

    $txt='';$i=0;
    $arr = array(); $arr1 = array();
    foreach ($pieces as $piece) {
        $txt.= $piece;
        if (strlen(strip_tags($txt))>1200)
        {
            $arr[] = substr($piece, -250).$tag; 
            $arr1[] = substr($piece, -250).$tag.retArticlesTopPosition($cat);
            $txt=''; $i+=1;
        }
        if ($i==2) {break;}
    }
    if ($i==0) 
    {
        return str_replace($arr, $arr1, $text).retArticlesTopPosition($cat);
     } else {return str_replace($arr, $arr1, $text);}
}
// Функция предназначена для получения блока верхней рекламы статей
function retArticlesTopPosition($catid){   
    $catid1 = get_IPCOUNTRY();    
    if ($catid1==120) 
    {
        $ret = "
        <a href='http://www.softmaker.kz/articles/reklama/opytnyj-perevodchik-anglijskogo-yazyka-v-almaty.html' target='_blank'>
        <img width='468' height='60' title='Переводчик английского языка'
        alt='Переводчик английского в Алматы' src='banner/NadinBanner.gif' >
        </a>";
    } elseif ($catid==1) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиHTML */
        google_ad_slot = '8437999491';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>";
    } elseif ($catid==2) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиPHP */
        google_ad_slot = '5733221937';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
        $ret = '<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             /* ГлавВерхБанСтатьиPHP */
             style="display:inline-block;width:728px;height:90px"
             data-ad-client="ca-pub-7017401012475874"
             data-ad-slot="8493816565"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';
    } elseif ($catid==5) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьи1С */
        google_ad_slot = '8985178717';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
            src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
        $ret = '<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             style="display:inline-block;width:728px;height:90px"
             data-ad-client="ca-pub-7017401012475874"
             data-ad-slot="5540350160"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';
    } elseif ($catid==6) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиFreelance */
        google_ad_slot = '7590805840';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
    } elseif ($catid==26) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиDelphi */
        google_ad_slot = '5385286167';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
    } elseif ($catid==27) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиОборудование */
        google_ad_slot = '0089510970';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
    } elseif ($catid==30) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиКазахский */
        google_ad_slot = '2642208314';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
        $ret = '<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             style="display:inline-block;width:728px;height:90px"
             data-ad-client="ca-pub-7017401012475874"
             data-ad-slot="3924016166"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';
    } elseif ($catid==31) 
    {
        $ret = "
        <script type='text/javascript'><!--
        google_ad_client = 'ca-pub-7017401012475874';
        /* ГлавВерхДосУмолСтатьиПутешествия */
        google_ad_slot = '1150321442';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
        src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
    } else {
        $ret = "
        <script type='text/javascript'><!--ГлавВерхДосУмол
        google_ad_client = 'pub-7017401012475874';
        /* 468x60, sm создано 28.06.10 */
        google_ad_slot = '6580654287';
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type='text/javascript'
            src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
        </script>
        ";
        /* ГлавВерхПолнБан 728x90 03.07.2013 */
        $ret = '<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <ins class="adsbygoogle"
             style="display:inline-block;width:728px;height:90px"
             data-ad-client="ca-pub-7017401012475874"
             data-ad-slot="5040822566"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        ';
}
$adv = "<div class='adv_div' align='center'>
        <a href='http://www.softmaker.kz/mail/feedback.php' target='_blank'>
        <img width='728' height='90' title='Реклама на сайте www.softmaker.kz'
        alt='Размещение баннеров рекламы на сайте www.softmaker.kz' src='http://www.softmaker.kz/img/Adv/EmptyAdv.png' >
        </a></div>";
return "<div class='adv_div' align='center'>".$ret.'</div>';
} // retArticlesTopPosition

// Функция предназначена для получения блока рекламы левого блока статей
function ArticlesLeftPosition($catid)
{   
    echo "<p align='center'>";
    if ($catid==1) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиHTML */
        google_ad_slot = "3852156920";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==2) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиPHP */
        google_ad_slot = "9639173483";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==5) // статьи по 1С
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьи1С */
        google_ad_slot = "3247866291";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==6) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиFreelance */
        google_ad_slot = "9492497094";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==26) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиDelphi */
        google_ad_slot = "9898902130";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==27) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиОборудование */
        google_ad_slot = "0749138668";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==30) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиКазахский */
        google_ad_slot = "5545231330";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==31) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавЛевНСУмолСтатьиПутешествия */
        google_ad_slot = "1993337394";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } else {
        ?>
        <script type="text/javascript"><!--ГлавЛевНСУмол
        google_ad_client = "pub-7017401012475874";
        /* softmaker.kz, создано 29.03.09 */
        google_ad_slot = "0383771297"; 
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    }
echo "<p>";
}

// Функция предназначена для получения блока рекламы правого блока статей
function ArticlesRightPosition($catid)
{   
    if ($catid==1) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиHTML */
        google_ad_slot = "9194722373";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==2) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиPHP */
        google_ad_slot = "5413015463";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==5) // статьи по 1С
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьи1С */
        google_ad_slot = "6090776703";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==6) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиFreelance */
        google_ad_slot = "6395235987";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==26) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиDelphi */
        google_ad_slot = "3527588335";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==27) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиОборудование */
        google_ad_slot = "6377734546";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==30) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиКазахский */
        google_ad_slot = "8459825351";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==31) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* ГлавПравНСУмолСтатьиПутешествия */
        google_ad_slot = "4896594679";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } else {
        ?>
        <script type="text/javascript"><!--ГлавПравНСУмол
        google_ad_client = "pub-7017401012475874";
        /* 120x600, sm НебоСкрёб создано 29.06.10 */
        google_ad_slot = "8820541340";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    }
}

// Функция предназначена для получения блока ссылок в категориях
function ArticlesBottomPosition($catid)
{   
    echo "<div id='left'>";
    if ($catid==1) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииHTML */
        google_ad_slot = "7448437791";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==2) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииPHP */
        google_ad_slot = "7068341538";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==5) // статьи по 1С
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегории1С */
        google_ad_slot = "3595919422";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==6) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииFreelance */
        google_ad_slot = "2955554753";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==26) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииDelphi */
        google_ad_slot = "3029041206";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==27) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииОборудование */
        google_ad_slot = "7155391391";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==30) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииКазахский */
        google_ad_slot = "1924514570";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==31) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегорииПутешествия */
        google_ad_slot = "8140627308";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } else {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокСсылокКатегории */
        google_ad_slot = "8274040911";
        google_ad_width = 468;
        google_ad_height = 15;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    }
    echo "</div>";
}
// Функция предназначена для получения блока ссылок рядом с формой комментария для статей
function ArticlesBottomPositionComm($catid)
{   
    echo "<div id='right'>";
    if ($catid==1) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияHTML */
        google_ad_slot = "1350987728";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==2) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияPHP */
        google_ad_slot = "9323487446";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==5) {// статьи по 1C
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментария1C */
        google_ad_slot = "6307552786";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==6) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияFreelance */
        google_ad_slot = "6828957577";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==26) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияDelphi */
        google_ad_slot = "6982086081";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==27) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияОборудование */
        google_ad_slot = "5356417195";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==30) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияКазахский */
        google_ad_slot = "0123273869";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } elseif ($catid==31) 
    {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментарияПутешествия */
        google_ad_slot = "0827913327";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    } else {
        ?>
        <script type="text/javascript"><!--
        google_ad_client = "ca-pub-7017401012475874";
        /* БлокРядомСФормойКомментария */
        google_ad_slot = "2758052633";
        google_ad_width = 200;
        google_ad_height = 90;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
        <?
    }
    echo "</div>";
}

/*--------------------------------------------------------------------
# Определяем пренадлежность браузера к мобильным устройствам
# Возвращает false - если браузер не определен или стационарный
# и от 1 до 4 (зависит от типа определения) - если браузер относится к мобильным устройствам
--------------------------------------------------------------------*/
function is_mobile() {
  $user_agent=strtolower(getenv('HTTP_USER_AGENT'));
  $accept=strtolower(getenv('HTTP_ACCEPT'));
 
  if ((strpos($accept,'text/vnd.wap.wml')!==false) ||
      (strpos($accept,'application/vnd.wap.xhtml+xml')!==false)) {
    return 1; // Возращает 1 если мобильный браузер определен по HTTP-заголовкам
  }
 
  if (isset($_SERVER['HTTP_X_WAP_PROFILE']) ||
      isset($_SERVER['HTTP_PROFILE'])) {
    return 2; // Возвращает 2 если мобильный браузер определен по установкам сервера
  }
 
  if (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|'.
    'wireless| mobi|lg380|ahong|lgku|lgu900|lg210|lg47|lg920|lg840|'.
    'lg370|sam-r|mg50|s55|g83|mk99|vx400|t66|d615|d763|sl900|el370|'.
    'mp500|samu4|samu3|vx10|xda_|samu6|samu5|samu7|samu9|a615|b832|'.
    'm881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|'.
    'r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|'.
    'i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|'.
    'htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|'.
    'sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|'.
    'p404i|s210|c5100|s940|teleca|c500|s590|foma|vx8|samsu|vx9|a1000|'.
    '_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|'.
    's800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|'.
    'd736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |'.
    'sonyericsson|samsung|nokia|240x|x320vx10|sony cmd|motorola|'.
    'up.browser|up.link|mmp|symbian|android|tablet|iphone|ipad|mobile|smartphone|j2me|wap|vodafone|o2|'.
    'pocket|kindle|mobile|psp|treo)/', $user_agent)) {
    return 3; // Возвращает 3 если мобильный браузер определен по сигнатуре User Agent
  }
 
  if (in_array(substr($user_agent,0,4),
    Array("1207", "3gso", "4thp", "501i", "502i", "503i", "504i", "505i", "506i",
          "6310", "6590", "770s", "802s", "a wa", "abac", "acer", "acoo", "acs-",
          "aiko", "airn", "alav", "alco", "alca", "amoi", "anex", "anyw", "anny",
          "aptu", "arch", "asus", "aste", "argo", "attw", "au-m", "audi", "aur ",
          "aus ", "avan", "beck", "bell", "benq", "bilb", "bird", "blac", "blaz",
          "brew", "brvw", "bumb", "bw-n", "bw-u", "c55/", "capi", "ccwa", "cdm-",
          "cell", "chtm", "cldc", "cmd-", "dmob", "cond", "craw", "dait", "dall", "dang",
          "dbte", "dc-s", "devi", "dica", "doco", "dopo", "ds-d", "ds12",
          "el49", "elai", "eml2", "emul", "eric", "erk0", "esl8", "ez40", "ez60",
          "ez70", "ezos", "ezwa", "ezze", "fake", "fetc", "fly-", "fly_", "g-mo",
          "g1 u", "g560", "gene", "gf-5", "go.w", "good", "grad", "grun", "haie",
          "hcit", "hd-m", "hd-p", "hd-t", "hei-", "hiba", "hipt", "hita", "hp i",
          "hpip", "hs-c", "htc ", "htc-", "htc_", "htca", "htcg", "htcp", "htcs",
          "htct", "http", "hutc", "huaw", "i-20", "i-go", "i-ma", "i230", "iac",
          "iac-", "iac/", "ibro", "idea", "ig01", "ikom", "im1k", "inno", "ipaq",
          "iris", "jata", "java", "jbro", "jemu", "jigs", "kddi", "keji", "kgt",
          "kgt/", "klon", "kpt ", "kwc-", "kyoc", "kyok", "leno", "lexi", "lg g",
          "lg-a", "lg-b", "lg-c", "lg-d", "lg-f", "lg-g", "lg-k", "lg-l", "lg-m",
          "lg-o", "lg-p", "lg-s", "lg-t", "lg-u", "lg-w", "lg/k", "lg/l", "lg/u",
          "lg50", "lg54", "lge-", "lge/", "libw", "lynx", "m-cr", "m1-w", "m3ga",
          "m50/", "mate", "maui", "maxo", "mc01", "mc21", "mcca", "medi", "merc",
          "meri", "midp", "mio8", "mioa", "mits", "mmef", "mo01", "mo02", "mobi",
          "mode", "modo", "mot ", "mot-", "moto", "motv", "mozz", "mt50", "mtp1",
          "mtv ", "mwbp", "mywa", "n100", "n101", "n102", "n202", "n203", "n300",
          "n302", "n500", "n502", "n505", "n700", "n701", "n710", "nec-", "nem-",
          "neon", "netf", "newg", "newt", "nok6", "noki", "nzph", "o2 x", "o2-x",
          "o2im", "opti", "opwv", "oran", "owg1", "p800", "palm", "pana", "pand",
          "pant", "pdxg", "pg-1", "pg-2", "pg-3", "pg-6", "pg-8", "pg-c", "pg13",
          "phil", "pire", "play", "pluc", "pn-2", "pock", "port", "pose", "prox",
          "psio", "pt-g", "qa-a", "qc-2", "qc-3", "qc-5", "qc-7", "qc07", "qc12",
          "qc21", "qc32", "qc60", "qci-", "qtek", "qwap", "r380", "r600", "raks",
          "rim9", "rove", "rozo", "s55/", "sage", "sama", "sams", "samm", "sany",
          "sava", "sc01", "sch-", "scoo", "scp-", "sdk/", "se47", "sec-", "sec0",
          "sec1", "semc", "send", "seri", "sgh-", "shar", "sie-", "siem", "sk-0",
          "sl45", "slid", "smal", "smar", "smb3", "smit", "smt5", "soft", "sony",
          "sp01", "sph-", "spv ", "spv-", "sy01", "symb", "t-mo", "t218", "t250",
          "t600", "t610", "t618", "tagt", "talk", "tcl-", "tdg-", "teli", "telm",
          "tim-", "topl", "treo", "tosh", "ts70", "tsm-", "tsm3", "tsm5", "tx-9",
          "up.b", "upg1", "upsi", "utst", "v400", "v750", "veri", "virg", "vite",
          "vk-v", "vk40", "vk50", "vk53", "vk52", "vm40", "vulc", "voda", "vx52",
          "vx53", "vx60", "vx61", "vx70", "vx80", "vx81", "vx83", "vx85", "vx98",
          "w3c ", "w3c-", "wap-", "wapa", "wapi", "wapj", "wapp", "wapm", "wapr",
          "waps", "wapt", "wapu", "wapv", "wapy", "webc", "whit", "wig ", "winc",
          "winw", "wmlb", "wonu", "x700", "xda-", "xdag", "xda2", "yas-", "your",
          "zeto", "zte-"))) {
    return 4; // Возвращает 4 если мобильный браузер определен по сигнатуре User Agent
  }
 
  return false; // Возвращает false если мобильный браузер не определен или браузер стационарный
}
////////////////////////////
