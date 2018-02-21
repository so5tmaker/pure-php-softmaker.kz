<?php
//Admin.class.php

class Admin {
    public $name;
    public $action;
    public $table;
    
    protected $areas  = array("sections", "categories", "data");
    
    protected $tbla; // содержит массив соответствия таблицам базы и значениям ссылки по имени
    protected $tblb; // содержит массив соответствия таблицам базы и значениям ссылки по имени

    protected $mode;
    protected $modes;
    protected $columns;
    public $data;
    
    protected $db1; // содержит объект базы

    //Конструктор вызывается при создании нового объекта
    //Takes an associative array with the DB row as an argument.
    function __construct($data, $details) {
        global $db1;
        foreach($details as $k => $v) {      
            $this->{$k} = (isset($v)) ? $v : "";
        }
        $result = $db1->columns($details[table]);
        foreach($result as $k => $v) {
            $row = (isset($data[$v])) ? $data[$v] : "";
            $val = is_array($row) ? implode(",", $row) : $row;
            $this->data[$v] = ($val === "on") ? 1 : addslashes($val);
        }
        $this->columns = $result;
        $this->set_tbla();
        $this->db1 = $db1;
        $this->mode = $data[mode];
    }
    
    // установим соответствия таблицам базы и поиску по id
    private function set_tbla() {
        $this->modes['new']  = array('добавления', 'Добавление');   
        $this->modes['edit'] = array('редактирования', 'Редактирование');
        $this->modes['del']  = array('удаления', 'Удаление');
        
        $this->tbla['sections']   = array("id", "секции");
        $this->tbla['categories'] = array("sec", "категории");
        $this->tbla['data']       = array("cat", "статьи");
        foreach($this->areas as $k => $v) {
            $this->tblb[$v] = $this->tbla[$v];
            if ($this->data[area] == $v) {
                return;
            }
        }
    }
    
    // Получу заголовок страницы согласно режиму 
    function get_title() {
        global $title_here;
        $jQuery = TRUE;
        $value = $this->modes[$this->mode];
        $title_here = "Страница $value[0] ".$this->name; 
        require_once("header.html");
        echo "<h3 align='center'>$value[1] ".$this->name."</h3>";
    }
    
    // Получу список записей из базы в виде ссылок 
    function get_list($value = '') {
        $value = ($value == '') ? 'id<>0 ORDER BY `turnon` DESC , `title`' : "id='$value'";
        $result = $this->db1->select($this->table, $value, 'id,title,turnon', FALSE);
        if ($this->mode == 'edit') {// редактирование записи 
            foreach($result as $k => $myrow) {
                $turnon = ($myrow[turnon]) ? ' Вкл.' : ' Выкл.';
                printf ("<p><a href='%s?id=%s&mode=edit&list'>%s</a></p>",
                        $this->action,$myrow["id"],$myrow["title"].$turnon);
            }
        }elseif ($this->mode == 'del') {// удаление записи   
        ?>
        <p><strong>Выберите элемент для удаления <? echo $this->name;?></strong></p>
        <form action="<? echo $this->action;?>" method="post">
        <?
            foreach($result as $k => $myrow) {
                printf ("<p><input name='id' type='radio' value='%s'>"
                        . "<label> %s</label></p>",
                        $myrow["id"],$myrow["title"]);
            }
        ?>
            <input name="mode" type="hidden" value="del">
            <p>
                <input name="submit" type="submit" value="Удаление <? echo $this->name;?>">
            </p>
        </form>
        <?
        }
    }
    
    // получаю запись по id
    public function getbyid($id) {
        if (!isset($id)){return;}
        return $this->db1->select($this->table, "id='$id'");
    }
    
    public function jQueryOpt($POST) {
        $area  = $POST[area];
        $where = $POST[where];
        if (isset($area)){
            $where = ($where !== '') ? $this->tbla[$area][0]."='$where'" : 'id<>0' ;
            $result = $this->db1->select($area, "$where ORDER BY title ASC", 'id, title, lang', FALSE);
            return $this->get_opts($result, '', true);
        }
    }
    
    // Получу значения списка и выделю текущее
    public function get_opts($opts, $val, $get = false) {
        global $SizeOfinput;
        $ret = "<option value='empty'>< Не выбрано ></option>";
        foreach($opts as $k => $v) {   
            if ($get){
                if (is_array($val)){
                    $selected = in_array($v[id], $val) ? 'selected' : '' ;
                } else {
                    $selected = ($val == $v[id]) ? 'selected' : '' ;
                }
                $size = substr($v[title], 0, $SizeOfinput);
                $ret .= "<option value='$v[id]' $selected>"
                    .$size." ($v[lang])"
                    . "</option>";
            } else {
                if (is_array($val)){
                    $selected = in_array($k, $val) ? 'selected' : '' ;
                } else {
                    $selected = ($val == $k) ? 'selected' : '' ;
                }
                echo "<option value='$k' $selected>$v</option>";
            }
        }
        if($get){return $ret;}
    } // get_opts
    
    public function print_select($area, $options = '') {
        global $SCRIPT;
        $display = ($options) ? '' : "style='display: none;'";
        // получу колво выбранных элементов
        $selCount = substr_count($options, 'selected');
        // получу общее колво элементов
        $optCount = substr_count($options, '</option>');
        $mult = ($selCount > 1) ? "multiple size='$optCount'" : '';
        $value = $this->tbla[$area][1];
        ?>
        <p id='p<?echo $area ?>' <?echo $display ?>>
            <label>Введите значение <? echo $value.' '.$this->name ?><br>
                <select name='<? echo $area ?>' id='<? echo $area ?>' <? echo $mult ?> onchange="fill('<? echo $area ?>', '<? echo $SCRIPT ?>');">
                    <? echo $options ?>
                </select>
            </label><br>
        </p>
        <?
    } // print_select

    // Получаю значения выпадающих списков
    public function get_area_opts() {
        if ($this->data[area] === ""){
            foreach($this->areas as $k => $v) {
                $this->print_select($v);
            }
            return;
        }
        $db1 = $this->db1;
        $resid = ''; 
        $was = FALSE;
        // прохожу в обратном порядке по таблицам
        $rareas = array_reverse($this->areas);
        foreach($rareas as $k => $v) {
            $fld = $this->tblb[$v][0];
            if ($this->data[area] !== $v AND !$value){continue;}
            $val = ($this->data[area] === $v) ? $this->data[value] : $value;
            $valArr = explode(",", $val);
            $value = $valArr[0];
            $was = FALSE;
            // получу id для следующей таблицы, родительский id
            $resid = $db1->select($v, "id='$value'", $fld);
            $where = ($v === 'sections') ? '' : "$fld='$resid[$fld]'";
            $result = $db1->select($v, $where, "id, title, lang", false);
            if (count($result) !== 0){
                $options[$v] = $this->get_opts($result, $valArr, true);
                $value = $resid[$fld];
                $was = TRUE;
            }
        }
        // прохожу в обратном порядке по таблицам
        foreach($this->areas as $k => $v) {
            $this->print_select($v, $options[$v]);
        }
    } // get_area_opts
    
    // функция меняет значения в строке $val, добавляя к каждому по краям 
    // знаки <>, напр., 1,34,4 на выходе: <1>,<34>,<4>
    // это нужно, чтобы функция LIKE правильно отбирала значения
    protected function changeVal($val) {
        $glue = ',';
        $valArr = explode($glue, $val);
        foreach($valArr as $k => $elem) {
            
            $valArr[$k] = "<".str_replace("'", "", $elem).">";
	}
        return implode($glue, $valArr);
    }
    
    public function save() {
        foreach($this->columns as $k => $col) {
            $data[$col] = "'".$this->data[$col]."'";
        }
        if ($this->mode == 'new') {// добавление новой записи 
            $data[id] = get_id($this->table);
            $data[value] = "'".$this->changeVal($data[value])."'";
            //if the row is being wrote for the first time.
            $result = $this->db1->insert($data, $this->table);    
        }elseif ($this->mode == 'edit') {// обновление записи
            //update the row in the database
            $data[value] = "'".$this->changeVal($data[value])."'";
            $result = $this->db1->update($data, $this->table, 'id = '.$data[id]);
        }elseif ($this->mode == 'del') {// удаление записи
            $result = $this->db1->delete($this->table, 'id = '.$data[id]);
        }
        $act = $this->modes[$this->mode][1];
        if ($result) {
            echo "<p align='center'>$act $this->name успешно завершено!</p>";
        }else{
            echo "<p align='center'>$act $this->name не прошло!</p>";
        }
    }
}
?>
