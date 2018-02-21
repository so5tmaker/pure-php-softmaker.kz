<?php
////////////////////////////////////////////////////////////////
//		Z-Payment, система приема и обработки платежей 		  //
//		All rights reserved © 2007, BillingSoft Inc.		  //
////////////////////////////////////////////////////////////////
//Файл основных переменных 

//ID магазина в Z-Payment
$IdShopZP = $pay_info_zpayment['ID_Shop'];
//Merhant Key ключ магазина
$SecretKeyZP = $pay_info_zpayment['SecretKey'];
//Метод передачи данных на Result URL
$ResultMethod = 'POST'; //  GET
//Метод передачи данных на Success URL
$SuccessMethod = 'POST'; //  GET
//Матод передачи данных на Fail URL
$FailMethod = 'POST';  //  GET


?>
