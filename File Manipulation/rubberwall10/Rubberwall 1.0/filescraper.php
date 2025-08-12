<?php //explodingpanda.com quiet antileech script

/***************************************************************************
 *                                RubberwaLL 1.0a
 *                            -------------------
 *   created:                : Friday, 9th May 2005
 *   copyright               : (C) 2005 ExplodingPanda.com, Neil Ord
 *   email                   : neil@explodingpanda.com
 *   web                     : http://www.explodingpanda.com/
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
$allowed = 0;
include('config.php');

if($allowblank > 0) { if($_SERVER['HTTP_REFERER']=="") { $allowed = 1; }}

$domains = count($alloweddomains);

for($y=0;$y<$domains+1;$y++) {
	if((stristr($_SERVER['HTTP_REFERER'], $alloweddomains[$y]))) { $allowed = 1;}
}

if($allowed > 0) {
						$namenumberarray = file($webaddress."fileindex.txt");
						$numberoffiles = count($namenumberarray);
						$filenames = array();
						
						for($x=0;$x<$numberoffiles+1;$x++) {
							$temporary = explode(":",$namenumberarray[$x]);
							$tempname = explode("\n",$temporary[1]);
							$filenames[$temporary[0]] = $tempname[0];
						}
						
						if(!isset($filenames[$_GET['serve']])) { 
							if($logging > 0){
								$status = "ReqNF";
								include('logit.php');
							}
							echo('That number wasnt found!');
							exit;
						}
						
						$wantedfilename = $filenames[$_GET['serve']];
														
														
						$extension = explode(".", $wantedfilename);
						$numberinarray = count($extension);
						
						$lcext = strtolower($extension[$numberinarray-1]);
						
						//BEGIN CONTENT TYPES BLOCK. ADD OR REMOVE FILE TYPES HERE, AS SHOWN //
						//DON'T EDIT THIS UNLESS YOU KNOW WHAT YOU ARE DOING!//
						//MOST COMMON FILE TYPES ARE ALREADY INCLUDED//
						
						switch($lcext) {
							case ($lcext == "swf"): 
								$commonname="flash"; 
								$ct = "Content-type: application/x-shockwave-flash";
							break;
							case ($lcext == "wmv"): 
								$commonname="wmv"; 
								$ct = "Content-type: video/x-ms-wmv";
							break;
							case ($lcext == "mov"): 
								$commonname="quicktime movie"; 
								$ct = "Content-type: video/quicktime";
							break;
							case ($lcext == "avi"): 
								$commonname="avi video"; 
								$ct = "Content-type: video/avi";
							break;
							case ($lcext == "rar"): 
								$commonname="winrar"; 
								$ct = "Content-type: application/octet-stream";
							break;
							case ($lcext == "zip"): 
								$commonname="zip"; 
								$ct = "Content-type: application/octet-stream";
							break;
							case ($lcext == "bmp"): 
								$commonname="bitmap"; 
								$ct = "Content-type: image/bmp";
							break;
							case ($lcext == "gif"): 
								$commonname="gif"; 
								$ct = "Content-type: image/gif";
							break;
							case ($lcext == "jpeg" || $lcext == "jpg" || $lcext == "jpe"): 
								$commonname="jpeg"; 
								$ct = "Content-type: image/jpeg";
							break;
							case ($lcext == "mpeg" || $lcext == "mpg" || $lcext == "mpe"): 
								$commonname="mpeg"; 
								$ct = "Content-type: video/mpeg";
							break;
							case ($lcext == "png"): 
								$commonname="png"; 
								$ct = "Content-type: image/png";
							break;
							
							//END//
							
							default: 
								$commonname="Generic Filetype"; 
								$ct = "Content-type: application/octet-stream";
								
								if($logging > 0){
									$status = "Generic_Filetype";
									include('logit.php');
								}
							
						}
						
						$handle = fopen($webaddress.$wantedfilename, "rb");
						header("Cache-Control: "); //keeps ie happy
						header("Pragma: "); //keeps ie happy
						header($ct); //content type as set above from explode();
						
						if(!stristr($lcext, "swf")){//flash plays, it isnt downloaded as an actual file.
							header("Content-Disposition: attachment; filename=\"".$wantedfilename."\"");
						}
						
						header("Content-Length: ".filesize($path.$wantedfilename));
						
						fpassthru($handle);
						if($logging > 0){
							$status = "Granted";
							include('logit.php');
						}
						exit;
}

else {
	if($logging > 0){
		$status = "Denied";
		include('logit.php');
	}
	exit;
	//quiet leech kill
}
?>