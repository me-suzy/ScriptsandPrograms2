<?php

/**
* Easy Peasy Image Gallery (EPIG)
*
* Copyright(c) 2005 Apostolos (Soumis) Dountsis
*
* @author	Apostolos (Soumis) Dountsis
* @email	epig@gamecycle.net
* @version	1.0.8
* @date		26-April-2005
*
* Revisions:
*	1.0.0	Initial release
*	1.0.2	Added PNG support
*	1.0.3	Sorting for the files is now added
*	1.0.4	Several code optimisations
*	1.0.5	Now 5 pages links are diplayed per line
*	1.0.6	Config file is now an external XML file. Allows user to upgrade EPIG
*			and maintain the album settings
*	1.0.7	Bundled as a class. No global variables any more (array is used instead)
*	1.0.8	The popup window where the actual is being displayed is now resized to the 
*		dimensions of the image and the filebar and addressbar are now removed
*		It uses javascript to control the popup window
*
*
* This file creates an image gallery with automatically generated thumbnails for
* all the images (gif, jpg and png) in the current directory.
* The images are sorted by file name. Simply copy this file and
* template to the directory where the pictures reside and you are done.
* EPIG requires PHP4 and GD.
* You can customise EPIG by changing the Configuration Settings to match your preference.
* If you want to customise it further, you can modify the template provided or create your own.
* Instructions on the custom template creation can be found below.
*
*
* Custom Template Instructions
* ----------------------------
* You are more than welcome to modify the template supplied by EPG. You can also create your
* own template by creating an html file and applying the EPIG markers on it.
* Do not forget to change the $template value if the filename is othen than "template.html".
* The EPIG markers are:
*	{gallery}	: Your thumbnails
*	{next}		: The link to the next page
*	{back}		: The link to the back page
*	{pages}		: A list with all your pages as links
*	{title}		: The title of your album (optional)
*	{description}	: A description for your album (optional)
*	{author}	: Your Name (optional)
*
*
* EPIG License
* ------------
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details 
* (http://www.gnu.org/licenses/gpl.html).
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*
*
*	TODO: 	<meta name="publisher" content="EPIG">
*
*/

define('VERSION', '1.0.8');

class epig
{
	// Array that holds the user defined settings
	var $settings = array();

	// Current page on the album
	var $current_page;
	
	// The album photos
	var $Images = array();

	/**
	 * Default constructor
	 *
	 * @param void
	 * @return void
	 */	
	function epig()
	{
		// Default Settings (to overide the values, modify the XML file and NOT this array)
		$this->settings = array("epig_rows" => 2,					// The number of thumbnail rows in each page
					"epig_columns" => 2,					// The number of thumbnail columns in each page
					"thumbnail_width" => 100,				// The width of each thumbnail
					"thumbnail_height" => 50,				// The height of each thumbnail
					"thumbnail_quality" => 75,				// The quality of each thumbnail. Applies only to JPEG files
					"epig_author" => "Apostolos Dountsis",			// User's name
					"epig_email" => "A.Dountsis@sussex.ac.uk",		// User's e-mail address
					"epig_title" => "My EPIG Album",			// The album title
					"epig_description" => "The photos from my holidays.",	// Album description
					"template_file" => "template.html",			// Template file
					"config_file" => "config.xml");				// Config file
		
		$this->current_page = $_REQUEST['page'];
		$this->Images = array();
		$this->load_config($this->settings['config_file']);	

		if($_REQUEST['file'])
		{
			$this->generate_thumbnail($_REQUEST['file']);
		}
		else
		{	
			$this->load_images();
			$this->populate_template();
		}		
		
	}

	/**
	 * Get contents from XML file and insert into an array
	 *
	 * @param string $file
	 * @return mixed
	 */
	function xml2php($file) 
	{
		$arr_vals = array();
		$xml_parser = xml_parser_create();
	   
		if (!($fp = fopen($file, "r")))
		{
			die("I am sorry but I cannot locate your configuration file with filename <b>$file</b>.");
		}
	   
		$contents = fread($fp, filesize($file));
		fclose($fp);
		
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
		
		xml_parse_into_struct($xml_parser, $contents, $arr_vals);
		xml_parser_free($xml_parser);
		
		return $arr_vals;
	}
	
	function get_current_page()
	{
		return $_REQUEST['page'];
	}
	

	/**
	 * Load the XML config file and parse the EPIG elements
	 *
	 * @param string $file
	 * @return void
	 */	
	function load_config($file)
	{		
		$config = $this->xml2php($file);

		foreach($config as $element)
		{
//			if(!empty($element['value']))
//			{
				switch($element['tag'])
				{
					case 'title':		$this->settings['epig_title'] = $element['value'];
								break;
					case 'author':		$this->settings['epig_author'] = $element['value'];
								break;
					case 'email':		$this->settings['epig_email'] = $element['value'];
								break;
					case 'description':	$this->settings['description'] = $element['value'];
								break;
					case 'title':		$this->settings['epig_title'] = $element['value'];
								break;
					case 'template':	$this->settings['template_file'] = $element['value'];
								break;
					case 'rows':		$this->settings['epig_rows'] = $element['value'];
								break;
					case 'columns':		$this->settings['epig_columns'] = $element['value'];
								break;
					case 'width':		$this->settings['thumbnail_width'] = $element['value'];
								break;
					case 'height':		$this->settings['thumbnail_height'] = $element['value'];
								break;
					case 'quality':		$this->settings['thumbnail_quality'] = $element['value'];
								break;
				}
//			}
		}
	}	

	/**
	 * Checks allowed image extensions
	 *
	 * @param string $file
	 * @return boolean
	 */
	function validate_image_extension($file)
	{
		switch(strtolower(substr($file, -4)))
		{
			case '.jpg':	return true;
			case '.gif':	return true;
			case '.png':	return true;
			default :	return false;
		}
	
	}
	
	/**
	 * Reads and sorts all images in the current directory
	 *
	 * @param void
	 * @return void
	 */	
	function load_images()
	{
		if(empty($this->current_page))
		{
		    $this->current_page = 1;
		}
	
		$dir = opendir(dirname(__FILE__));
	
		while(false != ($file = readdir($dir)))
		{
		    if(($file != ".") && ( $file != ".." ))
		    {
		        if($this->validate_image_extension($file))
		        {
		            $this->Images[] = $file;
		        }
		    }
		}
		// Sort by Filename ASC
		sort($this->Images);
	}
	
	/**
	 * Generates the thumbnail for the specified file
	 *
	 * @param string $image_file
	 * @return void
	 */
	function generate_thumbnail($image_file)
	{
		
		if($size['mime'] == 'image/jpeg')
		{
			Header("Content-type: image/jpeg");
		}
		elseif($size['mime'] == 'image/gif')
		{
			Header("Content-type: image/gif");
		}
		elseif($size['mime'] == 'image/png')
		{
			Header("Content-type: image/png");
		}

		$size = GetImageSize($image_file);
		
		// Aspect Ratio
		$image_width = $size[0];
		$image_height = $size[1];
		
		$user_width = $this->settings['thumbnail_width'];
		$user_height = $this->settings['thumbnail_height'];
	
		$x_ratio = $user_width / $image_width;
		$y_ratio = $user_height / $image_height;
	
		if( ($image_width <= $user_width) && ($image_height <= $user_height) )
		{
			$this->settings['thumbnail_width'] = $image_width;
			$this->settings['thumbnail_height'] = $image_height;
		}
		elseif (($x_ratio * $image_height) < $user_height)
		{
			$this->settings['thumbnail_height'] = ceil($x_ratio * $image_height);
			$this->settings['thumbnail_width'] = $user_width;
		}
		else
		{
			$this->settings['thumbnail_width'] = ceil($y_ratio * $image_width);
			$this->settings['thumbnail_height'] = $user_height;
		}

		if($size['mime'] == 'image/jpeg')	// JPEG
		{
			$src_image = imagecreatefromjpeg($image_file);
			$dst_image = imagecreatetruecolor($this->settings['thumbnail_width'], $this->settings['thumbnail_height']);
			imagecopyresized($dst_image, $src_image, 0, 0, 0, 0, $this->settings['thumbnail_width'], $this->settings['thumbnail_height'], $image_width, $image_height);
	
			Header("Content-type: image/jpeg");
			imagejpeg($dst_image,'', $this->settings['thumbnail_quality']);
		}
		elseif($size['mime'] == 'image/png')	// PNG
		{
			$src_image = imagecreatefrompng($image_file);
			$dst_image = imagecreatetruecolor($this->settings['thumbnail_width'], $this->settings['thumbnail_height']);
			imagecopyresized($dst_image, $src_image, 0, 0, 0, 0, $this->settings['thumbnail_width'], $this->settings['thumbnail_height'], $image_width, $image_height);
	
			Header("Content-type: image/png");
			imagepng($dst_image);
		}	     	
	     	elseif($size['mime'] == 'image/gif')	// GIF
	     	{
			$src_image = imagecreatefromgif($image_file);
			$dst_image = imagecreatetruecolor($this->settings['thumbnail_width'], $this->settings['thumbnail_height']);
			imagecopyresized($dst_image, $src_image, 0, 0, 0, 0, $this->settings['thumbnail_width'], $this->settings['thumbnail_height'], $image_width, $image_height);
	
			Header("Content-type: image/gif");
			imagegif($dst_image);
		}
		
		imagedestroy($src_image);
		imagedestroy($dst_image);
	}
	
	/**
	 * Displays the images and calculates the tables
	 *
	 * @param void
	 * @return string
	 */
	function build_gallery()
	{	
		$html = "<table cellspacing=\"5\">\n";
	
		$total_page = ($this->settings['epig_rows']) * ($this->settings['epig_columns']);
	
		if($this->current_page == 1)
		{
			if(sizeof($this->Images) < $total_page)
			{
				$total_page = sizeof($this->Images);
			}
			$col_count = 0;
			for( $i=0; $i<$total_page; $i++ )
			{
				if($col_count == $this->settings['epig_columns'])
				{
					$html .= "</tr><tr>\n";
					$col_count = 0;
				 }
				 
				$size = GetImageSize($this->Images[$i]);

				// Popup window dimensions
				$offset = 40;
				$image_width = $size[0] + $offset;
				$image_height = $size[1] + $offset;
				
				 $html .= "<td align=\"center\">
				 		<a href=\"#\" onClick=\"javascript:window.open('{$this->Images[$i]}','actualimage','toolbar=0,menubar=0,width=$image_width,height=$image_height,resizable=1,scrollbars=0,status=0');\" title=\"Click on the thumbnail to see the image in actual size\">
				 		<img class=\"thumbnail\" border=\"0\" src=\"index.php?file={$this->Images[$i]}\">
				 		</a>
				 	   </td>\n";
				 $col_count++;
			}
		}
		else
		{
			$col_count = 0;
			$range = $total_page * $this->current_page;
			$range_start = $range - $total_page;
		    	for( $i=$range_start; $i<$range; $i++ )
		    	{
				if($col_count == $this->settings['epig_columns'])
				{
					$html .= "</tr><tr>\n";
					$col_count = 0;
				}
				if( !empty($this->Images[$i]) )
				{
					$size = GetImageSize($this->Images[$i]);

					// Aspect Ratio
					$offset = 40;
					$image_width = $size[0] + $offset;
					$image_height = $size[1] + $offset;

					$html .= "<td align=\"center\">
				 		<a href=\"#\" onClick=\"javascript:window.open('{$this->Images[$i]}','actualimage','toolbar=0,menubar=0,width=$image_width,height=$image_height,resizable=1,scrollbars=0,status=0');\" title=\"Click on the thumbnail to see the image in actual size\">
				 		<img class=\"thumbnail\" border=\"0\" src=\"index.php?file={$this->Images[$i]}\">
				 		</span>
						  </td>\n";
				}
				else
				{
					$html .= "&nbsp;\n";
				}
				$col_count++;
		    	}
	
		}
		
		// Copyright footer
		$html .= "</table> <div align=\"center\" style=\"font-size:small;color:blue;\">Powered by <a href=\"http://www.gamecycle.net/epig/\">Easy Peasy Image Gallery (EPIG) ".VERSION." </a></div><br />
			<div style=\"font-size:x-small\">Click on a thumbnail to see the image in actual size</div><br />\n";
		
		return $html;
	}
	
	/**
	 * Displays the 'Next Page' link
	 *
	 * @param void
	 * @return string
	 */
	function build_next()
	{	
		$html = "";
		$pages = ceil(sizeof($this->Images) / ($this->settings['epig_rows'] * $this->settings['epig_columns']));
	
		if($this->current_page == $pages)
		{
			$html .= "Next Page";
		}
		else
		{
			$next_page = $this->current_page + 1;
			$html .= "<a href=\"index.php?page=$next_page\">Next Page</a>";
		}
	
		return $html;
	}
	
	/**
	 * Displays the 'Previous Page' link
	 *
	 * @param void
	 * @return string
	 */
	function build_back()
	{	
		$html = "";
	
		if($this->current_page == 1)
		{
			$html .= "Previous Page\n";
		}
		else
		{
			$back_page = $this->current_page - 1;
			$html .= "<a href=\"index.php?page=$back_page\">Previous Page</a>";
		}
	
		return $html;
	}
	
	/**
	 * Displays the page number as links
	 *
	 * @param void
	 * @return string
	 */
	function build_numbers()
	{
		$html = "";
	
		$pages = ceil(sizeof($this->Images) / ($this->settings['epig_rows'] * $this->settings['epig_columns']));
		$i=1;
		while( $i < $pages + 1 )
		{
		    if($this->current_page == $i)
		    {
		    	$html .= "<b>$i</b>\n";
		    }
		    else
		    {
		    	$html .= "<a href=\"index.php?page=$i\">$i</a>\n";
		    }
	
		    if($i % 5 == 0)	// if the current page can be divided with 5 then line break
	    	{
	    		$html .= "<br />";
	    	}	    
	    	
		    $i++;
		}
	
		return $html;
	}
	

	/**
	 * Loads and populates the template with the EPIG markers
	 *
	 * @param void
	 * @return string
	 */	
	function populate_template()
	{	
		$template_file = '';
	
		// Check if the template exists in the filesystem.
		if(!file_exists($this->settings['template_file']))
		{
			print("I am sorry but I cannot locate your template with filename <b>$this->settings['template_file']</b>.");
		}
		else
		{
			// Read the template file and load it into the appropriate attribute.
			$template_file = implode("", file($this->settings['template_file']));
		}
	
		// Substitute the template markers {} with the contents of the attribute.
		$template_file = str_replace('{gallery}', $this->build_gallery(), $template_file);
		$template_file = str_replace('{next}', $this->build_next(), $template_file);
		$template_file = str_replace('{back}', $this->build_back(), $template_file);
		$template_file = str_replace('{pages}', $this->build_numbers(), $template_file);
	
		// Optional markers
		if(!empty($this->settings['epig_email']))
		{
			$this->settings['description'] .= "<br /> You can always email the author of the album at <a href=\"mailto:{$this->settings['epig_email']}\">{$this->settings['epig_email']}</a>";
		}
		$template_file = str_replace('{title}', $this->settings['epig_title'], $template_file);
		$template_file = str_replace('{description}', $this->settings['description'], $template_file);
		$template_file = str_replace('{author}', $this->settings['epig_author'], $template_file);
		
		// Render the html file in the browser
		print($template_file);
}
	
}

/**
* Instantiate the class
*/

$album = new epig();

?>