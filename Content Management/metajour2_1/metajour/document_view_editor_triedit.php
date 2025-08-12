<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

	$this->context->setloosedtd(false);
	$header = <<<EOH
		<link REL="stylesheet" TYPE="text/css" HREF="editor/toolbars.css">
		<script LANGUAGE="JavaScript" SRC="editor/dhtmled.js"></script>
		<script LANGUAGE="JavaScript" SRC="editor/editor.js"></script>
		<script LANGUAGE="javascript" FOR="tbContentElement" EVENT="DisplayChanged">
		<!--
		return tbContentElement_DisplayChanged()
		//-->
		</script>

		<script LANGUAGE="javascript" FOR="tbContentElement" EVENT="ShowContextMenu">
		<!--
		return tbContentElement_ShowContextMenu()
		//-->
		</script>

		<script LANGUAGE="javascript" FOR="tbContentElement" EVENT="ContextMenuAction(itemIndex)">
		<!--
		return tbContentElement_ContextMenuAction(itemIndex)
		//-->
		</script>

		<SCRIPT LANGUAGE="javascript" FOR="tbContentElement" EVENT="DocumentComplete">
		<!--
		 tbContentElement_DocumentComplete()
		//-->
		</SCRIPT>
		<style type="text/css">
			#loading {
				background-color: #F1F4FF;
				position: absolute;
				top: 45%;
				left: 75px;
				font-family: arial, helvetica, sans-serif;
				font-size: 50px;
				border: 1px solid black;
				width: 800px;
				heigt: 100px;
				text-align: center;
			}
			#content { position: absolute; visibility: hidden}
		</style>
EOH;

	$this->context->addheader($header);
	$this->context->addonload('window_onload');
	?>
	<div id="loading">Loading, please wait ...</div>
	<div id="content">
	<?php
	$stack = $this->userhandler->getObjectIdStack();
	if (!empty($stack)) $this->objectid = $stack;
	
	$obj = owRead($this->objectid[0]);
	if (!$obj && isset($this->data['_parentid'])) { 
		// this can occur, if we have just deleted the section
		// in this case, we just read the document (as set by _parentid)
		// this will be handled in the next if-statement, as if we
		// had just loaded the document from the document-list
		$obj = owRead($this->data['_parentid']);
		// we know what happened, so we remove the error from the
		// errorhandler
		$this->errorhandler->removeLastError();
	}
	if ($obj->gettype() == 'document') {
		/* if current document has a future revision
			and we're not opening in locked mode, load the future revision 
			instead of the original document */
		if ($obj->hasFutureRevision() && !isset($_REQUEST['locked']) 
			&& $this->userhandler->getRevisionControl()) {
			$fr = $obj->getFutureRevisions();
			if (!empty($fr)) {
				$obj = owRead($fr[0]);
			}
		}
		$l = $obj->getchilds();
		$obj = owRead($l[0]);
		$this->objectid[0] = $l[0];
	}
	
	if ($obj->isVariant()) {
		# if the document section is a variant, the parentid is not set.
		# In this case we load the master object, and read the parentid
		$mobj = owRead($obj->getVariantOf());
		$parentid = $mobj->getParentId();
		$variant = true;
	} else {
		$parentid = $obj->getparentid();
		$variant = false;
	}
	$extension = $obj->elements[0]['extension'];
	$configset = $obj->elements[0]['configset'];
	$name = $obj->elements[0]['name'];
	$subname = $obj->elements[0]['subname'];
	$params = $obj->elements[0]['params'];
	$script = $obj->elements[0]['script'];
	$document = owRead($parentid);
	$approvestatus = -1;
	if ($document && $this->userhandler->getRevisionControl()) {
		$approvestatus = $document->elements[0]['object']['approved'];
	}

	$header = '	
	<SCRIPT LANGUAGE="javascript">
	function SECTION_PROPERTIES_onclick() {
		'.$this->ModalWindowLarge('documentsection',$this->objectid[0],'','combidialog','jswindowclose').'
	}

	function VARIANT_NEW_onclick() {
		'.$this->ModelessDialog('',$this->objectid[0],'','createvariant','jsopenerlogformsubmit,jswindowclose').'
	}
	
	function DECMD_IMAGE_onclick() {
		 var src = '.$this->ListDialog('binfile','','','splitdialog').';
		 if (src) {
			  tbContentElement.ExecCommand(DECMD_IMAGE, OLECMDEXECOPT_DONTPROMPTUSER, \'getfile.php?objectid=\'+src.id);
			  var image = tbContentElement.DOM.selection.createRange().commonParentElement();
			  image.border = 0;
		 }
		 tbContentElement.focus();
	}
	';

	$header .= "
	
	function DECMD_HYPERLINK_onclick() {
		var url = '';
		var target = '';
		
		
		if (tbContentElement.DOM.selection.type == 'Control') { 
			var link = tbContentElement.DOM.selection.createRange().commonParentElement().parentElement;
		} else {
			var selection = tbContentElement.DOM.selection.createRange();
			selection.collapse(true);
			selection.moveStart('character')
			var link = selection.parentElement();
		}
		// Set this in global scope, so we can reach it from our popup-window
		args = new Object();
		if (link) {
			args['url'] = link.href;
			args['anchors'] = tbContentElement.DOM.anchors;
			args['target'] = link.target;
		}
		var result = ".$this->ModalDialog('',$this->objectid[0],'','insertlink','jswindowclose', '', '', 450, 160).";
		if (result) {
			tbContentElement.ExecCommand(DECMD_HYPERLINK,OLECMDEXECOPT_DONTPROMPTUSER, result['url']);
			var link;
			if (tbContentElement.DOM.selection.type == 'Control') {
				link = tbContentElement.DOM.selection.createRange().commonParentElement().parentElement;
			} else {
				var selection = tbContentElement.DOM.selection.createRange();
				selection.collapse();
				selection.moveStart('character')
				link = selection.parentElement();
			}
			link.target = result['target'];
		}
	}
	";

	$header .= 	'</SCRIPT>';
	
	$this->context->addheader($header);


	?>

	<SCRIPT LANGUAGE="javascript">
	document.title = '<?php echo $name . " [" . $document->elements[0]["name"] . "]" ?>';
	</SCRIPT>
	<form name="logform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="cmd" value="update">
	<input type="hidden" name="view" value="editor">
	<input type="hidden" name="nextparam" value="">
	<INPUT type="hidden" name="objectid" value="<?php echo $this->objectid[0] ?>">
	<INPUT type="hidden" name="_parentid" value="<?php echo $parentid ?>">
	<INPUT type="hidden" name="content" value="">
<?php
/**
	<INPUT type="hidden" name="extension" value="<?php echo $extension; ?>">
	<INPUT type="hidden" name="configset" value="<?php echo $configset; ?>">
	<INPUT type="hidden" name="name" value="<?php echo $name; ?>">
	<INPUT type="hidden" name="subname" value="<?php echo $subname; ?>">
	<INPUT type="hidden" name="params" value="<?php echo $params; ?>">
	<INPUT type="hidden" name="script" value="<?php echo $script; ?>">
	**/
	?>
	<INPUT type="hidden" name="locked" value="<?php echo @$_REQUEST['locked']; ?>">
	</form>
		<object ID="tmpContent" style="width: 1px; height: 1px" CLASS="tbContentElement" CLASSID="clsid:2D360200-FFF5-11D1-8D03-00A0C959BC0A" VIEWASTEXT>
	  <param name=Scrollbars value=true>
	</object>

	<!-- Toolbars -->
	<div class="tbToolbar" ID="MenuBar">
	  <div class="tbMenu" ID="FILE">
	    <?php echo $this->gl('menu_file'); ?>
	    <div class="tbMenuItem" ID="FILE_SAVE" LANGUAGE="javascript" onclick="return MENU_FILE_SAVE_onclick()">
	    <?php echo $this->gl('menu_filesave'); ?>
	      <img class="tbIcon" src="editor/images/save.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="DOCUMENTSECTION_PROPERTIES" LANGUAGE="javascript" onclick="return SECTION_PROPERTIES_onclick()">
	    <?php echo $this->gl('menu_documentsection_properties'); ?>
	    </div>
		<div class="tbMenuItem" ID="DOCUMENT_PROPERTIES" LANGUAGE="javascript" onclick="return <?php echo $this->ModalWindowLarge('document',$document->elements[0]['objectid'],'','combidialog','jswindowclose'); ?>">
	    <?php echo $this->gl('menu_document_properties'); ?>
		</div>
	  </div>

	  <div class="tbMenu" ID="EDIT" LANGUAGE="javascript" tbOnMenuShow="return OnMenuShow(QueryStatusEditMenu, EDIT)">
	    <?php echo $this->gl('menu_edit'); ?>
	    <div class="tbMenuItem" ID="EDIT_UNDO" LANGUAGE="javascript" onclick="return DECMD_UNDO_onclick()">
	    <?php echo $this->gl('menu_undo'); ?>
	      <img class="tbIcon" src="editor/images/undo.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="EDIT_REDO" LANGUAGE="javascript" onclick="return DECMD_REDO_onclick()">
	    <?php echo $this->gl('menu_redo'); ?>
	      <img class="tbIcon" src="editor/images/redo.gif" WIDTH="23" HEIGHT="22">
	    </div>

	    <div class="tbSeparator"></div>

	    <div class="tbMenuItem" ID="EDIT_CUT" LANGUAGE="javascript" onclick="return DECMD_CUT_onclick()">
	    <?php echo $this->gl('menu_cut'); ?>
	      <img class="tbIcon" src="editor/images/cut.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="EDIT_COPY" LANGUAGE="javascript" onclick="return DECMD_COPY_onclick()">
	    <?php echo $this->gl('menu_copy'); ?>
	      <img class="tbIcon" src="editor/images/copy.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="EDIT_PASTE" LANGUAGE="javascript" onclick="return DECMD_PASTE_onclick()">
	    <?php echo $this->gl('menu_paste'); ?>
	      <img class="tbIcon" src="editor/images/paste.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="EDIT_DELETE" LANGUAGE="javascript" onclick="return DECMD_DELETE_onclick()">
	    <?php echo $this->gl('menu_delete'); ?>
	      <img class="tbIcon" src="editor/images/delete.gif" WIDTH="23" HEIGHT="22">
	    </div>

	    <div class="tbSeparator"></div>

	    <div class="tbMenuItem" ID="EDIT_SELECTALL" LANGUAGE="javascript" onclick="return DECMD_SELECTALL_onclick()">
	    <?php echo $this->gl('menu_selectall'); ?>
	    </div>

	    <div class="tbSeparator"></div>

	    <div class="tbMenuItem" ID="EDIT_FINDTEXT" TITLE="Find" LANGUAGE="javascript" onclick="return DECMD_FINDTEXT_onclick()">
	    <?php echo $this->gl('menu_findtext'); ?>
	      <img class="tbIcon" src="editor/images/find.gif" WIDTH="23" HEIGHT="22">
	    </div>
	  </div>

	  <div class="tbMenu" ID="VIEW">
	    <?php echo $this->gl('menu_view'); ?>
	    <div class="tbSubmenu" TBTYPE="toggle" ID="VIEW_TOOLBARS">
	    <?php echo $this->gl('menu_toolbar'); ?>
	      <div class="tbMenuItem" id="ToolbarMenuStd" TBTYPE="toggle" TBSTATE="checked" ID="TOOLBARS_STANDARD" LANGUAGE="javascript" onclick="return TOOLBARS_onclick(StandardToolbar, ToolbarMenuStd)">
	    <?php echo $this->gl('menu_tbstd'); ?>
	      </div>
	      <div class="tbMenuItem" id="ToolbarMenuFmt" TBTYPE="toggle" TBSTATE="checked" ID="TOOLBARS_FORMAT" LANGUAGE="javascript" onclick="return TOOLBARS_onclick(FormatToolbar, ToolbarMenuFmt)">
	    <?php echo $this->gl('menu_tbfmt'); ?>
	      </div>
	      <div class="tbMenuItem" id="ToolbarMenuAbs" TBTYPE="toggle" TBSTATE="checked" ID="TOOLBARS_ZORDER" LANGUAGE="javascript" onclick="return TOOLBARS_onclick(AbsolutePositioningToolbar, ToolbarMenuAbs)">
	    <?php echo $this->gl('menu_tbabs'); ?>
	      </div>
	      <div class="tbMenuItem" id="ToolbarMenuTable" TBTYPE="toggle" TBSTATE="checked" ID="TOOLBARS_TABLE" LANGUAGE="javascript" onclick="return TOOLBARS_onclick(TableToolbar, ToolbarMenuTable)">
	    <?php echo $this->gl('menu_tbtable'); ?>
	      </div>
	      <div class="tbMenuItem" id="ToolbarMenuAdvanced" TBTYPE="toggle" TBSTATE="checked" ID="TOOLBAR_ADVANCED" LANGUAGE="javascript" onclick="return TOOLBARS_onclick(AdvancedToolbar, ToolbarMenuAdvanced)">
	    <?php echo $this->gl('menu_tbadvanced'); ?>
	      </div>
	  	<div class="tbMenuItem" id="ToolbarMenuSections" TBTYPE="toggle" TBSTATE="checked" ID="TOOLBAR_SECTIONS" LANGUAGE="javascript" onclick="return TOOLBARS_onclick(SectionsToolbar, ToolbarMenuSections)">
	    <?php echo $this->gl('menu_tbsection'); ?>
		  </div>
	    </div>
	  </div>

	  <div class="tbMenu" ID="FORMAT" LANGUAGE="javascript" tbOnMenuShow="return OnMenuShow(QueryStatusFormatMenu, FORMAT)">
	    <?php echo $this->gl('menu_format'); ?>
	    <div class="tbMenuItem" ID="FORMAT_FONT" LANGUAGE="javascript" onclick="return FORMAT_FONT_onclick()">
	    <?php echo $this->gl('menu_font'); ?>
	    </div>

	    <div class="tbSeparator"></div>

	    <div class="tbMenuItem" ID="FORMAT_BOLD" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_BOLD_onclick()">
	    <?php echo $this->gl('menu_bold'); ?>
	      <img class="tbIcon" src="editor/images/bold.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="FORMAT_ITALIC" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_ITALIC_onclick()">
	    <?php echo $this->gl('menu_italic'); ?>
	      <img class="tbIcon" src="editor/images/italic.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="FORMAT_UNDERLINE" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_UNDERLINE_onclick()">
	    <?php echo $this->gl('menu_underline'); ?>
	      <img class="tbIcon" src="editor/images/under.gif" WIDTH="23" HEIGHT="22">
	    </div>

	    <div class="tbSeparator"></div>

	    <div class="tbMenuItem" ID="FORMAT_SETFORECOLOR" LANGUAGE="javascript" onclick="return DECMD_SETFORECOLOR_onclick()">
	    <?php echo $this->gl('menu_forecolor'); ?>
	      <img class="tbIcon" src="editor/images/fgcolor.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="FORMAT_SETBACKCOLOR" LANGUAGE="javascript" onclick="return DECMD_SETBACKCOLOR_onclick()">
	    <?php echo $this->gl('menu_backcolor'); ?>
	      <img class="tbIcon" src="editor/images/bgcolor.gif" WIDTH="23" HEIGHT="22">
	    </div>

	    <div class="tbSeparator"></div>

	    <div class="tbMenuItem" ID="FORMAT_JUSTIFYLEFT" TBTYPE="radio" NAME="Justify" LANGUAGE="javascript" onclick="return DECMD_JUSTIFYLEFT_onclick()">
	    <?php echo $this->gl('menu_justifyleft'); ?>
	      <img class="tbIcon" src="editor/images/left.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="FORMAT_JUSTIFYCENTER" TBTYPE="radio" NAME="Justify" LANGUAGE="javascript" onclick="return DECMD_JUSTIFYCENTER_onclick()">
	    <?php echo $this->gl('menu_justifycenter'); ?>
	      <img class="tbIcon" src="editor/images/center.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="FORMAT_JUSTIFYRIGHT" TBTYPE="radio" NAME="Justify" LANGUAGE="javascript" onclick="return DECMD_JUSTIFYRIGHT_onclick()">
	    <?php echo $this->gl('menu_justifyright'); ?>
	      <img class="tbIcon" src="editor/images/right.gif" WIDTH="23" HEIGHT="22">
	    </div>
	  </div>

	  <div class="tbMenu" ID="HTML" LANGUAGE="javascript" tbOnMenuShow="return OnMenuShow(QueryStatusHTMLMenu, HTML)">
	    <?php echo $this->gl('menu_html'); ?>
	    <div class="tbMenuItem" ID="HTML_HYPERLINK" LANGUAGE="javascript" onclick="return DECMD_HYPERLINK_onclick()">
	    <?php echo $this->gl('menu_hyperlink'); ?>
	      <img class="tbIcon" src="editor/images/link.gif" WIDTH="23" HEIGHT="22">
	    </div>
	    <div class="tbMenuItem" ID="HTML_ANCHOR" LANGUAGE="javascript" onclick="return DECMD_ANCHOR_onclick()">
	    <?php echo $this->gl('menu_anchor'); ?>
	    </div>
	    <div class="tbMenuItem" ID="HTML_IMAGE" LANGUAGE="javascript" onclick="return DECMD_IMAGE_onclick()">
	    <?php echo $this->gl('menu_image'); ?>
	      <img class="tbIcon" src="editor/images/image.gif" WIDTH="23" HEIGHT="22">
	    </div>

	    <div class="tbSeparator"></div>

		<div class="tbMenuItem" ID="HTML_VISIBLEBORDERS" TBTYPE="toggle" TBSTATE="checked" LANGUAGE="javascript" onclick="return DECMD_VISIBLEBORDERS_onclick()">
	    <?php echo $this->gl('menu_visibleborders'); ?>
			<img class="tbIcon" src="editor/images/borders.gif" WIDTH="23" HEIGHT="22">
		</div>

		<div class="tbMenuItem" ID="HTML_SHOWDETAILS" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_SHOWDETAILS_onclick()">
	    <?php echo $this->gl('menu_showdetails'); ?>
			<img class="tbIcon" src="editor/images/details.gif" WIDTH="23" HEIGHT="22">
		</div>

		<div class="tbSeparator"></div>

		<div class="tbMenuItem" ID="HTML_MAKE_ABSOLUTE" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_MAKE_ABSOLUTE_onclick()">
	    <?php echo $this->gl('menu_makeabs'); ?>
			<img class="tbIcon" src="editor/images/abspos.gif" WIDTH="23" HEIGHT="22">
		</div>

		<div class="tbMenuItem" ID="HTML_LOCK_ELEMENT" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_LOCK_ELEMENT_onclick()">
	    <?php echo $this->gl('menu_lock'); ?>
			<img class="tbIcon" src="editor/images/lock.gif" WIDTH="23" HEIGHT="22">
		</div>

		<div class="tbSeparator"></div>

		<div class="tbSubmenu" ID="HTML_ZORDER" LANGUAGE="javascript">
	    <?php echo $this->gl('menu_zorder'); ?>
			<div class="tbMenuItem" ID="HTML_ZORDER_BRINGFRONT" LANGUAGE="javascript" onclick="return ZORDER_BRINGFRONT_onclick()">
	    <?php echo $this->gl('menu_bringfront'); ?>
			</div>

			<div class="tbMenuItem" ID="HTML_ZORDER_SENDBACK" LANGUAGE="javascript" onclick="return ZORDER_SENDBACK_onclick()">
	    <?php echo $this->gl('menu_sendback'); ?>
			</div>

			<div class="tbSeparator"></div>

			<div class="tbMenuItem" ID="HTML_ZORDER_BRINGFORWARD" LANGUAGE="javascript" onclick="return ZORDER_BRINGFORWARD_onclick()">
	    <?php echo $this->gl('menu_bringforward'); ?>
			</div>

			<div class="tbMenuItem" ID="HTML_ZORDER_SENDBACKWARD" LANGUAGE="javascript" onclick="return ZORDER_SENDBACKWARD_onclick()">
	    <?php echo $this->gl('menu_sendbackward'); ?>
			</div>

			<div class="tbSeparator"></div>

			<div class="tbMenuItem" ID="HTML_ZORDER_BELOWTEXT" LANGUAGE="javascript" onclick="return ZORDER_BELOWTEXT_onclick()">
	    <?php echo $this->gl('menu_belowtext'); ?>
			</div>

			<div class="tbMenuItem" ID="HTML_ZORDER_ABOVETEXT" LANGUAGE="javascript" onclick="return ZORDER_ABOVETEXT_onclick()">
	    <?php echo $this->gl('menu_abovetext'); ?>
			</div>
		</div>

		<div class="tbSeparator"></div>

		<div class="tbMenuItem" ID="HTML_SNAPTOGRID" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_SNAPTOGRID_onclick()">
	    <?php echo $this->gl('menu_snaptogrid'); ?>
			<img class="tbIcon" src="editor/images/snapgrid.gif" WIDTH="23" HEIGHT="22">
		</div>
	    <div class="tbSeparator"></div>

  		<div class="tbMenuItem" ID="CUSTOM_HTML" LANGUAGE="javascript" onclick="return INTRINSICS_onclick(prompt('Indtast custom HTML', ''))">
	    <?php echo $this->gl('menu_customhtml'); ?>
		</div>

	  </div>
	</div>

	<div class="tbToolbar" ID="FormatToolbar">
	  <select ID="ParagraphStyle" class="tbGeneral" style="width:115px" TITLE="Typografi" LANGUAGE="javascript" onchange="return ParagraphStyle_onchange()">
	  </select>
	  <select ID="FontName" class="tbGeneral" style="width:120px" TITLE="Skrifttype" LANGUAGE="javascript" onchange="return FontName_onchange()">
	    <?php
			/** Hent fonte **/
			$fonts = system::getFonts();
			for($i = 0, $n = sizeOf($fonts); $i < $n; $i++) {
				echo '<option value="' . $fonts[$i] . '">' . $fonts[$i] . "\n";
			}
	    ?>
	  </select>
	  <select ID="FontSize" class="tbGeneral" style="width:40px" TITLE="Skriftstørrelse" LANGUAGE="javascript" onchange="return FontSize_onchange()">
	    <option value="1">1
	    <option value="2">2
	    <option value="3">3
	    <option value="4">4
	    <option value="5">5
	    <option value="6">6
	    <option value="7">7
	  </select>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_BOLD" TITLE="<?php echo $this->gl('menu_bold'); ?>" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_BOLD_onclick()">
	    <img class="tbIcon" src="editor/images/bold.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_ITALIC" TITLE="<?php echo $this->gl('menu_italic'); ?>" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_ITALIC_onclick()">
	    <img class="tbIcon" src="editor/images/italic.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_UNDERLINE" TITLE="<?php echo $this->gl('menu_underline'); ?>" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_UNDERLINE_onclick()">
	    <img class="tbIcon" src="editor/images/under.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_SETFORECOLOR" TITLE="<?php echo $this->gl('menu_forecolor'); ?>" LANGUAGE="javascript" onclick="return DECMD_SETFORECOLOR_onclick()">
	    <img class="tbIcon" src="editor/images/fgcolor.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_SETBACKCOLOR" TITLE="<?php echo $this->gl('menu_backcolor'); ?>" LANGUAGE="javascript" onclick="return DECMD_SETBACKCOLOR_onclick()">
	    <img class="tbIcon" src="editor/images/bgcolor.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_JUSTIFYLEFT" TITLE="<?php echo $this->gl('menu_justifyleft'); ?>" TBTYPE="toggle" NAME="Justify" LANGUAGE="javascript" onclick="return DECMD_JUSTIFYLEFT_onclick()">
	    <img class="tbIcon" src="editor/images/left.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_JUSTIFYCENTER" TITLE="<?php echo $this->gl('menu_justifycenter'); ?>" TBTYPE="toggle" NAME="Justify" LANGUAGE="javascript" onclick="return DECMD_JUSTIFYCENTER_onclick()">
	    <img class="tbIcon" src="editor/images/center.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_JUSTIFYRIGHT" TITLE="<?php echo $this->gl('menu_justifyright'); ?>" TBTYPE="toggle" NAME="Justify" LANGUAGE="javascript" onclick="return DECMD_JUSTIFYRIGHT_onclick()">
	    <img class="tbIcon" src="editor/images/right.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_ORDERLIST" TITLE="<?php echo $this->gl('menu_orderlist'); ?>" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_ORDERLIST_onclick()">
	    <img class="tbIcon" src="editor/images/numlist.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_UNORDERLIST" TITLE="<?php echo $this->gl('menu_unorderlist'); ?>" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_UNORDERLIST_onclick()">
	    <img class="tbIcon" src="editor/images/bullist.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_OUTDENT" TITLE="<?php echo $this->gl('menu_outdent'); ?>" LANGUAGE="javascript" onclick="return DECMD_OUTDENT_onclick()">
	    <img class="tbIcon" src="editor/images/deindent.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_INDENT" TITLE="<?php echo $this->gl('menu_indent'); ?>" LANGUAGE="javascript" onclick="return DECMD_INDENT_onclick()">
	    <img class="tbIcon" src="editor/images/inindent.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_HYPERLINK" TITLE="<?php echo $this->gl('menu_hyperlink'); ?>" LANGUAGE="javascript" onclick="return DECMD_HYPERLINK_onclick()">
	    <img class="tbIcon" src="editor/images/link.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_IMAGE" TITLE="<?php echo $this->gl('menu_image'); ?>" LANGUAGE="javascript" onclick="return DECMD_IMAGE_onclick()">
	    <img class="tbIcon" src="editor/images/image.gif" WIDTH="23" HEIGHT="22">
	  </div>
	</div>

	<div class="tbToolbar" ID="AbsolutePositioningToolbar">
	  <div class="tbButton" ID="DECMD_VISIBLEBORDERS" TITLE="<?php echo $this->gl('menu_visibleborders'); ?>" TBTYPE="toggle" TBSTATE="checked" LANGUAGE="javascript" onclick="return DECMD_VISIBLEBORDERS_onclick()">
	    <img class="tbIcon" src="editor/images/borders.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_SHOWDETAILS" TITLE="<?php echo $this->gl('menu_showdetails'); ?>" TBTYPE="toggle" LANGUAGE="javascript" onclick="return DECMD_SHOWDETAILS_onclick()">
	    <img class="tbIcon" src="editor/images/details.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_MAKE_ABSOLUTE" TBTYPE="toggle" LANGUAGE="javascript" TITLE="<?php echo $this->gl('menu_makeabs'); ?>" onclick="return DECMD_MAKE_ABSOLUTE_onclick()">
	    <img class="tbIcon" src="editor/images/abspos.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_LOCK_ELEMENT" TBTYPE="toggle" LANGUAGE="javascript" TITLE="<?php echo $this->gl('menu_lock'); ?>" onclick="return DECMD_LOCK_ELEMENT_onclick()">
	    <img class="tbIcon" src="editor/images/lock.gif" WIDTH="23" HEIGHT="22">
	  </div>
	</div>

	<div class="tbToolbar" ID="StandardToolbar">
	  <div class="tbButton" ID="MENU_FILE_SAVE" TITLE="<?php echo $this->gl('menu_filesave'); ?>" LANGUAGE="javascript" onclick="return MENU_FILE_SAVE_onclick()">
	    <img class="tbIcon" src="editor/images/save.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_CUT" TITLE="<?php echo $this->gl('menu_cut'); ?>" LANGUAGE="javascript" onclick="return DECMD_CUT_onclick()">
	    <img class="tbIcon" src="editor/images/cut.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_COPY" TITLE="<?php echo $this->gl('menu_copy'); ?>" LANGUAGE="javascript" onclick="return DECMD_COPY_onclick()">
	    <img class="tbIcon" src="editor/images/copy.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_PASTE" TITLE="<?php echo $this->gl('menu_paste'); ?>" LANGUAGE="javascript" onclick="return DECMD_PASTE_onclick()">
	    <img class="tbIcon" src="editor/images/paste.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_UNDO" TITLE="<?php echo $this->gl('menu_undo'); ?>" LANGUAGE="javascript" onclick="return DECMD_UNDO_onclick()">
	    <img class="tbIcon" src="editor/images/undo.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_REDO" TITLE="<?php echo $this->gl('menu_redo'); ?>" LANGUAGE="javascript" onclick="return DECMD_REDO_onclick()">
	    <img class="tbIcon" src="editor/images/redo.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_FINDTEXT" TITLE="<?php echo $this->gl('menu_findtext'); ?>" LANGUAGE="javascript" onclick="return DECMD_FINDTEXT_onclick()">
	    <img class="tbIcon" src="editor/images/find.gif" WIDTH="23" HEIGHT="22">
	  </div>
	</div>

	<div class="tbToolbar" ID="TableToolbar">
	  <div class="tbButton" ID="DECMD_INSERTTABLE" TITLE="<?php echo $this->gl('menu_inserttable'); ?>" LANGUAGE="javascript" onclick="return TABLE_INSERTTABLE_onclick()">
	    <img class="tbIcon" src="editor/images/instable.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_INSERTROW" TITLE="<?php echo $this->gl('menu_insertrow'); ?>" LANGUAGE="javascript" onclick="return TABLE_INSERTROW_onclick()">
	    <img class="tbIcon" src="editor/images/insrow.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_DELETEROWS" TITLE="<?php echo $this->gl('menu_deleterows'); ?>" LANGUAGE="javascript" onclick="return TABLE_DELETEROW_onclick()">
	    <img class="tbIcon" src="editor/images/delrow.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_INSERTCOL" TITLE="<?php echo $this->gl('menu_insertcol'); ?>" LANGUAGE="javascript" onclick="return TABLE_INSERTCOL_onclick()">
	    <img class="tbIcon" src="editor/images/inscol.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_DELETECOLS" TITLE="<?php echo $this->gl('menu_deletecols'); ?>" LANGUAGE="javascript" onclick="return TABLE_DELETECOL_onclick()">
	    <img class="tbIcon" src="editor/images/delcol.gif" WIDTH="23" HEIGHT="22">
	  </div>

	  <div class="tbSeparator"></div>

	  <div class="tbButton" ID="DECMD_INSERTCELL" TITLE="<?php echo $this->gl('menu_insertcell'); ?>" LANGUAGE="javascript" onclick="return TABLE_INSERTCELL_onclick()">
	    <img class="tbIcon" src="editor/images/inscell.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_DELETECELLS" TITLE="<?php echo $this->gl('menu_deletecells'); ?>" LANGUAGE="javascript" onclick="return TABLE_DELETECELL_onclick()">
	    <img class="tbIcon" src="editor/images/delcell.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_MERGECELLS" TITLE="<?php echo $this->gl('menu_mergecells'); ?>" LANGUAGE="javascript" onclick="return TABLE_MERGECELL_onclick()">
	    <img class="tbIcon" src="editor/images/mrgcell.gif" WIDTH="23" HEIGHT="22">
	  </div>
	  <div class="tbButton" ID="DECMD_SPLITCELL" TITLE="<?php echo $this->gl('menu_splitcell'); ?>" LANGUAGE="javascript" onclick="return TABLE_SPLITCELL_onclick()">
	    <img class="tbIcon" src="editor/images/spltcell.gif" WIDTH="23" HEIGHT="22">
	  </div>
	</div>

	<div class="tbToolbar" ID="AdvancedToolbar">

	  <div class="tbButton" ID="DECMD_SHOWHTML" TITLE="<?php echo $this->gl('menu_showhtml'); ?>" TBTYPE="Toggle" LANGUAGE="javascript" onClick="return SHOW_HTML_onclick()">
		<img class="tbIcon" src="editor/images/html.gif" width="23" height="22">
	  </div>

	<select ID="VariantNumber" class="tbGeneral" style="width:45" TITLE="" LANGUAGE="javascript" onchange="return VARIANT_NUMBER_onchange()">
		<?php
			$obj = new documentsection;
			$masterid = $this->getmasterid($this->objectid[0]);
			$obj->readobject($masterid);
			$v = $obj->getvariants();
				$count = 1;
				echo '<OPTION value="' . $masterid . '">'.$obj->getlanguage().'</OPTION>';
				foreach ($v as $order) {
					$nyobj = owRead($order);
					if ($nyobj->getType() != 'documentsection') continue;
					echo '<OPTION value="'.$order . '"';
					if ($this->objectid[0] == $order ) { echo ' SELECTED'; }
					echo '>'.$nyobj->getlanguage().'</OPTION>';
					$count++;
				}
		?>
	  </select>
	  <div class="tbButton" ID="DECMD_NEWVARIANT" TITLE="<?php echo $this->gl('menu_newvariant'); ?>" LANGUAGE="javascript" onclick="return VARIANT_NEW_onclick()">
	  	<img class="tbIcon" src="editor/images/newproj.gif" width="23" height="22">
	  </div>

	</div>

	<div class="tbToolbar" ID="SectionsToolbar">
	<select ID="SectionNumber" class="tbGeneral" style="width:237" TITLE="" LANGUAGE="javascript" onchange="return SECTION_NUMBER_onchange()">
		<?php
		$obj = owRead($this->objectid[0]);
		$language = $obj->getlanguage();
		unset($obj);
		$obj = new documentsection;
		$masterid = $this->getmasterid($this->objectid[0]);
		$obj->readobject($masterid);
		$s = $obj->getSiblings();
		$count = 1;
		foreach ($s as $order) {
			$nyobj = owRead($order);
			if ($nyobj->getType() == 'documentsection') {
				$v = $nyobj->getVariants($language);
				if ($v[0]) {
					$variant = new documentsection;
					$variant->readobject($v[0]);
					$name = $variant->getname();
					$oid = $variant->getobjectid();
					$lang = $variant->getlanguage();
					$active = $variant->isactive();
				} else {
					$name = $nyobj->getname();
					$oid = $order;
					$lang = $nyobj->getlanguage();
					$active = $nyobj->isactive();
				}
				echo '<OPTION value="'.$oid . '"';
				if (!$active) { echo ' style="color: red;"'; }
				if ($this->objectid[0] == $oid ) { echo ' SELECTED'; }
				echo '>['.$count."] ".$name.' (' . $lang . ')</OPTION>';
				$count++;
			}
		}
		
		if ($count > 2 || $variant) {
			$multiplesection = true;
		} else {
			$multiplesection = false;
		}
	?>
	  </select>

	  <div class="tbButton" ID="DECMD_NEWSECTION" TITLE="<?php echo $this->gl('menu_newsection'); ?>" LANGUAGE="javascript" onclick="return SECTION_NEW_onclick()">
	  	<img class="tbIcon" src="editor/images/newdoc.gif" width="23" height="22">
	  </div>

	  <div class="tbButton" ID="DECMD_NEWSECTIONBELOW" TITLE="<?php echo $this->gl('menu_newsectionbelow'); ?>" LANGUAGE="javascript" onclick="return SECTION_NEWBELOW_onclick()">
	  	<img class="tbIcon" src="editor/images/sectionbelow.gif" width="23" height="22">
	  </div>

	  <div class="tbButton" ID="DECMD_MOVEUPSECTION" TITLE="<?php echo $this->gl('menu_moveupsection'); ?>" LANGUAGE="javascript" <?php if ($multiplesection) { ?> onclick="return SECTION_MOVEUP_onclick()" <?php } ?>>
	  	<img class="tbIcon" src="editor/images/sectionup.gif" width="23" height="22">
	  </div>

	  <div class="tbButton" ID="DECMD_MOVEDOWNSECTION" TITLE="<?php echo $this->gl('menu_movedownsection'); ?>" LANGUAGE="javascript" <?php if ($multiplesection) { ?> onclick="return SECTION_MOVEDOWN_onclick()" <?php } ?>>
	  	<img class="tbIcon" src="editor/images/sectiondown.gif" width="23" height="22">
	  </div>

	  <div class="tbButton" ID="DECMD_DELETESECTION" TITLE="<?php echo $this->gl('menu_deletesection'); ?>" LANGUAGE="javascript" <?php if ($multiplesection) { ?> onclick="return SECTION_DELETE_onclick()" <?php } ?>>
	  	<img class="tbIcon" src="editor/images/delete.gif" width="23" height="22">
	  </div>

	<?php
	if ($approvestatus == 0) {
		?>
	  <div class="tbButton" ID="DECMD_REQUESTAPPROVAL" TITLE="<?php echo $this->gl('menu_requestapproval'); ?>" LANGUAGE="javascript" onclick="return REQUESTAPPROVAL_onclick()">
	  	<img class="tbIcon" src="editor/images/backdoc.gif" width="23" height="22">
	  </div>
	 <?php
	}
	?>
	<?php
	if ($approvestatus == 2) {
		?>
	  <div class="tbButton" ID="DECMD_APPROVEPUBLISH" TITLE="<?php echo $this->gl('menu_approvepublish'); ?>" LANGUAGE="javascript" onclick="return APPROVEPUBLISH_onclick()">
	  	<img class="tbIcon" src="editor/images/tasklist.gif" width="23" height="22">
	  </div>
	 <?php
	}
	?>
	<div class="tbButton" ID="DECMD_PREVIEW" TITLE="<?php echo $this->gl('menu_preview'); ?>" LANGUAGE="javascript" onclick="window.open('<?php echo $this->userhandler->getViewerUrl().'showpage.php?pageid=' . $parentid; ?>');">
	  	<img class="tbIcon" src="editor/images/preview.gif" width="23" height="22">
	  </div>
	  <div class="tbButton" ID="DECMD_SECTIONPROPERTIES" TITLE="<?php echo $this->gl('menu_documentsection_properties'); ?>" LANGUAGE="javascript" onclick="return SECTION_PROPERTIES_onclick()">
	  	<img class="tbIcon" src="editor/images/props.gif" width="23" height="22">
	  </div>


	</div>
	
	<!-- DHTML Editing control Object. This will be the body object for the toolbars. -->
	<object ID="tbContentElement" CLASS="tbContentElement" CLASSID="clsid:2D360200-FFF5-11D1-8D03-00A0C959BC0A" VIEWASTEXT>
	  <param name=Scrollbars value=true>
<?php
	  $obj = owNew('document');
	  $obj->readobject($parentid);
	  if ($obj->islocked() OR (@$_REQUEST['locked']==1) ) echo '<param name="BrowseMode" value="true">';
?>
	</object>

	<!-- DEInsertTableParam Object -->
	<object ID="ObjTableInfo" CLASSID="clsid:47B0DFC7-B7A3-11D1-ADC5-006008A5848C" VIEWASTEXT>
	</object>
	
	<!-- DEGetBlockFmtNamesParam Object -->
	<object ID="ObjBlockFormatInfo" CLASSID="clsid:8D91090E-B955-11D1-ADC5-006008A5848C" VIEWASTEXT>
	</object>
	<form name="indhold" style="display: none">
	<textarea name="content">
		<?php

		$obj = new documentsection;
		$obj->readobject($this->objectid[0]);
		$indhold = $obj->elements[0]['content'];
		$indhold = str_replace("&", "&amp", $indhold);
		$indhold = str_replace("<", "&lt;", $indhold);
		$indhold = str_replace(">", "&gt;", $indhold);

		?>
		<html>
		<head>
		<BASE HREF="<?php echo $obj->viewer_url ?>">
		<LINK href="<?php echo $this->userhandler->getSystemUrl();?>getstylesheet.php?objectid=<?php echo $this->objectid[0] ?>" rel=stylesheet type=text/css>
		</head>
		<body>
		<?php echo $indhold ?>
		</body>
		</head>
	</textarea>
	</form>
	<script LANGUAGE="Javascript">
    	var content = document.indhold.content.value;
	content = content.replace(/&amp;/g, "&");
	content = content.replace(/&lt;/g, "<");
	content = content.replace(/&gt;/g, ">");
	tbContentElement.DocumentHTML = document.indhold.content.value;
	</script>
	<!-- Toolbar Code File. Note: This must always be the last thing on the page -->
	<script LANGUAGE="Javascript" SRC="editor/toolbars.js">
	</script>
	<script LANGUAGE="Javascript">
	  tbScriptletDefinitionFile = "editor/menubody.htm";
	</script>
	<script LANGUAGE="Javascript" SRC="editor/tbmenus.js">
	</script>
	</div>

