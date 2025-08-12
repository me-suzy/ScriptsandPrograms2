<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Update from Server</title>
	<style>
		body, td {
			font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
			color : Black;
			font-size : 10pt;
		}
		
		.textdown {
			font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
			color : Black;
			font-size : 8pt;
		}
		.textrefresh {
			font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
			color : Black;
			font-size : 8pt;
		}
		a  { text-decoration: none; }
		a.nodetext:hover {color: #CA523E;}
		a.textdown:hover {color: #ff0000;}
		a.textrefresh:hover {color: #008000;}
	</style>
</head>

<body>
<?php
include("./class.ClientUpdate.php");
$b_show_readme = false;
$obj_cl_update = new ClientUpdate();
$s_akt_project_name = $obj_cl_update->getProjectInfo("../", "project_name");
?>
<strong>Updating the System<?php if ($s_akt_project_name != "") echo ": " . $s_akt_project_name?></strong><br>
<?php
    /**
     * Formats $value to byte view
     *
     * @param    double   the value to format
     * @param    integer  the sensitiveness
     * @param    integer  the number of decimals to retain
     *
     * @return   array    the formatted value and its unit
     *
     * @access  public
     *
     * @author   staybyte
     * @version  1.2 - 18 July 2002
     */
    function PMA_formatByteDown($value, $limes = 6, $comma = 0) {
		$byteUnits = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
        $dh           = pow(10, $comma);
        $li           = pow(10, $limes);
        $return_value = $value;
        $unit         = $byteUnits[0];

        for ( $d = 6, $ex = 15; $d >= 1; $d--, $ex-=3 ) {
            if (isset($byteUnits[$d]) && $value >= $li * pow(10, $ex)) {
                $value = round($value / ( pow(1024, $d) / $dh) ) /$dh;
                $unit = $byteUnits[$d];
                break 1;
            } // end if
        } // end for

        if ($unit != $byteUnits[0]) {
            $return_value = number_format($value, $comma, ",", ".");
        } else {
            $return_value = number_format($value, 0,  ",", ".");
        }

        return $return_value. " ". $unit;
    } // end of the 'PMA_formatByteDown' function

$a_serverproperties =  $obj_cl_update->getServerProperties();
$s_serverurl = "";
if (count($a_serverproperties)==0) {
	echo "'file_list.txt' not existant";
} else {
	if (isset($a_serverproperties['url'])) $s_serverurl = $a_serverproperties['url'];
	if (isset($a_serverproperties['system_code'])) $s_system_code = $a_serverproperties['system_code'];
	$s_akt_project_version = $obj_cl_update->getProjectInfo("../", "version");
	?>
		<script language="JavaScript">
			function loadFile(file_nr) {
				jetzt = new Date();
				<?php if ( isset($a_serverproperties['security']) AND ($a_serverproperties['security'] == 1) AND ($obj_cl_update->error != 10)) {?>
				username = document.forms.form_security.username.value;
				password = document.forms.form_security.password.value;
				<?php } else {?>
				username = "";
				password = "";
				<?php }?>
				document.location.href = "update.php?mode=update_file&file_nr=" + file_nr + "&username=" + username + "&password=" + password + "&now="+jetzt.getTime();
			}
		</script>
	<hr>
	<?php
	
	
	if (!isset($_GET['mode'])) {
		$s_success = false;
		$s_success = $obj_cl_update->getUpdaterListFile($s_serverurl);
		if ($s_success) {
			if ($obj_cl_update->isNewUpdaterVersion()) echo "<font color=\"#ff0000\">New Updater version available!</font>&nbsp;&nbsp;&nbsp;<a href='update.php?mode=update_updater&now=".time()."' class='textdown'>&gt;&gt; ( <u>download</u> )</a><br><br>";
			else echo "<font color=\"#008000\">Updater is up to date!</font><br><br>";
		} else echo "<font color=\"#000000\">New updater version not available!</font><br><br>";

		$s_success = false;
		$s_success = $obj_cl_update->getNewListFile($s_serverurl, $s_system_code);
		if ($s_success) echo "<font color=\"#008000\">File for update was downloaded</font><br><br>"; else echo "<font color=\"#ff0000\">File for update not available</font><br>". $obj_cl_update->s_server_message."<br>";
			if ($s_success) {
				$b_success_readme = $obj_cl_update->getNewReadmeFile($s_serverurl, $s_system_code);
				$b_show_readme = true;
				$a_files = $obj_cl_update->getFileList();
				$a_serverproperties =  $obj_cl_update->getServerProperties();
			?>
	<hr>
	&nbsp;&nbsp;&nbsp;<a href="update.php?now=<?php echo time();?>" class="textrefresh">[ <u>refresh view</u> ]</a><hr>
	&nbsp;&nbsp;&nbsp;<a href="javascript:loadFile(0);" class="textdown">( <u>start update</u> )</a><br><br>
<?php if ( isset($a_serverproperties['security']) AND ($a_serverproperties['security'] == 1) ) {?>
	<form name="form_security">
	<table>
	<tr>
		<td class="textrefresh">Username:</td>
		<td><input type="text" name="username" size="20" maxlength="20" class="textrefresh"></td>
	</tr>
	<tr>
		<td class="textrefresh">Password:</td>
		<td><input type="text" name="password" size="20" maxlength="20" class="textrefresh"></td>
	</tr>
	</table>
	</form>
<?php }?>
	<table border="0" cellspacing="0" cellpadding="0">
	<?php if ($s_akt_project_version != "") {?>
	<tr>
		<td>Actual Version:</td>
		<td><?php echo $s_akt_project_version;?></td>
	</tr>
	<?php } 
	if ($a_serverproperties['version'] != "") {?>
	<tr>
		<td>New Version:</td>
		<td><?php echo $a_serverproperties['version'];?></td>
	</tr>
	<?php }
	if (isset($a_serverproperties['download_cycle']) AND $a_serverproperties['download_cycle'] != "") {?>
	<tr><td height="10">&nbsp;</td></tr>
	<tr>
		<td>Update cycle:&nbsp;</td>
		<td><?php if ($a_serverproperties['download_cycle'] == 0) echo "immediate"; elseif ($a_serverproperties['download_cycle'] == -1) echo "manual"; else echo $a_serverproperties['download_cycle']."&nbsp;sec";?></td>
	</tr>
	<?php }?>
	</table>
	<br>
			<?php 
				?>
				<strong>Files for update:</strong><br><font class='text8'>
				<table cellpadding="0" cellspacing="0" border="0">
				<?php
				$i_whole_filesize = 0;
				$i_down_filesize = 0;
				$i_down_filecount = 0;
				 for ($i=0; $i<count($a_files); $i++) {
					$i_whole_filesize = $i_whole_filesize + (int)$a_files[$i]['filesize'];
					echo "<tr><td>&nbsp;<font size='1'>".$a_files[$i]['filepath'] . "</font>&nbsp;</td><td><font size='1' color='gray'>(". PMA_formatByteDown($a_files[$i]['filesize'], 2, 1) . ")</font>&nbsp;</td>";
					$act_hash = $obj_cl_update->getHashForFile($a_files[$i]['filepath']);
					if (!$act_hash) {
						echo "<td>&nbsp;<font size=\"1\" color=\"#ff0000\">file is new!</font>&nbsp;</td>";
						$i_down_filesize = $i_down_filesize + (int)$a_files[$i]['filesize'];
						$i_down_filecount++;
					} elseif (trim($act_hash) == trim($a_files[$i]['filehash'])) echo "<td>&nbsp;<font size=\"1\" color=\"#008000\">file is up to date!</font>&nbsp;</td>";
					else {
						echo "<td>&nbsp;<font size=\"1\" color=\"#ff0000\">file will be updated!</font>&nbsp;</td>";
						$i_down_filesize = $i_down_filesize + (int)$a_files[$i]['filesize'];
						$i_down_filecount++;
					}
					//echo trim($act_hash) . "|" .trim($a_files[$i]['filehash']) . "<br>";
				}
				echo "</table>";
					echo "<br><br><b>Total: <font size=\"1\" color=\"#008000\">$i</font></b> Files in <b><font size=\"1\" color=\"#008000\">" . PMA_formatByteDown($i_whole_filesize, 2, 1) . "</font></b>";
					echo "<br><b>Download: <font size=\"1\" color=\"#ff0000\">$i_down_filecount</font></b> Files in <b><font size=\"1\" color=\"#ff0000\">" . PMA_formatByteDown($i_down_filesize, 2, 1) . "</font></b>";
				$b_show_readme = true;
			}
	} else {
		// running the update for the Updater files
		if ($_GET['mode'] == "update_updater") {
			echo "<font class=\"textdown\"><strong>Loading Updater files ...</strong></font><br><br>";
			$a_files = $obj_cl_update->getFileList("updater_list.txt");
			for ($i=0; $i<count($a_files); $i++) {
				echo "<font size=\"1\">";
				$s_success = false;
				$s_filehash = $obj_cl_update->getHashForFile($a_files[$i]['filepath'], "./");
				echo "checking " . $a_files[$i]['filepath']." ... ";
	
				if ($s_filehash == false) {
					$s_success = $obj_cl_update->downloadFile($s_serverurl, "000_client", $a_files[$i], "./");
					echo "<font size=\"1\" color=\"#ff0000\">file is new</font> ... ";
				} elseif (trim($s_filehash) == trim($a_files[$i]['filehash'])) {
					$s_success = true;
					echo "<font size=\"1\" color=\"#008000\">file is up to date</font> ... ";
				} else {
					$s_success = $obj_cl_update->downloadFile($s_serverurl, "000_client", $a_files[$i], "./");
					echo "<font size=\"1\" color=\"#ff0000\">updating</font> ... ";
				}
				if ($s_success) {
					echo "done<br>";
				} else {
					echo " file not available!<br>";
				}
				echo "</font>";
			}
		}
		// running the update for the System files
		if ($_GET['mode'] == "update_file") {
			echo "<font class=\"textdown\"><strong>Loading System files ...</strong></font><br><br>";
			if ($_GET['file_nr'] == "0") $p_logfile = fopen ("./update.log", "w+");
			else  $p_logfile = fopen ("./update.log", "r+");
			$i_act_file = $_GET['file_nr'];
			$a_files = $obj_cl_update->getFileList();
			//print_r($a_files);
			if (isset($_GET['username'])) $strUsername = $_GET['username']; else $strUsername = "";
			if (isset($_GET['password'])) $strPassword = $_GET['password']; else $strPassword = "";
			$s_logile_text = "";
			while (!feof($p_logfile)) {
				$s_logile_text .= fgets($p_logfile, 1024);
				$s_logile_text .= "<br>";
			}
			if ($_GET['file_nr'] < count($a_files)) {
				$i_file_nr = $_GET['file_nr'];
				echo "<font size=\"1\">";
				$s_success = false;
				$s_filehash = trim($obj_cl_update->getHashForFile($a_files[$i_file_nr]['filepath']));
				while (($i_file_nr < count($a_files)) AND ($s_filehash == trim($a_files[$i_file_nr]['filehash'])) ) {
					$i_file_nr++;
					if ($i_file_nr < count($a_files)) $s_filehash = trim($obj_cl_update->getHashForFile($a_files[$i_file_nr]['filepath']));
				}
				if ($i_file_nr < count($a_files)) {
					fputs($p_logfile, "checking " . $a_files[$i_file_nr]['filepath']." ... ");
					echo "checking " . $a_files[$i_file_nr]['filepath']." ... ";
					if ($s_filehash == false) {
						$s_success = $obj_cl_update->downloadFile($s_serverurl, $s_system_code, $a_files[$i_file_nr],"../", $strUsername, $strPassword);
						fputs($p_logfile, "file is new ... ");
						echo "<font size=\"1\" color=\"#ff0000\">file is new</font> ... ";
					} elseif (trim($s_filehash) == trim($a_files[$i_file_nr]['filehash'])) {
						$s_success = true;
						fputs($p_logfile, "file is up to date ... ");
						echo "<font size=\"1\" color=\"#008000\">file is up to date</font> ... ";
					} else {
						$s_success = $obj_cl_update->downloadFile($s_serverurl, $s_system_code, $a_files[$i_file_nr], "../", $strUsername, $strPassword);
						fputs($p_logfile, "updating ... ");
						echo "<font size=\"1\" color=\"#ff0000\">updating</font> ... ";
					}
		
					if ($s_success) {
						fputs($p_logfile, "done!\n");
						echo "done<br>";
					} else {
						fputs($p_logfile, " file not available! " . $obj_cl_update->errorMessage . "\n");
						echo " file not available! <font size=\"1\" color=\"#ff0000\">" . $obj_cl_update->errorMessage."</font><br>";
					}
				}
				echo "<hr>\n";
				echo $s_logile_text;
				echo "</font>";
				?>
<?php if ( isset($a_serverproperties['security']) AND ($a_serverproperties['security'] == 1) ) {?>
	<form name="form_security">
		<input type="hidden" name="username" value="<?php if (isset($_GET['username'])) echo $_GET['username'];?>">
		<input type="hidden" name="password" value="<?php if (isset($_GET['password'])) echo $_GET['password'];?>">
	</form>
<?php }
				if ($obj_cl_update->error == 0) {
?>				
						<script language="JavaScript">
							setTimeout("loadFile(<?php echo $i_file_nr+1;?>)", 500);			
						</script>
<?php			}
			} else {
				echo "<font color=\"#008000\">finished</font>";
				echo "<hr>\n";
				echo "<font size=\"1\">";
				echo $s_logile_text;
				echo "</font>";
				$b_show_readme = true;
			}
			fclose($p_logfile);
		}
	
	}
}
?>
</font>
<?php
if (!isset($_GET['mode'])) {
?>
<br>
<br>	
&nbsp;&nbsp;&nbsp;<a href="javascript:loadFile(0);" class="textdown">( <u>start update</u> )</a><br>
<?php }?>
<br>
<hr>
&nbsp;&nbsp;&nbsp;<a href="update.php?now=<?php echo time();?>" class="textrefresh">[ <u>refresh view</u> ]</a><hr>
<br><br>
<?php
if (file_exists("./readme.txt") AND ($b_show_readme)) {
	$p_readmefile = fopen ("./readme.txt", "r+");
	$s_readme_text = "";
	while (!feof($p_readmefile)) {
		$s_readme_text .= fgets($p_readmefile, 1024);
	}
	fclose($p_readmefile);
?>
<strong><u>README</u></strong><br><br>
<?php
	echo str_replace("\n", "<br>", htmlspecialchars($s_readme_text));
}
?>
<br><br>
</body>
</html>