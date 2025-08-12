<?
//define("hostname", "localhost");//care ful of this one, remember to change
define("hostname", "www.links-automation.com");//care ful of this one, remember to change

define("versionhtml", "Version.html");
define("linkpage","Links-Automation.php");

//include ("functions_Constants.php");
//include("function_clsHtmlSource.php");
$source = new HtmlSource();
$source->host = constant("hostname"); 


//echo "REQUEST_URI".$GLOBALS_[REQUEST_URI];
//echo "<p>HTTP_HOST".$HTTP_HOST;
//echo "<p>PATH_INFO".$GLOBALS_[PATH_INFO];
//echo "<p>QUERY_STRING".$GLOBALS_[QUERY_STRING];

//echo "hello";
//$bar = each($HTTP_GET_VARS);
//print_r($bar);


//$headers = apache_request_headers();

//foreach ($headers as $header => $value) {
  //  echo "$header: $value <br />\n";
//}

//$headers = $HTTP_GET_VARS;

//foreach ($headers as $header => $value) {
  //  echo "$header: $value <br />\n";
//}

//echo $HTTP_HOST.strpos(strtolower($HTTP_HOST),"links-automation.com");

//echo phpinfo();

//echo "hello";
/*

while(list($key, $value) = each($HTTP_GET_VARS)) 
$n=0;
while(list($key, $value) = each($HTTP_GET_VARS)) 
{ 
echo $key.$value($n);
$n++; 
} */

//this part will 

//check for reciprocal link at the host page
//.
//from our server no need to check
//from others will have to check their home page
//this request comes from my own home page


if(strcasecmp(constant("hostname"),$HTTP_HOST)==0||
strcasecmp("localhost",$HTTP_HOST)==0)//request from our own host don't do anything
{
	
	//echo "comme here ";
	
	$source = new HtmlSource();
$source->host = constant("hostname"); 
	
	
	//include("function_miscellaneous.php");
	 $url="/ServerLinks-Automation.php?searchfield=".$_GET['searchfield']."&searchtype=".$_GET['searchtype']."&searchterm=".$_GET['searchterm']."&pageIndex=".$pageIndex."&page=".$_GET['page']."&action=".$_GET['action']."&catPage=".$REQUEST_URI;
	 
//	 echo "here usl ".$url;
$source->page = $url;
$source->method = "GET";

//echo "hello hellohellohellohellohellohellohellohellohellohellohello".$url;

echo $source->getSource();
die();
	//redirect($url);
}
//request to customise page
else if(strpos(strtolower($HTTP_HOST),"links-automation.com")>-1)
{
	//include("function_miscellaneous.php");
	
	 $url="/CustomPage.php?searchfield=".$_GET['searchfield']."&searchtype=".$_GET['searchtype']."&searchterm=".$_GET['searchterm']."&pageIndex=".$pageIndex."&page=".$_GET['page']."&custompage=".$HTTP_HOST."&action=".$_GET['action']."&catPage=".$REQUEST_URI;
$source->page = $url;
//echo $url;

$source->method = "GET";
echo $source->getSource();
die();
	//redirect($url);
}

else//check for reciprocal link
{
	//echo FindLink ("http://".$HTTP_HOST,"Links-Automation.php");
	
	
	
	//no need to check for recirprocal link
	/*
	
	if(!(FindLink ("http://".$HTTP_HOST,"Links-Automation.php"))=="1")//cannot find reciprocal link at homepage
	{
	echo "Configuration Error, A link at your homepage <strong>".$HTTP_HOST."</strong> to this link page is not found. Please visit ".constant("hostname")." for more configuation information.";
	die("");
	}*/
	
}



 

//this part here will check the version of the script
$source->page = "/".constant("versionhtml");
$source->method = "GET";
$scriptVersion="version 1.0";
$latestVersion= $source->getSource();

//echo $latestVersion.$scriptVersion; 
if(!$latestVersion==$scriptVersion) echo "<div align='center'><font size='-1'>New ".$latestVersion." available. <a href='http://".constant("hostname")."'>Download </a>here  </font></div>";


if($_GET['action']=="search")
{

//echo $_GET['pageIndex']; 

$pageIndex=1;
if(isset($_GET['pageIndex']))$pageIndex=$_GET['pageIndex'];



//echo "see:".$pageIndex;	
  $url="/searchLinks.php?searchfield=".$_GET['searchfield']."&searchtype=".$_GET['searchtype']."&searchterm=".$_GET['searchterm']."&pageIndex=".$pageIndex."&catPage=".$REQUEST_URI;


//echo "url".$url;

	$source->page = $url;

	
}

else
{

$page="/".$_GET['page'];
if($page=="/")$page="/Category.html";
$source->page = $page;
}








/* -- Most basic example -----------------------------------------------------*/


$source->method = "GET";
echo $source->getSource();
//echo "<BR>(Request: ".$source->request.")";








Class HtmlSource
{
	/* All vars are public */
	
	// Common
	var $host;
	var $port = 80;
	var $page;
	var $request;
	var $httpversion;
	var $method = "GET";
	var $timeout = 30;
	
	var $striptags;
	var $showsource;
	var $strip_responseheader = true;
	
	// Cookie
	var $cookies = array();
	
	// GET
	var $getvars = array();
	
	// POST
	var $postvars = array();
	
	// Request fields
	var $accept;         //format: Accept: */*
	var $accept_encoding;//format: gzip,deflate 
	var $accept_language;//format: en-gb 
	var $authorization;  //format: username:password
	var $content_length; //format: 40 (for POST)
	var $content_type;   //format: application/x-www-form-urlencoded
	var $date;           //format: Date: Tue, 15 Nov 1994 08:12:31 GMT
	var $referer;        //format: Referer: http://www.domain.com
	var $useragent;      //format: User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)
	
	function addPostVar($name,$value)
	{
		if (!empty($name) && !empty($value))
		{
			$this->postvars[] =$name."=".$value;
		}
	}
	function addGetVar($name,$value)
	{
		if (!empty($name) && !empty($value))
		{
			$this->getvars[] = $name."=".$value;
		}
	}
	function addCookie($name,$value)
	{
		if (!empty($name) && !empty($value))
		{
			$this->cookies[] = $name."=".$value;
		}
	}
	function getSource()
	{
		// Error check
		if (empty($this->httpversion))
		{
			$this->httpversion = "1.0";
		}
		if (empty($this->method))
		{
			$this->method = "GET";
		}
		
		// Make GET variables
		$vars   = "";
		$cookiehead = "";
		if (sizeof($this->getvars) >0 && $this->method == "GET")
		{
			$vars  = "?";
			$vars .= join($this->getvars,"&");
			// Knock last '&' off
			// Remove this..?
			if (sizeof($this->getvars) >1)
			{
				$vars = substr($vars,0,strlen($vars) -1);
			}
		}
		// Make POST variables
		if (sizeof($this->postvars) >0 && $this->method == "POST")
		{
			$vars  = "\r\n";
			$strpostvar = join($this->postvars,"&");
			$vars .= $strpostvar;
			$vars .= "\r\n";
		}
		
		// Make Cookies
		if (sizeof($this->cookies) >0)
		{
			$cookiehead  = "Cookie: ";
			$cookiehead .= join($this->cookies,"; ");
			$cookiehead .= "\r\n";
		}
		
		// Make up request. Host isn't strictly needed except IIS winges
		if ($this->method == "POST")
		{
			$this->content_length = strlen($strpostvar);
			$this->content_type = "application/x-www-form-urlencoded";
			
			$this->request  = $this->method." ".$this->page." HTTP/".$this->httpversion."\r\n";
			$this->request .= "Host: ".$this->host."\r\n";
			$this->request .= $cookiehead;
			$this->request .= $this->privateMakeRequest();
			$this->request .= $vars."\r\n";
		} else{
			$this->request  = $this->method." ".$this->page.$vars." HTTP/".$this->httpversion."\r\n";
			$this->request .= "Host: ".$this->host."\r\n";
			$this->request .= $cookiehead;
			$this->request .= $this->privateMakeRequest();
			$this->request .= "\r\n";
		}

		// Open socket to URL
		
		$sHnd = fsockopen ($this->host, $this->port, $errno, $errstr, $this->timeout);
		fputs ($sHnd, $this->request);
		
		// Get source
		while (!feof($sHnd))
		{
			$result .= fgets($sHnd,128);
		}
		
		// Strip header
		if ($this->strip_responseheader)
		{
			$result = $this->privateStripResponseHeader($result);
		}
		
		// Strip tags
		if ($this->striptags)
		{
			$result = strip_tags($result);
		}
		// Show the source only
		if ($this->showsource && !$this->striptags)
		{
			$result = htmlentities($result);
			$result = nl2br($result);
		}
		return $result;
	}
	
	// Make up headers
	function privateMakeRequest()
	{
		if (!empty($this->accept))
		{
			$result .= "Accept: ".$this->accept."\r\n";
		}
		if (!empty($this->accept_encoding))
		{
			$result .= "Accept-Encoding: ".$this->accept_encoding."\r\n";
		}
		if (!empty($this->accept_language))
		{
			$result .= "Accept-Language: ".$this->accept_language."\r\n";
		}
		if (!empty($this->authorization))
		{
			$result .= "Authorization: Basic ".base64_encode($this->authorization)."\r\n";
		}
		if (!empty($this->content_length))
		{
			$result .= "Content-length: ".$this->content_length."\r\n";
		}
		if (!empty($this->content_type))
		{
			$result .= "Content-type: ".$this->content_type."\r\n";
		}
		if (!empty($this->date))
		{
			$result .= "Date: ".$this->date."\r\n";
		}
		if (!empty($this->referer))
		{
			$result .= "Referer: ".$this->referer."\r\n";
		}
		if (!empty($this->useragent))
		{
			$result .= "User-Agent: ".$this->useragent."\r\n";
		}
		
		return $result;
	}
	function privateStripResponseHeader($source)
	{
		$headerend = strpos($source,"\r\n\r\n");
		if (is_bool($headerend))
		{
			$result = $source;
		} else{
			$result = substr($source,$headerend+4,strlen($source) - ($headerend+4));
		}
		return $result;
	}

}


function FindLink ($url, $linktext) {
	$filepointer = fopen($url,"r");
	
	if($filepointer){
		while(!feof($filepointer)){
			$buffer = fgets($filepointer, 4096);
			$file .= $buffer;
		}
		  fclose($filepointer);
		  //echo "<p>opened the page";
	} else {
		  die("Could not open the page");    
	}
	
	$string = $linktext;
	$pattern = "/\//i";
	$replacement = "\/";
	$newlinktext = preg_replace($pattern, $replacement, $string);
	
	preg_match("/(.*)$newlinktext(.*)/i",$file,$matches);
	
	if (count($matches) != 0) {
		// remove "//" lines from below to display output
		//echo "<p>Success <p>Link text '$linktext' <br>was found on '$url'";
		$returnValue = 1;
	} else {
		// remove "//" lines from below to display output
		//echo "<p>Failure <p>Link text '$linktext' <br>not found on '$url'";
		$returnValue = 0;
	}
	
	return $returnValue;
}
function redirect($to)
{
  $schema = $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
  $host = strlen($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];
  if (headers_sent()) return false;
  else
  {
    header("HTTP/1.1 301 Moved Permanently");
    // header("HTTP/1.1 302 Found");
    // header("HTTP/1.1 303 See Other");
    header("Location: $schema://$host$to");
    exit();
  }
}
?>