<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_links
{

function add_link($cat)
{
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Here you can add a new link.

<form name="addlink" id="addlink" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_links&amp;item=4" method="POST">
Name: <input type="text" name="name" /> <br />
Link: <input type="text" name="link" value="http://" /><br />
Catagory: <select name="catid">
EOT;

$num = count($cat);

  for ($i = 1; $i <= $num; $i++)
  {
$CMSHTML .= <<<EOT
<option value="{$cat[$i - 1]['id']}">{$cat[$i - 1]['cat']}</option>
EOT;
  }

$CMSHTML .= <<<EOT
</select>
<p>
<input type="submit" value="Submit" /> &nbsp; &nbsp; &nbsp; <input type="reset" value="Reset" />
</p>
</form>
EOT;

return $CMSHTML;

}

function add_cat()
{
global $info;
$sesid = $_GET['ses'];
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Here you can add new link catagory.

<form name="addlink" id="addlink" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&amp;id=manage_links&amp;item=5" method="POST">
Catagory Name: <input type="text" name="cat" /> <br />
About Catagory: <textarea name="about" cols="70" rows="10"></textarea><br />
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

function link_success()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Link added!!

EOT;

return $CMSHTML;

}

function cat_success()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Catagory added!!

EOT;

return $CMSHTML;

}

function link_delete_complete()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
The link has now been deleted.

EOT;

return $CMSHTML;

}

function cat_delete_complete()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
The catagory has now been deleted.
<p>
NOTE: Any links in the deleted catagory are still in the database, but are not active.
</p>
EOT;

return $CMSHTML;

}

function link_success_edit()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Link edited!

EOT;

return $CMSHTML;

}

function cat_success_edit()
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Catagory edited!

EOT;

return $CMSHTML;

}

function cat_top($r)
{

$CMSHTML = '';

$CMSHTML .= <<<EOT
Catagory: {$r['cat']} <br />
About: {$r['about']} <p>

EOT;

return $CMSHTML;

}


function list_links($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT


Name: {$row['name']} (<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=6&amp;linkid={$row['id']}">Edit</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=10&amp;linkid={$row['id']}">Delete</a> )<br />

EOT;

return $CMSHTML;

}

function add()
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT


<p><a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=2">Add Link</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=3">Add Catagory</a> </p>

EOT;

return $CMSHTML;

}


function list_cats($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT


Catagory: <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=1&amp;catid={$row['id']}">{$row['cat']}</a> (<a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=7&amp;catid={$row['id']}">Edit</a> || <a href="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=11&amp;catid={$row['id']}">Delete</a> )<br />

EOT;

return $CMSHTML;

}

function edit_link($row = '', $cat)
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
<form name="edit_link" id="edit_link" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=8" method="POST">
<input type="hidden" value="{$row['id']}" name="id" />
Name: <input type="text" value="{$row['name']}" name="name" /> <br />
Link: <input type="text" name="link" value="{$row['link']}" />
Catagory: <select name="catid">
EOT;

$num = count($cat);

  for ($i = 1; $i <= $num; $i++)
  {
  
    if ($cat[$i - 1]['id'] == $row['catid'])
    {
$CMSHTML .= <<<EOT
<option value="{$cat[$i - 1]['id']}" selected="selected">{$cat[$i - 1]['cat']}</option>
EOT;
    }
    
    else
    {
$CMSHTML .= <<<EOT
<option value="{$cat[$i - 1]['id']}">{$cat[$i - 1]['cat']}</option>
EOT;
  }
  
  }

$CMSHTML .= <<<EOT
</select>
<p>
<input type="submit" value="Submit" />
</p>
</form>
EOT;

return $CMSHTML;

}

function edit_cat($row = '')
{
global $info;
$sesid = $_GET['ses'];
$CMSHTML = '';

$CMSHTML .= <<<EOT
<form name="edit_link" id="edit_link" action="{$info['base_url']}/admin.php?ses={$sesid}&amp;do=3&id=manage_links&item=9" method="POST">
<input type="hidden" value="{$row['id']}" name="id" />
Catagory Name: <input type="text" value="{$row['cat']}" name="cat" /> <br />
About: <textarea name="about" cols="70" rows="10">{$row['about']}</textarea>
<p>
<input type="submit" value="Submit" />
</p>
</form>
EOT;

return $CMSHTML;

}



}
?>