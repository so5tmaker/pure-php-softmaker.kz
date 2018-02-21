<?php
    //настройка подключения к базе
    require('./db_mysql.php');
    $DB_site=new DB_sql;
    $DB_site->server='localhost';
    $DB_site->database='phpblog';
    $DB_site->user='bloguser';
    $DB_site->password='12345';
    $DB_site->reporterror=1;
    $DB_site->connect();

    //тестовое приложение "Угадай число"

    require('./cliapp.php');    //класс для взаимодействия с LiRu

    $action=$_GET['action'];

    //базовые константы приложения
    define('LIRU_APPNAME','acidworm');
    define('LIRU_APPBASE','/testapp2/');
    define('LIRU_SESSION_TABLE','testapp'); //в данном случае таблица расширена несколькими полями для использования логикой приложения

    //создаем объект класса CLiApp
    $test_app=new CLiApp;
    //метод перезагружает фреймсет или отдает запись из таблицы сессий приложения (LIRU_SESSION_TABLE)
    $testapp_a=$test_app->RefreshFrameset();

    //логика приложения

    if (empty($action))  //показать топ игроков и кнопку начало
    {
        //делаем топ
        $topusers_a=array();
        $SQL="SELECT * FROM testapp_results ORDER BY gpoints DESC LIMIT 10";
        $q=$DB_site->query($SQL);
        while($a=$DB_site->fetch_array($q)) $topusers_a[]=$a;

        //формируем страницу
        $content='<form id="GameStart">
            <input type="hidden" name="action" value="gostart">
            <input type="hidden" name="sessionhash" value="'.htmlspecialchars($testapp_a['sessionhash']).'">
            <input class="Hi" type="submit" value="Начать игру">
            </form>
            ';
        $game_start='<a href="javascript:document.getElementById(\'GameStart\').submit();">Поиграем?</a>';

        $content.='<h1 class="GlMarMdB">Топ 10 игр</h1>
            <table>
            <tr><th>Игрок</th><th>Набрано очков</th><th>Когда играл</th></tr>';

        foreach($topusers_a as $game_a)
        {
            $content.='<tr><td>'.htmlspecialchars($game_a['username']).
                '</td><td>'.$game_a['gpoints'].
                ' (попыток: '.$game_a['gtries'].
                ', затраченное время: '.strftime('%M:%S',$game_a['gtime']).
                '</td><td>'.strftime('%H:%M %d.%m.%Y',$game_a['time']).'</td></tr>';
        }

        $content.='</table>';
    }
    else
    {
        if ($action=='gostart') //инициализация игры
        {
            mt_srand(time()+$userid);
            $gnumber=mt_rand(1,100);
            $gstarttime=time();

            $SQL="UPDATE testapp
                SET gnumber=$gnumber,
                    gstarttime=$gstarttime,
                    gtries=0
                WHERE sessionhash='".addslashes($testapp_a['sessionhash'])."'";
            $DB_site->query($SQL);

            header("Location: testapp.php?action=showtry&sessionhash=$testapp_a[sessionhash]");
            exit();
        }
        else
        {
            if ($action=='processtry')
            {
                $unumber=intval($_GET['unumber']);


                $SQL="UPDATE testapp SET gtries=gtries+1 WHERE sessionhash='".addslashes($testapp_a['sessionhash'])."'";
                $DB_site->query($SQL);

                if ($unumber==$testapp_a['gnumber'])    //угадали! надо писать результат
                {
                    //обязательно проверить сессию что она действительна и наши userid/username назначены этой сессии
                    //в этом приложении это не имеет критического значения, но если понадобится записывать блоки в профиль
                    //или рассылать спам через механизмы лиру или еще чтото - сессия должна быть действующей
                    if ($test_app->CheckSession($testapp_a['sessionhash'],$testapp_a['userid']))
                    {
                        $gpoints=floor(10000/($testapp_a['gtries']+1)+1000/(time()-$testapp_a['gstarttime']));
                        $SQL="INSERT INTO testapp_results (userid, username, time, gtries, gtime, gpoints)
                            VALUES ($testapp_a[userid], '".addslashes($testapp_a['username'])."', ".time().", ".($testapp_a['gtries']+1).",
                            ".(time()-$testapp_a['gstarttime']).", $gpoints)";
                        $DB_site->query($SQL);
                    }
                    else
                    {
                        echo "Ошибка - сессия недействительна";
                        exit();
                    }
                }

                //формируем страницу
                if ($unumber>0)
                {
                    $content="<p>Вы ввели число $unumber. ";
                    if ($unumber<$testapp_a['gnumber']) $content.='Это число меньше загаданного';
                    else
                    {
                        if ($unumber>$testapp_a['gnumber']) $content.='Это число больше загаданного';
                        else
                        {
                            $content.="Это загаданное число!<br /> Вы получаете $gpoints очков.";
                            $finish=TRUE;

                            //запись в профиль, аватар, дневник и микроблог

                            $profilehtml="<h1>Угадай число</h1>
                                <p>Результат: $gpoints очков</p>
                                <p><a href=\"http://www.aguryanov.ru/testapp2/\">Играть самому</a></p>";

                            //попробовать сначала скрыто записать в профиль.
                            //чтобы получилось у пользователя должна стоять галочка "запись в профиль без уведомления"
                            if ($test_app->WriteProfileQuiet($testapp_a['sessionhash'],$testapp_a['userid'],$profilehtml)===TRUE)
                            {   //получилось записать в профиль самим. не пишем в профиль через пост-форму ниже.
                                // для этого достаточно очистить параметр 'profile'
                                $profilehtml='';
                            }

                            $avatarurl='http://www.aguryanov.ru/testapp2/testava.gif';

                            $journalhtml="<h1>Угадай число</h1>
                                <p>Результат: $gpoints очков</p>
                                <p><a href=\"http://www.aguryanov.ru/testapp2/\">Играть самому</a></p>";

                            $microbloghtml="Играл в игру и набрал $gpoints очков";

                            $resultform="<form action='".LIRU_APPBASE_PREFIX.LIRU_APPNAME."/testapp.php?param1=1' method='post' target='_top'>
                                    <input type='hidden' name='profile' value='".htmlspecialchars($profilehtml)."'>
                                    <input type='hidden' name='avatar' value='$avatarurl'>
                                    <input type='hidden' name='journal' value='".htmlspecialchars($journalhtml)."'>
                                    <input type='hidden' name='journaltitle' value='Результат игры &quot;Угадай число&quot;'>
                                    <input type='hidden' name='microblog' value='".htmlspecialchars($microbloghtml)."'>
                                    <span class='GlMarMdTB'><input type='submit' value='Сохранить все что можно'></span>
                                </form>";
                        }
                    }

                    $content.='</p>';
                }
            }
        }

        if (!$finish)
        {
//            $content.=$testapp_a['gnumber'];
            $content.=' <form>
                <input type="hidden" name="action" value="processtry">
                <input type="hidden" name="sessionhash" value="'.htmlspecialchars($testapp_a['sessionhash']).'">
                <strong class="GlMarSmB">Введите число:</strong> <input type="text" name="unumber" size=6>
                <input type="submit" value="Отправить">
                </form>';
        }
        else
        {
            $content.=$resultform;
            $content.='<a href="testapp.php?sessionhash='.htmlspecialchars($testapp_a['sessionhash']).'">Перейти в начало игры</a>';
        }
    }

    //вывести страницу добавив хидер-футер
    echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><link rel="stylesheet" href="reset.css" type="text/css"><link rel="stylesheet" href="style.css" type="text/css"></head><body>';
    echo '<h1>Привет, '.$testapp_a['username'].'! '.$game_start.'</h1>';
    echo '<div class="GlMarTB" id="AppHdr"><ul id="AppHdrNav"><li><a href="testapp.php?sessionhash='.htmlspecialchars($testapp_a['sessionhash']).'">К списку игроков</a></li><li><a href="testapp.php?sessionhash='.htmlspecialchars($testapp_a['sessionhash']).'&action=gostart">Начать заново</a></li></ul><div id="AppHdrLogo"><strong>Логотип</strong></div></div><div class="Bo"></div>';
    echo '<div class="GlPad">'.$content.'</div>';
    echo '</body></html>';
?>