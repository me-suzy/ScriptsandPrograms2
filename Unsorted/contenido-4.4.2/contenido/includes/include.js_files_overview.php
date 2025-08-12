<?php
/******************************************
* File      :   include.js_files_overview.php
* Project   :   Contenido
* Descr     :   Displays files in the
*               js directory of client
*
* Author    :   Olaf Niemann
* Created   :   20.04.2003
* Modified  :   20.04.2003
*
* © four for business AG
*****************************************/

$tpl->reset();

$path = $cfgClient[$client]["js"]["path"];
$tpl->set('s', 'SID', $sess->id);

$handle = opendir($path);

$aFiles = array();

if ($action == "js_delete")
{
    if (!strrchr($delfile, "/"))
    {

        if (file_exists($path.$delfile))
        {
            unlink($path.$delfile);
        }
    }

}

while ($file = readdir($handle))        {
        if( is_file($path.$file) ) {
            $aFiles[] = $file;
        }
}
closedir($handle);

// Die Dateien auflisten
if (is_array($aFiles)) {
    foreach ($aFiles as $filename) {

        $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];
        $tpl->set('d', 'BGCOLOR', $bgcolor);

        $html_filename = "<a class=\"action\" href=\"".$sess->url("main.php?area=$area&frame=4&file=$filename")."\" target=\"right_bottom\">".htmlspecialchars($filename)."</a>";
        $tpl->set('d', 'FILENAME', $html_filename);

        if ($perm->have_perm_area_action('js',"js_delete") ) { 
            $deletebutton = "<a onClick=\"event.cancelBubble=true;check=confirm('Script ".$filename ." wirklich löschen?'); if (check==true) { location.href='".$sess->url("main.php?area=js&action=js_delete&frame=$frame&delfile=$filename&del=")."#deletethis'};\" href=\"#\"><img src=\"".$cfg['path']['images']."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".$lngUpl["delfolder"]."\" title=\"".$lngUpl["deluser"]."\"></a>";
        } else {
            $deletebutton = "";
        }
        
        $delTitle = i18n("Delete JavaScript");
        $delDescr = sprintf(i18n("Do you really want to delete the following JavaScript source file:<br><br>%s<br>"),$filename);
        

        $tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteJS(\\\''.$filename.'\\\')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
        $tpl->next();

   }
}

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['js_files_overview']);

?>
