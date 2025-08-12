<?php 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  INDEXER - Remove index dataset 
//  
//  Example to remove one object of the index file
//  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  
//  Usage:
//  
//  method setPathToIndexer(path): sets the path to the indexer. Default is './'
//  
//  method setID(id): sets the ID. After performing a search, you retrieve this ID to identify your inputdata.
//  
//  method removeIndex(): perform the removing mechanism.
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
require_once("./class.indexer.php");

$indexer = new indexer();
$indexer->setPathToIndexer("./");
$indexer->setId($_GET['id']);
$indexer->removeIndex();
?>

</body>
</html>
