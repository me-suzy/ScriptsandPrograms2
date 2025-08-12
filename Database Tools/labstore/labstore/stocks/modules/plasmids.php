<?php
$table='plasmids'; // mysql table name
$head_extra = ''; // extra to include in HTML before </head>; style, javascript
$heading_color = '#82CAFA'; // table column heading and page number list row
$row_color_1 = '#bdedff'; // alternate row colors
$row_color_2 = '#ffffff'; // alternate row colors
$extra_bottom = ''; // extra, will appear at bottom; message
$column_nos = '6'; // number of columns - for colspan table rows (page number row, etc.)
// header
include ("../header.php");
echo ('<p><span style="color:#dcdcdc;">'.$header_to_show.' || <a href="../help/help.htm" onclick="return popitup(\'../help/help.htm\')">Help</a> || <a>'.$date.'</a></span></p></div><div style="padding-left: 5px;">');
// array of options and values for use with search and sort forms below
$option_value = array(
'name'=>'Name',
'description'=>'Description',
'owner'=>'Owner/creator',
'source'=>'Source',
'generation'=>'Generation technique',
'tag'=>'Tag encoded',
'mam_prom'=>'Mammalian promoter',
'other_prom'=>'Other promoters',
'bact_sel'=>'Bac. selection marker',
'other_sel'=>'Other selection marker',
'feature'=>'Other features',
'experiment'=>'Experiment info.',
'comment'=>'Comments',
'modified_on'=>'Update date',
'added_on'=>'Add date',
'modified_by'=>'Updater',
'added_by'=>'Adder'
);
///////////////////////////////////////////////////////////////////
// build upper table
echo ('<table width="750" summary="top" cellpadding="5" cellspacing="1" style="background-color:#dcdcdc; border:0;  background-image:url(\'../images/plasmids_small.gif\'); background-position: top right;background-repeat: no-repeat;"><tr><td>');
include ("../top_part.php");
if ($num_sat !==0) // IF START ######################
{
// column headings
echo ('<colgroup><col width="40" valign="top" align="left" /><col valign="top" align="left" /><col valign="top" align="left" /><col valign="top" align="left" /><col valign="top" align="left" /><col valign="top" width="170" align="left" /></colgroup><tr style="background-color:'.$heading_color.';" valign="top"><td></td><td >Name/description</td><td>Vector/insert</td><td >Owner/creator</td><td>Download</td><td></td></tr>');  
// start - alternate colors of table rows
for ($i = 0; $i < $numofrows; $i++) // FOR START ============
{
$row = mysql_fetch_array($result);
if ($i % 2){echo ("<tr style=\"background-color:".$row_color_1.";\" valign=\"top\">");} 
else {echo ("<tr style=\"background-color:".$row_color_2.";\" valign=\"top\">");}
  // end - alternate colors of table rows
  // serial number of item (not record ID)
  $sn = (($page-1)*$max_results)+$i+1;
  // start - build table rows  
echo (
  //---------------------------------------------------
'<td>'
.$sn
.'</td><td>'
  //---------------------------------------------------
.$row["name"]);
if ($row["description"] !='')
{
echo ('<br />'.$row["description"]);
}
  //---------------------------------------------------
echo ('</td><td>'
.$row["vector"]);
if ($row["insert"] !='')
{
echo ('<br />'.$row["insert"]);
}
  //---------------------------------------------------
echo ('</td><td>'
.$row["owner"]);
  //---------------------------------------------------
echo ('</td><td>');
    // provide link to download files
if ($row["map_file"] != '')
{
       // table data is of form - 'The filepath is - table-name/file-name'
       // for plasmids, so, filename is table data minus first 27 characters
       // 'The filepath is - '=[18]'plasmids'=[+8]'/'=[+1]
 $file = substr($row["map_file"], 27);
 if (file_exists('../interface_creator/uploads/plasmids/'.$file)) 
  {echo ('Map file - <a href="../interface_creator/uploads/plasmids/'.$file.'">'.$file.'</a><br />');
  }
 else
  {echo ($file.' - this is the entry for the map file in the table, but the files does not exist!<br />');}
}
if ($row["insert_seq_file"] != '')
{
 $file2 = substr($row["insert_seq_file"], 27);
 if (file_exists('../interface_creator/uploads/plasmids/'.$file2)) 
  {echo ('Insert seq. file - <a href="../interface_creator/uploads/plasmids/'.$file2.'">'.$file2.'</a><br />');
  }
 else
  {echo ($file2.' - this is the entry for the insert seq. file in the table, but the files does not exist!<br />');}
}
if ($row["extra_file"] != '')
{
 $file3 = substr($row["extra_file"], 27);
 if (file_exists('../interface_creator/uploads/plasmids/'.$file3)) 
  {echo ('Extra file - <a href="../interface_creator/uploads/plasmids/'.$file3.'">'.$file3.'</a>');
  }
 else
  {echo ($file3.' - this is the entry for the extra file in the table, but the file does not exist!');}
}
else
{
 echo ('No file provided');
}
  //---------------------------------------------------
echo ('</td><td><span style="color:#736F6E;">');
if ($row["added_on"] !='' & $row["added_on"] !='0000-00-00')
{
echo('Added '.$row["added_on"].'<br />');
}
if ($row["modified_on"] !='' & $row["modified_on"] !='0000-00-00')
{
echo ('Updated '.$row["modified_on"].'<br />');
}
   // start - edit, detail, update links
    // detail link
echo ('<a href="../interface_creator/index_short.php?table_name=plasmids&amp;function=details&amp;where_field=id&amp;where_value='.$row["id"].'" onClick="return popitup(\'../interface_creator/index_short.php?table_name=plasmids&amp;function=details&amp;where_field=id&amp;where_value='.$row["id"].'\')">Details</a>'
);
    // edit and delete link
if (!($all_affect_items == "no" and $client == "not_allowed"))
{echo (' | <a href="../interface_creator/index_short.php?table_name=plasmids&amp;function=edit&amp;where_field=id&amp;where_value='.$row["id"].'" onClick="return popitup(\'../interface_creator/index_short.php?table_name=plasmids&amp;function=edit&amp;where_field=id&amp;where_value='.$row["id"].'\')"> Edit</a> | <a href="../interface_creator/index_short.php?table_name=plasmids&amp;function=delete&amp;where_field=id&amp;where_value='.$row["id"].'" onClick="return confipop(\'../interface_creator/index_short.php?table_name=plasmids&amp;function=delete&amp;where_field=id&amp;where_value='.$row["id"].'\')"> Delete</a>');}
   // end - edit, detail, update links
  //---------------------------------------------------
echo ('</span></td></tr>');
  // end - build table rows  
} // FOR END ==================================================================
///////////////////////////////////////////////////////////////////////////
include ("../bottom_part.php");
} // IF END ##############################
include ("../footer.php");
?>