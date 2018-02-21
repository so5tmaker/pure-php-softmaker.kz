<?php
// Get values from query string http://www.weberdev.com/get_example-4214.html
$day = $_GET["day"];
$month = $_GET["month"];
$year = $_GET["year"];
$sel = $_GET["sel"];
$what = $_GET["what"];
$field = $_GET["field"];
$form = $_GET["form"];

if($day == "") $day = date("j");

if($month == "") $month = date("m");

if($year == "") $year = date("Y");

if($hour == "") $hour = date("H");

if($min == "") $min = date("i");

if($sec == "") $sec = date("s");

if($DayName == "") $DayName = date("l");


$currentTimeStamp = strtotime("$DayName $year-$month-$day $hour:$min:$sec" );
$monthName = date("F", $currentTimeStamp);
$numDays = date("t", $currentTimeStamp);
$curDay = date("d", $currentTimeStamp);
$numHours = date("H", $currentTimeStamp);
$numMin = date("i", $currentTimeStamp);
$DayNameD = date("l", $currentTimeStamp);
$counter = 0;
/*$numEventsThisMonth = 0;
$hasEvent = false;
$todaysEvents = "";*/
?>
<html>
<head>
<title>Victor's Calendar</title>
<link rel="stylesheet" type="text/css" href="calendar.css">
<script language="javascript">
    function goLastMonth(month,year,form,field)
    {
        // If the month is January, decrement the year.
        if(month == 1)
    {
    --year;
    month = 13;
    }
        document.location.href = 'calendar.php?month='+(month-1)+'&year='+year+'&form='+form+'&field='+field;
    }

    function goNextMonth(month,year,form,field)
    {
        // If the month is December, increment the year.
        if(month == 12)
    {
    ++year;
    month = 0;
    }
        document.location.href = 'calendar.php?month='+(month+1)+'&year='+year+'&form='+form+'&field='+field;
    }

    function sendToForm(val,field,form)
    {
        // Send back the date value to the form caller. #3E3E36
        eval("opener.document." + form + "." + field + ".value='" + val + "'");
        window.close();
    }
</script>
</head>
<body style="margin:10px 10px 10px 10px" >


<table width='220' align="center"  border='0' cellspacing='6' cellpadding='0' >
    
    <table width='220'  border='0' cellspacing='6' cellpadding='0' style="background:url('6.gif') #686552 no-repeat; " >
        <td valign="top" width="220" align="center"><?php echo "<span class='head_hour'>" . $numHours.":".$numMin."</span><br><span class='title'>" . $DayName.", " .$monthName ." ". $curDay . ", " . $year . "</span>"?></td></table>
            <td valign="top" width="220" >
            <div style="width:220px;background:url('3.gif') no-repeat;margin:0;padding:5px 0 0 0;">
                <?$width='25'; //"$year-$month-$day $hour:$min:$sec $DayNameD ".." - ".?>
                   <table width='211'  border='0' cellspacing='6' cellpadding='0' class="body" >
                       <!--<img src="1.gif" border="0"> style="background:url('2.gif') #686552;"-->
                        <td width="<? echo $width1 ?>" >
                        <input type='button' class='button' value='<' onClick='<?php echo "goLastMonth($month,$year,\"$form\",\"$field\")"; ?>'>
                        </td>
                        <td width='150' align="center" >
                        <span class='title'><?php echo $monthName . " " . $year; ?></span><br>
                        </td>
                        <td width="<? echo $width1 ?>" >
                        <input type='button' class='button' value='>' onClick='<?php echo "goNextMonth($month,$year,\"$form\",\"$field\")"; ?>'>
                        </td>
                     </table>
                   <table width='211'  border='0' cellspacing='6' cellpadding='0' class="body">
                        <td class='head' align="center" width="<? echo $width ?>">sun</td>
                        <td class='head' align="center" width="<? echo $width ?>">mon</td>
                        <td class='head' align="center" width="<? echo $width ?>">teu</td>
                        <td class='head' align="center" width="<? echo $width ?>">wed</td>
                        <td class='head' align="center" width="<? echo $width ?>">thu</td>
                        <td class='head' align="center" width="<? echo $width ?>">fri</td>
                        <td class='head' align="center" width="<? echo $width ?>">sat</td>
                   </table>
                   <table width='211'  border='0' cellspacing='8' cellpadding='2' class="body">
                 <?php
                    for($i = 1; $i < $numDays+1; $i++, $counter++)
                    {
                        $timeStamp = strtotime("$year-$month-$i");
                        if($i == 1)
                        {
                        // Workout when the first day of the month is
                        $firstDay = date("w", $timeStamp);

                        for($j = 0; $j < $firstDay; $j++, $counter++)
                        echo "<td> </td>";
                        }

                        if($counter % 7 == 0)
                        echo "</tr><tr>";

                        if(date("w", $timeStamp) == 0)

                        $class = "class='weekend'";
                        else
                        if($i == date("d") && $month == date("m") && $year == date("Y"))
                        $class = "class='today'";
                        else
                        $class = "class='normal'";

                        echo "<td class='tr'  bgcolor='#ffffff' align='center' ><a class='buttonbar' href='#' onclick=\"sendToForm('".sprintf("%02d/%02d/%04d", $month, $i, $year)."','$field','$form');\"><font $class>$i</font></a></td>";
                    }
                ?>
               </table>
           <img src="5.gif" border="0">
          </div>
        </td>
    </table>
</body>
</html>