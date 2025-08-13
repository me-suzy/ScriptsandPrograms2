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

// Check page not called directly
if (empty($_GET['id']) || !is_numeric($_GET['id']))
{
	// Redirect user to main page
	header('Location: index.php');
}

// Function: Get data
$download_exists = get_data($_GET['id'], $language);
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
<?= $language['description_details']; ?><br /><br />

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
			<br />
			<?php
			// Check ratings are enabled
			if ($config['ratings'] == 1 && $download_exists)
			{
			?>
			<table class="categoriesbox_table" border="1" align="center" cellpadding="3" cellspacing="3">
				<tr class="categoriesbox_transparentborder"> 
					<td class="categoriesbox_title">
						<div align="center">
							<strong><?= $language['title_ratedownload']; ?></strong>
						</div>
					</td>
				</tr>
				<tr class="categoriesbox_transparentborder"> 
					<td class="categoriesbox_text">
						<form action="rate.php" method="post" name="rate">
							<div align="center" class="small">
								<select name="user_rating">
								<option value="5.0" selected="selected"><?= $language['rating_5']; ?></option>
								<option value="4.0"><?= $language['rating_4']; ?></option>
								<option value="3.0"><?= $language['rating_3']; ?></option>
								<option value="2.0"><?= $language['rating_2']; ?></option>
								<option value="1.0"><?= $language['rating_1']; ?></option>
								<option value="0.0"><?= $language['rating_0']; ?></option>
								</select>
								<input name="submit" type="submit" value="<?= $language['button_rate']; ?>" />
								<input name="id" type="hidden" value="<?= $_GET['id']; ?>" />
							</div>
						</form>
					</td>
				</tr>
			</table>
			<?php
			}
			?>
		</td>
		<td valign="top" width="80%">
        <?php 
        // If download exists display details
        if ($download_exists) 
		{
        ?>
			<table class="contentbox_table" border="1" align="center" cellpadding="3" cellspacing="3">
				<tr class="contentbox_transparentborder">
					<td class="contentbox_title">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="47%">
									<strong><?= stripslashes($row['date']); ?></strong> - <?= stripslashes($row['name']); ?>
								</td>
								<td width="53%">
								<div align="right">
									<a href="file-<?= stripslashes($row['id']); ?>"><?= $language['link_downloadnow']; ?></a>
								</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="contentbox_transparentborder">
					<td class="contentbox_text">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<?php
									// Function: Handle images
									image_handler($row);
									?>
								</td>
								<td valign="top">
									<?= stripslashes($row['description_full']); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="contentbox_transparentborder">
					<td class="contentbox_text">
						<span class="small">
							<strong><?= $language['info_downloads']; ?> </strong><?= stripslashes($row['count']); ?> &middot; 
							<strong><?= $language['info_filesize']; ?> </strong><?= stripslashes($row['size']); ?>Mb &middot;
							<?php
							// Check ratings are enabled
							if ($config['ratings'] == 1)
							{
							?>
							<strong><?= $language['info_rating']; ?> </strong><?= $rating; ?> &middot;
							<strong><?= $language['info_votes']; ?> </strong><?= stripslashes($row['votes']); ?>
							<?php
							}
							// Display custom 1 if exists
							if (!empty($row['custom_1_l']) && !empty($row['custom_1_v']))
							{
							?>
							 &middot; <strong><?= stripslashes($row['custom_1_l']); ?>: </strong><?= stripslashes($row['custom_1_v']); ?> &middot;
							<?php
							}
							// Display custom field 2 if exists
							if (!empty($row['custom_2_l']) && !empty($row['custom_2_v']))
							{
							?>
							<strong><?= stripslashes($row['custom_2_l']); ?>: </strong><?= stripslashes($row['custom_2_v']); }?>
						</span>
					</td>
				</tr>
			</table>
        <?php
		} else {
		?>
            <table class="contentbox_table" border="1" align="center" cellpadding="3" cellspacing="3">
                <tr class="contentbox_transparentborder"> 
                    <td class="contentbox_title">
                        <strong><?= $language['link_moreinfo']; ?></strong>
                    </td>
                </tr>
                <tr class="ccontentbox_transparentborder"> 
                    <td class="contentbox_text">
                        <?php
                        
                        // Show the warning
                        echo $language['description_nodownload'];

                        ?>
                    </td>
                </tr>
            </table>
        
        <?php
		}
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