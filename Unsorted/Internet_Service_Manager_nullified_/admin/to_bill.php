<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="billing"; //this is the section, needs to be fed to the auth script or it will assume it is general, all admin access.

include "header.php";

  ?>
  <script language="JavaScript">
			<!--
			var dfgdfgfdg;

   function pop_project(id){
     window.name='opener';
     dfgdfgfdg=window.open('pop_project_details.php?project_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


  }



			//-->
</script>
        <?

echo '<font face="'.$admin_font.'" size="2"><B><BR>The following projects are ready to have some or all of their cost invoiced.';
echo '<P><table width=100%>';
echo '<tr bgcolor="'.$admin_color_2.'"><td><font face="'.$admin_font.'" size="2"><B>ID</td><td><font face="'.$admin_font.'" size="2"><B>Project Name</td><td><font face="'.$admin_font.'" size="2"><B>Client</td><td><font face="'.$admin_font.'" size="2"><B>Bill Stages</td><td><font face="'.$admin_font.'" size="2"><B>Actions</td></tr>';

$projects=mysql_query("SELECT * from projects");

while($pro=mysql_fetch_array($projects)){
     $billnow=0;

     if($pro[bill_in]==2){
         //just one or more stages needs to be completed to do some billing..
            $stageno=mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE project_id='".$pro[id]."' && billed='0' && completed='1'"));
            if($stageno>0){
                      //there are some..
                      $billnow=1;
            }
     }else{
     //all the stages of the project need to be completed..
            $stageno=mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE project_id='".$pro[id]."' && billed='0' && completed='1'"));
           if($stageno==mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE project_id='".$pro[id]."' && billed='0'"))){
                      $billnow=1;
           }
     }
     
     if($billnow){
      echo '<tr><td><font face="'.$admin_font.'" size="2">'.$pro[id].'</td><td><font face="'.$admin_font.'" size="2"><a href="javascript: pop_project('.$pro[id].')" class="left_menu">'.$pro[project_name].'</a></td>';
      $client=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='".$pro[client_id]."'"));
      echo '<td><font face="'.$admin_font.'" size="2">'.$client[name].'</td>';
            $stages_to_bill=mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE project_id='".$pro[id]."' && billed='0' && completed='1'"));
            $total_stages=mysql_num_rows(mysql_query("SELECT * FROM project_stages WHERE project_id='".$pro[id]."'"));
      echo '<td><font face="'.$admin_font.'" size="2">'.$stages_to_bill.'/'.$total_stages.'</td>';
      echo '<td><font face="'.$admin_font.'" size="2"><a href="create_invoice.php?client_id='.$pro[client_id].'&project_id='.$pro[id].'" class="left_menu">Invoice Now</a></td>';
      echo '</tr>';
     }
}

echo '</table<';
include "footer.php";
?>
