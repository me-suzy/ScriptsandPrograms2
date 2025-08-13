<?php
function insert_data($table)
{
	global $mysql_db, $conn;
	@set_time_limit(60);
    $rs = &$conn->Execute("SELECT * from $table") or exit();

	$insert="";
	while($rs && !$rs->EOF)
	{	/*$insert.="INSERT INTO $table (";
		$cols=&$conn->MetaColumns($table);
		//all field names
		foreach($cols as $key => $value)
			$insert.=strtolower($key).", ";
		$insert=substr_replace($insert,") ",-2); //kill last comma and space
		$insert.="VALUES (";
		
		//all values
		for($i=0;$i<count($cols);$i++)
			//$insert.="'".$rs->fields[$i]."', ";
			$insert.=$conn->qstr($rs->fields[$i].", ");
		$insert=substr_replace($insert,") ",-2); //kill last comma and space
		$insert.=";\n";*/
		$temp=$conn->GetInsertSQL($rs,$rs->fields);
		$temp=ereg_replace("\n","\\n", $temp);
		$temp=ereg_replace("\r","\\r", $temp);
		$insert.=$temp.";\n";
		$rs->MoveNext();
    }
    return ($insert);
}

function create_tables()
{	global $sql_type;
	//create tables
	$dbtype=$sql_type;
	
	//config
	if ($dbtype == "mysql")
		$inl_config_u = ", UNIQUE name (name)";
	elseif ($dbtype == "postgres7")
		$inl_config_u = ", UNIQUE (name)";
	elseif ($dbtype == "mssql")
		$inl_config_u = "";

	$query="CREATE TABLE inl_config (name varchar(255) NOT NULL,value varchar(255) NOT NULL,PRIMARY KEY (name) $inl_config_u);\n";

	if ($dbtype == "mysql")
		$idfield = "int NOT NULL auto_increment";
	elseif ($dbtype == "postgres7")
		$idfield = "serial";
	elseif ($dbtype == "mssql")
			$idfield = "int IDENTITY (1, 1) NOT NULL ";

	//categories
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_cats_cat_id_seq\"'::text) NOT NULL";
	$query .= "CREATE TABLE inl_cats (cat_id $idfield,cat_name varchar(255),cat_desc text NULL,cat_user int DEFAULT '0' NOT NULL,cat_sub int DEFAULT '0' NOT NULL,cat_perm smallint DEFAULT '0' NOT NULL,	   cat_pend smallint DEFAULT '0' NOT NULL,cat_vis smallint DEFAULT '0' NOT NULL,cat_links int DEFAULT '0' NOT NULL,cat_cats int DEFAULT '0' NOT NULL,cat_date int DEFAULT '0' NOT NULL,cat_pick int DEFAULT '0' NOT NULL,cat_image varchar(255) NULL,cat_cust int DEFAULT '0' NOT NULL,meta_keywords text NULL,meta_desc text NULL,PRIMARY KEY (cat_id));\n";

   $query .= "CREATE INDEX cat_sub ON inl_cats (cat_sub);\n";

	//custom
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_custom_cust_id_seq\"'::text) NOT NULL";
	$query .= "CREATE TABLE inl_custom (cust_id $idfield,cust1 varchar(255) NULL,cust2 varchar(255) NULL,cust3 varchar(255) NULL,cust4 text NULL,cust5 text NULL,cust6 text NULL,PRIMARY KEY (cust_id));\n";

	//e-mail
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_email_email_id_seq\"'::text) NOT NULL";
	$query .= "CREATE TABLE inl_email (email_id $idfield,email_subject varchar(255) NULL,email_body text NULL,   email_from varchar(50) NULL,email_reply varchar(50) NULL,email_to varchar(50) NULL,	PRIMARY KEY (email_id));\n";

	//Favorites
	//$query .= "CREATE TABLE inl_fav ( user_id int(11) NOT NULL default '0', link_id int(11) NOT NULL default '0', KEY user_id (user_id,link_id));\n";
	$query .= "CREATE TABLE inl_fav (user_id int not null default '0',link_id int not null default '0');\n";
  $query .= "CREATE INDEX user_id ON inl_fav (user_id);\n";
  $query .= "CREATE INDEX fav_link_id ON inl_fav (link_id);\n";

	//link cats
	$query .= "CREATE TABLE inl_lc (link_id int DEFAULT '0' NOT NULL,cat_id int DEFAULT '0' NOT NULL,	   link_pend int DEFAULT '0' NOT NULL);\n";

  $query .= "CREATE INDEX lc_link_id ON inl_lc (link_id);\n";
  $query .= "CREATE INDEX cat_id ON inl_lc (cat_id);\n";

	//links
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_links_link_id_seq\"'::text) NOT NULL";
	$query .= "CREATE TABLE inl_links (link_id $idfield,link_name varchar(254) NOT NULL,link_desc text NOT NULL,	link_url varchar(254) NOT NULL,link_date int DEFAULT '0' NOT NULL,link_user int DEFAULT '0' NOT NULL,	   link_hits int DEFAULT '0' NOT NULL,link_votes int DEFAULT '0' NOT NULL,link_rating decimal(6,4) DEFAULT '0.0000' NOT NULL,link_pick smallint DEFAULT '0' NOT NULL,link_vis smallint DEFAULT '0' NOT NULL,	   link_image varchar(254) NULL,link_cust int DEFAULT '0' NOT NULL,link_numrevs int DEFAULT '0' NOT NULL,	   PRIMARY KEY (link_id));\n";
	
	//Related Categories
	//$query .= "CREATE TABLE inl_rel_cats ( cat_id int(11) NOT NULL default '0', rel_id int(11) NOT NULL default '0', KEY cat_id (cat_id,rel_id));\n"; 
	$query.= "CREATE TABLE inl_rel_cats (cat_id int not null default '0', rel_id int not null default '0');\n"; 
  $query.= "CREATE INDEX rel_cat_id ON inl_rel_cats (cat_id);\n"; 
  $query.= "CREATE INDEX rel_id ON inl_rel_cats (rel_id);\n"; 

	//reviews
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_reviews_rev_id_seq\"'::text) NOT NULL";
	$query.= "CREATE TABLE inl_reviews (rev_id $idfield,rev_link int DEFAULT '0' NOT NULL,rev_user int DEFAULT '0' NOT NULL,rev_text text NULL,rev_date int DEFAULT '0' NOT NULL,rev_pend int DEFAULT '0' NOT NULL,PRIMARY KEY (rev_id));\n";
  $query.= "CREATE INDEX rev_link ON inl_reviews (rev_link);\n";

	//search log
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_search_log_log_id_seq\"'::text) NOT NULL";
	$query.= "CREATE TABLE inl_search_log (log_id $idfield, log_type smallint NOT NULL default '0', log_date int NOT NULL default '0', log_search smallint NOT NULL default '0', log_keyword varchar(255) NOT NULL default '', search_action int NOT NULL default '0', search_cat int NOT NULL default '0', PRIMARY KEY  (log_id)) ;\n";

   //sessions
	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_sessions_ses_id_seq\"'::text) NOT NULL";
  $query.= "CREATE TABLE inl_sessions (ses_id  $idfield,ses_time int DEFAULT '0' NOT NULL,user_id int DEFAULT '0' NOT NULL,user_perm int DEFAULT '0' NOT NULL,num_res varchar(25) NULL,link_order varchar(25) NULL,link_sort varchar(25) NULL,cat_order varchar(25) NULL,cat_sort varchar(25) NULL,lang varchar(25) NULL,theme varchar(25) NULL,destin varchar(250) NULL,PRIMARY KEY (ses_id));\n";
  $query.= "CREATE INDEX ses_id ON inl_sessions (ses_id);\n";

	//users
	if ($dbtype == "mysql")
		$inl_users_u = ", UNIQUE user_name (user_name)";
	elseif ($dbtype == "postgres7")
		$inl_users_u = ", UNIQUE (user_name)";
	elseif ($dbtype == "")
		$inl_users_u = "";

	if ($dbtype == "postgres7") $idfield = "int4 DEFAULT nextval('\"inl_users_user_id_seq\"'::text) NOT NULL";
	$query.= "CREATE TABLE inl_users (user_id $idfield,user_name varchar(20) NOT NULL,user_pass varchar(50) NOT NULL,first varchar(50) NOT NULL,last varchar(50) NOT NULL,email varchar(255) NULL,user_perm int DEFAULT '0' NOT NULL,user_date int DEFAULT '0' NOT NULL,user_cust int DEFAULT '0' NOT NULL,user_status smallint DEFAULT '0' NOT NULL,user_pend int DEFAULT '0' NOT NULL,PRIMARY KEY (user_id)  $inl_users_u);\n";

	//votes
   $query.= "CREATE TABLE inl_votes (stamp int DEFAULT '0' NOT NULL,vote_ip varchar(16) NOT NULL,	   vote_link int DEFAULT '0' NOT NULL,rev int DEFAULT '0' NOT NULL);\n";

   $query.= "CREATE INDEX stamp ON inl_votes (stamp);\n";
   $query.= "CREATE INDEX vote_ip ON inl_votes (vote_ip);\n";
   $query.= "CREATE INDEX vote_link ON inl_votes (vote_link);\n";

   return $query;
}

function create_seq()
{
	global $conn;
	$query = "SELECT relname FROM pg_class WHERE NOT relname ~ 'pg_.*' AND relkind ='S' ORDER BY relname";
	$rs = $conn->Execute($query);
	if ( $rs AND !$rs->EOF )
	{
		//echo "<pre>";
		for ( $i = 1 ; $i <= $rs -> RecordCount() ; $i ++ )
		{
			
			$sequence = $rs -> fields["relname"];	
			$query_seq = "SELECT * FROM $sequence";
			$rs1 = $conn->Execute($query_seq);
			
			if ( $rs1 AND !$rs1->EOF )
			{			
				//print_r($rs1);
				$last_value = $rs1 -> fields["last_value"];	
				$increment_by = $rs1 -> fields["increment_by"];				
				$max_value = $rs1 -> fields["max_value"];	
				$min_value = $rs1 -> fields["min_value"];	
				$cache_value = $rs1 -> fields["cache_value"];	
				$ret.="CREATE SEQUENCE $sequence start $last_value increment $increment_by maxvalue $max_value minvalue $min_value cache $cache_value;\n";				
				if ( $last_value > 1 ) $ret.="SELECT NEXTVAL('$sequence'); \n";			
			}
			$rs -> MoveNext();
		}
	}
	return $ret;
}
?>