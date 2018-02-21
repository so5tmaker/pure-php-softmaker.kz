<tr>
    <tr>
        <td height="128" width="100%" align="center" style="background:url('<? echo $deep; ?>img/5.jpg') no-repeat #4491D7;padding-left:150px;">
            <!--Поиск по сайту-->
            <div align="center">
            <form action="http://www.softmaker.kz/google_search.php" id="cse-search-box">
              <div>
                <input type="hidden" name="cx" value="partner-pub-7017401012475874:5872442383" />
                <input type="hidden" name="cof" value="FORID:10" />
                <input type="hidden" name="ie" value="Windows-1251" />
                <p>
                <input type="text" name="q" size="85" />
                <input class="search_b" type="submit" name="sa" value="<? echo get_foreign_equivalent("Искать"); ?>" />
                </p>
              </div>
            </form>
            <script type="text/javascript" src="http://www.google.com/jsapi"></script>
            <script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
            <script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en%2Cru"></script>
            <script type="text/javascript" src="http://www.google.kz/coop/cse/brand?form=cse-search-box&amp;lang="></script>
            </div>
            <!--Поиск по сайту-->
            <div>
                
                <b> 
                <?
                if ($lang == "RU") {
                    ?>
                    <a style="color:#FFFFFF;font:20pt bold Verdana,Tahoma;" 
                    title="SoftMaker.kz - блог для начинающих и опытных программистов | 
                    Примеры программирования и разработка в системе 1С, PHP, Delphi, работа с MySQL, HTML, CSS" 
                    href="<? echo $rest_; ?>/"> SoftMaker.kz </a>
                        <p style="color:#FFFFFF;font:12pt bold Verdana,Tahoma;">
                    Все о программировании и разработке в системе 1С, PHP, Delphi, работа с MySQL, HTML, CSS
                    </p>
                    <?
                } else {?>
                    <span style="color:#FFFFFF;font:20pt bold Verdana,Tahoma;">
                    <?   echo get_foreign_equivalent("Примеры программирования в 1С, PHP, Delphi");?>
                    </span>
                <?
                } 
                ?></b>
             </div>
            
        </td>
     </tr>
    <tr>
        <td height="11" style="background:url('<? echo $deep; ?>img/MiddleBar.gif');">
            <img src="<? echo $deep; ?>img/SupForMon.gif" border="0">
       </td>
    </tr>
 </tr>