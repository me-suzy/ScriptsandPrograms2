<?
##############################################################################
#                                                                            #
#                              admin.php                                     #
#                                                                            #
##############################################################################
# PROGRAM : E-MatchMaker                                                     #
# VERSION : 1.51                                                             #
#                                                                            #
# NOTES   : site using default layout and graphics                           #
##############################################################################
# All source code, images, programs, files included in this distribution     #
# Copyright (c) 2001-2002                                                    #
# Supplied by          : CyKuH [WTN]                                         #
# Nullified by         : CyKuH [WTN]                                         #
# Distribution:        : via WebForum and xCGI Forums File Dumps             #
##############################################################################
#                                                                            #
#    While we distribute the source code for our scripts and you are         #
#    allowed to edit them to better suit your needs, we do not               #
#    support modified code.  Please see the license prior to changing        #
#    anything. You must agree to the license terms before using this         #
#    software package or any code contained herein.                          #
#                                                                            #
#    Any redistribution without permission of MatchMakerSoftware             #
#    is strictly forbidden.                                                  #
#                                                                            #
##############################################################################
?>
<?

require_once("siteconfig.php");

$admincookiestring = $mmconfig->adminusername . ":" . $mmconfig->adminpassword;

if(!empty($adminlogin)) {
  $adminstring = implode(":", $adminlogin);
  if($adminstring == $admincookiestring) {
    setcookie('admincookie', $adminstring, 0);
    if($mmconfig->securemode == 0) header("Location: admin.php");
  }
}

if($admincookie != $admincookiestring) {
  echo "<HTML><HEAD><TITLE>Restricted Area</TITLE></HEAD><BODY>";
  echo "<FORM action=$mmconfig->webaddress/admin.php method=post><h1>Admin Login</h1>";
  echo "Username: <input type=text name='adminlogin[]'><BR>";
  echo "Password: <input type=password name='adminlogin[]'><BR>";
  echo "<input type=submit value=Login></FORM></BODY></HTML>";
  exit;
}

if($action == "showpic") {
  $recordSet = &$db->Execute("select image, image_type from profile_pic where username = '$username'");
   $count = $recordSet->RecordCount();
   if($count) {
      $image = $recordSet->Fields("image");
      $image_type = $recordSet->Fields("image_type");
      header("Content-type: $image_type");
      echo $image;
      exit;
   }
}

?>
<title>Admin Area</title>
</head>
<body>
<p align="center">
<nobr>[&nbsp;<a href="admin.php?action=stats">Demographics</a>&nbsp;]</nobr> 
<nobr>[&nbsp;<a href="admin.php?action=approvepic">Approve&nbsp;Pictures</a>&nbsp;]</nobr> 
<nobr>[&nbsp;<a href="admin.php?action=emailusers">Email&nbsp;All&nbsp;Users</a>&nbsp;]</nobr> 
<nobr>[&nbsp;<a href="admin.php?action=usermaint">User&nbsp;Maintenance</a>&nbsp;]</nobr>
<nobr>[&nbsp;<a href="admin.php?action=ibillpin">Add&nbsp;Ibill&nbsp;Pincodes</a>&nbsp;]</nobr>
</p>
<?

if($action == "ibillupload") {
  $fp = fopen($userfile, "r");           
  while(!feof($fp)) {
    $pincode = fgets($fp, 10);
    $db->Execute("insert into ibill (pincode, submit_date) values ($pincode, now())");
  }
  echo "<h1>Upload Complete</h1>";
}

if($action == "ibillpin") {
?>
<form enctype="multipart/form-data" action="admin.php" method=post>
<input type=hidden name=action value="ibillupload">
<h1>Upload Ibill pincodes</h1>
Please select Ibill pincode file to upload:<br>
<input type=file name=userfile length=50 maxlength=255>
<input type=submit value=Submit></form>
<?
exit;
}

if($action == "stats") {
  require_once("select_values.php");
  $recordSet = $db->Execute("select * from profile");
  $count = $recordSet->RecordCount();
  for($i=1; $i <= $count; $i++) {
    $age_string .= $recordSet->Fields("age");
    $state_string .= $recordSet->Fields("state");
    $height_string .= $recordSet->Fields("height");
    $hair_string .= $recordSet->Fields("hair");
    $sex_string .= $recordSet->Fields("sex");
    $race_string .= $recordSet->Fields("race");
    $eyes_string .= $recordSet->Fields("eyes");
    $kids_string .= $recordSet->Fields("kids");
    $build_string .= $recordSet->Fields("build");
    $smoke_string .= $recordSet->Fields("smoke");
    $education_string .= $recordSet->Fields("education");
    $income_string .= $recordSet->Fields("income");
    $drink_string .= $recordSet->Fields("drink");
    $relation_string .= $recordSet->Fields("relation");
    $travel_string .= $recordSet->Fields("travel");
    $city_string .= trim(strtolower($recordSet->Fields("city")));
    $zipcode_string .= $recordSet->Fields("zipcode");
    $haspicture_string .= $recordSet->Fields("haspicture");
    if($i <= ($count - 1)) {
      $age_string .= ":";
      $state_string .= ":";
      $height_string .= ":";
      $hair_string .= ":";
      $sex_string .= ":";
      $race_string .= ":";
      $eyes_string .= ":";
      $kids_string .= ":";
      $build_string .= ":";
      $smoke_string .= ":";
      $education_string .= ":";
      $income_string .= ":";
      $drink_string .= ":";
      $relation_string .= ":";
      $travel_string .= ":";
      $city_string .= ":";
      $zipcode_string .= ":";
      $haspicture_string .= ":";

    }
    $recordSet->MoveNext();
  }
  $recordSet = $db->Execute("select id from login_data where date_add(now(), interval -1 day) < lastlogin");
  $login_today = $recordSet->RecordCount();
  $recordSet = $db->Execute("select id from login_data where date_add(now(), interval -7 day) < lastlogin");
  $login_lweek = $recordSet->RecordCount();
  $recordSet = $db->Execute("select id from login_data where date_add(now(), interval -30 day) < lastlogin");
  $login_lmonth = $recordSet->RecordCount();
  $recordSet = $db->Execute("select id from login_data");
  $new_total = $recordSet->RecordCount();
  $recordSet = $db->Execute("select id from login_data where date_add(now(), interval -1 day) < usersince");
  $new_today = $recordSet->RecordCount();
  $recordSet = $db->Execute("select id from login_data where date_add(now(), interval -7 day) < usersince");
  $new_lweek = $recordSet->RecordCount();
  $recordSet = $db->Execute("select id from login_data where date_add(now(), interval -30 day) < usersince");
  $new_lmonth = $recordSet->RecordCount();
  $recordSet = $db->Execute("select username from verified where date_add(now(), interval -1 day) < verified_date");
  $verified_today = $recordSet->RecordCount();
  $recordSet = $db->Execute("select username from verified where date_add(now(), interval -7 day) < verified_date");
  $verified_lweek = $recordSet->RecordCount();
  $recordSet = $db->Execute("select username from verified where date_add(now(), interval -30 day) < verified_date");
  $verified_lmonth = $recordSet->RecordCount();
  $recordSet = $db->Execute("select username from profile_pic where approved = 1");
  $approved_pic = $recordSet->RecordCount();
  $age_array = explode(":", $age_string);
  $age_array = array_count_values($age_array);
  ksort($age_array);
  $age_karray = array_keys($age_array);
  $state_array = explode(":", $state_string);
  $state_array = array_count_values($state_array);
  ksort($state_array);
  $state_karray = array_keys($state_array);
  $height_array = explode(":", $height_string);
  $height_array = array_count_values($height_array);
  ksort($height_array);
  $height_karray = array_keys($height_array);
  $hair_array = explode(":", $hair_string);
  $hair_array = array_count_values($hair_array);
  ksort($hair_array);
  $hair_karray = array_keys($hair_array);
  $sex_array = explode(":", $sex_string);
  $sex_array = array_count_values($sex_array);
  ksort($sex_array);
  $sex_karray = array_keys($sex_array);
  $race_array = explode(":", $race_string);
  $race_array = array_count_values($race_array);
  ksort($race_array);
  $race_karray = array_keys($race_array);
  $eyes_array = explode(":", $eyes_string);
  $eyes_array = array_count_values($eyes_array);
  ksort($eyes_array);
  $eyes_karray = array_keys($eyes_array);
  $kids_array = explode(":", $kids_string);
  $kids_array = array_count_values($kids_array);
  ksort($kids_array);
  $kids_karray = array_keys($kids_array);
  $build_array = explode(":", $build_string);
  $build_array = array_count_values($build_array);
  ksort($build_array);
  $build_karray = array_keys($build_array);
  $smoke_array = explode(":", $smoke_string);
  $smoke_array = array_count_values($smoke_array);
  ksort($smoke_array);
  $smoke_karray = array_keys($smoke_array);
  $education_array = explode(":", $education_string);
  $education_array = array_count_values($education_array);
  ksort($education_array);
  $education_karray = array_keys($education_array);
  $income_array = explode(":", $income_string);
  $income_array = array_count_values($income_array);
  ksort($income_array);
  $income_karray = array_keys($income_array);
  $drink_array = explode(":", $drink_string);
  $drink_array = array_count_values($drink_array);
  ksort($drink_array);
  $drink_karray = array_keys($drink_array);
  $relation_array = explode(":", $relation_string);
  $relation_array = array_count_values($relation_array);
  ksort($relation_array);
  $relation_karray = array_keys($relation_array);
  $travel_array = explode(":", $travel_string);
  $travel_array = array_count_values($travel_array);
  ksort($travel_array);
  $travel_karray = array_keys($travel_array);
  $city_array = explode(":", $city_string);
  $city_array = array_count_values($city_array);
  ksort($city_array);
  $city_karray = array_keys($city_array);
  $zipcode_array = explode(":", $zipcode_string);
  $zipcode_array = array_count_values($zipcode_array);
  ksort($zipcode_array);
  $zipcode_karray = array_keys($zipcode_array);
  $haspicture_array = explode(":", $haspicture_string);
  $haspicture_array = array_count_values($haspicture_array);
  ksort($haspicture_array);
  $haspicture = $haspicture_array[1];
  echo "<TABLE border=1><TR><TD>Age</TD><TD>Num</TD><TD>State</TD><TD>Num</TD><TD>Height</TD><TD>Num</TD><TD>Hair</TD><TD>Num</TD><TD>Sex</TD><TD>Num</TD><TD>Race</TD><TD>Num</TD><TD>Eyes</TD><TD>Num</TD><TD>Kids</TD><TD>Num</TD><TD>Build</TD><TD>Num</TD><TD>Smoke</TD><TD>Num</TD><TD>Education</TD><TD>Num</TD><TD>Income</TD><TD>Num</TD><TD>Drink</TD><TD>Num</TD><TD>Relation</TD><TD>Num</TD><TD>Travel</TD><TD>Num</TD><TD>City</TD><TD>Num</TD><TD>Zipcode</TD><TD>Num</TD></TR>";
  for($i=0; $i < $count; $i++) {
    echo "<TR>"; 
    echo "<TD>" . $age_values[$age_karray[$i]] . "</TD><TD>" . $age_array[$age_karray[$i]] . "</TD>";
    echo "<TD>" . $state_values[$state_karray[$i]] . "</TD><TD>" . $state_array[$state_karray[$i]] . "</TD>";
    echo "<TD>" . $height_values[$height_karray[$i]] . "</TD><TD>" . $height_array[$height_karray[$i]] . "</TD>";
    echo "<TD>" . $hair_values[$hair_karray[$i]] . "</TD><TD>" . $hair_array[$hair_karray[$i]] . "</TD>";
    echo "<TD>" . $sex_values[$sex_karray[$i]] . "</TD><TD>" . $sex_array[$sex_karray[$i]] . "</TD>";
    echo "<TD>" . $race_values[$race_karray[$i]] . "</TD><TD>" . $race_array[$race_karray[$i]] . "</TD>";
    echo "<TD>" . $eyes_values[$eyes_karray[$i]] . "</TD><TD>" . $eyes_array[$eyes_karray[$i]] . "</TD>";
    echo "<TD>" . $kids_values[$kids_karray[$i]] . "</TD><TD>" . $kids_array[$kids_karray[$i]] . "</TD>";
    echo "<TD>" . $build_values[$build_karray[$i]] . "</TD><TD>" . $build_array[$build_karray[$i]] . "</TD>";
    echo "<TD>" . $smoke_values[$smoke_karray[$i]] . "</TD><TD>" . $smoke_array[$smoke_karray[$i]] . "</TD>";
    echo "<TD>" . $education_values[$education_karray[$i]] . "</TD><TD>" . $education_array[$education_karray[$i]] . "</TD>";
    echo "<TD>" . $income_values[$income_karray[$i]] . "</TD><TD>" . $income_array[$income_karray[$i]] . "</TD>";
    echo "<TD>" . $drink_values[$drink_karray[$i]] . "</TD><TD>" . $drink_array[$drink_karray[$i]] . "</TD>";
    echo "<TD>" . $relation_values[$relation_karray[$i]] . "</TD><TD>" . $relation_array[$relation_karray[$i]] . "</TD>";
    echo "<TD>" . $travel_values[$travel_karray[$i]] . "</TD><TD>" . $travel_array[$travel_karray[$i]] . "</TD>";
    echo "<TD>" . $city_karray[$i] . "</TD><TD>" . $city_array[$city_karray[$i]] . "</TD>";
    echo "<TD>" . $zipcode_karray[$i] . "</TD><TD>" . $zipcode_array[$zipcode_karray[$i]] . "</TD>";
    echo "</TR>";
  }
  echo "</TABLE>";
  echo "<TABLE border=1><TR><TD>New Users</TD><TD>Num</TD><TD>Unique Logins</TD><TD>Num</TD><TD>New Verified Members</TD><TD>Num</TD></TR>";
  echo "<TR><TD>Today</TD><TD>$new_today</TD><TD>Today</TD><TD>$login_today</TD><TD>Today</TD><TD>$verified_today</TD></TR>";
  echo "<TR><TD>Last 7 Days</TD><TD>$new_lweek</TD><TD>Last 7 Days</TD><TD>$login_lweek</TD><TD>Last 7 Days</TD><TD>$verified_lweek</TD></TR>";
  echo "<TR><TD>Last 30 Days</TD><TD>$new_lmonth</TD><TD>Last 30 Days</TD><TD>$login_lmonth</TD><TD>Last 30 Days</TD><TD>$verified_lmonth</TD></TR>";
  echo "<TR><TD>Grand Total</TD><TD>$new_total</TD></TR>";
  echo "</TABLE>";
  exit;

}

if($action == "verified") {
  if($formaction == "update") {
    $update = $db->Execute("update verified set verified_by = '$verified_by', verified_date = now(), comments = '$comments' where username = '$username'");
    $update = $db->Execute("update profile set isverified = 1 where username = '$username'");
  }
  $recordSet = $db->Execute("select verified.* from verified, profile where verified.username = profile.username AND profile.isverified = 0");
  
  while(!$recordSet->EOF) {
    echo "<FORM action=admin.php method=post>";
    echo "<input type=hidden name=action value=verified><input type=hidden name=formaction value=update>";
    echo "<input type=hidden name=username value='" . $recordSet->fields('username') . "'>";
    echo "Name: " . $recordSet->fields('f_name') . " " . $recordSet->fields('l_name') . "<br>";
    echo "Address: <BR>" . $recordSet->Fields('address') . "<br>";
    echo $recordSet->Fields('city') . ", " . $recordSet->Fields('state') . "   " .  $recordSet->Fields('zipcode') . "<br><br>";
    echo "Telephone: " . $recordSet->Fields('telephone') . "<BR>";
    echo "Paypal Email Address: " . $recordSet->Fields('paypalemail') . "<BR>";
    echo "Verified By: <input type=text name=verified_by value='Administrator'><BR><BR>";
    echo "Comments for database: <textarea name=comments rows=3 cols=50>Type your comments here</textarea><BR>";
    echo "<input type=submit value='Make Member Verified'></form>";
    $recordSet->MoveNext();
  }
  exit;
}

if($action == "approvepic") {
  if($formaction == "approved") {
    $result = $db->Execute("update profile_pic set approved = 1, approval_date = now() where username = '$username'");
    @mail($email, "$mmconfig->website Picture Approved", "Thank you for submitting your picture to $mmconfig->website.
		\nYour picture has been approved. Please login to $mmconfig->webaddress today to 
		\nfind your ideal match.
		\n\nThank You,
		\n$mmconfig->website staff", "From: $mmconfig->webmaster");
  }
  if($formaction == "denied") {
    $result = $db->Execute("delete from profile_pic where username = '$username'");
    $result = $db->Execute("update profile set haspicture = 0 where username = '$username'");
    $email = $db->Execute("select email from login_data where username = '$username'");
    @mail($email, "$mmconfig->website Picture Denied", "We are sorry to inform you your picture was denied because it
		\ndid not meet our quality standards for one reason or another.
		\nIf you wish to dispute this please email $mmconfig->webmaster
		\n\nThank You,
		\nthe staff of $mmconfig->website", "From: $mmconfig->webmaster");
    // email user that they were denied
  }

  $recordSet = $db->Execute("select username from profile_pic where approved = 0");
  
  while(!$recordSet->EOF) {
    echo "<FORM action=admin.php method=post><input type=hidden name=action value=approvepic>";
    echo "<input type=hidden name=username value='" . $recordSet->fields('username') . "'>";
    echo "User: " . $recordSet->fields('username') . "<BR>";
    echo "Image: <BR> <img src=admin.php?action=showpic&username=" . $recordSet->fields('username') . ">";
    echo "<BR><input type=submit name=formaction value=approved><input type=submit name=formaction value=denied>";
    echo "</form>";
    $recordSet->MoveNext();
  }
  exit;
}

if($action == "deleteusr") {
  if(isset($user)) {
    if(isset($confirm)) {
      $sql = "select email from login_data where username = '$user'";
      $sql1 = "delete from login_data where username = '$user'";
      $sql2 = "delete from profile where username = '$user'";
      $sql3 = "delete from profile_pic where username = '$user'";
      $sql4 = "delete from verified where username = '$user'";
      $recordSet = $db->Execute($sql);
      $db->Execute($sql1);
      $db->Execute($sql2);
      $db->Execute($sql3);
      $db->Execute($sql4);
      $email = $recordSet->Fields("email");
      @mail($email, "Your account has been deleted", $reason, "From: $mmconfig->webmaster");
    }
    else {
    echo "<HTML><BODY><FORM action='admin.php' method=post>Are you sure you want to delete user <b>'$user'</b>? &nbsp; &nbsp;";
    echo "<input type=hidden name=action value=$action><input type=hidden name=confirm value=1>";
    echo "<input type=hidden name=user value=$user><input type=submit name=submit value='Delete User'><BR>";
    echo "Reason:<br><textarea name=reason rows=5 cols=80>Your Account at $mmconfig->website was deleted because:</textarea>";
    echo "</form></body></html>";
    exit;
    }
  }
exit;
}
if($action == "usermaint") {
  echo "<html><body>";
  echo "<form action=admin.php method=post><input type=hidden name=action value=usermaint>";
  echo "You can input anything into the search box below. <br>For example typing <b>&nbsp;J&nbsp;</b> will return"; 
  echo " every user with <b>&nbsp;J&nbsp;</b> in their username, first name, or last name.<br> Typing <b>&nbsp;all&nbsp;</b> 
will return all users (may take "; 
  echo "a few seconds for page to load on larger sites)<br><br>";
  echo "Search Criteria &nbsp;<input type=text name=userlookup><input type=submit></form>";

 if(isset($userlookup) && $userlookup != "") {
echo "<table border=1>";
  echo "<tr align=center><td>ID</td><td>Username</td><td>Password</td><td>Change Member Status</td><td>Delete 
User?</td><td>Edit User's Profile</td>
<td>Real Name</td><td>email</td><td>User Since</td><td>Last Login</td>
</tr>";
  if(strtolower($userlookup) == "all") {
    $sql = "select * from login_data";
  }
  else {
     $sql = "select login_data.* from login_data where login_data.username like '%$userlookup%' or login_data.f_name like '%$userlookup%' or login_data.l_name like '%$userlookup%'";
  }
  $recordSet = $db->Execute($sql);

  while(!$recordSet->EOF) {
    $id = $recordSet->Fields("id");
    $f_name = $recordSet->Fields("f_name");
    $l_name = $recordSet->Fields("l_name");
    $email = $recordSet->Fields("email");
    $user = $recordSet->Fields("username");
    $password = $recordSet->Fields("password");
    $usersince = $recordSet->Fields("usersince");
    $lastlogin = $recordSet->Fields("lastlogin");
    $premium = $recordSet->Fields("pmember");
    $recordSet2 = $db->Execute("select profile.isverified from profile where username = '$user'");
    $isverified = $recordSet2->Fields("isverified");

    echo " <TR bgcolor='#FFFFFF' onMouseOver=this.bgColor='gold'; onMouseOut=this.bgColor='#FFFFFF'; height=5>\n";
    echo "<td>$id</td><td><a href=showprofile.php?user=$user&hash=2434e44d9x9330a0d30c6e6cec33195393319525718283efadb1f5a target=_blank>$user</a></td>";
    echo "<td>$password</td>";
    if($premium)
       echo "<td align=center valign=middle><form action='admin.php?action=setunverified&userlookup=$userlookup' 
method=post><input type=hidden name='user' value='$user'><input type=submit value='To Normal'></FORM>";
    else
       echo "<td align=center valign=middle><form action='admin.php?action=setverified&userlookup=$userlookup' 
method=post><input type=hidden name='user' value='$user'><input type=submit value='To Premium'></FORM>";
    echo "<td align=center valign=middle><form action=admin.php><input type=hidden name=action value=deleteusr>";
    echo "<input type=hidden name='user' value='$user'><input type=submit value=Delete></form></td>";
    echo "<td align=center valign=middle><form action=admin.php><input type=hidden name=action value=editprofile>";
    echo "<input type=hidden name=user value=$user><input type=submit value=Edit></form></td>";
    echo "<td>$f_name $l_name</td>";
    echo "<td>$email</td><td>$usersince</td><td>$lastlogin</td></tr>";    
    $recordSet->MoveNext();
  }
 }
  echo "</table></form></html>";
  exit;
}

if($action == "setverified") {
  if(isset($user)) {
     $update = $db->Execute("update login_data set pmember = 1 where username = '$user'");
  }
}
if($action == "setunverified") {
  if(isset($user)) {
    $update = $db->Execute("update login_data set pmember = 0 where username = '$user'");
  }
}

if($action == "editprofile") {
  if(isset($user)) {
    if($modeofform == "update") {
        $city=addslashes($city);
        $state=addslashes($state);
        $catch=addslashes($catch);
        $about=addslashes($about);
        $looking=addslashes($looking);
        $username=addslashes($username);
        if(!empty($interest)) {
           $interest=implode(":", $interest);
        }    
        $sql="update profile set
                age=$age,
                height=$height,
                hair=$hair,
                sex=$sex,
                race=$race,
                eyes=$eyes,
                kids=$kids,
                build=$build,
                interest='$interest',
                smoke=$smoke,
                education=$education,
                income=$income,
                drink=$drink,
                relation=$relation,
                travel=$travel,
                city='$city',
                state='$state',
                zipcode=$zipcode,
                catch='$catch',
                about='$about',
                looking='$looking'
                where username='$user'";
        $db->Execute($sql);  
        exit;
    }

  $sql="select * from profile where username='$user'";
  $recordSet = &$db->Execute($sql);

  $age=$recordSet->Fields("age");
  $height=$recordSet->Fields("height");
  $hair=$recordSet->Fields("hair");  
  $sex=$recordSet->Fields("sex");
  $race=$recordSet->Fields("race");  
  $eyes=$recordSet->Fields("eyes");  
  $kids=$recordSet->Fields("kids");
  $build=$recordSet->Fields("build");
  $interest=split(":", $recordSet->Fields("interest"));
  $smoke=$recordSet->Fields("smoke");
  $education=$recordSet->Fields("education");
  $income=$recordSet->Fields("income");
  $drink=$recordSet->Fields("drink");
  $relation=$recordSet->Fields("relation");
  $travel=$recordSet->Fields("travel");
  $city=$recordSet->Fields("city");  
  $state=$recordSet->Fields("state");
  $zipcode=$recordSet->Fields("zipcode");
  $catch=stripslashes($recordSet->Fields("catch"));
  $about=stripslashes($recordSet->Fields("about"));
  $looking=stripslashes($recordSet->Fields("looking"));

  include("writecombo.php");
  include("select_values.php");
  $modeofform = "update";
  include("static/editprofile.html");
  exit;

  }
}

if($action == "logout") {
  setcookie("admincookie");
  exit;
}

if($action == "emailusers") {
if($submit) {
  $recordSet = $db->Execute("select f_name, l_name, email from login_data");
  
  while(!$recordSet->EOF) {
    $f_name = $recordSet->fields("f_name");
    $l_name = $recordSet->fields("l_name");  
    $email = $recordSet->fields("email");            
    $from = addslashes($from);
    
    @mail("\"$f_name $l_name\" <$email>", addslashes($subject), addslashes($message), "From: $from\r\n");
    
    $recordSet->MoveNext();
  }
  exit;
}
  
?>

<form action="admin.php" method=post>
<input type=hidden name=action value=emailusers>
From:&nbsp;<input type=text name=from size=42>&nbsp;&nbsp;In the format "Your Name" 
&ltemail@address.com&gt<br>
Subject:&nbsp;<input type=text name=subject size=40><br>
<br>
Message:<br>
<textarea name=message cols=40 rows=10></textarea>
<input type=submit name=submit value=Send>
</form>

<?
exit;
}


?>

