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


function read_index_from_disk() {
global $index;

	$index2=file("db/index.txt");
	
	// sift all dummy entries..
	for ($i=0; $i<count($index2); $i++) {
		//$line=chop($line);
		if (strlen($index2[$i])>10) {
			$index[]=$index2[$i];
		}
	}
	//natsort($index);
	$index = array_unique($index);

}


function write_index() {
global $index;

	$fp=fopen("db/index.txt", "w");
	foreach ($index as $value) {
		$value=trim($value)."\n";
		fwrite($fp, $value);	
//		$value=substr($value,0,40);
	}
	debug("writeindex");
//	fwrite($fp,"\r\n");
	fclose($fp);
     
}


function find_max_sub($code) {
global $index;

	$max=0;
	foreach ($index as $value) {
		list($dummy,$uid)=explode(",",$value);
		$uid= str_replace('"', '', $uid);
		$uid= (int) substr($uid, strlen($code)+1);
		if ($uid>$max) { $max=$uid; }
	}
    return $max;
}


function maxid() {
global $index;

	$max=0;
	foreach ($index as $value) {
		list($uid)=explode(",",$value);
		$uid= (int) str_replace('"', '', $uid);
		if ($uid>$max) { $max=$uid; }
	
	}
    return $max;
}

function sort_index_code_function ($a, $b) {
	
	list($dummy,$code_a)=explode(",",$a);
	list($dummy,$code_b)=explode(",",$b);
	
    if ($code_a == $code_b) return 0;
    return ($code_a > $code_b) ? -1 : 1;

}

function sort_index_date_function ($a, $b) {
	
	$code_a=explode(",",$a);
	$code_b=explode(",",$b);
	//echo "-- vgl.: $code_a - $code_b --";
	
    if ($code_a[2] == $code_b[2]) return 0;
    return ($code_a[2] > $code_b[2]) ? -1 : 1;


}


function sort_index_by_date($order='desc') {
global $index;
	usort ($index, "sort_index_date_function");

	if ($order=='asc') {
		$index=array_reverse($index);
	}

}

function sort_index_by_code() {
global $index;
	usort ($index, "sort_index_code_function");

}

function get_entry_by_id($id) {
global $index_count, $entry;

	$index_count=0;
	do {
		$ok=get_entry();
		if ($entry['id']==$id) { 
			return true;
		}
	} while ($ok);
	return false;
}

function exist_code($code) {
global $index;
	$code='"'.add_code_padding($code).'"';
	foreach ($index as $value) {
		list($uid, $ind_code)=explode(",",$value);
		if ($ind_code==$code) {
			return true;
		} 
	}
	return false;
}


function get_entry_by_code($code) {
global $index_count, $entry;

	$index_count=0;
	do {
		$ok=get_entry();
		echo ".";
		if ($entry['code']==$code) { 
			return true;
		}
	} while ($ok);
	return false;
}


function get_entry() {
global $index_count, $index, $entry, $global_pref;

	if (!($index_count)) {
	 $index_count=0;
	}


	if (!isset($index[$index_count])) {
		debug("Bestaat niet: ".$index_count);
		piv_error("File does not exist!", "You are trying to generate a page that does not exist according to the index. <br />If you've just recreated the index, <a href='javascript:self.close();'>close</a> this screen and check the 'newest entries' or 'all entries'. Otherwise you could try recreating the index-file. Click <a href='reindex.php'>reindex</a> to do so now, and then go <a href='javascript:history.go(-1)'>back</a> to the last page.", 0);
	}
	$index[$index_count]=trim($index[$index_count]);
	//strip leading and tailing "-s


	// split the thing..
	list($entry['id'], $entry['code'], $entry['date'], $entry['user'], 
		$entry['title'], $entry['file'], $entry['template'], 
		$entry['size'], $entry['comments'], $entry['cnames'] )=
		split('","',$index[$index_count]);

	$entry['id'] = substr($entry['id'],1);

	$entry['cnames'] = substr($entry['cnames'],0,-1);
	$entry['cnames']=explode("|",$entry['cnames']);
	$entry['cnames']=array_unique($entry['cnames']);

	if ($entry['title']=="") { $entry['title']="No title.."; }
	$entry['title']=stripslashes($entry['title']);
	if (strlen($entry['title'])>$global_pref['max_chars']) { 
		$entry['title_short']=substr($entry['title'],0,50).'...'; 
	} else {
		$entry['title_short']=$entry['title'];
	}	
	$entry['size']= (int) $entry['size'];
	$entry['comments']= (int) $entry['comments'];
	
	if ($entry['size']>1024) {
		$entry['print_size']=sprintf("%01.1f Kb",  $entry['size']/1024.0);
	} else {
		$entry['print_size']=sprintf("%d B", $entry['size']);
	} 
	
	//$entry['print_size'].=sprintf(", %s words", count(preg_split(/\w\b/,$text))-1));

	$entry['title_safe']=str_replace("'", "", $entry['title']);
	$entry['title_safe']=str_replace('"', "", $entry['title_safe']);
	// this one doesn't work yet..
	$entry['title_safe']=ereg_replace("[^a-zA-Z0-9 _.,]", "", $entry['title_safe']);
	
	if ($index_count<count($index)) {
		$index_count++;
		return true;
	} else {
		return false;
	}	
}


// maak xml van $row..

function add_to_index($line) {
global $index, $skipcheck;

	// first, get the current uid
	list($current)=explode(",",$line);
	$current= (int) str_replace('"', '', $current);
	
	$success=0;

	// then, search if it exists..
	if ($skipcheck==0) {
		for ($i=0;$i<count($index);$i++) {
			list($id)=explode(",",$index[$i]);
			$id= (int) str_replace('"', '', $id);
		
			if ($id==$current) { 
				$index[$i]=$line."\n";
				$success=1;
			}
		}
		debug("niet skipcheck");
	}
	
	if ($success<>1) { $index[]="\n".$line; }
}

function make_entry_indexline($write=1) {
global $row;
		if (strlen($row['title'])>2) {
			$title=$row['title'];
		} else {
			$title=substr($row['introduction'],0,300);
			$title=strip_tags($title);
			$title=str_replace("\n","",$title);
			$title=str_replace("\r","",$title);
			$title=substr($title,0,60);

		}
		$size=strlen($row['introduction'])+strlen($row['body']);
		$filename= str_replace("blog-", "entry", add_code_padding($row['code']).".php");		
		$index_line=sprintf('"%d","%s","%s","%s","%s"', $row['id'], add_code_padding($row['code']), addslashes($row['date']), addslashes($row['user']), addslashes($title));
		$index_line.=sprintf(',"%s"', $filename);
		$index_line.=sprintf(',"%s"', $row['template']);
		$index_line.=sprintf(',"%s"', $size);

		if (isset($row['comments'])) {
			for ($i=0;$i<count($row['comments']);$i++) {
				$comment_names[]=$row['comments'][$i]['name'];
			}
		}

		if (isset($comment_names)) {
			$comment_names=implode("|",$comment_names);
		}

		if (isset($row['comments'])) {
			$index_line.=sprintf(',"%s","%s"', count($row['comments']), $comment_names);
		} else {
			$index_line.=sprintf(',"0",""');
		}
		
		//debug($index_line);

		add_to_index($index_line);		

		if($write==1){
			write_index();
			//debug("write index");
		}
}

function get_entry_xml($entry_file) {
global $row, $global_pref;

//	debug("get_entry_xml: file: $entry_file");

	$entry_file="db/".$entry_file;

	$xml = new XMLFile();
	$fh = fopen( $entry_file, 'r' );
	if ($fh==false) {
		piv_error("Index File corrupt!", "One of the xml files needed could not be found. <br /><br />This is can normally be fixed by recreating the index-file. Click <a href='reindex.php'>reindex</a> to do so now, and then go <a href='javascript:history.go(-1)'>back</a> to the last page.", 0);

	}
	$xml->read_file_handle( $fh );
	fclose( $fh );
	
	$row_entrypoint= &$xml->roottag->tags[0];
	$row['code'] = rem_code_padding($row_entrypoint->attributes['CODE']);
	$row['id'] = $row_entrypoint->attributes['UID'];
	$row['template']= $row_entrypoint->tags[0]->cdata;
	$row['date']= $row_entrypoint->tags[1]->cdata;
	$row['user']= $row_entrypoint->tags[2]->cdata;
	$row['title']= rem_entities($row_entrypoint->tags[3]->cdata);
	$row['subtitle']= rem_entities($row_entrypoint->tags[4]->cdata);
	$row['introduction']= rem_entities($row_entrypoint->tags[5]->cdata);
	$row['body']= rem_entities($row_entrypoint->tags[6]->cdata);
	$row['media']= $row_entrypoint->tags[7]->cdata;
	$row['links']= $row_entrypoint->tags[8]->cdata;
	$row['url']=$global_pref['pivot_url'].str_replace("%1",$row['code'],$global_pref['file_format']);
	$row['xmlfilename']=$entry_file;

	$commcount=0;
	unset($row['comments']);

	//debug("user: ".$row['user']);
	
	while ((isset($row_entrypoint->tags[(9+$commcount)])) && ($row_entrypoint->tags[(9+$commcount)]->tags[0]->cdata<>"")) {
		$row['comments'][$commcount]['name']= rem_entities($row_entrypoint->tags[(9+$commcount)]->tags[0]->cdata);
		$row['comments'][$commcount]['email']= rem_entities($row_entrypoint->tags[(9+$commcount)]->tags[1]->cdata);
		$row['comments'][$commcount]['url']= $row_entrypoint->tags[(9+$commcount)]->tags[2]->cdata;
		$row['comments'][$commcount]['ip']= $row_entrypoint->tags[(9+$commcount)]->tags[3]->cdata;
		$row['comments'][$commcount]['date']= $row_entrypoint->tags[(9+$commcount)]->tags[4]->cdata;
		$row['comments'][$commcount]['comment']= rem_entities($row_entrypoint->tags[(9+$commcount)]->tags[5]->cdata);
		$commcount++;

	}

	$xml->cleanup();

}


function get_entry_xml_by_id($id){
global $index, $entry;
	get_entry_by_id($id);
	get_entry_xml($entry['file']);

}


function get_entry_xml_by_code($code){
global $index, $entry;

	get_entry_by_code($code);
	get_entry_xml($entry['file']);
}


function make_entry_xml() {
	global $row, $xml, $comment_arr, $count;
	
	$xml = new XMLFile();
	$xml->create_root(); # necessary -- no root is created until requested
	$xml->roottag->name = 'PIVOTXML';
	$xml->roottag->add_subtag( 'ENTRY', array() );
	$entrytag= &$xml->roottag->curtag;
	$entrytag->add_attribute( 'UID', $row['id']);
	$entrytag->add_attribute( 'CODE', $row['code']);
	
	$entrytag->add_subtag( 'TEMPLATE', array() );
	$entrytag->curtag->cdata=$row['template'];
	
	$entrytag->add_subtag( 'DATE', array() );
	$entrytag->curtag->cdata=$row['date'];
	
	$entrytag->add_subtag( 'USER', array() );
	$entrytag->curtag->cdata=$row['user'];
	
	$entrytag->add_subtag( 'TITLE', array() );
	$entrytag->curtag->cdata=html_entities($row['title']);
	
	$entrytag->add_subtag( 'SUBTITLE', array() );
	$entrytag->curtag->cdata=html_entities($row['subtitle']);
	
	$entrytag->add_subtag( 'INTRODUCTION', array() );
	$entrytag->curtag->cdata=html_entities($row['introduction'], 1);
	
	$entrytag->add_subtag( 'BODY', array() );
	$entrytag->curtag->cdata=html_entities($row['body']);
	
	$entrytag->add_subtag( 'MEDIA', array() );
	$entrytag->curtag->cdata=$row['media'];
	
	$entrytag->add_subtag( 'LINKS', array() );

	if (count($row['comments'])>0) {
		foreach ($row['comments'] as $comment) {
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

	$filename= str_replace("blog-", "entry", add_code_padding($row['code']).".php");		

	$fh = fopen( "db/".$filename, 'w' );
	$xml->write_file_handle( $fh );
	fclose( $fh );
	$xml->cleanup();
	debug("writexml - $filename");
     
}


function writexml() {
		make_entry_indexline();
		make_entry_xml();

}


function get_row($file) {

	//nutting

}


// read_index_from_disk();



if (!isset($XMLFile_Included) || !$XMLFile_Included) {
$XMLFile_Included = 1;

###############################################################################
class XMLTag
{
    var $cdata;
    var $attributes;
    var $name;
    var $tags;
    var $parent;

    var $curtag;

//    function XMLTag($parent=0)
    function XMLTag(&$parent)
    {
//				global $this;
        if (is_object( $parent ))
        {
            $this->parent = &$parent;
        }
        $this->_init();
    }

    function _init()
    {
        $this->attributes = array();
        $this->cdata = '';
        $this->name = '';
        $this->tags = array();
    }

    function add_subtag($name, $attributes=0)
    {
//        $tag = new XMLTag( &$this );
        $tag = new XMLTag( $this );
        $tag->set_name( $name );
        if (is_array($attributes)) {
            $tag->set_attributes( $attributes );
        }
        $this->tags[] = &$tag;
        $this->curtag = &$tag;
    }

    function find_subtags_by_name( $name )
    {
        $result = array();
        $found=false;
        for($i=0;$i<$this->num_subtags();$i++) {
            if(strtoupper($this->tags[$i]->name)==strtoupper($name)) {
                $found=true;
                $array2return[]=&$this->tags[$i];
            }
        }
        if($found) {
            return $array2return;
        }
        else {
            return false;
        }
    }

    function clear_subtags()
    {
        # Traverse the structure, removing the parent pointers
        $numtags = sizeof($this->tags);
        $keys = array_keys( $this->tags );
        foreach( $keys as $k ) {
            $this->tags[$k]->clear_subtags();
            unset($this->tags[$k]->parent);
        }

        # Clear the tags array
        $this->tags = array();
        unset( $this->curtag );
    }


    function remove_subtag($index)
    {
        if (is_object($this->tags[$index])) {
            unset($this->tags[$index]->parent);
            unset($this->tags[$index]);
        }
    }

	function make_array() {
		if (isset($result)) { unset($result); }
		$result=Array();
		$count=0;
		for($i=0;$i<$this->num_subtags();$i++) {
			$sub=count($this->tags[$i]->tags);

			$name=strtolower($this->tags[$i]->name);
			if ($sub>0) { 
				$result[$name][]=$this->tags[$i]->make_array();
			} else {
				$result[$name]=$this->tags[$i]->cdata;
			}
		}
		return $result;
	}

    function num_subtags()
    {
        return sizeof( $this->tags );
    }

    function add_attribute( $name, $val )
    {
        $this->attributes[strtoupper($name)] = $val;
    }

    function clear_attributes()
    {
        $this->attributes = array();
    }

    function set_name( $name )
    {
        $this->name = strtoupper($name);
    }

    function set_attributes( $attributes )
    {
        $this->attributes = (is_array($attributes)) ? $attributes : array();
    }

    function add_cdata( $data )
    {
        $this->cdata .= $data;
    }

    function clear_cdata()
    {
        $this->cdata = "";
    }

    function write_file_handle( $fh, $prepend_str='' )
    {
		
        # Get the attribute string
        $attrs = array();
        $attr_str = '';
        foreach( $this->attributes as $key => $val )
        {
            $attrs[] = strtoupper($key) . "=\"$val\"";
        }
        if ($attrs) {
            $attr_str = join( " ", $attrs );
        }
        # Write out the start element
        $tagstr = "$prepend_str<{$this->name}";
        if ($attr_str) {
            $tagstr .= " $attr_str";
        }

        $keys = array_keys( $this->tags );
        $numtags = sizeof( $keys );
        # If there are subtags and no data (only whitespace), 
        # then go ahead and add a carriage
        # return.  Otherwise the tag should be of this form:
        # <tag>val</tag>
# If there are no subtags and no data, then the tag should be
        # closed: <tag attrib="val"/>
$trimmeddata = trim( $this->cdata );
        if ($numtags && ($trimmeddata == "")) {
            $tagstr .= ">\n";
        }
        elseif (!$numtags && ($trimmeddata == "")) {
            $tagstr .= "/>\n";
        }
        else {
            $tagstr .= ">";
        }

        fwrite( $fh, $tagstr );

        # Write out the data if it is not purely whitespace
        if ($trimmeddata != "") {
            fwrite( $fh, $trimmeddata );
        }

        # Write out each subtag
        foreach( $keys as $k ) {
            $this->tags[$k]->write_file_handle( $fh, "$prepend_str\t" );
        }

        # Write out the end element if necessary
        if ($numtags || ($trimmeddata != "")) {
            $tagstr = "</{$this->name}>\n";
            if ($numtags) {
                $tagstr = "$prepend_str$tagstr";
            }
            fwrite( $fh, $tagstr );
        }
    }

}


###############################################################################
class XMLFile
{
    var $parser;
    var $roottag;
    var $curtag;

    function XMLFile()
    {
        $this->init();
    }

    # Until there is a suitable destructor mechanism, this needs to be
    # called when the file is no longer needed.  This calls the clear_subtags
    # method of the root node, which eliminates all circular references
    # in the xml tree.
    function cleanup()
    {
        if (is_object( $this->roottag )) {
            $this->roottag->clear_subtags();
        }
    }

    function init()
    {
        $this->roottag = "";
        $this->curtag = &$this->roottag;
    }

    function create_root()
    {
        $this->roottag = new XMLTag($dummy);
        $this->curtag = &$this->roottag;
    }

    # read_xml_string
    # Same as read_file_handle, but you pass it a string.  Note that
    # depending on the size of the XML, this could be rather memory intensive.
    # Contributed July 06, 2001 by Kevin Howe
    function read_xml_string( $str )
    {
        $this->init();
        $this->parser = xml_parser_create("UTF-8");
//        xml_set_object( $this->parser, &$this );
        xml_set_object( $this->parser, $this );
        xml_set_element_handler( $this->parser, "_tag_open", "_tag_close" );
        xml_set_character_data_handler( $this->parser, "_cdata" );
        xml_parse( $this->parser, $str );
        xml_parser_free( $this->parser );
    }

    function read_file_handle( $fh )
    {
        $this->init();
        $this->parser = xml_parser_create("UTF-8");
//        xml_set_object( $this->parser, &$this );
        xml_set_object( $this->parser, $this );
        xml_set_element_handler( $this->parser, "_tag_open", "_tag_close" );
        xml_set_character_data_handler( $this->parser, "_cdata" );

        while( $data = fread( $fh, 4096 )) {
            if (!xml_parse( $this->parser, $data, feof( $fh ) )) {
                return FALSE;
								// die(sprintf("XML error: %s at line %d",  xml_error_string(xml_get_error_code($this->parser)),  xml_get_current_line_number($this->parser)));
            }
        }

        xml_parser_free( $this->parser );

				return TRUE;
    }

		// Modified by me: It now includes the ent.dtd.
		// This is so i can use &eacute; and the like..
    function write_file_handle( $fh, $write_header=1 )
    {
        if ($write_header) {
            fwrite( $fh, "<?xml version='1.0' encoding='UTF-8'?>\n" );
            fwrite( $fh, "<!DOCTYPE pivotxml SYSTEM \"ent.dtd\">\n" );
        }

        # Start at the root and write out all of the tags
        $this->roottag->write_file_handle( $fh );
    }

    ###### UTIL #######

    function _tag_open( $parser, $tag, $attributes )
    {
        #print "tag_open: $parser, $tag, $attributes\n";
        # If the current tag is not set, then we are at the root
        if (!is_object($this->curtag)) {
            $this->curtag = new XMLTag($dummy);
            $this->curtag->set_name( $tag );
            $this->curtag->set_attributes( $attributes );
        }
        else { # otherwise, add it to the tag list and move curtag
            $this->curtag->add_subtag( $tag, $attributes );
            $this->curtag = &$this->curtag->curtag;
        }
    }

    function _tag_close( $parser, $tag )
    {
        # Move the current pointer up a level
        $this->curtag = &$this->curtag->parent;
    }

    function _cdata( $parser, $data )
    {
        $this->curtag->add_cdata( $data );
    }
}
###############################################################################
} // included
###############################################################################


?>
