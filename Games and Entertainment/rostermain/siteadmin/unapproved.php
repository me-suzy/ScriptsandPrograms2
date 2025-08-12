<?
echo '<p><a href="index.php?page=members" />Approved members</a> | <a href="index.php?page=trialists" />Trialists</a> | <b>Unapproved members</b></p>';
$umembers = "SELECT users.approved,users.username,characters.username,characters.charactername,characters.main,users.email,users.rec FROM users,characters WHERE users.username=characters.username ORDER BY users.username";
$uresult = mysql_query($umembers, $db_conn) or die("query [$uresult] failed: ".mysql_error()); 
echo '<table class="log" cellspacing="5" cellpadding="5"><tr><td><div class="log"><u>Username</u></div></td><td><div class="log"><u>Main character</u></div></td><td><u>Email</u></td><td><u>How they found us</u></td><td></td><td></td><td></td>'; 

while ($row = mysql_fetch_assoc($uresult)) 
{ 
	if (($row['main'] ==1) && ($row['approved'] ==0))
	{
   echo '<tr>';
   echo '<td><div class="log">'.$row['username'].'</div></td>'; 
   echo '<td><div class="log">'.$row['charactername'].'</div></td>';
    echo '<td><div class="log">'.$row['email'].'</div></td>';
	 echo '<td><div class="log">'.$row['rec'].'</div></td>';
   echo '<td>';
      //APPROVE USER
   	echo '<a href="index.php?page=umem&&app='.$row['username'].'">Promote to member</a></div></td>';
	//start trial
	echo '<td><a href="index.php?page=umem&&starttrial='.$row['username'].'">Start Trial</a></div></td>';
		echo '<td><a href="index.php?page=delu&&del='.$row['username'].'" />Remove user from database</a></td>';

}
}
echo '</tr></table>';
if (isset($_GET['app']))
		{
	$userapp = "UPDATE users set rank='0',approved='1' where username = '$_GET[app]'"; 
   $resultapp = mysql_query($userapp, $db_conn) or die("query [$userapp] failed: ".mysql_error()); 
   	   }
		if (isset($done))
		{
	header("Location: index.php?page=umem");
		}
if (isset($_GET['starttrial']))
{
   echo '<form method="post" action="index.php?page=umem">';
   echo '<input type="hidden" value="'.$_GET['starttrial'].'" name="startuser">';
   echo '<p class="log">Start date:<br /><br />';
	echo '<b>Day:</b>  <select name="daystart" style="font-size:10px;border:solid 1px";>';
   echo'<option value="01">01</option>';
   echo'<option value="02">02</option>';
   echo'<option value="03">03</option>';
   echo'<option value="04">04</option>';
   echo'<option value="05">05</option>';
   echo'<option value="06">06</option>';
   echo'<option value="07">07</option>';
   echo'<option value="08">08</option>';
   echo'<option value="09">09</option>';
   echo'<option value="10">10</option>';
   echo'<option value="11">11</option>';
   echo'<option value="12">12</option>';
echo'<option value="13">13</option>';
echo'<option value="14">14</option>';
echo'<option value="15">15</option>';
echo'<option value="16">16</option>';
echo'<option value="17">17</option>';
echo'<option value="18">18</option>';
echo'<option value="19">19</option>';
echo'<option value="20">20</option>';
echo'<option value="21">21</option>';
echo'<option value="22">22</option>';
echo'<option value="23">23</option>';
echo'<option value="24">24</option>';
echo'<option value="25">25</option>';
echo'<option value="26">26</option>';
echo'<option value="27">27</option>';
echo'<option value="28">28</option>';
echo'<option value="29">29</option>';
echo'<option value="30">30</option>';
echo'<option value="31">31</option>';
echo '</select>';
      echo '<b>Month:</b>  <select name="monthstart" style="font-size:10px;border:solid 1px";>';
   echo'<option value="01">01</option>';
   echo'<option value="02">02</option>';
   echo'<option value="03">03</option>';
   echo'<option value="04">04</option>';
   echo'<option value="05">05</option>';
   echo'<option value="06">06</option>';
   echo'<option value="07">07</option>';
   echo'<option value="08">08</option>';
   echo'<option value="09">09</option>';
   echo'<option value="10">10</option>';
   echo'<option value="11">11</option>';
   echo'<option value="12">12</option>';
   echo'</select>';
   echo '<b>Year</b>  <select name="yearstart" style="font-size:10px;border:solid 1px";>';
   echo'<option value="2003">2003</option>';
   echo'<option value="2004">2004</option>';
   echo'</select>';
   echo '<br /><br />';
   echo 'End date:<br /><br />';
   	echo '<b>Day:</b>  <select name="dayend" style="font-size:10px;border:solid 1px";>';
   echo'<option value="01">01</option>';
   echo'<option value="02">02</option>';
   echo'<option value="03">03</option>';
   echo'<option value="04">04</option>';
   echo'<option value="05">05</option>';
   echo'<option value="06">06</option>';
   echo'<option value="07">07</option>';
   echo'<option value="08">08</option>';
   echo'<option value="09">09</option>';
   echo'<option value="10">10</option>';
   echo'<option value="11">11</option>';
   echo'<option value="12">12</option>';
echo'<option value="13">13</option>';
echo'<option value="14">14</option>';
echo'<option value="15">15</option>';
echo'<option value="16">16</option>';
echo'<option value="17">17</option>';
echo'<option value="18">18</option>';
echo'<option value="19">19</option>';
echo'<option value="20">20</option>';
echo'<option value="21">21</option>';
echo'<option value="22">22</option>';
echo'<option value="23">23</option>';
echo'<option value="24">24</option>';
echo'<option value="25">25</option>';
echo'<option value="26">26</option>';
echo'<option value="27">27</option>';
echo'<option value="28">28</option>';
echo'<option value="29">29</option>';
echo'<option value="30">30</option>';
echo'<option value="31">31</option>';
echo'</select>';
      echo '<b>Month:</b>  <select name="monthend" style="font-size:10px;border:solid 1px";>';
   echo'<option value="01">01</option>';
   echo'<option value="02">02</option>';
   echo'<option value="03">03</option>';
   echo'<option value="04">04</option>';
   echo'<option value="05">05</option>';
   echo'<option value="06">06</option>';
   echo'<option value="07">07</option>';
   echo'<option value="08">08</option>';
   echo'<option value="09">09</option>';
   echo'<option value="10">10</option>';
   echo'<option value="11">11</option>';
   echo'<option value="12">12</option>';
   echo'</select>';
   echo '<b>Year</b>  <select name="yearend" style="font-size:10px;border:solid 1px";>';
   echo'<option value="2003">2003</option>';
   echo'<option value="2004">2004</option>';
   echo'</select>';
   echo '<br /><br /><input type="submit" name="trialbegin" value="start trial" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;">';
   echo'</form>';
   echo '</p>';
}
if (isset($_POST['trialbegin']))
{
	$start = ''.$_POST['yearstart'].'-'.$_POST['monthstart'].'-'.$_POST['daystart'].'';
	$end = ''.$_POST['yearend'].'-'.$_POST['monthend'].'-'.$_POST['dayend'].'';
	$trialbegin = "UPDATE users SET approved='1',rank='3',trialstart='$start',trialend='$end' WHERE username='$_POST[startuser]'";
	$begin = mysql_query($trialbegin, $db_conn) or die("Query [begin] Failed: ".mysql_error());
	if (mysql_affected_rows($begin)==1)
	{
		echo '<p class="log">Trial started</p>';
	}
}
?>