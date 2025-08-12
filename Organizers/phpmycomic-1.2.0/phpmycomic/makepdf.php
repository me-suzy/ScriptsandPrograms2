<?php

  // Include the config file
  require('config/config.php');

    // Connect to the MySQL Database
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
     	
      $series_issue = "$issue $mini";

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
      
      $comic_price = "$currency_uid $price";
      $comic_value = "$currency_uid $value";

  // Include the pdf making files
  define('FPDF_FONTPATH','font/');
  require('fpdf.php');

  class PDF extends FPDF
  {
    // Page footer
    function Footer()
    {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('Arial','I',8);
      // Powered by
      $this->Line(10,282,200,282);
      $this->Cell(0,10,'Powered by: PhpMyComic',0,0,'C',0,'http://www.phpmycomic.net');
    }
  }

  // Start and create the pdf file
  $pdf=new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage();

  // Write comic title
  $pdf->SetFont('Arial','B',16);
  $pdf->SetFillColor(220,220,220);
  $pdf->Cell(190,10,$name_uid,1,1,'C',1);
  $pdf->SetFont('Arial','B',10);
  $pdf->SetFillColor(235,235,235);
  $pdf->Cell(190,7,$story,1,0,'C',1);
  $pdf->Ln();

  // Write issue cover details
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(38,6,'Issue Number:',1,0,'C');
  $pdf->Cell(38,6,'Volume:',1,0,'C');
  $pdf->Cell(38,6,'Quantity:',1,0,'C');
  $pdf->Cell(38,6,'Issue Price:',1,0,'C');
  $pdf->Cell(38,6,'Current Value:',1,1,'C');
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(38,6,$series_issue,1,0,'C');
  $pdf->Cell(38,6,$volume,1,0,'C');
  $pdf->Cell(38,6,$qty,1,0,'C');
  $pdf->Cell(38,6,$comic_price,1,0,'C');
  $pdf->Cell(38,6,$comic_value,1,1,'C');
  $pdf->Ln();

  // Write the issue details
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(190,6,'Comic Details',1,1,'C',1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(95,6,'Series Year:',1,0,'L');
  $pdf->Cell(95,6,$year,1,1,'L');
  $pdf->Cell(95,6,'Publisher:',1,0,'L');
  $pdf->Cell(95,6,$publisher_uid,1,1,'L');
  $pdf->Cell(95,6,'Comic Format:',1,0,'L');
  $pdf->Cell(95,6,$format_uid,1,1,'L');
  $pdf->Cell(95,6,'Comic Type:',1,0,'L');
  $pdf->Cell(95,6,$type_uid,1,1,'L');
  $pdf->Cell(95,6,'Comic Genre:',1,0,'L');
  $pdf->Cell(95,6,$genre_uid,1,1,'L');
  $pdf->Cell(95,6,'Issue Condition:',1,0,'L');
  $pdf->Cell(95,6,$condition_uid,1,1,'L');
  $pdf->Cell(95,6,'Issue Variation:',1,0,'L');
  $pdf->Cell(95,6,$variation_uid,1,1,'L');
  $pdf->Cell(95,6,$userdef1,1,0,'L');
  $pdf->Cell(95,6,$userdef2,1,1,'L');
  $pdf->Cell(95,6,'Ebay Link',1,0,'L');
  $pdf->Cell(95,6,$ebaylink,1,1,'L');
  $pdf->Cell(95,6,'Language:',1,0,'L');
  $pdf->Cell(95,6,$lang,1,1,'L');
  $pdf->Cell(95,6,'Link Publisher:',1,0,'L');
  $pdf->Cell(95,6,$link1_uid,1,1,'L');
  $pdf->Cell(95,6,'Link Comic:',1,0,'L');
  $pdf->Cell(95,6,$link2_uid,1,1,'L');
  $pdf->Ln();

  // Write the issue artist details
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(190,6,'Artist Details',1,1,'C',1);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(95,6,'Writer:',1,0,'L');
  $pdf->Cell(95,6,$comic_writer,1,1,'L');
  $pdf->Cell(95,6,'Penciler:',1,0,'L');
  $pdf->Cell(95,6,$comic_penciler,1,1,'L');
  $pdf->Cell(95,6,'Inker:',1,0,'L');
  $pdf->Cell(95,6,$comic_inker,1,1,'L');
  $pdf->Cell(95,6,'Colorist:',1,0,'L');
  $pdf->Cell(95,6,$comic_colorist,1,1,'L');
  $pdf->Cell(95,6,'Letterer:',1,0,'L');
  $pdf->Cell(95,6,$comic_letterer,1,1,'L');
  $pdf->Cell(95,6,'Cover Artist:',1,0,'L');
  $pdf->Cell(95,6,$comic_coverartist,1,1,'L');
  $pdf->Ln();

  // Write the issue plot
  $pdf->Line(10,175,200,175);
  $pdf->Write(4,$plot);

  // Output the pdf file
  $pdf->Output();
  
?>