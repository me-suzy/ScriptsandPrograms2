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

if(!$dbh)dbconnect();

$this_vortech_config = mysql_fetch_array(mysql_query("SELECT * FROM config WHERE config_type = 'vortech_type1'",$dbh));

if (mysql_query("INSERT INTO config VALUES ('$new_vortech',
                                            '$this_vortech_config[1]',
                                            '$this_vortech_config[2]',
                                            '$this_vortech_config[3]',
                                            '$this_vortech_config[4]',
                                            '$this_vortech_config[5]',
                                            '$this_vortech_config[6]',
                                            '$this_vortech_config[7]',
                                            '$this_vortech_config[8]',
                                            '$this_vortech_config[9]',
                                            '$this_vortech_config[10]',
                                            '$this_vortech_config[11]',
                                            '$this_vortech_config[12]',
                                            '$this_vortech_config[13]',
                                            '$this_vortech_config[14]',
                                            '$this_vortech_config[15]',
                                            '$this_vortech_config[16]',
                                            '$this_vortech_config[17]',
                                            '$this_vortech_config[18]',
                                            '$this_vortech_config[19]',
                                            '$this_vortech_config[20]',
                                            '$this_vortech_config[21]',
                                            '$this_vortech_config[22]',
                                            '$this_vortech_config[23]',
                                            '$this_vortech_config[24]',
                                            '$this_vortech_config[25]',
                                            '$this_vortech_config[26]',
                                            '$this_vortech_config[27]',
                                            '$this_vortech_config[28]',
                                            '$this_vortech_config[29]',
                                            '$this_vortech_config[30]',
                                            '$this_vortech_config[31]',
                                            '$this_vortech_config[32]',
                                            '$this_vortech_config[33]',
                                            '$this_vortech_config[34]',
                                            '$this_vortech_config[35]',
                                            '$this_vortech_config[36]',
                                            '$this_vortech_config[37]',
                                            '$this_vortech_config[38]',
                                            '$this_vortech_config[39]',
                                            '$this_vortech_config[40]',
                                            '$this_vortech_config[41]',
                                            '$this_vortech_config[42]',
                                            '$this_vortech_config[43]',
                                            '$this_vortech_config[44]',
                                            '$this_vortech_config[45]',
                                            '$this_vortech_config[46]',
                                            '$this_vortech_config[47]',
                                            '$this_vortech_config[48]',
                                            '$this_vortech_config[49]',
                                            '$this_vortech_config[50]'
                                            )"))
{
  $response = "<font color=blue>$new_vortech <b>".OK."</b></font>";
}
else
{
  $response = "<font color=red>$new_vortech config <b>".NOTOK."</b></font>";
}
        start_html();
        admin_heading($tile);
        start_table(CREATENEWVORTECH,$a_tile_width);
        echo "<tr><td align=center>".SFB.$response.EF."<hr size=1></td></tr>";
        ?>
        <tr>
        <td align=center><b><?=SFB.THEME.": \"$new_vortech\""?></b> <a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|<?=$new_vortech?>><?=VIEW?></a> <a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|<?=$new_vortech?>><?=EDIT?></a><?=EF?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <?
        stop_table();
        stop_html();
?>