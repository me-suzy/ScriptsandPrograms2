<?php
require_once("admin_max_settings.php"); require_once("db.php"); if (check_user2()) { if (isset($delete)){ db_query("delete from ttp_archive where siteid='$site_id'");  db_query("delete from ttp_traffic where siteid='$site_id'");
db_query("delete from ttp_sites where siteid='$site_id'");  header("Location: admin.php\n\n"); } if (isset($blacklist)){ db_query("delete from ttp_archive where siteid='$site_id'");db_query("delete from ttp_traffic where siteid='$site_id'"); db_query("update ttp_sites set active=-2 where siteid='$site_id'");
header("Location: admin.php\n\n"); } if (isset($save)){ db_query("update ttp_sites set active=$active, perm=$perm, force=$force, manage_type=$manage, send_ratio=$ratio, wname='".urlencode($wname)."', email='".urlencode($email)."', icqnumb='$icqnumb', icqname='$icqname' where siteid=$site_id"); header("Location: admin.php\n\n");}
?>

<html><head><title>Stats</title>
<STYLE type=text/css>.title {FONT: 10pt Verdana, Helvetica, sans-serif}</STYLE>
<STYLE type=text/css>.main {FONT: 8pt Verdana, Helvetica, sans-serif; color:white}</STYLE>
<STYLE type=text/css>.small {FONT: 7pt Verdana, Helvetica, sans-serif; color:white}</STYLE>
<STYLE type=text/css>
A:link {COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:visited {COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:active {COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:hover {COLOR: #0000FF; TEXT-DECORATION: none}
</STYLE></head>

<body bgcolor=#000000 background=../assets/am-interfacev1_r6_c4.jpg leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<table height=280 border=0 cellpadding=0 cellspacing=0 background=../assets/am-interfacev1_r6_c4.jpg align=center>
  <tr>
    <td valign=top align=center class=main>
   <table border="0" cellpadding="3" cellspacing="1" width=720>
    <tr bgcolor="#425B7E"><td class="main" align=left colspan=2><b>Manage</b>
    <?php
        $h_data = db_query("select * from ttp_sites where siteid='$site_id'");
        $h_row = mysql_fetch_array($h_data);
        echo urldecode($h_row["sitename"])."&nbsp;&nbsp;<a href=\"".urldecode($h_row["siteurl"])."\" target=_blank>".urldecode($h_row["siteurl"])."</a>\n";
    ?>
    </td></tr>
    <tr bgcolor="#557AB1"><td colspan=2>

    <table align=right cellspacing=0 cellpadding=0 border=0>
    <tr><form><td valign=middle>
    <input type=submit name=delete value="delete">&nbsp;
    <input type=hidden name=site_id value="<?php echo $site_id;?>">
    </td></form><form>
    <td valign=middle>
    &nbsp;<input type=submit name=blacklist value="blacklist">
    <input type=hidden name=site_id value="<?php echo $site_id;?>">
    </td></form>
    </tr></table>

    </td></tr>
    <form>
                <tr><td class="main" align=right bgcolor="#425B7E" width=150>Active Trade:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=radio name=active value=1 <?php if($h_row["active"]) echo "checked";?>>Yes <input type=radio name=active value=0 <?php if(!($h_row["active"])) echo "checked";?>>No</td></tr>
                <tr><td class="main" align=right bgcolor="#425B7E" width=150>Permanent Trade (Never Delete):&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=radio name=perm value=1 <?php if($h_row["perm"]) echo "checked";?>>Yes <input type=radio name=perm value=0 <?php if(!($h_row["perm"])) echo "checked";?>>No</td></tr>
                <tr><td class="main" align=right bgcolor="#425B7E" width=150>Daily Force:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=text name=force size=6 maxsize=5 value="<?php echo $h_row["force"];?>"></td></tr>
                <td class="main" align=right bgcolor="#425B7E">Traffic Management:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1">
    <input type=radio name=manage value=0 <?php if($h_row["manage_type"]==0) echo "checked";?>>Automatic Management
    <input type=radio name=manage value=1 <?php if($h_row["manage_type"]==1) echo "checked";?>>Set Send Ratio: <input type=text size=4 name=ratio maxsize=4 value="<?php echo $h_row["send_ratio"];?>">%
    </td></tr>
    <tr><td class="main" align=right bgcolor="#425B7E" width=150>Webmaster Name:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=text size=30 name=wname value="<?php echo urldecode($h_row["wname"]);?>"></td></tr>
                <tr><td class="main" align=right bgcolor="#425B7E" width=150>Webmaster Email:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=text size=30 name=email value="<?php echo urldecode($h_row["email"]);?>"></td></tr>
                <tr><td class="main" align=right bgcolor="#425B7E" width=150>Webmaster ICQ:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=text size=30 name=icqnumb value="<?php echo $h_row["icqnumb"];?>"></td></tr>
                <tr><td class="main" align=right bgcolor="#425B7E" width=150>Webmaster ICQ Name:&nbsp;</td>
    <td class="main" align=left bgcolor="#557AB1"><input type=text size=30 name=icqname value="<?php echo $h_row["icqname"];?>"></td></tr>
    <tr bgcolor="#425B7E"><td class="main" align=right colspan=2><input type=submit name=save value="Save Settings"><input type=hidden name=site_id value="<?php echo $site_id;?>"></td>
    </tr></form>
   </table>
    </td>
  </tr>
</body>
</html>

<?php
db_close(); exit(); } else { db_close(); header("Location: login.htm\n\n"); exit();
}
?>
