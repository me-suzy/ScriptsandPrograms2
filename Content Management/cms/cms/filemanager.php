<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: filemanager.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Upload/attache files to the system pages
// ----------------------------------------------------------------------

session_start();
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("security.inc.php") ?>
<?php if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php") ?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php
// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if((!($_SESSION["privilege"] & 4) && !($_SESSION["privilege"] & 8))){
   $db->Close();
   noPrivilege();
}

$Types = array('Images','Media','PDF','PPT','Doc','Excel','ZIP');

$Images = "/(\.gif|\.png|\.jpg|\.jpeg)\$/i";
$Media = "/(\.mov|\.mpg|\.mpeg|\.wav|\.avi|\.mid|\.midi|\.mp3|\.aif|\.au|\.ra|\.rpm|\.ids|\.wmv|\.swf)\$/i";
$PDF = "/\.pdf\$/i";
$PPT = "/(\.ppt|\.pps)\$/i";
$Doc = "/(\.doc|\.rtf|\.txt)\$/i";
$Excel = "/\.xls\$/i";
$ZIP = "/(\.zip|\.gz|\.rar)\$/i";

$incImages = "<img src=\"uploads/' + incFile + '\" border=0 alt=\"' + incFile + '\">";
$incMedia = "<embed src=\"uploads/' + incFile + '\" autoplay=true controller=false loop=true>";
$incPDF = "<a href=\"uploads/' + incFile + '\" target=_blank><img src=cmsimages/pdf.gif width=40 height=41 border=0 alt=\"' + incFile + '\"> ' + incFile + '</a>";
$incPPT = "<a href=\"uploads/' + incFile + '\" target=_blank><img src=cmsimages/ppt.gif width=38 height=35 border=0 alt=\"' + incFile + '\"> ' + incFile + '</a>";
$incDoc = "<a href=\"uploads/' + incFile + '\" target=_blank><img src=cmsimages/doc.gif width=32 height=32 border=0 alt=\"' + incFile + '\"> ' + incFile + '</a>";
$incExcel = "<a href=\"uploads/' + incFile + '\" target=_blank><img src=cmsimages/xls.gif width=41 height=42 border=0 alt=\"' + incFile + '\"> ' + incFile + '</a>";
$incZIP = "<a href=\"uploads/' + incFile + '\" target=_blank><img src=cmsimages/zip.gif width=40 height=40 border=0 alt=\"' + incFile + '\"> ' + incFile + '</a>";

$incAll = "<a href=\"uploads/' + incFile + '\" target=_blank>' + incFile + '</a>";

$Defaults = array('All','Upload');
$Tabs = array_merge($Types,$Defaults);

//upload file if exist
$path = "uploads/";

if (in_array($_GET['tab'], $Tabs)) { $selectedTab = $_GET['tab']; }else{ $selectedTab = 'Upload'; }
if (in_array($_GET['sortby'], array('fName','fSize','fTime'))) { $sortBy = $_GET['sortby']; }else{ $sortBy = 'fTime'; }
if ($_GET['sortdir']=='ASC' || $_GET['sortdir']=='DESC') { $sortDir = $_GET['sortdir']; }else{ $sortDir = 'DESC'; }

if($_POST[upload]){
    if (isset($_FILES['uploadfile'])) {
	if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
	    if ($_FILES['uploadfile']['size']<$maxUpload) {
		if(!preg_match("/(\.exe|\.rpm|\.php|\.com)\$/i", $_FILES['uploadfile']['name'])){
		    $file_dest = $path.$_FILES['uploadfile']['name'];
		    if(!file_exists($file_dest)){
			copy($_FILES['uploadfile']['tmp_name'], $file_dest);
			foreach($Types as $type){ if(preg_match($$type, $file_dest)){ $selectedTab = $type; } }
		    }else{ $err = ERR_FILE_EXIST . " <a href=$file_dest target=_blank>$file_dest</a>"; $selectedTab = 'Upload'; }
		}else{ $err = ERR_FILE_BLOCKED . " (" . $_FILES['uploadfile']['name'] . ")"; }
	    }else{ $err = ERR_FILE_BIG . " $maxUpload Byte"; $selectedTab = 'Upload'; }
	    unlink($_FILES['uploadfile']['tmp_name']);
	}else{ $err = ERR_FILE_FAILD; $selectedTab = 'Upload'; }
    }
}

if($_GET[del]){
   $delfile = preg_replace('/(\\\\|\/|\:|\*|\?|\"|\<|\>|\|)/', '', $_GET[del]);
   @unlink("uploads/".$delfile);
}

function fileslist($dir, $filter, $sortF, $sortD, $selectedTab){
    $fName = array();
    $fSize = array();
    $fTime = array();
    if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
	    if ($file != "index.html" && $file != "." && $file != ".." && preg_match($filter, $file)) {
		$fileinfo = stat("uploads/$file");
		$filesize = round($fileinfo[7]/1024);
		$filemtime = date("d/m/Y h:i", $fileinfo[9]);
		array_push($fName, ucfirst(strtolower($file)));
		array_push($fSize, $filesize);
		array_push($fTime, $filemtime);
	    }
	}
	closedir($handle);
    }

    if($sortD == 'ASC'){ asort($$sortF); $newD = 'DESC'; }else{ arsort($$sortF); $newD = 'ASC'; }

    $html = '<table border="0" align="center" cellpadding="5" cellspacing="2" width="98%" class="bodyBlock"><tr><td>';
    $html .= '<table border="0" align="center" cellpadding="5" cellspacing="2" width="98%">';
    $html .= "<tr><td class=tabSelected width=%50><a href=filemanager.php?tab=$selectedTab&sortby=fName&sortdir=$newD>".F_NAME."</a></td>";
    $html .= "<td class=tabSelected width=%15><a href=filemanager.php?tab=$selectedTab&sortby=fSize&sortdir=$newD>".F_SIZE."</a></td>";
    $html .= "<td class=tabSelected width=%25><a href=filemanager.php?tab=$selectedTab&sortby=fTime&sortdir=$newD>".F_TIME."</a></td>";
    $html .= "<td class=tabSelected width=%10>&nbsp;</td></tr>";

    while (list($key, $val) = each($$sortF)) {
	$file = $fName[$key];
	$filesize = $fSize[$key];
	$filemtime = $fTime[$key];
	$html .= "<tr><td class=bodyBlock><a href=\"uploads/$file\" target=_blank>$file</a></td><td class=bodyBlock>$filesize KB</td><td class=bodyBlock>$filemtime</td>";
	$html .= "<td class=bodyBlock align=center><a href=\"javascript: AddLink('$file');\"><img src=cmsimages/insert_file.png width=24 height=24 border=0 alt=\"\"></a>";
	$html .= " <a href=\"filemanager.php?tab=$selectedTab&sortby=fTime&sortdir=$newD&del=$file\" onClick=\"return confirm('".ENTRY_DEL_MSG."')\"><img src=cmsimages/cancel.png width=24 height=24 border=0 alt=\"\"></a></td></tr>";
    }

    $html .= '</table></td></tr></table>';
    return $html;
}

function drawTabs($arrTabs, $activeTab){
	 $strHtml = '';

	 $strHtml .= '<table border=0 align="center" width=98% cellspacing="0" cellpadding="0"><tr>';
	 for($i=0; $i<count($arrTabs); $i++){
		 $strHtml .= '<td class="tabSpace">&nbsp;</td><td class="';
		 if($arrTabs[$i] == $activeTab){ $strHtml .= "tabSelected"; }else{ $strHtml .= "tabCell"; }
		 $strHtml .= '" align="center"><a href=filemanager.php?tab='.$arrTabs[$i].'>'.$arrTabs[$i].'</a></td>';
	 }
	 $strHtml .= '<td class="tabSpace">&nbsp;</td></tr></table>';

	 return $strHtml;
}

function uploadGUI(){
	 $html = '';
	 $uploadTitle = LBL_UPLOAD;
	 $uploadLabel = CMD_UPLOAD;
	 $html = <<<END
<form  action="filemanager.php" method="post" name="upload" enctype="multipart/form-data">
<table border="0" align="center" cellpadding="5" cellspacing="2" width="100%">
<tr><td colspan=2><img src="cmsimages/upload.gif" width="48" height="48" border="0" alt="$uploadTitle"></td></tr>
<tr><td valign="top" class="pageTitle">$uploadTitle:</td>
<td class="bodyBlock"><input type="file" name="uploadfile" size="60"></td></tr>
<tr><td colspan=2 class="bodyBlock" align="center">
<input type="submit" name="upload" value="$uploadLabel">
</td></tr></table></form>
END;

	return $html;
}
?>
<?php include_once ("header.php") ?>

<script language="JavaScript" type="text/javascript">
<!--
function AddLink(incFile) {
	var html = '<?php $incstr = "inc".$selectedTab; echo $$incstr; ?>';

	window.opener.insertHTML(html);
	window.close();
	return true;
}
//-->
</script>

<table width="98%" border="0" cellspacing="2" cellpadding="5" dir="<?php echo DIRECTION; ?>" align="center">
   <tr>
      <td class="linksBlock" align="<?php if(DIRECTION == "RTL"){ echo "left"; }else{ echo "right"; } ?>">
      <?php
	   foreach($activeLang as $langparam=>$langicon){
	     if($langparam != $lang){
      ?>
	    <a href="filemanager.php?tab=<?php echo $selectedTab; ?>&lang=<?php echo $langparam; ?>"><img src="<?php echo $langicon; ?>" border=0 alt="<? echo $lang; ?>"></a>
      <?php
	     }
	   }
      ?>
      </td>
  </tr>
</table>
<?
echo "<center><b><font color=red>$err</font></b></center>";
echo "<br>".drawTabs($Tabs, $selectedTab);
switch (strtolower($selectedTab)){
       case 'upload':
	    echo uploadGUI();
	    break;
       case 'all':
	    echo fileslist($path,"/\./i",$sortBy,$sortDir,$selectedTab);
	    break;
       default:
	    echo fileslist($path,$$selectedTab,$sortBy,$sortDir,$selectedTab);
	    break;
}
?>
<br><br>
<?php 
      include_once ("footer.php");
      $db->Close();
?>
