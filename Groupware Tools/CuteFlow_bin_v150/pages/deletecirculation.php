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

    function delete_file($file)
    {
        $delete = @unlink($file);
        clearstatcache();
        if (@file_exists($file)) 
        {
            $filesys = eregi_replace("/","\\",$file);
            $delete = @system("del $filesys");
            clearstatcache();
      
            if (@file_exists($file)) 
            {
                $delete = @chmod ($file, 0775);
                $delete = @unlink($file);
                $delete = @system("del $filesys");
            }
        }
        clearstatcache();
        if (@file_exists($file))
        {
            return false;
        }
        else
        {
            return true;
        }
    }  // end function
    
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	
	if ($nConnection)
	{
		//--- get maximum count of users
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
            //--- delete the form
			$query = "DELETE FROM cf_circulationform WHERE nID=".$_REQUEST["cfid"];
			$nResult = mysql_query($query, $nConnection);
            
            //--- delete the Process history
            $query = "DELETE FROM cf_circulationprocess WHERE nCirculationFormId =".$_REQUEST["cfid"];
			$nResult = mysql_query($query, $nConnection);   
            
            //--- delete the values
            $query = "DELETE FROM cf_slottouser WHERE nCirculationId =".$_REQUEST["cfid"];
			$nResult = mysql_query($query, $nConnection);   
            
            $query = "SELECT nValueId FROM cf_slottovalue WHERE nFormId=".$_REQUEST["cfid"];
            $nResult = mysql_query($query, $nConnection);
            if ($nResult)
			{
                $arrValueIds = array();
				if (mysql_num_rows($nResult) > 0)
				{
					while (	$arrRow = mysql_fetch_array($nResult))
					{	
					    $arrValueIds[] = $arrRow["nValueId"];	
					}				
				}
			}
            
			$query = "DELETE FROM cf_fieldvalue WHERE nFormId =".$_REQUEST["cfid"];
			$nResult = mysql_query($query, $nConnection);   
			
            $query = "DELETE FROM cf_slottovalue WHERE nFormId =".$_REQUEST["cfid"];
			$nResult = mysql_query($query, $nConnection);   
            
            //--- delete the attachments
            $query = "SELECT cf_attachment.* FROM cf_attachment, cf_circulationhistory WHERE cf_attachment.nCirculationHistoryId=cf_circulationhistory.nID AND cf_circulationhistory.nCirculationFormId=".$_REQUEST["cfid"];
            $nResult = mysql_query($query, $nConnection);
            
            $arrAttachmentsToDelete = array();
            if ($nResult)
			{
                $arrValueIds = array();
				if (mysql_num_rows($nResult) > 0)
				{
					while (	$arrRow = mysql_fetch_array($nResult))
					{	
						$arrAttachmentsToDelete[] = $arrRow["nID"];
                        delete_file($arrRow["strPath"]);					        
					}				
				}
            }
            
            foreach ($arrAttachmentsToDelete as $nAttachmentId)
            {
            	$query = "DELETE FROM cf_attachment WHERE nID =".$nAttachmentId;
				$nResult = mysql_query($query, $nConnection);   
            }
            
            //--- deleting the history
            $query = "DELETE FROM cf_circulationhistory WHERE nCirculationFormId=".$_REQUEST["cfid"];
            mysql_query($query, $nConnection);
		}
	}
?>

<html>
	<head>
		<script language="Javascript">
		<!--
			function loadNext()
			{
				document.location.href="showcirculation.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"];?>&archivemode=<?php echo $_REQUEST["archivemode"];?>&sortby=<?php echo $_REQUEST["sortby"];?>";
			}
		//-->
		</script>
	</head>
	<body onload="window.setTimeout('loadNext()',1000);">
	</body>
</html>
