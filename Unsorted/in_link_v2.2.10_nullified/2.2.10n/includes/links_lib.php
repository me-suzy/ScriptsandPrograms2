<?php

function print_links($query, $tpl, $lim=0, $start=0, $word="")
{	global $conn, $link_data, $toprate, $tophits, $link_pop,$lu_no_links,$la_no_links,$cat,$use_pick_tpl, $link_top, $admin, $ses,
	$srch_tag1, $srch_tag2;
	$query = ereg_replace("@\!", "&", $query);
	
	$srch_tag1 = "<B>";
	$srch_tag2 = "</B>";

	if($admin==1)
		$l_no_links=$la_no_links;
	else
		$l_no_links=$lu_no_links;

	$rs = &$conn->SelectLimit("SELECT link_rating FROM inl_links ORDER BY link_rating DESC",$link_top,0);
	if ($rs && !$rs->EOF) 
	{	$rs->MoveLast();
		$toprate = $rs->fields[0];
	}
	$rs = &$conn->SelectLimit("SELECT link_hits FROM inl_links ORDER BY link_hits DESC",$link_pop,0);
	if ($rs && !$rs->EOF) 
	{	$rs->MoveLast();
		$tophits = $rs->fields[0];
	}
	
	settype($lim,"integer");
	settype($start,"integer");

	
	if($lim)
		$rs = &$conn->SelectLimit($query,$lim,$start);
	else
		$rs = &$conn->Execute($query);
	
	if ($rs && !$rs->EOF)
	{	do
		{	
			if((/*$tpl=="pend_links" ||*/ $tpl=="validate_links") && $ses["user_perm"]==5)
			{	//get cat info


				$lid=$rs->fields[0];
				$rs2 = &$conn->Execute("SELECT cat_user FROM inl_cats LEFT JOIN inl_lc ON inl_lc.cat_id=inl_cats.cat_id WHERE inl_lc.link_id=$lid");

				if ($rs2 && !$rs2->EOF)
				{	

					if($rs2->fields[0]==$ses["user_id"])
						$show=1;
					else
						$show=0;
				}
				else $show=0;
			}
			elseif ($tpl=="list_mod_links")
			{
				$lid=-$rs->fields[0];
				$rs2 = &$conn->Execute("SELECT link_id FROM inl_lc WHERE link_pend=$lid");

				if ($rs2 && !$rs2->EOF)
					$show=0;
				else
					$show=1;
			}
			else
				$show=1;
			
			
			if($show)
			{	$link_data = $rs->fields;
				if($use_pick_tpl && ($tpl=="list_links" || $tpl=="list_search_links") && $link_data[2]=="1" && $admin!=1) //separate tpl for picked links
					$ret .= parse("list_pick_links");
				else
					$ret .= parse($tpl);
			}
			$rs->MoveNext();
		} 
		while ($rs && !$rs->EOF);
	}
	else //no links or error
		$ret = "<span class='sys-message'>$l_no_links</span>";

	return $ret;

}

function validatelink($cat_list) 
{
	global $form_input_add_link_name, $form_input_add_link_desc, $form_input_add_link_month, $form_input_add_link_day, $form_input_add_link_year, $form_input_add_link_url, $form_input_add_link_user, $form_input_add_link_votes, $form_input_add_link_hits, $form_input_add_link_rating, $form_input_add_link_cust1, $form_input_add_link_cust2, $form_input_add_link_cust3, $form_input_add_link_cust4, $form_input_add_link_cust5, $form_input_add_link_cust6, $form_input_add_link_minute,$form_input_add_link_hour, $form_input_add_link_second,$admin;
	$error = 0;
	$form_input_add_link_rating=$form_input_add_link_rating*1.0000;
	settype($form_input_add_link_month, "integer");
	settype($form_input_add_link_year, "integer");
	settype($form_input_add_link_day, "integer");
	settype($form_input_add_link_hour, "integer");
	settype($form_input_add_link_second, "integer");
	settype($form_input_add_link_minute, "integer");
	settype($form_input_add_link_votes, "integer");
	settype($form_input_add_link_hits, "integer");
	settype($form_input_add_link_rating, "double");
	if ($form_input_add_link_name == "") {
		$error = 1;
	} elseif ((is_int($form_input_add_link_month) == false) || ($form_input_add_link_month > 12)) {
		$error = 3;
	} elseif ((is_int($form_input_add_link_day) == false) || ($form_input_add_link_day > 31)) {
		$error = 4;
	} elseif ((is_int($form_input_add_link_year) == false) || (strlen($form_input_add_link_year) != 4)) {
		$error = 5;
	} elseif (is_int($form_input_add_link_votes) == 0) {
		$error = 7;
	} elseif (is_int($form_input_add_link_hits) == 0) {
		$error = 8;
	} elseif ((is_double($form_input_add_link_rating) == false) || ($form_input_add_link_rating > 5) || ($form_input_add_link_rating < 0)) {
		$error = 9;
	} elseif($admin==1)
	{
		if(!$form_input_add_link_user)
			$error=11;
	}

	$cats=split(",",$cat_list);
	reset($cats);
	$cat_found="";
	while (list ($key, $value) = each($cats)) 
		if($value)
			$cat_found="1";
	if(!$cat_found)
		$error=10;
	
	

	
	$form_input_add_link_name = inl_escape($form_input_add_link_name);
	$form_input_add_link_url  = inl_escape($form_input_add_link_url);
	$form_input_add_link_desc = inl_escape($form_input_add_link_desc);
	$form_input_add_link_image= inl_escape($form_input_add_link_image);
	$form_input_add_link_cust1 = inl_escape($form_input_add_link_cust1);
	$form_input_add_link_cust2 = inl_escape($form_input_add_link_cust2);
	$form_input_add_link_cust3 = inl_escape($form_input_add_link_cust3);
	$form_input_add_link_cust4 = inl_escape($form_input_add_link_cust4);
	$form_input_add_link_cust5 = inl_escape($form_input_add_link_cust5);
	$form_input_add_link_cust6 = inl_escape($form_input_add_link_cust6);

	return $error;
}


//delete link
function delete_link($link_id, $link_cat)
{	//delete from lc, just the instance
	global $conn, $ses;

	if(ereg("pending_links",$ses["destin"])>0){
		global $email_perm, $subject, $from, $reply, $user_data, $email_link, $lib_included;
		if($email_perm[10]==1){
			$query="select * from inl_links where link_id=$link_id";
			$rs = &$conn->Execute("$query");
			$email_link=$rs->fields;
			$query="select * from inl_users where user_id=".$email_link["link_user"];
			$rs = &$conn->Execute("$query"); 
			$user_data=$rs->fields;
			if($lib_included!=1){
				include("../includes/admin_email_lib.php");
				$lib_included=1;
			}

			$body=email_parse("mail_user_link_denied");
			if($user_data["email"])
				@mail($user_data["email"], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
		}		
	}

	$conn->Execute("delete from inl_lc where link_id=$link_id and cat_id=$link_cat");
	$conn->Execute("Delete from inl_fav where  link_id='$link_id'");
	$rs = &$conn->Execute("select count(link_id) from inl_lc where link_id=$link_id");
	if($rs->EOF)
		$rs->fields[0]=1; //db error, do not destroy link

	if($link_cat=="all" || $rs->fields[0]==0)
	{	//take care of custom, if any
		$rs = &$conn->Execute("select link_cust from inl_links where link_id='$link_id'");
		if ($rs && !$rs->EOF)
			if ($rs->fields[0])
				$conn->Execute("delete from inl_custom where cust_id='".$rs->fields[0]."'");

		//delete link itself
		$conn->Execute("delete from inl_links where link_id='$link_id'");

		//clean up references (should already be Ok);
		$conn->Execute("delete from inl_lc where link_id=$link_id");

		$conn->Execute("delete from inl_reviews where rev_link=$link_id");
	}
	
	if($link_cat=="all")
		update_all_link_count();
	else
		update_link_count($link_cat);
}

function moves_link($link_id, $cat_to, $cat_from)
{	global $conn;
	if($cat_from=="all")
	{	//clean up references
		$query="delete from inl_lc where link_id=$link_id";
		$conn->Execute($query);
		//re-create reference for the new cat
		$query="INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ('$link_id','$cat_to','0')";
		$conn->Execute($query);
		update_all_link_count();
	}
	else
	{	//update only current reference
		settype($cat_from,"integer");
		$query="UPDATE inl_lc SET cat_id='$cat_to' WHERE link_id='$link_id' and cat_id='$cat_from'";
		$conn->Execute($query);
		update_link_count($cat_to);
		update_link_count($cat_from);
	}
}

function gotolink($id)
{	global $conn, $admin, $sid, $session_get;
	if($sid && $session_get)
		$att_sid="sid=$sid&";

	$query="select link_url from inl_links where link_id='$id' and link_vis='1'";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
	{	$url=$rs->fields[0];
		$query="update inl_links set link_hits=link_hits+1 where link_id='$id'";
		$conn->Execute($query);
		return $url;
	}
	elseif($admin==1)
		return "navigate.php?$att_sid";
	else
		return "index.php?$att_sid";
}

function get_top_links()
{		global $conn, $link_top, $admin;

	if($admin==1)
		$vis="";
	else
		$vis="AND link_vis=1 ";
	
	$query=" WHERE link_pend!=1 "; 
	
	$rs = &$conn->SelectLimit("SELECT DISTINCT link_rating, inl_lc.link_id FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id = inl_links.link_id WHERE inl_lc.link_pend=0 $vis ORDER BY link_rating DESC",$link_top,0);
	
	if($rs && !$rs->EOF) 
	{	
		$query.="AND (";
		while (TRUE) 
		{	$query.="inl_lc.link_id=".$rs->fields[1]; 
			$rs->MoveNext();
			if(!$rs->EOF)
				$query.=" OR ";
			else
				break;
		}
		$query.=") $vis";
	}
	
	return $query;
}	
function get_pop_links(){
	global $conn, $link_pop, $admin;

	if($admin==1)
		$vis="";
	else
		$vis="AND link_vis=1 ";
	
	$query=" WHERE link_pend!=1 "; 

	$rs = &$conn->SelectLimit("SELECT DISTINCT link_hits, inl_lc.link_id FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id = inl_links.link_id WHERE inl_lc.link_pend=0 $vis ORDER BY link_hits DESC",$link_pop,0);
	if($rs && !$rs->EOF) 
	{	$query.="AND (";
		while (TRUE) 
		{	$query.="inl_lc.link_id=".$rs->fields[1]; 
			$rs->MoveNext();
			if(!$rs->EOF)
				$query.=" OR ";
			else
				break;
		}
		$query.=") $vis";
	}

	return $query;
}
function get_new_links(){
	global $link_new, $admin;
	$cutoffdate = mktime(0,0,0,date("m"),date("d")-$link_new,date("Y"));
	if($admin==1)
		$vis="";
	else
		$vis="AND link_vis='1' ";
	$query=" WHERE link_pend='0' $vis and link_date>='$cutoffdate'"; 
	return $query;
}
function get_pick_links()
{	global $admin;
	if($admin==1)
		$vis="";
	else
		$vis="AND link_vis=1 ";
	$query=" where link_pend=0 $vis and link_pick=1"; 
	return $query;
}

function get_user_links()
{	
	global $ses, $admin;
	if($admin==1)
		$vis="";
	else
		$vis="AND link_vis=1 ";

	if($ses["user_id"]<1) //no user
		$query=" WHERE inl_lc.link_pend=0 $vis AND link_user=-1";
	else
		$query=" WHERE inl_lc.link_pend=0 $vis AND link_user=".$ses["user_id"]; 
	return $query;
}

function get_fav_links()
{	
	global $ses;
	$query=" Left Join inl_fav on inl_fav.link_id=inl_links.link_id where inl_fav.user_id='" .$ses["user_id"]."'"; 
	return $query;
}

function add_new_link($cat_list,$old_id=false) 
{	
	global $conn, $form_input_add_link_name, $form_input_add_link_desc, $month, $day, $year, $form_input_add_link_url, $form_input_add_link_cust1, $form_input_add_link_cust2, $form_input_add_link_cust3, $form_input_add_link_cust4, $form_input_add_link_cust5, $form_input_add_link_cust6, $form_input_add_link_image, $perm_addlink, $ses, $rootperm,$cat, $start,$lu_error_addlink_not_allowed,$lu_error_db,$la_error_db, $admin, $form_input_add_link_vis, $form_input_add_link_pick, $form_input_add_link_hits, $form_input_add_link_votes, $form_input_add_link_rating, $form_input_add_link_month, $form_input_add_link_day, $form_input_add_link_year, $attach, $having, $form_input_add_link_user, $lu_confirm_addlink, $lu_confirm_linkupdate,$old_link;
	
	if($admin==1)
	{	$l_error_db=$la_error_db;
		$templ_exec="navigate.php";
		if($form_input_add_link_pick=="on")
			$form_input_add_link_pick=1;
		else
			$form_input_add_link_pick=0;

		if($form_input_add_link_vis=="on")
			$form_input_add_link_vis=1;
		else
			$form_input_add_link_vis=0;

	}
	else
	{	//set default settings for the user side
		if(!$form_input_add_link_day)
			$form_input_add_link_day=$day;
		if(!$form_input_add_link_month)
			$form_input_add_link_month=$month;
		if(!$form_input_add_link_year)
			$form_input_add_link_year=$year;
		$form_input_add_link_vis = 1;
		$form_input_add_link_pick=0;
		$form_input_add_link_hits=0;
		$form_input_add_link_votes=0;
		$form_input_add_link_rating=0;
		$form_input_add_link_user=$ses["user_id"];
		$templ_exec="index.php";
		$l_error_db=$lu_error_db;
	}
	
	$form_input_add_link_date = mktime(0,0,0,$form_input_add_link_month,$form_input_add_link_day,$form_input_add_link_year);
	$form_input_add_link_name = inl_escape($form_input_add_link_name);
	$form_input_add_link_url  = inl_escape($form_input_add_link_url);
	$form_input_add_link_desc = inl_escape($form_input_add_link_desc);
	$form_input_add_link_image= inl_escape($form_input_add_link_image);
	
	if (($form_input_add_link_cust1) || ($form_input_add_link_cust2) || ($form_input_add_link_cust3) || ($form_input_add_link_cust4) || ($form_input_add_link_cust5) || ($form_input_add_link_cust6))
	{	$form_input_add_link_cust1 = inl_escape($form_input_add_link_cust1);
		$form_input_add_link_cust2 = inl_escape($form_input_add_link_cust2);
		$form_input_add_link_cust3 = inl_escape($form_input_add_link_cust3);
		$form_input_add_link_cust4 = inl_escape($form_input_add_link_cust4);
		$form_input_add_link_cust5 = inl_escape($form_input_add_link_cust5);
		$form_input_add_link_cust6 = inl_escape($form_input_add_link_cust6);

		$query="insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$form_input_add_link_cust1', '$form_input_add_link_cust2', '$form_input_add_link_cust3', '$form_input_add_link_cust4', '$form_input_add_link_cust5', '$form_input_add_link_cust6')";
		$rs=&$conn->Execute($query);
		if($rs)
			$link_cust = $conn->Insert_ID("inl_custom","cust_id");
		else
			$link_cust=0;
	}

	if($old_id)
	{
		$rs=&$conn->Execute("SELECT * FROM inl_links WHERE link_id='$old_id'");
		$old_link = $rs->fields;
		$form_input_add_link_hits=$rs->fields["link_hits"];
		$form_input_add_link_votes=$rs->fields["link_votes"];
		$form_input_add_link_rating=$rs->fields["link_rating"];
		$form_input_add_link_pick=$rs->fields["link_pick"];
		$link_numrevs = $rs->fields["link_numrevs"];
		
	}
	
	$query = "INSERT INTO inl_links (link_name, link_url, link_desc, link_date, link_vis, link_image, link_cust, link_user, link_pick, link_hits, link_votes, link_rating,link_numrevs) VALUES ('$form_input_add_link_name', '$form_input_add_link_url', '$form_input_add_link_desc', '$form_input_add_link_date', '$form_input_add_link_vis', '$form_input_add_link_image', '$link_cust', '$form_input_add_link_user', '$form_input_add_link_pick', '$form_input_add_link_hits', '$form_input_add_link_votes', '$form_input_add_link_rating','$link_numrevs')";

	$rs=&$conn->Execute($query);
	$link_id = $conn->Insert_ID("inl_links","link_id");

	//begin inserting cat entries
	$cats=split(",",$cat_list);
	reset($cats);

	if($old_id)
	{
		$link_pending_yes = -$old_id;
		$link_pending_no  = -$old_id;
	}
	else
	{
		$link_pending_yes = 1;
		$link_pending_no  = 0;
	}

	while (list ($key, $value) = each($cats)) //avoiding holes in the list
	{	if($value && $value!="Home")
		{	//get cat information
			$query="select cat_perm from inl_cats where cat_id=$value";

			$rs = &$conn->Execute($query);

			if($rs && !$rs->EOF)
			{	
				if(check_perm($rs->fields[0],"link")==1 && $admin!=1) //pending
				{	//insert link
					if(!$conn->Execute("insert into inl_lc (link_id, cat_id, link_pend) values ($link_id,$value,$link_pending_yes)"))
						return "$templ_exec?t=error&message=".base64_encode("$l_error_db 2".$conn->ErrorMsg());
				}
				elseif(check_perm($rs->fields[0],"link")==2 && $admin!=1) //direct
				{	
					if(!$conn->Execute("insert into inl_lc (link_id, cat_id, link_pend) values ($link_id,$value,$link_pending_no)"))
						return "$templ_exec?t=error&message=".base64_encode("$l_error_db 3".$conn->ErrorMsg());
				}
				elseif($admin==1)  //admin overrides
				{
					if(!$conn->Execute("insert into inl_lc (link_id, cat_id, link_pend) values ($link_id,$value,0)"))
						return "$templ_exec?t=error&message=".base64_encode("$l_error_db 4".$conn->ErrorMsg());
				}
				else
				{
					$conn->Execute("DELETE FROM inl_links where link_id='$link_id'");
					$conn->Execute("DELETE FROM inl_custom where cust_id='$link_cust'");
					$msg=base64_encode($lu_error_addlink_not_allowed);
					return "index.php?$att_sid"."t=error&message=$msg";
				}
				update_link_count($value);
			}
			else
				return "$templ_exec?t=error&message=".base64_encode("$l_error_db 5".$conn->ErrorMsg());
				//else cat info not retrieved
		}
		elseif($value=="Home")
		{	if(check_perm(-1,"link") && $admin!=1) //pending
			{	//insert link
				if(!$conn->Execute("insert into inl_lc (link_id, cat_id, link_pend) values ($link_id,0,$link_pending_yes)"))
					return "$templ_exec?t=error&message=".base64_encode("$l_error_db 6".$conn->ErrorMsg());
			}
			elseif(check_perm(-1,"link")==2 && $admin!=1) //direct
			{	
				if(!$conn->Execute("insert into inl_lc (link_id, cat_id, link_pend) values ($link_id,$value,$link_pending_no)"))
					return "$templ_exec?t=error&message=".base64_encode("$l_error_db 7".$conn->ErrorMsg());
			}
			elseif($admin==1) //admin overrides
				if(!$conn->Execute("insert into inl_lc (link_id, cat_id, link_pend) values ($link_id,0,0)"))
					return "$templ_exec?t=error&message=".base64_encode("$l_error_db 8".$conn->ErrorMsg());
		}
	}
	
	if($admin!=1)
	{
		global $email_perm, $subject, $from, $reply, $user_data, $email_link;
		$query="select * from inl_links where link_id=$link_id";
		$rs = &$conn->Execute("$query");
		$email_link = $rs->fields;
		include("includes/admin_email_lib.php");
		$query="select first,last,email,user_name from inl_users where user_id=".$email_link[5];
		$rs = &$conn->Execute("$query");
		$user_data = $rs->fields;
	}

	if($email_perm[1]==1 && $admin!=1)
	{
		if ( !$old_id ) $mail_body = "mail_admin_new_link"; else $mail_body = "mail_admin_mod_link";
		$body = email_parse($mail_body);
		if($u = get_admin_emails())
		{
			/*
				echo "from = ".$from."<br>";
				echo "reply = ".$reply."<br>";
				echo "subject = ".$subject."<br>";
				echo "email = ".$u."<br>";
				echo "body = ".$body."<br>"; 
			*/
			@mail($u, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
		}
	}
	if($email_perm[8]==1 && $admin!=1)
	{
		if ( !$old_id ) $mail_body = "mail_user_new_link"; else $mail_body = "mail_user_mod_link";
		$body = email_parse($mail_body);
		if($user_data["email"])
		{
			/*
				echo "from = ".$from."<br>";
				echo "reply = ".$reply."<br>";
				echo "subject = ".$subject."<br>";
				echo "email = ".$user_data[0]."<br>";
				echo "body = ".$body."<br>";
			*/
			@mail($user_data["email"], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
		}
	}
	
	/*
	if($email_perm[1]==1 && $admin!=1)
	{
		$body=email_parse("mail_admin_new_link");
		if($u=get_admin_emails())
			@mail($u, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
	}
	if($email_perm[8]==1 && $admin!=1){
		$body=email_parse("mail_user_new_link");
		if($user_data[0])
			@mail($user_data[0], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
	}
	*/
	//end inserting cat entries
	
	if ($admin==1)
	{
		//$attach=ereg_replace("\|","&",$attach);
		//return "$templ_exec?$attach&having=$having";
		return "$templ_exec?".$ses["destin"]."&having=$having";
	}
	else
	{	
		if ( !$old_id ) $txt = $lu_confirm_addlink; else $txt = $lu_confirm_linkupdate;
		$message = base64_encode($txt);		
		//$message=base64_encode($lu_confirm_addlink);
		return "$templ_exec?attach=$attach&having=$having&t=confirm&message=$message";
	}
}

function save_link($id,$cat_list) 
{	
	global $conn, $form_input_add_link_name, $form_input_add_link_desc, $month, $day, $year, $form_input_add_link_url, $form_input_add_link_cust1, $form_input_add_link_cust2, $form_input_add_link_cust3, $form_input_add_link_cust4, $form_input_add_link_cust5, $form_input_add_link_cust6, $form_input_add_link_image, $perm_addlink, $ses, $rootperm,$cat, $start,$lu_error_addlink_not_allowed,$lu_error_db,$la_error_db, $admin, $form_input_add_link_vis, $form_input_add_link_pick, $form_input_add_link_hits, $form_input_add_link_votes, $form_input_add_link_rating, $form_input_add_link_month, $form_input_add_link_day, $form_input_add_link_year,$form_input_add_link_minute,$form_input_add_link_hour, $form_input_add_link_second, $attach, $having, $old_email, $email_link, $old_link, $form_input_add_link_user, $lu_confirm_linkupdate;
	
//echo "executing 'save_link',link_id=$id<br>";
//$conn->debug=true;

	$query="select * from inl_links where link_id=$id";
	$rs = &$conn->Execute($query);
	$old_link = $rs->fields;

	if($admin==1)
	{	$l_error_db="message=".base64_encode($la_error_db);
		if($attach=="duplicates")
			$templ_exec="duplicatelinks.php";
		else
			$templ_exec="navigate.php";
	}
	else
	{	$templ_exec="index.php";
		$l_error_db="message=".base64_encode($lu_error_db);
	}

	$rs = &$conn->Execute("select link_user, link_cust from inl_links where link_id=$id");
	if($rs && !$rs->EOF)
		$link_data = $rs->fields;
	else
		return "$templ_exec?t=error&$l_error_db";

	if($admin==1)
	{	if($form_input_add_link_pick=="on")
			$form_input_add_link_pick=1;
		else
			$form_input_add_link_pick=0;
		if($form_input_add_link_vis=="on")
			$form_input_add_link_vis=1;
		else
			$form_input_add_link_vis=0;

		$form_input_add_link_date = mktime($form_input_add_link_hour,$form_input_add_link_minute,$form_input_add_link_second,$form_input_add_link_month,$form_input_add_link_day,$form_input_add_link_year);
	}
	else
	{	if($ses["user_id"]<1)
			return "index.php?t=login&attach=$attach&having=$having";
		elseif($ses["user_id"]!=$link_data[0])
		{	$message=base64_encode($lu_error_wrong_user);
			return "index.php?t=error&message=$message";
		}
	}
		
	$form_input_add_link_name = inl_escape($form_input_add_link_name);
	$form_input_add_link_url  = inl_escape($form_input_add_link_url);
	$form_input_add_link_desc = inl_escape($form_input_add_link_desc);
	$form_input_add_link_image= inl_escape($form_input_add_link_image);
	
	if ($form_input_add_link_cust1 || $form_input_add_link_cust2 || $form_input_add_link_cust3 || $form_input_add_link_cust4 || $form_input_add_link_cust5 || $form_input_add_link_cust6)
	{	$form_input_add_link_cust1 = inl_escape($form_input_add_link_cust1);
		$form_input_add_link_cust2 = inl_escape($form_input_add_link_cust2);
		$form_input_add_link_cust3 = inl_escape($form_input_add_link_cust3);
		$form_input_add_link_cust4 = inl_escape($form_input_add_link_cust4);
		$form_input_add_link_cust5 = inl_escape($form_input_add_link_cust5);
		$form_input_add_link_cust6 = inl_escape($form_input_add_link_cust6);

		if(!$link_data[1]) //custom fields do not yet exist
		{	$query="insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$form_input_add_link_cust1', '$form_input_add_link_cust2', '$form_input_add_link_cust3', '$form_input_add_link_cust4', '$form_input_add_link_cust5', '$form_input_add_link_cust6')";
			$conn->Execute($query);
			$link_data[1] = $conn->Insert_ID("inl_custom","cust_id");
		}
		else
		{	$query="update inl_custom set cust1='$form_input_add_link_cust1', cust2='$form_input_add_link_cust2', cust3='$form_input_add_link_cust3', cust4='$form_input_add_link_cust4', cust5='$form_input_add_link_cust5', cust6='$form_input_add_link_cust6' where cust_id=" . $link_data[1];
		
			if (!$conn->Execute($query)) 
				return "$templ_exec?t=error&$l_error_db".base64_encode($conn->ErrorMsg());
		}
	}
	elseif($link_data[1])
	{	$conn->Execute("DELETE FROM inl_custom WHERE cust_id=$link_data[1]"); //delete custom fields if they are no longer used!
		$link_data[1]=0;
	}

	if($admin==1)
		$query="UPDATE inl_links SET link_name='$form_input_add_link_name', link_url='$form_input_add_link_url', link_desc='$form_input_add_link_desc', link_date=$form_input_add_link_date, link_vis=$form_input_add_link_vis, link_image='$form_input_add_link_image', link_cust=$link_data[1], link_user=$form_input_add_link_user, link_pick=$form_input_add_link_pick, link_hits=$form_input_add_link_hits, link_votes=$form_input_add_link_votes, link_rating=$form_input_add_link_rating WHERE link_id=$id";
	else  //update link and reset pending flags
	{	$query="UPDATE inl_links SET link_name='$form_input_add_link_name', link_url='$form_input_add_link_url',";
		$query.="link_desc='$form_input_add_link_desc', link_image='$form_input_add_link_image',";
		$query.="link_cust=$link_data[1] WHERE link_id=$id";
		
		//permissions by category
		$qu="SELECT distinct cat_perm, inl_cats.cat_id from inl_lc, inl_cats WHERE link_id=$id AND inl_lc.cat_id=inl_cats.cat_id"; //get all cats
		$rs = &$conn->Execute($qu);
		while (!$rs->EOF) 
		{	$conn->Execute("DELETE FROM inl_lc WHERE link_id=$id AND cat_id=".$rs->fields[1]); //kill all cat references
			if(check_perm($rs->fields[0],"links")==1)
				$conn->Execute("INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ($id,".$rs->fields[1].",1)");
			else
				$conn->Execute("INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ($id,".$rs->fields[1].",0)");
			update_link_count($rs->fields[1]);
			$rs->MoveNext(); 
		}
	}

	if ($conn->Execute($query) === false) 
		return "$templ_exec?t=error&$l_error_db".base64_encode($conn->ErrorMsg());
	
	if($admin==1)
	{	if(ereg("pending_link",$ses[destin])>0) //in pending
		{	$rs=&$conn->Execute("SELECT link_pend FROM inl_lc WHERE link_id=$id AND link_pend<0");
			if($rs && !$rs->EOF)
				$pending = $rs->fields[0];
			else
				$pending = 1;
		}
		else
			$pending = 0;
		settype($pending,"integer");
		
		//delete old cat references
		$conn->Execute("DELETE FROM inl_lc WHERE link_id=$id");
		//begin inserting cat entries
		$cats=split(",",$cat_list);
		reset($cats);
		
		while (list ($key, $value) = each($cats)) //avoiding holes in the list
		{	if($value && $value!="Home")
			{	if ($conn->Execute("INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ($id,$value,$pending)") === false)
					return "$templ_exec?t=error&$l_error_db".base64_encode($conn->ErrorMsg());
				update_link_count($value);
			}
			elseif($value=="Home")
				if ($conn->Execute("INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ($id,0,$pending)") === false)
					return "$templ_exec?t=error&$l_error_db".base64_encode($conn->ErrorMsg());
		}
		//end inserting cat entries
	}
	if($admin!=1){
	global $email_perm, $subject, $from, $reply, $user_data, $email_link;
	$query="select * from inl_links where link_id=$id";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
		$email_link=$rs->fields;
	$query="select * from inl_users where user_id=".$ses["user_id"];
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
		$user_data=$rs->fields;
	include("includes/admin_email_lib.php");
	}
	if($email_perm[2]==1 && $admin!=1){
		$body=email_parse("mail_admin_mod_link");
		if($u=get_admin_emails())
			@mail($u, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
	}
	if($email_perm[11]==1 && $admin!=1){
		$body=email_parse("mail_user_mod_link");
		@mail($user_data["email"], $subject, $body, "From:$from\r\nReply-to:$reply");
	}

	#$attach=ereg_replace("\|","&",$attach);;
	if ($admin==1)
	{
		//$attach=ereg_replace("\|","&",$attach);
		//return "$templ_exec?$attach&having=$having";
		return "$templ_exec?".$ses["destin"]."&having=$having";
	}
	else
	{	$message=base64_encode($lu_confirm_linkupdate);
		return "$templ_exec?t=confirm&message=$message&attach=$attach&having=$having";
	}		
}

//for edit link purposes only
function get_link($id)
{	global $ses, $conn, $form_input_add_link_name, $form_input_add_link_url, $form_input_add_link_image, $form_input_add_link_desc, $form_input_add_link_cust1, $form_input_add_link_cust2, $form_input_add_link_cust3, $form_input_add_link_cust4, $form_input_add_link_cust5, $form_input_add_link_cust6,  $cat_list,$admin,$form_input_add_link_vis,$form_input_add_link_pick, $form_input_add_link_user, $form_input_add_link_day, $form_input_add_link_month, $form_input_add_link_year, $form_input_add_link_hits, $form_input_add_link_votes, $form_input_add_link_rating, $attach, $form_input_add_link_minute,$form_input_add_link_hour, $form_input_add_link_second;
	$query="Select link_name, link_url, link_image, link_desc, link_vis, link_pick, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_hits, link_votes, link_rating, link_date from inl_links left join inl_custom on link_cust=cust_id where link_id=$id";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) {
		$form_input_add_link_name=stripslashes($rs->fields[0]);
		$form_input_add_link_url=stripslashes($rs->fields[1]);
		$form_input_add_link_image=stripslashes($rs->fields[2]);
		$form_input_add_link_desc=stripslashes($rs->fields[3]);
		$form_input_add_link_vis=$rs->fields[4];
		$form_input_add_link_pick=$rs->fields[5];
		$form_input_add_link_hits=$rs->fields[13];
		$form_input_add_link_votes=$rs->fields[14];
		$form_input_add_link_rating=$rs->fields[15];
		$form_input_add_link_day=date("j",$rs->fields[16]);
		$form_input_add_link_month=date("n",$rs->fields[16]);
		$form_input_add_link_year=date("Y",$rs->fields[16]);
		$form_input_add_link_hour=date("H",$rs->fields[16]);
		$form_input_add_link_minute=date("i",$rs->fields[16]);
		$form_input_add_link_second=date("s",$rs->fields[16]);
		$form_input_add_link_cust1=stripslashes($rs->fields[6]);
		$form_input_add_link_cust2=stripslashes($rs->fields[7]);
		$form_input_add_link_cust3=stripslashes($rs->fields[8]);
		$form_input_add_link_cust4=stripslashes($rs->fields[9]);
		$form_input_add_link_cust5=stripslashes($rs->fields[10]);
		$form_input_add_link_cust6=stripslashes($rs->fields[11]);
		$form_input_add_link_user=stripslashes($rs->fields[12]);
		
	}
	if($admin==1)
	{	$cur_cat=0;
		$cat_list="";
		$u=" AND link_pend=0";
		//if(ereg("pending_link",$attach)>0){$u="";}
		if(ereg("pending_link",$ses[destin])>0){$u="";}
		$rs = &$conn->Execute("SELECT cat_id FROM inl_lc WHERE link_id=$id$u");
		while($rs && !$rs->EOF)
		{	if(!$rs->fields[0])
				$rs->fields[0]="Home";
			$cat_list.=$rs->fields[0] . ",";
			$rs->MoveNext();
		}
	}
}

//copy of the function from cat_lib
//number of cats and links in the category and all parents, bottom up
function update_link_count($cat) //assumes that parent to be updated is sent
{	global $conn;
	if($cat) //not root
	{	//select all children of the parent, assumes they are fixed
		$numcats=0;
		$numlinks=0;
		$rs = &$conn->Execute("select cat_id, cat_cats, cat_links from inl_cats where cat_sub=$cat and cat_pend=0");
		while($rs && !$rs->EOF) //add up all of the children
		{	$numcats+=$rs->fields[1];
			$numcats+=1; //update cats of itself
			$numlinks+=$rs->fields[2];
			$rs->MoveNext();
		}
		//links of itself
		$rs = &$conn->Execute("select count(link_id) from inl_lc where cat_id=$cat and link_pend=0");
		if($rs && !$rs->EOF) 
			$numlinks+=$rs->fields[0];

		//update number of sub cats for current cat
		$conn->Execute("update inl_cats set cat_cats=$numcats, cat_links=$numlinks where cat_id=$cat");
		//get parent & recurse
		$rs = &$conn->Execute("select cat_sub from inl_cats where cat_id=$cat and cat_pend=0");
		if($rs && !$rs->EOF)
			update_link_count($rs->fields[0]);
	}
}

//same as in cats, top down
function update_all_link_count($sub=0) 
{	global $conn;
	$query="select cat_id from inl_cats where cat_sub=$sub and cat_pend=0";
	$val=0;
	//all childern and their links
	$rs = &$conn->Execute($query);
	
	while ($rs && !$rs->EOF) 
	{	$t=update_all_link_count($rs->fields[0]);
		$val+=$t;
		$query="Update inl_cats set cat_links=$t where cat_id = ".$rs->fields[0];
		$conn->Execute($query);

		$rs->MoveNext();
	}

	//its own links
	$query="select count(link_id) from inl_lc where cat_id=$sub and link_pend=0";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
	$val+=$rs->fields[0];

	return $val; 
}

function num_to_image($n)
{	if($n<0.25 || $n>5)
		return "0";
	if($n>=0.25 && $n<0.75)
		return "0_half";
	if($n>=0.75 && $n<1.25)
		return "1";
	if($n>=1.25 && $n<1.75)
		return "1_half";
	if($n>=1.75 && $n<2.25)
		return "2";
	if($n>=2.25 && $n<2.75)
		return "2_half";
	if($n>=2.75 && $n<3.25)
		return "3";
	if($n>=3.25 && $n<3.75)
		return "3_half";
	if($n>=3.75 && $n<4.25)
		return "4";
	if($n>=4.25 && $n<4.75)
		return "4_half";
	if($n>=4.75 && $n<=5)
		return "5";
}


//user link data - checks for pending and visible
function get_link_data($link_id)
{	global $conn;
	$query="SELECT inl_links.link_id, link_name, link_pick, link_desc, link_date, link_hits, link_rating, link_votes, link_numrevs, link_image, cust1, cust2, cust3, cust4, cust5, cust6, link_user, link_url FROM inl_links LEFT JOIN inl_lc ON inl_lc.link_id=inl_links.link_id LEFT JOIN inl_custom ON inl_links.link_cust=inl_custom.cust_id WHERE inl_links.link_id=$link_id AND link_pend=0 AND link_vis=1";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF)
		$link_data = $rs->fields;
	return $link_data;
}

function masslinkapprove($pendlinks){
	global $conn, $action;
	
	while (list ($link_id, $val) = each ($pendlinks)) 
	{

		$rs_cat = &$conn->Execute("SELECT cat_id FROM inl_lc WHERE link_id='$link_id'");

		//while (list ($link_id, $val) = each ($pendlinks)) {
		while($rs_cat && !$rs_cat->EOF) 
		{
			$val=$rs_cat->fields[0];

			$rs_id = &$conn->Execute("SELECT link_pend FROM inl_lc WHERE link_id='$link_id'");
			
			if($rs_id && !$rs_id->EOF) 
			{
				$lid=$rs_id->fields[0];
				if($lid < 0)
				{
					$lid=-$lid;
					
					$rs = &$conn->Execute("select link_cust from inl_links where link_id='$lid'");
					if ($rs && !$rs->EOF)
						if ($rs->fields[0])
							$conn->Execute("delete from inl_custom where cust_id='".$rs->fields[0]."'");

					//delete link itself
					$conn->Execute("delete from inl_links where link_id='$lid'");

					//clean up references (should already be Ok);
					$conn->Execute("delete from inl_lc where link_id=$lid");

					$conn->Execute("UPDATE inl_links SET link_id='$lid' WHERE link_id='$link_id'");			
					$conn->Execute("UPDATE inl_lc SET link_id='$lid' WHERE link_id='$link_id'");			

					$link_id = $lid;
				}			
			}

			$conn->Execute("update inl_lc set link_pend=0 where link_id='$link_id' and cat_id=$val");
			update_cat_count($val);

			global $la_button_approve_selected;

			if($action=="approvelink" || $action==$la_button_approve_selected){
				global $email_perm, $subject, $from, $reply, $user_data, $email_link;
				if($email_perm[9]==1){
					$query="select * from inl_links where link_id=$link_id";
					$rs = &$conn->Execute("$query");
					$email_link = $rs->fields;
					$query="select * from inl_users where user_id=".$email_link[5];
					$rs = &$conn->Execute("$query");
					$user_data = $rs->fields;
					include("../includes/admin_email_lib.php");
					$body=email_parse("mail_user_link_approved");

					if($user_data[5])
						@mail($user_data[5], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
				}		
			}
		
			$rs_cat->MoveNext();
		}
	}
}

function masslinkdelete($pendlinks)
{
	global $conn;
	
	while (list ($link_id, $val) = each ($pendlinks)) 
	{
		$rs = &$conn->Execute("SELECT cat_id FROM inl_lc WHERE link_id='$link_id'");

		while($rs && !$rs->EOF) 
		{
			$val=$rs->fields[0];
			
			delete_link($link_id,$val);
			
			$rs->MoveNext();
		}
	}
}


function linkpath($cat) {
	global $conn, $la_navbar_seperator, $lu_navbar_seperator, $admin, $lu_nav_home,$la_nav_home;
	$catb = $cat;
	if ($admin == 1)
	{	$sep = $la_navbar_seperator;
		$home=$la_nav_home;
	}else
	{	$sep = $lu_navbar_seperator;
		$home=$lu_nav_home;;
	}
	$cloc = "";
	if ($cat != "0") 
	{
		do {
		$rs = &$conn->Execute("select cat_name, cat_sub from inl_cats where cat_id='$catb'");
		if (!$rs->EOF && $rs) 
		{
			$cloc =" $sep " . $rs->fields[0] . $cloc;
			$catb = $rs->fields[1];
		}
		} while ($catb != "0" && !$rs->EOF && $rs);
	$cloc = $home . $cloc;
	} else {
	$cloc = $home;
	}
	return $cloc;
}
?>