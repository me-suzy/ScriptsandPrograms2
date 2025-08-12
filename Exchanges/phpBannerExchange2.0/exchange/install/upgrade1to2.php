<?
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

include("../lang/install.php");
include("../css.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><? echo "$LANG_title"; ?></title>
<link rel="stylesheet" href="<? echo "../template/css/$css"; ?>" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
<div id="content">
<div class="main">
<center><table border="0" cellpadding="1" width="650" align="center" cellspacing="0">
<tr>
<td>
<table class="tablehead" cellpadding="5" border="0" width="100%" cellspacing="0">
<tr>
<td colspan="2"><center><div class="head">
      <? echo "$LANG_title"; ?></center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse" class="windowbg" width="90%">
  <tr>


<?
	//convert admin..
	if($usemd5=="Y"){
	$get_admin=mysql_query("select * from banneradmin");
	$total_admin=mysql_num_rows($get_admin);
	echo "<b>Converting Admin</b><br>";
	echo "<b>$total_admin</b> Admins<p>";
	mysql_query("ALTER TABLE banneradmin CHANGE adminpass adminpass VARCHAR (200) not null");
	while ($get_admin_rows=mysql_fetch_array($get_admin)){
		$id=$get_admin_rows[id];
		$adminuser=$get_admin_rows[adminuser];
		$adminpass=$get_admin_rows[adminpass];
	echo "$LANG_convert_alter <b>$id</b>..<br>";
	$admencpass=md5($adminpass);
	mysql_query("update banneradmin set adminpass='$admencpass' where id='$id'");
	echo "<p><b>$LANG_convert_done</b><p>";
	}
	}else{
	}
	echo "$LANG_convert_alter <b>$id</b>..<br>";
	$get_accts=mysql_query("select * from banneruser");
	$total_found=mysql_num_rows($get_accts);
	echo "<b>$LANG_convert_accounts</b><br>";
	echo "<b>$total_found</b> $LANG_convert_total<p>";
	while ($get_rows=mysql_fetch_array($get_accts)){
		$id=$get_rows[id];
		$login=$get_rows[login];
		$pass=$get_rows[pass];
		$name=$get_rows[name];
		$email=$get_rows[email];
		$newsletter="1";
	echo "$LANG_convert_alter <b>$id</b>..<br>";
		if($usemd5=="Y"){
			echo "Converting password..<br>";
			$encpass=md5($pass);
		echo "Cleartext Password: <b>$pass</b><br>";
		echo "Encrypted Password: <b>$encpass</b><p>";
	$insert=mysql_query("insert into bannerusernew values('$id','$login','$encpass','$name','$email','$newsletter')");
	}else{
	$insert=mysql_query("insert into bannerusernew values('$id',$login','$pass','$name','$email','$newsletter')");
	}
	}
	echo "<p><b>$LANG_convert_done</b><p>";
	$timestamp=time();
	$get_accts=mysql_query("select * from banneruser");
	while ($get_rows=mysql_fetch_array($get_accts)){
		$uid=$get_rows[id];
		$url=$get_rows[url];
		$exposures=$get_rows[exposures];
		$credits=$get_rows[credits];
		$clicks=$get_rows[clicks];
		$siteclicks=$get_rows[siteclicks];
		$approved=$get_rows[approved];
		$defaultacct=$get_rows[defaultacct];
		$histexposures=$get_rows[exposures];
		$raw=$get_rows[raw];
		$startdate=$timestamp;
	echo "$LANG_convert_stats <b>$uid</b>...";
	$insert=mysql_query("insert into bannerstats values('$uid','$url','1','$exposures','$credits','$clicks','$siteclicks','$approved','$defaultacct','$histexposures','$raw','$startdate')");
	echo "$LANG_convert_done<p>";
	}
	echo "$LANG_convert_drop";
	mysql_query("drop table banneruser");
	echo "$LANG_convert_done<br>";
	echo "$LANG_convert_rename";
	mysql_query("ALTER table bannerusernew rename to banneruser");
	echo "$LANG_convert_done<p>";

	echo "<a href=\"$PHP_SELF?upgrade=5\">Continue</a>";
	}

elseif ($upgrade==5){
echo "phpBannerExchange has been successfully updated! You may now run the <a href=\"install.php\">phpBannerExchange 2.0 installer</a> to install the latest version of phpBannerExchange!";
}
?>