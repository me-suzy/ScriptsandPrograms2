<?php
//
// Created on: <02-Nov-2004 13:23:10 dl>
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

/*! \file function_definition.php
*/


$FunctionList = array();

$FunctionList['version'] = array( 'name' => 'version',
                                  'operation_types' => array( 'read' ),
                                  'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                          'class' => 'eZSetupFunctionCollection',
                                                          'method' => 'fetchFullVersionString' ),
                                  'parameter_type' => 'standard',
                                  'parameters' => array( ) );
$FunctionList['major_version'] = array( 'name' => 'major_version',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                                'class' => 'eZSetupFunctionCollection',
                                                                'method' => 'fetchMajorVersion' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( ) );
$FunctionList['minor_version'] = array( 'name' => 'minor_version',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                                'class' => 'eZSetupFunctionCollection',
                                                                'method' => 'fetchMinorVersion' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( ) );
$FunctionList['release'] = array( 'name' => 'release',
                                  'operation_types' => array( 'read' ),
                                  'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                          'class' => 'eZSetupFunctionCollection',
                                                          'method' => 'fetchRelease' ),
                                  'parameter_type' => 'standard',
                                  'parameters' => array( ) );
$FunctionList['state'] = array( 'name' => 'state',
                                'operation_types' => array( 'read' ),
                                'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                        'class' => 'eZSetupFunctionCollection',
                                                        'method' => 'fetchState' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( ) );
$FunctionList['is_development'] = array( 'name' => 'is_development',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                                 'class' => 'eZSetupFunctionCollection',
                                                                 'method' => 'fetchIsDevelopment' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( ) );
$FunctionList['revision'] = array( 'name' => 'revision',
                                   'operation_types' => array( 'read' ),
                                   'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                           'class' => 'eZSetupFunctionCollection',
                                                           'method' => 'fetchRevision' ),
                                   'parameter_type' => 'standard',
                                   'parameters' => array( ) );
$FunctionList['database_version'] = array( 'name' => 'database_version',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                                   'class' => 'eZSetupFunctionCollection',
                                                                   'method' => 'fetchDatabaseVersion' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'with_release',
                                                                         'type' => 'bool',
                                                                         'required' => false,
                                                                         'default' => true ) ) );
$FunctionList['database_release'] = array( 'name' => 'database_release',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'include_file' => 'kernel/setup/ezsetupfunctioncollection.php',
                                                                   'class' => 'eZSetupFunctionCollection',
                                                                   'method' => 'fetchDatabaseRelease' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
?>
