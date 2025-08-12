<?php

   	/*=====================================================================
	// $Id: build_tree.php,v 1.6 2005/04/28 15:14:38 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/
	die ("depreciated");
	
	$nodes_string = "
	var TREE_NODES = [

    ['<b>".$version_name."</b>', null, null,";
	
	if (module_enabled ('contacts')) {
    	$nodes_string .= "  
        ['<img src=\"".$img_path."contacts.gif\" align=\"absmiddle\" border=0> ".translate ("contacts")."</a>', null, null,
		    ['&rarr; ".translate ("new contact")."', 'modules/contacts/index.php?command=add_contact_view',  'l4w_main'],
            ['&rarr; ".translate ("search")."',      'modules/contacts/index.php?command=show_entries',      'l4w_main']
        ],
	    ";
	}      
	  
	if (module_enabled ('companies')) {
    	$nodes_string .= "  
        ['<img src=\"".$img_path."companies.gif\" align=\"absmiddle\" border=0> ".translate ("companies")."</a>&nbsp;<a href=\"new_company.php\" target=\"main\"><img src=\"".$img_path."small_plus.gif\" align=\"absmiddle\" border=0></a>&nbsp;<a href=\"companies.php\" target=\"main\"><img src=\"".$img_path."lupe.gif\" align=\"absmiddle\" border=0></a>', null, null,
		    ['&rarr; ".translate ("new company")."',     'new_company.php',     'l4w_main'],
			['&rarr; ".translate ("search")."',          'companies.php',       'l4w_main']
        ],
	    ";
	}      

	if (module_enabled ('emails')) {
    	$nodes_string .= "  
		['<img src=\"".$img_path."emailer.gif\" align=\"absmiddle\" border=0> ".translate ("email")."</a>&nbsp;<a href=\"sendmailform.php\" target=\"_blank\"><img src=\"".$img_path."small_plus.gif\" align=\"absmiddle\" border=0>', null, null,
		    ['&bull; ".translate ("get mails")."',  '".$new_email_file."*Emails*toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=400,height=300', '_jsopen'],
			['&bull; ".translate ("send mail")."',  'sendmailform.php*Emails*toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=580,height=500', '_jsopen'],
			['  <img src=\"".$img_path."openfolder.gif\" align=\"absmiddle\" border=0> ".translate ("inbox")." - <span id=\"inbox_new_mails\">".$anz_inbox_row_new[0]."</span> neu', 'email.php?folder=1', 'l4w_main']
        ],
	    ";
	}      

	if (module_enabled ('stats')) {
    	$nodes_string .= "  
		['<img src=\"".$img_path."stats.gif\" align=\"middle\" border=0> ".translate ("statistic")."</a>', null, null,
            ['&bull; ".translate ("page_stats")."', 'page_stats.php',       'l4w_main']
        ],
	    ";
	}      

	$nodes_string .= "  
        ['<img src=\"".$img_path."/admin.gif\" align=\"absmiddle\" border=0> ".translate ("options")."', null, null,
	        ['".translate ("skins")."', null, null ~~tree_skins~~],
		    ['".translate ("languages")."', null, null ~~tree_sprachen~~],
        ],
		['<img src=\"".$img_path."/admin.gif\" align=\"absmiddle\" border=0> ".translate ("administration")."', null, null,
		    ~~rights~~
        ],
        ['<img src=\"".$img_path."quicklinks.gif\" align=\"absmiddle\" border=0> ".translate ("quicklinks")."</a>', 'quicklinks.php', 'l4w_main'],
		['<img src=\"".$img_path."exit.gif\" align=\"absmiddle\" border=0> Logout', 'logout.php', '_top']
    ]
	];";


	function tree_escape ($string) {
		$ret_str = str_replace ("[", "", $string);
		$ret_str = str_replace ("]", "", $string);
		$ret_str = str_replace ("'", "\'", $string);
		return $ret_str;
	}

	// Datum:
    $datum = date ("Y-m-d");


	
	//========================================================================================
	// Skins
	//========================================================================================
	$skins_nodes = ",";
	$user_res	  = mysql_query ("SELECT skin FROM ".TABLE_PREFIX."user_details WHERE user_id='$user_id'");
	logDBError (__FILE__, __LINE__, mysql_error());
	$user_row	  = mysql_fetch_array ($user_res);

	$skins_res = mysql_query ("SELECT * FROM skins");
	logDBError (__FILE__, __LINE__, mysql_error());
	while ($skin_row = mysql_fetch_array ($skins_res)) {
		$display_name = tree_escape($skin_row['name']);
		$display_url  = "'change_skin.php?skin=".$skin_row['id']."'";
		$display_target = "'_top'";
		if ($skin_row['id'] == $user_row['skin']) {
		   $display_name = "<span class=selected>".tree_escape($skin_row['name'])."</span>";
		   $display_url  = "null";
		   $display_target= "null";
		}
		$skins_nodes .= "['&bull; ".$display_name."',	  ".$display_url.", ".$display_target."],\n";
	}
	$skins_nodes = substr ($skins_nodes, 0, -1);

	$nodes_string = str_replace ('~~tree_skins~~', $skins_nodes, $nodes_string);

	//========================================================================================
	// Rights
	//========================================================================================
	$rights_nodes = '';

	// Eigende Angaben
	$rights_nodes .= "\n['&bull; ".translate ("user info")."', 'modules/users/index.php?command=view_user&use_user=".$_SESSION['user_id']."&self=true', 'l4w_main'],";

    if ($gacl->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id']))
        $rights_nodes .= "\n['&bull; <font color=\"red\">".translate ("usermanager")."</font>', 'modules/users/index.php?command=show_users',       'l4w_main'],";

    if ($gacl->acl_check('Groupmanager', 'Show', 'Person', $_SESSION['user_id']))
        $rights_nodes .= "\n['&bull; <font color=\"red\">".translate ("groupmanager")."</font>', 'modules/groups/index.php?command=show_groups',       'l4w_main'],";

    if ($gacl->acl_check('Use Leads4web', 'Show Logfile', 'Person', $_SESSION['user_id']))
       $rights_nodes .= "\n['&bull; <font color=\"red\">Leads4web/4 ".translate ("logfile")."</font>', 'show_log.php',       'l4w_main'],";

    $rights_nodes .= "\n['&bull; <font color=\"red\">".translate ("tree")."</font>', 'modules/tree/index.php?command=show_entries',       'l4w_main'],";

    //if ($gacl->acl_check('Use Leads4web', 'Show Logfile', 'Person', $_SESSION['user_id']))
    //   $rights_nodes .= "\n['&bull;".translate ("php info")."', 'show_phpinfo.php',       'l4w_main'],";


	if (strlen($rights_nodes) == 1) // nur ein Komma
		$rights_nodes = "";
	else // letztes Komma entfernen
		$rights_nodes = substr ($rights_nodes, 0, -1);

	$nodes_string = str_replace ('~~rights~~', $rights_nodes, $nodes_string);

	//========================================================================================
	// Sprachen
	//========================================================================================
	$sprachen_nodes = ",";
	$user_res	  = mysql_query ("SELECT lang FROM ".TABLE_PREFIX."user_details WHERE user_id='$user_id'");
	logDBError (__FILE__, __LINE__, mysql_error());
	$user_row	  = mysql_fetch_array ($user_res);

	$sprachen_res = mysql_query ("SELECT * FROM languages WHERE aktiv<>0");
	logDBError (__FILE__, __LINE__, mysql_error());
	while ($sprachen_row = mysql_fetch_array ($sprachen_res)) {
		$display_name = tree_escape(translate ($sprachen_row['language']));
		$display_url  = "'change_language.php?sprache=".$sprachen_row['lang_id']."'";
		$display_target = "'_top'";
		if ($sprachen_row['lang_id'] != $_SESSION['language']) {
    		$sprachen_nodes .= "['&bull; ".$display_name."',	".$display_url.", ".$display_target."],\n";
		}
	}
	$sprachen_nodes = substr ($sprachen_nodes, 0, -1);

	$nodes_string = str_replace ('~~tree_sprachen~~', $sprachen_nodes, $nodes_string);

	echo $nodes_string;
	

?>

