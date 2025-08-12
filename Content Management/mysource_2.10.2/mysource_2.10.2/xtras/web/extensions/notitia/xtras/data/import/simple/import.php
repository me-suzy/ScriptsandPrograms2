<?php
    ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/data/import/simple/import.php,v $
## $Revision: 1.7 $
## $Author: achadszinow $
## $Date: 2004/03/31 01:43:50 $
##############################################################################################################
#
# This script works with the Simple Notitia importer (Web_Extension_Notitia_Data_Import_Simple) to 
# run an import from the server command line
#
##############################################################################################################

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
ini_set('memory_limit', '-1');

$MYSOURCE_SYSTEM_ROOT = dirname(__FILE__).'/../../../../../../../..';

$IMPORT_CACHE_FILE = $_SERVER['HOME'].'/.'.basename(__FILE__).'.cache';

# the number of records to process at a time - sorry couldn't think of a better name :)
$MAGIC_NUMBER = 50;
# the number of secs to sleep between each record load
$SLEEP = 0;

if (empty($_SERVER['argv'][1]) || !is_readable($_SERVER['argv'][1])) {
	die("You need to pass a filename to the script that is the import file. It must also be readable\n");
}

if (isset($_SERVER['argv'][2])) {
	$MAGIC_NUMBER = (int) $_SERVER['argv'][2];
}

if (isset($_SERVER['argv'][3])) {
	$SLEEP = (int) $_SERVER['argv'][3];
}

$IMPORT_FILE = $_SERVER['argv'][1];

 ############################################################################################################
##############################################################################################################
#                              GET THE CONFIG INFORMATION FROM THE USER                                      #
#                              AND SETUP THE "SESSION" AND IMPORTER                                          #
##############################################################################################################
 ############################################################################################################

$ARGV = Array(
	'username'			=> '',
	'password'			=> '',
	'categoryid'		=> '',
	'parameters'		=> Array(),
);


if (file_exists($IMPORT_CACHE_FILE) && is_readable($IMPORT_CACHE_FILE)) {
	$tmp = unserialize(file_get_contents($IMPORT_CACHE_FILE));
	if (is_array($tmp)) {
		foreach($tmp as $k => $v) {
			if (isset($ARGV[$k])) $ARGV[$k] = $v;
		}
	}
}

# simple fn to print a prompt and return what the user enters
function get_line($prompt='')
{
	echo $prompt;
	// now get their entry and remove the trailing new line
	return rtrim(fgets(STDIN, 4094));
}

// get the main (simple) vars
for(reset($ARGV); NULL !== ($k = key($ARGV)); next($ARGV)) {
	$prompt = ucwords(str_replace('_', ' ', $k));

	if ($ARGV[$k]) {
		if ($k == 'parameters') {
			echo "\nExisting Parameters\n";
			print_r($ARGV[$k]);
			$prompt .= ' [blank string to use existing]';
		} else {
			$prompt .= ' ['.$ARGV[$k].']';
		}
	}
	$prompt .= ': ';
	do {
		$loop = false;
		$tmp = get_line($prompt);
		if ($tmp) {
			if ($k == 'parameters') {
				$unser = @unserialize(str_replace(Array("~cr~", "~nl~", "~~"), Array("\r", "\n", "~"), $tmp));
				if ($unser === FALSE) {
					echo "*** Something went wrong with the parameters data, try again ***\n";
					$loop = true;
				} else {
					echo "\nPassed Parameters\n";
					print_r($unser);
					if (strtoupper(get_line('Params OK (Y/N) : ')) == 'Y') {
						$ARGV[$k] = $unser;
					} else {
						$loop = true;
					}
				}
			} else {
				$ARGV[$k] = $tmp;
			}
		}// end if
	} while($loop);

}// end for

$fp = fopen($IMPORT_CACHE_FILE, 'w');
if ($fp) {
	fwrite($fp, serialize($ARGV));
	fclose($fp);
} else {
	echo 'Unable to write Cache File : '.$IMPORT_CACHE_FILE."\n";
}

 ############################################################################################################
##############################################################################################################
#                                 DO THE ACUTAL IMPORT BELOW HERE                                            #
##############################################################################################################
 ############################################################################################################

echo "\nImport Started At ".date("d/M/Y H:i:s")."\n";
$start_time = time();

# Clandestinely Log-in as the user :)
$_REQUEST['mysource_session_action']	= 'login';
$_POST['mysource_login']				= $ARGV['username'];
$_POST['mysource_password']				= $ARGV['password'];
$_POST['mysource_login_key']			= '';

require_once $MYSOURCE_SYSTEM_ROOT.'/web/init.php';

$session = &get_mysource_session();
if (!$session->logged_in()) die('Unable to log-in : '.$session->message()."\n");

# Now that we are logged in we can do what ever we want ....
$web = &get_web_system();
$notitia = &$web->get_extension('notitia');

$importer = &$notitia->get_data_import('simple', $ARGV['categoryid']);

$file_delimiter = $importer->delimiters[$ARGV['parameters']['delimiter']];

$fp = fopen($IMPORT_FILE, 'r');
if (!$fp) die("Unable to open file\n");
echo 'Processing '.$IMPORT_FILE."\n\n";

$headers = fgetcsv($fp, 65535, $file_delimiter);
if (empty($headers)) {
	die("No header or content\n");
}

$session->set_var('notitia_import_data_parameters', $ARGV['parameters']);
$session->set_var('matrix_header', $headers);

$importer->parameters = $ARGV['parameters'];
if (!$importer->validate_primary_key($headers)) {
	die("Aborting due to lack of primary key\n");
}

$survivors = '';
$session->set_var('notitia_data_import_survivors', $survivors);
$results_message = '';
$session->set_var('results_message', $results_message);

$data_cache = Array();
$data_count = 0;
$data_done_count = 0;


$import_start = time();
$avg_time = 0;

echo "Importing Records...\n";
while(!feof($fp)) {
	$data = fgetcsv($fp, 65535, $file_delimiter);
	if ($data !== FALSE) {
		$data_cache[] = $data;
		$data_count++;

		if ($data_count >= $MAGIC_NUMBER) {
			$importer->import($data_cache);
			$data_done_count += $data_count;
			unset($data_cache);
			$data_cache = Array();
			$data_count = 0;
			$avg_time = (time() - $import_start) / $data_done_count;
			printf ("    Records Imported  : % 8d -> Avg Record Time : % 3.2f secs\r", $data_done_count, $avg_time);
			sleep($SLEEP);
		}

	}// end if

}// end while

// if there was any that we haven't imported (ie the number of lines didn't divide evenly by $MAGIC_NUMBER)
// do them now
if ($data_count) {
	$importer->import($data_cache);
	$data_done_count += $data_count;
	$avg_time = (time() - $import_start) / $data_done_count;
	printf ("    Records Imported  : % 8d -> Avg Record Time : % 3.2f secs\r", $data_done_count, $avg_time);
}

echo "\n";

unset($data_cache);
unset($data_count);
unset($data_done_count);

$delete_start = time();
$avg_time = 0;

echo "Deleting Unused...\n";
$tree = &$notitia->get_category_tree();
$all_categories = $tree->all_descendantids($importer->categoryid);

array_unshift($all_categories, $importer->categoryid);
$num_to_process = 0;
if (!empty($all_categories)) {
	foreach($all_categories as $categoryid){
		$num_to_process = $num_to_process + count($record_list = $importer->get_primary_recordid_list($categoryid));
	}
}
# This should always run at least once so cleanup of empty cats possible
$i = 0;
do {
	$i = $i + $MAGIC_NUMBER;
	$importer->delete_unused($MAGIC_NUMBER);
	$avg_time = (time() - $import_start) / $data_done_count;
	printf ("    Records Processed : % 8d -> Avg Record Time : % 3.2f secs\r", $data_done_count, $avg_time);
} while ($i < $num_to_process);
echo "\n";

echo "Import Finished At ".date("d/M/Y H:i:s")."\n";
include_once $SQUIZLIB_PATH.'/general/general.inc';
echo "Duration : ".easy_time_total(time() - $start_time)."\n";

?>
