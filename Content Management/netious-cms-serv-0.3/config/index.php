<?

/* *****************************************************
CMS + service configuration script
****************************************************** */

if (!isset($action)) $action="0";

echo "
<html>
<title>Configuration script</title>
</head>
<body>
<center>
<h2>If you haven't created the DB yet, please do it now. Otherwise the script won't work.</h2><br />
<b>The configuration script inserts into it 3 tables: 'mycmsadmin', 'pages', and 'style'. If you later decide to create your own RSS feeds, two further tables, 'rsschannel' and 'rssitem' will be created (by the CMS scripts).</b><br /><br /><br />";

if ($action=="1") echo "<b style=\"color:red\">The passwords or e-mail addresses did not match!</b>";
echo "
<form action='configuration.php' method='post'>
<table width=600>
	<tr>
		<td>
		Host name (for the DB to connect):
		</td>
		<td>
		<input type=\"text\" name=\"host_name\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		DB User name:
		</td>
		<td>
		<input type=\"text\" name=\"user_name\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		DB Password:
		</td>
		<td>
		<input type=\"password\" name=\"password\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		Repeat the password:
		</td>
		<td>
		<input type=\"password\" name=\"password1\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		DB Name:
		</td>
		<td>
		<input type=\"text\" name=\"db_name\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		URL of the folder in which the service should be stored (e.g. 'http://www.mydomain.com/myservice')
		</td>
		<td>
		<input type=\"text\" name=\"this_url\" value=\"http://\" size=\"40\" />
		</td>
	</tr>
<tr>
		<td>
		Name of the directory in which the CMS (control panel) should be stored (do not create it):
		</td>
		<td>
		<input type=\"text\" name=\"cms_dir_name\" value=\"cms\" size=\"40\" />
		</td>
	</tr>


	<tr>
		<td>
		Password to the CMS:
		</td>
		<td>
		<input type=\"password\" name=\"cms_password\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		Repeat:
		</td>
		<td>
		<input type=\"password\" name=\"cms_password1\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		Your e-mail address (the configuration information will be sent to you after successful configuration):
		</td>
		<td>
		<input type=\"text\" name=\"email\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		Repeat e-mail:
		</td>
		<td>
		<input type=\"text\" name=\"email1\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		Include the Contact form in the service as default and use my e-mail as the contact e-mail (You can postpone it).
		</td>
		<td>
		<input type=\"checkbox\" checked=\"checked\" name=\"incl_contact\" size=\"40\" />
		</td>
	</tr>
	<tr>
		<td>
		<input type=\"checkbox\" checked=\"checked\" name=\"regme\" />
		</td>
		<td>
		<b>Store my e-mail address in your base.</b> This field is optional. However, if you want to be informed about our new products, scripts, or possibly errors in the released software you should leave this checked.
		</td>
	</tr>
	<tr>
		<td colspan=2 align=center>
		<input type=submit value=\"Configure the service\" />
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>
"


?>