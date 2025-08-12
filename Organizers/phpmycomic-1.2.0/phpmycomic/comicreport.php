<?php

// Include needed files
include("./class.TemplatePower.inc.php");
include("./config/config.php");

// Create a new template object
$tpl = new TemplatePower("themes/$themes/tpl/comicreport.tpl");

// Prepare the template
$tpl->prepare();

$tpl->assignGlobal("theme", $themes);
$tpl->assignGlobal("pmcurl", $siteurl);
$tpl->assignGlobal("sitetitle", $sitetitle);
$tpl->assignGlobal("imgfolder", "themes/$themes/img");
$tpl->assign("version", $version);

// MySQL Connection
mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
mysql_select_db($sql['data']) or die("Unable to find DB");

if($_POST['list_option1'] == 'none') {
	$tpl->assign("cell1", "");
}
if($_POST['list_option1'] == 'title') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Title</td>");
}
if($_POST['list_option1'] == 'story') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Story</td>");
}
if($_POST['list_option1'] == 'publisher') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Publisher</td>");
}
if($_POST['list_option1'] == 'price') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Price</td>");
}
if($_POST['list_option1'] == 'value') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Value</td>");
}
if($_POST['list_option1'] == 'issue') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Issue</td>");
}
if($_POST['list_option1'] == 'volume') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Volume</td>");
}
if($_POST['list_option1'] == 'type') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Type</td>");
}
if($_POST['list_option1'] == 'genre') {
	$tpl->assign("cell1", "<td class=\"header\" nowrap=\"nowrap\">Genre</td>");
}

if($_POST['list_option2'] == 'none') {
	$tpl->assign("cell2", "");
}
if($_POST['list_option2'] == 'title') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Title</td>");
}
if($_POST['list_option2'] == 'story') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Story</td>");
}
if($_POST['list_option2'] == 'publisher') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Publisher</td>");
}
if($_POST['list_option2'] == 'price') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Price</td>");
}
if($_POST['list_option2'] == 'value') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Value</td>");
}
if($_POST['list_option2'] == 'issue') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Issue</td>");
}
if($_POST['list_option2'] == 'volume') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Volume</td>");
}
if($_POST['list_option2'] == 'type') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Type</td>");
}
if($_POST['list_option2'] == 'genre') {
	$tpl->assign("cell2", "<td class=\"header\" nowrap=\"nowrap\">Genre</td>");
}

if($_POST['list_option3'] == 'none') {
	$tpl->assign("cell3", "");
}
if($_POST['list_option3'] == 'title') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Title</td>");
}
if($_POST['list_option3'] == 'story') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Story</td>");
}
if($_POST['list_option3'] == 'publisher') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Publisher</td>");
}
if($_POST['list_option3'] == 'price') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Price</td>");
}
if($_POST['list_option3'] == 'value') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Value</td>");
}
if($_POST['list_option3'] == 'issue') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Issue</td>");
}
if($_POST['list_option3'] == 'volume') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Volume</td>");
}
if($_POST['list_option3'] == 'type') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Type</td>");
}
if($_POST['list_option3'] == 'genre') {
	$tpl->assign("cell3", "<td class=\"header\" nowrap=\"nowrap\">Genre</td>");
}

if($_POST['list_option4'] == 'none') {
	$tpl->assign("cell4", "");
}
if($_POST['list_option4'] == 'title') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Title</td>");
}
if($_POST['list_option4'] == 'story') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Story</td>");
}
if($_POST['list_option4'] == 'publisher') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Publisher</td>");
}
if($_POST['list_option4'] == 'price') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Price</td>");
}
if($_POST['list_option4'] == 'value') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Value</td>");
}
if($_POST['list_option4'] == 'issue') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Issue</td>");
}
if($_POST['list_option4'] == 'volume') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Volume</td>");
}
if($_POST['list_option4'] == 'type') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Type</td>");
}
if($_POST['list_option4'] == 'genre') {
	$tpl->assign("cell4", "<td class=\"header\" nowrap=\"nowrap\">Genre</td>");
}

if($_POST['list_option5'] == 'none') {
	$tpl->assign("cell5", "");
}
if($_POST['list_option5'] == 'title') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Title</td>");
}
if($_POST['list_option5'] == 'story') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Story</td>");
}
if($_POST['list_option5'] == 'publisher') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Publisher</td>");
}
if($_POST['list_option5'] == 'price') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Price</td>");
}
if($_POST['list_option5'] == 'value') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Value</td>");
}
if($_POST['list_option5'] == 'issue') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Issue</td>");
}
if($_POST['list_option5'] == 'volume') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Volume</td>");
}
if($_POST['list_option5'] == 'type') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Type</td>");
}
if($_POST['list_option5'] == 'genre') {
	$tpl->assign("cell5", "<td class=\"header\" nowrap=\"nowrap\">Genre</td>");
}

// The MySQL command to run
$comselect = "SELECT * FROM pmc_artist WHERE type = 'Series' ORDER BY name";

// Run the query
$comdata = mysql_db_query($sql['data'], $comselect) or die("Select Failed!");

while ($comrow = mysql_fetch_array($comdata))
   {
   	
   	$getname = $comrow['uid'];

// The MySQL command to run
$select = "SELECT * FROM pmc_comic WHERE title = $getname ORDER BY title, volume, issue, issueltr";
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");

while($row = mysql_fetch_array($data))
{
   
      // Get all the fields
      $issue = $row['issue'];
      $issueltr = $row['issueltr'];
      $volume = $row['volume'];
      $title = $row['title'];
      $story = $row['story'];
      $publisher = $row['publisher'];
      $type = $row['type'];
      $genre = $row['genre'];
      $cost = $row['price'];
      $value = $row['value'];
      $currency = $row['currency'];
      
      $tpl->newBlock("comic_report");
      
      // GET THE OPTION 1 VALUES      
      if($_POST['list_option1'] == 'none') {
      	$tpl->assign("option1", "");
      }
      
      if($_POST['list_option1'] == 'title') {
	  	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$name_uid</td>");
      }
      
      if($_POST['list_option1'] == 'publisher') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$pub_uid = $row['name'];
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$pub_uid</td>");
      }
      
      if($_POST['list_option1'] == 'story') {
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$story</td>");
      }
      
      if($_POST['list_option1'] == 'volume') {
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$volume</td>");
      }
      
      if($_POST['list_option1'] == 'issue') {
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$issue$issueltr</td>");
      }
      
      if($_POST['list_option1'] == 'price') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$cost_uid = $row['name'];
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$cost_uid $cost</td>");
      }
      
      if($_POST['list_option1'] == 'value') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$val_uid = $row['name'];
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$val_uid $value</td>");
      }
      
      if($_POST['list_option1'] == 'type') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$type_uid = $row['name'];
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$type_uid</td>");
      }
      
      if($_POST['list_option1'] == 'genre') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$genre_uid = $row['name'];
      	$tpl->assign("option1", "<td nowrap=\"nowrap\" class=\"cell\">$genre_uid</td>");
      }  
      
      // GET THE OPTION 2 VALUES      
      if($_POST['list_option2'] == 'none') {
      	$tpl->assign("option2", "");
      }
      
      if($_POST['list_option2'] == 'title') {
	  	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$name_uid</td>");
      }
      
      if($_POST['list_option2'] == 'publisher') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$pub_uid = $row['name'];
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$pub_uid</td>");
      }
      
      if($_POST['list_option2'] == 'story') {
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$story</td>");
      }
      
      if($_POST['list_option2'] == 'volume') {
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$volume</td>");
      }
      
      if($_POST['list_option2'] == 'issue') {
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$issue$issueltr</td>");
      }
      
      if($_POST['list_option2'] == 'price') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$cost_uid = $row['name'];
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$cost_uid $cost</td>");
      }
      
      if($_POST['list_option2'] == 'value') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$val_uid = $row['name'];
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$val_uid $value</td>");
      }
      
      if($_POST['list_option2'] == 'type') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$type_uid = $row['name'];
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$type_uid</td>");
      }
      
      if($_POST['list_option2'] == 'genre') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$genre_uid = $row['name'];
      	$tpl->assign("option2", "<td nowrap=\"nowrap\" class=\"cell\">$genre_uid</td>");
      }
      
      // GET THE OPTION 3 VALUES      
      if($_POST['list_option3'] == 'none') {
      	$tpl->assign("option3", "");
      }
      
      if($_POST['list_option3'] == 'title') {
	  	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$name_uid</td>");
      }
      
      if($_POST['list_option3'] == 'publisher') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$pub_uid = $row['name'];
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$pub_uid</td>");
      }
      
      if($_POST['list_option3'] == 'story') {
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$story</td>");
      }
      
      if($_POST['list_option3'] == 'volume') {
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$volume</td>");
      }
      
      if($_POST['list_option3'] == 'issue') {
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$issue$issueltr</td>");
      }
      
      if($_POST['list_option3'] == 'price') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$cost_uid = $row['name'];
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$cost_uid $cost</td>");
      }
      
      if($_POST['list_option3'] == 'value') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$val_uid = $row['name'];
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$val_uid $value</td>");
      }
      
      if($_POST['list_option3'] == 'type') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$type_uid = $row['name'];
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$type_uid</td>");
      }
      
      if($_POST['list_option3'] == 'genre') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$genre_uid = $row['name'];
      	$tpl->assign("option3", "<td nowrap=\"nowrap\" class=\"cell\">$genre_uid</td>");
      }
      
      // GET THE OPTION 4 VALUES      
      if($_POST['list_option4'] == 'none') {
      	$tpl->assign("option4", "");
      }
      
      if($_POST['list_option4'] == 'title') {
	  	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$name_uid</td>");
      }
      
      if($_POST['list_option4'] == 'publisher') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$pub_uid = $row['name'];
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$pub_uid</td>");
      }
      
      if($_POST['list_option4'] == 'story') {
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$story</td>");
      }
      
      if($_POST['list_option4'] == 'volume') {
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$volume</td>");
      }
      
      if($_POST['list_option4'] == 'issue') {
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$issue$issueltr</td>");
      }
      
      if($_POST['list_option4'] == 'price') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$cost_uid = $row['name'];
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$cost_uid $cost</td>");
      }
      
      if($_POST['list_option4'] == 'value') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$val_uid = $row['name'];
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$val_uid $value</td>");
      }
      
      if($_POST['list_option4'] == 'type') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$type_uid = $row['name'];
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$type_uid</td>");
      }
      
      if($_POST['list_option4'] == 'genre') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$genre_uid = $row['name'];
      	$tpl->assign("option4", "<td nowrap=\"nowrap\" class=\"cell\">$genre_uid</td>");
      }
      
      // GET THE OPTION 5 VALUES      
      if($_POST['list_option5'] == 'none') {
      	$tpl->assign("option5", "");
      }
      
      if($_POST['list_option5'] == 'title') {
	  	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$title'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$name_uid = $row['name'];
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$name_uid</td>");
      }
      
      if($_POST['list_option5'] == 'publisher') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$pub_uid = $row['name'];
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$pub_uid</td>");
      }
      
      if($_POST['list_option5'] == 'story') {
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$story</td>");
      }
      
      if($_POST['list_option5'] == 'volume') {
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$volume</td>");
      }
      
      if($_POST['list_option5'] == 'issue') {
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$issue$issueltr</td>");
      }
      
      if($_POST['list_option5'] == 'price') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$cost_uid = $row['name'];
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$cost_uid $cost</td>");
      }
      
      if($_POST['list_option5'] == 'value') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$val_uid = $row['name'];
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$val_uid $value</td>");
      }
      
      if($_POST['list_option5'] == 'type') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$type_uid = $row['name'];
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$type_uid</td>");
      }
      
      if($_POST['list_option5'] == 'genre') {
      	// Get Series name
      	$select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
      	$dat = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      	$row = mysql_fetch_array($dat);
      	$genre_uid = $row['name'];
      	$tpl->assign("option5", "<td nowrap=\"nowrap\" class=\"cell\">$genre_uid</td>");
      }  
      
	  
      
}

   }

// Print the result
$tpl->printToScreen();

?>