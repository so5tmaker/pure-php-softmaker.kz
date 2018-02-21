<?
require_once '../blocks/global.inc.php';

function formatstr($str) 
{
    $str = trim($str);
    $str = stripslashes($str);
    $str = htmlspecialchars($str);
    return $str;
}

if (isset($_GET['email']))
{
//    $email = 'softmaker.kz@gmail.com';
    $email = formatstr($_GET['email']);
} else {
    $email = '';
}

//проверить отправлена ли форма
if(isset($_POST['subscription'])) {
    if (isset($_POST['subscrips'])) {
        $email = $_POST[email];
        $subscrips_array = $_POST['subscrips'];
        $sub_count = count($subscrips_array);
        $sub_del = '';
        for($i = 0; $i < $sub_count; ++$i) {
            $post_cat_array = explode("-", $subscrips_array[$i]);
            $post = $post_cat_array[0];
            $cat  = $post_cat_array[1];
            /* Здесь пишем что можно заносить информацию в базу */
            $query_txt = "UPDATE comments SET notify='0' WHERE post='$post' AND cat='$cat' AND email='$email'";
            $result = mysql_query ($query_txt);
        }
            if ($result == 'true') {
                $sub_del = "<li>".get_foreign_equivalent("Было удалено подписок").": $sub_count</li>";
            }else{
                $sub_del = "<li>".get_foreign_equivalent("Ошибка")." - ".get_foreign_equivalent("Было удалено подписок").": 0</li>";
            }
    }
}

if ($lang == "RU") {
    $softmaker_description = 'SoftMaker.kz - все для начинающих программистов | 
Нюансы программирования, разработки в системе 1С, PHP, Delphi, работа с MySQL, HTML, CSS';
    $sub_description = "Если Вы хотите, чтобы адрес <strong>$email</strong> был 
        отписан от некоторых уведомлений, то просто поставьте рядом с ними 
        галочки и нажмите расположенную чуть ниже кнопку:";
    $not_found = "<strong>$email</strong> не подписан на рассылку комментариев на этом сайте.";
    $empty = "Пустой адрес";
} else {
    $softmaker_description = get_foreign_equivalent("Примеры программирования в 1С, PHP, Delphi");
    $sub_description = "In order to unsubscribe your email address <strong>$email</strong>, 
            please, tick the checkboxes that you see below:";
    $not_found = "No subscription information found for email address <strong>$email</strong>.";
    $empty = "empty";
}
$header = get_foreign_equivalent("Управление подписками на комментарии");
$title = $header." - ".$softmaker_description;
$n=0;
include_once ($DIR."header.html");
// Реклама от Google
echo retArticlesTopPosition($cat);
echo "<h2 class='post_title2'>".$header."</h2>";

if ($email !== ''){
    $i = 1;
    $result4 = mysql_query ("SELECT cat,post FROM comments WHERE email='$email' AND notify = '1' GROUP BY `post`,`cat`",$db);
    if (mysql_num_rows($result4) <> 0) {
        echo "<p>$sub_description</p>";
        echo $sub_del;
        echo '<form action="subscription-manager.php" method="post"><ol>';
        $myrow4 = mysql_fetch_array ($result4);
        do
        {
            $post = $myrow4[post];
            $cat  = $myrow4[cat];
            $cats = tbl_info('categories',$cat,'name,cat_tbl');
            $cat_name = $cats[name];
            $filename = $cats[cat_tbl];
            if (empty($filename)){continue;}
            $arts = tbl_info($filename,$post,'name,title');
            $art_name  = $arts[name];
            $art_title = $arts[title];
            $filename = ($filename == 'data') ? 'articles' : 'files';
            $href = "$rest_/$filename/$cat_name/$art_name.html";
            echo '<li>';
            echo "<label for='subscrip-$i'>
                <input id='subscrip-$i' type='checkbox' value='$post-$cat' name='subscrips[]'>
                <a href='$href' $open>
                $art_title</a>
            </label>";
            $i += 1;
            echo '</li>';
        }
        while ($myrow4 = mysql_fetch_array($result4));
        echo "</ol>
            <input name='email' type='hidden' value='$email' />
            <p align=center>
                <input name='subscription' type='submit' id='subscription' 
                    value='".get_foreign_equivalent("Отписаться от рассылки")."' 
                    class='formbutton2' />
            </p>
        </form>";
    } else {
        echo "<ul class='error'>$sub_del</ul>";
        echo "
            <p align=center>
                $not_found
            </p>";
    }
} else {
    $not_found = str_replace('<strong></strong>', "<strong>$empty</strong>", $not_found);
    echo "<p align=center>$not_found</p>";    
}
      
include_once ($DIR."footer.html"); 

?>