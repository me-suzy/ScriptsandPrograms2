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
  ob_start();
  
  include("templates/top.php");
  include_once("includes/functions.php");

  $what = @$_POST["what"];
  
  if($what == "")
  $what = @$_GET["what"];
  
  switch($what)
  {
    case "doLogin":
      ProcessLogin();
	  break;
	case "logout":
	  ProcessLogout();
	  break;
	default:
	  ShowLoginForm();
  }
  
  function ShowLoginForm()
  {
  ?>  
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">MailWorksPro Admin Login</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
              Please complete the form below to login to the MailWorksPro admin area.			
			</span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">		  
		  </td></tr>
		  <tr><td>
		    <form action="login.php" method="post">
			  <input type="hidden" name="what" value="doLogin">
			  <table width="95%" align="center" border="0">
			    <tr>
				  <td width="50%" valign="top">
				    <br>
				    <table width="100%" border="0">
					  <tr>
					    <td width="30%">
						  <span class="BodyText">Username:</span>
						</td>
						<td width="70%">
						  <input type="text" name="userName">
						</td>
					  </tr>
					  <tr>
					    <td width="30%">
						  <span class="BodyText">Password:</span>
						</td>
						<td width="70%">
						  <input type="password" name="password">
						</td>
					  </tr>
					  <tr>
					    <td width="30%">
						  
						</td>
						<td width="70%">
						  <input type="submit" name="submit" value="Process Login »">
						</td>
					  </tr>
					</table>
				  </td>
				  <td width="50%">
				    <img src="images/people.gif">
				  </td>
				</tr>
			  </table>
			</form>
		  </td></tr>
		</table>
	<?php
	}
	
	function ProcessLogin()
	{
	  global $adminUser;
	  global $adminPass;
	  
	  $u = @$_POST["userName"];
	  $p = @$_POST["password"];
	  $err = "";
	  
	  if($u == "")
	    $err .= "<li>You forgot to enter your username</li>";
		
	  if($p == "")
	    $err .= "<li>You forgot to enter your password</li>";
	  
	  // Display the page header
	  ?>
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">MailWorksPro Admin Login</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info"><br>
            <?php
			
			  if($err != "")
			  {
			    echo "Some errors occured while trying to log you in: <ul>$err</ul>";
				echo "<p style='margin-left:10'><a href='login.php'><< Go Back</a><br>&nbsp;";
			  }
			  else
			  {
			    // Is this a valid user?
				doDbConnect();
				
				$validUser = ($u == $adminUser) ? true : false;
				$validPass = ($p == $adminPass) ? true : false;
				
				$isValid = $validUser && $validPass;
				
				if($isValid == true)
				{
				  setcookie("auth", true);
				  echo "You have successfully logged into the MailWorksPro admin area";
				  echo "<p style='margin-left:10'><a href='index.php'>Continue >></a><br>&nbsp;";
				}
				else
				{
				  echo "Your login credentials were invalid or do not exist in the database";
				  echo "<p style='margin-left:10'><a href='login.php'><< Go Back</a><br>&nbsp;";
				}
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
	  <?php
	}
	
	function ProcessLogout()
	{
	  setcookie("auth", true, time() - 3600*60);
	  JSRedirectTo("index.php", 0);
	}
	?>
		
<?php include("templates/bottom.php"); ?>
