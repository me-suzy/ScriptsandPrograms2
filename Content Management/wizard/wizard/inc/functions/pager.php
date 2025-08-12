<?php 
/*  
   Database results pagination
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

function pager($selectClause, $whereClause, $pageRoot,$linkable,$editPage=1,$tableName,$default_sort, $rank, $tableWidth,$columns,$col_alias,$col_widths,$col_align,$limit,$bgcolor,$altbgcolor,$headrow,$tblHeadBg="",$sorting=1) {



//variables passed to this function
	
	//default column for sorting
	$order = $_GET['order'];
	if ($order=="") {
	    $order = $default_sort;
	}
    
	
//total number of rows in table
    $db = new DB();
    $db->query("SELECT count(*) FROM $tableName $whereClause");     
	$i = $db->next_record();
	$totalrows   = $i[0];
	
	
	
	
//for paging, check to see if this is the first time this been run
    $page = $_GET['page'];
	if(empty($page)){ 
        $page = 1; 
    } 

	
	
/* construct and run our query */
	$db = new DB();
	$limitvalue = $page * $limit - ($limit); 
	$row = $db->query("SELECT $selectClause FROM $tableName $whereClause ORDER BY $order $rank LIMIT $limitvalue, $limit");



/* make sure data was retrieved */
$dataempty = $db->num_rows();
if ($dataempty == 0) {
	    echo "<p align=\"center\">&nbsp;</p>"; 
    	echo "<p class=\"message\" align=\"center\">No results to display!</p>";
    	echo "<p align=\"center\">&nbsp;</p>"; 
		return;
	}
else {
    echo "<div align=\"center\"><center>";
	echo("<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\"  width=\"$tableWidth\">"); 
	echo "<tr><td>";
	echo("<table bgcolor=\"$altbgcolor\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\"  width=\"$tableWidth\">"); 
    
	//number of columns to determine colspan for the empty row
	$num_columns = count($columns);
	
    if ($sorting == 1) {
	    
        echo "<br /><p align=\"left\" class=\"message\">&nbsp;&nbsp;Use first column to edit record, other column headings to sort.</p>";
    }
	
	
	//Header row with column names 
	echo "<tr bgcolor=\"".$headrow."\">";
	$count = 0;
	foreach ($columns as $column){
		echo "<td class=\"smallText\" background=\"$tblHeadBg\" align=\"$col_align[$count]\" width=\"$col_widths[$count]\">";
		if ($sorting) { 
				echo "<a href=\"". $pageRoot . "&page=1&order=$column\"><b>$col_alias[$count]</b></a>";
			} 
		else { 
				echo "<b>$col_alias[$count]</b>";
			}
		echo "</td>";
		$count++;
	}
	echo "</tr>";
	
	

	// Table rows
   
	while($row = $db->next_record()){ 
	
        if ($rowcolor == $bgcolor){ 
            $rowcolor = $altbgcolor;  
        }else{ 
            $rowcolor = $bgcolor; 
        } 
    	echo "<tr bgcolor=\"".$rowcolor."\">";
		    $count = 0;
			foreach ($columns as $column){
			//check to see if this is the first column, if so create a link to an editing page
			if ($count == 0 && $linkable == 1) {
			   
			   echo "<td class=\"smallText\" align=\"$col_align[$count]\" width=\"$col_widths[$count]\"><a href=\"$editPage&colname=$columns[0]&$columns[0]=$row[$column]\">$row[$column]</a></td>"; 
			}
			else {
			echo "<td class=\"smallText\" align=\"$col_align[$count]\" width=\"$col_widths[$count]\">$row[$column]</td>"; 
			}
			$count++;
			} // foreach
    	echo "</tr>" ; 
    } //while

    echo("</table></td></tr>"); 

 
if ($totalrows < $limit) {
    // do not show if the rows to show is less than the display limit	
}
else {
//Page Navigation Links
	echo("<table align=\"center\" bgcolor=\"$altbgcolor\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\" width=\"" . ($tableWidth - 4) ."\"  >"); 
	echo "<tr><td>";
	echo("<table align=\"left\" bgcolor=\"$altbgcolor\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\"   >"); 
    echo "<tr class=\"smallText\">";
	
	//Previous arrow link
	$page = $_GET['page'];
	if(empty($page)){ 
        $page = 1; 
    }  
	if($page != 1){ 
        $pageprev = $page-1; 
         
        echo("<td align=\"left\" class=\"tinyText\"><a href=\"". $pageRoot . "&page=$pageprev&order=$order\">PREV&nbsp;</a></td>"); 
		echo "<td align=\"left\" ><a href=\"". $pageRoot . "&page=$pageprev&order=$order\"><img alt=\"Previous Page\" src=\"admin/images/pageleft.gif\" width=\"12\" zborder=\"0\"></a></td>";

    }
	

	
	//Page Numbers	
    echo "<td align=\"left\" >";
    $numofpages = $totalrows / $limit; 
     
    for($i = 1; $i <= $numofpages; $i++){ 
        if($i == $page){ 
            echo($i."&nbsp;"); 
        }else{ 
            echo("<a href=\"". $pageRoot . "&page=$i&order=$order\"><font color=\"Red\">$i</font></a>&nbsp;"); 
			if (($i % 25) == 0) {
			    echo "<br />";
			}
        } 
    } 
    

	// Last Page Number
	
    if(($totalrows % $limit) != 0){ 
        if($i == $page){ 
            echo($i."&nbsp;"); 
        }else{ 
            echo("<a href=\"". $pageRoot . "&page=$i&order=$order\"><font color=\"Red\">$i</font></a>&nbsp;"); 
        } 
    } 
	echo "</td>";
	
	
	//Next
    if(($totalrows - ($limit * $page)) > 0){ 
        $pagenext = $page+1; 
        echo "<td align=\"left\" ><a href=\"". $pageRoot . "&page=$pagenext&order=$order\"><img alt=\"Next Page\" src=\"admin/images/pageright.gif\" width=\"12\" height=\"13\" border=\"0\"></a></td>"; 
        echo("<td align=\"left\" class=\"tinyText\"><a href=\"". $pageRoot . "&page=$pagenext&order=$order\">NEXT&nbsp;</a><td>"); 
    }
	
	echo("</tr></table>");
	echo "</td></tr>"; 
	echo("</table>");
	$db->close();
	} // total rows is > limit
	
    
	echo "</td></tr>";
	echo("</table>");
	echo "</center></div>";
		
    
	
} // no results to display

return;
} // function
?>