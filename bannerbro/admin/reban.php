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
$ShowReBan=mysql_query("SELECT * FROM Orders WHERE Pay='Оплачено' and new_ban!='' and new_url!='' ORDER BY ID DESC", $link)  or die (mysql_error($link));
if (mysql_num_rows($ShowReBan)!=null) {$ShowOrdersNewReBan=GoShowNaviReban($ShowReBan,$inav,0,$so);}else{$ShowOrdersNewReBan=$ShowReBan;}

	if (isset($_GET['good']) && isset($_GET['mail'])) 
	{	
		$mailAdresat=$_GET['mail'];
		$good=$_GET['good']; 
		if ($good!=null) 
		{
		goodmod($good,$mailAdresat,'Ваш новый баннер принят','Здравствуйте. Ваш новый баннер принят<br><br>'); # Отправляем хорошее письмо
		}
	} # Отправляем good письмо

	if (isset($_GET['new']) && isset($_GET['text']) && isset($_GET['titletext'])) 
	{
		$new=$_GET['new'];  
		$mailZag=$_GET['titletext'];
		$mailText=$_GET['text'];
		$mailAdresat=$_GET['mail'];
		if ($new!=null)
		{
			badmod($new,$mailAdresat,$mailZag,$mailText);
		}  
	} # Отправляем bad письмо
?>

<?php include 'header.php'; ?>

<title>Заказы на замену</title>
</head>
<body>

<div class="wrapper">

	<?php include 'menu.php'; ?>
	
	<div class="content orders">
<?php

$i=0;
echo '
	<table class="order_table" style="text-align:center;">
	<tr>
	<th>№</th>
	<th>Покупатель</th>
	<th>Старый баннер</th>
	<th>Новый баннер</th>
	<th>Действия</th>
	</tr>';

print "<script type=\"text/javascript\">var jspic=[];var jsurl=[];var jspicnew=[];var jsurlnew=[];</script>";

while($data = mysql_fetch_array($ShowOrdersNewReBan))
	{
		$JSsize=explode("x", $data['Size']);
		
		echo '<tr>';
		
		echo '<td>'. $data['ID'] . '</td>';
		
		echo '<td>'. $data['User'] .'<br>'.$data['Email']. '</td>';

		if (getExtension($data['Picture'])=='swf')
			{
				echo '<td class="picswf"><a data-lightbox="on" href="#show_banner"><img src ="/bannerbro/images/swf.jpg" onclick="showbanner(2,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')" /></a><br>'.$JSsize[0].'x'.$JSsize[1].'<br>'.'Позиция '.$data['Position'].'</td>';
			}	else	{
				echo '<td class="pic"><a data-lightbox="on" href="#show_banner"><img style="width:30px; height:30px;" src="/bannerbro/img/'. $data['Picture'] .'" onclick="showbanner(1,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')" /></a><br>'.$JSsize[0].'x'.$JSsize[1].'<br>'.'Позиция '.$data['Position'].'</td>';
			}

		if (getExtension($data['new_ban'])=='swf')
			{ 
				echo '<td class="picswf"><a data-lightbox="on" href="#show_banner"><img src ="/bannerbro/images/swf.jpg" onclick="showbanner(4,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')" /></a><br>'.$JSsize[0].'x'.$JSsize[1].'<br>'.'Позиция '.$data['Position'].'</td>';
			}	else	{
				echo '<td class="pic"><a data-lightbox="on" href="#show_banner"><img style="width:30px; height:30px;" src="/bannerbro/img/'. $data['new_ban'] .'" onclick="showbanner(3,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')"/></a><br>'.$JSsize[0].'x'.$JSsize[1].'<br>'.'Позиция '.$data['Position'].'</td>';
			}

		echo '<td><a href=\'reban.php?good='.$data['ID'].'&mail='.$data['Email'].'\'><input class="greens_btn" style="width:110px;" type="submit" name="dobro" value="Одобрить"/></a><br><a data-lightbox="on" class="reds_link_btn inl-bl" onclick="otkaz(2,'.$data['ID'].',\''.$data['Email'].'\',1)" href="#refuse_order">Отклонить</a></td>';
		
		echo '</tr>';
		echo '<script type="text/javascript">';
		echo 'jspic.push(\''.$data['Picture'].'\');';
		echo 'jsurl.push(\''.$data['Url'].'\');';
		echo 'jspicnew.push(\''.$data['new_ban'].'\');';
		echo 'jsurlnew.push(\''.$data['new_url'].'\');';
		echo '</script>';
		$i=$i+1;
	}

echo '</table>';
GoShowNaviReban($ShowReBan,$inav,1,$so);

mysql_close($link);
?>
	</div>
	
</div>	

<!-- Показать баннер -->

<div style="display: none;">
    <div class="popup" id="show_banner">
        <div class="popup_container" style="padding: 0;">
			<div id="showbanner">
				
			</div>
        </div>
    </div>
</div>

<!-- Отклонить предложение -->

<div style="display: none;">
    <div class="popup" id="refuse_order">
        <div class="popup_header ta-l">
            Укажите причину отказа
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form method="post">
                <input id="theme" style="width: 50%;" name="theme" value="Ваш новый баннер не принят" onkeyup="otkaztext(2)" placeholder="Заголовок письма" required/><br>
                <textarea id="reason" name="reason" rows="8" cols="55" onkeyup="otkaztext(2)" placeholder="Текст письма" required>Здравствуйте. Ваш <strong>новый</strong> баннер не соответствует нашим требованиям.</textarea>
                <input type="hidden" id="badmail" name="badmail" value=""/>
                <input type="hidden" id="badpos" name="badmpos" value=""/>
					<div id="button_no" style="text-align:center;">
						<input type="submit" value="Отправить письмо" />
					</div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>