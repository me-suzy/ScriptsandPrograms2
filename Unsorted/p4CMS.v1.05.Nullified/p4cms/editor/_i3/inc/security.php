<?
session_start();
if(isset($HTTP_GET_VARS["password"])) {
        if($HTTP_GET_VARS["password"] == $settings[password]) {
                $password = $HTTP_GET_VARS["password"];
                session_register("password");
                Header("Location: _editor.php?".$HTTP_GET_VARS["query_string"]."");
        }
}

if((!isset($HTTP_SESSION_VARS["password"])) || ($HTTP_SESSION_VARS["password"] != $settings[password])) {
        $login_form = "";
        $login_form .= "<html><head><title>".$settings[product_name]." ".$settings[version]." (build ".$settings[build].")</title>";
        $login_form .= "</head>";
        $login_form .= "<body leftmargin=\"2\" marginwidth=\"2\" topmargin=\"2\" marginheight=\"2\">";
        $login_form .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" height=\"100%\" style=\"border: 1px solid ".$settings[border_color]."; background-color: ".$settings[bgcolor]."\">";
        $login_form .= "<tr><td align=center valign=\"middle\">";
        $login_form .= "<form method=\"GET\" action=\"_editor.php\" style=\"margin: 0px;\">";
        $login_form .= "<table cellspacing=\"1\" cellpadding=\"4\" border=\"0\"><tr><td>";
        $login_form .= "<span style=\"font: 11px Verdana;\">".$lang[enter_password].": </span></td><td>";
        $login_form .= "<input type=\"text\" name=\"password\" size=\"24\" style=\"font: 11px Verdana;\"></td></tr>";
        $login_form .= "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"".$lang[submit]."\" style=\"font: 11px Verdana;\">";
        $login_form .= "<input type=\"hidden\" name=\"query_string\" value=\"".$HTTP_SERVER_VARS['QUERY_STRING']."\"></td></tr></table>";
        $login_form .= "</form>";
        $login_form .= "</td></tr></table>";
        $login_form .= "</body></html>";

        echo $login_form;
        die;
}

if(isset($HTTP_GET_VARS["logout"])) {
        session_unset($HTTP_SESSION_VARS["password"]);
}


?>