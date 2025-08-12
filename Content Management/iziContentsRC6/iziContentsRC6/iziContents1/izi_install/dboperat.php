<?php

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

$GLOBALS["dbConn"] = &ADONewConnection(strtolower($_POST["DBType"])); 
 

//########   Versioning check
function getVersion($DBPrefix){
	// checking if settings does exist
	$sqlcheck= "SHOW TABLES LIKE '%settings'";
	$checkresult = dbRetrieve($sqlcheck,true,0,0);
	if($rsck = dbFetch($checkresult)){
		// getting version
		$sqlQuery = "SELECT settingvalue FROM ".$DBPrefix."settings WHERE settingname='version';";
		$result = dbRetrieve($sqlQuery,true,0,0);
		if ($rs = dbFetch($result)) { $version = $rs["settingvalue"]; }
		else{ $version = false;}
		dbFreeResult($result);
	} else {
		$version = false;
	}	
	
	dbFreeResult($checkresult);
	return $version;
}

function updateVersion($DBPrefix){
	$Status = True;
	$sqlQuery = "UPDATE ".$DBPrefix."settings SET settingvalue='".$GLOBALS["version"]."' WHERE settingname='version';";
	sqlLog($sqlQuery);
	$uresult = dbExecute($sqlQuery,true);
	if (!$uresult) { $Status = False; }
	return $Status;
}

function setVersion($DBPrefix){
	$Status = True;
	$sqlQuery = "INSERT INTO ".$DBPrefix."settings (settingname, cssentry, settingvalue) VALUES ('version', '', '".$GLOBALS["version"]."');";
	sqlLog($sqlQuery);
	$uresult = dbExecute($sqlQuery,true);
	if (!$uresult) { $Status = False; }
	return $Status;
}
//#########  End versioning check  
 
function sqlLog(&$sqlString)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlString,$GLOBALS["Titles"]["InstallLog"]); }
} // function sqlLog()

function create_database($DBName,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { 
	debug_msg('****	Creating Database '.$DBName,$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$sqlQuery = "CREATE DATABASE ".$DBName.";";
	sqlLog($sqlQuery);
	$result = dbExecute($sqlQuery,true);

	if ($result) {
		install_message('green','Database '.$DBName.' created.');
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Database '.$DBName.' created',$GLOBALS["Titles"]["InstallLog"]); }
		$GLOBALS["dbConn"]->SelectDB($DBName);
	} else {
		install_message('red','Failed to create database '.$DBName);
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Failed to create database '.$DBName,$GLOBALS["Titles"]["InstallLog"]); }
		$Status = False;
	}
} // function create_database()


function test_database($DBName,$DBPrefix,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Testing Database '.$DBName,$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$tables = $GLOBALS["dbConn"]->MetaTables();
	if (in_array($DBPrefix.'authors',$tables) === False) {
		install_message('red','Database '.$DBName.' is not an iziContents database;<br />or table prefix '.$DBPrefix.'is invalid');
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Database '.$DBName.' is not an iziContents database; or prefix '.$DBPrefix.' is invalid',$GLOBALS["Titles"]["InstallLog"]); }
		$Status = False;
	}
} // function test_database()

function populate_database($DBPrefix,$ScriptFile,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Populating Database using MySQL_izicontents1_'.$ScriptFile.'.sql',$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$fp = fopen('./sql/MySQL_izicontents1'.$ScriptFile.'.sql', "r");
	$installfile = './sql/MySQL_izicontents1'.$ScriptFile.'.sql';
	if (!$fp) {
		install_message('red','Unable to open MySQL_izicontents1'.$ScriptFile.'.sql file');
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Unable to open MySQL_izicontents1'.$ScriptFile.'.sql',$GLOBALS["Titles"]["InstallLog"]); }
		$Status = False;
	} else {
		//$file = fread($fp, $GLOBALS["MaxFileSize"]);
		$file = file_get_contents($installfile);
		$file = str_replace("\r", "", $file);
		$query = explode(";\n",$file);
		for ($i=0; $i < count($query) - 1; $i++) {
			if ($Status) {
				$sqlQuery = trim($query[$i]);
				$workquery = explode("\n",$sqlQuery);
				for ($j=0; $j < count($workquery) - 1; $j++) {
					$test_workquery = trim($workquery[$j]);
					if (substr($test_workquery,0,1) == '#') { $workquery[$j] = ''; }
				}
				$sqlQuery = implode("",$workquery);
				if ((substr($sqlQuery,0,4) == 'DROP') || (substr($sqlQuery,0,6) == 'CREATE') || (substr($sqlQuery,0,6) == 'UPDATE') || (substr($sqlQuery,0,6) == 'INSERT') || (substr($sqlQuery,0,5) == 'ALTER') ) {
					if (substr($sqlQuery,0,4) == 'DROP') {
						$sqlQuery = str_replace('DROP TABLE IF EXISTS ','DROP TABLE IF EXISTS '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'CREATE') {
						$sqlQuery = str_replace('CREATE TABLE ','CREATE TABLE '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'UPDATE') {
						$sqlQuery = str_replace('UPDATE ','UPDATE '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'INSERT') {
						$sqlQuery = str_replace('INSERT INTO ','INSERT INTO '.$DBPrefix, $sqlQuery);
					}
					if (substr($sqlQuery,0,5) == 'ALTER') {
						$sqlQuery = str_replace('ALTER TABLE ','ALTER TABLE '.$DBPrefix, $sqlQuery);
					}
					sqlLog($sqlQuery);
					$result = dbExecute($sqlQuery,true);
					if (!$result) { $Status = False; }
				}
			}
		}
		$fp = fclose($fp);
	}
} // function populate_database()

function transfer_database($DBPrefix,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Transferring data to new data structures',$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$tables = $GLOBALS["dbConn"]->MetaTables();
	$num_tables = count($tables);
	$i = 0;
	while ($i < $num_tables) {
		if ($Status) {
			$tablename = $tables[$i];
			if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Testing table '.$tablename.' for upgrade',$GLOBALS["Titles"]["InstallLog"]); }
			if ((substr($tablename,0,strlen($DBPrefix)) == $DBPrefix) && (substr($tablename,0,7) != 'izicupg_')) {
				if ($GLOBALS["Log"] == 'Y') { debug_msg('****			Table requires upgrade',$GLOBALS["Titles"]["InstallLog"]); }
				$FieldList = $GLOBALS["dbConn"]->MetaColumnNames($tablename);
				$newtablename = 'izicupg_'.substr($tablename,strlen($DBPrefix));
				$NewFieldList = $GLOBALS["dbConn"]->MetaColumnNames($newtablename);
				if ($GLOBALS["Log"] == 'Y') {
					debug_msg('****			Field List in table '.$tablename,$GLOBALS["Titles"]["InstallLog"]);
					reset($FieldList);
					while (list ($key, $val) = each ($FieldList)) {
						debug_msg('****				'.$val,$GLOBALS["Titles"]["InstallLog"]);
					}
					reset($FieldList);
					debug_msg('****			Field List in new table '.$newtablename,$GLOBALS["Titles"]["InstallLog"]);
					reset($NewFieldList);
					while (list ($key, $val) = each ($NewFieldList)) {
						debug_msg('****				'.$val,$GLOBALS["Titles"]["InstallLog"]);
					}
					reset($FieldList);
				}

				$sqlQuery = 'SELECT * FROM '.$tablename;
				if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
				$result = dbRetrieve($sqlQuery,false,0,0);
				while ($rs = dbFetch($result)) {
					if ($Status) {
						$isql1 = 'INSERT INTO '.$newtablename.' ( ';
						$isql2 = ' ) VALUES ( ';
						$usql  = 'UPDATE '.$newtablename.' SET ';
						$k = 0;
						for ($j=0; $j < count($FieldList); $j++) {
							$ColumnName = $FieldList[$j];
							reset($NewFieldList);
							if (in_array($ColumnName,$NewFieldList) === True) {
								if ($k > 0) {
									$isql1 .= ', ';
									$isql2 .= ', ';
									$usql  .= ', ';
								} else {
									$keycol = $ColumnName;
									$keyval = dbStr($rs[$ColumnName]);
								}
								$isql1 .= $ColumnName;
								$isql2 .= "'".dbStr($rs[$ColumnName])."'";
								if (($newtablename == 'izicupg_specialcontents') && ($ColumnName == 'scid')) {
									$k--;
								} else {
									$usql  .= $ColumnName."='".dbStr($rs[$ColumnName])."'";
								}
								$k++;
							}
						}
						if ($newtablename == 'izicupg_specialcontents') {
							$keycol = 'scname';
							$keyval = $rs["scname"];
						}
						$isql = $isql1.$isql2.' );';
						$usql .= " WHERE ".$keycol."='".$keyval."';";
						if ($k > 0) {
							sqlLog($isql);
							$iresult = dbExecute($isql,false);
							if ($iresult === False) {
								sqlLog($usql);
								$uresult = dbExecute($usql,true);
								if (!$uresult) { $Status = False; }
							}
						}
					}
				}
				dbFreeResult($result);
			}
		}
		$i++;
	}
} // function transfer_database()


function upgrade_database($DBPrefix,&$Status)
{
	global $_POST;

	$Status = True;

	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Upgrading Database',$GLOBALS["Titles"]["InstallLog"]); }

	$baselanguage = 'en';
	//  Retrieve the default site language
	$sqlQuery = "SELECT settingvalue FROM ".$DBPrefix."settings WHERE settingname='default_language';";
	if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
	$result = dbRetrieve($sqlQuery,true,0,0);
	if ($rs = dbFetch($result)) { $baselanguage = $rs["settingvalue"]; }
	dbFreeResult($result);


	$masteruser = '';
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the authors table',$GLOBALS["Titles"]["InstallLog"]); }
	//  Upgrade the authors table
	$sqlQuery = 'SELECT * FROM '.$DBPrefix.'authors;';
	if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		if ($Status) {
			//  Encrypt the user's password
			$md5password = md5($rs["password"]);
			//  Retrieve the 'master' user (site administrator) so that we have an account for granting ownership to all other 'owned' records.
			if (($masteruser == '') && ($rs["usergroup"] == 1)) { $masteruser = $rs["authorid"]; }
			//  Retrieve the usergroup code rather than the number used in previous versions
			$usergroup = $rs["usergroup"];
			$sqlString = "SELECT usergroupname FROM izicupg_usergroups WHERE usergroupid='".$usergroup."';";
			$tresult = dbRetrieve($sqlString,true,0,0);
			$trs = dbFetch($tresult);
			$usergroupname = $trs["usergroupname"];
			dbFreeResult($tresult);
			//  Perform the update
			$sqlString = "UPDATE izicupg_authors SET userpassword='".$md5password."',usergroup='".$usergroupname."' WHERE authorid='".$rs["authorid"]."';";
			sqlLog($sqlString);
			$uresult = dbExecute($sqlString,true);
			if (!$uresult) { $Status = False; }
		}
	}
	dbFreeResult($result);

	if ($Status) {
		//  Upgrade the banners table with the tag creator (master author) and fieldname change: (active => banneractive)
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the banners table',$GLOBALS["Titles"]["InstallLog"]); }
		$sqlQuery = 'SELECT * FROM '.$DBPrefix.'banners;';
		if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
		$result = dbRetrieve($sqlQuery,true,0,0);
		while ($rs = dbFetch($result)) {
			if ($Status) {
				$sqlString = "UPDATE izicupg_banners SET banneractive='".$rs["active"]."', authorid='".$masteruser."';";
				sqlLog($sqlString);
				$uresult = dbExecute($sqlString,true);
				if (!$uresult) { $Status = False; }
			}
		}
	}

	if ($Status) {
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the tags table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Remove any '[' or ']' from the tags table as these are now handled internally
		$sqlString = "DELETE FROM izicupg_tags WHERE translation='".$GLOBALS["tqBlock1"]."' OR translation='".$GLOBALS["tqBlock2"]."';";
		sqlLog($sqlString);
		$uresult = dbExecute($sqlString,true);
		if (!$uresult) { $Status = False;
		} else {
			//  Upgrade the tags table with the tag creator (master author) and appropriate categories
			$sqlString = "UPDATE izicupg_tags SET authorid='".$masteruser."', cat='text' WHERE tag IN ('b','/b','i','/i','u','/u');";
			sqlLog($sqlString);
			$uresult = dbExecute($sqlString,true);
			if (!$uresult) { $Status = False;
			} else {
				$sqlString = "UPDATE izicupg_tags SET authorid='".$masteruser."', cat='list' WHERE tag IN ('ul','/ul','ol','/ol','li','/li');";
				sqlLog($sqlString);
				$uresult = dbExecute($sqlString,true);
				if (!$uresult) { $Status = False;
				} else {
					$sqlString = "UPDATE izicupg_tags SET authorid='".$masteruser."', cat='title' WHERE tag IN ('h2','/h2','h3','/h3','h4','/h4');";
					sqlLog($sqlString);
					$uresult = dbExecute($sqlString,true);
					if (!$uresult) { $Status = False;
					} else {
						$sqlString = "UPDATE izicupg_tags SET authorid='".$masteruser."', cat='table' WHERE tag IN ('table','/table','row','/row','cell_lt','cell_ct','cell_rt','cell_lc','cell_cc','cell_rc','cell_lb','cell_cb','cell_rb','/cell');";
						sqlLog($sqlString);
						$uresult = dbExecute($sqlString,true);
						if (!$uresult) { $Status = False;
						} else {
							$sqlString = "UPDATE izicupg_tags SET authorid='".$masteruser."', cat='other' WHERE cat='';";
							sqlLog($sqlString);
							$uresult = dbExecute($sqlString,true);
							if (!$uresult) { $Status = False; }
						}
					}
				}
			}
		}
	}

	if ($Status) {
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the imageformattemplates table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Upgrade the imageformattemplates table with the tag creator (master author)
		$sqlString = "UPDATE izicupg_imageformattemplates SET authorid='".$masteruser."';";
		sqlLog($sqlString);
		$uresult = dbExecute($sqlString,true);
		if (!$uresult) { $Status = False; }
	}

	if ($Status) {
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the sidebartemplates table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Upgrade the sidebartemplates table with the tag creator (master author)
		$sqlString = "UPDATE izicupg_sidebartemplates SET authorid='".$masteruser."';";
		sqlLog($sqlString);
		$uresult = dbExecute($sqlString,true);
		if (!$uresult) { $Status = False; }
	}


	if ($Status) { specialcontents($DBPrefix,'izicupg_',$Status); }

	if ($Status) {
		if ($_POST["diary"] == 'Y') {
			if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the diary table',$GLOBALS["Titles"]["InstallLog"]); }
			//  Upgrade the diary table (fieldname change: active => activeentry)
			$sqlQuery = 'SELECT * FROM '.$DBPrefix.'diary;';
			if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
			$result = dbRetrieve($sqlQuery,true,0,0);
			while ($rs = dbFetch($result)) {
				if ($Status) {
					$active = $rs["active"];
					//  Perform the update
					$sqlString = "UPDATE izicupg_diary SET activeentry='".$active."' WHERE diaryid='".$rs["diaryid"]."';";
					sqlLog($sqlString);
					$uresult = dbExecute($sqlString,true);
					if (!$uresult) { $Status = False; }
				}
			}
			dbFreeResult($result);
		}
	}

	if ($Status) {
		if ($_POST["guestbook"] == 'Y') {
			if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the guestbook table',$GLOBALS["Titles"]["InstallLog"]); }
			//  Upgrade the guestbook table (fieldname change: active => activeentry)
			$sqlQuery = 'SELECT * FROM '.$DBPrefix.'guestbook;';
			if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
			$result = dbRetrieve($sqlQuery,true,0,0);
			while ($rs = dbFetch($result)) {
				if ($Status) {
					$active = $rs["active"];
					//  Perform the update
					$sqlString = "UPDATE izicupg_guestbook SET activeentry='".$active."' WHERE guestbookid='".$rs["guestbookid"]."';";
					sqlLog($sqlString);
					$uresult = dbExecute($sqlString,true);
					if (!$uresult) { $Status = False; }
				}
			}
			dbFreeResult($result);
		}
	}

	if ($Status) {
		if ($_POST["news"] == 'Y') {
			if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the news table',$GLOBALS["Titles"]["InstallLog"]); }
			//  Upgrade the news table (fieldname change: active => activeentry)
			$sqlQuery = 'SELECT * FROM '.$DBPrefix.'news;';
			if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
			$result = dbRetrieve($sqlQuery,true,0,0);
			while ($rs = dbFetch($result)) {
				if ($Status) {
					$active = $rs["active"];
					//  Perform the update
					$sqlString = "UPDATE izicupg_news SET activeentry='".$active."' WHERE newsid='".$rs["newsid"]."';";
					sqlLog($sqlString);
					$uresult = dbExecute($sqlString,true);
					if (!$uresult) { $Status = False; }
				}
			}
			dbFreeResult($result);
		}
	}

	if ($Status) {
		if ($_POST["links"] == 'Y') {
			//	Test to see if links module was installed in previous version
			$sqlQuery = "SELECT scname FROM ".$DBPrefix."specialcontents WHERE scname='links'";
			sqlLog($sqlQuery);
			$lresult = dbRetrieve($sqlQuery,true,0,0);
			$lRecCount = dbRowsReturned($lresult);
			dbFreeResult($lresult);
			if ($lRecCount > 0) {
				if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the links table',$GLOBALS["Titles"]["InstallLog"]); }
				//  Upgrade the links table (fieldname change: active => activeentry)
				$sqlQuery = 'SELECT * FROM '.$DBPrefix.'links;';
				if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
				$result = dbRetrieve($sqlQuery,true,0,0);
				while ($rs = dbFetch($result)) {
					if ($Status) {
						$active = $rs["active"];
						//  Perform the update
						$sqlString = "UPDATE izicupg_links SET activeentry='".$active."' WHERE linksid='".$rs["linksid"]."';";
						sqlLog($sqlString);
						$uresult = dbExecute($sqlString,true);
						if (!$uresult) { $Status = False; }
					}
				}
				dbFreeResult($result);
			}
		}
	}

	if ($Status) {
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the topgroups table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Upgrade the topgroups table
		$sqlQuery = 'SELECT * FROM '.$DBPrefix.'topgroups;';
		if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
		$result = dbRetrieve($sqlQuery,true,0,0);
		while ($rs = dbFetch($result)) {
			if ($Status) {
				//  Retrieve the usergroup codes rather than the number used in previous versions
				$usergroup = $rs["usergroup"];
				$usergroupnames = '';
				if (($usergroup != '') && ($usergroup != '99')) {
					$sqlString = "SELECT usergroupname FROM izicupg_usergroups WHERE usergroupid<='".$usergroup."';";
					$tresult = dbRetrieve($sqlString,true,0,0);
					while ($trs = dbFetch($tresult)) {
						$usergroupnames .= ','.$trs["usergroupname"];
					}
					dbFreeResult($tresult);
				}
				//  Perform the update
				$sqlString = "UPDATE izicupg_topgroups SET topgroupname=topgroupid, language='".$baselanguage."', usergroups='".$usergroupnames."', authorid='".$masteruser."' WHERE topgroupid='".$rs["topgroupid"]."';";
				sqlLog($sqlString);
				$uresult = dbExecute($sqlString,true);
				if (!$uresult) { $Status = False; }
			}
		}
		dbFreeResult($result);
	}

	if ($Status) {
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the groups table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Upgrade the groups table
		$sqlQuery = 'SELECT * FROM '.$DBPrefix.'groups;';
		if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
		$result = dbRetrieve($sqlQuery,true,0,0);
		while ($rs = dbFetch($result)) {
			if ($Status) {
				//  Retrieve the subgroup count
				$sqlString = "SELECT COUNT(subgroupid) AS count FROM ".$DBPrefix."subgroups WHERE groupname='".$rs["groupdesc"]."';";
				$tresult = dbRetrieve($sqlString,true,0,0);
				$trs = dbFetch($tresult);
				$subgroupcount = $trs["count"];
				dbFreeResult($tresult);
				//  Retrieve the usergroup codes rather than the number used in previous versions
				$usergroup = $rs["usergroup"];
				$usergroupnames = '';
				if (($usergroup != '') && ($usergroup != '99')) {
					$sqlString = "SELECT usergroupname FROM izicupg_usergroups WHERE usergroupid<='".$usergroup."';";
					$tresult = dbRetrieve($sqlString,true,0,0);
					while ($trs = dbFetch($tresult)) {
						$usergroupnames .= ','.$trs["usergroupname"];
					}
					dbFreeResult($tresult);
				}
				//  Perform the update
				$sqlString = "UPDATE izicupg_groups SET groupname=groupid, topgroupname='".$rs["topgroupid"]."', language='".$baselanguage."', usergroups='".$usergroupnames."', subgroupcount='".$subgroupcount."', authorid='".$masteruser."' WHERE groupid='".$rs["groupid"]."';";
				sqlLog($sqlString);
				$uresult = dbExecute($sqlString,true);
				if (!$uresult) { $Status = False; }
			}
		}
		dbFreeResult($result);
	}

	if ($Status) {
		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the subgroups table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Upgrade the subgroups table
		$sqlQuery = 'SELECT * FROM '.$DBPrefix.'subgroups;';
		if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
		$result = dbRetrieve($sqlQuery,true,0,0);
		while ($rs = dbFetch($result)) {
			if ($Status) {
				//  Retrieve the usergroup codes rather than the number used in previous versions
				$usergroup = $rs["usergroup"];
				$usergroupnames = '';
				if (($usergroup != '') && ($usergroup != '99')) {
					$sqlString = "SELECT usergroupname FROM izicupg_usergroups WHERE usergroupid<='".$usergroup."';";
					$tresult = dbRetrieve($sqlString,true,0,0);
					while ($trs = dbFetch($tresult)) {
						$usergroupnames .= ','.$trs["usergroupname"];
					}
					dbFreeResult($tresult);
				}
				//  Perform the update
				$sqlString = "UPDATE izicupg_subgroups SET subgroupname=subgroupid, groupname='".$rs["groupid"]."', language='".$baselanguage."', usergroups='".$usergroupnames."', authorid='".$masteruser."' WHERE subgroupid='".$rs["subgroupid"]."';";
				sqlLog($sqlString);
				$uresult = dbExecute($sqlString,true);
				if (!$uresult) { $Status = False; }
			}
		}
		dbFreeResult($result);
	}

	if ($Status) {
		$GLOBALS["tqBlock1"] = '[';
		$GLOBALS["tqBlock2"] = ']';
		$GLOBALS["tqCloseBlock"] = '/';
		$GLOBALS["tqSeparator"] = ',';
		$GLOBALS["eztbTags"] = 'izicupg_tags';
		include_once('../admin/compile.php');

		if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the contents table',$GLOBALS["Titles"]["InstallLog"]); }
		//  Upgrade the contents table
		$sqlQuery = 'SELECT * FROM '.$DBPrefix.'contents;';
		if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
		$result = dbRetrieve($sqlQuery,true,0,0);
		while ($rs = dbFetch($result)) {
			if ($Status) {
				$sbody = $rs["body"];
				$steaser = $rs["teaser"];
				//	Strip obsolete [html] tags
				$sbody = str_replace('[html]','', $sbody);
				$sbody = str_replace('[/html]','', $sbody);
				$steaser = str_replace('[html]','', $steaser);
				$steaser = str_replace('[/html]','', $steaser);

				debug_msg('Article '.$rs["contentid"].':<BR>BODY=['.$sbody.']<BR>TEASER=['.$steaser.']',$GLOBALS["Titles"]["InstallLog"]);

				//	Generate pre-compile body and teaser content
				$cbody = dbStr(compile(trim('[html]'.$sbody.'[/html]'),'N','Y',$rs["leftright"],'Y'));
				$cteaser = dbStr(compile(trim('[html]'.$steaser.'[/html]'),'N','Y',$rs["leftright"],'Y'));
				$sbody = dbStr($sbody);
				$steaser = dbStr($steaser);
				//  Perform the update
				$sqlString = "UPDATE izicupg_contents SET contentname=contentid, subgroupname='".$rs["subgroupid"]."', groupname='".$rs["groupid"]."', contentactive='".$rs["active"]."', language='".$baselanguage."', body='".$sbody."', teaser='".$steaser."', cbody='".$cbody."', cteaser='".$cteaser."', authorid='".$masteruser."' WHERE contentid='".$rs["contentid"]."';";
				sqlLog($sqlString);
				$uresult = dbExecute($sqlString,true);
				if (!$uresult) { $Status = False; }
			}
		}
		dbFreeResult($result);
	}

	if ($Status) {
		//  Update language settings if site isn't using the default 'en' language
		if ($baselanguage != 'en') {
			if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Upgrading the languages table',$GLOBALS["Titles"]["InstallLog"]); }
			$sqlString = "UPDATE izicupg_languages SET enabled='Y' WHERE languagecode='".$baselanguage."';";
			sqlLog($sqlString);
			$uresult = dbExecute($sqlString,true);
			if (!$uresult) { $Status = False;
			} else {
				$sqlQuery = "SELECT * FROM izicupg_tagcategories;";
				if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlQuery,$GLOBALS["Titles"]["InstallLog"]); }
				$tresult = dbRetrieve($sqlQuery,true,0,0);
				while ($trs = dbFetch($tresult)) {
					if ($Status) {
						$sqlString = "INSERT INTO izicupg_tagcategories(catdesc, catname, language, authorid) VALUES('".dbStr($trs["catdesc"])."','".$trs["catname"]."','".$baselanguage."','".$masteruser."');";
						sqlLog($sqlString);
						$uresult = dbExecute($sqlString,true);
						if (!$uresult) { $Status = False; }
					}
				}
				dbFreeResult($tresult);
				if ($Status) {
					$sqlString = "SELECT * FROM izicupg_usergroups;";
					if ($GLOBALS["Log"] == 'Y') { debug_msg($sqlString,$GLOBALS["Titles"]["InstallLog"]); }
					$tresult = dbRetrieve($sqlString,true,0,0);
					while ($trs = dbFetch($tresult)) {
						if ($Status) {
							$sqlString = "INSERT INTO izicupg_usergroups(usergroupdesc, usergroupname, language, authorid) VALUES('".dbStr($trs["usergroupdesc"])."','".$trs["usergroupname"]."','".$baselanguage."','".$masteruser."');";
							sqlLog($sqlString);
							$uresult = dbExecute($sqlString,true);
							if (!$uresult) { $Status = False; }
						}
					}
					dbFreeResult($tresult);
				}
			}
		}
	}

	if ($Status) {
		//  Clear all sessions
		$sqlString = "DELETE FROM izicupg_sessions;";
		sqlLog($sqlString);
		$uresult = dbExecute($sqlString,true);
		if (!$uresult) { $Status = False; }
	}
} // function upgrade_database()


function drop_old_tables($DBPrefix,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Dropping old database tables',$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$tables = $GLOBALS["dbConn"]->MetaTables();
	$num_tables = count($tables);
	$i = 0;
	while ($i < $num_tables) {
		if ($Status) {
			$tablename = $tables[$i];
			if ((substr($tablename,0,strlen($DBPrefix)) == $DBPrefix) && (substr($tablename,0,7) != 'izicupg_')) {
				if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Dropping table '.$tablename,$GLOBALS["Titles"]["InstallLog"]); }
				$dsql = "DROP TABLE ".$tablename;
				sqlLog($dsql);
				$dresult = dbExecute($dsql,true);
				if (!$dresult) { $Status = False; }
			}
		}
		$i++;
	}
} // function drop_old_tables()

function rename_new_tables($DBPrefix,&$Status)
{
	if ($GLOBALS["Log"] == 'Y') { debug_msg('****	Renaming new database tables',$GLOBALS["Titles"]["InstallLog"]); }

	$Status = True;
	$tables = $GLOBALS["dbConn"]->MetaTables();
	$num_tables = count($tables);
	$i = 0;
	while ($i < $num_tables) {
		if ($Status) {
			$tablename = $tables[$i];
			if (substr($tablename,0,7) == 'izicupg_') {
				$tableref = $DBPrefix.substr($tablename,7);
				if ($GLOBALS["Log"] == 'Y') { debug_msg('****		Renaming table '.$tablename.' to '.$tableref,$GLOBALS["Titles"]["InstallLog"]); }
				$rsql = "ALTER TABLE ".$tablename.' RENAME '.$tableref;
				sqlLog($rsql);
				$rresult = dbExecute($rsql,true);
				if (!$rresult) { $Status = False; }
			}
		}
		$i++;
	}
} // function rename_new_tables()

?>