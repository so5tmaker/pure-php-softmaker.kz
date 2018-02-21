<td width="182" valign="top" class="left">
  <?
     function show_cat($id,$sec_name,$result2,$db)
     {
        global $lang, $cat, $deep, $rest_;
        
        echo_error($result2); 

        if (mysql_num_rows($result2) > 0)
        {
            $myrow2 = mysql_fetch_array($result2);
            do
            {
                //Здесь хочу проверить, есть ли в категории хоть одна заметка,
                //если нет не буду выводить категорию
                $local_cat= $myrow2["id"];
                $tbl      = 'data';
                $cat_name = $myrow2["name"];
                $meta_d   = $myrow2[meta_d];
                $atitle   = $myrow2["title"];
                if ($myrow2["parent"] <> 0){
                    $ctitle = $myrow2["atitle"];
                    $length = 8;       
                    $pos = strpos($ctitle, '&#');
                    // Заметьте, что используется ===.  Использование == не даст верного 
                    // результата, так как 'a' находится в нулевой позиции.
                    if ($pos === false) {
                        $atitle = '&#160;&#160;&#10149;'.((strlen($ctitle) > $length) ? substr($ctitle, 0, $length).'...' : $ctitle);
                    } else {
                        $atitle = '&#160;&#160;&#10149;'.$ctitle;
                    }
                    
                }              
                $res_kol  = mysql_query ("SELECT COUNT(*) FROM ".$tbl." WHERE (cat='$local_cat') and (lang='".$lang."')",$db);
                
                $sum_kol = mysql_fetch_array($res_kol);
                $img = 'arr';
                if (isset($cat))
                {
                    if ($cat == $local_cat)
                    {
                        $cat_class = 'm_sel';
                        $img = 'arr2'; 
                    } else {
                        $cat_class = 'm';
                    }
                } else {
                    $cat_class = 'm';
                }

                $total = $sum_kol[0] + $s_count; 
                if ( $total <> 0)
                {
                    printf ("<p class='point'>"
                            . "<img src='".$deep."img/".$img.".jpg' height='10' width='10'>"
                            . "<a title='$meta_d' class='$cat_class' href='%s/%s/%s/'>%s (%s)</a>"
                            . "</p>",$rest_,$sec_name,$cat_name,$atitle,$total);
                }
            }
            while ($myrow2 = mysql_fetch_array($result2));
        } else {
            echo_no_records();
        }
     }

    $sec_res = mysql_query("SELECT * FROM sections WHERE lang='".$lang."'",$db);

    echo_error($sec_res);

    if (mysql_num_rows($sec_res) > 0)
    {
        $sec_row = mysql_fetch_array($sec_res);
        do
        {
            // Стрелка вниз с поворотом направо &#8627;
            // Жирная закрашенная изогнутая стрела, указывающая вниз и вправо &#10149;
            // Неразрывный пробел &#160;
            $id = $sec_row["id"];           
            $sql = "SELECT id, meta_d, title as atitle, parent,
                CASE parent WHEN 0 THEN title ELSE CONCAT('&#160;&#160;&#10149;', title) END AS title, 
                name 
                FROM categories WHERE(sec='$id') AND (lang='$lang') AND (turnon=1)
                ORDER BY CASE parent WHEN 0 THEN id ELSE parent END, 
                CASE parent WHEN 0 THEN title ELSE CONCAT('    ', title) END DESC";
            
            $result2 = mysql_query($sql, $db);
//            $result2 = mysql_query("SELECT * FROM categories WHERE (sec='$id') AND (lang='".$lang."') ORDER BY title ASC",$db);
            if (mysql_num_rows($result2) > 0)
            {
                
                echo_error($result2);
                $sec_name  = $sec_row["name"];
                printf ("<div class='menu_title'>%s</div>",$sec_row["title"]);
                show_cat($id,$sec_name,$result2,$db);
            }
        }
        while ($sec_row = mysql_fetch_array($sec_res));
    } else {
        echo_no_records();
    }

$res_faq = mysql_query("SELECT id,name,cat,title FROM data WHERE (faq=1) AND (lang='".$lang."')",$db);

echo_error($res_faq);

if (mysql_num_rows($res_faq) > 0)

{
    ?>
    <div class="menu_title">FAQ</div>
    <?
    $myrow_faq = mysql_fetch_array($res_faq);
    do
    {
        $cat_name = get_fld_by_id($myrow_faq["cat"], 'categories', 'name');
        
        printf ("<p class='point'><img src='".$deep."img/arr4.jpg' height='10' width='10'><a $open class='m' href='%s/articles/%s/%s'>%s</a></p>",$rest_,$cat_name,$myrow_faq["name"].".html",$myrow_faq["title"]);
    }
    while ($myrow_faq = mysql_fetch_array($res_faq));
}
?>

<div class="menu_title"><? echo get_foreign_equivalent("Статистика"); ?></div>
 
 <?php 
$sql = "SELECT COUNT(*) FROM data INNER join categories on "
            . "data.cat=categories.id WHERE (data.lang='".$lang."') "
            . "AND (categories.turnon=1)";
$result10 = mysql_query ($sql, $db);
$sum = mysql_fetch_array($result10);

$sql = "SELECT data.id FROM data INNER join categories on "
            . "data.cat=categories.id WHERE (data.lang='".$lang."') "
            . "AND (categories.turnon=1)";
$result11 = mysql_query ("SELECT COUNT(*) FROM comments WHERE post IN ($sql)",$db);
$sum2 = mysql_fetch_array($result11);

echo "<p class='comments'>".get_foreign_equivalent("Заметок в базе: ").($sum[0])."<br> ".get_foreign_equivalent("Комментариев: ").($sum2[0])."</p>";
?>
 
<div class="menu_title"><? //echo get_foreign_equivalent("Реклама от Google"); ?></div>

<p align="center">
<?php
    include_once("counters/liveinternet.php");
    
    $menu = "<div class='menu_title'></div>";
    if ($n==1) { // реклама на главной
        echo $menu;
        codbanner(10);  
    } elseif ($n==2) { // реклама в статьях
        echo $menu;
        codbanner(11);
    } elseif ($n==3) {// реклама в файлах
        echo $menu;
        codbanner(12);
    } else {
        echo $menu;
        $advs->show('left', $Link);
    }
?>
</p>

</td>
