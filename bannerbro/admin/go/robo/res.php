<?php
require_once "../../config.php";
require_once "../info_pay.php";
require_once "../../validate.php";
require_once "../../try_function.php";

$mrh_pass2 = $pay_info_robo['password2'];

$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

if ($my_crc !=$crc)
	{
	  echo "Оплата не прошла\n";
	  exit();
	} else {
Vint($inv_id);	
connectToDB();
$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
$data = mysql_fetch_array($ShowSetting);

	if ($data['Method']==0)
		{
			mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$inv_id'", $link)  or die (mysql_error($link));
		}
		
	if ($data['Method']==1)
		{
			$CheckPay=mysql_query("SELECT ID FROM Orders WHERE ID='$inv_id' and Pay='Ожидает оплаты'", $link) or die (mysql_error($link));
			
				if (mysql_num_rows($CheckPay)!=null) // Узнаем есть ли активные заказы
					{
						mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$inv_id'", $link)  or die (mysql_error($link));
					}
				else
					{
						mysql_query("UPDATE Orders SET Pay='Оплачено-Модерация' WHERE ID='$inv_id'", $link)  or die (mysql_error($link));	
					}
	
		}
		
	if ($data['Method']==2)
		{
			mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$inv_id'", $link)  or die (mysql_error($link));
		}		
echo "OK$inv_id\n";		
GetInformationAutorMail();
GetInformationOrder($inv_id);
GoMail($mail_admin,'БаннерБро - Заказ оплачен','На сайте - <b>'.$_SERVER['HTTP_HOST'].'</b> <br>Клиент - <b>'.$Order_Email.'</b> ('.$Order_User.')<br><br> <span style="color:green">Оплатил заказ </span><br><br>Пожалуйста промодерируйте его в ближайшее время <br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/">Панель администратора</a>');
GoMail($Order_Email,'БаннерБро - Заказ оплачен','Вы оплатили размещение своей рекламы на сайте '.$_SERVER['HTTP_HOST'].'.<br><br>Робот БаннерБро предупредит администратора сайта и он в ближайшее время промодерирует ваш заказ. <br><br>Email администратора  '.$mail_admin.'<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$inv_id.'&sec='.md5($inv_id).'">Ссылка на ваш заказ</a><br><br>Спасибо, что воспользовались системой БаннерБро!');
mysql_close($link);
}

?>