<?php
error_reporting(7);

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

$words_to_filter = array("merda", "joder", "follar", "jodan", "gilipolles", "capullo");//lista de palabrotas para filtar, pon las que quieras

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

$intro_alert="Si us plau:"; //

$alert_message_1="-El nom ha de tenir almenys 4 caràcters"; //

$alert_message_2="-La contrasenya ha de tenir al menys 4 caràcters"; //

$alert_message_3="-No usis accents, caràcters especials o números al nom"; //

$alert_message_4="-Ho sentim, però no podem obtenir la teva IP per dar-te accés"; //

$alert_message_5="-No usis accents ni caràcters especials al nom"; //

$person_word=" persone"; //

$plural_particle="s"; //
 
$now_in_the_chat= " ara a la sala de xat";//

$require_sentence = "Aquest xat requereix Flash 4"; //

$name_word = "Nom"; //

$password_word = "Contrasenya"; //

$enter_button = "Entrar" ; //

$enter_sentence_1 = "Per entrar a la sala de xat, escriu el teu nom ";//

$enter_sentence_2 = "i contrasenya";//

$enter_sentence_3 = " i pulsa el botó";//

$name_taken= "escriba otro nombre";


/*   Frases para traducir en el chat   */
/*   ________________________________  */

$private_message_expression = "\(per (.*)\)"; // Expresion regular para indicar mensaje privado.

$before_name="(per "; // si cambias la expresion regular pon aqui lo que hay entre "\ y (.*)

$after_name=")"; // si cambias la expresion regular pon aqui lo que detras de (.*) 

$not_here_string = "-El destinatari no es troba-"; //el receptor del mensaje privado no esta en la sala

$bye_string = "Adeu. Torna quan vulguis";//despedida para el usuario

$enter_string = "(acaba d'entrar a la sala)";//mensaje que avisa que un usuario entra en la sala.

$bye_user = "(acaba de sortir de la sala)";//mensaje que avisa que un usuario abandona la sala.

$kicked_user = "---Has estat expulsat de la sala---";//mensaje mostrado en el chat al usuario expulsado.

$bye_kicked_user = "Si tornes, procura comportar-te";//despedida para el usuario expulsado

$bye_banned_user = "T'ha estat denegada l'entrada a aquesta sala";//despedida para el usuario inhabilitado

$banned_user = "Ho sento, no ets benvingut a aquest lloc";//mensaje mostrado en el chat al usuario inhabilitado


/*   frases para traducir en el interface flash   */
/*   ___________________________________________  */

$intro_text="Abans d'entrar, llegeix les següents indicacions.";

$intro_text .="Si hi ha algú a la sala amb el nom que has escollit, et serà afegida una extensió numèrica. ";

$intro_text .="Si entres amb el nom de 'Carles' i ja hi ha algú anomenat 'Carles' a la sala, el teu nom serà 'Carles1'.";

$intro_text .="Podràs veure als usuaris conectats i enviar-los missatges privats, activar i desactivar el só (icona 'altaveu') i revisar la conversa (icona 'fletxa enrera').";

$intro_text .="Això és tot. Disfruta de la xerrada.";//

$conn="Conectant amb la sala de xat. Si us plau, espera un moment..."; //

//not used in version 1.5
$you_are="tu ets"; //

//not used in version 1.5
$connected_users= "Usuaris conectats";//

$private_message_to= "missatge privat a";//

//not used in version 1.5
$private_message_text="Els missatges privats solament poden ser vistos per l'emisor i el receptor.";//
 
//not used in version 1.5
$private_message_text.="Escriu el nom exacte del receptor o no podrà veure el missatge.";// 

//not used in version 1.5
$private_message_text.="recorda que pots copiar un texte sel·leccionat i enganxar-lo en un altre camp usant el botó dret a Windows o ctrl-click a Mac.";//


/*   Frases para traducir en la pagina de revision de mensajes  */
/*   _________________________________________________________  */

$review_title ="Xat. Últims Missatges";// titulo de la pagina


/*   Frases para traducir en las paginas de administracion   */
/*   ______________________________________________________  */

$link_to_admin ="Administració";// texto para vinculo a las paginas de administracion

$intro_admin_title = "Develooping flash chat. Administració";// titulo de la pagina intro de administracion

$intro_admin_name = "Nom";// texto para el campo nombre de la pagina intro de administracion

$intro_admin_password = "Contrasenya";// texto para el campo password de la pagina intro de administracion

$intro_admin_button= "Ok";//texto para el boton de la pagina intro de administracion

$no_users = "No hi ha usuaris a la sala";//no hay usuarios en la sala

$text_for_kick_button = "Expulsar";//texto para boton 'kicking'

$text_for_bann_button = "Inhabilitar";//texto para boton 'banning'

$no_ips = "No hi ha IPs inhabilitades per entrar a la sala";//no hay IPs inhabilitadas

$text_for_pardon_button = "Perdonar";//texto para boton 'perdonar ip'

$ip_link = "Administració d'IPs inhabilitades";//texto para vinculo a IPs inhabilitadas

$no_ip_link = "El xat no està usant la IP per identificar usuaris, així que aquests no poden ser inhabilitats";//texto si usas password en lugar de IP

$users_link = "Administració d'usuaris";//texto del vinculo a usuarios conectados

?>
