<?php
///////////
if($total_pages >1)
{
 echo ('<tr valign="top" style="background-color:'.$heading_color.';"><td colspan="'.$column_nos.'" style="align:middle;"><span style="color:#808080;">Page ' . $page. ' of ' . $total_pages . '<br />'); 
 // for 'previous'
 if($page > 1)
 {$prev = ($page - 1); echo ("<a href=\"" . $_SERVER['PHP_SELF'] . "?where_condition=".rawurlencode($where_condition)."&amp;order_condition=".rawurlencode($order_condition)."&amp;page=".$prev."\">&laquo;Previous</a>&nbsp;");
 } 
 // for rest of pages
 for($i = 1; $i <= $total_pages; $i++)
 {
  if($i == $page)
  {
  echo ($i."&nbsp;");
  }
  else
  {
  if(abs($i-$page) < 10)
     {
     echo ("<a href=\"" . $_SERVER['PHP_SELF'] . "?where_condition=".rawurlencode($where_condition)."&amp;order_condition=".rawurlencode($order_condition)."&amp;page=".$i."\">".$i."</a>&nbsp;");
     }
  }
 }
 // for 'next'
 if($page < $total_pages)
 {
 $next = ($page + 1);
 echo ("<a href=\"" . $_SERVER['PHP_SELF'] . "?where_condition=".rawurlencode($where_condition)."&amp;order_condition=".rawurlencode($order_condition)."&amp;page=".$next."\">Next&raquo;</a></span>");
 }
 echo ('</td></tr>');
}
//////////
echo ('</table>');
// build lower table
if ($num_tot != 0)
{
 echo ('<table width="750" summary="export" style="background-color:#dcdcdc; border:0;" cellpadding="5" cellspacing="1"><tr><td>');
  // Excel export options - ordering maintained
 echo ('<form action="../export.php" method="post"><p>
 <select single="single" name="parameter" id="parameter">
 <option value="Excel llll  ORDER BY '.$order_condition.'">Export all '.$num_tot.' entries in Excel format, or...</option>
 <option value="CSV llll  ORDER BY '.$order_condition.'">All '.$num_tot.' entries in CVS format</option>');
   // selective export only if looking at table subset - therefore too the where_condition
 if ($num_sat !== $num_tot and $num_sat !== 0)
  {
  echo ('
  <option value="Excel llll '.$where_condition.' ORDER BY '.$order_condition.'">These '.$num_sat.' entries in Excel format</option>
  <option value="CSV llll '.$where_condition.' ORDER BY '.$order_condition.'">These '.$num_sat.' entries in CVS format</option>');
  }
   // end selective export
 echo ('</select>');
   // hidden values to pass the mysql query and table name
 echo ('
<input type="hidden" name="table" id="table" value="`'.$table.'`" />
<input type="submit" name="export" id="export" value="Export" /><a href="../help/help.htm#export" onclick="return popitup(\'../help/help.htm#export\')">?</a>
 </p></form>');
  // end export options
// end lower table
 echo ($extra_bottom.'</td></tr></table>');
 }
?>