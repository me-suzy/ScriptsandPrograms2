<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_custom
{

function add_page()
{
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Here you can add a new custom page.


<form name="addpage" id="addpage" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_custom&amp;item=5" method="POST">
Page Title: <input type="text" name="title" /> <br />
Page ID: <input type="text" name="pageid" /> (This is the text that is passed through in the url to identify the page. Only a-z and - or _ is allowed.) <br />
Page Text: (HTML Allowed) <br />
<textarea name="text" cols="50" rows="10"></textarea> <br />
Members only? <input type="checkbox" name="mem_only" /> (If ticked, only members can see the page.) <br />
Viewable? <input type="checkbox" name="view" checked="checked" />
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

function page_success()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
New page added!

EOT;

return $CMSHTML;

}

function page_success_edit()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Page edited!

EOT;

return $CMSHTML;

}
function list_pages($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
id: {$row['id']} - Page ID: {$row['pageid']} - Title: {$row['title']} (<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_custom&amp;item=3&amp;customid={$row['id']}">Edit</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_custom&amp;item=6&amp;customid={$row['id']}">Delete</a> )<br />
EOT;

return $CMSHTML;

}

function manage_top()
{
global $info;
$sesid = $_GET['ses'];

$CMSHTML = '';

$CMSHTML .= <<<EOT
Here you can manage and add new custom pages.<br />
<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_custom&amp;item=2">Add New Page</a> <p> 
EOT;

return $CMSHTML;

}

function edit_page($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
<form name="addpage" id="addpage" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_custom&amp;item=4" method="POST">
<input type="hidden" name="id" value="{$row['id']}" />
Page Title: <input type="text" name="title" value="{$row['title']}" /> <br />
Page ID: <input type="text" name="pageid" value="{$row['pageid']}" /> (This is the text that is passed through in the url to identify the page. Only a-z and - or _ is allowed.) <br />
Page Text: (HTML Allowed) <br />
<textarea name="text" cols="50" rows="10">{$row['html']}</textarea> <br />
Members only? <input type="checkbox" name="mem_only" {$row['mem_only']} /> (If ticked, only members can see the page.) <br />
Viewable? <input type="checkbox" name="view" {$row['view']} />
<p>
<input type="submit" value="Submit" /> &nbsp; &nbsp; &nbsp; <input type="reset" value="Reset" />
</p>
</form>
EOT;

return $CMSHTML;

}

}
?>