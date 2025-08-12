<?php
class fiction extends dp {

	var $user = array();
	var $title = array();
	var $crumb = array();
	var $skin;
	var $valid;
	var $ismod;
	var $moddel = array();
	var $modmove = array();

	function fiction(){
	}

// ##### DISPLAY SWITCH - WE'RE SHOWING WHAT NOW? ##### //

	function makePage() {

		global $db, $lang, $conf;

		switch( $_GET['go'] ) {

			default:								// FICTION

				if( $_GET['category'] ) 

					$content = $this->showCategory();

				elseif( $_GET['story'] ) 

					$content = $this->showStory();

				elseif( $_GET['review'] ) 

					$content = $this->makeReview();

				elseif( $_GET['reviews'] ) 

					$content = $this->showReviews();

				elseif( $_GET['add2fav'] ) 

					$content = $this->add2Fav();

				elseif( $_GET['go'] == $lang['sys']['nav']['fiction'] )

					$content = $this->showCategories();			

				else

					$content = $this->mainPage();

			break;

			case $lang['sys']['nav']['search']: $content = $this->makeSearch();	// SEARCH

			break;

			case $lang['sys']['nav']['latest']: $content = $this->makeLatest();	// LATEST

			break;


		}

		$this->title ? $this->title = implode($conf['sep_title'] , $this->title) : $this->title = "";

		$this->crumb ? $this->crumb = implode($conf['sep_crumb'] , $this->crumb) : $this->crumb = "";

		return $content;

	}

// ##### MAIN PAGE ##### //

	function mainPage() {
	
		global $db, $conf, $ficrating, $lang;

		$fa = DBPRE;

	// LATEST STORIES //

		$stories = $db->select( "stitle, sdesc, sid, srating, MAX(chpdate) AS latest, uname, uid, cname, cid, cread" , 
		"stories LEFT JOIN {$fa}chapters ON chsid=sid LEFT JOIN {$fa}users ON uid=suid LEFT JOIN {$fa}categories ON cid=scid", 
		array('chapp'=>1),
		"GROUP BY stitle, sdesc, sid, srating, swip, uname, uid ORDER BY latest DESC LIMIT 0, 5");

		$tmpl = $this->grabFile( $this->skin."mainpage_latest.tmpl.php" );

		$lforig = array("<%TITLE%>" , "<%DESCRIPTION%>", "<%AUTHOR%>" , "<%RATING%>" , "<%CATEGORY%>");

		foreach( $stories as $row ) {

			$read = explode( "|" , $row['cread'] );

			if( in_array( $this->user['gid'] , $read ) ) {

				$row = $this->cleanText( $row );

				$row['srating'] == 4 ? $ch = " onClick='return confirm(\"".LANG_FIC_NC17."\")'" : $ch = "";

				$fp = $lang['sys']['nav']['fiction'];

				$title = "<a href='index.php?go={$fp}&story={$row['sid']}' class='title'{$ch}>{$row['stitle']}</a>";

				$name = "<a href='index.php?go={$lang['sys']['nav']['search']}&author={$row['uid']}'>{$row['uname']}</a>";	

				$row['swip'] == 0 ? $alt = LANG_FIC_WIP : $alt = LANG_FIC_COMPLETE;

				$wip = "<img src='{$this->skin}images/wip_{$row['swip']}.gif' alt='{$alt}' align='middle'>";

				$cat = "<a href='index.php?go={$fp}&category={$row['cid']}'>{$row['cname']}</a>";

				$lfrepl = array($title, $row['sdesc'] , $name , $ficrating[$row['srating']] , $cat);

				$latestfics[] = str_replace( $lforig , $lfrepl , $tmpl );

			}

		}

		$latestfics = @implode( "<br>" , $latestfics );

	// STATS

		$tmpl = $this->grabFile( $this->skin."mainpage_stats.tmpl.php" );

		if( strstr( $tmpl , "<%AUTHORCOUNT%>" ) ) { 

			$authors = $db->select( "count(uid) AS count" , "users" );

			$authors = $authors[0]['count'] - 1;

		}

		if( strstr( $tmpl , "<%FICTIONCOUNT%>" ) ) $stories = $db->select( "count(sid) As count" , "stories" );

		if( strstr( $tmpl , "<%REVIEWCOUNT%>" ) ) $reviews = $db->select( "count(rid) As count" , "reviews" );

		$storig = array("<%AUTHORS%>", "<%AUTHORCOUNT%>", "<%FICTION%>", "<%FICTIONCOUNT%>", "<%REVIEWS%>", "<%REVIEWCOUNT%>");

		$strepl = array( LANG_SYS_STATS_AUTHORS , $authors, LANG_SYS_STATS_FICTION, $stories[0]['count'], LANG_SYS_STATS_REVIEWS,
		$reviews[0]['count'] );

		$stats = str_replace( $storig, $strepl, $tmpl );


	// STUFF THE MAIN TEMPLATE

		$orig = array("<%CATEGORIES%>", "<%LATESTFICS%>" , "<%STATS%>" , "<%NEWS%>" , "<%WELCOME%>", "<%LATEST%>", "<%STATISTICS%>");

		$repl = array($this->showCategories(), $latestfics, $stats, $this->makeNews($fa) , 
		$this->cleanText( LANG_SYS_WELCOME, $conf['allowed_html']), LANG_SYS_MP_LATEST, LANG_SYS_MP_STATISTICS);

		return str_replace($orig, $repl ,$this->grabFile( $this->skin."mainpage.tmpl.php" ) );

	}

// ##### MAKE NEWS ##### //

	function makeNews($fa) {

		global $db, $conf, $lang;

		if( $_POST['ncomment'] ) {

			$_POST['ncomment'] = str_replace( "|" , "" , $_POST['ncomment'] );

			$cmt = $db->select( "ncomment" , "news" , array('nid'=>$_GET['news']) );

			if( $cmt ) {

				$comment = explode( "|" , $cmt[0]['ncomment'] );

				$comment[] = "{$_POST['ncomment']} - <b>{$this->user['uname']}</b>";

			} else $comment = "{$_POST['ncomment']} - <b>{$this->user['uname']}</b>";

			$comment = implode( "|" , $comment );

			$db->update( "news" , array('ncomment'=>$comment) , array('nid'=>$_GET['news']) );

			header( "Location: " . $conf['url'] . "index.php?news=" . $_GET['news'] );

		} 

		$news = $db->select( "nid, ndate, nuid, nnews, ncomment, uid, uname" , "news LEFT JOIN {$fa}users ON uid=nuid" , "" , 
		"ORDER BY ndate DESC LIMIT 0, {$conf['latest_limit']}");
	
		$tmpl = $this->grabFile( $this->skin."mainpage_news.tmpl.php" );

		$orig = array("<%DATE%>", "<%POSTER%>", "<%NEWS%>", "<%COMMENTS%>");

		foreach( $news as $row ) {

			$row = $this->cleanText( $row , $conf['allowed_html'] );

			$date = $this->showTime( $conf['time_format_news'] , $row['ndate'] );

			$pst = "<a href='{$conf['url']}index.php?go={$lang['sys']['nav']['search']}&author={$row['uid']}'>{$row['uname']}</a>";

			$cword = "<a href='{$conf['url']}index.php?news={$row['nid']}#{$row['nid']}'>".LANG_SYS_NEWS_COMMENTS."</a>";

			if( $_GET['news'] == $row['nid'] ) { 

				if( $row['ncomment'] ) {

					$comments = explode( "|" , $row['ncomment'] );

					foreach( $comments as $is ) if( $is ) $comment[] = $is;

					$comments = "<a name='{$row['nid']}'></a>" . implode( "<p>" , $comment );

				}

				if( $this->user['uid'] != 1 ) {

					$comments.= $this->formWrap("<textarea name='ncomment'></textarea><br>
					<input type='submit' value='".LANG_SYS_NEWS_COMMENT."'>");

				}

			} elseif( $row['ncomment'] ) {

				$commes = explode( "|" , $row['ncomment'] );

				foreach( $commes as $is ) if( $is ) $comment[] = $is;

				$comments = count($comment);

			 	$comments = str_replace( "<%COMS%>" , $comments , $cword );

			} else $comments = str_replace( "<%COMS%>" , 0 , $cword );


			$repl = array( $date , $pst , $row['nnews'] , $comments );

			$mnews[] = str_replace( $orig , $repl , $tmpl );	

			$comments = "";

			$comment = "";	

		}

		return @implode( "" , $mnews);


	}

// ##### ADD TO FAVORITES ##### //

	function add2Fav() {

		global $db;

		if( $this->user['uid'] == 1 ) return LANG_FIC_A2F_ERR1;

		else {

			$favs = $db->select( "ufavorites" , "users" , array('uid'=>$this->user['uid']) );

			$fav = explode( "|" , $favs[0]['ufavorites'] );

			if( !in_array( $_GET['add2fav'] , $fav ) ) $fav[] = $_GET['add2fav'];

			foreach( $fav as $key=>$is ) if( !$is ) unset( $fav[$key] );

			$fav = implode( "|" , $fav );

			$db->update( "users" , array('ufavorites'=>$fav) , array('uid'=>$this->user['uid']) );

			header( "Location: index.php?go={$_GET['go']}&story=".$_GET['add2fav'] );

			die();

		}

	}

// ##### MAKE REVIEW ##### //

	function makeReview() {

		global $db, $conf;

		$fa = DBPRE;

		$story = $db->select( "stitle, uname, uemail" , "stories LEFT JOIN {$fa}users ON uid=suid" , 
		array('sid'=>$_GET['review']) , "GROUP BY stitle LIMIT 0,1" );

		if( $_POST['rreview'] ) {

			if( $this->user['uid'] == 1 && !$_POST['rname'] ) $_POST['rname'] = $this->user['uname'];
			$insert['rname'] = $_POST['rname'];
			$insert['rsid'] = $_GET['review'];
			$insert['rdate'] = date("Y-m-d H:i:s");
			$insert['rreview'] = $_POST['rreview'];
			$insert['ruid'] = $this->user['uid'];

			$db->insert( "reviews" , $insert );

			$note = LANG_FIC_REVIEW_SUCC1;

			if( $conf['mailer_reviewalert'] == 1 ) {

				$story = $this->cleanText( $story[0] );

				$message = $this->grabFile( $conf['path']."language/".$this->user['ulang']."/mail/reviewalert.mail.php" );

				$url = "<a href='{$conf['url']}index.php?go={$_GET['go']}&reviews={$_GET['review']}'>
				{$conf['url']}index.php?go={$_GET['go']}&reviews={$_GET['review']}</a>";

				$orig = array("<%NAME%>", "<%TITLE%>", "<%URL%>", "<%FROM%>");

				$repl = array($story['uname'], $story['stitle'], $url, $conf['title']);

				$message = str_replace( $orig, $repl, $message );

				$this->mailer( $conf['email_bot'] , $story['uemail'], $story['stitle']." Review" , $message );

			} 

		}

		if( $story ) {

			$title = $this->cleanText($story[0]['stitle']);

			$this->crumb[] = LANG_FIC_RREVIEW . " <a href='index.php?go={$_GET['go']}&story={$_GET['review']}'>{$title}</a>";

			$this->title[] = LANG_FIC_RREVIEW . " " . $title;

			$this->user['uid'] == 1 ? $inp = "<input type='text' name='rname'>" : $inp = $this->user['uname'];

			$orig = array("<%RNAMEA%>","<%RNAMEB%>","<%GO%>");

			$repl = array( LANG_FIC_REVIEWNAME , $inp , LANG_SYS_SUBMIT);

		} else $note = LANG_FIC_REVIEW_ERR1;

		return $note.$this->formWrap( str_replace( $orig, $repl , $this->grabFile($this->skin."review.tmpl.php") ) );
	}

// ##### READ REVIEWS ##### //

	function showReviews() {

		global $db, $conf, $lang;

		!$_GET['page'] ? $page = 0 : $page = ($_GET['page'] * $conf['ppage']) - ($conf['ppage']);

		$count = $db->select( "COUNT(rid) AS count" , "reviews" , array('rsid'=>$_GET['reviews']) );

		if( $count[0]['count'] > 0 ) {

			$fa = DBPRE;

			$this->title[] = LANG_FIC_RREVIEWS;
			
			$this->crumb[] = LANG_FIC_RREVIEWS;

			$revs = $db->select( "rid, ruid, rdate, rreview, rname, uname, uid, stitle,cmod" , 
			"reviews LEFT JOIN {$fa}users ON uid=ruid LEFT JOIN {$fa}stories ON sid=rsid 
			LEFT JOIN {$fa}categories ON cid=scid", array('rsid'=>$_GET['reviews']) ,
			"GROUP BY ruid, rdate, rreview, rname ORDER BY rid DESC LIMIT {$page}, ".$conf['ppage'] );

			$total = ceil( $count[0]['count'] / $conf['ppage'] );

			if( $total > 1 ) {

				!$_GET['page'] ? $page = 1 : $page = $_GET['page'];

				for( $i = 1 ; $i<($total+1) ; $i++ ) {

					$i == $page ? $sel=" selected class='hl'" : $sel="";

					$opt.="<option value='{$i}'{$sel}>".LANG_FIC_P2PPAGE."{$i}</option>";
				}

				$gen = "document.location='index.php?go={$_GET['go']}&reviews={$_GET['reviews']}&page=";

				$page = "<select onChange=\"{$gen}'+this.value\">{$opt}</select>";

			} else $page="";

			$tmpl = $this->grabFile($this->skin."reviews.tmpl.php");

			$find = array("<%RNAME%>","<%RDATE%>","<%RCOMMENT%>", "<%RID%>");

			foreach( $revs as $row ) {

				$rrev = $this->cleanText( $row['rreview'] , $conf['allowed_html'] );

				$row = $this->cleanText( $row );

				$row['ruid'] == 1 ? 
				$name = $row['rname'] : 
				$name = "<a href='index.php?go={$lang['sys']['nav']['search']}&author={$row['uid']}'>{$row['uname']}</a>";
			
				$date = $this->showTime($conf['time_format'], $row['rdate']);

				$reviews.=str_replace( $find, array($name, $date, $rrev, $row['rid']) , $tmpl);

			}

			$this->crumb[] = "<a href='index.php?go={$_GET['go']}&story={$_GET['reviews']}'>{$row['stitle']}</a>";

			$this->title[] = $row['stitle'];

			$orig = array("<%PAGE%>", "<%REVIEWS%>");

			$repl = array( $page, $reviews);

			return $this->formWrap( str_replace( $orig, $repl, $this->grabFile($this->skin."listreviews.tmpl.php") ) );

		} else return LANG_FIC_REVIEW_ERR2;

	}

// ##### COMPILE THE CATEGORY CONTENT ##### //

	function showCategory() {

		global $db;

		$category = $db->select( "cname, cimg, cmod, cchars" , "categories" , array('cid'=>$_GET['category'] ) );

		$img = $this->makeImg( $this->skin."images/cats/".$category[0]['cimg'] , $this->cleanText($category[0]['cname']) );

		$subcats = $this->showCategories();

		$moderators = $this->showModerators($category[0]['cmod']);

		$sorter = $this->makeSorter();

		$genre = $this->makeGenre();

		$character = $this->makeCharacter($category[0]['cchars']);

		$stories = $this->listStories();

		$pages = $this->page2Page();

		$orig = array("<%IMG%>", "<%MODERATORS%>", "<%SORTER%>" , "<%SUBCATS%>" , "<%PAGES%>" , "<%STORIES%>", "<%MODERATOROPTIONS%>",
		"<%GENRE%>", "<%CHARACTER%>");

		$repl = array( $img , $moderators , $sorter, $subcats, $pages , $stories, $this->modOptions(), $genre, $character);

		return str_replace( $orig , $repl , $this->grabFile( $this->skin."category.tmpl.php" ) );

	}

// ##### MODERATOR OPTIONS ##### //

	function modOptions() {

		global $db;

		if( $this->ismod == 1 ) {

			if( $_POST['modmove'] ) {

				$check = $db->select( "capp" , "categories", array('cid'=>$_POST['ncid']) );

				if( $check[0]['capp'] == 1 ) $db->update( "chapters" , array('chapp'=>0), array('chsid'=>$_POST['modmove']) );

				else $db->update( "chapters" , array('chapp'=>1), array('chsid'=>$_POST['modmove']) );

				$db->update( "stories" , array('scid'=>$_POST['ncid']) , array('sid'=>$_POST['modmove']) );	

				$location = "index.php?go={$_GET['go']}&category={$_POST['ncid']}";

				header( "Location: " . $location);

			}	

			if( $_POST['moddel'] ) { 

				$db->delete( "reviews" , array( 'r_sid'=>$_POST['moddel'] ) );

				$db->delete( "chapters" , array('chsid'=>$_POST['moddel'] ) );

				$db->delete( "stories" , array( 'sid'=>$_POST['moddel'] ) );

				$location = "index.php?go={$_GET['go']}&category={$_GET['category']}";

				header( "Location: " . $location);

			}

			if( $_GET['approve'] ) {

				$fa = DBPRE;

				if( $_POST['chap'] ) {

					foreach( $_POST['chap'] as $is ) $wh[] = "chid='{$is}'";

					$wh = @implode( " OR " , $wh );

					if( $_POST['reject'] ) {

						$db->delete( "chapters" , $wh );

						$orphan = $db->select( "sid, COUNT(chsid) AS count" , 
						"stories LEFT JOIN {$fa}chapters ON chsid=sid", "" , "GROUP BY sid HAVING count=0");

						if( $orphan ){

							foreach( $orphan as $row ) $del[] = "sid='{$row['sid']}'";

							$del = implode( " OR " , $del );

							$db->delete( "stories" , $del );

						}

					} else {

						$db->update( "chapters", array('chapp'=>1), $wh ); 

					}

					$location = "index.php?go={$_GET['go']}&category={$_GET['category']}";

					header( "Location: " . $location);

				} else {

					$fic = $db->select( "ch.chtitle, ch.chchapter, ch.chid, s.stitle" , 
					"chapters ch LEFT JOIN {$fa}stories s ON s.sid=ch.chsid" , 
					array('ch.chsid'=>$_GET['approve'], 'ch.chapp'=>0), 
					"ORDER BY ch.chorder ASC");

					if( $fic ) {

						$title = $this->cleanText( $fic[0]['stitle'] );

						foreach( $fic as $row ) {

							$row = $this->cleanText( $row );

						$chapters.="<tr>
						<td valign='top'><input type='checkbox' value='{$row['chid']}' name='chap[]' class='sm'></td>
						<td width='100%'>{$row['chtitle']}<br>{$row['chchapter']}</td></tr>";
	
						}

					$orig = array("<%FICTITLE%>", "<%CHAPTERS%>", "<%APPROVE%>", "<%REJECT%>", "<%AREYOUSURE%>");

					$repl = array($title, $chapters, LANG_FIC_MODAPPROVECHAPTER, LANG_FIC_MODREJECTCHAPTER, 
					LANG_SYS_AREYOUSURE);

					$app = $this->formWrap( 
					str_replace( $orig, $repl, $this->grabFile( $this->skin."approve.tmpl.php") ) );

					}
				
				}

			}


			$categories = $db->select( "cname, cparent, cid, cpost" , "categories" , array('cactive'=>1) , "ORDER BY corder ASC");

			$categories = $this->makeParent( $categories , "" , $this->user['gid'] );

			foreach( $this->moddel as $key=>$is ) $moddel.= "<option value='{$key}'>{$is}</option>";

			foreach( $this->modmove as $key=>$is ) $modmove.= "<option value='{$key}'>{$is}</option>";

			$orig = array("<%CATEGORIES%>", "<%MOVESTORY_VAL%>", "<%DELETESTORY_VAL%>", "<%GO%>" , "<%AREYOUSURE%>",
			"<%MOVESTORY%>", "<%DELETESTORY%>", "<%CHOOSESTORY%>", "<%APPROVE%>");

			$repl = array($categories, $moddel, $modmove, LANG_SYS_SUBMIT, LANG_SYS_AREYOUSURE, LANG_FIC_MODMOVE, 
			LANG_FIC_MODDELETE, LANG_FIC_MODCHOOSESTORY, $app);

			return $this->formWrap( str_replace( $orig, $repl, $this->grabFile( $this->skin."moderator.tmpl.php" ) ) );

		}

	}

// ##### CHARACTER SORTER ##### //

	function makeCharacter($chars=" " , $curr=" ") {

		if( !$chars ) return;

		$chars = explode( "|" , LANG_FIC_CHARACTER_ALL."|".$chars );

		foreach( $chars as $key=>$is ) {

			$key == $_GET['character'] ? $sel = " selected class='hl'" : $sel = "";

			$opt.="<option value='{$key}'{$sel}>{$is}</option>";

		}

		if( $_GET['sort'] ) $sort = "&sort={$_GET['sort']}";
		if( $_GET['genre'] ) $genre = "&genre={$_GET['genre']}";
		if( $_GET['page'] ) $page = "&page={$_GET['page']}";

		$gen = "index.php?go={$_GET['go']}&category={$_GET['category']}{$page}{$sort}{$genre}&character=";

		return "<select onChange=\"document.location='{$gen}'+ this.value\" class='mid'>{$opt}</select>";
	}

// ##### GENRE SORTER ##### //

	function makeGenre() {

		global $ficgenre;

		foreach( $ficgenre as $key=>$is ) {

			$key == $_GET['genre'] ? $sel = " selected class='hl'" : $sel = "";

			$opt.="<option value='{$key}'{$sel}>{$is}</option>";

		}

		if( $_GET['sort'] ) $sort = "&sort={$_GET['sort']}";
		if( $_GET['character'] ) $character = "&character={$_GET['character']}";
		if( $_GET['page'] ) $page = "&page={$_GET['page']}";

		$gen = "index.php?go={$_GET['go']}&category={$_GET['category']}{$page}{$sort}{$character}&genre=";

		return "<select onChange=\"document.location='{$gen}'+ this.value\" class='mid'>{$opt}</select>";
	}

// ##### SORTER ##### //

	function makeSorter() {

		global $ficsort;

		foreach( $ficsort as $key=>$is ) {

			$key == $_GET['sort'] ? $sel = " selected class='hl'" : $sel = "";

			$opt.="<option value='{$key}'{$sel}>{$is['option']}</option>";

		}

		if( $_GET['character'] ) $character = "&character={$_GET['character']}";
		if( $_GET['genre'] ) $genre = "&genre={$_GET['genre']}";
		if( $_GET['page'] ) $page = "&page={$_GET['page']}";

		$gen = "index.php?go={$_GET['go']}&category={$_GET['category']}{$page}{$genre}{$character}&sort=";

		return LANG_FIC_SORTBY . "<select onChange=\"document.location='{$gen}'+ this.value\" class='mid'>{$opt}</select>";
	}

// ##### PAGE TO PAGE ##### //

	function page2Page() {

		global $db, $conf;

		$fa = DBPRE;

		$count = $db->select( "COUNT(DISTINCT sid) AS count" , "stories LEFT JOIN {$fa}chapters ON chsid=sid", 
		array('chapp'=>1 , 'scid'=>$_GET['category']) );

		$count = $count[0]['count'];

		$total = ceil( $count / $conf['ppage'] );

		if( $total > 1 ) {

			!$_GET['page'] ? $page = 1 : $page = $_GET['page'];

			for( $i = 1 ; $i<($total+1) ; $i++ ) {

				$i == $page ? $sel = " selected class='hl'" : $sel="";

				$skpg.="<option value='{$i}'{$sel}>".LANG_FIC_P2PPAGE."{$i}</option>";

			}

			if( $_GET['character'] ) $character = "&character={$_GET['character']}";
			if( $_GET['genre'] ) $genre = "&genre={$_GET['genre']}";
			if( $_GET['sort'] ) $sort = "&sort={$_GET['sort']}";
					
			$gen = "document.location='index.php?go={$_GET['go']}&category={$_GET['category']}{$character}{$genre}{$sort}&page=";

			$next = $page+1;

			$back = $page-1;

			if( $page > 1 ) 

				$pages = "<input type='submit' onClick=\"{$gen}{$back}'\" value='".LANG_FIC_P2PBACK."' class='back'>";

			$pages.= " <select onChange=\"{$gen}'+this.value\" class='mid'>{$skpg}</select> ";

			if( $page < $total )

				$pages.= "<input type='submit' onClick=\"{$gen}{$next}'\" value='".LANG_FIC_P2PNEXT."' class='next'>";

			return $pages;

		}

		return;

	}

// ##### MODERATOR LIST AND THEIR OPTIONS ##### //

	function showModerators($mods) {

		global $db, $conf, $lang;

		$mods = explode( "|" , $mods );

		foreach( $mods as $is ) $where[] = "uid={$is}";

		$where = implode( " OR " , $where );

		$moderators = $db->select( "uname, uid" , "users" , $where , "ORDER BY uname ASC" );

		if( $moderators ) {

			$search = $lang['sys']['nav']['search'];

			foreach( $moderators as $row ) {

				$mod[] = "<a href='{$conf['url']}index.php?go={$search}&author={$row['uid']}'>{$row['uname']}</a>";

				if( $this->user['uid'] == $row['uid'] ) $this->ismod = 1;

			}

			if( $this->ismod == 1 ) {

				$fa = DBPRE;

				$fic = $db->select( "stitle, sid" , "stories LEFT JOIN {$fa}chapters ON chsid=sid" , 
				array('chapp'=>0, 'scid'=>$_GET['category']), "GROUP BY stitle, scid, sid ORDER BY stitle ASC");

				if( $fic ) {

					foreach( $fic as $row ) {

						$row = $this->cleanText( $row );

						$app[] = "<a href='{$conf['url']}index.php?go={$_GET['go']}
						&category={$_GET['category']}&approve={$row['sid']}#{$row['stitle']}'>{$row['stitle']}</a>";

					}

					$app = "<br>" . LANG_FIC_MODAPPROVE . @implode( ", " , $app );

				}

			}

			return LANG_FIC_MODERATORS . @implode( ", " , $mod ) . $app;

		}

		return;

	}

// ##### LIST LATEST STORIES ##### //

	function makeLatest() {

		global $db, $conf, $ficrating, $lang;

		$fa = DBPRE;

		$stories = $db->select( "stitle, sdesc, sid, srating, swip, shits, COUNT(DISTINCT chid) AS chaps, MIN(chpdate) AS earliest, 
		MAX(chpdate) AS latest, SUM(chwords) AS words, uname, uid, COUNT(DISTINCT rid) AS revs, cname, cid, cread" , 
		"stories LEFT JOIN {$fa}chapters ON chsid=sid LEFT JOIN {$fa}users ON uid=suid LEFT JOIN {$fa}reviews ON rsid=sid 
		LEFT JOIN {$fa}categories ON cid=scid", array('chapp'=>1) , 
		"GROUP BY stitle, sdesc, sid, srating, swip, uname, uid ORDER BY latest DESC LIMIT 0, {$conf['latest_limit']}" );

		$orig = array("<%TITLE%>","<%DESCRIPTION%>","<%AUTHOR%>", "<%RATING%>", "<%CHAPTERS%>", "<%REVIEWS%>", "<%UPLOADED%>",
		"<%UPDATED%>", "<%WIP%>" , "<%WORDS%>", "<%HITS%>", "<%CATEGORY%>");
			
		$tmpl = $this->grabFile( $this->skin."listlatest.tmpl.php" );

		foreach( $stories as $row ) {

			$read = explode( "|" , $row['cread'] );

			if( in_array( $this->user['gid'] , $read ) ) {

				$row = $this->cleanText( $row );

				$row['revs'] < 1 ? $div = 1 : $div = $row['revs'];

				$div ? $row['words'] = $row['words'] / $div : $row['words'] = $row['words'];

				$row['srating'] == 4 ? $ch = " onClick='return confirm(\"".LANG_FIC_NC17."\")'" : $ch = "";

				$fp = $lang['sys']['nav']['fiction'];

				$title = "<a href='index.php?go={$fp}&story={$row['sid']}' class='title'{$ch}>{$row['stitle']}</a>";

				$name = "<a href='index.php?go={$lang['sys']['nav']['search']}&author={$row['uid']}'>{$row['uname']}</a>";
	
				$pdate = LANG_FIC_PUBLISHED . $this->showTime( $conf['time_format'] , $row['earliest'] );

				$udate = LANG_FIC_UPDATED . $this->showTime( $conf['time_format'] , $row['latest'] );	

				$row['swip'] == 0 ? $alt = LANG_FIC_WIP : $alt = LANG_FIC_COMPLETE;

				$wip = "<img src='{$this->skin}images/wip_{$row['swip']}.gif' alt='{$alt}' align='middle'>";

				$cat = "<a href='index.php?go={$fp}&category={$row['cid']}'>{$row['cname']}</a>";

				$row['revs'] > 0 ? 
				$revs = "<a href='index.php?go={$fp}&reviews={$row['sid']}'>{$row['revs']}</a>" : 
				$revs = $row['revs'];

				$repl = array($title, $row['sdesc'] , $name , $ficrating[$row['srating']] , LANG_FIC_CHAPTERS.$row['chaps'] , 
				LANG_FIC_REVIEWS.$revs , $pdate , $udate , $wip , LANG_FIC_WORDS.$row['words'] , LANG_FIC_HITS.$row['shits'], 
				$cat);

				$cont.= "<tr><td>" . str_replace( $orig , $repl , $tmpl ) . "</td></tr>";

			}

		}

		return "<table border='0' width='100%'>{$cont}</table>"; 

	}

// ##### SEARCHING SWITCH ##### //

	function makeSearch() {

		if( $_GET['author'] ) return $this->showProfile();

		elseif( isset($_GET['letter']) ) return $this->authorList();

		else return $this->fullSearch();

	}

// ##### SEARCH : AUTHOR LIST ##### //

	function authorList() {

		global $db, $conf;

		$this->title[] = LANG_SEARCH_AUTHORS;
		$this->crumb[] = LANG_SEARCH_AUTHORS;

		$authors = $db->select( "uname, uid" , "users" , "uname LIKE '{$_GET['letter']}%' AND uid != '1'" , 
		"GROUP BY uname, uid ORDER BY uname ASC" );

		if( !$authors ) return LANG_SEARCH_ERR1 . $_GET['letter'];

		else {

			$this->title[] = $_GET['letter'];
			$this->crumb[] = $_GET['letter'];

			count( $authors ) > $conf['ppage'] ? $limit = round( count( $authors ) / $conf['cols'] ) : $limit = $conf['ppage'];

			$c = 0;

			$t = 1;

			foreach( $authors as $row ) {

				$row = $this->cleanText( $row );

				$cont.="{$t}. <a href='index.php?go={$_GET['go']}&author={$row['uid']}'>{$row['uname']}</a><br>";

				$c++;

				$t++;

				if( $c > $limit ) {

					 $cont.="</td><td valign='top'>";
					 $c = 0;
				}

			}

			return "<table border='0' width='100%'><tr><td valign='top'>{$cont}</td></tr></table>";

		}

	}

// ##### SEARCH : FULL SEARCH ##### //

	function fullSearch() {

		global $db, $conf, $lang;

		if( strlen( trim( $_POST['for'] ) ) >= 4 && $_POST['st']) {

			$time = date("H:i:s", mktime(date("H"), date("i"), date("s")-$conf['search_time'], 7, 1, 2000));

			if( $time > $_COOKIE['fsearch'] ) {

				$words = trim( $_POST['for'] );

				if( $_POST['st'] == 1 ) { 
 
					$search = $db->select( "s.sid, s.stitle, s.sdesc, c.cread", "stories s 
					LEFT JOIN ".DBPRE."chapters ch ON ch.chsid=s.sid LEFT JOIN ".DBPRE."categories c ON c.cid=s.scid", 
					"MATCH(s.stitle) AGAINST('{$words}' IN BOOLEAN MODE) AND ch.chapp=1", "GROUP BY s.stitle, s.sdesc");

				} elseif( $_POST['st'] == 2 ) {

					$search = $db->select( "s.sid, s.stitle, s.sdesc, c.cread" , "stories s 
					LEFT JOIN ".DBPRE."chapters ch ON ch.chsid=s.sid LEFT JOIN ".DBPRE."categories c ON c.cid=s.scid", 
					"MATCH(s.s_desc) AGAINST('{$words}' IN BOOLEAN MODE) AND ch.chapp=1", "GROUP BY s.stitle, s.sdesc");
	
				} else { 

					$search = $db->select( "sid, stitle, sdesc, cread" , "chapters 
					LEFT JOIN ".DBPRE."stories ON sid=chsid LEFT JOIN ".DBPRE."categories ON cid=scid", 
					"MATCH(chchapter) AGAINST('{$words}' IN BOOLEAN MODE) AND chapp=1", "GROUP BY stitle, sdesc");
		
				}


				if( $search ) {

					$c = 1;

					foreach( $search as $row ) {

						$row = $this->cleanText( $row );

						$perm = explode( "|" , $row['cread'] );

						if( in_array( $this->user['gid'] , $perm ) ) {

							$row['stitle'] == $_POST['for'] ? $hl = " class='hl'" : $hl = "";

							$fic = $lang['sys']['nav']['fiction'];
						
							$res[] = "{$c}. <a href='index.php?go={$fic}&story={$row['sid']}' {$hl}>
							{$row['stitle']}</a><br>{$row['sdesc']}";

							$c++;

						}

						if( $c == $conf['search_limit'] ) break;

					}

				}

				setcookie( "fsearch" , date("H:i:s") , time()+$conf['search_time'] );

				$result = @implode( "<br>" , $res );

				$rorig = array("<%RESULTS%>", "<%RCOUNT%>");
				$rrepl = array( $result , str_replace( "<%C%>" , count( $res ) , LANG_SEARCH_RESULTS ) );

				$result = str_replace( $rorig, $rrepl , $this->grabFile( $this->skin."results.tmpl.php" ) );

			} else $result = str_replace( "<%TIME%>" , $conf['search_time'] ,  LANG_SEARCH_ERR2);

		} 

		foreach( range( 0, 9 ) as $is ) $quick[] = "<a href='index.php?go={$_GET['go']}&letter={$is}'>{$is}</a>";
		foreach( range( "A" , "Z" ) as $is ) $quick[] = "<a href='index.php?go={$_GET['go']}&letter={$is}'>{$is}</a>";

		$quick = implode( " " , $quick );

		$orig = array("<%FOR%>","<%STITLE%>","<%SFULL%>","<%GO%>","<%QUICKNAV%>","<%RANGE%>","<%RESULT%>",
		"<%SSUMM%>","<%BOOLEAN%>", "<%BOOEXP%>");

		$repl = array(LANG_SEARCH_FOR, LANG_SEARCH_TITLE, LANG_SEARCH_CHAPTER, LANG_SYS_SUBMIT ,LANG_SEARCH_AUTHORS , $quick , $result, 
		LANG_SEARCH_SUMMARY, LANG_SEARCH_BOOLEAN, $this->cleanText(LANG_SEARCH_BOOEX));

		return $this->formWrap( str_replace( $orig, $repl , $this->grabFile($this->skin."search.tmpl.php") ) , "" , "search" );

	}

// ##### SEARCH : SHOW PROFILE ##### // 

	function showProfile() {

		global $db, $conf, $lang;

		$fa = DBPRE;

		// AUTHOR ACCOUNT INFO

		$auth = $db->select( "uname, ubio, uavatar, ustart, gname, gcolor, ufavorites" , "users LEFT JOIN {$fa}groups ON gid=ugroup" ,
		array('uid'=>$_GET['author']) , "GROUP BY uname, ubio, uavatar, ustart, gname, gcolor LIMIT 0,1");

		if( !$auth ) return LANG_FIC_ERR5;

		else {

			$biog = $this->cleanText( $auth[0]['ubio'] , $conf['allowed_html']);

			$auth = $this->cleanText( $auth[0] );

			$l = strtoupper( $auth['uname'][0] );

		// WHERE WE ARE

			$this->title[] = $l;
			$this->crumb[] = "<a href='{$conf['url']}index.php?go={$_GET['go']}&letter={$l}'>{$l}</a>";
	
			$this->title[] = $auth['uname'];
			$this->crumb[] = $auth['uname'];

		// FICTION FROM THE AUTHOR

			$stories = $db->select( "stitle, sdesc, sid, srating, swip, shits, COUNT(DISTINCT chid) AS chaps, MIN(chpdate) AS 
			earliest, MAX(chpdate) AS latest, SUM(chwords) AS words, uname, uid, COUNT(DISTINCT rid) AS revs, cname, cid, cread" , 
			"stories LEFT JOIN {$fa}chapters ON chsid=sid LEFT JOIN {$fa}users ON uid=suid LEFT JOIN {$fa}reviews ON rsid=sid 
			LEFT JOIN {$fa}categories ON cid=scid", array('chapp'=>1, 'suid'=>$_GET['author']) , 
			"GROUP BY stitle, sdesc, sid, srating, swip, uname, uid ORDER BY latest DESC");
			
			$tmpl = $this->grabFile( $this->skin."listlatest.tmpl.php" );

			if( $stories ) $written = "<table border='0' width='100%'>".$this->printStory($stories, $tmpl)."</table>"; 

		// AUTHOR FAVORITES

			$favs = explode( "|" , $auth['ufavorites'] );

			foreach( $favs as $is ) $where[] = "sid={$is}";

			$where = @implode( " OR " , $where ) . " AND chapp=1";

			$favts = $db->select( "stitle, sdesc, sid, srating, swip, shits, COUNT(DISTINCT chid) AS chaps, MIN(chpdate) AS 
			earliest, MAX(chpdate) AS latest, SUM(chwords) AS words, uname, uid, COUNT(DISTINCT rid) AS revs, cname, cid, cread" , 
			"stories LEFT JOIN {$fa}chapters ON chsid=sid LEFT JOIN {$fa}users ON uid=suid LEFT JOIN {$fa}reviews ON rsid=sid 
			LEFT JOIN {$fa}categories ON cid=scid", $where , 
			"GROUP BY stitle, sdesc, sid, srating, swip, uname, uid ORDER BY latest DESC");

			$tmpl = $this->grabFile( $this->skin."listlatest.tmpl.php" );

			if( $favts ) $favorites = "<table border='0' width='100%'>".$this->printStory($favts, $tmpl)."</table>";

		// PRETTY UP THE INFO	

			if( $auth['uavatar'] ) $avatar = "<img src='{$conf['url']}avatars/{$auth['uavatar']}'>";

			$date = $this->showTime( $conf['time_format'] , $auth['ustart'] );

			$group = "<font color='{$auth['gcolor']}'>{$auth['gname']}</font>";

		// STUFF THE TEMPLATE

			$orig = array("<%AVATAR%>", "<%AUTHORNAME%>", "<%AUTHORBIOGRAPHY%>", "<%STARTED%>", "<%STARTED_VAL%>", "<%GROUP%>",
			"<%GROUP_VAL%>", "<%WRITTEN%>", "<%FAVORITES%>");

			$repl = array($avatar, $auth['uname'], $biog, LANG_SEARCH_AUTHORSTARTED, $date, LANG_SEARCH_AUTHORGROUP,
			$group, $written, $favorites);

			return str_replace($orig, $repl, $this->grabFile($this->skin."author.tmpl.php"));

		}

	}

// ##### PRINT THE STORY DETAILS ##### //

	function printStory($stories, $tmpl) {

		global $db, $conf, $ficrating, $lang;

		foreach( $stories as $row ) {

			$read = explode( "|" , $row['cread'] );

			if( in_array( $this->user['gid'] , $read ) ) {

				$row = $this->cleanText( $row );

				$row['revs'] < 1 ? $div = 1 : $div = $row['revs'];

				$div ? $row['words'] = $row['words'] / $div : $row['words'] = $row['words'];

				$row['srating'] == 4 ? $ch = " onClick='return confirm(\"".LANG_FIC_NC17."\")'" : $ch = "";

				$fp = $lang['sys']['nav']['fiction'];

				$title = "<a href='index.php?go={$fp}&story={$row['sid']}' class='title'{$ch}>{$row['stitle']}</a>";

				$name = "<a href='index.php?go={$_GET['go']}&author={$row['uid']}'>{$row['uname']}</a>";
	
				$pdate = LANG_FIC_PUBLISHED . $this->showTime( $conf['time_format'] , $row['earliest'] );

				$udate = LANG_FIC_UPDATED . $this->showTime( $conf['time_format'] , $row['latest'] );	

				$row['swip'] == 0 ? $alt = LANG_FIC_WIP : $alt = LANG_FIC_COMPLETE;

				$wip = "<img src='{$this->skin}images/wip_{$row['swip']}.gif' alt='{$alt}' align='middle'>";

				$cat = "<a href='index.php?go={$fp}&category={$row['cid']}'>{$row['cname']}</a>";

				$row['revs'] > 0 ? 
				$revs = "<a href='index.php?go={$lang['sys']['nav']['fiction']}&reviews={$row['sid']}'>{$row['revs']}</a>" : 
				$revs = $row['revs'];

				$orig = array("<%TITLE%>","<%DESCRIPTION%>","<%AUTHOR%>", "<%RATING%>", "<%CHAPTERS%>", "<%REVIEWS%>", 
				"<%UPLOADED%>", "<%UPDATED%>", "<%WIP%>" , "<%WORDS%>", "<%HITS%>", "<%CATEGORY%>");

				$repl = array($title, $row['sdesc'] , $name , $ficrating[$row['srating']] , 
				LANG_FIC_CHAPTERS.$row['chaps'] , LANG_FIC_REVIEWS.$revs , $pdate , $udate , $wip , 
				LANG_FIC_WORDS.$row['words'] , LANG_FIC_HITS.$row['shits'], $cat);

				$list.= "<tr><td>" . str_replace( $orig , $repl , $tmpl ) . "</td></tr>";

			}

		}

		return $list;

	}

// ##### DISPLAY THE STORY ##### //

	function showStory(){

		global $db , $conf , $ficsort, $ficrating, $lang;

		!$_GET['chapter'] ? $chapter = 1 : $chapter = $_GET['chapter'];

		$story = $db->select( "s.stitle, s.sdesc, s.srating, s.swip, s.shits, u.uname, u.uid, COUNT(DISTINCT r.rid) AS revs,
		c.cname, c.cid, c.cparent, ch.chtitle , ch.chchapter, ch.chpdate" , 
		"stories s LEFT JOIN ".DBPRE."chapters ch ON ch.chsid=s.sid 
		LEFT JOIN ".DBPRE."users u ON u.uid=s.suid LEFT JOIN ".DBPRE."reviews r ON r.rsid=s.sid 
		LEFT JOIN ".DBPRE."categories c on c.cid=s.scid" , 
		array('ch.chapp'=>1, 'ch.chorder'=>$chapter , 's.sid'=>$_GET['story']) , 
		"GROUP BY s.stitle, s.sdesc, s.srating, s.swip, u.uname, u.uid, c.cname, c.cid, ch.chtitle, ch.chchapter,ch.chpdate
		LIMIT 0,1");

		if( $story ) {

			$shstory = $this->cleanText( $story[0]['chchapter'], $conf['allowed_html']);

			$story = $this->cleanText( $story[0] );

			if( !$_GET['chapter'] || $_GET['chapter'] < 2 ) {

				$hits = $story['shits'] + 1;

				$db->update( "stories" , array('shits'=>$hits) , array('sid'=>$_GET['story']) );

			}


			if( $story['cparent'] != 0 ) {

				$cats = $db->select( "cname, cparent, cid" , "categories" , array('cactive'=>1) );

				$this->navRec( $story['cid'] , $cats , $rec);

				$this->title[] = $story['stitle'];
				$this->crumb[] = $story['stitle'];

			} else {

				$this->title[] = $story['cname'];
				$this->title[] = $story['stitle'];

				$this->crumb[] = "<a href='index.php?go={$_GET['go']}&category={$story['cid']}'>{$story['cname']}</a>";
				$this->crumb[] = $story['stitle'];


			}

			$chapters = $db->select( "chtitle, chorder" , "chapters" , array('chsid'=>$_GET['story'] , 'chapp'=>1) ,
			"GROUP BY chtitle, chorder ORDER BY chorder ASC");

			if( count( $chapters ) > 1 ) {

				foreach( $chapters as $row ) {

					$row = $this->cleanText( $row );

					!$_GET['chapter'] ? $chapter = 1 : $chapter = $_GET['chapter'];

					$row['chorder'] == $chapter ? $sel=" selected class='hl'" : $sel = "";

					!$row['chtitle'] ? $title = LANG_FIC_CHAPTER . " " . $row['chorder'] : $title = $row['chtitle'];

					$opt.="<option value='{$row['chorder']}'{$sel}>{$title}</option>";

				}

				$next = $chapter+1;
				$back = $chapter-1;
				$gen = "document.location='index.php?go={$_GET['go']}&story={$_GET['story']}&chapter=";

				if( $chapter > 1 ) 
				$chapnav = "<input type='submit' onClick=\"{$gen}{$back}'\" value='".LANG_FIC_P2PBACK."' class='back'>";

				$chapnav.= " <select onChange=\"{$gen}'+this.value\" class='mid'>{$opt}</select> ";

				if( $chapter < count( $chapters ) ) 
				$chapnav.= "<input type='submit' onClick=\"{$gen}{$next}'\" value='".LANG_FIC_P2PNEXT."' class='next'>";
			}

			$orig = array("<%TITLE%>", "<%AUTHOR%>", "<%RATING%>", "<%PUBLISHED%>", "<%REVIEWS%>", "<%ADD2FAV%>","<%PRINTOUT%>",
			"<%BOOKMARK%>", "<%CHNAV%>", "<%CHAPTER%>", "<%PRINTIT%>", "<%REVIEW%>");	

			$pdate = LANG_FIC_PUBLISHED . $this->showTime( $conf['time_format'] , $story['chpdate'] );

			$author = "<a href='index.php?go={$lang['sys']['nav']['search']}&author={$story['uid']}'>{$story['uname']}</a>";

			$story['revs'] == 0 ? $revs = $story['revs'] : 
			$revs = "<a href='index.php?go={$_GET['go']}&reviews={$_GET['story']}'>{$story['revs']}</a>";

			$review = "<a href='index.php?go={$_GET['go']}&review={$_GET['story']}'>
			<img src='{$this->skin}images/review.gif' border='0' alt='".LANG_FIC_REVIEW."'></a>";

			$add2fav = "<a href='index.php?go={$_GET['go']}&add2fav={$_GET['story']}'>
			<img src='{$this->skin}images/add_to_favorites.gif' border='0' alt='".LANG_FIC_ADD2FAVORITES."'></a>";

			$print = "<a href='index.php?go={$_GET['go']}&story={$_GET['story']}&chapter={$_GET['chapter']}&print=1'>
			<img src='{$this->skin}images/print.gif' border='0' alt='".LANG_FIC_PRINT."'></a>";

			$url = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

			$bookmark = "<a href='javascript:window.external.AddFavorite(\"{$url}\",\"{$story['stitle']}\")'>
			<img src='{$this->skin}images/bookmark.gif' border='0' alt='".LANG_FIC_BOOKMARK."'></a>";
			
			$repl = array($story['stitle'], $author , $ficrating[$story['srating']] , $pdate , LANG_FIC_REVIEWS.$revs , 
			$add2fav, $print, $bookmark, $chapnav, $shstory, $printit, $review);

			$this->cid = $story['cid'];

			if( !$_GET['print'] ) return str_replace($orig, $repl ,$this->grabFile( $this->skin."story.tmpl.php" ) );

			else return str_replace($orig, $repl ,$this->grabFile( $this->skin."print.tmpl.php" ) );

		} else return LANG_FIC_ERR4;

	}

// ##### LIST THE STORIES IN A CATEGORY ##### //

	function listStories() {

		global $db , $conf , $ficsort, $ficrating, $ficgenre, $lang;

		if( $this->valid != 1 ) return;

		!$_GET['sort'] ? $sort = 0 : $sort = $_GET['sort'];

		!$_GET['genre'] ? $where = "" : $where = " AND ( sgenre1={$_GET['genre']} OR sgenre2={$_GET['genre']} )";

		!$_GET['character'] ? $whereb = "" : $whereb = " AND scharacter={$_GET['character']}";

		!$_GET['page'] ? $page = 0 : $page = ($_GET['page'] * $conf['ppage'] ) - ($conf['ppage']);

		$fa = DBPRE;

		$stories = $db->select( "stitle, sdesc, sid, srating, swip, shits, sgenre1, sgenre2, COUNT(DISTINCT chid) AS chaps, 
		MIN(chpdate) AS earliest, MAX(chpdate) AS latest, SUM(chwords) AS words, uname, uid, COUNT(DISTINCT rid) AS revs" , 
		"stories LEFT JOIN {$fa}chapters ON chsid=sid LEFT JOIN {$fa}users ON uid=suid LEFT JOIN {$fa}reviews ON rsid=sid", 
		"chapp=1 AND scid={$_GET['category']}{$where}{$whereb}" , 
		"GROUP BY stitle, sdesc, sid, srating, swip, uname, uid ORDER BY {$ficsort[$sort]['sort']} LIMIT {$page} , {$conf['ppage']}" );

		if( !$stories ) return LANG_FIC_ERR6;

		$orig = array("<%TITLE%>","<%DESCRIPTION%>","<%AUTHOR%>", "<%RATING%>", "<%CHAPTERS%>", "<%REVIEWS%>", "<%UPLOADED%>",
		"<%UPDATED%>", "<%WIP%>" , "<%WORDS%>", "<%HITS%>", "<%PRIMARYGENRE%>", "<%SECONDARYGENRE%>");
			
		$tmpl = $this->grabFile( $this->skin."liststory.tmpl.php" );

		foreach( $stories as $row ) {

			$row = $this->cleanText( $row );

			$row['revs'] < 1 ? $div = 1 : $div = $row['revs'];

			$div ? $row['words'] = $row['words'] / $div : $row['words'] = $row['words'];

			$row['srating'] == 4 ? $ch = " onClick='return confirm(\"".LANG_FIC_NC17."\")'" : $ch = "";

			$title = "<a href='index.php?go={$_GET['go']}&story={$row['sid']}' class='title'{$ch}>{$row['stitle']}</a>";

			$name = "<a href='index.php?go={$lang['sys']['nav']['search']}&author={$row['uid']}'>{$row['uname']}</a>";
	
			$pdate = LANG_FIC_PUBLISHED . $this->showTime( $conf['time_format'] , $row['earliest'] );

			$udate = LANG_FIC_UPDATED . $this->showTime( $conf['time_format'] , $row['latest'] );	

			$row['swip'] == 0 ? $alt = LANG_FIC_WIP : $alt = LANG_FIC_COMPLETE;

			$wip = "<img src='{$this->skin}images/wip_{$row['swip']}.gif' alt='{$alt}' align='middle'>";

			$row['revs'] > 0 ? 
			$revs = "<a href='index.php?go={$_GET['go']}&reviews={$row['sid']}'>{$row['revs']}</a>" : 
			$revs = $row['revs'];

			$primary = $row['sgenre1'];

			$secondary = $row['sgenre2'];

			$repl = array($title, $row['sdesc'] , $name , $ficrating[$row['srating']] , LANG_FIC_CHAPTERS.$row['chaps'] , 
			LANG_FIC_REVIEWS.$revs , $pdate , $udate , $wip , LANG_FIC_WORDS.$row['words'] , LANG_FIC_HITS.$row['shits'],
			$ficgenre[$primary], $ficgenre[$secondary]);

			$cont.= "<tr><td>" . str_replace( $orig , $repl , $tmpl ) . "</td></tr>";

			if( $this->ismod == 1 ) {

				$this->moddel[$row['sid']] = $row['stitle'];

				$this->modmove[$row['sid']] = $row['stitle'];

			}

		}

		return "<table border='0' width='100%'>{$cont}</table>"; 

	}

// ##### GRAB OUR CATEGORIES ##### //

	function showCategories() {

		global $db;

		!$_GET['category'] ? $parent = 0 : $parent = $_GET['category'];

		$categories = $db->select( "cname, cid, cdesc, cread, cimg" , "categories" , array('cparent'=>$parent, 'cactive'=>1) , 
		"GROUP BY cname, cid, cdesc, cread ORDER BY corder ASC");

		if( $parent != 0 ) {	

			// CHECK THE CATEGORY IS OPEN AND THE USER HAS READ PERMISSIONS. ALSO START THE RECURSIVE CRUMB / TITLE NAV //
							
			$check = $db->select( "cread, cactive" , "categories" , array('cid'=>$parent) );

			$read = explode( "|" , $check[0]['cread'] );

			if( !in_array( $this->user['gid'] , $read ) ) return LANG_FIC_ERR3;

			if( $check[0]['cactive'] != 1 ) return LANG_FIC_ERR2;

			$trail = $db->select( "cname, cid, cparent" , "categories" , array('cactive'=>1) , "GROUP BY cname, cid, cparent");

			$rec = array();

			$this->navRec($parent, $trail, $rec);

			$this->valid = 1;

		}

		if( !$categories && $parent == 0 ) return LANG_FIC_ERR1;

		else return $this->listCategories($categories);


	}

// ##### PRINT OUT THE CATEGORIES IN AN AESTHETIC FASHION, AKA "COLUMNS". IT WORKED FOR THE ROMANS. ##### //

	function listCategories($cats) {		

		global $conf, $lang;

		$tmpl = $this->grabFile( $this->skin."listcategory.tmpl.php" );

		$orig = array( "<%IMG%>" , "<%NAME%>" , "<%DESCRIPTION%>" );

		$c = 1;

		$w = round( 100/$conf['cols'] );

		$go = $lang['sys']['nav']['fiction'];

		foreach( $cats as $row ) {

			if( $c == 1 ) $cont.="<tr>";

			$row = $this->cleanText( $row , $conf['allowed_html'] );

			$read = explode( "|" , $row['cread'] );

			if( in_array( $this->user['gid'] , $read ) ) {  	// PRINT IF USER'S GROUP HAS READING PERMISSION

				$row['cimg'] ? $img = $this->makeImg( $this->skin."images/cats/".$row['cimg'], $row['cname'] ) : $img = "";

				$name = "<a href='{$conf['url']}index.php?go={$go}&category={$row['cid']}'>{$row['cname']}</a>";

				$repl = array( $img , $name , $row['cdesc']);
				
				$cont.="<td width='{$w}%'>".str_replace( $orig , $repl , $tmpl )."</td>";

				if( $c == $conf['cols'] ) {

					$c = 0;
					$cont.="</tr>";

				}

				$c++;

				$repl = "";

			}

			$read = "";
	
		}

		while( $c != 1 && $c < $conf['cols'] + 1 ) {			// MAKE SURE THE TABLE IS FULL

			$cont.="<td width='{$w}'>&nbsp;</td>";

			$c++;

			if( $c == $conf['cols'] + 1 ) $cont.="</tr>";

		}

		return "<table border='0' width='100%'>{$cont}</table>";

	}

// ##### A FAIRLY UNINSPIRED IMAGE EXISTENCE CHECKER ##### //

	function makeImg($img, $alt) {

		if( $size = @getImageSize( $img ) ) return "<img src='{$img}' alt='{$alt}' {$size[3]}>";

		return;

	}
	


// ##### CRUMB / TITLE CREATOR. IT COULD BE MORE ELEGENT BUT IT DOES THE JOB ##### //

	function navRec($id, $cat, $rec) {

		foreach( $cat as $row ) {

			$row = $this->cleanText( $row );

			if( $row['cid'] == $id ) { 

				$rec[] = "{$row['cid']}|{$row['cname']}";

				if( $row['cparent'] == 0 ) {

					$rec = array_reverse( $rec );

					foreach( $rec as $key=>$is ) { 

						if( $is ) {

							$is = explode( "|" , $is );
							$this->title[] = $is[1];
							$this->crumb[] = "<a href='index.php?go={$_GET['go']}&category={$is[0]}'>{$is[1]}</a>";

						}

					}

				}

				else $this->navRec( $row['cparent'] , $cat , $rec );

			}

		}

	}

}
?>