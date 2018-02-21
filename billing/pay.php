<?php
//pay.php
$download = filter_input(INPUT_POST, 'download');
//проверить отправлена ли форма логина
if(isset($download)) {
    require_once '../blocks/global.inc.php';
    $id = filter_input(INPUT_POST, 'id');
    $account = filter_input(INPUT_POST, 'account');
    $result = $db1->select('data', "id='$id'", "title,price");
    if(count($result)){
        $pay_title = mb_convert_encoding($result[title], "utf-8", "windows-1251");
        $ref = "https://unitpay.ru/pay/5036-af4e0?sum=$result[price]&account=$account&desc=$pay_title";
        header("Location: ".$ref);
    }else{
        //редирект на страницу приветствия    header("Location: welcome.php);
    //    header("Location: welcome.php?error=".$error."&lang=".$lang);
    }
}
?>
<p align="center"><em>Ссылка на скачивание появится после оплаты</em></p>
<form action="<?php echo $deep;?>billing/pay.php" method="post">
    <input name="id" id="id" type="hidden" value="<?php echo $myrow['id'];?>">
    <input name="account" id="account" type="hidden" value="<?php echo $account;?>">
    <p class='post_add' align="center"><input type="submit" name="download" value="<? echo get_foreign_equivalent("Оплатить")." $myrow[price] руб."; ?>" class="formbutton" ></p>
</form>


