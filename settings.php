<?php
//Зарегистрированный пользователь, который вошел в систему
//может захотеть изменить некоторые данные.
//Для простоты примера, мы разрешим пользователям
//менять только электронный адрес.
//Вы же можете попробовать расширить этот функционал.

//Все очень схоже со страницами входа и выхода.
//На этой странице при отправке формы мы
//берем текущего пользователя в качестве объекта
//User и выставляем ему новый емейл.
//Далее просто вызываем функцию save() и все у нас выходит.

require_once 'blocks/global.inc.php';

//взять объект user из сессии
//Далее мы проверяем залогинен ли юзер.
if(isset($_SESSION['logged_in'])) {
    $logon = 1;
    $user = unserialize($_SESSION['user']);
    //инициализировать php переменные, используемые в форме
    $username = $user->username;
    $email = $user->email;
    $password = "";
    $password_confirm = "";
    $message = "";
    $cat_rows = get_cat_rows($user);
}else {
    $logon = 0;
}

//проверить отправлена ли форма
if(isset($_POST['submit-settings']) && ($logon == 1)) {
    //получить переменные $_POST
//    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];
    $email = $_POST['email'];
    $answer = $_POST['answer'];
    $sum1 = $_POST['sum1'];
     
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

    //Если форма прошла проверку, мы перенаправляемся на страницу приветствия
    if($success)
    {
        //изменить настройки пользователя в БД
        $user->hashedPassword = md5($password); //зашифровать пароль для хранения
        $user->email = $email;
        $user->cats = $cats;
        $user->save();
        $message = "<p align='center'>".get_foreign_equivalent("Настройки сохранены!")."</p><br/>";
        // прошло обновление базы, поэтому обновим категории
        $cat_rows = get_cat_rows($user);
    }
}
//Если форма не отправлена или не прошла проверку - показать форму снова

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

if ($logon == 0){
    //редирект на главную страницу
    header("Location: index.php?lang=".$lang);
    exit ('');
// если только регистрация
}elseif ($logon == 1){
    $n=0;$text='';$title = get_foreign_equivalent("Изменение настроек пользователя");
    require_once ("header.html");
    
?>
 <form action="<? echo "settings.php?lang=".$lang; ?>" method="post">
 <table width="500" border="0" align="center" class="regtable">
  <p class='post_title2'><? echo get_foreign_equivalent("Изменение настроек пользователя"); ?> <i><?php echo $username." ".get_foreign_equivalent("на сайте")." ".$softmaker_link; ?></i></p>

  <? 
  $advs->show('top');
  
  echo $message; echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
  ?>
  
  <tr>
    <td><p><strong>Email:</strong><br>
    </p></td>
    <td> <input name="email" type="text" id="email" class="forminput" size="40" maxlength="40" value="<?php echo $email; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Пароль"); ?>:</strong> <br>*A-z, 0-9, _, <? echo get_foreign_equivalent("от 4 до 20 символов"); ?></p></td>
    <td> <input name="password" type="password" id="password" class="forminput" size="40" maxlength="40" value="<?php echo $password; ?>" /></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Подтвердите пароль"); ?>:</strong></p></td>
    <td><input name="password-confirm" type="password" id="password-confirm" class="forminput" size="40" maxlength="40" value="<?php echo $password_confirm; ?>" /></td>
  </tr>
  <? // Вывожу категории для выбора пользователю
  echo $cat_rows; ?>
  <tr>
    <td colspan="2"><img style='margin: 0px 0pt -13pt 333px;;' src="<? echo $myrowimg["img"]; ?>"/></td>
  </tr>
  <tr>
    <td><p><strong><? echo get_foreign_equivalent("Введите ответ к картинке"); ?>:</strong></p></td>
    <td><input name="answer" type="text" class="forminput" size="23" maxlength="23" /></td>
  </tr>
  <tr>
     <td>
          <input name="sum1" type="hidden" value="<? echo $sum1; ?>">
     </td>
  </tr>
  <tr><td colspan="2" align="center"><input name="submit-settings" type="submit" id="submit-settings" value="<? echo get_foreign_equivalent("Сохранить"); ?>" class="formbutton2" /></td></tr>
</table>
</form>
<? } 
$advs->show('bottom');
require_once ("footer.html");?>