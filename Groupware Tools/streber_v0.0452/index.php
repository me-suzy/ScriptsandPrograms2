<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
* Welcome to the source-code. This is a good point to start reading.
*
* This is index.php - the master-control-page. There are NO other php-pages, except from
* install.php (which should have been delete in normal process).
*
* index.php does...
*
* - initialize the profiler
* - include config and customize
* - include core-components
* - authenticate the user
* - render a page (which means calling a function defined in a file at pages/*.inc)
*
* If you want to read more source-code try...
*
* - pages/_pagehandles.inc  - a list of definiation of all posibible pages, it's required rights, etc.
* - pages/home.inc          - example, how a normal page looks like
* - pages/effort.inc        - example, how a form-workflow looks like
* - lists/list_efforts.inc  - example for listing objects
* - db/class_effort.inc     - exampel for back-end definition of object-types
* - render/page.inc         - rending of html-code
*
*/


error_reporting (E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_STRICT);

### start timing ###
global $TIME_START;
$TIME_START=microtime(1);
$DB_ITEMS_LOADED=0;

### include misc functions ###
require_once("std/trace.inc");



/**
* include configuration
**/
{
    require_once("conf/conf.inc");
    
    ### if no db_settings start installaion ###
    if(!file_exists(confGet('DIR_SETTINGS').confGet('FILE_DB_SETTINGS'))) {
        header("location:install/install.php");
        exit;
    }
    else {
    	require_once(confGet('DIR_SETTINGS').confGet('FILE_DB_SETTINGS'));
    }
    
    ### include user-settings ##
    require_once('customize.inc');
}




/**
* run profiler and output measures in footer?
*/
if(confGet('USE_PROFILER')) {
    require_once("std/profiler.inc");
}
else {
    ###  define empty functions ###
    function measure_start($id){};
    function measure_stop($id){};
    function render_measures(){return '';};
}


measure_start('time_complete'); # measure complete time (stops before profiling)
measure_start('core_includes'); # measure time for including core-components


/**
* include the core-class (php5)
*/
require_once "std/errorhandling.inc";
require_once "db/db.inc";
require_once "std/class_auth.inc";
require_once "db/db_item.inc";
require_once "render/render_page.inc";
require_once "std/class_pagehandler.inc";
require_once "pages/_handles.inc";


/**
* filter get and post-vars
*
* We don't not distinguish security between post-,get- and cookie-vars
* because any of them can be easily forged easily. All security-checks
* or done later in db- and field-classes.
*
* passed parames should always used like;
*
*  $f_person_name= get('person_name');
*
*/
global $vars;
$vars= array();
filter_vars($_GET, $vars);
filter_vars($_POST, $vars);
filter_vars($_COOKIE, $vars);

measure_stop('core_includes');

measure_start('init2');



/**
* cache some db-elements
*
* those assoc. arrays hold references to objects from database
*  like       $id => object
*
* @@@ add to db/class_project db/class_task
*/
global $cache_projects;
$cache_projects=array();

global $cache_tasks;
$cache_tasks=array();



### if index.php was called without target, check environment ###
if(!$go=get('go')) {
    
    require_once('std/check_version.inc');
    validateEnvironment();
}


### user NOT logged in ###
if(!$auth->getUserByCookie()) {

    ### submitting login ###
    if($go == 'loginFormSubmit') {
        $PH->show('loginFormSubmit');
    }

    ### valid for anonymous ###
    else if( isset($PH->hash[$go]) && $PH->hash[$go]->valid_for_anonymous) {
        $PH->show($go);
    }
    
    ### all other request lead to login-form ###
    else{


        ### check if we have a proper environment and if db is online ###

        ### check php-version and database (we don't want to show exceptions and php-errors) ###

        ### warn if install-dir present ###
		if(file_exists('install')) {
			$PH->message="<b>Install-directory still present.</b> This is a massive security issue (<a href='".confGet('STREBER_WIKI_URL')."installation'>read more</a>)"
        		.'<ul><li><a href="install/remove_install_dir.php">remove install directory now.</a></ul>';

		}	

        ### render login-form ###
        $PH->show('loginForm');

        ### stop here ##ä
        exit;
    }
}

### user logged in by Cookie ###
else {

    $go=get('go');

    ### if no target-page show home ###
    if(!$go) {

           $PH->show('home');
    }

    ### error-page if invalid target-page####
    else if(!isset($PH->hash[$go])) {
        $PH->show('error');
    }

    ### render target-page ###
    else {
        $PH->show($go);
    }
}
?>
