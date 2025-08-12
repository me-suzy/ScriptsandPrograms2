<?php
//
// Created on: <01-Aug-2002 10:40:10 bf>
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

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezorder.php' );
include_once( 'kernel/classes/ezorderstatus.php' );
include_once( 'kernel/classes/ezpreferences.php' );

$module =& $Params['Module'];

$tpl =& templateInit();

$offset = $Params['Offset'];
$limit = 15;


if( eZPreferences::value( 'admin_orderlist_sortfield' ) )
{
    $sortField = eZPreferences::value( 'admin_orderlist_sortfield' );
}

if ( !isset( $sortField ) || ( ( $sortField != 'created' ) && ( $sortField!= 'user_name' ) ) )
{
    $sortField = 'created';
}

if( eZPreferences::value( 'admin_orderlist_sortorder' ) )
{
    $sortOrder = eZPreferences::value( 'admin_orderlist_sortorder' );
}

if ( !isset( $sortOrder ) || ( ( $sortOrder != 'asc' ) && ( $sortOrder!= 'desc' ) ) )
{
    $sortOrder = 'asc';
}

$http =& eZHttpTool::instance();

if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteOrderIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removeorder' ) . '/' );
        }
    }
}

if ( $http->hasPostVariable( 'SaveOrderStatusButton' ) )
{
    if ( $http->hasPostVariable( 'StatusList' ) )
    {
        foreach ( $http->postVariable( 'StatusList' ) as $orderID => $statusID )
        {
            $order = eZOrder::fetch( $orderID );
            $access = $order->canModifyStatus( $statusID );
            if ( $access and $order->attribute( 'status_id' ) != $statusID )
            {
                $order->modifyStatus( $statusID );
            }
        }
    }
}

$orderArray =& eZOrder::active( true, $offset, $limit, $sortField, $sortOrder );
$orderCount = eZOrder::activeCount( true, $offset );

$tpl->setVariable( 'order_list', $orderArray );
$tpl->setVariable( 'order_list_count', $orderCount );
$tpl->setVariable( 'limit', $limit );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'sort_field', $sortField );
$tpl->setVariable( 'sort_order', $sortOrder );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Order list' ),
                 'url' => false );

$Result = array();
$Result['path'] =& $path;

$Result['content'] =& $tpl->fetch( 'design:shop/orderlist.tpl' );
?>
