<?php
/**
 * This response returns a page showing the matrix.
 * Requires JavaScript; will hopefully work with many user agents.
 *
 *                     ;-)
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Matrix extends Response {

	function Matrix( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = "Matrix";
		$this->changeState('out/Html');

		$cols = $gConfig->mProp["EditColumns"] + 1; //make textarea one col wider
		$rows = $gConfig->mProp["EditRows"] - 1; //line breaks not in all lines

		echo <<<eoh
<script language="JavaScript1.4" type="text/javascript">
// A matrix implementation.
// @author Lars Ackermann
// @status Part of PWP Wiki Processor, licensed under GPL.

//speed definitions, symbolic names
var FAST = 2;
var WAIT = 1;
var SLOW = 0;

//arry size
var ROWS = {$gConfig->mProp["EditRows"]};
var COLS = {$gConfig->mProp["EditColumns"]};

//timeout in ms
var TIME_OUT = 140;

//initially filled lines (random!)
var INIT_LINES = 5;

//random range
var RND_RANGE = 300;

//probability for opening a new line; less than N within RND_RANGE
var OPEN_LINE = 12;

//probability for breaking a line; more than N within RND_RANGE
var BREAK_LINE = 19;

//Data containers
var gData  = new Array( ROWS );
var gSpeed = new Array( COLS );

//creates the data structure and pre-fills the first row
function init() {

	//define the data structure
	for (i=0; i<ROWS; i++) {
		gData[i] = new Array(COLS);
	}

	//populate the structure
	for (i=0; i<COLS; i++) {
		gSpeed[i]   = (Math.round( Math.random() ) == 1)?(SLOW):(FAST);
		entry       = Math.round( Math.random() * 94 * INIT_LINES );
		gData[0][i] = ((entry < 32) || (entry > 126))?(' '):(String.fromCharCode(entry));
	}

	//populate rest of array with spaces
	for (j=1; j<ROWS; j++) {
		for (i=0; i<COLS; i++) {
			gData[j][i] = ' ';
		}
	}
}

//transforms data field into string
function fillMyText() {
    var contents = '';
	for (j=0; j<ROWS; j++) {
		for (i=0; i<COLS; i++) {
			contents += gData[j][i];
		}
		if (j<$rows) {
			contents += "\\n";
		}
	}
	return contents;
}

//adds a row on top and removes the bottom row
function calculateNextRow( feedPos ) {
	var newChar  = '';
	var newValue = 0;
	var newLine  = new Array(COLS);
	var max      = 0;
	for (i=0; i<COLS; i++) {

		//update a line
		newValue = Math.round( Math.random() * RND_RANGE );
		if (i == FEED_LINE) {
			newChar  = FEED_TEXT.charAt( feedPos );
			feedPos = (feedPos == 0 )?(FEED_TEXT.length-1):(feedPos-1);
		} else if (gData[0][i] == ' ') {
			newChar  = (newValue < OPEN_LINE)?(String.fromCharCode(32 + Math.round(Math.random() * 94 ))):(' ');
		} else {
			newChar  = (newValue > BREAK_LINE)?(String.fromCharCode(32 + Math.round( Math.random() * 94 ))):(' ');
		}
		newLine[i] = newChar;

		//preserve a line with slow speed
		if ( gSpeed[i] == SLOW ) {
			gSpeed[i] = WAIT;
			newLine[i] = gData[0][i];

			max = ROWS-1;
			for (j=0; j<max; j++) {
				gData[j][i] = gData[j+1][i];
			}
		} else if ( gSpeed[i] == WAIT ) {
			gSpeed[i] = SLOW;
		}
	}
	gData.pop();
	gData.unshift( newLine );
	return feedPos;
}

//"main", fPos is the current position within the feed text
function run( fPos ) {
	Math.random(); //seed
	window.document.myform.mytext.value = fillMyText();
	fPos = calculateNextRow( fPos );
	window.setTimeout( 'run( ' + fPos + ' )', TIME_OUT );
}

var FEED_LINE = Math.round( Math.random() * (COLS-1) );
var FEED_TEXT = " ### LA ### --PWP-- ### - P W P - ###   15077B5 ";

</script>
<form name="myform">
	<textarea name="mytext" rows="{$gConfig->mProp["EditRows"]}" cols="$cols" wrap="off" style="background-color: #000000; color:#00aa00;" ></textarea>
</form>
<script language="JavaScript1.4" type="text/javascript">
init();
window.setTimeout( "run( 0 )", 2 * TIME_OUT );
</script>
eoh;
	}
}
?>