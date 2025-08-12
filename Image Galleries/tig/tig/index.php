<?

include('config.php');
@$page = $_GET["page"];
$ver = "v0.08";

// ++ //

if (!$include_mode)
	echo "<head>\n";

if ($use_own_colors) {
	echo <<<EOF

<style>
td{font-family:Tahoma;font-size:12px;color:#000000;}
a{color:$link_color; text-decoration:none;}
a:hover{color:$hover_color}
.menu{color:#00458B;text-decoration:none;}
.quick{color:#00597D;text-decoration:none;font-size:11px;padding-top:2px;}
input,textarea, select {
	color : #000000;
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	border-color : #000000;
}
body {
	background-color: white;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;
	font-family:Tahoma;font-size:12px;color:#000000;
}

A.nounderline
{
 text-decoration: none;
font :bold
}

    </style>
EOF;

}

if (!$include_mode) {
	echo "<title>".$title;
	if (isset($_GET['dir'])) {
		echo " - '".$_GET['dir']."'";

	echo "</title></head>";
	}
}

			/*+++++++++++++++++*/
			////// ------- //////
			//// MAIN SCRIPT ////
			////// ------- //////
			/*=================*/

if (@$_GET['type'] == 'display') {
	$dir = $_GET['dir'];

	// top echo for index info
	echo("<br><b>Gallery: &nbsp;<font size=\"-1\"><u>$dir</u></font></b><br><font size=\"-2\"><br><a href=index.php>Back to Index</a></font><br><br>");

	$maindir = "thumbnail_".$dir;
	$mydir = @opendir($maindir);
	$exclude = array(".","..", "index.php");
	$counter = 0;

	$fn = @readdir($mydir);	
	if (!$fn) {
		die("Directory does not exist!");
	}
	
	$action = closedir($mydir);
	$mydir = opendir($maindir);

	$print = "<table cellpadding=\"4\" border=\"1\">\n  <tr>\n";
	
	if ($comment) {
		if (is_file($dir."/".$commentfile)) {
			$b = fopen($dir."/".$commentfile, "r");
			$commentdata = fread($b, 32767)."<br><br>";
			$action = fclose($b);
		} else {
			$commentdata = "";
		}
	}	
	
	$i = 0;
	if (@!$page || $page == "0") {
		$page = 1;
	}
	
	// preread out for pages (sloppy)
	
	$pagework = ($page * ($row * $perrow)) - ($row * $perrow);
		
	$z = 0;
	while ($z != $pagework && !$gal_sort) {
		$fn = readdir($mydir);
		++$z;
		++$i;
		
		if (@$fn == $exclude[0] || @$fn == $exclude[1]) {
			--$z;
			--$i;
		}
	}

	$j = 0;
	$counter = 0;
	
	while (false !== ($fn = readdir($mydir)) && !$gal_sort) {
 		$jpegfind = substr($fn, -5, 5);
 		
 		if ($fn == "." || $fn == ".." || (!eregi(".jpg", $jpegfind) && !eregi(".jpeg", $jpegfind))) continue; 
		
		++$i; //total image counter
		++$j; //per page image counter
				
		if ($j < ($row * $perrow) + 1) {
			ImageDisplay($dir, $fn);	
				
			++$counter;
			if ($counter == $row) { 
				$counter = 0;
				$print = $print."</tr><tr>\n";
			}
		}
	}
	
	if ($gal_sort) {
		while (false !== ($fn = readdir($mydir))) {
			$jpegfind = substr($fn, -5, 5);
 		
 			if ($fn == "." || $fn == ".." || (!eregi(".jpg", $jpegfind) && !eregi(".jpeg", $jpegfind))) continue; 
		
			++$i; //total image counter
			++$j; //per page image counter
		
			$image_g[$j] = $fn;
		}
		
		if ($gal_ascending) {
			sort($image_g);
		} else {
			rsort($image_g);
		}
				
		$l = 0;
		$image_gr = NULL;
		while ($l != ($row * $perrow) && isset($image_g[$pagework + $l])) {
			$image_gr[$l] = $image_g[$pagework + $l];
			++$l;
		}

		$k = 0;
		
		while ($k != sizeof($image_gr)) {
			ImageDisplay($dir, $image_gr[$k]);	
				
			++$counter;
			if ($counter == $row) { 
				$counter = 0;
				$print = $print."</tr><tr>\n";
			}
			
			++$k;
		}
	}
	
	$print = $print."</tr></table>";
	
	if ($comment && $commentdata) {
		echo $commentprefix.$commentdata;
	}
	
	if ($print_pages_on_top) {
		PageAmount($i, $dir, $row, $perrow);
	}
	
	echo $print;
	
	if ($print_pages_on_bottom) {
		echo "<br>";
		PageAmount($i, $dir, $row, $perrow);	
	}

 	@closedir($mydir);
 	
 	if ($gal_footer) {
 		ShowFooter($ver);
 	}
 		
} else if (@$_GET['type'] == 'inline') {

	// code for display image within frame
	die("<a href=\"javascript:history.back()\">Go Back</a><br /><img src=\"$dir/$file\">");
	
} else {

	// MAIN SCREEN TURN ON

	$msto = NULL;
	
	$result = opendir(".");
		
	echo("<br><b>$title</b><br><br>\n");
	
	while ($fn = readdir($result)) {
		if ($fn != "." AND $fn != ".." AND is_dir($fn) AND !strstr($fn,'thumbnail_')) {
			if (is_dir('thumbnail_'.$fn)) {
				$b = 0;
				if (@$mainpage_showcount) {
					$c = opendir("thumbnail_".$fn);
					while (false !== ($a = readdir($c))) {
						if (strtolower(substr($a, strlen($a) - 4, 4)) == ".jpg" ||
						  strtolower(substr($a, strlen($a) - 5, 5)) == ".jpeg") {
							++$b;
						}
					}
					
					$b = " (<b>{$b}</b>)";
					closedir($c);
				}
				
				if ($b == "0") {
					$b = "";
				}
				
				if (!$ascending) {
					$msto = $msto."<a href=\"index.php?type=display&dir=$fn\">{$fn}{$b}\n</a><br>";
				} else {
					$msto = "<a href=\"index.php?type=display&dir=$fn\">{$fn}{$b}\n</a><br>".$msto;
				}
			}
		}	
	}
	
	echo $msto;
	
	
	if ($footer) {
		ShowFooter($ver);
	}
}


function PageAmount ($i, $dir, $row, $perrow) {
	echo "<b>Photo count: $i</b><br>Page ";

	$pages1 = (int)(($i / $row) / $perrow);
	$pages2 = ($i / $row) / $perrow;

	$pgt = $pages2 - $pages1;

	if ($pgt) {
		$pagest = $pages1 + 1;
	} else {
		$pagest = $pages1;
	}

	$j = 0;
	while ($j != $pagest && $i != 0) {
		++$j;
		if ($j == @$_GET["page"] && $j != $pagest)
			echo "<b>$j</b>, ";
		else if ($j != @$_GET["page"] && $j != $pagest)
			echo "<a href=\"index.php?type=display&dir=$dir&page=$j\">".$j."</a>, ";
		else if ($j == @$_GET["page"] && $j == $pagest)
			echo "<b>$j</b><br /><br />";
		else if ($j != @$_GET["page"] && $j == $pagest)
			echo "<a href=\"index.php?type=display&dir=$dir&page=$j\">".$j."</a><br /><br />";	
		
	}
	


	
	
}

function ImageDisplay ($dir, $fn) {
	global $maindir, $print, $border;
	global $display_mode;
	include("config.php");

	$print = $print."<td><div align=\"center\"><a href=";
			
	if ($display_mode == "link") {
		$print .= "'".$dir."/".$fn."'";
	} else if ($display_mode == "page") {
		$print .= "'".@$_SERVER['PHP_SELF']."?type=inline&file=".$fn."&dir=".$dir."'";
	}
		
	if ($imagetarget) {
		$print .= " target=\"$imagetarget\"";
	} else {
		$print .= " target=\"_top\"";
	}
		
	$print .= "><img src='$maindir/$fn'";
	
	if (!$heightalso) {
		$print .= " width=100% ";
	}
		
	$print .= "border='$border' alt='$fn'></a>";
	
	if ($showdetails) {
	$tlabel = " bytes";
		$image_size = filesize($dir."/".$fn);
			
		if ($bytetype == "k" || $bytetype == "K") {
			if ($mib) {
				$tlabel = " KiB";
				$image_size = $image_size / 1024;
			} else {
				$tlabel = "kb";
				$image_size = $image_size / 1000;
			}
			$image_size = round($image_size, 2);
		}
		
		if ($bytetype == "m" || $bytetype == "M") {
			if ($mib) {
				$tlabel = " MiB";
				$image_size = $image_size / 1048576;
			} else {
				$tlabel = "mb";
				$image_size = $image_size / 1000000;
			}
			$image_size = round($image_size, 2);
		}
		$image_size = number_format($image_size, 2, $decimal, $thousands);
		$image_size = $image_size.$tlabel;
		
		$ressize = getimagesize($dir."/".$fn);
		$res_w = $ressize[0];
		$res_h = $ressize[1];
		
		include("config.php");
		$print .= $showdetails_html;
	}
	
	$print .= "</div> \n </td>\n";
}

function ShowFooter($ver) {
	echo "<br><font size=\"-2\">true's image gallery $ver, (c) 2005 TRUE (<a href=\"mailto:tpa@REMOVETHIStoothpastealien.com\">tpa (at) toothpastealien [odt] com</a>). Licensed under the LGPL. Contains code from Image Gallery by <a href=\"mailto:tomchan@hkstar.com\">tomchan [at] hkstar.com.</a><br>"
	."Images (c) their respective owners. Source can be found in <a href=\"http://true.damnserver.com/images/tig.zip\">tig.zip</a>.";
}

?>