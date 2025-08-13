<?php
//Read in config file
$thisfile = "log";
$admin = 1;
include("../includes/config.php");
include("../includes/stats_lib.php");
include("../includes/hierarchy_lib.php");

$query = "SELECT sum(link_hits) FROM inl_links";
$rs = &$conn->Execute($query);
if ($rs && !$rs->EOF) 
	$hit_count =$rs->fields[0];

$query="SELECT count(log_id) FROM inl_search_log";
$rs = &$conn->Execute($query);
if ($rs && !$rs->EOF) 
	$search_count =$rs->fields[0];

?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<link rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon5-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav4 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#stats"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <?php
  if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_statistics, $la_title_search_log, $la_title_reports);
	$nav_links_admin[$la_title_statistics]="log.php$att_sid";
	$nav_links_admin[$la_title_search_log]="search_log.php$att_sid";
	$nav_links_admin[$la_title_reports]="reports.php$att_sid";
	echo display_admin_nav($la_title_statistics, $nav_names_admin, $nav_links_admin);
?>

  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_statistics ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 

        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle"><?php echo $la_databases ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_number_of ?>
              <?php echo $la_records ?></td><td class="text" colspan="2"><b>
		
			<?php echo stats_num_fields(); ?>

            </b></td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_number_of ?>
              <?php echo $la_links ?></td>
            <td class="text"><b>
			
		
			<?php echo stats_num_links(); ?>
		
			
			
			</b> </td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <?php echo $la_pending ?>
              <?php echo $la_links ?>

            </td>
            <td class="text"><b>
			
			<?php echo stats_num_pendlinks(); ?>

			
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_number_of ?>
              <?php echo $la_categories ?></td>
            <td class="text"><b>
			
			<?php echo stats_num_cats(); ?>
			
			</b> </td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <?php echo $la_pending ?>
              <?php echo $la_categories ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_pedcats(); ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_number_of ?>
              <?php echo $la_reviews ?></td>
            <td class="text"><b>
			
			<?php echo stats_num_reviews(); ?>

			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_number_of ?>
              <span class="new"><?php echo $la_new ?></span>
			  <?php echo $la_links ?></td>
            <td class="text"><b>
			
			<?php echo stats_num_newlinks(); ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <span class="new"><?php echo $la_new ?></span>
              <?php echo $la_categories ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_newcats(); ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <span class="pick"><?php echo $la_pick ?></span>
              <?php echo $la_links ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_picklinks(); ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <span class="pick"><?php echo $la_pick ?></span>
              <?php echo $la_categories ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_pickcats(); ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <span class="pop"><?php echo $la_pop ?></span>
              <?php echo $la_links ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_poplinks(); ?>
						
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <span class="top"><?php echo $la_top ?></span>
              <?php echo $la_links ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_toplinks(); ?>

			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <?php echo $la_hidden ?>
              <?php echo $la_links ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_hiddenlinks() ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <?php echo $la_hidden ?>
              <?php echo $la_categories ?>
            </td>
            <td class="text"><b>
			
			<?php echo stats_num_hiddencats() ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_number_of ?>
              <?php echo $la_users ?></td>
            <td class="text"><b>
			
			<?php echo stats_num_users() ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">&nbsp;</td>
            <td class="text" colspan="2">&nbsp;</td>
          </tr>

          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle"><?php echo $la_visitors ?></td>
          </tr>

          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"> 
              <?php echo $la_number_of ?>
              <?php echo $la_searches ?></td>
            <td class="text"> <b>
			
			<?php echo $search_count ?>
			
			</b> </td>
            <td class="text"> 
             &nbsp;
            </td>
          </tr>

          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle"><?php echo $la_reviews_and_votes ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_total_linkhits ?></td>
            <td class="text"><b>
			
			<?php echo stats_num_linkhits() ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_total_votes ?></td>
            <td class="text"> <b>

			<?php echo stats_num_linkvotes() ?>
			
			</b> </td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_average_vote ?></td>
            <td class="text"><b>
			
			<?php echo stats_num_avgvotes() ?>
			
			</b></td>
            <td class="text"> 
              <div align="right"> </div>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2">&nbsp;</td>
            <td class="text">&nbsp;</td>
          </tr>
		 
        </table>
        <br>

    </td>
  </tr> 
</table>
<p>&nbsp; </p>
</body>
</html>