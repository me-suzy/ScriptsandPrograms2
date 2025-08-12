<?php

/***************************************************************************
 *                            lang_user_search.php (English)
 *                              -------------------
 *     begin                : Sat Apr 10, 2004
 *     copyright            : (C) 2004 Adam Alkins
 *     email                : phpbb at rasadam dot com
 *	   $Id: lang_user_search.php,v 1.9 2004/11/17 22:50:55 rasadam Exp $
 *    
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

$lang['Search_invalid_username'] = 'Ongeldige gebruikersnaam ingevuld';
$lang['Search_invalid_email'] = 'Ongeldig e-mail adres ingevuld';
$lang['Search_invalid_ip'] = 'Ongeldig IP adres ingevuld';
$lang['Search_invalid_group'] = 'Ongeldige Groep ingevuld';
$lang['Search_invalid_date'] = 'Ongeldige datum ingevuld';
$lang['Search_invalid_postcount'] = 'Ongeldig Bericht aantal ingevoerd';
$lang['Search_invalid_userfield'] = 'Ongeldige gebruikersveld data ingevoerd';
$lang['Search_invalid_language'] = 'Ongeldige Taal Geselecteerd';
$lang['Search_invalid_style'] = 'Ongeldige Stijl geselecteerd';
$lang['Search_invalid_timezone'] = 'Ongeldige Tiijdszone geselecteerd';
$lang['Search_invalid_moderators'] = 'Ongeldig Forum Selected';
$lang['Search_invalid'] = 'Ongeldige zoekactie';
$lang['Search_invalid_day'] = 'De dag die je hebt ingetypt is ongeldig';
$lang['Search_invalid_month'] = 'De maand die je hebt ingetypt is ongeldig';
$lang['Search_invalid_year'] = 'Het jaar dat je hebt ingetypt is ongeldig';
$lang['Search_no_regexp'] = 'Jouw database ondersteunt het gebruik van \'Regular Expression Zoeken\' NIET.';
$lang['Search_for_username'] = 'Zoeken gebruikersnamen overeenkomend met %s';
$lang['Search_for_email'] = 'Zoeken emailadressen overeenkomend met %s';
$lang['Search_for_ip'] = 'Zoeken IP adressen overeenkomend met %s';
$lang['Search_for_date'] = 'Zoeken gebruikers die lid werden op %s %d/%d/%d';
$lang['Search_for_group'] = 'Zoeken groep leden van %s';
$lang['Search_for_banned'] = 'Zoeken naar verbande/gebande gebruikers';
$lang['Search_for_admins'] = 'Zoeken naar Beheerders';
$lang['Search_for_mods'] = 'Zoeken naar Moderators';
$lang['Search_for_disabled'] = 'Zoeken naar niet actieve/geldige leden';
$lang['Search_for_disabled_pms'] = 'Zoeken naar gebruikers die het prive berichten systeem uitgeschakeld hebben staan';
$lang['Search_for_postcount_greater'] = 'Zoeken naar gebruikers met meer berichten dan %d';
$lang['Search_for_postcount_lesser'] = 'Zoeken naar gebruikers met minder berichten dan %d';
$lang['Search_for_postcount_range'] = 'Zoeken naar gebruikers met het aantal berichten tussen %d en %d';
$lang['Search_for_postcount_equals'] = 'Zoeken naar gebruikers met %d berichten';
$lang['Search_for_userfield_icq'] = 'Zoeken naar gebruikers met een ICQ adres overeenkomend met %s';
$lang['Search_for_userfield_yahoo'] = 'Zoeken naar gebruikers met een Yahoo IM overeenkomend met %s';
$lang['Search_for_userfield_aim'] = 'Zoeken naar gebruikers met een AIM overeenkomend met %s';
$lang['Search_for_userfield_msn'] = 'Zoeken naar gebruikers met een MSN Messenger overeenkomend met %s';
$lang['Search_for_userfield_website'] = 'Zoeken naar gebruikers met een Website overeenkomend met %s';
$lang['Search_for_userfield_location'] = 'Zoeken naar gebruikers met een Locatie overeenkomend met %s';
$lang['Search_for_userfield_interests'] = 'Zoeken naar gebruikers met interress's overeenkomend met %s';
$lang['Search_for_userfield_occupation'] = 'Zoeken naar gebruikers met het beroep van %s';
$lang['Search_for_language'] = 'Zoeken naar gebruikers die %s als hun taal hebben ingesteld';
$lang['Search_for_timezone'] = 'Zoeken naar gebruikers die GMT %s als hun tijdszone hebben ingesteld';
$lang['Search_for_style'] = 'Zoeken naar gebruikers die %s als hun stijl hebben ingesteld';
$lang['Search_for_moderators'] = 'Zoeken naar moderators van het forum: -> %s';
$lang['Search_users_advanced'] = 'Geavanceerde Gebruikers Zoekactie';
$lang['Search_users_explain'] = 'Deze module maakt het mogelijk om te zoeken naar gebruikers op basis van vele criteria. Lees de beschrijvingen onder de zoekopties zodat u het begrijpt.';
$lang['Search_username_explain'] = 'Hier kan een intensieve zoekactie naar gebuikers worden gehouden. Als u wilt dat slechts delen overeen moet komen, gebruik dan * als een wildcard. De \'Regular Expressions box\' staat u toe om op je eigen \'regex\' patroon te zoeken.';
$lang['Search_email_explain'] = 'Vul hier een extensie in om te zoeken naar e-mailadressen. Deze actie is zee intensief. Als u wilt dat slechts delen overeen moet komen, gebruik dan * als een wildcard. De \'Regular Expressions box\' staat u toe om op je eigen \'regex\' patroon te zoeken.';
$lang['Search_ip_explain'] = 'Zoeken naar gebruikers die berichten maakten terwijl zij een specifiek ip addes (xxx.xxx.xxx.xxx) gebruikten, via een wildcard (xxx.xxx.xxx.*) of via een \'range\' (xxx.xxx.xxx.xxx-yyy.yyy.yyy.yyy). Notatie: de laatste \'quad'\ .255 wordt beschouwd als de \'range\' van alle IPs in de \'quad\'. Als u 10.0.0.255 invoert, is dat hetzelfde als 10.0.0.* (.255 wWordt on die rede niet toegewezen aan een IP adres, omdat dit vantevoren al is toegewezen). Als u 10.0.0.5-10.0.0.255 komt dat hetzelfde neer als "10.0.0.*" . Je kunt beter 10.0.0.5-10.0.0.254 intypen.';
$lang['Search_users_joined'] = 'Gebruikers die lid geworden zijn';
$lang['Before'] = 'Voor';
$lang['After'] = 'Na';
$lang['Search_users_joined_explain'] = 'Zoeken naar gebruikers lid werden voor of na een bepaalde datum. Het datumformaat is: YYYY/MM/DD.';
$lang['Search_users_groups_explain'] = 'Bekijk all leden van de geselecteerd group.';
$lang['Administrators'] = 'Beheerders';
$lang['Banned_users'] = 'Gebande/verbanen Gebruikers';
$lang['Disabled_users'] = 'Ongeldig/Niet actieve Gebruikers';
$lang['Users_disabled_pms'] = 'Gebruikers met uitgeschakelde PMs';
$lang['Search_users_misc_explain'] = 'Beheerders - Alle gebruikers met beheerder bevoegdheden; Moderators - Alle forum moderators; Gebande/verbanen Gebruikers - Alle accounts die geband/verband zijn op dit forum; Ongeldige/Niet actieve gebruikers - Alle gebruikers met ongeldige/niet actieve gebruikers accounts (of door handmatig uit te schakelen of het niet verifiveren van een e-mailadres); Gebruikers met uitgeschakelde PMs - Gebruikers die het Prive Berichten privilages verwijderd (Gemaakt via het gebruikers Management)';
$lang['Postcount'] = 'Berichten aantal';
$lang['Equals'] = 'Gelijken';
$lang['Greater_than'] = 'Groter dan';
$lang['Less_than'] = 'Kleiner dan';
$lang['Search_users_postcount_explain'] = 'Je kan zoeken naar gebruikers op het aantal berichten. Je kan of zoeken op een specifieke waarde, groter dan of kleiner dan een waarde of tussen twee waarde's. Om deze grote zoekactie te verrichten, selecteert u "Gelijken" en dan een begin en een eind waarde invullen, scheiden met een streepje (-), bijvoorbeeld: 10-15';
$lang['Userfield'] = 'Gebruikersveld';
$lang['Search_users_userfield_explain'] = 'Zoeken naar gebruikers met bepaalde profiel waarde's. Wildcards worden ondersteund met een sterretje (*). De \'Regular Expressions box\' staat u toe om op je eigen \'regex\' patroon te zoeken.';
$lang['Search_users_language_explain'] = 'This will display users whom have geselecteerd a specific language in their Profile';
$lang['Search_users_timezone_explain'] = 'Gebruikers die een specifieke tijdzone in hun proiel hebben geselecteerd';
$lang['Search_users_style_explain'] = 'Laat gebruikers zien die een specifieke stijl in hun profiel hebben geselecteerd.';
$lang['Moderators_of'] = 'Moderators van';
$lang['Search_users_moderators_explain'] = 'Zoeken naar gebruikers met Moderater bevoegdheden in een specifiek forum. Moderater bevoegdheden worden herkende of door Gebruikers Bevoegdheden of door een lidmaatschap met de juiste Groep bevoegdheden.';
$lang['Regular_expression'] = 'Regular Expression?';

$lang['Manage'] = 'Beheer';
$lang['Search_users_new'] = '%s opgebrachte %d resultaten. Voer <a href="%s">een zoekactie uit</a>.';
$lang['Banned'] = 'Geband/Verband';
$lang['Not_banned'] = 'Niet Geband/Verband';
$lang['Search_no_results'] = 'Geen gebruikers komen overeen met, de door jouw, geselecteerd criteria. Probeer het alstublieft opnieuw. Als u opzoek bent naar een gebruiker of een e-mailadres kunt u een gedeelte invoeren en er een wildcard toevoegen * (een sterretje).';
$lang['Account_status'] = 'Account Status';
$lang['Sort_options'] = 'Sorteer opties:';

?>