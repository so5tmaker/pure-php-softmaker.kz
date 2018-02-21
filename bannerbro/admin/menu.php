<div id="menu_container">
    <div id="menu">
        <ul class="menu-top">
            <li>
                <a href="/bannerbro/admin/index.php" class="menu-button"><span class="menu-label">Главная</span></a>
            </li>
            <li>
                <a href="#" class="menu-button menu-drop"><span class="menu-label">Заказы</span></a>
                <div class="menu-dropdown menu-dropdown1">
                    <ul class="menu-sub">        
	<?php
		connectToDB();
		$ShowSetting=mysql_query("SELECT form,Method FROM Admin", $link) or die (mysql_error($link));
		$ShowUserInfo=mysql_query("SELECT * FROM Users", $link) or die (mysql_error($link));
		$data_menu = mysql_fetch_array($ShowSetting);
		$data_user = mysql_fetch_array($ShowUserInfo);
		$Name_Admin=$data_menu['form'];
		$Email_Admin=$data_user['Email'];
		if ($data_menu['Method']==0)
			{		
				echo'<li><a href="/bannerbro/admin/orders.php?set=1" class="menu-subbutton"><span class="menu-label">На проверку</span></a></li>'; 
				echo'<li><a href="/bannerbro/admin/orders.php?set=2" class="menu-subbutton"><span class="menu-label">Ожидают оплаты</span></a></li>';
			}
		if ($data_menu['Method']==1)
			{	
				echo'<li><a href="/bannerbro/admin/orders.php?set=1" class="menu-subbutton"><span class="menu-label">Ожидают оплаты</span></a></li>'; 
				echo'<li><a href="/bannerbro/admin/orders.php?set=2" class="menu-subbutton"><span class="menu-label">На проверку</span></a></li>';
			}
		if ($data_menu['Method']==2)
			{	
				echo'<li><a href="/bannerbro/admin/orders.php?set=2" class="menu-subbutton"><span class="menu-label">Ожидают оплаты</span></a></li>';
			}			
	?>						      
                        <li><a href="/bannerbro/admin/orders.php?set=3" class="menu-subbutton"><span class="menu-label">Действующие</span></a></li>
                        <li><a href="/bannerbro/admin/orders.php?set=4" class="menu-subbutton"><span class="menu-label">Законченные</span></a></li>
                        <li><a href="/bannerbro/admin/reban.php" class="menu-subbutton"><span class="menu-label">На замену</span></a></li>
                        <li><a href="/bannerbro/admin/queue.php" class="menu-subbutton"><span class="menu-label">В очереди</span></a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="#" class="menu-button menu-drop"><span class="menu-label">Настройки</span></a>
                <div class="menu-dropdown menu-dropdown1">
                    <ul class="menu-sub">
                        <li><a href="/bannerbro/admin/setting.php" class="menu-subbutton"><span class="menu-label">Общие</span></a></li>
                        <li><a href="/bannerbro/admin/position.php" class="menu-subbutton"><span class="menu-label">Позиции</span></a></li>
                        <li><a href="/bannerbro/admin/discount.php" class="menu-subbutton"><span class="menu-label">Скидки</span></a></li>
                        <li><a href="/bannerbro/admin/mail.php" class="menu-subbutton"><span class="menu-label">Почта</span></a></li>
                        <li><a href="/bannerbro/admin/goodbad.php" class="menu-subbutton"><span class="menu-label">Шаблоны писем</span></a></li>
						<li><a href="/bannerbro/admin/look.php" class="menu-subbutton"><span class="menu-label">Редактор</span></a></li>
                        <li><a href="/bannerbro/admin/pay.php" class="menu-subbutton"><span class="menu-label">Оплата</span></a></li>
						<li><a href="/bannerbro/admin/moderat.php" class="menu-subbutton"><span class="menu-label">Модераторы</span></a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="/bannerbro/admin/users.php" class="menu-button"><span class="menu-label">Клиенты</span></a>
            </li>
            <li>
                <a href="/bannerbro/" class="menu-button" target="_blank"><span class="menu-label">Страница продажи</span></a>
            </li>
            <li>
                <a href="http://bannerbro.ru/service" class="menu-button" target="_blank"><span class="menu-label">БаннерБро</span></a>
            </li>
            <li>
                <a href="http://bannerbro.ru/service/faq.php" class="menu-button" target="_blank"><span class="menu-label">FAQ</span></a>
            </li>			
        </ul>
    </div>
    <div class="username">
	<?php
		$MyAccess=explode("/", $_SESSION["Access"]);
		
		if ($MyAccess[0]!="2")
		{
			echo '<span>Вы зашли как <span class="moder">Модератор</span>&nbsp;<a class="exit" href="logout.php">[Выйти]</a></span>';
		} 
		else { 
			echo '<span>Вы зашли как <span class="admin">Администратор</span>&nbsp;<a class="exit" href="logout.php">[Выйти]</a></span>';
		}
	?>
    </div>
</div>
<div class="clear"></div>