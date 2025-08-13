<?
# In-Link Version 2.1.0 admin configuration library
# Admin section, Configuration implementation

function update_conf_output() 
{	//var declaration
	global $conn, $rootperm_t, $imagecounter_t, $cat_order_t, $cat_sort_t, $cat_user_change_sort_t, $cols_t, $rcols_t, $cat_new_t, $cat_mark_as_t, $cat_icon_t, $cat_icon_custom_t, $force_pick_t, $use_pick_tpl_t,$review_order_t, $default_meta_keywords_t, $default_meta_desc_t, $link_order_t, $link_sort_t, $link_user_change_sort_t, $lim_t, $link_pop_t, $link_top_t, $link_new_t, $link_mark_as_t, $link_icon_t, $link_icon_custom_t, $link_rating_t, $rate_icon_t, $rate_icon_custom_t,$review_sort_t,$show_status_url_t,$multiple_search_instances_t, $subcat_order_t, $subcat_sort_t;
	//saving variables in inl_config, output screen
	$conn->Execute("update inl_config set value='$rootperm_t' where name='rootperm'");
	$conn->Execute("update inl_config set value='$imagecounter_t' where name='imagecounter'");	
	$conn->Execute("update inl_config set value='$cat_order_t' where name='cat_order'");
	$conn->Execute("update inl_config set value='$cat_sort_t' where name='cat_sort'");
	$conn->Execute("update inl_config set value='$cat_user_change_sort_t' where name='cat_user_change_sort'");
	$conn->Execute("update inl_config set value='$cols_t' where name='cols'");
	$conn->Execute("update inl_config set value='$rcols_t' where name='rcols'");
	$conn->Execute("update inl_config set value='$cat_new_t' where name='cat_new'");
	$conn->Execute("update inl_config set value='$cat_mark_as_t' where name='cat_mark_as'");
	$conn->Execute("update inl_config set value='$cat_icon_t' where name='cat_icon'");
	$conn->Execute("update inl_config set value='$cat_icon_custom_t' where name='cat_icon_custom'");
	$conn->Execute("update inl_config set value='$link_order_t' where name='link_order'");
	$conn->Execute("update inl_config set value='$link_sort_t' where name='link_sort'");
	$conn->Execute("update inl_config set value='$link_user_change_sort_t' where name='link_user_change_sort'");
	$conn->Execute("update inl_config set value='$lim_t' where name='lim'");
	$conn->Execute("update inl_config set value='$link_pop_t' where name='link_pop'");
	$conn->Execute("update inl_config set value='$link_top_t' where name='link_top'");
	$conn->Execute("update inl_config set value='$link_new_t' where name='link_new'");
	$conn->Execute("update inl_config set value='$link_mark_as_t' where name='link_mark_as'");
	$conn->Execute("update inl_config set value='$link_icon_t' where name='link_icon'");
	$conn->Execute("update inl_config set value='$link_icon_custom_t' where name='link_icon_custom'");
	$conn->Execute("update inl_config set value='$link_rating_t' where name='link_rating'");
	$conn->Execute("update inl_config set value='$rate_icon_t' where name='rate_icon'");
	$conn->Execute("update inl_config set value='$rate_icon_custom_t' where name='rate_icon_custom'");
	$conn->Execute("update inl_config set value='$force_pick_t' where name='force_pick'");
	$conn->Execute("update inl_config set value='$use_pick_tpl_t' where name='use_pick_tpl'");
	$conn->Execute("update inl_config set value='$review_order_t' where name='review_order'");
	$conn->Execute("update inl_config set value='$review_sort_t' where name='review_sort'");
	$conn->Execute("update inl_config set value='$default_meta_keywords_t' where name='default_meta_keywords'");
	$conn->Execute("update inl_config set value='$default_meta_desc_t' where name='default_meta_desc '");
	$conn->Execute("update inl_config set value='$show_status_url_t' where name='show_status_url'");
	$conn->Execute("update inl_config set value='$multiple_search_instances_t' where name='multiple_search_instances'");
	$conn->Execute("update inl_config set value='$subcat_order_t' where name='subcat_order'");
	$conn->Execute("update inl_config set value='$subcat_sort_t' where name='subcat_sort'");


}
function validateconf()
{	//var declaration
	global $conn, $rootperm_t, $imagecounter_t, $cat_order_t, $cat_sort_t, $cat_user_change_sort_t, $cols_t, $rcols_t, $cat_new_t, $cat_mark_as_t, $cat_icon_t, $cat_icon_custom_t, $link_order_t, $link_sort_t, $link_user_change_sort_t, $lim_t, $link_pop_t, $link_top_t, $link_new_t, $link_mark_as_t, $link_icon_t, $link_icon_custom_t, $link_rating_t, $rate_icon_t, $rate_icon_custom_t, $rootperm, $imagecounter, $cat_order, $cat_sort, $cat_user_change_sort, $cols, $cat_new, $cat_mark_as, $cat_icon, $cat_icon_custom, $link_order, $link_sort, $link_user_change_sort, $lim, $link_pop, $link_top, $link_new, $link_mark_as, $link_icon, $link_icon_custom, $link_rating, $rate_icon, $rate_icon_custom,$force_pick_t, $use_pick_tpl_t,$force_pick, $use_pick_tpl, $review_order_t,	$review_order, $review_sort_t, $review_sort, $default_meta_keywords_t, $default_meta_desc_t, $default_meta_keywords, $default_meta_desc, $err_conf, $show_status_url,$show_status_url_t,$multiple_search_instances,$multiple_search_instances_t, $subcat_order_t, $subcat_sort_t, $subcat_order, $subcat_sort;

	//error checking for output screen
	$noerror=true;
	if($cols_t<1)
	{	$err_conf["cols"]=1;
		$noerror=false;
	}
	if($rcols_t<1)
	{	$err_conf["rcols"]=1;
		$noerror=false;
	}
	if($cat_new_t<1)
	{	$err_conf["cat_new"]=1;
		$noerror=false;
	}
	if($lim_t<1)
	{	$err_conf["lim"]=1;
		$noerror=false;
	}
	if($link_pop_t<0)
	{	$err_conf["link_pop"]=1;
		$noerror=false;
	}
	if($link_top_t<0)
	{	$err_conf["link_top"]=1;
		$noerror=false;
	}
	if($link_new_t<0)
	{	$err_conf["link_new"]=1;
		$noerror=false;
	}

	//resetting values of global variables - to update the screen
	$rootperm=$rootperm_t;
	$imagecounter=$imagecounter_t;
	$cat_order=$cat_order_t; 
	$cat_sort=$cat_sort_t; 
	$cat_user_change_sort=$cat_user_change_sort_t; 
	$cols=$cols_t; 
	$rcols=$rcols_t; 
	$cat_new=$cat_new_t; 
	$cat_mark_as=$cat_mark_as_t; 
	$cat_icon=$cat_icon_t; 
	$cat_icon_custom=$cat_icon_custom_t;
	$link_order=$link_order_t; 
	$link_sort=$link_sort_t; 
	$link_user_change_sort=$link_user_change_sort_t; 
	$lim=$lim_t; 
	$link_pop=$link_pop_t; 
	$link_top=$link_top_t; 
	$link_new=$link_new_t; 
	$link_mark_as=$link_mark_as_t; 
	$link_icon=$link_icon_t; 
	$link_icon_custom=$link_icon_custom_t; 
	$link_rating=$link_rating_t; 
	$rate_icon=$rate_icon_t; 
	$rate_icon_custom=$rate_icon_custom_t;
	$force_pick=$force_pick_t;
	$use_pick_tpl=$use_pick_tpl_t;
	$review_order=$review_order_t;
	$review_sort=$review_sort_t;
	$default_meta_keywords=$default_meta_keywords_t;
	$default_meta_desc=$default_meta_desc_t;
	$show_status_url=$show_status_url_t;
	$multiple_search_instances=$multiple_search_instances_t;
	$subcat_order=$subcat_order_t;
	$subcat_sort=$subcat_sort_t;

	return $noerror;
}

function update_conf_system() 
{	//var declaration CykuH [WTN]
	global $conn, $sql_user, $sql_pass, $sql_server, $sql_db, $sql_type, $sql_usern, $sql_passn, $sql_servern, $sql_dbn, $sql_typen, $filepath, $filepathn, $filedir, $filedirn, $first_name, $first_namen, $last_name, $last_namen, $sitename, $sitenamen, $server, $servern, $session_control, $session_get, $session_cookie, $ses_expiration, $ses_expirationn, $pconnect_t, $pconnect; 

	//saving vars in the db
	$conn->Execute("update inl_config set value='$filepathn' where name='filepath'");
	$conn->Execute("update inl_config set value='$filedirn' where name='filedir'");
	$conn->Execute("update inl_config set value='$first_namen' where name='first_name'");
	$conn->Execute("update inl_config set value='$last_namen' where name='last_name'");
	$conn->Execute("update inl_config set value='$sitenamen' where name='sitename'");
	$conn->Execute("update inl_config set value='$servern' where name='server'");

	$conn->Execute("update inl_config set value='$ses_expirationn' where name='ses_expiration'");
	if($session_control==1)
	{	$session_get=0;
		$session_cookie=1;
	}
	elseif($session_control==2)
	{	$session_get=1;
		$session_cookie=0;
	}
	elseif($session_control==3)
	{	$session_get=1;
		$session_cookie=1;
	}

	$pconnect=$pconnect_t;
	$conn->Execute("update inl_config set value='$pconnect' where name='pconnect'");

	$conn->Execute("update inl_config set value='$session_get' where name='session_get'");
	$conn->Execute("update inl_config set value='$session_cookie' where name='session_cookie'");
	
	$configfile = $filedir . "includes/config.php";
	if (($sql_user != $sql_usern)||($sql_server != $sql_servern)||($sql_pass != $sql_passn)||($sql_db != $mysql_dbn)||($sql_type != $sql_typen)) 
	{	//read configuration
		$fd = fopen($configfile, "r");
		$cfg = fread($fd, filesize($configfile));
		fclose($fd);

		//replace MySQL vars
		$cfg = ereg_replace("sql_server(.+)#sql_server", "sql_server = \"$sql_servern\";#sql_server", $cfg);
		$cfg = ereg_replace("sql_user(.+)#sql_user", "sql_user = \"$sql_usern\";#sql_user", $cfg);
		$cfg = ereg_replace("sql_pass(.+)#sql_pass", "sql_pass = \"$sql_passn\";#sql_pass", $cfg);
		$cfg = ereg_replace("sql_db(.+)#sql_db", "sql_db = \"$sql_dbn\";#sql_db", $cfg);
		$cfg = ereg_replace("sql_type(.+)#sql_type", "sql_type = \"$sql_typen\";#sql_type", $cfg);
			
		//save configuration
		$fd = fopen($configfile, "w");
		fputs($fd, $cfg);
		fclose($fd);
	}

	//update global vars CyKuH [WTN]
	$sql_user = $sql_usern;
	$sql_server = $sql_servern;
	$sql_pass = $sql_passn;
	$sql_db = $sql_dbn;
	$sql_type = $sql_typen;
	$filedir = $filedirn;
	$filepath = $filepathn;
	$sitename = $sitenamen;
	$server = $servern;

	$session_expiration=$session_expirationn;
}

function update_conf_users() 
{	global $conn, $perm_addlink, $perm_addlinkn, $perm_addcat, $perm_addcatn, $perm_vote, $perm_voten, $perm_review, $perm_reviewn, $regperm, 			$regpermn, $root_link_all, $root_link_reg, $root_link_perm, $apply_cat_perm, $suggest_cat_all, $suggest_cat_reg, $suggest_cat_perm, 		$review_all, $review_reg, $review_perm, $review_expiration_t, $review_expiration, $rate_reg, $rate_all, $rate_perm,$rating_expiration_t, 	$rating_expiration, $user_perm_t, $user_perm;

	//set vars & update the db
	$root_link_perm=$root_link_reg*3+$root_link_all;
	$conn->Execute("update inl_config set value='$root_link_perm' where name='root_link_perm'");

	if($apply_cat_perm) //recurese permissions
		@$conn->Execute("UPDATE inl_cats SET cat_perm=$root_link_perm");

	$suggest_cat_perm=$suggest_cat_reg*3+$suggest_cat_all;
	$conn->Execute("update inl_config set value='$suggest_cat_perm' where name='suggest_cat_perm'");

	$review_perm=$review_reg*3+$review_all;
	$conn->Execute("update inl_config set value='$review_perm' where name='review_perm'");

	$rate_perm=$rate_reg*2+$rate_all;
	$conn->Execute("update inl_config set value='$rate_perm' where name='rate_perm'");

	$review_expiration=$review_expiration_t;
	$conn->Execute("update inl_config set value='$review_expiration' where name='review_expiration'");

	$rating_expiration=$rating_expiration_t;
	$conn->Execute("update inl_config set value='$rating_expiration' where name='rating_expiration'");

	$user_perm=$user_perm_t;
	$conn->Execute("update inl_config set value='$user_perm' where name='user_perm'");
}

function update_conf_email() 
{	//var decl
	global $conn, $admin_email, $email_perm, $edit_link_d, $admin_new_user, $admin_new_link, $admin_edit_link, $admin_new_cat, $admin_new_review, 	$new_user_r, $new_user_a, $new_user_d, $new_link, $new_link_a, $new_link_d, $edit_link, $edit_link_a, $add_review_owner;
	
	//save root email
	$conn->Execute("update inl_users set email='$admin_email' where user_perm='1'");

	//build email_perm var
	$email_perm="";
	$email_perm.=sprintf("%d",$admin_new_user);
	$email_perm.=sprintf("%d",$admin_new_link);
	$email_perm.=sprintf("%d",$admin_edit_link);
	$email_perm.=sprintf("%d",$admin_new_cat);
	$email_perm.=sprintf("%d",$admin_new_review);
	$email_perm.=sprintf("%d",$new_user_r);
	$email_perm.=sprintf("%d",$new_user_a);
	$email_perm.=sprintf("%d",$new_user_d);
	$email_perm.=sprintf("%d",$new_link);
	$email_perm.=sprintf("%d",$new_link_a);
	$email_perm.=sprintf("%d",$new_link_d);
	$email_perm.=sprintf("%d",$edit_link);
	$email_perm.=sprintf("%d",$edit_link_a);
	$email_perm.=sprintf("%d",$edit_link_d);
	$email_perm.=sprintf("%d",$add_review_owner);

	//save email perm var
	$conn->Execute("update inl_config set value='$email_perm' where name='email_perm'");
}


function updatecustom() 
{	global $conn, $cc1n, $cc2n, $cc3n, $cc4n, $cc5n, $cc6n, $lc1n, $lc2n, $lc3n, $lc4n, $lc5n, $lc6n, $uc1n, $uc2n, $uc3n, $uc4n, $uc5n, $uc6n, $cc1, $cc2, $cc3, $cc4, $cc5, $cc6, $lc1, $lc2, $lc3, $lc4, $lc5, $lc6, $uc1, $uc2, $uc3, $uc4, $uc5, $uc6;

	
	//save vars
	$conn->Execute("update inl_config set value='".inl_escape($cc1n)."' where name='cc1'");
	$cc1=$cc1n;
	$conn->Execute("update inl_config set value='".inl_escape($cc2n)."' where name='cc2'");
	$cc2=$cc2n;
	$conn->Execute("update inl_config set value='".inl_escape($cc3n)."' where name='cc3'");
	$cc3=$cc3n;
	$conn->Execute("update inl_config set value='".inl_escape($cc4n)."' where name='cc4'");
	$cc4=$cc4n;
	$conn->Execute("update inl_config set value='".inl_escape($cc5n)."' where name='cc5'");
	$cc5=$cc5n;
	$conn->Execute("update inl_config set value='".inl_escape($cc6n)."' where name='cc6'");
	$cc6=$cc6n;
	$conn->Execute("update inl_config set value='".inl_escape($lc1n)."' where name='lc1'");
	$lc1=$lc1n;
	$conn->Execute("update inl_config set value='".inl_escape($lc2n)."' where name='lc2'");
	$lc2=$lc2n;
	$conn->Execute("update inl_config set value='".inl_escape($lc3n)."' where name='lc3'");
	$lc3=$lc3n;
	$conn->Execute("update inl_config set value='".inl_escape($lc4n)."' where name='lc4'");
	$lc4=$lc4n;
	$conn->Execute("update inl_config set value='".inl_escape($lc5n)."' where name='lc5'");
	$lc5=$lc5n;
	$conn->Execute("update inl_config set value='".inl_escape($lc6n)."' where name='lc6'");
	$lc6=$lc6n;
	$conn->Execute("update inl_config set value='".inl_escape($uc1n)."' where name='uc1'");
	$uc1=$uc1n;
	$conn->Execute("update inl_config set value='".inl_escape($uc2n)."' where name='uc2'");
	$uc2=$uc2n;
	$conn->Execute("update inl_config set value='".inl_escape($uc3n)."' where name='uc3'");
	$uc3=$uc3n;
	$conn->Execute("update inl_config set value='".inl_escape($uc4n)."' where name='uc4'");
	$uc4=$uc4n;
	$conn->Execute("update inl_config set value='".inl_escape($uc5n)."' where name='uc5'");
	$uc5=$uc5n;
	$conn->Execute("update inl_config set value='".inl_escape($uc6n)."' where name='uc6'");
	$uc6=$uc6n;
}

//get admin email
function adminemail()
{	global $conn, $admin_email;
	
	//retreievs admin email 
	$rs = &$conn->Execute("select email from inl_users where user_perm='1'");
	if ($rs && !$rs->EOF)
		$admin_email = $rs->fields[0];
}

//change root pass
function updaterootpass($pass_new, $pass_new1, $pass_old)
{	global $conn, $error;
	
	if ($pass_new == $pass_new1) //check that pwd entered twice correctly
	{	$rs = &$conn->Execute("select user_pass from inl_users where user_perm='1'");
		if ($rs && !$rs->EOF)
			$admin_pass = $rs->fields[0]; //retrieve old pwd in md5
		if ($admin_pass == md5($pass_old)) //compare old entered against db
		{	//if matches, update db with new pwd in md5
			$pass_new=md5($pass_new);
			$conn->Execute("update inl_users set user_pass='$pass_new' where user_perm='1'");
		}
		else
			$error = 2;
	} 
	else 
		$error = 1;
}

//decode email perm var
function read_email_setting($setting,$type)
{	switch($type)
	{	case "admin_new_user":
			return substr($setting,0,1);
		case "admin_new_link":
			return substr($setting,1,1);
		case "admin_edit_link":
			return substr($setting,2,1);
		case "admin_new_cat":
			return substr($setting,3,1);
		case "admin_new_review":
			return substr($setting,4,1);
		case "new_user_r":
			return substr($setting,5,1);
		case "new_user_a":
			return substr($setting,6,1);
		case "new_user_d":
			return substr($setting,7,1);
		case "new_link":
			return substr($setting,8,1);
		case "new_link_a":
			return substr($setting,9,1);
		case "new_link_d":
			return substr($setting,10,1);
		case "edit_link":
			return substr($setting,11,1);
		case "edit_link_a":
			return substr($setting,12,1);
		case "edit_link_d":
			return substr($setting,13,1);
		case "add_review_owner":
			return substr($setting,14,1);
	}
}

function update_conf_search()
{	global $conn,
	$extended_search_f, $high_lighting_tag1_f, $high_lighting_tag2_f,
	$do_link_name_f, $do_link_desc_f, $do_link_url_f, $do_link_image_f, $do_link_cust1_f, $do_link_cust2_f,	$do_link_cust3_f, $do_link_cust4_f, $do_link_cust5_f, $do_link_cust6_f,
	$do_cat_name_f, $do_cat_desc_f, $do_cat_image_f, $do_cat_cust1_f, $do_cat_cust2_f,
	$do_cat_cust3_f, $do_cat_cust4_f, $do_cat_cust5_f, $do_cat_cust6_f;
		
	if (!$extended_search_f)
		$extended_search_f = 0;
	if (!$high_lighting_tag1_f)
		$high_lighting_tag1_f = 0;
	if (!$high_lighting_tag2_f)
		$high_lighting_tag2_f = 0;
	if (!$do_link_name_f)
		$do_link_name_f = 0;
	if (!$do_link_desc_f)
		$do_link_desc_f = 0;
	if (!$do_link_url_f)
		$do_link_url_f = 0;
	if (!$do_link_image_f)
		$do_link_image_f = 0;
	if (!$do_link_cust1_f)
		$do_link_cust1_f = 0;
	if (!$do_link_cust2_f)
		$do_link_cust2_f = 0;
	if (!$do_link_cust3_f)
		$do_link_cust3_f = 0;
	if (!$do_link_cust4_f)
		$do_link_cust4_f = 0;
	if (!$do_link_cust5_f)
		$do_link_cust5_f = 0;
	if (!$do_link_cust6_f)
		$do_link_cust6_f = 0;
	
	if (!$do_cat_name_f)
		$do_cat_name_f = 0;
	if (!$do_cat_desc_f)
		$do_cat_desc_f = 0;
	if (!$do_cat_image_f)
		$do_cat_image_f = 0;
	if (!$do_cat_cust1_f)
		$do_cat_cust1_f = 0;
	if (!$do_cat_cust2_f)
		$do_cat_cust2_f = 0;
	if (!$do_cat_cust3_f)
		$do_cat_cust3_f = 0;
	if (!$do_cat_cust4_f)
		$do_cat_cust4_f = 0;
	if (!$do_cat_cust5_f)
		$do_cat_cust5_f = 0;
	if (!$do_cat_cust6_f)
		$do_cat_cust6_f = 0;

	$high_lighting_tag1_f = stripslashes($high_lighting_tag1_f);
	$high_lighting_tag2_f = stripslashes($high_lighting_tag2_f);	
	$high_lighting_tag1_f = eregi_replace("\"", "", $high_lighting_tag1_f);
	$high_lighting_tag2_f = eregi_replace("\"", "", $high_lighting_tag2_f);
	$conn->Execute("UPDATE inl_config SET value='$extended_search_f' where name='extended_search'");
	$conn->Execute("UPDATE inl_config SET value='".addslashes($high_lighting_tag1_f)."' where name='high_lighting_tag1'");
	$conn->Execute("UPDATE inl_config SET value='".addslashes($high_lighting_tag2_f)."' where name='high_lighting_tag2'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_name_f' where name='do_link_name'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_desc_f' where name='do_link_desc'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_url_f' where name='do_link_url'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_image_f' where name='do_link_image'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_cust1_f' where name='do_link_cust1'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_cust2_f' where name='do_link_cust2'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_cust3_f' where name='do_link_cust3'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_cust4_f' where name='do_link_cust4'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_cust5_f' where name='do_link_cust5'");
	$conn->Execute("UPDATE inl_config SET value='$do_link_cust6_f' where name='do_link_cust6'");
	
	$conn->Execute("UPDATE inl_config SET value='$do_cat_name_f' where name='do_cat_name'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_desc_f' where name='do_cat_desc'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_image_f' where name='do_cat_image'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_cust1_f' where name='do_cat_cust1'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_cust2_f' where name='do_cat_cust2'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_cust3_f' where name='do_cat_cust3'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_cust4_f' where name='do_cat_cust4'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_cust5_f' where name='do_cat_cust5'");
	$conn->Execute("UPDATE inl_config SET value='$do_cat_cust6_f' where name='do_cat_cust6'");
	/*
	$extended_search = $extended_search_f;
	$high_lighting_tag1 = $high_lighting_tag1_f;
	$high_lighting_tag2 = $high_lighting_tag2_f;
	$do_link_name = $do_link_name_f;
	$do_link_desc = $do_link_desc_f;
	$do_link_url = $do_link_url_f;
	$do_link_image = $do_link_image_f;
	$do_link_cust1 = $do_link_cust1_f;
	$do_link_cust2 = $do_link_cust2_f;
	$do_link_cust3 = $do_link_cust3_f;
	$do_link_cust4 = $do_link_cust4_f;
	$do_link_cust5 = $do_link_cust5_f;
	$do_link_cust6 = $do_link_cust6_f;
	
	$do_cat_name_f = $do_cat_name_f;
	$do_cat_desc_f = $do_cat_desc_f;
	$do_cat_image_f = $do_cat_image_f;
	$do_cat_cust1_f = $do_cat_cust1_f;
	$do_cat_cust2_f = $do_cat_cust2_f;
	$do_cat_cust3_f = $do_cat_cust3_f;
	$do_cat_cust4_f = $do_cat_cust4_f;
	$do_cat_cust5_f = $do_cat_cust5_f;
	$do_cat_cust6_f = $do_cat_cust6_f;

	*/
}
?>
