<?php
/* ����������� ������������� (register.php)
� ���� �������� � ��� ��� �����.
���� ���� PHP � ������� �����, � HTML - � ������.
PHP ��� ��������� global.inc.php.
�� ����� �������� ���������� � �������� �����.
��� HTML �������� ��� ������� ������� �����.
����� ��������� ���������� � ����:
 */
//register.php

require_once 'blocks/global.inc.php';

//�������������� php ����������, ������� ������������ � �����
$username = "";
$password = "";
$password_confirm = "";
$email = "";
$error = "";

//���������� $_POST � ������������� ������� ���������� 
//���������� �������� ������� � ������� ������ HTTP POST. 
//�������� ������� ���� �������� � $_POST ��������� �������� 
//���� � �������� �����. ������ ����� �� ��������� ���� 
//�� ������ ������ "���������". �� ��������� $_POST['submit-form'] 
//� ������� ������� isset(), ��������� �submit-form� ���� �������� ������ �����.

//��������� ���������� �� �����
if(isset($_POST['sub_mail'])) {
    //�������� ���������� $_POST
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];
    $email = trim($_POST['email']);
    $answer = $_POST['answer'];
    $sum1 = $_POST['sum1'];
    $waserror = $_POST['error'];

    if (isset($_POST['cats'])) {
        $cats_array = $_POST['cats'];
        $cats = implode(",", $cats_array);
    } else {
        $cats = "";
    }
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
    //��������� ������������ ���������� �����
    //��������� �� ����� �� ���� �����
    if($userTools->checkUsernameExists($username))
    {
        $error .= get_foreign_equivalent("����� ��� ������������ ��� ����������.")."<br/> \n\r";
        $success = false;
    }

    // ��������� ���� �� ������������ � ����� ������� ����������� �����
    if($userTools->checkEmailExists($email))
    {
        $error .= get_foreign_equivalent("����� ����� ����������� ����� ��� ����������.")."<br/> \n\r";
        $success = false;   
    }

    if (empty($username))
    {
        $error .= get_foreign_equivalent("�� �� ����� ��� ������������, ��������� ����� � ��������� ��� ����.")."<br/> \n\r";
        $success = false;
    }

    // �������� ��������� �� ������ ����� ����������� �����
    $is_ok = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email);
    if ($is_ok==0)
    {
        $error .= get_foreign_equivalent("�� ����� �������� ����� ����������� �����!")."<br/> \n\r";
        $success = false;
    }

    //��������� ��� - ��� ��������� ��������� �� ������,
    //� ������� �������� ���������.
    //
    //��������� ���������� �������
    if($password != $password_confirm) {
        $error .= get_foreign_equivalent("������ �� ���������.")."<br/> \n\r";
        $success = false;
    }

    //��������� �� ������ �� ������
    if(empty ($password)) {
        $error .= get_foreign_equivalent("�� �� ����� ������!")."<br/> \n\r";
        $success = false;
    }
    $no_one_hour_user = get_foreign_equivalent("�� �� ������ �������������� ������ 1 ������������ � ���!");
    if (check_submit($_POST, 60, 'register') == 0 AND $waserror =="" AND $waserror !=$no_one_hour_user){
        $error .= $no_one_hour_user."<br/> \n\r";
        $success = false;
    }

    //���� ����� ������ ��������, �� ���������������� �� �������� �����������
    if($success) {
        //���� ����� ������ ��������, �� ��������� ���������� � ������ $data
        //� ���������� ��� ��� �������� ������ ������� User.
        //�� ������� ������� save() ���� ������� User � �����������
        //$isNewUser - true.

        //����������� ���������� ��� ���������� ������� ������ ������������
        $data['username'] = $username;
        $data['password'] = md5($password); //����������� ������ ��� ��������
        $data['email'] = $email;
        $data['lang'] = strtoupper($lang);
        $data['cats'] = $cats;

        //������� ����� ������ ������������
        $newUser = new User($data);

        //��������� ������ ������������ � ��
        $newUser->save(true);

        //����� �� ������ � ������� � ������� ������� login()
        //�����

        //�������� �� �������� �����������
        header("Location: welcome.php?error=Registration&id=".$newUser->id);
        exit ('');
    }
}

//����� � HTML ����� ������ ���� ��������� ������. 
//����� ����� ������������ ������������� ����������� 
//register.php � ��� �������� ������������ � ���������� $_POST.
//
//�������� ������� ���� ����� ������������ � ���������� 
//($username, $password...). ���� ����� ��� �� ����������, 
//��� ���������� ����� ��������� ������ ������. 
//���� ����� ����������, �� �� ������ ��������, 
//��� ���������� ����� ��������� �������� �� �������� �����������, 
//����� ������������ �� �������� ������ ������� ������.

//����� ���� ����� �� ������ ��������, ����� �������� ���������� $error. 
//� ���� ���������� ����� ����������� ������� ����, 
//������ ����� �� ������ ��������.


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

//����� �� ��������� ��������� �� ����.
if(isset($_SESSION['logged_in'])) {
    $logon = 1;
}else {
    $logon = 0;
}
// ���� ����������� � ������������ �����, �� �� �� �����
// ������ ��� ���������������� �� ��� ���������������
if ($logon == 1){
    //�������� �� ������� ��������
    header("Location: index.php");
    exit ('');
// ���� ������ �����������
}elseif ($logon == 0){
    $n=0;$text='';$title = get_foreign_equivalent("����������� ������������");
    require_once ("header.html");
    
    //���� ����� �� ���������� ��� �� ������ ��������, ����� �������� ����� �����
    echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
    $from4to20 = get_foreign_equivalent("�� 4 �� 20 ��������");
?>

<p class='post_title2'><? echo get_foreign_equivalent("����������� �� �����")." ".$softmaker_link; ?> </p>
<p><? echo "<p align=center>".get_foreign_equivalent("����� ����� ������������������ �������������, ��� ����� ��������� ��� ��������� �����. ����������� �������� ��� ��������� ����������� � ������� � ������, � ����� ������� ������ � ���������� ������.")."<p>"; ?>
</p><?$advs->show('top');?><br>
<form action="register.php" method="post">
 <table width="500" border="0" align="center" class="regtable">
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("�����"); ?>:</strong><br>*A-z, 0-9, _, <? echo $from4to20; ?></p></td>
    <td> <input name="username" type="text" id="username" class="forminput" size="40" maxlength="40" value="<?php echo $username; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong>Email:</strong><br>
    </p></td>
    <td> <input name="email" type="text" id="email" class="forminput" size="40" maxlength="40" value="<?php echo $email; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("������"); ?>:</strong> <br>*A-z, 0-9, _, <? echo $from4to20; ?></p></td>
    <td> <input name="password" type="password" id="password" class="forminput" size="40" maxlength="40" value="<?php echo $password; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("����������� ������"); ?>:</strong></p></td>
    <td> <input name="password-confirm" type="password" id="password-confirm" class="forminput" size="40" maxlength="40" value="<?php echo $password_confirm; ?>" /></td>
  </tr>
  <? echo get_all_cat_rows(); ?>
  <tr>
    <td colspan="2"><img style='margin: 0px 0pt -13pt 333px;' src="<? echo $myrowimg["img"]; ?>"/></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("������� ����� � ��������"); ?>:</strong></p></td>
    <td><input name="answer" type="text" class="forminput" size="23" maxlength="23" /></td>
  </tr>
  <tr>
    <!--<td>
        <p><input name="avatar" type="checkbox" id="avatar"  value="yes" />
         ��������� ������ 80x80px
           <br />*��� ������� �� ����� 8 Kb, ������ jpg.
          ����� ���������  ����� �����������.</p></td>-->
     <td>
          <!--<input name="avatarfile" type="file" id="avatarfile" class="forminput" />
          <input type="hidden" name="MAX_FILE_SIZE" value="8192">-->
          <input name="one" type="hidden" value="5" />
          <input name="two" type="hidden" value="10" />
          <input name="sum1" type="hidden" value="<? echo $sum1; ?>">
          <input name="error" type="hidden" value="<? echo $error; ?>" />
     </td>
  </tr>
  <tr><td colspan="2" align="center"><input name="sub_mail" type="submit" id="sub_mail" value="<? echo get_foreign_equivalent("�����������"); ?>" class="formbutton2" /></td></tr>
</table>
</form>

<?} 
$advs->show('bottom');
require_once ("footer.html");?>