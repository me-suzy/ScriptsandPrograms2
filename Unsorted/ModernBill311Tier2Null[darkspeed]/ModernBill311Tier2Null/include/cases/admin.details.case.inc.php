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


/* ---- DISPLAY ALL DETAILS ----*/
// THIS IS THE MASTER CASE THAT WILL HANDLE ALL DETAILS DISPLAY
        validate_table($db_table,1); if(isset($error)) return;
        $id = explode("|",$id);
        $sql = "SELECT * FROM $db_table WHERE $id[0]='$id[1]'";
        if ($debug)echo SFB.$sql.EF."<br>";
        addslashes($result = mysql_query($sql,$dbh));

        // Build Display Dynamically
        start_html();
        admin_heading($tile);
        start_table("$title [".ID.": ".$id[1]."]",$a_tile_width);
        build_form($args,$result);
             echo "<tr><td colspan=2 valign=top align=center>";
             echo SFB."<a href=\"$page?op=form&db_table=$db_table&tile=$tile&id=$id[0]|$id[1]\">".EDIT_IMG."</a>".EF;
             if (!$disable_delete) echo "&nbsp;".SFB."<a href=\"$page?op=delete&db_table=$db_table&tile=$tile&&id=$id[0]|$id[1]\">".DELETE_IMG."</a>".EF;
             echo "</td></tr>";
             // IF there are children, display them
             if ($children&&count($children)>0) {
                 echo "<tr><td colspan=2><hr size=1></td></tr>";
                 echo "<tr><td colspan=2>";
                 $i=0;
                 if ($db_table=="client_package")
                 {
                   $sql = str_replace("*","client_id",$sql);
                   list($client_id)=mysql_fetch_array(mysql_query($sql,$dbh));
                 }
                 foreach($children as $values){
                     $recursive=1;
                     $where="WHERE $id[0]='$id[1]' ";
                     $db_table=$children[$i];
                     include("include/db_attributes.inc.php");
                     echo SFB."[<a href=$page?op=form&db_table=$db_table&$id[0]=$id[1]&client_id=$client_id><b>".ADD."</b></a>] ".EF.MFH." <b>$title</b>".EF."<br>";
                     display_list($args,$select_sql,$where,$db_table,$order,$sort,$offset,$limit);
                     echo "<hr size=1>";
                 $i++;
                 }
                 echo "</td></tr>";
             }
        stop_table();
        stop_html();
?>
