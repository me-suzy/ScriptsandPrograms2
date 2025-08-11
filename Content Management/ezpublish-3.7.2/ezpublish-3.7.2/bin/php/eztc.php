#!/usr/bin/env php
<?php
//
// Created on: <02-Mar-2004 20:10:18 amos>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Template Compiler\n" .
                                                         "\n" .
                                                         "./bin/php/eztc.php -snews --www-dir='/mypath' --index-file='/index.php' --access-path='news'" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[compile-directory:][www-dir:][index-file:][access-path:][force][full-url][no-full-url]",
                                "",
                                array( 'force' => "Force compilation of template whether it has changed or not",
                                       'compile-directory' => "Where to place compiled files,\ndefault is template/compiled in current cache directory",
                                       'full-url' => "Makes sure generated urls have http:// in them (i.e. global), used mainly by sites that include the eZ publish HTML (e.g payment gateways)",
                                       'no-full-url' => "Makes sure generated urls are relative to the site. (default)",
                                       'www-dir' => "The part before the index.php in your URL, you should supply this if you are running in non-virtualhost mode",
                                       'index-file' => "The name of your index.php if you are running in non-virtualhost mode",
                                       'access-path' => "Extra access path" ) );
$sys =& eZSys::instance();

$forceCompile = false;
$useFullURL = false;

if ( $options['www-dir'] )
{
    $sys->WWWDir = $options['www-dir'];
}
if ( $options['index-file'] )
{
    $sys->IndexFile = $options['index-file'];
}
if ( $options['access-path'] )
{
    $sys->AccessPath = array( $options['access-path'] );
}
if ( $options['force'] )
{
    $forceCompile = true;
}
if ( $options['full-url'] )
{
    $useFullURL = true;
}
if ( $options['no-full-url'] )
{
    $useFullURL = false;
}

$script->initialize();

include_once( 'lib/ezutils/classes/ezhttptool.php' );
$http =& eZHTTPTool::instance();
$http->UseFullUrl = $useFullURL;


if ( count( $options['arguments'] ) > 0 )
{
    $ini =& eZINI::instance();

    include_once( 'kernel/common/template.php' );
    include_once( 'lib/eztemplate/classes/eztemplatecompiler.php' );
    $tpl =& templateInit();

    $fileList = $options['arguments'];

    $script->setIterationData( '.', '~' );
    $script->setShowVerboseOutput( true );
    if ( $forceCompile )
        eZTemplateCompiler::setSettings( array( 'generate' => true ) );

    $files = array();
    foreach ( $fileList as $file )
    {
        $filename = basename( $file );
        if ( preg_match( "!^.+~$|^/?#.+#$|^\..+$!", $filename ) )
            continue;
        $files[] = $file;
    }

    $script->resetIteration( count( $files ) );
    foreach ( $files as $file )
    {
        if ( is_dir( $file ) )
        {
            $script->iterate( $cli, true, "Skipping directory: " . $cli->stylize( 'dir', $file ) );
        }
        else
        {
            $status = $tpl->compileTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Compiled template file: " . $cli->stylize( 'file', $file );
            else
                $text = "Compilation failed: " . $cli->stylize( 'file', $file );
            $script->iterate( $cli, $status, $text );
        }
    }
}
else
{
    $ini =& eZINI::instance();
    $standardDesign = $ini->variable( "DesignSettings", "StandardDesign" );
    $siteDesign = $ini->variable( "DesignSettings", "SiteDesign" );
    $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

    $designList = array_merge( array( $standardDesign ), $additionalSiteDesignList, array( $siteDesign ) );

    include_once( 'kernel/common/template.php' );
    include_once( 'lib/eztemplate/classes/eztemplatecompiler.php' );
    $tpl =& templateInit();

    $script->setIterationData( '.', '~' );
    if ( $forceCompile )
        eZTemplateCompiler::setSettings( array( 'generate' => true ) );

    foreach ( $designList as $design )
    {
        $cli->output( "Compiling in design " . $cli->stylize( 'emphasize', $design ) );
        $baseDir = 'design/' . $design;
        $files = eZDir::recursiveFindRelative( $baseDir, 'templates', "\.tpl" );
        $files = array_merge( $files, eZDir::recursiveFindRelative( $baseDir, 'override/templates', "\.tpl" ) );
        $script->resetIteration( count( $files ) );
        foreach ( $files as $fileRelative )
        {
            $file = $baseDir . '/' . $fileRelative;
            $status = $tpl->compileTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Compiled template file: " . $cli->stylize( 'file', $file );
            else
                $text = "Compilation failed: " . $cli->stylize( 'file', $file );
            $script->iterate( $cli, $status, $text );
        }
    }
}

$script->shutdown();

?>
