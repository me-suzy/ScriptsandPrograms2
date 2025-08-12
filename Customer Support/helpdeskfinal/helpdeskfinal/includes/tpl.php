<?php
//
// Project: Help Desk support system
// Description: Template engine class and template related functions
//

#
# Template class
#
class tpl
{
	var $template;		// Holds raw loaded template file
	var $parsed;		// Holds result after parsing tags of loaded template ($html)
	var $compiled;		// Holds result after parsing tags AND compiling template file (in $html);
	
	var $filename;		// Filename of loaded template

	
	// Constructor
	// Loads template on init
	function tpl($tpl_file)
	{
		if(!is_file($tpl_file))
			die("Error: Template file $tpl_file not found.");
			
		$this->template = implode("", file($tpl_file));
		
		$this->filename = $tpl_file;
	}
	
	//
	// Replaces tags in $template
	function parse($tags)
	{
		$this->parsed = $this->template;
		if(count($tags))
		{
			foreach($tags as $tag=>$value)
			{		
				if(!empty($tag))
					$this->parsed = preg_replace("/\{".$tag."}/", $value, $this->parsed);
			}
		}
	}
	
	//
	// Replaces tags in $template AND compiles it
	function compile($tags=0)
	{
		// Compile file
		ob_start();
			include $this->filename;
			$this->compiled = ob_get_contents();
		ob_end_clean();
			
		if(count($tags) && is_array($tags))
		{
			foreach($tags as $tag=>$value)
			{
				if(!empty($tag))
					$this->compiled = preg_replace("/{".$tag."}/", $value, $this->compiled);
			}
		}
			
	}
}

#
# Builds a generic content box
#
function content_box($content, $title, $admin=false)
{
	if(!$admin)
		$tpl_contbox = new tpl("tpl/content_box.tpl");
	else
		$tpl_contbox = new tpl("tpl/admin_content_box.tpl");
	
	$tpl_contbox_tags = array( "box_title"		=> $title,
							   "box_content"	=> $content );

	$tpl_contbox->parse($tpl_contbox_tags);
	return $tpl_contbox->parsed;							
}

#
# Builds a generic page with the default header/content/footer layout
# Used to avoid repeating the Load header/main/footer template, 
# compile header/main/footer template task in every file that outputs 
# data to screen with the default layout
#

function build_page($page_content, $page_title="")
{
	// Load template files
	$tpl_header = new tpl("header.php");
	$tpl_footer = new tpl("base.php");
	$tpl_main = new tpl("tpl/main.tpl");
		
	// Compile and get dynamic template files output data
	$tpl_header->compile();
	$tpl_footer->compile();
	
	$tpl_footer->compiled = ($tpl_footer->compiled) ? ($tpl_footer->compiled) : ("&nbsp;");

	$tpl_main_tags = array( "header"	=> $tpl_header->compiled,
							"content"	=> $page_content,
							"footer"	=> $tpl_footer->compiled,
							"page_title"=> $page_title );
	
	$tpl_main->parse($tpl_main_tags);
	return $tpl_main->parsed;
}

#
# Shows a generic dialog box with a link used to pass messages to the user
# If only content and title provided, the link will be linking to the referrer
function dialog($dialog_content, $dialog_title = "System Message", $link_text = "BACK", $link_url = "referer",
				$link_show = TRUE, $auto_redir = true)
{
	$tpl_dialog = new tpl("tpl/dialog.tpl");
	
	// Check whether not to show the "back" link
	if(!$link_show)
		$tpl_dialog->template = fragment_delete("link_back", $tpl_dialog->template);

	if($link_url == "referer")
		$link_url = (!empty($_SERVER['HTTP_REFERER'])) ? ($_SERVER['HTTP_REFERER']) : ("index.php");

	$tpl_dialog_tags = array("dialog_message"	=> $dialog_content,
							 "link_text"		=> $link_text,
							 "link_url"			=> $link_url );
	$tpl_dialog->parse($tpl_dialog_tags);
	
	die(build_page(content_box($tpl_dialog->parsed, $dialog_title), $dialog_title));
}

#
# Error dialog box
# Can be used for error reporting
function error($error_content, $error_title="Error")
{
	if(is_file("../tpl/error.tpl"))
		$tpl_file = "../tpl/error.tpl";
	elseif(is_file("tpl/error.tpl"))
		$tpl_file = "tpl/error.tpl";
		
	$tpl_error = new tpl($tpl_file);

	if(defined("DEBUG_MODE"))
		$mysql_error = (mysql_error()!="") ? ("<b>mySQL Returned:</b> " . mysql_error()) : ("");
	
	$tpl_error_tags = array( "error_title"		=> $error_title,
							 "error_content"	=> $error_content,
							 "error_mysql"		=> $mysql_error );
							 
	$tpl_error->parse($tpl_error_tags);
	
	die($tpl_error->parsed);
}

#
# Gets a fragment of code from a html code segment
# Code must be between <!--BEGIN pointername--> and <!--END pointername-->
function fragment_get($pointer, $subject)
{
	$ptr_begin	= "<!--BEGIN $pointer-->";
	$ptr_end	= "<!--END $pointer-->";

	$fragment_begin		= strpos($subject, $ptr_begin)+strlen($ptr_begin);
	$fragment_length	= strpos($subject, $ptr_end) - $fragment_begin;
	
	
	return substr($subject, $fragment_begin, $fragment_length);
}

#
# Replaces a fragment of code within a html code segment
# Code must be between <!--BEGIN pointername--> and <!--END pointername-->
function fragment_replace($pointer, $replacement, $subject)
{
	$ptr_begin	= "<!--BEGIN $pointer-->";
	$ptr_end	= "<!--END $pointer-->";

	$fragment_begin		= strpos($subject, $ptr_begin);
	$fragment_length	= strpos($subject, $ptr_end)-$fragment_begin+strlen($ptr_end);

	return substr_replace($subject, $replacement, $fragment_begin, $fragment_length);
}

#
# Replaces a fragment of code within a html code segment
# Code must be between <!--BEGIN pointername--> and <!--END pointername-->
function fragment_delete($pointer, &$subject)
{
	$subject = fragment_replace($pointer, "", $subject);
	return $subject;
}

#
# Replaces tags in a html code segment
# Tag format must be {tag_name}. Similar to $tpl->parse()
function replace_tags($tags, $subject)
{
	if(count($tags) > 0)
	{
		foreach($tags as $tag=>$contents)
		{
			$subject = str_replace("{".$tag."}", $contents, $subject);
		}
		return $subject;
	}
}
?>