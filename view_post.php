<? 
$myrow = $data;
$id_lang = $myrow["id_lang"];
$cat = $myrow["cat"];
$title = $myrow["title"];
$new_view = $myrow["view"] + 1;
$update = mysql_query ("UPDATE data SET view='$new_view' WHERE id='$id_score'",$db);

$myrow_cat = $categories;
if (isset($cat)) {
    //    ����� �������� ���������� �� ���������
    $sec = $myrow_cat["sec"];
    $cat_title = $myrow_cat["title"];
    $cat_name1 = $myrow_cat["name"];
    $title_cat = get_foreign_equivalent("������� ��������� -")." ".$cat_title;
}

// �������� ���� �� ����
//get_cache();
$file = IsItPost($myrow[file]);
if ($file) {
    $n=3;
} else {
    $n=2;
}
$text="";$comment = TRUE;
require_once ("header.html");

if ($title_cat != "") {
    show_breadcrumbs(2, $cat_name1);
}

?>
<script type="text/javascript">
<!--
 function openblock(item1) {
        var text=document.getElementById(item1);
        if (text.style.display==='none')
        {
                text.style.display='block';
        }
        else
        {
                text.style.display='none';
        }
 }
 function closeblock(item1) {
        var text=document.getElementById(item1);
        text.style.display='none';
 }
 //-->
</script>
<?

printf ("
        <h1 class='post_title2'>
        %s
        </h1>"
        ,$myrow["title"]);

$view = get_foreign_equivalent("����������");

$comments = "<a href='#comm'>".get_foreign_equivalent("������������:")." ".
                                quant_comment($myrow["id"],$myrow["cat"])."</a>";
$date = $myrow[date];
echo "<div class='post_top'>
        <span class='commentp'>
            $date | $view: $myrow[view] | $comments
        </span>
      </div>";

if (!$file) {
    codbanner(2);
} else {
    codbanner(3);
}
//$advs->getBro(1);
$advs->show('top', $Link);
// ������� ������� �� ���� ���������
//require_once 'blocks/view_banners.php';
    
$str1 = array("<PRE","/PRE>");
$str2 = array("<PRE","/PRE>");

require_once 'faq.php';

//������� ������������� ������ ���������� �����
show_social_buttons();

//������� ������� ����� �������� �� ������ �� �����
show_form_subscribe_by_mail();

//������� ������� ������ �� ������ �����
echo show_other_lang_post();

//������� ������� ������ ������ ������� ��������� � ����������
echo show_mini();
//������� ������� ������ ������ ������� ���������
//        show_list();

//������� ������� ������ ������ �� �������� ��������� � ����������
echo show_cat_mini($sec);
//������� ������� ������ ������ �� �������� ���������
//        show_cat_list($sec);

//������� ������� ������ ������ �� ��������� ������� � ����������
echo show_sec_mini($sec);
//������� ������� ������ ������ �� ��������� �������
//        show_sec_list($sec);
?>

<?
echo "<a id='comm' name='comm'></a><p class='post_comment'>".
        get_foreign_equivalent("����������� � ���� �������").":</p>";	
$email_caption = "";
if($root) 
{ 
    $email_caption = " e-mail: ";
}

// �������� �� ������� jquery changecomm()
$POST = filter_input_array(INPUT_POST);
$data = $POST['delcom'];
if (isset($data)){
    $pieces = explode("-", $data);
    $id   = $pieces[0];// id ����������� � ������� comments
    $mode = $pieces[1];// ����� 1-�������, 2-�������������, 3-��������
    $text = $pieces[2];// ����� ���������
    if ($mode == '1') {
        $db1->delete('comments', 'id='.$id);
    }elseif ($mode == '3') {
        $text = iconv('UTF-8', 'windows-1251',trim($text));
        $db1->update(array("text" => "'$text'"), 'comments', 'id = '.$id);
    }
    
//    echo "<p class='post_comment'>".
//        get_foreign_equivalent("����������� � ���� �������".$mode).":</p>";
}


function edit_comm($id) {
    global $root, $SCRIPT;
    if($root)    
    { 
        ?>
        <div id='btn<? echo $id ?>' >
            <input class='commentbtn' type='submit' value='�������' onClick="changecomm('<? echo $id."-1" ?>', '<? echo $_SERVER['REQUEST_URI'] ?>');">
            <input class='commentbtn' type='submit' value='��������' onClick="changecomm('<? echo $id."-2" ?>', '<? echo $_SERVER['REQUEST_URI'] ?>');"><br />
        </div>
        <?
    }
}

$result3 = mysql_query ("SELECT * FROM comments WHERE post='$id_score' AND cat='$cat' AND mailonly = '0' ORDER BY date",$db);
if (mysql_num_rows($result3) > 0)
{
    $myrow3 = mysql_fetch_array($result3);
    do 
    {
        if ($email_caption != "") 
        {
            $mail3 = str_replace("@", "_", $myrow3["email"]);
            $mail3 = ($myrow3[notify] == '1') ? "<span style='COLOR: blue'>$mail3</span>" : $mail3;
            $email_new = "<br>".$email_caption.$mail3;
        }    
        printf ("<div id=$myrow3[id] class='post_div'><p class='post_comment_add'>".get_foreign_equivalent("����������� �������(�)").": <strong>%s</strong></p>
        <p id=p$myrow3[id]>%s</p></div>",$myrow3["author"].$email_new, stripslashes($myrow3["text"]));
        // ��������� ������ � ����������� ��������� �������. <br> ����: <strong>%s</strong> , $myrow3["date"]
        edit_comm($myrow3[id]);
    }
    while ($myrow3 = mysql_fetch_array($result3));
} else {
    echo "<p  align='center'>".get_foreign_equivalent("��� �� ������ �����������, �� ������ ����� ������.")."</p>";
}

// ����� ������ ���������� ������� (��������) � ������� � ���������� � ������� ���� � ��������� �������
$result4b = mysql_query ("SELECT COUNT(*) FROM comments_setting",$db);
$sum = mysql_fetch_array($result4b);
if (!$sum)
{   // ������� ������, ���� ������ �� ������
    $sum1 = 1;
}
else
{ // ������� ���� � ��������� �������
    $sum1 = rand (1, $sum[0]);
}

$result4 = mysql_query ("SELECT img FROM comments_setting WHERE id='$sum1'",$db);
$myrow4 = mysql_fetch_array($result4);

require_once ("mail/comment.php");
if (!$file) {
    codbanner(7);
} else {
    codbanner(8);
}
require_once ("footer.html");

// ������ ����� � ���
//set_cache();

?>