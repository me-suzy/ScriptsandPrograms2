<?php
//
// Definition of  class
//
// Created on: <07-Jul-2003 10:06:19 wy>
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

/*! \file linkcheck.php
*/
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
include_once( "lib/ezutils/classes/ezini.php" );

ini_set( 'user_agent', 'eZ publish Link Validator' );

eZModule::setGlobalPathList( array( "kernel" ) );
if ( !$isQuiet )
    $cli->output( "Checking link ..." );

$cronjobIni =& eZINI::instance( 'cronjob.ini' );
$siteURLs = $cronjobIni->variable( 'linkCheckSettings', 'SiteURL' );
$linkList = eZURL::fetchList( array( 'only_published' => true ) );
foreach ( array_keys( $linkList ) as $key )
{
    $link =& $linkList[$key];
    $linkID = $link->attribute( 'id' );
    $url = $link->attribute( 'url' );
    $isValid = $link->attribute( 'is_valid' );

    $cli->output( "check-" . $cli->stylize( 'emphasize', $url ) . " ", false );
    if ( preg_match("/^(http:)/i", $url ) or
         preg_match("/^(ftp:)/i", $url ) or
         preg_match("/^(https:)/i", $url ) or
         preg_match("/^(file:)/i", $url ) or
         preg_match("/^(mailto:)/i", $url ) )
    {
        if ( preg_match("/^(mailto:)/i", $url))
        {
            if ( eZSys::osType() != 'win32' )
            {
                $url = trim( preg_replace("/^mailto:(.+)/i", "\\1", $url));
                list($userName, $host) = split("@", $url);
                list($host, $junk)= split("\?", $host);
                $dnsCheck = checkdnsrr( $host,"MX" );
                if ( !$dnsCheck )
                {
                    if ( $isValid )
                        eZURL::setIsValid( $linkID, false );
                    $cli->output( $cli->stylize( 'warning', "invalid" ) );
                }
                else
                {
                    if ( !$isValid )
                        eZURL::setIsValid( $linkID, true );
                    $cli->output( $cli->stylize( 'success', "valid" ) );
                }
            }
        }
        else if ( preg_match("/^(http:)/i", $url ) or
                  preg_match("/^(file:)/i", $url ) or
                  preg_match("/^(ftp:)/i", $url ) )
        {
            $fp = @fopen( $url, "r");
            if ( !$fp )
            {
                if ( $isValid )
                    eZURL::setIsValid( $linkID, false );
                $cli->output( $cli->stylize( 'warning', "invalid" ) );
            }
            else
            {
                fclose($fp);
                if ( !$isValid )
                    eZURL::setIsValid( $linkID, true );
                $cli->output( $cli->stylize( 'success', "valid" ) );
            }
        }
        else
        {
            $cli->output( "Couldn't check https protocol" );
        }
    }
    else
    {
        include_once( 'kernel/classes/ezurlalias.php' );
        $translateResult = eZURLAlias::translate( $url );
        if ( !$translateResult )
            $translateResult =& eZURLAlias::translateByWildcard( $url );

        if ( !$translateResult )
        {
              $isInternal = false;
              // Check if it is a valid internal link.
              foreach ( $siteURLs as $siteURL )
              {
                  $siteURL = preg_replace("/\/$/e", "", $siteURL );
                  $fp = @fopen( $siteURL . "/". $url, "r" );
                  if ( !$fp )
                  {
                      // do nothing
                  }
                  else
                  {
                      $isInternal = true;
                      fclose($fp);
                  }
              }
              $translateResult = $isInternal;
        }
        if ( $translateResult )
        {
            if ( !$isValid )
                eZURL::setIsValid( $linkID, true );
            $cli->output( $cli->stylize( 'success', "valid" ) );
        }
        else
        {
            if ( $isValid )
                eZURL::setIsValid( $linkID, false );
            $cli->output( $cli->stylize( 'warning', "invalid" ) );
        }
    }
    eZURL::setLastChecked( $linkID );
}

if ( !$isQuiet )
    $cli->output( "All links have been checked!" );

?>
