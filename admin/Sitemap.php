<? include ("lock.php");
if (isset($_POST['rec'])) {$rec = $_POST['rec'];}

$name_dt = "Sitemap.xml";
$title_here = "�������� �������� �����  ".$name_dt; include("header.html");
if (isset($rec))
{
    $fw=fopen('../Sitemap.xml','w'); #������
    if (!$fw) echo '<p>�� ���� ������� ���� ��� ������!</p>';
    $subs = array();
    get_array();
    fwrite($fw,"<?xml version=\"1.0\" encoding=\"UTF-8\"?>"."\n");
    fwrite($fw,"<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">"."\n");

    foreach($subs as $k => $v) {
        fwrite($fw,"<url>"."\n");//http://www.softmaker.kz/
        $strokaxml = "<loc>".$v."</loc>";
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
    
//    $ftp_server = "ftp.d1088065-6504.idhost.kz";
//    $ftp_user = "f126857";
//    $ftp_pass = "idHostFTP";
//
//    // ���������� ���������� ��� �����
//    $conn_id = ftp_connect($ftp_server) or die("<p>�� ������� ���������� ���������� � $ftp_server</p>"); 
//
//    // ������� �����
//    if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
//        echo "<p>���������� ���� �� $ftp_server ��� ������ $ftp_user\n</p>";
//    } else {
//        echo "<p>�� ������� ����� ��� ������ $ftp_user\n</p>";
//    }
//    ftp_raw($conn_id, "CWD softmaker.kz");
//    if (ftp_raw($conn_id, "SITE chmod 777 Sitemap.xml")) echo "<p>����� ������� � ����� ��������.</p>";
//    else echo "<p>�� ������� �������� ����� ������� � �����</p>";
//    // close the connection
//    ftp_close($conn_id);
    
//    if (!chmod('../Sitemap.xml', 0765)) echo '<p>�� ���� �������� ����� �������!</p>';
    #������� ������ � ������������� ���������
    //unlink('file.txt');
    //rename('temp_file.txt','file.txt');
}
?>
<form name="form1" method="post" action="Sitemap.php">
<input name="rec" type="hidden" value="yes">       
 <p>
   <label>
   <input type="submit" name="submit" id="submit" value="������� Sitemap">
   </label>
 </p>
</form>
<!--���������� ������ ����������� �������-->  
<?  include_once ("footer.html"); ?>  
