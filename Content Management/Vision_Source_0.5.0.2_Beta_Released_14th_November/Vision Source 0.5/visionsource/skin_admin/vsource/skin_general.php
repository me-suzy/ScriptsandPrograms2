<?php

if ( ! defined( 'ACP' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

class skin_general
{

function top_header()
{
global $info;
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
You can edit your general config here.
EOT;

return $CMSHTML;

}

function edit_form($info = '')
{
global $info;
	
$CMSHTML = '';
$sesid 	 = $_GET['ses'];

$CMSHTML .= <<<EOT
<table width="75%">
<form name="general_config" id="general_config" action="admin.php?ses={$sesid}&amp;do=3&amp;id=general&amp;item=2" method="POST">
<tr>
<td>DB Host:</td>
<td><input type="text" name="dbhost" value="{$info['dbhost']}" /></td>
</tr>
<tr>
<td>DB User:</td>
<td><input type="text" name="dbuser" value="{$info['dbuser']}" /></td>
</tr>
<tr>
<td>DB Pass:</td>
<td><input type="text" name="dbpass" value="{$info['dbpass']}" /></td>
</tr>
<tr>
<td>DB Name:</td>
<td><input type="text" name="dbname" value="{$info['dbname']}" /></td>
</tr>
<tr>
<td>Prefix:</td>
<td><input type="text" name="prefix" value="{$info['prefix']}" /></td>
</tr>
<tr>
<td>Full URL Address:</td>
<td><input type="text" name="base_url" value="{$info['base_url']}" /></td>
</tr>
<tr>
<td>Title:</td>
<td><input type="text" name="title" value="{$info['title']}" /> </td>
</tr>
<tr>
<td>Admin Email:</td>
<td><input type="text" name="email" value="{$info['email']}" /> </td>
</tr>
<tr>
<td>Number of news articles:</td>
<td><input type="text" name="news_limit" value="{$info['news_limit']}" /></td>
</tr>
<tr>
<td colspan="2"><input type="submit" Value="Submit" name="submit">
</tr>
</table>
</form>

EOT;

return $CMSHTML;

}

function error($msg)
{
global $info;
	
$CMSHTML = '';
	
$CMSHTML .= <<<EOT
Im sorry, an error has occurred. The error message was: $msg
<p><a href="javascript:back(-1)">Please click here to go back</a></p>
EOT;

return $CMSHTML;

}



}
?>