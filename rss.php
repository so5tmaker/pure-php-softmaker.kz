<?php
    require_once 'blocks/global.inc.php';
    header("Content-Type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"".$coding."\"?>";
    $lang = filter_input(INPUT_GET, 'lang');
    if (!isset($lang)) {
        $lang = "RU";
    }
?>

<rss version="2.0">
<channel>
    <title><? 
    $link = get_lang_link_no_http();
    $link_http = get_lang_link_();
    echo get_foreign_equivalent("Канал новостей блога")." ".$link;    
    ?></title>
    <link><? 
    echo $link_http;    
    ?></link>
<description><? echo get_foreign_equivalent("Десять новых статей сайта")." ".$link; ?> - PHP,MySQL,HTML,1С.</description>
<language><? echo $lang; ?></language>
<?php 
    
    $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
//    $date = '2009-12-12';
//    $ndate = SUBSTR($date, 8, 2)."-".SUBSTR($date, 5, 2)."-".SUBSTR($date, 0,4);
    
    // Выберем статьи и закачки
//    $query = "SELECT id,name,cat,title,description,date as mdate, 'articles'
//      FROM data
//      WHERE secret='0' and lang='".$lang."' 
//    UNION
//    SELECT id,name,cat,title,description,date as mdate, 'files'
//      FROM files
//      WHERE secret='0' and lang='".$lang."' ORDER BY `mdate` DESC LIMIT 0,10";
    $query = "SELECT id,name,cat,title,text,meta_d,mini_img,description,date as mdate, 'articles'
      FROM data
      WHERE lang='".$lang."' ORDER BY `mdate` DESC LIMIT 0,10";
//    $query = "SELECT id,name,cat,title,text,meta_d,mini_img,description,date as mdate, 'articles'
//      FROM data
//      WHERE secret='0' and lang='".$lang."' 
//    UNION
//    SELECT id,name,cat,title,text,meta_d,mini_img,description,date as mdate, 'files'
//      FROM files
//      WHERE secret='0' and lang='".$lang."' ORDER BY `mdate` DESC LIMIT 0,10";
    $result = mysql_query($query,$db);
    if ($myrow = mysql_fetch_array($result))
    {
        do
        {
            $descr = "";
            $descr = stripslashes($myrow["description"]);
//            $descr = htmlspecialchars( $myrow["description"]);
            $title = htmlspecialchars($myrow["title"]);
            $dt = htmlspecialchars($myrow["mdate"]);
            
            $file_name = $myrow[name];
            $cat = $myrow[cat];
            $cat_name = get_fld_by_id($cat, 'categories', 'name');
            if ($cat_name == '0')
            {
                continue;
            }
            $distination = create_thumb($myrow, $cat_name, TRUE);
            $link=htmlspecialchars("$link_http$myrow[articles]/$cat_name/$file_name.html");
            $guid=htmlspecialchars("$link_http$myrow[articles]/$cat_name/$file_name.html");
            $date=date("D, j M Y G:i:s", strtotime($dt)). " GMT";
            if(!empty($distination)){
                $description = "<description><![CDATA[<img src='$link_http$distination' align='middle' hspace='6'>".$descr."]]></description>\n";
            } // align='left' hspace='6'
            else{
                $description = "<description>".$descr."</description>";}
            printf ("<item>
            <title>%s</title>
            <link>%s</link>
            %s
            <author>info@softmaker.kz</author>
            
            <guid>%s</guid>
            </item>", $title.' '.$dt,$link,$description,$guid);
//            <description><![CDATA[<img src="http://sitename.ru/image.jpg">]]></description><pubDate>$date</pubDate>
        }
        while($myrow = mysql_fetch_array($result));
    }
    
?>
</channel>
</rss>