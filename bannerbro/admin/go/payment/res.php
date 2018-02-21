<?php
require_once "../../config.php";
require_once "../../try_function.php";
require_once "../info_pay.php";
require_once "../../validate.php";

require_once('config_zp.php');

if($ResultMethod=='POST') $HTTP = $_POST; 
else $HTTP = $_GET;

foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }

$CalcHash = md5($LMI_PAYEE_PURSE.$LMI_PAYMENT_AMOUNT.$LMI_PAYMENT_NO.$LMI_MODE.$LMI_SYS_INVS_NO.$LMI_SYS_TRANS_NO.$LMI_SYS_TRANS_DATE.$SecretKeyZP.$LMI_PAYER_PURSE.$LMI_PAYER_WM);

$file = 'log.txt';
$current=$LMI_HASH.'--'.strtoupper($CalcHash).'--'.$SecretKeyZP;
file_put_contents($file, $current);

if($LMI_HASH == strtoupper($CalcHash)) {

connectToDB();
$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
$data = mysql_fetch_array($ShowSetting);
Vint($LMI_PAYMENT_NO);
	if ($data['Method']==0)
		{
			mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$LMI_PAYMENT_NO'", $link)  or die (mysql_error($link));
		}
		
	if ($data['Method']==1)
		{
			$CheckPay=mysql_query("SELECT ID FROM Orders WHERE ID='$LMI_PAYMENT_NO' and Pay='Ожидает оплаты'", $link) or die (mysql_error($link));
			
				if (mysql_num_rows($CheckPay)!=null) // Узнаем есть ли активные заказы
					{
						mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$LMI_PAYMENT_NO'", $link)  or die (mysql_error($link));
					}
				else
					{
						mysql_query("UPDATE Orders SET Pay='Оплачено-Модерация' WHERE ID='$LMI_PAYMENT_NO'", $link)  or die (mysql_error($link));	
					}
	
		}
		
	if ($data['Method']==2)
		{
			mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$LMI_PAYMENT_NO'", $link)  or die (mysql_error($link));
		}		
		

echo 'YES';
GetInformationAutorMail();
GetInformationOrder($LMI_PAYMENT_NO);
GoMail($mail_admin,'БаннерБро - Заказ оплачен','На сайте - <b>'.$_SERVER['HTTP_HOST'].'</b> <br>Клиент - <b>'.$Order_Email.'</b> ('.$Order_User.')<br><br> <span style="color:green">Оплатил заказ </span><br><br>Пожалуйста промодерируйте его в ближайшее время <br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/">Панель администратора</a>');
GoMail($Order_Email,'БаннерБро - Заказ оплачен','Вы оплатили размещение своей рекламы на сайте '.$_SERVER['HTTP_HOST'].'.<br><br>Робот БаннерБро предупредит администратора сайта и он в ближайшее время промодерирует ваш заказ. <br><br>Email администратора  '.$mail_admin.'<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$LMI_PAYMENT_NO.'&sec='.md5($LMI_PAYMENT_NO).'">Ссылка на ваш заказ</a><br><br>Спасибо, что воспользовались системой БаннерБро!');
mysql_close($link);
} else {
echo 'NO';
mysql_close($link);
}
?>
