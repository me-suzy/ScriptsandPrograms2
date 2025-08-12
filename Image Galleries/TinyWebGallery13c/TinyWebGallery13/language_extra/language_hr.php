<?php 
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
// language file - version 1.3 - if you translate a langauge file where already some parts are translated: Please don't remove the former translator completely - insert him/her behind the lang_translator as a comment
$lang_translator = " - Translator: <a href=\"http://www.znaor.com\" target=\"_blank\"><span class=\"twglink\">Krki</span></a>";
$lang_optionen = "Opcije";
$lang_menu_titel = "Naslov";
$lang_kommentar = "Komentar";

$lang_titel = "";
$lang_titel_no = "Trenutno nema galerija.";
$lang_select_gallery = "Odaberite galeriju";
$lang_help = "Pomoc";
$lang_new_window = "Novi prozor";
$lang_login = "Login";
$lang_logout = "Logout";
$lang_visitor = "Posjeta:";
$lang_total = "Ukupno:";
$lang_today = "Danas:";
$lang_galleries = "Galerije";
$lang_picture = "Slika";
$lang_pictures = "Slika";
$lang_of = "od";

$lang_overview = "Prethodni nivo";
$lang_forward = "Sljedeca";
$lang_back = "Zadnja";
$lang_rotate_left = "Rotiraj lijevo";
$lang_rotate_right = "Rotiraj desno";
$lang_start_slideshow = "Pokreni slideshow";
$lang_stop_slideshow = "Zaustavi slideshow";
$lang_thumb_forward = "&gt;"; // = '>'
$lang_thumb_back="&lt;"; // = '<'
$lang_views = "x";

$lang_login_php_enter = "Za uredjivanje naslova i brisanje komentara je potrebna lozinka.";
$lang_login_php_enter_again = "Kriva lozinka<br />Pokusajte ponovo:";

$lang_kommenar_php_both_fields = "Ispuniti oba polja.<br />";
$lang_kommenar_php_enter_name = "Unesite Vase ime<br />(max. 30 slova):";
$lang_kommenar_php_enter_comment = "Unesite komentar<br />(max. 150 slova):";
$lang_kommenar_php_name = "Ime:";
$lang_kommenar_php_comment = "Komentar (max. 150 znakova):";
$lang_kommenar_php_speichern = "Snimi";

$lang_privatelogin_php_wrong_password = "Kriva lozinka<br />Pokuajte ponovo:";
$lang_privatelogin_php_password = "Unesite lozinku za ovu galeriju<br />'%s':";
$lang_privatelogin_php_login = "Login";

$lang_titel_php_titel = "Unesite naslov<br />(max. 150 znakova):";
$lang_titel_php_save = "Snimi";

$lang_slideshowid_php_loading = "Ucitavam slideshow ...";

$lang_opionen_php_yes = "Da";
$lang_opionen_php_no = "Ne";
$lang_opionen_php_new_window = "Otvori galeriju u novom prozoru";
$lang_opionen_php_opt_slideshow = "Optimizirani slideshow sa tranzicijama (za IE)";
$lang_opionen_php_slideshowintervall = "Slideshow interval (sek.)";
$lang_opionen_php_ok = "OK";
$lang_opionen_php_top_nav = "Pokazi samo gornju navigaciju";

$lang_back_topx = "natrag&nbsp;na&nbsp;Top&nbsp;%s";
$lang_topx = "Top&nbsp;%s";
$lang_main_topx = "Natrag u galerije";
// new in 1.1
$lang_loading = "ucitavam...";
$lang_dhtml_navigation = "Koristi DHTML navigaciju";
$lang_optionen_slideshow = "Tip SlideShow-a";
$lang_optionen_slideshow_fullscreen = "Maksimizirano";
$lang_optionen_slideshow_optimized = "Pretapanje";
$lang_optionen_slideshow_normal = "Normalno";
$lang_lowbandwidth = "TinyWebGallery je optimiziran za spore veze.";
$lang_highbandwidth = "TinyWebGallery je optimiziran za brze veze.";
// new in 1.2
$lang_email_menu_user = "Email obavijest";
$lang_email_menu_admin = "Posalji email";
// Sorrymessage for failed subscription
$lang_email_sorrysignmessage = "Izgleda da je ovaj email '%s' je vec registriran.";
// Sorrymessage for blank email
$lang_email_sorryblankmailmessage = "Unesi email adresu.";
// Sorrymessage for invalid emails
$lang_email_sorryoddmailmessage = "Izgleda da ova '%s' adresa koju ste unijeli nije valjana.";
// Sorrymessage if someone entered your own mail
$lang_email_sorryownmailmessage = "Oprosti, ali ne zelim dobivati vlastiti newsletter!";
// Title of the newsletter, will be displayed in the FROM field of the mailclient
$lang_email_subscribemail_subject = "Galerija je osvjezena sa novim slikama.";
// Subscribemessage, will be shown when someone subscribes.
$lang_email_subscribemessage = "Hvala na registraciji. Email potvrde je poslan na Vasu adresu.";
// Subscribemail, will be sent when someone subscribes.
$lang_email_subscribemail = "Uspjesno ste dodani na nasu newsletter listu.";
// Unsubscribemessage for deletion, will be followed by the email!
$lang_email_unsubscribemessage = "Email '%s' je obrisan.";
// Unsubscribemessage for failed deletion, will be followed by the email!
$lang_email_failedunsubscriptionmessage = "Email '%s' nije nadjen.";
// the welcometext
$lang_email_welcomemessage = "Ovdje se mozete registrirati (ili ponistiti registraciju) <br />ukoliko zelite emailom dobivati obavijesti administratora o novim slikama u galeriji";
// email could not be sent.
$lang_email_error_send_mail = "Email potvrde ne moze biti poslat.<br/>Email server najvjerojatnije nije dobro konfiguriran. Molimo da kontaktirate administratora galerije.";

$lang_email_add = "Dodaj";
$lang_email_remove = "Makni";
$lang_email_send = "Posalji";

$lang_email_admin_welcomemessage_send = 'Ovdje mozete sve registrirane korisnike (%s) obavijestiti o promjenama u galeriji.';
$lang_email_admin_sorryoddsendermailmessage = "Zao nam je, email '%s' posiljatelja koji ste unijeli izgleda nije valjan.";
$lang_email_admin_sendermail = "Adresa posiljatelja (tvoj email)";
$lang_email_admin_subject = "Subjekt";
$lang_email_admin_message = "Poruka";
$lang_email_admin_sendbutton = "Posalji";
$lang_email_admin_sent = "Poslata je sljedeca poruka:";
$lang_email_admin_from = "Od";
$lang_email_admin_notloggedin = "Niste logirani. Logirati se mozete na glavnoj stranici.";

$lang_visitor_30 = "zadnjih 30 dana";

$lang_login_php_enter = "Za uporabu naprednih mogucnosti galerije potrebno je unijeti lozinku.";
$lang_opionen_php_zoom = "Maksimizirani pregled";
$lang_administration = "Administracija";
$lang_opionen_php_zoom_message = "Upozorenje: \'Maksimizirani pregled\' je sporiji od normalnog zato jer se slika generira dinamicki u realnom vremenu. Buduci da su uvecane i slike potrebno je vise vremena za transfer.\\n Prepoucujemo da ovu opciju koristite samo ako imate brzu internet vezu.";
$lang_first = "Prva";
$lang_last = "Zadnja";
$lang_no_session = "Session is not available. Login to private galleries is not enabled.";
$lang_not_loggedin = "You are not logged in.<br/>Please go to the main page and unlock this gallery.";
$lang_no_topx_images = "No images could be found for this view. <br / >Protected galleries are only included if you unlock them first.";

$lang_tips_overview = array('Tip: Please click on the help link, if you want to know what you can do with the gallery.');
$lang_tips_thumb = array('Tip: On the small navigation in the left upper corner you can directly jump to all the parent levels.','Tip: The Top 13 on this page does only show the most viewed images of THIS directory.');
$lang_tips_image = array('Tip: You can use the left/right arrow key to navigate to the next/last image.','Tip: If you login (right upper corner) you can enter captions, delete comments and rotate images permanently.','Tip: You can use smilies like :) or ;) in titles and comments.');

// new 1.3
$lang_dl_as_zip1 = "You can download all images of this directory in a single archive.<br />Do you want to download the whole directory or continue to the actual image?";
$lang_dl_as_zip2 = "Actual image";
$lang_dl_as_zip3 = "All";
$lang_dl_as_zip4 = "Do not ask again in this session."; // we store this is the session

$lang_download_counter = "Downloads";
$lang_download_counter_short = "dl";
// not used in 1.3 yet!
$lang_email_subscribemail_2_subject = "Please confirm your registration at the TinyWebGallery";
$lang_email_subscribemail_2 = "Dear TinyWebGallery user:\n\nYou have registered to the notification list of TinyWebGallery. To complete the registration please klick on the link below:\n%s\n\nYour TinyWebGallery";
// --
$lang_rating = "Vote";
$lang_rating1 = "1";
$lang_rating2 = "2";
$lang_rating3 = "3";
$lang_rating4 = "4";
$lang_rating5 = "5";
$lang_rating_button = "Rate";
$lang_rating_text = "Please rate the actual image:";
$lang_rating_new="New vote: ";
$lang_rating_vote="Votes";

$lang_rating_security="Please enter the 4 characters<br />of the security code [A-F,0-9]:";
$lang_rating_help="Please click on the image,<br />if you cannot see the code.";
$lang_rating_message1="You have already voted.";
$lang_rating_message2="please close this window.";
$lang_rating_message3="Thanks for voting.";
$lang_rating_message4="You have entered a wrong code.";
$lang_rating_send="Send";

$lang_fileinfo="Info";
$lang_fileinfo_name = "File name";
$lang_fileinfo_date = "Date";
$lang_fileinfo_size = "Size";
$lang_fileinfo_resolution = "Resolution";
$lang_fileinfo_views = "Views";
$lang_fileinfo_dl = "Downloads";
$lang_fileinfo_rating = "Rating";
$lang_fileinfo_not_available = " - (remote)";
$lang_exif_not_available = " - ";
// please check the constants in the file inc/exifReader.inc.php for exif entries you can try - not all are available on all cameras! sometimes the constants don't work - try to google for the real exif field name!
$lang_exif_info = array("Camera Model" => "model", "Exif Date" => "DateTime", "Focal Length" => 
"focalLength", "f/Stop" => "fnumber", "Shutter Speed" => "exposureTime", "ISO" => "isoEquiv");

$lang_comments = "Comments";
$lang_last_comments="Newest comments";
$lang_show_kommentar="Show comments";
$lang_add_kommentar="Add comment";
$lang_close_fullscreen="Back to the normal view";

$lang_search_results="Search resuls";
$lang_search_back="Back to the search results.";
$lang_search="Search";
$lang_search_text="Please enter the search text:";
$lang_search_button="Search";
$lang_search_where="Search in";
$lang_search_max="Hits shown per page";
$lang_search_all="All";
$lang_search_hits="Found %s hits.";

// height of the i_frames - please check if the settings match for your language!!
$lang_height_comment = 150;
$lang_height_caption = 90;
$lang_height_login = 80;
$lang_height_private = 80;
$lang_height_options = 205;
if (!$show_new_window) { $lang_height_options -= 30; }
if (!$show_slideshow)  { $lang_height_options -= 60; }
if (!$enable_maximized_view)  { $lang_height_options -= 30; }
$lang_height_email_user = 180;
$lang_height_email_admin = 280;
$lang_height_info = 150;
if (!$show_download_counter || !$enable_download_counter) { $lang_height_info -= 17; }
if (!$show_count_views) { $lang_height_info -= 17; }
if (!$show_image_rating) { $lang_height_info -= 17; }
if ($show_exif_info) { $lang_height_info += (count($lang_exif_info) * 17);}
$lang_height_rating = 150;
$lang_height_dl_manager=150;
$lang_height_search=175;
$lang_xpos_lang_dropdown="200"; // this is only needed if you include twg and ie does not position the language dropdown correct because you include twg in several divs - in a fixed layout you can adjust this here - if you don't have a fix layout don't use the dropdown - use the normal setting! 
?>