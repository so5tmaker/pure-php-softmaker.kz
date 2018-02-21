<?php 
require_once "config.php"; 
require_once "session.php"; 
require_once "validate.php";
checkLoggedIn("no"); 
connectToDB();
if (isset($_POST['prosto']))
{ Vname($_POST['prosto']); $check = $_POST['prosto']; 
	if ($check!=null)
	{
		if($_POST['prosto'] != $_SESSION['rand_code'])
		{header('Location:login.php?bad=1'); exit;}else
		{
			if(isset($_POST["submit"])) 
			{
			Vname($_POST["login"]);
			Vname($_POST["password"]);
				if( !($row = checkPass($_POST["login"], md5($_POST["password"]))) ) 
				{
					header('Location:login.php?badlogpas=1'); exit;	 
				} else{
					$Access = $row["Access"];
					cleanMemberSession($_POST["login"],$Access);
				}
			} 
		}
	}
}
?>
<?php include 'header.php'; ?>

<title>Главная страница</title>
</head>
<body>

<div class="wrapper">
<div class="content">
<div id="up-form">


<?php
if (isset($_GET['bad'])){
    echo '
            <div class="queue ta-c"><div class="red message">Защитный код введен не верно!</div><br>
            <a href="login.php">Повторить вход</a></div>';
			
    exit;
}
if (isset($_GET['badlogpas'])){
    echo '	
            <div class="queue ta-c"><div class="red message">Логин или Пароль не найдены!</div><br>
            <a href="login.php">Повторить вход</a></div>';
    exit;
}
?>

            <h1>Вход в систему БаннерБро</h1>
            <form method="post"  action="<?php print $_SERVER["PHP_SELF"]; ?>">
                <table class="order_table">
                    <tr>
                        <td><input type="text" name="login" style="margin-top:10px;" placeholder="Логин" value="<?php print isset($_POST["login"]) ? $_POST["login"] : "" ; ?>" required></td>
                    </tr>
                    <tr>
                        <td><input type="password" name="password" placeholder="Пароль" value="" required></td>
                    </tr>
                    <tr>
                        <td><img src = "prot/captcha.php" /><br><input  placeholder="Защитный код" type = "text" name = "prosto" required/></td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="submit" style="font-size:20px; margin-bottom:10px;" value="Войти"></td>
                    </tr>
                </table>
			 <h4><a href="rec.php">Забыли пароль?</a> Восстановите его!</h4>
            </form>

</div>
</div>
</div>
<?php include 'footer.php'; ?>



 