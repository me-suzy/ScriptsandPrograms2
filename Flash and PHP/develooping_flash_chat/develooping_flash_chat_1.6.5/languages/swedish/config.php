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

$url="http://www.yoursite.com/chat/chat/"; //absolute url to scripts directory

$text_order = "down"; //use "down" o "up" to show messages downwars or upwards

$review_text_order = "down"; //the same with review messages windos

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

$chat_lenght = 15;//number of messages displayed in chat room

$review_lenght = 500;//number of messages displayed in review messages

$total_lenght = 1000;//number of messages stored in chat file

$minutes_to_delete = 15; //minutes to delete inactive users


/*   Words to filter   */
/*   _________________  */

$words_to_filter = array("knulla", "knull", "kuk", "fitta", "hora");//list of bad words to replace (add more if you want)

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

$intro_alert="Observera:"; //

$alert_message_1="-Namnet måste vara minst fyra bokstäver långt"; //

$alert_message_2="-Lösenordet måste vara minst fyra bokstäver långt"; //

$alert_message_3="-Använd inte specialtecken eller siffror i namnet"; //

$alert_message_4="-Beklagar, vi måste ha ditt IP-nummer för att kunna ge dig tillträde"; //

$alert_message_5="-Använd inte specialtecken i lösenordet"; //

$person_word="person"; //

$plural_particle="er"; //
 
$now_in_the_chat= " är i chaten nu";//

$require_sentence = "Chaten kräver Flash 4"; //

$name_word = "Namn"; //

$password_word = "Lösenord"; //

$enter_button = "Stig in" ; //

$enter_sentence_1 = "För att gå med i chaten, ange ditt namn ";//

$enter_sentence_2 = "och lösenord";//

$enter_sentence_3 = " och klicka på knappen";//

$name_taken= "använd ett annat namn";


/*   Expressions to translate in chat room   */
/*   ______________________________________  */

$private_message_expression = "\(till (.*)\)"; //Regular expression for beginning of private message

$before_name="(till "; // if you change the regular expression write here the text between "\ and (.*)

$after_name=")"; // if you change the regular expression write here the text after (.*) 

$not_here_string = "-Mottagaren befinner sig inte i chatten-"; //receiver of private message is not in the room

$bye_string = "Hej då! Hoppas vi ses snart,";//message showed to dimissed user

$enter_string = "(steg just in i chaten)";//message showed when a new user enters.

$bye_user = "(lämnade just chaten)";//message showed when a user exits.

$kicked_user = "---Du har blivit sparkad från chaten---";//message showed in the chat room to kicked user.

$bye_kicked_user = "Nästa gång, försök vara lite artigare";//bye for kicked users

$bye_banned_user = "Hej då! Du har blivit portad från chaten";//bye for banned users

$banned_user = "Beklagar, du kan inte stiga in i chaten";//message for banned user trying to enter


/*   Expressions to translate in chat interface   */
/*   ___________________________________________  */

$intro_text="Innan du stiger in i chaten, ta del av instruktionerna.
";

$intro_text .="Om någon annan i chaten har samma namn som du valt, kommer en siffra läggas till i ditt namn. ";

$intro_text .="Om du stiger in i chaten med namnet Kalle och där redan finns en Kalle, kommer ditt namn att bli Kalle1.
";

$intro_text .="Du kan se anslutna chatare och skicka privata meddelanden till dem, aktivera och inaktivera ljudet ('högtalare'-ikon) och se tidigare inlägg i chaten ('bakåt pil'-ikon).
";

$intro_text .="Det var allt! Mycket nöje i chaten!";//

$conn="
Ansluter till chaten. Vänta lite, tack..."; //

$you_are="du är"; //

$connected_users= "Anslutna användare";//

$private_message_to= "Privat meddelande till";//

$private_message_text="De privata meddelandena kan bara ses av mottagaren och avsändaren.
";//
 
$private_message_text.="Skriv det exakta namnet på mottagaren, annars kommer denne ej att kunna se meddelandet.
";// 

$private_message_text.="Kom ihåg att du kan kopiera markerad tex och klistra in den i ett annan fält genom att höger-klick i Windows eller ctrl-klick i Mac.";//


/*   Expressions to translate in review messages page  */
/*   ________________________________________________  */

$review_title ="Chat - Senaste Meddelandena";// title for review messages page


/*   Expressions to translate in administration pages   */
/*   ________________________________________________  */

$link_to_admin ="Administration";// text for link to administration pages

$intro_admin_title = "Chat - Admin";// title for administration intro page

$intro_admin_name = "Namn";//Text for name field in administration intro page

$intro_admin_password = "Lösenord";//Text for name field in administration intro page

$intro_admin_button= "Ok";//Text for button in administration intro page

$no_users = "Där finns inga i chaten";//no users in the room

$text_for_kick_button = "Sparka";//text fof kick button

$text_for_bann_button = "Porta";//text for button for bannig ips

$no_ips = "Där finns inga portade IP's i chaten";//no banned IPs in the room

$text_for_pardon_button = "Förlåtelse";//text for button to pardon ips

$ip_link = "Administration för portade IP's";//text for link to banned IPs

$no_ip_link = "Chaten använder inte IP's till att identifiera användare, så den kan inte bli portade";//text if you use password instead de IP

$users_link = "Administration för anslutna användare";//text for link to connected users

?>