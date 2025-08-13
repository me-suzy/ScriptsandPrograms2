<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

      vortech_HTML_start(1);
      ?>
      <!-------- DOMAIN SEARCH ------->
      <? if($error_msg) display_error($error_msg); ?>
      <?=display_step(1)?>
      <?=vortech_TABLE_start(DOMAINNAME)?>
      <table cellpadding=3 cellspacing=0 border=0 width=100%>
      <? if ($allow_domain_register) { ?>
      <form method=post action="<?=$script_url_non_secure?>index.php?<?=session_name()."=".session_id()?>">
      <tr><td bgcolor=<?=$tablebgcolor?> align=center><B><?=REGISTERNEW?></B></td></tr>
      <tr><td bgcolor=<?=$tablebgcolor?> align=center>www.<input type=text name=domain value="" size=30 maxlength=63><font color=<?=$tablebgcolor?>>.</font><?=tld_select_box($id)?>&nbsp;<input type=submit name=submit_register value="<?=SUBMIT?>"></td></tr>
      </form>
      <? } ?>
      <? if ($allow_domain_register&&$allow_domain_transfer) { ?>
      <tr><td bgcolor=<?=$tablebgcolor?> align=center><hr size=1></td></tr>
      <? } ?>
      <? if ($allow_domain_transfer) { ?>
      <form method=post action="<?=$script_url_non_secure?>index.php?<?=session_name()."=".session_id()?>">
      <tr><td bgcolor=<?=$tablebgcolor?> align=center><B><?=USEMYDOMAIN?></B></td></tr>
      <tr><td bgcolor=<?=$tablebgcolor?> align=center>www.<input type=text name=domain value="" size=30 maxlength=63>.<input type=text name=tld_extension value="" size=5 maxlength=25>&nbsp;<input type=submit name=submit_transfer value="<?=SUBMIT?>"></td></tr>
      </form>
      <? } ?>
      <tr><td bgcolor=<?=$tablebgcolor?> align=center>&nbsp;</td></tr>
      </table>
      <?=vortech_TABLE_stop()?>
      <br>
      <?
      vortech_HTML_stop(1);
?>