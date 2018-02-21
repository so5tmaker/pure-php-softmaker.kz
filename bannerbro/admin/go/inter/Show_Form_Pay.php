<?php
				print
				  "<form id='payment' name='payment' method='post' action='https://sci.interkassa.com/' enctype='utf-8'>".
				  "<fieldset>".
				  "<legend>Оплата Интеркасса 2.0</legend>".
					"<table class='order_table'>
					<tr>
						<td style='text-align:center; font-size:20px; color:green;'>
						<img src='/bannerbro/images/interkassa.png' width='100px'><br>
						К оплате <strong>".$data['Price']."</strong> руб<br>
						</td>
					</tr>

					<tr>
						<td style='text-align:right;'>
						<input type='submit' value='Оплатить'>
						</td>
					</tr>
					</table>".
				"<input type='hidden' name='ik_co_id' value='".$pay_info_inter['ID_Shop']."' />".
				"<input type='hidden' name='ik_pm_no' value='".$data['ID']."' />".
				"<input type='hidden' name='ik_am' value='".$data['Price']."' />".
				"<input type='hidden' name='ik_cur' value='RUB' />".
				"<input type='hidden' name='ik_desc' value='Покупка баннера' />".
				"<input type='hidden' name='ik_loc' value='ru' />".
				"<input type='hidden' name='ik_enc' value='utf-8' />".
				"<input type='hidden' name='ik_int' value='web' />".
				"<input type='hidden' name='ik_am_t' value='payway' />".				 
				"</form>";	
?>