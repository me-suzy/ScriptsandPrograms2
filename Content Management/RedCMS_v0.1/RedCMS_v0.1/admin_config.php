<?php

  include"top.php";

  access(10);

  connect();

  $file = 'config.php';

  if($database) {

    $start = microtime(true);

    $error = 0;
    $errorMessage = "";

    if($dbServer == NULL) { $error = 1; $errorMessage .= "Database name must not be blank.<br>"; }
    if($dbUser == NULL) { $error = 1; $errorMessage .= "Database user must not be blank.<br>"; }
    if($dbPass == NULL) { $error = 1; $errorMessage .= "Database password must not be blank.<br>"; }
    if($dbName == NULL) { $error = 1; $errorMessage .= "Database name must not be blank.<br>"; }

    if($error == 0) {

      // Connect to the database.

      mysql_pconnect($dbServer,$dbUser,$dbPass) or die("ERROR: could not connect to database. <br>Please go back and check the information you entered."); 
      mysql_selectdb($dbName) or die("ERROR: could not connect to database. <br>Please go back and check the information you entered."); 

      echo "Updating config.php...<br><br>";

      // Write to config.php

      $block = array("");

      array_push($block, '<?php');
      array_push($block, '$dbServer = "' . $dbServer .'";');
      array_push($block, '$dbName = "' . $dbName . '";');
      array_push($block, '$dbUser = "' . $dbUser . '";');
      array_push($block, '$dbPass = "' . $dbPass . '";');
      array_push($block, 'mysql_pconnect($dbServer,$dbUser,$dbPass) or die("ERROR: Failed to connect to database.");');
      array_push($block, 'mysql_selectdb($dbName); '); 
      array_push($block, '?>');
    
      $file = fopen($file, "w");

      $length = count($block);

      for($i = 0; $i < $length; $i++) {

        fwrite($file, $block[$i] . "\n");

      } 

      fclose($file);    

      $end = microtime(true);

      $totalTime = $end - $start;
      $totalTime = round($totalTime, 9);

      echo "Update of config.php completed in " . $totalTime . " microseconds.<br><br>";

      include"bottom.php";

      exit();

  } else { echo "ERROR: <br><br>" . $errorMessage . "<br><br>"; }

  } 

  if (!is_writable($file)) {
    echo 'ERROR: Please make sure that you have set the file permissions (CHMOD) for config.php to 777.';
  } else {


?>

 Fill in the form below and press the button to update the config file.<br><br>

<form method="post" action="<?php echo $PHP_SELF?>">

  <table>

    <tr><td>Database server:</td><td><input type="text" name="dbServer" value="localhost"></td></tr>
    <tr><td>Database name:</td><td><input type="text" name="dbName" value=""></td></tr>
    <tr><td>Database user:</td><td><input type="text" name="dbUser" value=""></td></tr>
    <tr><td>Database password:</td><td><input type="password" name="dbPass" value=""></td></tr>

  </table>

  <input type="Submit" name="database" value="Update">

</form>

<?php

  }

  include"bottom.php";

?>