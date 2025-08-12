<?
##########################################
### 
### CzarNews v1.13
### Made by: Czaries  [czaries@czaries.net]
### http://www.czaries.net/scripts/
### for more scripts and updates.
###
### Current Version, do not alter or script will not install/upgrade correctly
$cnver = "1.14";
##########################################

if(empty($tpath) && $_REQUEST['tpath'] != $tpath) {
	// Automatically get $tpath to avoid possible security holes
	$tpath = realpath(__FILE__);
	$tpath = substr($tpath,0,strrpos($tpath,DIRECTORY_SEPARATOR)+1);
}

if(file_exists($tpath . "cn_dbdefs.php")) {
	// Include MySQL Definitions
	require_once($tpath . "cn_dbdefs.php");
} else {
	if(file_exists("" . $tpath . "install.php")) {
		include_once($tpath . "install.php");
		exit;
	} else {
		print E("Installation file not found.  Please upload 'install.php' to run the necessary upgrades for your new version.");
	}
}

// Directory the images are in
$imgdir = "images/";

// Make the default database connection
$link = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Unable to connect to MySQL server.<br>" . mysql_error()); 
mysql_select_db($dbname, $link) or die ("Could Not Connect to Selected Database.<br>" . mysql_error());

// Table prefix name
$t_prefix = "cn";
// Table names
$t_news = $t_prefix . "_news";
$t_user = $t_prefix . "_users";
$t_cats = $t_prefix . "_cats";
$t_conf = $t_prefix . "_config";
$t_coms = $t_prefix . "_comments";
$t_words = $t_prefix . "_words";
$t_img = $t_prefix . "_images";


##############################################
### Useful Functions and other configurations.           ###
### There is no need to edit anything below this line. ###
##############################################

// Current server time, in milliseconds - do not alter
$now = strtotime('now');

// Current root path
$current_root = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'], "/")) . "/";

// Retrieve Script Configuration Settings
$q[sets] = mysql_query("SELECT * FROM $t_conf LIMIT 1", $link) or E("Couldn't retrieve configuration settings:<br>" . mysql_error());
$set = mysql_fetch_array($q[sets], MYSQL_ASSOC);

if($cnver > $set[version]) {
	if(file_exists("" . $tpath . "install.php")) {
		include_once($tpath . "install.php");
		exit;
	} else {
		print E("Installation file not found.  Please upload 'install.php' to run the necessary upgrades for your new version.");
	}
}

### Timezones Array ###
$tzones = Array(
			"-12" => "GMT - 12 Hours",
			"-11" => "GMT - 11 Hours",
			"-10" => "GMT - 10 Hours",
			"-9" => "GMT - 9 Hours",
			"-8" => "GMT - 8 Hours",
			"-7" => "GMT - 7 Hours",
			"-6" => "GMT - 6 Hours",
			"-5" => "GMT - 5 Hours",
			"-4" => "GMT - 4 Hours",
			"-3.5" => "GMT - 3.5 Hours",
			"-3" => "GMT - 3 Hours",
			"-2" => "GMT - 2 Hours",
			"-1" => "GMT - 1 Hour",
			"0" => "GMT",
			"1" => "GMT + 1 Hour",
			"2" => "GMT + 2 Hours",
			"3" => "GMT + 3 Hours",
			"3.5" => "GMT + 3.5 Hours",
			"4" => "GMT + 4 Hours",
			"4.5" => "GMT + 4.5 Hours",
			"5" => "GMT + 5 Hours",
			"5.5" => "GMT + 5.5 Hours",
			"6" => "GMT + 6 Hours",
			"6.5" => "GMT + 6.5 Hours",
			"7" => "GMT + 7 Hours",
			"8" => "GMT + 8 Hours",
			"9" => "GMT + 9 Hours",
			"9.5" => "GMT + 9.5 Hours",
			"10" => "GMT + 10 Hours",
			"11" => "GMT + 11 Hours",
			"12" => "GMT + 12 Hours",
			"13" => "GMT + 13 Hours"
			);

### Success Message ###
function S($msg) {
	?>
	<center>
	<table border="0" cellpadding="1" cellspacing="1" width="500" bgcolor="#FFFFFF" class="menu"><tr>
	<td class="head">S U C C E S S</td>
	</tr><tr>
	<td class="value">
	<blockquote><br>
	
	<? print $msg; ?>
	
	<br><br>
	</blockquote>
	</td>
	</tr><tr>
	<td class="head" colspan="2"><center>
	<input type="button" value=" Okay " onClick="javascript:location.href='<? print $_SERVER['PHP_SELF']; ?>'">&nbsp;&nbsp;
	<input type="button" value=" Main " onClick="javascript:location.href='index.php'">
	</center></td></tr>
	</table>
	</center>
	<br><br>
	<?
	include("cn_foot.php");
	exit;
}

### Error Message ###
function E($msg) {
	?>
	<center>
	<table border="0" cellpadding="1" cellspacing="1" width="500" bgcolor="#FFFFFF" class="menu"><tr>
	<td class="head">E R R O R</td>
	</tr><tr>
	<td class="value">
	<blockquote><br>
	
	<? print $msg; ?>
	
	<br><br>
	</blockquote>
	</td>
	</tr><tr>
	<td class="head" colspan="2"><center>
	<input type="button" value=" &lt; Back" onClick="javascript:history.go(-1)">&nbsp;&nbsp;
	<input type="button" value=" Main " onClick="javascript:location.href='index.php'">
	</center></td></tr>
	</table>
	</center>
	<br><br>
	<?
	include("cn_foot.php");
	exit;
}


### Get info from a user record with their ID ###
function cn_getinfo($id,$field="user",$table="") {
	GLOBAL $link, $t_user;
	
	if(empty($table)) { $table = "$t_user"; }
	
	$q[theinfo] = mysql_query("SELECT $field FROM $table WHERE id='$id'", $link);
	$inf = mysql_fetch_array($q[theinfo], MYSQL_ASSOC);
	return "$inf[$field]";
}

### Drop-down user select box ###
function cn_userBox($boxname,$boxvalue="") {
	global $link, $t_user, $useri;
	if (empty($boxvalue)) { $boxvalue = $useri[id]; }
	
	print "<select name=\"$boxname\">\n";
	
	$q[uinfo] = mysql_query("SELECT id,user FROM $t_user ORDER BY user ASC", $link);
	while($getusr = mysql_fetch_array($q[uinfo], MYSQL_ASSOC)) {
		if($boxvalue == $getusr[id]) { $opsel = " selected"; } else { $opsel = ""; }
		print "<option value=\"$getusr[id]\"$opsel>$getusr[user]</option>\n";
	}
	
	print "</select>\n";
}

### Drop-down category select box ###
function cn_catBox($boxname="cat",$cat="",$jump="no",$mode="admin") {
	global $t_cats, $link, $useri;
	
	$q[box] = mysql_query("SELECT * FROM $t_cats ORDER BY name ASC", $link) or E("Couldn't select categories:<br>" . mysql_error());
	$catnum = mysql_num_rows($q[box]);
	if($catnum == "0" && $mode == "admin") {
		print "[ No categories ]&nbsp;&nbsp;&nbsp;(You can still post without them)";
	} else {
	if($jump == "yes") {
	?>
	<script language="javascript">
	<!-- 
	function gone() {
	location=document.theform.<?=$boxname?>.options[document.theform.<?=$boxname?>.selectedIndex].value
	}
	//-->
	</script><? } ?>
	<? if($jump == "yes") { ?><form name="theform" action="" method="post"><? } ?>	
	<select name="<?=$boxname?>"<? if($jump == "yes") { ?> onChange="gone()" <? } ?>>
	<?
	if($useri[categories] == "all" || $mode != "admin") {
	// Print "View All" selection for view all categories [v1.12]
	if($mode != "admin") { print "<option value=\"$PHP_SELF?\">View All</option>\n"; }
	while($cv = mysql_fetch_array($q[box], MYSQL_ASSOC)) {
		?>
		<option value="<? if($jump == "yes") { ?><?=$PHP_SELF?>?<?=$boxname?>=<? } ?><? print $cv[id]; ?>"<?if($cat==$cv[id]){print " SELECTED";}?>><? print stripslashes($cv[name]); ?></option>
		<? 
	}
	if($jump == "yes") { ?></form><? }
	} else {
		### Assemble the categories field into an array for selection ###
		$cats = explode(", ", $useri[categories]);
		foreach($cats as $cv) {
			$q[cat] = mysql_query("SELECT * FROM $t_cats WHERE id = '$cv'", $link) or E("Couldn't select category with id:<br>" . mysql_error());
			$cva = mysql_fetch_array($q[cat], MYSQL_ASSOC);
			?>
			<option value="<? print $cv; ?>"<?if($cat==$cv){print " SELECTED";}?>><? print stripslashes($cva[name]); ?>
			<?
	}
	}
	?>
	</select>
	<? if($jump == "yes") { ?>
	<input type="button" name="go" value="Go" onClick="gone()">
	<?
	}
	}
}

// String shortener
function cn_cutstr($string,$endlength="30",$end="...") {
	$strlen = strlen($string);
	if ($strlen > $endlength) {
		$trim = $endlength-$strlen;
		$string = substr("$string", 0, $trim); 
		$string .= $end;
	}
	return $string;
}

### Timezone Formatting ###
/*
$current should be the timezone on the
current machine, $target is the timezone
you want to calculate.  Both should be in
hours.  For example, GMT -05:00 should
be -5.  GMT +12:00 should be 12.  You can
use the returned timestamp with the date
function.

ex: echo date('r', zonechange(-5, 10));
would echo the date for a +10:00
timezone on a machine in -05:00.
*/

function cn_zonechange($current, $target, $date="") {
	$current = -1 * $current;
	if($date == "") {
		$zonedate = mktime(date('G'), date('i'), date('s'), date('n'), date('j'), date('Y'), 1) + (($current + $target) * 3600);
	} else {
		$zonedate = mktime(date('G', $date), date('i', $date), date('s', $date), date('n', $date), date('j', $date), date('Y', $date), 1) + (($current + $target) * 3600);
	}
	return $zonedate;
}

### Search & Replace with highlighted same text (case-insensitive) ###
// Added v1.12 
function cn_highlight($x,$var) {
   if ($var != "") {
       $xtemp = "";
       $i=0;
       while($i<strlen($x)){
           if((($i + strlen($var)) <= strlen($x)) && (strcasecmp($var, substr($x, $i, strlen($var))) == 0)) {
                   $xtemp .= "<span style=\"background-color:yellow; color: black\">" . substr($x, $i , strlen($var)) . "</span>"; // replace search with HTML
                   $i += strlen($var);
           }
           else {
               $xtemp .= $x{$i};
               $i++;
           }
       }
       $x = $xtemp;
   }
   return $x;
}

### Checks if the email address entered is valid ###
// Added v1.12 
function cn_isemail($email) {
	return eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*"."@([a-z0-9]+([\.-][a-z0-9]+))*$",$email);
}

// Decodes and codes HTML strings
// (turns HTML entities into text, and vice versa)
// Added v1.14
function cn_htmltrans($string,$type=text) {
	$trans = get_html_translation_table(HTML_ENTITIES);
	
	// Return "text" string - decode all HTML chars
	if($type == "text") {
		$string = addslashes($string);
		return strtr($string, $trans);
	
	// Return "HTML" string - convert text abbreviations into HTML code
	} else {
		$trans = array_flip($trans);
		$string = stripslashes($string);
		return strtr($string, $trans);
	}
}

### Filename Formatting Function ###
// Added v1.14
function cn_FileFormat($file_name) {
	// Remove spaces in filename
	$file_name = str_replace(" ","_",$file_name);
	$file_name = str_replace("%20","_",$file_name);
	
	return $file_name;
}

### File Delete Function ###
// Added v1.14
function cn_FileDelete($file_name) {
	global $current_root,$set;
	
	// Get file name without the extension
	$file_name_nx = substr($file_name,0,strrpos($file_name,"."));
	// Get file extension, make all extensions lower case for match
	$fileext = strtolower(substr($file_name,strrpos($file_name,".")+1));
	
	$thumb_name = substr($file_name,0,strrpos($file_name,".")) . "_thumb.jpg";

	if(file_exists("$current_root$set[img_dir]$file_name")) {
		@unlink("$current_root$set[img_dir]$file_name");
		@unlink("$current_root$set[img_dir]$thumb_name");
		return TRUE;
	} else {
		return FALSE;
	}
}

### File Upload Function ###
// Added v1.14
function cn_FileUpload($fieldname,$overwrite="n") {
	global $set;
	$allow_types="bmp,gif,jpg,jpeg,png";
	
	// Temporary...
	$upload_dir = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/')) . "/" . $set['img_dir'];
	// End Temp

	// Define varibles from files array
	$temp_name = $_FILES[$fieldname]['tmp_name'];
	$file_name = $_FILES[$fieldname]['name']; 
	$file_type = $_FILES[$fieldname]['type']; 
	$file_size = $_FILES[$fieldname]['size']; 
	$result    = $_FILES[$fieldname]['error'];
	
	// Get file name without the extension
	$file_name_nx = substr($file_name,0,strrpos($file_name,"."));
	// Get file extension, make all extensions lower case for match
	$fileext = strtolower(substr($file_name,strrpos($file_name,".")+1));
	$allow_types = strtolower($allow_types);
	// Remove all extra spacing, then split into an array
	$allow_types = str_replace(" ","",$allow_types);
	$allowed_types = explode(",",$allow_types);
	
	// File overwrite prevention
	if($overwrite == "y") {
		cn_FileDelete($file_name);
	} else {
		$fi = 1;
		$file_name_nex = $file_name_nx;
		while(file_exists("$upload_dir$file_name_nex.$fileext")) {
			$file_name_nex = $file_name_nx . "_" .$fi;
			$fi++;
		}
		$file_name = $file_name_nex . "." . $fileext;
	}
	$file_path = $upload_dir.$file_name;
	
	// File size check
	if($file_size > $set[img_maxsize]) {
		print E("The file you uploaded is too big.  Please select a smaller file.");
	} // File type check
	elseif(!in_array($fileext,$allowed_types)) {
		print E("The type of file you uploaded is not allowed");
	} else {
		move_uploaded_file($temp_name,$file_path);
	}
	
	return $file_path;
}

### Global function for displaying an image ###
// Added v1.14
function cn_showImage($filename,$align) {
	global $set;

	return "<img src=\"$set[scripturl]$set[img_dir]$filename\" align=\"$align\" border=\"0\" />";
}

### Create image thumbnail from larger picture ###
// Added v1.14
function cn_ImageThumbnail($image_path,$maxw="150",$maxh="150") {
	
	# Load image
	$img = null;
	$ext = strtolower(end(explode('.', $image_path)));
	if ($ext == 'jpg' || $ext == 'jpeg') {
		$img = @imagecreatefromjpeg($image_path);
	} else if ($ext == 'png' || $ext == 'bmp') {
		$img = @imagecreatefrompng($image_path);
	} else if ($ext == 'gif') {
		// For older versions of GD (GD < 2.0)
		if(function_exists('imagecreatefromgif')) {
			$img = @imagecreatefromgif($image_path);
		} else {
			$img = @imagecreatefrompng($image_path);
		}
	}
	
	# If an image was successfully loaded, test the image for size
	if ($img) {
	
		# Get image size and scale ratio
		$width = imagesx($img);
		$height = imagesy($img);
		$scale = min($maxw/$width, $maxh/$height);
	
		# If the image is larger than the max shrink it
		if ($scale < 1) {
			$new_width = floor($scale*$width);
			$new_height = floor($scale*$height);
	
			# Create a new temporary image
			$tmp_img = imagecreatetruecolor($new_width, $new_height);
	
			# Copy and resize old image into new image
			imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagedestroy($img);
			$img = $tmp_img;
			
			$thumb_path = substr($image_path,0,strrpos($image_path,".")) . "_thumb.jpg";
			//if(!file_exists($thumb_path)) {
				// Save thumbnail to file
				imagejpeg($img,$thumb_path,80);
			//}
			
			return $thumb_path;
		}
	}
}

function cn_urlpost($url,$data) {
	global $set;
	// parsing the given URL
	$urlinfo=parse_url($url);
	$refpage = $set['siteurl'];
	
	// making string from $data
	foreach($data as $key=>$value) {
		$values[]="$key=".urlencode($value);
	}
	$data_string=implode("&",$values);
	
	// Find out which port is needed - if not given use standard (=80)
	if(!isset($urlinfo['port'])) { $urlinfo['port']=80; }
	
	// building POST-request:
	$request.="POST ".$urlinfo['path']." HTTP/1.1\n";
	$request.="Host: ".$urlinfo['host']."\n";
	$request.="Referer: $referer\n";
	$request.="Content-type: application/x-www-form-urlencoded\n";
	$request.="Content-length: ".strlen($data_string)."\n";
	$request.="Connection: close\n";
	$request.="\n";
	$request.=$data_string."\n";
	
	$fp = @fsockopen($urlinfo['host'],$urlinfo['port']);
	$fpp = @fputs($fp, $request);
	while(!feof($fp)) {
		$result .= fgets($fp, 128);
	}
	fclose($fp);
	if(!$fp || !$fpp || !$result) { $result = FALSE; }
	
	return $result;
}

// $output1=HTTP_Post("http://www.server1.com/script1.php",$_POST);

### Build Query String ###
// Allows you to easily change values in a query string without having to type out the full string.
// Also allows more portability because it accounts for pre-existing query string contents and does
// not overwrite them, but instead adds to them, and changes values where the name matches.
// Input is an array, so almost an unlimited amount of values can be added
//
// Input example:     cn_buildQueryString(array('a'=>'5','c'=>'1'))
// Output example:    ?a=5&c=1
//
function cn_buildQueryString($qskeys="") {
	$qs = "";
	
	if(is_array($qskeys)) {
		foreach($qskeys as $k => $v) {
			if(!empty($k) && !array_key_exists($k,$_GET)) {
				$_GET[$k] = "$v";
			}
		}
		
		foreach($_GET as $k => $v) {
			if(isset($qskeys[$k])) {
				$v = $qskeys[$k];
			}
			
			if(empty($qs)) {
				if(!empty($v)) {
					$qs = "?" . $k . "=" . $v;
				}
			} else {
				if(!empty($v)) {
					$qs = $qs . "&amp;" . $k . "=" . $v;
				}
			}
		}
	}
	return $qs;
}

### Get superglobal values ###
// Added v1.13 
$op = $_REQUEST['op'];
$go = $_POST['go'];
$id = $_REQUEST['id'];
$mode = $_REQUEST['mode'];
$pg = $_GET['pg'];
$qs = $_SERVER['QUERY_STRING'];
?>