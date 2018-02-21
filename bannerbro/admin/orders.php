<?php 
include 'up.php'; 
require_once "function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[1]!="1"){ header("Location:no.php");}
 
require_once "try_function.php";

if (isset($_POST['EditOrder'])){EditOrder($_GET['set']);}

connectToDB();

		$ShowSetting=mysql_query("SELECT Method,Show_Orders FROM Admin", $link) or die (mysql_error($link));
		$data_menu = mysql_fetch_array($ShowSetting);
		$so=$data_menu['Show_Orders'];
if (isset($_GET['EndMail'])){ EndMail($_GET['EndMail'],1);}
if (isset($_GET['EndMailHide'])){ EndMail($_GET['EndMailHide'],0);}
if (isset($_GET['mail'])) { $mailAdresat=$_GET['mail']; }
if (isset($_GET['titletext'])) { $mailZag=$_GET['titletext']; }
if (isset($_GET['text'])) { $mailText=$_GET['text']; }
if (isset($_GET['good'])) 
{ 
	$good=$_GET['good'];  
	if ($good!=null) 
		{
			$ItsDay=mysql_query("SELECT Whot,Do,Position FROM Orders WHERE ID='$good'", $link) or die (mysql_error($link));
			$Its_data = mysql_fetch_array($ItsDay);
			if ($Its_data['Whot']=='Day') #Отправляем письмо если на эти дни ни кто другой еще не позарился
			{
				$StopPositiom=$Its_data['Position']; 
				$WhotDay = explode(",", $Its_data['Do']);

				$CheckDay=mysql_query("SELECT Whot,Do FROM Orders WHERE Whot='Day' AND Position='$StopPositiom' AND (Pay='Ожидает оплаты' OR Pay='Оплачено') ", $link) or die (mysql_error($link));
				while($Check_data = mysql_fetch_array($CheckDay))
				{
					$WhotCheck = explode(",", $Check_data['Do']);
		
					for ($m = 0; $m < count($WhotDay); $m++)
					{
						for ($i = 0; $i < count($WhotCheck); $i++)
						{
						if ($WhotCheck[$i]==$WhotDay[$m]) { $fuck='Yes'; }
						}
					}
				} if ($fuck=='Yes'){ header('Location:orders.php?set=stopday'); exit;} else {
				
					if ($data_menu['Method']==1) 
						{
							mail_inpay($_GET['s'],$good,$mailAdresat,$mailZagGood,$mailTextGood);
						}	
						
					if ($data_menu['Method']!=1) 
						{
							goodone($good,$mailAdresat,$mailZagGood,$mailTextGood);  
						}					
				}
			} else {
				if ($data_menu['Method']==1) 
					{
						mail_inpay($_GET['s'],$good,$mailAdresat,$mailZagGood,$mailTextGood);
					}							
					
				if ($data_menu['Method']!=1) 
					{
						goodone($good,$mailAdresat,$mailZagGood,$mailTextGood);  
					}	
			}
		}
}
if (isset($_GET['bad'])) {  $bad=$_GET['bad'];  if ($bad!=null){badone($bad,$mailAdresat,$mailZag,$mailText);}  } # Отправляем плохое письмо

$i=0;$m=0;

if (isset($_GET['set']))
{
	$Set=$_GET['set'];

	if (isset($_POST['ShureDel']))
		{
			$del=$_POST['ShureDel']; mysql_query("DELETE FROM Orders WHERE ID='$del'", $link)  or die (mysql_error($link)); 
			
		if ($data_menu['Method']==0)
			{
				header('Location:orders.php?set='.$Set); exit;
			}		
			
		if ($data_menu['Method']==1)
			{
				header('Location:orders.php?set='.$Set); exit;
			}			
		}
}	

	function GoSearch($Set)
		{
			$show='';
			$show .='<div class="search"><form method="GET">';
			$show .='<input type="text" name="s" placeholder="Email для поиска">';
			$show .='<input type="hidden" name="set" placeholder="Email для поиска" value="'.$Set.'">';
			$show .='</form></div>';
			echo $show;
		}
	
if (isset($_GET['s'])){$S_mail=$_GET['s'];}				
?>
<?php include 'header.php'; ?>

<title>Управление заказами</title>
</head>
<body>

<div class="wrapper">
	<?php include 'menu.php'; ?>
	

<?php
if (isset($_GET['n'])){ $inav=$_GET['n']-1; $inav=$inav*$so;} else { $inav=0; }
$nav=0;
if (isset($_GET['set'])) 
	{		
		$Set=$_GET['set'];
		
		if ($Set=='stopday')
			{
			echo '
				<div class="content orders"><div class="queue ta-c"><div class="red message">Невозможно одобрить данный заказ! Дни, которые вы хотите одобрить, уже заняты другим баннером.</div><br>
				<a href="orders.php?set=1">Назад</a></div></div>';
			}
		

					
		if ($Set=='1' || $Set=='2' || $Set=='3' || $Set=='4')
		{
			echo '<div class="clear"></div><div class="content orders"><div class="clear"></div><div class="BarOrders">';
			GoSearch($Set);
			
			
			if ($Set=='1' && $data_menu['Method']==0)
				{
					$pay_info='Ожидает одобрения';	
				}	
			if ($Set=='1' && $data_menu['Method']==1)
				{
					$pay_info='Модерация после оплаты';	
				}
			if ($Set=='2' && $data_menu['Method']==0  || $data_menu['Method']==2)
				{
					$pay_info='Ожидает оплаты';
				}
			if ($Set=='2' && $data_menu['Method']==1)
				{
					$pay_info='Оплачено-Модерация';	
				}
			if ($Set=='3')
				{
					$pay_info='Оплачено';
				}
			if ($Set=='4')
				{
					$pay_info='End';
				}
				
				
				
			if (isset($_GET['s'])) 
				{
					$ShowOrders=mysql_query ("SELECT * FROM Orders WHERE Pay='$pay_info' AND Email='$S_mail' ORDER BY ID DESC", $link)  or die (mysql_error($link));
					if (mysql_num_rows($ShowOrders)!=null) {$ShowOrdersNew=GoShowNavi($ShowOrders,$pay_info,$Set,$inav,0,$S_mail,$so);}else{$ShowOrdersNew=$ShowOrders;}
					echo '<div class="result">Найдено: '.mysql_num_rows($ShowOrders).'</div>';
				} else {	
					$ShowOrders=mysql_query ("SELECT * FROM Orders WHERE Pay='$pay_info' ORDER BY ID DESC", $link)  or die (mysql_error($link));
					if (mysql_num_rows($ShowOrders)!=null) {$ShowOrdersNew=GoShowNavi($ShowOrders,$pay_info,$Set,$inav,0,'no',$so);}else{$ShowOrdersNew=$ShowOrders;}
				}	
				
				
				
			echo '</div>';			

			print "<script type=\"text/javascript\">
			var jspic=[];
			var jsurl=[];
			var jstableday=[];
			var jsorderurl=[];
			var jsorderalt=[];
			var jsordername=[];
			var jsordermail=[];
			var jsorderid=[];
			</script>";
			
			echo '
					<table class="order_table" style="text-align:center;">
					<tr>
						<th>№</th>
						<th>Покупатель</th>
						<th>Заказ</th>
						<th style="width:30%">Ссылка</th>
						<th>Баннер</th>
						<th>Цена</th>
						<th>Действия</th>
					</tr>';
			
			while($data = mysql_fetch_array($ShowOrdersNew))
				{
				
					if ($data['Whot']=='Shows'){$data['Whot']='Показы:';}
					if ($data['Whot']=='Click'){$data['Whot']='Клики:';}
					if ($data['Whot']=='Day'){$data['Whot']='Дни:';}
					
					$JSsize=explode("x", $data['Size']);
					echo '<tr>';
					echo '<td width="60px" style="text-align:center;"><span style="font-weight: bold;">'. $data['ID'] . '</span><br>'. iddate($data['date']) .'</td>';
					GetInformationAutorMail();
					if ($data['Email']==$mail_admin){
					echo '<td><span style="color:green;">Вы</span></td>';
					}else{
					echo '<td>'. $data['User'] .'<br>'.$data['Email']. '</td>';
					}
					
					
					echo '<td>';
					echo $data['Whot'];
					if ($data['Whot']=='Дни:')
						{
							echo '<script type="text/javascript"> jstableday.push(\''.str($data['Do']).'\');</script>';
							echo '&nbsp;'.count(explode(",", $data['Do'])).'<br>&nbsp;&nbsp;&nbsp;<a data-lightbox="on" href="#numday"><img onclick="showday('.$i.')" style="cursor:pointer;" src="../images/watch.png" width="12"></a></td>';
						} else {
							echo '<script type="text/javascript"> jstableday.push(\''.$data['Do'].'\');</script>';
							echo ' '.$data['Do']. '</td>'; 
						}
						
					$dttp=$data['Position'];	
					$Showposition=mysql_query ("SELECT * FROM Setting WHERE ID='$dttp' ORDER BY ID", $link)  or die (mysql_error($link));
					$datadttp = mysql_fetch_array($Showposition);					
					if (strlen($datadttp['About'])>76){$dttpabout=mb_substr($datadttp['About'], 0, 76).'...'; } else {$dttpabout=$datadttp['About'];}
					if (strlen($data['Alt'])>76){$dataalt=mb_substr($data['Alt'], 0, 75).'...'; } else {$dataalt=$data['Alt'];}
					if (strlen($data['Url'])>45)
						{
							if ($data['Alt']!=''){
							echo '<td>'.$dataalt. '<br><span class="hidden-link" data-link="'.$data['Url'].'">'.substr($data['Url'], 0, 44).'...</span><span style="color:silver;"><br>('.$dttpabout.')</span></td>';
							} else {
							echo '<td><span class="hidden-link" data-link="'.$data['Url'].'">'.substr($data['Url'], 0, 44).'...</span><span style="color:silver;"><br>('.$dttpabout.')</span></td>';
							}
						} 
						else {
							if ($data['Alt']!=''){
							echo '<td>'.$dataalt. '<br><span class="hidden-link" data-link="'.$data['Url'].'">'.$data['Url'].'</span><span style="color:silver;"><br>('.$dttpabout.')</span></td>';
							} else {
							echo '<td><span class="hidden-link" data-link="'.$data['Url'].'">'.$data['Url'].'</span><span style="color:silver;"><br>('.$dttpabout.')</span></td>';
							}
						}

					if (getExtension($data['Picture'])=='swf')
						{ 
							echo '<td class="picswf" style="text-align:center;"><a data-lightbox="on" href="#show_banner"><img src ="/bannerbro/images/swf.jpg" onclick="showbanner(2,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')"/></a><br>'.$JSsize[0].'x'.$JSsize[1].'</td>';
						}else{
							echo '<td class="pic" style="text-align:center;"><a data-lightbox="on" href="#show_banner"><img style="width:30px; height:30px;" src="/bannerbro/img/'. $data['Picture'] .'" onclick="showbanner(1,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')" /></a><br>'.$JSsize[0].'x'.$JSsize[1].'</td>';
						}
						
					echo '<td>'. $data['Price'];
					if ($data['discount']=='1'){echo '<br><span style="color:green">(Купон)</span>';}
					echo '</td>';
					if ($Set=='1' && $data_menu['Method']==0)
						{
							echo '<td><a href=\'orders.php?good='.$data['ID'].'&mail='.$data['Email'].'\'><input class="green_btn" type="submit" name="dobro" value="Одобрить"/></a>&nbsp;&nbsp;<a data-lightbox="on" class="red_link_btn inl-bl" onclick="otkaz(1,'.$data['ID'].',\''.$data['Email'].'\',1)" href="#refuse_order">Отклонить</a></td>';
						}
						
					if ($Set=='1' && $data_menu['Method']==1) 
						{
							echo '<td><a href="/bannerbro/show.php?id='.$data['ID'].'&sec='.md5($data['ID']).'" target="_blank"><input class="blues_btn" type="submit" name="lock" value="Просмотр"/></a><br><a class="yellows_link_btn" onclick="ShowOrder('.$i.')" data-lightbox="on" href="#edit_order">Редактировать</a><br><a href=\'orders.php?good='.$data['ID'].'&s=1&mail='.$data['Email'].'\'><input style="margin-top:3px;" class="greens_btn" type="submit" name="dobro" value="Показывать сразу"/></a><br><a class="reds_link_btn" onclick="ShowDel(1,'.$data['ID'].')" data-lightbox="on" href="#remove_moder">Удалить</a></td>';
						}			  
						
					if ($Set=='2' or $Set=='4')
						{	
							echo '<td>';
							if ($Set=='4') { echo'<a href="/bannerbro/show.php?id='.$data['ID'].'&sec='.md5($data['ID']).'" target="_blank"><input class="blues_btn" type="submit" name="lock" value="Просмотр"/></a><br><a href="/bannerbro/admin/orders.php?EndMail='.$data['ID'].'"><input class="greens_btn" style="width:110px; margin-top:3px;" type="submit" name="lock" value="Уведомление"/></a><br><a class="reds_link_btn" href="/bannerbro/admin/orders.php?EndMailHide='.$data['ID'].'">Скрыть</a>'; } else {
							if ($data_menu['Method']==0)  																																	   
								{
									echo '<a href="/bannerbro/show.php?id='.$data['ID'].'&sec='.md5($data['ID']).'" target="_blank"><input class="blues_btn" type="submit" name="lock" value="Просмотр"/></a><br><a class="reds_link_btn" onclick="ShowDel(1,'.$data['ID'].')" data-lightbox="on" href="#remove_moder">Удалить</a></td>';
								}
							if ($data_menu['Method']==1 && $Set=='2') 
								{
									echo '<span style="font-size:10px; color:silver;margin-left:5px;">Уже оплачено</span><br><a class="yellows_link_btn" onclick="ShowOrder('.$i.')" data-lightbox="on" href="#edit_order">Редактировать</a><br><a href=\'orders.php?good='.$data['ID'].'&s=2&mail='.$data['Email'].'\'><input class="greens_btn" style="width:110px;margin-top:3px;" type="submit" name="dobro" value="Одобрить"/></a><br><a data-lightbox="on" class="reds_link_btn" onclick="otkaz(1,'.$data['ID'].',\''.$data['Email'].'\',1)" href="#refuse_order">Отклонить</a></td>';
								}
							if ($data_menu['Method']==2) 
								{
									echo '<a href="/bannerbro/show.php?id='.$data['ID'].'&sec='.md5($data['ID']).'" target="_blank"><input class="blues_btn" type="submit" name="lock" value="Просмотр"/></a><br><a class="reds_link_btn" onclick="ShowDel(1,'.$data['ID'].')" data-lightbox="on" href="#remove_moder">Удалить</a></td>';
								}
							}								
						} 
						
					if ($Set=='3')
						{
							echo '<td><a href="/bannerbro/show.php?id='.$data['ID'].'&sec='.md5($data['ID']).'" target="_blank"><input class="blues_btn" type="submit" name="lock" value="Просмотр"/></a><br><a class="yellows_link_btn" onclick="ShowOrder('.$i.')" data-lightbox="on" href="#edit_order">Редактировать</a><br><a class="reds_link_btn" onclick="ShowDel(1,'.$data['ID'].')" data-lightbox="on" href="#remove_moder">Удалить</a></td>';
						}                                                                                                                                                                            
						
					echo '</tr>';
					
					echo '
					<script type="text/javascript">
					jspic.push(\''.$data['Picture'].'\');
					jsurl.push(\''.$data['Url'].'\');
					jsorderid.push(\''.$data['ID'].'\');
					jsorderurl.push(\''.$data['Url'].'\');
					jsorderalt.push(\''.$data['Alt'].'\');
					jsordername.push(\''.$data['User'].'\');
					jsordermail.push(\''.$data['Email'].'\');
					</script>';
					
					$i=$i+1;
				}
			echo '</table>';
			if (isset($_GET['s'])){GoShowNavi($ShowOrders,$pay_info,$Set,$inav,1,$S_mail,$so);}else{GoShowNavi($ShowOrders,$pay_info,$Set,$inav,1,'no',$so);}
		}
		
	}
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
                <input id="theme" style="width: 50%;" name="theme" value="<?php echo $mailZag ?>" onkeyup="otkaztext(1)" placeholder="Заголовок письма" required/><br>
                <textarea id="reason" name="reason" rows="8" cols="55" onkeyup="otkaztext(1)" placeholder="Текст письма" required><?php echo $mailText;?></textarea>
                <input type="hidden" id="badmail" name="badmail" value=""/>
                <input type="hidden" id="badpos" name="badmpos" value=""/>
				<fieldset>
					<legend>Примеры отклонений заказа</legend>
						
					<div class="list_noway">
						<ul>
							<li onclick="document.getElementById('reason').innerHTML='Здравствуйте! Мы вынуждены отказать вам в размещении Вашего баннера у нас на сайте, так как в вашей рекламе замечен оскорбительный материал.';otkaztext(1)">> Реклама оскорбительного материала</li>
							<li onclick="document.getElementById('reason').innerHTML='Здравствуйте! Мы вынуждены отказать вам в размещении Вашего баннера у нас на сайте, так как мы заметили в вашей рекламе запрещенные услуги и товаровы.';otkaztext(1)">> Реклама запрещенных услуг и товаров</li>
							<li onclick="document.getElementById('reason').innerHTML='Здравствуйте! Мы вынуждены отказать вам в размещении Вашего баннера у нас на сайте, <p>Пожалуйста, сделайте более качественный и профессиональный баннер и оформите заказ с самого начала.';otkaztext(1)">> Некачественный баннер</li>
							<li onclick="document.getElementById('reason').innerHTML='Здравствуйте! Мы вынуждены отказать вам в размещении Вашего баннера у нас на сайте, так как ссылка ведет на вредоностный сайт.';otkaztext(1)">> Ссылка не вредоностный сайт</li>
						</ul>	
					
					</div>
				</fieldset>
				
					<div id="button_no" style="text-align:center; margin-top:10px;">
						<input type="submit" value="Отправить письмо" />
					</div>
            </form>
        </div>
    </div>
</div>


<!-- Удалить предложение -->

<div style="display: none;">
    <div class="popup" id="remove_moder">
        <div class="popup_header ta-l">
            Удаление заказа
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <h3>Вы уверены что хотите удалить заказ № <label id="ShurePos"></label>?</h3>
            <form method="post">
                <input type="hidden" name="ShureDel" id="ShureDel"/>
                <div class="submit_form">
                    <input type="submit" name="RemoveOrder" value="Да, я хочу удалить"/>
                </div>
            </form>
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

<!-- Редактировать заказ -->

<div style="display: none;">
    <div class="popup" id="edit_order">
        <div class="popup_header ta-l">
            Редактировать заказ № <label id="Orderpos"></label>
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form enctype="multipart/form-data" method="post">
                <fieldset>
                    <legend>Баннер</legend>
                    <table class="order_table">
						<tr>
							<td>Баннер рекламодателя</td>
							<td><input type="radio" id="ordbanner_1" name="ordbanner" onclick="edit_order_banner_auto()" value="0" checked></td>
						</tr>				
						<tr>	
							<td>Новый баннер</td>
							<td><input type="radio" id="ordbanner_2" name="ordbanner" onclick="edit_order_banner()" value="1" <?php if ($Scrin_info[0]==1) echo 'checked'; ?>></td>
						</tr>					
                        <tr>
                            <td><input type="file" name="OrderNewBanner" id="OrderNewBanner" required disabled></td>
                        </tr>
                        <tr>
							<td><input style="width:300px;" type="url" value="" name="order_url" id="order_url" placeholder="Ссылка" required></td>
                        </tr>
						<tr>
							<td><input style="width:300px;" type="text" name="order_alt" id="order_alt" value=""  placeholder="Альт текст" required/></td>
                        </tr>
					</table>	
				</fieldset>	
				<fieldset>
					<legend>Рекламодатель</legend>
					<table class="order_table">
                        <tr>
							<td><input style="width:300px;" type="text" name="order_name" id="order_name" value=""  placeholder="Имя" required/></td>
                        </tr>
                        <tr>
							<td><input style="width:300px;" type="email" name="order_mail" id="order_mail" value=""  placeholder="E-mail" required/></td>
                        </tr>					
				    </table>
                </fieldset>		
                <div class="submit_form">
					<input type="text" name="OrderID" style="display:none;" id="OrderID"/>
                    <input type="submit" name="EditOrder" value="Применить изменения"/>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

$('.hidden-link').click(function(){window.open($(this).data('link'));return false;});

</script>

<?php include 'footer.php'; ?>



