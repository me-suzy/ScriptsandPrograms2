<?
	if($src == "logout"){
	 	setcookie("auth");
		header("Location: ../");
	}


	if($cid == "")
		$cid = $dcid;
	else{
	 	setcookie("dcid","$cid");
		$dcid = $cid;
	}

	include "../tpl/clients_top.ihtml";

	switch($src){
		case add_keyword:
			if($act == create)
			{
				if($k_text == "") $es = "Please, enter your keywords !";
				if($k_cost == "" ) $es = "Please, enter your keyword bid !";

				if($es == "")
				{
					$status = 1;
					strip_tags($k_text, "");

					q("insert into keywords (cid, keyword, ppc) values('$cid', '$k_text', '$k_cost')");

						include "my_campaigns";								}
				else
				{
					include "add_keyword";
				}
			}
			else if($act == modify)
			{
				if($k_text == "") $es = "Please, enter your keywords !";
				if($k_cost == "" ) $es = "Please, enter your keyword bid !";

				if($es == "")
				{
					$status = 1;
					strip_tags($b_text, "");

					q("update keywords set keyword='$k_text' where id='$id'");
					q("update keywords set ppc='$k_cost' where id='$id'");

					include "my_campaigns";	
				}
				else
				{
					include "add_keyword";
				}
			}
			else
			{
				if($act == mod)
				{
					$act = modify;
				}
				include "add_keyword";
			}
			break;

		case add_banner:
			if($act == create)
			{
				if($burl == "" && $type != "Text") $es = "Please, enter your banner source URL!";
				if($b_text == "" && $type == "Text") $es = "Please, enter your text banner source!";

				if($es == "")
				{
					$status = (int)!$policy[ approval_required ];
					strip_tags($b_text, "<b><i><u>");

					q("insert into banners (cid, burl, type, status, isize, btext, weight) values('$cid', '$burl', '$type', '$status', '$isize', '$b_text','$weight')");

					if($status)
					{
						include "my_campaigns";					}
					else
					{
						include "add_banner_ok";
					}
				}
				else
				{
					include "add_banner";
				}
			}
			else if($act == modify)
			{
				if($burl == "" && $type != "Text") $es = "Please, enter your banner source URL!";
				if($b_text == "" && $type == "Text") $es = "Please, enter your text banner source!";

				if($es == "")
				{
					$status = (int)!$policy[ approval_required ];
					strip_tags($b_text, "<b><i><u>");

					q("update banners set burl='$burl' where id='$id'");
					q("update banners set btext='$b_text' where id='$id'");
					q("update banners set type='$type' where id='$id'");
					q("update banners set isize='$isize' where id='$id'");
					q("update banners set status='$status' where id='$id'");
					q("update banners set weight='$weight' where id='$id'");

					if($status)
					{
						include "my_campaigns";					}
					else
					{
						include "add_banner_ok";
					}
				}
				else
				{
					include "add_banner";
				}
			}
			else
			{
				if($act == mod)
				{
					$act = modify;
				}
				include "add_banner";
			}
			break;

		case "cr_campaign":
			if($act == "create")
			{
				if($url == "") $es = "Enter web site's URL!";
				if($title == "") $es = "Enter web site's title!";
				if($es == "")
				{
					$rr = q("select approval_required as a from members_policy where user_id='$user[id]'");

					if(!e($rr))
					{
						$ccc = f($rr);
						if(!$ccc[a])
						{
							q("insert into campaigns values('0','$user[id]','$url','$group_id','$ikeys','$title','1','".strtotime(date("d M Y H:i:s"))."')");
							$new_id = f(q("select id from campaigns where user_id='$user[id]' and url='$url' and ikeys='$ikeys' and status='1'"));

							$dcr = def_credits("../");
							if($new_id[id] != "")
							{

								if(sizeof($sgrp))
								{
									while(list($k,$v) = each($sgrp))
									{
										q("insert into camp_groups values('0','$new_id[id]','$v')");
									}
								}

								if(e(q("select id from prev where cid='$new_id[id]'")))
								{
//									q("insert into prev values('0','$new_id[id]','$dcr[1]')");
									q("insert into prev values('0','$new_id[id]','0')");				}
							}

							include my_campaigns;
							break;
						}
					}

					q("insert into campaigns values('0','$user[id]','$url','$group_id','$ikeys','$title','0','".strtotime(date("d M Y H:i:s"))."')");
					$new_id = f(q("select id from campaigns where user_id='$user[id]' and url='$url' and ikeys='$ikeys' and status='0'"));

					$dcr = def_credits("../");
					if($new_id[id] != "")
					{
						if(sizeof($sgrp))
						{
							while(list($k,$v) = each($sgrp))
							{
								q("insert into camp_groups values('0','$new_id[id]','$v')");
							}
						}
					}

					$can_do = 1;
				}
			}
			if(!$can_do)
			{
				include new_campaign;
			}
			else
			{
				include new_campaign_ok;
			}
			break;
		case "tools":
			include tools;
			break;
		case "":
		case "default":
			include my_campaigns;
			break;
		case "view_campaigns":
			include my_campaigns;
			break;
		case "edit_campaign":
			if($act == "edit" && $id!=""){
				if($url == "") $es = "Enter web site's URL!";
				if($title == "") $es = "Enter web site's title!";
				if($es == ""){
					q("update campaigns set url='$url' where id='$id'");
					q("update campaigns set title='$title' where id='$id'");
					q("update campaigns set group_id='$group_id' where id='$id'");
					q("update campaigns set ikeys='$ikeys' where id='$id'");

					if($policy[ approval_required ])
					{
						q("update campaigns set status='0' where id='$id'");
					}

					q("delete from camp_groups where cid='$id'");
					if(sizeof($sgrp)){
						while(list($k,$v) = each($sgrp))
						q("insert into camp_groups values('0','$id','$v')");
					}
					$can_do = 1;
				}
			}

			if(!$can_do)
			{
				 include edit_campaign;
			}
			else
			{
				if($policy[ approval_required ])
				{
				 	include edit_campaign_ok;
				}
				else
				{
					include my_campaigns;
				}
			}
			break;
		 default:
			include $src;

	}

	include "../tpl/clients_bottom.ihtml";

	d($db);
?>
