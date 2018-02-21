<? 
//index.php

require_once 'blocks/global.inc.php';

$result = mysql_query("SELECT title,meta_d,meta_k,text,page FROM settings WHERE (page='index') AND (lang ='".$lang."')",$db);

echo_error($result);

if (mysql_num_rows($result) > 0)
{
    $myrow = mysql_fetch_array($result); 
} else {
    echo_no_records();
}

$page_ = filter_input(INPUT_GET, 'page');
$deep = '';
if (isset($page_)) {
    $deep = '../';
}  else {
    $page_ = 1;
}
$title = "Главная страница";
$n=1;$text=$myrow["text"]; $cat_adv = 1;
require_once ("header.html");
codbanner(1);
//$advs->show('top');
require_once ("blocks/centraltd.php");
require_once ("footer.html");
?>       
  
          
