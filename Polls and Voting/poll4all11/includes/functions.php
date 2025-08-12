<?php


/*******************************************************************************
****                         MAIN POLL FUNCTIONS                            ****
*******************************************************************************/
function poll($path="txt/txtdb.ini.php", $img="images/") {


    // Create and configure new ConfigMagik-Object
    $dbTXT = new ConfigMagik( $path, true, true);
    $dbTXT->SYNCHRONIZE = false;

    // if poll exist...
    if($dbTXT->get('pollid','MAIN')!=null) {

	// if someone voted by the poll form -> show poll result
	if ( (isset($_POST['voto'])) && ($_POST['voto']!="") ) pollPrint($dbTXT, $img);
	// else show poll form
	else pollForm($dbTXT);
        
    }
    // if poll doesn't exist...
    else print "<div style=\"text-align: center;\">- No Poll Available -</div>";

}

/*******************************************************************************
****                    VISUALIZZA RISULTATI SONDAGGIO                      ****
*******************************************************************************/

function pollPrint(&$dbTXT, $imgPath="images/") {

                global $border,$width,$bgColor,$percentageBg,
                       $cellpadding,$questionAlign,$optionsAlign,
                       $questionColor,$optionColor,$questionBgColor,
                       $font,$answerSize,$RandomColors,$questionSize,$barBg;

		// inizializzo l'array contenente i numeri corrispondenti ai colori per gli istogrammi
                $colors = array(1,2,3,4,5,6,7);
                if ($RandomColors) shuffle($colors);
                
                $numAnswers = $dbTXT->get('numAnswers', 'MAIN');
		$question = $dbTXT->get('question', 'MAIN');
		
		$totVoti = votiTotali($dbTXT);
		
		// valori di cellpadding >0 influiscono sulla lunghezza dei contenitori div
		$width=$width-($cellpadding*2);

                echo "<!-- Poll Box -->
		      <div style=\"width: ".$width."px; background-color: ".$bgColor."; border: ".$border."px solid #999999; padding: ".$cellpadding."px ".$cellpadding."px ".$cellpadding."px ".$cellpadding."px;\">
		      <!-- QUESTION -->
		      <div style=\"text-align: ".$questionAlign.";  background-color: ".$questionBgColor."; font-family: ".$font.",Arial; font-size: ".$questionSize."px; color: ".$questionColor."; padding: 5px 5px 5px 5px;\">$question</div>
		      <!-- Separator -->
		      <div style=\"width: ".$width."px; height: 5px;\">&nbsp;</div>";
                      
                // visualizza voti
                for ($i=0; $i<$numAnswers; $i++) {
                
                        $answerKey = "an".($i+1);
			$pollKey = $answerKey."poll";
			
			$answer = $dbTXT->get($answerKey,"ANSWERS");
			$poll = $dbTXT->get($pollKey,"ANSWERS");

                        // calcolo la percentuale evitando la divisione per zero
                        if ($poll!=0) {$percentuale=(($poll*100)/$totVoti);}
                        else $percentuale=0;
                        
                        if ($percentuale) {
                                $w = round((($width*0.01)*$percentuale));
				$barra = "<img src=\"".$imgPath."/".$colors[$i].".gif\" width=\"$w\" height=\"8\" alt=\"bar\" />";
			}
                        else $barra = "<img src=\"".$imgPath."0.gif\" width=\"8\" height=\"8\" alt=\"no result\" />";

                        echo "<!-- Text Answer -->
			      <div style=\"text-align:".$optionsAlign."; background-color: ".$percentageBg."; font-family: ".$font.",Arial; font-size: ".$answerSize."px; color: ".$optionColor.";\">$answer: $poll votes (".round($percentuale,1)."%)</div>
			      <!-- Bar -->
			      <div style=\"text-align: left; background-color: ".$barBg.";\">$barra</div>
			      <!-- Separator -->
			      <div style=\"width: ".$width."px; height: 5px;\">&nbsp;</div>";
                }
                // toal votes
                echo "<div style=\"text-align: center; font-size: ".$answerSize."px;\">total votes: $totVoti</div></div>";
}


/*******************************************************************************
****                  VISUALIZZA IL FORM PER VOTARE                         ****
*******************************************************************************/

function pollForm(&$dbTXT) {

	global $border,$width,$cellpadding,$questionAlign,$questionBgColor,
	       $font,$answerSize,$optionColor,$optionsAlign,$bgColor,$oddBgColor,
	       $evenBgColor,$questionSize,$questionColor;

        $currentFile= $_SERVER["SCRIPT_NAME"];
        if ( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!="" ) $query="?".$_SERVER['QUERY_STRING'];

        $numAnswers = $dbTXT->get('numAnswers', 'MAIN');
	$question = $dbTXT->get('question', 'MAIN');
	$totVoti = votiTotali($dbTXT);
	
       if ( isset($_COOKIE['poll4all']) && $_COOKIE['poll4all']==0 ) print "<div style=\"text-align: center;\">- Cookie resetted -</div>";

        echo "<!-- Poll Form Table -->
	      <form name=\"sondaggio\" method=\"post\" action=\"".$currentFile.$query."\">
              <table style=\"border: ".$border."px solid #000000; width: ".$width."px; padding: ".$cellpadding."px ".$cellpadding."px ".$cellpadding."px ".$cellpadding."px; background-color: ".$bgColor.";\">
	      <!-- QUESTION -->
	      <tr><td colspan=\"2\" style=\"text-align: ".$questionAlign."; background-color: ".$questionBgColor."; font-family: ".$font.",Arial; font-size: ".$questionSize."px; color: ".$questionColor.";\">$question</td></tr>";

        for ($i=1; $i<=$numAnswers; $i++) {
		$anKey = "an".$i;
		$answer = $dbTXT->get($anKey, 'ANSWERS');
                if ( $i&1 ) $bgColor = $oddBgColor;
                else        $bgColor = $evenBgColor;

        echo  "<!-- Answers -->
	       <tr><td style=\"width: ".($width-10)."px; text-align: ".$optionsAlign."; border-style: solid; border-width: 0px; margin: 0px 0px 0px 0px; font-size: ".$answerSize."px; background-color: ".$bgColor.";\">$answer</td>
	       <td style=\"width: 10px; border-style: solid; border-width: 0px; margin: 0px 0px 0px 0px; background-color: ".$bgColor.";\"><input type=\"radio\" name=\"voto\" value=\"$i\" /></td>
	       </tr>";
        }
              
        echo "<!-- Submit Button -->
	      <tr><td colspan=\"2\" style=\"text-align: center; width: ".$width."px; padding: 5px 5px 0px 5px;\">
	      <input type=\"submit\" name=\"submit\" value=\">> cast my vote <<\" /></td>
	      </tr></table></form>";
}


/*******************************************************************************
*******                  RITORNA I VOTI TOTALI 	                          ******
*******************************************************************************/
function votiTotali(&$dbFile) {

	$tot=0;
	$numAnswers = $dbFile->get('numAnswers', 'MAIN');

	for ($i=1; $i<=$numAnswers; $i++) {
		$pollKey = "an".$i."poll";
		$tot+=$dbFile->get($pollKey,"ANSWERS");
	}

	return $tot;
}




?>
