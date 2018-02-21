<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="/bannerbro/css/style.css"/>
</head>
<body>
<div class="wrapper">	
<?php
require_once "../../config.php";
require_once "../info_pay.php";
require_once "../../validate.php";
// регистрационная информация (пароль #1)
// registration info (password #1)
$mrh_pass1 = $pay_info_robo['password1'];

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

// проверка корректности подписи
// check signature
if ($my_crc != $crc)
	{
	  echo "Оплата не прошла\n";
	  exit();
	}
Vint($inv_id);
// проверка наличия номера счета в истории операций
// check of number of the order info in history of operations
connectToDB();
$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
$data = mysql_fetch_array($ShowSetting);
	if ($data['Method']==0)
		{
			$SB=mysql_query("SELECT * FROM Orders WHERE ID='$inv_id' and Pay='Оплачено'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
				{
					echo '<div class="queue ta-c">
					<div class="green message">Оплата прошла успешно. Теперь ваш баннер отображается на сайте.</div><br>
					<a href="/bannerbro/show.php?id='.$inv_id.'&sec='.md5($inv_id).'" target="_blank">Ваша ссылка состояния заказа</a><p>
					<a href="/bannerbro">Купить еще один баннер</a></div>';
				}
		}
		
	if ($data['Method']==1)
		{
			$SB=mysql_query("SELECT * FROM Orders WHERE ID='$inv_id' and Pay='Оплачено-Модерация'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
				{
					echo '<div class="queue ta-c">
					<div class="green message">Оплата прошла успешно. <br>Ваш заказ отправлен на модерацию. После одобрения вы получете сообщение на свой Email.</div><br>
					<a href="/bannerbro/show.php?id='.$inv_id.'&sec='.md5($inv_id).'" target="_blank">Ваша ссылка состояния заказа</a><p>
					<a href="/bannerbro">Купить еще один баннер</a></div>';
				}
		}	

	if ($data['Method']==2)
		{
			$SB=mysql_query("SELECT * FROM Orders WHERE ID='$inv_id' and Pay='Оплачено'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
				{
					echo '<div class="queue ta-c">
					<div class="green message">Оплата прошла успешно. Теперь ваш баннер отображается на сайте.</div><br>
					<a href="/bannerbro/show.php?id='.$inv_id.'&sec='.md5($inv_id).'" target="_blank">Ваша ссылка состояния заказа</a><p>
					<a href="/bannerbro">Купить еще один баннер</a></div>';
				}
		}		

mysql_close($link);	
?>
<div class="clear"></div>
</div>
</body>
</html>