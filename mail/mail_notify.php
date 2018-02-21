<?php
if (!isset($fromglobal)){
    require_once '../classes/User.class.php';
    require_once '../classes/UserTools.class.php';
}

require_once $DIR.'/mail/config.php';

function mail_body($newUser,$arr)
     {
        global $lang; // , $url
        /* ���������� */
        $to  = $newUser->username." <".$newUser->email.">";
        $address = $newUser->email;

//        $url = 'http://www.softmaker.kz/';
        $url = get_lang_link_();

        /* ����\subject */
        $subject = get_foreign_equivalent("�������� ����� ������ � �����")." ".$url;

        /* ��������� */
        if (strtolower($lang) == 'ru'){
            $message = "
            ������������, ".$newUser->username."!

            <p>�� ��� ������ ������� ���� ��� �� ���� �� ����� �����, �� ������ <a href='".$url."'>".$url."</a>, ��������� ����� ������!</p>

            ======================================================================";
            foreach ($arr as $v) {
                if ($v[date]>$newUser->last_mail) {
                    $message = $message." ".$v[link];
                }
            }
            $message = $message.
            "======================================================================

            <p>���� �� �� ���������, � ��� ���� ���� � ������ �������������� ��� ���������!</p>

            <p>���� �� ������ �������� ��������, �� � ���������� ������������ �� ������
            ���������� ������ �������� ������:</br>
            �".get_foreign_equivalent("���������� ���� � ������ ����� ������ � ��������� ����������")."�.</p>
            
            <p>����� �� ������ <a href='".$url."not_notify_me.php?note=".$newUser->id."&uid=".$newUser->hashedPassword."#mdl'>���������� �� ��������</a>.</p>

            <p>�� ����� �������� �� ��� ������, ��� ������������� �������������.</p>

            <p>���� ��� ����� ��������� � ������� �����, �������� ��� ����� �������� �����: <a href='".$url."mail/feedback.php'>".$url."mail/feedback.php</a></p>

            <p>� ���������� �����������,</br>
            ������������� ����� <a href='".$url."'>".$url."</a></br>
            --</p>
            ";
        } else {
            $message = "
            <p>Dear ".$newUser->username.",

            <p>New articles were written on our website <a href='".$url."'>".$url."</a>.</p> 
            ======================================================================";
            foreach ($arr as $v) {
               if ($v[date]>$newUser->last_mail) {
                    $message = $message." ".$v[link];
                }
            }

            $message = $message.
            "======================================================================

            <p>Ignore this message if you do not understand the letter!</p>
            
            <p>If you want to change subscribtion, modify your preferences by clicking on flags:
            </br>
            �".get_foreign_equivalent("���������� ���� � ������ ����� ������ � ��������� ����������")."�.</p>
            <p>OR</p>
             
            <p><a href='".$url."not_notify_me.php?note=".$newUser->id."&uid=".$newUser->hashedPassword."#mdl'>Unsubscribe.</a></p>


            <p>Please do not reply to this message via e-mail.</br>
            This address is automated, unattended, and cannot help with questions or requests.</p>

            <p>If you would like to contact the author of this site, please, use this feedback form: <a href='".$url."mail/feedback.php'>".$url."mail/feedback.php</a></p>

            <p>All the best,</br>
            EN.SOFTMAKER.KZ team</br>
            --</p>
            ";
        }
        
        /* ��� �������� HTML-����� �� ������ ���������� ����� Content-type. */

        $headers .= "Content-type: text/html; Charset=windows-1251\r\n";

        /* �������������� ����� */
        $headers .= "From: www.softmaker.kz <info@softmaker.kz>\r\n";

        /* � ������ �������� �� */
         return array($subject, $message, $address);
     } // mail_body($newUser,$arr)

// �������� ����� ������� ��� ��������� ������������� ��� ������������ �������� ����
function get_mdate($sql,$max=TRUE)
{
    $max_min = (max) ? "MAX" : "MIN";
    $sql_MAX1 = "SELECT $max_min(mdate) as mdate FROM (";
    $sql_MAX2 = ") as ".$max_min."date";
    $sql_MAX  = $sql_MAX1.$sql.$sql_MAX2;

    $res = mysql_query($sql_MAX); // ������� ������ ��� ��������
    $num_rows = mysql_num_rows($res);
    // ���� ���������� ������� �� ����� ����
    if ($num_rows==0)
    {
        return date("Y-m-d");
    }
    $myrow = mysql_fetch_array($res);
    do
    {
        return ($myrow[mdate]==null) ? date("Y-m-d") : $myrow[mdate];
    }
    while ($myrow = mysql_fetch_array($res));

    return date("Y-m-d");
} // get_mdate

function send_mails()
{
    global $lang, $rus, $max_mails, $email_quant, $db1; // , $url
//    $url = "http://www.softmaker.kz/";
    $url = get_lang_link_();
    $arr_articles = array ();// ������� ��� �������� �� �������
    // ���������� ���������� ���� ����� ���������� ������
    $day_quant_after_pub = 7; 
    // ������� ������ � �������
    $sql = "SELECT a.id,a.name,a.cat as mcat,a.title, b.date as mdate, b.question, b.id as faq_id, 'articles'\n"
        . " FROM data a, faq b\n"
        . " WHERE a.id = b.post AND lang = '".$lang."' AND (TO_DAYS(NOW()) - TO_DAYS(b.date))<$day_quant_after_pub\n"
        . "union\n"
        . "SELECT a.id, a.name, a.cat as mcat, a.title, a.date as mdate, 'null', 0, 'articles'\n"
        . "FROM data a\n"
        . "WHERE lang ='".$lang."' AND (TO_DAYS(NOW()) - TO_DAYS(a.date))<$day_quant_after_pub\n"; 
//        . "UNION\n"
//        . "SELECT c.id, c.name, c.cat as mcat, c.title, c.date as mdate, 'null', 0, 'files'\n"
//        . "FROM files c\n"
//        . "WHERE lang='".$lang."' AND (TO_DAYS(NOW()) - TO_DAYS(c.date))<$day_quant_after_pub";   

    // �������� ����� ������� ��� ��������� ������ � ������ ���������
    $sql_cat1 = "SELECT cat.name as catname, cat.sec as sec, d.* FROM (";
    $sql_cat2 = ") as d, categories as cat WHERE cat.id=d.mcat";
            
    $sql_cat = $sql_cat1.$sql.$sql_cat2;

    $MAX = get_mdate($sql);
//    $MIN = get_mdate($sql, FALSE);

    $res_art = mysql_query($sql_cat); // ������� ������ ��� ��������
    // ���� ���������� ������� �� ����� ����
    if (mysql_num_rows($res_art)==0)
    {
        return 'stop1';
    }
    $myrow_art = mysql_fetch_array($res_art);
    do
    {
        if ($myrow_art["question"] == 'null'){
            $title = $myrow_art["title"];
            $faq_id = "";
        } else {
            $title = $myrow_art["question"];
            $faq_id = "#".$myrow_art["faq_id"];
        }   
//      $cat_name = get_fld_by_id($myrow_art["cat"], 'categories', 'name');
        $cat_name = $myrow_art[catname];
        
        // ������� �������� �������
        $result = $db1->select('sections', "id='$myrow_art[sec]' AND lang='$lang'", "name");
        
        $link = "<p><a href='".$url.$result[name]."/".$cat_name."/".$myrow_art["name"].".html".$faq_id."'>".$title."</a></p>";
        $arr_articles[] = array("link" => $link, "date" => $myrow_art[mdate]);
        
    }
    while ($myrow_art = mysql_fetch_array($res_art));

    // ������� �������������
    $res_usr = mysql_query("SELECT id,username,join_date,active,
            last_visit,last_mail,cats FROM users 
            WHERE (active = '1')AND(cats <> '')AND
            (last_mail < '$MAX' OR last_mail = '0000-00-00') AND (lang='".$lang."') 
            ORDER BY username");
    // ���� ���������� ������� �� ����� ����
    if (mysql_num_rows($res_usr)==0)
    {
        return 'stop2';
    }
    $max_mails_here = 100000; $letters_quant=1;
    $myrow_usr = mysql_fetch_array($res_usr);
    do
    {
        if ($max_mails_here<$letters_quant) {
            return 'stop3';
        }
        if (file_exists('../admin/stop.txt')) {return 'stop';}
        $tools = new UserTools();
        $user = $tools->get($myrow_usr["id"]);
        $ret = mail_body($user,$arr_articles);

        $subject = $ret[0];
        $message = $ret[1];
        $email = $ret[2];
//      $email = 'softmaker.kz@gmail.com';
        $deep_mail = '../';
        $ret = pearmail('','',$subject,$message,$email,$deep_mail,'notify');
        if ($ret == '')
        {
            $letters_quant = $letters_quant + 1;
            $user->last_mail = date("Y-m-d");
            $user->save();
        } else {
            Return 'stop4';
        }
    }
    while ($myrow_usr = mysql_fetch_array($res_usr));
    set_stop('../admin/');
}
?>
