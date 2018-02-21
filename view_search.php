<? 
require_once 'blocks/global.inc.php';
require_once 'classes/Search.class.php';

$endings = "��|��|�|��|��|��|���|��|���|��|��|��|���|�|����|��|���|��|�|�|���|��|�|����|��|��|�|���|�|�|��|��|";
$endings_arr = explode("|",$endings);

$i_count = 0; $mat_ids  = array();

function echo_no_match()
{
    echo "<p align=center>".get_foreign_equivalent("���������� �� ������ ������� �� �������.")."</p>";
}

function get_cat($id, $db)
{
    $res_kol = mysql_query ("SELECT cat FROM data WHERE id=".$id,$db);
    $sum_kol = mysql_fetch_array($res_kol);
    if ($sum_kol[0]<>0)
    {   
        return $sum_kol[0];
    } else {
        echo_no_records();
    }
}

function check_id($id, $faq, $db)
{
    
    $result = mysql_query ("SELECT id, faq FROM searchers WHERE (id=".$id.") AND (faq=".$faq.")",$db);
    
    echo_error($result);

    if (mysql_num_rows($result) > 0)
    {   
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function get_post($id, $db)
{
    $res_kol = mysql_query ("SELECT id,cat,mini_img FROM data WHERE id=".$id,$db);
    $sum_kol = mysql_fetch_assoc($res_kol);
    return $sum_kol;
}

function DeleteEndings($word) { //��� �� ������������ ���� �����
    global $endings, $lang;
    if (strtolower($lang) != 'ru'){return $word;} 
    $reg = "/(".$endings.")$/i"; //������ ���������� �������� ����� ������ ���������� ���������
    $word = preg_replace($reg,'',$word); //������� ���������
    return $word;
}

function stopWords($query) { //��� �� ������������ ���� ��������� ������
//    $reg = "/\s(���|�����|���|�����|���|���|�������|�������|���|�����|���)\s/im"; //������ ��������� ������� ��� ����-����� ������� ���������
//    $query = preg_replace($reg,'',$query); //������� ���������
    return $query;
}

function explodeQueryX($query) { 	//������� ������ ��������� ������
    global $endings_arr;
    $query = stopWords($query); 	//���������� ���������� ���� ����� ������� ��� �������� ����-����
    $words = explode(" ",$query); 	//��������� ��������� ������ �� ����� ����� ������ � ������� ��� ����� � ������
    $keywords = "";$keywords_str = "";			//������� ������ ������
    foreach ($words as $word) { 	//� ����� ��� ������� words ������� ������� word
        $j=0;
        $word = trim($word);
//            if (mb_strlen ($word, 'UTF-8')<3) {
        if (strlen($word)<3) {	//���� ����� ������ 3 �������� �� ������� ���
                unset($word);
        }
        else {			//����� ��������� ���������
            if (strlen($word)>2) {
                $dropBackWord = DeleteEndings($word); //���� ������� ������ ��������� ��� ���� ������ 8 �������� � ��������� �� � ��������� ���� ������
                if ($j == 0) {
                    if ($word != $dropBackWord) {
                        $keywords_str=$keywords_str." ".$dropBackWord;
                    }
                    $keywords_str=$keywords_str." ".$word;
                }
                $j++;
                foreach ($endings_arr as $ending){
                    if ($word == $dropBackWord.$ending) {
                        continue;
                    }
                    $keywords_str=$keywords_str." ".$dropBackWord.$ending;	//���� ������� ������ ��������� ��� ���� ������ 8 �������� � ��������� �� � ��������� ���� ������
//                    $keywords_str=$keywords_str." ".$ending;
                }
            }
            else {
                $keywords_str=$keywords_str." ".$word;//���� ������ 2 �������� �� ������ ��������� � ������
            }
        }
    }
    $keywords = explode(" ",trim($keywords_str));
    return $keywords; //���������� ���������� ������
}

function explodeQuery($query) { 	//������� ������ ��������� ������
    global $endings_arr;
    $query = stopWords($query); 	//���������� ���������� ���� ����� ������� ��� �������� ����-����
    $words = explode(" ",$query); 	//��������� ��������� ������ �� ����� ����� ������ � ������� ��� ����� � ������
    $keywords = "";$keywords_str = "";			//������� ������ ������
    foreach ($words as $word) { 	//� ����� ��� ������� words ������� ������� word
        $word = trim($word);
//            if (mb_strlen ($word, 'UTF-8')<3) {
        if (strlen($word)<3) {	//���� ����� ������ 3 �������� �� ������� ���
                unset($word);
        }
        else {			//����� ��������� ���������
            if (strlen($word)>2) {
                $dropBackWord = DeleteEndings($word); //���� ������� ������ ��������� ��� ���� ������ 8 �������� � ��������� �� � ��������� ���� ������
                $keywords_str=$keywords_str." ".$dropBackWord;
            }
            else {
                $keywords_str=$keywords_str." ".$word;//���� ������ 2 �������� �� ������ ��������� � ������
            }
        }
    }
    $keywords = explode(" ",trim($keywords_str));
    return $keywords; //���������� ���������� ������
}

function SearchArticles($materials, $keywords) {
    global $db, $search;

    $was = 0;
    $i = 0;
    $matres = mysql_query ("SELECT * FROM searchers ORDER BY relevation DESC",$db);
    
    echo_error($matres);

    if (mysql_num_rows($matres) > 0)
    {   
        while($material = mysql_fetch_assoc($matres)){
            
            $title = $material[title];
//            $title = htmlspecialchars(strip_tags($material[title]));	//��� �� ������ ��� �������� ������� - title, text � keywords �� ����� � ����������� ��������
    //        $text = htmlspecialchars(strip_tags($material[text]));		//��� ������� ����� ��� ��� ����� ��������� � ������ �������
            $text = $material[text];
            if($material["faq"]==0) 
            {
                $description = $material[description];
            }
            $key = htmlspecialchars(strip_tags($material[keywords]));
            $wordWeight =0; //��� ����� ������� ������������ � 0
            $relevation = 0;
            $keywords_ = explodeQuery($search);
            foreach ($keywords_ as $word) {//������ ��� ������� ���������� ����� �� ������� ���� ���������� � ������
                $word_l = strtolower($word);
                $dropBackWord = DeleteEndings($word_l);
                $reg = "/(".$word_l.")/"; 	//����� ������ ��� ���������� �������
                /* 	
                ������������� ���������� ��� ����� ��� ������� �������� �������.
                ��� �� ���� ����� �������� �������� ���� description ���� ��� � ��� ����.
                ��������� ���������� $out, ������� ������� �������� ������. ��� ��� ����� � �� ����������, �� ����� �����, ����� ���� �� ������� �� ����������.
                */
                if ($word_l == $dropBackWord) {
                    $wordWeight = preg_match_all($reg, strtolower($title), $out);	//��� ������� ����� ��� ��� ���� � ��������� ��� ����������� � ��� ����
                    $wordWeight += preg_match_all($reg, strtolower($text), $out);	//�� ��� ��� ������������ ���� �� ������ �������� ��������� � ������� �������� �� �������������
    //                  $wordWeight = substr_count(strtolower($title), $word_l); 
    //                  $wordWeight += substr_count(strtolower($text), $word_l);
    //                if($material["faq"]==0) 
    //                {
                    $wordWeight += preg_match_all($reg, strtolower($description), $out);
    //                  $wordWeight += substr_count(strtolower($description), $word_l);
    //                }
        //            $wordWeight += preg_match_all($reg, $key, $out);	//�� �� ���� ����� ������ �� �����
                    $material[relevation] += $wordWeight;//����������� ��� ����� ��������� �� ��� ���������� �����
                    //������������ ��������� ����� ��������, ������� �� ������ � ������ ����� ����� #ffec82

                    $title = MarkAll($word_l, $title, "yellow");
                    $text = MarkAll($word_l, $text, "yellow");
                    $description = MarkAll($word_l, $description, "yellow");
                }
    //            $key = colorSearchWord($word, $key, "yellow"); //������ ����� �������� ����� �������, �� ���� �� ����������� �������� ������������ :)
            }

            //������ ���� �� ���������, � ������� ��������� ������� relevation �� ����� 0
            if($material[relevation]!=0) {
                $was = 1;
                //���������� ������� � ���������� ��������� � ��� ������������� �������
                $material[title] = $title;
                $material[text] = $text;
                $material[keywords] = $key;
                $material[description] = $description;

                     //������� ����� ������ ������
                    $newSearch = new Search($material);
                    //��������� ����� ����� � ��
                    $newSearch->Save();
            }
            //����� ������ ������� ���� ������� material �� �������������
            else {
                unset($material);
            }
        }
    }
    if ($was == 0) {echo echo_no_match();}
    return $material; 
}

function MarkAll($word, $string, $color) {
    global $endings_arr, $search_arr;

    if (stristr ($string, $word) == FALSE) { // ���� �� ������� ����� ��� ���������, ������ �� ����� ������
        return $string;
    }
//  ������� ���������� ����� ����� � �����������
    foreach ($search_arr as $search){
        if (stristr($search, $word) == FALSE) {
            continue;
        }
        $pattern = "/\b".$search."\b/i";
        $replacement = "<mark>".$search."</mark>";
        $string = preg_replace($pattern, $replacement, $string);
        if (stristr($string, $replacement) == FALSE) {
            $pattern = $search;
            $replacement = "<mark>".$search."</mark>";
            $string = eregi_replace($pattern, $replacement, $string);
        }
    }
//    ������� ����� ��� ��������� � ���������� ���
    if (in_array($word, $search_arr))
    {
        $pattern = "/\b".$word."\b/i";
        $replacement = "<mark>".$word."</mark>";
        $string = preg_replace($pattern, $replacement, $string);
        if (stristr($string, $replacement) == FALSE) {
            $pattern = $word;
            $replacement = "<mark>".$word."</mark>";
            $string = eregi_replace($pattern, $replacement, $string);
        }
    }
    // ��������� � ����� ��� ��������� ��� ��������� � ����������
    foreach ($endings_arr as $ending){
        $pattern = "/\b".$word.$ending."\b/i";
        $replacement = "<mark>".$word.$ending."</mark>";
//        $replacement = "<span style='background-color: ".$color.";'>".$word.$ending."</span>";
        $string = preg_replace($pattern, $replacement, $string); 
        if (stristr($string, $replacement) == FALSE) {
            $pattern = $word.$ending;
            $replacement = "<mark>".$word.$ending."</mark>";
            $string = eregi_replace($pattern, $replacement, $string);
        }
    }
    return $string;
}

function get_material($keywords, $column, $db, $tbl = "faq")
{
    global $lang, $i_count, $mat_ids, $search;
    if ($tbl != "faq") {
        $query_str = "SELECT id,cat,text,mini_img,title,description FROM ".$tbl." WHERE (lang ='".$lang."') AND ";
//    }elseif ($tbl == "files") {
//        $query_str = "SELECT id,cat,text,mini_img,title,description FROM ".$tbl." WHERE (lang='ru' or lang='') AND ";
    }else{
        $query_str = "SELECT * FROM ".$tbl." WHERE (post IN (SELECT id FROM data WHERE lang='".$lang."')) AND ";
    }
    $keywords_ = explodeQuery($search);
    foreach ($keywords_ as $word) {
        $result1 = mysql_query($query_str."(".$column." LIKE '%$word%')",$db);// 
        
        echo_error($result1);

        if (mysql_num_rows($result1) > 0)
        {
            while($row_ = mysql_fetch_assoc($result1))
            {
                if ($tbl != "faq") {
                    $row = $row_;
                    $row[faq] = 0;
//                    $row[post] = $row_["id"];
                }else{
                    $arr_post = get_post($row_["post"], $db);
                    $row[faq] = $row_["id"]; //$row[post] = $row_["post"];
                    $row[id] = $arr_post["id"]; $row[cat] = $arr_post["cat"];
                    $row[text] = $row_["answer"]; $row[mini_img] = $arr_post["mini_img"];
                    $row[title] = $row_["question"]; $row[description] = $row_["answer"];
                    $row[text] = $row_["answer"];
                }
                $row[tbl] = $tbl;
                if (isset($row)) {
                    $check_id = check_id($row[id], $row[faq], $db);
                    if($check_id) {
                        continue;
                    }
                    //������� ����� ������ ������
                    $newSearch = new Search($row);
                    //��������� ����� ����� � ��
                    $newSearch->Save(TRUE);
                }
            }
        }
    }
    return $materials;
} // function get_material()

 function ViewArticles($keywords, $db){
    SearchArticles($materials, $keywords);
    $matres = mysql_query ("SELECT * FROM searchers WHERE relevation <> 0 ORDER BY relevation DESC",$db);
    
    echo_error($matres);

    if (mysql_num_rows($matres) > 0)
    {   
        while($material = mysql_fetch_assoc($matres))
        {
            $description = $material["description"];
            $faq = $material["faq"];
            $id = $material["id"];
            
            $file = ($material[tbl] == "files") ? "files" : "articles";
            $tbl  = ($material[tbl] == "files") ? "files" : "data";
            $cat = get_fld_by_id($id, $tbl);
            if($material["faq"]!=0) 
            {
                  $description = "<p>".$material["title"]."</p>"; $id = $material["id"]."#".$faq;
            }
            $NumberOfEntries = get_foreign_equivalent("����� ���������");
            $Show = get_foreign_equivalent("��������");
            $Hide = get_foreign_equivalent("������");
            $text = stripslashes($material["text"]);
            print <<<HERE
            <br><table align='center' class='post'>
            <tr>
                <td class='post_title'>
                    <p class='post_name'><img class='mini' align='left' src='$material[mini_img]'><a href='$file.php?cat=$cat&id=$id&lang=$material[lang]' target='_blank' >$material[title]</a></p>
                 </td>
            </tr>
            <tr>
                <td>$description<p class='post_view'><span style='background-color: #ffec82;'>$NumberOfEntries: $material[relevation]</span></p>
                    <p><a href="#step$id$faq" onclick="openblock('step$id$faq')">$Show >></a></p>
                    <div id='step$id$faq' style="display:none" >
                    <p>$text</p>
                    <p><a href="#step$id$faq" onclick="closeblock('step$id$faq')">$Hide >></a></p>
                    </div>
                </td>
            </tr>
            </table>    
HERE;
        }
    }
    else
    {
       echo echo_no_match();
    }
 }
 
if (isset($_POST['submit_s']))
{
$submit_s = $_POST['submit_s'];
}

if (isset($_POST['search']))
{
$search = $_POST['search'];
}

if (isset($submit_s))
{
    if (empty($search) or strlen($search) < 3)
    {
        exit ("<p>".get_foreign_equivalent("��������� ������ �� ������, ���� �� ����� 3-� ��������.")."</p>");
    }
    $search = trim($search);
    $search = stripslashes($search); // ����������� �� / ����� ���������
    $search = htmlspecialchars($search); // ����������� ������������� ������� ������ ������ ����� � ����������� ���������
}
else 
{
    exit("<p>".get_foreign_equivalent("�� ���������� � ����� ��� ����������� ����������.")."</p>");
}
$title = get_foreign_equivalent("�������� ������ ���������")." - ".$search;
$n=0;
require_once ("header.html");
?>
<script type="text/javascript">
<!--
 function openblock(item1) {
	var text=document.getElementById(item1);
	if (text.style.display='none')
	{
		text.style.display='block';
	} 
	else 
	{
		text.style.display='none';	
	}
 }
 function closeblock(item1) {
	var text=document.getElementById(item1);	
	text.style.display='none';	
 }
 //-->
</script>
<div id="cse-search-results"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 800;
  var googleSearchDomain = "www.google.kz";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
       
<? 
//if (isset($_GET['id']) AND isset($_GET['faq'])){
////    $id = isset($_GET['id']);
////    $faq = isset($_GET['faq']);
//}else{
//
    $search_arr = explode(" ",strtolower($search));
    $keywords = explodeQueryX($search);
    $db1 = new DB();
    $db1->delete("searchers", 'id<>0');

    if ($keywords <> "") 
    {
        echo "<p class='post_title2'>".get_foreign_equivalent("���������� ���")." <strong><em>".$search."</em></strong></p>";
//        $keywords_[0] = strtolower($search);
//        $materials = get_material($keywords_, "title", $db);
        if (isset($materials)){
            ViewArticles($keywords_, $db);
        }else{
            $tbl ="data";
            $result1 = get_material($keywords, "title", $db, $tbl);
            $result2 = get_material($keywords, "text", $db, $tbl);
            
            $tbl ="files";
            $result1 = get_material($keywords, "title", $db, $tbl);
            $result2 = get_material($keywords, "text", $db, $tbl);

            $result1 = get_material($keywords, "question", $db);
            $result2 = get_material($keywords, "answer", $db);

            ViewArticles($keywords, $db);
        }
    }else{echo echo_no_match();}
//}
require_once ("footer.html");
?>
