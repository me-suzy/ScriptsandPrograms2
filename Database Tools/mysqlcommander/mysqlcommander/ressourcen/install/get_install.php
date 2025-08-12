<?php 
include "install_config.php";
if ( ($o_inst_props->s_mode != "private") AND ($o_inst_props->s_mode != "protected") ) {
	header("Content-Type: text/x-download\n");
	header("Content-Disposition: attachment; filename=\"class.superconfig.php\"");
	
	$a_varnames = $o_inst_props->getVarnames();
	
	for ($i=0; $i<count($a_varnames); $i++)	{
		if (isset($HTTP_POST_VARS[$a_varnames[$i]])) 
			$o_inst_props->setVariableValue($a_varnames[$i], $HTTP_POST_VARS[$a_varnames[$i]]);
	}
	//echo "<pre>";
	//print_r($_POST);
	//echo "</pre>";
	
	echo $o_inst_props->getClassConfigFile();
} else {
	echo "Installer runs in 'private' or 'protected' mode, no file available!";
}
?>
