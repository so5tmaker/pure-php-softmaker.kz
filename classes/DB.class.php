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

    // Открывает соединение к БД. Убедитесь, что
    // эта функция вызывается на каждой странцице
    //Вот пример использования этой функции вне класса:
    //$db = new DB();
    //$db->connect();
    public function connect() {
//        global $mysql_host, $mysql_database, $mysql_user, $mysql_password;

        $this->db_name = $mysql_database;
        $this->db_host = $mysql_host;
        $this->db_user = $mysql_user;
        $this->db_pass = $mysql_password;

//        $mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
//        /* проверка соединения */
//        if ($mysqli->connect_errno) {
//            printf("Не удалось подключиться: %s\n", $mysqli->connect_error);
//            exit();
//        }
//        $this->mysqli = $mysqli;
    }

    // Берет ряд mysql и возвращает ассоциативный массив, в котором
    // названия колонок являются ключами массива. Если singleRow - true,
    // тогда выводится только один ряд
    //Вторая функция называется processRowSet(). Цель данной функции -
    //взять объект результата mysql и конвертировать его в ассоциативный
    //массив, в котором ключами являются название колонок.
    //Функция проходит по каждому ряду и функция mysql_fetch_assoc()
    //преобразовывает каждый ряд в массив. Ряд далее передается массиву
    //и возвращается с помощью функции.

    //Существует второй аргумент $singleRow, который содержит значение
    //по умолчанию. Если значение true, выводится только один ряд вместо
    //массива. Это очень полезно, если Вы ожидаете получить один результат
    //(например, при выборе юзера из БД используя уникальный id).
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

    //Выводит структуру таблицы $table
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
    
    //Последние 3 функции выполняют простые фукнции MySQL:
    //select, insert, update. Цель данных функций минимизировать
    //количество SQL кода, который необходимо использовать где-либо
    //в другом месте приложения. Каждая функция создает SQL запрос
    //на основе переданного значения и выполняет этот запрос.
    //В случае select(), результаты форматируются и выводятся.
    //В случае update(), выводится true при успешном выполнении.
    //В случае insert(), выводится id нового ряда.
    //Вот пример как Вы можете изменить данные пользователя
    //в БД используя функцию update():
    //
    //$db = new DB();
    //$data = array(
    //"username" => "'johndoe'",
    //"email" => "'johndoe@email.com'"
    //);
    //Найти юзера с id = 3 в БД и внести изменения в ряд
    //username - johndoe и e-mail - johndoe@email.com
    //$db->update($data, 'users', 'id = 3');

    //Выбирает ряды из БД
    //Выводит полный ряд или ряды из $table используя $where
    public function select($table, $where = '', $fld = '*', $singleRow=true) {
        $where_loc = ($where == '') ? '' : "WHERE $where";
        $sql = "SELECT $fld FROM $table $where_loc";
        $result = mysql_query($sql);
        if(mysql_num_rows($result) == 1 AND $singleRow)
        return $this->processRowSet($result, $singleRow);
        return $this->processRowSet($result);
    }

    //Вносит изменения в БД
    public function update($data, $table, $where) {
        foreach ($data as $column => $value) {
            $sql = "UPDATE $table SET $column = $value WHERE $where";
            mysql_query($sql) or die(mysql_error());
        }
        return true;
    }

    //Вносит изменения в БД
    public function delete($table, $where) {
        $sql = "DELETE FROM $table WHERE $where";
        mysql_query($sql) or die(mysql_error());
        return true;
    }

//Вставляет новый ряд в таблицу
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

        //Выводит ID пользователя в БД.
        return mysql_insert_id();
//        return $this->mysqli->insert_id;
    }
}

?>