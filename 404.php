<? require_once 'blocks/global.inc.php';

$title = get_foreign_equivalent("�������� �� �������")."!";
$n=0;
require_once ("header.html");

echo "<h2 class='post_title2'>".$title."</h2>";
echo "<p class='post_comment'>".
get_foreign_equivalent("��������, �� ������, �� ������� �� ������ ��� �����, ������� �� �������, �� ��������� �� �� ���� �������� ����� SoftMaker.kz. ��������, ��� ����������, ������� �� �����, ���� ���������� �� ������ �������� �����, ����������, �������������� �������, ����� ����� ��, ��� �� �����.")."</p>";
$advs->show('top');
//������� ������� ����� �������� �� ������ �� �����
show_form_subscribe_by_mail();

require_once ("blocks/centraltd.php");   
       
require_once ("footer.html"); ?>

