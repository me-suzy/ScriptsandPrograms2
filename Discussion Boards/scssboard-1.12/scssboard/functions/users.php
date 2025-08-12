<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php

	echo "<table width='100%' border='0' cellpadding='4' cellspacing='1' align='center'>
            <tr>
                <td class='catheader' height='30' width='100%' colspan='6'>
                    &nbsp; <b>Forum Members</b>";
					if ($_GET[level]) { echo " (Level $_GET[level])"; }
					elseif ($_GET[level_h]) { echo " (Level $_GET[level_h] and above)"; }
                echo "</td>
            </tr>";

	echo "<tr><td height='20' width='10' align='center' class='forum_stat_hd'>ID</td>
		<td height='20' width='10' align='center' class='forum_stat_hd'>Posts</td>
		<td height='20' width='15%' align='center' class='forum_stat_hd'>Level</td>
		<td height='20' width='35%' align='center' class='forum_stat_hd'>Username</td>
		<td height='20' width='25%' align='center' class='forum_stat_hd'>Real Name</td>
		<td height='20' width='25%' align='center' class='forum_stat_hd'>Location</td>";

	if ((!$_GET[level_h]) and (!$_GET[level])) {
		$users_query = @mysql_query("select * from $_CON[prefix]users");
	} elseif ($_GET[level_h]) {
		$users_query = @mysql_query("select * from $_CON[prefix]users where users_level >= '$_GET[level_h]'");
	} elseif ($_GET[level]) {
		$users_query = @mysql_query("select * from $_CON[prefix]users where users_level = '$_GET[level]'");
	}

		while($users_show = @mysql_fetch_array($users_query)) {

		if ($users_show[users_level] == 1)
		$user_lvl_word = "Member";
		if ($users_show[users_level] == 2)
		$user_lvl_word = "Moderator";
		if ($users_show[users_level] == 3)
		$user_lvl_word = "Administrator";
		if ($users_show[users_level] == 4)
		$user_lvl_word = "Board Owner";
		
		$numposts = @mysql_num_rows(@mysql_query("select * from $_CON[prefix]posts where posts_starter = '$users_show[users_id]'"));
		echo "<tr><td class='forum_name' align='center'>$users_show[users_id]</td>";
		echo "<td class='forum_name' align='center'>$numposts</td>";
		echo "<td class='forum_name' align='center'>$user_lvl_word</td>";
		echo "<td class='forum_name' align='center'><a href='?act=profile&amp;u=$users_show[users_id]'>$users_show[users_username]</a></td>";
		echo "<td class='forum_name' align='center'>$users_show[users_realname]</td>";
		echo "<td class='forum_name' align='center'>$users_show[users_location]</td>";
		echo "</tr>";
		}
	echo "<tr><td colspan='6' class='forum_name'><strong>Show:</strong><br>
	<br><a href='?act=users&amp;level=1'>[1] = Members</a>
	<br><a href='?act=users&amp;level=2'>[2] = Moderators</a>
	<br><a href='?act=users&amp;level=3'>[3] = Administrators</a>
	<br><a href='?act=users&amp;level=4'>[4] = Board Owner</a>
	</td></tr>";
	echo "</table>";

?>