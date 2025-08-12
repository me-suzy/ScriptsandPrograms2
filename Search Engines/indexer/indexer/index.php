<?php 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  INDEXER - Index new dataset 
//  
//  Use the Indexer to prepare i.e. the database for a customizing fulltext search.
//  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  Usage:
//  
//  method setPathToIndexer(path): sets the path to the indexer. Default is './'
//  
//  method setID(id): sets the ID. After performing a search, you retrieve this ID to identify your inputdata.
//  
//  method addParameter(type, val): add various parameters to the object file. You can filter the output.
//  
//  method addField(type, text): add various fields to the search index. All text get into the index.
//  
//  method index(): perform the indexing mechanism.
//  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Indexer</title>
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
if (isset($_POST['indexnow']) and $_POST['indexnow']) {

	// Example data
	$data = array(
		1 => array(
			"titel"=>"AEGEON", 
			"text"=>"O, had the five gods done so, I had not now
Worthily term'd them merciless to us!
For, ere the ships could meet by twice five leagues,
We were encounterd by a mighty rock;
Which being violently borne upon,
Our helpful ship was splitted in the midst;
So that, in this unjust divorce of us,
Fortune had left to both of us alike
What to delight in, what to sorrow for."
		), 
		2 => array(
			"titel"=>"ANGELO", 
			"text"=>" Even just the sum that I do owe to you
Is growing to me by Antipholus,
And in the instant that I met with you
He had of me a chain: at five o'clock
I shall receive the money for the same.
Pleaseth you walk with me down to his house,
I will discharge my bond and thank you too."
		), 
		3 => array(
			"titel"=>"ADRIANA", 
			"text"=>"Hold, hurt him not, for God's sake! he is mad.
Some get within him, take his sword away:
Bind Dromio too, and bear them to my house."
		), 
		4 => array(
			"titel"=>"OF SYRACUSE", 
			"text"=>"
After his brother: and importuned me
That his attendant -- so his case was like,
Reft of his brother, but retain'd his name --
Might bear him company in the quest of him:
Whom whilst I labour'd of a love to see,
I hazarded the loss of whom I loved.
Five summers have I spent in furthest Greece,
Roaming clean through the bounds of Asia,
And, coasting homeward, came to Ephesus."
		), 
		
		
	);
	
	require_once("./class.indexer.php");
	
	foreach ($data as $key=>$value) {
		$indexer = new indexer();
		$indexer->setPathToIndexer("./");
		$indexer->setId($key);
		$indexer->addParameter("active", "1");
		$indexer->addField("titel", $value['titel']);
		$indexer->addField("text", $value['text']);
		$indexer->index();
	}
?>
READY!
<?php 
}
?>
<h1>INDEXER</h1>
<hr>
By clicking the button, you fill the 'idx.php' file in the 'index' directory and create object-files in the 'object' directory.<br>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="formindex" id="formindex">
<input type="submit" name="indexnow" value="Build Index">
</form>

<hr>

<form action="./search.php" method="post" name="formsearch" id="formsearch">
Search:<br>
<input type="text" name="searchword" class="forms"> <input type="submit" name="search" value="Search"><br>
For example: "five" or "bear"
</form>

<hr>
<br><br><br>

<h2>README</h2>
<pre>
<?php echo join("", file("./README"));?>
</pre>

</body>
</html>
