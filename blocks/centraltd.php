        <?php
        if (isset($page_) AND $filename == 'index') {
            // ������ � ���������� ������� � ������� - ������ | ���������� | 1 | 2 | 3 | 4 | 5 | 6 | 7 | ��������� | ���������
            $text_cat = "<>'0'";
            $num = $notices; // ���������� ��� �������� ���-�� �������� ������ �� ��������
            // ��������� �� URL ������� ��������
            @$page = $page_;
            // ���������� ����� ����� ��������� � ���� ������
            $sql = "SELECT COUNT(*) FROM data INNER join categories on "
            . "data.cat=categories.id WHERE (data.lang='".$lang."') AND "
            . "(data.cat".$text_cat.") AND (categories.turnon=1)";
            $result00 = mysql_query($sql);
            $temp = mysql_fetch_array($result00);
            $posts = $temp[0];
            // ������� ����� ����� �������
            $total = intval((($posts - 1) / $num) + 1);
            // ���������� ������ ��������� ��� ������� ��������
            $page = (intval($page) == 0) ? 1 : intval($page);
            // ���� �������� $page ������ ������� ��� ������������
            // ��������� �� ������ ��������
            // � ���� ������� �������, �� ��������� �� ���������
            if(empty($page) or $page < 0) $page = 1;
            if($page > $total) $page = $total;
            // ��������� ������� � ������ ������
            // ������� �������� ���������
            $start = $page * $num - $num;
            // �������� $num ��������� ������� � ������ $start
        }else{
            $start = 0;
            $num = 5;
        }
        $sql = "SELECT a.id, a.meta_d, a.name, a.cat, a.title, a.text, "
        . "a.description, a.date as mdate, a.view FROM data a  "
        . "INNER join categories on a.cat=categories.id "
        . "WHERE a.lang ='".$lang."' AND categories.turnon=1 "
        . "ORDER BY mdate DESC LIMIT $start, $num";
        $result = mysql_query($sql, $db);
        echo_error($result);
        $i = 0;        
        if (mysql_num_rows($result) > 0){
        $myrow = mysql_fetch_array($result);
        do
        {
            $message = CreateSmartMessage($myrow);
            $i++;   
            if ($i==$gap){
               echo $advs->show('center');
            }
        }while ($myrow = mysql_fetch_array($result));
        }
        
        if ($n==1) { // ������� �� �������
            codbanner(7);  
        } elseif ($n==2) { // ������� � �������
            codbanner(8);
        } elseif ($n==3) {// ������� � ������
            codbanner(9);
        }
//        $advs->show('bottom');
        if (isset($page_) AND $filename == 'index') {
            echo_bookmarks(NULL, $page, $total);
        }
?>
       
  
          
