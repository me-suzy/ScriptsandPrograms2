<?PHP

if($_userlevel != 3){

    die("<center><span class=red>You need to be an admin to access this page!</span></center>");

}



echo "



<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/onlineinfo_title.gif\" border=0 alt=\"\"><p>

<table width=100% class=category_table cellpadding=0 cellspacing=0 align=center>

		<tr>

			<td class=table_1_header>			

				<b>Now</b>			

			</td>

		</tr>

		<tr>

			<td>

			<table  width=100% border=0 cellpadding=0 cellspacing=0 align=center>

				<tr>

					<td class=table_subheader width=25%><b>Username</b></td>

					<td class=table_subheader width=25% align=center><b>IP Address</b></td>

					<td class=table_subheader width=25% align=center><b>Viewing</b></td>

					<td class=table_subheader width=25% align=left><b>Time</b></td>

				</tr>";



$table_row = '1';



// STARTING WITH USER'S ONLINE FIRST, THEN GUESTS

$user_online = mysql_query("SELECT * FROM online WHERE guest='0' AND isonline='1' ORDER BY timestamp DESC") or die(mysql_error());

while($urow = mysql_fetch_assoc($user_online)){

	$users_id = $urow['id'];

	$users_name = $urow['username'];

	$users_ip = $urow['ip'];

	$u_time = $urow['timestamp'];

	$u_viewtopic = $urow['viewtopic'];

	$u_viewforum = $urow['viewforum'];

	$u_posting = $urow['posting'];

	$u_file = $urow['page'];

	

	// FIND OUT WHERE USER IS

	if($u_posting == '0'){



		if($u_viewtopic !== '0' || $u_viewforum !== '0'){

			

			if($u_viewforum !== '0'){



				if($u_viewtopic !== '0'){



					$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$u_viewtopic'");

					$topic_title = mysql_fetch_row($topic_lookup);



					$place = "READING: <a href='../viewtopic.php?id=$u_viewtopic' target='_blank'>$topic_title[0]</a>";



				} else {



					$forum_lookup = mysql_query("SELECT name FROM forums WHERE id='$u_viewforum'");

					$forum_title = mysql_fetch_row($forum_lookup);



					$place = "Browsing: <a href='../viewforum.php?id=$u_viewforum' target='_blank'>$forum_title[0]</a>";



				}





			}



		} else {



			$place = "$u_file";



		}





	} else {



		if($u_viewtopic !== '0'){



			$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$u_viewtopic'");

			$topic_title = mysql_fetch_row($topic_lookup);



			$place = "POSTING IN: <a href='../viewtopic.php?id=$u_viewtopic' target='_blank'>$topic_title[0]</a>";



		} 





	}



	$curdate = date("F j, Y h:i:s A", $u_time);



	if($table_row == '1'){



		echo "<tr>

				<td class=row1><a href='../profile.php?id=$users_id' target='_blank'>$users_name</a></td>

				<td class=row3 align=center><a href='http://www.whois.sc/$users_ip' target='_blank'>$users_ip</a></td>

				<td class=row1 align=center>$place</td>

				<td class=row1>$curdate</td></tr>";



		$table_row = '2';



	} else if($table_row == '2'){



		echo "<tr>

				<td class=row2><a href='../profile.php?id=$users_id' target='_blank'>$users_name</a></td>

				<td class=row3 align=center><a href='http://www.whois.sc/$users_ip' target='_blank'>$users_ip</a></td>

				<td class=row2 align=center>$place</td>

				<td class=row2>$curdate</td></tr>";



		$table_row = 1;



	}

}





$guest_online = mysql_query("SELECT * FROM online WHERE guest='1' AND isonline='1' ORDER BY timestamp DESC") or die(mysql_error());

while($grow = mysql_fetch_assoc($guest_online)){

	$guests_id = $grow['id'];

	$guests_name = $grow['username'];

	$guests_ip = $grow['ip'];

	$g_time = $grow['timestamp'];

	$g_viewtopic = $grow['viewtopic'];

	$g_viewforum = $grow['viewforum'];

	$g_posting = $grow['posting'];

	$g_file = $grow['page'];

	

	// FIND OUT WHERE guest IS

	if($g_posting == '0'){



		if($g_viewtopic !== '0' || $g_viewforum !== '0'){

			

			if($g_viewforum !== '0'){



				if($g_viewtopic !== '0'){



					$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$g_viewtopic'");

					$topic_title = mysql_fetch_row($topic_lookup);



					$place = "READING: <a href='../viewtopic.php?id=$g_viewtopic'>$topic_title[0]</a>";



				} else {



					$forum_lookup = mysql_query("SELECT name FROM forums WHERE id='$g_viewforum'");

					$forum_title = mysql_fetch_row($forum_lookup);



					$place = "Browsing: <a href='../viewforum.php?id=$g_viewforum'>$forum_title[0]</a>";



				}





			}

		} else {



			$place = "$g_file";



		}





	} else {



		if($g_viewtopic !== '0'){



			$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$g_viewtopic'");

			$topic_title = mysql_fetch_row($topic_lookup);



			$place = "POSTING IN: <a href='../viewtopic.php?id=$g_viewtopic'>$topic_title[0]</a>";



		} 





	}



	$curdate = date("F j, Y h:i:s A", $g_time);



	if($table_row == '1'){



		echo "<tr>

				<td class=row1>GUEST</td>

				<td class=row3 align=center><a href='http://www.whois.sc/$guests_ip' target='_blank'>$guests_ip</a></td>

				<td class=row1 align=center>$place</td>

				<td class=row1>$curdate</td></tr>";



		$table_row = '2';



	} else if($table_row == '2'){



		echo "<tr>

				<td class=row2>GUEST</a></td>

				<td class=row3 align=center><a href='http://www.whois.sc/$guests_ip' target='_blank'>$guests_ip</a></td>

				<td class=row2 align=center>$place</td>

				<td class=row2>$curdate</td></tr>";



	}

}





echo "		</table>

			</td>

		</tr>

	</table><br><br>";





// ONLINE TODAY

echo "<table width=100% class=category_table cellpadding=0 cellspacing=0 align=center>

		<tr>

			<td class=table_1_header>			

				<b>Today</b>			

			</td>

		</tr>

		<tr>

			<td>

			<table  width=100% border=0 cellpadding=0 cellspacing=0 align=center>

				<tr>

					<td class=table_subheader width=25%><b>Username</b></td>

					<td class=table_subheader width=25% align=center><b>IP Address</b></td>

					<td class=table_subheader width=25% align=center><b>Viewing</b></td>

					<td class=table_subheader width=25% align=left><b>Time</b></td>

				</tr>";



$table_row = '1';



// STARTING WITH USER'S ONLINE FIRST, THEN GUESTS

$user_online = mysql_query("SELECT * FROM online WHERE guest='0' ORDER BY timestamp DESC") or die(mysql_error());

while($urow = mysql_fetch_assoc($user_online)){

	$users_id = $urow['id'];

	$users_name = $urow['username'];

	$users_ip = $urow['ip'];

	$u_time = $urow['timestamp'];

	$u_viewtopic = $urow['viewtopic'];

	$u_viewforum = $urow['viewforum'];

	$u_posting = $urow['posting'];

	$u_file = $urow['page'];

	

	// FIND OUT WHERE USER IS

	if($u_posting == '0'){



		if($u_viewtopic !== '0' || $u_viewforum !== '0'){

			

			if($u_viewforum !== '0'){



				if($u_viewtopic !== '0'){



					$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$u_viewtopic'");

					$topic_title = mysql_fetch_row($topic_lookup);



					$place = "READING: <a href='../viewtopic.php?id=$u_viewtopic'>$topic_title[0]</a>";



				} else {



					$forum_lookup = mysql_query("SELECT name FROM forums WHERE id='$u_viewforum'");

					$forum_title = mysql_fetch_row($forum_lookup);



					$place = "Browsing: <a href='../viewforum.php?id=$u_viewforum'>$forum_title[0]</a>";



				}





			}



		} else {



			$place = "$u_file";



		}





	} else {



		if($u_viewtopic !== '0'){



			$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$u_viewtopic'");

			$topic_title = mysql_fetch_row($topic_lookup);



			$place = "POSTING IN: <a href='../viewtopic.php?id=$u_viewtopic'>$topic_title[0]</a>";



		} 





	}



	$curdate = date("F j, Y h:i:s A", $u_time);



	if($table_row == '1'){



		echo "<tr>

				<td class=row1><a href='../profile.php?id=$users_id' target='_blank'>$users_name</a></td>

				<td class=row3 align=center><a href='http://www.whois.sc/$users_ip' target='_blank'>$users_ip</a></td>

				<td class=row1 align=center>$place</td>

				<td class=row1>$curdate</td></tr>";



		$table_row = '2';



	} else if($table_row == '2'){



		echo "<tr>

				<td class=row2><a href='../profile.php?id=$users_id' target='_blank'>$users_name</a></td>

				<td class=row3 align=center><a href='http://www.whois.sc/$users_ip' target='_blank'>$users_ip</a></td>

				<td class=row2 align=center>$place</td>

				<td class=row2>$curdate</td></tr>";



		$table_row = 1;



	}

}



$guest_online = mysql_query("SELECT * FROM online WHERE guest='1' ORDER BY timestamp DESC") or die(mysql_error());

while($grow = mysql_fetch_assoc($guest_online)){

	$guests_id = $grow['id'];

	$guests_name = $grow['username'];

	$guests_ip = $grow['ip'];

	$g_time = $grow['timestamp'];

	$g_viewtopic = $grow['viewtopic'];

	$g_viewforum = $grow['viewforum'];

	$g_posting = $grow['posting'];

	$g_file = $grow['page'];

	

	// FIND OUT WHERE guest IS

	if($g_posting == '0'){



		if($g_viewtopic !== '0' || $g_viewforum !== '0'){

			

			if($g_viewforum !== '0'){



				if($g_viewtopic !== '0'){



					$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$g_viewtopic'");

					$topic_title = mysql_fetch_row($topic_lookup);



					$place = "READING: <a href='../viewtopic.php?id=$g_viewtopic'>$topic_title[0]</a>";



				} else {



					$forum_lookup = mysql_query("SELECT name FROM forums WHERE id='$g_viewforum'");

					$forum_title = mysql_fetch_row($forum_lookup);



					$place = "Browsing: <a href='../viewforum.php?id=$g_viewforum'>$forum_title[0]</a>";



				}





			}

		} else {



			$place = "$g_file";



		}





	} else {



		if($g_viewtopic !== '0'){



			$topic_lookup = mysql_query("SELECT title FROM topics WHERE id='$g_viewtopic'");

			$topic_title = mysql_fetch_row($topic_lookup);



			$place = "POSTING IN: <a href='../viewtopic.php?id=$g_viewtopic'>$topic_title[0]</a>";



		} 





	}



	$curdate = date("F j, Y h:i:s A", $g_time);



	if($table_row == '1'){



		echo "<tr>

				<td class=row1>GUEST</td>

				<td class=row3 align=center><a href='http://www.whois.sc/$guests_ip' target='_blank'>$guests_ip</a></td>

				<td class=row1 align=center>$place</td>

				<td class=row1>$curdate</td></tr>";



		$table_row = '2';



	} else if($table_row == '2'){



		echo "<tr>

				<td class=row2>GUEST</a></td>

				<td class=row3 align=center><a href='http://www.whois.sc/$guests_ip' target='_blank'>$guests_ip</a></td>

				<td class=row2 align=center>$place</td>

				<td class=row2>$curdate</td></tr>";



	}

}

echo "		</table>

			</td>

		</tr>

	</table><br><br>";



?>



