<?php
# choose a banner
include_once('../includes/db_connect.php');
check_admin();
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
include("$CONST_INCLUDE_ROOT/includes/admin_header.php");
$html_url = "$CONST_LINK_ROOT/$CONST_LINK_GENERATE";
$html_dir = "$CONST_INCLUDE_ROOT/$CONST_LINK_GENERATE";
?>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td align=center>
                <form method='post' action='<?=$CONST_LINK_ROOT?>/admin/generate_html.php'>
                    <input type="submit" class="button" value="Start Generation Process" name="START">
                </form>
            </td>
        </tr>
        <tr>
            <td>
        <?        
                if ($START){
                    ob_start();
                    include "generate_category_html.php";
                    $data = ob_get_contents();
                    ob_end_clean();
                    
                    $fd = fopen("$html_dir/sitemap.html","w");
                    fwrite($fd,$data);
                    fclose($fd);
                    echo "Category <b>list</b> was generated <b><a href=\"$html_url/sitemap.html\" target=_blank>Show</a></b><br>";

                    $categories=mysql_query("SELECT * FROM dir_categories");
                    while ($category=mysql_fetch_object($categories)) {
                        ob_start();
                        $cat = $category->cat_id;
                        include "generate_site_html.php";
                        $data = ob_get_contents();
                        ob_end_clean();
                        
                        $category->cat_child=str_replace(" ","_",$category->cat_child);
                        $category->cat_child=str_replace("/","_",$category->cat_child);
						$fd = fopen("$html_dir/$category->cat_child.html","w");
                        fwrite($fd,$data);
                        fclose($fd);
                        echo "Category <b>$category->cat_child</b> was generated <b><a href=\"$html_url/$category->cat_child.html\" target=_blank>Show</a></b><br>";
                    }
                            
                }
        ?>        
            </td>
        </tr>
        <tr>
            <td align=center>&nbsp;</td>
        </tr>
    </table>
<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>
