<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## $Source: /home/cvsroot/squizlib/wysiwyg/wysiwyg.inc,v $
## $Revision: 1.55 $
## $Author: dofford $
## $Date: 2004/03/08 01:09:42 $
#######################################################################
include_once(dirname(__FILE__).'/../var_serialise/var_serialise.inc');
#######################################################################

global $SQUIZLIB_WYSIWYG_POP_UP_INITIALISED;

class wysiwyg {
	
	var $name						= 'wysiwyg';	  # name of the wysiwyg field
	var $siteid						= 0;			  # the siteid that the content belongs to
	var $pageid						= 0;			  # the pageid that the content belongs to
	var $stylesheet					= '';			  # href to the stylesheet to use
	var $styles						= Array();		  # array of the styles to place in the drop down box
	var $enable_styles				= false;		  # whether or not to let the user select different styles
	var $allow_image_uploads		= false;		  # whether or not to let the user upload an different styles
	var $allow_colour_selections	= false;		  # whether or not to let the user select font and background colours
	var $enable_keywords			= false;		  # whether or not to let the user select from a list of keyword replacements
	var $enable_source				= false;		  # whether or not to let the user view the html source
	var $enable_ccp					= false;		  # wethwe or not to show the cut, copy, and paste buttons
	var $button_text				= 'Edit WYSIWYG'; # the text on the edit button
	var $show_updated				= true;			  # whether or not to show the updated text and box
	var $link_interface				= 'full';		  # allow no linking (none), external links only(simple), or full (full)
	var $enable_spell_check			= true;
	var $enable_file_upload			= true; 
	var $enable_file_delete			= true;

	function wysiwyg($name='', $siteid=0, $pageid=0, $stylesheet='', $styles=Array(), 
					$enable_styles=false, $allow_image_uploads=false, $allow_colour_selections=false, 
					$enable_keywords=false, $enable_source=false, $link_interface='full', $enable_ccp=true, $enable_spell_check=true, $enable_file_upload=true, $enable_file_delete=true) {
		$this->name = eregi_replace('[^a-z0-9]', '_', $name);
		$this->siteid					= $siteid;
		$this->pageid					= $pageid;
		$this->stylesheet				= $stylesheet;
		$this->styles					= $styles;
		$this->enable_styles			= (isset($enable_styles)?$enable_styles:false);
		$this->allow_image_uploads		= (isset($allow_image_uploads)?$allow_image_uploads:false);
		$this->allow_colour_selections	= (isset($allow_colour_selections)?$allow_colour_selections:false);
		$this->enable_keywords			= (isset($enable_keywords)?$enable_keywords:false);
		$this->enable_source			= (isset($enable_source)?$enable_source:false);
		$this->enable_ccp				= (isset($enable_ccp)?$enable_ccp:true);
		$this->link_interface			= (isset($link_interface)?$link_interface:'full');
		$this->enable_spell_check		= (isset($enable_spell_check)?$enable_spell_check:true);
		$this->enable_file_upload		= (isset($enable_file_upload)?$enable_file_upload:true); 
		$this->enable_file_delete		= (isset($enable_file_delete)?$enable_file_delete:true);
	}

	 ####################################################
	# some code for the pop-up that is needed only once
	function init_pop_up() {
		global $SQUIZLIB_WYSIWYG_POP_UP_INITIALISED;
		if ($SQUIZLIB_WYSIWYG_POP_UP_INITIALISED) return;

		$pop_up_prefix = squizlib_href('wysiwyg', 'pop_ups');
	?>
		<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('var_serialise', 'var_serialise.js')?>"></script>
		<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','detect.js')?>"></script>
		<script language="JavaScript" type="text/javascript">

			 /////////////////////////////////////////////////
			// returns the data for initialising the pop-up
			var WYSIWYG_STYLES = null;
			function get_wysiwyg_data(name) {
				var data = new Object();
				data["html"] = get_wysiwyg_html(name);
				if (WYSIWYG_STYLES == null) {
					WYSIWYG_STYLES = var_unserialise('<?=var_serialise($this->styles, true)?>');
				}
				data["styles"] = WYSIWYG_STYLES;
				return data;
			}

			function create_wysiwyg_pop_up() {

				if (document.getElementById('wysiwyg_pop_up_<?=$this->name?>') == null) {

					var pop_up_width  = 650;
					var pop_up_height = 350;
					var pop_up_border = 0;

					var wysiwyg_pop_up = document.createElement("DIV");
					var scroll_top = parseInt(document.body.scrollTop) + 100;
					with(wysiwyg_pop_up){
						id = 'wysiwyg_pop_up_<?=$this->name?>';
						with(style) {
							position   = 'absolute';
							visibility = 'hidden';
							left   = '150px';
							top    = scroll_top    + 'px';
							width  = pop_up_width  + 'px';
							height = pop_up_height + 'px';
							borderWidth = '0px';
							borderStyle = 'none';
						}// end with
					}// end with
					var wysiwyg_pop_up_iframe = document.createElement("IFRAME");
					with(wysiwyg_pop_up_iframe){
						id = 'wysiwyg_pop_up_iframe_<?=$this->name?>';
						scrolling   = 'no';
						frameBorder = 'no';
						with(style) {
							visibility = 'inherit';
							width  = (pop_up_width  - pop_up_border) + 'px';
							height = (pop_up_height - pop_up_border) + 'px';
							backgroundColor = 'blue';
							borderWidth = '0px';
							borderStyle = 'none';
						}// end with
					}// end with
					wysiwyg_pop_up.appendChild(wysiwyg_pop_up_iframe);
					document.body.appendChild(wysiwyg_pop_up);
				}// end if

			}// end create_wysiwyg_pop_up()

			var wysiwyg_form = null
			function edit_wysiwyg(form, name, enable_styles, allow_image_uploads, allow_colour_selections, enable_keywords, enable_source, link_interface, enable_ccp, enable_spell_check, enable_file_upload, enable_file_delete) {
				wysiwyg_form = form;
				create_wysiwyg_pop_up();
				var pop_up = document.getElementById('wysiwyg_pop_up_<?=$this->name?>');
				var pop_up_iframe = document.getElementById('wysiwyg_pop_up_iframe_<?=$this->name?>');
				var page_width  = parseInt(pop_up.style.width)  - parseInt(pop_up.style.borderLeftWidth) - parseInt(pop_up.style.borderRightWidth);
				var page_height = parseInt(pop_up.style.height) - parseInt(pop_up.style.borderTopWidth)  - parseInt(pop_up.style.borderBottomWidth);
				var src = '<?=addslashes($pop_up_prefix)?>/wysiwyg.php?browser=' + ((is_dom) ? "dom" : ((is_ie4up) ? "ie" : "ns"))
							+ '&siteid=<?=urlencode($this->siteid)?>'
							+ '&pageid=<?=urlencode($this->pageid)?>'
							+ '&stylesheet=<?=urlencode($this->stylesheet)?>'
							+ '&name='						+ escape(name)
							+ '&enable_styles='				+ escape(enable_styles)
							+ '&enable_keywords='			+ escape(enable_keywords)
							+ '&enable_source='				+ escape(enable_source)
							+ '&enable_ccp='				+ escape(enable_ccp)
							+ '&allow_image_uploads='		+ escape(allow_image_uploads)
							+ '&allow_colour_selections='	+ escape(allow_colour_selections)
							+ '&link_interface='			+ escape(link_interface)
							+ '&page_width='				+ escape(page_width)
							+ '&page_height='				+ escape(page_height)
							+ '&enable_spell_check='		+ escape(enable_spell_check)
							+ '&enable_file_upload='		+ escape(enable_file_upload)
							+ '&enable_file_delete='		+ escape(enable_file_delete)
							+ '&embeded='					+ '0';
				pop_up.style.visibility = 'visible';
				pop_up_iframe.src = src;
			}// end edit_wysiwyg()

			function close_wysiwyg() {
				var pop_up = document.getElementById('wysiwyg_pop_up_<?=$this->name?>');
				var pop_up_iframe = document.getElementById('wysiwyg_pop_up_iframe_<?=$this->name?>');
				pop_up_iframe.src = '<?=addslashes($pop_up_prefix)?>/blank.php';
				pop_up.style.visibility = 'hidden';
			}// end close_wysiwyg()

			function get_wysiwyg_html(name) {
				return wysiwyg_form.elements['squizlib_wysiwyg_content[' + name + ']'].value;
			}// end get_wysiwyg_html()

			// this is a copy of the function in squizlib/bodycopy/bodycopy.inc
			function check_external_links(html) {
				// we'll target specific extensions - we can't target a number of chars (for eg)
				// since if you put a reference to a site rather than a page it might end in
				// .net, .com, .org.
				var re = /http:\/\/(.*).(js|jpg|jpeg|gif|png|pdf|doc|zip|htm|html|php)/gi;
				if (re.test(html)) {
					alert('Warning: You have a link to an external document. This may move later on and cause issues for your site.');
				}
			} // end check_external_links()

			function save_wysiwyg_html(name, html) {
				// only if a change has occured
				if (wysiwyg_form.elements['squizlib_wysiwyg_content[' + name + ']'].value != html) {
					check_external_links(html);
					wysiwyg_form.elements['squizlib_wysiwyg_content[' + name + ']'].value = html;
					<? if (!$this->embeded && $this->show_updated) { ?>
					var changed_image = document.getElementById('wysiwyg_changed_' + name);
					changed_image.src = "<?=squizlib_href('wysiwyg', 'images/tick.gif')?>";
					<? } ?>
				}
				<? if (!$this->embeded) { ?>
				close_wysiwyg();
				<? } ?>
			}// end save_wysiwyg_html()

			function get_wysiwyg_pop_up_visibility() {
				var pop_up = document.getElementById('wysiwyg_pop_up_<?=$this->name?>');
				return (pop_up.style.visibility == 'visible');
			}// end get_wysiwyg_pop_up_visibilty()

		</script>
	<?
		$SQUIZLIB_WYSIWYG_POP_UP_INITIALISED = true;

	}#end init_pop_up()

	 ###########################################################
	# initiates the edit form element for this wysiwyg
	function paint_edit($html='', $have_button=true) {
		global $WEB_PATH;
		$this->init_pop_up();
		?><input type="hidden" name="squizlib_wysiwyg_content[<?=$this->name?>]" value="<?=htmlspecialchars($html)?>"><?
		if ($have_button) {
		?>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="button" value="<?=$this->button_text?>" onClick="javascript: edit_wysiwyg(this.form, '<?=addslashes(htmlspecialchars($this->name))?>', <?=(int)$this->enable_styles?>, <?=(int)$this->allow_image_uploads?>, <?=(int)$this->allow_colour_selections?>, <?=(int)$this->enable_keywords?>, <?=(int)$this->enable_source?>, '<?=$this->link_interface?>', '<?=$this->enable_ccp?>', '<?=$this->enable_spell_check?>', '<?=$this->enable_file_upload?>', '<?=$this->enable_file_delete?>');"></td>
					<? if ($this->show_updated) { ?>
					<td>&nbsp;&nbsp;Updated ?</td>
					<td><img id="wysiwyg_changed_<?=$this->name?>" src="<?=squizlib_href('wysiwyg', 'images/cross.gif')?>" width="15" height="15" border="0" align="bottom"></td>
					<? } ?>
				</tr>
			</table>
		<?
		}
	}#end paint_edit()


	function paint_embeded($html='', $width=500, $height=300) {
		$this->embeded = true;
		$this->init_pop_up();
		$pop_up_prefix = squizlib_href('wysiwyg', 'pop_ups');
		$html = str_replace(";","\;",htmlspecialchars(addslashes($html)));
		$html = preg_replace("([\r\n])",'',$html);
		?>
		<script language="Javascript">
			if (is_ie5_5up && is_win32) {
				document.writeln('<input type="hidden" name="squizlib_wysiwyg_content[<?=$this->name?>]" value="<?=$html?>">');
				document.writeln('<DIV ID="wysiwyg_pop_up_<?=$this->name?>"><IFRAME NAME="wysiwyg_pop_up_iframe_<?=$this->name?>" ID="wysiwyg_pop_up_iframe_<?=$this->name?>" SCROLLING="No" WIDTH="<?=(int)$width?>" HEIGHT="<?=(int)$height?>" MARGINWIDTH=0 MARGINHEIGHT=0 FRAMEBORDER="No"></IFRAME></DIV>');
				document.writeln('<script language="Javascript">');
				document.writeln('wysiwyg_form = document.edit_form;');
				document.writeln('var src = \'<?=addslashes($pop_up_prefix)?>/wysiwyg.php?browser=\' + ((is_dom) ? "dom" : ((is_ie4up) ? "ie" : "ns"))');
				document.writeln('+ \'&siteid=<?=urlencode($this->siteid)?>\'');
				document.writeln('+ \'&pageid=<?=urlencode($this->pageid)?>\'');
				document.writeln('+ \'&stylesheet=<?=urlencode($this->stylesheet)?>\'');
				document.writeln('+ \'&name=\'						+ \'<?=addslashes(htmlspecialchars($this->name))?>\'');
				document.writeln('+ \'&enable_styles=\'				+ \'<?=(int)$this->enable_styles?>\'');
				document.writeln('+ \'&enable_keywords=\'			+ \'<?=(int)$this->enable_keywords?>\'');
				document.writeln('+ \'&enable_source=\'				+ \'<?=(int)$this->enable_source?>\'');
				document.writeln('+ \'&enable_ccp=\'         		+ \'<?=(int)$this->enable_ccp?>\'');
				document.writeln('+ \'&allow_image_uploads=\'		+ \'<?=(int)$this->allow_image_uploads?>\'');
				document.writeln('+ \'&allow_colour_selections=\'	+ \'<?=(int)$this->allow_colour_selections?>\'');
				document.writeln('+ \'&page_width=\'				+ \'<?=(int)$width?>\'');
				document.writeln('+ \'&page_height=\'				+ \'<?=(int)$height?>\'');
				document.writeln('+ \'&embeded=\'					+ \'1\'');
				document.writeln('+ \'&enable_spell_check=\'	+ \'<?=(int)$this->enable_spell_check?>\'');
				document.writeln('+ \'&enable_file_upload=\'	+ \'<?=(int)$this->enable_file_upload?>\'');
				document.writeln('+ \'&enable_file_delete=\'	+ \'<?=(int)$this->enable_file_delete?>\'');
				document.writeln('+ \'&link_interface=\'			+ \'<?=$this->link_interface?>\';');
				document.writeln('var pop_up_iframe_<?=$this->name?> = document.getElementById(\'wysiwyg_pop_up_iframe_<?=$this->name?>\');');
				document.writeln('var pop_up_<?=$this->name?> = document.getElementById(\'wysiwyg_pop_up_<?=$this->name?>\');');
				document.writeln('pop_up_<?=$this->name?>.style.visibility = \'visible\';');
				document.writeln('pop_up_iframe_<?=$this->name?>.src = src;');
				document.writeln('</' + 'script>');
			} else {
				document.writeln('<textarea rows="10" cols="50" name="squizlib_wysiwyg_content[<?=$this->name?>]"><?=$html?></textarea>');
			}
		</script>
		<?
	}#end paint_embeded()

	 ###########################################################
	# returns the html for this wysiwyg that was posted in form
	function get_html() {
		return gpc_stripslashes($_REQUEST['squizlib_wysiwyg_content'][$this->name]);
	}#end get_html()

	  #######################################################################################
	 # Prints out the full page WYSIWYG editing interface
	function paint_popup() {
	?> 
		<html>
		<head>
		<style type="text/css">
			body { 
				background-color: #212E61;
			}
			td { 
				font-family: Arial, Verdana, Sans-Serif; 
				font-size: 12px; 
			}
			.bodycopy-popup-heading {
				font-family: Arial, Verdana, Sans-Serif; 
				font-size: 12px; 
				font-weight: bold;
			}
			.bodycopy-popup-table { 
				background-color: #C0C0C0;
			}
		</style>
	<? 
		if ($_GET['stylesheet']) { 
		?> 
			<link rel="stylesheet" href="<?=$_GET['stylesheet']?>" type="text/css">
		<? 
		} #end if 

	?> 
		<script language="JavaScript" src="<?=squizlib_href('js','detect.js');?>"></script>
		<script language="JavaScript">

			if (is_ie4up || is_dom) {
				var owner = parent;
			} else {
				var owner = window;
			}// end if

		</script>
		</head>
	<?
		if ($_GET['page_width'])  $table_width  = 'width="'.$_GET['page_width'].'"';
		if ($_GET['page_height']) $table_height = 'height="'.$_GET['page_height'].'"';
	?>
		<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onload="Javascript:var loadingDiv = document.getElementById('loading_div'); loadingDiv.style.visibility='hidden';">
		<div id="loading_div" width="100%" height="100%" style="position:absolute;left:100;top:120;clip:rect(0,600,600,0);"><table><tr><td style="font-family:verdana;font-size:9px;color:white">LOADING WYSIWYG EDITOR. PLEASE WAIT...</td></tr></table></div>
		<table <?=$table_width?> <?=$table_height?> border="1">
			<tr><td valign="top" align="center" class="bodycopy-popup-table" width="100%" height="100%">
	<?
		# now print the actual WYSIWYG interface
		$this->paint_interface($_GET['siteid'],
								$_GET['pageid'],
								$_GET['page_width'], 
								'owner.get_wysiwyg_data("'.addslashes($this->name).'")', 
								'owner.save_wysiwyg_html("'.addslashes($this->name).'", html)',
								'owner.close_wysiwyg()',
								'owner.get_wysiwyg_pop_up_visibility()',
								$_GET['enable_styles'], 
								$_GET['allow_image_uploads'], 
								$_GET['allow_colour_selections'],
								$_GET['anchors_function'],
								$_GET['enable_keywords'],
								$_GET['enable_source'],
								$_GET['embeded'],
								$_GET['link_interface'],
								$_GET['enable_ccp'],
								$_GET['stylesheet'], 
								$_GET['enable_spell_check'],
								$_GET['enable_file_upload'],
								$_GET['enable_file_delete']
								);
	?>
			</td></tr>
		</table>
		</body>
		</html>
	<?

	}#end paint_popup()

	  #######################################################################################
	 # Prints out the actual WYSIWYG editing interface
	# Args :
	#	$siteid    				= the id of the site to which this content belongs
	#	$pageid    				= the id of the page to which this content belongs
	#	$page_width				= the width of the pop-up that the WYSIWYG is being shown in
	#	$get_data_function		= the js function call that will return an array with the original HTML 
	#							  and an array of styles
	#	$save_data_function		= the js function call that MUST include the argument 'html' that is the 
	#							  html to be saved
	#	$cancel_function		= the js function call that to be run when cancel is pressed
	#	$visiblilty_function	= the js function call that will return whether the javascript pop_up we are in
	#							  is visible or not
	#	$enable_styles			= whether or not to let the user select different styles
	#	$allow_image_uploads	= whether or not to let the user upload an image
	#	$allow_colour_selections= whether or not to let the user select font and background colours
	#	$anchors_function		= the js function call that returns the list of anchors on the current page
	function paint_interface($siteid=0, $pageid=0, $page_width=0, 
					$get_data_function='', $save_data_function='', $cancel_function='',
					$visibility_function='', $enable_styles=false, $allow_image_uploads=false,
					$allow_colour_selections=false, $anchors_function='', $enable_keywords=false,
					$enable_source=false, $embeded=false, $link_interface='full', $enable_ccp=false,
					$stylesheet, $enable_spell_check=true, $enable_file_upload=true, $enable_file_delete=true)
		{
		$web = &get_web_system();
		$site = $web->get_site($siteid);

		$image_prefix  = squizlib_href('wysiwyg', 'images');
		$pop_up_prefix = squizlib_href('wysiwyg', 'pop_ups');

		?> 
		<link rel="stylesheet" type="text/css" href="<?=squizlib_href('wysiwyg', 'js/syntax.css')?>">
		<script language="JavaScript" src="<?=squizlib_href('js', 'general.js');?>"></script>
		<script language="JavaScript" src="<?=squizlib_href('wysiwyg', 'js/codesweep.js');?>"></script>
		<script language="JavaScript" src="<?=squizlib_href('wysiwyg', 'js/editmode.js');?>"></script>
		<?
		  ####################################################################################
		 # NOTE: this is the original licence, but this page has been specialised           #
		#        to work in the bodycopy and may not work without modification elsewhere   #
		###################################################################################

		#################################################################################
		##
		## HTML Text Editing Component for hosting in Web Pages
		## Copyright (C) 2001  Ramesys (Contracting Services) Limited
		## 
		## This library is free software; you can redistribute it and/or
		## modify it under the terms of the GNU Lesser General Public
		## License as published by the Free Software Foundation; either
		## version 2.1 of the License, or (at your option) any later version.
		##
		## This library is distributed in the hope that it will be useful,
		## but WITHOUT ANY WARRANTY; without even the implied warranty of
		## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
		## Lesser General Public License for more details.
		##
		## You should have received a copy of the GNU LesserGeneral Public License
		## along with this program; if not a copy can be obtained from 
		##
		##    http://www.gnu.org/copyleft/lesser.html
		##
		## or by writing to:
		##
		##    Free Software Foundation, Inc.
		##    59 Temple Place - Suite 330,
		##    Boston,
		##    MA  02111-1307,
		##    USA.
		## 
		## Original Developer:
		##
		##	Austin David France
		##	Ramesys (Contracting Services) Limited
		##	Mentor House
		##	Ainsworth Street
		##	Blackburn
		##	Lancashire
		##	BB1 6AY
		##	United Kingdom
		##  email: Austin.France@Ramesys.com
		##
		## Home Page:    http://richtext.sourceforge.net/
		## Support:      http://richtext.sourceforge.net/
		## 
		#################################################################################
		?>
		<STYLE type="text/css">

		.bodytext {
			height: 150px;
			border: 2px inset buttonface; 
			padding:5px;
		}

		TD.rebar { 
			padding:0; 
			padding-bottom:2; 
			padding-top:2; 
			font-family: "MS Sans Serif"; 
			font-size: xx-small; 
			vertical-align: middle;
		}

		.rebar SPAN { 
			border-top: 1px solid buttonhighlight;
			border-left: 1px solid buttonhighlight;
			border-bottom: 1px solid buttonshadow;
			border-right: 1px solid buttonshadow;
			padding: 0; margin: 0; padding-bottom:1; padding-top:1;
		}

		.rebar_spacer { 
			border-top: 1px solid buttonhighlight;
			padding: 0; 
			margin: 0; 
			height: 4;
		}

		.rebar_start {
			margin:0;
			background-color: buttonface;
			border-top: 2px solid buttonhighlight;
			border-left: 2px solid buttonhighlight;
			border-bottom: 2px solid buttonshadow;
			border-right: 2px solid buttonshadow;
			width: 3px;
		}
		SPAN.rebar_sep {
			margin:0;
			background-color: buttonface;
			border-top: 1px solid buttonshadow;
			border-left: 1px solid buttonshadow;
			border-bottom: 1px solid buttonhighlight;
			border-right: 1px solid buttonhighlight;
			width: 2px;
		}

		SPAN.label { 
			border:0; 
			position:relative; 
			top: -1;
		}

		INPUT.rebar_button {
			border: 1px solid buttonface;
			height: 20;	
		}

		SELECT.rebar_button {
			border: 1px solid buttonface;
			font-size: xx-small;
			height: 75%;
		}

		IMG.rebar_button {
			border: 1px solid buttonface; 
			margin: 0; 
			padding: 0;
		}

		IMG.rebar_button_hover {
			border-top: 1px solid buttonhighlight;
			border-left: 1px solid buttonhighlight;
			border-bottom: 1px solid buttonshadow;
			border-right: 1px solid buttonshadow;

		}
		IMG.rebar_button_down {
			border-bottom: 1px solid buttonhighlight;
			border-right: 1px solid buttonhighlight;
			border-top: 1px solid buttonshadow;
			border-left: 1px solid buttonshadow;
		}

		#color { 
			position: absolute;
			width:300; height: 227;
			display: none;
			background-color: red; 
			border: 2px outset buttonface;
		}

		a:link, a:visited, a:active {
			text-decoration:underline;
		}
		</STYLE>

		<script language="JavaScript">

			var popup_initialised = false;
			var wysiwyg_editing = true; // just so that onclick link's don't execute in wysiwyg
			var INITIAL_ANCHORS = new Array(); // an array of the initial anchors in this text, used by get_anchors()
			function popup_init() {

				if (popup_initialised) return;

				var data = <?=$get_data_function?>;

				// place the initial data into the content area
				if (data["html"] != null || data["html"] != "") {

					// OK, just a bit of updating of old contents that had href onclicks that were getting
					// executed in the WYSIWYG - for more info see link()
					var e = '(onClick="javascript: )(window\\.open\\(\'[^\']+\', \'[0-9]+\', \'[^\']+\'\\);)';
					var re = new RegExp(e, "gi");
					data["html"] = data["html"].replace(re, "$1if (!window.wysiwyg_editing) { $2 }");

					doc.innerHTML = encode_anchors(make_full_hrefs(data["html"]));

					INITIAL_ANCHORS = get_local_anchors();

				}// end if
	<?
		if ($enable_styles) {
		?>
				// set up the style names for the styles
				for(var style in data["styles"]) {
					var value = "";

					if (data["styles"][style] == null) continue;

					switch(style.toLowerCase()) {
						case "h1"      : value = "Heading 1"; break;
						case "h2"      : value = "Heading 2"; break;
						case "h3"      : value = "Heading 3"; break;
						case "h4"      : value = "Heading 4"; break;
						case "h5"      : value = "Heading 5"; break;
						case "h6"      : value = "Heading 6"; break;
						case "pre"     : value = "Formatted"; break;
						case "address" : value = "Address"; break;
						default        : value = style; break;
					}// end switch

					if (value != "") {
						ctlStyle.options[ctlStyle.options.length] = new Option(data["styles"][style], value);
					}// end if

				}// end for
		<?
		}#end if enable styles
		if ($enable_keywords) {
	?>
				// set up the style names for the styles
				for(var keyword in data["keywords"]) {
					var value = data["keywords"][keyword];
					if ((typeof value) == 'string') {
						if (value.length > 75) value = value.substr(0,75) + '...';
						if (value != "" &&  value != null) {
							ctlKeywords.options[ctlKeywords.options.length] = new Option(value, "%"+keyword+"%");
						}// end if
					}
				}// end for
	<?
		} # end if enable_keywords
	?>

				init_doc();

				popup_initialised = true;

			}// end popup_init()

			function popup_save() {
				// because the editor automatically prepend the http:// business to all relative urls
				// remove them from the html

				<? if (!$embeded) { ?>
					setEditMode(true);
					setEditMode(false);
				<? } ?>

				html = unencode_anchors(make_relative_hrefs(doc.innerHTML));
				<?=$save_data_function?>;

			}

			 ////////////////////////////////////////////////////////////////////////
			// We need to prepend site href to all relative links so that when 
			// we go and save we can remove it properly to regain the relative hrefs
			//
			// We need to do this because if we leave them the WYSIWYG will automatically
			// resolve these links to full urls, and sometimes what will happen is that
			// you will be editing a page from another url, then when we go and save
			// and run make_relative_hrefs, basing all our REs on the site href,
			// everything gets buggered up. Trust Me :)
			function make_full_hrefs(html) {
				var e = '\\./(\\?[spf]=[0-9]+)';
				var re = new RegExp(e, "gi");
				html = html.replace(re, "<?=$site->get_href()?>$1");
				return html;

			}// end make_full_hrefs()

			 ////////////////////////////////////////////////////////////////////////
			// because the editor automatically prepend the http:// business to 
			// all relative urls remove them from the html
			function make_relative_hrefs(html_code) {

				var html = new String(html_code);
				var e  = '';
				var re = null;

				// now firstly remove any references to this pop-ups url
				var current_location = document.location.toString();
			
				// escape any special reg exp chars in the location
				current_location = current_location.replace(/([.?+*^$\\\[\]\(\)\{\}|])/gi, '\\$1');
				re = new RegExp(current_location, "gi");
				html = html.replace(re, "");

				// now replace any ampersands with '&amp;' as IE seems to some times for some reason (even in href's)
				current_location = current_location.replace(/\&/gi, "&amp;");
				re = new RegExp(current_location, "gi");
				html = html.replace(re, "");

				<?
					$site_url  = str_replace('.', '\\\\.', $site->get_url());
					$site_href = str_replace('.', '\\\\.', $site->get_href());
				?>
				// replace every god-dam occurance of the full-urls

				// links to sites, pages or files in the backend 
				e = 'http[s]?://<?=$_SERVER['HTTP_HOST'].dirname($_SERVER["PHP_SELF"])?>/?(\\?[spf]=[0-9]+)';
				re = new RegExp(e, "gi");
				html = html.replace(re, "./$1");

				// links to stuff in the backend directory
				e = 'http[s]?://<?=$_SERVER['HTTP_HOST'].dirname($_SERVER["PHP_SELF"])?>/';
				re = new RegExp(e, "gi");
				html = html.replace(re, "");

				// links to sites, pages or files in the frontend
				e = '\\.*((<?=$site_url?>)|(<?=$site_href?>))/?(\\?[spf]=[0-9]+)';
				re = new RegExp(e, "gi");
				html = html.replace(re, "./$4");
				return html;

			}// end make_relative_hrefs()


			function visible() {
				return <?=$visibility_function?>;
			}

			// takes some html text and converts all '<a name="anchor_name"></a>' which are invisible
			// to '#anchor_name' which is visible
			function encode_anchors(html) {

				var e = '<a name=["\']([a-z0-9_]+)["\']></a>';
				var r = '#$1#';
				var re = new RegExp(e, 'gi');
				var new_html = html.replace(re, r);
				return new_html;

			}// encode_anchors()

			// takes some html text and converts back all '#anchor_name' to 
			// '<a name="anchor_name"></a>' 
			function unencode_anchors(html) {

				var e = '#([a-z0-9_]+)#';
				var r = '<a name="$1"></a>';
				var re = new RegExp(e, 'gi');
				var new_html = html.replace(re, r);
				return new_html;

			}// unencode_anchors()

		</script>

		<SCRIPT language="JavaScript">

		function put_defaultFont(passedValue) {
			doc.style.fontFamily = passedValue;
		}

		function put_defaultFontSize(passedValue) {
			switch(passedValue) {
			case "1": passedValue = "xx-small"; break;
			case "2": passedValue = "x-small"; break;
			case "3": passedValue = "small"; break;
			case "4": passedValue = "medium"; break;
			case "5": passedValue = "large"; break;
			case "6": passedValue = "x-large"; break;
			case "7": passedValue = "xx-large"; break;
			}
			doc.style.fontSize = passedValue;
		}

		// checkRange(): make sure our pretend document (the content editable DIV with id of "doc")
		// has focus and that a text range exists (which is what execCommand() operates on).
		function checkRange() {
			if (set_doc_focus()) {
				if (document.selection.type == "None") {
					document.selection.createRange();
				}
				return true;
			} else {
				return false;
			}
		}

		// set_doc_focus(): give our pretend document (the content editable DIV with id of "doc")
		// the focus, if it's visible
		function set_doc_focus() {
			// doc is visible set the focus
			if (visible()) {
				doc.focus();
				return true;
			// invisible cannot set focus
			} else {
				return false;
			}// end if

		}

		// insert_image(): called in response to clicking the insert image icon
		// in the toolbar.  Loads up an appropriate image dialog to prompt for information, the
		// dialog then returns the HTML code or NULL.  We paste the HTML code into the
		// document.
		function insert_image() {
			// First, pick up the current selection.
			if (set_doc_focus()) {

				var url    = "";
				var align  = "";
				var alt    = "";
				var hspace = "";
				var vspace = "";
				var border = "0";
				var is_image = false;

				var r = document.selection.createRange();
				if (document.selection.type.toLowerCase() == "control") {
					if(r.length) {
						if (r.item(0).tagName == "IMG") {
							is_image = true;
							var image_tag = r.item(0);
							// because the editor automatically prepend the http:// business to 
							// all relative urls remove it
							url   = make_relative_hrefs(image_tag.src);
							align = image_tag.align;
							alt = image_tag.alt;
							hspace = (!image_tag.hspace) ? ""  : image_tag.hspace;
							vspace = (!image_tag.vspace) ? ""  : image_tag.vspace;
							border = (!image_tag.border) ? "0" : image_tag.border;
						}// end if IMG

					}// end if length

				}// end if a control

				strPage = "<?=$pop_up_prefix?>/insert_image.php?siteid=<?=$siteid?>&pageid=<?=$pageid?>&url=" + escape(url) + "&align=" + escape(align) + "&alt=" + escape(alt) + "&hspace=" + escape(hspace) + "&vspace=" + escape(vspace) + "&border=" + escape(border) + "&enable_file_upload=<?=$enable_file_upload?>&enable_file_delete=<?=$enable_file_delete?>";
				strAttr = "status:no;dialogWidth:500px;dialogHeight:470px;help:no;resizable:yes;";

				var retVal = showModalDialog(strPage, null, strAttr);

				if (retVal && retVal["url"] != "" && retVal["url"] != undefined) {
					var url = new String(retVal["url"]);

					// put the site root infront of the image url if it is a file from our site
					// the reason that we don't do it on the pop_up is so that people can use the same
					// syntax when they use the Raw HTML/Rich Text editors ( and works on the frontend )
					var e = '^\\./(\\?f=[0-9]+)';
					var re = new RegExp(e);
					url = url.replace(re, "<?=$site->get_href()?>$1");

					var html = '<img src="' + url + '"';
					if (retVal["align"] != "") {
						html += ' align="' + retVal["align"] + '"';
					}
					if (retVal["alt"] != "") {
						html += ' alt="' + retVal["alt"] + '"';
					}
					if (retVal["hspace"] != "") {
						html += ' hspace="' + retVal["hspace"] + '"';
					}
					if (retVal["vspace"] != "") {
						html += ' vspace="' + retVal["vspace"] + '"';
					}
					if (retVal["border"] != "") {
						html += ' border="' + retVal["border"] + '"';
					}
					html += '>';

					// Replace text with new Image
					if (!is_image) {
						r.pasteHTML(html);
					// we are replacing an existing image
					} else {
						image_tag.outerHTML = html;
					}// end if

				}// end if retVal exists

				if (retVal  && retVal["del_fileids"].length > 0) {
					for(var i = 0; i < retVal["del_fileids"].length; i++) {
						var e = '<img[^>]+src\\s*=\\s*[\'"][^\\?]+\\?f='+retVal["del_fileids"][i]+'[\'"][^>]*>';
						var r = '';
						var re = new RegExp(e, 'gi');
						doc.innerHTML = doc.innerHTML.replace(re, r);
					}
				} //end if

			}//end if set doc focus()

		}// end insert_image()

		function check_spelling(){
			strPage = "<?=$pop_up_prefix?>/spellchecker_iframe.php?";
			strAttr = "status:no;dialogWidth:675px;dialogHeight:330px;scroll:off;help:no;resizable:yes;";
			var currArgs = new Object();
			currArgs["html"] = escape(doc.innerHTML);
			currArgs["stylesheet"] = "<?=$stylesheet?>";
			var retVal = showModalDialog(strPage, currArgs, strAttr);
			var html = new String(retVal);
			if(html != "" && html != "undefined"){
				doc.innerHTML = unescape(html);
			}
		}

		// link(): called to insert a hyperlink.  It will use the selected text if
		// there is some, or the URL entered if not.  If clicked when over a link,
		// that link is allowed to be edited.
		function link(on) {
			// all available new window options
			var NEW_WINDOW_OPTIONS = new Array('toolbar','menubar','location','status','scrollbars','resizable','width','height');

			// OK, let's create the current args array that we'll pass to the dialog
			var currArgs = new Object();
			currArgs["anchor_list"] = get_anchors();
			currArgs["url"] = "http://";
			currArgs["status_text"] = "";
			currArgs["new_window"]  = 0;

			currArgs["new_window_options"] = new Object();
			for(var i=0; i < NEW_WINDOW_OPTIONS.length; i++) {
				currArgs["new_window_options"][NEW_WINDOW_OPTIONS[i]] = '';
			}


			// First, pick up the current selection.
			if (set_doc_focus()) {

				// if they haven't selected anything, tell them to politely go away
				if (document.selection.type.toLowerCase() == "none") {
					alert("You need to select something before attempting to make it a link.");
					return;
				}// end if 

				// OK, so they have a control object selected, let's try and make it a text selection
				if (document.selection.type.toLowerCase() == "control") {
					var control_range = document.selection.createRange();
					var text_range = document.body.createTextRange();
					if (text_range != null) {
						text_range.moveToElementText(control_range(0));
						text_range.select();
					} else {
						alert("You need to select this object as though it was text, rather than just clicking on it.");
						document.selection.empty();
						return;
					}// end if

				}// end if a control

				var r = document.selection.createRange();
				var el = r.parentElement();
				var link_text = "";
				var para_obj = null;

				// if the parent element is not an anchor but the text starts with an anchor tag
				// which happens when selecting images with existing links on them
				// OR
				// if the text starts with an img tag then try and get the parent
				if ((el && el.nodeName != "A" && r.htmlText.search(/^<a/gi) > -1) || r.htmlText.search(/^<img/gi) > -1) {
					if (el.parentNode && el.parentNode.nodeName == "A") {
						el = el.parentNode;
					}

				}// end if

				// Is this aready a link?
				if (el && el.nodeName == "A") {
					r.moveToElementText(el);
					r.select();
					if (!on) {		// If removing the link, then replace all with
						r.pasteHTML(el.innerHTML);
						return;
					}

					currArgs["url"] = el.href;

					// OK, let's see if we are doing a window.open()
					var e = 'onClick="javascript: if \\(!window\\.wysiwyg_editing\\) { window\\.open\\(\'([^\']+)\', \'[0-9]+\', \'([^\']+)\'\\); }';
					var re = new RegExp(e, "gi");
					var results = re.exec(el.outerHTML);
					if (results != null) {
						currArgs["url"] = results[1];
						currArgs["new_window"] = 1;
						var options_str = results[2]; 

						for(var i=0; i < NEW_WINDOW_OPTIONS.length; i++) {
							e = NEW_WINDOW_OPTIONS[i] + "=([^,]+)";
							re = new RegExp(e, "gi");
							results = re.exec(options_str);
							currArgs["new_window_options"][NEW_WINDOW_OPTIONS[i]] = (results != null) ? results[1] : "";
						}

					}// end if


					// OK, let's try and get the window.status text if any
					re = new RegExp('onmouseover="([^"]+)"', "gi");
					results = re.exec(el.outerHTML);
					if (results != null) {
						var onmouseover = results[1];
						re = new RegExp('window.status=\'(.+)\'; return true;', "gi");
						results = re.exec(onmouseover);
						if (results != null) {
							currArgs["status_text"] = rev_htmlspecialchars(results[1]).replace(/\\\'/gi, "\'");
						}
					}// end if

					// because the editor automatically prepend the http:// business to 
					// all relative urls remove it
					currArgs["url"] = unescape(make_relative_hrefs(currArgs["url"]));
					// Get the text associated with this link
					link_text = el.innerHTML;

				// else not a link so check for paragraphs
				} else {

					// Get the text associated with this link
					link_text = r.htmlText;
					// convert all para tags to lowercase
					link_text = link_text.replace(/(<\/?)P/gi, "$1p");
					
					// OK so we have some paragraph tags caught up in the mix, let's see what we can do with them
					// because we don't want our href's going around paragraph tags
					if (link_text.indexOf("<p") > -1) {

						// if the position of the first tag is the same as the last then we have only one paragraph
						// we don't want more than one paragraph because these can start or end from half-way through 
						// a para, which would stuff up the formatting when we go to re-insert the text with the href
						if (link_text.indexOf("<p") == link_text.lastIndexOf("<p")) {
							para_obj = format_link_para(link_text);
						} else {
							alert("Please select only one paragraph to link at a time otherwise bad things happen.");
							return;
						}// end if

					}// end if paragraphs exist

				}// end if existing link

				var strPage = "<?=$pop_up_prefix?>/insert_url.php?siteid=<?=$siteid?>&pageid=<?=$pageid?>&enable_file_upload=<?=$enable_file_upload?>";
				strAttr = "status:no;dialogWidth:500px;dialogHeight:500px;help:no;resizable:yes;";
				retVal = showModalDialog(strPage, currArgs, strAttr);

				if (retVal && retVal["url"] != "") {
		
					var html = '';

					if (para_obj != null) {
						link_text = para_obj.html;
						html += para_obj.start;
					}

					// if we have a new window, generate the new javascript url string
					if (retVal["new_window"] == "1") {
						var now = new Date();
						var option_string = "";
						for(var i=0; i < NEW_WINDOW_OPTIONS.length; i++) {
							if (option_string != "") option_string += ",";
							option_string += NEW_WINDOW_OPTIONS[i] + "=" + retVal["new_window_options"][NEW_WINDOW_OPTIONS[i]];
						}

						retVal["onClick"] = "javascript: if (!window.wysiwyg_editing) { window.open('" + retVal["url"] + "', '" + now.getTime() + "', '" + option_string + "'); } return false;";

					}

					html += '<a href="' + retVal["url"] + '"';
					if (retVal["status_text"] != "") {
						html += ' onMouseOver="javascript: window.status=\'' + htmlspecialchars(retVal["status_text"]).replace(/\'/gi, "\\'") + '\'; return true;" onMouseOut="javascript: window.status=\'\'; return true;"';
					}
					if (retVal["onClick"] != null && retVal["onClick"] != "") {
						html += ' onClick="' + retVal["onClick"] + '"';
					}
					html += '>' + link_text + '</a>';

					if (para_obj != null) {
						html += para_obj.end;
					}

					// Replace with new URL
					r.pasteHTML(html);

				}// end if retVal exists

				reset();

			}// end if set_doc_focus

		}// end link()


		// inserts span tags around the current text with the passed style
		function insert_span(style) {

			// First, pick up the current selection.
			if (set_doc_focus()) {

				// OK, so they have a control object selected, let's try and make it a text selection
				if (document.selection.type.toLowerCase() == "control") {
					var control_range = document.selection.createRange();
					var text_range = document.body.createTextRange();
					if (text_range != null) {
						text_range.moveToElementText(control_range(0));
						text_range.select();
					} else {
						alert("You need to select this object as though it was text, rather than just clicking on it.");
						document.selection.empty();
						return;
					}// end if

				}// end if a control

				var r = document.selection.createRange();
				var el = r.parentElement();
				var link_text = "";
				var para_obj = null;
				var html = '';

				// Is this aready a span?
				if (el && el.nodeName == "SPAN" && el.outerHTML == r.htmlText) {
					e = '(.*)class=([A-za-z0-9\-_]*)(.*)';
					re = new RegExp(e, "i");
					el.outerHTML = el.outerHTML.replace(re, "$1class=" + style + "$3");
				// else not current span
				} else {
					// Get the text associated with this span
					link_text = r.htmlText;
					// Replace with new span
					html = '<SPAN class=' + style + '>' + link_text + '</SPAN>';
					r.pasteHTML(html);
				}// end if existing link

			}// end if set_doc_focus

		}// end link()

		// formats a paragraph into 3 parts, the start tag, the inner html and the end tag
		function format_link_para(para) {

			// remove any new line chars
			para = para.replace(/\\n/g, "\\\\n");
			para = para.replace(/\n/g, "\\n");
			para = para.replace(/\\r/g, "\\\\r");
			para = para.replace(/\r/g, "\\r");

			var re = /(<p[^>]*>)(.*)(<\/p>)/i;
			var arr = para.match(re);
			if (arr == null) return null;

			var para_obj = new Object();
			para_obj.start = arr[1];
			para_obj.html  = arr[2];
			para_obj.end   = arr[3];

			// put any new line chars back
			for(var i in para_obj) {
				para_obj[i] = para_obj[i].replace(/\\\\n/g, "\\n");
				para_obj[i] = para_obj[i].replace(/\\n/g, "\n");
				para_obj[i] = para_obj[i].replace(/\\\\r/g, "\\r");
				para_obj[i] = para_obj[i].replace(/\\r/g, "\r");
			}// end for

			return para_obj;

		}// end format_link_para()

		// returns the list of anchors on in this editor and potentially in the page as a whole
		function get_anchors() {

			var anchor_names  = <? echo ($anchors_function) ? 'array_copy('.$anchors_function.');' : 'new Array();'; ?>

			if (anchor_names.length > 0) {
				// OK what we need to do is remove the anchors that existed in the current content before 
				// we started editing, this is because the user may have changed these ones so we should 
				// remove them from any existing list
				for(var i = 0 ; i < INITIAL_ANCHORS.length; i++) {
					array_remove_element(anchor_names, INITIAL_ANCHORS[i]);
					if (anchor_names.length <= 0) break;
				}// end for
			}// end if

			// now add the current local anchors to the list
			var local_anchors = get_local_anchors();
			for(var i = 0 ; i < local_anchors.length; i++) {
				anchor_names.push(local_anchors[i]);
			}// end for

			anchor_names.sort();

			return anchor_names;

		}// end get_anchors()
			
		// returns the list of anchors on in this editor
		function get_local_anchors() {

			var anchor_names = new Array();

			var text = new String(doc.innerText);
			if (text == "") return anchor_names;

			// remove any new line chars
			text = text.replace(/\n/gi, " ");
			text = text.replace(/\r/gi, " ");

			// now add some markers for split below
			text = text.replace(/(#[a-z0-9_]+#)/gi, "\n$1\n");

			var lines = text.split("\n");

			var re = new RegExp("^#([a-z0-9_]+)#$", "gi");
			for(var i =0; i < lines.length; i++) {
				var matches = re.exec(lines[i]);
				if (matches != null) {
					anchor_names.push(matches[1]);
				}
			}

			return anchor_names;

		}// end get_local_anchors()
			



		// doStyle(): called to handle the simple style commands such a bold, italic
		// etc.  These require no special handling, just a call to execCommand().  We
		// also call reset so that the toolbar represents the state of the current 
		// text.
		function doStyle(s,i) {
			if (checkRange()) {
				document.execCommand(s,i);
				reset();
			}
		}

		// sel(); similar to doStyle() but called from the dropdown list boxes for font
		// and style commands.
		function sel(el) {
			if (checkRange()) {
				switch(el.id) {
					case "ctlFont":
						document.execCommand('FontName', false, el[el.selectedIndex].value);
						break;
					case "ctlSize":
						document.execCommand('FontSize', false, el[el.selectedIndex].value);
						break;
					case "ctlStyle":
						switch(el[el.selectedIndex].value) {
						case "Normal" :
						case "Heading 1" :
						case "Heading 2" :
						case "Heading 3" :
						case "Heading 4" :
						case "Heading 5" :
						case "Heading 6" :
						case "Formatted" :
						case "Address"   : document.execCommand('FormatBlock', false, el[el.selectedIndex].value); break;
						default          : insert_span(el[el.selectedIndex].value); break;
					}// end switch
						
						break;
				}
				if (set_doc_focus()) {
					reset();
				}

			}// end checkRange
				
		}

		// sel(); similar to doStyle() but called from the dropdown list boxes for font
		// and style commands.
		function insertkeyword(el) {
			if (checkRange()) {
				var r = document.selection.createRange();
				html = el[el.selectedIndex].value;
				if (html != null) {
					r.pasteHTML(html);
				}
				el.selectedIndex=0;
			}// end checkRange
		}

		// pickColor(): called when the text or fill color icons are clicked.  Displays the
		// color chooser control.  The color setting is completed by the event handler of
		// this control.
		function pickColor(fg) {

			if (checkRange()) {
				var el = window.event.srcElement;
				if (el && el.nodeName == "IMG") {
					setState(el, true);
				}
				color.style.top = window.event.clientY + 10;
				color.style.left = window.event.clientX;
				color.style.display = 'block';
				color._fg = fg;

			}// end checkRange
		}

		// setValue(): called from reset() to make a select list show the current font
		// or style attributes
		function selValue(el, str) {

			window.status = el.id + ", " + str;
			for (var i = 0; i < el.length; i++) {
				if (el[i].value == str) {
					el.selectedIndex = i;
					return;
				}
			}
			el.selectedIndex = 0;
		}

		// setState(): called from reset() to make a button represent the state of the
		// current text.  Pressed is on, unpressed is off.
		function setState(el, on) {
			if (on) {
				el.className = "rebar_button_down";
			} else {
				el.className = "rebar_button";
			}
		}

		// reset(): called from all over the place to make the toolbar represent the
		// current text. If el specified, it was called from hover(off)
		function reset(el) {

			if (!el) color.style.display = 'none';
			if (checkRange()) {
				// if (!el || el == ctlFont)			selValue(ctlFont, document.queryCommandValue('FontName'));
				// if (!el || el == ctlSize)			selValue(ctlSize, document.queryCommandValue('FontSize'));
	<?
		if ($enable_styles) {
		?>
				if (!el || el == ctlStyle)			selValue(ctlStyle, document.queryCommandValue('FormatBlock'));
		<?
		}#end if enable_styles
	?>
				if (!el || el == btnBold)			setState(btnBold, document.queryCommandValue('Bold'));
				if (!el || el == btnItalic)			setState(btnItalic, document.queryCommandValue('Italic'));
				if (!el || el == btnUnderline)		setState(btnUnderline, document.queryCommandValue('Underline'));
				if (!el || el == btnStrikethrough)	setState(btnStrikethrough, document.queryCommandValue('Strikethrough'));
				if (!el || el == btnLeftJustify)	setState(btnLeftJustify, document.queryCommandValue('JustifyLeft'));
				if (!el || el == btnCenter)			setState(btnCenter, document.queryCommandValue('JustifyCenter'));
				if (!el || el == btnRightJustify)	setState(btnRightJustify, document.queryCommandValue('JustifyRight'));
				if (!el || el == btnNumList)		setState(btnNumList, document.queryCommandValue('InsertOrderedList'));
				if (!el || el == btnBulList)		setState(btnBulList, document.queryCommandValue('InsertUnorderedList'));
			}// end checkRange
		}

		// hover(): Handles mouse hovering over toolbar buttons
		function hover(on) {
			var el = window.event.srcElement;
			if (el && el.nodeName == "IMG") {
				if (on) {
					el.className = "rebar_button_hover";
				} else {
					el.className = "rebar_button";
					reset(el);
				}
			}
		}
		// hover(): Handles mouse clicks on toolbar buttons
		function press(on) {
			var el = window.event.srcElement;
			if (el && el.nodeName == "IMG") {
				if (on) {
					el.className = "rebar_button_down";
				} else { 
					el.className = el.className == "rebar_button_down"
									? "rebar_button_hover"
									: "rebar_button";
				}
			}
		}

		// Initialise toolbar, called once the body has loaded.  Sets the initial state
		// of the toolbar to represent the current text.
		function init_doc() {
			if (set_doc_focus()) {
				reset();
			}
		}

		</SCRIPT>
		<table unselectable="on" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="buttonface">
			<tr>
				<td class="rebar">
					<table border="0" cellspacing="0" cellpadding="0" bgcolor="buttonface" width="100%">
						<tr ondragstart="return false;" onmouseup="press(false)" onmousedown="press(true)" onmouseover="hover(true)" onmouseout="hover(false)">
							<td class="rebar">
								<nobr>
								<span>
									&nbsp;
		<?
		if ($enable_ccp) {
		?>
									<img class="rebar_button" id="btnCut"           onclick="doStyle('Cut')"           alt="Cut"           src="<?=$image_prefix?>/icon_cut.gif"           width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnCopy"          onclick="doStyle('Copy')"          alt="Copy"          src="<?=$image_prefix?>/icon_copy.gif"          width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnPaste"         onclick="doStyle('Paste')"         alt="Paste"         src="<?=$image_prefix?>/icon_paste.gif"         width="20" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
		<? } ?>									
									<img class="rebar_button" id="btnBold"          onclick="doStyle('bold')"          alt="Bold"          src="<?=$image_prefix?>/icon_bold.gif"          width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnItalic"        onclick="doStyle('italic')"        alt="Italic"        src="<?=$image_prefix?>/icon_italic.gif"        width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnUnderline"     onclick="doStyle('underline')"     alt="Underline"     src="<?=$image_prefix?>/icon_underline.gif"     width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnStrikethrough" onclick="doStyle('strikethrough')" alt="Strikethrough" src="<?=$image_prefix?>/icon_strikethrough.gif" width="20" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<img class="rebar_button" id="btnLeftJustify"   onclick="doStyle('JustifyLeft')"   alt="Align Left"    src="<?=$image_prefix?>/icon_left.gif"          width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnCenter"        onclick="doStyle('JustifyCenter')" alt="Center"        src="<?=$image_prefix?>/icon_center.gif"        width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnRightJustify"  onclick="doStyle('JustifyRight')"  alt="Align Right"   src="<?=$image_prefix?>/icon_right.gif"         width="20" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<img class="rebar_button" id="btnNumList"       onclick="doStyle('InsertOrderedList')"   alt="Numbered List" src="<?=$image_prefix?>/icon_numlist.gif" width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnBulList"       onclick="doStyle('InsertUnorderedList')" alt="Buletted List" src="<?=$image_prefix?>/icon_bullist.gif" width="20" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<img class="rebar_button" id="btnOutdent"       onclick="doStyle('Outdent')"       alt="Decrease Indent" src="<?=$image_prefix?>/icon_outdent.gif"     width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnIndent"        onclick="doStyle('Indent')"        alt="Increase Indent" src="<?=$image_prefix?>/icon_indent.gif"      width="20" height="20" align="absMiddle">
									<!-- <img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<img class="rebar_button" id="btnChar"        onclick="insert('char')"        alt="Insert Special Character" src="<?=$image_prefix?>/icon_ins_char.gif"      width="20" height="20" align="absMiddle"> -->
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<img id="btnPrev" onmousedown="document.execCommand('Undo')" alt="Undo"    src="<?=$image_prefix?>/icon_undo.gif" align="absMiddle" width="20" height="20">
									<img id="btnNext" onmousedown="document.execCommand('Redo')"  alt="Redo" src="<?=$image_prefix?>/icon_redo.gif" align="absMiddle" width="20" height="20">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<img class="rebar_button" id="btnHelp"        onclick="Javascript: var help = window.open('<?=squizlib_href('wysiwyg', 'help/index.html')?>', 'help', 'status=1,toolbar=0,width=680,height=600,titlebar=0,scrollbars=1,resizable=1');" alt="Help" src="<?=$image_prefix?>/icon_help.gif"      width="16" height="16" align="absMiddle">
								</span>
								</nobr>
							</td>
							<td rowspan="2" align="right">
								<?
								if (!$embeded) {
								?>
								<input type="button" value="Save" onclick="javascript: popup_save(this.form)">
								<input type="button" value="Cancel" onclick="javascript: <?=$cancel_function?>;">&nbsp;
								<?
								}
								?>
							</td>
							<td rowspan="2" align="center">
								<img src="<?=$image_prefix?>/blank.gif" width="10" height="1">
							</td>
						</tr>
						<tr ondragstart="return false;"  onmouseup="press(false)" onmousedown="press(true)" onmouseover="hover(true)" onmouseout="hover(false)">
							<td class="rebar" height="20">
								<nobr>
								<span>
									&nbsp;
		<?
		if ($enable_styles) {
		?>
									<span class="label">Style</span>
									<select class="rebar_button" id="ctlStyle" onchange="sel(this)">
										<option value="Normal" selected>Normal</option>
					<? 
						/*
									THESE AREN'T NEEDED BECAUSE THEY ARE SET BY THE SITE DESIGN

										<option value="Heading 1">Heading 1</option>
										<option value="Heading 2">Heading 2</option>
										<option value="Heading 3">Heading 3</option>
										<option value="Heading 4">Heading 4</option>
										<option value="Heading 5">Heading 5</option>
										<option value="Heading 6">Heading 6</option>
										<option value="Address">Address</option>
										<option value="Formatted">Formatted</option>
								
						*/
					?>
									</select>
									<span class="rebar_sep"></span>
		<? 
		}#end if enable_styles
	?>

					<? /*
									&nbsp;
									<span class="label">Font</span>
									<select class="rebar_button" id="ctlFont" onchange="sel(this)">
										<option selected></option>
										<option value="Arial">Arial</option>
										<option value="Arial Black">Arial Black</option>
										<option value="Comic Sans MS">Comic Sans MS</option>
										<option value="Courier New">Courier New</option>
										<option value="Lucida Console">Lucida Console</option>
										<option value="MS Sans Serif">MS Sans Serif</option>
										<option value="Tahoma">Tahoma</option>
										<option value="Times New Roman">Times New Roman</option>
										<option value="Trebuchet MS">Trebuchet MS</option>
										<option value="Verdana">Verdana</option>
										<option value="Wingdings">Wingdings</option>
									</select>
									<span class="rebar_sep"></span>
									&nbsp;
									<span class="label">Size</span>
									<select class="rebar_button" id="ctlSize" onchange="sel(this)">
										<option selected></option>
										<option value="1">xx-small</option>
										<option value="2">x-small</option>
										<option value="3">small</option>
										<option value="4">medium</option>
										<option value="5">large</option>
										<option value="6">x-large</option>
										<option value="7">xx-large</option>
									</select>
									<span class="rebar_sep"></span>
						*/
					?>
		<?
		if ($allow_colour_selections) {
		?> 
									<img class="rebar_button" id="btnText" onclick="pickColor('ForeColor')" alt="Text Color"       src="<?=$image_prefix?>/icon_color_text.gif" width="36" height="20" align="absMiddle">
									<img class="rebar_button" id="btnFill" onclick="pickColor('BackColor')" alt="Background Color" src="<?=$image_prefix?>/icon_color_fill.gif" width="36" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
		<?
		}#end if allow_colour_selections
		?>
									<img class="rebar_button" id="btnSelect"  onclick="doStyle('SelectAll')"            alt="Select All"             src="<?=$image_prefix?>/icon_select_all.gif"     width="20" height="20" align="absMiddle">
									<img class="rebar_button" id="btnRemove"  onclick="doStyle('RemoveFormat')"         alt="Remove Formatting"      src="<?=$image_prefix?>/icon_rem_formatting.gif" width="20" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
		<?
		if (strtolower($link_interface) != 'none') {
			switch (strtolower($link_interface)) {
				case 'simple' :
		?> 
									<img class="rebar_button" id="btnLink"    onclick="doStyle('CreateLink',1)"                      alt="Insert Link"            src="<?=$image_prefix?>/icon_ins_link.gif"       width="20" height="20" align="absMiddle">
				<?
					break;
				case 'full' :
				default     :
				?>
									<img class="rebar_button" id="btnLink"    onclick="link(true)"                      alt="Insert Link"            src="<?=$image_prefix?>/icon_ins_link.gif"       width="20" height="20" align="absMiddle">
			<?
					break;
			}
			?>
									<img class="rebar_button" id="btnRemLink" onclick="document.execCommand('unlink');"                     alt="Remove Link"            src="<?=$image_prefix?>/icon_rem_link.gif"       width="20" height="20" align="absMiddle">
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
		<?
		} # end if $link_interface != none
		?>
									
		<?
		if ($allow_image_uploads) {
		?>
									<img class="rebar_button" id="btnImage"   onclick="insert_image()"                  alt="Insert Image"           src="<?=$image_prefix?>/icon_ins_image.gif"      width="20" height="20" align="absMiddle">

		<?
		}#end if allow_image_uploads
		
		# check if pspell is installed before printing spellcheck button
		if(defined('PSPELL_FAST') && $enable_spell_check) {
		?>
									<img class="rebar_button" id="btnSpelling"   onclick="check_spelling()"                  alt="Check Spelling"           src="<?=$image_prefix?>/icon_spell.gif"      width="20" height="20" align="absMiddle">
		<?
		}
		?>

									<img class="rebar_button" id="btnRule"    onclick="doStyle('InsertHorizontalRule')" alt="Insert Horizontal Line" src="<?=$image_prefix?>/icon_rule.gif"           width="20" height="20" align="absMiddle">

		<?
		if ($enable_source) {
		?>
									<img src="<?=$image_prefix?>/blank.gif" width="2"><span class="rebar_sep"></span>
									<span class="label">Source</span>
									<input title="Source" hidefocus type="checkbox" name="switchMode" onclick="setEditMode(switchMode.checked)">
		<?
		}#end if enable_source
		?>
								</span>
								</nobr>
							</td>
						</tr>
		<?
			/*
				This if statement should wrap around just the span and select box for the keywords.
				Since this is the only thing on the next line, it wraps around the whole lot.
				When the wysiwyg expands, it should be moved.
			*/
			if ($enable_keywords) {
		?>
						<tr ondragstart="return false;"  onmouseup="press(false)" onmousedown="press(true)" onmouseover="hover(true)" onmouseout="hover(false)">
							<td class="rebar" height="20">
								<nobr>
								<span>
									&nbsp;
									<span class="label">Keyword Replacements</span>
									<select class="rebar_button" id="ctlKeywords" onchange="insertkeyword(this)">
										<option value="">-- Insert Keyword --</option>
									</select>
								</span>
								</nobr>
							</td>
						</tr>
		<?
			}
		?>
					</table>
				</td>
			</tr>
			<tr>
				<td class="rebar_spacer"><img height="1" src="<?=$image_prefix?>/blank.gif" align="left"></td>
			</tr>
			<tr>
				<td valign="top" height="100%"><div class="bodytext" id="doc" onkeyup="reset()" contenteditable="true" style="OVERFLOW: auto; WIDTH: <?=($page_width - 11)?>px; HEIGHT: 100%;" onclick="reset();" <?=($embeded) ? 'onBlur="popup_save(this.form);"' : ''?>></div></td>
			</tr>
		</table>
		<object id="color" data="<?=$pop_up_prefix?>/colourchooser.php" type="text/x-scriptlet" VIEWASTEXT>
		</object>
		<script for="color" event="onscriptletevent(name, data)">
			color.style.display = 'none';
			if (checkRange()) {
				if (!data) {
					document.execCommand("RemoveFormat");
				} else {
					document.execCommand(color._fg, false, data);
				}
				set_doc_focus();
			}// end checkRange
		</script>
		<script language="JavaScript">
			// do this here because there seems to be a pause between the 
			// loading of the file and calling of the onLoad event
			popup_init();
		</script>
	<?
	}#end paint_interface()

}#end class
?>
