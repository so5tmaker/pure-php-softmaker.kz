        <!--<div align="center">
        <script type="text/javascript">
        var begun_auto_pad = 200085888;
        var begun_block_id = 200089285;
        </script>
        <script src="http://autocontext.begun.ru/autocontext2.js" type="text/javascript"></script>
        </div>-->
        <?php
   
        // Функция предназначена для получения блока верхней рекламы на index.php и files.php
//        AdvTopPosition();
        if (isset($page_)) {
            // Работа с закладками страниц в подвале - Первая | Предыдущая | 1 | 2 | 3 | 4 | 5 | 6 | 7 | Следующая | Последняя
            $text_cat = "<>'0'";
            $result77 = mysql_query("SELECT str FROM options", $db);
            $myrow77 = mysql_fetch_array($result77);
    //        $num = $myrow77["str"];
            $num = 5;
            // Извлекаем из URL текущую страницу
            @$page = $page_;
            // Определяем общее число сообщений в базе данных
            $result00 = mysql_query("SELECT COUNT(*) FROM data WHERE (lang='".$lang."')AND(cat".$text_cat.")");
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
        }else{
            $start = 0;
            $num = 5;
        }

        $result = mysql_query("SELECT id,name,cat,title,description,date,author,mini_img,view,rating,q_vote 
            FROM data WHERE (cat".$text_cat.") AND (lang ='".$lang."') 
                ORDER BY date desc, id LIMIT $start, $num",$db);
        ?>
        <table width="100%" align="center"><!--tr1_td1_table1_tr1_td1_table1-->
        <!--<tr>-->
          <td width="50%" valign="top" style="border-right:1px solid #ccc;"><!--tr1_td1_table1_tr1_td1_table1_td1-->
              <div class="new_head"><? echo get_foreign_equivalent("Новые статьи"); ?></div>
              <?php
              if ($lang <> 'RU') {
                  $sql = "SELECT id,name,cat,title,description,date as mdate,author,mini_img,view,rating,q_vote FROM data WHERE lang ='".$lang."' ORDER BY date DESC LIMIT $start, $num";
              }  else {
                  $sql = "SELECT a.id,a.name,a.cat,a.title,a.description,b.date as mdate,a.author,a.mini_img,a.view,rating, a.q_vote, b.question, b.id as faq_id\n"
                . " FROM data a, faq b\n"
                . " WHERE a.id = b.post AND lang = '".$lang."'\n"
                . "union\n"
                . "SELECT a.id,a.name,a.cat,a.title,a.description,a.date as mdate,a.author,a.mini_img,a.view,rating, a.q_vote, 'null', 0\n"
                . "FROM data a\n"
                . "WHERE lang ='".$lang."' ORDER BY mdate DESC LIMIT $start, $num";
              }

                $result = mysql_query($sql,$db);
                
                echo_error($result);
                
                if (mysql_num_rows($result) > 0){
                    $myrow = mysql_fetch_array($result);
                    $i = 0;
                    do
                    {
                        if (($myrow["question"] == 'null')OR($lang <> 'RU')){
                            $title = $myrow["title"];
                            $description = $myrow["description"];
                            $faq_id = "";
                        }  else {
                            $title = $myrow["question"];
                            $description = "<p>".$myrow["title"]."</p>";
                            $faq_id = "#".$myrow["faq_id"];
                        }
                        $cat_name = get_fld_by_id($myrow[cat], 'categories', 'name');
                        $cat_path = $rest_.'/articles/'.$cat_name.'/';
                        $path = $cat_path.$myrow["name"].".html";
                        $comments = "<a $open href='".$path."#comm'>".
                                get_foreign_equivalent("Комментариев:")." ".
                                quant_comment($myrow["id"],$myrow["cat"])."</a>";
                        $data_url = "data-url='$path'";
                        printf ("
                                <div class='content3'>
                                <p class='new_name'><img class='mini' align='left' src='%s'>
                                    <a $open href='%s".$faq_id."'>%s</a>
                                </p>
                                <p class='new_title'>%s</p>
                                
                                <div class='new_line'><img src='".$deep."img/spacer.gif' width='1' height='1'></div>
                                <div class='info'>
                                <span class='comment'>%s</span>
                                <span class='comment'>%s: %s</span>
                                </div></div>", $deep.$myrow["mini_img"],$path,$title,$description, $comments, get_foreign_equivalent("Просмотров"), $myrow["view"]);
                        $i+=1;
                        if ($i==5 or $i==1){
                           RectangleMain();
                        }           
                    }while ($myrow = mysql_fetch_array($result));
                } // ,get_foreign_equivalent("Добавлен"), $myrow["mdate"]
             ?>
          </td><!--tr1_td1_table1_tr1_td1_table1_td1 bgcolor="#F6F6F6"-->
          <td width="50%" valign="top" ><!--tr1_td1_table1_tr1_td1_table1_td2-->
              <div class="new_head_1"><? echo get_foreign_equivalent("Новые файлы"); ?></div>
               <?php
                $result = mysql_query("SELECT id,name,cat,title,description,date,author,mini_img,view,rating,q_vote FROM files WHERE lang ='".$lang."' ORDER BY date DESC LIMIT $start, $num",$db);

                echo_error($result);
                
                if (mysql_num_rows($result) > 0)
                {
                    $myrow = mysql_fetch_array($result);
                    $i = 0;
                    do
                    {
                        $cat_name = get_fld_by_id($myrow[cat], 'categories', 'name');
                        $cat_path = $rest_.'/files/'.$cat_name.'/';
                        $path = $cat_path.$myrow["name"].".html";
                        $data_url = "data-url='$path'";
                        $comments = "<a $open href='".$path."#comm'>".get_foreign_equivalent("Комментариев:")." ".quant_comment($myrow["id"],$myrow["cat"])."</a>";
                        printf ("
                             <div class='content3'>
                             <p class='new_name1'><img class='mini' align='left' src='%s'>
                                <a $open href='%s'>%s</a>
                             </p>
                             <p class='new_title'>%s</p>

                             <div class='new_line1'><img src='".$deep."img/spacer.gif' width='1' height='1'></div>
                             <div class='info1'>
                             <span class='comment'>%s</span>
                             <span class='comment'>%s: %s</span>
                             </div></div>", $deep.$myrow["mini_img"],$path,$myrow["title"], $myrow["description"], $comments, get_foreign_equivalent("Просмотров"), $myrow["view"]);
                        $i+=1;
                        if ($i==10 or $i==1){
                           RectangleMain();
                        }           
                    }while ($myrow = mysql_fetch_array($result));
                    if ($i<10){
                       RectangleMain();
                    } 
                }  else {
                    RectangleMain();
                    echo 'Advtop';
                }
//                if (isset($page_)) {
//                    echo_bookmarks(NULL, $page, $total);
//                }
             ?>
             </td><!--tr1_td1_table1_tr1_td1_table1_td2-->
             
            </table><!--tr1_td1_table1_tr1_td1_table1-->
<?
             if (isset($page_)) {
                    echo_bookmarks(NULL, $page, $total);
                }
             ?>
       
  
          
