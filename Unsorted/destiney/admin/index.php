<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script language="javascript" type="text/javascript">if(top.location != self.location){top.location = self.location;}</script>
<title>Admin</title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<frameset cols="150,*">
<frame src="$base_url/admin/menu.php" name="menu" scrolling="no" />
<frame src="$base_url/admin/main.php" name="main" />
</frameset>
</html>
EOF;

echo $final_output;

?>