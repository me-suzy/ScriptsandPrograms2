<?	/*	php4flicks movie database (c) mr.Fox					*
 	*	released under the GNU General Public License				*
 	*	contact and additional information: http://php4flicks.ch.vu		*/

	// fetchimg.php - gets image from file or urland temporarily stores it as session data!
	// session must have been initialized

	function fetchimg($movieid, $url=''){
		if($url=='' || $url=='http://'){
			//no url specified, so image is supposed to have been uploaded as file. check img data:

			// mark file as non-existent if not present.
			isset($_FILES['file']) or $_FILES['file']['size'] = 0;
			
			$_FILES['file']['size']>0 or $_FILES['file']['error'] = 4;
			//otherwise nonexistent files cause no error => bug?
		
			//if fileupload was ok, filetype must be type image/...
			($_FILES['file']['error'] || substr($_FILES['file']['type'],0,6) == 'image/') or $_FILES['file']['error'] = 42;
					
			if(!$_FILES['file']['error']){
				// no error, so store file
				$handle = fopen($_FILES['file']['tmp_name'], 'rb');
				unset($_SESSION['image']);
				$_SESSION['image'][$movieid] = fread($handle, filesize($_FILES['file']['tmp_name']));
				fclose($handle);
				return '';
				
			} else {
				switch($_FILES['file']['error']){
					case 1:
					case 2: return 'the file is too large.'; break;
					case 3: return 'file upload was interrupted.'; break;
					case 42: return 'please select a .gif or .jpg file!'; break;
					default: return 'no file was uploaded.';
				}
			}
		} else {
			//url present, so fetch image from url!
			//special case: imdb url, since imdb does not allow linking to its pages. we've got to send out faked headers...
			if($url == 'http://i.imdb.com/Heads/npa.gif')
				return '';	// the 'NO PIC AVAILABLE' pic, DON'T store it!
			
			if(substr($url,0,19)=='http://ia.imdb.com/'){
				// it's an imdb-url
				$data = "GET ".$url." HTTP/1.0\r\n";
				$data .= "User-Agent: Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)\r\n";
				$data .= "Accept: text/html, image/png, image/x-xbitmap, image/gif, image/jpeg, */*\r\n";
				$data .= "Accept-Language: en, de\r\n";
				$data .= "Accept-Encoding: gzip, deflate, x-gzip, identity, *;q=0\r\n";
				$data .= "Referer: imdb.com\r\n";
				$data .= "Host: ia.imdb.com\r\n";
				$data .= "Connection: close\r\n";
				$data .= "Cache-Control: no-cache\r\n";
				$data .= "\r\n";
				
				// open connection to imdb server
				$fp = @fsockopen('ia.imdb.com', 80);
				if (!$fp) 
					return 'could not open url.';
					
				// put request header
				fputs ($fp, $data);
				
				// get only image, not header
				$line='';
				while(!feof($fp)&&$line!="\r\n") {
					$line = fgets($fp,256);
				}
				// fetch img
				unset($_SESSION['image']);
				$_SESSION['image'][$movieid] = '';
				while(!feof($fp)) {
   					$_SESSION['image'][$movieid] .= fread($fp,8192);
   				}
   				return '';
   			} else {
   				//'normal' url
   				if(!preg_match("/(\.jpg|\.jpeg|\.gif)$/i",$url))
   					return 'please select a .gif or .jpg file!';
   				   				
				$handle = @fopen($url, 'rb');
				if (!$handle) 
					return 'could not open url.';
				
				unset($_SESSION['image']);
				$_SESSION['image'][$movieid] = '';
					
				do{
   					$data = fread($handle, 8192);
   					if (strlen($data) == 0)
       					break;
   					$_SESSION['image'][$movieid] .= $data;
				} while (true);
				
				fclose($handle);
				return '';
			}
		}
	}
?>
