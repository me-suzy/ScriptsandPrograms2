<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class Comments
{
	function Comments()
	{
		global $settings,$root;
		$this->def_folder = $root."templates/styles/".$settings['defstyle'];
		$this->perpage = "15";
		$this->floodcheck = "60"; // in seconds
	}
	
	function process_addcomment()
	{
		global $_POST,$database,$admin,$udb,$evoLANG,$_COOKIE,$settings;
		
		if ($settings['internal_usevalidation'] == 1)
		{
			$validation = ",approved='0'";
		}

		if ($_COOKIE['flood'] == 1)
		{
			return $evoLANG['floodmsg'];
		}
		else
		{
			$_POST = $admin->slash_array($_POST);		
			$udb->query("INSERT INTO $database[article_comment]
							SET
								name='$_POST[name]',
								artid='$_POST[artid]',
								comment='$_POST[comments]',
								ip='$_POST[ip]',
								email='$_POST[email]',
								date='".time()."'
								$validation
						");

			$admin->makecookie("flood","1",$this->floodcheck);

			if ($settings['internal_usevalidation'] == 1)
			{
				return $evoLANG['comment_approval'] ;
			}
			else
			{
				return $evoLANG['added'];
			}
		}
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
		global $udb,$database,$_SERVER,$_GET;

		$udb->query("DELETE FROM $database[article_comment] WHERE id='$id'");
		$this->redirect($_SERVER['PHP_SELF']."?ex=".$_GET['ex']."&pg=".$_GET['pg']);
	}

	function approvecomment($id)
	{
		global $udb,$database,$_SERVER,$_GET;

		$udb->query("UPDATE $database[article_comment] SET approved='1' WHERE id='$id'");
		$this->redirect($_SERVER['PHP_SELF']."?ex=".$_GET['ex']."&pg=".$_GET['pg']);
	}

	function approveall()
	{
		global $udb,$database,$_SERVER,$_GET;
		
		$udb->query("UPDATE $database[article_comment] SET approved='1' WHERE approved='0'");
		$this->redirect($_SERVER['PHP_SELF']."?ex=".$_GET['ex']."&pg=".$_GET['pg']);
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
			$validation = $settings['internal_usevalidation'] == 1 ? " AND approved = '1'":"";
			$sql = $udb->query("SELECT * FROM $database[article_comment] WHERE artid='$artid'".$validation);
			$parser->doAutoBR = 1;
			$parser->allowBBCode = $settings['internal_canbbcode'] == 1 ? 1:0;
			$parser->allowHTML= $settings['internal_canhtml'] == 1 ? 1:0;

			

			while ( $comment = $udb->fetch_array($sql) )
			{
				$comment = $admin->strip_array($comment);
				
				if ($settings['internal_censor'] != '')
				{
					$words = explode("\n",$settings['internal_censor']);
				
					if (count($words) > 1)
					{
						foreach($words as $word)
						{
							$comment['comment'] = $this->filter($word,$comment['comment']);
						}
					}
				}
					
				$comment['date'] = $comment['date'] != '' ? date($settings['dateformat'],$comment['date']):"";
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
				
				$cmt_cache [$crow['artid']][$crow['id']] = $crow;
			}
		}


		$pg = trim($_GET['pg']) == '' ? '':$_GET['pg'];
		$pg = (isset($pg)) && $pg != '' ? $pg:"1";
			
		$sql2 = $udb->query("SELECT * FROM $database[article_comment] WHERE artid != 0 ORDER BY date DESC");
		while ($brow = $udb->fetch_array($sql2))
		{	
			//trying to save queries
			$sql_save .= "".$brow['artid'].",";

			if ($brow['approved'] == 0)
			{
				$unlive[$brow['id']] = $brow;
				$overallunapproved++;
			}
			else
			{
				$groups[][$brow['artid']][$brow['id']] = $brow;
				$overalltotal++;
			}
		}
		
		$sql_save = substr($sql_save,0,-1);
		if ($sql_save != '')
		{
			$save_sql = $udb->query("SELECT id,pid,subject FROM $database[article_article] WHERE id IN (".$sql_save.")");
			while ($save_row = $udb->fetch_array($save_sql))
			{
				$art_cache[$save_row['id']] = $save_row;
			}
		}


		include ("lib/class_codeparse.php");
		$parser = new Parser;

		$parser->doAutoBR = 1;
		$parser->allowBBCode = $settings['internal_canbbcode'] == 1 ? 1:0;
		$parser->allowHTML= $settings['internal_canhtml'] == 1 ? 1:0;
		require ("./lib/class_articles.php");
		$art = new addon_Article;

		
		$html .= "<div align=\"right\">".$admin->link_button($evoLANG['viewallcomments'],$_SERVER['PHP_SELF']."?do=viewall") ."</div>";
		$html .= $evoLANG['comment_totalunapprvd'].": ".number_format($overallunapproved) ." <br />";
		$html .= $evoLANG['totalcomments'].": ".number_format($overalltotal) ." <br />";
		$html .= "<br />";

		if ( is_array($unlive) )
		{
			foreach ($unlive as $comment)
			{
				$I++;
				$bg = $admin->get_bg($I);

				$unapproved .= "<tr $bg><td colspan=\"2\"><div style=\"float:left;margin-left:5px;\"><b> $comment[name] </b>(".date($settings['dateformat'],$comment[date]).") <br /> ".$parser->do_parse($comment[comment])."</div> <div style=\"float:right\"> [".$this->wrap_confirm("<b>".$evoLANG['approvecomment']."</b>","$_SERVER[PHP_SELF]?do=approve&amp;id=$comment[id]&amp;pg=$pg&amp;ex=$_GET[ex]")."] [<a href=\"$_SERVER[PHP_SELF]?do=edit&amp;id=$comment[id]\">Edit</a>] [".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=delete&amp;id=$comment[id]")."] </div></td> </tr>";
			}
		}

		$unapproved = $unapproved == '' ? "<tr class=\"thrdalt\"><td colspan=\"2\">".$evoLANG['comment_x2apprved']."</td></tr>":$unapproved;

		$html .= $admin->add_table($admin->add_spacer($evoLANG['comment_unapproved']).$unapproved);
		$html .= "<div align=\"right\">".$admin->link_button($evoLANG['approveall'],$_SERVER['PHP_SELF']."?do=approveall") ."</div><br />";
		

		if (is_array($groups))
		{
			foreach($groups as $ar1 => $ar2)
			{
				foreach($ar2 as $v1 => $v2)
				{
					foreach($ar2[$v1] as $cs1 => $cs2)
					{
						//print_r( $cs2 );
						$newarr[$v1][$cs1] = $cs2;
					}
				}
			}
		}
			
		if (is_array($newarr))
		{
			foreach ($newarr as $p => $c)
			{
				$newarr2[][$p] = $c;
			}
		}

		$total = count($newarr2);			
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

		$counter = $offset;
		while ($counter < ($this->perpage*$pg))
		{
			if (!is_array($newarr2[$counter])) break;

			foreach ($newarr2[$counter] as $parent => $child)
			{
				$row = $art_cache[$parent];
					
					//save queries!
					//$row = $udb->query_once("SELECT id,pid,subject FROM $database[article_article] WHERE id='$parent'");
					$i++;
					$bg = $admin->get_bg($i);
					$totalcomments = $evoLANG['totalcomments'].": ".number_format(count($cmt_cache[$row['id']]));
					$img = $row['id'] == $_GET['ex'] ? "minus":"plus";
					$row['subject'] = number_format(count($cmt_cache[$row['id']])) > 0 ? $admin->makelink("<img src=\"".$script['imgfolder']."/$img.gif\" alt=\"\"> ".$row['subject'],$_SERVER['PHP_SELF']."?ex=".$row['id']."&amp;pg=".$pg):$row['subject'];
					$arturl = $settings['siteurl']."/".$art->link_art($parent);
					$loop .= "<tr $bg><td colspan=\"2\"><div style=\"float:left\"><b> $row[subject] </b> [ ".$admin->makelink($evoLANG['viewart'],$arturl,$evoLANG['viewart'],"_blank")."]</div> <div style=\"float:right\">$totalcomments </div></td> </tr>";
					if ($_GET['ex'] == $row['id'])
					{
						$loop .= $this->admin_getcomments($row['id']);
					}

			}

			$counter++;
		}
		
		$content .= $admin->add_spacer($evoLANG['comments']);
		$content .= $loop;
		$content = $admin->add_table($content);
		$content = $content."<br /><div align=\"right\">".$prevpage.$pgloop.$nextpage."</div>";
		
		return $html."<br />".$content;
	}
	
	function viewall()
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$evoLANG,$tpl,$script,$settings,$cmt_cache,$parser;
		
		include ("lib/class_codeparse.php");
		$parser = new Parser;

		$parser->doAutoBR = 1;
		$parser->allowBBCode = $settings['internal_canbbcode'] == 1 ? 1:0;
		$parser->allowHTML= $settings['internal_canhtml'] == 1 ? 1:0;


		$validation = $settings['internal_usevalidation'] == 1 ? " WHERE approved = '1'":"";
		$sql = $udb->query("SELECT 
									$database[article_comment].*,
									$database[article_article].subject 
										
										FROM $database[article_comment]
											LEFT JOIN $database[article_article]
												ON ($database[article_comment].artid = $database[article_article].id) 
													".$validation."
													ORDER BY date DESC
														");

		while ($comment = $udb->fetch_array($sql) )
		{
			$i++;
			$bg = $admin->get_bg($i);
			
			$loop .= "<tr $bg><td colspan=\"2\"><div style=\"float:right\"> [<a href=\"$_SERVER[PHP_SELF]?do=edit&amp;id=$comment[id]\">Edit</a>] [".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=delete&amp;id=$comment[id]")."] </div><h5 style=\"margin:4px;font-size:small\">$comment[subject]</h5><div style=\"float:right;width:96%;\"><b> $comment[name] </b>(".date($settings['dateformat'],$comment[date]).") <br /> ".$parser->do_parse($comment[comment])."</div> </td> </tr>";

		}
		
		$content .= $admin->add_spacer($evoLANG['comments']);
		$content .= $loop;
		$content = $admin->add_table($content);

		return $content;
	}

	function admin_getcomments($id)
	{
		global $admin,$database,$udb,$_GET,$_SERVER,$evoLANG,$tpl,$script,$settings,$cmt_cache,$parser;
		if ($id == '') return false;
		$validation = $settings['internal_usevalidation'] == 1 ? " WHERE approved = '1'":"";
		
		if (!is_array($cmt_cache))
		{
			$csql = $udb->query("SELECT * FROM $database[article_comment]".$validation);
			while ($crow = $udb->fetch_array($csql))
			{
				$cmt_cache [$crow['artid']][$crow['id']] = $crow;
			}
		}
			
		foreach ($cmt_cache[$id] as $comment)
		{
			$i++;
			$bg = $admin->get_bg($i);

			$loop .= "<tr $bg><td colspan=\"2\"><div style=\"float:left;margin-left:15px;\"><b> $comment[name] </b>(".date($settings['dateformat'],$comment[date]).") <br /> ".$parser->do_parse($comment[comment])."</div> <div style=\"float:right\"> [<a href=\"$_SERVER[PHP_SELF]?do=edit&amp;id=$comment[id]\">Edit</a>] [".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=delete&amp;id=$comment[id]")."] </div></td> </tr>";
		}

		return $loop;
		
	}

	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}
	
	function filter($word,$text)
	{
		$word = trim($word);
		//return str_replace($word,str_repeat("*",strlen($word)),$text);
		return preg_replace("/".$word."/i",str_repeat("*",strlen($word)),$text);
	}

	function process_addarticle()
	{
		//used when add article function is used
	}

}