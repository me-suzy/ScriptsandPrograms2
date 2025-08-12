<?php 
/*
+--------------------------------------------------------------------------
|   Alex Install Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > Installationsdatei für Beta-Update
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
+--------------------------------------------------------------------------
*/

@error_reporting  (7);
@set_magic_quotes_runtime(0);

if (@get_magic_quotes_gpc() == 0) {
  $HTTP_GET_VARS = addslashes_array($HTTP_GET_VARS);
  $HTTP_POST_VARS = addslashes_array($HTTP_POST_VARS);
}

$old_version = "1.5.0 RC 5";
$new_version = "1.5.0";
$app_name = "News Engine";
$eng_type = "news";

$filename = "beta.php";

include_once("include/config.inc.php");
include_once("admin/enginelib/class.db.php");

$db_sql = new db_sql($dbName,$hostname,$dbUname,$dbPasswort);
$config = loadEngineSetting();

if (!empty($_GET))                   { extract($_GET); }
else if (!empty($HTTP_GET_VARS))     { extract($HTTP_GET_VARS); }

if (!empty($_POST))                  { extract($_POST); }
else if (!empty($HTTP_POST_VARS))    { extract($HTTP_POST_VARS); }

if (!empty($_COOKIE))                { extract($_COOKIE); }
else if (!empty($HTTP_COOKIE_VARS))  { extract($HTTP_COOKIE_VARS); }

if (!empty($_ENV))                   { extract($_ENV); }
else if (!empty($HTTP_ENV_VARS))     { extract($HTTP_ENV_VARS); }

if (!empty($_SERVER))                { extract($_SERVER); }
else if (!empty($HTTP_SERVER_VARS))  { extract($HTTP_SERVER_VARS); }

if (!empty($_SESSION))               { extract($_SESSION); }
else if (!empty($HTTP_SESSION_VARS)) { extract($HTTP_SESSION_VARS); }

buildSetupHeader();
if(!$_POST['step']) {
    buildFormHeader("2");
    buildHeaderRow();
    buildTableSeparator("Step 1 of 2");
    buildOneRow("<b>".$app_name." V".$new_version."</b>", "2", "right");
    buildOneRow("<p>Welcome to the Beta-Update - ".$app_name." V".$new_version." - Start your Update now.</p>
                <p>Be sure to backup all your own Modules and Containers! Many changes has been made and it is necessary to change the base Modules and Containers.</p>");
    buildFormFooter("Start", ""); 	
}

if($_POST['step'] == 2) {
	$db_sql->sql_query("ALTER TABLE $news_table ADD is_html tinyint(1) unsigned default '0' NOT NULL AFTER reads");
	
    buildHeaderRow();	
    buildTableSeparator('Update completed');
    buildOneRow("<b>".$app_name." V".$new_version."</b>", "2", "right");
	buildTableFooter();		
	buildTableHeader('Installation successfully updated!');
	buildOneRow("Congratulations, Update successfully<br><br>The ".$app_name." update has been completed. Please delete the file beta.php from your webserver. Click <a href=\"admin/index.php\">here</a>, to access the Admin Center");
	buildTableFooter();		
}

buildSetupFooter();

//-------------------------- Functions ----------------------------------//
function stripslashes_array(&$array) {
    reset($array);
    if(is_array($array)) {
    	foreach ($array as $key => $val) {
    		$array[$key] = (is_array($val)) ? stripslashes_array($val) : stripslashes($val);
   		}
      	return $array;
	}	
}	

function loadEngineSetting() {
    global $db_sql,$set_table,$_ENGINE;
    
    $result = $db_sql->sql_query("SELECT * FROM $set_table");
    while($set = $db_sql->fetch_array($result)) {
        $set = stripslashes_array($set);
        $config[$set['find_word']] = $set['replace_value'];
    }
    
    $_ENGINE['main_url'] = $config['newsscripturl'];
    $_ENGINE['languageurl'] = $_ENGINE['main_url']."/lang/".$config['language']."/images";  
    $_ENGINE['std_group'] = $config['std_group']; 
    $config['engine_mainurl'] = $config['newsscripturl'];
      
    return $config;    
}

function buildSetupHeader($head="") {
	global $a_lang, $config, $app_name;
	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<meta content="text/html; charset=windows-1252" http-equiv="Content-Type">
<html>
<head>
<title><?php echo $app_name; ?> - Setup</title>
<link rel="stylesheet" href="admin/acstyle.css">
<?php
echo $head;
?>
</head>
<body leftmargin="20" topmargin="0" marginwidth="20" marginheight="20"  bgcolor="#F4F7FE" text="#000000" align="center">
</br>

	<?php
	}
	
function buildSetupFooter() {
?>
</body></html>
<?php
}

function switchBgColor() {
	global $bgcount;
	if ($bgcount++%2==0) {
		return "firstcolumn";
	} else {
		return "othercolumn";
	}
}

function buildHeaderRow($colspan = 2) {
	echo "<table bgcolor=\"#000000\" width=\"90%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n<tr>\n<td>\n";
	echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
	echo "<tr>\n<td colspan=\"$colspan\" class=\"menu_desc\" align=\"center\">";
	echo "<img src=\"templates/default/images/installer_pic.gif\" width=\"390\" height=\"70\" border=\"0\">";
	echo "\n</td>\n</tr>\n";
}

function buildTableHeader($headline, $colspan = 2) {
	echo "<table bgcolor=\"#000000\" width=\"90%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n<tr>\n<td>\n";
	echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
	echo "<tr>\n<td colspan=\"$colspan\" class=\"menu_desc\">&raquo;&nbsp;";
	echo $headline;
	echo "\n</td>\n</tr>\n";
}

function buildTableSeparator($title, $colspan = 2) {
	echo "<tr>\n<td colspan=\"$colspan\" class=\"menu_desc\">&raquo;&nbsp;";
	echo $title;
	echo "\n</td>\n</tr>\n";
}	
	
function buildTableFooter($extra="",$colspan=2) {
	if ($extra!="") echo "<tr class=\"table_footer\">\n<td colspan=\"$colspan\" align=\"center\">$extra</td></tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n";
	echo "</table><br />\n";
}	
	
function buildFormHeader($step="", $method="post") {
	global $config,$filename;
	echo "<form action=\"".$filename."\" name=\"ase\" method=\"".$method."\">\n";	
	if ($step != "") echo "<input type=\"hidden\" name=\"step\" value=\"".$step."\">\n";
}

function buildFormFooter($submitname = "Submit", $resetname = "Reset", $colspan = 2) {
	echo "<tr class=\"table_footer\">\n<td colspan=\"".$colspan."\" align=\"center\">\n&nbsp;";
	
	if ($submitname != "") echo "<input type=\"submit\" value=\"   ".$submitname."   \" class=\"button\">\n";	
	if ($resetname != "") echo "<input type=\"reset\" value=\"   ".$resetname."   \" class=\"button\">\n";	

	echo "&nbsp;\n</td>\n</tr>\n</table>\n";
	echo "</td>\n</tr>\n</table>\n";
	echo "</form><br />\n";
}

function buildHiddenField($name,$value="",$html=0) {
	if ($html) $value=htmlspecialchars($value);
	echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";	
}	

function buildInputRow($title, $name, $value="", $size="40", $max_length="", $info=0 , $info_message="", $info_message_header="") {
	global $config,$bgcount;
    if ($max_length) $max = "maxlength=\"".$max_length."\"";
	if ($info) $help = "<a href=\"#\" onMouseOver=\"dcc('".$info_message_header."','".$info_message."'); return true;\" onMouseOut=\"nd(); return true;\"><img src=\"admin/images/info_set.gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></a>";
	echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" width=\"40%\"><p>".$title."</p></td>\n";
	echo "<td><p><input ".$max." type=\"text\" size=\"".$size."\" name=\"".$name."\" value=\"".$value."\">".$help."</p></td>\n</tr>\n";
}

function buildStandardRow($title, $value="",$html=0, $info=0 , $info_message="", $info_message_header="") {
	global $config,$bgcount;
	if ($html) $value=htmlspecialchars($value);
	if ($info) $help = "<a href=\"#\" onMouseOver=\"dcc('".$info_message_header."','".$info_message."'); return true;\" onMouseOut=\"nd(); return true;\"><img src=\"admin/images/info_set.gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></a>";	
	echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\"><p>".$title."</p></td>\n";
	echo "<td><p>".$value." ".$help."</p></td>\n</tr>\n";
}

function buildOneRow($title, $colspan="2", $align="", $html=0, $info=0 , $info_message="", $info_message_header="") {
	global $config,$bgcount;
	if ($html) $value=htmlspecialchars($value);
    if ($align) $align_insert = "align=\"".$align."\"";
	if ($info) $help = "<a href=\"#\" onMouseOver=\"dcc('".$info_message_header."','".$info_message."'); return true;\" onMouseOut=\"nd(); return true;\"><img src=\"admin/images/info_set.gif\" width=\"15\" height=\"15\" border=\"0\" align=\"middle\"></a>";		
	echo "<tr class=\"".switchBgColor()."\">\n<td colspan=\"".$colspan."\" valign=\"top\" ".$align_insert."><p>".$title." ".$help."</p></td>\n</tr>\n";
}

function splitSqlFile($sql, $delimiter) {
	$tokens = explode($delimiter, $sql);
	$sql = "";
	$output = array();

	$matches = array();

	$token_count = count($tokens);
	for ($i = 0; $i < $token_count; $i++) {
		if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
			$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
			$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

			$unescaped_quotes = $total_quotes - $escaped_quotes;

			if (($unescaped_quotes % 2) == 0) {
				$output[] = $tokens[$i];
				$tokens[$i] = "";
			} else {
				$temp = $tokens[$i] . $delimiter;
				$tokens[$i] = "";
				$complete_stmt = false;

				for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
					$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
					$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

					$unescaped_quotes = $total_quotes - $escaped_quotes;

					if (($unescaped_quotes % 2) == 1) {
						$output[] = $temp . $tokens[$j];
						$tokens[$j] = "";
						$temp = "";
						$complete_stmt = true;
						$i = $j;
					} else {
						$temp .= $tokens[$j] . $delimiter;
						$tokens[$j] = "";
					}

				}
			}
		}
	}
	return $output;
}

function deleteRemarks($sql) {
	$lines = explode("\n", $sql);
	$sql = "";

	$linecount = count($lines);
	$output = "";

	for ($i = 0; $i < $linecount; $i++) {
		if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
			if ($lines[$i][0] != "#") {
				$output .= $lines[$i] . "\n";
			} else {
				$output .= "\n";
			}
			$lines[$i] = "";
		}
	}
	return $output;
}
?>