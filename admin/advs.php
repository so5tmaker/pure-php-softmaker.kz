<? // advs.php
header('Content-type: text/html; charset="windows-1251"');
include ("lock.php");
$details = array(table => "advs", action => "advs.php", name => "�������");
$GET = filter_input_array(INPUT_GET);
$POST = filter_input_array(INPUT_POST);
$mode = $GET['mode'];
$postmode = $POST['mode'];
$submit = $POST['submit'];
$POSTGET = (isset($GET)) ? $GET : $POST;
$admin = new Admin($POSTGET, $details);
$row = $admin->data;
if (!isset($submit) AND !isset($mode)){
    $jqres = $admin->jQueryOpt($POST);
    if ($jqres){
        echo $jqres;
    }
}

// ����� ���� �� 6 ������: 3 � ������� GET � 3 � ������� POST
if ($mode){// 1-� ����� 3 ������ GET
    $admin->get_title($mode);
    if ($mode == 'new' OR $mode == 'edit') {// ���������� ������ ��������
        if (!isset($GET['list']) AND $mode == 'edit'){
            $admin->get_list();
        } else {
            $row = $admin->getbyid($GET[id]);
            // ������ ������� "<" � ">"
            $row[value] = str_replace(">", "", str_replace("<", "", $row[value]));
            $admin = new Admin($row, $details);
        ?>
        <form name="form1" method="post" action="<? echo $admin->action ?>">
        <p>
        <label>������� �������� <? echo $admin->name ?> (�� ����� 255 ��������)<br>
         <input type="text" name="title" id="title" value="<? echo $row[title] ?>" size="<? echo $SizeOfinput ?>">
         </label>
        </p>
        <p>
        <?
        $date = ($row[date] == '') ? date("Y-m-d H:i:s",time()) : $row[date]; 
        ?>
        <label>������� ���� ���������� <? echo $admin->name ?><br>
         <input type="text" name="date" id="date" value="<? echo $date ?>">
        </label>
        </p>
        <?
        $pages = array(articles => '������',
                index => '�������', feedback => '�������� �����',
                sitemap => '����� �����', register => '����������� ������������',
                settings => '��������� ������������', 
                resetpassword => '����� ������', 404 => '�������� �� ������� 404');
        ?>
        <p>
            <label>������� �������� ������������ ����� <? echo $admin->name ?> (�� ����� 255 ��������)<br>
                <select name='page[]' id='page' multiple size="<? echo count($pages) + 1 ?>" 
                        onchange="changePage();">
                    <option value='empty'>< �� ������� ></option>
                <?
                $admin->get_opts($pages, explode(",", $row[page]));
                ?>
                </select>
            </label>
        </p>
        <p>
            <label>������� ������� ������������� <? echo $admin->name ?> (������ ��� �������� ������)<br>
                <select name='area' id='area' onchange="fill('area', '<? echo $SCRIPT ?>');">
                    <option value='empty'>< �� ������� ></option>
                    <?
                    $areas = array(sections => '������'  , categories => '���������',
                          data => '������'
                        );
                    $admin->get_opts($areas, $row[area]);
                    ?>
                </select>
            </label><br>
        </p>
        <?
        $admin->get_area_opts();
        ?>    
        <!--���� ��������� �������� id ��������� ������, ��������� ��� ������-->
        <input type="hidden" name="value" id='value' value="<? echo $row[value] ?>" size="<? echo $SizeOfinput ?>">
        
        <?
        if ($row[applyart]){
            $applyart = "checked";
        }else{
            $applyart = "";
        }
        ?>
        <p>
            <label>
            <input type="checkbox" name="applyart" id="applyart" <? echo $applyart ?>>
            ��������� ���� ���� <? echo $admin->name ?> � �������
            </label>
        </p>
        <p>
            <label>������� ������ ����� <? echo $admin->name ?>
            <textarea id="block" name="block" cols="<? echo $ColsOfarea ?>" rows="22"><?echo $row[block];?></textarea>
            </label>
        </p>
        <p>
            <label>������� �����, ������� ����� ������� ������ <? echo $admin->name ?> (�� ����� 255 ��������,
                ���� ����� ������, �� ������� ����� �������� ������ � ������ ��� ���������, 
                ���� ��������, �� ������� ���� ������� ����� ������� ������ � ������ ������)<br>
                <input type="text" name="tag" id="tag" value="<? echo $row[tag] ?>" size="<? echo $SizeOfinput ?>">
            </label>
        </p>
        <? $rep = ($row[rep] == 0) ? 1 : $row[rep]; ?>
        <p>
            <label>����� ���������� ����� <? echo $admin->name ?> � ������ ������ (��������, �� ����� 1-�� ������� 1-9,
                �� ��������� ����)<br>
            <input type="text" name="rep" id="rep" value="<? echo $rep ?>" size="<? echo $SizeOfinput ?>">
            
            </label>
        </p>
        <?
        $positions = array(top => '������� ����'  , center => '����������� ����',
                  bottom => '������ ����', right => '������ ����', 
                  left => '����� ����');
        ?>
        <p>
            <label>������� ����� ������������ ����� <? echo $admin->name ?> (�� ����� 255 ��������)<br>
                <select name='position[]' id='position' multiple size="<? echo count($positions) + 1 ?>">
                    <option value='empty'>< �� ������� ></option>
                <?
                $admin->get_opts($positions, explode(",", $row[position]));
                ?>
                </select>
            </label>
        </p>
        <? $priority = ($row[priority] == '') ? 0 : $row[priority]; ?>
        <p>
            <label>��������� ����� <? echo $admin->name ?> (��������, �� ����� 4-� ��������,
                ���� �� ����� ����� ��������� ������ �������, 
                �� �� ���������� ������� ����� �� ����� ����� ������ ����,
                ���� ���� ��� ������ ��� ���������, �� ����� ����� ���������� ����� ����� ������ �������� ������ ����)<br>
            <input type="text" name="priority" id="priority" value="<? echo $priority ?>" size="<? echo $SizeOfinput ?>">
            
            </label>
        </p>
        <?
        if ($row[turnon]){
            $turnon = "checked";
        }else{
            $turnon = "";
        }
        ?>
        <p>
            <label>
            <input type="checkbox" name="turnon" id="turnon" <? echo $turnon ?>>
            �������� ���� ���� <? echo $admin->name ?>
            </label>
        </p>
        <input type="hidden" name="mode" value="<? echo $mode ?>">
        <input type="hidden" name="id" value="<? echo $row[id] ?>">
        <p>
          <label>
          <input type="submit" name="submit" id="submit" value="��������� <? echo $admin->name ?> � ����">
          </label>
        </p>
        </form>
        <a id="dele">������� ��������� ��������� 1�:����������� 8</a>
        <?     
        }
    }elseif ($mode == 'del') {// �������� ��������
        $admin->get_list();
    }
 }//if (isset($mode))
 
if (isset($postmode)){// 2-� ����� 3 ������ POST
    $admin->get_title();
    $admin->save();
}
include_once ("footer.html");?>
