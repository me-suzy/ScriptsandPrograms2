<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
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
|   > Browser für WYSIWYG
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: browser.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

$max_fsize = "2097152";

$base_css = $config['newsscripturl']."/templates/".$config['template_folder']."/style.css";

function fileHeaderXHTML() {
    global $lang, $config, $base_css, $_GET;
    echo "<?xml version=\"1.0\" encoding=\"".$lang['charset']."\"?>\n";
    echo $lang['doctype']."\n";
    echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"".$lang['dir']."\" lang=\"".$lang['lang']."\">\n";
    echo "<head>\n";
    echo "<title>".$lang['browser_image_browser']."</title>\n";
    if(!$_GET['library']) {
    echo "<script language=\"javascript\">\n";    
    echo "function OpenFile( fileUrl ) {\n";
    echo "window.top.opener.SetUrl( fileUrl ) ;\n";
    echo "window.top.close() ;\n";
    echo "window.top.opener.focus() ;\n";
    echo "}\n";
    echo "</script>\n";    
    }
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; ".$lang['charset']."\" />\n";
    echo "<link href=\"".$base_css."\" rel=\"stylesheet\" type=\"text/css\" />\n";
    echo "</head>\n";
    echo "<body bgcolor=\"".$config['body_background_color']."\">\n<br />";
}

function rebuildFileSizeBrowser($fsize) {
	global $config,$lang;
	$fsize = intval($fsize);
	$length = strlen($fsize);
	if($length <= 3) {
		$fsize = number_format($fsize,2,",",".");
		return $fsize." Bytes";
	} elseif($length >= 4 && $length <= 6) {
		$fsize = number_format($fsize/1024,2,",",".");
		return $fsize." kB";
	} elseif($length >= 7 && $length <= 9) {	
		$fsize = number_format($fsize/1048576,2,",",".");
		return $fsize." MB";
	} else {
		$fsize = number_format($fsize/1073741824,2,",",".");
		return $fsize." GB";
	}	
}		

if($_POST['action'] == 'upload' && $config['wysiwyg_user']) {
	$verz = "catgrafs";
	$gr_url = $config['newsscripturl']."/catgrafs";
	$message = "";
   	include_once($_ENGINE['eng_dir']."admin/enginelib/class.upload.php");
   	$my_upload = new upload();
    $my_upload->setAllowedExtensions("gif,jpg,jpeg,png,bmp");
    $my_upload->setMaxFileSize($max_fsize);
    $my_upload->setFilesDir($verz);
    if($my_upload->uploadFile("file")) {
        $message = $lang['browser_successfully_added'];
        $new_name = $my_upload->getDestName();
    } else {
        $message = $my_upload->getErrorCode();
    }	
	rideSite($sess->url('browser.php?type=images'), $message);
	exit();
} elseif($_POST['action'] == 'upload' && !$config['wysiwyg_user']) {
	rideSite($sess->url('browser.php?type=images'), $lang['browser_upload_error']);
	exit();
} 

switch ($_GET['type']) {
    case 'images':
        fileHeaderXHTML();
        $filesdir = $_ENGINE['eng_dir']."catgrafs";
        $extensions = array("gif","jpg","jpeg","png","bmp");
        
    	$handle=opendir($filesdir);
    	if($handle) {
    		while($file=readdir ($handle)) {
    			if($file!="." && $file!="..") $bildlist[]=$file;
    		}
    	} else {
    		die("Folder ".$filesdir." not found or not able to read, please check CHMOD settings (CHMOD 777 needed)!");
    	}
    	closedir($handle);
    	@sort($bildlist); 
        
        if(in_array('Thumbs.db',$bildlist)) {
            $position = array_search('Thumbs.db',$bildlist);
            array_splice($bildlist,$position,1);
        }
        
        if(in_array('index.html',$bildlist)) {
            $position2 = array_search('index.html',$bildlist);
            array_splice($bildlist,$position2,1);    
        }
           
        $over_all = count($bildlist);
        
    	include_once($_ENGINE['eng_dir']."admin/enginelib/class.nav.php");
    	if(!isset($_GET['start'])) {
            $start = 0;
        } else {
            $start = intval($_GET['start']);
            $bildlist = array_slice($bildlist,$_GET['start']);
        }
    	$nav = new Nav_Link();
    	$nav->overAll = $over_all;
    	$nav->perPage = 9;
        $nav->DisplayLast = 1;
        $nav->DisplayFirst = 1;    
    	$url_neu = $sess->url("browser.php?type=images")."&amp;";
    	$nav->MyLink = $url_neu;
    	$nav->LinkClass = "smalltext";
    	$nav->start = $start;
    	$pagecount = $nav->BuildLinks();
    	if(!$pagecount) $pagecount = "<b>1</b>";
        $pages = intval($over_all / $nav->perPage);
        if($over_all % $nav->perPage) $pages++;	
    
        $no = 1;
        echo "<div align=\"right\" class=\"page_step\">".$lang['browser_page']." (".$pages."): ".$pagecount."</div><br />";
    	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n<tr>\n<td bgcolor=\"#000000\">\n";
    	echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
    	echo "<tr>\n<td colspan=\"3\" class=\"list_headline\">&raquo;&nbsp;";
    	echo $lang['browser_media_browser'];
    	echo "\n</td>\n</tr>\n";            
        
        foreach($bildlist AS $file) {
            if($no == 1 || $no == 4 || $no == 7) $display_avatar .= "<tr>\n";
            $size = getimagesize($filesdir."/".$file);
            $datasize=filesize($filesdir."/".$file);
    
            $ins['size']=$size[0].'x'.$size[1].', '.rebuildFileSizeBrowser($datasize);
            
            //Resize Picture
            if($size[0]>120 && $size[0]>$size[1]) {
                $fr=$size[1]/$size[0];
                $size[0]=120;
                $size[1]=ceil(120*$fr);
            } elseif($size[1]>120) {
                $fr=$size[0]/$size[1];
                $size[0]=ceil(120*$fr);
                $size[1]=120;
            }   
            
            if(strlen($file)>20) {
                $ins['name']=substr($file,0,7)."[...]".substr($file,-7);
            } else {
                $ins['name']=$file;                     
            }     
            
            if($_GET['library'] == true) {
                $js_option = "opener.document.alp.".$_GET['field_name'].".value = '".$_ENGINE['main_url']."/catgrafs/".$file."'; window.top.close() ;";
            } else {
                $js_option = "OpenFile('".$_ENGINE['main_url']."/catgrafs/".$file."')";
            }
            $option = "<a href=\"javascript:".$js_option."\"><img src=\"".$_ENGINE['languageurl']."/btn_insert.gif\" border=\"0\" width=\"105\" height=\"22\" alt=\"".$lang['browser_insert_image']."\" title=\"".$lang['browser_insert_image']."\" /></a>&nbsp;";              
            $display_image .= "<td class=\"list_light\" valign=\"top\"><div align=\"center\"><b>".$ins['name']."</b><br><span class=\"menu\">(".$lang['browser_size']." ".$ins['size'].")</span><br><a href=\"".$_ENGINE['main_url']."/catgrafs/".$file."\" target=\"_blank\"><img src=\"".$_ENGINE['main_url']."/catgrafs/".$file."\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"".$file."\" title=\"".$file."\" border=\"0\" style=\"border:1px dashed #000000;margin:3px;\" /><br>".$option."</div></td>\n";
            if($no == 3 || $no == 6 || $no == 9) $display_image .= "</tr>\n";
            if($no == 9) break;
            $no++;
        }
        echo $display_image;
    	echo "</table>\n";
    	echo "</td>\n</tr>\n";
    	echo "</table><br />\n";
        echo "<div align=\"right\" class=\"page_step\">".$lang['browser_page']." (".$pages."): ".$pagecount."</div>";
        ?>
        <p>
        <form action="<?php echo $_ENGINE['main_url'] ?>/browser.php"  ENCTYPE="multipart/form-data" name="alp" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_fsize ?>" />
        <input type="hidden" name="action" value="upload" />
        <input type="hidden" name="<?php echo $sess->sess_name ?>" value="<?php echo $sess->sess_id ?>" />
        <table width="100%" border="0" cellspacing="2" cellpadding="0"> 
          <tr> 
            <td bgcolor="#000000"><table width="100%" border="0" cellspacing="1" cellpadding="0"> 
                <tr> 
                  <td class="list_headline" colspan="2"><?php echo $lang['browser_add_new_image_file'] ?></td> 
                </tr>
                <tr class="{media_css}"> 
                  <td class="list_light">
                  <p><?php echo $lang['browser_choose_file'] ?></p>
                  </td> 
                  <td class="list_light">
                  <input class="input" name="file" type="file" size="40" />             
                  </td>                              
                </tr>
              </table>
              </td> 
          </tr> 
        </table> 
        <div align="center">
        <input name="" type="submit" class="input" value=" <?php echo $lang['browser_upload'] ?> " />&nbsp;                      
        <input name="Reset" type="reset" class="input" value=" <?php echo $lang['browser_reset'] ?> " />
        </div>                                    
        </form>
        </p>        
        <?php    
           
        break;
}
?>
