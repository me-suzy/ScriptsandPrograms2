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
<?
/**
search boxes
*/

function updateBox($boxID, $name, $htmlCode){
	global $dbObj ;
	$dbSet=new xxDataset($dbObj);
	
	$table="affsearchboxes";
    $columnID = "searchBoxID";
    
    $dbSet->execute("UPDATE $table SET searchBoxName='".$name."', htmlCode='".$htmlCode."'
    	WHERE $columnID=$boxID");
}

function updateBoxFromResult($boxID, $result){
	global $dbObj ;
	$dbSet=new xxDataset($dbObj);
	
	$table="affsearchboxes";
    $columnID = "searchBoxID";
    
	$strSet="";
   	makeUpdateList($strSet,$result,array("itemID","searchBoxID" ));
    $id = $dbSet->execute("UPDATE ".$table." SET $strSet WHERE $columnID = $boxID");

}

function createBoxFromResult($result){
	global $dbObj ;
	$dbSet=new xxDataset($dbObj);
	
	$table="affsearchboxes";
    $columnID = "searchBoxID";
    
	$strColumns=$strValues="";
   	makeInsertList($strColumns,$strValues,$result,array("itemID", "searchBoxID" ));
    $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.") 
		VALUES (".$strValues.")");
	return $id;

}

function getSearchBoxes(){
	global $dbObj ;
	$dbSet=new xxDataset($dbObj);
	
	$table="affsearchboxes";
    $columnID = "searchBoxID";

    $items = array();    
      $dbSet->open("SELECT * 
     	 FROM ".$table." asb"
     );
			 
        $i=0;
        while($row = $dbSet->fetchArray()) {
                $items[$i] = $row;
                $i++;
        }

	return $items;

}

function translateSearchBox($affiliateID, $htmlCode){
	global $PHP_SELF, $DOCUMENT_ROOT, $SERVER_NAME;
	
	$selfURL = "http://".$SERVER_NAME."".$PHP_SELF;

	$htmlCode = "<form action='$selfURL'> \n <input type='hidden' name='mode' value='search'> \n <input type='hidden' name='format' value='HTML'> \n
<input type='hidden' name='affiliateID' value=$affiliateID>".$htmlCode."</form>";
	return $htmlCode;

}


?>