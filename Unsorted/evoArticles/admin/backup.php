<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require("./admin_common.php");
$usr->checkperm('',"isadmin");
// *************************************************** //
$script[version] = "0.5"; // 23/07/2002
$script[filename] = "backup.php";
$script[name] = "Backup System";
$site[title] = "Backup System";
// *************************************************** //
$PHP_SELF = $_SERVER['PHP_SELF'];
require ('lib/class_zip.php');
@set_time_limit(1200); // 2 minit cukup aa

//kena bace dulu file pastu baru add
//$zipfile -> add_file($filez, $file2);
//$admin->write_file("Filename", $zipfile -> file());
class BackupSystem
{
	var $maindir="";
	var $backupdir= "../backup/";
	
	function read_dir($dir='')
	{
		global $admin,$tpl,$evoLANG,$site,$db;

		$a = opendir($dir);
		while ($file = readdir($a))
		{
			if(($file != ".") && ($file != ".."))
			{
				$file2 = explode (".",$file);
				/*if ($file2[1] != '') { // not a dir
					$i++; $bg=$admin->get_bg($i);
					$filename = str_replace("_"," ", $file2[0]);
						eval("\$file_loop .= \"".$tpl->gettemplate("backup_fileloop")."\";");
				}*/
				
				if (!$file2[1])
				{
					$i++; $bg=$admin->get_bg($i);
					$dirname = str_replace("_"," ", $file2[0]);
						eval("\$dir_loop .= \"".$tpl->gettemplate("backup_dirloop")."\";");
				}
			}
		}
		closedir($a);

		$b = @opendir($this->backupdir);
		
		while($bfile = @readdir($b))
		{
			if ($bfile!="." && $bfile!="..")
			{
				$filedate = date("j M Y",fileatime($this->backupdir.$bfile));
				$filesize = filesize($this->backupdir.$bfile) / 1024;
				$filesize = round($filesize,1);

				eval("\$file_loop .= \"".$tpl->gettemplate("backup_fileloop")."\";");
			}
		}

		@closedir($b);
		$add = basename($site[url]);

		$dir_loop .= "<tr><td class=thrdalt> <li> </td><td class=thrdalt colspan=2> <a href=$PHP_SELF?do=backdir&d=../$add&n=All><b class=title>Entire Site</b></a></td></tr>\n";
		
		if ($file_loop!=''||$dir_loop!='')
		{
			eval("\$content .= \"".$tpl->gettemplate("backup_main")."\";");
		}
		
		return $content;
	}

	function backup($path,$name)
	{
		global $zipfile,$admin,$_GET,$site;
		
		$a = opendir($path);
		while ($file = readdir($a))
		{
			unset($file3,$path2);
			if ($file != "." && $file != "..")
			{ 
				$file2 = explode('.' , $file);	
					
					if (!$file2[1])
					{
						if (!preg_match("/backup/",$file2[0]))
						{
							$this->backup($path."/".$file2[0]."/",$name);
						}
					}
					else
					{
						$array = array('png','jpg','jpeg','tiff','bmp','gif','zip');

						$mode = in_array( strtolower($file2[1]),$array ) ? "rb":"r";
						$path2 = str_replace("../","",$path);
							
						$file3 = $path != $_GET[d] ? $path2.$file : basename($site[url])."/".$file;
							
						$file3 = str_replace("//","/",$file3);
						$zipfile->add_file($admin->get_file($path.'/'.$file,$mode),$base.$file3);
					}

			}
		}
		closedir($a);
	}

	function backup_mysql($dbname,$endit=0)
	{
		global $script,$database,$db,$site,$udb,$admin,$_GET,$_POST;
		sort($database);
		reset($database);
		
		$dbname = ($_GET['name'] != '') ? $_GET['name']:$db['name'];
		
		if ($_POST['only'] == "1")
		{
			$gettables = count($database);
		}
		else
		{
			$tables = mysql_list_tables($dbname);
			$gettables = $udb->num_rows($tables);
		}
		
		$a .= "############################################\n";
		$a .= "#$site[name] mySQL Database : $db[name]\n";
		$a .= "#Total Tables : $gettables\n";
		$a .= "############################################\n\n";

		$i = 0;
		$ender = ($endit) ? ';':'';
		while ($i < $gettables)
		{
			$i++;
			if ($_POST['only'] == "1")
			{
				$saparate = each($database);
				$row[0] = $saparate[1];
			}
			else
			{
				$row = $udb->fetch_array($tables);
			}
			
			//echo $row[0]."<br />";
			$a .= "############ ".$row[0]." #####################\n \n";
				
			$drop = "DROP TABLE IF EXISTS ".$row[0].$ender."\n";
			$a .= ($_POST['drop']=="1") ? $drop:'';

			$row2 = $udb->query_once("SHOW CREATE TABLE ".$row[0]);
			$a .= $row2[1].$ender."\n";
			$a .= ($_POST['data']) ? "\n".$this->backup_table($row[0],$endit):'';				
			$a .= "\n\n\n\n";

			$udb->free_result($row2);
		}
		return $a;
	}

	function backup_table($table,$endit=0)
	{
		global $script,$database,$db,$site,$udb,$admin;
		$sql = $udb->query("SELECT * FROM $table");
		$count = $udb->num_rows($sql);
		
		$countit = mysql_num_fields($sql);		
		
		while ($row = mysql_fetch_array($sql))
		{
			$a .= "INSERT INTO `".$table."` SET ";
			
			for ($i=0; $i < $countit; $i++)
			{
				$get = mysql_fetch_field($sql,$i);
				$a .= $get->name."='".$row[$i]."'";					
				
				if ($i+1 < $countit)
				{
					$a .= ", ";
				}
			}
			
			$a .= ($endit) ? ";":"";
			$a .= "\n";
		}

		return $a;

	}
}

$zipfile = new zipfile(); //the zip thingy
$bck = new BackupSystem;
$bck->maindir=$root;
@mkdir($bck->backupdir,0777);

switch ($_GET['do'])
{
	# **************************************** #
	case "backdir":
		$admin->nocache();
		$content .= $admin->do_nav("$evoLANG_admin[home]|$evoLANG_admin[backupsystem]","index.php|$PHP_SELF");
		$bck->backup($_GET[d],$_GET[n]);
		$filename=$bck->backupdir.$_GET[n].".zip";
		if (file_exists($filename)) { @unlink($filename); }
		$content .= $admin->write_file($filename,$zipfile -> file(),0,"wb",0);
		$content .= $admin->redirect($HTTP_SERVER_VARS['PHP_SELF']);
	break;
	# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< #
	case "deletefile":
		if ($_GET['file'])
		{
			unlink($_GET['file']) or die ("Unable to delete file.<br /> <a href=\"$_SERVER[HTTP_REFERER]\">Back</a>");
			header("location: $_SERVER[PHP_SELF]");
			exit;
		}
	break;
	# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< #
	case "backsql":
	$admin->nocache();
	$endit = ($_POST['endall'] == "1") ? "1":"0";
	
	if ($_GET['debug'] == "1")
	{
		$debug = ($bck->backup_mysql($db['name'])); // just for fun viewing
		$content .= "<textarea cols='140' rows='20'>".$debug."</textarea>";
	}
	else
	{
		$sqlbackup = "sqlbackup.txt";
		$sqlbackupzip = $bck->backupdir."sql_".date("dmy").".zip";

		if (file_exists($sqlbackupzip))
		{
			@unlink($sqlbackupzip);
		}
		
		$thebackup = $bck->backup_mysql($db['name'],$endit);
			$zipfile->add_file($thebackup,$sqlbackup);
		$admin->write_file($sqlbackupzip, $zipfile->file(),0,"wb",0);
		
		@unlink($sqlbackup);
		header("location: $PHP_SELF");
	}
	break;
	# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< #
	default:
	$content .= $admin->do_nav("$evoLANG_admin[home]|$evoLANG_admin[backupsystem]","index.php|$PHP_SELF");
	$content .= $bck->read_dir($bck->maindir);
}

eval("echo(\"".$tpl->gettemplate("main",1)."\");");

?> 