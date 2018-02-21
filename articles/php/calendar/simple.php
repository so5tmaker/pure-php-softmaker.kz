<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  
  <meta content="text/html; charset=windows-1251" http-equiv="Content-Type">
  <title>�������� ��������� �� PHP</title>
 
  <link rel="stylesheet" href="style/style.css" type="text/css">
 <script language="javascript">
 <!--
 function openblock(item) {
	var text=document.getElementById(item);	
	if (text.style.display='none')
	{
		text.style.display='block';
	} 
	else 
	{
		text.style.display='none';	
	}
 }
 function closeblock(item) {
	var text=document.getElementById(item);	
	text.style.display='none';	
 }
 //-->
 </script>

</head><body>
<p>��� ������� �������, �������� ��������� �� PHP ��������� CSS?</p>
<p>��� ������� ��������� �� PHP ��������� CSS?</p>

<p>CSS, ��������� �� PHP</p>

<p>� ���� ������ �������� ��� ������� ������ ��������� �� PHP. ����� ������ �������, �� ��������, ����� ������������� ����� CSS, ���������.</p>
<form name="frmMain" method="post">
<input type="button" class='button' name="cmdCal" value="�������� ���������" onClick='javascript:window.open("../phpblog/articles/php/calendar/simplecalendar.php?form=frmMain&field=txtDate","","top=50,left=400,width=230,height=200,menubar=no,toolbar=no,scrollbars=no,resizable=no,status=no"); return false;'>
</form>
<p><strong>����� 1.</strong><br />
	������ ����� ��������� ������� ����������� ����������, ������� �����, ����� �������� ������� ����� � ����. ����� ����, ����� ������� ������� ��� � �����. ��� ����� ��� �����:<br />
</p>
<ol>
	<li>������� ����</li>
	<li>������ ���� �������� ������</li>
	<li>��������� ���� �������� ������</li>
</ol>
<p><br />
	���� ��������, ������������� ���� �� ������ ����������, ����� ������ ���� ������ , ������ ������ ������ � ���������� ������� ����.</p>
	<br />
<p><strong>����� 2.</strong><br />
����� �������� ����������, ���������� � 1 ������ �� ������������� �������� getdate(). ���� �� �� ��������� ������� ���������� � �������, �� ��� ��������� ���������� � ������� ���� � ���� �������:</p>

<pre>Array<br />(<br />   [seconds] =&gt; 40<br />   [minutes] =&gt; 58<br />   [hours]   =&gt; 21<br />   [mday]    =&gt; 17<br />   [wday]    =&gt; 2<br />   [mon]     =&gt; 6<br />   [year]    =&gt; 2003<br />   [yday]    =&gt; 167<br />   [weekday] =&gt; Tuesday<br />   [month]   =&gt; June<br />   [0]       =&gt; 1055901520<br />)<br /><br />
</pre>
<p>����� �������� ��������� ���� ������ �� ������������� �������� <strong>mktime</strong> (int   hour, int minute, int second, int month, int day, int year [, int is_dst]), ������� �������� UNIX timestamp ����. ��� ����� ����� �������� ������� ����� ����� �������� 0. ������� ��� ����� ��������� ��������� �������:</p><br />

<pre>
&lt;?php<br />
&nbsp;&nbsp;&nbsp;&nbsp;$today&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;getdate();<br />
&nbsp;&nbsp;&nbsp;&nbsp;$firstDay&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon'],1,$today['year']));<br />
&nbsp;&nbsp;&nbsp;&nbsp;$lastDay&nbsp;&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));<br /> 
?&gt;
</pre>
<a name="step3"></a>
<p><strong>����� 3.</strong><br />
����� �������� ��������� ��� ����� ������� ������� � 7 ��������� ��� 7 ���� ������. ����� ����� ������� ������� �� ���������� ���� ������. ����� ���������� ������� ���������, � ������� ��������� ���������� � ������ � ����. � ����� ������������ � ��������� ���.</p>
	<p><a href="#step3" onclick="openblock('step3')">�������� >></a></p>
<div id='step3' style="display:none" >
	<pre>&lt;?php<br />
	&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;�������� ������� � �����������<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;table&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;tr&gt;&lt;th&nbsp;colspan=&quot;7&quot;&gt;'.$today['month'].&quot;&nbsp;-&nbsp;&quot;.$today['year'].&quot;&lt;/th&gt;&lt;/tr&gt;&quot;;<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&nbsp;class=&quot;days&quot;&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Mo&lt;/td&gt;&lt;td&gt;Tu&lt;/td&gt;&lt;td&gt;We&lt;/td&gt;&lt;td&gt;Th&lt;/td&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Fr&lt;/td&gt;&lt;td&gt;Sa&lt;/td&gt;&lt;td&gt;Su&lt;/td&gt;&lt;/tr&gt;';<br />
	?&gt; <br /></pre>
	<div><a href="#step3" onclick="closeblock('step3')">������ >></a></div>
	</div>
<a name="step4"></a>
<p><strong>����� 4.</strong><br />
������ � ��� ���� ��������� �������. ������� ��������� ��������� ������ ������. ��� ������� ��������, ��� ��� �� �� ������ �������� 1 � ������ ������, 2 �� ������ � �.�. ��� ���������, ���� ������ ���� ������ �����������, �� ���� ���? ��� �� ������ ��� ������, ��� ����� ������ wday (��. ����� 1, ������) �� ������� firstDay. ��� ���������� ������� ��� ��������� ������ ��� � ��������� ���������.���������� ��������� ���:
</p>
<p><a href="#step4" onclick="openblock('step4')">�������� >></a></p>
<div id='step4' style="display:none">
	<br />
	<pre>&lt;?php<br />echo '&lt;tr&gt;';<br />$actday=0;<br />for($i=1;$i&lt;=7;$i++)<br />	{<br />   	if ($i == $curDay ){ $class = ' class=&quot;cur_td&quot;';}<br />       else{ $class = ''; }<br />       if ($DayNameNum &lt;= $i)
		{<br />       	if ($DayNameNum==$i)<br />           {<br />           	$actday++;<br />           }<br />           if ($actday!==0)<br />			{<br />				echo &quot;&lt;td$class&gt;$actday&lt;/td&gt;&quot;; <br />				$actday++;<br />			}<br />       }<br />       else 
		{<br />       	echo &quot;&lt;td$class&gt;&amp;nbsp;&lt;/td&gt;&quot;;<br />       }<br />   }<br />	echo&nbsp;'&lt;/tr&gt;';<br />	$actday = $StartDay;
	?&gt; <br /></pre>
<a href="#step4" onclick="closeblock('step4')">������ >></a>
</div>
<a name="step5"></a>
<p><strong>����� 5.</strong><br />
<p><a href="#step5" onclick="openblock('step5')">�������� >></a></p>
<div id='step5' style="display:none">
��������� ����� ����� ���������� ��������� ����� � ���������. ��� ������� �����, ��� ������ ����� ����� ������� ������ ������ � ������� ������:</p>
<pre>&lt;?php<br />
	&nbsp;&nbsp;&nbsp;&nbsp;$fullWeeks&nbsp;=&nbsp;floor(($lastDay['mday']-$actday)/7);<br />	
	&nbsp;&nbsp;&nbsp;&nbsp;for&nbsp;($i=0;$i&lt;$fullWeeks;$i++){<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;for&nbsp;($j=0;$j&lt;7;$j++){<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$actday++;<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;&quot;&lt;td&gt;$actday&lt;/td&gt;&quot;;<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;/tr&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;}<br />
	?&gt;</pre>
<a href="#step5" onclick="closeblock('step5')">������ >></a>
</div>
<a name="step6"></a>
<p><strong>����� 6.</strong><br />
� ���� ������ ��� ����� �������� ���������� ��� ������ � ��������� ������ �������:</p>
<p><a href="#step6" onclick="openblock('step6')">�������� >></a></p>
<div id='step6' style="display:none">
<pre>&lt;?php<br />
&nbsp;&nbsp;&nbsp;&nbsp;//������� ��������� ��� ������<br />    if($actday &lt; $lastDay['mday']){<br />        echo '&lt;tr&gt;';<br />        for($i=0;$i&lt;7;$i++){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$actday++;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if($actday==$today['mday']){$class=' class=&quot;cur_td&quot;';
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
			else{<br />				$class = '';
				if($actday &lt;= $lastDay['mday']){echo&quot;&lt;td$class&gt;$actday&lt;/td&gt;&quot;;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	}else{echo'&lt;td&gt;&amp;nbsp;&lt;/td&gt;';}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;/tr&gt;';<br />		echo &quot;&lt;/table&gt;&quot;;
&nbsp;&nbsp;&nbsp;&nbsp;}<br />
?&gt; </pre>
<a href="#step6" onclick="closeblock('step6')">������ >></a>
</div>
<a name="step7"></a>
<p><strong>����� 7.</strong><br />
����� ��� ��������� �������� �������� �� �������� CSS �������. CSS ����� �������:</p>
<p><a href="#step7" onclick="openblock('step7')">�������� >></a></p>
<div id='step7' style="display:none">
<pre>table&nbsp;{<br />
&nbsp;&nbsp;&nbsp;background : #E0FFFF;<br />	width : 210px;<br />	border: 1px solid #888;<br />
}<br />

td&nbsp;{<br />
&nbsp;&nbsp;&nbsp;border: 1px solid Black;<br />	text-align : center;<br />	font-weight : bold;<br />	font-size : 12px;<br />
}<br />
<br />.cur_td{<br />	background : #FFEBCD;<br />	text-decoration : blink;<br />}

th&nbsp;{<br />
&nbsp;&nbsp;&nbsp;ont-family: Verdana;<br />	font-weight : bold;<br />	font-size : 14px;<br />	background : #E9ECEF;<br />
}<br />
<br />
.actday{<br />
&nbsp;&nbsp;&nbsp;&nbsp;background-color:&nbsp;#c22;<br />
&nbsp;&nbsp;&nbsp;&nbsp;font-weight:bold;<br />
}</pre>
<a href="#step7" onclick="closeblock('step7')">������ >></a>
</div>

<a name="step8"></a>
<p><strong>����� 8.</strong><br />
������ ��� ��������� �� PHP, ��������� ������� ��������� ������ CSS:</p>
<p><a href="#step8" onclick="openblock('step8')">�������� >></a></p>
<div id='step8' style="display:none">
<pre>&lt;!DOCTYPE&nbsp;html&nbsp;PUBLIC&nbsp;&quot;-//W3C//DTD&nbsp;XHTML&nbsp;1.0&nbsp;Transitional//EN&quot;&nbsp;&quot;DTD/xhtml1-transitional.dtd&quot;&gt;<br />
&lt;html&gt;<br />
&lt;head&gt;<br />
&nbsp;&nbsp;&nbsp;&lt;link href=&quot;style/style.css&quot;&nbsp;rel=&quot;stylesheet&quot;&nbsp;type=&quot;text/css&quot;&nbsp;/&gt;<br />
&lt;/head&gt;<br />
&lt;body&gt;<br />
&lt;?php<br />
function&nbsp;showCalendar(){<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;������� ���������� � ����.&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;��� ����� �������, ������ � ��������� ���� ������. <br />
&nbsp;&nbsp;&nbsp;&nbsp;$today&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;getdate();<br />
&nbsp;&nbsp;&nbsp;&nbsp;$firstDay&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon'],1,$today['year']));<br />
&nbsp;&nbsp;&nbsp;&nbsp;$lastDay&nbsp;&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;�������� ������� � ����������<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;table&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;tr&gt;&lt;th&nbsp;colspan=&quot;7&quot;&gt;'.$today['month'].&quot;&nbsp;-&nbsp;&quot;.$today['year'].
		 &quot;&lt;/th&gt;&lt;/tr&gt;&quot;;
<br />&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&nbsp;class=&quot;days&quot;&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Mo&lt;/td&gt;&lt;td&gt;Tu&lt;/td&gt;&lt;td&gt;We&lt;/td&gt;&lt;td&gt;Th&lt;/td&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Fr&lt;/td&gt;&lt;td&gt;Sa&lt;/td&gt;&lt;td&gt;Su&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;������� ������ ������ ���������<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;for($i=1;$i&lt;$firstDay['wday'];$i++){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;td&gt;&amp;nbsp;&lt;/td&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;$actday&nbsp;=&nbsp;0;<br />
&nbsp;&nbsp;&nbsp;&nbsp;for($i=$firstDay['wday'];$i&lt;=7;$i++){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$actday++;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;($actday&nbsp;==&nbsp;$today['mday'])&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$class&nbsp;=&nbsp;'&nbsp;class=&quot;actday&quot;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;else&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$class&nbsp;=&nbsp;'';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;&quot;&lt;td$class&gt;$actday&lt;/td&gt;&quot;;<br />
&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;// ������� ���������� ������ ������ �������� ������<br />
&nbsp;&nbsp;&nbsp;&nbsp;$fullWeeks&nbsp;=&nbsp;floor(($lastDay['mday']-$actday)/7);<br />

&nbsp;&nbsp;&nbsp;&nbsp;for&nbsp;($i=0;$i&lt;$fullWeeks;$i++){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;for&nbsp;($j=0;$j&lt;7;$j++){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$actday++;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;($actday&nbsp;==&nbsp;$today['mday'])&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$class&nbsp;=&nbsp;'&nbsp;class=&quot;actday&quot;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;else&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$class&nbsp;=&nbsp;'';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;&quot;&lt;td$class&gt;$actday&lt;/td&gt;&quot;;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;//������� ��������� ��� ������<br />
&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;($actday&nbsp;&lt;&nbsp;$lastDay['mday']){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;for&nbsp;($i=0;&nbsp;$i&lt;7;$i++){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$actday++;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;($actday&nbsp;==&nbsp;$today['mday'])&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$class&nbsp;=&nbsp;'&nbsp;class=&quot;actday&quot;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;else&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$class&nbsp;=&nbsp;'';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;($actday&nbsp;&lt;=&nbsp;$lastDay['mday']){<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;&quot;&lt;td$class&gt;$actday&lt;/td&gt;&quot;;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else&nbsp;{<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;td&gt;&amp;nbsp;&lt;/td&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;}<br />
&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;/table&gt;';<br />
}<br />
showCalendar();<br />
?&gt;

&lt;/body&gt;<br />
&lt;/html&gt; <br />
</pre>
<a href="#step8" onclick="closeblock('step8')">������ >></a>
</div>
<p>��������: <a href="http://www.phptoys.com/" target="_blank">http://www.phptoys.com/</a></p>
<p>������� ���������  ����� <a href="../../../articles/php/calendar/simplecalendar.zip" target="_blank">�����.</a></p>
</body>
</html>
