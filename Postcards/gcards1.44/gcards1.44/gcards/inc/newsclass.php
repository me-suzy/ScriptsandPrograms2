<?

class news
{
/*
	var $conn;
	function news($conn)
	{
		$this->conn = $conn;
	}
*/
	function displayItem($subject, $postdate, $username, $body)
	{
		global $news02;
		global $news03;
		?>
			
			<tr>
				<td><? echo '<span class="bold">'.$subject.'</span><br><span class="smalltext">'.$news02.' '.$postdate.' '.$news03.' '.$username.'</span>'; ?></td>
			</tr>
			<tr>
				<td>
					<? 
						echo $body;
					?>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		<?
	}
	function getNews(&$recordSet, $summary=0)
	{
		include_once('inc/smileyClass.php');
		$newsSmileyClass = new smileyClass("images/siteImages/smilies/");
		global $dateFormat;
		global $summaryLength;
		$newsRecordSet = $recordSet;
		while (!$newsRecordSet->EOF)
		{
			$newsid = $newsRecordSet->fields['newsid'];
			$username = $newsRecordSet->fields['username'];
			$subject = $newsRecordSet->fields['subject'];
			$body = $newsRecordSet->fields['body'];
			$postdate = date($dateFormat, $newsRecordSet->fields['postdate']);
			if (($summary !=0) && (strlen($body) > $summaryLength))
			{
				global $nav13;
				$body = substr($body, 0, $summaryLength).'... <a href="getnewsitem.php?newsid='.$newsid.'">'.$nav13.'</a>';
			}
			$body = $newsSmileyClass->replaceSmileys($body);
			$this->displayItem($subject, $postdate, $username, $body);
			$newsRecordSet->MoveNext();
		}
	}
}

?>