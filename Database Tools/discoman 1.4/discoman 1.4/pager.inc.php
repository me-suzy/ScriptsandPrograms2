<?
 $limit = 20; // Default results per-page.

 if(empty($_GET['page']))$_GET['page'] = 0; // Default page value
 $pages = intval($numrows/$limit); // Number of results pages.
 if($numrows%$limit) $pages++; // has remainder so add one page
 $current = ($_GET['page']/$limit) + 1; // Current page number.
 if(($pages < 1) || ($pages == 0))
      $total = 1; // If $pages is less than one or equal to 0, total pages is 1.
 else
     $total = $pages; // Else total pages is $pages value.
 $first = $_GET['page'] + 1; // The first result.
 if(((($_GET['page'] + $limit) / $limit) >= $pages) && $pages != 1)
         $last = $_GET['page'] + $limit; //If not last results page, last result equals $page plus limit.
else
    $last = $numrows; // If last results page, last result equals total number of results.";

?>