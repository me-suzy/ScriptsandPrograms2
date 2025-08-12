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
	
	include ("../config/config.inc.php");
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title></title>	
	<link rel="stylesheet" href="format.css" type="text/css">
	<script language="JavaScript">
	<!--
		function doOk()
		{
			if (document.forms.BrowseMailingList.MailingList.options.selectedIndex != -1)
			{
				nID = document.forms.BrowseMailingList.MailingList.options[document.forms.BrowseMailingList.MailingList.options.selectedIndex].value;
				strMailingListName = document.forms.BrowseMailingList.MailingList.options[document.forms.BrowseMailingList.MailingList.options.selectedIndex].innerHTML;
				
				opener.SetMailingList(nID, strMailingListName);
					
				window.close();				
			}	
			else
			{
				alert ("<?php echo $MAILINGLIST_SELECT_NO_SELECT;?>");				
			}
		}
	//-->
	</script>	
</head>
<body topmargin="0" leftmargin="0" style="margin: 0px;">
	<div align="center">
		<form action="" id="BrowseMailingList">
    		<table class="note">
    			<tr>
    				<td colspan="2" bgcolor="Red" align="left" style="font-weight:bold;color:White;">
						<?php echo $MAILINGLIST_SELECT_FORM_HEADER;?>
					</td>
    			</tr>
				<tr>
					<td>
						<select id="MailingList" class="FormInput" size="7" style="width:250px;">
        					<?php
        						//--- open database
                            	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
                            	
                            	if ($nConnection)
                            	{
                            		//--- get maximum count of users
                            		if (mysql_select_db($DATABASE_DB, $nConnection))
                            		{
                            			//--- read the values of the user
                        				$strQuery = "SELECT * FROM cf_mailinglist ORDER BY strName ASC";
                        				$nResult = mysql_query($strQuery, $nConnection);
                                
                                		if ($nResult)
                                		{
                                			if (mysql_num_rows($nResult) > 0)
                                			{
                                				while (	$arrRow = mysql_fetch_array($nResult))
                                				{
                             						echo "<option value=\"".$arrRow["nID"]."\">".$arrRow["strName"];
                                				}		
                                			}
                                		}
                              		}
                            	}
        					?>
						</select>
					</td>
				</tr>
                <tr>
    				<td colspan="2" style="border-top: 1px solid #B8B8B8;padding: 6px 0px 4px 0px;" align="right">
						<input type="button" value="<?php echo $BTN_CANCEL;?>" class="Button" onClick="window.close()">
						<input type="button" value="<?php echo $BTN_OK;?>" class="Button" onClick="doOk()">
					</td>
    			</tr>
    		</table>
		</form>
	</div>
</body>
</html>
