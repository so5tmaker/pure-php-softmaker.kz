<?php
if (!isset($fromglobal)){
    require_once $DIR.'blocks/global.inc.php';
//    require_once $DIR.'/classes/User.class.php';
//    require_once $DIR.'/classes/UserTools.class.php';
}

require_once $DIR.'/mail/config.php';

function send_mails($letter)
{
    global $lang;
    
    // Выберем пользователей
    $res_usr = mysql_query("SELECT id,username,join_date,active,
            last_visit,last_mail,cats FROM users 
            WHERE (active = '1')AND(lang='".$lang."') AND (DATE_FORMAT(`last_mail`, '%Y-%c-%d') < CURDATE() OR last_mail = '0000-00-00')
            ORDER BY username"); //
    // если количество записей не равно нулю
    if (mysql_num_rows($res_usr)==0)
    {
        return 'stop2';
    }
    $max_mails_here = 100000; $letters_quant=1;
    $myrow_usr = mysql_fetch_array($res_usr);
    do
    {
        if ($max_mails_here<$letters_quant) {
            return 'stop3';
        }
        if (file_exists($DIR.'/admin/stop.txt')) {return 'stop';}
        $tools = new UserTools();
        $user = $tools->get($myrow_usr["id"]);

        $subject = $letter[0];
        $message = $letter[1];
        $email = $user->email;
//      $email = 'softmaker.kz@gmail.com';
        $deep_mail = '../';
        $ret = pearmail('','',$subject,$message,$email,$deep_mail,'notify');
        if ($ret == '')
        {
            $letters_quant = $letters_quant + 1;
            $user->last_mail = date("Y-m-d");
            $user->save();
        } else {
            Return $ret;
        }
    }
    while ($myrow_usr = mysql_fetch_array($res_usr));
    set_stop($DIR.'/admin/');
}
?>
