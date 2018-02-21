<?php  
require_once '../blocks/global.inc.php';
require_once ('config.php');

$deep = '../';

$error = "";
$no_error = "";

if (isset($_POST['author']))
{
    $author = $_POST['author'];
}

if (isset($_POST['email']))
{
    $email = $_POST['email'];
}

if (isset($_POST['text']))
{
    $textmail = $_POST['text'];
}

if (isset($_POST['$sum1']))
{
    $sum1 = $_POST['$sum1'];
}

if (isset($_POST['sub_mail']))
{
    $sub_mail = $_POST['sub_mail'];
}

if (isset($_POST['id']))
{
    $id = $_POST['id'];
}

if (isset($_POST['img']))
{
    $img = $_POST['img'];
}

if (isset($sub_mail))
{

    //���������������� ���������� ��� �������� �����
    $success = true;
    
    //�������� ���������� $_POST
    $waserror = $_POST['error'];
    
    if (isset($author)) {trim($author);}
    else {$author = "";}

    if (isset($textmail)) {trim($textmail);   }
    else {$textmail = "";}

    if (empty($author))
    {
        $error .= get_foreign_equivalent("�� �� ����� ���!")."<br/> \n\r";
        $success = false; 
    }
    
    if (empty($textmail))
    {
        $error .= get_foreign_equivalent("�� �� ����� ����� �����������!")."<br/> \n\r";
        $success = false;
    }
    
    $is_ok = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email);
	
    if ($is_ok==0)
    {
        $error .= get_foreign_equivalent("�� ����� �������� ����� ����������� �����.")."<br/> \n\r";
        $success = false; 
    }

    $author = stripslashes($author); // ����������� �� / ����� ���������
    $textmail = stripslashes($textmail);
    $author = htmlspecialchars($author); // ����������� ������������� ������� ������ ������ ����� � ����������� ���������
    $textmail = htmlspecialchars($textmail);

    $result = mysql_query ("SELECT sum FROM comments_setting  WHERE id='$id'",$db);
    $myrow = mysql_fetch_array($result);

    if ($sum1 != $myrow["sum"])
    {
        $error .= get_foreign_equivalent("�� ����� �������� ����� ���� � ��������!")."<br/> \n\r";
        $success = false; 
    }
    
    $no_one_hour_user = get_foreign_equivalent("�� �� ������ ���������� ������ 1 �������� � 5 �����!");
    if (check_submit($_POST, 300, 'feedback') == 0 AND $waserror =="" AND $waserror !=$no_one_hour_user){
        $error .= $no_one_hour_user."<br/> \n\r";
        $success = false;
    }
    
    if($success)
    {

        $subject = "�������� ����� - ".$author;
        $message = "������ ������ �� �������� ����� - ".$email."<br>������ �������(�): <strong>".$author."</strong><br> ����� ������: ".$textmail."";
        
        if (!$result)
        {
            $error = get_foreign_equivalent("<p>�������� ������ �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>");
            $error .= mysql_error();
        }
        
        $ret = pearmail($email,$author,$subject,$message, '', '', 'feedback');
//        $ret = mail_function($email, $author, $subject, $message);
        if ($ret <> '')
        {
            $error = get_foreign_equivalent("<p>�������� ������ �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>").$ret;
            $success = false;
        }
        if($success){
            $no_error = get_foreign_equivalent("���� ������ ������� ����������!");
            $sum1 = ""; $author = ""; $email = ""; $textmail = "";
        }
    }
} 

//������ *********************** ������� ��������� �������� � �.�. *************
$result = mysql_query("SELECT title,meta_d,meta_k,text FROM settings WHERE (page='about') AND (lang ='".$lang."')",$db);

echo_error($result);

if (mysql_num_rows($result) > 0)
{
    $myrow = mysql_fetch_array($result); 
}else{
    echo_no_records();
}
//����� *********************** ������� ��������� �������� � �.�. **************

//������ *********************** ������� �������� ******************************
$result4a_ = mysql_query ("SELECT COUNT(*) FROM comments_setting",$db);
$sum = mysql_fetch_array($result4a_);
if (!$sum)
{   // ������� ������, ���� ������ �� ������
    $elem = 1;
}
else
{ // ������� ���� � ��������� �������
    $elem = rand (1, $sum[0]);
}
// ����� ������ ���������� ������� (��������) � ������� � ���������� � ������� ���� � ��������� �������
$result4a = mysql_query("SELECT img FROM comments_setting WHERE id='$elem'",$db);

echo_error($result4a);

if (mysql_num_rows($result4a) > 0)
{
    $myrow4a = mysql_fetch_array($result4a);
    $img = $myrow4a["img"];
}
//����� *********************** ������� �������� *******************************

$n=4;
include_once ($DIR."header.html");
show_breadcrumbs(1, '', '.php');

echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
//echo ($no_error != "") ? "<ul class='error'><li>".$no_error."</li></ul>" : "";
echo ($no_error != "") ? "<p align='center' class='post_comment'>".$no_error."</p>" : "";

if ($lang == 'RU')
{
    $title ='����� �������� ����� c ������� ����� SoftMaker.kz';
}else{
    $title = 'Contact the author of this site SoftMaker.kz';
} 
$advs->show('top');
?>
<h1 class='post_title2'><? echo $title; ?></h1>
<p class='post_comment'><? echo get_foreign_equivalent("�������� ������"); ?>:</p>
<form class="formmail" action="feedback.php" method="post" name="form_mail">
    <p><label><? echo get_foreign_equivalent("���� ���"); ?>: <br> </label>
        <input value="<? echo $author ?>" class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="author" type="text" size="90%" maxlength="30">
    </p>
    <p><label><? echo get_foreign_equivalent("��� e-mail"); ?>: <br> </label><br>
        <input value="<? echo $email ?>" class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="email" type="text" size="90%" maxlength="30">
    </p>
    <p><label><? echo get_foreign_equivalent("����� ������"); ?>: <br> 
            <textarea class="comarea" onblur="this.className='comarea'" onfocus="this.className='comareaact'" name="text" cols="67%" rows="6"><? echo $textmail?></textarea>
        </label></p>
    <p><? echo get_foreign_equivalent("������� ����� ����� � ��������"); ?><br>
      <img src="<? echo $deep.$img; ?>" width="80" height="40"><br>
      <? $sum_value =  (isset($sum1)) ? 'value="'.$sum1.'"' : ""; ?>
      <input <? echo $sum_value ?> class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" style='margin-bottom:5px;' name="$sum1" type="text" size="5" maxlength="5"></p>
      <input name="id" type="hidden" value="<? echo $elem; ?>">
      <input name="img" type="hidden" value="<? echo $img; ?>">
      <input name="error" type="hidden" value="<? echo $error; ?>" />
    <p><input class="formbutton" name="sub_mail" type="submit" value="<? echo get_foreign_equivalent("���������"); ?>"></p>
</form>
<!--<p class="social"><? echo get_foreign_equivalent("�� � ��������"); ?>:</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="https://twitter.com/SoftmakerKz">
https://twitter.com/SoftmakerKz</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="https://plus.google.com/+SoftmakerKz">
https://plus.google.com/+SoftmakerKz</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="http://www.liveinternet.ru/users/softmakerkz/">
http://www.liveinternet.ru/softmakerkz/</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="http://softmakerkz.livejournal.com/">
http://softmakerkz.livejournal.com/</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="http://vk.com/softmakerkz">
http://vk.com/softmakerkz</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="http://my.mail.ru/mail/softmaker.kz/">
http://my.mail.ru/softmaker.kz/</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="http://www.odnoklassniki.ru/profile/562180924611">
http://www.odnoklassniki.ru/softmaker.kz</a>
</p>
<p><a rel=nofollow target=blank_ title= "��������� � ����� ����" href="http://softmakerkz.blogspot.com/">
http://softmakerkz.blogspot.com/</a>
</p>-->
<? //phpinfo()
// echo "<a href=".$_SERVER['REQUEST_URI'].">".$_SERVER['REQUEST_URI']."</a>"
//echo getcwd().'<br>';
//echo dirname(__FILE__);
//echo $url;
$advs->show('bottom');
include_once ($DIR."footer.html");?>
