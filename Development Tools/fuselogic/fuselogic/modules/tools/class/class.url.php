<?
/**
* Class for getting information about URL's
* @author    Sven Wagener <sven.wagener@intertribe.de>
* @copyright Intertribe limited
* @include 	 Funktion:_include_
*/
class url{
	
	var $url="";
	var $url_host;
	var $url_path;
	var $file="";
	
	var $code="";
	var $code_desc="";
	var $http_version=""; // Variable for HTTP version
	
	var $header_stream;
	var $header_array;
	
	var $timeout="1";
	
	/**
	* Constructor of class url
	* @param string	$url the complete url
	* @desc Constructor of class url
	*/
	function url($url){
		$this->url=$url;
		
		$url_array=parse_url($this->url);
		$this->url_host=$url_array['host'];
		$this->url_path=$url_array['path'];
		
		if($this->url_path==""){
			$this->url_path="/";
		}
		
		$this->refresh_headerinfo();
	}
	
	/**
	* Returns the whole url
	* @return string $url the whole url
	* @desc Returns the whole url
	*/
	function get_url(){
		return $this->url;
	}
	
	/**
	* Returns the host of the url
	* @return string $url_host the host of the url
	* @desc Returns the host of the url
	*/
	function get_url_host(){
		return $this->url_host;
	}
	
	/**
	* Returns the path of the url
	* @return string $url_path the path of the url
	* @desc Returns the path of the url
	*/
	function get_url_path(){
		return $this->url_path;
	}
	
	/**
	* Returns the status code of the url
	* @return string $status_code the status code
	* @desc Returns the status code of the url
	*/
	function get_statuscode(){
		return $this->code;
	}
	
	/**
	* Returns the status code description of the url
	* @return string $status_code_desc the status code description
	* @desc Returns the status code description of the url
	*/
	function get_statuscode_desc(){
		return $this->code_desc;
	}
	
	/**
	* Returns the http version of the url by the returned headers of the server
	* @return string $http_version the http version
	* @desc Returns the http version of the url by the returned headers of the server
	*/
	function get_info_http_version(){
		return $this->http_version;
	}
	
	/**
	* Returns the server type of the url's host by the returned headers of the server
	* @return string header_array['Server'] the server type
	* @desc Returns the server type of the url's host by the returned headers of the server
	*/
	function get_info_server(){
		return $this->header_array['Server'];
	}
	
	/**
	* Returns the date of the url's host by the returned headers of the server
	* @return string $header_array['Date'] the date
	* @desc Returns the date of the url's host by the returned headers of the server
	*/
	function get_info_date(){
		return $this->header_array['Date'];
	}
	
	/*
	function get_info_content_length(){
	return $this->header_array['Content-Length'];
	}
	*/
	
	/**
	* Returns the content type by the returned headers of the server
	* @return string header_array['Content-Type'] the content type
	* @desc Returns the content type by the returned headers of the server
	*/
	function get_info_content_type(){
		return $this->header_array['Content-Type'];
	}
	
	/**
	* Returns the content of the url without the headers
	* @return string $content the content
	* @desc Returns the content of the url without the headers
	*/
	function get_content(){
		// Get a web page into a string
		$string = implode ('', file ($this->url));
		return $string;
	}
	
	/**
	* Returns the whole header of url without content
	* @return string $header the header
	* @desc Returns the whole header of url without content
	*/
	function get_header_stream(){
		return $this->header_stream;
	}
	
	/**
	* Returns the whole headers of the url in an array
	* @return array $header_array the headers in an array
	* @desc Returns the whole headers of the url in an array
	*/
	function get_headers(){
		return $this->header_array;
	}
	
	/**
	* Refreshes the header information
	* @desc Refreshes the header information
	*/
	function refresh_headerinfo(){
		// Open socket for connection via port 80 to put headers
		$fp = fsockopen ($this->url_host, 80, $errno, $errstr, 30);
		if (!$fp) {
			// echo "$errstr ($errno)";
			if($errno==0){
				$errstr="Server Not Found";
			}
			$this->code=$errno;
			$this->code_desc=$errstr;			
		} else {
			
			$put_string="GET ".$this->url_path." HTTP/1.0\r\nHost: ".$this->url_host."\r\n\r\n";
			fputs ($fp, $put_string);
			@socket_set_timeout($fp,$this->timeout);
			
			$stream="";
			$this->header_array="";
			$header_end=false;
			
			// Getting header string and creating header array
			$i=0;
			while (!feof($fp) && !$header_end) {
				$line=fgets($fp,128);
				if(strlen($line)==2){
					$header_end=true;
				}else{
					if($i==0){
						$line1=$line;
					}
					$stream.=$line;
					$splitted_line=split(":",$line);
					@$this->header_array[$splitted_line[0]]=$splitted_line[1];
					$i++;
				}
			}
			fclose ($fp);
			
			
			$this->header_stream=$stream;
			
			$splitted_stream=split(" ",$line1);
			
			// Getting status code and description of the URL
			$this->code=$splitted_stream[1];
			$this->code_desc=$splitted_stream[2];
			if(count($splitted_stream)>3){
				for($i=3;$i<count($splitted_stream);$i++){
					$this->code_desc.=" ".$splitted_stream[$i];
				}
			}
			// Cleaning up for \n and \r
			$this->code_desc=preg_replace("[\\n]","",$this->code_desc);
			$this->code_desc=preg_replace("[\\r]","",$this->code_desc);
			
			// Getting Http Version
			$http_array=split("\/",$splitted_stream[0]);
			$this->http_version=$http_array[1];
		}
	}
	
	/**
	* Sets the timeout for getting header data from server
	* @param int $seconds time for timeout in seconds
	* @desc Sets the timeout for getting header data from server
	*/	
	function set_timeout($seconds){
		$this->timeout=$seconds;
	}
}
?>
