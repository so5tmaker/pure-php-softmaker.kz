<?php //перенос данных в вордпресс
//Здесь я проверяю путь к файлу исполняемого в данный момент скрипта,
//чтобы определить какую базу мне нужно локальную или удаленную
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');
// хочу найти домашнюю папку локального сайта
$PHP_SELF = filter_input(INPUT_SERVER, 'PHP_SELF');
$cut_slash = substr($PHP_SELF, 1); // отсекаю первый слэш
$pos = strpos($cut_slash, "/"); // нахожу позицию первого вхождения символа "/" ."/" .'/'
$rest = substr($cut_slash, 0, $pos); // возвращает, например "phpbloguser"
$rest1 = ($rest=="") ? $rest : "/".$rest;
$rest_ = ($rus !== false) ? $rest1 : '' ;
// получаю полный корневой путь
$DIR   = $ROOT.$rest_.'/';

if ($rus !== false){
    $url = "http://".$HOST."/".$rest."/";
    $url_wp = "http://localhost/tili.kz/";
    $path_wp = "../../tili.kz/wp-content/uploads/softmaker/";
}else{
    $url = 'http://'.$HOST.'/';
    $url_wp = "http://tili.softmaker.kz/";
    $path_wp = "../../tili.softmaker.kz";
}

if ($rus !== false)
{
    /** Имя базы данных для WordPress */
    define('DB_NAME_wp', 'tili');

    /** Имя пользователя MySQL */
    define('DB_USER_wp', 'bloguser');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD_wp', '12345');

    /** Имя сервера MySQL */
    define('DB_HOST_wp', 'localhost');

    // ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
    /** Имя базы данных для проекта */
    define('DB_NAME', 'phpblog');

    /** Имя пользователя MySQL */
    define('DB_USER', 'bloguser');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD', '12345');

    /** Имя сервера MySQL */
    define('DB_HOST', 'localhost');
}
else
{
    
    /** Имя базы данных для WordPress */
    define('DB_NAME_wp', 'db1088065_tili');

    /** Имя пользователя MySQL */
    define('DB_USER_wp', 'u1088065_tili');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD_wp', '5@oce^DQ2]');

    /** Имя сервера MySQL */
    define('DB_HOST_wp', 'mysql389.cp.idhost.kz');

    
    /** Имя базы данных для проекта */
    define('DB_NAME', 'db1088065_db');

    /** Имя пользователя MySQL */
    define('DB_USER', 'u1088065_root');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD', ']budetr');

    /** Имя сервера MySQL */
    define('DB_HOST', 'mysql677.cp.idhost.kz');

}

function connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME) {
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

    if ($mysqli->connect_errno) { 
       printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", $mysqli->error); 
       return $mysqli;
    } 
    $mysqli->query("SET NAMES 'cp1251'");
    return $mysqli;
}

// Функция предназначена для получения максимального id
function get_max_id($mysqli, $tbl_dt, $id_clmn)
{   // отсортируем по убываниюSELECT MAX(`term_id`) AS id FROM `wp_terms`
    $sql = "SELECT MAX(`$id_clmn`) AS id FROM $tbl_dt";    
    
    if ($result = $mysqli->query($sql)) { 

        /* Выбираем результаты запроса: */ 
        while( $row = $result->fetch_assoc() ){ 
            return $row[id]; 
        } 

        /* Освобождаем память */ 
        $result->close(); 
    } 
    return 1;
}

// Функция предназначена для получения максимального id
function get_img_path($name)
{   
    $distination = '../img/thumb/temp/'.$img.'.png';
    $source      = '../img/thumb/temp/'.$name.'.png';
    if (file_exists($source)){
//        copy($source, $distination) 
        return "$img.png";
    }
    return 'default.png';
}

// Функция предназначена для удаления последнего символа ","
function rem_last($text) {
    $string = substr($text, strlen($text)-1);
    if ($string == ",") {
        return substr($text, 0, -1); // возвращаю строку без запятой
    }
    return $text;
}

$mysqli    = connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli_wp = connect(DB_HOST_wp, DB_USER_wp, DB_PASSWORD_wp, DB_NAME_wp);

if ($mysqli->connect_errno == 0 OR $mysqli_wp->connect_errno == 0) { 
//   printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", $mysqli->error); 
//   exit; 
    /* Переносим категории */ 
    $sql = 'SELECT `id`, `parent`, `id_lang`, `name`, '
            . '`sec`, `title`, `meta_d`, `meta_k`, '
            . '`text`, `file`, `cat_tbl`, `lang` '
            . 'FROM `categories` WHERE `id` IN (6, 17, 19, 20)';
    if ($result = $mysqli->query($sql)) { 
        $num = $result->num_rows;
        $sql_ct = 'INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES ';
        $sql_tx = 'INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, '
                . '`description`, `parent`, `count`) VALUES ';
        $acat = array();
        $i = 1;
        $maxid = get_max_id($mysqli_wp, 'wp_terms', 'term_id');
        $maxtx = get_max_id($mysqli_wp, 'wp_term_taxonomy', 'term_taxonomy_id');
        while( $row = $result->fetch_assoc() ){ 
            $s = ($num == $i)? '':',';
            $id = $maxid + $i;
            $ix = $maxtx + $i;
            $acat[$row['id']] = $id; // синхронизация категорий
            $cat_in .= $row['id'].$s;
            $sql_ct .= "($id, '$row[title]', '$row[name]', 0)$s";
            $sql_tx .= "($ix, $id, 'category', '', 0, 1)$s";
            $i += 1;
        } 
        
        if (!($result_wp = $mysqli_wp->query(rem_last($sql_ct)))) {
            printf("Ошибка добавления категорий. Код ошибки: %s\n", $mysqli_wp->error); 
        } 
        
        if (!($result_tx = $mysqli_wp->query(rem_last($sql_tx)))) {
            printf("Ошибка добавления таксономий категорий. Код ошибки: %s\n", $mysqli_wp->error); 
        }
    } 
    
    $cat_in = "WHERE cat IN ($cat_in)";
     
    /* Переносим статьи */ 
    $sql = 'SELECT `id`, `id_lang`, `name`, `cat`, `new_cat`, '
            . '`meta_d`, `meta_k`, `description`, `text`, '
            . '`view`, `author`, `date`, `lang`, `mini_img`, '
            . '`title`, `secret`, `rating`, `q_vote`, `file`, '
            . '`faq`, `phpcolor`, `notprohibit`, `blog_id`, '
            . '`price` FROM `data` '.$cat_in;
    if ($result = $mysqli->query($sql)) {
        $num = $result->num_rows;
        $sql_wp = 'INSERT INTO `wp_posts`(`ID`, `post_author`, `post_date`, '
                . '`post_date_gmt`, `post_content`, `post_title`, '
                . '`post_excerpt`, `post_status`, `comment_status`, '
                . '`ping_status`, `post_password`, `post_name`, '
                . '`to_ping`, `pinged`, `post_modified`, '
                . '`post_modified_gmt`, `post_content_filtered`, '
                . '`post_parent`, `guid`, `menu_order`, `post_type`, '
                . '`post_mime_type`, `comment_count`) VALUES ';
        $sql_mt = "INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES ";
        $sql_rl = "INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ";
        $i = 1;
        $im = 1;
        $maxid = get_max_id($mysqli_wp, 'wp_posts', 'ID');
        $maxmt = get_max_id($mysqli_wp, 'wp_postmeta', 'meta_id');
        while( $row = $result->fetch_assoc() ){ 
            $s = ($num == $i)? '':',';
            $id = $maxid + $i;
            $idm = $maxmt + $im;
            $id_img = $id + 1;
            $date = date("Y-m-d H:i:s");
            
            $text = str_replace('http://www.softmaker.kz/', $url_wp, $row[text]);   
            $text = str_replace('/articles', '', $text); 
            $text = str_replace('/files', '', $text);
            $text = str_replace('<!--more-->', '', $text);
//            $text = addslashes($text);
            
            $file = "$row[name].png";
            $path = "wp-content/uploads/softmaker/$file";
            
            $sql_wp .= "($id, 1, '$date', '$date', '$text', '$row[title]', "
                . "'$row[description]', 'publish', 'open', 'closed', '', '$row[name]', '', '', "
                . "'$date', '$date', '', "
                . "0, '$url_wp?p=$id', 0, 'post', '', 0),"
                . "($id_img, 1, '$date', '$date', '', "
                . "'$row[name]', '', 'inherit', 'open', 'closed', '', '$row[name]', "
                . "'', '', '$date', '$date',"
                . "'', $id, '$url_wp"."$path', "
                . "0, 'attachment', 'image/png', 0)$s";
            
            $cat = $acat[$row[cat]];
            $sql_rl .= "($id, $cat, 0)$s";
            
            $sql_mt .= "(".($idm).", $id, 'metabox_posts_header_img', "
            . "'a:5:{s:3:\"url\";s:0:\"\";s:2:\"id\";"
            . "s:0:\"\";s:6:\"height\";s:0:\"\";"
            . "s:5:\"width\";s:0:\"\";s:9:\"thumbnail\";"
            . "s:0:\"\";}'),"
            . "(".($idm+1).", $id, '_thumbnail_id', '$id_img'),"
            . "(".($idm+2).", $id, '_aioseop_keywords', '$row[meta_k]'),"
            . "(".($idm+3).", $id, '_aioseop_description', '$row[meta_d]'),"
            . "(".($idm+4).", $id, '_aioseop_title', '$row[title]'),"
            . "(".($idm+5).", $id, '_aioseop_custom_link', '$row[name].html'),"
            . "(".($idm+6).", $id, '_wp_attached_file', '$path'),"
            . "(".($idm+7).", $id, '_wp_attachment_metadata', "
            . "'a:5:{s:5:\"width\";i:192;s:6:\"height\";i:256;s:4:\"file\";"
            . "s:17:\"softmaker/$file\";s:5:\"sizes\";a:2:{s:9:\"thumbnail\";a:"
            . "4:{s:4:\"file\";s:17:\"$file\";s:5:\"width\";i:150;s:6:\"height\";"
            . "i:150;s:9:\"mime-type\";s:10:\"image/png\";}"
            . "s:20:\"event-post-thumb-box\";a:4:{s:4:\"file\";s:17:\"$file\";"
            . "s:5:\"width\";i:192;s:6:\"height\";i:240;s:9:\"mime-type\";"
            . "s:10:\"image/png\";}}s:10:\"image_meta\";"
            . "a:11:{s:8:\"aperture\";i:0;s:6:\"credit\";s:0:\"\";"
            . "s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";"
            . "s:17:\"created_timestamp\";i:0;s:9:\"copyright\";"
            . "s:0:\"\";s:12:\"focal_length\";i:0;s:3:\"iso\";i:0;"
            . "s:13:\"shutter_speed\";i:0;s:5:\"title\";s:0:\"\";"
            . "s:11:\"orientation\";i:0;}}')$s";
            $r = "\"";
            $i += 2;
            $im+= 8;
            break;
        } 
        
        
        
//        echo $sql_wp."<br>";
//        
//        echo rem_last($sql_rl)."<br>";
//        
//        echo rem_last($sql_mt)."<br>";
        
//          echo rem_last($sql_wp);
        
        if (!($result_wp = $mysqli_wp->query(rem_last($sql_wp)))) {
            printf("Ошибка добавления записей. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
        } 
        
        if (!($result_wp = $mysqli_wp->query(rem_last($sql_rl)))) {
            printf("Ошибка добавления связей записей и категорий. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
        } 
        
        if (!($result_wp = $mysqli_wp->query(rem_last($sql_mt)))) {
            printf("Ошибка добавления метаданный записей. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
        } 
    }
//    $k=1;
    /* Закрываем соединение */ 
    $mysqli->close(); 
    $mysqli_wp->close(); 

} 



/* http://localhost/tili.kz/wp-content/uploads/softmaker/padezhi-kazaxskogo-yazyka.png
 * http://localhost/tili.kz/wp-content/uploads/2015/09/Vitya.jpg

SELECT * FROM `wp_posts` WHERE post_type='post' and post_status='publish'

INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(1, 'Казахский язык', 'kazaxskij-yazyk', 0),
(2, 'Главная страница', 'glavnaya-stranica', 0); 

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(1, 1, 'category', '', 0, 1)
 
INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(20, 1, '2015-09-04 16:47:41', '2015-09-04 10:47:41', '', 'test', '', 'publish', 'open', 'closed', '', 'test', '', '', '2015-09-07 08:31:41', '2015-09-07 02:31:41', '', 0, 'http://localhost/tili.kz/?p=20', 0, 'post', '', 0),
(25, 1, '2015-09-07 08:31:28', '2015-09-07 02:31:28', '', 'Vitya', '', 'inherit', 'open', 'closed', '', 'vitya', '', '', '2015-09-07 08:31:28', '2015-09-07 02:31:28', '', 20, 'http://localhost/tili.kz/wp-content/uploads/2015/09/Vitya.jpg', 0, 'attachment', 'image/jpeg', 0);

INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
(51, 20, 'metabox_posts_header_img', 'a:5:{s:3:"url";s:0:"";s:2:"id";s:0:"";s:6:"height";s:0:"";s:5:"width";s:0:"";s:9:"thumbnail";s:0:"";}'),
(77, 20, '_thumbnail_id', '25'),
(88, 20, '_aioseop_keywords', 'каз, tili'),
(89, 20, '_aioseop_description', 'Большинство поисковых систем видят лишь 160 символов.'),
(90, 20, '_aioseop_title', 'test 60 символ'),
(91, 20, '_aioseop_custom_link', 'test.html');
 
INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES
(1, 1, 0),
(10, 1, 0),
(20, 1, 0),
(24, 2, 0),
(30, 1, 0);
(1, 1, 0),
(10, 1, 0),
(20, 1, 0),
(24, 2, 0),
(30, 1, 0);
 * 
 SELECT * FROM `wp_postmeta` WHERE post_id=33 OR post_id=34 ORDER BY `post_id` ASC
 a:5:{s:5:"width";i:192;s:6:"height";i:256;s:4:"file";s:17:"2015/09/Vitya.jpg";
 * s:5:"sizes";a:2:{s:9:"thumbnail";a:4:{s:4:"file";s:17:"Vitya-150x150.jpg";
 * s:5:"width";i:150;s:6:"height";i:150;s:9:"mime-type";s:10:"image/jpeg";}
 * s:20:"event-post-thumb-box";a:4:{s:4:"file";s:17:"Vitya-192x240.jpg";
 * s:5:"width";i:192;s:6:"height";i:240;s:9:"mime-type";s:10:"image/jpeg";}}
 * s:10:"image_meta";a:11:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";
 * s:0:"";s:7:"caption";s:0:"";s:17:"created_timestamp";i:0;s:9:"copyright";
 * s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:0;s:13:"shutter_speed";i:0;
 * s:5:"title";s:0:"";s:11:"orientation";i:0;}}

 * 
 DELETE FROM `tili`.`wp_posts` WHERE `wp_posts`.`ID` = 33;
 DELETE FROM `tili`.`wp_posts` WHERE `wp_posts`.`ID` = 34;
 DELETE FROM `tili`.`wp_postmeta` WHERE `wp_postmeta`.`meta_id` = 192;
DELETE FROM `tili`.`wp_postmeta` WHERE `wp_postmeta`.`meta_id` = 193;
DELETE FROM `tili`.`wp_postmeta` WHERE `wp_postmeta`.`meta_id` = 194;
DELETE FROM `tili`.`wp_postmeta` WHERE `wp_postmeta`.`meta_id` = 195;
DELETE FROM `tili`.`wp_postmeta` WHERE `wp_postmeta`.`meta_id` = 196;
DELETE FROM `tili`.`wp_postmeta` WHERE `wp_postmeta`.`meta_id` = 197;
DELETE FROM `tili`.`wp_terms` WHERE `wp_terms`.`term_id` = 3;
DELETE FROM `tili`.`wp_terms` WHERE `wp_terms`.`term_id` = 4;
DELETE FROM `tili`.`wp_terms` WHERE `wp_terms`.`term_id` = 5;
DELETE FROM `tili`.`wp_terms` WHERE `wp_terms`.`term_id` = 6;
DELETE FROM `tili`.`wp_term_relationships` WHERE `wp_term_relationships`.`object_id` = 33 AND `wp_term_relationships`.`term_taxonomy_id` = 4;
DELETE FROM `tili`.`wp_term_taxonomy` WHERE `wp_term_taxonomy`.`term_taxonomy_id` = 3;
DELETE FROM `tili`.`wp_term_taxonomy` WHERE `wp_term_taxonomy`.`term_taxonomy_id` = 4;
DELETE FROM `tili`.`wp_term_taxonomy` WHERE `wp_term_taxonomy`.`term_taxonomy_id` = 5;
DELETE FROM `tili`.`wp_term_taxonomy` WHERE `wp_term_taxonomy`.`term_taxonomy_id` = 6;
 */

