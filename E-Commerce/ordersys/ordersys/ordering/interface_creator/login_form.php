<table summary="none" class="table_login_form" align="center">
    <tr valign="top" align="center">
        <td colspan="2" align="center" style="text-align:center;">
            <?php
            if($parentsite_name != '')
            {echo ('<a href="'.$parentsite_url.'" onclick="javascript:window.open(this.href); window.close(); return false;">'.$parentsite_name.'</a> | ');}
            echo ('<a href="'.$dadabik_main_file.'" onclick="javascript:window.open(this.href); window.close(); return false;">'.$site_name.'</a>');
            ?>
        </td>
    </tr>
    <tr class="tr_header_login_form">
        <td valign="top" style="align:center; text-align:center;">
            <br />
            <b>
                <?php echo $login_messages_ar['please_authenticate']; ?>
            </b>
        </td>
    </tr>
    <tr valign="top" align="center">
        <td valign="top" align="center" style="align:center; text-align:center;">
            <form method="post" action="<?php echo $dadabik_login_file.'?function='.$type_of_login; ?>">
                <table summary="none">
                    <tr align="center">
                        <td colspan="2" style="align:center; text-align:center;">
                            <?php txt_out($login_message, 'error_messages_form'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="align:right; text-align:right;">
                            <?php echo $login_messages_ar['username']; ?>
                        </td>
                        <td style="align:left; text-align:left;">
                            <input type="text" name="username_user" id="username_user" class="input_login_form" />
                        </td>
                    </tr>
                    <tr>
                        <td style="align:right; text-align:right;">
                            <?php echo $login_messages_ar['password']; ?>
                        </td>
                        <td style="align:left; text-align:left;">
                            <input type="password" name="password_user" id="password_user" class="input_login_form" />
                            <input type="hidden" id="go_to" name="go_to" value="<?php echo $go_to; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="align:right; text-align:right;">
                            Remember me
                        </td>
                        <td style="align:left; text-align:left;">
                            <input type="checkbox" name="remember_me" id="remember_me" class="input_login_form" />
                            <input type="hidden" id="go_to" name="go_to" value="<?php echo $go_to; ?>" />
                        </td>
                    </tr>
                    <tr align="center">
                        <td colspan="2" style="align:center; text-align:center;">
                            <input type="submit" value="<?php echo $login_messages_ar['login']; ?>" />
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr valign="top" align="center">
        <td colspan="2" align="center" style="text-align:center;">
            <?php
            if($mainsite_name != '')
            {echo ('<a href="'.$mainsite_url.'" onclick="javascript:window.open(this.href); window.close(); return false;">'.$mainsite_name.'</a>');}
            ?>
        </td>
    </tr>
</table>
