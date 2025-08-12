<?php
error_reporting(7);

/*   (Develooping flash Chat version 1.2)  */
/*   (NL vertaling van Paul Oostenveld, e-mail: info@dutchveiling.nl)_*/
/*_  (Een Demo vind u op http://www.dutchveiling.nl/chat.php)_*/

/*___________________________________________________*/
/*___________________________________________________*/
/*                    SETTINGS                       */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Chat settings    */
/*   _________________*/

$url="http://www.uwdomein.nl/chat/"; //absolute url to scripts directory

$text_order = "down"; //use "down" o "up" to show messages downwars or upwards

$review_text_order = "down"; //the same with review messages windos

$delete_empty_room = "no"; //use "yes" to delete messagees when the room is empty

$show_without_time = "no"; //"no" shows always hour, "yes" shows hour only whe the user enters or leaves the room

$password_system = "ip"; //"ip" o "password" to use ip or password to identify users

/*   NOTE:   the banning system only works with "ip" "ip" */
/*           Use "password" only when users come from the same ip*/


/*   Administration variables  */
/*   _______________________   */

$admin_name = "user"; //user name for admin (max. 12 characters)

$admin_password = "wachtwoord"; // password for admin (max. 12 characters)


/*   Chat numeric variables    */
/*   _______________________   */

$correct_time = 0;//difference in seconds with time in server

$chat_lenght = 15;//number of messages displayed in chat room

$review_lenght = 500;//number of messages displayed in review messages

$total_lenght = 1000;//number of messages stored in chat file

$minutes_to_delete = 15; //minutes to delete inactive users


/*   Words to filter   */
/*   _________________  */

$words_to_filter = array("fucking", "fuck", "shit", "cunt", "piss", "neuken");//list of bad words to replace (add more if you want)

$replace_by = "*@#!";//expression to replace bad words


/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    TRANSLATION                    */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Expressions to translate in intro page  */
/*   ______________________________________  */

$intro_alert="Hallo"; //

$alert_message_1="-De naam moet uit min. 4 letters bestaan"; //

$alert_message_2="-Wachtwoord moet uit min. 4 tekens bestaan"; //

$alert_message_3="-Gebruik geen tekens of nummers in de naam"; //

$alert_message_4="-Sorry, we hebben je IP nodig om toegang te krijgen"; //

$alert_message_5="-Gebruik geen speciale tekens in je wachtwoord"; //

$person_word="-Chatter"; //

$plural_particle="s"; //
 
$now_in_the_chat= " Aanwezig in de chat";//

$require_sentence = "Mimimaal flash 4 pluggin vereist voor de chat"; //

$name_word = "Naam"; //

$password_word = "Wachtwoord"; //

$enter_button = "Verder" ; //

$enter_sentence_1 = "Typ uw naam om de chat binnen te gaan ";//

$enter_sentence_2 = "en wachtwoord";//

$enter_sentence_3 = " en klik op de button ";//

$name_taken= "use other name";


/*   Expressions to translate in chat room   */
/*   ______________________________________  */

$private_message_expression = "\(voor (.*)\)"; //Regular expression for beginning of private message

$before_name="(voor "; // if you change the regular expression write here the text between "\ and (.*)

$after_name=")"; // if you change the regular expression write here the text after (.*) 

$not_here_string = "-Deze persoon is niet in de Chat-"; //receiver of private message is not in the room

$bye_string = "Tot ziens en tot de volgende keer,";//message showed to dimissed user

$enter_string = "(is de chat binnen gekomen)";//message showed when a new user enters.

$bye_user = "(heeft de chat verlaten)";//message showed when a user exits.

$kicked_user = "---U bent verwijderd uit de Chat---";//message showed in the chat room to kicked user.

$bye_kicked_user = "Hou je de volgende keer aan de regels";//bye for kicked users

$bye_banned_user = "We hebben je beblokkeerd voor deze Chat ruimte.";//bye for banned users

$banned_user = "Sorry, je hebt geen toegang tot de Chat";//message for banned user trying to enter


/*   Expressions to translate in chat interface   */
/*   ___________________________________________  */

$intro_text="Lees de volgende notities voordat u verder gaat.
";

$intro_text .="Wanneer er al iemand in de chatruimte is met de door u gekozen naam, dan word er een nummer aan de naam toegevoegd ";

$intro_text .="Als u de chatruimte binnengaat met de naam Paul, en de naam Paul bestaat al, dan word uw naam b.v. Paul1.
";

$intro_text .="U kunt de on-line chatter zien, en prive berichten naar hen sturen, geluid aan en uit zetten (knop met de speaker) en de history van alle berichten bekijken (knop met zwarte pijl).
";

$intro_text .="Dat was alles, veel plezier in de Chat";//

$conn="
U gaat nu naar de Chat. Een ogenblik gedult a.u.b..."; //

$you_are="Je naam is"; //

$connected_users= "Verbonden users";//

$private_message_to= "Privè bericht voor";//

$private_message_text="Privè berichten zijn alleen zichtbaar voor zender/ontvanger.
";//
 
$private_message_text.="Voer de exacte naam in van de ontvanger.
";// 

$private_message_text.="U kunt de geselecteerde tekst copieeren en plakken in een ander veld met de rechter muisknop in Windows or ctrl-klik in Mac.";//


/*   Expressions to translate in review messages page  */
/*   ________________________________________________  */

$review_title ="Chat. Laatste berichten";// title for review messages page


/*   Expressions to translate in administration pages   */
/*   ________________________________________________  */

$link_to_admin ="Beheer";// text for link to administration pages

$intro_admin_title = "Beheer van de Chat ruimte";// title for administration intro page

$intro_admin_name = "Naam";//Text for name field in administration intro page

$intro_admin_password = "Wachtwoord";//Text for name field in administration intro page

$intro_admin_button= "Ok";//Text for button in administration intro page

$no_users = "Er is niemand in de Chat aanwezig";//no users in the room

$text_for_kick_button = "Kick";//text fof kick button

$text_for_bann_button = "Bann";//text for button for bannig ips

$no_ips = "Er zijn geen geblokkeerde Ip's in de Chat";//no banned IPs in the room

$text_for_pardon_button = "Pardon";//text for button to pardon ips

$ip_link = "Beheer geblokkeerde IP's";//text for link to banned IPs

$no_ip_link = "De chat gebruikt geen IP indentificatie, Ip kan niet gebanned worden";//text if you use password instead de IP

$users_link = "Beheer van de chatters";//text for link to connected users

?>
