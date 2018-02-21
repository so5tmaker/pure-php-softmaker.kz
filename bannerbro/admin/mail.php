<?php 
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[0]!="2"){ header("Location:no.php");} 

connectToDB();

if (isset($_POST['new_host'])) { $new_host=$_POST['new_host']; }
if (isset($_POST['new_log'])) { $new_log=$_POST['new_log']; }
if (isset($_POST['new_pass'])) { $new_pass=$_POST['new_pass']; }

if (isset($_POST['type_mail'])) 
	{  
		$LoginPhpMailer=$_POST['new_php_mail']; 
		$type_mail=$_POST['type_mail']; 
		$abs=$_POST['ankor']; 
		$sl=$_POST['ssl'];		
		if ($type_mail=='0')
			{
				mysql_query("UPDATE Admin SET mail='$type_mail', login='$LoginPhpMailer', absorb='$abs'", $link)  or die (mysql_error($link)); header('Location:mail.php?good');
			} 
		if ($type_mail=='1')
			{
				mysql_query("UPDATE Admin SET mail='$type_mail',host='$new_host',login='$new_log',password='$new_pass',ssl1='$sl',absorb='$abs'", $link)  or die (mysql_error($link)); header('Location:mail.php?good'); 
			}
	}


mysql_close($link);
?>
<?php include 'header.php'; ?>

<title>Настройка почты</title>
</head>
<body>

<div class="wrapper">

<?php
if (isset($_GET['good'])){
        echo '
            <div class="queue ta-c"><div class="green message">Настройки успешно сохранены!</div><br>
            <a href="mail.php">Вернуться к настройке почты</a></div>';
        exit;
    }
?>

<?php include 'menu.php'; ?>

<div class="content">

<fieldset>
    <legend>Настрока почты</legend>
    <div class="settings_container">
        <form method="POST">
            <table class="order_table">
                <tr>
                    <th colspan="2">Текущие настройки</th>
                </tr>
				<tr>
                    <td>Ваши настройки:</td>
						<?php
						if ($mail_set=='0'){ 
						echo '
								<td>PHP Mailer</td>
							</tr>
							<tr>
								<td>Email отправителя</td>
								<td>'.$mail_log.'</td>
							</tr>';
						}
						if ($mail_set=='1'){ 
						echo '
								<td>SMPT-сервер</td>
							</tr>
							<tr>
								<td>Хост</td>
								<td>'.$mail_smtp.'</td>
							</tr>
							<tr>
								<td>Почта</td>
								<td>'.$mail_log.'</td>
							</tr>					
							<tr>
								<td>Порт</td>
								<td>'.$port.'</td>
							</tr>';				
						}
						?>	
            </table>
			
            <table class="order_table settings_item">
                <tr>
                    <th colspan="2">Способ отправки</th>
                </tr>
                <tr>
                    <td>Способ отправки</td>
                    <td><select name="type_mail" id="type_mail"><option value="0">PHP Mailer (рекомендуется)</option><option value="1">SMTP-сервер</option></select></td>
                </tr>
                <tr>
                    <td>Ссылки в сообщениях</td>
                    <td><select name="ankor" id="ankor"><option value="No">Как анкор</option><option value="Yes">Как текст</option></select></td>
                </tr>				
            </table>
			
            <table class="order_table settings_item">
                <tr>
                    <th colspan="3">Настройки PHP Mailer*</th>
                </tr>
                <tr>
                    <td>Email отправителя</td>
                    <td><input type="email" name="new_php_mail" value="<?php echo $mail_log; ?>"></td><td><?php help('Укажите рабочую почту. <p style="color:red">Внимание!</p> Если письма не приходят, значит вам нужно указать почту, которую предоставляет ваш хостинг, например webnames@ваш_сайт.ru<br>Подробнее об этом можно узнать у своего хостинга.'); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        *Рекомендуется использовать <b>PHP Mailer</b> - этот класс использует стандартную функцию mail() для отправки почты.
                    </td>
                </tr>
            </table>
			
            <table class="order_table settings_item">
                <tr>
                    <th colspan="3">Настройки SMTP*</th>
                </tr>
                <tr>
                    <td>Хост</td>
                    <td><input type="text" name="new_host" value="<?php echo $mail_smtp; ?>"></td><td><?php help('Укажите ваш хост, например: smtp.mail.ru'); ?></td></td>
                </tr>
                <tr>
                    <td>Почта</td>
                    <td><input type="email" name="new_log" value="<?php echo $mail_log; ?>"></td><td><?php help('Укажите рабочую почту согласно своего хоста, например test@mail.ru'); ?></td>
                </tr>
                <tr>
                    <td>Пароль</td>
                    <td><input type="password" name="new_pass" value="<?php echo $mail_pas; ?>"></td><td><?php help('Укажите пароль от почты'); ?></td>
                </tr>
                <tr>
                    <td>Защищенное соединение с SMTP</td>
                    <td><select name="ssl" id="ssl"><option value="Yes">Да</option><option value="No">Нет</option></select></td><td><?php help('Некоторые хостинги требуют ssl передачу данных. Что бы ее включить поставьте значение "Да".'); ?></td>
                </tr>				
                <tr>
                    <td colspan="2">*Вариант с <b>SMTP</b> наиболее ресурсозатратный и может неподдерживаться хостингом (т.к. требует подключения средствами PHP через 25 порт), но обычно хорошо справляется с спам-фильтрами.</td>
                </tr>
				<tr>
                    <td colspan="2"><input name="SaveSetting" type="submit" value="Сохранить настройки" /></td>
                </tr>				
            </table>
			
        </form>
    </div>
</fieldset>
</div>
</div>

<script>
$(function() {
      var temp="<?php echo $mail_set ?>"; 
	  var ab="<?php echo $absorb ?>";
	  var ss="<?php echo $ssl ?>"; 
    $("#type_mail").val(temp);
	$("#ankor").val(ab); 
	$("#ssl").val(ss);  
});
</script>

<?php include 'footer.php'; ?>


