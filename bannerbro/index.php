<?php
include 'header.php'; 
connectToDB();

$GetInfoAdmin=mysql_query("SELECT * FROM Admin",$link)  or die (mysql_error($link));
$data_info = mysql_fetch_array($GetInfoAdmin);

include 'bro.php'; 

queue();

$filename = 'images/'.$_SERVER['HTTP_HOST'].'.jpg';
if (!file_exists($filename) && $data_info['Scrin']==0) 
	{
		save_screenshot('images/', 'http://'.$_SERVER['HTTP_HOST'].'/', '1280x1024', '420', 'jpg');
	}
$Scrin_info=explode("/", $data_info['Scrin']);
?>
<title>Оформление заказа ШАГ 1</title>
<body class="body">
<section class="wrapper">
<?php echo '<noscript><div style="text-align:center; height:100px; background:green; font-size:36px; color:white;">Пожалуйста, включите JavaScript :)</div></noscript>'; ?>
<?php queue_check(); ?>
<div class="steps_container">
    <table>
        <tr>
            <td class="active">Шаг 1</td>
            <td class="border">Шаг 2</td>
            <td>Шаг 3</td>
        </tr>
    </table>
    <div class="clear"></div>
</div>
<?php left_cont(); mysql_close($link); ?>
<div  class="actions fl-r">
	<div id='something' style="display: block;">
		<?php echo $data_info['WhatHello']; ?>
			<div class="site_scrin">
			<?php if ($data_info['Scrin']==0) {if (file_exists($filename)){echo '<img src="/bannerbro/images/'.$_SERVER['HTTP_HOST'].'.jpg">'; }}?>
			<?php if ($data_info['Scrin']!=0) {if (file_exists('images/'.$Scrin_info[1])){echo '<img width="420px" src="/bannerbro/images/'.$Scrin_info[1].'">'; }}?>	
				*Вы покупаете рекламные баннеры на сайте <?php echo '<a target="_blank" href="http://'.$_SERVER['HTTP_HOST'].'">'.$_SERVER['HTTP_HOST'].'</a>'; ?>
			</div>
	</div>
	
	
			<div id='yourday' style="display:none;">
			<form onsubmit='return daysend()' action='step2.php' method="post" autocomplete="off">
			<?php
			echo '<fieldset>
					<legend id="change_day">Введите данные</legend> 
					<div id="dayHowcount" style="display:none;"></div>
					<div id="calendar" onclick="Show()"></div>
					<table class="order_table">
					<tr><td colspan="2"><span class="discount_info" id="day_discount_info"></span></td></tr>
					<tr>
					<td id="skidday">Сумма</td>
					<td><input id=\'count\' type=\'text\' name="MyCount" value="0" readonly/></td>
					</tr>
					<tr>
					<td>Ваше имя</td>
					<td><input id=\'nameday\' type=\'text\' name="nameday" value="" required/></td>
					</tr>
					<tr>
					<td>Ваш Email</td>
					<td><input id=\'emailday\' type=\'email\' name="emailday" value="" required/></td>
					</tr>
					<tr>
					<td colspan="2" class="ta-r"><input type="submit" value="Принять"></td>
					</tr>
					</table>
					</fieldset>
					<div style="padding:10px; font-size:12px;">
					*Примечание
					<table>
					<tr><td><div style="background:#88FC88; width:59px; height:25px;"></div></td><td>- свободно</td></tr>
					<tr><td><div style="background:#EFEFEF; width:59px; height:25px; margin-top:4px;"></div></td><td>- занято или день прошел</td></tr>
					<tr><td><div style="background:#00CCFF; width:59px; height:25px; margin-top:4px;"></div></td><td>- выбранный день</td></tr>
					</table>
					</div>'; 
			?>	
			<span id='dayerr' style="align:center;" class='error'></span>
			<div style="display:none;"><input id='altField' name='altField' type='text' readonly /><input id='MyDayPosition' type='text' name="MyDayPosition" /></div>
			</form>
			</div> 
	
			<div id='yourclick' style="display:none;">
			<form onsubmit='return clicksend()' action='step2.php' method="post" autocomplete="off">
			<?php
			echo '<fieldset>
					<legend id="change_click">Введите данные</legend>
					<div id="clickHowcount" style="display:none;"></div>
					<table class="order_table">
					<tr><td colspan="2"><span class="discount_info" id="click_discount_info"></span></td></tr>
					<tr>
					<td>Количество кликов</td>
					<td><input id=\'Myclick\' type=\'text\' name="Myclick" value="" onKeyUp="paymentclick()" pattern="^[ 0-9]+$" required/></td>
					</tr>
					<tr>
					<td id="skidclick">Сумма</td>
					<td><input id=\'priceclick\' type=\'text\' name="priceclick" value="0" readonly/></td>
					</tr>
					<tr>
					<td>Ваше имя</td>
					<td><input id=\'nameclick\' type=\'text\' name="nameclick" value="" required/></td>
					</tr>
					<tr>
					<td>Ваш Email</td>
					<td><input id=\'emailclick\' type=\'email\' name="emailclick" value="" required/></td>
					</tr>
					<tr>
					<td colspan="2" class="ta-r"><input type="submit" value="Принять"></td>
					</tr>
					</table>
					</fieldset>';
			?>
			<div style="display:none;"><input id='MyClickPosition' type='text' name="MyClickPosition" /></div>			
			<span id='clickserr' style="align:center;" class='error'></span>
			</form>
			</div>
		
			<div id='yourshows' style="display:none;">
			<form onsubmit='return showssend()' action='step2.php' method="post" autocomplete="off">
			<?php
			echo '<fieldset>
					<legend id="change_shows">Введите данные</legend>
					<div id="ShowsHowcount" style="display:none;"></div>
					<table class="order_table">
					<tr><td colspan="2"><span class="discount_info" id="show_discount_info"></span></td></tr>
					<tr>
					<td>Количество показов</td>
					<td><input id=\'Myshows\' type=\'text\' name="MyShows" value="" onKeyUp="paymentshows()" pattern="^[ 0-9]+$" required/></td>
					</tr>
					<tr>
					<td id="skidshow">Сумма</td>
					<td><input id=\'priceshows\' type=\'text\' name="priceshows" value="0" readonly/></td>
					</tr>
					<tr>
					<td>Ваше имя</td>
					<td><input id=\'nameshows\' type=\'text\' name="nameshows" value="" required/></td>
					</tr>
					<tr>
					<td>Ваш Email</td>
					<td><input id=\'emailshows\' type=\'email\' name="emailshows" value="" required/></td>
					</tr>
					<tr>
					<td colspan="2" class="ta-r"><input type="submit" value="Принять"></td>
					</tr>
					</table>
					</fieldset>';
			?>
			<div style="display:none;"><input id='MyShowsPosition' type='text' name="MyShowsPosition"/></div>	
			<span id='showsserr' style="align:center;" class='error'></span>
			</form>
			</div> 
	
	
			<div id='queue' style="display:none;">
			<form method="post">
			<?php
			echo '<fieldset>
					<legend id="change_queue">Введите данные</legend>
					<div class="warning">
					Извините, на этой позиции не осталось свободных мест, но вы можете встать в очередь и как только место освободится мы дадим вам знать.
					</div>
					<table class="order_table">
					<tr>
					<td>Ваше Имя</td>
					<td><input id=\'queue_name\' type=\'text\' name="queue_name" value="" required/></td>
					</tr>
					<tr>
					<td>Ваше Email</td>
					<td><input id=\'queue_email\' type=\'email\' name="queue_email" value="" required/></td>
					</tr>
					<tr>
					<td colspan="2" class="ta-r"><input type="submit" value="Встать в очередь"></td>
					</tr>
					</table>';
			?>
			<div style="display:none;"><input id='MyQueuePosition' type='text' name="MyQueuePosition" /></div>
                        </form>
			</div> 
	
	</div>	
	<div class="clear"></div>
</div>
</section>

<?php include 'bottommenu.php' ; ?>

<script> 
var MyCount=0;
var MyStringCount="";
var a = '01/01/2014';
arr = a.split(',');
$('#calendar').multiDatesPicker({firstDay: 1,minDate: 1,maxDate: 90,altField: '#altField',addDisabledDates:arr});
document.getElementById('MyPos').value = "1";
</script> 
</body>
</html>