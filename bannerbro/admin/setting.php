<?php 
include 'up.php'; 
require_once "try_function.php";
require_once "function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[0]!="2"){ header("Location:no.php");}

	if (isset($_POST['backup'])){backup_database_tables($db_host,$db_log,$db_pass,$db_base, '*');}

	if (isset($_POST['EditPass'])){EditPass();}
	if (isset($_POST['SaleStop'])){SaleStop('NO');}
	if (isset($_POST['SaleStart'])){SaleStop('YES');}
	if (isset($_POST['EditBan'])){EditBan($_POST['fatban']);}
	if (isset($_POST['Delban'])){Delban();}
	if (isset($_POST['EditMethod'])){EditMethod();}
	if (isset($_POST['GoBackUp'])){go_backup();}
	if (isset($_POST['Edit_Show_Orders'])){EditShowOrders();}
	

connectToDB();
$ShowSetting=mysql_query("SELECT Sale,fatban,last_backup,Method,Show_Orders FROM Admin", $link) or die (mysql_error($link));
$data = mysql_fetch_array($ShowSetting);
$cf = count_files('../img');
$backich = count_files('database_backup');
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="../css/def.css"/>

<title>Общие настройки</title>
</head>
<body>

<div class="wrapper">

    <?php	
        if (isset($_GET['set'])) {
            if ($_GET['set']=='backup'){
                echo '
                <div class="queue ta-c"><div class="green message">Копия базы данных успешно сохранена в папке <b>database_backup</b>!</div><br>
                <a href="/bannerbro/admin/setting.php">Вернуться к настройкам</a></div>';
                exit;
            }
			
            if ($_GET['set']=='backupbad'){
                echo '
                <div class="queue ta-c"><div class="yellow message">Копия базы данных НЕ создана. Нет доступа к перезаписи файла.<br> Пожалуйста установите на <b>database_backup</b> и файл <b>backup.sql</b>(если он есть) права 777 и повторите попытку!</div><br>
                <a href="/bannerbro/admin/setting.php">Вернуться к настройкам</a></div>';
                exit;
            }			
			
            if ($_GET['set']=='so'){
                echo '
                <div class="queue ta-c"><div class="green message">Настройки сохранены!</div><br>
                <a href="/bannerbro/admin/setting.php">Вернуться к настройкам</a></div>';
                exit;
            }			
        }

        if (isset($_GET['yes'])) {
            if ($_GET['yes']=='1'){
				echo'
				<div class="queue ta-c"><div class="green message">Пароль изменен успешно!</div><br>
				<a href="setting.php">Назад</a></div>'; 
                exit;
            }
        }

        if (isset($_GET['bad'])) {
            if ($_GET['bad']=='1'){
				echo'
				<div class="queue ta-c"><div class="red message">Неверный пароль!</div><br>
				<a href="setting.php">Назад</a></div>';
                exit;
            }
        }		
    ?>

 <?php include 'menu.php'; ?>

    <div class="content">
	
<fieldset>
    <legend>Общие настройки</legend>
    <div class="settings_container">
        <form method="post">
            <table class="order_table">
                <tr>
                    <th colspan="2">Метод реализации скрипта</th>
                </tr>
                <tr>
                     <td>Новый заказ -> Модерация -> Оплата -> Показ</td>
                    <td><input type="radio" name="Method" value="0" <?php if ($data['Method']==0) echo 'checked'; ?> ></td>
	
                </tr>				
                <tr>	
                    <td>Новый заказ -> Оплата -> Модерация -> Показ</td>
                    <td><input type="radio" name="Method" value="1" <?php if ($data['Method']==1) echo 'checked'; ?>>   <span style="color:green; font-size:12px;">*Рекомендуется</span></td>
	
                </tr>
                <tr>	
                    <td>Новый заказ -> Оплата -> Показ</td>
                    <td><input type="radio" name="Method" value="2" <?php if ($data['Method']==2) echo 'checked'; ?>></td>

                </tr>				

                <tr>
                    <td class="ta-l" colspan="2"><input type="submit" name="EditMethod" value="Принять"></td>
                </tr>
            </table>
        </form>		
		
        <form method="post">
            <table class="order_table">
                <tr>
                    <th colspan="2">Смена пароля</th>
                </tr>
                <tr>
                    <td>Старый пароль</td>
                    <td><input type="text" name="oldpassword" value="" required></td>
                </tr>
                <tr>
                    <td>Новый пароль</td>
                    <td><input type="text" name="newpassword" value="" title="Только латинские буквы и цифры" pattern="^[a-zA-Z0-9]+$" required></td>
                </tr>
                <tr>
                    <td class="ta-l" colspan="2"><input type="submit" name="EditPass" value="Принять"></td>
                </tr>
            </table>
        </form>
		
		<form method="post" class="settings_item" action="setting.php">
            <table class="order_table">
                <tr>
                    <th colspan="2">Продажи</th>
                </tr>
                <tr>
				<?php
						if ($data['Sale']=='YES'){
						echo '
						<td>В настоящий момент продажи:<br> <span class="bold" style="color: green;">активны</span></td>
						<td><input type="submit" name="SaleStop" value="Остановить продажи"><br></td>';
						}else{
						echo '
						<td>В настоящий момент продажи:<br> <span class="bold" style="color: red;">закрыты</span></td>
						<td><input type="submit" name="SaleStart" value="Возобновить продажи"><br></td>';
						}
				?>			
                </tr>
            </table>
        </form>
		
        <form method="post" class="settings_item">
            <table class="order_table">
                <tr>
                    <th colspan="2">Баннеры</th>
                </tr>
                <tr>
                    <td>Max размер загружаемого баннера:</td>
					<?php echo '<td> '.$data['fatban'].' килобайт.</td>'; ?>

                </tr>
                <tr>
                    <td>Изменить размер загружаемого баннера:</td>
                    <td>
                        <input type="text" style="width: 40px;" name="fatban" value="" pattern="^[ 0-9]+$" required> Кб.&nbsp;
                        <input type="submit" name="EditBan" value="Принять">
                    </td>
                </tr>
		</form>	
		<form method="post" class="settings_item">	
                <tr>
					<?php echo '<td>Всего загруженных баннеров:<br><span class="bold" style="color: green;">'.$cf.'</span></td>'; ?>
                    <td><input type="submit" name="Delban" value="Удалить неиспользуемые баннеры" <?php if ($cf==0) echo 'disabled';?>></td>
                </tr>
            </table>
        </form>
		
		
        <form method="post" class="settings_item">
            <table class="order_table">
                <tr>
                    <th colspan="2">База данных</th> 
                </tr>
                <tr>
                    <td>Последний BackUp базы данных:<br><span style="color:blue"><?php if ($data['last_backup']==null){echo 'никогда';}else{ echo $data['last_backup']; }?></span></td>
                    <td colspan="2"><input name="backup" type="submit" value="Сделать backup"> <a data-lightbox="on" href="#backwindow"><input name="backdown" type="submit" value="Восстановить базу" <?php if ($backich<=1) echo 'disabled';?>></a></td>
                </tr>
            </table>
        </form>
		
		<form method="post" class="settings_item">	
            <table class="order_table">		
                <tr>
                    <th colspan="2">Заказы</th> 
                </tr>
				<tr>
                    <td>Показывать по <select name="Show_Orders"><option <?php if ($data['Show_Orders']==10) echo 'selected'; ?>>10</option><option <?php if ($data['Show_Orders']==30) echo 'selected'; ?>>30</option><option <?php if ($data['Show_Orders']==50) echo 'selected'; ?>>50</option></select></td>
                </tr>
                <tr>
                    <td class="ta-l" colspan="2"><input type="submit" name="Edit_Show_Orders" value="Принять"></td>
                </tr>				
            </table>
        </form>
		
        <table class="order_table settings_item" style="padding-top:20px;">
            <tr>
                <th>Настройки стилей</th>
            </tr>
		</table>	
			<?php echo '<div style="margin-top:10px ;margin-left:60px;"><div class="DefaultText"><a href="http://'.$_SERVER['HTTP_HOST'].'/bannerbro" target="_blank"><div class="DefaultImg" style=" width:468px; height:60px;"><div style="word-wrap: break-word;">Купить здесь баннер</div></div></a></div></div>'; ?>
         <table class="order_table settings_item">          
		   <tr>
                <td>Вы можете изменить стиль ракми, для этого откройте файл bannerbro/css/<strong>def.css</strong> и отредактируйте class DefaultImg и DefaultText как вам угодно.</td>
            </tr>			
		</table>
                   
                   

     
    </div>
</div>
</div>

<!-- Восстановление БД -->

<div style="display: none;">
    <div class="popup" id="backwindow">
        <div class="popup_header ta-l">
            <span style="color:red">Восстановление базы данных</span>
            <div class="clear"></div>
        </div>
        <div class="popup_container">
		При восстановлении из резервной копии - абсолютно все предыдущие данные будут уничтожены и заменены резервной копией!
            <h3 style="color:red;margin-top:10px;">Вы уверены что хотите восстановить резервную копию?</h3>
            <form method="post">
                <div class="submit_form">
                    <input type="submit" name="GoBackUp" value="Да, уверен!"/>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>