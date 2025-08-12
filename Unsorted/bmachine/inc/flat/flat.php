<?php
/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

//######################################
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

// Get the list of all posts and necessary data into
// a multi dimensional array

function getPostList() {
global $c_dir;
$files = array();
$fn = array();
$directory="$c_dir/";

// Read all the files in the DATA folder
	$handle = opendir("$c_dir") or errd("\"$c_dir\" directory not found!", "\"$c_dir\" directory not found or is not writable!");
	$i=0;

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != ".." && substr($filename,0,2) != "[]") { 
	$files[$i] = array(filemtime($directory .$filename), $filename); 
	$i++; 
	if($i == $x) { return; }
	} 
} 
	closedir($handle);

// Sort the files who are already in the "created date" order
arsort($files);
reset($files);

	foreach($files as $name ) 
	{
	array_push($fn, $name[1]);
	}

for($n=0;$n<=count($fn)-1;$n++) {

$fp=@fread(@fopen("$c_dir/$fn[$n]", "r"), 1000);

// Explode the contents of the file
list($title,$date,$file,$frmt, $a_name, $a_email, $a_site, $keyw, $msg, $smr) = explode("||", $fp);

	$ar[id][$n]=$fn[$n];
	$ar[title][$n]=$title;
	$ar[a_name][$n]=$a_name;
	$ar[a_email][$n]=$a_email;
	$ar[a_url][$n]=$a_url;
	$ar[date][$n]=$date;
	$ar[summary][$n]=$smr;
	$ar[data][$n]=$msg;
	$ar[format][$n]=$frmt;
	$ar[file][$n]=$dat[file];
	$ar[keyws][$n]=$keyw;

}
return $ar;
}


//######################## GET LAST X POSTS #######################

function getPosts($x,$start,$end) {
global $c_dir, $x_wrap;
$files = array();
$fn = array();
$directory="$c_dir/";

// Read all the files in the DATA folder
	$handle = opendir("$c_dir") or errd("\"$c_dir\" directory not found!", "\"$c_dir\" directory not found or is not writable!");
	$i=0;

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != ".." && substr($filename,0,2) != "[]") { 
	$files[$i] = array(filemtime($directory .$filename), $filename); 
	$i++; 
	if($i == $x) { return; }
	} 
} 
	closedir($handle);

// Sort the files who are already in the "created date" order
arsort($files);
reset($files);

	foreach($files as $name ) 
	{
	array_push($fn, $name[1]);
	}

for($n=0;$n<=count($fn)-1;$n++) {

	$fpr=@fopen("$c_dir/$fn[$n]", "r"); $fp=@fgets($fpr); @fclose($fpr);
// Explode the contents of the file
list($title,$date) = explode("||", $fp);

	if(strlen($title) > 10) { $title=substr($title,0,$x_wrap).".."; };

	// Append the links to a variable
	if(trim($title)) {
	$dat.="$start<a href=\"index.php?id=$fn[$n]\"><span class=\"t_small\">$title</span></a>$end";
	}
}
return $dat;
}



//#################### Get the number of Comments for a post ###########

// Get the number of comments for an entry
function getCmts($in) {
global $c_dir,$cmt_dir,$m_cnv;

$fp=@fread(fopen("$cmt_dir/$in","r"),100000);
if(!$fp) { return "0"; }

// In the comments data file, comments by users are separated by
// <!-- comment termination //--> . Explode them into an array

$cmts=explode("<!-- comment termination //-->",$fp);

return count($cmts)-1;

}

// ##################### Delete all comments for a post ############
function delCmts($id) {
global $cmt_dir;
@unlink("$cmt_dir/$id");
}

//#######################  Delete all articles #########################

function delAll() {
global $c_dir,$cmt_dir;

/////////////////////////////////////
// Delte all the posts in the data directory

	$handle = opendir("$c_dir");
	while($filename = readdir($handle))
	{
	if( $filename != "." && $filename != ".." )
	@unlink("$c_dir/$filename");
	}
	closedir( $handle );


//-----------------------------------
// Delete all the comments into the comments directory

$handle = opendir("$cmt_dir");
while($filename = readdir($handle))
{
if( $filename != "." && $filename != ".." )
@unlink("$cmt_dir/$filename");
}
closedir( $handle );


//-----------------------------------
// Delete all the attached files in the files directory

	$handle = opendir("./files");
	while($filename = readdir($handle))
	{
	if( $filename != "." && $filename != ".." )
	@unlink("./files/$filename");
	}
	closedir( $handle );
/////////////////////////////////////

	header("Location: admin.php?done=true"); exit();
}

//####################################################################

// Delete a specific post
function delPost() {
global $c_dir,$cmt_dir;

	if($_GET[f]) {
	global $c_dir,$cmt_dir;
		if(file_exists("$c_dir/$_GET[f]")) {
		clearstatcache();
		$fpr=@fopen("$c_dir/$_GET[f]", "r"); $fp=@fgets($fpr); @fclose($fpr);

		list($title,$date,$file, $nn) = explode("||", $fp);

	 // Delte the attached file, if any
	if(trim($file) != "") {
	@unlink("files/$file");
	}

	// Delete the article and the comments
	@unlink("$c_dir/$_GET[f]");
	@unlink("$cmt_dir/".str_replace("[]","",$_GET[f]));
	}
		}
header("Location: admin.php?done=true"); exit();
}

//####################################################################

// List the articles

function getFileDat() {
$files = array();
$fn = array();
global $c_dir;
$directory="$c_dir/";

$handle = opendir("$c_dir") or errd("Cannot find the data dir!", "Cant open the \"$c_dir\" dir!"); 
$i=0;

// Get the file list, sort them by date and store in an array

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != "..") { 
	$files[$i] = array(filemtime($directory .$filename), $filename); 
	$i++; 
	} 
} 

arsort($files); 
reset($files); 

foreach($files as $name ) 
	{ 
	array_push($fn, $name[1]);
	}

for($j=0;$j<=count($fn);$j++) {
	$tdt=doDat($fn[$j]);
	if($tdt) { echo $tdt; $pr="yes"; }
}

if(!$pr) { echo "<font face=verdana color=red size=2><b>0 articles were found in the database!</b></font>"; }
}


//################################### Date/Calendar functions #######

// Get all the posts posted in an array by date.
// Used for calendar display

function getDatelist() {
$files = array();
$fn = array();
global $c_dir;
$directory="$c_dir/";

$handle = opendir("$c_dir") or errd("Cannot find the data dir!", "Cant open the \"$c_dir\" dir!"); 
$i=0;

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != "..") { 
	$files[$i] = array(filemtime($directory .$filename), $filename); 
	$i++; 
	} 
} 

arsort($files); 
reset($files); 

foreach($files as $name ) 
	{ 
	array_push($fn, $name[1]);
	}

$ar=array();

	for($j=0;$j<=count($fn);$j++) {

			if($fn[$j]) {
			$fdat=fopen("$c_dir/$fn[$j]","r"); $filedat = fgets($fdat); fclose($fdat);
			list($title,$date,$null)=explode("||",$filedat);

			// Store the file data in the array with the name as the date
			// in the format dd_mm_yy
			$tmp_date=date("j_M_Y",$date);
			$ar["$tmp_date"]="true";
			}
	}

return $ar;
}


// Display posts on a particular date

function showbyDate($d="",$m="",$y="") {
global $s_title,$m_cmt;

if(!$d || $d > 31 || $d < 1) { $d=date("j"); }
if(!$m || strlen($m) != 3) { $m=date("M"); }
if(!$y || strlen($y) != 4) { $y=date("Y"); }


$ar=getPostList();

hdr("$s_title","");

for($b=0;$b<=count($ar[title])-1;$b++) {

$t_date=date("j_M_Y",$ar[date][$b]);
$tmp_date=$d."_".$m."_".$y;

	if(trim($t_date) == trim($tmp_date)) {
	echo dodat($ar[id][$b]);
	}

}
ftr("","");
exit();
}

?>