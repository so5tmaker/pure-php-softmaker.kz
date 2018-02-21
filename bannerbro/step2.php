<?php 
include 'bro.php'; 
		if (isset($_POST['MyShows'])){$MyShows=$_POST['MyShows'];Vintonly($MyShows); if ($MyShows!=null){$MyСhoiсe=$MyShows;$StringСhoiсe='Показов';}		}
		if (isset($_POST['Myclick'])){$MyClick=$_POST['Myclick'];Vintonly($MyClick); if ($MyClick!=null){$MyСhoiсe=$MyClick;$StringСhoiсe='Кликов';}		}
		if (isset($_POST['altField'])){$MyDay=$_POST['altField'];Vdate($MyDay); if ($MyDay!=null){$MyСhoiсe=$MyDay;$StringСhoiсe='Дни';}		}

		if (isset($_POST['priceshows'])){$priceshows=round($_POST['priceshows'],1);Vprice($priceshows); if ($priceshows!=null){$Prise=$priceshows;}		}
		if (isset($_POST['MyCount'])){$priceday=round($_POST['MyCount'],1);Vprice($priceday); if ($priceday!=null){$Prise=$priceday;}		}
		if (isset($_POST['priceclick'])){$priceclick=round($_POST['priceclick'],1);Vprice($priceclick); if ($priceclick!=null){$Prise=$priceclick;}		}

		if (isset($_POST['emailshows'])) { $emailshows=$_POST['emailshows']; Vmail($emailshows); if ($emailshows!=null){$Email=$emailshows;}	}
		if (isset($_POST['emailday'])) {$emailday=$_POST['emailday']; Vmail($emailday);  if ($emailday!=null){$Email=$emailday;}	}
		if (isset($_POST['emailclick'])) { $emailclick=$_POST['emailclick']; Vmail($emailclick); if ($emailclick!=null){$Email=$emailclick;}	}

		if (isset($_POST['nameday'])) { $nameday=$_POST['nameday'];Vname($nameday); if ($nameday!=null){$Name=$nameday;}	}
		if (isset($_POST['nameclick'])) { $nameclick=$_POST['nameclick'];Vname($nameclick); if ($nameclick!=null){$Name=$nameclick;}		}
		if (isset($_POST['nameshows'])) { $nameshows=$_POST['nameshows'];Vname($nameshows); if ($nameshows!=null){$Name=$nameshows;}		}

		if (isset($_POST['MyShowsPosition'])) { $MyShowsPosition=$_POST['MyShowsPosition'];Vint($MyShowsPosition); if ($MyShowsPosition!=null){$Pos=$MyShowsPosition;} }
		if (isset($_POST['MyClickPosition'])) { $MyClickPosition=$_POST['MyClickPosition'];Vint($MyClickPosition); if ($MyClickPosition!=null){$Pos=$MyClickPosition;} }
		if (isset($_POST['MyDayPosition'])) { $MyDayPosition=$_POST['MyDayPosition'];Vint($MyDayPosition); if ($MyDayPosition!=null){$Pos=$MyDayPosition;} }
include 'header.php'; 




?>
<script>
$(function() {
    if ($.browser.msie && $.browser.version.substr(0,1)<7)
    {
      $('.tooltip').mouseover(function(){
            $(this).children('span').show();
          }).mouseout(function(){
            $(this).children('span').hide();
          })
    }
  });
</script> 
<title>Оформление заказа ШАГ 2</title>
</head>
<body>
<div class="wrapper">
<?php
if (!isset($_POST['MyShows']) && !isset($_POST['Myclick']) && !isset($_POST['altField']))
{
	echo '<div class="steps_container">';
	echo '<table>';
	echo '<tr>';
	echo '<td >Шаг 1</td>';
	echo '<td class="active border">Шаг 2</td>';
	echo '<td>Шаг 3</td>';
	echo '</tr>';
	echo '</table>';
	echo '<div class="clear"></div>';
	echo '</div>';
	echo '<div class="queue ta-c"><div class="yellow message">Заказ не сделан!</div><br>';
	echo '<a href="index.php">Назад</a></div>';
	exit;
}
connectToDB();
step2();
mysql_close($link);
?>
<div class="clear"></div>
</div>
</div>
<?php include 'copyright.php'; ?>
</body>
</html>