<?
function auth($login = '', $passwd = '')
{
	session_start();
	global $adminuser, $adminpass;
	global $HTTP_SESSION_VARS;
	$authdata = $HTTP_SESSION_VARS['authdata'];
	
	if ( is_array( $authdata ) ) {
		$un = $authdata['login'];
		$pw = $authdata['password'];
		$register = false;
	} elseif (!empty($login)) {
		$un = $login;
		$pw = $passwd;
		$register = true;
	} else {
		return false;
	}
	
	if ( $adminuser == $un && $adminpass == $pw ) {
	
		if ($register) {
			$HTTP_SESSION_VARS["authdata"] = array("login"=>$login, "password"=>$passwd);
		}
		
		return true;
	}

   	unset( $HTTP_SESSION_VARS['authdata'] );
   	return false;
}

function getTreeArray($currentdir = "", $parent = "0")
{
	unset($nodes);
	global $root, $id, $thumbflag;
	
	chdir($root . "/" . $currentdir);
	$handle = opendir(".");
	
	while ($file = readdir($handle)) {
		
		if(is_dir($file) && $file != "." && $file != "..") {
			$nodes[] = $file;
		}
	}
	
	closedir($handle);
	
	if(is_array($nodes)) {
		sort($nodes);
		reset($nodes);

	  	for($y=0; $y<sizeof($nodes); $y++) {
			unset($files);
			$newdir = $currentdir . "/" . $nodes[$y];
			chdir($root . "/" . $newdir);
			$handle = opendir(".");
			
			while ($file = readdir($handle)) {
				if(eregi("$thumbflag\.(jpg|gif|png|jpeg|jpe)$", $file)) {
					$files[] = $file;
				}
			}
			
			closedir($handle);
			
			echo "\t\tTree[" . $id . "]  = \"" . ($id + 1) . "|" . $parent . "|" . $nodes[$y] . "|" . $newdir . "|" . count($files) . "\";\n";
			$id++;
			getTreeArray($newdir, $id);
		}
	}
}

function getTemplates()
{
	global $HTTP_SERVER_VARS;
	$currentdir = dirname($HTTP_SERVER_VARS['PATH_TRANSLATED']);
	chdir("$currentdir/templates");
	$handle = opendir(".");

	while ($file = readdir($handle)) {
		if (is_dir($file) && $file != "." && $file != "..") {
			echo "<input type=\"hidden\" name=\"templates[]\" value=\"" . $file . "\">";
		}
	}

	closedir($handle);
	return $templates;
}
?>
