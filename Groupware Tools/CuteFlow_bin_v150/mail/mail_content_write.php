<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include ("../config/config.inc.php");
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
    include ("../lib/datetime.inc.php");
	include ("../pages/send_circulation.php");
	
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	$nConnection2 = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			mysql_select_db($DATABASE_DB, $nConnection2);
			
			//-----------------------------------------------
			//--- Write user inputs to database
			//-----------------------------------------------
			if ($_REQUEST["Answer"] == "false")
			{
				$dateNow = date("Y-m-d");
				$strQuery = "UPDATE cf_circulationprocess SET nDecissionState=2, dateDecission='$dateNow' WHERE nID=".$_REQUEST["cpid"];
				mysql_query($strQuery, $nConnection);
				
				//--- send done email to sender if wanted
				$strQuery = "SELECT * FROM cf_circulationprocess WHERE nID=".$_REQUEST["cpid"];
				$nResult = mysql_query($strQuery, $nConnection);
				if ($nResult)
				{
					if (mysql_num_rows($nResult) > 0)
					{
						$arrProcessInfo = mysql_fetch_array($nResult);
					}
				}
				
				$strQuery = "SELECT nEndAction, nSenderId, strName FROM cf_circulationform WHERE nID=".$arrProcessInfo["nCirculationFormId"];
				$nResult = mysql_query($strQuery, $nConnection);
				if ($nResult)
				{
					if (mysql_num_rows($nResult) > 0)
					{
						$arrRow = mysql_fetch_array($nResult);
						
						$nEndAction = $arrRow["nEndAction"];
						$nSenderId = $arrRow["nSenderId"];
						$strCircName = $arrRow["strName"];
						
						sendMessageToSender($nSenderId, $arrProcessInfo["nUserId"], "done", $strCircName, "REJECT");
					}
				}
			}
			else
			{
				//--- get the current decission state
				$strQuery = "SELECT nDecissionState FROM cf_circulationprocess WHERE nID=".$_REQUEST["cpid"];
				$nResult = mysql_query($strQuery, $nConnection);
				if ($nResult)
				{
					if (mysql_num_rows($nResult) > 0)
					{
						$arrProcessInfo = mysql_fetch_array($nResult);
						
//						echo $arrProcessInfo["nDecissionState"];
						if ($arrProcessInfo["nDecissionState"] != 0)
						{
							$bAlreadySend = true;
						}
						else
						{
							$bAlreadySend = false;
						}
					}
				}
				
				if ($bAlreadySend == false)
				{			
					$dateNow = date("Y-m-d");
					$strQuery = "UPDATE cf_circulationprocess SET nDecissionState=1, dateDecission='$dateNow'  WHERE nID=".$_REQUEST["cpid"];
					mysql_query($strQuery, $nConnection);
	
					$strQuery = "SELECT * FROM cf_circulationprocess WHERE nID=".$_REQUEST["cpid"];
					$nResult = mysql_query($strQuery, $nConnection);
					if ($nResult)
					{
						if (mysql_num_rows($nResult) > 0)
						{
							$arrProcessInfo = mysql_fetch_array($nResult);
						}
					}				
					
					while(list($key, $value) = each($HTTP_GET_VARS))
					{
						$arrValues = explode("_", $key);
						
						if (sizeof($arrValues) > 2)
						{
							//--- Test if value already exists
							$nFieldId = $arrValues[0];
							$nSlotId = $arrValues[1];
							$nFormId = $arrValues[2];
							
							$strQuery = "SELECT nID FROM cf_fieldvalue WHERE nInputFieldId=$nFieldId AND nSlotId=$nSlotId AND nFormId=$nFormId";
							$nResult = mysql_query($strQuery, $nConnection);
							
							if ($nResult)
					   		{
					   			if (mysql_num_rows($nResult) > 0)
								{
									$strQuery = "UPDATE cf_fieldvalue SET strFieldValue='$value' WHERE nInputFieldId=".$arrValues[0]." AND nSlotId=".$arrValues[1]." AND nFormId=".$arrProcessInfo["nCirculationFormId"];
									//echo $strQuery."<br>";
								}
								else
								{
									$strQuery = "INSERT INTO cf_fieldvalue values(null, ".$arrValues[0].", '$value', ".$arrValues[1].", ".$arrProcessInfo["nCirculationFormId"].")";
									//echo $strQuery."<br>";
								}
							}
								
							mysql_query($strQuery, $nConnection);
						}
					} 			
					
					//-----------------------------------------------
					//--- send mail to next user in list
					//-----------------------------------------------
					$strQuery = "SELECT * FROM cf_mailinglist INNER JOIN cf_circulationform ON cf_mailinglist.nID = cf_circulationform.nMailingListId WHERE cf_circulationform.nID=".$arrProcessInfo["nCirculationFormId"];
					$nResult = mysql_query($strQuery, $nConnection);
					if ($nResult)
					{
						if (mysql_num_rows($nResult) > 0)
						{
							$arrRow = mysql_fetch_array($nResult);
							
							$nListId = $arrRow[0];
						}
					}
					
					if ($arrProcessInfo["nIsSubstitiuteOf"] != 0)
					{
						$strQuery = "SELECT nUserId FROM cf_circulationprocess WHERE nID=".$arrProcessInfo["nIsSubstitiuteOf"];
						$nResult = mysql_query($strQuery, $nConnection);
						if ($nResult)
						{
							if (mysql_num_rows($nResult) > 0)
							{
								$arrRow = mysql_fetch_array($nResult);
								
								$nCurUserId = $arrRow[0];
							}
						}	
						
						$arrNextUser = getNextUserInList($nCurUserId, $nListId, $arrProcessInfo["nSlotId"]);
						
						if ( ($arrNextUser[0] == $arrProcessInfo["nUserId"]) && ($arrNextUser[1] == $arrProcessInfo["nSlotId"]))
						{
							//--- attention if following situation: Circulation is send to substitute of
							//--- user A. Subsititute is B. Next user of A is B too. So B is getting the 
							//--- circulation 2 times.
							
							//--- insert "done" entry to process table
							//echo "double chocolata ".$arrNextUser[0]."<br>";
							$strInsertQuery = "INSERT INTO cf_circulationprocess VALUES (null, ".
															$arrProcessInfo["nCirculationFormId"].", ".
															$arrProcessInfo["nSlotId"].", ".
															$arrNextUser[0].", ".
															"'".date("Y-m-d")."', ".
															"1, ".
															"'".date("Y-m-d")."', ".
															"0".
															$arrProcessInfo["nCirculationHistoryId"].
															")";
							mysql_query($strInsertQuery, $nConnection2);
							
							//--- and went on with next user
							$arrNextUser = getNextUserInList($arrNextUser[0], $nListId, $arrProcessInfo["nSlotId"]);
							//echo "next = ".$arrNextUser[0]."<br>";
						}
					}
					else
					{
						$arrNextUser = getNextUserInList($arrProcessInfo["nUserId"], $nListId, $arrProcessInfo["nSlotId"]);
					}
					
					if ($arrNextUser[0] != "")
					{
						sendToUser($arrNextUser[0], $arrProcessInfo["nCirculationFormId"], $arrNextUser[1], 0, $arrProcessInfo["nCirculationHistoryId"]);
					}
					else
					{
						//--- send done email to sender if wanted
						$strQuery = "SELECT nEndAction, nSenderId, strName FROM cf_circulationform WHERE nID=".$arrProcessInfo["nCirculationFormId"];
						$nResult = mysql_query($strQuery, $nConnection);
						if ($nResult)
						{
							if (mysql_num_rows($nResult) > 0)
							{
								$arrRow = mysql_fetch_array($nResult);
								
								$nEndAction = $arrRow["nEndAction"];
								$nSenderId = $arrRow["nSenderId"];
								$strCircName = $arrRow["strName"];
								
								$nShouldArchived = $nEndAction & 2;
								$nShouldMailed = $nEndAction & 1;
								if ($nShouldMailed == 1)
								{
									sendMessageToSender($nSenderId, $arrProcessInfo["nUserId"], "done", $strCircName, "SUCCESS");
								}
								if ($nShouldArchived == 2)
								{
									$strQuery = "UPDATE cf_circulationform SET bIsArchived=1 WHERE nID=".$arrProcessInfo["nCirculationFormId"];
									mysql_query($strQuery, $nConnection);
								}
							}
						}
					}
				}
			}
		}
	}	
?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="<?php echo $CUTEFLOW_SERVER;?>/pages/format.css" type="text/css">
</head>
<body>
	<br>
	<br>
	<div align="center">
		<table class="note" width="350px" border="0">
			<tr>
				<td valign="top"><img src="<?php echo $CUTEFLOW_SERVER;?>/images/stop2.png" height="48" width="48" alt="stop2"></td>
				<td>
					<?php 
						if ($bAlreadySend == 1)
						{
							echo $MAIL_CONTENT_SENT_ALREADY;
						}
						else
						{
							if ($_REQUEST["Answer"] == "true")
							{
								echo $MAIL_ACK;
							}
							else
							{
								echo $MAIL_NACK;
							}
						}
					?>
				</td>
			</tr>
			<tr>
				<td style="height:10px" colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="right"><a href="javascript:window.close();"><?php echo $MAIL_CLOSE_WINDOW;?></a></td>
			</tr>
		</table>	
	</div>
</body>
</html>
