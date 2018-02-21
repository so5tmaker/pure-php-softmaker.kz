<? include ("lock.php");
if (isset($_POST['rec'])) {$rec = $_POST['rec'];}

$name_dt = "Sitemap.xml";
$title_here = "Страница создания файла  ".$name_dt; include("header.html");
if (isset($rec))
{
    $fw=fopen('../Sitemap.xml','w'); #писать
    if (!$fw) echo '<p>Не могу открыть файл для записи!</p>';
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
    print "<p>Всего строк: ".count($subs)."</p>";
    #закрыли файл
    fclose($fw);
    
//    $ftp_server = "ftp.d1088065-6504.idhost.kz";
//    $ftp_user = "f126857";
//    $ftp_pass = "idHostFTP";
//
//    // установить соединение или выйти
//    $conn_id = ftp_connect($ftp_server) or die("<p>Не удалось установить соединение с $ftp_server</p>"); 
//
//    // попытка входа
//    if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
//        echo "<p>Произведен вход на $ftp_server под именем $ftp_user\n</p>";
//    } else {
//        echo "<p>Не удалось войти под именем $ftp_user\n</p>";
//    }
//    ftp_raw($conn_id, "CWD softmaker.kz");
//    if (ftp_raw($conn_id, "SITE chmod 777 Sitemap.xml")) echo "<p>Права доступа к файлу изменены.</p>";
//    else echo "<p>Не удалось изменить права доступа к файлу</p>";
//    // close the connection
//    ftp_close($conn_id);
    
//    if (!chmod('../Sitemap.xml', 0765)) echo '<p>Не могу изменить права доступа!</p>';
    #удалили старый и переименовали временный
    //unlink('file.txt');
    //rename('temp_file.txt','file.txt');
}
?>
<form name="form1" method="post" action="Sitemap.php">
<input name="rec" type="hidden" value="yes">       
 <p>
   <label>
   <input type="submit" name="submit" id="submit" value="Создать Sitemap">
   </label>
 </p>
</form>
<!--Подключаем нижний графический элемент-->  
<?  include_once ("footer.html"); ?>  
