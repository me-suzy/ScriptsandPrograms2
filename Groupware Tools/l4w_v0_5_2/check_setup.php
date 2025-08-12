<?php

   	/*=====================================================================
	// $Id: check_setup.php,v 1.1 2004/10/20 12:21:11 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

        // --- Inclusion ---------------------------------------------------
        require ("config/config.inc.php");

        // --- Defaults ----------------------------------------------------
        $problems = false;

        // Checking DB Settings:
        $db_hdl = @mysql_connect($db_host, $db_user, $db_passwd);
        $db_host_check = "<font color='red'>cannot connect to database!</font>";
		$db_host_check.= "</td><td>Please check the settings...";
        if ($db_hdl)
           $db_host_check = "<font color='green'>ok...</font>";
        else
            $problems = true;


        $db_name_check = "<font color='red'>cannot find database $db_name!</font>";
		$db_name_check.= "</td><td>Please create the database before installation...";
        if (@mysql_select_db($db_name, $db_hdl))
             $db_name_check = "<font color='green'>ok...</font>";
        else
            $problems = true;

		// Table count check	
		$tables = mysql_list_tables($db_name);
		if (@mysql_num_rows($tables) > 58)
		    $tables_check = "<font color='green'>".@mysql_num_rows($tables).", ok...</font>";
        else {
			$tables_check  = "<font color='red'>only ".@mysql_num_rows($tables)." table(s) found</font>";
            $tables_check .= "</td><td>Please consult installation guide 'INSTALL'";
			$problems = true;        
		}

		// Translation count check	
		$res = mysql_query ("SELECT COUNT(*) FROM texte");
		$row = @mysql_fetch_array ($res);
		if ($row[0] > 574)
		    $translation_check = "<font color='green'>".$row[0].", ok...</font>";
        else {
			$translation_check   = "<font color='red'>only ".$row[0]." entries found</font>";
            $translation_check  .= "</td><td>Please consult installation guide 'INSTALL'";
            $problems = true;        
		}
		
		// count users check	
		$res = mysql_query ("SELECT COUNT(*) FROM users");
		$row = @mysql_fetch_array ($res);
		if ($row[0] > 0)
		    $count_users_check = "<font color='green'>".$row[0].", ok...</font>";
        else {
			$count_users_check   = "<font color='red'>only ".$row[0]." entries found</font>";
            $count_users_check .= "</td><td>Please consult installation guide 'INSTALL'";
            $problems = true;        
		}
        // Checking Session Path
        //$session_path = "files";
        /*$session_path_check = "<font color='red'><i>$session_path</i> is not writeable! Sessions won't work</font>";
        if (is_writeable ($session_path))
                $session_path_check = "<font color='green'>ok...</font>";
        else {
                if (!file_exists($session_path)) {
                        $session_path_check = "<font color='red'><i>$session_path</i> does not exist!</font>";
                        $problems = true;
                }
        } */

        // Checking Email Path
        //$system_path_to_email_folder = "files";
        $email_path_check = "<font color='red'><i>$system_path_to_email_folder</i> is not writeable! Email won't work</font>";
        if (is_writeable ($system_path_to_email_folder))
                $email_path_check = "<font color='green'>ok...</font>";
        else {
                if (!file_exists($system_path_to_email_folder)) {
                    $email_path_check = "<font color='red'><i>$system_path_to_email_folder</i> does not exist!</font>";
                    $problems = true;
                }
        }

        // Logging File
        $logging_file_check = "<font color='red'><i>".LOGGING_OUTPUT_FILE."</i> is not writeable! Logging won't work</font>";
        if (is_writeable (LOGGING_OUTPUT_FILE))
                $logging_file_check = "<font color='green'>ok...</font>";
        else {
                if (!file_exists(LOGGING_OUTPUT_FILE)) {
                        $logging_file_check = "<font color='red'><i>".LOGGING_OUTPUT_FILE."</i> does not exist!</font>";
                        $problems = true;
                }
        }

    // Checking XML Dir Path
        //$system_path_to_email_folder = "files";
        $xml_path_check = "<font color='red'>Directory <i>xml</i> is not writeable!</font>";
        if (is_writeable ("xml"))
                $xml_path_check = "<font color='green'>ok...</font>";
        else {
                if (!file_exists("xml")) {
                        $xml_path_check = "<font color='red'>Directory <i>xml</i> does not exist!</font>";
                            $problems = true;
                }
        }

?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Easy</title>
       <link rel='stylesheet' type='text/css' href='css/eclipse/main.css'>
       <style type="text/css">
                       td.green {
                               FONT-FAMILY: Verdana, Geneva, Arial, Helvetica, sans-serif;
                                font-size:12px;
                                color:green;
                       }

                       td.red {
                               FONT-FAMILY: Verdana, Geneva, Arial, Helvetica, sans-serif;
                                font-size:12px;
                                color:red;
                                font-weight:bold;
                       }
       </style>
</head>
<body>

<br>
<table width='95%' align='center'>
<tr>
        <td align='left' colspan=4>
                <font size=3><b>Leads4web/3 - Checking Setup</b></font>
        </td>
</tr>
<tr>
        <td align='left' colspan=4>
                <hr>
        </td>
</tr>
<tr>
        <td align='left' colspan=4>
                <font color=red>Once your installation is finished, you should <b>rename this file</b>
                (or delete it) to
                something like check_setup.php.ini, as others could execute it and get information
                about your paths.
                </font>
        </td>
</tr>
<tr>
        <td align='left' colspan=4>
                &nbsp;
        </td>
</tr>
<tr>
        <td align='left' colspan=4>
                <font size=2><b>Configuration (as in config.inc.php):</b></font>
        </td>
</tr>
<tr>
        <td align='left' colspan=4><hr></td>
</tr>
<tr>
        <td align='left' width='20%'><b>$db_host:</b></td>
        <td align='left' width='20%'><?=$db_host?></td>
        <td align='left'><?=$db_host_check?></td>
</tr>
<tr>
        <td align='left'><b>$db_user:</b></td>
        <td align='left'><?=$db_user?></td>
        <td align='left'><?=$db_host_check?></td>
</tr>
<tr>
        <td align='left'><b>$db_name:</b></td>
        <td align='left'><?=$db_name?></td>
        <td align='left'><?=$db_name_check?></td>
</tr>
<tr>
        <td align='left' colspan=4><hr></td>
</tr>
<tr>
        <td align='left'><b>Tables:</b></td>
        <td align='left'>Count</td>
        <td align='left'><?=$tables_check?></td>
</tr>
<!--<tr>
        <td align='left'><b>Translation table:</b></td>
        <td align='left'>Count</td>
        <td align='left'><?=$translation_check?></td>
</tr>-->
<tr>
        <td align='left'><b>Users table:</b></td>
        <td align='left'>Count</td>
        <td align='left'><?=$count_users_check?></td>
</tr>
<tr>
        <td align='left' colspan=4><hr></td>
</tr>
<tr>
        <td align='left'><b>$system_path_to_email_folder:</b></td>
        <td align='left'><?=$system_path_to_email_folder?></td>
        <td align='left'><?=$email_path_check?></td>
</tr>
<tr>
        <td align='left' colspan=4><hr></td>
</tr>
<tr>
        <td align='left'><b>LOGGING_OUTPUT_FILE:</b></td>
        <td align='left'><?=LOGGING_OUTPUT_FILE?></td>
        <td align='left'><?=$logging_file_check?></td>
</tr>
<tr>
        <td align='left' colspan=4><hr></td>
</tr>
<!-- <tr>
        <td align='left'><b>Directory XML</b></td>
        <td align='left'>Directory <i>xml</i> should be writable</td>
        <td align='left'><?=$xml_path_check?></td>
</tr>  -->
<?php if (!$problems) { ?>
     <tr>
        <td colspan=4>Everything seems to be ok... continue <a href='main.php'><u>here</u></a></td>
    </tr>
<?php } ?>
</table>

</body>
</html>