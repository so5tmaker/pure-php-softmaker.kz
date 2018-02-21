<?php  
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[0]!="2"){ header("Location:no.php");}

connectToDB();

define('i',"0");
define('u',"0");

$Method_setting=mysql_query ("SELECT Method FROM Admin", $link)  or die (mysql_error($link));
$data_admin = mysql_fetch_array($Method_setting);

$Count_Positions=mysql_query("SELECT * FROM Setting",$link)  or die (mysql_error($link));
$Count_Users=mysql_query ("SELECT COUNT(*) AS u,Position,sum(Price)  FROM Orders WHERE Pay='Оплачено' or Pay='End' or Pay='Hide' GROUP BY Position ORDER BY sum(Price) DESC", $link)  or die (mysql_error($link));
$Best_Positions = mysql_fetch_array($Count_Users);

$Count_New=mysql_query("SELECT * FROM Orders WHERE Pay='Ожидает одобрения'",$link)  or die (mysql_error($link));
$Count_Wait=mysql_query("SELECT * FROM Orders WHERE Pay='Ожидает оплаты'",$link)  or die (mysql_error($link));

$Count_Moderbefore_pay=mysql_query("SELECT * FROM Orders WHERE Pay='Модерация после оплаты'",$link)  or die (mysql_error($link));
$Count_Pay_Moder=mysql_query("SELECT * FROM Orders WHERE Pay='Оплачено-Модерация'",$link)  or die (mysql_error($link));

$Count_Good=mysql_query("SELECT * FROM Orders WHERE Pay='Оплачено'",$link)  or die (mysql_error($link));
$Count_Queue=mysql_query("SELECT * FROM Queue",$link)  or die (mysql_error($link));
$Count_Re=mysql_query("SELECT * FROM Orders WHERE Pay='Оплачено' and new_ban!='' and new_url!=''", $link)  or die (mysql_error($link));
$Count_Bad=mysql_query("SELECT * FROM Orders WHERE Pay='End'",$link)  or die (mysql_error($link));
$Count_Users=mysql_query ("SELECT COUNT(*) AS i,Email,User,sum(Price) FROM Orders WHERE Pay='Оплачено' or Pay='End' or Pay='Hide' GROUP BY Email ORDER BY i DESC", $link)  or die (mysql_error($link));
$Best_Users = mysql_fetch_array($Count_Users);

$income_day=mysql_query("SELECT sum(Price) FROM Orders WHERE admin_order!='1' and (date >= CURDATE()) AND (Pay='Оплачено' or Pay='End' or Pay='Hide')",$link)  or die (mysql_error($link));
$money_day = mysql_fetch_array($income_day); if ($money_day['sum(Price)']==null) $money_day['sum(Price)']=0; 
$income_yesterday=mysql_query("SELECT sum(Price) FROM Orders WHERE admin_order!='1' and (date >= (CURDATE()-1) AND date < CURDATE()) AND (Pay='Оплачено' or Pay='End' or Pay='Hide')",$link)  or die (mysql_error($link));
$money_yesterday = mysql_fetch_array($income_yesterday); if ($money_yesterday['sum(Price)']==null) $money_yesterday['sum(Price)']=0; 
$income_week=mysql_query("SELECT sum(Price) FROM Orders WHERE admin_order!='1' and (date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)) AND (Pay='Оплачено' or Pay='End' or Pay='Hide')",$link)  or die (mysql_error($link));
$money_week = mysql_fetch_array($income_week); if ($money_week['sum(Price)']==null) $money_week['sum(Price)']=0;
$income_month=mysql_query("SELECT sum(Price) FROM Orders WHERE admin_order!='1' and (date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)) AND (Pay='Оплачено' or Pay='End' or Pay='Hide')",$link)  or die (mysql_error($link));
$money_month = mysql_fetch_array($income_month); if ($money_month['sum(Price)']==null) $money_month['sum(Price)']=0;
$income_all=mysql_query("SELECT sum(Price) FROM Orders WHERE admin_order!='1' and (Pay='Оплачено' or Pay='End' or Pay='Hide')",$link)  or die (mysql_error($link));
$money_all = mysql_fetch_array($income_all); if ($money_all['sum(Price)']==null) $money_all['sum(Price)']=0;

require_once "function.php";
?>

<?php include 'header.php'; ?>

<title>Главная страница</title>
</head>
<body>

<div class="wrapper">

<?php include 'menu.php'; ?>
<?php if (isset($_GET['n'])){ $inav=$_GET['n']-1; $inav=$inav*10;} else { $inav=0; } ?>
<div class="content">
<?php
$filename = '../install';
if (file_exists($filename)) {
    echo '<span style="color:green; text-align:center;"><h2>Добро пожаловать в БаннерБро!</h2></span>'; 
	Print '
	<fieldset>
		<legend>Первым делом</legend>
			<table class="order_table">
				<tr>
					<td></td>
					<td><strong>Шаг 1</strong>. <span style="color:green;">Настройте отправку почты.</span><br> Настройка почты является одной из самых важных функций взаимодействия веб-мастера и рекламодателя, так же она поможет вам вспомнить утраченный пароль.<br><br><a href="/bannerbro/admin/mail.php"><span style="font-style:oblique">Перейти к настройке</span></a></td>
				</tr>
				<tr>
					<td></td>
					<td><strong>Шаг 2</strong>. <span style="color:green;">Удалите папку install.</span><br>Папка install находится в корневой папке скрипта БаннерБро.</td>
				</tr>
			</table>
			</div></div>
	</fieldset>';
	mysql_close($link); include 'footer.php';
	exit;
	} 
?>	
<div class="Hello_Admin">Здравствуйте, <?php echo $Name_Admin.'! <span style="color:silver">( '.$Email_Admin.' )</span>'; ?></div>
<fieldset style="margin-bottom:10px;">
    <legend>Статистика</legend>
    <div class="first_line fl-l">
        <table class="order_table" cellspacing="5">
            <tr>
                <th colspan="2">Позиции</th>
            </tr>
            <tr>
                <td class="stattd">Всего:</td>
                <td class="tb_stat"><?php echo mysql_num_rows($Count_Positions); ?></td>
            </tr>
            <tr>
                <td class="stattd">Лучшая позиция:</td>
                <td class="tb_stat"><?php echo $Best_Positions['Position']; ?></td>
            </tr>
        </table>

        <table class="order_table" style="margin-top:15px;">
            <tr>
                <th colspan="2">Клиенты</th>
            </tr>
            <tr>
                <td class="stattd">Всего клиентов:</td>
                <td class="tb_stat"><?php echo mysql_num_rows($Count_Users); ?></td>
            </tr>
            <tr>
                <td class="stattd">Лучший клиент:</td>
                <td class="tb_stat"><?php echo $Best_Users['Email']; ?></td>
            </tr>
        </table>

        <table class="order_table" style="margin-top:15px;" cellpadding="0">
            <tr>
                <th colspan="5">Доходы</th>
            </tr>		
            <tr>
                <td class="stattdp" colspan="3" id="textcache">Прибыль за сегодня:</td>
                <td class="tb_statp" colspan="2" id="textincome"><?php if (isset($money_day['sum(Price)'])) {echo $money_day['sum(Price)'].' р.';} else { echo '---'; } ?></td>
            </tr>
        </table>
		
        <table class="order_table_button_stat">
            <tr>
			<?php
				echo'
                <td><input onclick="document.getElementById(\'textcache\').innerHTML = \'Прибыль за сегодня:\';document.getElementById(\'textincome\').innerHTML = \''.$money_day['sum(Price)'].' р.\';" style="width:85px;" type="submit" value="За сегодня"></td>
				<td><input onclick="document.getElementById(\'textcache\').innerHTML = \'Прибыль за вчера:\';document.getElementById(\'textincome\').innerHTML = \''.$money_yesterday['sum(Price)'].' р.\';" style="width:85px;" type="submit" value="За вчера"></td>
				<td><input onclick="document.getElementById(\'textcache\').innerHTML = \'Прибыль за неделю:\';document.getElementById(\'textincome\').innerHTML = \''.$money_week['sum(Price)'].' р.\';" style="width:85px;" type="submit" value="За неделю"></td>
				<td><input onclick="document.getElementById(\'textcache\').innerHTML = \'Прибыль за месяц:\';document.getElementById(\'textincome\').innerHTML = \''.$money_month['sum(Price)'].' р.\';" style="width:85px;" type="submit" value="За месяц"></td>
				<td><input onclick="document.getElementById(\'textcache\').innerHTML = \'Прибыль за все время:\';document.getElementById(\'textincome\').innerHTML = \''.$money_all['sum(Price)'].' р.\';" style="width:85px;" type="submit" value="За все время"></td>
				';
			?>	
            </tr>			

        </table>		
    </div>
    <div class="second_line fl-r">
        <table class="order_table">
            <tr>
                <th colspan="2">Заказы</th>
            </tr>

<?php 
if ($data_admin['Method']==0)
	{	
		echo'
		<tr>
			<td class="stattd"><a href="/bannerbro/admin/orders.php?set=1" class="menu-subbutton">На проверку:</a></td>
			<td class="tb_stat">'.mysql_num_rows($Count_New).'</td>
		</tr>
		<tr>
			<td class="stattd"><a href="/bannerbro/admin/orders.php?set=2" class="menu-subbutton">Ожидают оплаты:</a></td>
			<td class="tb_stat">'.mysql_num_rows($Count_Wait).'</td>
		</tr>';
	}
if ($data_admin['Method']==1)
	{	
		echo'
		<tr>
			<td class="stattd"><a href="/bannerbro/admin/orders.php?set=1" class="menu-subbutton">Ожидают оплаты:</a></td>
			<td class="tb_stat">'.mysql_num_rows($Count_Moderbefore_pay).'</td>
		</tr>
		<tr>
			<td class="stattd"><a href="/bannerbro/admin/orders.php?set=2" class="menu-subbutton">На проверку:</a></td>
			<td class="tb_stat">'.mysql_num_rows($Count_Pay_Moder).'</td>
		</tr>';
	}	
if ($data_admin['Method']==2)
	{	
		echo'
		<tr>
			<td class="stattd"><a href="/bannerbro/admin/orders.php?set=2" class="menu-subbutton">Ожидают оплаты:</a></td>
			<td class="tb_stat">'.mysql_num_rows($Count_Wait).'</td>
		</tr>';
	}
?>	
            <tr>
                <td class="stattd"><a href="/bannerbro/admin/orders.php?set=3" class="menu-subbutton">Действующие:</a></td>
                <td class="tb_stat"><?php echo mysql_num_rows($Count_Good); ?></td>
            </tr>
            <tr>
                <td class="stattd"><a href="/bannerbro/admin/reban.php" class="menu-subbutton">На замену:</a></td>
                <td class="tb_stat"><?php echo mysql_num_rows($Count_Re); ?></td>
            </tr>
            <tr>
                <td class="stattd"><a href="/bannerbro/admin/orders.php?set=4" class="menu-subbutton">Законченные:</a></td>
                <td class="tb_stat"><?php echo mysql_num_rows($Count_Bad); ?></td>
            </tr>
            <tr>
                <td class="stattd"><a href="/bannerbro/admin/queue.php" class="menu-subbutton">В очереди:</a></td>
                <td class="tb_stat"><?php echo mysql_num_rows($Count_Queue); ?></td>
            </tr>			
        </table>

    </div>
</fieldset>

    <span class="blue_link_btn" onclick="$('#history').slideToggle('fast');" style="margin-left:5px; margin-bottom:10px; border-bottom:1px dashed #017AA0; cursor:pointer;">История денежных средств</span>
<?php
if (!isset($_GET['n'])){ echo '<div style="display:none" id="history" >'; } else { echo '<div id="history" >'; } 
?>
	<table class="order_table" style="text-align:center; margin-top:10px;">
	<tr>
		<th>№</th>
		<th style="text-align:center;">Дата создания</th>
		<th style="text-align:center;">Покупатель</th>
		<th style="text-align:center;">Заказ</th>
		<th style="text-align:center;">Прибыль</th>
		<th style="text-align:center;">Оплачено</th>
	</tr>
<script type="text/javascript">var jstableday=[];</script>
<?php
$i=0;
$ShowOrders=mysql_query ("SELECT * FROM Orders WHERE admin_order!='1' ORDER BY ID DESC", $link)  or die (mysql_error($link));
if (mysql_num_rows($ShowOrders)!=null) {$ShowOrdersNew=GoShowPribil($ShowOrders,$inav,0);}else{$ShowOrdersNew=$ShowOrders;}
	while($data = mysql_fetch_array($ShowOrdersNew))
		{
		if ($data['Whot']=='Shows'){$data['Whot']='Показы';}
		if ($data['Whot']=='Click'){$data['Whot']='Клики';}
		if ($data['Whot']=='Day'){$data['Whot']='Дни';}
		echo '<tr>';
		echo '<td style="text-align:center;"><span style="font-weight: bold;">'. $data['ID'] . '</td>';
		echo '<td style="text-align:center;"><span style="font-weight: bold;">'. coupdate($data['date']) .'</td>';
		echo '<td style="text-align:center;"><b>'. $data['User'] .'</b><br> ('.$data['Email'].')</td>';
		echo '<td style="text-align:center;">'. $data['Whot'].':';
		if ($data['Whot']=='Дни')
		{
			echo '<script type="text/javascript"> jstableday.push(\''.str($data['Do']).'\');</script>';
			echo '&nbsp;'.count(explode(",", $data['Do'])).'&nbsp;&nbsp;&nbsp;<a data-lightbox="on" href="#numday"><img onclick="showday('.$i.')" style="cursor:pointer;" src="../images/watch.png" width="12"></a></td>';
		} else {
			echo '<script type="text/javascript"> jstableday.push(\''.$data['Do'].'\');</script>';
			echo ' '.$data['Do']. '</td>'; 
		}
		
		echo '</td>';
		echo '<td style="text-align:center; color:green;">+ <b>'. $data['Price'] .' руб.</b></td>';
		if ($data['Pay']!='Ожидает одобрения' && $data['Pay']!='Модерация после оплаты' && $data['Pay']!='Ожидает оплаты'){  
		echo '<td style="text-align:center; color:green;"><b>Да</b></td>';
		} else {
		echo '<td style="text-align:center; color:red;"><b>Нет</b></td>';
		}
		echo '</tr>';
		$i=$i+1;
		}
echo '
	</table>';	
	GoShowPribil($ShowOrders,$inav,1);
?>
</div>

</div>
</div>
<!-- Заказанные дни -->
<div style="display: none;">
    <div class="popup" id="numday">
        <div class="popup_header ta-l">
            Заказанные дни
            <div class="clear"></div>
        </div>
        <div class="popup_container">
			<div id="showday">
			
			</div>
        </div>
    </div>
</div>


<?php mysql_close($link); include 'footer.php'; ?>