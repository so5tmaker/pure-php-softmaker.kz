<?php
include ("lock.php");
// �������������� ��� �������� ������ � ������� � Blogger
// ������ ���� � righttd.php:
//<p align="center" class="title">����� � ����</p>
//<div id="coolmenu">
//<a href="CreatePost.php?sec=1">�������� ������</a>
//<a href="CreatePost.php?sec=2">�������� �������</a>
//</div>	
if (isset($_GET['sec'])) {
    $sec = $_GET['sec'];
}  else {
    if (isset($_POST['sec'])) {$sec = $_POST['sec'];}
}
if ($sec == '1') {
    $name_dt = "������"; $filename = 'articles'; $tbl_dt = "data"; $action = "data.php";
    $faq_qry = "AND faq=0";
} elseif ($sec == '2') {
    $name_dt = "�������"; $filename = 'files'; $tbl_dt = "files"; $action = "data.php";
    $faq_qry = "";
} else {
    $name_dt = "������"; $filename = 'articles'; $tbl_dt = "data"; $action = "data.php";
    $faq_qry = "AND faq=0";
}
$faq_qry = "";
$title_here = "�������� ���������� ".$name_dt;
include("header.html");

function CreatePost($myrow) {
  
    global $filename, $sec, $tbl_dt, $db;

    // ������������ ��������� �� ����
    $cat = $myrow[cat];
    $result_cat = mysql_query("SELECT title, name FROM categories WHERE (id=$cat)", $db);
    $myrow_cat = mysql_fetch_array($result_cat);
    $num_rows = mysql_num_rows($result_cat);
    // ���� ���������� ������� �� ����� ����
    if ($num_rows!=0)
    {
        $cat_name = $myrow_cat[name];
        $label = $myrow_cat[title];
    } else { 
        return FALSE;
    }
    if ($cat_name == '0')
    {
        return FALSE;
    }
    $was = FALSE;
    if ($sec !== '2') {
        if ($myrow[faq] == '1') {
            $was = TRUE;
            $post = $myrow[id];
            $result_faq = mysql_query("SELECT answer, question FROM faq WHERE (post=$post)", $db);
            $myrow_faq = mysql_fetch_array($result_faq);
            $num_rows = mysql_num_rows($result_faq);
            // ���� ���������� ������� �� ����� ����
            if ($num_rows!=0)
            {
                if ($myrow["phpcolor"] == '1') {
                    $text = get_highlight_code($myrow_faq[answer]);
                    $text = str_replace("pre>", "p>", $text);
                } else {
                    $text = get_highlight_code($myrow_faq[answer]);    
                }
                $text = "<h4 align=center>".$myrow_faq[question]."</h4>".$text;
                $image = "";
            } else { 
                echo "<p align=center>��������� �� �������! �������� ��� �������� ��� ������� - " . $myrow[title] . '</p>';
                return FALSE;
            }
        }
    } 
    $link = get_other_lang_link($myrow[lang]);
    if (!$was) {
        $matches = null;
        // �������� �� ������ ��� ���� img ������ � ���������� $myrow[text]
        preg_match_all('/<img[^>]+>/i', stripslashes($myrow[text]), $matches);
        $bodytag = $matches[0];
        $bodytag = str_replace("../../", $link, $bodytag);
        $bodytag = str_replace("src", " border='1' src", $bodytag);

        if ($bodytag[0] != null){
            $image = "<p align=center>"
            ."<a href='$link$filename/$cat_name/".$myrow[name].".html'". 
            $open.$bodytag[0]."</a>"
            ."</p>";
        }
        // ������ ������ �������� ����� �� ������
        $image = str_replace("\r\n",'',$image);
        $image = str_replace("\n",'',$image);
        $text = $myrow[description];
    }
       
    $footer = "<p align=center>"
    .get_foreign_equivalent("������ �����")." <a href='$link$filename/$cat_name/".$myrow[name].".html'" 
    .$open
    ."�$myrow[title]�</a>"
    ."</p>";
    $title = "<H1 align=center>".$myrow[meta_d]."</H1>";
    $body1 = $title.$image.$text.$footer;
    echo $body1;
    $body = $image.$text.$footer;
    
    
//    return TRUE;
    // �������� ��������� Zend Gdata 
    require_once 'Zend/Loader.php';
    Zend_Loader::loadClass('Zend_Gdata');
    Zend_Loader::loadClass('Zend_Gdata_Query');
    Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    Zend_Loader::loadClass('Zend_Gdata_Feed');

    // ������� ������� ������ ��� �������������� ClientLogin
    $user = "softmaker.kz@gmail.com";
    $pass = "]budetgoo";

    try {
    // ����������� 
    // ������������� ������� ������
    $client = Zend_Gdata_ClientLogin::getHttpClient(
      $user, $pass, 'blogger');
    $service = new Zend_Gdata($client);

    // �������� id �����
    $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/default/blogs');
    $feed = $service->getFeed($query);

    //����� id ����� �����: tag:blogger.com,1999:user-blogID.blogs
    // � ��� �� ����� ������ � ������� entries ������� ��� ������ ����
    $idText = explode('-', $feed->entries[0]->id->text);
    //    echo $idText[2];
    // ������� �������������� �����
    $id = $idText[2];

    // �������� ������ ������� ������
    // ���������� ��� �������    
    $uri = 'http://www.blogger.com/feeds/' . $id . '/posts/default';
    $entry = $service->newEntry();
    $entry->title = $service->newTitle(iconv('windows-1251', 'UTF-8',$myrow[meta_d]));
    $entry->content = $service->newContent(iconv('windows-1251', 'UTF-8', $body));
    $entry->content->setType('text');

    //zend gdata blogger ������
    $labels = $entry->getCategory();
    $newLabel = $service->newCategory(iconv('windows-1251', 'UTF-8', $label), 'http://www.blogger.com/atom/ns#');
    $labels[] = $newLabel; // Append the new label to the list of labels. 
    $entry->setCategory($labels);
    
    // ��������� ����
    $entry->published = $service->newPublished($myrow[date].'T00:00:00.000-00:00');

    // ���������� ������ �� �������
    // ��������� ����������� �������������� ����� �������
    $response = $service->insertEntry($entry, $uri);
    $arr = explode('-', $response->getId());
    $id = $arr[2];
    echo '<p align=center>��������� ������� ��������� � ID: ' . $id . '</p>';
    
    $query_txt = "UPDATE ".$tbl_dt." SET blog_id='$id' WHERE id='$myrow[id]'";
    $result = mysql_query ($query_txt);
    if ($result == 'true') {
        echo "<p align='center'>���������� ".$name_dt." ������� ���������!</p>";
    }else{
        echo "<p align='center'>���������� ".$name_dt." �� ������!</p>";
    }

    } catch (Exception $e) {
    die('<p align=center>ERROR:' . $e->getMessage() . '</p>');  
    }
}

function PrintList($list)
  {
    echo "<ul>\n";
  
    for($a=0;$a<count($list);$a++)
    {
      echo "  <li>".$list[$a][2]."</li>\n";    
    }
  
    echo "</ul>\n";
  } 

$result = mysql_query("SELECT * FROM ".$tbl_dt." WHERE blog_id='' AND lang='RU' $faq_qry ORDER by date LIMIT 0 , 50"); // id=8 AND 
$myrow = mysql_fetch_array($result);
do
{
    if (!CreatePost($myrow))
    {
        continue;
    }
}
while ($myrow = mysql_fetch_array($result));

include_once ("footer.html");
?>