<?php

session_start();


$_POST['login'] = trim(filter_input(INPUT_POST, 'login'));
$_POST['password'] = trim(filter_input(INPUT_POST, 'password'));
$_POST['login'] = $_POST['login'];

if(!empty($_POST['admin'])){
	if(empty($_POST['login'])) ErrorAut("Введите логин!");
	if(empty($_POST['password']))  ErrorAut("Введите пароль!");

	// Извлекаем логин и пароль из базы данных
	$query = "SELECT * FROM userlist WHERE user LIKE '".$_POST['login']."'";
	$result = $dbh->query($query);

	if($result) { $aut = $result->fetch_array(); }

	$result->close();

	if($_SESSION['sess_admin'] != "ok"){
		$_SESSION['login'] = $_POST['login'];
	}

	$hash = md5($_POST['password']);

	if($hash == $aut['pass']){
		$_SESSION['sess_admin'] = "ok";
		$_SESSION['login'] = $aut['login'];
		$_SESSION['id_user'] = $aut['id_user'];
		
		if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
	}
	else{
		ErrorAut("Извините, но Вы не прошли авторизацию.\\nДоступ закрыт!");
	}
}
else
{
	if($_SESSION['sess_admin'] != "ok"){
		ShowAdmin();
	}
}

function ShowAdmin()
{

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251">
<link href="./styles/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="./styles/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="./styles/styles.css" rel="stylesheet" media="screen">
<link href="./styles/DT_bootstrap.css" rel="stylesheet" media="screen">		
<title>Авторизация</title>
<head>
<body>
<div class="container">
<form class="form-signin" method="post">
<input class="input-block-level" type="text" name="login" placeholder="логин">
<input class="input-block-level" type="password" name="password" placeholder="пароль">
<input type="submit" class="btn btn-primary" name="admin" value=" OK ">
</form>
</div>
</body>
</html>
<?php

	exit();
}

function ErrorAut($msg)
{

?>
<!DOCTYPE html>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<title>Ошибка авторизации!</title>
</head>
<body>
<script type='text/javascript'>
window.alert('<?php echo $msg; ?>');
window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>';
</script>
</body>
</html>
<?php

	exit();
}

?>