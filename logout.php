<?php
//�������� ������ ��� �����. ��� �������� �� ��������� � HTML.
//������� ���� �������� ����� �� ���������� � ������� ������� logout().
//����� ������ ������������ ������������ �� �������.

//logout.php
require_once 'blocks/global.inc.php';
$userTools = new UserTools();
$userTools->logout();
if (strstr($_SERVER['HTTP_REFERER'], 'uid') == FALSE){
    // ������� ���� �� ����
    del_cache($tbl);
    //������� ����, �������� �� ��������
    header("Location: ".$_SERVER['HTTP_REFERER']);
}else{ // �������� ������ ������, ���� ����� ���������, �� index.php
        header("Location: index.php");
}

?>
