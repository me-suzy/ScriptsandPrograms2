<?php
class RSSFeed				// 0.91 syntax
{
	// ===============================================================================================================

	// Constructor
	function RSSFeed()
	{
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
		?>
		<!DOCTYPE rss PUBLIC "-//Netscape Communications//DTD RSS 0.91//EN" 
		"http://my.netscape.com/publish/formats/rss-0.91.dtd">
		<rss version="0.91">
		<channel>
	<?
	}

	// ===============================================================================================================

	// Feed header
	function Header($Title, $FeedURL, $ImageURL, $FeedDescription)
	{
		?>
		<title><?= $Title ?></title>
		<link><?= $FeedURL ?></link>
		<description><?= $FeedDescription ?></description>
		<language>en-gb</language>
		<lastBuildDate><?= date("r") ?></lastBuildDate>
		<image>
			<title><?= $FeedDescription ?></title>
			<url><?= $ImageURL ?></url>
			<link><?= $FeedURL ?></link>
		</image>
		<?php
	}

	// ===============================================================================================================

	// Feed item
	function Item($Title, $Description, $URL)
	{
		?>
		<item>
			<title><?= $Title ?></title>

			<?php
			if ($Description != NULL)
			{
				?>
				<description><?= $Description ?></description>
				<?php
			}

			if ($URL != NULL)
			{
				?>
				<link><?= $URL ?></link>
				<?php
			}
			?>

		</item>
		<?php
	}

	// ===============================================================================================================

	// Feed footer
	function Footer()
	{
		?>
</channel>
</rss>
		<?php
	}

	// ===============================================================================================================
}
?>