<?php


//---------------------------------------------------
//	Admin Notification of New Member Registrations
//--------------------------------------------------

function admin_notify_reg_title()
{
return <<<EOF
Nieuw lid geregistreerd
EOF;
}

function admin_notify_reg()
{
return <<<EOF
De volgende persoon heeft zich geregistreerd: {name}

Op: {site_name}

De URL van je Control Panel: {control_panel_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Comment
//--------------------------------------------------

function admin_notify_comment_title()
{
return <<<EOF
Er is een reactie geplaatst
EOF;
}

function admin_notify_comment()
{
return <<<EOF
Er is een reactie gekomen op uw bericht van de volgende site:
{weblog_name}

De titel van het bericht is:
{entry_title}

U kunt het vinden op: 
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
U heeft een trackback ontvangen
EOF;
}

function admin_notify_trackback()
{
return <<<EOF
Er is zojuist een Trackback ontvangen op het volgende bericht:
{entry_title}

U kunt het vinden op: 
{comment_url}

De Trackback is afkomstig van de volgende site:
{sending_weblog_name}

Titel van het bericht:
{sending_entry_title}

URL van de site:
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
Uw activeringscode
EOF;
}

function mbr_activation_instructions()
{
return <<<EOF
Dank u wel dat u zich geregistreerd heeft. 

Om uw account te activeren moet u op onderstaande link klikken:

{activation_url}

Bedankt!

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
Login informatie
EOF;
}

function forgot_password_instructions()
{
return <<<EOF
Beste {name},

Om uw wachtwoord te resetten kunt u op onderstaande link klikken

{reset_url}

Uw wachtwoord zal dan automatisch gereset worden en een nieuw wachtwoord zal automatisch toegestuurd worden via de mail.

Als je je wachtwoord niet wilt resetten, doe dan verder niets met dit bericht. Het zal automatisch naar 24 uur verlopen.

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
Nieuwe log-in gegevens
EOF;
}

function reset_password_notification()
{
return <<<EOF 
Hallo {name},

Hierbij uw nieuwe log-in gegevens:

Gebruikersnaam: {username}
Wachtwoord: {password}

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
Uw account is geactiveerd
EOF;
}

function validated_member_notify()
{
return <<<EOF 
Hallo {name},

Uw account is geactiveerd en kan nu worden gebruikt.

Bedankt!

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
Email Bevestiging
EOF;
}

function mailinglist_activation_instructions()
{
return <<<EOF
Bedankt voor uw aanmelding op de mailinglijst!

Klik op onderstaande link om uw email adres te bevestigen.

Als u niet toegevoegd wilt worden aan onze mailinglist, negeer deze email dan.

{activation_url}

Bedankt!

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
Reactie op uw bericht
EOF;
}

function comment_notification()
{
return <<<EOF
Iemand heeft gereageerd op het bericht waar u zich voor ingeschreven had:
{weblog_name}

De titel van het bericht is:
{entry_title}

Als u de reactie wilt bekijken dan kunt u op onderstaande link klikken:
{comment_url}

Wilt u geen verdere emails meer ontvangen over dit bericht klik dan hier:
{notification_removal_url}
EOF;
}
// END


?>