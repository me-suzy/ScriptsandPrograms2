<?php
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
			if (document.forms.BrowseUser.Substitude.options.selectedIndex != -1)
			{
				nID = document.forms.BrowseUser.Substitude.options[document.forms.BrowseUser.Substitude.options.selectedIndex].value;
				strUserName = document.forms.BrowseUser.Substitude.options[document.forms.BrowseUser.Substitude.options.selectedIndex].innerHTML;
				
				opener.SetUser(nID, strUserName);
					
				window.close();				
			}	
			else
			{
				alert ("<?php echo $USER_SELECT_NO_SELECT;?>");				
			}
		}
	//-->
	</script>	
</head>
<body topmargin="0" leftmargin="0" style="margin: 0px;">
	<div align="center">
		<form action="" id="BrowseUser">
    		<table class="note">
    			<tr>
    				<td colspan="2" bgcolor="Red" align="left" style="font-weight:bold;color:White;">
						<?php echo $USER_SELECT_FORM_HEADER;?>
					</td>
    			</tr>
				<tr>
					<td>
						<select id="Substitude" class="FormInput" size="7" style="width:250px;">
        					<?php
        						//--- open database
                            	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
                            	
                            	if ($nConnection)
                            	{
                            		//--- get maximum count of users
                            		if (mysql_select_db($DATABASE_DB, $nConnection))
                            		{
                            			//--- read the values of the user
                        				$strQuery = "SELECT * FROM cf_user ORDER BY strLastName ASC";
                        				$nResult = mysql_query($strQuery, $nConnection);
                                
                                		if ($nResult)
                                		{
                                			if (mysql_num_rows($nResult) > 0)
                                			{
                                				while (	$arrRow = mysql_fetch_array($nResult))
                                				{
                             						echo "<option value=\"".$arrRow["nID"]."\">".$arrRow["strLastName"].", ".$arrRow["strFirstName"]."</option>";   					
                                				}		
                                			}
                                		}
                              		}
                            	}
        					?>
							<option value="0">-</option>
						</select>
					</td>
				</tr>
                <tr>
    				<td colspan="2" style="border-top: 1px solid #B8B8B8;padding: 6px 0px 4px 0px;" align="right">
						<input type="button" value="<?php echo $BTN_CANCEL;?>" class="Button" onClick="window.close();">
						<input type="button" value="<?php echo $BTN_OK;?>" class="Button" onClick="doOk()">
					</td>
    			</tr>
    		</table>
		</form>
	</div>
</body>
</html>
