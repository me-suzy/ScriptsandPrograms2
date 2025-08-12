<?php
/*  
 * Site.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages a generic very simple Site Class for the site templates.
 *	 The class idea is based that a document, any document, is composed of three
 *	 blocks of information Header, Content and Ending. This three components
 *	 are identified as Header, Body and footer; but to avoid confusion of the
 *	 HTML tags that are used with the same name, the head of the HTML document
 *	 is NOT the same as the Header. 
 *
 * Author(s):
 *   Alex Espinoza <aespinoza@structum.com.mx>
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

import("moebius2.base.object");

class Site extends Object
{
	/* ---Attributes--- */
    // Components
    var $htmlHeader;
    var $htmlBody;
    var $htmlFooter;

    // Header
	var $docType;
    var $contentType;
    var $title;

	var $styles;
	var $scripts;
	
	/* ---Services--- */
	function Site()
	{
		Object::Object("moebius2.html", "Site");
		
		$this->SetDocType("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n");
	}

	function CreateLinkTag($rel, $type, $href)
	{
        $link = "<link ";

        if(!empty($rel)) {
            $link .= "rel=\"".$rel."\" ";
        }

        if(!empty($type)) {
            $link .= "type=\"".$type."\" ";
        }

        $link .= "href=\"".$href."\" ";
        
        $link .= ">\n";

		return $link;
	}       

    function CreateMetaTag($name, $httpEquiv, $content)
    {
        $meta = "<meta ";

        if(!empty(name)) {
            $meta .= "name=\"".$name."\" ";
        }

        if(!empty($httEquiv)) {
            $meta .= "http-equiv=\"".$httpEquiv."\" ";
        }

        $meta .= " content=\"".$content."\" ";
        $meta .= ">\n";

        return $meta;            
    }

    function AddMeta($name, $httpEquiv, $content)
    {
        $this->metas .= $this->CreateMetaTag($name, $httpEquiv, $content);
    }

	function AddStyle($href)
	{
		$this->styles .= $this->CreateLinkTag("stylesheet", "text/css", $href);
	}

    function GetStyles()
    {
        return $this->styles;
    }

	function AddScript($src, $content, $type="text/javascript")
	{
		$this->scripts .= "<script type=\"".$type."\" src=\"".$src."\">".$content."</script>\n";
	}

    function GetScripts()
    {
        return $this->scripts;
    }

	function SetDocType($docType)
	{
		$this->docType = $docType;
	}

	function GetDocType()
	{
		return $this->docType;
	}

    function SetTitle($title)
    {
        $this->title = $title;        
    }

    function GetTitle()
    {
        return $this->title;
    }

    function SetAuthor($author)
    {
        $this->author = $author;        
    }

    function GetAuthor()
    {
        return $this->author;
    }

	function GenerateHTMLHeader()
	{
		$this->htmlHeader  = $this->GetDocType();
        $this->htmlHeader .= "<html>\n";
        $this->htmlHeader .= "<head>\n";
        $this->htmlHeader .= "<title>".$this->GetTitle()."</title>\n";

        $this->htmlHeader .= $this->GetMeta();

        if(!empty($this->scripts)) {
            $this->htmlHeader .= $this->GetScripts();
        }

		if(!empty($this->styles)) {
			$this->htmlHeader .= $this->GetStyles();
        }


        $this->htmlHeader .= "</head>\n";
	}

}

?>