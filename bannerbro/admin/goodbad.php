<?php 
include 'up.php'; 

require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[4]!="1"){ header("Location:no.php");} 

connectToDB();
$lo=$_SESSION["login"];
$AD_S=mysql_query("SELECT Email FROM Users WHERE login='$lo'",$link)  or die (mysql_error($link));
$ADdata = mysql_fetch_array($AD_S);
if (isset($ADdata)) { $mail_from_test =$ADdata['Email']; }

if (isset($_POST['titlegood'])) { $titlegood=$_POST['titlegood']; }
if (isset($_POST['textgood'])) { $textgood=$_POST['textgood']; }
if (isset($_POST['titlebad'])) { $titlebad=$_POST['titlebad']; }
if (isset($_POST['textbad'])) { $textbad=$_POST['textbad']; }
if (isset($_POST['testgood'])) { $testgood=$_POST['testgood']; testone($mail_from_test,$mailZagGood,$mailTextGood,'0'); }
if (isset($_POST['testbad'])) { $testbad=$_POST['testbad']; testone($mail_from_test,$mailZag,$mailText,'1'); }
if (isset($_POST['titlegood']) && isset($_POST['textgood']) && isset($_POST['savegood'])){addmail('1',$titlegood,$textgood);}
if (isset($_POST['titlebad']) && isset($_POST['textbad']) && isset($_POST['savebad'])){addmail('2',$titlebad,$textbad);}

mysql_close($link);
?>

<?php include 'header.php'; ?>

<title>Настройка почты</title>
</head>
<body>

<div class="wrapper">
<?php
if (isset($_GET['set'])){
    $Set = $_GET['set'];
    if ($Set=='testmail'){
        echo '
            <div class="queue ta-c"><div class="green message">Тестовое письмо отправлено! Если оно пришло на ваш почтовый ящик, значит отправка почты работает!</div><br>
            <a href="goodbad.php">Вернуться к настройке почты</a></div>';
        exit;
    }
    if ($Set=='goodsavemail'){
        echo '
            <div class="queue ta-c"><div class="green message">Шаблон письма сохранен.</div><br>
            <a href="goodbad.php">Вернуться к настройке почты</a></div>';
        exit;
    }
    exit;
}
?>

    <?php include 'menu.php'; ?>

	<div class="content">

        <fieldset>
            <legend>Настройка шаблона писем</legend>
		<div class="settings_container">
		 <form method="post">
			<table class="order_table">
				<tr>
					<th>В случае Одобрения</th>
				</tr>
				<tr>
					<td><input style="width:300px; margin-left:3px;" name="titlegood" value="<?php if (isset($mailZagGood)) { echo $mailZagGood; } ?>" required/></td>
				</tr>
				<tr>
					<td><textarea id="reason" name="textgood" rows="6" cols="90"  required><?php if (isset($mailTextGood)) { echo $mailTextGood; } ?></textarea></td>
				</tr>
				<tr>
					<td><input name="savegood" type="submit" value="Сохранить" />&nbsp;<input name="testgood" type="submit" value="Тестовое письмо" /></td>
				</tr>
			</table>
		 </form>
		 <form method="post">
			<table class="order_table settings_item">
				<tr>
					<th>В случае Отказа</th>
				</tr>
				<tr>
					<td><input style="width:300px; margin-left:3px;" name="titlebad"  value="<?php if (isset($mailZag)) { echo $mailZag; } ?>" required/></td>
				</tr>
				<tr>
					<td><textarea id="reason" name="textbad" rows="6" cols="90"  required><?php  if (isset($mailText)) { echo $mailText; }  ?></textarea></td>
				</tr>
				<tr>
					<td><input name="savebad" type="submit" value="Сохранить" />&nbsp;<input name="testbad" type="submit" value="Тестовое письмо" /></td>
				</tr>
			</table>
		 </form>
			<table class="order_table settings_item">
				<tr>
					<td>*Тестовое письмо придет на : <b><?php echo $mail_from_test; ?></b></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>