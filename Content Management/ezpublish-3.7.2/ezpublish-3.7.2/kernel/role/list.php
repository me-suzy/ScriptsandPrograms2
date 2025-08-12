<?php
//
// Created on: <15-Aug-2002 14:37:29 bf>
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
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentbrowse.php' );
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezrole.php' );
include_once( 'kernel/classes/ezpreferences.php' );

$http =& eZHTTPTool::instance();


$Module =& $Params['Module'];

$offset = $Params['Offset'];

if( eZPreferences::value( 'admin_role_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_role_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
	case '3': { $limit = 50; } break;
	default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
   if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        $db =& eZDB::instance();
        $db->begin();
        foreach ( $deleteIDArray as $deleteID )
        {
            eZRole::remove( $deleteID );
        }
        // Clear role caches.
        eZRole::expireCache();

        // Clear all content cache.
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        $db->commit();
    }
}
// Redirect to content node browse in the user tree
// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID );
    }
    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    include_once( 'kernel/classes/ezcontentcachemanager.php' );
    eZContentCacheManager::clearAllContentCache();
}

if ( $http->hasPostVariable( 'NewButton' )  )
{
    $role = eZRole::createNew( );
    return $Module->redirectToView( 'edit', array( $role->attribute( 'id' ) ) );
}

$viewParameters = array( 'offset' => $offset );
$tpl =& templateInit();

$roles = eZRole::fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = true );
$roleCount = eZRole::roleCount();
$tempRoles = eZRole::fetchList( $temporaryVersions = true );
$tpl->setVariable( 'roles', $roles );
$tpl->setVariable( 'role_count', $roleCount );
$tpl->setVariable( 'temp_roles', $tempRoles );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'limit', $limit );


$Result = array();
$Result['content'] =& $tpl->fetch( 'design:role/list.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/role', 'Role list' ) ) );
?>
