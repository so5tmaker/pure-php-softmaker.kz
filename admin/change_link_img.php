<? include ("lock.php");

$subs[] = "faq";
$subs[] = "data";
$subs[] = "files";

// функция замены ссылок на изображения в тексте
function change_img($text) 
{ 
    $count = null;
    return preg_replace('~src=(.+?)\\/~is', 'src="../../files/', $text, -1, $count);
}

function change_url($text) 
{ 
    global $db;
    $matches = null;
    preg_match_all('#\\s(?:href)=(?:[\\"\\\'])?(.*?)(?:[\\"\\\'])?(?:[\\s\\>])#i', $text, $matches, PREG_PATTERN_ORDER);
    $new_text = $text;
    foreach($matches[1] as $k => $v) {
        if (strstr($v, 'files.php')) {
            $filename = 'files';
            $tbl = 'files';
        } elseif (strstr($v, 'articles.php')) {
            $filename = 'articles';
            $tbl = 'data';
        } else {
            continue;
        }
        $arr=parse_url($v);
        parse_str($arr['query'], $arr2);
        $id_score = $arr2['id'];
        if (isset($id_score)){
            $id_score = $arr2['id'];
        } else {
            $id_score = $arr2['amp;id'];
        }
        $result = mysql_query("SELECT name, cat FROM $tbl WHERE (id=$id_score)",$db);
        $num_rows = mysql_num_rows($result);
        // если количество записей не равно нулю
        if ($num_rows!=0)
        {
            $myrow = mysql_fetch_array($result);
            $file_name = $myrow[name];
            $cat = $myrow[cat];
            $cat_name = get_fld_by_id($cat, 'categories', 'name');
            if ($cat_name == '0')
            {
                continue;
            }
            $fragment = '';
            if (isset($arr['fragment'])){
                $fragment = '#'.$arr['fragment'];
            }
            $url = "http://www.softmaker.kz/$filename/$cat_name/$file_name.html$fragment";
            echo "<p align='center'>".$v." - ".$url."</p>";
            $new_text = str_replace($v, $url, $new_text);
        }
//        print_r($arr2); 
//        print_r($arr['fragment']);
    }
    return $new_text;
}

if (isset($_GET['table'])) {$table = $_GET['table'];}
 
if (isset($table))
{
    $tbl_dt = $table;
    $title_here = "Страница замены в таблице ".$tbl_dt;
    include("header.html");
    echo "<h3 align='center'>".$title_here."</h3>";
    $count = 0; $fld = 'text';
    if ($tbl_dt == 'faq'){
        $fld = 'answer';
    } 
//    $id_score = 41;
    $result = mysql_query("SELECT id,$fld FROM $tbl_dt",$db); // WHERE (id=$id_score)
    $myrow = mysql_fetch_array($result);
    do
    {
        $text = stripslashes($myrow[$fld]);
        $text = change_img($text);
        $text = change_url($text);
        $text = addslashes($text);
        /* Здесь пишем что можно заносить информацию в базу */
        $result1 = mysql_query ("UPDATE ".$tbl_dt." SET $fld='$text' WHERE id='$myrow[id]'",$db);
        if ($result1 == 'true') {
//            echo "<p align='center'>".$myrow["title"]." - ".$name."</p>";
        }else{
            echo "<p align='center'>Замена ".$myrow["title"]." не прошла!</p>";
        }
        $count = $count + 1;
    }
    while ($myrow = mysql_fetch_array($result));
    print "<p>Всего строк: ".$count."</p>";
} else {
    $title_here = "Страница замены в таблице";
    include("header.html");
    echo "<h3 align='center'>".$title_here."</h3>";
    foreach($subs as $k => $v) {
        echo "<p align=center><a href='change_link_img.php?table=$v'>".$v."</a></p>";;
    }
}
include_once ("footer.html"); ?>  