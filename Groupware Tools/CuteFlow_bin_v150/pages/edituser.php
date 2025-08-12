<?php
	/** Copyright (c) 2003, 2004 EMEDIA OFFICE GmbH. All rights reserved.
	*
	* Redistribution and use in source and binary forms, with or without 
	* modification, are permitted provided that the following conditions are met:
	* 
	*  o Redistributions of source code must retain the above copyright notice, 
	*    this list of conditions and the following disclaimer. 
	*     
	*  o Redistributions in binary form must reproduce the above copyright notice, 
	*    this list of conditions and the following disclaimer in the documentation 
	*    and/or other materials provided with the distribution. 
	*     
	*  o Neither the name of EMEDIA OFFICE GmbH nor the names of 
	*    its contributors may be used to endorse or promote products derived 
	*    from this software without specific prior written permission. 
	*     
	* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
	* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
	* THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
	* PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
	* CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
	* EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
	* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
	* OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
	* WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR 
	* OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
	* EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	*/
	
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title></title>	
	<link rel="stylesheet" href="format.css" type="text/css">
	<script language="JavaScript">
	<!--
		function BrowseUser()
		{
			url="selectuser.php?language=<?php echo $_REQUEST["language"];?>";
			open(url,"BrowseUser","width=260,height=160,status=no,menubar=no,resizable=no,scrollbars=no");		
		}

		function SetUser(nId, strName)
		{
			document.EditUser["SubstitudeId"].value = nId;
			
			objSubstitude = document.getElementById("SubstitudeName");
			objSubstitude.innerHTML = strName;
		}

		function validate(objForm)
		{
			var objForm = document.forms["EditUser"];
			objForm.strFirstName.required = 1;
			objForm.strFirstName.err = "<?php echo $EDIT_NEW_ERROR_FIRSTNAME;?>";
			objForm.strLastName.required = 1;
			objForm.strLastName.err = "<?php echo $EDIT_NEW_ERROR_LASTNAME;?>";
			
			objElementAdmin = document.getElementById("UserAccesslevelAdmin");
			objElementReadOnly = document.getElementById("UserAccesslevelReadOnly");
			if ( (objElementAdmin.checked == true) || (objElementReadOnly.checked == true) )
			{
				objForm.Password1.required = 1;
				objForm.Password1.err = "<?php echo $EDIT_NEW_ERROR_PASSWORD1;?>";
				objForm.Password2.required = 1;
				objForm.Password2.err = "<?php echo $EDIT_NEW_ERROR_PASSWORD2;?>";
			}
			else
			{
				objForm.Password1.required = 0;
				objForm.Password2.required = 0;
			}
			
			bResult = jsVal(objForm);
			
			if (bResult == true)
			{
				if (objForm.Password1.value != objForm.Password2.value)
				{
					alert ("<?php echo $EDIT_NEW_ERROR_PASSWORD3;?>");
					bResult = false;
				}
			}
			
			return bResult	;
		}
	//-->
	</script>
	<script src="jsval.js" type="text/javascript" language="JavaScript"></script>	
</head>
<?php
	$strFirstName = "";
	$strLastName = "";
	$strEMail = "";
	$nAccessLevel = 1;
	$nSubstitudeId = 0;
	
	if ($userid == -1)
	{
		$strPassword = "";
	}
	else
	{
		$strPassword = "unchanged";	
	}
	
	$strSubstitude = "-";
	
	include ("../config/config.inc.php");

	if (-1 != $userid)
	{
    	//--- open database
    	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
    	
    	if ($nConnection)
    	{
    		//--- get maximum count of users
    		if (mysql_select_db($DATABASE_DB, $nConnection))
    		{
    			//--- read the values of the user
				$strQuery = "SELECT * FROM cf_user WHERE nID = ".$_REQUEST["userid"];
				$nResult = mysql_query($strQuery, $nConnection);
        
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
        				while (	$arrRow = mysql_fetch_array($nResult))
        				{
        					$strFirstName = $arrRow["strFirstName"];
        					$strLastName = $arrRow["strLastName"];
        					$strEMail = $arrRow["strEMail"];
        					$nAccessLevel = $arrRow["nAccessLevel"];
        					$nSubstitudeId = $arrRow["nSubstitudeId"];
                            $strUserId = $arrRow["strUserId"];
        				}		
        			}
        		}
        		
        		//--- get substitude
				$strQuery = "SELECT * FROM cf_user WHERE nID = $nSubstitudeId";
				$nResult = mysql_query($strQuery, $nConnection);
        
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
        				while (	$arrRow = mysql_fetch_array($nResult))
        				{
        					$strSubstitude = $arrRow["strLastName"].", ".$arrRow["strFirstName"];
        				}
        			}
        		}
    		}
    	}
	}	
?>
<body>
	<br/>
	<br/>
	<div align="center">
		<form action="writeuser.php" id="EditUser" name="EditUser" onsubmit="return validate(this);">
    		<table class="note">
    			<tr>
    				<td colspan="2" bgcolor="Red" align="left" style="font-weight:bold;color:White;">
						<?php echo $USER_EDIT_FORM_HEADER;?>
					</td>
    			</tr>
                <tr>
    				<td class="mandatory"><?php echo $USER_EDIT_FIRSTNAME;?></td>
    				<td><input id="strFirstName" Name="strFirstName" type="text" class="FormInput" style="width:150px;" value="<?php echo $strFirstName;?>"></td>
    			</tr>
                <tr>
    				<td class="mandatory"><?php echo $USER_EDIT_LASTNAME;?></td>
    				<td><input id="strLastName" Name="strLastName" type="text" class="FormInput" style="width:250px;" value="<?php echo $strLastName;?>"></td>
    			</tr>
                <tr>
    				<td><?php echo $USER_EDIT_EMAIL;?></td>
    				<td><input id="strEMail" Name="strEMail" type="text" class="FormInput" style="width:250px;" value="<?php echo $strEMail;?>"></td>
    			</tr>
				<tr>
					<td colspan="2" height="10px"></td>
				</tr>
    	        <tr>
    				<td valign="top"><?php echo $USER_EDIT_ACCESSLEVEL;?></td>
    				<td>
						<input type="radio" id="UserAccesslevelAdmin" name="UserAccessLevel" value="2" <?php echoCheckedAllowed($nAccessLevel, 2);?>> <?php echo $USER_ACCESSLEVEL_ADMIN;?><br>
						<input type="radio" id="UserAccesslevelReadOnly" name="UserAccessLevel" value="4" <?php echoCheckedAllowed($nAccessLevel, 4);?>> <?php echo $USER_ACCESSLEVEL_READONLY;?><br>
						<input type="radio" id="UserAccesslevelReceiver" name="UserAccessLevel" value="1" <?php if (($nAccessLevel == 0) || ($nAccessLevel == 1)) echo "CHECKED";?>> <?php echo $USER_ACCESSLEVEL_RECEIVER;?>						
					</td>
    			</tr>
				<tr>
					<td colspan="2" height="10px"></td>
				</tr>
                <tr>
    				<td class="mandatory"><?php echo $USER_EDIT_USERID;?></td>
    				<td><input type="text" Name="UserName" id="UserName" class="FormInput" style="width:150px;" value="<?php echo $strUserId;?>"></td>
    			</tr>
                <tr>
    				<td class="mandatory"><?php echo $USER_EDIT_PWD;?></td>
    				<td><input type="password" Name="Password1" id="Password1" class="FormInput" style="width:150px;" value="<?php echo $strPassword;?>"></td>
    			</tr>
                <tr>
    				<td class="mandatory"><?php echo $USER_EDIT_PWDCHECK;?></td>
    				<td><input type="password" Name="Password2" id="Password2" class="FormInput" style="width:150px;" value="<?php echo $strPassword;?>"></td>
    			</tr>
				<tr>
					<td colspan="2" height="10px"></td>
				</tr>
    			<tr>
    				<td><?php echo $USER_EDIT_SUBSTITUDE;?></td>
    				<td>
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<div id="SubstitudeName" style="background-color:#F7F7F7; border:1px solid #B8B8B8;width:200px;"><?php echo $strSubstitude;?></div>
								</td>
								<td>
									<a href="javascript:BrowseUser();"><img border="0" style="padding-left:3px;" src="../images/browseuser.png"/></a>
								</td>
							</tr>
						</table>
					<td>
    			</tr>
    			<tr>
    				<td colspan="2" style="border-top: 1px solid #B8B8B8;padding: 6px 0px 4px 0px;" align="right">
						<input type="button" class="Button" value="<?php echo $BTN_CANCEL;?>" onclick="history.back()">&nbsp;&nbsp;<input type="submit" value="<?php echo $USER_EDIT_ACTION;?>" class="Button">
					</td>
    			</tr>
    		</table>
			<input type="hidden" value="<?php echo $_REQUEST["userid"];?>" id="userid" name="userid">
			<input type="hidden" value="<?php echo $_REQUEST["language"];?>" id="language" name="language">
			<input type="hidden" value="<?php echo $_REQUEST["sort"];?>" id="sort" name="sort">
			<input type="hidden" value="<?php echo $_REQUEST["start"];?>" id="start" name="start">
			<input type="hidden" value="<?php echo $_REQUEST["nSubstitudeId"];?>" id="SubstitudeId" name="SubstitudeId">
		</form>
	</div><table>
	<tr>
		<td></td>
	</tr>
	</table>
	
</body>
</html>

<?php
	function echoCheckedAllowed($nAccessLevel, $nRequestLevel)
	{
		if ( ($nAccessLevel & $nRequestLevel) == $nRequestLevel)
		{
			echo "CHECKED";	
		}	
	}
?>