<? include ("lock.php");?>
<? $name_dt = "��������� ������"; $tbl_dt = "catalog"; $action = "catalog.php";
// ����� ���� �� 6 ������: 3 � ������� GET � 3 � ������� POST
if (isset($_GET['mode'])){// 1-� ����� 3 ������ GET
    if ($_GET['mode'] == 'new') {// ���������� ������ ��������
        $title_here = "�������� ���������� ".$name_dt; include("header.html");
        echo "<h3 align='center'>���������� ".$name_dt."</h3>";
        ?>
       <form name="form1" method="post" action="<? echo $action ?>">
         <p>
           <label>����� ������� <? echo $name_dt ?><br>
               <textarea name="RU" id="RU" cols="<? echo $ColsOfarea ?>" rows="5"></textarea>
           </label>
         </p>
         <p>
           <label>����� ���������� <? echo $name_dt ?><br>
               <textarea name="EN" id="EN" cols="<? echo $ColsOfarea ?>" rows="5"></textarea>
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
            if (!isset($id))
            {
                $result = mysql_query("SELECT * FROM ".$tbl_dt." ORDER BY id DESC");      
                $myrow = mysql_fetch_array($result);
                do 
                {
                    printf ("<p><a href='%s?id=%s&mode=edit'>%s</a></p>",$action,$myrow["id"],$myrow["RU"]." - ".$myrow["EN"]);
                }
                while ($myrow = mysql_fetch_array($result));
            }else{
                $result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE id=$id");      
                $myrow = mysql_fetch_array($result);
                echo "<h3 align='center'>�������������� ".$name_dt."</h3>";
                ?>
                <form name="form1" method="post" action="<? echo $action ?>">
                 <p>
                   <label>����� ������� <? echo $name_dt ?><br>
                       <textarea name="RU" id="RU" cols="<? echo $ColsOfarea ?>" rows="5"><?echo $myrow['RU']?></textarea>
                   </label>
                 </p>
                 <p>
                   <label>����� ���������� <? echo $name_dt ?><br>
                       <textarea name="EN" id="EN" cols="<? echo $ColsOfarea ?>" rows="5"><?echo $myrow['EN']?></textarea>
                   </label>
                 </p>
                 <input name="id" type="hidden" value="<? echo $myrow['id'] ?>">
                 <input name="mode" type="hidden" value="update">
                 <p>
                   <label>
                   <input type="submit" name="submit" id="submit" value="��������� ��������� <? echo $name_dt ?>">
                   </label>
                 </p>
               </form>
                <?
            }
        }elseif ($_GET['mode'] == 'del') {// �������� ��������
            if (isset($_GET['id'])) {$id = $_GET['id'];}
            $title_here = "�������� �������� ".$name_dt; include("header.html");
            ?>
            <p><strong>�������� �������� <? echo $name_dt ?> ��� �������� </strong></p>
            <form action="<? echo $action ?>" method="post">
            <? 
            $result = mysql_query("SELECT * FROM ".$tbl_dt);      
            $myrow = mysql_fetch_array($result);
            do 
            {
                printf ("<p><input name='id' type='radio' value='%s'><label> %s</label></p>",$myrow["id"],$myrow["RU"]." - ".$myrow["EN"]);
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
        if (isset($_POST['RU']))  {$RU = $_POST['RU'];   if ($RU == '' ) {unset($RU); }}
        if (isset($_POST['EN']))  {$EN = $_POST['EN'];   if ($EN == '' ) {unset($EN); }}
        if (isset($RU) && isset($EN))
        {
            $id = get_id($tbl_dt);
            $result = mysql_query ("INSERT INTO ".$tbl_dt." (id,RU,EN) VALUES ($id, '$RU', '$EN')");
            if ($result == 'true') {echo "<p align='center'>���������� ".$name_dt." ������� ���������!</p>";}
            else {echo "<p align='center'>���������� ".$name_dt." �� ������!</p>";}
        }		 
        else 
        {
            echo "<p align='center'>�� ����� �� ��� ����������, ������� ���������� ".$name_dt." ����������.</p>";
        }
     }elseif ($_POST['mode'] == 'update') {// �������������� ��������
        $title_here = "�������� �������������� ".$name_dt; include("header.html");
        if (isset($_POST['RU']))  {$RU = $_POST['RU'];   if ($RU == '' ) {unset($RU); }}
        if (isset($_POST['EN']))  {$EN = $_POST['EN'];   if ($EN == '' ) {unset($EN); }}
        if (isset($_POST['id']))    {$id = $_POST['id'];}

        if (isset($RU) && isset($EN)){
        $result = mysql_query ("UPDATE ".$tbl_dt." SET RU='$RU', EN='$EN'  WHERE id='$id'");            
        if ($result == 'true') {
                echo "<p align='center'>���������� ".$name_dt." ������� ���������!</p>";
            }else{
                echo "<p align='center'>���������� ".$name_dt." �� ������!</p>";
            }
        }		 
        else 
        {
            echo "<p align='center'>�� ����� �� ��� ����������, ������� ���������� ".$name_dt." ����������.</p>";
        }
    }elseif ($_POST['mode'] == 'drop') {// �������� ��������
        if (isset($_POST['id'])) {$id = $_POST['id'];}
        $title_here = "�������� �������� ".$name_dt; include("header.html");
        if (isset($id)){
            $result = mysql_query ("DELETE FROM ".$tbl_dt." WHERE id='$id'");
            if ($result == 'true') {echo "<p align='center'>�������� ".$name_dt." ������� ���������!</p>";}
            else {echo "<p align='center'>�������� ".$name_dt." �� ������!</p>";}
        }else{
            echo "<p>�� ��������� ������ ���� ��� ��������� id � �������, �������� ".$name_dt." ���������� (������ ����� �� �� ������� ����������� �� ���������� ����).</p>";
        }
    }
}//if (isset($_POST['mode']))
include_once ("footer.html");?>
