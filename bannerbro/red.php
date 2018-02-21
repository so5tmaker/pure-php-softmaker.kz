<?php
$StopBot = 
"(Yandex|Googlebot|googlebot|StackRambler|Slurp|MSNBot|Teoma|Scooter|ia_archiver
|Lycos|Mail.Ru|Aport|WebAlta|Googlebot-Mobile|Googlebot-Image|Mediapartners-Google
|Adsbot-Google|MSNBot-NewsBlogs|MSNBot-Products|MSNBot-Media|Yahoo Slurp|msnbot|Parser|bot|Robot)";
if (preg_match($StopBot,$_SERVER['HTTP_USER_AGENT']))
	{ 	
		echo '<html>
		<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex,nofollow" />
		</head>
		<body>
		</body>
		</html>';
		exit;
	}
if (isset($_GET['id'])){
if ($_GET['id']!=''){
require "admin/config.php";
require "admin/validate.php";
$ClickId=$_GET['id'];
Vint($ClickId);
$Url=$_GET['url'];
Vurl($Url);
connectToDB();
$CountClick=mysql_query("SELECT Clicks,Position,ID,Pay FROM Orders WHERE ID='$ClickId' and (Pay='Оплачено' or Pay='End' or Pay='Hide')", $link) or die (mysql_error($link));
$data = mysql_fetch_array($CountClick);

$click_ip = $_SERVER['REMOTE_ADDR']; # ip нажатика :)
if ($click_ip==null){$click_ip='mobile';} 
$date = date("Y-m-d"); # дата сегодняшняя
$Position=$data['Position']; # позиция

$Bilili=mysql_query("SELECT * FROM iper WHERE time_click='$date'", $link) or die (mysql_error($link));
if (mysql_num_rows($Bilili)!=null)
	{
	$SearchIp=mysql_query("SELECT * FROM iper WHERE pos='$ClickId' and ip='$click_ip' and time_click='$date'", $link) or die (mysql_error($link));
	if (mysql_num_rows($SearchIp)==null) 
		{
			$CCS=$data['Clicks']; # текущие клики
			$CCS=$CCS+1; 
			mysql_query("UPDATE Orders SET Clicks='$CCS' WHERE ID='$ClickId' and Position='$Position'", $link)  or die (mysql_error($link));
			
			$MaxId = mysql_query("SELECT max(Id) FROM iper", $link)  or die (mysql_error($link));
			$MaxId = mysql_result($MaxId,0);
			$MaxId =$MaxId + 1;
			
			$sql = "INSERT INTO iper(Id,time_click,ip,pos) VALUES ('$MaxId','$date','$click_ip','$ClickId')";
			mysql_query($sql);
		}
	} else {
		$CCS=$data['Clicks']; # текущие клики
		$CCS=$CCS+1; 
		mysql_query("UPDATE Orders SET Clicks='$CCS' WHERE ID='$ClickId' and Position='$Position'", $link)  or die (mysql_error($link));		
		
		mysql_query("DELETE FROM iper",$link);
		
		$MaxId = mysql_query("SELECT max(Id) FROM iper", $link)  or die (mysql_error($link));
		$MaxId = mysql_result($MaxId,0);
		$MaxId =$MaxId + 1;

		$sql = "INSERT INTO iper(Id,time_click,ip,pos) VALUES ('$MaxId','$date','$click_ip','$ClickId')";
		mysql_query($sql);
	}
mysql_close($link);
header('Location:'.$Url);
}
}	
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />
</head>
<body>
</body>
</html>
