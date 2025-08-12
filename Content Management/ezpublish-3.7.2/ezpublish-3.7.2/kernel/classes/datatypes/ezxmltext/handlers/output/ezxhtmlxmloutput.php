<?php
//
// Definition of eZXHTMLXMLOutput class
//
// Created on: <28-Jan-2003 15:05:00 bf>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
*/

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmloutputhandler.php' );
include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );

class eZXHTMLXMLOutput extends eZXMLOutputHandler
{
    function eZXHTMLXMLOutput( &$xmlData, $aliasedType, &$contentObjectAttribute )
    {
        $this->eZXMLOutputHandler( $xmlData, $aliasedType );
        $this->ContentObjectAttribute = $contentObjectAttribute;
    }

    /*!
     \reimp
    */
    function &outputText()
    {
        $retVal =& $this->xhtml();
        return $retVal;
    }

    /*!
     \return the XHTML rendered value of the XML data
    */
    function &xhtml()
    {
        $output = "";
        $tpl =& templateInit();
        $xml = new eZXML();
        $res =& eZTemplateDesignResource::instance();
        if ( $this->ContentObjectAttribute )
        {
            $res->setKeys( array( array( 'attribute_identifier', $this->ContentObjectAttribute->attribute( 'contentclass_attribute_identifier' ) ) ) );
        }
        $dom =& $xml->domTree( $this->XMLData );
        if ( $dom )
        {
            $domNode =& $dom->elementsByName( "section" );

            $relatedObjectIDArray = array();
            $nodeIDArray = array();

            // Fetch all links and cache the url's
            $links =& $dom->elementsByName( "link" );

            if ( count( $links ) > 0 )
            {
                $linkIDArray = array();
                // Find all Link id's
                foreach ( $links as $link )
                {
                    $linkID = $link->attributeValue( 'url_id' );
                    if ( $linkID != null )
                        if ( !in_array( $linkID, $linkIDArray ) )
                            $linkIDArray[] = $linkID;

                    $objectID = $link->attributeValue( 'object_id' );
                    if ( $objectID != null )
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;

                    $nodeID = $link->attributeValue( 'node_id' );
                    if ( $nodeID != null )
                        if ( !in_array( $nodeID, $nodeIDArray ) )
                            $nodeIDArray[] = $nodeID;
                }

                if ( count( $linkIDArray ) > 0 )
                {
                    $inIDSQL = implode( ', ', $linkIDArray );

                    $db =& eZDB::instance();
                    $linkArray = $db->arrayQuery( "SELECT * FROM ezurl WHERE id IN ( $inIDSQL ) " );

                    foreach ( $linkArray as $linkRow )
                    {
                        $this->LinkArray[$linkRow['id']] = $linkRow['url'];
                    }
                }
            }

            // Fetch all embeded objects and cache by ID
            $objectArray =& $dom->elementsByName( "object" );

            if ( count( $objectArray ) > 0 )
            {
                foreach ( $objectArray as $object )
                {
                    $objectID = $object->attributeValue( 'id' );
                    if ( $objectID != null )
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;
                }
            }

            $embedTagArray =& $dom->elementsByName( "embed" );

            if ( count( $embedTagArray ) > 0 )
            {
                foreach ( $embedTagArray as $embedTag )
                {
                    $objectID = $embedTag->attributeValue( 'object_id' );
                    if ( $objectID != null )
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;

                    $nodeID = $embedTag->attributeValue( 'node_id' );
                    if ( $nodeID !=null )
                        if ( !in_array( $nodeID, $nodeIDArray ) )
                            $nodeIDArray[] = $nodeID;
                }
            }

            if ( $relatedObjectIDArray != null )
                $this->ObjectArray =& eZContentObject::fetchIDArray( $relatedObjectIDArray );

            if ( $nodeIDArray != null )
            {
                $nodes = eZContentObjectTreeNode::fetch( $nodeIDArray );

                if ( is_array( $nodes ) )
                {
                    foreach( $nodes as $node )
                    {
                        $nodeID = $node->attribute( 'node_id' );
                        $this->NodeArray["$nodeID"] = $node;
                    }
                }
                elseif ( $nodes )
                {
                    $node =& $nodes;
                    $nodeID = $node->attribute( 'node_id' );
                    $this->NodeArray["$nodeID"] = $node;
                }
              //  else
              //  {
              //      eZDebug::writeError( "Embedded node(s) fetching failed", "XML output handler" );
              //  }
            }

            $sectionNode =& $domNode[0];
            if ( get_class( $sectionNode ) == "ezdomnode" )
            {
                $output =& $this->renderXHTMLSection( $tpl, $sectionNode, 0 );
            }
        }
        $res->removeKey( 'attribute_identifier' );
        return $output;
    }

    /*!
     \private
     \return the XHTML rendered version of the section
    */
    function &renderXHTMLSection( &$tpl, &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', "level " . $section->toString( 0 ) );
        foreach ( $section->children() as $sectionNode )
        {
            if ( $tdSectionLevel == null )
            {
                $sectionLevel = $currentSectionLevel;
            }
            else
            {
                $sectionLevel = $tdSectionLevel;
                $currentSectionLevel = $currentSectionLevel;
            }
            $tagName = $sectionNode->name();
            switch ( $tagName )
            {
                // tags with parameters
                case 'header' :
                {
                   // Add the anchor tag before the header.
                   $name = $sectionNode->attributeValue( 'anchor_name' );
                   $class = $sectionNode->attributeValue( 'class' );

                   $res =& eZTemplateDesignResource::instance();
                   $res->setKeys( array( array( 'classification', $class ) ) );

                   if ( $name )
                   {
                       $tpl->setVariable( 'name', $name, 'xmltagns' );

                       $uri = "design:content/datatype/view/ezxmltags/anchor.tpl";

                       eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                       $output .= implode( '', $textElements );
                   }

                   $level = $sectionLevel;
                   $tpl->setVariable( 'content', $sectionNode->textContent(), 'xmltagns' );
                   $tpl->setVariable( 'level', $level, 'xmltagns' );
                   $tpl->setVariable( 'classification', $class, 'xmltagns' );
                   $uri = "design:content/datatype/view/ezxmltags/header.tpl";
                   $textElements = array();
                   eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                   $output .= implode( '', $textElements );

                   // Remove the design key, so it will not override other tags
                   $res->removeKey( 'classification' );
                   $tpl->unsetVariable( 'classification', 'xmltagns' );
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderXHTMLParagraph( $tpl, $sectionNode, $currentSectionLevel, $tdSectionLevel );
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', "level ". $sectionLevel );
                    if ( $tdSectionLevel == null )
                        $output .= $this->renderXHTMLSection( $tpl, $sectionNode, $sectionLevel );
                    else
                        $output .= $this->renderXHTMLSection( $tpl, $sectionNode, $currentSectionLevel, $sectionLevel );
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputSectionXML()" );
                }break;
            }
        }
        return $output;
    }

    /*!
     \private
     \return the XHTML rendered version of the section
    */
    function &renderList( &$tpl, &$listNode, $currentSectionLevel, $listSectionLevel = null )
    {
        $output = "";
        $tagName = $listNode->name();
        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= $this->renderXHTMLParagraph( $tpl, $listNode, $currentSectionLevel, $listSectionLevel );
            }break;

            case 'section' :
            {
                $sectionLevel += 1;
                if ( $listSectionLevel == null )
                    $output .= $this->renderXHTMLSection( $tpl, $listNode, $sectionLevel );
                else
                    $output .= $this->renderXHTMLSection( $tpl, $listNode, $currentSectionLevel, $sectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputSectionXML()" );
            }break;
        }
        return $output;
    }

    /*!
     \private
     \return XHTML rendered version of the paragrph
    */
    function &renderXHTMLParagraph( &$tpl, $paragraph, $currentSectionLevel, $tdSectionLevel = null )
    {
        $insideParagraph = true;
        $paragraphCount = 0;
        $paragraphContentArray = array();

        $sectionLevel = $currentSectionLevel;
        $class = $paragraph->attributeValue( 'class' );
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $isBlockTag = false;
            $content =& $this->renderXHTMLTag( $tpl, $paragraphNode, $sectionLevel, $isBlockTag, $tdSectionLevel );
            if ( $isBlockTag === true )
            {
                $paragraphCount++;
            }

            if ( !isset( $paragraphContentArray[$paragraphCount]['Content'] ) )
                $paragraphContentArray[$paragraphCount] = array( "Content" => $content, "IsBlock" => $isBlockTag );
            else
                $paragraphContentArray[$paragraphCount] = array( "Content" => $paragraphContentArray[$paragraphCount]['Content'] . $content, "IsBlock" => $isBlockTag );
            if ( $isBlockTag === true )
            {
                $paragraphCount++;
            }
        }
        $output = "";
        foreach ( $paragraphContentArray as $paragraphContent )
        {
            if ( !$paragraphContent['IsBlock'] )
            {
                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'content', $paragraphContent['Content'], 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/paragraph.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $output .= implode( '', $textElements );

                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
                $tpl->unsetVariable( 'classification', 'xmltagns' );
            }
            else
            {
                $output .= $paragraphContent['Content'];
            }

        }
        if ( $paragraph->children() == null )
            $output = "<p>&nbsp;</p>";
        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderXHTMLTag( &$tpl, &$tag, $currentSectionLevel, &$isBlockTag, $tdSectionLevel = null, $isChildOfLinkTag = false )
    {
        $tagText = "";
        $childTagText = "";
        $tagName = $tag->name();

        if ( !$isChildOfLinkTag && count( $this->LinkParameters ) )
            $this->LinkParameters = array();

        // Set link parameters for rendering children of link tag
        if ( $tagName=="link" )
        {
            $href='';
            if ( $tag->attributeValue( 'url_id' ) != null )
            {
                $linkID = $tag->attributeValue( 'url_id' );
                $href = $this->LinkArray[$linkID];

                include_once( 'lib/ezutils/classes/ezmail.php' );
                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;
            }
            elseif ( $tag->attributeValue( 'node_id' ) != null )
            {
                $nodeID = $tag->attributeValue( 'node_id' );
                $node =& $this->NodeArray[$nodeID];
                if ( $node != null )
                {
                    $href = $node->attribute( 'url_alias' );
                }
                //else
                //{
                //    eZDebug::writeError( "Node $nodeID doesn't exist", "XML output handler" );
                //}
            }
            elseif ( $tag->attributeValue( 'object_id' ) != null )
            {
                $objectID = $tag->attributeValue( 'object_id' );
                $object =& $this->ObjectArray["$objectID"];
                if ( $object )
                {
                    $node =& $object->attribute( 'main_node' );
                    if ( $node )
                    {
                        $href = $node->attribute( 'url_alias' );
                    }
                   // else
                   // {
                   //     eZDebug::writeError( "Object $objectID is not attached to a node", "XML output handler" );
                   // }
                }
               // else
               //  {
               //     eZDebug::writeError( "Object $objectID doesn't exist", "XML output handler" );
               //  }
            }
            elseif ( $tag->attributeValue( 'href' ) != null )
            {
                $href = $tag->attributeValue( 'href' );
                include_once( 'lib/ezutils/classes/ezmail.php' );
                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;
            }

            if ( $tag->attributeValue( 'anchor_name' ) != null )
            {
                $href .= '#' . $tag->attributeValue( 'anchor_name' );
            }

            if ( $href != '' )
            {

               // Making valid URI  (commented cause decided to use ezurl in template)
               // include_once( 'lib/ezutils/classes/ezuri.php' );
               //  eZURI::transformURI( $href );

                $this->LinkParameters['href'] = $href;

                $this->LinkParameters['class'] = $tag->attributeValue( 'class' );
                $this->LinkParameters['target'] = $tag->attributeValue( 'target' );
                $this->LinkParameters['title'] = $tag->attributeValueNS( 'title', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
                $this->LinkParameters['id'] = $tag->attributeValueNS( 'id', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
            }
        }

        // render children tags using recursion
        $tagChildren = $tag->children();

        foreach ( $tagChildren as $childTag )
        {
            switch( $tagName )
            {
                case "literal" :
                {
                    $childTagText .= $childTag->content();
                }break;
                case "link" :
                {
                    // we use no template for link tag, all link parameters are used
                    // inside the templates of it's children, so we update tagText directly
                    $tagText .= $this->renderXHTMLTag( $tpl, $childTag, $currentSectionLevel, $isBlockTag, $tdSectionLevel, $href != '' );
                }break;
                default :
                    $childTagText .= $this->renderXHTMLTag( $tpl, $childTag, $currentSectionLevel, $isBlockTag, $tdSectionLevel, $isChildOfLinkTag );
            }
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $text = htmlspecialchars( $tag->content() );
                // Get rid of linebreak and spaces stored in xml file
                $text = preg_replace( "#[\n]+#", "", $text );
                $text = preg_replace( "#    #", "", $text );

                if ( $isChildOfLinkTag )
                {
                    $res =& eZTemplateDesignResource::instance();
                    $res->setKeys( array( array( 'classification', $this->LinkParameters['class'] ) ) );

                    $tpl->setVariable( 'content', $text, 'xmltagns' );

                    $tpl->setVariable( 'href', $this->LinkParameters['href'], 'xmltagns' );
                    $tpl->setVariable( 'target', $this->LinkParameters['target'], 'xmltagns' );
                    $tpl->setVariable( 'classification', $this->LinkParameters['class'], 'xmltagns' );
                    $tpl->setVariable( 'title', $this->LinkParameters['title'], 'xmltagns' );
                    $tpl->setVariable( 'id', $this->LinkParameters['id'], 'xmltagns' );

                    $uri = "design:content/datatype/view/ezxmltags/link.tpl";

                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );

                    $tagText .= implode( '', $textElements );

                    // Remove the design key, so it will not override other tags
                    $res->removeKey( 'classification' );
                    $tpl->unsetVariable( 'classification', 'xmltagns' );
                }
                else
                {
                    $tagText .= $text;
                }
            }break;

            case 'object' :
            {
               // $isBlockTag = true;
                $objectID = $tag->attributeValue( 'id' );
                // fetch attributes
                $objectAttributes =& $tag->attributes();
                $object =& $this->ObjectArray["$objectID"];
                // Fetch from cache
                if ( get_class( $object ) == "ezcontentobject" and
                     $object->attribute( 'status' ) == EZ_CONTENT_OBJECT_STATUS_PUBLISHED )
                {
                    $view = $tag->attributeValue( 'view' );
                    $alignment = $tag->attributeValue( 'align' );
                    $class = $tag->attributeValue( 'class' );

                    $res =& eZTemplateDesignResource::instance();

                    // Save current class identifier for it to be restored below
                    $savedKeys = $res->keys();
                    $savedClassIdentifier = $savedKeys['class_identifier'];
                    unset( $savedKeys );

                    $res->setKeys( array( array( 'classification', $class ),
                                          array( 'class_identifier', $object->attribute( 'class_identifier' ) ) ) );

                    $hasLink = false;
                    $linkID = $tag->attributeValueNS( 'ezurl_id', "http://ez.no/namespaces/ezpublish3/image/" );

                    if ( $linkID != null )
                    {
                        $href = eZURL::url( $linkID );
                        $target = $tag->attributeValueNS( 'ezurl_target', "http://ez.no/namespaces/ezpublish3/image/" );
                        $hasLink = true;
                    }

                    if ( !isset( $target ) )
                        $target = "_self";

                    $objectParameters = array();
                    $objectParameters['align'] = 'right';
                    foreach ( $objectAttributes as $attribute )
                    {
                        if ( $attribute->name() == "ezurl_id" )
                        {
                            $this->LinkParameters = array();
                            $this->LinkParameters['href'] = $href;
                        }
                        else if ( $attribute->name() == "ezurl_target" )
                            $this->LinkParameters['target'] = $target;
                        else if ( $attribute->name() == "align" )
                            $objectParameters['align'] = $alignment;
                        else
                            $objectParameters[$attribute->name()] = $attribute->content();
                    }

                    if ( strlen( $view ) == 0 )
                        $view = "embed";

                    if ( $object->attribute( 'can_read' ) )
                    {
                        $xmlTemplate = 'object';
                    }
                    else
                    {
                        $xmlTemplate = 'object_denied';
                    }

                    $tpl->setVariable( 'classification', $class, 'xmltagns' );
                    $tpl->setVariable( 'object', $object, 'xmltagns' );
                    $tpl->setVariable( 'view', $view, 'xmltagns' );
                    $tpl->setVariable( 'object_parameters', $objectParameters, 'xmltagns' );
                    $tpl->setVariable( 'link_parameters', $this->LinkParameters, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/$xmlTemplate.tpl";

                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                    $tagText = implode( '', $textElements );

                    // Set to true if tag breaks paragraph flow as default
                    $isBlockTag = true;

                    // Check if the template overrides the block flow setting
                    $isBlockTagOverride = 'true';
                    if ( $tpl->hasVariable( 'is_block', 'xmltagns:ContentView' ) )
                    {
                        $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns:ContentView' );
                    }
                    else if ( $tpl->hasVariable( 'is_block', 'xmltagns' ) )
                    {
                        $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns' );
                    }

                    if ( $isBlockTagOverride == 'true' )
                        $isBlockTag = true;
                    else
                        $isBlockTag = false;

                    // Remove the design key, so it will not override other tags
                    $res->removeKey( 'classification' );
                    $tpl->unsetVariable( 'classification', 'xmltagns' );

                    // Restore previously saved class identifier
                    $res->removeKey( 'class_identifier' );
                    $res->setKeys( array( array( 'class_identifier', $savedClassIdentifier ) ) );
                }
            }break;

        case 'embed' :
        {
            //$isBlockTag = true;

            $objectID = $tag->attributeValue( 'object_id' );

            if ( $objectID )
            {
                $object =& $this->ObjectArray["$objectID"];
            }

            $nodeID = $tag->attributeValue( 'node_id' );
            if ( $nodeID )
            {
                if ( isset( $this->NodeArray[$nodeID] ) )
                {
                    $node =& $this->NodeArray[$nodeID];
                    $objectID = $node->attribute( 'contentobject_id' );
                    $object =& $node->object();
                }
                else
                {
                 //   eZDebug::writeError( "Node $nodeID doesn't exist", "XML output handler" );
                    break;
                }
            }

			if ( !$object )
            {
                //eZDebug::writeError( "Can't fetch object. objectID: $objectID, nodeID: $nodeID", "XML output handler" );
	            break;
            }

            // fetch attributes
            $embedAttributes =& $tag->attributes();

            // Fetch from cache
            if ( get_class( $object ) == "ezcontentobject" and
                 $object->attribute( 'status' ) == EZ_CONTENT_OBJECT_STATUS_PUBLISHED )
            {
                $view = $tag->attributeValue( 'view' );
                if ( $view == null )
                    $view = 'embed';
                $class = $tag->attributeValue( 'class' );

                $objectParameters = array();
                $objectParameters['align'] = 'right';
                foreach ( $embedAttributes as $attribute )
                {
                   $attrName = $attribute->name();
                   if ( $attrName != 'view' && $attrName != 'class' && $attrName != 'node_id' && $attrName != 'object_id' )
                       $objectParameters[$attribute->name()] = $attribute->content();
                }

                if ( $object->attribute( 'can_read' ) )
                {
                    $xmlTemplate = 'embed';
                }
                else
                {
                    $xmlTemplate = 'embed_denied';
                }

                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $tpl->setVariable( 'view', $view, 'xmltagns' );
                $tpl->setVariable( 'object_parameters', $objectParameters, 'xmltagns' );
                $tpl->setVariable( 'link_parameters', $this->LinkParameters, 'xmltagns' );

                $uri = "design:content/datatype/view/ezxmltags/$xmlTemplate.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText = implode( '', $textElements );

                // Set to true if tag breaks paragraph flow as default
                $isBlockTag = true;

                // Check if the template overrides the block flow setting
                $isBlockTagOverride = 'true';
                if ( $tpl->hasVariable( 'is_block', 'xmltagns:ContentView' ) )
                {
                    $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns:ContentView' );
                }
                else if ( $tpl->hasVariable( 'is_block', 'xmltagns' ) )
                {
                    $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns' );
                }

                if ( $isBlockTagOverride == 'true' )
                    $isBlockTag = true;
                else
                    $isBlockTag = false;
            }
        }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                if ( $border === null )
                    $border = 1;

                $width = $tag->attributeValue( 'width' );
                if ( $width === null )
                    $width = "100%";

                $tableClassification = $tag->attributeValue( 'class' );

                $rowCount = 0;
                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = "";
                    $cellCount = 0;
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = "";
                        $cellContent .= $this->renderXHTMLSection( $tpl, $tableCell, 0, 0 );

                        $tpl->setVariable( 'content', $cellContent, 'xmltagns' );
                        $cellWidth = $tableCell->attributeValueNS( 'width', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $colspan = $tableCell->attributeValueNS( 'colspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $rowspan = $tableCell->attributeValueNS( 'rowspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );

                        $class = $tableCell->attributeValue( 'class' );

                        $res =& eZTemplateDesignResource::instance();
                        $res->setKeys( array( array( 'classification', $class ),
                                              array( 'table_classification', $tableClassification ) ) );

                        if ( $tableCell->Name == "th" )
                            $uri = "design:content/datatype/view/ezxmltags/th.tpl";
                        else
                            $uri = "design:content/datatype/view/ezxmltags/td.tpl";
                        $textElements = array();
                        $tpl->setVariable( 'classification', $class, 'xmltagns' );
                        $tpl->setVariable( 'colspan', $colspan, 'xmltagns' );
                        $tpl->setVariable( 'rowspan', $rowspan, 'xmltagns' );
                        $tpl->setVariable( 'width', $cellWidth, 'xmltagns' );
                        $tpl->setVariable( 'row_count', $rowCount, 'xmltagns' );
                        $tpl->setVariable( 'col_count', $cellCount, 'xmltagns' );

                        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                        $tableData .= implode( '', $textElements );

                        $cellCount++;
                        // Remove the design key, so it will not override other tags
                        $res->removeKey( 'classification' );
                        if ( $tpl->hasVariable( 'classification', 'xmltagns' ) )
                             $tpl->unsetVariable( 'classification', 'xmltagns' );
                        $res->removeKey( 'table_classification' );
                        if ( $tpl->hasVariable( 'table_classification', 'xmltagns' ) )
                             $tpl->unsetVariable( 'table_classification', 'xmltagns' );
                    }
                    $res =& eZTemplateDesignResource::instance();
                    $res->setKeys( array( array( 'classification', $class ),
                                          array( 'table_classification', $tableClassification ) ) );

                    $tpl->setVariable( 'content', $tableData, 'xmltagns' );
                    $tpl->setVariable( 'row_count', $rowCount, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/tr.tpl";
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                    $tableRows .= implode( '', $textElements );
                    $rowCount++;

                    // Remove the design key, so it will not override other tags
                    $res->removeKey( 'classification' );
                    if ( $tpl->hasVariable( 'classification', 'xmltagns' ) )
                        $tpl->unsetVariable( 'classification', 'xmltagns' );
                    $res->removeKey( 'table_classification' );
                    if ( $tpl->hasVariable( 'table_classification', 'xmltagns' ) )
                        $tpl->unsetVariable( 'table_classification', 'xmltagns' );
                }

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $tableClassification ) ) );

                $tpl->setVariable( 'classification', $tableClassification, 'xmltagns' );
                $tpl->setVariable( 'rows', $tableRows, 'xmltagns' );
                $tpl->setVariable( 'border', $border, 'xmltagns' );
                $tpl->setVariable( 'width', $width, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/table.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText .= implode( '', $textElements );
                $isBlockTag = true;

                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
                $tpl->unsetVariable( 'classification', 'xmltagns' );
            }break;

            case 'ul' :
            case 'ol' :
            {
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $isBlockTag = true;

                $listContent = "";
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = "";

                    $listSectionLevel = $currentSectionLevel;

                    $listChildren = $listItemNode->children();

                    // If <paragraph> is the one and only child then don't render it as <p>.
                    if ( count( $listChildren ) == 1 )
                    {
                        if ( $listChildren[0]->name() == "paragraph" )
                        {
                            $listChildren = $listChildren[0]->children();
                        }
                    }

                    foreach ( $listChildren as $itemChildNode )
                    {
                        $listSectionLevel = $currentSectionLevel;
                        if ( $itemChildNode->name() == "section" or $itemChildNode->name() == "paragraph" )
                        {
                            $listItemContent .= $this->renderList( $tpl, $itemChildNode, $currentSectionLevel, $listSectionLevel );
                        }
                        else
                        {
                            $listItemContent .= $this->renderXHTMLTag( $tpl, $itemChildNode, 0, $isBlockTag );
                        }
                    }

                    $liClass = $listItemNode->attributeValue( 'class' );
                    $tpl->setVariable( 'classification', $liClass, 'xmltagns' );
                    $tpl->setVariable( 'content', $listItemContent, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/li.tpl";

                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $listContent .= implode( '', $textElements );
                }

                $className = $tag->attributeValue( 'class' );
                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'content', $listContent, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
                $tpl->unsetVariable( 'classification', 'xmltagns' );
            }break;

            // Literal text which allows xml specific caracters < >
            case 'literal' :
            {
                $isBlockTag = true;

                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
                $tpl->unsetVariable( 'classification', 'xmltagns' );
            }break;

            // normal content tags
            case 'emphasize' :
            case 'strong' :
            case 'line' :
            {
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $tpl->setVariable( 'classification', $class, 'xmltagns' );

                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
                $tagText = trim( $tagText );

                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
                $tpl->unsetVariable( 'classification', 'xmltagns' );
            }break;

            // custom tags which could added for special custom needs.
            case 'custom' :
            {
                // Get the name of the custom tag.
                $name = $tag->attributeValue( 'name' );
                $isInline = false;
                include_once( "lib/ezutils/classes/ezini.php" );
                $ini =& eZINI::instance( 'content.ini' );

                $isInlineTagList = $ini->variable( 'CustomTagSettings', 'IsInline' );
                foreach ( array_keys ( $isInlineTagList ) as $key )
                {
                    $isInlineTagValue =& $isInlineTagList[$key];
                    if ( $isInlineTagValue )
                    {
                        if ( $name == $key )
                            $isInline = true;
                    }
                }

                if ( $isInline )
                {
                    $childContent = $childTagText;
                }
                else
                {
                    $childContent = $this->renderXHTMLSection( $tpl, $tag, $currentSectionLevel, $tdSectionLevel );
                    $isBlockTag = true;
                }

                $customAttributes =& $tag->attributesNS( "http://ez.no/namespaces/ezpublish3/custom/" );
                foreach ( $customAttributes as $attribute )
                {
                    $tpl->setVariable( $attribute->Name, $attribute->Content, 'xmltagns' );
                }

                $tpl->setVariable( 'content',  $childContent, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$name.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
                $tagText = trim( $tagText );

                foreach ( $customAttributes as $attribute )
                {
                    $tpl->unsetVariable( $attribute->Name, 'xmltagns' );
                }
            }break;

            case 'anchor' :
            {
                $name = $tag->attributeValue( 'name' );

                $tpl->setVariable( 'name', $name, 'xmltagns' );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            default :
            {
                // unsupported tag
            }break;
        }
        return $tagText;
    }

    /// Contains the URL's for <link> tags hashed by ID
    var $LinkArray = array();

    /// Contains the Objects hashed by ID
    var $ObjectArray = array();

    /// Contains the Nodes hashed by ID
    var $NodeArray = array();

    /// Array of parameters for rendering tags that are children of 'link' tag
    var $LinkParameters = array();
}

?>
