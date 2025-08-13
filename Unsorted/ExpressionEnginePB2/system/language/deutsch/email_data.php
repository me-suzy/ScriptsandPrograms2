<?php


//---------------------------------------------------
//	Admin Notification of New Member Registrations
//--------------------------------------------------

function admin_notify_reg_title()
{
return <<<EOF
Benachrichtigung: Ein neues Mitglied hat sich angemeldet
EOF;
}

function admin_notify_reg()
{
return <<<EOF
Die folgende Person hat eine Anmeldung abgeschickt: {name}

An: {site_name}

Der URL Ihres Control-Panels: {control_panel_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Comment
//--------------------------------------------------

function admin_notify_comment_title()
{
return <<<EOF
Jemand hat einen Kommentar zu Ihrem Weblog geschrieben
EOF;
}

function admin_notify_comment()
{
return <<<EOF
Jemand hat einen Kommentar zum folgenden Weblog geschrieben:
{weblog_name}

Der Titel des Eintrags lautet:
{entry_title}

Zu finden unter:
{comment_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Trackback
//--------------------------------------------------

function admin_notify_trackback_title()
{
return <<<EOF
Jemand hat einen Trackback zu Ihrem Weblog geschickt
EOF;
}

function admin_notify_trackback()
{
return <<<EOF
Jemand hat einen Trackback zu dem folgenden Eintrag geschickt:
{entry_title}

Zu finden unter:
{comment_url}

Der Trackback kam vom folgenden Weblog:
{sending_weblog_name}

Titel des Eintrags:
{sending_entry_title}

Weblog-URL:
{sending_weblog_url}
EOF;
}
// END


//---------------------------------------------------
//	Membership Activation Instructions
//--------------------------------------------------

function mbr_activation_instructions_title()
{
return <<<EOF
Hier ist Ihr Aktivierungscode
EOF;
}

function mbr_activation_instructions()
{
return <<<EOF
Danke fuer Ihre Anmeldung.

Um Ihren neuen Account zu aktivierten, gehen Sie bitte zu:

{activation_url}

Danke!

{site_name}

{site_url}
EOF;
}
// END


//---------------------------------------------------
//	Member Forgotten Password Instructions
//--------------------------------------------------

function forgot_password_instructions_title()
{
return <<<EOF
Login-Information
EOF;
}

function forgot_password_instructions()
{
return <<<EOF
Hallo, {name},

um Ihr Passwort zurueckzusetzen, gehen Sie bitte zu:

{reset_url}

Sie erhalten dann automatisch ein neues Passwort zugeschickt.

Wenn Sie Ihr Passwort nicht aendern wollen, ignorieren Sie diese Nachricht einfach. Sie wird in 24 Stunden ungueltig.

{site_name}
{site_url}
EOF;
}
// END



//---------------------------------------------------
//	Reset Password Notification
//--------------------------------------------------

function reset_password_notification_title()
{
return <<<EOF
Neue Login-Information
EOF;
}

function reset_password_notification()
{
return <<<EOF
Hallo, {name},

hier ist Ihre neue Login-Information:

Username: {username}
Passwort: {password}

{site_name}
{site_url}
EOF;
}
// END



//---------------------------------------------------
//	Validated Member Notification
//--------------------------------------------------

function validated_member_notify_title()
{
return <<<EOF
Ihr Account ist jetzt aktiv!
EOF;
}

function validated_member_notify()
{
return <<<EOF
Hallo, {name},

Ihr Account ist jetzt aktiv und bereit fuer Ihr Login.

Danke!

{site_name}
{site_url}
EOF;
}
// END




//---------------------------------------------------
//	Mailinglist Activation Instructions
//--------------------------------------------------

function mailinglist_activation_instructions_title()
{
return <<<EOF
Email-Bestaetigung
EOF;
}

function mailinglist_activation_instructions()
{
return <<<EOF
Danke, dass Sie sich fuer unsere Mailing-Liste interessieren!

Bitte, klicken Sie auf den unten stehenden Link, um Ihre Mail-Adresse zu bestaetigen.

Wenn Sie keine Post von dieser Liste erhalten wollen, ignorieren Sie diese Nachricht einfach.

{activation_url}

Danke!

{site_name}
EOF;
}
// END



//---------------------------------------------------
//	Comment Notification
//--------------------------------------------------

function comment_notification_title()
{
return <<<EOF
Jemand hat auf Ihren Kommentar geantwortet
EOF;
}

function comment_notification()
{
return <<<EOF
Jemand hat auf den Kommentar geantwortet, den Sie bei
{weblog_name} hinterlassen haben.

Der Titel des Eintrags lautet:
{entry_title}

Sie finden den Kommentar unter:
{comment_url}

Wenn Sie keine weitere Benachrichtigungen erhalten wollen, klicken Sie bitte hier:
{notification_removal_url}
EOF;
}
// END


?>