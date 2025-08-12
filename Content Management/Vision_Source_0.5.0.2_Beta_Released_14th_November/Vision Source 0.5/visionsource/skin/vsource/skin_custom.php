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

class skin_custom {

function custom_home() {

$CMSHTML = <<<EOT
Welcome to the custom page module. Here is a list of all the custom pages:

EOT;

return $CMSHTML;


  }
  
function list_pages($row = '') {
global $info;

$CMSHTML = '';

$CMSHTML .= <<<EOT
<li><a href="{$info['base_url']}/index.php?id=custompage&amp;page={$row['pageid']}">{$row['title']}</a> </li>

EOT;

return $CMSHTML;
}

function start_ul() {

$CMSHTML = <<<EOT
<p>
<ul>

EOT;

return $CMSHTML;
}

function end_ul() {

$CMSHTML = <<<EOT
</ul>
</p>

EOT;

return $CMSHTML;
}

function no_pages() {

$CMSHTML = <<<EOT

<p>
Im sorry, but there are currently no pages in the database. Please try again later.
</p>

EOT;

return $CMSHTML;
}
  
function showpage($row = '') {

$CMSHTML = '';

$CMSHTML .= <<<EOT

<div class="title">{$row['title']}</div>
    <div class="text_main">
 	<br />
	{$row['html']}
	</div>
	<div class="text_bottom">&nbsp;</div>
	
EOT;

return $CMSHTML;  

}

}


?>