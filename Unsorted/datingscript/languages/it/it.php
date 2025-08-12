<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               it.php                           #
# File purpose            Italian language file            #
# File created by         D.Bianco <webmaster@9euro.com>   #
############################################################

define('C_HTML_DIR','ltr'); // HTML direction for this language
define('C_CHARSET', 'iso-8859-1'); // HTML charset for this language

### !!!!! Please read it: RULES for translate!!!!! ###
### 1. Be carefull in translate - don`t use ' { } characters
###    You can use them html-equivalent - &#39; &#123; &#125;
### 2. Don`t translate {some_number} templates - you can only replace it - 
###    {0},{1}... - is not number - it templates
###################################

$w=array(
'<font color=red size=3>*</font>', //0 - Symbol for requirement field
'Errore di sicurezza - #', //1
'Questo indirizzo email è già presente nel database. Si prega di sceglierne un altro!', //2
'Nome errato. Il numero di caratteri deve essere tra  {0} e {1} ', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Cognome errato. Il numero di caratteri deve essere tra {0} - {1} ', //4
'Data di nascita errata', //5
'Password errata. Il numero di caratteri deve essere tra {0} - {1} chars', //6
'Selezionare il sesso', //7
'Selezionare il genere ricercato', //8
'Seleziona il tipo di relazione desiderata', //9
'Seleziona la nazione', //10
'Email errata o incompleta', //11
'Pagina web errata', //12
'Numero ICQ errato', //13
'AIM errato', //14
'Inserire il numero di telefono', //15
'Inserire la città', //16
'Selezionare lo stato civile', //17
'Scegliere la risposta riguardo i figli', //18
'Seleziona la tua altezza', //19
'Seleziona il peso', //20
'Seleziona altezza cercata', //21
'Seleziona peso cercato', //22
'Seleziona il colore dei capelli', //23
'Seleziona il colore degli occhi', //24
'Seleziona la razza', //25
'Seleziona la religione', //26
'Seleziona la razza cercata', //27
'Seleziona la religione cercata', //28
'Seleziona riguardo il fumo', //29
'Seleziona riguardo il bere', //30
'Seleziona riguardo la formazione', //31
'Il tuo lavoro:', //32
'Età desiderata', //33
'Come ci hai trovati', //34
'Descrivi i tuoi hobby:', //35
'Campo Hobby errato. Non può superare  {0} caratteri', //36
'Campo Hobby errato. Ciascun hobby non può superare {0} caratteri', //37
'Scrivi a proposito di te:', //38
'Campo descrizione errato. Non può superare {0} caratteri', //39
'Campo descrizione errato. Ciascuna parola non può superare {0} caratteri', //40
'La tua foto è necessaria!', //41
'Congratulazioni! <br>La tua attivazione ti è stata inviata via email. <br>Devi confermare l&#39attivazione!', //42 - Message after register if need confirm by email
'Confirm your registration', //43 - Confirm mail subject
'Thanks for registering in our site...
Please enter this link for confirm your register:

', //44 - Confirm message
'Grazie per la registrazione. Il tuo profilo verrà approvato in breve tempo. Torna presto...', //45 - Message after registering if admin allowing is needed
'Congratulazioni! <br>Il tuo profilo è stato aggiunto al database!<br><br>Il tuo login:', //46
'<br>La tua password:', //47
'Ripeti la password', //48
'Le password non sono identiche', //49
'Utente registrato', //50
'Il tuo nome', //51
'caratteri', //52
'Il tuo cognome', //53
'Password', //54
'Ripeti password', //55
'Compleanno', //56
'Genere', //57
'Tipo di relazione', //58
'Nazione', //59
'Email', //60
'Webpage', //61
'ICQ', //62
'AIM', //63
'Tel.', //64
'Città', //65
'Stato civile', //66
'Figli', //67
'Altezza', //68
'Peso', //69
'Colore capelli', //70
'Colore occhi', //71
'Razza', //72
'Religione', //73
'Fumo', //74
'Bere', //75
'Formazione', //76
'Professione', //77
'Hobby', //78
'Descriviti e spiega come deve essere la persona che cerchi come potenziale partner.', //79
'Cerco', //80
'Cerco razza', //81
'Cerco religione', //82
'Cerco età', //83
'Cerco altezza', //84
'Cerco peso', //85
'Come ci hai trovati?', //86
'Foto', //87
'Home', //88
'Registra', //89
'Area membri', //90
'Cerca', //91
'Contatto', //92
'FAQ', //93
'Statistiche', //94
'ID menu membri n.', //95
'Vedi messaggi', //96
'La mia camera da letto:', //97
'Il mio profilo', //98
'Cambia profilo', //99
'Cambia password', //100
'Rimuovi profilo', //101
'Esci', //102
'Tempo di elaborazione:', //103
'sec.', //104
'Utenti online:', //105
'Ospiti online:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Solo gli utenti registrati possono accedere alla ricerca avanzata', //108
'Attenzione, "Età minima" deve essere minore di "Età massima"', //109
'La ricerca non ha prodotto alcun risultato', //110
'No', //111 Picture available?
'Sì', //112 Picture available?
'Non è possibile connettersi al server<br>mysql login o mysql password errata.<br>Controlla in config file', //113
'Non è possibile connettersi al server<br>Database inesistente<br>o nome del database errato in config', //114
'Pagine :', //115
'Risultati ricerca: ', //116
'Totale : ', //117 
'Nome utente', //118
'Utilizzi', //119
'Età', //120
'Nazione', //121
'Città', //122
'Ultimo accesso', //123
'Data registrazione', //124
'Ricerca avanzata', //125
'User ID', //126
'Nome', //127
'Cognome', //128
'Segno zodiacale', //129
'Altezza', //130
'Peso', //131
'Genere', //132
'Tipo di relazione', //133
'Stato civile', //134
'Figli', //135
'Colore capelli', //136
'Colore occhi', //137
'Razza', //138
'Religione', //139
'Fumo', //140
'Bere', //141
'Formazione', //142
'Ricerca utenti con', //143
'Webpage', //144
'ICQ', //145
'AIM', //146
'Tel.', //147
'Registrato\a in ', //148
'Ordina risultati per', //149
'Risultati per pagina', //150
'Ricerca semplice', //151
'Accesso inibito ai non membri', //152
'Accesso chiuso per invio profilo errato', //153
'Utente inserito in tabella profili errati', //154
'Grazie, Utente aggiunto in tabella profili errati, verrà controllato in breve tempo dal personale', //155
'Accesso inibito uso camera da letto', //156
'Utente già nella tua camera da letto', //157
'Grazie, utente aggiunto alla tua camera da letto', //158
'Il tuo profilo è stato aggiunto per controllo amministrativo!', //159
'Il tuo profilo è stato aggiunto al database', //160
'Errore attivazione profilo. Potrebbe essere già attivo', //161
'FAQ database vuoto', //162
'FAQ risposta n.', //163
'Tutti i campi devono essere riempiti', //164
'Il tuo messaggio è stato inviato', //165
'Inserisci oggetto', //166
'Inserisci messaggio', //167
'Oggetto', //168
'Messaggio', //169
'Invia messaggio', //170
'Per membri', //171
'User ID', //172
'Password dimenticata', //173
'Dillo ad un amico', //174
'Amico - {0} email', //175
'Compleanni di oggi', //176
'Nessun compleanno', //177
'Benvenuto nel nostro sitp AzDGDating', //178 Welcome message header
'AzDGDatingLite - è un grande sistema per trovare amicizie o partner, per divertirsi, conoscersi e magari avere una lunga relazione. Incontrare e socializzare è sicuro e divertente. Le precauzioni dettate dal buon senso vanno comunque prese quando ci si appresta ad incontrare qualcuno faccia a faccia per la prima volta.<br><br>Puoi anche trovare nuovi amici utilizzando il nostro sistema email privato. Questo ti consente di comunicare con gli altri membri e di conoscersi meglio reciprocamente e sviluppare una relazione.<br>', //179 Welcome message
'Ultimi {0} utenti registrati', //180
'Ricerca rapida', //181
'Ricerca avanzata', //182
'Foto del giorno', //183
'Statistica semplice', //184
'Il tuo ID deve essere numerico', //185
'Errato Login ID o password', //186
'Invio messaggi email inibito', //187
'Invia messaggio alla email di ID utente', //188
'Nessun utente collegato', //189
'Pagina Dillo ad un amico non disponibile', //190
'Saluti da {0}', //191 "Recommend Us" subject, {0} - username
'Ciao da {0}!

How are you:)

Please visit this site - its fine:
{1}', //192 "Recommend Us" message, {0} - username, {1} - site url
'Scrivi correttamente email Amico {0} ', //193
'Inserisci il tuo nome ed email', //194
'YLa tua password da {0}', //195 Reming password email subject
'Questo è un account disattivato o non esiste nel database.<br>Scrivi al tuo admin per questo problema dal Contatto. Includi il tuo ID.', //196
'Ciao!

Your login ID#:{0}
Your Password:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'La tua password ti è stata inviata via email.', //198
'Inserisci il tuo ID', //199
'Spedisci password', //200
'Accesso inibito per invio messaggi email', //201
'Invia messaggio a user ID', //202
'Avvertimi quando leggerà il messaggio', //203
'Nessun utente nel database', //204
'Statistica non disponibile', //205
'Questo user ID non esiste', //206
'Profilo ID', //207
'Nome Utente', //208
'Cognome Utente', //209
'Compleanno', //210
'Email', //211
'Messaggio da AzDGDating', //212 - Subject for email
'Professione', //213
'Hobby', //214
'Io', //215
'Popolarità', //216
'Invia email', //217
'Profilo errato', //218
'Aggiungi alla mia camera da letto', //219
'Assenza di file da caricare, <br>o la sua dimensione era eccedente il limite di {0} Kb. Il tuo file è di {1} Kb', //220
'La larghezza della foto era di {0} px o altezza maggiore del limite di {1} px.', //221
'Il tipo di file da caricare era errato (solo jpg, gif and png sono consentiti). ', //222
'(Max. {0} Kb)', //223
'Statistiche per Nazioni', //224
'Non ci sono messaggi per te', //225
'Totale messaggi - ', //226
'Num', //227 Number
'Da', //228
'Data', //229
'Canc', //230 Delete
'<sup>Nuovo</sup>', //231 New messages
'Cancella messaggi selezionati', //232
'Messaggio da - ', //233
'Rispondi', //234
'Ciao, hai scritto {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Il tuo messaggio è stato letto', //236
'Il tuo messaggio:<br><br><span class=dat>{0}</span><br><br>è stato letto da {1} [ID#{2}] in {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} messaggi eliminati!', //238
'Inserisci vecchia password', //239
'Inserisci nuova password', //240
'Ripeti nuova password', //241
'Cambia password', //242
'Vecchia password', //243
'Nuova password', //244
'Ripeti nuova password', //245
'Non hai nessun utente nella camera da letto', //246
'Data di aggiunta', //247
'Cancella utenti selezionati', //248
'Sei sicuro di voler eliminare il profilo?<br>Tutti i tuoi messaggi e immagini verranno rimossi dal database.', //249
'Utente con ID={0} eliminato dal database', //250
'Il tuo account verrà eliminato dopo un controllo amministrativo', //251
'{0} utenti eliminati dalla tua camera da letto!', //252
'Password non identiche o non presente nel database', //253
'Non puoi cambiare la password', //254
'Vecchia password errata. Torna indietro e reinseriscila!', //255
'Password cambiata!', //256
'Impossibile eliminare foto', //257
'Profilo variato', //258
' - Elimina immagine', //259
'Sessione terminata. Puoi chiudere il browser', //260
'Immagine bandiera non disponibile', //261
'Linguaggi', //262
'Invia', //263
'Login [3-16 chars [A-Za-z0-9_]]', //264
'Login', //265
'La tua login deve essere di 3-16 caratteri e solo  A-Za-z0-9_ chars is available', //266
'Login già presente nel database. Scegline una diversa!', //267
'Utenti totali - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=it&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
'User type', //270
'Purshase date', //271
'Search results position', //272
'Price', //273
'month', //274
'Purshase Last date', //275
'Higher than', //276
'Purshase', //277
'Purshase with', //278
'PayPal', //279
'Thanks for your registration. Payment has been succesfully send and will be checked by admin in short time.', //280
'Incorrect error. Please try again, or contact with admin!', //281
'Send congratulation letter about privilegies activating', //282
'User type has successfully changed.', //283
'Email with congratulations has been send to user.', //284
'ZIP',// 285 Zip code
'Congratulations, 

Your status is changed to {0}. This privilegies will be available in next {1} month.

Now you can check your messages in your box.

__________________________________
{2}', //286 {0} - Ex:Gold member, {1} - month number, {2} - Sitename from config
'Congratulations', //287 Subject
'ZIP code must be numeric', //288
'Keywords', //289
'We are sorry, but the following error occurred:', //290
'', //291
'', //292
'', //293
'', //294
'', //295
'', //296
'', //297
'', //298
'' //299
); 
?>

