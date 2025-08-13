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

## Validate that the user is an ADMIN or log them out
if (!testlogin()||!$this_admin||$this_user)  { Header("Location: http://$standard_url?op=logout"); exit; }

$this_page = "$page?op=menu&tile=faq_config";
$faq_name  = FAQ;
$search_color = "RED";

if(!$dbh)dbconnect();
$num_categories=$num_questions=0;
list($num_categories)=mysql_fetch_row(mysql_query("SELECT count(cid) FROM faq_categories"));
list($num_questions)=mysql_fetch_row(mysql_query("SELECT count(fid) FROM faq_questions"));
?>
<tr>
  <td>
    <table cellpadding=0 cellspacing=0 border=0 align=center width=100%>
     <tr><td><?=LFH?><b><?=FAQSTATS?>:</b><?=EF?></td><td><?=LFH?><b><?=FAQSEARCH?>:</b><?=EF?></td></tr>
     <tr>
       <td width=50% valign=top>
         <table>
          <tr><td>
               <?=MFB?>
               <?=FAQCATEGORIES?>:<br>
               <?=FAQQUESTIONS?>:<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               [<a href=<?=$page?>?op=view&db_table=faq_categories&tile=<?=$tile?>><?=$num_categories?></a>]<br>
               [<a href=<?=$page?>?op=view&db_table=faq_questions&tile=<?=$tile?>><?=$num_questions?></a>]<br>
               <?=EF?>
              </td>
              <td align=right>
               <?=MFB?>
               [<a href=<?=$page?>?op=form&db_table=faq_categories&tile=<?=$tile?>><b><?=ADD?></b></a>]<br>
               [<a href=<?=$page?>?op=form&db_table=faq_questions&tile=<?=$tile?>><b><?=ADD?></b></a>]<br>
               <?=EF?>
              </td>
           </tr>
          </table>
       </td>
       <td width=50% valign=top>
          <table>
          <tr><td><?=search_box()?></td></tr>
          </table>
       </td>
     </tr>
    </table>
   <hr size=1 width=98%>
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
  </td>
</tr>