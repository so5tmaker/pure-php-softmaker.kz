<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  
  <meta content="text/html; charset=windows-1251" http-equiv="Content-Type">
  <title>Создание календаря на PHP</title>
 
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
<p>Как создать простой, красивый календарь на PHP используя CSS?</p>
<p>Как создать календарь на PHP используя CSS?</p>

<p>CSS, календарь на PHP</p>

<p>В этой статье показано как создать скрипт календаря на PHP. Будет создан простой, но красивый, легко настраиваемый через CSS, календарь.</p>
<form name="frmMain" method="post">
<input type="button" class='button' name="cmdCal" value="Показать календарь" onClick='javascript:window.open("../phpblog/articles/php/calendar/simplecalendar.php?form=frmMain&field=txtDate","","top=50,left=400,width=230,height=200,menubar=no,toolbar=no,scrollbars=no,resizable=no,status=no"); return false;'>
</form>
<p><strong>Пункт 1.</strong><br />
	Прежде всего попробуем собрать необходимую информацию, которая нужна, чтобы показать текущий месяц и день. Кроме того, нужно вывести текущий год и месяц. Для этого нам нужно:<br />
</p>
<ol>
	<li>Текущий день</li>
	<li>Первый день текущего месяца</li>
	<li>Последний день текущего месяца</li>
</ol>
<p><br />
	Имея сведения, перечисленные выше мы сможем определить, какой первый день месяца , какова длинна месяца и определить текущий день.</p>
	<br />
<p><strong>Пункт 2.</strong><br />
Чтобы получить информацию, упомянутую в 1 пункте мы воспользуемся функцией getdate(). Если мы не указываем никаких параметров в функции, то она возвратит информацию о текущем дате в виде массива:</p>

<pre>Array<br />(<br />   [seconds] =&gt; 40<br />   [minutes] =&gt; 58<br />   [hours]   =&gt; 21<br />   [mday]    =&gt; 17<br />   [wday]    =&gt; 2<br />   [mon]     =&gt; 6<br />   [year]    =&gt; 2003<br />   [yday]    =&gt; 167<br />   [weekday] =&gt; Tuesday<br />   [month]   =&gt; June<br />   [0]       =&gt; 1055901520<br />)<br /><br />
</pre>
<p>Чтобы получить последний день месяца мы воспользуемся функцией <strong>mktime</strong> (int   hour, int minute, int second, int month, int day, int year [, int is_dst]), которая получает UNIX timestamp даты. Для этого пятый аргумент функции будет иметь значение 0. Поэтому код будет выглядеть следующим образом:</p><br />

<pre>
&lt;?php<br />
&nbsp;&nbsp;&nbsp;&nbsp;$today&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;getdate();<br />
&nbsp;&nbsp;&nbsp;&nbsp;$firstDay&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon'],1,$today['year']));<br />
&nbsp;&nbsp;&nbsp;&nbsp;$lastDay&nbsp;&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));<br /> 
?&gt;
</pre>
<a name="step3"></a>
<p><strong>Пункт 3.</strong><br />
Чтобы показать календарь нам нужно вывести таблицу с 7 столбцами для 7 дней недели. Число строк таблицы зависит от количества дней месяца. Также необходимо вывести заголовок, в котором отразится информация о месяце и годе. А также подзаголовок с названием дня.</p>
	<p><a href="#step3" onclick="openblock('step3')">Показать >></a></p>
<div id='step3' style="display:none" >
	<pre>&lt;?php<br />
	&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;Создадим таблицу с заголовками<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;table&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;tr&gt;&lt;th&nbsp;colspan=&quot;7&quot;&gt;'.$today['month'].&quot;&nbsp;-&nbsp;&quot;.$today['year'].&quot;&lt;/th&gt;&lt;/tr&gt;&quot;;<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&nbsp;class=&quot;days&quot;&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Mo&lt;/td&gt;&lt;td&gt;Tu&lt;/td&gt;&lt;td&gt;We&lt;/td&gt;&lt;td&gt;Th&lt;/td&gt;';<br />
	&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Fr&lt;/td&gt;&lt;td&gt;Sa&lt;/td&gt;&lt;td&gt;Su&lt;/td&gt;&lt;/tr&gt;';<br />
	?&gt; <br /></pre>
	<div><a href="#step3" onclick="closeblock('step3')">Скрыть >></a></div>
	</div>
<a name="step4"></a>
<p><strong>Пункт 4.</strong><br />
Сейчас у нас есть заголовок таблицы. Давайте попробуем заполнить первую строку. Это сделать непросто, так как Вы не можете записать 1 в первую ячейку, 2 во вторую и т.д. Это получится, если первый день месяца понедельник, но если нет? Что бы решить эту задачу, нам нужен индекс wday (см. пункт 1, массив) из массива firstDay. Эта информация поможет нам заполнить пустые дни в календаре пробелами.Посмотрите следующий код:
</p>
<p><a href="#step4" onclick="openblock('step4')">Показать >></a></p>
<div id='step4' style="display:none">
	<br />
	<pre>&lt;?php<br />echo '&lt;tr&gt;';<br />$actday=0;<br />for($i=1;$i&lt;=7;$i++)<br />	{<br />   	if ($i == $curDay ){ $class = ' class=&quot;cur_td&quot;';}<br />       else{ $class = ''; }<br />       if ($DayNameNum &lt;= $i)
		{<br />       	if ($DayNameNum==$i)<br />           {<br />           	$actday++;<br />           }<br />           if ($actday!==0)<br />			{<br />				echo &quot;&lt;td$class&gt;$actday&lt;/td&gt;&quot;; <br />				$actday++;<br />			}<br />       }<br />       else 
		{<br />       	echo &quot;&lt;td$class&gt;&amp;nbsp;&lt;/td&gt;&quot;;<br />       }<br />   }<br />	echo&nbsp;'&lt;/tr&gt;';<br />	$actday = $StartDay;
	?&gt; <br /></pre>
<a href="#step4" onclick="closeblock('step4')">Скрыть >></a>
</div>
<a name="step5"></a>
<p><strong>Пункт 5.</strong><br />
<p><a href="#step5" onclick="openblock('step5')">Показать >></a></p>
<div id='step5' style="display:none">
Следующим шагом будет заполнение остальных строк в календаре. Это сделать легче, нам только нужно знать сколько полных недель в текущем месяце:</p>
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
<a href="#step5" onclick="closeblock('step5')">Скрыть >></a>
</div>
<a name="step6"></a>
<p><strong>Пункт 6.</strong><br />
В этом пункте нам нужно добавить оставшиеся дни месяца в последние строки таблицы:</p>
<p><a href="#step6" onclick="openblock('step6')">Показать >></a></p>
<div id='step6' style="display:none">
<pre>&lt;?php<br />
&nbsp;&nbsp;&nbsp;&nbsp;//Покажем последние дни месяца<br />    if($actday &lt; $lastDay['mday']){<br />        echo '&lt;tr&gt;';<br />        for($i=0;$i&lt;7;$i++){<br />
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
<a href="#step6" onclick="closeblock('step6')">Скрыть >></a>
</div>
<a name="step7"></a>
<p><strong>Пункт 7.</strong><br />
Чтобы наш календарь выглядел красивее мы создадим CSS таблицу. CSS будет простым:</p>
<p><a href="#step7" onclick="openblock('step7')">Показать >></a></p>
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
<a href="#step7" onclick="closeblock('step7')">Скрыть >></a>
</div>

<a name="step8"></a>
<p><strong>Пункт 8.</strong><br />
Полный код календаря на PHP, используя таблицу каскадных стилей CSS:</p>
<p><a href="#step8" onclick="openblock('step8')">Показать >></a></p>
<div id='step8' style="display:none">
<pre>&lt;!DOCTYPE&nbsp;html&nbsp;PUBLIC&nbsp;&quot;-//W3C//DTD&nbsp;XHTML&nbsp;1.0&nbsp;Transitional//EN&quot;&nbsp;&quot;DTD/xhtml1-transitional.dtd&quot;&gt;<br />
&lt;html&gt;<br />
&lt;head&gt;<br />
&nbsp;&nbsp;&nbsp;&lt;link href=&quot;style/style.css&quot;&nbsp;rel=&quot;stylesheet&quot;&nbsp;type=&quot;text/css&quot;&nbsp;/&gt;<br />
&lt;/head&gt;<br />
&lt;body&gt;<br />
&lt;?php<br />
function&nbsp;showCalendar(){<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;Получим информацию о днях.&nbsp;<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;Нам нужен текущий, первый и последний день месяца. <br />
&nbsp;&nbsp;&nbsp;&nbsp;$today&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;getdate();<br />
&nbsp;&nbsp;&nbsp;&nbsp;$firstDay&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon'],1,$today['year']));<br />
&nbsp;&nbsp;&nbsp;&nbsp;$lastDay&nbsp;&nbsp;=&nbsp;getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;Создадим таблицу с заголовком<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;table&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;tr&gt;&lt;th&nbsp;colspan=&quot;7&quot;&gt;'.$today['month'].&quot;&nbsp;-&nbsp;&quot;.$today['year'].
		 &quot;&lt;/th&gt;&lt;/tr&gt;&quot;;
<br />&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&lt;tr&nbsp;class=&quot;days&quot;&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Mo&lt;/td&gt;&lt;td&gt;Tu&lt;/td&gt;&lt;td&gt;We&lt;/td&gt;&lt;td&gt;Th&lt;/td&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;echo&nbsp;'&nbsp;&nbsp;&lt;td&gt;Fr&lt;/td&gt;&lt;td&gt;Sa&lt;/td&gt;&lt;td&gt;Su&lt;/td&gt;&lt;/tr&gt;';<br />
&nbsp;&nbsp;&nbsp;&nbsp;//&nbsp;Покажем первую строку календаря<br />
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
&nbsp;&nbsp;&nbsp;&nbsp;// Получим количество полных недель текущего месяца<br />
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
&nbsp;&nbsp;&nbsp;&nbsp;//Покажем последние дни месяца<br />
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
<a href="#step8" onclick="closeblock('step8')">Скрыть >></a>
</div>
<p>Источник: <a href="http://www.phptoys.com/" target="_blank">http://www.phptoys.com/</a></p>
<p>Скачать исходники  можно <a href="../../../articles/php/calendar/simplecalendar.zip" target="_blank">здесь.</a></p>
</body>
</html>
