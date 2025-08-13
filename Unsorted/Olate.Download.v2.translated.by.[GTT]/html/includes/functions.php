<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 ***************************************************************************/

// Function     : error_handling($error_page_name, $error_message)
// Description  : Error handling function that will print the error message then exit the script.

function error_handling($error_page_name, $error_message)
{
	echo '<table width="500"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
		  <tr>
		  <td bordercolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
		  <tr>
		  <td height="20" bgcolor="#E3E8EF"><font face="Arial, Helvetica, sans-serif" size="2"><b>Error: </b></font></td>
		  </tr>
		  <tr>
		  <td><font face="Arial, Helvetica, sans-serif" size="2">An error has occurred in '.$error_page_name.':<br /><br /><i>'.$error_message.'</i></font></td>
		  </tr>
		  </table></td>
		  </tr>
		  </table>';
	exit;
}

/***************************************************************************/

// Function     :  get_config($where)
// Description  :  Retrieve configuration data from database.

function get_config($where)
{
	// Query database
	$query = 'SELECT * FROM '.db_prefix.'config WHERE config_name = "'.$where.'"';
	
	@ $result = mysql_query($query);
	
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_config()';
		error_handling('includes/functions.php', $error_message);
	}

	@ $row = mysql_fetch_assoc($result);

	// Error handling
	if (!$row)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_config()';
		error_handling('includes/functions.php', $error_message);
	}
	
	return $row['config_value'];
}

/***************************************************************************/

// Function     :   display_downloads($category, $config, $language, $page, $sort)
// Description  :   Display the available downloads

function display_downloads($category, $config, $language, $page, $sort)
{			
	// Define the number of results per page
    if ($category == "top") 
	{
		 $display_results = $config['notopdownloads'];
	} else {
		$display_results = $config['pages']; 
	}

	// Calculate limit based on $page 
	$from = (($page * $display_results) - $display_results);
	$query = ' FROM '.db_prefix.'files ';

    // Avoid SQL injections
    if (($category != "top" && $category != "all"))
    {
	    $query .= ' WHERE category = '.$category.' ';
    } else {
        $query .= ' WHERE 1 ';
	}
        
    // Query database
    if ($sort == 'name')
    {
	    $query .= ' ORDER BY '.$sort.' ASC ';
    } else {
        $query .= ' ORDER BY '.$sort.' DESC ';
	}

    $query_page  = 'SELECT * ' . $query . ' LIMIT '.$from.', '.$display_results.'';
    $query_total = 'SELECT COUNT(id) '  . $query; 

    @ $result = mysql_query($query_page);
	@ $num_rows = mysql_num_rows($result);
	
    @ $total_num_rows = mysql_result(@mysql_query($query_total), 0);
	
	// If no downloads, display friendly message and exit function
	if ($num_rows == 0)
	{
		echo $language['description_nodlcategory'];
		return;
	}
	
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in display_downloads()';
		error_handling('includes/functions.php', $error_message);
	}
	
    $i = 0;

    while (@ $row = mysql_fetch_assoc($result)) 
	{
		if ($i >= $config['notopdownloads'] && $config['topdownloadslink'] && $category == "top")
        {    
            break;
		}
			
		// Format $rating			
		if ( $row['votes'] == 0 )
		{
			$rating = $language['rating_notrated'];
		} else {
			$rating = stripslashes($row['rating']).' / 5.00';
		}
		
		echo '<table class="indexdownloads_table" border="1" align="center" cellpadding="3" cellspacing="3">';
		echo '<tr class="indexdownloads_title">';
		echo '<td class="indexdownloads_text"><table width="98%" border="0" align="center" cellpadding="3" cellspacing="0">';
		echo '<tr>';
		echo '<td width="50%"><a href="info-'.stripslashes($row['id']).'"><strong>'.stripslashes($row['name']).'</strong></a></td>';
		echo '<td width="35%"><div align="right"><a class="listing" href="info-'.stripslashes($row['id']).'">'.$language['link_moreinfo'].'</a></div></td>';
		echo '<td width="35%"><div align="right"><a class="listing" href="file-'.stripslashes($row['id']).'">'.$language['link_downloadnow'].'</a></div></td>';
		echo '</tr>';
		echo '</table></td>';
		echo '</tr>';
		echo '<tr valign="top" class="contentbox_table">';
		echo '<td class="indexdownloads_text" width="15%">';
		echo stripslashes($row['description_brief']);
		echo '<br /><br /><span class="small"><strong>'.$language['info_downloads'].' </strong>'.stripslashes($row['count']).' &middot;';
		if ($config['ratings'] == 1)
		{
			echo ' <strong>'.$language['info_rating'].' </strong>'.$rating.'</span>';
		}
		echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo '<hr class="indexdownloads_hr" />';		

        $i++;
    }

    if ($category != "top") 
	{
		// Calculate number of pages and round
		$pages = ceil($total_num_rows / $display_results);

		echo $language['title_pages'];
		// If page number is greater than 1, display 'Previous' link
		if($page > 1)
		{ 
			$prev = ($page - 1); 
			echo '<a href="index.php?category='.$category.'&page='.$prev.'&sort='.$sort.'">'.$language['title_pages_prev'].'</a>&nbsp;'; 
		} 
	
		// Display number of pages linked to that page number
		for($i = 1; $i <= $pages; $i++)
		{ 
			if($page == $i)
			{ 
				// Display page number
				echo $i.'&nbsp;'; 
			} else { 
				// Display link
				echo '<a href="index.php?category='.$category.'&page='.$i.'&sort='.$sort.'">'.$i.'</a>&nbsp;'; 
			} 
		}
	
		// If page number is less than number of pages, display 'Next' link 
		if($page < $pages)
		{ 
			$next = ($page + 1); 
			echo '<a href="index.php?category='.$category.'&page='.$next.'&sort='.$sort.'">'.$language['title_pages_next'].'</a>'; 
		}
	}
}

/***************************************************************************/

// Function     :   get_categories()
// Description  :   Retrieve the listing of categories from the database ready for displaying

function get_categories()
{
	// Query database
	$query = 'SELECT * FROM '.db_prefix.'categories ORDER BY name ASC';
		
	@ $result = mysql_query($query);	
	@ $num_rows = mysql_num_rows($result);
	
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_categories()';
		error_handling('includes/functions.php', $error_message);
	}
	
	// If no categories, exit function
	if ($num_rows == 0)
	{
		return;
	}
	
	// Error Handling
	if (!$num_rows)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_categories()';
		error_handling('includes/functions.php', $error_message);
	}
	
	// Display all entries
    for ($i=0; $i < $num_rows; $i++) 
	{
		@ $row = mysql_fetch_assoc($result);
	
		// Error handling
		if (!$row)
		{
			$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in display_categories()';
			error_handling('includes/functions.php', $error_message);
		}
		echo '<li><a href="cat-'.stripslashes($row['id']).'">'.stripslashes($row['name']).'</a></li>';
	}
}

/***************************************************************************/

// Function     :   increment_count($id)
// Description  :   Increment the download count of the file

function increment_count($id)
{
	settype($id, 'integer');

    // Increment count
    $query = 'SELECT count FROM '.db_prefix.'files WHERE id = '.$id.' LIMIT 1';
    
    @ $result = mysql_query($query);
    @ $row = mysql_fetch_assoc($result);
	
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in increment_count()';
		error_handling('includes/functions.php', $error_message);
	}
	
	// Error Handling
    if (mysql_num_rows($result) == 0) 
	{
        echo $language['description_nodownload'];
        return;
    } else if (!$row) 
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in increment_count()';
		error_handling('includes/functions.php', $error_message);
	}
	
	// Get count data
	$count = $row['count'];

	// Increment $views
	$count++;

    // Put new value into database
    $query = 'UPDATE '.db_prefix.'files SET count = '.$count.' WHERE id = '.$id.' LIMIT 1'; 
	@ $result = mysql_query($query);
	
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in increment_count()';
		error_handling('includes/functions.php', $error_message);
	}
}

/***************************************************************************/

// Function     :   get_data($id, $language)
// Description  :   Get the data for the selected file

function get_data($id, $language)
{	
    global $row, $rating;

    settype($id, 'integer');
    $id = abs($id);
	
	// Increment count
	$query = 'SELECT * FROM '.db_prefix.'files WHERE id = '.$id.'';
    
	@ $result = mysql_query($query);
    @ $row = mysql_fetch_assoc($result);
	
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_data()';
		error_handling('includes/functions.php', $error_message);
	}
	
	// Error Handling
	if ($row == 0)
	{
        return false;
	} else if (!$row) {
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_data()';
		error_handling('includes/functions.php', $error_message);
	}
	
	// Format $rating		
	if ( $row['votes'] == 0 )
	{
		$rating = $language['rating_notrated'];
	} else {
		$rating = stripslashes($row['rating']).' / 5.00';
	}
    // All is OK
    return true;
}
/***************************************************************************/

// Function     :   image_handler($row)
// Description  :   Checks if image is available, if not, uses default image

function image_handler($row)
{
	if (stripslashes($row['image']) == NULL)
	{
		echo '<img src="images/noimage.gif" alt="No Image" />';
	} else {
		echo '<img src="'.stripslashes($row['image']).'" />';
	}
}

/***************************************************************************/	

// Function     : rate($id, $user_rating, $language)
// Description  : Get rating, calculate it and then apply.

function rate($id, $user_rating, $language)
{
	global $result;
	
    // SQL injection prevention
    settype($id, 'integer');
    settype($user_rating, 'integer');

    if (($user_rating < 0 || $user_rating > 5))
	{
        $user_rating = 5;
	}
	
	// Start sessions
	session_start();
	
	// Check if session is registered
	if (isset($_SESSION['download_rating'.$id]))
	{ 
        $result = $language['rating_already'].'<a href="info-'.$id.'">'.$language['link_clickheregoback'].'</a>.'; 
    } else { 
        // Get original data from database
		@ $get_count = mysql_query('SELECT rating, votes FROM '.db_prefix.'files WHERE id='.$id.'');
		// Error handling
		if (!$get_count)
		{
			$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in rate()';
			error_handling('includes/functions.php', $error_message);
		} 
        
    	while(list($rating, $votes) = mysql_fetch_assoc($get_count)) 
		{
			// Calculate new rating
			$new_count = ($votes + 1); 
            $dl_rating2 = ($rating * $votes); 
            $new_rating = (($user_rating + $dl_rating2) / ($new_count)); 
            $new_rating2 = number_format($new_rating, 2, '.', '');
			
            // Add new rating to database
            @ $update_rating = mysql_query('UPDATE '.db_prefix.'files SET rating = "'.$new_rating2.'", votes = "'.$new_count.'" WHERE id = '.$id.' LIMIT 1'); 
            
			// Error handling
			if (!$update_rating)
			{
				$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in rate()';
				error_handling('includes/functions.php', $error_message);
			}
			
			// Define and register a new session
			$sessionvar = 'download_rating'.$id; 
            
			$_SESSION[$sessionvar] = true; 
             
            $result = $language['rating_thanks'].$new_rating2.$language['rating_outof'].' <a href="info-'.$id.'">'.$language['link_clickheregoback'].'</a>'; 
        } 
    } 
}

/***************************************************************************/	

// Function     : search($search, $language)
// Description  : Search the names of files

function search($search, $language)
{
    // SQL injection prevention
    $search = addslashes(safestrip($search));
    
    // Query database      
    $query  =  'SELECT * FROM '.db_prefix.'files WHERE 1 AND ';
    $query .=  ' name               LIKE "%'.$search.'%" OR  ';
    $query .=  ' description_brief  LIKE "%'.$search.'%" OR  ';
    $query .=  ' description_full   LIKE "%'.$search.'%"     ';

	@ $result = mysql_query($query);	
	@ $num_rows = mysql_num_rows($result);
						
	// If no downloads, display friendly message and exit function
	if (empty($search))
	{
		return;
	}
	
	// If no downloads, display friendly message and exit function
	if ($num_rows == 0)
	{
		echo '<b>'.$language['title_search_results'].'</b><br /><br />';
		echo $language['description_noresults'];
		return;
	}
						
	// Error Handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in search()';
		error_handling('includes/functions.php', $error_message);
	}
						
	// Error Handling
	if (!$num_rows)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in search()';
		error_handling('includes/functions.php', $error_message);
	}
						
	echo '<b>'.$language['title_search_results'].'</b>';
	echo '<ul>';
		
	// Display all downloads
    for ($i=0; $i <$num_rows; $i++) 
	{ 
        $row = mysql_fetch_assoc($result);

        $str  =  '<li><a href="info-'.stripslashes($row['id']).'">'.stripslashes($row['name']) . "</a>\n\n";
        $str .=  "<div class='search_download_desc_brief'>"; 

        // Highlight the search clause        
        $desc = stripslashes($row['description_brief']);
        $desc = str_i_replace($search, "<b>".$search."</b>", $desc);
        
        $str .=  $desc . "</div>\n\n<br /></li>\n\n";

        echo $str;	
    }
	echo '</ul>';
}

/***************************************************************************/	

// Function     : ssafestrip($str)
// Description  : If running with magic_quotes_gpc (get/post/cookie) set in php.ini, we will need to 
//                strip slashes from every field we receive from a get/post operation.

function safestrip($str) 
{
    if(get_magic_quotes_gpc()) 
	{
        $str = stripslashes($str);
    }
    return $str;
}

/***************************************************************************/   

// Function     : str_i_replace($search, $replace, $text)
// Description  : Case insensitive str_replace

function str_i_replace($search, $replace, $text) 
{
    if (!is_array($search)) 
	{
        $search = array($search);
        $replace = array($replace);
    }
    
    foreach($search AS $key => $val) 
	{
        $search["$key"]  = '/'.quotemeta($val).'/i';
    }
    
    return preg_replace($search,$replace,$text);
}
/***************************************************************************/   
?>