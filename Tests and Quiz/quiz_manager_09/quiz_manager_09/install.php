<html>
<head><title>Admin Control Panel</title>
<style type="text/css">
<!--
     A:link {text-decoration: none;}
     A:visited {text-decoration: none;}
-->
</style>
</head>
<body bgcolor="#008080" link="#FF0000" vlink="#FF0000" alink="#FF0000">
<center>
<table border="3" cellpadding="2" cellspacing="6" width=600 bgcolor="#FFFFFF">
    <tr>
        <td bgcolor="#004080" align="left"><font
        color="#FFFFFF" size="2" face="Verdana"><strong>Installation</strong></font>
        </td>
    </tr>
    <tr>
        <td align="center"><table border="0" width=550>
            <tr>
                <td valign="top" width=50%><br>
		<font size="2" face="Verdana">
<!-- #### HTML #### -->
<?


#- first check for config.php status
print "<font color=red>";
if(!is_writable("config.php")){
	print "Please set \"config.php\" file as writablable(chmod 766), to begin installation!";
	exit;
}

require "config.php";

if($host != "" && $login != ""){
        $dbh = @mysql_connect($host, $login, $password)
                or $dberr=1;
	if($dberr){
		print("Cannot connect to database with the information provided. Please check your host/login/password entries.");
	}else{

		@mysql_select_db($database)
			or $dberr=1;

		if($dberr){
			if($create_database && $database != ""){
				print "<font color=navy><b>Creating $database:";
				   if (mysql_create_db($database)) {
					print ("Database created successfully\n");
					$dberr = 0;
				    } else {
					printf ("<font color=red>Error creating database: %s\n</font>", mysql_error ());
				    }
				 print "</b></font>";
			}else{
				print("Cannot connect to the database. Please check your \"MySQL database name\" entry!");
			}
		}

	}
		if($result = @mysql_query("SELECT NOW()") && !$dberr){
			$connected = 1;
	}

}
print "</font>";


#- ask for mysql installation
?>

<?
print "<p>MySQL status: ";
if($connected){
	print "<b><font color=green>connected</font></b>";

	if($admin_password != ""){
		write_config();
		if(create_tables() == -1){
			print "<p>Cannot create tables!"; 
		}else{
			print "<p><b>INSTALLATION COMPLETE!</b>";
			print "<p>Security Warning:Please make sure \"config.php\" is not writable any more! Or delete \"install.php\".";	
			$done = 1;
		}
	}

}else{
	print "<b><font color=blue>not connected</font></b>";
}

if(!$done){
?>
<p>

<form action=install.php method=post>
<table border=0>
<tr><td>
<font size="2" face="Verdana">
MySQL hostname:
</td><td>
<input type=text size=20 name=host value="<? if($host != "") print $host; else print "localhost" ?>">
</td></tr>
<tr><td>
<font size="2" face="Verdana">
MySQL database name:
</td><td>
<input type=text size=20 name=database value="<? if($database != "") print $database; ?>">
</td><td>
<font size="2" face="Verdana">
<input type=checkbox name=create_database>Create new!
</td></tr>
<tr><td>
<font size="2" face="Verdana">
MySQL login:
</td><td>
<input type=text size=20 name=login value="<? if($login != "") print $login; ?>">
</td></tr>
<tr><td>
<font size="2" face="Verdana">
MySQL password:
</td><td>
<input type=password size=20 name=password value="<? if($password != "") print $password; ?>">
</td></tr>
<tr><td>
<font size="2" face="Verdana">
Admin password:
</td><td>
<input type=password size=20 name=admin_password value="<? if($admin_password != "") print $admin_password; ?>">
</td></tr>
<tr><td>
<font size="2" face="Verdana">
Email:
</td><td>
<font size="2" face="Verdana">
<input type=radio name=email_required value="yes" <? if($email_required != "no") print checked; ?>> Required<br>
<input type=radio name=email_required value="no" <? if($email_required == "no") print checked; ?>> Not required
</td></tr>

</table>

<p>


<input type=submit name=submit value="Install!">
</form>

<?
}

function write_config()
{
	global $admin_password, $host, $database, $login, $password, $email_required;

	$out = "<";
	$out .= "?php

	  \$admin_pass = \"$admin_password\";


	  \$db_host = \"$host\";
	  \$db_name = \"$database\";
	  \$db_user = \"$login\";
	  \$db_password = \"$password\";

	  \$tempz[form] = \"templates/quiz_form_template.html\";

	";
	if($email_required == "no")
		$out .= " \$email_not_required = 1; ";
	else
		$out .= " \$email_not_required = 0; ";

	$out .= "
	\n?";
	$out .= ">";


	$fp = fopen("config.php","w"); 
	fwrite($fp, $out); 
	fclose($fp);
}


function create_tables()
{
	global $database;
        $query = "
create table quizes(
	id     int(5) primary key auto_increment,
	name   varchar(64),
	question   varchar(128),
	answer1   varchar(128),
	answer2   varchar(128),
	answer3   varchar(128),
	answer4   varchar(128),
	created	  datetime,
	start date not null,
	end date not null,
	index(start),
	index(end)
)
	";

        $dbc = mysql_query($query);

        $query = "
create table votes(
	quiz_id    int(5) not null,
	email      varchar(64) not null,
        vote       int(1) not null,
        IP         varchar(16) not null,
        created    datetime,
	index(quiz_id),
	index(ip)
)
	";

        $dbc = mysql_query($query);

	$result = mysql_list_tables($database);
	if( mysql_num_rows($result) != 2 ){
		return -1;
	}

	return 1;
}


function display_perms( $mode ) 
{ 
/* Determine Type */ 
if( $mode & 0x1000 ) 
$type='p'; /* FIFO pipe */ 
else if( $mode & 0x2000 ) 
$type='c'; /* Character special */ 
else if( $mode & 0x4000 ) 
$type='d'; /* Directory */ 
else if( $mode & 0x6000 ) 
$type='b'; /* Block special */ 
else if( $mode & 0x8000 ) 
$type='-'; /* Regular */ 
else if( $mode & 0xA000 ) 
$type='l'; /* Symbolic Link */ 
else if( $mode & 0xC000 ) 
$type='s'; /* Socket */ 
else 
$type='u'; /* UNKNOWN */ 

/* Determine permissions */ 
$owner["read"] = ($mode & 00400) ? 'r' : '-'; 
$owner["write"] = ($mode & 00200) ? 'w' : '-'; 
$owner["execute"] = ($mode & 00100) ? 'x' : '-'; 
$group["read"] = ($mode & 00040) ? 'r' : '-'; 
$group["write"] = ($mode & 00020) ? 'w' : '-'; 
$group["execute"] = ($mode & 00010) ? 'x' : '-'; 
$world["read"] = ($mode & 00004) ? 'r' : '-'; 
$world["write"] = ($mode & 00002) ? 'w' : '-'; 
$world["execute"] = ($mode & 00001) ? 'x' : '-'; 

/* Adjust for SUID, SGID and sticky bit */ 
if( $mode & 0x800 ) 
$owner["execute"] = ($owner[execute]=='x') ? 's' : 'S'; 
if( $mode & 0x400 ) 
$group["execute"] = ($group[execute]=='x') ? 's' : 'S'; 
if( $mode & 0x200 ) 
$world["execute"] = ($world[execute]=='x') ? 't' : 'T'; 

$r = sprintf("%1s", $type); 
$r .= sprintf("%1s%1s%1s", $owner[read], $owner[write], $owner[execute]); 
$r .= sprintf("%1s%1s%1s", $group[read], $group[write], $group[execute]); 
$r .= sprintf("%1s%1s%1s\n", $world[read], $world[write], $world[execute]); 
return $r;
}



?>
<!-- #### HTML #### -->
<p><br>
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>
</center>
</body>
</html>
