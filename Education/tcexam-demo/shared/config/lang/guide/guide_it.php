<?php
//============================================================+
// File name   : guide_it.php                                     
// Begin       : 2004-06-14                                    
// Last Update : 2005-07-06                                    
//                                                             
// Description : TCExam Guide                                  
//               Language module                               
//               (contains translated texts)                   
//                                                             
//                                                             
// Author: Nicola Asuni                                        
//                                                             
// (c) Copyright:                                              
//               Tecnick.com S.r.l.                            
//               Via Ugo Foscolo n.19                          
//               09045 Quartu Sant'Elena (CA)                  
//               ITALY                                         
//               www.tecnick.com                               
//               info@tecnick.com                              
//============================================================+

/**
 * TCExam Guide :: Language module (contains translated texts)
 * @author Nicola Asuni
 * @copyright Copyright © 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2004-06-14
 */

// ITALIANO - ITALIAN
?>

TCExam è un software per la creazione e gestione online di test (esami).<br />
<br />
Gli esami elettronici, o e-exam, sono esami che possono essere sostenuti attraverso l'ausilio di un personal computer o un dispositivo elettronico equivalente (es. computer palmare).<br />
<br />
L'utilizzo di sistemi e-exam al posto dei tradizionali test cartacei, semplifica notevolmente i processi di generazione, esecuzione, valutazione,  presentazione ed archiviazione degli esami. Questa semplificazione si traduce concretamente in un grande risparmio di tempo, in una maggior affidabilità dei test ed una significativa diminuzione degli errori di valutazione.<br />
<br />
<a name="index" id="index"></a>

<h2>Indice</h2><ul>
<li><a href="#features">Caratteristiche principali</a></li>
<li><a href="#structure">Struttura generale</a></li>
<li><a href="#install1">Requisiti minimi</a></li>
<li><a href="#install2">Configurazione dell'ambiente operativo</a></li>
<li><a href="#install3">Installazione TCExam</a><ul>
<li><a href="#install3_1">Installazione automatica</a></li>
<li><a href="#install3_2">Installazione manuale</a><ul>
<li><a href="#install3_2_1">Modifica dei file di configurazione</a></li>
<li><a href="#install3_2_2">Installazione del database</a></li></ul></li>
<li><a href="#install3_3">Post installazione</a></li></ul></li>
<li><a href="#install4">Configurazione del sistema</a></li>
<li><a href="#install5">Accesso e Sicurezza</a></li>
<li><a href="#use">Uso</a><ul>
<li><a href="#use_public">Area Pubblica</a></li>
<li><a href="#use_admin">Area di Amministrazione</a></li></ul></li></ul><a name="features" id="features"></a>

<h2>Caratteristiche principali</h2><ul>
<li>sistema di installazione automatica</li>
<li>software server-side indipendente dalla piattaforma</li>
<li>supporto multilingua (implementa lo standard <a href="http://www.lisa.org/tmx" target="_blank" title="Translation Memory eXchange [questo link apre una nuova finestra del browser]">TMX</a> e supporta l'UTF-8 Unicode)</li>
<li>basato su tecnologie standard ed open standard (<a href="http://www.php.net" target="_blank" title="www.php.net [questo link apre una nuova finestra del browser]">PHP5</a>, <a href="http://www.mysql.com" target="_blank" title="www.mysql.com [questo link apre una nuova finestra del browser]">MySQL</a>, <a href="http://www.postgresql.org" target="_blank" 
title="www.postgresql.org [questo link apre una nuova finestra del browser]">PostgreSQL</a>, <a href="http://www.w3.org/TR/xhtml1/" target="_blank" title="XHTML 1.0 The Extensible HyperText Markup Language [questo link apre una nuova finestra del browser]">XHTML</a>, <a href="http://developer.netscape.com/tech/javascript/index.html" target="_blank" title="JavaScript Developer Central [questo link apre una nuova finestra del browser]">JavaScript</a>, <a href="http://www.adobe.com/products/acrobat/adobepdf.html" target="_blank" title="Portable Document Format (PDF) [questo link apre una nuova finestra del browser]">PDF</a>)</li>
<li>include un Database Abstraction Layer con driver per <a href="http://www.mysql.com" target="_blank" 
title="www.mysql.com [questo link apre una nuova finestra del browser]">MySQL</a> e <a href="http://www.postgresql.org" target="_blank" 
title="www.postgresql.org [questo link apre una nuova finestra del browser]">PostgreSQL</a></li>
<li>interfaccia Web-based <a href="http://www.w3.org/WAI/" target="_blank" title="Web Accessibility Initiative (WAI)">accessibile</a> in grado di essere visualizzata sulla quasi totalità dei moderni browser internet</li>
<li>contiene un'area protetta di amministrazione da cui è possibile gestire l'intero sistema</li>
<li>supporta diversi livelli di accesso sia per gli utenti che per le risorse (pagine, maschere, sezioni)</li>
<li>contiene un sistema di sicurezza aggiuntivo per l'accesso ai test basato sul controllo degli indirizzi IP</li>
<li>supporta un numero illimitato di test, argomenti, quesiti e risposte alternative</li>
<li>supporta la formattazione del testo e l'inserimento di immagini, video e audio, nelle descrizioni dei test, dei quesiti e delle risposte</li>
<li>gli argomenti possono essere arbitrariamente raggruppati per essere oggetto d'esame in un test specifico</li>
<li>supporta sia domande a risposta multipla che a risposta aperta</li>
<li>contiene un sistema di calcolo automatico dei punteggi per le risposte multiple</li>
<li>contiene un sistema per la valutazione manuale delle risposte aperte</li>
<li>genera test unici per ogni utente selezionando casualmente le domande e le risposte alternative</li>
<li>supporta la limitazione temporale della validità e durata dei test</li>
<li>supporta la generazione di un numero arbitrario di test in formato PDF affinché possano essere stampati su carta ed impiegati in un esame tradizionale (senza computer)</li>
<li>genera report e statistiche sui test sia formato XHTML che PDF</li>
<li>consente la personalizzazione del formato e delle intestazioni dei documenti PDF</li>
<li>supporta la visualizzazione dei risultati del test all'utente</li>
<li>supporta l'invio dei risultati dei test via email</li>
</ul>[<a href="#topofdoc">indice</a>]<a name="structure" id="structure"></a>

<h2>Struttura generale</h2>Il sistema TCExam è essenzialmente formato da tre entità:<ul>
<li>Il <strong>Database</strong> dove i dati vengono archiviati in record e tabelle.</li>
<li>Il <strong>software TCExam</strong> sotto forma di file gerarchicamente organizzati in cartelle (directory) e sottocartelle.</li>
<li>Gli <strong>utenti</strong>, le persone che accedono al sistema, siano essi amministratori o semplici utenti.</li></ul><img src="../../images/tcexam_structure.png" alt="TCExam Structure" name="tcexamstruct" id="tcexamstruct" width="520" height="245" border="0" /><br /><br />Il database ed i file di sistema di TCExam possono essere localizzati su uno o più internet/intranet server, gli utenti possono accedere al sistema con una connessione internet/intranet ed un moderno Web-browser.<br /><br /><br />

<h2>File System</h2>Al fine di garantire una maggior sicurezza ed una migliore organizzazione logica, i file del software TCExam sono stati suddivisi in tre aree distinte:<br />

<h3>admin</h3>Contiene l'area di amministrazione del sistema.<br />L'accesso a quest'area è riservato agli amministratori.<br /><br /><table title="informazioni sottocartella admin" border="1" cellspacing="0" cellpadding="2">
<tr><th>cartella</th><th>descrizione</th></tr>
<tr><td>code</td><td>contiene i file di programma dell'area di amministrazione</td></tr>
<tr><td>config</td><td>contiene i file di configurazione per l'area di amministrazione</td></tr>
<tr><td>doc</td><td>contiene documentazione e licenze</td></tr>
<tr><td>log</td><td>in questa cartella verranno memorizzati i file di log relativi all'area di amministrazione</td></tr>
<tr><td>phpMyAdmin</td><td>contiene un'applicazione di terze parti per la gestione del database MySQL</td></tr>
<tr><td>phpPgAdmin</td><td>contiene un'applicazione di terze parti per la gestione del database PostgreSQL</td></tr>
<tr><td>styles</td><td>contiene i fogli di stile CSS per l'area di amministrazione</td></tr></table>

<h3>shared</h3>Contiene le risorse condivise utilizzate sia dal sistema di amministrazione (admin) che dal sito pubblico (public).<br /><br /><table title="informazioni sottocartella shared" border="1" cellspacing="0" cellpadding="2">
<tr><th>cartella</th><th>descrizione</th></tr>
<tr><td>barcode</td><td>contiene un'applicazione di terze parti per la generazione dei codici a barre</td></tr>
<tr><td>code</td><td>contiene i file di programma condivisi (principali funzioni di TCExam)</td></tr>
<tr><td>config</td><td>contiene i file di configurazione generali</td></tr>
<tr><td>jscripts</td><td>contiene programmi javascript condivisi</td></tr>
<tr><td>pdf</td><td>contiene una libreria di terze parti per la generazione di documenti PDF</td></tr>
<tr><td>phpmailer</td><td>contiene una libreria di terze parti per l'invio di email</td></tr>
</table>

<h3>public</h3>Contiene le risorse accessibili agli utenti generici.<br /><br /><table title="informazioni sottocartella public" border="1" cellspacing="0" cellpadding="2">
<tr><th>cartella</th><th>descrizione</th></tr>
<tr><td>code</td><td>contiene i file di programma specifici per l'area pubblica</td></tr>
<tr><td>config</td><td>contiene i file di configurazione dell'area pubblica</td></tr>
<tr><td>log</td><td>in questa cartella verranno memorizzati i file di log generati dall'area pubblica</td></tr>
<tr><td>styles</td><td>contiene i fogli di stile CSS per l'area pubblica</td></tr></table>

<h3>cartelle restanti</h3><table title="informazioni sottocartelle rimanenti" border="1" cellspacing="0" cellpadding="2">
<tr><th>cartella</th><th>descrizione</th></tr>
<tr><td>cache</td><td>qui verranno archiviati i file temporanei e le immagini inviate tramite l'apposito modulo di upload</td></tr>
<tr><td>fonts</td><td>contiene le definizioni dei font PDF</td></tr>
<tr><td>images</td><td>contiene le immagini del sistema</td></tr>
<tr><td>install</td><td>contiene i file di installazione di TCExam. Per ragioni di sicurezza si consiglia di rimuovere questa cartella al termine dell'installazione.</td></tr></table><br /><br />

<h2>Note</h2><ul>
<li>La struttura ed i nomi delle cartelle possono essere modificati in accordo con quanto specificato nel seguente file di configurazione: <em class="path">/shared/tce_paths.php</em>.<br /><br /></li></ul>[<a href="#topofdoc">indice</a>]<a name="install1" id="install1"></a>

<h2>Requisiti minimi</h2>Prima di procedere all'installazione di TCExam è necessario verificare i requisiti minimi del sistema:<ul><li>Un web server (es: Apache [<a href="http://httpd.apache.org/" target="_blank" title="questo link apre una nuova finestra del browser">http://httpd.apache.org/</a>], Microsoft<sup>®</sup> IIS [<a href="http://www.microsoft.com" target="_blank" title="questo link apre una nuova finestra del browser">http://www.microsoft.com</a>]).</li><li>PHP 5 (<a href="http://www.php.net" target="_blank" title="questo link apre una nuova finestra del browser">http://www.php.net</a>) - indispensabile</li><li>Le seguenti librerie:<ul><li>PHP GD Library 2.0.1 (<a href="http://www.boutell.com/gd" target="_blank" title="questo link apre una nuova finestra del browser">http://www.boutell.com/gd</a>) - necessaria per le immagini ed i grafici in true color<br />Questa libreria andrà installata con:<ul><li>libpng 1.2.2 - supporto immagini PNG</li><li>jpegsrc.v6b - supporto immagini JPEG</li></ul></li></ul></li><li>Uno dei seguenti database:
<ul>
<li>MySQL 4.1 (<a href="http://www.mysql.com" target="_blank" title="questo link apre una nuova finestra del browser">http://www.mysql.com</a>)
<ul><li>Controlla il link seguente in caso di problemi di autenticazione: <a href="http://dev.mysql.com/doc/mysql/en/Old_client.html" target="_blank" title="questo link apre una nuova finestra del browser">http://dev.mysql.com/doc/mysql/en/Old_client.html</a></li></ul></li>
<li>PostgreSQL 7.4 (<a href="http://www.postgresql.org" target="_blank" title="questo link apre una nuova finestra del browser">http://www.postgresql.org</a>)</li>
</ul>
</li><li><a href="http://www.zend.com/store/products/zend-optimizer.php" target="_blank" title="questo link apre una nuova finestra del browser">Zend Optimizer<sup>TM</sup></a> 2.5.7 per eseguire i file codificati (non richiesto per la versione non codificata).</li><li>Almeno 20MB per i file ed almeno 10MB per il database</li></ul>Per l'installazione e la configurazione del server web e delle librerie richieste consultate i rispettivi manuali.<br /><br />I client dovranno essere dotati di un comune Web-browser che supporti XHTML 1.0 e JavaScript 1.2.<br /><br />[<a href="#topofdoc">indice</a>]

<a name="install2" id="install2"></a><h2>Configurazione dell'ambiente operativo</h2>Per il corretto funzionamento di TCExam è necessario configurare il PHP in modo che supporti i sistemi e le librerie sopra indicate, è necessario inoltre che alcuni parametri di PHP siano impostati come segue:<br /><br />su <strong>php.ini</strong><ul>
<li>safe_mode = Off</li>
<li>arg_separator.output = "&amp;amp;"</li>
<li>magic_quotes_gpc = On</li>
<li>register_long_arrays = On</li>
</ul><br />oppure su modulo Apache (<strong>/etc/httpd/conf/httpd.conf</strong>):<br /><pre>
&lt;IfModule mod_php5.c&gt;
	php_admin_flag safe_mode off
	php_value arg_separator.output "&amp;amp;"
	php_value magic_quotes_gpc On
	php_flag register_long_arrays On
&lt;/IfModule&gt;</pre>Per la configurazione degli aspetti generali del PHP o per diverse modalità di configurazione, fate riferimento alla guida ed alle annotazioni ufficiali sul sito <a href="http://www.php.net" target="_blank" title="questo link apre una nuova finestra del browser">www.php.net</a>.<br /><br />Sarà inoltre necessario controllare le quote disco degli utenti che dovranno essere sufficienti a gestire i file ed il database.<br /><br /><strong>NOTA:</strong>Se utilizzate IIS su una versione di Microsoft® Windows non Server, utilizzate l'applicativo software di Microsoft <em>MetaEdit 2.2</em> per modificare il numero di massime connessioni contemporanee consentite ed i timeout di IIS:<ul><li>LM/W3SVC/MaxConnections 40</li><li>LM/W3SVC/CGITimeout 300</li><li>LM/W3SVC/ROOT/CGITimeout 300</li></ul>  
Nota: 300 rappresenta il numero di secondi in 5 minuti.<br /><br />[<a href="#topofdoc">indice</a>]

<a name="install3" id="install3"></a><h2>Installazione</h2>Verificate che l'ambiente operativo sia correttamente installato e configurato così come descritto nei paragrafi precedenti.<br /><br />Copiate tutto il contenuto della cartella TCExam nella web root del vostro server o in una sottocartella.<br />Se utilizzate un FTP per trasferire la versione encoded di TCExam, assicuratevi di impostare il trasferimento dei file in modalità binaria prima di inviare i file al server.<br /><br />A questo punto è possibile procedere in due modi:<br />

<a name="install3_1" id="install3_1"></a><h3>Installazione automatica</h3>Attraverso questo processo è possibile installare automaticamente il database e configurare i parametri di sistema principali.<br /><br />Modificate i permessi dei seguenti file e directory in modo tale che siano scrivibili dal software (chmod 666 su sistemi unix-like):<ul><li>install/</li><li>shared/config/tce_db_config.php</li><li>shared/config/tce_paths.php</li><li>admin/phpMyAdmin/config.inc.php</li><li>admin/phpPgAdmin/conf/config.inc.php</li></ul>Questi file potranno essere reimpostati su <em>sola lettura</em> al termine dell'installazione.<br />Nel caso in cui il programma di installazione non riuscisse a modificare questi file, sarà sempre possibile modificarli manualmente come descritto nel paragrafo successivo.<br /><br />Usando un comune web browser (programma di navigazione internet come Microsoft® Internet Explorer, Mozilla o Netscape®) collegatevi all'indirizzo in cui si trova il programma di installazione di TCExam: <strong>http://&lt;host&gt;/install/install.php</strong><br /><br />Se l'ambiente operativo è correttamente configurato dovreste vedere il modulo di installazione di TCExam.<br />
<br />
<img src="../../images/screenshots/ita/screenshot_install.png" alt="schermata: installazione" width="776" height="748" border="1" />
<br />
<br />Nota che:<br />Data la criticità di questo componente, il programma di installazione si presenta solo ed esclusivamente in lingua inglese.<br />Il processo di installazione eliminerà qualsiasi dato delle precedenti installazioni di TCExam, se ci si trova in questo caso sarà quindi opportuno fare delle copie (backup) dei dati.<br /><br />Per procedere all'installazione sarà necessario compilare opportunamente il modulo e premere il pulsante INSTALL.<br /><br />Di seguito l'elenco dei campi richiesti dal modulo di installazione:<ul><li><strong>db type</strong>: tipo di database utilizzato (il predefinito è <em>MySQL</em>)</li>
<li><strong>db host</strong>: nome dell'host del database (solitamente <em>localhost</em>)</li>
<li><strong>db port</strong>: porta database (solitamente <em>3306</em> per MySQL o <em>5432</em> per PostgreSQL)</li>
<li><strong>db user</strong>: nome dell'utente del database (solitamente è <em>root</em>)</li>
<li><strong>db password</strong>: password utente per l'accesso al database </li><li><strong>db name</strong>: nome del database (solitamente TCExam). Sarà necessario cambiare il nome solo nel caso in cui nello stesso sistema esistano altre copie di TCExam.</li><li><strong>tables prefix</strong>: prefisso da aggiungere al nome delle tabelle (solitamente <em>tce_</em>)</li><li><strong>host URL</strong>: il nome di dominio del vostro sito (ad es: <em>http://www.host.com</em>)</li><li><strong>relative URL</strong>: percorso a partire dalla root del webserver dove sono stati copiati i file di TCExam (solitamente / oppure /&lt;percorso ad TCExam&gt;/)</li><li><strong>TCExam path</strong>: percorso reale completo della directory dove è stato installato TCExam (ad esempio: <em>/usr/local/apache/htdocs/TCExam/</em> oppure <em>c:/Inetpub/wwwroot/TCExam/</em>)</li><li><strong>TCExam port</strong>: porta di connessione http predefinita (solitamente 80).</li></ul>Se l'installazione è andata a buon fine il sistema sarà pronto per la sua prima esecuzione.<br />A questo punto si potrà rimuovere la cartella <em>install</em> dal server e ripristinare i permessi di sola lettura nei file di configurazione.<br />In caso contrario si potrà completare o ripetere l'installazione usando la procedura manuale descritta di seguito.<br /><br />[<a href="#topofdoc">indice</a>]

<a name="install3_2" id="install3_2"></a><h3>Installazione manuale</h3>Per installare manualmente TCExam occorre modificare i file di configurazione ed installare il database.

<a name="install3_2_1" id="install3_2_1"></a><h4>Modifica file di configurazione</h4>I file e le costanti di configurazione essenziali per l'avvio di TCExam sono:<ul><li>shared/config/tce_db_config.php<ul><li>K_DATABASE_TYPE (tipo di database, solitamente <em>MYSQL</em> oppure <em>POSTGRESQL</em>)</li><li>K_DATABASE_HOST (nome dell'host del database, solitamente <em>localhost</em>)</li><li>K_DATABASE_NAME (nome del database, solitamente <em>TCExam</em>)</li><li>K_DATABASE_USER_NAME (nome dell'utente del database,  solitamente è <em>root</em>)</li><li>K_DATABASE_USER_PASSWORD (password per l'accesso al database)</li><li>K_TABLE_PREFIX (prefisso da aggiungere al nome delle tabelle, solitamente <em>tce_</em>)</li></ul></li><li>shared/config/tce_paths.php<ul><li>K_PATH_HOST (il nome di dominio del vostro sito ad es: <em>http://www.host.com</em>)</li><li>K_PATH_PHPMYEXAM (percorso a partire dalla root del webserver dove sono stati copiati i file di TCExam, solitamente / oppure /&lt;percorso a TCExam&gt;/)</li><li>K_PATH_MAIN (percorso reale completo della directory dove è stato installato TCExam, ad esempio: <em>/usr/local/apache/htdocs/TCExam/</em> oppure <em>c:/Inetpub/wwwroot/TCExam/</em>)</li><li>K_STANDARD_PORT (porta di comunicazione http, solitamente 80)</li></ul></li>

<li>admin/phpMyAdmin/config.inc.php
<ul><li>cfg['PmaAbsoluteUri'] (indirizzo internet completo dove si trova installato il programma phpMyAdmin, solitamente <em>http://&lt;host&gt;/admin/phpMyAdmin/</em>)</li><li>cfg['Servers'][$i]['host'] (nome dell'host del database MySQL, solitamente <em>localhost</em>)</li><li>cfg['Servers'][$i]['user'] (nome dell'utente del database MySQL, solitamente è <em>root</em>)</li><li>cfg['Servers'][$i]['password'] (password utente per l'accesso al database MySQL)</li>
</ul></li>
<li>admin/phpPgAdmin/conf/config.inc.php
<ul>
<li>$conf['servers'][0]['host'] (nome dell'host del database PostgreSQL, solitamente <em>localhost</em>)</li>
<li>$conf['servers'][0]['port'] (port del database sul server, solitamente 5432)</li>
</ul></li>
</ul>

<a name="install3_2_2" id="install3_2_2"></a><h4>Installazione del database</h4>All'interno della cartella <em>install</em> sono contenuti i file SQL che contengono lo schema ed i dati del database:
<ul>
<li>mysql_db_structure.sql - contiene la struttura del database MySQL</li>
<li>mysql_db_data.sql - contiene i dati del database MySQL</li>
<li>pgsql_db_structure.sql - contiene la struttura del database PostgreSQL</li>
<li>pgsql_db_data.sql - contiene i dati del database PostgreSQL</li>
</ul>

Se si desidera cambiare il prefisso delle tabelle sarà necessario usare un editor di testi che abbia una funzione <em>cerca e sostituisci</em> e operare le seguenti sostituzioni:<ul><li>Nel file ..._db_structure.sql sostituire <em>CREATE TABLE tce_</em> con <em>CREATE TABLE vostroprefisso</em></li><li>Nel file ..._db_data.sql sostituire <em>INSERT INTO tce_</em> con <em>INSERT INTO vostroprefisso</em></li></ul>
Per eseguire i file SQL potete usare i comandi di shell dei rispettivi database.<br />
Esempio MySQL:<pre>
mysql
mysql&gt; CREATE DATABASE TCExam;
shell&gt; mysql TCExam &lt; db_structure.sql
shell&gt; mysql TCExam &lt; db_data.sql
</pre>
Nell'esempio precedente si è supposto che il nome del database fosse <em>TCExam</em>.<br /><br />In alternativa (<em>se avete già configurato il config.inc.php</em>) potete usare l'utility <strong>http://&lt;host&gt;/admin/phpMyAdmin/index.php</strong> per MySQL o <strong>http://&lt;host&gt;/admin/phpPgAdmin/index.php</strong> per PostgreSQL con la quale potete creare il database ed eseguire i file SQL attraverso l'apposito comando.<br /><br />[<a href="#topofdoc">indice</a>]

<a name="install3_3" id="install3_3"></a><h3>Post installazione</h3>Una volta terminato il processo di installazione occorre:<ul><li>eliminare la cartella <em>install</em> che non è più necessaria e rappresenta un pericolo per la sicurezza dell'attuale installazione</li><li>impostare a sola lettura (chmod -R 644 su sistemi unix-like) i permessi dei file nelle cartelle:<ul><li>admin/config/</li><li>shared/config/</li><li>public/config/</li></ul></li><li>impostare opportunamente i permessi per quelle cartelle che dovranno essere accessibili in scrittura dal software TCExam (chmod -R 666):<ul><li>admin/log</li><li>cache</li><li>images</li><li>public/log</li></ul></li><li>impostare nel dettaglio i file di configurazione come indicato nel paragrafo successivo</li></ul>[<a href="#topofdoc">indice</a>]

<a name="install4" id="install4"></a><h2>Configurazione del sistema</h2>Terminata l'installazione automatica, TCExam dovrebbe essere in grado di sfruttare tutte le sue funzioni.<br />È possibile personalizzare alcune impostazioni e caratteristiche di base, modificando i seguenti file di configurazione:<ul>
<li>shared/config/tce_config.php - configurazione generale del sistema</li>
<li>shared/config/tce_db_config.php - configurazione database</li>
<li>shared/config/tce_extension.inc - estensione dei file usata dal sistema (.php)</li>
<li>shared/config/tce_general_constants.php - costanti di uso generale</li>
<li>shared/config/lang/ - file di lingua</li>
<li>shared/config/tce_paths.php - percorsi file e directory all'interno del sistema</li>
<li>shared/config/tce_pdf.php - configurazione del formato dei documenti PDF e delle intestazioni</li>
<li>shared/config/tce_email_config.php - configurazione relativa al sistema di generazione ed invio delle email</li>
<li>admin/config/tce_config.php - configurazione generale pannello di amministrazione</li>
<li>admin/config/tce_auth.php - impostazione livello di accesso ai moduli di amministrazione</li>
<li>public/config/tce_config.php - configurazione generale dell'area pubblica</li>
</ul>[<a href="#topofdoc">indice</a>]

<a name="install5" id="install5"></a><h2>Accesso e Sicurezza</h2>Una volta terminate le fasi di installazione e configurazione sopra descritte potete accedere al sistema collegandovi col browser all'indirizzo http://&lt;host&gt;/admin/code/index.php ed usando i seguenti dati:<ul><li>nome: admin</li><li>password: 1234</li></ul>
<br />
<img src="../../images/screenshots/ita/screenshot_login.png" alt="schermata: autenticazione utente (area di amministrazione)" width="776" height="354" border="1" />
<br />
Al fine di proteggere il vostro sistema e garantirvi un accesso esclusivo, ricordatevi di cambiare la password tramite il modulo <em>Utenti</em>. Sempre tramite questo modulo potete anche inserire i vostri dati e le vostre preferenze.<br /><br />Per ottenere un maggior livello di sicurezza si consiglia vivamente di proteggere l'intera cartella <em>admin</em> con un sistema di autenticazione utente web-based.<br />Uno dei modi più semplici e sicuri per proteggere una cartella su un server <a href="http://www.apache.org" target="_blank" title="questo link apre una nuova finestra del browser">Apache</a> consiste nell'utilizzare l'autenticazione Htaccess. Per maggiori informazioni consultate <a href="http://httpd.apache.org/docs/howto/htaccess.html" target="_blank" title="questo link apre una nuova finestra del browser">http://httpd.apache.org/docs/howto/htaccess.html</a>.<br />Se usate un diverso web server consultate la relativa documentazione.<br /><br />[<a href="#topofdoc">indice</a>]

<a name="use" id="use"></a>

<h2>Uso</h2>Così come evidenziato nella <a href="#structure">struttura generale</a>, TCExam presenta due distinte aree di interfaccia, <a href="#use_public">pubblica</a> e di <a href="#use_admin">amministrazione</a>, fisicamente separate nel filesystem del server:<br /><br />[<a href="#topofdoc">indice</a>]<a name="use_public" id="use_public"></a>

<h3>Area Pubblica</h3>È accessibile puntando il browser all'indirizzo <strong>http://&lt;host&gt;/admin/public/index.php</strong>.<br />Contiene le maschere e le interfacce per l'esecuzione dei test da parte degli utenti.<br /><br />Per poter accedere a quest'area, gli utenti dovranno autenticarsi inserendo nell'apposita maschera il nome e la password a loro assegnate.<br />
<br />
<img src="../../images/screenshots/ita/screenshot_login_public.png" alt="schermata: autenticazione utente (area pubblica)" width="776" height="247" border="1" />
<br />
<br />
Una volta autenticati, gli utenti visualizzeranno una schermata contenente l'elenco dei test che potranno eseguire ed eventualmente l'elenco dei test gia eseguiti. Questi ultimi verranno visualizzati solo se la costante <em>K_ENABLE_RESULTS_TO_USERS</em> è stata impostata a <em>true</em> nel file di configurazione <em>shared/config/tce_config.php</em>.<br />L'elenco dei test visualizzati dipende dai rispettivi intervalli di validità temporale, dall'indirizzo IP dell'utente e dal fatto di essere già stati eseguiti o meno.<br />
<br />
<img src="../../images/screenshots/ita/screenshot_test_list.png" alt="schermata: test attivi" width="776" height="260" border="1" />
<br />
<br />
L'elenco dei test attivi mostra, oltre al nome del test, una serie di collegamenti a seconda dei casi:<ul>
<li><strong>[info]</strong><br />Se cliccato, apre una finestra di popup contenente le informazioni dettagliate sul test corrispondente.<br />
<br />
<img src="../../images/screenshots/ita/screenshot_test_info.png" alt="schermata: informazioni sul test" width="700" height="300" border="1" />
<br />
<br /></li>
<li><strong>[esegui]</strong><br />Se cliccato, genera il test corrispondente per l'utente corrente ed apre la maschera di esecuzione del test.</li>
<li><strong>[continua]</strong><br />Compare in alternativa al collegamento [esegui], se cliccato, permette di continuare l'esecuzione del test il cui tempo non è ancora scaduto.</li>
<li><strong>[risultato]</strong><br />Compare quando il test è stato già eseguito e solo se la costante <em>K_ENABLE_RESULTS_TO_USERS</em> è stata impostata a <em>true</em> nel file di configurazione <em>shared/config/tce_config.php</em>. Se cliccato, apre una maschera per la visualizzazione dei risultati del test (la valutazione si riferisce solo alle domande a risposta multipla). Nota che la chiamata a questa maschera blocca il test per evitarne la prosecuzione.</li></ul>

<h4>Esecuzione del test</h4>La maschera per l'esecuzione del test è composta da diversi elementi:<ul>
<li>Un orologio che informa sull'ora corrente.</li>
<li>Un countdown (conto alla rovescia) che informa sul tempo rimanente allo scadere del test.</li>
<li>Un menù per selezionare le domande e per visualizzarne lo stato (completate, non completate).</li>
<li>Un collegamento sul nome del test che, se cliccato, apre una finestra di popup contenente le informazioni dettagliate sul test in esecuzione.</li>
<li>Il quesito della domanda che può contenere anche formattazione ed immagini.</li>
<li>Un'area per le risposte che a seconda dei casi può essere:<ul>
<li>Un elenco di risposte alternative disposte casualmente di cui una giusta, una relativa alla non-risposta (predefinito) e le altre sbagliate. Ogni modifica della risposta provoca un aggiornamento della pagina con invio dei dati del form al server.</li>
<li>Un campo nel quale è possibile inserire un testo arbitrario. Una volta inserito il testo sarà necessario premere il tasto [aggiorna] o passare ad un'altra domanda.</li></ul></li>
<li>Una barra di stato che visualizza il nome dell'utente corrente ed un collegamento per l'uscita dal sistema (logout).</li></ul>
<br />
<img src="../../images/screenshots/ita/screenshot_test_execute.png" alt="schermata: esecuzione test" width="776" height="552" border="1" />
<br />
<br />
L'utente potrà modificare le risposte senza limitazioni per tutta la durata del test.
Non sarà necessario dare una conferma di conclusione del test che rimarrà quindi operativo fino allo scadere del tempo.<br /><br />[<a href="#topofdoc">indice</a>]<a name="use_admin" id="use_admin"></a>

<h3>Area di Amministrazione</h3>È accessibile puntando il browser all'indirizzo <strong>http://&lt;host&gt;/admin/code/index.php</strong>.<br />Contiene le maschere e le interfacce per la gestione dell'intero sistema, compresa la gestione degli utenti e del database, la generazione dei test e dei risultati.<br /><br />Tutte le maschere di quest'area sono caratterizzate da alcuni elementi comuni:<ul>
<li>Un orologio che informa sull'ora corrente.</li>
<li>Un menù di navigazione flottante.</li>
<li>Un'area di informazioni sulla maschera corrente.</li>
<li>Una barra di stato contenente il nome dell'utente corrente ed un collegamento per l'uscita dal sistema (logout).</li></ul>

<h4>Maschere</h4><ul>
<li><strong>Gestione Utenti</strong><br />Attraverso questa maschera è possibile inserire, modificare o eliminare gli utenti che hanno accesso al sistema. Per ogni utente è possibile scegliere un nome, una password ed un livello. Il livello 0 indica un utente anonimo (non registrato), il livello 1 indica un semplice utente (es: studente che deve sostenere un test), il livello 10 indica un amministratore che ha accesso a tutte le funzioni del sistema.<br />Il livello di accesso delle risorse dell'area di amministrazione è definito nel file di configurazione <em>admin/config/tce_auth.php</em>.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_user_edit.png" alt="schermata: gestione utenti" width="776" height="612" border="1" />
<br />
<br /></li>
<li><strong>Selezione Utenti</strong><br />Questa maschera consente di visualizzare e selezionare gli utenti registrati.<br />È possibile ordinare gli utenti secondo vari criteri cliccando sui nomi delle colonne.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_user_select.png" alt="schermata: selezione utenti" width="776" height="354" border="1" />
<br />
<br /></li>
<li><strong>Utenti Online</strong><br />Questa maschera mostra gli utenti collegati (o quelli a cui non è ancora scaduta la sessione).
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_user_online.png" alt="schermata: utenti online" width="776" height="354" border="1" />
<br />
<br /></li>
<li><strong>Gestione Argomenti</strong><br />Attraverso questa maschera è possibile inserire, modificare o eliminare gli argomenti d'esame. Tali argomenti costituiranno le categorie di raggruppamento delle domande presenti nel sistema.<br />Un esame può essere costituito da un insieme arbitrario di argomenti.<br />Non è possibile modificare o eliminare un argomento se questo è presente tra i test eseguiti, in tal caso potrà essere solo disabilitato utilizzando sempre il pulsante [elimina].<br />
Il pulsante [modifica] apre una maschera popup che consente di inserire immagini e formattazione nella descrizione.<br />
Il link [elenco] apre una maschera di selezione contenente la lista di tutte le domande e risposte relative all'argomento selezionato.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_subject.png" alt="schermata: gestione argomenti" width="776" height="449" border="1" />
<br />
<br /></li>
<li><strong>Lista Domande</strong><br />
Questa maschera mostra l'elenco completo delle domande e delle risposte relative all'argomento selezionato.<br />
I link [modifica] aprono la maschera di modifica per l'elemento corrispondente.<br />
Premendo il pulsante [PDF] si ottiene la corrispondente versione PDF dei dati visualizzati.<br />
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_questions_list.png" alt="screenshot: questions list" width="776" height="720" border="1" />
<br />
<br /></li>
<li><strong>Gestione Domande</strong><br />Attraverso questa maschera è possibile inserire, modificare o eliminare le domande relative all'argomento selezionato.<br />Le domande potranno essere a risposta multipla o libera attraverso un campo di testo.<br />Non è possibile modificare o eliminare una domanda se questa è presente tra i test eseguiti, in tal caso potrà essere solo disabilitata utilizzando sempre il pulsante [elimina].<br />Il pulsante [modifica] apre una maschera popup che consente di inserire immagini e formattazione nella descrizione.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_question.png" alt="schermata: gestione domande" width="776" height="491" border="1" />
<br />
<br /></li>
<li><strong>Gestione Risposte Multiple</strong><br />Attraverso questa maschera è possibile inserire, modificare o eliminare le risposte possibili per la domanda selezionata.<br />Ad ogni domanda è possibile associare un numero arbitrario di risposte esatte e sbagliate, durante i test il sistema provvederà automaticamente a scegliere un'unica risposta esatta da inserire nell'insieme di possibili risposte.<br />Non è possibile modificare o eliminare una risposta se questa è presente tra i test eseguiti, in tal caso potrà essere solo disabilitata utilizzando sempre il pulsante [elimina].<br />Il pulsante [modifica] apre una maschera popup che consente di inserire immagini e formattazione nella descrizione.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_answer.png" alt="schermata: gestione risposte multiple" width="776" height="485" border="1" />
<br />
<br /></li>
<li><strong>Gestione Test</strong><br />Attraverso questa maschera è possibile inserire, modificare o eliminare i test.<br />Prima di inserire un test è necessario aver precedentemente inserito degli argomenti con un numero adeguato di relative domande e risposte.<br />I test sono accessibili solo agli utenti abilitati e con un indirizzo IP valido. Il campo IP può contenere una lista separata da virgola degli indirizzi IP abilitati ad eseguire il test. Un indirizzo IP può anche contenere wildcards (* = qualsiasi numero).<br />Il pulsante [modifica] apre una maschera popup che consente di inserire immagini e formattazione nella descrizione.<br />Il test sarà attivo solo durante l'intervallo temporale specificato e, una volta generato, dovrà essere completato entro la durata massima.<br />I test possono essere composti da più argomenti.<br />È possibile scegliere il numero massimo di domande e di risposte per domanda che verranno estratte casualmente tra tutte quelle presenti. È inoltre possibile definire un punteggio per ogni tipo di risposta (giusta, sbagliata, non data).<br />Non è possibile modificare un test se questo è stato già eseguito. L'eliminazione di un test comporterà l'eliminazione di tutti i log relativi (dati dei test effettuati).<br />Il pulsante [genera] consente la generazione di un numero arbitrario (specificato al lato) di test unici in formato PDF che possono essere stampati ed utilizzati per un esame tradizionale (senza computer).
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_test.png" alt="schermata: gestione test" width="776" height="887" border="1" />
<br />
<br /></li>
<li><strong>Valutazione Risposte Aperte</strong><br />Attraverso questa maschera è possibile assegnare un punteggio alle risposte aperte.<br />La correzione è anonima in maniera predefinita ma è anche possibile visualizzare i dati dell'utente selezionando il campo '<em>dati utente</em>'.<br />Il campo '<em>mostra tutti</em>' consente di visualizzare e correggere le risposte che sono state gia valutate.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_rating.png" alt="schermata:valutazione risposte" width="776" height="491" border="1" />
<br />
<br /></li>
<li><strong>Riepilogo Risultati Test</strong><br />Questa maschera mostra il riepilogo dei risultati degli utenti per il test selezionato.<br />Cliccando sui nomi di alcune colonne è possibile cambiare l'ordinamento della tabella.<br />I dati tra parentesi mostrano il dettaglio delle riposte multiple e le risposte testuali.<br /> Cliccando sul numero di riga si ottiene il dettaglio del compito relativo.<br />Premendo il pulsante [PDF] si ottiene la corrispondente versione PDF dei dati visualizzati.<br />
Premendo il pulsante [PDF compiti] si ottiene un documento PDF contenente tutti i dettagli dei singoli compiti.<br />
Premendo il pulsante [invia email] verrà inviata ad ogni utente che ha eseguito il test un'email contenente una copia del proprio esame in formato PDF.<br />
<br />
<img src="../../images/screenshots/ita/screenshot_results.png" alt="schermata: riepilogo risultati test" width="776" height="403" border="1" />
<br />
<br /></li>
<li><strong>Risultati Test</strong><br />Questa maschera mostra i dettagli del test per l'utente selezionato.<br />I dati su ogni riga di dettaglio corrispondono a:<br /><em>numero domanda. [voto] (IP utente | tempo di visualizzazione in hh:mm:ss| tempo di ultima modifica in hh:mm:ss| tempo impiegato a rispondere in mm:ss)</em><br />Il simbolo ® è posto in corrispondenza delle risposte esatte, mentre le risposte date dall'utente sono indicate con una 'x'.<br />Premendo il pulsante [PDF] si ottiene la corrispondente versione PDF dei dati visualizzati.<br />
Premendo il pulsante [invia email] verrà inviata all'utente un'email contenente una copia del proprio esame in formato PDF.<br />
<br />
<img src="../../images/screenshots/ita/screenshot_result_user.png" alt="schermata: risultati test" width="776" height="853" border="1" />
<br />
<br /></li>
<li><strong>Statistiche sui Quesiti</strong><br />Questa maschera mostra le statistiche sui quesiti per il test selezionato.<br />Cliccando sui nomi di alcune colonne è possibile cambiare l'ordinamento della tabella.<br />Cliccando sul numero di riga si passa alla pagina di modifica per il quesito selezionato.<br />Premendo il pulsante [PDF] si ottiene la corrispondente versione PDF dei dati visualizzati.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_stats.png" alt="schermata: statistiche sui quesiti" width="776" height="680" border="1" />
<br />
<br /></li>

<li><strong>Editor mark-up</strong><br />
Questa maschera viene visualizzata su una finestra di popup che si apre premendo il pulsante [modifica] associato ai campi descrizione presenti in alcune delle maschere precedenti. Questa maschera consente la formattazione del testo e l'inserimento di immagini utilizzando un apposito linguaggio di mark-up.<br />
Gli elementi di mark-up (tag) sono costituiti da nomi minuscoli racchiusi tra parentesi quadre. I tag di chiusura si differenziano da quelli di apertura per la presenza del carattere slash (/) prima del nome.<br />
Per applicare un determinato stile o effetto ad una porzione di testo, occorre racchiudere quest'ultima tra un tag di apertura ed uno di chiusura.<br />
Nell'editor è presente un select-box per l'inserimento rapido dei tag, un select-box per l'inserimento di oggetti già presenti nel server (immagini, video, audio) ed un campo per l'upload (invio) nel server di file.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_markup.png" alt="schermata: editor mark-up" width="574" height="436" border="1" />
<br />
<br />
Premendo il pulsante [anteprima] è possibile visualizzare l'anteprima del testo formattato in un'apposita finestra di popup.
<br />
<br />
<img src="../../images/screenshots/ita/screenshot_markup_preview.png" alt="schermata: anteprima editor mark-up" width="500" height="650" border="1" />
<br />
<br />
</li>

</ul><br /><br />[<a href="#topofdoc">indice</a>]<br /><br />NOTA: I documenti PDF generati dal sistema supportano solo immagini png o jpg non interlacciate e senza canali alpha.
<?php
//============================================================+
// END OF FILE                                                 
//============================================================+
?>
