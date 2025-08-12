<?php
  switch ($HTTP_GET_VARS['step']) {
    case '2':
            $page_contents = 'install2.php';
    break;
    case 'export_db':
            $page_contents = 'export_db.php';
    break;
    default:
        $page_contents  = 'install.php';
    break;
  }
  require('templates/main.php');
?>