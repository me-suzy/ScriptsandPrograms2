<?php

   // don't cache admin pages
   $cachetimeout = -1;

   // Load config, then return to present directory
   $pwd = getcwd();
   chdir('..');
   require_once('./config.php');
   chdir($pwd);

   // Check if home config
   if ($ary['section_id'] == $_PSL['home_section_id']) {
      $ary['section_id'] = '';
   }

?>