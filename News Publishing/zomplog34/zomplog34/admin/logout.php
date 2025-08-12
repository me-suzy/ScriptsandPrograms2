<?php

/* Written by Gerben Schmidt, http://scripts.zomp.nl */
include_once("functions.php");
include_once("config.php");
include('session.php');

checkLoggedIn("yes");

flushMemberSession();

header("Location: ../login.php");
?>