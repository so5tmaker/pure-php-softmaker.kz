  <?php 
//    if($lang=='RU') {$width = '20';} else {$width = '25';}
    $width = '16%';
    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width='<?php echo $width;?>%' 
    <?php 
    if(isset($n)) {
	if ($n==1) {
            echo "class='nav_a'"; 
	}
	else {
            echo "class='nav_t'"; 
	}
    }
    ?>>
    <strong><a href="<?php echo $rest_;?>/"><? echo get_foreign_equivalent("Главная"); ?></a></strong></td>
    <td width='<?php echo $width;?>' <?php if (isset($n)) {if ($n==2) echo "class='nav_a'"; else echo "class='nav_t'"; }?> ><strong><a href="<? echo $rest_; ?>/articles/"><? echo get_foreign_equivalent("Каталог статей"); ?></a></strong></td>
    <td width='<?php echo $width;?>' <?php if (isset($n)) {if ($n==3) echo "class='nav_a'"; else echo "class='nav_t'"; }?>><strong><a href="<? echo $rest_; ?>/files/"><? echo get_foreign_equivalent("Каталог файлов"); ?></a></strong></td>
    <td width='<?php echo $width;?>' <?php echo "class='nav_t'"; ?>><strong><a target="_blank" href="<? echo $rest_; ?>/bannerbro/index.php"><? echo "Реклама" ?></a></strong></td>
    <td width='<?php echo $width;?>' <?php if (isset($n)) {if ($n==4) echo "class='nav_a'"; else echo "class='nav_t'"; }?>><strong><a href="<? echo $rest_; ?>/mail/feedback.php"><? echo get_foreign_equivalent("Обратная связь"); ?></a></strong></td>
    <td width='<?php echo $width;?>' <?php if (isset($n)) {if ($n==5) echo "class='nav_a'"; else echo "class='nav_t'"; }?>><strong><a href="<? echo $rest_; ?>/sitemap.php"><? echo get_foreign_equivalent("Карта сайта"); ?></a></strong></td>
    <?php
    if($lang=='KZ'){
    print <<<HERE
        <td width='$width' class='nav_t'><strong><a href="forum">Форум</a></strong></td>
HERE;
    }   
    ?>
  </tr>
</table>