<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

require("./admin_common.php");
// *************************************************** //
$script[version] = "0.5"; // 23/07/2002
$script[filename] = "templates.php";
$script[name] = "Template Manager";
$script[prefix] = "tpl";
$script_db[settings] = $script[prefix]."_file";
$site[title] = "Template Manager";
// *************************************************** //

$admin->checkadmin();
class Tpl_Manager
{
	var $mainfolder="";

	/*
	function printinfo()
	{
		global $script;
			$a .= "<h1> $script[name] </h1>";
			$a .= "version : <b>".$script[version]."</b><br />";
			$a .= "prefix : <b>".$script[prefix]."</b><br />";
			$a .= "filename : <b>".$script[filename]."</b><br />";
		return $a;
	}
	*/

	function get_bg($i,$is3=0)
	{ // to get alternating color
		$bg1="class=firstalt";
		$bg2="class=secondalt";
		$bg3="class=thrdalt";
		if ($is3) {	$bg = $bg3; } else {
		  if ($i % 2) { $bg=$bg1; } else { $bg=$bg2; }
		}
		return $bg;
	}

	function chmoder($dirname)
	{
		global $tpl;
		$dir = $dirname;
		$maindir=$this->mainfolder;
		$a = opendir($dir);
		chmod($dir,0777);
		$dir2 = str_replace("../","",$dir);
		$content .= "<b class=title><i>$dir2</i> chmoded....</b> <br />";
		while ($file = readdir($a)) {
			if(($file != ".") && ($file != "..")) {
				$file2 = explode (".",$file);
				if ($file2[1] != '') { // not a dir
					chmod("$dir/$file",0777);
					$content .= "<li> <i><b>$dir2/$file</b></i> Chmoded .....<br />";
				}
			}
		} closedir($a);		
		return $content;
	}

	function editfile($fileurl,$style=0)
	{
		global $tpl,$evoLANG,$admin,$evoLANG,$_SERVER;
			$REQUEST_URI = $_SERVER['REQUEST_URI'];
			$f = $fileurl;
			$filecontent=$admin->get_file($fileurl);
			$filecontent=htmlspecialchars($filecontent);
			
			if ($style==1) {
				$fileurl2=str_replace("/".basename($fileurl),'',$fileurl); // get style folder
				$curstyle = substr(strrchr($fileurl2, "/"),1); // get current style

				$handle = opendir($this->mainfolder);
				while($file= readdir($handle)) {
					unset($chked);if ($files != "." && $file != "..") {
						$files = explode(".",$file); $file = $files[0];
						if (preg_match("/style/",$file)) {
							if ($file == $curstyle) { $chked="checked"; $file2="<a href='$REQUEST_URI'>$file</a>";} else {
							$file2=str_replace($curstyle,$file,$REQUEST_URI); $file2="<a href='$file2'>$file</a>"; }
								$liststyle .= "$file2 <input type=checkbox name=\"styles[$file]\" value=\"$file\" $chked>\n";
						}
					}
				}
				closedir($handle);
				$apply_text = "<input type=hidden name=multiple value='yes' />\n";
				$apply_text .= "<b>Apply for : $liststyle</b>";
				$additional ="<tr><td class=firstalt><b>$evoLANG[word_option]</b></td><td class=secondalt>$apply_text</td></tr>";
			}
			$filen = basename($fileurl); $filen = explode(".",$filen); $filename=$filen[0];
			eval("\$content .= \"".$tpl->gettemplate("template_editfile_form")."\";");
		return $content;
	}

	function previewpage($fileurl) {
		global $tpl,$evoLANG,$udb,$admin;
			$f = $fileurl;
			$filecontent=$admin->get_file($fileurl);
			if ($filecontent=='') { $filecontent = "Empty"; }

			$filen = basename($fileurl); $filen = explode(".",$filen); $filename=$filen[0];
			eval("\$content .= \"".$tpl->gettemplate("template_previewpage")."\";");
		return $content;
	}

	function main_page() {
		global $tpl,$evoLANG,$udb,$admin;
		eval("\$content .= \"".$tpl->gettemplate("template_mainpage")."\";");

		return $content;
	}

	function read_tpldir() {
		global $tpl,$script,$evoLANG,$_SERVER;
		$maindir = $this->mainfolder;
		$dir=$maindir;
		$content .= "<p align=right>";
		$content .= "<b><a href=$_SERVER[PHP_SELF]?do=makedir&f=$dir>$evoLANG[adddir]</a></b> ";
		$content .= "<b><a href=$_SERVER[PHP_SELF]?do=makefile&f=$dir>$evoLANG[addfile]</a></b>";
		$content .= "</p><br />";

		$a = opendir($dir);
		while ($file = readdir($a)) {
			if(($file != ".") && ($file != "..")) {
				$file_s = explode(".",$file);
				if (!$file_s[1] && preg_match("/style/i",$file_s[0])) { // if found style folder
					eval("\$style_loop .= \"".$tpl->gettemplate("template_styleloop")."\";");
				} else {
					if (!$file_s[1]) { // this is directory
						
						eval("\$dir_loop .= \"".$tpl->gettemplate("template_dirlist_loop")."\";");						
					} else {					
						$i++; $bg=$this->get_bg($i);
						$filename = str_replace("_"," ", $file_s[0]);
						  eval("\$file_loop .= \"".$tpl->gettemplate("template_filelist_loop")."\";");
					}
				}
			}
		} closedir($a);
		if($style_loop != '') { 
		eval("\$content .= \"".$tpl->gettemplate("template_style")."\";"); 
		$content .= "<hr size=1><br />";}
		if ($dir_loop != '') { eval("\$content .= \"".$tpl->gettemplate("template_dirlist")."\";"); }
		if($file_loop != '') { 
			$content .= "<span class=title style='font-size:18pt;'>Templates -</span><br /><br />";
		    eval("\$content .= \"".$tpl->gettemplate("template_filelist")."\";"); }
		return $content;
	}

	function confirmpage($f,$isfile=0,$url='') { // this is the file version. the content.php was a sql version. had to rewrote
		global $script_db,$udb,$admin,$tpl,$evoLANG,$_SERVER,$_GET;
		$REQUEST_URI=$_SERVER[REQUEST_URI];
		/*$msg = basename($f);
		$msg = "(".stripslashes($msg).")";*/
			if (preg_match("/confirm=no/",$REQUEST_URI)) {
				$REQUEST_URI = str_replace("confirm=no","confirm=yes",$REQUEST_URI);
				$url = "<a href=$REQUEST_URI>Yes</a>";
			}
				eval("\$content .= \"".$tpl->gettemplate("deletepage")."\";");
			return $content;
	}

	function delfile($f) {
		$content .= $this->confirmpage($_GET[f]);
		return $content;
	}
	
	function makefile($n='') {
		global $tpl,$admin,$evoLANG,$_POST,$_GET;
		$f=$_GET[f];
		if ($n == '') {
			eval("\$content .= \"".$tpl->gettemplate("template_addfile")."\";");
		} else {
			$f = $_POST[f].$_POST[n].".inc";
			eval("\$content .= \"".$tpl->gettemplate("template_addfile_form")."\";");
		}
		return $content;
	}
	function makedir($n='') {
		global $tpl,$admin,$evoLANG,$_POST,$_SERVER,$_GET;
		$f = $_GET[f];
		if ($n == '') {	
			eval("\$content .= \"".$tpl->gettemplate("template_adddir")."\";");
		} else {
			$f = $_POST[f].$_POST[n];
			$admin->makedir($f,$_POST[chmod]);
			$content .= $evoLANG[word_done]." <br />";
			$content .= $evoLANG[template_andchmoded]." <i><b>".$_POST[chmod]."</i></b>";
			echo $admin->redirect($_SERVER[PHP_SELF]);
		}
		return $content;
	}

	function getdir($dirname,$style=0) {
		global $tpl,$evoLANG,$_SERVER;
		$dir = $dirname;
		$maindir=$this->mainfolder;
		$content .= "<p align=right>";
		$content .= "<b><a href=$_SERVER[PHP_SELF]?do=makedir&f=$dir>$evoLANG[adddir]</a></b> ";
		$content .= "<b><a href=$_SERVER[PHP_SELF]?do=makefile&f=$dir>$evoLANG[addfile]</a></b>";
		$content .= "</p><br />";
		$a = opendir($dir);
		if ($style==1) { $isstyle = "&s=yes"; }
		while ($file = readdir($a)) {
			if(($file != ".") && ($file != "..")) {
				$file2 = explode (".",$file);
				if ($file2[1] != '') { // not a dir
					$i++; $bg=$this->get_bg($i);
					$filename = str_replace("_"," ", $file2[0]);
						  eval("\$file_loop .= \"".$tpl->gettemplate("template_filelist_loop")."\";");
				}
			}
		} closedir($a);
			
			eval("\$content .= \"".$tpl->gettemplate("template_filelist")."\";");
		return $content;
	}
}
$temp = new Tpl_Manager;
$temp->mainfolder="../templates";
// UDB - CNT - TPL
$addon_links = $tpl->getnav("template_links");
$PHP_SELF=$_SERVER[PHP_SELF];

switch ($_GET['do']) {
	# **************************************** #
	case "info":
		 echo $content = $temp->printinfo();
	exit;
	break;
	# **************************************** #
	case "getdir":
		$loc = str_replace("../","",$_GET[dir]); $loc = str_replace("/"," > ",$loc);
		
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[templatebrowsedir]|$loc",
									"index.php|$_SERVER[PHP_SELF]|$_SERVER[PHP_SELF]|$_SERVER[REQUEST_URI]");
		$content .= $s=="yes"? $temp->getdir($_GET[dir],1):$temp->getdir($_GET[dir]);
	break;
	# **************************************** #
	case "submit":
	if ($_POST[editfile]) {
		if ($_POST[multiple] == "yes") {
			if ($_POST[styles] != "") {
				while ($a = each($_POST[styles])) {
					$url = explode("/",$_POST[url]);
					$fileurl=$url[0]."/".$url[1]."/".current($a)."/".$url[3];			
					$content .= $admin->write_file($fileurl,$_POST[content]);
					$applied .= "applied for <b>".current($a)."</b> too.. <br />";
				}
			}
			echo $admin->redirect($PHP_SELF);
		} else {
			$admin->write_file($_POST[url],$_POST[content]);
			echo $admin->redirect($PHP_SELF);
		}
		unset($url,$fileurl);
	}
	if ($_POST[addfile]) { $admin->write_file($_POST[f],$_POST[file]);
													echo $admin->redirect($PHP_SELF); }
	unset($content);
	$content .= $applied;
	$content .= $evoLANG[word_done];

	break;
	# **************************************** #
	case "edit":
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[templatefileedit]",
									"index.php|$_SERVER[PHP_SELF]|$REQUEST_URI");
		$content .= $s=="yes"? $temp->editfile($_GET[f],1):$temp->editfile($_GET[f]);
	break;
	# **************************************** #
	case "chmoder":
		if ($_GET[f]) { $f = $_GET[f]; }
		if ($_POST[f]) { $f = $_POST[f]; }

		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[chmoder]",
									"index.php|$_SERVER[PHP_SELF]|nolink");
		$content .= $temp->chmoder($f);
		$content .= "<br /><b>".$evoLANG[word_done]."</b><br />";
		$content .= $admin->redirect($_SERVER[HTTP_REFERER],3);
	break;
	# **************************************** #
	case "preview":
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[content_preview]",
									"index.php|$PHP_SELF|$_SERVER[REQUEST_URI]");
		$content .= $temp->previewpage($_GET[f]);
		
	break;
	# **************************************** #
	case "delfile":
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[delfile]",
									"index.php|$PHP_SELF|$_SERVER[REQUEST_URI]");
		if ($_GET[confirm] == "no") {
			$content .= $temp->delfile($_GET[f]);
		} else {
			@unlink($_GET[f]);
			$content .= "<br />".$evoLANG[filedeleted];
			$content .= $admin->redirect($PHP_SELF);
		}
		
	break;
	# **************************************** #
	case "deletedir":
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[deletedir]",
									"index.php|$_SERVER[PHP_SELF]|nolink");
		if ($_GET[confirm] == "no") {
			$content .= $temp->confirmpage($_GET[f],1);
		} else {
			$content .= $admin->deldir($_GET[f]);
			$content .= $admin->redirect($PHP_SELF);
		}
		
	break;
	# **************************************** #
	case "makefile":
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[addfile]",
									"index.php|$PHP_SELF|$_SERVER[REQUEST_URI]");
		$content .= $temp->makefile($_POST[n]);
		
		
	break;
	# **************************************** #
	case "makedir":
		$content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]|$evoLANG[adddir]",
									"index.php|$_SERVER[PHP_SELF]|nolink");
		$content .= $temp->makedir($_POST[n]);
		
		
	break;


	# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< #
	default:
		 $content .= $admin->do_nav("$evoLANG[home]|$evoLANG[templatemanager]",
									"index.php|$PHP_SELF");
		 
	     $content .= $temp->read_tpldir();
		 $content .= $temp->main_page();
}
eval("echo(\"".$tpl->gettemplate("main",1)."\");");

?>