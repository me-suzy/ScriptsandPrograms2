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
    File-Version: 1.1
    Details: place this file in each folder that contains downloads.
*/


$incme = array();
if ($handle = opendir('.')) {
	echo "<ul>";
    while (false !== ($file = readdir($handle))) {
    
		if (is_dir("$file")==True) {
			if ($file ==".") {
				//ignore it
			}elseif ($file =="..") {
				echo "<li>Change Category: <a href='".$file."'>Up One Level</a></li>";
			} else {
				echo "<li>Change Category: <a href='".$file."'> ".$file."</a></li>";
			}
		} else {
			// it's a file
			$temp = explode(".", $file);
			$name = $temp[0];
			$type = $temp[1];
			if ($file=="index.php") {
				// Ignore that too.
			} elseif ($type == "php") {
				array_push($incme, $file);
			} else {
				echo "<li>Get file: <a href='".$file."'> ".$file."</a></li>";
			}
		}
    }
	echo "</ul>";	
	foreach($incme as $fileto) {
		include_once($fileto);
	}
}

?>