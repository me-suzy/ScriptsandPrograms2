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
	session_start();
	
	include ("../config/config.inc.php");
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
    include ("../lib/datetime.inc.php");
    include ("../lib/viewutils.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title></title>
   	<link rel="stylesheet" href="format.css" type="text/css">
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="tooltip.js"></SCRIPT>
	<script type="text/javascript">
    	maketip('skip_station','<?php echo $CIRCDETAIL_TIP_SKIP;?>');
    	maketip('retry_station','<?php echo $CIRCDETAIL_TIP_RETRY;?>');
    	
    	function Go(nId) 
		{
			document.forms["RevisionForm"].submit();
		}
	</script>
</head>
<?php
    //--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
            //--- get the single circulation form
			$query = "select * from cf_circulationform WHERE nID=".$_REQUEST["circid"];
			$nResult = mysql_query($query, $nConnection);

			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrCirculationForm = mysql_fetch_array($nResult);				
				}
			}
			
			//-----------------------------------------------
			//--- get history (all revisions)
			//-----------------------------------------------
			$arrHistoryData = array();
			$nMaxRevisionId = 0;
			$strQuery = "SELECT * FROM cf_circulationhistory WHERE nCirculationFormId=".$_REQUEST["circid"]." ORDER BY nRevisionNumber DESC";
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					if ($nMaxRevisionId == 0)
    					{
    						$nMaxRevisionId = $arrRow["nID"];	
    					}
    					
    					$arrHistoryData[$arrRow["nID"]] = $arrRow;
    				}
    			}
    		}
    		
    		if ($_REQUEST["nRevisionId"] == "")
    		{
    			$_REQUEST["nRevisionId"] = $nMaxRevisionId;
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
			//--- get the mailing list
			//-----------------------------------------------
			$query = "select * from cf_mailinglist WHERE nID=".$arrCirculationForm["nMailingListId"];
			$nResult = mysql_query($query, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$arrMailingList = mysql_fetch_array($nResult);				
				}
			}
			
			
            //-----------------------------------------------
            //--- get the template
            //-----------------------------------------------	            
            $strQuery = "SELECT * FROM cf_formtemplate WHERE nID=".$arrMailingList["nTemplateId"];
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				$arrTemplate = mysql_fetch_array($nResult);
   					$strTemplateName = $arrTemplate["strName"];
    			}
    		}
            
            //-----------------------------------------------
            //--- get the form slots
            //-----------------------------------------------	            
            $arrSlots = array();
            $strQuery = "SELECT * FROM cf_formslot WHERE nTemplateID=".$arrMailingList["nTemplateId"]."  ORDER BY nSlotNumber ASC";
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					$arrSlots[] = $arrRow;
    				}
    			}
    		}
			
			//-----------------------------------------------
            //--- get the field values
            //-----------------------------------------------	            
            $arrValues = array();
            $strQuery = "SELECT * FROM cf_fieldvalue WHERE nFormId=".$_REQUEST["circid"];
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					$arrValues[$arrRow["nInputFieldId"]."_".$arrRow["nSlotId"]] = $arrRow;
    				}
    			}
    		}
			
			//-----------------------------------------------
            //--- get the form process detail
            //-----------------------------------------------	            
            $arrProcessInformation = array();
			$arrProcessInformationSubstitute = array();
			
            $strQuery = "SELECT * FROM cf_circulationprocess WHERE nCirculationFormId=".$_REQUEST["circid"]." AND nCirculationHistoryId=".$_REQUEST["nRevisionId"];
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				$nPosInSlot = -1;
    				$nLastSlotId = -1;
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					if ($arrRow["nSlotId"] != $nLastSlotId)
    					{
    						$nLastSlotId = $arrRow["nSlotId"];	
    						$nPosInSlot = -1;
    					}
    					$nPosInSlot++;
    					
						if ($arrRow["nIsSubstitiuteOf"] != 0)
						{
							$arrProcessInformationSubstitute[$arrRow["nIsSubstitiuteOf"]] = $arrRow;
						}
						else
						{
    						$arrProcessInformation[$arrRow["nUserId"]."_".$arrRow["nSlotId"]."_".$nPosInSlot] = $arrRow;
						}
    				}
    			}
    		}
		}
    }
	
	function printUser($arrRow, $bIsSubstitute, $nUserId, $bLastUser)
	{
		global $arrUsers, $_REQUEST;
		global $CIRCDETAIL_RECEIVE, $CIRCDETAIL_STATE_WAITING, $CIRCDETAIL_STATE_OK, $CIRCDETAIL_STATE_STOP;
		global $CIRCDETAIL_STATE_SKIPPED, $CIRCDETAIL_STATE_SUBSTITUTE, $CIRCDETAIL_PROCESS_DURATION;
		global $CIRCDETAIL_DAYS, $CIRCDETAIL_STATE_DENIED;
		
		echo "<tr style=\"height:22px;\">\n";
		
		if ($bIsSubstitute == false)
		{
        	echo "<td width=\"20px\"><img src=\"../images/singleuser.png\" height=\"19\" width=\"16\"></td>\n";
       		echo "<td width=\"140px\">".$arrUsers[$nUserId]["strUserId"]."</td>\n";
		}
		else
		{
			echo "<td width=\"20px\" align=\"right\"><img src=\"../images/right.png\" height=\"16\" width=\"16\"></td>\n";
       		echo "<td width=\"140px\"><img src=\"../images/singleuser2.png\" height=\"19\" width=\"16\" align=\"absmiddle\">&nbsp;&nbsp;".$arrUsers[$arrRow["nUserId"]]["strUserId"]."</td>\n";
		}
	
		//--- The receiving date
		$dateReceive = convertDateFromDB($arrRow["dateInProcessSince"]);
		if ($dateReceive == "..")
		{
			$dateReceive = "-";
			echo "<td width=\"150px\">&nbsp;</td>\n";
		}
		else
		{
			echo "<td width=\"150px\" nowrap>".$dateReceive."</td>\n";
		}
	                                  
		//--- The process state
		if (!$arrRow)
		{
			echo "<td width=\"16px\">&nbsp;</td>\n";
	        echo "<td width=\"110px\">&nbsp;</td>\n";
		}
		else
		{
			switch ($arrRow["nDecissionState"])
			{
				case 0: $strImage = "state_wait.png";
						$strText = $CIRCDETAIL_STATE_WAITING;
						break;
				case 1: $strImage = "state_ok.png";
						$strText = $CIRCDETAIL_STATE_OK;
						break;
				case 2: $strImage = "state_stop.png";
						$strText = "<strong style=\"color:Red;\">$CIRCDETAIL_STATE_DENIED</strong>";
						break;
				case 4: $strImage = "state_skip.png";
						$strText = $CIRCDETAIL_STATE_SKIPPED;
						break;
				case 8: $strImage = "state_skip.png";
						$strText = $CIRCDETAIL_STATE_SUBSTITUTE;													
						break;
				case 16: $strImage = "stop.gif";
						$strText = "<strong style=\"color:Red;\">$CIRCDETAIL_STATE_STOP</strong>";
						break;
						
			}
			echo "<td width=\"16px\">";
			echo "<img src=\"../images/$strImage\" height=\"16\" width=\"16\">";
			echo "</td>\n";
	       	echo "<td width=\"200px\" nowrap>$strText</td>\n";
		}
		
		//--- the working duration
		if ($dateReceive != "-")
		{
			if ($arrRow["nDecissionState"] == 0)
			{
	            $nDays = floor(dateDiff($dateReceive, date("d.m.Y")));
			}
			else
			{
				if ($arrRow["nDecissionState"] != 16)
				{
					$dateDecission = convertDateFromDB($arrRow["dateDecission"]);
					$nDays = floor(dateDiff($dateReceive, $dateDecission));
				}
				else
				{
					$nDays = "-";
				}
			}
			
            echo "<td nowrap><strong style=\"color:".getDelayColor($nDays).";\">$nDays</strong> $CIRCDETAIL_DAYS</td>\n";
		}
		else
		{
            echo "<td>&nbsp;</td>\n";
		}
		
		//--- the actions
		echo "<td nowrap>";
		if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
		{
			if ($dateReceive != "-")
			{
				$nState = $arrRow["nDecissionState"];
				if ( ($nState == 0) || ($nState == 2) )
				{
					echo "<a onMouseOver=\"tip('skip_station')\" onMouseOut=\"untip()\" href=\"skipuser.php?circid=".$_REQUEST["circid"]."&language=".$_REQUEST["language"]."&cpid=".$arrRow["nID"]."&start=".$_REQUEST["start"]."&archivemode=".$_REQUEST["archivemode"]."&sortby=".$_REQUEST["sortby"]."\">";
					echo "<img src=\"../images/stepover_co.png\" border=\"0\" height=\"16\" width=\"16\">";
					echo "</a>&nbsp;&nbsp;";
					
					echo "<a onMouseOver=\"tip('retry_station')\" onMouseOut=\"untip()\" href=\"retryuser.php?circid=".$_REQUEST["circid"]."&language=".$_REQUEST["language"]."&cpid=".$arrRow["nID"]."&start=".$_REQUEST["start"]."&sortby=".$_REQUEST["sortby"]."&archivemode=".$_REQUEST["archivemode"]."\">";
					echo "<img src=\"../images/retry.png\" border=\"0\" height=\"16\" width=\"16\">";
					echo "</a>";													
				}				
				else if ($bLastUser == true)
				{
					echo "<a onMouseOver=\"tip('retry_station')\" onMouseOut=\"untip()\" href=\"retryuser.php?circid=".$_REQUEST["circid"]."&language=".$_REQUEST["language"]."&cpid=".$arrRow["nID"]."&start=".$_REQUEST["start"]."&sortby=".$_REQUEST["sortby"]."&archivemode=".$_REQUEST["archivemode"]."\">";
					echo "<img src=\"../images/retry.png\" border=\"0\" height=\"16\" width=\"16\">";
					echo "</a>";													
				}
			}
		}
		echo "&nbsp;</td>";
        echo "</tr>\n";
	}

?>
<body bgcolor="White">
<br>
<form method="POST" id="RevisionForm">
	<table border="0" width="90%" cellpadding="0" cellspacing="0" class="BorderSilver">
	    <tr>
	        <td colspan="3">
	            <table bgcolor="Silver" width="300px">
	                <tr>
	                    <td width="20px"><img src="../images/circulate.png" height="16" width="16"></td>
	                    <td style="font-weight:bold;"><?php echo $arrCirculationForm["strName"];?></td>
	                </tr>
	            </table>
	        </td>
	    </tr>
	    <tr style="height:22px;">
	        <td width="20px"><img src="../images/template_type.png" height="16" width="16"></td>
	        <td width="150px"><?php echo $CIRCDETAIL_TEMPLATE_TYPE;?></td>
	        <td><?php echo $strTemplateName;?></td>
	    </tr>
	    <tr style="height:22px;">
	        <td width="20px"><img src="../images/singleuser2.png" height="19" width="16"></td>
	        <td width="150px"><?php echo $CIRCDETAIL_SENDER;?></td>
	        <td>
	        <?php
	            echo $arrUsers[$arrCirculationForm["nSenderId"]]["strLastName"].", ".$arrUsers[$arrCirculationForm["nSenderId"]]["strFirstName"]." (".$arrUsers[$arrCirculationForm["nSenderId"]]["strUserId"].")";
	        ?>
	        </td>
	    </tr>
	    <tr style="height:22px;">
	        <td width="20px"><img src="../images/calendar.png" height="16" width="16" ></td>
	        <td width="150px"><?php echo $CIRCDETAIL_SENDREV;?></td>
	        <td>
	        	<select name="nRevisionId" id="nRevisionId" class="FormInput" onChange="Go(this.form.nRevisionId.options[this.form.nRevisionId.options.selectedIndex].value)">
				<?php 
	        		foreach ($arrHistoryData as $arrCurHistory)
	        		{
						$check = "";
						if($_REQUEST["nRevisionId"] == $arrCurHistory["nID"])
							$check = "selected";
						
						echo "<option value=\"".$arrCurHistory["nID"]."\" ".$check.">#".$arrCurHistory["nRevisionNumber"]." - ".convertDateFromDB($arrCurHistory["dateSending"])."</option>";
					}
				?>
				</select>
				
	        </td>
	    </tr>
		 <tr style="height:22px; padding-top: 4px;">
		 	<td style="padding-top: 4px;" width="20px" valign="top"><img src="../images/description.gif" height="16" width="16" ></td>
	        <td style="padding-top: 4px;" width="150px" valign="top"><?php echo $CIRCDETAIL_DESCRIPTION;?></td>
	        <td style="padding-top: 4px;" valign="top"><?php echo str_replace("\n", "<br>", $arrHistoryData[$_REQUEST["nRevisionId"]]["strAdditionalText"]);?></td>
		 </tr>
	</table>
</form>
<br>

<?php
if ($view != "print")
{
?>
<table border="0" width="90%" cellpadding="0" cellspacing="0" class="BorderSilver">
    <tr>
        <td colspan="5">
            <table bgcolor="Silver" width="300px">
                <tr>
                    <td width="20px"><img src="../images/attach.png" height="16" width="16"></td>
                    <td style="font-weight:bold;"><?php echo $CIRCDETAIL_ATTACHMENT;?></td>
                </tr>
            </table>
        </td>
    </tr>
    <?php
    //--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
            //-----------------------------------------------
    		//--- get all attachments
            //-----------------------------------------------
            $strQuery = "SELECT * FROM cf_attachment WHERE  nCirculationHistoryId=".$arrHistoryData[$_REQUEST["nRevisionId"]]["nID"];
    		$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
                    $nRunningNumber = 1;
                    echo "<tr>\n";
					while (	$arrRow = mysql_fetch_array($nResult))
    				{
                        echo "<td>\n";
						echo "<table>\n<tr>\n";
    					echo "<td style=\"height:22px;\" width=\"20px\"><img src=\"../images/document.png\" height=\"16\" width=\"16\"></td>\n";
                        echo "<td style=\"height:22px;\"><a target=\"_blank\" href=\"".$arrRow["strPath"]."\">".getFileNameFromPath($arrRow["strPath"])."</td>\n";
                    	echo "</tr>\n</table\n";
						echo "</td>\n";
						
                        if ($nRunningNumber % 2 == 0)
                        {
                            echo "</tr>\n<tr>";
                        }
                        else
                        {
                            echo "<td style=\"height:22px;\" width=\"10px\">&nbsp;</td>\n";
                        }
                        
                        $nRunningNumber++;
        			}
					echo "</tr>\n";
    			}
    		}
        }
    }
    ?>
</table>
<br>
<table border="0" width="90%" cellpadding="0" cellspacing="0" class="BorderSilver">
    <tr>
        <td colspan="2">
            <table bgcolor="Silver" width="300px">
                <tr>
                    <td width="20px"><img src="../images/history.png" height="16" width="16"></td>
                    <td style="font-weight:bold;"><?php echo $CIRCDETAIL_HISTORY;?></td>
                </tr>
            </table>
        </td>
    </tr>
	
	<tr>
		<td colspan="2">
			<table width="100%">
				<tr style="background-color:#EEEEEE;">
					<td>&nbsp;</td>
					<td><?php echo $CIRCDETAIL_STATION;?></td>
					<td><?php echo $CIRCDETAIL_RECEIVE;?></td>
					<td colspan="2"><?php echo $CIRCDETAIL_STATE;?></td>
					<td><?php echo $CIRCDETAIL_PROCESS_DURATION;?></td>
					<td><?php echo $CIRCDETAIL_COMMANDS;?></td>
				</tr>
	<?php
        $nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
        if ($nConnection)
	    {
		    if (mysql_select_db($DATABASE_DB, $nConnection))
    		{
    			$nPICount = 0;
    			for ($nIndex = 0; $nIndex < sizeof($arrSlots); $nIndex++)
    			{
    				$nPosInSlot = 0;
    				$arrSlot = $arrSlots[$nIndex];

    				$strQuery = "SELECT * FROM cf_slottouser WHERE nMailingListId=".$arrCirculationForm["nMailingListId"]." AND nSlotId=".$arrSlot["nID"]." ORDER BY nPosition ASC";
                    $nResult = mysql_query($strQuery, $nConnection);
                    if ($nResult)
	   		        {
                    	if (mysql_num_rows($nResult) > 0)
                        {
							?>
								<tr>
							    	<td colspan="8" style="border-bottom: 1px solid Silver; padding-top:8px;"><strong><?php echo $MAILLIST_EDIT_FORM_SLOT.": ".$arrSlot[1];?></strong></td>
								</tr>
			                <?php
                           	while (	$arrRow = mysql_fetch_array($nResult))
                        	{
								$arrCurPi = $arrProcessInformation[$arrRow["nUserId"]."_".$arrSlot["nID"]."_".$nPosInSlot];
								$nPICount++;
								
								$bLastUser = ($nPICount == sizeof($arrProcessInformation)) ? true : false;
								printUser($arrCurPi, false, $arrRow["nUserId"], $bLastUser);
											
								$nCurPiId = $arrCurPi["nID"];
								$arrSubstitute = $arrProcessInformationSubstitute[$nCurPiId];
								if ($arrSubstitute)
								{
									//--- Message was sent to substitute
									printUser($arrSubstitute, true, $arrProcessInformationSubstitute, $arrSubstitute["nUserId"], $bLastUser);
								}																				
								
								$nPosInSlot++;
                         	}
    		           }
    		       }
                }
            }
        }
    ?>
			</table>		
		</td>
	</tr>
</table>
<br>
<?php
}	//--- end if ($view != "print")
?>
<table border="0" width="90%" cellpadding="0" cellspacing="0" class="BorderSilver">
    <tr>
        <td colspan="2">
            <table bgcolor="Silver" border="0" width="300px">
                <tr>
                    <td width="20px"><img src="../images/values.png" height="16" width="16"></td>
                    <td style="font-weight:bold;"><?php echo $CIRCDETAIL_VALUES;?></td>
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
									<tr>
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
												echo "<td class=\"mandatory\" width=\"200px\">".$arrRow["strName"].":</td>";
												echo "<td width=\"200px\">";
												if ($arrRow["nType"] == 1)
												{
													echo $arrValues[$arrRow["nFieldId"]."_".$arrSlot["nID"]]["strFieldValue"];	
												}
												else if ($arrRow["nType"] == 2)
												{
													if ($arrValues[$arrRow["nFieldId"]."_".$arrSlot["nID"]]["strFieldValue"] != "on")
													{
														$state = "inactive";
													}
													else
													{
														$state = "active";
													}
													
													echo "<img src=\"../images/$state.png\" height=\"16\" width=\"16\">";
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
									</tr>
								</table>
							</td>
						</tr>
					<?php
				}
			}
		}
		?>
</table>
<br>
<br>
<br>
</body>
</html>
