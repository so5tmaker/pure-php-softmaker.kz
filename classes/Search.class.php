<?php
//User.class.php

//������ ����� ����, ��� ���� ������, ������������ ����������� ������ � ��
//(��������� � ������ User ���� �������, ������� ������� ���� �����).
//������ ���������� ������ �protected� (�������������� � 1-� �����)
//�� ���������� �� ��� �public�. ��� ��������, ��� ����� ��� ��� ������
//����� ������ � ���� ���������� ��� ������ � �������� User.
//
//����������� ����� ������, � ������� ������� � ������� �������� �������.
//�� ������ ���������� ������ ��������� $this->variablename.
//� ������� ������� ������, �� ������ ����� ��������� ���������� ��
//�������� ������������� �����. ���� ��, ����� �� ������������
//���������� ������ � ����� ��������. � ��������� ������ - ������ ������.
//��� ���������� ������� ����� ������ ������� if:
//
//$value = (3 == 4) ? "A" : "B";
//
//� ������ ������� �� ��������� ��������� �� 3 �������!
//���� �� - ����� $value = �A�, ��� - $value = �B�.
//� ����� ������� ��������� $value = �B�.

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

//����������� ���������� ��� �������� ������ �������
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

//������� ���������� ������������ ��� �������� ��������� � �������
//�� � �������� ���������� � ������� User. ��� ������� ���������� ����� ��,
//������� �� ������� � ������ �����. ��������� ���������� ������,
//��������������� ������ $data. ���� ������ � ������������ �����������
//�������, ����� $isNewUser ���������� ��� $true (�� ��������� false).
//���� $isNewUser = $true, ����� ���������� ������� insert() ������ DB.
//� ��������� ������ ���������� ������� update().
//� ����� ������� ���������� �� ������� user ����� ��������� � ��.

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
