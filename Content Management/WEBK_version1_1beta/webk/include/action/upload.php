<?php
//---------------------------------------------------------------------------//
// author: Yanick Bourbeau <ybourbeau@mrgtech.ca> 
// date:   2005.09.30
// web:    http://mrgibson.zapto.org
// info:   WEBK
//---------------------------------------------------------------------------//
// copyleft license
//
// this software is provided 'as-is', without any express or implied
// warranty. in no event will the authors be held liable for any damages
// arising from the use of this software.
//
// permission is granted to anyone to use this software for any purpose,
// including commercial applications, and to alter it and redistribute it
// freely, subject to the following restrictions: 
//
// 1. the origin of this software must not be misrepresented;
//	  you must not claim that you wrote the original software. 
//	  if you use this software in a product, an acknowledgment 
//	  in the product documentation would be appreciated but is not required.
//
// 2. altered source versions must be plainly marked as such, 
//	  and must not be misrepresented as being the original software.
//
// 3. mail about the fact of using this class in production 
//	  would be very appreciated.
//
// 4. this notice may not be removed or altered from any source distribution.
//
//---------------------------------------------------------------------------//

// action: HTML, render the content of a file to look like a html content


function WEBK_action_UPLOAD($item)
{
	global $PATH;
	
	 $TPL = new phemplate(PATH_TEMPLATE);
	 
	 if (isset($_FILES["upload"]))
	 {
	 		$file = $_FILES["upload"];
	 		$name = $file["name"];
	 		$name = str_replace("..","",$name);
	 		$name = str_replace("/","",$name);
	 		$PATH = str_replace("..",".",$PATH);
	 		
	 	
	 		if (!move_uploaded_file($file["tmp_name"], PATH_ROOT.$PATH.$file["name"]) )
			{
    	  print "Upload failed";
 				die();
    	}
	
		 	WEBK_Redirect("?folder=".$PATH);	
	 }
	 
 	 $TPL->set_file("page","action/upload.html");
	

	return $TPL->process("","page",2);
}


?>
