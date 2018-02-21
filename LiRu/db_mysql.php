<?php
error_reporting(7);

class DB_sql
{
    var $link_id  = 0;
    var $query_id = 0;
    var $record   = array();

    var $errdesc    = "";
    var $errno   = 0;
    var $reporterror = 1;

    var $server   = "localhost";
    var $database = "";
    var $user     = "root";
    var $password = "";

    function connect()
    {
        global $usepconnect;
        // connect to db server

        if ( 0 == $this->link_id )
        {
            if ($usepconnect==1)
            {
              $this->link_id=mysql_pconnect($this->server,$this->user,$this->password);
            }
            else
            {
              $this->link_id=mysql_connect($this->server,$this->user,$this->password);
            }
        }

        if (!$this->link_id)
        {
            $this->halt("Link-ID == false, connect failed");
        }

        if ($this->database!="")
        {
            if(!mysql_select_db($this->database, $this->link_id))
            {
                $this->halt("cannot use database ".$this->database);
            }
        }
    }

    function query($query_string)
    {
        global $__querycount,$__make_querylist,$__querylist_a,$__queryalltime,$showqueries,$explain,$querytime;

        if ($__make_querylist)
        {
            $starttime=explode(" ",microtime());
        }

        $this->query_id = mysql_query($query_string,$this->link_id);
        if (!$this->query_id)
        {
            $this->halt("Invalid SQL: ".$query_string);
        }

        $__querycount++;
        if ($__make_querylist)
        {
            $endtime=explode(" ",microtime());
            $querytime=$endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];

            $__querylist_a[]=$__querycount.' : '.sprintf('%.5f',$querytime).' : '.$query_string."\n\n";
            $__queryalltime+=$querytime;
        }

        return $this->query_id;
    }

    function fetch_array($query_id=-1)
    {
        if ($query_id!=-1)
        {
            $this->query_id=$query_id;
        }

        if (isset($this->query_id))
        {
            $this->record = mysql_fetch_array($this->query_id,MYSQL_ASSOC);
        }
        else
        {
            $this->halt("Invalid query id ".$this->query_id." specified");
        }

        return $this->record;
    }

    function free_result($query_id=-1)
    {
        if ($query_id!=-1)
        {
            $this->query_id=$query_id;
        }

        return @mysql_free_result($this->query_id);
    }

    function query_first($query_string)
    {
        $query_id = $this->query($query_string);
        $returnarray=$this->fetch_array($query_id, $query_string);
        $this->free_result($query_id);
        return $returnarray;
    }

    function get_one($query_string)
    {
        $query_id = $this->query($query_string);
        $returnarray=$this->fetch_array_both($query_id, $query_string);
        $this->free_result($query_id);
        return $returnarray[0];
    }

    function num_rows($query_id=-1)
    {
        if ($query_id!=-1)
        {
            $this->query_id=$query_id;
        }

        return mysql_num_rows($this->query_id);
    }

    function insert_id()
    {
        return mysql_insert_id($this->link_id);
    }

    function halt($msg)
    {
        $this->errdesc=mysql_error();
        $this->errno=mysql_errno();

        if ($this->reporterror==1)
        {
            $message="Database error in $this->appname: $msg\n";
            $message.="mysql error: $this->errdesc\n";
            $message.="mysql error number: $this->errno\n";
            $message.="Date: ".date("l dS of F Y h:i:s A")."\n";
            $message.="Script: ".getenv("REQUEST_URI")."\n";
            $message.="Referer: ".getenv("HTTP_REFERER")."\n";

            echo "\n<!-- $message -->\n";

            echo "Problem with the database.\n";
            die("");
        }
    }
}
?>