<?php
function convertforwards($part) {
	$array_1 = array("?", "&", "=");
	$array_2 = array("#", ",", "!");
	return str_replace($array_1, $array_2, $part);
}
						
function convertbackwards($part) {
	$array_1 = array("?", "&", "=");
	$array_2 = array("#", ",", "!");
	return str_replace($array_2, $array_1, $part);
}

if($_GET['file'] == "") {
	$perms = fileperms('../'.$_GET['dir']);
} else {
	$perms = fileperms('../'.$_GET['dir'].'/'.convertbackwards($_GET['file']));
}
if (($perms & 0xC000) == 0xC000) {
   // Socket
   $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
   // Symbolic Link
   $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
   // Regular
   $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
   // Block special
   $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
   // Directory
   $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
   // Character special
   $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
   // FIFO pipe
   $info = 'p';
} else {
   // Unknown
   $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
           (($perms & 0x0800) ? 's' : 'x' ) :
           (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
           (($perms & 0x0400) ? 's' : 'x' ) :
           (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
           (($perms & 0x0200) ? 't' : 'x' ) :
           (($perms & 0x0200) ? 'T' : '-'));

$info = substr($info, strlen($info) - 3, 3);
?><html><head><title>RFX-Folder Program</title><link rel="stylesheet" href="styles.css">
</head><body><?
$fp = fopen("http://www.radiantfx.com/open/RFX-Folder_Version.txt", "r");
if($fp) {
	$version = fread($fp, 5);
	if($version != "") {
		if($version != "1.3") {
			echo "<div align=\"center\"><b>Version out of date, please go to RadiantFX.com to update.</b></div>";
		} else {
			echo "<!-- Version Update -->";
		}
	} else {
		echo "<!-- Unable to connect to update server -->";
	}
fclose($fp);
} else {
	echo "<!-- Unable to connect to update server -->";
}
?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="17"><img src="images/box_top_left.png" width="17" height="30"></td>
        <td width="766" background="images/box_top_center.png"><span class="title"><?
		$dirs = explode("/", $_GET['dir']);
		if($dirs[count($dirs) - 1] != "") {
			echo $dirs[count($dirs) - 1];
		} else {
			echo "RFX-Folder";
		}
		?></span></td>
        <td width="17"><img src="images/box_top_right.png" width="17" height="30"></td>
      </tr>
      <tr>
        <td colspan="3"><table width="800" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="4" background="images/box_middle_left.png">&nbsp;</td>
            <td width="792"><table width="792" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="41" background="images/button_bg.png" class="button_set"><img src="images/back.png" onMouseOver="this.src='images/back_over.png';" onMouseOut="this.src='images/back.png';" onClick="javascript:history.go(-1);" width="77" class="buttons" height="37"><img src="images/forward.png" onClick="javascript:history.go(+1);" onMouseOver="this.src='images/forward_over.png';" onMouseOut="this.src='images/forward.png';" class="buttons" width="51" height="37"><? if($_GET['dir'] != "") {
				$dirs = explode("/", $_GET['dir']);
				for($i = 0; $i < count($dirs) - 1; $i++) {
					if($i != 0) {
						$link .= "/";
					}
					$link .= $dirs[$i];
				}
				?><img src="images/up_folder.png" onMouseOver="this.src='images/up_folder_over.png';" onClick="window.location='<? echo $PHP_SELF."?dir=".$link; ?>';" onMouseOut="this.src='images/up_folder.png';" class="buttons" width="32" height="37"><? } else { ?><img src="images/up_folder_disabled.png" class="buttons" width="32" height="37"><? } ?></td>
              </tr>
              <tr>
                <td background="images/address_bg.png"><img src="images/address.png" width="49" height="23" align="top"> <input name="address" type="text" class="address" id="address" size="120" value=" /<? echo $_GET['dir']; ?>"></td>
              </tr>
              <tr>
                <td><table width="790" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td background="images/main_bg.png"><table width="790" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="210" valign="top"><br>
                          <table width="185" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td><img src="images/tab.png" width="185" height="25"></td>
                            </tr>
                            <tr>
                              <td bgcolor="#D6DFF7"><?
							  if(eregi("w", $info)) {
							  	?>
                                <table width="160" border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td colspan="2" height="5"></td>
                                    </tr><tr>
                                    <td width="16"><img src="images/newfolder.png" width="16" height="16"></td>
                                    <td>&nbsp;<span class="style1"><a href="<? echo $PHP_SELF; ?>?dir=<? echo $_GET['dir']; ?>&atx=newfolder">Make new folder</a></span> </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" height="5"></td>
                                    </tr>
                                  <tr>
                                    <td><img src="images/upload.png" width="16" height="16"></td>
                                    <td class="style1">&nbsp;<a href="<? echo $PHP_SELF; ?>?dir=<? echo $_GET['dir']; ?>&atx=upload">Upload files</a> </td>
                                  </tr><tr>
                                    <td colspan="2" height="5"></td>
                                    </tr><?
									if($_GET['file'] != "") {
?><tr>
                                    <td><img src="images/denied.png" width="16" height="16"></td>
                                    <td class="style1">&nbsp;<a href="<? echo $_SERVER['PHP_SELF']; ?>?dir=<? echo $_GET['dir']; ?>&amp;file=<? echo $_GET['file']; ?>&amp;atx=deletefile">Delete File</a></td>
                                  </tr><tr>
                                    <td colspan="2" height="5"></td>
                                    </tr><tr>
                                    <td><img src="images/download.png" width="16" height="16"></td>
                                    <td class="style1">&nbsp;<a href="download.php?dir=<? echo $_GET['dir']; ?>&amp;file=<? echo $_GET['file']; ?>">Download File</a></td>
                                  </tr><tr>
                                    <td colspan="2" height="5"></td>
                                    </tr><?
									} elseif($_GET['dir'] != "") {
									?><td><img src="images/denied.png" width="16" height="16"></td>
                                    <td class="style1">&nbsp;<a href="<? echo $_SERVER['PHP_SELF']; ?>?dir=<? echo $_GET['dir']; ?>&amp;atx=deletefolder">Delete Folder</a></td>
                                  </tr><tr>
                                    <td colspan="2" height="5"></td>
                                    </tr>
									<?
									}
									?>
                                </table>
                                <?
							  } else {
							  	?><table width="160" border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tr>
                                    <td colspan="2" height="5"></td>
                                    </tr>
                                  <tr>
                                    <td><img src="images/denied.png" width="16" height="16"></td>
                                    <td class="style1">&nbsp;Needs Chmod777</td>
                                  </tr><tr>
                                    <td colspan="2" height="5"></td>
                                    </tr><? if($_GET['file'] != "") { ?><tr>
                                    <td><img src="images/download.png" width="16" height="16"></td>
                                    <td class="style1">&nbsp;<a href="download.php?dir=<? echo $_GET['dir']; ?>&amp;file=<? echo $_GET['file']; ?>">Download File</a></td>
                                  </tr><tr>
                                    <td colspan="2" height="5"></td>
                                    </tr><? } ?>
                                </table><?
							  }
							  ?></td>
                            </tr>
                          </table>
                          <p>&nbsp;</p>
                        </div></td>
                        <td width="580" valign="top"><?
						function changefilesize($bytes) {
							if($bytes < 1000) {
								return $bytes." Bytes";
							} elseif($bytes >= 1000 && $bytes < 1000000) {
								return round($bytes / 1024)." Kb";
							} else {
								return round($bytes / 1024 / 1024)." Mb";
							}
						}
						

						
						function showDir($dir, $i, $maxDepth){
   $i++;
   if($checkDir = opendir($dir)){
       $cDir = 0;
       $cFile = 0;
       // check all files in $dir, add to array listDir or listFile
       while($file = readdir($checkDir)){
           if($file != "." && $file != ".."){
               if(is_dir($dir . "/" . $file)){
                   $listDir[$cDir] = $file;
                   $cDir++;
               }
               else{
                   $listFile[$cFile] = $file;
                   $cFile++;
               }
           }
       }
      
       // show directories
       if(count($listDir) > 0){
           sort($listDir);
           for($j = 0; $j < count($listDir); $j++){
               echo "<div class=\"section\"><div class=\"folder\">";
                   // create link
				   if($_GET['dir'] == "") {
				   		$overall = "";
				   } else {
				   		$overall = $_GET['dir']."/";
				   }
                   echo "<a href=\"" . $_SERVER["PHP_SELF"] . "?dir=" . $overall . $listDir[$j] . "\"><img src=\"images/spacer.gif\" height=\"60\" width=\"60\" alt=\"".$listDir[$j]."\" border=\"0\"></a>";
                   echo "</div><div class=\"section_text\">".$listDir[$j]."</div></div>";
               // list all subdirectories up to maxDepth
               if($i < $maxDepth) showDir($dir . "/" . $listDir[$j], $i, $maxDepth);
           }
       }
      
       // show files
       if(count($listFile) > 0){
           sort($listFile);
           for($k = 0; $k < count($listFile); $k++){
		   		$filename_ex = explode(".",$listFile[$k]);
				if($_GET['dir'] != "") {
					$direst = "../".$_GET['dir']."/";
				} else {
					$direst = "../";
				}
				switch(strtolower($filename_ex[count($filename_ex) - 1])) {
					case "png":
					case "bmp":
					case "jpg":
					case "jpeg":
					case "gif":
						$filetype = "image";
						break;
					case "psd":
						$filetype = "photoshop";
						break;
					case "php":
					case "php3":
						$filetype = "php";
						break;
					case "zip":
						$filetype = "zip";
						break;
					case "html":
					case "htm":
					case "css":
						$filetype = "html";
						break;
					case "ini":
					case "htaccess":
					case "db":
						$filetype = "htaccess";
						break;
					case "txt":
					case "text":
						$filetype = "text";
						break;
					default:
						$filetype = "file";
				}
				if($filetype == "image") {
					$link =  "<a href=\"" . $_SERVER["PHP_SELF"] . "?dir=" . $_GET["dir"] . "&amp;file=".convertforwards($listFile[$k])."&amp;atx=show\"><img src=\"images/spacer.gif\" height=\"60\" width=\"60\" alt=\"".$listFile[$k]."\" border=\"0\"></a>";
				} else {
					$link =  "<a href=\"download.php?dir=" . $_GET["dir"] . "&amp;file=".convertforwards($listFile[$k])."\"><img src=\"images/spacer.gif\" height=\"60\" width=\"60\" alt=\"".$listFile[$k]."\" border=\"0\"></a>";
				}
				echo "<div class=\"section\"><div class=\"".$filetype."\">".$link."</div><div class=\"section_text_file\"><br>".$listFile[$k]."<br><font color=\"#999999\">".changefilesize(filesize($direst.$listFile[$k]))."</font></div></div>"; 
				
           }
       }       
       closedir($checkDir);
   }
}
if(eregi("r", $info)) {
	if($_GET['atx'] == "") {
		if($_GET["dir"] == "" || !is_dir("../".$_GET["dir"])) $dir = "../";
		else $dir = "../".$_GET["dir"];
	
	// show parent path
	$pDir = pathinfo($dir);
	$parentDir = $pDir["dirname"];
	showDir($dir, -1, $maxDepth); 
	} elseif($_GET['atx'] == "show") {
		$fileshow = convertbackwards($_GET['file']);
		
		if(file_exists("../".$_GET['dir']."/".$fileshow)) {
			$filename_ex = explode(".",$fileshow);
			switch(strtolower($filename_ex[count($filename_ex) - 1])) {
						case "png":
						case "bmp":
						case "jpg":
						case "jpeg":
						case "gif":
							echo "<div class=\"show_area\" align=\"center\"><img src=\"../".$_GET['dir']."/".$fileshow."\"></div>";
						break;
						default:
							$fp = fopen("../".$_GET['dir']."/".$fileshow, "r");
							$array_1 = array("\t", "<", ">");
							$array_2 = array("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "&lt;", "&gt;");
							echo "<div class=\"show_area\">".nl2br(str_replace($array_1, $array_2, fread($fp, 1024 * 1024)))."</div>";
							fclose($fp);
			}
		} else {
			echo "Error: File does not exist!";
		}
	} elseif($_GET['atx'] == "newfolder") {
		if($_POST['inputcomplete'] == "") {
			?><form action="<? echo $PHP_SELF; ?>?dir=<? echo $_GET['dir']; ?>&atx=newfolder" method="post" enctype="multipart/form-data" name="form_newfolder">
                          <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="100"><span class="medium">Folder 1:</span></td>
                              <td><input name="newfolder_1" type="text" class="medium" id="newfolder_1" size="40" maxlength="30"></td>
                            </tr>
                                                        <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">Folder 2: </span></td>
                              <td><input name="newfolder_2" type="text" class="medium" id="newfolder_2" size="40" maxlength="30"></td>
                            </tr>
                            <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">Folder 3: </span></td>
                              <td><input name="newfolder_3" type="text" class="medium" id="newfolder_3" size="40" maxlength="30"></td>
                            </tr>
                            <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">Folder 4: </span></td>
                              <td><input name="newfolder_4" type="text" class="medium" id="newfolder_4" size="40" maxlength="30"></td>
                            </tr>
                            <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">Folder 5: </span></td>
                              <td><input name="newfolder_5" type="text" class="medium" id="newfolder_5" size="40" maxlength="30"></td>
                            </tr>
                              <tr>
                                <td colspan="2" height="5"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><div align="center">
                                  <input name="inputcomplete" type="hidden" value="true"><input type="submit" class="medium" name="Submit" value="Submit">
                                </div></td>
                                </tr>
                          </table>
                        </form>
                        <?
		} else {
			for($i = 1; $i < 6; $i++) {
				if($_POST['newfolder_'.$i] != "") {
					if($_GET['dir'] != "") {
						mkdir("../".$_GET['dir']."/".$_POST['newfolder_'.$i]);
						chmod("../".$_GET['dir']."/".$_POST['newfolder_'.$i], 0777);
					} else {
						mkdir("../".$_POST['newfolder_'.$i]);
						chmod("../".$_POST['newfolder_'.$i], 0777);
					}
				}
			}
			echo "Folders created.<script language=\"javascript\">
			function redir() {
				window.location = '".$_SERVER["PHP_SELF"]."?dir=".$_GET['dir']."';
			}
			setTimeout('redir()', 2000);
			</script>";
		}
	} elseif($_GET['atx'] == "upload") {
		if($_POST['inputcomplete'] == "") {
			?><form action="<? echo $PHP_SELF; ?>?dir=<? echo $_GET['dir']; ?>&atx=upload" method="post" enctype="multipart/form-data" name="form_upload">
                          <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td width="100"><span class="medium">File 1:</span></td>
                              <td><input name="file_1" type="file" class="medium" id="newfolder_1" size="40" maxlength="30"></td>
                            </tr>
                                                        <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">File 2: </span></td>
                              <td><input name="file_2" type="file" class="medium" id="newfolder_1" size="40" maxlength="30"></td>
                            </tr>
                            <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">File 3: </span></td>
                              <td><input name="file_3" type="file" class="medium" id="newfolder_1" size="40" maxlength="30"></td>
                            </tr>
                            <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">File 4: </span></td>
                              <td><input name="file_4" type="file" class="medium" id="newfolder_1" size="40" maxlength="30"></td>
                            </tr>
                            <tr>
                              <td colspan="2" height="5"></td>
                              </tr><tr>
                              <td><span class="medium">File 5: </span></td>
                              <td><input name="file_5" type="file" class="medium" id="newfolder_1" size="40" maxlength="30"></td>
                            </tr>
                              <tr>
                                <td colspan="2" height="5"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><div align="center">
                                  <input name="inputcomplete" type="hidden" value="true"><input type="submit" class="medium" name="Submit" value="Submit">
                                </div></td>
                                </tr>
                          </table>
                        </form>
                        <?
		} else {
			for($i = 1; $i < 6; $i++) {
				if(file_exists($_FILES['file_'.$i]['tmp_name'])) {
					if($_GET['dir'] != "") {
						copy ($_FILES['file_'.$i]['tmp_name'], "../".$_GET['dir']."/".$_FILES['file_'.$i]['name']);
						chmod("../".$_GET['dir']."/".$_FILES['file_'.$i]['name'], 0777);
					} else {
						copy ($_FILES['file_'.$i]['tmp_name'], "../".$_FILES['file_'.$i]['name']);
						chmod("../".$_FILES['file_'.$i]['name'], 0777);
					} 
				}
			}
			echo "Files uploaded.<script language=\"javascript\">
			function redir() {
				window.location = '".$_SERVER["PHP_SELF"]."?dir=".$_GET['dir']."';
			}
			setTimeout('redir()', 2000);
			</script>";
		}
	} elseif($_GET['atx'] == "deletefile") {
		if($_POST['inputcomplete'] == "") {
			?><form action="<? echo $PHP_SELF; ?>?dir=<? echo $_GET['dir']; ?>&file=<? echo $_GET['file']; ?>&atx=deletefile" method="post" enctype="multipart/form-data" name="form_delete">
                          <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td colspan="2"><span class="medium">Confirm delete of <? echo $_GET['file']; ?>...<br><input name="confirm_delete" type="checkbox" value="true">
                                Yes</span></td>
                              </tr>
                              <tr>
                                <td colspan="2" height="5"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><div align="center">
                                  <input name="inputcomplete" type="hidden" value="true"><input type="submit" class="medium" name="Submit" value="Submit">
                                </div></td>
                                </tr>
                          </table>
                        </form>
                        <?
		} else {
			if($_POST['confirm_delete'] == "true") {
				if($_GET['dir'] != "") {
					unlink("../".$_GET['dir']."/".$_GET['file']);
				} else {
					unlink("../".$_GET['file']);
				}
				echo "File deleted.";
			} else {
				echo "File was not confirmed for deletion.";
			}
			echo "<script language=\"javascript\">
			function redir() {
				window.location = '".$_SERVER["PHP_SELF"]."?dir=".$_GET['dir']."';
			}
			setTimeout('redir()', 2000);
			</script>";
		}
	} elseif($_GET['atx'] == "deletefolder") {
		if($_POST['inputcomplete'] == "") {
			?><form action="<? echo $PHP_SELF; ?>?dir=<? echo $_GET['dir']; ?>&atx=deletefolder" method="post" enctype="multipart/form-data" name="form_delete">
                          <table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                              <td colspan="2"><span class="medium">Confirm delete of <? echo $_GET['dir']; ?>...<br><input name="confirm_delete" type="checkbox" value="true">
                                Yes</span></td>
                              </tr>
                              <tr>
                                <td colspan="2" height="5"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><div align="center">
                                  <input name="inputcomplete" type="hidden" value="true"><input type="submit" class="medium" name="Submit" value="Submit">
                                </div></td>
                                </tr>
                          </table>
                        </form>
                        <?
		} else {
			if($_POST['confirm_delete'] == "true") {
				if($_GET['dir'] != "") {
					rmdir("../".$_GET['dir']);
				}
				echo "Folder deleted.";
				$dirs = explode("/", $_GET['dir']);
				$link = "";
				for($i = 0; $i < count($dirs) - 1; $i++) {
					if($i != 0) {
						$link .= "/";
					}
					$link .= $dirs[$i];
				}
				echo "<script language=\"javascript\">
			function redir() {
				window.location = '".$_SERVER["PHP_SELF"]."?dir=".$link."';
			}
			setTimeout('redir()', 2000);
			</script>";
			} else {
				echo "Folder was not confirmed for deletion.";
				echo "<script language=\"javascript\">
			function redir() {
				window.location = '".$_SERVER["PHP_SELF"]."?dir=".$_GET['dir']."';
			}
			setTimeout('redir()', 2000);
			</script>";
			}
			
		}
	}
} else {
	echo "Error: Unable to read file! (Needs to be chmodded)";
}
?></tr>
                      <tr>
                        <td colspan="2" valign="top" bgcolor="#FFFFFF" height="1"></td>
                        </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="4" background="images/box_middle_right.png">&nbsp;</td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td><img src="images/box_bottom_left.png" width="17" height="4"></td>
        <td background="images/box_bottom_center.png"></td>
        <td><img src="images/box_bottom_right.png" width="17" height="4"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body></html>