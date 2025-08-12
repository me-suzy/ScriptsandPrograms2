<?php
include("./config.php");
include("$include_path/common.php");
unset($_SESSION['admin']);
unset($_SESSION['cq']);
header("Location: $base_url/admin/");
exit();
?>
