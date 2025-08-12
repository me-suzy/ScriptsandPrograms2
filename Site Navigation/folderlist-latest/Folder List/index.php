<?php
///////////////////////////////////////////////////////////
// Developed by Bruno de Oliveira		 				 //
// E-Mail: brunodeoliveira@gmail.com			 		 //
// Read INSTALL.TXT before mail-me for support	 		 //
///////////////////////////////////////////////////////////
// Require PHP 4.x ////////////////////////////////////////
///////////////////////////////////////////////////////////

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

require("classes/common.inc.php");
require("classes/config.inc.php");
$dataobj = new Common;



?>
<html>
<head>
<title><?php echo $ver; ?> - <?php echo $by; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<style type="text/css">
<!--
.list_header_txt {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: <?php echo $list_header_txt; ?>; }
.list_files {font-family: Arial, Helvetica, sans-serif;	font-size: 12px; color: <?php echo $list_files; ?>; }
.list_folders {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: <?php echo $list_folders; ?>; }
.alert {font-family: Arial, Helvetica, sans-serif;	font-size: 16px; color: <?php echo $alert; ?>; font-weight: bold; }
.alltextaround {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: <?php echo $alltextaround; ?>; }
.copyright {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: <?php echo $alltextaround; ?>; }
body { background-color: <?php echo $bg ?> ; }
.style11:link, .style11:active, .style11:visited  {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: <?php echo $link_color ?>; text-decoration: none; }
.style11:hover {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: <?php echo $link_color; ?>; text-decoration: underline; }
-->
</style>
<?php
if(isset($g['dir'])) {
	if(ereg(".*\..*", $g['dir'])) {
		echo "<div align=\"center\" class=\"alert\">You do drugs?!<br><br><a href=\"$url_to_domain\" class=\"style11\">Back</a></div>";
		exit();
	}
	$dir = $path_to_files.$g['dir'];
} else {
	$dir = $path_to_files;
}

if (@is_dir($dir)) {
    if (!@opendir($dir)) {
		echo "<div align=\"center\" class=\"alert\">The folder doesn't exist or <br> you have no access !<br><br><a href=\"$url_to_domain\" class=\"style11\">Back</a></div>";
	} else {
		$dh = opendir($dir);
		while(($file = readdir($dh)) !== false) {
			$info_name[] = $file;
			$info_type[] = filetype($dir ."/". $file);
			$info_size[] = (@filetype($dir ."/". $file) != "dir") ? $dataobj->formatSize(filesize($dir ."/". $file)) : $dataobj->getFolderSize($dir."/".$file);			
		}
	}
} else {
	echo "<div align=\"center\" class=\"alert\">The path isn't a valid readable path !<br><br><a href=\"$url_to_domain\" class=\"style11\">Back</a></div>";
}
?>
<table width="558" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="550" align="right"><table width="550" border="0" cellpadding="0" cellspacing="4" bgcolor="<?php echo $list_bg2; ?>">
      <tr>
        <td align="left" class="style5"><p align="right" class="copyright"> 
              <?php $ver; ?>
              - 
              <?php $by; ?>
            </p></td>
      </tr>
      <tr>
        <td align="left" class="alltextaround">You are in :<br>
&nbsp;&nbsp;&nbsp;| - /&nbsp; <a href="?" class="style11">root</a>
      <?php
		$pathline = explode("/",$g['dir']);
		$tmp = "";
		for($i=0;$i<count($pathline);$i++) {
			$tmp .= $pathline[$i] ."/";
			echo "<a class=\"style11\" href=\"$url_to_domain?dir=".substr($tmp,0,strlen($tmp)-1)."\">$pathline[$i]</a> ";
			if((count($pathline)-1)!=$i) { echo "/ "; }
		}
	  ?></td>
      </tr>
      <tr>
        <td bgcolor="<?php echo $list_bg; ?>"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
            <tr bgcolor="<?php echo $list_header; ?>">
              <td width="21">&nbsp;</td>
              <td width="379"><span class="list_header_txt">Filename</span></td>
              <td width="29" align="center"><span class="list_header_txt">Type</span></td>
              <td width="120" align="center"><span class="list_header_txt">Size</span></td>
            </tr>
            <?php
		for($i=0;$i<count($info_name);$i++) {
			if($info_type[$i] == "dir" && $info_name[$i] != "." && $info_name[$i] != "..") {
	?>
            <tr valign="top" bgcolor="<?php echo $list_bg_folders; ?>" style="cursor: pointer" onClick="window.location='?dir=<?php echo $g['dir']?>/<?php echo $info_name[$i]; ?>'">
              <td><img src="folder.gif" width="18" height="15"></td>
              <td><span class="list_folders">
                <?php echo $info_name[$i]; ?>
              </span></td>
              <td align="center" class="list_folders"><?php echo $info_type[$i]; ?></td>
              <td align="right" class="list_folders"><?php echo $info_size[$i]; ?></td>
            </tr>
            <?php
			}
		}
	?>
            <?php
		for($i=0;$i<count($info_name);$i++) {
			if($info_type[$i] != "dir" && $info_name[$i] != "." && $info_name[$i] != "..") {
	?>
            <tr valign="top" bgcolor="<?php echo $list_bg_files; ?>">
              <td align="center"><a href="<?php echo $url_to_files; ?><?php echo $g['dir']?>/<?php echo $info_name[$i]; ?>"><img src="disk.gif" width="18" height="15" style="cursor: pointer" border="0"></a></td>
              <td><span class="list_files">
                <?php echo $info_name[$i]; ?>
              </span></td>
              <td align="center" class="list_files"><?php echo $info_type[$i]; ?></td>
              <td align="right" class="list_files"><?php echo $info_size[$i]; ?></td>
            </tr>
            <?php
			}
		}
	?>
        </table></td>
      </tr>
      <tr>
        <td align="left" class="style5"><p align="right" class="copyright"><?php $ver; ?> - <?php $by; ?></p></td>
      </tr>
    </table></td>
  </tr>
</table>
</html>
<?php clearstatcache(); // Free used memory ?>