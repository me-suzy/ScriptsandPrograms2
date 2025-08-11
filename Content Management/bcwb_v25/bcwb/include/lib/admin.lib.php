<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if(!defined("BCWB_ADMIN"))
{
	define("BCWB_ADMIN","open");
	
	class bcwb_admin
	{
		
		/**
		* @return bcwb_admin
		* @desc Administration class
		*/
		function bcwb_admin()
		{
			$this->script_uri = false;
			$this->xslt_filename = false;
			$this->action = false;
			$this->query_uri = false;
			$this->argv = array();
			$this->last_argv = false;
			$this->js_code = false;
			$this->submit_code = false;
			$this->common_fileld = false;
			$this->xslt_list_html = false;
			$this->parse_str1 = false;
			$this->parse_str2 = false;
		}
		
		
		/**
		* @return string
		* @param string $filename
		* @desc Get file content
		*/
		function get_file_content($filename)
		{
			$parse = false;
			
			if (!($fp = @fopen($filename, "r")))  include($GLOBALS["root_path"]."scripts/404error.xml.php");
			while ($block = fread($fp, 4096)) { $parse .= $block; }
			fclose($fp);
			
			return $parse;
		}
		
		/**
		* @return void
		* @param string $_error_report
		* @desc View error report page
		*/
		function error_report($_error_report = false )
		{ global $root_path, $error_report;
		if($_error_report) $error_report = $_error_report;
		print ( $this->admin_parse_error_report( $this->get_file_content($root_path."scripts/error_report.xsl") ) );
		exit;
		}
		
		
		/**
		* @return string
		* @param string $code
		* @desc Error report page parsing
		*/
		function admin_parse_error_report($code)
		{
			global $http_path, $lang, $error_report;
			return preg_replace( 	array("'encoding=\"UTF-8\"'", "'&error_report;'"),  array("encoding=\"".$GLOBALS["default_charset"]."\"", $error_report), $code);
		}
		
		
		
		/**
		* @return string
		* @param string $code
		* @param string $tag
		* @desc Generate WYSIWYG form
		*/
		function wysiwyg($code, $tag)
		{
			global $http_path, $lang, $BCWB_TAG;
			
			$xsl_pointer = '<xsl:copy-of select="/root/doc/'.$tag.'/node()|@*"/>';
			
			$result .= "<textarea style=\"display: none;  padding-top: 0px; padding-bottom: 0px;\" name=\"".$tag."\">".$xsl_pointer."</textarea>\n";
			
			$result .= "<iframe width=\"100%\" height=\"300\" id=\"area_".$tag."\" name=\"area_".$tag."\">\n";
			$result .= "</iframe>\n";
			
			$result .= "<div ID=\"panel_".$tag."\" class=\"cont_btn\" style=\"padding: 5px 5px 5px 5px; display: none\">";
			
			$result .= " ".$lang["Font"]."&#xA0;";
			$result .= "<select name=\"txtdecorate\" class=\"cont_btn\" onchange=\"return ecommand(frames.area_".$tag.".document, this.value)\">\n
				<option value=\"\">none</option>\n
				<option value=\"Bold\">Bold</option>\n
				<option value=\"Italic\">Italic</option>\n
				<option value=\"Underline\">Underline</option>\n
				</select>\n";
			
			$result .= " ".$lang["Justify"]."&#xA0;";
			$result .= "<select name=\"txtdecorate\" class=\"cont_btn\" onchange=\"return ecommand(frames.area_".$tag.".document, this.value)\">\n
				<option value=\"\">none</option>\n
				<option value=\"JustifyLeft\">Left</option>\n	
				<option value=\"JustifyCenter\">Center</option>\n
				<option value=\"JustifyRight\">Right</option>\n
				</select>\n";
			
			$result .= " ".$lang["Indent"]."&#xA0;";
			$result .= "<select name=\"txtdecorate\" class=\"cont_btn\" onchange=\"return ecommand(frames.area_".$tag.".document, this.value)\">\n
				<option value=\"\">none</option>\n
				<option value=\"Indent\">Indent</option>\n	
				<option value=\"Outdent\">Outdent</option>\n
				</select>\n";
			
			$result .= " ".$lang["List"]."&#xA0;";
			$result .= "<select name=\"txtdecorate\" class=\"cont_btn\" onchange=\"return ecommand(frames.area_".$tag.".document, this.value)\">\n
				<option value=\"\">none</option>\n
				<option value=\"InsertUnorderedList\">Unordered</option>\n	
				<option value=\"InsertOrderedList\">Ordered</option>\n
				</select>\n";	
			
			$result .= "<IMG SRC=\"../system/x.gif\" WIDTH=\"10\" HEIGHT=\"1\" ALT=\"\" />";
			$result .= "<script type=\"text/javascript\" src=\"".$http_path."scripts/html2xhtml.js\"></script>";
			$result .= " <input class=\"cont_btn\" type=\"button\" onClick=\"return insimage(frames.area_".$tag.".document, '".$tag."')\" value=\"".$lang["Image"]."\" />\n";
			$result .= " <input class=\"cont_btn\" type=\"button\" onClick=\"return insfile(frames.area_".$tag.".document, '".$tag."')\" value=\"".$lang["File"]."\" />\n";
			$result .= " <input class=\"cont_btn\" type=\"button\" onClick=\"return inslink(frames.area_".$tag.".document, '".$tag."')\" value=\"".$lang["Link"]."\" />\n";
			$result .= " <input class=\"cont_btn\" type=\"button\" onClick=\"return inspage(frames.area_".$tag.".document, '".$tag."')\" value=\"".$lang["Page"]."\" />\n";
			
			$result .= "<br /><IMG SRC=\"../system/x.gif\" WIDTH=\"1\" HEIGHT=\"3\" ALT=\"\" /><br />\n";
			$result .= "</div>";
			
			$result .= "<input class=\"cont_btn\" type=\"button\" onClick=\"return menu(panel_".$tag.", this)\" value=\" + \" />\n";
			$result .= "<input class=\"cont_btn\" type=\"button\" onClick=\"return htmltotext(area_".$tag.".document.body, '".$tag."')\" value=\"HTML/Code\" />\n";
			$result .= "<input class=\"cont_btn\" type=\"button\" onClick=\"return cleanHTML(area_".$tag.".document)\" value=\"".$lang["cleanHTML"]."\" />\n";
			
			
		
			
			
			
			$this->js_code .= "vswitcher['".$tag."'] = true;\n";
			$this->js_code .= "area_".$tag.".document.designMode = \"on\";\n";
			$this->js_code .= "area_".$tag.".document.open();\n";
			$this->js_code .= "area_".$tag.".document.writeln('<style>body, td { font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 11px; } </style>'+document.bcwb_form.".$tag.".value);\n";
			$this->js_code .= "area_".$tag.".document.close();\n";
			$this->submit_code .= "if(vswitcher['".$tag."']) bcwb_form.".$tag.".value = get_xhtml(area_".$tag.".document.body, '".$GLOBALS["default_language"]."', '".$GLOBALS["default_charset"]."'); else bcwb_form.".$tag.".value = area_".$tag.".document.body.innerHTML;\n";

			$pat = preg_replace("/\(\.\*\?\)/is", $tag , $BCWB_TAG["_content"]);
			
			$code = preg_replace("/".$pat."/is", $result, $code);
			return $code;
		}
		
		
		/**
		* @return string
		* @param string $code
		* @desc Generate header input form
		*/
		function header_input($code)
		{
			global $BCWB_TAG;
			$pat = preg_replace("/\(\.\*\?\)/is", "header" , $BCWB_TAG["_content"]);
			$result = "<textarea name=\"header\" style=\"width: 100%;\" rows=\"1\"><xsl:value-of select=\"//root/doc/header\"/></textarea>\n";
			return preg_replace("/".$pat."/is", $result, $code);
		}
		

		/**
		* @return string
		* @param string $code
		* @desc Parsing BCWB tags
		*/
		function parsing_bcwb_tags_noform($code)
		{
			global $BCWB_TAG;
		
			$tags_array=array();
			preg_match_all("/".$BCWB_TAG["_content"]."/is", $code , $tags_array);

			// content1 , content2, .., header
			foreach($tags_array[1] as $val) {
			$pat = preg_replace("/\(\.\*\?\)/is", $val , $BCWB_TAG["_content"]);
			if($val == "header")	
				$result = '<xsl:value-of select="//root/doc/header"/>';
			else
				$result = '<xsl:copy-of select="/root/doc/'.$val.'/node()|@*"/>';
				
			$code = preg_replace("/".$pat."/is", $result, $code);
			}
		return $code;
		}		
		
		/**
		* @return string
		* @param string $code
		* @desc Replacement in a pattern of representation of indexes given by forms 
		*/
		function admin_parse_content($code)
		{
			global $http_path, $lang, $error_report, $BCWB_TAG;
			
			// Create tags pattern
			$BCWB_TAG["_form_start"] = preg_replace(array("/\//", "/\"/"), array("\/", '\"'), $BCWB_TAG["form_start"]);
			$BCWB_TAG["_form_finish"] = preg_replace(array("/\//", "/\"/"), array("\/", '\"'), $BCWB_TAG["form_finish"]);
			$BCWB_TAG["_content"] = preg_replace(array("/\//", "/\"/"), array("\/", '\"'), $BCWB_TAG["content"]);
			
			
			// Custom encoding in XSLT
			$code = preg_replace("'encoding=\"UTF-8\"'", "encoding=\"".$GLOBALS["default_charset"]."\"", $code);
			if( $GLOBALS["action"]!="editpage" AND $GLOBALS["action"]!="additem" AND $GLOBALS["action"]!="createsubitem")  {
				return $this->parsing_bcwb_tags_noform($code);
			}

			// Blank template for message
			if( $GLOBALS["action"]=="additem" OR $GLOBALS["action"]=="createsubitem") {		
				include($GLOBALS["root_path"]."scripts/additem.xslt.php");
				return preg_replace("/".$BCWB_TAG["_form_start"]."(.*?)".$BCWB_TAG["_form_finish"]."/is", $BCWB_TAG["form_start"].$block.$BCWB_TAG["form_finish"], $code);
			}
			
			// Get all content blocks
			$tags_array=array();
			preg_match_all("/".$BCWB_TAG["_content"]."/is", $code , $tags_array);
			if(!$tags_array[1]) $this->error_report( $lang["Not_found_content_pointer"]." &lt;".preg_replace("/[<>]/","", $BCWB_TAG["form_start"])."&gt;");			
			$tags_list = ""; 
			
			// content1 , content2, .., header
			foreach($tags_array[1] as $val) { 
				if($val=="header") 
					$code=$this->header_input($code);
				else
					$code = $this->wysiwyg($code, $val);
				$tags_list .= $val.",";
			}
			
			$this->common_fileld = "<input type=\"hidden\" name=\"tags_list\" value=\"".$tags_list."\" />\n";
			
			return $code;
		}
		
		/**
		* @return void
		* @desc Get XSLT template file list in the DCONTENT folder
		*/
		function get_xslt_list()
		{
			global $root_path;
			
			if ($dir = @opendir($root_path."dcontent")) {
				while (($file = readdir($dir)) !== false) {
					if( preg_match("/(.*?)\.xsl$/is", $file) )
					$this->xslt_list_html .= "<option ".($this->xslt_filename==$file?"selected=\"selected\"":"")." value=\"".$file."\">".$file."</option>";
				}
				closedir($dir);
			}
			return true;
		}
		
		
		function tree_content_parse($content)
		{
			global $lang;
			
			return  preg_replace(
				array( 	"'encoding=\"UTF-8\"'", "/&Title;/", "/&Edit_page;/", "/&Add_item;/", "/&Create_subitem;/", "/\&Delete;/" ),
				array(	"encoding=\"".$GLOBALS["default_charset"]."\"", $lang["Structure"], $lang["Edit_page"], $lang["Add_item"], $lang["Create_subitem"], $lang["Delete"] ), 	$content );
		}
		
		
		/**
		* @return string
		* @param string $content
		* @desc Inclusion in a pattern of representation of the administrative interface 
		*/
		function admin_header_parse($content)
		{
			global $lang, $BCWB_TAG;

			include($GLOBALS["root_path"]."scripts/admin_header.inc.php");

			// Create tags pattern
			$BCWB_TAG["_form_start"] = preg_replace(array("/\//", "/\"/"), array("\/", '\"'), $BCWB_TAG["form_start"]);
			$BCWB_TAG["_form_finish"] = preg_replace(array("/\//", "/\"/"), array("\/", '\"'), $BCWB_TAG["form_finish"]);
			$BCWB_TAG["_content"] = preg_replace(array("/\//", "/\"/"), array("\/", '\"'), $BCWB_TAG["content"]);
		
			if(!preg_match("/".$BCWB_TAG["_form_start"]."/is",$content)) $this->error_report( "Not found &lt;".preg_replace("/[<>]/","", $BCWB_TAG["form_start"])."&gt; construction in xslt" );
			if(!preg_match("/".$BCWB_TAG["_form_finish"]."/is",$content)) $this->error_report( "Not found &lt;".preg_replace("/[<>]/","", $BCWB_TAG["form_finish"])."&gt; construction in xslt" );
			
			// Erase all template forms
			$content = preg_replace("/<(.?)form(.*?)>/is", "<delete_form />", $content );
						
			include($GLOBALS["root_path"]."scripts/admin_startup.js.php");
		
			$this->common_fileld .= "<textarea name=\"title_hidden\"  style=\"display: none;  padding-top: 0px; padding-bottom: 0px;\"  rows=\"1\"><xsl:value-of select=\"//root/doc/title\"/></textarea>\n";
			$this->common_fileld .= "<input type=\"hidden\" name=\"script_uri\" value=\"".$this->script_uri."\" />\n";
			$this->common_fileld .= "<input type=\"hidden\" name=\"post_action\" value=\"".$this->action."\" />\n";
			
			$js = "<SCRIPT LANGUAGE=\"JavaScript\">\n//<![CDATA[\n";
			$js .= "var vswitcher = new Array();\n";
			$js .= "document.bcwb_form.title.value = document.bcwb_form.title_hidden.value;\n";
			$js .= $add_js;
			$js .= "function htmltotext(obj, el) { if(vswitcher[el]) { obj.innerText = get_xhtml(obj, '".$GLOBALS["default_language"]."', '".$GLOBALS["default_charset"]."'); vswitcher[el]=false; } else { obj.innerHTML = obj.innerText; vswitcher[el]=true; } return false; } \n";
			$js .= $this->js_code."\n//]]>\n</SCRIPT>\n";

			if( $GLOBALS["action"]=="additem" OR $GLOBALS["action"]=="createsubitem" ) $js = false;
			
			$js .= "<SCRIPT LANGUAGE=\"JavaScript\">\n//<![CDATA[\n function submit_data() {\n".$this->submit_code."\n return checkbindfield(document.bcwb_form);  }\n//]]>\n</SCRIPT>\n";
			
			$content = preg_replace("/".$BCWB_TAG["_form_start"]."/is", $admin_header, $content);


			if( $GLOBALS["action"]=="editpage" OR $GLOBALS["action"]=="additem" OR $GLOBALS["action"]=="createsubitem" OR $this->action=="tree")
				$content = preg_replace("/".$BCWB_TAG["_form_finish"]."/is", $this->common_fileld.$js."</form>", $content);
			else
				$content = preg_replace("/".$BCWB_TAG["_form_finish"]."/is", "</form>", $content);			
			return $content;
		}
		
		/**
		* @return void
		* @param string $this->script_uri
		* @desc Parse HTTP-path
		*/
		function url_parse()
		{
			$_argv = split( "/", $this->script_uri);
			if($_argv) {
				foreach($_argv as $arg) {
					if($arg) { $this->argv[]=$arg; $this->last_argv = $arg; }
				}
			}
		}
		
	}
	
	$bcwb_admin = new bcwb_admin;
}

?>
