<?php
include_once("config.php");
require_once "session.php"; 
checkLoggedIn("yes");
flushMemberSession();
header("Location: login.php");
?>