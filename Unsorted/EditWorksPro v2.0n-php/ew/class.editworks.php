<?php 
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : EditWorks Professional                           //
//   Release Version      : 2.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
?>

<?php

	if(is_numeric(strpos($_SERVER["PHP_SELF"], "class.editworks.php")))
		$EWP_PATH = "";
	else
		$EWP_PATH = "ew/";

	// Define constants for calling varions class functions
	define("EW_PATH_TYPE_FULL", 0);
	define("EW_PATH_TYPE_ABSOLUTE", 1);
	define("EW_DOC_TYPE_SNIPPET", 0);
	define("EW_DOC_TYPE_HTML_PAGE", 1);
	define("EW_IMAGE_TYPE_ROW", 0);
	define("EW_IMAGE_TYPE_THUMBNAIL", 1);

?>

<?php if($_GET["ToDo"] == "") { ?>
	<link rel="stylesheet" href="ew/ew_includes/ew_styles.css" type="text/css">
<?php } else { ?>
	<link rel="stylesheet" href="ew_includes/ew_styles.css" type="text/css">
<?php } ?>
<?php require_once($EWP_PATH . "ew_lang/language.php"); ?>
<?php

	function DisplayIncludes ($file, $errorMsg)
	{
		// This function will load a .inc file and replace any
		// values that start with [sTxt using a regexp with the
		// values that were defined as constants in ew_lang/language.php
		
		global $EWP_PATH;
		global $EWP_IMG_PATH;
		global $filePath;
		
		$filePath = $EWP_PATH . "ew_includes/$file";

		if(file_exists($filePath))
		{
			// Workout the location of class.editworks.php
			$url = $_SERVER["SERVER_NAME"];
			$scriptName = "class.editworks.php";
			$scriptDir = strrev($_SERVER["PATH_INFO"]);
			$slashPos = strpos($scriptDir, "/");
			$scriptDir = strrev(substr($scriptDir, $slashPos, strlen($scriptDir)));
			$scriptName = $scriptDir . $scriptName;

			$fp = fopen($filePath, "rb");
			$fileContent = "";

			while($data = fgets($fp, 1024))
			{
				$data = str_replace("\$URL", $url, $data);
				$data = str_replace("\$SCRIPTNAME", $scriptName, $data);
				$fileContent .= preg_replace("/\[sTxt(\w*)\]/ei","sTxt\\1", $data);
			}
			
			// Close the file pointer and output the pReg'd code
			fclose($fp);
			echo $fileContent;
		}
		else
		{
			echo "file not found: $file";
		}
	}
	
	// Examine the value of the ToDo argument and proceed to correct sub
	$ToDo = $_GET["ToDo"];
	
	if($ToDo == "")
		$ToDo = $_POST["ToDo"];
	
	switch($ToDo)
	{
		case "InsertImage":
		{
			// Pass to insert image screen
			include("ew_includes/insert_image.php");
			break;
		}
		case "DeleteImage":
		{
			include("ew_includes/insert_image.php");
			break;
		}
		case "UploadImage":
		{
			include("ew_includes/insert_image.php");
			break;
		}
		case "InsertTable":
		{
			DisplayIncludes("insert_table.inc", "Insert Table");
			break;
		}
		case "ModifyTable":
		{
			DisplayIncludes("modify_table.inc", "Modify Table");
			break;
		}
		case "ModifyCell":
		{
			DisplayIncludes("modify_cell.inc", "Modify Cell");
			break;
		}
		case "ModifyImage":
		{
			DisplayIncludes("modify_image.inc", "Modify Image");
			break;
		}
		case "InsertForm":
		{
			DisplayIncludes("insert_form.inc", "Insert Form");
			break;
		}
		case "ModifyForm":
		{
			DisplayIncludes("modify_form.inc", "Modify Form");
			break;
		}
		case "InsertTextField":
		{
			DisplayIncludes("insert_textfield.inc", "Insert Text Field");
			break;
		}
		case "ModifyTextField":
		{
			DisplayIncludes("modify_textfield.inc", "Modify Text Field");
			break;
		}
		case "InsertTextArea":
		{
			DisplayIncludes("insert_textarea.inc", "Insert Text Area");
			break;
		}
		case "ModifyTextArea":
		{
			DisplayIncludes("modify_textarea.inc", "Modify Text Area");
			break;
		}
		case "InsertHidden":
		{
			DisplayIncludes("insert_hidden.inc", "Insert Hidden Field");
			break;
		}
		case "ModifyHidden":
		{
			DisplayIncludes("modify_hidden.inc", "Modify Hidden Field");
			break;
		}
		case "InsertButton":
		{
			DisplayIncludes("insert_button.inc", "Insert Button");
			break;
		}
		case "ModifyButton":
		{
			DisplayIncludes("modify_button.inc", "Modify Button");
			break;
		}
		case "InsertCheckbox":
		{
			DisplayIncludes("insert_checkbox.inc", "Insert Checkbox");
			break;
		}
		case "ModifyCheckbox":
		{
			DisplayIncludes("modify_checkbox.inc", "Modify Checkbox");
			break;
		}
		case "InsertRadio":
		{
			DisplayIncludes("insert_radio.inc", "Insert Radio");
			break;
		}
		case "ModifyRadio":
		{
			DisplayIncludes("modify_radio.inc", "Modify Radio");
			break;
		}
		case "PageProperties":
		{
			DisplayIncludes("page_properties.inc", "Page Properties");
			break;
		}
		case "InsertLink":
		{
			DisplayIncludes("insert_link.inc", "Insert HyperLink");
			break;
		}
		case "InsertEmail":
		{
			DisplayIncludes("insert_email.inc", "Insert Email Link");
			break;
		}
		case "InsertAnchor":
		{
			DisplayIncludes("insert_anchor.inc", "Insert Anchor");
			break;
		}
		case "ModifyAnchor":
		{
			DisplayIncludes("modify_anchor.inc", "Modify Anchor");
			break;
		}
		case "ShowHelp":
		{
			DisplayIncludes("help.inc", "Help");
			break;
		}
	}
	
	class ew
	{
		var $__controlWidth;
		var $__controlHeight;
		var $__initialValue;
		var $__langPack;
		var $__hideBold;
		var $__hideUnderline;
		var $__hideItalic;
		var $__hideNumberList;
		var $__hideBulletList;
		var $__hideDecreaseIndent;
		var $__hideIncreaseIndent;
		var $__hideLeftAlign;
		var $__hideCenterAlign;
		var $__hideRightAlign;
		var $__hideJustify;
		var $__hideHorizontalRule;
		var $__hideLink;
		var $__hideAnchor;
		var $__hideMailLink;
		var $__hideHelp;
		var $__hideFont;
		var $__hideSize;
		var $__hideFormat;
		var $__hideStyle;
		var $__hideForeColor;
		var $__hideBackColor;
		var $__hideTable;
		var $__hideForm;
		var $__hideImage;
		var $__hideSymbols;
		var $__hideProps;
		var $__hideWord;
		var $__hideGuidelines;
		var $__disableSourceMode;
		var $__disablePreviewMode;
		var $__guidelinesOnByDefault;
		var $__imagePathType;
		var $__docType;
		var $__imageDisplayType;
		var $__disableImageUploading;
		var $__disableImageDeleting;
		
		// Keep track of how many buttons are hidden in the top row.
		// If they are all hidden, then we dont show that row of the menu.
		var $__numTopHidden;
		var $__numBottomHidden;
		
		function ew()
		{
			// Set the default value of all private variables for the class
			$this->__controlWidth = 0;
			$this->__controlHeight = 0;
			$this->__initialValue = 0;
			$this->__langPack = 0;
			$this->__hideBold = 0;
			$this->__hideUnderline = 0;
			$this->__hideItalic = 0;
			$this->__hideNumberList = 0;
			$this->__hideBulletList = 0;
			$this->__hideDecreaseIndent = 0;
			$this->__hideIncreaseIndent = 0;
			$this->__hideLeftAlign = 0;
			$this->__hideCenterAlign = 0;
			$this->__hideRightAlign = 0;
			$this->__hideJustify = 0;
			$this->__hideHorizontalRule = 0;
			$this->__hideLink = 0;
			$this->__hideAnchor = 0;
			$this->__hideMailLink = 0;
			$this->__hideHelp = 0;
			$this->__hideFont = 0;
			$this->__hideSize = 0;
			$this->__hideFormat = 0;
			$this->__hideStyle = 0;
			$this->__hideForeColor = 0;
			$this->__hideBackColor = 0;
			$this->__hideTable = 0;
			$this->__hideForm = 0;
			$this->__hideImage = 0;
			$this->__hideSymbols = 0;
			$this->__hideProps = 0;
			$this->__hideWord = 0;
			$this->__hideGuidelines = 0;
			$this->__disableSourceMode = 0;
			$this->__disablePreviewMode = 0;
			$this->__guidelinesOnByDefault = 0;
			$this->__numTopHidden = 0;
			$this->__numBottomHidden = 0;
			$this->__imagePathType = 0;
			$this->__docType = 0;
			$this->__imageDisplayType = 0;
			$this->__disableImageUploading = 0;
			$this->__disableImageDeleting = 0;
		}

		function SetWidth($Width)
		{
			$this->__controlWidth = $Width;
		}
		
		function SetHeight($Height)
		{
			$this->__controlHeight = $Height;
		}

		function SetValue($HTMLValue)
		{
			// Format the initial text so that we can set the content of the iFrame to its value
			$this->__initialValue = $HTMLValue;

			if($this->__initialValue != "")
			{
				if(isIE55OrAbove)
				{
					$this->__initialValue = str_replace("'", "\'", $this->__initialValue);
					$this->__initialValue = str_replace(chr(13) . chr(10), "\r\n", $this->__initialValue);
				}
				else
				{
					$this->__initialValue = $HTMLValue;
					$this->__initialValue = str_replace("\\'", "'", $this->__initialValue);
					$this->__initialValue = str_replace('\\"', '"', $this->__initialValue);
				}
			}
		}
		
		function GetValue($ConvertQuotes = true)
		{
			$tmpVal = $_POST["ew_control_html"];

			if($ConvertQuotes == false)
			{
				$tmpVal = str_replace("\\'", "'", $tmpVal);
				$tmpVal = str_replace('\\"', '"', $tmpVal);
			}

			return $tmpVal;
		}

		function HideBoldButton()
		{
			// Hide the bold button
			$this->__hideBold = true;
			$this->__numTopHidden++;
		}
		
		function HideUnderlineButton()
		{
			// Hide the underline button
			$this->__hideUnderline = true;
			$this->__numTopHidden++;
		}

		function HideItalicButton()
		{
			// Hide the italic button
			$this->__hideItalic = true;
			$this->__numTopHidden++;
		}

		function HideNumberListButton()
		{
			// Hide the number list button
			$this->__hideNumberList = true;
			$this->__numTopHidden++;
		}

		function HideBulletListButton()
		{
			// Hide the bullet list button
			$this->__hideBulletList = true;
			$this->__numTopHidden++;
		}

		function HideDecreaseIndentButton()
		{
			// Hide the decrease indent button
			$this->__hideDecreaseIndent = true;
			$this->__numTopHidden++;
		}

		function HideIncreaseIndeitButton()
		{
			// Hide the increase indent button
			$this->__hideIncreaseIndent = true;
			$this->__numTopHidden++;
		}

		function HideLeftAlignButton()
		{
			// Hide the left align button
			$this->__hideLeftAlign = true;
			$this->__numTopHidden++;
		}

		function HideCenterAlignButton()
		{
			// Hide the center align button
			$this->__hideCenterAlign = true;
			$this->__numTopHidden++;
		}

		function HideRightAlignButton()
		{
			// Hide the right align button
			$this->__hideRightAlign = true;
			$this->__numTopHidden++;
		}

		function HideJustifyButton()
		{
			// Hide the justify button
			$this->__hideJustify = true;
			$this->__numTopHidden++;
		}

		function HideHorizontalRuleButton()
		{
			// Hide the horizontal rule button
			$this->__hideHorizontalRule = true;
			$this->__numTopHidden++;
		}

		function HideLinkButton()
		{
			// Hide the link button
			$this->__hideLink = true;
			$this->__numTopHidden++;
		}

		function HideAnchorButton()
		{
			// Hide the anchor button
			$this->__hideAnchor = true;
			$this->__numTopHidden++;
		}

		function HideMailLinkButton()
		{
			// Hide the mail link button
			$this->__hideMailLink = true;
			$this->__numTopHidden++;
		}

		function HideHelpButton()
		{
			// Hide the help button
			$this->__hideHelp = true;
			$this->__numTopHidden++;
		}

		function HideFontList()
		{
			// Hide the font list
			$this->__hideFont = true;
			$this->__numBottomHidden++;
		}
		
		function HideSizeList()
		{
			// Hide the size list
			$this->__hideSize = true;
			$this->__numBottomHidden++;
		}

		function HideFormatList()
		{
			// Hide the format list
			$this->__hideFormat = true;
			$this->__numBottomHidden++;
		}

		function HideStyleList()
		{
			// Hide the style list
			$this->__hideStyle = true;
			$this->__numBottomHidden++;
		}

		function HideForeColorButton()
		{
			// Hide the forecolor button
			$this->__hideForeColor = true;
			$this->__numBottomHidden++;
		}
		
		function HideBackColorButton()
		{
			// Hide the backcolor button
			$this->__hideBackColor = true;
			$this->__numBottomHidden++;
		}

		function HideTableButton()
		{
			// Hide the table button
			$this->__hideTable = true;
			$this->__numBottomHidden++;
		}

		function HideFormButton()
		{
			// Hide the form button
			$this->__hideForm = true;
			$this->__numBottomHidden++;
		}
		
		function HideImageButton()
		{
			// Hide the image button
			$this->__hideImage = true;
			$this->__numBottomHidden++;
		}

		function HideSymbolButton()
		{
			// Hide the symbol button
			$this->__hideSymbols = true;
			$this->__numBottomHidden++;
		}

		function HidePropertiesButton()
		{
			// Hide the properties button
			$this->__hideProps = true;
			$this->__numBottomHidden++;
		}

		function HideCleanHTMLButton()
		{
			// Hide the clean HTML button
			$this->__hideWord = true;
			$this->__numBottomHidden++;
		}

		function HideGuidelinesButton()
		{
			// Hide the guidelines button
			$this->__hideGuidelines = true;
			$this->__numBottomHidden++;
		}

		function DisableSourceMode()
		{
			// Hide the source mode button
			$this->__disableSourceMode = true;
		}
		
		function DisablePreviewMode()
		{
			// Hide the preview mode button
			$this->__disablePreviewMode = true;
		}

		function EnableGuidelines()
		{
			// Set the table guidelines on by default
			$this->__guidelinesOnByDefault = true;
		}

		function SetPathType($PathType)
		{
			// How do we want to include the path to the images? 0 = Full, 1 = Absolute
			$this->__imagePathType = $PathType;
		}
		
		function SetDocumentType($DocType)
		{
			// Is the user editing a full HTML document
			$this->__docType = $DocType;
		}

		function SetImageDisplayType($DisplayType)
		{
			// How should the images be displayed in the image manager? 0 = Line / 1 = Thumbnails
			$this->__imageDisplayType = $DisplayType;
		}

		function DisableImageUploading()
		{
			// Do we need to stop images being uploaded?
			$this->__disableImageUploading = 1;
		}

		function DisableImageDeleting()
		{
			// Do we need to stop images from being delete?
			$this->__disableImageDeleting = 1;
		}

		function isIE55OrAbove()
		{
			// Is it MSIE?
			$browserCheck1 = ( is_numeric(strpos($_SERVER["HTTP_USER_AGENT"], "MSIE")) ) ? true : false;

			// Is it version 5.5 or above?
			$browserCheck2 = ( is_numeric(strpos($_SERVER["HTTP_USER_AGENT"], "5.5")) || is_numeric(strpos($_SERVER["HTTP_USER_AGENT"], "6.0")) ) ? true : false;

			// Is it NOT Opera?
			$browserCheck3 = ( !is_numeric(strpos($_SERVER["HTTP_USER_AGENT"], "Opera")) ) ? true : false;

			if($browserCheck1 && $browserCheck2 && $browserCheck3)
				return true;
			else
				return false;
		}
		
		function ShowControl($Width, $Height, $ImagePath)
		{
			global $EWP_PATH;

			$this->SetWidth($Width);
			$this->SetHeight($Height);

			// If the browser isn't IE5.5 or above, show a <textarea> tag and die
			if(!isIE55OrAbove)
			{
			?>
				<span style="background-color: lightyellow"><font face="verdana" size="1" color="red"><b>Your browser must be IE5.5 or above to display the EditWorks control. A plain text box will be displayed instead.</b></font></span><br>
				<textarea style="width:<?php echo $this->__controlWidth; ?>; height:<?php echo $this->__controlHeight; ?>" rows="10" cols="30" name="ew_control_html"><?php echo str_replace("\\'", "'", $this->__initialValue); ?></textarea>
			<?php
			}
			else
			{
			
        			// Do we need to hide the page properties button?
        			if($this->__hideProps != 0 || $this->__docType == 0)
        				$this->HidePropertiesButton();
        
        			$filePath = $EWP_PATH . "ew_includes/jsfunctions.inc";
        			
        			if(file_exists($filePath))
        			{
        				// Workout the location of class.editworks.php
        				$url = $_SERVER["HTTP_HOST"];
        				$scriptName = dirname($_SERVER["SCRIPT_NAME"]) . "/ew/class.editworks.php";
        				
        				$fp = fopen($filePath, "rb");
        				$fileContent = "";
        				
        				while($data = fgets($fp, 1024))
        				{
        					$data = str_replace("\$URL", $url, $data);
        					$data = str_replace("\$SCRIPTNAME", $scriptName, $data);
        					$data = str_replace("\$IMAGEDIR", $ImagePath, $data);
        					$data = str_replace("\$SHOWTHUMBNAILS", $this->__imageDisplayType, $data);
        					$data = str_replace("\$EDITINGHTMLDOC", $this->__docType, $data);
        					$data = str_replace("\$PATHTYPE", $this->__imagePathType, $data);
        					$data = str_replace("\$GUIDELINESDEFAULT", $this->__guidelinesOnByDefault, $data);
        					$data = str_replace("\$DISABLEIMAGEUPLOADING", $this->__disableImageUploading, $data);
        					$data = str_replace("\$DISABLEIMAGEDELETING", $this->__disableImageDeleting, $data);
        
        					$fileContent .= preg_replace("/\[sTxt(\w*)\]/e","sTxt\\1", $data);
        				}
        				
        				// Close the file pointer and output the pReg'd code
        				fclose($fp);
        				echo $fileContent;
        			}
        			else
        			{
        				echo "file not found: jsfunctions.inc";
        				die();
        			}
        			?>
        				<table id="fooContainer" width="<?php echo $this->__controlWidth; ?>" height="<?php echo $this->__controlHeight; ?>" border="1" cellspacing="0" cellpadding="0">
        					<tr>
        						<td height=1>
        							<?php include($EWP_PATH . "ew_includes/toolbar.php"); ?>
        							</td></tr>
        							<tr><td>
        							<table class=iframe height=100% width=100%>
        								<tr height=100%>
        									<td>
        										<iFrame onBlur="updateValue()" SECURITY="restricted" contenteditable HEIGHT=100% id="foo" style="width:100%;"></iFrame>
        										<iframe onBlur="updateValue()" id=previewFrame height=100% style="width=100%; display:none"></iframe>
        										<input type="hidden" name="ew_control_html" value="">
        									</td>
        								</tr>
        							</table>
        							</td></tr>
        							<tr><td height=1>
        							<table cellpadding=0 cellspacing=0 width=100% style="background-color: threedface" class=status>
        								<tr>
        									<td background=ew/ew_images/status_border.gif height=22><img style="cursor:hand;" id=editTab src=ew/ew_images/status_edit_up.gif width=98 height=22 border=0 onClick=editMe()><img style="cursor:hand; <?php if($this->__disableSourceMode == true) {?>display:none<?php } ?>" id=sourceTab src=ew/ew_images/status_source.gif width=98 height=22 border=0 onClick=sourceMe()><img style="cursor:hand; <?php if($this->__disablePreviewMode == true) {?>display:none<?php } ?>" id=previewTab src=ew/ew_images/status_preview.gif width=98 height=22 border=0 onClick=previewMe()></td>
        									<td background=ew/ew_images/status_border.gif id=statusbar align=right>&nbsp;</td>
        								</tr>
        							</table>
        						</td>
        					</tr>
        				</table>
        
        				<script language="JavaScript">
        							
        					var fooWidth = "<?php echo $this->__controlWidth; ?>";
        					var fooHeight = "<?php echo $this->__controlHeight; ?>";
        
        					function setValue()
        					{
        						<?php if($this->__docType == 0) { ?>
        							foo.document.write('');
        							foo.document.close()
        							foo.document.body.innerHTML = '<?php echo $this->__initialValue; ?>'
        						<?php } else { ?>
        							foo.document.write('<?php echo $this->__initialValue; ?>');
        							foo.document.close()
        						<?php } ?>
        					}
        
        					function updateValue()
        					{
									if (document.activeElement) {
										if (document.activeElement.parentElement.id == "ew") {
											return false;
										} else {
											document.all.ew_control_html.value = SaveHTMLPage();
										}
									}
        					}
        							
        				</script>
        				
        			<?php
			}
		}
	}
?>