<?php
//welcome.php
require_once 'blocks/global.inc.php';

if (isset($_POST['error'])){$error = $_POST['error']; if ($error == '') {unset($error);}}
if (!isset($error)){if (isset($_GET['error'])){$error = $_GET['error']; if ($error == '') {unset($error);}}}
if (isset($error)){
    $error_set = $_GET['error'];
    $n=0;$text="";$title = get_foreign_equivalent("Активация пользователя");
    require_once ("header.html");
}else {
    header("Location: index.php");
    exit ("");
}

if ($error_set == "WrongPassword") {
    echo "<ul class='error'><li>".get_foreign_equivalent("Вы ввели неправильный пароль. Попробуйте снова.")."</li></ul>";
}elseif ($error_set == "NotActivated") {
    echo "<ul class='error'><li>".get_foreign_equivalent("Вы не активировали Вашу учетную запись.")."</li></ul>";
    include 'blocks/activationform.php';
}else{

    if (isset($_POST['id'])){$lid = $_POST['id']; if ($lid == '') {unset($lid);}}
    if (!isset($lid)){if (isset($_GET['id'])){$lid = $_GET['id']; if ($lid == '') {unset($lid);}}}

    if (isset($_POST['uid'])){$uid = $_POST['uid']; if ($uid == '') {unset($uid);}}
    if (!isset($uid)){if (isset($_GET['uid'])){$uid = $_GET['uid']; if ($uid == '') {unset($uid);}}}

    if (isset($_POST['CODE'])){$CODE = $_POST['CODE']; if ($CODE == '') {unset($CODE);}}
    if (!isset($CODE)){if (isset($_GET['CODE'])){$CODE = $_GET['CODE']; if ($CODE == '') {unset($CODE);}}}

    if(($error_set == "Registration") && isset($lid)&& !isset($CODE)){?>
        <p align="center"><? echo get_foreign_equivalent("Поздравляем, Вы зарегистрировались! <br> Код активации был отправлен, на указанный Вами адрес."); ?></p>
        <?php
        
        /* изменим id пользователя */
        $new_id = get_id('users');
        $new_db = new DB();
        $new_data = array(
        "id" => $new_id
        );
        $new_db->update($new_data, 'users', 'id = '.$lid);

        $tools = new UserTools();
        $newUser = $tools->get($new_id);
                
        /* получатели */
        $to  = $newUser->email;

        /* тема\subject */
        $subject = get_foreign_equivalent("Регистрация на сайте")." ".$url;
        $site = $url;
        $reg_url = $url.'welcome.php?error=Registration&CODE=05&id='.$newUser->id.'&uid='.$newUser->hashedPassword.'';
        $reg_link = $url.'welcome.php?error=Registration&CODE=05';
        /* сообщение */
        $message = '
        <p>Здравствуйте!</p>

        <p>На сайте по адресу:<br>
        <a href="'.$site.'">'.$site.'</a><br>
        появилась регистрационная запись,<br>
        в которой был указал ваш электронный адрес (e-mail).</p>

        <p>При заполнении регистрационной формы было указано следующее имя пользователя:</p>

        ===================================<br>
        Имя пользователя (login): '.$newUser->username.'<br>
        ===================================

        <p>Если вы не понимаете, о чем идет речь — просто проигнорируйте это сообщение!</p>

        <p>Если же именно вы решили зарегистрироваться в форуме по адресу:<br>
        <a href="'.$site.'">'.$site.'</a>,<br>
        то вам следует подтвердить свою регистрацию и тем самым активировать вашу учетную запись.</p>
        <p>
        Подтверждение регистрации производится один раз и необходимо для повышения безопасности сайта и<br>
        защиты его от злоумышленников.</p>
        
        <p>Чтобы активировать вашу учетную запись, необходимо перейти по ссылке:<br>
        <a href="'.$reg_url.'">'.$reg_url.'</a><br>
        Активация произойдет автоматически.</p>

        <p>Если таким способом активировать учетную запись не удалось, попробуйте сделать это вручную.<br>
        Пройдите по ссылке:<br>
        <a href="'.$reg_link.'">'.$reg_link.'</a><br>
        и введите указанные ниже ID пользователя и код активации (не пароль!) в соответствующие поля.</p>

        ===================================<br>
        ID пользователя: '.$newUser->id.'<br>
        Код активации: '.$newUser->hashedPassword.'<br>
        ===================================

        <p>После активации учетной записи вы сможете войти на сайт,<br> 
        используя выбранные вами имя пользователя (login) и пароль.</p>

        <p>Благодарим за регистрацию!</p>
        --
        ';
        $messageEN = '
        <p>Dear '.$newUser->username.',</p>

        <p>Thank you for registering at the <a href="'.$site.'">'.$site.'</a>.<br>
            
	Before we can activate your account one last step must be taken to complete your registration.</p>

        <p>Please note - you must complete this last step to become a registered member.<br> 
        You will only need to visit this url once to activate your account.</p>

        <p>To complete your registration, please visit this url:
        <a href="'.$reg_url.'">'.$reg_url.'</a></p>

        <p>**** Does The Above URL Not Work? ****<br>
        If the above url does not work, please use your Web browser to go to:<br>
        <a href="'.$reg_link.'">'.$reg_link.'</a></p>

        <p>Please be sure not to add extra spaces.<br> 
        You will need to type in your User ID and Activation Code on the page<br>
        that appears when you visit the url.</p>

        ===================================<br>
        Your User ID is: '.$newUser->id.'<br>
        Your Activation Code is: '.$newUser->hashedPassword.'<br>
        ===================================

        </p>Ignore this message if you have not registered on our website!<p>

        <p>Please do not reply to this message via e-mail.<br> 
        This address is automated, unattended, and cannot help with questions or requests.</p>

        <p>All the best,<br>
        SOFTMAKER.KZ team<br>
        --</p>
        ';
        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        if (strtolower($lang) == 'en')
        {
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        }else{
            $headers .= "Content-type: text/html; Charset=windows-1251\r\n";
        }
     
        /* дополнительные шапки */
        $headers .= "From: www.softmaker.kz <info@softmaker.kz>\r\n";

        $message = (strtolower($lang) == 'ru') ? $message : $messageEN;
        
        require_once 'mail/config.php'; 
        try {
            $ret = pearmail('','',$subject,$message,$to,'','register');
            if ($ret == '')
            {
                include 'blocks/activationform.php';
            } else {
                echo get_foreign_equivalent("<p>Отправка письма не прошла. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>");
                echo $ret."<br>";
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        } 
      }elseif (isset($CODE)){
                if (isset($uid) && isset($lid)){
                    // автоматическая активация пользователя по ссылке
                    $tools = new UserTools();
                    $User = $tools->get($lid);
                    if ($User->active <> '1'){
                        if (($User->hashedPassword==$uid) && ($User->id==$lid)){
                            $User->active = '1';
                            $User->save();
                            echo get_foreign_equivalent("<p align=center>Поздравляем, Ваша учетная запись успешно активирована! <br> Теперь Вы можете войти на сайт.</p>");
                        }else{
                            echo get_foreign_equivalent("<ul class=error><li>Вы ввели неправильный ID пользователя или Код активации. Попробуйте снова.</li></ul>");
                            include 'blocks/activationform.php';
                        }
                    }else {
                        echo get_foreign_equivalent("<p align=center>Ваша учетная запись <strong>уже прошла</strong> активацию! <br> Теперь Вы можете войти на сайт.</p>");
                    }
                }else {
                    // если нет uid пользователя, то выводим форму регистрации
                    include 'blocks/activationform.php';
                }
      }else{
         $error = get_foreign_equivalent("К сожалению регистрация не прошла. Попробуйте ещё раз.");
         echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
     }// Registration
  } //$error != ""
 ?>
<?require_once ("footer.html");?>