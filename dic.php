<? 
require_once 'blocks/global.inc.php';

// полный алфавит синхронизации
$alphabet_syncro =  array(
        'Aa_kzstn_' => '&#1240;',
        'Gg_kzstn_' => '&#1170;',
        'Kk_kzstn_' => '&#1178;',
        'Hh_kzstn_' => '&#1186;',
        'Oo_kzstn_' => '&#1256;',
        'Uu_kzstn_' => '&#1200;',
        'Yy_kzstn_' => '&#1198;',
        'Aa_l_kzstn_' => '&#1241;',
        'Gg_l_kzstn_' => '&#1171;',
        'Kk_l_kzstn_' => '&#1179;',
        'Hh_l_kzstn_' => '&#1187;',
        'Oo_l_kzstn_' => '&#1257;',
        'Uu_l_kzstn_' => '&#1201;',
        'Yy_l_kzstn_' => '&#1199;'
        );
// массив алфавита синхронизации с казахскими символами
$alphabet_syncro1 =  array(
        0 => '&#1240;',
        1 => '&#1170;',
        2 => '&#1178;',
        3 => '&#1186;',
        4 => '&#1256;',
        5 => '&#1200;',
        6 => '&#1198;',
        7 => '&#1241;',
        8 => '&#1171;',
        9 => '&#1179;',
        10 => '&#1187;',
        11 => '&#1257;',
        12 => '&#1201;',
        13 => '&#1199;'
        );
// массив алфавита синхронизации с заменой казахских символов
$alphabet_syncro2 =  array(
    0 => 'Aa_kzstn_',
    1 => 'Aa_kzstn_',
    2 => 'Kk_kzstn_',
    3 => 'Kk_kzstn_',
    4 => 'Oo_kzstn_',
    5 => 'Uu_kzstn_',
    6 => 'Yy_kzstn_',
    7 => 'Aa_l_kzstn_',
    8 => 'Gg_l_kzstn_',
    9 => 'Kk_l_kzstn_',
    10 => 'Hh_l_kzstn_',
    11 => 'Oo_l_kzstn_',
    12 => 'Uu_l_kzstn_',
    13 => 'Yy_l_kzstn_'
    );
// массив шаблонов алфавита синхронизации с казахскими символами
$alphabet_syncro1_p =  array(
    0 => '/&#1240;/',
    1 => '/&#1170;/',
    2 => '/&#1178;/',
    3 => '/&#1186;/',
    4 => '/&#1256;/',
    5 => '/&#1200;/',
    6 => '/&#1198;/',
    7 => '/&#1241;/',
    8 => '/&#1171;/',
    9 => '/&#1179;/',
    10 => '/&#1187;/',
    11 => '/&#1257;/',
    12 => '/&#1201;/',
    13 => '/&#1199;/'
    );
// массив шаблонов алфавита синхронизации с заменой казахских символов
$alphabet_syncro2_p =  array(
    0 => '/Aa_kzstn_/',
    1 => '/Aa_kzstn_/',
    2 => '/Kk_kzstn_/',
    3 => '/Kk_kzstn_/',
    4 => '/Oo_kzstn_/',
    5 => '/Uu_kzstn_/',
    6 => '/Yy_kzstn_/',
    7 => '/Aa_l_kzstn_/',
    8 => '/Gg_l_kzstn_/',
    9 => '/Kk_l_kzstn_/',
    10 => '/Hh_l_kzstn_/',
    11 => '/Oo_l_kzstn_/',
    12 => '/Uu_l_kzstn_/',
    13 => '/Yy_l_kzstn_/'
    );

function change_alphabet($alphabet_pattern, $alphabet, $str) { 
    // ищу совпадения в строке $str из массива $alphabet_pattern и
    //  заменяю найденные символы символами из массива $alphabet 
    $count = null;  
//    return preg_replace($alphabet_pattern, $alphabet, $str, -1, $count);
    return str_replace($alphabet_pattern, $alphabet, $str);

}

function set_alphabet_example($str) { // заменяю квадратные скобки на абзац
    return preg_replace("/\[([^]]+)\]*/i ", "<p class='alphabet_ex'>$1</p>", $str);
}

function set_alphabet_word($str, $langlink, $column_lang, $search_kz = '') {
    global $lang, $language, $search, $db, $alphabet_syncro1, $alphabet_syncro2;
    
    // получаю слово внутри фигурных скобок, оно нужно для поиска
    $result = preg_match("/\{([^}]+)\}*/i", $str, $found);
//    $result1 = preg_match_all("/\{([^}]+)\}*/i", $str, $found1);
    $search1 = $found[1];
    if (!isset ($search1)) {
        return $str;
    }
//    $res_dic = mysql_query("SELECT * FROM dic WHERE $language LIKE '$search%'",$db);
//    $res_dic = mysql_query("SELECT * FROM dic WHERE (KZ LIKE '$search%') OR (RU LIKE '$search%') OR (EN LIKE '$search%')",$db);
    $query = "SELECT 'KZ' FROM dic
              WHERE KZ LIKE '$search1%' 
            UNION
            SELECT 'RU' FROM dic
              WHERE RU LIKE '$search1%'
            UNION
            SELECT 'EN' FROM dic
              WHERE EN LIKE '$search1%'";
    $res_dic = mysql_query($query,$db);
    echo_error($res_dic);

    if (mysql_num_rows($res_dic) > 0)
    {
        $myrow_dic = mysql_fetch_array($res_dic);
        do
        { 
            $language = $myrow_dic['KZ'];
            $mess = get_foreign_equivalent("Ваш запрос обрабатывается...");
            $backurl = ($search_kz == '') ? $search : $search_kz;
            $search1 = change_alphabet($alphabet_syncro1, $alphabet_syncro2, $search1);
            // заменяю фигурные скобки на тэг ссылки и вставляю окончание слова перед закрывающейся скобкой
            return preg_replace('/\{(.*)\}([^\s]*)/', "<a onclick=\"doLoad(document.getElementById('dosearch'),'message','$mess','$lang',0,'$search1','$language','$backurl','$langlink','$column_lang')\">$1$2</a>", $str, -1);
//            return preg_replace('/\{(.*)\}([^\s]*)/', "<a href='dic.php?lang=$lang&search=$search' target='_blank'>$1$2</a>", $str, -1);
        }
        while ($myrow_dic = mysql_fetch_array($res_dic));
    }
    return preg_replace("/\{([^}]+)\}*/i", "$1", $str);
//    return $str;
}

function get_symbol_kz($str, $low) { // $low - английская "л" маленькая
    global $alphabet_syncro;
    $symbol = strstr($str, '_kzstn_');
    if ($symbol != FALSE) {
        return $alphabet_syncro[$str.$low];
    }
    return $str;
}
//%u04D8
$search = (isset($_GET['search'])) ? trim($_GET['search']) : trim($_POST['search']);
$language = (isset($_GET['language'])) ? trim($_GET['language']) : trim($_POST['language']);
$language = ($_GET['language'] == '') ? trim($_POST['language']) : trim($_GET['language']);
$language_post = (isset($_POST['language'])) ? trim($_POST['language']) : '';

$search_kz = '';

if ($language == 'KZ')
{
    $search_kz = $search;
    $search = change_alphabet($alphabet_syncro2, $alphabet_syncro1, $search);
//    $search = get_symbol_kz($search,'l');
}

// 'AaАа' 

$alphabet_kz = array('А','Aa_kzstn_','Б','В','Г','Gg_kzstn_','Д','Е','Ж','З','И','К',
                     'Kk_kzstn_','Л','М','Н','О','Oo_kzstn_','П','Р','С','Т','У',
                     'Uu_kzstn_','Yy_kzstn_','Ф','Х','Ц','Ч','Ш','Ы','І','Э','Ю','Я');
$alphabet_ru = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И',
                     'К','Л','М','Н','О','П','Р','С','Т','У',
                     'Ф','Х','Ц','Ч','Ш','Щ','Ы','Э','Ю','Я');            
$alphabet_en = array('A','B','C','D','E','F',
                     'G','H','I','G','K','L',
                     'M','N','O','P','Q','R',
                     'S','T','U','V','W','X',
                              'Y','Z');

if (isset($_POST['search']) OR isset($_GET['search']))
{
    // Запрет на кэширование %D1%85%D0%B0%D0%BB%D1%8B%D2%9B
    header("Expires: Mon, 23 May 1995 02:00:00 GTM");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GTM");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    //****

    $log =="";
    $error="no"; //флаг наличия ошибки

    require_once 'classes/JsHttpRequest.php';
    $JsHttpRequest =& new JsHttpRequest("windows-1251");

    if($search == '' AND !isset($_GET['radio']))
    {
            $log .= "<li>".get_foreign_equivalent("Вы ничего не ввели в поле поиска!")."</li>";
            $error = "yes";
    }

    //Экранирование и преобразование опасных символов
//    if (!get_magic_quotes_gpc())
//    {
//        $search = addslashes($search);
//    }
//    $search = htmlspecialchars($search);
    
    //****
    //Если нет ошибок ищем  
    if($error=="no" AND !isset($_GET['radio']))
    {
        $prov = 0;
        $ok = "
        <div class='div_dic'>
        <table width='100%' border='1' color: #518EAD; bordercolor='#518EAD' cellpadding='1' cellspacing='1' >
            <col width='33%' />
            <col width='33%' />
            <col width='33%' />
            <tr class='trkaz'>
                    <td><strong>&#1178;аза&#1179;ша</strong></td>
                    <td><strong>Русский</strong></td>
                    <td><strong>English</strong></td>
            </tr>";
        
//        $language = (isset($_GET['langlink'])) ? trim($_GET['langlink']) : $language;
        
        if (isset($_GET['langlink'])){
            $language = (isset($_GET['columnlang'])) ? trim($_GET['columnlang']) : $_GET['langlink'];
        } 

        if (isset($_GET['search'])){$firstsmbl='';} else {$firstsmbl='%';}
        
        $res_dic = mysql_query("SELECT * FROM dic WHERE ".$language." LIKE '$firstsmbl"."$search%'",$db);

        echo_error($res_dic);
        
        $columnlang = (isset($_GET['columnlang'])) ? trim($_GET['columnlang']) : '';
        
        if (mysql_num_rows($res_dic) > 0)

        {
            $myrow_dic = mysql_fetch_array($res_dic);
            do
            {
                $ok .= "<tr class='trkaz_alpabet'>
                            <td>".set_alphabet_word(set_alphabet_example($myrow_dic[KZ]),$language_post,'KZ',$search_kz)."</td>
                            <td>".set_alphabet_word(set_alphabet_example($myrow_dic[RU]),$language_post,'RU')."</td>
                            <td>".set_alphabet_word(set_alphabet_example($myrow_dic[EN]),$language_post,'EN')."</td>
                        </tr>";
            }
            while ($myrow_dic = mysql_fetch_array($res_dic));
            $ok .= "</table>
                </div>";
//          Если пришли по ссылке на слово, то ставим возврат откуда пришли.
//            $langlink = (isset($_GET['backurl']) == '') ? $language : $language_post;
            if (isset($_GET['backurl'])) {
                $backurl = $_GET['backurl'];
                $backurl_bool = ($backurl == '') ? FALSE : TRUE;
            }else{$backurl_bool = FALSE;}
            if ($backurl_bool) {
                $mess = get_foreign_equivalent("Ваш запрос обрабатывается...");
                $GoBack = get_foreign_equivalent("Вернуться назад");
                $langlink = ($language_post == '') ? $language : $language_post;
//                $langlink = ($columnlang == '') ? $language : $columnlang;
                $backurl = "<a class='alphabet_back_a' onclick=\"doLoad(document.getElementById('dosearch'),'message','$mess','$lang',0,'$backurl','','','$langlink')\"><< $GoBack</a>";
                if ($backurl_bool) {$ok .= $backurl;}
            }
        } else {
            $no_match = get_foreign_equivalent("Информация по Вашему запросу не найдена.");
            $ok ="<p style='font-family:Verdana; font-size:12px; border:2px solid #0c7f00; padding:10px; margin:20px; background-color:#ffffff;'><strong>".$no_match."</strong></p>";
        }

        //****
        //Помещаем результат в массив
        $GLOBALS['_RESULT'] = array(
        'error' => 'no',
        'ok' => $ok
        );
    } else if(isset($_POST['language']) AND isset($_GET['radio'])) {
        $prov = 0;
        $language = $_GET['language'];
        if ($language == 'KZ')
        {
            $alphabet = $alphabet_kz;
        } else if ($language == 'RU') {
            $alphabet = $alphabet_ru;            
        } else {
            $alphabet = $alphabet_en;
        }

        $mess = get_foreign_equivalent("Ваш запрос обрабатывается...");
        $str_alph = "<form method='POST' action='#' enctype='multipart/form-data' name='dosearch1' id='dosearch1' onSubmit='return false'>
                     <input type='hidden' name='language' id='language' value='$language'>";
        foreach ($alphabet as $val) {
            if ($language == 'KZ')
            {
                $symbol = get_symbol_kz($val,'');
                if (strstr($val, '_kzstn_') != FALSE) {
                    $val1 = str_replace('_kzstn_', '_l_kzstn_', $val);
                } else {$val1 = $val;}
            } else {$symbol = $val; $val1 = $val;}
            $str_alph .= "
                <input class='frmbtn_alphabet' onmouseout=\"this.className='frmbtn_alphabet'\" onmouseover=\"this.className='frmbtn_alphabetover'\" name='button' type='button' value='$symbol' onclick=\"doLoad(document.getElementById('dosearch1'),'message','$mess','$lang',0,'$val1','')\">
            ";
        }
        $str_alph .= "</form>";
        $ok = $str_alph;
        //****
        //Помещаем результат в массив
        $GLOBALS['_RESULT'] = array(
        'error' => 'no',
        'ok' => $ok
        );
        //****
    } else {//если ошибки есть
        $log = "<p><font color=#cc0000><strong>Ошибка</strong></font></p><ul style='font-family:Verdana; font-size:12px; border:2px solid #cc0000; padding:10px; margin:20px;'>".$log."</ul>";
        //Отправляем результат в массив
        $GLOBALS['_RESULT'] = array(
        'error' => 'yes',      
        'er_mess' => $log);
    }  
} else {
    $n=0; $dic = TRUE; $title = get_foreign_equivalent("Казахско-Русско-Английский")." ".get_foreign_equivalent("Словарь"); $text = "";
    $meta_d = $title; $meta_k = get_foreign_equivalent("Казахско-Русско-Английский").",".get_foreign_equivalent("Словарь");
//    $coding = "UTF-8";
    include_once ("header.html");
    ?>

    <h1 class='post_title2'><? echo  $title; ?></h1>

    <div class="adv_div" align="center">
    <script type="text/javascript"><!--
    google_ad_client = "pub-7017401012475874";
    /* 468x60, sm создано 28.06.10 */
    google_ad_slot = "6580654287";
    google_ad_width = 468;
    google_ad_height = 60;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    </div>
    <? $str_alph = "<form method='POST' action='#' enctype='multipart/form-data' name='dosearch1' id='dosearch1' onSubmit='return false'>
                    <input type='hidden' name='language' id='language' value='KZ'>";
    ?>
    <div align='center' id='alphabet'>
    <?    
        foreach ($alphabet_kz as $val) {
            $symbol = get_symbol_kz($val,'');
            if (strstr($val, '_kzstn_') != FALSE) {
                $val1 = str_replace('_kzstn_', '_l_kzstn_', $val);
            } else {$val1 = $val;}
            $str_alph .= "
                <input class='frmbtn_alphabet' onmouseout=\"this.className='frmbtn_alphabet'\" onmouseover=\"this.className='frmbtn_alphabetover'\" name='button' type='button' value='$symbol' onclick=\"doLoad(document.getElementById('dosearch1'),'message','','$lang',0,'$val1','')\">
            ";
        }
        $str_alph .= "</form>";
        echo $str_alph;
    ?>
    </div>
    <form method="POST" action="#" enctype="multipart/form-data" name="dosearch" id="dosearch" onSubmit="return false">
        <p align="center" class='post_comment'>
        <? echo get_foreign_equivalent("Выберите язык поиска"); //alert(this.value)?>:
        </p>
        <p align="center">
            <label><strong>&#1178;аза&#1179;ша</strong>
            <input type="radio" onclick="doLoad(document.getElementById('dosearch'),'alphabet',this.value,'<? echo $lang; ?>',1,'','')" checked name="language" id="language" value="KZ">
            </label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label><strong>Русский</strong>
            <input type="radio" onclick="doLoad(document.getElementById('dosearch'),'alphabet',this.value,'<? echo $lang; ?>',1,'','')" name="language" id="language" value="RU">
            </label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label><strong>English</strong>
            <input type="radio" onclick="doLoad(document.getElementById('dosearch'),'alphabet',this.value,'<? echo $lang; ?>',1,'','')" name="language" id="language" value="EN">
            </label>
        </p>
        <p align="center"><input type="text" class="inputtextdic" size="70" onblur="this.className='inputtextdic'" onfocus="this.className='inputtextdicact'" name="search" class="inputtext1" id="search">
        <? $mess = get_foreign_equivalent("Ваш запрос обрабатывается..."); ?>
        <input class="formbutton" style="width:110px"  name="button" type="button" value="<? echo get_foreign_equivalent("Поиск"); ?>"  onclick="doLoad(document.getElementById('dosearch'),'message','<? echo $mess; ?>','<? echo $lang; ?>',0,'','')"></p>
    </form>
    <div align='center' id='message'></div>
    
    <?
//    $text_code = 'пять {вариант}ов ответа';
//    $phrase = preg_replace("/\{([^}]+)\}*/i ", "<p>${1}</p>", $text_code);
//    echo $phrase;
//    $text_code = 'пять [вариантов] ответа';
//    $phrase = preg_replace("/\[([^]]+)\]*/i ", "<p>$1</p>", $text_code);
//    echo $phrase;
//    $phrase = preg_replace('/\{(.*)\}([^\s]*)/', '<p>$1$2</p>', 'пять {вариант}ов ответа', -1); 
//    echo $phrase;
//     $str = "Ищет в {sub}ject совпадения.";
//$phrase = preg_replace('/\{(.*)\}([^\s]*)/', '<em>$1$2</em>', $str); 
//echo $phrase;
//    echo '&#1240;';
//    echo get_symbol_kz('GgГг_kzstn_');
//    $text_code = 'пять [вариантов] ответа';
//    $result = preg_match("/\[([^]]+)\]*/i", $text_code, $maches);
//    print_r($maches);

//    echo encodeURIComponent('?'); 
//    echo uc2html('%D3%99');
//    echo decode_uc2html('%D3%99');
//    echo urldecode('%D3%99'); 
//    echo urlencode('%D3%99');
//    echo set_alphabet_example('пять [вариант]ов ответа');
//    $phrase = '{preg_match} - выполняет подстановку регулярного выражения.
//		  Ищет в {subject} совпадения с регулярным выражением, заданным в {pattern}.
//		  '; 
//    preg_match("/\{([^}]+)\}*/i", $phrase, $found);
//    print_r($found); 
   
//    $arr=parse_url('http://mysite/articles.php?cat=5&id=3');
//    //    print_r($arr);
//
//    parse_str($arr['query'], $arr2);
//    print_r($arr2); 
//
//    $phrase = 'http://mysite/articles.php?cat=5&id=3'; 
//
//    preg_match("/\?(?:.*&)*cat=([^&]+)/i", $phrase, $found);
//    print_r($found); 
//    
//    preg_match("/\?(?:.*&)*id=([^&]+)/i", $phrase, $found);
//    print_r($found); 
    
//    $arr=parse_url('http://mysite/articles.php?cat=5&id=3');
//
//    parse_str($arr['query'], $arr2);
//    print_r($arr2); 
//
//    $phrase = 'http://mysite/articles.php?cat=5&id=3'; 
//
//    preg_match("/\?(?:.*&)*cat=([^&]+)/i", $phrase, $found);
//    print_r($found);
//
//    preg_match("/\?(?:.*&)*id=([^&]+)/i", $phrase, $found);
//    print_r($found);
    
//    $phrase = 'dghsdfgg Здравствуйте,<strong>softmaker!</strong> dfghsfhfdhg';
////    $phrase = '{preg_match} - выполняет подcтановку регулярного выражения.'; 
//    $filename = 'cache/articles_2_4_1.cache';
//    $body = file_get_contents($filename);
//    preg_match("/Здравствуйте, <strong>([^<]+)<*/i", $phrase, $found);
//    $bodytag = str_ireplace($found[1], "admin!", $body);
////    echo $bodytag;
//    file_put_contents($filename, $bodytag);
//    $phrase = preg_replace('/\{(.*)\}([^\s]*)/', '<p>$1$2</p>', 'пять {вариант}ов ответа', -1); 
//    $phrase = preg_replace('/Здравствуйте,<strong>(.*)<\/strong>/i', '<chng>$1</chng>', $phrase); 
//    $pattern = "/\b".'admin'."\b/i";
//        $replacement = "<chng>".'admin'."</chng>";
//        $replacement = "<span style='background-color: ".$color.";'>".$word.$ending."</span>";
//        $string = preg_replace($pattern, $replacement, $phrase); 
//        if (stristr($string, $replacement) == FALSE) {
//            $pattern = $word.$ending;
//            $replacement = "<mark>".$word.$ending."</mark>";
//            $phrase = eregi_replace($pattern, $replacement, $string);
//        }
//    $phrase = preg_replace("/Здравствуйте,([^<\/strong>])/i ", "<p>$1</p>", $phrase);
//    echo $phrase;
//      echo uc2html("%D1%85%D0%B0%D0%BB%D1%8B%D2%9B");
    
//    $string = "&#1200;";
//    $vowels = 0;
//    for ($i = 0, $j = 13; $i < $j; $i++) {
//        
//        if (strstr($string,$alphabet_syncro1[$i])) {
//            $vowels++;
//            echo $i;
//        }
//    }
//    echo $vowels;
    // Добавляем к слову без окончания все окончания и окрашиваем
//    foreach ($alphabet_syncro1 as $symbol){
//        $symbol = '&#1179;';
//        $pattern = "/".$symbol."/";
//        $replacement = 'AA';
        
//    }
//    echo change_alphabet($alphabet_syncro1_p, $alphabet_syncro2, 'халы&#1179;');
    
//    $phrase = 'http://www.softmaker.kz/articles.php?cat=2&id=2'; 

//    preg_match("/\?(?:.*&)*cat=([^&]+)/i", $phrase, $found);
////    print_r($found); 
//    echo $found[1];
//    preg_match("/\?(?:.*&)*id=([^&]+)/i", $phrase, $found);
////    print_r($found);
//    echo $found[1];
    
//    $arr=parse_url('http://www.softmaker.kz/articles.php?cat=2&id=2');
//    parse_str($arr['query'], $arr2);
//    print_r($arr2); 

    include_once ("footer.html");
}
    ?>
