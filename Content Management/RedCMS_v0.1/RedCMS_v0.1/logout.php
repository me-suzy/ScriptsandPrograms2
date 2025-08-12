<?php

  session_start();

  $l = "logout";

  include"top.php";

  $_SESSION['redIn'] = 'FALSE';
  $_SESSION['redUserID'] = '';
  $_SESSION['redUserLevel'] = '';
  $_SESSION['redThemePath'] = null;

  echo "You are now logged out.";

  echo '<meta http-equiv="Refresh" content="0;url=index.php">';

  include"bottom.php";

?>