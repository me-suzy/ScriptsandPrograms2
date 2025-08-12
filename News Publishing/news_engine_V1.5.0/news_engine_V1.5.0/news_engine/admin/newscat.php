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
|   > Kategorien Setup Admin Center
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: newscat.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","newscat.php");

include_once('adminfunc.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
$auth->checkEnginePerm("canaccessadmincent");

$message = "";
$max_fsize = "2097152";

$folder = $config['catgrafurl'];
$filesdir = strrchr($folder,303);
$filesdir = substr($filesdir, 1); 
$filesdir = "./../".$filesdir;	
$extensions = "gif,jpg,jpeg,png,bmp";

if(isset ($action) && $action=='new') {
	$db_sql->sql_query("INSERT INTO $newscat_table (titel,cat_image,rss_activate)
				VALUES ('".addslashes(htmlspecialchars($titel))."','".addslashes($pic_name)."','".$rss_activate."')");	
	$message .= $a_lang['newscat_mes1'];
	unset ($catid);
	$step = "edit";	
}
	
if(isset ($action) && $action=='del') {
	$db_sql->sql_query("DELETE FROM $newscat_table WHERE catid='$catid'");	
	$message .= $a_lang['newscat_mes2'];
	unset ($catid);
	$step = "edit";	
}
	
if(isset ($action) && $action=='edit') {
	$db_sql->sql_query("UPDATE $newscat_table SET titel='".addslashes(htmlspecialchars($titel))."', cat_image='".addslashes($pic_name)."', rss_activate='".$rss_activate."' WHERE catid='$catid'");
	$message .= $a_lang['newscat_mes3'];
	unset ($catid);
	$step = "edit";
}

if($action == "thumb_upload") {
    if(!@is_writeable($filesdir)) {
        $message .= $a_lang['uploads_nopermission']."<br>";
    } else {
    	include_once($_ENGINE['eng_dir']."admin/enginelib/class.upload.php");
    	$my_upload = new upload();
    	
    	if($rename == 0) $my_upload->setChangeFilename(0);
    	$my_upload->setAllowedExtensions($extens);
		$my_upload->setMaxFileSize($max_fsize);
    	$my_upload->setFilesDir($filesdir);
    	if($my_upload->uploadFile("file")) {
    		$message = $a_lang['uploads_ok1'];
    		$new_name = $my_upload->getDestName();
    	} else {
    		$message = $my_upload->getErrorCode();
    		$action = "file_upload";
    	}	
    	$head_js = "
    	<script language=\"JavaScript\">
    	<!--	
    	function filedata2(data) { 
            opener.document.alp.pic_name.value += data+\" \";
    	    self.close(); 
    	} 	
    	//-->
    	</script>	
    	";
    }
}	
		
buildAdminHeader($head_js);

if ($message != "") buildMessageRow($message);
if ($head_js != '' && $new_name) buildTransferRow($a_lang['uploads_ok6'],$a_lang['uploads_ok7'].$new_name.$a_lang['uploads_ok8'],"filedata2",$new_name,$_FILES['file']['size']);

if($step == 'maincat' || $step == 'work') {
	buildHeaderRow($a_lang['afunc_31'],"newcat.gif");  
	if($step == 'work') {
		$cat = GetCatInfo($catid);
		buildFormHeader("newscat.php","post","edit");
		buildHiddenField("catid",$catid);	
	} else {
		buildFormHeader("newscat.php","post","new");
		buildHiddenField($name,$value="");		
	}
	buildTableHeader($a_lang['afunc_32']);
	buildInputRow($a_lang['afunc_33'], "titel", $cat['titel']);
	buildUploadInput($a_lang['afunc_34'], "pic_name", $cat['cat_image'], "40",0,"Uploadimage()");
	buildInputYesNo($a_lang['afunc_321'], "rss_activate", $cat['rss_activate']);
	buildFormFooter($a_lang['afunc_36'], "", 2, $a_lang['afunc_37']);
}

if($step == 'edit') {
	buildHeaderRow($a_lang['afunc_26']);  
	//buildTableHeader($a_lang['afunc_26'],3);
	buildTableDescription(array($a_lang['newscat_name'],$a_lang['newscat_picture_name'],$a_lang['newscat_options']),1);
	$no=1;
	$result = $db_sql->sql_query("SELECT * FROM $newscat_table");
	while($cat = $db_sql->fetch_array($result)) {		
		$cat = stripslashes_array($cat);
		if ($cat['cat_image'] == "") $cat['cat_image'] = "&nbsp;";	
        echo "<tr class=\"".switchBgColor()."\">\n";
        echo "<td>".$cat['titel']." <span class=\"smalltext\">(ID: <b>$cat[catid]</b>)</span></td>\n";
        echo "<td><span class=\"smalltext\">$cat[cat_image]</span></td>\n";
        echo "<td class=\"menuhead2\"> <a class=\"menu\" href=\"".$sess->adminUrl("newscat.php?step=work&catid=$cat[catid]")."\"><img src=\"images/edit.gif\" alt=\"$a_lang[afunc_292]\" align=\"absmiddle\" height=\"16\" width=\"16\" border=\"0\" vspace=\"2\">$a_lang[afunc_292]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class=\"menu\" href=\"".$sess->adminUrl("newscat.php?step=cat_del&catid=$cat[catid]")."\"><img src=\"images/delart.gif\" alt=\"$a_lang[afunc_293]\" align=\"absmiddle\" height=\"16\" width=\"16\" border=\"0\" vspace=\"2\">$a_lang[afunc_293]</a> </td>\n";
        echo "</tr>";		
		$no++;
	}	
	buildTableFooter("",3);
	buildExternalItems($a_lang['afunc_31'],"newscat.php?step=maincat","add.gif");
}

if($step == 'cat_del') {
    $result = $db_sql->sql_query("SELECT * FROM $newscat_table WHERE catid='$catid'");
    $del = $db_sql->fetch_array($result);  
    buildHeaderRow($a_lang['afunc_259'],"delart.gif",1,3);
    buildFormHeader("newscat.php","post","del"); 
    buildHiddenField("catid",$catid);
    buildTableHeader("$a_lang[afunc_259]: <u>$del[titel]");
    buildDarkColumn("$a_lang[newscat_del1] (ID: $catid) $a_lang[newscat_del2]",1,1,2);
    buildFormFooter($a_lang['afunc_61'], "", 2, $a_lang['afunc_62']);  
}  

if($step == 'thumb') {
    buildHeaderRow($a_lang['uploads_categupload'],"upload_file.gif");	
    buildInfo($a_lang['uploads_fileupload'],"$a_lang[uploads_note1] $max_fsize $a_lang[uploads_note2]");
    $head = $a_lang['uploads_h1'];  
    buildFormHeader("newscat.php", "post", "thumb_upload", "alp", 1);
    buildTableHeader($a_lang['uploads_new']);
    buildUploadRow("<b>$a_lang[uploads_search] $a_lang[uploads_h1]</b><br><span class=\"smalltext\">$a_lang[uploads_message]</span>", "file");
    buildRadioRow($a_lang['uploads_changename'], "rename");
    buildFormFooter($a_lang['uploads_button1'], $a_lang['uploads_reset'], 2);
    closeWindowRow();
}  

buildAdminFooter();
?>