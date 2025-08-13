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

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr valign="top"> 
		<td width="20%">
			<table class="categoriesbox_table" border="1" align="center" cellpadding="3" cellspacing="3">
				<tr class="categoriesbox_transparentborder"> 
					<td class="categoriesbox_title">
						<div align="center">
							<strong><?=  $language['title_categories']; ?></strong>
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
			<table class="contentbox_table" border="1" align="center" cellpadding="3" cellspacing="3">
				<tr class="contentbox_transparentborder"> 
					<td class="contentbox_title">
						<strong><?= $language['title_search']; ?></strong>
					</td>
				</tr>
				<tr class="ccontentbox_transparentborder"> 
					<td class="contentbox_text"><p><?= $language['description_search']; ?></p>
						<form action="search" method="post">
							<p>
								<?= $language['title_search']; ?> 
                                <input type="text" name="search" value="<?php echo safestrip($_POST['search']); ?>"/>  
								<input type="submit" value="<?= $language['button_search']; ?>" />
							</p>
						</form>
						<?php
						// Function: Search
						search($_POST['search'], $language);
						?>
					</td>
				</tr>
			</table>
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