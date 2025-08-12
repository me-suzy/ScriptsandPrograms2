<?
echo '<p><b>Approved Members</b> | <a href="index.php?page=trialists" />Trialists</a> | <a href="index.php?page=umem" />Unapproved members</a></p>';
//show members 

$memquery = "SELECT users.username,users.approved,users.rank,users.admin,characters.username,characters.charactername,characters.main FROM users,characters WHERE users.username=characters.username ORDER BY users.username"; 
$memresult = mysql_query($memquery, $db_conn) or die("query [$memquery] failed: ".mysql_error()); 
echo '<table class="log" cellspacing="5" cellpadding="5"><tr><td><div class="log"><u>Username</u></div></td><td><div class="log"><u>Main character name</u></div></td><td><div class="log"><u>Rank?</u></div></td><td><div class="log"><u>Admin?</u></div></td><td><div class="log"><u>Set new user rank</u></div></td><td></td></tr>'; 
while ($row = mysql_fetch_assoc($memresult)) 
{  
if (($row['approved'] ==1) && ($row['rank']!=3))
	{
	if ($row['rank'] == 1) 
   { 
      $rank = Officer; 
   } 
   if ($row['rank'] == 2) 
   { 
      $rank = Leader; 
   } 
   elseif ($row['rank'] == 0) 
   { 
      $rank = Member; 
   } 
   if ($row['rank'] == 3) 
   { 
      $rank = Trialist; 
   }
   if ($row['main'] == 1)
	{
   echo '<tr>';
   echo '<td><div class="log">'.$row['username'].'</div></td>'; 
   echo '<td><div class="log">'.$row['charactername'].'</div></td>';   
   echo '<td><div class="log">'.$rank.'</div></td>'; 
   

//SET ADMIN RIGHTS
   echo '<td><div class="log">';
   if ($row['admin'] == 0)
	{
	  echo '<a href="index.php?page=members&&makead='.$row['username'].'">No</a></div></td>';
	  if (isset($_GET['makead']))
		{
		$useradm = "UPDATE users set admin = '1' where username = '$_GET[makead]'"; 
		$resultadm = mysql_query($useradm, $db_conn) or die("query [$query] failed: ".mysql_error()); 
		if (isset($resultadm))
			{
		header("Location: index.php?page=members");
			}
		}
	}
	//REMOVE ADMIN RIGHTS
	if ($row['admin'] == 1)
		{
			echo '<a href="index.php?page=members&&remad='.$row['username'].'">Yes</a></div></td>';
		if (isset($_GET['remad']))
			{
			$usernoad = "UPDATE users set admin = '0' where username = '$_GET[remad]'"; 
			$resultnoad = mysql_query($usernoad, $db_conn) or die("query [$query] failed: ".mysql_error()); 
			if (isset($resultnoad))
				{
				header("Location: index.php?page=members");
				}
			}
		}
		//SETTING USER RANKS
		echo '<td>';
		echo '<a href="index.php?page=members&&mem='.$row['username'].'"><img src="buttons/m.jpg" border="0" /></a><a href="index.php?page=members&&off='.$row['username'].'"><img src="buttons/o.jpg" border="0" /></a><a href="index.php?page=members&&lea='.$row['username'].'"><img src="buttons/l.jpg" border="0" /></a></div>';
		if (isset($_GET['mem']))
		{
			$usermem = "UPDATE users set rank = '0' where username = '$_GET[mem]'"; 
			$resultmem = mysql_query($usermem, $db_conn) or die("query [$query] failed: ".mysql_error()); 
			if (isset($resultmem))
			{
			header("Location: index.php?page=members");
			}
		}
		if (isset($_GET['off']))
		{
			$useroff = "UPDATE users set rank = '1' where username = '$_GET[off]'"; 
			$resultoff = mysql_query($useroff, $db_conn) or die("query [$query] failed: ".mysql_error()); 
			if (isset($resultoff))
			{
			header("Location: index.php?page=members");
			}
		}
		if (isset($_GET['lea']))
		{
			$userlea = "UPDATE users set rank = '2' where username = '$_GET[lea]'"; 
			$resultlea = mysql_query($userlea, $db_conn) or die("query [$query] failed: ".mysql_error()); 
			if (isset($resultlea))
			{
			header("Location: index.php?page=members");
			}
		}
		echo '</td>';
		//DENY USER
		echo '<td><a href="index.php?page=members&&den='.$row['username'].'">Unapprove</a></div></td>';
		if (isset($_GET['den']))
		{
		$userden = "UPDATE users set approved = '0' where username = '$_GET[den]'"; 
		$resultden = mysql_query($userden, $db_conn) or die("query [$query] failed: ".mysql_error()); 
		if (isset($resultden))
				{
				header("Location: index.php?page=members");
				}
		}
		
	}
	}
}
echo '</tr></table>';
?>