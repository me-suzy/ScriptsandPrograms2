<?php

   	/*=====================================================================
	// $Id: assign.php,v 1.4 2005/08/01 14:55:12 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    include ("inc/pre_include_standard.inc.php");

	//show_all_vars();
	
    // --- GET / POST -----------------------------------------------
    $limit   = var_include ("limit",    "POST");
	$hits    = var_include ("hits",     "POST");
	$doit    = var_include ("doit",     "POST");
	$ma      = var_include ("ma",       "POST");
	$from_ma = var_include ("from_ma",  "POST");
	
	if ($from_ma != "" && !is_superadmin($user_id)) {
	    die ("Security alert in ".__FILE__.", Line ".__LINE__);
	}
    // --- Page Stat -------------------------------------------------
    set_page_stats($user_id, "assign");

	// =====================================================================
    // Header und Javascritps
    include ("header.inc");
	
	$headline  = "<img src='".$img_path."companies.gif' align=top>&nbsp;";
    $headline .= translate ("assign");
    //$headline .= "&nbsp;&nbsp;<a href='javascript:helpsystem(\"".HELP_SYSTEM_URL."help.php?use_language=".$language."&name=assign&mytype=page\")'>";

    include ("leiste.php");

	if ($doit == "true") {
		// --- Query aus Datenbank auslesen: ----------------------------
	    $query_res = mysql_query ("SELECT db_query_serialized FROM ".TABLE_PREFIX."user_details WHERE user_id='$user_id'");
    	logDBError (__FILE__, __LINE__, mysql_error());
    	$query_row = mysql_fetch_array ($query_res);
    	$query     = unserialize ($query_row[0]);
		// --- Limit clause entfernen: ----------------------------------
    	$dummy = explode (" LIMIT ", $query);
    	$query = $dummy[0];
		$comp_res = mysql_query($query);
		logDBError (__FILE__, __LINE__, mysql_error());
    	
		// --- get grp from new owner -----------------------------------
		$sel_query = "SELECT grp FROM users where id='$ma'";
        $sel_res = mysql_query ($sel_query);
		logMsg ($sel_query);
        $sel_row = mysql_fetch_array ($sel_res);
        logDBError (__FILE__, __LINE__, mysql_error());
        $use_grp = $sel_row[0];

	?>
	<table class=frame width='100%' align=center>
    <tr>  
	   <td>
	   <b><?=translate ("assignments made")?></b><br>
	<?php
		if ($from_ma == $ma) {
			echo translate ("assigned same user");
		    echo "</td></tr></table>\n";
			die ("</body></html>");
		}
	    if (mysql_num_rows($comp_res) == 0) {
	        echo translate ("no assignment found");
	    }
		while ($comp_row = mysql_fetch_array ($comp_res)) {
			// --- Should this company be assigned? -----------------
			$do_update = false;
			if (is_superadmin($user_id) && $comp_row['owner'] == $from_ma) {
			    $do_update = true;
			    $old_owner = $from_ma;
			}
			elseif (!is_superadmin($user_id)) {
				if ($comp_row['owner'] == $user_id) {
				    $do_update = true;
				 	$old_owner = $user_id;   
				}
			}
			
			// --- if yes, assign -----------------------------------
			if ($do_update) {

				// --- Update company -------------------------------
				$update_query = "UPDATE companies
                      SET owner='$ma',
                          grp='$use_grp'
                      WHERE company_id='".$comp_row['company_id']."'";

				mysql_query ($update_query);      
		        logDBError (__FILE__, __LINE__, mysql_error());
				logMsg ($update_query);
				$tmp = explode ("-",$comp_row['Firmenname']);
				array_pop($tmp);
				$tmp = implode("-", $tmp);
				//echo "<br><b>Firma <i>".implode("-", $tmp)."</i> wurde zugewiesen.</b><br>";
				echo translate ("assignment of company",$tmp);
				
				// --- update corresponding contacts ----------------
				$update_query = "
					UPDATE ".TABLE_PREFIX."contacts
        				SET owner='$ma',
                    		grp='$use_grp'
                		WHERE firma='".$comp_row['company_id']."'
						      AND owner=$old_owner";
	    		mysql_query ($update_query);
				
				logDBError (__FILE__, __LINE__, mysql_error());
				logMsg ($update_query);
				// !!! text
				echo mysql_affected_rows()." zugehörige(r) Kontakt(e) wurde(n) angepasst.<br>"; 

				// --- update corresponding talks ----------------
				$update_query = "
					UPDATE talks
                        SET owner='$ma',
                          grp='$use_grp'
                        WHERE company='".$comp_row['company_id']."'
						AND owner=$old_owner";
				
				mysql_query ($update_query);
    	    	logDBError (__FILE__, __LINE__, mysql_error());
				logMsg ($update_query);
				echo mysql_affected_rows()." Firmenbericht(e) wurde(n) angepasst.<br>";
			}
		}
		echo "</td></tr></table>\n";
		die ("</body></html>");
	}
	

	set_time_limit(0);
    ini_set ("memory_limit", "64M");

	$mygroups    = get_all_groups ($user_id);
	$valid_users = get_members_of_groups ($mygroups);
?>
<form action='assign.php' method='post'>
	<input type=hidden name=user_id value='<?php echo $user_id ?>'>
    <input type=hidden name=limit   value='<?=$limit?>'>
    <input type=hidden name=doit    value='true'>

   <table class=frame style="border-width:1px; border-style:solid; border-color:#FF0000;" width='100%' align=center>
     <tr>  
	   <td>
	   <?php if (is_superadmin($user_id)) {?>
		   		<b><?=translate ("clicking assign means1")?>
				<br><br>
				<select name='from_ma' style='width:300px'>
				<?php
					foreach ($valid_users AS $key => $valid_user) {
						if ($valid_user != $user_id) {
							echo "<option value='$valid_user' selected>";
							echo get_username_by_user_id($valid_user)." ";
							echo "(Gruppe ".show_group_alias(get_main_group($valid_user)).")</option>\n";
						}
					}
					echo "<option value='$user_id'>";
					echo get_username_by_user_id($user_id)." ";
					echo "(Gruppe ".show_group_alias(get_main_group($valid_user)).")</option>\n";
			
				?>
				</select>
				<br>				
				<br><?=translate ("clicking assign means2")?><br><br>
				<select name='ma' style='width:300px'>
				<?php
					foreach ($valid_users AS $key => $valid_user) {
						if ($valid_user != $user_id) {
							echo "<option value='$valid_user' selected>";
							echo get_username_by_user_id($valid_user)." ";
							echo "(Gruppe ".show_group_alias(get_main_group($valid_user)).")</option>\n";
						}
					}
				?>
				</select><br><br>
				<?=translate ("clicking assign means3")?>:
				</b>
				<ul>
				    <?=translate ("clicking assign means4")?>
				</ul>
				<?=translate ("clicking assign means5")?>
				<br><br>
				<?=translate ("clicking assign means6", $hits)?>
		   		<br><br>
	   
	   		 <?php          
	         } else { ?>
				<b><?=translate ("clicking assign means1b", $hits)?>
				<br><br>
				<select name='ma' style='width:300px'>
				<?php
					foreach ($valid_users AS $key => $valid_user) {
						if ($valid_user != $user_id) {
							echo "<option value='$valid_user' selected>";
							echo get_username_by_user_id($valid_user)." ";
							echo "(Gruppe ".show_group_alias(get_main_group($valid_user)).")</option>\n";
						}
					}
				?>
				</select><br><br>
				<?=translate ("clicking assign means2b")?>
				</b>
				<ul>
				    <?=translate ("clicking assign means4");?>
				</ul>
				<b><?=translate ("clicking assign means3b");?></b>
				<br><br>
				<?=translate ("clicking assign means5");?>
				<br><br>
				<?=translate ("clicking assign means6", $hits)?>
		   		<br><br>
				 
			 <?php
			 }
			 ?>
	   </td>
	 </tr>
	 <tr>
	 	<td>
		    <input type=submit class='buttonstyle' value='<?=translate ("assign")?>...'>
		</td>
	 </tr>
   </table>
</form>