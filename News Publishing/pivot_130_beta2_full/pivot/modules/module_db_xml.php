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

// lamer protection
if (strpos($pivot_path,"ttp://")>0) {	die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection


class db_lowlevel {

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

// public functions

function db_lowlevel($loadindex=TRUE, $allow_write=TRUE) {
	global $global_pref;

	//init vars..

	// Logname will be phased out eventually, since all will be based on categories.
	$this->logname = "standard";

	$this->entry = Array('code' => '', 'id' => '',  'template' => '',  'date' => '',  'user' => '',  'title' => '',  'subtitle' => '',  'introduction' => '',  'body' => '',  'media' => '',  'links' => '',  'url' => '',  'filename' => '',  'category' => '');

	$this->entry_index_filename = "";
	$this->entry_index = Array();
	$this->date_index = Array();
	$this->cat_index = Array();

	$this->update_mode=TRUE;
	$this->global_reindex=FALSE;


	// Load the index..
	if ($loadindex) {
		$this->read_date_index();
		$this->allow_index=TRUE;
	} else {
		$this->allow_index=FALSE;		
	}

	// Load the index..
	if ($allow_write) {
		$this->allow_write=TRUE;
	} else {
		$this->allow_write=FALSE;		
	}


	$this->indexline= Array();


	// from the config.inc.php
	if (!isset($global_pref['entries_per_dir'])) {
		$global_pref['entries_per_dir']=100;
	}

}


//my getlist function, replaces old getlist function.
function getlist($amount, $offset=0, $filteronuser="", $filteroncat="", $order=TRUE, $field="date") {
	
	global $absmax;

	// if amount is positive, it means 'get the $amount first entries', which means
	//   from the beginning of the $this->date_index
	// if amount is negative, it means 'get the $amount last entries', which means 
	//   from the end of the $this->date_index
	//
	// offset is always considered to be positive
	// 
	// order defines whether the results are in chronological order. 
	//   0: reverse chronological
	//   1: chronological


	if (($filteronuser=="") && ($filteroncat=="") && ( ($field=="date") || ($field=="") )) {
		return $this->getlist_partial($amount, $offset, $order, $field);
	} else {
		debug("full getlist: $field...");
		return $this->getlist_all($amount, $offset, $filteronuser, $filteroncat, $order, $field);
	}


}


function getlist_partial($amount, $offset=0, $order=TRUE, $field="") {


    $total = count($this->date_index);

    $offset = abs($offset);

    $overview_arr = array();

    // treat forgotten '0' case
    switch( true ) {
        case( $amount > 0 ) :
            // if amount is positive, iterate forward
            $start_at = $offset;
            $stop_at  = $amount+$offset;

            // set pointer to first element
            reset( $this->date_index );

            // skip to where we start
            for( $i=0;$i<$start_at; $i++ ) {
                next( $this->date_index );
            }

            // get the next $amount number of entries
            for ($i=0; $i<$amount; $i++) {
                $this_dateindex_line= each($this->date_index);
                if ($this_dateindex_line['key']!="") {
                    $this->entry['code']=$this_dateindex_line['key'];
                    $this->check_current_index();
                    $this->entry_from_indexline();
                    $overview_arr[]=$this->entry;
                }
            }
            break ;

        case( $amount < 0 ) :
            // if amount is negative, iterate backwards
            $amount=abs($amount);

            $start_at=max(1, $total-$amount-$offset+1);
            $stop_at=$total-$offset;

            // set pointer to last element
            end($this->date_index);

            // skip to where we start
            for ($i=$total;$i>$start_at; $i--) {
                prev($this->date_index);
            }

            // get the next $amount number of entries
            for ($i=0; $i<$amount; $i++) {
                $this_dateindex_line= each($this->date_index);
                if ($this_dateindex_line['key']!="") {
                    $this->entry['code']=$this_dateindex_line['key'];
                    $this->check_current_index();
                    $this->entry_from_indexline();
                    $overview_arr[]=$this->entry;
                }
            }
            break ;

        default:
        // do nothing - $amount == 0 ;
        // JM - this may leave a phantom entry 0...
        // but otherwise pivot goes titsup with error messages...
    }

    // if $order==1, we need to reverse the array
    if( $order == FALSE ) {
        $overview_arr = array_reverse( $overview_arr );
    }
    return $overview_arr;

} 


function getlist_all($amount, $offset=0, $filteronuser="", $filteroncat="", $order=TRUE, $field="") {

	//builds a temporary array containing all the postings
	for ($i=0; $i<count($this->date_index); $i++) {
		$this_dateindex_line = each($this->date_index);
		if ($this_dateindex_line['key']!="") {
			$this->entry['code']=$this_dateindex_line['key'];
			$this->check_current_index();
			$this->entry_from_indexline();
			if ( ( ($filteroncat=="") || ($this->intersect($filteroncat, $this->entry['category'])) ) &&
			( ($filteronuser=="") || ($filteronuser == $this->entry['user']) ) ) {
				$init_arr[]=$this->entry;
			}
		}
	} 
	


	// Global var $absmax is used in the entries screen, to enable paginating
	$absmax = count($init_arr);
		
	//if the field to sort by isn't the date field, build the sort array
	if($field != "date" && $field != "") {
	  //build the array to sort by
	  foreach ($init_arr as $row_key => $row) 
	  {
	   	   $sort_array[] = $row[$field];
	  }
	 //sorts the $init_arr based on the $sort_array
	   array_multisort($sort_array, $init_arr);
	   if ($order==FALSE) {
	      $init_array = array_reverse($init_arr);
	 }
	}
	//if it is the date field, and ammount is negative, reverse the array
	else if($amount < 0) {
	  $init_arr=array_reverse($init_arr);
	}
   
	//if the amount was negative, we flip the array around so we can just do
   //grab the needed entries like normal
	$start_at = $offset;
	$stop_at = ($offset + abs($amount)) <= count($this->date_index) ? $offset+abs($amount) : count($this->date_index);
	
	for ($i=0;$i<$start_at; $i++) {
		next($init_arr);
	}
	for($i=$start_at;$i<$stop_at;$i++) {
		
		$elm = current($init_arr);
		if ($elm=="") { 
			break; 
		} else {
			$overview_arr[] = $elm;
			next($init_arr);
		}
	}
   
	if(($amount < 0 && $field == "") || $order==FALSE) {
	  $overview_arr = array_reverse($overview_arr);
	}



	return $overview_arr;


}



function getlist_end($amount, $filteronuser="", $filteroncat="") {
	// gets entries from the end, filtered on cats.
	// set pointer to last element

	// if we're getting a list of empty cats, we can just return
	if (count($filteroncat)==0) {
		return array();
	}

	$amount=abs($amount);

	$reverse = array_reverse($this->date_index, TRUE);
	reset ($reverse);

	$found=0;

	while (list ($key, $val) = each ($reverse)) {

		if ($key!="") {
			$this->entry['code']=$key;
			$this->check_current_index();
			$this->entry_from_indexline();
			if ( ($filteroncat=="") || ($this->intersect($filteroncat, $this->entry['category'])) ) {
				if ($this->entry['status']=="publish") {
					$overview_arr[]=$this->entry;
					$found++;
				}
			}
		}

		if ($found>=$amount) { 
			break; }
	}

	return $overview_arr;

}


function getlist_range($start_date, $stop_date, $filteronuser="", $filteroncat="", $order=TRUE) {

	// gets the entries that were created between $start_date and $stop_date
	// 
	// order defines whether the results are in chronological order. 
	//   0: reverse chronological
	//   1: chronological

	$overview_arr= array();


	foreach ($this->date_index as $code => $date) {
			//echo ".";

		if ( ($start_date <= $date) && ($date <= $stop_date) ) {
			if ( ($filteroncat=="") || ($this->intersect($filteroncat, explode('|',$this->cat_index[$code]) )) ) {
				$this->entry['code']=$code;
				$this->check_current_index();
				$this->entry_from_indexline();
				$overview_arr[]=$this->entry;
			}

		}

	}


	// if $order==1, we need to reverse the array
	if ($order==FALSE) {
		$overview_arr=array_reverse($overview_arr);
	}
	return $overview_arr;
}






function getcodes_range($start_date, $stop_date, $filteronuser="", $filteroncat="", $order=TRUE) {

	// gets the codes of that were created between $start_date and $stop_date
	// 
	// order defines whether the results are in chronological order. 
	//   0: reverse chronological
	//   1: chronological
	// this iss used next to getlist_range, but uses much less memory (one-dimensional array 
	// with codes, instead of huge array with stuff we don't need

	$overview_arr= array();

	foreach ($this->date_index as $code => $date) {

		if ( ($start_date <= $date) && ($date <= $stop_date) ) {
			if ( ($filteroncat=="") || ($this->intersect($filteroncat, explode('|',$this->cat_index[$code]) )) ) {
				//$this->entry['code']=$code;
				//$this->check_current_index();
				//$this->entry_from_indexline();
				$overview_arr[]=$code;
			}

		}

	}

	// if $order==1, we need to reverse the array
	if ($order==FALSE) {
		$overview_arr=array_reverse($overview_arr);
	}
	return $overview_arr;
}



function get_archive_array($force=FALSE) {

	if ( ($force) || (!file_exists('db/ser-archives.php')) ) {

		add_log("rebuild archive");
		debug("REBUILD make_archive_array");
		
		$Archive_array=array();
		$lastdate = 0;
	
		ksort($this->date_index);
		
		foreach ($this->date_index as $code => $date) {

			$this->entry['code']=$code;
			$this->check_current_index();
			$this->entry_from_indexline();
			
			// we calculate the archivename only once for every three days
			$thisdate = floor(str_replace("-", "", $this->entry['date']) / 30000);
			
			//debug("entry:" . $this->entry['date'] . " - " . $this->entry['code']);
			
			if ($thisdate != $lastdate) {
				$in_weblogs = find_weblogs_with_cat($this->entry['category']);
				foreach ($in_weblogs as $in_weblog) {
					$name = make_archive_name($this->entry['date'], $in_weblog);
					$Archive_array[$in_weblog][$name] = $this->entry['date'];
				}
				$lastdate = $thisdate;	
			}
		}

		// sort the array, to maintain correct order..
		foreach ($Archive_array as $key => $value) {
			krsort($Archive_array[$key]);
		}		
		
		// save the archive_array, for easier retrieval next time..
		save_serialize('db/ser-archives.php', $Archive_array);
	

	} else {

		// just load the file, and get the last 3 entries. Much easier..
		$Archive_array = load_serialize('db/ser-archives.php');
		$entries_arr = $this->getlist(-3);

		// loop for all entries
		foreach ($entries_arr as $loopentry) {
		
			// then loop for all the weblogs that publish this entry
			$in_weblogs = find_weblogs_with_cat($loopentry['category']);
			foreach ($in_weblogs as $in_weblog) {

				$name = make_archive_name($loopentry['date'], $in_weblog);
				$Archive_array[$in_weblog][$name] = $loopentry['date'];

			}
		}
		
		// sort the array, to maintain correct order..
		foreach ($Archive_array as $key => $value) {
			krsort($Archive_array[$key]);
		}
	}

	
	return $Archive_array;

}





function disallow_write() {
	$this->allow_write=FALSE;
}


function allow_write() {
	$this->allow_write=TRUE;
}

// get the number of entries
function get_entries_count() {

	return count($this->date_index);

}



// get the code of the next entry
function get_next_code($num) {

	$code = $this->entry['code'];
	$ok = TRUE;
	$found=0;

	// first we move the pointer to where we are at now..
	reset($this->date_index);
	while ($ok && (key($this->date_index) != $code)) {
		$ok = next($this->date_index);
	} 

	// then step back to the previous one
	do {
		$ok = next($this->date_index);
		$found++;
	} while ($ok && ($found<$num) );

	// if $ok, that must mean the last one is the one.
	if ($ok) {
		return key($this->date_index);
	} else {
		return false;
	}

}



// get the code of the prevous entry
function get_previous_code($num) {

	$code = $this->entry['code'];
	$ok=TRUE;
	$found=0;

	// first we move the pointer to where we are at now..
	reset($this->date_index);
	while ($ok && (key($this->date_index) != $code)) {
		$ok = next($this->date_index);
	} 

	// then step back to the previous one
	do {
		$ok = prev($this->date_index);
		$found++;
	} while ($ok && ($found<$num) );

	// if $ok, that must mean the last one is the one.
	if ($ok) {
		return key($this->date_index);
	} else {
		return false;
	}

}


function need_index() {

	// the flat file database needs an index.
	return TRUE;

}

// This will rebuild the index of the flatfile Database
function generate_index() {

	$this->global_reindex=TRUE;
	$this->update_mode=FALSE;
	$this->date_index = Array();
	$this->cat_index = Array();
	
	add_log("Start rebuild index");


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

	add_log("Finish rebuild index");

	$this->write_date_index();



}


function entry_exists($code) {

	$filename=$this->set_filename($code);

	return file_exists($filename);

}


function get_date($code) {

	if (isset($this->date_index[$code])) {
		return $this->date_index[$code];
	} else {
		return 0;
	}

}


function read_entry($code, $force=FALSE) {

	$filename=$this->set_filename($code);

	$this->read_entry_filename($filename, FALSE, $force);

	//add_log("Read entry '". $this->entry['title'] ."' (". $this->entry['code'] .")");

	return $this->entry;
}



// 2005/01/01 =*=*= changed JM
function set_entry( $entry ) {

    $this->entry = $entry;
        
    if ( $this->entry['code'] == '>' ) {
    	if (is_array ( $this->date_index )) {
	        ksort( $this->date_index );
	        $max = end( $this->date_index );
	        $max = key( $this->date_index );
	        $max = $max + 1;
	        $this->entry['code'] = $max;
	    } else { 
	    	$this->entry['code'] = 1;
	    }
    }
	    
    $this->update_index();

    return $this->entry;
}


function save_entry($update_index=TRUE) {

	$filename=$this->set_filename();

	unset($this->entry['commnames']);
	unset($this->entry['commcount']);
	unset($this->entry['commcount_str']);
	unset($this->entry['tracknames']);
	unset($this->entry['trackcount']);
	unset($this->entry['trackcount_str']);
	unset($this->entry['filename']);

	makedir(dirname($filename));

	save_serialize($filename, $this->entry);

	add_log("Save entry '". $this->entry['title'] ."' (". $this->entry['code'] .")");

	$this->update_index();
	$this->write_entry_index();

	if ($update_index) {
		$this->write_date_index();
	}

	return TRUE;

}


function delete_entry() {

	debug("unlink: ". $this->set_filename());
	
	unlink($this->set_filename());

	unset ($this->date_index[$this->entry['code']]);
	unset ($this->cat_index[$this->entry['code']]);
	unset ($this->entry_index[$this->entry['code']]);

	$this->write_entry_index(TRUE);
	$this->write_date_index();

}



// -----------------
// private functions
// ------------------



// Convert a string, so that it only contains alphanumeric and a few others.
function safestring($name) {
	return ereg_replace("[^-a-zA-Z0-9_.]", "", $name);
}



// Read the date index.
function read_date_index() {
	global $pivot_path, $serialize;

	if (count($this->date_index)<2) {

		// load and sort the date_index
		$this->date_index = load_serialize($pivot_path."/db/ser-dates.php", TRUE, TRUE);

		$this->cat_index = load_serialize($pivot_path."/db/ser-cats.php", TRUE, TRUE);

		//add_log("Read date index (". count($this->date_index) .",". count($this->cat_index) .")");

		$this->updated=FALSE;
	}


}



// Check if the current index file is the right one. If not
// load it.
function check_current_index() {

	$entry_index_file = $this->make_entry_index_filename();
	if ($entry_index_file != $this->entry_index_filename) {
		// ergo. the current dir's index is not in memory..
		$this->write_entry_index();
		$this->read_entry_index($entry_index_file);
	}
}



// Read an entry index file.
function read_entry_index($filename) {
	global $pivot_path;

	$this->entry_index_filename = $filename;
	$this->entry_index = Array();


	if ( ($this->update_mode) && (file_exists($pivot_path.$filename)) ) {
		//debug("Read entry index: $pivot_path.$filename ");
		$this->entry_index = load_serialize($pivot_path.$filename, TRUE, TRUE);

	}
		
	$this->updated=FALSE;
}

// Write an entry index file.
function write_entry_index($force=FALSE) {

	$this->make_entry_index_filename();

	if ($this->global_reindex) {
		//debug("sort index");
		ksort($this->entry_index);
	}


	if ( ($this->entry_index_filename!="") && (($this->updated)||($force)) && ($this->allow_write==TRUE) ) {

		save_serialize($this->entry_index_filename, $this->entry_index);
		add_log("Save entry index (". count($this->entry_index) .",". basename($this->entry_index_filename) .")");
		$this->updated = FALSE;


	}

}

function write_date_index() {

	asort($this->date_index);

	add_log("Save date index (". count($this->date_index) .",". count($this->cat_index) .")");
	
	save_serialize("db/ser-dates.php", $this->date_index);
	save_serialize("db/ser-cats.php", $this->cat_index);

}


// Figure out the filename of the current entry-index file to write to
// based on the current $this->entry['code']
function make_entry_index_filename() {
	global $global_pref;
	
	$code=$this->entry['code'];
	$dircount=floor($code / $global_pref['entries_per_dir']);
	//debug("code en dirc: $code - $dircount");
	$dir=sprintf("db/%s-%05d/", $this->logname, ( $global_pref['entries_per_dir'] * $dircount) );
	$filename=sprintf("index-%s-%05d.php", $this->logname, ( $global_pref['entries_per_dir'] * $dircount) );

	return $dir.$filename;

}


function entry_from_indexline() {
	global $global_pref;

	$this->entry = $this->entry_index[$this->entry['code']];

	$this->entry['id'] = $this->logname."-".$this->entry['code'];
	$this->entry['filename']= $this->set_filename();


	
	if ($this->entry['title']=="") { $this->entry['title']="No title.."; }
	$this->entry['title']=stripslashes($this->entry['title']);
	if (strlen($this->entry['title'])>50) { 
		$this->entry['title_short']=substr($this->entry['title'],0,50).'...'; 
	} else {
		$this->entry['title_short']=$this->entry['title'];
	}	
	$this->entry['size']= (int) $this->entry['size'];
	$this->entry['commcount']= (int) $this->entry['commcount'];
	$this->entry['trackcount']= (int) $this->entry['trackcount'];
	
	if ($this->entry['size']>1024) {
		$this->entry['print_size']=sprintf("%01.1f Kb",  $this->entry['size']/1024.0);
	} else {
		$this->entry['print_size']=sprintf("%d B", $this->entry['size']);
	} 
	
	$this->entry['title_safe']=str_replace("http://", "", $this->entry['title']);	$this->entry['title_safe']=str_replace("'", "", $this->entry['title_safe']);
	$this->entry['title_safe']=str_replace('"', "", $this->entry['title_safe']);
	$this->entry['title_safe']=ereg_replace("[^a-zA-Z0-9 :/_.,]", "", $this->entry['title_safe']);
}


// Based on the $this->entry['code'], this function sets and
// returns the filename of this entry
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
		$filename=sprintf("%05d.php", $code );
		// set it and return it as well..
		$this->entry['filename']=$dir.$filename;
	}
	return $this->entry['filename'];
}


// given a dirname, this will index the entries in that directory
function index_entries($dirname) {

	
	if (is_dir("db/".$dirname)) {
		$d= dir("db/".$dirname);

		while ($filename=$d->read()) {
			if (strlen($filename)==9) {
				$filelist[] = $filename;
			}
		}
		

		foreach($filelist as $file) {
		
				$result = $this->read_entry_filename("db/".$dirname."/".$file, TRUE);
					
				if($result) {
					debug ("($file is ok: ".$this->entry['title']." - ".$this->entry['date'].")");
				} else {
					debug ("(<b>$file is NOT ok: </b>".$this->entry['title']." - ".$this->entry['date'].")");
				}
		}
		
				
		$d->close();
		$this->write_entry_index(TRUE);
	}
}




// read an entry from disk. If no filename is given, it will 
// use what's in $this->entry['filename'].
function read_entry_filename($filename, $updateindex=TRUE, $force=FALSE) {
	global $global_pref;

	if ($entry=load_serialize($filename, TRUE, $force)) {
		$this->entry = $entry;
		$this->update_index(FALSE);
		return TRUE;
	} else {
		//echo "Entry c";
		return FALSE;
	}
	

}


// after indexing or updating an entry, this is used to update the 
// entry-index file.
function update_index($update=TRUE) {

	$this->check_current_index();

	if (strlen($this->entry['title'])>1) {
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

	unset($commnames);
	if (isset($this->entry['comments'])) {
		
		foreach ($this->entry['comments'] as $comment) {
			if (block_type($comment['ip'])=="none") {
				$commnames[]=stripslashes($comment['name']);
			}
		}

		if (isset($commnames) && (count($commnames)>0)) {
			$this->entry['commnames']=implode(", ",array_unique ($commnames));
			$commcount=count($commnames);
		} else {
			$this->entry['commnames'] = "";
			$commcount = 0;
		}
	} else {
		unset ($this->entry['comments']);
		$commcount=0;
		$this->entry['commnames']="";
	}
	
	$this->entry['commcount']=$commcount;

	if ($commcount==0) { 
		$commcount_str=lang('comments_text','0');
		//$this->entry['commcount_str']= $commcount_str;
		$this->entry['commcount_str']=str_replace("%num%", lang('numbers',0), $commcount_str);
		$this->entry['commcount_str']=str_replace("%n%", '0', $commcount_str);
	} else if ($commcount==1) { 
		$commcount_str=lang('comments_text','1'); 
		$this->entry['commcount_str']=str_replace("%num%", lang('numbers',1), $commcount_str);
		$this->entry['commcount_str']=str_replace("%n%", '1', $commcount_str);
	} else {
		if (lang('numbers',$commcount)) {
			$num=lang('numbers',$commcount);
		} else {
			$num=$commcount;
		}
		$commcount_str=lang('comments_text','2'); 
		$this->entry['commcount_str']=str_replace("%num%", $num, $commcount_str);
		$this->entry['commcount_str']=str_replace("%n%", $commcount, $commcount_str);

	}
        
	unset($tracknames);
	if (isset($this->entry['trackbacks'])) {
		
		foreach ($this->entry['trackbacks'] as $trackback) {
                        $tracknames[]=stripslashes($trackback['name']);
		}

		if (isset($tracknames) && (count($tracknames)>0)) {
			$this->entry['tracknames']=implode(", ",array_unique ($tracknames));
			$trackcount=count($tracknames);
		} else {
			$this->entry['tracknames'] = "";
			$trackcount = 0;
		}
	} else {
		unset ($this->entry['trackbacks']);
		$trackcount=0;
		$this->entry['tracknames']="";
	}
	
	$this->entry['trackcount']=$trackcount;

	if ($trackcount==0) { 
		$trackcount_str=lang('trackbacks_text','0');
		//$this->entry['trackcount_str']= $trackcount_str;
		$this->entry['trackcount_str']=str_replace("%num%", lang('numbers',0), $trackcount_str);
		$this->entry['trackcount_str']=str_replace("%n%", '0', $trackcount_str);
	} else if ($trackcount==1) { 
		$trackcount_str=lang('trackbacks_text','1'); 
		$this->entry['trackcount_str']=str_replace("%num%", lang('numbers',1), $trackcount_str);
		$this->entry['trackcount_str']=str_replace("%n%", '1', $trackcount_str);
	} else {
		if (lang('numbers',$trackcount)) {
			$num=lang('numbers',$trackcount);
		} else {
			$num=$trackcount;
		}
		$trackcount_str=lang('trackbacks_text','2'); 
		$this->entry['trackcount_str']=str_replace("%num%", $num, $trackcount_str);
		$this->entry['trackcount_str']=str_replace("%n%", $trackcount, $trackcount_str);

	}


	//if ($this->entry['category']=="") {
	//	$this->entry['category'][]="default";
	//}

	if (!isset($this->entry['status'])) { $this->entry['status']='publish'; }


	$index_line = array(
		'code' => $this->entry['code'], 
		'date' => addslashes($this->entry['date']),
		'user' => $this->entry['user'],
		'title' => addslashes($title),
		'size' => $size,
		'commcount' => $this->entry['commcount'], 
		'cnames' => $this->entry['commnames'], 
		'trackcount' => $this->entry['trackcount'], 
		'tnames' => $this->entry['tracknames'], 
		'category' => $this->entry['category'], 
		'status' => $this->entry['status'] 
	);

	if  ($this->entry['code'] != "") {
		$this->entry_index[ $this->entry['code'] ]=$index_line;		
		$this->date_index[ $this->entry['code'] ]= $this->entry['date'];
		$this->cat_index[ $this->entry['code'] ]= implode('|',$this->entry['category']);

	}

	if ($update) {
		$this->updated=TRUE;
	}
	
}

// checks if two arrays have overlapping elements. mostly used to check 
// if a set of categories (in a subweblog) matches a set of categories in an entry.
function intersect($arr1, $arr2) {
	
	// if $arr2 is not an array
	if (is_string($arr2)) {
		return in_array($arr2, $arr1);
	}

	// if both are arrays
	return (count(@array_intersect($arr1, $arr2))>0) ? TRUE : FALSE;

}


// end of class db_access
}

?>
