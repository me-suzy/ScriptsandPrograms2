<?php
if(!function_exists('remote_file_exists')){
/**
 * Exam if a remote file or folder exists
 *
 * This function is adopted from setec's version.
 * + What's new:
 * Error code with descriptive string.
 * More accurate status code handling.
 * Redirection tracing supported.
 *
 * @retval true resource exists
 * @retval false resource doesn't exist
 * @retval "1 Invalid URL host"
 * @retval "2 Unable to connect to remote host"
 * @retval "3 Status Code not supported: {STATUS_CODE REASON}"
 */
function remote_file_exists($url)
{
   $head = '';
   $url_p = parse_url ($url);

   if (isset ($url_p['host']))
   { $host = $url_p['host']; }
   else
   {
       return '1 Invalid URL host';
   }

   if (isset ($url_p['path']))
   { $path = $url_p['path']; }
   else
   { $path = ''; }

   $fp = @fsockopen($host,80,$errno,$errstr,20);
   if (!$fp)
   {
       return '2 Unable to connect to remote host';
   }
   else
   {
       $parse = parse_url($url);
       $host = $parse['host'];

       fputs($fp, 'HEAD '.$url." HTTP/1.1\r\n");
       fputs($fp, 'HOST: '.$host."\r\n");
       fputs($fp, "Connection: close\r\n\r\n");
       $headers = '';
       while (!feof ($fp))
       { $headers .= fgets ($fp, 128); }
   }
   fclose ($fp);
  
   // for debug
   //echo nl2br($headers);
  
   $arr_headers = explode("\n", $headers);
   if (isset ($arr_headers[0]))    {
       if(strpos ($arr_headers[0], '200') !== false)
       { return true; }
       if( (strpos ($arr_headers[0], '404') !== false) ||
           (strpos ($arr_headers[0], '410') !== false))
       { return false; }
       if( (strpos ($arr_headers[0], '301') !== false) ||
           (strpos ($arr_headers[0], '302') !== false))
       {
           preg_match("/Location:\s*(.+)\r/i", $headers, $matches);
           if(!isset($matches[1]))
               return false;
           $nextloc = $matches[1];
           return remote_file_exists($nextloc);
       }
   }
   preg_match('/HTTP.*(\d\d\d.*)\r/i', $headers, $matches);
   return '3 Status Code not supported'.
       (isset($matches[1])?": $matches[1]":'');
}
}
?>