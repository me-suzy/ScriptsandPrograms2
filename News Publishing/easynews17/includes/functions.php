<?php

//-----------------------<EASYNEWS COMMON FUNCTIONS >---------------------------

/*******************************************************************************
	    						EMOTICONS ARRAY
*******************************************************************************/
$emoticons = array ( 'char' => array ( ':)' , ':D' , ':(' , ':S' , ':P' , ':angry:' , ':-O' , ';)' ),
                     'icon' => array ( '<img src="'.$emoPath.'smile.gif" alt="smilie for :)"  title=":)"  width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'bigsmile.gif" alt="smilie for :D"  title=":D"  width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'sad.gif" alt="smilie for :("  title=":("  width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'weird.gif" alt="smilie for :-S" title=":-S" width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'toung.gif" alt="smilie for :-P" title=":-P" width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'mad.gif" alt="smilie for 8o|" title="8o|" width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'amazed.gif" alt="smilie for :-O" title=":-O" width="16" height="16" style="border: 0;" />',
                                       '<img src="'.$emoPath.'blink.gif" alt="smilie for ;)"  title=";)"  width="16" height="16" style="border: 0;" />'
                                     ),
                     'meaning' => array ( 'happy' , 'very happy' , 'sad' , 'weird' , 'tongue sticking out' , 'mad' , 'amazed' , 'blink' ) );


/*******************************************************************************
**       sostituisce gli elementi con stesso indice fra 2 array               **
*******************************************************************************/
// sostituisce in $text tutte le occorrenze degli elementi dell'array $s con le
// analoghe (di pari indice) dell'array $d,
function doReplace( &$text, &$s, &$d ) {

        $textMod='';
        for ($i=0; $i<count($s); $i++) {
                if (!$i) $textMod = str_replace($s[$i], $d[$i], $text);
                else     $textMod = str_replace($s[$i], $d[$i], $textMod);

        }

        return $textMod;

}


/*******************************************************************************
**  		   riduce i testi delle news in base a $maxChar                   **
*******************************************************************************/
function stringCutter(&$str, $maxChar, $link ) {

	global $time,$page;

// se il numero max di caratteri è maggiore della lunghezza della stringa
// restituisce la stringa per intero
if (strlen($str)<=$maxChar) return $str;

// altrimenti la tronca...
do {

    // find last "white space" in truncated string
    $index = strrpos(substr( $str , 0, $maxChar ), ' ');
        if ($index === false) return $str;
        else $maxChar=$index;
// previene il troncamento in presenza di "<a href" , "<br />" e "<span style"
} while ( (substr($str, $index-3 , 3)=='<br') || (substr($str, $index-2 , 2)=='<a') || (substr($str, $index-5 , 5)=='<span') );

// return well cutted string
$vLink="$link [...continue]</a>";
return substr($str, 0, $index).$vLink;

}


?>
