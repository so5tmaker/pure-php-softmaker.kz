<?php
//resetpassword.php
require_once '../blocks/global.inc.php';
require_once 'config.php';

function generate_password()  
  {  
    $number = rand (8, 16). 
//    $arr = array('a','b','c','d','e','f',  
//                 'g','h','i','j','k','l',  
//                 'm','n','o','p','r','s',  
//                 't','u','v','x','y','z',  
//                 'A','B','C','D','E','F',  
//                 'G','H','I','J','K','L',  
//                 'M','N','O','P','R','S',  
//                 'T','U','V','X','Y','Z',  
//                 '1','2','3','4','5','6',  
//                 '7','8','9','0','.',',',  
//                 '(',')','[',']','!','?',  
//                 '&','^','%','@','*','$',  
//                 '<','>','/','|','+','-',  
//                 '{','}','`','~');  
    $arr = array('a','b','c','d','e','f',  
                 'g','h','i','j','k','l',  
                 'm','n','o','p','r','s',  
                 't','u','v','x','y','z',  
                 'A','B','C','D','E','F',  
                 'G','H','I','J','K','L',  
                 'M','N','O','P','R','S',  
                 'T','U','V','X','Y','Z',  
                 '1','2','3','4','5','6',  
                 '7','8','9','0','.');  
    // ���������� ������  
    $pass = "";  
    for($i = 0; $i < $number; $i++)  
    {  
      // ��������� ��������� ������ �������  
      $index = rand(0, count($arr) - 1);  
      $pass .= $arr[$index];  
    }  
    return $pass;  
  }  

function sendmail($newUser){
    global $lang, $url, $password;
   
    /* ����\subject */
    $subject = get_foreign_equivalent("����� ������ �� �����")." ".$url;
    $site = $url.'index.php';
     /* ��������� */
    $message = "
    <p>������������, ".$newUser->username."!</p>

    <p>�� ����� �� ������: <a href='".$url."'>".$url."</a> ��������� ����� ������,<br>
    � ������� ��� ������ ��� ����������� ����� (e-mail).</p>

    <p>��� ���������� ��������������� ����� ���� ������� ��������� ��� ������������:</p>

    =================================== <br>
    ��� ������������ (login): ".$newUser->username." <br>
    ����� ������ (password): ".$password." <br>
    ===================================

    <p>���� �� �� ���������, � ��� ���� ���� � ������ �������������� ��� ���������!</p>

    <p>�� ����� �������� �� ��� ������, ��� ������������� �������������.</p>

    <p>���� ��� ����� ��������� � ������� �����, �������� ��� ����� �������� �����: <a href='".$url."about.php'>".$url."about.php</a></p>

    <p>� ���������� �����������,<br>
    ������������� ����� <a href='".$url."'>".$url."</a><br>
    --</p>
    ";
   
    $messageEN = "
    <p>Dear ".$newUser->username.",</p>

    <p>Thank you for registering at the <a href='".$url."'>".$url."</a>.<br>

    <p>You recently changed your password on a site ".$url."</p>

    =================================== <br>
    Your User name is: ".$newUser->username." <br>
    Your new password is: ".$password." <br>
    ===================================

    <p>Please do not reply to this message via e-mail.<br>
    This address is automated, unattended, and cannot help with questions or requests.</p>

    <p>If you would like to contact the author of this site, please, use this feedback form: <a href='".$url."mail/feedback.php'>".$url."mail/feedback.php</a></p>

    <p>All the best,<br>
    SOFTMAKER team<br>
    --</p>
    ";
    $message = (strtolower($lang) == 'ru') ? $message : $messageEN;
    
    /* � ������ �������� �� */
    $ret = pearmail('','',$subject,$message,$newUser->email,'','resetpassword');
    if ($ret <> '')
    {
        return get_foreign_equivalent("<p>�������� ������ �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>").$ret;
    }
    return '';
}
//�������������� php ����������, ������� ������������ � �����
$username = "";
$password = "";
$email = "";
$error = "";
$no_error = "";

//���������� $_POST � ������������� ������� ���������� 
//���������� �������� ������� � ������� ������ HTTP POST. 
//�������� ������� ���� �������� � $_POST ��������� �������� 
//���� � �������� �����. ������ ����� �� ��������� ���� 
//�� ������ ������ "���������". �� ��������� $_POST['submit-form'] 
//� ������� ������� isset(), ��������� �submit-form� ���� �������� ������ �����.

//��������� ���������� �� �����
if(isset($_POST['sub_mail'])) {

//�������� ���������� $_POST
//$password = 'apple';
$password = generate_password();
$email = trim($_POST['email']);
$answer = $_POST['answer'];
$sum1 = $_POST['sum1'];
$waserror = $_POST['error'];

//��� ������ �� �����, ��� ������ ������, ����� �������� �������� �����. 
//�� ����� ������������ ����� UserTools ��� �������� ����������� 
//����� ������������. ���� ����� ��� ��� ����������, 
//�� ����� ������ $success �� false � ������� ��� ������ � $error. 


//���������������� ���������� ��� �������� �����
$success = true;
$userTools = new UserTools();

// �������� ����� � ��������
$resultsum = mysql_query ("SELECT sum FROM comments_setting  WHERE id='$sum1'",$db);
$myrowsum = mysql_fetch_array($resultsum);

if ($answer <> $myrowsum["sum"])
{
    $error .= get_foreign_equivalent("�� ����� �������� ����� ���� � ��������!")."<br/> \n\r";
    $success = false;
}

// ��������� ���� �� ������������ � ����� ������� ����������� �����
if(!$userTools->checkEmailExists($email))
{
    $error .= get_foreign_equivalent("������ ������ ����������� ����� �� ����������!")."<br/> \n\r";
    $success = false;   
}


// �������� ��������� �� ������ ����� ����������� �����
$is_ok = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email);
if ($is_ok==0)
{
$error .= get_foreign_equivalent("�� ����� �������� ����� ����������� �����!")."<br/> \n\r";
$success = false;
}

$no_one_hour_user = get_foreign_equivalent("�� �� ������ �������� ������ 1-�� ������ � ������!");
if (check_submit($_POST, 60, 'resetpassword') == 0 AND $waserror =="" AND $waserror !=$no_one_hour_user){
    $error .= $no_one_hour_user."<br/> \n\r";
    $success = false;
}

if($success)
{
    $hashedPassword = md5($password); //����������� ������ ��� ��������
    $result_ut = mysql_query("SELECT * FROM users WHERE email='$email'");
    if(mysql_num_rows($result_ut) > 0)
    {
        $myrow_ut = mysql_fetch_array($result_ut);
        do
        {
            //�������� ��������� ������������ � ��
            $user = $userTools->get($myrow_ut[id]);
            $user->hashedPassword = $hashedPassword; 
            $user->save();
            $ret = sendmail($user);
            if ($ret <> ''){
                $error = get_foreign_equivalent("����� ������ �� ��� ��������� �� ���� �����!");
                $error .= $ret;
                $success = false;
                break;
            }
        }
        while ($myrow_ut = mysql_fetch_array($result_ut));
        if($success)
        { 
            $no_error = get_foreign_equivalent("����� ������ ��������� �� ���� �����!");
        }
    }else {
        $error = get_foreign_equivalent("����� ������ �� ��� ��������� �� ���� �����!");
    }   
  }
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

$resultimg = mysql_query ("SELECT img FROM comments_setting WHERE id='$sum1'",$db);
$myrowimg = mysql_fetch_array($resultimg);

$n=0;$text='';$title = get_foreign_equivalent("����� ������ �� �����");
include_once ($DIR."header.html");
// ������� �� Google
?>


<p class='post_title2'><? echo get_foreign_equivalent("����� ������ �� �����")." ".$softmaker_link; ?> </p>
<?$advs->show('top');?>
<p><? echo "<p align=center>".get_foreign_equivalent("��� ����� ������ ����������� ����� ������������������ �� ����� �����, ����� ��������� ����� ������. ����� ������ ����� ������ �� ���� �����.")."<p>"; ?>
</p><br>
<?
//���� ����� �� ���������� ��� �� ������ ��������, ����� �������� ����� �����
    echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
//    echo ($no_error != "") ? "<ul class='error'><li>".$no_error."</li></ul>" : "";
    echo ($no_error != "") ? "<p align='center' class='post_comment'>".$no_error."</p>" : "";
?>
<form action="<? echo "resetpassword.php" ?>" method="post">
      
 <table width="500" border="0" align="center" class="regtable">
  <tr>
    <td><p><strong>Email:</strong><br>
    </p></td>
    <td> <input name="email" type="text" id="email" class="forminput" size="40" maxlength="40" value="<?php echo $email; ?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><img style='margin: 0px 0pt -13pt 333px;' src="<? echo $deep.$myrowimg["img"]; ?>"/></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("������� ����� � ��������"); ?>:</strong></p></td>
    <td><input name="answer" type="text" class="forminput" size="23" maxlength="23" /></td>
  </tr>
  <tr>

     <td>
          <input name="one" type="hidden" value="5" />
          <input name="two" type="hidden" value="10" />
          <input name="sum1" type="hidden" value="<? echo $sum1; ?>">
          <input name="error" type="hidden" value="<? echo $error; ?>" />
     </td>
  </tr>
  <tr><td colspan="2" align="center"><input name="sub_mail" type="submit" id="sub_mail" value="<? echo get_foreign_equivalent("����� ������ �� �����"); ?>" class="formbutton2" /></td></tr>
</table>
</form>

<? 
$advs->show('bottom');

include_once ($DIR."footer.html");
?>