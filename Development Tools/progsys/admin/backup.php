<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
@set_time_limit(600);
header('Pragma: no-cache');
header('Expires: 0');
require('../config.php');
if(!isset($lang) || !$lang)
	$lang=$admin_lang;
include('./language/lang_'.$lang.'.php');
require_once('../functions.php');
require_once('./functions.php');
require_once('./auth.php');
$backupprefix=$tableprefix."_";
$crlf="\n";
$user_loggedin=0;
$userdata=Array();
if($enable_htaccess)
{
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database ".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
	    die("<tr class=\"errorrow\"><td>User not defined for Progsys");
	$userid=$myrow["usernr"];
	$user_loggedin=1;
	$userdata = get_userdata_by_id($userid, $db);
}
else if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_userip(), $db);
		if ($userid) {
		   $user_loggedin = 1;
		   update_session($url_sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($url_sessid, $sesscookietime, get_userip(), $db);
		}
	}
}
else
{
	if(isset($_COOKIE[$sesscookiename]))
	{
		$sessid = $_COOKIE[$sesscookiename];
		$userid = get_userid_from_session($sessid, $sesscookietime, get_userip(), $db);
		if ($userid)
		{
			$user_loggedin = 1;
			update_session($sessid, $db);
			$userdata = get_userdata_by_id($userid, $db);
		}
	}
}
if($user_loggedin==0)
{
	echo "<div align=\"center\">$l_notloggedin</div>";
	echo "<div align=\"center\">";
	echo "<a href=\"login.php?lang=$lang\">$l_loginpage</a>";
	die ("</div>");
}
else
{
	$admin_rights=$userdata["rights"];
}
if(isset($target))
{
	if($admin_rights < 3)
	{
		die("$l_functionnotallowed");
	}
	if($target=="file")
	{
		header('Content-Type: application/octetstream');
		header('Content-Disposition: filename="'.$tableprefix.'.sql"');
    	$crlf="\r\n";
    	$asfile="download";
    }
    else
		print "<div align=left><pre>\n";

	$dump_buffer="";

	$tables = mysql_list_tables($dbname);
	$num_tables = @mysql_numrows($tables);

	if($num_tables == 0)
	{
		echo "# No Tables Found";
		exit;
	}
	$dump_buffer.= "# ProgSys DatabaseBackup$crlf";
	$dump_buffer.= "# Backup made:$crlf";
	$dump_buffer.= "# ".date("F j, Y, g:i a")."$crlf";
	$dump_buffer.= "# Database: $dbname$crlf";
	$dump_buffer.= "# Backed up tables with prefix: $backupprefix$crlf";

	$i = 0;
	while($i < $num_tables)
	{
		$table = mysql_tablename($tables, $i);
		if(strncmp($backupprefix,$table,strlen($backupprefix))==0)
		{
			$dump_buffer.= "# --------------------------------------------------------$crlf";
			$dump_buffer.= "$crlf#$crlf";
			$dump_buffer.= "# Table structure for table '$table'$crlf";
			$dump_buffer.= "#$crlf$crlf";
			$dump_buffer.= get_table_def($dbname,$table, $crlf).";$crlf";
			$dump_buffer.= "$crlf#$crlf";
			$dump_buffer.= "# Dumping data for table '$table'$crlf";
			$dump_buffer.= "#$crlf$crlf";
			$tmp_buffer="";
			get_table_content($dbname, $table, 0, 0, 'my_handler');
			$dump_buffer.=$tmp_buffer;
		}
		$i++;
		$dump_buffer.= "$crlf";
	}
	echo $dump_buffer;
	if($target!="file")
	{
		echo "</pre></div>\n";
	}
	exit;
}
$page_title=$l_dbbackup;
require('heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<tr class="displayrow" align="center">
<td><a href="<?php echo do_url_session("$act_script_url?target=file&lang=$lang")?>" target="_blank"><?php echo $l_downloadbackup?></a></td></tr>
<tr class="displayrow" align="center">
<td><a href="<?php echo do_url_session("$act_script_url?target=screen&lang=$lang")?>" target="_blank"><?php echo $l_displaybackup?></a></td></tr>
</table></td></tr></table>
<?php
include('trailer.php');
function get_table_def($db, $table, $crlf)
{

    $schema_create = "DROP TABLE IF EXISTS $table;$crlf";


    $schema_create .= "CREATE TABLE $table ($crlf";

    $result = mysql_query("SHOW FIELDS FROM " .$db."."
	. $table) or mysql_die();
    while($row = mysql_fetch_array($result))
    {
        $schema_create .= "   $row[Field] $row[Type]";

        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        $schema_create .= ",$crlf";
    }
    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
    $result = mysql_query("SHOW KEYS FROM " .$db."." .
	$table) or mysql_die();
    while($row = mysql_fetch_array($result))
    {
        $kname=$row['Key_name'];
        $comment=(isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part=(isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
            $kname="UNIQUE|$kname";

        if($comment=="FULLTEXT")
            $kname="FULLTEXT|$kname";
         if(!isset($index[$kname]))
             $index[$kname] = array();

        if ($sub_part>1)
         $index[$kname][] = $row['Column_name'] . "(" . $sub_part . ")";
        else
         $index[$kname][] = $row['Column_name'];
    }

    while(list($x, $columns) = @each($index))
    {
         $schema_create .= ",$crlf";
         if($x == "PRIMARY")
            $schema_create .= "   PRIMARY KEY (";
         elseif (substr($x,0,6) == "UNIQUE")
            $schema_create .= "   UNIQUE " .substr($x,7)." (";
         elseif (substr($x,0,8) == "FULLTEXT")
            $schema_create .= "   FULLTEXT ".substr($x,9)." (";
         else
            $schema_create .= "   KEY $x (";

        $schema_create .= implode($columns,", ") . ")";
    }

    $schema_create .= "$crlf)";
    if(get_magic_quotes_gpc()) {
      return (stripslashes($schema_create));
    } else {
      return ($schema_create);
    }
}
function get_table_content($db, $table, $limit_from = 0, $limit_to = 0, $handler)
{
    // Defines the offsets to use
    if ($limit_from > 0) {
        $limit_from--;
    } else {
        $limit_from = 0;
    }
    if ($limit_to > 0 && $limit_from >= 0) {
        $add_query  = " LIMIT $limit_from, $limit_to";
    } else {
        $add_query  = '';
    }

    get_table_content_fast($db, $table, $add_query, $handler);

}

function get_table_content_fast($db, $table, $add_query = '', $handler)
{
    $result = mysql_query('SELECT * FROM ' . $db . '.' . $table . $add_query) or mysql_die();
    if ($result != false) {

        @set_time_limit(1200); // 20 Minutes

        // Checks whether the field is an integer or not
        for ($j = 0; $j < mysql_num_fields($result); $j++) {
            $field_set[$j] = mysql_field_name($result, $j);
            $type          = mysql_field_type($result, $j);
            if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                $type == 'bigint'  ||$type == 'timestamp') {
                $field_num[$j] = true;
            } else {
                $field_num[$j] = false;
            }
        } // end for

        // Get the scheme
        if (isset($GLOBALS['showcolumns'])) {
            $fields        = implode(', ', $field_set);
            $schema_insert = "INSERT INTO $table ($fields) VALUES (";
        } else {
            $schema_insert = "INSERT INTO $table VALUES (";
        }

        $field_count = mysql_num_fields($result);

        $search  = array("\x0a","\x0d","\x1a"); //\x08\\x09, not required
        $replace = array("\\n","\\r","\Z");


        while ($row = mysql_fetch_row($result)) {
            for ($j = 0; $j < $field_count; $j++) {
                if (!isset($row[$j])) {
                    $values[]     = 'NULL';
                } else if (!empty($row[$j])) {
                    // a number
                    if ($field_num[$j]) {
                        $values[] = $row[$j];
                    }
                    // a string
                    else {
                        $values[] = "'" . str_replace($search, $replace, addslashes($row[$j])) . "'";
                    }
                } else {
                    $values[]     = "''";
                } // end if
            } // end for

            $insert_line = $schema_insert . implode(',', $values) . ')';
            unset($values);

            // Call the handler
            $handler($insert_line);
        } // end while
    } // end if ($result != false)

    return true;
}


function my_handler($sql_insert)
{
	global $crlf, $asfile;
	global $tmp_buffer;

	if(empty($asfile))
		$tmp_buffer.= htmlspecialchars("$sql_insert;$crlf");
	else
		$tmp_buffer.= "$sql_insert;$crlf";
}
?>
