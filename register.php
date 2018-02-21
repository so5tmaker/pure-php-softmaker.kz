<?php
/* Регистрация пользователей (register.php)
В этой странице у нас две части.
Блок кода PHP в верхней части, и HTML - в нижней.
PHP код загружает global.inc.php.
Он также содержит информацию о проверке формы.
Код HTML помогает нам создать простую форму.
Более детальные объяснения в коде:
 */
//register.php

require_once 'blocks/global.inc.php';

//инициализируем php переменные, которые используются в форме
$username = "";
$password = "";
$password_confirm = "";
$email = "";
$error = "";

//Переменная $_POST в ассоциативном массиве переменных 
//передается текущему скрипту с помощью метода HTTP POST. 
//Значение каждого поля доступно в $_POST используя названия 
//поля в качестве ключа. Прежде всего мы проверяем была 
//ли нажата кнопка "Отправить". Мы проверяем $_POST['submit-form'] 
//с помощью функции isset(), поскольку “submit-form” было название кнопки формы.

//проверить отправлена ли форма
if(isset($_POST['sub_mail'])) {
    //получить переменные $_POST
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];
    $email = trim($_POST['email']);
    $answer = $_POST['answer'];
    $sum1 = $_POST['sum1'];
    $waserror = $_POST['error'];

    if (isset($_POST['cats'])) {
        $cats_array = $_POST['cats'];
        $cats = implode(",", $cats_array);
    } else {
        $cats = "";
    }
    //Как только мы знаем, что кнопка нажата, можем начинать проверку формы. 
    //Мы будем использовать класс UserTools для проверки доступности 
    //имени пользователя. Если такое имя уже существует, 
    //мы тогда ставим $success на false и выводим эту ошибку в $error. 

    //инициализировать переменные для проверки формы
    $success = true;
    $userTools = new UserTools();

    // проверка суммы с картинки
    $resultsum = mysql_query ("SELECT sum FROM comments_setting  WHERE id='$sum1'",$db);
    $myrowsum = mysql_fetch_array($resultsum);

    if ($answer <> $myrowsum["sum"])
    {
        $error .= get_foreign_equivalent("Вы ввели неверную сумму цифр с картинки!")."<br/> \n\r";
        $success = false;
    }
    //проверить правильность заполнения формы
    //проверить не занят ли этот логин
    if($userTools->checkUsernameExists($username))
    {
        $error .= get_foreign_equivalent("Такое имя пользователя уже существует.")."<br/> \n\r";
        $success = false;
    }

    // Проверяет есть ли пользователь с таким адресом электронной почты
    if($userTools->checkEmailExists($email))
    {
        $error .= get_foreign_equivalent("Такой адрес электронной почты уже существует.")."<br/> \n\r";
        $success = false;   
    }

    if (empty($username))
    {
        $error .= get_foreign_equivalent("Вы не ввели имя пользователя, вернитесь назад и заполните все поля.")."<br/> \n\r";
        $success = false;
    }

    // ПРОВЕРИМ правильно ли введен адрес электронной почты
    $is_ok = preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $email);
    if ($is_ok==0)
    {
        $error .= get_foreign_equivalent("Вы ввели неверный адрес электронной почты!")."<br/> \n\r";
        $success = false;
    }

    //Следующий шаг - это проверить совпадают ли пароли,
    //с помощью простого сравнения.
    //
    //проверить совпадение паролей
    if($password != $password_confirm) {
        $error .= get_foreign_equivalent("Пароли не совпадают.")."<br/> \n\r";
        $success = false;
    }

    //проверить не пустой ли пароль
    if(empty ($password)) {
        $error .= get_foreign_equivalent("Вы не ввели пароль!")."<br/> \n\r";
        $success = false;
    }
    $no_one_hour_user = get_foreign_equivalent("Вы не можете регистрировать больше 1 пользователя в час!");
    if (check_submit($_POST, 60, 'register') == 0 AND $waserror =="" AND $waserror !=$no_one_hour_user){
        $error .= $no_one_hour_user."<br/> \n\r";
        $success = false;
    }

    //Если форма прошла проверку, мы перенаправляемся на страницу приветствия
    if($success) {
        //Если форма прошла проверку, мы размещаем информацию в массив $data
        //и используем его для создания нового объекта User.
        //Мы вызовем функцию save() того объекта User с параметрами
        //$isNewUser - true.

        //подготовить информацию для сохранения объекта нового пользователя
        $data['username'] = $username;
        $data['password'] = md5($password); //зашифровать пароль для хранения
        $data['email'] = $email;
        $data['lang'] = strtoupper($lang);
        $data['cats'] = $cats;

        //создать новый объект пользователя
        $newUser = new User($data);

        //сохранить нового пользователя в БД
        $newUser->save(true);

        //Далее мы войдем в систему с помощью функции login()
        //войти

        //редирект на страницу приветствия
        header("Location: welcome.php?error=Registration&id=".$newUser->id);
        exit ('');
    }
}

//Часть с HTML кодом должна быть предельно проста. 
//Когда форма отправляется пользователем загружается 
//register.php и все значения отправляются в переменную $_POST.
//
//Значение каждого поля ввода превращается в переменную 
//($username, $password...). Если форма еще не отправлена, 
//все переменные будут содержать пустую строку. 
//Если форма отправлена, но не прошла проверку, 
//эти переменные будут содержать значения из прошлого отправления, 
//чтобы пользователю не пришлось заново вводить данные.

//Также если форма не прошла проверку, будет показана переменная $error. 
//В этой переменной будут содержаться причины того, 
//почему форма не прошла проверку.


// Здесь нахожу количество записей (картинок) в таблице с картинками и выбираю одну в случайном порядке
$result4b = mysql_query ("SELECT COUNT(*) FROM comments_setting",$db);
$sum = mysql_fetch_array($result4b);
if (!$sum)
{   // выбираю первую, если запрос не прошёл
    $sum1 = 1;
}
else
{ // выбираю одну в случайном порядке
    $sum1 = rand (1, $sum[0]);
}

$resultimg = mysql_query ("SELECT img FROM comments_setting WHERE id='$sum1'",$db);
$myrowimg = mysql_fetch_array($resultimg);

//Далее мы проверяем залогинен ли юзер.
if(isset($_SESSION['logged_in'])) {
    $logon = 1;
}else {
    $logon = 0;
}
// если регистрация и пользователь вошёл, то он не может
// второй раз регистрироваться он уже зарегистрирован
if ($logon == 1){
    //редирект на главную страницу
    header("Location: index.php");
    exit ('');
// если только регистрация
}elseif ($logon == 0){
    $n=0;$text='';$title = get_foreign_equivalent("Регистрация пользователя");
    require_once ("header.html");
    
    //Если форма не отправлена или не прошла проверку, тогда показать форму снова
    echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
    $from4to20 = get_foreign_equivalent("от 4 до 20 символов");
?>

<p class='post_title2'><? echo get_foreign_equivalent("Регистрация на сайте")." ".$softmaker_link; ?> </p>
<p><? echo "<p align=center>".get_foreign_equivalent("Чтобы стать зарегистрированным пользователем, Вам нужно заполнить эту небольшую форму. Регистрация позволит Вам оставлять комментарии к статьям и файлам, а также откроет доступ к скачиванию файлов.")."<p>"; ?>
</p><?$advs->show('top');?><br>
<form action="register.php" method="post">
 <table width="500" border="0" align="center" class="regtable">
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Логин"); ?>:</strong><br>*A-z, 0-9, _, <? echo $from4to20; ?></p></td>
    <td> <input name="username" type="text" id="username" class="forminput" size="40" maxlength="40" value="<?php echo $username; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong>Email:</strong><br>
    </p></td>
    <td> <input name="email" type="text" id="email" class="forminput" size="40" maxlength="40" value="<?php echo $email; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Пароль"); ?>:</strong> <br>*A-z, 0-9, _, <? echo $from4to20; ?></p></td>
    <td> <input name="password" type="password" id="password" class="forminput" size="40" maxlength="40" value="<?php echo $password; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Подтвердите пароль"); ?>:</strong></p></td>
    <td> <input name="password-confirm" type="password" id="password-confirm" class="forminput" size="40" maxlength="40" value="<?php echo $password_confirm; ?>" /></td>
  </tr>
  <? echo get_all_cat_rows(); ?>
  <tr>
    <td colspan="2"><img style='margin: 0px 0pt -13pt 333px;' src="<? echo $myrowimg["img"]; ?>"/></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Введите ответ к картинке"); ?>:</strong></p></td>
    <td><input name="answer" type="text" class="forminput" size="23" maxlength="23" /></td>
  </tr>
  <tr>
    <!--<td>
        <p><input name="avatar" type="checkbox" id="avatar"  value="yes" />
         Загрузить аватар 80x80px
           <br />*Вес аватара не более 8 Kb, формат jpg.
          Можно загрузить  после регистрации.</p></td>-->
     <td>
          <!--<input name="avatarfile" type="file" id="avatarfile" class="forminput" />
          <input type="hidden" name="MAX_FILE_SIZE" value="8192">-->
          <input name="one" type="hidden" value="5" />
          <input name="two" type="hidden" value="10" />
          <input name="sum1" type="hidden" value="<? echo $sum1; ?>">
          <input name="error" type="hidden" value="<? echo $error; ?>" />
     </td>
  </tr>
  <tr><td colspan="2" align="center"><input name="sub_mail" type="submit" id="sub_mail" value="<? echo get_foreign_equivalent("Регистрация"); ?>" class="formbutton2" /></td></tr>
</table>
</form>

<?} 
$advs->show('bottom');
require_once ("footer.html");?>