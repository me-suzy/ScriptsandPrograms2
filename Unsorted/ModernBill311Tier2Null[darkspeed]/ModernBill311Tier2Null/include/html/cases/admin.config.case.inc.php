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
?>
<tr>
  <td width=50% valign=top>
    <?=start_box(SYSTEMCONFIG)?>
           <table width=250 cellpadding=2 cellspacing=2>
            <tr>
             <td><b><?=SFB.MAINCONFIG.EF?></b></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|main><?=VIEW?></a><?=EF?></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|main><?=EDIT?></a><?=EF?></td>
            </tr>
            <tr>
             <td><b><?=SFB.EMAILCONFIG.EF?></b></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|email><?=VIEW?></a><?=EF?></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|email><?=EDIT?></a><?=EF?></td>
            </tr>
            <tr>
             <td><b><?=SFB.PAYMENTSCONFIG.EF?></b></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|payments><?=VIEW?></a><?=EF?></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|payments><?=EDIT?></a><?=EF?></td>
            </tr>
            <tr>
             <td><b><?=SFB.ADMINCONFIG.EF?></b></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=view&db_table=admin&tile=<?=$tile?>><?=VIEW?></a><?=EF?></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=admin&tile=<?=$tile?>><?=ADD?></a><?=EF?></td>
            </tr>
            <tr>
             <td><b><?=SFB.CLIENTSCONFIG15.EF?></b></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|client_extras_1_5><?=VIEW?></a><?=EF?></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|client_extras_1_5><?=EDIT?></a><?=EF?></td>
            </tr>
            <tr>
             <td><b><?=SFB.CLIENTSCONFIG610.EF?></b></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|client_extras_6_10><?=VIEW?></a><?=EF?></td>
             <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|client_extras_6_10><?=EDIT?></a><?=EF?></td>
            </tr>
           </table>
     <hr size=1>
     <?=stop_box()?>
     <br>
     <?=start_box(SYSTEMSETUP)?>
     <?=admin_settings_menu($tile)?>
     <hr size=1>
     <?=stop_box()?>
     <br>
    <?=start_box(SYSTEMUTILITIES)?>
    <?=admin_utilities_menu($tile)?>
    <hr size=1>
    <?=stop_box()?>
    <br>
     <?=start_box(SYSTEMDISPLAY)?>
           <table align=center border=0 cellpadding=2 cellspacing=2>
             <form method=post action=<?=$page?>>
             <input type=hidden name=tile value=config>
              <tr><td colspan=3><?=SFB?><b><?=PLEASESELOPTIONS?></b><?=EF?></td></tr>
              <tr><td><?=SFB.LANGUAGE?>:<?=EF?><br><?=language_select_box($language);?></td>
                  <td><?=SFB.THEME?>:<?=EF?><br><?=theme_select_box($theme);?></td>
                  <td><?=SFB?>&nbsp;<?=EF?><br><?=SUBMIT_IMG?></td>
              </tr>
             </form>
            </table>
     <hr size=1>
    <?=stop_box()?>
    <br>
  </td>
  <td width=50% valign=top>

        <table border=1 width=90% height=100% align=center>
          <tr>
           <td>
           <table cellpadding=2 cellspacing=2>
            <?
            $result=mysql_query("SELECT * FROM config WHERE config_type LIKE '%vortech%'");
            while(list($this_type)=mysql_fetch_array($result))
            {
                ?>
                <tr>
                <td><b><?=SFB."Vortech: \"$this_type\"".EF?></b></td>
                <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|<?=$this_type?>><?=VIEW?></a><?=EF?></td>
                <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|<?=$this_type?>><?=EDIT?></a><?=EF?></td>
                </tr>
                <?
            }
            ?>
            <tr><td colspan=3><hr size=1></td></tr>
            <form method=post action=<?=$page?>?op=insert_vortech>
            <input type=hidden name=tile value=config>
            <tr>
             <td colspan=3>
             <center><b><?=MFB.CREATENEWVORTECH.EF?></b></center><br><br>
             <?=SFB."<b>".STEP1."</b>: ".COPYVORTECHDIR.EF?><br><br>
             <?=SFB."<b>".STEP2."</b>: ".EDITVORTECHCONFIGFILE.EF?><br><br>
             <?=SFB."<b>".STEP3."</b>: ".INSERTVORTECHDEFAULTCONFIG.EF?><br><br>
             <center><input type=text name=new_vortech value="vortech_type??" size=15 maxlength=50>&nbsp;<input type=submit name=submit value="<?=GO?>"></center><br><br>
             <?=SFB."<b>".STEP4."</b>: ".EDITNEWVORTECHVARIABLES.EF?><br>
             </td>
            </tr>
            </form>
           </table>
           </td>
          </tr>
         </table>

         <br>

         <table border=1 width=90% height=100% align=center>
          <tr>
           <td>
           <table cellpadding=2 cellspacing=2>
            <?
            $result=mysql_query("SELECT * FROM config WHERE config_type LIKE '%theme%'");
            while(list($this_type)=mysql_fetch_array($result))
            {
                ?>
                <tr>
                <td><b><?=SFB.THEME.": \"$this_type\"".EF?></b></td>
                <td align=center><?=SFB?><a href=<?=$page?>?op=details&db_table=config&tile=<?=$tile?>&id=config_type|<?=$this_type?>><?=VIEW?></a><?=EF?></td>
                <td align=center><?=SFB?><a href=<?=$page?>?op=form&db_table=config&tile=<?=$tile?>&id=config_type|<?=$this_type?>><?=EDIT?></a><?=EF?></td>
                </tr>
                <?
            }
            ?>
            <tr><td colspan=3><hr size=1></td></tr>
            <form method=post action=<?=$page?>?op=insert_theme>
            <input type=hidden name=tile value=config>
            <tr><td colspan=3>
            <center><b><?=MFB.CREATENEWTHEME.EF?></b></center><br><br>
            <?=SFB."<b>".STEP1."</b>: ".COPYTHEMEDIR.EF?><br><br>
            <?=SFB."<b>".STEP2."</b>: ".EDITTHEMECONFIGFILE.EF?><br><br>
            <?=SFB."<b>".STEP3."</b>: ".INSERTTHEMEDEFAULTCONFIG.EF?><br><br>
            <center><input type=text name=new_theme value="theme_??" size=15 maxlength=50>&nbsp;<input type=submit name=submit value="<?=GO?>"></center><br><br>
            <?=SFB."<b>".STEP4."</b>: ".EDITNEWTHEMEVARIABLES.EF?><br>
            </td></tr>
            </form>
           </table>
          </td>
         </tr>
        </table>
  </td>
</tr>