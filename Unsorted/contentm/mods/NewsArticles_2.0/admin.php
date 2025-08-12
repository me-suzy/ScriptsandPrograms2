<?php
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");	
	
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	/admin/docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	// XHTML Compliant = Yes

	/////////////////////////////////////////////////////////////*/
	
	@import_request_variables('cgps');
	$PHP_SELF=$_SERVER['PHP_SELF'];
	if(!isset($S)){$S=0;}
	$ModLoader=1;
	include "../../admin/iware.php";
	$IW = new IWARE ();	
	$IW->maybeOpenLogInWindow();

	// Include Configured Language Definition
	$lang = IWARE_LANG;
	if(!file_exists($lang)){$lang="US_ENGLISH";}
	include $lang;

	// Program Command definitions
	define("INSTALL",0);
	define("STARTUP",1);
	define("UPDATE",2);
	define("CREATE_ARTICLE",3);	
	define("ADD_ARTICLE",4);
	define("EDIT_ARTICLE",5);
	define("UPDATE_ARTICLE",6);
	define("REMOVE_ARTICLE",7);

	/** 
	 * Module class for news Articles Module
	 *
	 * @package iWare Professional
	 * @author David N. Simmons <http://www.dsiware.com>
	 * @version 2.0
	 * @access public
	 * @copyright iWare 2002,2003
	 *
	 */
	class Module {

		/**
		 * Checks if the required datbase structure for the module exists in the configured database. If the required structure exists then the methosd will return TRUE, false otherwise.
		 *
		 * @return boolean
		 * @access private
		 */
		function ModExists ()
			{
			global $IW;
			if(!$IW->tableExists ("mod_newspost_config")){return false;}
			else{return true;}
			}

		/**
		 * Outputs the install module interface
		 *
		 * @access private
		 */
		function ModInstallForm ()
			{
			global $IW,$GUI;
			$GUI->OpenWidget(MODLANG_1);
			$GUI->OpenForm ("","admin.php?cmd=".INSTALL,"");
			echo MODLANG_2."<br /><br />";
			echo $GUI->Button(MODLANG_3);
			$GUI->CloseForm ();
			$GUI->CloseWidget();
			echo "</body>\n</html>\n";
			exit;
			}
		
		/**
		 * Creates any needed database structure and default data for the module in the configured database
		 *
		 * @access private
		 */
		function ModInstall ()
			{
			global $IW,$GUI;
			$IW->Query("CREATE TABLE mod_newspost ( id varchar(50) default NULL, news_date int(20) unsigned default NULL, news_headline varchar(50) default NULL, news_author varchar(50) default NULL, news_body text)");
			$IW->Query("CREATE TABLE mod_newspost_config ( news_per_page int(3) default NULL, preview_length int(3) default NULL)");
			$IW->Query("INSERT INTO mod_newspost_config VALUES('4', '200')");
			$GUI->Message(MODLANG_4);
			$GUI->Navigate("admin.php?");
			}

		/**
		 * Outputs the configuration interface for the module
		 *
		 * @access private
		 */
		function ConfigForm ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_newspost_config limit 1");
			$GUI->OpenForm("Settings","admin.php?cmd=".UPDATE,"return vSettings ()");
			$GUI->OpenWidget(MODLANG_5);
			echo "<table border=0>";	
			echo "<tr><td>".$GUI->TextBox("news_per_page",$IW->Result($result,0,"news_per_page"),3);
			echo "</td><td>".$GUI->Label(MODLANG_6)."</td></tr>";
			echo "<tr><td>".$GUI->TextBox("preview_length",$IW->Result($result,0,"preview_length"),3);
			echo "</td><td>".$GUI->Label(MODLANG_7)."</td></tr>";
			echo "</table>";
			echo $GUI->Button(MODLANG_8);
			$GUI->CloseWidget("");
			$GUI->CloseForm();
			$IW->FreeResult($result);
			}

		/**
		 * Updates any changes made to the configuration of the module
		 *
		 * @access private
		 */
		function ConfigUpdate ()
			{
			global $IW,$GUI;
			global $allow_posts,$author_req,$subject_req,$body_req;
			global $news_per_page,$preview_length;
			if(!isset($allow_posts)){$allow_posts=0;}
			if(!isset($author_req)){$author_req=0;}
			if(!isset($subject_req)){$subject_req=0;}
			if(!isset($body_req)){$body_req=0;}
			$IW->Query("update mod_newspost_config set news_per_page='$news_per_page',preview_length='$preview_length' ");
			$GUI->Message(MODLANG_9);
			$GUI->Navigate("admin.php?");			
			}

		/**
		 * Outputs the article management interface
		 *
		 * @access private
		 */
		function ManageArticles ()
			{
			global $IW,$GUI;
			$result=$IW->Query("select * from mod_newspost order by news_date desc");
			$GUI->OpenWidget(MODLANG_10);
			$count=$IW->CountResult($result);
			echo "<center><i>".MODLANG_11." $count ".MODLANG_12."</i></center>";
			$GUI->OpenForm("","admin.php?cmd=".CREATE_ARTICLE,"");
			echo $GUI->Button(MODLANG_13);
			$GUI->CloseForm();
			echo "<table border=0 cellpadding=3 cellspacing=0>";
			$row=0;
			for($i=0;$i<$count;$i++)
				{
				if($row==0){$color="#f5f5f5";}
				elseif($row==1){$color="#e4e4e4";}
				echo "<tr>";
				echo "<td bgcolor=$color><i>".date("m/d/Y",$IW->Result($result,$i,"news_date"))."</i></td>";
				echo "<td bgcolor=$color><b>".$IW->Result($result,$i,"news_headline")."</b></td>";
				$GUI->OpenForm ("dForm","admin.php?cmd=".EDIT_ARTICLE."&id=".$IW->Result($result,$i,"id"),"");
				echo "<td bgcolor=$color>".$GUI->Button(MODLANG_14)."</td>";
				$GUI->CloseForm ();				
				$GUI->OpenForm ("dForm","admin.php?cmd=".REMOVE_ARTICLE."&id=".$IW->Result($result,$i,"id"),"return vDelete ()");
				echo "<td bgcolor=$color>".$GUI->Button(MODLANG_15)."</td>";
				$GUI->CloseForm ();
				echo "</tr>";
				if($row==0){$row=1;}
				elseif($row==1){$row=0;}
				}
			echo "</table>";
			$GUI->CloseWidget("");
			$IW->FreeResult($result);
			}
		
		/**
		 * Outputs the create new article interface
		 *
		 * @access private
		 */	
		function AddArticleForm ()
			{
			global $GUI;
			$GUI->OpenForm("Newpost","admin.php?cmd=".ADD_ARTICLE,"return vCreate ()");
			$GUI->OpenWidget("Create New Article");
			echo "<table border=0><tr><td>";
			echo $GUI->Label(MODLANG_16)."<br />";
			echo $GUI->TextBox("news_date",date("m/d/Y"),15)." <i>mm/dd/yyyy</i><br />";
			echo $GUI->Label(MODLANG_17)."<br />";
			echo $GUI->TextBox("news_headline","",50)."<br />";
			echo $GUI->Label(MODLANG_18)."<br />";
			echo $GUI->TextBox("news_author","",50)."<br />";
			echo $GUI->Label(MODLANG_19)."<br />";
			echo $GUI->TextArea("news_body","",10,70)."<br />";
			echo "<script language=\"javascript1.2\">editor_generate('news_body');</script>\n";
			echo "</td></tr></table>";
			echo $GUI->Button(MODLANG_20);
			$GUI->CloseWidget();
			$GUI->CloseForm();			
			}

		/**
		 * Outputs the edit article interface
		 *
		 * @access private
		 */
		function EditArticleForm ()
			{
			global $IW,$GUI;
			global $id;
			$result=$IW->Query("select * from mod_newspost where id='$id' limit 1");
			$GUI->OpenForm("Newpost","admin.php?cmd=".UPDATE_ARTICLE."&id=$id","return vCreate ()");
			$GUI->OpenWidget(MODLANG_21);
			echo "<table border=0><tr><td>";
			echo $GUI->Label(MODLANG_22)."<br />";
			echo $GUI->TextBox("news_date",date("m/d/Y",$IW->Result($result,0,"news_date")),15)." <i>mm/dd/yyyy</i><br />";
			echo $GUI->Label(MODLANG_23)."<br />";
			echo $GUI->TextBox("news_headline",$IW->Result($result,0,"news_headline"),50)."<br />";
			echo $GUI->Label(MODLANG_24)."<br />";
			echo $GUI->TextBox("news_author",$IW->Result($result,0,"news_author"),50)."<br />";
			echo $GUI->Label(MODLANG_25)."<br />";
			echo $GUI->TextArea("news_body",$IW->Result($result,0,"news_body"),10,70)."<br />";
			echo "<script language=\"javascript1.2\">editor_generate('news_body');</script>\n";
			echo "</td></tr></table>";
			echo $GUI->Button(MODLANG_26);
			$GUI->CloseWidget();
			$GUI->CloseForm();
			$IW->FreeResult($result);
			}

		/**
		 * Adds an article to the database
		 *
		 * @access private
		 */
		function AddArticle ()
			{
			global $IW,$GUI;
			global $news_date,$news_author,$news_headline,$news_body;
			$news_author=str_replace("'","",$news_author);
			$news_headline=str_replace("'","",$news_headline);
			$news_body=str_replace("'","",$news_body);
			$id=md5(uniqid(rand(),1)); 
			$date=strtotime($news_date);
			$IW->Query("insert into mod_newspost (id,news_date,news_author,news_headline,news_body) values ('$id','$date','$news_author','$news_headline','$news_body')");
			$GUI->Message(MODLANG_27);
			$GUI->Navigate("admin.php?");
			}

		/**
		 * Updates any changes made to an existing article
		 *
		 * @access private
		 */
		function UpdateArticle ()
			{
			global $IW,$GUI;
			global $id,$news_date,$news_author,$news_headline,$news_body;
			$date=strtotime($news_date);
			$IW->Query("update mod_newspost set news_date='$date',news_author='$news_author',news_headline='$news_headline',news_body='$news_body' where id='$id' limit 1");
			$GUI->Message(MODLANG_28);
			$GUI->Navigate("admin.php?");
			}

		/**
		 * Deletes an existing article from the database
		 *
		 * @access private
		 */
		function DeleteArticle ()
			{
			global $IW,$GUI;
			global $id;
			$IW->Query("delete from mod_newspost where id='$id' limit 1");
			$GUI->Message(MODLANG_29);
			$GUI->Navigate("admin.php?");
			}

	// end class
	}

	// Instantiate Module Class
	$MOD = new Module ();

?>
<html>
<head>
<title>iWare Professional Version <?php echo IWARE_VERSION; ?></title>
<link rel="stylesheet" href="../../admin/iware.css"></link>
<style type="text/css">
	body, td  { font-family: arial; font-size: x-small; }
	a         { color: #0000BB; text-decoration: none; }
	a:hover   { color: #FF0000; text-decoration: underline; }
	.headline { font-family: arial black, arial; font-size: 28px; letter-spacing: -1px; }
	.headline2{ font-family: verdana, arial; font-size: 12px; }
	.subhead  { font-family: arial, arial; font-size: 18px; font-weight: bold; font-style: italic; }
	.backtotop     { font-family: arial, arial; font-size: xx-small;  }
	.code     { background-color: #EEEEEE; font-family: Courier New; font-size: x-small;
	margin: 5px 0px 5px 0px; padding: 5px;
	border: black 1px dotted;
	}
	font { font-family: arial black, arial; font-size: 28px; letter-spacing: -1px; }
</style>
<script language=JavaScript>
	_editor_url = "../../admin/";
	var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
	if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
	if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
	if (win_ie_ver >= 5.5) 
		{
		 document.write('<scr' + 'ipt src="' +_editor_url+ 'modeditor.js"');
		 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
		} 
	else 
		{ 
		document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>');
		}
function isNumber (x)
	{
	var anum=/(^\d+$)|(^\d+\.\d+$)/
	if (anum.test(x))
		return true;
	else 
		return false;
	}	
	function vSettings ()
		{
		if(!isNumber(document.Settings.preview_length.value))
			{alert('<?php echo MODLANG_30; ?>');return false;}
		if(!isNumber(document.Settings.news_per_page.value))
			{alert('<?php echo MODLANG_31; ?>');return false;}
		return true;
		}
	function vCreate ()
		{
		if(document.Newpost.news_headline.value.length<1)
			{alert('<?php echo MODLANG_32; ?>');return false;}
		if(document.Newpost.news_body.value.length<1)
			{alert('<?php echo MODLANG_33; ?>');return false;}
		return true;
		}
	function vDelete ()
		{
		if(window.confirm('<?php echo MODLANG_34; ?>')){return true;}
		else{return false;}
		}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<?php $GUI->PageBody (1); ?>
<?php
	if(!isset($cmd)){$cmd=STARTUP;}
	switch($cmd)
		{
		case INSTALL:
			$MOD->ModInstall ();
		break;		
		case STARTUP:
			if(!$MOD->ModExists ()){$MOD->ModInstallForm ();}
			$MOD->ConfigForm ();
			$MOD->ManageArticles ();
		break;
		case UPDATE:
			$MOD->ConfigUpdate ();
		break;
		case CREATE_ARTICLE:
			$MOD->AddArticleForm ();
		break;
		case ADD_ARTICLE:
			$MOD->AddArticle ();
		break;
		case EDIT_ARTICLE:
			$MOD->EditArticleForm ();
		break;
		case UPDATE_ARTICLE:
			$MOD->UpdateArticle ();
		break;
		case REMOVE_ARTICLE:
			$MOD->DeleteArticle ();
		break;
		}
?>
<?php include "../../admin/author.php"; ?>
</body>
</html>