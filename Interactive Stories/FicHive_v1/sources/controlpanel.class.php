<?php

class controlpanel extends dp {

	var $user = array();
	var $title = array();
	var $crumb = array();
	var $skin;

	function controlpanel(){
	}

	function makePage(){

		global $db, $conf, $lang;

		if( $this->user['gid'] == 1 ) {

			switch( $_GET['set'] ) {

				default: 
					$this->title[] = LANG_CP_LOGIN;
					$this->crumb[] = LANG_CP_LOGIN;
					$content = $this->login();
				break;

				case LANG_CP_REGISTER: 
					$this->title[] = LANG_CP_REGISTER;
					$this->crumb[] = LANG_CP_REGISTER;
					$content = $this->register();
				break;

			}

		} else {

			switch( $_GET['set'] ) {

				default:
					$content = $this->profile();
				break;

				case $lang['nav']['cpanel'][0]['sub'][1]:
					$content = $this->logout();
				break;

				case $lang['nav']['cpanel'][1]['sub'][0]:
					$content = $this->addStory();
				break;

				case $lang['nav']['cpanel'][1]['sub'][1]:
					$content = $this->editStory();
				break;

				case $lang['nav']['cpanel'][1]['sub'][2]:
					$content = $this->deleteStory();
				break;

				case $lang['nav']['cpanel'][1]['sub'][3]:
					$content = $this->addChapter();
				break;

				case $lang['nav']['cpanel'][1]['sub'][4]:
					$content = $this->editChapter();
				break;

				case $lang['nav']['cpanel'][1]['sub'][5]:
					$content = $this->deleteChapter();
				break;

				case $lang['nav']['cpanel'][1]['sub'][6]:
					$content = $this->orderChapters();
				break;

				case $lang['nav']['cpanel'][2]['sub'][0]:
					$content = $this->deleteFavorite();
				break;

				case $lang['nav']['cpanel'][3]['sub'][0]:
					$content = $this->deleteReviews();
				break;


			}

			$orig = array("<%NAVIGATION%>", "<%CONTENT%>");

			$repl = array( $this->makeNav() , $content );

			$content = str_replace( $orig , $repl , $this->grabFile( $this->skin."/controlpanel/layout.tmpl.php" ) );

		}

		$this->title ? $this->title = implode($conf['sep_title'] , $this->title) : $this->title = "";

		$this->crumb ? $this->crumb = implode($conf['sep_crumb'] , $this->crumb) : $this->crumb = "";

		return $content;

	}

// ##### EDIT CHAPTER ##### //

	function editChapter() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][5];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][5];

		if( $_POST['chchapter'] ) {

			$db->update( "chapters" , array('chtitle'=>$_POST['chtitle'], 'chchapter'=>$_POST['chchapter']), 
			array('chid'=>$_POST['finish']) );

			$note = LANG_CP_EDITCHAPTER_SUCC1;

			$_POST['chid'] = $_POST['finish'];

		}

		if( $_POST['chid'] ) {

			$chapter = $db->select( "*" , "chapters" , array('chsid'=>$_POST['chid']) );

			$chapter = $this->cleanForm($chapter[0]);

			$orig = array("<%GO%>", "<%CHAPTERTITLE%>", "<%CHAPTERTEXT%>" , "<%MAX%>", "<%WYSIWYG%>", 
			"<%CHAPTERTITLE_VAL%>", "<%CHCHAPTER_VAL%>");

			$repl = array(LANG_SYS_SUBMIT, LANG_CP_ADDSTORY_CHAPTERTITLE, LANG_CP_ADDSTORY_CHAPTERTEXT, 
			$conf['fiction_words'], $this->makeWysiwyg(), $chapter['chtitle'], $chapter['chchapter']);

			$content = str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/editchapter_3.tmpl.php" ) );
	
			$content.="<input type='hidden' name='finish' value='{$_POST['chid']}'>";

			return $note.$this->formWrap( $content , 1, "form1" );	

		}

		if( $_POST['sid'] ) {

			$chapters = $db->select( "chtitle, chid" , "chapters" , array('chsid'=>$_POST['sid']) , "ORDER BY chorder ASC" );

			$c = 1;

			foreach( $chapters as $row ) {

				$chapters = $this->cleanText( $row );

				$c == 1 ? $sel=" selected" : $sel="";
				
				!$row['chtitle'] ? $chapter = LANG_CP_CHAPTER." {$c}" : $chapter = $row['chtitle']; 

				$opt.="<option value='{$row['chid']}'{$sel}>{$chapter}</option>";

				$c++;

			}

			$file = $this->grabFile( $this->skin."controlpanel/editchapter_2.tmpl.php" );

			$orig = array("<%CHAPTERLIST%>" , "<%GO%>");

			$repl = array( $opt , LANG_SYS_SUBMIT);

		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."controlpanel/editchapter_1.tmpl.php" );

			$fa = DBPRE;

			$stories = $db->select( "sid, stitle, cname" , "stories LEFT JOIN {$fa}categories ON cid=scid" , 
			array( 'suid'=>$this->user['uid'] ) , "ORDER BY cname ASC, stitle ASC" );
			
			foreach( $stories as $row ) {

				$stories = $this->cleanText( $row );

				$opt.="<option value='{$row['sid']}'>{$row['cname']} {$conf['sep_misc']} {$row['stitle']}</option>";

			}

			$orig = array("<%STORYLIST%>" , "<%GO%>");

			$repl = array( $opt , LANG_SYS_SUBMIT);
		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) );

	}

// ##### DELETE REVIEWS ##### //

	function deleteReviews() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][3]['sub'][0];
		$this->crumb[] = $lang['nav']['cpanel'][3]['sub'][0];

		if( $_POST ) {

			$story = $db->select( "rsid" , "reviews" , array('rid'=>$_POST['rid']) );

			if( $story ) {

				$check = $db->select( "suid" , "stories" , array('sid'=>$story[0]['rsid']) );

				if( $this->user['uid'] == $check[0]['suid'] ) {

					if( $db->delete( "reviews" , array('rid'=>$_POST['rid'] ) ) )

						$note = LANG_CP_DELREVIEW_SUCC1;

					else 

						$note = LANG_CP_DELREVIEW_ERR1;

				} else $note = LANG_CP_DELREVIEW_ERR1;

			} else $note = LANG_CP_DELREVIEW_ERR1;
			
		}

		$orig = array("<%REVIEWNO%>" , "<%GO%>" , "<%AREYOUSURE%>");

		$repl = array(LANG_CP_DELREVIEW_REVIEWNUMBER, LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE);

		return $note.$this->formWrap(str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/deletereviews.tmpl.php")));	

	}

// ##### DELETE FAVORITE ##### //

	function deleteFavorite() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][2]['sub'][0];
		$this->crumb[] = $lang['nav']['cpanel'][2]['sub'][0];

		$uid = $this->user['uid'];

		if( $_POST['favs'] ) {

			$orig = explode( "|" , $_POST['orig'] );

			foreach( $orig as $is )  if( !in_array( $is , $_POST['favs'] ) ) $keep[] = $is;

			$keep = @implode( "|" , $keep );

			$db->update( "users" , array('ufavorites'=>$keep ), array('uid'=>$uid) );

			$note = LANG_CP_DELFAVORITES_SUCC1;
			

		}

		$favs = $db->select( "ufavorites" , "users" , array('uid'=>$uid) );

		if( $favs[0]['ufavorites'] ) {

			$favs = explode( "|" , $favs[0]['ufavorites'] );

			$fa = DBPRE;

			foreach( $favs as $is ) $where[] = "sid={$is}";

			$where = implode( " OR " , $where ) . " AND chapp=1";

			$stories = $db->select( "stitle, uname, uid, cname, cid, sid" , "stories LEFT JOIN {$fa}users ON uid=suid 
			LEFT JOIN {$fa}categories ON cid=scid LEFT JOIN {$fa}chapters ON chsid=sid", $where , 
			"GROUP BY stitle, uname, uid ORDER BY stitle ASC" );

			foreach( $stories as $row ) {

				$row = $this->cleanText( $row );

				$row['srating'] == 4 ? $ch = " onClick='return confirm(\"".LANG_FIC_NC17."\")'" : $ch = "";

				$fp = $lang['sys']['nav']['fiction'];

				$title = "<a href='index.php?go={$fp}&story={$row['sid']}' class='title'{$ch}>{$row['stitle']}</a>";

				$name = "<a href='index.php?go={$lang['sys']['nav']['search']}&author={$row['uid']}'>{$row['uname']}</a>";

				$cat = "<a href='index.php?go={$fp}&category={$row['cid']}'>{$row['cname']}</a>";
				
				$check = "<input type='checkbox' name='favs[]' value='{$row['sid']}'>";

				$favorites.="<tr><td width='25%'>{$check}</td><td>{$title}</td><td>{$name}</td><td>{$cat}</td></tr>";
			}

			$favorites.="<input type='hidden' name='orig' value='{$favs[0]['ufavorites']}'>";

		} else 	$favorites = "<tr><td>".LANG_CP_DELFAVORITES_ERR1."</td></tr>";

		$orig = array("<%FAVORITES%>" , "<%GO%>" , "<%AREYOUSURE%>");

		$repl = array($favorites, LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE);

		return $note.$this->formWrap( 
		str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/deletefavorites.tmpl.php" ) ) );	
	}

// ##### ORDER CHAPTERS ##### //

	function orderChapters() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][5];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][5];

		if( $_POST['chid'] ) {

			if( count( array_unique( $_POST['chid'] ) ) != $_POST['count'] ) $note = LANG_CP_ODERCHAPTER_ERR1;

			else {

				foreach( $_POST['chid'] as $key=>$is ) 

					$db->update( "chapters" , array('chorder'=>$is) , array('chid'=>$key) );

				
	
				$note = LANG_CP_ODERCHAPTER_SUCC1;

			}

			$_POST['sid'] = $_POST['chsid'];

		}

		if( $_POST['sid'] ) {

			$fa = DBPRE;

			$chapters = $db->select( "chid, chtitle, chorder" , "chapters" , array( 'chsid'=>$_POST['sid'] ) , 
			"ORDER BY chorder ASC" );

			$count = count( $chapters )+1;

			$c = 1;
			
			foreach( $chapters as $row ) {

				$stories = $this->cleanText( $row );

				for( $i=1 ; $i < $count ; $i++ ) { 

					$i == $row['chorder'] ? $sel = " selected class='hl'" : $sel="";

					$opt.= "<option value='{$i}'{$sel}>{$i}</option>";

				}

				!$row['chtitle'] ? $row['chtitle'] = LANG_CP_CHAPTER . $c : $row['chtitle'] = $row['chtitle'];

				$sel = "<select name='chid[{$row['chid']}]' style='width:40'>{$opt}</select>";

				$chapterlist[] = "{$sel} {$row['chtitle']}";

				$opt="";

				$c++;

			}

			$c = $count - 1;

			$file = $this->grabFile( $this->skin."controlpanel/orderchapters_2.tmpl.php" );

			$file.= "<input type='hidden' name='count' value='{$c}'><input type='hidden' name='chsid' value='{$_POST['sid']}'>";

			$orig = array("<%CHAPTERLIST%>" , "<%GO%>");

			$repl = array( implode( "<br>" , $chapterlist ) , LANG_SYS_SUBMIT);			

		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."controlpanel/orderchapters_1.tmpl.php" );

			$fa = DBPRE;

			$stories = $db->select( "sid, stitle, cname" , "stories LEFT JOIN {$fa}categories ON cid=scid" , 
			array( 'suid'=>$this->user['uid'] ) , "ORDER BY cname ASC, stitle ASC" );
			
			foreach( $stories as $row ) {

				$stories = $this->cleanText( $row );

				$opt.="<option value='{$row['sid']}'>{$row['cname']} {$conf['sep_misc']} {$row['stitle']}</option>";

			}

			$orig = array("<%STORYLIST%>" , "<%GO%>");

			$repl = array( $opt , LANG_SYS_SUBMIT);
		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) );

	}

// ##### DELETE CHAPTER ##### //

	function deleteChapter() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][4];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][4];

		if( $_POST['chid'] ) {

		// STORY ID

			$id = $db->select( "chsid, chorder" , "chapters" , array('chid'=>$_POST['chid']) );

			$id = $id[0];

		// DELETE THE CHAPTER

			$db->delete( "chapters" , array('chid'=>$_POST['chid']) );

		// DELETE ANY ARCHIVE FILE

			@unlink( "archive/".$id['chsid']."_".$_POST['chid'].".txt" );

		// REORDER THE REMAINING CHAPTERS

			$reorder = $db->select( "chid" , "chapters" , array('chsid'=>$id['chsid']) , "ORDER BY chorder ASC" );

			$c = 1;
	
			foreach( $reorder as $row ) {

				$db->update( "chapters" , array('chorder'=>$c) , array('chid'=>$row['chid']) );

				$c++;

			}

			$note = LANG_CP_DELCHAPTER_SUCC1;

			$_POST['sid'] = $id['chsid'];

		}

		if( $_POST['sid'] ) {

			$chapters = $db->select( "chtitle, chid" , "chapters" , array('chsid'=>$_POST['sid']) , "ORDER BY chorder ASC" );

			if( count( $chapters ) == 1 ) { 

				$note = LANG_CP_DELCHAPTER_ERR1;

				$_POST = "";

			} else {

				$c = 1;

				foreach( $chapters as $row ) {

					$chapters = $this->cleanText( $row );

					$c == 1 ? $sel=" selected" : $sel="";
				
					!$row['chtitle'] ? $chapter = LANG_CP_CHAPTER." {$c}" : $chapter = $row['chtitle']; 

					$opt.="<option value='{$row['chid']}'{$sel}>{$chapter}</option>";

					$c++;

				}

				$file = $this->grabFile( $this->skin."controlpanel/deletechapter_2.tmpl.php" );

				$orig = array("<%CHAPTERLIST%>" , "<%GO%>", "<%AREYOUSURE%>");

				$repl = array( $opt , LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE);

			}

		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."controlpanel/deletechapter_1.tmpl.php" );

			$fa = DBPRE;

			$stories = $db->select( "sid, stitle, cname" , "stories LEFT JOIN {$fa}categories ON cid=scid" , 
			array( 'suid'=>$this->user['uid'] ) , "ORDER BY cname ASC, stitle ASC" );
			
			foreach( $stories as $row ) {

				$stories = $this->cleanText( $row );

				$opt.="<option value='{$row['sid']}'>{$row['cname']} {$conf['sep_misc']} {$row['stitle']}</option>";

			}

			$orig = array("<%STORYLIST%>" , "<%GO%>");

			$repl = array( $opt , LANG_SYS_SUBMIT);

		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) );

	}

// ##### ADD CHAPTER ##### //

	function addChapter() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][3];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][3];

		if( $_POST['chsid'] ) {

			if( !$_POST['chchapter'] && !$_FILES['upload']['name'] ) $note = LANG_CP_ADDCHAPTER_ERR1;

			else {
				if( $_FILES['upload']['name'] ) {

					$ext = end( explode( "." , $_FILES['upload']['name'] ) );

					$types = explode( " " , $conf['fiction_types'] );

					if( !in_array( $ext , $types ) ) {

						$note = LANG_CP_ADDSTORY_ERR4;
				
						unlink( $_FILES['upload']['tmp_name'] );

					} else {
				
						$chapter = trim( $this->grabFile( $_FILES['upload']['tmp_name'] ) );

						if( count( explode( " " , $chapter ) ) > $conf['fiction_words'] ) { 

							$note = LANG_CP_ADDSTORY_ERR5;

							unlink( $_FILES['upload']['tmp_name'] );

						}
					}
					

				} else $chapter = $_POST['chchapter'];

				if( !$note ) {

					$story = $db->select( "scid" , "stories", array('sid'=>$_POST['chsid']) );

					$cat = $db->select( "capp" , "categories" , array('cid'=>$story[0]['scid']) );

					$cat[0]['capp'] == 1 ? $app = 0 : $app = 1;

					$order = $db->select( "MAX(chorder) AS ord" , "chapters" , array('chsid'=>$_POST['chsid']) );

					$order = $order[0]['ord']+1;

					$chap['chsid'] = $_POST['chsid'];
					$chap['chtitle'] = $_POST['chtitle'];
					$chap['chchapter'] = $chapter;
					$chap['chapp'] = $app;
					$chap['chpdate'] = date("Y-m-d H:i:s");
					$chap['chorder'] = $order;
					$chap['chwords'] = count( explode( " " , $chapter ) );

					$db->insert( "chapters" , $chap );

					$note = LANG_CP_ADDCHAPTER_SUCC1;

					if( $conf['mailer_updatealert'] == 1 ) {

						$fa = DBPRE; 

						$sstory = $db->select( "stitle, uname" , 
						"stories LEFT JOIN {$fa}users ON uid=suid" , array('sid'=>$_POST['chsid']) );

						$sstory = $this->cleanText( $sstory[0] );

						$message = 
						$this->grabFile($conf['path']."language/".$this->user['ulang']."/mail/updatealert.mail.php");

						$morig = array("<%NAME%>", "<%AUTHOR%>", "<%TITLE%>", "<%URL%>", "<%FROM%>");

						$check = $db->select( "uname, uemail, ufavorites" , "users");

						foreach( $check as $is ) {

							$favs = explode( "|" , $is['ufavorites'] );

							if( in_array( $_POST['chsid'] , $favs ) ) {

					$url = "<a href='{$conf['url']}index.php?go={$lang['sys']['nav']['fiction']}&story={$_POST['chsid']}'>
					{$conf['url']}index.php?go={$lang['sys']['nav']['fiction']}&story={$_POST['chsid']}</a>";

								$mrepl = array($is['uname'], $sstory['uname'], $sstory['stitle'], $url, 
								$conf['title']);

								$message = str_replace( $morig, $mrepl , $message );

								$this->mailer( $conf['email_bot'] , $is['uemail'] , 
								$sstory['stitle'] . " Update" , $message );

							}

						}

					}

				}

			}

		}

		$fa = DBPRE;

		$stories = $db->select( "sid, stitle, cname" , "stories LEFT JOIN {$fa}categories ON cid=scid" , 
		array( 'suid'=>$this->user['uid'] ) , "ORDER BY cname ASC, stitle ASC" );
			
		foreach( $stories as $row ) {

			$stories = $this->cleanText( $row );

			$opt.="<option value='{$row['sid']}'>{$row['cname']} {$conf['sep_misc']} {$row['stitle']}</option>";

		}


		if( $conf['fiction_upload'] == 1 ) {

			$uploada = LANG_CP_ADDSTORY_CHAPTERUPLOAD . "<br>" . $conf['fiction_types'];
			$uploadb = "<input type='file' name='upload'>";

		}
		
		$orig = array("<%STORYLIST%>" , "<%GO%>", "<%CHAPTERTITLE%>", "<%CHAPTERTEXT%>" , "<%MAX%>", "<%CHAPUPLOADA%>",
		"<%CHAPUPLOADB%>", "<%WYSIWYG%>");

		$repl = array($opt, LANG_SYS_SUBMIT, LANG_CP_ADDSTORY_CHAPTERTITLE, LANG_CP_ADDSTORY_CHAPTERTEXT, $conf['fiction_words'],
		$uploada, $uploadb, $this->makeWysiwyg());

		return $note.$this->formWrap( 
		str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/addchapter.tmpl.php" ) ) , 1, "form1" );		
	}

// ##### DELETE STORY ##### //

	function deleteStory() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][2];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][2];

		if( $_POST ) {

			$db->delete( "reviews" , array( 'r_sid'=>$_POST['sid'] ) );

			$db->delete( "chapters" , array('chsid'=>$_POST['sid'] ) );

			$db->delete( "stories" , array( 'sid'=>$_POST['sid'] ) );

			$note = LANG_CP_DELSTORY_SUCC1;

		}

		$fa = DBPRE;

		$stories = $db->select( "sid, stitle, cname" , "stories LEFT JOIN {$fa}categories ON cid=scid" , 
		array( 'suid'=>$this->user['uid'] ) , "ORDER BY cname ASC, stitle ASC" );
			
		foreach( $stories as $row ) {

			$stories = $this->cleanText( $row );

			$opt.="<option value='{$row['sid']}'>{$row['cname']} {$conf['sep_misc']} {$row['stitle']}</option>";

		}
		
		$orig = array("<%STORYLIST%>" , "<%GO%>", "<%AREYOUSURE%>", "<%WARN%>");

		$repl = array( $opt , LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE, LANG_CP_DELSTORY_WARN1);

		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/deletestory.tmpl.php") ) );

	}

// ##### EDIT STORY ##### //

	function editStory() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][1];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][1];

		if( $_POST['finish'] ) {

			if( !$_POST['stitle'] ) $note[] = LANG_CP_ADDSTORY_ERR2;

			if( !$_POST['sdesc'] ) $note[] = LANG_CP_ADDSTORY_ERR3;

			if( !$note ) {

				$check = $db->select( "capp" , "categories" , array('cid'=>$_POST['scid']) );

				if( $check[0]['capp'] == 1 ) $db->update( "chapters" , array('chapp'=>0), array('chsid'=>$_POST['finish']) ); 

				else $db->update( "chapters" , array('chapp'=>1), array('chsid'=>$_POST['finish']) );

				$story['scid'] = $_POST['scid'];
				$story['swip'] = $_POST['swip'];
				$story['stitle'] = $_POST['stitle'];
				$story['sdesc'] = $_POST['sdesc'];
				$story['srating'] = $_POST['srating'];
				$story['sgenre1'] = $_POST['sgenre1'];
				$story['sgenre2'] = $_POST['sgenre2'];
				$story['scharacter'] = $_POST['scharacter'];

				$db->update( "stories" , $story , array('suid'=>$this->user['uid'] , 'sid'=>$_POST['finish']) );

				$note[] = LANG_CP_EDITSTORY_SUCC1;

			}

			$note = implode( "<br>" , $note );
		
			$_POST['edit'] = $_POST['finish'];

		} 

		if( $_POST['edit'] ) {

			$story = $db->select( "*" , "stories" , array('sid'=>$_POST['edit']) );

			$story = $story[0];

			$categories = $db->select( "cname, cid, cparent, cpost, cchars" , "categories" , "" , "ORDER BY corder ASC" );

			$maincharacterstart = "<option value=''>".LANG_CP_ADDSTORY_MCHARGEN."</option>";

			foreach( $categories as $key=>$row ) {

				$row = $this->cleanText( $row );

				$options.= "if (chosen == \"{$row['cid']}\") {\r\n";
				
				$chars = explode( "|" , $row['cchars'] );

				$options.= "selbox.options[selbox.options.length] = new Option('".LANG_CP_ADDSTORY_MCHARGEN."','');\r\n";

				foreach( $chars as $keyb=>$is ) {

					$k = $keyb +1;

					$k == $story['scharacter'] ? $sel = " selected class='hl'" : $sel = "";

					$options.= "selbox.options[selbox.options.length] = new Option('{$is}','{$k}');\r\n";

					if( $row['cid'] == $story['scid'] ) $maincharacterstart.="<option value='{$k}'{$sel}>{$is}</option>";

				}

				$options.="}\r\n";

			}

			$categories = $this->makeParent( $categories , $story['scid'] , $this->user['gid'] );

			$file = $this->grabFile( $this->skin."controlpanel/editstory_2.tmpl.php" );

			$file.="<input type='hidden' name='finish' value='{$_POST['edit']}'>";

			$orig = array("<%STORYTITLE%>", "<%DESCRIPTION%>", "<%CATEGORY%>", "<%CATEGORYLIST%>", "<%RATING%>", "<%RATING_VAL%>",
			"<%WIP%>", "<%WIP_VAL%>", "<%GO%>", "<%STORYTITLE_VAL%>", "<%DESCRIPTION_VAL%>", "<%PRIMARYGENRE%>", 
			"<%PRIMARYGENRE_VAL%>", "<%SECONDARYGENRE%>", "<%SECONDARYGENRE_VAL%>", "<%MAINCHARACTER%>", "<%MAINCHARACTER_VAL%>", 
			"<%OPTIONS%>");

			$repl = array(LANG_CP_ADDSTORY_TITLE, LANG_CP_ADDSTORY_DESC, LANG_CP_ADDSTORY_CATEGORY, $categories, 
			LANG_CP_ADDSTORY_RATING, $this->ratingList($story['srating']), LANG_CP_ADDSTORY_WIP, 
			$this->makeChooser(LANG_CP_YES, LANG_CP_NO, 1, $story['swip']), LANG_SYS_SUBMIT, $this->cleanForm( $story['stitle']),
			$this->cleanForm( $story['sdesc'] ), LANG_CP_ADDSTORY_PGENRE , $this->genreList($story['sgenre1']) , 			LANG_CP_ADDSTORY_SGENRE , $this->genreList($story['sgenre2']), LANG_CP_ADDSTORY_MCHAR, $maincharacterstart, $options);			
		}

		if( !$_POST ) {

			$file = $this->grabFile( $this->skin."controlpanel/editstory_1.tmpl.php" );

			$fa = DBPRE;

			$stories = $db->select( "sid, stitle, cname" , "stories LEFT JOIN {$fa}categories ON cid=scid" , 
			array( 'suid'=>$this->user['uid'] ) , "ORDER BY cname ASC, stitle ASC" );
			
			foreach( $stories as $row ) {

				$stories = $this->cleanText( $row );

				$opt.="<option value='{$row['sid']}'>{$row['cname']} {$conf['sep_misc']} {$row['stitle']}</option>";

			}

			$orig = array("<%STORYLIST%>" , "<%GO%>");

			$repl = array( $opt , LANG_SYS_SUBMIT);

		}

		return $note.$this->formWrap( str_replace( $orig, $repl, $file ) , "" , "form1" );

	}

// ##### ADD STORY ##### //

	function addStory() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][1]['sub'][0];
		$this->crumb[] = $lang['nav']['cpanel'][1]['sub'][0];

		if( $_POST ) {

			if( !$_POST['chchapter'] && !$_FILES['upload']['name'] ) $note[] = LANG_CP_ADDSTORY_ERR1;

			if( !$_POST['stitle'] ) $note[] = LANG_CP_ADDSTORY_ERR2;

			if( !$_POST['sdesc'] ) $note[] = LANG_CP_ADDSTORY_ERR3;

			if( !$note ) {

				if( $_FILES['upload']['name'] ) {

					$ext = end( explode( "." , $_FILES['upload']['name'] ) );

					$types = explode( " " , $conf['fiction_types'] );

					if( !in_array( $ext , $types ) ) {

						$note[] = LANG_CP_ADDSTORY_ERR4;
				
						unlink( $_FILES['upload']['tmp_name'] );

					} else {
				
						$chapter = trim( $this->grabFile( $_FILES['upload']['tmp_name'] ) );

						if( count( explode( " " , $chapter ) ) > $conf['fiction_words'] ) { 

							$note[] = LANG_CP_ADDSTORY_ERR5;

							unlink( $_FILES['upload']['tmp_name'] );

						}
					}
					

				} else $chapter = $_POST['chchapter'];

				if( !$note ) {

					$cat = $db->select( "capp" , "categories" , array('cid'=>$_POST['scid']) );

					$cat[0]['capp'] == 1 ? $app = 0 : $app = 1;

					$story['scid'] = $_POST['scid'];
					$story['suid'] = $this->user['uid'];
					$story['swip'] = $_POST['swip'];
					$story['stitle'] = $_POST['stitle'];
					$story['sdesc'] = $_POST['sdesc'];
					$story['srating'] = $_POST['srating'];
					$story['sgenre1'] = $_POST['sgenre1'];
					$story['sgenre2'] = $_POST['sgenre2'];
					$story['scharacter'] = $_POST['scharacter'];

					$db->insert( "stories" , $story );

					$chap['chsid'] = mysql_insert_id();
					$chap['chtitle'] = $_POST['chtitle'];
					$chap['chchapter'] = $chapter;
					$chap['chapp'] = $app;
					$chap['chpdate'] = date("Y-m-d H:i:s");
					$chap['chorder'] = 1;
					$chap['chwords'] = count( explode( " " , $chapter ) );

					$db->insert( "chapters" , $chap );

					$note[] = LANG_CP_ADDSTORY_SUCC1;

				}

			}

			$note = implode( "<br>" , $note );

		}

		$categories = $db->select( "cname, cid, cparent, cpost, cchars " , "categories" , "" , "ORDER BY corder ASC" );

		$maincharacterstart = "<option value=''>".LANG_CP_ADDSTORY_MCHARGEN."</option>";

		foreach( $categories as $key=>$row ) {

			$row = $this->cleanText( $row );

			$options.= "if (chosen == \"{$row['cid']}\") {\r\n";
			
			$chars = explode( "|" , $row['cchars'] );

			$options.= "selbox.options[selbox.options.length] = new Option('".LANG_CP_ADDSTORY_MCHARGEN."','');\r\n";

			foreach( $chars as $keyb=>$is ) {

				$k = $keyb +1;

				$options.= "selbox.options[selbox.options.length] = new Option('{$is}','{$k}');\r\n";

				if( $key == 0 ) $maincharacterstart.="<option value='{$k}'>{$is}</option>";

			}

			$options.="}\r\n";

		}

		$categories = $this->makeParent( $categories , "" , $this->user['gid'] );

		if( $conf['fiction_upload'] == 1 ) {

			$uploada = LANG_CP_ADDSTORY_CHAPTERUPLOAD . "<br>" . $conf['fiction_types'];
			$uploadb = "<input type='file' name='upload'>";

		}

		$orig = array("<%STORYTITLE%>", "<%DESCRIPTION%>", "<%CATEGORY%>", "<%CATEGORYLIST%>", "<%RATING%>", "<%RATING_VAL%>",
		"<%WIP%>", "<%WIP_VAL%>", "<%CHAPTERTITLE%>", "<%MAX%>", "<%GO%>", "<%CHAPTERTEXT%>", "<%CHAPUPLOADA%>", "<%CHAPUPLOADB%>",
		"<%PRIMARYGENRE%>", "<%PRIMARYGENRE_VAL%>", "<%SECONDARYGENRE%>", "<%SECONDARYGENRE_VAL%>", "<%WYSIWYG%>",  
		"<%MAINCHARACTER%>", "<%MAINCHARACTER_VAL%>", "<%OPTIONS%>");

		$repl = array(LANG_CP_ADDSTORY_TITLE, LANG_CP_ADDSTORY_DESC, LANG_CP_ADDSTORY_CATEGORY, $categories, LANG_CP_ADDSTORY_RATING,
		$this->ratingList(), LANG_CP_ADDSTORY_WIP, $this->makeChooser(LANG_CP_YES,LANG_CP_NO,1), LANG_CP_ADDSTORY_CHAPTERTITLE,
		$conf['fiction_words'] , LANG_SYS_SUBMIT, LANG_CP_ADDSTORY_CHAPTERTEXT, $uploada, $uploadb, LANG_CP_ADDSTORY_PGENRE , 
		$this->genreList() , LANG_CP_ADDSTORY_SGENRE , $this->genreList(), $this->makeWysiwyg() , LANG_CP_ADDSTORY_MCHAR, 
		$maincharacterstart, $options);

		return $note.$this->formWrap( 
		str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/addstory.tmpl.php" ) ) , 1, "form1" );

	}

	function makeWysiwyg() {
		
		global $conf;

		$html = explode( "><" , $conf['allowed_html'] );

		foreach( $html as $is ) {

			$is = str_replace( array("<",">") , array("","") , $is );

			$wys.="<input type='button' name='{$is}' value='{$is}' style='width:50'
			onclick=\"wrapSelection(document.form1.chchapter,'<{$is}>','</{$is}>');\"'>";

		}

		$wys.=" <input type='button' value='preview' style='width:60' 
		onClick='document.getElementById(\"preview\").innerHTML=breaker(document.form1.chchapter.value)'>"; 

		return $wys;

	}

	function genreList($curr=" ") {

		global $conf;

		include( $conf['path']."language/".$this->user['ulang']."/fiction.lang.php" );

		foreach( $ficgenre as $key=>$is ) {

			$curr == $key ? $sel = " selected class='hl'" : $sel = "";

			$opt.="<option value='{$key}'{$sel}>{$is}</option>";
			

		}

		return $opt;

	}

	function ratingList($curr=" ") {

		global $conf;

		include( $conf['path']."language/".$this->user['ulang']."/fiction.lang.php" );

		foreach( $ficrating as $key=>$is ) {

			$curr == $key ? $sel = " selected class='hl'" : $sel = "";

			$opt.="<option value='{$key}'{$sel}>{$is} {$ficratingdesc[$key]}</option>";
			

		}

		return $opt;

	}

	function profile() {

		global $db, $conf, $lang;

		$this->title[] = $lang['nav']['cpanel'][0]['sub'][0];
		$this->crumb[] = $lang['nav']['cpanel'][0]['sub'][0];

		if( $_POST ) {

			$l = $conf['penname_length'];
			$p = $conf['password_length'];

			if( !$this->eregCheck( $_POST['uname'] , "^[A-Z0-9 ]{3,$l}$") ) $note[] = LANG_CP_ERR3;

			if( !$this->eregCheck( $_POST['uemail'] , 
			"^[a-z0-9]+([_\\.-][a-z0-9]+)*" ."@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$") ) $note[] = LANG_CP_U_ERR3;

			if( $this->eregCheck( $_POST['upass'] , "^[A-Z0-9]{3,$p}$") ) { 

				$_POST['upass'] = crypt( $_POST['upass'] , $conf['salt'] );

			} else { 

				unset($_POST['upass']);

			}

			$check = $db->select( "uname" , "users" , "uname='{$_POST['uname']}' AND uid != {$this->user['uid']}" );

			if( $check ) $note[] = LANG_CP_U_ERR1;

			if( !$note ) {

				if( $_FILES['uavatarb']['name'] ) {

					$size = getImageSize( $_FILES['uavatarb']['tmp_name'] );

					if( $size[0] <= $conf['avatar_width'] && $size[1] <= $conf['avatar_height'] ) {

						$ext = explode( "." , $_FILES['uavatarb']['name'] );

				copy( $_FILES['uavatarb']['tmp_name'], $conf['path']."avatars/upload/".$this->user['uid'].".".end($ext));

						$_POST['uavatar'] = "upload/{$this->user['uid']}.".end($ext);

					}

					unset( $_FILES );

				} else {

					if( !strstr( $_POST['uavatara'] , "upload" ) ) $_POST['uavatar'] = "site/{$_POST['uavatara']}";

				}

				unset( $_POST['uavatara'] );

				$db->update( "users" , $_POST , array('uid'=>$this->user['uid']) );

				$note = LANG_CP_U_SUCC1;

			} else $note = implode( "<br>" , $note );

		}

		$fa = DBPRE;

		$account = $db->select( "*" , "users LEFT JOIN {$fa}groups ON gid=ugroup" , array('uid'=>$this->user['uid']) );

		$account = $account[0];

		$group = "<font color='{$account['gcolor']}'>".$this->cleanText($account['gname'])."</font>";

		$language = $this->fileList($conf['path']."language/", array(".","..","index.php") , $account['ulang'], "langname");

		$skin = $this->fileList($conf['path']."skins/", array(".","..","index.php") , $account['uskin'], "skinname");

		!$account['uavatar'] ? $curr = "site/None.gif" : $curr = $account['uavatar'];

		$img = "<img src='avatars/{$curr}' name='img'>";
		
		if( strstr( $curr, "upload" ) ) $avatars ="<option value='{$account['uavatar']}' selected class='hl'>Current";

		$avatars.= $this->fileList($conf['path']."avatars/site/", array(".","..","index.php") , str_replace( "site/" , "" , $curr ) );
				
		$file.="<input type='hidden' name='finish' value='{$_POST['edit']}'>";

		$orig = array("<%PENNAME%>", "<%PENNAME_VAL%>", "<%PASSWORD%>", "<%EMAIL%>", "<%EMAIL_VAL%>", "<%LANGUAGE%>",
		"<%LANGUAGE_VAL%>", "<%SKIN%>", "<%SKIN_VAL%>", "<%BIO%>", "<%BIO_VAL%>", "<%AVATAR%>", "<%AVATAR_VAL%>",
		"<%IMGP%>", "<%GROUP%>", "<%GROUP_VAL%>", "<%IP%>", "<%IP_VAL%>", "<%REGISTERED%>", "<%REGISTERED_VAL%>","<%GO%>");

		$repl = array(LANG_CP_U_PENNAME, $account['uname'] , LANG_CP_U_PASSWORD , LANG_CP_U_EMAIL, $account['uemail'],
		LANG_CP_U_LANGUAGE, $language, LANG_CP_U_SKIN, $skin, LANG_CP_U_BIO, $this->cleanForm( $account['ubio'] ),
		$img, $avatars, $conf['url']."avatars/site/", LANG_CP_U_GROUP, $group, LANG_CP_U_IP, $account['uip'], LANG_CP_U_REGISTERED, 
		$this->showTime($conf['time_format'] , $account['ustart']), LANG_SYS_SUBMIT);


		return $note.$this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/profile.tmpl.php" )), 1);

	}

	function logout() {

		global $conf;

		$cookie = $conf['cookie']."user";
	
		setcookie( $cookie , "" , time()-500000 );

		header( "Location:index.php" );

	}

	function login() {

		global $db, $conf, $lang;

		if( $_POST ) {

			if( $_POST['forgotten'] == 1 ) {

				$check = $db->select( "uname" , "users" , array('uemail'=>$_POST['u_email']) );

				if( $check ) {

					for( $i=0 ; $i<5 ; $i++ ) $a[] = rand(0,9);

					$pass = substr($check[0]['uname'],0,3).implode( "" , $a );

					$password = crypt( $pass , $conf['salt'] );

					$db->update( "users" , array('upass'=>$password) , array('uemail'=>$_POST['u_email']) );

					$orig = array("<%NAME%>" , "<%PASSWORD%>" , "<%FROM%>");

					$repl = array( $check[0]['uname'], $pass , $conf['title'] );

					$message = 
					$this->grabFile( $conf['path']."language/".$this->user['ulang']."/mail/forgottenpassword.mail.php" );

					$message = str_replace( $orig, $repl , $message );

					$this->mailer($conf['email_bot'], $_POST['u_email'] , LANG_CP_FORGOTTEN_SUBJECT , $message  );

					$note = LANG_CP_FORGOTTEN_SUCCESS;

				} else $note = LANG_CP_ERR6;

			} else {

				$pass = crypt( $_POST['u_pass'] , $conf['salt'] );

				$fa = DBPRE;

				$check = $db->select( "uid, uname, ulang, uskin, gid, gname, upass, uemail", 
				"users LEFT JOIN {$fa}groups ON ugroup=gid", array('uemail'=>$_POST['u_email'], 'upass'=>$pass), 
				"GROUP BY uid, uname, ulang, uskin, uemail, upass, gid, gname LIMIT 0,1");	

				if( $check ) {

					$cookie = $conf['cookie']."user";

					$user = serialize( $check[0] );

					setcookie( $cookie, $user , 0 , $conf['cookie_path'] , $conf['cookie_domain'] );

					header("Location:index.php?go=".$_GET['go']); 
	
					die();


				} else $note = LANG_CP_ERR1;

			}

		} 

		$orig = array("<%EMAIL%>", "<%PASSWORD%>", "<%GO%>", "<%REGISTER%>", "<%FORGOTTEN%>" , "<%FORGOTTENB%>");

		$repl = array(LANG_CP_EMAIL, LANG_CP_PASSWORD, LANG_SYS_SUBMIT, LANG_CP_REGISTER_LINK, LANG_CP_FORGOTTEN, LANG_CP_FORGOTTEN2 );

		$content = str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/login.tmpl.php" ));

		return $note.$this->formWrap( $content );
	}

	function register() {

		global $db, $conf;

		if( $_POST ) {

			$check = $db->select( "uid" , "users" , "uemail='{$_POST['u_email']}' OR uname='{$_POST['u_name']}'" );

			if( $check ) $note = LANG_CP_ERR2;

			else {

				$_POST['u_name'] = trim( $_POST['u_name'] );

				$l = $conf['penname_length'];

				if( !$this->eregCheck( $_POST['u_name'] , "^[A-Z0-9 ]{3,$l}$") ) 

					$note[] = LANG_CP_ERR3;

				if( !$_POST['agree'] ) 

					$note[] = LANG_CP_ERR4;

				if( !$_POST['u_email'] )

					$note[] = LANG_CP_ERR5;

				if( $note ) $note = implode( "<br>" , $note );

				else {

					for( $i=0 ; $i<5 ; $i++ ) $a[] = rand(0,9);

					$pass = substr($_POST['u_name'],0,3).implode( "" , $a );

					$insert['upass'] = crypt( $pass , $conf['salt'] );
					$insert['ugroup'] = $conf['default_group'];
					$insert['uskin'] = $conf['default_skin'];
					$insert['ulang'] = $conf['default_lang'];
					$insert['ustart'] = date("Y-m-d H:i:s");
					$insert['uip'] = getenv("REMOTE_ADDR");
					$insert['uname'] = $_POST['u_name'];
					$insert['uemail'] = $_POST['u_email'];

					$db->insert( "users" , $insert );

					$note = LANG_CP_REGISTER_SUCCESS;

					$message = 
					$this->grabFile( $conf['path']."language/".$this->user['ulang']."/mail/registration.mail.php" );

					$orig = array("<%NAME%>", "<%TITLE%>", "<%EMAIL%>", "<%PASSWORD%>", "<%FROM%>");

					$repl = array($insert['uname'], $conf['title'], $insert['uemail'], $pass, $conf['title']);

					$message = str_replace( $orig, $repl , $message );

					$this->mailer( $conf['email_bot'] , $_POST['u_email'] , $conf['title']." Registration" , $message );

				}

			}

		}

		$orig = array("<%USERNAME%>", "<%EMAIL%>", "<%AGREES%>", "<%GO%>", "<%TAC%>");

		$conf['mage'] > 0 ? $agree = LANG_CP_AGREE2 : $agree = LANG_CP_AGREE1;

		$repl = array(LANG_CP_PENNAME, LANG_CP_EMAIL, $agree, LANG_SYS_SUBMIT, $this->cleanText( LANG_CP_TAC, $conf['allowed_html']) );

		$content = str_replace( $orig, $repl, $this->grabFile( $this->skin."controlpanel/register.tmpl.php" ));

		return $note.$this->formWrap( $content );

	}

	function makeNav(){

		global $lang, $conf;

		foreach( $lang['nav']['cpanel'] as $key=>$is ) {

			if( $is['sub'] ) {

				$nav[] = $is['main'];

				foreach( $is['sub'] as $are ) 

					$nav[] = "&rsaquo; <a href='{$conf['url']}index.php?go={$_GET['go']}&set={$are}'>{$are}</a>";

			} else $nav[] = "<a href='{$conf['url']}index.php?go={$_GET['go']}&set={$is['main']}'>{$is['main']}</a>";

		}

		return implode( "<br>" , $nav );

	}
}

?>