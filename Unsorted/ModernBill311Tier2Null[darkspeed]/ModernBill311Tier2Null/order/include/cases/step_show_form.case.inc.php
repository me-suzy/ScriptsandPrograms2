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

        $required = "<font color=\"red\">*</font>";
        $error_msg .= ($stop) ? "<br>".REQUIREDFILEDS :
                                "<br>".PLEASEVERIFY ;

        vortech_HTML_start(6);
        ?>
        <form method=post action="<?=$script_url?>index.php?<?=session_name()."=".session_id()?>">
        <? if($error_msg) display_error($error_msg); ?>
        <?=display_step(8)?>
        <?=display_cart_no_output()?>
        <br>
        <?=vortech_TABLE_start(CONTACTINFO)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
              <tr>
                <td width=40% align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=FIRSTNAME?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_First_Name" value="<?=ucwords($x_First_Name)?>"><b><? if (!$stop) { echo SFB.$x_First_Name.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=LASTNAME?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_Last_Name" value="<?=ucwords($x_Last_Name)?>"><b><? if (!$stop) { echo SFB.$x_Last_Name.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=SECONDARYCONTACTNAME?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="secondary_contact" value="<?=$secondary_contact?>"><b><? if (!$stop) { echo SFB.$secondary_contact.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><nobr><?=SFB?><?=COMPORDOM?>:<?=$required?><?=EF?></nobr></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Company" value="<?=strtoupper($x_Company)?>"><b><? if (!$stop) { echo SFB.$x_Company.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=ADDRESS?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_Address" value="<?=ucwords($x_Address)?>"><b><? if (!$stop) { echo SFB.$x_Address.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=ADDRESS?>:&nbsp;&nbsp;<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_Address_2" value="<?=$x_Address_2?>"><b><? if (!$stop) { echo SFB.$x_Address_2.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=CITY?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_City" value="<?=ucwords($x_City)?>"><b><? if (!$stop) { echo SFB.$x_City.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=STATEREGION?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_State" value="<?=ucwords($x_State)?>"><b><? if (!$stop) { echo SFB.$x_State.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=ZIPPOSTAL?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_Zip" value="<?=$x_Zip?>"><b><? if (!$stop) { echo SFB.$x_Zip.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=COUNTRY?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><?=select_country($x_Country)?><b><? if (!$stop) { echo SFB.$x_Country.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><nobr><?=SFB?><?=PHONE?>:<?=$required?><?=EF?></nobr></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_Phone" value="<?=$x_Phone?>"><b><? if (!$stop) { echo SFB.$x_Phone.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=FAX?>:&nbsp;&nbsp;<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=100 name="x_Fax" value="<?=$x_Fax?>"><b><? if (!$stop) { echo SFB.$x_Fax.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=PRIMARYEMAIL?>:<?=$required?><?=EF?></td>
                <td valign="top" bgcolor="<?=$tablebgcolor?>"><?=SFB."<b>".$x_Email."</b>".EF?></td>
                <input type="hidden" name="x_Email" value="<?=$x_Email?>">
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=SECONDARYEMAIL?>:<?=$required?><?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="client_secondary_email" value="<?=$client_secondary_email?>"><b><? if (!$stop) { echo SFB.$client_secondary_email.EF; } ?></b></td>
              </tr>
        </table>
        <?=vortech_TABLE_stop()?>
        <br>


        <?=vortech_TABLE_start(BILLINGINFO)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
              <tr>
                <td width=40% align="right" bgcolor="<?=$tablebgcolor?>" valign=middle><?=SFB?><?=PAYMENTMETHOD?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>" valign=top>
                <?
                if ($pay_method=="") {
                    $default_checked = "checked";
                } else {
                    $default_checked = NULL;
                }

                if ( ($allow_credit_card && $stop) || ($allow_credit_card && !$stop && $pay_method=="creditcard") ) {
                    $checked = ($pay_method=="creditcard") ? "checked" : $default_checked ;
                    echo "<input type=$radio_variable_type class=radiobox name=pay_method value=creditcard $checked> ".CREDITCARD."&nbsp;(".COMPLETESECTIONA.")&nbsp;<br>";
                }
                if ( ($allow_echeck && $stop) || ($allow_echeck && !$stop && $pay_method=="echeck") ) {
                    $checked = ($pay_method=="echeck") ? "checked" : $default_checked ;
                    echo "<input type=$radio_variable_type class=radiobox name=pay_method value=echeck $checked> ".ECHECK."&nbsp;(".COMPLETESECTIONB.")&nbsp;<br>";
                }
                if ( ($allow_paypal&&$paypal_enabled&&$tier2 && $stop) || ($allow_paypal&&$paypal_enabled&&$tier2 && !$stop && $pay_method=="paypal") ) {
                    $checked = ($pay_method=="paypal") ? "checked" : $default_checked ;
                    echo "<input type=$radio_variable_type class=radiobox name=pay_method value=paypal $checked> ".PAYPAL."&nbsp;&nbsp;<br>";
                }
                if ( ($allow_worldpay&&$worldpay_enabled&&$tier2 && $stop) || ($allow_worldpay&&$worldpay_enabled&&$tier2 && !$stop && $pay_method=="worldpay") ) {
                    $checked = ($pay_method=="worldpay") ? "checked" : $default_checked ;
                    echo "<input type=$radio_variable_type class=radiobox name=pay_method value=worldpay $checked> ".WORLDPAY."&nbsp;&nbsp;<br>";
                }
                if ( ($allow_invoice && $stop) || ($allow_invoice && !$stop && $pay_method=="invoice") ) {
                    $checked = ($pay_method=="invoice") ? "checked" : $default_checked ;
                    echo "<input type=$radio_variable_type class=radiobox name=pay_method value=invoice $checked> ".CHECKINVOICE."&nbsp;&nbsp;<br>";
                }
                ?>
                </td>
              </tr>
              <? if ( ($allow_credit_card && $stop) || ($allow_credit_card && !$stop && $pay_method=="creditcard") ) { ?>
              <tr>
                <td colspan=2 bgcolor="<?=$tablebgcolor?>" align=center><?=SFB?><b>-- <?=SECTIONA?>: <?=CREDITCARDINFO?> --</b><?=EF?></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=CARDHOLDER?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Card_Name" value="<?=$x_Card_Name?>"><b><? if (!$stop) { echo SFB.$x_Card_Name.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=CARDBANK?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Card_Bank" value="<?=$x_Card_Bank?>"><b><? if (!$stop) { echo SFB.$x_Card_Bank.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=CCNUMBER?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=20 maxlength=18 name="x_Card_Num" value="<?=$x_Card_Num?>"><b><? if (!$stop) { echo SFB."xxxx-xxxx-xxxx-".substr($x_Card_Num,-4).EF; } ?></b><br>(<?=$we_accept?>)</td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?> <?=EXPIRATIONDATE2?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>">
                 <? if ($variable_type=="text") { ?>
                    <select name=x_Exp_Month>
                    <?
                    for($i=1;$i<=12;$i++){
                        echo "<option value=\"$i\"";
                        if($x_Exp_Month==$i) { echo " SELECTED "; }
                        echo ">$i</option>";
                    }
                    ?>
                    </select>
                    /
                    <select name=x_Exp_Year>
                    <?
                    for($i=date("Y");$i<=date("Y")+10;$i++){
                        echo "<option value=\"$i\"";
                        if($x_Exp_Year==$i) { echo " SELECTED "; }
                        echo ">$i</option>";
                    }
                    ?>
                    </select>
                    (mm/yyyy)
                 <? } else { ?>
                    <input type="hidden" size=9 maxlength=7 name="x_Exp_Date" value="<?=$x_Exp_Month."/".$x_Exp_Year?>">
                    <b><? if (!$stop) { echo SFB.$x_Exp_Month."/".$x_Exp_Year.EF; } ?></b>
                 <? } ?>
                </td>
              </tr>
              <?  if ($require_cvvc_code) { ?>
              <tr>
                 <td align="right" bgcolor="<?=$tablebgcolor?>"><nobr><?=SFB?><?=CARDCODE?>:<?=EF?></nobr></td>
                 <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=6 maxlength=4 name="x_Card_Code" value="<?=$x_Card_Code?>"><b><? if (!$stop) { echo SFB.$x_Card_Code.EF; } ?></b> <?=SFB?>(<a href="#" target="whoiswin" onClick='window.open("viewcvv2.php","whoiswin","width=475, height=470, scrollbars=1,resizable=1"); return false;'><?=WHATSTHIS?></a>)<?=EF?></td>
              </tr>
              <? } ?>
              <? } ?>
              <? if ( ($allow_echeck && $stop) || ($allow_echeck && !$stop && $pay_method=="echeck") ) { ?>
              <tr>
                <td colspan=2 bgcolor="<?=$tablebgcolor?>" align=center><?=SFB?><b>-- <?=SECTIONB?>: <?=ECHECKINFO?> --</b><?=EF?></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=BANKNAME?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=20 maxlength=18 name="x_Bank_Name" value="<?=$x_Bank_Name?>"><b><? if (!$stop) { echo SFB.$x_Bank_Name.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=BANKABACODE?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Bank_ABA_Code" value="<?=$x_Bank_ABA_Code?>"><b><? if (!$stop) { echo SFB.$x_Bank_ABA_Code.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=BANKACCOUNTNUM?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Bank_Acct_Num" value="<?=$x_Bank_Acct_Num?>"><b><? if (!$stop) { echo SFB.$x_Bank_Acct_Num.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=DRIVERSLICENSE?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Drivers_License_Num" value="<?=$x_Drivers_License_Num?>"><b><? if (!$stop) { echo SFB.$x_Drivers_License_Num.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=DRIVERSESTATE?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Drivers_License_State" value="<?=$x_Drivers_License_State?>"><b><? if (!$stop) { echo SFB.$x_Drivers_License_State.EF; } ?></b></td>
              </tr>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=DRIVERSDOB?>:<?=EF?></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="x_Drivers_License_DOB" value="<?=$x_Drivers_License_DOB?>"><b><? if (!$stop) { echo SFB.$x_Drivers_License_DOB.EF; } ?></b></td>
              </tr>
              <? } ?>
        </table>
        <?=vortech_TABLE_stop()?>
        <br>

        <?=vortech_TABLE_start(ADDITIONALINFO)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
        <? if ($allow_referrer) { ?>
              <tr>
                <td width=40% align="right" bgcolor=<?=$tablebgcolor?>><nobr><?=SFB?><?=REFERREDBY?>:<?=EF?></nobr></td>
                <td bgcolor=<?=$tablebgcolor?>><?=vortech_referrer_menu($referrer)?><b><? if (!$stop) { echo SFB.$referrer.EF; } ?></b></td>
              </tr>
        <? } ?>
        <? if ($allow_domain_username) { ?>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><nobr><?=SFB?><?=USERNAME?>:<?=$required?><?=EF?></nobr></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=30 maxlength=255 name="username" value="<?=$username?>"><b><? if (!$stop) { echo SFB.$username.EF; } ?></b></td>
              </tr>
        <? } ?>
        <? if ($allow_domain_password) { ?>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><nobr><?=SFB?><?=PASSWORD_t?>:<?=$required?><?=EF?></nobr></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$pass_variable_type?>" size=30 maxlength=255 name="password" value="<?=$password?>"><b><? if (!$stop) { echo SFB.$password.EF; } ?></b></td>
              </tr>
        <? } ?>
        <? if ($allow_domain_password && $stop) { ?>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><nobr><?=SFB?><?=VERIFYPASSWORD?>:<?=$required?><?=EF?></nobr></td>
                <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$pass_variable_type?>" size=30 maxlength=255 name="pass_check" value="<?=$pass_check?>"><b><? if (!$stop) { echo SFB.$pass_check.EF; } ?></b></td>
              </tr>
        <? } elseif ($allow_domain_password && !$stop) { ?>
              <input type="<?=$pass_variable_type?>" size=30 maxlength=255 name="pass_check" value="<?=$pass_check?>">
        <? } ?>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=COMMENTS?>:<?=EF?></nobr></td>
                <td bgcolor="<?=$tablebgcolor?>"><? if ($stop) { ?><textarea maxlength=1000 rows=4 cols=40 name="comments" wrap=VIRTUAL><?=$comments?></textarea><?}?><b><? if (!$stop) { echo SFB.$comments.EF; echo "<input type=hidden name=comments value=\"$comments\">"; } ?></b></td>
              </tr>
        <?
        include($DIR."include/config/config.client_extras.php");
        for($ic=1;$ic<=10;$ic++)
        {
           if (${"client_field_active_$ic"}&&${"client_field_vortech_$ic"})
           {
             $this_input_value = array("column"       => "client_field_$ic",
                                       "required"      => ${"client_field_required_$ic"},
                                       "title"         => ${"client_field_title_$ic"},
                                       "type"          => ${"client_field_type_$ic"},
                                       "size"          => ${"client_field_size_$ic"},
                                       "maxlength"     => ${"client_field_maxlength_$ic"},
                                       "admin_only"    => ${"client_field_admin_only_$ic"},
                                       "append"        => ${"client_field_append_$ic"},
                                       "default_value" => ${"client_field_append_$ic"});

              $display_required = ($this_input_value[required]) ? $required : NULL ;
              ?>
              <tr>
                <td align="right" bgcolor="<?=$tablebgcolor?>"><?=SFB?><?=$this_input_value[title]?>:<?=$display_required?><?=EF?></td>
                <?
                switch ($this_input_value[type]) {
                   case TEXTAREA: ?> <td bgcolor="<?=$tablebgcolor?>"><? if ($stop) { ?><textarea maxlength=<?=$this_input_value[maxlength]?> rows=<?=$this_input_value[size]?> cols=40 name="<?=$this_input_value[column]?>" wrap=VIRTUAL><?=${$this_input_value[column]}?></textarea><?}?><b><? if (!$stop) { echo SFB.${$this_input_value[column]}.EF; echo "<input type=hidden name=\"".$this_input_value[column]."\" value=\"".${$this_input_value[column]}."\">"; } ?></b></td> <? break;
                   case TEXT:     ?> <td bgcolor="<?=$tablebgcolor?>"><input type="<?=$variable_type?>" size=<?=$this_input_value[size]?> maxlength=<?=$this_input_value[maxlength]?> name="<?=$this_input_value[column]?>" value="<?=${$this_input_value[column]}?>"><b><? if (!$stop) { echo SFB.${$this_input_value[column]}.EF; } ?></b></td> <? break;
                }
              ?>
              </tr>
              <?
           }
        }
        ?>
        </table>
        <?=vortech_TABLE_stop()?>
        <br>

        <?=vortech_TABLE_start(SUBMITINFO)?>
        <table cellpadding=3 cellspacing=1 border=0 width=100%>
               <tr>
                 <td bgcolor=<?=$tablebgcolor?> colspan=2 align=center>
                  <a href=<?=$path_to_terms?> target=_blank><?=IHAVEREAD?></a>.<br><br>
                  <? $tos = ($tos) ? $tos : 0 ; ?>
                  <? if ($stop) { ?>
                     <input type=<?=$radio_variable_type?> class=radiobox name=tos value=1 <? if ($tos==1) echo "checked"; ?>> <?=YES?>&nbsp;&nbsp;&nbsp;
                     <input type=<?=$radio_variable_type?> class=radiobox name=tos value=0 <? if ($tos==0) echo "checked"; ?>> <?=NO?>
                  <? } else { ?>
                     <input type=<?=$radio_variable_type?> class=radiobox name=tos value=<?=$tos?>><b><? echo ($tos) ? YES : NO ; ?></b>
                  <? } ?>
                 </td>
               </tr>
               <tr>
                 <td colspan=2 bgcolor=<?=$tablebgcolor?>>
                  <b><?=SUBMITCHECK1?></b><br>
                  <li> <?=SUBMITCHECK2?><br>
                  <li> <?=SUBMITCHECK3?><br>
                  <li> <?=SUBMITCHECK4?>
                 </td>
               </tr>
               <tr>
                 <td colspan=2 bgcolor=<?=$tablebgcolor?>>
                  <b><?=FRAUDCHECK1?></b>:<br>
                  <li> <?=getenv("REMOTE_ADDR")?> <?=FRAUDCHECK2?><br>
                  <li> <?=date($default_stamp)?> <?=FRAUDCHECK3?><br>
                 </td>
               </tr>
               <tr>
                 <td colspan=2 bgcolor=<?=$tablebgcolor?> align=center>
                  <? if ($stop) { ?>
                     <input type=submit value="<?=VERIFYMYORDER?>" name=submit_verify>
                  <? } else { ?>
                     <input type=submit value="<?=CORRECTMYDATA?>" name=submit_correct>&nbsp;
                     <input type=submit value="<?=PROCESSMYORDER?>" name=submit_process>
                  <? } ?>
                 </td>
               </tr>
        </table>
        <?=vortech_TABLE_stop()?>
        </form>
        <?
        vortech_HTML_stop(6);
?>