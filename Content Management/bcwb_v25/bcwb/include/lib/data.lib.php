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

if(!defined("BCWB_DATA"))
{
	define("BCWB_DATA","open");
	

		function CallBack_StripTags($matches) {
		global $tag;
		return "<".$tag.">".strip_tags($matches[1])."</".$tag.">";
		}
		
		function CallBack_TagsOrders($matches) {
			global $thistag;
			if(!preg_match("/<\/".$thistag.">/is", $matches[2])) 
			return "<".$thistag.$matches[1].$matches[2]."</".$thistag."><".$thistag.$matches[3]; 
			else
			return "<".$thistag.$matches[1].$matches[2]."<".$thistag.$matches[3]; 
		}
	
	
	
	class bcwb_data
	{
	
		/**
		* @return void
		* @desc Get structure tree array
		*/
		function get_tree()
		{
			if( !@include($root_path.'scripts/structure.inc.php') ) {
				$this->tree=array( "index" => array( "label" => "Main page", "variable" => "", "level" => "1", "xslt" => "index.xsl", "childs"  => "", "type" => "") );
				$this->filename = "index.xml";
				$this->variable = "index";
				$this->structure_save();
				define("CREATE_STRUCTURE", true);
			 }

		}

		/**
		* @return void
		* @desc Add message to guestbook 
		*/
		function add_guestmessage() {
			
				$fp = @fopen($root_path."dcontent/".date("Y-m-d_His").".guestbook", "w");
				fwrite($fp, "<entry filename=\"".date("Y-m-d_His").".guestbook\" date_create=\"".date("Y-m-d H:m:s")."\"><author_name>".stripslashes(strip_tags($_POST["yourname"]))."</author_name>\n<author_email>".strip_tags($_POST["youremail"])."</author_email>\n<message>".stripslashes (preg_replace("/\n/is", "<br />", strip_tags($_POST["yourmessage"])))."</message></entry>\n");
				fclose($fp);
		}

		/**
		* @return string
		* @desc Get message from guestbook archive
		*/		
		function get_guestmessages() {
			
			$files = array();
			if ($dir = @opendir($root_path."dcontent")) {
				while (($file = readdir($dir)) !== false) {
					if( preg_match("/(.*?)\.guestbook$/is", $file) )
					$files[] = $file;
				}
				closedir($dir);
				
				rsort($files);
				$len = sizeof($files);
				$gb_offset = 5;
				$gb_finish = $gb_offset*($_GET["page"]+1);
				$finish = ($len<$gb_finish?$len:$gb_finish);
				
				for($i=($gb_offset*$_GET["page"]);$i<$gb_finish;$i++) {
					if($files[$i]) $xml  .= join("", @file($root_path."dcontent/".$files[$i]));
				}
				
				if($GLOBALS["authorized"]) $xml .= "<authorized state=\"enable\" />";
				
				if($len > $gb_finish) $xml .= "<toprev page=\"".($_GET["page"]+1)."\" />";  else $xml .= "<toprev page=\"none\" />";
				if($_GET["page"]) $xml .= "<tonext page=\"".($_GET["page"]-1)."\" />"; else $xml .= "<tonext page=\"none\" />";
			}
			
			return  "<guestbook>\n".$xml."\n</guestbook>\n";
		}
		
		/**
		* @return string
		* @desc Generate XML childs 
		*/

		function _generate_child_xml_tree($childs, $path, $parent)
		{	
			static $deep_counter;
			global $argv, $MODREWRITE;
			if($deep_counter++>30) return false;
			
			$parse="";
			if(!is_array($childs)) return false;
			foreach($childs as $value) {
				
				$fetch = $this->tree[$value];
				//selected
				$pref = str_repeat("\t", $fetch["level"]);
				$parse .= $pref ."<treeitem type=\"".$fetch["type"]."\" state=\"".( $argv[($fetch["level"]-1)]==$fetch["variable"]  ? "selected" : "")."\" parent=\"".$parent."\" variable=\"".$fetch["variable"]."\" level=\"".$fetch["level"]."\" >\n";
				$parse .= $pref ."<label>".$fetch["label"]."</label>\n";
				$parse .= $pref ."<variable>".$path.$fetch["variable"]."/</variable>\n";
				$parse .= $pref ."<xslt>".$fetch["xslt"]."</xslt>\n";
				if($fetch["childs"]) $parse .= $this->_generate_child_xml_tree($fetch["childs"], $path.$fetch["label"]."/", $fetch["variable"]);
				$parse .= $pref ."</treeitem>\n";
			}
			
			$deep_counter--;
 			return $parse;
		}
		
		/**
		* @return string
		* @desc Generate XML on basis of the tree array
		*/
		function generate_xml_tree()
		{	
			global $http_path, $argv, $MODREWRITE;
			
			if($MODREWRITE == "disable") $mode="?vpath="; else $mode=false;
			
			$parse="<tree>\n";
			foreach($this->tree as $key => $fetch) {
				if($fetch["level"]==1) {
					if($fetch["variable"])
						$path=$http_path.$mode.$fetch["variable"]."/";
					else 
						$path=$http_path;
					//selected				
					$parse.="\t<treeitem type=\"".$fetch["type"]."\" state=\"".( ($argv[0]==$fetch["variable"] OR (!$argv[0] AND $key=="index")) ? "selected" : "")."\" parent=\"\" variable=\"".$fetch["variable"]."\" level=\"".$fetch["level"]."\" >\n";
					$parse.="\t<label>".$fetch["label"]."</label>\n";
					$parse.="\t<variable>".$path."</variable>\n";
					$parse .= "\t<xslt>".$fetch["xslt"]."</xslt>\n";				
					if($fetch["childs"]) $parse .= $this->_generate_child_xml_tree($fetch["childs"], $path, $fetch["variable"]);
					$parse.="\t</treeitem>\n";
				}
			} 
			
			$parse.="</tree>\n";
 			return $parse;
		}
		
		
		/**
		* @return string
		* @desc Select current page filename
		*/
		function get_current_file_name()
		{
			global $argv_level, $argv_last, $argv_pre_last;
			if($argv_last AND $argv_pre_last) $filename = $argv_level.".".$argv_last.".".$argv_pre_last.".xml";
			elseif($argv_last) $filename = $argv_level.".".$argv_last.".xml";
			else $filename = "index.xml";
			$this->filename = $filename;
			$this->variable = preg_replace("/\.xml$/is", "", $filename);
			return $filename;
		}
		
		
		
		/**
		* @return string
		* @desc Create content
		*/
		function generate_content()
		{
			global $argv_level, $argv_last, $argv_pre_last, $var_pre_last, $lang, $http_path;

			// Check mainpage file exists
			if( !file_exists( $root_path."dcontent/index.xml" ) AND $GLOBALS["authorized"] )  {
				@exec("chmod 777 ".$root_path."dcontent/index.xml");
				$fp = @fopen($root_path."dcontent/index.xml", "w");
				fwrite($fp, "<doc>\n<title>Hello world!</title>\n<header>Hello world!</header>\n<content1>Content block A</content1>\n<content2>Content block B</content2>\n</doc>\n");
				fclose($fp);
			}
			
			if( $_GET["action"]=="clean" AND $GLOBALS["authorized"] )  {
				@exec("chmod 777 ".$root_path."dcontent/".$this->filename);
				$fp = @fopen($root_path."dcontent/".$this->filename, "w");
				fwrite($fp, "<doc>\n<title>Hello world!</title>\n<header>Hello world!</header>\n<content1>Content block A</content1>\n<content2>Content block B</content2>\n</doc>\n");
				fclose($fp);
			}
			
			
			$filename = $this->filename;
		
			$parse = false;
	
			if (!($fp = @fopen($root_path."dcontent/".$filename, "r")))  
			{
				if( $GLOBALS["authorized"] AND $argv_last != "index" AND !defined("CREATE_STRUCTURE") ) {
					$this->tree[ $this->variable ] = array( "label" => "Title of ".$argv_last, "variable" => $argv_last, "level" => $argv_level, "xslt" => "index.xsl", "childs"  => "", "type" => "hidden" ); 
					if( $var_pre_last ) {
						if( !$this->tree[ $var_pre_last ] ["childs"] AND !@in_array($var_pre_last, $this->tree[ $var_pre_last ] ["childs"]))  
							$this->tree[ $var_pre_last ] ["childs"] [] = $this->variable;
					}
					$this->structure_save();	
					return false;
				} else {				
					include($GLOBALS["root_path"]."scripts/404error.xml.php");
					return false;
				}
				
				
			}
				while ($block = fread($fp, 4096)) { $parse .= $block; }
				fclose($fp);
			if( $_GET["action"]!="editpage" )
				$parse = preg_replace("/\(\(\!\/(.*?) (.*?)\)\)/" , "<a href=\"".$GLOBALS["http_path"].$GLOBALS["NOSLASH_SCRIPT_URI"]."/\\1/\">\\2</a>", $parse);
				
				if($GLOBALS["MODREWRITE"] == "disable") {
					$parse = preg_replace("/href=\"http/", "hrof=\"http", $parse);
					$parse = preg_replace("/href=\"/", "href=\"?vpath=", $parse);
					$parse = preg_replace("/hrof=\"http/", "href=\"http", $parse);
				}
				
			
			return $parse;
		}

		/**
		* @return string 
		* @desc Generate XML on basis of the pagetrack info
		*/
		function generate_track()
		{
			global $argv, $argv_level, $http_path;

			$path=$http_path;
			$parse="<track>\n";
			$parse.="\t<trackitem state=\"\"  level=\"0\">\n";
			$parse.="\t<label>".$this->tree["index"]["label"]."</label>\n";
			$parse.="\t<variable>".$http_path."</variable>\n";
			$parse.="\t</trackitem>\n";
			
				for($i=0;$i<$argv_level;$i++) {
					if(!$i)
						$index=($i+1).".".$argv[$i];
					else 
						$index=($i+1).".".$argv[$i].".".$argv[$i-1];
						
					$path.=$this->tree[$index]["variable"]."/";
					$parse.="\t<trackitem state=\"\" level=\"".($i+1)."\">\n";
					$parse.="\t<label>".$this->tree[$index]["label"]."</label>\n";
					$parse.="\t<variable>".$path."</variable>\n";
					$parse.="\t</trackitem>\n";
				}
							
			$parse.="</track>\n";

			return $parse;
		}
		
		/**
		* @return void
		* @desc Save structure buffer
		*/
		function structure_save()
		{
			global $root_path;
			
			if( !$GLOBALS["authorized"] ) return false;
			$code = "<?PHP\n";
			$code .= '$this->tree=array('."\n";
			if($this->tree) {
				foreach($this->tree as $key => $fetch)	{
					
					if( $key=="index" ) $fetch["variable"] = "";
					$childs = "\"\"";
					if( $fetch["childs"] ) {	
						$childs = "array( ";
						foreach($fetch["childs"] as $child) {
							$childs .= "\"".$child."\", ";
						}
						$childs .=  ")";
					}
					
					$fetch["label"] = str_replace('"', "&#34;", $fetch["label"]);
					$fetch["variable"] = str_replace('"', "&#34;", $fetch["variable"]);
					
					$code .= '"'.$key.'" => array( "label" => "'.$fetch["label"].'", "variable" => "'.$fetch["variable"].'", "level" => "'.$fetch["level"].'", "xslt" => "'.$fetch["xslt"].'", "childs"  => '.$childs.', "type" => "'.$fetch["type"].'"), '."\n";
				}
			}
			$code .=");\n?>\n";
			if($this->tree) {
				@copy($root_path."/scripts/structure.inc.php", $root_path."/dcontent/structure.inc.php.bak");
				@unlink($root_path."/scripts/structure.inc.php");
				$fp=fopen($root_path."/scripts/structure.inc.php" , "wb" );
				fwrite($fp, $code);
				fclose($fp);
			}
			return true;
		}

		/**
		* @return void
		* @desc Save XML on basis of the page content
		*/
		function data_delete()
		{
			global $http_path, $root_path, $argv, $last_argv, $argv_level;
			if( $this->variable == "index" ) return false;
			if( @unlink($root_path."/dcontent/".$this->variable.".xml") ) {
				// Delete structure child rows
				if( $this->tree[$this->variable]["level"]>1 ) {
					foreach($this->tree as $key => $fetch) {
						if( $fetch["level"] == $this->tree[$this->variable]["level"]-1  AND $fetch["childs"])  { 
							foreach($fetch["childs"] as $k => $v) {
								if( $this->variable == $v ) unset($this->tree[$key]["childs"][$k]);
							}
						}
					}
				}
				
				unset($this->tree[$this->variable]);
				$this->variable = "index";
				$this->filename  = "index.xml";
				$this->structure_save();
				$last_argv=false; 
				$argv_level=0;
				$argv=array();
				return true;
			}
		}

		/**
		* @return string
		* @desc Parse content upload elements
		*/

		function convert_files_emb($content) {
			return $content;
		}		


		
		/**
		* @return string
		* @desc Convert tags to XHTML
		*/
		function convert_to_XHTML($content) 
		{
			$content = preg_replace("/style=\".*?;*?\"/", "", $content);
			return $content;
		}
		
		/**
		* @return void
		* @desc Save XML on basis of the page content
		*/
		function data_save()
		{
			global $http_path, $root_path, $argv, $last_argv, $argv_level;

		
			$_POST["title"] = preg_replace("/[\r\n\t\"\'<>&;\?%#@\^\$~\`]/is", "", $_POST["title"]);
			$_POST["url"] = preg_replace("/[\r\n\t\"\'<>&;\?%#@\^\$~\`]/is", "", $_POST["url"]);
			
			if(!$_POST["header"]) $header = ($_POST["title"] ? $_POST["title"] : "Header"); else $header = trim($_POST["header"]);
			if(!$_POST["url"]) return false;
			if(!eregi("^[A-Z0-9_ ]*$", $_POST["url"])) return false;
			$_POST["url"] = preg_replace("/ /", "_", $_POST["url"]);
			

			// Generate new filename
			if( $_POST["post_action"]=="createsubitem" ) 
			{ $argv[] = $_POST["url"]; $last_argv = $_POST["url"]; $argv_level++; }
			elseif($argv) 
			{ $last_argv = $argv[count( $argv )-1] = $_POST["url"]; }
			
			$argv_level = ( $argv_level ? $argv_level: 1);
		

			if( (!$argv OR $argv_last=="index" OR $_POST["url"]=="index") AND $_POST["post_action"]=="editpage")
				$new_filename = "index.xml";
			elseif($argv_level>1)
				$new_filename = $argv_level.".".$_POST["url"].".".$argv[$argv_level-2].".xml";
			else
				$new_filename = $argv_level .".".$_POST["url"].".xml";
				
				
			// Change structure tree	
			$var_page =  preg_replace("/\.xml$/i", "", $new_filename);
			$this->tree[$var_page]["label"]=$header;
			$this->tree[$var_page]["xslt"]=$_POST["xslt"];
			$this->tree[$var_page]["level"]=$argv_level;
			$this->tree[$var_page]["variable"]=$_POST["url"];
			$this->variable = $var_page;
			
			
			if($argv[$argv_level-2]=="index")
				$prev_var = "index";		
			elseif($argv_level>2)
				$prev_var = ($argv_level-1).".".$argv[$argv_level-2].".".$argv[$argv_level-3];
			else
				$prev_var = ($argv_level-1).".".$argv[$argv_level-2];

				$old_variable = preg_replace("/\.xml$/i", "", $this->filename);
			
			if( $_POST["post_action"]=="createsubitem" ) 
				$this->tree[$prev_var]["childs"][]=$var_page;
			if($_POST["post_action"]=="editpage" AND $old_variable != $this->variable )
				unset($this->tree[ $old_variable ] );

			$this->structure_save();
			
			// Genereate new page content XML
			$xml = "<doc>\n";
			
			$xml .= "\t<title>". str_replace("\"", "&quot;", stripslashes( $_POST["title"] ))."</title>\n";
			$xml .= "\t<header>". str_replace("\"", "&quot;", stripslashes( $header ))."</header>\n";
			$arr = split(",", $_POST["tags_list"]);
			
			
			if($arr) {
				foreach($arr as $tag) {
					if($tag AND $tag!="header") {
						$xml .= "\t<".$tag.">\n";
						//$xml .= "".preg_replace( "/<P>(.*?)<\/P>/is", "\\1", $this->convert_to_XHTML( $this->convert_files_emb(  stripslashes($_POST[$tag])  ) ) )."\n";
						$xml .= $this->convert_to_XHTML( $this->convert_files_emb(  stripslashes($_POST[$tag])  ) ) ."\n";
						$xml .= "\t</".$tag.">\n";
					}
				}
			}
			
			
			$xml .= "</doc>\n";
			$xml = preg_replace("'&nbsp;'i"," ", $xml);
			
			// Save data into file
			if($GLOBALS["authorized"]) {
				if($_POST["post_action"]=="editpage")
					@unlink($root_path."dcontent/".$this->filename);
				$this->filename = $new_filename;
				$fp=fopen($root_path."dcontent/".$this->filename , "wb" );
				fwrite($fp, $xml);
				fclose($fp);
			}

			if( $_POST["post_action"]=="additem" )  {
				$_argv = $argv; unset($_argv[count($_argv)-1]);  $url = join("/", $_argv); $url = ($url?$url."/":"");
				header("Location: ".$GLOBALS["http_path"].$url.$this->tree[$this->variable]["variable"]."/");
				exit;
			} elseif( $_POST["post_action"]=="createsubitem" ) {
				if($argv) $url = join("/", $argv); else $url = "index";
				header("Location: ".$GLOBALS["http_path"].$url."/");
				exit;
			}			
			
			return false;
		}
		

		
		/**
		* @return void
		* @desc 20.06.04 // Data repear
		*/
		function file_save($filename, $content) {
				@exec("chmod 777 ".$filename);
				$fp = @fopen($filename, "w");
				fwrite($fp, $content);
				fclose($fp);
				@exec("chmod 777 ".$filename);
		}
		
		
		
		/**
		* @return string
		* @desc 27.04.04 // Data repear
		*/
		
		function data_repear($parse) {
		global $root_path, $tag;
		
		$xslt = join("", file($root_path."dcontent/".$this->xslt_filename));
		
		preg_match_all("/<bcwb content=\"(.*?)\" \/>/is", $xslt, $list_array );
			if($list_array[1]) {
				foreach($list_array[1] as $tag) {
					$parse = preg_replace_callback("/<".$tag.">(.*?)<\/".$tag.">/is", "CallBack_StripTags", $parse );
				}
			}
		
			// Save data into file
			if($GLOBALS["authorized"]) {
				@unlink($root_path."/dcontent/".$this->filename);
				$fp=fopen($root_path."/dcontent/".$this->filename , "wb" );
				fwrite($fp, $parse);
				fclose($fp);
			}
		
		return $parse;
		}
		
		
		
		
		/**
		* @return bcwb_data
		* @desc Data consolidation class
		*/
		function bcwb_data()
		{
			global $http_path, $bcwb_admin, $lang, $root_path;
			$xml = false;
			$this->tree = array();
			$this->filename = "index.xml";
			$this->xslt_filename = "index.xsl";			
			$this->variable = "index";
			
			$this->get_tree();
			$this->get_current_file_name();
			
			if($_GET["action"]=="edittemplate") {
				include($root_path."scripts/edittemplate.xslt.php");
				return true;
			}
			if($_GET["action"]=="editxml") {
				include($root_path."scripts/editxml.xslt.php");
				return true;
			}			
			if($_GET["action"]=="stats") {
				include($root_path."scripts/stats.xslt.php");
				return true;
			}
			
			if( $_GET["deletepost"] AND $GLOBALS["authorized"])
				@unlink($root_path."dcontent/".$_GET["deletepost"]);

			
			// Administrator actions

				// Save template changes
				if($_POST["OK"] AND $_POST["edit_template_name"] AND $GLOBALS["authorized"] ) {
					@unlink($root_path."dcontent/".$_POST["edit_template_name"].".bak");
					@rename($root_path."dcontent/".$_POST["edit_template_name"], $root_path."dcontent/".$_POST["edit_template_name"].".bak");
					$this->file_save($root_path."dcontent/".$_POST["edit_template_name"], stripslashes(preg_replace("/\{(.?)textarea(.*?)\}/is",  "<\\1textarea\\2>", $_POST["content"])));	
					define("TEMPLATECHANGED", 1);
				}

				// Save XML code after code editing
				if($_POST["OK"] AND $_POST["edit_xml_name"] AND $GLOBALS["authorized"] ) {
					@unlink($root_path."dcontent/".$_POST["edit_xml_name"].".bak");
					@rename($root_path."dcontent/".$_POST["edit_xml_name"], $root_path."dcontent/".$_POST["edit_xml_name"].".bak");
					$this->file_save($root_path."dcontent/".$_POST["edit_xml_name"], stripslashes(preg_replace("/\{(.?)textarea(.*?)\}/is",  "<\\1textarea\\2>", $_POST["content"])));	
				}

			if($custom_template) $this->xslt_filename = $custom_template;
			if($this->tree[$this->variable]["xslt"]) $this->xslt_filename = $this->tree[$this->variable]["xslt"];
							
				
				// Save data changes
				if( $_POST["OK"] AND $_POST["xslt"] AND $_POST["post_action"] AND $GLOBALS["authorized"] )
	 			 	$this->data_save();
				// Delete selected page		
	 			$delete_action = $_GET["action"];
	 			if( $delete_action=="delete" AND $GLOBALS["authorized"] )
	 				$this->data_delete();
 				// Send mail to your
 				if( $_POST["mailto"] )  send_mail($GLOBALS["FEEDBACK_EMAIL"], $_POST["youremail"], $_POST["subject"], "User name: ".$_POST["youremail"]."<BR />\n User email: ".$_POST["youremail"]."<BR />\n User message: <BR />\n".$_POST["yourmessage"]);
 				if( $_POST["guestbookto"] )  $this->add_guestmessage();
 	

 				if($_GET["action"]!="additem" AND $_GET["action"]!="createsubitem") {

 					
 					
 				if($this->xslt_filename=="guestbook.xsl") 
 					$xml .= $this->get_guestmessages();
 				else
					$xml .= $this->generate_content();
			
				if($_GET["action"]=="repair") $xml =$this->data_repear($xml);
			$noxslt_data = $xml;
			$xml .= $this->generate_xml_tree();
			$xml .= $this->generate_track();
			}


			if($custom_template) $this->xslt_filename = $custom_template;
			if($this->tree[$this->variable]["xslt"]) $this->xslt_filename = $this->tree[$this->variable]["xslt"];
			
			$xslt_file_name="xslt.php";
			$xslt_file_name="xslt.php?script_uri=".$GLOBALS["SCRIPT_URI"].";".$this->xslt_filename;
			if($_GET["action"]) $xslt_file_name="xslt.php?script_uri=".$GLOBALS["SCRIPT_URI"].";".$this->xslt_filename.";".$_GET["action"];
			
			
			
			$output = "<?xml version=\"1.0\" encoding=\"".$GLOBALS["default_charset"]."\"?>\n";
		
			$noxslt_data = $output.$noxslt_data;

			
			// In case of absence SABLOTRON to include the reference to pattern XSLT in XML a file 
			if(!defined("BCWB_NOXSLT") AND !defined("SABLOTRON") )
				$output .= "<?xml-stylesheet type='text/xsl' href='".$http_path."scripts/".$xslt_file_name."'?>\n";
			if($mail_report) $xml .= "\n<report>\n$mail_report\n</report>\n";

			$output .= "\n<root>\n".$xml."\n</root>\n";
			
			// Analize includes in the DATA file
			$arr = array();
			preg_match_all("/include\(\"(.*?)\"\)/is", $output, $arr);
			
			if($arr[1]) {
				foreach($arr[1] as $fetch) {
				$text = join("", file($root_path."dcontent/".$fetch));
				$output = str_replace("include(\"".$fetch."\")", $text, $output);
				}
			}
			
	
			// Show instructions 		
			if($_GET["action"]=="additem" OR $_GET["action"]=="createsubitem")
				include($GLOBALS["root_path"]."scripts/additem.xml.php");


			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

			if( $PAGE_CONTENT_TYPE_PREF AND !$custom_template) {
				foreach($PAGE_CONTENT_TYPE_PREF as $key=>$type) {
					if( preg_match("/^".$key."/is", $this->xslt_filename ) ) { @header("Content-Type: ".$type."; charset=".$GLOBALS["default_charset"]); $SENDPAGEHEADER = true; }
				}
			}
			
			if( preg_match("/svg__.*?\.xsl/", $custom_template) ) 	
				@header("Content-type: image/svg+xml");
			
			elseif($DEFAULT_CONTENT_TYPE!="text/html" AND $DEFAULT_CONTENT_TYPE AND !$SENDPAGEHEADER)
				@header("Content-Type: ".$DEFAULT_CONTENT_TYPE."; charset=".$GLOBALS["default_charset"]); 			
			elseif(!defined("SABLOTRON") AND !$SENDPAGEHEADER)
				@header("Content-Type: text/xml; charset=".$GLOBALS["default_charset"]); 
				
				
				

			if(defined("BCWB_NOXSLT"))
				print $noxslt_data;
			elseif( defined("SABLOTRON") )	{
				if($custom_template)
					$xslData = join("", file($root_path."dcontent/".$custom_template));
				else
					include_once($GLOBALS["root_path"]."scripts/xslt.php");
				
				include_once($GLOBALS["root_path"]."include/lib/sablotron.inc.php");
			} else  print $output;
			
				
/*			
				$fp=fopen($root_path."debug.log" , "wb" );
				fwrite($fp, $output);
				fclose($fp);
*/			
			
			
		}
		
		
	}
}
?>