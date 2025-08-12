<?php 

/*  
    Position Sorting Functions
 	(c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function insert_position($table_name, $newPosition, $position, $where, $insertId) {

// function renumbers 'position' column values after a record insertion

// $table_name = name of table that will be sorted
// $insertId = id of the new or changed record
// $newPosition = position of the new or changed record
// $position = array of 'ids' all records to sort,  less the one just inserted or changed
// $where = SQL where clause for performing update. Use "1=1" if all records in the table are to be updated


	$count = 1;
    foreach ($position as $value){
	  
	    //insert new Category position before a category with the same position value
		
		if ($count == $newPosition) {
			$newpos[] = $insertId;
			$newpos[] = $value;
		}
		else {
			$newpos[] = $value;
		}
		$count++;
	}
    
	// insert the new Category id at the end if it was given that position
	$records = count($position) + 1;  //total number of records including updated or inserted one
	$count = count($newpos); // number of records just processed.
	if ($count < $records) {
	$newpos[] = $insertId;
	}
	
	
	//renumber sequentially beginning with "1"
    $count = 0;
	$renumber = 1;
	$db2 = new DB();
	while($count < $records){
	    
	    $db2->query("UPDATE ". DB_PREPEND . $table_name . " SET position='$renumber' WHERE id='$newpos[$count]' AND $where ");
		
		$count++;
		$renumber++;
		
	} // while	
	$db2->close();
	return TRUE;
}

function sort_position($table_name, $where, $parentId ) {

// function renumbers 'position' column values after a record deletion or move

// $table_name = name of table that will be sorted
// $where = SQL where clause for performing update. Use "1=1" if all records in the table are to be sorted
// $parentId = the child pages to be sorted

	$db = new DB();
	$db->query("SELECT id FROM ". DB_PREPEND . $table_name . " WHERE $where and parentId='$parentId' ORDER BY position ");
	$test = $db->num_rows();
	
	//nothing to sort
	if ($test < 2) {
		
		return;
	}
	
	

	//renumber sequentially beginning with "1"
    $count = 0;
	$renumber = 1;
	$db2 = new DB();
	while ($i = $db2->next_record() ) {
	    $id = $i['id'];
	    $db2->query("UPDATE ". DB_PREPEND . $table_name . " SET position='$renumber' WHERE id='$id' AND $where ");
	    $count++;
		$renumber++;
		
	} // while	
	$db->close();
	$db2->close();
	return TRUE;
}

	
?>