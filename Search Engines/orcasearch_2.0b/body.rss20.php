<?php /* ***** Orca Search - Search Engine XML:RSS 2.0 Output *******
* Orca Search v2.0b - Copyright (C) 2005 GreyWyvern
*  A robust auto-spidering search engine for single/multiple sites
* 
* See the readme.txt file for installation instructions.
****************************************************************** */ 

/* *************************************************************** */
/* ***** Setup *************************************************** */
$_QUERY['original'] = trim(htmlspecialchars($_QUERY['original']));
$_QUERY['time'] = time();
$_QUERY['date'] = date("r");

$resultTemplate = <<<ORCA
<item>
  <title>{R_TITLE} ({R_RELEVANCE})</title>
  <description><![CDATA[{R_MATCH}]]></description>
  <link>{R_URI}</link>
  <author>Result #{R_NUMBER}</author>
  <pubDate>{$_QUERY['date']}</pubDate>
  <guid isPermaLink="false">{$vData['sp.time']}-{R_MD5URI}</guid>
</item>
ORCA;


$resultsMax = 10;

header("Content-type: application/rss+xml; charset=".$vData['c.charset']);
header("Expires: ".date("r", time() + 60 * $vData['sp.interval']));

/* *************************************************************** */
/* ***** Output ************************************************** */
echo "<?xml version=\"1.0\"?>\n";
?><rss version="2.0" xmlns:html="http://www.w3.org/TR/REC-html40">
  <channel>
    <title>Orca Search Results: <?php echo $_QUERY['original']; ?></title>
    <link><?php echo $_SERVER['REQUEST_URI']; ?></link>
    <description>Search results from <?php echo $_SERVER['REQUEST_URI']; ?></description>
    <language>en-us</language>
    <copyright>Copyright 2004 - <?php echo $_SERVER['REQUEST_URI']; ?></copyright>
    <lastBuildDate><?php echo date("r"); ?></lastBuildDate>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <generator><?php echo $dData['userAgent']; ?></generator>
    <managingEditor>youremail@example.com</managingEditor>
    <webMaster>youremail@example.com</webMaster>
    <ttl><?php echo $vData['sp.interval'] * 60; ?></ttl>
    <?php $tally = 0;
    $sData['find'] = array("{R_NUMBER}", "{R_RELEVANCE}", "{R_URI}", "{R_CATEGORY}", "{R_TITLE}", "{R_DESCRIPTION}", "{R_MATCH}", "{R_MD5URI}");
    foreach ($_RESULTS as $result) {
      if (++$tally > $resultsMax) break;
      $result['title'] = strip_tags($result['title']);
      $sData['repl'] = array($tally, $result['relevance'], htmlspecialchars($result['uri']), htmlspecialchars($result['category']), ($result['title']) ? $result['title'] : htmlspecialchars($result['uri']), $result['description'], $result['matchText'], md5($result['uri']));
      echo str_replace($sData['find'], $sData['repl'], $resultTemplate);
    } ?> 
  </channel>
</rss>