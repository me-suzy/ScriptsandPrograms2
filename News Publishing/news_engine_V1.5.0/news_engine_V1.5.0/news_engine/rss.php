<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > RSS Newsfeed zur Verfügung stellen
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: rss.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');

if($config['activate_rss'] == 0) exit;

header("Content-Type: text/xml");

$result = $db_sql->sql_query("SELECT $news_table.* FROM $news_table
                              LEFT JOIN $newscat_table ON $news_table.catid = $newscat_table.catid
							  WHERE $news_table.newsdate <= '".time()."' 
							  AND ($news_table.news_enddate >= '".time()."' 
							  OR ISNULL($news_table.news_enddate)) 
							  AND $news_table.published = '1'
                              AND $newscat_table.rss_activate = '1'
							  GROUP BY $news_table.newsid ORDER BY $news_table.newsdate DESC LIMIT 15");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n";
echo "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns=\"http://my.netscape.com/rdf/simple/0.9/\">";
echo "<channel>\n";
echo "<title>".$config['scriptname']."</title>\n";
echo "<link>".$config['newsscripturl']."</link>\n";
echo "<description>".$lang['rss_description']."</description>\n";
echo "</channel>\n";

while ($news = $db_sql->fetch_array($result)) {
    echo "<item>\n";
    echo "<title>".htmlspecialchars($news['headline'])."</title>\n";
    echo "<link>".$config['newsscripturl']."/news.php?newsid=".$news['newsid']."</link>\n";
    echo "</item>\n\n";
}

echo "</rdf:RDF>\n";
?>
