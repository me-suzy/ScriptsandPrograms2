<?php


//---------------------------------------------------
//	Admin Notification of New Member Registrations
//--------------------------------------------------

function admin_notify_reg_title()
{
return <<<EOF
Ilmoitus uuden jäsenen rekisteröitymisestä
EOF;
}

function admin_notify_reg()
{
return <<<EOF
Seuraava henkilö on rekisteröitynyt uutena jäsenenä: {name}

Sivulla: {site_name}

Sinun ohjauspaneelin URL: {control_panel_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Comment
//--------------------------------------------------

function admin_notify_comment_title()
{
return <<<EOF
Ole juuri vastaanottanut kommentin
EOF;
}

function admin_notify_comment()
{
return <<<EOF
Olet juuri vastaanottanut kommentin seuraavalta weblogilta:
{weblog_name}

Viestin otsikko on:
{entry_title}

Sijainti: 
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
Olet juuri vastaanottanut trackbackin
EOF;
}

function admin_notify_trackback()
{
return <<<EOF
Olet juuri vastaanottanut trackbakin seuraavaan viestiin:
{entry_title}

Located at: 
{comment_url}

Trackback lähetettiin seuraavalta weblogilta:
{sending_weblog_name}

Viestin otsikko:
{sending_entry_title}

Weblogin URL:
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
Seuraavassa aktivointi koodisi
EOF;
}

function mbr_activation_instructions()
{
return <<<EOF
Kiitos rekisteröitymisestäsi uudeksi jäseneksi.

Aktivoidaksesi tilisi, seuraa seuraavaa linkkiä:

{activation_url}

Kiittäen!

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
Kirjautumisohjeet
EOF;
}

function forgot_password_instructions()
{
return <<<EOF
{name},

Resetoidaksesi salasanasi, mene seuraavalle sivulle:

{reset_url}

Salasanasi on automaattisesti resetoitu ja uusi salasana lähetettiin sähköpostiisi.

Jos et halua resetoida salasanaasi, unohda tämä viesti. Viesti vanhenee 24 tunnissa.

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
Uusi kirjautumisohje
EOF;
}

function reset_password_notification()
{
return <<<EOF
{name},

Tässä on sinun uudet tunnukset:

Käyttäjätunnus: {username}
Salasana: {password}

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
Sinun jäsenyystilisi on aktivoitu
EOF;
}

function validated_member_notify()
{
return <<<EOF
{name},

Sinun jäsenyystilisi on aktivoitu ja valmiina käyttöön.

Kiittäen!

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
Sähköpostivahvistus
EOF;
}

function mailinglist_activation_instructions()
{
return <<<EOF
Kiitos liittymisestäsi postituslistallemme!

Klikkaa allaolevaan linkkiä vahvistaaksesi liittymisesi postituslistallemme.

Jos et halua liittyä listallemme, unohda tämä viesti.

{activation_url}

Kiittäen!

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
Joku juuri vastasi kommenttiisi
EOF;
}

function comment_notification()
{
return <<<EOF
Joku juuri vastasi viestiisi jonka kirjoitit sivulla:
{weblog_name}

Viestin otsikko on:
{entry_title}

Voi nähdä kommentin seuraamalla tätä linkkiä:
{comment_url}

Lopeta ilmoitukset tämän kommentin osalta, klikkaa tästä:
{notification_removal_url}
EOF;
}
// END


?>