<?
// ��� ������ �������
$empty_ban = 0;
// ������� ������ �� ������� ���������
$result_cat = mysql_query("SELECT categoryId FROM categories WHERE (id=$cat)",$db);
if (mysql_num_rows($result_cat) > 0)
{
    $myrow_cat = mysql_fetch_array($result_cat);
    $catid = $myrow_cat["categoryId"];
    $result_ban = mysql_query("SELECT * FROM ms_product WHERE (categoryId=$catid)",$db);
    if (mysql_num_rows($result_ban) > 0)
    {
        $myrow_ban = mysql_fetch_array($result_ban);
        do
        {
           $empty_ban += 1; // .$myrow_ban["width_height"]."
           $subs_ban[$empty_ban] = "
                            <a href='".$myrow_ban["href"]."?partner=03456' target='_blank'>
                            <img src='".$myrow_ban["imgsrc"]."' alt='".$myrow_ban["alt"]."'
                             width='80' height='111' hspace='0' border='1'/></a>
                            ";
        }
        while ($myrow_ban = mysql_fetch_array($result_ban));
        // ��� ������ �������
        if ($empty_ban < 7)
            {
                    $rand = $empty_ban;
            }
            else
            {
                    $rand = 7;
            }
        if ($empty_ban !== 0)
        {
            echo "<p align='center'>";
            // � ������ �������� �� ����������� ������� ��������� ��������
            // � ������� ������ �� ���� ������
            if ($empty_ban !== 1)
            {
                    $shfl_ban = array_rand($subs_ban, $rand);
                    for ($x=0; $x<count($shfl_ban); $x++)
                    {
                            $shsubs_ban[] = $subs_ban[$shfl_ban[$x]];
                            echo $shsubs_ban[$x];
                    }
            }elseif ($empty_ban == 1)
            {echo $subs_ban[1];}
            echo "</p>";
        }
    }
}
?>