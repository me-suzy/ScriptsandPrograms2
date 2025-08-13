<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

if (!$dbh) dbconnect();

$this_theme_config = mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type = 'theme_default'",$dbh));

if (mysql_query("INSERT INTO config VALUES ('$new_theme',
                                            '$this_theme_config[1]',
                                            '$this_theme_config[2]',
                                            '$this_theme_config[3]',
                                            '$this_theme_config[4]',
                                            '$this_theme_config[5]',
                                            '$this_theme_config[6]',
                                            '$this_theme_config[7]',
                                            '$this_theme_config[8]',
                                            '$this_theme_config[9]',
                                            '$this_theme_config[10]',
                                            '$this_theme_config[11]',
                                            '$this_theme_config[12]',
                                            '$this_theme_config[13]',
                                            '$this_theme_config[14]',
                                            '$this_theme_config[15]',
                                            '$this_theme_config[16]',
                                            '$this_theme_config[17]',
                                            '$this_theme_config[18]',
                                            '$this_theme_config[19]',
                                            '$this_theme_config[20]',
                                            '$this_theme_config[21]',
                                            '$this_theme_config[22]',
                                            '$this_theme_config[23]',
                                            '$this_theme_config[24]',
                                            '$this_theme_config[25]',
                                            '$this_theme_config[26]',
                                            '$this_theme_config[27]',
                                            '$this_theme_config[28]',
                                            '$this_theme_config[29]',
                                            '$this_theme_config[30]',
                                            '$this_theme_config[31]',
                                            '$this_theme_config[32]',
                                            '$this_theme_config[33]',
                                            '$this_theme_config[34]',
                                            '$this_theme_config[35]',
                                            '$this_theme_config[36]',
                                            '$this_theme_config[37]',
                                            '$this_theme_config[38]',
                                            '$this_theme_config[39]',
                                            '$this_theme_config[40]',
                                            '$this_theme_config[41]',
                                            '$this_theme_config[42]',
                                            '$this_theme_config[43]',
                                            '$this_theme_config[44]',
                                            '$this_theme_config[45]',
                                            '$this_theme_config[46]',
                                            '$this_theme_config[47]',
                                            '$this_theme_config[48]',
                                            '$this_theme_config[49]',
                                            '$this_theme_config[50]')"))

{
  $response = "<font color=blue>$new_theme <b>".OK."</b></font>";
}
else
{
  $response = "<font color=red>$new_theme config <b>".NOTOK."</b></font>";
}
        start_html();
        admin_heading($tile);
        start_table(CREATENEWtheme,$a_tile_width);
        echo "<tr><td colspan=3 align=center>".SFB.$response.EF."<hr size=1></td></tr>";
        ?>
        <tr>
        <td align=center><b><?=SFB.THEME.": \"$new_theme\""?></b> <a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|<?=$new_theme?>><?=VIEW?></a> <a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|<?=$new_theme?>><?=EDIT?></a><?=EF?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <?
        stop_table();
        stop_html();
?>