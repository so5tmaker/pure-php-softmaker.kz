<?php
   define('LIRU_SESSION_EXPIRE_TIME',20);
   define('LIRU_APPBASE_PREFIX','http://www.liveinternet.ru/app/');

   define('LIRU_WRITEPROFILE_FORBIDDEN',2);
   define('LIRU_WRITEPROFILE_SESSIONSTALE',3);

   class CLiApp
   {
       function RefreshFrameset()
       {
           global $DB_site;

           $userid=intval($_GET['userid']);
           $username=get_magic_quotes_gpc() ? stripslashes($_GET['username']) : $_GET['username'];
           $sessionhash=get_magic_quotes_gpc() ? stripslashes($_GET['sessionhash']) : $_GET['sessionhash'];

           if ($_SERVER['REQUEST_METHOD']=='GET')  //пока умеем только гет
           {
               $SQL="SELECT * FROM ".LIRU_SESSION_TABLE." WHERE sessionhash='".addslashes($sessionhash)."'";
               $session_a=$DB_site->query_first($SQL);

               if (empty($session_a))
               {
                   $SQL="INSERT INTO ".LIRU_SESSION_TABLE." (sessionhash, userid, username, sessiontime)
                       VALUES ('".addslashes($sessionhash)."', '".intval($userid)."', '".addslashes($username)."', ".time().")";
                   $DB_site->query($SQL);
                   $session_a=array('sessionhash'=>$sessionhash, 'userid'=>$userid, 'username'=>$username, 'sessiontime'=>time());
               }

               if ((time()-$session_a['sessiontime'])>=LIRU_SESSION_EXPIRE_TIME)    //сессия устарела
               {
                   $SQL="UPDATE ".LIRU_SESSION_TABLE." SET sessiontime=".time()." WHERE sessionhash='".addslashes($sessionhash)."'";
                   $DB_site->query($SQL);

                   $query=$this->_prepare_query();
                   $request_string=preg_replace('/^'.preg_quote(LIRU_APPBASE,'/').'/U','',$_SERVER['PHP_SELF']).(!empty($query) ? '?'.$query : '');

                   echo "<html><script language='javascript'>parent.location.href='http://www.liveinternet.ru/app/".LIRU_APPNAME."/{$request_string}';</script></html>";
                   exit();
               }
           }

           $SQL="SELECT * FROM testapp WHERE sessionhash='".addslashes($sessionhash)."'";
           $testapp_a=$DB_site->query_first($SQL);

           return $testapp_a;
       }

       function CheckSession($sessionhash,$userid)
       {
           $f=@fopen('http://www.liveinternet.ru/apps/app_client.php?action=checksession&sessionhash='.urlencode($sessionhash).'&userid='.urlencode($userid),'r');
           if ($f)
           {
//               while($str=fgets($f)) echo "$str\n"; exit();

               $str=trim(fgets($f));
               if ($str=='OK') return TRUE;
           }

           return FALSE;
       }

       function WriteProfileQuiet($sessionhash,$userid,$message)
       {
           $query_a=array('message'=>$message);
           $query_encoded=$this->_do_urlencode_array($query_a);
           $rval=$this->_do_post_request('www.liveinternet.ru','/apps/app_client.php?action=writeprofile&sessionhash='.urlencode($sessionhash).'&userid='.urlencode($userid),$query_encoded);

           $result=trim(strstr($rval,"\r\n\r\n"));

           switch($result)
           {
                case 'OK':          return TRUE;
                case 'FORBIDDEN':   return LIRU_WRITEPROFILE_FORBIDDEN;
                case 'STALE':       return LIRU_WRITEPROFILE_SESSIONSTALE;
           }

           return FALSE;
       }

       function _prepare_query()
       {
           $params_a=array();
           foreach($_GET as $key=>$value)
           {
               if ($key!='sessionhash' && $key!='userid' && $key!='username') $params_a[]=$key.'='.urlencode($value);
           }

           return implode('&',$params_a);
       }

       function _do_post_request($host,$path,$data_to_send,$port=80,$proto="1.0")
       {
            $rval=-1;
            $data_len=strlen($data_to_send);
            $fp=fsockopen($host,$port);

            if ($fp)
            {
                fputs($fp,"POST $path HTTP/$proto\r\n");
                fputs($fp,"Host: $host\r\n");
                fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp,"Content-length: ".$data_len."\r\n");
                fputs($fp,"Connection: close\r\n\r\n");
                fputs($fp,$data_to_send);
                while(!feof($fp)) { $rval .= fgets($fp, 128); }
                fclose($fp);
            }
            return($rval);
        }

        function _do_urlencode_array($QueryVars)
        {
             $QueryBits=array();
             while(list($var,$value)=each($QueryVars))
             {
                 $QueryBits[]=urlencode($var).'='.urlencode($value);
             }

             return implode('&', $QueryBits);
        }

   }
?>