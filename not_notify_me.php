<? require_once 'blocks/global.inc.php';
require_once 'classes/UserTools.class.php';
$n=0;$text="";$title = get_foreign_equivalent("���������� �� ��������");
require_once ("header.html");

AdvTopPosition();

if (isset($_GET['note'])){$note = $_GET['note']; if ($note == '') {unset($note);}}
if (isset($_GET['uid'])){$uid = $_GET['uid']; if ($uid == '') {unset($uid);}}

$success = 0;
if (isset($note)){
    $tools = new UserTools();
    $user = $tools->get($note);
    if (is_object($user)) {
        if ($uid == $user->hashedPassword){
            $user->cats = '';
            $user->save();
            $success = 1;
        }
    }
}  
if ($success == 0) {
    echo "<h2 class='post_title2'>".get_foreign_equivalent("���������� �� �������� �� �������!")."</h2>";
} else {
    echo "<h2 class='post_title2'>".get_foreign_equivalent("�� ������� ���������� �� ��������!")."</h2>";
}

//������� ������� ����� �������� �� ������ �� �����
show_form_subscribe_by_mail();

require_once ("blocks/centraltd.php"); 

require_once ("footer.html");?>