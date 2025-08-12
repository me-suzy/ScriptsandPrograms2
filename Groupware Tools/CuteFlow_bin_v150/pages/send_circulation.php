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
	
	include ("../lib/htmlMimeMail.php");
	include ("../lib/mimetype.php");
	include ("version.inc.php");
	
	function getNextUserInList($nCurUserId, $nMailingListId, $nSlotId)
	{
		global $DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD, $DATABASE_DB;
		
		$arrUserInfo = array();
		
		$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
		$nConnection2 = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
		
		if ( ($nConnection) && ($nConnection2) ) 
		{
			if (mysql_select_db($DATABASE_DB, $nConnection))
			{
				mysql_select_db($DATABASE_DB, $nConnection2);
				
				//$strQuery = "SELECT * FROM cf_slottouser WHERE nMailingListId=$nMailingListId ORDER BY nSlotId ASC, nPosition ASC";
				$strQuery = "SELECT * FROM cf_slottouser INNER JOIN cf_formslot ON cf_slottouser.nSlotId  = cf_formslot.nID WHERE cf_slottouser.nMailingListId=$nMailingListId ORDER BY cf_formslot.nSlotNumber ASC, cf_slottouser.nPosition ASC";
				$nResult = mysql_query($strQuery, $nConnection);
//echo $strQuery."<br>";
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
						$bFoundOne == false;
        				while (	$arrRow = mysql_fetch_array($nResult))
        				{
							if ($nCurUserId == -1)
							{
								//--- lets take the first user
								$arrUserInfo[0] = $arrRow["nUserId"];
								$arrUserInfo[1] = $arrRow["nSlotId"];
								
								return $arrUserInfo;
							}
							else if ($bFoundOne == true)
							{
								$arrUserInfo[0] = $arrRow["nUserId"];
								$arrUserInfo[1] = $arrRow["nSlotId"];
								
								return $arrUserInfo;
							}
							else
							{
								if ( ($arrRow["nUserId"] == $nCurUserId) && 
										($arrRow["nSlotId"] == $nSlotId))
								{
									$bFoundOne = true; //--- next loop returns user	
								}
							}
						}
					}
				}
			}
		}
		
		return $arrUserInfo;
	}

	
	function sendToUser($nUserId, $nCirculationId, $nSlotId, $nCirculationProcessId, $nCirculationHistoryId)
	{
		//echo "$nUserId, $nCirculationId, $nSlotId, $nCirculationProcessId";
		
		global $DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD, $DATABASE_DB, $MAIL_HEADER_PRE, $CUTEFLOW_SERVER;
		global $SMTP_SERVER, $SMTP_PORT, $SMTP_USERID, $SMTP_PWD, $SMTP_USE_AUTH;
		global $SYSTEM_REPLY_ADDRESS;
		
		$language = $_REQUEST["language"];
		
		$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
		if ($nConnection)
		{
			if (mysql_select_db($DATABASE_DB, $nConnection))
			{	
				$mail = new htmlMimeMail();
				$mail->setSMTPParams($SMTP_SERVER, $SMTP_PORT, NULL, $SMTP_USE_AUTH, $SMTP_USERID, $SMTP_PWD);
				
				//------------------------------------------------------
				//--- get the needed informations
				//------------------------------------------------------
				
				//--- circulation form
				$arrForm = array();
				$strQuery = "SELECT * FROM cf_circulationform WHERE nID=$nCirculationId";
				$nResult = mysql_query($strQuery, $nConnection);
				if ($nResult)
	    		{
	    			if (mysql_num_rows($nResult) > 0)
	    			{
	    				$arrForm = mysql_fetch_array($nResult);
					}
				}
				
				//--- circulation history
				$arrHistory = array();
				$strQuery = "SELECT * FROM cf_circulationhistory WHERE nID=$nCirculationHistoryId";
				$nResult = mysql_query($strQuery, $nConnection);
				if ($nResult)
	    		{
	    			if (mysql_num_rows($nResult) > 0)
	    			{
	    				$arrHistory = mysql_fetch_array($nResult);
					}
				}
				
				//--- the attachments
				$strQuery = "SELECT * FROM cf_attachment WHERE nCirculationHistoryId=$nCirculationHistoryId";
				$nResult = mysql_query($strQuery, $nConnection);
	    		if ($nResult)
	    		{
	    			if (mysql_num_rows($nResult) > 0)
	    			{
	    				while (	$arrRow = mysql_fetch_array($nResult))
	    				{
							$attachment = $mail->getFile($arrRow["strPath"]);
							
							$arrPathParts = split("[/\]", $arrRow["strPath"]);        
					        $strFileName = $arrPathParts[sizeof($arrPathParts)-1];
							
							$mimetype = new mimetype();
					      	$filemime = $mimetype->getType($strFileName);
							
							$mail->addAttachment($attachment, $strFileName, $filemime);							
						}
					}
				}
				
				//------------------------------------------------------
				//--- update status in circulationprocess table
				//------------------------------------------------------
				$dateInProcess = date("Y-m-d");
				$strQuery = "INSERT INTO cf_circulationprocess values (null, $nCirculationId, $nSlotId, $nUserId, '$dateInProcess', 0, '0000-00-00', $nCirculationProcessId, $nCirculationHistoryId)";
				mysql_query($strQuery, $nConnection);	
							
				//------------------------------------------------------
				//--- generate email message
				//------------------------------------------------------				
				$strQuery = "SELECT nID FROM cf_circulationprocess WHERE nSlotId=$nSlotId AND nUserId=$nUserId AND nCirculationFormId=$nCirculationId AND nCirculationHistoryId=$nCirculationHistoryId";
				$nResult = mysql_query($strQuery, $nConnection);
	    		if ($nResult)
	    		{
	    			if (mysql_num_rows($nResult) > 0)
	    			{
	    				$arrLastRow = array();
	    				
	    				while ($arrRow = mysql_fetch_array($nResult))
	    				{
	    					$arrLastRow = $arrRow;
	    				}
						$Circulation_cpid = $arrLastRow[0];
					}
				}				
				
				//--- get mail template from file
				$strMessage = $mail->getFile("../language_files/$language/mail_template.html");
								
				//--- fill placeholders
				$Circulation_Name = $arrForm["strName"];
				$Circulation_AdditionalText = str_replace("\n", "<br>", $arrHistory["strAdditionalText"]);
				
				$strMessage = str_replace("CIRCULATION_NAME", $Circulation_Name, $strMessage);
				$strMessage = str_replace("CIRCULATION_ADDITIONALTEXT", $Circulation_AdditionalText, $strMessage);
				$strMessage = str_replace("CIRCULATION_SERVER", $CUTEFLOW_SERVER, $strMessage);
				$strMessage = str_replace("CIRCULATION_CPID", $Circulation_cpid, $strMessage);
				$strMessage = str_replace("CUTEFLOW_VERSION", $CUTEFLOW_VERSION, $strMessage);
				
				$mail->setHtml($strMessage);				
				
				//------------------------------------------------------
				//--- send email to user
				//------------------------------------------------------
				$strQuery = "SELECT * FROM cf_user WHERE nID = $nUserId";
				$nResult = mysql_query($strQuery, $nConnection);
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
						$arrRow = mysql_fetch_array($nResult);
				
						$mail->setFrom($SYSTEM_REPLY_ADDRESS);
						$mail->setSubject($MAIL_HEADER_PRE.$arrForm["strName"]);
						$mail->setHeader('X-Mailer', 'CuteFlow Document Workflow System');
						$mail->setHeader('Date', date('D, d M y H:i:s O'));
						
						$result = $mail->send(array($arrRow["strEMail"]), 'smtp');
						if (!$result) 
						{
							echo "Error:";
							print_r($mail->errors);
						}
						else
						{
							return true;
						}
					}
				}	
			}
		}
		
		return false;
	}
	
	function sendMessageToSender($nSenderId, $nLastStationId, $strMessageFile, $strCirculationName, $strEndState)
	{
		global $DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD, $DATABASE_DB, $MAIL_HEADER_PRE, $CUTEFLOW_SERVER;
		global $SMTP_SERVER, $SMTP_PORT, $SMTP_USERID, $SMTP_PWD, $SMTP_USE_AUTH, $MAIL_ENDACTION_DONE_REJECT, $MAIL_ENDACTION_DONE_SUCCESS;
		global $SYSTEM_REPLY_ADDRESS, $CIRCULATION_DONE_MESSSAGE_REJECT, $CIRCULATION_DONE_MESSSAGE_SUCCESS;
		
		$language = $_REQUEST["language"];
		$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
		if ($nConnection)
		{
			if (mysql_select_db($DATABASE_DB, $nConnection))
			{	
				$mail = new htmlMimeMail();
				$mail->setSMTPParams($SMTP_SERVER, $SMTP_PORT, NULL, $SMTP_USE_AUTH, $SMTP_USERID, $SMTP_PWD);
				
				//--- get mail template from file
				$strMessage = $mail->getFile("../language_files/$language/mail_".$strMessageFile."_template.html");
				$strMessage = str_replace("CIRCULATION_NAME", $strCirculationName, $strMessage);
				$strMessage = str_replace("CIRCULATION_ADDITIONALTEXT", $Circulation_AdditionalText, $strMessage);
				$strMessage = str_replace("CIRCULATION_SERVER", $CUTEFLOW_SERVER, $strMessage);
				$strMessage = str_replace("CIRCULATION_DONE_DATE", date('d M y H:i'), $strMessage);
				$strMessage = str_replace("CUTEFLOW_VERSION", $CUTEFLOW_VERSION, $strMessage);
				
				//--- getting last station
				$query = "SELECT * FROM cf_user WHERE nID=".$nLastStationId;
				$nResult = mysql_query($query, $nConnection);
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
						$arrRow = mysql_fetch_array($nResult);
						$strLastStation = $arrRow["strLastName"].", ".$arrRow["strFirstName"]." (".$arrRow["strUserId"].")";	
        			}
        		}
				$strMessage = str_replace("CIRCULATION_DONE_LASTSTATION", $strLastStation, $strMessage);
				
				eval ("\$strEndMessage = \"\$CIRCULATION_DONE_MESSSAGE_$strEndState\";");
				$strMessage = str_replace("CIRCULATION_DONE_MESSAGE", $strEndMessage, $strMessage);
				$mail->setHtml($strMessage);
								
				$strQuery = "SELECT * FROM cf_user WHERE nID = $nSenderId";
				$nResult = mysql_query($strQuery, $nConnection);
        		if ($nResult)
        		{
        			if (mysql_num_rows($nResult) > 0)
        			{
						$arrRow = mysql_fetch_array($nResult);
				
						$mail->setFrom($SYSTEM_REPLY_ADDRESS);
						
						eval ("\$strEndSubject = \"\$MAIL_ENDACTION_DONE_$strEndState\";");
						$mail->setSubject($MAIL_HEADER_PRE.$strCirculationName.$strEndSubject);
						$mail->setHeader('X-Mailer', 'CuteFlow Document Workflow System');
						$mail->setHeader('Date', date('D, d M y H:i:s O'));
						$result = $mail->send(array($arrRow["strEMail"]), 'smtp');
						if (!$result) 
						{
							echo "Error:";
							print_r($mail->errors);
						}
						else
						{
							return true;
						}
					}
				}
			}
		}
	}
?>