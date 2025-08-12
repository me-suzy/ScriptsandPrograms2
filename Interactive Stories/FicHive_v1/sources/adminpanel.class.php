<?php
class adminpanel extends dp {

	var $user = array();
	var $title = array();
	var $crumb = array();
	var $skin;

	function adminpanel(){
	}

	function makePage(){

		global $lang;

		if( $this->user['gid'] == 4 ) {

		switch( $_GET['set'] ) {

			default:
				$content = $this->generalSettings();
			break;

			case $lang['nav']['admin'][1]['sub'][0]:
				$content = $this->addCategory();
			break;

			case $lang['nav']['admin'][1]['sub'][1]:
				$content = $this->editCategory();
			break;

			case $lang['nav']['admin'][1]['sub'][2]:
				$content = $this->deleteCategory();
			break;

			case $lang['nav']['admin'][1]['sub'][3]:
				$content = $this->orderCategories();
			break;

			case $lang['nav']['admin'][2]['sub'][0]:
				$content = $this->editAccount();
			break;

			case $lang['nav']['admin'][2]['sub'][1]:
				$content = $this->deleteAccount();
			break;

			case $lang['nav']['admin'][3]['sub'][0]:
				$content = $this->addGroup();
			break;

			case $lang['nav']['admin'][3]['sub'][1]:
				$content = $this->editGroup();
			break;

			case $lang['nav']['admin'][3]['sub'][2]:
				$content = $this->deleteGroup();
			break;

			case $lang['nav']['admin'][4]['sub'][0]:
				$content = $this->createSkin();
			break;

			case $lang['nav']['admin'][4]['sub'][1]:
				$content = $this->editSkin();
			break;

			case $lang['nav']['admin'][4]['sub'][2]:
				$content = $this->deleteSkin();
			break;

			case $lang['nav']['admin'][5]['sub'][0]:
				$content = $this->createLanguage();
			break;

			case $lang['nav']['admin'][5]['sub'][1]:
				$content = $this->editLanguage();
			break;

			case $lang['nav']['admin'][5]['sub'][2]:
				$content = $this->deleteLanguage();
			break;

			case $lang['nav']['admin'][6]['sub'][0]:
				$content = $this->addNews();
			break;

			case $lang['nav']['admin'][6]['sub'][1]:
				$content = $this->editNews();
			break;

			case $lang['nav']['admin'][6]['sub'][2]:
				$content = $this->deleteNews();
			break;

		}

		$this->title ? $this->title = implode($conf['sep']['title'] , $this->title) : $this->title = "";

		$this->crumb ? $this->crumb = implode($conf['sep']['crumb'] , $this->crumb) : $this->crumb = "";

		$orig = array("<%NAVIGATION%>", "<%CONTENT%>");

		$repl = array( $this->makeNav() , $content );

		return str_replace( $orig , $repl , $this->grabFile( $this->skin."/admin/layout.tmpl.php" ) );
		}

	}

	function deleteNews() {

		global $conf, $lang, $db;;

		$this->title[] = $lang['nav']['admin'][6]['sub'][2];
		$this->crumb[] = $lang['nav']['admin'][6]['sub'][2];

		if( $_POST ) {

			$db->delete( "news" , array('nid'=>$_POST['delete']) );

			$note = LANG_AP_NEWS_SUCC3;

		}


		$gnews = $db->select( "ndate, nid" , "news" , "" , "ORDER BY ndate DESC" );

		foreach( $gnews as $row ) {

			$news.="<option value='{$row['nid']}'>".$this->showTime($conf['time_format_news'],$row['ndate'])."</option>";

		}

		$orig = array("<%GO%>", "<%AREYOUSURE%>", "<%NEWS%>");
		
		$repl = array( LANG_SYS_SUBMIT , LANG_SYS_AREYOUSURE, $news );

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/deletenews.tmpl.php" ) ) );
	}

	function editNews(){

		global $conf, $lang, $db;;

		$this->title[] = $lang['nav']['admin'][6]['sub'][1];
		$this->crumb[] = $lang['nav']['admin'][6]['sub'][1];

		if( $_POST['finish'] ) {

			$comment = str_replace( "\r\n" , "|" , $_POST['ncomment'] );

			$db->update( "news" , array('ndate'=>$_POST['ndate'], 'nnews'=>$_POST['nnews'], 'ncomment'=>$comment), 
			array('nid'=>$_POST['finish']) );

			$note = LANG_AP_NEWS_SUCC2;

			$_POST['edit'] = $_POST['finish'];

		}

		if( $_POST['edit'] ) {

			$news = $db->select( "ndate, nnews, ncomment" , "news" , array('nid'=>$_POST['edit']) );

			$news = $this->cleanForm( $news[0] );

			$news['ncomment'] = trim( str_replace("|" , "\r\n" , $news['ncomment']) );

			$orig = array("<%DATE%>", "<%DATE_VAL%>", "<%NEWS_VAL%>" , "<%COMMENT_VAL%>", "<%GO%>");

			$repl = array(LANG_AP_NEWS_DATE, $news['ndate'], $news['nnews'], $news['ncomment'], LANG_SYS_SUBMIT );

			$tmpl = $this->grabFile( $this->skin."/admin/editnews_2.tmpl.php" );

			$tmpl.="<input type='hidden' name='finish' value='{$_POST['edit']}'>";

		}

		if( !$_POST ) {

			$gnews = $db->select( "ndate, nid" , "news" , "" , "ORDER BY ndate DESC" );

			foreach( $gnews as $row ) {

				$news.="<option value='{$row['nid']}'>".$this->showTime($conf['time_format_news'],$row['ndate'])."</option>";

			}

			$orig = array("<%GO%>", "<%NEWS%>");
		
			$repl = array( LANG_SYS_SUBMIT , $news );

			$tmpl = $this->grabFile( $this->skin."/admin/editnews_1.tmpl.php" );

		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $tmpl ) );

	}

	function addNews(){

		global $conf, $lang, $db;;

		$this->title[] = $lang['nav']['admin'][6]['sub'][0];
		$this->crumb[] = $lang['nav']['admin'][6]['sub'][0];

		if( $_POST['nnews'] ) {

			$db->insert( "news" , array('ndate'=>date("Y-m-d H:i:s"), 'nnews'=>$_POST['nnews'], 'nuid'=>$this->user['uid']) );

			$note = LANG_AP_NEWS_SUCC1;

		}

		$orig = array("<%GO%>");
		
		$repl = array( LANG_SYS_SUBMIT );

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/addnews.tmpl.php" ) ) );

	}

	function deleteLanguage() {

		global $conf, $lang, $db;;

		$this->title[] = $lang['nav']['admin'][5]['sub'][2];
		$this->crumb[] = $lang['nav']['admin'][5]['sub'][2];

		if( $_POST ) {

			if( $_POST['lgida'] == $conf['default_lang'] ) $note = LANG_CP_LANG_ERR1;

			elseif( $_POST['lgida'] == $_POST['lgidb'] ) $note = LANG_CP_LANG_ERR2;

			else {

				$this->rmdirr( $conf['path']."language/".$_POST['lgida']."/" );

				$db->update( "users" , array('ulang'=>$_POST['lgidb']) , array('ulang'=>$_POST['lgida']) );

				$note = LANG_CP_LANG_SUCC3;

			}

		}

		$dlang = $this->fileList($conf['path']."language/", array(".","..","index.php") , "" , "langname" );

		$orig = array("<%DELETELANG%>", "<%LANGLIST%>", "<%CHANGETO%>" , "<%GO%>", "<%AREYOUSURE%>");
		
		$repl = array( LANG_CP_LANG_DELETELANG, $dlang , LANG_CP_LANG_TRANSFERTO , LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/deletelang.tmpl.php" ) ) );

	}

	function editLanguage() {

		global $conf, $lang;

		$this->title[] = $lang['nav']['admin'][5]['sub'][1];
		$this->crumb[] = $lang['nav']['admin'][5]['sub'][1];

		if( $_POST['finish'] ) {

			$write = fopen( $_POST['finish'] , "w" );
		
			fwrite($write, stripslashes( $_POST['lgcontent'] ) );

			fclose($write);	

			$note = LANG_CP_LANG_SUCC2;

			$_POST['edit'] = $_POST['finish'];

		}

		if( $_POST['edit'] ) {

			$content = $this->grabFile($_POST['edit']);

			$filename = str_replace( $conf['path'] , "" , $_POST['edit'] );

			$file = $this->grabFile( $this->skin."/admin/editlang_3.tmpl.php" );

			$file.="<input type='hidden' name='finish' value='{$_POST['edit']}'>";

			$orig = array("<%FILENAME%>", "<%LANGFILE%>", "<%GO%>");
		
			$repl = array( $filename, htmlentities($content) , LANG_SYS_SUBMIT);

		}

		if( $_POST['choose'] ) {

			$file = $this->grabFile( $this->skin."/admin/editlang_2.tmpl.php" );

			$orig = array("<%LANGFILES%>", "<%GO%>");
		
			$repl = array( $this->listDir($conf['path']."language/".$_POST['choose']) , LANG_SYS_SUBMIT);


		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."/admin/editlang_1.tmpl.php" );

			$dlang = $this->fileList($conf['path']."language/", array(".","..","index.php") , "" , "langname" );

			$orig = array("<%LANGLIST%>", "<%GO%>");
		
			$repl = array( $dlang , LANG_SYS_SUBMIT);
		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) );		

	}


	function createLanguage() {

		global $conf, $lang;

		$this->title[] = $lang['nav']['admin'][5]['sub'][0];
		$this->crumb[] = $lang['nav']['admin'][5]['sub'][0];

		if( $_POST ) {

		// GET THE LANGUAGE NUMBER

			if( is_dir( $conf['path']."language/") ) {

				if( $dh = opendir( $conf['path']."language/" ) ) {

					while( ( $file = readdir( $dh ) ) !== false ) {

						if( !in_array( $file , array(".","..","index.php") ) ) $c[] = $file;

					}

					closedir($dh);
				}

			} 	

			$name = max($c) + 1;	

		// MAKE THE FOLDERS	

			mkdir( $conf['path'] . "language/".$name."/", 0777); 

			mkdir( $conf['path'] . "language/".$name."/mail/", 0777); 

		// MAKE THE INDEX NAME FILE

			$write = fopen( $conf['path'] . "language/".$name."/index.php" , "w" );

			$string.= "$"."lang['langname'] = \"{$_POST['lgname']}\";\r\n";

			$string = "<?php\r\n{$string}?>";
		
			fwrite($write, $string);

			fclose($write);		

		// POPULATE THE FOLDERS	

			$this->copyContent(
				$conf['path']."language/".$_POST['lgid']."/", 
				$conf['path']."language/".$name."/", array(".","..","index.php", "mail")
			);

			$this->copyContent(
				$conf['path']."language/".$_POST['lgid']."/mail/", 
				$conf['path']."language/".$name."/mail/", array(".","..", "mail")
			);

			$note = LANG_CP_LANG_SUCC1;

		} 

		$dlang = $this->fileList($conf['path']."language/", array(".","..","index.php") , "" , "langname" );

		$orig = array("<%BASEON%>", "<%LANGLIST%>", "<%LANGNAME%>" , "<%GO%>");
		
		$repl = array( LANG_CP_LANG_ADDBASEON, $dlang , LANG_CP_LANG_ADDLANGNAME , LANG_SYS_SUBMIT);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/addlang.tmpl.php" ) ) );

	}

	function rmdirr($dirname){

    		if( !file_exists( $dirname ) ) return false;
  
    		if( is_file( $dirname ) ) return unlink( $dirname );

    		$dir = dir( $dirname );

			while( false !== $entry = $dir->read() ) {
	
        			if ( $entry == '.' || $entry == '..' ) {
            				continue;
        			}

       				$this->rmdirr( "$dirname/$entry" );
    			}
 
		$dir->close();

    		return rmdir( $dirname );

	}

	function deleteSkin() {

		global $conf, $lang, $db;;

		$this->title[] = $lang['nav']['admin'][4]['sub'][2];
		$this->crumb[] = $lang['nav']['admin'][4]['sub'][2];

		if( $_POST ) {

			if( $_POST['skida'] == $conf['default_skin'] ) $note = LANG_CP_SKIN_ERR1;

			elseif( $_POST['skida'] == $_POST['skidb'] ) $note = LANG_CP_SKIN_ERR2;

			else {

				$this->rmdirr( $conf['path']."skins/".$_POST['skida']."/" );

				$db->update( "users" , array('uskin'=>$_POST['skidb']) , array('uskin'=>$_POST['skida']) );

				$note = LANG_CP_SKIN_SUCC3;

			}

		}

		$dskin = $this->fileList($conf['path']."skins/", array(".","..","index.php") , "" , "skinname" );

		$orig = array("<%DELETESKIN%>", "<%SKINLIST%>", "<%CHANGETO%>" , "<%GO%>", "<%AREYOUSURE%>");
		
		$repl = array( LANG_CP_SKIN_DELETESKIN, $dskin , LANG_CP_SKIN_TRANSFERTO , LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/deleteskin.tmpl.php" ) ) );

	}

	function listDir($dir) {

		$file_list = "";

		$stack[] = $dir;

		while ( $stack ) {

			$current_dir = array_pop( $stack );

			if( $dh = opendir( $current_dir ) ) {

				while( ( $file = readdir( $dh ) ) !== false ) {

					if( $file != "." && $file != ".." && $file != "images" && $file != "index.php" ) {

						$current_file = "{$current_dir}/{$file}";

                       				if( is_dir( $current_file ) ) $stack[] = $current_file; 

                       				if( is_file( $current_file ) ) {

							if( strstr( $current_dir , "admin" ) ) $prea = "admin";

							if( strstr( $current_dir , "controlpanel" ) ) $preb = "controlpanel";

							if( strstr( $current_dir , "mail" ) ) $prea = "mail";

							if( $prea && !$x ) {

								$file_list[] = "<tr><td colspan='2' class='fframe'>{$prea}</td></tr>";

								$x = 1;

							}

							if( $preb && !$y ) {

								$file_list[] = "<tr><td colspan='2' class='fframe'>{$preb}</td></tr>";

								$y = 1;

							}
									

							$file_list[] = "<tr><td class='fframe'>
							<input type='radio' name='edit' value='{$current_file}'></td>
							<td>{$file}</td></tr>";

						}

                   			}

               			}

           		}

       		}

      		return implode( "" , $file_list );
   
	}

	function editSkin() {

		global $conf, $lang;

		$this->title[] = $lang['nav']['admin'][4]['sub'][1];
		$this->crumb[] = $lang['nav']['admin'][4]['sub'][1];

		if( $_POST['finish'] ) {

			$write = fopen( $_POST['finish'] , "w" );
		
			fwrite($write, stripslashes( $_POST['skcontent'] ) );

			fclose($write);	

			$note = LANG_CP_SKIN_SUCC2;

			$_POST['edit'] = $_POST['finish'];

		}

		if( $_POST['edit'] ) {

			$content = $this->grabFile($_POST['edit']);

			$filename = str_replace( $conf['path'] , "" , $_POST['edit'] );

			$file = $this->grabFile( $this->skin."/admin/editskin_3.tmpl.php" );

			$file.="<input type='hidden' name='finish' value='{$_POST['edit']}'>";

			$orig = array("<%FILENAME%>", "<%SKINFILE%>", "<%GO%>");
		
			$repl = array( $filename, htmlentities($content) , LANG_SYS_SUBMIT);

		}

		if( $_POST['choose'] ) {

			$file = $this->grabFile( $this->skin."/admin/editskin_2.tmpl.php" );

			$orig = array("<%SKINFILES%>", "<%GO%>");
		
			$repl = array( $this->listDir($conf['path']."skins/".$_POST['choose']) , LANG_SYS_SUBMIT);


		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."/admin/editskin_1.tmpl.php" );

			$dskin = $this->fileList($conf['path']."skins/", array(".","..","index.php") , "" , "skinname" );

			$orig = array("<%SKINLIST%>", "<%GO%>");
		
			$repl = array( $dskin , LANG_SYS_SUBMIT);
		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) );		

	}

	function copyContent($from, $to, $excl) {

		if( is_dir( $from ) ) {

			if( $dh = opendir( $from ) ) {

				while( ( $file = readdir( $dh ) ) !== false ) {

					if( !in_array( $file , $excl ) ) { 

						$copy = $this->grabFile($from.$file);

						$write = fopen( $to.$file , "w" );

						fwrite($write, $copy);

						fclose($write);		

					}

				}

				closedir($dh);

			}

		} 

	}

	function createSkin() {

		global $conf, $lang;

		$this->title[] = $lang['nav']['admin'][4]['sub'][0];
		$this->crumb[] = $lang['nav']['admin'][4]['sub'][0];

		if( $_POST ) {

		// GET THE SKIN NUMBER

			if( is_dir( $conf['path']."skins/") ) {

				if( $dh = opendir( $conf['path']."skins/" ) ) {

					while( ( $file = readdir( $dh ) ) !== false ) {

						if( !in_array( $file , array(".","..","index.php") ) ) $c[] = $file;

					}

					closedir($dh);
				}

			} 	

			$name = max($c) + 1;	

		// MAKE THE FOLDERS	

			mkdir( $conf['path'] . "skins/".$name."/", 0777); 

			mkdir( $conf['path'] . "skins/".$name."/images/", 0777); 

			mkdir( $conf['path'] . "skins/".$name."/images/cats/", 0777); 

			mkdir( $conf['path'] . "skins/".$name."/controlpanel/", 0777); 

			mkdir( $conf['path'] . "skins/".$name."/admin/", 0777); 

		// MAKE THE INDEX NAME FILE

			$write = fopen( $conf['path'] . "skins/".$name."/index.php" , "w" );

			$string.= "$"."lang['skinname'] = \"{$_POST['skname']}\";\r\n";

			$string = "<?php\r\n{$string}?>";
		
			fwrite($write, $string);

			fclose($write);		

		// POPULATE THE FOLDERS	

			$this->copyContent(
				$conf['path']."skins/".$_POST['skid']."/", 
				$conf['path'] . "skins/".$name."/", array(".","..","index.php", "admin", "controlpanel", "images")
			);

			$this->copyContent(
				$conf['path']."skins/".$_POST['skid']."/admin/", 
				$conf['path'] . "skins/".$name."/admin/", array(".","..")
			);

			$this->copyContent(
				$conf['path']."skins/".$_POST['skid']."/controlpanel/", 
				$conf['path'] . "skins/".$name."/controlpanel/", array(".","..")
			);

			$this->copyContent(
				$conf['path']."skins/".$_POST['skid']."/images/", 
				$conf['path'] . "skins/".$name."/images/", array(".","..","cats")
			);

			$this->copyContent(
				$conf['path']."skins/".$_POST['skid']."/images/cats/", 
				$conf['path'] . "skins/".$name."/images/cats/", array(".","..")
			);

			$note = LANG_CP_SKIN_SUCC1;

		} 

		$dskin = $this->fileList($conf['path']."skins/", array(".","..","index.php") , "" , "skinname" );

		$orig = array("<%BASEON%>", "<%SKINLIST%>", "<%SKINNAME%>" , "<%GO%>");
		
		$repl = array( LANG_CP_SKIN_ADDBASEON, $dskin , LANG_CP_SKIN_ADDSKINNAME , LANG_SYS_SUBMIT);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/addskin.tmpl.php" ) ) );

	}

	function deleteGroup() {

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][3]['sub'][2];
		$this->crumb[] = $lang['nav']['admin'][3]['sub'][2];

		if( $_POST ) {

			if( $_POST['gida'] == $_POST['gidb'] ) $note = LANG_CP_GRP_ERR1;

			else {

				$db->update( "users" , array('ugroup'=>$_POST['gidb']) , array('ugroup'=>$_POST['gida']) );

				$db->delete( "groups" , array('gid'=>$_POST['gida']) );

				$note = LANG_CP_GRP_SUCC3;

			}

		}

		$groups = $db->select( "gid, gname" , "groups" , "" , "ORDER BY gid ASC" );

		foreach( $groups as $row ) {
	
			$row = $this->cleanText( $row );

			$grouplist.="<option value='{$row['gid']}'>{$row['gname']}</option>";

		}

		$orig = array("<%DELETEGROUP%>", "<%CHANGETO%>", "<%GROUPLIST%>" , "<%GO%>", "<%AREYOUSURE%>");
		
		$repl = array( LANG_CP_GRP_DELETEGROUP, LANG_CP_GRP_CHANGETO, $grouplist , LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/deletegroup.tmpl.php" ) ) );			

	}

	function editGroup() {

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][3]['sub'][1];
		$this->crumb[] = $lang['nav']['admin'][3]['sub'][1];

		if( $_POST['finish'] ) {

			$id = $_POST['finish'];

			unset( $_POST['finish'] );

			$db->update( "groups" , $_POST , array('gid'=>$id) );

			$_POST['edit'] = $id;

			$note = LANG_CP_GRP_SUCC2;

		}

		if( $_POST['edit'] ) {

			$file = $this->grabFile( $this->skin."/admin/editgroup_2.tmpl.php" );

			$file.= "<input type='hidden' name='finish' value='{$_POST['edit']}'>";

			$group = $db->select( "gid, gname, gcolor" , "groups" , array('gid'=>$_POST['edit']) );

			$group = $this->cleanForm( $group[0] );

			$orig = array("<%GROUPNAME%>", "<%GROUPNAME_VAL%>" , "<%GROUPCOLOR%>", "<%GROUPCOLOR_VAL%>" , "<%GO%>");
		
			$repl = array( LANG_CP_GRP_GROUPNAME, $group['gname'], LANG_CP_GRP_GROUPCOLOR, $group['gcolor'], LANG_SYS_SUBMIT);
			
		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."/admin/editgroup_1.tmpl.php" );

			$groups = $db->select( "gid, gname" , "groups" , "" , "ORDER BY gid ASC" );

			foreach( $groups as $row ) {
	
				$row = $this->cleanText( $row );

				$grouplist.="<option value='{$row['gid']}'>{$row['gname']}</option>";

			}

			$orig = array("<%GROUPLIST%>" , "<%GO%>");
		
			$repl = array( $grouplist , LANG_SYS_SUBMIT);

		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) );	

	}

	function addGroup() {

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][3]['sub'][0];
		$this->crumb[] = $lang['nav']['admin'][3]['sub'][0];	

		if( $_POST['gname'] ) {

			$db->insert( "groups" , $_POST );

			$note = LANG_CP_GRP_SUCC1;			

		}

		$orig = array("<%GROUPNAME%>" , "<%GROUPCOLOR%>" , "<%GO%>");
		
		$repl = array( LANG_CP_GRP_GROUPNAME, LANG_CP_GRP_GROUPCOLOR, LANG_SYS_SUBMIT);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/addgroup.tmpl.php" ) ) );	

	}

	function deleteAccount() {

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][2]['sub'][1];
		$this->crumb[] = $lang['nav']['admin'][2]['sub'][1];	

		if( $_POST ) {

			$stories = $db->select( "sid" , "stories" , array('suid'=>$_POST['uid']) );

			foreach( $stories as $row ) { 

				$chwhere[] = "chsid='{$row['sid']}'";

				$rewhere[] = "rsid='{$row['sid']}'";

			}

			$chwhere = @implode( " OR " , $chwhere );

			$rewhere = @implode( " OR " , $rewhere );

			$db->delete( "users" , array('uid'=>$_POST['uid']) );
		
			$db->delete( "stories" , array('suid'=>$_POST['uid']) );

			$db->delete( "chapters" , $chwhere );

			$db->delete( "reviews" , $rewhere );

			$db->update( "reviews" , array('ruid'=>1) , array('ruid'=>$_POST['uid']) );

			$note = LANG_CP_U_SUCC2;

		} 

		$orig = array("<%ACCOUNTNO%>" , "<%GO%>", "<%WARNING%>" , "<%AREYOUSURE%>");
		
		$repl = array( LANG_CP_U_ACCOUNTNO , LANG_SYS_SUBMIT, LANG_CP_U_WARN1,  LANG_SYS_AREYOUSURE );

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/deleteaccount.tmpl.php" ) ) );			

	}

	function editAccount() {
	
		global $db, $lang, $conf;

		$this->title[] = $lang['nav']['admin'][2]['sub'][0];
		$this->crumb[] = $lang['nav']['admin'][2]['sub'][0];	


		if( $_POST['finish'] ) {

			$l = $conf['penname_length'];
			$p = $conf['password_length'];

			if( !$this->eregCheck( $_POST['uname'] , "^[A-Z0-9 ]{3,$l}$") ) $note[] = LANG_CP_U_ERR5;

			if( !$this->eregCheck( $_POST['uemail'] , 
			"^[a-z0-9]+([_\\.-][a-z0-9]+)*" ."@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$") ) $note[] = LANG_CP_U_ERR4;

			if( $this->eregCheck( $_POST['upass'] , "^[A-Z0-9]{3,$p}$") ) { 

				$_POST['upass'] = crypt( $_POST['upass'] , $conf['salt'] );

			} else { 

				unset($_POST['upass']);

			}

			$check = $db->select( "uname" , "users" , "uname='{$_POST['uname']}' AND uid != {$_POST['finish']}" );

			if( $check ) $note[] = LANG_CP_U_ERR2;

			if( !$note ) {

				if( $_FILES['uavatarb']['name'] ) {

					$size = getImageSize( $_FILES['uavatarb']['tmp_name'] );

					if( $size[0] <= $conf['avatar_width'] && $size[1] <= $conf['avatar_height'] ) {

						$ext = explode( "." , $_FILES['uavatarb']['name'] );

				copy( $_FILES['uavatarb']['tmp_name'], $conf['path']."avatars/upload/".$_POST['finish'].".".end($ext));

						$_POST['uavatar'] = "upload/{$_POST['finish']}.".end($ext);

					}

					unset( $_FILES );

				} else {

					if( !strstr( $_POST['uavatara'] , "upload" ) ) $_POST['uavatar'] = "site/{$_POST['uavatara']}";

				}

				unset( $_POST['uavatara'] );
		
				$id = $_POST['finish'];

				unset( $_POST['finish'] );

				$db->update( "users" , $_POST , array('uid'=>$id) ) or die($db->getError());

			} else $note = implode( "<br>" , $note );

			$note = LANG_CP_U_SUCC1;

			$_POST['edit'] = $id;

		}

		if( $_POST['edit'] ) {

			$fa = DBPRE;

			$account = $db->select( "*" , "users" , array('uid'=>$_POST['edit']) );

			if( $account && $_POST['edit'] != 1 ) {

				$account = $account[0];

				$groups = $db->select( "gid, gname", "groups" , "", "ORDER BY gid ASC" );

				foreach( $groups as $row ) {

					$this->cleanText( $row );

					$row['gid'] == $account['ugroup'] ? $sel = " selected class='hl'" : $sel = "";

					$group.="<option value='{$row['gid']}'{$sel}>{$row['gname']}</option>";

				}

				$language = $this->fileList($conf['path']."language/", array(".","..","index.php") , $account['ulang'], 
				"langname");

				$skin = $this->fileList($conf['path']."skins/", array(".","..","index.php") , $account['uskin'], 
				"skinname");

				!$account['uavatar'] ? $curr = "site/None.gif" : $curr = $account['uavatar'];

				$img = "<img src='avatars/{$curr}' name='img'>";

		if( strstr( $curr, "upload" ) ) $avatars ="<option value='{$account['uavatar']}' selected class='hl'>Current";

		$avatars.= $this->fileList($conf['path']."avatars/site/", array(".","..","index.php") , str_replace( "site/" , "" , $curr ) );

				$file = $this->grabFile( $this->skin."/admin/editaccount_2.tmpl.php" );
				
				$file.="<input type='hidden' name='finish' value='{$_POST['edit']}'>";

				$orig = array("<%PENNAME%>", "<%PENNAME_VAL%>", "<%PASSWORD%>", "<%EMAIL%>", "<%EMAIL_VAL%>", "<%LANGUAGE%>",
				"<%LANGUAGE_VAL%>", "<%SKIN%>", "<%SKIN_VAL%>", "<%BIO%>", "<%BIO_VAL%>", "<%AVATAR%>", "<%AVATAR_VAL%>",
				"<%IMGP%>", "<%GROUP%>", "<%GROUP_VAL%>", "<%IP%>", "<%IP_VAL%>", "<%REGISTERED%>", "<%REGISTERED_VAL%>",
				"<%GO%>");

				$repl = array(LANG_CP_U_PENNAME, $account['uname'] , LANG_CP_U_PASSWORD , LANG_CP_U_EMAIL, $account['uemail'],
				LANG_CP_U_LANGUAGE, $language, LANG_CP_U_SKIN, $skin, LANG_CP_U_BIO, $this->cleanForm( $account['ubio'] ),
				$img, $avatars, $conf['url']."avatars/site/", LANG_CP_U_GROUP, $group, LANG_CP_U_IP, $account['uip'],
				LANG_CP_U_REGISTERED, $this->showTime($conf['time_format'] , $account['ustart']), LANG_SYS_SUBMIT);

			} else {

				$note = LANG_CP_U_ERR1;
		
				$_POST = "";

			}

		} 

		if( !$_POST ) {

			$orig = array("<%ACCOUNTNO%>" , "<%GO%>");
		
			$repl = array( LANG_CP_U_ACCOUNTNO , LANG_SYS_SUBMIT );

			$file = $this->grabFile( $this->skin."/admin/editaccount_1.tmpl.php" );

		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) , 1);

	}

	function orderCategories() {
	
		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][1]['sub'][3];
		$this->crumb[] = $lang['nav']['admin'][1]['sub'][3];		

		if( $_POST ) {

			foreach( $_POST['cid'] as $key=>$is ) $db->update( "categories" , array('corder'=>$is) , array('cid'=>$key) );

			$note = LANG_AP_CAT_SUCC4;

		}

		$categories = $db->select( "cname, cid, cparent, corder" , "categories" , "" , "ORDER BY corder" );

		$categories = $this->orderParent( $categories );

		$orig = array("<%ORDERCATEGORIES%>" , "<%GO%>");
		
		$repl = array( $categories , LANG_SYS_SUBMIT);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/ordercategories.tmpl.php" ) ) );

	}

	function orderDepth( $category , $parent , $id) {

		global $conf;

		if( $parent != 0 ) { 

			$sep.= "<img src='{$this->skin}images/cats/0.gif' width='20'>";

			foreach( $category as $row ) {

				if( $row['cid'] == $parent ) {
	
					$sep.= $this->orderDepth( $category , $row['cparent'] ,  $row['cid'] ); 

					break;

				}

			}

		}

		return $sep;

	} 

	function orderChild( $category , $parent , $id = " ", $type=" " ) {

		foreach( $category as $key=>$row ) {
			
			if( $row['cparent'] == $parent ) {

				$id != " " && $id == $row['cid'] ? $sel = " selected class='hl'" : $sel = "";

				$op.= "<tr><td>" . $this->orderDepth( $category , $parent , $row['cid'] ) . 
				"<input type='text' name='cid[{$row['cid']}]' value='{$row['corder']}' class='depth'> {$row['cname']}
				</td></tr>";
	
				$op.= $this->orderChild( $category , $row['cid'] , $id );



			}

		} 
	
		return $op;

	}

	function orderParent( $category , $id = " ") {

		foreach( $category as $key=>$row ) {

			if( $row['cparent'] == 0 ) { 

				$op.= "<tr><td>
				<input type='text' name='cid[{$row['cid']}]' value='{$row['corder']}' class='depth'> {$row['cname']}
				</td></tr>";

				$op.= $this->orderChild( $category , $row['cid'] , $id);

			}

		}

		return $op;
		
	}

	function deleteCategory() {

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][1]['sub'][2];
		$this->crumb[] = $lang['nav']['admin'][1]['sub'][2];

		if( $_POST ) {

			if( $_POST['cida'] == $_POST['cidb'] ) $note = LANG_AP_CAT_ERR1;

			else {

				$db->update( "categories" , array('cparent'=>$_POST['cidb']) , array('cparent'=>$_POST['cida']) );

				$db->update( "stories" , array('scid'=>$_POST['cidb']) , array('scid'=>$_POST['cida']) );

				$db->delete( "categories" , array('cid'=>$_POST['cida']) ) or die($db->getError());

				$note = LANG_AP_CAT_SUCC3;

			}

		}

		$categories = $db->select( "cname, cid, cparent" , "categories" , "" , "ORDER BY corder" );

		$categories = $this->makeParent( $categories );

		$orig = array("<%DELETECATEGORY%>" , "<%CATEGORYLIST%>", "<%CHANGETO%>", "<%GO%>", "<%AREYOUSURE%>", "<%TOPLEVEL%>");
		
		$repl = array( LANG_AP_CAT_DELETE, $categories, LANG_AP_CAT_CHANGETO, LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE, 
		LANG_AP_CAT_TOPLEVEL);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/deletecategory.tmpl.php" ) ) );

	}

	function editCategory() {

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][1]['sub'][1];
		$this->crumb[] = $lang['nav']['admin'][1]['sub'][1];

		if( $_POST['finish'] ) {

			if( $_FILES['cimgb']['name'] ) {

				copy( $_FILES['cimgb']['tmp_name'] , $this->skin."images/cats/".$_FILES['cimgb']['name'] );

				$_POST['cimg'] = $_FILES['cimgb']['name'];

				$_FILES = "";

			} else $_POST['cimga'] != "0.gif" ? $_POST['cimg'] = $_POST['cimga'] : $_POST['cimg'] = "";

			$id = $_POST['finish'];

			unset( $_POST['cimga'] );
			unset( $_POST['finish'] );


			if( $_POST['cchars'] ) {

				$_POST['cchars'] = trim( str_replace( "|" , "" , $_POST['cchars'] ) );

				$_POST['cchars'] = str_replace( "\r\n" , "|" , $_POST['cchars'] );

			}

			$_POST['cread'] ? $_POST['cread'] = implode( "|" , $_POST['cread'] ) : $_POST['cread'] = "";
			$_POST['cpost'] ? $_POST['cpost'] = implode( "|" , $_POST['cpost'] ) : $_POST['cpost'] = "";
			$_POST['cmod'] ? $_POST['cmod'] = implode( "|" , $_POST['cmod'] ) : $_POST['cmod'] = "";

			$db->update( "categories" , $_POST , array('cid'=>$id) );

			$_POST['edit'] = $id;

			$note = LANG_AP_CAT_SUCC2;

		}

		if( $_POST['edit'] ) {

			$category = $db->select( "*" , "categories" , array('cid'=>$_POST['edit']) );

			$category = $category[0];

			$categories = $db->select( "cid, cname, cparent" , "categories" , "" , "ORDER BY corder ASC" );

			$groups = $db->select( "gid, gname" , "groups" , "" , "ORDER BY gid ASC" );

			$users = $db->select( "uid, uname", "users" , "uid != 1" , "ORDER BY uname ASC" );

			if( $category['cparent'] == 0 ) $sel = " selected class='hl'"; 

			$parent = "<option value='0'{$sel}>" . LANG_AP_CAT_TOPLEVEL . $this->makeParent($categories, $category['cparent']);

			$imgp = "{$this->skin}images/cats/";

			$category['cimg'] ? $fimg = $category['cimg'] : $fimg = "0.gif";

			$img = "<img src='{$imgp}{$fimg}' name='img' id='img'>";

			$imgs = "<option value='0.gif'>".LANG_AP_CAT_SELIMG.
			$this->fileList( $imgp , array(".", "..","index.php","0.gif"), $category['cimg'] );

			$active = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 2, $category['cactive'], "cactive");

			$approve = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 2, $category['capp'] , "capp");

			$orig = array("<%CATNAME%>" , "<%CATDESCRIPTION%>", "<%CATPARENT%>", "<%CATPARENT_VAL%>", "<%IMG%>", "<%CATIMGA_VAL%>",
			"<%GO%>", "<%IMGP%>", "<%CATACTIVE%>", "<%CATACTIVE_VAL%>", "<%CATAPPROVAL%>", "<%CATAPPROVAL_VAL%>", 
			"<%CATPERMISSIONS%>", "<%CATPERMISSIONS_VAL%>", "<%CATMODERATORS%>", "<%CATMODERATORS_VAL%>", "<%CATNAME_VAL%>",
			"<%CATDESC_VAL%>" , "<%CATCHARACTERS%>" , "<%CATCHARACTERS_VAL%>");
		
			$repl = array(LANG_AP_CAT_NAME, LANG_AP_CAT_DESC, LANG_AP_CAT_PARENT, $parent , $img , $imgs, LANG_SYS_SUBMIT, $imgp,
			LANG_AP_CAT_OPEN, $active, LANG_AP_CAT_APPROVAL, $approve, LANG_AP_CAT_PERMISSIONS, 
			$this->makePerm( $groups, $category['cread'], $category['cpost'] ), LANG_AP_CAT_MODERATORS, 
			$this->makeMods($users, $category['cmod']),$this->cleanForm($category['cname']), 
			$this->cleanForm($category['cdesc']),LANG_AP_CAT_CHARACTERS, str_replace( "|" , "\r\n" , $category['cchars'] ));

			$file = $this->grabFile( $this->skin."/admin/editcategory_2.tmpl.php" );
			$file.= "<input type='hidden' name='finish' value='{$category['cid']}'>";

		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."/admin/editcategory_1.tmpl.php" );

			$category = $db->select( "cid, cname, cparent" , "categories" , "" , "ORDER BY corder ASC" );

			$orig = array("<%SELECTCATEGORY%>", "<%GO%>");
	
			$repl = array( $this->makeParent( $category ) , LANG_SYS_SUBMIT );

		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) , 1 );

	}

	function addCategory(){

		global $db, $lang;

		$this->title[] = $lang['nav']['admin'][1]['sub'][0];
		$this->crumb[] = $lang['nav']['admin'][1]['sub'][0];

		if( $_POST['cname'] ) {

			if( $_FILES['cimgb']['name'] ) {

				copy( $_FILES['cimgb']['tmp_name'] , $this->skin."images/cats/".$_FILES['cimgb']['name'] );

				$_POST['cimg'] = $_FILES['cimgb']['name'];

				$_FILES = "";

			} else $_POST['cimga'] != "0.gif" ? $_POST['cimg'] = $_POST['cimga'] : $_POST['cimg'] = "";

			unset( $_POST['cimga'] );

			if( $_POST['cchars'] ) {

				$_POST['cchars'] = trim( str_replace( "|" , "" , $_POST['cchars'] ) );

				$_POST['cchars'] = str_replace( "\r\n" , "|" , $_POST['cchars'] );

			}

			$order = $db->select( "MAX(corder) AS ord" , "categories" , array('cparent'=>$_POST['cparent']) );

			$_POST['corder'] = $order[0]['ord'] + 1;
			$_POST['cread'] ? $_POST['cread'] = implode( "|" , $_POST['cread'] ) : $_POST['cread'] = "";
			$_POST['cpost'] ? $_POST['cpost'] = implode( "|" , $_POST['cpost'] ) : $_POST['cpost'] = "";
			$_POST['cmod'] ? $_POST['cmod'] = implode( "|" , $_POST['cmod'] ) : $_POST['cmod'] = "";

			$db->insert( "categories" , $_POST );

			$note = LANG_AP_CAT_SUCC1;

		}

		$categories = $db->select( "cid, cname, cparent" , "categories" , "" , "ORDER BY corder ASC" );

		$groups = $db->select( "gid, gname" , "groups" , "" , "ORDER BY gid ASC" );

		$users = $db->select( "uid, uname", "users" , "uid != 1" , "ORDER BY uname ASC" );

		$parent = "<option value='0'>" . LANG_AP_CAT_TOPLEVEL . $this->makeParent($categories);

		$imgp = "{$this->skin}images/cats/";

		$img = "<img src='{$imgp}0.gif' name='img' id='img'>";

		$imgs = "<option value='0.gif'>".LANG_AP_CAT_SELIMG.$this->fileList( $imgp , array(".", "..","index.php","0.gif") );

		$active = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 2, 1, "cactive");

		$approve = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 2, "" , "capp");

		$orig = array("<%CATNAME%>" , "<%CATDESCRIPTION%>", "<%CATPARENT%>", "<%CATPARENT_VAL%>", "<%IMG%>", "<%CATIMGA_VAL%>",
		"<%GO%>", "<%IMGP%>", "<%CATACTIVE%>", "<%CATACTIVE_VAL%>", "<%CATAPPROVAL%>", "<%CATAPPROVAL_VAL%>", "<%CATPERMISSIONS%>",
		"<%CATPERMISSIONS_VAL%>", "<%CATMODERATORS%>", "<%CATMODERATORS_VAL%>", "<%CATCHARACTERS%>");
		
		$repl = array(LANG_AP_CAT_NAME, LANG_AP_CAT_DESC, LANG_AP_CAT_PARENT, $parent , $img , $imgs, LANG_SYS_SUBMIT, $imgp,
		LANG_AP_CAT_OPEN, $active, LANG_AP_CAT_APPROVAL, $approve, LANG_AP_CAT_PERMISSIONS, $this->makePerm( $groups ), 
		LANG_AP_CAT_MODERATORS, $this->makeMods($users), LANG_AP_CAT_CHARACTERS);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."/admin/addcategory.tmpl.php" ) ) , 1 );

	}

	function makeMods( $users, $curr=" " ) {

		$curr = explode( "|" , $curr );

		foreach( $users as $row ) {

			$row = $this->cleanText( $row );

			@in_array( $row['uid'], $curr ) ? $sel = " selected class='hl'" : $sel="";

			$opt.="<option value='{$row['uid']}'{$sel}>{$row['uname']}</option>"; 

		}

		return $opt;

	}

	function makePerm($groups, $currr=" ", $currp=" ") {

		$cont = "<table border='0' width='100%'>
		<tr><td>&nbsp;</td><td class='fframe'>" .LANG_AP_CAT_READPERM. "</td><td class='fframe'>" .LANG_AP_CAT_WRITEPERM. "</td></tr>"; 

		$currr = explode( "|" , $currr );

		$currp = explode( "|" , $currp );

		foreach( $groups as $row ) {

			$row = $this->cleanText( $row );

			in_array( $row['gid'] , $currr ) ? $rc = " checked" : $rc = "";
			in_array( $row['gid'] , $currp ) ? $pc = " checked" : $pc = "";

			$cont.="<tr><td class='frame'>{$row['gname']}</td>
			<td><input type='checkbox' name='cread[]' value='{$row['gid']}'{$rc}></td>
			<td><input type='checkbox' name='cpost[]' value='{$row['gid']}'{$pc}></td></tr>";

		}

		$cont.="</table>";

		return $cont;

	}

	function generalSettings() {

		global $db, $lang, $conf;

		$this->title[] = $lang['nav']['admin'][0]['main'];
		$this->crumb[] = $lang['nav']['admin'][0]['main'];

		if( $_POST ) {

			$write = fopen( "config.inc.php" , "w" );

			foreach( $_POST['db'] as $key=>$is ) 

				$string.= "define(\"".strtoupper($key)."\" , \"{$is}\");\r\n";

			foreach( $_POST['con'] as $key=>$is ) {

				$row[$key] = stripslashes( $is );
				$row[$key] = addslashes( $is );

				$string.= "$"."conf['".$key."'] = \"{$is}\";\r\n";

			}

			$string = "<?php\r\n{$string}?>";
		
			fwrite($write, $string);

			fclose($write);

			$note = LANG_AP_GS_SUCC1;

			header("Refresh:1");

		}

		$status_val = $this->makeChooser( LANG_AP_GS_OPEN, LANG_AP_GS_CLOSED, 1, $conf['open']);

		$orig = array("<%DATABASE%>" , "<%DBSERVER%>" , "<%DBSERVER_VAL%>", "<%DBNAME%>" , "<%DBNAME_VAL%>", "<%DBUSER%>", 
		"<%DBUSER_VAL%>", "<%DBPASS%>" , "<%DBPASS_VAL%>" , "<%DBPRE%>" , "<%DBPRE_VAL%>", "<%SITE%>", "<%TITLE%>" , 
		"<%TITLE_VAL%>", "<%URL%>", "<%URL_VAL%>", "<%PATH%>" , "<%PATH_VAL%>" , "<%BOTEMAIL%>" , "<%BOTEMAIL_VAL%>", "<%STATUS%>" , 			"<%STATUS_VAL%>", "<%GENERAL%>", "<%MAGE%>", "<%MAGE_VAL%>", "<%TIME%>", "<%TIME_VAL%>", "<%TIMEFORMAT%>" , 		"<%TIMEFORMAT_VAL%>", "<%TIMEFORMATNEWS%>", "<%TIMEFORMATNEWS_VAL%>", "<%ALLOWEDHTML%>",
		"<%ALLOWEDHTML_VAL%>", "<%PASSWORDSALT%>", "<%PASSWORDSALT_VAL%>", "<%COOKIES%>", "<%COOKIEPRE%>" , "<%COOKIEPRE_VAL%>",
		"<%COOKIEPATH%>", "<%COOKIEPATH_VAL%>", "<%COOKIEDOMAIN%>", "<%COOKIEDOMAIN_VAL%>", "<%SEPARATORS%>", "<%SEPTITLE%>",
		"<%SEPTITLE_VAL%>", "<%SEPCRUMB%>", "<%SEPCRUMB_VAL%>", "<%SEPNAV%>", "<%SEPNAV_VAL%>", "<%SEPMISC%>", "<%SEPMISC_VAL%>",
		"<%LAYOUT%>", "<%LAYCOLUMNS%>", "<%LAYCOLUMNS_VAL%>", "<%LAYPPAGE%>", "<%LAYPPAGE_VAL%>", "<%LATEST%>", "<%LATESTLIMIT%>",
		"<%LATESTLIMIT_VAL%>", "<%SEARCH%>", "<%SEARCHLIMIT%>", "<%SEARCHLIMIT_VAL%>", "<%SEARCHWAIT%>", "<%SEARCHWAIT_VAL%>",
		"<%USERS%>", "<%USERNAMELIMIT%>", "<%USERNAMELIMIT_VAL%>", "<%USERPASSLIMIT%>", "<%USERPASSLIMIT_VAL%>");

		$repl = array( LANG_AP_GS_DATABASE , LANG_AP_GS_DBSERVER , DBSERVER, LANG_AP_GS_DBNAME, DBNAME, LANG_AP_GS_DBUSER, DBUSER,
		LANG_AP_GS_DBPASS, DBPASS, LANG_AP_GS_DBPRE, DBPRE, LANG_AP_GS_SITE , LANG_AP_GS_TITLE, $this->cleanForm($conf['title']), 
		LANG_AP_GS_URL, $conf['url'], LANG_AP_GS_PATH, $conf['path'], LANG_AP_GS_BOT, $conf['email_bot'] , LANG_AP_GS_STATUS, 				$status_val, LANG_AP_GS_GENERAL, 
		LANG_AP_GS_MAGE, $conf['mage'], LANG_AP_GS_TIME, $conf['time'] , LANG_AP_GS_TIMEFORMAT , $conf['time_format'], 
		LANG_AP_GS_TIMEFORMATNEWS, $conf['time_format_news'],
		LANG_AP_GS_ALLOWEDHTML, $conf['allowed_html'], LANG_AP_GS_PASSWORDSALT, $conf['salt'], LANG_AP_GS_COOKIES, 
		LANG_AP_GS_COOKIEPRE, $conf['cookie'], LANG_AP_GS_COOKIEPATH, $conf['cookie_path'], LANG_AP_GS_COOKIEDOMAIN, 
		$conf['cookie_domain'], LANG_AP_GS_SEPARATORS, LANG_AP_GS_SEPTITLE, $this->cleanForm($conf['sep_title']), 
		LANG_AP_GS_SEPCRUMB, $this->cleanForm($conf['sep_crumb']), LANG_AP_GS_SEPNAVIG, $this->cleanForm($conf['sep_navig']),
		LANG_AP_GS_SEPMISC, $this->cleanForm($conf['sep_misc']), LANG_AP_GS_LAYOUT, LANG_AP_GS_LAYCOLUMNS, $conf['cols'],
		LANG_AP_GS_LAYPPAGE,$conf['ppage'], LANG_AP_GS_LATEST, LANG_AP_GS_LATESTLIMIT, $conf['latest_limit'], LANG_AP_GS_SEARCH,
		LANG_AP_GS_SEARCHLIMIT, $conf['search_limit'] , LANG_AP_GS_SEARCHWAIT, $conf['search_time'], LANG_AP_GS_USERS, 
		LANG_AP_GS_USERNAMELIMIT , $conf['penname_length'] , LANG_AP_GS_USERPASSLIMIT, $conf['password_length']);

		$form = str_replace( $orig , $repl, $this->grabFile( $this->skin."/admin/generalsettings.tmpl.php" ) );

		$orig = "";
		$repl = "";

		$upl = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 1, $conf['fiction_upload']);

		$xl = array(".","..","index.php");

		$dskin = $this->fileList($conf['path']."skins/", $xl , $conf['default_skin'] , "skinname" );

		$dlang = $this->fileList($conf['path']."language/", $xl , $conf['default_lang'], "langname");

		$groups = $db->select( "gid, gname" , "groups" , "" , "ORDER BY gid ASC" );

		foreach( $groups as $row ) {
	
			$row = $this->cleanText( $row );

			$row['gid'] == $conf['default_group'] ? $sel = " selected class='hl'" : $sel = "";

			$group.="<option value='{$row['gid']}'{$sel}>{$row['gname']}</option>";


		}

		$mua = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 1, $conf['mailer_updatealert']);
		$mra = $this->makeChooser(LANG_AP_YES, LANG_AP_NO, 1, $conf['mailer_reviewalert']);

		$orig = array("<%USERAVAWIDTH%>", "<%USERAVAWIDTH_VAL%>", "<%USERAVAHEIGHT%>","<%USERAVAHEIGHT_VAL%>", 
		"<%FICTION%>", "<%FICWORDS%>", "<%FICWORDS_VAL%>", "<%FICUPLOAD%>", "<%FICUPLOAD_VAL%>", "<%FICTYPES%>" ,
		"<%FICTYPES_VAL%>", "<%DEFAULTS%>", "<%DEFAULTSKIN%>", "<%DEFAULTLANG%>", "<%DEFAULTGROUP%>", "<%DEFAULTSKIN_VAL%>",
		"<%DEFAULTLANG_VAL%>","<%DEFAULTGROUP_VAL%>", "<%GO%>", "<%MAILER%>", "<%UPDATEALERT%>",
		"<%UPDATEALERT_VAL%>", "<%REVIEWALERT%>", "<%REVIEWALERT_VAL%>");

		$repl = array(LANG_AP_GS_USERAVAHEIGHT, $conf['avatar_height'] , LANG_AP_GS_USERAVAWIDTH, $conf['avatar_width'], 				LANG_AP_GS_FICTION, LANG_AP_GS_FICWORDS, $conf['fiction_words'], LANG_AP_GS_FICUPLOAD, $upl, 
		LANG_AP_GS_FICTYPES, $conf['fiction_types'], LANG_AP_GS_DEFAULTS, LANG_AP_GS_DEFAULTSKIN, LANG_AP_GS_DEFAULTLANG, 
		LANG_AP_GS_DEFAULTGROUP, $dskin, $dlang, $group, LANG_SYS_SUBMIT, LANG_AP_GS_MAILER, 
		LANG_AP_GS_MAILUPDATEALERT , $mua , LANG_AP_GS_MAILREVIEWALERT , $mra);

		return $note.$this->formWrap( str_replace( $orig, $repl, $form ) );

	}

	function makeNav(){

		global $lang, $conf;

		foreach( $lang['nav']['admin'] as $key=>$is ) {

			if( $is['sub'] ) {

				$nav[] = $is['main'];

				foreach( $is['sub'] as $are ) 

					$nav[] = "&rsaquo; <a href='{$conf['url']}index.php?go={$_GET['go']}&set={$are}'>{$are}</a>";

			} else $nav[] = "<a href='{$conf['url']}index.php?go={$_GET['go']}&set={$is['main']}'>{$is['main']}</a>";

		}

		return @implode( "<br>" , $nav );
	}

}
?>