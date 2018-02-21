<?php 
error_reporting( E_ERROR );
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[2]!="1"){ header("Location:index.php");}
 
if (isset($_POST['AddPositions'])) {$AddPositions=$_POST['AddPositions']; }
if (isset($_POST['EditPositions'])) {$EditPositions=$_POST['EditPositions']; }
if (isset($_POST['RemovePositions'])) { $RemovePositions=$_POST['RemovePositions']; }
if (isset($_POST['NoRemovePositions'])) { $NoRemovePositions=$_POST['NoRemovePositions']; }
if (isset($AddPositions)){AddPositions();}
if (isset($EditPositions)){EditPositions();}
if (isset($RemovePositions)){RemovePositions();}

?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="../css/def.css"/>


<title>Управление позициями</title>
</head>
<body>

<div class="wrapper">

<?php include 'menu.php'; ?>
<a data-lightbox="on" style="margin: 20px 10px 0 0;" class="blue_link_btn inl-bl fl-r" href="#add_position">Добавить новую позицию</a>
<div class="clear"></div>
	<div class="content positions">
<div class="clear"></div>
<?php
$i=0;
connectToDB();

$Showposition=mysql_query ("SELECT * FROM Setting ORDER BY ID", $link)  or die (mysql_error($link));
		
echo '
	<table class="order_table" style="text-align:center;">
	<tr>
		<th>№</th>
		<th>Размер</th>
		<th>Оплата за</th>
		<th style="width:250px;">Описание</th>
		<th>Стоимость</th>
		<th style="width:130px;">Код баннера</th>
		<th>Баннер</th>
		<th>Статус</th>
		<th style="width:130px;">Редактировать</th>
	</tr>
	<script type="text/javascript">
		var jsarpos=[];
		var jsarsizemin=[];
		var jsarsizemax=[];
		var jsarpay=[];
		var jsarabout=[];
		var jsarprice=[];
		var jsarcod=[];
		var jsarused=[];
		var jspic=[];
		var jsurl=[];
		var jsarqueue=[];
		var jsarcode=[];
	</script>';

while($data = mysql_fetch_array($Showposition))
	{
		$Check_Used_Pos = $data['Positions'];
		$CheckUsed=mysql_query ("SELECT * FROM Orders WHERE Position='$Check_Used_Pos' AND (Pay='Оплачено' OR Pay='Ожидает оплаты' OR Pay='Оплачено-Модерация' OR Pay='Ожидает одобрения')", $link)  or die (mysql_error($link));
			if (mysql_num_rows($CheckUsed)!=null)
				{
					mysql_query("UPDATE Setting SET used='1' WHERE ID='$Check_Used_Pos'", $link)  or die (mysql_error($link));
				}
		
		$JSsize=explode("x", $data['Size']);
		
		echo '<tr>';
		
		echo '<td>'. $data['Positions'] . '</td>';
		
		echo '<td>'.$JSsize[0].'x'.$JSsize[1].'</td>';
		
		echo '<td>' . $data['Paywhot'] . '</td>';
		
		if ($data['Place']==0) $Place='<span style="color:green">Неограниченно</span>'; else $Place=$data['Place'];
		
		echo '<td>' . $data['About'] . '<br>Число мест: '. $Place .'</td>';
		
		echo '<td>' . $data['Price'] . '</td>';
		
		echo '<td>' . $data['Cod'] . '</td>';
		
		$UU=$data['udefault'];

		if ($data['bdefault']!=null)
			{ 
				if (getExtension($data['bdefault'])=='swf'){ echo '<td class="picswf" style="text-align:center;"><a data-lightbox="on" href="#show_banner"><img src ="/bannerbro/images/swf.jpg" onclick="showbanner(6,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')"/></a></td>';}
				if (getExtension($data['bdefault'])!='swf'){ echo '<td class="pic" style="text-align:center;"><a data-lightbox="on" href="#show_banner"><img style="width:30px; height:30px;" src="/bannerbro/admin/img/'. $data['bdefault'] .'" onclick="showbanner(5,'.$i.','.$JSsize[0].','.$JSsize[1].','.$i.')"/></a></td>';}
			} else {
			
				if ($data['cdefault']!=null)
					{ 
				echo '<td style="text-align:center;"><span class="ramka">Код</span></td>'; 
					} else {			
				echo '<td style="text-align:center;"><span class="ramka">Рамка</span></td>'; 
					}
			}	

		echo '<td>';
		
		if ($data['Active']==0){ echo '<span style="color:green">Актив...</span>'; } else { echo '<span style="color:red">Остан...</span>'; }
		
		echo '</td>';	
		echo '<td><a data-lightbox="on" class="yellows_link_btn inl-bl" href="#edit_position" onclick="ShowEdit('.$i.')">Редактировать</a><a class="reds_link_btn" data-lightbox="on" href="#remove_position" onclick="ShowDel(2,\''.$i.'\')">Удалить</a></td>';	
		$newcode=trim(Google_Yandex_code($data['cdefault']));
		$newcode=str_replace("\r\n",'',$newcode);
		$newcode=str_replace("\n",'',$newcode);
		$newabout=$data['About'];
		$newabout=str_replace("\r\n",'',$newabout);
		$newabout=str_replace("\n",'',$newabout);		
		echo '</tr>';
		echo '
			<script type="text/javascript">
				jsarcode.push(\''.$newcode.'\');
				jsarprice.push(\''.$data['Price'].'\');
				jsarpay.push(\''.$data['Paywhot'].'\');
				jsarabout.push(\''.$newabout.'\');
				jsarcod.push(\''.$data['Cod'].'\');
				jsarused.push(\''.$data['used'].'\');
				jsarsizemax.push(\''.$JSsize[1].'\');
				jsarsizemin.push(\''.$JSsize[0].'\');
				jspic.push(\''.$data['bdefault'].'\');
				jsurl.push(\''.$data['udefault'].'\');
				jsarqueue.push(\''.$data['Place'].'\');
				jsarpos.push(\''.$data['Positions'].'\');
			</script>';
		$i=$i+1;
	}


echo '</table>';

mysql_close($link);
?>
	</div>
	
</div>	

<!-- Редактировать позицию -->


<div style="display: none;">
    <div class="popup" id="edit_position">
        <div class="popup_header ta-l">
            Редактировать позицию № <label id="Lpos"></label>.
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form enctype="multipart/form-data" method="post">
                <textarea name="Etext" id="Etext" rows="3" cols="55" placeholder="Введите описание позиции" required></textarea>
                <fieldset>
                    <legend>Параметры баннера</legend>
                    <table class="order_table">
                        <tr>
                            <td id="pos_used"><span onclick="ChangeTypePay('Eselect','zapretday_edit','enumber_place')">Выбор оплаты за <select name="Eselect" id='Eselect' required readonly><option>Клики</option><option>Дни</option><option>Показы</option></select></span></td>
                            <td><label><input type="radio" checked="checked" name="Eusedefault" id="Eban" value="ban" onclick="edit_ban()" /> Свой баннер</label> <label><input type="radio" name="Eusedefault" value="ram" id="Eram" onclick="edit_ram()" checked/> Рамка</label> <label><input type="radio" name="Eusedefault" id="Ecode" value="code" onclick="edit_code()"/> Код</label></td>  <!--//////////////-->
                        </tr>
                        <tr>
                            <td>Размер баннера <input name="Emin" id="Emin" type="text" style="width:40px" pattern="^[ 0-9]+$" value="" required/> x <input name="Emax" id="Emax" type="text" style="width:40px" pattern="^[ 0-9]+$" value="" required/></td>
                            <td>Ваш баннер, если место свободно<br><input type="file" name="EMyBanner" id="EMyBanner" required></td>
                        </tr>
                        <tr>
                            <td>Стоимость <input name="Eprice" id="Eprice" type="text" style="width:40px" pattern="\d+(\.\d{0,3})?" value="" title="Минимальная стоимость: 0.001" required/> руб.</td>
                            <td>Ваша ссылка если место свободно<br><input type="url" name="EMyUrl" id="EMyUrl" required></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="zapretday_edit">Количество заказов  <input style="width:40px" type="text" name="enumber_place" id="enumber_place" value="" required> *0 - неограниченно</td>
                        </tr>
				    </table>
                </fieldset>		
				<fieldset>
                    <legend>Дополнительные настройки</legend>	
					<table class="order_table">	
						<tr>
                            <td>Статус позиции - <label><input type="radio" checked="checked" name="EActive" value="0"/> Активна</label> <label><input type="radio" name="EActive" value="1"/> Остановлена</label></td>
                        </tr>
						<tr>
                            <td><textarea name="EMyCode" id="EMyCode" rows="3" cols="55" placeholder="Свой код google adsense" disabled></textarea></td>
                        </tr>						
                    </table>
                </fieldset>
                <div class="submit_form">
                    <input type="submit" name="EditPositions" value="Применить изменения"/>
					<input type="text" name="HLpos" style="visibility:hidden;" id="HLpos"/>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Добавить позицию -->

<div style="display: none;">
    <div class="popup" id="add_position">
        <div class="popup_header ta-l">
            Добавить новую позицию
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form enctype="multipart/form-data" method="post">
                <textarea name="Btext" rows="5" cols="55" placeholder="Введите описание позиции" required></textarea>
                <fieldset>
                    <legend>Параметры баннера</legend>
                    <table class="order_table">
                        <tr>
                            <td onclick="ChangeTypePay('ChangeTypePay','zapretday_new','number_place')">Выбор оплаты за <select name="Bselect" id='ChangeTypePay' required><option>Клики</option><option>Дни</option><option>Показы</option></select></td>
                            <td><label><input type="radio" checked="checked" name="usedefault" onclick="new_ban()" value="ban"/>&nbsp;Свой баннер</label> <label><input type="radio" name="usedefault" onclick="new_ram()" value="ram"/>&nbsp;Рамка</label> <label><input type="radio" name="usedefault" value="code" onclick="new_code()"/> Код</label></td> 
                        </tr>
                        <tr>
                            <td>Размер баннера&nbsp;<input name="Bmin" style="width: 40px;" type="text" pattern="^[ 0-9]+$" value="468" required/>&nbsp;x&nbsp;<input name="Bmax" style="width: 40px;" type="text" pattern="^[ 0-9]+$" value="60" required/></td>
                            <td>Ваш баннер, если место свободно<br><input type="file" name="MyBanner" id="MyBanner" required></td>
                        </tr>
                        <tr>
                            <td>Стоимость <input name="Bprice" style="width:40px" type="text" pattern="\d+(\.\d{0,3})?" value="" title="Минимальная стоимость: 0.001" required/> руб.</td>
                            <td>Ваша ссылка если место свободно<br><input type="url" name="MyUrl" id="MyUrl" required></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="zapretday_new">Количество заказов  <input style="width:40px" type="text" name="number_place" id="number_place" value="0" required> *0 - неограниченно</td>
                        </tr>
                    </table>
                </fieldset>
				<fieldset>
                    <legend>Дополнительные настройки</legend>	
					<table class="order_table">	
						<tr>
                            <td>Статус позиции - <label><input type="radio" checked="checked" name="NActive" value="0"/> Активна</label> <label><input type="radio" name="NActive" value="1"/> Остановлена</label></td>
                        </tr>
						<tr>
                            <td><textarea name="NMyCode" id="NMyCode" rows="3" cols="55" placeholder="Свой код google adsense" disabled></textarea></td>
                        </tr>						
                    </table>
                </fieldset>
                <div class="submit_form">
                    <input type="submit" name="AddPositions" value="Добавить новую позицию"/>
                </div>
				 <p style="font-size: 10px; margin-top: 10px;">*Редактирование выбора оплаты будет невозможно после добавления позиции!</p>
            </form>
        </div>
    </div>
</div>

<!-- Показать баннер -->

<div style="display: none;">
    <div class="popup" id="show_banner">
        <div class="popup_container" style="padding: 0;">
			<div id="showbanner">
				
			</div>
        </div>
    </div>
</div>

<!-- Показать баннер(рамка) -->

<div style="display: none;">
    <div class="popup" id="show_banner_ram">
        <div class="popup_container" style="padding: 0;">
			<div id="showbanner_ram">
				
			</div>
        </div>
    </div>
</div>



<!-- Удалить позицию -->

<div style="display: none;">
    <div class="popup" id="remove_position">
        <div class="popup_header ta-l">
            Удалить позицию
            <div class="clear"></div>
        </div>
        <div class="popup_container">
			<h3>Вы действительно хотите удалить позицию №</h3><div align="center" style="font-size:24px;"><label id="DLpos"></label></div>
            <form method="post">
			
                <input type="hidden" name="DHLpos" id="DHLpos"/>
                <div class="submit_form">
                    <input type="submit" name="RemovePositions" value="Да, я хочу удалить"/>
					<p style="font-size: 10px; margin-top: 10px; color:red;">*Удаленние позиции приведет к удалению всех заказов<br> связанных с этой позицией, вне зависимости от их состояния.</p>
                </div>
            </form>
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>