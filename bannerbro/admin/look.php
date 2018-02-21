<?php 
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[0]!="2"){ header("Location:no.php");}

connectToDB();
$GetInfoAdmin=mysql_query("SELECT * FROM Admin",$link)  or die (mysql_error($link));
$data_info = mysql_fetch_array($GetInfoAdmin);
if (isset($_POST['Save'])){ SaveLook(); }

$Scrin_info=explode("/", $data_info['Scrin']);
?>

<?php include 'header.php'; ?>

<title>Настройка отображения</title>
</head>
<body>

<div class="wrapper">
<?php include 'menu.php'; ?>

<?php
	if (isset($_GET['good'])) {
			echo '
			<div class="queue ta-c"><div class="green message">Данные успешно сохранены!</div><br>
			<a href="/bannerbro/admin/look.php">Назад</a></div>';
			exit;
		}
	if (isset($_GET['error'])) {
			echo '
			<div class="queue ta-c"><div class="yellow message">Пожалуйста, загрузите скриншот формата JPG,PNG или GIF</div><br>
			<a href="/bannerbro/admin/look.php">Назад</a></div>';
			exit;
		}		
?>

	<div class="content">

        <fieldset>
            <legend>Редактор страницы продаж</legend>
		<div class="settings_container">
		
        <form method="post" ENCTYPE="multipart/form-data">
            <table class="order_table">
                <tr>
                    <th colspan="2">Скриншот сайта</th>
                </tr>
                <tr>
                     <td>Автоматический</td>
                    <td><input type="radio" name="Scrin" onclick="edit_scrin_auto()" value="0" <?php if ($data_info['Scrin']==0) echo 'checked'; ?> ></td>
                </tr>				
                <tr>	
                    <td>Свой скриншот</td>
                    <td><input type="radio" name="Scrin" onclick="edit_scrin()" value="1" <?php if ($Scrin_info[0]==1) echo 'checked'; ?>></td>
                </tr>	
                <tr>
					<td>
					<?php if (isset($Scrin_info[1])) { echo '<span style="color:green;">Установлен</span>';} else  {echo '<span style="color:silver;">Выберите скриншот</span>';}  ?>
                    </td>
					<td> 
					<?php if ($data_info['Scrin']==1) { echo '<input type="file" name="MyScrin" id="MyScrin">'; } else {echo '<input type="file" name="MyScrin" id="MyScrin" disabled>'; } ?> 
					</td>
                </tr><tr><td style="font-size:10px;">Формат: JPG,PNG или GIF</td></tr>
            </table>
			
				<table class="order_table">
					<tr>
						<th>Приветствие</th>
					</tr>
					<tr>
						<td><textarea id="WhatHello" name="WhatHello" rows="6" cols="90"  ><?php echo $data_info['WhatHello']; ?></textarea></td>
					</tr>
				</table>
				
				<table class="order_table">
					<tr>
						<th>Как это работает</th>
					</tr>
					<tr>
						<td><textarea id="WhatWork" name="WhatWork" rows="6" cols="90"  ><?php echo $data_info['WhatWork']; ?></textarea></td>
					</tr>
				</table>
			 
				<table class="order_table settings_item">
					<tr>
						<th>Что запрещено рекламировать</th>
					</tr>
					<tr>
						<td><textarea id="WhatNot" name="WhatNot" rows="6" cols="90"  ><?php echo $data_info['WhatNot']; ?></textarea></td>
					</tr>
				</table>
			 
				<table class="order_table settings_item">
					<tr>
						<th>Политика безопасности</th>
					</tr>
					<tr>
						<td><textarea id="WhatSave" name="WhatSave" rows="6" cols="90"  ><?php echo $data_info['WhatSave']; ?></textarea></td>
					</tr>
				</table>
			 
				<table class="order_table settings_item">
					<tr>
						<th colspan="2">Контактные данные</th>
					</tr>
					<tr>	
						<td>Сайт</td>
						<td><input type="url" style="width:300px; margin-left:3px;" name="WhatSite" value="<?php echo $data_info['WhatSite']; ?>" /></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><input type="email" style="width:300px; margin-left:3px;" name="WhatEmail" value="<?php echo $data_info['WhatEmail']; ?>" /></td>
					</tr>
					<tr>
						<td>Телефон</td>
						<td><input style="width:300px; margin-left:3px;" name="WhatTel" value="<?php echo $data_info['WhatTel']; ?>" /></td>
					</tr>
					<tr>
						<td>WMID</td>
						<td><input style="width:300px; margin-left:3px;" name="WhatWMID" value="<?php echo $data_info['WhatWMID']; ?>" /></td>
					</tr>
					<tr>
						<td>Служба поддержки</td>
						<td><input style="width:300px; margin-left:3px;" name="WhatSup" value="<?php echo $data_info['WhatSup']; ?>" /></td>
					</tr>	
					<tr>
						<td>Адрес</td>
						<td><input style="width:300px; margin-left:3px;" name="WhatAdres" value="<?php echo $data_info['WhatAdres']; ?>" /></td>
					</tr>						
					
				</table>
				
				<table class="order_table settings_item">
					<tr>
						<th colspan="2">Ссылка на значках</th>
					</tr>
					<tr>
						<td>Веб мани</td>
						<td><input type="url" style="width:300px; margin-left:3px;" name="WhatWM" value="<?php echo $data_info['WhatWM']; ?>" /></td>
					</tr>
					<tr>
						<td>Яндекс деньги</td>
						<td><input type="url" style="width:300px; margin-left:3px;" name="WhatYA" value="<?php echo $data_info['WhatYA']; ?>" /></td>
					</tr>			
				</table>				
				 
				<table class="order_table settings_item">
					<tr>
						<td><input class="inl-bl fl-r" name="Save" type="submit" value="Сохранить" style="font-size:20px;"/></td>
					</tr>
				</table>
				
		</form>	
		</div>
		</fieldset>
	</div>


</div>


<?php include 'footer.php'; ?>