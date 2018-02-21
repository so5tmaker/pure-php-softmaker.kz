<?php
//Advs.class.php

class Advs {
    
    private $position;
    private $area;
    private $link;
    public $text;
    protected $rep; // сколько раз повторять блок рекламы в тексте статей
    protected $tag;
    protected $block;
    
    protected $db1; // содержит объект базы

    //Конструктор вызывается при создании нового объекта
    function __construct() {
        global $db1;
        $this->db1 = $db1;
    }
    // оберну блок рекламы тегом
    protected function tagoverlap($block) {
        return '<div class="adv_div" align="center">'.$block.'</div>';
    }
    
    protected function replace_tag(){
        //Код вашей рекламы
        $count = null;
        $this->text = preg_replace("~$this->tag~", $this->block, trim($this->text), $this->rep, $count);
    }
    
    // вставляет блок рекламы в текст статьи
    protected function insadv(){
        if (substr_count($this->text, $this->tag) >= 1)
        {
            $this->replace_tag();
            return;
        }
        return;
        $tag = "</h2>";
        $pieces = explode($tag, $this->text);

        $result1  = count($pieces);
        if ($result1==1){ 
            $tag = "</p>";
            $pieces = explode($tag, $this->text);
        }
        $result2  = count($pieces);
        if ($result2==1){ 
            $tag = "</li>";
            $pieces = explode($tag, $this->text);
        }

        $txt='';$i=0; 
        $arr = array(); $arr1 = array();
        foreach ($pieces as $piece) {
            $txt.= $piece;
            if (strlen(strip_tags($txt))>1200)
            {
                $arr[] = substr($piece, -250).$tag; 
                $arr1[] = substr($piece, -250).$tag.$this->block;
                $txt=''; $i+=1;
            }
            if ($i==2) {break;}
        }
        if ($i==0) 
        {
            $this->text = str_replace($arr, $arr1, $this->text).$this->block;
        } else {
            $this->text = str_replace($arr, $arr1, $this->text);
        }
    }
    
    protected function change($result) {
        foreach($result as $k => $v) {
            $areas = array('categories', 'sections');
            foreach($areas as $k => $area) {
                if ($v[area] === $area){
                   $vals = explode(",", $v[value]);
                   $yes = in_array("<".$this->link->tbls[$area][id].">", $vals);
                } else {
                    continue;
                }
                if (!$yes){
                   continue;
                } else {
                    break;
                }
            }
            if (!$yes){
               continue;
            }
            $this->block = $this->tagoverlap($v[block]);
            $this->tag = $v[tag];
            $this->rep = $v[rep];
            $this->insadv();
        }
    }

    public function get($position, $area, $value = '', $filename = false) {
        global $home;
        $where  = ($filename) ? "AND applyart='1' " : "AND applyart='0' ";
        if ($area){
            $where .= "AND area='$area' ";
            $where .= ($area === 'empty') ? "" : "AND value LIKE '%<$value>%'";
        }
        $result = $this->db1->select('advs', "page LIKE '%$home%' AND "
                    . "position LIKE '%$position%' "
                    . "AND turnon='1' $where ORDER BY priority ASC", 
                    'area, value, block, tag, rep', FALSE);
        if (count($result)) {
            if ($filename AND $position === 'center') {
                $this->change($result);
                echo $this->text;
                return true;
            }
            echo $this->tagoverlap($result[0][block]);
            return true;
        }
        return false;
    }
    
    public function getBro($whot) {
        codbanner($whot);
        mysql_query("SET NAMES 'cp1251'");
        $result = $this->db1->select('temp', "id='1'",'text');
        if (count($result)) {
            echo $this->tagoverlap(stripslashes($result[text]));
            return true;
        }
        return false;
    }
    
    public function show($position, $Link = null, $text = '') {
        $this->position = $position;
        $this->text = $text;
        $this->link = $Link;
        // если вызов из статьи
        if (isset($Link)) {
            $area = $Link->get_area();
            $this->area = $area;
            $value = $Link->tbls[$area][id];
            if ($Link->file_name AND $position === 'center') {
                $this->get($position, '', $Link->tbls['sections'][id], $Link->file_name);
                return;
            }
            // есть ли блок рекламы для этой статьи? 
            $exact = $this->get($position, $area, $value, $Link->file_name);
            if (!$exact) { // если нет, то ищу ниже по категории
                $was = false;
                foreach($Link->assoc_tbls as $k => $v) {
                    if ($v[0] === $area){
                        $was = true;
                        continue;
                    }
                    if ($was){ // если нет, то ищу ниже по секции
                        $catsec = $this->get($position, $v[0], $Link->tbls[$v[0]][id], $Link->file_name);
                        if ($catsec) {
                            return;
                        }
                    }
                }
            } else {
                return;
            }  
        }
        // если нет, то ищу блок по умолчанию и вывожу
        $empty = $this->get($position, 'empty');
        if (!$empty) {
            return;
        }
    }
}
?>
