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
|   > News-Funktionen Admin Center
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: news.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","news.php");

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

$display_preview = false;

function smilieTable() {
    global $config, $a_lang;;
    $smilie_table .= "<br><br><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n<tr>\n<td class=\"menuhead2\"><span class=\"smalltext\"><b>$a_lang[afunc_230]:</b></span><br></td></tr><tr>";
    $smilie_table .= "<td class=\"menuhead2\">";
  	$smilie_table .= "<a href=\"javascript:standard(':-)')\"><img src=\"$config[smilieurl]/smile.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(';-)')\"><img src=\"$config[smilieurl]/wink.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':O')\"><img src=\"$config[smilieurl]/wow.gif\" border=0></a><br>";
    $smilie_table .= "<a href=\"javascript:standard(';-(')\"><img src=\"$config[smilieurl]/sly.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':D')\"><img src=\"$config[smilieurl]/biggrin.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard('8-)')\"><img src=\"$config[smilieurl]/music.gif\" border=0></a><br>";
    $smilie_table .= "<a href=\"javascript:standard(':-O')\"><img src=\"$config[smilieurl]/cry.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':-(')\"><img src=\"$config[smilieurl]/confused.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard('(?)')\"><img src=\"$config[smilieurl]/sneaky2.gif\" border=0></a><br>";
    $smilie_table .= "<a href=\"javascript:standard('(!)')\"><img src=\"$config[smilieurl]/notify.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':!')\"><img src=\"$config[smilieurl]/thumbs-up.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':zzz:')\"><img src=\"$config[smilieurl]/sleepy.gif\" border=0></a><br>";
    $smilie_table .= "<a href=\"javascript:standard(':blush:')\"><img src=\"$config[smilieurl]/blush.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':inlove:')\"><img src=\"$config[smilieurl]/inlove.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':xmas:')\"><img src=\"$config[smilieurl]/xmas.gif\" border=0></a><br>";
    $smilie_table .= "<a href=\"javascript:standard(':baaa:')\"><img src=\"$config[smilieurl]/baaa.gif\" border=0></a>&nbsp;";
    $smilie_table .= "<a href=\"javascript:standard(':stupid:')\"><img src=\"$config[smilieurl]/withstupid.gif\" border=0></a>";
    $smilie_table .= "</td>\n</tr>\n</table>";
    if($config['click_smilies'] != "0") {
        return $smilie_table;
    } else {
        return;
    }
}

function HTMLLine() {
    global $a_lang,$_ENGINE;
    $html = "<script language=\"Javascript\" src=\"includes/bbcode/bbcode.js\"></script>";
    $html .= "<script language=\"Javascript\" src=\"includes/bbcode/bbcode_language$a_lang[afunc_258].js\"></script>";
    
    $html .= "<table cellspacing=\"2\" cellpadding=\"2\" border=\"0\">\n<tr>\n<td>";
    $html .= "<select class=\"listfield\" onchange=\"fontformat(this.form,this.options[this.selectedIndex].value,'size')\" onmouseover=\"stat('size')\">\n<option value=\"0\">SIZE</option>\n<option value=\"1\">$a_lang[afunc_235]</option>\n<option value=\"2\">$a_lang[afunc_236]</option>\n<option value=\"3\">$a_lang[afunc_237]</option>\n<option value=\"4\">$a_lang[afunc_238]</option>\n</select>";
    $html .= "<select class=\"listfield\" onchange=\"fontformat(this.form,this.options[this.selectedIndex].value,'font')\" onmouseover=\"stat('font')\"><option value=\"0\">FONT</option>\n<option value=\"arial\">Arial</option>\n<option value=\"comic sans ms\">Comic</option>\n<option value=\"courier\">Courier</option>\n<option value=\"courier new\">Courier New</option>\n<option value=\"tahoma\">Tahoma</option>\n<option value=\"times new roman\">Times New Roman</option>\n<option value=\"verdana\">Verdana</option>\n</select>";
    $html .= "<select class=\"listfield\" onchange=\"fontformat(this.form,this.options[this.selectedIndex].value,'color')\" onmouseover=\"stat('color')\"><option value=\"0\">COLOR</option><option value=\"#87CEEB\" style=\"color:skyblue\">sky blue</option>\n<option value=\"#4169E1\" style=\"color:royalblue\">royal blue</option>\n<option value=\"#0000FF\" style=\"color:blue\">blue</option>\n<option value=\"#00008B\" style=\"color:darkblue\">dark-blue</option>\n<option value=\"#FFA500\" style=\"color:orange\">orange</option>\n<option value=\"#FF4500\" style=\"color:orangered\">orange-red</option>\n";
    $html .= "<option value=\"#DC143C\" style=\"color:crimson\">crimson</option>\n<option value=\"#FF0000\" style=\"color:red\">red</option>\n<option value=\"#B22222\" style=\"color:firebrick\">firebrick</option>\n<option value=\"#8B0000\" style=\"color:darkred\">dark red</option>\n<option value=\"#00FF00\" style=\"color:green\">green</option>\n<option value=\"#32CD32\" style=\"color:limegreen\">limegreen</option>\n<option value=\"#2E8B57\" style=\"color:seagreen\">sea-green</option>\n";
    $html .= "<option value=\"#FF1493\" style=\"color:deeppink\">deeppink</option>\n<option value=\"#FF6347\" style=\"color:tomato\">tomato</option>\n<option value=\"#FF7F50\" style=\"color:coral\">coral</option>\n<option value=\"#800080\" style=\"color:purple\">purple</option>\n<option value=\"#4B0082\" style=\"color:indigo\">indigo</option>\n<option value=\"#DEB887\" style=\"color:burlywood\">burlywood</option>\n<option value=\"#F4A460\" style=\"color:sandybrown\">sandy brown</option>\n";
    $html .= "<option value=\"#A0522D\" style=\"color:sienna\">sienna</option>\n<option value=\"#D2691E\" style=\"color:chocolate\">chocolate</option>\n<option value=\"#008080\" style=\"color:teal\">teal</option>\n<option value=\"#C0C0C0\" style=\"color:silver\">silver</option>\n</select>";
    $html .= "</td>\n<td rowspan=\"3\">&nbsp;</td>\n<td rowspan=\"3\" valign=\"top\">";
    $html .= "\n<input class=\"button\" type=\"button\" value=\" x \" accesskey=\"c\" title=\"$a_lang[afunc_252] (alt+c)\" style=\"color:red; font-weight:bold\" onclick=\"closetag(this.form)\" onmouseover=\"stat('closecurrent')\"><span class=\"smalltext\">$a_lang[afunc_252]</span>\n<hr width=\"100%\">\n<input class=\"button\" type=\"button\" value=\" x \" accesskey=\"x\" title=\"$a_lang[afunc_253] (alt+x)\" style=\"color:red; font-weight:bold\" onclick=\"closeall(this.form)\" onmouseover=\"stat('closeall')\"><span class=\"smalltext\">$a_lang[afunc_253]</span>\n";
    $html .= "</td>\n</tr>\n<tr>\n<td>".htmlInputLine()."</td>\n</tr>\n<tr>";
    $html .= "<td><input type=\"text\" name=\"status\" style=\"width: 100%; font-size:9px; background-color: #D3D3D3; border: none\" value=\"$a_lang[afunc_254]\">\n</td>";
    $html .= "</tr>\n</table>";
    return $html;
}

function htmlInputLine() {
    global $a_lang;
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\" B \" onclick=\"bbcode(this.form,'B','')\" onmouseover=\"stat('b')\" title=\"BOLD (alt+b)\" accesskey=\"b\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\" I \" onclick=\"bbcode(this.form,'I','')\" onmouseover=\"stat('i')\" title=\"ITALIC (alt+i)\" accesskey=\"i\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\" U \" onclick=\"bbcode(this.form,'U','')\" onmouseover=\"stat('u')\" title=\"UNDERLINE (alt+u)\" accesskey=\"u\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"$a_lang[afunc_239]\" onclick=\"bbcode(this.form,'sup','')\" onmouseover=\"stat('sup')\" title=\"$a_lang[afunc_241]\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"$a_lang[afunc_240]\" onclick=\"bbcode(this.form,'sub','')\" onmouseover=\"stat('sub')\" title=\"$a_lang[afunc_242]\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"http://\" title=\"$a_lang[afunc_243]\" onclick=\"namedlink(this.form,'url')\" onmouseover=\"stat('url')\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"Email\" title=\"$a_lang[afunc_244]\" onclick=\"namedlink(this.form,'EMAIL')\" onmouseover=\"stat('email')\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"Code\" title=\"$a_lang[afunc_245]\" onclick=\"bbco(this.form,'code','')\" onmouseover=\"stat('code')\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"$a_lang[afunc_246]\" onclick=\"standard('[hr]')\" onmouseover=\"stat('hr')\" title=\"$a_lang[afunc_246]\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"$a_lang[afunc_247]\" title=\"$a_lang[afunc_248]\" accesskey=\"l\" onclick=\"dolist(this.form)\" onmouseover=\"stat('list')\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"$a_lang[afunc_249]\" title=\"$a_lang[afunc_250]\" onclick=\"bbco(this.form,'quote','')\" onmouseover=\"stat('quote')\">";
    $html .= "<input class=\"SBBbutton\" type=\"button\" value=\"IMG\" title=\"$a_lang[afunc_251]\" onclick=\"bbcode(this.form,'IMG','http://')\" onmouseover=\"stat('img')\">";
    return $html;            
}
         
function smallHTML($js_name) {
    global $config, $a_lang;
    $html_line = "
    <script language=\"JavaScript\" type=\"text/javascript\">
    <!--  
    tag_prompth = \"$a_lang[post_news_js1]\";
    
    link_text_prompth = \"$a_lang[post_news_js2]\";
    link_url_prompth = \"$a_lang[post_news_js3]\";
    link_email_prompth = \"$a_lang[post_news_js4]\";
    
    tags = new Array();
    function getarraysizeh(thearray) {
    for (i = 0; i < thearray.length; i++) {
    if ((thearray[i] == \"undefined\") || (thearray[i] == \"\") || (thearray[i] == null))
    	return i;
    }
    return thearray.length;
    }
    
    function arraypush(thearray,value) {
    thearraysize = getarraysizeh(thearray);
    thearray[thearraysize] = value;
    }
    
    function arraypop(thearray) {
    thearraysize = getarraysize(thearray);
    retval = thearray[thearraysize - 1];
    delete thearray[thearraysize - 1];
    return retval;
    }
    
    function bbcodeh(theform,bbcode,prompttext) {
    inserttext = prompt(tag_prompth+\" [\"+bbcode+\"]xxx[/\"+bbcode+\"]\",prompttext);
    if ((inserttext != null) && (inserttext != \"\")) {
    theform.$js_name.value += \"[\"+bbcode+\"]\"+inserttext+\"[/\"+bbcode+\"] \";
    theform.$js_name.focus();
    }
    }		
    
    function namedlinkh(theform,thetype) {
    linktext = prompt(link_text_prompth,\"\");
    var prompttext;
    if (thetype == \"URL\") {
    	prompt_text = link_url_prompth;
    	prompt_contents = \"http://\";
    } else {
    	prompt_text = link_email_prompth;
    	prompt_contents = \"\";
    }
    linkurl = prompt(prompt_text,prompt_contents);
    if ((linkurl != null) && (linkurl != \"\")) {
    	if ((linktext != null) && (linktext != \"\")) {
    		theform.$js_name.value += \"[\"+thetype+\"=\"+linkurl+\"]\"+linktext+\"[/\"+thetype+\"] \";
    	} else {
    		theform.$js_name.value += \"[\"+thetype+\"]\"+linkurl+\"[/\"+thetype+\"] \";
    	}
    }
    theform.$js_name.focus();
    }		
    
    //-->
    </script>";
    $html_line .= "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" width=\"100%\">\n<tr>\n<td>";
    $html_line .= "<input class=\"SBBbutton\" type=\"button\" name=\"button\" value=\" B \" onclick=\"bbcodeh(this.form,'B','')\">";
    $html_line .= "<input class=\"SBBbutton\" type=\"button\" name=\"button\" value=\" I \" onclick=\"bbcodeh(this.form,'I','')\">";
    $html_line .= "<input class=\"SBBbutton\" type=\"button\" name=\"button\" value=\" U \" onclick=\"bbcodeh(this.form,'U','')\">";
    $html_line .= "<input class=\"SBBbutton\" type=\"button\" name=\"button\" value=\" IMG \" onclick=\"bbcodeh(this.form,'IMG','http://')\">";
    $html_line .= "<input class=\"SBBbutton\" type=\"button\" name=\"button\" value=\" http:// \" onclick=\"namedlinkh(this.form,'URL')\">";
    $html_line .= "<input class=\"SBBbutton\" type=\"button\" name=\"button\" value=\" Quote \" onclick=\"bbcodeh(this.form,'QUOTE','')\">";
    $html_line .= "</td>\n</tr>\n</table>";
    return $html_line;
}   

if(isset($action) && $action=='edit' && $preview=='') {
	$timestamp = ($newsdate != "") ? "UNIX_TIMESTAMP('".trim($newsdate)."')" : time();
	
	if($news_enddate == "" || $news_enddate == 0) {
		$timestamp_end = "NULL";
	} else {
		$timestamp_end = "UNIX_TIMESTAMP('".trim($news_enddate)."')";
	}
    
    /*if($config['wysiwyg_admin']) {   
        include_once($_ENGINE['eng_dir']."admin/enginelib/function.wysiwyg.php");
        $hometext = turnWysiwygIntoBbcode($hometext);		
        $newstext = turnWysiwygIntoBbcode($newstext);		            
    }*/    
	
	$db_sql->sql_query("UPDATE $news_table SET
				headline='".addslashes($headline)."',
				hometext='".addslashes($hometext)."',
				newstext='".addslashes($newstext)."',
				catid='$catid',
				published='$published',
				comments_allowed='$comments_allowed',
				newsdate=".$timestamp.",
				news_enddate=".$timestamp_end.",
				pic_n = '$pic_n',
				pic_name = '".addslashes($pic_name)."',
				img_align = '".addslashes($img_align)."',
				news_links = '$news_links',
                is_html = '".intval($is_html)."'
				WHERE newsid='$newsid'");   
	$message .= $a_lang['news_mes2'];
	$step = "choose";
}
   
if(isset($action) && $action=='newsadd' && $preview=='') {
	$timestamp = ($newsdate != "") ? "UNIX_TIMESTAMP('".trim($newsdate)."')" : "'".time()."'";
	
	if($news_enddate == "" || $news_enddate == 0) {
		$timestamp_end = "NULL";
	} else {
		$timestamp_end = "UNIX_TIMESTAMP('".trim($news_enddate)."')";
	}		 
    
    /*if($config['wysiwyg_admin']) {   
        include_once($_ENGINE['eng_dir']."admin/enginelib/function.wysiwyg.php");
        $hometext = turnWysiwygIntoBbcode($hometext);		
        $newstext = turnWysiwygIntoBbcode($newstext);		            
    }*/    
    
	$db_sql->sql_query("INSERT INTO $news_table (headline,hometext,newstext,catid,published,comments_allowed,newsdate,news_enddate,pic_n,pic_name,img_align,userid,news_links,is_html)
				VALUES ('".addslashes(htmlspecialchars($headline))."','".addslashes($hometext)."','".addslashes($newstext)."','$catid','$published','$comments_allowed',$timestamp,$timestamp_end,'$pic_n','".addslashes($pic_name)."','".addslashes($img_align)."','".addslashes(htmlspecialchars($poster))."','$news_links','$is_html')");
	$newsid = $db_sql->insert_id();   
   
	if($newsid) {
		$message .= $a_lang['news_mes4']."<br>".$a_lang['news_click']." <a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=linkadd&newsid=".$newsid)."\">".$a_lang['news_here']."</a> ".$a_lang['news_addlink'];
	} else {
		$message .= $a_lang['news_mes8'];
	}
	unset($newsid);
    $step = "choose";
}     

if(($action=='newsadd' || $action=='edit') && $preview!='') {
    $step = "preview";
    $display_preview = true;

} 
   
if(isset($action) && $action=='del') {
	$db_sql->sql_query("DELETE FROM $news_table WHERE newsid='$newsid'");
	$db_sql->sql_query("DELETE FROM $newscomment_table WHERE newsid='$newsid'");   
	$message .= $a_lang['news_mes3'];
}

if(isset($action) && $action=='conf_news_multi') {
    if($_POST['public']) {
    	foreach($newsid as $key=>$wert) {
    		$db_sql->sql_query("UPDATE $news_table SET published='1' WHERE newsid='$key'");
    	}	
        $message .= $a_lang['news_mes5'];        
    } elseif($_POST['public']) {
    	foreach($newsid as $key=>$wert) {
            $db_sql->sql_query("DELETE FROM $news_table WHERE newsid='$key'");
    	}	
        $message .= $a_lang['news_mes5'];      
    }
    $step = 'cat';
}
   
if(isset($action) && $action=='linkedit') {
	$db_sql->sql_query("UPDATE $newslinks_table SET link_url='".addslashes(htmlspecialchars($link_url))."', link_name='".addslashes(htmlspecialchars($link_name))."', link_target='".$link_target."' WHERE linkid='".$linkid."'");   
	$message .= $a_lang['news_mes1'];
	$step = "linksedit";
}      
   
if(isset($action) && $action=='linkdel') {
	$db_sql->sql_query("DELETE FROM $newslinks_table WHERE linkid='".$linkid."'");   
	$message .= $a_lang['news_mes6'];
	$step = "linksedit";
}   
   
if(isset($action) && $action=='linkadd') {
	$db_sql->sql_query("INSERT INTO $newslinks_table (newsid,link_url,link_name,link_target)
				VALUES ('$newsid','".addslashes(htmlspecialchars($link_url))."','".addslashes(htmlspecialchars($link_name))."','".$link_target."')");
	$message .= $a_lang['news_mes7'];
	$step = "linksedit";
} 

if(isset($action) && $action=='delete_catgraf') {
    if(unlink($_ENGINE['eng_dir'].'/catgrafs/'.$_GET['file'])) {
        $message .= $a_lang['news_pic_success_deleted'];
    } else {
        $message .= $a_lang['news_pic_not_deleted'];
    }
    $step = "catgraf";
}
   
if(isset($action) && $action=="catgraf_upload") {
    if(!@is_writeable($filesdir)) {
        $message .= $a_lang['uploads_nopermission']."<br>";
    } else {
    	include_once($_ENGINE['eng_dir']."admin/enginelib/class.upload.php");
    	$my_upload = new upload();
    	
    	if($rename == 0) {	
			$my_upload->setChangeFilename(false);
		} else {
			$my_upload->setChangeFilename(true);
		}
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
            opener.document.alp.pic_name.value = data;
            opener.document.alp.pic_n.value = 1;
    	    self.close(); 
    	} 	
    	//-->
    	</script>	
    	";
    }
}

if($step == 'catgraf') {
	$head_js = "
	<script language=\"JavaScript\">
	<!--	
	function filedata2(data) { 
        opener.document.alp.pic_name.value = data;
        opener.document.alp.pic_n.value = 1;
	    self.close(); 
	} 	
	//-->
	</script>	
	";
}
        
if(!$config['wysiwyg_admin']) $sbb_style = $head_js;
$sbb_style .= $head_js."<style>\n.SBBbutton {\nfont-family : Verdana, Arial, sans-serif;\nbackground-color: #4665B5;\ncolor: White;\nfont-size: 10px;\nfont-weight: bold;\n}\n.news_textarea {\nwidth: 100%;\n}</style>\n";

if($step != "display_preview") {
    if($action == "catgraf_upload" || $step == "catgraf") {
    	buildAdminHeader($sbb_style,1);
    } else {
    	buildAdminHeader($sbb_style);
    }
}

if ($message != "") buildMessageRow($message);
if ($head_js != '' && $new_name) buildTransferRow($a_lang['uploads_ok6'],$a_lang['uploads_ok7'].$new_name.$a_lang['uploads_ok8'],"filedata2",$new_name,$_FILES['file']['size']);

if($step == 'display_preview') {
    include_once($_ENGINE['eng_dir']."admin/enginelib/class.template.php");
    $tpl = new engineTemplate($_ENGINE['eng_dir']."templates/".$config['template_folder']."/");

    initStandardVars();
    $tpl->loadFile('header', 'header.html'); 
    $tpl->loadFile('footer', 'footer.html');     

    $cat = $db_sql->query_array("SELECT * FROM $newscat_table WHERE catid='$catid'");
    $cat = stripslashes_array($cat);

    include_once($_ENGINE['eng_dir']."lang/".$config['language']."/".$config['language'].".php");
    $tpl->loadFile('main', 'news.html'); 
    $tpl->register('title', $newshead);
    
    $xhtml_body = "
    <?xml version=\"1.0\" encoding=\"".$lang['charset']."\"?>
    ".$lang['doctype']."
    <html xmlns=\"http://www.w3.org/1999/xhtml\">
    <head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; ".$lang['charset']."\" />
    <link href=\"".$config['newsscripturl']."/templates/".$config['template_folder']."/style.css\" rel=\"stylesheet\" type=\"text/css\" />
    </head>
    <body bgcolor=\"".$config['body_background_color']."\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">";
    
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => $sess->url('index.php'), urldecode($headline) => '')));
    $tpl->register('header', $xhtml_body);   
    
    if ($config['cat_pics'] == "1" ) {
        if ($news['pic_n'] == 0) {
            if($news['cat_image'] != "") $news_pic = "<img src=\"$config[catgrafurl]/".$news['cat_image']."\" border=\"0\" align=\"middle\">&nbsp;&nbsp;";
        }
        if ($news['pic_n'] == 1) $news_pic = "<img src=\"$config[catgrafurl]/".$pic_name."\" border=\"0\" align=\"middle\">&nbsp;&nbsp;";
        if ($news['pic_n'] == 2) $news_pic = "";
    }
    
    if($news['img_align']=="right" || $news['img_align']=="") {
    	$image_align_right = "<td valign=\"top\" align=\"middle\" width=\"96\" height=\"59\">".$news_pic."</td>";
    } elseif($news['img_align']=="left") {
    	$image_align_left = "<td valign=\"top\" align=\"middle\" width=\"96\" height=\"59\">".$news_pic."</td>";	
    } else {
    	$image_top = $news_pic;
    }    
    
    
    $news['postname'] = trim($auth->user['username']);
    $postname = trim($news['postname']); 
    
	$profile_link = definedBoardUrls("memberdetail",$auth->user['userid']);
	$parse_profile_link = true; 
    $tpl->parseIf('main', 'parse_profile_link');	
    
    if(trim($auth->user['userhp'])) {
    	$homepage_link = trim($auth->user['userhp']);
    	$parse_homepage_link = true;
    }	
    $tpl->parseIf('main', 'parse_homepage_link');	
    
    if(trim($auth->user['useremail'])) {	
    	$parse_email_link = true;
    }	
    $tpl->parseIf('main', 'parse_email_link'); 
    
    if($parse_profile_link || $parse_homepage_link || $parse_email_link) $author_information_block = true;
    $tpl->parseIf('main', 'author_information_block');			   
    
    $fulldate = $lang['php_news_at']." ".aseDate($config['shortdate'],$news['newsdate'],1)." ".$lang['php_last_visit2']." ".aseDate($config['timeformat'],$news['newsdate'],1)." ".$lang['php_news_time'];
    
    $hometext = $sess->getSessVar('hometext');
    $newstext = $sess->getSessVar('newstext');
    $hometext = urldecode($hometext);
    $newstext = urldecode($newstext);
    
    $newshead = urldecode($headline);
    
    include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
    $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);	
    
    
    if ($hometext != "" && $is_html == 0) {
        $hometext = $bbcode->rebuildText($hometext);		
        $hometext = trim($hometext);
    }
    
    if ($newstext != "" && $is_html == 0) {
        $newstext = $bbcode->rebuildText($newstext);		
        $newstext = trim($newstext);    
    }
    
    $count_reads = "&nbsp;&nbsp;".$lang['php_reads1']." 0x ".$lang['php_reads2'];

    $day = date("D", time());
		
    switch($day) {
        case Tue;
            $day = $lang['php_tu'];
            break;
        case Wed;
            $day = $lang['php_we'];
            break;
        case Thu;
            $day = $lang['php_th'];
            break;
        case Fri;
            $day = $lang['php_fr'];
            break;
        case Sat;
            $day = $lang['php_sa'];
            break;
        case Sun;
            $day = $lang['php_su'];
            break;
        default;
            $day = $lang['php_mo'];
            break;
    }
    
    if($config['newsdate'] == 1) {
        $fulldate = aseDate($config['shortdate'],time());
    } elseif($config['newsdate'] == 2) {
        $fulldate = aseDate($config['shortdate'],time())." - ".aseDate($config['timeformat'],time());
    } elseif($config['newsdate'] == 3) {
        $fulldate = $day.", ".aseDate($config['shortdate'],time());
    } else {
        $fulldate = $day.", ".aseDate($config['shortdate'],time())." - ".aseDate($config['timeformat'],time());
    }

    if($config['activate_recommendation']) {
    	$parse_recommend_link = true;
    }
    $tpl->parseIf('main', 'parse_recommend_link');	
    	
    if($news['comments_allowed']) {
    	$parse_comment_link = true;
    	$comment_count = relatedComments($news['comments_allowed'],$news['newsid']);
    }	
    $tpl->parseIf('main', 'parse_comment_link');	
    
    $print_link = "<a href=\"JavaScript:Print()\"><img src=\"".$config['grafurl']."/img_print.gif\" border=\"0\" align=\"absmiddle\" />News drucken</a><br />";
    
    
    if($config['categorie_before_headline']) $newshead = $config['start_category_html'].trim($cat['titel']).$config['end_category_html']."&nbsp;".$newshead;
    
    if(relatedLinks($news['newsid'])) {
    	$related_links_block = true;	
    	$tpl->parseLoop('main', 'links_loop');	
    } else {
    	$related_links_block = false;	
    }
    
    $tpl->parseIf('main', 'related_links_block');	   
    
    $tpl->register(array('news_headline' => $newshead,
    					'news_home_text' => trim($hometext),
    					'news_text' => trim($newstext),
    					'recommend_link' => $recommend_link,
    					'profile_link' => $profile_link,
    					'homepage_link' => $homepage_link,
    					'print_news_link' => $print_link,
    					'commentlink_link' => $commentlink_link,
    					'related_links' => $related_links,
    					'news_userid' => $auth->user['userid'],
    					'newsid' => '0',
    					'number_of_comments' => $comment_count,
    					'news_profile_of' => sprintf($lang['news_profile_of'],trim($news['postname'])),
    					'news_homepage_of' => sprintf($lang['news_homepage_of'],trim($news['postname'])),
    					'news_email_to' => sprintf($lang['news_email_to'],trim($news['postname'])),
    					'news_send_news_by_mail' => $lang['news_send_news_by_mail'],
    					'news_comments' => $lang['news_comments'],
    					'news_print_news' => $lang['news_print_news'],
    					'news_author' => $lang['news_author'],
    					'news_options' => $lang['news_options'],
    					'news_date' => $fulldate,
    					'news_more_links' => $lang['news_more_links']));
    $tpl->pprint('main');
}

if($step == 'post' || $step == 'edit' || $step == 'preview') {
    if($step == 'edit') {
        $result = $db_sql->sql_query("SELECT *, FROM_UNIXTIME(newsdate) AS newsdate, FROM_UNIXTIME(news_enddate) AS news_enddate FROM $news_table WHERE newsid='$newsid'");
        $news = stripslashes_array($db_sql->fetch_array($result));
        $action = "edit";
    } elseif($step == 'preview') {
        $news = array();
        $news = $_POST;
        buildHeaderRow("Vorschau","preview.gif");
        /*if($config['wysiwyg_admin']) {   
            include_once($_ENGINE['eng_dir']."admin/enginelib/function.wysiwyg.php");
            $news['hometext'] = turnWysiwygIntoBbcode($news['hometext']);		
            $news['newstext'] = turnWysiwygIntoBbcode($news['newstext']);		            
        }*/      
        $headline = urlencode($news['headline']);
        $hometext = urlencode($news['hometext']);
        $newstext = urlencode($news['newstext']);
        
        $sess->setSessVar('hometext', $hometext);
        $sess->setSessVar('newstext', $newstext);
        $step = $news['step'];
        
        echo "
        <iframe src=\"".$sess->adminUrl('news.php?step=display_preview&headline='.$headline.'&catid='.$news['catid'].'&img_align='.$news['img_align'].'&comments_allowed='.$news['comments_allowed'].'&pic_n='.$news['pic_n'].'&pic_name='.$news['pic_name'].'&is_html='.$news['is_html'])."\" width=\"100%\" height=\"250\" align=\"middle\" frameborder=\"1\"></iframe>
        ";
    } else {
        $action = "newsadd";
        // PRESETTINGS:
        $news['img_align'] = "right"; // right, left or ""
        $news['comments_allowed'] = 0; // 1=Yes; 0=No
        $news['published'] = 1; // 1=Yes; 0=No
        $news['news_links'] = 1; // 1=Yes; 0=No
        // PRESETTINGS END        
    }

    buildHeaderRow($a_lang['afunc_219'],"newdet.gif");
	if($step == 'edit') buildExternalItems(array($a_lang['afunc_131'],$a_lang['afunc_132'],$a_lang['afunc_320']),array("news.php?step=linksedit&newsid=".$news['newsid'],"news.php?step=del&newsid=".$news['newsid'],"news.php?step=choose&catid=".$news['catid']),array("links.gif","delart.gif","newdet.gif"));  
    buildFormHeader("news.php","post",$action,"alp");
    buildHiddenField("poster",$auth->user['userid']);
    if($step == 'edit') buildHiddenField("newsid",$news['newsid']);
    buildHiddenField("step",$step);
    buildTableHeader($a_lang['afunc_220']);
    buildInputRow($a_lang['afunc_221'], "headline", $news['headline']);
    if($step == 'edit' || $step == 'preview') {
        $cat_row = "<select class=\"input\" name=\"catid\">".GetActCat($news['catid'])."</select>";
    } else {
        $cat_row = "<select class=\"input\" name=\"catid\">".GetCateg()."</select>";
    }
    buildStandardRow($a_lang['afunc_222'], $cat_row);
    if($step == 'edit' || $step == 'preview') {
        $pic_use = "<select class=\"input\" name=\"pic_n\">\n";
		$pic_use .= "<option value=\"0\" ";
		$pic_use .= (!$news['pic_n']) ? "selected" : "";
		$pic_use .= ">$a_lang[afunc_224]</option>\n";
		$pic_use .= "<option value=\"1\" ";
		$pic_use .= ($news['pic_n'] == "1") ? "selected" : "";
		$pic_use .= ">$a_lang[afunc_225]</option>\n";
		$pic_use .= "<option value=\"2\" ";
		$pic_use .= ($news['pic_n'] == "2") ? "selected" : "";
		$pic_use .= ">$a_lang[afunc_226]</option>\n";
		$pic_use .= "</select>";
    } else {
        $pic_use = GetNewsPic($news['pic_n']);
    }
	
	if($news['img_align'] == '') $news['img_align'] = "right";
	$pic_align = "<select class=\"input\" name=\"img_align\" onchange=\"previewImage( 'img_align', 'preview_image', 'images/' )\">\n";
	$pic_align .= "<option value=\"right\" ";
	$pic_align .= ($news['img_align'] == "right") ? "selected" : "";
	$pic_align .= ">".$a_lang['pic_right_of_news']."</option>\n";
	$pic_align .= "<option value=\"left\" ";
	$pic_align .= ($news['img_align'] == "left") ? "selected" : "";
	$pic_align .= ">".$a_lang['pic_left_of_news']."</option>\n";
	$pic_align .= "<option value=\"top\" ";
	$pic_align .= ($news['img_align'] != "left" && $news['img_align'] != "right") ? "selected" : "";
	$pic_align .= ">".$a_lang['pic_in_front_of_news']."</option>\n";
	$pic_align .= "</select>";
	
	$pic_info = "<table width=\"100%\" cellspacing=\"5\" cellpadding=\"0\" border=\"0\"><tr><td>".$pic_use."</td>\n";
	$pic_info .= "<td rowspan=\"2\" width=\"100%\" valign=\"bottom\"><img name=\"preview_image\" src=\"images/".$news['img_align'].".gif\" width=\"60\" height=\"30\" /></td>\n</tr>\n<tr>\n<td>".$pic_align."</td>";
	$pic_info .= "</tr>\n</table>";
	
	
    buildStandardRow($a_lang['afunc_223'], $pic_info);
    buildUploadInput($a_lang['afunc_227'], "pic_name", $news['pic_name'], "40",0,"UploadNewsimage()");
	
    buildTableSeparator($a_lang['afunc_140']);        
    if(!$config['wysiwyg_admin'] && !$news['is_html']) {
        buildHiddenField("is_html",0);
        echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".smallHTML("hometext")."<textarea class=\"news_textarea\" name=\"hometext\" rows=\"8\" cols=\"70\" wrap=\"soft\">".stripslashes($news[hometext])."</textarea></p></td>\n</tr>\n";    
    	echo "<tr>\n<td colspan=\"2\" class=\"menu_desc\">";
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n<tr>\n<td><p style=\"color : White; font-size : 11px; font-weight : bold;\">&raquo;&nbsp;".$a_lang['afunc_141']."</p></td>\n<td style=\"color : White; font-size : 11px; font-weight : bold;\">";
        echo "<input class=\"button\" type=\"radio\" name=\"mode\" value=\"0\" title=\"Normal Mode: (alt+n)\" accesskey=\"n\" onclick=\"setmode(this.value)\" onmouseover=\"stat('norm')\" checked><span class=\"smalltext\">$a_lang[afunc_255]</span>\n<br>\n";
        echo "<input class=\"button\" type=\"radio\" name=\"mode\" value=\"1\" title=\"Normal Mode: (alt+e)\" accesskey=\"e\" onclick=\"setmode(this.value)\" onmouseover=\"stat('enha')\"><span class=\"smalltext\">$a_lang[afunc_256]</span>\n</td>";    
        echo "</td>\n</tr>\n</table>";    
        echo "\n</td>\n</tr>\n";       
    	echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>";
        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n<tr>\n<td align=\"left\">".HTMLLine()."</td>\n</tr>\n</table>";
        echo "<textarea class=\"news_textarea\" name=\"newstext\" rows=\"20\" cols=\"70\" wrap=\"soft\" onChange=getActiveText(this) onclick=getActiveText(this) onFocus=getActiveText(this)>".stripslashes($news[newstext])."</textarea>";
        echo "</p></td>\n</tr>\n";    
    } else {
        buildHiddenField("is_html",1);
        /*if(!class_exists(engineBBCode)) include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
        $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']); */
        
        if($news['is_html'] != 1) {
            if(!class_exists(engineBBCode)) include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
            $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);
            if($news['hometext']) $news['hometext'] = $bbcode->rebuildText($news['hometext']);     
            if($news['newstext']) $news['newstext'] = $bbcode->rebuildText($news['newstext']);
        }
        
        include_once($_ENGINE['eng_dir']."admin/enginelib/class.fckeditor.php") ;   
        $oFCKeditor = new FCKeditor('hometext') ;
        $oFCKeditor->BasePath = $_ENGINE['main_url']."/admin/includes/FCKeditor/";
        $oFCKeditor->Value = $news['hometext'];
        $oFCKeditor->ToolbarSet = 'Basic';
        $oFCKeditor->Height = "100";
        $small_editor .= $oFCKeditor->CreateHtml();  
        echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$small_editor."</p></td>\n</tr>\n";      
        
        /*include_once('enginelib/class.fckeditor.php') ;   
        $oFCKeditor = new FCKeditor('hometext') ;
        $oFCKeditor->BasePath = $config['newsscripturl'].'/admin/includes/FCKeditor/';
        $oFCKeditor->Value = $bbcode->rebuildText($news['hometext']);
        $oFCKeditor->ToolbarSet = 'Basic';
        $small_editor = $oFCKeditor->CreateHtml() ;        
        echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$small_editor."</p></td>\n</tr>\n"; */
   
    	/*include_once('includes/spaw/spaw_control.class.php');
    	$sw2 = new SPAW_Wysiwyg('hometext',$bbcode->rebuildText($news['hometext']),$lang['php_mailer_lang'], 'mini2', '','100%', '100px', "../templates/".$config['template_folder'].'/style.css');	        
    	$small_editor = $sw2->show();    
        echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$small_editor."</p></td>\n</tr>\n";*/
        
        $oFCKeditor2 = new FCKeditor('newstext') ;
        $oFCKeditor2->BasePath = $_ENGINE['main_url']."/admin/includes/FCKeditor/";
        $oFCKeditor2->Value = $news['newstext'];
        $oFCKeditor2->Height = "350";
        $editor .= $oFCKeditor2->CreateHtml();  
        echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$editor."</p></td>\n</tr>\n";        
		
        /*$oFCKeditor2 = new FCKeditor('newstext') ;
        $oFCKeditor2->BasePath = $config['newsscripturl'].'/admin/includes/FCKeditor/';
        $oFCKeditor2->Value = $bbcode->rebuildText($news['newstext']);
        $oFCKeditor2->ToolbarSet = 'Engine';
        $editor = $oFCKeditor2->CreateHtml() ;	
		echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$editor."</p></td>\n</tr>\n";*/	
    	
        /*$sw = new SPAW_Wysiwyg('newstext',$bbcode->rebuildText($news['newstext']),$lang['php_mailer_lang'], 'engine', '','100%', '200px', "../templates/".$config['template_folder'].'/style.css');	        
    	$editor = $sw->show();
        buildTableSeparator($a_lang['afunc_141']);
        echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$editor."</p></td>\n</tr>\n";*/
    
    }
    buildTableSeparator($a_lang['afunc_289']);
	
	include_once($_ENGINE['eng_dir']."admin/enginelib/class.calendar.php");
	$calendar = new DHTML_Calendar($config['newsscripturl'].'/admin/includes/calendar/', $lang['php_mailer_lang']);
	$calendar->load_files();
	$calendar->make_input_field(
	           array('firstDay'=>1,'showsTime'=>true,'showOthers'=>true,'ifFormat'=>'%Y-%m-%d %H:%M:%S','timeFormat'=> '24'),
	           array('size'=>'40','name'=>'newsdate','value'=>$news['newsdate']),
			   $a_lang['afunc_288']);
	$calendar->make_input_field(
	           array('firstDay'=>1,'showsTime'=>true,'showOthers'=>true,'ifFormat'=>'%Y-%m-%d %H:%M:%S','timeFormat'=> '24'),
	           array('size'=>'40','name'=>'news_enddate','value'=>$news['news_enddate']),
			   $a_lang['afunc_290']);		
			   	
    buildTableSeparator($a_lang['afunc_142']);
    buildInputYesNo($a_lang['afunc_144'], "comments_allowed", $news['comments_allowed']);
    buildInputYesNo($a_lang['afunc_143'], "published", ($news['published']=="") ? "1" : $news['published']);
    buildTableSeparator($a_lang['afunc_145']);
    buildInputYesNo($a_lang['afunc_146'], "news_links", $news['news_links']);
    if($step != 'edit') buildStandardRow($a_lang['afunc_231'], "<span class=\"smalltext\">$a_lang[afunc_232]</span>");
    //buildFormFooter($a_lang['afunc_233'], $a_lang['afunc_234']);
	echo "<tr class=\"table_footer\">\n<td colspan=\"2\" align=\"center\">\n&nbsp;";
	echo "<input type=\"submit\" value=\"   ".$a_lang['afunc_233']."   \" class=\"button\">\n";	
    echo "<input type=\"submit\" name=\"preview\" value=\"   Vorschau   \" class=\"button\">\n";	
	echo "<input type=\"reset\" value=\"   ".$a_lang['afunc_234']."   \" class=\"button\">\n";	
	echo "&nbsp;\n</td>\n</tr>\n</table>\n";
	echo "</td>\n</tr>\n</table>\n";
	echo "</form><br />\n";      
}    

if($step == 'cat') {
    buildHeaderRow($a_lang['afunc_111'],"newdet.gif");
    buildTableHeader($a_lang['afunc_112']);
    $result = $db_sql->sql_query("SELECT * FROM $newscat_table");
    while($cat = $db_sql->fetch_array($result)) {
        $cat = stripslashes_array($cat);
        $result3 = $db_sql->sql_query("SELECT newsid FROM $news_table WHERE catid='".$cat['catid']."'");
        $no = mysql_num_rows($result3);  
              
        if ($no == "0") {
            $link = "&nbsp;";
        } else {
            $link = "<a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=choose&catid=".$cat['catid'])."\"><img src=\"images/edit.gif\" alt=\"".$a_lang['afunc_176']."\" width\"16\" height=\"16\" border=\"0\" align=\"absmiddle\">$a_lang[afunc_113]</a>";
        }
        buildStandardRow($cat['titel']." <span class=\"smalltext\">($a_lang[afunc_1]: ".$cat['catid'].")</span>",$link);
    }
    buildTableFooter();   
}
  
if($step == 'down') {
    buildHeaderRow($a_lang['news_search_f'],"search.gif");
    buildFormHeader("news.php", "post", "");
    buildHiddenField("step","choose");
    buildHiddenField("search_query","1");
    buildTableHeader($a_lang['news_inserthead']);
    $tmp_value = "<select name=\"search_col\">\n";
    $tmp_value .= "<option value=\"title\">".$a_lang['search_in_headline']."</option>\n";
    $tmp_value .= "<option value=\"hometext\">".$a_lang['search_in_newstext']."</option>\n";
    $tmp_value .= "</select>\n";
    
    buildStandardRow($a_lang['search_define'], $tmp_value);
    buildInputRow($a_lang['search_note1'], "search_word");
    buildFormFooter($a_lang['search_button1'], $a_lang['adminutil_19']);     
}
  
if($step == 'choose') {
    if($search_col && $search_word) {
        if($search_col == "title") {
            $sql = "WHERE headline LIKE '%$search_word%'";
        } else {
            $sql = "WHERE hometext LIKE '%$search_word%' OR newstext LIKE '%$search_word%'";
        }        
        $query = "SELECT Count(*) as total FROM $news_table ".$sql."";
        $query_long = "SELECT * FROM $news_table ".$sql." ORDER BY newsdate DESC";
        $url_neu = $sess->adminUrl("news.php?step=choose&search_word=".$search_word."&search_col=".$search_col."&catid=".$catid)."&";
    } else {
        $query = "SELECT Count(*) as total FROM $news_table WHERE catid='$catid'";
        $query_long = "SELECT * FROM $news_table WHERE catid='$catid' ORDER BY newsdate DESC";
        $url_neu = $sess->adminUrl("news.php?step=choose&catid=".$catid)."&";
        
        $cat = $db_sql->query_array("SELECT titel FROM $newscat_table WHERE catid='".$catid."'");
        $add_headline = sprintf($a_lang['news_in_category'],$cat['titel']);
    }    


    
    $result = $db_sql->sql_query("".$query."");
    $over_all = $db_sql->fetch_array($result);    
    
    if(!class_exists(Nav_Link)) include_once($_ENGINE['eng_dir']."admin/enginelib/class.nav.php");
    if(!isset($start)) $start = 0;
    $nav = new Nav_Link();
    $nav->overAll = $over_all['total'];
    $nav->DisplayLast = 1;
    $nav->DisplayFirst = 1;
    $nav->perPage = 10;
    $nav->MyLink = $url_neu;
    $nav->LinkClass = "smalltext";
    $nav->start = $start;
    $pagecount = $nav->BuildLinks();
    $pages = intval($over_all['total'] / 10);
    if($over_all['total'] % 10) $pages++;				
    
    if(!$pagecount) $pagecount = "<b>1</b>";  
    
    buildHeaderRow($a_lang['afunc_114']." ".$add_headline.":<br><span class=\"smalltext\">&nbsp;$a_lang[afunc_294] ($pages): $pagecount</span>","newdet.gif", $use_info);          
    
    
    $result2 = $db_sql->sql_query("".$query_long." LIMIT $start,10");
    while($news = $db_sql->fetch_array($result2)) {   
        $news = stripslashes_array($news);
        $no++;
			 
        if ($news['comments_allowed'] == 1) {        
            $kom_i = GetCommentNor($news['newsid']);
            $comments = "<b>$kom_i</b> $a_lang[afunc_129]";
        } else {
            $comments = $a_lang['afunc_115'];
        }					   
    			 
        $result3 = mysql_query("SELECT * FROM $newslinks_table WHERE newsid='$news[newsid]'");
        $count_links = mysql_num_rows($result3);
        if ($count_links == "0") {
            $count_links = $a_lang['afunc_117'];
        } else {
            $count_links = "<b>$count_links</b> $a_lang[afunc_118]";
        }
    			   
        if ($news['published'] == 1) {
            $status = $a_lang['afunc_119'];
            $stpic = "<img class=\"nav\" src=\"images/flaggreen.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$a_lang[afunc_120]\">";
        } else {
            $status = $a_lang['afunc_122'];
            $stpic = "<img class=\"nav\" src=\"images/flagred.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$a_lang[afunc_121]\">";
        }
    		
        $date = getdate($news['newsdate']);
        if(strlen($news['hometext'])>200) $news['hometext'] = substr($news['hometext'],0,200)."...";
        echo "<p>";
        buildTableHeader($news['headline'], 4);
        echo "<tr class=\"".switchBgColor()."\">\n";
        echo "<td width=\"8%\" rowspan=\"3\"><p>ID: <b>$news[newsid]</b></p></td>\n";
        echo "<td width=\"32%\"><p>$stpic $status</p></td>\n";
        echo "<td width=\"40%\"><span class=\"smalltext\">$comments<br>$count_links</span></td>\n";
        echo "<td width=\"20%\"><p><span class=\"smalltext\">$a_lang[afunc_125] ".aseDate($config['shortdate'],$news['newsdate'])."</span></p></td>\n";
        echo "</tr>\n";   
        echo "<tr class=\"".switchBgColor()."\">\n";
        echo "<td colspan=\"2\" valign=\"top\"><span class=\"smalltext\">".strip_tags($news['hometext'])."</span></td>\n";
        echo "<td nowrap valign=\"top\"><a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=edit&newsid=".$news['newsid'])."\"><img src=\"images/edit.gif\" alt=\"$a_lang[afunc_130]\" height=\"16\" width=\"16\" border=\"0\" align=\"absmiddle\">$a_lang[afunc_130]</a><br><a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=linksedit&newsid=".$news['newsid'])."\"><img src=\"images/links.gif\" alt=\"$a_lang[afunc_131]\" height=\"16\" width=\"16\" border=\"0\" align=\"absmiddle\">$a_lang[afunc_131]</a><br><a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=del&newsid=".$news['newsid'])."\"><img src=\"images/delart.gif\" alt=\"$a_lang[afunc_132]\" height=\"16\" width=\"16\" border=\"0\" align=\"absmiddle\">$a_lang[afunc_132]</a></td>\n";
        echo "</tr>\n";  
        buildTableFooter("",4);
        echo "</p>";
    }	
    if(!$over_all['total'] && $search_col && $search_word) {
        buildTableHeader($a_lang['prog_no_result'], 1);
        buildDarkColumn($a_lang['prog_no_result1']." '<b>".$search_word."</b>' ".$a_lang['prog_no_result2'],1,1,2);    
        buildTableFooter("",1);
    }
    
}
  
if ($step == 'conf') {
	$db_sql->sql_query("UPDATE $news_table SET published=1 WHERE newsid=$newsid");
	buildMessageRow($a_lang['news_mes5']);
}  
  
if($step == 'linksedit') {
    $result1 = $db_sql->sql_query("SELECT headline,catid FROM $news_table WHERE newsid='".$newsid."'");
    $headline = $db_sql->fetch_array($result1);
    $headline = stripslashes_array($headline);
    buildHeaderRow($a_lang['afunc_202']." - <b>".$headline['headline']."</b> - ".$a_lang['afunc_203'],"edit.gif");
	buildExternalItems(array($a_lang['afunc_130'],$a_lang['afunc_320']),array("news.php?step=edit&newsid=".$newsid,"news.php?step=choose&catid=".$headline['catid']),array("edit.gif","newdet.gif"));  
    buildTableHeader($a_lang['afunc_204'], 5);
		 
	$result = $db_sql->sql_query("SELECT * FROM $newslinks_table WHERE newsid='$newsid'");
	if($db_sql->num_rows($result) >= 1) { 
		$no = 1;
		while($links = $db_sql->fetch_array($result)) {
			$links = stripslashes_array($links);
			if ($links['link_target'] == 1) {
				$target = $a_lang['afunc_205'];
			} else {
				$target = $a_lang['afunc_206'];
			}		   
			echo "
			<tr class=\"".switchBgColor()."\">
			<td>".$a_lang['afunc_1'].": <b>".$links['linkid']."</b></td>
			<td>".$links['link_url']."</td>
			<td>".$links['link_name']."</td>
			<td>".$target."</td>
			<td nowrap><a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=link&newsid=".$newsid."&linkid=".$links['linkid'])."\"><img src=\"images/edit.gif\" alt=\"$a_lang[afunc_208]\" height=\"16\" width=\"16\" border=\"0\" align=\"absmiddle\">$a_lang[afunc_208]</a>  <a class=\"menu\" href=\"".$sess->adminUrl("news.php?step=linkdel&newsid=".$newsid."&linkid=".$links['linkid'])."\"><img src=\"images/delart.gif\" alt=\"$a_lang[afunc_209]\" height=\"16\" width=\"16\" border=\"0\" align=\"absmiddle\">$a_lang[afunc_209]</a></td>
			</tr>";
			$no++;
		}
	} else {
		buildDarkColumn($a_lang['afunc_117'],1,1,5);
	}
               
	buildTableFooter("",5);
	buildExternalItems($a_lang['afunc_210'],"news.php?step=linkadd&newsid=".$newsid,"add.gif");
} 
  
if($step == 'link' || $step == 'linkadd') {
	if($step == 'link') {
		$link = $db_sql->query_array("SELECT * FROM $newslinks_table WHERE linkid='".$linkid."'");
		$link = stripslashes_array($link);
		$action = "linkedit";
	} else {
		$action = "linkadd";
	}
	
	buildHeaderRow($a_lang['afunc_211'],"edit.gif");
	buildFormHeader("news.php", "post", $action);
	
	if($step == 'link') {
		buildHiddenField("linkid",$link['linkid']);
	}
	
	buildHiddenField("newsid",$newsid);
	buildTableHeader($a_lang['afunc_212']);
	buildInputRow($a_lang['afunc_213'], "link_name", $link['link_name']);
	buildInputRow($a_lang['afunc_214'], "link_url", $link['link_url']);
	($link['link_target'] == 1) ? $selected1 = "selected" : $selected2 = "selected";
	$target_option = "<select class=\"input\" name=\"link_target\">\n<option value=\"0\" $selected2>$a_lang[afunc_206]</option>\n<option value=\"1\" $selected1>$a_lang[afunc_205]</option>\n</select>";
	buildStandardRow($a_lang['afunc_215'], $target_option);
	buildFormFooter($a_lang['afunc_57'], "");
}
	
if($step == 'del') {
    $result = $db_sql->sql_query("SELECT * FROM $news_table WHERE newsid='$newsid'");
    $del = $db_sql->fetch_array($result);    
    buildHeaderRow($a_lang['adminutil_4'],"delart.gif");
    buildInfo($a_lang['info8'][0],$a_lang['info8'][1]);	
    buildFormHeader("news.php","post","del"); 
    buildHiddenField("newsid",$newsid);
    buildTableHeader("$a_lang[afunc_259]: <u>$del[headline]</u>");
    buildDarkColumn("$a_lang[news_del1] (ID: $newsid) $a_lang[news_del2]",1,1,2);
    buildFormFooter($a_lang['afunc_61'], "", 2, $a_lang['afunc_62']);
}

if($step == 'linkdel') {
    $result = $db_sql->sql_query("SELECT * FROM $newslinks_table WHERE linkid='$linkid'");
    $del = $db_sql->fetch_array($result);
    
    buildHeaderRow($a_lang['adminutil_4'],"delart.gif");
    buildInfo($a_lang['info8'][0],$a_lang['info8'][1]);	
    buildFormHeader("news.php","post","linkdel"); 
    buildHiddenField("linkid",$linkid);
	buildHiddenField("newsid",$newsid);
    buildTableHeader("$a_lang[afunc_259]: <u>$del[link_name]</u>");
    buildDarkColumn(sprintf($a_lang['news_really_delete_link'],$linkid),1,1,2);
    buildFormFooter($a_lang['afunc_61'], "", 2, $a_lang['afunc_62']);  
}

if($step == "catgraf") {
    buildHeaderRow($a_lang['uploads_categupload'],"upload_file.gif");	
    buildInfo($a_lang['uploads_fileupload'],"$a_lang[uploads_note1] $max_fsize $a_lang[uploads_note2]");
    $head = $a_lang['uploads_h1'];  
    buildFormHeader("news.php", "post", "catgraf_upload", "alp", 1);
    buildTableHeader($a_lang['uploads_new']);
    buildUploadRow("<b>$a_lang[uploads_search] $a_lang[uploads_h1]</b><br><span class=\"smalltext\">$a_lang[uploads_message]</span>", "file");
    buildRadioRow($a_lang['uploads_changename'], "rename");
    buildFormFooter($a_lang['uploads_button1'], $a_lang['uploads_reset'], 2);
    
    echo "<br>";
    
    $result = $db_sql->sql_query("SELECT cat_image FROM $newscat_table");
    while($cat_image = $db_sql->fetch_array($result)) {
        $cat_cache[$cat_image['cat_image']] = $cat_image['cat_image'];
    }
    
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
    
    if(in_array('Thumbs.db',$bildlist)) $position = array_search('Thumbs.db',$bildlist);
    array_splice($bildlist,$position,1);
    
    if(in_array('index.html',$bildlist)) $position2 = array_search('index.html',$bildlist);
    array_splice($bildlist,$position2,1);    
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
	$url_neu = $sess->adminUrl("news.php?step=catgraf")."&amp;";
	$nav->MyLink = $url_neu;
	$nav->LinkClass = "smalltext";
	$nav->start = $start;
	$pagecount = $nav->BuildLinks();
	if(!$pagecount) $pagecount = "<b>1</b>";
    $pages = intval($over_all / $nav->perPage);
    if($over_all % $nav->perPage) $pages++;	

    $no = 1;
    buildTableHeader($a_lang['news_pictures']."<br><span class=\"smalltext\">&nbsp;$a_lang[afunc_291] ($pages): $pagecount</span>", 3);
    foreach($bildlist AS $file) {
        if($no == 1 || $no == 4 || $no == 7) $display_avatar .= "<tr>\n";
        $size = getimagesize($_ENGINE['eng_dir'].'/catgrafs/'.$file);
        $datasize=filesize($_ENGINE['eng_dir'].'/catgrafs/'.$file);

        $ins['size']=$size[0].'x'.$size[1].', '.rebuildFileSize($datasize);
        
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
        $option = "<a href=\"javascript:filedata2('".$file."')\"><img src=\"images/transfer.gif\" border=\"0\" width=\"16\" height=\"16\" alt=\"".$a_lang['news_insert_image']."\" title=\"".$a_lang['news_insert_image']."\" /></a>&nbsp;";              
        if(!in_array($file,$cat_cache)) {
        $option .= "<a onClick=\"return confirm('".$a_lang['news_do_you_really_want_delete']."');\" href=\"news.php?action=delete_catgraf&file=$file\"><img src=\"images/delete.gif\" border=\"0\" width=\"16\" height=\"16\" alt=\"".$a_lang['news_delete_image']."\" title=\"".$a_lang['news_delete_image']."\" /></a>";              
        } else {
        $option .= "<img src=\"images/no_delete.gif\" width=\"16\" height=\"16\" alt=\"".$a_lang['news_delete_image_not_possible']."\" title=\"".$a_lang['news_delete_image_not_possible']."\" />";
        }                    
        $display_avatar .= "<td class=\"firstcolumn\" valign=\"top\"><div align=\"center\"><b>".$ins['name']."</b><br><span class=\"menu\">(".$a_lang['news_size']." ".$ins['size'].")</span><br><a href=\"".$config['catgrafurl']."/".$file."\" target=\"_blank\"><img src=\"".$config['catgrafurl']."/".$file."\" width=\"".$size[0]."\" height=\"".$size[1]."\" alt=\"".$file."\" title=\"".$file."\" border=\"0\" style=\"border:1px dashed #000000;margin:3px;\" /><br>".$option."</div></td>\n";
        if($no == 3 || $no == 6 || $no == 9) $display_avatar .= "</tr>\n";
        if($no == 9) break;
        $no++;
    }
    echo $display_avatar;
    buildTableFooter("",3);
    closeWindowRow();
}  

if($step == "bbhelp") {
    buildTableHeader($a_lang['bbhelp_2']);
    buildTableSeparator($a_lang['bbhelp_3']);
    echo "<tr class=\"".switchBgColor()."\">\n<td valign=\"top\" colspan=\"2\"><p>".$a_lang['bbhelp_4']."</p></td>\n";
    buildStandardRow($a_lang['bbhelp_5'], $a_lang['bbhelp_6']);
    buildStandardRow("[b]".$a_lang['bbhelp_7']."[/b]", "<b>".$a_lang['bbhelp_7']."</b>");
    buildStandardRow("[i]".$a_lang['bbhelp_8']."[/i]", "<i>".$a_lang['bbhelp_8']."</i>");
    buildStandardRow("[u]".$a_lang['bbhelp_9']."[/u]", "<u>".$a_lang['bbhelp_9']."</u>");
    buildStandardRow("[url=http://www.link.de]".$a_lang['bbhelp_10']."[/url]", "<a class=\"post\" href=\"http://www.link.de\" target=_blank>".$a_lang['bbhelp_10']."</a>");
    buildStandardRow("[url]http://www.link.de[/url]", "<a class=\"post\" href=\"http://www.link.de\" target=_blank>http://www.link.de</a>");
    buildStandardRow("[email]die@adresse.de[/email]", "<a class=\"post\" href=\"mailto:die@adresse.de\">die@adresse.de</a>");
    buildStandardRow("[email=die@adresse.de]".$a_lang['bbhelp_11']."[/email]", "<a class=\"post\" href=\"mailto:die@adresse.de\">".$a_lang['bbhelp_11']."</a>");
    buildStandardRow("[code]".$a_lang['bbhelp_12']."[/code]", "<blockquote><font size=1>Quellcode:</font><hr><pre><font size=1>".$a_lang['bbhelp_12']."</font></pre><hr></blockquote>  <hr>");
    buildStandardRow("[quote]".$a_lang['bbhelp_13']."[/quote]", "<blockquote><font size=1>Zitat:</font><hr><font size=1>".$a_lang['bbhelp_13']."</font><hr></blockquote>");
    buildTableFooter();
}

buildAdminFooter();
?>