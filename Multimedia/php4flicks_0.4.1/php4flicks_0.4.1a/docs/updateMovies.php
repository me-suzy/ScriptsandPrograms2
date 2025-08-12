
<?
	include('../config/config.php');
	
	// this will take a while....
	$res = mysql_query("SELECT COUNT(*) AS count FROM movies") or die(mysql_error());
	$row = mysql_fetch_array($res);
	$count = $row['count'];
	
	set_time_limit($count*3);
	
	
	/************************************************
	*** change db structure
	************************************************/
	
	
	
	echo 'updating db structure...<br/><br/>';
	
	/**
	*** MOVIES table
	**/
	
	mysql_query("ALTER TABLE `movies` DROP PRIMARY KEY") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` ADD `fid` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` CHANGE `lang` `lang` SET( 'DE', 'EN', 'FR','ES','Other') DEFAULT 'EN'") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` CHANGE `medium` `medium` ENUM( 'dvd', 'vhs', 'svhs', 'divX/Xvid', 'vcd/Svcd', 'dvd-r' ) DEFAULT 'DVD' NOT NULL") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` CHANGE `format` `format` ENUM( 'PAL', 'NTSC' ) DEFAULT 'PAL'") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` CHANGE `ratio` `ratio` ENUM( '16:9', '4:3','letterbox' ) DEFAULT '16:9'") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` CHANGE `sound` `sound` SET( 'DD', 'DTS', 'stereo', 'mono' ) DEFAULT NULL") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` ADD `aka` varchar(200)") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` DROP INDEX `name`") or print('notice: index name not present in movies<br/>');
	mysql_query("ALTER TABLE `movies` ADD FULLTEXT `i_name_aka_full` (`name` ,`aka`)") or die(mysql_error());
	mysql_query("ALTER TABLE `movies` ADD `genre` SET( 'Action', 'Adult', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary', 'Drama', 'Family', 'Fantasy', 'Film-Noir', 'Horror', 'Music', 'Musical', 'Mystery', 'Romance', 'Sci-Fi', 'Short', 'Thriller', ' War', 'Western' )") or die(mysql_error());

	/**
	*** directs table
	**/
	
	mysql_query("ALTER TABLE `directs` ADD `movie_fid` SMALLINT UNSIGNED NOT NULL FIRST") or die(mysql_error());
	mysql_query("ALTER TABLE `directs` DROP PRIMARY KEY") or print('notice: pk directs not present<br/>');
	mysql_query("ALTER TABLE `directs` DROP INDEX `people_id`") or print('notice: index directs not present<br/>');	
	
	// replace movies.id by movies.fid
	$res = mysql_query("SELECT movie_id,people_id FROM directs") or die(mysql_error());
	$oldid = '';
	while($row=mysql_fetch_array($res)){
		$id = $row['movie_id'];
		if($id != $oldid){
			$res2 = mysql_query("SELECT fid FROM movies WHERE movies.id=$id") or die(mysql_error());
			if(mysql_num_rows($res2)==0)
				mysql_query("DELETE FROM directs WHERE movie_id=$id") or die(mysql_error());
			else{
				$row2 = mysql_fetch_array($res2);
				$fid = $row2['fid'];
				mysql_query("UPDATE directs SET movie_fid=$fid WHERE movie_id=$id") or die(mysql_error());
				$oldid = $id;
			}
		}
	}
	mysql_query("ALTER TABLE `directs` ADD PRIMARY KEY ( `movie_fid` , `people_id` )") or die(mysql_error());
	
	/**
	*** writes table
	**/

	mysql_query("ALTER TABLE `writes` ADD `movie_fid` SMALLINT UNSIGNED NOT NULL FIRST") or die(mysql_error());
	mysql_query("ALTER TABLE `writes` DROP PRIMARY KEY") or print('notice: pk writes not present<br/>');;
	mysql_query("ALTER TABLE `writes` DROP INDEX `people_id`") or print('notice: index writes not present<br/>');
	
	$res = mysql_query("SELECT movie_id,people_id FROM writes") or die(mysql_error());
	$oldid = '';
	while($row=mysql_fetch_array($res)){
		$id = $row['movie_id'];
		if($id != $oldid){
			$res2 = mysql_query("SELECT fid FROM movies WHERE movies.id=$id") or die(mysql_error());
			if(mysql_num_rows($res2)==0)
				mysql_query("DELETE FROM writes WHERE movie_id=$id") or die(mysql_error());
			else{
				$row2 = mysql_fetch_array($res2);
				$fid = $row2['fid'];
				mysql_query("UPDATE writes SET movie_fid=$fid WHERE movie_id=$id") or die(mysql_error());
				$oldid = $id;
			}
		}
	}
	mysql_query("ALTER TABLE `writes` ADD PRIMARY KEY ( `movie_fid` , `people_id` )") or die(mysql_error());
		
	/**
	*** plays_in table
	**/

	mysql_query("ALTER TABLE `plays_in` ADD `movie_fid` SMALLINT UNSIGNED NOT NULL FIRST") or die(mysql_error());
	mysql_query("ALTER TABLE `plays_in` DROP PRIMARY KEY") or print('notice: pk plays_in not present<br/>');
	mysql_query("ALTER TABLE `plays_in` DROP INDEX `people_id`") or print('notice: index plays_in not present<br/>');
	
	$res = mysql_query("SELECT movie_id,people_id FROM plays_in") or die(mysql_error());	
	$oldid = '';
	while($row=mysql_fetch_array($res)){
		$id = $row['movie_id'];
		if($id != $oldid){
			$res2 = mysql_query("SELECT fid FROM movies WHERE movies.id=$id") or die(mysql_error());
			if(mysql_num_rows($res2)==0)
				mysql_query("DELETE FROM plays_in WHERE movie_id=$id") or die(mysql_error());
			else{
				$row2 = mysql_fetch_array($res2);
				$fid = $row2['fid'];
				mysql_query("UPDATE plays_in SET movie_fid=$fid WHERE movie_id=$id") or die(mysql_error());
				$oldid = $id;
			}
		}
	}
	mysql_query("ALTER TABLE `plays_in` ADD PRIMARY KEY ( `movie_fid` , `people_id` )") or die(mysql_error());
	
	
	
	/************************************************
	*** and now add genres
	************************************************/
	
	
	echo 'db structure changed.<br/><br/>adding genres...<br/><br/>';
	
	$res = mysql_query("SELECT DISTINCT id FROM movies WHERE isnull(genre)") or die(mysql_error());
	$count = mysql_num_rows($res);
	$fp = null;
	
	// this will take a while...
	set_time_limit($count*2);
	
	while($row=mysql_fetch_array($res)){
		$imdbid = $row['id'];
		// get imdb page, parse for genres, update
		
		// query
		$data = "GET /title/tt$imdbid/ HTTP/1.0\r\n";
		$data .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.5) Gecko/20031007 Firebird/0.7\r\n";
		$data .= "Accept: text/html, image/png, image/x-xbitmap, image/gif, image/jpeg, */*\r\n";
		$data .= "Accept-Language: en, de\r\n";
		if($cfg['http_compress'])
			$data .= "Accept-Encoding: gzip, deflate, identity, chunked;q=0, *;q=0\r\n";
		else
			$data .= "Accept-Encoding: identity, chunked;q=0, *;q=0\r\n";
		$data .= "Referer: http://imdb.com/\r\n";  //with given referer
		$data .= "Host: imdb.com:80\r\n";
		$data .= "Connection: Keep-Alive\r\n";
		$data .= "Cache-Control: no-cache\r\n";
		$data .= "\r\n";
		
		// send
		$fp = fsockopen('imdb.com', 80);

		fputs($fp, $data);
		

		$result = '';
		while (!feof($fp)) {
			$result .= fgets ($fp, 1024);
		}
		
		fclose($fp);
		
		if($cfg['http_compress']){
			// support for gzipped html
			if(strpos($result,'Content-Encoding: gzip')>0){
				// strip http header
				$pos = strpos($result,"\r\n\r\n")+14;
				// and decode gz
				$result = gzinflate(substr($result,$pos));
			}
		}
		
		// parse result
		if(preg_match('#Genre:</B>\n?(.*)(<a href="/Keywords)?#is', $result, $gen)){
			$gen = $gen[1];
			$ret = array();
        	while(preg_match("#<a href=\"/Sections/Genres/[a-zA-Z\\-]*/\">([a-zA-Z\\-]*)</a>#is", $gen, $x)) {
				$gen = substr($gen,strpos($gen,$x[0])+strlen($x[0]));
				$ret[] = $x[1];
			}
			if(sizeof($ret)>0)
				mysql_query("UPDATE movies SET genre='".implode(',',$ret)."' WHERE id=$imdbid") or die(mysql_error());
		}
	}
	
	echo "$count movies updated.";
	
	// to keep old values, comment these lines out
	mysql_query("ALTER TABLE `directs` DROP `movie_id`") or print('');
	mysql_query("ALTER TABLE `writes` DROP `movie_id`") or print('');
	mysql_query("ALTER TABLE `plays_in` DROP `movie_id`") or print('');
?>
