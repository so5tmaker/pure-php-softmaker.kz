<?php
connectToDB();
$GetInfoAdmin=mysql_query("SELECT * FROM Admin",$link)  or die (mysql_error($link));
$data_info = mysql_fetch_array($GetInfoAdmin);

?>

<ul class="bottom_menu ta-c">
    <li><a data-lightbox="on" href="#how">Как это работает</a></li>
    <li><a data-lightbox="on" href="#forbidden">Что запрещено рекламировать</a></li>
    <li><a data-lightbox="on" href="#security">Политика безопасности</a></li>
    <li><a data-lightbox="on" href="#contacts">Контактные данные</a></li>
</ul>

<div class="payments ta-c">
    <img src="images/mastercard.jpg" alt=""/>
    <img src="images/qiwi.jpg" alt=""/>
    <img src="images/visa.jpg" alt=""/>
    <!--<a href="https://www.z-payment.com/?partner=ZP50932601" target="_blank"><img src="images/z-payment.jpg" alt=""/></a>-->

    <span class="nolink" onclick="GoTo('_www.z-payment.com/?partner=ZP50932601','s')"><span><img src="images/z-payment.jpg" alt=""/></span></span>
	
	<?php
	if ($data_info['WhatWM']!='')
		{
			echo  '<a href="'.$data_info['WhatWM'].'" target="_blank"><img src="images/webmoney.jpg" alt=""/></a>';
		} 
		else
		{
			echo  '<img src="images/webmoney.jpg" alt=""/>';
		}
		
	if ($data_info['WhatYA']!='')
		{
			echo  '<a href="'.$data_info['WhatYA'].'" target="_blank"><img src="images/yandex.jpg" alt=""/></a>';
		} 
		else
		{
			echo  ' <img src="images/yandex.jpg" alt=""/>';
		}	
	?>
</div>

<?php include 'copyright.php'; ?>

<!-- Как это работает -->

<div style="display: none;">
    <div class="popup" id="how">
        <div class="popup_header ta-l">
            Как это работает
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <?php echo $data_info['WhatWork']; ?>
        </div>
    </div>
</div>

<!-- Что запрещено рекламировать -->

<div style="display: none;">
    <div class="popup" id="forbidden">
        <div class="popup_header ta-l">
            Что запрещено рекламировать
            <div class="clear"></div>
        </div>
        <div class="popup_container">
            <?php echo $data_info['WhatNot']; ?>
        </div>
    </div>
</div>

<!-- Политика безопасности -->

<div style="display: none;">
    <div class="popup" id="security">
        <div class="popup_header ta-l">
            Политика безопасности
            <div class="clear"></div>
        </div>
        <div class="popup_container">
			<?php echo $data_info['WhatSave']; ?>
        </div>
    </div>
</div>

<!-- Контактные данные -->

<div style="display: none;">
    <div class="popup" id="contacts">
        <div class="popup_header ta-l">
            Контактные данные
            <div class="clear"></div>
        </div>
        <div class="popup_container">
		

            <p>Сайт: <span class="hidden-link" data-link="<?php echo $data_info['WhatSite']; ?>"><?php echo $data_info['WhatSite']; ?></span></p>
            <p>e-mail: <?php echo $data_info['WhatEmail']; ?></p>
            <p>телефон: <?php echo $data_info['WhatTel']; ?></p>
            <p>WMID: <?php echo $data_info['WhatWMID']; ?></p>
            <p>Служба поддержки - <?php echo $data_info['WhatSup']; ?></a></p>
			<p>Адрес: <?php echo $data_info['WhatAdres']; ?></p>
			
        </div>
    </div>
</div>
<script>
$('.hidden-link').click(function(){window.open($(this).data('link'));return false;});
</script>
