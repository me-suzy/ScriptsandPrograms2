<?php
//
// Created on: <03-May-2002 15:17:01 bf>
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
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

include_once( 'lib/ezutils/classes/ezhttptool.php' );

include_once( 'kernel/common/template.php' );

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$tpl =& templateInit();
// Will be sent from the content/edit page and should be kept
// incase the user decides to continue editing.
$FromLanguage = $Params['FromLanguage'];

$ini =& eZINI::instance();

$contentObject =& eZContentObject::fetch( $ObjectID );
if ( $contentObject === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$versionObject =& $contentObject->version( $EditVersion );
if ( !$versionObject )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$versionObject->attribute( 'can_read' ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

$user =& eZUser::currentUser();

$isCreator = ( $versionObject->attribute( 'creator_id' ) == $user->id() );

if ( $Module->isCurrentAction( 'Versions' ) )
{
    return $Module->redirectToView( 'versions', array( $ObjectID, $EditVersion, $LanguageCode, $FromLanguage ) );
}

$sectionID = false;
$placementID = false;
$assignment = false;

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( 'ContentObjectLanguageCode' ) )
    $LanguageCode = $http->postVariable( 'ContentObjectLanguageCode' );
if ( $http->hasPostVariable( 'ContentObjectPlacementID' ) )
    $placementID = $http->postVariable( 'ContentObjectPlacementID' );

$nodeAssignments =& $versionObject->attribute( 'node_assignments' );
$virtualNodeID = null;
if ( is_array( $nodeAssignments ) and
     count( $nodeAssignments ) == 1 )
{
    if ( $contentObject->attribute( 'main_node_id' ) != null )
        $virtualNodeID = $contentObject->attribute( 'main_node_id' );
    else
        $virtualNodeID = null;

    $placementID = $nodeAssignments[0]->attribute( 'id' );
}
else if ( !$placementID && count( $nodeAssignments ) )
{
    foreach ( array_keys( $nodeAssignments ) as $key )
    {
        $nodeAssignment =& $nodeAssignments[$key];
        if ( $nodeAssignment->attribute( 'is_main' ) )
        {
            $placementID = $nodeAssignment->attribute( 'id' );
            $parentNodeID = $nodeAssignment->attribute( 'parent_node' );
            $query="SELECT node_id
                    FROM ezcontentobject_tree
                    WHERE contentobject_id=$ObjectID
                    AND parent_node_id=$parentNodeID";

            $db =& eZDB::instance();
            $nodeListArray = $db->arrayQuery( $query );
            $virtualNodeID = $nodeListArray[0]['node_id'];
            break;
        }
    }
}
$parentNodeID = false;
$mainAssignment = false;
foreach ( array_keys( $nodeAssignments ) as $key )
{
    if ( $nodeAssignments[$key]->attribute( 'is_main' ) == 1 )
    {
        $mainAssignment =& $nodeAssignments[$key];
        $parentNodeID = $mainAssignment->attribute( 'parent_node' );
        break;
    }
}

$contentINI =& eZINI::instance( 'content.ini' );

if ( $Module->isCurrentAction( 'ChangeSettings' ) )
{
    if ( $Module->hasActionParameter( 'Language' ) )
    {
        $LanguageCode = $Module->actionParameter( 'Language' );
    }

    if ( $Module->hasActionParameter( 'PlacementID' ) )
    {
        $placementID = $Module->actionParameter( 'PlacementID' );
    }
}

$assignment = null;
if ( is_numeric( $placementID ) )
    $assignment = eZNodeAssignment::fetchByID( $placementID );
if ( $assignment !== null )
{
    $node =& $assignment->getParentNode();
    if ( $node !== null )
    {
        $nodeObject =& $node->attribute( "object" );
        $sectionID = $nodeObject->attribute( "section_id" );
    }
}
else
{
    $assignment = false;
}

$versionAttributes = $versionObject->contentObjectAttributes( $LanguageCode );
if ( $versionAttributes === null or
     count( $versionAttributes ) == 0 )
{
    $versionAttributes = $versionObject->contentObjectAttributes();
    $LanguageCode = eZContentObject::defaultLanguage();
}

$ini =& eZINI::instance();

$relatedObjectArray =& $contentObject->relatedContentObjectArray( $EditVersion );

$classID = $contentObject->attribute( 'contentclass_id' );

$class = eZContentClass::fetch( $classID );

$classes = eZContentClass::fetchList( $version = 0, $asObject = true, $user_id = false,
                                       array( 'name' => 'name' ), $fields = null );

if ( $assignment )
{
    $parentNodeObject =& $assignment->attribute( 'parent_node_obj' );
}

$navigationPartIdentifier = false;
if ( $sectionID !== false )
{
    $designKeys[] = array( 'section', $sectionID ); // Section ID
    include_once( 'kernel/classes/ezsection.php' );
    eZSection::setGlobalID( $sectionID );

    $section = eZSection::fetch( $sectionID );
    if ( $section )
        $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
}
$designKeys[] = array( 'navigation_part_identifier', $navigationPartIdentifier );

$contentObject->setAttribute( 'current_version', $EditVersion );

// Set variables to be compatible with normal design templates
$contentObject->CurrentLanguage = $LanguageCode;
$contentObject->setAttribute( 'current_version', $EditVersion );

$class = eZContentClass::fetch( $contentObject->attribute( 'contentclass_id' ) );
$objectName = $class->contentObjectName( $contentObject );
$contentObject->setCachedName( $objectName );
$contentObject->ContentObjectAttributeArray;
if ( $assignment )
    $assignment->setName( $objectName );


$path = array();
$pathString = '';
$pathIdentificationString = '';
$requestedURIString = '';
$depth = 2;
if ( isset( $parentNodeObject ) && is_object( $parentNodeObject ) )
{
    $pathString = $parentNodeObject->attribute( 'path_string' ) . $virtualNodeID . '/';
    $pathIdentificationString = $parentNodeObject->attribute( 'path_identification_string' ); //TODO add current node ident string.
    $depth = $parentNodeObject->attribute( 'depth' ) + 1;
    $requestedURIString = $parentNodeObject->attribute( 'url_alias' );
}

if ( isset( $node ) && is_object( $node ) )
{
    $requestedURIString = $node->attribute( 'url_alias' );
}

$node = new eZContentObjectTreeNode();
$node->setAttribute( 'contentobject_version', $EditVersion );
$node->setAttribute( 'path_identification_string', $pathIdentificationString );
$node->setAttribute( 'contentobject_id', $ObjectID );
$node->setAttribute( 'parent_node_id', $parentNodeID );
$node->setAttribute( 'main_node_id', $virtualNodeID );
$node->setAttribute( 'path_string', $pathString );
$node->setAttribute( 'depth', $depth );
$node->setAttribute( 'node_id', $virtualNodeID );
$node->setName( $objectName );

$node->setContentObject( $contentObject );

if ( $Params['SiteAccess'] )
{
    $siteAccess = $Params['SiteAccess'];
}
else
{
    include_once( 'kernel/content/versionviewframe.php' );
    return;
}

$contentINI =& eZINI::instance( 'content.ini' );
if ( !$siteAccess )
{
    if ( $contentINI->hasVariable( 'VersionView', 'DefaultPreviewDesign' ) )
    {
        $siteAccess = $contentINI->variable( 'VersionView', 'DefaultPreviewDesign' );
    }
    else
    {
        $siteAccess = eZTemplateDesignResource::designSetting( 'site' );
    }
}

$GLOBALS['eZCurrentAccess']['name'] = $siteAccess;
changeAccess( array( 'name' => $siteAccess ) );

if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
{
    eZSys::clearAccessPath();
    eZSys::addAccessPath( $siteAccess );
}

// Load the siteaccess extensions
eZExtension::activateExtensions( 'access' );

// Change content object default language
$GLOBALS['eZContentObjectDefaultLanguage'] = $LanguageCode;
eZContentObject::clearCache();

$Module->setTitle( 'View ' . $class->attribute( 'name' ) . ' - ' . $contentObject->attribute( 'name' ) );

$res =& eZTemplateDesignResource::instance();
$res->setDesignSetting( $ini->variable( 'DesignSettings', 'SiteDesign' ), 'site' );
$res->setOverrideAccess( $siteAccess );

$designKeys = array( array( 'object', $contentObject->attribute( 'id' ) ), // Object ID
                     array( 'node', $virtualNodeID ), // Node id
                     array( 'class', $class->attribute( 'id' ) ), // Class ID
                     array( 'class_identifier', $class->attribute( 'identifier' ) ), // Class identifier
                     array( 'viewmode', 'full' ) );  // View mode

if ( $assignment )
{
    $designKeys[] = array( 'parent_node', $assignment->attribute( 'parent_node' ) );
    if ( get_class( $parentNodeObject ) == 'ezcontentobjecttreenode' )
        $designKeys[] = array( 'depth', $parentNodeObject->attribute( 'depth' ) + 1 );
}


$res->setKeys( $designKeys );

include_once( 'kernel/classes/eznodeviewfunctions.php' );

$Result =& eZNodeviewfunctions::generateNodeView( $tpl, $node, $contentObject, $LanguageCode, 'full', 0,
                                                 false, false, false );

$Result['requested_uri_string'] = $requestedURIString;
$Result['ui_context'] = 'view';

?>
