<? 
//index.php
require_once 'blocks/global.inc.php';

$n=5; if ($lang == 'RU'){$cat_adv = 1;}//$text = $myrow["text"];
$title=get_foreign_equivalent("����� �����");
require_once ("header.html");

show_breadcrumbs(1, '', '.php');

$advs->show('top');

$sec_res = mysql_query("SELECT * FROM sections WHERE lang='".$lang."'",$db);

echo_error($sec_res);
$i = 0; // ���������� ������������ ����� ���������� �����
if (mysql_num_rows($sec_res) > 0)
{
    $sec_row = mysql_fetch_array($sec_res);
    
    do
    {
        $i++; // ���������� ������������ ����� ���������� �����
        $nameoffile = $sec_row[name];
        // �������� ������
        printf ("<ul><li class='liStyle'>
                 <a $open href='$rest_/$nameoffile/'>%s</a>
         ",$sec_row[title]);
        $id=$sec_row["id"];
        $result2 = mysql_query("SELECT * FROM categories WHERE (sec='$id') "
                . "AND turnon=1 AND (lang='".$lang."')",$db);
        if (mysql_num_rows($result2) > 0)
        {

            echo_error($result2);

            $myrow2 = mysql_fetch_array($result2);
            do
            {
                $i++; // ���������� ������������ ����� ���������� �����
                $cat_name = $myrow2[name];
                // �������� ��������� 
                printf ("<ul><li class='liStyle'>
                    <a $open href='$rest_/$nameoffile/$cat_name/'>%s</a>
                 ",$myrow2[title]);
                $id_cat=$myrow2["id"]; 
                
                $sql = "SELECT id,name,cat,title FROM data WHERE cat='$id_cat' AND lang ='".$lang."'"; //  ORDER BY id
                $result3 = mysql_query($sql,$db);
                
                echo_error($result3);
                
                if (mysql_num_rows($result3) > 0)
                {
                    $myrow3 = mysql_fetch_array($result3);
                    do
                    {
                        $i++; // ���������� ������������ ����� ���������� �����
                        $file_name = $myrow3[name];
                        printf ("<ul><li class='liStyle'>
                        <a $open href='$rest_/$nameoffile/$cat_name/$file_name.html'>
                            %s
                        </a>
                        </li></ul>",$myrow3[title]);

                        if ($i == 25){
                            $advs->show('center', $Link);
                        }
                
                    }
                    while ($myrow3 = mysql_fetch_array($result3));
                }
                
                echo '</li></ul>'; // �������� ���������
            }
            while ($myrow2 = mysql_fetch_array($result2));
        }
        echo '</li></ul>'; // �������� ������
    }
    while ($sec_row = mysql_fetch_array($sec_res));
} else {
    echo_no_records();
}
$advs->show('bottom');
require_once ("footer.html");?>
       
  
          
