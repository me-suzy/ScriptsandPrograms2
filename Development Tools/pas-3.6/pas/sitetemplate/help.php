<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /*****
   * General help page. 
   * Parse the docs directory and add link to each of them.
   *
   * @package PASSiteTemplate
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0
   */

  include_once("config.php") ;
  $pageTitle = "Help page" ;
  include("includes/header.inc.php");
?>
   Packages Documentation : <br><br>
<?php 
         $dirdoc = dir($conx->getProjectDirectory()."/docs");
            while ($entry = $dirdoc->read()) {
//                if (strlen($entry) > 2 && ereg("[0-9]$", $entry)) {
                if (strlen($entry) > 2 && !ereg("[php|html|txt]$", $entry)) {
                    $a_docs[$entry] = $entry ;
                }
            }
            ksort($a_docs) ;
            while (list($entry, $modulename) = each($a_docs)) {
                echo "\n<a href=\"docs/$entry\">$modulename</a><br>";
            }
            $dirdoc->close();
?>




<?php   
   include("includes/footer.inc.php") ;
?>