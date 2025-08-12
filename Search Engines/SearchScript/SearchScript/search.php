<?php

/* BEGIN SEARCH SETTINGS */

$inc = 0;		// $inc=[0/1]: if set to 1, only listing where incentive clicks are allowed, are returned

$WebsiteID = 79;		// $WebsiteID=[number]: your affiliate website id (required)

$adultfilter = 0;	// $adultfilter=[0/1]: if set to 1, only links without adult-content are returned

$max_results = 50;	// $max_results=[number]: set the maximum number of results to display (maximum of 100)

$excl = "";		// $excl=[userid]: here you can specify an UserID, that should be excluded (e.g. 1 excludes all listings from user 1, leave as "" if you want to include all users)

$results_pp = 10;	// $results_pp=[1-10]: here you specify the number of results to display per page in the range of 1 to 10 max.

/* END SEARCH SETTINGS */



/* BEGIN XML PARSING - DO NOT EDIT ANYTHING INSIDE THIS */

$q = (!empty($_GET['q']) ? urldecode($_GET['q']) : "");
$count = 0;
$start = (!empty($_GET['start']) ? $_GET['start'] : 0);
$_GET['action'] = (!empty($_GET['action']) ? $_GET['action'] : "");

if($_GET['action'] == "search" && $q != "") {

	// Gets clients ip address
	function get_ip() {
		GLOBAL $HTTP_SERVER_VARS;
		GLOBAL $HTTP_ENV_VARS;
		GLOBAL $REMOTE_ADDR;

		$client_ip = (!empty($HTTP_SERVER_VARS['REMOTE_ADDR'])) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] :
			(!empty($HTTP_ENV_VARS['REMOTE_ADDR']) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR);

		return $client_ip;
	}
	
	$client_ip = get_ip();
	$xml_url = "http://www.globalppc.com/feeds/xml.php?q=" . urlencode(strtolower($q)) . "&inc=" . $inc . "&ws=" . $WebsiteID . "&adultfilter=" . $adultfilter . "&start=" . $start . "&count=" . $max_results . "&ip=" . $client_ip . "&excl=" . $excl;

	// USE FOR DEBUG PURPOSES
	//echo $xml_url;

	$stack = array();

	function startTag($parser, $name, $attrs) {
		global $stack;
		$tag=array("name"=>$name,"attrs"=>$attrs); 
		array_push($stack,$tag);
 
	}

	function cdata($parser, $cdata) {
		global $stack,$i;
  
		if(trim($cdata)) {   
			$stack[count($stack)-1]['cdata']=$cdata;   
		}
	}

	function endTag($parser, $name) {
		global $stack; 
		$stack[count($stack)-2]['children'][] = $stack[count($stack)-1];
		array_pop($stack);
	}

	$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "startTag", "endTag");
	xml_set_character_data_handler($xml_parser, "cdata");

	$data = xml_parse($xml_parser,file_get_contents($xml_url));
	if(!$data) {
		die(sprintf("XML error: %s at line %d",
		xml_error_string(xml_get_error_code($xml_parser)),
		xml_get_current_line_number($xml_parser)));
	}

	xml_parser_free($xml_parser);
	
	
	$count = (((int)$stack[0]['children'][1]['cdata']<=($max_results<=100?$max_results:100))?(int)$stack[0]['children'][1]['cdata']:($max_results<=100?$max_results:100));
	$results_pp = ((1<=$results_pp&&$results_pp<=10)?$results_pp:10);
	
	for($i=0; $i<count($stack[0]['children'][0]['children']); $i++) {
		$url[$i] = $stack[0]['children'][0]['children'][$i]['children'][1]['cdata'];
		$title[$i] = $stack[0]['children'][0]['children'][$i]['children'][2]['cdata'];
		$bid[$i] = $stack[0]['children'][0]['children'][$i]['children'][4]['cdata'];
		$description[$i] = $stack[0]['children'][0]['children'][$i]['children'][3]['cdata'];
		$domain[$i] = $stack[0]['children'][0]['children'][$i]['children'][0]['cdata'];
	}
	
	$ret_count = 0;
	$results_block = "";
	$ret_count = ((($start + $results_pp) < $count) ? $results_pp : (($count - $start) != 0 ? ($count-$start) : 0));
	
	$words = "";
	$kwords = preg_split('/ /', $q);
	for($i=0; $i<count($kwords); $i++) {
		$words .= "<a href=\"?action=search&q=" . urlencode($kwords[$i]) . "\" class=\"word_link\">$kwords[$i]</a> ";
	}
	
	if($ret_count > 0) {
		$results_block = "Results " . ($start + 1) . " - " . ($ret_count + $start) . " of " . $count . " for " . $words;
	}
	
	$pages_block = "";
	$ret = $count;
	$ret = (($ret>$max_results)?$max_results:$ret);
	$page = 1;
	$page_start = 0;
	$pages_block = "";
	if($ret > $results_pp || $start > 0) {
		$pages_block = "<small>Result page:</small> &nbsp;<font size=\"+1\">";
		while($ret > 0) {
			if($page_start == $start) {
				$pages_block .= "<font color=\"#993333\">" . $page . "</font> ";
			}
			else {
				$pages_block .= "<a href=\"?action=search&q=" . $q . "&start=" . $page_start . "\" class=\"page_link\">" . $page . "</a> ";
			}
		
			$ret -= $results_pp;
			$page_start += $results_pp;
			$page += 1;
		}
		$pages_block .= "</font>";
	}
	
} // END if

/* END XML PARSING */

?>

<?php
	// grabs header file to display above search page
	include("header.html");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center">
    <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="hidden" name="action" value="search" />
    <tr>
     <td align="center">
      <span class="desc">Search for <input type="text"  name="q" value="<?php echo $q; ?>" /> <input type="submit" value="Go" />
      </span>
     </td>
    </tr>
    </form>

    <?php
    GLOBAL $ret_count;

    if(!empty($results_block)) {
    print <<<END
    <tr valign="top">
     <td><span class="desc">$results_block</span></td>
    </tr>\n
END;
    }

    for($i=0; $i<$ret_count; $i++) {
    print <<<END
    <tr>
        <td width="*"><small><span class="desc">
        <A href="$url[$i]" class="link">$title[$i]</A>  &nbsp;(Cost to Advertiser $ $bid[$i]) <br>
        $description[$i]<br>
        </span>
        <span class="domain">
        $domain[$i]<br>
        </span></small>
        </td>
    </tr>\n
END;
    }

    if($count == 0 && $_GET['action'] == "search") {
    print <<<END
    <tr>
        <td width="*"><span class="desc">
        No results found for "<i>$q</i>"
        </span>
        </td>
    </tr>\n
END;
    }

    if($_GET['action'] == "") {
    print <<<END
    <tr>
        <td width="*">

        </td>
    </tr>\n
END;
    }

    if(!empty($pages_block)) {
    print <<<END
    <tr valign="top">
     <td align="center"><span class="desc">$pages_block</span></td>
    </tr>\n
END;
    }
    
/* REMOVE BETWEEN HERE IF YOU DON'T WANT SEARCH BOX AT BOTTOM DISPLAYED */

    if($count > 10) {
    print <<<END
    <form  action="?" method="get">
    <input type="hidden" name="action" value="search" />
    <tr>
     <td align="center">
      <input type="text"  name="q" value="$q" /> <input type="submit" value="Go" />
     </td>
    </tr>
    </form>
END;
    }

/* REMOVE BETWEEN HERE IF YOU DON'T WANT SEARCH BOX AT BOTTOM DISPLAYED */
    
    ?>
</table>

<?php
	// grabs footer file to display above
	include("footer.html");
?>


