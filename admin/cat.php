<? include ("lock.php");?>
<? $name_dt = "���������"; $tbl_dt = "categories"; $action = "cat.php";
// ����� ���� �� 6 ������: 3 � ������� GET � 3 � ������� POST
$translit = TRUE;
if (isset($_GET['mode'])){// 1-� ����� 3 ������ GET
    if ($_GET['mode'] == 'new') {// ���������� ������ ��������
        $title_here = "�������� ���������� ".$name_dt; include("header.html");
        echo "<h3 align='center'>���������� ".$name_dt."</h3>";
        ?>
        <form name="form1" method="post" action="<? echo $action ?>">
         <p>
            <label>
            <input type="checkbox" name="turnon" id="turnon" checked>
            �������� ����� ���� <? echo $name_dt ?>
            </label>
        </p>
         <p>
           <label>������� �������� <? echo $name_dt ?><br>
             <input type="text" name="title" id="title" size="<? echo $SizeOfinput ?>">
             </label>
         </p>
         <p>
           <label>������� �������������� �������� <? echo $name_dt ?><br>
             <input type="text" name="name" id="name" size="<? echo $SizeOfinput ?>">
             </label>
         </p>
         <p>
             <input name="button" type="button" value="�����������������" size="<? echo $SizeOfinput ?>" onclick="SetTranslitRuToLat()">
         </p>
         <p>
           <label>������� ������� �������� <? echo $name_dt ?><br>
           <input type="text" name="meta_d" id="meta_d" size="<? echo $SizeOfinput ?>">
           </label>
         </p>
         <p>
           <label>������� �������� ����� ��� <? echo $name_dt ?><br>
           <input type="text" name="meta_k" id="meta_k" size="<? echo $SizeOfinput ?>">
           </label>
         </p>

         <p>
           <label>������� ������ ����� <? echo $name_dt ?> � ������<br>
           <textarea name="text" id="text" cols="<? echo $ColsOfarea ?>" rows="20"></textarea>
           </label>
         </p>

         <p>
           <label>������� ������ ���� � ����� <? echo $name_dt ?><br>
           <input type="text" name="file" id="file" size="<? echo $SizeOfinput ?>" maxlength="40">
           </label>
         </p>

         <p>
           <label>������� ������� ������ <? echo $name_dt ?>
           <input type="text" name="table" id="table" maxlength="20" size="<? echo $SizeOfinput ?>">
           </label>
         </p>

        <p>
           <label>�������� ������<br>
           <select name="sec">
           <?
		$result = mysql_query("SELECT title,id FROM sections",$db);
                if (!$result)
                {
                    echo "<p align='center'>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>";
                    exit(mysql_error());
                }
                if (mysql_num_rows($result) > 0)
                {
                    $myrow = mysql_fetch_array($result);
                    do
                    {
                    printf ("<option value='%s'>%s</option>",$myrow["id"],$myrow["title"]);
                    }
                    while ($myrow = mysql_fetch_array($result));
                } else {
                    echo "<p align='center'>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
                    exit();
                }
            ?>
            </select>
           </label>
         </p>
         <p>
           <label>�������� <? echo $name_dt ?> ��������<br>
           <select name="parent">
          <? 
                printf ("<option value='%s'>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");
                $resultru = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE lang='RU'",$db);

                if (!$resultru)
                {
                    echo "<p align='center'>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>";
                    exit(mysql_error());
                }
                if (mysql_num_rows($resultru) > 0)
                {
                    $myrowru = mysql_fetch_array($resultru);
                    do
                    {
                        printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                    }
                    while ($myrowru = mysql_fetch_array($resultru));
                } else {
                    echo "<p align='center'>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
                    exit();
                }
           ?>
           </select>
           </label>
         </p>
         <p>
           <label>�������� �������� <? echo $name_dt ?> �� ������� �����<br>
           <select name="id_lang">
          <? 
                printf ("<option value='%s'>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");
                $resultru = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE lang='RU'",$db);

                if (!$resultru)
                {
                    echo "<p align='center'>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>";
                    exit(mysql_error());
                }
                if (mysql_num_rows($resultru) > 0)
                {
                    $myrowru = mysql_fetch_array($resultru);
                    do
                    {
                        printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                    }
                    while ($myrowru = mysql_fetch_array($resultru));
                } else {
                    echo "<p align='center'>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
                    exit();
                }
           ?>
           </select>
           </label>
         </p>
         <p>
           <label>�������� ���� <? echo $name_dt ?><br>
           <select name="lang">
           <?
                foreach ($langs as $val) {
                   printf ("<option value='%s'>%s</option>",$val,$val);
                }
           ?>
           </select>
           </label>
         </p>
         <p>
           <label>�������� �������� <? echo $name_dt ?> �� ������� �����<br>
           <select name="id_lang">
          <? 
                printf ("<option value='%s'>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");
                $resultru = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE lang='RU'",$db);

                if (!$resultru)
                {
                    echo "<p align='center'>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>";
                    exit(mysql_error());
                }
                if (mysql_num_rows($resultru) > 0)
                {
                    $myrowru = mysql_fetch_array($resultru);
                    do
                    {
                        printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                    }
                    while ($myrowru = mysql_fetch_array($resultru));
                } else {
                    echo "<p align='center'>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
                    exit();
                }
           ?>
           </select>
           </label>
         </p>
         <input name="mode" type="hidden" value="add">
         <p>
           <label>
           <input type="submit" name="submit" id="submit" value="��������� <? echo $name_dt ?> � ����">
           </label>
         </p>
       </form>
        <?
        }elseif ($_GET['mode'] == 'edit') {// �������������� ��������
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "�������� �������������� ".$name_dt; include("header.html");
            echo "<h3 align='center'>�������������� ".$name_dt."</h3>";
            if (!isset($id))
            {
                $result = mysql_query("SELECT title,id,lang FROM ".$tbl_dt." ORDER BY id_lang,id");
                $myrow = mysql_fetch_array($result);
                do
                {
                printf ("<p align='center'><a href='%s?id=%s&lang=%s&mode=edit'>%s</a></p>",$action,$myrow["id"],$myrow["lang"],$myrow["title"]);
                }
                while ($myrow = mysql_fetch_array($result));
            }else{
                $result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE id=$id");
                $myrow = mysql_fetch_array($result);

                $result2 = mysql_query("SELECT id,title FROM sections");
                $myrow2 = mysql_fetch_array($result2);

                $count = mysql_num_rows($result2);

                echo "<form name='form1' method='post' action='$action'>";
                
                
                if ($myrow[turnon]){
                    $turnon = "checked";
                }else{
                    $turnon = "";
                }
                ?>
                <p>
                    <label>
                    <input type="checkbox" name="turnon" id="turnon" <? echo $turnon ?>>
                    �������� ����� ���� <? echo $name_dt ?>
                    </label>
                </p>
                <?
                
                echo "<p align='center'>�������� ������ ��� ".$name_dt."<br><select name='sec' size='$count'>";
                do
                {
                    if ($myrow['sec'] == $myrow2['id'])
                    {
                        printf ("<option value='%s' selected>%s</option>",$myrow2["id"],$myrow2["title"]);
                    }else{
                        printf ("<option value='%s'>%s</option>",$myrow2["id"],$myrow2["title"]);
                    }
                }
                while ($myrow2 = mysql_fetch_array($result2));

                echo "</select></p>";
                ?>
                <p>
                   <label>�������� <? echo $name_dt ?> ��������<br>
                   <select name="parent">
                  <? 
                       if (isset($_GET['lang'])){$lang = strtoupper($_GET['lang']);}else{$lang = "RU";}
                       if ($lang == "RU"){printf ("<option value='%s' selected>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");
                       } else {printf ("<option value='%s'>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");}
                        $resultru = mysql_query("SELECT title,id,parent FROM ".$tbl_dt." WHERE lang='RU'",$db);
                        $was = 0;
                        if (!$resultru)
                        {
                            echo "<p align='center'>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>";
                            exit(mysql_error());
                        }
                        if (mysql_num_rows($resultru) > 0)
                        {
                            $myrowru = mysql_fetch_array($resultru);
                            do
                            {
                                if ($myrowru["id"] == $myrow['parent'])
                                {
                                    printf ("<option value='%s' selected>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                                    $was = 1;
                                }else{
                                    printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                                }
                            }
                            while ($myrowru = mysql_fetch_array($resultru));
                            if ($was == 0 AND $lang != "RU"){printf ("<option value='%s' selected>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");}
                        } else {
                            echo "<p align='center'>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
                            exit();
                        }
                   ?>
                   </select>
                   </label>
                 </p>
                <?
                print <<<HERE
                 <p>
                   <label>������� �������� $name_dt<br>
                     <input value="$myrow[title]" type="text" name="title" id="title" size="$SizeOfinput">
                     </label>
                 </p>
                 <p>
                   <label>������� �������������� �������� $name_dt<br>
                    <input value="$myrow[name]" type="text" name="name" id="name" size="$SizeOfinput">
                    </label>
                 </p>
                 <p>
                     <input name="button" type="button" value="�����������������" size="$SizeOfinput"  onclick="SetTranslitRuToLat()">
                 </p>
                 <p>
                   <label>������� ������� �������� $name_dt<br>
                   <input value="$myrow[meta_d]" type="text" name="meta_d" id="meta_d" size="$SizeOfinput">
                   </label>
                 </p>
                 <p>
                   <label>������� �������� ����� ��� $name_dt<br>
                   <input value="$myrow[meta_k]" type="text" name="meta_k" id="meta_k" size="$SizeOfinput">
                   </label>
                 </p>

                 <p>
                   <label>������� ������ ����� �������� $name_dt
                   <textarea name="text" id="text" cols="$ColsOfarea" rows="20">$myrow[text]</textarea>
                   </label>
                 </p>

                <p>
                   <label>������� ������ ���� � ����� $name_dt
                   <input value="$myrow[file]" type="text" name="file" id="file" size="$SizeOfinput" maxlength="40">
                   </label>
                 </p>

                 <p>
                   <label>������� ������� ������ $name_dt
                   <input value="$myrow[cat_tbl]" type="text" name="table" id="table" maxlength="20" size="$SizeOfinput">
                   </label>
                 </p>
HERE;
                ?>
                        <p>
                           <label>�������� ���� <? echo $name_dt ?><br>
                           <select name="lang">
                           <?
                                foreach ($langs as $val) {
                                    if ($myrow["lang"] == $val)
                                    {
                                        printf ("<option value='%s' selected>%s</option>",$myrow["lang"],$myrow["lang"]);
                                    }else{
                                        printf ("<option value='%s'>%s</option>",$val,$val);
                                    }
                                }
                           ?>
                           </select>
                           </label>
                         </p>
                         <p>
                           <label>�������� �������� <? echo $name_dt ?> �� ������� �����<br>
                           <select name="id_lang">
                          <? 
                               if (isset($_GET['lang'])){$lang = strtoupper($_GET['lang']);}else{$lang = "RU";}
                               if ($lang == "RU"){printf ("<option value='%s' selected>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");
                               } else {printf ("<option value='%s'>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");}
                                $resultru = mysql_query("SELECT title,id FROM ".$tbl_dt." WHERE lang='RU'",$db);
                                $was = 0;
                                if (!$resultru)
                                {
                                    echo "<p align='center'>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>";
                                    exit(mysql_error());
                                }
                                if (mysql_num_rows($resultru) > 0)
                                {
                                    $myrowru = mysql_fetch_array($resultru);
                                    do
                                    {
                                        if ($myrowru["id"] == $myrow['id_lang'])
                                        {
                                            printf ("<option value='%s' selected>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                                            $was = 1;
                                        }else{
                                            printf ("<option value='%s'>%s</option>",$myrowru["id"],substr($myrowru["title"], 0, $SizeOfSelect));
                                        }
                                    }
                                    while ($myrowru = mysql_fetch_array($resultru));
                                    if ($was == 0 AND $lang != "RU"){printf ("<option value='%s' selected>%s</option>",0,"<<�������� ".$name_dt." �� �������>>");}
                                } else {
                                    echo "<p align='center'>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
                                    exit();
                                }
                           ?>
                           </select>
                           </label>
                         </p>
                <?
                print <<<HERE
                 <input name="id" type="hidden" value="$myrow[id]">
                 <input name="mode" type="hidden" value="update">
                 <p>
                   <label>
                   <input type="submit" name="submit" id="submit" value="��������� ���������">
                   </label>
                 </p>
               </form>
HERE;
            }
        }elseif ($_GET['mode'] == 'del') {// �������� ��������
            $title_here = "�������� �������� ".$name_dt; include("header.html");
          ?>
                <p><strong>�������� ������� ��� �������� <? echo $name_dt;?></strong></p>
                <form action="<? echo $action;?>" method="post">
                    <?
                    $result = mysql_query("SELECT title,id FROM ".$tbl_dt);
                    $myrow = mysql_fetch_array($result);
                    do
                    {
                    printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>",$myrow["id"],$myrow["title"]);
                    }
                    while ($myrow = mysql_fetch_array($result));
                    ?>
                    <input name="mode" type="hidden" value="drop">
                    <p> <input name="submit" type="submit" value="�������� <? echo $name_dt;?>"></p>
                </form>
                <?
      }//if ($_GET['mode'] == 'del')
 }//if (isset($_GET['mode']))
 
 if (isset($_POST['mode'])){// 2-� ����� 3 ������ POST
    if ($_POST['mode'] == 'add') {// ���������� ������ ��������
        $title_here = "�������� ���������� ".$name_dt; include("header.html");
        if (isset($_POST['title']))    {$title = $_POST['title'];if ($title == ''){unset($title);}}
        if (isset($_POST['meta_d']))   {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
        if (isset($_POST['meta_k']))   {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
        if (isset($_POST['text']))     {$text = $_POST['text']; if ($text == '') {unset($text);}}
        if (isset($_POST['sec']))      {$sec = $_POST['sec']; if ($sec == '') {unset($sec);}}
        if (isset($_POST['file']))     {$file = $_POST['file']; if ($file == '') {unset($file);}}
        if (isset($_POST['table']))    {$table = $_POST['table']; if ($table == '') {unset($table);}}
        if (isset($_POST['lang']))     {$lang = $_POST['lang']; if ($lang == '') {unset($lang);}}
        if (isset($_POST['id_lang']))  {$id_lang = $_POST['id_lang']; if ($id_lang == '') {unset($id_lang);}}
        if (isset($_POST['name']))     {$name = $_POST['name']; if ($name == '') {unset($name);}}
        if (isset($_POST['parent']))   {$parent = $_POST['parent']; if ($parent == '') {unset($parent);}}
        if (isset($_POST['turnon']))      {
            $turnon = $_POST['turnon']; 
            if ($turnon == '') {
                unset($turnon);
            } else {
                $turnon = ($turnon == 'on') ? 1 : 0;
            }   
        }

        if (isset($title) && isset($meta_d) && isset($meta_k) && isset($text) && isset($sec) && isset($file) && isset($table) && isset($name))
        {
            /* ����� ����� ��� ����� �������� ���������� � ���� */
            $id = get_id($tbl_dt);
            $result = mysql_query ("INSERT INTO ".$tbl_dt." (id,parent,sec,title,meta_d,meta_k,text,file,cat_tbl,lang,id_lang,name,turnon) VALUES ($id,'$parent','$sec','$title','$meta_d','$meta_k','$text','$file','$table','$lang','$id_lang','$name','$turnon')");
            if ($result == 'true') {echo "<p align='center'>���������� ".$name_dt." ������� ���������!</p>";}
            else {echo "<p align='center'>���������� ".$name_dt." �� ������!</p>";}
        }else{
            echo "<p align='center'>�� ����� �� ��� ����������, ������� ���������� ".$name_dt." ����������.</p>";
        }
    
    }elseif ($_POST['mode'] == 'update') {// �������������� ��������
        $title_here = "�������� �������������� ".$name_dt; include("header.html");
        if (isset($_POST['title']))       {$title = $_POST['title'];if ($title == ''){unset($title);}}
        if (isset($_POST['meta_d']))      {$meta_d = $_POST['meta_d']; if ($meta_d == '') {unset($meta_d);}}
        if (isset($_POST['meta_k']))      {$meta_k = $_POST['meta_k']; if ($meta_k == '') {unset($meta_k);}}
        if (isset($_POST['text']))        {$text = $_POST['text']; if ($text == '') {unset($text);}}
        if (isset($_POST['sec']))         {$sec = $_POST['sec']; if ($sec == '') {unset($sec);}}
        if (isset($_POST['id']))          {$id = $_POST['id'];}
        if (isset($_POST['file']))        {$file = $_POST['file']; if ($file == '') {unset($file);}}
        if (isset($_POST['table']))       {$table = $_POST['table']; if ($table == '') {unset($table);}}
        if (isset($_POST['lang']))        {$lang = $_POST['lang']; if ($lang == '') {unset($lang);}}
        if (isset($_POST['id_lang']))     {$id_lang = $_POST['id_lang']; if ($id_lang == '') {unset($id_lang);}}
        if (isset($_POST['name']))        {$name = $_POST['name']; if ($name == '') {unset($name);}}
        if (isset($_POST['parent']))      {$parent = $_POST['parent']; if ($parent == '') {unset($parent);}}
        if (isset($_POST['turnon']))      {
            $turnon = $_POST['turnon']; 
            if ($turnon == '') {
                unset($turnon);
            } else {
                $turnon = ($turnon == 'on') ? 1 : 0;
            }   
        }
        
        if (isset($sec) && isset($title) && isset($meta_d) && isset($meta_k) && isset($text)&& isset($file) && isset($table) && isset($name))
        {
        /* ����� ����� ��� ����� �������� ���������� � ���� */
        $result = mysql_query ("UPDATE ".$tbl_dt." SET parent='$parent',sec='$sec',title='$title', meta_d='$meta_d', meta_k='$meta_k', text='$text', file='$file', cat_tbl='$table', lang='$lang', id_lang='$id_lang', name='$name', turnon='$turnon' WHERE id='$id'");
            if ($result == 'true') { 
                echo "<p align='center'>���������� ".$name_dt." ������� ���������!</p>";
            }else{
                echo "<p align='center'>���������� ".$name_dt." �� ������!</p>";
            }
        }else{
            echo "<p align='center'>�� ����� �� ��� ����������, ������� ���������� ".$name_dt." ����������.</p>";
        }
    }elseif ($_POST['mode'] == 'drop') {// �������� ��������
        if (isset($_POST['id'])) {$id = $_POST['id'];}
        $title_here = "�������� �������� ".$name_dt; include("header.html");
        if (isset($id)){
            $result0 = mysql_query ("SELECT id FROM data WHERE cat='$id'");
            if (mysql_num_rows($result0) > 0) {
                echo "<p align='center'>�������� ".$name_dt." ����������, ��� ��� �� ���� ������� ���������� ������ �� ������ �������! ������� ������� ��.</p>";
            }else{
                $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
                if ($result == 'true') {echo "<p>�������� ".$name_dt." ������� ���������!</p>";}
                else {echo "<p align='center'>�������� ".$name_dt." �� ������!</p>";}
            }
        }else{
            echo "<p align='center'>�� ��������� ������ ���� ��� ��������� id � �������, �������� ".$name_dt." ���������� (������ ����� �� �� ������� ����������� �� ���������� ����).</p>";
        }
    }
}//if (isset($_POST['mode']))
require_once ("footer.html");?>
