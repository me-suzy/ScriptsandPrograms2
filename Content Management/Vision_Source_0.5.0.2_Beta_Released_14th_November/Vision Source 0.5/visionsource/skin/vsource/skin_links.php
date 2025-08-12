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
//		Script: skin_links.php								//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_links {

//////////////////////////////////
//	Main header thing for skin
/////////////////////////////////

function showcatinfo($about, $cat) {

$CMSHTML = <<<EOT
 Links: <p>
 {$cat}: <p>
 {$about}
 </p>
 <ul>
EOT;

return $CMSHTML;

  }
  
function error() {

$CMSHTML = <<<EOT
 <p>
 Im sorry but an error has occurred. Please go back.
 </p>
EOT;

return $CMSHTML;

  }
  
function link($id, $name, $hits) {
global $info;

$CMSHTML = <<<EOT
 <li class="links"><a onclick="window.open(this.href,'_blank');return false;" href="{$info['base_url']}/index.php?id=links&amp;do=2&amp;goto=$id">$name</a> &nbsp; $hits</li>

EOT;

return $CMSHTML;

}
  
function goback() {

$CMSHTML = <<<EOT
 </ul>
 <a href="javascript:history.go(-1)">Go back</a>
 </p> 
EOT;

return $CMSHTML;

 }
 
function hometop() {

$CMSHTML = <<<EOT
 Links: <p>
 <ul>
EOT;

return $CMSHTML;
 }

function showcat($catid, $cat) {
global $info;

$CMSHTML = <<<EOT
	<li><a href="{$info['base_url']}/index.php?id=links&amp;do=1&amp;catid=$catid">$cat</a></li>
	
EOT;

return $CMSHTML;

 }
 
function endcat() {

$CMSHTML = <<<EOT
 </ul></p> 
EOT;

return $CMSHTML;

 }



}


?>