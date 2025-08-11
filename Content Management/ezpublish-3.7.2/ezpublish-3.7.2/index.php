<?php
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

// if ( file_exists( 'ezp.xt' ) )
// {
//     $fd = fopen( 'ezp.xt', 'w' ); fclose( $fd );
// }
// xdebug_start_trace( 'ezp' );
ignore_user_abort( true );
require 'lib/compat.php';

$memLimit = ini_get( 'memory_limit' );
if ($memLimit != '')
{
    switch ( $memLimit{strlen( $memLimit ) - 1} )
    {
        case 'G':
            $memLimit *= 1024;
        case 'M':
            $memLimit *= 1024;
        case 'K':
            $memLimit *= 1024;
    }
    if ( $memLimit != -1 && $memLimit < 44040192) /* 42*1024*1024 */
    {
        @ini_set( 'memory_limit', '42M' );
    }
}

$scriptStartTime = microtime();
ob_start();

$use_external_css = true;
$show_page_layout = true;
$moduleRunRequired = true;
$policyCheckRequired = true;
$urlTranslatorAllowed = true;
$validityCheckRequired = false;
$userObjectRequired = true;
$sessionRequired = true;
$dbRequired = true;
$noCacheAdviced = false;

$siteDesignOverride = false;

// List of module names which will skip policy checking
$policyCheckOmitList = array();

// List of directories to search for modules
$moduleRepositories = array();

$siteBasics = array();
$siteBasics['external-css'] =& $use_external_css;
$siteBasics['show-page-layout'] =& $show_page_layout;
$siteBasics['module-run-required'] =& $moduleRunRequired;
$siteBasics['policy-check-required'] =& $policyCheckRequired;
$siteBasics['policy-check-omit-list'] =& $policyCheckOmitList;
$siteBasics['url-translator-allowed'] =& $urlTranslatorAllowed;
$siteBasics['validity-check-required'] =& $validityCheckRequired;
$siteBasics['user-object-required'] =& $userObjectRequired;
$siteBasics['session-required'] =& $sessionRequired;
$siteBasics['db-required'] =& $dbRequired;
$siteBasics['no-cache-adviced'] =& $noCacheAdviced;
$siteBasics['site-design-override'] =& $siteDesignOverride;

$siteBasics['module-repositories'] =& $moduleRepositories;

$GLOBALS['eZSiteBasics'] =& $siteBasics;

$GLOBALS['eZRedirection'] = false;

error_reporting ( E_ALL );

// include standard libs
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezdebugsetting.php" );

$debugINI =& eZINI::instance( 'debug.ini' );
eZDebugSetting::setDebugINI( $debugINI );


/*!
 Reads settings from site.ini and passes them to eZDebug.
*/
function eZUpdateDebugSettings()
{
    $ini =& eZINI::instance();

    $settings = array();
    list( $settings['debug-enabled'], $settings['debug-by-ip'], $settings['log-only'], $settings['debug-ip-list'], $logList ) =
        $ini->variableMulti( 'DebugSettings',
                             array( 'DebugOutput', 'DebugByIP', 'DebugLogOnly', 'DebugIPList', 'AlwaysLog' ),
                             array( 'enabled', 'enabled', 'disabled' ) );
    $logMap = array( 'notice' => EZ_LEVEL_NOTICE,
                     'warning' => EZ_LEVEL_WARNING,
                     'error' => EZ_LEVEL_ERROR,
                     'debug' => EZ_LEVEL_DEBUG );
    $settings['always-log'] = array();
    foreach ( $logMap as $name => $level )
    {
        $settings['always-log'][$level] = in_array( $name, $logList );
    }
    eZDebug::updateSettings( $settings );
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

// Initialize debug settings.
eZUpdateDebugSettings();


// Set the different permissions/settings.
$ini =& eZINI::instance();

list( $iniFilePermission, $iniDirPermission ) =
    $ini->variableMulti( 'FileSettings', array( 'StorageFilePermissions', 'StorageDirPermissions' ) );

$iniVarDirectory = eZSys::cacheDirectory() ;

// OPTIMIZATION:
// Sets permission array as global variable, this avoids the eZCodePage include
$GLOBALS['EZCODEPAGEPERMISSIONS'] = array( 'file_permission' => octdec( $iniFilePermission ),
                                           'dir_permission'  => octdec( $iniDirPermission ),
                                           'var_directory'   => $iniVarDirectory );

//
$warningList = array();

/*!
 Appends a new warning item to the warning list.
 \a $parameters must contain a \c error and \c text key.
*/
function eZAppendWarningItem( $parameters = array() )
{
    global $warningList;
    $parameters = array_merge( array( 'error' => false,
                                      'text' => false,
                                      'identifier' => false ),
                               $parameters );
    $error = $parameters['error'];
    $text = $parameters['text'];
    $identifier = $parameters['identifier'];
    $warningList[] = array( 'error' => $error,
                            'text' => $text,
                            'identifier' => $identifier );
}

include_once( 'lib/ezutils/classes/ezexecution.php' );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db =& eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( "<b>Fatal error</b>: eZ publish did not finish its request<br/>" );
    print( "<p>The execution of eZ publish was abruptly ended, the debug output is present below.</p>" );
    $templateResult = null;
    eZDisplayResult( $templateResult );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

eZDebug::setScriptStart( $scriptStartTime );

// Enable this line to get eZINI debug output
// eZINI::setIsDebugEnabled( true );
// Enable this line to turn off ini caching
// eZINI::setIsCacheEnabled( false);

function eZDisplayDebug()
{
    $ini =& eZINI::instance();

    if ( $ini->variable( 'DebugSettings', 'DebugOutput' ) != 'enabled' )
        return null;

    $type = $ini->variable( "DebugSettings", "Debug" );
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    if ( $type == "inline" or $type == "popup" )
    {
        $as_html = true;

        if ( $ini->variable( "DebugSettings", "DebugToolbar" ) == 'enabled' && $as_html == true && !$GLOBALS['eZRedirection'] )
        {
            include_once( 'kernel/common/template.php' );
            $tpl =& templateInit();
            $result = "<tr><td>" . $tpl->fetch( 'design:setup/debug_toolbar.tpl' ) . "</td></tr>";
            eZDebug::appendTopReport( "Debug toolbar", $result );
        }

        include_once( 'kernel/common/eztemplatesstatisticsreporter.php' );
        eZDebug::appendBottomReport( 'Template Usage Statistics', eZTemplatesStatisticsReporter::generateStatistics( $as_html ) );

        return eZDebug::printReport( $type == "popup", $as_html, true );
    }
    return null;
}

/*!
  \private
*/
function eZDisplayResult( $templateResult )
{
    if ( $templateResult !== null )
    {
        $debugMarker = '<!--DEBUG_REPORT-->';
        $pos = strpos( $templateResult, $debugMarker );
        if ( $pos !== false )
        {
            $debugMarkerLength = strlen( $debugMarker );
            echo substr( $templateResult, 0, $pos );
            eZDisplayDebug();
            echo substr( $templateResult, $pos + $debugMarkerLength );
        }
        else
        {
            echo $templateResult, eZDisplayDebug();
        }
    }
    else
    {
        eZDisplayDebug();
    }
}

function fetchModule( &$uri, &$check, &$module, &$module_name, &$function_name, &$params )
{
    $module_name = $uri->element();
    if ( $check !== null and isset( $check["module"] ) )
        $module_name = $check["module"];

    // Try to fetch the module object
    $module = eZModule::exists( $module_name );
    if ( get_class( $module ) != "ezmodule" )
        return false;

    $uri->increase();
    $function_name = "";
    if ( !$module->singleFunction() )
    {
        $function_name = $uri->element();
        $uri->increase();
    }
    // Override it if required
    if ( $check !== null and isset( $check["function"] ) )
        $function_name = $check["function"];

    $params = $uri->elements( false );
    return true;
}

include_once( 'lib/ezi18n/classes/eztextcodec.php' );
$httpCharset = eZTextCodec::httpCharset();
include_once( 'lib/ezlocale/classes/ezlocale.php' );
$ini =& eZINI::instance();
if ( $ini->variable( 'RegionalSettings', 'Debug' ) == 'enabled' )
    eZLocale::setIsDebugEnabled( true );

include_once( "lib/ezutils/classes/ezsys.php" );


eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

$GLOBALS['eZGlobalRequestURI'] = eZSys::serverVariable( 'REQUEST_URI' );

// Initialize basic settings, such as vhless dirs and separators

eZSys::init( 'index.php', $ini->variable( 'SiteAccessSettings', 'ForceVirtualHost' ) == 'true' );

eZSys::initIni( $ini );

eZDebug::addTimingPoint( "Script start" );

include_once( "lib/ezutils/classes/ezuri.php" );

$uri =& eZURI::instance( eZSys::requestURI() );
$GLOBALS['eZRequestedURI'] =& $uri;
include_once( "pre_check.php" );

// Shall we start the eZ setup module?
//if ( $ini->variable( "SiteAccessSettings", "CheckValidity" ) == "true" )
//    include_once( "lib/ezsetup/classes/ezsetup.php" );

include_once( 'kernel/error/errors.php' );

/*
print( "<pre>" );
var_dump( $_SERVER );
print( "</pre>" );
print( "HTTP_HOST=" . eZSys::serverVariable( 'HTTP_HOST' ) . "<br/" );
*/

// include ezsession override implementation
include_once( "lib/ezutils/classes/ezsession.php" );


include( "lib/ezutils/classes/ezweb.php" );

eZWeb::init();

// Check for extension
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions( 'default' );
// Extension check end

include_once( "access.php" );

$access = accessType( $uri,
                      eZSys::hostname(),
                      eZSys::serverPort(),
                      eZSys::indexFile() );
$access = changeAccess( $access );
eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'current siteaccess' );
$GLOBALS['eZCurrentAccess'] =& $access;

// Check for siteaccess extension
eZExtension::activateExtensions( 'access' );
// Siteaccess extension check end

// Make sure template.ini reloads its cache incase
// siteaccess or extensions override it
$tplINI =& eZINI::instance( 'template.ini' );
$tplINI->loadCache();

$check = eZHandlePreChecks( $siteBasics, $uri );

include_once( 'kernel/common/i18n.php' );

if ( $sessionRequired )
{
	$dbRequired = true;
}

$db = false;
if ( $dbRequired )
{
    include_once( 'lib/ezdb/classes/ezdb.php' );
    $db =& eZDB::instance();
    if ( $sessionRequired and
         $db->isConnected() )
    {
        eZSessionStart();
    }

    if ( !$db->isConnected() )
        $warningList[] = array( 'error' => array( 'type' => 'kernel',
                                                  'number' => EZ_ERROR_KERNEL_NO_DB_CONNECTION ),
                                'text' => 'No database connection could be made, the system might not behave properly.' );
}

// Initialize with locale settings
include_once( "lib/ezlocale/classes/ezlocale.php" );
$locale =& eZLocale::instance();
$languageCode = $locale->httpLocaleCode();
$phpLocale = trim( $ini->variable( 'RegionalSettings', 'SystemLocale' ) );
if ( $phpLocale != '' )
{
    setlocale( LC_ALL, explode( ',', $phpLocale ) );
}

// send header information
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Pragma: no-cache' );
header( 'X-Powered-By: eZ publish' );

header( 'Content-Type: text/html; charset=' . $httpCharset );
header( 'Content-language: ' . $languageCode );

include_once( 'kernel/classes/ezsection.php' );
eZSection::initGlobalID();

// Read role settings
$globalPolicyCheckOmitList = $ini->variable( 'RoleSettings', 'PolicyOmitList' );
$policyCheckOmitList = array_merge( $policyCheckOmitList, $globalPolicyCheckOmitList );
$policyCheckViewMap = array();
foreach ( $policyCheckOmitList as $omitItem )
{
    $items = explode( '/', $omitItem );
    if ( count( $items ) > 1 )
    {
        $module = $items[0];
        $view = $items[1];
        if ( !isset( $policyCheckViewMap[$module] ) )
            $policyCheckViewMap[$module] = array();
        $policyCheckViewMap[$module][] = $view;
    }
}

// Initialize module loading
include_once( "lib/ezutils/classes/ezmodule.php" );

$moduleINI =& eZINI::instance( 'module.ini' );
$globalModuleRepositories = $moduleINI->variable( 'ModuleSettings', 'ModuleRepositories' );
$extensionRepositories = $moduleINI->variable( 'ModuleSettings', 'ExtensionRepositories' );
$extensionDirectory = eZExtension::baseDirectory();
$activeExtensions = eZExtension::activeExtensions();
$globalExtensionRepositories = array();
foreach ( $extensionRepositories as $extensionRepository )
{
    $extPath = $extensionDirectory . '/' . $extensionRepository;
    $modulePath = $extPath . '/modules';
    if ( file_exists( $modulePath ) )
    {
        $globalExtensionRepositories[] = $modulePath;
    }
    else if ( !file_exists( $extPath ) )
    {
        eZDebug::writeWarning( "Extension '$extensionRepository' was reported to have modules but the extension itself does not exist.\n" .
                               "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extensions.\n" .
                               "You should probably remove this extension from the list." );
    }
    else if ( !in_array( $extensionRepository, $activeExtensions ) )
    {
        eZDebug::writeWarning( "Extension '$extensionRepository' was reported to have modules but has not yet been activated.\n" .
                               "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extensions\n" .
                               "or make sure it is activated in the setting ExtensionSettings/ActiveExtensions in site.ini." );
    }
    else
    {
        eZDebug::writeWarning( "Extension '$extensionRepository' does not have the subdirectory 'modules' allthough it reported it had modules.\n" .
                               "Looked for directory '" . $modulePath . "'\n" .
                               "Check the setting ModuleSettings/ExtensionRepositories in module.ini for your extension." );
    }
}
$moduleRepositories = array_merge( $moduleRepositories, $globalModuleRepositories, $globalExtensionRepositories );
eZModule::setGlobalPathList( $moduleRepositories );

include_once( 'kernel/classes/eznavigationpart.php' );

// Start the module loop
while ( $moduleRunRequired )
{
    $objectHasMovedError = false;
    $objectHasMovedURI = false;
    $actualRequestedURI = $uri->uriString();

    // Extract user specified parameters
    $userParameters = $uri->userParameters();

    // Generate a URI which also includes the user parameters
    $completeRequestedURI = $uri->originalURIString();

    // Check for URL translation
    if ( $urlTranslatorAllowed and
         $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' and
         ( !$uri->isEmpty() || ( $ini->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) and $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) != '' ) ) )
    {
        include_once( 'kernel/classes/ezurlalias.php' );
        $translateResult = eZURLAlias::translate( $uri );


        if ( !$translateResult )
        {
            $useWildcardTranslation = $ini->variable( 'URLTranslator', 'WildcardTranslation' ) == 'enabled';
            if ( $useWildcardTranslation )
            {
                $translateResult =& eZURLAlias::translateByWildcard( $uri );
            }
        }

        // Check if the URL has moved
        if ( get_class( $translateResult ) == 'ezurlalias' )
        {
            $objectHasMovedURI =& $translateResult->attribute( 'source_url' );
            $objectHasMovedError = true;
        }
        else if ( is_string( $translateResult ) )
        {
            $objectHasMovedURI = $translateResult;
            $objectHasMovedError = true;
        }
    }

    $moduleCheck = accessAllowed( $uri );
    if ( !$moduleCheck['result'] )
    {
        if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
        {
            $defaultPage = $ini->variable( "SiteSettings", "DefaultPage" );
            $uri->setURIString( $defaultPage );
            $moduleCheck['result'] = true;
        }
    }

    include_once( "lib/ezutils/classes/ezhttptool.php" );
    $http =& eZHTTPTool::instance();

    $displayMissingModule = false;
    $oldURI = $uri;

    if ( $uri->isEmpty() )
    {
        $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "IndexPage" ) );
        if ( !fetchModule( $tmp_uri, $check, $module, $module_name, $function_name, $params ) )
            $displayMissingModule = true;
    }
    else if ( !fetchModule( $uri, $check, $module, $module_name, $function_name, $params ) )
    {
        if ( $ini->variable( "SiteSettings", "ErrorHandler" ) == "defaultpage" )
        {
            $tmp_uri = new eZURI( $ini->variable( "SiteSettings", "DefaultPage" ) );
            if ( !fetchModule( $tmp_uri, $check, $module, $module_name, $function_name, $params ) )
                $displayMissingModule = true;
        }
        else
            $displayMissingModule = true;
    }

    if ( !$displayMissingModule and
         $moduleCheck['result'] and
         get_class( $module ) == "ezmodule" )
    {
        // Run the module/function
        eZDebug::addTimingPoint( "Module start '" . $module->attribute( 'name' ) . "'" );

        $moduleAccessAllowed = true;
        $omitPolicyCheck = true;
        $runModuleView = true;
        if ( $policyCheckRequired )
        {
            $omitPolicyCheck = false;
            $moduleName = $module->attribute( 'name' );
            $viewName = $function_name;
            if ( in_array( $moduleName, $policyCheckOmitList ) )
                $omitPolicyCheck = true;
            else if ( isset( $policyCheckViewMap[$moduleName] ) and
                      in_array( $viewName, $policyCheckViewMap[$moduleName] ) )
                $omitPolicyCheck = true;
        }
        if ( !$omitPolicyCheck )
        {
            if( include_once( "kernel/classes/datatypes/ezuser/ezuser.php" ) )
            {
                $currentUser =& eZUser::currentUser();

                $availableViewsInModule = $module->attribute( 'views' );
                $runningFunctions = false;
                if ( isset( $availableViewsInModule[$function_name][ 'functions' ] ) )
                    $runningFunctions = $availableViewsInModule[$function_name][ 'functions' ];
                $siteAccessResult = $currentUser->hasAccessTo( 'user', 'login' );

                $hasAccessToSite = false;
                if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
                {
                    $policyChecked = false;
                    foreach ( array_keys( $siteAccessResult['policies'] ) as $key )
                    {
                        $policy =& $siteAccessResult['policies'][$key];
                        if ( isset( $policy['SiteAccess'] ) )
                        {
                            $policyChecked = true;
                            eZDebugSetting::writeDebug( 'kernel-siteaccess', $policy['SiteAccess'], crc32( $access[ 'name' ] ));
                            if ( in_array( crc32( $access[ 'name' ] ), $policy['SiteAccess'] ) )
                            {
                                $hasAccessToSite = true;
                                break;
                            }
                        }
                        if ( $hasAccessToSite )
                            break;
                    }
                    if ( !$policyChecked )
                        $hasAccessToSite = true;
                }
                else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
                {
                    eZDebugSetting::writeDebug( 'kernel-siteaccess', "access is yes" );
                    $hasAccessToSite = true;
                }
                else if ( $siteAccessResult['accessWord'] == 'no' )
                {
                    $accessList = $siteAccessResult['accessList'];
                }
            }

            if ( $hasAccessToSite )
            {
                $accessResult = $currentUser->hasAccessTo( $module->attribute( 'name' ), $runningFunctions[0] );
                if ( $accessResult['accessWord'] == 'limited' )
                {
                    $moduleName = $module->attribute( 'name' );
                    $functionName = $runningFunctions[0];
                    $params['Limitation'] =& $accessResult['policies'];
                    $GLOBALS['ezpolicylimitation_list'][$moduleName][$functionName] =& $params['Limitation'];
                }
                if ( $accessResult['accessWord'] == 'no' )
                {
                    $accessList = $accessResult['accessList'];
                    $moduleAccessAllowed = false;
                }
            }
            else
            {
                eZDebugSetting::writeDebug( 'kernel-siteaccess', $access, 'not able to get access to siteaccess' );
                $moduleAccessAllowed = false;
                $requireUserLogin = ( $ini->variable( "SiteAccessSettings", "RequireUserLogin" ) == "true" );
                if ( $requireUserLogin )
                {
                    $module = eZModule::exists( 'user' );
                    if ( get_class( $module ) == "ezmodule" )
                    {
                        $moduleResult =& $module->run( 'login', array(),
                                                       array( 'SiteAccessAllowed' => false,
                                                              'SiteAccessName' => $access['name'] ) );
                        $runModuleView = false;
                    }
                }
            }
        }

        $GLOBALS['eZRequestedModule'] =& $module;

        if ( $runModuleView )
        {
            if ( $objectHasMovedError == true )
            {
                $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MOVED, 'kernel', array( 'new_location' => $objectHasMovedURI ) );
            }
            else if ( !$moduleAccessAllowed )
            {
                if ( isset( $accessList ) )
                    $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel', array( 'AccessList' => $accessList ) );
                else
                    $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
            }
            else
            {
                if ( !isset( $userParameters ) )
                {
                    $userParameters = false;
                }

                $moduleResult =& $module->run( $function_name, $params, false, $userParameters );

                if ( $module->exitStatus() == EZ_MODULE_STATUS_FAILED and
                     $moduleResult == null )
                    $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_VIEW_NOT_FOUND, 'kernel', array( 'module' => $module_name,
                                                                                                                   'view' => $function_name ) );
            }
        }
    }
    else if ( $moduleCheck['result'] )
    {
        eZDebug::writeError( "Undefined module: $module_name", "index" );
        $module = new eZModule( "", "", $module_name );
        $GLOBALS['eZRequestedModule'] =& $module;
        $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_NOT_FOUND, 'kernel', array( 'module' => $module_name ) );
    }
    else
    {
        if ( $moduleCheck['view_checked'] )
            eZDebug::writeError( "View '" . $moduleCheck['view'] . "' in module '" . $moduleCheck['module'] . "' is disabled", "index" );
        else
            eZDebug::writeError( "Module '" . $moduleCheck['module'] . "' is disabled", "index" );
        $module = new eZModule( "", "", $moduleCheck['module'] );
        $GLOBALS['eZRequestedModule'] =& $module;
        $moduleResult =& $module->handleError( EZ_ERROR_KERNEL_MODULE_DISABLED, 'kernel', array( 'check' => $moduleCheck ) );
    }
    $moduleRunRequired = false;
    if ( $module->exitStatus() == EZ_MODULE_STATUS_RERUN )
    {
        if ( isset( $moduleResult['rerun_uri'] ) )
        {
            $uri = & eZURI::instance( $moduleResult['rerun_uri'] );
            $moduleRunRequired = true;
        }
        else
            eZDebug::writeError( 'No rerun URI specified, cannot continue', 'index.php' );
    }

    if ( is_array( $moduleResult ) )
    {
        if ( isset( $moduleResult["pagelayout"] ) )
        {
            $show_page_layout =& $moduleResult["pagelayout"];
            $GLOBALS['eZCustomPageLayout'] = $moduleResult["pagelayout"];
        }
        if ( isset( $moduleResult["external_css"] ) )
            $use_external_css =& $moduleResult["external_css"];
    }
}

if ( $module->exitStatus() == EZ_MODULE_STATUS_REDIRECT )
{
    $GLOBALS['eZRedirection'] = true;
    $ini =& eZINI::instance();
    $uri =& eZURI::instance( eZSys::requestURI() );

    list( $redirUri, $debugByIP, $debugIPList ) =
        $ini->variableMulti( "DebugSettings", array( 'DebugRedirection', 'DebugByIP', 'DebugIPList' ) );
    $automatic_redir = true;

    if ( $redirUri == "enabled" )
    {
        $automatic_redir = false;
    }
    else if ( $redirUri != "disabled" )
    {
        $redirUris = $ini->variableArray( "DebugSettings", "DebugRedirection" );
        $uri->toBeginning();
        foreach ( $redirUris as $redirUri )
        {
            $redirUri = new eZURI( $redirUri );
            if ( $redirUri->matchBase( $uri ) )
            {
                $automatic_redir = false;
                break;
            }
        }
    }

    if ( $debugByIP == 'enabled' )
    {
        $ipAddress = eZSys::serverVariable( 'REMOTE_ADDR', true );
        if ( $ipAddress )
        {
            $debugEnabled = false;
            foreach( $debugIPList as $itemToMatch )
            {
                if ( preg_match("/^(([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+))(\/([0-9]+)$|$)/", $itemToMatch, $matches ) )
                {
                    if ( $matches[6] )
                    {
                        if ( eZDebug::isIPInNet( $ipAddress, $matches[1], $matches[7] ) )
                        {
                            $debugEnabled=true;
                            break;
                        }
                    }
                    else
                    {
                        if ( $matches[1] == $ipAddress )
                        {
                            $debugEnabled=true;
                            break;
                        }
                    }
                }
            }
        }
        else
        {
            $debugEnabled = (
                in_array( 'commandline', $debugIPList ) &&
                (php_sapi_name() == 'cli')
            );
        }
    }



    $redirectURI =& eZSys::indexDir();
//     eZDebug::writeDebug( eZSys::indexDir(), 'eZSys::indexDir()' );
//     eZDebug::writeDebug( $module->redirectURI(), '$module->redirectURI()' );

    $moduleRedirectUri = $module->redirectURI();
    $translatedModuleRedirectUri = $moduleRedirectUri;
    if ( $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
    {
        include_once( 'kernel/classes/ezurlalias.php' );
        if ( eZURLAlias::translate( $translatedModuleRedirectUri, true ) )
        {
            $moduleRedirectUri = $translatedModuleRedirectUri;
            if ( strlen( $moduleRedirectUri ) > 0 and
                 $moduleRedirectUri[0] != '/' )
                $moduleRedirectUri = '/' . $moduleRedirectUri;
        }
    }

    if ( preg_match( '#^(\w+:)|^//#', $moduleRedirectUri ) )
    {
        $redirectURI = $moduleRedirectUri;
    }
    else
    {
        $leftSlash = false;
        $rightSlash = false;
        if ( strlen( $redirectURI ) > 0 and
             $redirectURI[strlen( $redirectURI ) - 1] == '/' )
            $leftSlash = true;
        if ( strlen( $moduleRedirectUri ) > 0 and
             $moduleRedirectUri[0] == '/' )
            $rightSlash = true;

        if ( !$leftSlash and !$rightSlash ) // Both are without a slash, so add one
            $moduleRedirectUri = '/' . $moduleRedirectUri;
        else if ( $leftSlash and $rightSlash ) // Both are with a slash, so we remove one
            $moduleRedirectUri = substr( $moduleRedirectUri, 1 );
        $redirectURI .= $moduleRedirectUri;
    }

    include_once( 'kernel/classes/ezstaticcache.php' );
    eZStaticCache::executeActions();

    if ( $automatic_redir )
    {
        eZHTTPTool::redirect( $redirectURI );
    }
    else
    {
        // Make sure any errors or warnings are reported
        if ( $ini->variable( 'DebugSettings', 'DisplayDebugWarnings' ) == 'enabled' )
        {
            if ( isset( $GLOBALS['eZDebugError'] ) and
                 $GLOBALS['eZDebugError'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'error',
                                                              'number' => 1,
                                                              'count' => $GLOBALS['eZDebugErrorCount'] ),
                                            'identifier' => 'ezdebug-first-error',
                                            'text' => ezi18n( 'index.php', 'Some errors occured, see debug for more information.' ) ) );
            }

            if ( isset( $GLOBALS['eZDebugWarning'] ) and
                 $GLOBALS['eZDebugWarning'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'warning',
                                                              'number' => 1,
                                                              'count' => $GLOBALS['eZDebugWarningCount'] ),
                                            'identifier' => 'ezdebug-first-warning',
                                            'text' => ezi18n( 'index.php', 'Some general warnings occured, see debug for more information.' ) ) );
            }
        }

        include_once( "kernel/common/template.php" );
        $tpl =& templateInit();
        if ( count( $warningList ) == 0 )
            $warningList = false;
        $tpl->setVariable( 'warning_list', $warningList );
        $tpl->setVariable( 'redirect_uri', $redirectURI );
        $templateResult =& $tpl->fetch( 'design:redirect.tpl' );

        eZDebug::addTimingPoint( "End" );

        eZDisplayResult( $templateResult );
    }

    eZExecution::cleanExit();
}

// Store the last URI for access history for login redirection
// Only if database is connected and only if there was no error or no redirects happen
if ( is_object( $db ) and $db->isConnected() and
     $module->exitStatus() == EZ_MODULE_STATUS_OK )
{
    $currentURI = $completeRequestedURI;
    if ( strlen( $currentURI ) > 0 and $currentURI[0] != '/' )
        $currentURI = '/' . $currentURI;

    $lastAccessedURI = "";
    $lastAccessedViewURI = "";

    $http =& eZHTTPTool::instance();

    // Fetched stored session variables
    if ( $http->hasSessionVariable( "LastAccessesURI" ) )
    {
        $lastAccessedViewURI = $http->sessionVariable( "LastAccessesURI" );
    }
    if ( $http->hasSessionVariable( "LastAccessedModifyingURI" ) )
    {
        $lastAccessedURI = $http->sessionVariable( "LastAccessedModifyingURI" );
    }

    // Update last accessed view page
    if ( $currentURI != $lastAccessedViewURI and
         !in_array( $module->uiContextName(), array( 'edit', 'administration', 'browse', 'authentication' ) ) )
    {
        $http->setSessionVariable( "LastAccessesURI", $currentURI );
    }

    // Update last accessed non-view page
    if ( $currentURI != $lastAccessedURI )
    {
        $http->setSessionVariable( "LastAccessedModifyingURI", $currentURI );
    }
}


eZDebug::addTimingPoint( "Module end '" . $module->attribute( 'name' ) . "'" );
if ( !is_array( $moduleResult ) )
{
    eZDebug::writeError( 'Module did not return proper result: ' . $module->attribute( 'name' ), 'index.php' );
    $moduleResult = array();
    $moduleResult['content'] = false;
}

if ( !isset( $moduleResult['ui_context'] ) )
{
    $moduleResult['ui_context'] = $module->uiContextName();
}
$moduleResult['ui_component'] = $module->uiComponentName();

$templateResult = null;

eZDebug::setUseExternalCSS( $use_external_css );
if ( $show_page_layout )
{
    include_once( "kernel/common/template.php" );
    $tpl =& templateInit();
    if ( !isset( $moduleResult['path'] ) )
        $moduleResult['path'] = false;
    $moduleResult['uri'] = eZSys::requestURI();

    $tpl->setVariable( "module_result", $moduleResult );

    $meta = $ini->variable( 'SiteSettings', 'MetaDataArray' );

    if ( !isset( $meta['description'] ) )
    {
        $metaDescription = "";
        if ( isset( $moduleResult['path'] ) and
             is_array( $moduleResult['path'] ) )
        {
            foreach ( $moduleResult['path'] as $pathPart )
            {
                if ( isset( $pathPart['text'] ) )
                    $metaDescription .= $pathPart['text'] . " ";
            }
        }
        $meta['description'] = $metaDescription;
    }

    $http_equiv = array( 'Content-Type' => 'text/html; charset=' . $httpCharset,
                         'Content-language' => $languageCode );

    include_once( 'lib/version.php' );
    $site = array(
        "title" => $ini->variable( 'SiteSettings', 'SiteName' ),
        "page_title" => $module->title(),
        "uri" => $oldURI,
        "redirect" => false,
        "design" => $ini->variable( 'DesignSettings', 'SiteDesign' ),
        "http_equiv" => $http_equiv,
        "meta" => $meta,
        "version" => eZPublishSDK::version());
    $tpl->setVariable( "site", $site );

    include_once( 'lib/version.php' );
    $ezinfo = array( 'version' => eZPublishSDK::version( true ),
                     'version_alias' => eZPublishSDK::version( true, true ),
                     'revision' => eZPublishSDK::revision() );

    $tpl->setVariable( "ezinfo", $ezinfo );
    if ( isset( $tpl_vars ) and is_array( $tpl_vars ) )
    {
        foreach( $tpl_vars as $tpl_var_name => $tpl_var_value )
        {
            $tpl->setVariable( $tpl_var_name, $tpl_var_value );
        }
    }

    if ( $show_page_layout )
    {
        if ( $ini->variable( 'DebugSettings', 'DisplayDebugWarnings' ) == 'enabled' )
        {
            // Make sure any errors or warnings are reported
            if ( isset( $GLOBALS['eZDebugError'] ) and
                 $GLOBALS['eZDebugError'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'error',
                                                              'number' => 1 ,
                                                              'count' => $GLOBALS['eZDebugErrorCount'] ),
                                            'identifier' => 'ezdebug-first-error',
                                            'text' => ezi18n( 'index.php', 'Some errors occured, see debug for more information.' ) ) );
            }

            if ( isset( $GLOBALS['eZDebugWarning'] ) and
                 $GLOBALS['eZDebugWarning'] )
            {
                eZAppendWarningItem( array( 'error' => array( 'type' => 'warning',
                                                              'number' => 1,
                                                              'count' => $GLOBALS['eZDebugWarningCount'] ),
                                            'identifier' => 'ezdebug-first-warning',
                                            'text' => ezi18n( 'index.php', 'Some general warnings occured, see debug for more information.' ) ) );
            }
        }

        if ( $userObjectRequired )
        {
            // include user class
            if( include_once( "kernel/classes/datatypes/ezuser/ezuser.php" ) )
                $currentUser =& eZUser::currentUser();

            $tpl->setVariable( "current_user", $currentUser );
            $tpl->setVariable( "anonymous_user_id", $ini->variable( 'UserSettings', 'AnonymousUserID' ) );
        }
        else
        {
            $tpl->setVariable( "current_user", false );
            $tpl->setVariable( "anonymous_user_id", false );
        }

//         include_once( "lib/ezutils/classes/ezexecutionstack.php" );
//         $execStack =& eZExecutionStack::instance();
//         $tpl->setVariable( "execution_entries", $execStack->entries() );

        $tpl->setVariable( "access_type", $access );

        if ( count( $warningList ) == 0 )
            $warningList = false;
        $tpl->setVariable( 'warning_list', $warningList );

        $resource = "design:";
        if ( is_string( $show_page_layout ) )
        {
            if ( strpos( $show_page_layout, ":" ) !== false )
            {
                $resource = "";
            }
        }
        else
        {
            $show_page_layout = "pagelayout.tpl";
        }

        // Set the navigation part
        // Check for navigation part settings
        $navigationPartString = 'ezcontentnavigationpart';
        if ( isset( $moduleResult['navigation_part'] ) )
        {
            $navigationPartString = $moduleResult['navigation_part'];

            // Fetch the navigation part
        }
        $navigationPart = eZNavigationPart::fetchPartByIdentifier( $navigationPartString );

        $tpl->setVariable( 'navigation_part', $navigationPart );
        $tpl->setVariable( 'uri_string', $uri->uriString() );
        if ( isset( $moduleResult['requested_uri_string'] ) )
        {
            $tpl->setVariable( 'requested_uri_string', $moduleResult['requested_uri_string'] );
        }
        else
        {
            $tpl->setVariable( 'requested_uri_string', $actualRequestedURI );
        }

        // Set UI context and component
        $tpl->setVariable( 'ui_context', $moduleResult['ui_context'] );
        $tpl->setVariable( 'ui_component', $moduleResult['ui_component'] );

        $templateResult =& $tpl->fetch( $resource . $show_page_layout );
    }
}
else
{
    $templateResult =& $moduleResult['content'];
}


eZDebug::addTimingPoint( "End" );

ob_end_flush();

$db =& eZDB::instance();
while ( $db->TransactionCounter > 0 )
{
    eZDebug::writeError( "Internal transaction counter mismatch : " . $db->TransactionCounter . ". Should be zero." );
    $db->commit();
}

eZDisplayResult( $templateResult );

eZExecution::cleanup();
eZExecution::setCleanExit();

//xdebug_dump_function_profile( 4 );

?>
