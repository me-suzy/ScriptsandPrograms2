//
// Constants
//
var MENU_SEPARATOR = ""; // Context menu separator
//
// Globals
//

var docComplete = false;
var initialDocComplete = false;

var QueryStatusToolbarButtons = new Array();
var QueryStatusEditMenu = new Array();
var QueryStatusFormatMenu = new Array();
var QueryStatusHTMLMenu = new Array();
var QueryStatusTableMenu = new Array();
var ContextMenu = new Array();
var GeneralContextMenu = new Array();
var TableContextMenu = new Array();
var AbsPosContextMenu = new Array();
var ImageContextMenu = new Array();
var TablePropertiesContextMenu = new Array();

//
// Utility functions
//

// Constructor for custom object that represents an item on the context menu
function ContextMenuItem(string, cmdId) {
	 this.string = string;
	 this.cmdId = cmdId;
}

// Constructor for custom object that represents a QueryStatus command and
// corresponding toolbar element.
function QueryStatusItem(command, element) {
	 this.command = command;
	 this.element = element;
}

//
// Event handlers
//

function window_onload() {

	 // Initialze QueryStatus tables. These tables associate a command id with the
	 // corresponding button object. Must be done on window load, 'cause the buttons must exist.
	 QueryStatusToolbarButtons[0] = new QueryStatusItem(DECMD_BOLD, document.body.all["DECMD_BOLD"]);
	 QueryStatusToolbarButtons[1] = new QueryStatusItem(DECMD_COPY, document.body.all["DECMD_COPY"]);
	 QueryStatusToolbarButtons[2] = new QueryStatusItem(DECMD_CUT, document.body.all["DECMD_CUT"]);
	 QueryStatusToolbarButtons[3] = new QueryStatusItem(DECMD_HYPERLINK, document.body.all["DECMD_HYPERLINK"]);
	 QueryStatusToolbarButtons[4] = new QueryStatusItem(DECMD_INDENT, document.body.all["DECMD_INDENT"]);
	 QueryStatusToolbarButtons[5] = new QueryStatusItem(DECMD_ITALIC, document.body.all["DECMD_ITALIC"]);
	 QueryStatusToolbarButtons[6] = new QueryStatusItem(DECMD_JUSTIFYLEFT, document.body.all["DECMD_JUSTIFYLEFT"]);
	 QueryStatusToolbarButtons[7] = new QueryStatusItem(DECMD_JUSTIFYCENTER, document.body.all["DECMD_JUSTIFYCENTER"]);
	 QueryStatusToolbarButtons[8] = new QueryStatusItem(DECMD_JUSTIFYRIGHT, document.body.all["DECMD_JUSTIFYRIGHT"]);
	 QueryStatusToolbarButtons[9] = new QueryStatusItem(DECMD_LOCK_ELEMENT, document.body.all["DECMD_LOCK_ELEMENT"]);
	 QueryStatusToolbarButtons[10] = new QueryStatusItem(DECMD_MAKE_ABSOLUTE, document.body.all["DECMD_MAKE_ABSOLUTE"]);
	 QueryStatusToolbarButtons[11] = new QueryStatusItem(DECMD_ORDERLIST, document.body.all["DECMD_ORDERLIST"]);
	 QueryStatusToolbarButtons[12] = new QueryStatusItem(DECMD_OUTDENT, document.body.all["DECMD_OUTDENT"]);
	 QueryStatusToolbarButtons[13] = new QueryStatusItem(DECMD_PASTE, document.body.all["DECMD_PASTE"]);
	 QueryStatusToolbarButtons[14] = new QueryStatusItem(DECMD_REDO, document.body.all["DECMD_REDO"]);
	 QueryStatusToolbarButtons[15] = new QueryStatusItem(DECMD_UNDERLINE, document.body.all["DECMD_UNDERLINE"]);
	 QueryStatusToolbarButtons[16] = new QueryStatusItem(DECMD_UNDO, document.body.all["DECMD_UNDO"]);
	 QueryStatusToolbarButtons[17] = new QueryStatusItem(DECMD_UNORDERLIST, document.body.all["DECMD_UNORDERLIST"]);
	 QueryStatusToolbarButtons[18] = new QueryStatusItem(DECMD_INSERTTABLE, document.body.all["DECMD_INSERTTABLE"]);
	 QueryStatusToolbarButtons[19] = new QueryStatusItem(DECMD_INSERTROW, document.body.all["DECMD_INSERTROW"]);
	 QueryStatusToolbarButtons[20] = new QueryStatusItem(DECMD_DELETEROWS, document.body.all["DECMD_DELETEROWS"]);
	 QueryStatusToolbarButtons[21] = new QueryStatusItem(DECMD_INSERTCOL, document.body.all["DECMD_INSERTCOL"]);
	 QueryStatusToolbarButtons[22] = new QueryStatusItem(DECMD_DELETECOLS, document.body.all["DECMD_DELETECOLS"]);
	 QueryStatusToolbarButtons[23] = new QueryStatusItem(DECMD_INSERTCELL, document.body.all["DECMD_INSERTCELL"]);
	 QueryStatusToolbarButtons[24] = new QueryStatusItem(DECMD_DELETECELLS, document.body.all["DECMD_DELETECELLS"]);
	 QueryStatusToolbarButtons[25] = new QueryStatusItem(DECMD_MERGECELLS, document.body.all["DECMD_MERGECELLS"]);
	 QueryStatusToolbarButtons[26] = new QueryStatusItem(DECMD_SPLITCELL, document.body.all["DECMD_SPLITCELL"]);
	 QueryStatusToolbarButtons[27] = new QueryStatusItem(DECMD_SETFORECOLOR, document.body.all["DECMD_SETFORECOLOR"]);
	 QueryStatusToolbarButtons[28] = new QueryStatusItem(DECMD_SETBACKCOLOR, document.body.all["DECMD_SETBACKCOLOR"]);
	 QueryStatusToolbarButtons[29] = new QueryStatusItem(DECMD_IMAGE, document.body.all["DECMD_IMAGE"]);
/* Skal lige finde en smart måde at styr de sidste knappers status på */
//  QueryStatusToolbarButtons[30] = new QueryStatusItem(null, document.body.all["DECMD_SNAPTOGRID"]);
//  QueryStatusToolbarButtons[31] = new QueryStatusItem(null, document.body.all["DECMD_VISIBLEBORDERS"]);
//  QueryStatusToolbarButtons[32] = new QueryStatusItem(null, document.body.all["DECMD_SHOWDETAILS"]);


	 QueryStatusEditMenu[0] = new QueryStatusItem(DECMD_UNDO, document.body.all["EDIT_UNDO"]);
	 QueryStatusEditMenu[1] = new QueryStatusItem(DECMD_REDO, document.body.all["EDIT_REDO"]);
	 QueryStatusEditMenu[2] = new QueryStatusItem(DECMD_CUT, document.body.all["EDIT_CUT"]);
	 QueryStatusEditMenu[3] = new QueryStatusItem(DECMD_COPY, document.body.all["EDIT_COPY"]);
	 QueryStatusEditMenu[4] = new QueryStatusItem(DECMD_PASTE, document.body.all["EDIT_PASTE"]);
	 QueryStatusEditMenu[5] = new QueryStatusItem(DECMD_DELETE, document.body.all["EDIT_DELETE"]);

	 QueryStatusHTMLMenu[0] = new QueryStatusItem(DECMD_HYPERLINK, document.body.all["HTML_HYPERLINK"]);
	 QueryStatusHTMLMenu[1] = new QueryStatusItem(DECMD_HYPERLINK, document.body.all["HTML_ANCHOR"]);
	 QueryStatusHTMLMenu[2] = new QueryStatusItem(DECMD_IMAGE, document.body.all["HTML_IMAGE"]);
	 QueryStatusHTMLMenu[3] = new QueryStatusItem(DECMD_MAKE_ABSOLUTE, document.body.all["HTML_MAKE_ABSOLUTE"]);
	 QueryStatusHTMLMenu[4] = new QueryStatusItem(DECMD_LOCK_ELEMENT, document.body.all["HTML_LOCK_ELEMENT"]);
	 
	 QueryStatusFormatMenu[0] = new QueryStatusItem(DECMD_FONT, document.body.all["FORMAT_FONT"]);
	 QueryStatusFormatMenu[1] = new QueryStatusItem(DECMD_BOLD, document.body.all["FORMAT_BOLD"]);
	 QueryStatusFormatMenu[2] = new QueryStatusItem(DECMD_ITALIC, document.body.all["FORMAT_ITALIC"]);
	 QueryStatusFormatMenu[3] = new QueryStatusItem(DECMD_UNDERLINE, document.body.all["FORMAT_UNDERLINE"]);
	 QueryStatusFormatMenu[4] = new QueryStatusItem(DECMD_JUSTIFYLEFT, document.body.all["FORMAT_JUSTIFYLEFT"]);
	 QueryStatusFormatMenu[5] = new QueryStatusItem(DECMD_JUSTIFYCENTER, document.body.all["FORMAT_JUSTIFYCENTER"]);
	 QueryStatusFormatMenu[6] = new QueryStatusItem(DECMD_JUSTIFYRIGHT, document.body.all["FORMAT_JUSTIFYRIGHT"]);
	 QueryStatusFormatMenu[7] = new QueryStatusItem(DECMD_SETFORECOLOR, document.body.all["FORMAT_SETFORECOLOR"]);
	 QueryStatusFormatMenu[8] = new QueryStatusItem(DECMD_SETBACKCOLOR, document.body.all["FORMAT_SETBACKCOLOR"]);
	 QueryStatusTableMenu[0] = new QueryStatusItem(DECMD_INSERTTABLE, document.body.all["TABLE_INSERTTABLE"]);
	 QueryStatusTableMenu[1] = new QueryStatusItem(DECMD_INSERTROW, document.body.all["TABLE_INSERTROW"]);
	 QueryStatusTableMenu[2] = new QueryStatusItem(DECMD_DELETEROWS, document.body.all["TABLE_DELETEROW"]);
	 QueryStatusTableMenu[3] = new QueryStatusItem(DECMD_INSERTCOL, document.body.all["TABLE_INSERTCOL"]);
	 QueryStatusTableMenu[4] = new QueryStatusItem(DECMD_DELETECOLS, document.body.all["TABLE_DELETECOL"]);
	 QueryStatusTableMenu[5] = new QueryStatusItem(DECMD_INSERTCELL, document.body.all["TABLE_INSERTCELL"]);
	 QueryStatusTableMenu[6] = new QueryStatusItem(DECMD_DELETECELLS, document.body.all["TABLE_DELETECELL"]);
	 QueryStatusTableMenu[7] = new QueryStatusItem(DECMD_MERGECELLS, document.body.all["TABLE_MERGECELL"]);
	 QueryStatusTableMenu[8] = new QueryStatusItem(DECMD_SPLITCELL, document.body.all["TABLE_SPLITCELL"]);
	 
	 // Initialize the context menu arrays.
	 GeneralContextMenu[0] = new ContextMenuItem("Klip", DECMD_CUT);
	 GeneralContextMenu[1] = new ContextMenuItem("Kopier", DECMD_COPY);
	 GeneralContextMenu[2] = new ContextMenuItem("Indsæt", DECMD_PASTE);
	 TableContextMenu[0] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 TableContextMenu[1] = new ContextMenuItem("Indsæt række", DECMD_INSERTROW);
	 TableContextMenu[2] = new ContextMenuItem("Slet rækker", DECMD_DELETEROWS);
	 TableContextMenu[3] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 TableContextMenu[4] = new ContextMenuItem("Indsæt kolonne", DECMD_INSERTCOL);
	 TableContextMenu[5] = new ContextMenuItem("Slet kolonner", DECMD_DELETECOLS);
	 TableContextMenu[6] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 TableContextMenu[7] = new ContextMenuItem("Indsæt celle", DECMD_INSERTCELL);
	 TableContextMenu[8] = new ContextMenuItem("Slet celler", DECMD_DELETECELLS);
	 TableContextMenu[9] = new ContextMenuItem("Flet celler", DECMD_MERGECELLS);
	 TableContextMenu[10] = new ContextMenuItem("Opdel celle", DECMD_SPLITCELL);
//	 TableContextMenu[11] = new ContextMenuItem(MENU_SEPARATOR, 0);
//	 TableContextMenu[12] = new ContextMenuItem("Celle egenskaber", DECMD_CELLPROPERTIES);

	 AbsPosContextMenu[0] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 AbsPosContextMenu[1] = new ContextMenuItem("Placer bagerst", DECMD_SEND_TO_BACK);
	 AbsPosContextMenu[2] = new ContextMenuItem("Placer forrest", DECMD_BRING_TO_FRONT);
	 AbsPosContextMenu[3] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 AbsPosContextMenu[4] = new ContextMenuItem("Flyt en tilbage", DECMD_SEND_BACKWARD);
	 AbsPosContextMenu[5] = new ContextMenuItem("Flyt en frem", DECMD_BRING_FORWARD);
	 AbsPosContextMenu[6] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 AbsPosContextMenu[7] = new ContextMenuItem("Flyt bag tekst", DECMD_SEND_BELOW_TEXT);
	 AbsPosContextMenu[8] = new ContextMenuItem("Flyt foran tekst", DECMD_BRING_ABOVE_TEXT);
	 ImageContextMenu[0] = new ContextMenuItem(MENU_SEPARATOR, 0);
	 ImageContextMenu[1] = new ContextMenuItem("Venstrestil billede", DECMD_IMAGEALIGNLEFT);
	 //ImageContextMenu[2] = new ContextMenuItem("Align Center (tekstombrydning)", DECMD_IMAGEALIGNCENTER);
	 ImageContextMenu[2] = new ContextMenuItem("Højrestil billede", DECMD_IMAGEALIGNRIGHT);
	 TablePropertiesContextMenu[0] = new ContextMenuItem("Tabel egenskaber", DECMD_TABLEPROPERTIES);

	 var f=new ActiveXObject("DEGetBlockFmtNamesParam.DEGetBlockFmtNamesParam");
	 tbContentElement.ExecCommand(DECMD_GETBLOCKFMTNAMES,OLECMDEXECOPT_DODEFAULT,f);
	 vbarr = new VBArray(f.Names);
	 arr = vbarr.toArray();
	 for (var i=0;i<arr.length;i++) {
		  ParagraphStyle.options[ParagraphStyle.options.length]=new Option(arr[i], arr[i]);
	 }

	 tbContentElement.showBorders = true;
	 document.getElementById('content').style.visibility='visible';
	 document.getElementById('loading').style.visibility='hidden';
	 tbContentElement.focus();
}

/*
  function loadStyles() {
  tbContentElement.ExecCommand(DECMD_GETBLOCKFMTNAMES, OLECMDEXECOPT_DODEFAULT, ObjBlockFormatInfo);
  alert(ObjBlockFormatInfo.Names[0]);

  }
*/
function InsertTable() {
	 var pVar = ObjTableInfo;
	 var args = new Array();
	 var arr = null;

	 // Display table information dialog
	 args["NumRows"] = ObjTableInfo.NumRows;
	 args["NumCols"] = ObjTableInfo.NumCols;
	 args["TableAttrs"] = ObjTableInfo.TableAttrs;
	 args["CellAttrs"] = ObjTableInfo.CellAttrs;
	 args["Caption"] = ObjTableInfo.Caption;

	 arr = null;

	 arr = showModalDialog( "document_editor_inserttable.php",
							args,
							"font-family: Verdana; font-size: 12pt; dialogWidth: 365px; dialogHeight:305px; help: no; status: no; scroll: no");


	 if (arr != null) {

		  // Initialize table object
		  for ( elem in arr ) {
			   if ("NumRows" == elem && arr["NumRows"] != null) {
					ObjTableInfo.NumRows = arr["NumRows"];
			   } else if ("NumCols" == elem && arr["NumCols"] != null) {
					ObjTableInfo.NumCols = arr["NumCols"];
			   } else if ("TableAttrs" == elem) {
					ObjTableInfo.TableAttrs = arr["TableAttrs"];
			   } else if ("CellAttrs" == elem) {
					ObjTableInfo.CellAttrs = arr["CellAttrs"];
			   } else if ("Caption" == elem) {
					ObjTableInfo.Caption = arr["Caption"];
			   }
		  }
		  tbContentElement.ExecCommand(DECMD_INSERTTABLE,OLECMDEXECOPT_DODEFAULT, pVar);
	 }
}

function tbContentElement_ShowContextMenu() {
	 if (tbContentElement.browseMode) return false;

	 var menuStrings = new Array();
	 var menuStates = new Array();
	 var state;
	 var i
		  var idx = 0;

	 // Rebuild the context menu. 
	 ContextMenu.length = 0;

	 // Always show general menu
	 for (i=0; i<GeneralContextMenu.length; i++) {
		  ContextMenu[idx++] = GeneralContextMenu[i];
	 }

	 // Is the selection inside a table? Add table menu if so
	 if (tbContentElement.QueryStatus(DECMD_INSERTROW) != DECMDF_DISABLED) {
		  for (i=0; i<TableContextMenu.length; i++) {
			   ContextMenu[idx++] = TableContextMenu[i];
		  }
	 }

	 // Is the selection on an absolutely positioned element? Add z-index commands if so
	 if (tbContentElement.QueryStatus(DECMD_LOCK_ELEMENT) != DECMDF_DISABLED) {
		  for (i=0; i<AbsPosContextMenu.length; i++) {
			   ContextMenu[idx++] = AbsPosContextMenu[i];
		  }
	 }

	 if (tbContentElement.DOM.selection.type == "Control") {
		  var controlRange = tbContentElement.DOM.selection.createRange();
		  for (i = 0; i < controlRange.length; i++) {
			   if (controlRange.item(i).tagName == 'IMG') {
					for(j=0; j < ImageContextMenu.length; j++) {
						 ContextMenu[idx++] = ImageContextMenu[j];
					}
			   } else if (controlRange.item(i).tagName == 'TABLE') {
					for(j=0; j < TablePropertiesContextMenu.length; j++) {
						 ContextMenu[idx++] = TablePropertiesContextMenu[j];
					}
			   }
		  }
	 }
  	
  
	 // Set up the actual arrays that get passed to SetContextMenu
	 for (i=0; i<ContextMenu.length; i++) {
		  menuStrings[i] = ContextMenu[i].string;
		  if (ContextMenu[i].cmdId >= DECMD_IMAGEALIGNLEFT) {
			   var controlRange = tbContentElement.DOM.selection.createRange();
			   for (j = 0; j < controlRange.length; j++) {
					if (controlRange.item(j).tagName == 'IMG') {
						 if (ContextMenu[i].cmdId == DECMD_IMAGEALIGNLEFT && controlRange.item(j).align == 'left') {
							  state = DECMDF_LATCHED;
						 } else if (ContextMenu[i].cmdId == DECMD_IMAGEALIGNRIGHT && controlRange.item(j).align == "right") {
							  state = DECMDF_LATCHED;
						 } else if (ContextMenu[i].cmdId == DECMD_IMAGEALIGNCENTER && controlRange.item(j).align == "center") {
							  state = DECMDF_LATCHED;
						 } else {
							  state = DECMDF_ENABLED;
						 }
					} else if (controlRange.item(j).tagName == 'TABLE') {
						 state = DECMDF_ENABLED;
					}
			   }
		  } else if (menuStrings[i] != MENU_SEPARATOR) {
			   state = tbContentElement.QueryStatus(ContextMenu[i].cmdId);
		  } else {
			   state = DECMDF_ENABLED;
		  }
    
		  if (state == DECMDF_DISABLED || state == DECMDF_NOTSUPPORTED) {
			   menuStates[i] = OLE_TRISTATE_GRAY;
		  } else if (state == DECMDF_ENABLED || state == DECMDF_NINCHED) {
			   menuStates[i] = OLE_TRISTATE_UNCHECKED;
		  } else { // DECMDF_LATCHED
			   menuStates[i] = OLE_TRISTATE_CHECKED;
		  }
	 }
	 // Set the context menu
	 tbContentElement.SetContextMenu(menuStrings, menuStates);
}

function tbContentElement_ContextMenuAction(itemIndex) {

	 if (ContextMenu[itemIndex].cmdId == DECMD_CELLPROPERTIES) {
		  alert('Vis cellegenskaber');
	 } else if (ContextMenu[itemIndex].cmdId >= DECMD_IMAGEALIGNLEFT) {
		  if (tbContentElement.DOM.selection.type == "Control") {
			   var controlRange = tbContentElement.DOM.selection.createRange();
			   for (i = 0; i <controlRange.length; i++) {
					if (controlRange.item(i).tagName == 'IMG') {
						 if (ContextMenu[itemIndex].cmdId == DECMD_IMAGEALIGNLEFT) {
							  if (controlRange.item(i).align != "left") {
								   controlRange.item(i).align = "left";
							  } else {
								   controlRange.item(i).align = "";
							  }
						 } else if (ContextMenu[itemIndex].cmdId == DECMD_IMAGEALIGNRIGHT) {
							  if (controlRange.item(i).align != "right") {
								   controlRange.item(i).align = "right";
							  } else {
								   controlRange.item(i).align = "";
							  }
						 } else if (ContextMenu[itemIndex].cmdId == DECMD_IMAGEALIGNCENTER) {
							  if (controlRange.item(i).align != "center") {
								   controlRange.item(i).align = "center";
							  } else {
								   controlRange.item(i).align = "";
							  }
						 } else {
							  controlRange.item(i).align = "";
						 }
					} else if (controlRange.item(i).tagName == 'TABLE') {
						 TABLE_PROPERTIES_onclick();
					} 
			   }
		  }
	 } else {
		  if (ContextMenu[itemIndex].cmdId == DECMD_INSERTTABLE) {
			   InsertTable();
		  } else {
			   tbContentElement.ExecCommand(ContextMenu[itemIndex].cmdId, OLECMDEXECOPT_DODEFAULT);
		  }
	 }
}

// DisplayChanged handler. Very time-critical routine; this is called
// every time a character is typed. QueryStatus those toolbar buttons that need
// to be in synch with the current state of the document and update.
function tbContentElement_DisplayChanged() {
	 var i, s;

	 if (tbContentElement.browseMode) {
		  for (i = 0; i < QueryStatusToolbarButtons.length; i++) {
			   TBSetState(QueryStatusToolbarButtons[i].element, "gray");
		  }
		  document.body.all["FILE"].TBSTATE = 'gray';
		  document.body.all["FILE"].style.color = 'gray';

		  document.body.all["EDIT"].TBSTATE = 'gray';
		  document.body.all["EDIT"].style.color = 'gray';

		  document.body.all["VIEW"].TBSTATE = 'gray';
		  document.body.all["VIEW"].style.color = 'gray';

		  document.body.all["FORMAT"].TBSTATE = 'gray';
		  document.body.all["FORMAT"].style.color = 'gray';

		  document.body.all["HTML"].TBSTATE = 'gray';
		  document.body.all["HTML"].style.color = 'gray';
		  
		  ParagraphStyle.disabled = true;
		  FontName.disabled = true;
		  FontSize.disabled = true;

		  TBSetState(document.body.all["DECMD_NEWSECTIONBELOW"], "gray");
		  TBSetState(document.body.all["DECMD_MOVEUPSECTION"], "gray");
		  TBSetState(document.body.all["DECMD_MOVEDOWNSECTION"], "gray");
		  TBSetState(document.body.all["DECMD_DELETESECTION"], "gray");
		  TBSetState(document.body.all["DECMD_SECTIONPROPERTIES"], "gray");
		  TBSetState(document.body.all["DECMD_NEWVARIANT"], "gray");
		  TBSetState(document.body.all["DECMD_NEWSECTION"], "gray");
		  TBSetState(document.body.all["MENU_FILE_SAVE"], "gray");
		  TBSetState(document.body.all["DECMD_FINDTEXT"], "gray");

	 } else {

		  for (i = 0; i < QueryStatusToolbarButtons.length; i++) {
			   if (document.body.all["DECMD_SHOWHTML"].tbstate == "checked") {
					TBSetState(QueryStatusToolbarButtons[i].element, "gray");
			   } else {
					if (QueryStatusToolbarButtons[i].command == null) {
						 TBSetState(QueryStatusToolbarButtons[i].element, "unchecked");
					} else {
						 s = tbContentElement.QueryStatus(QueryStatusToolbarButtons[i].command);
						 if (s == DECMDF_DISABLED || s == DECMDF_NOTSUPPORTED) {
							  TBSetState(QueryStatusToolbarButtons[i].element, "gray");
						 } else if (s == DECMDF_ENABLED  || s == DECMDF_NINCHED) {
							  TBSetState(QueryStatusToolbarButtons[i].element, "unchecked");
						 } else { // DECMDF_LATCHED
							  TBSetState(QueryStatusToolbarButtons[i].element, "checked");
						 }
					}
			   }
		  }

		  if (document.body.all["DECMD_SHOWHTML"].tbstate == "checked") {
			   ParagraphStyle.disabled = true;
			   FontName.disabled = true;
			   FontSize.disabled = true;
		  } else {
			   s = tbContentElement.QueryStatus(DECMD_GETBLOCKFMT);
			   if (s == DECMDF_DISABLED || s == DECMDF_NOTSUPPORTED) {
					ParagraphStyle.disabled = true;
			   } else {
					ParagraphStyle.disabled = false;
					ParagraphStyle.value = tbContentElement.ExecCommand(DECMD_GETBLOCKFMT, OLECMDEXECOPT_DODEFAULT);
			   }
			   s = tbContentElement.QueryStatus(DECMD_GETFONTNAME);
			   if (s == DECMDF_DISABLED || s == DECMDF_NOTSUPPORTED) {
					FontName.disabled = true;
			   } else {
					FontName.disabled = false;
					FontName.value = tbContentElement.ExecCommand(DECMD_GETFONTNAME, OLECMDEXECOPT_DODEFAULT);
			   }

			   if (s == DECMDF_DISABLED || s == DECMDF_NOTSUPPORTED) {
					FontSize.disabled = true;
			   } else {
					FontSize.disabled = false;
					FontSize.value = tbContentElement.ExecCommand(DECMD_GETFONTSIZE, OLECMDEXECOPT_DODEFAULT);
			   }
		  }
		  // Disable link creation on ControlSelection
		  // It would be nice to have it, as it seems "natural" for the user
		  // but I can't get it to work just yet. :-(

		  // FEJL !!! - udkommenteret af JHA
		  //if (tbContentElement.DOM.selection.type == 'Control') {
		  //TBSetState(document.body.all["DECMD_HYPERLINK"], "gray");
		  //}
	 }
}

function MENU_FILE_SAVE_onclick() {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		  content = tbContentElement.DOM.body.innerText;
	 else
		  content = tbContentElement.DOM.body.innerHTML;

	 content = content.replace(/&/g, '&amp;');
	 content = content.replace(/\</g, '&lt;');
	 content = content.replace(/\>/g, '&gt;');
	 logform.content.value = content;

	 logform.submit();
}

function DECMD_VISIBLEBORDERS_onclick() {
	 tbContentElement.ShowBorders = !tbContentElement.ShowBorders;
	 tbContentElement.focus();
}

function DECMD_UNORDERLIST_onclick() {
	 tbContentElement.ExecCommand(DECMD_UNORDERLIST,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_UNDO_onclick() {
	 tbContentElement.ExecCommand(DECMD_UNDO,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_UNDERLINE_onclick() {
	 tbContentElement.ExecCommand(DECMD_UNDERLINE,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_SNAPTOGRID_onclick() {
	 tbContentElement.SnapToGrid = !tbContentElement.SnapToGrid;
	 tbContentElement.focus();
}

function DECMD_SHOWDETAILS_onclick() {
	 tbContentElement.ShowDetails = !tbContentElement.ShowDetails;
	 tbContentElement.focus();
}

function DECMD_SETFORECOLOR_onclick() {
	 var arr = showModalDialog( "document_editor_selectcolor.php",
								"",
								"font-family:Verdana; font-size:12; dialogWidth:29em; dialogHeight:24em" );

	 if (arr != null) {
		  tbContentElement.ExecCommand(DECMD_SETFORECOLOR,OLECMDEXECOPT_DODEFAULT, arr);
	 }
}

function DECMD_SETBACKCOLOR_onclick() {
	 var arr = showModalDialog( "document_editor_selectcolor.php",
								"",
								"font-family:Verdana; font-size:12; dialogWidth:29em; dialogHeight:24em" );

	 if (arr != null) {
		  tbContentElement.ExecCommand(DECMD_SETBACKCOLOR,OLECMDEXECOPT_DODEFAULT, arr);
	 }
	 tbContentElement.focus();
}

function DECMD_SELECTALL_onclick() {
	 tbContentElement.ExecCommand(DECMD_SELECTALL,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_REDO_onclick() {
	 tbContentElement.ExecCommand(DECMD_REDO,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_PASTE_onclick() {
	 tbContentElement.ExecCommand(DECMD_PASTE,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_OUTDENT_onclick() {
	 tbContentElement.ExecCommand(DECMD_OUTDENT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_ORDERLIST_onclick() {
	 tbContentElement.ExecCommand(DECMD_ORDERLIST,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_MAKE_ABSOLUTE_onclick() {
	 tbContentElement.ExecCommand(DECMD_MAKE_ABSOLUTE,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_LOCK_ELEMENT_onclick() {
	 tbContentElement.ExecCommand(DECMD_LOCK_ELEMENT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_JUSTIFYRIGHT_onclick() {
	 tbContentElement.ExecCommand(DECMD_JUSTIFYRIGHT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_JUSTIFYLEFT_onclick() {
	 tbContentElement.ExecCommand(DECMD_JUSTIFYLEFT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_JUSTIFYCENTER_onclick() {
	 tbContentElement.ExecCommand(DECMD_JUSTIFYCENTER,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_ITALIC_onclick() {
	 tbContentElement.ExecCommand(DECMD_ITALIC,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_INDENT_onclick() {
	 tbContentElement.ExecCommand(DECMD_INDENT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_FINDTEXT_onclick() {
	 tbContentElement.ExecCommand(DECMD_FINDTEXT,OLECMDEXECOPT_PROMPTUSER);
	 tbContentElement.focus();
}

function DECMD_DELETE_onclick() {
	 tbContentElement.ExecCommand(DECMD_DELETE,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_CUT_onclick() {
	 tbContentElement.ExecCommand(DECMD_CUT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_COPY_onclick() {
	 tbContentElement.ExecCommand(DECMD_COPY,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function DECMD_BOLD_onclick() {
	 tbContentElement.ExecCommand(DECMD_BOLD,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function OnMenuShow(QueryStatusArray, menu) {
	 var i, s;
 
	 for (i=0; i<QueryStatusArray.length; i++) {
		  s = tbContentElement.QueryStatus(QueryStatusArray[i].command);
		  if (s == DECMDF_DISABLED || s == DECMDF_NOTSUPPORTED) {
			   TBSetState(QueryStatusArray[i].element, "gray");
		  } else if (s == DECMDF_ENABLED  || s == DECMDF_NINCHED) {
			   TBSetState(QueryStatusArray[i].element, "unchecked");
		  } else { // DECMDF_LATCHED
			   TBSetState(QueryStatusArray[i].element, "checked");
		  }
	 }

	 // If the menu is the HTML menu, then
	 // check if the selection type is "Control", if so,
	 // set menu item state of the Intrinsics submenu and rebuild the menu.
	 /*if (QueryStatusArray[0].command == DECMD_HYPERLINK) { 
	   for (i=0; i < HTML_INTRINSICS.all.length; i++) {
	   if (HTML_INTRINSICS.all[i].className == "tbMenuItem") {
	   if (tbContentElement.DOM.selection.type == "Control") {
	   TBSetState(HTML_INTRINSICS.all[i], "gray");
	   } else {
	   TBSetState(HTML_INTRINSICS.all[i], "unchecked");
	   }
	   }
	   }
	   }*/

	 // rebuild the menu so that menu item states will be reflected
	 TBRebuildMenu(menu, true);

	 tbContentElement.focus();
}

function INTRINSICS_insert(html) {
	 var selection;

	 selection = tbContentElement.DOM.selection.createRange();
	 selection.pasteHTML(html);
	 tbContentElement.focus();
}

function INTRINSICS_onclick(html) {
	 var selection;
	 if (html) {
		  selection = tbContentElement.DOM.selection.createRange();
		  selection.pasteHTML(html);
	 }
	 tbContentElement.focus();
}

function TABLE_DELETECELL_onclick() {
	 tbContentElement.ExecCommand(DECMD_DELETECELLS,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_DELETECOL_onclick() {
	 tbContentElement.ExecCommand(DECMD_DELETECOLS,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_DELETEROW_onclick() {
	 tbContentElement.ExecCommand(DECMD_DELETEROWS,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_INSERTCELL_onclick() {
	 tbContentElement.ExecCommand(DECMD_INSERTCELL,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_INSERTCOL_onclick() {
	 tbContentElement.ExecCommand(DECMD_INSERTCOL,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_INSERTROW_onclick() {
	 tbContentElement.ExecCommand(DECMD_INSERTROW,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_INSERTTABLE_onclick() {
	 InsertTable();
	 tbContentElement.focus();
}

function TABLE_MERGECELL_onclick() {
	 tbContentElement.ExecCommand(DECMD_MERGECELLS,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TABLE_SPLITCELL_onclick() {
	 tbContentElement.ExecCommand(DECMD_SPLITCELL,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function FORMAT_FONT_onclick() {
	 tbContentElement.ExecCommand(DECMD_FONT,OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function ZORDER_ABOVETEXT_onclick() {
	 tbContentElement.ExecCommand(DECMD_BRING_ABOVE_TEXT, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function ZORDER_BELOWTEXT_onclick() {
	 tbContentElement.ExecCommand(DECMD_SEND_BELOW_TEXT, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function ZORDER_BRINGFORWARD_onclick() {
	 tbContentElement.ExecCommand(DECMD_BRING_FORWARD, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function ZORDER_BRINGFRONT_onclick() {
	 tbContentElement.ExecCommand(DECMD_BRING_TO_FRONT, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function ZORDER_SENDBACK_onclick() {
	 tbContentElement.ExecCommand(DECMD_SEND_TO_BACK, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function ZORDER_SENDBACKWARD_onclick() {
	 tbContentElement.ExecCommand(DECMD_SEND_BACKWARD, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.focus();
}

function TOOLBARS_onclick(toolbar, menuItem) {
	 if (toolbar.TBSTATE == "hidden") {
		  TBSetState(toolbar, "dockedTop");
		  TBSetState(menuItem, "checked");
	 } else {
		  TBSetState(toolbar, "hidden");
		  TBSetState(menuItem, "unchecked");
	 }

	 TBRebuildMenu(menuItem.parentElement, false);

	 tbContentElement.focus();
}

function removeformatting(parent) {
	 var tag = parent.tagName;
	 if (tag == 'SPAN' || tag == 'P') {
		  parent.removeAttribute('style', false);
		  parent.removeAttribute('className', false);
	 }

	 if (tag == 'FONT') {
		  parent.removeAttribute('name', false);
		  parent.removeAttribute('size', false);
	 }

	 var children = parent.children;
	 var i;
	 for (i=0; i<children.length; ++i) {
		  removeformatting(children[i]);
	 }
}

function ParagraphStyle_onchange() {
	 var selection = tbContentElement.DOM.selection.createRange();
	 var parent = selection.parentElement();
	 removeformatting(parent);
	 tbContentElement.ExecCommand(DECMD_REMOVEFORMAT, OLECMDEXECOPT_DODEFAULT);
	 tbContentElement.ExecCommand(DECMD_SETBLOCKFMT, OLECMDEXECOPT_DODEFAULT, ParagraphStyle.value);
	 tbContentElement.focus();
}

function FontName_onchange() {
	 tbContentElement.ExecCommand(DECMD_SETFONTNAME, OLECMDEXECOPT_DODEFAULT, FontName.value);
	 tbContentElement.focus();
}

function FontSize_onchange() {
	 tbContentElement.ExecCommand(DECMD_SETFONTSIZE, OLECMDEXECOPT_DODEFAULT, parseInt(FontSize.value));
	 tbContentElement.focus();
}

function tbContentElement_DocumentComplete() {

	 initialDocComplete = true;
	 docComplete = true;
}

function SHOW_HTML_onclick() {
	 var state  = document.body.all["DECMD_SHOWHTML"].tbstate;
	 if (state == "checked") {
		  document.body.all["FILE"].TBSTATE = 'unchecked';
		  document.body.all["FILE"].style.color = 'black';

		  document.body.all["EDIT"].TBSTATE = 'unchecked';
		  document.body.all["EDIT"].style.color = 'black';

		  document.body.all["VIEW"].TBSTATE = 'unchecked';
		  document.body.all["VIEW"].style.color = 'black';

		  document.body.all["FORMAT"].TBSTATE = 'unchecked';
		  document.body.all["FORMAT"].style.color = 'black';

		  document.body.all["HTML"].TBSTATE = 'unchecked';
		  document.body.all["HTML"].style.color = 'black';

		  tbContentElement.DOM.body.innerHTML = tbContentElement.DOM.body.innerText;
		  document.body.all["DECMD_SHOWHTML"].tbstate="unchecked";
	 } else {
		  document.body.all["FILE"].TBSTATE = 'gray';
		  document.body.all["FILE"].style.color = 'gray';

		  document.body.all["EDIT"].TBSTATE = 'gray';
		  document.body.all["EDIT"].style.color = 'gray';

		  document.body.all["VIEW"].TBSTATE = 'gray';
		  document.body.all["VIEW"].style.color = 'gray';

		  document.body.all["FORMAT"].TBSTATE = 'gray';
		  document.body.all["FORMAT"].style.color = 'gray';

		  document.body.all["HTML"].TBSTATE = 'gray';
		  document.body.all["HTML"].style.color = 'gray';

		  tbContentElement.DOM.body.innerText = tbContentElement.DOM.body.innerHTML;
		  document.body.all["DECMD_SHOWHTML"].tbstate="checked";
	 }
	 tbContentElement.focus();
}

function VARIANT_NUMBER_onchange() {
	 var secnr = VariantNumber.options[VariantNumber.selectedIndex].value;
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.view.value = 'changevariant';
	 logform.nextparam.value = secnr;
	 logform.submit();
}

function VARIANT_BACK_onclick(secnr) {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		  content = tbContentElement.DOM.body.innerText;
	 else 
		  content = tbContentElement.DOM.body.innerHTML;
  		
	 content = content.replace(/&/g, '&amp;');
	 content = content.replace(/\</g, '&lt;');
	 content = content.replace(/\>/g, '&gt;');
	 logform.content.value = content;

	 logform.cmd.value = '';
	 logform.nextcmd.value = 'edit';
	 logform.nextcmdparam.value = secnr;
	 logform.submit();
}

function SECTION_NEW_onclick() {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.cmd.value = logform.cmd.value + ',createblank';
	 logform.submit();
}

function SECTION_NEWBELOW_onclick() {
	 var secnr = SectionNumber.options[SectionNumber.selectedIndex].value;
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.cmd.value = logform.cmd.value + ',createbelow';
	 logform.nextparam.value = secnr;
	 logform.submit();
}

function SECTION_NUMBER_onchange() {
	 var secnr = SectionNumber.options[SectionNumber.selectedIndex].value;
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;

	 logform.view.value = 'changesection';
	 logform.nextparam.value = secnr;
	 logform.submit();
}

function SECTION_MOVEUP_onclick() {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.cmd.value = logform.cmd.value + ',moveup';
	 logform.submit();
}

function SECTION_MOVEDOWN_onclick() {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.cmd.value = logform.cmd.value + ',movedown';
	 logform.submit();
}


function SECTION_DELETE_onclick() {
	var confirmstring = "Er du sikker på at du vil slette denne sektion?";
		
	if (confirm(confirmstring)) {
		logform.cmd.value = 'delete';
		logform.submit();
	}
}

function REQUESTAPPROVAL_onclick() {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.cmd.value = logform.cmd.value + ',requestapproval';
	 logform.view.value = 'jswindowclose';
	 logform.submit();
}

function APPROVEPUBLISH_onclick() {
	 var content = "";
	 if (state  = document.body.all["DECMD_SHOWHTML"].tbstate == "checked")
		logform.content.value = tbContentElement.DOM.body.innerText;
	 else 
		logform.content.value = tbContentElement.DOM.body.innerHTML;
  		
	 logform.cmd.value = logform.cmd.value + ',approvepublish';
	 logform.view.value = 'jswindowclose';
	 logform.submit();
}

/*function SECTION_PROPERTIES_onclick() {

	 args = new Array();
	 args["name"] = logform.name.value;
	 args["subname"] = logform.subname.value;
	 args["extension"] = logform.extension.value;
	 args["configset"] = logform.configset.value;
	 args["params"] = logform.params.value;
	 args["script"] = logform.script.value;
	 args["objectid"] = logform.objectid.value;
	
	 values = showModalDialog("editor/documentsectionproperties.php",
							  args,
							  "font-family: Verdana; font-size: 12pt; dialogWidth: 440px; dialogHeight:250px; help: no; status: no");
	 if (values) {
		  logform.name.value = values["name"];
		  logform.subname.value = values["subname"];
		  logform.extension.value = values["extension"];
		  logform.configset.value = values["configset"];
		  logform.params.value = values["params"];
		  logform.script.value = values["script"];
		  var sectionNumber = document.getElementById('sectionNumber');
		  var selectedIndex = sectionNumber.selectedIndex;
		  sectionNumber.options[sectionNumber.selectedIndex] = new Option((sectionNumber.selectedIndex + 1) + " - " + values["name"], sectionNumber.selectedIndex + 1);
		  sectionNumber.selectedIndex = selectedIndex;
	 }
	
}*/

function TABLE_PROPERTIES_onclick() {
	 var args = new Array();
	
	 if (tbContentElement.DOM.selection.type == "Control") {
		  var controlRange = tbContentElement.DOM.selection.createRange();
		  for (i = 0; i < controlRange.length; i++) {
			   if (controlRange.item(i).tagName == 'TABLE') {
					//args["cellpadding"] = controlRange.item(i).cellPadding;
					//args["cellspacing"] = controlRange.item(i).cellSpacing;
					args["bgcolor"] = controlRange.item(i).style.backgroundColor;

					var tbody = controlRange.item(i).childNodes[0];
					var trs = tbody.childNodes;
					for (trIndex = 0; trIndex < trs.length; trIndex++) {
						 var tds = trs[trIndex].childNodes;
						 if (tds[0].tagName == 'TD') {
							  args["border"] = tds[0].style.borderWidth;
							  args["bordercolor"] = tds[0].style.borderColor;
							  args["cellspacing"] = tds[0].style.margin;
							  args["cellpadding"] = tds[0].style.padding;
						
						 }
					}
			   }
		  }
	 }

	 values = showModalDialog("document_editor_edittable.php", 
							  args, 
							  "font-family: Verdana; font-size: 12pt; dialogWidth: 330px; dialogHeight:205px; help: no; status: no; scroll: no");
	
	 if (values != null) {
		  if (tbContentElement.DOM.selection.type == "Control") {
			   var controlRange = tbContentElement.DOM.selection.createRange();
			   for (i = 0; i < controlRange.length; i++) {
					if (controlRange.item(i).tagName == 'TABLE') {
						 var table = controlRange.item(i);
						 //table.cellPadding = values["cellpadding"];
						 //table.cellSpacing = values["cellspacing"];
						 table.style.backgroundColor = values["bgcolor"];
						 table.style.borderCollapse = "collapse";
						 var tbody = table.childNodes[0];
						 var trs = tbody.childNodes;
						 var borderStyle = "";
						 if (values["border"] > 0) {
							  borderStyle = values["border"] + "px solid " + values["bordercolor"];
						 }
						 for (trIndex = 0; trIndex < trs.length; trIndex++) {
							  var tds = trs[trIndex].childNodes;
							  for (tdIndex = 0; tdIndex < tds.length; tdIndex++) {
								   tds[tdIndex].style.border = borderStyle;
								   tds[tdIndex].style.margin = values["cellspacing"] + "px";
								   tds[tdIndex].style.padding = values["cellpadding"] + "px";
							  }
						 }
					}
			   }
		  }
	 }
}

function DECMD_ANCHOR_onclick() {

	 if (tbContentElement.queryStatus(DECMD_HYPERLINK) == DECMDF_ENABLED) {
		  var url = '';
		  var selectedText = tbContentElement.DOM.selection.createRange();
		  var html = selectedText.htmlText;
		  var matches = html.match(/<a name=\"{0,1}([^(\"|>)]*)\"{0,1}[^>]*>/i);
		  if (matches) {
			   url = matches[1];;
		  }

		  var name = showModalDialog("editor/insertAnchor.html",
									 url,
									 "font-family: Verdana; font-size: 12pt; dialogWidth: 310px; dialogHeight:115px; help: no; status: no; scroll: no");

		  if (name != null) {
			   selectedText.pasteHTML(html.anchor(name));
		  }
	 }

}

