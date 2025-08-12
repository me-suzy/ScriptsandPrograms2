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

$url="http://www.domain.hu/chat/"; //absolute url to scripts directory

$text_order = "down"; //use "down" o "up" to show messages downwars or upwards

$review_text_order = "down"; //the same with review messages windos

$delete_empty_room = "no"; //use "yes" to delete messagees when the room is empty

$show_without_time = "no"; //"no" shows always hour, "yes" shows hour only whe the user enters or leaves the room

$password_system = "password"; //"ip" o "password" to use ip or password to identify users

/*   NOTE:   the banning system only works with "ip" "ip" */
/*           Use "password" only when users come from the same ip*/


/*   Administration variables  */
/*   _______________________   */

$admin_name = "admin"; //user name for admin (max. 12 characters)

$admin_password = "admin"; // password for admin (max. 12 characters)


/*   Chat numeric variables    */
/*   _______________________   */

$correct_time = 0;//difference in seconds with time in server

$chat_lenght = 15;//number of messages displayed in chat room

$review_lenght = 500;//number of messages displayed in review messages

$total_lenght = 1000;//number of messages stored in chat file

$minutes_to_delete = 15; //minutes to delete inactive users


/*   Words to filter   */
/*   _________________  */

$words_to_filter = array("picsa", "fasz", "kurva", "", "");//list of bad words to replace (add more if you want)

$replace_by = "*****";//expression to replace bad words


/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    TRANSLATION                    */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Expressions to translate in intro page  */
/*   ______________________________________  */

$intro_alert="FONTOS"; //

$alert_message_1="-A névnek legalább 4 karakter hosszúnak kell lennie"; //

$alert_message_2="-A jelszónak legalább 4 karakternek kell lennie"; //

$alert_message_3="-Ne használj speciális karaktereket és számokat a névben"; //

$alert_message_4="-Sajnálom, add meg az IP címed az eléréshez"; //

$alert_message_5="-Ne használj speciális karaktereket és számokat a jelszóban"; //

$person_word="személy"; //

$plural_particle=""; //
 
$now_in_the_chat= " most a szobában";//

$require_sentence = "A chat-hez flash player-re van szükség"; //

$name_word = "Név"; //

$password_word = "Jelszó"; //

$enter_button = "Belépés" ; //

$enter_sentence_1 = "A belépéshez add meg a neved";//

$enter_sentence_2 = " és jelszavad,";//

$enter_sentence_3 = " és klikkelj a BELÉPÉS gombra";//

$name_taken= "use other name";


/*   Expressions to translate in chat room   */
/*   ______________________________________  */

$private_message_expression = "\(Címzett (.*)\)"; //Regular expression for beginning of private message

$before_name="(xxx "; // if you change the regular expression write here the text between "\ and (.*)

$after_name=")"; // if you change the regular expression write here the text after (.*) 

$not_here_string = "-A címzett nincs a szobában-"; //receiver of private message is not in the room

$bye_string = "Na PÁ, remélem még találkozunk.";//message showed to dimissed user

$enter_string = "(belépett a szobába)";//message showed when a new user enters.

$bye_user = "(elhagyta a szobát)";//message showed when a user exits.

$kicked_user = "---Ki lettél rúgva a szobából---";//message showed in the chat room to kicked user.

$bye_kicked_user = "Próbálj meg udvariasabb lenni";//bye for kicked users

$bye_banned_user = "Pá, a belépésed le lett tiltva";//bye for banned users

$banned_user = "Sajnálom, nem léphetsz be a szobába";//message for banned user trying to enter


/*   Expressions to translate in chat interface   */
/*   ___________________________________________  */

$intro_text="Mielõtt belépsz olvasd el a szabályzatot.
";

$intro_text .="Ha valaki már benn van a szobában olyan névvel amit választottál, a neved mellé kapsz egy számot. ";

$intro_text .="Ha például -petike- névvel lépsz be és már van benn egy ilyen nevû felhasználó, akkor a neved -petike1- lesz.
";

$intro_text .="Láthatod a kapcsolodó felhasználókat, és küldhetsz nekik privát üzenetet a kis, ki és bekaocsolhatod a hangot -hangszóró- ikon, és vissza is léphetsz -vissza nyil-.
";

$intro_text .="Ennyi.";//

$conn="
Kapcsolódás a szobához. Kérlek várj..."; //

$you_are="te vagy"; //

$connected_users= "csatlakozott felhasználók";//

$private_message_to= "privát üzenet";//

$private_message_text="A privát üzenetet csak a feladó és a címzett látja.
";//
 
$private_message_text.="Pontosan írd be a címzett nevét, másképp nem látja az üzenetet. 
";// 

$private_message_text.="A windowsos másolás, beillesztés itt is mûködik (Ctrl-C másolás, Ctrl-V beillesztés).";//


/*   Expressions to translate in review messages page  */
/*   ________________________________________________  */

$review_title ="Chat. Utolsó üzenet";// title for review messages page


/*   Expressions to translate in administration pages   */
/*   ________________________________________________  */

$link_to_admin ="Adminisztráció (jelszavas)";// text for link to administration pages

$intro_admin_title = "A chat admin-ja";// title for administration intro page

$intro_admin_name = "Név";//Text for name field in administration intro page

$intro_admin_password = "Jelszó";//Text for name field in administration intro page

$intro_admin_button= "Ok";//Text for button in administration intro page

$no_users = "Nincsenek felhasználók a szobában";//no users in the room

$text_for_kick_button = "Kirúgás";//text fof kick button

$text_for_bann_button = "Kitiltás";//text for button for bannig ips

$no_ips = "Nincsen kitiltott IP a szobában";//no banned IPs in the room

$text_for_pardon_button = "Pardon";//text for button to pardon ips

$ip_link = "Kitiltott IP-k adminisztrációja";//text for link to banned IPs

$no_ip_link = "A chat nem használja az IP-t azonosításra, ezért õk nem kitilthatók";//text if you use password instead de IP

$users_link = "Felhasználók adminisztrációja";//text for link to connected users

?>