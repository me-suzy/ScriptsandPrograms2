<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972
   
   CSVParse version 2.04 created by Chris Carroll
   ctcarroll@mindspring.com
   Completely modified version of CSVParse based on Simon Weller's original work
*/

header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-control: No-Cache");

require('./includes/csvconfig.php');

$langtable = $custID.lang;
$zonetable = $custID.zone;
$mastertable = $custID.master;

error_reporting(0);
require('.././admin.php');
require('./includes/csvlang.php');
$flags_file = $droot.'/flags.php';
require($flags_file);

$fct = new FC_SQL;


// see if all has been set up yet; need at least one entry
// in each of the following tables.

$fct->query("select zoneid,zflag1 from $zonetable"); 

$zflag1 = $fct->f('zflag1');
$fct->free_result();

$fct->query("select zoneid from $mastertable");
$fct->next_record();

if( $zid ){
	$zoneid = $zid;
}
if( $lid ){
	$langid = $lid;
}

// get the default zone
if( empty($zoneid) ){
 $zoneid=$fct->f("zoneid");
}

// get the default language
if(empty($langid)){	// get default language from zone record
 $fct->query("select zonedeflid from $zonetable where zoneid=$zoneid");
 $fct->next_record();
 $langid=(int)$fct->f("zonedeflid");
 $fct->free_result();
 $lang_iso='';
}
if( !langid ){
 // make sure we get a value if zonedeflid is not yet set
 $fct->query(
  "select langid from $langtable where langzid=$zoneid order by langid");
 $fct->next_record();
 $langid=(int)$fct->f("langid");
 $fct->free_result();
}

// We can have a mismatch between zone and language if the language id
// is not valid for this zone.  If the language ID does not match the
// current zone ID, take the first one and make it the default.
$fct->query(
 "select count(*) as cnt from $langtable where langzid=$zoneid and langid=$langid");
$fct->next_record();
if( $fct->f("cnt")==0 ){
 $fct->free_result();
 $fct->query(
  "select langid from $langtable where langzid=$zoneid order by langid"); 
 if( $fct->next_record() ){
  $langid=(int)$fct->f("langid");
 }
}
$fct->free_result();


?>
<html>
<head>
<title>CSV-Parse</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#CCCCCC"> 
    <td width="98%" height="11">&nbsp;</td>
    <td colspan="2" height="11" width="2%" bgcolor="#000000">&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td width="98%" height="62" valign="bottom"><img src="images/cvsparselogo.gif" width="242" height="52"></td>
    <td colspan="2" height="231" rowspan="3" bgcolor="#CCCCCC" width="2%"> 
      <p>&nbsp;</p>
      <p>&nbsp; </p>
    </td>
  </tr>
<tr><td width="98%">
<form name="catalog" action="csvindex.php" method=post>
<table width="400">
<tr><td align=center>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>

<b>Select a catalog (zone):</b><br>
<?php 
$fct->query("select count(*) as cnt from $zonetable");
$fct->next_record();
$zt=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select zoneid,zonedescr,usescat from $zonetable order by zoneid"); 
?>
<select name=zid size="<?php echo $zt+1?>"
 onFocus="currfield='zoneid';"
 onChange="document.catalog.action='csvindex.php';submit();">
<option value="">[select a zone]
<?php 
while( $fct->next_record() ){
	$zi=$fct->f("zoneid");
	if(isset($zoneid)){
		if($zi==$zoneid){
			echo "<option value=\"$zi\" selected>";
			$zscat=(int)$fct->f("usescat");
		}else{
			echo "<option value=\"$zi\">";
		}
	}else{
		echo "<option value=\"$zi\">";
	}
	echo substr($fct->f("zonedescr"),0,20) . "\n";
}
$fct->free_result();
?>
</select><br>

<td align=center valign=top>

<b>Select a language:</b><br>
<?php 
$fct->query("select count(*) as cnt from $langtable where langzid=$zoneid");
$fct->next_record();
$zt=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select langid,langdescr from $langtable ".
 "where langzid=$zoneid order by langid"); 
?>
<select name=lid size="<?php echo $zt+1?>"
 onFocus="currfield='langid';"
 onChange="document.catalog.action='csvindex.php';submit();">
<option value="">[select a language]
<?php 
while( $fct->next_record() ){
	$li=$fct->f("langid");
	if(isset($langid)){
		if($li==$langid){
			echo "<option value=\"$li\" selected>";
		}else{
			echo "<option value=\"$li\">";
		}
	}else{
		echo "<option value=\"$li\">";
	}
	echo substr($fct->f("langdescr"),0,20) . "\n";
}
$fct->free_result();
?>
</select><br>
</td><tr>
</table>
</form>
</td><tr>
  <tr valign="top"> 
    <td width="98%"> 
         <p>Current field list and order to be used for this CSVParse action.<br>
    	<?php
		include "./includes/fieldlist.php";
		echo "<b>".$field_order."</b>\n";
		?>
<br>
<FORM ENCTYPE="multipart/form-data" ACTION="csvparse.php" METHOD=POST>
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000">
<p>Choose the CSV file you wish to use with the product adding/updating script:</p> <INPUT NAME="userfile" TYPE="file">
<INPUT TYPE="hidden" NAME="zoneid" VALUE="<?php echo $zoneid?>">
<INPUT TYPE="hidden" NAME="langid" VALUE="<?php echo $langid?>">
<INPUT TYPE="hidden" NAME="prodlid0" VALUE="<?php echo $langid?>">
<INPUT TYPE="hidden" NAME="show" VALUE="">
<INPUT TYPE="hidden" NAME="srch" VALUE="">
<SELECT NAME="act">
<OPTION value="insert">Insert New Products</option>
<OPTION value="update">Update Current Product Information</option>
<OPTION value="delete">Delete Products from Database</option>
</SELECT>

<INPUT TYPE="submit" VALUE="Begin processing">
</form>
      </p>
     </td>
  </tr>
</table>
</body>
</html>
