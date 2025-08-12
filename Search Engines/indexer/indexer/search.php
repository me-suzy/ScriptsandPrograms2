<?php 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  INDEXER - Search for keywords 
//  
//  Set the searchword value to start search. You will only find words longer than three chars!
//  At the moment it is possible to search for more than one word. The searchwords are AND-associated.
//  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  Returning array:
//  
//  Array (
//  	[0] => Object (
//  		[id]	=> id of the dataset
//  		[key]	=> Array of matching keywords
//  		[score]	=> ranking for the id
//   		[text]	=> cutted text
//  	)
//  )
//  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  Default values:
//  
//  setPathToIndexer = ./
//  textSimilarityInPercent = 100
//  textLength = 120
//  textOffset = 30
//  textDelimiter = "..."
//  textHighlight = '<span style="border-bottom: 1px dotted;"><strong>\1</strong></span>'
//  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
	<style type="text/css">
		body {
			font-family: Arial, Helvetica, sans-serif;
			color: #666666;
			font-size: 11px;
		}
		.forms {
			font-family: Arial, Helvetica, sans-serif;
			color: #666666;
			font-size: 11px;
		}
	</style>
</head>

<body>
<?php 
$searchword = (isset($_POST['searchword']) and $_POST['searchword']) ? $_POST['searchword'] : "";

require_once("./class.indexer.php");

$indexer = new indexer();
$indexer->setPathToIndexer("./");
$indexer->setSearchword($searchword);
$indexer->setLimit(0,10);
$indexer->setParameter("active", "1");
//$indexer->setTextSimilarityInPercent(100);
//$indexer->setTextLength(120);
//$indexer->setTextOffset(30);
//$indexer->setTextDelimiter("...");
//$indexer->setTextHighlight('<span style="border-bottom: 1px dotted;"><strong>\1</strong></span>');
$res = $indexer->search();

foreach ($res as $arrSearch) {
	echo $arrSearch->text."<br>";
}

?>
<hr>
<form action="./search.php" method="post" name="formsearch" id="formsearch">
Search:<br>
<input type="text" name="searchword" value="<?php echo $searchword;?>" class="forms"> <input type="submit" name="search" value="Search">
</form>


</body>
</html>
