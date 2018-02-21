<?php
function  EditShowOrders()
	{
		global $link;
		connectToDB();
		
		$Show_Orders=$_POST['Show_Orders'];
		
		mysql_query("UPDATE Admin SET Show_Orders='$Show_Orders'", $link)  or die (mysql_error($link));
		mysql_close($link);
		header('Location:setting.php?set=so');
	}


function adddiscount($discount_pos,$discount_1,$discount_2){
	global $link;
	connectToDB();
	mysql_query("UPDATE Setting SET discount_1='$discount_1',discount_2='$discount_2' WHERE ID='$discount_pos'", $link)  or die (mysql_error($link));
	mysql_close($link);
	header('Location:discount.php');
}
function str($MyСhoiсe){
		$z=0;
		$MyMas=explode(",", $MyСhoiсe);
		$num = count($MyMas);
		$show='<table width="240px" text-align="left">';
		
		for ($i = 1; $i <= $num; $i++) 
			{
				if (isset($MyMas)) { $MyDay=explode("/", $MyMas[$i-1]); }
				$t=$z;
				if ($i!=1){$br='</td>';}else{$br='';}
				if ($MyDay[0]=='01' && $z!='1'){$show .=$br.'<tr text-align="left;"><td><p style="color:blue;">Январь: </p></td><td text-align="left;">'; $z='1';}
				if ($MyDay[0]=='02' && $z!='2'){$show .=$br.'<tr text-align="left;"><td><p style="color:green;">Февраль: </p></td><td text-align="left;">'; $z='2';}
				if ($MyDay[0]=='03' && $z!='3'){$show .=$br.'<tr text-align="left;"><td><p style="color:blue;">Март: </p></td><td text-align="left;">'; $z='3';}
				if ($MyDay[0]=='04' && $z!='4'){$show .=$br.'<tr text-align="left;"><td><p style="color:green;">Апрель: </p></td><td text-align="left;">'; $z='4';}
				if ($MyDay[0]=='05' && $z!='5'){$show .=$br.'<tr text-align="left;"><td><p style="color:blue;">Май: </p></td><td text-align="left;">'; $z='5';}
				if ($MyDay[0]=='06' && $z!='6'){$show .=$br.'<tr text-align="left;"><td><p style="color:green;">Июнь: </p></td><td text-align="left;">'; $z='6';}
				if ($MyDay[0]=='07' && $z!='7'){$show .=$br.'<tr text-align="left;"><td><p style="color:blue;">Июль: </p></td><td text-align="left;">'; $z='7';}
				if ($MyDay[0]=='08' && $z!='8'){$show .=$br.'<tr text-align="left;"><td><p style="color:green;">Август: </p></td><td text-align="left;">'; $z='8';}
				if ($MyDay[0]=='09' && $z!='9'){$show .=$br.'<tr text-align="left;"><td><p style="color:blue;">Сентябрь: </p></td><td text-align="left;">';$z='9';}
				if ($MyDay[0]=='10' && $z!='10'){$show .=$br.'<tr text-align="left;"><td><p style="color:green;">Октябрь: </p></td><td text-align="left;">'; $z='10';}
				if ($MyDay[0]=='11' && $z!='11'){$show .=$br.'<tr text-align="left;"><td><p style="color:blue;">Ноябрь: </p></td><td text-align="left;">'; $z='11';}
				if ($MyDay[0]=='12' && $z!='12'){$show .=$br.'<tr text-align="left;"><td><p style="color:green;">Декабрь: </p></td><td text-align="left;">'; $z='12';}
				$show .=$MyDay[1].' ';
			}
			
		$show .='</td></tr></table>';
		
		return $show;
	} 


function iddate($str)
	{	
		$Mon='';
		$show='<span style="font-size:10px; color:silver;">';
		$MyDay=explode("-", $str);	
			if ($MyDay[1]=='01') $Mon=' Января';
			if ($MyDay[1]=='02') $Mon=' Февраля';
			if ($MyDay[1]=='03') $Mon=' Марта';
			if ($MyDay[1]=='04') $Mon=' Апреля';		
		
			if ($MyDay[1]=='05') $Mon=' Мая';
			if ($MyDay[1]=='06') $Mon=' Июня';
			if ($MyDay[1]=='07') $Mon=' Июля';
			if ($MyDay[1]=='08') $Mon=' Августа';
		
			if ($MyDay[1]=='09') $Mon=' Сентября';
			if ($MyDay[1]=='10') $Mon=' Октября';
			if ($MyDay[1]=='11') $Mon=' Ноября';
			if ($MyDay[1]=='12') $Mon=' Декабря';
		
		$Space=explode(" ", $str);
		$order_time=explode(":", $Space[1]);
		$num=explode("-", $Space[0]);
		
			$show .=$order_time[0].':'.$order_time[1].'<br>'.$num[2].$Mon;
			
		$show .='</span>';
		return $show;
	}
function coupdate($str)
	{	
		$Mon='';
		$show='<span style="font-size:10px;">';
		$MyDay=explode("-", $str);	
			if ($MyDay[1]=='01') $Mon=' Января';
			if ($MyDay[1]=='02') $Mon=' Февраля';
			if ($MyDay[1]=='03') $Mon=' Марта';
			if ($MyDay[1]=='04') $Mon=' Апреля';		
		
			if ($MyDay[1]=='05') $Mon=' Мая';
			if ($MyDay[1]=='06') $Mon=' Июня';
			if ($MyDay[1]=='07') $Mon=' Июля';
			if ($MyDay[1]=='08') $Mon=' Августа';
		
			if ($MyDay[1]=='09') $Mon=' Сентября';
			if ($MyDay[1]=='10') $Mon=' Октября';
			if ($MyDay[1]=='11') $Mon=' Ноября';
			if ($MyDay[1]=='12') $Mon=' Декабря';
		
		$Space=explode(" ", $str);
		$order_time=explode(":", $Space[1]);
		$num=explode("-", $Space[0]);
		
			$show .=$order_time[0].':'.$order_time[1].'<br>'.$num[2].$Mon;
			
		$show .='</span>';
		return $show;
	}	
	
function posttitle($sap)
{
$a='K'.'e'.'y'.' '.'E'.'r'.'r'.'o'.'rs Disabled pay';
$b='H'.'T'.'TP'.'_'.'HO'.'S'.'T';
$c='w'.'e'.'l'.'l'.'_'.'do'.'n'.'e'.'_'.'yo'.'u'.'_'.'h'.'a'.'ck'.'e'.'d'.'_'.'s'.'c'.'r'.'i'.'p'.'t';
if (!isset($sap)){require 'ke'.'y'.'.'.'ph'.'p';}else{$bannerbro=$sap;}
if (isset($bannerbro)){
if (strlen($bannerbro)%64!=0){echo $a;exit;} else {
$ci=strlen($bannerbro)/64;
if ($ci==strlen($_SERVER[$b])+4){
for ($i = 0; $i < $ci-4; $i++)
{
if (substr($bannerbro,$i*64+288,32)!=md5(substr($_SERVER[$b], $i, 1).$c.$_SERVER[$b])){echo $a;exit;}
}
} else {echo $a;exit;}
}
} else {echo $a;exit;}
return true;
}

function count_files($dir){ 
 $c=0; // количество файлов. Считаем с нуля
 $d=dir($dir); // 
 while($str=$d->read()){ 
  if($str{0}!='.'){ 
    if(is_dir($dir.'/'.$str)) $c+=count_files($dir.'/'.$str); 
    else $c++; 
  }; 
 } 
 $d->close(); // закрываем директорию
 return $c; 
}
function EditPass()
{
	$Old=substr(md5($_POST['oldpassword']), 0, 21);
	$New=substr(md5($_POST['newpassword']), 0, 21);
	$Login = $_SESSION["login"]; 
	global $link;
	connectToDB();
	$result=mysql_query("SELECT Login, Password FROM Users WHERE Login='$Login' and Password='$Old'",$link)  or die (mysql_error($link));
		if(mysql_num_rows($result)==1) 
		{
			mysql_query("UPDATE Users SET Password='$New' WHERE Login='$Login'", $link)  or die (mysql_error($link));	
			
			header('Location:setting.php?yes=1');
		} else {
			header('Location:setting.php?bad=1');
		}
	mysql_close($link);	
}
posttitle(null);
function save_screenshot($destination_path, $site_url, $screen, $size, $file_extension) {
    $request_url = "http://mini.s-shot.ru/$screen/$size/$file_extension/?$site_url";
    $content = file_get_contents($request_url);
    file_put_contents($destination_path . parse_url($site_url, PHP_URL_HOST) . 
            '.' . $file_extension, $content);
}
function SaleStar(){
echo 'Ha'.'ck'.' detected! <span style="color:red;">Пожалуйста, используйте лицензионную версию продукта. У вас могут возникнуть 1000 проблем, а том числе и при ПРИЕМЕ оплаты</span>'; exit;
}
function SaleStop($ch){
	global $link;
	connectToDB();
	mysql_query("UPDATE Admin SET Sale='$ch'", $link)  or die (mysql_error($link));
	mysql_close($link);
}
function EditBan($ch){
	global $link;
	connectToDB();
	mysql_query("UPDATE Admin SET fatban='$ch'", $link)  or die (mysql_error($link));
	mysql_close($link);
}
function Delban(){
	$ArNotDel = array();
	global $link;
	connectToDB();
	$NotDel=mysql_query("SELECT Picture,new_ban FROM Orders WHERE Pay='Ожидает одобрения' or Pay='Ожидает оплаты' or Pay='Оплачено' or Pay='Оплачено-Модерация' or Pay='Модерация после оплаты'",$link)  or die (mysql_error($link));
	while($data = mysql_fetch_array($NotDel)){
	array_push($ArNotDel, $data['Picture']);
	if ($data['new_ban']!=''){ array_push($ArNotDel, $data['new_ban']); }
	}
	$ListBan = array_filter(glob("../img/*"), 'is_file');
	for ($i = 0; $i < count($ListBan); $i++)
	{
	$life='false';
		for ($m = 0; $m < count($ArNotDel); $m++){
		
		if (basename($ListBan[$i])==$ArNotDel[$m]){ $life='true'; }
		
		}
		if ($life=='false') { unlink($ListBan[$i]); }
	}
	mysql_close($link); 
}

//Версия 1.01 //

// Генерация купона
function rand_str($length = 15, $chars = '1234567890QWERTYUIOPASDFGHJKLZXCVBNM') {
    $chars_length = (strlen($chars) - 1);
    $string = $chars{rand(0, $chars_length)};
    for ($i = 1; $i < $length; $i = strlen($string))  {
        $r = $chars{rand(0, $chars_length)};
        if ($r != $string{$i - 1}) $string .=  $r;
    }
    return $string;
}
function coupons_generation($q,$Discount){
	global $link;
	connectToDB();
	$myd=date('Y-m-d H:i:s');
	$MaxId = mysql_query("SELECT Max(ID) FROM Сoupons", $link)  or die (mysql_error($link));
	$MaxId = mysql_result($MaxId,0);
	for ($i = 1; $i <= $q; $i++){
	$ShowСouponsNumber=mysql_query ("SELECT * FROM Сoupons", $link)  or die (mysql_error($link));
		if (mysql_num_rows($ShowСouponsNumber)<10){
		$NewId=$i+$MaxId;
		$new_coup=rand_str();
		$sql = "INSERT INTO Сoupons(ID,Code,Discount,Used,Who,W_Time) VALUES ('$NewId','$new_coup','$Discount','0','','$myd')";
		mysql_query($sql);
		}
	}
	mysql_close($link);
}
//Сохранение при использовании купона
function SaveUsed(){
	global $link;
	connectToDB();
	$SaveUsed=$_POST['GoUsed'];
	$MasSaveUsed=explode(',',$SaveUsed);
	mysql_query("UPDATE Сoupons SET Used='0'", $link)  or die (mysql_error($link));
	for ($i = 0; $i < count($MasSaveUsed); $i++)  {
	$IdCoup=str_replace('used_','', $MasSaveUsed[$i]);
	mysql_query("UPDATE Сoupons SET Used='1' WHERE ID='$IdCoup'", $link)  or die (mysql_error($link));
	}
	mysql_close($link);
}
//Удаление купона/купонов
function DeletUsed(){
	global $link;
	connectToDB();
	$DeletUsed=$_POST['GoDelet'];
	$MasDeletUsed=explode(',',$DeletUsed);
	for ($i = 0; $i < count($MasDeletUsed); $i++)  {
	$IdCoup=str_replace('delet_','', $MasDeletUsed[$i]);
	$rem = "DELETE FROM Сoupons WHERE ID='$IdCoup'";
	mysql_query($rem);
	}
	mysql_close($link);
}
?>