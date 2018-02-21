<?php 
require_once "config.php";
require_once "SendMailSmtpClass.php";
require_once "adm.php";
checkLoggedIn("no");
require_once "try_function.php";
require_once "validate.php";
if (isset($_POST['submit'])){Vname($_POST["login"]); RecPas();}
?>
<?php include 'header.php'; ?>
<title>Восстановление пароля</title>
</head>
<body>
<div class="wrapper">
<div class="content">
<div id="up-form">

<?php 
if (isset($_GET['set'])) {
    echo '
            <div class="queue ta-c"><div class="green message">На ваш Email отправлено письмо!</div><br>
            <a href="newpas.php">Новый пароль</a></div>';
    exit;
    }
	
if (isset($_GET['bad'])) {
    echo '
            <div class="queue ta-c"><div class="red message">Вы ввели неверные данные</div><br>
            <a href="login.php">Повторить вход</a></div>';
    exit;
    }
?>
            <h1>Восстановление пароля</h1>
            <form method="post" action="">
                <table class="order_table">
                    <tr>
                        <td><input type="text" name="login" style="margin-top:30px;" placeholder="Логин" required></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" style="font-size:20px; margin-bottom:10px;" value="Принять"></td>
                    </tr>
                </table>
            </form>

</div>
</div>
</div>

</body>
</html>
