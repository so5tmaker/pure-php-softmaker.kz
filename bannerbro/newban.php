<?php
require_once "admin/config.php";
require_once "admin/try_function.php";
require_once 'admin/validate.php';
connectToDB();
if (isset($_GET['id'])){$Set=$_GET['id']; Vint($Set);}
if ((!isset($_GET['id'])) && (!isset($_GET['idd']))){header('Location:index.php');} 
if (isset($_GET['error'])){$Error=$_GET['error']; Vint($Error);}
if (isset($_GET['good'])){$Good=$_GET['good'];}
if (isset($_POST['cod']) && isset($_POST['url']) && $_FILES['NewBanner']['tmp_name']!=null && $Error==null && $Set!=null)
	{
	Vname($_POST['cod']);
	$Url=$_POST['url'];
	Vurl($Url);
	Vint($Set);
	$Admset=mysql_query ("SELECT * FROM Admin WHERE ID='1'", $link)  or die (mysql_error($link));
	$Admdata = mysql_fetch_array($Admset);
	$fatban = $Admdata['fatban'];
	$Check=mysql_query ("SELECT * FROM Orders WHERE ID='$Set'", $link)  or die (mysql_error($link));
	$Checkdata = mysql_fetch_array($Check);
	$Size = explode("x", $Checkdata['Size']);
		if ($_POST['cod'] == md5(substr($Checkdata['Email'], 2, 5))) 
		{ 
			Vint($Set);
			$imageinfo = getimagesize($_FILES['NewBanner']['tmp_name']);
				if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpg' && $imageinfo['mime'] != 'application/x-shockwave-flash') 
				{
					header('Location:newban.php?error=1&idd='.$Set); 
					exit;
				}
				
				if($imageinfo[0]!=$Size[0] && $imageinfo[1]!=$Size[1])
				{
					header('Location:newban.php?error=2&idd='.$Set); 
					exit;
				}
				
				if($_FILES["NewBanner"]["size"] > $fatban*1024)
				{
					header('Location:newban.php?error=3&idd='.$Set); 
					exit;
				}

						# Cчитаем pictures присваиваем переменной значение и кидаем в папку img обновляем базу данных для админа...
						$nums=mysql_query("SELECT Pics FROM Admin WHERE ID ='1'", $link) or die (mysql_error($link));
					
						$data = mysql_fetch_array($nums);
					
						$num=$data['Pics']+1;
					
						mysql_query("UPDATE Admin SET Pics='$num' WHERE ID='1'", $link)  or die (mysql_error($link));
					
						$uploaddir = 'img/'; 
					
						$uploadfile = $uploaddir . 'pic'.$num.'.'.getExtension($_FILES['NewBanner']['name']);
						
						$banner = 'pic'.$num.'.'.getExtension($_FILES['NewBanner']['name']);
						Vname($banner);
						
						if (move_uploaded_file($_FILES['NewBanner']['tmp_name'], $uploadfile)) 
						{
							mysql_query("UPDATE Orders SET new_ban='$banner',new_url='$Url' WHERE ID='$Set'", $link)  or die (mysql_error($link));
							header('Location:newban.php?good=1&idd='.$Set);
							exit;
						}
		} 
		else 
		{
			header('Location:newban.php?error=4&idd='.$Set); 
			exit;
		}
	}
mysql_close($link);	
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="css/normalize.css"/>

    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
    <script type="text/javascript" src="js/my.js"></script>

    <!--[if lt IE 9]>
        <script src="common/scripts/html5shiv.js"></script>
    <![endif]-->
<title>Изменение заказа</title>
</head>
<body>
<div class="wrapper">
	<div class="queue ta-c">
<?php 
	
	if (isset($_GET['error']))
	{ 
		$idd=$_GET['idd'];  
		if ($Error=='1') 
		{ 
			echo '<div class="queue ta-c"><div class="yellow message">Загружаемый баннер должен иметь формат JPG,PNG,GIF или SWF<br><br><a href="newban.php?id='.$idd.'">Назад</a></div><br>'; exit;
		}
	
		if ($Error=='2') 
		{ 
			echo '<div class="queue ta-c"><div class="yellow message">Загружаемый баннер имеет неподходящие размеры для выбранной позиции.<br><br><a href="newban.php?id='.$idd.'">Назад</a></div><br>'; exit;
		}
		
		if ($Error=='3') 
		{ 
			echo '<div class="queue ta-c"><div class="yellow message">Загружаемый баннер весит больше, чем установлено. Пожалуйста воспользуйтесь другим баннером.<br><br><a href="newban.php?id='.$idd.'">Назад</a></div><br>'; exit;
		}
		
		if ($Error=='4') 
		{ 
			echo '<div class="queue ta-c"><div class="yellow message">Защитный код введен неверно.<br><br><a href="newban.php?id='.$idd.'">Назад</a></div><br>'; exit;
		}
	}
	
	if (isset($_GET['good']))
	{
		connectToDB();
		GetInformationAutorMail();
		GoMail($mail_admin,'БаннерБро - Изменение заказа','На сайте - <b>'.$_SERVER['HTTP_HOST'].'</b> <br>Поступил запрос на изменение заказа<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/">Панель администратора</a>');
		$idd=$_GET['idd']; Vint($idd); Vint($_GET['good']);		
		if ($Good=='1') 
		{ 
			echo '<div class="green message">Спасибо, ваши новые данные отправлены на модерацию.</div>'; exit;
		}
	}
?>
 
		<form method="post" ENCTYPE="multipart/form-data" class="fl-l">
            <fieldset class="ta-l">
                <legend>Изменение баннера</legend>

                <table class="order_table">
                    <tr>
                        <td><input type="text" name="cod" placeholder="Код" required></td>
                    </tr>
                    <tr>
                        <td><input type="" name="url" placeholder="Ссылка" required></td>
                    </tr>
                    <tr>
                        <td><input type="file" name="NewBanner" title="Выберите новый баннер" placeholder="Код" required></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" value="Отправить на модерацию"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
		<div class="clear"></div>
    </div>
</div>
<?php include 'copyright.php'; ?>
</body>
</html>