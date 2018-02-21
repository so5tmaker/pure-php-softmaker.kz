<?php
include("bd.php");

require_once $DIR.'classes/DB.class.php';
require_once $DIR.'classes/Admin.class.php';
//��� ���������� ��������� �����. ������ �����, �� ��������� ���������� � �����.
//connect to the database
$db1 = new DB();
$db1->connect();

$max_mails = 140;
$email_quant = 5;

$langs[0] = "RU";
$langs[1] = "EN";

if (strstr($SERVER, 'en.')) {
    $lang = "EN";
} else {
    $lang = "RU";
}

$opp_lang = ($lang == "RU") ?  "EN" : "RU";

$SizeOfinput="93";
$ColsOfarea="80";
$SizeOfSelect = "77";
$langs[0] = "RU";
$langs[1] = "EN";

$open = 'target=_blank title="'.get_foreign_equivalent("��������� � ����� ����").'"';

// ������� ������������� ��� �������� ���� ������ ����
function del_all_cache()
{
    $dir = '../cache/';
    //    ������� ��� ����� � �����
    if($handle = opendir($dir))
    {
            while(false !== ($file = readdir($handle)))
                    if($file != "." && $file != "..") unlink($dir.$file);
            closedir($handle);
    }
}
// ������� ������������� ��� �������� ����� ����
function del_cache($id,$filename)
{
    global $db;
 
    $result = mysql_query("SELECT name FROM data WHERE (id=$id)",$db);
    $myrow = mysql_fetch_array($result);
    $num_rows = mysql_num_rows($result);
    // ���� ���������� ������� �� ����� ����
    if ($num_rows!=0)
    {
        $file_name = $myrow[name];
        $filename0 = '../cache/'.$file_name.'_0.cache';
        $filename1 = '../cache/'.$file_name.'_1.cache';
        // ����� ��� �����
        if (file_exists($filename0)){ 
            unlink ($filename0);
        }
        if (file_exists($filename1)){
            unlink ($filename1);
        }
    }
}

// ������� ������������� ��� ��������� ������ ������ �������
function get_id($tbl_dt)
{   // ����������� �� ��������
    $result = mysql_query("SELECT id FROM ".$tbl_dt." ORDER BY `id` DESC");    
    $myrow = mysql_fetch_array($result);
    $num_rows = mysql_num_rows($result);
    
    // ���� ���������� ������� ��������� � ��������� id, �����...
    if ($num_rows==$myrow['id'])
    { // ...������ ����� ������ ������� � ������� � ���������� ��������� id,
     // �������� �� ������� ��� ��������
       return $myrow['id'] + 1;
    } else { // ����������� �� �����������, �����...
        $result1 = mysql_query("SELECT id FROM ".$tbl_dt." ORDER BY `id` ASC");    
        $myrow1 = mysql_fetch_array($result1);
        $i = 1;
        do // ...���� "������" id � ��������� ������ �� ����� id
        {
            if ($i == $myrow1['id']) {
                $i++;
                continue;
            } else {
                return $i;
            }
        }
        while ($myrow1 = mysql_fetch_array($result1));
    }
}

function translitIt($str) 
{
    $tr = array(
        "�"=>"a","�"=>"b","�"=>"v","�"=>"g",
        "�"=>"d","�"=>"e","�"=>"yo","�"=>"zh","�"=>"z","�"=>"i",
        "�"=>"j","�"=>"k","�"=>"l","�"=>"m","�"=>"n",
        "�"=>"o","�"=>"p","�"=>"r","�"=>"s","�"=>"t",
        "�"=>"u","�"=>"f","�"=>"x","�"=>"c","�"=>"ch",
        "�"=>"sh","�"=>"shh","�"=>"j","�"=>"y","�"=>"",
        "�"=>"e","�"=>"yu","�"=>"ya","�"=>"a","�"=>"b",
        "�"=>"v","�"=>"g","�"=>"d","�"=>"e","�"=>"yo","�"=>"zh",
        "�"=>"z","�"=>"i","�"=>"j","�"=>"k","�"=>"l",
        "�"=>"m","�"=>"n","�"=>"o","�"=>"p","�"=>"r",
        "�"=>"s","�"=>"t","�"=>"u","�"=>"f","�"=>"x",
        "�"=>"c","�"=>"ch","�"=>"sh","�"=>"shh","�"=>"j",
        "�"=>"y","�"=>"","�"=>"e","�"=>"yu","�"=>"ya", 
        " "=> "-", "."=> "", "�"=> "i",
        "�"=> "i", "&#1186;"=> "n", "&#1187;"=> "n", "&#1198;"=> "u",
        "&#1199;"=> "u", "&#1178;"=> "q", "&#1179;"=> "q", "&#1200;"=> "u",
        "&#1201;"=> "u", "&#1170;"=> "g", "&#1171;"=> "g", "&#1256;"=> "o",
        "&#1257;"=> "o", "&#1240;"=> "a", "&#1241;"=> "a"			 				
    );
    // ������ ����, ������ ������ ������
    $urlstr = str_replace('�'," ",$str);
    $urlstr = str_replace('-'," ",$urlstr); 
    $urlstr = str_replace('�'," ",$urlstr);
//    $urlstr = preg_replace('/[^��\-]/', '', $str);
    // ������ ������ ������� ������ ������
    $urlstr=preg_replace('/\s+/',' ',$urlstr);
     if (preg_match('/[^A-Za-z0-9_\-]/', $urlstr)) {
        $urlstr = strtr($urlstr,$tr);
        $urlstr = preg_replace('/[^A-Za-z0-9_\-]/', '', $urlstr);
        $urlstr = strtolower($urlstr);
        return $urlstr;
    } else {
        return strtolower($str);
    }
}
// ������� ������������� ��� ��������� �������� ������ ���� (cat) �� id
function get_fld_by_id($id, $tbl, $fld = 'cat', $add_clause = "")
{   
    global $db;
    $result = mysql_query("SELECT ".$fld." FROM ".$tbl." WHERE (id=$id) ".$add_clause,$db);
    $myrow = mysql_fetch_array($result);
    $num_rows = mysql_num_rows($result);
    // ���� ���������� ������� �� ����� ����
    if ($num_rows!=0)
    {
        return $myrow[$fld];
    } else { 
        return '0';
    }
}
// ������� ����� ������, ����� ���� ������ �� ������ �� ������������ ������
function get_lang_link_(){
    global $lang;
    $link = "http://www.softmaker.kz/";
    if ($lang <> "RU") {
        $link = str_replace("www", strtolower($lang), $link);
    }
    return $link;
 } // get_lang_link
// ������� ����� ������, ������ ���� ������
function get_other_lang_link($lang){
    $link = "http://www.softmaker.kz/";
    if ($lang == "EN") {
        $link = str_replace("www", 'en', $link);
    }
     return $link;
 } // get_other_lang_link
 
 // ������� ������������� ��� ������ ��������� �� ������ �������
function echo_error($result) 
{ 
    if (!$result) {
        $err_str = mysql_error();
        echo get_foreign_equivalent("<p>������ �� ������� ������ �� ���� �� ������. �������� �� ���� �������������� info@softmaker.kz. <br> <strong>��� ������:</strong></p>");
        echo("<p>".$err_str."</p>");
    }
} // echo_error
 
 // ������� ������������� ��� ��������� �������� ������������ ����� � ��������
function get_foreign_equivalent($word) 
{ 
    global $lang, $db;
    
    if ($lang == 'RU'){return $word;}
    
    $res_lang = mysql_query("SELECT ".$lang." FROM catalog WHERE RU='".$word."'",$db);
//    echo $word;
    echo_error($res_lang);

    if (mysql_num_rows($res_lang) > 0)

    {
        $myrow_lang = mysql_fetch_array($res_lang);
        do
        {   
//            echo "��";
            return $myrow_lang[$lang];
        }
        while ($myrow_lang = mysql_fetch_array($res_lang));
    }
//    echo "���";
    return $word;
} // get_foreign_equivalent

 function create_image($source, $distination, $check_dist = FALSE) {
     if ($check_dist){
         if (file_exists($distination)){
             return TRUE;
         }
     }
     // �������� ���� �� �� ����� ���� ���� ��������� ����������� - $source,
    if (file_exists($source)){
        // ������� ���������� �����. 
        // ��� ����� ��������� ������� PHP explode ��� ���������� ����� ����� 
        // �� ����� ����� �������, � ����� ���������� ������� count ��� 
        // ����������� ��������� ����� �������� ������, 
        // ������� �������� ����������� �����. 
        // ��� ����� ��� ����������� ������� �����������.
        $stype = explode(".", $source);
        $stype = $stype[count($stype)-1];
        switch($stype) {
            case 'gif':
            $image = imagecreatefromgif($source);
            break;
            case 'jpg':
            $image = imagecreatefromjpeg($source);
            break;
            case 'png':
            $image = imagecreatefrompng($source);
            break;
        }

        // ������ �������� ������� ��������� �����������.
        $size = getimagesize($source);
        $image_width = $size[0];    // ������ ��������� ����������� 
        $image_height = $size[1];    // ������ ��������� �����������

        // ����� ��� ���:
    //                $image_width = imagesx($image);
    //                $image_height = imagesy($image);

        $thumb_width = 150; // ������ ���������
        $thumb_height = 100; // ������ ���������

        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);
    //              imagecreatetruecolor  ������� ������������ ����������� ���������� � 
    //              ���������� ������� � ���������� ��� �������������.
        imagealphablending($thumb, false);
    //                imagealphablending ��������� ������� ���� �� ���� �������� ������ � �����-�������.
    //                ���� ������ �������� ���� ������� ���������� � true, �� ��� ��������� �������������� 
    //                ������ ����� ����������� �������� ������������� �� ������. ���� �� �� ���������� � 
    //                false, �� ���������� ������ ��������� �������� ��������� ����������� �� ������� 
    //                ������. ����, ��� ��� ����� ������ ������ �����.
        imagesavealpha($thumb, true);
    //                ������� imagesavealpha ���������� ��� ����, ����� ��� ������ ������� 
    //                imagePNG �����-����� ��� �������� � �������������� �����������. 
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height); 
    //                imagecopyresampled �������� ������������� ������� �� ������� ����������� �� ������. 
    //                ��� ��������� �� ���� �������������� �����������, ���������� �������� ������ ���� 
    //                ������� �� ������ �����������, ���������� �������� ������ ���� ������� � ������ 
    //                �����������, ������ � ������ ������� �� ������ ����������� � ������ � ������ ������� 
    //                � ������. ���� ������� �������� ��������, ���������� ����������� ������������� ��� 
    //                ���������. ��� ���� ����������� �����������, ��� ��� �������� �������� �������� 
    //                ������������������.
        imagePNG($thumb,$distination,9);
    //                ��������� ��������� �� ���������� ���� - $distination.
    //                ��� ��� �� ����� ��������� ������������ ��������� �����������, 
    //                ��������� ���������� ��������� � ������� PNG.  

        //����������� ������
        imagedestroy($image);
        imagedestroy($thumb);
        return TRUE;
    } else {return FALSE;}
}

// �������� - $myrow ������ � ������������ ����� ���������� �������:
// $result = mysql_query("SELECT * FROM data WHERE id=$id");
// $myrow = mysql_fetch_array($result);
// �������� - $deep �����, ���� ������ �������� � ����� /admin, a ��������� � ��������. 
// ���� ������ � ��� �� �����, �� $deep = ''
 function create_thumb($myrow, $deep = '../'){
    // �����, ������������ ���������� GD !!!
    // ����� �� ���� ����������� ��������� ��� ���������� �����������. 
    $link = get_other_lang_link($myrow[lang]);
    $mini_img = $deep.$myrow[mini_img];
    $mini_img_temp = $deep.'img/thumb/temp/'.$myrow[cat].'.png';
    // ��� ������� � ����� � �����������
    $part_file = $deep.'img/thumb/';
    
    $default_name = $part_file.'default.png'; // ��� ������ ������ ��������� default.png
    $distination = $part_file.$myrow[name].'.png'; // ��������� �� �������������������� �������� ������
    try{
        if (!file_exists($distination)){
            $matches = null;
            // �������� �� ������ ������ src, �.�. ������ ������ �� �����������
            preg_match_all('/<img[^>]+src=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/', 
            stripslashes($myrow[text]), $matches);
            $bodytag = $matches[2];
            $bodytag = str_replace("../../", $link, $bodytag); 
            // ������� ../../ �� http://www.softmaker.kz/ 
            if (!empty($bodytag)){ // ���� � ������ ���� ���� �� ���� �����������
                $source = $bodytag[0]; // �������� ���� 
                $source = $deep.str_replace($link, "", $source);
            // �������� ���� �� �� ����� ���� ���� ��������� �����������,
            // ������� �������� �� ������ ������
            if (create_image($source, $distination)){
                // ����� �������� ��������� �������� ������� 
                // � ����� temp ���� ����������� ��� ���������,
                // ���� ����, �� ������ ���
                if (file_exists($mini_img_temp)){
                    unlink ($mini_img_temp);
                }
            } else { // ���� ���� ��������� ����������� �� ����������
                $distination = $default_name;
            } 
          } else { // ���� � ������ ��� �� ������ �����������
              $source = $mini_img;
              $distination = $mini_img_temp;
              // �� �������� ������� ���� ����������� ��� ���������
              if (!create_image($source, $distination, TRUE)){
              // ���� ��� ���� ����������� ��� ��������� ������ ���������
                  $distination = $default_name;
              }
          } 
             
        }
    } catch (Exception $exc) {
           $distination = $default_name;
           // ���� �������� ������ �������
    }
//    $distination = "<div class='tumbr' style='margin: 12px 15px 0 15px; 
//        width:150px;
//        height: 100px;
//        background: url($distination) 0 0 no-repeat;
//        background-size: 100%;'>
//            </div>";
    $distination = '<p align=center><img width="150" height="100" title="���������� ����������� � ��� ��� ���������� ������������ ������"
	alt="���������� �������������� ����� � ��������" 
	src="'.$distination.'" ></p>';
    $rest = substr($myrow[meta_d], 0, 57);
    $distination = $distination."<div class='fhjk'> 
          $rest... 
          </div>";
    return '<ol class="sdfhju">
  <li class="rle">'.$distination.'</li></ol>';
 }


 // ********************** ������������ ��� PHP *******************************

function unhtmlentities ($str)
{
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($str, $trans_tbl);
} // unhtmlentities ($str)

 function highlight_code($code) 
{ 
  // ���� �� ����� $code ������������ ��������
  // htmlspecaialchars, ����� ����� �������� ���, ������� �������� �� �������� 
  $code = unhtmlentities ($code);
//  if(!strpos($code,"<?") && substr($code,0,2)!="<?") {
//    $code="<?php\\n".trim($code)."\\n?)>"; 
//  }  
  $code = trim($code); 
  return highlight_string($code,true);
} // highlight_code($code)
 
function get_highlight_code($code, $br = true) 
{ 
    $string = $code;
    $CountOfEntry = preg_match_all("/<pre>(.+?)<\\/pre>/is", $string, $matches, PREG_PATTERN_ORDER);
    if ($CountOfEntry != 0) {
        for ($i = 0; $i < $CountOfEntry; $i++) {
            // ����� ��� � ������ �� ������ "<pre></pre>"
            $pre = str_ireplace($matches[0][$i],"<pre>".$i."</pre>",$string);
            $string = $pre;
        }
        for ($i = 0; $i < $CountOfEntry; $i++) {
            // ����� ��� � ������ �� ������ "<pre></pre>"
            $matches_str = $matches[0][$i];
            if (stristr($matches_str, '&lt;?') == FALSE AND stristr($matches_str, '<?php') == FALSE){
                $string = str_ireplace("<pre>".$i."</pre>",$matches_str,$string);
                continue;
            }
            $matches_str = str_ireplace("<pre>","",$matches_str);
            $matches_str = str_ireplace("</pre>","",$matches_str);
            $matches_str = str_ireplace("<strong>","",$matches_str);
            $matches_str = str_ireplace("</strong>","",$matches_str);
            $highlight_code = highlight_code($matches_str);
            if ($br) {
                $highlight_code = str_ireplace("<br />","",$highlight_code);
            }
            $pre = str_ireplace("<pre>".$i."</pre>","<pre>".$highlight_code."</pre>",$string);
            $string = $pre;
        }
        return $string;
    }
  return $code;
} // get_highlight_code
// ********************** ������������ ��� PHP ����� *******************************

// ********************** ������ ����� ����� (sitemap) *******************************
function get_array()
{
	global $subs, $db, $langs, $lang;
//        foreach($langs as $k => $v) {
//            $link = get_other_lang_link($v);
//            $subs[] = $link."sitemap.php";
//        }
        
        $link = get_other_lang_link($lang);
        $subs[] = $link."sitemap.php";
        $sql1 = "SELECT data.id, data.name, data.cat, data.lang FROM data "
                . "INNER join categories on data.cat=categories.id "
                . "WHERE data.lang = '$lang' AND categories.turnon=1 "
                . "order by data.id, data.cat, data.lang DESC";     
        $result1 = mysql_query($sql1, $db);
	$myrow1 = mysql_fetch_array($result1);
        $filename = "articles";
	do 
	{
            $file_name = $myrow1[name];
            $cat = $myrow1[cat];
            $cat_name = get_fld_by_id($cat, 'categories', 'name');
            if ($cat_name == '0')
            {
                continue;
            }
            $link = get_other_lang_link($myrow1[lang]);
            $subs[] = "$link$filename/$cat_name/$file_name.html";
	}
	while ($myrow1 = mysql_fetch_array($result1));
//        $sql2 = "SELECT id,name,cat,lang FROM files\n"
//                . "WHERE lang = '$lang'"
//                . "order by id,cat,lang DESC ";
//        $result2 = mysql_query($sql2, $db);
//	$myrow2 = mysql_fetch_array($result2);
//        $filename = "files";
//	do 
//	{
//            $file_name = $myrow2[name];
//            $cat = $myrow2[cat];
//            $cat_name = get_fld_by_id($cat, 'categories', 'name');
//            if ($cat_name == '0')
//            {
//                continue;
//            }
//            $link = get_other_lang_link($myrow2[lang]);
//            $subs[] = "$link$filename/$cat_name/$file_name.html";
//	}
//	while ($myrow2 = mysql_fetch_array($result2));
	return $subs;
}

function CreateSitemap()
{
    $fw=fopen('../Sitemap.xml','w'); #������
    $subs = array();
    $subs = get_array();
    fwrite($fw,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>"."\n");
    fwrite($fw,"<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">"."\n");

    foreach($subs as $k => $v) {
        fwrite($fw,"<url>"."\n");
        $strokaxml = "<loc>".$v."</loc>";//http://www.softmaker.kz/
        fwrite($fw,$strokaxml."\n");
        fwrite($fw,"<lastmod>".date("Y-m-d")."</lastmod>"."\n");
        fwrite($fw,"<changefreq>monthly</changefreq>"."\n");
        fwrite($fw,"<priority>0.8</priority>"."\n");
        $stroka = "<p>".$v."</p>";
        print $stroka;
        fwrite($fw,"</url>"."\n");
    }
    fwrite($fw,"</urlset>"."\n");
    print "<p>����� �����: ".count($subs)."</p>";
    #������� ����
    fclose($fw);

}

// ********************** ������ ����� ����� (sitemap) ����� *******************************

?>
