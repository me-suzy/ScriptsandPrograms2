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

	// init.php 
	
	require_once("config.php");
	require_once(PATH_INCLUDE."thirdparty/phemplate.class.php");
	require_once("functions.php");
	
	// action scripts
	require_once(PATH_INCLUDE."action/note.php");
	require_once(PATH_INCLUDE."action/news.php");
	require_once(PATH_INCLUDE."action/html.php");
	require_once(PATH_INCLUDE."action/upload.php");
	require_once(PATH_INCLUDE."action/rss.php");
	
	// preview scripts
	require_once(PATH_INCLUDE."preview/jpg.php");
	require_once(PATH_INCLUDE."preview/gif.php");	
	require_once(PATH_INCLUDE."preview/png.php");	
	require_once(PATH_INCLUDE."preview/txt.php");	

	// download scripts
	require_once(PATH_INCLUDE."download/jpg.php");
	require_once(PATH_INCLUDE."download/gif.php");
	require_once(PATH_INCLUDE."download/txt.php");
	require_once(PATH_INCLUDE."download/cpp.php");

	ob_start();
	session_start();
	set_time_limit(0);
	
?>
