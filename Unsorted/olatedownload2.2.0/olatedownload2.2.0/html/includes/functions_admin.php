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
		error_handling('includes/functions_admin.php', $error_message);
	}
	@ $row = mysql_fetch_array($result);

	// Error handling
	if (!$row)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in get_config()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	return $row['config_value'];
}

/***************************************************************************/

// Function     :   admin_login($username, $password)
// Description  :   Authenticate user and login

function admin_login($username, $password)
{
	// Define and execute query
	$query = 'SELECT * FROM '.db_prefix.'auth WHERE username = "'.$username.'" AND password = md5("'.$password.'")';
	@ $result = mysql_query($query);
			
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_login()';
		error_handling('includes/functions_admin.php', $error_message);
	}
		
		// If row exists -> user/pass combination is correct
		if (mysql_num_rows($result) >0)
		{			
			// Register session
			session_start();
			$_SESSION['admin_valid'] = 1;
			$_SESSION['admin_username'] = $username;
			
			// Redirect to protected page
			header('Location: main.php');
			exit();
		} else {
			// User/pass combination is wrong
			// Redirect to error page
			header('Location: error.php');
			exit();
		}
}

/***************************************************************************/

// Function     :   admin_authenticate($config)
// Description  :   Make sure user has logged in

function admin_authenticate($config)
{
	// Register session
	session_start();
	
	// Make checks
	if (!isset($_SESSION['admin_valid']))
	{
		// if session check fails, invoke error handler
		$errorpage = 'Location: '.$config['urlpath'].'/admin/error.php';
		
		header($errorpage);
		exit();
	}

}

/***************************************************************************/

// Function     :   admin_logout()
// Description  :   Logout user

function admin_logout() 
{
	// Destroy all session variables
	session_start();
	// Unset all of the session variables.
	$_SESSION = array();

	// Destroy the session
	session_destroy();
	
	// redirect browser back to login page
	header('Location: index.php');
}

/***************************************************************************/

// Function     :   admin_license()
// Description  :   Get license file and display it

function admin_license()
{
	// Get file and display
	@ $license = readfile('http://www.olate.com/legal/license_text.php');
	
	// Error handling
	if (!$license)
	{
		echo '<p><b>Error:</b></p>Unable to open license file. The Olate server may be unavailable so please try again later. If the problem persists, please select the Technical Support option from the main menu to find out how to get help.';
	}
}

/***************************************************************************/

// Function     :   admin_update($config)
// Description  :   Check for any updates

function admin_update($config)
{
	$url = 'http://www.olate.com/scripts/updates.php?script=1&version='.urlencode($config['version']).'';
	
	// Get file and display
	@ $update = readfile($url);
	
	// Error handling
	if (!$update)
	{
		echo '<p><b>Error:</b></p>Unable to contact the Update Server. The Olate server may be unavailable so please try again later. If the problem persists, please select the Technical Support option from the main menu to find out how to get help.';
	}
}

/***************************************************************************/

// Function     :   admin_config_language($config)
// Description  :   List available languages

function admin_config_language($config)
{
	// Get path
	$path = '../../languages/';  
	
	// Using the opendir function
	$dir_handle = opendir($path); 
	
	// Running the while loop
	while ($file = readdir($dir_handle)) 
	{
		// . and .. are displayed so remove them
		if (($file == '.') ||($file == '..'))
		{
		} else {
			// Check to see if all 3 language files are present
			if ((file_exists("$path$file/config.php")) && (file_exists("$path$file/main.php")) && (file_exists("$path$file/admin.php")))
			{
				// Get the language config.php file so data can be displayed
				include("$path$file/config.php");
				echo '<option value="'.$file.'">'.$language_config['language'].'</option>';
			}
		}
	} 
	
	// Close directory
	closedir($dir_handle);
}

/***************************************************************************/

// Function     :   admin_generalsettings_update($language, $urlpath, $topdownloadslink, $alldownloads, $searchlink, $sorting, $ratings, $notopdownloads, $pages)
// Description  :   Update database with new details


function admin_generalsettings_update($language, $urlpath, $topdownloadslink, $alldownloads, $searchlink, $sorting, $ratings, $notopdownloads, $pages)
{
	// Check all fields are filled out, if not, exit function
	if (empty($urlpath) || empty ($notopdownloads) || empty($pages))
	{
		// Exit function and return error
		return $language['description_allfields'];
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$urlpath.'" WHERE config_name = "urlpath"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$topdownloadslink.'" WHERE config_name = "topdownloadslink"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$alldownloads.'" WHERE config_name = "alldownloads"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$searchlink.'" WHERE config_name = "searchlink"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$sorting.'" WHERE config_name = "sorting"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$ratings.'" WHERE config_name = "ratings"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$notopdownloads.'" WHERE config_name = "notopdownloads"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$pages.'" WHERE config_name = "pages"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_generalsettings_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Exit function and return success
	return $language['description_config_general_upd'];
}

/***************************************************************************/

// Function     :   admin_languages_update($language)
// Description  :   Update database with new language


function admin_languages_update($language)
{
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'config SET config_value = "'.$language.'" WHERE config_name = "language"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_languages_update()';
		error_handling('includes/functions_admin.php', $error_message);
	}
}

/***************************************************************************/

// Function     :   admin_downloads_add_catmenu()
// Description  :   Display the categories in the drop down menu

function admin_downloads_add_catmenu()
{
	// Get information from database
	$query = 'SELECT id, name FROM '.db_prefix.'categories ORDER BY name ASC';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_add_catmenu()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	@ $num_results = mysql_num_rows($result);
	
	// If no downloads, exit function
	if ($num_results == 0)
	{
		return;
	}
	
	// Error handling
	if (!$num_results)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_add_catmenu()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Display categories in menu
	for ($i=0; $i <$num_results; $i++)
	{
		$row = mysql_fetch_array($result);
		echo '<option value="'.$row['id'].'">'.stripslashes($row['name']).'</option>';
	}
}

/***************************************************************************/

// Function     :   admin_downloads_add($language, $date, $name, $location, $size, $category, $description_brief, $description_full, $custom_1_l, $custom_1_v, $custom_2_l, $custom_2_v, $image)
// Description  :   Add the download to the database

function admin_downloads_add($language, $date, $name, $location, $size, $category, $description_brief, $description_full, $custom_1_l, $custom_1_v, $custom_2_l, $custom_2_v, $image)
{
	// Check all fields are filled out, if not, exit function
	if (empty($date) || empty($name) || empty($location) || empty($size) || empty($category) || empty($description_brief) || empty($description_full))
	{
		// Exit function and return error
		return $language['description_allfields'];
	}
	
	// Create insert query and execute it
	$query = 'INSERT INTO '.db_prefix.'files ( date, name, location, size, category, description_brief, description_full, custom_1_l, custom_1_v, custom_2_l, custom_2_v, image ) VALUES ("'.addslashes($date).'", "'.addslashes($name).'", "'.addslashes($location).'", "'.addslashes($size).'", "'.addslashes($category).'", "'.addslashes($description_brief).'", "'.addslashes($description_full).'", "'.addslashes($custom_1_l).'", "'.addslashes($custom_1_v).'", "'.addslashes($custom_2_l).'", "'.addslashes($custom_2_v).'", "'.addslashes($image).'")'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_'.db_prefix.'add()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Exit function and return success
	return $language['description_downloads_added'];
}

/***************************************************************************/

// Function     :   admin_downloads_edit_select()
// Description  :   Display all downloads so they can be selected

function admin_downloads_edit_select()
{
	// Get information from database
	$query = 'SELECT * FROM '.db_prefix.'files ORDER BY name ASC';
	@ $result = mysql_query($query);
	
		// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	@ $num_results = mysql_num_rows($result);
	
	// If no downloads, display friendly message and exit function
	if ($num_results == 0)
	{
		echo 'There are no downloads in the database.';
		return;
	}
	
	// Error handling
	if (!$num_results)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Display all files
	for ($i=0; $i <$num_results; $i++)
	{ 
		$row = mysql_fetch_array($result);
		echo '<li>';
		echo '<a href="edit_view.php?id='.stripslashes($row['id']).'">'.stripslashes($row['name']).'</a><br />';
		echo stripslashes($row['description_brief']);
		echo '<br /><br /></li>';
	}
}
/***************************************************************************/

// Function     :   admin_downloads_edit_view($id)
// Description  :   Display all downloads so they can be selected

function admin_downloads_edit_view($id)
{
	// Create and execute query
	$query = 'SELECT * FROM '.db_prefix.'files WHERE id = '.$id.'';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit_view()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	global $row;
	@ $row = mysql_fetch_array($result);
	
	// Error handling
	if (!$row)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit_view()';
		error_handling('includes/functions_admin.php', $error_message);
	}
}
/***************************************************************************/	

// Function     :   admin_downloads_edit_view_cat($category)
// Description  :   Get name of category

function admin_downloads_edit_view_cat($category)
{
	// Create and execute query
	$query = 'SELECT * FROM '.db_prefix.'categories WHERE id = '.$category.'';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit_view_cat()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Get data
	@ $row = mysql_fetch_array($result);
	
	// If no category, exit function
	if ($row == 0)
	{
		return 'No category selected';
	}
	
	// Error handling
	if (!$row)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit_view_cat()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Get name of category and return
	return $row['name']; 
}
/***************************************************************************/	

// Function     :   admin_downloads_edit($language, $id, $date, $name, $location, $size, $category, $description_brief, $description_full, $custom_1_l, $custom_1_v, $custom_2_l, $custom_2_v, $image)
// Description  :   Update database

function admin_downloads_edit($language, $id, $date, $name, $location, $size, $category, $description_brief, $description_full, $custom_1_l, $custom_1_v, $custom_2_l, $custom_2_v, $image)
{
	// Check all fields are filled out, if not, exit function
	if (empty($date) || empty($name) || empty($location) || empty($size) || empty($category) || empty($description_brief) || empty($description_full))
	{
		// Exit function and return error
		return $language['description_allfields'];
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'files SET date = "'.addslashes($date).'", name = "'.addslashes($name).'", location = "'.addslashes($location).'", size = "'.addslashes($size).'", category = "'.addslashes($category).'", description_brief = "'.addslashes($description_brief).'", description_full = "'.addslashes($description_full).'", custom_1_l = "'.addslashes($custom_1_l).'", custom_1_v = "'.addslashes($custom_1_v).'", custom_2_l = "'.addslashes($custom_2_l).'", custom_2_v = "'.addslashes($custom_2_v).'", image = "'.addslashes($image).'" WHERE id = "'.$id.'"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_edit()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Exit function and return success
	return $language['description_downloads_edited'];
}
/***************************************************************************/

// Function     :   admin_downloads_delete_select()
// Description  :   Display all downloads so they can be selected

function admin_downloads_delete_select()
{
	// Get information from database
	$query = 'SELECT * FROM '.db_prefix.'files ORDER BY name ASC';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_delete_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	@ $num_results = mysql_num_rows($result);
	
	// If no downloads, display friendly message and exit function
	if ($num_results == 0)
	{
		echo 'There are no downloads in the database.';
		return;
	}
	
	// Error handling
	if (!$num_results)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_delete_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Display all articles
	for ($i=0; $i <$num_results; $i++)
	{ 
		$row = mysql_fetch_array($result);
		echo '<li>';
		echo '<a href="delete_process.php?id='.stripslashes($row['id']).'">'.stripslashes($row['name']).'</a><br />';
		echo stripslashes($row['description_brief']);
		echo '<br /><br /></li>';
	}
}
/***************************************************************************/

// Function     :   admin_downloads_delete($id)
// Description  :   Delete selected download

function admin_downloads_delete($id)
{
	// Create query and execute it
	$query = 'DELETE FROM '.db_prefix.'files WHERE id = '.$id.''; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_downloads_delete()';
		error_handling('includes/functions_admin.php', $error_message);
	}
}

/***************************************************************************/

// Function     :   admin_users_add($language, $username, $password, $master)
// Description  :   Add user to database

function admin_users_add($language, $username, $password, $master)
{	
	// Check all fields are filled out, if not, exit function
	if (empty($username) || empty ($password))
	{
		// Exit function and return error
		return $language['description_allfields'];
	}
	
	// Create insert query and execute it
	$query = 'INSERT INTO '.db_prefix.'auth ( username, password, master ) VALUES ("'.$username.'", "'.md5($password).'", "'.$master.'")'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_users_add()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Exit function and return success
	return $language['description_users_added'];
}

/***************************************************************************/

// Function     :   admin_users_delete_select()
// Description  :   Display all users so they can be selected

function admin_users_delete_select()
{
	// Get information from database
	$query = 'SELECT * FROM '.db_prefix.'auth ORDER BY username ASC';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_users_delete_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	@ $num_results = mysql_num_rows($result);
	
	// Error handling
	if (!$num_results)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_users_delete_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Display all articles
	for ($i=0; $i <$num_results; $i++)
	{ 
		$row = mysql_fetch_array($result);
		echo '<li>';
		echo '<a href="delete_process.php?id='.stripslashes($row['id']).'">'.stripslashes($row['username']).'</a>';
		if ($row['master'] == 1)
		{
			echo ' (Master)';
		}
		echo '</li>';
	}
}
/***************************************************************************/

// Function     :   admin_users_delete($language, $id)
// Description  :   Delete selected user

function admin_users_delete($language, $id)
{
	// Get information from database
	$query = 'SELECT * FROM '.db_prefix.'auth WHERE id = '.$id.'';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_users_delete()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	$row = mysql_fetch_array($result);
	
	// Error handling
	if (!$row)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_users_delete()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	if ($row['master'] == 1)
	{
		return $language['description_users_master'];
	}
	
	// Create query and execute it
	$query = 'DELETE FROM '.db_prefix.'auth WHERE id = '.$id.''; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_users_delete()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	return $language['description_users_deleted'];
}

/***************************************************************************/

// Function     :   admin_categories_add($language, $name)
// Description  :   Add the category to the database

function admin_categories_add($language, $name)
{
	// Check all fields are filled out, if not, exit function
	if (empty($name))
	{
		// Exit function and return error
		return $language['description_allfields'];
	}
	
	// Create insert query and execute it
	$query = 'INSERT INTO '.db_prefix.'categories ( name ) VALUES ("'.addslashes($name).'")'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_add()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Exit function and return success
	return $language['description_categories_added'];
}

/***************************************************************************/

// Function     :   admin_categories_edit_select()
// Description  :   Display all categories so they can be selected

function admin_categories_edit_select()
{
	// Get information from database
	$query = 'SELECT * FROM '.db_prefix.'categories ORDER BY name ASC';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_edit_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	@ $num_results = mysql_num_rows($result);
	
	// If no categories, display friendly message and exit function
	if ($num_results == 0)
	{
		echo 'There are no categories in the database.';
		return;
	}
	
	// Error handling
	if (!$num_results)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_edit_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Display all articles
	for ($i=0; $i <$num_results; $i++)
	{ 
		$row = mysql_fetch_array($result);
		echo '<li>';
		echo '<a href="edit_view.php?id='.stripslashes($row['id']).'">'.stripslashes($row['name']).'</a><br />';
		echo '</li>';
	}
}
/***************************************************************************/

// Function     :   admin_categories_edit_view($id)
// Description  :   Display all downloads so they can be selected

function admin_categories_edit_view($id)
{
	// Create and execute query
	$query = 'SELECT * FROM '.db_prefix.'categories WHERE id = '.$id.'';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_edit_view()';
		error_handling('includes/functions_admin.php', $error_message);
	}
		
	global $row;
	@ $row = mysql_fetch_array($result);
	
	// Error handling
	if (!$row)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_edit_view()';
		error_handling('includes/functions_admin.php', $error_message);
	}
}
/***************************************************************************/

// Function     :   admin_categories_edit($language, $id, $name)
// Description  :   Update database

function admin_categories_edit($language, $id, $name)
{
	// Check all fields are filled out, if not, exit function
	if (empty($name))
	{
		// Exit function and return error
		return $language['description_allfields'];
	}
	
	// Create insert query and execute it
	$query = 'UPDATE '.db_prefix.'categories SET name = "'.addslashes($name).'" WHERE id = "'.$id.'"'; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_edit()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Exit function and return success
	return $language['description_categories_edited'];
}
/***************************************************************************/	

// Function     :   admin_categories_delete_select()
// Description  :   Display all users so they can be selected

function admin_categories_delete_select()
{
	// Get information from database
	$query = 'SELECT * FROM '.db_prefix.'categories ORDER BY name ASC';
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_delete_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	@ $num_results = mysql_num_rows($result);
	
	// If no downloads, display friendly message and exit function
	if ($num_results == 0)
	{
		echo 'There are no downloads in the database.';
		return;
	}
	
	// Error handling
	if (!$num_results)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_delete_select()';
		error_handling('includes/functions_admin.php', $error_message);
	}
	
	// Display all articles
	for ($i=0; $i <$num_results; $i++)
	{ 
		$row = mysql_fetch_array($result);
		echo '<li>';
		echo '<a href="delete_process.php?id='.stripslashes($row['id']).'">'.stripslashes($row['name']).'</a><br />';
		echo '</li>';
	}
}

/***************************************************************************/

// Function     :   admin_categories_delete($id)
// Description  :   Delete selected category

function admin_categories_delete($id)
{
	// Create query and execute it
	$query = 'DELETE FROM '.db_prefix.'categories WHERE id = '.$id.''; 
	@ $result = mysql_query($query);
	
	// Error handling
	if (!$result)
	{
		$error_message = 'Database Error - '.mysql_errno().': '.mysql_error().' in admin_categories_delete()';
		error_handling('includes/functions_admin.php', $error_message);
	}
}

/***************************************************************************/
?>