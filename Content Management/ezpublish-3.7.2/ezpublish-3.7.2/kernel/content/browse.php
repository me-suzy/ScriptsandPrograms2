<?php
//
// Created on: <18-Jul-2002 10:55:01 bf>
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

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'kernel/classes/ezcontentbrowse.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$tpl =& templateInit();
$http =& eZHTTPTool::instance();

$browse = new eZContentBrowse();


if ( isset( $Params['NodeID'] ) && is_numeric( $Params['NodeID'] ) )
{
    $NodeID = $Params['NodeID'];
    $browse->setStartNode( $NodeID );
}

$NodeID = $browse->attribute( 'start_node' );
$Offset = $Params['Offset'];

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$node = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$node )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$contentObject =& $node->attribute( 'object' );
if ( !$contentObject )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$contentObject->attribute( 'can_read' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$cancelAction = trim( $browse->attribute( 'cancel_page' ) );
if ( $cancelAction == trim( $browse->attribute( 'from_page' ) ) )
{
    $cancelAction = false;
}

$res =& eZTemplateDesignResource::instance();

$keyArray = array();
if ( $browse->hasAttribute( 'keys' ) )
{
    $attributeKeys = $browse->attribute( 'keys' );
    if ( is_array( $attributeKeys ) )
    {
        foreach ( $attributeKeys as $attributeKey => $attributeValue )
        {
            $keyArray[] = array( $attributeKey, $attributeValue );
        }
    }
    $res->setKeys( $keyArray );
}

$parents =& $node->attribute( 'path' );

$tpl->setVariable( 'browse', $browse );
$tpl->setVariable( 'main_node', $node );
$tpl->setVariable( 'node_id', $NodeID );
$tpl->setVariable( 'parents', $parents );
$tpl->setVariable( 'csm_menu_item_click_action', '/content/browse' );
$tpl->setVariable( 'cancel_action', $cancelAction );


$viewParameters = array( 'offset' => $Offset );
$tpl->setVariable( 'view_parameters', $viewParameters );

$tpl->setVariable( 'path', false );


$Result = array();

// Fetch the navigation part from the section information
include_once( 'kernel/classes/ezsection.php' );
$section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
$Result['navigation_part'] = false;
if ( $section )
{
    $Result['navigation_part'] = $section->attribute( 'navigation_part_identifier' );
}

//setting keys for override
$res =& eZTemplateDesignResource::instance();

$object = $node->attribute( 'object' );

if (isset( $GLOBALS['eZDesignKeys']['section'] ))
{
    $globalSectionID = $GLOBALS['eZDesignKeys']['section'];
    unset($GLOBALS['eZDesignKeys']['section']);
}

$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                      array( 'node', $node->attribute( 'node_id' ) ), // Node ID
                      array( 'parent_node', $node->attribute( 'parent_node_id' ) ), // Parent Node ID
                      array( 'class', $object->attribute( 'contentclass_id' ) ), // Class ID
                      array( 'view_offset', $Offset ),
                      array( 'navigation_part_identifier', $Result['navigation_part'] ),
                      array( 'depth', $node->attribute( 'depth' ) ),
                      array( 'url_alias', $node->attribute( 'url_alias' ) ),
                      array( 'class_identifier', $node->attribute( 'class_identifier' ) ),
                      array( 'section', $object->attribute('section_id') )
                      ) );

$Result['path'] =& $path;
$Result['content'] =& $tpl->fetch( 'design:content/browse.tpl' );

if (isset( $globalSectionID ))
{
    $GLOBALS['eZDesignKeys']['section'] = $globalSectionID;
}

$templatePath = $tpl->variable( 'path' );
if ( $templatePath )
{
    $Result['path'] = $templatePath;
}
else
{
    $path = array();
    foreach ( $parents as $parent )
    {
        $path[] = array( 'text' => $parent->attribute( 'name' ),
                         'url' => '/content/browse/' . $parent->attribute( 'node_id' ) . '/'
                         );
    }
    $path[] = array( 'text' => $contentObject->attribute( 'name' ),
                     'url' => false );
    $Result['path'] = $path;
}


?>
