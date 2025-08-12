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

class skin_news {

//////////////////////////////////
//	Main header thing for skin
/////////////////////////////////

function newstop() {
$CMSHTML = '';

$CMSHTML .= <<<EOT
News:

EOT;

return $CMSHTML;
}

function shownews($row = '') {
global $info;

$CMSHTML = <<<EOT
	<p><div class="title"><a href="{$info['base_url']}/index.php?id=news&amp;do=2&amp;item={$row['id']}">{$row['newstitle']}</a></div>
    <div class="subtitle">Posted By: {$row['poster']} on {$row['thedate']}</div>
	<div class="text_main">
 	<br />
	{$row['newstext']}
	</div>
	  <div class="text_bottom">&nbsp;</div>
    </p>
EOT;

return $CMSHTML;

  }
  
function showarticle($row = '') {

$CMSHTML = <<<EOT
	<p><div class="title">{$row['newstitle']}</div>
	<div class="subtitle">Posted By: {$row['poster']} on {$row['thedate']}</div>
    <div class="text_main">
 	<br />
	{$row['newstext']}
	</div>
	  <div class="text_bottom">&nbsp;</div>
    </p>
	<br />
	Comments:
EOT;

return $CMSHTML;
  }
  
function showpages($pages) {

if (!empty($pages))
{

$CMSHTML = <<<EOT

<p>$pages</p>

EOT;

}

return $CMSHTML;

}

function showarchive() {
global $info;


$CMSHTML = <<<EOT

<p>
<a href="{$info['base_url']}/index.php?id=news&amp;do=5">Click here for news archive</a>
</p>

EOT;


return $CMSHTML;
}
  
function showcomments($com = '', $delete = '') {
global $info, $db;
$CMSHTML = '';

$CMSHTML .= <<<EOT
<p> <div style="border: 1px solid #CCC; padding: 3px;">{$com['comment']} <br /> <br />
EOT;
if (!empty($delete))
{
$CMSHTML .= <<<EOT
Comment by: {$com['name']} <br />
Email: {$com['email']} <br />
Mod Options: {$delete}

EOT;
}
else
{

$CMSHTML .= <<<EOT
Comment by: {$com['name']} <br />
Email: {$com['email']}

EOT;
}

$CMSHTML .= <<<EOT
</div> 
</p>
EOT;

return $CMSHTML;
}

  
function postcomment($newsid, $info = '') {
global $info, $vsource;
$CMSHTML = '';

$CMSHTML .= <<<EOT
<p>
<form name='postcomment' id='postcomment' action="{$info['base_url']}/index.php?id=news&do=3" method="POST">
<input type="hidden" value="{$newsid}" name="newsid" />
EOT;
if ($vsource->is_member() == 1)
{
$row = $vsource->get_mem_info();
$CMSHTML .= <<<EOT
<input type="hidden" value="{$row['username']}" name="name" />
<input type="hidden" value="{$row['email']}" name="email" />
<input type="hidden" value="0" name="is_guest" />
EOT;
}
else
{
$CMSHTML .= <<<EOT
<input type="hidden" value="1" name="is_guest" />
Your Name: <input type="text" name="name" /> <br />
Your Email: <input type="text" name="email" /> <br />
EOT;
}
$CMSHTML .= <<<EOT
Your Comment: <br />
<textarea name="comment" cols="50" rows="10"></textarea>
<p>
<input type="submit" value="Submit" /> &nbsp; &nbsp; <input type="reset" value="Reset" />
</form>
</p>
EOT;
  
return $CMSHTML;
}
 
function error() {
global $info;
 
$CMSHTML = <<<EOT
<div>Im sorry, the news article you are trying to view is broken. <br />
You can report this to the admin via by clicking <a href="{$info['base_url']}/index.php?id=contact">here</a>
</div>
EOT;

return $CMSHTML;
  }
  
function delete_success() {
 
$CMSHTML = <<<EOT

Comment successfully deleted.
<p>
<a href="javascript:history.go(-1)">Go back</a>.
</p>

EOT;

return $CMSHTML;
}
  
function custerror($errormsg) {

$CMSHTML = <<<EOT
Im sorry, there was an error, the error was: $errormsg <br />
<a href="javascript:history.go(-1)">Go back</a>.
EOT;

return $CMSHTML;
}

}


?>