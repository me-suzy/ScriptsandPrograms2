<?php

error_reporting(7);

/*   Setarile Chatului              */
/*   http://www.evolva.ro           */
/*   http://www.evolva.ro/0chat/    */
/*   http://chat.evolva.ro          */
/*   Tradus de Michi                */
/*   ______________________________ */

$url="http://www.evolva.ro/0chat/chat/"; //url-ul absolut catre scripturi

$text_order = "down"; //foloseste "down" si "up" pentru afisarea mesajului in jos sau in sus

$review_text_order = "up"; //acelasi lucru pentru mesajele revazute din fereastra

$delete_empty_room = "yes"; //foloseste "yes" pentru stergerea mesajelor cand camera este goala

$show_without_time = "yes"; //"no" arata de fiecare data ora, "yes" arata ora doar cand userul intra sau iese

$password_system = "ip"; //"ip" si "password" pentru identificarea utilizatorilor

/*   NOTE:   sistemul de banare functioneaza numai cu "ip" */
/*           Foloseste "password" doar atunci cand utilizatorii au acelasi ip */


/*   Variabilele Administratorului   */
/*   _____________________________   */

$admin_name = "Admin"; //numele administrator (maxim 12 caractere)

$admin_password = "Admin"; // parola administratorului (maxim 12 caractere)


/*   Variabilele numerica ale Chat-ului    */
/*   __________________________________    */

$correct_time = 0;//diferenta in secunde a timpului din server

$chat_lenght = 50;//numarul de mesaje afisate in camera de chat

$review_lenght = 500;//numarul de mesaje afisate in fereastra de mesaje revazute

$total_lenght = 1000;//numarul de mesaje pastrate in fisierul de chat


/*   Filtru de cuvinte (numai peste 18 ani)   */
/*   ______________________________________   */

$words_to_filter = array("muie", "pula", "pizda", "fut", "cacat", "pisat", "rahat", "cur", "sug", "suge", "linge", "lingea", "futea",/*<<romanian bad words */ "fucking", "fuck", "shit", "cunt", "piss", "neuken", "merde", "&nbsp;cul&nbsp;", "&nbsp;con&nbsp;", "&nbsp;pd&nbsp;", "&nbsp;pede&nbsp;", "&nbsp;pédé&nbsp;", "picsa", "fasz", "kurva", "cazzo", "fottere", "merda", "stronzo", "fica", "fodendo", "foda", "merda", "pica", "caralho", "mierda", "joder", "follar", "jodan", "gilipollas", "capullo");//lista cu cuvintele obscene care se inlocuiesc (adauga mai multe daca vrei)

$replace_by = "####";//expresia care cenzureaza cuvintele obscene


/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                     TRADUCERE                     */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Expresii traduse in pagina de intro  */
/*   ______________________________________  */

$intro_alert="Va rog :"; //

$alert_message_1="-Lungimea Numelui trebuie sa aiba cel putin 4 caractere"; //

$alert_message_2="-Lungimea parolei trebuie sa aiba cel putin 4 caractere"; //

$alert_message_3="-Nu folositi caractere speciale sau numere in nume"; //

$alert_message_4="-Scuze, ne trebuie IP-ul pentru a-ti da acces in chat"; //

$alert_message_5="-Nu folosi caractere speciale in parola"; //

$person_word=" utilizator"; //

$plural_particle="i"; //
 
$now_in_the_chat= " acum in camera de chat";//

$require_sentence = "Acest chat are ca cerinte Macromedia Flash 6 plugin";

$name_word = "Nume : "; //

$password_word = "Parola : "; //

$enter_button = " Intra " ; //

$enter_sentence_1 = "Pentru a intra in chat introduceti numele ";//

$enter_sentence_2 = "si parola";//

$enter_sentence_3 = " si apasati butonul";//


/*   Expresii traduse in camera de chat   */
/*   __________________________________   */

$private_message_expression = "\(pentru (.*)\)"; //expresie standard pentru inceperea mesajului privat

$before_name="(pentru "; // daca schimbi expresia standard scrie-o pe cea noua intre "\ and (.*)

$after_name=")"; // daca schimbi expresia standard scrie-o pe cea noua intre (.*) 

$not_here_string = " - Primitorul nu este in camera de chat-"; //primitorul mesajului privat nu este in camera de chat

$bye_string = "Larevedere. Speram sa te revedem cat de curand, ";//mesaj aparut dupa iesire

$enter_string = "(a intrat in chat)";//mesaj aparut cand a intrat un nou utilizator.

$bye_user = "(a parasit chat-ul)";//mesaj aparut cand utilizatorul a iesit.

$kicked_user = ":: Ai fost dat afara din aceasta camera ::";//mesaj aparut in camera de chat celor dati afara.

$bye_kicked_user = "!!! Data viitoare, incearca sa fi politicos !!!";//larevedere pentru utilizatorii dati afara

$bye_banned_user = "!!! Larevedere, accesul tau in aceasta camera a fost restrictionat !!!";//larevedere pentru userii banati

$banned_user = "!!! Nu mai ai acces in aceasta camera !!!";//mesaj pentru utilizatorii banati aparut la intrare


/*   Expresii traduse in interfata de chat   */
/*   _____________________________________   */

$intro_text="     Inainte sa intrati, cititi urmatoarele indicatii. 
";

$intro_text .="
Daca este cineva in camera cu numele tau iti vom adauga sufix numeric numelui. 
";

$intro_text .="
exemplu: Daca intri cu numele de Mihai iar in chat se afla deja cineva cu acest nume, numele tau va deveni Mihai1. 
";

$intro_text .="
Poti sa vezi utilizatorii din camera de chat si poti sa le trimiti mesaje private (apasand pe numele respectiv din lista), activeaza si dezactiveaza sunetul (iconita cu difuzorul) si poti sa revizuiesti conversatiile (iconita cu sageata inapoi). 
";

$intro_text .="
  Asta'i tot. Distractie placuta pe chat.
  ";//

$conn="
Se conecteaza cu camera de chat. Asteptati un moment..."; //

$you_are="tu esti"; //

$connected_users= "Utilizatori conectati ";//

$private_message_to= "mesaj privat lui ";//

$private_message_text="Mesajele private pot fi vazute doar de utilizator si de primitor.";
 
$private_message_text.="Scrie numele exact al primitorului sau el nu va putea primi mesajul."; 

$private_message_text.="Iti reamintim ca poti selecta si copia un text si pune in alta parte folosind butonul dreapta al mouselui in windows si Ctrl-clik in Mac.";


/*   Expresii traduse in pagina de mesaje revizuite  */
/*   ______________________________________________  */

$review_title ="Chat. Ultimele Mesaje";// titlu pentru ultimul mesaj


/*   Expresii traduse in pagina de administrare   */
/*   __________________________________________   */

$link_to_admin ="Administrare";// Text pentru link-ul catre pagina de administrare

$intro_admin_title = "EVOLVA CHAT v1.8 Admin";// titlu pentru pagina de administrare

$intro_admin_name = "Nume";//Text pentru casuta de nume in pagina de administrare

$intro_admin_password = "Parola";//text pentru casuta de nume din pagina de administrare

$intro_admin_button= "Ok";//text pentru butonul din prima pagina de administrare

$no_users = "Nu sunt utilizatori in camera";//nu sun tilizatori in camera

$text_for_kick_button = " Afara ";//text pentru butonul de Kick

$text_for_bann_button = " Bann ";//text pentru butonul de bannat ip-ul

$no_ips = "Nu sunt IP-uri banate in camera.";//nu sunt ip-uri banate in camera

$text_for_pardon_button = "Pardon";//text pentru a sterge un ip banat

$ip_link = "Administratia pentru IP-urile banate";//text pentru link-ul catre ip-urile banate

$no_ip_link = "Chatul nu foloseste IP-uri pentru diferentierea utilizatorilor, asa ca ei nu pot fi banati";//text daca inlocuiesti ip-ul cu parola

$users_link = "Administratia pentru lista de utilizatori";//text pentru link-ul catre utilizatorii conectati

?>