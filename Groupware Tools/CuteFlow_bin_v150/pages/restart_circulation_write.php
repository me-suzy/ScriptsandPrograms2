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
    include ("../lib/datetime.inc.php");
	
	//--- write circulation to database
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			session_start();
			$nSenderId = $_SESSION["SESSION_CUTEFLOW_USERID"];
			$dateSending = date("Y-m-d");
		
			$nEndAction = 0;
			
			if ($_REQUEST["SuccessMail"] == "on")
			{
				$nEndAction = $nEndAction + 1;
			}
			if ($_REQUEST["SuccessArchive"] == "on")
			{
				$nEndAction = $nEndAction + 2;
			}
			
			$cfid = $_REQUEST["circid"];
			
			//-----------------------------------------
			//--- Write next history
			//-----------------------------------------
			$strQuery = "SELECT MAX(nRevisionNumber) FROM cf_circulationhistory WHERE nCirculationFormId=".$cfid;
			$nResult = mysql_query($strQuery, $nConnection);
			
			if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
   				{
   					$arrRow = mysql_fetch_array($nResult);
   					$nRevisionNumber = $arrRow[0] +1;
   				}
    		}
			
			$strQuery = "INSERT INTO cf_circulationhistory VALUES(null, $nRevisionNumber, '$dateSending', '".$_REQUEST["strAdditionalText"]."', $cfid)";
			mysql_query($strQuery, $nConnection);
			
			$strQuery = "SELECT MAX(nID) FROM cf_circulationhistory";
			$nResult = mysql_query($strQuery, $nConnection);
			
			if ($nResult)
    		{
    			if (mysql_num_rows($nResult) > 0)
   				{
   					$arrRow = mysql_fetch_array($nResult);
					$chid = $arrRow[0];
				}
			}			
			
			//-----------------------------------------
			//--- write the attachments
			//-----------------------------------------
			$strFolderName = "../attachments/cf_$cfid/";
			mkdir($strFolderName);
			
			$strFolderName = $strFolderName.time()."/";
			mkdir($strFolderName);
			
			if ($_FILES["attachment1"]["name"] != "")
			{
				move_uploaded_file($_FILES["attachment1"]["tmp_name"], $strFolderName.$_FILES["attachment1"]["name"]);
				$strQuery = "INSERT INTO cf_attachment values (null, '$strFolderName".$_FILES["attachment1"]["name"]."', ".$chid.")";
				mysql_query($strQuery, $nConnection);				
			}
			if ($_FILES["attachment2"]["name"] != "")
			{
				move_uploaded_file($_FILES["attachment2"]["tmp_name"], $strFolderName.$_FILES["attachment2"]["name"]);
				$strQuery = "INSERT INTO cf_attachment values (null, '$strFolderName".$_FILES["attachment2"]["name"]."', ".$chid.")";
				mysql_query($strQuery, $nConnection);				
			}
			if ($_FILES["attachment3"]["name"] != "")
			{
				move_uploaded_file($_FILES["attachment3"]["tmp_name"], $strFolderName.$_FILES["attachment3"]["name"]);
				$strQuery = "INSERT INTO cf_attachment values (null, '$strFolderName".$_FILES["attachment3"]["name"]."', ".$chid.")";
				mysql_query($strQuery, $nConnection);				
			}
			if ($_FILES["attachment4"]["name"] != "")
			{
				move_uploaded_file($_FILES["attachment4"]["tmp_name"], $strFolderName.$_FILES["attachment4"]["name"]);
				$strQuery = "INSERT INTO cf_attachment values (null, '$strFolderName".$_FILES["attachment4"]["name"]."', ".$chid.")";
				mysql_query($strQuery, $nConnection);				
			}
		}
	}
	
	
	include ("send_circulation.php");
	
	$arrNextUser = getNextUserInList(-1, $_REQUEST["listid"], -1);
	
	sendToUser($arrNextUser[0], $cfid, $arrNextUser[1], 0, $chid);
?>
<head>
	<script language="JavaScript">
	<!--
		function siteLoaded()
		{
			location.href = "showcirculation.php?language=<?php echo $_REQUEST["language"];?>&sort=<?php echo $_REQUEST["sort"];?>&start=<?php echo $_REQUEST["start"];?>&archivemode=<?php echo $_REQUEST["archivemode"];?>";
		}
	//-->
	</script>
</head>
<html>
<body onLoad="siteLoaded()">
</body>