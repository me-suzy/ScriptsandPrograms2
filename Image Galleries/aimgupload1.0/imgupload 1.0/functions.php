<?php
class functions
{
	function footer($user_rank)
	{
		define('usernav', '<a href="index.php?action=upload">Upload Image</a> | <a href="index.php?action=imgdir">Image Directory</a> | <a href="index.php?user=profile">Edit Profile</a> | <a href="index.php?user=logout">Logout</a>');
		define('adminnav', '<a href="index.php?admin=newuser">New User</a> | <a href="index.php?admin=userlist">User list</a> | <a href="index.php?admin=settings">Modify Settings</a> | <a href="index.php?admin=check_updates">Check for Updates</a>');
		define('guestnav', '<a href="index.php?user=register">Register</a> | <a href="index.php?user=login">Log In</a>');

		echo '<p><br>
			</p>
		</center>
		<p> </p>
		</td>
		</tr>
		<tr>
		<td> <img src="images/index_08.gif" alt="" height="4" width="535"></td>
		</tr>
		<tr>
      <td align="left" background="images/index_09.gif" nowrap="nowrap"
	valign="top"> <span class="style2"><center>'; 
	if($user_rank == "admin") 
	{ 
		echo usernav; 
		echo "</p><b>Admin Panel</b><br />"; 
		echo adminnav;
	} elseif ($user_rank == "normal") 
	{ 	
		echo "<b>User Panel</b><br />"; 
		echo usernav; 
	} elseif ($user_rank == "guest")  
	{ 
		echo "Welcome, guest.<br />";
		echo guestnav;
	} 
		echo '</center><font face="Arial"></font></span></td>
		</tr>
		<tr>
      <td colspan="3" align="center" background="images/index_10.gif"
	height="54" valign="middle"><span class="style2"> Copyright IMG
	Upload, by <a href="http://hellscythe.net">Hellscythe</a> </span></td>
	</tr>
	</tbody>
	</table>
	</body>
	</html>';
	}
	
	function exitp($user_rank)
	{
		$footer = new functions();
		$footer->footer($user_rank);
		exit();
	}
	
	function settings()
	{
		$query = mysql_query("SELECT * FROM imgup_config");
		$array = mysql_fetch_array($query);
		return $array;
	}
	
	function size_check($filesize)
	{
		if(($filesize >= 1024) && ($filesize < 1048576))
		{
			$i = 0;
			$reduce = $filesize;
			while($reduce > $i)
			{
				if($reduce < 1024)
				{
					break;
				}
				$reduce = $reduce - 1024;
				$kb++;
			}
			$remain = substr($reduce, 0, 2);
			echo $kb . "." . $remain . " KB";
			unset($kb);
			unset($count);
		} elseif ($filesize >= 1048576)
		{
			$i = 0;
			$reduce = $filesize;
			while($reduce > $i)
			{
				if($reduce < 1048576)
				{
					break;
				}
				$reduce = $reduce - 1048576;
				$mb++;
			}
			$remain = substr($reduce, 0, 2);
			echo $mb . "." . $remain . " MB";;
			unset($mb);
			unset($count);
		} elseif ($filesize <= 1024)
		{
			if($filesize == Null)
			{
				$filesize = 0;
			}
			
			echo "" . $filesize . " Bytes";
		}
	}
	
	function mb_bytes($in_mb)
	{
		$i = 0;
		$bytes = 0;
		while($i <= $in_mb)
		{
			$bytes = $bytes + 1048576;
			$i++;
		}
		return $bytes;
	}
	
	function kb_bytes($in_kb)
	{
		$i = 0;
		$bytes = 0;
		while($i <= $in_kb)
		{
			$bytes = $bytes + 1024;
			$i++;
		}
		return $bytes;
	}
	
	function footer_install()
	{
		echo '<p><br>
			</p>
		</center>
		<p> </p>
		</td>
		</tr>
		<tr>
		<td> <img src="images/index_08.gif" alt="" height="4" width="535"></td>
		</tr>
		<tr>
      <td align="left" background="images/index_09.gif" nowrap="nowrap"
	valign="top"> <span class="style2"><center>
	Panel disabled for installation.
	</center><font face="Arial"></font></span></td>
		</tr>
		<tr>
      <td colspan="3" align="center" background="images/index_10.gif"
	height="54" valign="middle"><span class="style2"> Copyright IMG
	Upload, by <a href="http://hellscythe.net">Hellscythe</a> </span></td>
	</tr>
	</tbody>
	</table>
	</body>
	</html>';
	}
	
	function exitp_install()
	{
		echo '<p><br>
			</p>
		</center>
		<p> </p>
		</td>
		</tr>
		<tr>
		<td> <img src="images/index_08.gif" alt="" height="4" width="535"></td>
		</tr>
		<tr>
      <td align="left" background="images/index_09.gif" nowrap="nowrap"
	valign="top"> <span class="style2"><center>
	Panel disabled for installation.
	</center><font face="Arial"></font></span></td>
		</tr>
		<tr>
      <td colspan="3" align="center" background="images/index_10.gif"
	height="54" valign="middle"><span class="style2"> Copyright IMG
	Upload, by <a href="http://hellscythe.net">Hellscythe</a> </span></td>
	</tr>
	</tbody>
	</table>
	</body>
	</html>';
		exit();
	}
}
?>