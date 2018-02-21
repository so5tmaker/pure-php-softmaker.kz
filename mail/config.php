<?php
// Отправлю почту функцией mail()
function mail_function($from, $author, $subject, $message, $to, $username = '')
{
    global $lang, $max_mails;
    $username = ($username == '') ? "info@softmaker.kz" : $username;
//    if (!check_amount_of_mails_by_email(127, $username, $max_mails)) {
//        return 'stop';
//    }
    
    $from = ($from == '') ? $username : $from;
    $author = ($author == '') ? "www.softmaker.kz" : $author;
//    $from = '"'.$author.'" <'.$from.'>';
    $to = ($to == '') ? $username : $to;
    $headers .= "From: $from\r\n";
    if (strtolower($lang) == 'en')
    {
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    }else{
        $headers .= "Content-type: text/html; Charset=windows-1251\r\n";
    }
    if (!mail($to, $subject, $message, $headers))
    {
        $echo = get_foreign_equivalent("<p>Отправка письма не прошла. Напишите об этом администратору info@softmaker.kz. <br> <strong>Код ошибки:</strong></p>");
        $echo = "mail_function: ".$echo." Тема письма: ".$subject;
        ins_error_log($echo);
        return $echo;
    }
    update_amount_of_mails_by_email($username);
    return '';
} // get_address

// Получим учетную запись в виде массива
function get_address()
{
    global $max_mails, $email_quant;
    // Выбираю в случайном порядке,
//    $num = rand(1, $email_quant);
    $num = 1;
//    $username = "$num.softmaker.kz@gmail.com";
    $username = "softmaker.kz@yandex.kz";
    $password = "softmaker".$num."kz";
    $check = check_amount_of_mails_by_email($num, $username, $max_mails, TRUE);
    if ($check == TRUE) {
        return array($username, $password, 0);
    }
    return 'no';
    // если не получается, то подряд
//    $excess = 0;
//    for ($i = 1; $i <= $email_quant; $i++) {
//        $username = "$i.softmaker.kz@gmail.com";
//        $password = "softmaker".$i."kz";
//        $check = check_amount_of_mails_by_email($i, $username, $max_mails, TRUE);
//        if ($check == FALSE) {
//            continue;
//            $excess = 1;
//        } elseif ($check == TRUE) {return array($username, $password, 0);}
//    }
//    if ($excess == 1){ 
//        return 'no';
//    } else {
//        return array($username, $password, 0);
//    }
} // get_address

function pearmail($from, $author, $subject, $message, $to='', $deep_mail='', $mode = '') {
    global $DIR;
//    $address = get_address();
    $address = 'yes';    
    if ($address <> 'no') {
        $cwd = getcwd();
        // Проверяю нужно ли менять текущую директорию
        $chdir = substr($cwd, -4) != 'mail';
        if ($chdir)
        {
            if (!chdir($deep_mail."mail"))
            {
                Return get_foreign_equivalent('Не могу изменить текущий каталог').' - '.$cwd;
            }
        }

        require_once($DIR."/mail/Mail.php");
        require_once($DIR."/mail/mime.php");
        $host = 'smtp3r.cp.idhost.kz';
        $username = 'info@softmaker.kz';
        $password = ']budetinfo';
//        $host = 'ssl://smtp.yandex.kz';
//        $host = "ssl://smtp.gmail.com";
//        $username = "$num.softmaker.kz@gmail.com";
//        $password = "softmaker".$num."kz";
//        $username = $address[0];
//        $password = $address[1];
//        sleep($address[2]); // задержка отправки в секундах
        
        $port = "25";
//        $port = "465";
        $crlf = "\n";

        $author = ($author == '') ? "www.softmaker.kz" : $author;
        
        if ($mode == 'feedback' OR $mode == 'comment') {
            $to = '<softmaker.kz@gmail.com>';
            $from = '"'.$author.'" <'.$username.'>';
        } elseif ($mode == 'resetpassword' OR $mode == 'recomment' OR $mode == 'register' OR $mode == 'notify') {
            $from = '"'.$author.'" <'.$username.'>';
        } 

        $headers = array ('From' => $from,
            'To' => $to,
            'Subject' => $subject
        );

        try {
            // Creating the Mime message
            $mime = new Mail_mime($crlf);

            $mimeparams=array(); // меняем кодировку
            $mimeparams['text_encoding']="windows-1251";
            $mimeparams['text_charset']="windows-1251"; // в теле письма текста
            $mimeparams['html_charset']="windows-1251"; // в теле письма html
            $mimeparams['head_charset']="windows-1251"; // в теме письма 

            // Setting the body of the email
            $mime->setHTMLBody($message);
            $body = $mime->get($mimeparams);
            $headers = $mime->headers($headers); 
            // true - означает, что перезаписываем параметры по умолчанию, напр. Content-Type

            $smtp = Mail::factory('smtp',
                array ('host' => $host,
                    'auth' => true,
                    'username' => $username,
                    'password' => $password,
                    'port' => $port
                )
            );

            $mail = $smtp->send($to, $headers, $body);
            if (PEAR::isError($mail)) {
                $ret = mail_function($from, $author, $subject, $message, $to);
                $err = $mail->getMessage().' '.$username.' '.$ret;
                ins_error_log($err);
                chdir ($cwd);
                if ($ret <> '')
                {
                    return $err;
                } else {return '';}
            }
            else {
                update_amount_of_mails_by_email($username);
                chdir ($cwd);
                return '';
            } 
       } catch (Exception $exc) {
           $ret = mail_function($from, $author, $subject, $message, $to);
           $err = $exc->getTraceAsString().' '.$username." ".$ret;
           ins_error_log($err);
           chdir ($cwd);
           return $err;
       }
    } else {
        return mail_function($from, $author, $subject, $message, $to);
    }
} // pearmail

//******************************** Почта начало ********************************
// Вставим новую запись в таблицу amount_of_mails
function ins_amount_of_mails($num, $email)
{
    global $db;
    $time = strtotime ("now");
    $mail_date = date("Y-m-d");
    $sql = "INSERT INTO amount_of_mails (id,email,mail_date,time,amount) VALUES ($num,'$email','$mail_date',$time, 1)";
    $result = mysql_query($sql,$db);
    if (!$result) 
    {
        echo "<p align='center'>Добавление в таблицу amount_of_mails не прошло!</p>";
        return FALSE;
    }
    return TRUE;
} // ins_amount_of_mails

// Обновим количество писем
function update_amount_of_mails_by_email($email)
{
    global $db;
    $time = strtotime ("now");
    $res = mysql_query("SELECT id, mail_date, amount FROM amount_of_mails WHERE email = '$email'",$db);
    $myrow = mysql_fetch_array($res);
    do
    { 
        $amount = $myrow["amount"] + 1;
        $result = mysql_query ("UPDATE amount_of_mails SET amount='$amount', time='$time' WHERE email = '$email'",$db);
     }
    while ($myrow = mysql_fetch_array($res));
} // update_amount_of_mails_by_email()

// Проверим сколько было отправлено писем, можно отправлять не больше $max_amount
function check_amount_of_mails_by_email($num, $email, $max_amount = 500, $check_time=FALSE)
{
    global $db;
    $today = getdate();
    $time = strtotime("now");
    $res = mysql_query("SELECT * FROM amount_of_mails WHERE email = '$email'",$db);
    if (mysql_num_rows($res) > 0){
        $myrow = mysql_fetch_array($res);
        do
        { 
            $mail_date = date("Y-m-d");
            if ($myrow["mail_date"] == $mail_date){
                if ($myrow["amount"] < $max_amount){
                    if ($check_time){
                        $secundy = 600; // 10 минут
                        $from = $myrow['time'];//время от
                        $to = strtotime("$today[mday] $today[month] $today[year] $today[hours] hours $today[minutes] minutes $today[seconds] seconds");//время до
                        $raznica = ($to - $from); // - секунды
                        if (round($raznica) < $secundy) {
                              return $raznica;
                        }
                    }
                    return TRUE;
                }
                else {return FALSE;}
            }
            else {
                $result = mysql_query ("UPDATE amount_of_mails SET amount=1, mail_date='$mail_date', time='$time' WHERE email = '$email'",$db);
                if (!$result) {
                    $echo = "Обновление amount_of_mails не прошло! Ошибка: ".mysql_error();
                    exit($echo);
                }
                return TRUE;
            }
         }
        while ($myrow = mysql_fetch_array($res));
    } else {
        return ins_amount_of_mails($num, $email);
    }
} // check_amount_of_mails_by_email()
//******************************** Почта конец *********************************

//******************************** Лог ошибок начало ***************************
// Вставим новую запись в таблицу error_log
function ins_error_log($text)
{
    global $db;
    $id = get_id('error_log');
    $datetime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO error_log (id,datetime,text) VALUES ('$id','$datetime','$text')";
    $result = mysql_query($sql,$db);
    if (!$result) 
    {
        echo "<p align='center'>Добавление в таблицу error_log не прошло!</p>";
        return FALSE;
    }
    return TRUE;
} // ins_error_log
//******************************** Лог ошибок конец ****************************

//chdir ($cwd);
?>

</body>
</html>
