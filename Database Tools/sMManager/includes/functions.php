<?php
/*
functions.php
Author : Thomas Whitecotton
Email  : admin@ciamosbase.com
Website: http://www.ciamosbase.com
*/

function retrieve_var($getVar='op') {
	global $HTTP_POST_VARS, $HTTP_GET_VARS, $_REQUEST;
	$gotVar = $HTTP_GET_VARS[$getVar] ? $_REQUEST[$getVar] : $HTTP_POST_VARS[$getVar];
	return $gotVar;	
}

function defaultMessage() {
	$content = "SQL Command Center
	<form method='post' action='index.php'>
	<textarea name='sqlcommand' cols='40' rows='15'></textarea><br />
	Show Result <input type='checkbox' name='showresult'><br />
	<input type='hidden' name='op' value='EXECUTE'>
	<input type='submit' name='submit' value='Execute'>
	</form>";
	return($content);
}

function getDirectoryFile($path)
{
    $path_id = opendir($path);
    while ( $file_name = readdir($path_id) ) {
        if ( $file_name == "." || $file_name == ".." ) {
			continue;
		} else
		if (substr($file_name, -4) == ".xml") {
            $file_type = filetype($path . "/" . $file_name);
            $found[$path][$file_name] = $file_type;
        } else
		if(is_dir($path.'/'.$file_name)) {
            $file_type = filetype($path . "/" . $file_name);
            $found[$path][$file_name] = $file_type;
            if ( $file_type == "dir" ) {
                $file_array = getDirectoryFile($path.'/'.$file_name);
                $found = array_merge((array)$found, (array)$file_array);
            }
		}
    }
    closedir($path_id);
    if ( !isset($found) ) {
        $found = array();
    }
    return $found;
}

function viewExports($path='export') {
	$content = '';
	$found = getDirectoryFile($path);
	reset($found);
	while ( list($d, $dv) = each($found) ) {
		if ( is_array($dv) ) {
			while ( list($f, $fv) = each($dv) ) {
				if ( $fv == "file" ) {
					$content .= '<a href="'.$d.'/'.$f.'">'.$d.'/'.$f.'</a><br />';
				}
			}
		}
	}
	if(empty($content)) $content = 'No files were found to be viewed.';
	return($content);
}

function deleteExports($path='export') {
	$unlinked = $rmdired = 0;
	$folders = array();
	$found = getDirectoryFile($path);
	reset($found);
	while ( list($d, $dv) = each($found) ) {
		if ( is_array($dv) ) {
			while ( list($f, $fv) = each($dv) ) {
				if ( $fv == "file" ) {
					unlink( $d.'/'.$f );
					$unlinked++;
				} else 
				if ( $fv == "dir" ) {
					$folders[] = $d.'/'.$f;
				}
			}
		}
	}
	foreach($folders as $folder) {
		if($path!=$folder) {
			rmdir($folder);
			$rmdired++;
		}
	}
	if($unlinked==0) {
		$content = "No files were found to be removed.<br />";
	} else {
		$content = "Successfully removed ".$unlinked." file(s).<br />";
	}
	if($rmdired==0) {
		$content .= "No folders were found to be removed.<br />";
	} else {
		$content .= "Successfully removed ".$unlinked." folder(s).<br />";
	}
	return($content);
}

function executeSQL() {
	global $layout;

	$sql = retrieve_var('sqlcommand');
	$showresult = retrieve_var('showresult');
	$sql = str_replace('drop','OPTIMIZE',$sql);

	$query = mysql_query($sql) or die($layout->dieMessage($sql,mysql_error()));
	$content = 'Executed the command:<br />'.$sql.'<br /><font color="green">Successfully</font><br /><hr>';

	if($showresult) {
		while($result = mysql_fetch_row($query)) {
			$content .= 'Result(s): <br /><br/>';
			if(is_array($result)) {
				foreach($result as $key=>$results) {
					if(!empty($results)) $content .= "Key: ".$key."<br />Value: ".$results."<br /><br/>";
				}
			} else {
				$content .= $result;
			}
		}
	}
	return($content);
}

function exportStep1($ie='EXPORT') {
	$result = mysql_query("SHOW TABLES");

	$content = "
	<form method='post' action='index.php?op=".$ie."&step=2'>
	<select size='1' name='table'>";
		
	while( $row = mysql_fetch_row( $result ) ) {
		$content .= "<option>".$row[0]."</option>";
	}
	$content .= "</select>
	<input type='submit' value='submit' name='submit'>
	</form>";
	return($content);
}

function exportStep2() {
	$table = retrieve_var('table');
	$result = mysql_query("SHOW COLUMNS FROM ".$table);

	$content = "
	<form method='post' action='index.php?op=EXPORT&step=3'>
	<select size='5' name='fields[]' multiple='yes'>";
		
	while( $row = mysql_fetch_row( $result ) ) {
		if(strtoupper($row[0]) != "ID") {
			$content .= "<option name='".$row[0]."'>".$row[0]."</option>";
		}
	}
	$content .= "</select>
	<input type='hidden' value='".$table."' name='table'>
	<input type='submit' value='submit' name='submit'>
	</form>";
	return($content);
}

function exportStep3() {
	$items = retrieve_var('fields');
	$table = retrieve_var('table');

	include("xmlexport.php");
	$XMLExport = new XMLExport();

	$query = "SELECT ";
	foreach($items as $item) {
		$query .= $item.", ";
	}
	$query = substr_replace($query,"",-2);
	$query .= " FROM ".$table;
	$sql = mysql_query($query);
	$i=0;

	$total = mysql_num_rows($sql);
	$values = array();

	while($i<$total) {
		$values[$i] = mysql_fetch_array($sql);
		$values[$i] = $XMLExport->array_extract($values[$i],2);
		$i++;
	}

	$filename = retrieve_var('filename');
	if(empty($filename) || $filename=='') {
		// Filename, if not specified
		$dir = "export/".$table;
		@mkdir($dir, 0777);
		$now = date("Y-m-d_H:i:s");
		$filename = $dir."/".$now.".xml";
	}

	// Updating
	//open same file and use "w" to clear file
	$f = @ fopen($filename,"w");

	// Check and make sure we opened the file right
	if($f!=false) {
		$XMLExport->set_db_info($table,$items);
		$XMLExport->startFeed();
		$x = 0;
		foreach($values as $entry) {
			$XMLExport->addItem($entry);
			$x++;
		}

		$XMLExport->closeFeed();
		$toFile = $XMLExport->returnFeed();
		$write = @ fputs($f,$toFile);
		// Make sure we were able to write to the file
		if($write==false) {
			$content = "Could not write to file ".$filename;
		}

		// Close the file
		$closed = fclose($f);
		
		if($closed==true) {
			$content = "Export procedure was successful. Click <a href='".$filename."'>here</a> to access the file.";
		}
	} else {
		// Tell the user we couldn't open the file
		$content = "Could not open file ".$filename;
	}
	return($content);
}

function getDBResult($sql) {
	global $layout;

	$query = @mysql_query($sql) or die($layout->dieMessage($sql,mysql_error()));
	return($query);
}

function viewDatabase() {
	global $dbtable;

	$table = retrieve_var('table');
	$field = retrieve_var('field');
	$show = retrieve_var('show');
	$showor = retrieve_var('showor');

	if(!isset($table) || empty($table)) {
		// Show all tables
		$sql = "SHOW TABLES";
		$title = $dbtable;
		$subtitle = "Tables";
		$link = "index.php?op=VIEW&table=";
		$check = 0;
		$error = "No tables were found in the database.";

		$result = getDBResult($sql);

		$content = $title."<br /><hr>".$subtitle."<ul>";
		
		$x=$check;
		while( $row = mysql_fetch_row( $result ) ) {
			if(!empty($row[0])) {
				$content .= "<li><a href='".$link.$row[0]."'>".$row[0]."</a> | <a href='index.php?op=EXPORT&step=2&table=".$row[0]."'>EXPORT</a></li>";
				$x++;
			}
		}
		if($x==$check) $content .= $error;

		$content .= "</ul>";
	} else 
	if(!isset($field) || empty($field)) {
		// Show the table fields
		$sql = "SHOW FIELDS from ".$table;
		$title = "<a href='index.php?op=VIEW'>".$dbtable."</a> -> ".$table;
		$subtitle = "Fields";
		$link = "index.php?op=VIEW&table=$table&field=";
		$check = 0;
		$error = "No fields were found for the table '".$table."'";

		$result = getDBResult($sql);

		$content = $title."<br /><hr>".$subtitle."<ul>";
		
		$x=$check;
		while( $row = mysql_fetch_row( $result ) ) {
			if(!empty($row[0])) {
				$content .= "<li><a href='".$link.$row[0]."'>".$row[0]."</a></li>";
				$x++;
			}
		}
		if($x==$check) $content .= $error;

		$content .= "</ul>";
	} else 
	if(!isset($show) || empty($show)) {
		// Show the field entries
		$result = getDBResult("SELECT ".$field." from ".$table);

		$content = "<a href='index.php?op=VIEW'>".$dbtable."</a> -> <a href='index.php?op=VIEW&table=".$table."'>".$table."</a> -> ".$field."<br /><hr>Entries<ul>";
		$x=1;
		while( $row = mysql_fetch_row( $result ) ) {
			if(!empty($row[0])) {
				$content .= "<li><a href='index.php?op=VIEW&table=".$table."&field=".$field."&show=".$row[0]."&showor=".$x."'>".$row[0]."</a></li>";
				$x++;
			}
		}
		if($x==1) $content .= "No entries were found for the field '".$field."' in table '".$table."'";

		$content .= "</ul>";
	} else {
		// Show the entry
		$result = getDBResult("SHOW FIELDS FROM ".$table);

		while( $row = mysql_fetch_row( $result ) ) {
			$fields[] = $row[0];
		}	
		
		$sql = "SELECT * FROM ".$table." WHERE ".$field."='".htmlspecialchars($show)."'";
		$result = mysql_query($sql);

		$row = mysql_fetch_row( $result );

		$content = "<table>";
		
		if(!isset($row[0])) {
			$result = getDBResult("SELECT * FROM ".$table." ORDER BY ".$field." ASC");

			$x=1;
			while( $row = mysql_fetch_row( $result ) ) {
				if($x==$showor) {
					$found = $row; 
					break; 
				}
				$x++;
			}

			$row = $found;
		}
		
		$x=0;
		foreach($row as $show) {
			$content .= "<tr>
			<td>".$fields[$x]."</td>
			<td>".$show."</td>
			</tr>";
			$x++;
		}

		$content .= "</table>";
	}
	return($content);
}
?>