<? include ("lock.php");

$subs[] = "sections";
$subs[] = "categories";
$subs[] = "data";
$subs[] = "files";

if (isset($_GET['table'])) {$table = $_GET['table'];}
 
if (isset($table))
{
    $tbl_dt = $table;
    $title_here = "�������� ���������������� ������� ".$tbl_dt;
    include("header.html");
    echo "<h3 align='center'>".$title_here."</h3>";
    $count = 0;
    $result = mysql_query("SELECT title,id,name FROM ".$tbl_dt);
    $myrow = mysql_fetch_array($result);
    do
    {
        $name = translitIt($myrow["title"]);
        /* ����� ����� ��� ����� �������� ���������� � ���� */
        $result1 = mysql_query ("UPDATE ".$tbl_dt." SET name='$name' WHERE id='$myrow[id]'");
        if ($result1 == 'true') {
            echo "<p align='center'>".$myrow["title"]." - ".$name."</p>";
        }else{
            echo "<p align='center'>���������������� ".$myrow["title"]." �� ������!</p>";
        }
    $count = $count + 1;
    }
    while ($myrow = mysql_fetch_array($result));
    print "<p>����� �����: ".$count."</p>";
} else {
    $title_here = "�������� ���������������� ������";
    include("header.html");
    echo "<h3 align='center'>".$title_here."</h3>";
    foreach($subs as $k => $v) {
        echo "<p align=center><a href='translit.php?table=$v'>".$v."</a></p>";;
    }
}
include_once ("footer.html"); ?>  

