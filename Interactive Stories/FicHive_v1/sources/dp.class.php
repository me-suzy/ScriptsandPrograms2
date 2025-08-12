<?php

/* Welcome to the home of the miscellaneous little functions that
/* it would be silly to write out for each class. My laziness is
/* the stuff of legend.
*/

class dp {

	function dp(){
	}

// ##### LOCATE FILE, TURN IT INTO A STRING. UNSURPRISINGLY, THE TEMPLATE STUFFER'S BEST FRIEND. ##### //

	function grabFile($file) {

		global $conf;

		if( file_exists( $file ) ) {

			if( function_exists( "file_get_contents" ) ) return file_get_contents( $file );
			
			else return implode( "" , file($file) );

		} else {

			return LANG_SYS_FNF . str_replace( $conf['path'] , "" , $file );

		}

	}

// ##### TEXT DEBABELIZERS ##### // 

	function cleanText($text , $html=" ") {

		if( is_array( $text ) ) { 

			foreach( $text as $key=>$is ) { 

				$redo = strip_tags($is, $html);
				$redo = preg_replace('/<(.*?)>/ie', "'<'.$this->removeEvil('\\1').'>'", $redo);
				$text[$key] = stripslashes( nl2br( $redo ) );
			}

		} else { 

			$text = strip_tags($text, $html);
			$text = preg_replace('/<(.*?)>/ie', "'<'.$this->removeEvil('\\1').'>'", $text);
			$text = stripslashes( nl2br( $text ) );

		}

		return $text;

	}

	function removeEvil($tagSource) {

		$stripAttrib = "' (style|class)=\"(.*?)\"'i";

		$tagSource = stripslashes($tagSource);

		$tagSource = preg_replace($stripAttrib, '', $tagSource);

		return $tagSource;
	}

	function cleanForm($text) {

		if( is_array( $text ) ) { 

			foreach( $text as $key=>$is ) $text[$key] = htmlentities( stripslashes( $text[$key] ) , ENT_QUOTES );

		} else { 
			$text = htmlentities( stripslashes( $text ) , ENT_QUOTES );
		}

		return $text;

	}

// ##### DATE PRETTIFIER ##### //

	function showTime($time , $date=" ") {

		global $conf;

		list( $d, $t ) = explode( " " , $date );

		$d = explode( "-" , $d );

		$t = explode( ":" , $t );

		$time = date($time, mktime ($t[0]+$conf['time'],$t[1],$t[2],$d[1],$d[2],$d[0]));

		return $time;
	}

// ##### EZ FORM WRAPPER ##### //

	function formWrap($content, $type=" " , $name=" " , $target=" ") {

		global $conf;

		$name != " " ? $name = " name='{$name}'" : $name = "";

		$target != " " ? $target = " target='{$target}'" : $target = "";

		$type != " " ? $type = " enctype='multipart/form-data'" : $type = "";

		return "<form method='post' action='{$_SERVER['REQUEST_URI']}'{$type}{$name}{$target}>{$content}</form>";

	}

// ##### GENERAL EREGI SKELETON. IT WAS A GOOD IDEA AT THE TIME ##### // 

	function eregCheck($content , $eregi) {

		if( !eregi( $eregi , $content ) ) return FALSE;

		else return TRUE;

	}

// ##### RECURSIVES FOR THE CATEGORIES / SUBCATEGORIES ##### //

	function makeDepth( $category , $parent , $id) {

		global $conf;

		if( $parent != 0 ) { 

			$sep.= $conf['sep_misc'];

			foreach( $category as $row ) {

				if( $row['cid'] == $parent ) {
	
					$sep.= $this->makeDepth( $category , $row['cparent'] , $row['cid']); 

					break;

				}

			}

		}

		return $sep;

	} 

	function makeChild( $category , $parent , $id = " ", $gid=" " ) {

		foreach( $category as $key=>$row ) {
			
			if( $row['cparent'] == $parent ) {

				$id != " " && $id == $row['cid'] ? $sel = " selected class='hl'" : $sel = "";

				if( $gid != " " ) {

					$row['cpost'] ? $post = explode( "|" , $row['cpost'] ) : $post = explode( "|" , $row['cread']);

					if( in_array( $gid , $post ) ) {

						$op.= "<option value='{$row['cid']}'{$sel}> " .

						$this->makeDepth( $category , $parent , $row['cid'] )." {$row['cname']}</option>";

						$op.= $this->makeChild( $category , $row['cid'] , $id , $gid);

					}

				} else {

					$op.= "<option value='{$row['cid']}'{$sel}> " .

					$this->makeDepth( $category , $parent , $row['cid'] )." {$row['cname']}</option>";

					$op.= $this->makeChild( $category , $row['cid'] , $id);

				}

			}

		} 
	
		return $op;

	}

	function makeParent( $category , $id = " ", $gid=" ") {

		foreach( $category as $key=>$row ) {

			if( $row['cparent'] == 0 ) { 

				$id != " " && $id == $row['cid'] ? $sel = " selected class='hl'" : $sel = "";

				if( $gid != " " ) {

					$row['cpost'] ? $post = explode( "|" , $row['cpost'] ) : $post = explode( "|" , $row['cread']);

					if( in_array( $gid , $post ) ) {

						$op.= "<option value='{$row['cid']}'{$sel}>{$row['cname']}</option>";

						$op.= $this->makeChild( $category , $row['cid'] , $id, $gid);

					}
						
				} else {

					$op.= "<option value='{$row['cid']}'{$sel}>{$row['cname']}</option>";

					$op.= $this->makeChild( $category , $row['cid'] , $id);

				}

			}

		}

		return $op;
		
	}

// ##### FILES IN FOLDER ##### //

	function fileList($dir, $xclude=" " , $curr=" ", $open=" ") {

		if( !is_array($xclude) ) $xclude = array();

		if( is_dir( $dir ) ) {

			if( $dh = opendir( $dir ) ) {

				$c = 1;

				while( ( $file = readdir( $dh ) ) !== false ) {

					if( !in_array( $file , $xclude ) ) { 

						$curr == $file ? $sel = " selected class='hl'" : $sel = "";

						if( $open != " " ) {

							include( $dir.$file."/index.php" );
							
							$n = $lang[$open];


						} else {
				
							$n = explode( "." , $file );
			
							$n = str_replace( "_" , " " , $n[0] );

						}

						$opt.="<option value='{$file}'{$sel}>{$n}</option>";

						$c++;

					}

				}

				closedir($dh);

				return $opt;
			}

		} 

		return;

	}


// ##### YES / NO SELECTION ##### //

	function makeChooser($yes, $no, $type, $curr=" ", $name=" ") {

		$choose = array( $no, $yes );

		foreach( $choose as $key=>$is ) {

			if( $type == 1 ) {
				
				$key == $curr ? $sel = " selected class='hl'" : $sel = "";

				$opt.="<option value='{$key}'{$sel}>{$is}</option>";

			} else {

				$key == $curr ? $sel = " checked" : $sel = "";

				$opt.="<input type='radio' name='{$name}' value='{$key}'{$sel} class='sm'> {$is} ";

			}
	
		}

		return $opt;

	}

// ##### MAILER ##### //

	function mailer($from_email, $to_email, $subject, $message ) {

		global $conf;

  		$headers .= "MIME-Version: 1.0\r\n";
  		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
  		$headers .= "X-Priority: 1\r\n";
  		// $headers .= "X-MSMail-Priority: High\r\n";
  		// $headers .= "X-Mailer: PHP\r\n";
  		$headers .= "From: \"".$conf['title']."\" <".$from_email.">\r\n";
  		$headers .= "Reply-To: \"".$conf['title']."\" <".$from_email.">\r\n";

  		return( mail ("\"".$to_email."\" <".$to_email.">", $subject, $message, $headers ) );
	}

}

?>