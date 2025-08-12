<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------


// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }

MinLevel(3);


function startAdmin() {
	global $Pivot_Vars, $adminInternal;
	if(isset($adminInternal[$Pivot_Vars['do']])) {
		$func = $adminInternal[$Pivot_Vars['do']];
		if(function_exists($func)) {
			$func();
		} else{
			ErrorOut('Function: <i>'.$adminInternal[$Pivot_Vars['do']].'</i> does not exist in pv_admin.php.');
		}
	} else {
		adminMain();
	}
}

function adminMain() {
	
	PageHeader(lang('userbar','admin_title'), 1);
	
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('userbar','admin_title'));

	$admin_funcs = array(
	array(lang('adminbar','seeusers'), 'seeusers', lang('admin','seeusers')),
	array(lang('adminbar','categories'), 'categories' , lang('adminbar','categories_title') ),
	array(lang('adminbar','blogs'), 'blogs', lang('admin','blogs')),
	array(lang('adminbar','filemappings'), 'filemappings', lang('adminbar','filemappings_title')),
	array(lang('adminbar','templates'), 'templates', lang('admin','templates')),
	array(lang('adminbar','seeconfig'), 'seeconfig', lang('admin','seeconfig')),
	array(lang('adminbar','maintenance'), 'maintenance', lang('admin','maintenance')),
	);
	DispPage($admin_funcs, 'administration');

	PageFooter();
}




function see_config() {

	PageHeader(lang('adminbar','seeconfig_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','seeconfig_title'));

	StartForm('save_config', 1);
	StartTable();

	DisplaySettings(get_config());
	EndForm(lang('config','save'), 1);
	PageFooter();
}


function see_categories($err=0) {
	global $Cfg, $Pivot_Vars;
	PageHeader(lang('adminbar','categories_title'), 1, 'switch');
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','categories_title'));

	$cat_page = array();

	$cats= cfg_cats();
	
	//get the list of people that can post..
	$overall = explode("|", $Cfg['users']);
	
	foreach($cats as $cat){
		
		// Allowed users, but only the ones that still exist.
		$allowed = implode(", ", array_intersect($cat['allowed'], $overall));
		
		$title   = sprintf("%s </a>(users: %s)</i>", $cat['name'], $allowed);
		
		if( $cat['searchexclusion'] == 1) { $title .= ' [not indexed]'; }
		if( $cat['nonpublic'] == 1) { $title .= ' [not public]'; }
		if( $cat['hidden'] == 1) { $title .= ' [hidden]'; }

		array_push( $cat_page, array( $title,'cat_edit&amp;cat='.urlencode( $cat['name'] ),sprintf( lang( 'category','edit_who' ),$cat['name'] )));
	}

	DispPage($cat_page, 'category');
	if($err==1){
		$err_msg = lang('category','same_name');
	}elseif($err==2){
		$err_msg = lang('category','need_name');
	}

	StartForm('save_cat', 1);
	StartTable();
	GenSetting('', lang('category','make_new'), '', 8, '', 2);
	GenSetting('cat_name', lang('category','name'), $err_msg, 0, $Pivot_Vars['cat_name']);
	
	foreach(explode("|", $Cfg['users']) as $user){
		$rvar[] = $user;
		$rvar[] = $user;
	}
	$cols = (count(explode("|", $Cfg['users'])) -2);
	if($cols < 4){
		$cols = 4;
	}
	GenSetting('allowed', lang('category','users'), lang('category','canpost'), 4, $rvar, $cols, 'multiple');

	GenSetting('new','','',7,'1');
	
	// 2004/10/14 =*=*= JM - incorporate search-index switch
	GenSetting( 'cat_searchindex',lang( 'category','search_index_newctitle' ),lang( 'category','search_index_newcdesc' ),3,'yn','','1' );
	// Order setting
	GenSetting('cat_order', lang('category','order'), lang( 'category','order_desc' ), 0, '100');
	// Public setting..
	GenSetting( 'cat_public',lang( 'category','public' ),lang( 'category','public_desc' ),3,'yn','','1' );
	// Hidden setting..
	GenSetting( 'cat_hidden',lang( 'category','hidden' ),lang( 'category','hidden_desc' ),3,'yn','','0' );

	echo '</table>';
	EndForm(lang('category','create'));
	PageFooter();
}


function edit_category() {
	global $Cfg, $Pivot_Vars;
	PageHeader(lang('adminbar','categories_title'), 1, 'switch');
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','categories_title'));

	//get the list of people that can post..
	$overall = explode("|", $Cfg['users']);
	
	// Allowed users, but only the ones that still exist.
	$allowed = explode("|", $Cfg['cat-'. $Pivot_Vars['cat']]);
	$allowed = array_intersect($allowed, $overall);
	
	foreach($overall as $cow){
		if(!in_array($cow, $allowed)){
			$denied[] = $cow;
		}
	}
	// 2004/10/15 =*=*= JM - get the cats-searchexclusion value ready for the button
	$cat_searchindex = 1; // searchindex (Yes) is always default
	$cats_nosearch   = cfg_cat_nosearchindex();
	if( in_array( $Pivot_Vars['cat'],$cats_nosearch )) {
		$cat_searchindex = 0;
	}

	// get the order of the cat..
	if (isset($Cfg['cats-order'])) {
		$temp_arr = explode("|-|", $Cfg['cats-order']);
		foreach($temp_arr as $temp_item) {
			list ($cat, $order) = explode('|', $temp_item);
			if ($cat==$Pivot_Vars['cat']) {
				$cat_order = $order;
			}
		}
	}
	
	// get public / non-public status
	$cat_public = 1;
	if (isset($Cfg['cats-nonpublic'])) {
		$nonpublic_arr = explode("|", $Cfg['cats-nonpublic']);
		foreach($nonpublic_arr as $temp_item) {
			if ($temp_item == $Pivot_Vars['cat']) {
				$cat_public = 0;	
			}	
		}
	}
	
	// get hidden status
	$cat_hidden = 0;
	if (isset($Cfg['cats-hidden'])) {
		$hidden_arr = explode("|", $Cfg['cats-hidden']);
		foreach($hidden_arr as $temp_item) {
			if ($temp_item == $Pivot_Vars['cat']) {
				$cat_hidden = 1;	
			}	
		}
	}
		
	StartForm('save_cat', 1, 'onSubmit="selectAll()"');
	GenSetting('new','','',7,'0');
	GenSetting('cat_name','','',7, $Pivot_Vars['cat']);
	StartTable('', 3);
	GenSetting('heading', lang('category','edit'), '', 8, '', '3', '');

	echo '<tr><td width="40%" style="align: center";><b>' . lang('category','allowed') .'</b><br />';
	echo '<select name="allowed[]" id="allowed" multiple size="5" style="width: 140px; height: 140px;" onDblClick="moveOver(this.form.allowed,this.form.denied)">';
	foreach($allowed as $name){
		if(strlen($name) > 2){
			echo '<option value="'.$name.'">'.$name.'</option>';
		}
	}
	
	echo '</select>';
	echo '</td><td><br /></td><td width="40%" style="align: center;"><b>'.lang('category','denied') .'</b><br />';
	echo '<select name="denied[]" id="denied" multiple size="5" style="width: 140px; height: 140px;" onDblClick="moveOver(this.form.denied,this.form.allowed)">';
	foreach($denied as $name){
		if(strlen($name) > 2){
			echo '<option value="'.$name.'">'.$name.'</option>';
		}
	}
	echo '</select>';
	echo '</td></tr><tr><td width="40%" style="align:center;">';
	echo '<input type="button" value="&gt;&gt; ' . lang('category','deny') .'" onclick="moveOver(this.form.allowed,this.form.denied)" />';
	echo '</td><td><br /></td><td width="40%" style="align: center;">';
	echo '<input type="button" value="&lt;&lt; ' . lang('category','allow') .'" onclick="moveOver(this.form.denied,this.form.allowed)" />';
	echo '</td></tr>';
	echo '<tr><td colspan="3" style="padding-left:18%;padding-bottom:7px;">'.lang( 'category','canpost' ).'</td></tr>';
	
	//$Cfg['cat_public'] = 0;
	
	StartTable('', 3);
	// 2004/10/14 =*=*= JM - incorporate search-index switch
	GenSetting( 'cat_searchindex',lang( 'category','search_index_newctitle' ),lang( 'category','search_index_newcdesc' ),3,'yn','',$cat_searchindex );
	// Order setting
	GenSetting('cat_order', lang('category','order'), lang( 'category','order_desc' ), 0, $cat_order);
	// Public setting..
	GenSetting( 'cat_public',lang( 'category','public' ),lang( 'category','public_desc' ),3,'yn','', $cat_public );
	// Hidden setting..
	GenSetting( 'cat_hidden',lang( 'category','hidden' ),lang( 'category','hidden_desc' ),3,'yn','', $cat_hidden );

	
	echo "<tr><td colspan='3' align='right'>";
	EndForm( lang('category','edit' ));
	echo '</td></tr>';
	echo '</table>';

	echo '<table cellspacing="0" cellpadding="2" border="0" width="480" class="sTable">'."\n";
	StartForm('del_cat', 1);
	GenSetting('heading', lang('category','delete').': '.$Pivot_Vars['cat'], '', 8, '', '2', '');
	GenSetting('delete', lang('delete'), lang('category', 'delete_desc'), 3, 'yn', '', '0');
	GenSetting('cat', '', '', 7, $Pivot_Vars['cat']);
	echo "<tr><td colspan='3' align='right'>";
	EndForm(lang('category', 'delete'));
	echo '</td></tr>';
	echo '</table>';

	// stuff goes here..

	PageFooter();
}

function save_categories(){
	global $Cfg, $Pivot_Vars;	
	
	// get the array of order..
	if (isset($Cfg['cats-order'])) {
		$temp_arr = explode("|-|", $Cfg['cats-order']);
		foreach($temp_arr as $temp_item) {
			list ($cat, $order) = explode('|', $temp_item);
			$order_arr[$cat] = $order;
		}
	}	
	
	// set the order..
	$order_arr[ $Pivot_Vars['cat_name'] ] = $Pivot_Vars['cat_order'];
	$temp_arr= array();
	foreach ($order_arr as $cat => $order) {
		$temp_arr[] = $cat."|".$order;
	}
	$Cfg['cats-order'] = implode("|-|",$temp_arr);
	
	
	// get the array of non-public categories
	if (isset($Cfg['cats-nonpublic'])) {
		$nonpublic_arr = explode("|", $Cfg['cats-nonpublic']);
	}
	
	// set the 'non-public'
	if ( ($Pivot_Vars['cat_public']==1) && (count($nonpublic_arr) > 0) ) {
		foreach($nonpublic_arr as $key => $cat) {
			if ($cat == $Pivot_Vars['cat_name']) {
				unset($nonpublic_arr[$key]);	
			}	
		}
	} else if ($Pivot_Vars['cat_public']==0) {
		$nonpublic_arr[] = $Pivot_Vars['cat_name'] ;
	}
	if (is_array($nonpublic_arr)) {
		$Cfg['cats-nonpublic'] = implode("|",array_unique($nonpublic_arr));
	} else {
		$Cfg['cats-nonpublic'] = "";
	}
	
	// get the array of hidden categories
	if (isset($Cfg['cats-hidden'])) {
		$hidden_arr = explode("|", $Cfg['cats-hidden']);
	}

	
	// set the 'hidden'
	if ( ($Pivot_Vars['cat_hidden']==0) && (count($hidden_arr) > 0) ) {
		foreach($hidden_arr as $key => $cat) {
			if ($cat == $Pivot_Vars['cat_name']) {
				unset($hidden_arr[$key]);	
			}	
		}
	} else if ($Pivot_Vars['cat_hidden']==1){
		$hidden_arr[] = $Pivot_Vars['cat_name'] ;
	}
	if (is_array($hidden_arr)) {
		$Cfg['cats-hidden'] = implode("|",array_unique($hidden_arr));
	} else {
		$Cfg['cats-hidden'] = "";
	}
	
	// get the array of searchexclusion categories
	if (isset($Cfg['cats-nonpublic'])) {
		$searchexclusion_arr = explode("|", $Cfg['cats-nonpublic']);
	}

	
	// set the 'searchexclusion'
	if ( ($Pivot_Vars['cat_searchindex']==1) && (count($searchexclusion_arr) > 0) ) {
		foreach($searchexclusion_arr as $key => $cat) {
			if ($cat == $Pivot_Vars['cat_name']) {
				unset($searchexclusion_arr[$key]);	
			}	
		}
	} else if ($Pivot_Vars['cat_searchindex']==0) {
		$searchexclusion_arr[] = $Pivot_Vars['cat_name'] ;
	}

	if (is_array($searchexclusion_arr)) {
		$Cfg['cats-searchexclusion'] = implode("|",array_unique($searchexclusion_arr));
	} else {
		$Cfg['cats-searchexclusion'] = "";
	}

	if($Pivot_Vars['new']==1){
		// make sure new cat does not contain any exotic characters.
		$Pivot_Vars['cat_name'] = safe_string($Pivot_Vars['cat_name']);

		// 2004/10/15 =*=*= JM
		searchindex_categories();
		
		if($Cfg['cat-' . $Pivot_Vars['cat_name']]){
			see_categories(1);
		}elseif(strlen($Pivot_Vars['cat_name']) < 1){
			see_categories(2);
		}else{
			@$Cfg['cat-' . $Pivot_Vars['cat_name']] = implode("|", $Pivot_Vars['allowed']);
			$Cfg['cats'] .= "|". $Pivot_Vars['cat_name'];
			see_categories();
		}
	}else{
		@$Cfg['cat-' . $Pivot_Vars['cat_name']] = implode("|", $Pivot_Vars['allowed']);
		
		$cats = cfg_getarray('cats');
		
		$cats[] = $Pivot_Vars['name'];
		unset($category['']);
		$Cfg['cats'] =  implode("|", array_unique($cats));
		see_categories();
	}

}


/*  2004/10/15 =*=*= JM
this deals with the searchindex flag for categories from admin pages

Let's have a look - in whatever the case, 
if cat_search_index = '0' (No) then add the cat to cats-searchexclusion array
if cat_search_index = '1' (Yes) and cat is in cats-searchexclusion array then remove it

Nota: this currently does nothing for categories that have been erased
*/
function searchindex_categories() {	
	global $Cfg, $Pivot_Vars;
		
	// what is the category?
	if( isset( $Pivot_Vars['name'] ))     { $thisCat = $Pivot_Vars['name']; }
	if( isset( $Pivot_Vars['cat_name'] )) { $thisCat = $Pivot_Vars['cat_name']; }
	// only proceed is we have a category...
	if( isset( $thisCat )) {
		// what is in there
		$cats_nosearch = cfg_cat_nosearchindex() ;
		// do what we have to 1 -> add, 0 -> remove	
		switch( $Pivot_Vars['cat_searchindex'] ) {
			case( 0 ) :
				$cats_nosearch[] = $thisCat ;
				break ;
			case( 1 ) : // drop through
			default :
				// do we need to remove?
				if( in_array( $thisCat,$cats_nosearch )) {
					// clear it
					$newArray = array();
					foreach( $cats_nosearch as $key => $value ) {
						if( $value!=$thisCat ) { $newArray[] = $value; }						
					}
					unset( $cats_nosearch ) ;
					$cats_nosearch = $newArray ;
				}
		}
		// put data back into Cfg
		$Cfg['cats-searchexclusion'] = implode( '|',array_unique( $cats_nosearch ));
	}
	// needed if called alone
	if( 1!=$Pivot_Vars['new']) {
		see_categories();
	}
}

// 2004/10/15 - JM
// this function now also removes deleted categories from the 'cat-searchexclusion' array
function delete_category(){
	global $Cfg, $Pivot_Vars;

	if($Pivot_Vars['confirmed']!=1){
		$vars = array('category', $Pivot_Vars['cat']);
		ConfirmPage(lang('category','delete'), $vars, sprintf(lang('category','delete_message'), $Pivot_Vars['username']));
	}else{
		$cats = cfg_getarray('cats');
		foreach($cats as $cat){
			if($cat != $Pivot_Vars['category']){
				$del_cat[] = $cat;
			}
		}
		$Cfg['cats'] = implode($del_cat, "|");
		unset($Cfg['cat-' . $Pivot_Vars['category']]);
		
		// 2004/10/15 =*=*= JM
		// if it is in cats-searchexclusion - delete also
		$cats_nosearch = cfg_cat_nosearchindex() ;
		if( in_array( $Pivot_Vars['category'],$cats_nosearch )) {
			// destroy in array
			foreach( $cats_nosearch as $key => $value ) {
				if( $value == $Pivot_Vars['category'] ) {
					unset( $cats_nosearch[$key] );
					// put data back into Cfg
					$Cfg['cats-searchexclusion'] = implode( '|',array_unique( $cats_nosearch ));
					break;
				}
			}
		}
		// END
		see_categories();
	}


}

function regen() {
	global $Cfg, $db, $done_archives, $totalfiles, $archive_array_next, $archive_array_prev, $Pivot_Vars, $Archive_array;
	PageHeader(lang('adminbar','regen_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','regen_title'));

	// initialise the threshold..
	if (isset($Cfg['rebuild_threshold']) && ($Cfg['rebuild_threshold']>4)) {
		$threshold = $Cfg['rebuild_threshold'];
	} else {
		$threshold = 28;
	}
	
	// stuff goes here..
	include_once("modules/module_parser.php");

	$db = new db();

	// also force the archive index file to be updated
	@unlink('db/ser-archives.php');
	// Make a new archive array.
	$Archive_array = make_archive_array();
	
	// ugly hack, to get a big slice of mem back..
	$entries_arr = $db->getcodes_range("0000-00-00-00-00", "2010-01-01-00-00","","", FALSE);

	$totalfiles = 0;
	$count=0;

	if (isset($Pivot_Vars['count'])) {
		$count = $Pivot_Vars['count'];
		$totalfiles = $Pivot_Vars['totalfiles'];
		reset($entries_arr);
		do {
			next($entries_arr);
			$key=key($entries_arr);
			//echo "(k $key)";
		} while ($key < $Pivot_Vars['count']);
	}


	do {
		
		$key=key($entries_arr);
		$loopentry = current($entries_arr);
		
		generate_pages($loopentry, TRUE, TRUE, TRUE);
		
		$count++;

		if (($count % 10) == 0) {
			printf("%1.2f sec: Processed %d entries.."."<br />\n", timetaken('int'), $count);
			flush();
		}

		// server friendly generate all..
		if (timetaken("int")>$threshold) {
			$myurl = sprintf("index.php?session=%s&menu=admin&func=admin&do=regen&count=%s&totalfiles=%s", $Pivot_Vars['session'], $count, $totalfiles);
		
			printf('<script> self.location = "%s"; </script>',$myurl);
			die();
		}

	} while (next($entries_arr));

	printf("<br />A total of <b>%s</b> entries have been processed, and <b>%s</b> files have been created"."<br />\n", $count, $totalfiles);

	
	echo "<p>Finished!</p>";

	PageFooter();
}


function filemappings() {
	global $Pivot_Vars, $Weblogs;

	PageHeader(lang('adminbar','filemappings'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','filemappings'));

	echo "<p>" . wordwrap( lang('adminbar','filemappings_desc'), 90, '<br />'). "</p>";

	foreach ($Weblogs as $key => $thislog) {
		printf("<br /><h2>Weblog '%s' (internal name: '%s')</h2>", $thislog['name'], $key);

		// Frontpage..
		$name = $thislog['front_path'] . $thislog['front_filename'];
		printf("<br /><h3>Frontpage: </h3>");
		printf("<p>The Frontpage will be written to file: <span class='filename'>%s</span>", $name);
		printf(" (<a href='%s' target='_blank'>view</a>)<br />", $name);
		testfile($name);

		$name = 'templates/'.$thislog['front_template'];
		printf("It uses the template file: <span class='filename'>%s</span>", $name);
		$link = sprintf("index.php?session=%s&amp;menu=admin&amp;func=admin&amp;do=templates&amp;edit=%s",
		$Pivot_Vars['session'], $thislog['front_template']);
		printf(" (<a href='%s' target='_blank'>edit</a>)<br />", $link);
		//testfile($name);

		echo "</p>";

		// subweblogs..

		printf("<p>This weblog has %s subweblogs:<br />\n", count($thislog['sub_weblog']));
		foreach ($thislog['sub_weblog'] as $key => $thissublog) {
			printf("- <b>%s</b> uses template: <span class='filename'>templates/%s</span>", $key, $thissublog['template']);
			$link = sprintf("index.php?session=%s&amp;menu=admin&amp;func=admin&amp;do=templates&amp;edit=%s",
			$Pivot_Vars['session'], $thissublog['template']);
			printf(" (<a href='%s'>edit</a>)<br />\n", $link);
		}

		echo "</p>";

		// Archive..
		$name = $thislog['archive_path'] . $thislog['archive_filename'];
		printf("<br /><h3>Archives: </h3>\n");
		printf("<p>The Archives will be written to files: <span class='filename'>%s</span> ('%%1' is used for the varying date format) <br />\n", $name);
		testfile($name);

		$name = 'templates/'.$thislog['archive_template'];
		printf("It uses the template file: <span class='filename'>%s</span>\n", $name);
		$link = sprintf("index.php?session=%s&amp;menu=admin&amp;func=admin&amp;do=templates&amp;edit=%s",
		$Pivot_Vars['session'], $thislog['archive_template']);
		printf(" (<a href='%s' target='_blank'>edit</a>)<br />\n", $link);
		//testfile($name);

		echo "</p>";

		// Single entries..
		$name = $thislog['entry_path'] . $thislog['entry_filename'];
		printf("<br /><h3>Single Entries: </h3>");
		if ($thislog['live_entries']==0) {
			printf("<p>Single entry pages be written to file: <span class='filename'>%s</span> ('%%1' is used for the varying entry format) <br />\n", $name);
			testfile($name);
		} else {
			printf("<p>Single entry pages are created dynamically.<br />\n");
		}

		$name = 'templates/'.$thislog['entry_template'];
		printf("It uses the template file: <span class='filename'>%s</span>", $name);
		$link = sprintf("index.php?session=%s&amp;menu=admin&amp;func=admin&amp;do=templates&amp;edit=%s",
		$Pivot_Vars['session'], $thislog['entry_template']);
		printf(" (<a href='%s' target='_blank'>edit</a>)<br />", $link);
		//testfile($name);

		echo "</p>";

		// Feeds..
		$name = $thislog['entry_path'] . $thislog['entry_filename'];
		printf("<br /><h3>Syndication Feeds: </h3>");
		if ($thislog['rss']==0) {
			echo "<p>No Feeds are created by this weblog.</p>";
		} else {
			if ($thislog['rss_filename']!="") {
				$name = $thislog['rss_path'] . $thislog['rss_filename'];
				printf("<p>The RSS feed will be written to file: <span class='filename'>%s</span>\n", $name);
				printf(" (<a href='%s' target='_blank'>view</a>)<br />", $name);
				testfile($name);
			} else {
				echo "<p>No RSS feed will be created, because no name was set in the weblog's config</p>";
			}

			if ($thislog['atom_filename']!="") {
				$name = $thislog['rss_path'] . $thislog['atom_filename'];
				printf("<p>The Atom feed will be written to file: <span class='filename'>%s</span>\n", $name);
				printf(" (<a href='%s' target='_blank'>view</a>)<br />", $name);
				testfile($name);
			} else {
				echo "<p>No RSS feed will be created, because no name was set in the weblog's config</p>";
			}
		}
	}
}



// CHANGES: PAUL 04/05/03
function templates() {
	global $Pivot_Vars;

	PageHeader(lang('adminbar','templates_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','templates_title'));

	// if there is an 'action' to do
	if (isset($Pivot_Vars['doaction'])) {
		files_action($Pivot_Vars['action'], $Pivot_Vars['check']);
	}

	if(isset($Pivot_Vars['edit']) && !isset($Pivot_Vars['template'])) {
		edit_template();
	} else if(isset($Pivot_Vars['template'])) {
		save_template();
	} else if(isset($Pivot_Vars['create'])) {
		create_template();
	} else {
		show_templates();
	}
}


function show_templates() {
	global $Cfg, $Pivot_Vars;

	DispPage(array(
	array(lang('templates', 'create_template'), 'templates&amp;create=true', lang('templates', 'create_template_info')),
	), 'templates');

	$myDeleteUrl =sprintf("index.php?session=%s&amp;menu=admin&amp;do=templates&amp;doaction=1", $Pivot_Vars['session']);
	printf("<form name='form1' method='post' action='%s'>", $myDeleteUrl);

	echo '<table cellspacing="0" class="tabular_border">';

	printf('<tr class="tabular_header"><td>&nbsp;</td><td width="300">%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', lang('upload','filename')  , lang('upload','date') , lang('upload','filesize') , lang('adminbar','description') );

	$d = opendir('templates/') or die(lang('error','path_open'));
	while(false !== ($f = readdir($d))) {
		$files[] = $f;
	}

	sort($files);

	foreach ($files as $f) {
		if(is_file('templates/'.$f)) {
			if(preg_match('/(.*)(html|css|php|htm|xml|txt)$/i','templates/'.$f)) {
				$fs = round(filesize('templates/'.$f)/1024);
				$fd = format_date(date('Y-m-d-H-i',filemtime('templates/'.$f)),'%day%-%month%-%ye% %hour24%:%minute%');

				if (!isset($linecount)) {
					$linecount=1;
				} else {
					$linecount++;
				}

				if (($linecount % 2)==0) {
					$bg_color="tabular_line_even";
				} else {
					$bg_color="tabular_line_odd";
				}

				$myUrl = sprintf("index.php?session=%s&amp;menu=admin&amp;do=templates&amp;edit=%s",$Pivot_Vars['session'],$f);

				printf('<tr class="%s">',$bg_color);
				printf("<td><input type='checkbox' name='check[%s]' /></td>",$f);
				printf('<td><a href="%s">%s</a></td>',$myUrl,$f);
				printf('<td>%s</td>',$fd);
				printf('<td>%d KB</td>',$fs);

				if (substr($f,0,5) == "_aux_") {
					printf('<td>%s</td>', lang('templates', 'aux_template') );
				} else if (substr($f,0,5) == "_sub_") {
					printf('<td>%s</td>', lang('templates', 'sub_template') );
				} else if ( (substr($f,0,5) == "feed_") && (getextension($f)=="xml") ) {
					printf('<td>%s</td>', lang('templates', 'feed_template') );
				} else if ( (getextension($f)=="css") ) {
					printf('<td>%s</td>', lang('templates', 'css_template') );
				} else if ( (getextension($f)=="txt") ) {
					printf('<td>%s</td>', lang('templates', 'txt_template') );
				} else if ( (getextension($f)=="php") ) {
					printf('<td>%s</td>', lang('templates', 'php_template') );
				} else {
					printf('<td>%s</td>', lang('templates', 'standard_template'));
				}
			}
		}
	}
	closedir($d);

	print("  <tr class=\"tabular_nav\"><td colspan=7><img src='pics/arrow_ltr.gif' width='29' height='14' border=0 alt='' />");
	echo '<a href="#" onclick=\'setCheckboxes("form1", true); return false;\'>'. lang('forms', 'c_all') .'</a> /';
	echo '<a href="#" onclick=\'setCheckboxes("form1", false); return false;\'>'. lang('forms', 'c_none') .'</a>';
	echo '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'. lang('forms', 'with_checked_templates');
	echo '<select name="action" class="input"><option value="" selected="selected">'. lang('forms', 'choose') .'</option><option value="delete">'. lang('forms', 'delete') .'</option></select>';

	echo '&nbsp;&nbsp;<input type="submit" value="'. lang('go') .'" class="button" />';
	print("</table></form>");

	PageFooter();
}


function edit_template() {
	global $Cfg, $Pivot_Vars;

	$fh = fopen('templates/'.$Pivot_Vars['edit'],'rb+') or die("<b>".$Pivot_Vars['edit'].": </b>".lang('error','file_open'));

	if(filesize('templates/'.$Pivot_Vars['edit']) > 0) {
		$s = fread($fh,filesize('templates/'.$Pivot_Vars['edit'])) or die("<b>".$Pivot_Vars['edit'].": </b>".lang('error','file_read'));
	} else {
		$s = '';
	}

	$s = str_replace('<textarea','<*textarea',$s);
	$s = str_replace('</textarea>','<*/textarea*>',$s);

	fclose($fh);

	printf("<h2>%s %s</h2><br />",lang('templates','editing'),$Pivot_Vars['edit']);
	printf("<form method=POST action='index.php'>\n",$action);
	printf("<input type='hidden' name='session' value='%s' />",$Pivot_Vars['session']);
	print("<input type='hidden' name='menu' value='admin' />");
	print("<input type='hidden' name='do' value='templates' />");
	printf("<input type='hidden' name='edit' value='%s' />",$Pivot_Vars['edit']);

	printf("<textarea name='template' rows='20' style='width: 98%%'>%s</textarea>",htmlspecialchars($s));
	echo '<br /><br /><input type="submit" value="'. lang('templates','save_changes') .'" class="button" />';
	print("</form>");

	PageFooter();
}


function save_template() {
	global $Cfg, $Pivot_Vars;

	// make sure that we save it in the pivot/templates/ folder..
	$file = basename($Pivot_Vars['edit']);

	$template = $Pivot_Vars['template'];

	if (get_magic_quotes_gpc()) {
		$template = stripslashes($template);
	}
	
	// make sure we don't accepts strange filename extensions
	$ext = getextension($file);
	if ( ($ext!='txt') && ($ext!='php') && ($ext!='htm') && ($ext!='html') && ($ext!='css') && ($ext!='xml') ) {
		$file = $file.".txt";
	}

	$template = str_replace('<*textarea','<textarea',$template);
	$template = str_replace('<*/textarea*>','</textarea>',$template);


	// Saving file
	//echo '-saved '.$file.'-<br />';
	debug( getcwd() );
	$fh = fopen('templates/'.$file,'wb+') or die("<b>$file: </b>".lang('error','file_open'));
	$changes = stripslashes($template);
	fwrite($fh, $template);
	fclose($fh);

	show_templates();
	PageFooter();
}


function create_template() {
	global $Pivot_Vars;

	printf("<h2>%s</h2><br />",lang('templates','create'));
	print("<form method=POST action='index.php'>\n");
	printf("<input type='hidden' name='session' value='%s' />",$Pivot_Vars['session']);
	print("<input type='hidden' name='menu' value='admin' />");
	print("<input type='hidden' name='do' value='templates' />");
	printf("<b>%s</b>%s<input type='text' name='edit' value='' class='input' size='50' /><br /><br />",lang('templates','filename'),str_repeat('&nbsp;',12));
	printf("<textarea name='template' rows='20' style='width: 98%%'></textarea>");
	echo '<br /><br /><input type="submit" value="'. lang('templates','save_template') .'" class="button" />';
	print("</form>");
}




function verify_db() {
	
	PageHeader(lang('adminbar','verifydb_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','verifydb_title'));

	// stuff goes here..
	echo "<p>This feature has not been implemented yet.</p>";

	PageFooter();
}



function backup() {
	global $zipfile;
	
	// make the zipfile..
	include_once('modules/zip.lib.php');
	$zipfile = new zipfile();
	
	// add some files
	addfiletozip('pv_cfg_settings.php');
	addfiletozip('pv_cfg_weblogs.php');
	
	$d = dir("./templates");  
	while (false !== ($entry = $d->read())) { 
		if ( ($entry != ".") && ($entry != "..") ) {
	   		addfiletozip("templates/".$entry);
		}
	} 
	$d->close(); 
	
	// get the zipp0red data..
	$zipped = $zipfile -> file();
	
	// trigger a download.
	$basename="pivot_config_".date("Ymd").".zip";
	header("Content-disposition: attachment; filename=$basename");
	header("Content-type: application/zip");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo $zipped;

	// done!! w00t~
	
}



function save_config() {
	global $Pivot_Vars, $Cfg;

	$config_array = get_config();

	$arraycount = Count($config_array);
	for($i = 0; $i < $arraycount; $i++){
		if($config_array[$i][0] != 'heading') {
			if($config_array[$i][3] == 4){
				$Pivot_Vars[$config_array[$i][0]] = @implode("|", $Pivot_Vars[$config_array[$i][0]]);
			}
			$Cfg[$config_array[$i][0]] = $Pivot_Vars[$config_array[$i][0]];
		}
	}

	// a hack by bob.
	if (isset($Cfg['ping_urls'])) {
		$Cfg['ping_urls'] = str_replace("\n", "|", $Cfg['ping_urls']);
		$Cfg['ping_urls'] = str_replace("\r", "", $Cfg['ping_urls']);
	}

	see_config();
}




function see_users() {
	global $Pivot_Vars, $Users;
	PageHeader(lang('adminbar','seeusers_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','seeusers_title'));

	DispPage(array(
	array(lang('userinfo', 'editfields'), 'userfields', lang('userinfo', 'desc_editfields')),
	array(lang('userinfo', 'newuser'), 'new_user', lang('userinfo', 'desc_newuser'))
	), "users");

	StartTable();
	GenSetting('', lang('category','users'), '', 8, '', 2);
	
	// make a temp array with the userlevels.
	$user_lev = array(
		0 => lang('userlevels',4), 
		1 => lang('userlevels',3),
		2 => lang('userlevels',2),
		3 => lang('userlevels',1),
		4 => lang('userlevels',0)
	);
	
	//echo "</table><table border='1'>";
	printf("<tr class='tabular_header'><td class='tabular'>%s:&nbsp;&nbsp;</td><td class='tabular'>%s:&nbsp;&nbsp;</td>
			<td class='tabular'>%s:&nbsp;&nbsp;</td><td class='tabular'>%s:&nbsp;&nbsp;</td><td>%s:&nbsp;&nbsp;</td><td>&nbsp;</td>", 
		 lang('userinfo', 'username'), lang('userinfo', 'userlevel'), lang('userinfo', 'lastlogin'), 
                 lang('userinfo','nickname'), lang('userinfo', 'email')) ;
	foreach($Users as $key => $array) {
		echo '<tr><td class="tabular"><b>'.$key.'</td>';
		echo '<td class="tabular">'. $user_lev[ $Users[$key]['userlevel'] ] . '&nbsp;&nbsp;</td>';
		if ( $Users[$key]['lastlogin'] == 0 ) {
			$lastlogin = "-";
		} else {
			$lastlogin = date("Y-m-d", $Users[$key]['lastlogin']);
		}
		echo '<td class="tabular">'. $lastlogin . '&nbsp;&nbsp;</td>';
		echo '<td class="tabular">'.$Users[$key]['nick'] . '&nbsp;&nbsp;</td>';
		echo '<td class="tabular">'.$Users[$key]['email'] . '&nbsp;&nbsp;</td><td class="tabular">';
		
		if(($Users[$Pivot_Vars['user']]['userlevel'] > $Users[$key]['userlevel']) || $Pivot_Vars['user'] == $key) {
			echo MakeLink(array('func'=>'admin', 'do'=>'edituser', 'edituser'=>$key));
			echo lang('userinfo','edituser').'</a></td>';
		}else{
			echo lang('userinfo','edituser').'</td>';
		}
		echo '</tr>';
	}
	echo '</table><br />';


	
	PageFooter();

}


function new_user($erred=0, $uf=''){
	global $Cfg;

	PageHeader(lang('adminbar','seeusers_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','seeusers_title'));
	if($erred==1) {
		$userfields = $uf;
	}else{
		$userfields = get_userfields(1);
	}


	// checkboxes for allowed categories
	$userfields[] = array('heading', lang('config', 'allowed_cats'), '', 8, '', 2);

	$categories = cfg_cats();

	foreach ($categories as $category) {
		$label = sprintf('&nbsp;&nbsp;&nbsp;'.$category['name']);
		$allowed_users = explode("|", $Cfg['cat-'.$category]);
		$name = urlencode($category['name']);
		if (in_array($theuser, $allowed_users)) {
			$userfields[] = array('allowed[' . $name . ']', $label, '', 2, 1, '', '');
		} else {
			$userfields[] = array('allowed[' . $name . ']', $label, '', 2, 0, '', '');
		}

	}
	$userfields[(count($userfields)-1)][2]  = lang('config', 'allowed_cats_desc');

	StartForm('new_user_save', 1);
	StartTable();
	DisplaySettings($userfields);
	EndForm(lang('userinfo','newuser_button'), 1);


	PageFooter();
}


function new_user_save() {
	global $Pivot_Vars, $Cfg, $Users;
	
	$userfields = get_userfields(1);
	if(CheckInput($Pivot_Vars['username'],2,0)==1){
		$userfields[0][2] = lang('userinfo','username_too_short');
		$Piverr++;
	}elseif(CheckInput($Pivot_Vars['username'],2,0)==0){
		$userfields[0][2] = lang('userinfo','username_not_valid');
		$Piverr++;
	}

	if(isset($Users[$Pivot_Vars['username']])){
		$userfields[0][2] = lang('userinfo','username_in_use');
		$Piverr++;
	}
	if((ltrim(rtrim(strtolower($Pivot_Vars['pass1'])))) != (ltrim(rtrim(strtolower($Pivot_Vars['pass2']))))) {
		$userfields[2][2] = lang('userinfo','pass_dont_match');
		$Piverr++;
	}

	if(CheckInput($Pivot_Vars['pass1'],4,0)==1){
		$userfields[1][2] = lang('userinfo','pass_too_short');
		$Piverr++;
	}


	if($Piverr > 0){
		$userfields[0][4] = $Pivot_Vars['username'];
		$userfields[3][6] = $Pivot_Vars['userlevel'];
		new_user(1, $userfields);
	}else{
		if($Pivot_Vars['userlevel']==3 && ($Pivot_Vars['confirmed']!=1)){
			$vars = array('username', $Pivot_Vars['username'], 'pass1', $Pivot_Vars['pass1'], 'pass2', $Pivot_Vars['pass2'], 'email', $Pivot_Vars['email'], 'userlevel', $Pivot_Vars['userlevel']);
			ConfirmPage(lang('userinfo', 'c_admin_title'), $vars, sprintf(lang('userinfo','c_admin_message'), $Pivot_Vars['username']));
		}elseif(($Pivot_Vars['userlevel']<3) || ($Pivot_Vars['confirmed']==1)){
			$Users[$Pivot_Vars['username']]['pass'] = md5($Pivot_Vars['pass1']);
			$Users[$Pivot_Vars['username']]['email'] = $Pivot_Vars['email'];
			$Users[$Pivot_Vars['username']]['userlevel'] = $Pivot_Vars['userlevel'];
			$Users[$Pivot_Vars['username']]['language'] = $Pivot_Vars['language'];
			$Users[$Pivot_Vars['username']]['wysiwyg'] = $Pivot_Vars['wysiwyg'];
			$Users[$Pivot_Vars['username']]['username'] = $Pivot_Vars['username'];
			$Users[$Pivot_Vars['username']]['nick'] = $Pivot_Vars['username'];
			$Users[$Pivot_Vars['username']]['view'] = $Pivot_Vars['view'];			
			
			$Cfg['users'] .= '|'.$Pivot_Vars['username'];

			// set the categories.,
			$cats = cfg_cats();
			foreach ($cats as $category) {
				
				if (isset($Pivot_Vars['allowed'][ $category['name'] ] )) {
					// add the user..
					$allowed_users = explode("|", $Cfg[ 'cat-'.$category['name' ]]);
					$allowed_users[] = $Pivot_Vars['username'];
					$Cfg[ 'cat-'.$category['name'] ] = implode("|", $allowed_users);
				}
			}


			SaveSettings();
			see_users();
		}
		
	}
}


function change_user($erred=0, $userfields=''){
	global $Pivot_Vars;
	PageHeader(lang('userinfo','edituser').' "'.$Pivot_Vars['edituser'].'"', 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('userinfo','edituser').' "'.$Pivot_Vars['edituser'].'"');

	libchange_user(1, $erred, $userfields);
	PageFooter();
}



function save_change_user(){

	libsave_change_user(1);
}



function ipblocks() {
	global $Pivot_Vars;

	
	// if saving, we save the file and display them again..
	if (isset($Pivot_Vars['blocks'])) {
		write_blocks($Pivot_Vars['blocks']);
		$msg = lang('adminbar', 'ipblocks_stored');
	} else {
		$msg = "";
	}

	
	// otherwise, we load the file..
	if (file_exists("db/blocked_ips.txt.php")) {
		$block = implode("", file("db/blocked_ips.txt.php"));
	} else {
		$block = "";	
	}	
		
	PageHeader(lang('userbar','admin'). ' &raquo; ' . lang('adminbar','ipblocks'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','ipblocks_title'));

	if ($msg != "") {
		echo "<p><B>$msg</b><br /><br /></p>";
	}
	

	StartForm('ipblocks', 0);
	StartTable();

	$settings = array();
	// $settings[] = array('heading', lang('adminbar','ipblocks_title'), '', 8, '', 2, '');
	$settings[] = array('blocks', lang('adminbar','ipblocks'), '', 5,  $block, '60', 'rows=20');

	DisplaySettings($settings, 'blog_settings');
	EndForm(lang('adminbar', 'ipblocks_store'), 1);

	PageFooter();

}


function ignoreddomains() {
	global $Pivot_Vars;

	// if saving, we save the file and display them again..
	if (isset($Pivot_Vars['blocks'])) {
		write_ignoreddomains($Pivot_Vars['blocks']);
		$msg = lang('adminbar', 'ignoreddomains_stored');
	} else {
		$msg = "";
	}
	
	// otherwise, we load the file..
	if (file_exists("db/ignored_domains.txt.php")) {
		$blockArray = file("db/ignored_domains.txt.php");
		$block = implode("",$blockArray);
	} else if (file_exists("db/ignored_domains.txt")) {
		$blockArray = file("db/ignored_domains.txt");
		$block = implode("",$blockArray);
	} else {
		$block = "";	
	}	
	PageHeader(lang('userbar','admin'). ' &raquo; ' . lang('adminbar','ignoreddomains'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','ignoreddomains_title'));

	if ($msg != "") {
		echo "<p><B>$msg</b><br /><br /></p>";
	}
	
	StartForm('ignoreddomains', 0);
	StartTable();
	
	$settings = array();
	$settings[] = array('blocks', lang('adminbar','ignoreddomains'), lang('adminbar', 'ignoreddomains_asterisk'), 5,  $block, '60', 'rows=20');

	DisplaySettings($settings, 'blog_settings');


	EndForm(lang('adminbar', 'ignoreddomains_store'), 1);

	PageFooter();

} 


function fileexplore() {
	global $Pivot_Vars;
	PageHeader(lang('userbar','admin').' &raquo; '. lang('adminbar','fileexplore_title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','fileexplore_title'));

	include("includes/explorer.txt");


	PageFooter();


}


function userfields_main($erred=0, $newdata='') {
	PageHeader(lang('ufield_main','title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','userfields_title'));
	MidHeading(lang('ufield_main', 'create'));
	StartForm('new_ufield');
	StartTable();
	if($erred==0) {
		$fickle = userfields_array();
	}else{
		$fickle = $newdata;
	}
	DisplaySettings($fickle , $ufield_item_arr);
	EndForm(lang('ufield_main', 'create'), 1);
	echo '</table>';
	MidHeading(lang('ufield_main', 'edit'));

	$ufields = GetUserFields();
	$fields = array();
	foreach($ufields As $key => $val){
		if($key != 'heading'){
			array_push($fields, array($ufields[$key]['disp'], 'ufield_edit&amp;field='.$key, ''));
		}
	}
	DispPage($fields, 'userfields');
	PageFooter();
}


function userfields_new() {
	global $Pivot_Vars, $Cfg;
	$fts = GetUserFields();
	$fickle = userfields_array();
	if($fts[$Pivot_Vars['intname']]){
		$errout++;
		$fickle[0][2] = lang('ufield_main', 'already');
	}
	if(strlen($Pivot_Vars['intname']) < 3){
		$errout++;
		$fickle[0][2] = lang('ufield_main', 'int');
	}
	if(strlen($Pivot_Vars['disp']) < 3 ){
		$errout++;
		$fickle[1][2] = lang('ufield_main', 'short_disp');
	}
	if($errout > 0){
		$fickle[0][4] = $Pivot_Vars['intname'];
		$fickle[1][4] = $Pivot_Vars['disp'];
		$fickle[2][4] = $Pivot_Vars['size'];
		$fickle[3][4] = $Pivot_Vars['maxlen'];
		$fickle[4][6] = $Pivot_Vars['minlevel'];
		userfields_main(1, $fickle);
	}else{
		$fts[$Pivot_Vars['intname']] =
		Array('type' => 0, 'size' => $Pivot_Vars['size'], 'maxlen' => $Pivot_Vars['maxlen'],
		'disp' => $Pivot_Vars['disp'], 'minlevel' => $Pivot_Vars['minlevel']
		);
		PutUserFields($fts);
		userfields_main();
	}
}


function userfields_edit($Failed=0, $newdata='') {
	global $ufield_item_arr, $Pivot_Vars;
	PageHeader(lang('ufield_main','title'), 1);
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','userfields_title'));
	$ufields = GetUserFields();
	$ufield_arr = userfields_array();
	if($Failed==0){
		$ufield_item_arr['intname'] = $Pivot_Vars['field'];
		foreach($ufields[$Pivot_Vars['field']] As $item => $val){
			$ufield_item_arr[$item] = $val;
		}
	}else{
		echo "failed at something";
		$ufield_arr = $newdata;
	}

	StartForm('editsave_ufield');
	StartTable();
	array_push($ufield_arr,
	array('heading', '', '', 8, '', '2', ''),
	array('delete', lang('delete'), '', 3, 'yn', '', '0')
	);
	DisplaySettings($ufield_arr, 'ufield_item_arr');
	EndForm(lang('ufield_main', 'edit'), 1);
	echo '</table>';

}


function userfields_editsave() {
	global $Pivot_Vars, $Cfg;
	$ufield_arr = userfields_array();
	$fts = GetUserFields();
	if(($Pivot_Vars['confirmed'] != 1) && ($Pivot_Vars['delete'] == 1)){
		$vars = array('intname', $Pivot_Vars['intname'], 'delete', 1);
		ConfirmPage(lang('ufield_main','del_title'), $vars, sprintf(lang('ufield_main','del_desc'), $Pivot_Vars['disp']));
	}elseif(($Pivot_Vars['confirmed'] == 1) && ($Pivot_Vars['delete'] == 1)){
		//go through deleting it.
		unset($Cfg['uf-'.$Pivot_Vars['intname']]);
		foreach(explode("|", $Cfg['userfields']) as $val){
			if($val != $Pivot_Vars['intname']){
				$spoon[] = $val;
			}
		}
		$Cfg['userfields'] = implode("|", $spoon);
		//first from the master list
		userfields_main();
	}else{
		if($errout > 0){
			//forget error checking for now..
		}else{
			foreach($ufield_arr as $val){
				if($val[0] != 'intname'){
					$fts[$Pivot_Vars['intname']][$val[0]] = $Pivot_Vars[$val[0]];
				}
			}
			$Pivot_Vars['field'] = $Pivot_Vars['intname'];
			PutUserFields($fts);
			userfields_main();
		}
	}
}


function maint_overview() {

	$maint_funcs = array(
	array(lang('adminbar','buildfrontpage'), 'buildfrontpage', lang('adminbar','buildfrontpage_title')),
	array(lang('adminbar','regen'), 'regen', lang('adminbar','regen_title')),
	// array(lang('adminbar','verifydb'), 'verify_db', lang('adminbar','verifydb_title')),
	array(lang('adminbar','buildindex'), 'build_index', lang('adminbar','buildindex_title')),
	// array(lang('adminbar','backup'), 'backup', lang('adminbar','backup_title')),
	array(lang('adminbar','buildsearchindex'), 'build_search', lang('adminbar','buildsearchindex_title')),
	array(lang('adminbar','backup_config'), 'backup_config', lang('adminbar','backup_config_desc')),

	array(lang('adminbar','ignoreddomains'), 'ignoreddomains', lang('adminbar','ignoreddomains_title')),
	array(lang('adminbar','ipblocks'), 'ipblocks', lang('adminbar','ipblocks_title')),
	array(lang('adminbar','fileexplore'), 'fileexplore', lang('adminbar','fileexplore_title'))
	);

	PageHeader(lang('maint','title'));
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','maintenance_title'));

	DispPage($maint_funcs, 'maintenance');

	PageFooter();
}


function main_blogs($msg="") {
	global $Weblogs;
	PageHeader(lang('adminbar','blogs_title'));
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('adminbar','blogs_title'));
	MidHeading(lang('weblog_config','edit_blog'));

	if ($msg!="") {
		echo "<br />$msg";
	}

	$send = array();

	// 2004/10/16 - =*=*= JM corrected irritating space...
	foreach ($Weblogs as $key => $weblog) {
		if ($key != "") {
			
			$name = ($Weblogs[$key]['name'] != "" ) ? $Weblogs[$key]['name'] : '( .. )';
			
			$send[] = array( $name , 'blog_edit1&amp;blog='. $key,
			lang('weblog_config','edit_weblog'). '&nbsp;\''. $name . '\'');
		}
	}

	$send[] = array('', '', '');

	$send[] = array(lang('weblog_config','new_weblog'), 'blog_edit1', lang('weblog_config','new_weblog_desc'));
	
	DispPage($send, 'weblogs');

	PageFooter();
}


function main_blogs_edit1() {
	global $Pivot_Vars, $blog_settings, $Weblogs;
	PageHeader(lang('weblog_config','create_1'));
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('weblog_config','create_1'));


	if (isset($Pivot_Vars['blog'])) {
		$blog_settings = $Weblogs[$Pivot_Vars['blog']];
		$orig_name = $Pivot_Vars['blog'];

		// option to delete a weblog
		$text = sprintf(lang('weblog_config','del_weblog') .' \'%s\'', $Weblogs[$Pivot_Vars['blog']]['name']);
		$send[] = array($text, 'blog_delete&name='.$Pivot_Vars['blog'] , lang('weblog_config','del_this_weblog'));
		DispPage($send, 'del_blog');

		echo "<br />";
		MidHeading(sprintf(lang('weblog_config','edit_weblog') .' \'%s\'', $Weblogs[$Pivot_Vars['blog']]['name']));


	} else {
		$blog_settings = get_tempweblog();
		$orig_name = "";
		MidHeading(lang('weblog_config','create_new'));
	}
	
	StartForm('blog_edit2', 1);
	StartTable();
	EndForm(lang('go'), 2);
	DisplaySettings(get_weblog_config1($orig_name), 'blog_settings');
	EndForm(lang('go'), 1);
	PageFooter();
}


function main_blogs_edit2() {
	global $Cfg, $Pivot_Vars, $blog_settings, $Weblogs, $Current_subweblog;
	PageHeader(lang('weblog_config','create_2'));
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('weblog_config','create_2'));

	$temp_weblog= array(
	'name' => stripslashes($Pivot_Vars['name']),
	'payoff' => stripslashes($Pivot_Vars['payoff']),
	'siteurl' => stripslashes($Pivot_Vars['siteurl']),
	'front_filename' => $Pivot_Vars['front_filename'],
	'front_path' => $Pivot_Vars['front_path'],
	'ssi_prefix' => $Pivot_Vars['ssi_prefix'],
	'language' => $Pivot_Vars['language'],

	'live_entries' => $Pivot_Vars['live_entries'],
	'entry_filename' => $Pivot_Vars['entry_filename'],
	'entry_path' => $Pivot_Vars['entry_path'],
	'read_more' => stripslashes($Pivot_Vars['read_more']),
	'archive_filename' => $Pivot_Vars['archive_filename'],
	'archive_path' => $Pivot_Vars['archive_path'],
	'archive_unit' => $Pivot_Vars['archive_unit'],
	'archive_amount' => $Pivot_Vars['archive_amount'],
	'archive_link' => stripslashes($Pivot_Vars['archive_link']),
	'archive_linkfile' => stripslashes($Pivot_Vars['archive_linkfile']),
	'archive_order' => stripslashes($Pivot_Vars['archive_order']),

	'front_template' => $Pivot_Vars['front_template'],
	'archive_template' => $Pivot_Vars['archive_template'],
	'entry_template' => $Pivot_Vars['entry_template'],
	'extra_template' => $Pivot_Vars['extra_template']
	);

	if ( (isset($Pivot_Vars['orig_name'])) && (strlen($Pivot_Vars['orig_name']) > 1) ) {
		$blog_settings = $Weblogs[$Pivot_Vars['orig_name']];
		$orig_name = $Pivot_Vars['orig_name'];
		MidHeading(sprintf(lang('weblog_config','edit_weblog') .' \'%s\'', $Weblogs[$Pivot_Vars['orig_name']]['name']));

	} else {
		$blog_settings = get_tempweblog();
		$orig_name = "";
		MidHeading(lang('weblog_config','create_new'));
	}



	StartForm('blog_edit3', 1);
	StartTable();
	EndForm(lang('go'), 2, lang('weblog_config','subw_heading') );
	
	// initialize the settings array..
	$settings = array();
	$settings[] = array('orig_name', 'originalname', '', 7,  $orig_name);
	$settings[] = array('temp_weblog', 'tempweblog', '', 7,  urlencode(serialize($temp_weblog)));

	$templates = gettemplates('_sub');
		
	$categories = cfg_cats();

	$template_html = load_template($temp_weblog['front_template']);

	// to cater for users that upgraded their old templates, we
	// assume [[weblog]] to be [[weblog:standard]]
	$template_html = str_replace("[[weblog]]", "[[weblog:standard]]", $template_html);

	preg_match_all("/\[\[(sub)?weblog:(.*)(:[0-9]*)?\]\]/mUi", $template_html, $match);
	$subweblogs= $match[2];


	foreach ($subweblogs as $key) {

		// if the subweblog is in the template, but not in the config files, assume some defaults
		if (!isset($blog_settings['sub_weblog'][$key])) {
			$blog_settings['sub_weblog'][$key]['template']  = "_sub_entry_standard.html";
			$blog_settings['sub_weblog'][$key]['num_entries'] = 10;
			$blog_settings['sub_weblog'][$key]['comments'] = 1;
			$blog_settings['sub_weblog'][$key]['categories'] = array();
		}

		$subweblog = $blog_settings['sub_weblog'][$key];

		$settings[] = array('heading', $key, '', 8, '', 2);
		$settings[] = array('sub_weblog['.$key.'][template]', lang('weblog_config','shortentry_template'),
		lang('weblog_config','shortentry_template_desc'), 3, $templates, '', $key);
		$settings[] = array('sub_weblog['.$key.'][num_entries]', lang('weblog_config','num_entries'),
		lang('weblog_config','num_entries_desc'), 0, $subweblog['num_entries']);
		$settings[] = array('sub_weblog['.$key.'][offset]', lang('weblog_config','offset'),
		lang('weblog_config', 'offset_desc'), 0, $subweblog['offset']);

		// JM - 2004/10/15 - lang abstraction inserted
		$settings[] = array( '',lang( 'weblog_config','publish_cats' ),'',6 );
		$style_nosearch = 'style="background-color:#eee;"';
		// NOTE - include style if a no-index category...
		$cats_nosearch = cfg_cat_nosearchindex();

		foreach ($categories as $category) {
			
			$label = sprintf('&nbsp;&nbsp;&nbsp;'.$category['name']);
			
			$name = urlencode($category['name']);
			if (in_array($category['name'], $subweblog['categories'])) {
				$settings[] = array('sub_weblog['.$key.'][categories]['.$name.']', $label, '', 2, 1, '', '');
			} else {
				$settings[] = array('sub_weblog['.$key.'][categories]['.$name.']', $label, '', 2, 0, '', '');
			}
		}
		
		$numentries = $blog_settings['sub_weblog'][$key]['num_entries'];
	}

	DisplaySettings($settings, 'blog_settings');
	EndForm(lang('go'), 1);
	PageFooter();
}



function main_blogs_edit3() {
	global $Pivot_Vars, $blog_settings, $Weblogs;
	PageHeader(lang('weblog_config','create_3'));
	PageAnkeiler(lang('userbar','admin') . ' &raquo; ' . lang('weblog_config','create_3'));


	if ( (isset($Pivot_Vars['orig_name'])) && (strlen($Pivot_Vars['orig_name']) > 1) ) {
		$blog_settings = $Weblogs[$Pivot_Vars['orig_name']];
		$orig_name = $Pivot_Vars['orig_name'];
		MidHeading(sprintf(lang('weblog_config','edit_weblog') .' \'%s\'', $Weblogs[$Pivot_Vars['orig_name']]['name']));

	} else {
		$blog_settings = get_tempweblog();
		$orig_name = "";
		MidHeading(lang('weblog_config','create_new'));
	}




	$temp_subweblogs = $Pivot_Vars['sub_weblog'];

	foreach ($temp_subweblogs as $subweblog => $dummy) {


		// fix the subweblogs' categories..
		if (count($temp_subweblogs[$subweblog]['categories']) > 0) {
			foreach ($temp_subweblogs[$subweblog]['categories'] as $cat => $dummy) {
				unset ($temp_subweblogs[$subweblog]['categories'][$cat]);
				$temp_subweblogs[$subweblog]['categories'][]=$cat;
			}
		} else {
			// you are an idiot if you did not select any categories..
			$temp_subweblogs[$subweblog]['categories'] = array();
		}
	}

	// unserialize the parsed temp_weblog..
	$temp_weblog = unserialize(urldecode($Pivot_Vars['temp_weblog']));

	// merge temp_weblog with temp_subweblogs
	$temp_weblog['sub_weblog']= $temp_subweblogs;


	StartForm('blog_save', 1);
	StartTable();
	EndForm(lang('weblog_config', 'create'), 2);
	
	$settings = get_weblog_config3();
	$settings[] = array('orig_name', 'originalname', '', 7,  $orig_name);
	$settings[] = array('temp_weblog', 'tempweblog', '', 7,  urlencode(serialize($temp_weblog)));

	DisplaySettings($settings, 'blog_settings');
	EndForm(lang('weblog_config', 'create'), 1);
	PageFooter();
}


function main_blog_save() {
	global $Pivot_Vars, $blog_settings, $Weblogs;

	// unserialize the parsed temp_weblog..
	$temp_weblog = unserialize(urldecode($Pivot_Vars['temp_weblog']));

	// urldecode category names
	foreach ($temp_weblog['sub_weblog'] as $sub_weblog_key => $sub_weblog_value) {
		foreach ($sub_weblog_value['categories'] as $category_key => $category_name) {
			$temp_weblog['sub_weblog'][$sub_weblog_key]['categories'][$category_key] = urldecode($category_name);
		}
	}

	if ( (isset($Pivot_Vars['orig_name'])) && (strlen($Pivot_Vars['orig_name']) > 1) ) {
		$orig_name = $Pivot_Vars['orig_name'];
		$msg = lang('weblog_config', 'saved_update');
	} else {
		$orig_name = strtolower(safe_string($temp_weblog['name'],TRUE));
		$msg = lang('weblog_config', 'saved_create');
	}

	// add the new stuff
	$temp_weblog['rss'] = $Pivot_Vars['rss'];
	$temp_weblog['rss_filename'] = $Pivot_Vars['rss_filename'];
	$temp_weblog['atom_filename'] = $Pivot_Vars['atom_filename'];
	$temp_weblog['rss_path'] = $Pivot_Vars['rss_path'];
	$temp_weblog['rss_full'] = $Pivot_Vars['rss_full'];
	$temp_weblog['rss_link'] = $Pivot_Vars['rss_link'];
	$temp_weblog['rss_img'] = $Pivot_Vars['rss_img'];

	
	$temp_weblog['lastcomm_amount'] = $Pivot_Vars['lastcomm_amount'];
	$temp_weblog['lastcomm_length'] = $Pivot_Vars['lastcomm_length'];
	$temp_weblog['lastcomm_format'] = stripslashes($Pivot_Vars['lastcomm_format']);
	$temp_weblog['lastcomm_redirect'] = stripslashes($Pivot_Vars['lastcomm_redirect']);

	$temp_weblog['lastref_amount'] = $Pivot_Vars['lastref_amount'];
	$temp_weblog['lastref_length'] = $Pivot_Vars['lastref_length'];
	$temp_weblog['lastref_format'] = stripslashes($Pivot_Vars['lastref_format']);
	$temp_weblog['lastref_graphic'] = stripslashes($Pivot_Vars['lastref_graphic']);

	$temp_weblog['emoticons'] = $Pivot_Vars['emoticons'];
	$temp_weblog['encode_email_addresses'] = $Pivot_Vars['encode_email_addresses'];
	$temp_weblog['target_blank'] = $Pivot_Vars['target_blank'];
	$temp_weblog['fulldate_format'] = stripslashes($Pivot_Vars['fulldate_format']);
	$temp_weblog['entrydate_format'] = stripslashes($Pivot_Vars['entrydate_format']);
	$temp_weblog['diffdate_format'] = stripslashes($Pivot_Vars['diffdate_format']);
	$temp_weblog['comment_sendmail'] = $Pivot_Vars['comment_sendmail'];
	$temp_weblog['comment_emailto'] = $Pivot_Vars['comment_emailto'];
	$temp_weblog['comment_texttolinks'] = $Pivot_Vars['comment_texttolinks'];
	$temp_weblog['comment_wrap'] = $Pivot_Vars['comment_wrap'];
	$temp_weblog['comments_text_0'] = stripslashes($Pivot_Vars['comments_text_0']);
	$temp_weblog['comments_text_1'] = stripslashes($Pivot_Vars['comments_text_1']);
	$temp_weblog['comments_text_2'] = stripslashes($Pivot_Vars['comments_text_2']);
	$temp_weblog['comment_pop'] = $Pivot_Vars['comment_pop'];
	$temp_weblog['comment_width'] = $Pivot_Vars['comment_width'];
	$temp_weblog['comment_height'] = $Pivot_Vars['comment_height'];
	$temp_weblog['comment_format'] = stripslashes($Pivot_Vars['comment_format']);
	$temp_weblog['comment_reply'] = stripslashes($Pivot_Vars['comment_reply']);
	$temp_weblog['comment_forward'] = stripslashes($Pivot_Vars['comment_forward']);
	$temp_weblog['comment_backward'] = stripslashes($Pivot_Vars['comment_backward']);
	$temp_weblog['comment_textile'] = $Pivot_Vars['comment_textile'];
	$temp_weblog['comment_gravatardefault'] = $Pivot_Vars['comment_gravatardefault'];
	$temp_weblog['comment_gravatarhtml'] = $Pivot_Vars['comment_gravatarhtml'];
	$temp_weblog['comment_gravatarsize'] = $Pivot_Vars['comment_gravatarsize'];
	//$temp_weblog['trackback_sendmail'] = $Pivot_Vars['trackback_sendmail'];
	//temp_weblog['trackback_emailto'] = $Pivot_Vars['trackback_emailto'];
	$temp_weblog['trackbacks_text_0'] = stripslashes($Pivot_Vars['trackbacks_text_0']);
	$temp_weblog['trackbacks_text_1'] = stripslashes($Pivot_Vars['trackbacks_text_1']);
	$temp_weblog['trackbacks_text_2'] = stripslashes($Pivot_Vars['trackbacks_text_2']);
	//$temp_weblog['trackback_pop'] = $Pivot_Vars['trackback_pop'];
	//$temp_weblog['trackback_width'] = $Pivot_Vars['trackback_width'];
	//$temp_weblog['trackback_height'] = $Pivot_Vars['trackback_height'];
	$temp_weblog['trackback_format'] = stripslashes($Pivot_Vars['trackback_format']);
	$temp_weblog['trackback_link_format'] = stripslashes($Pivot_Vars['trackback_link_format']);

	$temp_weblog['blogroll_id'] = $Pivot_Vars['blogroll_id'];
	$temp_weblog['blogroll_fg'] = $Pivot_Vars['blogroll_fg'];
	$temp_weblog['blogroll_bg'] = $Pivot_Vars['blogroll_bg'];
	$temp_weblog['blogroll_line1'] = $Pivot_Vars['blogroll_line1'];
	$temp_weblog['blogroll_line2'] = $Pivot_Vars['blogroll_line2'];
	$temp_weblog['blogroll_c1'] = $Pivot_Vars['blogroll_c1'];
	$temp_weblog['blogroll_c2'] = $Pivot_Vars['blogroll_c2'];
	$temp_weblog['blogroll_c3'] = $Pivot_Vars['blogroll_c3'];
	$temp_weblog['blogroll_c4'] = $Pivot_Vars['blogroll_c4'];
	

	$Weblogs[$orig_name] = $temp_weblog;

	save_serialize('pv_cfg_weblogs.php', $Weblogs);

	main_blogs($msg);

}


function main_blog_delete() {
	global $Weblogs, $Pivot_Vars;

	if ($Pivot_Vars['confirmed']!=1) {
		$vars = array('name', $Pivot_Vars['name'], 'blog_delete', 1);
		$msg = lang('weblog_config', 'confirm_delete');
		$msg = str_replace("%1", $Pivot_Vars['name'], $msg);
		ConfirmPage(lang('ufield_main','del_title'), $vars, $msg);
	} else {
		$name = $Pivot_Vars['name'];
		unset($Weblogs[$name]);
		save_serialize('pv_cfg_weblogs.php', $Weblogs);
		$msg = lang('weblog_config', 'deleted');
		main_blogs($msg);

	}
}

?>
