<?php include 'up.php'; 
$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[5]!="1"){ header("Location:no.php");} 
$file = 'users.txt';
if (isset($_POST['loaduser'])) { $loaduser=$_POST['loaduser']; if ($loaduser!=null){loaduser($file);} }

function loaduser($file)
	{
		if (file_exists($file)) 
			{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				readfile($file);
				exit;
			}
	}
?>
<?php include 'header.php'; ?>
<title>Клиенты</title>
</head>
<body>

<div class="wrapper">

	<?php include 'menu.php'; ?>
		<form method="POST">
		<input type="submit" style="margin: 20px 10px 0 0;" name="loaduser" class="blue_link_btn inl-bl fl-r" value="Собрать базу клиентов" />
		</form>
<div class="clear"></div>		
	<div class="content orders">
<div class="clear"></div>
		
<?php
print "<script type=\"text/javascript\">var jsarpok=[];</script>";

connectToDB();
define('t',"0");
$Showusers=mysql_query ("SELECT COUNT(*) AS t,Email,User,sum(Price)  FROM Orders WHERE admin_order!='1' and (Pay='Оплачено' or Pay='End' or Pay='Hide') GROUP BY Email ORDER BY t DESC", $link)  or die (mysql_error($link));

$current = file_get_contents($file);
$current = "";
file_put_contents($file, $current);

	echo '
		<table class="order_table">
		<tr>
			<th>Имя</th>
			<th>Email</th>
			<th>Заказов</th>
			<th>Общая сумма</th>
		</tr>';

while($data = mysql_fetch_array($Showusers))
	{
		echo '<tr>';
		echo '<td>'. $data['User'] . '</td>';
		echo '<td>'.$data['Email'].'</td>';
		echo '<td>'.$data[t].'</td>';
		echo '<td>'.$data['sum(Price)'].'</td>';
		echo '</tr>';
		$current .= $data['Email']."\r\n";
		file_put_contents($file, $current);
		echo '<script type="text/javascript">jsarpok.push(\''.$data['Email'].'\')</script>';
	}
echo '</table>';
mysql_close($link);
?>
	</div>
</div>
	
<?php include 'footer.php'; ?>