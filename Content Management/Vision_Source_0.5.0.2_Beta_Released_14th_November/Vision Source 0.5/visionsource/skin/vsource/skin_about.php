<?

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 11th April 2005							//
//															//
//----------------------------------------------------------//
//															//
//		Script: skin_news.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_about {

//////////////////////////////////
//	Show about us
/////////////////////////////////

function showabout($content) {

$CMSHTML = <<<EOT
<div class="title">About Us</div>
    <div class="text_main">
 	<br />
	{$content}
	</div>
	<div class="text_bottom">&nbsp;</div>
EOT;

return $CMSHTML;


  }
  

}


?>