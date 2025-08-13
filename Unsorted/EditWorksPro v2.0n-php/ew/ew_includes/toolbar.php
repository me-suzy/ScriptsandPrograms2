<table width="100%" cellspacing="0" cellpadding="0" class=toolbar>
	<tr>
	<td class="body" height="22">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="hide" align="center" id="toolbar_preview">
		<tr>
		  <td class="body" height="57">
		  &nbsp;&nbsp;&nbsp;<b>Preview Mode</b>
		  </td>
		 </tr>
	</table>
	 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="hide" align="center" id="toolbar_code">
		<tr>
		  <td class="body" height="22">
		  <table border="0" cellspacing="0" cellpadding="1">
			  <tr id=ew>
				<td>
				  <img border="0" src="ew/ew_images/button_cut.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='document.execCommand("Cut");foo.focus();' title="<?php echo sTxtCut; ?> (Ctrl+X)" class=toolbutton>
				</td>
				<td>
				  <img border="0" src="ew/ew_images/button_copy.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='document.execCommand("Copy");foo.focus();' title="<?php echo sTxtCopy; ?> (Ctrl+C)" class=toolbutton>
				</td>
				<td>
				  <img border="0" src="ew/ew_images/button_paste.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='document.execCommand("Paste");foo.focus();' title="<?php echo sTxtPaste; ?> (Ctrl+V)" class=toolbutton>
				</td>
				<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				<td>
				  <img border="0" src="ew/ew_images/button_undo.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Undo");' title="<?php echo sTxtUndo; ?> (Ctrl+Z)" class=toolbutton>
				</td>
				<td>
				  <img border="0" src="ew/ew_images/button_redo.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Redo");' title="<?php echo sTxtRedo; ?> (Ctrl+Y)" class=toolbutton>
				</td>
				</tr>
			</table>
		  </td>
		 </tr>
		 <tr>
		  <td class="body" bgcolor="#000000"><img src="ew/ew_images/1x1.gif" width="1" height="1"></td>
		</tr>
		 <tr><td height=29>&nbsp;</td></tr>
	</table>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bevel3" align="center" id="toolbar_full">
		<tr>
		  <td class="body" height="22">
			<table border="0" cellspacing="0" cellpadding="1">
			  <tr id=ew>
					<td>
						<img border="0" src="ew/ew_images/button_cut.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Cut");foo.focus();' title="<?php echo sTxtCut; ?> (Ctrl+X)" class=toolbutton>
					</td>
					<td>
						<img border="0" src="ew/ew_images/button_copy.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Copy");foo.focus();' title="<?php echo sTxtCopy; ?> (Ctrl+C)" class=toolbutton>
					</td>
					<td>
						<img border="0" src="ew/ew_images/button_paste.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Paste");foo.focus();' title="<?php echo sTxtPaste; ?> (Ctrl+V)" class=toolbutton>
					</td>
					<td>
						<img src="ew/ew_images/seperator.gif" width="2" height="20">
					</td>
					<td>
						<img border="0" src="ew/ew_images/button_undo.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Undo");' title="<?php echo sTxtUndo; ?> (Ctrl+Z)" class=toolbutton>
					</td>
					<td>
						<img border="0" src="ew/ew_images/button_redo.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Redo");' title="<?php echo sTxtRedo; ?> (Ctrl+Y)" class=toolbutton>
					</td>
					<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				  <?php if($this->__hideBold != true) { ?>
					<td>
						<img id=bold border="0" src="ew/ew_images/button_bold.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Bold");foo.focus();' title="<?php echo sTxtBold; ?> (Ctrl+B)" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideUnderline != true) { ?>
					<td>
						<img id=underline border="0" src="ew/ew_images/button_underline.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Underline");foo.focus();' title="<?php echo sTxtUnderline; ?> (Ctrl+U)" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideItalic != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_italic.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Italic");foo.focus();' title="<?php echo sTxtItalic; ?> (Ctrl+I)" class=toolbutton>
					</td>
					<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				  <?php } ?>
				  <?php if($this->__hideNumberList != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_numbers.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("InsertOrderedList");foo.focus();' title="<?php echo sTxtNumList; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideBulletList != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_bullets.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("InsertUnorderedList");foo.focus();' title="<?php echo sTxtBulletList; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideDecreaseIndent != true) { ?>
					<td>
					<img border="0" src="ew/ew_images/button_decrease_indent.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Outdent");foo.focus();' title="<?php echo sTxtDecreaseIndent; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideIncreaseIndent != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_increase_indent.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("Indent");foo.focus();' title="<?php echo sTxtIncreaseIndent; ?>" class=toolbutton>
					</td>
					<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				  <?php } ?>
				  <?php if($this->__hideLeftAlign != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_align_left.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("JustifyLeft");foo.focus();' title="<?php echo sTxtAlignLeft; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideCenterAlign != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_align_center.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("JustifyCenter");foo.focus();' title="<?php echo sTxtAlignCenter; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideRightAlign != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_align_right.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("JustifyRight");foo.focus();' title="<?php echo sTxtAlignRight; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideJustify != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_align_justify.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("JustifyFull");foo.focus();' title="<?php echo sTxtAlignJustify; ?>" class=toolbutton>
					</td>
					<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				  <?php } ?>
				  <?php if($this->__hideHorizontalRule != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_hr.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doCommand("InsertHorizontalRule");foo.focus();' title="<?php echo sTxtInsertHR; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideLink != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_link.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doLink()' title="<?php echo sTxtHyperLink; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideAnchor != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_anchor.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doAnchor()' title="<?php echo sTxtAnchor; ?>" class=toolbutton>
					</td>
				  <?php } ?>
				  <?php if($this->__hideMailLink != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_email.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doEmail()' title="<?php echo sTxtEmail; ?>" class=toolbutton>
					</td>
					<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				  <?php } ?>
				  <?php if($this->__hideHelp != true) { ?>
					<td>
						<img border="0" src="ew/ew_images/button_help.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick='doHelp()' title="<?php echo sTxtHelp; ?>" class=toolbutton>
					</td>
				  <?php } ?>
			  </tr>
	  		</table>
		  </td>
		</tr>
			<tr>
				<td class="body" bgcolor="#000000"><img src="ew/ew_images/1x1.gif" width="1" height="1"></td>
			</tr>
		<?php if($this->__numBottomHidden < 13) { ?>
		<tr>
		  <td class="body">
			<table border="0" cellspacing="1" cellpadding="1">
			  <tr id=ew>
				<?php if($this->__hideFont != true) { ?>
				<td>
				  <select onChange="(isAllowed()) ? foo.document.execCommand('FontName',false,this[this.selectedIndex].value) :foo.focus();foo.focus();this.selectedIndex=0" class="Text70" unselectable="on" id=select1 name=select1>
			  		<option selected><?php echo sTxtFont?></option>
			  		<option value="Times New Roman">Default</option>
			  		<option value="Arial">Arial</option>
			  		<option value="Verdana">Verdana</option>
			  		<option value="Tahoma">Tahoma</option>
			  		<option value="Courier New">Courier New</option>
			  		<option value="Georgia">Georgia</option>
				  </select>
				</td>
				<?php } ?>
				<?php if($this->__hideSize != true) { ?>
				<td>
				  <select onChange="(isAllowed()) ? foo.document.execCommand('FontSize',true,this[this.selectedIndex].value) :foo.focus();foo.focus();this.selectedIndex=0" class=Text45 unselectable="on" id=select2 name=select2>
			  		<option SELECTED><?php echo sTxtSize?>
			  		<option value="1">1
			  		<option value="2">2
			  		<option value="3">3
			  		<option value="4">4
			  		<option value="5">5
			  		<option value="6">6
			  		<option value="7">7
	  			  </select>
				</td>
				<?php } ?>
				<?php if($this->__hideFormat != true) { ?>
				<td>
				  <select onChange="(isAllowed()) ? doFormat(this[this.selectedIndex].value) : foo.focus();foo.focus();this.selectedIndex=0" class="Text70" unselectable="on" id=select3 name=select3>
				    <option selected><?php echo sTxtFormat?>
				    <option value="<P>">Normal
					<option value="SuperScript">SuperScript
					<option value="SubScript">SubScript
				    <option value="<H1>">H1
				    <option value="<H2>">H2
				    <option value="<H3>">H3
				    <option value="<H4>">H4
				    <option value="<H5>">H5
				    <option value="<H6>">H6
				  </select>
				</td>
				<?php } ?>
				<?php if($this->__hideStyle != true) { ?>
				<td>
				  <select id=sStyles onChange="applyStyle(this[this.selectedIndex].value);foo.focus();this.selectedIndex=0;" class="Text90" unselectable="on">
				    <option selected><?php echo sTxtStyle?></option>
				    <option value="">None</option>
				  </select>
				</td>
				<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				<?php } ?>
				<?php if($this->__hideForeColor != true) { ?>
				<td>
				  <img border="0" src="ew/ew_images/button_font_color.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick="(isAllowed()) ? showMenu('colorMenu',180,291) : foo.focus()" class=toolbutton title="<?php echo sTxtColour; ?>">
				</td>
				<?php } ?>
				<?php if($this->__hideBackColor != true) { ?>
				<td>
				  <img border="0" src="ew/ew_images/button_highlight.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick="(isAllowed()) ? showMenu('colorMenu2',180,291) : foo.focus()" class=toolbutton title="<?php echo sTxtBackColour; ?>">
				</td>
				<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				<?php } ?>
				<?php if($this->__hideTable != true) { ?>
				<td>
				  <img border="0" src="ew/ew_images/button_table_down.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick="(isAllowed()) ? showMenu('tableMenu',160,284) : foo.focus()" class=toolbutton title="<?php echo sTxtTableFunctions; ?>">
				</td>
				<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				<?php } ?>
				<?php if($this->__hideForm != true) { ?>
				<td>
				  <img class=toolbutton onMouseDown=button_down(this); onMouseOver=button_over(this); onClick="(isAllowed()) ? showMenu('formMenu',180,189) : foo.focus()" onMouseOut=button_out(this); type=image width="21" height="20" src="ew/ew_images/button_form_down.gif" border=0 title="<?php echo sTxtFormFunctions; ?>">
				</td>
				<td><img src="ew/ew_images/seperator.gif" width="2" height="20"></td>
				<?php } ?>
				<?php if($this->__hideImage != true) { ?>
				<td>
				  <img border="0" src="ew/ew_images/button_image.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick="doImage()" class=toolbutton title="<?php echo sTxtImage; ?>">
				</td>
				<?php } ?>
				<?php if($this->__hideSymbols != true) { ?>
				<td>
				  <img border="0" src="ew/ew_images/button_chars.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick="(isAllowed()) ? showMenu('charMenu',104,111) : foo.focus()" class=toolbutton title="<?php echo sTxtChars; ?>">
				</td>
				<?php } ?>
				<?php if($this->__hideProps != true) { ?>
				<td>
				  <img border="0" src="ew/ew_images/button_properties.gif" width="21" height="20" onmouseover="button_over(this);" onmouseout="button_out(this);" onmousedown="button_down(this);" onClick="ModifyProperties()" class=toolbutton title="<?php echo sTxtPageProperties; ?>">
				</td>
				<?php } ?>
				<?php if($this->__hideWord != true) { ?>
				<td>				
				  <img class=toolbutton onmousedown=button_down(this); onmouseover=button_over(this); onClick=cleanCode() onmouseout=button_out(this); type=image width="21" height="20" src="ew/ew_images/button_clean_code.gif" border=0 title="<?php echo sTxtCleanCode; ?>">
				</td>
				<?php } ?>
				<?php if($this->__hideGuidelines != true) { ?>
				<td>
				  <img class=toolbutton onMouseDown=button_down(this); onMouseOver=button_over(this); onClick=toggleBorders() onMouseOut=button_out(this); type=image width="21" height="20" src="ew/ew_images/button_show_borders.gif" border=0 title="<?php echo sTxtToggleGuidelines; ?>" id=guidelines>
				</td>
				<?php } ?>
			  </tr>
			</table>
		  </td>
		</tr>
		<?php } else { ?>
			<tr><td><img src="ew/ew_images/1x1.gif" width="1" height="29"></td></tr>
		<?php } ?>
	  </table>
	</td>
  </tr> 
</table>
<!-- table menu -->

<DIV ID="tableMenu" STYLE="display:none">
<table border="0" cellspacing="0" cellpadding="0" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: buttonshadow 2px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: buttonshadow 1px solid;" bgcolor="threedface">
  <tr onClick="parent.ShowInsertTable()" title="<?php echo sTxtTable?>"> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);"> 
      <img id=insertTable1 border="0" src="ew/ew_images/button_table.gif" width="21" height="20" align="absmiddle">&nbsp;<?php echo sTxtTable?>...&nbsp; </td>
  </tr>
  <tr onClick=parent.ModifyTable(); title="<?php echo sTxtTableModify?>"> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=modifyTable> 
	  <img id=modifyTable2 width="21" height="20" src="ew/ew_images/button_modify_table.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtTableModify?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtCellModify?>" onClick=parent.ModifyCell()> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=modifyCell> 
	<img id=modifyCell2 width="21" height="20" src="ew/ew_images/button_modify_cell.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtCellModify?>...&nbsp; </td>
  </tr>
  <tr height=10> 
    <td align=center><img src="ew/ew_images/vertical_spacer.gif" width="140" height="2"></td>
  </tr>
  <tr title="<?php echo sTxtInsertColA?>" onClick=parent.InsertColAfter()>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=colAfter> 
      <img id=colAfter2 width="21" height="20" src="ew/ew_images/button_insert_col_after.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtInsertColA?>&nbsp;
    </td>
  </tr>
  <tr title="<?php echo sTxtInsertColB?>" onClick=parent.InsertColBefore()>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=colBefore> 
      <img id=colBefore2 width="21" height="20" src="ew/ew_images/button_insert_col_before.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtInsertColB?>&nbsp;
    </td>
  </tr>
  <tr height=10> 
    <td align=center><img src="ew/ew_images/vertical_spacer.gif" width="140" height="2"></td>
  </tr>
  <tr title="<?php echo sTxtInsertRowA?>" onClick=parent.InsertRowAbove()>
	<td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=rowAbove> 
      <img id=rowAbove2 width="21" height="20" src="ew/ew_images/button_insert_row_above.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtInsertRowA?>&nbsp;
    </td>
  </tr>
  <tr title="<?php echo sTxtInsertRowB?>" onClick=parent.InsertRowBelow() >
	<td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=rowBelow> 
      <img id=rowBelow2 width="21" height="20" src="ew/ew_images/button_insert_row_below.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtInsertRowB?>&nbsp;
    </td>
  </tr>
  <tr height=10> 
    <td align=center><img src="ew/ew_images/vertical_spacer.gif" width="140" height="2"></td>
  </tr>
  <tr title="<?php echo sTxtDeleteRow?>" onClick=parent.DeleteRow()>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=deleteRow>
      <img id=deleteRow2 width="21" height="20" src="ew/ew_images/button_delete_row.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtDeleteRow?>&nbsp;
    </td>
  </tr>
  <tr title="<?php echo sTxtDeleteCol?>" onClick=parent.DeleteCol()>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=deleteCol>
      <img id=deleteCol2 width="21" height="20" src="ew/ew_images/button_delete_col.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtDeleteCol?>&nbsp;
    </td>
  </tr>
  <tr height=10> 
    <td align=center><img src="ew/ew_images/vertical_spacer.gif" width="140" height="2" tabindex=1 HIDEFOCUS></td>
  </tr>
  <tr title="<?php echo sTxtIncreaseColSpan?>" onClick=parent.IncreaseColspan()>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=increaseSpan>
      <img id=increaseSpan2 width="21" height="20" src="ew/ew_images/button_increase_colspan.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtIncreaseColSpan?>&nbsp;
    </td>
  </tr>
  <tr title="<?php echo sTxtDecreaseColSpan?>" onClick=parent.DecreaseColspan()>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id=decreaseSpan>
      <img id=decreaseSpan2 width="21" height="20" src="ew/ew_images/button_decrease_colspan.gif" border=0 align=absmiddle>&nbsp;<?php echo sTxtDecreaseColSpan?>&nbsp;
    </td>
  </tr>
</table>
</div>
<!-- end table menu -->

<!-- form menu -->
<DIV ID="formMenu" STYLE="display:none;">
<table border="0" cellspacing="0" cellpadding="0" width=180 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: buttonshadow 2px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: buttonshadow 1px solid;" bgcolor="threedface">
  <tr title="<?php echo sTxtForm; ?>" onClick=parent.insertForm()> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img width="21" height="20" src="ew/ew_images/button_form.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtForm?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtFormModify; ?>" onClick=parent.modifyForm()> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);" id="modifyForm1">
      <img id="modifyForm2" width="21" height="20" src="ew/ew_images/button_modify_form.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtFormModify?>...&nbsp;</td>
  </tr>
  <tr height=10> 
    <td align=center><img src="ew/ew_images/vertical_spacer.gif" width="140" height="2" tabindex=1 HIDEFOCUS></td>
  </tr>
  <tr title="<?php echo sTxtTextField; ?>" onClick=parent.doTextField()> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img width="21" height="20" src="ew/ew_images/button_textfield.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtTextField?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtTextArea; ?>" onClick=parent.doTextArea() >
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img type=image width="21" height="20" src="ew/ew_images/button_textarea.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtTextArea?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtHidden; ?>" onClick=parent.doHidden();>
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img width="21" height="20" src="ew/ew_images/button_hidden.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtHidden?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtButton; ?>" onClick=parent.doButton();> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img width="21" height="20" src="ew/ew_images/button_button.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtButton?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtCheckbox; ?>" onClick=parent.doCheckbox();> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img width="21" height="20" src="ew/ew_images/button_checkbox.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtCheckbox?>...&nbsp;</td>
  </tr>
  <tr title="<?php echo sTxtRadioButton; ?>" onClick=parent.doRadio();> 
    <td style="cursor: hand; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.button_over(this);" onMouseOut="parent.button_out(this);" onMouseDown="parent.button_down(this);">
      <img width="21" height="20" src="ew/ew_images/button_radio.gif" border=0 align="absmiddle">&nbsp;<?php echo sTxtRadioButton?>...&nbsp;</td>
  </tr>
</table>
</div>
<!-- formMenu -->
<DIV ID="colorMenu" STYLE="display:none;">
<table cellpadding="1" cellspacing="5" border="1" bordercolor="#666666" style="cursor: hand;font-family: Verdana; font-size: 7px; BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: buttonshadow 2px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: buttonshadow 1px solid;" bgcolor="threedface">
  <tr>
	<td colspan="10" id=color style="height=20px;font-family: verdana; font-size:12px;">&nbsp;</td>
  </tr>
  <tr>
    <td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF0000;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFF00;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00FF00;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00FFFF;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#0000FF;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF00FF;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFFFF;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F5F5F5;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DCDCDC;width=12px">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFAFA;width=12px">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#D3D3D3">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#C0C0C0">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#A9A9A9">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#808080">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#696969">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#000000">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#2F4F4F">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#708090">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#778899">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#4682B4">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#4169E1">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#6495ED">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#B0C4DE">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#7B68EE">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#6A5ACD">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#483D8B">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#191970">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#000080">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00008B">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#0000CD">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#1E90FF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00BFFF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#87CEFA">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#87CEEB">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#ADD8E6">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#B0E0E6">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F0FFFF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#E0FFFF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#AFEEEE">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00CED1">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#5F9EA0">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#48D1CC">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00FFFF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#40E0D0">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#20B2AA">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#008B8B">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#008080">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#7FFFD4">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#66CDAA">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#8FBC8F">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#3CB371">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#2E8B57">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#006400">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#008000">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#228B22">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#32CD32">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00FF00">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#7FFF00">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#7CFC00">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#ADFF2F">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#98FB98">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#90EE90">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00FF7F">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#00FA9A">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#556B2F">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#6B8E23">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#808000">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#BDB76B">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#B8860B">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DAA520">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFD700">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F0E68C">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#EEE8AA">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFEBCD">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFE4B5">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F5DEB3">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFDEAD">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DEB887">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#D2B48C">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#BC8F8F">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#A0522D">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#8B4513">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#D2691E">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#CD853F">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F4A460">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#8B0000">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#800000">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#A52A2A">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#B22222">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#CD5C5C">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F08080">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FA8072">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#E9967A">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFA07A">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF7F50">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF6347">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF8C00">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFA500">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF4500">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DC143C">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF0000">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF1493">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF00FF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FF69B4">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFB6C1">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFC0CB">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DB7093">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#C71585">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#800080">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#8B008B">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#9370DB">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#8A2BE2">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#4B0082">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#9400D3">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#9932CC">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#BA55D3">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DA70D6">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#EE82EE">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#DDA0DD">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#D8BFD8">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#E6E6FA">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F8F8FF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F0F8FF">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F5FFFA">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#F0FFF0">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FAFAD2">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFACD">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFF8DC">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFFE0">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFFF0">&nbsp;</td>
  </tr>
  <tr>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFFAF0">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FAF0E6">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FDF5E6">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FAEBD7">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFE4C4">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFDAB9">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFEFD5">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFF5EE">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFF0F5">&nbsp;</td>
	<td onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)" style="background-color:#FFE4E1">&nbsp;</td>
  </tr>
  <tr>
	<td colspan="10" style="height=15px;font-family: verdana; font-size:10px;" onMouseOver="parent.showColor(color,this)" onClick="parent.doColor(color)">&nbsp;None</td>
  </tr>
</table>
</DIV>
<!-- end color menu -->
<!-- Special Char Menu -->
<DIV ID="charMenu" STYLE="display:none;">
<table cellpadding="1" cellspacing="5" border="1" bordercolor="#666666" style="cursor: hand;font-family: Verdana; font-size: 14px; font-weight: bold; BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: buttonshadow 2px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: buttonshadow 1px solid;" bgcolor="threedface">
  <tr> 
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&copy;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&reg;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&#153;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&pound;</td>
  </tr>
  <tr> 
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&#151;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&#133;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&divide;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&aacute;</td>
  </tr>
  <tr> 
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&yen;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&euro;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&#147;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&#148;</td>
  </tr>
  <tr> 
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&#149;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&para;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&eacute;</td>
    <td style="width=15px; cursor: hand;" onClick="parent.insertChar(this)" onMouseOver="parent.button_over(this);" onMouseOut="parent.char_out(this);" onMouseDown="parent.button_down(this);">&uacute;</td>
  </tr>
</table>
</DIV>
<!-- end char menu -->

<DIV ID="contextMenu" style="display:none;">
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.document.execCommand("Cut");parent.oPopup2.hide()'>
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtCut; ?>&nbsp;</td>
  </tr>
  <tr onClick ='parent.document.execCommand("Copy");parent.oPopup2.hide()'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtCopy; ?>&nbsp;</td>
  </tr>
  <tr onClick ='parent.document.execCommand("Paste");parent.oPopup2.hide()'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtPaste; ?>&nbsp;</td>
  </tr>
</table>
</div>

<DIV ID="cmTableMenu" style="display:none">
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.ModifyTable();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtTableModify; ?>...&nbsp;</td>
  </tr>
</table>
</DIV>

<DIV ID="cmTableFunctions" style="display:none">
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.ModifyCell();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtCellModify; ?>...&nbsp;</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.InsertColBefore(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtInsertColB; ?>&nbsp;</td>
  </tr>
  <tr onClick ='parent.InsertColAfter(); parent.oPopup2.hide();'> 
   <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtInsertColA; ?>&nbsp;</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.InsertRowAbove(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtInsertRowA; ?>&nbsp;</td>
  </tr>
  <tr onClick ='parent.InsertRowBelow(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtInsertRowB; ?>&nbsp;</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.DeleteRow(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtDeleteRow; ?>&nbsp;</td>
  </tr>
  <tr onClick ='parent.DeleteCol(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtDeleteCol; ?>&nbsp;</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.IncreaseColspan(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtIncreaseColSpan; ?>&nbsp;</td>
  </tr>
  <tr onClick ='parent.DecreaseColspan(); parent.oPopup2.hide();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp<?php echo sTxtDecreaseColSpan; ?>&nbsp;</td>
  </tr>
</table>
</DIV>

<DIV ID="cmImageMenu" style="display:none">
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.doImage();'> 
    <td style="cursor:default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtModifyImage; ?>...&nbsp;</td>
  </tr>
</table>
</DIV>

<DIV ID="cmLinkMenu" style="display:none">
<table border="0" cellspacing="0" cellpadding="3" width=160 style="BORDER-LEFT: buttonhighlight 1px solid; BORDER-RIGHT: #808080 1px solid; BORDER-TOP: buttonhighlight 1px solid; BORDER-BOTTOM: #808080 1px solid;" bgcolor="threedface">
  <tr onClick ='parent.doLink();'> 
    <td style="cursor: default; font:8pt tahoma; BORDER-LEFT: threedface 1px solid; BORDER-RIGHT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-BOTTOM: threedface 1px solid;" onMouseOver="parent.contextHilite(this);" onMouseOut="parent.contextDelite(this);">
      &nbsp&nbsp;&nbsp;&nbsp&nbsp;<?php echo sTxtHyperLink; ?>...&nbsp;</td>
  </tr>
</table>
</DIV>