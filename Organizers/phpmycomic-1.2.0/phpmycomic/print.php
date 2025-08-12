<?php session_start();

  // Include needed files
  include("./config/config.php");


    mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
    mysql_select_db($sql['data']) or die("Unable to find DB");

// GET THE COMIC DETAILS FROM COMIC TABLE
$select = "SELECT * FROM pmc_comic WHERE uid = '". $_GET['id'] ."'";
$data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
$row = mysql_fetch_array($data);

	// GET ALL NEEDED FIELDS
	$uid = $row['uid'];
  	$dat = $row['date'];
  	$name = $row['title'];
  	$issue = $row['issue'];
  	$publisher = $row['publisher'];
  	
  	// GET AND FORMAT THE IMAGE FIELD : IF NO IMAGE THEN USE DEFAULT
  	$i = $row['image'];
  	$image = "image/$i";
  	if (file_exists($image))
    	{
        	$img = $image;
     	} else {
        	$img = "image/noimage.jpg";
     	}
     	
  	$size = GetImageSize ("$img");
  	$width = $size['0'];
  	$height = $size['1'];  	
  	$price = $row['price'];
  	$value = $row['value'];
  	$story = stripslashes($row['story']);
  	$type = $row['type'];
  	$genre = $row['genre'];
  	$format = $row['format'];
  	$condition = $row['condition'];
  
  	// Get and format the plot
      $about = $row['plot'];
      if($about ==  '')
      	{
      		$plot = "No Plot Has Been Written";
      	} else {
      		$plot = $about;
      	}
      	
  	$volume = $row['volume'];
  	$part1 = $row['part1'];
  	$part2 = $row['part2'];  
  	
  	// Get and format the user defined options
      $user1 = $row['user1'];
      $user2 = $row['user2'];
      if($user1 == '')
      	{
      		$userdef1 = "User Defined";
      	} else {
      		$userdef1 = $user1;
      	}
      if($user2 == '')
      	{
      		$userdef2 = "No Entry";
      	} else {
      		$userdef2 = $user2;
      	}
      	
  	// Get and format the language
      $lang = $row['language'];
      if ($lang == 'English' or $lang == '')
     	{
        	$language = "English";
     	} elseif ($translator == '') {
        	$language = "$lang";
     	} else {
        	$language = "$lang ($translator)";
     	}
     
  	$translator = $row['translator'];
  	$variation = $row['variation'];
  	$currency = $row['currency'];
  	$loan = $row['loan'];
  	
  	// Get and format the ebay options
      $ebay = $row['ebay'];
      $ebayl = $row['ebaylink'];
      if($ebay == 'yes')
  		{
  			$ebaylink = $ebayl;  			
  		} else {
  			$ebaylink = "No Link Added";
  		}
  		
  	// Get and format publication date
      $pdate = $row['pubdate'];
      if($pdate == '' or $pdate == '0000-00-00')
  		{
  			$pubdate = "No Publication Date Added";
  		} else {
  			$pubdate =  $pdate;
  		}
  		
  	$qty = $row['qty'];
	


  	// FORMAT THE DATE FIELDS AFTER USER OPTION
  	//$dato = "SELECT date_format('$dat','$dateoption')";
  	//$date = mysql_db_query($sql['data'], $dato) or die("Select Failed!");
  	//$row = mysql_fetch_array($date);
  	//$today = $row[0];
  	
  	

  	
  	//---------------------------------------------------------
  	// ARTISTS :: WRITERS
  	//---------------------------------------------------------
  	
  	$select_writer = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Writer'";
  	$data = mysql_db_query($sql['data'], $select_writer) or die("Select Writers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Writers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$writers[] = $realname['name'];
   		}

		if($writers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $writers);		
				$comic_writer = $art;
		
			} 
  
  	//---------------------------------------------------------
  	// ARTISTS :: INKERS
  	//---------------------------------------------------------
  	
  	$select_inker = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Inker'";
  	$data = mysql_db_query($sql['data'], $select_inker) or die("Select Inkers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Inkers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$inkers[] = $realname['name'];
   		}

		if($inkers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $inkers);		
				$comic_inker = $art;
			
			}
  	
	//---------------------------------------------------------
  	// ARTISTS :: PENCILERS
  	//---------------------------------------------------------
  	
  	$select_penciler = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Penciler'";
  	$data = mysql_db_query($sql['data'], $select_penciler) or die("Select Pencilers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Pencilers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$pencilers[] = $realname['name'];
   		}

		if($pencilers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $pencilers);		
				$comic_penciler = $art;
				
			}
  	
  	//---------------------------------------------------------
  	// ARTISTS :: LETTERERS
  	//---------------------------------------------------------
  	
  	$select_letterer = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Letterer'";
  	$data = mysql_db_query($sql['data'], $select_letterer) or die("Select Letterers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Letterers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$letterers[] = $realname['name'];
   		}

		if($letterers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $letterers);		
				$comic_letterer = $art;
				
			}
  	
  	//---------------------------------------------------------
  	// ARTISTS :: COLORIST
  	//---------------------------------------------------------
  	
  	$select_colorist = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Colorist'";
  	$data = mysql_db_query($sql['data'], $select_colorist) or die("Select Colorists Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Colorists Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$colorists[] = $realname['name'];
   		}

		if($colorists == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $colorists);		
				$comic_colorist = $art;
				
			}
  	
  	//---------------------------------------------------------
  	// ARTISTS :: COVER ARTISTS
  	//---------------------------------------------------------
  	
  	$select_cover = "SELECT * FROM pmc_link WHERE comic_id = '$uid' AND type = 'Coverartist'";
  	$data = mysql_db_query($sql['data'], $select_cover) or die("Select Covers Failed!");
 
	while ($row = mysql_fetch_array($data))
   		{
      		$fetch = mysql_query("SELECT * FROM pmc_artist WHERE uid = '".mysql_real_escape_string($row[artist_id])."' LIMIT 1") or die("Select Covers Failed!");
      		$realname = mysql_fetch_assoc($fetch);
          
      		$covers[] = $realname['name'];
   		}

		if($covers == '')
			{
				
				// No text is displayed!
				
			} else {
				
				$art = implode(', ', $covers);		
				$comic_coverartist = $art;
				
			}
  	
  	  
  	// Get the series name from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$name'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $name_uid = $row['name'];
      $link2_uid = $row['link'];
      $year = $row['year'];

      // Get the comic type from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$type'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $type_uid = $row['name'];
      
      if($type_uid == 'Mini Series')
     	{
        	$mini = "of $part2";
     	} else {
        	$mini = "";
     	}

      // Get the issue format from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$format'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $format_uid = $row['name'];

      // Get the publisher from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$publisher'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $publisher_uid = $row['name'];
      $link1_uid = $row['link'];

      // Get the issue condition from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$condition'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $condition_uid = $row['name'];

      // Get the comic genre from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$genre'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $genre_uid = $row['name'];

      // Get the issue variation from the artist table
      $select = "SELECT * FROM pmc_artist WHERE uid = '$variation'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $variation_uid = $row['name'];

      // Get the currency
      $select = "SELECT * FROM pmc_artist WHERE uid = '$currency'";
      $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
      $row = mysql_fetch_array($data);
      $currency_uid = $row['name'];
      
      $curdate = date("Y-m-d G:i:s");


?>

<html>
<head>
<title>PMC - <?php print("$name_uid #$issue"); ?></title>
<link rel=stylesheet href="print.css" type=text/css>
</head>

<body bgcolor="#F1F1F1" topmargin="10" bottommargin="10" rightmargin="10" leftmargin="10" marginwidth="10" marginheight="10" scroll="yes">

<table border="0" cellpadding="0" cellspacing="0" class="main">
	<tr>
		<td class="header" colspan="4">Printer Friendly</td>
	</tr><tr>
		<td class="bluecell">Series</td>
		<td class="bluecell"><b><?php print $name_uid; ?> (<?php print $year; ?>)</b></td>	
		<td class="bluecell">Title</td>
		<td class="bluecell"><?php print $story; ?></td>			
	</tr><tr>
		<td class="bluecell">Issue</td>
		<td class="bluecell">#<?php print ''.$issue.' '.$mini.''; ?></td>
		<td class="bluecell">Volume</td>
		<td class="bluecell">#<?php print $volume; ?></td>		
	</tr>
	<tr>
	    <td class="bluecell">Print Date</td>
		<td class="bluecell"><?php print $curdate; ?></td>
		<td class="bluecell">Last Change</td>
		<td class="bluecell"><?php print $dat; ?></td>				
	</tr>
	<tr>
		<td class="bluecell">PMC Version</td>
		<td class="bluecell"><?php print $version; ?></td>
		<td class="bluecell">Quantity</td>
		<td class="bluecell">#<?php print $qty; ?></td>		
	</tr>
</table>

<br />

<table border="0" cellpadding="0" cellspacing="0" class="main">
	<tr>
		<td class="header">Issue Details</td>
	</tr><tr>
		<td class="normcell">
			
			<table border="0" cellpadding="0" cellspacing="0" class="info">
				<tr>
					<td class="info_head" colspan="2">Main Details</td>					
				</tr><tr>
					<td class="info_cell">Series</td><td class="info_cell"><b><?php print $name_uid; ?> (<?php print $year; ?>)</b></td>
				</tr><tr>
					<td class="info_cell">Story Title</td><td class="info_cell"><?php print $story; ?></td>
				</tr><tr>
					<td class="info_cell">Issue Number</td><td class="info_cell">#<?php print ''.$issue.' '.$mini.''; ?></td>
				</tr><tr>
					<td class="info_cell">Series Volume</td><td class="info_cell">#<?php print $volume; ?></td>
				</tr><tr>
					<td class="info_cell">URL Series</td><td class="info_cell"><?php print $link2_uid; ?></td>
				</tr><tr>
					<td class="info_cell">Comic Type</td><td class="info_cell"><?php print $type_uid; ?></td>
				</tr><tr>
					<td class="info_cell">Series Genre</td><td class="info_cell"><?php print $genre_uid; ?></td>
				</tr><tr>
					<td class="info_head" colspan="2">Purchase / Value Info</td>					
				</tr><tr>
					<td class="info_cell">Cover Price</td><td class="info_cell"><?php print ''.$currency_uid.' '.$price.''; ?></td>
				</tr><tr>
					<td class="info_cell">Current Value</td><td class="info_cell"><?php print ''.$currency_uid.' '.$value.''; ?></td>
				</tr><tr>
					<td class="info_cell">Issue Condition</td><td class="info_cell"><?php print $condition_uid; ?></td>
				</tr><tr>
					<td class="info_cell">Issue Variation</td><td class="info_cell"><?php print $variation_uid; ?></td>
				</tr><tr>
					<td class="info_head" colspan="2">Publication Info</td>					
				</tr><tr>
					<td class="info_cell">Format</td><td class="info_cell"><?php print $format_uid; ?></td>
				</tr><tr>
					<td class="info_cell">Publisher</td><td class="info_cell"><?php print $publisher_uid; ?></td>
				</tr><tr>
					<td class="info_cell">URL Publisher</td><td class="info_cell"><?php print $link1_uid; ?></td>
				</tr><tr>
					<td class="info_cell">Publication Date</td><td class="info_cell"><?php print $pubdate; ?></td>
				</tr><tr>
					<td class="info_cell">Language</td><td class="info_cell"><?php print $language; ?></td>
				</tr><tr>
					<td class="info_head" colspan="2">Issue Artist Info</td>					
				</tr><tr>
					<td class="info_cell">Writer</td><td class="info_cell"><?php print $comic_writer; ?></td>
				</tr><tr>
					<td class="info_cell">Inker</td><td class="info_cell"><?php print $comic_inker; ?></td>
				</tr><tr>
					<td class="info_cell">Penciler</td><td class="info_cell"><?php print $comic_penciler; ?></td>
				</tr><tr>
					<td class="info_cell">Colorist</td><td class="info_cell"><?php print $comic_colorist; ?></td>
				</tr><tr>
					<td class="info_cell">Letterer</td><td class="info_cell"><?php print $comic_letterer; ?></td>
				</tr><tr>
					<td class="info_cell">Cover Artist</td><td class="info_cell"><?php print $comic_coverartist; ?></td>
				</tr><tr>
					<td class="info_head" colspan="2">Other Info</td>					
				</tr><tr>
					<td class="info_cell">Ebay Link</td><td class="info_cell"><?php print $ebaylink; ?></td>
				</tr><tr>
					<td class="info_cell"><?php print $userdef1; ?></td><td class="info_cell"><?php print $userdef2; ?></td>
				</tr><tr>
					<td class="info_head" colspan="2">The Plot</td>					
				</tr><tr>
					<td class="info_cell" colspan="2"><?php print $plot; ?></td>					
				</tr>
			</table>
			<br />
		</td>
	</tr>
</table>

</body>
</html>