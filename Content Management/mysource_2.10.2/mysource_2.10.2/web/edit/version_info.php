<?
require_once("../init.php");

ini_set('track_errors', true);

$XML_FILE = dirname(__FILE__).'/mysource.xml';

$system_config = &get_system_config();

# Let's do this thing now. Find all the Xtra.info files ....
$xtra_files = find_xtras($XTRAS_PATH,"Xtra.info");

$fname = $GLOBALS['XML_FILE'];
$fp = fopen($fname, 'w') or die("Unable to open $fname: " . $php_errormsg);
$xml = "<?xml version='1.0' encoding='UTF-8' ?>\n";
$xml .= "<SystemInfo>\n";
$xml .= "\t<Domain>" . $_SERVER['HTTP_HOST'] . "</Domain>\n";
$xml .= "\t<Server>" . $_SERVER['SERVER_NAME'] . "</Server>\n";
$xml .= "\t<BasePath>" . $GLOBALS['SYSTEM_ROOT'] . "</BasePath>\n";
$xml .= "\t<MySourceVersion>" . MYSOURCE_VERSION . "</MySourceVersion>\n";
$xml .= "\t<ModulesList>\n";
fputs($fp, $xml, strlen($xml));
fclose($fp);

foreach($xtra_files as $type => $data) {
	foreach($data as $template => $file) {
		if (is_array($file)) {
			foreach($file as $templ => $xtrafile) {
				export_data($xtrafile);
			}
		} else {
			export_data($file);
		}
	}
}

$fp = fopen($fname, 'a+') or die("Unable to open $fname: " . $php_errormsg);
$xml = "\t</ModulesList>\n";
$xml .= "</SystemInfo>\n";
fputs($fp, $xml, strlen($xml));
fclose($fp);

# Well, we've saved the file. Let's print it out now.
header('Content-type: text/xml');

$xml_file = file('mysource.xml');
$xml_file = implode("",$xml_file);
echo $xml_file;

/*
	Function lists below.
*/

function export_data($file='') {
	if (!$file || !is_file($file)) return;
	$fcontents = file ($file);
	$file = str_replace('/Xtra.info', '', $file);

	$xml = "\t\t<Module>\n";
	$xml .= "\t\t\t<FilePath>" . $file . "</FilePath>\n";
	while (list ($line_num, $line) = each ($fcontents)) {
		$line = trim($line);
		if (preg_match('/^\s*#/', $line) || strlen($line) == 0) continue;
		list($type, $data) = explode(':', $line);
		$type = trim(ucwords(strtolower($type)));
		$data = trim($data);
		if ($type == 'Name' || $type == 'Version' || $type == 'Requires') {
			$xml .= "\t\t\t<" . $type . ">" . htmlspecialchars($data) . "</" . $type . ">\n";
		}
	}
	$xml .= "\t\t</Module>\n";

	$fname = $GLOBALS['XML_FILE'];
	$fp = fopen($fname, 'a+') or die("Unable to open $fname: " . $php_errormsg);
	fputs($fp, $xml, strlen($xml));
	fclose($fp);
}

function find_xtras($dir, $file, $prune=array()) {

	# If we're meant to skip it, let's skip it.
	if (in_array("$dir/$file", $prune)) return;

	# find files from a specified directory.
	if(file_exists("$dir/$file")) {
		return "$dir/$file";
	}

	if(!$d = opendir($dir)) {
		echo "Unable to open Directory: $dir".__FILE__.__LINE__."<br>";
		return false;
	}

	$result = array();
	while($f = readdir($d)) {
		if (is_dir("$dir/$f") && $f[0] != "." && $f != "CVS") {
			$result[$f] = find_xtras("$dir/$f",$file, $prune);
		}
		if (empty($result[$f])) unset($result[$f]);
	}
	closedir($d);
	return $result;
}

?>