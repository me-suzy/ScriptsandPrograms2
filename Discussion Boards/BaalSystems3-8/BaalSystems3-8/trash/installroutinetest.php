<?php
session_start();
$browser_string=$HTTP_USER_AGENT;
$client_ip=gethostbyname(localhost);     
if(session_is_registered("whossession"))      
{        
	$_SESSION['who']="admin";        
	$_SESSION['username']="admin";      
}      
else      
{        
	session_register("whossession");        
	$_SESSION['who']="admin";        
	$_SESSION['username']="admin";      
}?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>  
<title>Baal Smart Form</title>
<link rel="STYLESHEET" type="text/css" href="helpdeskrevolutions.css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0">
<table border="0" cellspacing="0" width="100%">
	<tr><td bgcolor="#6C9CFF" valign="top">
		<table border="0" cellpadding="2" cellspacing="0" height="100%">
			<tr><td align="right" valign="bottom">
				<font size="3" face="Comic Sans MS, Trebuchet MS, Verdana, Tahoma, Arial" color="#FFFFFF"><h2>&nbsp;Baal Smart Form</h2></font>
			</td></tr>
		</table>    
	</td></tr>
</table>

<?php
extract ($HTTP_POST_VARS,EXTR_OVERWRITE);
$tableprefix .= "_";
$MYSQL_ERRNO = "";
$MYSQL_ERROR = "";
$name1 = "tbluser";
$name2 = "tblgroup";
$name3 = "tblforum";
$name4 = "tblsubforum";
$name5 = "msgs";
$name6 = "adminprefs";

function db_connect()
{	
	$link_id=@mysql_connect($_POST['dbservername'],$_POST['dbusername'],$_POST['dbpassword']);	
	if( $link_id == 0 )	
	{			
		echo "<center>Could Not Connect to Database.  Check hostname, username and password</center><br />";	    
		return 0 ;	
	}	
	
	return $link_id;
}	

function create_access()
{	
	$filename = "dataaccess.php";	
	if (!$datafile = @fopen($filename, 'w'))	
	{
		echo "Cannot open file $filename";
		exit;	
	}	
	else	
	{		    
		$strcontent = "<?php ";	    
		$strcontent = $strcontent . "\$dbservername=\"" . $_POST['dbservername'] . "\" ;" ;	    
		$strcontent = $strcontent . "\$dbusername=\"" . $_POST['dbusername'] . "\" ;" ;	    
		$strcontent = $strcontent . "\$dbpassword=\"" . $_POST['dbpassword'] . "\" ;" ;	    
		$strcontent = $strcontent . "\$dbname=\"" . $_POST['dbname'] . "\" ;" ;	    
		$strcontent = $strcontent . " ?>";	    
		fwrite($datafile, $strcontent , strlen($strcontent));	    
		fclose($datafile);	}	$filename1 = 'dataaccess1.php';	
		if (!$datafile1 = fopen($filename1, 'w'))	
		{	         
			echo "Cannot open file ($filename1)";	         
			exit;	
		}	
		else	
		{	  
			$strcontent1 = "<?php";	  
			$strcontent1 = $strcontent1 . " function dbConnect(){";	  
			$strcontent1 = $strcontent1 . "\$link=mysql_connect('{$_POST['dbservername']}','{$_POST['dbusername']}' , '{$_POST['dbpassword']}');";	  
			$strcontent1 = $strcontent1 . "if (!\$link)";	  $strcontent1 = $strcontent1 . "{ Error_handler('Error connecting to database server' , \$link ); } ";	  
			$strcontent1 = $strcontent1 . "mysql_select_db('{$_POST['dbname']}', \$link);}";	  
			$strcontent1 .= "\$tableprefix = {$_POST['tableprefix']}_; ?>";	  /*$strcontent  = addslashes($strcontent)*/	  
			fwrite($datafile1, $strcontent1 , strlen($strcontent1));	  
			fclose($datafile1);	
		}		
		echo "<center>Data base references updated " ;	
		echo "<br /><br /><br /><a href=\"regadmin.php\">Proceed as Administrator</a></center>";
}	
	
function install()
{	
	global $tableprefix;	
	$testdb = mysql_select_db($_POST['dbname']);	
	if(!$testdb)	
	{		
		$querydb="create database {$_POST['dbname']}" ;    
		$resultdb=mysql_query($querydb);    
		if(mysql_select_db($_POST['dbname']) == false)    
		{      
			$MYSQL_ERRNO=mysql_errno();      
			$MYSQL_ERROR=mysql_error();      
			echo "Could not create database.<br>" ;      
			return 0;    
		}	
	}	
	else	
	{		
		echo "<center>Adding this installation to the same Database.</center><br />";		
	}		
	$table1 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tbluser (	
						userid INT NOT NULL AUTO_INCREMENT ,	
						username VARCHAR( 40 ) NOT NULL ,	
						password VARCHAR( 40 ) ,	
						userrole VARCHAR( 20 ) NOT NULL,	
						mail VARCHAR( 60 ),	
						PRIMARY KEY ( userid ) )";
								
	$table2 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblgroup(	
						groupid INT NOT NULL AUTO_INCREMENT ,	
						groupname VARCHAR( 80 ) NOT NULL ,	
						PRIMARY KEY ( groupid ) )";		
						
	$table3 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblforum(	
						forumid INT NOT NULL AUTO_INCREMENT ,	
						groupname VARCHAR(80) ,	
						subject VARCHAR( 100 ) ,	
						authorname VARCHAR( 60 ) ,	
						detail TEXT,	
						lastpost DATETIME ,	
						totalpost INT(11),	
						views INT(11),	
						PRIMARY KEY ( forumid ) )";		
						
	$table4 = "CREATE TABLE {$tableprefix}tblsubforum (	
						subforumid INT NOT NULL AUTO_INCREMENT ,	
						forumid INT(11),	
						groupname VARCHAR( 80 ) ,	
						subject VARCHAR( 100 ) ,	
						authorname VARCHAR( 60 ) ,	
						dateposted DATETIME ,	
						detail TEXT,	
						PRIMARY KEY ( subforumid ) )";		
					
	$table5 = "CREATE TABLE {$tableprefix}msgs (	
						msgsid INT NOT NULL AUTO_INCREMENT ,	
						subject VARCHAR( 100 ) ,	
						fromid INT( 11 ) ,	
						toid INT(11),	
						dateposted DATETIME ,	
						detail TEXT,	
						didread CHAR DEFAULT n,	
						PRIMARY KEY ( msgsid ) )";		
					
	$table6 = "CREATE TABLE {$tableprefix}adminprefs (	
						prefid INT NOT NULL AUTO_INCREMENT ,	
						msgs CHAR DEFAULT y,	
						PRIMARY KEY ( prefid ) )";		
					
	$ra = mysql_query($table1);	
	$rb = mysql_query($table2);	
	$rc = mysql_query($table3);	
	$rd = mysql_query($table4);	
	$re = mysql_query($table5);	
	$rf = mysql_query($table6);		
	
	if($ra && $rb && $rc && $rd && $re && $rf)	
	{		
		echo "<center>Installation Completed.</center><br />";		
	}
}

function upgrade()
{	
	global $tableprefix;	
	$testdb = mysql_select_db($_POST['dbname']);	
	$result;	
	if(!$testdb)	
	{		
		$result = "<center>There is no Database with the name {$_POST['dbname']} installed.<br />";		
		$result .= "Please go back and check that you have the correct Database name or do a Fresh Install.<br />";		
		$result .= "<br /><a href=\"install.php\">Go back</a><br /></center>";		
		return $result;	
		}	
		else	
		{			
			$table1 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tbluser SELECT * FROM $name1;";		
			$table2 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblgroup SELECT * FROM $name2;";		
			$table3 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblforum SELECT * FROM $name3;";		
			$table4 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblsubforum SELECT * FROM $name4;";		
			$table5 = "CREATE TABLE IF NOT EXISTS {$tableprefix}msgs SELECT * FROM $name5;";		
			$table6 = "CREATE TABLE IF NOT EXISTS {$tableprefix}adminprefs SELECT * FROM $name6;";				
			
			$ra = mysql_query($table1);		
			$rb = mysql_query($table2);		
			$rc = mysql_query($table3);		
			$rd = mysql_query($table4);		
			$re = mysql_query($table5);		
			$rf = mysql_query($table6);		
			
			if(!($ra && $rb && $rc && $rd && $re && $rf))		
			{			
				$result = "<center>There was an error upgrading the tables.</center><br />";			
				return $result;		
			}					
		}	
		
		$result = "<center>Tables Upgraded Successfully</center><br />";	
		return $result;
}

$link_id=db_connect();
if($link_id)
{	
	create_access();
	if($_POST['installtype'] == freshinstall)	
	{		
		echo install();	
	}	
	else if($_POST['installtype'] == upgrade)	
	{		
		echo upgrade();	
	}		
}
else 
	echo "<center>** Not connected **</center>";
	
?>
</body></html>