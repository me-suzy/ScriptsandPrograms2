<?php session_start(); ?>
<html>
<head>
  <title>qliteNews Panel :: r2xDesign.net</title>
  <link href="style.css" type="text/css" rel="stylesheet"/>
</head>
<body>
  <?php
    if (isset($_SESSION['username']) && isset($_SESSION['password'])) { 
      include("controlpanel.php");
    } 
    else {
      include("login.php");
    }
?>
</body>
</html>