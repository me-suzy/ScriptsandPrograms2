<?php
//
// This is the index_webdav.php file. Manages WebDAV sessions.
//
// Created on: <15-Aug-2003 15:15:15 bh>
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
ignore_user_abort( true );
ob_start();

error_reporting ( E_ALL );

// Turn off session stuff, isn't needed for WebDAV operations.
$GLOBALS['eZSiteBasics']['session-required'] = false;

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezsys.php" );
include_once( "lib/ezutils/classes/ezini.php" );

/*! Reads settings from site.ini and passes them to eZDebug.
 */
function eZUpdateDebugSettings()
{
    $ini =& eZINI::instance();
    $debugSettings = array();
    $debugSettings['debug-enabled'] = $ini->variable( 'DebugSettings', 'DebugOutput' ) == 'enabled';
    $debugSettings['debug-by-ip']   = $ini->variable( 'DebugSettings', 'DebugByIP' )   == 'enabled';
    $debugSettings['debug-ip-list'] = $ini->variable( 'DebugSettings', 'DebugIPList' );
    eZDebug::updateSettings( $debugSettings );
}

/*!
 Reads settings from i18n.ini and passes them to eZTextCodec.
*/
function eZUpdateTextCodecSettings()
{
    $ini =& eZINI::instance( 'i18n.ini' );

    list( $i18nSettings['internal-charset'], $i18nSettings['http-charset'], $i18nSettings['mbstring-extension'] ) =
        $ini->variableMulti( 'CharacterSettings', array( 'Charset', 'HTTPCharset', 'MBStringExtension' ), array( false, false, 'enabled' ) );

    include_once( 'lib/ezi18n/classes/eztextcodec.php' );
    eZTextCodec::updateSettings( $i18nSettings );
}

// Initialize text codec settings
eZUpdateTextCodecSettings();

// Check for extension
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end

// Make sure site.ini and template.ini reloads its cache incase
// extensions override it
$ini =& eZINI::instance( 'site.ini' );
$ini->loadCache();
$tplINI =& eZINI::instance( 'template.ini' );
$tplINI->loadCache();

// Grab the main WebDAV setting (enable/disable) from the WebDAV ini file.
$webDavIni =& eZINI::instance( 'webdav.ini' );
$enable = $webDavIni->variable( 'GeneralSettings', 'EnableWebDAV' );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db =& eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
}

function eZFatalError()
{
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    if ( !class_exists( 'eZWebDAVServer' ) )
    {
        include_once( "lib/ezwebdav/classes/ezwebdavserver.php" );
    }
    eZWebDAVServer::appendLogEntry( "****************************************" );
    eZWebDAVServer::appendLogEntry( "Fatal error: eZ publish did not finish its request" );
    eZWebDAVServer::appendLogEntry( "The execution of eZ publish was abruptly ended, the debug output is present below." );
    eZWebDAVServer::appendLogEntry( "****************************************" );
//     $templateResult = null;
//            eZDisplayResult( $templateResult, eZDisplayDebug() );
}

// Check and proceed only if WebDAV functionality is enabled:
if ( $enable === 'true' )
{
    include_once( 'lib/ezutils/classes/ezexecution.php' );
    eZExecution::addCleanupHandler( 'eZDBCleanup' );
    eZExecution::addFatalErrorHandler( 'eZFatalError' );
    eZDebug::setHandleType( EZ_HANDLE_TO_PHP );

    if ( !isset( $_SERVER['REQUEST_URI'] ) or
         !isset( $_SERVER['REQUEST_METHOD'] ) )
    {
        // We stop the script if these are missing
        // e.g. if run from the shell
        eZExecution::cleanExit();
    }
    include_once( "lib/ezutils/classes/ezmodule.php" );
    include_once( 'lib/ezutils/classes/ezexecution.php' );
    include_once( "lib/ezutils/classes/ezsession.php" );
    include_once( "access.php" );
    include_once( "kernel/common/i18n.php" );
    include_once( "kernel/classes/webdav/ezwebdavcontentserver.php" );

    eZModule::setGlobalPathList( array( "kernel" ) );
    eZWebDAVServer::appendLogEntry( "========================================" );
    eZWebDAVServer::appendLogEntry( "Requested URI is: " . $_SERVER['REQUEST_URI'], 'webdav.php' );

    // Initialize/set the index file.
    eZSys::init( 'webdav.php' );

    // The top/root folder is publicly available (without auth):
    if ( $_SERVER['REQUEST_URI'] == ''  or
         $_SERVER['REQUEST_URI'] == '/' or
         $_SERVER['REQUEST_URI'] == '/webdav.php/' or
         $_SERVER['REQUEST_URI'] == '/webdav.php' )
    {
        $testServer = new eZWebDAVContentServer();
        $testServer->processClientRequest();
    }
    // Else: need to login with username/password:
    else
    {
        // Create & initialize a new instance of the content server.
        $server = new eZWebDAVContentServer();

        // Get the name of the site that is being browsed.
        $currentSite = $server->currentSiteFromPath( $_SERVER['REQUEST_URI'] );

        // Proceed only if the current site is valid:
        if ( $currentSite )
        {
            $server->setCurrentSite( $currentSite );

            $loginUsername = "";
            // Get the username and the password.
            if ( isset( $_SERVER['PHP_AUTH_USER'] ) )
                $loginUsername = $_SERVER['PHP_AUTH_USER'];
            if ( isset( $_SERVER['PHP_AUTH_PW'] ) )
                $loginPassword = $_SERVER['PHP_AUTH_PW'];

            // Strip away "domainname\" from a possible "domainname\password" string.
            if ( preg_match( "#(.*)\\\\(.*)$#", $loginUsername, $matches ) )
            {
                $loginUsername = $matches[2];
            }

            $user = false;
            if ( isset( $loginUsername ) && isset( $loginPassword ) )
            {
                include_once( 'kernel/classes/datatypes/ezuser/ezuserloginhandler.php' );

                if ( $ini->hasVariable( 'UserSettings', 'LoginHandler' ) )
                {
                    $loginHandlers = $ini->variable( 'UserSettings', 'LoginHandler' );
                }
                else
                {
                    $loginHandlers = array( 'standard' );
                }

                foreach ( array_keys ( $loginHandlers ) as $key )
                {
                    $loginHandler = $loginHandlers[$key];
                    $userClass =& eZUserLoginHandler::instance( $loginHandler );
                    $user = $userClass->loginUser( $loginUsername, $loginPassword );
                    if ( get_class( $user ) == 'ezuser' )
                        break;
                }
            }

            // Check if username & password contain someting, attempt to login.
            if ( get_class( $user ) != 'ezuser' )
            {
                header( 'HTTP/1.0 401 Unauthorized' );
                header( 'WWW-Authenticate: Basic realm="' . WEBDAV_AUTH_REALM . '"' );

               // Read XML body and discard it
               file_get_contents( "php://input" );
            }
            // Else: non-empty & valid values were supplied: login successful!
            else
            {
                $userName = $user->attribute( 'login' );
                eZWebDAVServer::appendLogEntry( "Logged in: '$userName'", 'webdav.php' );

                // Process the request.
                $server->processClientRequest();
            }
        }
        // Else: site-name is invalid (was not among available sites).
        else
        {
            header( "HTTP/1.1 404 Not Found" );
        }
    }

    eZExecution::cleanExit();
}
// Else: WebDAV functionality is disabled, do nothing...
else
{
    print ( WEBDAV_DISABLED );
}

?>
