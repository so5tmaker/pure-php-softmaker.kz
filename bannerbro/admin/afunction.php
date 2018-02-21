<?php
function checkLoggedIn($status){
  switch($status){
    case "yes":
      if(!isset($_SESSION["loggedIn"]) || !isset($_COOKIE["login"]) || $_COOKIE["login"]!=md5($_SESSION["login"].'gfkrfyt,f')){
		
        header("Location: /bannerbro/admin/login.php");
		exit;
      }
      break;
    case "no":
      if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true && isset($_COOKIE["login"]) && $_COOKIE["login"]==md5($_SESSION["login"].'gfkrfyt,f')){
	  
			   header("Location: /bannerbro/admin/index.php"); 
			   exit;	
	
      }
	  break;
	 
  }
  return true;
}

function checkPass($login, $password) {
  global $link;
  $password=substr($password, 0, 21);
  $query="SELECT Login, Password, Access FROM Users WHERE Login='$login' and Password='$password'";
  $result=mysql_query($query, $link)
    or die("checkPass fatal error: ".mysql_error());

  if(mysql_num_rows($result)==1) {
    $row=mysql_fetch_array($result);
    return $row;
  }
  return false;
}  


function connectToDB($turn_utf8 = 1){
  global $link;
  $link = mysql_pconnect(BRO_DB_HOST, BRO_DB_USER, BRO_DB_PASSWORD);
  mysql_select_db(BRO_DB_NAME, $link);
  if ($turn_utf8 == 1) {
    mysql_query("SET NAMES 'utf8'");
//    mysql_query("set charset utf8", $link);
  } else {
//      mysql_query("SET NAMES 'cp1251'");
  }

} 

function FirstConnect() {
	global $link;
	$link = mysql_pconnect(BRO_DB_HOST, BRO_DB_USER, BRO_DB_PASSWORD);
		if($link) {$p++; }
		else {header('Location:error.php?id=1'); exit;}
	$selected=mysql_select_db(BRO_DB_NAME, $link);
		if($selected) {$p++; }
		else {header('Location:error.php?id=2'); exit;}
  mysql_query("set charset utf8", $link);
  if ($p==2){header('Location:install.php'); exit;} 
} 


function flushMemberSession() {
unset($_SESSION["login"]);
unset($_SESSION["loggedIn"]);
unset($_SESSION["Access"]);
unset($_COOKIE['login']);	
setcookie('login', '', time() - 30);	   
return true;
}

function cleanMemberSession($login,$access) {
  $_SESSION["login"]=$login;
  $_SESSION["loggedIn"]=true;
  $_SESSION["Access"]=$access;
  header("Location: index.php");
  setcookie("login", md5($login.'gfkrfyt,f'), time()+7200);
}
?>