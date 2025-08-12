<?

// ==============================================================================================================================

function ShowVotingForm($ArticleID)
{
	global $NewsDir, $NewsDisplay_DateFormat, $NewsDisplay_TimeFormat, $MaxRating, $AllowDuplicateRating;

	// Obtain the remote IP
	$ip = GetRemoteIP();

	// See if this IP Address has already voted for this article
	$query = "SELECT * FROM news_ratings WHERE ArticleID = '$ArticleID' AND IPAddress = '$ip'";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if (($num_rows != 0) && (! $AllowDuplicateRating))
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		// Convert the date and time to the user-specified format
		$RatingDate = date($NewsDisplay_DateFormat, strtotime($row['RatingDateTime']));
		$RatingTime = date($NewsDisplay_TimeFormat, strtotime($row['RatingDateTime']));
		$Rating = $row['Rating'];

		?>
		<TABLE class="Admin">
			<tr>
				<td>
					<B>News Article: </B> <?= GetHeadline($ArticleID) ?><HR>
					Sorry, but a vote has already been cast from your IP address (<?=$ip?>) for this article.<BR><BR>
					Your vote was cast on <?=$RatingDate?> at <?=$RatingTime?>, and you awarded the article <?=$Rating?>/<?=$MaxRating?>.<BR><HR>
					<center>
						<input class="but" type="button" name="Close" value="Close" onClick="javascript:window.close()">
					</center>
				</td>
			</tr>
		</table>
		<?
	}
	else
	{
		?>
		<table align="center" width="100%" border="0">
			<tr>
				<td>
					<B>News Article: </B> <?= GetHeadline($ArticleID) ?><HR>
					<FORM name="vote" method="post" action="<?= $NewsDir . '/Vote.php?ArticleID=' . $ArticleID ?>">
						Please specify your rating for this article: <?php BuildNumericDropdown('Rating', -1, 1, $MaxRating) ?>
						<input class="but" type="submit" name="submit" value="Vote">
					</FORM>
				</td>
			</tr>
		</table>
		<?
	}
}

// ==============================================================================================================================

function RecordVote($ArticleID)
{
	global $NewsDir, $MaxRating, $AllowDuplicateRating;

	// Obtain the remote IP
	$ip = GetRemoteIP();

	// See if this IP Address has already voted for this article
	$query = "SELECT * FROM news_ratings WHERE ArticleID = '$ArticleID' AND IPAddress = '$ip'";
	$result = mysql_query($query) or die('Query failed : ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if (($num_rows != 0) && (! $AllowDuplicateRating))
		die ("Illegal attempt to record a new vote!");

	// Obtain the vote
	$RatingDateTime = CurrentFormattedDateTime();
	$Rating = $_POST['Rating'];

	// Not numeric?
	if (! is_numeric($Rating))
		die ("Illegal attempt to record an invalid vote format (must be numeric)!");

	// Illegal?
	if (($Rating < 1) || ($Rating > $MaxRating))
		die ("Illegal attempt to record an invalid vote!");

	// Record the vote
	mysql_query("INSERT INTO news_ratings SET ArticleID='$ArticleID', IPAddress='$ip', RatingDateTime=now(), Rating='$Rating'");
	?>
	<table align="center" width="100%" border="0">
		<tr>
			<td>
				<B>News Article: </B> <?= GetHeadline($ArticleID) ?><HR>
				Thank you for casting your vote.
				<BR><HR>
				<center>
					<input class="but" type="button" name="Close" value="Close" onClick="javascript:window.close()">
				</center>
			</td>
		</tr>
	</table>
	<?
}
?>