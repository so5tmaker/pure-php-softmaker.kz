<?php //перенос данных в вордпресс
//Здесь я проверяю путь к файлу исполняемого в данный момент скрипта,
//чтобы определить какую базу мне нужно локальную или удаленную
$HOST = filter_input(INPUT_SERVER, 'HTTP_HOST'); // $_SERVER['HTTP_HOST']
$rus = strstr($HOST, 'localhost'); //$rus = strstr($SERVER, 'localhost') OR strstr($SERVER, 'sites');

//    $cat_in = "6,17,19,20"; // массив категорий из базы sm для tili
    $cat_in = "5,7,12,13,14,15,16,23,24,25,29,32"; // массив категорий из базы sm для 1С

if ($rus !== false){
    $url = "http://".$HOST."/softmaker.kz/";
    $url_wp = "http://localhost/softmaker.wp/";
}else{
    $url = 'http://www.softmaker.kz/';
    $url_wp = "http://www.softmaker.kz/";
}

if ($rus !== false)
{
    /** Имя базы данных для WordPress */
    define('DB_NAME_wp', 'db1088065_wp');

    /** Имя пользователя MySQL */
    define('DB_USER_wp', 'u1088065_wp');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD_wp', '{0fR&yK(BW');

    /** Имя сервера MySQL */
    define('DB_HOST_wp', 'localhost');

    /** Имя базы данных для проекта */
    define('DB_NAME_sm', 'phpblog');

    /** Имя пользователя MySQL */
    define('DB_USER_sm', 'bloguser');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD_sm', '12345');

    /** Имя сервера MySQL */
    define('DB_HOST_sm', 'localhost');
}
else
{
    
    /** Имя базы данных для WordPress */
    define('DB_NAME_wp', 'db1088065_wp');

    /** Имя пользователя MySQL */
    define('DB_USER_wp', 'u1088065_wp');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD_wp', '{0fR&yK(BW');

    /** Имя сервера MySQL */
    define('DB_HOST_wp', 'mysql39.cp.idhost.kz');

    
    /** Имя базы данных для проекта */
    define('DB_NAME_sm', 'db1088065_db');

    /** Имя пользователя MySQL */
    define('DB_USER_sm', 'u1088065_root');

    /** Пароль к базе данных MySQL */
    define('DB_PASSWORD_sm', ']budetr');

    /** Имя сервера MySQL */
    define('DB_HOST_sm', 'mysql677.cp.idhost.kz');

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

// Функция предназначена для переноса категорий
function move_cat($mysqli, $mysqli_wp, $cat_in) {
    global $cat_in;
//    $cat_in = "6,17,19,20"; // массив категорий из базы sm для tili
    $cat_in = "5,7,12,13,14,15,16,23,24,25,29,32"; // массив категорий из базы sm для 1С
    $posts  = explode(",", $cat_in);
    $acat = array();
    // заполняю массив категорий
    foreach ($posts as $value) {
        $acat[$value] = -1;
    }
    // нахожу уже созданные категории
    $query = "SELECT term_id, sm FROM `wp_terms` WHERE sm IN ($cat_in)";
    if ($res_wp = $mysqli_wp->query($query)) {
        while( $row_wp = $res_wp->fetch_array() ){
            $acat[$row_wp[sm]] = $row_wp[term_id];
        }
    }
    $new_posts = array();
    // формирую строку оставшихся категорий
    foreach ($acat as $key => $value) {
        if ($value == -1) {
            $new_posts[] = $key;
        }
    }
    if (count($new_posts) > 0) {
        $cat_new_in = implode(",", $new_posts);
        /* Переносим категории */ 
        $sql = "SELECT `id`, `parent`, `id_lang`, `name`, "
                . "`sec`, `title`, `meta_d`, `meta_k`, "
                . "`text`, `file`, `cat_tbl`, `lang` "
                . "FROM `categories` WHERE `id` IN ($cat_new_in)";
        if ($result = $mysqli->query($sql)) { 
            $num = $result->num_rows;
            $sql_ct = 'INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `sm`) VALUES ';
            $sql_tx = 'INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, '
                    . '`description`, `parent`, `count`) VALUES ';
            $i = 1;
            $maxid = get_max_id($mysqli_wp, 'wp_terms', 'term_id');
            $maxtx = get_max_id($mysqli_wp, 'wp_term_taxonomy', 'term_taxonomy_id');
            while( $row = $result->fetch_assoc() ){ 
                $s = ($num == $i)? '':',';
                $id = $maxid + $i;
                $ix = $maxtx + $i;
                $acat[$row['id']] = $id; // синхронизация категорий
//                $cat_in .= $row['id'].$s;
                $sql_ct .= "($id, '$row[title]', '$row[name]', 0, $row[id])$s";
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
    }
//    $cat_in = implode(",", $acat);
    $cat_in = "WHERE cat IN ($cat_in)"; // массив категорий из базы sm
    return $acat;
}

function rem_text($param, $url_wp) {
    $text = str_replace('../../files', 'http://www.softmaker.kz/files', $param);
    $text = str_replace('../files', 'http://www.softmaker.kz/files', $text);
    $text = str_replace('../../', 'http://www.softmaker.kz/', $text);
    $text = str_replace('../', 'http://www.softmaker.kz/', $text);   
    $text = str_replace('/articles', '', $text); 
//    $text = str_replace('/files', '', $text);
    $text = str_replace('<!--more-->', '<!--softmaker-->', $text);
//    $text = str_replace('http://www.softmaker.kz/', '../', $text);
    $text = str_replace('<TABLE border=0 cellSpacing=0 cellPadding=0 width=590 align=center>', '<table>', $text);
    $text = str_replace('<table width=\"590\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" >', '<table>', $text);
    //            $text = addslashes($text);  
    return $text;
}

function rem_description($param) {
    $text = str_replace('<p>', '', $param);
    $text = str_replace('</p>', '', $param); 
    return $text;
}

function get_tax_by_id($id) {
    global $mysqli_wp;
    $query = "SELECT term_taxonomy_id FROM `wp_term_taxonomy` WHERE term_id=$id AND taxonomy = 'category'";
    if ($res_wp = $mysqli_wp->query($query)) {
        while( $row_wp = $res_wp->fetch_array() ){
            return $row_wp[term_taxonomy_id];
        }
    } else {
        $max = get_max_id($mysqli_wp, 'wp_term_taxonomy', 'term_taxonomy_id') + 1;
        $query = "INSERT INTO `wp_term_taxonomy`(`term_taxonomy_id`, `term_id`, `taxonomy`, "
        . "`description`, `parent`, `count`) VALUES ($max,$id,'category','',0,1)";
        if ($res_wp = $mysqli_wp->query($query)) {
            while( $row_wp = $res_wp->fetch_array() ){
                return $max;
            }
        } else {
            printf("Ошибка добавления таксаномий в таблицу wp_term_taxonomy. Код ошибки: %s\n", $mysqli_wp->error."<br>");
        }
    }
}

function create_relations($object_id, $term_taxonomy_id) {
    global $mysqli_wp;
    $not = TRUE;
    $query = "SELECT * FROM `wp_term_relationships` WHERE object_id=$object_id AND term_taxonomy_id = $term_taxonomy_id";
    if ($res_wp = $mysqli_wp->query($query)) {
        while( $row_wp = $res_wp->fetch_array() ){
            $not = FALSE;
        }
    } 
    if ($not) {
        $query = "INSERT INTO `wp_term_relationships`(`object_id`, "
        . "`term_taxonomy_id`, `term_order`) VALUES ($object_id, $term_taxonomy_id,0)";
        if (!($result_wp = $mysqli_wp->query($query))) {
            printf("Ошибка добавления связей в таблицу wp_term_relationships. Код ошибки: %s\n", $mysqli_wp->error."<br>");
        }
    }
}

function create_postmeta($post_id, $keywords, $description, $title, $custom_link) {
    global $mysqli_wp;
    $meta_keys = array(
                '_aioseop_keywords' => $keywords, 
                '_aioseop_description' => $description,
                '_aioseop_title' => $title, 
                '_aioseop_custom_link' => $custom_link);
    
    foreach ($meta_keys as $key => $value) {
        $not = TRUE;
        $query = "SELECT * FROM `wp_postmeta` WHERE post_id=$object_id AND meta_key = '$key'";
        if ($res_wp = $mysqli_wp->query($query)) {
            while( $row_wp = $res_wp->fetch_array() ){
                $not = FALSE;
            }
        } 
        if ($not) {
            $max = get_max_id($mysqli_wp, 'wp_postmeta', 'meta_id') + 1;
            $query = "INSERT INTO `wp_postmeta`(`meta_id`, `post_id`, `meta_key`, "
            . "`meta_value`) VALUES ($max,$post_id,'$key','$value')";
            if (!($result_wp = $mysqli_wp->query($query))) {
                printf("Ошибка добавления записей в таблицу wp_postmeta. Код ошибки: %s\n", $mysqli_wp->error."<br>");
            }
        }
    }
    
}

function post_exists($post_id) {
    global $mysqli_wp;
    $query = "SELECT ID FROM `wp_posts` WHERE post_type='post' and post_status='publish' and sm=$post_id";
    if ($res_wp = $mysqli_wp->query($query)) {
        while( $row_wp = $res_wp->fetch_array() ){
            return TRUE;
        }
    } 
    return FALSE;
}

function id_exists($post_id, $tbl) {
    global $mysqli_wp;
    $query = "SELECT ID FROM `$tbl` WHERE sm=$post_id";
    if ($res_wp = $mysqli_wp->query($query)) {
        while( $row_wp = $res_wp->fetch_array() ){
            return TRUE;
        }
    } 
    return FALSE;
}

$mode = filter_input(INPUT_GET, 'mode');
$mysqli = connect(DB_HOST_sm, DB_USER_sm, DB_PASSWORD_sm, DB_NAME_sm);
$mysqli_wp = connect(DB_HOST_wp, DB_USER_wp, DB_PASSWORD_wp, DB_NAME_wp);

if ($mysqli->connect_errno == 0 OR $mysqli_wp->connect_errno == 0) {
    
    $cat_in = '';
    $acat = move_cat($mysqli, $mysqli_wp);
    
    if ($mode == 'update') {
        
        $query = "SELECT ID, sm FROM `wp_posts` WHERE post_type='post' and post_status='publish'";
        $ids = array();
        $posts = array();
        if ($res_wp = $mysqli_wp->query($query)) {
            while( $row_wp = $res_wp->fetch_array() ){
                $posts[] = $row_wp[ID];
                $ids[] = $row_wp[sm]; 
            }
        }

        $sql = 'SELECT `id`, `id_lang`, `name`, `cat`, `new_cat`, '
                . '`meta_d`, `meta_k`, `description`, `text`, '
                . '`view`, `author`, `date`, `lang`, `mini_img`, '
                . '`title`, `secret`, `rating`, `q_vote`, `file`, '
                . '`faq`, `phpcolor`, `notprohibit`, `blog_id`, '
                . '`price` FROM `data` '.$cat_in;
        if ($result = $mysqli->query($sql)) {
            $i = 0;
            while( -array_sum($ids) < count($ids) ){
                $postid = $posts[$i];
                if ($postid == NULL){
                    break;
                }
                if ($ids[$i] == -1) {
                    $i += 1;
                    continue;
                }
                $row = $result->fetch_assoc();
                $key = array_search($row[id], $ids);
                $a = 0;
                if (false !== $key){
                    $postid = $posts[$key];
                    $ids[$key] = -1; // помечаю элемент
                    $a = -1;
                }
                $text = rem_text($row[text], $url_wp);
                $date = date("Y-m-d H:i:s");
                $qup = "UPDATE `wp_posts` SET "
                . "`post_author`='$row[author]',"
                . "`post_content`='$text',`post_title`='$row[title]',"
                . "`post_excerpt`='$row[description]', `post_name`='$row[name]',"
                . "`post_modified`='$date',`post_modified_gmt`='$date',"
                . " `sm`=$row[id] WHERE ID=$postid";
                if (!($result_wp = $mysqli_wp->query($qup))) {
                    printf("Ошибка обновления записей. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
                } else {
                    $i += 1 + $a;
                    create_relations($postid, get_tax_by_id($acat[$row[cat]]));
                    create_postmeta($postid, $row[meta_k], rem_description($row[meta_d]), $row[title], "$row[name].html");
                }
//                arsort($ids);
            }
        }
    } elseif ($mode == 'change_link') { // $mode == 'change_link'
        $query = "SELECT ID, sm, post_content FROM `wp_posts` WHERE post_type='post' and post_status='publish'";
        if ($res_wp = $mysqli_wp->query($query)) {
            while( $row_wp = $res_wp->fetch_array() ){
                $text = rem_text($row_wp[post_content], '../');
                $postid = $row_wp[ID];
                $qup = "UPDATE `wp_posts` SET "
                . "`post_content`='$text' WHERE ID=$postid";
                if (!($row_wp = $mysqli_wp->query($qup))) {
                    printf("Ошибка обновления записей. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
                }
            }
        }
    } 
    
    if ($mode == 'insert') { // $mode == 'insert'
  
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
                    . '`post_mime_type`, `comment_count`, `sm`) VALUES ';
            $sql_mt = "INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES ";
//            $sql_rl = "INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ";
            $i = 1;
            $im = 1;
            $maxid = get_max_id($mysqli_wp, 'wp_posts', 'ID');
            $maxmt = get_max_id($mysqli_wp, 'wp_postmeta', 'meta_id');
            while( $row = $result->fetch_assoc() ){ 
                if (post_exists($row[id]) ) {
                    continue;
                }
                $s = ($num == $i)? '':',';
                $id = $maxid + $i;
                $idm = $maxmt + $im;
//                $id_img = $id + 1;
                $date = date("Y-m-d H:i:s");
                
                $text = rem_text($row[text], $url_wp);

//                $file = "$row[name].png";
//                $path = "wp-content/uploads/softmaker/$file";

                $sql_wp .= "($id, 1, '$date', '$date', '$text', '$row[title]', "
                    . "'$row[description]', 'publish', 'open', 'closed', '', '$row[name]', '', '', "
                    . "'$date', '$date', '', "
                    . "0, '$url_wp?p=$id', 0, 'post', '', 0, $row[id]),"
                    . "(0, 1, '$date', '$date', '', "
                    . "'$row[name]', '', 'inherit', 'open', 'closed', '', '$row[name]', "
                    . "'', '', '$date', '$date',"
                    . "'', $id, '', "
                    . "0, 'attachment', 'image/png', 0, $row[id])$s";

//                $cat = $acat[$row[cat]];
//                $sql_rl .= "($id, $cat, 0)$s";
                create_relations($id, get_tax_by_id($acat[$row[cat]]));

                $sql_mt .= 
//                "(".($idm).", $id, 'metabox_posts_header_img', " 
//                . "'a:5:{s:3:\"url\";s:0:\"\";s:2:\"id\";"
//                . "s:0:\"\";s:6:\"height\";s:0:\"\";"
//                . "s:5:\"width\";s:0:\"\";s:9:\"thumbnail\";"
//                . "s:0:\"\";}'),"
//                . "(".($idm+1).", $id, '_thumbnail_id', '$id_img'),"
                  "(".($idm).", $id, '_aioseop_keywords', '$row[meta_k]'),"
                . "(".($idm+2).", $id, '_aioseop_description', '$row[meta_d]'),"
                . "(".($idm+3).", $id, '_aioseop_title', '$row[title]'),"
                . "(".($idm+4).", $id, '_aioseop_custom_link', '$row[name].html'),";
//                . "(".($idm+6).", $id, '_wp_attached_file', '$path')$s";
                $r = "\"";
                $i += 2;
                $im+= 5;
            } 

            if (!($result_wp = $mysqli_wp->query(rem_last($sql_wp)))) {
                printf("Ошибка добавления записей. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
            } 
            
//            if (!($result_wp = $mysqli_wp->query(rem_last($sql_rl)))) {
//                printf("Ошибка добавления связей записей и категорий. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
//            } 
//            echo $sql_mt;
            
//            if (!($result_wp = $mysqli_wp->query(rem_last($sql_mt)))) {
//                printf("Ошибка добавления метаданный записей. Код ошибки: %s\n", $mysqli_wp->error."<br>");    
//            } 
        }
    } 
    
    if ($mode == 'users' or $mode == 'comments'){
        $wp_table = 'wp_'.$mode;
        if ($mode == 'users'){
            $fields = array(
            "user_login" => "username",
            "user_nicename" => "username",
            "display_name" => "username",    
            "user_pass" => "password",
            "user_email" => "email",
            "user_registered" => "join_date"
            );
        }
        
        if ($mode == 'comments'){
            $fields = array(
            "comment_post_ID" => "post",
            "comment_author" => "author",
            "comment_content" => "text",
            "comment_date" => "date",
            "comment_date_gmt" => "date",
            "comment_author_email" => "email"
            );
        }
        
        /* Переносим */ 
        $sql = "SELECT * FROM $mode ";
        if ($result = $mysqli->query($sql)) {
            $num = $result->num_rows;
            $sql_wp = 'INSERT INTO `wp_posts`(';
            foreach ($fields as $key => $value) {
                $sql_wp .= $key.',';
            }
            $sql_wp = rem_last($sql_wp).') VALUES (';
            
            while( $row = $result->fetch_assoc() ){ 
                if (id_exists($row[id], $wp_table) ) {
                    continue;
                }
                
                foreach ($fields as $key => $value) {
                    $sql_wp .= $row[$value].',';
                }
                $sql_wp = rem_last($sql_wp).')';
                
                if (!($result_wp = $mysqli_wp->query(rem_last($sql_wp)))) {
                    printf("Ошибка добавления записей  в таблицу $wp_table. Код ошибки: %s\n", $mysqli_wp->error."<br>"); 
                    break;
                } 
                
                

            } 


        
        }
    }
}
/* Закрываем соединение */ 
$mysqli->close(); 
$mysqli_wp->close();

