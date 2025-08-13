<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

//mari kita guna array!
$main_nav = array("general","articles","category","user","usergroups","styles","comments","converter");

/* --- $General --- */
$main_links['general'][] = "index.php,Home";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['general'][] = "settings.php,Settings";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['general'][] = "backup.php,Backup System";

/* --- $Usegroups --- */
if($usr->checkperm('',"isadmin",1) == true ) $main_links['user'][] = "user.php?do=add,Add User";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['user'][] = "user.php?do=managefields,Profile Fields";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['user'][] = "user.php,Manage Users";

/* --- $Usegroups --- */
if($usr->checkperm('',"isadmin",1) == true ) $main_links['usergroups'][] = "usergroups.php?do=add,Add";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['usergroups'][] = "usergroups.php,Manage";

/* --- $Styles --- */
if($usr->checkperm('',"isadmin",1) == true ) $main_links['styles'][] = "styles.php,Manage";

/* --- $Articles --- */
if($usr->checkperm('',"canpost",1) == true ) $main_links['articles'][] = "articles.php?do=addart,Add Article";

if($usr->checkperm('',"editown",1) == true || $usr->checkperm('',"editall",1) == true ) $main_links['articles'][] = "articles.php?do=manageart&amp;search=1&amp;sort=date&amp;order=desc,Manage";
if($usr->checkperm('',"canapprove",1) == true ) $main_links['articles'][] = "articles.php?do=manageart&amp;search=1&amp;sort=date&amp;order=desc&amp;status=0,Validate";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['articles'][] = "articles.php?do=managefields,Manage Fields";

if ( preg_match("#/admin#",$_SERVER['PHP_SELF']) )
{
	$main_links['articles'][] = "spacer";

	if($usr->checkperm('',"canpost",1) == true ) $main_links['articles'][] = "articles.php?do=importart,Import HTML File";
	if($usr->checkperm('',"isadmin",1) == true ) $main_links['articles'][] = "articles.php?do=massmove,Mass Move";
	if($usr->checkperm('',"isadmin",1) == true ) $main_links['articles'][] = "articles.php?do=clearcache,Clear All Caches";
}


/* --- $Category --- */
if($usr->checkperm('',"isadmin",1) == true ) $main_links['category'][] = "articles.php?do=addcat,Add Category";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['category'][] = "articles.php?do=managecat,Manage Categories";
if($usr->checkperm('',"isadmin",1) == true ) $main_links['category'][] = "articles.php?do=managecatfields,Manage Fields";

/* --- $Comments --- */
if($usr->checkperm('',"isadmin",1) == true ) $main_links['comments'][] = "comments.php,Manage Comments";

/* --- $converter --- */
if($usr->checkperm('',"isadmin",1) == true ) $main_links['converter'][] = "converter.php,Converter";

?>