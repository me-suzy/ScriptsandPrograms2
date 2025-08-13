<?php
	if($submitNo=="No")
	{	header("location: ../index.php");
		exit();
	}
	elseif($submitYes!="Yes")
		echo '
<html>
<head>
<title>Database Restore</title>
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="../admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<br>    <form name="form1" method="post" action="restore_db.php">

  <table width="100%" border="0" cellspacing="0">
    <tr> 
      <td align="center"> 
        <table width="300" border="0" cellspacing="0" cellpadding="2" class="tableborder">
          <tr> 
    <td class="tabletitle" bgcolor="#666666">Confirmation</td>
  </tr>
  <tr> 
      <td bgcolor="#F6F6F6" align="center" valign="middle"> 
        <p>&nbsp;</p>
              <p align="center"><b>You are about to restore the entire In-link database. This will DELETE your current database, if one exists. Are you absolutely sure you want to continue?</b></p>
  
        <div align="center">
				<input type="submit" name="submitYes" value="Yes" class="button">
                <input type="submit" name="submitNo" value="No" class="button">
        </div>
        <p>&nbsp;</p>
    </td>
  </tr>
</table>    </td>
  </tr>
</table></form>
<p>&nbsp; </p>
</body>
</html>';
$include_path="../../includes";
if($submitYes!="Yes") //if yes was not replied
	exit();

@set_time_limit(600);

$configfile="$include_path/config.php";
$adodb_path="$include_path/adodb";

$fd = @fopen($configfile, "r");
$cfg = fread($fd, 8000);
@fclose($fd);
$sql_server_pos=strpos($cfg,"\$sql_server = \"")+strlen("\$sql_server = \"");
$sql_server = substr($cfg, $sql_server_pos, strpos($cfg,"\"",$sql_server_pos) - $sql_server_pos);

$sql_user_pos=strpos($cfg,"\$sql_user = \"")+strlen("\$sql_user = \"");
$sql_user = substr($cfg, $sql_user_pos, strpos($cfg,"\"",$sql_user_pos) - $sql_user_pos);

$sql_pass_pos=strpos($cfg,"\$sql_pass = \"")+strlen("\$sql_pass = \"");
$sql_pass = substr($cfg, $sql_pass_pos, strpos($cfg,"\"",$sql_pass_pos) - $sql_pass_pos);

$sql_db_pos=strpos($cfg,"\$sql_db = \"")+strlen("\$sql_db = \"");
$sql_db = substr($cfg, $sql_db_pos, strpos($cfg,"\"",$sql_db_pos) - $sql_db_pos);

$sql_type_pos=strpos($cfg,"\$sql_type = \"")+strlen("\$sql_type = \"");
$sql_type = substr($cfg, $sql_type_pos, strpos($cfg,"\"",$sql_type_pos) - $sql_type_pos);

include_once("$include_path/adodb/adodb.inc.php");

if (!$conn=&ADONewConnection($sql_type) || !$conn->PConnect($sql_server, $sql_user, $sql_pass, $sql_db))
{	echo "Database Connection Failed!";
	exit();
}

if(!$file=@fopen("dump.txt","r"))
{		echo "File dump.txt could not be opened. Restoring Failed!";
		exit();
}
else
{
	$conn->Execute("DROP TABLE inl_cats");
	$conn->Execute("DROP TABLE inl_config");
	$conn->Execute("DROP TABLE inl_email");
	$conn->Execute("DROP TABLE inl_custom");
	$conn->Execute("DROP TABLE inl_fav");
	$conn->Execute("DROP TABLE inl_lc");
	$conn->Execute("DROP TABLE inl_links");
	$conn->Execute("DROP TABLE inl_rel_cats");
	$conn->Execute("DROP TABLE inl_reviews");
	$conn->Execute("DROP TABLE inl_search_log");
	$conn->Execute("DROP TABLE inl_sessions");
	$conn->Execute("DROP TABLE inl_users");
	$conn->Execute("DROP TABLE inl_votes");

	if($sql_type == "postgres7")
	{
		$conn->Execute("DROP SEQUENCE inl_cats_cat_id_seq");
		$conn->Execute("DROP SEQUENCE inl_custom_cust_id_seq");
		$conn->Execute("DROP SEQUENCE inl_email_email_id_seq");
		$conn->Execute("DROP SEQUENCE inl_links_link_id_seq");
		$conn->Execute("DROP SEQUENCE inl_reviews_rev_id_seq");
		$conn->Execute("DROP SEQUENCE inl_search_log_log_id_seq");
		$conn->Execute("DROP SEQUENCE inl_sessions_ses_id_seq");
		$conn->Execute("DROP SEQUENCE inl_users_user_id_seq");
	}

	$failed=0;
	while($rest=@fgets($file,200000))
	{	$rest=trim($rest);
		if(!$rest)
			continue;
		if(substr($rest,-1,1) == ";")
			$rest=substr($rest,0,strlen($rest)-1);
		echo $rest;
		if($conn->Execute($rest))
			echo " ...  <font color=\"green\"><B>OK</B></font><BR>";
		else
		{	echo " ...  <font color=\"red\"><B>FAILED</B></font><BR>";
			$failed=1;
		}
	}
	fclose($file);

	if($failed)
		echo "<BR><BR>Errors found. Resotore failed.";
	else
		echo "<BR><BR>Database successfully restored. Click <a href=\"../login.php\">here</a> to log in.";

}
?>
