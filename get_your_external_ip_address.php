<? require_once 'blocks/global.inc.php';
$n=0;$text="";
$title = get_foreign_equivalent("������ ���� ������� ip �����");
$meta_k = 'ip ����� �������� ������ get external ip address';
$meta_d = 'ip ����� ������ ��� �������� ���� ������� get your external ip address';
require_once ("header.html");

AdvTopPosition();

if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
    $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
}else{
    $ip=$_SERVER['REMOTE_ADDR'];
//when not using cloudflare
}

echo "<h1 class='post_title2'>".get_foreign_equivalent("��� ������� ip �����").": $ip</h1>";

//������� ������������� ������ ���������� �����
show_social_buttons();

//������� ������� ����� �������� �� ������ �� �����
show_form_subscribe_by_mail();

require_once ("blocks/centraltd.php");      

require_once ("footer.html");?>
