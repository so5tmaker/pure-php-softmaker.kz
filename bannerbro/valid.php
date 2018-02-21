<?php include 'header.php'; include 'bro.php'; ?>
<div class="wrapper">
<?php
		if (!isset($_POST['stepend_pos']) && !isset($_GET['set']))
		{
			echo '<div class="queue ta-c"><div class="yellow message">Обнаружен запрещенный символ!</div><br>';
			echo '<a href="javascript:history.back()">Назад</a></div>';
			exit;
		}

?>
<div class="clear"></div>
</div>
</div>
<?php include 'copyright.php'; ?>
</body>
</html>