<?php 
include ("lock.php");
if (isset($_GET['id'])) {$id = $_GET['id'];}
if (isset($_GET['name_dt'])) {$name_dt = $_GET['name_dt'];}
if (isset($_GET['tbl_dt'])) {$tbl_dt = $_GET['tbl_dt'];}
if (isset($_GET['sec_dt'])) {$sec_dt = $_GET['sec_dt'];}
?>
<? $title_here = "�������� �������������� ".$name_dt; include("header.html"); ?>
<? 
if (!isset($id))
{
    $args = "name_dt=".$name_dt."&tbl_dt=".$tbl_dt."&sec_dt=".$sec_dt;
    $result = mysql_query("SELECT title,id FROM ".$tbl_dt);
    $myrow = mysql_fetch_array($result);
    do
    {
    printf ("<p><a href='edit_data.php?id=%s&%s'>%s</a></p>",$myrow["id"],$args,$myrow["title"]);
    }
    while ($myrow = mysql_fetch_array($result));
}
else
{
    $result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE id=$id");
    $myrow = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT id,title FROM categories WHERE sec='$sec_dt'");
    $myrow2 = mysql_fetch_array($result2);

    $count = mysql_num_rows($result2);
    echo "<h3 align='center'>�������������� ".$name_dt."</h3>";
    echo "<form name='form1' method='post' action='update_data.php'>
 <p>�������� ��������� ��� ".$name_dt."<br><select name='cat' size='$count'>";
    do
    {
        if ($myrow['cat'] == $myrow2['id'])
        {
            printf ("<option value='%s' selected>%s</option>",$myrow2["id"],$myrow2["title"]);
        }
        else
        {
            printf ("<option value='%s'>%s</option>",$myrow2["id"],$myrow2["title"]);
        }
    }
    while ($myrow2 = mysql_fetch_array($result2));
    echo "</select></p>";
//    echo "<p>��������� � ��������� ������?<br>
//           <label><strong>��</strong><input type='radio'";
//    if ($myrow['secret'] == 1) { echo " checked ";}
//    echo "name='secret' id='secret' value='1'></label><label><strong>���</strong>
//    <input type='radio'";
//    if ($myrow['secret'] == 0) { echo " checked ";}
//    echo "name='secret' id='secret' value='0'></label></p> ";
    print <<<HERE
         <p>
           <label>������� �������� $name_dt<br>
             <input value="$myrow[title]" type="text" name="title" id="title" size="$SizeOfinput">
             </label>
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
           <label>������� ���� ���������� $name_dt<br>
           <input value="$myrow[date]" name="date" type="text" id="date" size="8px" value="2007-01-27">
           </label>
         </p>
         <p>
           <label>������ ������� �������� $name_dt � ������ �������
           <textarea name="description" id="description" cols="$ColsOfarea" rows="20">$myrow[description]</textarea>
           </label>
         </p>
         <p>
           <label>������� ������ ����� $name_dt � ������
           <textarea name="text" id="text" cols="$ColsOfarea" rows="30">$myrow[text]</textarea>
           </label>
         </p>
         <p>
           <label>������� ������ $name_dt<br>
           <input value="$myrow[author]" type="text" name="author" id="author">
           </label>
         </p>
	 <!--<p>
           <label>������� ��� ����� ���������<br>
           <input value="$myrow[mini_img]" type="text" name="img" id="img">
           </label>
         </p>-->
 	 <input name="id" type="hidden" value="$myrow[id]">
         <p>
         <input name="name_dt" type="hidden" value="$name_dt">
         <input name="tbl_dt" type="hidden" value="$tbl_dt">
           <label>
           <input type="submit" name="submit" id="submit" value="��������� ���������">
           </label>
         </p>
       </form>
HERE;
}
?>
<? include("footer.html");?>