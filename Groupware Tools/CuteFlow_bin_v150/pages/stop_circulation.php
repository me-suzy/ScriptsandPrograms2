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
	
	function getMaxProcessId($nFormId, $Connection)
    {
        $query = "SELECT MAX(nID) FROM `cf_circulationprocess` WHERE `nCirculationFormId`=".$nFormId;
        $nResult = mysql_query($query, $Connection);

        if ($nResult)
        {
            if (mysql_num_rows($nResult) > 0)
            {
                $arrRow = mysql_fetch_array($nResult);
                
                if ($arrRow)
                {
                    $nMaxId = $arrRow[0];
                    return $nMaxId;
                }           
            }   
        }
    }

	//--- write circulation to database
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			$nMaxId = getMaxProcessId($_REQUEST["circid"], $nConnection);
						
			$strQuery = "UPDATE cf_circulationprocess SET nDecissionState=16 WHERE nID=".$nMaxId;
			mysql_query($strQuery, $nConnection);	
		}
	}
?>
<head>
	<script language="JavaScript">
	<!--
		function siteLoaded()
		{
			location.href = "showcirculation.php?language=<?php echo $_REQUEST["language"];?>&sort=<?php echo $_REQUEST["sortby"];?>&start=<?php echo $_REQUEST["start"];?>&archivemode=<?php echo $_REQUEST["archivemode"];?>";
		}
	//-->
	</script>
</head>
<html>
<body onLoad="siteLoaded()">
</body>