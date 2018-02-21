<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="/bannerbro/css/style.css"/>
<title>Прием платежа</title>		
</head>
<body>
<div class="wrapper">	
<?php
require_once "../../config.php";
require_once "../../validate.php";
require_once "../info_pay.php";

require_once('config_zp.php');

if($SuccessMethod=='POST') $HTTP = $_POST; 
else $HTTP = $GET;

foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }
if (isset($LMI_PAYMENT_NO)){
Vint($LMI_PAYMENT_NO);
connectToDB();
$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
$data = mysql_fetch_array($ShowSetting);

	if ($data['Method']==0)
		{
			$SB=mysql_query("SELECT * FROM Orders WHERE ID='$LMI_PAYMENT_NO' and Pay='Оплачено'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
				{
					echo '<div class="queue ta-c">
					<div class="green message">Оплата прошла успешно. Теперь ваш баннер отображается на сайте.</div><br>
					<a href="/bannerbro/show.php?id='.$LMI_PAYMENT_NO.'&sec='.md5($LMI_PAYMENT_NO).'" target="_blank">Ваша ссылка состояния заказа</a><p>
					<a href="/bannerbro">Купить еще один баннер</a></div>';
				}
		}
		
	if ($data['Method']==1)
		{
			$SB=mysql_query("SELECT * FROM Orders WHERE ID='$LMI_PAYMENT_NO' and Pay='Оплачено-Модерация'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
				{
					echo '<div class="queue ta-c">
					<div class="green message">Оплата прошла успешно. <br>Ваш заказ отправлен на модерацию. После одобрения вы получете сообщение на свой Email.</div><br>
					<a href="/bannerbro/show.php?id='.$LMI_PAYMENT_NO.'&sec='.md5($LMI_PAYMENT_NO).'" target="_blank">Ваша ссылка состояния заказа</a><p>
					<a href="/bannerbro">Купить еще один баннер</a></div>';
				}
		}	

	if ($data['Method']==2)
		{
			$SB=mysql_query("SELECT * FROM Orders WHERE ID='$LMI_PAYMENT_NO' and Pay='Оплачено'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
				{
					echo '<div class="queue ta-c">
					<div class="green message">Оплата прошла успешно. Теперь ваш баннер отображается на сайте.</div><br>
					<a href="/bannerbro/show.php?id='.$LMI_PAYMENT_NO.'&sec='.md5($LMI_PAYMENT_NO).'" target="_blank">Ваша ссылка состояния заказа</a><p>
					<a href="/bannerbro">Купить еще один баннер</a></div>';
				}
		}		

mysql_close($link);	
} else {
			echo '
				<div class="queue ta-c"><div class="red message">Ваш заказ обрабатывается!</div></div>';
}
?>
<div class="clear"></div>
</div>
</body>
</html>