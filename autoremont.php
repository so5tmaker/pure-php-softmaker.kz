<? 
require_once 'blocks/global.inc.php';

$n=0;
$title = "������ �����������";
include_once ("header.html");
 
AdvTopPosition();
        ?>
   
        <h1 class='post_title2'>������ �����������.</h1>
        <p align="center" class='post_comment'>
        ������ ���������� (����������, �������, ����), ������������ ����,
����������� ������� �������� ��������, ������ ���,
����������� �������. ������������ ������ ���������.<br><br>
������! �����������! ��������!<br>
        <br>�������� � ������: ���. +7 705 105 98 64</p>
        <br>

<?

//������� ������������� ������ ���������� �����
show_social_buttons();

//������� ������� ����� �������� �� ������ �� �����
show_form_subscribe_by_mail();

include_once ("blocks/centraltd.php");

include_once ("footer.html");?>
