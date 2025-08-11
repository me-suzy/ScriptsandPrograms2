<?php
// include the class nusoap. This class can be obtained from http://dietrich.ganx4.com/nusoap/index.php
// Once downloaded, put it in somewhere in your site tree, and change the next line to reflect that

include('nusoap.php');


// create a instance of the SOAP client object

// remember that this script is the client,
// accessing the web service provided by Google


// set up an array containing input parameters to be
// passed to the remote procedure

class clsGoogleApi {

	// These properties are used in the class:
	var $theResultSet; // holds the results of the search as given by google api
	var $theResults=array(); //holds the results, and is intended to do the traversing
	var $theRowShown=0; // internal field. Holds the index to the last row shown
	var $theMaxResults; // internal field. Holds the given max results parameter to the constructor
	var $flgError = false; // indicates if was there error or not
	var $theSearchQuery; // the Search query as returned by Google Api
	var $theEstimatedResultsCount; // The number of results found by the Api

	function doSearch($search_what,$maxResults,$start) {

      // $soapclient = new soapclient('http://api.google.com/search/beta2');
      $soapclient = new soapclient('http://api.google.com/GoogleSearch.wsdl', 'wsdl');

      // see http://api.google.com about getting a license key

		$params = array(
			 'key' => '3AkprvVQFHL/rLSoyBuoEOwnuAisqs+A',   // License key required.
			 'q'   => $search_what,                         // search term
			 'start' => $start,                             // start from result n
			 'maxResults' => $maxResults,                   // show a total of n results
			 'filter' => false,                             // remove similar results
			 'restrict' => '',                              // restrict by topic
			 'safeSearch' => false,                         // remove adult links
			 'lr' => '',                                    // restrict by language
			 'ie' => 'iso-8859-1',                                    // input encoding
			 'oe' => 'iso-8859-1'                                     // output encoding
		);
		// invoke the method on the server
		$this->theResultSet=$soapclient->call('doGoogleSearch', $params, 'urn:GoogleSearch', 'urn:GoogleSearch');
		$this->theMaxResults=$maxResults;

		// print the results of the search
		if ($this->theResultSet['faultstring']) {
			echo $this->theResultSet['faultstring'].'<br />';
			$this->flgError=true;
		} else  {
			$this->flgError=false;
			$this->theRowShown=0;
			$this->theSearchQuery=$this->theResultSet['searchQuery'];
			$this->theEstimatedResultsCount=$this->theResultSet['estimatedTotalResultsCount'];
			if (is_array($this->theResultSet['resultElements'])) {
				$this->theResults=array();
				foreach ($this->theResultSet['resultElements'] as $r) {
					$result['URL']=$r['URL'];
					$result['cached-size']=$r['cachedSize'];
					$result['snippet']=$r['snippet'];
					$result['directory category']=$r['directoryCategory'];
					$result['related information present']=$r['relatedInformationPresent'];
					$result['directory title']=$r['DirectoryTitle'];
					$result['summary']=utf8_decode($r['summary']);
					$result['title']=utf8_decode($r['title']);
					$this->theResults[]=$result;
				}
			}
		}
	}
	
	function getResultNextItem() {
		$result=$this->theResults[$this->theRowShown];
		$this->theRowShown++;
		if (($this->theRowShown > $this->theMaxResults) or ($this->theRowShown > $this->theEstimatedResultsCount))  {
			$result=false;
		}
		return $result;
	}
}

/*
echo 'FOO!<br /><br />';
  $myQuery=new clsGoogleApi('content management',0,10); // Search for content management, starting on the first found record, and getting a max of 10 items

    if ($myQuery->flgError) { // if error found do something
        echo 'Error!';
    } else {
		echo 'Search of '. $myQuery->theSearchQuery.' got '.$myQuery->theEstimatedResultsCount.' results<hr />';
		$item=0;
		echo '<ul>';
        while ($result=$myQuery->getResultNextItem()) {
			$item++;
			echo "<li> $item - ".$result['title']." (".$result['URL'].")<br />".$result['snippet']."(".$result['cached-size'].")";
        }
		echo '</ul>';
    }
*/

?>