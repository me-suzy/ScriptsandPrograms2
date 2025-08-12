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
	
	include ("send_circulation.php");

	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
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
	
			//--- get the waiting circulations
			$arrOpenCirculations = array();
			
			$dateNowMinus4 = subtractDaysFromDate(date("d-m-Y"), $SEND_TO_AFTER_DAYS);
			$dateDB = convertDateToDB($dateNowMinus4);
			$strQuery = "SELECT * FROM cf_circulationprocess WHERE nDecissionState = 0 AND dateInProcessSince < '$dateDB' AND  nIsSubstitiuteOf = 0";
			$nResult = mysql_query($strQuery, $nConnection);
    		if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
    			{
    				while (	$arrRow = mysql_fetch_array($nResult))
    				{
    					$arrOpenCirculations[$arrRow["nID"]] = $arrRow;						
    				}
    			}
    		}		
   		
			//--- send circulation to substitude if waiting days > $SEND_TO_AFTER_DAYS
			foreach ($arrOpenCirculations as $arrDelayedCirculation)
			{
				//--- get substitude user id
				$nSubstituteId = $arrUsers[$arrDelayedCirculation["nUserId"]]["nSubstitudeId"];
				
				if ($nSubstituteId != 0)
				{
					//--- change decission state
					$dateNow = date("Y-m-d");
					$strQuery = "UPDATE cf_circulationprocess SET nDecissionState=8, dateDecission='$dateNow' WHERE nID=".$arrDelayedCirculation["nID"];
					mysql_query($strQuery, $nConnection);
							
					//--- send substitute mail
					sendToUser($nSubstituteId, $arrDelayedCirculation["nCirculationFormId"], $arrDelayedCirculation["nSlotId"], $arrDelayedCirculation["nID"], $arrDelayedCirculation["nCirculationHistoryId"]);
				}
			}
		}
	}	
?>