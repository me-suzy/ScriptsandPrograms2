<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/PROFILE.PHP > 03-11-2005

$start = TRUE; 
include("system/include.php"); 
if ($checkauth) { 


if (!$action) { 
	$result = mysql_query("SELECT * FROM ".$prefix."profile WHERE username = '$user'"); 
	while($row=mysql_fetch_object($result)) { 
		$username = $row->username; 
		$nickname = $row->nickname; 
		$firstname = $row->firstname; 
		$lastname = $row->lastname; 
		$gender = $row->gender; 
		$dob = $row->dob; 
		$country = $row->country; 
		$city = $row->city; 
		$email = $row->email; 
		$url = $row->url; 
		$icq = $row->icq; 
		$msn = $row->msn; 
		$aim = $row->aim; 
		$yim = $row->yim; 
		$avatar = $row->avatar; 
		$signature = $row->signature; 
	} 
	if (!$nickname) $nickname = $user; 
	if ($dob) { 
		$dobm = substr($dob,0,2); 
		$dobd = substr($dob,2,2); 
		$doby = substr($dob,4,4); 
		if ($dobm{0} == "0") { $dobm = ereg_replace("0","",$dobm); }
		if ($dobd{0} == "0") { $dobd = ereg_replace("0","",$dobd); }
	} 
 
	echo "<strong>General information</strong><br />
		<table><tr><td><form method='post' action='".$PHP_SELF."'></td></tr></table>
		<input type='hidden' name='action' value='update'>
		<table>
		<tr><td width=175>Username</td> 
		    <td>$username</td></tr> 
		<tr><td width=175>Level</td> 
		    <td>".$levelname[$userdata['level']]; 
	if ($userdata['level'] > 0 && $userdata['level'] <= 4) { 
		echo " ("; 
		while ($foo < $userdata['level']) { 
			$foo++; 
			echo "*"; 
		} 
		echo ")"; 
	} else { 
		if ($level != 0) echo "(invalid level)"; 
	} 
	echo "</td></tr></table><br>"; 
	echo "<b>Personal information</b><br /><br /><table>"; 
	echo "<tr><td width=175>Nickname</td><td><input size=50 name='nickname' type='text' value='".$nickname."'></td></tr>"; 
	echo "<tr><td width=175>First name</td><td><input size=50 name='firstname' type='text' value='".$firstname."'></td></tr>"; 
	echo "<tr><td width=175>Last name</td><td><input size=50 name='lastname' type='text' value='".$lastname."'></td></tr>"; 
	echo "<tr><td width=175>Gender</td><td>"; 
	 
	if ($gender == 0) echo "<input type='radio' name='gender' value='0' CHECKED> Male <input type='radio' name='gender' value='1'> Female";
	if ($gender == 1) echo "<input type='radio' name='gender' value='0'> Male <input type='radio' name='gender' value='1' CHECKED> Female"; 
 
	echo "</td></tr>"; 
	echo "<tr><td width=175>Date of birth</td><td><select name='dobm'>"; 
 
	$foo = 0; 
	while ($foo != 12) { 
		$foo++; 
		if ($foo == $dobm) {  
			echo "<option value='$foo' selected>$months[$foo]"; 
	 	} else { 
			echo "<option value='$foo'>$months[$foo]"; 
		} 
	} 
 
	echo "</select><select name='dobd'>"; 
 
	$foo = 0; 
	while ($foo != 31) { 
		$foo++; 
		if ($foo == $dobd) {  
			echo "<option value='$foo' selected>$foo"; 
	 	} else { 
			echo "<option value='$foo'>$foo"; 
		} 
	} 
	$foo=0; 
 
	echo "</select><select name='doby'>"; 
 
	$foo = 1900; 
	while ($foo != 2000) { 
		$foo++; 
		if ($foo == $doby) { 
			echo "<option value='$foo' selected>$foo"; 
		} else { 
			if ($foo == 1970) { 
				echo "<option value='$foo' selected>$foo"; 
			} else { 
				echo "<option value='$foo'>$foo"; 
			} 
		} 
	} 
 
	echo "</select></td></tr>"; 
	echo "<tr><td width=175>Country</td><td><input size=50 name='country' type='text' value='".$country."'></td></tr>"; 
	echo "<tr><td width=175>City</td><td><input size=50 name='city' type='text' value='".$city."'><br></td></tr></table><br>"; 
 
	echo "<b>Contact information</b><br /><br /><table>"; 
	echo "<tr><td width=175>Email</td><td><input size=50 name='email' type='text' value='".$email."'></td></tr>"; 
	echo "<tr><td width=175>Icq</td><td><input size=50 name='icq' type='text' value='".$icq."'></td></tr>"; 
	echo "<tr><td width=175>Msn</td><td><input size=50 name='msn' type='text' value='".$msn."'></td></tr>"; 
	echo "<tr><td width=175>Aim</td><td><input size=50 name='aim' type='text' value='".$aim."'></td></tr>"; 
	echo "<tr><td width=175>Yim</td><td><input size=50 name='yim' type='text' value='".$yim."'></td></tr></table><br>"; 
 
	echo "<b>Other information</b><br /><br /><table>"; 
	echo "<tr><td width=175>Url</td><td><input size=50 name='url' type='text' value='".$url."'></td></tr>"; 
	echo "<tr><td width=175>Avatar</td><td><input size=50 name='avatar' type='text' value='".$avatar."'></td></tr>"; 
	echo "<tr><td width=175 valign=top>Signature</td><td><textarea name='signature' rows=7 cols=50>".$signature."</textarea></td></tr></table><br>"; 
 
	echo "<b>Confirm</b><br /><br /><table><tr><td width='175'>Save changes</td><td align='left'>"; 
	echo "<input type='submit' value='proceed'></td></tr></table>"; 
} elseif ($action == "update") { 
	if ($dobm || $doby || $dobd) { 
		if ($dobd < 10) $dobd = "0".$dobd; 
		if ($dobm < 10) $dobm = "0".$dobm; 
		$dob = $dobm.$dobd.$doby; 
	} 
	if ($avatar) { 
		$avatarleft = substr($avatar,0,7); 
		if ($avatarleft != "http://") $avatar = "http://".$avatar; 
	}	 
	if ($url) { 
		$urleft = substr($url,0,7); 
		if ($urleft != "http://") $url = "http://".$url; 
	} 
 
	$signature = cleanstring($signature); 
 
	if (!$nickname) $nickname = $login; 
	if (!$email) { 
		echo $error[10]; 
	} else { 
	$result = mysql_query("UPDATE ".$prefix."profile SET  
		nickname='".strip_tags($nickname)."', 
		firstname='".strip_tags($firstname)."', 
		lastname='".strip_tags($lastname)."', 
		gender='".strip_tags($gender)."', 
		dob='$dob', 
		country='".strip_tags($country)."', 
		city='".strip_tags($city)."', 
		email='".strip_tags($email)."', 
		url='".strip_tags($url)."', 
		icq='".strip_tags($icq)."', 
		msn='".strip_tags($msn)."', 
		aim='".strip_tags($aim)."', 
		yim='".strip_tags($yim)."', 
		avatar='".strip_tags($avatar)."', 
		signature='".strip_tags($signature)."'  
	WHERE username='$user'"); 
	echo "<meta http-equiv=Refresh content=0;URL='profile.php'>"; 
	} 
} elseif ($action == "view") { 
	$result = mysql_query("SELECT * FROM ".$prefix."profile WHERE username = '$username'"); 
	while($row=mysql_fetch_object($result)) { 
		$username = $row->username; 
		$nickname = $row->nickname; 
		$firstname = $row->firstname; 
		$lastname = $row->lastname; 
		$gender = $row->gender; 
		$dob = $row->dob; 
		$country = $row->country; 
		$city = $row->city; 
		$email = $row->email; 
		$url = $row->url; 
		$icq = $row->icq; 
		$msn = $row->msn; 
		$aim = $row->aim; 
		$yim = $row->yim; 
		$avatar = $row->avatar; 
		$signature = $row->signature; 
	} 
	if (!$nickname) $nickname = $login; 
	if ($dob) { 
		$dobm = substr($dob,0,2); 
		$dobd = substr($dob,2,2); 
		$doby = substr($dob,4,4); 
		if ($dobm{0} == "0") { $dobm = ereg_replace("0","",$dobm); }
		if ($dobd{0} == "0") { $dobd = ereg_replace("0","",$dobd); }
	} 
 
	echo "<b>General information</b><br><table>"; 
	echo "<tr><td width=175>Username</td><td>$username</td></tr></table><br>"; 
	echo "<b>personal information</b><br><table>"; 
	echo "<tr><td width=175>Nickname</td><td>$nickname</td></tr>"; 
	echo "<tr><td width=175>First name</td><td>$firstname</td></tr>"; 
	echo "<tr><td width=175>Last name</td><td>$lastname</td></tr>"; 
	echo "<tr><td width=175>Gender</td><td>"; 
	 
	if ($gender == 0) echo "Male"; 
	if ($gender == 1) echo "Female"; 
 
	echo "</td></tr>"; 
	echo "<tr><td width=175>Date of birth</td><td>".$dobd."/".$months[$dobm]."/".$doby."</td></tr>"; 
	echo "<tr><td width=175>Country</td><td>$country</td></tr>"; 
	echo "<tr><td width=175>City</td><td>$city</td></tr></table><br>"; 
 
	echo "<b>Contact information</b><br><table>"; 
	echo "<tr><td width=175>Email</td><td>$email</td></tr>"; 
	echo "<tr><td width=175>Icq</td><td>$icq</td></tr>"; 
	echo "<tr><td width=175>Msn</td><td>$msn</td></tr>"; 
	echo "<tr><td width=175>Aim</td><td>$aim</td></tr>"; 
	echo "<tr><td width=175>Yim</td><td>$yim</td></tr></table><br>"; 
 
	echo "<b>Other information</b><br><table>"; 
	echo "<tr><td width=175>Url</td><td>$url</td></tr>"; 
	echo "<tr><td width=175>Avatar</td><td>$avatar</td></tr>"; 
	echo "<tr><td width=175 valign=top>Signature</td><td>$signature</td></tr></table><br>"; 
} 
?> 
 
<?php }; $start = FALSE; include("system/include.php"); ?> 
