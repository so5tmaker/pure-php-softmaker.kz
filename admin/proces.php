
<?php

  header('Location: index.php');

  exec('php daemon.php > daemon.txt &');

 ?>

