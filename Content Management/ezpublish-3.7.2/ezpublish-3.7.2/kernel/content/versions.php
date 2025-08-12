<?php
//
// Created on: <22-Apr-2002 15:41:30 bf>
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );

include_once( 'kernel/common/template.php' );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezdb/classes/ezdb.php" );

$tpl =& templateInit();

$ObjectID = $Params['ObjectID'];
$EditVersion = $Params['EditVersion'];
$EditLanguage = $Params['EditLanguage'];

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$object =& eZContentObject::fetch( $ObjectID );
$editWarning = false;

$canEdit = false;
$canRemove = false;

if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_read' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $object->attribute( 'can_edit' ) )
    $canEdit = true;

$canRemove = true;

$http =& eZHTTPTool::instance();

if ( $http->hasSessionVariable( 'ExcessVersionHistoryLimit' ) )
{
    $excessLimit = $http->sessionVariable( 'ExcessVersionHistoryLimit' );
    if ( $excessLimit )
        $editWarning = 3;
    $http->removeSessionVariable( 'ExcessVersionHistoryLimit' );
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( !$canEdit )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $db =& eZDB::instance();
        $db->begin();
        
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        foreach ( $deleteIDArray as $deleteID )
        {
            $version = eZContentObjectVersion::fetch( $deleteID );
            if ( $version != null )
                $version->remove();
        }
        $db->commit();
    }
}

$user =& eZUser::currentUser();

if ( $Module->isCurrentAction( 'Edit' )  )
{
    if ( !$canEdit )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

    $versionID = false;

    if ( is_array( $Module->actionParameter( 'VersionKeyArray' ) ) )
    {
        $versionID = array_keys( $Module->actionParameter( 'VersionKeyArray' ) );
        $versionID = $versionID[0];
    }
    else if ( $Module->hasActionParameter( 'VersionID' ) )
        $versionID = $Module->actionParameter( 'VersionID' );
    if ( $Module->hasActionParameter( 'EditLanguage' ) and
         $Module->actionParameter( 'EditLanguage' ) )
        $EditLanguage = $Module->actionParameter( 'EditLanguage' );
    $version =& $object->version( $versionID );
    if ( !$version )
        $versionID = false;

    if ( $versionID !== false and
         $version->attribute( 'status' ) != EZ_VERSION_STATUS_DRAFT )
    {
        $editWarning = 1;
        $EditVersion = $versionID;
    }
    else if ( $versionID !== false and
              $version->attribute( 'creator_id' ) != $user->attribute( 'contentobject_id' ) )
    {
        $editWarning = 2;
        $EditVersion = $versionID;
    }
    else
    {
        return $Module->redirectToView( 'edit', array( $ObjectID, $versionID, $EditLanguage ) );
    }
}

$versions =& $object->versions();

if ( $Module->isCurrentAction( 'CopyVersion' )  )
{
    if ( !$canEdit )
        return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

    $contentINI =& eZINI::instance( 'content.ini' );
    $versionlimit = $contentINI->variable( 'VersionManagement', 'DefaultVersionHistoryLimit' );

    $limitList = $contentINI->variable( 'VersionManagement', 'VersionHistoryClass' );

    $classID = $object->attribute( 'contentclass_id' );
    foreach ( array_keys ( $limitList ) as $key )
    {
        if ( $classID == $key )
            $versionlimit =& $limitList[$key];
    }
    if ( $versionlimit < 2 )
        $versionlimit = 2;

    $versionCount = $object->getVersionCount();
    if ( $versionCount < $versionlimit )
    {
        if ( is_array( $Module->actionParameter( 'VersionKeyArray' ) ) )
        {
            $versionID = array_keys( $Module->actionParameter( 'VersionKeyArray' ) );
            $versionID = $versionID[0];
        }
        else
            $versionID = $Module->actionParameter( 'VersionID' );

        $db =& eZDB::instance();
        $db->begin();
        foreach ( array_keys( $versions ) as $versionKey )
        {
            $version =& $versions[$versionKey];
            if ( $version->attribute( 'version' ) == $versionID )
            {
                $newVersionID = $object->copyRevertTo( $versionID );
                if ( $Module->hasActionParameter( 'EditLanguage' ) and
                     $Module->actionParameter( 'EditLanguage' ) )
                    $EditLanguage = $Module->actionParameter( 'EditLanguage' );

                if ( !$http->hasPostVariable( 'DoNotEditAfterCopy' ) )
                {
                    return $Module->redirectToView( 'edit', array( $ObjectID, $newVersionID, $EditLanguage ) );
                }
            }
        }
        $db->commit();
    }
    else
    {
        // Remove oldest archived version first
        if ( $contentINI->variable( 'VersionManagement', 'DeleteDrafts' ) == 'enabled' )
        {
            $params = array( 'conditions' => array( 'status' => array( array( 0, 3 ) ) ) );
        }
        else
        {
            $params = array( 'conditions'=> array( 'status' => 3 ) );
        }
        $versions =& $object->versions( true, $params );
        if ( count( $versions ) > 0 )
        {
            $modified = $versions[0]->attribute( 'modified' );
            $removeVersion =& $versions[0];
            foreach ( array_keys( $versions ) as $versionKey )
            {
                $version =& $versions[$versionKey];
                $currentModified = $version->attribute( 'modified' );
                if ( $currentModified < $modified )
                {
                    $modified = $currentModified;
                    $removeVersion = $version;
                }
            }

            $db =& eZDB::instance();
            $db->begin();
            $removeVersion->remove();

            if ( is_array( $Module->actionParameter( 'VersionKeyArray' ) ) )
            {
                $versionID = array_keys( $Module->actionParameter( 'VersionKeyArray' ) );
                $versionID = $versionID[0];
            }
            else
                $versionID = $Module->actionParameter( 'VersionID' );

            $versions =& $object->versions();
            foreach ( array_keys( $versions ) as $versionKey )
            {
                $version =& $versions[$versionKey];
                if ( $version->attribute( 'version' ) == $versionID )
                {
                    $newVersionID = $object->copyRevertTo( $versionID );
                    if ( $Module->hasActionParameter( 'EditLanguage' ) and
                         $Module->actionParameter( 'EditLanguage' ) )
                        $EditLanguage = $Module->actionParameter( 'EditLanguage' );

                    if ( !$http->hasPostVariable( 'DoNotEditAfterCopy' ) )
                    {
                        break;
                    }
                }
            }
            $db->commit();

            if ( !$http->hasPostVariable( 'DoNotEditAfterCopy' ) )
            {
                return $Module->redirectToView( 'edit', array( $ObjectID, $newVersionID, $EditLanguage ) );
            }
        }
        else
        {
            $http->setSessionVariable( 'ExcessVersionHistoryLimit', true );
            $currentVersion = $object->attribute( 'current_version' );
            $Module->redirectToView( 'versions', array( $ObjectID, $currentVersion, $editLanguage ) );
            return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
        }
    }
}

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                      array( 'class', $object->attribute( 'contentclass_id' ) ), // Class ID
                      array( 'class_identifier', $object->attribute( 'class_identifier' ) ), // Class identifier
                      array( 'section_id', $object->attribute( 'section_id' ) ) // Section ID
                      ) ); // Section ID, 0 so far

include_once( 'kernel/classes/ezsection.php' );
eZSection::setGlobalID( $object->attribute( 'section_id' ) );

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'edit_language', $EditLanguage );
$tpl->setVariable( 'versions', $versions );
$tpl->setVariable( 'edit_warning', $editWarning );
$tpl->setVariable( 'can_edit', $canEdit );
//$tpl->setVariable( 'can_remove', $canRemove );
$tpl->setVariable( 'user_id', $user->attribute( 'contentobject_id' ) );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/versions.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Versions' ),
                                'url' => false ) );

?>
