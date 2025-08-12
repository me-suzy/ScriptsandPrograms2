<?php
session_start();
include("../inc/auth.php");
include("../inc/redir.php");
if($user == $_POST['username'] && $pass == $_POST['password']) {

    $_SESSION['username'] = $user;
    $_SESSION['password'] = $pass;  

header('Refresh: 3; URL=../index.php');
smsg('Welcome ' .$user. ', you are being redirected to the control panel.<br />If the page doesn&#39;t refresh, <a href="../index.php">follow this link</a>');
} else { 
smsg("Invalid username / password ! <br />click <a href='../admin.php'>here<a/> to return to the form."); 
}

?> 