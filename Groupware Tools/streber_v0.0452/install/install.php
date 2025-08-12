<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html


/**
*
* installation 
*
*/
error_reporting (E_ALL|E_NOTICED|E_STRICT);

require_once(dirname(__FILE__)."/../std/trace.inc");
require_once(dirname(__FILE__)."/../conf/conf.inc");

$vars= array();

filter_vars($_GET, $vars);
filter_vars($_POST, $vars);
filter_vars($_COOKIE, $vars);


DEFINE('RESULT_GOOD',0);
DEFINE('RESULT_FAILED',1);
DEFINE('RESULT_PROBLEM',2);

$g_form_fields=array(
    'hostname'=>array(
        'id'        =>'hostname',
        'default'   =>'localhost',
        'label'     =>' Hostname (for Database Server)',
        'required' => true,
    ),
    'db_username'=>array(
        'id'        =>'db_username',
        'default'   =>'root',
        'label'     =>'Username (for Database)',
        'required' => true,
    ),
    'db_password'=>array(
        'id'        =>'db_password',
        'default'   =>'',
        'label'     =>'Password (for Database)',
    ),
    'db_name'=>array(
        'id'        =>'db_name',
        'default'   =>'streber',
        'label'     =>'Name of database',
        'required' => true,
    ),
/*    'db_admin_user'=>array(
        'id'        =>'db_admin_user',
        'default'   =>'',
        'label'     =>'Admin Username (to create Database)',
    ),
    'db_admin_password'=>array(
        'id'        =>'db_admin_password',
        'default'   =>'',
        'label'     =>'Admin Password (to create Database)',
        'comment'   =>'not required, if database already exists',
    ),*/
    'db_table_prefix'=>array(
        'id'        =>'db_table_prefix',
        'default'   =>'',
        'label'     =>'SQL Table prefix (e.g. "streb_")',
        'comment'   =>'',
    ),
    'user_admin_name'=>array(
        'id'        =>'user_admin_name',
        'default'   =>'admin',
        'label'     =>'Streber administrator name',
        'comment'   =>'',
        'required' => true,
    ),
    'user_admin_password'=>array(
        'id'        =>'user_admin_password',
        'default'   =>'',
        'label'     =>'Streber administrator password',
        'comment'   =>'',
    ),

);


render_InstallationHTMLOpen();

### try to get form_infos ###
{
    $f_install_step=get('install_step');

    switch($f_install_step) {
            
        case 'form_submit':
            step_form_submit();
            break;
            
        default: 
            if(!step_welcome()) {
		        echo "<h2>Installation failed</h2>";
		        echo "You may find help at ".getStreberWikiLink('installation','the wiki-installation guide');
			}
			break;
    }

}
render_InstallationHTMLClose();


exit();

#========================================================================================================

/**
* installation header
*/
function render_InstallationHTMLOpen() {     
  echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
      <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
      <meta content="Streber, pm, project management, tool, php, php5, oop, tasks, projects, users, teams, online, web-based, free, open source, gpl"
     name="KEYWORDS">
      <meta content="Thomas Mann / pixtur" name="AUTHOR">
      <meta content="3 days" name="REVISIT-AFTER">
      <meta content="en" http-equiv="content-language">
      <title>Streber pm - a web based free open source project management tool with php 5 mysql</title>
      <link href="styles.css" rel="stylesheet" type="text/css">
    </head>
    <body>';

}


/**
* installation footer
*/
function render_InstallationHTMLClose() {
    echo "</html>";
}



/**
* STEP WELCOME TO INSTALLATION
*/
function step_welcome() {

	$flag_errors=false;
    echo "<h1>Welcome to installing streber ".confGet('STREBER_VERSION')."</h1>";
    
    echo "<h2>Checking environment...</h2>";

    ### check php version ###
    render_testStart("PHP-Version...");
    $php_version=phpversion();
    if($php_version > confGet('PHP_VERSION_REQUIRED')) {
        render_testResult(RESULT_GOOD,"is $php_version");
    }
    else {
        render_testResult(RESULT_FAILED,"Insufficient php version $php_version. Streber requires php v".confGet('PHP_VERSION_REQUIRED').". 
         You find additional information on how to get the latest php-version or a service provider
        with php5 at the ".getStreberWikiLink('installation','installation guide'));
		$flag_errors= true;
    }

	### check mysql-installed ###
	render_testStart("MySql installed?");
	if(!function_exists('mysql_pconnect')) {
        render_testResult(RESULT_FAILED,"mysql_pconnect() not defined");
		$flag_errors= true;
	}
	else {
    	render_testResult(RESULT_GOOD);
	}

	### check _settings-directory writeable ###
	render_testStart("check write-permissions for directory '<b>". confGet('DIR_SETTINGS') ."</b>'?");
	if(!is_writeable('../'. confGet('DIR_SETTINGS'))) {
        render_testResult(RESULT_FAILED,"Please grand write-permissions for this directory.");
		$flag_errors= true;
	}
	else {
    	render_testResult(RESULT_GOOD);
	}

	### check _tmp-directory writeable ###
	render_testStart("check write-permissions for directory '<b>". confGet('DIR_TEMP') ."</b>'?");
	if(!is_writeable('../'. confGet('DIR_TEMP'))) {
        render_testResult(RESULT_PROBLEM,"Please grand write-permissions for this directory. Although you can proceed with installation, you will get warnings later.");
	}
	else {
    	render_testResult(RESULT_GOOD);
	}


	if($flag_errors) {
		return false;
	}

	### render the configuration-form ###
    render_form_step1();
	return true;
}


/**
* check form-fields
*/
function step_form_submit() 
{

    ### check params passed ###
    global $g_form_fields;
    
    $errors=false;

    foreach($g_form_fields as $key=>$value) {
		$f= &$g_form_fields[$key];
        $value=get($f['id']);
        if(isset($value)) {
            $f['value']= $value;
        }
        if(isset($f['required']) && $f['required'] && !$value ) {
            $errors=true;
            $f['error']= true;
        }
    }
    
    ### reshow form if errors ###
    if($errors) {
         echo "<h2>Note: some fields are required</h2>";
         render_form_step1();
         return;
    }
    
    ### if no error continue ###
    if(step_02_proceed()) {
        echo "<h2>Installation finished successfully</h2>";
        echo "Please proceed by either...";
        echo "<ul>";
        echo "<li><a href='remove_install_dir.php'>deleting installation-directory</a>";
        echo "<li>".getStreberWikiLink('first steps','read a fast tutorial about first steps');
        echo "<li><a href='../index.php'>login</a>";
        echo "</ul>";
    }
    else {
        echo "<h2>Installation failed</h2>";
        echo "You may find help at ".getStreberWikiLink('installation','the wiki-installation guide');
    }
}

/**
* proceed with installation 
*/
function step_02_proceed() 
{
    global $g_form_fields;


    echo "<h2>Proceeding...</h2>";


    $f_hostname =       $g_form_fields['hostname']['value'];
    $f_db_name =        $g_form_fields['db_name']['value'];
    $f_db_username =    $g_form_fields['db_username']['value'];
    $f_db_password =    $g_form_fields['db_password']['value'];
    $f_db_table_prefix =$g_form_fields['db_table_prefix']['value'];
    $f_user_admin_name =     $g_form_fields['user_admin_name']['value'];
    $f_user_admin_password = $g_form_fields['user_admin_password']['value'];

    ### check mysql-connection ###
    {

        render_testStart("checking mysql connecting to '$f_hostname'...");
		$dbh = @mysql_pconnect(
            $f_hostname,
            $f_db_username,
            $f_db_password
        );
        if(!$dbh || !is_resource($dbh)) {
            render_testResult(RESULT_FAILED,"mysql-error:<pre>".mysql_error()."</pre>");
            return false;
        }
        render_testResult(RESULT_GOOD,"");
    }
    
    ### does database already exists? ###
    {
        render_testStart("Make sure to not overwrite existing streber-db called '$f_db_name'");

		### db does NOT exists ###
        if(!mysql_select_db($f_db_name, $dbh)) {
            render_testResult(RESULT_GOOD);
            
            ### create new database ###
            render_testStart("create database");
            if(!$result=mysql_query("CREATE DATABASE $f_db_name")) {
                render_testResult(RESULT_FAILED,"<pre>".mysql_error()."</pre>");
                return false;
            }
            else {
                if(!mysql_select_db($f_db_name, $dbh)) {
                    render_testResult(RESULT_FAILED,"could not select created database");
                    return false;
                }
                else {
                    render_testResult(RESULT_GOOD);
                }
			}
		}


		### db exists / upgrade ###
        else {
            render_testResult(RESULT_PROBLEM,"DB '$f_db_name' already exists");
            
            ### check version of existing database ###
            render_testStart("checking version of existing database");
            if($result=mysql_query("SELECT * 
                                       FROM {$f_db_table_prefix}db 
                                      WHERE db.updated is NULL", $dbh)
			) {
                $count=0;
                $db_version=NULL;
                $streber_version_required=NULL;
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $db_version= $row['version'];
                    $streber_version_required= $row['version_streber_required'];
                    $count++;
                }            
                if( $count!=1 ) {
                    render_testResult(RESULT_FAILED,"could not get propper db-version table entry. Please view ".getStreberWikiLink('installation','Installation Guide')." on hints how to proceed.");
                    return false;
                }
                else if($db_version < confGet('DB_VERSION_REQUIRED')) {
                    
                    ### updade ###
                    render_testResult(RESULT_PROBLEM,"version is $db_version. Upgrading...");
                    $result= upgrade(array(
                        'hostname'=> $f_hostname,
                        'db_username'=> $f_db_username,
                        'db_password'=> $f_db_password,
                        'db_table_prefix'=> $f_db_table_prefix,
                        'db_name'=> $f_db_name,
                    ));
                    return $result;

                }
                else if($streber_version_required > confGet('STREBER_VERSION')) {
                    render_testResult(RESULT_PROBLEM,"version is $db_version. It's requires Version " .confGet('DB_VERSION_REQUIRED'). " of Streber. Current Version is ".confGet('STREBER_VERSION').". Please download and install the latest version.");
                    return false;
                }
                else {
                    render_testResult(RESULT_GOOD, "Current database (version $db_version) looks fine. Installation finished with nothing changed. Please view ".getStreberWikiLink('installation','Installation Guide')." on how to fix unsolved problems.");
                    return true;
                }
                render_testResult(RESULT_PROBLEM,"Installation aborted due to unknown reason.");
				return false;
            }

			### no version / fresh installation ###
			else {
                render_testResult(RESULT_GOOD, 
                                "Could not query streber-db version. Assuming fresh installation");


            }
        }

			

        ### creating database-structure ###
        render_testStart("creating tables...");

        $filename= "../_sql/create_structure_v".confGet('DB_CREATE_VERSION').".sql";
        
        if(!file_exists($filename)) {
            render_testResult(RESULT_FAILED,"Getting sql-code failed. This is an internal error. Look at ". getStreberWikiLink('installation','Installation Guide') ." for clues. ");
            return false;
        }
        if(!parse_mysql_dump($filename, $f_db_table_prefix)) {
            render_testResult(RESULT_FAILED,"SQL-Error:<br><pre>". mysql_error()."</pre>");
            return false;
        }
        render_testResult(RESULT_GOOD);
        
        ### create db-version entry ###
        render_testStart("add db-version entry");
        $db_version= confGet('DB_CREATE_VERSION');
        $streber_version_required= confGet('DB_CREATE_STREBER_VERSION_REQUIRED');
        $str_query= "INSERT into {$f_db_table_prefix}db (id,version,version_streber_required,created) VALUES(1,'$db_version','$streber_version_required',NOW() )";
        if(!mysql_query($str_query)) {
            render_testResult(RESULT_FAILED,"SQL-Error:<pre>". mysql_error(). "</pre>Query was:<pre>$str_query</pre>");
            return false;
        }
        else {
            render_testResult(RESULT_GOOD);
        }

        ### create admin entry entry ###
        render_testStart("add admin-user entry 1/2");
        $password_md5=md5($f_user_admin_password);
        $str_query= "INSERT into {$f_db_table_prefix}person 
                          (id,
                          name,
                          nickname,
                          password,
                          user_rights,
                          can_login,
                          profile
                          ) 
                          VALUES(
                          1,
                          '$f_user_admin_name',
                          '$f_user_admin_name',
                          '$password_md5',
                          268435455, /* all rights */
                          1,
                          1 )";
        if(!mysql_query($str_query)) {
            render_testResult(RESULT_FAILED,"SQL-Error:<br><pre>". mysql_error(). "</pre>Querry was:<pre>$str_query</pre>");
            return false;
        }
        else {
            render_testResult(RESULT_GOOD);
        }

        ### create admin entry entry ###
        render_testStart("add admin-user entry 2/2");
        $str_query= "INSERT into {$f_db_table_prefix}item 
                          (id,
                          type,
                          state
                          ) 
                          VALUES(
                          1,"
                          .ITEM_PERSON.",
                          1 )";
        if(!mysql_query($str_query)) {
            render_testResult(RESULT_FAILED,"SQL-Error:<br><pre>". mysql_error(). "</pre><br><br>Querry was:<br>$str_query");
            return false;
        }
        else {
            render_testResult(RESULT_GOOD);
        }


		### settings-directory already exists? ###
		if(!file_exists('../'. confGet('DIR_SETTINGS'))) {
	        render_testStart("try to create ".confGet('DIR_SETTINGS')."...");
			if(!mkdir('../'. confGet('DIR_SETTINGS'))) {
	            render_testResult(RESULT_FAILED,"could not create directory. This could be a file permission problem...");
			}
			else {
	            render_testResult(RESULT_GOOD);
			}
		}

        ### writing setting-file ###
        render_testStart("writing configuration file '". confGet('DIR_SETTINGS').  confGet('FILE_DB_SETTINGS')."'...");
        $filename='../'. confGet('DIR_SETTINGS').  confGet('FILE_DB_SETTINGS');
        $buffer='
#--- streber db-configuration file ---
# this file has automatically been created and might be
# overwritten be installation procedures. If you want
# to overwrite any of these settings add lines to
# "customize.inc" in streber-root directory

confChange("HOSTNAME",		"'.$f_hostname.'");
confChange("DB_USERNAME",	"'.$f_db_username.'");
confChange("DB_PASSWORD",	"'.$f_db_password.'");
confChange("DB_NAME",		"'.$f_db_name.'");
confChange("DB_TABLE_PREFIX","'.$f_db_table_prefix.'");
confChange("DB_VERSION","'      .confGet('DB_CREATE_VERSION').'");

';
        

    	$FH= @fopen ($filename,"w");
    	if(!$FH) {
            render_testResult(RESULT_FAILED,"can not write '$filename'. Please create it with this content:<br><pre>&lt;?php".$buffer."?&gt;</pre>");
            return false;
        }
        if(!fputs ($FH, "<"."?php".$buffer."?".">")) {
            render_testResult(RESULT_FAILED,"can not write '$filename'. Please create it with this content:<br><pre>&lt;?php".$buffer."?&gt;</pre>");
            return false;
        }
    	fclose ($FH);
        render_testResult(RESULT_GOOD);

		### tmp-directory already exists? ###
		if(!file_exists('../'. confGet('DIR_TEMP'))) {
	        render_testStart("try to create directory of tempory files ".confGet('DIR_TEMP')."...");
			if(!mkdir('../'. confGet('DIR_TEMP'))) {
	            render_testResult(RESULT_FAILED,"could not create directory. This could be a file permission problem...");
			}
			else {
	            render_testResult(RESULT_GOOD);
			}
		}



        return true;
    }
}

/**
* parse a mysql-dump with multiple queries and sent it to mysql
*
* - adds table-prefix to all select and create-statements
* - This function is a hack to quicky set up the db-structure. Sooner
*   or later it will be replaces with a reall table-creation-function.
*
*
*/
function parse_mysql_dump($url,$table_prefix="")
{
    $file_content = file($url);
    $query = "";
    
    foreach($file_content as $sql_line){
        if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
            $query .= $sql_line;
			### query complete ###
            if(preg_match("/;\s*$/", $sql_line)){

				### add table-prefixes ###
				$matches= array();
				if(preg_match("/(CREATE\s*TABLE\s[`´'](.*)[`´'])\s*\(/", $query, $matches)) {
					$create_string_old= $matches[1];
					$table_name_old= $matches[2];
					$create_string_new= str_replace($table_name_old, $table_prefix.$table_name_old, $create_string_old);
					$query= str_replace($create_string_old, $create_string_new, $query);
				}

				### send query ###
                if(!$result = mysql_query($query)) {
                    return false;
                }
                $query = "";
            }
         }
    }
    return true;
}


/**
* render form with essential information for installation
*
* this form is validated in render_step_done. If some fields are invalid, this
* function is called again.
*/
function render_form_step1(){
    ### create form ###
    {
        echo "<h2>Settings</h2>";
        echo '<form method=GET action="./install.php"><div  class=form>';
    
        global $g_form_fields;
        foreach($g_form_fields as $key=>$value) {
			$f=&$g_form_fields[$key];
			
            if(!$value=get($f['id'])) {
                $value="";
                if(isset( $f['value'])) {
                    $value= $f['value'];
                }
                else if(isset($f['default'])){
                    $value= $f['default'];
                }
            }
            
            $class_additional= "";
            if(isset($f['required']) && $f['required']) {
                $class_additional.=" required";
            }
            if(isset($f['error'])) {
                $class_additional.=" error";
            }
            echo "<p><label>{$f['label']}:</label><input class='inp$class_additional' name='{$f['id']}' value='$value'></p>";
        }
        echo "<input class=button_submit type=submit value='install / upgrade'>";
        echo "<input type=hidden name=install_step value=form_submit>";
        echo "</div></form>";
    }
}



function render_testStart($p_message=NULL)
{

    if(!$p_message) {
         trace("notice", "render_testStart called without message");
         $p_message="";
    }
    
    echo "<div class='test_start'>$p_message</div>";
}

function render_testResult($p_result, $p_message="") 
{

    $style= '';
    $msg= '?';
    if($p_result== RESULT_FAILED) {
        $style='failed';
        $msg='FAILED';
    }    
    else if ($p_result == RESULT_GOOD) {
        $style='good';
        $msg='GOOD';
    }
    else if ($p_result == RESULT_PROBLEM) {
        $style='problem';
        $msg='POTENTIAL PROBLEM';
    }
    echo "<div class='test_result $style'><b>$msg</b><br>$p_message</div><br>";
}









/**
* upgrades
*/
function upgrade($args=NULL) {
    


    $hostname=          $args['hostname'];
    $db_username=       $args['db_username'];
    $db_password=       $args['db_password'];
    $db_table_prefix=   $args['db_table_prefix'];
    $db_name=           $args['db_name'];
    

    echo "<h2>Upgrading...</h2>";

    ### get version ###
    {
        render_testStart("getting original version for upgrading database '$db_name' at '$hostname'...");

        ### connect db ###
		$dbh = @mysql_pconnect(
            $hostname,
            $db_username,
            $db_password
        );
        
        if(!$dbh || !is_resource($dbh)) {
            render_testResult(RESULT_FAILED,"mysql-error:<pre>".mysql_error()."</pre>");
            return false;
        }


        ### select db? ###
        if(!mysql_select_db($db_name, $dbh)) {
            render_testResult(RESULT_FAILED,"Database does not exists mysql-error:<pre>".mysql_error()."</pre>");
            return false;
        }
            

        if(!$result=mysql_query("SELECT * 
                                   FROM {$db_table_prefix}db 
                                  WHERE db.updated is NULL", $dbh)
		) {
            render_testResult(RESULT_FAILED,"Count not get version:<pre>".mysql_error()."</pre>");
            return false;
		}
		
        $db_version=NULL;
        $count= 0;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $db_version= $row['version'];
            $streber_version_required= $row['version_streber_required'];
            $count++;
        }            
        if( $count!=1 ) {
            render_testResult(RESULT_FAILED,"could not get propper db-version table entry. Please view ".getStreberWikiLink('installation','Installation Guide')." on hints how to proceed.");
            return false;
        }
        if($db_version < 0.044) {
            render_testResult(RESULT_FAILED,"Sorry upgrading is supoorted since v0.044");
            return false;
        }
        render_testResult(RESULT_GOOD,"v $db_version");

    }
    
    
    $update_queries=array();

    ### update from 0.044 to 0.045
    $update_queries[]="ALTER TABLE `task`   ADD `view_collapsed` TINYINT NOT NULL";
    $update_queries[]="ALTER TABLE `comment` ADD `view_collapsed` TINYINT NOT NULL";
    $update_queries[]="ALTER TABLE `effort` ADD `task` INT NOT NULL";


    render_testStart("doing " .count($update_queries). " changes to database...");
    foreach($update_queries as $q) {

        if(!$result=mysql_query($q)){
            render_testResult(RESULT_FAILED,"Failed:<pre>".mysql_error()."</pre>");
            return false;
		}
    }

    ### update the db-version ###
    render_testStart("update db-version information");
    $str_query= "UPDATE {$db_table_prefix}db
                SET  updated = now();
                ";
    if(!mysql_query($str_query)) {
        render_testResult(RESULT_FAILED,"SQL-Error:<br><pre>". mysql_error(). "</pre><br><br>Querry was:<br>$str_query");
        return false;
    }
    

    ### create new db-version ###
    $db_version= confGet('DB_CREATE_VERSION');
    $streber_version_required= confGet('DB_CREATE_STREBER_VERSION_REQUIRED');
    $str_query= "INSERT into {$db_table_prefix}db (id,version,version_streber_required,created) VALUES(1,'$db_version','$streber_version_required',NOW() )";
    if(!mysql_query($str_query)) {
        render_testResult(RESULT_FAILED,"SQL-Error:<pre>". mysql_error(). "</pre>Query was:<pre>$str_query</pre>");
        return false;
    }
    else {
        render_testResult(RESULT_GOOD);
    }
    return true;
}

