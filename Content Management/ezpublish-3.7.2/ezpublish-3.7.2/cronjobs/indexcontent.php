<?php
//
// Created on: <18-Mar-2004 17:12:43 dr>
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

/*! \file indexcontent.php
*/

include_once( 'kernel/classes/ezsearch.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'lib/ezdb/classes/ezdb.php' );

if ( !$isQuiet )
{
    $cli->output( "Starting processing pending search engine modifications" );
}

$contentObjects = array();
$db =& eZDB::instance();

$offset = 0;
$limit = 50;

while( true )
{
    $entries = $db->arrayQuery( "SELECT param FROM ezpending_actions WHERE action = 'index_object'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );
    $inSQL = '';

    if ( is_array( $entries ) && count( $entries ) != 0 )
    {
        $db->begin();
        foreach ( $entries as $entry )
        {
            $objectID = $entry['param'];

            if ( $inSQL != '' )
            {
                $inSQL .= ', ';
            }
            $inSQL .= $objectID;

            $cli->output( "\tIndexing object ID #$objectID" );
            $object = eZContentObject::fetch( $objectID );
            if ( $object )
            {
                eZSearch::removeObject( $object );
                eZSearch::addObject( $object );
            }
        }

        $db->query( "DELETE FROM ezpending_actions WHERE action = 'index_object' AND param IN ($inSQL)" );
        $db->commit();

        $offset += $limit;
    }
    else
    {
        break; // No valid result from ezpending_actions
    }
}

if ( !$isQuiet )
{
    $cli->output( "Done" );
}

?>
