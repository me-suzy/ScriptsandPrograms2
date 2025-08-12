<?php
/*********************************************************************
CLASSE DefaultTable: This class hold the default functions and vars used by other derived classes.
One class will correspond to a table of the database. The relation will be in the array $relationship
and the culumn will be in the array $fieldlist
For the array $fieldspec the following value are present:
type => data type
pkey => True if it's a primary key
size => max size
required => The value cannot be null
auto_increment => true if mysql will autoincrement the value for a new insertion
true e false => if it's a boolean type specifies here the value 
lowercase o uppercase =>
enum => enum type list of values
**********************************************************************/

class DefaultTable
{
	var $tablename;         // Name of the table
	var $rows_per_page;     // number of rows per page
	var $pageno;            // Current page shown
	var $lastpage;          // Last page of the selection
	var $fieldspec;         // Description of the fields
	var $data_array;        // Will be stored all the data from a select
	var $errors;            // Error messages
	var $unique_keys;	    // List of unique table keys
	var $relationship;	    // Information about relation many-one
	var $relationship_out;  // Information about relation many-one for the external table

	var $sql_select; 		  // Used by the query
	var $sql_from;
	var $sql_where;
	var $sql_groupby;
	var $sql_having;
	var $sql_orderby;

	function DefaultTable () // Basic Constructor
	{
		$this->tablename       = 'default';
		$this->dbname          = 'default';
		$this->rows_per_page   = 10;

		$this->fieldspec = array('column1', 'column2', 'column3');
		$this->fieldspec['column1'] = array('keyword' => 'value');
		$this->relationship[] = array('many' => 'tablename',
		'fields' => array('one_id' => 'many_id'),
		'type' => 'nullify|delete|restricted');
		$this->relationship_out[] = array('one' => 'tablename',
		'fields' => array('many_id' => 'one_id'),
		'type' => 'nullify|delete|restricted');
		$this->unique_keys[] = array('fieldname1', 'fieldname2');
	}

	/***************************************
	Extract the data select and store them in the $data_array
	****************************************/
	function getData ()
	{
		$this->data_array = array();		// Initialize vars
		$pageno          = $this->pageno;
		$rows_per_page   = $this->rows_per_page;
		$this->numrows   = 0;
		$this->lastpage  = 0;
		global $dbconnect, $query;
		$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		/* Fill the sql_* vars */
		if (empty($this->sql_where)) {
			$where_str = NULL;
		} else {
			$where_str = "WHERE $this->sql_where";
		} // if
		if (empty($this->sql_select)) {
			$select_str = '*';
		} else {
			$select_str = $this->sql_select;
		} // if
		if (empty($this->sql_from)) {
			$from_str = $this->tablename;
		} else {
			$from_str = $this->sql_from;
		} // if
		if (!empty($this->sql_groupby)) {
			$group_str = "GROUP BY $this->sql_groupby";
		} else {
			$group_str = NULL;
		} // if
		if (!empty($this->sql_having)) {
			$having_str = "HAVING $this->sql_having";
		} else {
			$having_str = NULL;
		} // if
		if (!empty($this->sql_orderby)) {
			$sort_str = "ORDER BY $this->sql_orderby";
		} else {
			$sort_str = NULL;
		} // if

		// Reset the vars to count the pages
		$query = "SELECT $select_str FROM $from_str $where_str $group_str $having_str $sort_str";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		$query_data = mysql_num_rows($result);
		$this->numrows = $query_data;

		if ($this->numrows <= 0) {
			$this->pageno = 0;
			return;
		} // if
		if ($rows_per_page > 0) {
			$this->lastpage = ceil($this->numrows/$rows_per_page);
		} else {
			$this->lastpage = 1;
		} // if
		if ($pageno == '' OR $pageno <= '1') {
			$pageno = 1;
		} elseif ($pageno > $this->lastpage) {
			$pageno = $this->lastpage;
		} // if
		if ($rows_per_page > 0) {
			$limit_str = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
		} else {
			$limit_str = NULL;
		} // if

		$this->pageno = $pageno;

		// Do the select
		$query = "SELECT $select_str FROM $from_str $where_str $group_str $having_str $sort_str $limit_str";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		while ($row = mysql_fetch_assoc($result)) {
			$rowFormat = $this->formatGet($row); //Build the array
			$this->data_array[] = $rowFormat;
		} // while

		mysql_free_result($result);


		return $this->data_array;

	} // getData

	/********************************************************************************
	Counts the rows
	********************************************************************************/
	function getCount($where)
	{
		global $dbconnect, $query;
		$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		if (empty($where)) {
			$where_str = NULL;
		} else {
			$where_str = "WHERE $where";
		}

		$query = "SELECT count(*) AS tot FROM $this->tablename $where_str";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
		$row = mysql_fetch_array($result);

		return $row[tot];

	}
	/***************************************
	Perform an insertion with the data specified in the array $fieldarray
	****************************************/
	function insertRecord ($fieldarray)
	{
		$this->errors = array();
		global $dbconnect, $query;
		$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		$fieldlist = $this->fieldspec;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!array_key_exists($field, $fieldlist)) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach
		$query = "INSERT INTO $this->tablename SET ";
		foreach ($fieldarray as $item => $value) {
			$query .= "$item='$value', ";
		} // foreach
		$query = rtrim($query, ', ');
		$result = @mysql_query($query, $dbconnect);
		if (mysql_errno() <> 0) {
			if (mysql_errno() == 1062) {
				$this->errors[] = "A record already exists with this ID.";
			} else {
				trigger_error("SQL", E_USER_ERROR);
			} // if
		} // if

		return;

	} // insertRecord

	/***************************************
	Perform a search with the data in $fieldarray	
	Results will be stored in $dataarray
	Returns the string passed to the db
	****************************************/
	function searchRecord ($fieldarray)
	{
		$this->errors = array();

		$fieldlist = $this->fieldspec;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!array_key_exists($field, $fieldlist) || !$fieldvalue) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach

		$where  = NULL;
		$update = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item])) {
				if($this->fieldspec[$item]['type'] == 'text') // Search inside the text if the type is text
				$where .= "$item LIKE '%$value%' AND ";
				else
				$where .= "$item='$value' AND ";
			}
		} // foreach

		$where  = rtrim($where, ' AND ');

		$this->sql_where = $where;
		$this->getData();

		return $where;

	} // updateRecord

	/***************************************
	Perform an update with the data specified in the array $fieldarray
	****************************************/
	function updateRecord ($fieldarray)
	{
		$this->errors = array();

		global $dbconnect, $query;
		$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);

		$fieldlist = $this->fieldspec;
		foreach ($fieldarray as $field => $fieldvalue) {
			if (!array_key_exists($field, $fieldlist)) {
				unset ($fieldarray[$field]);
			} // if
		} // foreach

		$where  = NULL;
		$update = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey'])) {
				$where .= "$item='$value' AND ";
			} else {
				$update .= "$item='$value', ";
			} // if
		} // foreach

		$where  = rtrim($where, ' AND ');
		$update = rtrim($update, ', ');

		$query = "UPDATE $this->tablename SET $update WHERE $where";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);

		return;

	} // updateRecord

	/***************************************
	Delete the selected data from the table. Search for the primary key
	in the array fieldarray and delete the key with that value
	****************************************/
	function deleteRecord ($fieldarray)
	{
		$this->errors = array();

		global $dbconnect, $query;
		$dbconnect = db_connect() or trigger_error("SQL", E_USER_ERROR);
		$fieldlist = $this->fieldspec;
		$where  = NULL;
		foreach ($fieldarray as $item => $value) {
			if (isset($fieldlist[$item]['pkey'])) {
				$where .= "$item='$value' AND ";
			} // if
		} // foreach
		$where  = rtrim($where, ' AND ');

		$query = "DELETE FROM $this->tablename WHERE $where";
		$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);

		return;

	} // deleteRecord

	/***************************************
	Modify the array $fieldarray and make it ready for an InsertRecord call.
	If errors are found, store them in $errors. 
	****************************************/
	function std_fieldValidation_insert ($fieldarray)
	{

		$this->errors = array();

		$temparray = array();
		// Check the input data
		foreach ($this->fieldspec as $field => $spec) {
			if (isset($fieldarray[$field])) {
				$value = $fieldarray[$field];
			} else {
				$value = NULL;
			} // if

			// Check if the pkey is unique
			if($spec['pkey'] && !$spec['auto_increment']){
				$tot = $this->getCount("$field = '$value'");
				if ($tot > 0){//La pkey esiste già
				$this->errors[] = "Primary key $field with value $value already present";
				}
			}

			$value = $this->std_fieldvalidation($field, $value, $spec);

			if (strlen($value) > 0) {
				$temparray[$field] = $value;
			} // if

		} // foreach

		return $temparray;

	} // std_fieldvalidation_insert

	/***************************************
	Modify the array $fieldarray and make it ready for an UpdateRecord call.
	If errors are found, store them in $errors. 
	****************************************/
	function std_fieldValidation_update ($fieldarray)
	{

		// Initialize errors array
		$this->errors = array();

		$temparray = array();

		foreach ($fieldarray as $field => $value) {
			$spec = $this->fieldspec[$field];

			// Validate a single value
			$value = $this->std_fieldvalidation($field, $value, $spec);

			if (strlen($value) > 0) {
				$temparray[$field] = $value;
			} else {
				$temparray[$field] = NULL;
			} // if

		} // foreach

		return $temparray;

	} // std_fieldValidation_update

	/***************************************
	Modify the array $fieldarray and make it ready for a SearchArray call.
	If errors are found, store them in $errors. 
	****************************************/
	function std_fieldValidation_search ($fieldarray)
	{

		// Initialize errors array
		$this->errors = array();

		$temparray = array();

		foreach ($fieldarray as $field => $value) {
			$spec = $this->fieldspec[$field];

			// Set up a data value
			if($this->fieldspec[$field]['type'] == 'date')
			$value = $this->std_validateDate($field, $value, $spec);
			// Set up a text value
			if($this->fieldspec[$field]['type'] == 'varchar' || $this->fieldspec[$field]['type'] == 'text' )
			$value = addslashes($value);

			if (strlen($value) > 0) {
				$temparray[$field] = $value;
			} else {
				$temparray[$field] = NULL;
			} // if

		} // foreach

		return $temparray;

	} // std_fieldValidation_update


	/**********************************************************************
	Check if the value is correct. Compare with the data stored in fieldspec.
	Add errors to $errors. check if the keys are unique keys too
	**********************************************************************/
	function std_fieldvalidation ($fieldname, $fieldvalue, $fieldspec)
	// standard function for validating database fields
	{
		global $dateobj;

		// Set up the value
		$fieldvalue = trim($fieldvalue);


		if (strlen($fieldvalue) == 0) {
			// Check if the field can be null
			if ($fieldspec['required'] && !$fieldspec['auto_increment']) {
				$this->errors[$fieldname] = "$fieldname cannot be null";
			} // if

		} else {

			// Check the max size
			if ($fieldspec['size']) {
				$size = (int)$fieldspec['size'];
				if (strlen($fieldvalue) > $size) {
					$this->errors[$fieldname] = "$fieldname Cannot be greater than $size ";
				} // if
			} // if

			// check if the value has to be uppercase
			if (isset($fieldspec['uppercase'])) {
				$fieldvalue = strtoupper($fieldvalue);
			} // if
			// check if the value has to be lowercase
			if (isset($fieldspec['lowercase'])) {
				$fieldvalue = strtolower($fieldvalue);
			} // if

			// Set up a string type
			if ($fieldspec['type'] == 'varchar' OR $fieldspec['type'] == 'text') {
				$fieldvalue = addslashes($fieldvalue);
			} // if

			// If the field is a password check the value with the hash
			if (isset($fieldspec['password'])) {
				if (isset($fieldspec['hash'])) {
					switch($fieldspec['hash']){
						case 'md5':
						$fieldvalue = md5($fieldvalue);
						break;
						case 'sha1':
						$fieldvalue = sha1($fieldvalue);
						break;
						case 'custom':
						break;
						default:
						$this->errors[$fieldname] = "$fieldname: 'hash' not correct";
					} // switch
				} else {
					$this->errors[$fieldname] = "$fieldname:'hash' is missing";
				} // if
			} // if

			if ($fieldspec['type'] == 'float' ||  $fieldspec['type'] == 'double') {
				$double = doubleval($fieldvalue);
				if ((string)$fieldvalue <> (string)$double)
				$this->errors[] = "Value not numeric";
			}

			// check that the date is correct
			if ($fieldspec['type'] == 'date') {
				$fieldvalue = $this->std_validateDate($fieldname, $fieldvalue, $fieldspec);
			} // if

			// check that the time is correct
			if ($fieldspec['type'] == 'time') {
				$fieldvalue = $this->std_validateTime($fieldname, $fieldvalue, $fieldspec);
			} // if

			// for integer another function must be called
			$fieldvalue = $this->std_validateInteger($fieldname, $fieldvalue, $fieldspec);

		} // if


		// Check if a unique key has been modify
		if (!empty($this->unique_keys)) {
			foreach ($this->unique_keys as $key) {
				if($fieldname == $key){
					$count = $this->getCount("$fieldname = '$fieldvalue'");
					if($count > 0)
					$this->errors[] = "A record ($fieldname) already exist with that value ($fieldvalue).";
				} //if
			} // foreach
		} // if

		return $fieldvalue;

	} // std_fieldvalidation

	/**********************************************************************
	Validate a data type and change it from DD-MM-YYYY to MM-DD-YYYY 
	**********************************************************************/
	function std_validateDate ($field, $value, $spec)
	{

		$pattern = '(^[0-9]{1,2})' // 1 or 2 digits
		. '([^0-9a-zA-Z])' // not alpha or numeric
		. '([0-9]{1,2})' // 1 or 2 digits
		. '([^0-9a-zA-Z])' // not alpha or numeric
		. '([0-9]{1,4}$)'; // 1 to 4 digits
		if (ereg($pattern, $value, $regs)) {
			$value = "{$regs[5]}-{$regs[3]}-{$regs[1]}";
		} // if
		else{
			$this->errors[] = "Date ($value)  not valid ($field) . Date format has to be: DD-MM-YYYY";
		}
		if(!checkdate($regs[3],$regs[1],$regs[5]))
		$this->errors[] = "Date ($value) not valid($field) ";

		return $value;

	}

	/**********************************************************************
	Validate a time format
	**********************************************************************/
	function std_validateTime ($field, $value, $spec)
	{
		$pattern = '(^[0-9]{2})' // 2 digits
		. '([^0-9a-zA-Z])' // not alpha or numeric
		. '([0-9]{2})' // 2 digits
		. '([^0-9a-zA-Z])' // not alpha or numeric
		. '([0-9]{2}$)'; // 2 digits
		if (ereg($pattern, $value, $regs))
		$value = "{$regs[1]}:{$regs[3]}:{$regs[5]}";


		if ($regs[1] > 24) {
			$this->errors = 'Hour not valid in $field($value)';
		} // if

		if ($regs[3] > 59) {
			$this->errors = 'Minutes not valid in $field($value)';
		} // if

		if ($regs[5] > 59) {
			$this->errors = 'Seconds not valid in $field($value)';
		} // if

		return $value;

	}

	/**********************************************************************
	Validate an integer
	**********************************************************************/
	function std_validateInteger ($field, $value, $spec)
	{
		$pattern = '(int1|tinyint|int2|smallint|int3|mediumint|int4|integer|int8|bigint|int)';
		if (preg_match($pattern, $spec['type'], $match)) {

			//Check if the value can be used as an integer
			$integer = (int)$value;
			if ((string)$value <> (string)$integer) {
				$this->errors[] = "Value is not an integer";
				return $value;
			} // if
			// set the max and min value
			switch ($match[0]){
				case 'int1':
				case 'tinyint':
				$minvalue = -128;
				$maxvalue =  127;
				break;
				case 'int2':
				case 'smallint':
				$minvalue = -32768;
				$maxvalue =  32767;
				break;
				case 'int3';
				case 'mediumint':
				$minvalue = -8388608;
				$maxvalue =  8388607;
				break;
				case 'int':
				case 'int4':
				case 'integer':
				$minvalue = -2147483648;
				$maxvalue =  2147483647;
				break;
				case 'int8':
				case 'bigint':
				$minvalue = -9223372036854775808;
				$maxvalue =  9223372036854775807;
				break;
				default:
				$this->errors[] = "Integer type unknown ($match)";
				return $value;
			} // switch

			// If it's unsigned build a new range
			if ($spec['unsigned']) {
				$minvalue = 0;
				$maxvalue = ($maxvalue * 2) +1;
			} // if

			// Overwrite a minvalue if specified
			if (isset($spec['minvalue'])) {
				$minvalue = (int)$spec['minvalue'];
			} // if
			if ($integer < $minvalue) {
				$this->errors[$field] = "value too low ($minvalue)";
			} // if
			// Overwrite a maxvalue if specified
			if (isset($spec['maxvalue'])) {
				$maxvalue = (int)$spec['maxvalue'];
			} // if
			if ($integer > $maxvalue) {
				$this->errors[$field] = "value too high ($maxvalue)";
			} // if

			if (isset($spec['zerofill'])) {
				while (strlen($value) < $spec['size']){
					$value = '0' .$value;
				} // while
			} // if
		} // if

		return $value;

	} // std_validateInteger

	/**********************************************************************
	Check that the element we're going to delete are not in relation. Show an error if relation
	type is RESTRICTED, nullify all the extern keys if relation is NULLIFY, delete all the extern 
	keys if the type is DELETE. Errors will be stored in the $errors array. This function must be called
	before a delete.
	**********************************************************************/
	function checkRelations($arrayToDelete){

		foreach ($this->relationship as $reldata) {
			foreach($arrayToDelete as $dataToDelete){
				switch ($reldata['type']){
					case 'restricted':
					// delete cannot be done if relation is RESTRICTED
					$where = NULL;
					foreach ($reldata['fields'] as $one => $many) {
						$where .= "$many='$dataToDelete[$one]' AND ";
					} // foreach
					$where = rtrim($where, ' AND');
					// check if the element are present in the db
					global $dbconnect, $query;
					$query = "SELECT count(*) AS tot FROM {$reldata['many']} WHERE $where ";
					$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
					$row = mysql_fetch_array($result);
					if ($row[tot] != 0) {
						$this->errors[] = "There are {$row[tot]} elements in the table ".strtoupper($reldata['many']);
						return; }
						break;
						case 'delete':
						case 'nullify':
						break;
						default:
						$this->errors[] = "Relation type unkwnown: " .$reldata['type'];
				} // switch
			} // FORAECH
		} // foreach

		foreach ($this->relationship as $reldata) {
			foreach($arrayToDelete as $dataToDelete){
				switch ($reldata['type']){
					case 'nullify':
					// set foreign key(s) to null
					$where  = NULL;
					$update = NULL;
					foreach ($reldata['fields'] as $one => $many) {
						$where  .= "$many='$dataToDelete[$one]' AND ";
						$update .= "$many=NULL,";
					} // foreach
					$where  = rtrim($where, ' AND');
					$update = rtrim($update, ',');
					// set up query to update the database
					$query = "SELECT count(*) AS tot FROM {$reldata['many']} WHERE $where ";
					$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
					$row = mysql_fetch_array($result);
					$query = "UPDATE {$reldata['many']} SET $update WHERE $where";
					$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
					echo "<br>{$row[tot]} Nullificati dalla tabella {$reldata['many']} poichè in relazione di tipo NULLIFY<br>";
					break;
					case 'delete':
					// delete all related rows
					$where = NULL;
					foreach ($reldata['fields'] as $one => $many) {
						$where .= "$many='$dataToDelete[$one]' AND ";
					} // foreach
					$where = rtrim($where, ' AND');
					// set up query to update the database
					$query = "SELECT count(*) AS tot FROM {$reldata['many']} WHERE $where ";
					$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
					$row = mysql_fetch_array($result);
					$query = "DELETE FROM {$reldata['many']} WHERE $where";
					$result = mysql_query($query, $dbconnect) or trigger_error("SQL", E_USER_ERROR);
					echo "<br>{$row[tot]} Eliminati dalla tabella {$reldata['many']} poichè in relazione di tipo DELETE<br>";
					break;
					case 'restricted':
					break;
					default:
					$this->errors[] = "Tipo di relazione sconosciuta: " .$reldata['type'];
				} // switch
			} // FOREACH
		} // foreach

	} // checkRelations

	/************************************************************
	Helper function of getData. Set up an array selected from mysql
	************************************************************/

	function formatGet($row){
		foreach($row as $key=>$value){
			SWITCH($this->fieldspec[$key]['type']){
				CASE "text":
				CASE "varchar":
				$row[$key] = (stripslashes($value));
				break;
				CASE "date": //Change date format
				$pattern = '(^[0-9]{4})' // 4 digits
				. '([^0-9a-zA-Z])' // not alpha or numeric
				. '([0-9]{1,2})' // 1 or 2 digits
				. '([^0-9a-zA-Z])' // not alpha or numeric
				. '([0-9]{1,2}$)'; // 1 to 2 digits
				ereg($pattern, $value, $regs);
				$row[$key] = "{$regs[5]}-{$regs[3]}-{$regs[1]}";
				break;
				default:
				break;
			}



		}

		return $row;
	}

} // DefaultTable

?>