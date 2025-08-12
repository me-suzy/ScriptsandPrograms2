<?php
	include ("../config/config.inc.php");
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
    include ("../lib/datetime.inc.php");
    include ("../lib/viewutils.inc.php");
	
	include ("../pages/check_substitute.inc.php");
	
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			//-----------------------------------------------
			//--- get the user information from 
			//--- cf_circulationprocess
			//-----------------------------------------------
			$strQuery = "SELECT * FROM cf_circulationprocess WHERE nID=".$_REQUEST["cpid"];
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrCirculationProcess = mysql_fetch_array($nResult);				
				}
			}
			
			//-----------------------------------------------
			//--- get the single circulation form
			//-----------------------------------------------
			$query = "select * from cf_circulationform WHERE nID=".$arrCirculationProcess["nCirculationFormId"];
			$nResult = mysql_query($query, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrCirculationForm = mysql_fetch_array($nResult);				
				}
			}
			
			//-----------------------------------------------
			//--- get the single circulation history
			//-----------------------------------------------
			$query = "select * from cf_circulationhistory WHERE nID=".$arrCirculationProcess["nCirculationHistoryId"];
			$nResult = mysql_query($query, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrCirculationHistory = mysql_fetch_array($nResult);
				}
			}
			
			//-----------------------------------------------
    		//--- get all users
         	//-----------------------------------------------
         	$arrUsers = array();
    		$strQuery = "SELECT * FROM cf_user";
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					$arrUsers[$arrRow["nID"]] = $arrRow;
    				}
    			}
    		}
			
			//-----------------------------------------------
			//--- get the template id
			//-----------------------------------------------
			$strQuery = "SELECT nTemplateId FROM cf_formslot WHERE nID=".$arrCirculationProcess["nSlotId"];
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrRow = mysql_fetch_array($nResult);
					$templateid = $arrRow["nTemplateId"];
				}
			}
			
			//-----------------------------------------------
			//--- get the form slots
            //-----------------------------------------------	            
            $arrSlots = array();
            $strQuery = "SELECT * FROM cf_formslot WHERE nTemplateID=".$templateid." ORDER BY nSlotNumber ASC";
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					$arrSlots[$arrRow["nID"]] = $arrRow;
    				}
    			}
    		}
			
			//-----------------------------------------------
            //--- get the field values
            //-----------------------------------------------	            
            $arrValues = array();
            $strQuery = "SELECT * FROM cf_fieldvalue WHERE nFormId=".$arrCirculationProcess["nCirculationFormId"];
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					$arrValues[$arrRow["nInputFieldId"]."_".$arrRow["nSlotId"]."_".$arrRow["nFormId"]] = $arrRow;
    				}
    			}
    		}
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="../pages/format.css" type="text/css">
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body bgcolor="#FCFBE9" style="margin-top:0px">
	<table border="0" style="font-weight:bold;">
		<tr>
			<td><img src="<?php echo $CUTEFLOW_SERVER;?>/images/singleuser.png"</td>
			<td><?php echo htmlentities($CIRCDETAIL_SENDER);?></td>
			<td><?php echo htmlentities($arrUsers[$arrCirculationForm["nSenderId"]]["strLastName"].",".$arrUsers[$arrCirculationForm["nSenderId"]]["strFirstName"]."  (".$arrUsers[$arrCirculationForm["nSenderId"]]["strUserId"].")");?></td>
		</tr>
		<tr>
			<td><img src="<?php echo $CUTEFLOW_SERVER;?>/images/calendar.png"</td>
			<td><?php echo htmlentities($CIRCDETAIL_SENDDATE);?></td>
			<td><?php echo convertDateFromDB($arrCirculationHistory["dateSending"]);?></td>
		</tr>
		
	</table>
	<form action="mail_content_write.php" id="MailContentForm" name="MailContentForm" target="_blank">
	<table width="100%">
		<tr>
			<td valign="top"><img src="<?php echo $CUTEFLOW_SERVER;?>/images/question.png" height="32" width="32"></td>
			<td align="left">
				<table border="0" width="100%">
					<?php
						if ($arrCirculationProcess["nDecissionState"] == 0)
						{
							$ShowSendButton = true;
					?>
							<tr>
								<td align="left">
									<input type="radio" name="Answer" id="Answer" value="false"><?php echo $MAIL_CONTENT_RADIO_NACK;?><br>
								</td>
							</tr>
							<tr>
								<td align="left">
									<input type="radio" checked name="Answer" id="Answer" value="true"><?php echo $MAIL_CONTENT_RADIO_ACK;?><br>
					<?php
						}
						else
						{
							$ShowSendButton = false;
							if ($arrCirculationProcess["nDecissionState"] == 16)
							{
					?>
							<tr>
								<td align="left">
									<strong><?php echo htmlentities($MAIL_CONTENT_ATTETION); ?></strong> <?php echo $MAIL_CONTENT_STOPPED_TEXT; ?>
								</td>
							</tr>
							<tr>
								<td align="left">							
					<?php		
							}
							else
							{
					?>
							<tr>
								<td align="left">
									<strong><?php echo htmlentities($MAIL_CONTENT_ATTETION); ?></strong> <?php echo $MAIL_CONTENT_ATTETION_TEXT; ?>
								</td>
							</tr>
							<tr>
								<td align="left">							
					<?php
							}
						}
						
						if (sizeof($arrSlots) != 0)
						{
					?>
								<br>
								<div style="width: 96%; height: 330px; overflow : auto; border:1px solid Gray;margin-left:25px">
								<table border="0" width="100%" cellpadding="0" cellspacing="0" class="BorderSilver" style="background-color:White;">
								    <tr>
								        <td colspan="2">
								            <table bgcolor="Silver" width="300px">
								                <tr>
								                    <td width="20px"><img src="../images/values.png" height="16" width="16"></td>
								                    <td style="font-weight:bold;"><?php echo htmlentities($MAIL_VALUES_HEADER);?></td>
								                </tr>
								            </table>
								        </td>
								    </tr>
									<?php
										$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
								   	    if ($nConnection)
									    {
										    if (mysql_select_db($DATABASE_DB, $nConnection))
								   			{
												foreach ($arrSlots as $arrSlot)
												{
													?>
													    <tr>        
													        <td style="border-top: 1px solid Silver;">
													            <table>
																<?php
																	$strQuery = "SELECT * FROM cf_inputfield INNER JOIN cf_slottofield ON cf_inputfield.nID = cf_slottofield.nFieldId WHERE cf_slottofield.nSlotId = ".$arrSlot["nID"]."  ORDER BY cf_slottofield.nID ASC";
																	$nResult = mysql_query($strQuery, $nConnection);
								                   					if ($nResult)
												                  	{
								            			       			if (mysql_num_rows($nResult) > 0)
								                   						{
																			$nRunningCounter = 1;
											    		                  	while (	$arrRow = mysql_fetch_array($nResult))
								            			       				{
																				echo "<td class=\"mandatory\" width=\"200px\">".htmlentities($arrRow["strName"]).":</td>";
																				echo "<td width=\"200px\">";
																				
																				$keyId = $arrRow["nFieldId"]."_".$arrSlot["nID"]."_".$arrCirculationProcess["nCirculationFormId"];
																				if ($arrRow["nType"] == 1)
																				{
																					if ( ($arrSlot["nID"] == $arrCirculationProcess["nSlotId"]) &&
																					     ($arrCirculationProcess["nDecissionState"] == 0) )
																					{
																						//--- Slot is allowed to edit
																						echo "<input class=\"FormInput\" type=\"text\" name=\"".$keyId."\" value=\"".$arrValues[$keyId]["strFieldValue"]."\">";
																					}
																					else
																					{
																						echo $arrValues[$keyId]["strFieldValue"];	
																					}
																				}
																				else if ($arrRow["nType"] == 2)
																				{
																					if ( ($arrSlot["nID"] == $arrCirculationProcess["nSlotId"])  &&
																					     ($arrCirculationProcess["nDecissionState"] == 0) )
																					{
																						//--- Slot is allowed to edit
																						echo "<input type=\"checkbox\" name=\"".$keyId."\"";
																						
																						if ($arrValues[$keyId]["strFieldValue"] == "on")
																						{
																							echo " checked";
																						}
																						
																						echo ">";
																					}
																					else
																					{
																						if ($arrValues[$keyId]["strFieldValue"] != "on")
																						{
																							$state = "inactive";
																						}
																						else
																						{
																							$state = "active";
																						}
																						
																						echo "<img src=\"../images/$state.png\" height=\"16\" width=\"16\">";
																					}
																				}
																				echo "</td>";
																													
																				if ($nRunningCounter % 2 == 0)
																				{
																					echo "</tr>\n<tr>\n";
																				}
																				else
																				{
																					echo "<td width=\"10px\">&nbsp;</td>";
																				}
																				
																				$nRunningCounter++;
																			}
																		}
																	}
																?>
																</table>
															</td>
														</tr>
													<?php
												}
											}
										}
										?>
								</table>			
							</div>
						<?PHP
							}
						?>
						</td>
					</tr>
					<tr>
		   				<td colspan="2" align="right">
							<?php
								if ($ShowSendButton)
								{
								?>
									<input type="submit" value="<?php echo $BTN_SAVE;?>" class="Button">
								<?php
								}
								else
								{
									echo "&nbsp;";
								}
							?>
						</td>
		   			</tr>
					<tr>
						<td colspan="2">
							<a style="padding-left:28px" href="<?php echo $CUTEFLOW_SERVER;?>/pages/print/print.php?circid=<?php echo $arrCirculationProcess["nCirculationFormId"];?>&language=<?php echo $_REQUEST["language"];?>&sortby=&start=1" target="_blank"><img src="<?php echo $CUTEFLOW_SERVER;?>/images/printer_small.png" border="0" height="20" width="20" align="absmiddle"> <?php echo $MAIL_CONTENT_PRINTVIEW;?></a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<input type="hidden" name="language" value="<?php echo $_REQUEST["language"];?>">
	<input type="hidden" name="cpid" value="<?php echo $_REQUEST["cpid"];?>">
</form>	
</body>
</html>