<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");	
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/

	
	@import_request_variables('cgps');
	$PHP_SELF=$_SERVER['PHP_SELF'];
	if(!isset($S)){$S=0;}
	include "iware.php";
	$IW = new IWARE ();	
	$IW->maybeOpenLogInWindow();

?>
<html>
<head>
<title>iWare Professional Version <?php echo IWARE_VERSION; ?></title>
<script language="JavaScript" src="iware.js"></script>
<link rel="stylesheet" href="iware.css"></link>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
	body, td  { font-family: arial; font-size: x-small; }
	a         { color: #0000BB; text-decoration: none; }
	a:hover   { color: #FF0000; text-decoration: underline; }
	.headline { font-family: arial black, arial; font-size: 28px; letter-spacing: -1px; }
	.headline2{ font-family: verdana, arial; font-size: 12px; }
	.subhead  { font-family: arial, arial; font-size: 18px; font-weight: bold; font-style: italic; }
	.backtotop     { font-family: arial, arial; font-size: xx-small;  }
	.code     { background-color: #EEEEEE; font-family: Courier New; font-size: x-small;
	margin: 5px 0px 5px 0px; padding: 5px;
	border: black 1px dotted;
	}
	font { font-family: arial black, arial; font-size: 28px; letter-spacing: -1px; }
</style>
<script language="Javascript1.2">
function editor_defaultConfig(objname) {

this.version = "2.04"
this.width =  "auto";
this.height = "auto";
this.bodyStyle = 'background-color: #FFFFFF; font-family: "Verdana"; font-size: x-small;';
this.imgURL = _editor_url + 'images/';
this.debug  = 0;
this.replaceNextlines = 0;
this.plaintextInput = 0;

this.toolbar = [
    ['fontname'],
    ['fontsize'],
    ['linebreak'],
    ['bold','italic','underline','separator'],
    ['strikethrough','subscript','superscript','separator'],
    ['justifyleft','justifycenter','justifyright','separator'],
    ['OrderedList','UnOrderedList','Outdent','Indent','separator'],
    ['forecolor','backcolor','separator'],
    ['HorizontalRule','Createlink','InsertImage','MediaLibrary','InsertTable','htmlmode','separator'],
	];

this.fontnames = {
	<?php
		$fonts=$IW->GetFontsArray ();
		for($i=0;$i<count($fonts);$i++){echo "\"".trim($fonts[$i])."\": \"".trim($fonts[$i])."\",\n";}
		echo "\"Default\": \"Default\"\n";
	?>
	};

this.fontsizes = {
    "1 (8 pt)":  "1",
    "2 (10 pt)": "2",
    "3 (12 pt)": "3",
    "4 (14 pt)": "4",
    "5 (18 pt)": "5",
    "6 (24 pt)": "6",
    "7 (36 pt)": "7"
  };

this.fontstyles = [];

this.btnList = {          
    "bold":           ['Bold',                 'Bold',               'editor_action(this.id)',  'ed_format_bold.gif'],
    "italic":         ['Italic',               'Italic',             'editor_action(this.id)',  'ed_format_italic.gif'],
    "underline":      ['Underline',            'Underline',          'editor_action(this.id)',  'ed_format_underline.gif'],
    "strikethrough":  ['StrikeThrough',        'Strikethrough',      'editor_action(this.id)',  'ed_format_strike.gif'],
    "subscript":      ['SubScript',            'Subscript',          'editor_action(this.id)',  'ed_format_sub.gif'],
    "superscript":    ['SuperScript',          'Superscript',        'editor_action(this.id)',  'ed_format_sup.gif'],
    "justifyleft":    ['JustifyLeft',          'Justify Left',       'editor_action(this.id)',  'ed_align_left.gif'],
    "justifycenter":  ['JustifyCenter',        'Justify Center',     'editor_action(this.id)',  'ed_align_center.gif'],
    "justifyright":   ['JustifyRight',         'Justify Right',      'editor_action(this.id)',  'ed_align_right.gif'],
    "orderedlist":    ['InsertOrderedList',    'Ordered List',       'editor_action(this.id)',  'ed_list_num.gif'],
    "unorderedlist":  ['InsertUnorderedList',  'Bulleted List',      'editor_action(this.id)',  'ed_list_bullet.gif'],
    "outdent":        ['Outdent',              'Decrease Indent',    'editor_action(this.id)',  'ed_indent_less.gif'],
    "indent":         ['Indent',               'Increase Indent',    'editor_action(this.id)',  'ed_indent_more.gif'],
    "forecolor":      ['ForeColor',            'Font Color',         'editor_action(this.id)',  'ed_color_fg.gif'],
    "backcolor":      ['BackColor',            'Background Color',   'editor_action(this.id)',  'ed_color_bg.gif'],
    "horizontalrule": ['InsertHorizontalRule', 'Horizontal Rule',    'editor_action(this.id)',  'ed_hr.gif'],
    "createlink":     ['CreateLink',           'Insert Hyperlink',    'editor_action(this.id)',  'ed_link.gif'],
    "insertimage":    ['InsertImage',          'Insert Image from URL',       'editor_action(this.id)',  'ed_image.gif'],
    "inserttable":    ['InsertTable',          'Insert Table',       'editor_action(this.id)',  'insert_table.gif'],
    "htmlmode":       ['HtmlMode',             'View HTML Source',   'editor_setmode(\''+objname+'\')', 'ed_html.gif'],
    "popupeditor":    ['popupeditor',          'Enlarge Editor',     'editor_action(this.id)',  'fullscreen_maximize.gif'],
    "about":          ['about',                'About this editor',  'editor_about(\''+objname+'\')',  'ed_about.gif'],
    "medialibrary":           ['MediaLibrary',         'Insert Image from Media Library',  'editor_action(this.id)',  'ed_image.gif'],
    "custom2":           ['custom2',         'Purpose of button 2',  'editor_action(this.id)',  'ed_custom.gif'],
    "custom3":           ['custom3',         'Purpose of button 3',  'editor_action(this.id)',  'ed_custom.gif'],
    "help":           ['showhelp',             'Help using editor',  'editor_action(this.id)',  'ed_help.gif']};
}
</script>
<script language="Javascript1.2">
	_editor_url = "";
	var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
	if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
	if (win_ie_ver >= 5.5) 
		{
		 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
		 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
		} 
	else 
		{ 
		document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>');
		}
	function createDocument ()
		{
		if(document.editForm.link_text.value.length<1)
			{alert('You must enter the link text to be displayed for this document.');return false;}		
		document.editForm.submit();
		}
</script>
</head>
<?php $GUI->PageBody (); ?>
<center>
<?php
	if(!isset($mode)){$mode=0;}
	switch($mode)
		{
		case 0:
			$GUI->OpenForm("editForm","docs.php?S=1","return false;");
			$GUI->OpenWidget(EDITOR_1,2);
			echo "<table border=0 cellspacing=10>";
			echo "<tr><td valign=top>".$GUI->Label(EDITOR_2)."<br />";
			echo $GUI->TextBox("link_text","",30)."<br />";
			echo $GUI->CheckBox("is_hidden",0,1)." ".$GUI->Label(EDITOR_3)."<br />";
			echo $GUI->CheckBox("is_sublink",1)." ".$GUI->Label(EDITOR_4)."  ";
			$GUI->OpenListBox("parent_id",1);
			$IW->Docs_ListBox ();
			$GUI->CloseListBox ();
			echo "<br /><br />";
			echo $GUI->CheckBox("use_mod",1)." ".$GUI->Label(EDITOR_5)."  ";
			$GUI->OpenListBox("module",1);
			$IW->Mod_ListBox ();
			$GUI->CloseListBox ();
			echo "</td><td valign=top>";
			echo $GUI->Label(EDITOR_6)."<br />";
			echo $GUI->TextBox("meta_title","",30)."<br />";
			echo $GUI->Label(EDITOR_7)."<br />";
			echo $GUI->TextArea("meta_keywords","",2,30)."<br />";
			echo $GUI->Label(EDITOR_8)."<br />";
			echo $GUI->TextArea("meta_description","",2,30)."<br />";
			echo "</td></tr>";
			echo "<tr><td colspan=2 bgcolor=#e4e4e4><br />";
			echo "<textarea name=\"htmlSource\" rows=20 cols=80></textarea>\n";
			echo "<script language=\"javascript1.2\">editor_generate('htmlSource');</script>\n";
			echo "</td></tr>";
			echo "<tr><td colspan=2 align=center><br /><input type=\"button\" class=\"guiButton\" value=\"".EDITOR_9."\" onClick=\"createDocument()\"></td></tr>";
			echo "</table>";
			$GUI->CloseWidget ();
			$GUI->CloseForm();	
		break;
		case 1:
			$result=$IW->query("select * from ".IWARE_DOCS." where id='$id' limit 1");
			$GUI->OpenForm("editForm","docs.php?S=2&id=$id","return false;");
			$GUI->OpenWidget(EDITOR_0." : ".$IW->result($result,0,"link_text")."",2);
			echo "<table border=0 cellspacing=10>";
			echo "<tr><td valign=top>".$GUI->Label(EDITOR_2)."<br />";
			echo $GUI->TextBox("link_text",$IW->result($result,0,"link_text"),30)."<br />";
			echo $GUI->CheckBox("is_hidden",0,($IW->result($result,0,"is_hidden")==0)?1:0,30)." ".$GUI->Label(EDITOR_3)."<br />";
			echo $GUI->CheckBox("is_sublink",1,($IW->result($result,0,"parent_id")=="0")?0:1)." ".$GUI->Label(EDITOR_4)."  ";
			$GUI->OpenListBox("parent_id",1);
			if($IW->result($result,0,"parent_id")!="0")
			{$GUI->ListOption($IW->result($result,0,"parent_id"),$IW->Docs_GetName($IW->result($result,0,"parent_id")),1);}
			$IW->Docs_ListBox ();
			$GUI->CloseListBox ();
			echo "<br /><br />";
			$mod=$IW->result($result,0,"module");
			if(!empty($mod)){$hm=1;}else{$hm=0;}
			echo $GUI->CheckBox("use_mod",1,$hm)." ".$GUI->Label(EDITOR_5)."  ";
			$GUI->OpenListBox("module",1);
			$GUI->ListOption($mod,$mod,1);
			$IW->Mod_ListBox ();
			$GUI->CloseListBox ();
			echo "</td><td valign=top>";
			echo $GUI->Label(EDITOR_6)."<br />";
			echo $GUI->TextBox("meta_title",str_replace("'","",$IW->result($result,0,"meta_title")),30)."<br />";
			echo $GUI->Label(EDITOR_7)."<br />";
			echo $GUI->TextArea("meta_keywords",str_replace("'","",$IW->result($result,0,"meta_keywords")),2,30)."<br />";
			echo $GUI->Label(EDITOR_8)."<br />";
			echo $GUI->TextArea("meta_description",str_replace("'","",$IW->result($result,0,"meta_description")),2,30)."<br />";
			echo "</td></tr>";
			echo "<tr><td colspan=2 bgcolor=#e4e4e4><br />";
			echo "<textarea name=\"htmlSource\" rows=20 cols=80>".$IW->result($result,0,"doc_content")."</textarea>\n";
			echo "<script language=\"javascript1.2\">editor_generate('htmlSource');</script>\n";
			echo "</td></tr>";
			echo "<tr><td colspan=2 align=center><br /><input type=\"button\" class=\"guiButton\" value=\"".EDITOR_9."\" onClick=\"createDocument()\"></td></tr>";
			echo "</table>";
			$GUI->CloseWidget ();
			$GUI->CloseForm();
			$IW->freeResult($result);
		break;
		}
?>
</center>
<?php include "author.php"; ?>
</body>
</html>