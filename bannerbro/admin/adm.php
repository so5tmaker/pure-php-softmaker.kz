<?php
connectToDB();
$AD_S=mysql_query("SELECT mail,host,login,password,form,ssl1,last_backup,absorb FROM Admin",$link)  or die (mysql_error($link));
$ADdata = mysql_fetch_array($AD_S);
$absorb=$ADdata['absorb'];
$ssl=$ADdata['ssl1'];
if ($ssl=='Yes'){$add_ssl='ssl://'; $port=465;}else{$add_ssl='';$port=25;}
$mail_set =$ADdata['mail'];
$mail_log =$ADdata['login'];
$mail_pas =$ADdata['password'];
$mail_from =$ADdata['form'];
$mail_smtp =$ADdata['host'];

$c=array();
$conf=mysql_query("SELECT * FROM conf", $link)  or die (mysql_error($link));
while($data_conf=mysql_fetch_array($conf)) {
	array_push($c, $data_conf['Text']);
}

$Showtemplate= mysql_query("SELECT * FROM template", $link)  or die (mysql_error($link));
while($data = mysql_fetch_array($Showtemplate))
	{
		if ($data['type']=='Good'){$mailZagGood=$data['title']; $mailTextGood=$data['text'];}
		if ($data['type']=='Bad'){$mailZag=$data['title']; $mailText=$data['text'];}
	}	
mysql_close($link);
?>