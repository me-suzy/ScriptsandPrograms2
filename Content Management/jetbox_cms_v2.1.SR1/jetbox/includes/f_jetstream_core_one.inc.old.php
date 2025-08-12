<?
//In General the functions in this file are only used for the registration and login feature on the front-end
//All other functions for displaying contents can be found in f_general_functions.inc.php

//displays the actual login form
function login_form($login, $login_password, $annotation, $task, $error=''){
	global $site_title, $jetstream_url, $absolutepathfull, $absolutepath;
	?>
	<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
	<!--
	function login_focus()
	{
		if (document.login.login.value == '')
			document.login.login.focus();
		else
			document.login.login_password.focus();
	}
	//-->
	</SCRIPT><form name='login' method='post' action='<? echo $absolutepathfull; ?>view/webuser'>
			<table cellspacing=2 cellpadding=0 width="100%" border=0>
			<tr> 
				<td colspan=2 style="padding: 6px 0px 0px 0px">Jetstream offers you the possibility to register in order to receive mailing about relevant news and upcoming events.<br>
	<br></td>
			</tr>
			<tr> 
				<td colspan=2 bgcolor="#C5C7B5"><img height=1 src="<?echo $absolutepath?>images/clearpixel.gif" alt="" width=2></td>
			</tr>
			<tr> 
				<td><img height=22 alt="" hspace=3 src="<?echo $absolutepath?>images/lb_pijl.gif" width=22 vspace=3></td>
				<td width="100%"><b>
				<?
				if ($error==''){
					echo "Please login.";
				}
				else{
					echo "<font color=\"#880000\">".$error."</font>";
				}
				?>
				</b></span><br>
			</td>
			</tr>
			<tr> 
				<td colspan=2><img height=10 src="<?echo $absolutepath?>images/clearpixel.gif" alt="" width=2></td>
			</tr>
			<tr><td valign=top></td>
				<td valign=top width="100%">
					<table width="100%" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td colspan=5>
								<?
								if($annotation!=''){
									echo "<span class='install'>". $annotation."</span><br>";
								}
								else{
									echo "<span class='install'>Enter your username and password</span><br>";
								}
								?>
							</td>
						</tr>
				<TR>
					<TD width="13%"><IMG height=2 src="images/clearpixel.gif" alt="" width=120></TD>
          <TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
          <TD width="0%"><IMG height=2 src="images/clearpixel.gif" alt="" width=5></TD>
          <TD width="86%"><IMG height=2  src="images/clearpixel.gif" alt="" width=2></TD>
        </TR>
				<TR>
					<TD width="13%"></TD>
          <TD width="1%"><IMG height=6 src="images/clearpixel.gif" alt="" width=15></TD>
          <TD  colSpan=2>
					</TD>
        </TR>
				<TR>
					<TD noWrap width="13%">Username:</TD>
          <TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
          <TD width="0%"><IMG height=2  src="images/clearpixel.gif" alt="" width=5></TD>
          <TD width="86%">      
            <INPUT size=14 value="<?echo $login;?>" name=login>
				</TD>
        </TR>
				<TR>
					<TD width="13%"></TD>
          <TD width="1%"><IMG height=6 src="images/clearpixel.gif" alt="" width=15></TD>
          <TD colSpan=2>
					</TD>
        </TR>
				<TR>
					<TD noWrap width="13%">Password:</TD>
          <TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
          <TD width="0%"><IMG height=2 src="images/clearpixel.gif" alt="" width=5></TD>
          <TD width="86%"><INPUT type=password size=14 name=login_password></TD>
        </TR>
				<TR>
					<TD width="13%"></TD>
          <TD width="1%"><IMG height=6 src="images/clearpixel.gif" alt="" width=15></TD>
          <TD colSpan=2>
					</TD>
        </TR>
        <TR>
          <TD noWrap colspan="4">
           <input type='submit' value='Login'>
          </TD>
        </TR>
					<?
				if($_REQUEST["login"]<>'' && $_REQUEST["login_password"]<>''){
					?>
				<tr>
				<td valign='bottom' colspan=5><span class='tab-s'><br><b>I forgot my password for account: <?echo $_REQUEST["login"]?></b></span><br><span class='install'><a href="<? echo $absolutepathfull;?>view/webuser/task/sendpw/login/<?echo $_REQUEST["login"]?>">Please email my password.</a><br>The email will be sent to the address registrated on this account.</span><br>
				</td></tr>
				<?
				}
					?>
				</table>
				</form>
				<script language='JavaScript' type="text/javascript">
				<!--
					login_focus();
				// -->
				</script>
    </td>
  </tr>
	<!--
  <TR><TD colSpan=2><IMG height=30 src="images/clearpixel.gif" alt="" width=2></TD></TR>
	<tr><td colspan=2 bgcolor="#C5C7B5"><img height=1 src="<?echo $absolutepath?>images/clearpixel.gif" alt="" width=2></td></tr>
	<tr><td><img height=22 alt="" hspace=3 src="<?echo $absolutepath?>images/button_pijl.gif" width=22 vspace=3></td><td><b>Registration</b> is a 2 step process. You may stop your registration at any step.</td></tr>
	<tr><td colspan=2><img height=10 src="<?echo $absolutepath?>images/clearpixel.gif" alt="" width=2></td></tr>
	<tr><td valign=top></td><td valign=top width="100%">
		<table width="100%" border="0" cellspacing="2" cellpadding="0">
		<tr><td><a href="<? echo $absolutepathfull; ?>view/webuser/task/register">Go to the registration form.</a></td></tr>
	  </TABLE>
    </td>
  </tr>
	-->
</table>
	<?
}

//displays the login screen, invoced from auth.inc.php
function login_screen($screentype=''){
	global $site_title, $jetstream_url, $task, $annotation;
	if ($screentype=="expire"){
		$annotation="Enter your username and password.";
	}
	elseif($screentype=="ended"){
		$annotation="You succesfully logged out.";
	}
	login_form($login, $login_password, $annotation, $task);
}

//loggedin function with workflow support
function loggedin_workflow($uid=''){
	global $userright, $currentstatus, $primarykey, $$primarykey, $status, $generallanguage, $wf, $thisfile, $absolutepath;
	general_date_process();
	$_REQUEST["$primarykey"]=$_SESSION["uid"];
	if ($_SESSION["uid"]) {
		switch($_REQUEST["task"]) {
			case 'editrecord'					: general_form('edit', '', ''); break;
			case 'updateedit'					: general_process('edit'); break;
			case 'updatereedit'				: general_process('edit'); break;
			default :
			?>
			<table cellspacing=2 cellpadding=0 width="100%" border=0>
				<tr> 
					<td width="100%" style="padding: 6px 0px 0px 0px" colspan=2><h4>You are logged in.</h4></td>
				</tr>
				<tr> 
					<td colspan=2><img height=10 src="<?echo $absolutepath?>images/clearpixel.gif" alt="" width=2></td>
				</tr>
				<tr><td valign=top></td>
					<td valign=top width="100%">
						<table width="100%" border="0" cellspacing="2" cellpadding="0">
							<tr>
								<td><b><?echo "<a href=\"".$thisfile."/task/editrecord\">I want to edit my account.</a>";?><br>
								</b>or<b><br>
								<?echo "<a href=\"".$thisfile."/task/logout\">I want to logout.</a>";?>
								</b></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?
			break;
		}
	}
	else{
		switch($_REQUEST["task"]) {
			case 'createrecord'				: general_form('create', '', ''); break;
			case 'updatecreate'				: general_process('create'); break;
			case 'updaterecreate'			: general_process('create'); break;
			default		: general_form('create', '', ''); break;
		}
	}
}

//deletes record with wordkflow support
//deletes records from struct table
function general_deleterecord() {
	global $tablename, $primarykey, $container_id, $wf ;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT;
	if ($fileupload==true && $storemethod=='hd') {
		$r = mysql_query("SELECT $filenamefield from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'");
		if ($array=mysql_fetch_array($r)) {
			$todeletefilename=$array[$filenamefield];
		}
	}
	$q = "DELETE from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'";
	$r = mysql_query($q);
	$error1 = mysql_error();
	if ($wf){
		$q = "DELETE from struct WHERE content_id ='" . $_REQUEST[$primarykey] ."' AND container_id='".$container_id."'";
		$r = mysql_query($q);
		$error2 = mysql_error();
	}
	if ($error) {
		listrecords("\nError deleting: $error1 <br> $error2", "error");
	}
	else {
		if ($fileupload==true) {
			if ($BASE_ROOT<>'') {
		    $basedir=$_SERVER['DOCUMENT_ROOT']."/".$BASE_ROOT;
			}
			$full_localfilename = "$basedir/$todeletefilename";
			if (file_exists($full_localfilename)) {
				unlink($full_localfilename);			    
			}
		}
		listrecords("\nDeletion completed.", "notify");
	}
}

//displays general form
function general_form($action, $error='', $blurbtype='error') {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $wf;
	global $container_id, $generalconfig, $fileupload, $absolutepath;
	errorbox ($error, $blurbtype);
	if ($fileupload==true) {
	    $formdisable= "onsubmit=\"this.elements['statusactionsave'].disabled='disabled';\"";
	}
	if ($action == 'edit') {
		$formrarray= mysql_fetch_array(mysql_query("SELECT * FROM $tablename WHERE $primarykey='" . $_REQUEST[$primarykey] ."'"));
	}
	reset($records);
	while (list($var, $val) = each($records)) {
		$value=$val[0];
		$formelement=$val[1];
		$display=$val[2];
		if ($action == 'reedit' || $action == 'recreate') {
			$$value = $_REQUEST[$value];
			//$cellvalue = eregi_replace("<br>","",$cellvalue);
		}
		if ($action == 'edit') {
			$$value = $formrarray[$value];
			//$cellvalue = eregi_replace("<br>","\n",$cellvalue);
		}
		$cellvalue=stripslashes(stripslashes(stripslashes($cellvalue)));
		//general_formelement($val,$formrarray, $action);
	}
	if ($action=='reedit') {
		$passwordold= $_POST["passwordold"];
	}
	else{
		$passwordold=$user_password;
	}
	if($newsmail=='1'){
		$newsmailcheck='checked';
	}
	if($eventmail=='1'){
		$eventmailcheck='checked';
	}
	echo "\n<FORM enctype=\"multipart/form-data\" ACTION=\"".  $thisfile. "\" METHOD=\"POST\" name=\"mainform\" $formdisable>";
	?>
	<table cellspacing=2 cellpadding=0 border=0>
		<tr> 
			<td bgcolor=#8ABEE5><img height=2 src="{absolutepath}images/clearpixel.gif" alt="" width=5></td>
			<td class=infoBody>= Required field.</td>
		</tr>
	</table>
	<br>
	<table cellspacing=2 cellpadding=0 width="100%" border=0>
    <tr bgcolor="#C5C7B5" > 
      <td colspan=2><img height=1 src="images/clearpixel.gif" alt="" width=2></td>
    </tr>
    <tr> 
      <td><img height=22 alt="" hspace=3 src="images/lb_1.gif" width=22 vspace=3></td>
      <td width="100%">My username & password:</td>
    </tr>
    <tr> 
      <td colspan=2><img height=10 src="images/clearpixel.gif" alt="" width=2></td>
    </tr>
    <tr> 
      <td valign=top></td>
      <td valign=top width="100%"> 
			<TABLE cellSpacing=2 cellPadding=0 width="100%" border=0>
				<TBODY>
				<TR>
					<TD width="13%"><IMG height=2 src="images/clearpixel.gif" alt="" width=120></TD>
					<TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD width="0%"><IMG height=2 src="images/clearpixel.gif" alt="" width=5></TD>
					<TD width="86%"><IMG height=2  src="images/clearpixel.gif" alt="" width=2></TD>
				</TR>
				<TR>
					<TD width="13%"></TD>
					<TD width="1%"><IMG height=6 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD  colSpan=2>
					</TD>
				</TR>
				<TR>
					<TD noWrap width="13%">Username:</TD>
					<TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD bgColor=#8ABEE5 width="0%"><IMG height=2 src="images/clearpixel.gif" alt="" width=5></TD>
					<TD width="86%"><INPUT size=14 value="<?echo $login;?>" name=login><input name="uid" type="hidden" value="<? echo $uid;?>">
				</TD>
				</TR>
				<TR>
					<TD width="13%"></TD>
					<TD width="1%"><IMG height=6 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD colSpan=2>
					</TD>
				</TR>
				<TR>
					<TD noWrap width="13%">Password:</TD>
					<TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD bgColor=#8ABEE5 width="0%"><IMG height=2 src="images/clearpixel.gif" alt="" width=5></TD>
					<TD width="86%"><INPUT type=password size=14 name=user_password><SPAN class=formCaption>(Minimum of 4  characters)</SPAN></TD>
				</TR>
				<TR>
					<TD width="13%"></TD>
					<TD width="1%"><IMG height=6 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD colSpan=2>
					</TD>
				</TR>
				<TR>
					<TD noWrap width="13%">Retype Password:</TD>
					<TD width="1%"><IMG height=2 src="images/clearpixel.gif" alt="" width=15></TD>
					<TD bgColor=#8ABEE5 width="0%"><IMG height=2 src="images/clearpixel.gif" alt="" width=5></TD>
					<TD width="86%"><INPUT type=password size=14 name=user_password2></TD><INPUT type=hidden name=passwordold value="<?echo $passwordold?>">
				</TR>
				</TBODY>
				</TABLE>
      </td>
    </tr>
    <tr> 
      <td colspan=2><img height=30 src="images/clearpixel.gif" alt="" width=2></td>
    </tr>
  <tr bgcolor="#C5C7B5"> 
    <td colspan=2><img height=1 src="images/clearpixel.gif" alt="" width=2></td>
  </tr>
  <tr> 
    <td><img height=22 alt="" hspace=3 src="images/lb_2.gif" width=22 vspace=3></td>
    <td width="100%">About me:</td>
  </tr>
  <tr> 
    <td colspan=2><img height=10 src="images/clearpixel.gif" alt="" width=2></td>
  </tr>
  <tr> 
    <td valign=top></td>
    <td valign=top width="100%"> 
			<table cellspacing=2 cellpadding=0 width="100%" border=0>
				<tr> 
					<td><img height=2 src="images/clearpixel.gif" alt="" width=120></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td width="100%"><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif"  	width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td noWrap valign="top">Name:</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap><input size=14 name="firstname" value="<? echo $firstname;?>">
						<br>
            Firstname</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap> 
						<input size=3 name="middlename" value="<? echo $middlename;?>">
						<br>MI</td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap> 
						<input size=14 name="lastname" value="<? echo $lastname;?>">
						<br>
            Surname</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap>&nbsp;</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=20 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap>Company:</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=7> 
						<input size=30 name="companyname" value="<? echo $companyname;?>">
						<br>Name</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=7> 
						<input size=30 name="companyfunction" value="<? echo $companyfunction;?>">
						<br>
            Position</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=20 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap>Address:</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=7> 
						<input size=30 	name="address" value="<? echo $address;?>">
						<br>Line 1</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=7> 
						<input size=30 name="address2" value="<? echo $address2;?>">
						<br>Line 2</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=7> 
						<input size=30 name="address3" value="<? echo $address3;?>">
						<br>Line 3</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td noWrap></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td valign="top">
						<input size=14 name="city" value="<? echo $city;?>"><br>City</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=3 valign="top"> 
						<input size=3 name="state" value="<? echo $state;?>">
						<br>State/Province</td>
					<td valign="top"><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap valign="top"> 
						<input size=5 name="zip" value="<? echo $zip;?>">
						<br>
            Zip/Postal code</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td colspan=7> 
						<input size=14 name="country" value="<? echo $country;?>">
						<br>Country</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=20 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap>Contact Info:</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=7> 
						<input size=30 name="email" value="<? echo $email;?>">
						<br>Primary Email Address</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=20 src="images/clearpixel.gif" alt="" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td valign=top noWrap></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap> 
						<input size=14 name="workphone" value="<? echo $workphone;?>">
						<br>
            Work Phone no.</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap colspan=5><input size=14 name="homephone" value="<? echo $homephone;?>">
						<br>
            Home Phone no.</td>
				</tr>
			</table>
		</td>
		</tr>
		 <tr> 
		<td colspan=2><img height=30 src="images/clearpixel.gif" alt="" width=2></td>
	</tr>
  <tr bgcolor="#C5C7B5"> 
    <td colspan=2><img height=1 src="images/clearpixel.gif" alt="" width=2></td>
  </tr>
  <tr> 
    <td><img height=22 alt="" hspace=3 src="images/lb_3.gif" width=22 vspace=3></td>
    <td width="100%">Send me email:</td>
  </tr>
  <tr> 
    <td colspan=2><img height=10 src="images/clearpixel.gif" alt="" width=2></td>
  </tr>
  <tr> 
    <td valign=top></td>
    <td valign=top width="100%"> 
			<table cellspacing=2 cellpadding=0 width="100%" border=0>
				<tr> 
					<td><img height=2 src="images/clearpixel.gif" alt="" width=120></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
					<td width="100%"><img height=2 src="images/clearpixel.gif" alt="" width=2></td>
				</tr>
				<tr> 
					<td></td>
					<td><img height=6 src="images/clearpixel.gif" width=15></td>
					<td colspan=8> 
					</td>
				</tr>
				<tr> 
					<td noWrap valign="top">Contents:</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=15></td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap><input name="newsmail" value="1" type="checkbox" <?echo $newsmailcheck?>>
						<br>News</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap>&nbsp;&nbsp;&nbsp;</td>
					<td bgcolor=#8ABEE5><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap><input name="eventmail" value="1" type="checkbox" <?echo $eventmailcheck?>>
						<br>Events</td>
					<td><img height=2 src="images/clearpixel.gif" alt="" width=5></td>
					<td noWrap>&nbsp;</td>
				</tr>
			</table>
		</td>
		</tr>
		<tr> 
			<td valign=top></td>
			<td valign=top width="100%"> 
				<table cellspacing=2 cellpadding=0 width="100%" border=0>
					<tr> 
						<td colspan=4><img height=30 src="images/clearpixel.gif" alt="" width=2></td>
					</tr>
					<tr> 
						<td colspan=4> 
						<?
						if ($action == 'create' || $action == 'recreate') {
							echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Register\" name=\"statusactionsave\">";
						}
						else{
							echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Update\" name=\"statusactionsave\">";
						}
						echo "\n<INPUT TYPE=\"HIDDEN\" NAME=\"primarykey\" VALUE=\"\">";
						echo "\n<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"update".$action."\">";
						?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
  <?
	echo "\n</FORM>";
}

//display the form elements
function general_formelement($val, $formrarray, $action){
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status;
	global $container_id, $generalconfig, $wf, $absolutepath;
	$value=$val[0];
	$formelement=$val[1];
	$display=$val[2];
	$val[3]=='required' ? $requiredcolor="8C8A8C" : $requiredcolor="ffffff";
	if ($action == 'reedit' || $action == 'recreate') {
		$cellvalue = $_REQUEST[$value];
		//$cellvalue = eregi_replace("<br>","",$cellvalue);
	}
	if ($action == 'edit') {
		$cellvalue = $formrarray[$value];
		//$cellvalue = eregi_replace("<br>","\n",$cellvalue);
	}
	$cellvalue=stripslashes(stripslashes(stripslashes($cellvalue)));
	include ("general_formelements.inc.php");
}

//displays the default error box at the top of the list or form
function errorbox ($error, $blurbtype){
	global $absolutepath;
	echo "<br>";
	$blurbtype=='notify' ? $errorcolor='color="000000"' : $errorcolor='color="ff0000"';
	if($error<>'') {
	?>
	<table cellspacing=2 cellpadding=0 width="100%" border=0>
		<tr> 
			<td><img height=22 alt="" hspace=3 src="<?echo $absolutepath?>images/dr_pijl.gif" width=22 vspace=3></td>
			<td width="100%"><font color="#A61F1F"><b>Oops! Something is 
				wrong here.</b></font></td>
		</tr>
		<tr> 
			<td colspan=2><img height=10 src="<?echo $absolutepath?>images/clearpixel.gif" alt="" width=2></td>
		</tr>
		<tr> 
			<td valign=top></td>
			<td valign=top width="100%"> 
				<table width="100%" border="0" cellspacing="2" cellpadding="0">
					<tr>
						<td><?echo $error;?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?
	}
}


//this function inserts and updates the records
//inserts and updates struct with workflow support
function general_process($action) {
	global $records, $tablename, $primarykey, $uid, $status, $userright, $display_name, $u_mail, $pagetitle, $jetstream_url, $wf;
	global $container_id, $generalconfig, $mailuid, $front_end_url;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT;
	$error ="";
	if ($action=='edit' || $action=='reedit') {
		$records[2][3]='';
		if ($_POST["user_password"] <> $_POST["user_password2"]){
			$error='true';
			$_POST["user_password"]=$_POST["passwordold"];
			general_form("re".$action, "\nNew password do not match.");
			return false;
		}
		elseif ($GLOBALS["user_password"]<>'' && $GLOBALS["user_password2"]<>'' && ($GLOBALS["user_password"] == $GLOBALS["user_password2"])){
			$_POST["user_password"]=$GLOBALS["user_password"];
		}
		else{
			//echo $_POST["passwordold"];
			$_POST["user_password"]=$_POST["passwordold"];
		}	    
	}
	elseif($action=='create' || $action=='recreate'){
		if ($_POST["user_password"] <> $_POST["user_password2"]){
			$error='true';
			//$_POST["user_password"]=$_POST["passwordold"];
			general_form("re".$action, "\nNew password do not match.");
			return false;
		}
	}

	//check if all required items are filled out
	while (list($var, $val) = each($records)) {
		$field=$val[0];
		$nicefield=$val[2];
		$req=$val[3];
		if ($req && !$_POST[$field]) {
			$errors .="\n<li>".$nicefield."</li>";
		}
	}
	//if errors some of the required fields are empty
	if ($errors) {
		$_POST[$primarykey] = $primarykey;
		if ($fileupload==true) {
			if (file_exists($full_localfilename)) {
				unlink($full_localfilename);			    
			}
		}
		general_form("re".$action, "\nPlease fill out the required fields:\n<ul>" . $errors . "</ul>");
		return false;
	}
	if($action=='create' || $action=='recreate'){
		$checksql="SELECT login FROM $tablename WHERE login='".$_POST["login"]."'";
		$cr = mysql_query($checksql);
		if (mysql_num_rows($cr)>0) {
			general_form("re".$action, "<b>\nThe requested username is already in use.</b><br>To continue your registration, please choose a different username.<br><br>");
			return false;
		}
	}
	if ($action == 'create') {
		//a new record is created for the actual container data
		$sql="INSERT INTO $tablename ( $primarykey ) VALUES ('')";
		$r = mysql_query($sql);
		$error = mysql_error();
		if ($error) {
			if ($fileupload==true) {
				if (file_exists($full_localfilename)) {
					unlink($full_localfilename);			    
				}
			}
			listrecords("\nError creating record: $error","error");
			return false;
		}
		//get the primarykey
		$_POST[$primarykey] = mysql_insert_id();
	}
	$update = "UPDATE $tablename ";
	$delimiter= "SET ";
	$index=0;
	reset($records);
	while (list($var, $val) = each($records)) {
		$field=$val[0];
		if (!($primarykey == $field) && !(($field == $filefield)  && (($userfile=='') || ($userfile=='none')))) {
			$update .= $delimiter . $field  . "='" .  addslashes($_POST[$field]) . "'";
			$delimiter= ", ";
		}
		$index++;
	}
	$update = $update . " WHERE $primarykey = '" . $_POST[$primarykey] ."'";
	$r = mysql_query($update);
	$error = mysql_error();
	if ($error) {
		if ($action == 'create') {
			$errormsg= "\n<P><B>Error creating record: $error</B></P>\n";
		}
		else{
			$errormsg= "\n<P><B>Error updating record: $error</B></P>\n";
		}
		general_form('re'.$action,$errormsg);
		return false;

	} 
	else{
		listrecords();
	}
}

//displays a pulldown form item
function general_displaydropdown($pos, $selected, $dropdown, $argument='') {
	$q = "SELECT * FROM ".$dropdown[$pos][0]." ". $dropdown[$pos][3];
	$r = mysql_query($q) or die (mysql_error());
	$n = mysql_numrows($r);
	echo "\n<select name=\"".$pos."\">";
	for ($i=0; $i < $n; $i++) {
		$primkey = mysql_result ($r,$i,$dropdown[$pos][1]);
		$displaytext = mysql_result ($r,$i,$dropdown[$pos][2]);
		if ($selected == $primkey) {
			echo "\n<option class=\"form_select\" selected value=\"". $primkey . "\">" . $displaytext;
		}
		else {
			echo "\n<option class=\"form_select\" value=\"". $primkey . "\">" . $displaytext ;
		}
	}
	echo "\n</select>";
}

//displays a pulldown form item
function general_linkedvalue($pos, $selected, $dropdown, $argument='') {
	$q = "SELECT * FROM ".$dropdown[$pos][0]." ". $dropdown[$pos][3] ." WHERE ". $dropdown[$pos][1]."='".$selected."'";
	$r = mysql_query($q) or die (mysql_error());
	$n = mysql_numrows($r);
	if ($n) {
		return  $displaytext = mysql_result ($r,$i,$dropdown[$pos][2]);
	}
	else{
		return("none");
	}
}

//process date information
function general_date_process(){
	global $dbadmindatefield;
	if ($dbadmindatefield) {
		while (list($var, $value) = each($dbadmindatefield)) {
			$date_field=$value;
			$day = $date_field . "_day";
			$month = $date_field . "_month";
			$year = $date_field . "_year";
			$_REQUEST[$date_field]= $_REQUEST[$year] ."-". $_REQUEST[$month] ."-". $_REQUEST[$day];
			$_POST[$date_field]= $_POST[$year] ."-". $_POST[$month] ."-". $_POST[$day];
		}
	}
}

//display a "date" form item
//combination of three pulldown menus
function general_dateinput($inpone, $valone, $inptwo, $valtwo, $inpthree, $valthree) {
	if (!$valone && !$valtwo && !$valthree) {
		$valone = date("d");
		$valtwo = date("m");
		$valthree = date("Y");
	}
	$month[1]="Januari";
	$month[2]="Februari";
	$month[3]="Maart";
	$month[4]="April";
	$month[5]="Mei";
	$month[6]="Juni";
	$month[7]="Juli";
	$month[8]="Augustus";
	$month[9]="September";
	$month[10]="Oktober";
	$month[11]="November";
	$month[12]="December";
	$return = "\n<select name=\"".$inpone."\">\n" ;
	$return .= "	\n<option class=\"form_select\" value=\"0\">Day\n" ;
	for ($ii=1;$ii<=31;$ii++) {
		if ( $ii==$valone ) { $sel=" selected" ; } else { $sel="" ; }
		$return .= "	\n<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$ii."\n" ;
	}
	$return .= "\n</select>\n";
	$return .= "\n<select name=\"".$inptwo."\">\n" ;
	$return .= "	\n<option class=\"form_select\" value=\"0\">Maand\n" ;
	for ($ii=1;$ii<=12;$ii++) {
		if ( $ii==$valtwo ) { $sel=" selected" ; } else { $sel="" ; }
		$return .= "	\n<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$month[$ii]."\n" ;
	}
	$return .= "\n</select>\n";
	$return .= "\n<input class=\"form_input\" type=\"text\" name=\"$inpthree\" size=4 value=\"$valthree\">";
	return $return;
}
?>