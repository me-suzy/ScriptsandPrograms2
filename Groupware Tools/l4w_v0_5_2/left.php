<?php

   	/*=====================================================================
	// $Id: left.php,v 1.4 2005/05/21 09:13:44 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/
die (__FILE__);
	include ("config/config.inc.php");
	include ("connect_database.php");
	include ("inc/functions.inc.php");
	//session_start();
    loadLanguageFile ();

	// --- GET / POST -----------------------------------------------
	$user_id    = var_include_int ("user_id",  "SESSION");
	$language   = var_include_int ("language", "SESSION");
	$login	    = var_include ("login",	       "SESSION");
	$passwort   = var_include ("passwort",     "SESSION");
	$group	    = var_include_int ("group",	   "SESSION");
	$show_nodes = var_include ("show_nodes",   "GET");
	
	security_check_core();
	


    // --- PHPGACL --------------------------------------------------
    require_once('extern/phpgacl/gacl.class.php');
    require_once('extern/phpgacl/gacl_api.class.php');
    //require_once('/admin/gacl_admin.inc.php');
    
    $gacl_options = array(
    						'debug' => false,
    						'items_per_page' => 100,
    						'max_select_box_items' => 100,
    						'max_search_return_items' => 200,
    						'db_type' => 'mysql',
    						'db_host' => $db_host,
    						'db_user' => $db_user,
    						'db_password' => $db_passwd,
    						'db_name' => $db_name,
    						'db_table_prefix' => 'gacl_',
    						'caching' => FALSE,
    						'force_cache_expire' => TRUE,
    						'cache_dir' => '/tmp/phpgacl_cache',
    						'cache_expire_time' => 600
    					);
    
    $gacl = new gacl($gacl_options);

	// --- pagestats ------------------------------------------------
	set_page_stats(__FILE__);

	// --- Set Locale -----------------------------------------------
	$lang_res = mysql_query ("SELECT set_local_str FROM languages WHERE lang_id='$language'");
	$lang_row = mysql_fetch_array ($lang_res);
    logDBError (__FILE__, __LINE__, mysql_error());
	if ($lang_row <> "")
		setlocale (LC_TIME, $lang_row[0]);

	// --- Header & Javascripts -------------------------------------
	include ("inc/header_left.inc.php");
?>
	<script TYPE="text/javascript">
	function open_help () {
		window.open ("http://217.172.179.216/l4w_help", "l4w_help", "");
	}
	</script>

	<table border=1 cellpadding=0 cellspacing=0 width="200">
		<tr>
			<td align='left' class=leiste height=25 background='<?=$img_path?>leiste_bg_left.jpg'>
				<?php $title = translate ("user")?>
				&nbsp;
				<a href='http://www.evandor.de' class=leiste target='new'>
				<img src='<?=$img_path?>user.gif' alt='<?=$title?>' title='<?=$title?>' align=top border=0>
				<?php
					$user_res = mysql_query ("SELECT firstname, lastname FROM users where id='$user_id'");
					$user_row = mysql_fetch_array ($user_res);
				    logDBError (__FILE__, __LINE__, mysql_error());
					$show_name = substr(trim ($user_row['firstname']),0,1).". ".$user_row['lastname'];
					if ($show_name == "") $show_name = "Name nicht bekannt";
					echo $show_name;
				?>
				</a>
			</td>
		</tr>
	</table>

    <table border=0 cellpadding=0 cellspacing=0 background='<?=$img_path?>tree_bg.gif'
       width="200" height="23">
	<tr>
    <td class='iconleiste'>
        <a href='javascript:expand_all(tree, true);'><img src='<?=$img_path?>all_auf.gif' title='<?=translate ("expand tree")?>' border=0></a><a
	       href='javascript:collapse_all(tree, true);expand_me(tree,0);'><img src='<?=$img_path?>all_zu.gif' title='<?=translate ("collapse tree")?>' border=0></a><a
	       href='javascript:save_current(tree,"<?=session_id()?>",this);'><img src='<?=$img_path?>save.gif' title='<?=translate ("save tree view")?>' border=0></a><a
	       href='javascript:actualize(tree,"<?=session_id()?>",this);'><img src='<?=$img_path?>aktuell.gif' title='<?=translate ("refresh")?>' border=0></a><a
	       href='doc/manual.html' target='new'><img src='<?=$img_path?>question.gif' title='<?=translate ("leads4web help")?>' border=0></a><a
	       href='logout.php'><img src='<?=$img_path?>exit.gif' title='<?=translate ("logout")?>' border=0></a>
    </td>
	</tr>
	</table>

	<script TYPE="text/javascript">
	<?php  
	    include ("inc/build_tree.php"); 
    	//include ("inc/build_tree2.php"); 
	?>
	</script>
	<script TYPE="text/javascript" src="javascripts/wmtree.js"></script>
	<script TYPE="text/javascript" >
		var tree = new WWMTree (TREE_NODES,
			"<?=$img_path?>expand.gif",
			"<?=$img_path?>collapse.gif");
	   <?php  include ("inc/expand_tree.php"); ?>

	</script>

<div id='save_message' class='hiddenmessage' style="position:absolute; top:29; left:130; visibility:hidden;">
<?=translate ("saved")?>
</div>
</body>
</html>