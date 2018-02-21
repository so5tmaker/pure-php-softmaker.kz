<?php
//welcome.php
require_once 'blocks/global.inc.php';

if (isset($_POST['error'])){$error = $_POST['error']; if ($error == '') {unset($error);}}
if (!isset($error)){if (isset($_GET['error'])){$error = $_GET['error']; if ($error == '') {unset($error);}}}
if (isset($error)){
    $error_set = $_GET['error'];
    $n=0;$text="";$title = get_foreign_equivalent("��������� ������������");
    require_once ("header.html");
}else {
    header("Location: index.php");
    exit ("");
}

if ($error_set == "WrongPassword") {
    echo "<ul class='error'><li>".get_foreign_equivalent("�� ����� ������������ ������. ���������� �����.")."</li></ul>";
}elseif ($error_set == "NotActivated") {
    echo "<ul class='error'><li>".get_foreign_equivalent("�� �� ������������ ���� ������� ������.")."</li></ul>";
    include 'blocks/activationform.php';
}else{

    if (isset($_POST['id'])){$lid = $_POST['id']; if ($lid == '') {unset($lid);}}
    if (!isset($lid)){if (isset($_GET['id'])){$lid = $_GET['id']; if ($lid == '') {unset($lid);}}}

    if (isset($_POST['uid'])){$uid = $_POST['uid']; if ($uid == '') {unset($uid);}}
    if (!isset($uid)){if (isset($_GET['uid'])){$uid = $_GET['uid']; if ($uid == '') {unset($uid);}}}

    if (isset($_POST['CODE'])){$CODE = $_POST['CODE']; if ($CODE == '') {unset($CODE);}}
    if (!isset($CODE)){if (isset($_GET['CODE'])){$CODE = $_GET['CODE']; if ($CODE == '') {unset($CODE);}}}

    if(($error_set == "Registration") && isset($lid)&& !isset($CODE)){?>
        <p align="center"><? echo get_foreign_equivalent("�����������, �� ������������������! <br> ��� ��������� ��� ���������, �� ��������� ���� �����."); ?></p>
        <?php
        
        /* ������� id ������������ */
        $new_id = get_id('users');
        $new_db = new DB();
        $new_data = array(
        "id" => $new_id
        );
        $new_db->update($new_data, 'users', 'id = '.$lid);

        $tools = new UserTools();
        $newUser = $tools->get($new_id);
                
        /* ���������� */
        $to  = $newUser->email;

        /* ����\subject */
        $subject = get_foreign_equivalent("����������� �� �����")." ".$url;
        $site = $url;
        $reg_url = $url.'welcome.php?error=Registration&CODE=05&id='.$newUser->id.'&uid='.$newUser->hashedPassword.'';
        $reg_link = $url.'welcome.php?error=Registration&CODE=05';
        /* ��������� */
        $message = '
        <p>������������!</p>

        <p>�� ����� �� ������:<br>
        <a href="'.$site.'">'.$site.'</a><br>
        ��������� ��������������� ������,<br>
        � ������� ��� ������ ��� ����������� ����� (e-mail).</p>

        <p>��� ���������� ��������������� ����� ���� ������� ��������� ��� ������������:</p>

        ===================================<br>
        ��� ������������ (login): '.$newUser->username.'<br>
        ===================================

        <p>���� �� �� ���������, � ��� ���� ���� � ������ �������������� ��� ���������!</p>

        <p>���� �� ������ �� ������ ������������������ � ������ �� ������:<br>
        <a href="'.$site.'">'.$site.'</a>,<br>
        �� ��� ������� ����������� ���� ����������� � ��� ����� ������������ ���� ������� ������.</p>
        <p>
        ������������� ����������� ������������ ���� ��� � ���������� ��� ��������� ������������ ����� �<br>
        ������ ��� �� ���������������.</p>
        
        <p>����� ������������ ���� ������� ������, ���������� ������� �� ������:<br>
        <a href="'.$reg_url.'">'.$reg_url.'</a><br>
        ��������� ���������� �������������.</p>

        <p>���� ����� �������� ������������ ������� ������ �� �������, ���������� ������� ��� �������.<br>
        �������� �� ������:<br>
        <a href="'.$reg_link.'">'.$reg_link.'</a><br>
        � ������� ��������� ���� ID ������������ � ��� ��������� (�� ������!) � ��������������� ����.</p>

        ===================================<br>
        ID ������������: '.$newUser->id.'<br>
        ��� ���������: '.$newUser->hashedPassword.'<br>
        ===================================

        <p>����� ��������� ������� ������ �� ������� ����� �� ����,<br> 
        ��������� ��������� ���� ��� ������������ (login) � ������.</p>

        <p>���������� �� �����������!</p>
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
        /* ��� �������� HTML-����� �� ������ ���������� ����� Content-type. */
        if (strtolower($lang) == 'en')
        {
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        }else{
            $headers .= "Content-type: text/html; Charset=windows-1251\r\n";
        }
     
        /* �������������� ����� */
        $headers .= "From: www.softmaker.kz <info@softmaker.kz>\r\n";

        $message = (strtolower($lang) == 'ru') ? $message : $messageEN;
        
        require_once 'mail/config.php'; 
        try {
            $ret = pearmail('','',$subject,$message,$to,'','register');
            if ($ret == '')
            {
                include 'blocks/activationform.php';
            } else {
                echo get_foreign_equivalent("<p>�������� ������ �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>");
                echo $ret."<br>";
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        } 
      }elseif (isset($CODE)){
                if (isset($uid) && isset($lid)){
                    // �������������� ��������� ������������ �� ������
                    $tools = new UserTools();
                    $User = $tools->get($lid);
                    if ($User->active <> '1'){
                        if (($User->hashedPassword==$uid) && ($User->id==$lid)){
                            $User->active = '1';
                            $User->save();
                            echo get_foreign_equivalent("<p align=center>�����������, ���� ������� ������ ������� ������������! <br> ������ �� ������ ����� �� ����.</p>");
                        }else{
                            echo get_foreign_equivalent("<ul class=error><li>�� ����� ������������ ID ������������ ��� ��� ���������. ���������� �����.</li></ul>");
                            include 'blocks/activationform.php';
                        }
                    }else {
                        echo get_foreign_equivalent("<p align=center>���� ������� ������ <strong>��� ������</strong> ���������! <br> ������ �� ������ ����� �� ����.</p>");
                    }
                }else {
                    // ���� ��� uid ������������, �� ������� ����� �����������
                    include 'blocks/activationform.php';
                }
      }else{
         $error = get_foreign_equivalent("� ��������� ����������� �� ������. ���������� ��� ���.");
         echo ($error != "") ? "<ul class='error'><li>".$error."</li></ul>" : "";
     }// Registration
  } //$error != ""
 ?>
<?require_once ("footer.html");?>