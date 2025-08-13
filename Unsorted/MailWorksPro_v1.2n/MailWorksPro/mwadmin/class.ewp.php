<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
// Variable to track how many controls are instantiated
	$__numControls = 0;

	class EWP
	{
		// Private member variables
		var $__controlId;
		var $__width;
		var $__height;
		var $__value;
		var $__imagePath;
		var $__borderWidth;
		var $__borderColor;
		var $__customInserts;
		var $__numCustomInserts;
		var $__showToggle;
		var $__showBold;
		var $__showItal;
		var $__showUnde;
		var $__showLeftAlign;
		var $__showCenterAlign;
		var $__showRightAlign;
		var $__showOrderedList;
		var $__showBulletList;
		var $__showTextColor;
		var $__showBackColor;
		var $__showLink;
		var $__showRule;
		var $__showTable;
		var $__showImage;
		var $__showTextColorButton;
		var $__showBGColorButton;
		var $__showLinkButton;
		var $__showHorizontalRule;
		var $__showFontList;
		var $__showSizeList;
		var $__showHeadingList;
		var $__showCustomInsertList;
		
		// Constructor
		function EWP()
		{
			// Keep track of how many controls are instantiated
			global $__numControls;
			
			$__numControls++;
			$this->__controlId = $__numControls;

			// Setup default member variable values
			$this->__width = 0;
			$this->__height = 0;
			$this->__value = "";
			$this->__imagePath = "";
			$this->__borderWidth = 0;
			$this->__borderColor = "";
			$this->__customInserts = array();
			$this->__numCustomInserts = 0;
			$this->__showToggle = true;
			$this->__showBold = true;
			$this->__showItal = true;
			$this->__showUnde = true;
			$this->__showLeftAlign = true;
			$this->__showCenterAlign = true;
			$this->__showRightAlign = true;
			$this->__showOrderedList = true;
			$this->__showBulletList = true;
			$this->__showTextColor = true;
			$this->__showBackColor = true;
			$this->__showLink = true;
			$this->__showRule = true;
			$this->__showImage = true;
			$this->__showTextColorButton = true;
			$this->__showBGColorButton = true;
			$this->__showLinkButton = true;
			$this->__showHorizontalRule = true;
			$this->__showTable = true;
			$this->__showFontList = true;
			$this->__showSizeList = true;
			$this->__showSizeList = true;
			$this->__showHeadingList = true;
			$this->__showCustomInsertList = true;
		}
		
		function ShowControl($Width, $Height, $ImagePath)
		{
			$this->__width = $Width;
			$this->__height = $Height;
			$this->__imagePath = $ImagePath
		?>
			<style>

			  .butClass
			  {    
			    border: 1px solid;
			    border-color: #D6D3CE;
			  }
			  
			  .tdClass
			  {
			    padding-left: 3px;
			    padding-top:3px;
			  }

			</style>

			<table id="tblCtrls<?php echo $this->__controlId; ?>" width="<?php echo $this->__width; ?>" height="30px" border="<?php echo $this->__borderWidth; ?>" borderColor="<?php echo $this->__borderColor; ?>" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<table id="tblCtrls<?php echo $this->__controlId; ?>" width="<?php echo $this->__width; ?>" height="30px" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE">
						<tr>
							<td class="tdClass">
							
								<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="85%">
								
								<?php if($this->__showBold == "true") { ?>
									<img id="boldBut<?php echo $this->__controlId; ?>" alt="Bold" class="butClass" src="ewp_images/bold.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBold<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showItal == "true") { ?>
									<img id="italBut<?php echo $this->__controlId; ?>" alt="Italic" class="butClass" src="ewp_images/italic.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doItalic<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showUnde == "true") { ?>
									<img id="undeBut<?php echo $this->__controlId; ?>" alt="Underline" class="butClass" src="ewp_images/underline.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doUnderline<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showLeftAlign == "true") { ?>
									<img id="leftBut<?php echo $this->__controlId; ?>" alt="Left" class="butClass" src="ewp_images/left.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLeft<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showCenterAlign == "true") { ?>
									<img id="centBut<?php echo $this->__controlId; ?>" alt="Center" class="butClass" src="ewp_images/center.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCenter<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showRightAlign == "true") { ?>
									<img id="rightBut<?php echo $this->__controlId; ?>" alt="Right" class="butClass" src="ewp_images/right.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRight<?php echo $this->__controlId; ?>()">
								<?php } ?>
												
								<?php if($this->__showOrderedList == "true") { ?>
									<img id="ordBut<?php echo $this->__controlId; ?>" alt="Ordered List" class="butClass" src="ewp_images/ordlist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOrdList<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showBulletList == "true") { ?>
									<img id="unordBut<?php echo $this->__controlId; ?>" alt="Bulleted List" class="butClass" src="ewp_images/bullist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBulList<?php echo $this->__controlId; ?>()">
								<?php } ?>

								<?php if($this->__showTextColor == "true") { ?>
									<img id="fcolBut<?php echo $this->__controlId; ?>" alt="Text Color" class="butClass" src="ewp_images/forecol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doForeCol<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showBackColor == "true") { ?>
									<img id="bcolBut<?php echo $this->__controlId; ?>" alt="Background Color" class="butClass" src="ewp_images/bgcol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBackCol<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showLink == "true") { ?>
									<img id="linkBut<?php echo $this->__controlId; ?>" alt="Hyperlink" class="butClass" src="ewp_images/link.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLink<?php echo $this->__controlId; ?>()">
								<?php } ?>
							
								<?php if($this->__showImage == "true") { ?>
									<img id="imageBut<?php echo $this->__controlId; ?>" alt="Image" class="butClass" src="ewp_images/image.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doImage<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showHorizontalRule == "true") { ?>
									<img id="ruleBut<?php echo $this->__controlId; ?>" alt="Horizontal Rule" class="butClass" src="ewp_images/rule.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRule<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								<?php if($this->__showTable == "true") { ?>
									<img id="tableBut<?php echo $this->__controlId; ?>" alt="Table" class="butClass" src="ewp_images/table.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doTable<?php echo $this->__controlId; ?>()">
								<?php } ?>
								
								</td><td width="15%" align="right">
							  
							  <?php if($this->__showToggle == true) { ?>
								<img alt="Toggle Mode" class="butClass" src="ewp_images/mode.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doToggleView<?php echo $this->__controlId; ?>()">
								&nbsp;
	  						  <?php } ?>
	  						  
	  							</td></tr></table>
								
						<table width="<?php echo $this->__width; ?>" height="30px" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE">
						<tr>
							<td class="tdClass" colspan="1" width="80%">
							  <?php if($this->__showFontList == true) { ?>
								<select id="selFont<?php echo $this->__controlId; ?>" onChange="doFont<?php echo $this->__controlId; ?>(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
								  <option value="">-- Font --</option>
								  <option value="Arial">Arial</option>
								  <option value="Courier">Courier</option>
								  <option value="Sans Serif">Sans Serif</option>
								  <option value="Tahoma">Tahoma</option>
								  <option value="Verdana">Verdana</option>
								  <option value="Wingdings">Wingdings</option>
								</select>
							<?php } ?>
							<?php if($this->__showSizeList == true) { ?>
							  <select id="selSize<?php echo $this->__controlId; ?>" onChange="doSize<?php echo $this->__controlId; ?>(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
							    <option value="">-- Size --</option>
							    <option value="1">Very Small</option>
							    <option value="2">Small</option>
							    <option value="3">Medium</option>
							    <option value="4">Large</option>
							    <option value="5">Larger</option>
							    <option value="6">Very Large</option>
							  </select>
							<?php } ?>
							<?php if($this->__showHeadingList == true) { ?>
							  <select id="selHeading<?php echo $this->__controlId; ?>" onChange="doHead<?php echo $this->__controlId; ?>(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
							    <option value="">-- Heading --</option>
							    <option value="Heading 1">H1</option>
							    <option value="Heading 2">H2</option>
							    <option value="Heading 3">H3</option>
							    <option value="Heading 4">H4</option>
							    <option value="Heading 5">H5</option>
							    <option value="Heading 6">H6</option>
							  </select>
							 <?php } ?>
							</td>
							<td class="tdClass" colspan="1" width="20%" align="right">
							</td>
						</tr>
						</table>
							</td>
						</tr>
						<tr>
							<td class="tdClass">
							<?php if($this->__showCustomInsertList == true && $this->__numCustomInserts > 0) { ?>
								<select id="selCustomInsert<?php echo $this->__controlId; ?>" style="width:100%" onChange="doCustomInsert<?php echo $this->__controlId; ?>(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
									<option value="">-- Custom Inserts --</option>
									<?php
									
										foreach($this->__customInserts as $ci)
										{
										?>
											<option value="<?php echo $ci["value"]; ?>"><?php echo $ci["name"]; ?></option>
										<?php
										}
									?>
								</select>
							<?php } ?>
							</td>
						</tr>
						</table>
						<iframe onBlur="doValueUpdate<?php echo $this->__controlId; ?>()" id="iView<?php echo $this->__controlId; ?>" style="width: <?php echo $this->__width; ?>; height: <?php echo $this->__height; ?>"></iframe>
						<input type="hidden" name="__data<?php echo $this->__controlId; ?>" value="">
						<input type="hidden" name="__control_<?php echo $this->__controlId; ?>" value="">
					</td>
				</tr>
			</table>

			<script language="JavaScript">

			  var viewMode<?php echo $this->__controlId; ?> = 1; // WYSIWYG

			  function Init<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.designMode = 'On';
                iHTML<?php echo $this->__controlId; ?> = "<?php echo str_replace(chr(13) . chr(10), "\\r\\n", str_replace("&quot;", "\\\"", str_replace("\"", "&quot;", $this->__value))); ?>";
                
                setTimeout("iView<?php echo $this->__controlId; ?>.document.body.innerHTML = iHTML<?php echo $this->__controlId; ?>;", 0);
                setTimeout("iView<?php echo $this->__controlId; ?>.focus();", 0);
			  }
			  
			  function selOn(ctrl)
			  {
				ctrl.style.borderColor = '#000000';
				ctrl.style.backgroundColor = '#B5BED6';
				ctrl.style.cursor = 'hand';	
			  }
			  
			  function selOff(ctrl)
			  {
				ctrl.style.borderColor = '#D6D3CE';  
				ctrl.style.backgroundColor = '#D6D3CE';
			  }
			  
			  function selDown(ctrl)
			  {
				ctrl.style.backgroundColor = '#8492B5';
			  }
			  
			  function selUp(ctrl)
			  {
			    ctrl.style.backgroundColor = '#B5BED6';
			  }
			    
			  function doBold<?php echo $this->__controlId; ?>()
			  {
				iView<?php echo $this->__controlId; ?>.document.execCommand('bold', false, null);
			  }

			  function doItalic<?php echo $this->__controlId; ?>()
			  {
				iView<?php echo $this->__controlId; ?>.document.execCommand('italic', false, null);
			  }

			  function doUnderline<?php echo $this->__controlId; ?>()
			  {
				iView<?php echo $this->__controlId; ?>.document.execCommand('underline', false, null);
			  }
			  
			  function doLeft<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('justifyleft', false, null);
			  }

			  function doCenter<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('justifycenter', false, null);
			  }

			  function doRight<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('justifyright', false, null);
			  }

			  function doOrdList<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('insertorderedlist', false, null);
			  }

			  function doBulList<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('insertunorderedlist', false, null);
			  }
			  
			  function doForeCol<?php echo $this->__controlId; ?>()
			  {
			    var left = (screen.availWidth/2) - (293/2);
			    var top = (screen.availHeight/2) - (100/2);

			    document.all.__data<?php echo $this->__controlId; ?>.value = 'foreColor';
			    var colorWin = window.open("colors.ewp.php?controlId=<?php echo $this->__controlId; ?>", 'colors', 'scrollbars=0, toolbar=0, statusbar=0, width=293, height=100, left='+left+', top='+top);
			  }

			  function doBackCol<?php echo $this->__controlId; ?>()
			  {
			    var left = (screen.availWidth/2) - (293/2);
			    var top = (screen.availHeight/2) - (100/2);

			    document.all.__data<?php echo $this->__controlId; ?>.value = 'backColor';
			    var colorWin = window.open('colors.ewp.php?controlId=<?php echo $this->__controlId; ?>', 'colors', 'scrollbars=0, toolbar=0, statusbar=0, width=293, height=100, left='+left+', top='+top);
			  }

			  function doImage<?php echo $this->__controlId; ?>()
			  {
			    var img = prompt('Please enter the full URL to the image:', 'http://');
				
				if(img != null && img != '')
				  iView<?php echo $this->__controlId; ?>.document.execCommand('insertimage', false, img);
			}

			  function doLink<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('createlink');
			  }
			  
			  function doRule<?php echo $this->__controlId; ?>()
			  {
			    iView<?php echo $this->__controlId; ?>.document.execCommand('inserthorizontalrule', false, null);
			  }
			  
			  function doFont<?php echo $this->__controlId; ?>(fName)
			  {
			    if(fName != '')
			      iView<?php echo $this->__controlId; ?>.document.execCommand('fontname', false, fName);
			  }
			  
			  function doSize<?php echo $this->__controlId; ?>(fSize)
			  {
			    if(fSize != '')
			      iView<?php echo $this->__controlId; ?>.document.execCommand('fontsize', false, fSize);
			  }
			  
			  function doHead<?php echo $this->__controlId; ?>(hType)
			  {
			    if(hType != '')
			    {
			      iView<?php echo $this->__controlId; ?>.document.execCommand('formatblock', false, hType);  
			    }
			  }
			  
			  function doCustomInsert<?php echo $this->__controlId; ?>(iValue)
			  {
				iView<?php echo $this->__controlId; ?>.focus();
				var sel = iView<?php echo $this->__controlId; ?>.document.selection;
				var theFrame = sel.createRange();
				theFrame.pasteHTML(iValue);
			  }
			  
			  function doTable<?php echo $this->__controlId; ?>()
			  {
			    var left = (screen.availWidth/2) - (450/2);
			    var top = (screen.availHeight/2) - (210/2);

			    var imageWin = window.open('table.ewp.php?controlId=<?php echo $this->__controlId; ?>', 'table', 'scrollbars=1, toolbar=0, statusbar=0, width=450, height=210, left='+left+', top='+top);
			  }
			  
			  function doToggleView<?php echo $this->__controlId; ?>()
			  {  
			    if(viewMode<?php echo $this->__controlId; ?> == 1)
			    { 
			      iHTML = iView<?php echo $this->__controlId; ?>.document.body.innerHTML;
			      iView<?php echo $this->__controlId; ?>.document.body.innerText = iHTML;
			      
			      // Hide all controls
			      <?php if($this->__showBold == true) { ?>
					document.all.boldBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showItal == true) { ?>
					document.all.italBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
			      
			      <?php if($this->__showUnde == true) { ?>
					document.all.undeBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
			      
			      <?php if($this->__showLeftAlign == true) { ?>
					document.all.leftBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showCenterAlign == true) { ?>
					document.all.centBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showRightAlign == true) { ?>
					document.all.rightBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showOrderedList == true) { ?>
					document.all.ordBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showBulletList == true) { ?>
					document.all.unordBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showTextColor == true) { ?>
					document.all.fcolBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showBackColor == true) { ?>
					document.all.bcolBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showLink == true) { ?>
					document.all.linkBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
			      <?php if($this->__showImage == true) { ?>
					document.all.imageBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
			      
			      <?php if($this->__showRule == true) { ?>
					document.all.ruleBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>

				  <?php if($this->__showTable == true) { ?>
					document.all.tableBut<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
			      
			      <?php if($this->__showFontList == true) { ?>
					document.all.selFont<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showSizeList == true) { ?>
					document.all.selSize<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
				  <?php if($this->__showHeadingList == true) { ?>
					document.all.selHeading<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>

				  <?php if($this->__showCustomInsertList == true && $this->__numCustomInserts > 0) { ?>
					document.all.selCustomInsert<?php echo $this->__controlId; ?>.style.display = 'none';
				  <?php } ?>
				  
			      iView<?php echo $this->__controlId; ?>.focus();
			      viewMode<?php echo $this->__controlId; ?> = 2; // Code
			    }
			    else
			    {
			      iText = iView<?php echo $this->__controlId; ?>.document.body.innerText;
			      iView<?php echo $this->__controlId; ?>.document.body.innerHTML = iText;
			      
			      // Show all controls
			      <?php if($this->__showBold == true) { ?>
					document.all.boldBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showItal == true) { ?>
					document.all.italBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
			      
			      <?php if($this->__showUnde == true) { ?>
					document.all.undeBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
			      
			      <?php if($this->__showLeftAlign == true) { ?>
					document.all.leftBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showCenterAlign == true) { ?>
					document.all.centBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showRightAlign == true) { ?>
					document.all.rightBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showOrderedList == true) { ?>
					document.all.ordBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showBulletList == true) { ?>
					document.all.unordBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showTextColor == true) { ?>
					document.all.fcolBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showBackColor == true) { ?>
					document.all.bcolBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showLink == true) { ?>
					document.all.linkBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
			      <?php if($this->__showImage == true) { ?>
					document.all.imageBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
			      
			      <?php if($this->__showRule == true) { ?>
					document.all.ruleBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>

				  <?php if($this->__showTable == true) { ?>
					document.all.tableBut<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>

			      <?php if($this->__showFontList == true) { ?>
					document.all.selFont<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showSizeList == true) { ?>
					document.all.selSize<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>
				  
				  <?php if($this->__showHeadingList == true) { ?>
					document.all.selHeading<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>

				  <?php if($this->__showCustomInsertList == true && $this->__numCustomInserts > 0) { ?>
					document.all.selCustomInsert<?php echo $this->__controlId; ?>.style.display = 'inline';
				  <?php } ?>

			      iView<?php echo $this->__controlId; ?>.focus();
			      viewMode<?php echo $this->__controlId; ?> = 1; // WYSIWYG
			    }
			  }
			  
			  function doValueUpdate<?php echo $this->__controlId; ?>()
			  {
				// This function updates the hidden form field value
				iHTML = iView<?php echo $this->__controlId; ?>.document.body.innerHTML;
				document.all.__control_<?php echo $this->__controlId; ?>.value = iHTML;
			  }
			  
			  // Setup the control
			  Init<?php echo $this->__controlId; ?>();

			</script>
		<?php
		}
		
		function SetValue($NewValue)
		{
			$this->__value = $NewValue;
		}

		function GetValue()
		{
			return str_replace("'", "\\'", @stripslashes($_POST["__control_" . $this->__controlId]));
		}
		
		function SetBorderWidth($BorderWidth)
		{
			$this->__borderWidth = $BorderWidth;
		}
		
		function SetBorderColor($BorderColor)
		{
			$this->__borderColor = $BorderColor;
		}
		
		function HideToggleButton()
		{
			$this->__showToggle = false;
		}
		
		function HideBoldButton()
		{
			$this->__showBold = false;
		}
		
		function HideItalicButton()
		{
			$this->__showItal = false;
		}
		
		function HideUnderlineButton()
		{
			$this->__showUnde = false;
		}
		
		function HideLeftAlignButton()
		{
			$this->__showLeftAlign = false;
		}

		function HideCenterAlignButton()
		{
			$this->__showCenterAlign = false;
		}

		function HideRightAlignButton()
		{
			$this->__showRightAlign = false;
		}
		
		function HideOrderedListButton()
		{
			$this->__showOrderedList = false;
		}
		
		function HideBulletListButton()
		{
			$this->__showBulletList = false;
		}
		
		function HideTextColorButton()
		{
			$this->__showTextColor = false;
		}
		
		function HideBackgroundColorButton()
		{
			$this->__showBackColor = false;
		}

		function HideLinkButton()
		{
			$this->__showLink = false;
		}

		function HideImageButton()
		{
			$this->__showImage = false;
		}

		function HideHorizontalRuleButton()
		{
			$this->__showHorizontalRule = false;
		}

		function HideTableButton()
		{
			$this->__showTable = false;
		}
		
		function HideFontList()
		{
			$this->__showFontList = false;
		}

		function HideSizeList()
		{
			$this->__showSizeList = false;
		}
		
		function HideHeadingList()
		{
			$this->__showHeadingList = false;
		}
		
		function HideCustomInsertList()
		{
			$this->__showCustomInsertList = false;
		}
		
		function AddCustomInsert($InsertName, $InsertHTMLCode)
		{
			// Increment the number of inserts
			$this->__numCustomInserts++;
			
			// Add the custom insert to the array
			$this->__customInserts[] = array("name" => $InsertName, "value" => $InsertHTMLCode);
		}
	}
?>
