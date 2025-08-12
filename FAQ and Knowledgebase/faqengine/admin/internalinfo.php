<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_internalinfo;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_phpversion?>:</td>
<td><?php echo phpversion()?></td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_hasfileerrors?>:</td>
<?php
echo "<td>";
if($has_file_errors)
	echo $l_yes;
else
	echo $l_no;
echo " (";
if(phpversion() >= '4.2.0')
	echo $l_yes;
else
	echo $l_no;
echo ")</td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%" valign="top"><?php echo $l_uploadavail?>:</td>
<?php
echo "<td>";
if($upload_avail)
	echo $l_yes;
else
	echo $l_no;
echo " (";
$hasupload=true;
$upload_ini=@ini_get('file_uploads');
if(( $upload_ini == '0') || !$upload_ini)
	$hasupload=false;
if(strtolower($upload_ini) == 'off')
	$hasupload=false;
if(phpversion() == '4.0.4pl1')
	$hasupload=false;
if((phpversion() < '4.0.3') && (@ini_get('open_basedir') != ''))
	$hasupload=false;
if($hasupload)
	echo $l_yes;
else
	echo $l_no;
echo ")";
if($upload_avail && $hasupload)
	echo "<br>".$l_maxuploadsize.": ".$maxfilesize." Bytes";
echo "</td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_insafemode?>:</td>
<?php
echo "<td>";
if($insafemode)
	echo $l_yes;
else
	echo $l_no;
echo "</td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%">$new_global_handling:</td>
<?php
echo "<td>";
if($new_global_handling)
	echo $l_yes;
else
	echo $l_no;
echo " (";
if(phpversion() >= '4.1.0')
	echo $l_yes;
else
	echo $l_no;
echo ")</td></tr>";
?>
<tr class="displayrow"><td align="right" width="30%">Cookie domain:</td>
<td>
<?php
echo $cookiedomain;
if($new_global_handling)
	$myhost=$_SERVER["HTTP_HOST"];
else
	$myhost=$HTTP_SERVER_VARS["HTTP_HOST"];
$portpos=strpos($myhost,":");
if($portpos>0)
	$myhost=substr($myhost,0,$portpos);
$ckdom = $myhost;
if(strpos($ckdom,".")<1)
	$ckdom="";
else
{
	$num_points=substr_count($myhost,".");
	if($num_points<2)
		$ckdom=".".$myhost;
}
echo " (".$ckdom.")";
?>
</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_attachstore?>:</td><td>
<?php
if($attach_in_fs)
	echo $l_filesystem;
else
	echo $l_database;
?>
</td></tr>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_scriptversion?>:</td>
<td><?php echo $faqeversion?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_directories?></b></td></tr>
<?php
if($attach_in_fs)
{
	echo "<tr class=\"displayrow\" valign=\"top\"><td align=\"right\" width=\"30%\">".$l_attachements.":</td><td>";
	echo $path_attach."<br>";
	if(!file_exists($path_attach))
		echo $l_notexisting;
	else
	{
		echo $l_existing."<br>";
		if(!is_writeable($path_attach))
			echo $l_notwriteable;
		else
			echo $l_writeable;
	}
}
if(isset($path_tempdir))
{
	echo "<tr class=\"displayrow\" valign=\"top\"><td align=\"right\" width=\"30%\">".$l_tempdir.":</td><td>";
	echo $path_tempdir."<br>";
	if(!file_exists($path_tempdir))
		echo $l_notexisting;
	else
	{
		echo $l_existing."<br>";
		if(!is_writeable($path_tempdir))
			echo $l_notwriteable;
		else
			echo $l_writeable;
	}
}
?>
<tr class="displayrow" valign="top"><td align="right" width="30%"><?php echo $l_logfiles?>:</td><td>
<?php
echo $path_logfiles."<br>";
if(!file_exists($path_logfiles))
	echo $l_notexisting;
else
{
	echo $l_existing."<br>";
	if(!is_writeable($path_logfiles))
		echo $l_notwriteable;
	else
		echo $l_writeable;
}
?>
</td></tr>
<tr class="displayrow" valign="top"><td align="right" width="30%"><?php echo $l_inlinegfx2?>:</td><td>
<?php
echo $path_gfx."<br>";
if(!file_exists($path_gfx))
	echo $l_notexisting;
else
{
	echo $l_existing."<br>";
	if(!is_writeable($path_gfx))
		echo $l_notwriteable;
	else
		echo $l_writeable;
}
?>
</td></tr>
</table></tr></td></table>
<?php
include('./trailer.php');
?>
