<?php
/*  
 * XhtmlDocument.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class represents an xhtml document.
 *
 * Author(s):
 *   Alejandro Espinoza <aespinoza@structum.com.mx>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 */

import("moebius2.base.ObjectManager");
import("org.active-link.xml.XML");
import("moebius2.xhtml.base.Head");
import("moebius2.xhtml.base.Body");


/* --- Constants --- */
// Tags types
define("TAG_LINK", 0);
define("TAG_TEXT", 1);

// Meta tag attribute types.
define("META_HTTP_EQUIV", 0);
define("META_NAME", 1);

// Return types
define("RET_ARRAY", 0);
define("RET_ARRAY_XML", 1);
define("RET_TEXT", 2);
define("RET_XML", 3);

/**
  * Class that reprensents an xhtml document.
  *
  * Document's Structure :
  * - Header
  * - Content
  * - Footer
  * 
  * @class		XhtmlDocument
  * @package	moebius2.xhtml.base
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	0.1
  * @extends	ObjectManager
  * @requires	ObjectManager, XML, Head, Body
  * @see		XML
  */
class XhtmlDocument extends ObjectManager
{
	/* --- Attributes --- */	
	var $type;
	var $title;
	var $author;
	var $description;

	// HTML Components
	var $htmlHead;
	var $htmlBody;
	var $xhtml;	

	// Components	
	var $header;
	var $content;
	var $footer;

	var $body;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes the html document, and sets the default document type to <i>XHTML 1.0 Transitional//EN</i>
	  * @method		XhtmlDocument
	  * @returns	none
	  */
	function XhtmlDocument()
	{
		ObjectManager::ObjectManager("moebius2.xhtml.base", "XhtmlDocument");

		//$this->SetType("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");

		$this->htmlHead =& new Head();
		$this->htmlBody =& new Body();
	}   

	/**
	  * Changes the Document Type.
	  *
	  * NOTE: Remeber that this class manages XHTML; if you want your document valid with standards
	  * you should only use XHTML as a document type.
	  * @method		SetType
	  * @param		string docType
	  * @returns	none
	  */		
	function SetType($docType)
	{
		$this->type = $docType;
	}

	/**
	  * Returns the document type.
	  * @method		GetType
	  * @returns	string formatted document type
	  */		
	function GetType()
	{
		return $this->type;
	}

	/**
	  * Changes the document's title.
	  * @method		SetTitle
	  * @param		string title
	  * @returns	none
	  */			
	function SetTitle($title)
	{
		$this->title = $title;
	}

	/**
	  * Returns the document's title.
	  * @method		GetTitle
	  * @returns	string containing the document's title
	  */	
	function GetTitle()
	{
		return $this->title;
	}

	/**
	  * Changes the document's author.
	  * @method		SetAuthor
	  * @param		string author
	  * @returns	none
	  */				
	function SetAuthor($author)
	{
		$this->author = $author;        
	}

	/**
	  * Returns the document's author.
	  * @method		GetAuthor
	  * @returns	string containing the document's author
	  */		
	function GetAuthor()
	{
		return $this->author;
	}

	/**
	  * Changes the document's description.
	  * @method		SetDescription
	  * @param		string desc
	  * @returns	none
	  */	
	function SetDescription($desc)
	{
		$this->description = $desc;        
	}

	/**
	  * Returns the document's description.
	  * @method		GetDescription
	  * @returns	string containing the document's description
	  */		
	function GetDescription()
	{
		return $this->description;
	}	

	/**
	  * Adds a metadata (&lt;meta&gt;) text to the document.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Head
	  * @method		AddMetadata
	  * @param		string content
	  * @param		string attribContent
	  * @param		optional int attribType	  
	  * @returns	none
	  */	
	function AddMetadata($attribContent, $content, $attribType=META_HTTP_EQUIV)
	{
		$this->htmlHead->AddMetadata($attribContent, $content, $attribType);
	}

	/**
	  * Returns the list of metadata added to the document.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Head	  
	  * @method		GetMetadata
	  * @returns	list of metadata; depending on the returnType selected : array, XMLBranch  array or string.
	  */	
	function GetMetadata($returnType=RET_ARRAY)
	{
		$this->htmlHead->GetMetadata($returnType);
	}

	/**
	  * Adds a CSS Style Sheet link or text to the document.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Head
	  * @method		AddStyle
	  *	@param		string style
	  *	@param		optional int type
	  * @returns	none
	  */	
	function AddStyle($style, $type=TAG_LINK)
	{
		$this->htmlHead->AddStyle($style, $type);
	}

	/**
	  * Returns the list of styles added to the document.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Head
	  * @method		GetStyles
	  * @param		int returnType
	  * @returns	list of styles; depending on the returnType selected : array, XMLBranch array or string.
	  */	
	function GetStyles($returnType=RET_ARRAY)
	{
		$this->htmlHead->GetStyles($returningType);
	}
	
	/**
	  * Adds a script to the document.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Head
	  * @method		AddScript
	  * @param		string src
	  * @param		optional int  type
	  * @returns	none
	  */	
	function AddScript($script, $type=TAG_LINK)
	{
		$this->htmlHead->AddScript($script, $type);
	}

	/**
	  * Returns the list of scripts added to the document.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Head	  
	  * @method		GetScripts
	  * @param		optional int returnType
	  * @returns	list of scripts; depending on the returnType selected : array, XMLBranch array or string.
	  */	
	function GetScripts($returnType=RET_ARRAY)
	{
		$this->htmlHead->GetScripts($returnType);
	}

	/**
	  * Adds 1 or several attributes to the document's body.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Body	  
	  * @method		AddAttrib
	  * @param		string attribName
	  * @param		string attribVal
	  * @returns	none
	  */		
	function AddAttribute($attribName, $attribVal)
	{
		$this->htmlBody->AddAttribute($attribName, $attribVal);
	}

	/**
	  * Returns the attributes added to the document's body.
	  *
	  * See Head class for more information.
	  *	  
	  * @see		Body	  
	  * @method		GetAttrib
	  * @param		int returnType
	  * @returns	body tag; depending on the returnType selected : array, XMLBranch or string.	  
	  */			
	function GetAttrib($returnType=RET_ARRAY)
	{
		$this->htmlBody->GetAttrib($returnType);
	}	
	
	/**
	  * Changes the Document Header.
	  *
	  * @method		SetHeader
	  * @param		string header
	  * @returns	none
	  */		
	function SetHeader($header)
	{
		$xml =& new XMLBranch("header");
		
		if($xml->parseFromString("<header>".$header."</header>")) {			
			$this->header =& $xml;
		} else {
			$this->SendErrorMessage("SetHeader", "The 'header' argument does not contain valid xml code; it failed the xml parsing test.");
		}				
	}

	/**
	  * Returns the document header.
	  * @method		GetHeader
	  * @returns	object of type XMLBranch containing the document header.
	  */		
	function GetHeader()
	{		
		return $this->header;
	}

	/**
	  * Changes the Document Content.
	  *
	  * @method		SetContent
	  * @param		string content
	  * @returns	none
	  */		
	function SetContent($content)
	{
		$xml =& new XMLBranch("content");
		if($xml->parseFromString("<content>".$content."</content>")) {
			$this->content = $xml;
		} else {
			$this->SendErrorMessage("SetContent", "The 'content' argument does not contain valid xml code; it failed the xml parsing test.");
		}				
	}

	/**
	  * Returns the document content.
	  * @method		GetContent
	  * @returns	object of type XMLBranch containing the document content.
	  */		
	function GetContent()
	{
		return $this->content;
	}	

	/**
	  * Changes the Document Footer.
	  *
	  * @method		SetFooter
	  * @param		string footer
	  * @returns	none
	  */		
	function SetFooter($footer)
	{
		$xml =& new XMLBranch("footer");
		if($xml->parseFromString("<footer>".$footer."</footer>")) {
			$this->footer = $xml;		   
		} else {
			$this->SendErrorMessage("SetFooter", "The 'footer' argument does not contain valid xml code; it failed the xml parsing test.");
		}				
	}

	/**
	  * Returns the document footer.
	  * @method		GetFooter
	  * @returns	object of type XMLBranch containing the document footer.
	  */		
	function GetFooter()
	{
		return $this->footer;
	}	
	
	/**
	  * Generates the html code for the document.
	  * @method		Generate
	  * @returns	none
	  */
	//FIXME: This method can't add the document type to the XMLBranch Object; and it should.
	function Generate()
	{
		$doc =& new XML("html");
		$str = "";
		$bodyHasContent = false;
		
		// Add htmlHead
		$this->htmlHead->SetTitle($this->GetTitle());
		$this->htmlHead->AddMetadata("author", $this->author, META_NAME);
		$this->htmlHead->AddMetadata("description", $this->description, META_NAME);
		
		$doc->addXMLAsBranch($this->htmlHead->GetXhtml());

		if(is_object($this->header) && get_class($this->header)=="xmlbranch") {
			$branches =& $this->header->getBranches("header");
			$count = (empty($branches) ? 0 : count($branches));

			if($count > 0) {
				$bodyHascontent = true;
			}
			
			for($i = 0; $i < $count; $i++) {
				$this->htmlBody->AddXmlBranch($branches[$i]);
			}			
		}
	
		if(is_object($this->content) && get_class($this->content)=="xmlbranch") {
			$branches =& $this->content->getBranches("content");
			$count = (empty($branches) ? 0 : count($branches));

			if($count > 0) {
				$bodyHascontent = true;
			}			

			for($i = 0; $i < $count; $i++) {
				$this->htmlBody->AddXmlBranch($branches[$i]);
			}			
		}

		if(is_object($this->footer) && get_class($this->footer)=="xmlbranch") {
			$branches =& $this->footer->getBranches("footer");
			$count = (empty($branches) ? 0 : count($branches));

			if($count > 0) {
				$bodyHascontent = true;
			}
			
			for($i = 0; $i < $count; $i++) {
				$this->htmlBody->AddXmlBranch($branches[$i]);
			}						
		}

		if(!$bodyHascontent) {
			$this->htmlBody->SetContent("<br /><br />");
		}
	
		$doc->addXMLBranch($this->htmlBody->GetXhtml());

		$this->xhtml =& $doc;
	}

	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the document if autoGenerate is set to true (Default).
	  * @method		GetXhtml
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the xhtml document.
	  */	
	function GetXhtml($autoGenerate=true)
	{
		if($autoGenerate==true) {
			$this->Generate();
		}
		
		return $this->xhtml;
	}

	/**
	  * Returns the xhtml code in a string; it can also build the Xhtml code for the document if autoGenerate is set to true (Default).
	  * It can also format the string, depending on if 'formatString' is set to true.
	  * This method also adds the document type.
	  *
	  * @method		GetStringXhtml
	  * @param		optional bool autoGenerate
	  * @param		optional bool formatString 	  
	  * @returns	string containing the xhtml document.
	  */	
	function GetStringXhtml($autoGenerate=true, $formatString=true)
	{
		if($autoGenerate==true) {
			$this->Generate();
		}

		$str  = $this->GetType();
		$str .= $this->xhtml->getXMLString($formatString);
		
		return $str;
	}

	/**
	  * Displays the document in xhtml form. It can be displayed either formatted (with indentation) or a single line string; this is done because IE
	  * destroys the design of a page if the tags are not closed to each other.
	  *
	  * @method		Display
	  * @param		bool formatted
	  * @returns	none
	  */		
	function Display($formatted=false)
	{
		$stringXhtml = $this->GetStringXhtml(true, $formatted);
		   		
		print($stringXhtml);		
	}
}

?>