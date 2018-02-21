<?php
    $now=date("g-i-s-n-j-Y");
    list($hour,$minute,$second,$month,$date,$yearbefore)=explode("-",$now);

//    $zonefile=file("templates/timezone");
//    $timezone=$zonefile[0];
    $timezone=6;

    $now=date("l-d-F-Y-n",mktime(($hour+$timezone),$minute,$second,$month,$date,$yearbefore));
    list($day,$date,$month,$yearafter,$monthdigit)=explode("-",$now);

    $first=date("w-t",mktime((0+$timezone),0,0,$monthdigit,1,$yearbefore));
    list($firstday,$sumofdays)=explode("-",$first);
    $lastday=date("w",mktime((0+$timezone),0,0,$monthdigit,$sumofdays,$yearbefore));

    if($firstday){
        $sunday=1+(7-$firstday);
        if($sunday==7) $sunday=0;
    } else {
      $sunday=1;
    }

    $sumofdaysbefore=date("t",mktime((0+$timezone),0,0,($monthdigit-1),1,$yearbefore));
    for($i=0;$i<$firstday;$i++){
        $datelist[$i]=$sumofdaysbefore - $firstday + $i + 1;
        $colorlist[$i]="C0C0C0";
    }

    for($i=$firstday,$d=1;$d<=$sumofdays;$i++,$d++){
        $datelist[$i]=$d;
        if(($d%7)==$sunday) { $colorlist[$i]="FF0000"; }
        else { $colorlist[$i]="000000"; }
        if($d==$date) $colorlist[$i]="00BB00";
    }

    $sumofdatenow=sizeof($datelist);
    for($i=$sumofdatenow,$s=0;$s<(6-$lastday);$i++,$s++){
        $datelist[$i]=$s+1;
        $colorlist[$i]="C0C0C0";
    }

    $week=file("week.htm");
    for($i=0;$i<sizeof($week);$i++){
        $cldrlist.=$week[$i];
    }

    $sumofweek=sizeof($datelist)/7;
    for($i=0;$i<$sumofweek;$i++){
        $idx=$i*7;
        $idx1=$idx+1;
        $idx2=$idx+2;
        $idx3=$idx+3;
        $idx4=$idx+4;
        $idx5=$idx+5;
        $idx6=$idx+6;
        $weeklist=$cldrlist;
        $weeklist=ereg_replace("1X","$datelist[$idx]",$weeklist);
        $weeklist=ereg_replace("1COLOR",$colorlist[$idx],$weeklist);
        $weeklist=ereg_replace("2X","$datelist[$idx1]",$weeklist);
        $weeklist=ereg_replace("2COLOR",$colorlist[$idx+1],$weeklist);
        $weeklist=ereg_replace("3X","$datelist[$idx2]",$weeklist);
        $weeklist=ereg_replace("3COLOR",$colorlist[$idx+2],$weeklist);
        $weeklist=ereg_replace("4X","$datelist[$idx3]",$weeklist);
        $weeklist=ereg_replace("4COLOR",$colorlist[$idx+3],$weeklist);
        $weeklist=ereg_replace("5X","$datelist[$idx4]",$weeklist);
        $weeklist=ereg_replace("5COLOR",$colorlist[$idx+4],$weeklist);
        $weeklist=ereg_replace("6X","$datelist[$idx5]",$weeklist);
        $weeklist=ereg_replace("6COLOR",$colorlist[$idx+5],$weeklist);
        $weeklist=ereg_replace("7X","$datelist[$idx6]",$weeklist);
        $weeklist=ereg_replace("7COLOR",$colorlist[$idx+6],$weeklist);

         $calendar.=$weeklist;
    }

    $template=file("calendar.htm");

    for($i=0;$i<sizeof($template);$i++){
        $line=$template[$i];
        $line=ereg_replace("DAY",$day,$line);
        $line=ereg_replace("DATE",$date,$line);
        $line=ereg_replace("MONTH",$month,$line);
        $line=ereg_replace("YEAR",$yearafter,$line);
        $line=ereg_replace("<!--LIST OF CALENDAR-->",$calendar,$line);
        echo $line;
    }

?> 