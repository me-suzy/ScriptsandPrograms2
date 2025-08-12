<?php

  $error = 0;

  if(!is_writable('config.php')) {

    echo "ERROR: Please make sure that config.php is CHMOD'ed to 777.<br>";
    $error = 1;

  }

  if(!is_writable('setup.php')) {

    echo "ERROR: Please make sure that setup.php is CHMOD'ed to 777.<br>";
    $error = 1;

  }

  if(!is_writable('redStyles/')) {

    echo "ERROR: Please make sure that redStyles/ is CHMOD'ed to 777.<br>";
    $error = 1;

  }

  if($error == 1) { exit(); }

  function getURL() {

     // 

     $url = "http://".$GLOBALS['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";

     $url = str_replace("www.", "", $url);

     return $url;

  }

  function loadSQL($fileName) {

    $lines = file($fileName);

    $sql = "";

    foreach ($lines as $line_num => $line) {

      // Check for end of query.

      $pos = strpos($line, ');');

      if($pos == FALSE) {

        $sql .= $line;

      } else {

        $sql .= $line;

        $arr[] = $sql;

        $sql = "";

      }

    }

    for($i = 0; $i <  count($arr); $i++) {

        //echo htmlspecialchars($arr[$i]) . "<br><br>";

        mysql_query($arr[$i]) or die (mysql_error());

    }

    return TRUE;

  }

  if($inst) {

    $error = 0;
    $errorMessage = "";

    if($adminUName == NULL || $adminPass == NULL || $adminEmail == NULL) {

      $error = 1; $errorMessage .= "Please fill in the admin details.";

    }

    if($url == NULL) {

      $error = 1; $errorMessage .= "Please fill your website address.";

    }

    if($error != 0) { echo "ERROR: <br>" . $errorMessage; exit(); }

    $file = fopen('setup.php', 'w') or die("ERROR: Couldn't open file.");

    $cont = '<?php $site = "' . $url . '"; ?>';

    fwrite($file, $cont) or die("ERROR: Couldn't write to file.");

    mysql_pconnect($dbServer,$dbUser,$dbPass) or die("ERROR: Failed to connect to database.");
    mysql_selectdb($dbName);

    loadSQL('redcms.sql');    

    // Insert the first user (access level 10).

    $key = rand(111111111,999999999);

    $adminPass = md5($adminPass);

    $sql = "INSERT INTO `redcms_users` VALUES ('', '" . $adminUName . "', '" . $adminPass . "', 10, '', '" . $adminEmail . "', '', '', '', '', '', '', '', '', NOW(), NOW(), 'TRUE', '" . $key . "')";
        
    mysql_query($sql) or die("ERROR: Failed to execute query");

    $file = fopen('config.php', 'w') or die("ERROR: Couldn't open file.");

    $content[] = '<?php';
    $content[] = '$dbServer = "' . $dbServer .'";';
    $content[] = '$dbName = "' . $dbName . '";';
    $content[] = '$dbUser = "' . $dbUser . '";';
    $content[] = '$dbPass = "' . $dbPass . '";';
    $content[] = 'mysql_pconnect($dbServer,$dbUser,$dbPass) or die("ERROR: Failed to connect to database.");';
    $content[] = 'mysql_selectdb($dbName);';
    $content[] = '?>';

    for($i = 0; $i < count($content); $i++) {

      fwrite($file, $content[$i] . "\n") or die("ERROR: Couldn't write to file.");

    } 

    echo "RedCMS has been installed successfully, please delete install.php from your webserver.";

  } else {

  $url = getURL();
 
?>

Please confirm your web address in the text box below:

<form method="post" action="<?php echo $PHP_SELF?>">

  <input type="text" name="url" value="<?php echo $url; ?>">

Fill in the form below and press the button to install the RedCMS Login Script.<br><br>

  <table>

    <tr><td>Database server:</td><td><input type="text" name="dbServer" value="localhost"></td></tr>
    <tr><td>Database name:</td><td><input type="text" name="dbName" value=""></td></tr>
    <tr><td>Database user:</td><td><input type="text" name="dbUser" value=""></td></tr>
    <tr><td>Database password:</td><td><input type="password" name="dbPass" value=""></td></tr>

    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

    <tr><td>Admin Username:</td><td><input type="text" name="adminUName" value=""></td></tr>
    <tr><td>Admin password:</td><td><input type="password" name="adminPass" value=""></td></tr>
    <tr><td>Admin email:</td><td><input type="text" name="adminEmail" value=""></td></tr>

  </table>

  <input type="Submit" name="inst" value="Install">

</form>

<?php

  }

?>