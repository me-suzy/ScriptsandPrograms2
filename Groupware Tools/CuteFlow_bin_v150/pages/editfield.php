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
		function validate(objForm)
		{
			var objForm = document.forms["EditField"];
			objForm.strName.required = 1;
			objForm.strName.err = "<?php echo $FIELD_NEW_ERROR_NAME;?>";
			
			bResult = jsVal(objForm);
			
			return bResult;
		}
	//-->
	</script>
	<script src="jsval.js" type="text/javascript" language="JavaScript"></script>	
</head>
<?php
	$strName = "";
	$nType = 1;
	$strValue = "";
	$bReadOnly = 0;
	
	include ("../config/config.inc.php");

	if (-1 != $fieldid)
	{
    	//--- open database
    	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
    	
    	if ($nConnection)
    	{
    		//--- get maximum count of users
    		if (mysql_select_db($DATABASE_DB, $nConnection))
    		{
    			//--- read the values of the user
				$strQuery = "SELECT * FROM cf_inputfield WHERE nID = ".$_REQUEST["fieldid"];
				$nResult = mysql_query($strQuery, $nConnection);
        
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
        				while (	$arrRow = mysql_fetch_array($nResult))
        				{
        					$strName = $arrRow["strName"];
							$nType = $arrRow["nType"];
							$strValue = $arrRow["strStandardValue"];
							$bReadOnly = $arrRow["bReadOnly"];
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
		<form action="writefield.php" id="EditField" name="EditField" onsubmit="return validate(this);">
    		<table class="note">
    			<tr>
    				<td colspan="2" bgcolor="Red" align="left" style="font-weight:bold;color:White;">
						<?php echo $FIELD_EDIT_HEADLINE;?>
					</td>
    			</tr>
                <tr>
    				<td class="mandatory"><?php echo $FIELD_EDIT_NAME;?></td>
    				<td><input id="strName" Name="strName" type="text" class="FormInput" style="width:250px;" value="<?php echo $strName;?>"></td>
    			</tr>
                <tr>
    				<td class="mandatory"><?php echo $FIELD_EDIT_TYPE;?></td>
    				<td>
					<select id="nType" name="nType" class="FormInput">
						<option value="1" <?php if ($nType == 1) echo "selected";?>><?php echo $FIELD_TYPE_TEXT;?></option>
						<option value="2" <?php if ($nType == 2) echo "selected";?>><?php echo $FIELD_TYPE_BOOLEAN;?></option>
						<option value="3" <?php if ($nType == 3) echo "selected";?>><?php echo $FIELD_TYPE_DOUBLE;?></option>
						<option value="4" <?php if ($nType == 4) echo "selected";?>><?php echo $FIELD_TYPE_DATE;?></option>
					</select>
    			</tr>
				<tr>
					<td colspan="2" height="10px"></td>
				</tr>
                <tr>
    				<td><?php echo $FIELD_EDIT_STDVALUE;?></td>
    				<td><input type="text" Name="StdValue" id="StdValue" class="FormInput" style="width:150px;" value="<?php echo $strValue;?>"></td>
    			</tr>
				<tr>
					<td colspan="2" height="10px"></td>
				</tr>
    	        <tr>
    				<td><?php echo $FIELD_EDIT_READONLY;?></td>
    				<td><input type="checkbox" id="ReadOnly" name="ReadOnly"  <?php echoCheckedReadOnly($bReadOnly);?>>&nbsp;</td>
    			</tr>
    			<tr>
    				<td colspan="2" style="border-top: 1px solid #B8B8B8;padding: 6px 0px 4px 0px;" align="right">
						<input type="button" class="Button" value="<?php echo $BTN_CANCEL;?>" onclick="history.back()">&nbsp;&nbsp;<input type="submit" value="<?php echo $USER_EDIT_ACTION;?>" class="Button">
					</td>
    			</tr>
    		</table>
			<input type="hidden" value="<?php echo $_REQUEST["fieldid"];?>" id="fieldid" name="fieldid">
			<input type="hidden" value="<?php echo $_REQUEST["language"];?>" id="language" name="language">
			<input type="hidden" value="<?php echo $_REQUEST["sort"];?>" id="sort" name="sort">
			<input type="hidden" value="<?php echo $_REQUEST["start"];?>" id="start" name="start">
		</form>
	</div><table>
	<tr>
		<td></td>
	</tr>
	</table>
	
</body>
</html>

<?php
	function echoCheckedReadOnly($bReadOnly)
	{
		if ($bReadOnly == 1)
		{
			echo "CHECKED";	
		}	
	}
?>