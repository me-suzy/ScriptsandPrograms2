<?
//####################################################################
// Active PHP Bookmarks - lbstone.com/apb/
//
// Filename: cookie_auth.php
// Author:   L. Brandon Stone (lbstone.com)
//           Nathanial P. Hendler (retards.org)
//
// 2001-09-05 11:36     Created
//
//####################################################################

include_once('apb.php');

if ($APB_SETTINGS['auth_type'] == 'cookie') {

    //####################################################################
    // Cookie login/logout.
    //####################################################################

    // Login Form
    if ($action == 'cookie_login') {

        apb_head();
        ?>
          <h2>User Login</h2>
          <form action="<? echo $SCRIPT_NAME ?>?action=set_cookie_login" method="post">
          <table cellpadding="5" cellspacing="0" border="0">
          <tr>
            <td>Username:</td>
            <td><input name="form_username"></td>
          </tr><tr>
            <td>Password:</td>
            <td><input type="password" name="form_password"></td>
          </td>
          </table>

          <p><input type="checkbox" name="login_type" value="permanent"> Remember Me
          <p><input type="submit" value="Login">
          </form>
        <?
        apb_foot();

    }

    // Login
    if ($action == "set_cookie_login")
    {
        $expiration_date = time()+(60*60*24*365*10); // Expire in 10 years.
        if ($login_type != "permanent") { $expiration_date = 0; } // Expire when browser is closed.

        setcookie("cookie_username", strtolower($form_username), $expiration_date);
        setcookie("cookie_password", crypt($form_password, "27"), $expiration_date);
        header ("Location: ".$APB_SETTINGS['apb_url']);
        exit;
    }

    // Logout
    if ($action == "cookie_logout")
    {
        setcookie("cookie_username", "");
        setcookie("cookie_password", "");
        header ("Location: ".$APB_SETTINGS['apb_url']);
        exit;
    }

} else {
    apb_head();
    print "<b>System is set for HTTPD Authentication.  Something is wrong</b><p>\n";
    error("cookie_auth.php: System is set for HTTPD Authentication.  Something is wrong");
    apb_foot();
}

/*
// General where clause addition for public.
if ($private_session) { $where_public = ""; }
else { $where_public = "AND public = 1"; }
*/

?>