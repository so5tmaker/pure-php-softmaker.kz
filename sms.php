<? //include ("blocks/bd.php");
require_once 'blocks/global.inc.php';
$n=0;
$text=""; $title = get_foreign_equivalent("Помощь сайту");
require_once ("header.html");
if ($lang == 'RU') 
{
    $pref = ""; $language = "russian";
    $sms_text = "<p style='font-size:14px; margin-bottom:0px;'>Чтобы отправить сумму, выполните следующие действия:</p>";
} else {
    $pref = "_en"; $language = "english";
    $sms_text = "";
}
$scr = "http://donate.smscoin.com/js/smsdonate/index".$pref.".html?sid=415293&language=".$language;
?>
<p>
<? echo $sms_text; ?>
<IFRAME style="border: 5px solid #FFFFFF;" SRC="<? echo $scr; ?>" WIDTH=560 HEIGHT=440
NAME="iframe" SCROLLING="no" NORESIZE>
    <SCRIPT> window.open('http://donate.smscoin.com/js/smsdonate/index<? echo $pref; ?>.html?sid=415293&language=<? echo $language; ?>', 
        'smsdonate_popup', 'height=500,left=' + (screen.width - 700 >> 1) + ', resizable=yes,scrollbars=yes,top=' + (screen.height - 500 >> 1) + ',width=700');
    </SCRIPT>
</IFRAME>
</p>
<?
require_once ("footer.html");
?>
