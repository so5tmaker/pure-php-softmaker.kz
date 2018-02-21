<?php
//Link.class.php

class Link {
    public $sec_name;
    public $cat_name;
    public $file_name;
    public $page;
    public $sec;
    public $cat;
    public $id;

    public $ids; // содержит массив соответствия id
    public $tbls; // содержит данные таблиц sections, categories, data
    
    public $rest;
    public $link;
    public $lang;
    public $mode;

    protected $db1; // содержит объект базы
    public $links; // содержит массив пути ссылки
    public $true_links; // содержит массив пути правильной ссылки
    public $assoc_tbls; // содержит массив соответствия таблицам базы и значениям ссылки по имени
    protected $tbls_name_by_id; // содержит массив соответствия имен по id
    
    protected $area;
    
    //Конструктор вызывается при создании нового объекта
    //Takes an associative array with the DB row as an argument.
    function __construct($data = NULL) {
        global $lang, $rest_, $db1;
        
        if (isset($data)){
            $this->sec_name = (isset($data['sec_name'])) ? $data['sec_name'] : FALSE;
            $this->cat_name = (isset($data['cat_name'])) ? $data['cat_name'] : FALSE;
            $this->file_name = (isset($data['file_name'])) ? $data['file_name'] : FALSE;
            $this->page = (isset($data['page'])) ? $data['page'] : FALSE;
            $this->sec = (isset($data['sec'])) ? $data['sec'] : FALSE;
            $this->cat = (isset($data['cat'])) ? $data['cat'] : FALSE;
            $this->id = (isset($data['id'])) ? $data['id'] : FALSE;

            $this->links = $data;

            $this->set_assoc_tbls();
        }
        
        $this->lang = $lang;
        $this->rest = $rest_;
        
        $this->db1 = $db1;
    }
    
    // установим соответствия таблицам базы и значениям ссылки
    private function set_assoc_tbls() {
        // значения массива 1-таблица бд, 
        // 2-поле следующей таблицы, по которому будет поиск
        if ($this->page){
            $this->assoc_tbls['page'] = 'page';
        }
        if ($this->id OR $this->cat){
            if ($this->id){
                $this->assoc_tbls['id']   = array('data'      , "cat", "cat",
                    "id, id_lang, name, cat, meta_d, meta_k, "
                    . "description, text, view, date, title, file, faq, "
                    . "phpcolor, notprohibit, price");
            }
            if ($this->id OR $this->cat){
                $this->assoc_tbls['cat']  = array('categories', "sec", "sec", 
                    "id, sec, title, name");
            }
            if ($this->id OR $this->cat OR $this->sec){
                $this->assoc_tbls['sec']  = array('sections'  , "id", "id", 
                    "id, title, name");
            }
        }  else {
            // значения массива 1-таблица бд, 2-поле по которому поиск
            // 3-поле следующей таблицы, по которому будет поиск
            if ($this->file_name){
                $this->assoc_tbls['file_name'] = array('data'      , 'name', 
                    "cat", "id, id_lang, name, cat, meta_d, meta_k, "
                    . "description, text, view, date, title, file, faq, "
                    . "phpcolor, notprohibit, price");
//                $this->area = 'data';
            }
            if ($this->file_name OR $this->cat_name){
                $this->assoc_tbls['cat_name']  = array('categories', 'id'  , 
                    "sec", "id, sec, title, name");
//                if (!isset($this->area)) { $this->area = 'categories';}
            }
            if ($this->file_name OR $this->cat_name OR $this->sec_name){
                $this->assoc_tbls['sec_name']  = array('sections'  , 'id'  , 
                    "id", "id, title, name");
//                if (!isset($this->area)) { $this->area = 'sections';}
            }
        }
    }
    
    private function get_name_by_id() {
        $this->tbls_name_by_id['id']  = 'file_name';
        $this->tbls_name_by_id['cat'] = 'cat_name';
        $this->tbls_name_by_id['sec'] = 'sec_name';
    }
    // Получаю чистую ссылку по первой верной части
    public function get_true_link() {
        $db1 = $this->db1;
        $this->link = '';
        $links = array(); 
        $fld = ''; $was = FALSE;
        foreach($this->assoc_tbls as $k => $v) {
            if ($k =='page'){
                $links[] = $this->links[$k].'/';
                $this->true_links[$k] = $this->links[$k];
                continue;
            }
            $value = ($was) ? $value : $this->links[$k];
            $was = FALSE;
            $fld = ($fld == '') ? 'name' : $v[1];
            $result = $db1->select($v[0], "$fld='$value' AND lang='$this->lang'", $v[3]);
            $this->tbls[$v[0]] = $result;
            if (count($result) !== 0){
                $end = ($k =='file_name' ) ? '.html' : '/';
                $value = $result[$v[2]];
                $this->ids[$k] = $result[id];
                $links[] = $result[name].$end;
                $this->true_links[$k] = $result[name];
                $was = TRUE;
            }
        }
        krsort($links);
        $this->link = $this->rest.'/'.implode('', $links);
    } // get_true_link
    
    // Получаю чистую ссылку по первой верной части с id
    public function get_true_link_id() {
        $this->get_name_by_id();
        $db1 = $this->db1;
        $this->link = '';
        $links = array(); 
        $fld = ''; $was = FALSE;
        foreach($this->assoc_tbls as $k => $v) {
            if ($k =='page'){
                $links[] = $this->links[$k].'/';
                $this->true_links[$k] = $this->links[$k];
                continue;
            }
            $value = ($was) ? $value : $this->links[$k];
            $was = FALSE;
            $result = $db1->select($v[0], "id='$value' AND lang='$this->lang'", $v[3]);
            $this->tbls[$v[0]] = $result;
            if (count($result) !== 0){
                $p = $this->tbls_name_by_id[$k];// получу название параметра по id
                $end = ($k =='id' ) ? '.html' : '/';
                $value = $result[$v[2]];
                $this->ids[$p] = $result[id];
                $links[] = $result[name].$end;
                $this->true_links[$p] = $result[name];
                $was = TRUE;
            }
        }
        krsort($links);
        $this->link = $this->rest.'/'.implode('', $links);
    } // get_true_link_id
    
    // Проверяю в какой раздел входит статья
    public function getSection() {
        $db1 = $this->db1;
        $was = FALSE; 
        $value = $this->links[file_name];
        foreach($this->assoc_tbls as $k => $v) {
            $was = FALSE;
            $result = $db1->select($v[0], "$v[1]='$value' AND lang='$this->lang'", "$v[2], name, title");
            if (count($result) !== 0){
                $value = $result[$v[2]];
                $was = TRUE;
            }
            if ($k == 'sec_name' AND $was) {
                return $result;
            }
        }
        return FALSE;
    } // getSection
    
    public function Error301() 
    {
        header('HTTP/1.1 301 Moved Permanently');
        header("Location: $this->link");
        exit ('');
    }
    
    public function Error404() 
    {
        //редирект на страницу ошибки 404
        $redirect = get_foreign_equivalent("Выполняется перенаправление на указанную страницу подождите...");
        $wait = get_foreign_equivalent("Если не хотите ждать нажмите здесь.");
        header("HTTP/1.1 404 Not Found");
        header( "Refresh: 2; url=404.php#mdl" );
        echo "<table align='center' width='100%' height='100%'>
                <tr>
                <td valign='middle' align='center'>
                    <p align='center'><img src='".$this->rest."/img/dic/loading1.gif' width='16' height='16'><br>".
                        $redirect 
                    ."<br>
                    <a href='".$this->rest."/404.php#mdl'>".
                        $wait
                    ."</a>
                    </p>
                </td>
                </tr>
              </table>";
        exit ('');
    }
    
    // для определения местоположения в иерархии файлов
    public function GetDeep(){
        foreach($this->links as $k => $v) {
            $deep .= ($k =='file_name' ) ? '' : '../';
        }
        return $deep;
    }
    // установим соответствия таблицам базы и значениям ссылки
    public function get_area() {
        $this->area = null;
        $this->get_name_by_id();
        foreach($this->tbls_name_by_id as $k => $v) {   
            if (isset($this->area)) {break;}
            if ($this->{$v}){
                $this->area = $this->assoc_tbls[$v][0];
            }
        }
        return $this->area;
    } // get_area
}
?>