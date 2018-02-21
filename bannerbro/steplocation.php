<?php
require_once "admin/session.php";
require_once "admin/config.php";
require_once "admin/try_function.php";
require_once 'admin/validate.php';
$Pos=$_POST['step3pos']; Vint($Pos);
$About=$_POST['step3about']; DeletBadSimvol($About);
$Size=$_POST['step3size']; Vname($Size);
$Name=$_POST['step3name']; Vname($Name);
$Email=$_POST['step3email']; Vmail($Email);
$Prise=$_POST['step3prise']; Vint($Prise);
$URL=$_POST['step3url'];Vurl($URL);
$ALT=$_POST['step3alt'];Vname($ALT);
$MyÑhoiñe=$_POST['step3choice']; $ppt=strripos($MyÑhoiñe, '/'); if ($ppt === false) {Vint($MyÑhoiñe);} else {Vdate($MyÑhoiñe);}
$StringÑhoiñe=$_POST['step3string']; Vname($StringÑhoiñe);
$fatban=$_POST['step3fatban']; Vint($fatban);
$Load_Picture = $_FILES['MyBanner']['tmp_name'];
$Size = explode("x", $Size);

if (isset($Load_Picture)) { $imageinfo = getimagesize($_FILES['MyBanner']['tmp_name']); } 

Vname($_POST['prosto']);
if($_POST['prosto'] != $_SESSION['rand_code'])
{
header('Location:step21.php?set=1');
 exit;
}

if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpg' && $imageinfo['mime'] != 'application/x-shockwave-flash') 
{
header('Location:step21.php?set=2'); 
exit;
}
if($imageinfo[0]==$Size[0] && $imageinfo[1]==$Size[1]){
} else {
header('Location:step21.php?set=3');
exit; 
} 
if($_FILES["MyBanner"]["size"] > $fatban*1024)
{
header('Location:step21.php?set=5'); 
exit;
}

connectToDB();
$nums=mysql_query("SELECT Pics FROM Admin WHERE ID ='1'", $link) or die (mysql_error($link));
$data = mysql_fetch_array($nums);
$num=$data['Pics']+1;
mysql_query("UPDATE Admin SET Pics='$num' WHERE ID='1'", $link)  or die (mysql_error($link));
mysql_close($link);

$uploaddir = 'img/'; 
$uploadfile = $uploaddir . 'pic'.$num.'.'.getExtension($_FILES['MyBanner']['name']);
$banner = 'pic'.$num.'.'.getExtension($_FILES['MyBanner']['name']);
if (move_uploaded_file($_FILES['MyBanner']['tmp_name'], $uploadfile)) {
echo '<html>';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
echo '<link rel="stylesheet" type="text/css" href="style.css">';
echo '<script type="text/javascript" src="admin/common/scripts/jquery-1.11.0.min.js"></script>';
echo '</head>';
echo '<form action="step21.php" method="POST">';
echo '<div style="display:none;">';
echo '<input type="text" id="stepend_pos" name="stepend_pos" value="'.$Pos.'"><br>';
echo '<input type="text" id="stepend_email" name="stepend_email" value="'.$Email.'"><br>';
echo '<input type="text" id="stepend_name" name="stepend_name" value="'.$Name.'"><br>';
echo '<input type="text" id="stepend_url" name="stepend_url" value="'.$URL.'"><br>';
echo '<input type="text" id="stepend_alt" name="stepend_alt" value="'.$ALT.'"><br>';
echo '<input type="text" id="stepend_do" name="stepend_do" value="'.$MyÑhoiñe.'"><br>';
echo '<input type="text" id="stepend_string" name="stepend_string" value="'.$StringÑhoiñe.'"><br>';
echo '<input type="text" id="stepend_about" name="stepend_about" value="'.$About.'"><br>';
echo '<input type="text" id="stepend_pic" name="stepend_pic" value="'.$banner.'"><br>';
echo '<input type="text" id="stepend_size" name="stepend_size" value="'.$Size[0].'x'.$Size[1].'"><br>';
echo '<input type="text" id="stepend_price" name="stepend_price" value="'.$Prise.'"><br>';
echo '<input type="submit" name="go" id="go" value="Äàëåå">';
echo '</div>';
echo '</form>';

print '<script>';
print '$(document).ready(function(){';
print '$(\'#go\').click()';
print '})';
print '</script>';
}else{header('Location:step21.php?set=4'); exit;}
?>