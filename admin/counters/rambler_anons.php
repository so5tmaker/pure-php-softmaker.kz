<?php
if ($lang == "RU") {
    $id_value = "2180861";
    $code_value = "3432495d5e947000908b03a1c41f6961";
} else {
    $id_value = "2954116";
    $code_value = "1555feb5da96b99982d866d11d5140f2";
}
?>
<form name='form1' method='post' action='http://top100.rambler.ru/cgi-bin/set_title.cgi'>
    <input name="id" type="hidden" value="<? echo $id_value ?>">
    <input name="code" type="hidden" value="<? echo $code_value ?>">
     <p>
       <label>Введите название анонса <? echo $name_dt ?> в каталоге Rambler<br>
       <input value="<? echo iconv("windows-1251", "koi8-r", $myrow[meta_d]); ?>" type="text" name="announce" id="announce" size="<? echo $SizeOfinput ?>">
       </label>
     </p>
     <p>
       <label>Введите URL анонсируемой <? echo $name_dt ?><br>
       <input value="<? echo $link ?>" type="text" name="announce_url" id="announce_url" size="<? echo $SizeOfinput ?>">
       </label>
     </p>
     <p> <input name="submit" type="submit" value="Добавить анонс <? echo $name_dt;?>"></p>
</form>