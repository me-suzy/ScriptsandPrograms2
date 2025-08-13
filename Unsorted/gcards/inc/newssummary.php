<?
$newsInTableSQL = "SELECT COUNT(*) from ".$tablePrefix."news";
$numNews = $conn->GetOne($newsInTableSQL);
$getNewsSQL = "SELECT * from ".$tablePrefix."news ORDER BY newsid DESC";
$newsRecordSet = &$conn->SelectLimit($getNewsSQL,$newsLimit,0 );
if (!$newsRecordSet) print $conn->ErrorMsg();
else
{
	?>
		<table>
			<tr>
				<td class="subtitle"><? echo $newsTitle; ?></td>
			</tr>
			<tr>
				<td><? $page->drawLine();?><br></td>
			</tr>
	<?
	include_once('inc/newsclass.php');
	$news = new news;
	$news->getNews($newsRecordSet, 1);

	if ($numNews > $newsLimit)
	{
	?>
		<tr>
			<td><a href="news.php"><? echo $news01;?></a></td>
		</tr>
	<?
	}
	?></table>
	<?
}


$conn->Close(); # optional


?>