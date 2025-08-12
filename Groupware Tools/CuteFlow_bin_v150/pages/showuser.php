<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
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
	
	include ("../language_files/".$_REQUEST["language"]."/gui.inc.php");
?>

<head>
	<title></title>	
	<link rel="stylesheet" href="format.css" type="text/css">
	<style>
		.table_header
		{
			background-color: Red; 
			color: White; 
			font-size: 8pt; 
			font-weight: bold;"			
		}
	</style>
	
	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="tooltip.js"></SCRIPT>
	
	<script language="JavaScript">
	<!--
		function deleteUser(nUserId)
		{
			Check = confirm("<?php echo $USER_MNGT_ASKDELETE;?>");
			if(Check == true) 
			{
				location.href="deleteuser.php?userid="+nUserId+"&language=<?php echo $_REQUEST["language"]?>&sortby=<?php echo $_REQUEST["sortby"]?>&start=<?php echo $_REQUEST["start"]?>";
			}
		}
	//-->
	</script>
</head>
<body>
<?php
	include ("../config/config.inc.php");
    include ("../lib/datetime.inc.php");
    include ("../lib/viewutils.inc.php");
		
	include ("../pages/check_substitute.inc.php");
	
	//--- open database
	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	
	if ($nConnection)
	{
		//--- get maximum count of users
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			$query = "select COUNT(*) from cf_user";
			$nResult = mysql_query($query, $nConnection);

			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{
					while (	$arrRow = mysql_fetch_array($nResult))
					{	
						$nUserCount = $arrRow[0];
					}				
				}
			}
		}
		
		if ($nUserCount > $_REQUEST["start"] + 50)
		{
			$end = $_REQUEST["start"] + 49;
		}
		else
		{
			if ($_REQUEST["start"]+50 > $nUserCount)
			{
				$end = $nUserCount;
			}
			else
			{
				$end = $_REQUEST["start"] + 50;
			}
		}
		
		//--- get all users
		$arrUsers = array();
		$sortCol = "strLastName";
		$sortAs = "ASC";
		$strQuery = "SELECT * FROM cf_user ORDER BY $sortCol $sortAs";
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
	}

?>
	<br>
	<div align="center" width="100%">
		<table width="80%">
			<tr>
				<td align="left" width="14px">
					<a href="edituser.php?language=<?php echo $_REQUEST["language"];?>&userid=-1&sortby=<?php echo $_REQUEST["sortby"]?>&start=<?php echo $_REQUEST["start"]?>"><img src="../images/adduser.png" border="0"></a>
				</td>
				<td align="left">
					[ <a href="edituser.php?language=<?php echo $_REQUEST["language"];?>&userid=-1&sortby=<?php echo $_REQUEST["sortby"]?>&start=<?php echo $_REQUEST["start"]?>"><?php echo $USER_MNGT_ADDUSER;?></a> ]
				</td>
			</tr>
		</table>	
		<table width="80%">
			<tr>
				<td>
					<?php 
						$USER_MNGT_SHOWRANGE = str_replace("_%From", $_REQUEST["start"], $USER_MNGT_SHOWRANGE);
						$USER_MNGT_SHOWRANGE = str_replace("_%To", $end, $USER_MNGT_SHOWRANGE);
						echo $USER_MNGT_SHOWRANGE;?><br>		
				</td>
				<td>
					<form action="showuser.php">
						<table>
							<td>
								<?php echo $USER_MNGT_SORTBY;?>
							</td>
							<td>
								<select id="sortby" name="sortby" class="FormInput">
									<option value="name" <?php if ($sortby == "name") echo "selected"?>><?php echo $USER_MNGT_SORTBY_NAME;?></option>
								</select>
							</td>
							<td>
								<input type="submit" value="<?php echo $BTN_OK;?>" class="Button">
							</td>							
						</table>
						<input type="hidden" name="language" id="language" value="<?php echo $_REQUEST["language"];?>">
						<input type="hidden" name="start" id="start" value="<?php echo $_REQUEST["start"];?>">
					</form>
				</td>
				<td align="right">
					<?php
						if ($_REQUEST["start"] > 50)
						{
							?>
								<a href="showuser.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"]-50;?>&sortby=<?php echo $_REQUEST["sortby"];?>"><img src="../images/prev.png" height="10" width="10" alt="prev" border="0"> <?php echo $BTN_BACK;?></a>
							<?php
						}
						
						if ($end < $nUserCount)
						{
							?>
								<a href="showuser.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"]+50;?>&sortby=<?php echo $_REQUEST["sortby"];?>"><?php echo $BTN_NEXT;?> <img src="../images/next.png" height="10" width="10" alt="next"  border="0"></a>
							<?php
						}
						
					?>
				</td>
			</tr>
		</table>
		<table width="80%" style="border: 1px solid Red;">
			<tr>
				<td class="table_header">#</td>
				<td class="table_header"><?php echo $USER_MNGT_LASTNAME;?></td>
				<td class="table_header"><?php echo $USER_MNGT_FIRSTNAME;?></td>
				<td class="table_header"><?php echo $USER_MNGT_EMAIL;?></td>
				<td class="table_header"><?php echo $USER_MNGT_ADMINACCESS;?></td>
				<td class="table_header"><?php echo $USER_MNGT_SUBSTITUDE;?></td>
				<td class="table_header"></td>
			</tr>
			<?php
				//--- output the user inbetween the range (start to end)
				$nRunningNumber = 1;
				foreach ($arrUsers as $arrRow)
				{	
					$style = "background-color: #FFFAFA;";
					if ($nRunningNumber%2 == 1)
					{
						$style = "background-color: #EFEFEF;";
					}
					
					echo "<tr valign=\"top\" style=\"$style\">";
					echo "<td nowrap>".$nRunningNumber."</td>";
					
					echo "<td nowrap>".$arrRow["strLastName"]."</td>";
					echo "<td nowrap>".$arrRow["strFirstName"]."</td>";
					echo "<td nowrap>".$arrRow["strEMail"]."</td>";
					
					if (($arrRow["nAccessLevel"] & 2) == 2)
					{
						echo "<td align=\"center\"><img src=\"../images/active.png\" height=\"16\" width=\"16\"></td>";
					}
					else
					{
						echo "<td align=\"center\"><img src=\"../images/inactive.png\" height=\"16\" width=\"16\"></td>";
					}
					
					if ($arrRow["nSubstitudeId"] != 0)
					{
						$substitude = $arrUsers[$arrRow["nSubstitudeId"]];
						echo "<td nowrap>".$substitude["strLastName"].", ".$substitude["strFirstName"]."</td>"; 
					}
					else
					{
						echo "<td nowrap>-</td>";
					}
								
					echo "<td>";							
					echo "<a href=\"javascript:deleteUser($arrRow[0])\" alt=\"Löschen\" onMouseOver=\"tip('delete')\" onMouseOut=\"untip()\"><img src=\"../images/remove.png\" border=\"0\" height=\"16\" width=\"16\"></a>";
					echo "<a href=\"edituser.php?userid=$arrRow[0]&language=".$_REQUEST["language"]."&sortby=".$_REQUEST["sortby"]."&start=".$_REQUEST["start"]."\" onMouseOver=\"tip('detail')\" onMouseOut=\"untip()\" alt=\"Editieren\"><img src=\"../images/edit.png\" border=\"0\" height=\"16\" width=\"16\"></a>";
					echo "</td></tr>";
												
					$nRunningNumber++;
				}
			?>
		</table>
		<table width="80%">
			<tr>
				<td>
					<?php echo $USER_MNGT_SHOWRANGE;?><br>		
				</td>
				<td align="right">
					<?php
						if ($_REQUEST["start"] > 50)
						{
							?>
								<a href="showuser.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"]-50;?>&sortby=<?php echo $_REQUEST["sortby"];?>"><img src="images/prev.png" height="10" width="10" alt="prev" border="0"> <?php echo $BTN_BACK;?></a>
							<?php
						}
						
						if ($end < $nUserCount)
						{
							?>
								<a href="showuser.php?language=<?php echo $_REQUEST["language"];?>&start=<?php echo $_REQUEST["start"]+50;?>&sortby=<?php echo $_REQUEST["sortby"];?>"><?php echo $BTN_NEXT;?> <img src="images/next.png" height="10" width="10" alt="next"  border="0"></a>
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
	<script type="text/javascript">
    	maketip('delete','<?php echo $USER_TIP_DELETE;?>');
    	maketip('detail','<?php echo $USER_TIP_DETAIL;?>');
	</script>
</body>
</html>
<?php
	//--- close database	
	mysql_close($nConnection);
?>