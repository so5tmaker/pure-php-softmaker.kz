<?php
			$mrh_login = $pay_info_robo['login'];
			$mrh_pass1 = $pay_info_robo['password1'];
			$inv_id = $_GET['id'];
			$inv_desc = "Покупка баннера";
			
			$shp_item = 'bannerbro';
			$in_curr = "YandexMerchantR";
			$sIncCurrLabel ="R";
			$culture = "ru";

			require_once "proc.php";

			$out_summ = $Price_YandexMerchantR;
			
			$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
			
				print
				  "<form action='https://merchant.roboxchange.com/Index.aspx' method=POST>".
				  "<fieldset>".
				  "<legend>Оплата Robokassa</legend>".
				  
					"<table class='order_table'>
					<tr>
					<td style='text-align:center; font-size:20px; color:green;'>
					<img src='/bannerbro/images/robokassa.png' width='200px'><br>
					К оплате <strong>".$data['Price']."</strong> руб<br>
					</td>
					</tr>
					<tr>
					<td style='text-align:center;'>
					Способ оплаты 
					<select id='TypePayRobo' onclick=\"ChangeOper()\" required>
						<option value='YandexMerchantR' selected>Яндекс деньги</option>
						<option value='WMRM'>WMR</option>
						<option value='Qiwi29OceanR'>Qiwi</option>
						<option value='MailRuOceanR'>Деньги mail.ru</option>
						<option value='ElecsnetWalletR'>Элекснет</option>
						<option value='W1R'>W1</option>
						<option value='BANKOCEAN2R'>Банковской картой</option>
						<option value='VTB24R'>ВТБ 24</option>
						<option value='MegafonR'>Мегафон</option>
						<option value='MtsR'>МТС</option>
						<option value='RapidaOceanSvyaznoyR'>Связной</option>
						<option value='RapidaOceanEurosetR'>Евросеть</option>
					</select>
					</td>				
					</tr>
					<tr>
					<td style='text-align:right;'>
					<input type='submit' name='pay' value='Оплатить'>
					</td>
					</tr>
					</table>".
				  
				  "<input type=hidden name=MrchLogin value=$mrh_login>".
				  "<div id='PriceRobo'><input type=hidden name=OutSum value=$out_summ></div>".
				  "<input type=hidden name=InvId value=$inv_id>".
				  "<input type=hidden name=Desc value='$inv_desc'>".
				  "<div id='CrcRobo'><input type=hidden name=SignatureValue value=$crc></div>".
				  "<input type=hidden name=Shp_item value='$shp_item'>".
				  "<div id='TypeRobo'><input type=hidden name=IncCurrLabel value=$in_curr></div>".
				  "<input type=hidden name=Culture value=$culture>".
				  "<input type=hidden name=sIncCurrLabel value=$sIncCurrLabel>".
				  "</fieldset>".
				  "</form>";
?>				  