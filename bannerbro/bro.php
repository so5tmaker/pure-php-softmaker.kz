<?php
require 'admin/validate.php';
function queue() 
	{
		global $link;
		if (isset($_POST['MyQueuePosition']) && isset($_POST['queue_email']) && isset($_POST['queue_name'])) 
		{

			$QueueEmail=$_POST['queue_email']; $QueuePosition=$_POST['MyQueuePosition']; $QueueName=$_POST['queue_name'];
			Vmail($QueueEmail); Vname($QueueName);	Vint($QueuePosition);
			$CheckMailPlace=mysql_query("SELECT * FROM Queue WHERE Email='$QueueEmail' and Position='$QueuePosition'", $link) or die (mysql_error($link));
			
			if (mysql_num_rows($CheckMailPlace)==null)
			{
				$MaxId = mysql_query("SELECT Max(ID) FROM Queue", $link)  or die (mysql_error($link));
				$MaxId = mysql_result($MaxId,0);
				$MaxId =$MaxId + 1;
				$sql = "INSERT INTO Queue(ID,Position,Email,Name) VALUES ('$MaxId','$QueuePosition','$QueueEmail','$QueueName')";
				mysql_query($sql);
				header('Location:index.php?Queue=1');
			}   
			else 
			{ 
				header('Location:index.php?Queue=2'); 
			}
		}
	}
function queue_check() 
	{
		if (isset($_GET['Queue'])){
			if ($_GET['Queue']==1) 
			{
				echo '<div class="queue ta-c">
				<img src="images/queue.png" alt=""/><br>
				<div class="green message">Вы встали в очередь.</div><br>
				<a href="index.php">Назад</a></div>';
				exit;
			}
			if ($_GET['Queue']==2) 
			{
				echo '<div class="queue ta-c">
				<img src="images/queue.png" alt=""/><br>
				<div class="yellow message">Вы уже стоите в очереди.</div><br>
				<a href="index.php">Назад</a>';
				exit;
			}
		}
	}

function left_cont()
	{
		global $link;
		echo '<div class="action_container">';
		echo '    <div class="positions fl-l">';
		echo '<input style="display:none;" id="discount_1" name="discount_1" value=""/>';
		echo '<input style="display:none;" id="discount_2" name="discount_2" value=""/>';
		$Countrow=mysql_query("SELECT Active,Positions,Price,Paywhot,About,Size,discount_1,discount_2,Place FROM Setting ORDER BY ID", $link) or die (mysql_error($link));
		echo '<table class="info_table">';
		echo '	<tr>';
		echo '		<th style="width:10px;">№</th>';
		echo '		<th>Размещение</th>';
		echo '		<th>Размер</th>';
		echo '		<th>Оплата</th>';
		echo '		<th style="width:10px;"><u class="tooltip"><u>Цена</u><span>Цена указана в рублях.<br>Вы платите за 1 день, 1 показ или 1 клик.</span></u></th>';
		echo '		<th style="width:10px;"><u class="tooltip"><u>Количество</u><span>Количество баннеров одновременно в ротации.</span></u></th>';
		echo '		<th style="text-align:center;">Подробнее</th>';
		echo '	</tr>';
		while($data = mysql_fetch_array($Countrow))
		{
			if ($data['Active']==1){$dis='class="disabled" disabled';}else{$dis='';}
			$checkplace=false;
			echo '<tr>';
			echo '<td style="width:10px;">'.$data['Positions'].'</td>';
			echo '<td style="width:40%; text-align:left;">'. $data['About'];
			$discount_position_info='';
			if ($data['discount_1']!=null && $data['discount_2']!=null) {
			if ($data['Paywhot']=='Дни'){ $discount_position_info='document.getElementById(\'day_discount_info\').innerHTML = "При заказе свыше '.$data['discount_1'].' руб. скидка составит '.$data['discount_2'].'%";'; }
			if ($data['Paywhot']=='Клики'){ $discount_position_info='document.getElementById(\'click_discount_info\').innerHTML = "При заказе свыше '.$data['discount_1'].' руб. скидка составит '.$data['discount_2'].'%";'; }
			if ($data['Paywhot']=='Показы'){ $discount_position_info='document.getElementById(\'show_discount_info\').innerHTML = "При заказе свыше '.$data['discount_1'].' руб. скидка составит '.$data['discount_2'].'%";'; }
			echo '  <span class="discount_info">Скидка!</span>';
			}
			echo '</td>';
			echo '<td>'. $data['Size'] . '</td>';
			echo '<td>'. $data['Paywhot'] . '</td>';
			echo '<td>'. $data['Price'] . '</td>';
			echo '<td style="text-align:center; width:10px">';
			$PlacePos=$data['Positions'];
			Vint($PlacePos);
			if ($data['Place']!=0)
			{
				$PlaceOrders=mysql_query("SELECT ID FROM Orders WHERE Pay='Оплачено' and Position='$PlacePos'", $link) or die (mysql_error($link));
				$razn=$data['Place']-mysql_num_rows($PlaceOrders);
				if (mysql_num_rows($PlaceOrders)>=$data['Place']) { echo '<span style="color:red">Мест нет</span></td>'; $checkplace=true;} else { echo $razn.'</td>'; }
			} 
			else 
			{
				if ($data['Paywhot']=="Дни") { echo '1</td>'; } else {
					echo '<span style="font-size:16px;">&infin;</span></td>';
				}
				 
			}
			if ($checkplace==false)
			{
				if ($data['Paywhot']=='Дни'){echo '<td><a href="#" onclick="changeyourday('.$data['Positions'].')"><input type="submit" '.$dis.' name="edit" value="Купить баннер" onclick="Pos'.$data['Positions'].'();NextM('.$data['Positions'].');" ></a><br></td>';}
				if ($data['Paywhot']=='Показы'){echo '<td><a href="#" onclick="changeyourshows('.$data['Positions'].')"><input type="submit" '.$dis.' name="edit" value="Купить баннер" onclick="Pos'.$data['Positions'].'()"></a><br></td>';}
				if ($data['Paywhot']=='Клики'){echo '<td><a href="#" onclick="changeyourclick('.$data['Positions'].')"><input type="submit" '.$dis.' name="edit" value="Купить баннер" onclick="Pos'.$data['Positions'].'()"></a><br></td>';}
			}
				else 
			{
			echo '<td><a href="#" onclick="go_queue('.$data['Positions'].')"><input type="submit" name="edit" value="В очередь" onclick="Pos'.$data['Positions'].'()"></a><br></td>';
			}
			echo '</tr>';
			$i=$data['Positions']; Vint($i);
			$Pos[$data['Positions']]=mysql_query ("SELECT Do FROM Orders WHERE Position='$i' AND Whot='Day' AND (Pay ='Оплачено-Модерация' OR Pay ='Оплачено' OR Pay ='Ожидает оплаты')", $link)  or die (mysql_error($link));
			$num_rows = mysql_num_rows($Pos[$data['Positions']]);
			if ($num_rows >0)
			{
				while($row = mysql_fetch_array($Pos[$data['Positions']])){
				if (!isset($varpos[$data['Positions']])) { $varpos[$data['Positions']]=''; }
				$varpos[$data['Positions']]=$varpos[$data['Positions']].$row['Do'].',';}
				$varpos[$data['Positions']] = substr($varpos[$data['Positions']],  0, -1);
			} 
			print '<script>';
			print 'function Pos'.$data['Positions'].'() {';
			print 'document.getElementById(\'count\').value="0";';
			print 'document.getElementById(\'priceclick\').value="0";';
			print 'document.getElementById(\'priceshows\').value="0";'; 
			print 'document.getElementById(\'Myshows\').value="";'; 
			print 'document.getElementById(\'Myclick\').value="";';
			print 'document.getElementById(\'nameday\').value="";';  
			print 'document.getElementById(\'nameclick\').value="";';  
			print 'document.getElementById(\'nameshows\').value="";'; 
			print 'skidhide(\'skidday\');';
			print 'skidhide(\'skidclick\');';
			print 'skidhide(\'skidshow\');'; 
			print 'document.getElementById(\'discount_1\').value="'.$data['discount_1'].'";';  
			print 'document.getElementById(\'discount_2\').value="'.$data['discount_2'].'";';  
			print 'document.getElementById(\'dayHowcount\').innerHTML = "'.$data['Price'].'";';
			print 'document.getElementById(\'clickHowcount\').innerHTML = "'.$data['Price'].'";';
			print 'document.getElementById(\'ShowsHowcount\').innerHTML = "'.$data['Price'].'";';
			print 'document.getElementById(\'MyClickPosition\').value = "'.$data['Positions'].'";';
			print 'document.getElementById(\'MyShowsPosition\').value = "'.$data['Positions'].'";';
			print 'document.getElementById(\'MyDayPosition\').value = "'.$data['Positions'].'";';
			print 'document.getElementById(\'MyQueuePosition\').value = "'.$data['Positions'].'";';
			print 'document.getElementById(\'day_discount_info\').innerHTML = "";';
			print 'document.getElementById(\'click_discount_info\').innerHTML = "";';
			print 'document.getElementById(\'show_discount_info\').innerHTML = "";';
			print $discount_position_info;
			if (isset($varpos[$data['Positions']])) {print 'var a =\''.$varpos[$data['Positions']].'\';'; }
			print 'arr = a.split(\',\');';
			print '$("#calendar").multiDatesPicker(\'resetDates\',\'disabled\');';
			print '$(\'#calendar\').multiDatesPicker({firstDay: 1 ,minDate: 1,maxDate: 90,addDisabledDates:arr});';
			print '$("#calendar").multiDatesPicker(\'resetDates\');}';
			print '</script>';
		}
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
	}

function step2()
	{
		global $link;
		
		if (isset($_POST['MyShows'])){$MyShows=$_POST['MyShows']; if ($MyShows!=null){$MyСhoiсe=$MyShows;$StringСhoiсe='Показов'; Vint($MyСhoiсe);}		}
		if (isset($_POST['Myclick'])){$MyClick=$_POST['Myclick']; if ($MyClick!=null){$MyСhoiсe=$MyClick;$StringСhoiсe='Кликов'; Vint($MyСhoiсe);}		}
		if (isset($_POST['altField'])){$MyDay=$_POST['altField']; if ($MyDay!=null){$MyСhoiсe=$MyDay;$StringСhoiсe='Дни'; Vdate($MyСhoiсe);}		}

		if (isset($_POST['priceshows'])){$priceshows=round($_POST['priceshows'],1); if ($priceshows!=null){$Prise=$priceshows;}		}
		if (isset($_POST['MyCount'])){$priceday=round($_POST['MyCount'],1); if ($priceday!=null){$Prise=$priceday;}		}
		if (isset($_POST['priceclick'])){$priceclick=round($_POST['priceclick'],1); if ($priceclick!=null){$Prise=$priceclick;}		}

		if (isset($_POST['emailshows'])) { $emailshows=$_POST['emailshows']; if ($emailshows!=null){$Email=$emailshows;}	}
		if (isset($_POST['emailday'])) {$emailday=$_POST['emailday'];  if ($emailday!=null){$Email=$emailday;}	}
		if (isset($_POST['emailclick'])) { $emailclick=$_POST['emailclick']; if ($emailclick!=null){$Email=$emailclick;}	}

		if (isset($_POST['nameday'])) { $nameday=$_POST['nameday']; if ($nameday!=null){$Name=$nameday;}	}
		if (isset($_POST['nameclick'])) { $nameclick=$_POST['nameclick']; if ($nameclick!=null){$Name=$nameclick;}		}
		if (isset($_POST['nameshows'])) { $nameshows=$_POST['nameshows']; if ($nameshows!=null){$Name=$nameshows;}		}

		if (isset($_POST['MyShowsPosition'])) { $MyShowsPosition=$_POST['MyShowsPosition']; if ($MyShowsPosition!=null){$Pos=$MyShowsPosition;} }
		if (isset($_POST['MyClickPosition'])) { $MyClickPosition=$_POST['MyClickPosition']; if ($MyClickPosition!=null){$Pos=$MyClickPosition;} }
		if (isset($_POST['MyDayPosition'])) { $MyDayPosition=$_POST['MyDayPosition']; if ($MyDayPosition!=null){$Pos=$MyDayPosition;} }
		Vint($Pos);Vname($Name);Vmail($Email);Vprice($Prise);
		if (isset($_POST['MyDayPosition']) && isset($_POST['MyShowsPosition']) && isset($_POST['MyClickPosition']))
		{ 
			print '<script>document.getElementById("bbody").innerHTML=\'Заказ не сделан\'</script>';
		}
		else
		{
			echo '<div class="steps_container">';
			echo '<table>';
			echo '<tr>';
			echo '<td >Шаг 1</td>';
			echo '<td class="active border">Шаг 2</td>';
			echo '<td>Шаг 3</td>';
			echo '</tr>';
			echo '</table>';
			echo '<div class="clear"></div>';
			echo '</div>';
			
			
			$DatePosition=mysql_query("SELECT Positions,Price,Paywhot,About,Size FROM Setting WHERE Positions ='$Pos'", $link) or die (mysql_error($link));
			$data = mysql_fetch_array($DatePosition);
			$About=$data['About'];
			$Size=$data['Size'];
			$Adm=mysql_query("SELECT fatban FROM Admin", $link) or die (mysql_error($link));
			$Adata = mysql_fetch_array($Adm);

			echo '<div class="action_container">';
			echo '<div class="check_info fl-l">';
			echo '<table class="order_table">';
			echo '<tr>';
			echo '<th colspan="2">Пожалуйста проверьте данные</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Позиция</td>';
			echo '<td>'.$Pos.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Где будет показан баннер</td>';
			echo '<td>'.$About.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Размер</td>';
			echo '<td>'.$Size.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>'.$StringСhoiсe.'</td>';
			echo '<td>';
			if ($StringСhoiсe=='Дни')
				{
					echo '<div class="tooltip"><img style="cursor:pointer;" src="images/watch.png" width="20"><span>'.str($MyСhoiсe).'</span></div>';
				} 
				else 
				{
					echo $MyСhoiсe; 
				}
			echo '</td>';	
			echo '</tr>';
			echo '<tr>';
			echo '<td>Ваше имя</td>';
			echo '<td>'.$Name.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Ваш Email</td>';
			echo '<td>'.$Email.'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Сумма к оплате</td>';
			echo '<td>'.$Prise.'</td>';
			echo '</tr>';
			echo '</table>'; 
			echo '</div>';
			echo '<div class="banner_upload fl-r">';
			echo '<form action="steplocation.php" method="POST" ENCTYPE="multipart/form-data">';
			echo '<fieldset>';
			echo '<legend>Введите данные</legend>';
			echo '<table class="order_table">';
			echo '<tr>';
			echo '<th colspan="2">Загрузка баннера</th>';
			echo '</tr>';
			echo '<tr>';
			echo '<td>Ссылка</td>';
			echo '<td><input type="url" id="step3url" name="step3url" value="" required></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="tooltip">Альт текст(?)<span>Альтернативный текст для баннера</span></td>';
			echo '<td><input type="text" id="step3alt" name="step3alt" value=""></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td><img src = "captcha.php" /></td>';

			echo '<td><input type = "text" name = "prosto" /></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2"><input type="file" name="MyBanner" required><input type="submit" name="upload" value="Загрузить"></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2" style="font-size: 12px;">Формат: JPG,PNG,GIF или SWF. Размер: не больше '.$Adata['fatban'].' кб.</td>';
			echo '</tr>';
			echo '</table>';
			 
			echo '<div style="display:none;">';
			echo '<input type="text" id="step3pos" name="step3pos" value="'.$Pos.'"><br>';
			echo '<input type="text" id="step3name" name="step3name" value="'.$Name.'"><br>';
			echo '<input type="text" id="step3email" name="step3email" value="'.$Email.'"><br>';
			echo '<input type="text" id="step3prise" name="step3prise" value="'.$Prise.'"><br>';
			echo '<input type="text" id="step3choice" name="step3choice" value="'.$MyСhoiсe.'"><br>';
			echo '<input type="text" id="step3string" name="step3string" value="'.$StringСhoiсe.'"><br>';
			echo '<input type="text" id="step3about" name="step3about" value="'.$About.'"><br>';
			echo '<input type="text" id="step3size" name="step3size" value="'.$Size.'"><br>';
			echo '<input type="text" id="step3fatban" name="step3fatban" value="'.$Adata['fatban'].'"><br>';
			echo '</div>';
			echo '</form>';
			echo '</div>';
		}
	}
	
function error_set()
	{
		global $link;
		connectToDB();
		$ShowSetting=mysql_query("SELECT fatban FROM Admin", $link) or die (mysql_error($link));
		$data = mysql_fetch_array($ShowSetting);
		
		if (!isset($_POST['stepend_pos']) && !isset($_GET['set']))
		{
			echo '<div class="queue ta-c"><div class="yellow message">Заказ не сделан!</div><br>';
			echo '<a href="javascript:history.back()">Назад</a></div>';
			exit;
		}
		
		if (isset($_GET['set'])) 
		{ 
			$Set=$_GET['set']; 
			Vint($Set);
			if ($Set=='1'){
					echo '
						<div class="queue ta-c"><div class="yellow message">Защитный код введен неверно!</div><br>
						<a href="javascript:history.back()">Назад</a></div>';
					exit;
				}
				if ($Set=='2'){
					echo '
						<div class="queue ta-c"><div class="yellow message">Загружаемый баннер должен иметь формат JPG,PNG,GIF или SWF!</div><br>
						<a href="javascript:history.back()">Назад</a></div>';
					exit;
				}
				if ($Set=='3'){
					echo '
						<div class="queue ta-c"><div class="yellow message">Загружаемый баннер имеет неподходящие размеры для выбранной позиции!</div><br>
						<a href="javascript:history.back()">Назад</a></div>';
					exit;
				}
				if ($Set=='4'){
					echo '
						<div class="queue ta-c"><div class="yellow message">Произошел сбой. Пожалуйста сделайте заказ еще раз.</div><br>
						<a href="javascript:history.back()">Назад</a></div>';
					exit;
				}
				if ($Set=='5'){
					echo '
						<div class="queue ta-c"><div class="yellow message">Загружаемый баннер превышает '.$data['fatban'].' килобайт. Пожалуйста воспользуйтесь другим баннером.</div><br>
						<a href="javascript:history.back()">Назад</a></div>';
					exit;
				}
				exit;	
		}
	}	

function step21()
	{
		global $link;
		connectToDB();
		$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
		$data = mysql_fetch_array($ShowSetting);
		if (isset($_POST['stepend_pos'])) { $Pos=$_POST['stepend_pos']; Vint($Pos);}
		if (isset($_POST['stepend_name'])) { $Name=$_POST['stepend_name']; Vname($Name);}
		if (isset($_POST['stepend_url'])) { $URL=$_POST['stepend_url']; Vurl($URL);}
		if (isset($_POST['stepend_alt'])) { $ALT=$_POST['stepend_alt']; Vname($ALT);}
		if (isset($_POST['stepend_do'])) { $MyСhoiсe=$_POST['stepend_do']; $ppt=strripos($MyСhoiсe, '/'); if ($ppt === false) {Vint($MyСhoiсe);} else {Vdate($MyСhoiсe);}} 
		
		if (isset($_POST['stepend_string'])) { $StringСhoiсe=$_POST['stepend_string']; Vname($StringСhoiсe);}
		if (isset($_POST['stepend_pic'])) { $Pic=$_POST['stepend_pic']; Vnamepoint($Pic);}
		if (isset($_POST['stepend_size'])) { $Size=$_POST['stepend_size']; Vname($Size);}
		if (isset($_POST['stepend_price'])) { $Prise=$_POST['stepend_price']; Vprice($Prise);}
		if (isset($_POST['stepend_email'])) { $Email=$_POST['stepend_email']; Vmail($Email);}
		if (isset($_POST['stepend_about'])) { $About=$_POST['stepend_about']; DeletBadSimvol($About);}
		if (isset($_POST['stepend_pic'])) { $banner=$_POST['stepend_pic']; Vnamepoint($banner);}
		

		
		
		echo '<div class="action_container">';
		echo '<div class="check_info fl-l">';
		echo '<table class="order_table">';
		echo '<tr>';
		echo '<th colspan="2">Пожалуйста проверьте данные</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<tr>';
		echo '<td>Позиция</td>';
		echo '<td>'.$Pos.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Где будет показан баннер</td>';
		echo '<td>'.$About.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Размер</td>';
		echo '<td>'.$Size.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>'.$StringСhoiсe.'</td>';
		echo '<td>';
		if ($StringСhoiсe=='Дни')
			{
				echo '<div class="tooltip"><img style="cursor:pointer;" src="images/watch.png" width="20"><span>'.str($MyСhoiсe).'</span></div>';
			} 
			else 
			{
				echo $MyСhoiсe; 
			}
		echo '</td>';	
		echo '</tr>';
		echo '<tr>';
		echo '<td>Ваше имя</td>';
		echo '<td>'.$Name.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Ваш Email</td>';
		echo '<td>'.$Email.'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Сумма к оплате</td>';
		echo '<td>'.$Prise.'</td>';
		echo '</tr>';
		echo '</table>'; 
		echo '</div>';

		
		$Size = explode("x", $Size);
		echo '<div class="banner_upload fl-r">';
		echo '<table class="order_table">';
		echo '<tr>';
		echo '<th class="ta-c">Ваш баннер успешно загружен';
		if ($Size[0]>468 || $Size[1]>400){echo '<br><span style="color:silver; font-size:14px;">(Размер баннера временно уменьшен)</span>';}
		echo '</th>';
		echo '</tr>';
		echo '<td class="ta-c">';
		if (getExtension($banner) == 'swf')
		{
			echo '<span style="max-width:468px; max-height:400px;"><object type="application/x-shockwave-flash" data="/bannerbro/img/'.$banner.'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/img/'.$banner.'" /><param name="FlashVars" value="clickTAG='.$URL.'" /></object></span>';
		} else {
			echo '<span class="hidden-link" onclick="bannerbroUrl(\''.$URL.'\')"><img alt="'.$ALT.'" style="max-width:468px; max-height:400px; width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/img/'.$banner.'" /></span>';
		} 
		echo '</td>';
		echo '<tr>';
		echo '<td class="ta-c">';
		echo '<form action="step3.php" method="GET">';
		echo '<div style="display:none;">';
		echo '<input type="text" id="stepend_pos" name="stepend_pos" value=""><br>';
		echo '<input type="text" id="stepend_email" name="stepend_email" value=""><br>';
		echo '<input type="text" id="stepend_name" name="stepend_name" value=""><br>';
		echo '<input type="text" id="stepend_url" name="stepend_url" value=""><br>';
		echo '<input type="text" id="stepend_alt" name="stepend_alt" value=""><br>';
		echo '<input type="text" id="stepend_whot" name="stepend_whot" value=""><br>';
		echo '<input type="text" id="stepend_do" name="stepend_do" value=""><br>';
		echo '<input type="text" id="stepend_pic" name="stepend_pic" value=""><br>';
		echo '<input type="text" id="stepend_size" name="stepend_size" value=""><br>';
		echo '<input type="text" id="stepend_price" name="stepend_price" value=""><br>';
		echo '</div>';
		
		if ($data['Method']==0)
			{
				echo '<input style="text-align:center; font-weight:bold;" type="submit" value="Отправить заявку">';
			}
		if ($data['Method']==1)
			{
				echo '<input style="text-align:center; font-weight:bold;" type="submit" value="Оплатить заказ"><p>';
				echo '<div style="font-size:12px; color:grey; text-align:left; padding:20px;">*После оплаты, ваш заказ уйдет на модерацию администратору.</div>';
			}
		if ($data['Method']==2)
			{
				echo '<input style="text-align:center; font-weight:bold;" type="submit" value="Оплатить заказ"><p>';
				echo '<div style="font-size:12px; color:grey; text-align:left; padding:20px;">*После оплаты, ваш баннер сразу начнет отображаться на сайте.</div>';
			}			

		echo '</form>';
		echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		
		if ($StringСhoiсe=='Показов'){$Whot='Shows';}
		if ($StringСhoiсe=='Кликов'){$Whot='Click';}
		if ($StringСhoiсe=='Дни'){$Whot='Day';}
		print '<script>';
		print 'document.getElementById(\'stepend_pos\').value = "'.$Pos.'";';
		print 'document.getElementById(\'stepend_email\').value = "'.$Email.'";';
		print 'document.getElementById(\'stepend_do\').value = "'.$MyСhoiсe.'";';
		print 'document.getElementById(\'stepend_name\').value = "'.$Name.'";';
		print 'document.getElementById(\'stepend_url\').value = "'.$URL.'";';
		print 'document.getElementById(\'stepend_alt\').value = "'.$ALT.'";';
		print 'document.getElementById(\'stepend_size\').value = "'.$Size[0].'x'.$Size[1].'";';
		print 'document.getElementById(\'stepend_price\').value = "'.$Prise.'";';
		print 'document.getElementById(\'stepend_pic\').value = "'.$banner.'";';
		print 'document.getElementById(\'stepend_whot\').value = "'.$Whot.'";';
		print '</script>';
	}

function step3()
	{
		global $link, $mail_log, $mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$mail_admin;
		$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
		$data = mysql_fetch_array($ShowSetting);
		
		if (isset($_GET['stepend_pos'])) { $Pos=$_GET['stepend_pos']; Vint($Pos); }
		if (isset($_GET['stepend_name'])) { $User=$_GET['stepend_name']; Vname($User);}
		if (isset($_GET['stepend_url'])) { $URL=$_GET['stepend_url']; Vurl($URL);}
		if (isset($_GET['stepend_alt'])) { $ALT=$_GET['stepend_alt']; Vname($ALT); }
		if (isset($_GET['stepend_whot'])) { $Whot=$_GET['stepend_whot']; Vname($Whot);}
		if (isset($_GET['stepend_do'])) { $Do=$_GET['stepend_do']; $ppt=strripos($Do, '/'); if ($ppt === false) {Vint($Do);} else {Vdate($Do);}}
		if (isset($_GET['stepend_pic'])) { $Pic=$_GET['stepend_pic']; Vnamepoint($Pic);}
		if (isset($_GET['stepend_size'])) { $Size=$_GET['stepend_size']; Vname($Size);}
		if (isset($_GET['stepend_price'])) { $Price=$_GET['stepend_price']; Vprice($Price);}
		if (isset($_GET['stepend_email'])) { $Email=$_GET['stepend_email']; Vmail($Email);
				
		
		$checkpic=mysql_query("SELECT ID FROM Orders WHERE Picture ='$Pic'", $link) or die (mysql_error($link));
			$num_rows = mysql_num_rows($checkpic);
			$Id_data = mysql_fetch_array($checkpic);
			
		if ($num_rows >=1)
		{
			if ($data['Method']==0)
				{
					header('Location:step3.php?error=1'); 
					exit;
				}
				
			if ($data['Method']==1)
				{
					header('Location:show.php?id='.$Id_data['ID'].'&sec='.md5($Id_data['ID']).'');
					exit;
				}					

		}else 
		{ 
				if ($Pos!=null && $User!=null && $URL!=null && $Whot!=null && $Do!=null && $Pic!=null && $Size!=null && $Price!=null)
			{
				$MaxId = mysql_query("SELECT Orders FROM Admin", $link)  or die (mysql_error($link));
				$MaxId = mysql_result($MaxId,0);
				$MaxId =$MaxId + 1;
				mysql_query("UPDATE Admin SET Orders='$MaxId'", $link)  or die (mysql_error($link));
				
				$myd=date('Y-m-d H:i:s');	


				$ABCD=mysql_query ("SELECT * FROM Setting WHERE Positions='$Pos'", $link)  or die (mysql_error($link));
				$ABCDdata = mysql_fetch_array($ABCD);
				$d=$ABCDdata['Price'];
				
				if ($Whot=='Day')
					{
						$a=explode(',',$Do);
						$b=count($a);
						$Price=$b*$d;
						if ($ABCDdata['discount_1']!='' && $ABCDdata['discount_2']!='')
							{
								if ($Price>$ABCDdata['discount_1']) 
									{
										$discount=(100-$ABCDdata['discount_2'])/100;
										$Price=$Price*$discount;
									}
							}
					}
					
				if ($Whot=='Click' || $Whot=='Shows')
					{
						$Price=$Do*$d;
						if ($ABCDdata['discount_1']!='' && $ABCDdata['discount_2']!='')
							{
								if ($Price>$ABCDdata['discount_1']) 
									{
										$discount=(100-$ABCDdata['discount_2'])/100;
										$Price=$Price*$discount;
									}
							}
					}	
		$admin_order=0;
		GetInformationAutorMail();
		if ($mail_admin!=$Email){
		GoMail($mail_admin,'БаннерБро - Поступил заказ','На сайте - <b>'.$_SERVER['HTTP_HOST'].'</b> <br>Клиент - <b>'.$Email.'</b> ('.$User.')<br><br> <span style="color:green">Желает разместить рекламу</span> <br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/">Панель администратора</a>');
		GoMail($Email,'БаннерБро','Здравствуйте, <b>'.$User.'</b><br>Вы только что оформили заказ на размещение своей рекламы на сайте '.$_SERVER['HTTP_HOST'].'<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$MaxId.'&sec='.md5($MaxId).'">Ссылка на ваш заказ</a>');
		} else {
		$admin_order=1;
		}
		if ($data['Method']==0)
			{
				$sql = "INSERT INTO Orders(ID,date,Position,User,Email,Url,Alt,Whot,Do,Picture,Size,Price,Pay,Start,admin_order) VALUES ('$MaxId','$myd','$Pos','$User','$Email','$URL','$ALT','$Whot','$Do','$Pic','$Size','$Price','Ожидает одобрения','$myd',$admin_order)";
				mysql_query($sql);
				header('Location:show.php?id='.$MaxId.'&sec='.md5($MaxId).'');
			}
		if ($data['Method']==1)
			{
				$sql = "INSERT INTO Orders(ID,date,Position,User,Email,Url,Alt,Whot,Do,Picture,Size,Price,Pay,Start,admin_order) VALUES ('$MaxId','$myd','$Pos','$User','$Email','$URL','$ALT','$Whot','$Do','$Pic','$Size','$Price','Модерация после оплаты','$myd',$admin_order)";
				mysql_query($sql);
				header('Location:show.php?id='.$MaxId.'&sec='.md5($MaxId).'&tt='.$mail_admin.'');
			}	
		if ($data['Method']==2)
			{
				$sql = "INSERT INTO Orders(ID,date,Position,User,Email,Url,Alt,Whot,Do,Picture,Size,Price,Pay,Start,admin_order) VALUES ('$MaxId','$myd','$Pos','$User','$Email','$URL','$ALT','$Whot','$Do','$Pic','$Size','$Price','Ожидает оплаты','$myd',$admin_order)";
				mysql_query($sql);
				header('Location:show.php?id='.$MaxId.'&sec='.md5($MaxId).'');
			}				
				exit;
			}

		}
	}
	}
?>