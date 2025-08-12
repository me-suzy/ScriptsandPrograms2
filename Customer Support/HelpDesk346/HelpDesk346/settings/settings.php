<?php
	//Revised May 20, 2005
	//Revised by Jason Farrell
	//Revision Number 3
	$path = getcwd();
	chdir('..');
	include("checksession.php");
	include_once "./includes/settings.php";
	include_once "./classes/user.php";
	chdir($path);
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	$user = unserialize($_SESSION['enduser']);
	if(isset($_POST['Submit']))
	{
		if ($user->get('securityLevel', 'intval') == 2) {
		  	$OBJ->set('result_page', $_POST['res_page'], 'intval');
		  	$OBJ->set('hdticket', $_POST['hdtn1'], 'intval');
		  	$OBJ->set('hdemail', $_POST['hdemail1'], 'intval');
		  	$OBJ->set('email_type', $_POST['etype'], 'intval');
		  	$OBJ->set('req_image', $_POST['reqimg'], 'intval');
		  	$OBJ->set('ticketAccessModify', $_POST['ticketAccess'], 'intval');
		  	$OBJ->set('show_kb', $_POST['show_kb'], 'intval');
		  	$OBJ->set('allow_enduser_reg', $_POST['allow_enduser'], 'intval');
		  	$OBJ->set('user_defined_priorities', $_POST['define_priority'], 'intval');
		  	$OBJ->set('navigation', $_POST['navigation']);
		  	$OBJ->set('helpdesk', $_POST['helpdesk']);
		  	$OBJ->set('ticket_lookup', $_POST['ticket_lookup']);
		  	$OBJ->set('hd_from', $_POST['hd_from'], 'mysql_real_escape_string');
		}
		else {
			$OBJ->set('result_page', $_POST['res_page'], 'intval');
		}
		$OBJ->commit();
		$_SESSION['obj'] = serialize($OBJ);		//make sure to write the new settings to the session
	    $_SESSION["sess_errormsg"]="Settings Updated Successfully";
	  }
	?>
	<html>
	<head>
	<title>Helpdesk Settings</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
	</head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
	<table border="1">
	  <tr>
	      <td> 
	      <?php
	          		$ppath = '../';
	          		if ($OBJ->get('navigation') == 'B') {
	          			include_once '../dataaccessheader.php';
	          		}
	          		else {
	          			include_once '../textnavsystem.php';
	          		}
	          	?>
	            <br/><a href="../actmgt.php">Back to help desk control panel.</a></td>
	        </tr>
	        <tr> 
	          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top">
			  <form name="form1" method="post" action="settings.php">
	              <p>&nbsp;</p>
	              <table width="90%" border="0" align="center">
	                <tr> 
	                  <td height="20" colspan="2"><div align="center"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
	                      <?php echo (isset($_SESSION['sess_errormsg'])) ? $_SESSION["sess_errormsg"] : ''; 
							$_SESSION["sess_errormsg"]=""; ?></strong></font></div></td>
	                </tr>
	                <tr> 
	                  <td height="20">&nbsp;</td>
	                  <td height="20">&nbsp;</td>
	                </tr>
	                <tr> 
	                  <td width="48%" height="20">Number of results per page</td>
	                  <td width="52%" height="20">
	                  	<SELECT  name=res_page size=1 id="res_page" class="formfield">
	                    <?php
	    					for($i = 5; $i <= 50; $i+=5)
	    					{
								//If no day is selected then select the current day
		 						$selected = $i == $OBJ->get('result_page', 'intval') ? " selected " : "";
								$result .= "<option value=" . $i . $selected . ">" . $i . "</option>";	//dynamically adds options
							}
							echo $result;
						?>
	                    </SELECT>
	                  </td>
	                </tr>
	                <?php
	                	if ($user->get('securityLevel') == 2)
	                	{
	                ?>
	                <tr>
	                	<td>Navigation Style</td>
	                	<td>
	                		<select name="navigation" size="1">
	                			<option value='B' <? if($OBJ->get('navigation') == 'B') echo "selected"; ?>>Banner</option>
	                			<option value='T' <? if($OBJ->get('navigation') == 'T') echo "selected"; ?>>Text</option>
	                		</select>
	                	</td>
	                </tr>
	                <tr>
	                	<td>Security</td>
	                	<td>
	                		<select name="helpdesk" size="1">
	                			<option value='S' <? if($OBJ->get('helpdesk') == 'S') echo "selected"; ?>>Secure</option>
	                			<option value='O' <? if($OBJ->get('helpdesk') == 'O') echo "selected"; ?>>Open</option>
	                		</select>
	                	</td>
	                </tr>
	                <tr> 
	                  <td height="20">Display Help Desk Ticket Number</td>
	                  <td height="20"><select name="hdtn1">
	                      <option value=1 <? if($OBJ->get('hdticket', 'intval') == 1) echo "selected"; ?>>On</option>
	                      <option value=0 <? if($OBJ->get('hdticket', 'intval') == 0) echo "selected"; ?>>Off</option>
	                    </select></td>
	                </tr>
	                <tr> 
	                  <td height="20">Email Handling</td>
	                  <td height="20"><select name="hdemail1">
	                      <option value=1 <? if($OBJ->get('hdemail', 'intval') == 1) echo "selected"; ?>>On</option>
	                      <option value=0 <? if($OBJ->get('hdemail', 'intval') == 0) echo "selected"; ?>>Off</option>
	                    </select></td>
	                </tr>
	                <tr> 
	                  <td height="20"><div align="left">Email Type</div></td>
	                  <td height="20"><select name="etype">
	                      <option value=1 <? if($OBJ->get('email_type', 'intval') == 1) echo "selected"; ?>>Plain 
	                      Text Email</option>
	                      <option value=0 <? if($OBJ->get('email_type', 'intval') == 0) echo "selected"; ?>>HTML 
	                      Based Email</option>
	                    </select></td>
	                </tr>
	                <tr> 
	                  <td height="20">Help Desk Request Status Image</td>
	                  <td height="20"><select name="reqimg">
	                      <option value=1 <? if($OBJ->get('req_image', 'intval') == 1) echo "selected"; ?>>On</option>
	                      <option value=0 <? if($OBJ->get('req_image', 'intval') == 0) echo "selected"; ?>>Off</option>
	                    </select></td>
	                </tr>
	                <tr> 
	                  <td height="20">Ticket Access Setting:&nbsp;</td>
	                  <td height="20"> <select name="ticketAccess" size="1">
	                      <option value="1"<?php echo ($OBJ->get('ticketAccessModify', 'intval') == 1) ? ' selected' : ''; ?>>Yes</option>
	                      <option value="0"<?php echo ($OBJ->get('ticketAccessModify', 'intval') == 0) ? ' selected': ''; ?>>No</option>
	                    </select> </td>
	                </tr>
	                <tr> 
	                  <td height="20">Show User Knowledge Base:&nbsp;</td>
	                  <td height="20"> <select name="show_kb" size="1">
	                      <option value="1"<?php echo ($OBJ->get('show_kb', 'intval') == 1) ? ' selected' : ''; ?>>Yes</option>
	                      <option value="0"<?php echo ($OBJ->get('show_kb', 'intval') == 0) ? ' selected': ''; ?>>No</option>
	                    </select> </td>
	                </tr>
	                <tr> 
	                  <td height="20">Allow End User Registration:&nbsp;</td>
	                  <td height="20"> <select name="allow_enduser" size="1">
	                      <option value="1"<?php echo ($OBJ->get('allow_enduser_reg', 'intval') == 1) ? ' selected' : ''; ?>>Yes</option>
	                      <option value="0"<?php echo ($OBJ->get('allow_enduser_reg', 'intval') == 0) ? ' selected': ''; ?>>No</option>
	                    </select> </td>
	                </tr>
	                <tr> 
	                  <td height="20">Allow User Defined Priorities:&nbsp;</td>
	                  <td height="20"> <select name="define_priority" size="1">
	                      <option value="1"<?php echo ($OBJ->get('user_defined_priorities', 'intval') == 1) ? ' selected' : ''; ?>>Yes</option>
	                      <option value="0"<?php echo ($OBJ->get('user_defined_priorities', 'intval') == 0) ? ' selected': ''; ?>>No</option>
	                    </select> </td>
	                </tr>
	                
	                <tr> 
	                  <td height="20">Public Ticket Lookup:&nbsp;</td>
	                  <td height="20"> <select name="ticket_lookup" size="1">
	                      <option value="1"<?php echo ($OBJ->get('ticket_lookup', 'intval') == 1) ? ' selected' : ''; ?>>Yes</option>
	                      <option value="0"<?php echo ($OBJ->get('ticket_lookup', 'intval') == 0) ? ' selected': ''; ?>>No</option>
	                    </select> </td>
	                </tr>
	                
	                <tr>
	                	<td height="20">Helpdesk Email Address:&nbsp;</td>
	                	<td><input type="text" name="hd_from" size="20" maxlength="50" value="<?php echo $OBJ->get('hd_from', 'stripslashes'); ?>" /></td>
	                </tr>
	                
	                <tr> 
	                  <td height="20">&nbsp;</td>
	                  <td height="20">&nbsp;</td>
	                </tr>
	                <!--<tr> 
	                  <td height="20">Email Notification when trouble ticket update</td>
	                  <td height="20"><select name="hdemailup">
					    <option value=1 <? if($hdemailup1==1) echo "selected"; ?>>On</option>
	                      <option value=0 <? if($hdemailup1==0) echo "selected"; ?>>Off</option>
						 </select></td>
	                </tr>
	                <tr> 
	                  <td height="20">Email Address</td>
	                  <td height="20"><input type="text" name="email_addr" size="20" maxlength="255" value="<?php echo $email_addr; ?>" /></td>
	                </tr>-->
	                <tr> 
	                  <td height="20" colspan="2"> <a href="emailWizard.php">Set Email 
	                    Notification Rules</a> </td>
	                </tr>
	                <?php
	                	}
	                ?>
	                <tr> 
	                  <td height="20" colspan="2"><div align="center"> 
	                      <input type="submit" name="Submit" value="Submit" class="button">
	                    </div></td>
	                </tr>
	              </table>
	              <p>&nbsp; </p>
	            </form>
	            <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
	              2005 Help Desk Reloaded<br>
	              <a href="www.helpdeskreloaded.com">Today's Help Desk Software for 
	              Tomorrows Problem.</a></font></p>
	      
	       </td>
	     </tr>
	</table>
	<map name="Map2">
	  <area shape="rect" coords="4,130,70,176" href="reportproblem.php">
	  <area shape="rect" coords="80,128,159,174" href="helpDeskAccessAllCalls.php">
	  <area shape="rect" coords="173,129,274,173" href="DataAccessSearch.php">
	  <area shape="rect" coords="292,126,375,177" href="ocm-first.php">
	  <area shape="rect" coords="384,128,447,174" href="search.php">
	  <area shape="rect" coords="454,128,544,169" href="DataAccess.php">
	</map>
	</body>
</html>
<?php mysql_close(); ?>