<?php
require_once "config.php";
require_once "SendMailSmtpClass.php";
require_once "adm.php";
checkLoggedIn("no");
require_once "try_function.php";
require_once "validate.php";
if (isset($_POST['submit']))
	{
		Vname($_POST["login"]);
		Vname($_POST["pass"]);
		Vcode($_POST["cod"]);
		NewPas();
	}
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
            <div class="queue ta-c"><div class="green message">Ура. Вы поменяли пароль!</div><br>
            <a href="login.php">Авторизация</a></div>';
    exit;
}
if (isset($_GET['bad'])) {
    echo '
            <div class="queue ta-c"><div class="red message">Вы ввели неверные данные!</div><br>
			<a href="newpas.php">Эх, еще разок!</a></div>';
    exit;
}
?>

            <h1>Восстановление пароля</h1>
            <form method="post" action="<?php print $_SERVER["PHP_SELF"]; ?>">
                <table class="order_table">
                    <tr>
                        <td><input type="text" name="cod" placeholder="Введите код" required></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="login" placeholder="Логин" required></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="pass" placeholder="Новый пароль" title="Только латинские буквы и цифры" pattern="^[a-zA-Z0-9]+$" required></td>
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
