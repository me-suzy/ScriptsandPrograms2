<?
echo '<p><a href="index.php?page=members" />Approved members</a> | <b>Trialists</b> | <a href="index.php?page=umem" />Unapproved members</a></p>';
$trialshow = "SELECT users.approved,users.username,users.email,characters.charactername,characters.main,users.trialstart,users.trialend FROM users,characters WHERE users.username=characters.username AND users.rank='3'";
$show = mysql_query($trialshow, $db_conn) or die("Query [trialshow] Failed: ".mysql_error());
echo '<table class="log" cellspacing="5" cellpadding="5"><tr><td><u>Username</u></td><td><u>Character name</u></td><td><u>Email</u></td><td><u>Trial start date</u></td><td><u>Trial end date</u></td><td></td><td></td></tr>';
while ($row = mysql_fetch_assoc($show)) 
{
	if ($row['approved']==1)
	{
	echo '<tr>';
	echo '<td>'.$row['username'].'</td>';
	echo '<td>'.$row['charactername'].'</td>';
	echo '<td>'.$row['email'].'</td>';
	echo '<td>'.$row['trialstart'].'</td>';
	echo '<td>'.$row['trialend'].'</td>';
	echo '<td><a href="index.php?page=trialists&&app='.$row['username'].'">User passed, promote to member<br />(end trial)</a></td>';
	echo '<td><a href="index.php?page=delu&&del='.$row['username'].'" />User failed trial<br />(remove from database)</a></td>';
	echo '</tr>';
	}
}
echo '</table>';
if (isset($_GET['app']))
		{
	$userapp = "UPDATE users set rank='0',approved='1' where username = '$_GET[app]'"; 
   $resultapp = mysql_query($userapp, $db_conn) or die("query [$userapp] failed: ".mysql_error()); 
   $addtoforum = "SELECT username, passwd, email FROM users where username = '$_GET[app]'"; 
   $add1 = mysql_query($addtoforum, $db_conn) or die("query [$add1] failed: ".mysql_error()); 
      $row = mysql_fetch_assoc($add1); 
      $fuser = $row['username']; 
      $fpass = $row['passwd']; 
      $fmail = $row['email']; 
	  $idnumber = "SELECT id FROM ibf_members";
	  $idquery = mysql_query($idnumber, $db_conn) or die("query [$idnumber] failed: ".mysql_error());
	  $fid = mysql_num_rows($idquery);
	  $ftime = mktime();
      $adduser = "INSERT INTO ibf_members (id,name,mgroup,password,email,joined) VALUES ('$fid','$fuser','3',md5('$fpass'),'$fmail','$ftime')"; 
      $done = mysql_query($adduser, $db_conn) or die("query [$adduser] failed: ".mysql_error()); 
	   }
		if (isset($done) && isset($resultapp))
		{
	header("Location: index.php?page=trialists");
		}
?>