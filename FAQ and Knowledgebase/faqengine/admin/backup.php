<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
if($admoldhdr)
{
	header('Pragma: no-cache');
	header('Expires: 0');
}
else
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
}
if(!$insafemode)
	@set_time_limit($longrunner);
$exclude_tables=array($tableprefix."_iplog",$tableprefix."_session",$tableprefix."_failed_logins");
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
require_once('../functions.php');
require_once('./includes/constants.inc');
$backupprefix=$tableprefix."_";
$url_sessid=0;
$user_loggedin=0;
$userdata=Array();
if(!isset($onlydata))
	$onlydata=0;
if(!isset($target))
	$target=0;
$page_title=$l_dbbackup;
if($enable_htaccess)
{
	if(isbanned(get_user_ip(),$db))
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td class="prognamerow"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr><td class="pagetitlerow"><h2><?php echo $page_title?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td colspan="2" align="center"><b><?php echo $l_ipbanned?></b></td></tr>
<tr class="displayrow"><td align="right" width="20%"><?php echo $l_reason?>:</td>
<td align="left" width="80%"><?php echo $banreason?></td></tr>
</table></td></tr></table></body></html>
<?php
	}
	$username=$REMOTE_USER;
	$myusername=addslashes(strtolower($username));
	$sql = "select * from ".$tableprefix."_admins where username='$myusername'";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database");
	if (!$myrow = faqe_db_fetch_array($result))
	{
	    die("<tr class=\"errorrow\"><td>$l_undefuser");
	}
	$userid=$myrow["usernr"];
	$user_loggedin=1;
    $userdata = get_userdata_by_id($userid, $db);
}
else if($sessid_url)
{
	if(isset($$sesscookiename))
	{
		$url_sessid=$$sesscookiename;
		$userid = get_userid_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
		if ($userid) {
		   $user_loggedin = 1;
		   update_session($url_sessid, $db);
		   $userdata = get_userdata_by_id($userid, $db);
		   $userdata["lastlogin"]=get_lastlogin_from_session($url_sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
}
else
{
	$userid="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$sesscookiename]))
		{
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
	else
	{
		if(isset($_COOKIE[$sesscookiename])) {
			$sessid = $_COOKIE[$sesscookiename];
			$userid = get_userid_from_session($sessid, $sesscookietime, get_user_ip(), $db);
		}
	}
	if ($userid)
	{
	   $user_loggedin = 1;
	   update_session($sessid, $db);
	   $userdata = get_userdata_by_id($userid, $db);
	   $userdata["lastlogin"]=get_lastlogin_from_session($sessid, $sesscookietime, get_user_ip(), $db);
	}
}
if($user_loggedin==0)
{
	echo "<div align=\"center\">$l_notloggedin2</div>";
	echo "<div align=\"center\">";
	echo "<a href=\"login.php?$langvar=$act_lang\">$l_loginpage</a>";
	die ("</div>");
}
else
{
	$admin_rights=$userdata["rights"];
}
if(isset($dobackup))
{
	if($admin_rights < 3)
		die("$l_functionnotallowed");
	$localcrlf=$crlftypes[$selectedcrlftype];
	if(isset($no_banlist))
		array_push($exclude_tables,$banprefix."_banlist");
	if(isset($no_hostcache))
		array_push($exclude_tables,$hcprefix."_hostcache");
	if(isset($no_leachers))
		array_push($exclude_tables,$hcprefix,"_leachers");
	if(isset($no_badwords))
		array_push($exclude_tables,$badwordprefix."_bad_words");
	if($target==1)
	{
		header('Content-Type: application/octetstream');
		header('Content-Disposition: filename="'.$tableprefix.'engine.sql"');
    		$asfile="download";
	}
	else
	{
		print "<div align=left><pre>\n";
	}

	$dump_buffer="";

	$tables = faqe_db_list_tables($dbname);
	$num_tables = @faqe_db_num_rows($tables);

	if($num_tables == 0)
	{
		echo "# No Tables Found";
		exit;
	}
	if($onlydata==1)
	{
		$dump_buffer.= "# FAQEDATABACKUP".$localcrlf;
		$dump_buffer.= "# FAQEBACKUPVERSION ".$backup_version.$localcrlf;
		$dump_buffer.= "# CRLFTYPE: ".$crlftype_text[$selectedcrlftype].$localcrlf;
	}
	$dump_buffer.= "# FAQEngine DatabaseBackup".$localcrlf;
	$dump_buffer.= "# Backup made:".$localcrlf;
	$dump_buffer.= "# ".date("F j, Y, g:i a").$localcrlf;
	$dump_buffer.= "# Database: ".$dbname.$localcrlf;
	$dump_buffer.= "# Version of FAQEngine used to make backup: ".$faqeversion.$localcrlf;
	$dump_buffer.= "# Backed up tables with prefix: ".$backupprefix.$localcrlf;
	if($onlydata==1)
	{
		$dump_buffer.= "# Only table data dumped".$localcrlf;
		$dump_buffer.= "# --------------------------------------------------------".$localcrlf;
		$dump_buffer.= $localcrlf."#".$localcrlf;
	}

	$i = 0;
	while($i < $num_tables)
	{
		$table = faqe_db_tablename($tables, $i);
		$backuptable=false;
		if(strncmp($backupprefix,$table,strlen($backupprefix))==0)
			$backuptable=true;
		if(strncmp($hcprefix."_",$table,strlen($hcprefix."_"))==0)
			$backuptable=true;
		if(strncmp($banprefix."_",$table,strlen($banprefix."_"))==0)
			$backuptable=true;
		if(strncmp($badwordprefix."_",$table,strlen($badwordprefix."_"))==0)
			$backuptable=true;
		if(strncmp($leacherprefix."_",$table,strlen($leacherprefix."_"))==0)
			$backuptable=true;

		if($backuptable)
		{
			if($onlydata==0)
			{
				$dump_buffer.= "# --------------------------------------------------------".$localcrlf;
				$dump_buffer.= $localcrlf."#".$localcrlf;
				$dump_buffer.= "# Table structure for table '$table'".$localcrlf;
				$dump_buffer.= "#".$localcrlf.$localcrlf;
				$dump_buffer.= get_table_def($dbname, $table, $localcrlf, $db).";".$localcrlf;
				$dump_buffer.= $localcrlf."#".$localcrlf;
			}
			$tmp_buffer="";
			if(($onlydata==0) || (!in_array($table,$exclude_tables)))
			{
				if($onlydata==1)
				{
					$dump_buffer.= "# deleting old data for table '$table'".$localcrlf;
					$dump_buffer.= "DELETE from $table;#%%".$localcrlf.$localcrlf;
				}
				get_table_content($dbname, $table, 0, 0, 'my_handler', $db, $onlydata);
				if(strlen($tmp_buffer)>0)
				{
					$dump_buffer.= "# Dumping data for table '$table'".$localcrlf;
					$dump_buffer.= "#".$localcrlf.$localcrlf;
					$dump_buffer.=$tmp_buffer;
				}
			}
		}
		$i++;
		$dump_buffer.= $localcrlf;
	}
	echo $dump_buffer;
	if($target==0)
	{
		echo "</pre></div>\n";
	}
	exit;
}
require('heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"displayrow\" align=\"center\"><td>";
	echo "$l_nofunctions";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
?>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="dobackup" value="1">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<?php
		if($sessid_url)
			echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
		if(is_konqueror())
			echo "<tr><td></td></tr>";
?>
<tr class="inputrow"><td width="30%">&nbsp;</td><td align="left">
<input type="radio" name="target" value="0"> <?php echo $l_displaybackup?><br>
<input type="radio" name="target" value="1" checked> <?php echo $l_downloadbackup?>
</td></tr>
<tr class="inputrow"><td width="30%">&nbsp;</td><td align="left">
<input type="radio" name="onlydata" value="0"> <?php echo $l_completebackup?><br>
<input type="radio" name="onlydata" value="1" checked> <?php echo $l_databackup?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_crlftype?>:</td>
<td>
<?php
for($i=0;$i<count($crlftypes);$i++)
{
	echo "<input type=\"radio\" name=\"selectedcrlftype\" value=\"$i\"";
	if($crlf==$crlftypes[$i])
		echo " checked";
	echo ">".$crlftype_text[$i]."<br>";
}
?>
</td></tr>
<tr class="inputrow"><td width="30%" align="right" valign="top"><?php echo $l_dontbackup?>:</td>
<td valign="top">
<input type="checkbox" name="no_banlist" value="1"> Banlist<br>
<input type="checkbox" name="no_hostcache" value="1"> Hostcache<br>
<input type="checkbox" name="no_leachers" value="1"> Leacher<br>
<input type="checkbox" name="no_badwords" value="1"> Bad_words
</td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="submit" class="faqebutton" name="submit" value="<?php echo $l_ok?>"></td></tr></form>
</table></td></tr></table>
<?php
include('./trailer.php');
function get_table_def($db, $table, $crlf, $dbconnection)
{
	$schema_create = "DROP TABLE IF EXISTS $table;".$crlf."#%%".$crlf;
	$schema_create .= "CREATE TABLE $table (".$crlf;

	$result = faqe_db_query("SHOW FIELDS FROM " .$db."."
	. $table,$dbconnection) or die();
	while($row = faqe_db_fetch_array($result))
	{
		$schema_create .= "   $row[Field] $row[Type]";

	        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
			$schema_create .= " DEFAULT '$row[Default]'";
		if($row["Null"] != "YES")
			$schema_create .= " NOT NULL";
		if($row["Extra"] != "")
			$schema_create .= " $row[Extra]";
		$schema_create .= ",".$crlf;
	}
	$schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
	$result = faqe_db_query("SHOW KEYS FROM " .$db."." .
	$table,$dbconnection) or die();
	while($row = faqe_db_fetch_array($result))
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
function get_table_content($db, $table, $limit_from = 0, $limit_to = 0, $handler, $dbconnection, $onlydata)
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

    get_table_content_fast($db, $table, $add_query, $handler, $dbconnection, $onlydata);

}

function get_table_content_fast($db, $table, $add_query = '', $handler, $dbconnection, $onlydata)
{
    $result = faqe_db_query('SELECT * FROM ' . $db . '.' . $table . $add_query,$dbconnection) or die();
    if ($result != false) {

        // Checks whether the field is an integer or not
        for ($j = 0; $j < faqe_db_num_fields($result); $j++) {
            $field_set[$j] = faqe_db_field_name($result, $j);
            $type          = faqe_db_field_type($result, $j);
            if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                $type == 'bigint'  ||$type == 'timestamp') {
                $field_num[$j] = true;
            } else {
                $field_num[$j] = false;
            }
        } // end for

        // Get the scheme
        if ($onlydata==1) {
            $fields        = implode(', ', $field_set);
            $schema_insert = "INSERT INTO $table ($fields) VALUES (";
        } else {
            $schema_insert = "INSERT INTO $table VALUES (";
        }

        $field_count = faqe_db_num_fields($result);

        $search  = array("\x0a","\x0d","\x1a"); //\x08\\x09, not required
        $replace = array("\\n","\\r","\Z");


        while ($row = faqe_db_fetch_row($result)) {
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
            $handler($insert_line, $onlydata);
        } // end while
    } // end if ($result != false)

    return true;
}


function my_handler($sql_insert, $onlydata)
{
	global $crlf, $asfile;
	global $tmp_buffer;

	if(empty($asfile))
		$tmp_buffer.= htmlspecialchars("$sql_insert;");
	else
		$tmp_buffer.= "$sql_insert;";
	if($onlydata==1)
		$tmp_buffer.="#%%";
	$tmp_buffer.=$crlf;
}
?>
