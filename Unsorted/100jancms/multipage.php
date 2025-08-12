<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/

// MULTIPAGING MySQL RESULTS // set number of results to display per page



// determine how many pages there will be by using ceil() and dividing total rows by pagelimit
   $pagenums = ceil ($totalrows/$pagelimit);
//	echo "$pagenums";


// if no value for page, page = 1
    if ($page==''){
        $page='1';
    }
// create a start value
    $start = ($page-1) * $pagelimit;

// blank matches found
if ($totalrows>0) {echo "<span class='maintext'>Total number of search results: <span class='maintext3B'>" . $totalrows . "</span></span>";}


//Showing Results **********************

// Showing Results 1 to 1 (or if you're page limit were 5) 1 to 5, etc.
$starting_no = $start + 1;

if ($totalrows - $start < $pagelimit) {
   $end_count = $totalrows;
} elseif ($totalrows - $start >= $pagelimit) {
   $end_count = $start + $pagelimit;
}

  
if ($totalrows>0) {echo "<span class='maintext'>. Results <span class='maintext3B'>$starting_no</span> to <span class='maintext3B'>$end_count</span> shown.<br><br></span>";}
echo "<table width='".$table_width."' border='0' cellspacing='0' cellpadding='0' class='maintext3B'><tr class='maintext3B'><td>"; //tabele open


// create dynamic next, previous, and page links

/* lets say you're set to show 5 results per page and your script comes out with 7 results.
this will allow your script to say next2 if you're on the first page and previous5 if you're on the second page. */

if ($totalrows - $end_count > $pagelimit) {
   $var2 = $pagelimit;
} elseif ($totalrows - $end_count <= $pagelimit) {
   $var2 = $totalrows - $end_count;
}

$space = "&nbsp;";

// previous link (make sure to change yourpage.php to the name of your page)
if ($page>1) {
        echo "<a href='".$thispage."?pagelimit=$pagelimit&page=".($page-1)."' > Previous" . $space . $pagelimit . "</a>" . $space . $space . $space . $space ."";
    }

// dynamic page number links (make sure to change yourpage.php to the name of your page)

    for ($i=1; $i<=$pagenums; $i++) {
        if ($i!=$page) {
            echo " <a class='newslist2' href='".$thispage."?pagelimit=$pagelimit&page=$i' >&nbsp;$i&nbsp;</a>";
        }
        else {
            echo "&nbsp;<b class='maintext3'>&nbsp;$i&nbsp;</b>";
        }
    }


// next link 

    if ($page<$pagenums) {
        echo "" . $space . $space . $space . $space . " <a href='".$thispage."?pagelimit=$pagelimit&page=".($page+1)."' >Next " . $var2 . "</a>";
    }

// second query, here we limit results per page
$query=$MySQLQuery." LIMIT ".$start.",".$pagelimit;
$result=mysql_query($query);
$num=mysql_numrows($result); //how many rows

 echo "</td></tr></table>"; //table close
?>