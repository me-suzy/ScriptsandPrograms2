<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$CONST_LINK_SITE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Content-Language" CONTENT="EN">
<META NAME="DESCRIPTION" content="Start your search for romance here. The best free online dating service, premium online dating service, dating tips and matchmaking resources on the internet to help you find that special someone.">
<META NAME="KEYWORDS" CONTENT="online dating, free dating service, dating services, matchmaking, love, personals, dating tips">
<META NAME="ROBOTS" content="INDEX, FOLLOW">
<META NAME="CLASSIFICATION" CONTENT="personals, dating, singles, matchmaking, love, relationships">
<link rel="stylesheet" href="<?=$CONST_LINK_ROOT?>/style.css" type="text/css">
<SCRIPT language="JavaScript" src="<?=$CONST_LINK_ROOT?>/search.js"></SCRIPT>
<SCRIPT language="JavaScript" src="<?=$CONST_LINK_ROOT?>/validate_addurl.js"></SCRIPT>
</head>
<body>
<div class="topWhite">
  <table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="250"><a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_LINK_ROOT?>/images/logo.gif" alt="OnDating.com" width="200" height="80" border="0"></a></td>
      <td width="520" align="right" valign="bottom" nowrap><img src="<?=$CONST_LINK_ROOT?>/images/strap.gif" alt="" width="260" height="50" border="0"><a href="mailto:<?=$CONST_LINK_EMAIL?>"><img src="<?=$CONST_LINK_ROOT?>/images/i-mail.gif" alt="Contact Us" width="50" height="50" border="0"></a><a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_LINK_ROOT?>/images/i-home.gif" alt="Home" width="50" height="50" border="0"></a></td>
    </tr>
  </table>
</div>
<div class="greyBar"><img src="<?=$CONST_LINK_ROOT?>/images/spacer.gif" alt="Online Dating" height="10"></div>
<div class="navBar">
  <table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="nav"><img src="<?=$CONST_LINK_ROOT?>/images/arrow.gif" alt=""><a href="<?=$CONST_LINK_ROOT?>/articles.php">Articles</a><img src="<?=$CONST_LINK_ROOT?>/images/arrow.gif" alt=""><a href="<?=$CONST_LINK_ROOT?>/advertise.php">Advertise</a><img src="<?=$CONST_LINK_ROOT?>/images/arrow.gif" alt=""><a href="<?=$CONST_LINK_ROOT?>/add_site.php">Add URL</a><img src="<?=$CONST_LINK_ROOT?>/images/arrow.gif" alt=""><a href="<?=$CONST_LINK_ROOT?>/rules.php">Rules</a><img src="<?=$CONST_LINK_ROOT?>/images/arrow.gif" alt=""><a href="<?=$CONST_LINK_ROOT?>/aboutus.php">About Us</a></td>
    </tr>
  </table>
</div>
<div class="searchBar">
  <table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td class="search"><table border="0" cellspacing="0" cellpadding="0">

          	<form method='post' action='<?=$CONST_LINK_ROOT?>/search.php' name='frmSearch'>
            <tr>
              <td valign="top"><input type="text" name="txtSearch" class="searchBox"></td>
              <td><input name="imageField" type="image" src="<?=$CONST_LINK_ROOT?>/images/i-search.gif" width="42" height="38" border="0"></td>
              <td valign="top"><table border="0" cellspacing="0" cellpadding="9">
                  <tr>
                    <td><a class="search" href="javascript:MDM_openWindow('<?=$CONST_LINK_ROOT?>/search_help.htm','Search','width=375,height=375')" onmouseover="window.status=''; return true" onmouseout="window.status='';return true">Search help</a></td>
                  </tr>
                </table></td>
            </tr>
          </form>
        </table></td>
    </tr>
  </table>
</div>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>

    <td width="180" valign="top" class="main">
				<?php
					# directory menu

					$categories=mysql_query("SELECT DISTINCT(cat_parent) FROM dir_categories",$link);
					while ($directory_menu_top=mysql_fetch_object($categories)) {
						print("<div  class='sideNavHead'><img src='$CONST_LINK_ROOT/images/arrow.gif' alt=''>$directory_menu_top->cat_parent</div>");
						$sub_categories=mysql_query("SELECT cat_child, cat_id FROM dir_categories WHERE cat_parent = '$directory_menu_top->cat_parent'",$link);

						while ($directory_menu=mysql_fetch_object($sub_categories)) {
							$sub_category.="<a href='$CONST_LINK_ROOT/directory.php?cat=$directory_menu->cat_id'>$directory_menu->cat_child</a><br>";
						}
						print("<div class='sideNavBody'>$sub_category</div>");
						$sub_category="";
					}
				?>
	</td>
    <td width="590" valign="top" class="main"><p><?php echo $banner_text; ?></p>
