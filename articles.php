<? 
require_once 'blocks/global.inc.php';

//*************** Блок проверки параметров пути переменной $GET ***************

$Link = new Link(filter_input_array(INPUT_GET));

if ($Link->id OR $Link->cat){
    $Link->get_true_link_id();
}else{
    $Link->get_true_link(); 
}

// Если на вход идет ссылка типа: http://www.softmaker.kz/articles.php?page=11
if (!$Link->sec_name AND !$Link->cat_name AND !$Link->file_name AND !$Link->sec AND !$Link->cat AND !$Link->id AND $Link->page){
    $Link->Error301();
}
 
if (count($Link->true_links) == 0){
    $Link->Error404();
}
$result = array_diff($Link->true_links, $Link->links);
$count  = count($Link->true_links) !== count($Link->links);
$Link->links = $Link->true_links;

$lang = $Link->lang;
$rest_ = $Link->rest;

$data       = $Link->tbls[data];
$categories = $Link->tbls[categories];
$sections   = $Link->tbls[sections];
$id_score = $data[id];
$cat      = $categories[id];
$sec      = $sections[id];
$deep = $Link->GetDeep();
$filename = $sections[name];
$razdel = $sections[title];

$count_res = count($result);
if ($count_res == 0 AND ($Link->file_name)){
    include ("view_post.php");
    exit ("");
}elseif($count OR $count_res !== 0 OR $gotopage){
    $Link->Error301();
}

//*************** Конец блока проверки параметров пути переменной $GET *********

// Здесь я проверяю номер категории, если нет, то в запросах нужно изменить текст.
if ($Link->cat_name) {
    $text_cat = "='$cat'";
}else{
    $text_cat = "<>'0'";
    $result_raz = mysql_query("SELECT title,meta_d,meta_k,text FROM settings WHERE (page='$Link->sec_name') AND (lang ='".$lang."')",$db);
    
    echo_error($result_raz);

    if (mysql_num_rows($result_raz) > 0) {
        $myrow_raz = mysql_fetch_array($result_raz);
    } else {
        echo_no_records();
    }
    //    Здесь заполняю переменные из раздела ." | SoftMaker.Kz - ".$myrow_raz["title"]
    $title= $razdel;
    $meta_d=$myrow_raz["meta_d"];
    $meta_k=$myrow_raz["meta_k"];
} 

$result = mysql_query("SELECT * FROM categories WHERE (id".$text_cat.") AND (turnon=1) AND (lang ='".$lang."')",$db);

echo_error($result);

if (mysql_num_rows($result) > 0) {
    $myrow = mysql_fetch_array($result);
    if (isset($cat)) {
        //    Здесь заполняю переменные из категории
        $text=$myrow["text"];
        $lang = $myrow["lang"];
        $cat_title = $myrow["title"];
        $cat_name1 = $myrow["name"];
        $title_cat = get_foreign_equivalent("Заметки категории -")." ".$myrow["title"];
        $title = $title_cat;
        $meta_d=$myrow["meta_d"];
        $meta_k=$myrow["meta_k"];
    }
} else {
    echo_no_records();
}
$n = ($filename == 'articles') ? 2 : 3;
if ($text_cat == "<>'0'") {
    $text="";
} else {
    $text=$myrow_raz["text"];
}
$cat_adv = 1;
require_once ("header.html");

if ($text_cat != "<>'0'") {
    show_breadcrumbs(2, $cat_name1);
} else {
   show_breadcrumbs(1);
}
 
// Работа с закладками страниц в подвале - Первая | Предыдущая | 1 | 2 | 3 | 4 | 5 | 6 | 7 | Следующая | Последняя
$num = $notices; // переменная для указания кол-ва описаний статей на странице
// Извлекаем из URL текущую страницу
@$page = $Link->page;
// Определяем общее число сообщений в базе данных
$sql = "SELECT COUNT(*), cat.sec as sec, d.* 
    FROM (
	SELECT cat
	FROM data
	WHERE (cat".$text_cat.") AND (lang ='".$lang."')
    ) as d, categories as cat WHERE cat.id=d.cat AND (turnon=1) AND cat.sec = '$sec'";
$result00 = mysql_query($sql);
$temp = mysql_fetch_array($result00);
$posts = $temp[0];
// Находим общее число страниц
$total = intval((($posts - 1) / $num) + 1);
// Определяем начало сообщений для текущей страницы
$page = (intval($page) == 0) ? 1 : intval($page);
// Если значение $page меньше единицы или отрицательно
// переходим на первую страницу
// А если слишком большое, то переходим на последнюю
if(empty($page) or $page < 0) $page = 1;
if($page > $total) $page = $total;
// Вычисляем начиная с какого номера
// следует выводить сообщения
$start = $page * $num - $num;
// Выбираем $num сообщений начиная с номера $start			

// Выводим закладки вверху страницы
echo_bookmarks($cat_name1, $page, $total);
$flds = 'id, name, cat, description, view, date, title, author, mini_img';
$sql = "SELECT cat.name as catname, cat.sec as sec, d.* 
    FROM (
	SELECT $flds
	FROM data
	WHERE (cat".$text_cat.") AND (lang ='".$lang."')
    ) as d, categories as cat WHERE cat.id=d.cat AND (turnon=1) AND cat.sec = '$sec'
    ORDER BY date DESC  LIMIT $start, $num";

$advs->show('top', $Link);

// Функция выводит анонсы статей и файлов
print_post_notice(mysql_query($sql, $db), $filename, $cat);


require_once ("footer.html");

?>
