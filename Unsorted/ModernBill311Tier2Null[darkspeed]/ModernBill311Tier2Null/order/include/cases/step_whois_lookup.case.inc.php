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

        vortech_HTML_start(2);
        ?>
        <!---- DOMAIN SEARCH RESULTS --->
        <?
        echo "<center><span id=\"processing\">&nbsp;&nbsp;&nbsp;".SFB.PROCESSING." ...".EF."</span></center>";
        flush();
        ?>
        <form method=post action="<?=$script_url_non_secure?>index.php?<?=session_name()."=".session_id()?>">
        <? if($error_msg) display_error($error_msg); ?>
        <?=display_step(2)?>
        <?=vortech_TABLE_start(DOMSEARCHRESULTS)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
        <?
        if (!$dbh) dbconnect();
        $sql = "SELECT tld_extension, tld_auto_search FROM tld_config WHERE tld_accepted=2 ORDER BY tld_id";
        $tld_result = mysql_query($sql);
        while(list($new_tld_extension,$tld_auto_search) = mysql_fetch_array($tld_result))
        {
             $tld_auto_search = ($disable_whois) ? 0 : $tld_auto_search ;
             if ($tld_auto_search == 1 || $new_tld_extension == $tld_extension)
             {
                 $registered        = NULL;
                 $this_lookup       = array();
                 $new_tld_extension = strtolower(trim($new_tld_extension));
                 $domain            = strtolower(trim(strip_tags($domain)));
                 $this_lookup       = (!$disable_whois) ? whois($domain,$new_tld_extension) : NULL ;
                 $registered        = $this_lookup[is_registered];
                 $whois_www_link    = ($allow_whois_www_link) ? ": <a href=\"http://www.$domain.$new_tld_extension\" target=_blank>".WWW."</a>, <a href=\"viewwhois.php?domain=$domain&ext=$new_tld_extension\" target=\"whoiswin\" onClick='window.open(\"viewwhois.php?domain=$domain&ext=$new_tld_extension\", \"whoiswin\", \"width=475, height=280, scrollbars=1,resizable=1\"); return false;'>".WHOIS."</a>" : NULL ;

                 #if ( isset($submit_transfer) && ( $tld_extension == $new_tld_extension ) && $registered )
                 if ( isset($submit_transfer) && $tld_extension == $new_tld_extension )
                     /* ( isset($submit_transfer) && $tld_extension == $new_tld_extension && $registered ) ) */
                 {
                     $checkbox   = "<input type=checkbox name=domains[] value=\"transfer|$domain|$new_tld_extension\" checked> ".TRANSFER."$whois_www_link";
                     $transfer_added = TRUE;
                 }
                 else
                 {
                     $checked    = ($tld_extension==$new_tld_extension) ? " CHECKED" : NULL ;
                     $checkbox   = ($registered) ?
                                    REGISTERED.$whois_www_link :
                                    "<input type=checkbox name=domains[] value=\"register|$domain|$new_tld_extension\" $checked> ".ISAVAILABLE."!" ;
                 }
                 foreach($cart[domains] as $key => $value)
                 {
                     if ($query==$key)
                     {
                         $in_cart=TRUE;
                     }
                 }
                 if(!$in_cart)
                 {
                     echo "<tr bgcolor=$tablebgcolor><td width=30%><b>$domain.$new_tld_extension</b></td><td>$checkbox</td></tr>";
                 }
                 else
                 {
                     $in_cart = FALSE;
                 }
             }
        } #--> while loop

        if (isset($submit_transfer) && !$transfer_added) {
            $checkbox   = "<input type=checkbox name=domains[] value=\"transfer|$domain|$tld_extension\" checked> ".TRANSFER."$whois_www_link";
            echo "<tr bgcolor=$tablebgcolor><td width=30%><b>$domain.$tld_extension</b></td><td>$checkbox</td></tr>";
        }

        foreach($cart[domains] as $key => $value)
        {
            list($register,$domain,$tld_extension) = $value;
            $text = ($register=="register") ? ISAVAILABLE : TRANSFER ;
            $checkbox = "<input type=checkbox name=domains[] value=\"$register|$domain|$tld_extension\" CHECKED> $text!";
            echo "<tr bgcolor=$tablebgcolor><td width=30%><b>$key</b></td><td>$checkbox</td></tr>";
        }
        ?>
        <tr>
        <td colspan=2 bgcolor=<?=$tablebgcolor?> align=center valign=bottom>
        <?=SELECT2?>&nbsp;<input value="<?=CONTINUE_t?>" name=submit_package_select type=submit>&nbsp;<?=OR_t?>&nbsp;<input value="<?=SEARCHAGAIN?>" name=submit_search_again type=submit>
        </td>
        </tr>
        </table>
        <?=vortech_TABLE_stop()?>
        </form>
        <? echo "<script>processing.style.display='none'</script>"; ?>
        <?
        vortech_HTML_stop(2);
?>
