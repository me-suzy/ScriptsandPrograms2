<?php

define("EE_DEBUG_MODE",false);
define("EE_MEMORY_SAFE_MODE",true);

require "init.php";

if( !isset($session['exists']) ) {
 header("Location: http://".$HTTP_SERVER_VARS['HTTP_HOST'].dirname($HTTP_SERVER_VARS['PHP_SELF'])."/expire.html");
 exit();
}

// what worksheet we need to print out?
if( isset($HTTP_GET_VARS['sheet']) ) {
	$worksheet = (int)$HTTP_GET_VARS['sheet'];
} else {
	if( isset($session['sheet']) ) {
		$worksheet = (int)$session['sheet'];
	} else {
		$worksheet = 0;
	}
}

// store worksheet number into the session
$session['sheet'] = $worksheet;

if( !isset($session['opt']) ) $session['opt'] = array();
$session['opt']['explore_sheet'] = $worksheet;

// change options
$hc = isset($session['skip_hidden_cells']) ? (int)$session['skip_hidden_cells'] : 0;
if( isset($HTTP_GET_VARS['opt']) ) {
  $session['opt']['read_font'] = (isset($HTTP_GET_VARS['rfont']) && ($HTTP_GET_VARS['rfont'] == 'on'));
  $session['opt']['read_format'] = (isset($HTTP_GET_VARS['rformat']) && ($HTTP_GET_VARS['rformat'] == 'on'));
  $session['opt']['read_formula'] = (isset($HTTP_GET_VARS['rformula']) && ($HTTP_GET_VARS['rformula'] == 'on'));
  $session['opt']['read_border'] = (isset($HTTP_GET_VARS['rborder']) && ($HTTP_GET_VARS['rborder'] == 'on'));
  $session['opt']['read_bgcolor'] = (isset($HTTP_GET_VARS['rbgcolor']) && ($HTTP_GET_VARS['rbgcolor'] == 'on'));
  $session['opt']['read_link'] = (isset($HTTP_GET_VARS['rlink']) && ($HTTP_GET_VARS['rlink'] == 'on'));
  $hc = (isset($HTTP_GET_VARS['hc']) && ($HTTP_GET_VARS['hc'] == 'on')) ? 0 : 1;
}
$session['skip_hidden_cells'] = $hc;

$upfilename = $session['file'];

// write all session data and close session in order to
// load bottom frame faster
session_write_close();

?>
<html>
<head>
<script language="JavaScript">
<!--
window.parent.frames['ws'].location.href='ws.php';
function sp(p,r){
document.images['pr2'].width=150-1.5*p;
document.images['pr1'].width=1.5*p;
s=window.document.body.getElementsByTagName('span');
s=s['pr'];
s=s.getElementsByTagName('span');
s['pr3'].innerHTML=p+'%';
s['pr4'].innerHTML='('+r+' rows)';
}
//-->
</script>
<?php

if( EE_DEBUG_MODE ) {
  echo "memory_safe mode is ".(EE_MEMORY_SAFE_MODE ? 'enabled' : 'disabled')."<br>\n";
}

flush();

$eexp = new ExcelExplorer(EE_MEMORY_SAFE_MODE);

function getmicrotime(){ 
  list($usec, $sec) = explode(" ",microtime()); 
  return ((float)$usec + (float)$sec); 
}

if( EE_DEBUG_MODE ) $time1 = getmicrotime();
$res = $eexp->Explore_file($upfilename,$session['opt']);
if( EE_DEBUG_MODE ) $time2 = getmicrotime();

switch ($res) {
	case 0: break;
	case 1: die( $die_hdr.'File corrupted or not in Excel 5.0 and above format'.$die_ftr );
	case 2: die( $die_hdr.'Unknown or unsupported Excel file version'.$die_ftr );
	default:
		die( $die_hdr.'Excel Explorer give up'.$die_ftr );
}

?>
<style>
<!--
SPAN {font-size:1px}
.txt {font-size:15px;font-family:Arial,Verdana,Tahoma;color:#000000;font-weight:normal;text-decoration:none}
td {font-size:14px;font-family:Arial,Verdana,Tahoma;color:#000000;font-weight:normal;text-decoration:none;border-color: #000000}
<?php

$fonts = $eexp->GetFontsList();
for($i=0;$i<count($fonts);$i++) {
  print ".cf".$i." {";
  print 'font-family:'.$eexp->AsPlain($fonts[$i]['name']);
  print ';font-size:'.number_format($fonts[$i]['height']/1440,3,'.','').'in';
  print ';font-weight:'.$fonts[$i]['bold'];
  if( $fonts[$i]['italic'] ) print ';font-style:italic';
  if( $fonts[$i]['strike'] && $fonts[$i]['underline'] )
    print ';text-decoration: underline || line-through';
  elseif( $fonts[$i]['strike'] )
    print ';text-decoration: line-through';
  elseif( $fonts[$i]['underline'] )
    print ';text-decoration: underline';
  else
    print ';text-decoration: none';
  if( isset($fonts[$i]['color']) ) print ';color:'.$fonts[$i]['color']['html'];
  print "}\n";
}

?>
-->
</style>
</head>
<body topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<span id=pr><table border=0 cellpadding=2 cellspacing=0>
<tr>
 <td class=txt>Loading page: </td>
 <td>
<table border=0 cellpadding=1 cellspacing=0 bgcolor="#000000"><tr><td>
 <table border=0 cellpadding=0 cellspacing=0 width=150>
  <tr>
   <td bgcolor="#50A0F0"><img id=pr1 src="dot.gif" width=0 height=10 alt="" border=0></td>
   <td bgcolor="#FFFFFF"><img id=pr2 src="dot.gif" width=150 height=10 alt="" border=0></td></tr>
 </table>
</td></tr></table>
 </td>
 <td><span id=pr3 class=txt>0%</span></td>
 <td><span id=pr4 class=txt>(0 rows)</span></td>
</tr></table></span>
<?php

if( EE_DEBUG_MODE )  {
  $time_elapsed = round($time2-$time1,4);
  $class_size = strlen(serialize($eexp));
  $class_size = ($class_size >> 10);
}

function print_css_border_styles($border) {
  $styles = array('border-right-style:','border-bottom-style:');
  $b = array($border['right_style'],$border['bottom_style']);

  for( $i=0; $i<count($styles); $i++ ) {
    if( $b[$i] == 0 ) continue;

    print $styles[$i];

    switch( $b[$i] ) {
      case 1: //hair
      case 2: // dotted
  	print 'dotted';  break;
      case 3: // thin dash-dot-dotted 
      case 4: // thin dash-dotted
      case 5: // dashed
  	print 'dashed'; break;
      case 6: // thin
  	print 'solid'; break;
      case 7: // medium dash-dot-dotted
      case 8: // slanted medium dash-dotted
      case 9: // medium dash-dotted
      case 10: // medium dashed
  	print 'dashed'; break;
      case 11: // medium
      case 12: // thick
  	print 'solid'; break;
      case 13: // double
  	print 'double'; break;

      default:
  	print 'solid'; break;
    }
    print ';';
  }
}

function print_css_border_widths($border) {
  $styles = array('border-right-width:','border-bottom-width:');
  $b = array($border['right_style'],$border['bottom_style']);

  for( $i=0; $i<count($styles); $i++ ) {
    if( $b[$i]==0 ) continue;

    print $styles[$i];

    switch( $b[$i] ) {
      case 1: //hair
      case 2: // dotted
      case 3: // thin dash-dot-dotted 
      case 4: // thin dash-dotted
      case 5: // dashed
      case 6: // thin
  	print '1'; break;
      case 7: // medium dash-dot-dotted
      case 8: // slanted medium dash-dotted
      case 9: // medium dash-dotted
      case 10: // medium dashed
      case 11: // medium
  	print '2'; break;
      case 12: // thick
      case 13: // double
  	print '3'; break;

      default:
  	print '1'; break;
    }

    print 'px;';
  }
}

function get_border_style($style,$worksheet,$column,$row) {
  global $eexp,$hc;

  if( !isset($style['border']) ) {
    $style['border']['left_style'] = 0;
    $style['border']['top_style'] = 0;
    $style['border']['right_style'] = 0;
    $style['border']['bottom_style'] = 0;
  }

    $styleb = $eexp->GetCellStyle($worksheet,$column,$row+$eexp->GetMergedRowsNum($worksheet,$column,$row,$hc));
    if( ($style['border']['bottom_style'] == 0) &&
	!isset($style['bgcolor']) &&
	!isset($styleb['bgcolor']) ) {
	  $style['border']['bottom_style'] = 1;
	  if( !isset($style['border']['bottom_color']) )
	    $style['border']['bottom_color']['html'] = '#d0d0d0';
    }

    $styler = $eexp->GetCellStyle($worksheet,$column+$eexp->GetMergedColumnsNum($worksheet,$column,$row,$hc),$row);
    if( ($style['border']['right_style'] == 0) &&
	!isset($style['bgcolor']) &&
	!isset($styler['bgcolor']) ) {
          $style['border']['right_style'] = 1;
	  if( !isset($style['border']['right_color']) )
            $style['border']['right_color']['html'] = '#d0d0d0';
    }

    $style['border']['left_style'] = 0;
    $style['border']['top_style'] = 0;

  return $style;
}

function print_css_border_colors($border) {
  if( isset($border['right_color']) && ($border['right_style'] > 0) )
	print 'border-right-color:'.$border['right_color']['html'].';';
  if( isset($border['bottom_color']) && ($border['bottom_style'] > 0) )
	print 'border-bottom-color:'.$border['bottom_color']['html'].';';
}

if( EE_DEBUG_MODE ) {
  echo "\nTotal time elapsed: ".$time_elapsed." seconds, serialized class size: ".$class_size." Kb";
  $time1 = getmicrotime();
}

// maybe worksheet is a Chart or Visual Basic Module?
if( $eexp->GetWorksheetType($worksheet) != 0 ) {

  // Chart or Visual Basic Module
  print "<b>No data to display</b>\n";

} else {

  if( $eexp->IsEmptyWorksheet($worksheet) ) {

    // emtpty worksheet
    print "<b>Empty worksheet</b>\n";

  } else {
    // open table for worksheet data
    echo "<table cellpadding=0 cellspacing=0>\n";

    // print column names
    print "<tr><td style=\"border:1px solid #000000\"><img src=\"dot.gif\" width=1 height=1 alt=\"\" border=0></td>\n";
    for( $i=0; $i<=$eexp->GetLastColumnIndex($worksheet); $i++ ) {
      if( !($eexp->IsHiddenColumn($worksheet,$i) && ($hc==1)) ) {
        print '<td class=cf0 style="border-top:1px solid #000000;border-right:1px solid #000000;border-bottom:1px solid #000000;border-left:0px none #000000;color:#000000" align=center';
        $width = $eexp->getColumnWidth($worksheet,$i);
        // print empty image to specify column width
        print '><img src="dot.gif" height=0 width="'.number_format($width/29.25,0,'.','').'"><br>&nbsp;';
        if( $i>25 ) print chr((int)($i/26)+64);
        print chr(($i % 26) + 65)."&nbsp;";
        print "</td>\n";
      }
    }
    print"</tr>\n";

    $nrows = $eexp->GetLastRowIndex($worksheet);
    for( $j=0; $j<=$nrows; $j++ ) {
      if( !($eexp->IsHiddenRow($worksheet,$j) && ($hc==1)) ) {

        // open row tag and print row number
        print '<tr style="height:'.number_format($eexp->GetRowHeight($worksheet,$j)/1440,3,'.','').'in"';
        print '><td align=center valign=bottom class=cf0 style="border-left:1px solid #000000;border-right:1px solid #000000;border-bottom:1px solid #000000;border-top:0px none #000000;color:#000000">'.($j+1)."</td>\n";

        for( $i=0; $i<=$eexp->GetLastColumnIndex($worksheet); $i++ ) {

          // $i == column
          // $j == row

	  $cell_type = $eexp->GetCellType($worksheet,$i,$j);

          // skip printing cell within hidden column or merged cells area
          if( !($eexp->IsHiddenColumn($worksheet,$i) && ($hc == 1)) &&
              ($cell_type != 8) ) {

            // open cell tag
            print '<td';

            // spanning rows and columns for merged cells area
            if( $eexp->GetMergedRowsNum($worksheet,$i,$j) > 0 ) {

              // get merged rows and columns num
              // skip hidden if needed
              $mr = $eexp->GetMergedRowsNum($worksheet,$i,$j,$hc);
              $mc = $eexp->GetMergedColumnsNum($worksheet,$i,$j,$hc);

              if( $mr>1 ) print " rowspan=$mr";
              if( $mc>1 ) print " colspan=$mc";
            }

            $btag = '';
            $etag = '';

            // cell font
            $style = $eexp->GetCellStyle($worksheet,$i,$j,$hc==1,true);
            if( isset($style['font_index']) )
              print " class=cf".$style['font_index'];

            // cell background color
            if( isset($style['bgcolor']) ) {
              print ' bgcolor="'.$style['bgcolor']['html'].'"';
            }

            // cell font style
            if( isset($style['font']) ) {
              if( $style['font']['script']==1 ) {
                $btag .= '<sup>';
                $etag = '</sup>'.$etag;
              }
              if( $style['font']['script']==2 ) {
                $btag .= '<sub>';
                $etag = '</sub>'.$etag;
              }
            }

            // cell data alignment
            if( isset($style['align']) ) {

              // horizontal alignment
              if( $style['align'] > 0 ) {
                print ' align="';
                switch( $style['align'] ) {
                  case 2:
                  case 6:
                    print 'center'; break;
                  case 3:
                    print 'right'; break;
                  case 4:
                  case 5:
                  case 7:
                    print 'justify'; break;
                  case 1:
                  default:
                    print 'left'; break;
                }
                print '"';
              } else {
                // alignment depends on cell type
                switch( $cell_type ) {
                  case 1:
                  case 2:
                  case 6:
                    print ' align="right"';
                    break;
                  case 4:
                  case 5:
                    print ' align="center"';
                    break;
                  default:
                    break;
                }
              }

              // vertical alignment
              print ' valign="';
              switch( $style['valign'] ) {
                case 0:
                  print 'top'; break;
                case 1:
                case 3:
                case 4:
                  print 'middle'; break;
                case 2:
                default:
                  print 'bottom'; break;
              }
              print '"';

              // word wrapping
              if( !$style['word_wrap'] ) {
                $btag .= '<nobr>';
                $etag = '</nobr>'.$etag;
              }
            }

            // cell borders
	    $style = get_border_style($style,$worksheet,$i,$j);
            if( isset($style['border']) ) {
              print ' style="';
              print_css_border_styles($style['border']);
              print_css_border_widths($style['border']);
              print_css_border_colors($style['border']);
              print '"';
            }

            // hyperlink?
            $link = $eexp->GetCellLink($worksheet,$i,$j);
            if( $link !== false ) {
              if( isset($link['quick_tip']) ) {
                print ' title="'.$eexp->AsHTML($link['quick_tip']).'"';
              }

              if( ($link['type'] > 0) && ($link['type'] <= 3) ) {
                $link_tag = '<a target="_blank"';
                if( isset($style['font_index']) ) {
                  $link_tag .= ' class=cf'.$style['font_index'];
                }

                $link_tag .= ' href="';
                if( ($link['type']==2) && ($link['updir'] > 0) ) {
                  for( $up=0; $up<$link['updir']; $up++ )
                    $link_tag .= '../';
                }
                $link_tag .= $eexp->AsHTML($link['link']);
                if( isset($link['tmark']) )
                  $link_tag .= '#'.$eexp->AsHTML($link['tmark']);
                $link_tag .= '">';
                $btag = $link_tag.$btag;
                $etag = $etag.'</a>';
              }
            }

            // cell data
            $dt = $eexp->GetCellData($worksheet,$i,$j);

            print '>'.$btag;

            // print data according to type
            switch( $cell_type ) {

              // number
              case 1:
                print $dt;
                break;

              // percentage
              case 2:
                print ($dt*100).'%';
                break;

              // text
              case 3:
                print $eexp->AsHTML($dt);
                break;

              // boolean
              case 4:
                print ($dt ? "TRUE" : "FALSE");
                break;

              // error code
              case 5:
                // error code
                switch ( $dt ) {
                  case 0x00:
                    print "#NULL!";
                    break;
                  case 0x07:
                    print "#DIV/0";
                    break;
                  case 0x0F:
                    print "#VALUE!";
                    break;
                  case 0x17:
                    print "#REF!";
                    break;
                  case 0x1D:
                    print "#NAME?";
                    break;
                  case 0x24:
                    print "#NUM!";
                    break;
                  case 0x2A:
                    print "#N/A!";
                    break;
                  default:
                    print "Unknown";
                    break;
                }
                break;

              // date
              case 6:
                print $dt['string'];
                break;

              // empty
              case 0:

              // blank
              case 7:

              // empty, blank or unknown
              default:
                break;
            } // [switch]

            // close cell tag
            print $etag."<span>&nbsp;</span></td>\n";

          } // [if] skip cell within hidden columns or merged cells area
        } // [for] loop for each column

        // close row tag
        print "</tr>\n";
	flush();

      } // [if] skip hidden rows

      if( (($j % 5) == 0) || ($j==$nrows) ) {
	echo "\n<script language=\"JavaScript\">\n<!--\nsp(";
	echo (int)($j*(100/$nrows));
	echo ',';
	echo $j+1;
	echo ");\n//-->\n</script>\n";
      }

    } // [for] loop for each row

    // close table
    echo "</table>\n";
  }
}

$eexp->Close();

if( EE_DEBUG_MODE ) {
  $time2 = getmicrotime();
  $time_elapsed = round($time2-$time1,4);
  $class_size = strlen(serialize($eexp));
  $class_size = ($class_size >> 10);

  echo "\nTotal time elapsed: ".$time_elapsed." seconds, serialized class size: ".$class_size." Kb";
}

?>
<script language="JavaScript">
<!--
s=window.document.body.getElementsByTagName('span');
s['pr'].innerHTML = '';
//->
</script>
</body>
</html>