<?




// MyNewsGroups :) Emergency config file

$myng_db['host'] = 'your_db_host';
$myng_db['database'] = 'your_db_name';
$myng_db['user'] = 'your_user';
$myng_db['password'] = 'your_password';

// Dont change it!
$myng_db['prefix'] = 'myng_';

// Server root till MyNewsGroups folder (example provided)
$myng_root = '/home/htdocs/myng/';
// Include the required files
include($myng_root.'/include.php');

$start = start_time();

define('MYNG_INSTALLED', true);

define('MYNG_VERSION', '0.6');

?>