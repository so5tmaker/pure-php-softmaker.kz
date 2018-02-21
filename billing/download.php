<?php
//download.php
$download = filter_input(INPUT_POST, 'download');
//проверить отправлена ли форма логина
if(isset($download)) {
    require_once '../blocks/global.inc.php';
    $id = filter_input(INPUT_POST, 'id');
    $result = $db1->select('data', "id='$id'", "file");
    if(count($result)){
        $ref = $rest_."/".$result["file"];
        header("Location: ".$ref);
    }else{
        //редирект на страницу приветствия    header("Location: welcome.php);
    //    header("Location: welcome.php?error=".$error."&lang=".$lang);
    }
}
?>
<br>
<form action="<?php echo $deep;?>billing/download.php" method="post">
    <input name="id" id="id" type="hidden" value="<?php echo $myrow['id'];?>">
    <p class='post_add' align="center">
        <input style="width: 728px;" type="submit" name="download" 
        value="<? echo get_foreign_equivalent("Скачать")." ".$myrow[title]; ?>" 
        class="formbutton" >
    </p>
</form>


