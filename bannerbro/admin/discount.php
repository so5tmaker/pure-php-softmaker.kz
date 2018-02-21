<?php 
include 'up.php'; 
require_once "function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[3]!="1"){ header("Location:index.php");} 


if (isset($_POST['discount_1'])) { $discount_1=$_POST['discount_1']; }
if (isset($_POST['discount_2'])) { $discount_2=$_POST['discount_2']; }
if (isset($_POST['discount_pos'])) { $discount_pos=$_POST['discount_pos']; }

if (isset($_POST['discount_1']) && isset($_POST['discount_2']) && isset($_POST['discount_pos'])){adddiscount($discount_pos,$discount_1,$discount_2);}

if (isset($_GET['nodiscount'])) { $nodiscount=$_GET['nodiscount']; if ($nodiscount!=null){adddiscount($nodiscount,'','');} }

if (isset($_POST['Q_Coupons']) && isset($_POST['discount_coupons'])){coupons_generation($_POST['Q_Coupons'],$_POST['discount_coupons']);}
if (isset($_POST['Re_Coupons'])){SaveUsed();DeletUsed();}
?>

<?php include 'header.php'; ?>

<title>Настройка скидок</title>
</head>
<body>

<div class="wrapper">

	<?php include 'menu.php'; ?>
	<?php $ShowСouponsNumber=mysql_query ("SELECT * FROM Сoupons ORDER BY ID DESC", $link)  or die (mysql_error($link));
	$Str_Count_Coup='скидочных купонов';	
	if (mysql_num_rows($ShowСouponsNumber)=='1'){$Str_Count_Coup='скидочный купон';}
	if (mysql_num_rows($ShowСouponsNumber)=='2'){$Str_Count_Coup='скидочных купона';}
	if (mysql_num_rows($ShowСouponsNumber)=='3'){$Str_Count_Coup='скидочных купона';}
	if (mysql_num_rows($ShowСouponsNumber)=='4'){$Str_Count_Coup='скидочных купона';}
	?>
	<a data-lightbox="on" style="margin: 20px 10px 0 0;" class="blue_link_btn inl-bl fl-r" href="#add_coupons">Скидочный купон</a><span style="margin: 26px 10px 0 0;" class="inl-bl fl-r">У вас <a data-lightbox="on" href="#views_coupons"><?php echo mysql_num_rows($ShowСouponsNumber).'</a> '.$Str_Count_Coup; ?></span>
	<div class="clear"></div>
	<div class="content orders">
<?php
connectToDB();

$ShowDiscount=mysql_query ("SELECT * FROM Setting ORDER BY ID", $link)  or die (mysql_error($link));

echo '
	<table class="order_table" style="width:800px; margin:0 auto; text-align:center;">
	<tr>
		<th>Позиция</th>
		<th style="text-align:center;">Цена</th>
		<th style="text-align:center;">Оплата за</th>
		<th style="width:40%;text-align:center;">Скидка</th>
		<th style="text-align:center;">Редактировать</th>
	</tr>';

while($data = mysql_fetch_array($ShowDiscount))
	{
		echo '<tr>';
		
		echo '<td>'. $data['Positions'] .'</td>';
		
		echo '<td>'. $data['Price'] .'</td>';
		
		echo '<td>'. $data['Paywhot'] .'</td>';
		
		if ($data['discount_1']==null)
			{
				echo '<td>Нет</td>';
			}	else	{
				echo '<td>Скидка '.$data['discount_2'].'% После заказа свыше '.$data['discount_1'].' рублей</td>';
			}
			
		echo '<td><a style="text-decoration:none;" data-lightbox="on" href="#discount"><input class="green_btn" type="submit"  name="edit" value="Сделать скидку" onclick="ShowDiscount(\''.$data['Positions'].'\')"></a>&nbsp;&nbsp;<a style="text-decoration:none;" href="discount.php?nodiscount='.$data['Positions'].'"><input type="submit" value="Убрать скидку" /></a></td>';
				//<a data-lightbox="on" style="margin: 0 10px 20px 0;" class="blue_link_btn inl-bl fl-r" href="#discount">Сделать скидку</a>
		echo '</tr>';
	}
echo '</table>';
?>
	</div>
	
</div>

<!-- Скидки -->
<div style="display: none;">
    <div class="popup" id="discount">
        <div class="popup_header ta-l">
            Скидка
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form method="post">
                <input type="hidden" name="discount_pos" id="discount_pos"/>
                <fieldset>
                    <legend></legend>
                    <table class="order_table">
                        <tr>
                            <td>Скидка <input style="width:100px; text-align:center;" type="text" id="discount_2" name="discount_2" value="" onkeyup="isright(this);" pattern="^[ 0-9]+$" required/> %</td>
                        </tr>
                        <tr>
                            <td>После заказа на сумму свыше <input style="width:100px; text-align:center;" type="text" id="discount_1" name="discount_1" value="" pattern="^[ 0-9]+$" required/> рублей</td>
                        </tr>
                    </table>
                </fieldset>				
                <div class="submit_form">
                    <input type="submit" name="RemoveOrder" value="Принять"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Генерация купонов -->
<div style="display: none;">
    <div class="popup" id="add_coupons">
        <div class="popup_header ta-l">
            Генерация купонов
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form method="post">
                <input type="hidden" name="discount_pos" id="discount_pos"/>
                <fieldset>
                    <legend></legend>
                    <table class="order_table">
                        <tr>
                            <td>Сколько купонов генерировать <select name="Q_Coupons" id='Q_Coupons' required readonly><option>1</option><option>3</option><option>5</option></select></td>
                        </tr>
                        <tr>
                            <td>Скидка для этих купонов <input style="width:50px; text-align:center;" type="text" id="discount_coupons" name="discount_coupons" value="" onkeyup="isright(this);" pattern="^[ 0-9]+$" required/> %</td>
                        </tr>
                    </table>
                </fieldset>				
                <div class="submit_form">
                    <input type="submit" name="Create_New_Coupons" value="Генерировать"/>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Списко купонов -->
<div style="display: none;">
    <div class="popup" id="views_coupons">
        <div class="popup_header ta-l">
			Список купонов
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form method="post">
                <input type="hidden" name="discount_pos" id="discount_pos"/>
                <fieldset>
                    <legend></legend>
					<table class="order_table" style="text-align:center;">
					<?php
					echo '<tr>
							<th>№</th>
							<th>Создан</th>
							<th style="text-align:center;">Код</th>
							<th style="text-align:center;">Скидка</th>
							<th>Используется</th>
							<th>Удалить</th>
						  </tr>';
					$NowUsed='';		
					while($data = mysql_fetch_array($ShowСouponsNumber)){
					if ($data['Used']==0){$Used='';}else{$Used='checked';$NowUsed.="used_".$data['ID'].",";}
					echo '<tr>';
					echo '<td>'.$data['ID'].'</td>';
					echo '<td>'.coupdate($data['W_Time']).'</td>';
					echo '<td><span style="color:green;"><b>'.$data['Code'].'</b></span></td>';
					echo '<td><span style="color:green;margin-left:18px;">'.$data['Discount'].'</span></td>';
					echo '<td><span style="margin-left:38px;"><input onclick="ChoiseUsed(this.id);" type="checkbox" id="used_'.$data['ID'].'" '.$Used.'/></span></td>';
					echo '<td><span style="margin-left:20px;"><input onclick="ChoiseDel(this.id);" type="checkbox" id="delet_'.$data['ID'].'"/></span></td>';
					echo '</tr>';
					}
					?>
					</table>
                </fieldset>						
                <div class="submit_form">
					<input style="display:none;" type="text" id="GoDelet" name="GoDelet">
					<input style="display:none;" type="text" id="GoUsed" name="GoUsed" value="<?php echo $NowUsed ?>">
                    <input type="submit" name="Re_Coupons" value="Сохранить изменения"/>
                </div>
            </form>
        </div>
    </div>
</div>
<?php mysql_close($link); ?>	
<?php include 'footer.php'; ?>