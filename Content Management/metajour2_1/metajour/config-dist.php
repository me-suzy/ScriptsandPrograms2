<?php
# REMEMBER TO RENAME THIS FILE TO: config.php

############################################################################
# PRIMARY SETTINGS - YOU MUST EDIT THESE
############################################################################
 
# absolute path to METAjour installation - remember trailing slash!
$system_path = '/var/www/mydomain.com/htdocs/metajour/';
# or the DOS style absolute path:
# $system_path = 'c:/htdocs/mydomain.com/www/metajour/';

# full URL to METAjour installation - remember trailing slash!
$system_url = 'http://www.mydomain.com/metajour/';

# database type - currently only 'mysql' is valid
$CONFIG['sql_type']='mysql';

# sql server
$CONFIG['sql_host']='localhost';

# sql username
$CONFIG['sql_user']='root';

# sql password
$CONFIG['sql_password']='';

# sql database name
$CONFIG['sql_database']='metazo';

############################################################################
# DON'T EDIT SETTINGS BELOW - UNLESS YOU KNOW WHAT YOU'RE DOING
############################################################################

$CONFIG['time_limit']="43200";
$CONFIG['eventmail'] = true;
$CONFIG['eventfrom'] = 'METAZO auto-generated email <noreply@mydomain.com>';
$CONFIG['primary_language'] = 'EN';

?>