<?php
//
// phpOpenTracker - The Website Traffic and Visitor Analysis Solution
//
// Copyright 2000 - 2004 Sebastian Bergmann. All rights reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// $Id: phpOpenTracker.php.dist,v 1.3.2.1.2.1 2004/01/24 20:01:22 bergmann Exp $
//
//

$config = &phpOpenTracker_Config::getConfig();

/**
* phpOpenTracker Configuration File
*
* This file contains global configuration settings for phpOpenTracker.
* Values may be safely edited by hand.
* Uncomment only values that you intend to change.
*
* Strings should be enclosed in 'quotes'.
* Integers should be given literally (without quotes).
* Boolean values may be true or false (never quotes).
*/

// Database Type
// Available values: 'mssql', 'mysql', 'oci8', 'pgsql'
// $config['db_type'] = 'mysql';

// The host of your database server
 $config['db_host'] = $hostname;

// The port your database server listens on
// $config['db_port'] = 'default';

// The socket your database server uses
// $config['db_socket'] = 'default';

// Username to connect with to your database server
 $config['db_user'] = $username;

// Password to connect with to your database server
 $config['db_password'] = $password;

// Name of the database to use.
 $config['db_database'] = $database;

// Name of the Additional Data Table
// Default: 'pot_add_data'
// $config['additional_data_table'] = 'pot_add_data';

// Name of the Access Log Table
// Default: 'pot_accesslog'
// $config['accesslog_table'] = 'pot_accesslog';

// Name of the Documents Table
// Default: 'pot_documents'
// $config['documents_table'] = 'pot_documents';

// Name of the Exit Targets Table
// Default: 'pot_exit_targets'
// $config['exit_targets_table'] = 'pot_exit_targets';

// Name of the Hostnames Table
// Default: 'pot_hostnames'
// $config['hostnames_table'] = 'pot_hostnames';

// Name of the Operating Systems Table
// Default: 'pot_operating_systems'
// $config['operating_systems_table'] = 'pot_operating_systems';

// Name of the Referers Table
// Default: 'pot_referers'
// $config['referers_table'] = 'pot_referers';

// Name of the User Agents Table
// Default: 'pot_user_agents'
// $config['user_agents_table'] = 'pot_user_agents';

// Name of the Visitors Table
// Default: 'pot_visitors'
// $config['visitors_table'] = 'pot_visitors';

// 
// $config['merge_tables_threshold'] = 6;

// 
// $config['delay_key_write'] = false;

// Name of the environment variable to be used to 
// determine the current document.
// For instance, 'PATH_INFO' or 'REQUEST_URI' are possible here.
// $config['document_env_var'] = 'REQUEST_URI';

// When enabled, phpOpenTracker will strip away all HTTP GET
// parameters from the referer's URL before it gets stored
// in the database.
// $config['clean_referer_string'] = false;

// When enabled, phpOpenTracker will strip away all HTTP GET
// parameters from the URL, before it gets stored in the
// database.
// A Session ID will be stripped from the URL in either case.
// $config['clean_query_string'] = false;

// While enabling clean_query_string to will clean the
// document's URL of any HTTP GET parameters, you can define
// with the get_parameter_filter array a list of HTTP GET
// parameters that you would like to be stripped from the URL.
// $config['get_parameter_filter'] = '';

// Resolving of the hostname can be turned off.
// $config['resolve_hostname'] = true;

// Grouping of hostnames can be turned off.
// $config['group_hostnames'] = true;

// Grouping and parsing of user agents can be turned off.
// $config['group_user_agents'] = true;

// Detect and log returning visitors.
 $config['track_returning_visitors'] = true;

// Name of the cookie to use for returning visitors detection.
// $config['returning_visitors_cookie'] = 'pot_visitor_id';

// The 'returning_visitors_cookie' cookie expires after
// 'returning_visitors_cookie_lifetime' days.
// $config['returning_visitors_cookie_lifetime'] = 365;

// With this directive you can turn on or off the locking of
// certain IPs and/or user agents.
 $config['locking'] = true;

// With this directive you can turn on or off the logging of
// reloaded documents.
// $config['log_reload'] = false;

// The path to your JPGraph installation.
 $config['jpgraph_path'] = $includes_path."/jpgraph.1.16/";

// With this directive you can define the names, separated by commas,
// of plugins for the phpOpenTracker Logging Engine, that should be
// loaded.
 $config['logging_engine_plugins'] = 'search_engines';

// When enabled, the result of a phpOpenTracker API query which is
// limited to a timerange that lies completely in the past will be
// stored in a cache.
// $config['query_cache'] = true;

// The directory where the phpOpenTracker API Query Cache should
// store its files.
// $config['query_cache_dir'] = 'c:/windows/temp';

// The lifetime of a phpOpenTracker API Query Cache entry in seconds.
// $config['query_cache_lifetime'] = 3600;

// 0: Don't output error and warning messages.
// 1: Output error and warning messages. (default)
// 2: Output additional debugging messages.
// $config['debug_level'] = 1;

// When enabled, phpOpenTracker will exit on fatal errors.
// $config['exit_on_fatal_errors'] = true;

// When enabled, phpOpenTracker will log debugging, error and warning
// messages to a logfile.
// $config['log_errors'] = false;

// Mapping of client ids to client names.
// Currently only used by the simple_report example application.
// $config['clients'][1] = $_SERVER['HTTP_HOST'];

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>
