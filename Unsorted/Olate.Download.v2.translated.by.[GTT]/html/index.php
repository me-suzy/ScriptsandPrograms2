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

// Start script
require_once('includes/init.php');

// Set variable as $_GET['category'] so can be manipulated
$category = $_GET['category'];

// Avoid SQL injections
if (!empty($category) and ($category != "top") and ($category != "all")) 
{
    settype($category, "integer"); 
    $category = abs($category);
} elseif (empty($category) and ($config['topdownloadslink'])) 
{
    $category = "top";
} elseif (empty($category)) 
{
    $category = "all";
}

// Get current page number, if no page number defined, set default
if(!isset($_GET['page']))
{ 
    $page = 1;
} else { 
    $page = $_GET['page']; 
    settype($page, "integer");
    $page = abs($page);
} 

// Get selected sort, if no sort defined, set default
// Avoid SQL injections
$good_sorts = array("count", "name", "rating", );

if ( (!isset($_GET['sort'])) or ( !in_array($_GET['sort'], $good_sorts)) ) 
{
    $sort = 'count'; 
} else { 
    $sort = $_GET['sort']; 
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Olate Download - Translated by GTT</title>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link rel="stylesheet" type="text/css" href="css/style.css" title="default" />

</head>
<body>

<strong>Olate Download - Translated by GTT</strong><br /><br />
<?= $language['description_index']; ?><br /><br />
<?php
// Only echo if not displaying 'Top Downloads' (they are ordered by downloads) and is enabled in admin
if ($category != 'top') {
    
    if ($config['sorting'] == 1  && $config['ratings'] == 1)
        echo '<div align="right"><strong>'.$language['title_sortby'].'</strong> <a href="index.php?category='.$category.'&sort=name">'.$language['title_sorting_name'].'</a> | <a href="index.php?category='.$category.'&sort=rating">'.$language['title_sorting_rating'].'</a> | <a href="cat-'.$category.'">'.$language['title_sorting_downloads'].'</a></div><br />';

    // If sortings are disabled, do not show the option to sort by them!
    elseif ($config['sorting'] == 1)
 
	echo '<div align="right"><strong>'.$language['title_sortby'].'</strong> <a href="index.php?category='.$category.'&sort=name">'.$language['title_sorting_name'].'</a> | <a href="cat-'.$category.'">'.$language['title_sorting_downloads'].'</a></div><br />';
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr valign="top"> 
		<td width="20%">
			<table class="categoriesbox_table" border="1" align="center" cellpadding="3" cellspacing="3">
				<tr class="categoriesbox_transparentborder"> 
					<td class="categoriesbox_title">
						<div align="center">
							<strong><?= $language['title_categories']; ?></strong>
						</div>
					</td>
				</tr>
				<tr class="categoriesbox_transparentborder">
					<td class="categoriesbox_text">
						<ul>
						<?php
						// Show 'Top Downloads' link if enabled
						if ($config['topdownloadslink'] == 1)
						{
							echo '<li><a href="top">'.$language['menu_top'].' '.$config['notopdownloads'].' '.$language['menu_downloads'].'</a></li>';
						}
						// Show 'All Downloads' link if enabled
						if ($config['alldownloads'] == 1)
						{
							echo '<li><a href="all">'.$language['menu_all'].'</a></li>';
						}
						// Show 'All Downloads' link if enabled
						if ($config['searchlink'] == 1)
						{
							echo '<li><a href="search">'.$language['title_search'].'</a></li>';
						}						
						
						// Create a line break. Cannot use <br>
						if ($config['topdownloadslink'] == 1 || $config['alldownloads'] == 1 || $config['searchlink'] == 1)
						{
							echo '</ul><ul>';
						}
						
						// Function: Get categories
						get_categories();
						?>
						</ul>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" width="80%">
			<?php
			// Function: Display downloads
			display_downloads($category, $config, $language, $page, $sort);
			?>
		</td>
	</tr>
</table>

<br />
<?php
// Include Credits - REMOVAL WILL VOID LICENSE
require('includes/credits.php');
require('includes/translated.php');
?>

</body>
</html>