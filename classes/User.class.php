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

class User {
    public $id;
    public $username;
    public $hashedPassword;
    public $email;
    public $joinDate;
    public $active;
    public $last_visit;
    public $last_mail;
    public $lang;
    public $cats;

    //����������� ���������� ��� �������� ������ �������
    //Takes an associative array with the DB row as an argument.
    function __construct($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : "";
        $this->username = (isset($data['username'])) ? $data['username'] : "";
        $this->hashedPassword = (isset($data['password'])) ? $data['password'] : "";
        $this->email = (isset($data['email'])) ? $data['email'] : "";
        $this->joinDate = (isset($data['join_date'])) ? $data['join_date'] : "";
        $this->active = (isset($data['active'])) ? $data['active'] : "";
        $this->last_visit = (isset($data['last_visit'])) ? $data['last_visit'] : "";
        $this->last_mail = (isset($data['last_mail'])) ? $data['last_mail'] : "";
        $this->lang = (isset($data['lang'])) ? $data['lang'] : "";
        $this->cats = (isset($data['cats'])) ? $data['cats'] : "";
    }

    public function get_cats_id_str($db) {
        $result = $db->select('categories', "lang='$this->lang'", "id");
        $resultArray = array();
        foreach($result as $k => $v) {
            $resultArray[] = $v[id];
        }
        return implode(",", $resultArray);
    }
    
    //������� ���������� ������������ ��� �������� ��������� � �������
    //�� � �������� ���������� � ������� User. ��� ������� ���������� ����� ��,
    //������� �� ������� � ������ �����. ��������� ���������� ������,
    //��������������� ������ $data. ���� ������ � ������������ �����������
    //�������, ����� $isNewUser ���������� ��� $true (�� ��������� false).
    //���� $isNewUser = $true, ����� ���������� ������� insert() ������ DB.
    //� ��������� ������ ���������� ������� update().
    //� ����� ������� ���������� �� ������� user ����� ��������� � ��.

    public function save($isNewUser = false) {
        //create a new database object.
        $db = new DB();

        //if the user is already registered and we're
        //just updating their info.
        if(!$isNewUser) {
            //set the data array
            $data = array(
            "id" => "'$this->id'",
            "username" => "'$this->username'",
            "password" => "'$this->hashedPassword'",
            "email" => "'$this->email'",
            "active" => "'$this->active'",
            "last_visit" => "'$this->last_visit'",
            "last_mail" => "'$this->last_mail'",
            "lang" => "'$this->lang'",
            "cats" => "'$this->cats'"
            );

            //update the row in the database
            $db->update($data, 'users', 'id = '.$this->id);
        }else {
            //if the user is being registered for the first time.
//            $cats = $db->select('categories', "id <> ''");
            $data = array(
            "username" => "'$this->username'",
            "password" => "'$this->hashedPassword'",
            "email" => "'$this->email'",
            "join_date" => "'".date("Y-m-d H:i:s",time())."'",
            "active" => "'0'",
            "lang" => "'$this->lang'",
            "cats" => "'$this->cats'"
            );
            $this->id = $db->insert($data, 'users');
            $this->joinDate = time();
        }
            return true;
    }
}
?>
