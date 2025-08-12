<?php
	Require("includes.php");
	Require("../Includes/i_Includes.php");
	Require("SQL/index.php");
	
	
	OpenPage();
	Main();
	ClosePage();
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sOS;
		Global $sUseDB;
		Global $sDatabaseServer;
		Global $sDatabaseName;
		Global $sDatabaseLogin;
		Global $sDatabasePassword;
		Global $sTemplates;
		
		if ( Trim(Request("sAction")) == "step1" )
		{
			$sOS				= Trim(Request("sOS"));
			$sUseDB				= Trim(Request("sUseDB"));
			$sDatabaseServer	= Trim(Request("sDatabaseServer"));
			$sDatabaseName		= Trim(Request("sDatabaseName"));
			$sDatabaseLogin		= Trim(Request("sDatabaseLogin"));
			$sDatabasePassword	= Trim(Request("sDatabasePassword"));
			$sTemplates			= Trim(Request("sTemplates"));
			
			error_reporting(0);
			If ( $sUseDB == "MSSQL" ){
				if ( $DBConnection = mssql_connect ($sDatabaseServer, $sDatabaseLogin, $sDatabasePassword) )
				{
					if ( mssql_select_db($sDatabaseName, $DBConnection) )
					{
						
					}else{
						echo "Could not connect to MSSQL database server: ".mssql_get_last_message()."<br>";
					}
				}Else{
					echo "Could not connect to MSSQL database server: ".mssql_get_last_message()."<br>";
				}
			}ElseIf ( $sUseDB == "MYSQL" ){
				if ( $DBConnection = mysql_connect ($sDatabaseServer, $sDatabaseLogin, $sDatabasePassword) )
				{
					if ( mysql_select_db($sDatabaseName, $DBConnection) )
					{
						
					}else{
						echo "Could not connect to MySQL database server: ".mysql_error()."<br>";
					}
				}Else{
					echo "Could not connect to MySQL database server: ".mysql_error()."<br>";
				}
			}
			if ( copy ("test.txt", "../Configurations/test.txt") )
			{
				$sTempString = "<?php\n";
				$sTempString .= "	// Change this to the template you would like to use on the non-admin pages\n";
				$sTempString .= "	//		Aqua\n";
				$sTempString .= "	//		Aqua_Layout2\n";
				$sTempString .= "	//		Flat_Aqua\n";
				$sTempString .= "	//		Flat_Aqua_Layout2\n";
				$sTempString .= "	//		Black\n";
				$sTempString .= "	//		Black_Layout2\n";
				$sTempString .= "	//		Flat_Black\n";
				$sTempString .= "	//		Flat_Black_Layout2\n";
				$sTempString .= "	//		White\n";
				$sTempString .= "	//		White_Layout2\n";
				$sTempString .= "	//		Flat_White\n";
				$sTempString .= "	//		Flat_White_Layout2\n";
				$sTempString .= "	" . "$" . "sTemplates = \"" . $sTemplates . "\";\n";
				$sTempString .= "\n";
				$sTempString .= "	" . "$" . "sUseDB = \"" . $sUseDB . "\";\n";
				$sTempString .= "	" . "$" . "sDatabaseName = \"" . $sDatabaseName . "\";\n";
				$sTempString .= "	" . "$" . "sOS = \"" . $sOS . "\";\n";
				$sTempString .= "	" . "$" . "iColorScheme = 5;\n";
				$sTempString .= "	\n";
				$sTempString .= "	" . "$" . "sDatabaseServer = \"" . $sDatabaseServer . "\";\n";
				$sTempString .= "	" . "$" . "sDatabaseLogin = \"" . $sDatabaseLogin . "\";\n";
				$sTempString .= "	" . "$" . "sDatabasePassword = \"" . $sDatabasePassword . "\";\n";
				$sTempString .= "	\n";
				$sTempString .= "	Function DB_OpenDomains()\n";
				$sTempString .= "	{\n";
				$sTempString .= "	\n";
				$sTempString .= "		Global " . "$" . "PHPJKConnection;\n";
				$sTempString .= "		Global " . "$" . "sDatabaseServer;\n";
				$sTempString .= "		Global " . "$" . "sDatabaseLogin;\n";
				$sTempString .= "		Global " . "$" . "sDatabasePassword;\n";
				$sTempString .= "		\n";
				$sTempString .= "		" . "$" . "PHPJKConnection = DB_DBConnect (" . "$" . "sDatabaseServer, " . "$" . "sDatabaseLogin, " . "$" . "sDatabasePassword);\n";
				$sTempString .= "	\n";
				$sTempString .= "		Return " . "$" . "PHPJKConnection;\n";
				$sTempString .= "	}\n";
				$sTempString .= "?>\n";
				
				If (fwrite(fopen("../Configurations/PHPJK_Config.php", "w"), $sTempString) === FALSE)
				{
					echo "Cannot write to the PHPJK_Config.php file. Installation aborted.<br>";
				}else{				
					header( 'location:step2.php' );
				}
			}else{
				echo "Could not write to the Configurations folder. Please set permissions.<br>";
			}
		}
		
		if ( $sOS = "" )
		{
			if ( $_ENV['OS'] = "Windows_NT" )
			{
				$sOS = "WIN";
			}else{
				$sOS = "UNIX";
			}
		}
		
		?>
		<script language=JavaScript src="overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
		<div id=overDiv style="Z-INDEX: 1000; VISIBILITY: hidden; POSITION: absolute"></div>
		<form action="step1.php" method="post">
		<input type='hidden' name='sAction' value='step1'>
		<table>
			<tr>
				<td>
					Is PHP JackKnife being installed on Windows or *nix (Linux, Unix, etc.)?
				</td>
				<td>
					<select name='sOS'>
						<option value='WIN' <?php if ( $sOS == 'WIN' ) {echo "selected";}?>>Windows</option>
						<option value='UNIX' <?php if ( $sOS == 'UNIX' ) {echo "selected";}?>>*nix</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					MySQL or MSSQL?
				</td>
				<td>
					<select name='sUseDB'>
						<option value='MSSQL' <?php if ( $sUseDB == 'MSSQL' ) {echo "selected";}?>>MSSQL</option>
						<option value='MYSQL' <?php if ( $sUseDB == 'MYSQL' ) {echo "selected";}?>>MySQL</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Database server name (for example: "localhost")?
				</td>
				<td>
					<input type='text' name='sDatabaseServer' value="<?=$sDatabaseServer?>">
				</td>
			</tr>
			<tr>
				<td>
					Database name?
				</td>
				<td>
					<input type='text' name='sDatabaseName' value="<?=$sDatabaseName?>">
				</td>
			</tr>
			<tr>
				<td>
					Database login?
				</td>
				<td>
					<input type='text' name='sDatabaseLogin' value="<?=$sDatabaseLogin?>">
				</td>
			</tr>
			<tr>
				<td>
					Database password?
				</td>
				<td>
					<input type='text' name='sDatabasePassword' value="<?=$sDatabasePassword?>">
				</td>
			</tr>
			<tr>
				<td colspan=2>
					Which template would you like to start with (you can change it at any time by editing the /Configurations/PHPJK_Config.php file)?
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<table width=100%>
						<tr>
							<td align=center><img src='Images/Aqua_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Aqua.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/Black_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Black.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/White_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/White.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
						</tr>
						<tr>
							<td align=center>Aqua: <input type='radio' name='sTemplates' value='Aqua' <?php if ( $sTemplates == 'Aqua' ) {echo "checked";}?>></td>
							<td align=center>Black: <input type='radio' name='sTemplates' value='Black' <?php if ( $sTemplates == 'Black' ) {echo "checked";}?>></td>
							<td align=center>White: <input type='radio' name='sTemplates' value='White' <?php if ( $sTemplates == 'White' ) {echo "checked";}?>></td>
						</tr>
						
						<tr>
							<td align=center><img src='Images/Aqua_Layout2_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Aqua_Layout2.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/Black_Layout2_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Black_Layout2.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/White_Layout2_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/White_Layout2.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
						</tr>
						<tr>
							<td align=center>Aqua Layout 2: <input type='radio' name='sTemplates' value='Aqua_Layout2' <?php if ( $sTemplates == 'Aqua_Layout2' ) {echo "checked";}?>></td>
							<td align=center>Black Layout 2: <input type='radio' name='sTemplates' value='Black_Layout2' <?php if ( $sTemplates == 'Black_Layout2' ) {echo "checked";}?>></td>
							<td align=center>White Layout 2: <input type='radio' name='sTemplates' value='White_Layout2' <?php if ( $sTemplates == 'White_Layout2' ) {echo "checked";}?>></td>
						</tr>

						<tr>
							<td align=center><img src='Images/Flat_Aqua_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Flat_Aqua.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/Flat_Black_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Flat_Black.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/Flat_White_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Flat_White.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
						</tr>
						<tr>
							<td align=center>Flat Aqua: <input type='radio' name='sTemplates' value='Flat_Aqua' <?php if ( $sTemplates == 'Flat_Aqua' ) {echo "checked";}?>></td>
							<td align=center>Flat Black: <input type='radio' name='sTemplates' value='Flat_Black' <?php if ( $sTemplates == 'Flat_Black' ) {echo "checked";}?>></td>
							<td align=center>Flat White: <input type='radio' name='sTemplates' value='Flat_White' <?php if ( $sTemplates == 'Flat_White' ) {echo "checked";}?>></td>
						</tr>
						
						<tr>
							<td align=center><img src='Images/Flat_Aqua_Layout2_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Flat_Aqua_Layout2.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/Flat_Black_Layout2_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Flat_Black_Layout2.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
							<td align=center><img src='Images/Flat_White_layout2_sm.jpg' border=1 onMouseOver='return overlib("<img src=\"Images/Flat_White_layout2.jpg\">", CAPTION, "", CENTER);' onMouseOut=nd();></td>
						</tr>
						<tr>
							<td align=center>Flat Aqua Layout 2: <input type='radio' name='sTemplates' value='Flat_Aqua_Layout2' <?php if ( $sTemplates == 'Flat_Aqua_Layout2' ) {echo "checked";}?>></td>
							<td align=center>Flat Black Layout 2: <input type='radio' name='sTemplates' value='Flat_Black_Layout2' <?php if ( $sTemplates == 'Flat_Black_Layout2' ) {echo "checked";}?>></td>
							<td align=center>Flat White Layout 2: <input type='radio' name='sTemplates' value='Flat_White_Layout2' <?php if ( $sTemplates == 'Flat_White_Layout2' ) {echo "checked";}?>></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
		By clicking "Continue", the system will test your database connection, the folder permissions, and save your settings to the
		configuration file.<br>
		<input type='submit' value=' Continue '>
		</form>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		<?php
	}
	//************************************************************************************
?>