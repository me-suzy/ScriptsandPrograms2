<?php

/*

  .: Poll4All 1.1 by Pierino :.
|===============================|
| http://www.code4fun.org       |
| mail01: info@code4fun.org     |
| mail02: sanculamo@hotmail.com |
|===============================|

*/

//----------------------------
/* START OR REPLACE SESSION */
session_start();


//                   _______________________________________                   |
//------------------< INCLUDE AND SETTING UP SOME VARIABLES >------------------|
//		     ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯                   |

include 'config.php';
include 'includes/functions.php';
require_once("includes/configmagik.php");

// txt/db file path
$txtPath = "txt/txtdb.ini.php";
// create and configure new ConfigMagik-Object
$dbTXT = new ConfigMagik( $txtPath, true, true);
$dbTXT->SYNCHRONIZE = false;
// Current script name
$currentFile= $_SERVER['SCRIPT_NAME'];


//------------------------------
/* ADMIN RESTRICTED AREA TEST */
//------------------------------------------------------------------------------
// update session variables with "user name" and "password"
if ( isset($_POST['id']) && isset($_POST['pw']) ) {
     $_SESSION['userid']=$_POST['id']; $_SESSION['password']=$_POST['pw'];
}

// check "user name" and "password"
if ($_SESSION['userid']==$id && $_SESSION['password']==$pw ) {

// reset cookie must be sent before any output...
if ($_GET['flag']=='cookie' || $_GET['flag']=='del' || $_GET['flag']=='reset') setcookie( "poll4all", "", time()+1, "/");

// print xhtml header
top();

// control panel header
stampa_header();

//------------------------------------------------------------------------------
//************************** START MAIN LOOP ***********************************
//------------------------------------------------------------------------------

switch ($_GET['flag']) {
        
/*----------------------------------------------------------------------------*/
/**************************** NEW/EDIT POLL  **********************************/
/*----------------------------------------------------------------------------*/

                case 'setup':
                
		      // create new poll
                      if ( isset($_POST['risposte']) )  {

                                $risposte=$_POST['risposte'];
                                // visualizza form per inserimento opzioni
                                stampa_form_risposte($risposte);
                      }
                      // edit poll
		      else {
		                $numRisposte = $dbTXT->get('numAnswers', 'MAIN');
				// visualizza form per editing opzioni
                                stampa_form_risposte($numRisposte, "edit");
		      }

                break; 

/*----------------------------------------------------------------------------*/
/****************************** CREATE POLL ***********************************/
/*----------------------------------------------------------------------------*/

                case 'create':

			/********************* ANSWERS ************************/
                        $array_risposte = $_POST['array_risposte'];
                        // text adjustment
                        for ($i=0; $i<count($array_risposte); $i++) rectifyText($array_risposte[$i]);

			/******************** QUESTION ************************/
			$question=trim($_POST['domanda']);
			// text adjustment
                        rectifyText($question);

			/****************** VALIDATE POLL *********************/
			if ($errorMsg=validatePoll($array_risposte,$question)) {

                             // SHOW ERRORS FOUND
                             echo "<div class=\"box\">
                                   <div class=\"titolo\">Error :</div>
				   $errorMsg
                                   </div>";

			     // $_POST['action'] è un hidden field del form stampa_form_risposte()
			     // che tiene traccia se si sta facnedo un inserimento o un update
                             stampa_form_risposte(count($array_risposte), $_POST['action']);
			}

                        // NO ERRORS FOUND: CREATE NEW POLL
                        else {
				// POLLID + UPDATE + RESET
				newPoll($array_risposte,$question);
				
	                        // salva su file
                                if ($dbTXT->save()) success("Poll created successfully!");
                        	else print "Error: write to file fail<br />";
                        }
                        	
                break;  // fine case 'create'


/*----------------------------------------------------------------------------*/
/****************************** UPDATE POLL ***********************************/
/*----------------------------------------------------------------------------*/
		case 'update':
		
		        /********************* ANSWERS ************************/
                        $array_answers = $_POST['array_risposte'];
                        // text adjustment
                        for ($i=0; $i<count($array_risposte); $i++) rectifyText($array_risposte[$i]);

			/******************** QUESTION ************************/
			$question = trim($_POST['domanda']);
                        // text adjustment
                        rectifyText($question);

			/****************** VALIDATE POLL *********************/
			if ($errorMsg=validatePoll($array_answers,$question)) {

                             // SHOW ERRORS FOUND
                             echo "<div class=\"box\">
                                   <div class=\"titolo\">Error :</div>
				   $errorMsg
                                   </div>";

			     // $_POST['action'] è un hidden field del form stampa_form_risposte()
			     // che tiene traccia se si sta facnedo un inserimento o un update
                             stampa_form_risposte(count($array_answers), "edit");
			}
			// NO ERRORS FOUND: UPDATE POLL
			else {
			        // update poll (no votes reset)
				updatePoll($array_answers, $question);
				
				// salva su file
                                if ($dbTXT->save()) success("Poll updated successfully!");
                                else print "Error: write to file fail<br />";
			}

		break;


/*----------------------------------------------------------------------------*/
/************************* SHOW POLL CONTROL PANEL ****************************/
/*----------------------------------------------------------------------------*/

                case 'cp':

                        // controllo l'esistenza della tabella
                        if($dbTXT->get('pollid','MAIN')!=null) {
                        
				print "<div align=\"center\"><p>";
		                // Visualizza il sondaggio allo stato attuale
                                pollPrint($dbTXT);
				print "</p></div>";
					  
				/******** SHOW EXTRA OPTIONS ********/
				extraMenu();
                        }
                        else {
                                // se la tabella non esiste...
                                echo "<div class=\"boxHeader\">
				      <div class=\"cBox\">No poll, click \"<a href='$currentFile?flag=wizard'>new poll</a>\" to create one.</div>
				      </div>";
                        }

                break; 
                
/*----------------------------------------------------------------------------*/
/**************************** ERASE CURRENT POLL ******************************/
/*----------------------------------------------------------------------------*/

                case 'del':

                        // query di eliminazione
                        $dbTXT->removeSection('MAIN');
                        $dbTXT->removeSection('ANSWERS');
                        if ($dbTXT->save()) header ("location:".$currentFile."?flag=cp");
			else print "Error: erase poll fail<br />";
                break;

/*----------------------------------------------------------------------------*/
/******************************** RESET POLL **********************************/
/*----------------------------------------------------------------------------*/

                case 'reset':

			    // reset votes to 0
			    resetVotes();
			    // salva su file
        		    if ($dbTXT->save())  header ("location:$currentFile?flag=cp");
                            else print "Error: write to file fail<br />";
                        
                break;

/*----------------------------------------------------------------------------*/
/***************************** NEW POLL WIZARD ********************************/
/*----------------------------------------------------------------------------*/

                case 'wizard':

                        echo "   
                                 <div class=\"box\">
                                <form name=\"poll\" method=\"post\" action=\"$currentfile?flag=setup\">
				     <div class=\"titolo\">.: New Poll Wizard [step 1/2]</div>
				     <div class=\"cBox\">
				     <span class=\"testo\">Select answers number :</span>&nbsp;<select name=\"risposte\">";
                                    for ($i=2; $i<8; $i++) print "<option value=$i>$i</option>";
                        echo "       </select>
				     &nbsp;&nbsp;
			             <input type=\"submit\" name=\"submit\" value=\"Go Ahead &gt;&gt;\">
				     </div>

                                 </form></div>";

                break;
                
                
/*----------------------------------------------------------------------------*/
/****************************** TEST NEW POLL *********************************/
/*----------------------------------------------------------------------------*/

		case 'test':
		        
		        print "<div align=\"center\"><p>";
		        include 'check.php';
			poll();
			print "</p></div>";
			
			/******** SHOW EXTRA OPTIONS ********/
                        extraMenu();

		break;
		
		
/*----------------------------------------------------------------------------*/
/******************************* RESET COOKIE *********************************/
/*----------------------------------------------------------------------------*/

		case 'cookie':

  			// redirect to test
  			//header ("location:$currentFile?flag=test");
  			
  			echo "
			      <div class=\"cBox\">
			      <p>
              		      <span class=\"testo\"><strong>&gt;&gt; Cookie Resetted &lt;&lt;</strong></span>
              		      </p>
              		      [ Now you can <a href='$currentFile?flag=test'>vote again</a> ]
			      </div>";

			/******** SHOW EXTRA OPTIONS ********/
			extraMenu();

		break;
		
		
/*----------------------------------------------------------------------------*/
/********************************* HELP ***************************************/
/*----------------------------------------------------------------------------*/

		case 'help':
				echo "  <div class=\"box\">
				        <div class=\"titolo\">.: helP</div>
				        <div class=\"testo\">
				        <strong>:: main menu</strong><br />
				        <ul>
					<li><strong>new poll :</strong> create new poll, old poll will be overwrited.</li>
					<li><strong>view/edit :</strong> view (and edit) current poll.</li>
					<li><strong>help :</strong> show this page.</li>
					<li><strong>? :</strong> show author contact info.</li>
					</ul>
					<strong>:: eXtra options menu</strong><br />
					<ul>
					<li><strong>edit :</strong> edit question and/or answers poll, votes are not affected.</li>
					<li><strong>reset votes :</strong> reset to \"0\" all votes.</li>
					<li><strong>delete poll :</strong> erase current poll from database file.</li>
					<li><strong>reset cookie :</strong> reset cookie on your browser so you can vote again.</li>
					<li><strong>test poll :</strong> try yourself current poll. Warning: your votes real affect the poll.</li>
					</ul>
					</div>
				        </div>";


		break;
		
		
/*----------------------------------------------------------------------------*/
/******************************** ABOUT ***************************************/
/*----------------------------------------------------------------------------*/

		case 'about':
				echo "  <div class=\"box\">
				        <div class=\"titolo\">.: aboUt</div>
				        <div class=\"testo\">
					Poll4All is freeware and open source.<br /><br />
					Visit mY hoMe paGe: <a href=\"http://www.code4fun.org\">http://www.code4fun.org</a><br /><br />
					ConTact mE : <a href=\"mailto:info@code4fun.org\">info@code4fun.org</a><br /><br />
					</div>
				        </div>";
		

		break;
		
/*----------------------------------------------------------------------------*/
/******************************* WELCOME **************************************/
/*----------------------------------------------------------------------------*/
		
		default:

			$msg="";

			// visualizza il form iniziale con menu ed istruzioni
			echo "<div class=\"box\">
	      		      <div class=\"titolo\">WELCOME</div>";
				$msg.="Check database file...";
				if (file_exists($txtPath)) {
				$msg.="Ok<br />";
				$msg.="Check if is writable...";
		                	if (is_writable($txtPath)) $msg.="Ok<br />ALL OK!";
                			else {
						$msg.="Ko<br />";
						$msg.="Try to change mode...";
			                	if (chmod($txtPath, 0600)) $msg.="Ok<br />ALL OK!";
	        		        	else $msg.="Ko<br />Non ho i permessi per scriverci, modificarli a mano.";

	        			}
				}
				else {
 	        			$msg.="File doesn't exist.<br />";
 	        			$msg.="Try to create it...";
	        			if (@fopen($txtPath, "x+")) $msg.="Ok<br />";
  					else $msg.="Ko<br />Impossibile creare il file, assicurarsi di avere i permessi per scrivere nella dir ".$txtPath.".";
				}

			print "<div class=\"testo\">$msg</div>";
			print "</div>";


}// fine switch
        
//------------------------------------------------------------------------------
//**************************** END MAIN LOOP ***********************************
//------------------------------------------------------------------------------


// xhtml footer
foot();

} // fine if controllo pw e id

//-------------------------------------------------------------------
/* if check user name and password fail print restricted area form */
else {restrictedArea();}







//----------------------------------------------------------------------------//
//                          F U N C T I O N S                                 //
//____________________________________________________________________________//





/****                       STAMPA_FORM_INIZIALE                           ****/

function stampa_header() {


                echo "  
                <div class=\"boxHeader\">

                <!-- titolo -->
                <div class=\"logo\"><a href=\"http://www.code4fun.org\">POLL4ALL</a><br /><span class=\"testo\">[v.1.1]</span></div>

                <!-- Menu -->
                <div class=\"menu\"><strong>.: MainMenu :</strong>&nbsp;&nbsp;<a href=\"$currentFile?flag=wizard\">new poll</a> | <a href=\"$currentFile?flag=cp\">view/edit current poll</a> | <a href=\"$currentFile?flag=help\">help</a> | <a href=\"$currentFile?flag=about\">?</a></div>

                </div>";


}


/*******************************************************************************
****                  STAMPA_FORM_DOMANDA_&_RISPOSTE                        ****
*******************************************************************************/
// $option = new  // i campi del form sono vuoti
// $option = edit // i campi del form sono inizializzati con i valori del sond. attuale

function stampa_form_risposte($num_risposte, $option="new") {

        global $currentFile,$dbTXT;

	// setting question value
        if ($option=="edit") {
		$questionValue = $dbTXT->get('question', 'MAIN');
		$flag='update';
		$title=".: Edit Current Poll";
	}
        else { $questionValue = ""; $flag='create'; $title=".: New Poll Wizard [step 2/2]";}
        
        echo "  <div class=\"box\">
                <form name=\"init_poll\" method=\"post\" action=\"$currentFile?flag=".$flag."\">
		<div class=\"titolo\">".$title."</div>
                <div class=\"answers\">Poll question :&nbsp;<input type=\"text\" size=\"61\" name=\"domanda\" value=\"".$questionValue."\"></div>
                <hr />";
                
                if ($option=="edit") print "<input type=\"hidden\" value=\"edit\" name=\"action\" />";

                // ciclo for per la visualizzazione delle caselle di testo, una per ogni risposta del sondaggio
                for ($i=1; $i<=$num_risposte; $i++) {

			// setting answer value
			$val="";
			if ($option=="edit") {
				$answerKey = "an".$i;
				$val = $answer = $dbTXT->get($answerKey,"ANSWERS");
			}
				

                        print "<div class=\"answers\">.: answers [".$i."] :&nbsp;<input type=\"text\" size=\"61\" name=\"array_risposte[]\" value=\"".$val."\"></div>";
                }
	// setting submit button value
	if ($option=="edit") $submitVal = ">> Update Poll <<";
	else $submitVal = ">> Create Poll <<";
        echo "  <div class=\"cBox\"><input type=\"submit\" name=\"submit\" value=\"".$submitVal."\" /></div>

                </form></div>";


}


/*******************************************************************************
****                VALIDAZIONE_DATI_SONDAGGIO_INSERITI                     ****
*******************************************************************************/
// in: array delle domande; out: string error message if any or 0 if all ok
function validatePoll(&$answersArray, &$question) {

			// Variabile Validazione
                        $numErrors = 0;
                        $msg = "";

                        // Controllo sul campo "domanda"
                        if (!isset($question) || $question=="" ) {
                             $numErrors++;
                             $msg.="Question can't be empty sentence!<br />";
                        }

                        // Controllo che non ci siano due risposte uguali...
                        $array_no_doppi = array_unique($answersArray);
                        if ( count($array_no_doppi)!=count($answersArray) ) {
                             $numErrors++;
                             $msg.="Warning!!! Two or more answers are the same!<br />";
                        }

                        // Controllo che non ci siano risposte nulle
                        if (array_search( '' , $answersArray)!==false) {
                             $numErrors++;
                             $msg.="Answers can't be empty sentences!<br />";
                        }
                        if (!$numErrors) return false;
                        else return $msg;
}


/*******************************************************************************
****                       ADATTA IL TESTO INSERITO                         ****
*******************************************************************************/
// converte alcuni caratteri html come "<" e ">" oltre che le virgolette ed
// i singoli apici per una corretta visualizzazione
function rectifyText(&$string) {
	
	$string = str_replace("\\'","'",$string);
	$string = str_replace('\"',"&quot;",$string);
	$string = str_replace("<", "&lt;", $string);
	$string = str_replace(">", "&gt;", $string);

}


/*******************************************************************************
****                            CREATE NEW POLL                             ****
*******************************************************************************/
function newPoll(&$answers, &$question) {

	global $currentFile,$dbTXT;
	
	/**************** NEW POLL ID *****************/
	$pollid=time();
        $dbTXT->set('pollid', $pollid, 'MAIN');

	/******** INSERIMENTO DOMANDA E RISPOSTE ******/
	updatePoll($answers,$question);
	/**************** RESET ALL VOTES *************/
	resetVotes();
	

}


/*******************************************************************************
****                            UPDATE POLL                                 ****
*******************************************************************************/
// aggiorna il sondaggio corrente senza resettare i voti registrati
function updatePoll(&$answers, &$question) {

	global $currentFile,$dbTXT;
	$oldNumAnswers = $dbTXT->get('numAnswers', 'MAIN');
	$numAnswers = count($answers);

	// update poll question
        $dbTXT->set('question', $question, 'MAIN');
        $dbTXT->set('numAnswers', $numAnswers, 'MAIN');
	// update answers
	for ($i=0; $i<$numAnswers; $i++) {

                // converto i caratteri "speciali"
                rectifyText($answers[$i]);
                $answerKey = "an".($i+1);
	        $dbTXT->set($answerKey, $answers[$i], 'ANSWERS');
        }
        // remove superfluous questions if exist
        // sarà sempre falsa se si sta aggiornando
        
        if ( ($oldNumAnswers - $numAnswers) > 0 ) {
		for ($i=($numAnswers+1); $i<=$oldNumAnswers; $i++) {
                        $answerKey = "an".$i;
			$pollKey = "an".$i."poll";
			$dbTXT->removeKey($answerKey, 'ANSWERS');
		    	$dbTXT->removeKey($pollKey, 'ANSWERS');
		}
        }
        
}


/*******************************************************************************
**                       POLL CREATION SUCCESSFULL                            **
*******************************************************************************/
function success($msg) {

	echo "<div class=\"box\">
	      <div class=\"cBox\">
              <span class=\"testo\"><strong>".$msg."</strong></span><br />
	      <p>[<a href='$currentFile?flag=test'> TEST POLL </a>]</p>
	      </div>";
}

/*******************************************************************************
**                             VOTES RESET                                    **
*******************************************************************************/
function resetVotes() {

	global $currentFile,$dbTXT;
        
        // azzero i voti
	$numAnswers = $dbTXT->get('numAnswers', 'MAIN');
	
	    for ($i=1; $i<=$numAnswers; $i++) {
	        $pollKey = "an".$i."poll";
	        $dbTXT->set($pollKey, 0, 'ANSWERS');
	    }
}



/*******************************************************************************
**                             SHOW EXTRA MENU                                **
*******************************************************************************/
function extraMenu() {

	
		
        echo "<p>
              <div class=\"extraMenu\">
              <strong>.: eXtra options :</strong>
              <span class=\"testo\">
              <a href='$currentFile?flag=setup&action=edit'>edit</a>&nbsp;|&nbsp;
              <a href='$currentFile?flag=reset'>reset votes</a>&nbsp;|&nbsp;
	      <a href='$currentFile?flag=del'>delete poll</a>&nbsp;|&nbsp;
              <a href='$currentFile?flag=cookie'>reset cookie</a>&nbsp;|&nbsp;
              <a href='$currentFile?flag=test'>test poll</a>&nbsp;
              </span>
              </div>
              </form>
	      </p>";
}




/*******************************************************************************
**             Visualizza Form per accesso alla Restricted Area               **
*******************************************************************************/
function restrictedArea() {

         echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
               <html xmlns=\"http://www.w3.org/1999/xhtml\">
                <head>
                 <title>Poll4All Restricted Area</title>
                 <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\" />
                 <style type=\"text/css\">
                 <!--
                 body {
                       background-color: #FFFFFF;
                       margin-top: 30px;
                       margin-right: 0px;
                       margin-bottom: 0px;
                       margin-left: 0px;
                       font-family: Verdana, Arial, Courier, Serif;



                 }

                 .container { margin:0px auto; text-align: center; width: 200px;}

                 .box {

                        background-color: #CCCCCC;
                        width: 200px;
                        text-align: center;
                        border-color: #000000;
                        border-style: solid;
                        border-width: 2px 2px 2px 2px;
                        padding: 10px 4px 4px 4px;
                        margin: 0px 0px 0px 0px;
                        font-family: Verdana, Arial, sans-serif;
                        font-size: 13px;
                 }
                 -->
                 </style>
                </head>
                <body>
                      <div align=\"center\">
                      <div class=\"container\">
                      <div class=\"box\">
                      <p><strong>Poll4All</strong><br />Restricted Area</p>
                      <form name=\"restricted\" action=\"$currentFile\" method=\"post\">
                      User Name: <input name=\"id\" type=\"text\" value=\"\" /><br />
                      Password: <input name=\"pw\" type=\"password\" value=\"\" />
                      <p><input type=\"submit\" value=\"submit\" /></p>
                      </form>
                      </div>
                      </div>
                      </div>
                </body>
                </html>
         ";
}


/*******************************************************************************
*****                           HEADER XHTML                               *****
*******************************************************************************/
function top() {

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
      <html xmlns=\"http://www.w3.org/1999/xhtml\">
       <head>
        <title>One-Poll - www.code4fun.org</title>
        <meta http-equiv=\"Content-Type\" content=\"text/html;charset=UTF-8\" />
        <style type=\"text/css\">
        <!--
        body { background:#F3F3F1; margin-top: 30px; font-family: Arial, Verdana; color: #000000; font-size: 12px; padding:0px;}
        .container { margin:0px auto; width: 500px;}
        A:LINK {text-decoration:none; color: #000000;}
        A:VISITED {text-decoration:none; color: #000000;}
        A:HOVER {text-decoration:none; color: #BB381A;}
        
        .boxHeader { padding: 0px 0px 0px 0px; width: 500px; border-style: dashed; border-width: 1px 1px 1px 1px;}
        .box       { padding: 20px 5px 5px 5px; width: 490px; border-style: solid; border-width: 0px 1px 1px 1px; }
	.cBox      { font-size: 12px; font-family: Arial,Verdana; padding: 10px 5px 10px 5px; text-align:center;}
	.logo      { background:#EFF0D8; font-size: 26px; font-style: bold; font-family: Verdana; text-align:center; padding: 10px 5px 10px 5px; border-style: dashed; border-width: 0px 0px 1px 0px;}
	.menu      { background:#EFF0D8; font-size: 12px; font-family: Verdana,Arial; text-align:left; padding: 5px 5px 5px 5px; }
        .titolo    { font-size: 12px; font-family: Verdana,Arial; font-weight: bold; text-align:left;  border-style: dashed; border-width: 0px 0px 1px 0px; padding : 0px 5px 2px 5px; margin-bottom: 10px;}
        .testo     { font-size: 12px; font-family: Verdana,Arial; text-align:left; padding: 5px 5px 5px 5px;}
	.answers   { padding: 5px 0px 5px 2px;}
        .extraMenu { background:#EFF0D8; font-size: 13px; font-family: Arial,Verdana; padding: 5px 5px 5px 5px; text-align:left; width: 490px; border-style: dashed; border-width: 1px 1px 1px 1px;}
	hr         { border: 0; width: 100%; height: 2px; color: #CCCCCC; background-color: #CCCCCC; }
        
        -->
        </style>
        
       </head>
        <body>
        <div class=\"container\">
        ";


}


/*******************************************************************************
*****                           FOOTER XHTML                               *****
*******************************************************************************/
function foot() { echo "</div></body></html>"; }




?>
