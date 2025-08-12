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
// action: NOTE


function WEBK_action_NOTE($item)
{
	$f = fopen(PATH_ROOT.$item["path"].$item["name"],"r");
	$data = fread($f,49999);
	fclose($f);
	
	$data = str_replace("\n","<br>",$data);
	$TPL = new phemplate(PATH_TEMPLATE);

	$TPL->set_file("page","action/note.html");

	$TPL->set_var("content",$data);
		
		
	$content = $TPL->process("","page",2);

	return $content;
}


?>