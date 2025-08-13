<?php

include("includes/config.php");
include_once("includes/search_lib.php");


$destin="index.php?cat=$cat";

switch($action)
{
	case "suggest":
		######-Suggest the site to a friend-######		
		if($form_input_suggest_email)
		{	include("includes/admin_email_lib.php");
			$body=email_parse("mail_suggest_site");
			@mail($form_input_suggest_email, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
			$message=base64_encode($lu_confirm_suggest_site);
			$destin = "index.php?t=confirm&message=$message&go=index.php";
		}
		else
		{	$message=base64_encode("$lu_invalid_entry:$lu_email");
			$destin = "index.php?t=error&message=$message";
		}
		break;

	case "add_review":
		######-Add review-######
		if(check_perm(-1,"review"))
		{	
			$ip=$REMOTE_ADDR;
			$expired=time()-86400*$review_expiration;
			$query="delete from inl_votes where stamp<$expired and rev=1";
			$conn->Execute($query);

			if(!isset($revlink[$id]))
			{	$query="Select stamp from inl_votes where vote_ip='$ip' and vote_link='$id' and rev=1";
				$rs = &$conn->Execute($query);
				 if ($rs && !$rs->EOF)
				{	if($rs->fields[0]>0)
					{	$message=base64_encode($lu_error_addreview_not_allowed);
						$destin="index.php?t=error&message=$message";
					}
						break;
				}
			}
			else
			{	$message=base64_encode($lu_error_addreview_not_allowed);
				$destin="index.php?t=error&message=$message";
				break;
			}
				
			if(!$form_input_add_review_text)
			{	$message=base64_encode($lu_error_review_not_filled);
				$destin="index.php?t=error&message=$message";
				break;
			}
			if(check_perm(-1,"review")==1)
				$review_pend=1;
			else
				$review_pend=0;
		
			$rev_date = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$form_input_add_review_text = inl_escape($form_input_add_review_text);
			$query="INSERT INTO inl_reviews (rev_link, rev_text, rev_user, rev_date, rev_pend) VALUES ('$id', '$form_input_add_review_text', '".$ses["user_id"]."', '$rev_date', '$review_pend')";
			if ($conn->Execute($query) == true) {
				$lifetime=time()+86400*$review_expiration;
				@setcookie("revlink[$id]","1",$lifetime);
				$now=time();
				$query="INSERT INTO inl_votes (stamp, vote_ip, vote_link, rev) VALUES ('$now', '$ip', '$id', '1')";
				$conn->Execute($query);
			}


			global $email_perm, $subject, $from, $reply, $user_data, $email_link;
			if(($email_perm[4]==1 || $email_perm[14]==1) && $admin!=1)
			{
				include("includes/admin_email_lib.php");
				$query="SELECT * FROM inl_links WHERE link_id=$id";
				$rs = &$conn->Execute($query);
				if ($rs && !$rs->EOF)
					$email_link=$rs->fields; 
				if($email_perm[4]==1)
				{
					$query="SELECT * FROM inl_users WHERE user_id=".$ses["user_id"];
					$rs = &$conn->Execute($query);
					if ($rs && !$rs->EOF)
						$user_data=$rs->fields;
					$body=email_parse("mail_admin_new_review");
					if($u=get_admin_emails())
						@mail($u, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
				}
				if($email_perm[14]==1)
				{
					$query="SELECT email FROM inl_users WHERE user_id=".$email_link["link_user"];
					$rs = &$conn->Execute($query);
					if ($rs && !$rs->EOF)
						$u=$rs->fields[0];
					$query="SELECT * FROM inl_users WHERE user_id=".$ses["user_id"];
					$rs = &$conn->Execute($query);
					if ($rs && !$rs->EOF)
						$user_data=$rs->fields;
					$body=email_parse("mail_user_new_review");
					
						@mail($u, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
				}
				
				
			}
			

			//count number of reviews for that link
			if($review_pend==0)
			{	$query="SELECT count(rev_id) FROM inl_reviews WHERE rev_link=$id and rev_pend=0";
				$rs = &$conn->Execute($query);
				if ($rs && !$rs->EOF)
				{	$query="UPDATE inl_links SET link_numrevs='".$rs->fields[0]."' WHERE link_id=$id";
					$conn->Execute($query);
					$message=base64_encode($lu_confirm_addreview);
					$destin="index.php?t=confirm&message=$message";
				}
				else
				{	$message=base64_encode($lu_error_db);
					$destin="index.php?t=error&message=$message";//database error
				}

			}
			else
			{		//$ses["destin"]="../../index.php?t=sub_pages&cat=$cat"; //CyKuH Fix
					$message=base64_encode($lu_confirm_addreview);
					$destin="index.php?t=confirm&message=$message";
			}

		}
		else //display error, should not happen, since they wouldn't get to this point if perms are wrong
		{	$message=base64_encode($lu_error_addreview_not_allowed);
			$destin="index.php?t=error&message=$message";
		}
		
		break;
	case "login":
		######-Login-######

		if(strlen($form_input_login_username)>0) 
		{	$res=login($form_input_login_username, $form_input_login_password);
			if($res==1)
				$destin="index.php?$attach";
			else
			{	$message=base64_encode($lu_login_incorrect);
				$destin="index.php?t=error&message=$message";
			}
		}
		elseif ($perm = check_perm(-1,"user")) 
		{
			if($perm == 3)
					$destin="index.php?$att_sid"."t=rregistration&cat=$cat";
				else
					$destin="index.php?$att_sid"."t=registration&cat=$cat";
		}
		else
		{	$message=base64_encode($lu_error_user_not_allowed);
			$destin="index.php?t=error&message=$message";
		}
		break;

	case "logout":
		######-Logout-######
		logout();
		$destin="index.php";
		break;

	case "registerr":
		######-Register New User with random password and EMail confirmation-######
		
		$random_password = true;

	case "register":

		######-Register New User-######
	
		//Special characters CyKuH Fix
		$form_input_registration_user_name = inl_escape($form_input_registration_user_name);
		$form_input_registration_user_pass = inl_escape($form_input_registration_user_pass);
		$form_input_registration_re_pass = inl_escape($form_input_registration_re_pass);
		$form_input_registration_first = inl_escape($form_input_registration_first);
		$form_input_registration_last = inl_escape($form_input_registration_last);
		$form_input_registration_email = inl_escape($form_input_registration_email);	
		if (get_magic_quotes_gpc())
		{		
			$form_input_registration_user_name=stripslashes($form_input_registration_user_name);
			$form_input_registration_first=stripslashes($form_input_registration_first);
			$form_input_registration_last=stripslashes($form_input_registration_last);
			$form_input_registration_email=stripslashes($form_input_registration_email);
			$form_input_registration_cust1=stripslashes($form_input_registration_cust1);
			$form_input_registration_cust2=stripslashes($form_input_registration_cust2);
			$form_input_registration_cust3=stripslashes($form_input_registration_cust3);
			$form_input_registration_cust4=stripslashes($form_input_registration_cust4);
			$form_input_registration_cust5=stripslashes($form_input_registration_cust5);
			$form_input_registration_cust6=stripslashes($form_input_registration_cust6);
		}

		$vals="form_input_registration_user_name=". rawurlencode($form_input_registration_user_name); 
		$vals.="&form_input_registration_first=". rawurlencode($form_input_registration_first);
		$vals.="&form_input_registration_last=". rawurlencode($form_input_registration_last);
		$vals.="&form_input_registration_email=". rawurlencode($form_input_registration_email);
		$vals.="&form_input_registration_cust1=". rawurlencode($form_input_registration_cust1);
		$vals.="&form_input_registration_cust2=". rawurlencode($form_input_registration_cust2);
		$vals.="&form_input_registration_cust3=". rawurlencode($form_input_registration_cust3);
		$vals.="&form_input_registration_cust4=". rawurlencode($form_input_registration_cust4);
		$vals.="&form_input_registration_cust5=". rawurlencode($form_input_registration_cust5);
		$vals.="&form_input_registration_cust6=". rawurlencode($form_input_registration_cust6);
		$valid=1;
		
		//validate required fields

		if(strlen($form_input_registration_user_name)<3){$ret="err_user_name=$lu_invalid_entry&"; $valid=0;}
		
		if(!$random_password)
		{
			if(strlen($form_input_registration_user_pass)<3 || ereg(" ",$form_input_registration_user_pass)>0){$ret=$ret."err_user_pass=$lu_invalid_entry&"; $valid=0;}
		
			if(strlen($form_input_registration_re_pass)<1 || 		$form_input_registration_user_pass!=$form_input_registration_re_pass){$ret=$ret."err_re_pass=$lu_pass_not_match&"; $valid=0;}
		}

		if(strlen($form_input_registration_first)<1){$ret=$ret."err_first=$lu_invalid_entry&"; $valid=0;}
		if(strlen($form_input_registration_last)<1){$ret=$ret."err_last=$lu_invalid_entry&"; $valid=0;}
		if(strlen($form_input_registration_email)<5 || ereg("@",$form_input_registration_email)<1 || ereg("\.",$form_input_registration_email)<1){$ret=$ret."err_email=$lu_invalid_entry&"; $valid=0;}


		$query="SELECT user_name FROM inl_users WHERE user_name='$form_input_registration_user_name'";
		$rs = &$conn->Execute($query);
		if ($rs && !$rs->EOF)
		{
			$ret=$ret."err_username_used=$lu_username_used&";
			$valid=0;
		}
		
		if($valid==1)
		{	
			include("includes/user_lib.php");
			global $pass;
			
			if($random_password)
			{
				$pass = random_password();
				$form_input_registration_user_pass = $pass;
			}
			
			$res=newuser();

			if($res>0)
			{
				$attach=ereg_replace("\|","&",$attach);
				
				if(check_perm(-1, "user")==1)
				{	$message=base64_encode($lu_confirm_registration);
					$destin="index.php?t=confirm&message=$message&go=index.php&pendmsg=1";
				}
				elseif(check_perm(-1, "user")==3)
				{				
					$message=base64_encode($lu_conf_registration_email_confirmation);
					$destin="index.php?t=confirm&message=$message&go=index.php";
				}
				else
				{	$message=base64_encode($lu_confirm_registration);
					$destin="index.php?t=confirm&message=$message&go=index.php";
				}
			}
			else
				if(check_perm(-1, "user") == 3)
					$destin="index.php?t=rregistration&load=3&res=$res";
				else
					$destin="index.php?t=registration&load=3";
		}
		else
			if(check_perm(-1, "user") == 3)
				$destin="index.php?t=rregistration&load=3&$ret&".$vals;
			else
				$destin="index.php?t=registration&load=3&$ret&".$vals;
		break;
	case "send_password":

		######-Send the new generated password to user-######
	
		//Special characters CyKuH Fix
		$form_input_registration_user_name = inl_escape($form_input_registration_user_name);
		$form_input_registration_email = inl_escape($form_input_registration_email);	
		$vals="form_input_registration_user_name=". rawurlencode($form_input_registration_user_name); 
		$vals.="&form_input_registration_email=". rawurlencode($form_input_registration_email);
		$valid=1;

		//validate required fields

		if(strlen($form_input_registration_user_name)<3){$ret="err_user_name=$lu_invalid_entry&"; $valid=0;}
		if(strlen($form_input_registration_email)<5 || ereg("@",$form_input_registration_email)<1 || ereg("\.",$form_input_registration_email)<1){$ret=$ret."err_email=$lu_invalid_entry&"; $valid=0;}

		if($valid==1)
		{
			$query="SELECT email,user_perm FROM inl_users WHERE user_name='$form_input_registration_user_name'";
			$rs = &$conn->Execute($query);
			
			if ($rs && !$rs->EOF)
			{
				$email = $rs->fields[0];
				$perm = $rs->fields[1];

				if($perm <= 2 )
				{
					$ret=$ret."err_send_password=$lu_send_password_disabled&";
					$valid=0;
				}
				elseif($form_input_registration_email != $email )
				{
					$ret=$ret."err_send_password=$lu_invalid_username_password&";
					$valid=0;
				}
				else
				{
					include("includes/user_lib.php");
					global $pass;

					// update user password
					$pass = random_password();

					$password = md5($pass);
					$rs = &$conn->Execute("UPDATE inl_users SET user_pass='$password' WHERE user_name='$form_input_registration_user_name'");
			
					// send email
					include("includes/admin_email_lib.php");
					global $conn, $subject, $email, $reply, $email_perm, $from;
					$user_data["user_name"] = $form_input_registration_user_name;
					$body=email_parse("mail_user_password_changed");
					@mail($email, $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
				}
			}
			else
			{
				$ret=$ret."err_send_password=$lu_invalid_username_password&";
				$valid=0;
			}
		}
		
		if($valid==1)
		{	
			$message=base64_encode($lu_confirm_send_password);
			$destin="index.php?t=confirm&message=$message&go=index.php";			
		}
		else
			$destin="index.php?t=send_password&load=3&$ret&".$vals;

		break;
	case "add_link":

		$form_values="&load=1";
		if ($HTTP_POST_VARS && is_array($HTTP_POST_VARS))
		{	reset($HTTP_POST_VARS);
			while (list ($key, $value) = each($HTTP_POST_VARS))
			{	if(strpos($key,"orm_input_add_")==1 && $value>"")
				{	//15
					$vname=substr($key,15);
					if (get_magic_quotes_gpc())
						$value=stripslashes($value);
					$form_values.="&$vname=" . rawurlencode($value);
				}
			}
		}

		if($form_button_add_link_selcat) //select cat
		{
			$addlink_cur_cat=$form_select_add_link_cat;
			if(ereg("^_",$addlink_cur_cat)>0)
				$addlink_cur_cat=ereg_replace("^_","",$addlink_cur_cat);
			$destin="index.php?t=add_link&addlink_cur_cat=$addlink_cur_cat&addlink_cats=$addlink_cats&attach=$attach".$form_values;
			break;
		}
		elseif($form_button_add_link_addcat) //additional category
		{	
			if(ereg("^_",$form_select_add_link_cat)<1)
			{
				$addlink_cur_cat=$form_select_add_link_cat;
				if($addlink_cur_cat==0) //fix the root naming issue
				{	$addlink_cur_cat="Home";
					$cat_data[9]=-1;
				}
				else
				{	include("includes/cats_lib.php");
					$cat_data=get_cat_data($addlink_cur_cat);
				}
				if(check_perm($cat_data[9],"link"))
				{	
					if(ereg(",$addlink_cur_cat,",$addlink_cats)<1 
					&& ereg(",$addlink_cur_cat$",$addlink_cats)<1
					&& ereg("^$addlink_cur_cat,",$addlink_cats)<1)
					{
						$addlink_cats.="$addlink_cur_cat,";
						$destin="index.php?t=add_link&addlink_cur_cat=0&addlink_cats=$addlink_cats&attach=$attach".$form_values;
					}
					else
					{	$message=base64_encode($lu_error_addto_cat_added);
						$destin="index.php?t=error&message=$message";
					}
				} //else no permissions or already in the list
				else
				{	$message=base64_encode($lu_error_addto_cat_not_allowed);
					$destin="index.php?t=error&message=$message";
				}
				break;
			}
			else
			{
				$addlink_cur_cat=ereg_replace("^_","",$form_select_add_link_cat);
				$destin="index.php?t=add_link&addlink_cur_cat=$addlink_cur_cat&addlink_cats=$addlink_cats&attach=$attach".$form_values;
			}
		}
		elseif($form_button_add_link_addlink)
		{
			
				include("includes/links_lib.php");
				$ret=validatelink($addlink_cats);
				if($ret==1 || $ret==10)
				{	$more="&err_link_name=$lu_invalid_entry";
					$destin="index.php?t=add_link&addlink_cur_cat=$addlink_cur_cat&addlink_cats=$addlink_cats&attach=$attach&error=$ret$more$form_values";
				}
				else
				{
					$destin=add_new_link($addlink_cats);
				}
		}
		else
		{	
			if (($HTTP_POST_VARS)&&is_array($HTTP_POST_VARS))
			{
			reset($HTTP_POST_VARS);
			while (list ($key, $value) = each($HTTP_POST_VARS)) 
			{	if(strpos($key,"orm_button_add_link_cats_delcat")==1) //remove
				{	//32
					$id=substr($key,32);
					$cat_list=split(",",$addlink_cats);
					if(count($cat_list)<2)
					{	$message=base64_encode($lu_error_cannot_remove_cat);
						$destin="index.php?t=error&message=$message";
						break;
					}
					if($id==0)
						$id="Home";
					$addlink_cats="";
					for($i=0;$i<count($cat_list);$i++)
					{	if($cat_list[$i] != $id)
							$addlink_cats.="$cat_list[$i],";
					}
					$addlink_cats=substr($addlink_cats,0,strlen($addlink_cats)-1);
					$destin="index.php?t=add_link&addlink_cur_cat=$addlink_cur_cat&addlink_cats=$addlink_cats&attach=$attach".$form_values;
				}
			}
			}
		}
		break;
	case "rate":

		include("includes/user_lib.php");
		
		if(check_perm(-1,"rate"))
		{	$attach=ereg_replace("\|","&",$attach);
			if(!vote($linkid))
			{	$destin="index.php?".$ses["destin"];
				$message=base64_encode($lu_confirm_rate);
				$destin="index.php?t=confirm&message=$message";
			}
			else
			{	$message=base64_encode($lu_already_voted);
				$destin="index.php?t=error&message=$message";
			}
		}
		else
		{	$message=base64_encode($lu_already_voted);
			$destin="index.php?t=error&message=$message";
		}
		break;
	case "go":	
		include("includes/links_lib.php");
// CyKuH [WTN]
		$t=gotolink($id);
		/*if($t=="" || $t=="http://")
			$t=$server.$filepath;*/
		//echo $t;
		if(ereg("://",$t)>0)
			header("location:$t");
		else
			header("location:http://$t");
		die();
		break;
	case "modify_link":
			include("includes/links_lib.php");
			$error=validatelink("Home,");
			global $lu_invalid_entry;
			$ret="";
			if($error==1){$ret="&err_link_name=$lu_invalid_entry";}
			if($ret)
			{	
				if (($HTTP_POST_VARS)&&is_array($HTTP_POST_VARS))
				{
					reset($HTTP_POST_VARS);
					while (list ($key, $value) = each($HTTP_POST_VARS))
					{	if(strpos($key,"orm_input_add_")==1 && $value>"")
						{	//15
							$vname=substr($key,15);
							if (get_magic_quotes_gpc())
								$value=stripslashes($value);
							$form_values.="&$vname=" . rawurlencode($value);
						}
					}
				}
				$destin="index.php?id=$id&load=1&t=modify_link&attach=$attach".$ret.$form_values;
			}
			else	
			{	
				$rs = &$conn->Execute("SELECT inl_lc.cat_id,cat_perm FROM inl_lc LEFT JOIN inl_cats ON inl_cats.cat_id=inl_lc.cat_id WHERE inl_lc.link_id=$id");					
					
				if($rs && !$rs->EOF)
				{
					while (!$rs->EOF)
					{
						$cats[$rs->fields[0]] = $rs->fields[1];
						$rs->MoveNext();
					}

					while (list ($cat_id, $cat_perm) = each($cats)) //avoiding holes in the list
					{	
//echo "cat_id=$cat_id, cat_perm=$cat_perm<br>";
// CyKuH [WTN]

						if($cat_id)
						{	
							//get cat information
							$flag = false;						

							if(check_perm($cat_perm,"link")==1 && $admin!=1) //pending
							{
								$flag = true;
								break;
							}
						}
						else
						{
							if(check_perm(-1,"link")==1 && $admin!=1) //pending
							{
								$flag = true;
								break;
							}
						}
					}

					$str_cats = implode(",",array_keys($cats));
					$str_cats = str_replace(",0",",Home",$str_cats);
					
					$str_cats = str_replace("^0","Home","^".$str_cats);
					if($str_cats[0] == '^')
						$str_cats = substr($str_cats, 1);

//echo $str_cats."<br>";
					
					if( $flag )
						$destin=add_new_link($str_cats,$id);
					else
						$destin=save_link($id,$str_cats);
				}
			}
		break;
	case "suggest_cat":
			include("includes/cats_lib.php");
			$ret="";
			if(!$form_input_suggest_cat_name)
				$ret.="&err_link_name=$lu_invalid_entry";
			if($ret)
			{	
				$destin="index.php?t=suggest_cat&cat=$cat&load=1&attach=$attach&link_name=".rawurlencode(inl_escape($form_input_suggest_cat_name))."&link_desc=".rawurlencode(form_escape($form_input_suggest_cat_desc)).$ret;
			}
			else
			{	include("includes/links_lib.php");
				$cat_name=$form_input_suggest_cat_name;
				$cat_desc=$form_input_suggest_cat_desc;
				if(check_perm(-1,"cat")>0)
				{	addcat($cat);
					$message=base64_encode($lu_confirm_suggest);
					$destin="index.php?t=confirm&message=$message&go=index.php";
					
				}
				else
				{	$message=base64_encode($lu_error_addto_cat_not_allowed);
					$destin="index.php?t=error&message=$message";
				}
			}
		break;

	case "profile":
		if (get_magic_quotes_gpc())
		{		
			$form_input_registration_user_name=stripslashes($form_input_registration_user_name);
			$form_input_registration_first=stripslashes($form_input_registration_first);
			$form_input_registration_last=stripslashes($form_input_registration_last);
			$form_input_registration_email=stripslashes($form_input_registration_email);
			$form_input_registration_cust1=stripslashes($form_input_registration_cust1);
			$form_input_registration_cust2=stripslashes($form_input_registration_cust2);
			$form_input_registration_cust3=stripslashes($form_input_registration_cust3);
			$form_input_registration_cust4=stripslashes($form_input_registration_cust4);
			$form_input_registration_cust5=stripslashes($form_input_registration_cust5);
			$form_input_registration_cust6=stripslashes($form_input_registration_cust6);
		}
		$vals="form_input_registration_user_name=$form_input_registration_user_name&form_input_registration_first=$form_input_registration_first&form_input_registration_last=$form_input_registration_last&form_input_registration_email=$form_input_registration_email&form_input_registration_cust1=$form_input_registration_cust1&form_input_registration_cust2=$form_input_registration_cust2&form_input_registration_cust3=$form_input_registration_cust3&form_input_registration_cust4=$form_input_registration_cust4&form_input_registration_cust5=$form_input_registration_cust5&form_input_registration_cust6=$form_input_registration_cust6";
		global $form_input_registration_first;
		
		$valid=1;
		if(strlen($form_input_registration_user_pass)!=0) 
		{
			if(strlen($form_input_registration_user_pass)<3 || ereg(" ",$form_input_registration_user_pass)>0){$ret=$ret."err_user_pass=$lu_invalid_entry&"; $valid=0;}
			if($form_input_registration_user_pass!=$form_input_registration_re_pass){$ret=$ret."err_re_pass=$lu_pass_not_match&"; $valid=0;}
		}else{
			$keeppass = 1;
		}
		
		if(strlen($form_input_registration_first)<1){$ret=$ret."err_first=$lu_invalid_entry&"; $valid=0;}
		if(strlen($form_input_registration_last)<1){$ret=$ret."err_last=$lu_invalid_entry&"; $valid=0;}
		if(strlen($form_input_registration_email)<5 || ereg("@",$form_input_registration_email)<1 || ereg("\.",$form_input_registration_email)<1){$ret=$ret."err_email=$lu_invalid_entry&"; $valid=0;}
		$form_input_registration_email=inl_escape($form_input_registration_email);

		if($valid==1)
		{	include("includes/user_lib.php");
			$res=updateuser();
			if($res>0)
			{	$message=base64_encode($lu_confirm_profile);
				$attach=ereg_replace("\|","&",$attach);
				$destin="index.php?t=confirm&message=$message&$attach";
			}
			else
				$destin="index.php?t=profile&$attach=$attach";
		}
		else{
			$destin="index.php?t=profile&$ret"."load=3&$attach=$attach&".$vals;
		}
		break;
	case "search":	
		if (strlen(trim($form_input_search_keyword))<3)
		{
			$message=base64_encode($lu_error_for_simple_search);
			$destin="index.php?t=error&message=$message";
			break;
		}
		$conn->Execute("DROP TABLE IF EXISTS inl_$sid");
		if ($table=="link" || $table=="link1" || $table=="link2")
		{
			$destin="index.php?t=display_link_search&having=".searchforlinks($form_input_search_keyword, $current_cat_id)."&cat=".$current_cat_id;
		}
		elseif($table=="cat")
			$destin="index.php?t=display_cat_search&having=".searchforcats($form_input_search_keyword);
		break;
	case "advsearch":	
		global $lu_button_search_cats, $lu_button_search_links;
		$conn->Execute("DROP TABLE IF EXISTS inl_$sid");
		if ($form_button_search==$lu_button_search_links)
		{
			$r = getadvlinksearch();
			$destin="index.php?t=display_link_search&having=".$r;
		}
		if ($form_button_search==$lu_button_search_cats)
		{
			$r = getadvcatsearch();
			$destin="index.php?t=display_cat_search&having=".$r;
		}
		break;
	case "subscribe":	
		if($form_button_name_subscribed==$lu_button_subscribe && $ses["user_perm"]==3)
		{	$conn->Execute("UPDATE inl_users SET user_perm=4 WHERE user_id=".$ses["user_id"]);
			$ses["user_perm"]=4;
			save_session($sid);
			$message=base64_encode($lu_confirm_subscribe);
			$destin="index.php?t=confirm&message=$message";
		}
		if($form_button_name_subscribed==$lu_button_unsubscribe && $ses["user_perm"]==4)
		{	$conn->Execute("Update inl_users set user_perm=3 where user_id=".$ses["user_id"]);
			$ses["user_perm"]=3;
			save_session($sid);
			$message=base64_encode($lu_confirm_unsubscribe);
			$destin="index.php?t=confirm&message=$message";
		}
		break;
	case "confirmed":
		$destin="index.php";
	case "dead":
		$query="SELECT * FROM inl_links LEFT JOIN inl_lc USING (link_id) WHERE inl_links.link_id=$link_id";
		$rs = &$conn->Execute($query);
		if ($rs && !$rs->EOF)
			$email_link=$rs->fields;
		$query="SELECT * FROM inl_users WHERE user_id=".$ses["user_id"];
		$rs = &$conn->Execute($query);
		if ($rs && !$rs->EOF)
			$user_data=$rs->fields;
		include("includes/admin_email_lib.php");
		$body=email_parse("mail_admin_dead_link");
		if($u=get_admin_emails())
			@mail($u, $subject, $body, "From:".$user_data["first"]." ".$user_data["last"]."<".$user_data["email"].">\r\nReply-to:".$user_data["email"]);

		$message=base64_encode($lu_confirm_report_dead);
		$destin = "index.php?t=confirm&message=$message&attach=$attach";
	break;
	case "del_fav":
		if($ses["user_id"]>0)
			$conn->Execute("Delete from inl_fav where user_id='".$ses["user_id"]."' and link_id='$fav'");
		if ($HTTP_GET_VARS && is_array($HTTP_GET_VARS))
		{	reset($HTTP_GET_VARS);
			while (list ($key, $value) = each($HTTP_GET_VARS))	
				if($key!="fav" && $key!="action")
					$form_values.="$key=" . rawurlencode(inl_escape($value))."&";
		}
		$ses["destin"]="$form_values";
		save_session($sid);
		$message=base64_encode($lu_del_favorites);
		$destin = "index.php?t=confirm&message=$message";
	break;
	case "add_fav":
		if($ses["user_id"]>0)
		{
			$rs=&$conn->Execute("Select * from inl_fav where user_id='".$ses["user_id"]."' and link_id='$fav'");
			if ($rs && !$rs->EOF)
			{}
			else
			{
				if($ses["user_id"]>0)
					$conn->Execute("INSERT INTO inl_fav (user_id, link_id) VALUES ('".$ses["user_id"]."','$fav')");
			}
			if ($HTTP_GET_VARS && is_array($HTTP_GET_VARS))
			{	reset($HTTP_GET_VARS);
				while (list ($key, $value) = each($HTTP_GET_VARS))	
					if($key!="fav" && $key!="action")
						$form_values.="$key=" . rawurlencode(inl_escape($value))."&";
			}
			$ses["destin"]="$form_values";
			save_session($sid);
			$message=base64_encode($lu_add_favorites);
			$destin = "index.php?t=confirm&message=$message";
		}
		else
		{
			$message=base64_encode($lu_favorites_add_login);
			$destin="index.php?t=error&message=$message";
		}
	break;
	case "suggest_friend":
		
		if(!$form_input_suggest_friend_name)
		{
			$message=base64_encode("$lu_invalid_entry:$lu_name");
			$destin = "index.php?t=error&message=$message";
		}
		elseif(!$form_input_suggest_friend_email)
		{
			$message=base64_encode("$lu_invalid_entry:$lu_email");
			$destin = "index.php?t=error&message=$message";
		}
		elseif(!$form_input_suggest_friend_to_name)
		{
			$message=base64_encode("$lu_invalid_entry:$lu_to");
			$destin = "index.php?t=error&message=$message";
			
		}
		elseif(!$form_input_suggest_friend_to_email)
		{
			$message=base64_encode("$lu_invalid_entry:$lu_email");
			$destin = "index.php?t=error&message=$message";
			
		}
		elseif(!$form_input_suggest_friend_subject)
		{
			$message=base64_encode("$lu_invalid_entry:$lu_subject");
			$destin = "index.php?t=error&message=$message";
		}
		else
		{
			$expired=time()-259200;
			$query="delete from inl_votes where stamp<$expired and rev=2";
			$conn->Execute($query);
			$query="Select stamp, vote_link from inl_votes where vote_ip='".$REMOTE_ADDR."'and rev=2";
			$rs = &$conn->Execute($query);
			if ($rs && !$rs->EOF)
			{	$data=$rs->fields;
				$times_sent = $data["vote_link"];
				if($data["vote_link"]>5)
				{	
					$message=base64_encode($lu_error_suggest_not_allowed);
					$destin="index.php?t=error&message=$message";
					break;
				}				
			}	

			$times_sent++;

			$tofield = "\"". $form_input_suggest_friend_to_name . "\" <". $form_input_suggest_friend_to_email . ">"; 

			if (@mail($tofield, $form_input_suggest_friend_subject, $form_input_suggest_friend_body,"From: ". $form_input_suggest_friend_name."<".$form_input_suggest_friend_email.">" ."\r\nReply-to:$reply")) 
			{
				if ($times_sent == 1)
					$query="INSERT INTO inl_votes (stamp, vote_ip, vote_link, rev) VALUES ('".time()."', '".$REMOTE_ADDR."', '$times_sent', '2')";
				else
					$query="update inl_votes SET vote_link = '$times_sent' where vote_ip='".$REMOTE_ADDR."' and rev=2";
				$conn->Execute($query);
				$message=base64_encode($lu_confirm_suggest_site);
				$destin = "index.php?t=confirm&message=$message&go=index.php";
			}
		}
		break;
}
inl_header($destin);
?>