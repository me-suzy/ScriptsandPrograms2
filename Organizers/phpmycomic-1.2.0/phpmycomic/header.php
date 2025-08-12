<?php

  // INCLUDE TEMPLATE ENGINE AND CONFIG FILE
  include("./class.TemplatePower.inc.php");
  include("./config/config.php");
 
  // CREATE NEW TEMPLATE OBJEKT
  $tpl = new TemplatePower("themes/$themes/tpl/body.tpl");
  $tpl->assignGlobal("pmc_url", $siteurl);

  // INCLUDE SUB TEMPLATES
  if($_SESSION['loggedin'] == 'yes')
     {

        $tpl->assignInclude("sitemenu", "themes/$themes/tpl/menuin.tpl");

     } else {

        $tpl->assignInclude("sitemenu", "themes/$themes/tpl/menuout.tpl");

     }

  $tpl->assignInclude("footer", "themes/$themes/tpl/footer.tpl");

  // OPEN A MYSQL CONNECTION
  mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
  mysql_select_db($sql['data']) or die("Unable to find DB");

?>