<?php session_start();

// Include the config file
include("./config/config.php");

// Include the template engine
include("./class.TemplatePower.inc.php");

// Connect to the MySQL database
mysql_connect($sql['host'], $sql['user'], $sql['pass']) or die("Can't connect to MySQL");
mysql_select_db($sql['data']) or die("Can't select the database");

//-------------------------------------------------------------------
//
//  FUNCTION :: LOGIN
//
//-------------------------------------------------------------------

if (!strcmp($_GET['cmd'], "login")) {

	$loggeduser = $_POST['loguser'];
    
    // Check the user against the pmc_user table 
    $sql = "SELECT * FROM pmc_user WHERE name='" . mysql_real_escape_string($_POST['loguser']) . "' AND password='" . md5(mysql_real_escape_string($_POST['logpass'])) . "'";
    $result = mysql_query($sql) or die(mysql_error());

    if(mysql_num_rows($result) == 1)
    	{
        	session_register('loggedin');
           	session_register('username');
           	session_register('ip');

           	$_SESSION['loggedin'] = 'yes';
           	$_SESSION['username'] = $loggeduser;
           	$_SESSION['ip'] = getenv('REMOTE_ADDR');

           	// If login success, goto the index page
           	header("Location: index.php");
           	exit;

		} else {
			
        	// If login fails, show error page
        	header("Location: error.php?error=02");
        	exit;
        	
     }

//-------------------------------------------------------------------
//
// FUNCTION :: LOGOUT
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "logout")) {

	// Logout and delete the session
    session_start();
    session_destroy();

    // After logout, return to the index page
    header("Location: index.php");
    exit;

//-------------------------------------------------------------------
//
// FUNCTION :: ADD NEW COMIC
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addcomic")) {

	// Login check
    if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

    // Get the series name from the artist table
    $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_name']) ."' AND type = 'Series'";
    $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
    $row = mysql_fetch_array($data);   
    $name_uid = $row['uid'];

    // Get the issue variation from the artist table
    $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_variation']) ."' AND type = 'Variation'";
    $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
    $row = mysql_fetch_array($data);
    $variation_uid = $row['uid'];

    // Get the series type
    $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_type']) ."' AND type = 'Type'";
    $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
    $row = mysql_fetch_array($data);
    $type_uid = $row['name'];
    
    // This will explode the issue number into numeric and alpha
    $issue_number = $_POST['com_issue'];
	preg_match('/^(\d+)(\w*)?$/', $issue_number, $m);
  	$number_part = $m[1];
  	$alpha_part = $m[2]; 

	// Check if comic excists and that it is not a one shot comic
    if ($type_uid != 'One Shot')
    	{
     		// A query to check if the comic already exists
     		$getcomic = "SELECT * FROM `pmc_comic` WHERE issue = '". $number_part ."' and issueltr = '". $alpha_part ."' and title = '$name_uid' AND variation = '$variation_uid' AND volume = '". $_POST['com_volume'] ."'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid'];
     	}

	// If issue does not exists, add it to the database
    if($uid == '')
    	{
        	$dat = date("Y-m-d G-i-s");
            $com_story = addslashes($_POST['com_story']);
            $com_plot = addslashes($_POST['com_plot']);

            // Check the cover image file and if none, then use default
            $com_image = mysql_real_escape_string($_POST['com_image']);
            if(empty($com_image))
            	{
                	$com_image = "noimage.jpg";
            	}

           	// Get the writer from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_writer']) ."' AND type = 'Writer'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $writer_uid = $row['uid'];

            // Get the inker from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_inker']) ."' AND type = 'Inker'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $inker_uid = $row['uid'];

            // Get the penciler from the artist teble
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_penciler']) ."' AND type = 'Penciler'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $penciler_uid = $row['uid'];

            // Get the colorist from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_colorist']) ."' AND type = 'Colorist'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $colorist_uid = $row['uid'];

            // Get the coverartist from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_cover']) ."' AND type = 'Coverartist'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $cover_uid = $row['uid'];

            // Get the letterer from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_letterer']) ."' AND type = 'Letterer'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $letterer_uid = $row['uid'];

            // Get the series name from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_name']) ."' AND type = 'Series'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $name_uid = $row['uid'];

            // Get the comic genre from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_genre']) ."' AND type = 'Genre'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $genre_uid = $row['uid'];

            // Get the publisher name from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_publisher']) ."' AND type = 'Publisher'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $publisher_uid = $row['uid'];

            // Get the issue variation from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_variation']) ."' AND type = 'Variation'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $variation_uid = $row['uid'];

            // Get the issue format from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_format']) ."' AND type = 'Format'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $format_uid = $row['uid'];

            // Get the issue condition from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_condition']) ."' AND type = 'Condition'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $condition_uid = $row['uid'];

            // Get the comic type from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_type']) ."' AND type = 'Type'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $type_uid = $row['uid'];

            // Get the currency from the artist table
            $select = "SELECT * FROM pmc_artist WHERE name = '". $_POST['com_currency'] ."' AND type = 'Currency'";
            $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
            $row = mysql_fetch_array($data);
            $currency_uid = $row['uid'];                                   

            // Insert the new issue into the pmc_comic table
            mysql_query("INSERT INTO pmc_comic (title, story, price, part1, part2, user1, user2, image, issue, issueltr, volume, type, genre, publisher, condition, format, plot, value, date, variation, language, translator, currency, ebay, ebaylink, pubdate, qty) VALUES ('$name_uid', '$com_story', '".$_POST['com_price']."', '".$number_part."', '".$_POST['com_total']."', '".$_POST['com_user1']."', '".$_POST['com_user2']."', '".mysql_real_escape_string($_POST['com_image'])."', '". $number_part ."', '". $alpha_part ."', '".$_POST['com_volume']."', '$type_uid', '$genre_uid', '$publisher_uid', '$condition_uid', '$format_uid', '$com_plot', '".$_POST['com_value']."', '$dat', '$variation_uid', '".$_POST['com_language']."', '".mysql_real_escape_string($_POST['com_translator'])."', '$currency_uid', '".$_POST['com_ebay']."', '".mysql_real_escape_string($_POST['com_ebaylink'])."', '".$_POST['com_pubdate']."', '".$_POST['com_qty']."')");
           
            // Get the id of the newly added issue
            $select = "SELECT uid FROM pmc_comic WHERE title = '". mysql_real_escape_string($name_uid) ."' AND issue = '". $number_part ."' AND issueltr = '". $alpha_part ."' AND volume = '".$_POST['com_volume']."'";
            $data = mysql_db_query($sql['data'], $select) or die("Get ID Failed");
            $row = mysql_fetch_array($data);
            $comic_uid = $row['uid'];            
           
            // Insert the artists to the pmc_link table
            if($_POST['com_writer'] == 'Unknown Writer')
            	{
           			// Nothing will be added if no writer is selected
           		} else {
           			mysql_query("INSERT INTO pmc_link (title_id, comic_id, artist_id, type) VALUES ('$name_uid', '$comic_uid', '$writer_uid', 'Writer')");
           		}        
           
            if($_POST['com_inker'] == 'Unknown Inker')
           		{
           			// Nothing will be added if no writer is selected
           		} else {
           			mysql_query("INSERT INTO pmc_link (title_id, comic_id, artist_id, type) VALUES ('$name_uid', '$comic_uid', '$inker_uid', 'Inker')");
           		}
           
           	if($_POST['com_penciler'] == 'Unknown Penciler')
           		{
           			// Nothing will be added if no writer is selected
           		} else {
           			mysql_query("INSERT INTO pmc_link (title_id, comic_id, artist_id, type) VALUES ('$name_uid', '$comic_uid', '$penciler_uid', 'Penciler')");
           		}
           		
            if($_POST['com_letterer'] == 'Unknown Letterer')
           		{
           			// Nothing will be added if no writer is selected
           		} else {
           			mysql_query("INSERT INTO pmc_link (title_id, comic_id, artist_id, type) VALUES ('$name_uid', '$comic_uid', '$letterer_uid', 'Letterer')");
           		}
           		
            if($_POST['com_colorist'] == 'Unknown Colorist')
           		{
           			// Nothing will be added if no writer is selected
           		} else {
           			mysql_query("INSERT INTO pmc_link (title_id, comic_id, artist_id, type) VALUES ('$name_uid', '$comic_uid', '$colorist_uid', 'Colorist')");
           		}   
           		
            if($_POST['com_cover'] == 'Unknown Coverartist')
           		{
           			// Nothing will be added if no writer is selected
           		} else {
           			mysql_query("INSERT INTO pmc_link (title_id, comic_id, artist_id, type) VALUES ('$name_uid', '$comic_uid', '$cover_uid', 'Coverartist')");
           		}        
           
           	// Return to the add comic page with the same values
            header("Location: addcomic.php?a=".$_POST['com_name']."&b=".$_POST['com_issue']."&c=".$_POST['com_volume']."&d=0&e=0&f=".$_POST['com_publisher']."&g=".$_POST['com_type']."&h=".$_POST['com_genre']."&i=".$_POST['com_format']."&j=".$_POST['com_condition']."&k=".$_POST['com_price']."&l=".$_POST['com_value']."&m=".$_POST['com_writer']."&n=".$_POST['com_inker']."&o=".$_POST['com_penciler']."&p=".$_POST['com_cover']."&q=".$_POST['com_colorist']."&r=".$_POST['com_image']."&u=".$_POST['com_story']."&v=".$_POST['com_variation']."&w=".$_POST['com_user1']."&x=".$_POST['com_user2']."&y=&z=".$_POST['com_letterer']."&s=".$_POST['com_language']."&t=".$_POST['com_translator']."&cur=".$_POST['com_currency']."");
            exit;

		} else {

        	// If comic exists then show error page
        	header("Location: error.php?error=18");
        	exit;

		}

	} else {

    	// If not logged in, goto error page
    	header("Location: error.php?error=01");
    	exit;

    }

//-------------------------------------------------------------------
// Adding Multiple Issues
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addmulti")) {

     // Check if logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {    

           if($_POST['multi_end'] > '100')
           
           {
           		// Error if user adds more that the limit
     			header("Location: error.php?error=31");
     			exit;
     			
           } else {
           
           $start = $_POST['multi_start'];
		   $ending = $_POST['multi_end'];
           
           for ($i = $start; $i <= $ending; $i++) {
           
           $dat = date("Y-m-d G-i-s");           
           
           // Get the series name from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_name']) ."' AND type = 'Series'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $name_uid = $row['uid'];

           // Get the comic genre from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_genre']) ."' AND type = 'Genre'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $genre_uid = $row['uid'];

           // Get the publisher name from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_publisher']) ."' AND type = 'Publisher'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $publisher_uid = $row['uid'];
         
           // Get the issue format from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_format']) ."' AND type = 'Format'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $format_uid = $row['uid'];         

           // Get the comic type from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_type']) ."' AND type = 'Type'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $type_uid = $row['uid'];

           //Get the currency from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". $_POST['com_currency'] ."' AND type = 'Currency'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $currency_uid = $row['uid'];
           
           // Checks if the issue already exicists and then adds the issue
           $checkissue = "SELECT * FROM pmc_comic WHERE title = '$name_uid' AND issue = '$i' AND volume = '".$_POST['com_volume']."'";
           $data = mysql_db_query($sql['data'], $checkissue) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $chkissue = $row['uid'];
           
           if($chkissue == '')
           	{
				// Insert the new issues into the database
           		mysql_query("INSERT INTO pmc_comic (title, story, price, image, issue, volume, type, genre, publisher, format, value, date, language, translator, currency, ebay, qty) VALUES ('$name_uid', 'No Story Title', '".$_POST['com_price']."', 'noimage.jpg', '$i', '".$_POST['com_volume']."', '$type_uid', '$genre_uid', '$publisher_uid', '$format_uid', '".$_POST['com_value']."', '$dat', '".$_POST['com_language']."', '".mysql_real_escape_string($_POST['com_translator'])."', '$currency_uid', 'no', '1')");
           	} else {
           		// Nothing is added and the loop is continued
           	}
                    
           }
           
        // Return to index
        header("Location: index.php");
        exit;
        
        }

     } else {

     // If not logged in, goto error page
     header("Location: error.php?error=01");
     exit;

     }

//-------------------------------------------------------------------
// Edit a comic function
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "editcomic")) {

	 if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
        {
           $dat = date("Y-m-d G-i-s");
           $com_story = addslashes($_POST['com_story']);
           $com_plot = addslashes($_POST['com_plot']);    
           
           // This will explode the issue number into numeric and alpha
    		$issue_number = $_POST['com_issue'];
			preg_match('/^(\d+)(\w*)?$/', $issue_number, $m);
  			$number_part = $m[1];
  			$alpha_part = $m[2];        

           // Get the series name from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_name']) ."' AND type = 'Series'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $name_uid = $row['uid'];

           // Get the comic genre from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_genre']) ."' AND type = 'Genre'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $genre_uid = $row['uid'];

           // Get the publisher name from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_publisher']) ."' AND type = 'Publisher'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $publisher_uid = $row['uid'];

           // Get the issue variation from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_variation']) ."' AND type = 'Variation'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $variation_uid = $row['uid'];

           // Get the issue format from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_format']) ."' AND type = 'Format'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $format_uid = $row['uid'];

           // Get the issue condition from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_condition']) ."' AND type = 'Condition'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $condition_uid = $row['uid'];

           // Get the comic type from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['com_type']) ."' AND type = 'Type'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $type_uid = $row['uid'];

           //Get the currency from the artist table
           $select = "SELECT * FROM pmc_artist WHERE name = '". $_POST['com_currency'] ."' AND type = 'Currency'";
           $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
           $row = mysql_fetch_array($data);
           $currency_uid = $row['uid'];

           $id = $_GET['uid'];

           mysql_query("UPDATE pmc_comic SET title = '$name_uid', story = '$com_story', price = '".$_POST['com_price']."', part1 = '".$_POST['com_issue']."', part2 = '".$_POST['com_total']."', user1 = '".$_POST['com_user1']."', user2 = '".$_POST['com_user2']."', image = '".$_POST['com_image']."', issue = '".$number_part."', volume = '".$_POST['com_volume']."', type = '$type_uid', genre = '$genre_uid', publisher = '$publisher_uid', condition = '$condition_uid', format = '$format_uid', plot = '$com_plot', value = '".$_POST['com_value']."', date = '$dat', variation = '$variation_uid', language = '".$_POST['com_language']."', translator = '".mysql_real_escape_string($_POST['com_translator'])."', ebay = '".$_POST['com_ebay']."', ebaylink = '".mysql_real_escape_string($_POST['com_ebaylink'])."', pubdate = '".$_POST['com_pubdate']."', qty = '".$_POST['com_qty']."', currency = '$currency_uid', issueltr = '$alpha_part' WHERE uid = '$id'");
           header("Location: comic.php/$id");
           exit;

        } else {

        // Login failed
        header("Location: error.php?error=01");
        exit;

     }

//-------------------------------------------------------------------
// Adding a new artist function
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addartist")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

     if(empty($_POST['art_name']))
        {
           header("Location: error.php?error=03");
           exit;
        }

     // If no artist type has been selected
     if((!isset($_POST['check_writer'])) AND (!isset($_POST['check_inker'])) AND (!isset($_POST['check_penciler'])) AND (!isset($_POST['check_colorist'])) AND (!isset($_POST['check_letterer'])) AND (!isset($_POST['check_cover'])))
        {
           header("Location: error.php?error=23");
           exit;
        }

     require("config/config.php");

     mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
     mysql_select_db($sql['data']) or die("Unable to find DB");

     if($_POST['check_writer'] == "1")
        {           
        	// CHECK IF ARTIST EXCISTS IN DATABASE
           	$getcomic = "SELECT * FROM `pmc_artist` WHERE name = '". $_POST['art_name'] ."' AND type = 'Writer'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid']; 
           	
           	if($uid == '')
           		{
           			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Writer')");
           		} else {
           			// NO ARTIST IS ADDED
           		}
        }
     if($_POST['check_inker'] == "1")
        {
        	// CHECK IF ARTIST EXCISTS IN DATABASE
           	$getcomic = "SELECT * FROM `pmc_artist` WHERE name = '". $_POST['art_name'] ."' AND type = 'Inker'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid']; 
           	
           	if($uid == '')
           		{
           			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Inker')");
           		} else {
           			// NO ARTIST ADDED
           		}
        }
     if($_POST['check_penciler'] == "1")
        {
        	// CHECK IF ARTIST EXCISTS IN DATABASE
           	$getcomic = "SELECT * FROM `pmc_artist` WHERE name = '". $_POST['art_name'] ."' AND type = 'Penciler'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid']; 
           	
           	if($uid == '')
           		{
           			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Penciler')");
           		} else {
           			// NO COMIC ADDED
           		}
        }
     if($_POST['check_colorist'] == "1")
        {
        	// CHECK IF ARTIST EXCISTS IN DATABASE
           	$getcomic = "SELECT * FROM `pmc_artist` WHERE name = '". $_POST['art_name'] ."' AND type = 'Colorist'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid']; 
           	
           	if($uid == '')
           		{
           			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Colorist')");
           		} else {
           			// NO COMIC ADDED
           		}
        }
     if($_POST['check_letterer'] == "1")
        {
           // CHECK IF ARTIST EXCISTS IN DATABASE
           	$getcomic = "SELECT * FROM `pmc_artist` WHERE name = '". $_POST['art_name'] ."' AND type = 'Letterer'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid']; 
           	
           	if($uid == '')
           		{
           			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Letterer')");
           		} else {
           			// NO COMIC ADDED
           		}
        }
     if($_POST['check_cover'] == "1")
        {
           // CHECK IF ARTIST EXCISTS IN DATABASE
           	$getcomic = "SELECT * FROM `pmc_artist` WHERE name = '". $_POST['art_name'] ."' AND type = 'Coverartist'";
     		$datas = mysql_db_query($sql['data'], $getcomic) or die("Select Failed!");
     		$row = mysql_fetch_array($datas);
     		$uid = $row['uid']; 
           	
           	if($uid == '')
           		{
           			mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Coverartist')");
           		} else {
           			// NO COMIC ADDED
           		}
        }

     header("Location: addartist.php");
     exit;

     } else {

     // Login failed
     header("Location: error.php?error=01");
     exit;

     }

//-------------------------------------------------------------------
//
// FUNCTION :: ADD NEW SERIES
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addseries")) {

	if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
    	{
        	// Check if the user has written anything in the name field        	
        	if(empty($_POST['art_name']))
            	{
                	header("Location: error.php?error=03");
                 	exit;
              	}
            
            // Make sure the year field is only digits
            if(!ereg("[0-9]", $_POST['art_year']))
            	{
            		echo 'must user DIGITS';
            		exit;
            	}
            
            // Check the year field, make sure it is only 4 digits long  
           	if(strlen($_POST['art_year']) < 4) 
           		{
             		echo '4 digits minimum';
             		exit;
           		}
                   
           	// A query to check if the series already exists in database
           	$findseries = mysql_query("SELECT uid FROM `pmc_artist` WHERE name = '".mysql_real_escape_string($_POST['art_name'])."' and type = 'Series'") or die("Finding Series Failed!");
      		$getseries = mysql_fetch_assoc($findseries);
           	$uid = $getseries['uid'];
           	
           	// ABOVE COMMAND MAY CAUSE PROBLEMS ON SOME SYSTEMS??
           	//$getartist = "SELECT uid FROM `pmc_artist` WHERE name = '".mysql_real_escape_string($_POST['art_name'])."' and type = 'Series'";
           	//$datas = mysql_db_query($sql['data'], $getartist) or die("Select Failed!");
           	//$row = mysql_fetch_array($datas);
           	//$uid = $row['uid'];

           	// If link is empty (http://www.) don't add any link to the db
           	if(($_POST['art_link'] == "http://www.") OR ($_POST['art_link'] == ""))
            	{
                	$artlink = "";
              	} else {
                 	$artlink = mysql_real_escape_string($_POST['art_link']);
              	}

           	// If series does not exists, add it to the database
           	if($uid == '')
              	{
                 	// Insert comic and go back to the add artist page
                 	mysql_query("INSERT INTO pmc_artist (name, type, link, year) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Series', '$artlink', '". $_POST['art_year'] ."')");
                 	header("Location: addartist.php");
                 	exit;
              	
              	} else {
              		
              		// If series excist show error page
              		header("Location: error.php?error=20");
              		exit;
              	}

        } else {

        	// Login failed
        	header("Location: error.php?error=01");
        	exit;

     	}

//-------------------------------------------------------------------
// Adding a new publisher
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addpublisher")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
        {
           if(empty($_POST['art_name']))
              {
                 header("Location: error.php?error=03");
                 exit;
              }

           // A query to check if the publisher already existss
           $getartist = "SELECT * FROM `pmc_artist` WHERE name = '". mysql_real_escape_string($_POST['art_name']) ."' and type = 'Publisher'";
           $datas = mysql_db_query($sql['data'], $getartist) or die("Select Failed!");
           $row = mysql_fetch_array($datas);
           $uid = $row['uid'];

           //If link is empty (http://www.) don't add any link to the db
           if(($_POST['art_link'] == "http://www.") OR ($_POST['art_link'] == ""))
              {
                 $artlink = "";
              } else {
                 $artlink = mysql_real_escape_string($_POST['art_link']);
              }

           // If publisher does not exists, add it to the database
           if($uid == '')
              {
                 mysql_query("INSERT INTO pmc_artist (name, type, link) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Publisher', '$artlink')");
                 header("Location: addartist.php");
                 exit;

              } else {

              // If the publisher exists
              header("Location: error.php?error=20");
              exit;

              }

        } else {

        // Login failed
        header("Location: error.php?error=01");
        exit;

     }

//-------------------------------------------------------------------
// Adding a new genre
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addgenre")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
        {
           if(empty($_POST['art_name']))
              {
                 header("Location: error.php?error=03");
                 exit;
              }

           // A query to check if the comic already existss
           $getartist = "SELECT * FROM `pmc_artist` WHERE name = '". mysql_real_escape_string($_POST['art_name']) ."' and type = 'Genre'";
           $datas = mysql_db_query($sql['data'], $getartist) or die("Select Failed!");
           $row = mysql_fetch_array($datas);
           $uid = $row['uid'];

           // If genre does not exists, add it to the database
           if($uid == '')
              {
                 mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['art_name']) ."', 'Genre')");
                 header("Location: addartist.php");
                 exit;
              } else {

              // If the genre existss
              header("Location: error.php?error=20");
              exit;

              }

        } else {

        // Login failed
        header("Location: error.php?error=01");
        exit;

     }

//-------------------------------------------------------------------
// Edit an artist function
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "editartist")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
        {
           if(empty($_POST['art_name']))
              {
                 header("Location: error.php?error=03");
                 exit;
              }

           //if the new name is exists send an error message
           $result = mysql_query("SELECT uid FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['art_name']) ."' AND type = '". $_POST['art_hidden2'] ."' AND link = '". mysql_real_escape_string($_POST['art_link']) ."' AND year = '". mysql_real_escape_string($_POST['art_year']) ."'");
           $row = mysql_fetch_array($result);
           if($row['uid'] != '') {

           header("Location: error.php?error=24");
           exit;
        }

     //If link is empty (http://www.) don't add any link to the db
     if(($_POST['art_link'] == "http://www.") OR ($_POST['art_link'] == ""))
        {
           $artlink = "";
        } else {
           $artlink = mysql_real_escape_string($_POST['art_link']);
        }

     if($_POST['art_hidden1'] == 'Unknown Inker' or $_POST['art_hidden1'] == 'Unknown Publisher' or $_POST['art_hidden1'] == 'Unknown Series' or $_POST['art_hidden1'] == 'Unknown Genre' or $_POST['art_hidden1'] == 'Unknown Penciler' or $_POST['art_hidden1'] == 'Unknown Writer' or $_POST['art_hidden1'] == 'Unknown Colorist' or $_POST['art_hidden1'] == 'Unknown Coverartist' or $_POST['art_hidden1'] == 'Unknown Letterer')
        {
           header("Location: error.php?error=17");
           exit;
        } else {
           if($_POST['art_hidden2'] == "Series")
              {
                 mysql_query("UPDATE pmc_artist SET name = '". mysql_real_escape_string($_POST['art_name']) ."', link = '$artlink', year = '". mysql_real_escape_string($_POST['art_year']) ."' WHERE name = '". mysql_real_escape_string($_POST['art_hidden1']) ."'");
              } elseif ($_POST['art_hidden2'] == "Publisher") {
              	mysql_query("UPDATE pmc_artist SET name = '". mysql_real_escape_string($_POST['art_name']) ."', link = '$artlink' WHERE name = '". mysql_real_escape_string($_POST['art_hidden1']) ."'");
              } else {
                 mysql_query("UPDATE pmc_artist SET name = '". mysql_real_escape_string($_POST['art_name']) ."' WHERE name = '". mysql_real_escape_string($_POST['art_hidden1']) ."'");
              }

           if($_POST['art_hidden2'] == "Series")
           {
           header("Location: series.php/".$_GET['a']."");
           exit;
           } else {
           header("Location: browse.php/".$_POST['art_hidden2']."/".$_GET['a']."");
           exit;
           }
        }

     } else {

     // Login failed
     header("Location: error.php?error=01");
     exit;

     }

//-------------------------------------------------------------------
//
// FUNCTION :: DELETE AN ISSUE FROM DB
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delcomic")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
        {
           // Delete the comic from pmc_comic table
           mysql_query("DELETE FROM pmc_comic WHERE uid = '".$_GET['uid']."'");
           
           // Delete artist links related to this issue
           mysql_query("DELETE FROM pmc_link WHERE comic_id = '". $_GET['uid'] ."'");
           
           // Delete any loans related to this issue
           mysql_query("DELETE FROM pmc_loan WHERE comicid = '". $_GET['uid'] ."'");
           
           // Go back to index after delete
           header("Location: confirm.php?msg=04&file=index");
           exit;
           
        } else {
           
           // Login failed
           header("Location: error.php?error=01");
           exit;
        }
        
//-------------------------------------------------------------------
//
// FUNCTION :: LOAN ISSUE TO MATES
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "loancomic")) {

	if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
    	{
        	// Error massage if field is left emty
        	if(empty($_POST['loan_name']))
            	{                	
                	header("Location: error.php?error=03");
                 	exit;
              	}

           	// Loan this comic and insert new line to loan table           
           	mysql_query("INSERT INTO pmc_loan (itemid, titleid, comicid, date, due, name, notes ) VALUES ('', '". $_POST['titleid'] ."', '". $_POST['loan_uid'] ."', '". $_POST['loan_date'] ."', '". $_POST['loan_due'] ."', '". mysql_real_escape_string($_POST['loan_name']) ."', '". mysql_real_escape_string($_POST['loan_notes']) ."')");
           	
           	// Update the issue record and set loan to YES
           	mysql_query("UPDATE pmc_comic SET loan = 'yes' WHERE uid = '". $_POST['loan_uid'] ."'");
           
           	// Go back to issue detail page
           	header("Location: comic.php/". $_POST['loan_uid'] ."");
           	exit;
           
        } else {
           
           // Login failed
           header("Location: error.php?error=01");
           exit;
        }

//-------------------------------------------------------------------
//
// FUNCTION :: MARK ISSUE AS FAVORITE
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "favcomic")) {

	if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
    	{
        	// Update the issue record and set fav to YES
           	mysql_query("UPDATE pmc_comic SET fav = 'yes' WHERE uid = '". $_GET['id'] ."'");
           
           	// Go back to issue detail page
           	header("Location: comic.php/". $_GET['id'] ."");
           	exit;
           
        } else {
           
           // Login failed
           header("Location: error.php?error=01");
           exit;
        }
        
//-------------------------------------------------------------------
// Deleting an item
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delete")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {
       
       $select = "SELECT * FROM pmc_artist WHERE uid = '". $_GET['uid'] ."'";
       $data = mysql_db_query($sql['data'], $select) or die("Select Failed!");
       $row = mysql_fetch_array($data);
       $artname = $row['name'];

       if($artname == 'Unknown Inker' or $artname == 'Unknown Publisher' or $artname == 'Unknown Series' or $artname == 'Unknown Genre' or $artname == 'Unknown Penciler' or $artname == 'Unknown Writer' or $artname == 'Unknown Colorist' or $artname == 'Unknown Coverartist' or $artname == 'Unknown Letterer' or $artname == 'One Shot') {
         header("Location: error.php?error=15");
         exit;
       } else {
            
         // Create a new template object
         $tpl = new TemplatePower("themes/$themes/tpl/delete.tpl");

         // Prepare the template
         $tpl->prepare();

		// GET THE MENU AND LANGUAGE FILES
		include("./lang/$language/dialog.lang.php");

         // Assign needed values
         $tpl->assignGlobal("theme", $themes);
         $tpl->assign("imgfolder", "themes/$themes/img");
         $tpl->assign("version", $version);
         $tpl->assign("type", $_GET['type']);
         $tpl->assign("delcmd", $_GET['del']);
         $tpl->assign("id", $_GET['uid']);
         $tpl->assign("a", $_GET['a']);
         $tpl->assign("b", $_GET['b']);         

 if($_GET['a'] == 'Series')
       		{
       			
       			$tpl->assign("error", $lang_delete_msg_01);
       			
       		} else {
       			
       			$tpl->assign("error", $lang_delete_msg_02);
       			
       		}
       		
         // Print the result
         $tpl->printToScreen();
                
       }
     } else {
       // Login failed
       header("Location: error.php?error=01");
       exit;
     }

//-------------------------------------------------------------------
// Deleting an item multi
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delmore")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {                    

		foreach ($_POST['list_delete'] as $uid)
		// Delete the comic from pmc_comic table
        mysql_query("DELETE FROM pmc_comic WHERE uid = '". $uid ."'");
           
        // Delete artist links related to this issue
        mysql_query("DELETE FROM pmc_link WHERE comic_id = '". $uid ."'");
           
        // Delete any loans related to this issue
        mysql_query("DELETE FROM pmc_loan WHERE comicid = '". $uid ."'");
           
        // Go back to index after delete
        header("Location: confirm.php?msg=04&file=index");
        exit;	
     
     } else {
       // Login failed
       header("Location: error.php?error=01");
       exit;
     }
     
//-------------------------------------------------------------------
// Deleting an issue
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delete_series")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

         // Create a new template object
         $tpl = new TemplatePower("themes/$themes/tpl/delete.tpl");

         // Prepare the template
         $tpl->prepare();

		// GET THE MENU AND LANGUAGE FILES
		include("./lang/$language/dialog.lang.php");
		
         // Assign needed values
         $tpl->assignGlobal("theme", $themes);
         $tpl->assign("imgfolder", "themes/$themes/img");
         $tpl->assign("version", $version);
         $tpl->assign("type", $_GET['type']);
         $tpl->assign("delcmd", $_GET['del']);
         $tpl->assign("id", $_GET['uid']);
         $tpl->assign("a", $_GET['a']);
         $tpl->assign("b", $_GET['b']);
         
         $tpl->assign("error", $lang_delete_msg_03);

         // Print the result
         $tpl->printToScreen();

     } else {
       // Login failed
       header("Location: error.php?error=01");
       exit;
     }

//-------------------------------------------------------------------
//
// FUNCTION :: DELETE ARTIST LINK FROM COMIC
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delartlink")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

       mysql_query("DELETE FROM pmc_link WHERE artist_id = '". $_GET['id'] ."' AND type = '". $_GET['type'] ."' AND comic_id = '". $_GET['comicid'] ."'");
       
       header("Location: editcomic.php?uid=". $_GET['comicid'] ."");
       exit;

     } else {
       // Login failed
       header("Location: editcomic.php?error=01");
       exit;
     }

//-------------------------------------------------------------------
//
// FUNCTION :: ADD ARTIST LINK TO COMIC
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "addartistlink")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {

       // Get the writer from the artist table
       $select = "SELECT * FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['comic_artist']) ."' AND type = '". $_POST['comictype'] ."'";
       $data = mysql_db_query($sql['data'], $select) or die("Select Artist ID Failed!");
       $row = mysql_fetch_array($data);
       $artist_uid = $row['uid'];
            
       mysql_query("INSERT INTO pmc_link (comic_id, artist_id, title_id, type) VALUES ('". $_POST['comicid'] ."', '$artist_uid', '". $_POST['titleid'] ."', '". $_POST['comictype'] ."')");
       
       header("Location: editcomic.php?uid=". $_POST['comicid'] ."");
       exit;

     } else {
       // Login failed
       header("Location: editcomic.php?error=01");
       exit;
     }
          
//-------------------------------------------------------------------
//
// FUNCTION :: DELETING AN ARTIST
//
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delartist")) {

	if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
		{		
			       		
       		// If type is Series then run command or else run other command
       		if($_GET['a'] == "Series")
       			{
       				// Delete the artist from pmc_artist table       		      
       				mysql_query("DELETE FROM pmc_artist WHERE uid = '". $_GET['uid'] ."'");
       				
       				// Delete all issues with the choosen comic title
       				mysql_query("DELETE FROM pmc_comic WHERE title = '". $_GET['uid'] ."'");
       				
       				// Delete the artist links related to this comic
       				mysql_query("DELETE FROM pmc_link WHERE title_id = '". $_GET['uid'] ."'");
       				
       				// Delete all loans related to this comic
       				mysql_query("DELETE FROM pmc_loan WHERE titleid = '". $_GET['uid'] ."'");
       				
       				// Return To Series View
       				header("Location: series.php/". $_GET['b'] ."");
       				exit;
       				
       			} else {
       				
       				// Delete the artist from pmc_artist table       		      
       				mysql_query("DELETE FROM pmc_artist WHERE uid = '". $_GET['uid'] ."'");
       		
       				// Delete the artist links from pmc_link table
       				mysql_query("DELETE FROM pmc_link WHERE artist_id = '". $_GET['uid'] ."' AND type = '". $_GET['a'] ."'");
       		
       				// Return To Browse View
       				header("Location: browse.php/". $_GET['a'] ."/". $_GET['b'] ."");
       				exit;
       			}
       			
     		} else {
       
       		// Login failed
       		header("Location: error.php?error=01");
       		exit;
     	}

//-------------------------------------------------------------------
// Upload cover image
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "upload")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {
       // Configuration
       $uploaddir = "image";                        // Upload directory (0777)
       $allowed_ext = "jpg, jpeg, gif, png, JPG";   // Allowed extensions
       $max_size = $imgsize;                        // 100000 is the same as 100kb
       $max_height = $imgheight;                    // This is in pixels - Leave this field empty if you don't want to upload images
       $max_width = $imgwidth;                      // This is in pixels - Leave this field empty if you don't want to upload images

       // Extension check
       $extension = pathinfo($_FILES['file']['name']);
       $extension = $extension[extension];
       $allowed_paths = explode(", ", $allowed_ext);

       for($i = 0; $i < count($allowed_paths); $i++) {
         if ($allowed_paths[$i] == "$extension") {
           $ok = "1";
         }
       }

       if ($ok == "1") {
         // Check file size
         if($_FILES['file']['size'] > $max_size) {
           header("Location: error.php?error=26");
           exit;
         }

         // Check height & width
         if ($max_width && $max_height) {
           list($width, $height, $type, $w) = getimagesize($_FILES['file']['tmp_name']);
           if($width > $max_width || $height > $max_height) {
             // Check input, and show error page
             header("Location: error.php?error=09");
             exit;
           }
         }

         //if filename is exists send an error message
         if(file_exists($uploaddir.'/'.$_FILES['file']['name'])) {
           header("Location: error.php?error=25");
           exit;
         }

         // The Upload Part
         if(is_uploaded_file($_FILES['file']['tmp_name'])) {
           move_uploaded_file($_FILES['file']['tmp_name'],$uploaddir.'/'.$_FILES['file']['name']);
         }

         // Upload done, and return to tools page
         header("Location: tools.php");
         exit;
       } else {
         // Upload failed, show error page
         header("Location: error.php?error=10");
         exit;
       }
     } else {
       // Login failed
       header("Location: error.php?error=01");
       exit;
     }

//-------------------------------------------------------------------
// Import comic information from .pmc file
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "import")) {

     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       
     	print 'A new import tool is under development';

     } else {

     // Login failed
     header("Location: error.php?error=01");
     exit;

     }

//-------------------------------------------------------------------
// ADMINISTRATION - Adding a new user
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "adduser")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         if(empty($_POST['usr_user'])) {
           header("Location: admin.php?action=adduser&error=You must add a user name!");
           exit;
         }

         //Check the username and if it is exists send an error message
         $result = mysql_query("SELECT ID FROM pmc_user WHERE name = '". mysql_real_escape_string($_POST['usr_user']) ."'");
         $row = mysql_fetch_array($result);
         if($row['ID'] != '') {
           header("Location: admin.php?action=adduser&error=The Username is exists!");
           exit;
         }

         if(empty($_POST['usr_pass1'])) {
           header("Location: admin.php?action=adduser&error=The Enter Password field is empty!");
           exit;
         }

         if (strlen($_POST['usr_pass1']) < 5) {
           header("Location: admin.php?action=adduser&error=The password is too short (Minimum = 5)");
           exit;
         }

         if(empty($_POST['usr_pass2'])) {
           header("Location: admin.php?action=adduser&error=The ReEnter Password field is empty!");
           exit;
         }

         if ($_POST['usr_pass1'] != $_POST['usr_pass2']) {
           header("Location: admin.php?action=adduser&error=The Password fields did not match");
           exit;
         }

         if (!ereg("^[[:alnum:]_-]+$", $_POST['usr_pass1'])) {
           header("Location: admin.php?action=adduser&error=Password contains a character besides (a-z,0-9,_ ,-)");
           exit;
         }

         mysql_query("INSERT INTO pmc_user (name, realname, password, email) VALUES ('". mysql_real_escape_string($_POST['usr_user']) ."', '". mysql_real_escape_string($_POST['usr_name']) ."', md5('". mysql_real_escape_string($_POST['usr_pass1']) ."'), '". mysql_real_escape_string($_POST['usr_mail']) ."')");
         header("Location: admin.php?action=users");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Edit User Information
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "edituser")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         $result = mysql_query("SELECT ID FROM pmc_user WHERE name = '". mysql_real_escape_string($_POST['usr_user']) ."' AND ID != '". $_POST['usr_id'] ."'");
         $row = mysql_fetch_array($result);
         if($row['ID'] != '') {
           header("Location: error.php?error=27");
           exit;
         }

         if(empty($_POST['usr_pass1'])) {
           mysql_query("UPDATE pmc_user SET name = '". mysql_real_escape_string($_POST['usr_user']) ."', realname = '". mysql_real_escape_string($_POST['usr_name']) ."', email = '". mysql_real_escape_string($_POST['usr_mail']) ."' WHERE ID = '". $_POST['usr_id'] ."'");
           header("Location: admin.php?action=users");
           exit;
         } else  {
           if ($_POST['usr_pass1'] != $_POST['usr_pass2']) {
             header("Location: error.php?error=04");
             exit;
             }

           if (strlen($_POST['usr_pass1']) < 5) {
             header("Location: error.php?error=28");
             exit;
           }

           if (!ereg("^[[:alnum:]_-]+$", $_POST['usr_pass1'])) {
             header("Location: error.php?error=29");
             exit;
           }

           mysql_query("UPDATE pmc_user SET name = '". mysql_real_escape_string($_POST['usr_user']) ."', realname = '". mysql_real_escape_string($_POST['usr_name']) ."', email = '". mysql_real_escape_string($_POST['usr_mail']) ."', password = md5('". mysql_real_escape_string($_POST['usr_pass1']) ."') WHERE ID = '". $_POST['usr_id'] ."'");
           header("Location: admin.php");
           exit;
         }
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Edit Loan Information
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "editloan")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {        

           mysql_query("UPDATE pmc_loan SET name = '". mysql_real_escape_string($_POST['loan_name']) ."', date = '". $_POST['loan_date'] ."', due = '". $_POST['loan_due'] ."', notes = '". mysql_real_escape_string($_POST['loan_notes']) ."' WHERE itemid = '". $_POST['loan_uid'] ."'");
           header("Location: admin.php?action=loans");
           exit;
           
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }
     
//-------------------------------------------------------------------
// ADMINISTRATION - Edit personal options
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "adminoption")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"]) {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {

         if(empty($_POST['admin_pass1'])) {
           mysql_query("UPDATE `pmc_user` SET realname = '". mysql_real_escape_string($_POST['admin_name']) ."', email = '". mysql_real_escape_string($_POST['admin_mail']) ."' WHERE `name` = 'Admin';");
         } else {
           if ($_POST['admin_pass1'] != $_POST['admin_pass2']) {
             header("Location: admin.php?action=personal&error=The Password fields did not match");
             exit;
           }

           if (strlen($_POST['admin_pass1']) < 5) {
             header("Location: admin.php?action=personal&error=The password is too short (Minimum = 5)");
             exit;
           }

           if (!ereg("^[[:alnum:]_-]+$", $_POST['admin_pass1'])) {
             header("Location: admin.php?action=personal&error=Password contains a character besides (a-z,0-9,_ ,-)");
             exit;
           }

           mysql_query("UPDATE `pmc_user` SET `password` = md5('". mysql_real_escape_string($_POST['admin_pass1']) ."'), realname = '". mysql_real_escape_string($_POST['admin_name']) ."', email = '". mysql_real_escape_string($_POST['admin_mail']) ."' WHERE `name` = 'Admin';");
         }
         
         header("Location: confirm.php?msg=02&file=admin");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Deleting an admin artist function
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delart")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         mysql_query("DELETE FROM pmc_artist WHERE uid = '". $_GET['uid'] ."'");
         header("Location: admin.php?action=artist&type=".$_GET['a']."");
         exit;
       } else {
         // Show error page if your not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - MySQL Database Backup
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "db_backup")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {

       // Include config file
  include("config/config.php");

  $dat = date("Y-m-d");

  // Connect to MySQL and the database
  mysql_connect($sql['host'],$sql['user'],$sql['pass']) or die("Unable to connect to SQL server");
  mysql_select_db($sql['data']) or die("Unable to find DB");

  $tables = array("pmc_artist", "pmc_comic", "pmc_user");
  @set_time_limit(0);

  $values  = "# PhpMyComic DB Backup"."\n";
  $values .= "#-----------------------------------"."\n";
  $values .= "# Backup Date: $dat"."\n";
  $values .= "# Current PMC Version: $version"."\n";
  $values .= "#-----------------------------------"."\n";
  $values .= "\n"."\n";

  foreach($tables as $tablename)
  {
    $result = mysql_query("SELECT * FROM  $tablename");
    $fields_cnt   = mysql_num_fields($result);
    while ($row = mysql_fetch_array($result))
    {
      $tvalues = 'INSERT INTO ' . $tablename  . ' VALUES (';
      for ($j = 0; $j < $fields_cnt; $j++)
      {
        if (!isset($row[$j])) {
          $tvalues .= ' NULL, ';
        } else if ($row[$j] == '0' || $row[$j] != '') {
            $type = mysql_field_type($result, $j);
            if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' || $type == 'bigint'  ||$type == 'timestamp') {
              // a number
              $tvalues .= $row[$j] . ', ';
            } else {
              // a string
              $dummy  = '';
              $srcstr = $row[$j];
              for ($xx = 0; $xx < strlen($srcstr); $xx++)
              {
                $yy = strlen($dummy);
                if ($srcstr[$xx] == '\\')   $dummy .= '\\\\';
                if ($srcstr[$xx] == '\'')   $dummy .= '\\\'';
                if ($srcstr[$xx] == "\x00") $dummy .= '\0';
                if ($srcstr[$xx] == "\x0a") $dummy .= '\n';
                if ($srcstr[$xx] == "\x0d") $dummy .= '\r';
                if ($srcstr[$xx] == "\x1a") $dummy .= '\Z';
                if (strlen($dummy) == $yy)  $dummy .= $srcstr[$xx];
              }
              $tvalues .= "'" . $dummy . "', ";
            }
          } else {
            $tvalues .= "'', ";
          }
        }
        $tvalues = ereg_replace(', $', '', $tvalues);
        $tvalues .= ");"."\n";
        $values .= $tvalues;
      }
      mysql_free_result($result);
    }


  $FileName = "phpmycomic_".$dat.".sql";

  // Open file for writing
  $fp = @fopen("backup/$FileName","w") or die("Could not open file");

  // Write the config file
  $numBytes = @fwrite($fp, $values) or die("Could not write to file");

  // Close the config file
  @fclose($fp);

  header("Location: admin.php?action=backup");
  exit;



       } else {
         // Show error page if your not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Edit system configurations
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "adminsystem")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         $values = "<?\r\n\r\n";
         $values .= "// PhpMyComic System Configurations\r\n\r\n";
         $values .= "\$sql['host']       = '". $_POST['admin_host'] ."';\r\n";
         $values .= "\$sql['user']       = '". $_POST['admin_user'] ."';\r\n";
         $values .= "\$sql['pass']       = '". $_POST['admin_pass'] ."';\r\n";
         $values .= "\$sql['data']       = '". $_POST['admin_data'] ."';\r\n\r\n";
         $values .= "\$themes            = '". $_POST['admin_theme'] ."';\r\n";
         $values .= "\$install           = \"$install\";\r\n";
         $values .= "\$dateoption        = '". $_POST['admin_time'] ."';\r\n";
         $values .= "\$sitetitle         = '". $_POST['admin_sitetitle'] ."';\r\n";
         $values .= "\$siteurl           = '". $_POST['admin_siteurl'] ."';\r\n";
         $values .= "\$language          = '". $_POST['admin_language'] ."';\r\n";
         $values .= "\$pdfenable         = '". $_POST['admin_pdf'] ."';\r\n";
         $values .= "\$printenable       = '". $_POST['admin_print'] ."';\r\n";
         $values .= "\$loanenable		 = '". $_POST['admin_loan'] ."';\r\n";
         $values .= "\$rssenable		 = '". $_POST['admin_rss'] ."';\r\n";
         $values .= "\$favenable		 = '". $_POST['admin_fav'] ."';\r\n\r\n";
         $values .= "\$imgwidth          = '". $_POST['admin_width'] ."';\r\n";
         $values .= "\$imgheight         = '". $_POST['admin_height'] ."';\r\n";
         $values .= "\$imgsize           = '". $_POST['admin_size'] ."';\r\n\r\n";
         $values .= "\$version           = \"$version\";\r\n";
         $values .= "\$vername           = \"$vername\";\r\n\r\n";
         $values .= "\$statsenable		 = \"$statsenable\";\r\n";
         $values .= "\$statstype		 = \"$statstype\";\r\n";
         $values .= "\$listtype			 = \"$listtype\";\r\n";
         $values .= "\$rownumber		 = \"$rownumber\";\r\n";
         $values .= "\$paginate		 	 = '". $_POST['admin_paginate'] ."';\r\n\r\n";
         $values .= "?>";

         // Open file for writing
         $fp = @fopen("config/config.php","w") or die("Could not open file");

         // Write the config file
         $numBytes = @fwrite($fp, $values) or die("Could not write to file");

         // Close the config file
         @fclose($fp);

         header("Location: confirm.php?msg=01&file=admin");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
         }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Edit front page configurations
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "adminindex")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         
         $values = "<?\r\n\r\n";
         $values .= "// PhpMyComic System Configurations\r\n\r\n";
         $values .= "\$sql['host']       = '". $sql['host'] ."';\r\n";
         $values .= "\$sql['user']       = '". $sql['user'] ."';\r\n";
         $values .= "\$sql['pass']       = '". $sql['pass'] ."';\r\n";
         $values .= "\$sql['data']       = '". $sql['data'] ."';\r\n\r\n";
         $values .= "\$themes            = \"$themes\";\r\n";
         $values .= "\$install           = \"$install\";\r\n";
         $values .= "\$dateoption        = \"$dateoption\";\r\n";
         $values .= "\$sitetitle         = \"$sitetitle\";\r\n";
         $values .= "\$siteurl           = \"$siteurl\";\r\n";
         $values .= "\$language          = \"$language\";\r\n";
         $values .= "\$pdfenable         = \"$pdfenable\";\r\n";
         $values .= "\$printenable       = \"$printenable\";\r\n";
         $values .= "\$loanenable		 = \"$loanenable\";\r\n";
         $values .= "\$rssenable		 = \"$rssenable\";\r\n";
         $values .= "\$favenable		 = \"$favenable\";\r\n\r\n";
         $values .= "\$imgwidth          = \"$imgwidth\";\r\n";
         $values .= "\$imgheight         = \"$imgheight\";\r\n";
         $values .= "\$imgsize           = \"$imgsize\";\r\n\r\n";
         $values .= "\$version           = \"$version\";\r\n";
         $values .= "\$vername           = \"$vername\";\r\n\r\n";
         $values .= "\$statsenable		 = '". $_POST['admin_enable'] ."';\r\n";
         $values .= "\$statstype		 = '". $_POST['admin_stats'] ."';\r\n";
         $values .= "\$listtype			 = '". $_POST['admin_fav'] ."';\r\n";
         $values .= "\$rownumber		 = '". $_POST['admin_numlines'] ."';\r\n";
         $values .= "\$paginate          = \"$paginate\";\r\n\r\n";
         $values .= "?>";
         
         // Open file for writing
         $fp = @fopen("config/config.php","w") or die("Could not open file");

         // Write the config file
         $numBytes = @fwrite($fp, $values) or die("Could not write to file");

         // Close the config file
         @fclose($fp);

         header("Location: confirm.php?msg=01&file=admin");
         exit;
         
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
         }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }
          
//-------------------------------------------------------------------
// ADMINISTRATION - Delete a user
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "deluser")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         mysql_query("DELETE FROM pmc_user WHERE ID = '". $_GET['id'] ."'");
         header("Location: admin.php?action=users");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Delete a loan
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "loandelete")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         // Get the comic and change the loan value
         $getloan = "SELECT * FROM pmc_loan WHERE itemid = '". $_GET['id'] ."'";
         $data = mysql_db_query($sql['data'], $getloan) or die("Select Failed!");
         $row = mysql_fetch_array($data);
         $comicid = $row['comicid'];
         
         mysql_query("UPDATE pmc_comic SET loan = 'no' WHERE uid = '$comicid'");
         
         // Delete the loan from pmc_loan
         mysql_query("DELETE FROM pmc_loan WHERE itemid = '". $_GET['id'] ."'");         
         header("Location: admin.php?action=loans");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Remove a favorite link
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "favdelete")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         // Remove the favorite link from issue         
         mysql_query("UPDATE pmc_comic SET fav = 'no' WHERE uid = '". $_GET['id'] ."'");
                           
         header("Location: admin.php?action=favs");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }
          
//-------------------------------------------------------------------
// ADMINISTRATION - Delete an image
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delimage")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         unlink(image.'/'.$_GET['file']);

         //update the comics table for the noimage.jpg
         mysql_query("UPDATE pmc_comic SET image = 'noimage.jpg' WHERE image = '". mysql_real_escape_string($_GET['file']) ."'");
         header("Location: admin.php?action=images");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Delete a mysql backup file
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "delbackup")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         unlink(backup.'/'.$_GET['file']);

         header("Location: admin.php?action=backup");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Delete a mysql backup file
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "load_backup")) {

     print("<center><br><br>FEATURE NOT READY YET!</center>");

//-------------------------------------------------------------------
// ADMINISTRATION - Edit an image
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "editimage")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         $dir = "image";

         //checking the file filename, if exists send an error message
         if(file_exists("./$dir/".$_POST['new_file']."")) {
         header("Location: error.php?error=21");
         exit;
         }

         rename("./$dir/".$_POST['old_file']."", "./$dir/".$_POST['new_file']."");
         //update the comics table for the new picture name
         mysql_query("UPDATE pmc_comic SET image = '". mysql_real_escape_string($_POST['new_file']) ."' WHERE image = '". mysql_real_escape_string($_POST['old_file']) ."'");
         header("Location: admin.php?action=images");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Edit an artistname
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "manage")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         $new_file = mysql_real_escape_string($_POST['new_file']);
         $old_file = mysql_real_escape_string($_POST['old_file']);
         $number = $_POST['number'];

         //Check the name and if exists send an error message
         $result = mysql_query("SELECT uid FROM pmc_artist WHERE name = '$new_file' AND type = '$number'");
         $row = mysql_fetch_array($result);
         if($row['uid'] != '') {
           header("Location: error.php?error=20");
           exit;
         }

         mysql_query("UPDATE pmc_artist SET name = '$new_file' WHERE uid = '$old_file'");
         header("Location: admin.php?action=artist&type=$number");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

//-------------------------------------------------------------------
// ADMINISTRATION - Add a new artist option
//-------------------------------------------------------------------

} if (!strcmp($_GET['cmd'], "admartist")) {

     // Check if user is logged in
     if($_SESSION['loggedin'] == 'yes' and $_SESSION['ip'] == $_SERVER["REMOTE_ADDR"])
     {
       // Check if the user is the Admin
       $username = strtolower($_SESSION['username']);
       if($username == 'admin')
       {
         //Check the name and if exists send an error message
         $result = mysql_query("SELECT uid FROM pmc_artist WHERE name = '". mysql_real_escape_string($_POST['new_option']) ."' AND type = '". mysql_real_escape_string($_GET['type']) ."'");
         $row = mysql_fetch_array($result);
         if($row['uid'] != '') {
           header("Location: error.php?error=22");
           exit;
         }

         mysql_query("INSERT INTO pmc_artist (name, type) VALUES ('". mysql_real_escape_string($_POST['new_option']) ."', '". mysql_real_escape_string($_GET['type']) ."')");
         header("Location: admin.php?action=artist&type=".$_GET['type']."");
         exit;
       } else {
         // Show the error page if you are not the ADMIN
         header("Location: error.php?error=13");
         exit;
       }
     } else {
       // Show error page if not logged in
       header("Location: error.php?error=14");
       exit;
     }

}

?>