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
    <p class="pdf">������� ������� � ������� ��� ������ PDF-�������:<br>
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
            <a target=blank_ title= "��������� � ����� ����" 
               href="http://localhost/softmaker.kz/register.php">
               �����������������</a> ���
            <a href="#enter">�������</a>, ����� ������� ������� � ������� ��� ������ PDF-�������:<br>
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
        $dnldmess = "<p class='download_mess'>*�������� ��� ������� ��������� �����:"
                . "<ol><li class='download_mess'>����: $price ���. + % �� ������ (% ������� �� ������� ������). "
                . "<br>������ ����� �������� ����� ����������� � ������.</li>"
                . "<li class='download_mess'>���������� ��� ������������� ������ � ����� ����� � �������� �"
                . "<br>��������� ����� �������� ����� ������ �� ����������, "
                . "<br>�� ������� �� ���������� ��� ������. ����� �������� �� �������� ����.</li></ol></p>";
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
//            $dnldmess = get_foreign_equivalent("������ �������� ������ ��� ������������������ �����������!");
            $dnldmess = '<a target=blank_ title= "��������� � ����� ����" 
               href="http://localhost/softmaker.kz/register.php">
               �����������������</a> ���
            <a href="#enter">�������</a>, ����� �������� ������ �� ���������� �����:<br>';    
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
        <img width="440" height="24" title="��������� ������� ������"
          alt="������� ������ �� �����"
          src="<?php echo $deep;?>billing/PaySystems.png">
        </p>
        <?
    }
}
$advs->show('bottom', $Link);
?>

  
