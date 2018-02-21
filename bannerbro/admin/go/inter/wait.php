<?php
require_once "../../validate.php";
$HTTP = $_POST;
foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="/bannerbro/css/style.css"/>
</head>
<body>
<div class="wrapper">	
<?php

	echo '<div class="queue ta-c">
	<div class="yellow message">Ваш платеж обрабатывается системой. Пожалуйста подождите</div><br>';
	
	if (isset($ik_pm_no)){Vint($ik_pm_no); echo '<a href="/bannerbro/show.php?id='.$ik_pm_no.'" target="_blank">Ваша ссылка состояния заказа</a><p>';}
	
	echo '<a href="/bannerbro">Купить баннер в другом месте</a></div>';

?>
<div class="clear"></div>
</div>
</body>
</html>