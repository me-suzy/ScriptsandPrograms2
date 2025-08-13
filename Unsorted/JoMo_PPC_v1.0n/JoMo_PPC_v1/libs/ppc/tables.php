<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
/**
*/

class CTable{
	var $tableColumns;
	var $viewColumns;
	var $tableName;
	var $columnID;  // name 

	function CTable(){
	}
	
	// return empty row
	function emptyRow(){
	}
	
	/**
	$result["columnName"]=value;
	*/
	function insertRow($result, $excludeColumns){
	}
	
	function updateRow($result, $excludeColumns){
	}

	function deleteRow($id){
	}
	
	function deleteRows($filter){
	}
	
	function getRows($filter){
	}
	
	/**
	return row with $columnID=$id or return empty row
	*/
	function getRow($id){
	}
	
};


// init
CBannerTable tableBanner = new CTable