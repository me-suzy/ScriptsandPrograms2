<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# CALL COMMON LIBRARIES

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-pl-shrd-list.php");

# make sure the community feature is enabled
if (ENABLE_COMMUNITY != 't') header(WEB_PATH."/index.php");

$row_cnt = 10;

# $row_cnt LATEST SHARED PLAYLISTS

  $shrd_html = '';

  $dbrs = $dbconn->Execute( " select count(id) "
                          . " from netjuke_plists "
                          . " where shared_list = 't' " );
 
  $total = $dbrs->fields[0];

  if ($_REQUEST['page_nav']) {
    list($_REQUEST['first'],$_REQUEST['page']) = split(",",$_REQUEST['page_nav']);
  }
  
  if ($_REQUEST['first'] < 1) $_REQUEST['first'] = 1;
  $plus = abs(RES_PER_PAGE);
  if ($plus < 1) $plus = 25;
  $last = $_REQUEST['first'] + $plus;
  $prev_first = $_REQUEST['first'] - $plus;
 
  if ($_REQUEST['page'] < 1) $_REQUEST['page'] = 1;
  
  $dbrs = $dbconn->SelectLimit(  " SELECT id, us_email, title, comment "
                               . " from netjuke_plists "
                               . " where shared_list = 't' "
                               . " order by created desc "
                               , ($last-1) );

  $page_cnt = 1;
   
  $rows = $dbrs->RecordCount();

  while (!$dbrs->EOF) {
    
     if ($page_cnt == $last) {
      
        break;
      
     } else {

      if ($page_cnt >= $_REQUEST['first']) {
    
        $sql2 = " select sum(tr.time), sum(tr.size) "
              . " from netjuke_plists_tracks pt, netjuke_tracks tr "
              . " where pt.pl_id = ".$dbrs->fields[0]
              . " and tr.id = pt.tr_id " ;
    
        $dbrs2 = $dbconn->Execute($sql2);
    
        $shrd_html .= "<TR><td width='2%' align=left class='content' nowrap>"
                   .  "<a href=\"".WEB_PATH."/play.php?do=plist&val=".$dbrs->fields[0]."\" title=\"".PLSHRD_SHRD_PLAY_HELP."\"><img src='".$ICONS['play']."' alt='".PLSHRD_SHRD_PLAY_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='absmiddle'></a> "
                   .  "<a href=\"".WEB_PATH."/pl-shrd-view.php?do=view&pl_id=".$dbrs->fields[0]."&section=community\" title=\"".PLSHRD_SHRD_VIEW_HELP."\"><img src='".$ICONS['info']."' alt='".PLSHRD_SHRD_VIEW_HELP."' width='8' height='8' hspace='0' vspace='0' border='0' align='middle'></a> "
                   .  "</td><td width='43%' align=left class='content'>"
                   .  "<a href=\"".WEB_PATH."/pl-shrd-view.php?do=view&pl_id=".$dbrs->fields[0]."&section=community\" title=\"".PLSHRD_SHRD_VIEW_HELP."\">"
                   .  $dbrs->fields[2]
                   .  "</a>"
                   .  "</td><td width='35%' align=left class='content'>"
                   .  PLSHRD_SHRD_BY.": ".get_display_name($dbrs->fields[1])
                   .  "</td><td width='20%' align=left class='content'>"
                   .  myTimeFormat($dbrs2->fields[0])." ".PLSHRD_SHRD_FOR." ".myFilesizeFormat($dbrs2->fields[1]);

        $dbrs2->Close();
        
        if ($dbrs->fields[3] != '') $shrd_html .= "<br>" . $dbrs->fields[3];
        
        $shrd_html .= "</td></tr>";
      
      }
       
      $page_cnt++;
      
      $dbrs->MoveNext();
    
    }

  }

  $dbrs->Close();
  
  if ($prev_first > 0) {
    $prev_btn = "<b>&laquo; <a href='".$_SERVER['PHP_SELF']."?first=$prev_first&page=".($_REQUEST['page'] - 1)."' title=\"".PLSHRD_TB_PREV_HELP."\">".PLSHRD_TB_PREV."</a> &nbsp;";
  } else {
    $prev_btn = "&nbsp";
  }
  
  if ($page_cnt <= $total) {
    $next_btn = "<b><a href='".$_SERVER['PHP_SELF']."?first=$last&page=".($_REQUEST['page'] + 1)."' title=\"".PLSHRD_TB_NEXT_HELP."\">".PLSHRD_TB_NEXT."</a> &raquo;</b> &nbsp;";
  } else {
    $next_btn = "&nbsp";
  }
  
  $page_cnt = 0;
  
  $this_page  = 1;

  $this_prev_first = 1;
  
  while ($page_cnt < $total) {
    
    if ($page_cnt % $plus == 0) {

      $this_first = $this_prev_first;

      if ($this_page == $_REQUEST['page']) {
        $page_selected = "selected";
      } else {
        $page_selected = "";
      }
      $page_nav_html .= "<option value='$this_first,$this_page' $page_selected>".PLSHRD_TB_PAGE." $this_page</option>";
  
      $this_prev_first = $this_first + $plus;
      
       $this_page++;
    
    }
     
    $page_cnt++;
 
  }

# HTML HEADER

  $section = "community";
  include (INTERFACE_HEADER);

?>

        <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method=get name="pageForm">
         <tr>
           <td width="25%" class="content" align=center nowrap>
             <?php echo $prev_btn?>
           </td>
           <td width="25%" class="content" align=center nowrap>
             <a href="#PageBot" title="<?php echo  PLSHRD_TB_PAGEBOT_HELP ?>"><b><?php echo  PLSHRD_TB_PAGEBOT ?></b></a>
           </td>
           <td width="25%" class="content" align=center>
             <select name="page_nav" class=input_content>
               <?php echo $page_nav_html?>
             </select>
             <input type=submit value='<?php echo  PLSHRD_TB_PAGE_BTN ?>' class='btn_content'>
           </td>
           <td width="25%" class="content" align=center nowrap>
             <?php echo $next_btn?>
           </td>
        </tr>
        </form>
        </table>

        <BR>
  
         <div align=center>
         <table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
         <tr>
           <td class="header" colspan=4 nowrap><B><?php echo  PLSHRD_SHRD_HEADER ?></B></td>
         </tr>

         <?php echo  $shrd_html ?>

         </table>
         </div>


        <BR>

        <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method=get name="pageForm">
         <tr>
           <td width="25%" class="content" align=center nowrap>
             <?php echo $prev_btn?>
           </td>
           <td width="25%" class="content" align=center nowrap>
             <a href="#PageTop" title="<?php echo  PLSHRD_TB_PAGETOP_HELP ?>"><b><?php echo  PLSHRD_TB_PAGETOP ?></b></a>
           </td>
           <td width="25%" class="content" align=center>
             <select name="page_nav" class=input_content>
               <?php echo $page_nav_html?>
             </select>
             <input type=submit value='<?php echo  PLSHRD_TB_PAGE_BTN ?>' class='btn_content'>
           </td>
           <td width="25%" class="content" align=center nowrap>
             <?php echo $next_btn?>
           </td>
        </tr>
        </form>
        </table>

<?php 

  # HTML footer 
  include (INTERFACE_FOOTER);

?>
