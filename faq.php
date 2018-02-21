<?
$faq = $myrow['faq'];


$pretag = str_replace($str1, $str2, stripslashes($myrow["text"]));
if ($myrow["phpcolor"] == '1') { // ALTER TABLE `data` ADD `phpcolor` VARCHAR( 1 ) DEFAULT '0' NOT NULL ;
    $pretag = get_highlight_code($pretag);
}

$advs->show('center', $Link, $pretag);

//echo $pretag; 
if ($logon == 1){
?>
<div align="center">
    <p class="pdf">Скачать заметку в удобном для чтения PDF-формате:<br>
    <span class="nolink" onclick="pdf('<? echo $Link->link; ?>', '<? echo $Link->file_name; ?>', '<? echo $Link->rest; ?>')" >
        <img src="<? echo $deep; ?>img/pdf.png">
    </span> 
    </p>
</div>
<?
} else {
?>
    <div align="center">
        <p class="pdf">
            <a target=blank_ title= "Откроется в новом окне" 
               href="http://localhost/softmaker.kz/register.php">
               Зарегестрируйтесь</a> или
            <a href="#enter">войдите</a>, чтобы скачать заметку в удобном для чтения PDF-формате:<br>
        <span id="dnld">
            <img src="<? echo $deep; ?>img/pdf.png">
        </span> 
        </p>
    </div>
<?
}

if ($file) {
    $show = false; $pay = false;
    $price = $myrow[price];
    if ($price <> '0'){
        $account = $user->id.'-'.$myrow[id];
        $dnldmess = "<p class='download_mess'>*Доступны два способа получения файла:"
                . "<ol><li class='download_mess'>Цена: $price руб. + % за оплату (% зависят от способа оплаты). "
                . "<br>Ссылка будет доступна после регистрации и оплаты.</li>"
                . "<li class='download_mess'>Разместите три понравившиеся ссылки с этого сайта в соцсетях и"
                . "<br>отправьте через обратную связь письмо со страницами, "
                . "<br>на которых вы разместили эти ссылки. После проверки вы получите файл.</li></ol></p>";
        if ($logon == 1){
            $result = $db1->select('unitpay_payments', "account='$account' AND status='1'", "account");
            $show = count($result);
            $pay = true;
        }
    }elseif ($myrow["notprohibit"] == '1') {
        $show = TRUE;
    }else{
        $show = (($myrow['view'] < 500)OR($myrow['view'] > 500)AND($logon == 1));
        if (!$show){
//            $dnldmess = get_foreign_equivalent("Ссылка доступна только для зарегистрированных посетителей!");
            $dnldmess = '<a target=blank_ title= "Откроется в новом окне" 
               href="http://localhost/softmaker.kz/register.php">
               Зарегестрируйтесь</a> или
            <a href="#enter">войдите</a>, чтобы получить ссылку на скачивание файла:<br>';    
        }
    }
    If ($show){
        require_once $DIR."billing/download.php";
    }else{
        if ($pay) {
           require_once $DIR."billing/pay.php";
        }  else {
            echo "<br><p align='center' class='post_add'><img class='down_off' align='left' src='".$deep."img/download.png'></p>";
            echo "<div style='padding-top: 12px; font-size: 8pt; font-weight: bold'>".$dnldmess."<em>\"$myrow[title]\"</em></div>";
        }
        ?>
        <p align="center">
        <img width="440" height="24" title="Доступные способы оплаты"
          alt="Способы оплаты на сайте"
          src="<?php echo $deep;?>billing/PaySystems.png">
        </p>
        <?
    }
}
$advs->show('bottom', $Link);
?>

  
