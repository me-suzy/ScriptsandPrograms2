<?php

// defines if this script requires to be logged in
define( "PRIVATE", false );

# CALL COMMON LIBRARIES

require_once('./lib/inc-common.php');
require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-random.php");

if (strtolower($_REQUEST['do']) == 'select') {

  if ( (abs($_REQUEST['limit_pl']) < 10) || (abs($_REQUEST['limit_pl']) > 100) ) $_REQUEST['limit_pl'] = 10;
  
  $sql  = " select tr.id ";
  $sql .= " from netjuke_tracks tr, netjuke_artists ar, netjuke_albums al, netjuke_genres ge ";
  $sql .= " where tr.ar_id = ar.id and tr.al_id = al.id and tr.ge_id = ge.id ";
  
  // if we received a list of genre ids, add them them to the sql
  
  $ge_sql = array();
  
  foreach ($_REQUEST['genres'] as $ge_id) {
  
    if (abs($ge_id) != 0) {
    
      array_push($ge_sql," tr.ge_id = ".$ge_id);
    
    }
  
  }
  
  if (count($ge_sql) > 0) $sql .= " and ( ".join(" or ",$ge_sql)." ) ";
  
  if ((strlen($_REQUEST['min_time']) > 0) && (is_numeric($_REQUEST['min_time']))) {
     $sql .= " and tr.time >= ".$_REQUEST['min_time']." ";
  }
  
  if ((strlen($_REQUEST['max_time']) > 0) && (is_numeric($_REQUEST['max_time']))) {
     $sql .= " and tr.time <= ".$_REQUEST['max_time']." ";
  }
  
  // global srand call is made in lib/inc-common.php - 2002-03-25
  // srand ((float)microtime()*1000000);
  
  // the next 2 arrays (sort_options & sort_order) are shuffled to 
  // try to have a db independent random selection, by selecting a
  // larger dataset, then randomizing the "order by" clause at the
  // sql level. More randomizing is done in php later on.
  
  $sort_options = array('tr.name','tr.size','tr.time','ar.name','al.name','ge.name');
  shuffle ($sort_options);

  $sort_order   = array('asc','desc');
  shuffle ($sort_order);
  
  $sql .= " order by ".implode(",",$sort_options)." ".$sort_order[0]." ";
  
  // comment out the next line and uncomment the 2 others to implement
  // some more drastic db recordset limits to help reduce the load on
  // the server.
  $limit_rs = 10000;
  // $limit_rs = ($_REQUEST['limit_pl'] * 50);
  // if ($limit_rs > 5000) $limit_rs = 5000;
  
  
  // execute sql
  
  $dbrs = $dbconn->SelectLimit($sql,$limit_rs);
  
  // DEBUG echo $dbrs->RecordCount();

  // prompt an error if there are less than 2 tracks in the
  // recordset or stack the ids in an array to be shuffled
  // and processed later on
  
  $selection = array();
  
  if ($dbrs->RecordCount() < 2) {
  
    alert(RNDM_NO_TR);
    exit;
  
  } else {
    
    while (!$dbrs->EOF) {
      
      array_push($selection, $dbrs->fields[0]);
        
      $dbrs->MoveNext();
  
    }
  
  }
  
  $dbrs->Close();
  
  shuffle ($selection);
  
  // now that we have a "random" large dataset, we "randomly"
  // select the number of tracks the user wants to limit the
  // playlist by.
  
  $my_cnt = count($selection);
  
  // existing counter synchronization
  
  if ($my_cnt <= $limit_rs) $limit_rs = $my_cnt;
  
  if ($limit_rs <= $_REQUEST['limit_pl']) $_REQUEST['limit_pl'] = $limit_rs;
  
  $keepers = array();
  
  // loop until we reach the playlist limit.
  
  while (count($keepers) < $_REQUEST['limit_pl']) {
    
    $randnum = abs( rand( 0, $limit_rs ) - 1 );
    
    if ($randnum <= 0) $randnum = 0;
    
    if ( !in_array($selection[$randnum], $keepers) ) {
    
      array_push($keepers, $selection[$randnum]);
    
    }
  
  }
  
  // format the resulting ids and feed them to play.php
  // if in anonymous mode, or pl-edit.php if logged-in.
  
  $tr_list = join(",",$keepers);
  
  if (strlen($tr_list) < 1) $tr_list = 1;
  
  if ($NETJUKE_SESSION_VARS["email"] == "") {
  
    header("Location: ".WEB_PATH."/play.php?do=play&val=".$tr_list);
  
  } else {
  
    header("Location: ".WEB_PATH."/pl-edit.php?do=addto&random=1&pl_id=&val=".$tr_list);
  
  }

} else {

// just display the form.

# GENRE MENU

  $ge_html = '';
  
  $dbrs = $dbconn->Execute(  "SELECT id, name "
                           . "from netjuke_genres "
                           . " where track_cnt > 0 "
                           . "order by upper(name) asc " );

  while (!$dbrs->EOF) {
    
    $genre_menu .= "<option value='".$dbrs->fields[0]."'> - ".format_for_display($dbrs->fields[1])."   </option>";
    
    $dbrs->MoveNext();

  }

  $dbrs->Close();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
			"http://www.w3.org/TR/REC-html40/loose.dtd">

<HTML>
<HEAD>
	<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo  LANG_CHARSET ?>">
	<TITLE><?php echo  RNDM_FORM_HEADER ?></TITLE>
    <style type="text/css">
      <?php require_once(FS_PATH."/lib/inc-css.php"); ?>
    </style>
</HEAD>
<BODY BGCOLOR='#<?php echo $NETJUKE_SESSION_VARS["bgcolor"]?>' TEXT='#<?php echo $NETJUKE_SESSION_VARS["text"]?>' LINK='#<?php echo $NETJUKE_SESSION_VARS["link"]?>' ALINK='#<?php echo $NETJUKE_SESSION_VARS["alink"]?>' VLINK='#<?php echo $NETJUKE_SESSION_VARS["vlink"]?>' ONLOAD='self.focus();'>
	<a name="PageTop"></a>

  <div align=center>
    <table width='100%' border=0 cellspacing=1 cellpadding=3 class='border'>
      <form method="post" action="<?php echo  $_SERVER['PHP_SELF'] ?>" target="NetjukeMain">
      <input type="hidden" name="do" value="select">
        <tr>
            <td class="header" colspan=2><b><?php echo  RNDM_FORM_HEADER ?></b></td>
        </tr>
        <tr>
            <td width="40%" align=right valign=top class="content"><?php echo  RNDM_FORM_LIMIT_1 ?>:</td>
            <td width="60%" align=left valign=top class="content">
            <select name="limit_pl">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <?php echo  RNDM_FORM_LIMIT_2 ?>
            </td>
        </tr>
        <tr>
            <td width="40%" align=right valign=top class="content"><?php echo  RNDM_FORM_GENRES ?>:</td>
            <td width="60%" align=left valign=top class="content">
              <select name="genres[]" size="10" multiple>
                <option value='0' selected> - <?php echo  RNDM_FORM_GENRES_ALL ?>   </option>
                <?php echo  $genre_menu ?>
              </select>
            </td>
        </tr>
        <tr>
            <td width="40%" align=right valign=top class="content"><?php echo  RNDM_FORM_TRACKMINTIME_1 ?>:</td>
            <td width="60%" align=left valign=top class="content">
            <input type="text" size="5" name="min_time">
            <?php echo  RNDM_FORM_TRACKMINTIME_2 ?>
            </td>
        </tr>
        <tr>
            <td width="40%" align=right valign=top class="content"><?php echo  RNDM_FORM_TRACKMAXTIME_1 ?>:</td>
            <td width="60%" align=left valign=top class="content">
            <input type="text" size="5" name="max_time">
            <?php echo  RNDM_FORM_TRACKMAXTIME_2 ?>
            </td>
        </tr>
        <tr>
            <td colspan=2 align=center valign=middle class="content">
              <input type=submit name="btn_search" value='<?php echo  RNDM_FORM_BTN_SEARCH ?>' class='btn_content'>
            </td>
        </tr>
      </form>
    </table>

		<br>
		<table width='100%' border=0 cellspacing=1 cellpadding=3 class="border">
			<tr>
				<td width="" align="center" class="content"><a href="javascript:window.close();" title="<?php echo  RNDM_CLOSEWIN_HELP ?>"><b><?php echo  RNDM_CLOSEWIN ?></b></a></td>
			</tr>
		</table>
  </div>

	<a name="PageBot"></a>
</BODY>
</HTML>

<?php 

}

?>
