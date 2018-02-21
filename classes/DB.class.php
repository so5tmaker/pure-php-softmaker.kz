<?php
//DB.class.php

class DB {
    protected $mysqli;

    protected $db_name = 'phpapp';
    protected $db_user = 'bloguser';
    protected $db_pass = '12345';
    protected $db_host = 'localhost';
    protected $test = 'localhost';

    public function GetHost() {
        return $_SERVER['HTTP_HOST'];
    }

    // ��������� ���������� � ��. ���������, ���
    // ��� ������� ���������� �� ������ ���������
    //��� ������ ������������� ���� ������� ��� ������:
    //$db = new DB();
    //$db->connect();
    public function connect() {
//        global $mysql_host, $mysql_database, $mysql_user, $mysql_password;

        $this->db_name = $mysql_database;
        $this->db_host = $mysql_host;
        $this->db_user = $mysql_user;
        $this->db_pass = $mysql_password;

//        $mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
//        /* �������� ���������� */
//        if ($mysqli->connect_errno) {
//            printf("�� ������� ������������: %s\n", $mysqli->connect_error);
//            exit();
//        }
//        $this->mysqli = $mysqli;
    }

    // ����� ��� mysql � ���������� ������������� ������, � �������
    // �������� ������� �������� ������� �������. ���� singleRow - true,
    // ����� ��������� ������ ���� ���
    //������ ������� ���������� processRowSet(). ���� ������ ������� -
    //����� ������ ���������� mysql � �������������� ��� � �������������
    //������, � ������� ������� �������� �������� �������.
    //������� �������� �� ������� ���� � ������� mysql_fetch_assoc()
    //��������������� ������ ��� � ������. ��� ����� ���������� �������
    //� ������������ � ������� �������.

    //���������� ������ �������� $singleRow, ������� �������� ��������
    //�� ���������. ���� �������� true, ��������� ������ ���� ��� ������
    //�������. ��� ����� �������, ���� �� �������� �������� ���� ���������
    //(��������, ��� ������ ����� �� �� ��������� ���������� id).
    public function processRowSet($rowSet, $singleRow=false) {
        $resultArray = array();
        while($row = mysql_fetch_assoc($rowSet))
        {
            array_push($resultArray, $row);
        }   
        if($singleRow === true) 
        return $resultArray[0];
        return $resultArray;
    }

    //������� ��������� ������� $table
    public function columns($table) {
        $sql = "SHOW COLUMNS FROM $table";
        $result = mysql_query($sql);
        if(mysql_num_rows($result) !== 0){
            while($row = mysql_fetch_assoc($result))
            {
                $resultArray[] = $row[Field];
            }   
            return $resultArray;
        }
        die(mysql_error());
    }
    
    //��������� 3 ������� ��������� ������� ������� MySQL:
    //select, insert, update. ���� ������ ������� ��������������
    //���������� SQL ����, ������� ���������� ������������ ���-����
    //� ������ ����� ����������. ������ ������� ������� SQL ������
    //�� ������ ����������� �������� � ��������� ���� ������.
    //� ������ select(), ���������� ������������� � ���������.
    //� ������ update(), ��������� true ��� �������� ����������.
    //� ������ insert(), ��������� id ������ ����.
    //��� ������ ��� �� ������ �������� ������ ������������
    //� �� ��������� ������� update():
    //
    //$db = new DB();
    //$data = array(
    //"username" => "'johndoe'",
    //"email" => "'johndoe@email.com'"
    //);
    //����� ����� � id = 3 � �� � ������ ��������� � ���
    //username - johndoe � e-mail - johndoe@email.com
    //$db->update($data, 'users', 'id = 3');

    //�������� ���� �� ��
    //������� ������ ��� ��� ���� �� $table ��������� $where
    public function select($table, $where = '', $fld = '*', $singleRow=true) {
        $where_loc = ($where == '') ? '' : "WHERE $where";
        $sql = "SELECT $fld FROM $table $where_loc";
        $result = mysql_query($sql);
        if(mysql_num_rows($result) == 1 AND $singleRow)
        return $this->processRowSet($result, $singleRow);
        return $this->processRowSet($result);
    }

    //������ ��������� � ��
    public function update($data, $table, $where) {
        foreach ($data as $column => $value) {
            $sql = "UPDATE $table SET $column = $value WHERE $where";
            mysql_query($sql) or die(mysql_error());
        }
        return true;
    }

    //������ ��������� � ��
    public function delete($table, $where) {
        $sql = "DELETE FROM $table WHERE $where";
        mysql_query($sql) or die(mysql_error());
        return true;
    }

//��������� ����� ��� � �������
    public function insert($data, $table) {
        
    $columns = "";
    $values = "";
    foreach ($data as $column => $value) {
        $columns .= ($columns == "") ? "" : ", ";
        $columns .= $column;
        $values .= ($values == "") ? "" : ", ";
        $values .= $value;
    }

        $sql = "insert into $table ($columns) values ($values)";
//        $this->mysqli->query($sql) or die($this->mysqli->error);
        mysql_query($sql) or die(mysql_error());

        //������� ID ������������ � ��.
        return mysql_insert_id();
//        return $this->mysqli->insert_id;
    }
}

?>