<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_skin
{

function add_skin()
{
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Here you can add new skin.

<form name="addskin" id="addskin" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_skin&amp;item=3" method="POST">
Name: <input type="text" name="name" /> <br />
Directory: <input type="text" name="dir" /><br />
Viewable? <input type="checkbox" name="view" checked />
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

function skin_success()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Skin added!!

EOT;

return $CMSHTML;

}

function delete_complete()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Skin has now been deleted out of the database.

Note: The skin directory is still there for backup reasons.

EOT;

return $CMSHTML;

}

function skin_success_edit()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Skin edited!

EOT;

return $CMSHTML;

}

function manage_top()
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
Here you can manage all your skin.
<p>
<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_skin&amp;item=2">Add Skin</a>
</p>

EOT;

return $CMSHTML;

}

function default_skin_updated()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Default skin updated!!

EOT;

return $CMSHTML;

}

function list_skins($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
Name: {$row['name']} (<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_skin&item=4&amp;skinid={$row['id']}">Edit</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_skin&item=6&amp;skinid={$row['id']}">Make Defualt</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_skin&item=7&amp;skinid={$row['id']}">Delete</a> )<br />

EOT;

return $CMSHTML;

}

function edit_skin($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
<form name="edit_skin" id="edit_skin" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_skin&item=5" method="POST">
<input type="hidden" value="{$row['id']}" name="skinid" />
Name: <input type="text" value="{$row['name']}" name="name" /> <br />
Directory: <input type="text" name="dir" value="{$row['directory']}" />
Viewable? <input type="checkbox" name="view" checked />
<p>
<input type="submit" value="Submit" />
</p>
</form>
EOT;

return $CMSHTML;

}



}
?>
