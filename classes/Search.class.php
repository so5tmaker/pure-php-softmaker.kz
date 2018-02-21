<?php
//User.class.php

//Первая часть кода, вне зоны класса, обеспечивает подключение класса в БД
//(поскольку в классе User есть функция, которая требует этот класс).
//Вместо переменных класса “protected” (использовались в 1-м уроке)
//мы определяем их как “public”. Это означает, что любой код вне класса
//имеет доступ к этим переменным при работе с объектом User.
//
//Конструктор берет массив, в котором колонки в таблице являются ключами.
//Мы задаем переменную класса используя $this->variablename.
//В примере данного класса, мы прежде всего проверяем существует ли
//значение определенного ключа. Если да, тогда мы приравниваем
//переменную класса к этому значению. В противном случае - пустая строка.
//Код использует краткую форму записи оборота if:
//
//$value = (3 == 4) ? "A" : "B";
//
//В данном примере мы проверяем равняется ли 3 четырем!
//Если да - тогда $value = “A”, нет - $value = “B”.
//В нашем примере результат $value = “B”.

require_once 'DB.class.php';

class Search {
public $id;
public $faq;
public $title;
public $text;
public $description;
public $mini_img;
public $relevation;
public $tbl;

//Конструктор вызывается при создании нового объекта
//Takes an associative array with the DB row as an argument.
function __construct($data) {
$this->id = (isset($data['id'])) ? $data['id'] : "";
$this->faq = (isset($data['faq'])) ? $data['faq'] : "";
$this->title = (isset($data['title'])) ? $data['title'] : "";
$this->text = (isset($data['text'])) ? $data['text'] : "";
$this->description = (isset($data['description'])) ? $data['description'] : "";
$this->mini_img = (isset($data['mini_img'])) ? $data['mini_img'] : "";
$this->relevation = (isset($data['relevation'])) ? $data['relevation'] : "";
$this->tbl = (isset($data['tbl'])) ? $data['tbl'] : "";
}

//Функция сохранения используется для внесения изменений в таблицу
//БД с текущими значениями в объекте User. Эта функция использует класс БД,
//который мы создали в первом уроке. Используя переменные класса,
//устанавливается массив $data. Если данные о пользователе сохраняются
//впервые, тогда $isNewUser передается как $true (по умолчанию false).
//Если $isNewUser = $true, тогда вызывается функция insert() класса DB.
//В противном случае вызывается функция update().
//В обоих случаях информация от объекта user будет сохранена в БД.

public function save($isNewMaterial = FALSE) {
//create a new database object.
$db = new DB();
$text = addslashes($this->text);
//str_replace("'", "\"", $this->text);

if(!$isNewMaterial) {
    $data = array(
    "title" => "'$this->title'",
    "text" => "'".$text."'",
    "description" => "'$this->description'",
    "mini_img" => "'$this->mini_img'",
    "relevation" => "'$this->relevation'",
    "tbl" => "'$this->tbl'"
    );

//update the row in the database
$db->update($data, 'searchers', 'id = '.$this->id.' AND faq='.$this->faq );
}else {
    $data = array(
    "id" => "'$this->id'",
    "faq" => "'$this->faq'",
    "title" => "'$this->title'",
    "text" => "'".$text."'",
    "description" => "'$this->description'",
    "mini_img" => "'$this->mini_img'",
    "relevation" => "'$this->relevation'",
    "tbl" => "'$this->tbl'"
    );
    $this->id = $db->insert($data, 'searchers');
}
    return true;
}
}
?>
