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

//------------------------------------------
// These are the standard snippets..
//------------------------------------------

// include a file
function snippet_include($filename) {
	global $Cfg, $Paths;

	$current_path = getcwd();

	$org_filename= $filename;

	if (file_exists($filename)) {
		$file_ok = TRUE;
	} else if (file_exists('templates/'.$filename)) {
		$filename = 'templates/'.$filename;
		$file_ok = TRUE;
	} else if (file_exists('../'.$filename)) {
		$filename = '../'.$filename;
		$file_ok = TRUE;
	} else if (file_exists('../../'.$filename)) {
		$filename = file_exists('../../'.$filename);
		$file_ok = TRUE;
	}

	if ($file_ok) {

		if ((defined('LIVEPAGE')) && (getextension($filename)=="php") ) {

			ob_start();
			include_once($filename);
			$output = ob_get_contents();
			ob_end_clean();

		} else {
			$output=parse_step4(implode("", file($filename)));
		}

	} else {
		if ($Cfg['debug']==0) {
			$output="<!-- error: could not include $org_filename. File does not exist -->";
		} else {
			$output="[error: could not include $org_filename. File does not exist]";
		}
	}

	chdir($current_path);
	return $output;

}

// 2004/10/24 =*=*= JM - insert the extensions/ folder path
function snippet_extensions_dir() {
	global $Cfg, $Paths;

	// only if extensions is defined...
	if(( isset( $Cfg['extensions_path'] ))
		&&( ''!=$Cfg['extensions_path'] )) {

		if( file_exists( '../'.$Cfg['extensions_path'] )) {
			$path = '../';
		} elseif( file_exists( '../../'.$Cfg['extensions_path'] )) {
			$path = '../../';
		} elseif( file_exists( '../../../'.$Cfg['extensions_path'] )) {
			$path = '../../../';
		}

		if( file_exists( $path )) {
			$path  = fixpath( $Paths['pivot_url'].$path );
			$path .= $Cfg['extensions_path'];
		} else {
			$path = '<!-- error: could not find extensions directory: '.$path.' -->';
		}
	} else {
		$path = '<!-- error: extensions directory is not defined -->';
	}
	return $path;
}




/*  image
		[[image:filename:alt:meta:compl]]
			filename -> name + suffix of file in images/ driectory
			alt      -> alt text
			meta     -> left   -> style info is added for left align
                  right  -> style info is added for right align
                  inline -> style info is added for inline display
                         -> if meta is empty then style info is added for centered display
      for the above, if meta contains numerical info, then this value is used as margin...
      else a default of 10px is used.

                  id     -> a unique id is passed in compl [xhtml -strict]
                  class  -> a class is passed in compl [xhtml -strict]

      note: first four use a class 'pivot-image' so they can be styled
      note: last two display no class/id if compl is empty

      compl -> numerical value or complementary info

      r3 - additional changes JM 2004/01/03
------------------------------------------------------------------------ */

function snippet_image( $filename,$alt='',$meta='',$compl=0 ) {
	global $Cfg, $Paths;

	// a call to Setpaths(); should not be necesary,
	// but for some reason it is.. Find out why..
	// Setpaths();

	$org_filename = $filename;

	if( !file_exists( '../'.$filename )) {
		$filename = '../'.$Cfg['upload_path'].$filename;
	} else {
		$filename = '../'.$filename;
	}

	// only contine if we have an image
	if( file_exists( $filename )) {
	  $filename = fixpath( $Paths['pivot_url'].$filename );

		// perhaps we've added a margin..
		if( preg_match( "/([a-z]+)\(([0-9]+)\)/i",$meta,$match )) {
			$meta   = $match[1];
			$margin = $match[2];
		} else {
			$margin = 10;
		}

		// do we need to clean compl?
		if(( 'id' == $meta )||( 'class' == $meta )) {
			if(( '' == $compl )||( is_numeric( $compl ))) {
				$compl = '';
			}
		} else {
			if(( '' == $compl )||( !is_numeric( $compl ))) {
				$border = 0;
			} else {
				$border = $compl;
			}
		}

		switch( $meta) {
			case( 'left' ):
				$output   = '<img src="'.$filename.'" style="margin-right:';
				$output  .= $margin.'px;margin-bottom:5px;" ';
				$output  .= 'border="'.$border.'" title="'.$alt.'" alt="'.$alt;
				$output  .= '" align="left" class="pivot-image" />';
				break;
			case( 'right' ):
				$output   = '<img src="'.$filename.'" style="margin-left:';
				$output  .= $margin.'px;margin-bottom:5px;" ';
				$output  .= 'border="'.$border.'" title="'.$alt.'" alt="'.$alt;
				$output  .= '" align="right" class="pivot-image" />';
				break;
			case( 'inline' ):
				$output  = '<img src="'.$filename.'" border="'.$border.'" title="'.$alt;
				$output .= '" alt="'.$alt.'" class="pivot-image" />';
				break;
			case( 'id' ):
			case( 'class' ):
				$output  = '<img src="'.$filename.'" title="'.$alt.'" alt="'.$alt.'" ';
				if( '' != $compl ) { $output .= ' '.$meta.'="'.$compl.'" '; }
				$output .= '/>';
				break;
			default:
				$output  = '<p style="text-align:center;">';
				$output .= '<img src="'.$filename.'" border="'.$border.'" title="'.$alt;
				$output .= '" alt="'.$alt.'" class="pivot-image" />';
				$output .= '</p>';
		}
	} else {
		debug( 'could not display image '.$org_filename.'. File does not exist' );
		$output = '<!-- error: could not display image '.$org_filename.'. File does not exist -->';
	}
	return $output;
}




// insert a popup to an image
function snippet_popup ($filename, $thumbname,  $alt="", $align="center", $border="") {
	global	$Cfg, $Paths;

	if (is_numeric($align)) {
		// the border and align properties were swapped, so we need
		// to compensate for the wrong ones.
		$i = $border;
		$border = $align;
		$align = $i;
	}

	if ( ($border=="") || (!is_numeric($border)) ) { $border=1; }

	$border= set_target("style=\"border: ".$border."px solid;\"", "border=\"$border\"");

	// Fix filename, if necessary
	$org_filename = $filename;

	if (!file_exists("../".$filename)) {
		$filename = "../".$Cfg['upload_path'].$filename;
	} else {
		$filename = "../".$filename;
	}

	// Fix Thumbname, perhaps use a thumbname, instead of textual link
	$org_thumbname = $thumbname;
	if ( ($thumbname=="") || ($thumbname=="(thumbnail)") ) {
		$ext = getextension($org_filename);
		$thumbname = str_replace($ext, 'thumb.'.$ext, $org_filename);
	}
	if (!file_exists("../".$thumbname)) {
		$thumbname = "../".$Cfg['upload_path'].$thumbname;
	} else {
		$thumbname = "../".$thumbname;
	}

	// If the thumbnail exists, make the HTML for it, else just use the text for a link.
	if (file_exists($thumbname)) {
		$thumbname = $Paths['host'] . fixpath( $Paths['pivot_url'].$thumbname);

		$ext=getextension($thumbname);
		if ( ($ext=="jpg")||($ext=="jpeg")||($ext=="gif")||($ext=="png") ) {
				if ($align=="center") {
					$thumbname="<img src=\"$thumbname\" $border alt=\"$alt\" title=\"$alt\"  class='pivot-popupimage'/>";
				} else {
					$thumbname="<img src=\"$thumbname\" $border alt=\"$alt\" title=\"$alt\" align=\"$align\" class='pivot-popupimage' />";
				}
		} else {
			$thumbname = $org_thumbname;
		}
	} else {
		if (strlen($org_thumbname)>2) {
			$thumbname = $org_thumbname;
		} else {
			$thumbname = "popup";
		}
	}

	// Finally, make the HTML for the popup..
	// changed 2004/10/08 =*=*= JM

	if( file_exists( $filename )) {
		list( $width,$height ) = @getimagesize( $filename ) ;
		$filename = $Paths['host'].fixpath( $Paths['pivot_url'].$filename ) ;

		// $url=$Paths['host'] . sprintf($Paths['pivot_url']."includes/photo.php?img=%s&amp;w=%s&amp;h=%s&amp;t=%s",$filename, $width, $height, $alt);
		$url  = $Paths['host'].$Paths['pivot_url'] ;
		$url .= 'includes/photo.php?img='. base64_encode($filename).'&amp;w='.$width.'&amp;h='.$height.'&amp;t='.base64_encode($alt);

		$target = set_target('','target="_self"');
		// removed the onmouseover=\"window.status='(popup)';return true;\"
		// and onmouseout=\"window.status='';return true;\"
		// that is _so_ 2002..
		$code = sprintf( "<a href='$filename' onclick=\"window.open('%s','imagewindow','width=%s,height=%s,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no,left=0,top=0');return false\" style='border: 0;' %s  class='pivot-popuptext' >%s</a>",$url,$width,$height,$target,$thumbname ) ;

		if( 'center'==$align ) {
			$code = '<p style="text-align:center;">'.$code.'</p>' ;
		}
	} else {
		$code = '[!-- error: could not popup '.$filename.'. File does not exist --]' ;
	}

	return $code;
}

// [1.20b2] original code by wouter teepe
// 2004/10/05 =*=*= JM - adapted to have png -> gif -> fail; sizes are automatic;
// 2004/11/29 =*=*= JM - changes for IE/PC icon issue, thanks madjo

function snippet_download( $filename,$icon,$text,$title ) {
 global $Cfg,$Paths;

 $org_filename = $filename ;

 if( !file_exists( '../'.$filename )) {
  $filename = '../'.$Cfg['upload_path'].$filename;
 } else {
  $filename = '../'.$filename;
 }

 if( file_exists( $filename )) {
  $filename = fixpath( $Paths['pivot_url'].$filename );
  $ext      = getextension( $filename );
  $middle   = '';

  switch( $icon ) {
   case( 'icon' ):
    if( file_exists( $Paths['pivot_path'].'pics/icon_'.$ext.'.png' )) {
     $image = fixpath( $Paths['pivot_url'].'pics/icon_'.$ext.'.png' );
    } else if( file_exists( $Paths['pivot_path'].'pics/icon_'.$ext.'.gif' )) {
     $image = fixpath( $Paths['pivot_url'].'pics/icon_'.$ext.'.gif' );
    } else {
     $image = fixpath( $Paths['pivot_url'].'pics/icon_file.gif' );
    }

    if( '' != $image ) {
     $width = 0; $height = 0;
     list( $width,$height ) = @getimagesize( $Paths['host'].$image );
     $middle = '<img src="'.$image;
     if( 0 != $width )  { $middle .='" width="'.$width; }
     if( 0 != $height ) { $middle .='" height="'.$height; }
     $middle .= '" alt="'.$title.'" class="icon" style="border:0;" />';
     }
    $middle .= ' '.$text;
    // all ok... leave
    break;

   case( 'text' ): // fall through
   default:
    $middle = $text;
   }
   $code = '<a href="'.$filename.'" title="'.$title.'" class="download">'.$middle.'</a>';
 } else {
   $code = '<!-- error: could not make a download for '.$org_filename.'. File does not exist -->' ;
 }
 return $code;
}




// link to a file  [1.20b2]
// JM -> This needs changing... =*=*=
function snippet_link ($filename, $name) {

	$ext=getextension($filename);

	if ( ($ext=="doc")||($ext=="pdf")||($ext=="txt")||($ext=="ppt")||($ext=="pot")||($ext=="xls")||($ext=="mdb") ) {
		$target = set_target(' rel="external"', ' target="_blank"');
	} else {
		$target="";
	}
	if (file_exists("../".$filename)) {
		$code = "<a href=\"$filename\" $target>$name</a>";
	} else {
		$code="<!-- error: could not link to $filename. File does not exist -->";
	}

	return $code;
}


// make something lowercase
function snippet_lower($text) {
	return strtolower($text);
}


// make something uppercase
function snippet_upper($text) {
	return strtoupper($text);
}

// JM =*=*= 2004/09/25 [1.20b2]
function snippet_sitename() {
	global $Cfg;

	$output=$Cfg['sitename'];
	return $output;
}



function snippet_weblogtitle() {
	global $Weblogs, $Current_weblog;

	$output=$Weblogs[$Current_weblog]['name'];
	return $output;
}


function snippet_weblogsubtitle() {
	global $db, $Weblogs, $Current_weblog;

	$output=$Weblogs[$Current_weblog]['payoff'];
	return $output;
}


function snippet_weblogname() {
	global $Weblogs, $Current_weblog;

	$output=$Current_weblog;
	return $output;
}



function snippet_archivetitle() {
	global $db, $Weblogs, $Current_weblog;

	$output=$Weblogs[$Current_weblog]['name'];
	return $output;
}


function snippet_archivesubtitle() {
	global $db, $Weblogs, $Current_weblog;

	$output=$Weblogs[$Current_weblog]['payoff'];
	return $output;
}


function snippet_link_list() {

	if(file_exists('templates/link_list.html')) {
		$output = snippet_include('link_list.html');
	} else {
		$output = snippet_include('_aux_link_list.html');
	}

	$output = targetblank($output);

	return $output;

}


function snippet_archive_list() {
	global $Weblogs, $Current_weblog, $Archive_array, $Archive_array_html , $Paths;

	if ($Weblogs[$Current_weblog]['archive_unit']=="none") {
		// if no archives are needed, just return.
		return "";
	}

	// if not yet done, load / make the array of archive filenames (together
	// with at least one date)
	if (!isset($Archive_array)) { make_archive_array(); }

	// if not yet done, compile the html for this archive list
	if (!isset($Archive_array_html[$Current_weblog])) {

		$output = "";

		// maybe flip and reverse it.
        // 2004/11/23 =*=*= JM - changes to avoid array errors
        if( is_array( $Archive_array[$Current_weblog] )) {
            if($Weblogs[$Current_weblog]['archive_order'] == 'descending') {
                $mylist = $Archive_array[$Current_weblog];
            } else {
                $mylist = array_reverse($Archive_array[$Current_weblog]);
            }

            foreach($mylist as $file => $date) {
                // make Mark proud, and make a nice absolute url for the archive..
                $filelink = $Paths['pivot_url'] . $Weblogs[$Current_weblog]['archive_path'] . $file;
                $filelink = fixPath($filelink);

                // fix the rest of the string..
                list($start_date, $stop_date) = getdaterange($date, $Weblogs[$Current_weblog]['archive_unit']);
                $this_output = format_date_range($start_date, $stop_date, $Weblogs[$Current_weblog]['archive_linkfile']);

                $this_output = str_replace("%url%" , $filelink, $this_output);

                $output .= $this_output;
            }
		}

		// store it for quick access later on..
		$Archive_array_html[$Current_weblog] = $output;

	} else {

		// goodie, it's alread been done, so we just retrieve it.
		$output = $Archive_array_html[$Current_weblog];

	}

	return stripslashes($output);

}


function snippet_livearchive_list ($filename="", $format="", $weblog="", $template="") {
	global $Weblogs, $Current_weblog, $Cfg, $Paths, $allcats;

	if ($filename == "") { $filename = $Paths['pivot_url']."archive.php"; }

	if ($format == "") {
		$format = "<a href='%file%?c=%cat%&amp;w=%weblog%&amp;t=%template%'>%cat%</a><br />";
	}

	$allcats = cfg_cats();

	if ($weblog == "current") {
		// get cats from current weblog..
		$cats = array();
		foreach($Weblogs[$Current_weblog]['sub_weblog'] as $subweblog) {
			$cats = array_merge($cats, $subweblog['categories']);
		}

	} else if ( ($weblog != "") && (isset($Weblogs[$weblog])) ) {
		// get cats from a specific weblog..
		$cats = array();
		foreach($Weblogs[$weblog]['sub_weblog'] as $subweblog) {
			$cats = array_merge($cats, $subweblog['categories']);
		}

	} else {
		// get all cats..
		$cats = explode("|", $Cfg['cats']);
	}


	usort($cats, "category_simplesort");

	foreach ($cats as $cat) {

		// skip cats if it's 'not-public'.
		if ($allcats[$cat]['hidden'] == 1) { continue; }

		// skip if name is empty
		if ($cat == "") { continue; }

		$my_output = $format;
		$my_output = str_replace('%file%', $filename, $my_output);
		$my_output = str_replace('%cat%', $cat, $my_output);
		$my_output = str_replace('%weblog%', $weblog, $my_output);
		$my_output = str_replace('%template%', $template, $my_output);
		$output .= $my_output;
	}

	return $output;

}

function snippet_subweblog ($sub="", $count="") {
	global $db;

	if ($sub!="") { $sub="subweblog=\"$sub\""; }
	if ($count!="") { $count="showme=\"$count\""; }

	$output= cms_tag_weblog("nodes=\"lower\" order=\"lasttofirst\" $sub $count",'');

	return $output;
}

// an alias for [[subweblog]]
function snippet_weblog ($sub="", $count="") {

// if no subweblog has been specified, just assume 'standard'
	if (strlen($sub)<2) { $sub = "standard"; }

	return snippet_subweblog($sub, $count);

}



function snippet_archive ($sub="") {

	if ($sub!="") { $sub="subweblog=\"$sub\""; }

	$output= cms_tag_archive("nodes=\"lower\" order=\"lasttofirst\" $sub",'');

	return $output;
}

// Displays information about an entry. Can only be used in an entry.
// [[entry_data:word:image:download]]
// bob's function changed by JM
// 2004/11/25 =*=*= JM - minor corrections
function snippet_entry_data( $word='',$image='',$download='' ) {
  global $db;
  $output = array();
  // count words - only if OK
  if( '' != $word ) {
    $total = str_word_count(strip_tags($db->entry['title']." ".$db->entry['introduction']." ".$db->entry['body'])) ;
    if( '*' == $word ) {
       $output[] = ' '.$total.' '.lang( 'snippets_text','word_plural' );
    } else {
      $output[] = $total.' '.$word;
    }
  }
  // count images - only if OK
  if( '' != $image ) {
    preg_match_all("/(<img|\[\[image|\[\[popup)/mi", $db->entry['introduction'].$db->entry['body'], $match );
    $total = count( $match[0] );
    if( $total > 0 ) {
      if( '*' == $image ) {
        // single/plural
        if( 1 == $total ) {
          $output[] = '1 '.lang( 'snippets_text','image_single' );
        } else {
          $output[] = $total.' '.lang( 'snippets_text','image_plural' );
        }
      } else {
        $output[] = $total.' '.$image;
      }
    }
  }
  // count downloads - only if OK
  if( '' != $download ) {
    preg_match_all("/(\[\[download)/mi", $db->entry['introduction'].$db->entry['body'], $match );
    $total = count( $match[0] );
    if( $total > 0 ) {
      if( '*' == $download ) {
      // single/plural
        if( 1 == $total ) {
          $output[] = '1 '.lang( 'snippets_text','download_single' );
        } else {
          $output[] = $total.' '.lang( 'snippets_text','download_plural' );
        }
      } else {
        $output[] = $total.' '.$download;
      }
    }
  }
  return implode( ', ',$output );
}



// originally hacked by digits to adjust image size if custom Pivot badge is used
// changed for accessibility [and localistaion] by JM =*=*= 2004/10/03

function snippet_pivotbutton() {
	global $Paths,$build ;

	list( $width,$height) = @getimagesize( $Paths['pivot_path'].'pics/pivotbutton.png' ) ;
	$image   = $Paths['pivot_url'].'pics/pivotbutton.png' ;
	$alttext = lang( 'weblog_text','powered_by' ).$build ;

	$output  = '<a href="http://www.pivotlog.net/?ver='.urlencode( $build ).'" title="'.$alttext.'">' ;
	$output .= '<img src="'.$image.'" width="'.$width.'" height="'.$height.'" alt="'.$alttext.'" ' ;
	$output .= 'class="badge" longdesc="http://www.pivotlog.net/?ver='.urlencode( $build ).'" /></a>';
	$output  = targetblank( $output ) ;

	return $output;
}


function snippet_code() {
	global $db;

	$output=$db->entry['code'];

	return $output;
}


function snippet_uid() {
	global $db;

	$output=$db->entry['code'];

	return $output;
}


function snippet_id_anchor($name) {
	global $db;

	if ($name=="") { $name="e"; }

	$output="<span id=\"".$name.$db->entry['code']."\"></span>";

	return $output;
}

function snippet_even_odd() {
	global $even_odd;

	if ($even_odd) {
		return "even";
	} else {
		return "odd";
	}

}

function snippet_title() {
	global $db;

	$output = parse_step4( $db->entry['title']);

	return $output;
}



function snippet_subtitle() {
	global $db;

	$output = parse_step4( $db->entry['subtitle']);

	return $output;
}


function snippet_introduction($strip="") {
	global $db, $Weblogs, $Current_weblog;


	$output = parse_intro_or_body( $db->entry['introduction'], $strip);

	return $output;

}


function snippet_body($strip="") {
	global $db, $Weblogs, $Current_weblog;

	$output = parse_intro_or_body( $db->entry['body'], $strip);

	return $output;

}


function snippet_date($format="") {
	global $db, $Weblogs, $Current_weblog;

	if($format=="") {
		$format = $Weblogs[$Current_weblog]['entrydate_format'];
	} else {
		// compensate for textile oddness..
		$format = str_replace("<span>", "%", $format);
		$format = str_replace("</span>", "%", $format);
	}

	$output=format_date($db->entry["date"], $format );

	return $output;
}



function snippet_edit_date($format="") {
	global $db, $Weblogs, $Current_weblog;

	// only if different from normal date..
	if ($db->entry["edit_date"] == $db->entry["date"]) {
		return "";
	}

	if($format=="") {
		$format = $Weblogs[$Current_weblog]['fulldate_format'];
	} else {
		// compensate for textile oddness..
		$format = str_replace("<span>", "%", $format);
		$format = str_replace("</span>", "%", $format);
	}

	$output=format_date($db->entry["edit_date"], $format );

	return $output;
}



function snippet_fulldate($format="") {
	global $db, $Weblogs, $Current_weblog;

	if($format=="") {
		$format = $Weblogs[$Current_weblog]['fulldate_format'];
	} else {
		// compensate for textile oddness..
		$format = str_replace("<span>", "%", $format);
		$format = str_replace("</span>", "%", $format);
	}

	$output=format_date($db->entry["date"], $format );


	return $output;
}



function snippet_diffdate() {
	global $db, $diffdate_thisformat, $diffdate_lastformat, $Weblogs, $Current_weblog;

	$diffdate_thisformat=format_date($db->entry["date"], $Weblogs[$Current_weblog]['diffdate_format'] );
	if ( (!isset($diffdate_lastformat)) || ($diffdate_lastformat!=$diffdate_thisformat) ) {
		$diffdate_lastformat=$diffdate_thisformat;
	} else {
		$diffdate_thisformat="";
	}


	return $diffdate_thisformat;
}

function snippet_registered() {
	global $Pivot_Vars,  $Pivot_Cookies;

	// get the cookies in an array..
	if (isset($Pivot_Vars['HTTP_COOKIE']) && !isset($Pivot_Cookies))  {
		foreach (explode(";", $Pivot_Vars['HTTP_COOKIE']) as $cookie) {
			list ($key, $value)= explode("=", $cookie);
			$Pivot_Cookies[trim($key)] = urldecode(trim($value));
		}
	}

	// get the cookies in an array..
	if (isset($Pivot_Cookies['piv_reguser']))  {
		return "registered";
	}
}


function snippet_cookie($name="") {
	global $Pivot_Vars, $Pivot_Cookies, $Weblogs, $Current_weblog, $reg_user;

	include_once("modules/module_userreg.php");

	// if we don't use live entries, do _not_ use the cookies..
	if ($Weblogs[$Current_weblog]['live_entries'] == 0) {
		return "";
	}


	// get the cookies in an array..
	if (isset($Pivot_Vars['HTTP_COOKIE']) && !isset($Pivot_Cookies))  {
		foreach (explode(";", $Pivot_Vars['HTTP_COOKIE']) as $cookie) {
			list ($key, $value)= explode("=", $cookie);
			$Pivot_Cookies[trim($key)] = urldecode(trim($value));
		}
	}

	// If registered user, override the other settings..
	if (isset($Pivot_Cookies['piv_reguser']) && (!isset($reg_user))) {

		list($reg_name, $reg_hash) = explode("|", 	$Pivot_Cookies['piv_reguser']);

		//debug("reg: $reg_name, $reg_hash");
		if (check_user_hash($reg_name, $reg_hash)) {
			$reg_user = load_user($reg_name);
			if ($reg_user['show_address']==1) {
				$Pivot_Cookies['piv_email'] = $reg_user['email'];
			} else {
				$Pivot_Cookies['piv_email'] = "";
			}
			$Pivot_Cookies['piv_name'] = $reg_user['name'];
			$Pivot_Cookies['piv_url'] = $reg_user['url'];
		} else {
			$reg_user = FALSE;
		}
	}


	switch($name) {
		case 'all':
			echo "<h1>koekies</h1><pre>cookies:";
			print_r($Pivot_Cookies);
			echo "</pre>";
			break;
		case 'name':
			return (isset($Pivot_Cookies['piv_name'])) ? $Pivot_Cookies['piv_name'] : "";
			break;
		case 'email':
			return (isset($Pivot_Cookies['piv_email'])) ? $Pivot_Cookies['piv_email'] : "";
			break;
		case 'url':
			return (isset($Pivot_Cookies['piv_url'])) ? $Pivot_Cookies['piv_url'] : "";
			break;
		case 'remember_yes':
			return (isset($Pivot_Cookies['piv_rememberinfo'])) ? "checked='checked'" : "";
			break;
		case 'remember_no':
			return (isset($Pivot_Cookies['piv_rememberinfo'])) ? "" : "checked='checked'";
			break;
		case 'reguser':
			return (isset($Pivot_Cookies['piv_reguser'])) ? $Pivot_Cookies['piv_reguser'] : "";
			break;
	}


}


function snippet_jscookies() {

	$output = "<script type='text/javascript'>
//<![CDATA[
function readCookie(name) { var cookieValue = ''; var search = name + '='; if(document.cookie.length > 0) {  offset = document.cookie.indexOf(search); if (offset != -1) {  offset += search.length; end =  document.cookie.indexOf(';', offset); if (end == -1) end = document.cookie.length; cookieValue = unescape(document.cookie.substring(offset, end)) } } return cookieValue.replace(/\+/gi, ' '); } function getNames() { if (document.getElementsByName('piv_name')) { elt = document.getElementsByName('piv_name'); elt[0].value=readCookie('piv_name'); } if (document.getElementsByName('piv_email')) { elt = document.getElementsByName('piv_email'); elt[0].value=readCookie('piv_email');  } if (document.getElementsByName('piv_url')) { elt = document.getElementsByName('piv_url'); elt[0].value=readCookie('piv_url');  } if (document.getElementsByName('piv_rememberinfo')) { elt = document.getElementsByName('piv_rememberinfo'); if (readCookie('piv_rememberinfo') == 'yes') { elt[0].checked = true; } } } window.onload = setTimeout('getNames()', 500);
//]]>
</script>";

	return $output;

}

function snippet_nick() {
	return snippet_user("nick");
}


function snippet_user($field) {
	global $db, $Users;

	if ($field=="") {

		$output=$db->entry['user'];

	} else if ($field=="emailtonick") {

		if ($Users[$db->entry['user']]['nick']!="") {
			$output = encodemail_link($Users[$db->entry['user']]['email'], $Users[$db->entry['user']]['nick'] );
		} else {
			$output = encodemail_link($Users[$db->entry['user']]['email'], $db->entry['user']);
		}

	} else {

		if (isset($Users[$db->entry['user']][$field])) {
			$output = $Users[$db->entry['user']][$field];
		} else {
			$output = $db->entry['user'];
		}

	}

	return $output;
}




// 2004/10/25 =*=*= JM
/* [[encrypt_mail:email:display:title]]
   email -> mail address
   display -> if not mail address
   title -> in the href
   follows the encrypt mail switch in page three of weblog admin
*/
function snippet_encrypt_mail( $email='',$display='',$title='' ) {
    // only continue if there's a valid mail address
    if( isemail( $email )) {
        $output = encodemail_link( $email,$display,$title );
    } else {
        // do we have a fallback?
        if( '' != $display ) {
            $output = $display ;
        } else {
          $output = '<!-- error: unable to display encrypted e-mail address -->';
        }
    }
    return $output;
}


function snippet_category($filter="") {
	global $db, $Weblogs, $Current_weblog, $Current_subweblog;

	$output=$db->entry["category"];

	if ( ($filter != "") && (isset($Weblogs[$Current_weblog]['sub_weblog'][$Current_subweblog])) ) {
		$output = array_intersect ( $Weblogs[$Current_weblog]['sub_weblog'][$Current_subweblog]['categories'], $output);
	}

	if (is_array($output)) {
		return implode(", ", $output);
	} else {
		return "";
	}
}



function snippet_category_link($filter="", $filename="") {
	global $db, $Weblogs, $Current_weblog, $Current_subweblog, $Paths;

	if ($filename == "") { $filename = $Paths['pivot_url']."archive.php"; }

	$output=$db->entry["category"];

	if ( ($filter != "") && (isset($Weblogs[$Current_weblog]['sub_weblog'][$Current_subweblog])) ) {
		$output = array_intersect ( $Weblogs[$Current_weblog]['sub_weblog'][$Current_subweblog]['categories'], $output);
	}

	$f = create_function('&$item,$key','$item = "<a href=\"'.$filename.'?c=$item\">$item</a>";');
	array_walk($output, $f);

	if (is_array($output)) {
		return implode(", ", $output);
	} else {
		return "";
	}
}




// for backwards compatibility
function snippet_email() {
	global $db, $Users;

	if ($db->entry['code']=='ROOT') {
		$output='<cms email></cms>';
	} else {
		$output=encodemail($Users[$entry['user']]['email']);
	}

	return $output;
}

// for backwards compatibility
function snippet_email_to_nick() {
	global $db, $Users;

	if ($db->entry['code']=='ROOT') {
		$output='<cms emailtonick></cms>';
	} else {
		if (isset($Users[$db->entry['user']])) {
			$output = encodemail_link($Users[$db->entry['user']]['email'], $db->entry['user']);
		} else {
			$output = $db->entry['user'];
		}
	}


	return $output;
}



function snippet_more( $title='' ) {
	global $db, $Weblogs, $Current_weblog, $Cfg;

	if( '' != $title ) {
		$title = 'title="'.$title.'" ';
		// substitute %title% in the title attribute.
		$title = str_replace("%title%", $db->entry['title'], $title);
	}

	if( strlen( $db->entry['body'] ) >5 ) {
		$morelink = make_filelink( $db->entry['code'],'','body');
		$text     = ( strlen( $Weblogs[$Current_weblog]['read_more']) > 1 ) ?  $Weblogs[$Current_weblog]['read_more'] : '(more)';
		if( 1 == $Weblogs[$Current_weblog]['comment_pop']) {
			$target  = set_target('','target="_self"');
			$output  = sprintf( "<a href='%s' ",$morelink );
			$output .= $title;
			$output .= sprintf(  "onclick=\"window.open('%s','popuplink','width=%s,height=%s,directories= no,location=no,scrollbars=yes,menubar=no,status=yes,toolbar=no,resizable =yes'); return false\"", $morelink,  $Weblogs[$Current_weblog]['comment_width'],  $Weblogs[$Current_weblog]['comment_height'] );
			$output .= sprintf( " %s>%s</a>",$target,$text );
		} else {
			$output = "<a href=\"$morelink\"  $title>".$Weblogs[$Current_weblog]['read_more']."</a>";
		}

		// substitute %title% in the 'more' link.
		$output = str_replace("%title%", $db->entry['title'], $output);

	} else {
		$output = '';
	}
	return $output;
}


function snippet_via($text="") {
	global $db;

	if (strlen($db->entry['vialink']) > 4 ) {

		if ($text == "") {
			$text = "[<a href='%link%' title='%title%'>via</a>]";
		}

		$output = $text;
		$output = str_replace("%link%", $db->entry['vialink'], $output);
		$output = str_replace("%title%", $db->entry['viatitle'], $output);

		return $output;

	} else {

		return "";

	}
}

/*  snippet keywords 1.20
    *bob's having fun again*
    [[keyword:text:sep]]
        if keyword in extended form field less than 2 chars, passes through
        else
        text -> if set passed through, else contents of field used
        sep  -> if set, separator to use, else comma is used
                else
                if 'clear' -> then field contents passed through
        r2 - little additions by JM 2005/01/03
-------------------------------------------------------------- */
function snippet_keywords( $text='%keywords%',$sep=', ' ) {
    global $db;

    if( 'clear' == $sep ) {
        $output = $db->entry['keywords'];
    } elseif( strlen( trim( $db->entry['keywords'] )) > 2 ) {
        // format output..
        $output = preg_split( '/[, ]+/i',$db->entry['keywords'] );
        $output = implode( $sep,$output );
        $output = str_replace( '%keywords%',$output,$text );
    }    else {
        $output = $db->entry['keywords'];
    }
    return $output;
}




function snippet_comments() {
	global $db;

	$output = cms_tag_comments('', '');

	return $output;
}


function snippet_commentform() {
	global $Cfg, $db, $Weblogs, $Current_weblog;

	// check for entry's allow_comments, blocked IP address or subweblog comments..
	if ( (isset($db->entry['allow_comments']) && ($db->entry['allow_comments']==0)) || (ip_check_block($_SERVER['REMOTE_ADDR']))  ) {
		// if allow_comments set to 0, or current visitor has his ip blocked, then don't show form
		$output ="";
	}	else {
		// else show it
		if(file_exists('templates/_sub_commentform.html')) {
			$output='[[include:templates/_sub_commentform.html]]';
		} else {
			$output='[[include:templates/commentform.html]]';
		}

		$output = parse_step4($output);

	}

	return $output;

}


function snippet_archivenext($text="") {
	global $db, $Weblogs, $Current_weblog, $Archive_array, $archive_array_next, $Pivot_Vars, $live_output, $Paths;

	if (!$live_output) {

		// in 'normal' archive pages.

		if (!isset($db->entry)) { return "";  }

		// get the filename for the current archive..
		$current_archive = make_archive_name();
		$id = $db->entry['code'];

		// get the archive_array, if it's not done yet.
		if (!isset($Archive_array)) { make_archive_array(); }

		// we make another array where we number the archives. As far as i know this is
		// the only decent way to find the next or previous entries..
		// sometime i will want to cache this for performance
		$counter = 0;

	    // 2004/11/23 =*=*= JM - foreach() test
	    if( is_array( $Archive_array[$Current_weblog] )) {
	        foreach ($Archive_array[$Current_weblog] as $this_key => $this_val) {
	            $temp_arr[$counter] = $this_key;
	            if ($this_key == $current_archive) { $this_count = $counter; }
	            $counter++;
	        }
	    }

		if($text=="") {
			$text = lang('weblog_text','next_archive');
		}

		// then, we'll pick out the previous one in the array (remember, the array
		// is in reverse chronological order)
		if (isset($temp_arr[$this_count+1])) {
			$next_arc = $temp_arr[$this_count+1];
			$target = set_target('', 'target="_self"');

			$link = make_archive_link($Archive_array[$Current_weblog][ $next_arc ]);
			$link = sprintf("<a href=\"%s\" %s>%s</a>", $link, $target, $text ) ;

			return $link;
		} else {
			return "";
		}

	} else {

		// display link for pages in 'live archives'

		// guessstimate the current sybweblog.
		reset($Weblogs[$Current_weblog]['sub_weblog']);
		$my_subweblog = current($Weblogs[$Current_weblog]['sub_weblog']);

		$show = $my_subweblog['num_entries'];

		if($text=="") {
			$text = lang('weblog_text','next_archive');
		}

		$link = sprintf("%sarchive.php?c=%s&o=%s",
			$Paths['pivot_url'],
			$Pivot_Vars['c'],
			$Pivot_Vars['o']+$show
		);

		$target = set_target('', 'target="_self"');
		$link = sprintf("<a href=\"%s\" %s>%s</a>", $link, $target, $text ) ;

		return $link;

	}

}



function snippet_archiveprevious($text="") {
	global $db, $Weblogs, $Current_weblog, $Archive_array, $archive_array_prev, $Pivot_Vars, $live_output, $Paths;


	if (!$live_output) {

		// in 'normal' archive pages'..

		if (!isset($db->entry)) { return ""; }

		// get the filename for the current archive..
		$current_archive = make_archive_name();
		$id = $db->entry['code'];

		// get the archive_array, if it's not done yet.
		if (!isset($Archive_array)) { make_archive_array(); }

		// we make another array where we number the archives. As far as i know this is
		// the only decent way to find the next or previous entries..
		// sometime i will want to cache this for performance
		$counter = 0;

		// 2004/11/23 =*=*= JM - foreach() test
	    if( is_array( $Archive_array[$Current_weblog] )) {
			foreach ($Archive_array[$Current_weblog] as $this_key => $this_val) {
				$temp_arr[$counter] = $this_key;
				if ($this_key == $current_archive) { $this_count = $counter; }
				$counter++;
			}
	    }

		if($text=="") {
			$text = lang('weblog_text','previous_archive');
		}

		// then, we'll pick out the next one in the array (remember, the array
		// is in reverse chronological order)
		if (isset($temp_arr[$this_count-1])) {
			$prev_arc = $temp_arr[$this_count-1];
			$target = set_target('', 'target="_self"');

			$link = make_archive_link($Archive_array[$Current_weblog][ $prev_arc ]);
			$link = sprintf("<a href=\"%s\" %s>%s</a>", $link, $target, $text ) ;

			return $link;
		} else {
			return "";

		}

	} else {

		// display link for pages in 'live archives'

		// guessstimate the current sybweblog.
		reset($Weblogs[$Current_weblog]['sub_weblog']);
		$my_subweblog = current($Weblogs[$Current_weblog]['sub_weblog']);

		$show = $my_subweblog['num_entries'];

		debug("showprev = $show");

		// if it's the first one, we don't have to show it.
		if (($Pivot_Vars['o']-$show)>=0) {

			debug('1');

			if($text=="") {
				$text = lang('weblog_text','next_archive');
			}

			$link = sprintf("%sarchive.php?c=%s&o=%s",
				$Paths['pivot_url'],
				$Pivot_Vars['c'],
				$Pivot_Vars['o']-$show
			);

			$target = set_target('', 'target="_self"');
			$link = sprintf("<a href=\"%s\" %s>%s</a>", $link, $target, $text ) ;

		} else {
			debug('2');

			$link = "";

		}

		return $link;


	}
}



function snippet_nextentry($text="", $cutoff) {
	global $db, $global_pref, $temp_entry;

	// initialize a temporary db..
	$temp_db = new db(FALSE);

	// we fetch the next one, until we get one that is set to 'publish'
	$get_next_amount = 1;
	do {
		$next_code=$db->get_next_code($get_next_amount);
		if ($next_code) { $temp_entry = $temp_db->read_entry($next_code); }
		$get_next_amount++;
	} while ( !($next_code===FALSE) && !($temp_entry['status']=="publish") );

	unset($temp_db);

	if( ''==$text ) {
		$text = '&nbsp;&nbsp;&raquo; <a href="%link%">%title%</a>' ;
	}

	if( ''==$cutoff ) {
		$cutoff = 20 ;
	}

	if ($next_code) {
		$title= (strlen($temp_entry['title'])>2) ? $temp_entry['title'] : substr($temp_entry['introduction'],0,100);
		$link=make_filelink($next_code, "", "");
		$output="$text";
		$output=str_replace("%link%", $link, $output);
		$output=str_replace("%code%", $next_code, $output);
		$output=str_replace("%title%", trimtext($title,$cutoff), $output);
		return $output;

	} else {
		return "";
	}

}



function snippet_previousentry($text="", $cutoff) {
	global $db, $global_pref, $temp_entry;

	// initialize a temporary db..
	$temp_db = new db(FALSE);

	// we fetch the next one, until we get one that is set to 'publish'
	$get_prev_amount = 1;
	do {
		$prev_code=$db->get_previous_code($get_prev_amount);
		if ($prev_code) { $temp_entry = $temp_db->read_entry($prev_code); }
		$get_prev_amount++;
	} while ( !($prev_code===FALSE) && !($temp_entry['status']=="publish") );

	unset($temp_db);

	if( '' == $text ) {
		$text = '&laquo; <a href="%link%">%title%</a>' ;
	}
	if( '' == $cutoff ) {
		$cutoff = 20 ;
	}

	if ($prev_code) {
		$title= (strlen($temp_entry['title'])>2) ? $temp_entry['title'] : substr($temp_entry['introduction'],0,100);
		$link=make_filelink($prev_code, "", "");
		$output="$text";
		$output=str_replace("%link%", $link, $output);
		$output=str_replace("%code%", $prev_code, $output);
		$output=str_replace("%title%", trimtext($title,$cutoff), $output);
		return $output;
	} else {
		return "";
	}
}



function snippet_nextentryincategory($text="", $cutoff) {
	global $db, $global_pref, $temp_entry;

	// initialize a temporary db..
	$temp_db = new db(FALSE);

	$cat = $db->entry['category'];

	// we fetch the next one, until we get one that is set to 'publish'
	$get_next_amount = 1;
	do {
		$next_code=$db->get_next_code($get_next_amount);
		if ($next_code) { $temp_entry = $temp_db->read_entry($next_code); }
		$get_next_amount++;

		if ($next_code===FALSE) { break; }

	} while (  !($temp_entry['status']=="publish")  || (count(@array_intersect( $temp_entry['category'], $cat)) == 0) );

	unset($temp_db);

	if( '' == $text ) {
		$text = '&nbsp;&nbsp;&raquo; <a href="%link%">%title%</a>';
	}

	if( '' == $cutoff ) {
		$cutoff = 20 ;
	}

	if ($next_code) {
		$title= (strlen($temp_entry['title'])>2) ? $temp_entry['title'] : substr($temp_entry['introduction'],0,100);
		$link=make_filelink($next_code, "", "");
		$output="$text";
		$output=str_replace("%link%", $link, $output);
		$output=str_replace("%code%", $next_code, $output);
		$output=str_replace("%title%", trimtext($title,$cutoff), $output);
		return $output;

	} else {
		return "";
	}

}



function snippet_previousentryincategory($text="", $cutoff) {
	global $db, $global_pref, $temp_entry;

	// initialize a temporary db..
	$temp_db = new db(FALSE);

	$cat = $db->entry['category'];

	// we fetch the next one, until we get one that is set to 'publish'
	$get_prev_amount = 1;
	do {
		$prev_code=$db->get_previous_code($get_prev_amount);
		if ($prev_code) { $temp_entry = $temp_db->read_entry($prev_code); }
		$get_prev_amount++;

		if ($prev_code===FALSE) { break; }

	} while ( !($temp_entry['status']=="publish") || (count(@array_intersect( $temp_entry['category'], $cat)) == 0) );

	unset($temp_db);

	if( '' == $text ) {
		$text = '&laquo; <a href="%link%">%title%</a>' ;
	}
	if( '' == $cutoff ) {
		$cutoff = 20 ;
	}

	if ($prev_code) {
		$title= (strlen($temp_entry['title'])>2) ? $temp_entry['title'] : substr($temp_entry['introduction'],0,100);
		$link=make_filelink($prev_code, "", "");
		$output="$text";
		$output=str_replace("%link%", $link, $output);
		$output=str_replace("%code%", $prev_code, $output);
		$output=str_replace("%title%", trimtext($title,$cutoff), $output);
		return $output;
	} else {
		return "";
	}
}





function snippet_label($label) {

	return lang('weblog_text', $label);

}


// originally hacked by digits to adjust image size if custom RSS image is used
// changed for accessibility [and localisation] by JM =*=*= 2004/10/03

function snippet_rssbutton() {
	global $Weblogs,$Current_weblog,$Paths ;

	$filename = fixpath( $Paths['pivot_url'].$Weblogs[$Current_weblog]['rss_path'] ).$Weblogs[$Current_weblog]['rss_filename'] ;
	$image    = $Paths['pivot_url'].'pics/rssbutton.png' ;
	list( $width,$height ) = @getimagesize( $Paths['pivot_path'].'pics/rssbutton.png' ) ;
	$alttext  = lang( 'weblog_text','xml_feed' ) ;

	$output   = '<a href="'.$filename.'" title="'.$alttext.'"><img src="'.$image.'" width="'.$width.'" height="'.$height.'"' ;
	$output  .= ' alt="'.$alttext.'" class="badge" longdesc="'.$filename.'" /></a>' ;
	$output   = targetblank( $output ) ;

	return $output;
}


// originally hacked by digits to adjust image size if custom ATOM image is used
// changed for accessibility [and localistaion] by JM =*=*= 2004/10/03

function snippet_atombutton() {
	global $Weblogs,$Current_weblog,$Paths ;

	$filename = fixpath( $Paths['pivot_url'].$Weblogs[$Current_weblog]['rss_path'] ).$Weblogs[$Current_weblog]['atom_filename'] ;
	$image    = $Paths['pivot_url'].'pics/atombutton.png' ;
	list( $width,$height ) = @getimagesize( $Paths['pivot_path'].'pics/atombutton.png' ) ;
	$alttext  = lang( 'weblog_text','atom_feed' ) ;

	$output   = '<a href="'.$filename.'" title="'.$alttext.'"><img src="'.$image.'" width="'.$width.'" height="'.$height.'"' ;
	$output  .= ' alt="'.$alttext.'" class="badge" longdesc="'.$filename.'" /></a>' ;
	$output   = targetblank( $output ) ;

	return $output;
}


function snippet_rss_autodiscovery() {
	global  $Weblogs, $Current_weblog, $Paths;

	$filename = fixpath($Paths['pivot_url']. $Weblogs[$Current_weblog]['rss_path']) . $Weblogs[$Current_weblog]['rss_filename'];

	$output = '<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$filename.'"/>';

	return $output;
}


function snippet_atom_autodiscovery() {
	global  $Weblogs, $Current_weblog, $Paths;

	$filename = fixpath($Paths['pivot_url']. $Weblogs[$Current_weblog]['rss_path']) . $Weblogs[$Current_weblog]['atom_filename'];

	$output = '<link rel="alternate" type="application/atom+xml" title="Atom" href="'.$filename.'"/>';

	return $output;
}


function snippet_trackback_autodiscovery() {

	return snippet_trackautodiscovery();

}

function snippet_last_comments() {
	global $Cfg, $db, $Weblogs, $Current_weblog, $temp_entry, $Paths;

	if (isset($Weblogs[$Current_weblog]['lastcomm_format']) && (strlen($Weblogs[$Current_weblog]['lastcomm_format'])>2)) {
		$last_comments_format = $Weblogs[$Current_weblog]['lastcomm_format'];
	} else {
		$last_comments_format = "<a href='%url%' title='%date%' %popup%><b>%name%</b></a> (%title%): %comm%<br />";
	}

	if (isset($Weblogs[$Current_weblog]['lastcomm_length']) && ($Weblogs[$Current_weblog]['lastcomm_length']>0)) {
		$last_comments_length = $Weblogs[$Current_weblog]['lastcomm_length'];
	} else {
		$last_comments_length = 100;
	}

	if (isset($Weblogs[$Current_weblog]['lastcomm_trim']) && ($Weblogs[$Current_weblog]['lastcomm_trim']>0)) {
		$last_comments_trim = $Weblogs[$Current_weblog]['lastcomm_trim'];
	} else {
		$last_comments_trim = 16;
	}

	if (isset($Weblogs[$Current_weblog]['lastcomm_amount']) && ($Weblogs[$Current_weblog]['lastcomm_amount']>0)) {
		$last_comments_count = $Weblogs[$Current_weblog]['lastcomm_amount'];
	} else {
		$last_comments_count = 10;
	}

	@$file =	array_reverse(load_serialize($Paths['pivot_path']."db/ser_lastcomm.php", true, true));

	$cats = array();
	// round up the cats that are shown on this page:
	foreach ($Weblogs[$Current_weblog]['sub_weblog'] as $subweblog) {
			$cats = array_merge($cats, $subweblog['categories']);
	}

	$output="";

	$count=0;

	if (count($file)>0) {
		foreach ($file as $comment) {

			// if it's in a category that published n the frontpage, and the user is not blocked, we display it.
			if ( (count(array_intersect($comment['category'], $cats))>0)
			&& (!(ip_check_block(trim($comment['ip']))))
			&& $db->entry_exists($comment['code']) ) {

				$temp_entry['date'] = $db->get_date($comment['code']);
				$temp_entry['title'] = $comment['title'];
				$temp_entry['code'] = $comment['code'];

				$id = safe_string($comment["name"],TRUE) . "-" . format_date($comment["date"], "%ye%%month%%day%%hour24%%minute%");

				$url=make_filelink($comment['code'], "", $id);

				$comment['name'] = trimtext(stripslashes($comment['name']), $last_comments_trim);
				$comment['title'] = trimtext(stripslashes($comment['title']), $last_comments_trim);
				$comment['comment'] = mywordwrap($comment['comment'], 18, " ", 1);
				$comment['comment'] = str_replace('/', '', comment_format($comment["comment"]));
				// Remove the [name:1] part in the 'last comments..
				$comment['comment'] = preg_replace("/\[(.*):([0-9]+)\]/iU", '', $comment['comment']);
				$comment['comment'] = trimtext(stripslashes($comment['comment']), $last_comments_length);
				$comment['comment'] = unentify($comment['comment']);

				if ($Weblogs[$Current_weblog]['comment_pop']==1) {

					$popup= sprintf("onclick=\"window.open('%s', 'popuplink', 'width=%s,height=%s,directories=no,location=no,scrollbars=yes,menubar=no,status=yes,toolbar=no,resizable=yes'); return false\"", $url, $Weblogs[$Current_weblog]['comment_width'], $Weblogs[$Current_weblog]['comment_height']);

				} else {
					$popup="";
				}

				$thisline=$last_comments_format;
				$thisline=str_replace("%name%", $comment['name'], $thisline);
				$thisline=str_replace("%date%", $comment['date'], $thisline);
				$thisline=str_replace("%title%", $comment['title'], $thisline);
				$thisline=str_replace("%url%", $url, $thisline);
				$thisline=str_replace("%popup%", $popup, $thisline);
				$thisline=str_replace("%comm%", $comment['comment'], $thisline);

				$thisline=format_date($comment["date"], $thisline);

				$output.= $thisline;

				$count++;
				if ($count>=$last_comments_count) {
					return $output;
				}
			}
		}
	}
	return $output;
}



function snippet_emotpopup($title) {
	global $Cfg, $Weblogs, $Current_weblog, $Paths, $emoticon_window,$emoticon_window_width,$emoticon_window_height;

	if ($title=="") { $title= lang('weblog_text', 'emoticons'); }

	if ($Weblogs[$Current_weblog]['emoticons']==1) {

		if ($emoticon_window != "") {

			$url = $Paths['extensions_url']."emoticons/".$emoticon_window;

			$onclick = sprintf("window.open('%s','emot','width=%s,height=%s,directories=no,location=no,menubar=no,scrollbars=no,status=yes,toolbar=no,resizable=yes');return false",
						$url,
						$emoticon_window_width,
						$emoticon_window_height
					);

			$output = sprintf("<a href='#' onmouseover=\"window.status='(Emoticons Reference)';return true;\" onmouseout=\"window.status='';return true;\" title='Open Emoticons Reference' onclick=\"%s\">%s</a>",
						$onclick,
						$title
					);
		}

	} else {
		$output="";
	}

	return $output;

}


function snippet_textilepopup($title) {
	global $Cfg, $Weblogs, $Current_weblog, $Paths;

	if ($title=="") { $title= lang('weblog_text', 'textile'); }

	$desc = lang('weblog_text', 'textile_reference');

	if ($Weblogs[$Current_weblog]['comment_textile']==1) {

		$url=$Paths['pivot_url']."includes/textile/overview.html";
		$onclick="window.open('$url','textile','width=350,height=520,directories=no,location=no,menubar=no,scrollbars=no,status=yes,toolbar=no,resizable=no');return false";
		$output="<a href='#' onmouseover=\"window.status='$desc';return true;\" onmouseout=\"window.status='';return true;\" title='$desc' onclick=\"$onclick\">$title</a>";

	} else {
		$output="";
	}

	return $output;

}


function snippet_wastecomment() {

	$output="<input type='button'  style='border: 1px inset black;' value='Waste comment!' onclick=\"this.form.target='_blank'; this.form.action='http://ubique.ch/weblog/waste.pl'; this.form.submit(); if(!document.layers)location.reload()\"> ";

	return $output;

}

function snippet_self() {
	global $db, $Paths;

	return $Paths['host'].make_filelink($db->entry['code'], "","","");

}

function snippet_singlepermalink($text, $title) {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	$link=make_filelink($db->entry['code'], "","","");


	$text = str_replace('%title%', $db->entry['title'], $text);
	$text = str_replace('%subtitle%', $db->entry['subtitle'], $text);
	$text = format_date($db->entry["date"], $text );

	$title = str_replace('%title%', htmlspecialchars($db->entry['title']), $title);
	$title = str_replace('%subtitle%', htmlspecialchars($db->entry['subtitle']), $title);
	$title = format_date($db->entry["date"], $title );

	$code = sprintf("<a href=\"%s%s\" title=\"%s\">%s</a>", $Weblogs[$Current_weblog]['ssi_prefix'], $link, $title ,$text);

	return $code;

}


function snippet_permalink($text, $title) {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	$link=make_archive_link()."#e".$db->entry['code'];

	$text = str_replace('%title%', $db->entry['title'], $text);
	$text = format_date($db->entry["date"], $text );
	$title = str_replace('%title%', htmlspecialchars($db->entry['title']), $title);
	$title = format_date($db->entry["date"], $title );

	$code=sprintf("<a href=\"%s%s\" title=\"%s\">%s</a>", $Weblogs[$Current_weblog]['ssi_prefix'], $link, $title,$text);

	return $code;

}


function snippet_entrylink() {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	$output=make_filelink($db->entry['code'], "", "");

	return $output;
}


function snippet_commentlink() {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	$link=make_filelink($db->entry['code']);

	$commcount=$db->entry['commcount'];

	// special case: If comments are disabled, and there are no
	// comments, just return an empty string..
	if ( ($commcount == 0) &&  ($db->entry['allow_comments'] == 0) )  {
		return "";
	}

	$text = array($Weblogs[$Current_weblog]['comments_text_0'], $Weblogs[$Current_weblog]['comments_text_1'], $Weblogs[$Current_weblog]['comments_text_2']);

	$text = $text[min(2,$commcount)];
	$commcount = lang('numbers', $commcount);
	$commcount = str_replace("%num%", $commcount, $text);
	$commcount = str_replace("%n%", $db->entry['commcount'], $commcount);
	$commnames=$db->entry['commnames'];

	if ($commcount=="") { $commcount="(undefined)"; }

	if ($Weblogs[$Current_weblog]['comment_pop']==1) {

		$target = set_target('', 'target="_self"');

		$output = sprintf("<a href='%s' ", $link);
		$output.= sprintf("onclick=\"window.open('%s', 'popuplink', 'width=%s,height=%s,directories=no,location=no,scrollbars=yes,menubar=no,status=yes,toolbar=no,resizable=yes'); return false\"", $link, $Weblogs[$Current_weblog]['comment_width'], $Weblogs[$Current_weblog]['comment_height']);
		$output.= sprintf(" title=\"%s\" %s>%s</a>",$commnames, $target, $commcount);


	} else {

		$output=sprintf("<a href=\"%s\" title=\"%s\">%s</a>", $link, $commnames, $commcount);

	}

	return $output;
}


function snippet_inlinecommentlink() {
	global $db, $Cfg, $Weblogs, $Current_weblog, $Paths;

	$link=make_filelink($db->entry['code']);

	$commcount=$db->entry['commcount'];

	// special case: If comments are disabled, and there are no
	// comments, just return an empty string..
	if ( ($commcount == 0) && ($db->entry['allow_comments'] == 0) ) {
		return "";
	}

	$text = array($Weblogs[$Current_weblog]['comments_text_0'], $Weblogs[$Current_weblog]['comments_text_1'], $Weblogs[$Current_weblog]['comments_text_2']);
	$text = $text[min(2,$commcount)];
	$commcount = lang('numbers', $commcount);
	$commcount = str_replace("%num%", $commcount, $text);
	$commcount = str_replace("%n%", $db->entry['commcount'], $commcount);
	$commnames=$db->entry['commnames'];

	if ($commcount=="") { $commcount="(undefined)"; }

	$target = set_target('', 'target="_self"');

	$output = sprintf("<scr"."ipt type='text/javascript'>var pivot_url='%s';</scr"."ipt>", $Paths['pivot_url']);
	$output .= sprintf("<a href='%s' ", $link);
	$output.= sprintf("onclick=\"openComments('%s', this); return false\"", $db->entry['code']);
	$output.= sprintf(" title=\"%s\" %s>%s</a>",$commnames, $target, $commcount);


	return $output;
}



function snippet_inlinecomments() {
	global $db;

	$output = "<div id='comments_".$db->entry['code']."'></div>";

	return $output;
}


function snippet_inlinemorelink() {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	if (strlen($db->entry['body'])>5) {

		$link=make_filelink($db->entry['code']);
		$text= ( strlen($Weblogs[$Current_weblog]['read_more']) > 1 ) ? $Weblogs[$Current_weblog]['read_more'] : "(more)";

		$target = set_target('', 'target="_self"');

		$output = sprintf("<scr"."ipt type='text/javascript'>var pivot_url='%s';</scr"."ipt>", $Paths['pivot_url']);
		$output.= sprintf("<a href='%s' ", $link);
		$output.= sprintf("onclick=\"openBody('%s', this); return false\"", $db->entry['code']);
		$output.= sprintf("%s>%s</a>", $target, $text);
		return $output;

	} else {
		return "";
	}

}

function snippet_trackbacks() {
	global $db;

	$output = cms_tag_trackbacks('', '');

	return $output;
}


function snippet_trackbacklink() {
	global $db, $Cfg, $Weblogs, $Current_weblog;

	$link=make_filelink($db->entry['code'],'','track');

	$trackcount=$db->entry['trackcount'];

/* No trackback disabling option yet ...
	// special case: If comments are disabled, and there are no
	// comments, just return an empty string..
	if ( ($commcount == 0) &&  ($db->entry['allow_comments'] == 0) )  {
		return "";
	}
*/

	$text = array($Weblogs[$Current_weblog]['trackbacks_text_0'], $Weblogs[$Current_weblog]['trackbacks_text_1'], $Weblogs[$Current_weblog]['trackbacks_text_2']);
	$text = $text[min(2,$trackcount)];
	$trackcount = lang('numbers', $trackcount);
	$trackcount = str_replace("%num%", $trackcount, $text);
	$trackcount = str_replace("%n%", $db->entry['trackcount'], $trackcount);
	$tracknames=$db->entry['tracknames'];

	if ($trackcount=="") { $trackcount="(undefined)"; }

	if ($Weblogs[$Current_weblog]['comment_pop']==1) {

		$target = set_target('', 'target="_self"');

		$output = sprintf("<a href='%s' ", $link);
		$output.= sprintf("onclick=\"window.open('%s', 'popuplink', 'width=%s,height=%s,directories=no,location=no,scrollbars=yes,menubar=no,status=yes,toolbar=no,resizable=yes'); return false\"", $link, $Weblogs[$Current_weblog]['comment_width'], $Weblogs[$Current_weblog]['comment_height']);
		$output.= sprintf(" title=\"%s\" %s>%s</a>",$tracknames, $target, $trackcount);
	} else {
		$output=sprintf("<a href=\"%s\" title=\"%s\">%s</a>", $link, $tracknames, $trackcount);

	}

	return $output;
}


function snippet_inlinetrackbacklink() {
	global $db, $Cfg, $Weblogs, $Current_weblog, $Paths;

	$link=make_filelink($db->entry['code'],'','track');

	$trackcount=$db->entry['trackcount'];

/*
	// special case: If comments are disabled, and there are no
	// comments, just return an empty string..
	if ( ($commcount == 0) && ($db->entry['allow_comments'] == 0) ) {
		return "";
	}
*/
	$text = array($Weblogs[$Current_weblog]['trackbacks_text_0'], $Weblogs[$Current_weblog]['trackbacks_text_1'], $Weblogs[$Current_weblog]['trackbacks_text_2']);
	$text = $text[min(2,$trackcount)];
	$trackcount = lang('numbers', $trackcount);
	$trackcount = str_replace("%num%", $trackcount, $text);
	$trackcount = str_replace("%n%", $db->entry['trackcount'], $trackcount);
	$tracknames=$db->entry['tracknames'];

	if ($trackcount=="") { $trackcount="(undefined)"; }

	$target = set_target('', 'target="_self"');

	$output = sprintf("<scr"."ipt type='text/javascript'>var pivot_url='%s';</scr"."ipt>", $Paths['pivot_url']);
	$output .= sprintf("<a href='%s' ", $link);
	$output.= sprintf("onclick=\"openComments('%s', this); return false\"", $db->entry['code']);
	$output.= sprintf(" title=\"%s\" %s>%s</a>",$tracknames, $target, $trackcount);


	return $output;
}

function snippet_trackcount() {
	global $db, $global_pref, $Weblogs, $Current_weblog;

	$this_weblog= $Weblogs[$Current_weblog];

	$trackcount=$db->entry['trackcount'];

	if ($trackcount=="") { $trackcount=0; }

	$text = array($Weblogs[$Current_weblog]['trackbacks_text_0'], $Weblogs[$Current_weblog]['trackbacks_text_1'], $Weblogs[$Current_weblog]['trackbacks_text_2']);
	$text = $text[min(2,$trackcount)];
	$trackcount = lang('numbers', $trackcount);
	$trackcount = str_replace("%num%", $trackcount, $text);
	$trackcount = str_replace("%n%", $db->entry['trackcount'], $trackcount);


	return $trackcount;
}






function snippet_tracknames() {
	global $db, $global_pref, $Weblogs, $Current_weblog;

	$this_weblog= $Weblogs[$Current_weblog];

	$tracknames=$db->entry['tracknames'];

	return $tracknames;
}


function snippet_trackautodiscovery() {
	global $db;

	$rdf = "<!-- <rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
xmlns:trackback=\"http://madskills.com/public/xml/rss/module/trackback/\"><rdf:Description
rdf:about=\"%url%\"
dc:identifier=\"%url%\"
dc:title=\"%title%\"
trackback:ping=\"%tb-url%\"/></rdf:RDF> -->";

	$url = gethost() . make_filelink($db->entry['code'], "","","");
	$tb_url  = snippet_pivot_url() . 'tb.php?tb_id=' . $db->entry['code'];

	$rdf = str_replace("%url%", $url, $rdf);
	$rdf = str_replace("%title%", $db->entry['title'], $rdf);
	$rdf = str_replace("%tb-url%", $tb_url, $rdf);

	return $rdf;

}


function snippet_tracklink() {
	global $db, $Weblogs, $Current_weblog;

    if (strlen($Weblogs[$Current_weblog]['trackback_link_format'])>1) {
    	$format = $Weblogs[$Current_weblog]['trackback_link_format'];
    } else {
    	$format = "<p><small>Trackback link: <a href='%url%'>%url%</a>.</small></p>";
    }

	$tb_url = snippet_pivot_url() . 'tb.php?tb_id=' . $db->entry['code'];
    $output = str_replace("%url%", $tb_url, $format);

	return $output;

}


function snippet_inlinemore() {
	global $db;

	$output = "<div id='body_".$db->entry['code']."'></div>";

	return $output;
}


function snippet_commcount() {
	global $db, $global_pref, $Weblogs, $Current_weblog;

	$this_weblog= $Weblogs[$Current_weblog];


	$commcount=$db->entry['commcount'];

	// special case: If comments are disabled, and there are no
	// comments, just return an empty string..
	if ( ($commcount == 0) &&  ($db->entry['allow_comments'] == 0) )  {
		return "";
	}

	$text = array($Weblogs[$Current_weblog]['comments_text_0'], $Weblogs[$Current_weblog]['comments_text_1'], $Weblogs[$Current_weblog]['comments_text_2']);
	$text = $text[min(2,$commcount)];
	$commcount = lang('numbers', $commcount);
	$commcount = str_replace("%num%", $commcount, $text);
	$commcount = str_replace("%n%", $db->entry['commcount'], $commcount);

	return $commcount;
}



function snippet_commnames() {
	global $db, $global_pref, $Weblogs, $Current_weblog;

	$this_weblog= $Weblogs[$Current_weblog];

	$commnames=$db->entry['commnames'];

	return $commnames;
}


// changed for accessibility by JM =*=*= 2004/10/03

function snippet_search( $nobutton='',$button_name='', $placeholder="", $fieldname="" ) {
	global $Paths ;

	$search_formname    = lang( 'accessibility','search_formname' ) ;
	$search_idname      = lang( 'accessibility','search_idname' ) ;

	if ($fieldname!="") {
		$search_fldname = $fieldname;
	} else {
		$search_fldname     = lang( 'accessibility','search_fldname' ) ;
	}

	if ($placeholder!="") {
		$search_placeholder = $placeholder;
	} else {
		$search_placeholder = lang( 'accessibility','search_placeholder' ) ;
	}

	$output  = '<form method="post" action="'.$Paths['pivot_url'].'search.php"  class="pivot-search">'."\n" ;
	$output .= '<fieldset><legend>'.$search_formname.'</legend>'."\n" ;
	$output .= '<label for="'.$search_idname.'">'.$search_fldname.'</label>'."\n" ;
	$output .= '<input id="'.$search_idname.'" type="text" name="search" class="searchbox" value="' ;
	$output .= $search_placeholder.'"  onfocus="this.select();return true;" />'."\n" ;

	if( 'nobutton'!=$nobutton ) {
		if( ''==$button_name ) {
			$button_name = lang( 'weblog_text','search' ) ;
		}
		$output .= '<input type="submit" class="searchbutton" value="'.$button_name.'" />' ;
	}

	$output .= '</fieldset></form>'."\n" ;

	return $output ;
}



function snippet_top() {
	global $db, $global_pref;

	return "";
}

// Tick sets some variables and makes sure the tests are run to check
// if the page is still uptodate.
function snippet_tick() {
	global $Weblogs, $Current_weblog, $Paths, $set_output_paths;

	if (defined('LIVEPAGE')) {
		return "";
	}

	$ext = strtolower(getextension($Weblogs[$Current_weblog]['front_filename']));
	if ( ($ext=="php") || ($ext=="php3") ) {

		if(!isset($set_output_paths) || ($set_output_paths==FALSE)) {
			$set_output_paths=TRUE;
			$output = "<?php \n";
			$output .= "DEFINE('INWEBLOG', TRUE);\n";
			$output .= sprintf("\$log_url='%s';\n ", $Paths['log_url'] );
			$output .= sprintf("\$pivot_url='%s';\n ", $Paths['pivot_url'] );
			$output .= sprintf("\$pivot_path='%s';\n ", $Paths['pivot_path']);
			$output .= sprintf("\$weblog='%s';\n ", $Current_weblog);
			$output .= "include_once '".$Paths['pivot_path']."pv_core.php'; \n?".">";
		}
	} else {
		$output = "";
	}
	return $output;
}


function snippet_home() {
	return get_log_url('index');
}


function snippet_template_dir() {
	global $Paths;

	return $Paths['pivot_url']."templates/";

}


function snippet_pivot_dir() {
	global $Paths;

	return $Paths['pivot_url'];

}

function snippet_pivot_path() {
	global $Paths;

	return $Paths['pivot_path'];

}



function snippet_pivot_url() {
	global $Paths;

	return $Paths['host'].$Paths['pivot_url'];

}


function snippet_log_dir() {
	global $Paths;
	return $Paths['log_url'];
}


function snippet_link_static($titel, $naam) {
 global $global_pref;

 $filename="db/static/".$naam.".htm";

 if (file_exists($filename)) {

  $linkname=str_replace("%1",$naam,$global_pref['file_format']);
  $file="<a href=$linkname>$titel</a>";
 } else {
  $file="<!-- error: could not link $filename. File does not exist -->";
 }

 return $file;
}


function snippet_static($titel) {
 global $global_pref;

 $filename="db/static/".$titel.".htm";

 if (file_exists($filename)) {

  $file=implode("", file("$filename"));
 } else {
  $file="<!-- error: could not include $filename. File does not exist -->";
 }
 return $file;
}

// changed 2004/10/08 =*=*= JM

function snippet_vote( $value,$label,$title='',$total='',$group='' ) {
	global $Paths,$db,$Current_weblog ;

	$url  = $Paths['pivot_url'];
	$url .= 'submit.php?vote='.urlencode( $value );
	$url .= '&amp;piv_code='.$db->entry['code'];
	$url .= '&amp;piv_weblog='.urlencode( $Current_weblog );
	$url .=	'&amp;group='.$group;

	if( '' != $total ) {
		$count = @array_count_values( $db->entry['votes'] ) ;
		$count = isset( $count[$value] ) ? $count[$value] : 0 ;
		$total = str_replace( '%num%',$count,' '.$total ) ;
	}

	$onclick = "window.open('$url','emot','width=200,height=100,directories=no,location=no,menubar=no,scrollbars=no,status=yes,toolbar=no,resizable=no');return false";
	$output = sprintf("<a href='#' onclick=\"%s\" title=\"%s\">%s</a>%s", $onclick, $title, $label, $total);

	return $output;

}

function snippet_karma($value, $label, $label_extra) {


	$title = lang('karma', 'vote');
	$title = str_replace('%val%', lang('karma', $value), $title);

	return snippet_vote($value, $label, $title, '%num% ', "k_");

}

function snippet_message($format="") {
	global $Pivot_Vars;

	if ($format=="") {
		$format="<a id='message'></a><p style='border: 1px dashed #666; padding: 5px;'><b>%message%</b></p>\n\n";
	}

	if (isset($Pivot_Vars['message'])) {
		$message = strip_tags(stripslashes($Pivot_Vars['message']));
		$output = str_replace("%message%", $message, $format);
	} else {
		$output = "";
	}

	return $output;

}


function snippet_close_on_esc() {
	return "<script language='javaScript'>\ndocument.onkeypress = function esc(e) {\nif(typeof(e) == 'undefined') { e=event; }\nif (e.keyCode == 27) { self.close(); }\n}\n</script>\n";
}



function snippet_charset() {
	global $CurrentEncoding;

	if (isset($CurrentEncoding)) {
		return $CurrentEncoding;
	} else {
		return "iso-8859-1";
	}

}

// JM =*=*= 2004/10/04  [1.20b2]
// extended for use in HTML - [[lang:html]] returns, ready for use ' lang="xx"'
// where 'xx' is the 2-letter language code
function snippet_lang( $html='' ) {
	global $Language, $CurrentLanguage, $CurrentEncoding;

	$lang = $Language[$CurrentLanguage]->getName();

	if( ''!=$lang ) {
		$output = $lang ;
	} else {
		$output = 'en' ;
	}
	if( 'html'==$html ) {
		// is there a 2-letter language code set in accessibility?
		// and if so, is it different to 'en'? yes, override.
		$text = lang( 'accessibility','lang') ;
		if(( $text!=$output ) && ( 2==strlen( $text ))) {
			$output = $text ;
		}
		$output = ' lang="'.$output.'"' ;
	}
	return $output ;
}





// cosmetic change 2004/10/08 =*=*= JM

function snippet_blogroll_iframe( $width,$height ) {
	global $Paths,$Weblogs,$Current_weblog ;

	$my_weblog = $Weblogs[$Current_weblog];
	$id = $my_weblog['blogroll_id'];

	$fg = (strlen($my_weblog['blogroll_fg'])>0) ? $my_weblog['blogroll_fg'] : "#000";
	$bg = (strlen($my_weblog['blogroll_bg'])>0) ? $my_weblog['blogroll_bg'] : "#FFF";
	$line1 = (strlen($my_weblog['blogroll_line1'])>0) ? $my_weblog['blogroll_line1'] : "#EEE";
	$line2 = (strlen($my_weblog['blogroll_line2'])>0) ? $my_weblog['blogroll_line2'] : "#EEE";
	$c1 = (strlen($my_weblog['blogroll_c1'])>0) ? $my_weblog['blogroll_c1'] : "#90A8BF";
	$c2 = (strlen($my_weblog['blogroll_c2'])>0) ? $my_weblog['blogroll_c2'] : "#ACBECF";
	$c3 = (strlen($my_weblog['blogroll_c3'])>0) ? $my_weblog['blogroll_c3'] : "#C8D4DF";
	$c4 = (strlen($my_weblog['blogroll_c4'])>0) ? $my_weblog['blogroll_c4'] : "#E3E9EF";

	$colors = urlencode(implode( '|',array( $fg,$bg,$line1,$line2,$c1,$c2,$c3,$c4 )));

	$output  = '<iframe src="'.$Paths['pivot_url'];
	$output .= 'includes/blogroll.php?id='.$id;
	$output .= '&amp;color='.$colors.'" width="'.$width.'" height="'.$height;
	$output .= '" frameborder="0" style="border:1px;margin:0;"></iframe>';

	return $output;
}

function snippet_rss($rssurl, $rssmax, $rssformat, $trimlen="") {
	global $Paths, $set_output_paths;

	if ($rssmax=="") { $rssmax=8; }
	if ($rssformat=="") {
		$rssformat="<b><a href=\"%link%\">%title%</a></b><br /> <small>%description%</small><br />";
	}

	if ($trimlen=="") { $trimlen = 100; }

	if (defined('LIVEPAGE')) {

		ob_start();
		include($Paths['pivot_path']. "includes/rss.php");
		$output = ob_get_contents();
		ob_end_clean();

	} else {

		$output = "<?php \n\$rssurl=\"$rssurl\";\n";
		$output .= "\$rssmax=$rssmax;\n";
		$output .= "\$trimlen=$trimlen;\n";
		$output .= "\$rssformat=\"".addslashes($rssformat)."\";\n";
		if(!isset($set_output_paths) || ($set_output_paths==FALSE)) {
			$set_output_paths=TRUE;
			$output .= sprintf("DEFINE('INWEBLOG', TRUE);\n ");
			$output .=sprintf("\$log_url='%s';\n ", $Paths['log_url'] );
			$output .=sprintf("\$pivot_url='%s';\n ", $Paths['pivot_url'] );
			$output .= sprintf("\$pivot_path='%s';\n ", $Paths['pivot_path']);
			$output .= sprintf("\$weblog='%s';\n ", $Current_weblog);
		}
		$output.= "include \"".$Paths['pivot_path']."includes/rss.php\"; ?".">";
	}
	return $output;
}



?>
