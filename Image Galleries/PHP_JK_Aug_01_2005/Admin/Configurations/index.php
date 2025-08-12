<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	If ((ACCNT_ReturnRights("PHPJK_MCONF_DELETE")) || (ACCNT_ReturnRights("PHPJK_MCONF_UPDATE"))) {
		HeaderHTML();
		Main();
	}Else{
		$iRow = 1;
		WriteScripts();
		DOMAIN_Message("You must login with Domains rights.<br>", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		$sAction		= Trim(Request("sAction"));
		$sError			= "";
		$sSuccess		= "";
		
		If ( $sAction == "DeleteConfiguration" ) {

		}ElseIf ( $sAction == "UpdateConfiguration" ) {
				If ( ! ACCNT_ReturnRights("PHPJK_MCONF_UPDATE")) {
					$sError = "You must login with Domains rights.<br>";
				}Else{
					ForEach ($_POST as $sTextField=>$sValue)
					{
						If ( strpos($sTextField, "sOldValue") !== false ) {
							$sConfConst			= str_replace("sOldValue", "", $sTextField);
							$sOldValue			= FixFormData($sValue);
							$sNewValue			= Request("sNewValue" . $sConfConst);
							If ( $sOldValue != $sNewValue ) {
								DB_Update ("UPDATE Configurations SET Value = '" . SQLEncode($sNewValue) . "' WHERE ConfConst = '" . SQLEncode($sConfConst) . "'");
								If ( $sSuccess == "" )
									$sSuccess = "Your configuration variables have been updated.";
							}
						}
					}
				}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		
		WriteScripts();
		WriteForm();
		
	}
	//************************************************************************************
	

	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $aVariables;
		Global $aValues;
		
		$sBGColor	= $GLOBALS["BGColor2"];
		$sTextColor	= $GLOBALS["TextColor2"];
		
		$sQuery			= "SELECT * FROM Configurations (NOLOCK) ORDER BY ConfConst";
		$rsRecordSet	= DB_Query($sQuery);
		?>
		<center>
		<form name='ManageConfConsts' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aValues[0] = "New";
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		 ?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Configuration Variables</b></font>
					<br><br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Configuration Constant</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Value</b></td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
						</tr>
<?php TextValues("ARICAUR_WEBMASTER_ID", DOMAIN_Conf("ARICAUR_WEBMASTER_ID"), "If you have signed up at Aricaur.com to sell your images as prints, t-shirts or gifts, enter your Webmaster ID# here (this number can be found within the management console at Aricaur.com).");?>
<?php TextValues("ARICAUR_WEBMASTER_PASSCODE", DOMAIN_Conf("ARICAUR_WEBMASTER_PASSCODE"), "This is your Aricaur.com passcode.");?>
<?php TextValues("IMAGEGALLERY_CAT_IMAGE_HEIGHT", DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT"), "This is the height (in pixels) of the category image (default 40).");?>
<?php TextValues("IMAGEGALLERY_CAT_IMAGE_WIDTH", DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH"), "This is the width (in pixels) of the category image (default 40).");?>
<?php TextValues("IMAGEGALLERY_FORMCOLUMNS", DOMAIN_Conf("IMAGEGALLERY_FORMCOLUMNS"), "Width of text area form fields in the image gallery.");?>
<?php TextValues("IMAGEGALLERY_FORMWIDTH", DOMAIN_Conf("IMAGEGALLERY_FORMWIDTH"), "Width of form fields in the image gallery (not of text fields).");?>
<?php TextValues("IMAGEGALLERY_PRODUCTIMAGES_MAX_WIDTH", DOMAIN_Conf("IMAGEGALLERY_PRODUCTIMAGES_MAX_WIDTH"), "All thumbnail images associated with products will be displayed this width (in pixels).");?>
<?php TextValues("IMAGEGALLERY_THUMBNAIL_WIDTH", DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH"), "Width of the thumbnails that the system creates and displays. (In pixels.)");?> 
<?php TextValues("IMAGEGALLERY_INITIAL_HD_SPACE", DOMAIN_Conf("IMAGEGALLERY_INITIAL_HD_SPACE"), "This is the amount of disk space each user initially gets. In bytes. Set to -1 for unlimited.");?>
<?php TextValues("IMAGEGALLERY_INITIAL_MAX_NUM_GALLERIES", DOMAIN_Conf("IMAGEGALLERY_INITIAL_MAX_NUM_GALLERIES"), "This is the initial number of galleries each user can create. Set to -1 for unlimited.");?>
<?php TextValues("IMAGEGALLERY_INITIAL_NUMDOWNLOADS", DOMAIN_Conf("IMAGEGALLERY_INITIAL_NUMDOWNLOADS"), "The default max number of files new users can download each day. Set to -1 for unlimited.");?>
<?php TextValues("IMAGEGALLERY_INITIAL_NUMUPLOAD", DOMAIN_Conf("IMAGEGALLERY_INITIAL_NUMUPLOAD"), "The default number of files new users can upload to their galleries. Set to -1 for unlimited.");?>
<?php TextValues("IMAGEGALLERY_INITIAL_UPLOADBYTES", DOMAIN_Conf("IMAGEGALLERY_INITIAL_UPLOADBYTES"), "The default max number of bytes per file new users can upload. Set to -1 for unlimited.");?>
<?php TextValues("IMAGEGALLERY_INITIAL_UPLOADTYPE", DOMAIN_Conf("IMAGEGALLERY_INITIAL_UPLOADTYPE"), "The default file types new users can upload (seperate each type with a space).");?>
<?php TextValues("IMAGEGALLERY_MAX_DISPLAYABLE_PRODUCTS", DOMAIN_Conf("IMAGEGALLERY_MAX_DISPLAYABLE_PRODUCTS"), "This is the max number of products that will be displayed on with each image. If more than this number of products is assigned to the image, this number of products will be chosen and displayed at random.");?>
<?php TextValues("IMAGEGALLERY_MAX_THUMBS_DISPLAYED", DOMAIN_Conf("IMAGEGALLERY_MAX_THUMBS_DISPLAYED"), "This is the maximum number of thumbnails users can display on the thumbnail view page.");?>
<?php TextValues("IMAGEGALLERY_NEWIMAGE_DAYS", DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"), "Set this to the number of days old images can be to have the NEW image displayed with them.");?>
<?php TextValues("IMAGEGALLERY_NONMMBR_NUMDOWNLOADS", DOMAIN_Conf("IMAGEGALLERY_NONMMBR_NUMDOWNLOADS"), "The max number of files nonmembers can download each day. Set to -1 for unlimited.");?>
<?php TextValues("IMAGEGALLERY_NUM_CAT_LIST_ON_CATEGORY_DISPLAY", DOMAIN_Conf("IMAGEGALLERY_NUM_CAT_LIST_ON_CATEGORY_DISPLAY"), "This is the number of subcategories to list on the category display panel.");?>
<?php TextValues("IMAGEGALLERY_NUM_GAL_LIST_ON_CATEGORY_DISPLAY", DOMAIN_Conf("IMAGEGALLERY_NUM_GAL_LIST_ON_CATEGORY_DISPLAY"), "This is the number of galleries to list on the category display panel.");?>
<?php TextValues("IMAGEGALLERY_SCRIPTTIMEOUT", DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT"), "Script timeout in seconds. Used when uploading images/files. If less than value in metabase, then it uses value in metabase.");?>
<?php TextValues("EMAIL_REMOTEHOST", DOMAIN_Conf("EMAIL_REMOTEHOST"), "Email server used to route messages. Usually like mail.YourWebSiteHere.com. Leave blank to disable emails.");?>
<?php TextValues("EMAIL_LOGIN_FROMEMAIL", DOMAIN_Conf("EMAIL_LOGIN_FROMEMAIL"), "This is the email address used when sending users their password when they have forgotten their password.");?>
<?php TextValues("EMAIL_LOGIN_FROMNAME", DOMAIN_Conf("EMAIL_LOGIN_FROMNAME"), "This is the name used.");?>
<?php TextValues("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_EMAIL", DOMAIN_Conf("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_EMAIL"), "The email address the system uses to send out subscription emails.");?> 
<?php TextValues("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_NAME", DOMAIN_Conf("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_NAME"), "The name the system uses to send out subscription emails.");?>
<?php TextValues("IMAGEGALLERY_SUGGESTCATEGORY_FROM_EMAIL", DOMAIN_Conf("IMAGEGALLERY_SUGGESTCATEGORY_FROM_EMAIL"), "The email address the system uses to send out category suggestion emails.");?> 
<?php TextValues("IMAGEGALLERY_SUGGESTCATEGORY_FROM_NAME", DOMAIN_Conf("IMAGEGALLERY_SUGGESTCATEGORY_FROM_NAME"), "The name the system uses to send out category suggestion emails.");?>
<?php TextValues("IMAGEGALLERY_SUGGESTCATEGORY_TO_EMAIL", DOMAIN_Conf("IMAGEGALLERY_SUGGESTCATEGORY_TO_EMAIL"), "The email address the system sends gallery category suggestions to.");?> 
<?php TextValues("IMAGEGALLERY_SUGGESTGALLERY_FROM_EMAIL", DOMAIN_Conf("IMAGEGALLERY_SUGGESTGALLERY_FROM_EMAIL"), "The email address the system uses to send out category suggestion emails.");?>
<?php TextValues("IMAGEGALLERY_SUGGESTGALLERY_FROM_NAME", DOMAIN_Conf("IMAGEGALLERY_SUGGESTGALLERY_FROM_NAME"), "The name the system uses to send gallery category suggestion emails.");?> 
<?php TextValues("IMAGEGALLERY_SUGGESTGALLERY_TO_EMAIL", DOMAIN_Conf("IMAGEGALLERY_SUGGESTGALLERY_TO_EMAIL"), "The email address gallery suggestions are sent to.");?>
<?php TextValues("SEND_ADMIN_REG_DESTNAME", DOMAIN_Conf("SEND_ADMIN_REG_DESTNAME"), "The name of the user who receives the administrator emails that the system sends out when a user registers.");?> 
<?php TextValues("SEND_ADMIN_REG_EMAIL_DEST", DOMAIN_Conf("SEND_ADMIN_REG_EMAIL_DEST"), "The email address of the user who receives the administrator emails that the system sends out when a user registers.");?>
<?php TextValues("SEND_ADMIN_REG_FROMEMAIL", DOMAIN_Conf("SEND_ADMIN_REG_FROMEMAIL"), "The email address of the user who sends the administrator emails that the system sends out when a user registers.");?> 
<?php TextValues("SEND_ADMIN_REG_FROMNAME", DOMAIN_Conf("SEND_ADMIN_REG_FROMNAME"), "The name of the user who sends the administrator emails that the system sends out when a user registers.");?>
<?php TextValues("SEND_USER_REG_EMAIL_NAME", DOMAIN_Conf("SEND_USER_REG_EMAIL_NAME"), "The name of the user who sends the new user registration emails.");?> 
<?php TextValues("SEND_USER_REG_EMAIL_SOURCE", DOMAIN_Conf("SEND_USER_REG_EMAIL_SOURCE"), "The email address of the user who sends the new user registration emails.");?>
<?php TextValues("PHP_JK_WEBROOT", DOMAIN_Conf("PHP_JK_WEBROOT"), "This is the web root drive and directory.");?>
<?php TextValues("IG", DOMAIN_Conf("IG"), "This is the Image Gallery folder name (eg: /Galleries/).  Must have write/delete access for IUSR account.");?>
<?php TextValues("IMAGEGALLERY_CAT_IMAGE_LOC", DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC"), "This is the Image Gallery category image directory name (eg: /Images/CategoryImages/). Must have write/delete access for IUSR account.");?>
<?php TextValues("IMAGEGALLERY_MISSING_THUMBNAIL", DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL"), "Image to display instead if the thumbnail is missing.");?>
<?php TextValues("IMAGEGALLERY_SITEURL", DOMAIN_Conf("IMAGEGALLERY_SITEURL"), "URL of the PHPJK Site. This URL should end at the root of the PHPJK gallery folder. So, if you have put the PHPJK pages in a folder called /mygallery/ then this should be http://www.mywebsite.com/mygallery/");?>
						
						<?php BooleanField("ACCOUNTS_AUTHENTICATION", DOMAIN_Conf("ACCOUNTS_AUTHENTICATION"), "YES", "NO", "Would you like to require that new members authenticate their accounts before they are allowed to log in (requires that email is working)? This sends the new user an authentication an email with a link that they must click on to be authenticated.");?>
						<?php BooleanField("ACCOUNTS_SIGNUP", DOMAIN_Conf("ACCOUNTS_SIGNUP"), "OPEN", "CLOSED", "Would you like to allow anyone to sign up as a new member?");?>
						<?php BooleanField("IMAGEGALLERY_ALLOW_ALL_DISPLAY", DOMAIN_Conf("IMAGEGALLERY_ALLOW_ALL_DISPLAY"), "YES", "NO", "Would you like users to be able to view the full heirarchy of categories and galleries (very CPU intensive)?");?>
<?php BooleanField("IMAGEGALLERY_DISP_CAT_GAL_MAP", DOMAIN_Conf("IMAGEGALLERY_DISP_CAT_GAL_MAP"), "YES", "NO", "Would you like to display the Category Map button?");?>
<?php BooleanField("IMAGEGALLERY_DISP_LINES_ON_CATEGORY_DISPLAY", DOMAIN_Conf("IMAGEGALLERY_DISP_LINES_ON_CATEGORY_DISPLAY"), "YES", "NO", "Would you like to display the lines and graphics on the category display panel?");?>
<?php BooleanField("IMAGEGALLERY_DISP_NEW_IMAGE_SEARCH", DOMAIN_Conf("IMAGEGALLERY_DISP_NEW_IMAGE_SEARCH"), "YES", "NO", "Would you like to display the New Image Search button?");?>
<?php BooleanField("IMAGEGALLERY_DISP_OWNER_COL", DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_COL"), "YES", "NO", "Would you like to display the owner of each gallery on the gallery listing page?");?>
<?php BooleanField("IMAGEGALLERY_DISP_OWNER_INFO", DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_INFO"), "YES", "NO", "Would you like to display the gallery owners name and allow users to see that owners list of galleries?");?>
<?php BooleanField("IMAGEGALLERY_DISP_SUBSCRIBE_CATEGORY", DOMAIN_Conf("IMAGEGALLERY_DISP_SUBSCRIBE_CATEGORY"), "YES", "NO", "Would you like to display the Subscribe to Category button?");?>
<?php BooleanField("IMAGEGALLERY_DISP_SUBSCRIBE_GALLERY", DOMAIN_Conf("IMAGEGALLERY_DISP_SUBSCRIBE_GALLERY"), "YES", "NO", "Would you like to display the Subscribe to Gallery button?");?>
<?php BooleanField("IMAGEGALLERY_DISP_SUGGEST_CATEGORY", DOMAIN_Conf("IMAGEGALLERY_DISP_SUGGEST_CATEGORY"), "YES", "NO", "Would you like to display the Suggest Category button?");?>
<?php BooleanField("IMAGEGALLERY_DISP_SUGGEST_GALLERY", DOMAIN_Conf("IMAGEGALLERY_DISP_SUGGEST_GALLERY"), "YES", "NO", "Would you like to display the Suggest Gallery button?");?>
<?php BooleanField("IMAGEGALLERY_DISP_VOTING", DOMAIN_Conf("IMAGEGALLERY_DISP_VOTING"), "YES", "NO", "Would you like to display the vote results and links to allow users to rate images?");?>
<?php BooleanField("IMAGEGALLERY_DOMAINCHECK", DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"), "YES", "NO", "Would you like to prevent people from referencing your images on their website (some systems this must be disabled and if your images are not appearing in the gallery, check to see if disabling this fixes that)?");?>
<?php BooleanField("IMAGEGALLERY_SEND_ECARD", DOMAIN_Conf("IMAGEGALLERY_SEND_ECARD"), "ALL", "MEMBERS", "Would you like to allow all visitors to the gallery to be able to send e-cards (disabling this only allows members to send e-cards)?");?>
<?php BooleanField("IMAGEGALLERY_USEALPHA", DOMAIN_Conf("IMAGEGALLERY_USEALPHA"), "YES", "NO", "Would you like to display thumbnails with the fading rollover?");?>
<?php BooleanField("SEND_ADMIN_REG_EMAIL", DOMAIN_Conf("SEND_ADMIN_REG_EMAIL"), "YES", "NO", "Would you like to receive an email whenever a user registers (this email is sent even if they are not authenticated)?");?>
<?php BooleanField("SEND_USER_REG_EMAIL", DOMAIN_Conf("SEND_USER_REG_EMAIL"), "YES", "NO", "Would you like to send an email to the user when they register for a new account?");?>
<?php BooleanField("IMAGEGALLERY_HIGHVOLUME_DISPLAY", DOMAIN_Conf("IMAGEGALLERY_HIGHVOLUME_DISPLAY"), "YES", "NO", "Set this to YES to display the categories in a more streamlined method for sites with many images.");?>

<?php MultipleValues("EMAIL_TYPE", DOMAIN_Conf("EMAIL_TYPE"), array(0=>"No emails",1=>"ASPMAIL",2=>"CDONTS",3=>"SENDMAIL"), "Email component to use.");?>
<?php MultipleValues("IMAGEGALLERY_ALTIMAGE_VIEW", DOMAIN_Conf("IMAGEGALLERY_ALTIMAGE_VIEW"), array(0=>"NONE",1=>"ALL",2=>"RIGHTS",3=>"MEMBERS"), "Who would you like to be able to view Alternate View images? ALL (everyone can see), RIGHTS (only members, based on rights they have), MEMBERS (all members, but only, members), or NONE (do not display at all).");?>
<?php MultipleValues("IMAGEGALLERY_CAT_COLUMNS", DOMAIN_Conf("IMAGEGALLERY_CAT_COLUMNS"), array(0=>"1",1=>"2",2=>"3",3=>"4"), "This is the number of columns to display in the Category listing panel.");?>
<?php MultipleValues("IMAGEGALLERY_DISP_IMAGE_ON_CATEGORY_DISPLAY", DOMAIN_Conf("IMAGEGALLERY_DISP_IMAGE_ON_CATEGORY_DISPLAY"), array(0=>"RIGHT",1=>"TOP",2=>"NONE"), "Where would you like to display the category image? Set this to RIGHT to display the category image on the right in the category display panel. Set to TOP to display it above the category name, or NONE not to display it at all.");?>
<?php MultipleValues("IMAGEGALLERY_GFLAX_VER", DOMAIN_Conf("IMAGEGALLERY_GFLAX_VER"), array(0=>"L",1=>"S",2=>"F"), "If you are using GflAx, this is the version you have installed. L for Lite, S for Standard, or F for Full.");?>
<?php MultipleValues("IMAGEGALLERY_THUMBNAIL_GENERATOR", DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_GENERATOR"), array(0=>"GFL",1=>"PHP",2=>"NONE"), "Which component would you like to use to create thumbnails? Set to GFL to use the GflAx xnview.com component. Set to PHP to use the native PHP (for *nix boxes).");?>
<?php MultipleValues("IMAGEGALLERY_THUMBNAILVIEW_NUMCOLUMNS", DOMAIN_Conf("IMAGEGALLERY_THUMBNAILVIEW_NUMCOLUMNS"), array(0=>"1",1=>"2",2=>"3",3=>"4",4=>"5",5=>"6",6=>"7",7=>"8",8=>"9",9=>"10"), "This is the number of columns to display the thumbnails in the galleries on the thumbnail view page.");?>
<?php MultipleValues("IMAGEGALLERY_GDISPLAY_COLUMNS", DOMAIN_Conf("IMAGEGALLERY_GDISPLAY_COLUMNS"), array(0=>"ONE",1=>"TWO"), "How many columns would you like to have on the gallery display page?");?>

						<?php 
if ( 0 == 1 ) {
						while ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sConfConst		= $rsRow["ConfConst"];
							$sDescription	= $rsRow["Description"];
							$sValue			= $rsRow["Value"];
							$sViewRightsLvl	= Trim($rsRow["RightLvlv"]);
							$sEditRightsLvl	= Trim($rsRow["RightLvlc"]);
							$sTempType		= Trim($rsRow["Type"]);

							If (( ACCNT_ReturnRights($sEditRightsLvl) ) || ( $sEditRightsLvl == "" )) {
								If ( $sBGColor == $GLOBALS["BGColor1"] ) {
									$sBGColor	= $GLOBALS["PageBGColor"];
									$sTextColor	= $GLOBALS["PageText"];
									$sLinkColor	= "MediumNavPage";
								}Else{
									$sBGColor	= $GLOBALS["BGColor1"];
									$sTextColor	= $GLOBALS["TextColor1"];
									$sLinkColor	= "MediumNav1";
								}
								 ?>
								<tr>
									<td bgcolor=<?=$sBGColor?>>
										<input type='hidden' name="sOldConfConst<?=htmlentities($sConfConst)?>" value="<?=htmlentities($sConfConst)?>">
										<font color='<?=$sTextColor ?>'><b><?=FixFormData($sConfConst)?>
									</td>
									<td bgcolor=<?=$sBGColor?>>
										<input type='hidden' name="sOldValue<?=htmlentities($sConfConst)?>" value="<?=htmlentities($sValue)?>">
										<input type='text' name="sNewValue<?=htmlentities($sConfConst)?>" value="<?=htmlentities($sValue)?>" size=30 maxlength=250>
									</td>
									<td bgcolor=<?=$sBGColor?>><a href="Edit.php?<?=DOMAIN_Link("G")?>&sConfConst=<?=htmlentities($sConfConst)?>" class='<?=$sLinkColor ?>'>More...</a></td>
									<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sDeleteConfConst<?=htmlentities($sConfConst)?>" value="<?=htmlentities($sConfConst)?>"></td>
								</tr>
								<tr>
									<td colspan=4 bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor ?>' size=-2><?=$sDescription ?></td>
								</tr>
								<?php 
							}ElseIf (( ACCNT_ReturnRights($sViewRightsLvl) || ( $sViewRightsLvl == "" )) ) {
								If ( $sBGColor == $GLOBALS["BGColor1"] ) {
									$sBGColor	= $GLOBALS["PageBGColor"];
									$sTextColor	= $GLOBALS["PageText"];
								}Else{
									$sBGColor	= $GLOBALS["BGColor1"];
									$sTextColor	= $GLOBALS["TextColor1"];
								}
								?>
								<tr>
									<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor ?>'><?=$sConfConst ?><br></td>
									<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor ?>'><?=$sValue ?></td>
									<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
									<td align=center bgcolor=<?=$sBGColor?>>&nbsp;</td>
								</tr>
								<tr>
									<td colspan=4 bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor ?>' size=-2><?=$sDescription ?></td>
								</tr>
								<?php 
							}
						}
}
						?>
					</table>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Sub isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageConfConsts.sAction.value = sAction;
				document.ManageConfConsts.submit();
			}
			
		</script>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This checks to see if the configuration variable they are trying to add is 		*
	//*		already in the DB.															*
	//*																					*
	//************************************************************************************	
	Function DoesntExist($sConfConst)
	{
		$sQuery			= "SELECT * FROM Configurations (NOLOCK) WHERE ConfConst = '" . SQLEncode($sConfConst) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return False;
			
		Return True;
	}
	//************************************************************************************	
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function HeaderHTML()
	{
		Global $aVariables;
		Global $aValues;
		
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If (ACCNT_ReturnRights("PHPJK_MCONF_UPDATE")) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateConfiguration.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Update all Configuration Variables with changes on this page.' onClick='SubmitForm(\"UpdateConfiguration\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				?>
				<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=6 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function BooleanField($sConfVarName, $sConfVarValue, $sTrueValue, $sFalseValue, $sDesc)
	{
		// $sTrueValue is required because some conf vars are true if they are "YES" or true if they are "OPEN", etc.
		Global $iRow;
		
		if ( $iRow == 1 )
		{
			$sBGColor	= $GLOBALS["PageBGColor"];
			$sTextColor	= $GLOBALS["PageText"];
			$sLinkColor	= "MediumNavPage";
			$iRow = 0;
		}else{
			$sBGColor	= $GLOBALS["BGColor1"];
			$sTextColor	= $GLOBALS["TextColor1"];
			$sLinkColor	= "MediumNav1";
			$iRow = 1;
		}
		
		?>
		<tr>
			<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'><?=$sDesc?></td>
			<td bgcolor=<?=$sBGColor?>>
				<select name='sNewValue<?=$sConfVarName?>'>
					<option value='<?=$sTrueValue?>' <?php if ( $sConfVarValue == $sTrueValue ) {echo "selected";};?>>Yes</option>
					<option value='<?=$sFalseValue?>' <?php if ( $sConfVarValue == $sFalseValue ) {echo "selected";};?>>No</option>
				</select>
				<input type='hidden' name="sOldValue<?=htmlentities($sConfVarName)?>" value="<?=htmlentities($sConfVarValue)?>">
			</td>
			<td bgcolor=<?=$sBGColor?>><a href="Edit.php?<?=DOMAIN_Link("G")?>&sConfConst=<?=$sConfVarName?>" class='<?=$sLinkColor?>'>More...</a></td>
		</tr>
		<?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function MultipleValues($sConfVarName, $sConfVarValue, $aCValues, $sDesc)
	{
		Global $iRow;
		
		if ( $iRow == 1 )
		{
			$sBGColor	= $GLOBALS["PageBGColor"];
			$sTextColor	= $GLOBALS["PageText"];
			$sLinkColor	= "MediumNavPage";
			$iRow = 0;
		}else{
			$sBGColor	= $GLOBALS["BGColor1"];
			$sTextColor	= $GLOBALS["TextColor1"];
			$sLinkColor	= "MediumNav1";
			$iRow = 1;
		}
		
		$iNum = count($aCValues);
		?>
		<tr>
			<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'><?=$sDesc?></td>
			<td bgcolor=<?=$sBGColor?>>
				<select name='sNewValue<?=$sConfVarName?>'>
					<?php
					for ($i=0; $i<$iNum; $i++) {
						echo "<option value='";
						echo $aCValues[$i];
						echo "'";
						if ( $sConfVarValue == $aCValues[$i] )
						{
							echo " selected";
						}
						echo ">";
						echo $aCValues[$i];
						echo "</option>";
					}
					?>
				</select>
				<input type='hidden' name="sOldValue<?=htmlentities($sConfVarName)?>" value="<?=htmlentities($sConfVarValue)?>">
			</td>
			<td bgcolor=<?=$sBGColor?>><a href="Edit.php?<?=DOMAIN_Link("G")?>&sConfConst=<?=$sConfVarName?>" class='<?=$sLinkColor?>'>More...</a></td>
		</tr>
		<?php
	}
	//************************************************************************************
	

	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function TextValues($sConfVarName, $sConfVarValue, $sDesc)
	{
		Global $iRow;
		
		if ( $iRow == 1 )
		{
			$sBGColor	= $GLOBALS["PageBGColor"];
			$sTextColor	= $GLOBALS["PageText"];
			$sLinkColor	= "MediumNavPage";
			$iRow = 0;
		}else{
			$sBGColor	= $GLOBALS["BGColor1"];
			$sTextColor	= $GLOBALS["TextColor1"];
			$sLinkColor	= "MediumNav1";
			$iRow = 1;
		}

		?>
		<tr>
			<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'><?=$sDesc?></td>
			<td bgcolor=<?=$sBGColor?>>
				<input type='hidden' name="sOldValue<?=htmlentities($sConfVarName)?>" value="<?=htmlentities($sConfVarValue)?>">
				<input type='text' name="sNewValue<?=htmlentities($sConfVarName)?>" value="<?=htmlentities($sConfVarValue)?>" size=30 maxlength=250>
			</td>
			<td bgcolor=<?=$sBGColor?>><a href="Edit.php?<?=DOMAIN_Link("G")?>&sConfConst=<?=$sConfVarName?>" class='<?=$sLinkColor?>'>More...</a></td>
		</tr>
		<?php
	}
	//************************************************************************************
 ?>