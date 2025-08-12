<?
	/* 'main' configuration ******************************************************
	** various options for the script                                           **
	******************************************************************************/
	
	$cfg['mysql_host'] = 'localhost';       // mysql host 	
	
	$cfg['mysql_user'] = '*****';           // mysql user
	
	$cfg['mysql_pass'] = '*****';           // mysql password
	
	$cfg['mysql_db'] = 'flicks';            // database name
	
	$cfg['pagetitle'] = 'php4flicks';       // the browser window title

	$cfg['nofflicks'] = 25;                 // # movies per page (main window)
	
	    // default sort order; must NOT be empty! the max # of rows by which can be sorted is the size of this array.
	    // ASC/DESC MUST be specified!
	$cfg['defaultsort'] = array('name ASC','nr ASC','year DESC');

	    // define any number of users here; users[i][md5pass] must contain the md5-encrypted password
	$cfg['users'][0]['user'] = 'dave'; 
	$cfg['users'][0]['md5pass'] = 'd0684cfe844d7353619932ef26fa6e90';
		
	$cfg['version'] = 'version 0.4';

	$cfg['old_browsers'] = false;           // if set to true, javascript/DOM will not be used in the edit mode 
	                                        // for compatibility with older browser

	
	/* imdb scripts configuration ************************************************
	** configures fetching of data from imdb                                    **
	******************************************************************************/

	$cfg['actorLimit'] = 10;                // how many actors per movie?
	$cfg['searchLimit'] = 25;               // max # of search results per catecory

	$cfg['actSearchLimit'] = 25;            // max # of search results for people
		// which categories to search for people:
	$cfg['actCats'] = array('actors','actresses','directors','writers'); 
	    // possible categories include: Actor, Actress, Cinemathographer, Composer, Director,
	    // Writer, Editor, Producer, Stunts, and a whole lot of others
	    // independent of $actCats and $actSearchLimit, all 'most popular' matches will always be shown
	
	$cfg['cache'] = true;                   // whether or not to cache last search results
	
	$cfg['http_cache_size'] = 0;				
	    // number of html-pages kept in session cache. 
	    // should be switched off in general, useful when testing app since it reduces traffic from imdb
		
	$cfg['http_compress'] = true;
	    // whether or not to use gzipped html.
	    // set to false if you have problems fetching data from imdb (php versions <4.3.x)				
	
	
	/* media configuration ********************************************************
	** languages, media, etc...                                                  **
	******************************************************************************/
	
	$cfg['cats'] = array(
	    // an array to automatically choose category depending on medium. leave empty if not desired.
	    // max length for category name is 10 chars.
	    'dvd' => 'C',
	    'vhs' => 'A',
	    'svhs' => 'B',
	    'divX/Xvid' => 'D',
	    'vcd/Svcd' => 'E',
	    'dvd-r' => 'C'
	    );


	/* print PDF configuration*****************************************************
	** configures pdf output generation                                          **
	******************************************************************************/
	
		// pdf page size ('A4' | 'LETTER')
	$cfg['pagesize'] = 'A4';

		// choose html->pdf converter. at this time, allowed values are 'htmldoc' and 'ezpdf'.
		// ezpdf is included in the distribution, htmldoc must be compiled
		// however, htmldoc is faster and generates a somewhat nicer output
	$cfg['pdfout'] = 'ezpdf';

	// HTMLDOC options

	$cfg['htmldoc_path'] = 'c:/programme/apache2/htdocs/movies/print';  // the data directory for htmldoc
	$cfg['htmldoc_fname'] = 'movies.pdf';                               // name of pdf file outputted by htmldoc
	
	// end HTMLDOC options

	// EZPDF options

		// Table Category Titles... <b>=bold, <i>=italic, <b><i>=bolditalic 
		// (this array determines what data will be shown)
	$cfg['tablecat'] = array(
		'nr'		=>'<b>CAT#</b>',
		'name'	=>'<b>TITLE</b>',
		'year'	=>'<b>YEAR</b>',
		'runtime'	=>'<b>TIME</b>',
		'ratio'	=>'<b>RATIO</b>',
	);

		// Main Title of PDF document
	$cfg['tabletitle'] = 'php4flicks';

		// Put a link BACK on PDF printout(overcome redirect) 0=No 1=Yes
	$cfg['backlink'] = 0;

	// end EZPDF options


	/* ****************************************************************************
	** do not edit below this line                                               **
	******************************************************************************/

		// get available options for media, language, etc directly from mysql table definition.
		// DO NOT CHANGE config below this line. to change options, directly edit mysql definitions.
	
	mysql_connect($cfg['mysql_host'],$cfg['mysql_user'],$cfg['mysql_pass']) or die(mysql_error());
	mysql_select_db($cfg['mysql_db']) or die(mysql_error());
	
	set_magic_quotes_runtime(0);				// get rid of that plague
	
		// option columns must be of type set or enum
	$options = array('medium','lang','format','ratio','sound','genre');
	
	foreach($options as $o){
		$result = mysql_query("describe movies $o") or die(mysql_error());	
		$row = mysql_fetch_array($result);
		$type = $row['Type'];
		preg_match_all("#\'([^,\']{1,})\'#",$type,$matches,PREG_PATTERN_ORDER);

		$cfg[$o] = $matches[1];
    }
?>
