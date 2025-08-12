<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

include("./includes/config.php");
include("./includes/functions/fns_std.php");
include("./includes/functions/fns_db.php");
include('./includes/settings.php');

db_connect();

$DOC_TITLE = "Login";

$login_success = false;

if($_POST['submit']){
	if(!strlen($msg = validate_login_form($_POST))){
		$login_success = doLogin($_POST, $_SESSION);	
			if($login_success){
		    // check the users active status
			if(!is_active($_SESSION['user_id'])){
			  $_SESSION = array();
			  redirect('user_not_active.php');
			}
			/* THIS FEATURE NOT ENABLED IN THIS PACKAGE
			record_tracking_action($_SESSION['user_id'],'login');
			if(!is_online($_SESSION['user_id'])){	// currently listed in db as online?
				record_tracking_action($_SESSION['user_id'],'online');
			}
			*/
			if($_SESSION['admin']) { redirect('admin/'); } else { redirect('private.php'); }
	    } else {
		    $msg = "Login Error: Please try again.";	
	    }//end if login success
	}//end if form validated
} else { // Not submitted, destroy any session in place.
     $_SESSION = array();
}//end if submitted

include("./templates/header.php");
?> 
<span class='portfolio_category_title'>Private Viewing Login</span>
<div class="error_mark"><?php print $msg; ?></div>
<p>To access your private viewing area enter a username and password below:</p>
<form name="form1" method="post" action="<?= $PHP_SELF ?>">
  <table width="300">
    <tr> 
      <td align="right" class="form_label">Username:</td>
      <td> 
        <input type="text" name="username" value="<?php print $_POST['username'] ?>">
      </td>
    </tr>
    <tr> 
      <td align="right" class="form_label">Password:</td>
      <td> 
        <input type="password" name="password">
      </td>
    </tr>
    <tr align="center"> 
      <td colspan="2" height="18"> 
        <input type="submit" name="submit" value="<?php print SUBMIT ?>">
      </td>
    </tr>
  </table>
</form>
<p>If you have forgotten your login information, please contact us.</p>
<?php
include("./templates/footer.php");

/*****************************************************
	FUNCTIONS
******************************************************/

function validate_login_form($frm)
{
	$str = "";
	if(	trim($frm['username']) === '' ||
		strlen(trim($frm['username'])) < 5 ||
		strlen(trim($frm['username'])) > 32){
		$str .= "Please enter between 5 and 32 characters for your Username<br>";
	}

	if(	trim($frm['password']) === '' ||
		strlen(trim($frm['password'])) < 8 ||
		strlen(trim($frm['password'])) > 32){
		$str .= "Please enter between 8 and 32 characters for your Password<br>";
		}				
  return $str;
}//end validate login

function doLogin($frm, &$s) 
{
	db_connect();
	$query = "SELECT u.username, u.name, u.id as user_id, u.cat_id    
			FROM " . PDB_PREFIX . "auth u
			WHERE u.username = '" . $frm['username'] . "' 
			AND u.password = '" . md5($frm['password']) . "'"; 

	if(db_num_rows($result = db_query($query)) > 0) {
        $row = db_fetch_array($result);
		/* Set some SESSION variables */				
		$s["user_id"] = $row["user_id"];
		$s["username"] = $row["username"];
		$s["name"] = $row["name"];
		$s['admin'] = ($row["user_id"] == '1')?'1':0;
		$s["authorized_portfolio_id"] = $row["cat_id"];
		return true;
	} else {
    return false;
  }// end if
}// end doLogin
?>
