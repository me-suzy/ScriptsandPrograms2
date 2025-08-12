<?php
# choose a banner

include_once('../includes/db_connect.php');
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.without.menu.inc.php");
include_once('../pagerank.php');
check_admin();

$result=mysql_query("SELECT * FROM dir_categories WHERE cat_id = $cat");
$category_info=mysql_fetch_object($result);
$page_title=$category_info->cat_child;

$result=mysql_query("SELECT COUNT(*) AS total FROM dir_site_list WHERE cat_id=$cat AND site_sponsor='N'",$link);
$site_list=mysql_fetch_object($result); $total=$site_list->total;

?>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td width="100%" height="30">
            <hr>
            <h1><?php echo $category_info->cat_parent; ?><?php print(" : $category_info->cat_child"); ?> (<?php echo $total ?>)</h1></td>
        </tr>
        <tr>
            <td align=center>
            <?php
                $sub_categories=mysql_query("SELECT cat_child, cat_id FROM dir_categories WHERE cat_parent = '$category_info->cat_parent'",$link);

                while ($directory_menu=mysql_fetch_object($sub_categories)) {
                    $sub_category.="$directory_menu->cat_child, ";
                }
                $sub_category=trim($sub_category,", "); $sub_category.=".";
                print("$sub_category</p>");
                $sub_category="";
            ?>
            </td>
        </tr>
        <tr>
            <td>
            <?php
                $result=mysql_query("SELECT * FROM dir_site_list WHERE cat_id = $cat AND site_sponsor='N' ORDER BY site_id DESC ",$link);
                while ($site_list=mysql_fetch_object($result)) {
                    $votes=(isset($site_list->votes))?$site_list->votes:'0';
                    $rating=(isset($site_list->average))?$site_list->average:'0.00';
                    $grating = display_rank($site_list->site_url);
                    $num_rank = get_page_rank("http://".$site_list->site_url);
                    if ($num_rank == -1) {
                        $num_str = 'No rank!';
                    } else {
                        $num_str = $num_rank.'/10';
                    }
                    print("
                        <p>
                        <b>$site_list->site_name</b> -
                        $site_list->site_description<br>
                        <b>($site_list->site_url)</b><br>
                        <i>Hits: $site_list->clicks_counter, Rating: $rating Votes: $votes Rate It</i>
                        Google rating <img src=\"$grating\" border=0 title=\"$num_str\"></p>
                        <p></p>");
                }
            ?>
            </td>
        </tr>
    </table>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
