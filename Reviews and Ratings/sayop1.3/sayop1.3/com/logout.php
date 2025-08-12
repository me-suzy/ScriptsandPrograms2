<?php
include("../inc/redir.php");
session_start();
session_unset();
if(session_destroy()) {
smsg("<b>You have been logged out.</b><br />Now redirecting to login form... If the page doesn&#39;t refresh, <a href='../admin.php'>follow this link</a>");
header('Refresh: 3; URL=../admin.php');
}
?>