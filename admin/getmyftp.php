<?php
include ("lock.php");
$fp = fopen("ip.txt", "r"); // ��������� ���� � ������ ������
if ($fp)
{
    while (!feof($fp))
    {
        $mytext = trim(fgets($fp, 999));
        $ftp = "ftp://softmaker:]budets@".$mytext;
//        header("Location: ftp://softmaker:]budets@".$mytext);//ftp://login:password@server/path
        break;
    }
    echo $ftp;
}
else echo "������ ��� �������� �����";
fclose($fp);
?>

