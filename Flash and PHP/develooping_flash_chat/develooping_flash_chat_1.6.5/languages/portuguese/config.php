<?php
error_reporting(7);

/*   (Develooping flash Chat version 1.2)  */
/*   ____________________________________  */

/* portuguese translation by Douglas Pechtoll Prado */

/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                   Configurações                   */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Configurações do Chat 	*/
/*   _________________		*/

$url="http://www.seudominio.com.br/chat/chat/"; //url de seu diretório de escripts

$text_order = "down"; //use "down" ou "up" para mostrar mensagens de cima para baixo ou vice-versa

$review_text_order = "down"; //o mesmo acima mas para a janela de revisão de mensagens

$delete_empty_room = "no"; //use "yes" para deletar as mensagens quando a sala estiver vazia

$show_without_time = "no"; //"no" mostrar sempre a hora , "yes" mostrar a hora apenas quando o usuário entra ou sai da sala

$password_system = "ip"; //"ip" ou "password" usar ip ou password para identificar usuários

/*   NOTE:   O sistema de "banimento" funciona somente com a opção acima em "ip" */
/*           Use "password" somente quando os usuários vem do mesmo ip*/


/*   Variáveis administrativas */
/*   _______________________   */

$admin_name = "admin"; //nome do usuário administrador(max. 12 caracteres)

$admin_password = "admin"; // password do administrador(max. 12 caracteres)


/*   Variáveis numéricas do Chat  	*/
/*   _______________________   	*/

$correct_time = 0;//diferença em segundos para o horário do servidor

$chat_lenght = 15;//número de mensagens mostradas na sala do Chat

$review_lenght = 500;//número de mensagens mostradas na janela de revisão

$total_lenght = 1000;//número de mensagens arquivadas no arquivo do chat

$minutes_to_delete = 15; //minutes to delete inactive users


/*   Palavras Filtradas */
/*   _________________  */

$words_to_filter = array("fodendo", "foda", "merda", "pica", "caralho");//lista de palavras que devem ser trocadas (adicione mais se você quiser)

$replace_by = "*@#!";// expressão que será apresentada no local das palavras trocadas da lista acima


/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/
/*                    Tradução                       */
/*___________________________________________________*/
/*___________________________________________________*/
/*___________________________________________________*/


/*   Expressões a traduzir na página de introdução 	*/
/*   _____________________________________________ 	*/

$intro_alert="Por favor:"; //

$alert_message_1="-O nome deve ter pelo menos 4 caracteres"; //

$alert_message_2="-A password (senha) deve ter pelo menos 4 caracteres"; //

$alert_message_3="-Não use caracteres especiais ou números no nome"; //

$alert_message_4="-Desculpe, precisamos conhecer o número de seu IP para permitir seu acesso"; //

$alert_message_5="-Não use caracteres especiais na password (senha)"; //

$person_word=" pessoa"; //

$plural_particle="s"; //
 
$now_in_the_chat= " estão agora na sala de Chat";//

$require_sentence = "Este chat requer Flash 4"; //

$name_word = "Nome"; //

$password_word = "Senha"; //

$enter_button = "Enter" ; //

$enter_sentence_1 = "Para entrar na sala de chat , digite seu nome ";//

$enter_sentence_2 = "e sua senha";//

$enter_sentence_3 = " e click no botão";//

$name_taken= "nome escolhido";


/*   Expressões a traduzir na sala de chat   */
/*   ______________________________________  */

$private_message_expression = "\(para (.*)\)"; //Expressão regular para o inicio das mensagens particulares

$before_name="(para "; // se você mudou a Expressão regular acima , utilize aqui a mesma palavra colocada entre "\ e (.*)

$after_name=")"; // se você mudou a Expressão regular , utilize aqui o mesmo texto que utilizou após (.*) 

$not_here_string = "-O destinatário não está na sala-"; // O destinatário de uma mensagem particular não está na sala

$bye_string = "Até logo. Esperamos vê-lo em breve,";//mensagem apresentada ao usuário que deixa a sala de chat

$enter_string = "(entrou na sala)";//mensagem apresentada a todos quando um novo usuário entra na sala de chat.

$bye_user = "(deixou a sala)";//mensagem apresentada a todos quando um usuário deixa a sala de chat.

$kicked_user = "---Você foi expulso desta sala de chat---";//mensagem apresentada ao usuário que é expulso da sala de chat.

$bye_kicked_user = "Da próxima vez, tente ser mais educado";//mensagem de despedida para usuários expulsos

$bye_banned_user = "Sua entrada nesta sala de chat foi proibida";//despedida a usuários banidos

$banned_user = "Sinto muito, você não pode entrar nesta sala";//mensagem apresentada a usuários banidos que tentam entrar na sala


/*   Expressões a traduzir na interface do chat   */
/*   ___________________________________________  */

$intro_text="Antes de entrar, leia as seguintes informações.
";

$intro_text .="Caso exista na sala alguém com o mesmo nome escolhido por você, um número será adicionado ao final de seu nome. ";

$intro_text .="Por ex. caso o nome digitado por você seja Carlos, e já exista alguém na sala chamado Carlos, seu nome será alterado para Carlos1.
";

$intro_text .="Você pode ver os usuários conectados e enviar mensagens particulares aos mesmos, ativar e desativar o som(ícone 'alto-falante') e revisar a conversa (ícone 'seta de retorno').
";

$intro_text .="Isto é tudo. Aproveite o chat.";//

$conn="
Conectando a sala de chat. Por favor, aguarde um momento..."; //

$you_are="você é"; //

$connected_users= "Usuários conectados";//

$private_message_to= "mensagem particular para";//

$private_message_text="As mensagens particulares só podem ser vistas pelo próprio remetente e o respectivo destinatário.
";//
 
$private_message_text.="Escreva o nome do usuário exatamente como aparece, ou o mesmo não receberá a mensagem.
";// 

$private_message_text.="Lembre-se que você pode copiar um texto selecionado e cola-lo em outro campo usando o botão direito no Windows ou control-click no Mac.";//


/*   Expressões a traduzir na página de revisão de mensagens  */
/*   _______________________________________________________  */

$review_title ="Últimas mensagens no Chat";// titulo da página de revisão de mensagens


/*   Expressões a traduzir na página de administração   */
/*   _________________________________________________  */

$link_to_admin ="Administração";// texto para o link da página de administração

$intro_admin_title = "Administração do Develooping Chat";// título da página de administração

$intro_admin_name = "Nome";//Texto para o campo nome na página de administração

$intro_admin_password = "Senha";//Texto para o campo senha na página de administração

$intro_admin_button= "Ok";//Texto do botão página de administração

$no_users = "Não existem usuários na sala";// mensagem caso não existam usuários na sala 

$text_for_kick_button = "Expuls";//texto para o botão expulsar

$text_for_bann_button = "Banir";//texto para o botão banir ips

$no_ips = "Não existem IP's banidos na sala";//

$text_for_pardon_button = "Perdoar";//texto para o botão de perdoar ips

$ip_link = "Administração de IPs banidos";//texto para o link de IPs banidos

$no_ip_link = "O chat não está usando o IP para identificar os usuários , então eles não podem ser banidos";//texto caso você use senha ao invés de IP

$users_link = "Administração por lista de usuários";//texto para o link de usuários conectados 

?>
