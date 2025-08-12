<?php

	include_once("../init.php");

/*
* script for ACIF to import data from mysql (dumped from access and put in mysql) into notitia
*/

# GLOBALS
#############################################################################################

	$web_system                         = &get_web_system();
	$GLOBALS['db']                      = &$web_system->get_db();
	$GLOBALS['db_name']					= "acif";			# name of the database with the temp data
	$GLOBALS['dest_database']           = "mysource_acif";	# destination databas
	$GLOBALS['rec_to_cat_table']		= "xtra_web_extension_notitia_record_to_category"; 	# tables for adding records
	$GLOBALS['record_table']			= "xtra_web_extension_notitia_record";              # tables for adding records
	$GLOBALS['att_val_table']			= "xtra_web_extension_notitia_attribute_value";     # tables for adding records
	$GLOBALS['ftp_server']				= "beta.squiz.net"; # name of the ftp server
	$GLOBALS['ftp_user_name']           = "mmcintyre";      # ftp username
	$GLOBALS['ftp_user_pass']           = "hal-lev%";       #ftp password
	$GLOBALS['page_id']					= "26";             # page id that files are associated with
	#$GLOBALS['path']                    = $DATA_PATH . "/unrestricted/page/" . $GLOBALS['page_id']; 
	$GLOBALS['path']                    = "/home/mysource_testbed/mysource_acif/data/restricted/page/" . $GLOBALS['page_id'];
	$GLOBALS['file_path']               = "files";          # where the files are stored remotely

############################################################################################
	/* 
	* categories in the form:
	* catid => array(
	*				'name' => 'table_name_from_access_dump'
	*				'ids'  => row_of_element_in_access_dump => corresponding_attribute_id
	*		);
	*
	* some attribute ids are arrays, this means that they are foreign keys or file references
	*
	*/

	$GLOBALS['categories'] = array (
		1		=> array (		
						'name'	=> 'Projects',
						'ids'	=> array(
							0 => array(1, "f_key"), 1 => 2, 2 => array(3, "f_key"), 3 => 4, 4 => 5, 5 => array(6, "f_key"), 6 => 7, 7 => 8,
							8 => 9, 9 => 10, 10 => 11, 11 => 12, 12 => 13, 13 => 14, 14 => 15,
							15 => 16, 16 => 17, 17 => 18, 18 => 19, 19 => 20, 20 => 21, 21 => 22,
							22 => 23, 23 => array(24, "f_key"), 24 => 25, 25 => 26, 26 => 27, 27 => 28, 28 => 29,
							29 => 30, 30 => 31, 31 => 32, 32 => array(62, "file")
						),
				),

		2		=> array (
						'name' => 'Project___Subjects', # there are three underscores here
						'ids'  => array (
							0 => 32, 1=> 33, 2 => 34
						),
				),

		3		=> array (
						'name' => 'Committees',
						'ids'  => array (
							0 => 35, 1 => array(36, "f_key"), 2 => 37, 3 => 38, 4 => 39, 5 => 40,
							6 => 41, 7 => 42, 8 => 43, 9 => 44, 10 => 60
						),
				),

		4		=> array (
						'name' => 'Publication_types',
						'ids'  => array (
							0 => 45, 1 => 46, 2 => 47, 3 => 49
						)	
				),

		5		=> array (
						'name' => 'Project_Stages',
						'ids' => array (
							0 => 50, 1 => 51, 2 => 52, 3 => 53  
						)
				),
		
		6		=> array (
						'name' => 'Reference_Panels',
						'ids' => array (
							0 => 54, 1 => 55, 2 => 56, 3 => 57, 4 => 58, 5 => 59, 6 => 61  
						)
				)
	);

	# MAIN
	#######################

	purge_records();
	loop_categories();

	#######################

	/**
	* Purges the current records in notitia
	* delete returns the number of rows affected ()
	*/	
	function purge_records() {
		echo "Purging Records....<BR>";
		$sql = "DELETE FROM " . $GLOBALS['dest_database'] . "." . $GLOBALS['rec_to_cat_table'];
		if($GLOBALS['db']->delete($sql) === false) {
			echo "could not purge record from " . $GLOBALS['rec_to_cat_table'] . "<BR>";
		}
		$sql = "DELETE FROM " . $GLOBALS['dest_database'] . "." . $GLOBALS['record_table'];		
		if($GLOBALS['db']->delete($sql) === false) {
			echo "could not purge record from " . $GLOBALS['record_table'] . "<BR>";
		}
		$sql = "DELETE FROM " . $GLOBALS['dest_database'] . "." . $GLOBALS['att_val_table'];
		if($GLOBALS['db']->delete($sql) === false) {
			echo "could not purge record from " . $GLOBALS['att_val_table'] . "<BR>";
		}
	}

	/**
	* loops over categories as call make_attributes
	*/
	function loop_categories () {
		foreach ($GLOBALS['categories'] as $num => $info) {
			make_attributes($num, $info['name']);
		}
	}

	/**
	* gets data from exported to mysql
	* then creates an attributes for notitia
	*/
	function make_attributes($cat, $table_name) {
		echo "------ CREATING RECORDS FOR TABLE '".$table_name."' ------<BR>";
		
		$sql = "SELECT * FROM " . $GLOBALS['db_name'] . "." . $table_name;
		$result = $GLOBALS['db']->select($sql);

		while($row = mysql_fetch_array($result, MYSQL_BOTH)) {

			# firstly, create a new record
			
			$recordid = create_new_record($cat); 
			
			# now create some attrbutes
			
			foreach ($GLOBALS['categories'][$cat]['ids'] as $ref => $attributeid) {
				
				# check if this attribute is a foreign key
				if(is_array($attributeid)) {
					
					list($attributeid, $type) = $attributeid;
					if($type == "f_key") {
						$value = "|" . $row[$ref] . "|";
					} elseif ($type == "file") {
						$value = get_file($row[$ref]);
					}
				} else {
					$value = $row[$ref];
				}

				$sql = "INSERT INTO " . $GLOBALS['dest_database'] . "." . $GLOBALS['att_val_table'] . " values(" . $attributeid .", " . $recordid . ", '" . addslashes($value) ."')";
				
				if(!$GLOBALS['db']->insert($sql)) {
					echo "could not create record " . $recordid . "....exiting...<BR>";
					exit();
				}
			}
		}
		echo "--------- DONE!!!! -----------<BR>";
	}

	/**
	* Creates a new record and returns the recordid of that record
	*/
	function create_new_record($cat = 0) {
		# create new recordid
		
		$sql = "INSERT INTO " . $GLOBALS['dest_database'] . "." . $GLOBALS['record_table'] . " VALUES (0)";
		if(!$GLOBALS['db']->insert($sql)) {
			echo "could not create new recordid...exiting...<BR>";
			exit();
		}
		
		# get the recordid for this record
		
		$sql = "SELECT MAX(recordid) FROM " . $GLOBALS['dest_database'] . "." . $GLOBALS['record_table'];
		if(!$recordid =  $GLOBALS['db']->single_element($sql)) {
			echo "could not get the new recordid...exiting...<BR>";
			exit();
		}

		# create a record to category mapping

		$sql = "INSERT INTO " . $GLOBALS['dest_database'] . "." . $GLOBALS['rec_to_cat_table'] . " VALUES (" . $recordid .", " . $cat . ")";
		if(!$GLOBALS['db']->insert($sql)) {
			echo "could not map recordid to categoryid...exiting...<BR>";
			exit();
		}

		return $recordid;
	}

	/**
	*
	* Function to get a file via ftp and store it to the data directory
	* also creates an entry in the database so we can make an assocition
	* with frontitia
	*
	*/
	function get_file ($filename = '') {
		$ftp_ptr = ftp_connect($GLOBALS['ftp_server']);
		$login_result = ftp_login($ftp_ptr, $GLOBALS['ftp_user_name'], $GLOBALS['ftp_user_pass']); 

		if ((!$ftp_ptr) || (!$login_result)) { 
			echo "FTP connection has failed!<BR>";
			exit; 
		} else {
			echo "Connected to " . $GLOBALS['ftp_server'] . "<BR>";
		}
		# change to the directory of the files
		if(!ftp_chdir($ftp_ptr, $GLOBALS['file_path'])) {
			print "could not change directories<BR>";
		}
		if(!$fp = fopen($GLOBALS['path'] . "/". $filename, "w")) {
			print $GLOBALS['path'] . "/". $filename . "<BR>";
			print "could not create new file...exiting...<BR>";
			exit;
		}
		# get the remote file and add to the data directory
		if(!ftp_fget($ftp_ptr, $fp, $filename, FTP_BINARY)) {
			echo "could not get file " . $filename . "...exiting...<BR>";
			exit;
		}
		echo $filename . " added to file reference<BR>";
		# close the FTP stream 
		ftp_close($ftp_ptr);
		fclose($fp);

		# create a new file record in the database and get the fileid
		# so that we can make an association with notitia

		$orderno = 1 + $GLOBALS['db']->single_element("SELECT max(orderno) FROM " . $GLOBALS['dest_database'] . ".file WHERE pageid='" . $GLOBALS['page_id'] . "'");

		# make sure that there is not an entry for this file already

		$fileid = $GLOBALS['db']->single_element("SELECT fileid FROM " . $GLOBALS['dest_database'] . ".file WHERE pageid='" . $GLOBALS['page_id'] . "' AND filename='" . addslashes($filename) . "'");
		if($fileid) return $fileid;

		$fileid = $GLOBALS['db']->insert("INSERT INTO " . $GLOBALS['dest_database'] . ".file (pageid,filename,description,keywords,visible,log_hits,orderno) VALUES ('" . $GLOBALS['page_id'] . "','".addslashes($filename)."','','','N','0','" . $orderno . "')");

		return $fileid;

	}

?>