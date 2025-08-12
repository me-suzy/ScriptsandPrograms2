<?php

	$dirpath = "../";

	if(!$step || $step == "1a")
	require($dirpath."essential/config.php");
	else
	require($dirpath."essential/dbc_essential.php");

	if($step > 2)
	{
		$result_vars = queryDB( "SELECT * FROM $tbl_config" );
		while( $row_vars = mysql_fetch_array( $result_vars ))
		${$row_vars[fname]} = $row_vars[fnvalue];
		mysql_free_result ( $result_vars );
		unset( $row_vars );

		$bad_user_name = explode(",", $bad_user_name);
		$allow_types = explode(",", $allow_types);
	}

if(!$step) $welcome = "Welcome to";

$header_installation=<<<__HEADER_END

<html>
<head>
<title>Albinator Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.tn {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; font-style: normal}
.ts {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; font-style: normal}
-->
</style>
</head>

<body bgcolor="#FFFFFF" topmargin=0 bottommargin=0 marginheight=0 marginwidth=0 leftmargin=0 rightmargin=0>
<p align="right"><img src="../images/albinator.gif" width="225" height="60"></p>
<table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td bgcolor="#006699"> 
      <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="4" color="#FFFFFF">$welcome Albinator 1.03 Installation</font></div>
    </td>
  </tr>

__HEADER_END;


$footer_installation=<<<__FOOTER_END

  <tr bgcolor="#eeeeee"> 
    <td class=tn>&nbsp;</td>
  </tr>
  <tr bgcolor="#eeeeee"> 
    <td class=ts> 
      <div align="center">powererd by <a href="http://www.albinator.com/product/" target="_blank">Albinator</a>, 
        &copy; copyright 2001-02 <a href=http://www.mgzhome.com>mgZhome</a></div>
    </td>
  </tr>
</table>
</body>
</html>

__FOOTER_END;


if(!$step)
{

echo($header_installation);
?>

  <tr bgcolor="#eeeeee"> 
    <td class=tn> 
	<table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
	  <tr> 
	    <td class=tn> 
      <p>&nbsp;</p>
      <p><b>Before you proceed check following:</b></p>
      <ul class=tn>
        <li>All files are up on the server</li>
        <li>You are doing this for the first time, remember unexpected errors 
          will come if you do this process again</li>
        <li>You have configured config.php as 
          per the Installation manual</li>
        <li>All Perl scripts are in the cgi-bin directory (if any cgi scripts 
          were provided to you)</li>
        <li>You have imagemagick installed on the server (else you have the php 
          only version of Albinator)</li>
        <li>You have PHP 4.0.4+, MySql 3.23.xx+ & optionall Perl 5.0.x+ installed on the 
          server</li>
      </ul>
      <p align="center">If you have any problems/questions considering above please 
        stop now and mail us for details before proceeding...</p>
      <p>&nbsp;</p>
      <p><b>Points to remember about Albinator:</b></p>
      <ul>
	  <li>The DataPath directory, has been setup and is chmoded 777.</li>
        <li>Albinator needs Imagemagick &amp; Perl for the manipulate option and 
          for creating Auto Thumbnails, but if you opted for PHP only version 
          the Auto-Thumbnailing is available for .jpg &amp; .png formats from 
          PHP itself.</li>
      </ul>
      <p>&nbsp;</p>
      <p align="left"><b>The installation process is in 4 parts:</b></p>
      <ol class=tn>
        <li>Initialize/create database, tables (if you done this manually <a href=install.php?step=2>skip to 2nd</a>)</li>
        <li>Configure the system settings</li>
        <li>Create your first account (admin)</li>
        <li>Delete install.php from the server</li>
      </ol>
	<br>
	<p align=center class=tn>By installing Albinator means you read & abide 
      to the <a href="../terms3.html" target=_blank class="noundertn">terms & conditions</a> 
      of Albinator System</p>
	<p align="right">Are ready to proceed ? <a href="install.php?step=1a">start installation &gt;&gt;</a>&nbsp;</p>

	    </td>
	  </tr>
	</table>
    </td>
  </tr>
<?php
echo($footer_installation);

}

else if($step == "1a")
{

echo($header_installation);
?>

  <tr bgcolor="#eeeeee"> 
    <td class=tn height="182"> 
      <p>&nbsp;</p>
      <p align="center">Setup will now attempt to create the tables with the database 
        properties you provided in config.php<br>
        <b>Note</b>: if the database name provided is not present, then the setup will 
        attempt to create it : if you have the access to do so.</p>
      <p align="center">&nbsp;</p>
      <p align="center">here are the Values fetched from config.php, please 
        verify them...</p>
      <table width="50%" border="0" cellspacing="0" cellpadding="4" align="center">
        <tr bgcolor="#999999"> 
          <td width="50%" class="tn"><b>Database Configuration</b></td>
          <td class="tn">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">server name</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $database_host ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">database name</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $database_name ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">username</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $database_user ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">password</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $database_pass ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn">&nbsp;</td>
          <td class="tn">&nbsp;</td>
        </tr>
        <tr bgcolor="#999999"> 
          <td width="50%" class="tn"><b>Table Names</b></td>
          <td class="tn">&nbsp;</td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">Userinfo</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $tbl_userinfo ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">Albumlist</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $tbl_albumlist ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">Pictures</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $tbl_pictures ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">Userwait</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $tbl_userwait ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">Config</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $tbl_config ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">Adlogs</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $tbl_adlogs ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">eCards</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $tbl_ecards ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">Publist</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $tbl_publist ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#dddddd">Reminders</td>
          <td class="tn" bgcolor="#dddddd"><?php echo $tbl_reminders ?></td>
        </tr>
        <tr> 
          <td width="50%" class="tn" bgcolor="#cccccc">Userprofile</td>
          <td class="tn" bgcolor="#cccccc"><?php echo $tbl_userprofile ?></td>
        </tr>
      </table>
      <p align="center"><b>Are these values correct?</b></p>
      <p>&nbsp;</p>
      <p align="right">yeah they are correct proceed to, <a href="install.php?step=1">initialize 
        database &gt;&gt;</a>&nbsp;</p>
    </td>
  </tr>
<?php
echo($footer_installation);
exit;
}

else if($step == 1)
{
     $allcorrect = 0;

     error_reporting(0);
     $errno = InstallConnectDB();
     $errno = "$errno";
     error_reporting(E_ERROR | E_WARNING);

     if($errno == "1049" || $errno == "1044" || $errno == "1045")
     {
	// create database
	if($database_user)
	$db_login_g = "$database_user@localhost";
	else
	{ $db_login_g = "albinator@localhost"; $db_login_g2 = "albinator";
	  $ch_db = 1; }
	
	if($database_pass)
	$db_pass_g = $database_pass;
	else
	{ $db_pass_g = "albinator"; $ch_db = 1; }

      error_reporting(0);
	mysql_query("CREATE DATABASE IF NOT EXISTS $defDB");
	mysql_query("grant all privileges on $defDB.* to $db_login_g identified by '$db_pass_g'");
	mysql_query("flush privileges");
      error_reporting(E_ERROR | E_WARNING);
	closeDB();

	      // check again
	      $errno = InstallConnectDB();
	      $errno = "$errno";

		if($errno == "1044" || $errno == "1045")
		{
		$errMsg = ("Database doesn't exists or user/password provided is incorrect, Setup tried to create but failed due to lack of permission. Please consult your server adminstrator");
		$allcorrect = 0;
		}

		else
		{
		 $allcorrect = 2;
		}
     }

     else if($errno == "1045")
     {
      $errMsg = ("Sorry, The authentication to the mysql server failed, please check the username and password in <b>config.php</b>");
     }

     else if($errno == "2005" || $errno == "2003")
     {
      $errMsg = ("Sorry, the setup could not connect to the server name provided ($__serName). Please consult your installation manual or your server adminstrator for efficiency of the mysql server.");
     }

    else if($errno && $errno != "1146" && $errno != "1")
    {
     $errMsg = ("An unexpected error occurred, Error No $errno. Please contact mgZhome.<br><br>Mysql said: ");
     $errMsg .= mysql_errno().": ".mysql_error()."<BR>";
    }

     else
     $allcorrect = 1;

     if($allcorrect != 0)
     {
	// create tables
	closeDB();

      $errno = InstallConnectDB();
      $errno = "$errno";

	if($errno != 1146)
      $errMsg = ("$tbl_userinfo :: Table exists,<BR>");
	
	$result = mysql_db_query( $defDB, "Select * from $tbl_albumlist" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_albumlist :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_pictures" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_pictures :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_publist" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_publist :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_adlogs" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_adlogs :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_config" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_config :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_userwait" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_userwait :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_reminders" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_reminders :: Table exists,<BR>");

	$result = mysql_db_query( $defDB, "Select * from $tbl_ecards" );
	$errno = mysql_errno();
	if($errno != 1146)
      $errMsg .= ("$tbl_ecards :: Table exists,<BR>");


	if($errMsg)
	{
	    $errMsg .= "<BR>please change the name of the table(s) in the configuration file or delete the earlier table.";

	    echo($header_installation);
	    echo("<tr><td><BR>");
	    errMessage( $errMsg, 'error' );
	    echo("<BR></td></tr>");
	    echo($footer_installation);
	    exit;
	}

	else
	{
	closeDB();

	$result = queryDB( "CREATE TABLE $tbl_adlogs (
      logid bigint(20) NOT NULL auto_increment,
      uid varchar(15) NOT NULL DEFAULT '0' ,
      acctimedate varchar(100) NOT NULL DEFAULT '' ,
      status tinyint(1) NOT NULL DEFAULT '1' ,
      msg text NOT NULL DEFAULT '' ,
      PRIMARY KEY (logid),
      KEY logid (logid),
      KEY uid (uid, logid),
      KEY status (status) );" );

	$result = queryDB( "CREATE TABLE $tbl_albumlist (
      aname varchar(100) NOT NULL DEFAULT '' ,
      aid bigint(20) NOT NULL auto_increment,
      uid varchar(15) NOT NULL DEFAULT '0' ,
      password varchar(50) DEFAULT '0' ,
      private tinyint(1) DEFAULT '0' ,
      amsg varchar(99) ,
      cdate int(8) NOT NULL DEFAULT '0' ,
      sused bigint(20) NOT NULL DEFAULT '0' ,
      pused mediumint(9) NOT NULL DEFAULT '0' ,
      PRIMARY KEY (aid),
	KEY aid (aid),
	KEY uid (uid, aid),
      KEY cdate (cdate) );" );

	$result = queryDB( "CREATE TABLE $tbl_config (
      fname varchar(25) NOT NULL DEFAULT '' ,
      fnvalue mediumtext ,
      PRIMARY KEY (fname),
      KEY fname (fname) );" );

	$result = queryDB( "CREATE TABLE $tbl_ecards (
      ecid bigint(20) NOT NULL auto_increment,
      uid varchar(15) NOT NULL DEFAULT '0' ,
      rec_name varchar(100) NOT NULL DEFAULT '0' ,
      rec_email varchar(150) NOT NULL DEFAULT '0' ,
      colors varchar(20) NOT NULL DEFAULT '0' ,
      message text NOT NULL DEFAULT '' ,
      pic varchar(20) NOT NULL DEFAULT '0' ,
      music varchar(10) DEFAULT '1' ,
      makedate varchar(8) NOT NULL DEFAULT '0' ,
      notify tinyint(1) NOT NULL DEFAULT '0' ,
      code varchar(100) NOT NULL DEFAULT '0' ,
      mailsent tinyint(1) NOT NULL DEFAULT '0' ,
      PRIMARY KEY (ecid),
      KEY ecid (ecid),
	KEY uid (uid, ecid),
      KEY mailsent (mailsent) );" );

	$result = queryDB( "CREATE TABLE $tbl_pictures (
      pid bigint(20) NOT NULL auto_increment,
      aid int(11) NOT NULL DEFAULT '0' ,
      pname varchar(100) NOT NULL DEFAULT '0' ,
      pindex smallint(6) NOT NULL DEFAULT '0' ,
      pmsg text ,
      o_used mediumint(9) NOT NULL DEFAULT '0' ,
      i_used mediumint(9) NOT NULL DEFAULT '0' ,
      t_used mediumint(9) NOT NULL DEFAULT '0' ,
      PRIMARY KEY (pid),
      KEY pid (pid),
      KEY aid (aid, pid),
      KEY pindex (pindex) );" );

	$result = queryDB( "CREATE TABLE $tbl_publist (
      pubid bigint(20) NOT NULL auto_increment,
      name varchar(100),
      email varchar(200) NOT NULL DEFAULT '0' ,
      userval varchar(15),
      PRIMARY KEY (pubid),
 	KEY pubid (pubid) );" );

	$result = queryDB( "CREATE TABLE $tbl_reminders (
      rid bigint(20) NOT NULL auto_increment,
      uid varchar(15) NOT NULL DEFAULT '0' ,
      event varchar(100) NOT NULL DEFAULT '0' ,
      message text ,
      estatus smallint(1) NOT NULL DEFAULT '1' ,
      date_year smallint(4) NOT NULL DEFAULT '0' ,
      date_month tinyint(2) NOT NULL DEFAULT '0' ,
      date_day tinyint(2) NOT NULL DEFAULT '0' ,
      PRIMARY KEY (rid),
      KEY rid (rid),
      KEY uid (uid, rid) );" );

	$result = queryDB( "CREATE TABLE $tbl_userinfo (
      uid varchar(15) NOT NULL DEFAULT '0' ,
      password varchar(50) NOT NULL DEFAULT '' ,
      uname varchar(100) NOT NULL DEFAULT '' ,
      email varchar(70) NOT NULL DEFAULT '' ,
      country varchar(40) ,
      sessiontime bigint(20) NOT NULL DEFAULT '0' ,
      lastip varchar(100) ,
      admin tinyint(1) DEFAULT '0' ,
      status tinyint(1) NOT NULL DEFAULT '0' ,
      prefs varchar(10) ,
      profile longtext NOT NULL DEFAULT '' ,
      adddate varchar(8) NOT NULL DEFAULT '0' ,
      limits varchar(15) DEFAULT '0' ,
      sused bigint(20) DEFAULT '0' ,
      pused mediumint(9) DEFAULT '0' ,
      langcode char(3) ,
	validity VARCHAR(8) DEFAULT '0' NOT NULL ,
	logintime smallint(5) unsigned DEFAULT '0' ,
      PRIMARY KEY (uid),
	KEY uid (uid),
      UNIQUE email (email),
      KEY email_2 (email),
      KEY status (status),
      KEY adddate (adddate) );" );

	$result = queryDB( "CREATE TABLE $tbl_userprofile (
      fid tinyint(4) NOT NULL auto_increment,
      type varchar(10) NOT NULL DEFAULT '0' ,
      tname varchar(100) NOT NULL DEFAULT '0' ,
      topts text NOT NULL DEFAULT '' ,
      dvalue varchar(50) ,
      findex smallint(6) NOT NULL DEFAULT '0' ,
      PRIMARY KEY (fid),
      KEY fid (fid),
      KEY findex (findex) );" );


	$result = queryDB( "CREATE TABLE $tbl_userwait (
      uid varchar(15) NOT NULL DEFAULT '0' ,
      code varchar(100) NOT NULL DEFAULT '0' ,
      adddate varchar(8) NOT NULL DEFAULT '0' ,
      PRIMARY KEY (uid),
      KEY uid (uid) );" );

	echo($header_installation);
	echo("<tr bgcolor=#eeeeee class=tn align=center><td><BR>");

	if($allcorrect == 1)
	echo("<br><b>Database found, tables created</b>");
	else if($ch_db == 1)
	echo('<br><b>Database created, tables created<br><br>Before proceding please make changes in your config.php<br>$database_user = '.$db_login_g2.'<br>$database_pass = '.$db_pass_g.'</b><br><br>you must do the changes and upload config.php again to make the next installation step work<br><br>');
	else
	echo("<br><b>Database created, tables created</b>");

	echo("<BR><b>Congratulations All the database has been initialized.</b>");
?>

      <p align="center"><b>lets move to the 2nd step now</b></p>
      <p>&nbsp;</p>
      <p align="right"> <a href="install.php?step=2">configure system &gt;&gt;</a>&nbsp;</p>
    </td>
  </tr>

<?php
	echo($footer_installation);
	exit;
	}
     }

     else if($errMsg)
     {
	    echo($header_installation);
	    echo("<tr><td><BR>");
	    errMessage( $errMsg, 'error' );
	    echo("<BR></td></tr>");
	    echo($footer_installation);
	    exit;
     }
     exit;
}

else if($step == 2)
{
	   echo($header_installation);
	   echo ("<P>&nbsp;</P>");

$system_version = "1.03";
$rootdir = '';
$sitename = 'Albinator';
$systemname = 'Albinator';
$buyline = 'share photo albums with the world';
$SiteTitle = '';
$main_bgimage = '/images/design/background.gif';
$main_bgcolor = '#FFFFFF';
$table_size = '90';
$logout_time = '600';
$p = 'p1';
$buylink = "feedback.php";
$mainurl = 'http://';
$adminname = '';
$adminmail = '';
$default_space = '2';
$default_album = '15';
$default_remind = '15';
$show_min = '5';
$maxshow = '20';
$makelogs = '1';
$ecard_days = '10';
$unact_days = '2';
$abuse_link = 'mailto:abuse@yourdomain.com?Subject=Abuse';
$showspace_taken = '1';
$allowed_size = '500';
$allow_types = 'image/x-png,image/pjpeg,image/jpeg';
$datapath = 'data';
$msgfooter = '';
$allow_types_show = '.jpg, .png';
$bad_user_name = 'temp,system';

$site_msg = "Albinator is a realm of photos the mystical land of visualizing expirence... and their professional display layouts and its totally free, you can have your good times always with you if you have those special moments of life collected and easily accessed anytime you want... what best to put it somewhere nice and safe...


With albinator you can:

= create and personlize your albums
= create private albums
= add pictures with simple steps  
= arrange your photos as you like
= never you would need to send bulky email attachments with photos
= tell family & friends about particular albums
= make your pictures a personalised photo eCards
= never forget those special dates with personal reminders
= manipulate / edit your photos to look better
   and lots lots more...

And the best thing about it is that its Absolutely FREE...

signup now: http://www.albinator.com/register.php

for more information visit:
http://www.albinator.com/info.php
Albinator is a realm of photos the mystical land of visualizing expirence... and their professional display layouts and its totally free, you can have your good times always with you if you have those special moments of life collected and easily accessed anytime you want... what best to put it somewhere nice and safe...


With albinator you can:

= create and personlize your albums
= create private albums
= add pictures with simple steps  
= arrange your photos as you like
= never you would need to send bulky email attachments with photos
= tell family & friends about particular albums
= make your pictures a personalised photo eCards
= never forget those special dates with personal reminders
= manipulate / edit your photos to look better
   and lots lots more...

And the best thing about it is that its Absolutely FREE...

signup now: http://www.albinator.com/register.php

for more information visit:
http://albums.mgzhome.com/info.php
Albinator is a realm of photos the mystical land of visualizing expirence... and their professional display layouts and its totally free, you can have your good times always with you if you have those special moments of life collected and easily accessed anytime you want... what best to put it somewhere nice and safe...


With albinator you can:

= create and personlize your albums
= create private albums
= add pictures with simple steps  
= arrange your photos as you like
= never you would need to send bulky email attachments with photos
= tell family & friends about particular albums
= make your pictures a personalised photo eCards
= never forget those special dates with personal reminders
= manipulate / edit your photos to look better
   and lots lots more...

And the best thing about it is that its Absolutely FREE...

signup now: http://www.albinator.com/register.php

for more information visit:
http://www.albinator.com/info.php";

$tbwidth_long = '75';
$tbheight_long = '100';
$tbwidth_short = '100';
$tbheight_short = '75';
$exceed_width = '600';
$exceed_height = '800';
$ResizeBy = '1';
$remind_msg_max = '250';
$imgdir = 'images';
$cgidir = 'cgi-bin';
$dprefs = 'BRML';

	if($confirm == 1)
	{
       if(empty($frm_adminname))
	 $errMsg .=	"No Admin name provided<br>";

	 else if(strlen($frm_adminname) < 5)
	 $errDisp .="<b>AdminName must be atleast 5 chars</b><br>";

       if(!$frm_adminmail || !CheckEmail($frm_adminmail))
	 $errMsg .=	"Invalid Admin email address<br>";

       if(!$frm_mainurl || $frm_mainurl == "http://")
	 $errMsg .=	"No System Url provided<br>";

       if(!$frm_datapath)
	 $errMsg .=	"No Datapath<br>"; // chg diralso

       if(!$frm_cgidir)
	 $errMsg .=	"No CGIDIR<br>"; // chg diralso

       if(!$frm_imgdir)
	 $errMsg .=	"No Imagedir<br>"; // chg diralso

	 if(!$frm_table_size)
	 $errMsg .=	"No TableSize<br>";
	}

	if($confirm != 1 || $errMsg)
	{

	   if($errMsg)
	   {
		 echo("<P>&nbsp;</P><tr><td>");
      	 errMessage( $errMsg, 'Error', 'error', '80' );
		 echo("<P>&nbsp;</P></td></tr>");
	
		 $frm = "frm_";
		 $def_link = "&nbsp;<a href=install.php?step=2>&lt;&lt; revert default</a> :: ";
	   }
   
?>
<tr bgcolor=#EEEEEE class=tn>
<td>
<br>
<div align=center><b>You just have to set * marked fields now and leave others as it is, can be done later from Admin Panel</b><br>** very neccessary<br><br>Note you can configure all from your Admin Panel, before starting the system for public or start uploading</div>
<p>&nbsp;</p>
<form action=install.php name=config method=post>
<table width="95%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=2 bgcolor="#EEEEEE"> 
      <div align="left" class=tn><?php echo $def_link ?>&nbsp;<a href="http://albums.mgzhome.com/manual/admin.php#conf" target=_blank>open 
        manual for details &gt;&gt;</a></div>
    </td>
  </tr>
  <tr>
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2">&nbsp;</td>
    <td width="60%" bgcolor="#eeeeee" height="2" align=right>
<a href=#bottom><img src="<?php echo ($dirpath.$imgdir); ?>/design/icon_bottom.gif" border=0 alt="go to bottom of page"></a>&nbsp;&nbsp;<img src="<?php echo ($dirpath."images/eng_headers"); ?>/buttons/reset.gif" width=53 height=19 border=0 onclick="document.config.reset();">&nbsp;&nbsp;<input type="image" name="submit" src="<?php echo ($dirpath."images/eng_headers"); ?>/buttons/save.gif" width=53 height=19 border=0 value="save &gt;&gt;">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">License Information</td>
    <td width="60%" class="tn" bgcolor="#EEEEEE"><b><?php echo ($owner_lid." (".$HTTP_HOST.")"); ?></b></td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">sysName : Version</td>
    <td width="60%" class="tn" bgcolor="#CCCCCC"><b>Albinator <?php echo $system_version; ?></b></td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Admin Name *</td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
      <input type="text" name="frm_adminname" maxlength="100" size="40" value="<?php echo ${$frm.adminname}; ?>"> e.g. Eshaan Bisht
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Admin Email *</td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_adminmail" size="40" maxlength="200" value="<?php echo ${$frm.adminmail}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Site Name</td>
    <td width="60%" bgcolor="#DDDDDD"> 
<input type="text" name="frm_sitename" size="40" value="<?php echo ${$frm.sitename}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">By line</td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_buyline" size="40" value="<?php echo ${$frm.buyline}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Site Title</td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_SiteTitle" size="40" value="<?php echo ${$frm.SiteTitle}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">System Url (path to albinator index) (no trailing slash /) put http:// in front *</td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
      <input type="text" name="frm_mainurl" size="40" value="<?php echo ${$frm.mainurl}; ?>"> e.g. http://www.mysite.com/albinator
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Cgi-bin (relative from albinator's root) (no traling /) *</td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_cgidir" size="40" value="<?php echo ${$frm.cgidir}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Albinator's ImageDir (relative) (no traling /) *</td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_imgdir" size="40" value="<?php echo ${$frm.imgdir}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Data Path (no trailing /) **</td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_datapath" size="40" value="<?php echo ${$frm.datapath}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Main TableSize (%)</td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_table_size" maxlength="3" size="40" value="<?php echo ${$frm.table_size}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Main Table Bgcolor</td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_main_bgcolor" size="40" maxlength="7" value="<?php echo ${$frm.main_bgcolor} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">Main Table Bgimage (absolute path) set to albinator's imgdir or custom file</td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_main_bgimage" size="40" value="<?php echo ${$frm.main_bgimage} ?>">
    </td>
  </tr>
  <tr>
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2">&nbsp;</td>
    <td width="60%" bgcolor="#eeeeee" height="2" align=right>
      <input type="hidden" name="confirm" value="1">
      <input type="hidden" name="step" value="2">
<img src="<?php echo ($dirpath."images/eng_headers"); ?>/buttons/reset.gif" width=53 height=19 border=0 onclick="document.config.reset();">&nbsp;&nbsp;<input type="image" name="submit" src="<?php echo ($dirpath."images/eng_headers"); ?>/buttons/save.gif" width=53 height=19 border=0 value="save &gt;&gt;">
    </td>
  </tr>
</table>
</form>
<a name=#bottom></a>
</td></tr>

<?php
	}

	else
	{
       if(!$frm_systemname)
	 $frm_systemname .= "Albinator";

       if(!$frm_bad_user_name)
	 $frm_bad_user_name = "temp,system";
	 else
	 {
		$temparr = explode(",", $frm_bad_user_name);
		if(!in_array("system", $temparr))
		$frm_bad_user_name .= ",system";
		if(!in_array("temp", $temparr))
		$frm_bad_user_name .= ",temp";
	 }

$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('sysstatus', '1')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('sysmsg', '')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('system_version', '$system_version')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('sitename', '$frm_sitename')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('buyline', '$frm_buyline')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('SiteTitle', '$frm_SiteTitle')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('main_bgimage', '$frm_main_bgimage')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('main_bgcolor', '$frm_main_bgcolor')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('table_size', '$frm_table_size')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('p', '$p')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('mainurl', '$frm_mainurl')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('adminname', '$frm_adminname')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('adminmail', '$frm_adminmail')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('imgdir', '$frm_imgdir')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('cgidir', '$frm_cgidir')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('datapath', '$frm_datapath')" );

$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('rootdir', '$rootdir')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('systemname', '$systemname')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('abuse_link', '$abuse_link')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('logout_time', '$logout_time')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('default_space', '$default_space')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('default_album', '$default_album')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('default_remind', '$default_remind')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('default_photo', '0')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('show_min', '$show_min')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('maxshow', '$maxshow')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('makelogs', '$makelogs')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('ecard_days', '$ecard_days')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('unact_days', '$unact_days')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('allowed_size', '$allowed_size')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('allow_types', '$allow_types')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('bad_user_name', '$bad_user_name')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('msgfooter', '$msgfooter')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('site_msg', '$site_msg')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('tbwidth_long', '$tbwidth_long')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('tbheight_long', '$tbheight_long')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('tbwidth_short', '$tbwidth_short')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('tbheight_short', '$tbheight_short')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('exceed_width', '$exceed_width')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('remind_msg_max', '$remind_msg_max')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('dprefs', '$dprefs')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('allow_types_show', '$allow_types_show')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('exceed_height', '$exceed_height')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('ResizeBy', '$ResizeBy')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('buylink', '$buylink')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('shut_logoff', '0')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('forceSize', '0')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('spaceScheme', 'AB')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('dlist_priv_show', '1')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('langCode', 'eng')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('langCodeForce', '1')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('showProcessTime', '1')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('list_langCode', 'eng|English')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('default_uvalid', '0')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('blockmsg', '0')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('blockmsgEar', '0')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('BlockNotifyDay', '5')" );
$result_confup = queryDB( "INSERT INTO $tbl_config VALUES('BlockNotify', '1')" );

	   echo ("<tr bgcolor=#EEEEEE class=tn><td>");
	   $errMsg = "<b>Configuration saved</b>\n";
	   errMessage( $errMsg, 'Congratulations', 'tick', '60' );
?>

      <p align="center" class=tn><b>lets move to the 3rd step now</b></p>
      <p>&nbsp;</p>
      <p align="right" class=tn><a href=install.php?step=3>create your first account &gt;&gt;</a>&nbsp;</p>
    </td>
  </tr>

<?php
	}

   echo($footer_installation);
   exit;
}

else if($step == "3")
{
if($confirm == 1)
{
$username = strtolower($username);
$username = rtrim($username);

#username check
if(in_array($username, $bad_user_name))
{ $errDisp .="<b>This username is not allowed</b><br>"; }

else if (!preg_match ("/^([a-z]|[0-9])*$/", $username))
{ $errDisp .="<b>Invalid username, can contain numbers and chars only</b><br>"; }

$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
$nr = mysql_num_rows( $result );

if($nr || !$username)
{ $errDisp .="<b>Username exists</b><br>"; }
mysql_free_result($result);

$usr_len = strlen($username);
if($usr_len < 4 || $usr_len > 10)
{ $errDisp .="<b>Invalid Username, should be 4 - 10 char long</b><br>"; }


# email check
$result = CheckEmail($email_id);
if(!$result || !$email_id)
{ $errDisp .="<b>Invalid Email</b><br>"; }

$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id'" );
$nr = mysql_num_rows( $result );

if($nr)
{ $errDisp .="<b>Email exists</b><br>";  }
mysql_free_result($result);

if($password != $repassword)
{ $errDisp .="<b>2 passwords dont match</b><br>"; }

$lenpass = strlen($password);
if($lenpass < 6 || $lenpass > 15)
{ $errDisp .="<b>Password should be 6 - 15 char long</b><br>"; }

if(!$uname)
{ $errDisp .="<b>Real Name not specified</b><br>"; }

else
{
$lenname = strlen($uname);

if($lenname < 5)
{ $errDisp .="<b>Real Name must be atleast 5 chars</b><br>"; }
}

if($terms != 1)
{ $errDisp .="<b>You must agree to the Terms & Conditions to register Albinator</b>"; }

}
	if($errDisp || $confirm != 1)
	{ 
      echo($header_installation);

?>
<tr bgcolor=#eeeeee class=tn>
<td>
<?php

	if($errDisp)
	{ 
		$errMsg = "$errDisp";

		echo ("<p>&nbsp;</p>");
	      errMessage( $errMsg, 'Error', 'error', '60');
	}

?>	
<script language="JavaScript">
<!--
function openwin() {
terms=window.open("","terms","status=no,resize=no,toolbar=no,scrollbars=yes,width=500,height=360,maximize=no"); 
}
//--> 
</script>
<p>&nbsp;</p>

<form method=post action=install.php name="Register">
  <table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#003366">
    <tr> 
      <td colspan=2> 
        <div align="right"><img src="<?php echo $dirpath."images/eng_headers/buttons" ?>/registration.gif" width="400" height="32"></div>
      </td>
    </tr>
    <tr> 
      <td colspan=2 height="149"> 
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr> 
            <td> 
              <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">username&nbsp;</font></div>
                  </td>
                  <td width="71%"> 
                    <input type="text" name="username" maxlength=10 value="<?php echo $username ?>"> <span class=ts>must be 4 - 10 char long</span>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td bgcolor="#CCCCCC" width="29%"> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">password&nbsp;</font></div>
                  </td>
                  <td width="71%"> 
                    <input type="password" name="password" maxlength=15> <span class=ts>must be 6 - 15 char long</span>
                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">re-enter 
                      password&nbsp;</font></div>
                  </td>
                  <td width="71%"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <input type="password" name="repassword" maxlength=15>
                    </font></td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">name&nbsp;</font></div>
                  </td>
                  <td width="71%"> 
                    <input type="text" name="uname" value="<?php echo $adminname ?>" maxlength="25">
                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%" valign=top height="5"> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">email&nbsp;</font></div>
                  </td>
                  <td width="71%" height="5"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <input type="text" name="email_id" value="<?php echo $adminmail ?>">
                    </font></td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">country&nbsp;</font></div>
                  </td>
                  <td width="71%"> 
                    <select name="country">
                      <option value="India" selected>INDIA </option>
                      <option value="Albania">Albania </option>
                      <option value="Algeria">Algeria </option>
                      <option value="American Samoa">American Samoa </option>
                      <option value="Andorra">Andorra </option>
                      <option value="Angola">Angola </option>
                      <option value="Anguilla">Anguilla </option>
                      <option value="Antarctica">Antarctica </option>
                      <option value="Antigua and Barbuda">Antigua and Barbuda 
                      </option>
                      <option value="Argentina">Argentina </option>
                      <option value="Armenia">Armenia </option>
                      <option value="Aruba">Aruba </option>
                      <option value="Australia">Australia </option>
                      <option value="Austria">Austria </option>
                      <option value="Azerbaijan">Azerbaijan </option>
                      <option value="Bahamas">Bahamas </option>
                      <option value="Bahrain">Bahrain </option>
                      <option value="Bangladesh">Bangladesh </option>
                      <option value="Barbados">Barbados </option>
                      <option value="Belarus">Belarus </option>
                      <option value="Belgium">Belgium </option>
                      <option value="Belize">Belize </option>
                      <option value="Benin">Benin </option>
                      <option value="Bermuda">Bermuda </option>
                      <option value="Bhutan">Bhutan </option>
                      <option value="Bolivia">Bolivia </option>
                      <option value="Bosnia and Herzegowina">Bosnia and Herzegowina 
                      </option>
                      <option value="Botswana">Botswana </option>
                      <option value="Bouvet Island">Bouvet Island </option>
                      <option value="Brazil">Brazil </option>
                      <option value="British Indian Ocean Territory">British Indian 
                      Ocean Territory </option>
                      <option value="Brunei Darussalam">Brunei Darussalam </option>
                      <option value="Bulgaria">Bulgaria </option>
                      <option value="Burkina Faso">Burkina Faso </option>
                      <option value="Burundi">Burundi </option>
                      <option value="Cambodia">Cambodia </option>
                      <option value="Cameroon">Cameroon </option>
                      <option value="Canada">Canada </option>
                      <option value="Cape Verde">Cape Verde </option>
                      <option value="Cayman Islands">Cayman Islands </option>
                      <option value="Chad">Chad </option>
                      <option value="Chile">Chile </option>
                      <option value="China">China </option>
                      <option value="Christmas Island">Christmas Island </option>
                      <option value="Colombia">Colombia </option>
                      <option value="Comoros">Comoros </option>
                      <option value="Congo">Congo </option>
                      <option value="Cook Islands">Cook Islands </option>
                      <option value="Costa Rica">Costa Rica </option>
                      <option value="Cote Divoire">Cote Divoire </option>
                      <option value="Cuba">Cuba </option>
                      <option value="Cyprus">Cyprus </option>
                      <option value="Czech Republic">Czech Republic </option>
                      <option value="Denmark">Denmark </option>
                      <option value="Djibouti">Djibouti </option>
                      <option value="Dominica">Dominica </option>
                      <option value="Dominican Republic">Dominican Republic </option>
                      <option value="East Timor">East Timor </option>
                      <option value="Ecuador">Ecuador </option>
                      <option value="Egypt">Egypt </option>
                      <option value="El Salvador">El Salvador </option>
                      <option value="Equatorial Guinea">Equatorial Guinea </option>
                      <option value="Eritrea">Eritrea </option>
                      <option value="Estonia">Estonia </option>
                      <option value="Ethiopia">Ethiopia </option>
                      <option value="Faroe Islands">Faroe Islands </option>
                      <option value="Fiji">Fiji </option>
                      <option value="Finland">Finland </option>
                      <option value="France">France </option>
                      <option value="France, Metropolitan">France, Metropolitan 
                      </option>
                      <option value="French Guiana">French Guiana </option>
                      <option value="French Polynesia">French Polynesia </option>
                      <option value="Gabon">Gabon </option>
                      <option value="Gambia">Gambia </option>
                      <option value="Georgia">Georgia </option>
                      <option value="Germany">Germany </option>
                      <option value="Ghana">Ghana </option>
                      <option value="Gibraltar">Gibraltar </option>
                      <option value="Greece">Greece </option>
                      <option value="Greenland">Greenland </option>
                      <option value="Grenada">Grenada </option>
                      <option value="Guadeloupe">Guadeloupe </option>
                      <option value="Guam">Guam </option>
                      <option value="Guatemala">Guatemala </option>
                      <option value="Guinea">Guinea </option>
                      <option value="Guinea-Bissau">Guinea-Bissau </option>
                      <option value="Guyana">Guyana </option>
                      <option value="Haiti">Haiti </option>
                      <option value="Honduras">Honduras </option>
                      <option value="Hong Kong">Hong Kong </option>
                      <option value="Hungary">Hungary </option>
                      <option value="Iceland">Iceland </option>
                      <option value="India">INDIA </option>
                      <option value="Indonesia">Indonesia </option>
                      <option value="Iraq">Iraq </option>
                      <option value="Ireland">Ireland </option>
                      <option value="Israel">Israel </option>
                      <option value="Italy">Italy </option>
                      <option value="Jamaica">Jamaica </option>
                      <option value="Japan">Japan </option>
                      <option value="Jordan">Jordan </option>
                      <option value="Kazakhstan">Kazakhstan </option>
                      <option value="Kenya">Kenya </option>
                      <option value="Kiribati">Kiribati </option>
                      <option value="Korea, Republic of">Korea, Republic of </option>
                      <option value="Kuwait">Kuwait </option>
                      <option value="Kyrgyzstan">Kyrgyzstan </option>
                      <option value="Latvia">Latvia </option>
                      <option value="Lebanon">Lebanon </option>
                      <option value="Lesotho">Lesotho </option>
                      <option value="Liberia">Liberia </option>
                      <option value="Liechtenstein">Liechtenstein </option>
                      <option value="Lithuania">Lithuania </option>
                      <option value="Luxembourg">Luxembourg </option>
                      <option value="Macau">Macau </option>
                      <option value="Madagascar">Madagascar </option>
                      <option value="Malawi">Malawi </option>
                      <option value="Malaysia">Malaysia </option>
                      <option value="Maldives">Maldives </option>
                      <option value="Mali">Mali </option>
                      <option value="Malta">Malta </option>
                      <option value="Marshall Islands">Marshall Islands </option>
                      <option value="Martinique">Martinique </option>
                      <option value="Mauritania">Mauritania </option>
                      <option value="Mauritius">Mauritius </option>
                      <option value="Mayotte">Mayotte </option>
                      <option value="Mexico">Mexico </option>
                      <option value="Monaco">Monaco </option>
                      <option value="Mongolia">Mongolia </option>
                      <option value="Montserrat">Montserrat </option>
                      <option value="Morocco">Morocco </option>
                      <option value="Mozambique">Mozambique </option>
                      <option value="Myanmar">Myanmar </option>
                      <option value="Namibia">Namibia </option>
                      <option value="Nauru">Nauru </option>
                      <option value="Nepal">Nepal </option>
                      <option value="Netherlands">Netherlands </option>
                      <option value="Netherlands Antilles">Netherlands Antilles 
                      </option>
                      <option value="New Caledonia">New Caledonia </option>
                      <option value="New Zealand">New Zealand </option>
                      <option value="Nicaragua">Nicaragua </option>
                      <option value="Niger">Niger </option>
                      <option value="Nigeria">Nigeria </option>
                      <option value="Niue">Niue </option>
                      <option value="Norfolk Island">Norfolk Island </option>
                      <option value="Norway">Norway </option>
                      <option value="Oman">Oman </option>
                      <option value="Pakistan">Pakistan </option>
                      <option value="Palau">Palau </option>
                      <option value="Panama">Panama </option>
                      <option value="Papua New Guinea">Papua New Guinea </option>
                      <option value="Paraguay">Paraguay </option>
                      <option value="Peru">Peru </option>
                      <option value="Philippines">Philippines </option>
                      <option value="Pitcairn">Pitcairn </option>
                      <option value="Poland">Poland </option>
                      <option value="Portugal">Portugal </option>
                      <option value="Puerto Rico">Puerto Rico </option>
                      <option value="Qatar">Qatar </option>
                      <option value="Reunion">Reunion </option>
                      <option value="Romania">Romania </option>
                      <option value="Russian Federation">Russian Federation </option>
                      <option value="Rwanda">Rwanda </option>
                      <option value="Saint Kitts and Nevis">Saint Kitts and Nevis 
                      </option>
                      <option value="Saint Lucia">Saint Lucia </option>
                      <option value="Samoa">Samoa </option>
                      <option value="San Marino">San Marino </option>
                      <option value="Sao Tome and Principe">Sao Tome and Principe 
                      </option>
                      <option value="Saudi Arabia">Saudi Arabia </option>
                      <option value="Senegal">Senegal </option>
                      <option value="Seychelles">Seychelles </option>
                      <option value="Sierra Leone">Sierra Leone </option>
                      <option value="Singapore">Singapore </option>
                      <option value="Slovenia">Slovenia </option>
                      <option value="Solomon Islands">Solomon Islands </option>
                      <option value="Somalia">Somalia </option>
                      <option value="South Africa">South Africa </option>
                      <option value="Spain">Spain </option>
                      <option value="Sri Lanka">Sri Lanka </option>
                      <option value="St. Helena">St. Helena </option>
                      <option value="St. Pierre and Miquelon">St. Pierre and Miquelon 
                      </option>
                      <option value="Sudan">Sudan </option>
                      <option value="Suriname">Suriname </option>
                      <option value="Swaziland">Swaziland </option>
                      <option value="Sweden">Sweden </option>
                      <option value="Switzerland">Switzerland </option>
                      <option value="Syrian Arab Republic">Syrian Arab Republic 
                      </option>
                      <option value="Taiwan">Taiwan </option>
                      <option value="Tajikistan">Tajikistan </option>
                      <option value="Thailand">Thailand </option>
                      <option value="Togo">Togo </option>
                      <option value="Tokelau">Tokelau </option>
                      <option value="Tonga">Tonga </option>
                      <option value="Trinidad and Tobago">Trinidad and Tobago 
                      </option>
                      <option value="Tunisia">Tunisia </option>
                      <option value="Turkey">Turkey </option>
                      <option value="Turkmenistan">Turkmenistan </option>
                      <option value="Turks and Caicos Islands">Turks and Caicos 
                      Islands </option>
                      <option value="Tuvalu">Tuvalu </option>
                      <option value="Uganda">Uganda </option>
                      <option value="Ukraine">Ukraine </option>
                      <option value="United Arab Emirates">United Arab Emirates 
                      </option>
                      <option value="United Kingdom">United Kingdom </option>
                      <option value="United States of America">United States of 
                      America </option>
                      <option value="Uruguay">Uruguay </option>
                      <option value="Uzbekistan">Uzbekistan </option>
                      <option value="Vanuatu">Vanuatu </option>
                      <option value="Venezuela">Venezuela </option>
                      <option value="Viet Nam">Viet Nam </option>
                      <option value="Virgin Islands (British)">Virgin Islands 
                      (British) </option>
                      <option value="Virgin Islands (U.S.)">Virgin Islands (U.S.) 
                      </option>
                      <option value="Wallis and Futuna Islands">Wallis and Futuna 
                      Islands </option>
                      <option value="Western Sahara">Western Sahara </option>
                      <option value="Yemen">Yemen </option>
                      <option value="Yugoslavia">Yugoslavia </option>
                      <option value="Zaire">Zaire </option>
                      <option value="Zambia">Zambia </option>
                      <option value="Zimbabwe">Zimbabwe </option>
                    </select>
                    <input type="hidden" name="confirm" value="1">
                    <input type="hidden" name="step" value="3">
                  </td>
                </tr>
                <tr bgcolor="#DDDDDD"> 
                  <td width="29%"> 
                    <div align="right" class=tn>Login for&nbsp;</div>
                  </td>
                  <td width="71%">
      		<select name='login_time'>
	          <option value="0" selected>till i use</option>
                <option value="1">1 Day</option>
                <option value="7">1 Week</option>
                <option value="30">1 Month</option>
                <option value="365">1 Year</option>
	              </select>
                    </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%" valign=top> 
                    <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></div>
                  </td>
                  <td width="71%"> 
                    <input type="checkbox" name="terms" value="1">
                    <span class=tn>I read & abide to the <a href="../terms3.html" target=_blank class="noundertn">terms 
                    & conditions</a> &lt; please read it</span></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr bgcolor="#003366"> 
      <td colspan=2> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr> 
            <td width="29%"> 
              <div align="right">&nbsp;</div>
            </td>
            <td width="71%"><font size=1>&nbsp;</font>
              <input type="image" name="submit" src="<?php echo $dirpath."images/eng_headers/buttons" ?>/register.gif" width=53 height=19 align=bottom border=0 value="register">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr> 
    <td class=ts align=center><font size=1>By registering means you read & abide 
      to the <a href=<?php echo $dirpath ?>terms3.html target=terms onClick="openwin()">Terms & Conditions</a> 
      of Albinator System</font></td>
  </tr>
</table>
</td></tr>

<?php
echo($footer_installation);

	exit;
	}
	
	else
	{
	$username = strtolower($username);
	$username = strip_tags($username);
	$uname = strip_tags($uname);
	$encpass = md5($password);
	$uname = ucwords($uname);

      $LastTimeDate = date ("l dS of F Y h:i:s A");
      $lastinfo = "$REMOTE_ADDR, $LastTimeDate";

	$adddate = date("Ymd");

	$result = queryDB( "SELECT * FROM $tbl_userinfo" );
	$nr = mysql_num_rows( $result );
	if($nr > 0)
  	{
	echo($header_installation);
	echo("<tr bgcolor=#eeeeee><td><BR>");
	$errMsg = "<br><b>Admin account exists in table, can't continue, if you have problems contact: customer@mgzhome.com</b>\n";
	errMessage( $errMsg, 'Error', 'error' );
	?>

	      <p align="center" class=tn><b>for security sake this script will make only 1 admin account</b></p>
	      <p>&nbsp;</p>
	    </td>
	  </tr>

	<?php
	echo($footer_installation);
	exit;
      }

	// mL("inst");
	$sendmessage = "$HTTP_HOST :: $SCRIPT_NAME";
	$name = "albhome";
	$email = "admin@mgzhome.com";
	$subject = "new installation";
	$mailheader = "From: $name <$email>\nX-Mailer: new installtion\nContent-Type: text/plain";
      error_reporting(0);
	mail("$email","$subject","$sendmessage","$mailheader");
      error_reporting(E_ERROR | E_WARNING);
	//

 	mysql_free_result($result);
 	$now_date  = date("Ymd");

	$result = queryDB( "INSERT INTO $tbl_userinfo VALUES('$username', '$encpass', '$uname', '$email_id', '$country', '0', '$lastinfo', '1', '1', '$dprefs', '0*0|', '$now_date', '0|0|0|0', '0', '0', 'eng', '0', '$login_time')" );

	error_reporting(0);
	if(file_exists("$dirpath$datapath") == 0)
	$direrr = 1;
	else if(!is_writable("$dirpath$datapath"))
	$direrr = 1;
	error_reporting(E_ERROR | E_WARNING);

	if($direrr == 1)
	{
	echo($header_installation);
	echo("<tr bgcolor=#eeeeee><td><BR>");
	$errMsg = "<br><b>User data (datapath) not found or non-writable!</b>\n";
	errMessage( $errMsg, 'Error', 'error' );
	?>

	      <p align="center" class=tn><b>please create the directory under your albinator's directory named <?php echo($datapath) ?> then chmod it 777 and then click below</b></p>
	      <p>&nbsp;</p>
	      <p align="right" class=tn><a href="install.php?step=7&username=<?php echo($username) ?>">next step &gt;&gt;</a>&nbsp;</p>
	    </td>
	  </tr>

	<?php
	echo($footer_installation);
	exit;

	}

	$datapath_dir = "1";
	mkdir ("$dirpath"."$datapath/$username", 0777);

	echo($header_installation);
	echo("<tr bgcolor=#eeeeee><td><BR>");
	$errMsg = "<br><b>Your account has been setup.</b>\n";
	errMessage( $errMsg, 'Congratulations', 'tick' );
?>

      <p align="center" class=tn><b>lets move to the 4th and final step now</b></p>
      <p>&nbsp;</p>
      <p align="right" class=tn><a href="install.php?step=4">final stage &gt;&gt;</a>&nbsp;</p>
    </td>
  </tr>

<?php
	echo($footer_installation);
	}
}

else if($step == "7")
{
	error_reporting(0);
	if(file_exists("$dirpath$datapath") == 0)
	$direrr = 1;
	else if(!is_writable("$dirpath$datapath"))
	$direrr = 1;

	else
	unlink("$dirpath$datapath/check");
	error_reporting(E_ERROR | E_WARNING);

	if($direrr == 1)
	{
		echo($header_installation);
	echo("<tr bgcolor=#eeeeee><td><BR>");
	$errMsg = "<br><b>User data (datapath) not found or non-writable!</b>\n";
	errMessage( $errMsg, 'Error', 'error' );
	?>

	      <p align="center" class=tn><b>please create the directory under your albinator's directory named <?php echo($datapath) ?> then chmod it 777 and then click below</b></p>
	      <p>&nbsp;</p>
	      <p align="right" class=tn><a href="install.php?step=7&username=<?php echo($username) ?>">next step &gt;&gt;</a>&nbsp;</p>
	    </td>
	  </tr>

	<?php
	echo($footer_installation);
	exit;

	}

	mkdir ("$dirpath"."$datapath/$username", 0777);
	echo($header_installation);
	echo("<tr bgcolor=#eeeeee><td><BR>");
	$errMsg = "<br><b>Your account has been setup.</b>\n";
	errMessage( $errMsg, 'Congratulations', 'tick' );
?>

      <p align="center" class=tn><b>lets move to the 4th and final step now</b></p>
      <p>&nbsp;</p>
      <p align="right" class=tn><a href="install.php?step=4">final stage &gt;&gt;</a>&nbsp;</p>
    </td>
  </tr>

<?php
	echo($footer_installation);
}

else if($step == 4)
{
	    echo($header_installation);
	    echo("<tr bgcolor=#eeeeee><td><BR>");
?>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><font size="3">Congratulations 
  all installations steps have been executed successfully.</font></b></font></p>
<p>&nbsp;</p>
<p align="center"><font size="3"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Caution 
  and Note: Please DO NOT run this script again, please delete this script from 
  the server now...</font></b></font></p>
<p align="center">&nbsp;</p>
<p align="center">
<a href=install.php?step=5 class=tn>delete now</a>&nbsp;::&nbsp;<a href="<?php echo $mainurl ?>/login.php"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Login 
  Now &gt;&gt;</font></a>&nbsp;</p>

<?php
	    echo("<BR></td></tr>");
	    echo($footer_installation);
}

else if($step == 5)
{
error_reporting(0);

	    if(!unlink("install.php"))
	    $delmsg = "The installer could not delete the file as there was writing permission error.<br>Please delete file maually. This is very important...";
	    else
	    $delmsg = "Congratulations, file deleted";

error_reporting(E_ERROR | E_WARNING);

	    echo($header_installation);
	    echo("<tr bgcolor=#eeeeee><td><BR>");
?>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><font size="3"><?php echo $delmsg ?></font></b></font></p>
<p>&nbsp;</p>
<p align="center">
<a href="<?php echo $mainurl ?>/login.php"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Login 
  Now &gt;&gt;</font></a>&nbsp;</p>

<?php
	    echo("<BR></td></tr>");
	    echo($footer_installation);
}

else
{
	    echo($header_installation);
	    echo("<tr><td><BR>");
	    $errMsg = "<b>Invalid stepval</b>\n";
	    errMessage( $errMsg, 'error' );
	    echo("<BR></td></tr>");
	    echo($footer_installation);
	    exit;
}


function CheckEmail( $sAddr)
{
    return ( preg_match( "/^(.*[\<|\(]{1})?(([a-z\._\-0-9])+\@([a-z\._\-0-9])+\.([a-z])+)?([\>|\)]{1})?$/i", $sAddr) );
}

function errMessage( $errMsg, $MsgID, $errImg = "error", $errSize = "50" )
{
  global $dirpath, $imgdir;
  
  if(!$imgdir)
  { $imgdir = "images"; }

?>

<br>
<table width="<?php echo $errSize ?>%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="12" bgcolor=#CCCCCC valign="top" align="left"><img src="<?php echo ($dirpath.$imgdir); ?>/form_tl.gif" width="12" height="12"></td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td width="12" bgcolor=#CCCCCC align="right" valign="top">
      <div align="right"><img src="<?php echo ($dirpath.$imgdir); ?>/form_tr.gif" width="12" height="12"></div>
    </td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td bgcolor="#CCCCCC" align="right" valign="top" width="90">
      <div align="center"><img src="<?php echo ($dirpath.$imgdir); ?>/<?php echo ($errImg); ?>.gif" width="60" height="53"></div>
    </td>
    <td bgcolor="#CCCCCC" valign="middle" class=tn><font size="4"><b><?php echo $MsgID; ?></b> 
      <br>
      <font size="2" color="#660000"><?php echo ( $errMsg ); ?></font></font>
    </td>
    <td bgcolor="#CCCCCC" align="right" valign="top" width="12">
     &nbsp;
    </td>
  </tr>
  <tr>
    <td width="12" bgcolor="#CCCCCC" valign="bottom" align="left"><img src="<?php echo ($dirpath.$imgdir); ?>/form_bl.gif" width="12" height="12"></td>
    <td bgcolor="#CCCCCC">&nbsp;</td>
    <td width="12" bgcolor="#CCCCCC" align="right" valign="bottom">
      <div align="right"><img src="<?php echo ($dirpath.$imgdir); ?>/form_br.gif" width="12" height="12"></div>
    </td>
  </tr>
</table>

<?php
}

?>