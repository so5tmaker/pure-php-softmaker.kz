<?php
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
<div class="queue ta-c">
<div class="green message">Оплата прошла успешно.</div><br>
<a href="/bannerbro/show.php?id=<?php echo $ik_pm_no; ?>" target="_blank">Ваша ссылка состояния заказа</a><p>
<a href="/bannerbro">Купить еще один баннер</a></div>';
<div class="clear"></div>
</div>
</body>
</html>