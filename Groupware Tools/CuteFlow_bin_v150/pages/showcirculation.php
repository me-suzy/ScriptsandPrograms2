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
		
	include ("../pages/check_substitute.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="stylesheet" href="format.css" type="text/css">
	<style>
		.table_header
		{
			background-color: Red; 
			color: White; 
			font-size: 8pt; 
			font-weight: bold;			
		}
		
		tr.rowEven
		{
			background-color: #FFFAFA;
		}
		
		tr.rowUneven
		{
			background-color: #EFEFEF;
		}
		
		td.highlight_bright
		{
			background-color: #FFF7DE;
		}
		
		td.highlight_dark
		{
			background-color: #F4E8C2;
		}
		
	</style>
	
	<script language="JavaScript">
	<!--
		function deleteCirculation(nCirculationId)
		{
			Check = confirm("<?php echo $CIRCULATION_MNGT_ASKDELETE;?>");
			if(Check == true) 
			{
				location.href="deletecirculation.php?cfid="+nCirculationId+"&language=<?php echo $_REQUEST["language"]?>&archivemode=<?php echo $_REQUEST["archivemode"];?>&sortDirection=<?php echo $_REQUEST["sortDirection"];?>&sortby=<?php echo $_REQUEST["sortby"]?>&start=<?php echo $_REQUEST["start"]?>";
			}
		}
	//-->
	</script>
	
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="tooltip.js"></SCRIPT>	
<script LANGUAGE="JavaScript" type="text/javascript">
  	maketip('delete','<?php echo $CIRCULATION_TIP_DELETE;?>');
  	maketip('detail','<?php echo $CIRCULATION_TIP_DETAIL;?>');
	maketip('archive','<?php echo $CIRCULATION_TIP_ARCHIVE;?>');
	maketip('unarchive','<?php echo $CIRCULATION_TIP_UNARCHIVE;?>');
	maketip('stop','<?php echo $CIRCULATION_TIP_STOP;?>');
	maketip('restart','<?php echo $CIRCULATION_TIP_RESTART;?>');
</script>
</head>

<body>

<?php
	if ($_REQUEST["sortby"] == "")
	{
		$_REQUEST["sortby"] = $DEFAULT_SORT_COL;	
	}
	
	if ($_REQUEST["sortDirection"] == "")
	{
		$_REQUEST["sortDirection"] = "ASC";	
	}
	
    function getMaxProcessId($nHistoryId, $Connection)
    {
        $query = "SELECT MAX(nID) FROM `cf_circulationprocess` WHERE `nCirculationHistoryId`=".$nHistoryId;
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
    
    function getMaxHistoryData($nFormId, $Connection)
    {
    	$arrResult = array();
    	
        $query = "SELECT MAX(nID) FROM `cf_circulationhistory` WHERE `nCirculationFormId`=".$nFormId;
        $nResult = mysql_query($query, $Connection);

        if ($nResult)
        {
            if (mysql_num_rows($nResult) > 0)
            {
                $arrRow = mysql_fetch_array($nResult);
                
                if ($arrRow)
                {
                    $arrResult[0] = $arrRow[0];
                }           
            }   
        }
        
        $query = "SELECT dateSending FROM cf_circulationhistory WHERE nID=".$arrResult[0];
        $nResult = mysql_query($query, $Connection);

        if ($nResult)
        {
            if (mysql_num_rows($nResult) > 0)
            {
                $arrRow = mysql_fetch_array($nResult);
                
                if ($arrRow)
                {
                    $arrResult[1] = $arrRow["dateSending"];
                }           
            }   
        }
        
        return $arrResult;
    }

    function getProcessInformation($nMaxId, $Connection)
    {
        $query = "SELECT * FROM `cf_circulationprocess` WHERE `nID`=".$nMaxId;
        $nResult = mysql_query($query, $Connection);

        if ($nResult)
        {
            if (mysql_num_rows($nResult) > 0)
            {
                $arrRow = mysql_fetch_array($nResult);
                
                if ($arrRow)
                {
                    return $arrRow;
                }           
            }   
        }        
    }
    
    function cmpCirculations($arr1, $arr2)
    {
    	global $_REQUEST;
    	
    	switch ($_REQUEST["sortby"])
    	{
    		case "COL_CIRCULATION_STATION":	
    					return strcmp($arr1["COL_CIRCULATION_STATION"], $arr2["COL_CIRCULATION_STATION"]);
    					break;
    		case "COL_CIRCULATION_PROCESS_DAYS": 
    					if ($arr1["COL_CIRCULATION_PROCESS_DAYS"] == $arr2["COL_CIRCULATION_PROCESS_DAYS"])
    					{
    						return 0;	
    					}
    					else if ($arr1["COL_CIRCULATION_PROCESS_DAYS"] > $arr2["COL_CIRCULATION_PROCESS_DAYS"])
    					{
    						return 1;
    					}
    					else 
    					{
    						return -1;
    					}
    					break;
    		case "COL_CIRCULATION_SENDER": 
    					return strcmp($arr1["COL_CIRCULATION_SENDER"], $arr2["COL_CIRCULATION_SENDER"]);
    					break;
    		case "COL_CIRCULATION_PROCESS_START":
    					$strDate1 = str_replace("-", "", $arr1["COL_CIRCULATION_PROCESS_START"]);
    					$strDate2 = str_replace("-", "", $arr2["COL_CIRCULATION_PROCESS_START"]);
    					if ($strDate1 == $strDate2)
    					{
    						return 0;	
    					}
    					else if ($strDate1 > $strDate2)
    					{
    						return 1;
    					}
    					else 
    					{
    						return -1;	
    					}
    					
    					break;
    	}
    }
    
    //--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	$nConnection2 = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	
	if ( ($nConnection) && ($nConnection2) )
	{
		//--- get maximum count of users
		if ( (mysql_select_db($DATABASE_DB, $nConnection))  && 
				(mysql_select_db($DATABASE_DB, $nConnection)) )
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
					$nRunningNumber = $start;
					while (	$arrRow = mysql_fetch_array($nResult))
					{
						$arrUsers[$arrRow["nID"]] = $arrRow;
					}
				}
			}
			
			//-----------------------------------------------
			//--- getting the circulation information
			//-----------------------------------------------
			if ($_REQUEST["filter"] == "")
            {
                $strFilter = "/.*/i";
            }
            else
            {
            	$strFilter = str_replace("*", ".*", $_REQUEST["filter"]);
            	$strFilter = str_replace("?", ".{1}", $strFilter);
                $strFilter = "/".$strFilter."/i";
            }
			
			$query = "select COUNT(*) from cf_circulationform WHERE ";
			if ($_REQUEST["archivemode"] == 1)
			{
				$query .= "bIsArchived = 1";
			}
			else
			{
				$query .= "bIsArchived = 0";
			}

			$nResult = mysql_query($query, $nConnection);

			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					while (	$arrRow = mysql_fetch_array($nResult))
					{	
						$nCirculationCount = $arrRow[0];
					}				
				}
			}
		
			if ($nCirculationCount > $_REQUEST["start"] + 50)
			{
				$end = $_REQUEST["start"] + 49;
			}
			else
			{
				if ($_REQUEST["start"]+50 > $nCirculationCount)
				{
					$end = $nCirculationCount;
				}
				else
				{
					$end = $_REQUEST["start"] + 50;
				}
			}
        
        	$arrCirculations = array();
				
			//--- output the circulation inbetween the range (start to end)
			$strQuery = "SELECT * FROM cf_circulationform WHERE ";
		
			if ($_REQUEST["archivemode"] == 1)
			{
				$strQuery .= "bIsArchived = 1";
			}
			else
			{
				$strQuery .= "bIsArchived = 0";
			}
			
			$sortCol = "strName";
			$sortDirection = $_REQUEST["sortDirection"];
		
			$strQuery .= " ORDER BY $sortCol $sortDirection";
		
			/*if ($_REQUEST["filter"] == "")
			{
				//--- if no filter is set, we only read a fix amount of data	
				$strQuery .= " LIMIT ".($_REQUEST["start"]-1).", ".$end."";
			}*/
					
			$nResult = mysql_query($strQuery, $nConnection);
			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					$nRunningNumber = $start;
					while (	$arrRow = mysql_fetch_array($nResult))
					{
						$bStopped = false;
		                if (@preg_match($strFilter, $arrRow["strName"]))
		                {
		                	$arrCurCirculation = array();
		                	
		                	$arrCurCirculation["COL_CIRCULATION_ID"] = $arrRow["nID"];		                	
		                	$arrCurCirculation["COL_CIRCULATION_NAME"] = $arrRow["strName"];
		                	$arrCurCirculation["COL_CIRCULATION_SENDER"] = $arrUsers[$arrRow["nSenderId"]]["strUserId"];
		                	
		                	//--- get the current station
		                	$arrHistoryData = getMaxHistoryData($arrRow["nID"], $nConnection2);
		                	
		                    $nMaxId = getMaxProcessId($arrHistoryData[0], $nConnection2);
		                    $arrProcessInformation = getProcessInformation($nMaxId, $nConnection2);
		                    $arrCurCirculation["COL_CIRCULATION_STATION"] = $arrUsers[$arrProcessInformation["nUserId"]]["strUserId"];
		
							$arrCurCirculation["COL_CIRCULATION_DECISION_STATE"] = $arrProcessInformation["nDecissionState"];
		                	
		                	//--- and detect how long the the circulation is already in that station
							$dateReceived = convertDateFromDB($arrProcessInformation["dateInProcessSince"]);
							if ($arrProcessInformation["nDecissionState"] == 0)
							{
					            $arrCurCirculation["COL_CIRCULATION_PROCESS_DAYS"] = floor(dateDiff($dateReceived, date("d.m.Y")));
							}
							else
							{
								$arrCurCirculation["COL_CIRCULATION_PROCESS_DAYS"] = "-";
							}
							
							$arrCurCirculation["COL_CIRCULATION_PROCESS_START"] = $arrHistoryData[1];
							
							$arrCirculations[] = $arrCurCirculation;
		                }
					}
				}
			}
			
			//--- sorting the array
			if ($_REQUEST["sortby"] != "COL_CIRCULATION_NAME")
			{
				usort($arrCirculations, cmpCirculations);
				
				if ($_REQUEST["sortDirection"] != "ASC")
				{
					$arrCirculations = array_reverse($arrCirculations);	
				}
			}
			
		}       	
    }
    
    
    function getColHighlight($nRow, $strSortBy, $strCol)
    {
    	if ($strCol == $strSortBy)
    	{
    		if ($nRow % 2 != 0)
    		{
    			return " class=\"highlight_dark\" ";	
    		}
    		else 
    		{
    			return " class=\"highlight_bright\" ";	
    		}
    	}	
    	
    	return "";
    }
    
    function getSortDirection($strColumn)
    {
    	global $_REQUEST;
    	
    	if ($strColumn == $_REQUEST["sortby"])
    	{
    		if ($_REQUEST["sortDirection"] == "ASC")
    		{
    			return "DESC";	
    		}
    		else 
    		{
    			return "ASC";
    		}
    	}
    	else 
    	{
    		return "ASC";
    	}
    }
?>
<br>
	<div align="center" width="100%">
		<?php
		if ($_REQUEST["archivemode"] == 0)
		{
		?>
    	<table width="80%">
    		<tr>
				<?php 
				if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
				{
				?>
					<td align="left" width="14px">
	    				<a href="editcirculation.php?language=<?php echo $_REQUEST["language"];?>&circid=-1&archivemode=<?php echo $_REQUEST["archivemode"];?>&sortDirection=<?php echo $_REQUEST["sortDirection"];?>&sortby=<?php echo $_REQUEST["sortby"]?>&start=<?php echo $_REQUEST["start"]?>&filter=<?php echo $_REQUEST["filter"];?>"><img src="../images/addcirculation.png" border="0"></a>
	    			</td>
	    			<td align="left">
	    				[ <a href="editcirculation.php?language=<?php echo $_REQUEST["language"];?>&circid=-1&archivemode=<?php echo $_REQUEST["archivemode"];?>&sortDirection=<?php echo $_REQUEST["sortDirection"];?>&sortby=<?php echo $_REQUEST["sortby"]?>&start=<?php echo $_REQUEST["start"]?>&filter=<?php echo $_REQUEST["filter"];?>"><?php echo $CIRCULATION_MNGT_ADDCIRCULATION;?></a> ]
	    			</td>
				<?php
				}
				else
				{
					?>
					<td align="left" width="14px">
	    				<img src="../images/addcirculation.png" border="0">
	    			</td>
	    			<td align="left" style="color: Gray;">
	    				[ <?php echo $CIRCULATION_MNGT_ADDCIRCULATION;?> ]
	    			</td>
					<?php
				}
				?>
			
    			
    		</tr>
    	</table>
		<?php
		}
		?>
        <br>
        <table width="80%">
            <tr>
                <td>
                    <form action="showcirculation.php">
                        <table>
                            <tr>
                                <td align="left"><?php echo $CIRCULATION_MNGT_FILTER;?></td>
                                <td><input class="FormInput" type="text" name="filter" id="filter" value="<?php echo $_REQUEST["filter"];?>"></td>
                                <td><input type="submit" value="<?php echo $BTN_OK;?>" class="Button"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="language" id="language" value="<?php echo $_REQUEST["language"];?>">
                        <input type="hidden" name="sortby" id="sortby" value="<?php echo $_REQUEST["sortby"];?>">
                        <input type="hidden" name="sortDirection" id="sortDirection" value="<?php echo $_REQUEST["sortDirection"];?>">
                        <input type="hidden" name="start" id="start" value="1">
                    </form>
                </td>
            </tr>
        </table>
        <br/>
     	<table width="80%" style="border: 1px solid Red;" >
            <thead>
    			<tr>
    				<td class="table_header">#</td>
    				
    				<?php 
    					foreach ($CIRCULATION_COLUMNS as $strColumn)
    					{
    						echo "<td class=\"table_header\" align=\"left\"><a style=\"color:White\" href=\"showcirculation.php?language=".$_REQUEST["language"]."&start=1&sortby=".$strColumn."&sortDirection=".(getSortDirection($strColumn))."\">";	
    						switch ($strColumn)
    						{
    							case "COL_CIRCULATION_NAME": echo $CIRCULATION_MNGT_NAME; break;	
    							case "COL_CIRCULATION_STATION": echo $CIRCULATION_MNGT_CURRENT_SLOT; break;
    							case "COL_CIRCULATION_PROCESS_DAYS": echo $CIRCULATION_MNGT_WORK_IN_PROCESS; break;
    							case "COL_CIRCULATION_PROCESS_START": echo $CIRCULATION_MNGT_SENDING_DATE; break;
    							case "COL_CIRCULATION_SENDER": echo $CIRCDETAIL_SENDER; break;
    						}
    						echo "</a></td>";
    					}
    				?>
                    <td class="table_header">&nbsp;</td>
                </tr>
            </thead>
            <tbody id="tblBdy">
            <?php
                //--- output the circulations inbetween the range (start to end)
				$nRunningNumber = 1;

				for ($nIndex = $_REQUEST["start"]-1; $nIndex <= $end-1; $nIndex++)
				{
					$arrRow = $arrCirculations[$nIndex];
					
					$bStopped = false;
                    
					$class = "rowEven";
					if ($nRunningNumber%2 == 1)
					{
						$class = "rowUneven";
					}
                        
        			echo "\n<tr class=\"$class\" valign=\"top\">\n";
        			
        			echo "<td>".($nIndex+1)."</td>";
        			
        			foreach ($CIRCULATION_COLUMNS as $strColumn)
					{
						echo "<td nowrap ".getColHighlight($nIndex, $_REQUEST["sortby"], $strColumn)." align=\"left\">";
						switch ($strColumn)
						{
							case "COL_CIRCULATION_NAME": 
									if ($_REQUEST["filter"] != "")
			        				{
			        					$strStrippedFilter = str_replace(array("*", "?"), "", $_REQUEST["filter"]);
			        					$strName = str_replace($strStrippedFilter, "<strong style=\"color: orange;\">$strStrippedFilter</strong>", $arrRow["COL_CIRCULATION_NAME"]);
			        					echo $strName;
			        				}
			        				else 
			        				{
			        					echo $arrRow["COL_CIRCULATION_NAME"];	
			        				} 
			        				break;	
							case "COL_CIRCULATION_STATION": 
									switch ($arrRow["COL_CIRCULATION_DECISSION_STATE"])
									{
										case 0: echo $arrRow["COL_CIRCULATION_STATION"]; break;
										case 1: echo "<img src=\"../images/circ_done.png\">&nbsp;<em>$CIRCULATION_MNGT_CIRC_DONE</em>"; break;
										case 4: echo "<img src=\"../images/circ_done.png\">&nbsp;<em>$CIRCULATION_MNGT_CIRC_DONE </em>"; break; //new
										case 16: $bStopped = true; echo "<img src=\"../images/circ_stop.png\">&nbsp;<em>$CIRCULATION_MNGT_CIRC_STOP</em>"; break;
										case 2: $bStopped = true; echo "<img src=\"../images/circ_stop.png\">&nbsp;<em>$CIRCULATION_MNGT_CIRC_BREAK</em>"; break;
										case 8: echo $arrRow["COL_CIRCULATION_STATION"]; break;
									}

									break;
							case "COL_CIRCULATION_PROCESS_DAYS": 
									echo "<dd style=\"color:".getDelayColor($arrRow["COL_CIRCULATION_PROCESS_DAYS"]).";\">".$arrRow["COL_CIRCULATION_PROCESS_DAYS"]."</dd>"; 
									break;
							case "COL_CIRCULATION_PROCESS_START": 
									echo $arrRow["COL_CIRCULATION_PROCESS_START"];
									 break;
							case "COL_CIRCULATION_SENDER": 
									echo $arrRow["COL_CIRCULATION_SENDER"]; 
									break;
						}
						echo "</td>";
					}
    				
    				echo "<td align=\"left\">";			
					
					if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
					{
    					echo "<a href=\"javascript:deleteCirculation(".$arrRow["COL_CIRCULATION_ID"].")\" onMouseOver=\"tip('delete')\" onMouseOut=\"untip()\"><img src=\"../images/remove.png\" border=\"0\"height=\"16\" width=\"16\"></a> ";
					}
					
					if ($OPEN_DETAILS_IN_SEPERATE_WINDOW == true)
					{
						$strTarget= "target=\"_blank\"";
					}
					
    				echo "<a $strTarget href=\"circulation_detail.php?circid=".$arrRow["COL_CIRCULATION_ID"]."&language=".$_REQUEST["language"]."&archivemode=".$_REQUEST["archivemode"]."&sortDirection=".$_REQUEST["sortDirection"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\" onMouseOver=\"tip('detail')\" onMouseOut=\"untip()\" ><img src=\"../images/act_view.png\" border=\"0\"height=\"16\" width=\"16\"></a> ";
					
					if ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2)
					{
						if ($_REQUEST["archivemode"] == 0)
						{
							echo "<a href=\"archive_circulation.php?circid=".$arrRow["COL_CIRCULATION_ID"]."&archivebit=1&language=".$_REQUEST["language"]."&archivemode=".$_REQUEST["archivemode"]."&sortDirection=".$_REQUEST["sortDirection"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\" onMouseOver=\"tip('archive')\" onMouseOut=\"untip()\" ><img src=\"../images/import_wiz.gif\" border=\"0\"height=\"16\" width=\"16\"></a> ";
						}
						else
						{
							echo "<a href=\"archive_circulation.php?circid=".$arrRow["COL_CIRCULATION_ID"]."&archivebit=0&language=".$_REQUEST["language"]."&archivemode=".$_REQUEST["archivemode"]."&sortDirection=".$_REQUEST["sortDirection"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\" onMouseOver=\"tip('unarchive')\" onMouseOut=\"untip()\" ><img src=\"../images/export_wiz.gif\" border=\"0\"height=\"16\" width=\"16\"></a> ";
						}
					}
					
					if ( ($arrRow["COL_CIRCULATION_DECISSION_STATE"] != 1) && 
						 ($arrRow["COL_CIRCULATION_DECISSION_STATE"] != 2) &&
						 ($arrRow["COL_CIRCULATION_DECISSION_STATE"] != 4) &&
						 ($arrRow["COL_CIRCULATION_DECISSION_STATE"] != 16) &&
						 ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2))
					{
						echo "<a href=\"stop_circulation.php?circid=".$arrRow["COL_CIRCULATION_ID"]."&language=".$_REQUEST["language"]."&archivemode=".$_REQUEST["archivemode"]."&sortDirection=".$_REQUEST["sortDirection"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\" onMouseOver=\"tip('stop')\" onMouseOut=\"untip()\" ><img src=\"../images/stop.gif\" border=\"0\"height=\"16\" width=\"16\"></a> ";
					}
					
					if ( ($bStopped == true) && ($_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] == 2))
					{
						echo "<a href=\"restart_circulation.php?circid=".$arrRow["COL_CIRCULATION_ID"]."&language=".$_REQUEST["language"]."&archivemode=".$_REQUEST["archivemode"]."&sortDirection=".$_REQUEST["sortDirection"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\" onMouseOver=\"tip('restart')\" onMouseOut=\"untip()\" ><img src=\"../images/restart.gif\" border=\"0\"height=\"16\" width=\"16\"></a> ";
					}
					
    				echo "</td></tr>";
    											
    				$nRunningNumber++;
                }
            ?>
            </tbody>
        </table>
        <table width="80%">
			<tr>
				<td>
					<?php 
						$From = ( ($_REQUEST["start"]-1) == 0) ? 1 : $_REQUEST["start"]-1;
                        $strRange = str_replace("_%From", $From, $CIRCULATION_MNGT_SHOWRANGE);
						$strRange = str_replace("_%To", $_REQUEST["start"]+$nRunningNumber-2, $strRange);
						$strRange = str_replace("_%Off", sizeof($arrCirculations), $strRange);
						
						echo $strRange?><br>		
				</td>
				<td align="right">
					<?php
						if ($_REQUEST["start"] > 50)
						{
							?>
								<a href="showcirculation.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"]-50;?>&archivemode=<?php echo $_REQUEST["archivemode"];?>&sortDirection=<?php echo $_REQUEST["sortDirection"];?>&sortby=<?php echo $_REQUEST["sortby"];?>"><?php echo $BTN_BACK;?></a>
							<?php
						}
						
						if ($end < sizeof($arrCirculations))
						{
							?>
								<a href="showcirculation.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"]+50;?>&archivemode=<?php echo $_REQUEST["archivemode"];?>&sortDirection=<?php echo $_REQUEST["sortDirection"];?>&sortby=<?php echo $_REQUEST["sortby"];?>"><?php echo $BTN_NEXT;?></a>
							<?php
						}
						
					?>
				</td>
			</tr>
		</table>
	</div>
	<br>
	<br>
	<br>
	<br>
	<br>
</body>
</html>
