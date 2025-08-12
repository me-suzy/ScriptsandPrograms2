<?php session_start();

if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {
  // Include config file
  include("config/config.php");

  $dat = date("Y-m-d");

  // Connect to MySQL and the database
  mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
  mysql_select_db($sql['data']) or die("Unable to find DB");

  $tables = array("pmc_artist", "pmc_comic", "pmc_user");
  @set_time_limit(0);

  $values  = "# PhpMyComic DB Backup"."\n";
  $values .= "#-----------------------------------"."\n";
  $values .= "# Backup Date: $dat"."\n";
  $values .= "# Current PMC Version: $version"."\n";
  $values .= "#-----------------------------------"."\n";
  $values .= "\n"."\n";

  foreach($tables as $tablename)
  {
    $result = mysql_query("SELECT * FROM  $tablename");
    $fields_cnt   = mysql_num_fields($result);
    while ($row = mysql_fetch_array($result))
    {
      $tvalues = 'INSERT INTO ' . $tablename  . ' VALUES (';
      for ($j = 0; $j < $fields_cnt; $j++)
      {
        if (!isset($row[$j])) {
          $tvalues .= ' NULL, ';
        } else if ($row[$j] == '0' || $row[$j] != '') {
            $type = mysql_field_type($result, $j);
            if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' || $type == 'bigint'  ||$type == 'timestamp') {
              // a number
              $tvalues .= $row[$j] . ', ';
            } else {
              // a string
              $dummy  = '';
              $srcstr = $row[$j];
              for ($xx = 0; $xx < strlen($srcstr); $xx++)
              {
                $yy = strlen($dummy);
                if ($srcstr[$xx] == '\\')   $dummy .= '\\\\';
                if ($srcstr[$xx] == '\'')   $dummy .= '\\\'';
                if ($srcstr[$xx] == "\x00") $dummy .= '\0';
                if ($srcstr[$xx] == "\x0a") $dummy .= '\n';
                if ($srcstr[$xx] == "\x0d") $dummy .= '\r';
                if ($srcstr[$xx] == "\x1a") $dummy .= '\Z';
                if (strlen($dummy) == $yy)  $dummy .= $srcstr[$xx];
              }
              $tvalues .= "'" . $dummy . "', ";
            }
          } else {
            $tvalues .= "'', ";
          }
        }
        $tvalues = ereg_replace(', $', '', $tvalues);
        $tvalues .= ");"."\n";
        $values .= $tvalues;
      }
      mysql_free_result($result);
    }


  $FileName = "phpmycomic_".$dat.".sql";

  // Open file for writing
  $fp = @fopen("backup/$FileName","w") or die("Could not open file");

  // Write the config file
  $numBytes = @fwrite($fp, $values) or die("Could not write to file");

  // Close the config file
  @fclose($fp);


} else {
  // Login failed
  header("Location: error.php?error=01");
  exit;
}

?>