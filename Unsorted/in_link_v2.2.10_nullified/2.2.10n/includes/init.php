<?php
	//Turns off the magic quotes
	@set_magic_quotes_runtime (0);
	// Overrides GPC variables 

	//load default english language variables
	if ($admin == 1)
	{	if(!file_exists("$language_path/english/language_admin.php"))	
		{	error("English language files are required. Failed to load admin language file: $language_path/english/language_admin.php",0); //fatal;
			die();
		}
		else
			include_once("$language_path/english/language_admin.php");
	}
	else
	{	if(!file_exists("$language_path/english/language.php"))	
		{	error("English language files are required. Failed to load language file: $language_path/english/language.php",0); //fatal;
			die();
		}
		else
			include_once("$language_path/english/language.php");
	}
	
	//open DB connection
	$conn=&ADONewConnection($sql_type);
	$conn->Connect($sql_server, $sql_user, $sql_pass, $sql_db);

	if(!$conn)
	{	error("Database connection failed. DB Type: $sql_type, DB Server: $sql_server, DB User: $sql_user, DB Name: $sql_db",0); //fatal;
		error($conn->ErrorMsg(),0);
		die();
	}

	//initialize all variables
	$rs =&$conn->Execute("select name,value from inl_config");
	while ($rs && !$rs->EOF) 
	{	$c_name = $rs->fields[0];
	    $c_value = $rs->fields[1];
		$$c_name = $c_value;
		$rs->MoveNext();
	}
	
	if($pconnect)
	{
		$conn->Close();
		$conn=&ADONewConnection($sql_type);
		$conn->PConnect($sql_server, $sql_user, $sql_pass, $sql_db);
	}


	if($debug_ado && $debug_ado==1)
	{
		$conn->debug=true;
	}
	else
	
	if(!$CookiesOn)
		$session_get=1;

	//session handling
	if($sid)
		$test = load_session($sid);
	//save browsing options
	$i_change=false;

	if($cat_sort_c && $cat_sort_c != $ses["cat_sort"])
	{	$ses["cat_sort"]=$cat_sort_c;
		$i_change=true;
	}
	elseif(!$ses["cat_sort"])
		$ses["cat_sort"]=$cat_sort; //default

	if($cat_order_c && $cat_order_c != $ses["cat_order"])
	{	$ses["cat_order"]=$cat_order_c;
		$i_change=true;
	}
	elseif(!$ses["cat_order"])
		$ses["cat_order"]=$cat_order; //default

	if($link_order_c && $link_order_c != $ses["link_order"])
	{	$ses["link_order"]=$link_order_c;
		$i_change=true;
	}
	elseif(!$ses["link_order"])
		$ses["link_order"]=$link_order; //default

	if($link_sort_c && $link_sort_c != $ses["link_sort"])
	{	$ses["link_sort"]=$link_sort_c;
		$i_change=true;
	}
	elseif(!$ses["link_sort"])
		$ses["link_sort"]=$link_sort; //default

	if($num_results && $num_results != $ses["num_res"])
	{	$ses["num_res"]=$num_results;
		$i_change=true;
	}
	elseif(!$ses["num_res"])
		$ses["num_res"]=$lim; //default

	if($inl_language=="default")
		$inl_language=$language;

	if($inl_language && $inl_language!=$ses["lang"])
	{	$ses["lang"]=$inl_language;
		$i_change=true;
	}
	elseif(!$ses["lang"])
		$ses["lang"]=$language; //default

	if($inl_theme=="default")
		$inl_theme=$theme;

	if($inl_theme && $inl_theme!=$ses["theme"])
	{	$ses["theme"]=$inl_theme;
		$i_change=true;
	}
	elseif(!$ses["theme"])
		$ses["theme"]=$theme; //default
		
	if($sid)
	{	if($i_change) //save new options
		{	if(!save_session($sid))
			{	error("Database Error",0); //fatal;
				die();
			}
		}
		else
			refresh_session($sid);
	}
	elseif($i_change)
	{	if(!$sid=init_session())
		{	error("Failed to create a session",0); //fatal;
			error($conn->ErrorMsg(),0); //fatal;
			die();
		}
		if(!save_session($sid))
		{	error("Database Error",0); //fatal;
			error($conn->ErrorMsg(),0); //fatal;
			die();
		}
	}
	
	//load language variables if not enlgish
	if($ses["lang"]!="english")
	{	if ($admin == 1)
		{	if(!file_exists("$language_path/".$ses["lang"]."/language_admin.php"))	
			{	error("Failed to load admin language file: $language_path/".$ses["lang"]."/language_admin.php",0); //fatal;
				die();
			}
			else
				include_once("$language_path/".$ses["lang"]."/language_admin.php");
		}
		else
		{	if(!file_exists("$language_path/".$ses["lang"]."/language.php"))	
			{	error("Failed to load language file: $language_path/".$ses["lang"]."/language.php",0); //fatal;
				die();
			}
			else
				include_once("$language_path/".$ses["lang"]."/language.php");
		}
	}
	//DROP THE TEMPORARLY TABLE FOR EXTENDED_SEARCH
	if ($extended_search == 1)
		if ($sid)
			if (!$having)
				$conn->Execute("DROP TABLE IF EXISTS inl_$sid");

	//check login for admin
	if($admin==1 && $thisfile!="login" && $thisfile!="index") //admin but not the login screen
	{
		
		if ($ses["user_perm"]==5)
		{
			if (!ereg($thisfile, $file_list))
			{
				error($la_login_expired,0);
				echo "<br>$la_click_to_login1 <a href='http://$server$filepath"."admin/login.php' target='_top'> $la_click_to_login2 </a> $la_click_to_login3";
				die();	
			}
		}
		elseif (($ses["user_perm"]>2) || (!$ses["user_perm"]))
		{
			error($la_login_expired,0);
			echo "<br>$la_click_to_login1 <a href='http://$server$filepath"."admin/login.php' target='_top'> $la_click_to_login2 </a> $la_click_to_login3";
			die();	
				
		}	
/*	
		if ((!ereg($thisfile, $file_list) && $ses["user_perm"]==5) || (!$ses["user_perm"]) || ($ses["user_perm"]>2 && $ses["user_perm"]!=5))
		{
		//	if(!$ses["user_perm"] || ($ses["user_perm"]>2)) //not root, admin or editor
			error($la_login_expired,0);
			echo "<br>$la_click_to_login1 <a href='http://$server$filepath"."admin/login.php' target='_top'> $la_click_to_login2 </a> $la_click_to_login3";
			die();
		}
*/
	}
?>