<?php
include("common.php");
if (session_is_registered("whossession")) {
    if ($_SESSION['who'] == "admin" || $_SESSION['who'] == "user" || $_SESSION['who'] == "moderator") {
        $_SESSION['who'] = "";
        session_destroy();
        header('Location:index.php');
    } 
    header('Location:index.php');
} 
header('Location:index.php');
// CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
?>
