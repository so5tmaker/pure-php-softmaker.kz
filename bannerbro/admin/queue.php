<?php 
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[1]!="1"){ header("Location:no.php");} 

connectToDB();
		$ShowSetting=mysql_query("SELECT Method,Show_Orders FROM Admin", $link) or die (mysql_error($link));
		$data_menu = mysql_fetch_array($ShowSetting);
		$so=$data_menu['Show_Orders'];
		
if (isset($_GET['n'])){ $inav=$_GET['n']-1; $inav=$inav*$so;} else { $inav=0; }
if (isset($_GET['gomail'])) {QueueMail($_GET['gomail'],$_GET['id']);} 
$i=0;
if (isset($_GET['delete'])) {QueueDel($_GET['delete']);} 
?>
<?php include 'header.php'; ?>

<title>Очередь клиентов</title>
</head>
<body>

<div class="wrapper">

	<?php include 'menu.php'; ?>
	
	<div class="content orders">
<?php
$TableQueue=mysql_query ("SELECT * FROM Queue ORDER BY ID", $link)  or die (mysql_error($link));
if (mysql_num_rows($TableQueue)!=null) {$TableQueueNew=GoShowNaviQueue($TableQueue,$inav,0,$so);}else{$TableQueueNew=$TableQueue;}

echo '
	<table class="order_table" style="text-align:center;">
	<tr>
	<th>№</th>
	<th>Клиент</th>
	<th>Желаемая позиция</th>
	<th>Свободные места</th>
	<th>Действия</th>
	</tr>';

while($data = mysql_fetch_array($TableQueueNew))
	{
		$checkplace=false;
		
		echo '<tr>';
		
		echo '<td>'.$data['ID'].'</td>';
		
		echo '<td>'.$data['Name'].'<br>'.$data['Email'].'</td>';
		
		echo '<td>'.$data['Position'].'</td>';
		
		$TableFreeQueuePos=$data['Position'];
		$TableFreeQueue=mysql_query ("SELECT ID FROM Orders WHERE Position='$TableFreeQueuePos' and Pay='Оплачено'", $link)  or die (mysql_error($link));
		$TablePlaceQueue=mysql_query ("SELECT Place FROM Setting WHERE Positions='$TableFreeQueuePos'", $link)  or die (mysql_error($link));
		$SettingData = mysql_fetch_array($TablePlaceQueue);

		if ($SettingData['Place']!=0)
			{
			
				$razn=$SettingData['Place']-mysql_num_rows($TableFreeQueue);
				if ($razn<=0){ $razn='<span style="color:red">Мест нет</span>'; $checkplace=true;}
				echo '<td>'.$razn.'</td>';
				
				if ($checkplace==false)
					{
					
						echo '<td><a href="queue.php?gomail='.$data['Email'].'&id='.$data['ID'].'"><input class="greens_btn" style="width:110px;" type="submit" value="Сообщить"/></a><br><a class="reds_link_btn" href="/bannerbro/admin/queue.php?delete='.$data['ID'].'">Удалить</a></td>';
						
					}	else	{ echo '<td><input class="disabled" type="submit" value="Сообщить" disabled/></td>'; }
					
			}	else	{
			
				echo '<td><span style="color:green">Неограниченно</span></td>';
				
				echo '<td><a href="queue.php?gomail='.$data['Email'].'&id='.$data['ID'].'"><input class="greens_btn" style="width:110px;" type="submit" value="Сообщить"/></a><br><a class="reds_link_btn" href="/bannerbro/admin/queue.php?delete='.$data['ID'].'">Удалить</a></td>';
				
			}

		echo '</tr>';
	}
	
echo '</table>';
GoShowNaviQueue($TableQueue,$inav,1,$so);
mysql_close($link);
?>
	</div>
	</div>
<?php include 'footer.php'; ?>