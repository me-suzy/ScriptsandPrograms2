<?php

set_time_limit(0);

// --- convert: reading file, parse it --- //
function convert($filename) {
    global $db;
    global $counter;
    $pos = strpos($filename, ".stor");
    if ($pos > 0) {
        $code = (int) substr(basename($filename),0,$pos);
        $tb = implode( "", file($filename));
        $tb_arr = unserialize($tb);
        $count = (int)count($tb_arr);
        if ($count > 0) {
            $entry = $db->read_entry($code);
            echo "$count trackbacks in $filename<br>";
            foreach($tb_arr as $val) {
                    $entry['trackbacks'][] = array(
                            'name' => $val["blog_name"],
                            'title' => $val["title"],
                            'url' => $val["url"],
                            'date' => date("Y-m-d-H-i",$val["timestamp"]),
                            'excerpt' => $val["excerpt"]
                            );
                    // print_r($entry);
            }
            
            if (insert_entry($entry)) {
                // Entry has been inserted, so we delete it..
                if (isset($_POST['unlink'])) {
                    unlink($filename);
                }
            }
        } else {
            echo "No trackbacks in $filename<br>";
            unlink($filename);
        }
    }
}



// --- insert_entry: Puts the function into the 1.0 db--- //
function insert_entry($entry) {
	global $db;
	
	$entry = $db->set_entry($entry);
	if ($db->save_entry()) {
		echo "(inserted!)<br />";
		return TRUE;
	} else {
		echo "(<b>NOT</b> inserted!)<br />";
		return FALSE;
	}

	flush();
	
	
}

// -------
function start_conversion($dir) {
	global $db;
	
	// open the db, make sure it's updated..
	$db = new db();
	$db->generate_index();
	
	$dir= realpath($dir);
	
	$d= dir($dir);
	
	while ($filename=$d->read()) {
		
		if ( ($filename=="..") || ($filename==".") ) { continue; }
		
		convert($dir."/".$filename);
					
		flush();
		
	}
	
	$d->close();
	
	echo "<br /><br />\n\n<b>conversion completed in ".timetaken()." seconds.</b><br /><br />\n";
	echo "<a href='index.php'>Log in to Pivot to regenerate pages...</a>.</div>";
	
}


function show_form() {
	
	$self = $_SERVER['PHP_SELF'];
	$dir = isset($_POST['dir']) ? $_POST['dir'] : "" ;
	
	echo "The current path is: ".dirname($self)."<br /><br />";
	
	echo "Please give the location of your old trackback data folder. This is the folder with all the files that are named 1.stor, 2.stor, etcetera. <br />Depending on where your previous install is located, this can look something like 'db/trackback/'.<br /><br />";
	echo "<b>Location:</b> <form method='post' action='$self'><input type='text' name='dir' size='30' value='$dir' /><br /><br />";
	
	echo "<input type='checkbox' name='unlink' value='1' /> <b>Remove old trackbacks after conversion?</b><br /><small>If your server runs in safe_mode and you can't convert all trackbacks in one go, use this option. This way the
converted old trackbacks will be removed. And you can restart the conversion to continue where it stopped.</small><br /><br />";
	
	
	echo "<input type='checkbox' name='confirm' value='1' /> <b>Yes, do it!</b> <br /><br />";
	echo "<input type='submit' value='Convert!' /></form>";
	
}


// -------- Main ----------

?>

<h1>Welcome to the quick-and-dirty conversion script</h1>
<h3>Use this tool to convert old trackback data (pre 1.25) to new format.</h3>

<?php

if (count($_POST)>0) {
	
	// we know there's input..
	
	if (strlen($_POST['dir'])<3) {
		
		// no dir given
		echo "<b>You need to give the location of your old Pivot's db/ folder..</b><br /><br />";
		show_form();
		
	} else if (!is_dir(realpath($_POST['dir']))) {
		
		// not a dir..
		echo "<b>The dir you've given translates to: <br />";
		echo realpath($_POST['dir'])."<br />";
		echo "This folder does not exist.. Please verify your input.</b><br /><br />";
		show_form();
		
	} else if (!isset($_POST['confirm'])) {
		
		// not confirmed..
		echo "<b>You need to confirm that you want to start the conversion..</b><br /><br />";
		show_form();
		
	} else {
		
		// go!
		
		include_once("pv_core.php");
		include_once("modules/old_module_xml.php");
		include_once("modules/old_xmlfile.php");
		
		start_conversion($_POST['dir']);
		
	}
	
	
	
} else {
	
	show_form();
	
}


?>

</BODY>
</HTML>
