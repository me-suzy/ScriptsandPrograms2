<?php

	/*=====================================================================
    // $Id: save_current_tree.php,v 1.2 2005/04/03 06:30:09 carsten Exp $
    // copyright evandor media Gmbh 2004
    //=====================================================================*/

    // --- Standard Inclusions --------------------------------------
    include ("inc/pre_include_standard.inc.php");

	mysql_query ("UPDATE ".TABLE_PREFIX."user_details
	              SET current_tree='".$_REQUEST['current_tree']."' 
	              WHERE user_id='$user_id'");
	$error = mysql_error();
	logDBError (__FILE__, __LINE__, $error);

	if ($error == '') {
	   ?>
	   <script language=javascript>
		   var NavFrame  = parent.l4w_nav;
		   var container = NavFrame.document.getElementById("save_message");

		   function hide_again () {
				container.style.visibility="hidden";
		   }

		   container.style.visibility="visible";
		   setTimeout ("hide_again()", 1000);
	   </script>
	   <?php
	   die ("&nbsp;");
	}

?>

  <script language=javascript>
		  alert ("EXECUTION ERROR!!!");
  </script>
