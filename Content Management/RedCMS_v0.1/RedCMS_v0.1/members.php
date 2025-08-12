<?php

  session_start();

  $l = "members";

  include"top.php";

  connect();

  $sql = "SELECT * FROM redcms_users ORDER BY redcms_users.user_uname";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  echo "<table width='100%'>";
  echo "<tr class='tr1'><td><b>Username</b></td><td><b>Gender</b></td><td><b>Age</b></td><td><b>Email</b></td><td><b>Location</b></td></tr>";

  for($i = 0; $i < $num; $i++) {

    $userID = mysql_result($result, $i, "redcms_users.user_id");
    $userUName = mysql_result($result, $i, "redcms_users.user_uname");
    $userLevel = mysql_result($result, $i, "redcms_users.user_level");
    $userName = mysql_result($result, $i, "redcms_users.user_name");
    $userEmail = mysql_result($result, $i, "redcms_users.user_email");
    $userLocation = mysql_result($result, $i, "redcms_users.user_location");
    $userGender = mysql_result($result, $i, "redcms_users.user_gender");
    $userDOB = mysql_result($result, $i, "redcms_users.user_dob");
    $userWebsite = mysql_result($result, $i, "redcms_users.user_site");
    $userMSN = mysql_result($result, $i, "redcms_users.user_msn");
    $userAIM = mysql_result($result, $i, "redcms_users.user_aim");
    $userYahoo = mysql_result($result, $i, "redcms_users.user_yahoo");
    $userICQ = mysql_result($result, $i, "redcms_users.user_icq");
    $userJoinedDate = mysql_result($result, $i, "redcms_users.user_joined_date");
    $userJoinedTime = mysql_result($result, $i, "redcms_users.user_joined_time");

    $userAge = age($userDOB);

    echo "<tr class='tr2'>";
    echo "<td><a href='profile.php?id=" . $userID . "'>" . $userUName . "</a></td>";
    echo "<td>" . $userGender . "</td>";
    echo "<td>" . $userAge . "</td>";
    echo "<td><a href='mailto:" . $userEmail . "'>" . $userEmail . "</a></td>";
    echo "<td><a href='http://maps.google.co.uk/?q=" . $userLocation . "'>" . $userLocation . "</a></td>";
    echo "</tr>";

  }

  echo "</table>";
 
  include"bottom.php";

?>