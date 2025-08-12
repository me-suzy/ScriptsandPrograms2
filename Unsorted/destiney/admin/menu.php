<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

if(isset($_SESSION['admin'])){
$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color">
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td class="regular">
<a href="$base_url/" target="_blank">$site_title</a>
<br>
<br>
<a href="$base_url/admin/main.php" target="main">Main Page</a>
<br>
<a href="$base_url/admin/new_images.php" target="main">New Images</a>
<br>
<a href="$base_url/admin/users.php" target="main">Edit Users</a>
<br>
<a href="$base_url/admin/mailing.php" target="main">Send Mail</a>
<br>
<a href="$base_url/admin/forums.php" target="main">Forums</a>
<br>
<a href="$base_url/admin/user_types.php" target="main">User Types</a>
<br>
<a href="$base_url/admin/image_types.php" target="main">Image Types</a>
<br>
<a href="$base_url/admin/update_counts.php" target="main">Update Counts</a>
<br>
<a href="$base_url/admin/password.php" target="main">Password</a>
<br>
<br>
<a href="$base_url/admin/phpinfo.php" target="main">My phpinfo()</a>
<br>
<br>
<a href="$base_url/admin/logout.php" target="_top">Logout</a>
<br>
<br>
</td>
</tr>
</table>
</body>
</html>
EOF;
}

echo $final_output;

?>