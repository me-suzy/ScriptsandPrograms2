<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_news
{

function top()
{
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Here you can add news.

<form name="addnews" id="addnews" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_news&item=5" method="POST">
News title: <input type="text" name="title" /> <br />
News message: (HTML Allowed) <br />
<textarea name="text" cols="50" rows="10"></textarea>
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

function news_success()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
News added!

EOT;

return $CMSHTML;

}

function news_success_edit()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
News edited!

EOT;

return $CMSHTML;

}
function list_news($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
id: {$row['id']} - Title: {$row['newstitle']} Posted by: {$row['poster']} On: {$row['thedate']} (<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_news&item=3&amp;newsid={$row['id']}">Edit</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_news&item=6&amp;newsid={$row['id']}">Delete</a> )<br />
EOT;

return $CMSHTML;

}

function manage_top()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Here you can manage news blah <br /> <br />
EOT;

return $CMSHTML;

}

function edit_news($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
<form name="edit_news" id="edit_news" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_news&item=4" method="POST">
<input type="hidden" value="{$row['id']}" name="id" />
News Title: <input type="text" value="{$row['newstitle']}" name="title" /> <br />
News Text: (HTML Allowed) <br />
<textarea name="text" cols="50" rows="10">{$row['newstext']}</textarea>
<p>
<input type="submit" value="Submit" /> &nbsp; &nbsp; &nbsp; <input type="reset" value="Reset" />
</p>
</form>
EOT;

return $CMSHTML;

}

}
?>