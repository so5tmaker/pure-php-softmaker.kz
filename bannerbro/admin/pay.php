<?php 
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[0]!="2"){ header("Location:no.php");}

require_once "go/info_pay.php";

if (isset($_POST['SRobo'])){ SavePay('Robokassa'); }
if (isset($_POST['SZpayment'])){ SavePay('Z-payment'); }
if (isset($_POST['SInter'])){ SavePay('Interkassa'); }
?>
<?php include 'header.php'; ?>
<title>Настройка приема платежей</title>
</head>
<body>

<div class="wrapper">
<?php include 'menu.php'; ?>

	<div class="content">
<?php
		if (isset($_GET['good']))
			{
			echo '
				<div class="queue ta-c"><div class="green message">Настройки успешно сохранены</div><br>
				<a href="pay.php">Назад</a></div>';
				exit;
			}
?>	
        <fieldset>
            <legend>Настройка способов оплаты</legend>
		<div class="settings_container_pay">
		 <form method="post">
			<table class="order_table" style="border:1px solid;">
				<tr>
					<th colspan="4"></th>
				</tr>	
				<tr>
					<td colspan="4"><img src="/bannerbro/images/robokassa.png" width="150px"></td>
				</tr>
				<tr>
					<td style="width:130px;">Включить робокассу</td>
					<td colspan="3"><input name="Yes_Robo" id="Yes_Robo" value="1" type="checkbox" <?php if ($pay_info_robo['Enable']!=0) echo 'checked'; ?>/></td>
				</tr>
				<tr>
					<td style="width:100px;">Логин</td>
					<td><input name="Login_Robo" id="Login_Robo" value="<?php echo $pay_info_robo['login']; ?>" type="text"/></td>
					<td>Result URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/robo/res.php'; ?></td>
				</tr>
				<tr>
					<td>Пароль 1</td>
					<td><input name="Pass1_Robo" id="Pass1_Robo" value="<?php echo $pay_info_robo['password1']; ?>" type="password"/></td>
					<td>Success URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/robo/suc.php'; ?></td>
				</tr>
				<tr>
					<td>Пароль 2</td>
					<td><input  name="Pass2_Robo" id="Pass2_Robo" value="<?php echo $pay_info_robo['password2']; ?>" type="password"/></td>
					<td>Fail URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/robo/fail.php'; ?></td>
				</tr>		
				<tr>
					<td>Валюта</td>
					<td>Рубли</td>
					<td>Все методы</td>
					<td>POST</td>
				</tr>				
				<tr>
					<td colspan="4" style='text-align:right; font-size:18px;'><input name="SRobo" type="submit" value="Сохранить" /></td>
				</tr>
				<tr>
					<th colspan="4"></th>
				</tr>				
			</table>
		 </form>
		 
		 <form method="post">
			<table class="order_table" style="border:1px solid; margin-top:25px;">
				<tr>
					<th colspan="4"></th>
				</tr>	
				<tr>
					<td colspan="4"><img src="/bannerbro/images/zpayment.png" width="100px"></td>
				</tr>
				<tr>
					<td style="width:130px;">Включить Z-payment</td>
					<td colspan="3"><input name="Yes_payment" id="Yes_payment" value="1" type="checkbox" <?php if ($pay_info_zpayment['Enable']!=0) echo 'checked'; ?>/></td>
				</tr>
				<tr>
					<td style="width:100px;">ID магазина</td>
					<td><input name="Login_payment" id="Login_payment" value="<?php echo $pay_info_zpayment['ID_Shop']; ?>" type="text"/></td>
					<td>Result URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/payment/res.php'; ?></td>
				</tr>
				<tr>
					<td>Секретный ключ</td>
					<td><input name="Key_payment" id="Key_payment" value="<?php echo $pay_info_zpayment['SecretKey']; ?>" type="text"/></td>
					<td>Success URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/payment/suc.php'; ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>Fail URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/payment/fail.php'; ?></td>
				</tr>		
				<tr>
					<td></td>
					<td></td>
					<td>Все методы</td>
					<td>POST</td>
				</tr>				
				<tr>
					<td colspan="4" style='text-align:right; font-size:18px;'><input name="SZpayment" type="submit" value="Сохранить" /></td>
				</tr>
				<tr>
					<th colspan="4"></th>
				</tr>				
			</table>
		 </form>
		 
		 <form method="post">
			<table class="order_table" style="border:1px solid; margin-top:25px;">
				<tr>
					<th colspan="4"></th>
				</tr>	
				<tr>
					<td colspan="4"><img src="/bannerbro/images/interkassa.png" width="100px"></td>
				</tr>
				<tr>
					<td style="width:130px;">Включить Интеркассу</td>
					<td colspan="3"><input name="Yes_inter" id="Yes_inter" value="1" type="checkbox" <?php if ($pay_info_inter['Enable']!=0) echo 'checked'; ?>/></td>
				</tr>
				<tr>
					<td style="width:100px;">ID магазина</td>
					<td><input name="Login_inter" id="Login_inter" value="<?php echo $pay_info_inter['ID_Shop']; ?>" type="text"/></td>
					<td>Success URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/inter/suc.php'; ?></td>
				</tr>
				<tr>
					<td>Секретный ключ</td>
					<td><input name="Key_inter" id="Key_inter" value="<?php echo $pay_info_inter['SecretKey']; ?>" type="text"/></td>
					<td>Fail URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/inter/fail.php'; ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>Wait URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/inter/wait.php'; ?></td>
				</tr>	
				<tr>
					<td></td>
					<td></td>
					<td>Result URL:</td>
					<td><?php echo 'http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/go/inter/res.php'; ?></td>
				</tr>					
				<tr>
					<td></td>
					<td></td>
					<td>Все методы</td>
					<td>POST</td>
				</tr>				
				<tr>
					<td colspan="4" style='text-align:right; font-size:18px;'><input name="SInter" type="submit" value="Сохранить" /></td>
				</tr>
				<tr>
					<th colspan="4"></th>
				</tr>				
			</table>
		 </form>
		 
		</div>
	</div>


</div>


<?php include 'footer.php'; ?>