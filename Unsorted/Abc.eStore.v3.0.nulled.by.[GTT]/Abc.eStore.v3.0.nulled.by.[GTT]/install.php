<?

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

$page_title = "ABC eStore installation script";

error_reporting(0);

//-----------------------------------------------------
// Restore from dump file
//-----------------------------------------------------

function RestoreDbFromFile( $db, $file, $prefix ) {
			
	set_time_limit (0); //Setting no time limit for execution
	
	$err=0;
	
	if ( is_file($file) ) {
	
		$f = fopen($file,'r');
		$content = fread ( $f, filesize($file) );
		
		$content = str_replace ( 'abcsh_', $prefix, $content );
		
		$sqlquery = array();
		splitSqlFile ( $sqlquery, $content, 0 );
		
		foreach ( $sqlquery as $sql ) {
		
			$res= mysql_query( $sql );
			
			if( !$res )
				$err=1;
		
		}
		
		if ( $err == 0 )
			return '';
		else 	return 'Some database errors occured!';
	
	} 
	else 	return 'Bad install sql file!';
	
}


/**
 * Removes comment lines and splits up large sql files into individual queries
 *
 *
 * @param   array    the splitted sql commands
 * @param   string   the sql commands
 * @param   integer  the MySQL release number (because certains php3 versions
 *                   can't get the value of a constant from within a function)
 *
 * @return  boolean  always true
 *
 * @access  public
 */
function splitSqlFile(&$ret, $sql, $release)
{
    $sql          = trim($sql);
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = FALSE;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = substr($sql, 0, $i);
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $string_start = $char;
        } // end else if (is start of string)

        // ... for start of a comment (and remove this comment if found)...
        else if ($char == '#'
                 || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
            // starting position of the comment depends on the comment type
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
            // if no "\n" exits in the remaining string, checks for "\r"
            // (Mac eol style)
            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                              ? strpos(' ' . $sql, "\012", $i+2)
                              : strpos(' ' . $sql, "\015", $i+2);
            if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array if required and exit
                if ($start_of_comment > 0) {
                    $ret[]    = trim(substr($sql, 0, $start_of_comment));
                }
                return TRUE;
            } else {
                $sql          = substr($sql, 0, $start_of_comment)
                              . ltrim(substr($sql, $end_of_comment));
                $sql_len      = strlen($sql);
                $i--;
            } // end if...else
        } // end else if (is comment)

        // ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
        else if ($release < 32270
                 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
            $sql[$i] = ' ';
        } // end else if

        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
            $time0 = $time1;
            header('X-pmaPing: Pong');
        } // end if
    } // end for

    // add any rest to the returned array
    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
        $ret[] = $sql;
    }

    return TRUE;
} // end of the 'PMA_splitSqlFile()' function


?>

<html>
<head>
<title><? echo $page_title; ?></title>

<style>
BODY {
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 12px;
font-weight: normal;
}

TABLE, TR, TD {
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size: 12px; 
color:#000000;
 }

a:link, a:visited, a:active {
text-decoration:underline; 
color:#003399;
}

a:hover {
text-decoration:none;
color:#003399;
}
</style>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">

<?php

extract( $_GET );
extract( $_POST );

$url = $_SERVER['HTTP_HOST'];
$dir_name = dirname($_SERVER['PHP_SELF']);
$current_url = "http://$url$dir_name";
$current_dir = getcwd();

$step = empty( $step ) ? 1 : $step;


echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#CCCCCC\">";
echo "<tr>";
echo "<td width=\"100%\" align=\"center\">";
echo "<b><font size=\"4\">$page_title</font></b>";
echo "</td>";
echo "</tr>";
echo "</table>";

if($step == 1 )
{
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#FFFFFF\">";
	echo "<tr>";
	echo "<td>";	
	echo "<b>STEP 1: File permissions confirmation</b><br><br>";

	$images_perms = fileperms("$current_dir/images");
	$xl_perms = fileperms("$current_dir/admin/xlupload");
	$config_perms = fileperms("$current_dir/admin/config.php");
	$shipment_perms = fileperms("$current_dir/shipment");
	

	if( $images_perms == 16895 )
		echo "$current_url/images/ successfully set to <font color=green>chmod 777</font><br>";
	else
	{
		echo "<font color=\"#CC0000\">Please chmod folder $current_url/images/ to \"777\" manually and then refresh this window</font><br>";
		$stop = 1;
	}
	
	if( $xl_perms == 16895 )
		echo "$current_url/admin/xlupload/ successfully set to <font color=green>chmod 777</font><br>";
	else
	{
		echo "<font color=\"#CC0000\">Please chmod folder $current_url/admin/xlupload/ to \"777\" manually and then refresh this window</font><br>";
		$stop = 1;	
	}
	
	if( $config_perms == 33279 )
		echo "$current_url/admin/config.php successfully set to <font color=green>chmod 777</font><br>";
	else
	{
		echo "<font color=\"#CC0000\">Please chmod file $current_url/admin/config.php to \"777\" manually and then refresh this window</font><br>";
		$stop = 1;
	}
	
	if( $shipment_perms == 16895 )
		echo "$current_url/shipment/ successfully set to <font color=green>chmod 777</font><br>";
	else
	{
		echo "<font color=\"#CC0000\">Please chmod folder $current_url/shipment/ to \"777\" manually and then refresh this window</font><br>";
		$stop = 1;
	}
	
	
	if( $stop == 1 )
	{
		echo"<br><br>These files/folders should be set up to the correct chmod value before installation can continue!<br>
		Files and folders can be set up to the correct chmod value using most FTP programs.<br>
		After that refresh this window!!!
		<p>&nbsp;</p>";

		echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#F5F5F5\">";
		echo "<tr>";
		echo "<td>";
		echo "<div align=\"center\"><a href=\"install.php?step=2\"><font size=\"2\"><b>Ignore and Continue >></b></font></a> (Not recommended)</div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		exit;
	}

	if( $stop !== 1 )
		$step = 2;
	
	echo"<br><br><b><font color=\"#000099\">STEP 1 finished SUCCESSFUL!</font></b><br><br>";

	echo "</td>";
	echo "</tr>";
	echo "</table>";
	
}// end step 1





if( $step == 2 )
{

echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#FFFFFF\">";
echo "<tr>";
echo "<td>";

// create config.php
	if( !$submit )
	{
		// START output step 2 form
?>
	<BR><b>STEP 2: BUILD DATABASE AND CONFIG.PHP</b><br><BR>
	<b><FONT COLOR="RED">WARNING!</FONT> 
	IF YOU HAVE INSTALLED ABC eStore AND PRESS [CONTINUE] ALL PREVIOUS INSTALLATIONS WILL BE LOST!
	</b><BR>
	<p>
	Please enter your hostname, database name, username, password and name.<br>

	<form method="post" action="install.php?step=2" target="_self">
	<table width="50%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" border="0">
	<tr>
	<td colspan="2" bgcolor="#DDDDDD"><b>Database Connection:</b></td>
	</tr>
	<tr>
	<td >Hostname:<br>(Often 'localhost')</td>
	<td><input type="text" class="textbox" name="dbhost" size="20"></td>
	</tr>
	<tr>
	<td>Database Name:</td>
	<td><input type="text" name="dbname" class="textbox" size="20"></td>
	</tr>
	<tr>
	<td>Database Username:</td>
	<td><input type="text" class="textbox" name="dbuser" size="20"></td>
	</tr>
	<tr>
	<td>Database Password:</td>
	<td><input type="password" class="textbox" name="dbpasswd" size="20"></td>
	</tr>
	<tr>
	<td>Table Prefix:<Br>(Can be left empty)</td>
	<td><input type="text" name="prefix" class="textbox" size="20"></td>
	</tr>
	<tr>
	<td colspan="2" bgcolor="#DDDDDD"><b>Admin Login:</b></td>
	</tr>
	<tr>
	<td>Username:</td>
	<td><input type="text" class="textbox" name="adm_user" size="20"></td>
	</tr>
	<tr>
	<td valign="top">Password:</td>
	<td><input type="password" name="adm_pass" class="textbox" size="20"><br>(PASSWORD STRONGLY RECOMMENDED TO BE 8 CHARACTERS OR OVER AND CONTAIN AT LEAST 1 NUMBER!)</td>
	</tr>
	<tr>
	<td>Confirm Password:</td>
	<td><input type="password" name="adm_pass_cont" class="textbox" size="20"></td></tr>
	<tr>
	<td colspan="2" bgcolor="#DDDDDD"><b>Demo Login:</b></td>
	</tr>
	<tr>
	<td>Allow demo:</td>
	<td><input type="checkbox" class="textbox" name="allowdemo" value="1" size="20">&nbsp;(IF NO DEMO LEAVE FIELDS BELLOW BLANK)</td>
	</tr>
	<tr>
	<td>Demo login:</td>
	<td><input type="text" class="textbox" name="demologin" size="20"></td>
	</tr>
	<tr>
	<td>Demo password:</td>
	<td><input type="password" name="demopass" class="textbox" size="20"></td>
	</tr>
	<tr>
	<td>Confirm demo password:</td>
	<td><input type="password" name="demopass_cont" class="textbox" size="20"></td>
	</tr>
	</table>
	<br><br>
	<table width="100%" border="0" cellpadding="5" cellspacing="5" bgcolor="#F5F5F5">
	<tr>
	<td align="center"><input type="submit" value="Continue" class="submit" name="submit"></td>
	</tr>
	</table>
	</form>
<?
		// END output step 2 form
	}
	
	if( $submit )
	{
		echo"<b>STEP 2: BUILD CONFIG.PHP AND DATABASE</b><br>";
    
		// make sure passwords match and and encrypt result
		if( $adm_pass != $adm_pass_cont )
		{
			echo "<p><div align='center'><font color=red><b>The passwords entered do not match!</b><br><br><a href='install.php' TARGET='_SELF'>Please try again!</font></p>";
			exit;
		}
		
		// make sure demo passwords match
		if (isset ($allowdemo)) {
			if( $demopass != $demopass_cont )
			{
				echo "<p><div align='center'><font color=red><b>The demo passwords entered do not match!</b><br><br><a href='install.php' TARGET='_SELF'>Please try again!</font></p>";
				exit;
			}
		}
		else $allowdemo=0;
		
		$adm_pass_enc = md5($adm_pass);

		// write config.php and drop table	
	
	    $write = fopen( "$current_dir/admin/config.php", "w+" );
	    
		$config = "<?php
		\$dbhost = \"$dbhost\";
		\$dbname = \"$dbname\";
		\$dbuser = \"$dbuser\";
		\$dbpasswd = \"$dbpasswd\";
		\$prefix = \"$prefix\";
		\$allowdemo = \"$allowdemo\";
		\$demologin = \"$demologin\";
		\$demopass = \"$demopass\";		
		if(!\$db = @mysql_connect(\"\$dbhost\", \"\$dbuser\", \"\$dbpasswd\"))
			die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check \$dbhost, \$dbuser, and \$dbpasswd in config.php.');
		if(!@mysql_select_db(\"\$dbname\",\$db))
			die(\"<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>\$dbname</b> on your MySQL server.\");
?>";

		$length = strlen($config);
		fwrite($write, $config, $length);
		fclose($write);
	
		// connect to database
		include("$current_dir/admin/config.php");
	
		
		// Create tables and insert data
		
		$err = RestoreDbFromFile( $dbname , "install.sql", $prefix );
					
		// Insert config information
				
		$current_dir = addslashes($current_dir);
		
		if ( !mysql_query ("UPDATE ".$prefix."store_config SET
		`site_url` = '$current_url',
		`site_dir` = '$current_dir',
		`pass` = '$adm_pass_enc',
		`user` = '$adm_user'
		") ) {
			$err .= " Database error!";
			
		}
		
		if ( $err ) {
			echo "<br><font color='red'>".$err."</font>";
			echo "<br><br><a href='install.php?step=2'> << Back</a>";
			$step = 2;
		}
		else	$step = 3;
		
		//		
		
	}// end if submit


echo "</td>";
echo "</tr>";
echo "</table>";

}// end step 2



if( $step == 3 )
{

echo "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#FFFFFF\">";
echo "<tr>";
echo "<td>";

	echo"<font color=\"#000099\"><b>IF YOU ARE READING THIS STEP 2 WAS SUCCESSFUL!</b></FONT><br><br>
		<BR><b>STEP 3: CHMOD REQUIRED</b><br><BR>";
		
	if( $warn !== "no" )
		echo"<b><FONT COLOR=\"#CC0000\">Important:</FONT> config.php must be chmod back to 644!</b><BR>";

	$config_perms_new = fileperms("$current_dir/admin/config.php");

	if( $config_perms_new == 33188 )
		echo "$current_url/admin/config.php successfully set to <font color=green>chmod 644</font><br>";
	else
	{
		echo "<br><font color='red'>Please chmod file $current_url/admin/config.php to \"644\" manually!</font> 
		<p><a href='install.php?step=3&warn=no'>Then click here</a> (Do not use Browser for refresh this window)";

		echo "<p><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#F5F5F5\">";
		echo "<tr>";
		echo "<td>";
		echo "<div align=\"center\"><font size=\"2\"><a href=\"install.php?step=3&cont=yes\"><b>Ignore and continue >></b></a></font>  (Not recommended)</div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";

		$stop=2;
	}
	
	if( ( $stop !== 2 ) || ( $cont == "yes" ) )
		echo"<br><font color=\"#000099\"><b>No error messages? Installation is complete!</b><br></font>
		<BR><font color=\"#CC0000\">Remember! For good safety install.php and install.sql must be deleted!</font>";

		echo "<p><table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"5\" bgcolor=\"#F5F5F5\">";
		echo "<tr>";
		echo "<td>";
		echo"<div align=\"center\"><font size=\"2\"><A HREF=\"admin/index.php\"><b>Login to admin now and check settings >></b></a></font></div>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";


echo "</td>";
echo "</tr>";
echo "</table>";
		
}// end step 3

?>
</body>
</html>
