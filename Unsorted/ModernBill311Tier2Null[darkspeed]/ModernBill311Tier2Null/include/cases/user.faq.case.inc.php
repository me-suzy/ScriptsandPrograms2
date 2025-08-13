<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/

## Must be included ONLY once!
include_once("include/functions.inc.php");

## Validate that the user
if (!testlogin()||!$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

/////////////////////////////////

$this_page = "$page?op=faq";
$faq_name  = FAQ;
$search_color = "RED";

GLOBAL $query;

///////////////////////////////////////////
start_html();
user_heading(NULL);
start_table(NULL,$u_tile_width);

if(!$dbh)dbconnect();
$num_categories=$num_questions=0;
list($num_categories)=mysql_fetch_row(mysql_query("SELECT count(cid) FROM faq_categories"));
list($num_questions)=mysql_fetch_row(mysql_query("SELECT count(fid) FROM faq_questions"));
?>
<tr>
  <td align=center><?=MFB?><b><?=FAQSEARCH?>:</b><?=EF?> <?=search_box()?></td>
</tr>
<tr>
  <td><hr size=1 width=98%></td>
</tr>
<tr>
  <td>
  <?
  echo "<table width=100% cellpadding=4 cellspacing=4 border=0><tr><td valign=top>";
  switch ($faq_op) {
     case faq:
          faq_view($cid,$fid,$query);
          break;

     case search:
          if ($query == "")
          {
              echo MFB.NOMATCHESFOUND." [<a href=\"javascript:history.back(-1)\">Go Back</a>]".EF;
          }
          else
          {
              search_view(strip_tags($query));
          }
          break;

     default:
          index_view();
          break;
  }
  echo "</td></tr></table>";
  ?>
  <br>
  </td>
</tr>
<?
stop_table();
stop_html();
?>
