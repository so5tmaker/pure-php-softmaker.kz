<?
$name_dt = "�������";
$tbl_dt = "banners";
?>
<? include ("../lock.php");?>
<? $title_here = "�������� ���������� ".$name_dt; include("../header.html");?>
<form name_dt="form1" method="post" action="add_banner.php">
 <p>
   <label>������� ������ �� ������ <? echo $name_dt ?><br>
       <input type="text" name="href" id="href" size="<? echo $SizeOfinput ?>" >
     </label>
 </p>
 <p>
   <label>������� ������ �� ����������� <? echo $name_dt ?><br>
   <input type="text" name="imgsrc" id="imgsrc" size="<? echo $SizeOfinput ?>">
   </label>
 </p>
 <p>
   <label>������� ���������� ����� ��� ����������� <? echo $name_dt ?><br>
   <input type="text" name="alt" id="alt" size="<? echo $SizeOfinput ?>">
   </label>
 </p>
 <? $size = getimagesize("http://softmaker.kz/img/5.jpg");
        print_r($size);
        $size[3] //=> width="570" height="128"
 ?>
 <p>
 <label>������� ���������� <? echo $name_dt ?><br>
   <input type="text" name="author" id="author">
   </label>
 </p>
  
 <p>
   <label>�������� ��������� <? echo $name_dt ?><br>
   
   <select name="cat">
   
   <?
   
        $result = mysql_query("SELECT title,id FROM categories",$db);

        if (!$result)
        {
            echo "<p>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@profcentre.kz. <br> <strong>��� ������:</strong></p>";
            exit(mysql_error());
        }
        $num_rows = mysql_num_rows($result);
        if ($num_rows > 0)
        {
            $myrow = mysql_fetch_array($result);
            do
            {
            printf ("<option value='%s'>%s</option>",$myrow["id"],$myrow["title"]);
            }
            while ($myrow = mysql_fetch_array($result));
        }
        else
        {
//            echo "<p>��� ���������� ".$name_dt." ����� �������� ���� �� ���� ��������� � �������!</p>";
//            echo "<p>���������� �� ������� �� ����� ���� ��������� � ������� ��� �������.</p>";
//            exit();
        }
        ?>

   </select>
   </label>
 </p>
 <?if ($num_rows == 0) echo "<p style='color: red' ><label>��� ���������� ".$name_dt." ����� �������� ���� �� ���� ��������� � �������!</label></p>";?>
<!-- <p>��������� � ��������� ������?<br>
   <label><strong>��</strong>
   <input type="radio" name="secret" id="secret" value="1">
   </label>
   
    <label><strong>���</strong>
   <input type="radio" checked name="secret" id="secret" value="0">
   </label>
 </p>-->
 <input name="name_dt" type="hidden" value="<? echo $name_dt ?>">
 <input name="tbl_dt" type="hidden" value="<? echo $tbl_dt ?>">
 <p>
   <label>
   <input type="submit" name="submit" id="submit" value="<? echo "��������� ".$name_dt." � ����" ?>">
   </label>
 </p>
</form>
<? include("../footer.html");?>
