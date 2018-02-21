<?php
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=YandexMerchantR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_YandexMerchantR = $xml->OutSum; if ($Price_YandexMerchantR==0) $Price_YandexMerchantR=$data['Price'];
			$crc_YandexMerchantR  = md5("$mrh_login:$Price_YandexMerchantR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=WMRM&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_WMRM = $xml->OutSum; if ($Price_WMRM==0) $Price_WMRM=$data['Price'];		
			$crc_WMRM  = md5("$mrh_login:$Price_WMRM:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=Qiwi29OceanR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_Qiwi29OceanR = $xml->OutSum;	if ($Price_Qiwi29OceanR==0) $Price_Qiwi29OceanR=$data['Price'];
			$crc_Qiwi29OceanR  = md5("$mrh_login:$Price_Qiwi29OceanR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=MailRuOceanR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_MailRuOceanR = $xml->OutSum;	if ($Price_MailRuOceanR==0) $Price_MailRuOceanR=$data['Price'];
			$crc_MailRuOceanR  = md5("$mrh_login:$Price_MailRuOceanR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=ElecsnetWalletR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_ElecsnetWalletR = $xml->OutSum;  if ($Price_ElecsnetWalletR==0) $Price_ElecsnetWalletR=$data['Price'];
			$crc_ElecsnetWalletR  = md5("$mrh_login:$Price_ElecsnetWalletR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=W1R&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_W1R = $xml->OutSum;	if ($Price_W1R==0) $Price_W1R=$data['Price'];
			$crc_W1R  = md5("$mrh_login:$Price_W1R:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=BANKOCEAN2R&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_BANKOCEAN2R = $xml->OutSum;	if ($Price_BANKOCEAN2R==0) $Price_BANKOCEAN2R=$data['Price'];
			$crc_BANKOCEAN2R  = md5("$mrh_login:$Price_BANKOCEAN2R:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=VTB24R&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_VTB24R = $xml->OutSum;	if ($Price_VTB24R==0) $Price_VTB24R=$data['Price'];
			$crc_VTB24R  = md5("$mrh_login:$Price_VTB24R:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=MegafonR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_MegafonR = $xml->OutSum;	if ($Price_MegafonR==0) $Price_MegafonR=$data['Price'];
			$crc_MegafonR  = md5("$mrh_login:$Price_MegafonR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=MtsR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_MtsR = $xml->OutSum;	if ($Price_MtsR==0) $Price_MtsR=$data['Price'];
			$crc_MtsR  = md5("$mrh_login:$Price_MtsR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=RapidaOceanSvyaznoyR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_RapidaOceanSvyaznoyR = $xml->OutSum;	if ($Price_RapidaOceanSvyaznoyR==0) $Price_RapidaOceanSvyaznoyR=$data['Price'];
			$crc_RapidaOceanSvyaznoyR  = md5("$mrh_login:$Price_RapidaOceanSvyaznoyR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			$url = 'https://auth.robokassa.ru/Merchant/WebService/Service.asmx/CalcOutSumm?MerchantLogin=imakescript.ru&IncCurrLabel=RapidaOceanEurosetR&IncSum='.$data['Price'];$xml = simplexml_load_file($url);$Price_RapidaOceanEurosetR = $xml->OutSum;	if ($Price_RapidaOceanEurosetR==0) $Price_RapidaOceanEurosetR=$data['Price'];					
			$crc_RapidaOceanEurosetR  = md5("$mrh_login:$Price_RapidaOceanEurosetR:$inv_id:$mrh_pass1:Shp_item=$shp_item");
?>