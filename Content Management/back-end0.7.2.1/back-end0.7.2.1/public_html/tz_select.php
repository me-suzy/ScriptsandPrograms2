<?php

   require('./config.php');
   /*
    page_open(array('sess' => 'slashSess',
    'auth' => 'slashAuth',
    'perm' => 'slashPerm'));
    */
   slashhead(pslgetText('Select Time Zone'), pslgetText('Customization'));
   if ($_GET['submit']) {
      echo $_TZ['obj']->get_confirm_form(clean($_GET),
         $_PSL['rooturl'] .'?TZ=' . clean($_GET['region']),
         $_SERVER['PHP_SELF']);
   } else {
      echo $_TZ['obj']->get_select_form();
   }
   slashfoot();
?>