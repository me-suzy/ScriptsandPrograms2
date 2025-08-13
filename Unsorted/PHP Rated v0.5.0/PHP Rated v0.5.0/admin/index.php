<?

/*
 * $Id: index.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: login.php?$sn=$sid");

include("$include_path/common.php");
include("$include_path/$table_file");

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>pRated - Admin</title>
$styles
<script>if(top.location != self.location){top.location.href='$base_url/admin/index.php';}</script>
</head>
<frameset cols="150,*">
<frame src="$base_url/admin/menu.php?$sn=$sid" name="menu" scrolling="no" />
<frame src="$base_url/admin/main.php?$sn=$sid" name="main" />
</frameset>
</html>
EOF;

echo $content;

/*
 * $Id: index.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
