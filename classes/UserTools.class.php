<?php

//UserTools.class.php

class UserTools {

    //Функция login() понятна по названию. Она берет аргументы пользователя
    //$username и $password и проверяет их соответствие.
    // аргумент $registration указывает на текущий режим: 1 - регистрация пользователя
    //Если все совпадает, создает объект User со всей
    //информацией и сохраняет его в сессии. Обратите внимание,
    //что мы только используем функцию PHP serialize().
    //Она создает сохраненный вариант объекта, который можно
    //легко отменить с помощью unserialize().
    //Также время логина будет сохранено.
    //Это может использоваться в дальнейшем для предоставления
    //пользователям информации о длительности пребывания на сайте.

    //Вы также можете заметить, что мы выставляем $_SESSION['logged_in'] на 1.
    //Это позволяет нам легко проверить на каждой странице залогинен ли пользователь.
    //Достаточно проверить только эту переменную.

    //Log the user in. First checks to see if the
    //username and password match a row in the database.
    //If it is successful, set the session variables
    //and store the user object within.
    public function login($username, $password) {
        $hashedPassword = md5($password);
        $result_ut = mysql_query("SELECT * FROM users WHERE username = '$username' AND
        password = '$hashedPassword'");
        if(mysql_num_rows($result_ut) == 1)
        {
            $myrow_ut = mysql_fetch_assoc($result_ut);
            if ($myrow_ut["active"] == '1'){
                $_SESSION["user"] = serialize(new User($myrow_ut));
                $_SESSION["login_time"] = time();
                $_SESSION["logged_in"] = 1;
                return "Activated";
            }else {
                return "NotActivated";
            }
        }else{
            return "WrongPassword";
        }
    }
    // проверить наличие пользователя с таким паролем
    public function checkuser($userid, $password, $hashed=1) {
        if ($hashed <> 1) $hashedPassword = md5($password); else $hashedPassword = $password;
        $result_ch = mysql_query("SELECT * FROM users WHERE id = '$userid' AND
        password = '$hashedPassword'");
        if(mysql_num_rows($result_ch) == 1){
            return true;
        }else{
            return false;
        }
    }
    //Также простая функция. Функция PHP unset() очищает 
    //переменные в памяти, в то время как session_destroy() удалит сессию.

    //Log the user out. Destroy the session variables.
    public function logout() {
        unset($_SESSION['user']);
        unset($_SESSION['login_time']);
        unset($_SESSION['logged_in']);
        session_destroy();
    }

    //Кто знает английский легко поймет функцию.
    //Она просто запрашивает БД,
    //использован ли подобный логин или нет.

    //Check to see if a username exists.
    //This is called during registration to make sure all user names are unique.
    public function checkUsernameExists($username) {
        $result = mysql_query("select id from users where username='$username'");
        if(mysql_num_rows($result) == 0)
        {
            return false;
        }else{
            return true;
        }
    } // checkUsernameExists

    // Проверяет есть ли пользователь с таким адресом электронной почты
    public function checkEmailExists($email) {
        $result = mysql_query("select id from users where email='$email'");
        if(mysql_num_rows($result) == 0)
        {
            return false;
        }else{
            return true;
        }
    } // checkEmailExists


    //Эта функция берет уникальный id пользователя и
    //делает запрос к БД с помощью класса DB, а именно
    //функции select(). Она возьмет ассоциативный массив
    //с рядом информации о пользователе и создаст новый
    //объект User, передавая массив конструктору.

    //Где можно это использовать? К примеру,
    //если Вы создадите страницу, которая должна
    //отображать специфические профили пользователей,
    //Вам необходимо будет динамически брать эту информацию.
    //Вот так Вы можете это сделать:
    //(допустим УРЛ http://www.website.com/profile.php?userID=3)

    //note: you will have to open up a database connection first.
    //see Part 1 for further information on doing so.
    //You'll also have to make sure that you've included the class files.

    //$tools = new UserTools();
    //$user = $tools->get($_REQUEST['userID']);
    //echo "Username: ".$user->username."";
    //echo "Joined On: ".$user->joinDate."";
    //
    //Легко! Правда?

    //get a user
    //returns a User object. Takes the users id as an input
    public function get($id)
    {
        $db = new DB();
        $result = $db->select('users', "id = $id");
        return new User($result);
    }
}

?>