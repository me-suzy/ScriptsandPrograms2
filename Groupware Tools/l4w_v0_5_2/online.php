<?php
	
	die ("in version 3.0 nicht genutzt");
	// Version 2.0 beta, 8.11.2001, Carsten Gräf, evandor media GmbH München  
	// changes: 28.11.2001 Nur Die User, von denen user_id member ist, werden angezeigt
	include ("config/config.inc.php");					
	include	("connect_database.php");
    @session_name (SESSION_NAME);
	session_start(); 
	include ("inc/functions.inc.php");
	security_check();
	// pagestats
	set_page_stats(__FILE__);
	include ("header.inc"); 			
	$headline = get_from_texte ("Eingeloggt", $language);		
	include ("links_leiste.php");
   
?>
	
	<br><br><br><br>
	
		<table border=0 width='60%' align='center'>
		
		<?php
	
			mysql_select_db($db_name, $db);
	        $timestamp=time();                                                                                            
			$timeout=time()-300; // 5 Minuten 
			$result=mysql_query("DELETE FROM useronline WHERE timestamp<$timeout");     

        	$res = mysql_query ("SELECT * FROM useronline WHERE 1=1 ".get_all_groups_or_statement($user_id));
			     
			$online_array = array ();   	
        	//mysql_select_db($db_user_name, $db);
        	$first = true;
        	while ($row = mysql_fetch_array ($res)) {
        		$online_array[] = $row['user_id'];
        		if (!first)
					echo "<tr><td colspan=2><hr></td></tr>\n";
        		$this_res = mysql_query ("SELECT * FROM users WHERE id='".$row['user_id']."'", $db);
				$this_row = mysql_fetch_array ($this_res);
				$group_res= mysql_query ("SELECT name FROM groups WHERE id='".$this_row['grp']."'", $db);
				$group_row= mysql_fetch_array ($group_res);
				
				echo "<tr><td rowspan=5>&nbsp;<img src='img/is_online.gif'></td>\n";
				echo "<td><b>".$this_row['anrede']." ".$this_row['vorname']." ".$this_row['nachname']."</b>";
				echo "&nbsp;<a href='sendmailform.php?to=".$this_row['email']."' target='_new'><img src='img/ol_mail.gif' border=0></a>";
				echo "</td></tr>\n";
				echo "<tr><td>".ucwords ($this_row['kind_of'])."</td></tr>\n";
				echo "<tr><td>".$this_row['telefon_firma']."</td></tr>\n";
				echo "<tr><td>".get_from_texte ("Gruppe", $language).": ".$group_row['name']."</td></tr>\n";
				$count_res = mysql_query ("SELECT login_count FROM ".TABLE_PREFIX."user_details WHERE user_id='".$row['user_id']."'");
				$count = mysql_fetch_array ($count_res);
				echo "<tr><td>Eingeloggt: ".$count[0]." x</td></tr>\n";
        	}
        	
        	// Wer ist nicht online?
        	$where_clause = "SELECT * FROM users WHERE 1=1 ";
        	for ($i=0; $i<count ($online_array); $i++) 
        		$where_clause .= " AND id<>'".$online_array[$i]."'";
        	$where_clause .= get_all_groups_or_statement($user_id);
        	
        	$this_res = mysql_query ($where_clause, $db);
			        	
        	//mysql_select_db($db_user_name, $db);
        	$first = true;
        	while ($this_row = mysql_fetch_array ($this_res)) {
        		echo "<tr><td colspan=2><hr></td></tr>\n";
        		$group_res= mysql_query ("SELECT name FROM groups WHERE id='".$this_row['grp']."'", $db);
				$group_row= mysql_fetch_array ($group_res);
				
				echo "<tr><td rowspan=5>&nbsp;<img src='img/is_not_online.gif'></td>\n";
				echo "<td><b>".$this_row['anrede']." ".$this_row['vorname']." ".$this_row['nachname']."</b>";
				echo "&nbsp;<a href='sendmailform.php?to=".$this_row['email']."' target='_new'><img src='img/ol_mail.gif' border=0></a>";
				echo "</td></tr>\n";
				echo "<tr><td>".ucwords ($this_row['kind_of'])."</td></tr>\n";
				echo "<tr><td>".$this_row['telefon_firma']."</td></tr>\n";
				echo "<tr><td>".get_from_texte ("Gruppe",$language).": ".$group_row['name']."</td></tr>\n";
				$count_res = mysql_query ("SELECT login_count FROM ".TABLE_PREFIX."user_details WHERE user_id='".$this_row['id']."'");
				$count = mysql_fetch_array ($count_res);
				echo "<tr><td>".get_from_texte ("Eingeloggt", $language).": ".$count[0]." x</td></tr>\n";
        	}

			//mysql_select_db($db_name, $db);
	    
	    ?>
		
		</table>
	</form>
	
</body>
</html>
