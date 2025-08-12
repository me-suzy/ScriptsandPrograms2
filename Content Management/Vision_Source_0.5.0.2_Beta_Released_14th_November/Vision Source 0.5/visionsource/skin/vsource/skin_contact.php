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

class skin_contact {

//////////////////////////////////
//	Main header thing for skin
/////////////////////////////////

function contact() {
global $info;

$CMSHTML = <<<EOT
<form action="{$info['base_url']}/index.php?id=contact&amp;do=send" method="post">
<table width="100%">
<tr>
<td style="width: 15%">Your Name:</td>
<td style="width: 85%"><input type="text" name="name"></td>
</tr>
<tr>
<td style="width: 15%">Your E-mail:</td>
<td style="width: 85%"><input type="text" name = "email"></td>
</tr>
<tr>
<td colspan="2">Your Message</td>
</tr>
<tr>
<td colspan="2"><textarea name="message" cols="70" rows="10"></textarea></td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Submit"></td>
</tr>
</table>
</form>
EOT;

return $CMSHTML;


  }
  
function complete() {
global $info;

$CMSHTML = <<<EOT
Your email has successfully been sent.

EOT;

return $CMSHTML;


  }
  

}


?>