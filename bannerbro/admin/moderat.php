<?php 
error_reporting( E_ERROR );
include 'up.php'; 
require_once "try_function.php";

$MyAccess=explode("/", $_SESSION["Access"]);
if ($MyAccess[0]!="2"){ header("Location:no.php");} 


	if (isset($_POST['AddModer'])){AddModer();}
	if (isset($_POST['EditModer'])){EditModer();}
	if (isset($_POST['RemModer'])){RemModer();}

?>

<?php include 'header.php'; ?>
<title>Модераторы</title>
</head>
<body>

<div class="wrapper">

	<?php
        if (isset($_GET['bad'])) {
            if ($_GET['bad']=='1'){
                echo '
                <div class="queue ta-c"><div class="red message">Логин или Email занят!</div><br>
                <a href="moderat.php">Вернуться к настройкам</a></div>';
                exit;
            }
        }
	?>	
		
    <?php include 'menu.php'; ?>

    <div class="content">
        <fieldset>
            <legend>Добавить нового модератора</legend>
            <div class="first_line">
                <form method="post" action="<?php print $_SERVER["PHP_SELF"]; ?>">
                    <table class="order_table">
                        <tr>
                            <td>
                                <table class="order_table">
                                    <tr>
                                        <td><input type="email" name="email" placeholder="Рабочий Email" required/></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="login" value="" placeholder="Логин" title="Только латинские буквы и цифры" pattern="^[a-zA-Z0-9]+$" required></td>
                                    </tr>
                                    <tr>
                                        <td><input type="password" name="password" value="" placeholder="Пароль" title="Только латинские буквы и цифры" pattern="^[a-zA-Z0-9]+$" required></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="AddModer" value="Добавить модератора"></td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <input type="checkbox" name="Accs1" value="1">&nbsp;Одобрять и Отклонять заказы.<br>
                                <input type="checkbox" name="Accs2" value="1">&nbsp;Добавлять новые позиции<br>
                                <input type="checkbox" name="Accs3" value="1">&nbsp;Добавлять и Удалять скидки<br>
                                <input type="checkbox" name="Accs4" value="1">&nbsp;Менять шаблоны писем<br>
                                <input type="checkbox" name="Accs5" value="1">&nbsp;Просмотр покупателей<br>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </fieldset>


	<fieldset>
	<legend>Модераторы</legend>
		<div align="center">
			<?php
			$i=0;
			connectToDB();
			$ShowUsers=mysql_query ("SELECT * FROM Users ORDER BY ID", $link)  or die (mysql_error($link));
				echo '
					<table class="order_table" style="text-align:center;">
					<tr>
						<th style="text-align:center;">Email</th>
						<th style="text-align:center;">Логин</th>
						<th style="text-align:center;">Код прав</th>
						<th style="text-align:center;">Изменить права</th>
					</tr>';

				print "<script type=\"text/javascript\">var jsarpos=[];</script>";

			while($data = mysql_fetch_array($ShowUsers))
				{
					if ($data['Type']!='admin')
						{
							echo '<tr>';
							echo '<td>'. $data['Email'] .'</td>';
							echo '<td>'. $data['Login'] .'</td>';
							echo '<td>'. $data['Access'] .'</td>';
							echo '<td style="width: 15%;"><a data-lightbox="on" class="yellows_link_btn inl-bl" href="#edit_moder" onclick="EditModer(\''.$i.'\')">Редактировать</a><br><a class="reds_link_btn" data-lightbox="on" href="#remove_moder" onclick="ShowDel(3,\''.$i.'\')">Удалить</a></td>';
							echo '</tr>';
						}
					echo '<script type="text/javascript">jsarpos.push(\''.$data['ID'].'\')</script>';
					$i=$i+1;
				}
			echo '</table>';

			mysql_close($link);
			?>
		</div>
	</fieldset>

	</div>
	
</div>

<!-- Удалить модератора -->

<div style="display: none;">
    <div class="popup" id="remove_moder">
        <div class="popup_header ta-l">
            Удалить модератора
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <h3>Вы действительно хотите удалить модератора?</h3>
            <form method="post">
                <input type="hidden" name="HLpos" id="HLpos"/>
                <div class="submit_form">
                    <input type="submit" name="RemModer" value="Да, я хочу удалить"/>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Редактировать модератора -->

<div style="display: none;">
    <div class="popup" id="edit_moder">
        <div class="popup_header ta-l">
            Изменить права модератору
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <form method="post">
                <form method="post">
                    <input type="checkbox" name="Accs1" value="1"> Одобрять и Отклонять заказы.<br>
                    <input type="checkbox" name="Accs2" value="1"> Добавлять новые позиции<br>
                    <input type="checkbox" name="Accs3" value="1"> Добавлять и Удалять скидки<br>
                    <input type="checkbox" name="Accs4" value="1"> Менять шаблоны писем<br>
                    <input type="checkbox" name="Accs5" value="1"> Просмотр покупателей<br>
                    <input type="hidden" name="DHLpos" id="DHLpos"/>
                    <div class="submit_form">
                        <input  type="submit" name="EditModer" value="Изменить"/>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>