<?php

/*********************************************************
 * Name: Category.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Functions for download file control
 * Version: 4.00
 * Last edited: 5th March, 2004
 *********************************************************/

class Category
{
	var $html	= "";
	var $output = "";
	
    function Category()
	{
		global $rwdInfo, $OUTPUT, $DB;
		
		if ( !$rwdInfo->cats_saved )
		{
			$DB->query("SELECT * FROM dl_categories ORDER BY sortorder ASC");
			if ($myrow = $DB->fetch_row($result))
			{
				do
				{
					// Add category to cache
					$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
				} while ($myrow = $DB->fetch_row($result));
			}
			$rwdInfo->cats_saved = 1;
		}

		$this->html = $OUTPUT->load_template("skin_category");
    }

	function getLatestFileInfo($categoryData)
    {
		global $rwdInfo,$std;

		foreach ($rwdInfo->cat_cache as $subcat)
		{
			if ($subcat['parentid'] == $categoryData['cid'])
	    	{
				$categoryData['downloads'] += $tempData['downloads'];
				if ( $tempData['lastDate'] > $categoryData['lastDate'] ) 
				{
				    $categoryData['lastDate'] = $tempData['lastDate'];
					$categoryData['lastid'] = $tempData['lastid'];
					$categoryData['lastTitle'] = $tempData['lastTitle'];
				}
	    	}
		}
		return $categoryData;
    }

    function listAll()
    {
		global $DB, $IN, $CONFIG, $std, $rwdInfo;

	    // Count the number of categories user can see. If 0 then display a message to the user
	    $catsShown = -1;

		$result = $DB->query("SELECT * FROM dl_categories ORDER BY sortorder");
        
		if ($myrow = $DB->fetch_row($result))
		{
			// Add category to cache
			$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;

		    // There are categories so make this 0
		    $catsShown = 0;

		    $this->output .= $this->html->cat_head();
			
		    do
		    {
			    $catData = $this->getLatestFileInfo($myrow);
			    
			    $rows = $catData['downloads'];
			    
				if ($catData['thumb'] )
				    $thumb = "<a href='index.php?cid={$catData['cid']}'><img src='{$rwdInfo->url}/downloads/{$catData['thumb']}' border='0' class='thumb'></a>";
			    else
				    $thumb = "";
					    
			    $cat_name = "<a href='index.php?cid={$catData['cid']}'>{$catData['name']}</a>";
			    			    
			    if ( $rows > 0 )
				    $cat_latest = "<a href='?dlid={$catData['lastid']}'>{$catData['lastTitle']}</a>";
			    else
				    $cat_latest = GETLANG("nodls");

			    $cat_desc = nl2br($catData['description']);

                $data = array(
				    "cat_thumb"	    => $thumb,
				    "cat_name"	    => $cat_name,
				    "cat_latest"    => $cat_latest,
				    "cat_desc"	    => $cat_desc,
				    "cat_dlcount"   => $rows);
			    
			    $this->output .= $this->html->cat_row($data);

			    // Increment counter
			    $catsShown++;
			    
		    } while ( $myrow = $DB->fetch_row($result) );
		    
		    $this->output .= $this->html->cat_foot();
	    }
	    return $catsShown;
    }
}