<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: search.results.php,v $
// | $Date: 2002/11/05 16:02:35 $
// | $Revision: 1.24 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
$templatesused = 'mailbit,search_results,mailbit_priority,mailbit_attach,mailbit_from,mailbit_subject,mailbit_datetime,mailbit_size,search_results_header_priority,search_results_header_attach,search_results_header_from,search_results_header_subject,search_results_header_datetime,search_results_header_size';
require_once('./global.php');

// ############################################################################
// Fields that can be searched by
$searchable = array(
	'subject',
	'name',
	'email',
	'message'
);

// ############################################################################
// Get the navigation bar
makemailnav(5);

// ############################################################################
if (empty($searchid)) {
	// Initialize
	$sqlmiscwhere = "AND userid = $hiveuser[userid]";

	// In which folders
	if (!is_array($folderids)) {
		invalid('folders');
	} else {
		if (!in_array(0, $folderids)) {
			$sqlmiscwhere .= ' AND folderid IN (0';
			foreach ($folderids as $folderid) {
				if ($folderid != '-') {
					$sqlmiscwhere .= ','.intval($folderid);
				}
			}
			$sqlmiscwhere .= ')';
		}
	}

	// Older than or newer than
	if ($searchdate != '-1') {
		$sqlmiscwhere .= ' AND dateline '.iif($beforeafter == 'before', '<=', '>=').' '.(TIMENOW - $searchdate*60*60*24);
	}

	// Read or unread
	switch ($readornot) {
		case 'read':
			$sqlmiscwhere .= ' AND status & '.MAIL_READ;
			break;
		case 'unread':
			$sqlmiscwhere .= ' AND NOT(status & '.MAIL_READ.')';
			break;
	}

	// Flagged or not
	switch ($flaggedornot) {
		case 'flagged':
			$sqlmiscwhere .= ' AND status & '.MAIL_FLAGGED;
			break;
		case 'unflagged':
			$sqlmiscwhere .= ' AND NOT(status & '.MAIL_FLAGGED.')';
			break;
	}

	// Priority
	switch ($priority) {
		case 'high':
			$sqlmiscwhere .= ' AND priority = 5';
			break;
		case 'normal':
			$sqlmiscwhere .= ' AND priority = 3';
			break;
		case 'low':
			$sqlmiscwhere .= ' AND priority = 1';
			break;
	}

	// Replied emails only
	if ($replied != 0) {
		$sqlmiscwhere .= ' AND status & '.MAIL_REPLIED;
	}

	// Forwarded emails only
	if ($forwarded != 0) {
		$sqlmiscwhere .= ' AND status & '.MAIL_FORWARDED;
	}

	// Number of attachments
	if ($attach != 0) {
		$sqlmiscwhere .= ' AND attach ';
		switch ($leastbest) {
			case 'least':
				$sqlmiscwhere .= '>=';
				break;
			case 'best':
				$sqlmiscwhere .= '<=';
				break;
			default:
				$sqlmiscwhere .= '=';
		}
		$sqlmiscwhere .= ' '.intval($attach);
	}

	// Sort order
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
		$newsortorder = 'asc';
	}
	$sortorder = strtoupper($sortorder);

	// Sort field
	switch ($sortby) {
		case 'attach':
		case 'subject':
		case 'name':
		case 'dateline':
		case 'priority':
		case 'size':
			break;
		default:
			$sortby = 'dateline';
	}

	// Create the CONCAT() string
	$concat = "CONCAT(' ', ".implode(", ' ', ", array_intersect($fields, $searchable)).", ' ')";
	$whereextra = strlen(" AND $concat ");
	$selectextra = strlen(" + ($concat ");

	// Parse the actual query
	if ($sqlmiscwhere == "AND userid = $hiveuser[userid]" and trim($query) == '') {
		invalid('search terms');
	} elseif (trim($query) != '') {
		$sqlwhere = "$concat ";
		$sqlselect = "($concat ";
		$totalweigh = 0;
		$totalmust = 0;
		$stropen = false;
		$mustopen = false;
		$notopen = false;
		$c = 0;
		$word = '';
		do {
			$char = $query{$c++};
			switch ($char) {
				case '"':
					$stropen = !$stropen;
					break;
				case '+':
					if (!$mustopen) {
						$mustopen = true;
					} else {
						$word .= $char;
					}
					break;
				case '-':
					if (!$notopen) {
						$notopen = true;
					} else {
						$word .= $char;
					}
					break;
				case '':
				case ' ':
					if ($stropen) {
						$word .= ' ';
					} elseif ($notopen or $mustopen) {
						if ($notopen) {
							$sqlwhere .= 'NOT ';
						}
						$sqlwhere .= "LIKE '";
						if ($word{0} != '%') {
							$sqlwhere .= '% ';
						}
						$sqlwhere .= $word;
						if ($word{(strlen($word)-1)} != '%') {
							$sqlwhere .= ' %';
						}
						$sqlwhere .= "' AND $concat ";
						$mustopen = false;
						$notopen = false;
						$word = '';
						$totalmust++;
					} else {
						$sqlselect .= "LIKE '";
						if ($word{0} != '%') {
							$sqlselect .= '% ';
						}
						$sqlselect .= $word;
						if ($word{(strlen($word)-1)} != '%') {
							$sqlselect .= ' %';
						}
						$sqlselect .= "') + ($concat ";
						$mustopen = false;
						$notopen = false;
						$word = '';
						$totalweigh++;
					}
					break;
				case "'":
					$word .= "\'";
					break;
				case '%':
					$word .= '\%';
					break;
				case '*':
					if ($word{(strlen($word)-1)} != '%') {
						$word .= '%';
					}
					break;
				default:
					$word .= $char;
					break;
			}
		} while ($c < (strlen($query) + 1));

		if ($sqlwhere != "$concat ") {
			$sqlwhere = 'WHERE ' . substr($sqlwhere, 0, -$whereextra);
		} else {
			$sqlwhere = '';
		}
		if ($sqlselect != "($concat ") {
			$sqlselect = ', (' . substr($sqlselect, 0, -$selectextra) . ') AS weigh';
		} else {
			$sqlselect = '';
		}
	}

	// Prepare some of the information for insertion
	$searchinfo = array(
		'query' => $query,
		'sqlwhere' => $sqlwhere,
		'sqlselect' => $sqlselect,
		'sqlmiscwhere' => $sqlmiscwhere,
		'sqlsortby' => $sqlsortby,
		'sqlsortorder' => $sqlsortorder
	);
	$newsearch = true;
} else {
	// Get cached search info and extract it
	$search = getinfo('search', $searchid);
	extract(unserialize($search['data']));
	$newsearch = false;
	$sqlwhere = "WHERE messageid IN ($msgids)";
	$sqlmiscwhere = '';
	$sqlselect = '';

	// Sort order
	if ($sortorder != 'asc') {
		$sortorder = 'desc';
		$newsortorder = 'asc';
	}
	$sortorder = strtoupper($sortorder);

	// Sort field
	switch ($sortby) {
		case 'attach':
		case 'subject':
		case 'name':
		case 'dateline':
		case 'priority':
		case 'size':
			break;
		default:
			$sortby = 'dateline';
	}
}

// Set default page number and per page values
if (intme($pagenumber) < 1) {
	$pagenumber = 1;
}
if (intme($perpage) < 1)	{
	$perpage = $hiveuser['perpage'];
}

// Get total number of results
if ($newsearch) {
	$totalmails = $DB_site->get_field("
		SELECT COUNT(*) AS count
		FROM message
		".iif(empty($sqlwhere), 'WHERE '.substr($sqlselect, 1, -9).' > 0', $sqlwhere) . " $sqlmiscwhere
	");
} else {
	$totalmails = count(explode(',', $msgids)) - 1;	// We have that 0, remember?
}

// Handle pagination stuff
$limitlower = ($pagenumber-1)*$perpage+1;
$limitupper = ($pagenumber)*$perpage;
if ($limitupper > $totalmails) {
	$limitupper = $totalmails;
	if ($limitlower > $totalmails) {
		$limitlower = $totalmails-$perpage;
	}
}
if ($limitlower <= 0) {
	$limitlower = 1;
}

// Sort order
$sortorder = strtolower($sortorder);
if ($sortorder != 'asc') {
	$sortorder = 'desc';
	$newsortorder = 'asc';
	$arrow_image = 'arrow_up';
} else {
	$newsortorder = 'desc';
	$arrow_image = 'arrow_down';
}
$sortorder = strtoupper($sortorder);

// Sort field
switch ($sortby) {
	case 'attach':
	case 'subject':
	case 'name':
	case 'dateline':
	case 'priority':
	case 'size':
		break;
	default:
		$sortby = 'dateline';
}

// Get all the emails
$mails = $DB_site->query("
	SELECT *
	FROM message
	".iif(empty($sqlwhere), 'WHERE '.substr($sqlselect, 1, -9).' > 0', $sqlwhere) ." $sqlmiscwhere
	ORDER BY $sortby $sortorder
");

$rowjsbits = '';
$msgids = '0';
if ($DB_site->num_rows($mails) <= 0) {
	eval(makeerror('error_noresults'));
}

$rowjsbits = '';
$markallbg = '';
for ($current = 1; $mail = $DB_site->fetch_array($mails); $current++) {
	$msgids .= ",$mail[messageid]";
	if ($current < $limitlower or $current >= ($limitlower + $perpage)) {
		continue;
	}
	$mailbits .= makemailbit($mail);
}

// Show the delete note
$deletenote = '<b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.';

// Insert stuff into the database for future reference
if ($newsearch) {
	$searchinfo['msgids'] = $msgids;
	$DB_site->query("
		INSERT INTO search (searchid, userid, dateline, data)
		VALUES (NULL, $hiveuser[userid], ".TIMENOW.", '".addslashes(serialize($searchinfo))."')
	");
	$searchid = $DB_site->insert_id();
}

// Sort image
$sortimages = array("$sortby" => '</a>&nbsp;&nbsp;<a href="search.results.php?searchid='.$searchid.'&perpage='.$perpage.'&sortorder='.$newsortorder.'&sortby='.$sortby.'"><img src="'.$skin['images'].'/'.$arrow_image.'.gif" align="middle" alt="" border="0" />');

// Create the page navigation
$pagenav = getpagenav($totalmails, "search.results.php?searchid=$searchid&perpage=$perpage");

// Custom columns
$colheaders = '';
foreach ($hiveuser['cols'] as $column) {
	eval(makeeval('colheaders', "search_results_header_$column", 1));
}

$folderid = -1;
$youarehere = '<a href="index.php">'.getop('appname').'</a> &raquo; <a href="search.intro.php">Search Messages</a> &raquo; Results';
eval(makeeval('echo', 'search_results'));

?>