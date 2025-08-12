<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();
empty_trash($_SESSION['userid']);

header("Location: $base_url/messages.php?folder=inbox");
exit();
?>