<?php 

require_once 'config.php';

$error = "";
$no_error = "";
$email = "";
$ok = "";

function check_or_update($id, $cat, $email) {
    global $db;
    $res_mail = mysql_query ("SELECT email FROM comments WHERE post='$id' AND cat='$cat' AND email='$email' AND notify = '0'",$db);
    if (mysql_num_rows($res_mail) !== 0) {
        return mysql_query ("UPDATE comments SET notify='1' WHERE post='$id' AND cat='$cat' AND email='$email'",$db);
    } else {
        return FALSE;
    }
}

function comm_ins($notify,$id,$cat,$author,$textmail,$email,$mailonly = '1') {
    global $db;
    $date = date("Y-m-d");
    $comm_id = get_id('comments');
    $textmail = change_alphabet($textmail);
    return mysql_query ("INSERT INTO `comments` ( `id` , `notify` , `post` , `cat` , `author` , `text` , `date`, `email` , `mailonly`) VALUES ($comm_id,'$notify','$id','$cat','$author','$textmail','$date','$email','$mailonly')",$db);
}

if (isset($_POST['sub_mail']))
{
    $sub_mail = $_POST['sub_mail'];
}

if (isset($_POST['author']))
{
    $author = $_POST['author'];
}

if (isset($_POST['text']))
{
    $textmail = $_POST['text'];
}

if (isset($_POST['pr']))
{
    $pr = $_POST['pr'];
}

if (isset($_POST['id']))
{
    $id = $_POST['id'];
}

if (isset($_POST['sum1']))
{
    $sum1 = $_POST['sum1'];
}

if (isset($_POST['cat']))
{
    $cat = $_POST['cat'];
}

if (isset($_POST['file_name']))
{
    $file_name = $_POST['file_name'];
}

if (isset($_POST['cat_name']))
{
    $cat_name = $_POST['cat_name'];
}

if (isset($_POST['email']))
{
    $email = trim($_POST['email']);
}

if (isset($_POST['notify']))
{
    $notify = '1';
}else{
    $notify = '0';
}

try {
    if (isset($sub_mail)){

        //инициализировать переменные для проверки формы
        $success = true;

        //получить переменные $_POST
        $waserror = $_POST['error'];

        //Проверка email адреса
        function isEmail($email)
        {
            return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i"
                    ,$email));
        } 

        if($email == '')
        {
            $error .= "<li>".get_foreign_equivalent("Вы не ввели адрес электронной почты!")."</li>";
            $success = false;
        }			

        else if(!isEmail($email))
        {
            $error .= "<li>".get_foreign_equivalent("Вы ввели неверный адрес электронной почты!")."</li>";
            $success = false;
        }

        if (isset($author)) {trim($author);   }
        else {$author = "";}

        if (isset($textmail)) {trim($textmail);   }
        else {$textmail = "";}

        if ($filename == 'files'){
            $zam_down = "закачке";
        }  else {
            $zam_down = "заметке";
        }

        // удалить файл из кэша
        //del_cache($filename);

        if (empty($author))
        {
            $error .= "<li>".get_foreign_equivalent("Вы не ввели имя!")."</li>";
            $success = false;
        }

        if (empty($textmail))
        {
            $error .= "<li>".get_foreign_equivalent("Вы не ввели текст комментария!")."</li>";
            $success = false;
        }
        $text_old = stripslashes($textmail);
        $author = mysql_real_escape_string($author);  // мнемонизирую одинарные кавычки "\" - обратным слэшем, 
        $textmail = mysql_real_escape_string($textmail);      //чтобы занести в базу
        //$author = stripslashes($author); // Обезапасить от / перед кавычками
        //$text = stripslashes($text);
        $author = htmlspecialchars($author); // подставляет мнемонические символы вместо знаков тегов и исполняемых выражений
        $textmail = htmlspecialchars($textmail);


        $result = mysql_query ("SELECT sum FROM comments_setting  WHERE id='$sum1'",$db);
        $myrow = mysql_fetch_array($result);
        
        $nomail = FALSE;
        if (isset($_POST['nomail'])){
            $nomail = TRUE;
        }

        if (!$nomail) {
            if (strstr($email, 'wpcomment.ru') == FALSE) {
                if ($pr <> $myrow["sum"])
                {
                    $error .= "<li>".get_foreign_equivalent("Вы ввели неверную сумму цифр с картинки!")."</li>";
                    $success = false;
                }
            }
            $no_one_hour_user = get_foreign_equivalent("Вы не можете добавлять больше 1-го комментария в минуту!");
        } else {
            $no_one_hour_user = get_foreign_equivalent("Вы не можете добавлять больше 1-го адреса в минуту!");
        }

        
        if (check_submit($_POST, 300) == 0 AND $waserror =="" AND $waserror !=$no_one_hour_user){
            $error .= "<li>".$no_one_hour_user."</li>";
            $success = false;
        }

        if ($success) {
            if (!$nomail) {
                $result2 = comm_ins($notify,$id,$cat,$author,$textmail,$email,'0');
                if ($result2) {
    //                $email2 = trim($_POST['email2']);
    //                $author_ = $_POST['author2'];
                    $result3 = mysql_query ("SELECT title FROM data WHERE id='$id'",$db);
                    $myrow3 = mysql_fetch_array ($result3);
                    $post_title = $myrow3["title"];
                    // Отправляю уведомление о комментарии всех, кто на него подписан
                    // если количество записей не равно нулю
                    $result4 = mysql_query ("SELECT author,email FROM comments WHERE post='$id' AND cat='$cat' AND notify = '1' AND email<>'$email' GROUP BY `email`",$db);
                    if (mysql_num_rows($result4) <> 0) {
                        $myrow4 = mysql_fetch_array($result4);
                        do
                        {
                            $email2 = $myrow4[email];
                            $author_= $myrow4[author]; 
                            if(isEmail($email2)) {
                                $ret = send_comment($author_, $email2 ,$text_old, $filename, $cat_name, $file_name, $post_title, $author);
                            }
                        }
                        while ($myrow4 = mysql_fetch_array($result4));
                    }
                    $ok = "<div class='post_div'><p class='post_comment_add'>"
                    .get_foreign_equivalent("Комментарий добавил(а)")
                    .": <strong>$author</strong>
                    <p>".$text_old."</p></div>";
                    echo $ok;

                    /* Оповещаю администратора о комментарии */
                    $headers .= "Content-type:text/html; Charset=windows-1251\r\n";
                    /* дополнительные шапки */
                    $headers .= "From: ".$email." <".$email.">\r\n";

                    $address = "softmaker.kz@gmail.com";//"info@softmaker.kz"
    //                $ref = $url.$section."/".$cat_name."/".$file_name.".html"."#comm";
                    $ref = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."#comm";
                    $subject = "Новый комментарий на блоге - ".$url;
                    $result3 = mysql_query ("SELECT title FROM data WHERE id='$id'",$db);
                    $myrow3 = mysql_fetch_array ($result3);
                    $post_title = $myrow3["title"];
                    $message = "<p>Появился комментарий к ".$zam_down
                            .": <a href='$ref'>".$post_title."</a></p>"
                            ."<p>Комментарий добавил(а): <b>".$author." < $email ></b></p>"
                            ."<p>Текст комментария: <b>".$text_old."</b></p>";
                    $ret = pearmail($email,$author,$subject,$message,'','','comment');
                    $sum1 = ""; $author = ""; $email = ""; $textmail = "";
                }
            } else {
                $check = check_or_update($id, $cat, $email);
                if (!$check){
                    $res_mail = mysql_query ("SELECT email FROM comments WHERE post='$id' AND cat='$cat' AND email='$email' AND mailonly = '1' AND notify = '1'",$db);
                    if (mysql_num_rows($res_mail) == 0) {
                        $result2 = comm_ins($notify,$id,$cat,$author,$textmail,$email);
                        if ($result2){
                            $error = "<li>".get_foreign_equivalent("Вы успешно подписались!")."</li>";
                        } else {
                            $error = "<li>".get_foreign_equivalent("Подписаться не удалось!")."</li>";
                        }
                    } else {
                        $error = "<li>".get_foreign_equivalent("Вы уже подписаны!")."</li>";
                    }
                } else {
                    $error = "<li>".get_foreign_equivalent("Вы успешно подписались!")."</li>";
                }
                $textmail = ''; $author = ''; $email = '';
//        $subscript_ref = $url."mail/subscription-manager.php?email=$email";
//        header("Location: $subscript_ref");
//        exit ('');
            }
        }
    }
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} 
//Начало *********************** Находит картинки ******************************
// Здесь нахожу количество записей (картинок) в таблице с картинками и выбираю одну в случайном порядке
$result4b = mysql_query ("SELECT COUNT(*) FROM comments_setting",$db);
$sum = mysql_fetch_array($result4b);
if (!$sum)
{ // выбираю первую, если запрос не прошёл
    $sum1 = 1;
}
else
{ // выбираю одну в случайном порядке
    $sum1 = rand (1, $sum[0]);
}

$result4 = mysql_query ("SELECT img FROM comments_setting WHERE id='$sum1'",$db);
$myrow4 = mysql_fetch_array($result4);
$img = $deep.$myrow4["img"];
//Конец *********************** Находит картинки *******************************

function send_comment($user, $email, $comment, $section, $cat_name, $file_name, $title, $author){
        global $lang, $url;
        /* получатели */
        $to  = $user." <".$email.">";
//        $address = $email;
        /* тема\subject */
        $subject = get_foreign_equivalent("Комментарий с сайта")." ".$url;
        $ref = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."#comm";
        /* сообщение */
        $subscript_ref = $url."mail/subscription-manager.php?email=$email";
        if (strtolower($lang) == 'ru'){
            $message = "
            Здравствуйте, ".$user."!

            <p>Вам был отправлен комментарий с адреса: <a href='".$url."'>".$url."</a>.</p>
            <p>Автор: $author</p>
            <p>На тему: <a href='$ref'>".$title."</a></p>
            ======================================================================";
            $message = $message."<br>".$comment."<br>";
            
            $message = $message.
            "======================================================================

            <p>Если вы не понимаете, о чем идет речь — просто проигнорируйте это сообщение!</p>

            <p>Не нужно отвечать на это письмо, оно сгенерировано автоматически.</p>

            <p>Если Вам нужно связаться с автором сайта, сделайте это через 
            обратную связь: <a href='".$url."about.php'>".$url."about.php</a></p>
            
            <p>Для управления подпиской, щелкните по следующей ссылке:
            <a href='$subscript_ref'>$subscript_ref</a></p>
            </p>
            
            <p>С наилучшими пожеланиями,</br>
            Администрация сайта <a href='".$url."'>".$url."</a></br>
            --</p>
            ";
        } else {
            $message = "
            <p>Dear ".$user.",

            <p>You were sent a comment from address <a href='".$url."'>".$url."</a>.</p>
            <p>Author: $author</p>
            <p>Theme: <a href='$ref'>".$title."</a></strong></p>
            ======================================================================";
            $message = $message."<br>".$comment."<br>";

            $message = $message.
            "======================================================================

            <p>Ignore this message if you do not understand the letter!</p>
            
            <p>Please do not reply to this message via e-mail.</br>
            This address is automated, unattended, and cannot help with questions or requests.</p>
            
            <p>To manage subscription click the reference below:
            <a href='$subscript_ref'>$subscript_ref</a></p>
            </p>

            <p>If you would like to contact the author of this site, please, 
            use this feedback form: <a href='".$url."about.php'>".$url."about.php</a></p>

            <p>All the best,</br>
            SOFTMAKER team</br>
            --</p>
            ";
        }
        
        /* Для отправки HTML-почты вы можете установить шапку Content-type. */
        $headers .= "Content-type: text/html; Charset=windows-1251\r\n";

        $headers .= "From: www.softmaker.kz <info@softmaker.kz>\r\n";
        /* и теперь отправим из */
        $ret = pearmail('','',$subject,$message,$email,'','recomment');
        if ($ret == '')
        {
            return 1;
        } else {
//            echo str_replace("@", "_", $ret)."<br>";
            echo $ret."<br>";
            return 0;
        }
     } // send_comment
echo ($error != "") ? "<ul class='error'>".$error."</ul>" : "";  
$required = " (".get_foreign_equivalent("обязательно").")";
?>
<a id='last_comm' name='last_comm'></a>
<p class='post_comment'><?php echo get_foreign_equivalent("Добавьте свой комментарий");?>:</p>
<? //require_once 'AdSense/MR_Right.php';?>
<form class="formmail" action="<? echo $_SERVER['REQUEST_URI']."#last_comm"; ?>" method="post" name="form_mail">
    <p><label><?php echo get_foreign_equivalent("Ваше имя").$required;?>: <br> </label>
        <input value="<? echo $author ?>" class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="author" id="author" type="text" size="60" maxlength="60">
    </p>
    <p><label><? echo get_foreign_equivalent("Ваш e-mail").$required; ?>: <br> </label>
        <input value="<? echo $email ?>" class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="email" id="email" type="text" size="60" maxlength="60">
    </p>
    <?
    if ($email_caption != "") 
    {?>
        <p><label>e-mail отправки: <br> </label><input class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="email2" id="email2" type="text" size="30" maxlength="30"></p>
        <p><label>Автор отправки: <br> </label><input class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="author2" id="author2" type="text" size="30" maxlength="30"></p>
    <?
    }
    ?>
    <p><label><?php echo get_foreign_equivalent("Текст комментария");?>: <br> <textarea class="comarea" onblur="this.className='comarea'" onfocus="this.className='comareaact'" name="text" id="text" cols="45" rows="8"><? echo $textmail?></textarea></label></p>
    <p class="sum">
      <?php echo get_foreign_equivalent("Введите сумму чисел с картинки").":";?>
      <div id="img" style="margin-left:5px">
          <img  style='margin-top:5px; align:left' src="<? echo $img; ?>">
      </div>
      <div style='margin-left:10px'>
          <input class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" 
             style='margin-bottom:0px;margin-top:0px;' name="pr" id="pr" type="text" size="8" maxlength="5">
          <? echo "<strong>$required</strong>"; ?>
      </div>
    </p>
    <input name="id" type="hidden" value="<? echo $id_score; ?>">
    <? $sum_value =  (isset($sum1)) ? 'value="'.$sum1.'"' : ""; ?>
    <div id="inp_sum">
        <input <? echo $sum_value ?> name="sum1" type="hidden" >
    </div>
    <p>
        <label>
            <input type="checkbox" name="notify" id="notify" >
            <? echo get_foreign_equivalent("Оповещать о новых комментариях по почте"); ?>
        </label>
    </p>
    <input  name="file_name" type="hidden" value="<? echo $file_name; ?>">
    <input name="cat_name" type="hidden" value="<? echo $cat_name1; ?>">
    <input name="cat" type="hidden" value="<? echo $cat; ?>">
    <!--<input name="sub_mail" type="hidden" value="yes">-->
    <input name="error" type="hidden" value="<? echo $error; ?>" />
    <p><input class="formbutton" name="sub_mail" type="submit" value="<? echo get_foreign_equivalent("Комментировать"); ?>"></p>
</form><br>
<form class="formmail" action="<? echo $_SERVER['REQUEST_URI']."#comm"; ?>" method="post" name="form_mail">
    <input type="hidden" name="nomail" id="nomail" value="1">
    <input type="hidden" name="notify" id="notify" value="1">
    <input value="noname" type="hidden" name="author" id="author">
    <input value="nocomment" type="hidden" name="text" id="text">
    <input name="id" type="hidden" value="<? echo $id_score; ?>">
    <input  name="file_name" type="hidden" value="<? echo $file_name; ?>">
    <input name="cat_name" type="hidden" value="<? echo $cat_name1; ?>">
    <input name="cat" type="hidden" value="<? echo $cat; ?>">
    <input name="error" type="hidden" value="<? echo $error; ?>" />
    <p><label><? echo get_foreign_equivalent("Подписаться без комментирования")." ".
            "e-mail".$required; ?>: </label><br>
        <input value="<? echo $email ?>" class="inputtext1" onblur="this.className='inputtext1'" onfocus="this.className='inputtextact'" name="email" id="email" type="text" size="35" maxlength="35">
        <input class="formbutton" name="sub_mail" type="submit" value="<? echo get_foreign_equivalent("Подписаться"); ?>"><br>
    </p>
</form><br><br><br><br><br>