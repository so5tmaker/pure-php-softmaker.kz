<?php 
require_once "admin/session.php";
require_once "admin/config.php";
require_once "admin/adm.php";
require_once "admin/SendMailSmtpClass.php";
require_once "admin/try_function.php";
require_once 'admin/validate.php';

if (!isset($_GET['id']) && !isset($_GET['sec']) && !isset($_GET['edit'])){
	header('Location:index.php');
    exit;
}

connectToDB();

if (isset($_GET['id'])){$Set=$_GET['id']; Vint($Set);}
if (isset($_POST['mail_order']) && isset($_POST['edit']) && isset($_GET['id']))
	{
	Vname($_POST['edit']); Vmail($_POST['mail_order']); $Set=$_GET['id']; Vint($Set);
	$mail_order=$_POST['mail_order'];
	$CheckOrder=mysql_query ("SELECT * FROM Orders WHERE ID='$Set'", $link)  or die (mysql_error($link));
	$Checkdata = mysql_fetch_array($CheckOrder);
	
	if ($Checkdata['Email']==$mail_order && $_POST['edit'] == $_SESSION['rand_code']) 
		{ 
				go_mail($mail_order,$Set,$mail_order);
		}
	} 
$MessageCoupon='';
$GoodMessageCoupon='';	
if (isset($_POST['discount_access']) && isset($_POST['discount_coupon'])){
$CouponCode=trim($_POST['discount_coupon']);
Vname($CouponCode);
$RealyCoupon=mysql_query ("SELECT * FROM Сoupons WHERE Code='$CouponCode'", $link)  or die (mysql_error($link));
$DontUsedDiscount=mysql_query ("SELECT * FROM Orders WHERE ID='$Set' and discount='0'", $link)  or die (mysql_error($link));
if (mysql_num_rows($RealyCoupon)>0){
	if (mysql_num_rows($DontUsedDiscount)>0){
	$DataCoupon = mysql_fetch_array($RealyCoupon);
	$DataOrderCoupon = mysql_fetch_array($DontUsedDiscount);
	$NewPrice=$DataOrderCoupon['Price']-(($DataOrderCoupon['Price']/100)*$DataCoupon['Discount']);
	mysql_query("UPDATE Orders SET discount='1',Price='$NewPrice' WHERE ID='$Set'", $link)  or die (mysql_error($link));
	mysql_query("DELETE FROM Сoupons WHERE Code='$CouponCode'", $link)  or die (mysql_error($link));
	$GoodMessageCoupon='Ваш купон успешно задействован!';
	} else {
	$MessageCoupon='В этом заказе уже действует скидка по купону';
	}
} else {
$MessageCoupon='Неверный код купона';
}
}

require_once "admin/function.php";
if (isset($Set)){
//if (md5($_GET['sec'])==$Set){
$MessageDay='';
$MessagePay='';
$closeday='';
$closepay='';
$Timer=mysql_query ("SELECT * FROM Orders WHERE ID='$Set' and Whot='Day' and Pay!='Оплачено'", $link)  or die (mysql_error($link));
$TimerData = mysql_fetch_array($Timer);
$myd=date('Y-m-d H:i:s');			
$myd10=date('Y-m-d H:i:s',strtotime('+ 10 min'));	
	if (mysql_num_rows($Timer)!=null) // Узнаем есть ли активные заказы
		{
			$TP=$TimerData['Position'];
			$TDo = explode(",", $TimerData['Do']);
				$TDay=mysql_query("SELECT * FROM Orders WHERE Position='$TP' AND ID!='$Set' AND Start>'$myd' AND (Pay='Модерация после оплаты' OR Pay='Оплачено-Модерация')", $link) or die (mysql_error($link));
					if (mysql_num_rows($TDay)!=null) // Узнаем есть ли активные заказы
						{				
							while($TDays = mysql_fetch_array($TDay))
								{
									$WhotCheck = explode(",", $TDays['Do']);
									for ($m = 0; $m < count($TDo); $m++) 
									{
										for ($i = 0; $i < count($WhotCheck); $i++)
										{
											if ($WhotCheck[$i]==$TDo[$m])
												{
													$zap=explode("/", $WhotCheck[$i]);
													$closeday.=$zap[2].'-'.$zap[0].'-'.$zap[1].', ';
													$closeUser=$TDays['User'];
													$closeTimer=$TDays['Start'];
												}
										}	
									}
								}
								
							if ($closeday!='')
								{							
									$closeday = substr($closeday,  0, -2);
									$MessageDay='Извините, но дни '.$closeday.' уже заняты. Пользователь <b>'.$closeUser.'</b> опередил вас. Вы можете купить баннер на другие дни, либо оплатить этот заказ после '.$closeTimer.', если пользователь <b>'.$closeUser.'</b> откажется от оплаты.';	
								} else {
								if ($TimerData['Start']<$myd)
									{
										mysql_query("UPDATE Orders SET Start='$myd10' WHERE ID='$Set'", $link)  or die (mysql_error($link));
									}
								}	
						} else {
								if ($TimerData['Start']<$myd)
									{
										mysql_query("UPDATE Orders SET Start='$myd10' WHERE ID='$Set'", $link)  or die (mysql_error($link));
									}
						}
				$YesPay=mysql_query("SELECT * FROM Orders WHERE Position='$TP' AND ID!='$Set' AND Pay='Оплачено'", $link) or die (mysql_error($link));
					if (mysql_num_rows($YesPay)!=null) // Узнаем есть ли активные заказы
						{
							while($TDays = mysql_fetch_array($YesPay))
								{
									$WhotCheck = explode(",", $TDays['Do']);
									for ($m = 0; $m < count($TDo); $m++) 
									{
										for ($i = 0; $i < count($WhotCheck); $i++)
										{
											if ($WhotCheck[$i]==$TDo[$m])
												{
													$zap=explode("/", $WhotCheck[$i]);
													$closepay.=$zap[2].'-'.$zap[0].'-'.$zap[1].', ';
													$closeUser=$TDays['User'];												
												}
										}	
									}
								}			
								if ($closepay!='')
									{							
										$closepay = substr($closepay,  0, -2);
										$MessagePay='Извините, но дни '.$closepay.' уже куплены пользователем <b>'.$closeUser.'</b>. <p>Вы можете купить баннер на другие дни.';	
									}		
						}
		}
//	}	
}		
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="css/normalize.css"/>
	<link href="admin/addons/lightbox/css/lightbox.css" type="text/css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/style.css">

    <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
	
    <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
	<script type="text/javascript" src="admin/addons/lightbox/js/lightbox.js"></script>
    <script type="text/javascript" src="js/my.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
		$('a[data-lightbox]').lightbox();
	})

	$(function() {
		if ($.browser.msie && $.browser.version.substr(0,1)<7)
		{
		  $('.tooltip').mouseover(function(){
				$(this).children('span').show();
			  }).mouseout(function(){
				$(this).children('span').hide();
			  })
		}
	  });
	  
	function Url(url) 
		{
			window.open(url);
		} 
	</script> 	
	
    <!--[if lt IE 9]>
        <script src="common/scripts/html5shiv.js"></script>
    <![endif]-->

<title>Просмотр заказа</title>

</head>
<body>
<div class="wrapper">
<?php
GetInformationAutorMail();
		echo '
			<div class="steps_container">
				<table>
					<tr>
						<td >Шаг 1</td>
						<td class="border">Шаг 2</td>
						<td class="active">Шаг 3</td>
					</tr>
				</table>
				<div class="clear"></div>
			</div>';
//if (isset($Set)){echo md5($Set);}
if (isset($_GET['edit'])!=null){
    echo '<div class="queue ta-c"><div class="green message">На ваш Email адрес отправлено письмо</div></div></div>';
	include 'copyright.php';
    exit;
}

if (isset($_GET['id'])!=1){
    echo '<div class="queue ta-c"><div class="yellow message">Заказа не существует</div></div></div>';
	include 'copyright.php';
    exit;
}

if ($MessageDay!='')
	{ 
		echo '<div class="queue ta-c"><div class="yellow message">'.$MessageDay.'</div></div></div><div class="copyright ta-c">Система "<a href="http://bannerbro.ru" target="_blank">БаннерБро</a>" &copy; Автор: Андрей Серебряков</div>';
		exit;
	} 
if ($MessagePay!='')
	{ 
		echo '<div class="queue ta-c"><div class="yellow message">'.$MessagePay.'</div></div></div><div class="copyright ta-c">Система "<a href="http://bannerbro.ru" target="_blank">БаннерБро</a>" &copy; Автор: Андрей Серебряков</div>';
		exit;
	}
if ($MessageCoupon!='')
	{ 
		echo '<div class="queue ta-c"><div class="yellow message">'.$MessageCoupon.'</div></div></div><div class="copyright ta-c">Система "<a href="http://bannerbro.ru" target="_blank">БаннерБро</a>" &copy; Автор: Андрей Серебряков</div>';
		exit;
	}
if ($GoodMessageCoupon	!='')
	{ 
		echo '<div class="queue ta-c"><div class="green message">'.$GoodMessageCoupon.'</div><br><a href="javascript:history.back()">Назад</a></div></div></div><div class="copyright ta-c">Система "<a href="http://bannerbro.ru" target="_blank">БаннерБро</a>" &copy; Автор: Андрей Серебряков</div>';
		exit;
	}	

if (isset($_GET['edit'])) {$Edit=$_GET['edit'];}
if (isset($Set)!=null  && isset($_GET['sec']))
{
if ($_GET['sec']==md5($Set))
{
	$tttt=(int)($Set);
	$ShowOrder=mysql_query ("SELECT * FROM Orders WHERE ID='$tttt'", $link)  or die (mysql_error($link));
	$data = mysql_fetch_array($ShowOrder);
	if (mysql_num_rows($ShowOrder)!=null)
	{
		echo '<div class="statistics">';
		echo '<div class="fl-l">';
		echo '<fieldset style="max-width:705px;">';
		echo '<legend>Ваш заказ № '.$data['ID'].'</legend>';
		echo '<table class="order_table">';
		$Size = explode("x", $data['Size']);
		echo '<tr><td rowspan="4">';
		if (getExtension($data['Picture']) == 'swf')
			{
				echo '<span style="max-width:468px; max-height:400px;"><object type="application/x-shockwave-flash" data="/bannerbro/img/'.$data['Picture'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/img/'.$data['Picture'].'" /><param name="FlashVars" value="clickTAG='.$data['Url'].'" /></object></span>';
			} else {
				echo '<span class="hidden-link" onclick="Url(\''.$data['Url'].'\')"><img style="max-width:468px; max-height:400px; " src="/bannerbro/img/'.$data['Picture'].'" /><a>';
			}
		echo '</td></tr>';
		if ($data['Pay']=='Ожидает одобрения'){$Stat='<span style="color:grey;">На модерации';}
		if ($data['Pay']=='Ожидает оплаты'){$Stat='<span style="color:blue;">Жду оплаты';}
		if ($data['Pay']=='Оплачено'){$Stat='<span style="color:green;">Оплачено';}
		if ($data['Pay']=='End' or $data['Pay']=='Hide'){$Stat='<span style="color:red;">Не активен';}
		if ($data['Pay']=='Модерация после оплаты'){$Stat='<span style="color:grey;"><br>Модерация после оплаты';}
		if ($data['Pay']=='Оплачено-Модерация'){$Stat='<span style="color:blue;"><br>Заказ оплачен. На модерации';}
		echo '<tr><td>Статус: '.$Stat.'</span></td></tr>';
		echo '<tr><td>Кликов: '.$data['Clicks'].'<br>';
		echo 'Показов: '.$data['Shows'].'</td></tr>';
		echo '<tr><td>';
		
		if ($data['Whot']=='Click')
			{ 
				$razn =$data['Do']-$data['Clicks']; if ($razn<0){$razn=0;}
				echo 'Кликов заказанно: '.$data['Do'].'<br>';
				echo 'Кликов осталось: '.$razn.'';
			}
			
		if ($data['Whot']=='Shows')
			{ 
				$razn =$data['Do']-$data['Shows']; if ($razn<0){$razn=0;}
				echo 'Показов заказанно: '.$data['Do'].'<br>';
				echo 'Показов осталось: '.$razn.'';
			}
			
		if ($data['Whot']=='Day')
			{ 
				echo 'Дней: '.count(explode(",", $data['Do'])).'&nbsp;&nbsp;<a data-lightbox="on" href="#numday"><img style="cursor:pointer;" src="images/watch.png" width="12"></a>';
			}
		echo '</tr></td>';
		echo ' </table>';
		
	} else { 
		echo '<div class="queue ta-c"><div class="yellow message">Заказа не существует</div></div></div><div class="copyright ta-c">Система "<a href="http://bannerbro.ru" target="_blank">БаннерБро</a>" &copy; Автор: Андрей Серебряков</div>'; 
		exit; 
	}
} else {
    echo '<div class="queue ta-c"><div class="yellow message">Ошибка в ссылке на заказ. Пожалуйста обратитесь к администратору<br><br>Номер заказа: '.$Set.'<br><br><hr/><br>Email для связи: <b>'.$mail_admin.'</b></div><br></div></div></div>';
	include 'copyright.php';
    exit;
}
} else {
	echo '<div class="queue ta-c"><div class="yellow message">Ошибка в ссылке на заказ. Пожалуйста обратитесь к администратору<br><br>Номер заказа: '.$Set.'<br><br><hr/><br>Email для связи: <b>'.$mail_admin.'</b></div><br></div></div></div>';
	include 'copyright.php';
    exit;
}

	
mysql_close($link);
?>



</div>

<div class="change fl-r">
<?php
if (isset($Stat))
	{ 
		if ($data['Pay']=='Оплачено')
			{
				echo '<form method="POST" action="show.php?id='.$Set.'&sec='.md5($Set).'">';
				echo '<fieldset>';
				echo '<legend>Изменить данные</legend>';
				echo '<table class="order_table">';
				echo '<tr>';
				echo '<th class="bold ta-l">Вы можете изменить баннер и ссылку</th>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>';
				echo '<img src = "captcha.php" /><br><input  placeholder="Защитный код" type ="text" name ="edit" required/>';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>';
				echo '<input  placeholder="Ваш Email при заказе" type ="email" name ="mail_order" required/>';
				echo '</td>';
				echo ' </tr>';
				echo '<tr>';
				echo ' <td>';
				echo ' <input type="submit" value="Изменить">';
				echo '</td>';
				echo ' </tr>';
				echo '</table>';
				echo ' </fieldset>';
				echo '</form>';					
			} 
			
		if (($data['Pay']=='Ожидает оплаты') || ($data['Pay']=='Модерация после оплаты'))
			{
			
			require_once "admin/go/info_pay.php";
			
			if ($pay_info_robo['Enable']!=0) 
				{
					require_once "admin/go/robo/Show_Form_Pay.php";
				}
				
			if ($pay_info_zpayment['Enable']!=0) 
				{
					require_once "admin/go/payment/Show_Form_Pay.php";
				}
				
			if ($pay_info_inter['Enable']!=0) 
				{
					require_once "admin/go/inter/Show_Form_Pay.php";
				}					

				
			if (mysql_num_rows($EnablePay)==null)
				{
					echo '
					<fieldset>
					<legend>Оплата</legend>
					<table class="order_table">
					<tr>
					<td>Оплата отключена</td>
					</tr>
					</table>
					</fieldset>';
				}
			if ($data['discount']==0)
				{
					echo '
					<fieldset>
					<legend>Скидка</legend>
					<form method="post">
					<table class="order_table">
					<tr>
					<td><input style="width:200px;"type="text" name="discount_coupon" placeholder="Купон скидки (если есть)"/><input style="float:right;"type="submit" name="discount_access" value="Принять"></td>
					</tr>
					</table>
					</form>
					</fieldset>';
				} else {
					echo '
					<fieldset>
					<legend>Скидка</legend>
					<span style="color:green">Действует скидка по купону!</span>
					</fieldset>';
				}				
				mysql_close($link);	
			} 			
	}
	
?>

			</div>

		<div class="clear"></div>

	</div>

</div>

<!-- Заказанные дни -->

<div style="display: none;">
    <div class="popup" id="numday">
        <div class="popup_header ta-l">
            Заказанные дни
            <div class="clear"></div>
        </div>
        <div class="popup_container">
			<div id="showday">
			<?php echo str($data['Do']); ?>
			</div>
        </div>
    </div>
</div>

<?php include 'copyright.php'; ?>
</body>
</html>
<script type="text/javascript">
function ChangeOper()
	{
		var sel = document.getElementById("TypePayRobo"); 
		var val = sel.options[sel.selectedIndex].value; 
		
		document.getElementById("TypeRobo").innerHTML = '<input type=hidden name=IncCurrLabel value='+val+'>';
		if (val == 'YandexMerchantR') {var New_Price = '<?php echo $Price_YandexMerchantR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_YandexMerchantR; ?>';   }
		if (val == 'WMRM') {var New_Price = '<?php echo $Price_WMRM;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_WMRM; ?>';   }
		if (val == 'Qiwi29OceanR') {var New_Price = '<?php echo $Price_Qiwi29OceanR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_Qiwi29OceanR; ?>';   }
		if (val == 'MailRuOceanR') {var New_Price = '<?php echo $Price_MailRuOceanR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_MailRuOceanR; ?>';   }
		if (val == 'ElecsnetWalletR') {var New_Price = '<?php echo $Price_ElecsnetWalletR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_ElecsnetWalletR; ?>';   }
		if (val == 'W1R') {var New_Price = '<?php echo $Price_W1R;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_W1R; ?>';   }
		if (val == 'BANKOCEAN2R') {var New_Price = '<?php echo $Price_BANKOCEAN2R;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_BANKOCEAN2R; ?>';   }
		if (val == 'VTB24R') {var New_Price = '<?php echo $Price_VTB24R;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_VTB24R; ?>';   }
		if (val == 'MegafonR') {var New_Price = '<?php echo $Price_MegafonR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_MegafonR; ?>';   }
		if (val == 'MtsR') {var New_Price = '<?php echo $Price_MtsR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_MtsR; ?>';   }
		if (val == 'RapidaOceanSvyaznoyR') {var New_Price = '<?php echo $Price_RapidaOceanSvyaznoyR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_RapidaOceanSvyaznoyR; ?>';   }
		if (val == 'RapidaOceanEurosetR') {var New_Price = '<?php echo $Price_RapidaOceanEurosetR;?>'; document.getElementById("PriceRobo").innerHTML = '<input type=hidden name=OutSum value='+New_Price+'>';var New_Crc = '<?php echo $crc_RapidaOceanEurosetR; ?>';   }

		document.getElementById("CrcRobo").innerHTML = '<input type=hidden name=SignatureValue value='+New_Crc+'>';
	}

</script>