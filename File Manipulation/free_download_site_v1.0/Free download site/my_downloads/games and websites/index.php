<?php
/*
    Copyright (C) 2005 Adullam Limited., All Rights Reserved.

    Unless explicitly acquired and licensed from Licensor under the Technical Pursuit License ("TPL") 
    Version 1.0 or greater, the contents of this file are subject to the Reciprocal Public License 
    ("RPL") Version 1.1, or subsequent versions as allowed by the RPL, and You may not copy or use 
    this file in either source code or executable form, except in compliance with the terms and 
    conditions of the RPL.

    You may obtain a copy of both the TPL and the RPL (the "Licenses") from Technical Pursuit Inc. 
    at http://www.technicalpursuit.com.

    All software distributed under the Licenses is provided strictly on an "AS IS" basis, WITHOUT 
    WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, AND Adullam Limited HEREBY DISCLAIMS ALL 
    SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
    PARTICULAR PURPOSE, QUIET ENJOYMENT, OR NON-INFRINGEMENT. See the Licenses for specific 
    language governing rights and limitations under the Licenses. 
    
    Project Name: Download Site System
    File: index.php
    File-Version: 1.2
    Details: place this file in each folder that contains downloads.
    
    1.2 moved all non-essential "boot" functions to site root.
    1.1 self correcting version
    1.0 Orininal version
*/

function find_include($this){
	$topinc  = ""; 					
	$depthlimit=6; 		// alter this value to allow deeper itterations
	$lockbreaker=0; 
	$currentdir="."; 	
	while ($topinc ==""){
		if ($handle = opendir($currentdir)) {
			while (false !== ($file = readdir($handle))) { 
				if (is_dir("$file")==True) {
					if ($file =="..") {
						//add it
						$newcurrentdir=$currentdir."/..";
					}
				}
				if ($file == $this) {
				$topinc = $currentdir."/".$this;
				include($topinc );
				}
			}
		}
		if ($lockbreaker > $depthlimit) {
			$topinc = "error";
		}
		$currentdir=$newcurrentdir;
		$lockbreaker=$lockbreaker+1;
	}
}

find_include("top.inc.php");
find_include("codebase.inc.php");
find_include("bottom.inc.php");
?>