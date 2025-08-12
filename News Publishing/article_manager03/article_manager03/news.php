<html>
<body bgcolor=#f4efce>

<basefont size=2 face=arial>

<b>Add Article</b>
<?
        include ("template.inc");
        include ("config.php");
	
	$subject = $_POST[subject];
	$summary = $_POST[summary];
	$passwd = $_POST[passwd];
	$date = $_POST[date];
	$body = $_POST[body];
	$article_id = $_POST[article_id];

	#foreach($GLOBALS as $a => $b){	print "<li>$a => $b";}

	$summary_template = "t_summary.html";
	$article_template = "t_article.html";
	$max_summary = 5;

	function summary_page ($subject, $date, $summary, $article_id)
	{
		global $summary_template;
        	$t = new Template();
        	$t->set_file("SummaryPage", $summary_template);
		$article_url = "article_".$article_id.".html";
		$date = nl2br($date);
		$summary =  nl2br($summary);	 
		$t->set_var( array(
				"subject" => $subject,
				"date"    => $date,
				"summary" => $summary,
				"article_url" => $article_url
				));
		$t->parse("Summary", "SummaryPage");
		return $t->get_var("Summary");
	}

	function main_page ($subject, $date, $summary, $article_id, $body)
	{
		global $article_template;

                $t = new Template();
                $t->set_file("ArticlePage", $article_template);
                $article_url = "article_".$article_id.".html";
                $date = nl2br($date);
                $summary =  nl2br($summary);
                $body =  nl2br($body);
                $t->set_var( array(
                                "subject" => $subject,
                                "date"    => $date,
                                "summary" => $summary,
                                "body" => $body,
                                "article_url" => $article_url
                                ));
                $t->parse("Article", "ArticlePage");
                return $t->get_var("Article"); 
	}

	function add_article($filename, $news)
	{

		if(file_exists($filename)){
			$fh = fopen($filename, "r");
			$old_news = fread($fh, filesize($filename));
			fclose($fh); 
		}

		/* TODO: Multipage articles
			preg_match_all("<!--ARTICLE PAGE=(\d*)-->", $old_news, $matches;
		
			if( count($matches[0]) >= $max_summary){
				$oldfilename = $filename.($matches[0][0]+1);
			} 
		*/

		$fh = fopen($filename, "w");
		$news = stripslashes($news);
		fwrite($fh, "\n<!--ARTICLE-->\n$news $old_news");
		fclose($fh);
	}

	if(strcmp($subject, "")){	
		if(!(strcmp($passwd, $password))){	
			add_article("article_summary.html", summary_page($subject, $date, $summary, $article_id));
			add_article("article_$article_id.html", main_page($subject, $date, $summary, $article_id, $body));
			echo "<p> <a href=article_$article_id.html>Article</a> has been added! <p>";
		}else{
			echo "<p><b> Password is wrong! </b>";
		}
	}
?>


<form action=news.php method=post>
<table border=0>
<tr> <td> (Password): </td><td> <input type=password name=passwd size=20> </td></tr>
<tr> <td> Subject: </td><td> <input type=text name=subject size=50> </td></tr>
<tr> <td> Article ID: </td><td> <input type=text name=article_id value=<? echo date("Y_m_j_is"); ?> size=30> </td></tr>
<tr> <td> Date/Author/etc: </td><td> <textarea name=date rows=2 cols=50 wrap=soft><? echo date("M j, Y\n"); ?>Author: </textarea> </td></tr>
<tr> <td> Summary: </td><td> <textarea name=summary rows=5 cols=50 wrap=soft></textarea> </td></tr>
<tr> <td> Body: </td><td> <textarea name=body rows=15 cols=50></textarea> </td></tr>
</table>
<input type=submit name=submit value=Add>
</form>


<p>
