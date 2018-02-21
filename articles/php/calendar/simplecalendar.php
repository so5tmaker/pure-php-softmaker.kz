<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title></title>
<link href="style/style.css" rel="stylesheet" type="text/css" />

</head>

<body>

<?php
function showCalendar()
{
        $day = 1;
        $month = date("m");
        $year = date("Y");
        $hour = date("H");
        $min = date("i");
        $sec = date("s");
        $DayName = date("l");
        // Получим информацию о днях 
		// Нам нужен текущий, первый и последний день месяца
		$today = getdate();
        $firstDay=getdate(mktime(0,0,0,$today['mon'],1,$today['year']));
        $lastDay=getdate(mktime(0,0,0,$today['mon']+1,0,$today['year']));
        $monthCur = date("m");
        $yearCur = date("Y");
        $curDay = date("d");
        $DayNameCur = date("l");
        $currentTimeStamp = strtotime("$yearCur-$monthCur-$day $hour:$min:$sec" );
        $monthName = date("F", $currentTimeStamp);
        $numDays = date("t", $currentTimeStamp);
        
        $numHours = date("H", $currentTimeStamp);
        $numMin = date("i", $currentTimeStamp);
        $DayNameD = date("l", $currentTimeStamp);
        // Нужно определить с какого дня  недели первое число месяца
		$DayNameNum = date("w", $currentTimeStamp);
		// w - день недели, числовой, т.е. от "0" (Sunday) до "6" (Saturday)
        if ($DayNameNum==0){$DayNameNum=7;}
        // Эта переменная поможет определить с какого числа начать отсчет второй недели
        $StartDay = 7 - $DayNameNum + 1;
        // Создадим таблицу с заголовком
		echo '<table>';
		echo '  <tr ><th colspan="7";>'.$today['month']." - ".$today['year']."</th></tr>";
		echo '<tr>';
		echo '  <td>Mo</td><td>Tu</td><td>We</td><td>Th</td>';
		echo '  <td>Fr</td><td>Sa</td><td>Su</td></tr>';
		
		// Покажем первую строку календаря
		echo '<tr>';
        $actday=0;
        for($i=1;$i<=7;$i++)
        {
          if ($i == $curDay ){ $class = ' class="cur_td"';}
          else{ $class = ''; }
          if ($DayNameNum <= $i){
              if ($DayNameNum==$i)
              {
                 $actday++;
              }
              if ($actday!==0)
			  {
				  echo "<td$class>$actday</td>"; 
				  $actday++;
			  }
          }
          else {
                echo "<td$class>&nbsp;</td>";
          }
        }
		echo '</tr>';
		$actday = $StartDay;
    // Получим количество полных недель текущего месяца
    $fullWeeks=floor(($numDays-$actday)/7);

    for($i=0;$i<$fullWeeks;$i++){

        for($j=0;$j<7;$j++){
            $actday++;
            if($actday == $today['mday']) {$class = ' class="cur_td"';}//class="actday"
            else{
            $class = '';
            }
            echo"<td$class>$actday</td>";
        }
        echo'</tr>';
    }
    //Покажем последние дни месяца
    if($actday < $lastDay['mday']){
        echo '<tr>';
        for($i=0;$i<7;$i++){

            $actday++;

            if($actday==$today['mday']){$class=' class="cur_td"';
            }else{
                $class = '';
                if($actday <= $lastDay['mday']){echo"<td$class>$actday</td>";
                }else{echo'<td>&nbsp;</td>';}
            }
        }
        echo'</tr>';
        echo "</table>";
    }
}
showCalendar();

?>

</body>

</html> 


