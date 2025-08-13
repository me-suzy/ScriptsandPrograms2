<?php
 // file: authenticate.php
 // desc: basic authentication for Photoseek
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

 if (!defined(CONFIG)) include ("config.inc");
 if (!defined(API))    include ("API.inc");

 // authenticate the user
 photoseek_authenticate ();

 include ("searchform.php"); // include the form
 closeDatabase ();
?>