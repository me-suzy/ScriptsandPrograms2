<?

////////////////////////////////////////////////////////////
//
// spiderPoll v1.1 - a voting poll
//
////////////////////////////////////////////////////////////
//
// This script adds votes to a poll and displays the poll.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 02/17/2003
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////



// DEFINE THE VARIABLES //
// title of this poll
$title = "What is your favorite programming language?";
// closing date for this poll in MM/DD/YYYY format
$closingDate = "1/1/2010";
// text file that stores vote choices and totals
$pollFile = "poll.txt";
// text file that stores IP addresses
$ipFile = "ips.txt";
// full path to your CSS style sheet
$styleSheet = "http://yoursite/style.css"; // leave blank if you aren't using this

// DO NOT EDIT BELOW THIS POINT UNLESS YOU KNOW PHP! //

// DEFINE THE FUNCTIONS //
// check if the poll has closed
function is_closed() {
	global $closingDate;

	// split the closing date into month, day, and year
	$closingDate = explode("/", $closingDate);

	// get today's today to test against the closing date
	$today = getdate();

	$message = date("l, F j", mktime(0,0,0,$closingDate[0],$closingDate[1],$today[year]));

	// if today's year is greater than the closing year, return true
	if ($today[year] > $closingDate[2]) {
		return $message;
	}
	// if today's year is equal to the closing year
	elseif ($today[year] == $closingDate[2]) {
		// if today's month is greater than the closing month, return true
		if ($today[mon] > $closingDate[0]) {
			return $message;
		}
		// if today's month is equal to the closing month
		elseif ($today[mon] == $closingDate[0]) {
			// if today is greater than or equal to the closing day, return true
			if ($today[mday] >= $closingDate[1]) {
				return $message;
			}
			// if the poll is still open, return false
			else {
				return false;
			}
		}
		// if the poll is still open, return false
		else {
			return false;
		}
	}
	// if the poll is still open, return false
	else {
		return false;
	}
}

// check if the user has already voted
function has_voted() {
	global $ipFile;
	global $REMOTE_ADDR;

	// open the IP address file
	$ips = fopen($ipFile, "r");

	// compare each entry with the user's IP address
	while (!feof($ips)) {
		$ip = fgets($ips, 20);

		if ($ip == $REMOTE_ADDR . "\r\n") {
			$match = 1;
			break;
		}
	}

	// close the IP address file
	fclose($ips);

	if (!$match) {
		// reopen the IP address file
		$ips = fopen($ipFile, "a");

		// add the user's IP address
		fputs($ips, $REMOTE_ADDR . "\r\n");

		// close the IP address file
		fclose($ips);

		return false;
	}
	else {
		return true;
	}
}

// add the user's vote
function addVote($vote) {
	global $pollFile;

	// get the current votes
	$fp_read = fopen($pollFile, "r");
	$currentVote = fread($fp_read, filesize($pollFile));
	fclose($fp_read);

	// create an array with even numbers containing vote choices
	// and odds containing vote totals
	$votes = split('[|:]', $currentVote);

	// update the vote
	for ($i = 1; $i < count($votes); $i = $i + 2) {
		// get the array index number for the name of this vote
		$name = $i - 1;

		// if this vote choice is this user's selection, increment it
		if ($votes[$name] == $vote) {
			$votes[$i]++;
		}

		// if this vote IS the last choice
		if ($i == (count($votes) - 1)) {
			$updatedVote .= $votes[$name] . ":" . $votes[$i];
		}

		// if this vote is NOT the last choice
		else {
			$updatedVote .= $votes[$name] . ":" . $votes[$i] . "|";
		}
	}

	// save the updated vote
	$fp_write = fopen($pollFile, "w");
	fputs($fp_write, $updatedVote);
	fclose($fp_write);
}

// display the poll
function displayPoll($message) {
	global $title, $pollFile, $styleSheet;

	echo "<html>\n";
	echo "<head>\n";
	echo "<title>$title</title>\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$styleSheet\">\n";
	echo "</head>\n\n";
	echo "<body>\n";

	// get the current votes
	$fp_read = fopen($pollFile, "r");
	$currentVote = fread($fp_read, filesize($pollFile));
	fclose($fp_read);

	// create an array with even numbers containing vote choices
	// and odds containing vote totals
	$votes = split('[|:]', $currentVote);

	// if a message was sent, print it
	if (isset($message)) {
		echo "<p align=center>$message</p>\n\n";
	}

	echo "<table align=center>\n";
	echo "<caption align=top><b>$title</b></caption>\n";

	// print the poll table rows
	// including vote choice, vote total, and percentage of total votes
	for ($i = 1; $i < count($votes); $i = $i + 2)
	{
		// add together each vote total to find the total number of votes cast
		$totalVotes += $votes[$i];
	}

	for ($i = 1; $i < count($votes); $i = $i + 2) {
		// get the array index number for the name of this vote
		$name = $i - 1;

		// calculate the percentage of total votes for this vote
		// rounded to 1 decimal place
		if ($totalVotes == 0) {
			$percentage = 0;
		}
		else {
			$percentage = $votes[$i] / $totalVotes * 100;
			$percentage = round($percentage, 1);
		}

		echo "<tr>\n";
		echo "\t<td>$votes[$name]</td>\n";
		echo "\t<td>$votes[$i] votes</td>\n";

		// if the percentage is 0, don't print a bar
		if ($percentage == 0) {
			echo "\t<td>$percentage%</td>\n";
		}

		// otherwise, print the bar
		else {
			echo "\t<td><img src=poll.jpg width=$percentage height=15> $percentage%</td>\n";
		}

		echo "</tr>\n";
	}

	// print the total number of votes cast
	echo "<caption align=bottom>Total Votes: $totalVotes</caption>\n";

	// finish printing the poll table
	echo "</table>\n";
	echo "</body>\n";
	echo "</html>\n";
}


// PROGRAM CODE //
// if the poll is closed, display the poll and exit
if ($message = is_closed()) {
	displayPoll("The poll closed on " . $message . ".");
	exit;
}

// if the user is not voting, display the poll and exit
if (!isset($vote)) {
	displayPoll("");
	exit;
}

// if the user has already voted, display the poll and exit
if (has_voted()) {
	displayPoll("You already voted.");
	exit;
}

// add the user's vote
addVote($vote);

// display the poll
displayPoll("");

?>