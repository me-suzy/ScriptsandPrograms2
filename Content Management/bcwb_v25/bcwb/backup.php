<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
 
include ("config.inc.php");

$root_path = preg_replace("/backup\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);

// Startup 
include($root_path.'include/startup/debug.inc.php');
include($root_path.'include/startup/common_functions.inc.php');
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
if(! include($root_path_admin.'lang/'.$default_language.'.inc.php') ) die("Can't include ".$root_path.'lang/'.$default_language.'.inc.php');

include($root_path.'include/startup/auth.inc.php');

if($_GET["action"]=="restorebackup" AND $authorized) {
	if($d = @dir($root_path."backup")) {
		while($entry=$d->read()) {
	    	if(strlen($entry)>2 ) { 
	    		if($entry=="structure.inc.php") {
	    		@unlink($root_path."scripts/".$entry);
	    		@copy($root_path."backup/".$entry, $root_path."scripts/".$entry);
	    		@exec("chmod 777 ".$root_path."scripts/".$entry);
	    		$stream .= $entry."<br />";
	    		} elseif($entry!="datetime.dump") {
	    		@unlink($root_path."dcontent/".$entry);
	    		@copy($root_path."backup/".$entry, $root_path."dcontent/".$entry);
	    		@exec("chmod 777 ".$root_path."dcontent/".$entry);
	    		$stream .= $entry."<br />";
	    		}
	    	}
		}
		$d->close();
	}
	
}

if($_GET["action"]=="createbackup" AND $authorized) {
	

	if($d = @dir($root_path."backup")) {
		while($entry=$d->read()) {
	    	if(strlen($entry)>2 ) unlink($root_path."backup/".$entry);
		}
		$d->close();
	}
	
	@mkdir ($root_path."backup", 0755);
	@exec("chmod 777 ".$root_path."backup");

	$d = dir($root_path."dcontent");
	while($entry=$d->read()) {
    	 if(strlen($entry)>2 AND 
	    	( preg_match("/\.xml$/is", $entry) OR preg_match("/\.xsl$/is", $entry))
	    	)  { @copy($root_path."dcontent/".$entry, $root_path."backup/".$entry); $stream .= $entry."<br />"; }
	}
	$d->close();

	@copy($root_path."scripts/structure.inc.php", $root_path."backup/structure.inc.php");
	$stream .= "structure.inc.php<br />";
	$fp = @fopen($root_path."backup/datetime.dump", "w");
	@fwrite($fp, time());
	@fclose($fp);
}





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=$default_charset?>">
    <title>Install</title>
    <LINK REL="stylesheet" TYPE="text/css" HREF="system/default.css.php" TITLE="Style" />
  </head>
  <body bgcolor="#F6F7F7" leftmargin="0" topmargin="0" marginwidth="0">
  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
    		<td background="system/install_bg.gif"><IMG SRC="system/install_title.gif" WIDTH="225" HEIGHT="51" ALT="BCWB Install" /></td>
    	</tr>
<?    	
if(!$authorized) {
?>
    	<tr>
	    	<td align="center">	
	    	<br /><br /><br /><br /><form name="install" method="POST">
	    	<table border="0" cellspacing="0" cellpadding="2" class="install">
	    	    <tr>
	    			<td >	
	    			<h1><?=$lang["Status"]?></h1>
	    			</td>
	    		</tr>
	    	    <tr>
	    			<td align="left" class="install">	
	    			<?=$lang["Need_authorization"]?><br />
	    			
	    			</td>
	    		</tr>
    				<tr>
	    			<td >	
	    			<IMG SRC="system/install_line.gif" WIDTH="381" HEIGHT="36" ALT="" />
	    			</td>
	    		</tr>	    		
	    		</table>
	    	</form>
	    	</td>
    	</tr>
<?

} else {
?>
    	<tr>
	    	<td align="center">	
	    	<br /><br /><br /><br /><form name="install" method="POST">
	    	<table border="0" cellspacing="0" cellpadding="2" class="install">
	    	    <tr>
	    			<td>	
	    			<h1><?=$lang["Create_backup"]?></h1>
	    			</td>
	    		</tr>
   		
	    	    <tr>
	    			<td align="center">	
	    				<br />
	    		    	<table border="0" cellspacing="0" cellpadding="0">
	    		    		<tr>
				    			<td style="cursor:hand" onclick="location.href='backup.php?action=createbackup'"><IMG SRC="system/btn101_l.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    			<td align="center" class="install" style="cursor:hand" background="system/btn101_c.gif"><a class="install" href="backup.php?action=createbackup"><?=$lang["Run"]?></a></td>
				    			<td style="cursor:hand" onclick="location.href='backup.php?action=createbackup'"><IMG SRC="system/btn101_r.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    		</tr>
				    	</table>
				    	
	    			
	    			</td>
	    		</tr>
	    		
	    	    <tr>
	    			<td colspan="3">	
	    			<IMG SRC="system/install_line.gif" WIDTH="381" HEIGHT="36" ALT="" />
	    			</td>
	    		</tr>
	    		
<?
if(file_exists($root_path."backup/datetime.dump"))
	$timedump = trim(join("", file($root_path."backup/datetime.dump")));
if($timedump) {
?>	    		
	    	    <tr>
	    			<td colspan="3">	
	    			<h1><?=$lang["Restore_backup"]?></h1>
	    			</td>
	    		</tr>
	    	    <tr>
	    			<td>	
	    			<?=$lang["Archive"]?>: <span class="bold"><?=date("Y-m-d H:i:s", $timedump)?></span>
	    			</td>
	    		</tr>
	    		

	    	    <tr>
	    			<td align="center">	
	    				<br />
	    		    	<table border="0" cellspacing="0" cellpadding="0">
	    		    		<tr>
				    			<td style="cursor:hand" onclick="location.href='backup.php?action=restorebackup'"><IMG SRC="system/btn101_l.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    			<td align="center" class="install" style="cursor:hand" background="system/btn101_c.gif"><a class="install" href="backup.php?action=restorebackup"><?=$lang["Run"]?></a></td>
				    			<td style="cursor:hand" onclick="location.href='backup.php?action=restorebackup'"><IMG SRC="system/btn101_r.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    		</tr>
				    	</table>				    	
	    			
	    			</td>
	    		</tr>
	    			    		
	    	    <tr>
	    			<td colspan="3">	
	    			<IMG SRC="system/install_line.gif" WIDTH="381" HEIGHT="36" ALT="" />
	    			</td>
	    		</tr>
<? } 

if($stream) {
?>
	    	    <tr>
	    			<td colspan="3">	
	    			<h1><?=$lang["Moved"]?></h1>
	    			</td>
	    		</tr>
	    	    <tr>
	    			<td class="bold">	
	    			<?=$stream?>
	    			</td>
	    		</tr>

<?
}

?>

	    		</table>
	    	</form>
	    	</td>
    	</tr>   		
<?
}
?>	    		
	    		
    </table>
  </body>
</html>