<td width="172" align="center" valign="top" class="left"><!--tr1_td1_table1_tr1_td2-->
    
<div class="menu_title"><? echo get_foreign_equivalent("Получать статьи по почте"); ?></div>

<p align="center">
   <span class='comments'>
   <? 
   if ($lang == "EN") {
        echo "<a class='inputtext' href='".show_other_lang_link()."'><b>Русский</b></a>&nbsp;&nbsp;&nbsp;<b>English</b>";
    } else {
        echo "<b>Русский</b>&nbsp;&nbsp;&nbsp;<a class='inputtext' href='".show_other_lang_link()."'><b>English</b></a>";    
    } 
   ?>
   </span>
</p>
<?
$feed_lang = ($lang=="EN") ? $lang : "";
?>        
<p align="center">
    <a <? echo $open; ?> href="<?echo 'http://feeds.softmaker.kz/'.$feed_lang.'Softmakerkz/' ?>">
        <img src="<? echo $deep; ?>img/rss.png" width="32" height="27" title="RSS" alt="RSS"/>
    </a>
</p>
<?
show_form_subscribe_by_mail(FALSE);
?>

<script type="text/javascript">(function() {
          if (window.pluso)if (typeof window.pluso.start == "function") return;
          var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
          s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
          s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
          var h=d[g]('head')[0] || d[g]('body')[0];
          h.appendChild(s);
          })();</script>
<div class="pluso" 
     data-options="small,square,multiline,horizontal,counter,theme=01" 
     data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,blogger" 
     data-background="transparent">
</div>
    
<a name="enter" id="enter"></a><div class="menu_title"><? echo get_foreign_equivalent("Вход"); ?></div>
<?php if(isset($_SESSION['logged_in'])) : ?>
<?php $user = unserialize($_SESSION['user']); ?>
<p><? echo get_foreign_equivalent("Здравствуйте"); ?>, <strong><?php echo $user->username;?>!</strong></p>
<p><img src="<? echo $deep; ?>img/users/User.png" width="64" height="64" alt="Пользователь" /></p>
<?php // $user = unserialize($_SESSION['user']); ?>
<p><? echo get_foreign_equivalent("Вы вошли на сайт."); ?></p>
<p><a href="<? echo $rest_."/logout.php"; ?>"><? echo get_foreign_equivalent("Выйти"); ?></a></p>
<p><a href="<? echo $rest_."/settings.php"; ?>"><? echo get_foreign_equivalent("Изменить настройки"); ?></a></p>
<?php else : ?>
<?require_once $DIR."login.php"?>
<? echo $quant_users_str;?>
<p align="center" class="inputtext"><a <? echo $open;?> href="<? echo $rest_;?>/register.php"><? echo get_foreign_equivalent("Регистрация"); ?></a></p>
<p align="center" class="inputtext"><a <? echo $open;?> href="<? echo $rest_; ?>/mail/resetpassword.php"><? echo get_foreign_equivalent("Забыли пароль?"); ?></a></p>
<p></p>
<?php endif;?>

<!--<div class="menu_title"></div>
<p>
<script type="text/javascript" src="/orphus/orphus.js"></script>
<a rel="nofolow" href="http://orphus.ru" id="orphus" <? echo $open;?>>
    <img alt="Система Orphus" src="<? echo $deep;?>orphus/orphus.gif" border="0" width="125" height="115" /></a>
</p>-->

<?
error_reporting(0);
$menu = "<div class='menu_title'></div>";
if ($n==1) { // реклама на главной
    echo $menu;
    codbanner(4);  
} elseif ($n==2) { // реклама в статьях
    echo $menu;
    codbanner(5);
} elseif ($n==3) {// реклама в файлах
    echo $menu;
    codbanner(6);
} else {
    $sec_res = $db1->select('sections', "lang='$lang'", "id, name");
    foreach($sec_res as $k_s => $v_s) {
        $sql_tab = "(
    	SELECT name,cat,title,view
    	FROM data
    	WHERE lang = '$lang'
        ) as d, categories as cat";
        $result = $db1->select($sql_tab, 
                "cat.id=d.cat AND cat.sec = '$v_s[id]' AND (turnon=1) ORDER BY view DESC  LIMIT 5", 
                "cat.name as catname, cat.sec as sec, d.*");
        if ($v_s[name] == 'articles'){
            printf ("<div class='menu_title'>%s</div>", get_foreign_equivalent("Популярные статьи"));
        }elseif ($v_s[name] == 'files'){
            printf ("<div class='menu_title'>%s</div>", get_foreign_equivalent("Популярные файлы"));
        }  else {
            continue;
        }
        foreach($result as $k => $v) {
            $path = "$rest_/$v_s[name]/$v[catname]/$v[name].html";
            printf ("<p class='point'><a $open class='m' href='%s' >%s</a></p>"
                    ,$path,$v["title"]);
        }
    }
}

//$advs->show('right', $Link);
?>
<span class="nolink" onclick="GoTo('_www.moedelo.org/Referal/Lead/28107','')">
    <span>
    <img src="http://www.softmaker.kz/img/partners/moedelo/159x600.gif">
    </span>
</span>
<!--<a target="_blank" href="http://www.moedelo.org/Referal/Lead/28107"><img src="http://www.moedelo.org/mrkbanners/159x600.gif"></a>-->

<!--</div>-->
  
</td><!--tr1_td1_table1_tr1_td2-->

