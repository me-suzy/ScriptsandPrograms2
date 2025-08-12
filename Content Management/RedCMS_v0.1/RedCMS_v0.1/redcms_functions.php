<?php

  // This function connects to the MYSQL database.

  function connect() {

    require"config.php";

  }

  function loggedIn() {

    if($_SESSION['redIn'] == 'TRUE') {

      $isLoggedIn = 'TRUE';

    } else {

      $isLoggedIn = 'FALSE';

    }

    return $isLoggedIn;

  }

  function isActive() {

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_id = '" . $_SESSION['redUserID'] . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num != 0) {

      $isActive = mysql_result($result, "0", "redcms_users.user_active");
 
    } 

    return $isActive;

  }

  function isLoggedIn() {

    if(loggedIn() != 'TRUE') {

      echo'<b>You need to be logged in to view this page.</b>';

      echo'<meta http-equiv="Refresh" content="2;url=login.php">';
      include"bottom.php";
      exit();

    }

  }

  function access($level) {

    isLoggedIn();

    if($_SESSION['redUserLevel'] < $level) {

      echo'<b>You cannot view this page.</b>';

      echo'<meta http-equiv="Refresh" content="2;url=index.php">';
      include"bottom.php";
      exit();

    }

  }

  function age($date_of_birth) {

    $cur_year=date("Y");
    $cur_month=date("m");
    $cur_day=date("d");
    $dob_year=substr($date_of_birth, 0, 4);
    $dob_month=substr($date_of_birth, 5, 2);
    $dob_day=substr($date_of_birth, 8, 2);
    if($cur_month>$dob_month || ($dob_month==$cur_month && $cur_day>=$dob_day) )
    return $cur_year-$dob_year;
    else
    return $cur_year-$dob_year-1;

  }

  function hit($counterID) {

    connect();

    $ip = $_SERVER["REMOTE_ADDR"];

    $sql = "INSERT INTO redcms_hits VALUES ('', '" . $counterID . "', '" . $ip . "', NOW(), NOW())";
 
    mysql_query($sql) or die("ERROR: Failed to execute SQL query.");

  }

  function stats($counterID) {

    connect();

    $sql = "SELECT * FROM redcms_hits WHERE redcms_hits.counter_id = '" . $counterID . "' ORDER BY redcms_hits.hit_date, redcms_hits.hit_time DESC";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 0) { echo "ERROR: Counter not found, or no hits avaliable"; include"bottom.php"; exit(); }

    echo "Monthly statistics:";

    $colour = "black";

    $c = 0;

   $heightfactor = 1;

   if($num <= 10000) { $heightfactor = $hits/500; $widthfactor = 1; }
   if($num > 10000) { $heightfactor = $hits/1000; $widthfactor = $num/5000; }

   $sql = "SELECT MONTHNAME(redcms_hits.hit_date) AS month, COUNT(*) AS month_count FROM redcms_hits WHERE redcms_hits.counter_id = '" . $counterID . "' GROUP BY month ORDER BY redcms_hits.hit_date ASC";

   $result = mysql_query($sql) or die($sql);

   $num = mysql_num_rows($result);

    echo "<table>";

   $j = 0;
   $first = "";
   $second = "";
   $third = "";

   for($i=0; $i<$num; $i++) {

     $month = mysql_result($result, $i, "month");
     $hits = mysql_result($result, $i, "month_count");

     if($heightfactor == 0) { $heightfactor = 1; }

     $height = $hits / $heightfactor;

     $first .= '<td valign="bottom" align="center"><img src="redStyles/colours/' . $colour . '.gif" width="10" height="' . $height . '"></td>';

     $second .= '<td valign="bottom"> (' . $hits . ')</td>';

     $third .= '<td valign="bottom">' . $month . '</td>';

     if($j == 10) {

       echo "<tr>" . $first . "</tr><tr>" . $second . "</tr><tr>" . $third . "</tr>";

       $j = 0;

       $first = "";
       $second = "";
       $third = "";

     } else { $j++; }

   }

  echo "<tr>" . $first . "</tr><tr>" . $second . "</tr><tr>" . $third . "</tr>";

  echo "</table>";

  }

  function getHits($counterID) {

    connect();

    $sql = "SELECT * FROM redcms_hits WHERE redcms_hits.counter_id = '" . $counterID . "' ORDER BY redcms_hits.hit_date, redcms_hits.hit_time DESC";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    return $num;

  }

  function loginForm() {

  ?>

<form method="post" action="login.php">

  Username: <input type="text" name="username" value="<?php echo $username; ?>"> <br><br>

  Password: <input type="password" name="password"> <br><br>

  <input type="hidden" name="login" value="TRUE">

<input type="Submit" value="Login"><br>

  <?php

  }

?>