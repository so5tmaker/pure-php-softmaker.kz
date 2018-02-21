<?php

require_once dirname(__FILE__)."/blocks/connect.inc";
require_once dirname(__FILE__)."/blocks/authenticate.inc";


//if (!isset($_SERVER['PHP_AUTH_USER']))
//{
//        Header ("WWW-Authenticate: Basic realm=\"Admin Page\"");
//        Header ("HTTP/1.0 401 Unauthorized");
//        exit();
//} else {
//        if (!get_magic_quotes_gpc()) {
//                $_SERVER['PHP_AUTH_USER'] = mysql_escape_string($_SERVER['PHP_AUTH_USER']);
//                $_SERVER['PHP_AUTH_PW'] = mysql_escape_string($_SERVER['PHP_AUTH_PW']);
//        }
//        
//        $query = "SELECT pass FROM userlist WHERE user='".$_SERVER['PHP_AUTH_USER']."'";
//        $lst = @mysql_query($query);
////        $lst = @$mysqli->query($query);
//
//        if (!$lst)
//        {
//            Header ("WWW-Authenticate: Basic realm=\"Admin Page\"");
//            Header ("HTTP/1.0 401 Unauthorized");
//            exit();
//        }
//
////        if ($lst->num_rows == 0)
//        if (mysql_num_rows($lst) == 0)
//        {
//           Header ("WWW-Authenticate: Basic realm=\"Admin Page\"");
//           Header ("HTTP/1.0 401 Unauthorized");
//           exit();
//        }
//
//        $pass =  @mysql_fetch_array($lst);
////        $pass =  @mysqli_fetch_array($lst, MYSQLI_ASSOC); 
//        if ($_SERVER['PHP_AUTH_PW']!= $pass['pass'])
//        {
//           Header ("WWW-Authenticate: Basic realm=\"Admin Page\"");
//           Header ("HTTP/1.0 401 Unauthorized");
//           exit();
//        }
//}

