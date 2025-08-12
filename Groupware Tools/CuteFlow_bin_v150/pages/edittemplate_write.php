<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
	print_r($_REQUEST);
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
	include ("../config/config.inc.php");
?>
<head>
	<title></title>	
	<link rel="stylesheet" href="format.css" type="text/css">
	
	<script language="JavaScript">
	<!--
		function siteLoaded()
		{
			document.location.href="showtemplates.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"];?>&sortby=<?php echo $_REQUEST["sortby"];?>";
		}
	//-->
	</script>
</head>
<html>
<body onLoad="siteLoaded()">
	<?php
		//--- open database
	   	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	   	
	   	if ($nConnection)
	   	{
	   		//--- get maximum count of users
	   		if (mysql_select_db($DATABASE_DB, $nConnection))
	   		{
				//-----------------------------------------------
	    		//--- get all slots for the given template
	            //-----------------------------------------------
				$arrSlots = array();
				$arrSlotRelations = array();
				
	            $strQuery = "SELECT * FROM cf_formslot WHERE nTemplateID=".$_REQUEST["templateid"]."  ORDER BY nSlotNumber ASC";
	    		$nResult = mysql_query($strQuery, $nConnection);
	    		if ($nResult)
	    		{
	    			if (mysql_num_rows($nResult) > 0)
	    			{
	    				while (	$arrRow = mysql_fetch_array($nResult))
	    				{
	    					$arrSlots[] = $arrRow;
							$arrSlotRelations[] = array();
	    				}
	    			}
	    		}	
						
				//-----------------------------------------------
				//--- create the array with all slot to user 
				//--- relations
				//-----------------------------------------------
				while(list($key, $value) = each($HTTP_GET_VARS))
				{
					$arrKeyValue = explode ("_", $value);
					
					if (sizeof($arrKeyValue) == 3)
					{
						//--- we have there a slot to field relation
						//                SlotId           Position           FieldId
						$arrSlotRelations[$arrKeyValue[0]][$arrKeyValue[2]] = $arrKeyValue[1];
					}
				}
				
				//-----------------------------------------------			
				//--- write to database
				//-----------------------------------------------
				//--- cf_slottofield
				foreach ($arrSlots as $arrSlot)
				{
					//--- first delete all entries for this slot
					$strQuery = "DELETE FROM cf_slottofield WHERE nSlotId=".$arrSlot["nID"];
					$nResult = mysql_query($strQuery, $nConnection);					
					
					//--- After that insert all slot to user relations for this slot
					if ($arrSlotRelations[$arrSlot['nID']])
					{				
						foreach ($arrSlotRelations[$arrSlot['nID']] as $nPos=>$nFieldId)
						{
							$strQuery = "INSERT INTO cf_slottofield values (null, ".$arrSlot["nID"].", $nFieldId, '', 0, 0)";
							$nResult = mysql_query($strQuery, $nConnection);
						}
					}
				}
			}
		}
	?> 
</body>
</html>
