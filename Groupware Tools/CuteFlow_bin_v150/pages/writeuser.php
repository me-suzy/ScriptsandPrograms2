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
	//--- write user to database
	include ("../config/config.inc.php");
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");

	$nAccessLevel = 1;
	
	if (isset($_REQUEST["UserAccessLevel"]))
	{
		$nAccessLevel = $_REQUEST["UserAccessLevel"];
	}
	
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	
	if ($nConnection)
	{
		//--- get maximum count of users
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			if ($_REQUEST["userid"] == -1)
			{
				//--- add new user
				$query = "INSERT INTO cf_user values (null, \"".$_REQUEST["strLastName"]."\", \"".$_REQUEST["strFirstName"]."\", \"".$_REQUEST["strEMail"]."\", \"$nAccessLevel\", \"".$_REQUEST["UserName"]."\", \"".md5($_REQUEST["Password1"])."\", \"".$_REQUEST["SubstitudeId"]."\")";	
			}
			else
			{
				//--- update existing user
				$query = "UPDATE cf_user SET strLastName=\"".$_REQUEST["strLastName"]."\", strFirstName=\"".$_REQUEST["strFirstName"]."\", strEMail=\"".$_REQUEST["strEMail"]."\", nAccessLevel=\"$nAccessLevel\", strUserId=\"".$_REQUEST["UserName"]."\", nSubstitudeId=\"".$_REQUEST["SubstitudeId"]."\"";
				
				if ($_REQUEST["Password1"] != "unchanged")
				{
					$query .= ", strPassword=\"".md5($_REQUEST["Password1"])."\"";	
				}
				
				$query .= " WHERE nID=".$_REQUEST["userid"];
			}
			
			$nResult = mysql_query($query, $nConnection);
		}
	}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<script language="JavaScript">
		function onLoad()
		{
			<?php echo "location.href=\"showuser.php?language=".$_REQUEST["language"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\"";?>
		}
	</script>
</head>
<body onLoad="onLoad()">

</body>
