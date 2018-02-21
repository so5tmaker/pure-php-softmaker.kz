<? // advs.php
header('Content-type: text/html; charset="windows-1251"');
include ("lock.php");
$details = array(table => "advs", action => "advs.php", name => "рекламы");
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

// делим файл на 6 частей: 3 с методом GET и 3 с методом POST
if ($mode){// 1-я часть 3 метода GET
    $admin->get_title($mode);
    if ($mode == 'new' OR $mode == 'edit') {// добавление нового значения
        if (!isset($GET['list']) AND $mode == 'edit'){
            $admin->get_list();
        } else {
            $row = $admin->getbyid($GET[id]);
            // заменю символы "<" и ">"
            $row[value] = str_replace(">", "", str_replace("<", "", $row[value]));
            $admin = new Admin($row, $details);
        ?>
        <form name="form1" method="post" action="<? echo $admin->action ?>">
        <p>
        <label>Введите название <? echo $admin->name ?> (не более 255 символов)<br>
         <input type="text" name="title" id="title" value="<? echo $row[title] ?>" size="<? echo $SizeOfinput ?>">
         </label>
        </p>
        <p>
        <?
        $date = ($row[date] == '') ? date("Y-m-d H:i:s",time()) : $row[date]; 
        ?>
        <label>Введите дату добавления <? echo $admin->name ?><br>
         <input type="text" name="date" id="date" value="<? echo $date ?>">
        </label>
        </p>
        <?
        $pages = array(articles => 'Статьи',
                index => 'Главная', feedback => 'Обратная связь',
                sitemap => 'Карта сайта', register => 'Регистрация пользователя',
                settings => 'Настройки пользователя', 
                resetpassword => 'Сброс пароля', 404 => 'Страница не найдена 404');
        ?>
        <p>
            <label>Введите страницу расположения вашей <? echo $admin->name ?> (не более 255 символов)<br>
                <select name='page[]' id='page' multiple size="<? echo count($pages) + 1 ?>" 
                        onchange="changePage();">
                    <option value='empty'>< Не выбрано ></option>
                <?
                $admin->get_opts($pages, explode(",", $row[page]));
                ?>
                </select>
            </label>
        </p>
        <p>
            <label>Введите область использования <? echo $admin->name ?> (только для страницы статей)<br>
                <select name='area' id='area' onchange="fill('area', '<? echo $SCRIPT ?>');">
                    <option value='empty'>< Не выбрано ></option>
                    <?
                    $areas = array(sections => 'Секция'  , categories => 'Категория',
                          data => 'Статья'
                        );
                    $admin->get_opts($areas, $row[area]);
                    ?>
                </select>
            </label><br>
        </p>
        <?
        $admin->get_area_opts();
        ?>    
        <!--сюда заносится значение id выбранной секции, категории или статьи-->
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
            Применить этот блок <? echo $admin->name ?> к статьям
            </label>
        </p>
        <p>
            <label>Введите полный текст <? echo $admin->name ?>
            <textarea id="block" name="block" cols="<? echo $ColsOfarea ?>" rows="22"><?echo $row[block];?></textarea>
            </label>
        </p>
        <p>
            <label>Введите текст, который будет замещен блоком <? echo $admin->name ?> (не более 255 символов,
                если текст пустой, то реклама будет выведена только в секции или категории, 
                если заполнен, то бданный блок рекламы будет выведен только в тексте статьи)<br>
                <input type="text" name="tag" id="tag" value="<? echo $row[tag] ?>" size="<? echo $SizeOfinput ?>">
            </label>
        </p>
        <? $rep = ($row[rep] == 0) ? 1 : $row[rep]; ?>
        <p>
            <label>Число повторений блока <? echo $admin->name ?> в тексте статьи (числовой, не более 1-го символа 1-9,
                по умолчанию один)<br>
            <input type="text" name="rep" id="rep" value="<? echo $rep ?>" size="<? echo $SizeOfinput ?>">
            
            </label>
        </p>
        <?
        $positions = array(top => 'Верхний блок'  , center => 'Центральный блок',
                  bottom => 'Нижний блок', right => 'Правый блок', 
                  left => 'Левый блок');
        ?>
        <p>
            <label>Введите место расположения вашей <? echo $admin->name ?> (не более 255 символов)<br>
                <select name='position[]' id='position' multiple size="<? echo count($positions) + 1 ?>">
                    <option value='empty'>< Не выбрано ></option>
                <?
                $admin->get_opts($positions, explode(",", $row[position]));
                ?>
                </select>
            </label>
        </p>
        <? $priority = ($row[priority] == '') ? 0 : $row[priority]; ?>
        <p>
            <label>Приоритет блока <? echo $admin->name ?> (числовой, не более 4-х символов,
                если на одном месте несколько блоков рекламы, 
                то от приоритета зависит каким по счету будет данный блок,
                если блок для секции или категории, то число будет обозначать после какой статьи выводить данный блок)<br>
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
            Включить этот блок <? echo $admin->name ?>
            </label>
        </p>
        <input type="hidden" name="mode" value="<? echo $mode ?>">
        <input type="hidden" name="id" value="<? echo $row[id] ?>">
        <p>
          <label>
          <input type="submit" name="submit" id="submit" value="Занесение <? echo $admin->name ?> в базу">
          </label>
        </p>
        </form>
        <a id="dele">процесс установки платформы 1С:Предприятие 8</a>
        <?     
        }
    }elseif ($mode == 'del') {// удаление значения
        $admin->get_list();
    }
 }//if (isset($mode))
 
if (isset($postmode)){// 2-я часть 3 метода POST
    $admin->get_title();
    $admin->save();
}
include_once ("footer.html");?>
