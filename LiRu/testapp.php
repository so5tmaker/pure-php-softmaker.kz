<?php
    //��������� ����������� � ����
    require('./db_mysql.php');
    $DB_site=new DB_sql;
    $DB_site->server='localhost';
    $DB_site->database='phpblog';
    $DB_site->user='bloguser';
    $DB_site->password='12345';
    $DB_site->reporterror=1;
    $DB_site->connect();

    //�������� ���������� "������ �����"

    require('./cliapp.php');    //����� ��� �������������� � LiRu

    $action=$_GET['action'];

    //������� ��������� ����������
    define('LIRU_APPNAME','acidworm');
    define('LIRU_APPBASE','/testapp2/');
    define('LIRU_SESSION_TABLE','testapp'); //� ������ ������ ������� ��������� ����������� ������ ��� ������������� ������� ����������

    //������� ������ ������ CLiApp
    $test_app=new CLiApp;
    //����� ������������� �������� ��� ������ ������ �� ������� ������ ���������� (LIRU_SESSION_TABLE)
    $testapp_a=$test_app->RefreshFrameset();

    //������ ����������

    if (empty($action))  //�������� ��� ������� � ������ ������
    {
        //������ ���
        $topusers_a=array();
        $SQL="SELECT * FROM testapp_results ORDER BY gpoints DESC LIMIT 10";
        $q=$DB_site->query($SQL);
        while($a=$DB_site->fetch_array($q)) $topusers_a[]=$a;

        //��������� ��������
        $content='<form id="GameStart">
            <input type="hidden" name="action" value="gostart">
            <input type="hidden" name="sessionhash" value="'.htmlspecialchars($testapp_a['sessionhash']).'">
            <input class="Hi" type="submit" value="������ ����">
            </form>
            ';
        $game_start='<a href="javascript:document.getElementById(\'GameStart\').submit();">��������?</a>';

        $content.='<h1 class="GlMarMdB">��� 10 ���</h1>
            <table>
            <tr><th>�����</th><th>������� �����</th><th>����� �����</th></tr>';

        foreach($topusers_a as $game_a)
        {
            $content.='<tr><td>'.htmlspecialchars($game_a['username']).
                '</td><td>'.$game_a['gpoints'].
                ' (�������: '.$game_a['gtries'].
                ', ����������� �����: '.strftime('%M:%S',$game_a['gtime']).
                '</td><td>'.strftime('%H:%M %d.%m.%Y',$game_a['time']).'</td></tr>';
        }

        $content.='</table>';
    }
    else
    {
        if ($action=='gostart') //������������� ����
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

                if ($unumber==$testapp_a['gnumber'])    //�������! ���� ������ ���������
                {
                    //����������� ��������� ������ ��� ��� ������������� � ���� userid/username ��������� ���� ������
                    //� ���� ���������� ��� �� ����� ������������ ��������, �� ���� ����������� ���������� ����� � �������
                    //��� ��������� ���� ����� ��������� ���� ��� ��� ����� - ������ ������ ���� �����������
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
                        echo "������ - ������ ���������������";
                        exit();
                    }
                }

                //��������� ��������
                if ($unumber>0)
                {
                    $content="<p>�� ����� ����� $unumber. ";
                    if ($unumber<$testapp_a['gnumber']) $content.='��� ����� ������ �����������';
                    else
                    {
                        if ($unumber>$testapp_a['gnumber']) $content.='��� ����� ������ �����������';
                        else
                        {
                            $content.="��� ���������� �����!<br /> �� ��������� $gpoints �����.";
                            $finish=TRUE;

                            //������ � �������, ������, ������� � ���������

                            $profilehtml="<h1>������ �����</h1>
                                <p>���������: $gpoints �����</p>
                                <p><a href=\"http://www.aguryanov.ru/testapp2/\">������ ������</a></p>";

                            //����������� ������� ������ �������� � �������.
                            //����� ���������� � ������������ ������ ������ ������� "������ � ������� ��� �����������"
                            if ($test_app->WriteProfileQuiet($testapp_a['sessionhash'],$testapp_a['userid'],$profilehtml)===TRUE)
                            {   //���������� �������� � ������� �����. �� ����� � ������� ����� ����-����� ����.
                                // ��� ����� ���������� �������� �������� 'profile'
                                $profilehtml='';
                            }

                            $avatarurl='http://www.aguryanov.ru/testapp2/testava.gif';

                            $journalhtml="<h1>������ �����</h1>
                                <p>���������: $gpoints �����</p>
                                <p><a href=\"http://www.aguryanov.ru/testapp2/\">������ ������</a></p>";

                            $microbloghtml="����� � ���� � ������ $gpoints �����";

                            $resultform="<form action='".LIRU_APPBASE_PREFIX.LIRU_APPNAME."/testapp.php?param1=1' method='post' target='_top'>
                                    <input type='hidden' name='profile' value='".htmlspecialchars($profilehtml)."'>
                                    <input type='hidden' name='avatar' value='$avatarurl'>
                                    <input type='hidden' name='journal' value='".htmlspecialchars($journalhtml)."'>
                                    <input type='hidden' name='journaltitle' value='��������� ���� &quot;������ �����&quot;'>
                                    <input type='hidden' name='microblog' value='".htmlspecialchars($microbloghtml)."'>
                                    <span class='GlMarMdTB'><input type='submit' value='��������� ��� ��� �����'></span>
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
                <strong class="GlMarSmB">������� �����:</strong> <input type="text" name="unumber" size=6>
                <input type="submit" value="���������">
                </form>';
        }
        else
        {
            $content.=$resultform;
            $content.='<a href="testapp.php?sessionhash='.htmlspecialchars($testapp_a['sessionhash']).'">������� � ������ ����</a>';
        }
    }

    //������� �������� ������� �����-�����
    echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><link rel="stylesheet" href="reset.css" type="text/css"><link rel="stylesheet" href="style.css" type="text/css"></head><body>';
    echo '<h1>������, '.$testapp_a['username'].'! '.$game_start.'</h1>';
    echo '<div class="GlMarTB" id="AppHdr"><ul id="AppHdrNav"><li><a href="testapp.php?sessionhash='.htmlspecialchars($testapp_a['sessionhash']).'">� ������ �������</a></li><li><a href="testapp.php?sessionhash='.htmlspecialchars($testapp_a['sessionhash']).'&action=gostart">������ ������</a></li></ul><div id="AppHdrLogo"><strong>�������</strong></div></div><div class="Bo"></div>';
    echo '<div class="GlPad">'.$content.'</div>';
    echo '</body></html>';
?>