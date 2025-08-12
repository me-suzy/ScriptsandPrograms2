<?php
##########################################################################
# Italian translation by Massimo Danieli <m.danieli@sisgeinformatica.it>.
##########################################################################

$s1 = "Aiuto";
$s2 = "Scrivi mail";
$s3 = "Aggiungi/rimuovi iscrizioni";
$s4 = "Opzioni liste";
$s5 = "Crea/Rimuovi lista";
$s6 = "Ora sar&agrave creata la tabella principale di Postlister. Deve essere fatto solo una volta. Hai scelto di chiamarla <i>$mainTable</i>. Se vorrai cambiare questo nome dovrai editare il file <i>settings.php</i> e cambiare il valore <i>\$mainTable</i> . Altrimenti tutto ci&ograve che devi fare &egrave premere il bottone qui sotto.";
$s7 = "Crea la tabella";
$s8 = "Errore:";
$s9 = "Indietro";
$s10 = "Il nome della tabella non &egrave valido. Pu&ograve contenere solo caratteri alfanumerici -- NON spazi o caratteri speciali.";
$s11 = "La tabella principale Postlister <i>$mainTable</i> &agrave stata creata. Ora puoi iniziare  <a href=lists.php>creare la mailing list</a>.";
$s12 = "Scegli una mailing list:";
$s13 = "OK";
$s14 = "Non esistono mailing list disponibili.";
$s15 = "Crea la lista";
$s16 = "Crea una mailing list";
$s17 = "Nome della lista:";
$s18 = "Scegli un nome per la nuova mailing list. Il nome NON pu&ograve essere pi&ugrave lungo di 20 caratteri, e non pu&ograve contenere spazi o altri caratteri speciali -- solo le lettere  a-z e numeri.";
$s19 = "Rimuovi una mailing list";
$s20 = "Quale mailing list vuoi rimuovere?";
$s21 = "Cancella";
$s22 = "La mailing list <i>$listeOpret</i> &egrave stata creata. Ora puoi <a href=edit.php?liste=$listeOpret>modificala </a>.";
$s23 = "Sei sicuro di voler cancellare la lista <i>$listeSlet</i>? Se lo farai, perderai tutti gli indirizzi contenuti in essa.";
$s24 = "Cancella";
$s25 = "Cancella la lista";
$s26 = "La lista <i>$listeSletBekraeft</i> &egrave stata cancellata.";
$s27 = "Indirizzo del mittente, es. <i>Nome.Cognome &lt;nome.cognome@$SERVER_NAME&gt;</i>:";
$s28 = "La firma che sar&agrave inserita in fondo alle mail inviate alla lista:";
$s29 = "The subscribe message -- il messaggio che verr&agrave inviato a chi voglia iscriversi alla lista.";
$s30 = "Salva modifiche";
$s31 = "Il messaggio di iscrizione <b>deve</b> contenere la parola <i>[SUBSCRIBE_URL]</i>.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s32 = "Hai ricevuto questa email perchè tu o altri ti hanno iscritto alla lista $listeOpret a
http://$HTTP_HOST.
Prima che noi possiamo aggiungere il tuo indirizzo alla nostra mailing list, dobbiamo essere certi che questo indirizzo
sia attivo e che tu voglia davvero iscriverti alla lista. Perciò ti chiediamo di visitare questo URL 
e di confermare la tua iscrizione:

<[SUBSCRIBE_URL]>

Grazie.";

$s33 = "Sono stati salvati i cambiamenti alla lista <i>$liste</i>.";
$s34 = "Aggiungi un indirizzo";
$s35 = "Cancella un indirizzo";
$s36 = "Aggiungi";
$s37 = "Inserisci il nuovo indirizzo che sar&agrave aggiunto alla lista -- es. <i>pippo@esempio.com</i>:";
$s38 = "<i>$epostadresseTilfoej</i> non &egrave un indirizzo email valido.";
$s39 = "L'indirizzo <i>$epostadresseTilfoej</i> &egrave stato aggiunto alla lista <i>$liste</i>.";
$s40 = "Sembra che l'indirizzo <i>$epostadresseTilfoej</i> sia gi&agrave presente nella lista.";
$s41 = "Mostra";
$s42 = "tutte le iscrizioni";
$s43 = "approvato";
$s44 = "non-approvato";
$s45 = "inizianti per";
$s46 = "che contengono";
$s47 = "Nessun risultato.";
$s48 = "approvato";
$s49 = "non-approvato";
$s50 = "L'indirizzo <i>$sletDenne</i> &egrave stato rimosso dalla lista <i>$liste</i>.";
$s51 = "Scrivi un messaggio alla lista <i>$liste</i>";
$s52 = "Da:";
$s53 = "Soggetto:";
$s54 = "Contenuto:";
$s55 = "La linea sar&agrave tagliata al carattere 72 ";
$s56 = "Anteprima";
$s57 = "Stampa";
$s58 = "Conteggio parole";
$s59 = "Funzioni";
$s60 = "Numero di caratteri:";
$s61 = "Numero di parole:";
$s62 = "Devi fornire il giusto username e password per accedere a questa pagina.";
$s63 = "Puoi usare le seguenti variabili nel contenuto della email:";
$s64 = "L'indirizzo del destinatario.";
$s65 = "The unsubscribe URL -- l' URL che il destinatario deve visitare per cancellarsi dalla lista.";
$s66 = "A:";
$s67 = "Invia";
$s68 = "Indietro -- Voglio correggere la email";
$s69 = "Mailing lists";
$s70 = "Iscriviti alla nostra/e lista/e:";
$s71 = "Il tuo indirizzo email:";
$s72 = "Scegli mailing list:";
$s73 = "Iscrivi";
$s74 = "Cancella iscrizione";
$s75 = "<i>$email</i> non &egrave un indirizzo email valido.";
$s76 = "You did not specify whether you want to subscribe or unsubscribe to the mailing list. The problem may be caused by an error in the formular which you submitted. Please contact the website administrator.";
$s77 = "Iscrizione alla lista $list";
$s78 = "Cancellazione dalla lista $list";
$s79 = "Grazie per esserti iscritto alla lista <i>$list</i>. Prima di poterti aggiungere alla lista di richiediamo una conferma. Tra qualche minuto riceverai una mail contenente un URL che dovrai visitare per confermare la tua richiesta.";
$s80 = "Prima di cancellarti dalla lista <i>$list</i> ti chiediamo di confermarci la tua decisione. Tra qualche minuto riceverai una mail contenente un URL che dovrai visitare per confermare la tua richiesta.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s81 = "Hai ricevuto questa email perchè tu o qualcuno per te ha cancellato la tua sottoscrizione alla lista $listeOpret a
http://$HTTP_HOST.
Prima che il tuo indirizzo si rimosso dalla nostra lista dobbiamo essere certi che tu, il proprietario di questo indirizzo, voglia essere rimosso dalla lista. Perciò ti chiediamo di visitare questo
URL per confermare la tua richiesta:

<[UNSUBSCRIBE_URL]>

Grazie.";

$s82 = "Il messaggio di cancellazione <b>deve</b> deve contenere la parola <i>[UNSUBSCRIBE_URL]</i>.";
$s83 = "The unsubscribe message -- il messaggio che sar&agrave inviato a chi vorr&agrave cancellarsi dalla lista.";
$s84 = "Sembra che l'indirizzo <i>$email</i> sia gi&agrave presente nella lista.";
$s85 = "Fatto! La mail &egrave stata inviata a tutti gli indirizzi presenti nella lista.";
$s86 = "Postlister sta inviando la mail numero";
$s87 = " ";
$s88 = "NON chiudete questa finestra! NON toccate nulla fino a che il programma avr&agrave inviato le mail rimanenti.";
$s89 = "L'indirizzo <i>$email</i> non &egrave presente nella lista. Quindi non pu&ograve essere cancellato.";
$s90 = "Non &egrave stato specificato nessun indirizzo email.";
$s91 = "You did not specify whether you want to subscribe or unsubscribe to the list.";
$s92 = "Non &egrave stato specificato nessun indirizzo email.";
$s93 = "Non &egrave stata specificata una mailing list.";
$s94 = "Non hai specificato l' ID corretto per l'indirizzo <i>$epost</i>.";
$s95 = "Fatto! Sei appena stato iscritto alla lista <i>$liste</i>.";
$s96 = "E' stata cancellata la tua sottoscrizione alla lista <i>$liste</i> , e non riceverai pi&ugrave mail da questa.";
$s97 = "di";
$s98 = "Importa gli indirizzi email";
$s99 = "Apri e importa";
$s100 = "Il file <i>$importfil</i> non &egrave stato trovato.";
$s101 = "Fatto! Tutti gli indirizzi nel file <i>$importfil</i> sono stati importati nella mailing list <i>$liste</i>.";
$s102 = "Se hai un file contenente una serie di indirizzi email, puoi importarli nella list <i>$liste</i>. Tuttavia è importante che contenga un solo indirizzo per riga, e che contenga esclusivamente indirizzi email. In altre parole, il formato del file deve essere qualcosa come questo:<p><i>mario.rossi@esempio.it<br>Mario Rossi &lt;joe.johnson@example.com&gt;<br>php@php.net</i>";
$s103 = "File:";
$s104 = "Ritorna alla pagina principale";
$s105 = "Import/export";
$s106 = "Esporta gli indirizzi email";
$s107 = "Esporta";
$s108 = "Usando questa funzione puoi esportare gli indirizzi nella lista <i>$liste</i>. Semplicemente, gli indirizzi saranno salvati in un file - uno per riga. Il nome del file sar&agrave <i>postlister-$liste.txt</i>, e sar&agrave slavato nella directory specificata. <b>E' molto importante che la directory nel quale il file sar&agrave salvato abbia le giuste permissions. Ci&ograve significa che dovrai cambiarle (chmod 777) con un  FTP client o SSH/telnet.</b>";
$s109 = "La directory nella quale vuoi salvare il file:";
$s110 = "<i>$eksport</i> non &egrave una directory. Devi specificare una directory nella quale vuoi salvare gli indirizzi email.";
$s111 = "Fatto! Tutti gli indirizzi nella mailing list <i>$liste</i> sono stati salvati nel file <i>$eksport/postliste-$liste.txt</i>.";
?>
