<?php
error_reporting(7);
/* Traducción al euskera por Naiara Abaroa*/

/*   (Develooping flash Chat version 1.2)  */
/*   ____________________________________  */

/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    AJUSTES                        */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Ajustes del chat    */
/*   ____________________*/

$url="http://www.yourdomain.com/chat/"; //url absoluta del directorio donde se encuentran los scripts

$text_order = "down"; //usa "down" o "up" para mostrar el texto hacia abajo o hacia arriba respectivamente

$review_text_order = "down"; //igual pero en la ventana de revision de mensajes

$delete_empty_room = "no"; //usa "yes" si deseas que se borren los textos de la sala al quedarse vacia

$show_without_time = "no"; //"no" muestra siempre la hora, "yes" solo la muestra en la entrada y salida del usuario

$password_system = "ip"; //usa "ip" o "password" segn quieras usar la ip o un password para identificar usuarios

/*   NOTA:   El sistema de banning (inhabilitacion de usuarios) solo funciona con "ip" */
/*           Usa "password" preferiblemente solo en entornos donde haya usuarios conectados a travs de una misma ip*/


/*   Variables de Administracion */
/*   ___________________________ */

$admin_name = "admin"; //nombre del administrador (max. 12 caracteres)

$admin_password = "admin"; // clave del administrador (max. 12 caracteres)


/*   Variables numericas del chat    */
/*   _____________________________   */

$correct_time = 0; //diferencia en segundos con el tiempo en el servidor

$chat_lenght = 15; //numero de mensajes mostrados en la sala

$review_lenght = 500; //numero de mensajes mostrados al revisar mensajes

$total_lenght = 1000; //numero de mensajes almacenados por el sistema

$minutes_to_delete = 15; //minutes to delete inactive users


/*   Palabras para filtrar   */
/*   ______________________  */

$words_to_filter = array("", "", "", "", "", "");//lista de palabrotas para filtar, pon las que quieras

$replace_by = "*@#!";//expresion para reemplazar a las palabrotas


/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    TRADUCCION                     */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Fases para traducir en la pagina intro  */
/*   ______________________________________  */

$intro_alert="Mesedez:"; //

$alert_message_1="-Ezizenak gutxienez 4 hizki eduki behar ditu"; //

$alert_message_2="-Pasahitzak gutxienez 4 hizki eduki behar ditu"; //

$alert_message_3="-Ezizenean ez erabili tilde, hizki berezi edo zenbakirik"; //

$alert_message_4="-Sentitzen dugu, baina ezin dugu zure IPa lortu txatean sartzeko"; //

$alert_message_5="-Ezizenean ez erabili tilde edo hizki berezirik"; //

$person_word="pertsona"; //

$plural_particle=""; //
 
$now_in_the_chat= " Txat gelan orain";//

$require_sentence = "Txat honek Flash 4 behar du"; //

$name_word = "Ezizena"; //

$password_word = "Pasahitza"; //

$enter_button = "Sartu" ; //

$enter_sentence_1 = "Idatzi zure ezizena, Txat gelan sartzeko ";//

$enter_sentence_2 = "eta pasahitza";//

$enter_sentence_3 = " eta botoiari eman";//

$name_taken= "beste ezizen bat erabili";


/*   Frases para traducir en el chat   */
/*   ________________________________  */

$private_message_expression = "\( (.*) \)"; // Expresion regular para indicar mensaje privado. 'Para' en euskera es un sufijo Para Carlos= CarlosENTZAT

$before_name="( "; // si cambias la expresion regular pon aqui lo que hay entre "\ y (.*)

$after_name="entzat )"; // si cambias la expresion regular pon aqui lo que detras de (.*) 

$not_here_string = "-Erabiltzaile hori ez dago txat gelan-"; //el receptor del mensaje privado no esta en la sala

$bye_string = "Agur. Nahi duzunean bueltatu";//despedida para el usuario

$enter_string = "(Txat gelan sartu berri da)";//mensaje que avisa que un usuario entra en la sala.

$bye_user = "(Txat gelatik irten berri da)";//mensaje que avisa que un usuario abandona la sala.

$kicked_user = "---Txat gelatik kanporatua izan da---";//mensaje mostrado en el chat al usuario expulsado.

$bye_kicked_user = "Bueltatzen zarenean, jator portatu";//despedida para el usuario expulsado

$bye_banned_user = "Txat gela honetan sartzeko aukera galdu duzu";//despedida para el usuario inhabilitado

$banned_user = "Barkatu baina, ez zara ongietorria Txat gela honetan";//mensaje mostrado en el chat al usuario inhabilitado


/*   frases para traducir en el interface flash   */
/*   ___________________________________________  */

$intro_text="Sartu aurretik, ondoko informazioa irakurri.
";

$intro_text .="Txat gelan zure ezizen bera duen norbait egotekotan, zure ezizenari zenbaki bat gaineratuko zaio.";

$intro_text .="'Amaia' ezizenarekin sartzen bazara, eta 'Amaia' ezizeneko norbait dagoeneko barruan bada, zure ezizena 'Amaia1'izango da.
";

$intro_text .="Konektatuta dauden erabiltzaileak ikusi ahal izango dituzu eta mezu pribatuak bidali, soinua jarri eta kendu ('altaboza' ikonoarekin) eta elkarrizketa berbegiratu ('Gezia atzerantza' ikonoaz).
";

$intro_text .="Hori da guztia. Solasaldi ona eduki.";//

$conn="Txat gelarekin konektatzen. Itxaron apur bat, mesedez..."; //

$you_are="Zure ezizena"; //

$connected_users= "Konektatutako erabiltzaileak";//

$private_message_to= "Mezu pribatua nori";//

$private_message_text="Mezu pribatua igorle eta hartzaileak soilik ikus dezake.";//
 
$private_message_text.=" Hartzailearen ezizena ondo idatzi, edo bestela ezin izango du mezua irakurri.
";// 

$private_message_text.="gogoratu aukeratutako textua kopiatu dezakezula, eta beste kanpo batetan itsatsi, Windowsen arratoiaren eskuineko botoia erabiliz, edo Mac bada ctrl-click eginez.";//


/*   Frases para traducir en la pagina de revision de mensajes  */
/*   _________________________________________________________  */

$review_title ="Txata. Azken mezuak";// titulo de la pagina


/*   Frases para traducir en las paginas de administracion   */
/*   ______________________________________________________  */

$link_to_admin ="Administrazioa";// texto para vinculo a las paginas de administracion

$intro_admin_title = "Develooping flash chat. Administrazioa";// titulo de la pagina intro de administracion

$intro_admin_name = " Erabiltzailearen Izena";// texto para el campo nombre de la pagina intro de administracion

$intro_admin_password = "Pasahitza";// texto para el campo password de la pagina intro de administracion

$intro_admin_button= "Sartu";//texto para el boton de la pagina intro de administracion

$no_users = "Ez dago erabiltzailerik Txat gelan";//no hay usuarios en la sala

$text_for_kick_button = "Kanporatu";//texto para boton 'kicking'

$text_for_bann_button = "Gaitasuna kendu";//texto para boton 'banning'

$no_ips = "Ez dago gaitasuna kendutako IPrik Txat gelan sartzeko";//no hay IPs inhabilitadas

$text_for_pardon_button = "Barkatu";//texto para boton 'perdonar ip'

$ip_link = "Gaitasuna kendutako IPen Administrazioa";//texto para vinculo a IPs inhabilitadas

$no_ip_link = "Txata ez dago IPa aztertzen erabiltzaileak antzemateko, beraz ezin zaie gaitasuna kendu";//texto si usas password en lugar de IP

$users_link = "Erabiltzaileen Administrazioa";//texto del vinculo a usuarios conectados

?>