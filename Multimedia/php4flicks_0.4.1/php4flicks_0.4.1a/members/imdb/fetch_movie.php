<?php
// script to fetch data on movies from IMDB, taken from www.powermovielist.com and modified
// http://www.powermovielist.com/phpwiki/index.php/FetchScripts

/************************************************************
*	available fields:
*	"Title"			string
*	"Year",			int
*	"Poster",		url
*	"Director",		array of array(string'id',string'name')
*	"Credits",		array of array(string'id',string'name')
*	"Genre",		array of string
*	"Rating",		real
*	"Starring",		array of array(string'id',string'name')
*	"Plot",			string
*	"Release",		date (1999-03-07)
*	"Runtime",		int
*	"imdbid",		string
*	"aka"			string
*************************************************************/

//first check if the class exists allready, if so return and don't include it again
if(class_exists('fetch_movie')) return;

require_once('fetch.php'); //base class

class fetch_movie extends pml_fetch {

	// regular expressions for imdb search	
	var $re = array(
		// find titles on search result page
		'searchTitle' => '#<a href="/title/tt([0-9]{7})/[^>]*">([^<]*)</a>#i', 
		// movie title
		'title' => "#<title>(.*) \([0-9]{4}\).*</title>#is",
		// movie data...
		'year' => "#<title>.*\(([0-9]{4})\).*</title>#is",
		'poster' => '#alt="cover" src="([^"]+)"#is',
		'director' => '#[^<]*<a href="\/name\/nm([0-9]{7})\/">([^<]*)<\/a>#i',
		'credits' => '#[^<]*<a href="\/name\/nm([0-9]{7})\/">([^<]*)<\/a>#i',
		'genreList' => '#Genre:</B>\n?(.*)(<a href="/Keywords)?#is',
		'genre' => "#<a href=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</a>#is",
		'rating' => "<B>([0-9]).([0-9])/10</B> \([0-9,]+ votes\)",
		'actor' => '#<td valign="top"> ?<a href="/name/nm([0-9]{7})/">([^<]*)</a></td>#i',
		'plot' => '#<p class="plotpar">([^<]*)</p>#is',
		'plotOutline' => "#Plot \w+:</b>([^<]*)#",
		'tagline' => "#Tagline:</b>([^<]*)#",
		'date' => '#<a href="/BusinessThisDay[^>]*>([0-9]+) ([A-Za-z]+)</a>#is',
		'year' => '#<a href="/Sections/Years[^>]*>([^<]*)</a>#is',
		'runtime' => '#<b class="ch">Runtime:</b>\n(.*:)?([0-9]+) min#i',
		'aka' => '#<b class="ch">Also Known As</b>:</b><br>(.*)#i'
	);

	var $actorLimit,$searchLimit;
	
	// constructor 
	function fetch_movie($searchLimit,$actorLimit){
		$this->searchLimit = $searchLimit; $this->actorLimit = $actorLimit;		
	}
	
	function doSearch(&$out, $SearchString, $EntryUrl) {
		// SearchString: the movie title to search for
		// $out: the resulting HTML code
		// EntryUrl: if set to 'blah', if >1 movies found they will be linked with <a href=blah&FetchID=...>
		
		global $cfg;
		
		// check whether cache is enabled and search results have been cached
		$outvar = $out;
		if($cfg['cache']){
			if(isset($_SESSION['cache'][$SearchString])){
				if(isset($_SESSION['cache'][$SearchString]['id'])){
					$this->FetchID = $_SESSION['cache'][$SearchString]['id'];
					return PML_FETCH_EXACTMATCH;
				} else {
					$out .= $_SESSION['cache'][$SearchString]['out'];
					return PML_FETCH_SEARCHDONE;
				}
			}
			unset($_SESSION['cache']);
			$_SESSION['cache'][$SearchString]['out'] = '';
			$outvar = &$_SESSION['cache'][$SearchString]['out'];
		}
		
		$outvar .= "<table id=\"restable\">\n";
		
		//fetchCachedUrl(url, host, referer)
		$data = "/find?tt=on;mx=$this->searchLimit;q=".rawurlencode($SearchString);
		$site = $this->FetchCachedUrl($data,'imdb.com:80','http://imdb.com/');

		//when you use the search-form on imdb.com and you search for a title that was exactly found
		//imdb uses a 302-found-page to redirect to the Title-page of this movie.
		//if this happens, we can use this imdb-id too
		if(strstr($site, 'HTTP/1.0 302') || strstr($site, 'HTTP/1.1 302')) { //exact match?
			preg_match("#Location: .*/title/tt([0-9]*)#i", $site, $x);
			$this->FetchID = $x[1];	      //save the id in $FetchID
			if($cfg['cache'])
				$_SESSION['cache'][$SearchString]['id'] = $x[1];
			return(PML_FETCH_EXACTMATCH); //return to editentry that it can fetch now the data - search is allready done
		}
		
		$found = 0;
		$brow = true;
	
		while(preg_match($this->re['searchTitle'], $site, $x)) { 
				// id in x[1], name in x[2]
				$found ++;
				$site = substr($site,strpos($site,$x[0])+strlen($x[0]));
				$outvar .= "\t<tr class=\"".($brow?'row0':'row1')."\"><td><a href=\"$EntryUrl&amp;FetchID=$x[1]\">$x[2]</a></td><td><a target=\"_blank\" href=\"http://www.imdb.com/title/tt$x[1]/\"><img alt=\"imdblogo\" src=\"../pics/imdb.gif\"/></a></td></tr>\n";
				$brow = !$brow;
		}
		if($found==0) 
			$outvar .= "\t<tr><td>nothing found.</td></tr>";			
			
		$outvar .= '</table>';
		if($cfg['cache'])
			$out .= $_SESSION['cache'][$SearchString]['out'];
		return(PML_FETCH_SEARCHDONE);
	}	

	function DoFetch(&$ret,$FieldName) {
		/* DoFetch - perform the search on the page to fetch from
		@param string the fetched value (return-string)
		@param string the FieldName
		@access public
		@return const PML_FETCH_ERROR, PML_FETCH_OK or PML_FETCH_ITEMNOTFOUND*/
		
		switch($FieldName) {
		
			case 'Title': //fetch Title
				// get this url, cached if allready used:
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
				// regular-expresstion to filter out the field
				// i: case insensitive, s: match whole text (not per line only)
                
                if(!preg_match($this->re['title'], $site, $x))
					return(PML_FETCH_ERROR);

                // remove possible artifacts caused by chunking of html source
                $ret = preg_replace('#\n[0-9abcdef]*\n#is','',$x[1]); 
				break;
				
			case 'Year':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
				
                if(!preg_match($this->re['year'], $site, $x))
					return(PML_FETCH_ERROR);
				
                $ret = $x[1];
                if($ret=='') $ret=0;                
				break;
				
			case 'Poster':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
			
			//alt="cover" src="http://ia.imdb.com/media/imdb/01/I/76/68/18m.jpg" 
   
   				if(!preg_match($this->re['poster'], $site, $x))
                	return(PML_FETCH_ERROR);
				
				// remove possible artifacts caused by chunking of html source
                $ret = preg_replace('#\n[0-9abcdef]*\n#is','',$x[1]);
                
				break;
				
			case 'Director':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');

				$start = strpos($site, 'Directed by');
				$len = strpos($site, 'Writing credits')-$start;
				$site = substr($site,$start,$len);
				if(!preg_match_all($this->re['director'], $site, $x,PREG_SET_ORDER)) {
					return(PML_FETCH_ERROR);
				}
				$ret = array();
				foreach($x as $dir){
					$ret[] = array('id'=>$dir[1],'name'=>$dir[2]);
				}		
                break;
                
            case 'Credits':
            	$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
            	
				$start = strpos($site, 'Writing credits');
				$len = strpos($site, '</table>',$start)-$start;
				$site = substr($site,$start,$len);
                if(!preg_match_all($this->re['credits'], $site, $x,PREG_SET_ORDER))
					return(PML_FETCH_ERROR);

				$ret = array();
				foreach($x as $dir){
					$ret[] = array('id'=>$dir[1],'name'=>$dir[2]);
				}		
                break;
                
			case 'Genre':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
				
                if(!preg_match($this->re['genreList'], $site, $gen))
					return(PML_FETCH_ERROR);

                $gen = $gen[1];
				$ret = array();
                while(preg_match($this->re['genre'], $gen, $x)) {
                        $gen = substr($gen,strpos($gen,$x[0])+strlen($x[0]));
						$ret[] = $x[1];
                }
				if(sizeof($ret)==0) {
					return(PML_FETCH_ERROR);
				}
				break;
				
			case 'Rating':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
				
                if(!eregi($this->re['rating'], $site, $x)) {
					return(PML_FETCH_ERROR);
				}
                $ret = $x[1].$x[2];
				$ret = $ret/10;
				break;
				
			case 'Starring':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
				$ret = array();
				$i=0;
				
				while( ($i < $this->actorLimit) && preg_match($this->re['actor'], $site, $x)) {
					$site = substr($site,strpos($site,$x[0])+strlen($x[0]));
					$ret[] = array('id'=>$x[1],'name'=>$x[2]);
					$i++;
                }
                
				if(sizeof($ret)==0)
					return(PML_FETCH_ERROR);
				break;
				
			case 'Plot':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/plotsummary/", 'imdb.com:80', "http://us.imdb.com/title/tt$this->FetchID/");
				
				if(preg_match($this->re['plot'], $site, $x)) {
					//plot exists:
					$ret = $x[1];
				} else {
					//plot doesn't exist, use plot-outline from title-page:
					$site = $this->fetchCachedUrl("http://imdb.com/title/tt$this->FetchID/", 'imdb.com:80', "http://imdb.com");
					preg_match($this->re['plotOutline'], $site, $x);
					$ret = $x[1];
					// if there's no plot outline fetch tagline.
					if(!$ret) {
						$x = array();
						if(!preg_match($this->re['tagline'], $site, $x))
							return(PML_FETCH_ERROR);
						$ret = $x[1];
					}
				}
				break;
				
			case 'Release':
				$site = $this->fetchCachedUrl("/title/tt$this->FetchID/releaseinfo", 'imdb.com:80', "http://us.imdb.com/title/tt$this->FetchID/");

                $convert['January']='01';
                $convert['February']='02';
                $convert['March']='03';
                $convert['April']='04';
                $convert['May']='5';
                $convert['June']='06';
                $convert['July']='07';
                $convert['August']='08';
                $convert['September']='09';
                $convert['October']='10';
                $convert['November']='11';
                $convert['December']='12';

                if(!preg_match($this->re['date'], $site, $date))
					return(PML_FETCH_ERROR);

                if(!preg_match($this->re['year'], $site, $year))
					return(PML_FETCH_ERROR);

                $ret = $year[1] . "-" . $convert[$date[2]] . "-" . $date[1];
				break;
				
			case 'imdbid':
				$ret = $this->FetchID;
				break;
				
            case 'Runtime':
                $site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
                
                if(!preg_match($this->re['runtime'], $site,$x)) {
                	// set runtime to 0, so no crap is returned if FETCH_ERROR not caught
                	$ret = 0;
                    return(PML_FETCH_ERROR);
                }
                $ret = $x[2];
                break;
                
            case 'aka':
                $site = $this->fetchCachedUrl("/title/tt$this->FetchID/", 'imdb.com:80', 'http://imdb.com');
                
                if(!preg_match($this->re['aka'], $site,$x)) {
                    return(PML_FETCH_ERROR);
                }
                $x[1] = str_replace('<br>',"\n",$x[1]);
                $x[1] = str_replace('&#32;','',$x[1]);
                $ret = $x[1];
              	break;
              	
			default:
				return(PML_FETCH_ITEMNOTFOUND);
		}//end switch $FieldName
		
		return(PML_FETCH_OK);
	}
}

?>
