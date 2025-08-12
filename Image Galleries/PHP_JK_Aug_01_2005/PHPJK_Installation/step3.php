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
		Global $sIG;
		Global $sIMAGEGALLERY_CAT_IMAGE_LOC;
		Global $sEmailType;
		Global $sThumbnailType;
		Global $sEMAIL_REMOTEHOST;
		Global $sTechEmail;
		Global $sTechName;
		Global $sAdminLogin;
		Global $sAdminPW;
		Global $sPHP_JK_WEBROOT;
		Global $bNewMembers;
		Global $sIMAGEGALLERY_SITEURL;
		
		Global $sOS;
		Global $sUseDB;
		Global $sDatabaseServer;
		Global $sDatabaseName;
		Global $sDatabaseLogin;
		Global $sDatabasePassword;
		Global $sTemplates;
		
		Global $PHPJKConnection;
		
		if ( Trim(Request("sAction")) == "step3" )
		{
			$sIG 							= Trim(Request("sIG"));
			$sIMAGEGALLERY_CAT_IMAGE_LOC 	= Trim(Request("sIMAGEGALLERY_CAT_IMAGE_LOC"));
			$sEmailType 					= Trim(Request("sEmailType"));
			$sThumbnailType 				= Trim(Request("sThumbnailType"));
			$sEMAIL_REMOTEHOST 				= Trim(Request("sEMAIL_REMOTEHOST"));
			$sTechEmail 					= Trim(Request("sTechEmail"));
			$sTechName 						= Trim(Request("sTechName"));
			$sAdminLogin 					= Trim(Request("sAdminLogin"));
			$sAdminPW 						= Trim(Request("sAdminPW"));
			$sPHP_JK_WEBROOT 				= Trim(Request("sPHP_JK_WEBROOT"));
			$bNewMembers					= Trim(Request("bNewMembers"));
			$sIMAGEGALLERY_SITEURL			= Trim(Request("sIMAGEGALLERY_SITEURL"));
			
			$sGalleryPath	= str_replace("\\\\", "\\", $sPHP_JK_WEBROOT . str_replace("/", "\\", $sIG));
			$sCategoryPath	= str_replace("\\\\", "\\", $sPHP_JK_WEBROOT . str_replace("/", "\\", $sIMAGEGALLERY_CAT_IMAGE_LOC));

			error_reporting(0);
			if ( copy ("test.txt", $sGalleryPath . "test.txt") )
			{
				if ( unlink($sGalleryPath . "test.txt") )
				{
					if ( copy ("test.txt", $sCategoryPath . "test.txt") )
					{
						if ( unlink($sCategoryPath . "test.txt") )
						{
							$PHPJKConnection = DB_DBConnect($sDatabaseServer, $sDatabaseLogin, $sDatabasePassword);
							
							// save the values to the database
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sIG) . "' WHERE ConfConst = 'IG'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sIMAGEGALLERY_CAT_IMAGE_LOC) . "' WHERE ConfConst = 'IMAGEGALLERY_CAT_IMAGE_LOC'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sEMAIL_REMOTEHOST) . "' WHERE ConfConst = 'EMAIL_REMOTEHOST'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sPHP_JK_WEBROOT) . "' WHERE ConfConst = 'PHP_JK_WEBROOT'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sEmailType) . "' WHERE ConfConst = 'EMAIL_TYPE'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sThumbnailType) . "' WHERE ConfConst = 'IMAGEGALLERY_THUMBNAIL_GENERATOR'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sIMAGEGALLERY_SITEURL) . "' WHERE ConfConst = 'IMAGEGALLERY_SITEURL'");
							
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'SEND_USER_REG_EMAIL_SOURCE'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'SEND_ADMIN_REG_FROMEMAIL'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'SEND_ADMIN_REG_EMAIL_DEST'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'IMAGEGALLERY_SUGGESTGALLERY_TO_EMAIL'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'IMAGEGALLERY_SUGGESTGALLERY_FROM_EMAIL'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'IMAGEGALLERY_SUGGESTCATEGORY_TO_EMAIL'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'IMAGEGALLERY_SUGGESTCATEGORY_FROM_EMAIL'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_EMAIL'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechEmail) . "' WHERE ConfConst = 'EMAIL_LOGIN_FROMEMAIL'");
							
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'SEND_USER_REG_EMAIL_NAME'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'SEND_ADMIN_REG_FROMNAME'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'SEND_ADMIN_REG_DESTNAME'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'IMAGEGALLERY_SUGGESTGALLERY_FROM_NAME'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'IMAGEGALLERY_SUGGESTCATEGORY_FROM_NAME'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_NAME'");
							DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sTechName) . "' WHERE ConfConst = 'EMAIL_LOGIN_FROMNAME'");

							if ( $bNewMembers == 'Y' )
							{
								// set the system to allow new members to create galleries and upload images
								DB_Update ("UPDATE Configurations SET Value = '5242880' WHERE ConfConst = 'IMAGEGALLERY_INITIAL_HD_SPACE'");
								DB_Update ("UPDATE Configurations SET Value = '10' WHERE ConfConst = 'IMAGEGALLERY_INITIAL_MAX_NUM_GALLERIES'");
								DB_Update ("UPDATE Configurations SET Value = '200' WHERE ConfConst = 'IMAGEGALLERY_INITIAL_NUMDOWNLOADS'");
								DB_Update ("UPDATE Configurations SET Value = '50' WHERE ConfConst = 'IMAGEGALLERY_INITIAL_NUMUPLOAD'");
								DB_Update ("UPDATE Configurations SET Value = '250000' WHERE ConfConst = 'IMAGEGALLERY_INITIAL_UPLOADBYTES'");
								DB_Update ("UPDATE Configurations SET Value = 'gif jpg png txt zip bmp jpeg pdf doc' WHERE ConfConst = 'IMAGEGALLERY_INITIAL_UPLOADTYPE'");

								DB_Insert ("DELETE FROM InitialRights WHERE RightsLvl = 'PHPJK_IG_ADD_CF_IMAGE'");
								DB_Insert ("DELETE FROM InitialRights WHERE RightsLvl = 'PHPJK_IG_CREATE_GALLERY'");
								DB_Insert ("DELETE FROM InitialRights WHERE RightsLvl = 'PHPJK_IG_DEL_CF_IMAGE'");
								DB_Insert ("DELETE FROM InitialRights WHERE RightsLvl = 'PHPJK_IG_EDIT_CF_DATA_IMAGE'");
								DB_Insert ("DELETE FROM InitialRights WHERE RightsLvl = 'PHPJK_IG_EDIT_CF_IMAGE'");
								DB_Insert ("DELETE FROM InitialRights WHERE RightsLvl = 'PHPJK_IG_REF_GALLERY'");
								
								DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, 'PHPJK_IG_ADD_CF_IMAGE')");
								DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, 'PHPJK_IG_CREATE_GALLERY')");
								DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, 'PHPJK_IG_DEL_CF_IMAGE')");
								DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, 'PHPJK_IG_EDIT_CF_DATA_IMAGE')");
								DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, 'PHPJK_IG_EDIT_CF_IMAGE')");
								DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, 'PHPJK_IG_REF_GALLERY')");
							}
							
							$sQuery			= "SELECT MIN(AccountUnq) AS MinAccountUnq FROM Accounts";
							$rsRecordSet	= DB_Query($sQuery);
							if ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE Accounts SET Login = '" . SQLEncode($sAdminLogin) . "', Password = '" . SQLEncode($sAdminPW) . "' WHERE AccountUnq = " . $rsRow["MinAccountUnq"]);
								
							setcookie("GAL1", $sAdminLogin, 0, "/", $_SERVER["SERVER_NAME"]);
							setcookie("GAP1", md5($sAdminPW), 0, "/", $_SERVER["SERVER_NAME"]);
							setcookie("GAA1", $rsRow["MinAccountUnq"], 0, "/", $_SERVER["SERVER_NAME"]);
							//header( 'location:step4.php' );	// go home on an emergency where neither the referrer nor return page have data
							?>
							<script language='JavaScript1.2' type='text/javascript'>
							
								document.location = "step4.php";
							
							</script>
							<?php

						}else{
							echo "Unable to delete from the " . $sCategoryPath . " folder.<br>";
						}
					}else{
						echo "Unable to write to the " . $sCategoryPath . " folder.<br>";
					}
				}else{
					echo "Unable to delete from the " . $sGalleryPath . " folder.<br>";
				}
			}else{
				echo "Unable to write to the " . $sGalleryPath . " folder.<br>";
			}
		}
		
		if ( $sIG == "" )
		{
			$sIG = str_replace("PHPJK_Installation/step3.php", "", $_SERVER["SCRIPT_NAME"]) . "Galleries/";
		}
		
		if ( $sIMAGEGALLERY_CAT_IMAGE_LOC == "" )
		{
			$sIMAGEGALLERY_CAT_IMAGE_LOC = str_replace("PHPJK_Installation/step3.php", "", $_SERVER["SCRIPT_NAME"]) . "Images/CategoryImages/";
		}
		
		if ( $sEMAIL_REMOTEHOST == "" )
		{
			$sEMAIL_REMOTEHOST = str_replace("www", "mail", $_SERVER["HTTP_HOST"]);
		}
		
		if ( $sTechEmail == "" )
		{
			$sTechEmail = str_replace("www.", "admin@", $_SERVER["HTTP_HOST"]);
		}
		
		if ( $sTechName == "" )
		{
			$sTechName = strtoupper(str_replace(".com", "", str_replace("www.", "", $_SERVER["HTTP_HOST"]))) . " Administrator";
		}

		if ( $sAdminLogin == "" )
		{
			$sAdminLogin = "admin";
		}
		
		if ( $sPHP_JK_WEBROOT == "" )
		{
			$sTemp = str_replace("/", "\\", $_SERVER["SCRIPT_NAME"]);
			$sPHP_JK_WEBROOT = str_replace($sTemp, "", $_SERVER["PATH_TRANSLATED"]) . "\\";
		}
		
		if ( $sIMAGEGALLERY_SITEURL == "" )
		{
			$sIMAGEGALLERY_SITEURL = "http://" . $_SERVER["HTTP_HOST"] . str_replace("PHPJK_Installation/step3.php", "", $_SERVER["SCRIPT_NAME"]);
		}

		?>
		<form action="step3.php" method="post">
		<input type='hidden' name='sAction' value='step3'>
		Values are pre-populated with default information. Please make changes as required.<br><br>
		<table cellpadding=5 cellspacing=5>
			<tr>
				<td bgcolor=f1f1f1>
					Gallery folder (relative to website root - beginning and ending with /)
				</td>
				<td bgcolor=f1f1f1>
					<input type='text' name='sIG' value="<?=$sIG?>" size=40>
				</td>
			</tr>
			<tr>
				<td>
					Category image folder (relative to website root - beginning and ending with /)
				</td>
				<td>
					<input type='text' name='sIMAGEGALLERY_CAT_IMAGE_LOC' value="<?=$sIMAGEGALLERY_CAT_IMAGE_LOC?>" size=40>
				</td>
			</tr>
			
			<tr>
				<td colspan=2 bgcolor=f1f1f1>
					Path to the web root. This should not include any portion of the gallery or category image folders.
					For example: c:\domains\mywebsite\<br><br>
					Then, to create the full path to the gallery folder you would have: c:\domains\mywebsite<b><?=$sIG?></b>
					<br>
					<input type='text' name='sPHP_JK_WEBROOT' value="<?=$sPHP_JK_WEBROOT?>" size=40>
				</td>
			</tr>
			
			<tr>
				<td colspan=2 bgcolor=f1f1f1>
					Path to the web root. This should not include any portion of the gallery or category image folders.
					For example: c:\domains\mywebsite\<br><br>
					Then, to create the full path to the gallery folder you would have: c:\domains\mywebsite<b><?=$sIG?></b>
URL of the PHPJK Site. 
This URL should end at the root of the PHPJK gallery folder. 
So, if you have put the PHPJK pages in a folder called /mygallery/ then this should be http://www.mywebsite.com/mygallery/
					<br>
					<input type='text' name='sIMAGEGALLERY_SITEURL' value="<?=$sIMAGEGALLERY_SITEURL?>" size=40>
				</td>
			</tr>


			<tr>
				<td>
					Mail server's address
				</td>
				<td>
					<input type='text' name='sEMAIL_REMOTEHOST' value="<?=$sEMAIL_REMOTEHOST?>" size=40>
				</td>
			</tr>
			<tr>
				<td bgcolor=f1f1f1>
					Email address of Webmaster or Technical contact
				</td>
				<td bgcolor=f1f1f1>
					<input type='text' name='sTechEmail' value="<?=$sTechEmail?>" size=40>
				</td>
			</tr>
			<tr>
				<td>
					Name to use when sending emails
				</td>
				<td>
					<input type='text' name='sTechName' value="<?=$sTechName?>" size=40>
				</td>
			</tr>
			<tr>
				<td bgcolor=f1f1f1>
					Email component
				</td>
				<td bgcolor=f1f1f1>
					<select name='sEmailType'>
						<option value='' <?php If ( $sEmailType == "" ) Echo "selected"; ?>>Disable email functionality</option>
						<option value='ASPMAIL' <?php If ( $sEmailType == "ASPMAIL" ) Echo "selected"; ?>>ASPMail (www.serverobjects.com)</option>
						<option value='CDONTS' <?php If ( $sEmailType == "CDONTS" ) Echo "selected"; ?>>CDONTS</option>
						<option value='SENDMAIL' <?php If ( $sEmailType == "SENDMAIL" ) Echo "selected"; ?>>sendmail</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Auto-thumbnail component
				</td>
				<td>
					<select name='sThumbnailType'>
						<option value='' <?php If ( $sThumbnailType == "" ) Echo "selected"; ?>>No thumbnail creation</option>
						<option value='PHP' <?php If ( $sThumbnailType == "PHP" ) Echo "selected"; ?>>PHP Native (no component)</option>
						<option value='GFL' <?php If ( $sThumbnailType == "GFL" ) Echo "selected"; ?>>GlfAX (www.xnview.com)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor=f1f1f1>
					Administrator login
				</td>
				<td bgcolor=f1f1f1>
					<input type='text' name='sAdminLogin' value="<?=$sAdminLogin?>">
				</td>
			</tr>
			<tr>
				<td>
					Administrator password
				</td>
				<td>
					<input type='text' name='sAdminPW' value="<?=$sAdminPW?>">
				</td>
			</tr>
			<tr>
				<td colspan=2 bgcolor=f1f1f1>
					Would you like to allow new members to create their own galleries and upload images?
					<select name='bNewMembers'>
						<option value='Y' <?php If ( $bNewMembers == "Y" ) Echo "selected"; ?>>Yes</option>
						<option value='N' <?php If ( $bNewMembers == "N" ) Echo "selected"; ?>>No</option>
					</select>
					<br><br>
					This will set various Initial Rights and Configuration Variables. If you later decide to
					disable this, or change the defaults, consult the documentation on the www.phpjk.com site.
					<br><br>
					Default settings include:
					<ul>
						<li>Initial hard disk space = 5megs
						<li>Number of galleries they may create = 10 galleries
						<li>Number of images they may download each day = 250 images
						<li>Number of images they may upload each day = 50 images
						<li>Maximum image size they may upload = 250k
						<li>They may upload these file types: gif jpg png txt zip bmp jpeg pdf doc
					</ul>
					Default Rights include:
					<ul>
						<li>Rights to create galleries (not categories)
						<li>Rights to add images to their galleries
						<li>Rights to add custom data to their images
						<li>Rights to reference their images from their gallery A to their gallery B
					</ul>
				</td>
			</tr>
		</table>
		<input type='submit' value=' Continue '>
		</form>
		<?php
	}
	//************************************************************************************
?>