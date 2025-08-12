<?php
// fetch_person.php: search imdb for person id, given name
// return found items as a table. clicking on person changes value in filmform.php and closes window.
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// js function setperson(id, name) must be defined in caller!!!!

//first check if the class exists allready, if so return and don't include it again
if(class_exists('fetch_person')) return;

require_once('fetch.php');

class fetch_person extends pml_fetch {

	var $searchLimit;
	var $_actorName = '';
	function fetch_person($searchLimit,$cats){
		$this->searchLimit = $searchLimit; $this->cats = $cats;
	}
	
	function doSearch(&$out, $SearchString) {
		// SearchString: the movie title to search form
		// $out: the resulting HTML code
		//$data = "/find?nm=on;mx=$this->searchLimit;q=".rawurlencode($SearchString);
		
		$searchData = 'occupation='.implode($this->cats,'&occupation=').'&name='.urlencode($SearchString).'&mx='.$this->searchLimit;

		$site = $this->post('/Nsearch','imdb.com:80','imdb.com/find/',$searchData); 

		//when you use the search-form on imdb.com and you search for a person that was exactly found
		//imdb uses a 302-found-page to redirect to the page of this person. something like
		//302 page found location: http://us.imdb.com/Name?lastname,+firstname
		if(strstr($site, 'HTTP/1.0 302') || strstr($site, 'HTTP/1.1 302')) { //exact match?
			preg_match("#Location: http://us\.imdb\.com[:0-9]*/Name\?([^\s]*)\s#i", $site, $x);
			// x[0]: location: http://us.imdb.com/Name?lastname,+firstname
			// x[1]: lastname,+firstname
			
			$site = $this->FetchCachedUrl('/Name?'.$x[1],'us.imdb.com:80','http://us.imdb.com/Find');
			// here imdb will return another 302-found page of the form
			// HTTP/1.1 302 Found ... Location: /name/nm0000206/
			
			$x[1] = urldecode($x[1]);
			$start = strpos($x[1],',');
			$this->_actorName = str_replace('"','\'',substr($x[1],$start+2).' '.substr($x[1],0,$start)); 
			
			preg_match("#Location: http://us\.imdb\.com[:0-9]*/name/nm([0-9]{7})#i", $site, $x);
			$this->FetchID = $x[1];	      //save the id in $FetchID

			return(PML_FETCH_EXACTMATCH); //return to editentry that it can fetch now the data - search is already done
		}
		
		$y=spliti('<h3><a NAME="([^"]*)">', $site);
		// split according to categories (actor, director etc)
		
		$found = 0;
		// always display all 'most popular'
		$site = $y[0];
		$out .= '<table id="restable">';
		$start = strpos($site,'Most popular searches');
		if($start>0){
			$out.="\t<tr><td class=\"rowtitle\">Most popular</td><td style=\"width: 30px\"/></tr>\n";
			$site = substr($site,$start);
			$brow = true;
			while(eregi('<li><a HREF="/name/nm([0-9]*)/">([^<]*)</a>', $site, $x)) { 
				$x[2] = str_replace('"','\'',$x[2]); //html does not cope well with doubleqoutes, so replace them with single quotes.
				// id in x[1], name in x[2]
				$found ++;
				$site = substr($site,strpos($site,$x[0])+strlen($x[0]));
				$out .= "\t<tr class=\"".($brow?'row0':'row1')."\"><td><a href=\"#\" onclick=\"setperson('$x[1]','$x[2]')\">$x[2]</a></td><td><a target=\"_blank\" href=\"http://www.imdb.com/name/nm$x[1]/\"><img src=\"../pics/imdb.gif\"/></a></td></tr>\n";
				$brow = !$brow;
			}
		}
		for($i=1;$i<sizeof($y);$i++) {
			$site = $y[$i];
			$cat=substr($site,0,strpos($site,'<')); //strpos($site,'</a>') does not work! seems to be a bug in strpos
			$brow = true;
			$out.="\t<tr><td class=\"rowtitle\">$cat</td><td style=\"width: 30px\"/></tr>\n";
			$j=0; // counter for matches per catecory
			while($j<$this->searchLimit && (eregi('<li><a HREF="/name/nm([0-9]*)/">([^<]*)</a>', $site, $x))) { 
				$x[2] = str_replace('"','\'',$x[2]);
				// id in x[1], name in x[2]
				$j++;
				$site = substr($site,strpos($site,$x[0])+strlen($x[0]));
				$out .= "\t<tr class=\"".($brow?'row0':'row1')."\"><td><a href=\"#\" onclick=\"setperson('$x[1]','$x[2]')\">$x[2]</a></td><td><a target=\"_blank\" href=\"http://www.imdb.com/name/nm$x[1]/\"><img src=\"../pics/imdb.gif\"/></a></td></tr>\n";
				$brow = !$brow;
			}
			$found += $j;
		}
		if($found==0) 
			$out .= "\t<tr><td>nothing found.</td></tr>";
			
		$out .= '</table>';

		return(PML_FETCH_SEARCHDONE);
	}

	/*function DoFetch(&$ret) {
		// this function is not needed, since search already delivers all information needed
		// (i.e., id and name)
		// however, if additional info is to be retrieved, like birthdate etc, the site
		// http://www.imdb.com/People/nm<FetchID>/ must be loaded and parsed here.
		return PML_FETCH_ERROR;
	}*/
}

?>
