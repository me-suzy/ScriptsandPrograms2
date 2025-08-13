<?php
	$windows  = stristr(php_uname(), 'windows') !== false;
	$dirSep   = $windows ? '\\' : '/';

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
	
	function fixIt($str) {
		$str = str_replace('&', '&amp;', $str);
		$str = str_replace('<', '&lt;', $str);
		$str = str_replace('>', '&gt;', $str);
		return $str;
	}

	$magic = get_magic_quotes_gpc();

	$input = $HTTP_POST_VARS['input'];
	if ($magic)
		$input = stripslashes($input);
	$input = preg_replace("'<\s*SCRIPT\s+LANGUAGE\s*=\s*\"?php\"?\s*>(.*?)</SCRIPT>'si", "<?php$1?>", $input);
	$inFile = tempnam('','');
	$errFile = $inFile . '_err';
	if ($fp = fopen($inFile,'w')) {
		fwrite($fp, $input);
		fclose($fp);
	}
	
	$name = $HTTP_GET_VARS['name'];
	$fn = '';
	if (isset($HTTP_GET_VARS['fn'])) {
		$fn = $HTTP_GET_VARS['fn'];
		if ($magic)
			$fn = stripslashes($fn);
		$fn = 'parent.' . $fn . ';';
	}

	$config = $HTTP_GET_VARS['page'] == 0 ? 'body.cfg' : 'html.cfg';
	$what   = $HTTP_GET_VARS['tidy'] == 'html' ? '-upper' : '-asxhtml';
	$cmd = getcwd() . $dirSep . "tidy $what -config $config -f $errFile $inFile";
	$tidied = PEmysystem($cmd, $windows);
	$tidymsg = '';
	$tidyExecError = '';
	if ($tidied === false) {
		$cmd = str_replace('\\', '\\\\', getcwd() . $dirSep . 'tidy');
		$tidyExecError = "alert('Cannot execute $cmd!');";
	}
	else {
		$tidied = preg_replace('/ +\?>/', '?>', $tidied);
		if ($what == '-asxhtml')
			$tidied = preg_replace("'<\?php(.*?)\?>'si", '<script language="php">$1</script>', $tidied);
		else
			$tidied = preg_replace("'<\?php(.*?)\?>'si", '<SCRIPT LANGUAGE="php">$1</SCRIPT>', $tidied);

		$tidied = fixIt($tidied);
		// Read Tidy messages:
		if ($fp = @fopen($errFile,'r')) {
			while(!feof($fp))
				$tidymsg .= fread($fp, 1024);
			fclose($fp);
		}
	}
	$tidymsg = fixIt($tidymsg);
	@unlink($inFile);
	@unlink($errFile);
	if (!empty($tidied) && $tidied !== false)
		$setValue = 'parent._form[\'' . $name . '\'].value = document.forms[0].tidied.value;';
	else
		$setValue = '';

	print <<<EOM
<HTML>
<HEAD>
<SCRIPT LANGUAGE="Javascript">
<!--

function init() {
	$tidyExecError
	try {
		$setValue
		parent.PEs['$name'].tidymsg = document.forms[0].tidymsg.value;
		var D = parent.document.frames['PhLp_$name'].document;
		$fn
	} catch(e) {}
}

//-->
</SCRIPT>
</HEAD>
<BODY onLoad="init()">
<FORM>
<TEXTAREA NAME="tidied">$tidied</TEXTAREA>
<TEXTAREA NAME="tidymsg">$tidymsg</TEXTAREA>
</FORM>
</BODY>
</HTML>
EOM;

?>
