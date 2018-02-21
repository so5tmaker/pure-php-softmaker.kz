<link rel="stylesheet" type="text/css" href="/bannerbro/css/def.css">
<?php
require "admin/config.php";

function getExtension($filename) {
    $path_info = pathinfo($filename);
    return $path_info['extension'];
  }
 
function setText($text) {
    global $link;
    connectToDB();
    $SBDay=mysql_query("SELECT Id FROM temp WHERE id='1'", $link) or die (mysql_error($link));
    if($Daydata = mysql_fetch_array($SBDay))
    { 
        mysql_query("UPDATE `temp` SET `text`='".addslashes($text)."' WHERE `id`='1'", $link)  or die (mysql_error($link));
    } else {
        mysql_query("INSERT INTO `temp`(`id`, `text`) VALUES (1,'".addslashes($text)."')", $link)  or die (mysql_error($link));
    }
  }  
  
function codbanner($whot)
{

// <!-- Подключение к БД --!>
global $link;
connectToDB();

// <!-- $StopBot - список ботов которых не считать при заходе на ваш сайт. Попросту говоря, +1 к показу баннера у ботов засчитан не будет. --!>
$StopBot = 
"(Yandex|Googlebot|StackRambler|Slurp|MSNBot|Teoma|Scooter|ia_archiver
|Lycos|Mail.Ru|Aport|WebAlta|Googlebot-Mobile|Googlebot-Image|Mediapartners-Google
|Adsbot-Google|MSNBot-NewsBlogs|MSNBot-Products|MSNBot-Media|Yahoo Slurp|msnbot|Parser|bot|Robot)";
// <!-- Выборка заказа в БД --!>
		$SB=mysql_query("SELECT Picture,ID,Shows,Whot,Clicks,Do,Size,Url,Alt FROM Orders WHERE Position='$whot' and Pay='Оплачено' ORDER BY RAND()", $link) or die (mysql_error($link));
		if (mysql_num_rows($SB)!=null) // Узнаем есть ли активные заказы
		{
			$data = mysql_fetch_array($SB);
			$Size = explode("x", $data['Size']); // разбиваем размер баннера на ширину и длину
			#Банер по дате
			if ($data['Whot']=='Day')
			{
				$ShowDay=0;
				$SBDay=mysql_query("SELECT Picture,ID,Shows,Whot,Clicks,Do,Size,Url,Alt FROM Orders WHERE Position='$whot' and Pay='Оплачено'", $link) or die (mysql_error($link));
				while($Daydata = mysql_fetch_array($SBDay))
				{ 
					$m=0;
					$WhotShowDay = explode(",", $Daydata['Do']);
					for ($i = 0; $i < count($WhotShowDay); $i++) 
					{
						$ID_Now=$Daydata['ID'];
						if ($WhotShowDay[$i]===date("m/d/Y")) 
						{# �?щу баннер который должен показываться именно сегодня. 
							$ShowDay=1;
							if (getExtension($Daydata['Picture']) == 'swf')
							{
								$code =  '<span onmousedown="bannerbroUrl(\''.$Daydata['ID'].'\',\''.$Daydata['Url'].'\')"><object type="application/x-shockwave-flash" data="/bannerbro/img/'.$Daydata['Picture'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/img/'.$Daydata['Picture'].'" /><param name="FlashVars" value="clickTAG=" /></object></span>'; //Выводим флеш картинку.
							} else 
							{
								$code =  '<span class="hidden-link" onclick="bannerbroUrl(\''.$Daydata['ID'].'\',\''.$Daydata['Url'].'\')"><img style="width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/img/'.$Daydata['Picture'].'"></span>';
							}
							// <!-- Обновляем переменную показов если это не бот --!>	
							if (!preg_match($StopBot,$_SERVER['HTTP_USER_AGENT']))
							{ 	
								if (!isset($_COOKIE['BlokShows']))
									{							
										$Show_Now=$Daydata['Shows']; // сколько сейчас показов
										$Show_Now++; // +1 к показу
										mysql_query("UPDATE Orders SET Shows='$Show_Now' WHERE ID='$ID_Now'", $link)  or die (mysql_error($link));
									}
							}
						}
						//******Фикс 5 декабря********//
						$Nowcheckyear=explode('/',date("m/d/Y"));
						$Ordercheckyear=explode('/',$WhotShowDay[$i]);
						if ($Ordercheckyear[2]===$Nowcheckyear[2]){ 
						if ($WhotShowDay[$i] < date("m/d/Y")){ $m++; } # подсчет убывших дней
						}
						if ($Nowcheckyear[2]>$Ordercheckyear[2]){ $m++; } # подсчет убывших лет
						//^^^^^^Фикс 5 декабря^^^^^^^//
					}
					if ($m >= count($WhotShowDay)) { mysql_query("UPDATE Orders SET Pay='End' WHERE ID='$ID_Now'", $link)  or die (mysql_error($link)); } # Конец показам баннера у этого заказа.
				}

				if ($ShowDay==0)
					{
						$ShowDay=1;
						$SDBA=mysql_query("SELECT ID,bdefault,udefault,Size,cdefault FROM Setting WHERE ID='$whot'", $link) or die (mysql_error($link));
						$notdata = mysql_fetch_array($SDBA);
						if (mysql_num_rows($SDBA)!=null){
						$Size = explode("x", $notdata['Size']); // разбиваем размер баннера на ширину и длину
							if ($notdata['bdefault']!='') // Узнаем есть ли баннер по умолчанию
							{
							if ($notdata['udefault']!=''){
							if (getExtension($notdata['bdefault'])=='swf'){ $code =  '<object type="application/x-shockwave-flash" data="/bannerbro/admin/img/'.$notdata['bdefault'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/admin/img/'.$notdata['bdefault'].'" /><param name="wmode" value="transparent" /><param name="FlashVars" value="clickTAG='.$notdata['udefault'].'" /></object>';}
							if (getExtension($notdata['bdefault'])!='swf'){ $code =  '<span class="hidden-link" onclick="bannerbroDefUrl(\''.$notdata['udefault'].'\')"><img style="width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/admin/img/'.$notdata['bdefault'].'"></span>';}
							} else {
							if (getExtension($notdata['bdefault'])=='swf'){ $code =  '<object type="application/x-shockwave-flash" data="/bannerbro/admin/img/'.$notdata['bdefault'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/admin/img/'.$notdata['bdefault'].'" /><param name="wmode" value="transparent" /><param name="FlashVars" value="clickTAG=http://'.$_SERVER['HTTP_HOST'].'/bannerbro" /></object>';}
							if (getExtension($notdata['bdefault'])!='swf'){ $code =  '<a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/" target="_blank"><img style="width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/admin/img/'.$notdata['bdefault'].'"></a>';}
							}
							} else {
								if ($notdata['cdefault']!=''){
								$code =  stripslashes($notdata['cdefault']);
//                                                                $code =  'Code Adsense if ($ShowDay==0)';
								} else {
								$code =  '<a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/" target="_blank"><div class="DefaultImg" style=" width:'.$Size[0].'px;height:'.$Size[1].'px;"><div class="DefaultText" style="word-wrap: break-word;">Купить здесь баннер</div></div></a>'; // Если нет то выводим css с рамкой
								}
							}
						}
						$ShowDay=1;
					} 
			} 
			
			#Банер НЕ по дате
			if ($data['Whot']!='Day')
			{
// <!-- Показ баннера или флеш баннера --!>
				if (getExtension($data['Picture']) == 'swf')
				{
				$code =  '<span onmousedown="bannerbroUrl(\''.$data['ID'].'\',\''.$data['Url'].'\')"><object type="application/x-shockwave-flash" data="/bannerbro/img/'.$data['Picture'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/img/'.$data['Picture'].'" /><param name="FlashVars" value="clickTAG=" /></object></span>'; //Выводим флеш картинку.
				} else {  
				$code =  '<span class="hidden-link" onclick="bannerbroUrl(\''.$data['ID'].'\',\''.$data['Url'].'\')"><img style="width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/img/'.$data['Picture'].'"></span>'; // Выводим обычную картинку
				}  
		$ID_Now=$data['ID']; // узнаем ид заказа
// <!-- Обновляем переменную показов --!>
				if (!preg_match($StopBot,$_SERVER['HTTP_USER_AGENT']))
				{
					if (!isset($_COOKIE['BlokShows']))
						{
							$Show_Now=$data['Shows']; // сколько сейчас показов
							$Show_Now++; // +1 к показу
							mysql_query("UPDATE Orders SET Shows='$Show_Now' WHERE ID='$ID_Now'", $link)  or die (mysql_error($link)); // заносим показы в базу 
						}
				} 	
// <!-- Останавливаем заказ --!>	
				if ($data['Whot']=='Click' && $data['Clicks'] >= $data['Do']) {mysql_query("UPDATE Orders SET Pay='End' WHERE ID='$ID_Now'", $link)  or die (mysql_error($link));} // Останавливаем заказ если клики кончились
				if ($data['Whot']=='Shows' && $data['Shows'] >= $data['Do']) {mysql_query("UPDATE Orders SET Pay='End' WHERE ID='$ID_Now'", $link)  or die (mysql_error($link));} // Останавливаем заказ если показы кончились				
			}
		} else {
// <!-- �?дем к баннеру по умолчанию --!>
			$SDB=mysql_query("SELECT ID,bdefault,udefault,Size,cdefault FROM Setting WHERE ID='$whot'", $link) or die (mysql_error($link));
			$data = mysql_fetch_array($SDB);
			if (mysql_num_rows($SDB)!=null)	{
			$Size = explode("x", $data['Size']); // разбиваем размер баннера на ширину и длину
				if ($data['bdefault']!='') // Узнаем есть ли баннер по умолчанию
				{
				if ($data['udefault']!=''){
				if (getExtension($data['bdefault'])=='swf'){ $code =  '<object type="application/x-shockwave-flash" data="/bannerbro/admin/img/'.$data['bdefault'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/admin/img/'.$data['bdefault'].'" /><param name="wmode" value="transparent" /><param name="FlashVars" value="clickTAG='.$data['udefault'].'" /></object>';}
				if (getExtension($data['bdefault'])!='swf'){ $code =  '<span class="hidden-link" onclick="bannerbroDefUrl(\''.$data['udefault'].'\')"><img style="width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/admin/img/'.$data['bdefault'].'"></span>';}
				} else {
				if (getExtension($data['bdefault'])=='swf'){ $code =  '<object type="application/x-shockwave-flash" data="/bannerbro/admin/img/'.$data['bdefault'].'" width="'.$Size[0].'" height="'.$Size[1].'"><param name="movie" value="/bannerbro/admin/img/'.$data['bdefault'].'" /><param name="wmode" value="transparent" /><param name="FlashVars" value="clickTAG=http://'.$_SERVER['HTTP_HOST'].'/bannerbro" /></object>';}
				if (getExtension($data['bdefault'])!='swf'){ $code =  '<a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/" target="_blank"><img style="width:'.$Size[0].'px; height:'.$Size[1].'px;" src="/bannerbro/admin/img/'.$data['bdefault'].'"></a>';}
				}
				} else {
					if ($data['cdefault']!=''){
					//$code =  $data['cdefault'];
                                        $code =   stripslashes($data['cdefault']);
//					$code =  'Code Adsense if ($data[Whot]!=Day)'.$whot;
					} else {
					$code =  '<a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/" target="_blank"><div class="DefaultImg" style=" width:'.$Size[0].'px;height:'.$Size[1].'px;"><div class="DefaultText" style="word-wrap: break-word;">Купить здесь баннер</div></div></a>'; // Если нет то выводим css с рамкой
					}
				}
			}
		} 
//mysql_close($link); // закрываем соединение с бд
                setText($code);
}
date_default_timezone_set('Europe/Moscow');
?>
<script type="text/javascript">
function bannerbroUrl(id,url) {
window.open("/bannerbro/red.php?id="+id+'&url='+url);
}
function bannerbroDefUrl(url) {
window.open(url);
}
</script>