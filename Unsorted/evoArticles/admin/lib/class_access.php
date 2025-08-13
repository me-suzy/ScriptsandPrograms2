<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class Access
{
	function Access()
	{
		global $database;
		$this->cat_db = $database['article_cat'];
		$this->user_db = $database['article_user'];
		$this->acc_db = $database['article_access'];
	}

	function redirect($loc)
	{
		if ($loc != "")
		{
			header("location: ".$loc);
		}
	}

	function accessmask($userid='')
	{
		global $admin,$udb,$database,$settings,$cat_cache,$_POST,$_SERVER,$_GET,$evoLANG;
		if ($userid == '') return $evoLANG['xid'];
	
		if ( !is_array($cat_cache) )
		{
			$sql = $udb->query("SELECT * FROM ".$this->cat_db);
			while($crow = $udb->fetch_array($sql) )
			{
				$cat_cache[$crow['pid']][$crow['cid']] = $crow;
			}
		}

		if ($_POST['editaccess'])
		{
			if (is_array($cat_cache))
			{
				foreach ($cat_cache as $parent => $cat)
				{
					foreach ($cat as $category)
					{
						if (!in_array($category['cid'],$_POST['cat']) )
						{
							$not .= $category['cid'].",";
						}
					}
				}
			}
			
			if ($not != '')
			{

				$not = substr($not,0,-1);
				$check = $udb->query("SELECT * FROM ".$this->acc_db." WHERE userid='$userid'");
				if ($udb->num_rows($check) == 0)
				{
					$udb->query("INSERT INTO ".$this->acc_db." SET userid='".$userid."',cat_id='".$not."'");
				}
				else
				{
					$udb->query("UPDATE ".$this->acc_db." SET cat_id='".$not."' WHERE userid='".$userid."'");
				}
			}
			else
			{
				$check = $udb->query("SELECT * FROM ".$this->acc_db." WHERE userid='$userid'");
				if ($udb->num_rows($check) == 0)
				{
					//$udb->query("INSERT INTO ".$this->acc_db." SET userid='".$userid."',cat_id=''");
				}
				else
				{
					$udb->query("UPDATE ".$this->acc_db." SET cat_id='' WHERE userid='".$userid."'");
				}
			}



			$this->redirect($_SERVER['PHP_SELF']."?do=access&id=".$userid."&u=1");
			exit;
		}
		$content .= $_GET['u'] == 1 ? $evoLANG['infoupdated']. ' <br /><br />' : '';

		$user = $udb->query_once("SELECT * FROM ".$this->user_db." WHERE id='$userid'");
		$perm = $udb->query_once("SELECT * FROM ".$this->acc_db." WHERE userid='$userid'");
		
		$uperm = explode(",",$perm['cat_id']);

		$content .= $admin->form_start("",$_SERVER['PHP_SELF']."?do=access&amp;id=".$userid);
		$submit = $admin->form_submit("editaccess");
		$user = $udb->query_once("SELECT username FROM ".$this->user_db." WHERE id='".$userid."'");
		$content .= $admin->add_table($admin->add_spacer("Access Mask - ".$user['username']).$this->maketable('0',1,$uperm).$submit,"90%");
		return $content;		
	}

	function maketable($pid='0',$depth=1,$not='')
	{
		global $cat_cache,$udb,$database,$admin;
		
		$admin->row_width = "30%";
		$admin->row_align = "left";

		if ( !is_array($cat_cache) )
		{
			$sql = $udb->query("SELECT * FROM ".$this->cat_db);
			while($crow = $udb->fetch_array($sql) )
			{
				$cat_cache[$crow['pid']][$crow['cid']] = $crow;
			}
		}
		
		$cache = $cat_cache;

		if(!isset($cache[$pid])) return;
		
			while (list($parent,$category) = each($cache[$pid]))
			{					
				$cdepth = str_repeat("__",$depth-1);
				$cdepth = $cdepth != '' ? "|".$cdepth:'';
				
				$chk =  !in_array($category['cid'],$not) ? "CHECKED":"";
			
				$a .= $admin->add_row("<span style=\"font-weight:normal\">".$cdepth."</span> ".$category['name'],"<input type=\"checkbox\" name=\"cat[]\" value=\"".$category['cid']."\" $chk/> <b>Can Post Articles</b>"); 

				$a .= $this->maketable($category['cid'],$depth+1,$not);
		} 
		return $a;
	}
}
?>