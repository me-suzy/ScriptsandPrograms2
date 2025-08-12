<?php
//
// Created on: <22-Sen-2004 13:19:58 vs>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$Module =& $Params['Module'];
$NodeID =& $Params['NodeID'];

$curNode = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$curNode )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$curNode->attribute( 'can_hide' ) )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( $curNode->attribute( 'is_hidden' ) )
    eZContentObjectTreeNode::unhideSubTree( $curNode );
else
    eZContentObjectTreeNode::hideSubTree( $curNode );


include_once( 'kernel/classes/ezredirectmanager.php' );
$hasRedirect = eZRedirectManager::redirectTo( $Module, false );
if ( !$hasRedirect )
{
    // redirect to the parent node
    if( ( $parentNodeID = $curNode->attribute( 'parent_node_id' ) ) == 1 )
        $redirectNodeID = $NodeID;
    else
        $redirectNodeID = $parentNodeID;
    return $Module->redirectToView( 'view', array( 'full', $redirectNodeID ) );
}

?>
