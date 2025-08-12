<?php
# All site specific configuration settings are located in this file
# Common settings shared by all sites are placed in the config.php file
# in your METAjour directory

# Site identification
$site = '1';

# Absolute path to the directory where the website is located
$viewer_path = 'c:/webserver/htdocs/www/';

# URL to the website
$viewer_url = 'http://www.mydomain.dk/';

$CONFIG['primary_language'] = 'EN';

# Inclusion of config.php
require('metajour/config.php');

$CONFIG['doctype'] = 'DOCTYPE_401_TRANS_WITH_URL';
?>