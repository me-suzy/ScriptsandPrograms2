<?php
/*  
 *  Language Translation 
 *  Wizard Site Framework
*/ 


// Search and Login Bar at top
	define("SEARCH", "Suchen" );  //note that the search button is a graphic: /admin/images/search.gif
	define("CONTACT", "Kontakt" );
	define("LOGIN", "Login" );
	define("LOGOUT", "Logout" );
	define("REGISTER", "Registrieren" );
	define("ADMIN", "Admin" );
	define("SITEMAP", "Site Map" );
	
// Footer 
   define("WIZARD", "Wizard PHP Toolbox" );
   define("LOGOUT", "Logout" );
   define("EDIT", "Editieren");
   
// Registration Form / Contact Form
   define("REQUIRED", "Erforderliche Felder" );
   define("USERPASS", "Benutzername und Passwort" );
   define("CUSERNAME", "Benutzername" );
   define("CPASSWORD", "Passwort" );
   define("PASSAGAIN", "Passwort nochmal" );
   define("CONTACTINFO", "Kontakt-Informationen" );
   define("FIRSTNAME", "Vorname" );
   define("LASTNAME", "Nachname" );
   define("EMAIL", "Email");
   define("PHONE", "Telefon" );
   define("ORGANIZATION", "Organisation" );
   define("ADDRESS", "Adresse" );
   define("CITY", "Stadt" );
   define("STATE", "Bundesland" );
   define("COUNTRY", "Land" );
   define("POSTAL", "Postleitzahl" );
   define("NEWSLETTER", "Newsletter");
   define("RECEIVE", "Wollen Sie gelegentliche Werbe-Emails (nur von uns) erhalten?*" );
   define("PLAINTEXT", "Einfacher Text" );
   define("NOTHANKS", "Nein Danke" );
   define("PRIVACY", "Anmerkung zur Privatsphäre: Wir werden ohne Ihre Erlaubnis keine Ihrer hier gemachten Angaben an Dritte weitergeben." );
   define("CHANGEPASS", "Ändern Sie Ihr Passwort" );
   define("CHANGEEMAIL", "Ändern Sie Ihre Emailadresse" );
   define("LOST", "Passwort verloren?" );
   define("ERRORFIRSTNAME", "Fehler: Bitte geben Sie Ihren Vornamen ein." );
   define("ERRORLAST", "Fehler: Bitte geben Sie Ihren Nachnamen ein.");
   define("ERROREMAIL", "Fehler: Bitte geben Sie eine gültige Emailadresse ein.");
   define("ERRORUSER", "Fehler: Bitte geben Sie einen Benutzernamen ein.");
   define("ERRORPASS", "Fehler: Bitte geben Sie ein Passwort ein.");
   define("USERALREADY", "Fehler: Der Benutzername wird bereits verwendet. Bitte wählen Sie einen anderen.");
   define("EMAILBAD", "Fehler: Ihre Emailadresse ist nicht korrekt formatiert.");
   define("FAILED", "Die Registrierung ist fehlgeschlagen. Bitte versuchen Sie es noch einmal oder kontaktieren Sie uns.");
   define("MATCH", "Fehler: Die Passwörter sind nicht idntisch.");
   define("SUCCESS", "Die Registrierung war erfolgreich. Und so aktivieren Sie Ihren Account: klicken Sie auf den Bestätigungsink in der Email, die wir Ihnen gerade geschickt haben.");
   define("SUBMIT", "Abschicken");
   define("RESET","Löschen");
   define("TOKENS", "Fehler: Der Benutzername fehlte beim Erstellen der Cookies.");
   define("NOHASH", "Fehler: Ihr Bestätigungscode (hash) wurde nicht gefunden."); //problem with the secret hash
   define("CONFIRMED", "Ihr Account wurde bestätigt. Bitte klicken Sie auf den Login-Link.");
   define("CONFIRMFAILED", "Es tut uns leid, aber Ihr Bestätigungscode stimmt nicht mit unseren Daten überein.");
   define("MISCONFIRM", "Fehler: Ihr Bestätigungscode oder Ihre Emailadresse fehlt.");
   define("GUEST", "Gast");
   define("SPACES", "Fehler: Bitte verwenden Sie keine Leerschritte im Benutzernamen.");
   define("ALPHA", "Es muss wenigstens einen Buchstaben im Benutzernamen geben.");
   define("ILLEGALCHAR", "Fehler: Unerlaubte Zeichen im Benutzernamen.");
   define("USERSHORT", "Fehler: Der Benutzername muss wenigstens 5 Zeichen haben.");
   define("USERLONG", "Fehler: Der Benutzername darf nicht mehr als 15 Zeichen haben.");
   define("SYSTEMUSE", "Fehler: Der von Ihnen gewählte Benutzername ist für Systemverwaltungszwecke reserviert.");
   define("PASSINVALID", "Fehler: Passwörter müssen wenigstens 5 Zeichen und nicht mehr als 15 Zeichen haben.");
   define("THANKSREGISTER", "Vielen Dank für Ihre Registrierung bei:");
   define("FOLLOWTHIS", "Klicken Sie einfach auf diesen Link, um Ihre Registrierung zu bestätigen:");
   define("HASREGISTERED", "hat sich registriert bei");
   define("CLICK", "Klicken Sie auf diesen Link, um zur Benutzerliste im Admin- Bereich zu gelangen: ");
   define("REGCONFIRM", "Registrierungsbestätigung");
   define("NEWUSER", "Benutzerregistrierung (neu)");

// Contact Form
   define("CF_REQUEST", "Geben Sie hier Ihre Anfrage ein");
   define("CF_COORDINATES", "Unsere Adresse");
   define("CF_REQUEST_INFO", "Informationsanforderung");
   define("CF_RECEIVED", "Sie haben eine Info- Anforderung erhalten von:");
   define("CF_NAME", "Name");
   
   
// Login Form
   define("COOKIES", "Cookies müssen eingeschaltet sein.");   
   define("USERMISSING", "Fehler: Benutzername oder Passwort nicht eingegeben."); 
   define("NOTUSERPASS", "Fehler: Benutzername oder Passwort falsch.");
   define("NOTACTIVATED", "Der Account wurde nicht aktiviert <br />Bitte klicken Sie auf den Bestätigungslink in der Email, die wir Ihnen bei Ihrer Registrierung gesandt haben.");
   
//Change Password or Email
   define("FILLALL", "Fehler: Alle Felder müssen ausgefüllt sein.");
   define("CHANGECONFIRMED", "Ihre Mitgliedsdatensatz wurde aktualisiert.");
   define("TRYAGAIN", "Fehler: Bitte versuchen Sie es nocheinmal.");
   define("NOMATCH", "Fehler: Wir konnten leider weder einen passenden Benutzernamen noch eine entsprechende Emailadresse finden.");
   define("PASSRESET", "Ihr Passwort wurde geändert in:");
   define("CHANGEIT", "Sie finden einen Link zur Anderung Ihres Passwortes im unteren Bereich der Login- Seite.");
   define("FROM", "Von:");
   define("PASSEMAILED", "Ihr neues Passwort wurde Ihnen per Email übersandt.");
   define("CHANGECON", "Achtung: Sie benötigen Ihre neue Emailadresse, um die Änderung bestätigen zu können.");
   define("OLDPASS", "Altes Passwort");
   define("NEWPASS", "Neues Passwort");
   
//authorization
   define("NOTLOGGED", "Sie sind nicht eingeloggt.");   
   define("ADMINONLY", "Der Zugang ist beschränkt auf Administratoren.");
   define("WEBMASTERONLY", "Der Zugang ist beschränkt auf die Webmaster.");

//search
   define("S_NO_MATCH", "No match found.");
   define("S_EMPTY_FORM", "No search term entered.");
   define("S_NO_RESULTS", "The search returned no results.");
   define("S_SEARCH_RESULTS", "Search Results");
   define("S_SEARCH", "Search");
   define("S_RESULTS", "results");
   define("S_PREVIOUS_PAGE", "Previous Page");
   define("S_PREV", "PREV");  //short form for previous page
   define("S_NEXT_PAGE", "Next Page");
   define("S_NEXT", "NEXT");  //short form for next page
   
?>