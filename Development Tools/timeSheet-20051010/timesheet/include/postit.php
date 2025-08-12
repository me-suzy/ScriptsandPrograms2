<?php
  function postIt($DataStream, $proto,$URL,$port) {

//  Separate into Host and URI
    $Host = substr($URL, 0, strpos($URL, "/"));
    $URI = strstr($URL, "/");

//  Form up the request body
    $ReqBody = "";
    while (list($key, $val) = each($DataStream)) {
      if ($ReqBody) $ReqBody.= "&";
      $ReqBody.= $key."=".urlencode($val);
    }
    $ContentLength = strlen($ReqBody);

//  Generate the request header
    $ReqHeader =
      "POST $URI HTTP/1.0\n".
      "Host: $Host\n".
      "User-Agent: PostIt\n".
      "Content-Type: application/x-www-form-urlencoded\n".
      "Content-Length: $ContentLength\n\n".
      "$ReqBody\n";

//     echo $ReqHeader;


//  Open the connection to the host
    $socket = fsockopen($proto.$Host, $port, $errno, $errstr);
    if (!$socket) {
      $Result["errno"] = $errno;
      $Result["errstr"] = $errstr;
      return $Result;
    }
    $idx = 0;
    fputs($socket, $ReqHeader);
    while (!feof($socket) && $Result[$idx-1] != "0\r\n") {
    if (substr($Result[$idx-1], 0, 2) == "0\r\n") echo "The End:".strlen($Result[$idx-1]);
      $Result[$idx++] = @fgets($socket, 128);
    }
    return $Result;
  }
?>
