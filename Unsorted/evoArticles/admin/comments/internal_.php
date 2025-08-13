<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+
// OLD COMMENTS CLASS, dont use
class Comments
{
	function Comments()
	{
		global $settings,$root;
		$this->def_folder = $root."templates/styles/".$settings['defstyle'];
		$this->perpage = "20";
	}
	
	function process_addcomment()
	{
		global $_POST,$database,$admin,$udb,$evoLANG;

		$_POST = $admin->slash_array($_POST);

		$udb->query("INSERT INTO $database[article_comment] SET name='$_POST[name]',artid='$_POST[artid]',comment='$_POST[comments]',ip='$_POST[ip]',email='$_POST[email]'");
		return $evoLANG['added'];
	}

	function redirect($loc)
	{
		if ($loc != "")
		{
			header("location: ".$loc);
		}
	}

	function deletecomment($id)
	{
		global $udb,$database,$_SERVER;

		$udb->query("DELETE FROM $database[article_comment] WHERE id='$id'");
		$this->redirect($_SERVER['PHP_SELF']);
	}

	function editcomment($id)
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$_POST,$evoLANG,$tpl,$script,$parser,$settings;
		if ($id == '') return false;
		
		if ($_POST['editcomment'])
		{
			$_POST = $admin->slash_array($_POST);

			$udb->query("UPDATE $database[article_comment] SET comment='".$_POST['comment']."',name='".$_POST['name']."',email='".$_POST['email']."' WHERE id='".$_POST['id']."'");
			$content .= $evoLANG['commentupdated']." <br />";
		}

		$row = $udb->query_once("SELECT * FROM $database[article_comment] WHERE id='".$id."'");
		$row = $admin->strip_array($row);
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => $evoLANG['name'],
										"email"		  => $evoLANG['email'],
										"comment"     => $evoLANG['comments']
									 );
		// element condition
		/* ------------------------------------------------ */

		$content .= $admin->form_start("",$_SERVER['PHP_SELF']."?do=edit&amp;id=$id").$admin->form_hidden("id",$id);
		$html .= $admin->add_spacer($evoLANG['editcomment']);
		$html .= $admin->add_row($evoLANG['name'],$admin->form_input("name",$row['name']) );
		$html .= $admin->add_row($evoLANG['email'],$admin->form_input("email",$row['email']) );
		$html .= $admin->add_row($evoLANG['comments'],$admin->form_textarea("comment",$row['comment']) );
		$html .= $admin->form_submit("editcomment");
		$content .= $admin->add_table($html);
		$content .= "</form>";

		return $content;
	}


	function showcomments($artid='')
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$evoLANG,$tpl,$script,$parser,$settings;

		if ($artid != '')
		{
			$sql = $udb->query("SELECT * FROM $database[article_comment] WHERE artid='$artid'");
			$parser->doAutoBR = 0;
			$parser->allowBBCode = $settings['internal_canbbcode'] == 1 ? 1:0;
			$parser->allowHTML= $settings['internal_canhtml'] == 1 ? 1:0;

			

			while ( $comment = $udb->fetch_array($sql) )
			{
				$comment = $admin->strip_array($comment);
				$comment['comment'] = $parser->do_parse($comment['comment']);

				if ($comment['email'] != '')
				{
					$email = explode("@",$comment['email']);
					eval("\$comment[email] = \"".$tpl->gettemplate("bits_email",'',$this->def_folder)."\";");
				}

				eval("\$content .= \"".$tpl->gettemplate("comments_loop",'',$this->def_folder)."\";");
			}
		}
		return $content;
	}

	function showform($id='')
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$evoLANG,$tpl;
		
		if ($id == '') return false;

		$admin->row_align = "left";
		$userip = $admin->get_ip();
		

		eval("\$content = \"".$tpl->gettemplate("comments_add",'',$this->def_folder)."\";");
		return $content;
	}

	function manage()
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$evoLANG,$tpl,$script,$parser,$settings,$script;

		if (!is_array($cmt_cache))
		{
			$csql = $udb->query("SELECT * FROM $database[article_comment]");
			while ($crow = $udb->fetch_array($csql))
			{
				$cmt_cache = $admin->strip_array($cmt_cache);
				$cmt_cache [$crow['artid']][$crow['id']] = $crow;
			}
		}
		
		$pg = trim($_GET['pg']) == '' ? '':$_GET['pg'];
		$pg = (isset($pg)) && $pg != '' ? $pg:"1";

			
		$sql2 = $udb->query("SELECT id FROM $database[article_article]");
		$total = $udb->num_rows($sql2);
			
		if ($total > $this->perpage)
		{
			$totalpage = ceil($total/$this->perpage);
		}
				
		$totalpage = ($totalpage=='') ? $pg:$totalpage;
		$num = $pg + 1;
		$offset = ($pg-1) * $this->perpage;
		$prev = $pg-1;
		$curpage = $pg;
		$sort = $_GET['sort'] == '' ? "id":$_GET['sort'];

		if ($pg < $totalpage) $nextpage = "<a href=\"$_SERVER[PHP_SELF]?pg=$num\"> ></a> ";
		if ($pg > 1) $prevpage = " <a href=\"$_SERVER[PHP_SELF]?pg=".$prev."\">< </a>";
		
		for ($i = 1; $i <= $totalpage; $i++)
		{
			if ($i == $curpage)
			{
				$pgloop .= "<b> [".$i."]</b> ";
			}
			else
			{
				$pgloop .= " <b><a href=\"$_SERVER[PHP_SELF]?pg=".$i."\">".$i."</a></b> ";
			}
		}

		$sql = $udb->query("SELECT * FROM $database[article_article] ORDER BY $sort DESC LIMIT $offset,".$this->perpage);
		while ( $row = $udb->fetch_array($sql) )
		{
			$i++;
			$bg = $admin->get_bg($i);
			$totalcomments = $evoLANG['totalcomments'].": ".number_format(count($cmt_cache[$row['id']]));
			$img = $row['id'] == $_GET['ex'] ? "minus":"plus";
			$row['subject'] = number_format(count($cmt_cache[$row['id']])) > 0 ? $admin->makelink("<img src=\"".$script['imgfolder']."/$img.gif\" alt=\"\"> ".$row['subject'],$_SERVER['PHP_SELF']."?ex=".$row['id']."&amp;pg=".$pg):$row['subject'];

			$loop .= "<tr $bg><td colspan=\"2\"><div style=\"float:left\"><b> $row[subject] </b></div> <div style=\"float:right\">$totalcomments </div></td> </tr>";
			if ($_GET['ex'] == $row['id'])
			{
				$loop .= $this->admin_getcomments($row['id']);
			}
		}
		
		$content .= $admin->add_spacer($evoLANG['comments']);
		$content .= $loop;
		$content = $admin->add_table($content);
		$content = $content."<br /><div align=\"right\">".$prevpage.$pgloop.$nextpage."</div>";
		return $content;
	}
	
	function admin_getcomments($id)
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$evoLANG,$tpl,$script,$settings,$cmt_cache;
		if ($id == '') return false;

		if (!is_array($cmt_cache))
		{
			$csql = $udb->query("SELECT * FROM $database[article_comment]");
			while ($crow = $udb->fetch_array($csql))
			{
				$cmt_cache = $admin->strip_array($cmt_cache);
				$cmt_cache [$crow['artid']][$crow['id']] = $crow;
			}
		}
			
		include ("lib/class_codeparse.php");
		$parser = new Parser;


		$parser->doAutoBR = 0;
		$parser->allowBBCode = $settings['internal_canbbcode'] == 1 ? 1:0;
		$parser->allowHTML= $settings['internal_canhtml'] == 1 ? 1:0;


		foreach ($cmt_cache[$id] as $comment)
		{
			$i++;
			$bg = $admin->get_bg($i);

			$loop .= "<tr $bg><td colspan=\"2\"><div style=\"float:left;margin-left:15px;\"><b> $comment[name] </b> <br /> ".$parser->do_parse($comment[comment])."</div> <div style=\"float:right\"> [<a href=\"$_SERVER[PHP_SELF]?do=edit&amp;id=$comment[id]\">Edit</a>] [".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=delete&amp;id=$comment[id]")."] </div></td> </tr>";
		}

		return $loop;
		
	}

	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}


	// forum userid
	// forum password
	// forum url
	// category mapping

}