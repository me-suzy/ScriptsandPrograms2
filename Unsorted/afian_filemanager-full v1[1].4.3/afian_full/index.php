<?

/*
The Afian file manager
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/



$minPHPver = "4.0.1";
$minver = explode(".", $minPHPver);
$curver = explode(".", phpversion());
if (($curver[0] < $minver[0]) || (($curver[0] = $minver[0])  && ($curver[1] < $minver[1])) || (($curver[0] = $minver[0]) && ($curver[1] = $minver[1]) && ($curver[2][0] < $minver[2][0]))) {
	die("Current PHP version: ".phpversion().". Required version: ".$minPHPver.".");
}

require_once("./config.php");
//first run setup
if ($config[install]) {
	header("Location: install/"); /* load setup screen */
	exit();
}
require_once("$config[root_dir]/class/iconlist/iconlist.class.inc");
require_once("$config[root_dir]/functions/functions.php");
require_once("$config[root_dir]/functions/compatibility.php");


set_time_limit("180");


/*************** VIEW STYLE *******************/
if ($view) {
	setcookie ("viewStyle", $view);
} else {
	if ($viewStyle) {
		$view = $viewStyle;
	} else {
		$view = "icons";
	}
}
/*************** END VIEW STYLE *******************/


//set path
$base_dir = $config[base_dir];
if ($dir) {
	$base_dir = $base_dir . $dir;
}

if (!is_dir($base_dir)) {
	$base_dir = $config[base_dir];
	$erMsg = "Folder \"$dir\" doesn't exist.";
	$dir = "";
}



//get dir listing
$d = @dir($base_dir);
if (!$d) {
	$base_dir = $config['base_dir'];
	$d = @dir($base_dir);
	$erMsg = "You are not allowed to access folder \"".$dir."\".";
	$dir = "";
}

//vars prepared for javascript
$jsvar['dir'] = prepUrl(safestr($dir, false));


$splited = split("/", $dir);
$i = sizeof($splited);
for ($j = 0 ; $j < $i-1 ; $j++) {
$upOneDirAddr .= $splited[$j];
	if ($j == $i-2) {
	} else {
	$upOneDirAddr .= "/";
	}
}



if ($act) {
	$act = safefilename($act);
	$actionFile = "actions/".$act.".inc";
	if (file_exists($actionFile)){require_once($actionFile);}
}


//init icon list
$iconList = new iconList(array("width"=>"650px", "height"=>"268px", "listExtra" => "id=\"fileList\""), $checkboxName = "item[]", $emptyListTxt = "Folder empty.");


while ($file = @$d->read()) {
	if ($file != "." && $file != "..") {
			$files['name'][] = $file;
		if (is_dir($base_dir."/".$file)) {
			$files['isdir'][] = "1";
			if ($config[showDirSize]) {
				$files['size'][] = dirsize($base_dir."/".$file);
			} else {
				$files['size'][] = "0";
			}
			$files['ext'][] = "";
			$files['mdate'][] = filemtime($base_dir."/".$files['name'][$i]."");
		} else {
			$files['isdir'][] = "0";
			$files['size'][] = filesize($base_dir."/".$file);
			$files['ext'][] = getExtension($file);
			$files['mdate'][] = filemtime($base_dir."/".$files['name'][$i]."");
		}
	}
}
$d->close();


if (!$sort) {
	$sort = "name";
}

if ($sort == "name") {
@array_multisort($files['isdir'],SORT_DESC, $files['name'], SORT_ASC, SORT_STRING,$files['ext'], SORT_DESC, $files['size'], $files['mdate']);
} elseif ($sort == "size") {
@array_multisort($files['size'], SORT_NUMERIC, SORT_DESC,$files['ext'], SORT_DESC, $files['name'], SORT_ASC, SORT_STRING, $files['isdir'], $files['mdate']);
} elseif ($sort == "type") {
@array_multisort($files['isdir'],SORT_DESC,$files['ext'], SORT_DESC, $files['name'], SORT_ASC, SORT_STRING, $files['size'], $files['mdate']);
} elseif ($sort == "mdate") {
@array_multisort($files['mdate'],SORT_NUMERIC,SORT_DESC,$files['ext'], SORT_DESC, $files['name'], SORT_ASC, SORT_STRING, $files['size'], $files['isdir']);
}

	if ($view == "list") {
		$small = true;
		$thumbview = false;
	} else {
		$small = false;
		$thumbview = true;
	}



for ($i = 0 ; $i < sizeof($files['name']) ; $i++) {

if (!eregi("windows", php_uname())) {
	$perms = getperms($base_dir."/".$files['name'][$i]."");
} else {
	$perms = "n/a";
}

		if ($files['isdir'][$i]) {
			$img = icon($files['name'][$i], $isdir = 1, $returnType = 0, $dirSize = 0, $public = 0,$thumbview, $small);
			$isdir = "yes";
			$divExtra = "onDblClick=\"browse('". prepUrl($files['name'][$i])."')\" style=\"".$extraStyle."\"";
			
			if ($view == "list") {
				$imgExtra = "width=\"16\" height=\"16\"";
			} else {
				$imgExtra = "width=\"32\" height=\"32\"";
			}
			$iszip = "no";

			if ($config[showDirSize]) {
				$dirsize = getFileSize($files['size'][$i]);
			} else {
				$dirsize = "n/a";
			}
$alt = $files['name'][$i]. "\r\nType: Folder\r\nSize: " . $dirsize;

			$listViewDetails = "
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"details\">
<tr>
	<td width=\"60\">$dirsize</td>
	<td width=\"30\">".$perms."</td>
	<td nowrap width=\"110\">". date("d/n/Y H:i", $files['mdate'][$i])."</td>
<td width=\"130\">Folder</td>
</tr>
</table>";

		} else {
		$listViewDetails = "
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"details\">
<tr>
	<td width=\"60\">".getFileSize ($files['size'][$i])."</td>
	<td width=\"30\">".$perms."</td>
	<td nowrap width=\"110\">". date("d/n/Y H:i", $files['mdate'][$i])."</td>
<td nowrap width=\"130\" nowrap>
" . icon($files['name'][$i], $isdir = 0, $returnType = 1, $dirSize = 0, $public = 0). "</td>
</tr>
</table>";



		if ($files['ext'][$i] == "zip") {
			$iszip = "yes";
		} else {
			$iszip = "no";
		}



			$img = icon($files['name'][$i], $isdir = 0, $returnType = 0, $dirSize = 0, $public = 0, $thumbview, $small);
			$isdir = "no";
			$divExtra = "onDblClick=\"down('". prepUrl($files['name'][$i])."')\"";
			$alt = $files['name'][$i]. "\r\nType: " . icon($files['name'][$i], $isdir = 0, $returnType = 1, $dirSize = 0, $public = 0). "\r\nSize: " .getFileSize ($files['size'][$i]);

			if ($view == "list") {
				$imgExtra = "width=\"16\" height=\"16\"";
			} else {
				if (($files['ext'][$i] == "jpeg" || $files['ext'][$i] == "jpg" || $files['ext'][$i] == "png") && extension_loaded("gd") == true) {
					$imgExtra = "width=\"42\" height=\"32\"";
				} else {
					$imgExtra = "width=\"32\" height=\"32\"";
				}
			}


		}
		
		
		$chkId = "FM~CHK~".prepUrl(safestr($files['name'][$i], false));
		$divId =  prepUrl($files['name'][$i], false);
		
		$iconList->setIcon($properties = array(
		"title" => $files['name'][$i], 
		"divtitle" => $divId, 
		"img" => $img, 
		"alt" => $alt, 
		"divExtra" => "selectable=\"yes\" onClick=\"iconListSel('".$i."', true)\"  chkId=\"".$chkId."\" isdir=\"".$isdir."\" iszip=\"".$iszip."\" ".$divExtra."", 
		"imgExtra" => $imgExtra,
		"iconExtra" => "",
		"inputExtra" => "id=\"".$chkId."\"",
		"ancorExtra" => "",
		"inputValue" => $files['name'][$i],
		"listViewDetails" => $listViewDetails
		));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Afian file manager <?echo $dir?></title>
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/style.css">
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/<?if ($view == "list") {?>list.css<?}else{?>iconlist.css<?}?>">

<script type="text/javascript" language="javascript" src="js/dom-drag.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/custom.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/selection.js"></script>


<script language="JavaScript1.2" type="text/javascript">
function upOneDir() {
	document.location.href='?dir=<?echo  prepUrl($upOneDirAddr)?>';
}
	var currentDir = '<?echo  prepUrl($dir)?>';
</script>
<meta name="author" content="Vlad Roman vlad@afian.com">
</head>

<body id="FM~BODY" <?if(sizeof($files['name']) > 0){?> onLoad="iconListInit()"<?}?> style="background-image:url(images/interface/bg-page.gif)">
<!-- FILEMANAGER CREATED BY VLAD ROMAN <vlad@afian.com> -->

<?
//context menus
require_once("$config[root_dir]/includes/contextmenus.php");
//all other floating menus
require_once("$config[root_dir]/includes/menus.php");
?>



<!-- START VISIBLE STUFF -->

<div align="center">
<div style="width: 650px; text-align: right"><img width="122" height="31" title="AFIAN, the coolest file manager for the web - http://www.afian.com" alt="AFIAN logo" src="images/interface/logo.gif" border=0> 
</div>
<br>
<?
//horizontal menu
require_once("$config[root_dir]/includes/basemenu.php");
?>

<!-- <div style="font-size:0px;height:5px;width:646px">&nbsp;</div> -->
<div style="width: 650px; border:solid 1px #C0C0C0;border-bottom:none">
<span style="width: 648px; padding: 0px; padding-bottom: 2px; padding-top: 2px; white-space: nowrap; background-color: #f3f3f3; border: solid 1px #dddddd; border-top-color:white;border-right-width:0px;border-left-width:1px;border-left-color:white;">
<!-- Up button -->
<span style="width: 55px;border:solid 1px silver;"><input class="button" value="up" type="button" onClick="javascript:if(currentDir!=''){document.location.href='?dir=<?echo prepUrl(safeStr($upOneDirAddr, false))?>'}" style="width:55px;"></span>&nbsp;
<!-- Address bar -->
<form action="?"><span style="color:gray;">Location</span> <INPUT value="<?if (!$dir){echo "/";}else{echo safestr($dir);}?>" name="dir" style="font-size:8pt;font-family:Tahoma;width:335px;" onFocus="javascript:writing=true;" onBlur="javascript:writing=false;"> 
<span style="width: 25px;border:solid 1px silver"><input type="submit" value="go" style="width: 25px;" class="button" onClick="javascript:if(this.form.dir.value == '' || (this.form.dir.value == '/' && currentDir=='')){return false;}">
</span> 
<span style="color:gray;padding-left:5px;">Quick select</span> <input type="text" onFocus="javascript:writing=true;" onKeyDown="javascript:selIconByLetter(this.value);" onKeyPress="javascript:selIconByLetter(this.value);" onKeyUp="javascript:selIconByLetter(this.value);" style="width:71px;">
</form>
<div style="font-size:0px;height:2px;width:646px">&nbsp;</div>
</span>
</div>
<form name="filemanform" method="post" action="index.php?dir=<?echo prepUrl(safeStr($dir));?>">
<?
	$iconList->startList();
?>
<script>
if (screen.height >= '768') {
	document.getElementById('fileList').style.height='370px';
}
</script>
<?if(sizeof($files['name']) > 0){?>
<div style="position:relative;top:-15px;left:-15px;font-size:8pt;color:navy;" id="FM~statusBar">Loading. Please wait ...</div>
<?}?>


<?if ($view == "list" && sizeof($files['name']) > 0){?>
<table border="0" cellpadding="5" cellspacing="0" style="width:600px;height:25px;background-color:whitesmoke;margin-left:1px;">
<tr>
	<td width="215"><?if($sort == "name"){?>&raquo; <a>Filename</a><?}else{?><a href="?dir=<?echo prepUrl(safeStr($dir));?>&sort=name">Filename</a><?}?></td>
	<td width="47"><?if($sort == "size"){?>&raquo; <a>Size</a><?}else{?><a href="?dir=<?echo prepUrl(safeStr($dir));?>&sort=size">Size</a><?}?></td>
	<td width="40">Chmod</td>
	<td width="107"><?if($sort == "mdate"){?>&raquo; <a>Modified date</a><?}else{?><a href="?dir=<?echo prepUrl(safeStr($dir));?>&sort=mdate">Modified date</a><?}?></td>
	<td><?if($sort == "type"){?>&raquo; <a>File type</a><?}else{?><a href="?dir=<?echo prepUrl(safeStr($dir));?>&sort=type">File type</a><?}?></td>
</tr>
</table>
<?}?>
<?
$iconList->drawIcons($view);
$iconList->endList();
?>
<input type="hidden" name="act" value="">
<input type="hidden" name="chmode" value="">
<input type="hidden" name="recurschmod" value="no">
</form>
<div style="width: 650px;border: solid 1px #D1D1D1; border-top-width:0px;border-bottom: solid 1px silver;">
<div style="padding: 3px;width: 648px; height: 20px; background-color: #f3f3f3; text-align: left; border: solid 1px #dddddd;border-right-width:0px;border-left-width:0px;border-top-color:white;">
<?
if (strlen($reMsg) > 0) {
?>
<?echo $reMsg;?>
<?}?>
<?
if (strlen($erMsg) > 0) {
?>
<span style="color:red;">
<?echo $erMsg;?>
</span>
<?}?>
</div>
</div>
<div style="width:650px;text-align:right;margin-top:1px;">
<a href="javascript:popup('hotkeys.html', '400', '320', '15', '150')">hotkeys</a> <span style="width:15px;text-align:center;color:silver;">|</span> 
<a href="javascript:popup('about.html', '360', '260', '30', '150')">about</a>
</div>
</div>

<script language="JavaScript1.2" type="text/javascript">
<?if(sizeof($files['name']) > 0){?>
	makeDragable('FM~multiple');
	makeDragable('FM~menuDir');
	makeDragable('FM~menuFile');
	makeDragable('FM~renDiv');
	firstIcon = document.getElementById('<?echo urlencode(safestr($files['name'][0], false))?>');
	lastIcon = document.getElementById('<?echo urlencode(safestr($files['name'][sizeof($files['name'])-1], false))?>');
<?}?>
	makeDragable('FM~clipboard');
	makeDragable('FM~upload');
	makeDragable('FM~mkdir');
	makeDragable('FM~popupDIV');
</script>
</body>
</html>