<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_about
{

function showabout($row = '')
{
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Here you can add news.

<form name="editabout" id="editabout" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_about&amp;item=2" method="POST">
About message: (HTML Allowed) <br />
<textarea name="about_text" cols="50" rows="10">{$row['content']}</textarea>
<p>
<input type="submit" value="Submit" /> &nbsp; &nbsp; &nbsp; <input type="reset" value="Reset" />
</p>
</form>
EOT;

return $CMSHTML;

}

function error($errormsg)
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Im sorry, there has been an error. The error msg is: $errormsg

EOT;

return $CMSHTML;
}

function edit_complete()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
The about page has been edited!!

EOT;

return $CMSHTML;

}


}
?>