

<?

        require('config.php'); 

	$filename = "article_summary.html";

	#- open article summaries
	if(file_exists($filename)){
		$fh = fopen($filename, "r");
		$old_news = fread($fh, filesize($filename));
		fclose($fh);
	}

 
	#- get first five article
	$articles = explode("<!--ARTICLE-->", $old_news);

	$i=0;
	foreach ( $articles as $article ){
		if(count($articles)>$i){
			if($max_latest >= $i++){
				print $article;
			}
		}
	}

?>



