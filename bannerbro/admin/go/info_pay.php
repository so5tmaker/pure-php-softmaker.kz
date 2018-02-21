<?php
connectToDB();

$EnablePay=mysql_query("SELECT * FROM Pay WHERE Enable!=0",$link)  or die (mysql_error($link));
	$pay_enable = mysql_fetch_array($EnablePay);

$GetInfoPay_Robo=mysql_query("SELECT * FROM Pay WHERE ID='0'",$link)  or die (mysql_error($link));
	$pay_info_robo = mysql_fetch_array($GetInfoPay_Robo);

$GetInfoPay_Zpayment=mysql_query("SELECT * FROM Pay WHERE ID='1'",$link)  or die (mysql_error($link));
	$pay_info_zpayment = mysql_fetch_array($GetInfoPay_Zpayment);
	
$GetInfoPay_Inter=mysql_query("SELECT * FROM Pay WHERE ID='2'",$link)  or die (mysql_error($link));
	$pay_info_inter = mysql_fetch_array($GetInfoPay_Inter);	
?>