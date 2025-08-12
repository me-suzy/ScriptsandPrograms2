<?php
error_reporting(7);

/*   (Develooping flash Chat version 1.2)  */
/*   ____________________________________  */

/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    AJUSTES                        */
/*	traduzione in italiano a cura di Enrico Bono	 */
/*	enrico@lifejackets.it							 */
/*	http://www.lifejackets.it						 */
/*___________________________________________________*/
/*___________________________________________________*/


/*   Ajustes del chat    */
/*   ____________________*/

$url="http://lnx.battaglini-marrini.com/chat_php/"; //url absoluta del directorio donde se encuentran los scripts

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

$chat_lenght = 30;//number of messages displayed in chat room

$review_lenght = 500;//number of messages displayed in review messages

$total_lenght = 1000;//number of messages stored in chat file

$minutes_to_delete = 15; //minutes to delete inactive users

/*   Words to filter   */
/*   _________________  */

$words_to_filter = array("cazzo", "fottere", "merda", "stronzo", "fica");//list of bad words to replace (add more if you want)

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

$intro_alert="Attenzione:"; //
$alert_message_1="- Il Nome deve essere lunga almeno 4 caretteri"; //
$alert_message_2="- La Password deve essere lunga almeno 4 caratteri"; //
$alert_message_3="- Non sono ammessi caratteri speciali o numeri nel Nome"; //
$alert_message_4="- Mi dispiace, ma è necessario poter ottenere il tuo indirizzo IP per permettere l'accesso"; //
$alert_message_5="- Non sono ammessi caratteri speciali nella Password"; //
$person_word=" utenti"; //
$plural_particle=""; //
$now_in_the_chat= " presenti nella chat room";//
$require_sentence = "Per questa chat è necessario Flash 4"; //
$name_word = "Nome"; //
$password_word = "Password"; //
$enter_button = "  Vai!  " ; //
$enter_sentence_1 = "Per entrare nella chat room, inserisci il tuo Nome ";//

$enter_sentence_2 = "e una Password";//

$enter_sentence_3 = " e poi clicca su 'Vai!'";//

$name_taken= "use otro nome";


/*   Expressions to translate in chat room   */
/*   ______________________________________  */
$private_message_expression = "\(per (.*)\)"; //Regular expression for beginning of private message
$before_name="(per "; // if you change the regular expression write here the text between "\ and (.*)

$after_name=")"; // if you change the regular expression write here the text after (.*) 

$not_here_string = "- L'Utente richiesto non è nella chat room -"; //receiver of private message is not in the room

$bye_string = "Arrivederci. Speriamo di vederti presto,";//message showed to dimissed user
$enter_string = "(entra in chat)";//message showed when a new user enters.

$bye_user = "(esce dalla chat)";//message showed when a user exits.

$kicked_user = "--- Sei stato cacciato dalla chat! ---";//message showed in the chat room to kicked user.

$bye_kicked_user = "La prossima volta prova a rispettare l'Etichetta";//bye for kicked users

$bye_banned_user = "Arrivederci: ti è stato proibito l'ingresso in questa chat room";//bye for banned users

$banned_user = "Mi dispiace, non puoi entrare in questa chat";//message for banned user trying to enter


/*   Expressions to translate in chat interface   */
/*   ___________________________________________  */

$intro_text="Prima di entrare, leggi le seguenti istruzioni.
";

$intro_text .="Se nella chat esiste già un utente con il tuo stesso Nome, sarà aggiunta una estensione numerica al tuo Nome. ";

$intro_text .="Per esempio, se entri con il nome Geppetto e c'è già qualcuno con il nome Geppetto, il tuo diventerà Geppetto1.
";

$intro_text .="Puoi vedere gli utenti connessi e spedire loro dei messaggi privati, attivare o disattivare l'audio (l'icona con l'Altoparlante) e rivedere la conversazione (l'icona della freccina).
";

$intro_text .="Questo è tutto! Buon Divertimento!";//

$conn="
Connessione alla Chat Room... Si prega di attendere..."; //

$you_are="Tu Sei"; //

$connected_users= "Utenti Connessi";//

$private_message_to= "messaggio privato a";//

$private_message_text="I messaggi privati possono essere visti soltanto dal mittente e dal destinatario.
";//
 
$private_message_text.="Scrivi il Nome esatto del destinatario, altrimenti non sarà in grado di visualizzare il tuo messaggio.
";// 

$private_message_text.="Ricordati che puoi copiare un testo selezionato e incollarlo in qualsiasi altro campo usando il tasto destro in Windows oppure ctrl-click in Mac.";//


/*   Expressions to translate in review messages page  */
/*   ________________________________________________  */

$review_title ="Chat. Ultimi Messaggi";// title for review messages page


/*   Expressions to translate in administration pages   */
/*   ________________________________________________  */

$link_to_admin ="Amministrazione";// text for link to administration pages

$intro_admin_title = "Develooping Chat Admin";// title for administration intro page

$intro_admin_name = "Nome";//Text for name field in administration intro page

$intro_admin_password = "Password";//Text for name field in administration intro page

$intro_admin_button= "Ok";//Text for button in administration intro page

$no_users = "Non ci sono utenti nella Chat Room";//no users in the room

$text_for_kick_button = "Caccia";//text fof kick button

$text_for_bann_button = "Banna";//text for button for bannig ips

$no_ips = "Non ci sono IP bannati dalla chat room";//no banned IPs in the room

$text_for_pardon_button = "Scusa";//text for button to pardon ips

$ip_link = "Amministrazione per IP bannati";//text for link to banned IPs

$no_ip_link = "La chat non è configurata per usare l'IP per identificare gli utenti, quindi questi non possono essere bannati";//text if you use password instead de IP

$users_link = "Amministrazione per lista utenti";//text for link to connected users

?>