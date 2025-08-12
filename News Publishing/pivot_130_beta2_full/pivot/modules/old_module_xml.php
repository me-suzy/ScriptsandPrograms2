<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }


class db_xml {
	
	// the name of the log
	var $logname;

	// the data for the current entry
	var $entry;

	// a nice and big array with all the dates.
	var $date_index;
	var $indexline;

	// a somewhat smaller array for the entries that share the same 
	// directory as the current entry
	var $update_mode;
	var $updated;
	var $entry_index;
	var $entry_index_filename;

	// pointer to where we are..
	var $pointer;

	// some names and stuff..
	var $weblog;



	function db_xml($name="standard", $load_index=TRUE) {
		global $global_pref;

		//init vars..

		$this->logname = $this->safestring($name);

		$this->entry = Array('code' => '', 'id' => '',  'template' => '',  'date' => '',  'user' => '',  'title' => '',  'subtitle' => '',  'introduction' => '',  'body' => '',  'media' => '',  'links' => '',  'url' => '',  'filename' => '',  'category' => '');

		$this->entry_index_filename="";
		$this->entry_index= Array();
		$this->date_index= Array();

		$this->update_mode=TRUE;
		$this->global_reindex=FALSE;
		// Load the index..
		//$this->read_date_index();


		$this->indexline= Array();

		//include "templates/templates.inc.php";
		$this->globals=$weblog;
		$this->globals=$weblog;
		//debug("cats: ".$this->globals['categories']);
		$this->globals['categories']=explode(',', $this->globals['categories']);


		// from the config.inc.php
		if (!isset($global_pref['entries_per_dir'])) {
			$global_pref['entries_per_dir']=100;
		}

	}


	function set_entry($row) {
	
		$this->entry=$row;

		$this->updated=TRUE;
		if ( ($this->entry['code']==">") || ($this->entry['code']==">") ) {
			$this->fixcode();
		}
		$this->set_filename();
		$this->set_id();

		//debug("code: ". $this->entry['code']);
		$this->date_index[$this->entry['code']]="1";

	}

/*
	function get_categories_select() {
		
		$output="";

		$this_cat=$this->entry['category'];

		if (isset($this->globals['categories'])) {
			$output="category:</td><td> <select name='f_cat'>";
			foreach ($this->globals["categories"] as $cat) {
				
				$cat= trim($cat);

				$sel = ($cat==$this_cat) ? " selected" : "";
				$output.="<option value='$cat'$sel>$cat</option>";
			}
			$output.="</select>&nbsp;";
		}
		return $output;
	}
*/


	function get_categories_num() {
		$output="";

		if (isset($this->globals['categories'])) {
			return (count($this->globals['categories']));
		} else {
			return 0;
		}
	}


	function get_entry($row="") {
		return $this->entry;
	}


	function delete_entry() {

		debug ("deleten maar!");
		unlink($this->set_filename());
		
		unset ($this->date_index[$this->entry['code']]);
		unset ($this->entry_index[$this->entry['code']]);

		$this->write_entry_index(TRUE);
		$this->write_date_index();

	}


	// only used for conversion
	function fixcode() {

		if ( ($this->entry['code']==">") || ($this->entry['code']==">") ) {
			ksort($this->date_index);
			$max=end($this->date_index);
			$max=key($this->date_index);
			$max++;
			$this->entry['code']=$max;
		} else {
			debug("fixcode: ".$this->entry['code']);
			$code=$this->entry['code'];
			@list($dummy,$code)=explode("-",$code);
			$this->entry['code']=$code;
		}
	}


	function set_id() {
		
		$this->entry['id']='standard-'.$this->entry['code'];
		return $this->entry['id'];
	}


	function get_outputfile() {
		global $global_pref;
		
		if ($this->entry['filename']=="ROOT") {
			$filename="../".$global_pref['index'];
		} else {
			$filename="../".str_replace("%1", $this->entry['code'], $global_pref['file_format'] );
		}
		return $filename;
	}


	function get_entries_count() {

//		debug (count($this->date_index));
		return count($this->date_index);
	}


	function set_url($code="") {
		global $global_pref;
		
		if ($code=="") { 
			$code= $this->entry['code']; 
			$id=$this->entry['id'];	
		}  else { 
			$id='';	
		}

		if ($code!="ROOT") {
			if ($global_pref['live_comments']) {
				$filename=$global_pref['pivot_url'].'entry.php?uid='.$id;
			} else {
				$filename=$global_pref['log_url'].str_replace("%1", $code, $global_pref['file_format'] );
			}
		} else {
			$filename=$global_pref['log_url'].$global_pref['index'];
		}
		$host="http://".$_SERVER['HTTP_HOST'];
		

		return $host.$filename;

	}

	function set_filename($code="") {
		global $global_pref;
		if ($code=="") {
			$code=$this->entry['code'];
		}


		if (strpos($code,"-")>0) {
		//	debug("ack");
			list($dummy,$code)=explode("-",$code);
		}

		if ($code==$this->logname) {
			$this->entry['filename']="ROOT";
			$this->entry['code']="ROOT";
			$this->entry['id']="ROOT";

		} else {
			$dircount=floor($code / $global_pref['entries_per_dir']);
			//echo " [ $code - $dircount ] ";
			$dir=sprintf("db/%s-%05d/", $this->logname, ( $global_pref['entries_per_dir'] * $dircount) );
			$filename=sprintf("%s-%05d.php", $this->logname, $code );
			// set it and return it as well..
			$this->entry['filename']=$dir.$filename;
		}
		return $this->entry['filename'];
	}

	
	function make_filename($code="") {
		global $global_pref;
	
		if (strpos($code, "-")>0) {
			list($dummy, $code)=explode("-", $code);
		}

		if ($code=="") {
			return $global_pref['log_url'].str_replace("%1",$this->entry['code'],$global_pref['file_format']);
		} else {
			return $global_pref['log_url'].str_replace("%1",$code,$global_pref['file_format']);
		}

	}

	function make_entry_index_filename() {
		global $global_pref;
		
		$code=$this->entry['code'];
		$dircount=floor($code / $global_pref['entries_per_dir']);
		//debug("code en dirc: $code - $dircount");
		$dir=sprintf("db/%s-%05d/", $this->logname, ( $global_pref['entries_per_dir'] * $dircount) );
		$filename=sprintf("index-%s-%05d.php", $this->logname, ( $global_pref['entries_per_dir'] * $dircount) );

		return $dir.$filename;
	
	}


	function safestring($name) {
		return ereg_replace("[^-a-zA-Z0-9_.]", "", $name);
	}

	function savexml($update_index=TRUE) {
		$xml = new XMLFile();
		$xml->create_root(); # necessary -- no root is created until requested
		$xml->roottag->name = 'PIVOTXML';
		$xml->roottag->add_subtag( 'ENTRY', array() );
		$entrytag= &$xml->roottag->curtag;
		//$entrytag->add_attribute( 'UID', $this->entry['id']);
		$entrytag->add_attribute( 'CODE', $this->entry['code']);
		@$entrytag->add_attribute( 'CATEGORY', trim($this->entry['category']));
	
		$entrytag->add_subtag( 'TEMPLATE', array() );
		$entrytag->curtag->cdata=$this->entry['template'];
	
		$entrytag->add_subtag( 'DATE', array() );
		$entrytag->curtag->cdata=$this->entry['date'];
	
		$entrytag->add_subtag( 'USER', array() );
		$entrytag->curtag->cdata=$this->entry['user'];
	
		$entrytag->add_subtag( 'TITLE', array() );
		$entrytag->curtag->cdata=html_entities($this->entry['title']);
		
		$entrytag->add_subtag( 'SUBTITLE', array() );
		$entrytag->curtag->cdata=html_entities($this->entry['subtitle']);
	
		$entrytag->add_subtag( 'INTRODUCTION', array() );
		$entrytag->curtag->cdata=html_entities($this->entry['introduction'], 1);
	
		$entrytag->add_subtag( 'BODY', array() );
		$entrytag->curtag->cdata=html_entities($this->entry['body']);

		$entrytag->add_subtag( 'CATEGORY', array() );
		@$entrytag->curtag->cdata=html_entities($this->entry['category']);
	
		$entrytag->add_subtag( 'MEDIA', array() );
		$entrytag->curtag->cdata=$this->entry['media'];
	
		$entrytag->add_subtag( 'LINKS', array() );

		if ( (isset($this->entry['comments'])) && (count($this->entry['comments'])>0) )  {
			foreach ($this->entry['comments'] as $comment) {
				$entrytag->add_subtag( 'COMMENT', array() );
		
				$commtag= &$entrytag->curtag;
				// When we start using the Thread_code, use this..
				//$commtag->add_attribute( 'THREAD_CODE', $comment['thread_code']);
				$commtag->add_attribute( 'THREAD_CODE', "");
			
				$commtag->add_subtag( 'NAME', array() );	
				$commtag->curtag->cdata=html_entities($comment['name']);
	
				$commtag->add_subtag( 'EMAIL', array() );	
				$commtag->curtag->cdata=html_entities($comment['email']);
	
				$commtag->add_subtag( 'URL', array() );	
				$commtag->curtag->cdata=html_entities($comment['url']);
	
				$commtag->add_subtag( 'IP', array() );	
				$commtag->curtag->cdata=$comment['ip'];
	
				$commtag->add_subtag( 'DATE', array() );	
				$commtag->curtag->cdata=$comment['date'];
	
				$commtag->add_subtag( 'COMMENT', array() );	
				$commtag->curtag->cdata=html_entities($comment['comment'], 1);
	   
			} // foreach
		} // if

		//debug ("file: ".$this->entry['filename']);
		// Make folder if it doesn't exist.
		$dir=dirname($this->entry['filename']);
		$oldumask = umask(0);
		@mkdir ("$dir", 0777);
		chmod ("$dir", 0777);
		

		// write the file
		$fh = fopen( $this->entry['filename'], 'w' );
		$xml->write_file_handle( $fh );
		fclose( $fh );
		chmod ($this->entry['filename'], 0666);
		umask($oldumask);


		$xml->cleanup();


		// There should be code here to update the current index.
		if ($update_index) {
			$this->update_index();
			$this->write_entry_index();
			$this->write_date_index();

		}
	}


	function generate_index() {

		$this->global_reindex=TRUE;
		$this->update_mode=FALSE;
		$this->date_index = Array();
		

		$d= dir("db");

		while ($filename=$d->read()) {
			$ext=getextension($filename);
			$pos=strpos($filename, $this->logname."-");
			if ( (!($pos===FALSE)) && ($pos==0) ) {
				echo "dir: $filename<br />\n";
				$this->index_entries($filename);
				flush();
			}
		}
		$d->close();

		//$this->write_entry_index(TRUE);
		$this->write_date_index();

	}


	function index_entries($dirname) {
	
		if (is_dir("db/".$dirname)) {
			debug("nu openen: ".$dirname);
			$d= dir("db/".$dirname);

			while ($filename=$d->read()) {
				$ext=getextension($filename);
				$pos=strpos($filename, $this->logname);
				if ( (!($pos===FALSE)) && ($pos==0) ) {
					$this->read_entry("db/".$dirname."/".$filename, TRUE);
					debug ("($filename is ok)");
				}
			}
			$d->close();
			$this->set_id();
			$this->write_entry_index(TRUE);
		}
	}

	function read_entry($filename, $updateindex=TRUE) {
		global $global_pref;
	
		$xml = new XMLFile();

		if ($filename=="") { $filename=$this->entry['filename']; }

		//debug("read entry: $filename". ( ($updateindex) ? "(T)" : "(F)"));

		if($this->entry['filename']=="ROOT") {
			debug("root lezen");
			$this->entry['template']="weblog.html";
			return;
		}


		@$fh = fopen( $filename, 'r' );
		if ($fh==false) {
			piv_error("File Does not Exist!", "One of the xml files needed ($filename) could not be found. <br /><br />This can normally be fixed by recreating the index-file. Click <a href='reindex.php'>reindex</a> to do so now, and then go <a href='javascript:history.go(-1)'>back</a> to the last page.", 0);
		}
	
		$xml->read_file_handle( $fh );
		fclose( $fh );
	
		$row_entrypoint= &$xml->roottag->tags[0];
		$row=&$xml->roottag->tags[0]->make_array();

		$this->entry['code'] = rem_code_padding($row_entrypoint->attributes['CODE']);
		$this->entry['id'] = $this->set_id();
		@$this->entry['category'] = trim($row_entrypoint->attributes['CATEGORY']);
		debug("leescat2: ".$this->entry['category']);

		$this->entry['template']= $row['template'];
		$this->entry['date']= $row['date'];
		$this->entry['user']= $row['user'];
		$this->entry['title']= rem_entities($row['title']);
		$this->entry['subtitle']= rem_entities($row['subtitle']);
		$this->entry['introduction']= rem_entities($row['introduction']);
		$this->entry['body']= rem_entities($row['body']);
		$this->entry['media']= $row['media'];
		$this->entry['links']= $row['links'];
		$this->entry['url']=$this['url'];
		$this->entry['filename']=$filename;

		unset($commnames);
		if (isset($row['comment'])) {
			
			for ($i=0;$i<count($row['comment']);$i++) {
				$row['comment'][$i]['name']=substr(rem_entities($row['comment'][$i]['name']),0,30);
				$row['comment'][$i]['email']=rem_entities($row['comment'][$i]['email']);
				$row['comment'][$i]['comment']=rem_entities($row['comment'][$i]['comment']);
				$commnames[]=$row['comment'][$i]['name'];
			}

			$this->entry['comments']=$row['comment'];

			$this->entry['commnames']=implode(", ",array_unique ($commnames));
			$commcount=count($commnames);
		} else {
			unset ($this->entry['comments']);
			$commcount=0;
		}
		
		$this->entry['commcount']=$commcount;

		if ($commcount==0) { 
			$commcount_str=$global_pref['commentsarr'][0];
			$this->entry['commcount_str']=str_replace("%num%", $global_pref['comments_numbersarr'][0], $commcount_str);
		} else if ($commcount==1) { 
			$commcount_str=$global_pref['commentsarr'][1]; 
			$this->entry['commcount_str']=str_replace("%num%", $global_pref['comments_numbersarr'][1], $commcount_str);
		} else {
			if (isset($global_pref['comments_numbersarr'][$commcount])) {
				$num=$global_pref['comments_numbersarr'][$commcount];
			} else {
				$num=$commcount;
			}
			$commcount_str=$global_pref['commentsarr'][2]; 
			$this->entry['commcount_str']=str_replace("%num%", $num, $commcount_str);
		}

		if ($this->entry['media']!="") {
			$media_lines=explode("#", $this->entry['media']);
			$i=0;
			unset($this->entry['media']);
			foreach ($media_lines as $media_line) {
				@list($file, $width, $height, $name)= explode("|", $media_line);
				$i++;
				$this->entry['media'][]=array ("file" => $file, "width" => $width, "height" => $height, "name" => $name);
			}
		}

		if ($this->entry['category']=="") {
			$this->entry['category']="standard";
		}

		$xml->cleanup();
		$this->update_index(FALSE);

		// Sometimes the code is another number than the filename would suggest. If so, 
		// this needs to be fixed, by renaming the file.
		if ($filename != $this->set_filename($this->entry['code']) ) {
			debug("rename: ".$filename ." , ",  $this->set_filename($this->entry['code']) );
			rename($filename ,  $this->set_filename($this->entry['code']) );
		}

		return TRUE;

	}

	function read_entry_code($code) {

		$filename=$this->set_filename($code);
		//debug("cd: $code - fn: $filename");
		$this->read_entry($filename, FALSE);

	}

	function print_entry() {

		echo "<pre>";
		print_r($this->entry);
		echo "</pre>";

	}

	function update_index($update=TRUE) {

		$this->check_current_index();

		if (strlen($this->entry['title'])>2) {
			$title=$this->entry['title'];
			$title=strip_tags($title);
		} else {
			$title=substr($this->entry['introduction'],0,300);
			$title=strip_tags($title);
			$title=str_replace("\n","",$title);
			$title=str_replace("\r","",$title);
			$title=substr($title,0,60);
		}

		$size=strlen($this->entry['introduction'])+strlen($this->entry['body']);

		$index_line=sprintf('"%s","%s","%s","%s"', $this->entry['code'], addslashes($this->entry['date']), addslashes($this->entry['user']), addslashes($title));
		$index_line.=sprintf(',"%s"', $this->entry['template']);
		$index_line.=sprintf(',"%s"', $size);

		if (isset($this->entry['comments'])) {

			$comment_count=0;
			unset($comment_names);

			for ($i=0;$i<count($this->entry['comments']);$i++) {
				//debug("comm-ip: ".$this->entry['comments'][$i]['ip']);
				//if (!(ip_check_block($this->entry['comments'][$i]['ip']))) {
					$comment_count++;
					$comment_names[]=$this->entry['comments'][$i]['name'];
				//}
			}
			$comment_names=implode("|",$comment_names);
			$index_line.=sprintf(',"%s","%s"', $comment_count, $comment_names);
		} else {
			$index_line.=sprintf(',"0",""');
		}

		$index_line.=sprintf(',"%s"',$this->entry['category']);
		
		if  ($this->entry['code'] != "") {
			//debug($this->entry['code']." - ".$index_line);
			$this->entry_index[ $this->entry['code'] ]=$index_line;		

			$this->date_index[ $this->entry['code'] ]= $this->entry['date'];
		}
	
		if ($update) {
			$this->updated=TRUE;
		}
		
	}

	

	function write_entry_index($force=FALSE) {

		$this->make_entry_index_filename();

		if ($this->global_reindex) {
			//debug("sort index");
			ksort($this->entry_index);
		}

		if ( ($this->entry_index_filename!="") && (($this->updated)||($force))  ) {
			$fp=fopen($this->entry_index_filename, "w");
			foreach ($this->entry_index as $key => $value) {
				$value=trim($value)."\n";
				fwrite($fp, $key."||".$value);	
			}
			fclose($fp); 
		} else {
			//debug("Nothing to write");
		}
	}

	function write_date_index() {

		debug("Write date_index");

		asort($this->date_index);
		$fp=fopen("db/index-".$this->logname."-dates.php", "w");
		foreach ($this->date_index as $key => $value) {
			$value=trim($value)."\n";
			fwrite($fp, $key."|".$value);	
		}
		fclose($fp); 
	}


	function check_current_index() {

		$entry_index_file = $this->make_entry_index_filename();
		if ($entry_index_file != $this->entry_index_filename) {
			// ergo. the current dir's index is not in memory..
			$this->write_entry_index();
			$this->read_entry_index($entry_index_file);
		}
		
	}

	function read_entry_index($filename) {
	
		$this->entry_index_filename = $filename;
		$this->entry_index = Array();

		//debug("entry-index: $filename");

		if ($this->update_mode) {

			$entry_file=file($filename);
			foreach($entry_file as $line) {
				//debug($line);
				list($code, $line)=explode("||",$line);
				$this->entry_index[$code]=$line;
			}

		}
		$this->updated=FALSE;
	}

	function read_date_index() {

		if (count($this->date_index)<2) {

			$date_index=file("db/index-".$this->logname."-dates.php");
			foreach ($date_index as $line) {
				list($key, $value)=explode("|", $line);
				//echo ".";
				$this->date_index[$key]=$value;
		
			}
			$this->updated=FALSE;
			//debug("load_date_index: ".count($this->date_index)." entries");
		}
	}



function setpointer($where, $index=0) {
		
	$res=TRUE;
	switch ($where) {
		case "last":
			end($this->date_index);
			break;	
		case "first":
			reset($this->date_index);
			break;
		case "prev":
			$res = prev($this->date_index);
			break;
		case "next":
			$res = next($this->date_index);
			break;		
		case "to":

			end($this->date_index);
			$counter=0;
			do {
				if (key($this->date_index)==$index) { break; }
				prev($this->date_index);
				$counter++;
			} while ($counter<10000);
			break;
		case "rewind":
			for ($i=0;$i<$index;$i++) {
				prev($this->date_index);
			}
			break;
		case "fastforward":
			reset($this->date_index);
			for ($i=0;$i<$index;$i++) {
				next($this->date_index);
			}
			break;
	}

	if ($res) {
		$current=current($this->date_index);
		$key=key($this->date_index);
		//debug("key: $key, cur: $current");
		$this->entry['code']=$key;
		$this->check_current_index();

		$this->entry_from_indexline();
		return TRUE;
	} else {
		return FALSE;
	}
}

function get_previous_code() {

	$org_code=$this->entry['code'];
	$this->setpointer('to', $org_code);
	if($this->setpointer("prev")) {
		$this->previous_title=$this->entry['title'];
		$result=$this->entry['code'];
	} else {
		$this->previous_title="";
		$result=FALSE;
	}
	$this->setpointer('to', $org_code);

	return $result;
}


function get_previous_title() {
	return $this->previous_title;
}

function get_next_code() {

	$org_code=$this->entry['code'];
	$this->setpointer('to', $org_code);
	if($this->setpointer("next")) {
		$this->next_title=$this->entry['title'];
		$result=$this->entry['code'];
	} else {
		$this->previous_title="";
		$result=FALSE;
	}
	$this->setpointer('to', $org_code);
	return $result;
}


function get_next_title() {
	return $this->next_title;
}

function entry_from_indexline() {
	global $global_pref;

	// split the thing..
	@list( $this->entry['code'], $this->entry['date'], $this->entry['user'], $this->entry['title'], 
		$this->entry['template'], $this->entry['size'], $this->entry['commcount'], $this->entry['cnames'], $this->entry['category'] )
		 = split('","',$this->entry_index[$this->entry['code']]);


	$this->entry['code'] = substr($this->entry['code'],1);
	$this->entry['id'] = $this->logname."-".$this->entry['code'];
	$this->entry['filename']= $this->set_filename();
	//debug("zet cat: ".$this->entry['category']);

	$this->entry['category'] = trim(str_replace('"', '', substr($this->entry['category'],0,-1)));
	debug("leescat1: +".$this->entry['category']."+ - ". $this->entry['title']);

	if ($this->entry['category']=="") {
		$this->entry['category']="standard";
	}
	if ($this->entry['cnames']=='') {
		unset($this->entry['cnames']);
		$this->entry['commnames']="";
		$this->entry['commcount']=0;
	} else {
		$this->entry['cnames']=explode("|",$this->entry['cnames']);
		$this->entry['cnames']=array_unique($this->entry['cnames']);
		$this->entry['commnames']=implode(", ",$this->entry['cnames']);
		$this->entry['commcount']=count($this->entry['cnames']);
	}


	
	if ($this->entry['title']=="") { $this->entry['title']="No title.."; }
	$this->entry['title']=stripslashes($this->entry['title']);
	if (strlen($this->entry['title'])>$global_pref['max_chars']) { 
		$this->entry['title_short']=substr($this->entry['title'],0,50).'...'; 
	} else {
		$this->entry['title_short']=$this->entry['title'];
	}	
	$this->entry['size']= (int) $this->entry['size'];
	$this->entry['commcount']= (int) $this->entry['commcount'];
	
	if ($this->entry['size']>1024) {
		$this->entry['print_size']=sprintf("%01.1f Kb",  $this->entry['size']/1024.0);
	} else {
		$this->entry['print_size']=sprintf("%d B", $this->entry['size']);
	} 
	

	$this->entry['title_safe']=str_replace("'", "", $this->entry['title']);
	$this->entry['title_safe']=str_replace('"', "", $this->entry['title_safe']);
	$this->entry['title_safe']=ereg_replace("[^a-zA-Z0-9 _.,]", "", $this->entry['title_safe']);
	}


} // end of class


?>
