<?php
//����� ��� � � register.php, login.php ����� �����
//�������� ����� ������ � ����� ��� ����� �����.
//� ����� ����� ��� ����. ��� ����� ����� � ������.

//login.php
require_once 'blocks/global.inc.php';
$error = "";
$username = "";
$password = "";

//��������� ���������� �� ����� ������
if(isset($_POST['submit-login'])) {

$username = $_POST['username'];
$password = $_POST['password'];

$userTools = new UserTools();
$error = $userTools->login($username, $password);
if($error == "Activated"){
    if (strstr($_SERVER['HTTP_REFERER'], 'uid') == FALSE){
        // ������� ����, �������� �� ��������
        // ����� �� ��������� ���� ��������� �����
        $user = unserialize($_SESSION['user']);
        $user->last_visit = date("Y-m-d");
        $user->save();
        // ������� ���� �� ����
//        del_cache($tbl);
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }else{ // �������� ������ ������, ���� ����� ���������, �� index.php
        header("Location: index.php");
   }
}else{
    //�������� �� �������� �����������    header("Location: welcome.php);
    header("Location: welcome.php?error=".$error);
}
}
if($error == ""):?>
<form action="<?php echo $deep;?>login.php" method="post">
<p align="center" class="inputtext"><? echo get_foreign_equivalent("�����"); ?>:<br >
<input name="username" type="text" id="username" maxlength="20" class="forminput" value="<?php echo $username; ?>"></p>
<p align="center" class="inputtext"><? echo get_foreign_equivalent("������"); ?>:<br >
<input name="password" type="password" id="password" class="forminput" maxlength="20" value="<?php echo $password;?>"></p>
<input name="page" id="page" type="hidden" value="<?php echo $_SERVER['PHP_SELF'];?>">
<input name="id" id="id" type="hidden" value="<?php echo $_GET['id'];?>">
<input name="cat" id="cat" type="hidden" value="<?php echo $_GET['cat'];?>">
<p><input type="submit" name="submit-login" value="<? echo get_foreign_equivalent("�����"); ?>" class="formbutton" ></p>
</form>
<?php endif;?>

