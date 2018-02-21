<?php
//������������������ ������������, ������� ����� � �������
//����� �������� �������� ��������� ������.
//��� �������� �������, �� �������� �������������
//������ ������ ����������� �����.
//�� �� ������ ����������� ��������� ���� ����������.

//��� ����� ����� �� ���������� ����� � ������.
//�� ���� �������� ��� �������� ����� ��
//����� �������� ������������ � �������� �������
//User � ���������� ��� ����� �����.
//����� ������ �������� ������� save() � ��� � ��� �������.

require_once 'blocks/global.inc.php';

//����� ������ user �� ������
//����� �� ��������� ��������� �� ����.
if(isset($_SESSION['logged_in'])) {
    $logon = 1;
    $user = unserialize($_SESSION['user']);
    //���������������� php ����������, ������������ � �����
    $username = $user->username;
    $email = $user->email;
    $password = "";
    $password_confirm = "";
    $message = "";
    $cat_rows = get_cat_rows($user);
}else {
    $logon = 0;
}

//��������� ���������� �� �����
if(isset($_POST['submit-settings']) && ($logon == 1)) {
    //�������� ���������� $_POST
//    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];
    $email = $_POST['email'];
    $answer = $_POST['answer'];
    $sum1 = $_POST['sum1'];
     
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

    //���� ����� ������ ��������, �� ���������������� �� �������� �����������
    if($success)
    {
        //�������� ��������� ������������ � ��
        $user->hashedPassword = md5($password); //����������� ������ ��� ��������
        $user->email = $email;
        $user->cats = $cats;
        $user->save();
        $message = "<p align='center'>".get_foreign_equivalent("��������� ���������!")."</p><br/>";
        // ������ ���������� ����, ������� ������� ���������
        $cat_rows = get_cat_rows($user);
    }
}
//���� ����� �� ���������� ��� �� ������ �������� - �������� ����� �����

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

if ($logon == 0){
    //�������� �� ������� ��������
    header("Location: index.php?lang=".$lang);
    exit ('');
// ���� ������ �����������
}elseif ($logon == 1){
    $n=0;$text='';$title = get_foreign_equivalent("��������� �������� ������������");
    require_once ("header.html");
    
?>
 <form action="<? echo "settings.php?lang=".$lang; ?>" method="post">
 <table width="500" border="0" align="center" class="regtable">
  <p class='post_title2'><? echo get_foreign_equivalent("��������� �������� ������������"); ?> <i><?php echo $username." ".get_foreign_equivalent("�� �����")." ".$softmaker_link; ?></i></p>

  <? 
  $advs->show('top');
  
  echo $message; echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
  ?>
  
  <tr>
    <td><p><strong>Email:</strong><br>
    </p></td>
    <td> <input name="email" type="text" id="email" class="forminput" size="40" maxlength="40" value="<?php echo $email; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("������"); ?>:</strong> <br>*A-z, 0-9, _, <? echo get_foreign_equivalent("�� 4 �� 20 ��������"); ?></p></td>
    <td> <input name="password" type="password" id="password" class="forminput" size="40" maxlength="40" value="<?php echo $password; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("����������� ������"); ?>:</strong></p></td>
    <td><input name="password-confirm" type="password" id="password-confirm" class="forminput" size="40" maxlength="40" value="<?php echo $password_confirm; ?>" /></td>
  </tr>
  <? // ������ ��������� ��� ������ ������������
  echo $cat_rows; ?>
  <tr>
    <td colspan="2"><img style='margin: 0px 0pt -13pt 333px;;' src="<? echo $myrowimg["img"]; ?>"/></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("������� ����� � ��������"); ?>:</strong></p></td>
    <td><input name="answer" type="text" class="forminput" size="23" maxlength="23" /></td>
  </tr>
  <tr>
     <td>
          <input name="sum1" type="hidden" value="<? echo $sum1; ?>">
     </td>
  </tr>
  <tr><td colspan="2" align="center"><input name="submit-settings" type="submit" id="submit-settings" value="<? echo get_foreign_equivalent("���������"); ?>" class="formbutton2" /></td></tr>
</table>
</form>
<? } 
$advs->show('bottom');
require_once ("footer.html");?>