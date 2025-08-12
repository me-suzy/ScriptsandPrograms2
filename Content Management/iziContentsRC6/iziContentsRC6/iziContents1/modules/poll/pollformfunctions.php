<?php
/***************************************************************************

 pollformfunctions.php
 -------------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

function SubFormHeader($formname)
{
   global $_SERVER;

   ?>
   <form name="<?php echo $formname; ?>" action="<?php echo $_SERVER["PHP_SELF"]; if ($_SERVER["QUERY_STRING"] != '') { echo '?'.$_SERVER["QUERY_STRING"]; } ?>" method="post" enctype="multipart/form-data">
   <?php
} // function SubFormHeader()


function SubFormFooter()
{
   global $EZ_SESSION_VARS, $_POST;
   ?>
   <input type="hidden" name="topgroupname" value="<?php echo $_POST["topgroupname"]; ?>">
   <input type="hidden" name="groupname" value="<?php echo $_POST["groupname"]; ?>">
   <input type="hidden" name="subgroupname" value="<?php echo $_POST["subgroupname"]; ?>">
   <input type="Hidden" name="link" value="<?php echo $_POST["link"]; ?>">
   <input type="Hidden" name="catcode" value="<?php echo $_POST["catcode"]; ?>">
   <input type="Hidden" name="ezSID" value="<?php echo $_POST["ezSID"]; ?>">
   </form>
   <?php
} // function SubModFormFooter()

?>
