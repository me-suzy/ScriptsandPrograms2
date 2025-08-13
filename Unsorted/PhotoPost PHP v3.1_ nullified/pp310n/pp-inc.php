<?
//////////////////////////// COPYRIGHT NOTICE //////////////////////////////
// Program Name  	 : PhotoPost PHP                                  //
// Program Version 	 : 3.1                                            //
// Contributing Developer: Michael Pierce                                 //
// Supplied By           : Goshik [WTN]                                   //
// Nullified By          : CyKuH [WTN]                                    //
//  This script is part of PhotoPost PHP, a software application by       //
// All Enthusiast, Inc.  Use of any kind of part or all of this           //
// script or modification of this script requires a license from All      //
// Enthusiast, Inc.  Use or modification of this script without a license //
// constitutes Software Piracy and will result in legal action from All   //
//                                                                        //
//           PhotoPost Copyright 2002, All Enthusiast, Inc.               //
//                       Copyright WTN Team`2002                          //
////////////////////////////////////////////////////////////////////////////
include("config-inc.php");

$magic = get_magic_quotes_gpc();

// --------------------------------
// Register the global variables
   if (is_array($HTTP_GET_VARS)) {
     while(list($key,$value) = each($HTTP_GET_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }
   if (is_array($HTTP_POST_VARS)) {
     while(list($key,$value) = each($HTTP_POST_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }
   if (is_array($HTTP_COOKIE_VARS)) {
     while(list($key,$value) = each($HTTP_COOKIE_VARS)) {
        if ($magic) {
           $value = stripslashes($value);
        }
        ${$key} = $value;
     }
   }

// ---------------------------
// Turn off the magic quoting
   set_magic_quotes_runtime(0);

   error_reporting (E_ALL ^ E_NOTICE);

//===========================================================

// Connecting, selecting database
$link = mysql_connect("$host:3306", "$mysql_user", "$mysql_password") or die ('I cannot connect to the PhotoPost database. [$php_errormsg]');
mysql_select_db ("$database",$link)or die("Could not connect to PhotoPost database". mysql_error() );

$db_link = mysql_connect("$host_bb", "$user_bb", "$password_bb") or die ('I cannot connect to the Members database. [$php_errormsg]');
mysql_select_db ("$database_bb",$db_link)or die("Could not connect to User database". mysql_error() );

$query = "SELECT varname,setting FROM settings";
$getsets = mysql_query($query,$link);

if ( !$getsets ) {
    print "Database not properly setup. Contact administrator.";
    exit;
}

while ( list($var, $set) = mysql_fetch_row($getsets)) {
    $Globals[$var] = stripslashes($set);
}
mysql_free_result($getsets);

// These variables come from the config-inc.php, placed here so in the Global array
$Globals{'zip_command'} = $zip_command;
$Globals{'mogrify_command'} = $mogrify_command;
$Globals{'cookie_path'} = $cookie_path;
$Globals{'debug'} = $debug;
$Globals{'botbuster'} = $botbuster;
$Globals{'usegd'} = $usegd;
$Globals{'dateformat'} = $dateformat;
$Globals{'onthefly'} = $onthefly;
$Globals{'mainfonts'} = $mainfonts;
$Globals{'fontsmall'} = $fontsmall;
$Globals{'fontmedium'} = $fontmedium;
$Globals{'fontlarge'} = $fontlarge;
$Globals{'ppboards'} = $ppboards;
$Globals{'gmtoffset'} = $gmtoffset;
$Globals{'ipcache'} = $ipcache;

if ( $usegd != 0 ) {
    if ( !extension_loaded('gd')) {
        if (!dl('gd.so')) {
        dieWell( "GD support enabled; but not installed on your server.<br>Please contact System Administrator." );
            exit;
        }
    }
}

// need to fix a couple variables to prevent problems
$Globals{'maindir'} = trim( $Globals{'maindir'} );

// overrides; this is mostly for testing purposes
if ( file_exists("globals-over.inc") ) {
    $filearray = file( "globals-over.inc" );

    while ( list($num, $line) = each($filearray) ) {
        if ($line != "") {
            $vars = explode( "=", $line);
            $var = $vars[0]; $set = $vars[1];
            $Globals[$var] = trim($set);
        }
    }
}

// If they want compression, enable it!
if ( $compression == "1" && !isset($disableforadmin) ) {
    $phpa = phpversion();
    $phpv = $phpa[0] . "." . $phpa[2] . $phpa[4];
    if (($phpv > 4.0004) && extension_loaded("zlib") && !ini_get("zlib.output_compression") && !ini_get("output_handler")) {
        ob_start("ob_gzhandler");
    }
}

$headeropen=$Globals{'header'};
$footeropen=$Globals{'footer'};
$headtagsopen=$Globals{'headtags'};

// Read in the header tags file
if ( file_exists($headtagsopen) ) {
    $filearray = file($headtagsopen);
    $headtags = implode( " ", $filearray );
}
else
    $headtags = "";

// read in the header (or vbheader file)
if ( !empty($vbheader) ) {
        $theader = $vbheader;
        $theader = str_replace( "{tablewidth}", $Globals{'tablewidth'}, $theader);
        $theader = str_replace( "{pagebgcolor}", $Globals{'forwardbody'}, $theader);
}
elseif ( file_exists($headeropen) ) {
    $filearray = file($headeropen);
    $theader = implode( " ", $filearray );
}
else
    $theader="";

// read in the footer (or the vbfooter file)
if ( !empty($vbfooter) ) {
        $footer = $vbfooter;
}
elseif ( file_exists($footeropen) ) {
    $filearray = file($footeropen);
    $footer = implode( " ", $filearray );
}
else
    $footer="";

$footer .="</center></html>";

if ( $Globals{'cjurl'} != "" ) {
    if ( $Globals{'cjurl'} != "http://www.qksrv.net" ) {
        $Globals{'cright'} = str_replace( "$HTTP_SERVER_VARS[HTTP_HOST]", $Globals{'cjurl'}, $Globals{'cright'} );
    }
}

$Globals{'cright'} = str_replace( "--replaceme--", "PHP 3.1.0", $Globals{'cright'} );

$nocachetag = "<!-- no cache -->
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
        <meta http-equiv=\"Pragma\" content=\"no-cache\">
        <meta name=\"description\" content=\"WTN Team `2002\">
        <meta http-equiv=\"Expires\" content=\"-1\">
        <meta http-equiv=\"Cache-Control\" content=\"no-cache\">
        <!-- ok -->";
        
$javapopup = "<script type=\"text/javascript\">
var PopUpHelpX = (screen.width/2)-150;
var PopUpHelpY = (screen.height/2)-200;
var pos = \"left=\"+PopUpHelpX+\",top=\"+PopUpHelpY;
function PopUpHelp(url){
PopUpHelpWindow = window.open(\"".$Globals{'maindir'}."/help/\"+url,\"Smilies\",\"scrollbars=yes,width=300,height=400,\"+pos);
}
</script>";

$header = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n
    <html>
    <head>
    <title>".$Globals{'galleryname'}."</title>\n
    $nocachetag\n
    $headtags\n
    $javapopup\n
    </head>
    $theader";

$username=""; $userid=""; $menu=""; $posternav="";

// handler to hand all mysql_queries
function ppmysql_query( $query, $database ) {
    global $Globals;

    $mysql_eval_error="";
    $mysql_eval_result = mysql_query($query, $database) or $mysql_eval_error = mysql_error();
    
    if ($mysql_eval_error) {
        if ( $Globals{'debug'} == 1 ) {
            $letter = "An error was encountered during execution of the query:\n\n";
            $letter .= $query."\n\n";
            $letter .="The query returned with an errorcode of: \n\n$mysql_eval_error\n\n";
            $letter .= "If you need assistence or feel this is a 'bug' : please help  itself =) ";
            $letter .= ".\n\n";
            $letter .= "To turn off these emails, set \$debug=0 in your config-inc.php file.";

            $email = $Globals{'adminemail'};
            $email_from = "From: ".$Globals{'adminemail'};

            $subject="Subject: ".$Globals{'webname'}." MySQL Error Report";
            $subject=trim($subject);

            mail( $email, $subject, $letter, $email_from );
        }
        elseif ( $Globals{'debug'} == 2 ) {
            dieWell( "MySQL error reported!<p>Query: $query<p>Result: $mysql_eval_error<p>Database handle: $database" );
            exit;
        }
        return FALSE;
    }
    else {
        return $mysql_eval_result;
    }
}


function ppmysql_free_result( $result ) {
    return @mysql_free_result($result);
}


function get_ext( $filename ) {
    $photolen = strlen($filename);
    $RetVal = substr( $filename, $photolen-3, $photolen);

    return $RetVal;
}

function get_filename($filename) {
    // strip off the last 4
    $len = strlen( $filename )-4;
    $RetVal = substr( $filename, 0, $len);
    return $RetVal;
}

function is_image_private($catid) {
    global $link;
    
    $isprivate = "no";
    
    if ( $catid > 2999 ) {
        $query = "SELECT isprivate FROM useralbums WHERE id=$catid";
        $boards = ppmysql_query($query,$link);
        list( $isprivate ) = mysql_fetch_row($boards);
        ppmysql_free_result($boards);
    }
    
    return $isprivate;
}


function display_gallery($q_switch, $showuser="", $showcat="") {
    global $Globals, $link, $output, $cat, $exclude_cat, $si;

    $rcols=$Globals['recentcols'];
    $rphotos=$Globals['recentnum'];
    $catquery="";

    if ($rcols < 0) $rcols=4;
    if ($rcols > 12) $rcols=4;
    if ($rphotos < 0) $rphotos=12;
    if ($rphotos > 100) $rphotos=12;

    $twidth = intval(100/$rcols);

    if ( $cat < 3000 )
        $querya="SELECT catname FROM categories WHERE id='$cat'";
    else
        $querya="SELECT albumname FROM useralbums WHERE id='$cat'";

    $catq = ppmysql_query($querya,$link);
    list( $catname ) = mysql_fetch_row($catq);
    ppmysql_free_result( $catq );

    if ( $cat == "500" ) {
        if ( $si == "" ) {
            $catquery = "500";
        }
        else {
            $queryb = "SELECT id FROM categories ORDER BY catorder ASC";
            $boards = ppmysql_query($queryb, $link);
            $cnt=0;
            while ( list( $catqid ) = mysql_fetch_row($boards) ) {
                if ( $cnt == 0 ) {
                    $cnt=1;
                    $catquery .= "$catqid";
                }
                else {
                    $catquery .= ",$catqid";
                }
            }
            ppmysql_free_result($boards);
        }
    }
    else {
        if ( $showcat > 2999 ) {
            $catquery="$showcat";
        }
        else {
            $catquery = "$cat";

            $queryb = "SELECT id FROM categories WHERE parent='$cat' ORDER BY catorder ASC";
            $boards = ppmysql_query($queryb,$link);

            while ( list( $catqid ) = mysql_fetch_row($boards)) {
                $catquery .= ",$catqid";
            }
            ppmysql_free_result( $boards );
        }
    }

    $userq = "";
    if ( $showuser != "" ) {
        $userq = " AND userid=$showuser";

        list( $tcat, $tmail ) = get_username($showuser);
        $catname = "$tcat's $catname";
    }

    switch ($q_switch) {
        case "most_views":
            if ( !IsSet($cat) ) {
                $group_title = "Most Popular Images - All Categories";
                $query = "SELECT id,user,userid,cat,title,bigimage,views FROM photos WHERE cat < 3000 AND bigimage!='' AND approved='1' $exclude_cat ORDER BY views DESC";
                break;
            }
            else {
                $group_title = "Most Popular Images - $catname";
                $query = "SELECT id,user,userid,cat,title,bigimage,views FROM photos WHERE bigimage!='' AND approved='1' $userq $exclude_cat AND cat in ($catquery) ORDER BY views DESC";
            }
            break;

        case "latest":
            if ( !IsSet($cat) ) {
                $group_title = "Most Recent Images - All Categories";
                $query = "SELECT id,user,userid,cat,title,bigimage,views FROM photos WHERE cat < 3000 AND bigimage!='' AND approved='1' $exclude_cat ORDER BY date DESC";
                break;
            }
            else {
                $query = "SELECT id,user,userid,cat,title,bigimage,views FROM photos WHERE bigimage!='' AND approved='1' $userq $exclude_cat AND cat in ($catquery) ORDER BY date DESC";
                $group_title = "Most Recent Images - $catname";
                break;
            }
            break;

        default:
            if ( !IsSet($cat) ) {
                $group_title = "Random Images - All Categories";
                $query = "SELECT id,user,userid,cat,title,bigimage,views FROM photos WHERE cat < 3000 AND bigimage!='' AND approved='1' $exclude_cat ORDER BY RAND() DESC LIMIT $rphotos";
                break;
            }
            else {
                $group_title = "Random Images - $catname";
                $query = "SELECT id,user,userid,cat,title,bigimage,views FROM photos WHERE bigimage!='' AND approved='1' $userq $exclude_cat AND cat in ($catquery) ORDER BY RAND() DESC LIMIT $rphotos";
                break;
            }
            break;
    }

    $result = ppmysql_query($query,$link);
    $numimages = mysql_num_rows( $result );

    // lets clean up the displays; only show if the number of images is under the threshold
    // or as long as there is at least 1 row worth of images
    if ( $numimages < $rcols ) return;

    $output .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\" width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
        <table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\">
        <tr align=\"center\">
        <td colspan=\"$rcols\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\">
        <font size=\"".$Globals{'fontmedium'}."\" face=\"".$Globals{'mainfonts'}."\" color=\"".$Globals{'headfontcolor'}."\"><b>$group_title</b></font>
        </td></tr>
        <Tr align=\"center\">";

    $count=0;

    if ( $result ) {
        for ( $totali=0; $totali < $rphotos; $totali++ ) {
            unset($line);
            $line = mysql_fetch_array($result);

            if ( !empty($line) ) {
                $is_private = is_image_private( $line['cat'] );
                
                if ( $is_private == "no" ) {                
                    $output .= "<td width=\"$twidth%\" align=\"center\" valign=\"middle\" bgcolor=\"".$Globals{'maincolor'}."\">
                        <Font size=\"".$Globals{'fontsmall'}."\"color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\">
                        <A href=\"".$Globals{'maindir'}."/showphoto.php?photo=".$line['id']."\">";
    
                    $imgtag = get_imagethumb( $line['bigimage'], $line['cat'], $line['userid'], 1 );
    
                    $output .= "$imgtag<Br>".$line['title']."</a>\n<Br>by ".$line['user']."</font>";
                    $count++;
    
                    if ( $count == $rcols && (($totali+1) != $rphotos) ) {
                        $output .= "</td></tr><tr>";
                        $count = 0;
                    }
                    else {
                        $output .="</td>";
                    }
                }
            }
            
            if ( $count == $rphotos ) break;
        }
        ppmysql_free_result($result);        
    }

    if ( $count < $rcols ) {
        while ($count != $rcols) {
            $output .= "<td valign=\"middle\" bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\" width=\"$twidth%\">&nbsp;</td>";
            $count++;
        }
    }

    $output .= "</tr></table>";
    $output .= "</td></tr></table><p>";
}


function catlist( $parid, $reiter=0 ) {
    global $Globals, $link, $cathash;

    $query = "SELECT id FROM categories WHERE parent=$parid";
    $boards = ppmysql_query($query,$link);

    if ( $reiter == 0 ) $cathash = "cat='$parid'";

    while ( list ( $tid ) = mysql_fetch_row($boards) ) {
        $cathash = $cathash." OR cat='$tid'";
        catlist( $tid, 1 );
    }
    ppmysql_free_result( $boards );

    return ( $cathash );
}


function catrow( $parent ) {
    global $Globals, $link, $catrows, $catdepth, $cptotal, $posttotal, $totalviews, $diskspace, $headdone, $output;
    global $ugview, $userid, $totalphotos;

    if ( $Globals{'catdepth'} == 0 && $parent == 0 ) {
        // this one gets through
    }
    elseif ( $Globals{'catdepth'} == 0 && $parent == 0 ) {
        return;
    }
    elseif ( $catdepth > $Globals{'catdepth'} )
        return;

    $query = "SELECT id,catname,description,catorder,parent,thumbs FROM categories WHERE parent='$parent' ORDER BY catorder ASC";
    $boards = ppmysql_query($query,$link);
    $posts = mysql_num_rows($boards);

    $indent = "";
    for( $x=0; $x < $catdepth; $x++ ) {
        $indent .="<img height=\"1\" width=\"15\" src='".$Globals{'idir'}."/spacer.gif' alt=\"\">";
    }

    while ( list( $id, $catname, $catdesc, $order, $catparent, $catthumbs ) = mysql_fetch_row($boards) ) {
        // If we're dealing with the member galleries, count all the photos and comments.  Otherwise,
        // just count the photos and comments for this particular category.

        if ( $catthumbs == "yes" ) {
            $dodetails = 1;
        }
        else {
            if ($Globals{'catdetails'} == "yes") $dodetails = 1;
            else $dodetails = 0;
        }

        $lastposttime=""; $lastpostby=""; $lastpostlink="";
        $lastphoid=""; $lastphoby=""; $lpholink="";
        $newphotos=""; $newcomments=""; $laston=0;

        if ($dodetails == "1") {
            $quid=""; $cphotos=0; $catposts=0;

            if ( $id != "500" ) {
                $quid = catlist( $id );

                if ( $quid != "" && $quid != "cat=" ) {
                    $query = "SELECT id,user,userid,date FROM photos WHERE $quid ORDER BY date DESC";
                    $lastp = ppmysql_query($query, $link);
                    $cphotos = mysql_num_rows($lastp);
                    list( $lastphoid, $lastphoby, $lastphobyid, $lastphotime ) = mysql_fetch_row($lastp);
                    ppmysql_free_result($lastp);

                    $query = "SELECT username,id,date,photo FROM comments WHERE $quid ORDER BY date DESC";
                    $lastc = ppmysql_query($query, $link);
                    $catposts = mysql_num_rows($lastc);
                    list( $lastpostby, $lastpostbyid, $lastposttime, $lastpostlink ) = mysql_fetch_row($lastc);
                    ppmysql_free_result($lastc);
               }
            }
            else {
                if ( $Globals{'memformat'} == "no" ) {
                    $query1 = "SELECT id,user,userid,date FROM photos WHERE cat=500 ORDER BY date DESC";  
                    $query2 = "SELECT username,id,date,photo FROM comments WHERE cat=500 ORDER BY date DESC";
                }
                else {
                    $query1 = "SELECT id,user,userid,date FROM photos ORDER BY date DESC";  
                    $query2 = "SELECT username,id,date,photo FROM comments ORDER BY date DESC";                    
                }
                                    
                $lastp = ppmysql_query($query1,$link);
                $cphotos = mysql_num_rows($lastp);         
                list( $lastphoid, $lastphoby, $lastphobyid, $lastphotime ) = mysql_fetch_row($lastp);
                ppmysql_free_result($lastp);

                $lastc = ppmysql_query($query2,$link);
                $catposts = mysql_num_rows($lastc);
                list( $lastpostby, $lastpostbyid, $lastposttime, $lastpostlink ) = mysql_fetch_row($lastc);
                ppmysql_free_result($lastc);

                $quid = "cat='500'";                
            }

            // check to see if we want to display new posts/comments icon
            if ( $userid > 0 && ($quid != "" && $quid != "cat=") ) {
                $query = "SELECT laston FROM laston WHERE cat=$id AND userid=$userid ORDER BY laston DESC LIMIT 1";
                $laste = ppmysql_query($query, $link);
 
                if ( $laste ) {
                    list( $laston ) = mysql_fetch_row($laste);                        
                    ppmysql_free_result($laste);
                    
                    if ( $laston > 0 ) {
                        $query = "SELECT id,date FROM photos WHERE cat=$id AND date > $laston";
                        $isonp = ppmysql_query($query, $link);
                        $isonphoto = mysql_num_rows($isonp);

                        if ( $isonphoto > 0 ) 
                            $newphotos = "<font size=\"1\"><i>($isonphoto)</i></font>";
    
                        $query = "SELECT id,date FROM comments WHERE cat=$id AND date > $laston";
                        $isonc = ppmysql_query($query, $link);
                        $isoncomment = mysql_num_rows($isonc);
    
                        if ( $isoncomment > 0 ) 
                            $newcomments = "<font size=\"1\"><i>($isoncomment)</i></font>";
                    }
                }
            }

            $lpholink="";
            $lpprint = "no photos";

            if ($lastphoid != "" && $lastphotime != "") {
                if ( $isonphoto > 0 || $laston == 0 )
                    $lpholink = "<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$lastphoid\"><img border=\"0\" src=\"".$Globals{'idir'}."/lastpost.gif\" alt=\"View last photo posted\"></a>";                
                else
                    $lpholink = "<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$lastphoid\"><img border=\"0\" src=\"".$Globals{'idir'}."/nonewposts.gif\" alt=\"View last photo posted\"></a>";
                    
                $cclock = formatpptime( $lastphotime );
                $ppdate = formatppdate( $lastphotime );

                $lpprint = "$ppdate $cclock";
            }

            if ($lastpostby != "") $lastposttext = "by $lastpostby";
            else $lastposttext = "";

            if ($lastphoby != "") $lastphotext = "by $lastphoby";
            else $lastphotext = "";

            if ( $lastpostlink != "" ) {
                if ( $isoncomment > 0 || $laston == 0 ) {
                    $lpostlink="<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$lastpostlink\"><img border=\"0\" src=\"".$Globals{'idir'}."/lastpost.gif\" alt=\"View last comment posted\"></a>";
                }
                else {
                    $lpostlink="<a href=\"".$Globals{'maindir'}."/showphoto.php?photo=$lastpostlink\"><img border=\"0\" src=\"".$Globals{'idir'}."/nonewposts.gif\" alt=\"View last comment posted\"></a>";
                }
            }
            else
                $lpostlink="";

            if ($lastposttime != "") {
                $cclock = formatpptime( $lastposttime );
                $ppdate = formatppdate( $lastposttime );

                $lcprint = "$ppdate $cclock";
            }
            else {
                $lcprint="no comments";
            }

            if ( IsSet($thumb) ) {
                if ($headdone != "1") {
                    $catrows .= "<tr align=\"center\">
                        <Td align=\"left\"   bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Category</b></font></td>
                        <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Photos</b></font></td>
                        <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Comments</b></font></td>
                        <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><b>Last Comment</b></font></td>
                        <!-- CyKuH [WTN] -->
                        <Td align=\"center\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\" color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontsmall'}."\"><B>Last Photo Upload</b></font></td>
                        </tr>";
                    $headdone=1;
                }
            }

            if ($catthumbs == "yes") {
                if ($ugview{$id} != 1 && !($id == "500" && $Globals{'showmem'} == "no") ) {
                    $catrows .= "<Tr><Td width=\"45%\" bgcolor=\"".$Globals{'maincolor'}."\">
                        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'catfontsize'}."\">$indent<b>
                        <A href=\"".$Globals{'maindir'}."/showgallery.php?cat=$id&amp;thumb=1\">$catname</a></b></font><br>
                        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'maintext'}."\">$indent$catdesc</font></td>
                        <Td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\"><font color=\"".$Globals{'maintext'}."\" face=\"".$Globals{'mainfonts'}."\"
                        size=\"".$Globals{'fontmedium'}."\">$cphotos $newphotos</font></td><Td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\"><font
                        face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\">$catposts $newcomments</font></td>
                        <Td bgcolor=\"".$Globals{'maincolor'}."\">
                        <Table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                        <Tr><Td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'maintext'}."\">$lcprint<Br>
                        $lastposttext</font></td>
                        <Td>$lpostlink</td>
                        </tr></table></td>
                        <Td bgcolor=\"".$Globals{'maincolor'}."\"><Table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><Tr><Td><font
                        face=\"".$Globals{'mainfonts'}."\"
                        size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'maintext'}."\">$lpprint<Br>$lastphotext</font></td><Td>$lpholink</td>
                        </tr></table></td></tr>";

                    $catdepth++;
                    catrow($id);
                    $catdepth--;
                }
            }
            else {
                if ($ugview{$id} != 1 ) {
                    $catrows .= "<Tr><Td width=\"45%\" bgcolor=\"".$Globals{'topcatcolor'}."\"><font face=\"".$Globals{'mainfonts'}."\"
                        size=\"".$Globals{'catfontsize'}."\">$indent<b><A
                        href=\"".$Globals{'maindir'}."/index.php?cat=$id\"><font face=\"".$Globals{'mainfonts'}."\"
                        size=\"".$Globals{'fontmedium'}."\"
                        color=\"".$Globals{'catnavcolor'}."\">$catname</font></a></b></font><br>
                        <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'topcatfcolor'}."\">$indent$catdesc</font></td><Td
                        bgcolor=\"".$Globals{'topcatcolor'}."\" align=\"center\"><font color=\"".$Globals{'topcatfcolor'}."\" face=\"".$Globals{'mainfonts'}."\"
                        size=\"".$Globals{'fontmedium'}."\">$cphotos $newphotos</font></td><Td bgcolor=\"".$Globals{'topcatcolor'}."\" align=\"center\"><font
                        face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'topcatfcolor'}."\">$catposts $newcomments</font></td>
                        <Td bgcolor=\"".$Globals{'topcatcolor'}."\"><Table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                        <Tr><Td><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'topcatfcolor'}."\">$lcprint<Br>
                        $lastposttext</font></td><Td>$lpostlink</td></tr></table></td>
                        <Td bgcolor=\"".$Globals{'topcatcolor'}."\"><Table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><Tr><Td><font
                        face=\"".$Globals{'mainfonts'}."\"
                        size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'topcatfcolor'}."\">$lpprint<Br>$lastphotext</font></td><Td>$lpholink</td>
                        </tr></table></td></tr>";

                    $catdepth++;
                    catrow($id);
                    $catdepth--;
                }
            }
        }
        else {
            if ($ugview{$id} != 1) {
                $catrows .= "<Tr><Td width=\"100%\" colspan=\"5\" bgcolor=\"".$Globals{'topcatcolor'}."\"><font
                    face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'catfontsize'}."\">$indent<b><A
                    href=\"".$Globals{'maindir'}."/index.php?cat=$id\"><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"
                    color=\"".$Globals{'catnavcolor'}."\">$catname</a></b></font><br>
                    <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\" color=\"".$Globals{'searchtext'}."\">$indent$catdesc</font></td></tr>";

                $catdepth++;
                catrow($id);
                $catdepth--;
            }
        }
    }
    ppmysql_free_result($boards);

    return;
} // end sub catrow

function thetime($inhour,$inmin) {
    if ($inmin < 10) {
        $inmin="0$inmin";
    }
    if ($inhour == 0) {
        $inhour = str_replace("0", "12", $inhour);
        $outclock=$inhour.":".$inmin."am";
        return($outclock);
    }
    else {
        if ($inhour < 10) {
            $inhour = str_replace("0", "", $inhour);
        }
    }
    if ($inhour > 11) {
        if ($inhour != 12) {
            $inhour=$inhour-12;
        }
        $outclock=$inhour.":".$inmin."pm";
    }
    else {
        $outclock=$inhour.":".$inmin."am";
    }

    return($outclock);
}


function gmttime($dTimestamp = '', $bAssoc = 0) {
	// Evaluate how much difference there is between local and GTM/UTC
	// Don't forget to correct for daylight saving time...
	$aNow = localtime();
	$iDelta = gmmktime(1, 1, 1, 1, 1, 1970, $aNow[8]) - mktime(1, 1, 1, 1, 1, 1970, $aNow[8]);

	if (!$bAssoc) {
		if ($dTimestamp == '') {
			return localtime(time() - $iDelta, 0);
		} else {
			return localtime($dTimestamp - $iDelta, 0);
		}
	} else {
		// For associative array, add full year index
		if ($dTimestamp == '') {
			$aGMTTime = localtime(time() - $iDelta, 1);
		} else {
			$aGMTTime = localtime($dTimestamp - $iDelta, 1);
		}
		$aGMTTime['tm_fyear'] = $aGMTTime['tm_year'] + 1900;
		return $aGMTTime;
	}
}


function formatpptime( $date ) {
    global $Globals, $userid, $db_link;

    $offset = 0;

    if ( $userid > 0 ) {
        if ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
            $query = "SELECT U_TimeOffset FROM w3t_Users WHERE U_Number='$userid'";
        }
        elseif ($Globals{'vbversion'} == "phpBB2") {
            if ( !empty($Globals{'dprefix'}) ) {
                $utable=$Globals{'dprefix'} ."_users";
            }
            else {
                $utable="users";
            }
    
            $query = "SELECT user_timezone FROM $utable WHERE user_id='$userid'";
        }
        elseif ($Globals{'vbversion'} == "phpBB") {
            $query = "SELECT user_timezone FROM users WHERE user_id='$userid'";
        }
        elseif ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
            $query = "SELECT timezoneoffset FROM user WHERE userid='$userid'";
            
            list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = gmttime($date);
            $mon = $mon + 1;    
            $date = mktime($hour,$min,$sec,$mon,$mday,$year);        
        }
        else {
            $query = "SELECT offset FROM users WHERE userid='$userid'";
        }
    
        $queryv = ppmysql_query($query, $db_link);

        if ( $queryv ) {
            list( $offset ) = mysql_fetch_row($queryv);
            ppmysql_free_result($queryv);
        }
    }
    else {
        $offset = $Globals{'gmtoffset'};
    }

    $date = $date + ($offset * 3600);
    list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($date);

    $cclock = thetime($hour,$min);

    return( $cclock );
}


function formatppdate( $date ) {
    global $Globals;

    list($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime($date);
    $mon++;
    $year=1900+$year;

    $months = array( "","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec" );
    $days = array( "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" );

    if ( isset($Globals{'ppdateformat'}) )
        $glob_date = $Globals{'ppdateformat'};
    else
        $glob_date = "dow month dd, yyyy";

    $glob_date = str_replace( "mm", "$mon", $glob_date );
    $glob_date = str_replace( "dd", "$mday", $glob_date );
    $glob_date = str_replace( "yyyy", "$year", $glob_date );
    $glob_date = str_replace( "month", "$months[$mon]", $glob_date );
    $glob_date = str_replace( "dow", "$days[$wday]", $glob_date );

    return( $glob_date );
}


function get_username( $user ) {
    global $Globals, $db_link;

    if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
        $query = "SELECT userid,username,email FROM user WHERE userid='$user'";
    }
    if ($Globals{'vbversion'} == "phpBB") {
        $query = "SELECT user_id,username,user_email FROM users WHERE user_id='$user'";
    }
    if ($Globals{'vbversion'} == "phpBB2") {
        if ( !empty($Globals{'dprefix'}) ) {
            $utable=$Globals{'dprefix'} ."_users";
        }
        else {
            $utable="users";
        }

        $query = "SELECT user_id,username,user_email FROM $utable WHERE user_id='$user'";
    }
    if ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
        $query = "SELECT U_Number,U_Username,U_Email FROM w3t_Users WHERE U_Number='$user'";
    }
    if ($Globals{'vbversion'} == "Internal") {
        $query = "SELECT userid,username,email FROM users WHERE userid='$user'";
    }

    $queryv = ppmysql_query($query,$db_link);
    $results = mysql_fetch_row($queryv);

    //need to modify to return both username and email
    $username[0] = $results[1];
    $username[1] = $results[2];
    //list( $username, $email ) = get_username( $user );
    
    ppmysql_free_result($queryv);

    return( $username );
}


function get_totalusers() {
    global $Globals, $db_link;

    if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
        $query = "SELECT * FROM user";
    }
    if ($Globals{'vbversion'} == "phpBB") {
        $query = "SELECT * FROM users";
    }
    if ($Globals{'vbversion'} == "phpBB2") {
        if ( !empty($Globals{'dprefix'}) ) {
            $utable=$Globals{'dprefix'} ."_users";
        }
        else {
            $utable="users";
        }

        $query = "SELECT * FROM $utable";
    }
    if ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
        $query = "SELECT * FROM w3t_Users";
    }
    if ($Globals{'vbversion'} == "Internal") {
        $query = "SELECT * FROM users";
    }

    $queryv = ppmysql_query($query, $db_link);
    $totalusers = mysql_num_rows($queryv);

    return( $totalusers );
}


function get_profilelink( $tuserid ) {
    global $Globals, $db_link, $postreply, $privatelink;
    
    if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
        $profilelink = $Globals{'vbulletin'}."/member.php?action=getinfo&amp;userid=$tuserid";
        $privatelink = $Globals{'vbulletin'}."/private.php?action=newmessage&amp;userid=$tuserid";
        $postreply = "<img src=\"".$Globals{'vbulletin'}."/images/reply.gif\" border=\"0\" alt=\"Post A Reply\">";
    }
    if ($Globals{'vbversion'} == "Internal") {
        $profilelink = $Globals{'maindir'}."/member.php?ppaction=profile&amp;uid=$tuserid";
        $postreply = "<font color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><b>Post a Reply</b></font>";
    }
    if ($Globals{'vbversion'} == "phpBB") {
        $profilelink = $Globals{'vbulletin'}."/bb_profile.php?mode=view&amp;user=$tuserid";
        $postreply = "<font color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><b>Post a Reply</b></font>";
    }
    if ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
        $query = "SELECT U_Username FROM w3t_Users WHERE U_Number=$tuserid LIMIT 1";
        $queryv = ppmysql_query($query,$db_link);
        list( $tusername ) = mysql_fetch_row($queryv);
        ppmysql_free_result($queryv);
        
        if ( $Globals{'vbversion'} == "w3t6" ) {
            $profilelink = $Globals{'vbulletin'}."/showprofile.php?Cat=&amp;User=$tusername&amp;Number=$tuserid";
            $postreply = "<font color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><b>Post a Reply</b></font>";
        }
        else {
            $profilelink = $Globals{'vbulletin'}."/showprofile.php?Cat=&amp;user=$tusername";
            $postreply = "<font color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><b>Post a Reply</b></font>";            
        }
    }
    if ($Globals{'vbversion'} == "phpBB2") {
        $profilelink = $Globals{'vbulletin'}."/profile.php?mode=viewprofile&u=$tuserid";
        $postreply = "<font color=\"".$Globals{'headfontcolor'}."\" face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\"><b>Post a Reply</b></font>";
    }

    return( $profilelink );
}

function pagesystem( $rowcnt, $whoami = "showgallery" ) {
    global $Globals, $perpage, $pages, $page, $posternav, $thecat, $sword, $p, $sortparam, $ppuser, $stype;

    // begin pages/nav system //
    // $rowcnt = number of images to display
    // $perpage = configurable by menu

    $pages = ($rowcnt/$perpage);

    if ((intval($pages)) < $pages) {
        $pages = intval($pages)+1;
    }
    else {
        $pages = intval($pages);
    }

    if ($page != "") {
        $startnumb = ($page*$perpage)-$perpage+1;
    }
    else {
        $page = 1;
        $startnumb = 1;
    }

    if ($pages > 1) {
        $posternav .= "<table width=\"".$Globals{'tablewidth'}."\"><Tr><Td align=\"right\"><font size=\"".$Globals{'fontsmall'}."\" face=\"verdana,sans-serif\">";
        $thestart = 0;

        if ($page < 11) {
            $thestart = 1;
        }
        elseif ($page > 10) {
            $thestart = $page-5;
        }
        $theend = $thestart+9;

        for ($p=$thestart; $p<=$pages; $p++) {
            if ($p != $thestart) {
                $posternav .= " | ";
            }
            else
                $posternav .= "Page ";

                if ( $p == $thestart ) {
                    if ( $whoami == "admusers" ) {                    
                        $posternav .= "<a href=\"".$Globals{'maindir'}."/adm-users.php?username=$uname&amp;email=$uemail&amp;susergroupid=$ugroupid&amp;ppaction=users&amp;do=findusers&amp;page=1&amp;sort=$sortparam&amp;perpage=$perpage\">1</a> ... ";
                    }
                    else {
                        $posternav .= "<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=$thecat&amp;si=$sword&amp;thumb=1&amp;page=1&amp;sort=$sortparam&amp;perpage=$perpage&amp;ppuser=$ppuser&amp;stype=$stype\">1</a> ... ";                        
                    }
                }

            if ($page != $p) {
                $thispage = "$p";
                if ( $whoami == "admusers" ) {
                    $posternav .= "<a href=\"".$Globals{'maindir'}."/adm-users.php?username=$uname&amp;email=$uemail&amp;susergroupid=$ugroupid&amp;ppaction=users&amp;do=findusers&amp;page=$p&amp;sort=$sortparam&amp;perpage=$perpage\">$thispage</a>";
                }
                else {
                    $posternav .= "<a href=\"".$Globals{'maindir'}."/showgallery.php?cat=$thecat&amp;si=$sword&amp;thumb=1&amp;page=$p&amp;sort=$sortparam&amp;perpage=$perpage&amp;ppuser=$ppuser&amp;stype=$stype\">$thispage</a>";
                }
            }

            if ($p > $theend) {
                if ( $whoami == "admusers" ) {
                    $posternav .= " ... <a href=\"".$Globals{'maindir'}."/adm-users.php?username=$uname&amp;email=$uemail&amp;susergroupid=$ugroupid&amp;ppaction=users&amp;do=findusers&amp;page=$pages&amp;sort=$sortparam&amp;perpage=$perpage\">$pages</a>";
                }
                else {                
                    $posternav .= " ... <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=$thecat&amp;si=$sword&amp;thumb=1&amp;page=$pages&amp;sort=$sortparam&amp;perpage=$perpage&amp;ppuser=$ppuser&amp;stype=$stype\">$pages</a>";
                }
                break;
            }

            if ($page == $p) {
                $posternav .= "<b>$p</b>";
            }
        }

        $posternav .= "</font></td></tr></table>";
    }
// end pages/nav //#
}


function ConvertReturns( $ConvertReturns ) {
    $ConvertReturns = str_replace( "\n\r\n", "<p>", $ConvertReturns);
    $ConvertReturns = str_replace( "\r", "<br>", $ConvertReturns);
    //$ConvertReturns = eregi_replace( "(\r\n|\r|\n)","<br />", $ConvertReturns );

    return ($ConvertReturns);
}


function fixfilenames( $realname ) {
    $realname  = str_replace( "%20", "_", $realname );
    $realname  = ereg_replace( "\\\\'", "_", strtolower($realname) );
    $stripname = get_filename( $realname );
    $theext    = get_ext( $realname );
    $stripname = ereg_replace( "[^a-zA-Z0-9/\:_\-]", "_", $stripname );
    $realname  = "{$stripname}.{$theext}";
    
    return( $realname );
}


function gen_password() {
	$consts='bcdgklmnprst';
	$vowels='aeiou';

	for ($x=0; $x < 6; $x++) {
		mt_srand ((double) microtime() * 1000000);
		$const[$x] = substr($consts,mt_rand(0,strlen($consts)-1),1);
		$vow[$x] = substr($vowels,mt_rand(0,strlen($vowels)-1),1);
	}
	$newpass = $const[0] . $vow[0] .$const[2] . $const[1] . $vow[1] . $const[3] . $vow[3] . $const[4];
	
    return( $newpass );
}


function get_catname( $cat ) {
    global $link;
    
    $query = "SELECT catname FROM categories WHERE id=$cat";
    $catresults = ppmysql_query($query, $link);
    list( $catname ) = mysql_fetch_row($catresults) ;   
    ppmysql_free_result($catresults);

    return( $catname );    
}


function childsub( $parid, $last=0 ) {
    global $Globals, $childnav, $link, $tcat, $ppuser, $si, $papass;

    if ( $parid == 999 ) {
        $childnav = "> <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=999&amp;thumb=1\">My Favorites</a>";        
    }
    elseif ( $parid > 990 && $parid < 999 ) {
        if ( empty($si) ) {
            if ( $parid == 997 ) $childnav = "> Last Days Post";   
            elseif ( $parid == 996 ) $childnav = "> Last 7 Days Post";   
            elseif ( $parid == 995 ) $childnav = "> Last 14 Days Post";
            else $childnav = "> Custom View";
        }
        else {
            $childnav = "> Search Results";        
        }
    }
    elseif ( $parid > 3000 ) {
        $aquery = "SELECT id,albumname,parent FROM useralbums WHERE id=$parid";
        $arows = ppmysql_query($aquery,$link);
        list( $subid, $subalbumname, $aparent ) = mysql_fetch_row($arows);
        
        if ( empty( $tcat ) ) list( $tcat, $tmail ) = get_username($aparent);
        
        $childnav = ">
            <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=500&amp;thumb=1\">Member</a> >
            <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=500&amp;ppuser=$ppuser&amp;thumb=1\">$tcat</a> > 
            <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=$subid&amp;papass=$papass&amp;thumb=1\">
            $subalbumname</a> $childnav";
        
        ppmysql_free_result($arows);
    }
    else {
        $query = "SELECT id,catname,parent,thumbs FROM categories WHERE id=$parid";
        $resulta = ppmysql_query($query, $link);

        while ( list( $tid, $tcatname, $tparent, $tthumbs ) = mysql_fetch_row($resulta) ) {
            if ( $tthumbs == "yes" )
                $childnav = "> <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=$tid&amp;thumb=1\">$tcatname</a> $childnav";
            else
                $childnav = "> <a href=\"".$Globals{'maindir'}."/index.php?cat=$tid\">$tcatname</a> $childnav";

            childsub($tparent, 1);
        }
        ppmysql_free_result($resulta);
        
        if ( $parid == 500 && !empty($ppuser) ) {
            $childnav .= "> <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=500&amp;ppuser=$ppuser&amp;thumb=1\">$tcat</a>";
        }
    }
    
    if ( !empty($si) && $last == 0 ) 
        $childnav = "$childnav ($si)";
        
}

//
//
function catmoveopt( $subcatid ) {
    global $Globals, $catoptions, $link, $dashes, $selected, $ugcat, $adminedit, $userid, $personal;

    if (empty($dashes)) $dashes=array("");

    if (empty($personal)) {
        $aquery = "SELECT id,albumname,description FROM useralbums WHERE parent=$userid";
        $arows = ppmysql_query($aquery,$link);

        if ( $arows ) {
            while ( list( $subid, $subalbumname, $subalbumdesc ) = mysql_fetch_row($arows) ) {
                if ($selected == $subid) {
                    $selecttext = "SELECTED";
                }
                else {
                    $selecttext = "";
                }

                $catoptions .= "<option $selecttext value=\"$subid\">Album: $subalbumname</option>";
            }
            ppmysql_free_result( $arows );
        }
        $personal = 1;
    }

    $query = "SELECT id,ugnoupload FROM categories";
    $resultb = ppmysql_query($query,$link);

    if ($adminedit == "1") {
        $query = "SELECT id,catname,thumbs FROM categories WHERE parent=$subcatid ORDER BY catorder";
    }
    else {
        $query = "SELECT id,catname,thumbs FROM categories WHERE parent=$subcatid AND private='no' ORDER BY catorder";
    }

    $rows = ppmysql_query($query,$link);
    while ( list( $subid, $subcatname, $subthumbs ) = mysql_fetch_row($rows) ) {
        $dashdisp = "";
        for ($i = 0; $i < $dashes[$subcatid]; $i++) {
            $dashdisp .= "-";
        }
        $dashes[$subid] = $dashes[$subcatid]+1;
        if ( !empty($dashdisp) ) $dashdisp .= ">";

        if ($selected == $subid) $selecttext = "SELECTED";
        else $selecttext = "";

        if ( $subthumbs == "no" ) {
            if ( $ugcat{$subid} != 1 ) {
                $catoptions .= "<option $selecttext value=\"notcat\">$subcatname</option>";
            }
        }
        else {
            if ( $ugcat{$subid} != 1 ) {
                $catoptions .= "<option $selecttext value=\"$subid\">$dashdisp$subcatname</option>";
            }
        }
        catmoveopt($subid);
    }
    ppmysql_free_result($rows);
    
}


function topmenu() {
    global $Globals, $link, $menu, $menu2, $userid, $username, $cat, $adminedit, $useruploads, $catquery;

    if ( IsSet($cat) ) {
        $catquery="?cat=$cat";
    }

    $menu = "<font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">";
    $menu2 = "<font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\">";
    
    if ( $adminedit == 1 ) {
        $query="SELECT id FROM photos WHERE approved=0";
        $nump = ppmysql_query($query,$link);
        $rows = mysql_num_rows($nump);
        ppmysql_free_result( $nump );
        
        if ( $rows > 0 )
            $menu .= "<img border=\"0\" src=\"".$Globals{'idir'}."/check.gif\" alt=\"New Photos\">&nbsp;";

        $menu .= "<A href=\"".$Globals{'maindir'}."/adm-index.php\">Admin</a> | <A href=\"".$Globals{'maindir'}."/index.php\">Home</a>";
    }
    else
        $menu .= "<A href=\"".$Globals{'maindir'}."/index.php\">Home</a>";

    if ($userid != "") {
        $log = " | <a href=\"".$Globals{'maindir'}."/logout.php\">Logout</a>";
        $menu2 .= "<a href=\"".$Globals{'maindir'}."/showgallery.php?ppuser=$userid&amp;cat=500&amp;thumb=1\">My&nbsp;Gallery</a>";        
        
        if ( $Globals{'allowpa'} == "yes" ) {
            $menu2 .= " | <a href=\"".$Globals{'maindir'}."/useralbums.php\">Album&nbsp;Admin</a>";
        }
        $menu2 .= " | <a href=\"".$Globals{'maindir'}."/showgallery.php?cat=999&amp;thumb=1\">My&nbsp;Favorites</a>";        
    }
    else {
        $log = "| <a href=\"".$Globals{'maindir'}."/login.php?login=yes\">Login</a>";
    }

    if ( $adminedit == 0 ) {
        if ($Globals{'allowregs'} == "yes" && $userid == "") {
            if ($Globals{'vbversion'} == "phpBB") {
                $regtext = " | <A href=\"".$Globals{'vbulletin'}."/bb_register.php?mode=agreement\">Register</a>";
                $menu .= $regtext;
            }
            elseif ($Globals{'vbversion'} == "phpBB2") {
                $regtext = " | <A href=\"".$Globals{'vbulletin'}."/profile.php?mode=register\">Register</a>";
                $menu .= $regtext;
            }
            elseif ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
                $regtext = " | <A href=\"".$Globals{'vbulletin'}."/newuser.php?Cat=\">Register</a>";
                $menu .= $regtext;
            }
            elseif ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
                $regtext = " | <A href=\"".$Globals{'vbulletin'}."/register.php?action=signup\">Register</a>";
                $menu .= $regtext;
            }
            else {
                $regtext = " | <A href=\"".$Globals{'maindir'}."/register.php\">Register</a>";
                $menu .= $regtext;
            }
        }
    }

    if ($Globals{'vbversion'} == "phpBB") {
        $menu .= " | <A href=\"".$Globals{'vbulletin'}."/prefs.php\">Profile</a>";
    }
    elseif ($Globals{'vbversion'} == "phpBB2") {
        $menu .= " | <A href=\"".$Globals{'vbulletin'}."/profile.php?mode=editprofile\">Profile</a>";
    }
    elseif ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
        $menu .= " | <A href=\"".$Globals{'vbulletin'}."/login.php\">Profile</a>";
    }
    elseif ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
        $menu .= " | <A href=\"".$Globals{'vbulletin'}."/usercp.php\">Profile</a>";
    }
    else {
        $menu .= " | <A href=\"".$Globals{'maindir'}."/member.php?ppaction=edit\">Profile</a>";
    }

    if ( $adminedit == 0 ) {
        if ( $Globals{'allowup'} == "yes" && $useruploads == 1 ) {
            $menu .= " | <A href=\"".$Globals{'maindir'}."/uploadphoto.php$catquery\">Upload&nbsp;Photos</a>";
        }
    }
    else {
        $menu .= " | <A href=\"".$Globals{'maindir'}."/uploadphoto.php$catquery\">Upload&nbsp;Photos</a>";
    }

    $menu .= " $log";
    
    //if ( !empty($menu2) ) $menu .= "<br>$menu2";
    $menu .= "</font>";
    $menu2 .= "</font>";    

    return;
}

function forward( $redirect, $msg="" ) {
    global $Globals, $link, $db_link;
    
    if ( empty($msg) ) {
        $msg = "Now forwarding you to $redirect";
    }
    
    if ( $redirect == "" ) {
        $redirect = $Globals{'maindir'}."/index.php";
    }

    $success = "<head><title>".$Globals{'galleryname'}."</title>
    <script language=\"JavaScript\"><!--
    t=1; function dorefresh() { u=new String(\"$redirect\");
    ti=setTimeout(\"dorefresh();\",2000); if (t>0) { t-=1; }
    else { clearTimeout(ti); window.location=u.replace(\"#\",\"&t=\"+parseInt(10000*Math.random())+\"#\"); }
    } window.onLoad=dorefresh();
    --></script><meta http-equiv=\"Refresh\" content=\"2; URL=$redirect\">
    </head>
    <body bgcolor=\"".$Globals{'forwardbody'}."\" leftmargin=\"10\" topmargin=\"10\" marginwidth=\"10\" marginheight=\"10\"
    link=\"".$Globals{'maintext'}."\" vlink=\"".$Globals{'maintext'}."\" alink=\"".$Globals{'maintext'}."\"><!-- CyKuH [WTN] -->
    <br><br>

    <table width=\"".$Globals{'tablewidth'}."\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"50%\" align=\"center\">
    <tr align=\"center\" valign=\"middle\">
    <td>
    <table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"".$Globals{'bordercolor'}."\" width=\"70%\">
    <tr>
    <td bgcolor=\"".$Globals{'maincolor'}."\" align=\"center\">
    <p><font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontmedium'}."\" color=\"".$Globals{'maintext'}."\"><b>
    $msg</b><br><br></font>

    <font face=\"".$Globals{'mainfonts'}."\" size=\"".$Globals{'fontsmall'}."\"> <a
    href=\"$redirect\">Click here if you do not want to wait any longer<br>
    (or if your browser does not automatically forward you)</a></font></p></td>
    </tr>
    </table>
    </td>
    </tr>
    </table>";

    print $success;
}

function dieWell( $message )  {
    global $Globals, $childnav, $menu, $link, $db_link, $header, $footer;

    topmenu();
    $header = str_replace( "titlereplace", "Notice", $header );    

    $output = "$header
    
        <p><table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"".$Globals{'tablewidth'}."\"><Tr>
        <Td valign=\"middle\" width=\"50%\">&nbsp;$childnav</td><td width=\"50%\" align=\"right\" valign=\"middle\">
        $menu&nbsp;
        </td></tr></table><!-- CyKuH [WTN] -->

        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" bgcolor=\"".$Globals{'bordercolor'}."\"  width=\"".$Globals{'tablewidth'}."\" align=\"center\"><tr><td>
        <table cellpadding=\"4\" cellspacing=\"1\" border=\"0\"  width=\"100%\">
        <tr align=\"center\">
        <td colspan=\"2\" align=\"left\" bgcolor=\"".$Globals{'headcolor'}."\"><font face=\"".$Globals{'headfont'}."\"
        color=\"".$Globals{'headfontcolor'}."\" size=\"".$Globals{'fontmedium'}."\"><B>".$Globals{'galleryname'}." Message
        </font>
        </font></td>
        </tr><Tr><Td bgcolor=\"".$Globals{'maincolor'}."\" height=\"300\" valign=\"middle\"><center><font face=\"".$Globals{'mainfonts'}."\"
        size=\"".$Globals{'fontlarge'}."\" color=\"".$Globals{'maintext'}."\"><b>$message</b></font></centeR></td></tr></table></td></tr></table>";

    print "$output\n$footer";

    exit;
}

function fixmessage ( $message ) {
    
    $newmessage = str_replace( "%22","\"", $message );
    $newmessage = htmlspecialchars( $newmessage );

    return ( $newmessage );
}

function fixstring ( $string ) {
    $string = fixmessage ( $string );
    $string = preg_replace( "/<(?:[^>']*|([']).*?\1)*>/e", "", $string );

    return( $string );
}

function findenv ( $name ) {
    global $HTTP_SERVER_VARS;

    $this = "";
    if (empty($HTTP_SERVER_VARS["$name"]))
        $HTTP_SERVER_VARS["$name"]="";
    if (empty($_ENV["$name"]))
        $_ENV["$name"]="";

    if(getenv($name) != '') {
        $this = getenv("$name");
    }

    if(($this == '') && ($HTTP_SERVER_VARS["$name"] != '')) {
        $this = $HTTP_SERVER_VARS["$name"];
    }

    if(($this == '') && ($_ENV["$name"] != '')) {
        $this = $_ENV["$name"];
    }

    return $this;
}

function wordchars ( $string ) {

    $stripstring = ereg_replace( "[^a-zA-Z0-9/\: ]", "", $string );

    if ( strcmp($string, $stripstring) ) {
        dieWell( "The username you chose is not valid. Usernames may not contain anything but numbers and letters." );
        exit;
    }

    return ( $stripstring );
}



function inc_user_posts( $type = "plus", $user = 0 ) {
    global $Globals, $db_link, $userid;

    if ( $user == 0 ) $user = $userid;

    if ( $type == "plus" ) {
        $do_inc = "+1";
    }
    else {
        $do_inc = "-1";
    }

    if ($Globals{'vbversion'} == "2.0.3" || $Globals{'vbversion'} == "2.2.0") {
        $query = "UPDATE user SET posts=posts$do_inc WHERE userid=$user";
        $resulta = ppmysql_query($query,$db_link);
    }
    if ($Globals{'vbversion'} == "phpBB") {
        $query = "UPDATE users SET user_posts=user_posts$do_inc WHERE user_id=$user";
        $resulta = ppmysql_query($query,$db_link);
    }
    if ($Globals{'vbversion'} == "phpBB2") {
        if ( !empty($Globals{'dprefix'}) ) {
            $utable=$Globals{'dprefix'} ."_users";
        }
        else {
            $utable="users";
        }

        $query = "UPDATE $utable SET user_posts=user_posts$do_inc WHERE user_id=$user";
    }
    if ($Globals{'vbversion'} == "w3t" || $Globals{'vbversion'} == "w3t6") {
        $query = "UPDATE w3t_Users SET U_Totalposts=U_Totalposts$do_inc WHERE U_Number=$user";
        $resulta = ppmysql_query($query,$db_link);
    }
    if ($Globals{'vbversion'} == "Internal") {
        $query = "UPDATE users SET posts=posts$do_inc WHERE userid=$user";
        $resulta = ppmysql_query($query,$db_link);
    }

    return;
}

function delete_dir($file) {
    if ( file_exists($file) ) {
        chmod( $file, 0777 );
        if ( is_dir($file) ) {
            $handle = opendir( $file ); 
            while( $filename = readdir($handle) ) {
                if ( $filename != "." && $filename != ".." ) {
                    delete( $file."/".$filename );
                }
            }
            closedir($handle);
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

function remove_all_files( $filename, $medsize, $uid, $thecat ) {
    global $Globals;

    $theext = substr($filename,strlen($filename) - 4,4);
    $filenoext = $filename;
    $filenoext= str_replace( $theext, "", $filenoext);

    if ( file_exists($Globals{'datafull'}."$thecat/$uid$filenoext-thumb$theext") ) {
        $thumbnail = $Globals{'datafull'}."$thecat/$uid$filenoext-thumb$theext";
    }
    else {
        $thumbnail = $Globals{'datafull'}."$thecat/$uid$filenoext-thumb.jpg";
    }

    $bigpic = $Globals{'datafull'}."$thecat/$uid$filenoext$theext";
    $medpic = $Globals{'datafull'}."$thecat/$uid$filenoext-med$theext";

    if ( file_exists($thumbnail) ) unlink($thumbnail);
    if ( file_exists($bigpic) ) unlink($bigpic);

    if ($medsize != "0") {
         if ( file_exists($medpic) ) unlink($medpic);
    }
}

function move_image( $pcat, $catmove, $tuserid, $bigimage ) {
    global $Globals;

    $datadir = $Globals{'datafull'};
    $olddir = $datadir."$pcat";
    $newdir = $datadir."$catmove";

    $theext = substr($bigimage, strlen($bigimage) - 4,4);
    $filename = $bigimage;
    $filename = str_replace( $theext, "", $filename);

    $biglink = $olddir."/$tuserid$filename$theext";
    $newbiglink = $newdir."/$tuserid$filename$theext";

    $thumblink = $olddir."/$tuserid$filename-thumb$theext";
    $newthumblink = $newdir."/$tuserid$filename-thumb$theext";

    $mediumlink = "";
    $newmediumlink = "";
    $mediumlink = $olddir."/$tuserid$filename-med$theext";
    $newmediumlink = $newdir."/$tuserid$filename-med$theext";

    //print "Moving file: [$biglink] to [$newbiglink]<br>";
    //print "Moving medium file: [$mediumlink] to [$newmediumlink]<br>";
    //print "Moving thumbnail: [$thumblink] to [$newthumblink]<br><br>";

    if ( file_exists($biglink) ) {
        if ( copy($biglink, $newbiglink) ) {
            unlink( $biglink );
        }
        else {
            dieWell( "Copy of the file $biglink failed. Operation cancelled." );
            exit;
        }
    }

    if ( file_exists($mediumlink) ) {
        if ( copy($mediumlink, $newmediumlink) ) {
            unlink( $mediumlink );
        }
        else {
            unlink( $newbiglink );
            dieWell( "Copy of the file $mediumlink failed. Operation cancelled." );
            exit;
        }
    }

    if ( file_exists($thumblink) ) {
        if ( copy($thumblink, $newthumblink) ) {
            unlink( $thumblink );
        }
        else {
            unlink( $newbiglink );
            unlink( $newmediumlink );
            dieWell( "Copy of the file $thumblink failed. Operation cancelled." );
            exit;
        }
    }
}


function admin_email ( $ppactionvar, $photonum, $getuserid="", $phototitle="" ) {
    global $Globals, $link;

    if ( $getuserid == "" ) {
        $query = "SELECT userid, title FROM photos WHERE id=$photonum";
        $results = ppmysql_query($query, $link);

        list( $getuserid, $phototitle ) = mysql_fetch_row($results);
        ppmysql_free_result( $results );
    }

    list( $usernm, $useremail ) = get_username( $getuserid );

    if ( empty($useremail) ) return;

    $email_from = "From: ".$Globals{'adminemail'};

    if ($ppactionvar == "approve") {
        $letter="$usernm,

We wanted to let you know that your photo, titled \"$phototitle\", has
been approved and is now visible.  Here is the link to the photo:
".$Globals{'maindir'}."/showphoto.php?photo=$photonum

And if you would like to view your personal photo album, containing all
of the images that you have uploaded to ".$Globals{'webname'}.", you can do so here:
".$Globals{'maindir'}."/showgallery.php?user=$getuserid&amp;thumb=1&amp;cat=500

Thanks!

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

        $subject = $Globals{'webname'}." photo upload approved";
    }

    if ($ppactionvar == "moved") {
        $letter="$usernm,

We felt that your photo, titled \"$phototitle\", was more appropriate
in a different category.  To view it, and to find out where
we moved it (look in the upper left for the category name), visit
this link:

".$Globals{'maindir'}."/showphoto.php?photo=$photonum

Thanks!

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

        $subject = $Globals{'webname'}." photo category change";
    }

    if ($ppactionvar == "delete") {
        $letter="$usernm,

I'm sorry, but the photo you submitted to ".$Globals{'webname'}.", titled
\"$phototitle\", has been deleted.  Some reasons for photo deletions include:

-Images that were partially uploaded/incomplete
-Broken images
-Extremely poor quality/images (impossible to make out the image itself)
-Images that did not conform to our published site contribution and usage guidelines such as offensive images

If you would like to submit another photo, please return to our photo upload form:
".$Globals{'maindir'}."/uploadphoto.php

Thanks,

The ".$Globals{'webname'}." Team
".$Globals{'domain'};

        $subject="Regarding your ".$Globals{'webname'}." photo upload";
    }

    mail( $useremail, $subject, $letter, $email_from );
} // end sub email



function move_image_cat( $pid, $catmove, $forward="yes" ) {
    global $Globals, $link, $userid, $adminedit;

    $query = "SELECT userid,cat,bigimage,medsize,title FROM photos WHERE id=$pid";

    $resulta = ppmysql_query($query,$link);
    list( $puserid, $pcat, $bigimage, $medsize, $ptitle ) = mysql_fetch_row($resulta);
    ppmysql_free_result( $resulta );

    if ( ($userid == $puserid && $Globals{'userdel'} == "yes") || $adminedit == 1 ) {
        move_image( $pcat, $catmove, $puserid, $bigimage );

        if ( $Globals{'moderation'} == "yes" && $adminedit != 1 ) $approved="0";
        else $approved="1";

        $query = "UPDATE photos SET cat=$catmove, approved='$approved' WHERE id=$pid";
        $resulta = ppmysql_query($query,$link);

        $query = "UPDATE comments SET cat=$catmove WHERE photo=$pid";
        $resulta = ppmysql_query($query,$link);

        if ( $Globals{'useemail'} == "yes" && $userid != $puserid ) admin_email( 'moved', $pid, $puserid, $ptitle ) ;

        if ( $forward == "yes" ) {
            $adesc="Moved image $bigimage";
            $furl = $Globals{'maindir'}."/showphoto.php?photo=$pid";

            forward( $furl, $adesc );
            exit;
        }
    }
    else {
        dieWell( "You do not have permission for this action!" );
        exit;
    }
}

function fixcolor( $string="" ) {
    $string = str_replace(" ","",$string);
    $string = str_replace("\"","",$string);
    $string = "<font color=\"$string\">";
    return $string;
}


function is_multimedia( $filename ) {
    $retval = 0;
    
    $mediatypes = array( ".mpg", ".avi", ".asf", ".wmv", ".mov" );
                
    if ( in_array( strtolower(substr($filename,strlen($filename) - 4,4)), $mediatypes ) )
        $retval = 1;
        
    return( $retval );
}


function is_image( $filename ) {
    global $Globals;
    
    $retval = FALSE;

    if ( $Globals{'usegd'} == 0 )
        $imagetypes = array( ".gif", ".jpg", ".png", ".tif", ".bmp" );
    else
        $imagetypes = array( ".gif", ".jpg", ".png" );
                
    if ( in_array( strtolower(substr($filename,strlen($filename) - 4,4)), $imagetypes ) )
        $retval = TRUE;
        
    return( $retval );
}



function get_imagethumb( $bigimage, $thecat, $theuser, $approved ) {
    global $Globals;
    
    if ( empty($bigimage) ) {
        return( "" );
    }
    
    if ( $approved == 0 ) {
        $imgtag = "<img border=\"0\" src=\"".$Globals{'idir'}."/ipending.gif\" alt=\"$fileurl\">";
        return( $imgtag );
    }
    
    $photo_name = get_filename( $bigimage );
    $theext = get_ext( $bigimage );
    
    $filepath = $Globals{'datafull'}."/$thecat/$theuser$photo_name-thumb.$theext";
    $fileurl = $Globals{'datadir'}."/$thecat/$theuser$photo_name-thumb.$theext";
    $imgtag = "<img border=\"0\" src=\"".$Globals{'idir'}."/nothumb.gif\" alt=\"$fileurl\">";

    if ( file_exists( $filepath ) ) {
        $imgtag = "<img border=\"0\" src=\"$fileurl\" alt=\"\">";
    }
    else {
        $theext = strtolower( $theext );
        $filepath2 = $Globals{'datafull'}."/$thecat/$theuser$photo_name-thumb.jpg";
        $fileurl2 = $Globals{'datadir'}."/$thecat/$theuser$photo_name-thumb.jpg";

        if ( file_exists( $filepath2 ) )
            $imgtag = "<img border=\"0\" src=\"$fileurl2\" alt=\"\">";
        else {
            if ( is_multimedia($filepath) )
                $imgtag = "<img border=\"0\" src=\"".$Globals{'idir'}."/video.jpg\" alt=\"$fileurl2\">";
        }
    }

    return( $imgtag );    
}



function convert_markups( $ecomments ) {
    global $Globals;

    // If you would like to disable SOME of the UBB Code (such as links); you can comment
    // out the lines which handle that conversion. The actual links are NOT stored in the
    // database, so commenting out these fields will disable the code for all displays
    
    // Convert near-URL tags to HTML (auto conversion, no url tag needed
    $ecomments = " " . $ecomments;
    $ecomments = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $ecomments);
    $ecomments = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $ecomments);
    $ecomments = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^, \n\r]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ecomments);
    $ecomments = substr($ecomments, 1);

    // Convert URL tags to HTML
    $ecomments = preg_replace("/\[url\]ftp:\/\/([^\[]*?)\[\/url\]/i","<a href=\"ftp://\\1\" target=\"_blank\">ftp://\\1</a>",$ecomments);
    $ecomments = preg_replace("/\[url\]http:\/\/([^\[]*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">http://\\1</a>",$ecomments);
    $ecomments = preg_replace("/\[url\]https:\/\/([^\[]*?)\[\/url\]/i","<a href=\"https://\\1\" target=\"_blank\">https://\\1</a>",$ecomments);
    $ecomments = preg_replace("/\[url\]([^\[]*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">\\1</a>",$ecomments);
    $ecomments = preg_replace("/\[url=http:\/\/(.*?)\](.*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">\\2</a>",$ecomments);
    $ecomments = preg_replace("/\[url=https:\/\/(.*?)\](.*?)\[\/url\]/i","<a href=\"https://\\1\" target=\"_blank\">\\2</a>",$ecomments);
    $ecomments = preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/i","<a href=\"http://\\1\" target=\"_blank\">\\2</a>",$ecomments);

    // Convert bolds and italics
    $ecomments = str_replace("[b]","<b>",$ecomments);
    $ecomments = str_replace("[i]","<i>",$ecomments);
    $ecomments = str_replace("[/i]","</i>",$ecomments);
    $ecomments = str_replace("[/b]","</b>",$ecomments);

    // Convert the color codes
    $ecomments = preg_replace("/\[color:(.+?)\]/e","fixcolor(\"$1\")",$ecomments);
    $ecomments = preg_replace("/\[\/color\]/","</font color>",$ecomments);

    // Do list elements
    $ecomments = preg_replace("/(\[list\])\n?\r?(.+?)(\[\/list\])/is","<ul type=\"square\">\\2</ul>",$ecomments);
    $ecomments = preg_replace("/(\[list=)(A|1)(\])\n?\r?(.+?)(\[\/list\])/is","<ol type=\"\\2\">\\4</ol>",$ecomments);
    $ecomments = preg_replace("/\n?\r?(\[\*\])/is","<li>",$ecomments);

    // Quote markup
    $ecomments = str_replace("[quote]","<blockquote>Quote:<hr /><br />",$ecomments);
    $ecomments = str_replace("[/quote]","<br /><br /><hr /></blockquote>",$ecomments);

    // Convert email markup to html
    $ecomments = eregi_replace("\[email\]([^\[]*)\[/email\]","<a href=\"mailto:\\1\">\\1</a>",$ecomments);

    // Smilies
    $ecomments = preg_replace("/(( |\n|^):\)|(\[|:)smile(\]|:))/","\\2<img src=\"{$Globals['idir']}/smile.gif\" alt=\"Smile\" />", $ecomments);
    $ecomments = preg_replace("/(( |\n|^);\)|(\[|:)wink(\]|:))/","\\2<img src=\"{$Globals['idir']}/wink.gif\" alt=\"Wink\" />", $ecomments);
    $ecomments = preg_replace("/(( |\n|^):\(|(\[|:)sad(\]|:))/","\\2<img src=\"{$Globals['idir']}/sad.gif\" alt=\"Sad\" />", $ecomments);

    $ecomments = preg_replace("/(( |\n|^):d|(\[|:)teeth(\]|:))/","\\2<img src=\"{$Globals['idir']}/teeth_smile.gif\" alt=\"Smile!\" />", $ecomments);
    $ecomments = preg_replace("/(( |\n|^):p|(\[|:)tounge(\]|:))/","\\2<img src=\"{$Globals['idir']}/tounge_smile.gif\" alt=\"Nah!\" />", $ecomments);

    $ecomments = preg_replace("/(( |\n|^)\(y\)|(\[|:)up(\]|:))/","\\2<img src=\"{$Globals['idir']}/thumbs_up.gif\" alt=\"Thumbs Up!\" />", $ecomments);
    $ecomments = preg_replace("/(( |\n|^)\(n\)|(\[|:)no(\]|:))/","\\2<img src=\"{$Globals['idir']}/thumbs_down.gif\" alt=\"Thumbs Down!\" />", $ecomments);

    return ($ecomments);
}

?>
