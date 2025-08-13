	<?
	
// mysql database configuration
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "prdelka";

	$dbsafe = "1";

// don't edit anything below this line unless you really know what you are doing!!!
//################################################################################

if(!isset($dbworks)) {
	$dbworks="";
}

switch($dbworks) {
	default:
	if($input_method == "3") {
		$dbname = $HTTP_GET_VARS["dbname"];
		$dbtable = $HTTP_GET_VARS["dbtable"];
		$dbfield = $HTTP_GET_VARS["dbfield"];
		$dbrecord = $HTTP_GET_VARS["dbrecord"];
		$dbai = $HTTP_GET_VARS["dbai"];
		$dbreturn = $HTTP_GET_VARS["dbreturn"];
		
		if(isset($HTTP_GET_VARS["dbsafe"])) {
			$dbsafe = $HTTP_GET_VARS["dbsafe"];
		}
	}

	if($input_method == "4") {
		$dbname = $HTTP_POST_VARS["dbname"];
		$dbtable = $HTTP_POST_VARS["dbtable"];
		$dbrecord = $HTTP_POST_VARS["dbrecord"];
		$dbfield = $HTTP_POST_VARS["dbfield"];
		$dbai = $HTTP_POST_VARS["dbai"];
		$dbreturn = $HTTP_POST_VARS["dbreturn"];
		
		if(isset($HTTP_POST_VARS["dbsafe"])) {
			$dbsafe = $HTTP_POST_VARS["dbsafe"];
		}
	}

break;

	case "init":

		$dbname = $HTTP_GET_VARS["dbname"];
		$dbtable = $HTTP_GET_VARS["dbtable"];
		$dbfield = $HTTP_GET_VARS["dbfield"];
		$dbrecord = $HTTP_GET_VARS["dbrecord"];
		$dbai = $HTTP_GET_VARS["dbai"];
		$dbreturn = $HTTP_GET_VARS["dbreturn"];
		
		if(isset($HTTP_GET_VARS["dbsafe"])) {
			$dbsafe = $HTTP_GET_VARS["dbsafe"];
		}

		// (dbai stands for auto increment field called as id, ID ro whatever - remember, this is the only CASE SENSITIVE variable)
		// backpath is where 
		
		// in case, there is no record number, editor will create new record
		if($dbrecord == "") {
			return;

		// otherwise it will load requested content from database
		} else {

			if($db = mysql_connect($dbhost,$dbuser,$dbpass)) {
				if(mysql_select_db($dbname,$db)) {
					if($query = mysql_query("SELECT ".$dbfield." AS thatsit FROM ".$dbtable." WHERE ".$dbai."=".$dbrecord."",$db)) {
						$result = mysql_fetch_array($query);
					
						// return stripslashes($result[thatsit]);
						echo stripslashes($result[thatsit]);

					} else { echo "<script language=\"Javascript\">alert('Error: Cannot perform the query check name of the table, field, record number and name of autoincremented field.');</script>";
					}
		
				} else {
					echo "<script language=\"Javascript\">alert('Error: Connected but cannot find database ".$dbname.".');</script>";
				}

			} else {
				echo "<script language=\"Javascript\">alert('Error: Cannot connect to the database, check your hostname, username and password, please.');</script>";
			}
		
			mysql_close($db);
		}

break;

case "save":
	// input is following $dbhost,$dbuser,$dbpass,$dbname,$dbtable,$dbfield,$dbrecord,$dbsafe,$dbreturn,$edited

		$edited = $HTTP_POST_VARS["EditorValue"];
		
		if($db = mysql_connect($dbhost,$dbuser,$dbpass)) {
			if(mysql_select_db($dbname,$db)) {

				if($dbsafe == "1") {
					$edited = addslashes($edited);
				}
				
				// no record number, insert new record to database
				if($dbrecord == "") {
					
					if($query = mysql_query("INSERT INTO ".$dbtable." (".$dbfield.") VALUES ('".$edited."')",$db)) {
					
						echo "<script language=\"Javascript\">window.location = '".$dbreturn."';</script>";

					} else { 
						echo "<script language=\"Javascript\">alert('Error: Cannot create new record, dunno why ...');</script>";
					}

				// we have record number, update database record
				} else {

					if($query = mysql_query("UPDATE ".$dbtable." SET ".$dbfield."='".$edited."' WHERE ".$dbai."=".$dbrecord."",$db)) {
					
						echo "<script language=\"Javascript\">window.location = '".$dbreturn."';</script>";

					} else { 
						echo "<script language=\"Javascript\">alert('Error: Cannot update record, with ID=".$dbrecord." ...');</script>";
					}
				}

		
			} else {
				echo "<script language=\"Javascript\">alert('Error: Connected but cannot find database ".$dbname.".');</script>";
			}

		} else {
			echo "<script language=\"Javascript\">alert('Error: Cannot connect to the database, check your hostname, username and password, please.');</script>";
		}
		
		mysql_close($db);
break;
}
?>