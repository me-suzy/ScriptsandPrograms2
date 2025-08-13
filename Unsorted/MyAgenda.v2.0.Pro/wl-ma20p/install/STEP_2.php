<script language="Javascript">
<!--
function validate() {
	var f = document.myAgenda;
  if (f.Host_DB.value == "") {
    alert("You forgot to fill in the myAgenda Database Host!");
    f.Host_DB.focus();
    return false;
  } 
  if (f.Name_DB.value == "") {
    alert("You forgot to fill in the myAgenda Database Name!");
    f.Name_DB.focus();
    return false;
  } 
  if (f.User_DB.value == "") {
    alert("You forgot to fill in the myAgenda Database User!");
    f.User_DB.focus();
    return false;
  } 
   if (f.Pass_DB.value == "") {
    alert("You forgot to fill in the myAgenda Database Password!");
    f.Pass_DB.focus();
    return false;
  }
   if (f.PROG_NAME.value == "") {
    alert("You forgot to fill in the myAgenda Program Name!");
    f.PROG_NAME.focus();
    return false;
  } 
   if (f.PROG_PATH.value == "") {
    alert("You forgot to fill in the myAgenda Server Path!");
    f.PROG_PATH.focus();
    return false;
  } 
   if (f.PROG_URL.value == "") {
    alert("You forgot to fill in the myAgenda Program Url!");
    f.PROG_URL.focus();
    return false;
  } 
  if (f.PROG_EMAIL.value == "") {
    alert("You forgot to fill in the myAgenda E-Mail!");
    f.PROG_EMAIL.focus();
    return false;
  } 
  if (f.USER_TIMEOUT.value<600) {
    alert("You should give at least 10 minutes (600) to user timeout");
    f.USER_TIMEOUT.focus();
    return false;
  }
  if (f.User_Cron.value == "") {
    alert("You forgot to fill in the myAgenda Cron User!");
    f.User_Cron.focus();
    return false;
  } 
   if (f.Pass_Cron.value == "") {
    alert("You forgot to fill in the myAgenda Cron Password!");
    f.Pass_Cron.focus();
    return false;
  }
  if (f.ADMIN_USERNAME.value == "") {
    alert("You forgot to fill in the username field!");
    f.ADMIN_USERNAME.focus();
    return false;
  } 
  if (f.ADMIN_PASSWORD.value == "") {
    alert("You forgot to fill in the password field!");
    f.ADMIN_PASSWORD.focus();
    return false;
  } 
  if (f.ADMIN_PASSWORD.value != f.CONFIRM.value) {
    alert("The passwords do not match!");
    f.ADMIN_PASSWORD.focus();
    return false;
  }
  return true;
}
function IsDigit() {
	return (event.keyCode >= 48) && (event.keyCode <= 57)
}
// -->
</script>
</head>
<BODY bgcolor="#007F7F" onload="document.myAgenda.Host_DB.focus();">
<table border="1" cellspacing="0" cellpadding="0" align="center" width="500">
  <tr bgcolor="#C6C3C6">
    <td>
      <table width="500" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr bgcolor="#400080">
          <td height="20" bgcolor="#000084"><b><font color="#FFFFFF">
            &nbsp;myAgenda <?=$version;?> Installation</font></b></td>
          <td height="20" align="right" bgcolor="#000084"><a href="javascript:void(0);" onclick="Cancel()"><img src="cross.gif" width="16" height="14" border="0"></a></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <form method="post" name="myAgenda" onsubmit="return validate()" action="<?=$SELF;?>">
			<input type="hidden" name="STEP" value="3">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr bgcolor="#FFFFFF" valign="bottom">
                  <td  height="30"><b>&nbsp;&nbsp;&nbsp;&nbsp;Settings</b></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                  <td  height="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Enter the required settings</td>
                </tr>
                <tr bgcolor="#C6C3C6">
                  <td  valign="top" align="center">
                    <table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr>
                        <td>
                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                            <?php
							if($msg) {
							?>
							<tr>
								<td colspan="3"><font color="#FF0000"><b><?=$msg;?></b></font></td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td colspan="3"><b>These variables will be stored into config.php file</b></td>
							</tr><tr>
                              <td width="30%" >Database Host</td>
							  <td align="center">:</td>
                              <td width="70%"><input type="text" class="txt" name="Host_DB" value="<?=(empty($frm['Host_DB']) ? "localhost" : $frm['Host_DB']);?>" size="25"></td>
                            </tr><tr>
                              <td valign="top" >Database Name</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="Name_DB" value="<?=(empty($frm['Name_DB']) ? "myAgenda" : $frm['Name_DB']);?>" size="25"></td>
                            </tr><tr>
                              <td >Database User</td>
							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="User_DB" value="<?=(empty($frm['User_DB']) ? "myAgenda" : $frm['User_DB']);?>" size="25"></td>
                            </tr><tr>
                              <td valign="top" >Database Password</td>
							  <td align="center">:</td>
                              <td ><input type="password" class="txt" name="Pass_DB" size="25"></td>
                            </tr><tr>
                              <td valign="top" >Tables Prefix</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="Tables_PREFIX" value="<?=(empty($frm['Tables_PREFIX']) ? "AGENDA" : $frm['Tables_PREFIX']);?>" size="25" maxlength="10"><br>
							  If you enter AGENDA as prefix, tables will be created like AGENDA_USERS, AGENDA_ETC</td>
                            </tr><tr>
                              <td >Cron Username</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="User_Cron" value="<?=(empty($frm['User_Cron']) ? "cronuser" : $frm['User_Cron']);?>" size="25" maxlength="10"></td>
                            </tr><tr>
                              <td >Cron Password</td>
 							  <td align="center">:</td>
                              <td ><input class="txt" type="password" name="Pass_Cron" size="25" maxlength="10"></td>
                            </tr><tr>
								<td align="center" height="20" colspan="3"><img src="h_line.gif" height="18" width="490"></td>
			                </tr><tr>
								<td colspan="3"><b>These variables will be stored into MySQL</b></td>
							</tr><tr>
                              <td width="30%" >Agenda Name</td>
 							  <td align="center">:</td>
                              <td width="70%"><input type="text" class="txt" class="txt" name="PROG_NAME" value="<?=(empty($frm['PROG_NAME']) ? "myAgenda" : $frm['PROG_NAME']);?>" size="25"></td>
                            </tr><tr>
                              <td >Agenda Server Path</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="PROG_PATH" value="<?=(empty($frm['PROG_PATH']) ? "/home/mesut/www/myAgenda" : $frm['PROG_PATH']);?>" size="25"> ( No ending slash)<br>
							  It seems <?=str_replace("/install/index.php", "", getenv("SCRIPT_FILENAME"));?></td>
                            </tr><tr>
                              <td >Agenda URL</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="PROG_URL" value="<?=(empty($frm['PROG_URL']) ? "http://www.asite.com/myAgenda" : $frm['PROG_URL']);?>" size="25"> ( No ending slash)<br>
							  It seems <?="http://" . getenv("HTTP_HOST") . str_replace("/install/", "", getenv("REQUEST_URI"));?></td>
                            </tr><tr>
                              <td >Agenda E-Mail</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="PROG_EMAIL" value="<?=(empty($frm['PROG_EMAIL']) ? "myAgenda@asite.com" : $frm['PROG_EMAIL']);?>" size="25"></td>
                            </tr><tr>
                              <td >Agenda Language</td>
 							  <td align="center">:</td>
                              <td ><?=$lang_form;?></td>
                            </tr><tr>
                              <td >Week Start:</td>
 							  <td align="center">:</td>
                              <td ><select name="WEEK_START">
							  <Option value="0" <?=($frm['WEEK_START']=='0' ? "Selected" : "");?>>Sunday
							  <Option value="1" <?=($frm['WEEK_START']=='1' ? "Selected" : "");?>>Monday
							</select></td>
                            </tr><tr>
                              <td >Time Offset</td>
 							  <td align="center">:</td>
                              <td valign="top">
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td><select name="TIME_OFFSET">
							  <Option value="0">0
							  <Option value="-39600" <?=($frm['TIME_OFFSET']=="-39600" ? "Selected" : "");?>>-11
							  <Option value="-36000" <?=($frm['TIME_OFFSET']=="-36000" ? "Selected" : "");?>>-10
							  <Option value="-32400" <?=($frm['TIME_OFFSET']=="-32400" ? "Selected" : "");?>>-9
							  <Option value="-28800" <?=($frm['TIME_OFFSET']=="-28800" ? "Selected" : "");?>>-8
							  <Option value="-25200" <?=($frm['TIME_OFFSET']=="-25200" ? "Selected" : "");?>>-7
							  <Option value="-21600" <?=($frm['TIME_OFFSET']=="-21600" ? "Selected" : "");?>>-6
							  <Option value="-18000" <?=($frm['TIME_OFFSET']=="-18000" ? "Selected" : "");?>>-5
							  <Option value="-14400" <?=($frm['TIME_OFFSET']=="-14400" ? "Selected" : "");?>>-4
							  <Option value="-10800" <?=($frm['TIME_OFFSET']=="-10800" ? "Selected" : "");?>>-3
							  <Option value="-7200"  <?=($frm['TIME_OFFSET']== "-7200" ? "Selected" : "");?>>-2
							  <Option value="-3600"  <?=($frm['TIME_OFFSET']== "-3600" ? "Selected" : "");?>>-1
							  <Option value="+3600"   <?=($frm['TIME_OFFSET']==  "+3600" ? "Selected" : "");?>>1
							  <Option value="+7200"   <?=($frm['TIME_OFFSET']==  "+7200" ? "Selected" : "");?>>2
							  <Option value="+10800"  <?=($frm['TIME_OFFSET']== "+10800" ? "Selected" : "");?>>3
							  <Option value="+14400"  <?=($frm['TIME_OFFSET']== "+14400" ? "Selected" : "");?>>4
							  <Option value="+18000"  <?=($frm['TIME_OFFSET']== "+18000" ? "Selected" : "");?>>5
							  <Option value="+21600"  <?=($frm['TIME_OFFSET']== "+21600" ? "Selected" : "");?>>6
							  <Option value="+25200"  <?=($frm['TIME_OFFSET']== "+25200" ? "Selected" : "");?>>7
							  <Option value="+28800"  <?=($frm['TIME_OFFSET']== "+28800" ? "Selected" : "");?>>8
							  <Option value="+32400"  <?=($frm['TIME_OFFSET']== "+32400" ? "Selected" : "");?>>9
							  <Option value="+36000"  <?=($frm['TIME_OFFSET']== "+36000" ? "Selected" : "");?>>10
							  <Option value="+39600"  <?=($frm['TIME_OFFSET']== "+39600" ? "Selected" : "");?>>11
							</select></td>
	<td>Use this if your server time is different than your country<BR>(your server date time is: <?=date("d-m-Y H:i:s");?>)<BR></td>
</tr>
</table>

							  </td>
                            </tr><tr>
                              <td >User Timeout</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="USER_TIMEOUT" value="<?=(empty($frm['USER_TIMEOUT']) ? 1800 : $frm['USER_TIMEOUT']);?>" size="40" maxlength="4" onKeyPress="event.returnValue=IsDigit()"> <font class="small">(As Seconds)</font></td>
                            </tr><tr>
                              <td >Admin Username</td>
 							  <td align="center">:</td>
                              <td ><input type="text" class="txt" name="ADMIN_USERNAME" value="<?=(empty($frm['ADMIN_USERNAME']) ? "admin" : $frm['ADMIN_USERNAME']);?>" size="25"></td>
                            </tr><tr>
                              <td >Admin Password</td>
 							  <td align="center">:</td>
                              <td ><input class="txt" type="password" name="ADMIN_PASSWORD" size="25"></td>
                            </tr><tr>
                              <td >Admin Password (Confirm)</td>
 							  <td align="center">:</td>
                              <td ><input type="password" name="CONFIRM" size="25"></td>
                            </tr>
                          </table>
                              </td>
                            </tr>
                          </table>
                  </td>
                </tr>
                <tr bgcolor="#C6C3C6">
                  <td align="center" height="20">
                    <img src="h_line.gif" height="18" width="490">
                  </td>
                </tr>
                <tr bgcolor="#C6C3C6" align="right">
                  <td><input type="button" value="< Back" onClick="Back()" style="width:75px;height:23px;"><input type="submit" value="Next >" style="width:75px;height:23px;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="Cancel()" style="width:75px;height:23px;">&nbsp;&nbsp;</td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>