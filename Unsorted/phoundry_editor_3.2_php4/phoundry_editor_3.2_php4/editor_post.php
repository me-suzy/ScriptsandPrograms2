<?php

if (!isset($_GET))
	$_GET = &$HTTP_GET_VARS;
if (!isset($_POST))
	$_POST = &$HTTP_POST_VARS;

function PEmysystem($command, $windows = false) {
	if (!($p=popen("($command)2>&1",'r'))) return false;
	$output = '';
	while(!feof($p))
		$output .= fread($p,4096);
	pclose($p);
	if ($windows && strstr($output, 'not recognized as an internal or external command'))
		return false;
	else if(strstr($output, 'Permission denied') || strstr($output, 'No such file or directory'))
		return false;
	return $output;
}
	
function PEtidyUp() {
	global $_GET, $_POST, $HTTP_GET_VARS, $HTTP_POST_VARS;

	$ARR = NULL;
	if (isset($_POST['_pe_eds'])) {
		$PEeds = explode('|', $_POST['_pe_eds']);
		$ARR = &$_POST;
		$GP = 'POST';
		unset($_POST['_pe_eds']); unset($HTTP_POST_VARS['_pe_eds']);
	}
	else if (isset($_GET['_pe_eds'])) {
		$PEeds = explode('|', $_GET['_pe_eds']);
		$ARR = &$_GET;
		$GP = 'GET';
		unset($_GET['_pe_eds']); unset($HTTP_GET_VARS['_pe_eds']);
	}
	if ($ARR === NULL)
		return;

	$magic = get_magic_quotes_gpc();
	$windows  = stristr(php_uname(), 'windows') !== false;
	$dirSep   = $windows ? '\\' : '/';
	$file = @eval('return __FILE__;');
	preg_match("'(.*?)\([0-9]+\)'si", $file, $_reg);
	$cwd = dirname($_reg[1]);

	foreach($PEeds as $ed) {
		$feat  = explode(',', $ed);
		$name  = $feat[0];
		$page  = $feat[1];
		$what  = $feat[2];
		
		// If $what is empty, tidy is not used -> continue!
		if (empty($what)) continue;
		
		if ($magic)
			$ARR[$name] = stripslashes($ARR[$name]);
		$input = preg_replace("'<\s*SCRIPT\s+LANGUAGE\s*=\s*\"?php\"?\s*>(.*?)</SCRIPT>'si", "<?php$1?>", $ARR[$name]);
	
		$outFile = tempnam('','');
		$errFile = $outFile . '_err';
		if ($_fp = fopen($outFile,'w')) {
			fwrite($_fp, $input);
			fclose($_fp);
		}
		
		$what   = ($what == 'html') ? '-upper' : '-asxhtml';
			
		$config = $cwd . $dirSep . 'tidy' . $dirSep . ($page ? 'html.cfg' : 'body.cfg');
		$cmd = $cwd . $dirSep . 'tidy' . $dirSep . "tidy $what -config $config -f \"$errFile\" \"$outFile\"";
		$tidied = PEmysystem($cmd, $windows);
			
		if ($tidied === false) {
			// Could not execute tidy!
			print "Phoundry Editor: Cannot execute $cmd!<BR>\n";
			exit();
		}
		else if (empty($tidied)) {
			// Tidy could not tidy the source, restore original content!
			$tidied = $ARR[$name];
		}
		else {
			// Okay, tidy worked correctly.
			$tidied = preg_replace('/ +\?>/', '?>', $tidied);
			if ($what == '-asxhtml')
				$tidied = preg_replace("'<\?php(.*?)\?>'s", '<script language="php">$1</script>', $tidied);
			else
				$tidied = preg_replace("'<\?php(.*?)\?>'s", '<SCRIPT LANGUAGE="php">$1</SCRIPT>', $tidied);
		}
				
		if ($magic)
			$tidied = addslashes($tidied);
			
		if($GP == 'POST') {
			$_POST[$name] = $tidied;
			$HTTP_POST_VARS[$name] = $tidied;
		}
		else if($GP == 'GET') {
			$_GET[$name] = $tidied;
			$HTTP_GET_VARS[$name] = $tidied;
		}
		@unlink($outFile);
		@unlink($errFile);
	}
}

PEtidyUp();
?>
