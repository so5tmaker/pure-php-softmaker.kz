<?php require_once "admin/config.php"; include 'admin/try_function.php'; include 'bro.php'; ?>
<?php
$Good=null;
$Err=null;

if (isset($_GET['error'])) { $Err=$_GET['error']; }
if (isset($_GET['good'])) { $Good=$_GET['good']; }

if (!isset($Err) && !isset($Good))
	{ 
		connectToDB();
		step3();			
		mysql_close($link);
	}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow" />

    <link rel="stylesheet" href="css/normalize.css"/>
    <link href="admin/addons/lightbox/css/lightbox.css" type="text/css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css"/>

    <script type="text/javascript" src="js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="js/jquery-ui.multidatespicker.js"></script>
    <script type="text/javascript" src="admin/addons/lightbox/js/lightbox.js"></script>
    <script type="text/javascript" src="js/my.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('a[data-lightbox]').lightbox();
        })
    </script>

    <!--[if lt IE 9]>
        <script src="common/scripts/html5shiv.js"></script>
    <![endif]-->
<title>Оформление заказа ШАГ 3</title>
</head>
<body>
<div class="wrapper">

<div class="steps_container">
        <table>
            <tr>
                <td >Шаг 1</td>
                <td class="border">Шаг 2</td>
                <td class="active">Шаг 3</td>
            </tr>
        </table>
        <div class="clear"></div>
  </div>
<?php 

if ($Good==null && $Err==null && !isset($_GET['stepend_email']))
	{
		echo '
				<div class="queue ta-c"><div class="yellow message">Заказ не сделан, пожалуйста оформите его с самого начала</div><br>
				<a href="index.php">Сделать заказ с начала</a></div>';
		exit;
	} 	

if (isset($Good))
	{ 
		if ($Good=='1')
		{
			echo '<div class="queue ta-c"><div class="green message">Спасибо за ваш заказ! Ждите ответного письма подтверждения на свой Email!</div><br>';
			echo '<a href="show.php?id='.$_GET['id'].'">Состояние вашего заказа</a></div>';
		}
	}else
	{	
		if (isset($Err))
		{ 	
			if ($Err=='1')
				{
					echo '<div class="queue ta-c"><div class="yellow message">Заказ с таким банером уже сделан</div><br>';
					echo '<a href="index.php">Сделать заказ с другим баннером</a></div>';
				}
		}
	}
	

?>
</div>
</div>
<?php include 'copyright.php'; ?>
</body>
</html>