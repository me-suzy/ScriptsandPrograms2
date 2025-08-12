<?php
error_reporting(7);

/*   (Develooping flash Chat version 1.2)  */
/*   ____________________________________  */

/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    SETTINGS                       */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Chat settings    */
/*   _________________*/

$url="http://www.yourdomain.com/chat/"; //absolute url to scripts directory

$text_order = "up"; //use "down" o "up" to show messages downwars or upwards

$review_text_order = "up"; //the same with review messages windos

$delete_empty_room = "no"; //use "yes" to delete messagees when the room is empty

$show_without_time = "no"; //"no" shows always hour, "yes" shows hour only whe the user enters or leaves the room

$password_system = "ip"; //"ip" o "password" to use ip or password to identify users

/*   NOTE:   the banning system only works with "ip" "ip" */
/*           Use "password" only when users come from the same ip*/


/*   Administration variables  */
/*   _______________________   */

$admin_name = "admin"; //user name for admin (max. 12 characters)

$admin_password = "admin"; // password for admin (max. 12 characters)


/*   Chat numeric variables    */
/*   _______________________   */

$correct_time = 2850;//difference in seconds with time in server

$chat_lenght = 5;//number of messages displayed in chat room

$review_lenght = 500;//number of messages displayed in review messages

$total_lenght = 1000;//number of messages stored in chat file

$minutes_to_delete = 15; //minutes to delete inactive users


/*   Words to filter   */
/*   _________________  */

$words_to_filter = array("fucking", "fuck", "shit", "cunt", "piss");//list of bad words to replace (add more if you want)

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

$intro_alert="Please:"; //

$alert_message_1="-Ihr Name muss mindestens aus 4 Zeichen bestehen"; //

$alert_message_2="-Das Passwort muss mindestens aus 4 bestehen"; //

$alert_message_3="-Sonderzeichen sind nicht erlaubt"; //

$alert_message_4="-Sorry, aber  Sie benötigen Ihre aktuelle IP für den Zugang"; //

$alert_message_5="-Sonderzeichen sind nicht erlaubt"; //

$person_word=" Person"; //

$plural_particle="en"; //

$now_in_the_chat= " Anwesend";//

$require_sentence = "Dieser Chat benötigt Flash 4"; //

$name_word = "Name "; //

$password_word = "Passwort"; //

$enter_button = " Enter" ; //

$enter_sentence_1 = "Geben Sie Ihren Namen ein, um den Chat zu betreten ";//

$enter_sentence_2 = "und Passwort";//

$enter_sentence_3 = " und klicken Sie auf den Button";//

$name_taken= "use other name";


/*   Expressions to translate in chat room   */
/*   ______________________________________  */

$private_message_expression = "\\(for (.*)\\)"; //Regular expression for beginning of private message

$before_name="(for "; // if you change the regular expression write here the text between "\\ and (.*)

$after_name=")"; // if you change the regular expression write here the text after (.*) 

$not_here_string = "-Keine private Mitteilung möglich, User ist nicht anwesend -"; //receiver of private message is not in the room

$bye_string = "Bye. Bis zum nächsten mal"; //message showed to dimissed user

$enter_string = "(hat den Raum betreten)";//message showed when a new user enters.

$bye_user = "(hat den Raum verlassen)";//message showed when a user exits.

$kicked_user = "---Sie wurden aus dem Raum geworfen---";//message showed in the chat room to kicked user.

$bye_kicked_user = "Das nächste mal, verhalten Sie sich bitte respektvoll!";//bye for kicked users

$bye_banned_user = "Adios, Sie haben keinen Zutritt zum Chat";//bye for banned users

$banned_user = "Sorry, aber Sie dürfen den Chat nicht mehr betreten";//message for banned user trying to enter


/*   Expressions to translate in chat interface   */
/*   ___________________________________________  */

$intro_text="Bitte vor dem Betreten des WTV - Chattertreffs lesen!
";

$intro_text .="Sollte sich jemand mit gleichem Pseudonym im Chat befinden, wird automatisch eine Ziffer hinzugefügt. ";

$intro_text .="Betreten Sie den Raum z.B. mit dem Pseudonym 'Chatter', es ist aber bereits jemand mit gleichem Namen im Chat, werden Sie automatisch zu 'Chatter1'.
";

$intro_text .="Anwesende Chatter werden angezeigt. Sie können anwesenden Chattern eine private Mitteilung schicken, Sound aktivieren und deaktivieren ('Lautsprecher' icon) und  sich die letzten Gespräche anzeigen lassen ('Pfeil zurück' icon).
";

$intro_text .="Das ist alles - Viel Vergnügen.";//

$conn="
Verbindung zum WTV - Chattertreff - Moment bitte..."; //

$you_are="Willkommen"; //

$connected_users= " Anwesende";//

$private_message_to= "Private Mitteilung";//

$private_message_text="Private Mitteilungen können nur vom Absender und Empfänger gelesen werden!
";//

$private_message_text.="Bitte geben Sie zum verschicken einer privaten Mitteilung den korrekten Namen des Empfängers ein.
";// 

$private_message_text.="Sie können auch einen Text aus einer anderen Anwendung kopieren und hier einfügen!";//


/*   Expressions to translate in review messages page  */
/*   ________________________________________________  */

$review_title ="Letzte Gespräche anzeigen";// title for review messages page


/*   Expressions to translate in administration pages   */
/*   ________________________________________________  */

$link_to_admin ="Administration";// text for link to administration pages

$intro_admin_title = "Develooping Chat Admin";// title for administration intro page

$intro_admin_name = "Name";//Text for name field in administration intro page

$intro_admin_password = "Passwort";//Text for name field in administration intro page

$intro_admin_button= "Ok";//Text for button in administration intro page

$no_users = "Keiner da!";//no users in the room

$text_for_kick_button = "Kick";//text fof kick button

$text_for_bann_button = "Bann";//text for button for bannig ips

$no_ips = "There aren't banned IPs in the room";//no banned IPs in the room

$text_for_pardon_button = "Pardon";//text for button to pardon ips

$ip_link = "Administration for banned IPs";//text for link to banned IPs

$no_ip_link = "The chat is not using the IP to identify users, so they cannot be banned";//text if you use password instead de IP

$users_link = "Administration for user list";//text for link to connected users

?>