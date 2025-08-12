<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------------------+
// | WebCards Version 1.0 - A powerful, easy to configure e-card system               |
// | Copyright (C) 2003  Chris Charlton (corbyboy@hotmail.com)                        |
// |                                                                                  |
// |     This program is free software; you can redistribute it and/or modify         |
// |     it under the terms of the GNU General Public License as published by         |
// |     the Free Software Foundation; either version 2 of the License, or            |
// |     (at your option) any later version.                                          |
// |                                                                                  |
// |     This program is distributed in the hope that it will be useful,              |
// |     but WITHOUT ANY WARRANTY; without even the implied warranty of               |
// |     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                |
// |     GNU General Public License for more details.                                 |
// |                                                                                  |
// |     You should have received a copy of the GNU General Public License            |
// |     along with this program; if not, write to the Free Software                  |
// |     Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    |
// |                                                                                  |
// | Authors: Chris Charlton <corbyboy@hotmail.com>                                   |
// | Official Homepage: http://webcards.sourceforge.net                               |
// | Project Homepage: http://www.sourceforge.net/projects/webcards                   |
// +----------------------------------------------------------------------------------+
//
// $Id: index.php,v 1.00 2005/07/24 15:54:30 chrisc Exp $

//Debug Mode
/*
print "<pre>";
print_r($HTTP_GET_VARS);
print_r($HTTP_POST_VARS);
print "</pre>";
*/


/*-----------------------------------------------
  ENSURE CONTENT CANNOT BE ACCESSED DIRECTLY
 ------------------------------------------------*/
define("LOADED", 1);

//First of all, if lock.cgi doesn't exist we must exit
if(!file_exists("lock.cgi"))
{
	die("The program is not properly installed and has been halted as a precaution. Please contact the webmaster.");
}

//set_magic_quotes_runtime(0);

require "./config.php";
require "./source/functions.php";

$HTTP_POST_VARS = clean_incoming_data();
$HTTP_GET_VARS = clean_incoming_data();

/*-----------------------------------------------
  REQUIRE LANGUAGE FILES
 ------------------------------------------------*/

if(isset($HTTP_COOKIE_VARS['wc_lang']) && $HTTP_COOKIE_VARS['wc_lang'] != "")
{
	$lang_dir = $HTTP_COOKIE_VARS['wc_lang'];
}
else
{
	$lang_dir = $conf['default_pub_lang'];
}

$lang_dir = is_dir($conf['dir'] . "lang/" . $lang_dir) ? $lang_dir : "English";

	require_once "./lang/" . $lang_dir . "/index.php";
	require_once "./lang/" . $lang_dir . "/global.php";

/*-----------------------------------------------
  REQUIRE DB DRIVER
 ------------------------------------------------*/
if(!file_exists("./source/drivers/" . $conf['db_driver'] . ".php"))
{
	die("Unable to include the driver file. Please manually check your database settings and folder names");
}
require_once "./source/drivers/" . $conf['db_driver'] . ".php";

if($conf['buffer'] == "y")
{
	ob_start("ob_gzhandler");
}

$DB = new DB($conf['dbhost'], $conf['dbuser'], $conf['dbpass'], $conf['dbname'], false);
if (!$DB->connect())
{
	error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
}
//Connect to the DB

if(check_ip(getip()))
{
	output($conf['ban_message']);
}
//Die if IP address is banned

	switch($HTTP_GET_VARS['act'])
	{
		case 'form':
		$to_do = parse_template();
		break;
		case 'search':
		$to_do = choose_image_template("", "yes");
		break;
		case 'adv_search':
		$to_do = adv_search_form();
		break;
		case 'do_adv_search':
		$to_do = choose_image_template("", "yes");
		break;
		case 'preview':
		$to_do = preview();
		break;
		case 'send_card':
		$to_do = add_n_send();
		break;
		case 'view_stats':
		$to_do = view_stats_page();
		break;
		case 'browse_cat':
		$to_do = choose_image_template();
		break;
		case 'prefs':
		$to_do = set_prefs();
		break;
		default:
		$to_do = view_stats_page();
		break;
	}

	if($conf['auto_expire'] == "y")
	{
		expire();
	}

function view_stats_page()
{
global $conf, $DB, $lang;

	if (!$DB->query("SELECT id, title, description FROM " . $conf['dbprefix'] . "categories ORDER by title ASC"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$search_box = "<select name=\"search_cat\">\n<option value=\"all\">" . $lang['all_cats'] . "</option>";
	$cat_list = "<tr><td align=\"left\"><img src=\"./site_images/small_arrow.gif\" alt=\"nav\" title=\"nav\"></td><td align=\"left\"><span class=\"cat_list\"><a href=\"" . $conf['script'] . "?act=browse_cat\"><b>" . $lang['all_cats'] . "</b></a></span><br /></td></tr>";

	while($row=$DB->fetch_array())
	{
		$search_box .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "</option>";
		$cat_list .= "<tr><td align=\"left\"><img src=\"./site_images/small_arrow.gif\" alt=\"nav\"  title=\"nav\"></td><td align=\"left\"><span class=\"cat_list\"><a href=\"" . $conf['script'] . "?act=browse_cat&amp;cat=" . $row['id'] . "\">" . $row['title'] . "</a></span><br /></td></tr>\n";
	}

	$search_box .= "</select>";

	//If it has not been set in the ACP, set the number of new and popular images displayed to 6
	$num_new_pop_imgs = isset($conf['new_pop_imgs']) ? $conf['new_pop_imgs'] : 6;

	if (!$DB->query("SELECT * FROM " . $conf['dbprefix'] . "images ORDER by id DESC LIMIT 0," . $num_new_pop_imgs . ""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row=$DB->fetch_array())
	{
		$new_images .= "<br />" . getMacro(getExt($row['thumb']), "macro") . "<br /><a href=\"" . $conf['script'] . "?act=form&pic=" . $row['id'] . "\">" . $row['name'] . "</a><br />";
		$new_images = preg_replace("/{{name}}/i", $row['name'], $new_images);
		
		//It is important to set the width and height variables to 0 each time or they will be reused between images
		$width_thumb = "";
		$height_thumb = "";
		if($row['width_thumb'] != "" && $row['width_thumb'] != "0" && $row['width_thumb'] != "NULL")
		{
			$width_thumb = "width=\"" . $row['width_thumb'] . "\"";
		}
		if($row['height_thumb'] != "" && $row['height_thumb'] != "0" && $row['height_thumb'] != "NULL")
		{
			$height_thumb = "height=\"" . $row['height_thumb'] . "\"";
		}
		$new_images = preg_replace("/{{width}}/i", $width_thumb, $new_images);
		$new_images = preg_replace("/{{height}}/i", $height_thumb, $new_images);
		if($row['thumb_type'] == "upload")
		{
			$new_images = preg_replace("/{{img}}/i", $conf['url'] . "images/thumbs/" . $row['thumb'], $new_images);
		}
		else
		{
			$new_images = preg_replace("/{{img}}/i", $row['thumb'], $new_images);
		}
	}

	if (!$DB->query("SELECT i.*, s.pic FROM " . $conf['dbprefix'] . "sent_cards s, " . $conf['dbprefix'] . "images i WHERE s.pic=i.id GROUP BY s.pic ORDER by s.pic DESC, i.id ASC LIMIT 0," . $num_new_pop_imgs . ""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while($row=$DB->fetch_array())
	{
		$popular_images .= "<br />" . getMacro(getExt($row['thumb']), "macro") . "<br /><a href=\"" . $conf['script'] . "?act=form&pic=" . $row['id'] . "\">" . $row['name'] . "</a><br />";
		$popular_images = preg_replace("/{{name}}/i", $row['name'], $popular_images);
		
		//It is important to set the width and height variables to 0 each time or they will be reused between images
		$width_thumb = "";
		$height_thumb = "";
		if($row['width_thumb'] != "" && $row['width_thumb'] != "0" && $row['width_thumb'] != "NULL")
		{
			$width_thumb = "width=\"" . $row['width_thumb'] . "\"";
		}
		if($row['height_thumb'] != "" && $row['height_thumb'] != "0" && $row['height_thumb'] != "NULL")
		{
			$height_thumb = "height=\"" . $row['height_thumb'] . "\"";
		}
		$popular_images = preg_replace("/{{width}}/i", $width_thumb, $popular_images);
		$popular_images = preg_replace("/{{height}}/i", $height_thumb, $popular_images);
		
		if($row['thumb_type'] == "upload")
		{
			$popular_images = preg_replace("/{{img}}/i", $conf['url'] . "images/thumbs/" . $row['thumb'], $popular_images);
		}
		else
		{
			$popular_images = preg_replace("/{{img}}/i", $row['thumb'], $popular_images);
		}
	}

	$cat_to_show = isset($conf['cat_to_show']) ? $conf['cat_to_show'] : 6;

	if(!$result = mysql_query("SELECT id FROM " . $conf['dbprefix'] . "categories ORDER by RAND() LIMIT 0," . $cat_to_show . ""))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	while ($row = mysql_fetch_array($result))
	{
		$cq = $DB->query("SELECT c.id AS cat_id, c.title, i.name, i.thumb, i.thumb_type, i.width_thumb, i.height_thumb, i.id AS img_id
		FROM " . $conf['dbprefix'] . "images i, " . $conf['dbprefix'] . "categories c
		WHERE c.id = i.cat
		AND c.id = ${row['id']}
		ORDER by RAND()
		LIMIT 3");
		$temp_count = 1;
		while ($cr = $DB->fetch_array($cq))
		{
			if($temp_count == 1)
			{
				$browse_images .= "<table class=\"noborder\" width=\"100%\">\n";
				$browse_images .= "<tr>\n<td align=\"left\">";
				$browse_images .= getMacro(getExt($cr['thumb']), "macro");
				$browse_images = preg_replace("/{{name}}/i", $cr['name'], $browse_images);
				
				//It is important to set the width and height variables to 0 each time or they will be reused between images
				$width_thumb = "";
				$height_thumb = "";
				if($cr['width_thumb'] != "" && $cr['width_thumb'] != "0" && $cr['width_thumb'] != "NULL")
				{
					$width_thumb = "width=\"" . $cr['width_thumb'] . "\"";
				}
				if($cr['height_thumb'] != "" && $cr['height_thumb'] != "0" && $cr['height_thumb'] != "NULL")
				{
					$height_thumb = "height=\"" . $cr['height_thumb'] . "\"";
				}
				$browse_images = preg_replace("/{{width}}/i", $width_thumb, $browse_images);
				$browse_images = preg_replace("/{{height}}/i", $height_thumb, $browse_images);
				
				if($cr['thumb_type'] == "upload")
				{
					$browse_images = preg_replace("/{{img}}/i", $conf['url'] . "images/thumbs/" . $cr['thumb'], $browse_images);
				}
				else
				{
					$browse_images = preg_replace("/{{img}}/i", $cr['thumb'], $browse_images);
				}
				$browse_images .= "</td>\n";
				$browse_images .= "<td align=\"left\" width=\"100%\" valign=\"top\"><span class=\"sub_header\">" . $cr['title'] . "</span><br />\n";
			}
				$browse_images .="<a href=\"" . $conf['script'] . "?act=form&amp;pic=" . $cr['img_id'] . "\" title=\"" . $cr['name'] . "\">" . $cr['name'] . "<br />\n";

			if($temp_count == 3 || $temp_count == $DB->num_rows())
			{
				$browse_images .="</table>";
			}


			$temp_count ++;
		}
		unset($temp_count);
	}

	if(!$fp = @fopen("./templates/view_image_stats.html", "r"))
	{
		error($lang['cannot_open_file'] . " <b>view_image_stats.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
	}
	$data = @fread($fp, filesize("./templates/view_image_stats.html"));

	$data = preg_replace("/{{action}}/i", $conf['script'], $data);
	$data = preg_replace("/{{new_images}}/i", $new_images, $data);
	$data = preg_replace("/{{popular_images}}/i", $popular_images, $data);
	$data = preg_replace("/{{browse_images}}/i", $browse_images, $data);
	$data = preg_replace("/{{search_select_box}}/i", $search_box, $data);
	$data = preg_replace("/{{cat_jump}}/i", $cat_list, $data);
	return $data;

}

function adv_search_form($warnings="")
{
global $conf, $DB, $lang;

	if(!$render_open = @fopen("./templates/adv_search.html", "r"))
        {
		return error($lang['cannot_open_file'] . " <b>./templates/adv_searcg.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
        }
	$render = @fread($render_open, filesize("./templates/adv_search.html"));

	if (!$DB->query("SELECT id, title FROM " . $conf['dbprefix'] . "categories ORDER by title ASC"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	while($row=$DB->fetch_array())
	{
		$cat_list .= "<option value=\"" . $row['id'] . "\">" . $row['title'] . "</option>\n";
	}

	$data = preg_replace("/{{action}}/i", $conf['script'], $render);
	$data = preg_replace("/{{cat_select}}/i", $cat_list, $data);
	$data = preg_replace("/{{warnings}}/i", $warnings, $data);

	return $data;

}

function choose_image_template($warnings="", $search="no")
{
global $conf, $HTTP_GET_VARS, $HTTP_POST_VARS, $DB, $lang;
$img_count = 0;

	if($search == "yes")
	{
		
		if($HTTP_GET_VARS['act'] == "search") // ie we are only basic searching, not advanced searching
		{
			$sql = "SELECT * FROM " . $conf['dbprefix'] . "images WHERE name LIKE '%" . $HTTP_POST_VARS['search_text'] . "%'";
			if($HTTP_POST_VARS['search_cat'] != "all" && isset($HTTP_POST_VARS['search_cat']))
			{
				$sql .= " AND cat=\"" . $HTTP_POST_VARS['search_cat'] . "\"";
			}
			$sql .= " ORDER BY id DESC";
		}
		else	// set up sql for advanced search
		{
		
			//Check that the user has entered some words to search and chosen some categories
			if($HTTP_POST_VARS['search_terms'] == "")
			{
				return adv_search_form($lang['no_search_terms']);
			}
			if(count($HTTP_POST_VARS['cat_search']) == 0)
			{
				return adv_search_form($lang['no_cat_selected']);
			}
		
			$sql = "SELECT * FROM " . $conf['dbprefix'] . "images WHERE MATCH (name) AGAINST "; //base sql for all advanced queries
			if($HTTP_POST_VARS['match_mode'] == "any_words")
			{
				$sql .= "('" . $HTTP_POST_VARS['search_terms'] . "' IN BOOLEAN MODE)";
			}
			else if($HTTP_POST_VARS['match_mode'] == "all_words")
			{
				$word_array = explode(" ", $HTTP_POST_VARS['search_terms']);
				foreach($word_array as $word)
				{
					$word_string .= "+" . $word. " ";
				}
				$sql .= "('" . $word_string . "' IN BOOLEAN MODE)";
			}
			else if($HTTP_POST_VARS['match_mode'] == "exact_phrase")
			{
				$sql .= "('\"" . $HTTP_POST_VARS['search_terms'] . "\"' IN BOOLEAN MODE)";
			}

			//Now set up the sql for the categories chosen
			if(!in_array("all", $HTTP_POST_VARS['cat_search'])) //ie we are NOT searching all categories, therefore must modify the sql some more
			{
				foreach($HTTP_POST_VARS['cat_search'] as $key => $cat)
				{
					if($key != "0")
					{
						$cat_sql_list .= " OR cat = '" . $cat . "'";
					}
					else
					{
						$cat_sql_list .= "cat = '" . $cat . "'";
					}
				}

				//Now put all the sql together
				$sql .= " AND (" . $cat_sql_list . ")";
			}
		}
	}

	else
	{
		if (!isset($HTTP_GET_VARS['cat']) || $HTTP_GET_VARS['cat'] == "")
		{
			$sql = "SELECT * FROM " . $conf['dbprefix'] . "images ORDER BY id DESC";
			$cat_name = "all categories";
		}
		else
		{
			$sql = "SELECT * FROM " . $conf['dbprefix'] . "images WHERE cat=\"" . $HTTP_GET_VARS['cat'] . "\" ORDER BY id DESC";
		}

	/*/////////////////////////////
	// Construct limit box and < prev | next > links
	//////////////////////////////*/

	$prev_next = "";

	//Unfortunately, we must execute another SQL query to count the total number of rows for this cat
	$DB->query($sql);
	$numrows = $DB->num_rows();

	//Ensure the values for limiting cards seen are valid to insert into DB query
	$offset = isset($HTTP_GET_VARS['offset']) && is_numeric($HTTP_GET_VARS['offset']) ? $HTTP_GET_VARS['offset'] : 0;
	$limit = isset($HTTP_GET_VARS['limit']) && is_numeric($HTTP_GET_VARS['limit']) ? $HTTP_GET_VARS['limit'] : 10;

	$limit_box = "<select name=\"limit\">\n";
	$limit_box .= "<option value=\"5\""; if ($limit == 5) { $limit_box .= " selected"; } $limit_box .= " />5\n";
	$limit_box .= "<option value=\"10\""; if ($limit == 10) { $limit_box .= " selected"; } $limit_box .= " />10\n";
	$limit_box .= "<option value=\"20\""; if ($limit == 20) { $limit_box .= " selected"; } $limit_box .= " />20\n";
	$limit_box .= "<option value=\"50\""; if ($limit == 50) { $limit_box .= " selected"; } $limit_box .= " />50\n";
	$limit_box .= "<option value=\"100\""; if ($limit == 100) { $limit_box .= " selected"; } $limit_box .= " />100\n";
	$limit_box .= "</select>";

	if ($numrows <= $limit)
	{
		$prev_next .= $lang['1_page'];
	}
	else
	{
		$pages=intval($numrows/$limit);
		$totpages = $pages+1;
		$prev_next .= $lang['pages'];
    		if ($offset!=0)
		{
			$prevoffset=$offset-$limit;
			$prev_next .= "<a href=\"" . $conf['script'] . "?act=browse_cat&amp;cat=" . $HTTP_GET_VARS['cat'] . "&amp;limit=$limit&amp;offset=$prevoffset\"> " . $lang['prev'] . "</a>&nbsp;\n";
   		}
   
   		if ($numrows%$limit)
		{
			$pages++;
   		}
		for ($i=1;$i<=$pages;$i++)
		{
			$cpage = $offset + $limit;
			$cpage = $cpage/$limit;
			if ($cpage == "$i")
			{
				$prev_next .= "<b>$i</b>&nbsp;\n";
			}
			else
			{
       				$newoffset=$limit*($i-1);
       				$prev_next .= "<a href=\"" . $conf['script'] . "?act=browse_cat&amp;cat=" . $HTTP_GET_VARS['cat'] . "&amp;limit=$limit&offset=$newoffset\">$i</a>&nbsp;\n";
			}
		}
		$cpage = $offset + $limit;
		$cpage = $cpage/$limit;
   		if ((($cpage*$limit)<=$numrows) && $pages!=1)
		{
       			$newoffset=$offset+$limit;
       			$prev_next .= "<a href=\"" . $conf['script'] . "?act=browse_cat&amp;cat=" . $HTTP_GET_VARS['cat'] . "&amp;limit=$limit&offset=$newoffset\">" . $lang['next'] . "</a>\n";
   		}    
	}

	$sql .= " LIMIT " . $offset . ", " . $limit . "";

	}

	if (!$DB->query($sql))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	if($DB->num_rows() <= "0")
	{
		$img_output .= $lang['no_img_found'] . "<br /><br /><br /><br /><br /><br />";
	}
	$to_repeat = ($conf['img_per_row'] - $DB->num_rows()%$conf['img_per_row']);
	while ($row = $DB->fetch_array())
	{
	$macro = getMacro(getExt($row['thumb']));
	$macro = preg_replace("/{{name}}/i", $row['name'], $macro);
	
	//It is important to reset the width and height variables each time or they will be reused between images
	$width_thumb = "";
	$height_thumb = "";
	if($row['width_thumb'] != "" && $row['width_thumb'] != "0" && $row['width_thumb'] != "NULL")
	{
		$width_thumb = "width=\"" . $row['width_thumb'] . "\"";
	}
	if($row['height_thumb'] != "" && $row['height_thumb'] != "0" && $row['height_thumb'] != "NULL")
	{
		$height_thumb = "height=\"" . $row['height_thumb'] . "\"";
	}
	$macro = preg_replace("/{{width}}/i", $width_thumb, $macro);
	$macro = preg_replace("/{{height}}/i", $height_thumb, $macro);
	
	if($row['thumb_type'] == "upload")
	{
		$macro = preg_replace("/{{img}}/i", $conf['url'] . "images/thumbs/" . $row['thumb'], $macro);
	}
	else
	{
		$macro = preg_replace("/{{img}}/i", $row['thumb'], $macro);
	}

		if ($img_count%$conf['img_per_row'] == "0")
		{
			$img_output .= "<tr>";
		}
		$img_output .= "<td align=\"center\" valign=\"top\">";
		$img_output .= $macro;
		$img_output .= "<br><a href=\"" . $conf['script'] . "?act=form&pic=" . $row['id'] . "\">" . $row['name'] . "</a></td>\n";
		if ($img_count%$conf['img_per_row'] == $conf['img_per_row'] - 1 && $img_count!=$DB->num_rows())
		{
			$img_output .= "</tr>\n\n";
		}
		$img_count++;
	}
	if($to_repeat != $conf['img_per_row'])
	{
		$img_output .= str_repeat("<td>&nbsp;</td>\n", $to_repeat);		
	}

		if(!$fp = @fopen("./templates/select_img.html", "r"))
       	{
		return error($lang['cannot_open_file'] . " <b>./templates/select_img.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);

       	}
       	else
       	{
		$data = @fread($fp, filesize("./templates/select_img.html"));
		$recip = preg_replace("/{{choose_pic}}/i", $img_output, $data);
	}

	if (!$DB->query("SELECT id, title, description FROM " . $conf['dbprefix'] . "categories ORDER BY title ASC"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	$select_box = "<select name=\"search_cat\">\n
			<option value=\"all\">" . $lang['all_cats'] . "</option>";

	$cat .= "<tr><td align=\"left\"><img src=\"./site_images/small_arrow.gif\" alt=\"nav\"  title=\"nav\"></td><td aign=\"left\"><span class=\"cat_list\"><a href=\"" . $conf['script'] . "?act=browse_cat\" title=\"" . $lang['browse_cats'] . " - " . $lang['all_cats'] . "\">" . $lang['all_cats'] . "</a><span></td></tr>";
	while ($row=$DB->fetch_array())
	{
		$select_box .= "<option value=\"" . $row['id'] . "\"";
		if($HTTP_GET_VARS['cat'] == $row['id'] || $HTTP_POST_VARS['search_cat'] == $row['id'])
		{
			$select_box .= " selected=\"selected\"";
		}
		$select_box .= ">" . $row['title'] . "</option>";

		if(!isset($HTTP_GET_VARS['cat']) || $HTTP_GET_VARS['cat'] == "")
		{
			if($search == "yes")
			{
				$current_cat = $lang['search_results'];
			}
			else
			{
				$current_cat = $lang['all_cats'];
			}
		}
		if ($HTTP_GET_VARS['cat'] != $row['id'])
		{
			$cat .= "<tr><td align=\"left\"><span class=\"cat_list\"><img src=\"./site_images/small_arrow.gif\" alt=\"nav\" title=\"nav\"></td><td align=\"left\"><a href=\"" . $conf['script'] . "?act=browse_cat&amp;cat=" . $row['id'] . "\" title=\"" . $lang['browse_cats'] . " - " . $row['title'] . "\">" . $row['title'] . "</a></li><br /></span></td></tr>";
		}
		else
		{
			$cat .= "<tr><td align=\"left\"><img src=\"./site_images/small_arrow.gif\" alt=\"nav\"  title=\"nav\"></td><td align=\"left\"><b>" . $row['title'] . "</b></td></tr>";
			$cat .= "<tr><td align=\"left\">&nbsp;</td><td align=\"left\"><i> " . nl2br($row['description']) . "</i></td></tr>";
			$current_cat = $row['title'];
		}
	}
	$select_box .= "</select>";

	if($HTTP_GET_VARS['act'] == "search" || $HTTP_GET_VARS['act'] == "do_adv_search")
	{
		$start_prev_next = "<!-- \n Hide drop-box because we are searching";
		$end_prev_next = "-->";
		$search_terms = $HTTP_POST_VARS['search_text'];
	}
	else
	{
		$start_prev_next = "<!-- Drop-box to change number viewed per page -->";
		$end_prev_next = "<!-- End drop-boc -->";
		$search_terms = $lang['enter_terms'];
	}

	$recip = preg_replace("/{{cat_jump}}/i", $cat, $recip);
	$recip = preg_replace("/{{search_select_box}}/i", $select_box, $recip);
	$recip = preg_replace("/{{cat_name}}/i", $current_cat, $recip);
	$recip = preg_replace("/{{cat_id}}/i", $HTTP_GET_VARS['cat'], $recip);
	$recip = preg_replace("/{{action}}/i", $conf['script'], $recip);
	$recip = preg_replace("/{{warnings}}/i", $warnings, $recip);
	$recip = preg_replace("/{{limit_box}}/i", $limit_box, $recip);
	$recip = preg_replace("/{{prev_next_link}}/i", $prev_next, $recip);
	$recip = preg_replace("/{{start_prev_next}}/i", $start_prev_next, $recip);
	$recip = preg_replace("/{{end_prev_next}}/i", $end_prev_next, $recip);
	$recip = preg_replace("/{{search_terms}}/i", $search_terms, $recip);
	return $recip;
}

function parse_template($warnings="")
// Parses the main form and put all our variables and warnings in
{
global $conf, $HTTP_POST_VARS, $HTTP_GET_VARS, $DB, $lang;

	$selected_pic = isset($HTTP_POST_VARS['pic']) ? $HTTP_POST_VARS['pic'] : $HTTP_GET_VARS['pic'];

	if (!isset($selected_pic))
	{
		return choose_image_template("<br /><br />" . $lang['please_select_image']);
	}

	$selected_pic = (int) $selected_pic;

	$define = array("title", "sender_name", "sender_email", "message");
	foreach ($define as $define)
	{
		if (!isset($HTTP_POST_VARS[$define]))
		{
			$HTTP_POST_VARS[$define] = "";
		}
	}
	// For error reporting's sake make the undefined variables blank
	// Now do recipients template
	for($i=0; $i<=($conf['max_recip'] - 1); $i++)
	{
			$recip .= "Recipient " . ($i + 1) . " email<br />";

			$recip .= "<input size=\"50\" type=\"text\" name=\"recip_email[]\" value=\"" . $HTTP_POST_VARS['recip_email'][$i] . "\" class=\"chunky\" /><br /><br />";
        }

	if($conf['enable_notify'] == "y")
	{
		$checked = $HTTP_POST_VARS['notify'] == "1" ? "checked=\"checked\" " : "";
		$notify_box = "<br /><br /><input type=\"checkbox\" name=\"notify\" id=\"notify\" value=\"1\" class=\"chunky\" " . $checked . "/><label for=\"notify\">" . $lang['notify_when_picked'] . "</label>";
	}

	if (!$DB->query("SELECT name, url, img_type, width, height FROM " . $conf['dbprefix'] . "images WHERE id=\"" . $selected_pic . "\" LIMIT 1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	while($row = $DB->fetch_array())
	{
		$macro = getMacro(getExt($row['url']), "macro");
		$macro = preg_replace("/{{name}}/i", $row['name'], $macro);
		
		//It is important to set the width and height variables to 0 each time or they will be reused between images
		$width = "";
		$height = "";
		if($row['width'] != "" && $row['width'] != "0" && $row['width'] != "NULL")
		{
			$width = "width=\"" . $row['width'] . "\"";
		}
		if($row['height'] != "" && $row['height'] != "0" && $row['height'] != "NULL")
		{
			$height = "height=\"" . $row['height'] . "\"";
		}
		$macro = preg_replace("/{{width}}/i", $width, $macro);
		$macro = preg_replace("/{{height}}/i", $height, $macro);
		
		if($row['img_type'] == "upload")
		{
			$macro = preg_replace("/{{img}}/i", $conf['url'] . "images/" . $row['url'], $macro);
		}
		else
		{
			$macro = preg_replace("/{{img}}/i", $row['url'], $macro);
		}
		$img_name = $row['name'];
	}

	//Now render the font and background options as defined in the config file
	$bg_select_array = explode(",", $conf['font_bg_colours']);
	foreach($bg_select_array as $colour)
	{
		$bg_select .= "<option value=\"" . $colour . "\"";
		if($HTTP_POST_VARS['bg_color'] == $colour)
		{
			$bg_select .= " selected=\"selected\"";
		}
		$bg_select .= ">" . $colour . "</option>\n";
	}
	$font_colour_select_array = explode(",", $conf['font_colours']);
	foreach($font_colour_select_array as $colour)
	{
		$font_colour_select .= "<option value=\"" . $colour . "\"";
		if($HTTP_POST_VARS['font_color'] == $colour)
		{
			$font_colour_select .= " selected=\"selected\"";
		}
		$font_colour_select .= ">" . $colour . "</option>\n";
	}
	$font_face_select_array = explode(",", $conf['font_faces']);
	foreach($font_face_select_array as $font)
	{
		$font_face_select .= "<option value=\"" . $font . "\"";
		if($HTTP_POST_VARS['font_face'] == $font)
		{
			$font_face_select .= " selected=\"selected\"";
		}
		$font_face_select .= ">" . $font . "</option>\n";
	}
	$font_size_select_array = explode(",", $conf['font_sizes']);
	foreach($font_size_select_array as $size)
	{
		$font_size_select .= "<option value=\"" . $size . "\"";
		if($HTTP_POST_VARS['font_size'] == $size)
		{
			$font_size_select .= " selected=\"selected\"";
		}
		$font_size_select .= ">" . $size . "</option>\n";
	}

	if(!$fp = @fopen("./templates/main_form.html", "r"))
        {
                return error($lang['cannot_open_file'] . " <b>./templates/main_form.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
        }
        else
        {
		//If the user has made a mistake and we are looking at an error page, the <br> and <br /> tags must be converted back to \n
		$HTTP_POST_VARS['message'] = preg_replace("/<br>/i", "\n", $HTTP_POST_VARS['message']);
		$HTTP_POST_VARS['message'] = preg_replace("/<br \/>/", "\n", $HTTP_POST_VARS['message']);

		$data = @fread($fp, filesize("./templates/main_form.html"));
		$data = preg_replace("/{{action}}/i", $conf['script'], $data);
		$data = preg_replace("/{{show_pic}}/i", $macro . "<br />" . $img_name, $data);
		$data = preg_replace("/{{chosen_pic}}/i", $selected_pic, $data);
		$data = preg_replace("/{{title}}/i", $HTTP_POST_VARS['title'], $data);
		$data = preg_replace("/{{sender_name}}/i", $HTTP_POST_VARS['sender_name'], $data);
		$data = preg_replace("/{{sender_email}}/i", $HTTP_POST_VARS['sender_email'], $data);
		$data = preg_replace("/{{message}}/i", $HTTP_POST_VARS['message'], $data);
		$data = preg_replace("/{{max_message_length}}/i", $conf['max_message_length'], $data);
		$data = preg_replace("/{{warnings}}/i", $warnings, $data);
		$data = preg_replace("/{{notify_box}}/i", $notify_box, $data);
		$data = preg_replace("/{{max_recip}}/i", $conf['max_recip'], $data);
		$data = preg_replace("/{{bg_select}}/i", $bg_select, $data);
		$data = preg_replace("/{{font_colour_select}}/i", $font_colour_select, $data);
		$data = preg_replace("/{{font_face_select}}/i", $font_face_select, $data);
		$data = preg_replace("/{{font_size_select}}/i", $font_size_select, $data);
		$form_output = preg_replace("/{{recipients}}/i", $recip, $data);
        }
	return $form_output;
}

function preview()
{
global $HTTP_POST_VARS, $conf, $DB, $lang;

	$required = array("title" => $lang['card_title'], "sender_name" => $lang['your_name'], "sender_email" => $lang['your_email'], "pic" => $lang['pic_2_send'], "bg_color" => $lang['bg_colour'], "font_color" => $lang['font_colour'], "font_face" => $lang['font_face'], "font_size" => $lang['font_size'], "message" => $lang['msg_2_send']);
	foreach($required as $k => $v)
	{
		$actual = $HTTP_POST_VARS[$k];
		if ($actual == "")
		{
			return parse_template($lang['val_4_field'] . " \"" . $v . "\" " . $lang['is_required']);
		}
	}
	if(!isset($HTTP_POST_VARS['sender_email']) || $HTTP_POST_VARS['sender_email'] == "")
	{
		return parse_template($lang['no_email']);
	}
//	if (!check_email_format($HTTP_POST_VARS['sender_email']))
//	{
//		return parse_template($lang['email_ad'] . "\"" . $HTTP_POST_VARS['sender_email'] . "\"" . $lang['is_invalid']);
//	}

//	if (!check_email($HTTP_POST_VARS['sender_email']))
//	{
//		return parse_template($lang['email_ad'] . "\"" . $HTTP_POST_VARS['sender_email'] . "\"" . $lang['been_banned']);
//	}


	$email_count = 0;
	for($i=0; $i<=count($HTTP_POST_VARS['recip_email']); $i++)
	{
		if (isset($HTTP_POST_VARS['recip_email'][$i]) && $HTTP_POST_VARS['recip_email'][$i] != "")
		{
			$actual = $i + 1;
//			if (!check_email_format($HTTP_POST_VARS['recip_email'][$i]))
//			{
//				return parse_template($lang['email_recip_entered'] . $actual . ", \"" . $HTTP_POST_VARS['recip_email'][$i] . "\"" . $lang['is_invalid']);
//			}
//			if (!check_email($HTTP_POST_VARS['recip_email'][$i]))
//			{
//				return parse_template($lang['email_recip_entered'] . $actual . ", \"" . $HTTP_POST_VARS['recip_email'][$i] . "\"" . $lang['been_banned']);
//			}
	
			$email_count++;
		}
	}

	if ($email_count <= 0)
	{
		return parse_template($lang['no_recips_entered']);
	}

	if($HTTP_POST_VARS['bg_color'] == $HTTP_POST_VARS['font_color'])
	{
		return parse_template($lang['bg_font_colors_match']);
	}

	if(isset($conf['max_message_length']) && $conf['max_message_length'] != "")
	{
		if (strlen($HTTP_POST_VARS['message']) > $conf['max_message_length'])
		{
			return parse_template($lang['msg_too_long'] . $conf['max_message_length'] . " " . $lang['chars']);
		}
	}
	if(!$fp = @fopen("./templates/preview_card.html", "r"))
        {
		return error($lang['cannot_open_file'] . " <b>./templates/preview_card.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
        }

	if (!$DB->query("SELECT url, img_type, width, height FROM " . $conf['dbprefix'] . "images WHERE id=\"" . $HTTP_POST_VARS['pic'] . "\" LIMIT 1"))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}

	while($row = $DB->fetch_array())
	{
		$macro = getMacro(getExt($row['url']), "macro");
		$macro = preg_replace("/{{name}}/i", $row['name'], $macro);
		
		//It is important to set the width and height variables to 0 each time or they will be reused between images
		$width = "";
		$height = "";
		if($row['width'] != "" && $row['width'] != "0" && $row['width'] != "NULL")
		{
			$width = "width=\"" . $row['width'] . "\"";
		}
		if($row['height'] != "" && $row['height'] != "0" && $row['height'] != "NULL")
		{
			$height = "height=\"" . $row['height'] . "\"";
		}
		$macro = preg_replace("/{{width}}/i", $width, $macro);
		$macro = preg_replace("/{{height}}/i", $height, $macro);
		if($row['img_type'] == "upload")
		{
			$macro = preg_replace("/{{img}}/i", $conf['url'] . "images/" . $row['url'], $macro);
		}
		else
		{
			$macro = preg_replace("/{{img}}/i", $row['url'], $macro);
		}
		$img_name = $row['name'];
	}

	$hidden = "<input type=\"hidden\" name=\"title\" value=\"" . $HTTP_POST_VARS['title'] . "\" />\n";
	$hidden .= "<input type=\"hidden\" name=\"sender_name\" value=\"" . $HTTP_POST_VARS['sender_name'] . "\" />\n";
	$hidden .= "<input type=\"hidden\" name=\"sender_email\" value=\"" . $HTTP_POST_VARS['sender_email'] . "\" />\n";
	$hidden .= "<input type=\"hidden\" name=\"bg_color\" value=\"" . $HTTP_POST_VARS['bg_color'] . "\" />\n";
	$hidden .= "<input type=\"hidden\" name=\"font_color\" value=\"" . $HTTP_POST_VARS['font_color'] . "\" />\n";
	$hidden .= "<input type=\"hidden\" name=\"font_face\" value=\"" . $HTTP_POST_VARS['font_face'] . "\" />\n";
	$hidden .= "<input type=\"hidden\" name=\"font_size\" value=\"" . $HTTP_POST_VARS['font_size'] . "\" />\n";


	$message = $HTTP_POST_VARS['message'];

	$hidden .= "<input type=\"hidden\" name=\"message\" value=\"" . $message . "\" />\n";

	$hidden .= "<input type=\"hidden\" name=\"notify\" value=\"" . $HTTP_POST_VARS['notify'] . "\" />\n";

	foreach($HTTP_POST_VARS['recip_email'] as $key => $val)
	{
		$hidden .= "<input type=\"hidden\" name=\"recip_email[]\" value=\"" . $val . "\" />\n";
	}
	$hidden .= "<input type=\"hidden\" name=\"pic\" value=\"" . $HTTP_POST_VARS['pic'] . "\" />\n";

	if(!$render_open = @fopen("./templates/render_card.html", "r"))
        {
		return error($lang['cannot_open_file'] . " <b>./templates/render_card.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
        }
	$render = @fread($render_open, filesize("./templates/render_card.html"));

	$output = @fread($fp, filesize("./templates/preview_card.html"));

	$output = preg_replace("/{{render}}/i", $render, $output);
	$output = preg_replace("/{{title}}/i", $HTTP_POST_VARS['title'], $output);
	$output = preg_replace("/{{pic}}/i", $macro, $output);
	$output = preg_replace("/{{message}}/i", parse_tags($message), $output);
	$output = preg_replace("/{{script}}/i", $conf['script'], $output);
	$output = preg_replace("/{{hidden_elements}}/i", $hidden, $output);
	$output = preg_replace("/{{sender_name}}/i", $HTTP_POST_VARS['sender_name'], $output);
	$output = preg_replace("/{{sender_email}}/i", $HTTP_POST_VARS['sender_email'], $output);
	$output = preg_replace("/{{bg_colour}}/i", $HTTP_POST_VARS['bg_color'], $output);
	$output = preg_replace("/{{font_face}}/i", $HTTP_POST_VARS['font_face'], $output);
	$output = preg_replace("/{{font_size}}/i", $HTTP_POST_VARS['font_size'], $output);
	$output = preg_replace("/{{font_color}}/i", $HTTP_POST_VARS['font_color'], $output);
	return $output;
}

function add_n_send()
{
global $conf, $HTTP_POST_VARS, $DB, $lang;

	$recip_email_array = array();
	//This array holds all the email addresses that aren't blank to add them to the database

	$title = $HTTP_POST_VARS['title'];
	$sender_name = $HTTP_POST_VARS['sender_name'];
	$sender_email = $HTTP_POST_VARS['sender_email'];
	$message = $HTTP_POST_VARS['message'];
	$message = preg_replace("/&lt;br \/&gt;/i", "<br \/>", $message);
	$card_id = md5(uniqid(microtime()));
	$time = time();
	$notify = $HTTP_POST_VARS['notify'] == "1" ? "1" : "0";

	foreach($HTTP_POST_VARS['recip_email'] as $email)
	{
		if($email != "")
		{
			$recip_email_array[] = $email;
		}
	}

	$recip_email_string = implode(",", $recip_email_array);

	$insert_sql = "INSERT INTO " . $conf['dbprefix'] . "sent_cards (id, title, date, from_name, from_email, recip_email, bg_color, font_color, font_face, font_size, message, pic, notify, email_sent, num_resends, sender_ip) VALUES (\"" . $card_id . "\", \"" . $title . "\", \"" . $time . "\", \"" . $sender_name . "\", \"" . $sender_email . "\", \"" . $recip_email_string . "\", \"" . $HTTP_POST_VARS['bg_color'] . "\", \"" . $HTTP_POST_VARS['font_color'] . "\", \"" . $HTTP_POST_VARS['font_face'] . "\", \"" . $HTTP_POST_VARS['font_size'] . "\", \"" . $message . "\", \"" . $HTTP_POST_VARS['pic'] . "\", \"" . $notify . "\", \"0\", \"0\", \"" . getip() . "\")";
	if (!$DB->query($insert_sql))
	{
		return error($DB->error(), $lang['check_db_settings'] . "|" . $lang['check_db_on']);
	}
	
	
	//Debug
	//echo "<pre>send_mail(send_card, " . $recip_email_array . ", " . $sender_name . ", " . $sender_email . ", " . $card_id . "))</pre>";
	if(!send_mail(send_card, $recip_email_array, $sender_name, $sender_email, $card_id))
	{
			return $lang['cannot_send_card'];
	}

	return $lang['card_sent'] . "<br /><br />" . $lang['recip_info'];
}

function set_prefs()
{
global $lang, $conf, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS;

	if($HTTP_POST_VARS['_lang'] != "")
	{
		$lang_to_set = $HTTP_POST_VARS['_lang'];
	}
	else if($HTTP_COOKIE_VARS['wc_lang'] != $HTTP_POST_VARS['_lang'])
	{
		$lang_to_set = $HTTP_COOKIE_VARS['wc_lang'];
	}
	else
	{
		$lang_to_set = "English";
	}
	//echo "\$lang_to_set = " . $lang_to_set . "<br />";
	//Check that it exists
	$lang_to_set = is_dir($conf['dir'] . "lang/" . $lang_to_set) ? $lang_to_set : "English";
	
	if($HTTP_POST_VARS['_style'] != "")
	{
		$style_to_set = $HTTP_POST_VARS['_style'];
	}
	else if($HTTP_COOKIE_VARS['wc_style'] != $HTTP_POST_VARS['_style'])
	{
		$style_to_set = $HTTP_COOKIE_VARS['wc_style'];
	}
	else
	{
		$style_to_set = "Default.css";
	}
	//Check that it exists
	$style_to_set = file_exists($conf['dir'] . "templates/styles/" . $style_to_set) ? $style_to_set : "Default.css";
	
	//We now need to set the two cookies. First of all we need to check whether the language and style have changed. If not, don't set that cookie
	
	//Set the cookie for 1 year
	$exp = time() + (60*60*24*365);
	
	if($HTTP_POST_VARS['_lang'] != "") //Only bother setting the cookie if the user has chosen a language
	{
		if($HTTP_COOKIE_VARS['wc_lang'] != $lang_to_set)
		{
			@setcookie("wc_lang", $lang_to_set, $exp);
		}
	}
	
	if($HTTP_POST_VARS['_style'] != "")
	{
		if($HTTP_COOKIE_VARS['wc_style'] != $style_to_set)
		{
			@setcookie("wc_style", $style_to_set, $exp);
		}
	}

	//See if we can go back to the referrer page
	if($HTTP_SERVER_VARS['HTTP_REFERER'] != "")
	{
		header("Location: " . $HTTP_SERVER_VARS['HTTP_REFERER']);
	}
	else
	{
		header("Location: index.php");
	}

}

function output($what)
{
global $HTTP_GET_VARS, $HTTP_COOKIE_VARS, $to_do, $cpy, $DB, $conf, $lang;

	$what = isset($what) ? $what : $to_do;

	$DB->disconnect();

	$stats = get_stats("p");

	if(!$fp = @fopen("./templates/template.html", "r"))
	{
		error($lang['cannot_open_file'] . " <b>./templates/template.html</b>", $lang['check_file_exists'] . "|" . $lang['cannot_file_perms']);
	}
	$data = @fread($fp, filesize("./templates/template.html"));

	
	/*===============================================
	Add our chosen stylehseet
	
	How to choose which stylehseet to use:
	1. If a sheet is being previewed, use that or
	2. Read the cookie to see if a choice has been made or
	3. Use the Default.css sheet
	4. Check if the chosen stylsheet exists. If not, use Default.css
	================================================*/
	
	if(isset($HTTP_GET_VARS['preview_style']) && $HTTP_GET_VARS['preview_style'] != "")
	{
		$sheet_to_use = $HTTP_GET_VARS['preview_style'];
	}
	else if(isset($HTTP_COOKIE_VARS['wc_style']) && $HTTP_COOKIE_VARS['wc_style'] != "")
	{
		$sheet_to_use = $HTTP_COOKIE_VARS['wc_style'];
	}
	else
	{
		$sheet_to_use = "Default.css";
	}
	$sheet_to_use = file_exists($conf['dir'] . "templates/styles/" . $sheet_to_use) ? $sheet_to_use : "Default.css";

	//List available stylesheets that the user can pick from
	$dp = opendir($conf['dir'] . "templates/styles");

	while($file = readdir($dp))
	{
		if($file!="." && $file!="..")
		{
			$filename_array = explode(".", $file);
			$title = $filename_array['0'];
			$style_list .= "<option value=\"" . $file . "\">" . $title . "</option>\n";
		}
	}
	
	//List available languages that the user can pick from
	$dp = opendir($conf['dir'] . "./lang");
	if(!$dp)
	{
		return error($lang['no_open_lang_dir'], $lang['check_dir_exists'] . "|" . $lang['check_dir_perms']);
	}
	while($file = readdir($dp))
	{
		if($file!="." && $file!=".." && $file!="index.html")
		{
			$lang_list .= "<option value=\"" . $file . "\">" . $file . "</option>";
		}
	}

	$script_output = preg_replace("/{{content}}/i", $what, $data);
	$script_output = preg_replace("/{{chosen_style}}/i", $sheet_to_use, $script_output);
	$script_output = preg_replace("/{{style_list}}/i", $style_list, $script_output);
	$script_output = preg_replace("/{lang_list}}/i", $lang_list, $script_output);
	$script_output = preg_replace("/{{stats}}/i", $stats . $cpy, $script_output);

	$script_output = preg_replace("/\|\|(\w+)\|\|/ie", "stripslashes(\$lang['\$1'])", $script_output);

	echo $script_output;
	exit();
}

//If we have made it all the way to the end without any errors, output the final response
output($to_do);

?>