<?php
require_once "../../config.php";
require_once "../info_pay.php";
require_once "../../try_function.php";
require_once "../../validate.php";

$HTTP = $_POST;
foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }

unset($HTTP['ik_sign']);
ksort($HTTP, SORT_STRING);
array_push($HTTP, $pay_info_inter['SecretKey']);
$signString = implode(':', $HTTP);
$sign = base64_encode(md5($signString, true)); 


if ($ik_sign !=$sign)
	{
	  echo "Оплата не прошла\n";
	  exit();
	} else {
Vint($ik_pm_no);	
connectToDB();
$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
$data = mysql_fetch_array($ShowSetting);

	if ($data['Method']==0)
		{
			mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$ik_pm_no'", $link)  or die (mysql_error($link));
		}
		
	if ($data['Method']==1)
		{
			$CheckPay=mysql_query("SELECT ID FROM Orders WHERE ID='$ik_pm_no' and Pay='Ожидает оплаты'", $link) or die (mysql_error($link));
			
				if (mysql_num_rows($CheckPay)!=null) // Узнаем есть ли активные заказы
					{
						mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$ik_pm_no'", $link)  or die (mysql_error($link));
					}
				else
					{
						mysql_query("UPDATE Orders SET Pay='Оплачено-Модерация' WHERE ID='$ik_pm_no'", $link)  or die (mysql_error($link));	
					}
	
		}
		
	if ($data['Method']==2)
		{
			mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$ik_pm_no'", $link)  or die (mysql_error($link));
		}		
echo "200 OK";	
GetInformationAutorMail();
GetInformationOrder($ik_pm_no);
GoMail($mail_admin,'БаннерБро - Заказ оплачен','На сайте - <b>'.$_SERVER['HTTP_HOST'].'</b> <br>Клиент - <b>'.$Order_Email.'</b> ('.$Order_User.')<br><br> <span style="color:green">Оплатил заказ </span><br><br>Пожалуйста промодерируйте его в ближайшее время <br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/">Панель администратора</a>');	
GoMail($Order_Email,'БаннерБро - Заказ оплачен','Вы оплатили размещение своей рекламы на сайте '.$_SERVER['HTTP_HOST'].'.<br><br>Робот БаннерБро предупредит администратора сайта и он в ближайшее время промодерирует ваш заказ. <br><br>Email администратора  '.$mail_admin.'<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$ik_pm_no.'&sec='.md5($ik_pm_no).'">Ссылка на ваш заказ</a><br><br>Спасибо, что воспользовались системой БаннерБро!');
mysql_close($link);
}
?>