<?php
//
// Created on: <08-Aug-2002 14:04:07 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezsearchlog.php" );
include_once( "kernel/classes/ezpreferences.php" );

if ( eZPreferences::value( 'admin_search_stats_limit' ) )
{
    switch ( eZPreferences::value( 'admin_search_stats_limit' ) )
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

$offset = $Params['Offset'];
if ( !is_numeric( $offset ) )
{
    $offset = 0;
}

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

if ( $module->isCurrentAction( 'ResetSearchStats' ) )
{
    eZSearchLog::removeStatistics();
}

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );
$tpl =& templateInit();

$db =& eZDB::instance();
$query = "SELECT count(*) as count FROM ezsearch_search_phrase";
$searchListCount = $db->arrayQuery( $query );

$mostFrequentPhraseArray =& eZSearchLog::mostFrequentPhraseArray( $viewParameters );

$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "most_frequent_phrase_array", $mostFrequentPhraseArray );
$tpl->setVariable( "search_list_count", $searchListCount[0]['count'] );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:search/stats.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/search', 'Search stats' ),
                                'url' => false ) );

?>
