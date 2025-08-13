<?php include("templates/top.php"); ?>
<?php include_once("includes/functions.php"); ?>
<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
  $auth = @$_COOKIE["auth"] == 1 ? true : false;

  // Display the page header
	  ?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">MailWorksPro Admin Area</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info"><br>
            <?php
			
			  if(!isLoggedIn())
			  {
			    header("location: login.php");
			  }
			  else
			  {
				echo "You are currently logged in to the MailWorksPro admin area. Please choose an option from the menu down the left side of the page to get started.";
		        echo "<p style='margin-left:10'><a href='login.php?what=logout'>Logout >></a><br>&nbsp;";
			  }
			?>			
			</span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">		  
		  </td></tr>
		  <tr><td>
	  <?php
	  // Finish off the page
	  ?>
		  </td></tr>
		</table>
		
<?php include("templates/bottom.php"); ?>
