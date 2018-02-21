<?php
require_once('config_zp.php');

				print
				  "<form id='pay' name='pay' method='post' action='https://z-payment.com/merchant.php'>".
				  "<fieldset>".
				  "<legend>Оплата Z-payment</legend>".
					"<table class='order_table'>
					<tr>
						<td style='text-align:center; font-size:20px; color:green;'>
						<img src='/bannerbro/images/zpayment.png' width='100px'><br>
						К оплате <strong>".$data['Price']."</strong> руб<br>
						</td>
					</tr>

					<tr>
						<td style='text-align:right;'>
						<input type='submit' value='Оплатить'>
						</td>
					</tr>
					</table>".
				 "<input name='LMI_PAYMENT_NO' type='hidden' value='".$data['ID']."'>".	
				 "<input name='LMI_PAYMENT_AMOUNT' type='hidden' value='".$data['Price']."'>".	
				 "<input name='CLIENT_MAIL' type='hidden' value='".$data['Email']."'>".	
				 "<input name='LMI_PAYMENT_DESC' type='hidden' value='Покупка баннера'>".
				 "<input name='LMI_PAYEE_PURSE' type='hidden' value='".$IdShopZP."'>".
				 "</form>".
				 "</fieldset>";

?>