<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "header.php";
if($client_id){
    $res=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
  if($res[account_balance]<=0){$res[account_balance]=0;}
  echo '<table width="629" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td colspan="4"><font face="'.$admin_font.'" size="2"><b>Client
      Profile: '.$res[name].' (Balance: '.$payment_unit.$res[account_balance].')</b></font></td>

    <td width="115">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" height="3"></td>
  </tr>
  <tr bgcolor="#006699">
    <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="client_list.php?client_id='.$client_id.'" class="other_text">Profile</a> </div>
    </td>
    <td width="115">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="billing_overview.php?client_id='.$client_id.'" class="other_text">Billing</a> </div>
    </td>
    <td width="125">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_project.php?client_id='.$client_id.'" class="other_text">Add Project</a></div>
    </td>
    <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="edit_client.php?client_id='.$client_id.'" class="other_text">Edit Client</a></div>
    </td>
    <td width="120">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="support_overview.php?client_id='.$client_id.'" class="other_text">Support</a></div>
      </td>
      <td width="130">
      <div align="center"><font face="'.$admin_font.'" size="2"><B><a href="add_contact.php?client_id='.$client_id.'" class="other_text">Add Contact</a></div>
    </td>
  </tr>
  <tr>
    <td width="115">&nbsp;</td>
    <td width="125">&nbsp;</td>
    <td width="130">&nbsp;</td>
    <td width="120">&nbsp;</td>
    <td width="115">&nbsp;</td>
  </tr>
</table>
';

  if(!$action){
  $con=mysql_query("SELECT * FROM contacts WHERE client_id='$client_id'");
  ?>
  <script language="JavaScript">
			<!--
			var dfgdfgfdg;
			function pop_contact(id){
				window.name='opener';
				dfgdfgfdg=window.open('pop_contact.php?contact_id='+id,'popupwinPUP','width=300,height=300,scrollbars=no,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


			}
   
   function pop_project(id){
     window.name='opener';
     dfgdfgfdg=window.open('pop_project_details.php?project_id='+id,'popupwinPUP','width=500,height=500,scrollbars=yes,status=no,toolbars=no,left=125,top=200,resizable=no,menubar=no');


  }

   function deletecontact(id, client)
   {
           var ask=confirm("Are you sure you wish to delete the contact?");
           if(ask==true)
                        window.location="add_contact.php?delete="+id+"&client_id="+client;

   }
   
   function cantdeletecontact()
   {
     alert("You cant delete the primary contact, change the primary contact to delete this one!");
   }


     function deleteproject(id)
   {
           var ask=confirm("Are you sure you wish to delete this project?");
           if(ask==true)
           window.location="edit_project.php?delete="+id+"&client_id=<?echo $client_id;?>";

   }


			//-->
</script>
        <?
  echo '<table>';
    echo '<tr><td><font face="'.$admin_font.'" size=2><B>Basic Info..</B><BR></td></tr>';
                 echo '<tr bgcolor="'.$admin_color_2.'"><td height=1></td></tr>';	
	echo '<tr><td><font face="'.$admin_font.'" size=2><P>
	Client Name: '.$res[name].'<BR>
	Account Balance: '.$payment_unit.$res[account_balance].'<BR>
	Preferred Billing Method: '.$res[billing_method].'<BR>
		Outstanding Amount: ';
		$invs=mysql_query("SELECT * FROM invoices WHERE paid='0' && to_client='".$res[id]."'");
	$outstanding=0;
	while($i=mysql_fetch_array($invs)){
   $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$i[id]."'");
                                              $tpaid=0;
											  while($g=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$g[amount];
                                              }
											  $outstanding=$outstanding+$i[amount]-$tpaid;
	}
	echo $payment_unit.$outstanding;
		
		echo '<BR>
		Billing Contact: ';
		$billingc=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$res[bill_to_contact]."'"));
		echo $billingc[firstname].' '.$billingc[lastname];
		echo '<BR>
	Primary Contact: ';
		$primaryc=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$res[primary_contact]."'"));
		echo $primaryc[firstname].' '.$primaryc[lastname];
		echo '<BR>
Comments: '.nl2br($res[comments]).'
	<P>
	</td></tr>';
    
			   	
  echo '<tr><td><font face="'.$admin_font.'" size=2><B>Contacts..</B><BR></td></tr>';

        while($c=mysql_fetch_array($con)){
                 echo '<tr bgcolor="'.$admin_color_2.'"><td height=1></td></tr>';
                 echo '<tr><td><font face="'.$admin_font.'" size=2>';
                 echo $c[firstname].' '.$c[lastname];
                 $pri=0;if($res[primary_contact]==$c[id]){
                    echo ' (Primary Contact) ';
                    $pri=1;
                 }
                 echo ' <a href="email.php?contact='.$c[id].'" class="left_menu">'.$c[email].'</a>';
                 echo '&nbsp;&nbsp;<a href="javascript: pop_contact('.$c[id].')" class="left_menu">Contact Details</a>';
                 echo '&nbsp;&nbsp;<a href="edit_client.php?contact='.$c[id].'#client'.$c[id].'" class="left_menu">Edit Contact</a>';
                 if($pri){
                     echo '&nbsp;&nbsp;<a href="javascript: cantdeletecontact()" class="left_menu">DELETE CONTACT</a>';
                 }else{
                     echo '&nbsp;&nbsp;<a href="javascript: deletecontact('.$c[id].', '.$c[client_id].')" class="left_menu">DELETE CONTACT</a>';
                 }

                 echo '</td></tr>';
        }
  echo '</table>';

   echo '<P><table><tr><td><font face="'.$admin_font.'" size=2><B>Projects..</B><BR></td></tr>';

   $pro=mysql_query("SELECT * FROM projects WHERE client_id='$client_id' ORDER BY status ASC");
        while($p=mysql_fetch_array($pro)){
                 echo '<tr bgcolor="'.$admin_color_2.'"><td height=1></td></tr>';
                 echo '<tr><td><font face="'.$admin_font.'" size=2>';
                 echo $p[project_name];
                 $project_states=array(0=>"Active", 1=>"Suspended", 2=>"Completed", 3=>"Proposal");
                 echo ' <font color="red">('.$project_states[$p[status]].')</font> ';
                 echo 'Started: '.date("F j, Y, g:i a", $p[start_date]);
                 echo '&nbsp;<a class="left_menu" href="javascript: pop_project('.$p[id].')">More Details</a>';
                 echo '&nbsp;<a class="left_menu" href="edit_project.php?projectid='.$p[id].'">Edit Project</a>';
                 echo '&nbsp;<a class="left_menu" href="edit_project.php?projectid='.$p[id].'&addnew=1#newstage">Add Stage</a>';
                 echo '&nbsp;<a class="left_menu" href="javascript: deleteproject('.$p[id].')">DELETE PROJECT</a>';
                 echo '</td></tr>';
        }
  echo '</table>';

  }

}else{
      if($search || $letter){
          if($search){
                      $tit="clients whose name contains '$search'";
                      $res=mysql_query("SELECT * FROM clients WHERE name LIKE '%$search%'");
          }else{
                      $tit="clients whose name starts with '$letter'";
                      if($letter=="number"){
                          $res=mysql_query("SELECT * FROM clients WHERE name LIKE '0%' OR name LIKE '1%' OR name LIKE '2%' OR name LIKE '3%' OR name LIKE '4%' OR name LIKE '5%' OR name LIKE '6%' OR name LIKE '7%' OR name LIKE '8%' OR name LIKE '9%' ");
                      }else{
                          $res=mysql_query("SELECT * FROM clients WHERE name LIKE '$letter%'");
                      }
          }
          
          echo '<center><B><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2">Listing '.mysql_num_rows($res).' results for your search for '.$tit.'</B><P><table width=70%>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><B>Client Name</td>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><B>Active Projects</td>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><B>Total Projects</td>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><B>Client Since</td></tr>';
                   echo '<tr><td height=1 bgcolor="'.$admin_color_2.'"><td height=1 bgcolor="'.$admin_color_2.'"><td height=1 bgcolor="'.$admin_color_2.'"><td height=1 bgcolor="'.$admin_color_2.'"></tr>';
          while($r=mysql_fetch_array($res)){
                   echo '<tr>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><a class="left_menu" href="client_list.php?client_id='.$r[id].'">'.$r[name].'</a></td>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><center>'.mysql_num_rows(mysql_query("SELECT * FROM projects WHERE status='0' && client_id='".$r[id]."'")).'</td>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2"><center>'.mysql_num_rows(mysql_query("SELECT * FROM projects WHERE client_id='".$r[id]."'")).'</td>';
                   echo '<td><font face="'.$admin_font.'" color="'.$admin_font_color.'" size="2">'.date("F j, Y",$r[date_added]).'</td>';
                   echo '</tr><tr><td height=1 bgcolor="'.$admin_color_2.'"><td height=1 bgcolor="'.$admin_color_2.'"><td height=1 bgcolor="'.$admin_color_2.'"><td height=1 bgcolor="'.$admin_color_2.'"></tr>';
          }
          echo '</table><P><BR>';

          if($letter){
              $letters=array("number",a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z);
                      foreach($letters as $letter){
                                       echo '<a class="left_menu" href="client_list.php?letter='.$letter.'">'.$letter.'</a>-';
                      }
          }
          echo '</center>';

      }else{
           echo '<font face="'.$admin_font.'" size=2><B>Client List..<B><center><P>';
           echo '<table width="500" border="0" cellspacing="0" cellpadding="4">
            <tr>
               <td colspan="2" bgcolor="'.$admin_color.'"><font color="'.$admin_font_color_2.'"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;Find
            a client by id..</font></b></font></td>
       </tr>
       <tr bgcolor="'.$admin_color_2.'">
       <td height="41" width="186" align="right"><font color="'.$admin_font_color.'" face="Verdana, Arial, Helvetica, sans-serif" size="2">Client
       Id:<br>
        <font size="1">enter client id and press enter</font></font> </td>
       <td height="41" width="314">
         <form name="form1" method="get" action="client_list.php">
           <input type="text" name="client_id">
           </form>
               </td>
         </tr>
       </table>      ';
       
       echo '<P><BR><table width="500" border="0" cellspacing="0" cellpadding="4">
            <tr>
               <td colspan="2" bgcolor="'.$admin_color.'"><font color="'.$admin_font_color_2.'"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;Search
            for a client..</font></b></font></td>
       </tr>
       <tr bgcolor="'.$admin_color_2.'">
       <td height="41" width="186" align="right"><font color="'.$admin_font_color.'" face="Verdana, Arial, Helvetica, sans-serif" size="2">Search for:<br>
        <font size="1">enter search and press enter</font></font> </td>
       <td height="41" width="314">
         <form name="form1" method="get" action="client_list.php">
           <input type="text" name="search">
           </form>
               </td>
         </tr>
       </table>      ';
       
       echo '<P><BR><table width="500" border="0" cellspacing="0" cellpadding="4">
            <tr>
               <td width=500 bgcolor="'.$admin_color.'"><font color="'.$admin_font_color_2.'"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;Display alphabetically..</font></b></font></td>
       </tr>
       <tr bgcolor="'.$admin_color_2.'">
       <td height="41" width="500"><center>';
                $letters=array("number",a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z);
                      foreach($letters as $letter){
                                       echo '<a class="left_menu" href="client_list.php?letter='.$letter.'">'.$letter.'</a>-';
                      }
              echo '</center></td>
         </tr>
       </table>      ';
      }


}


include "footer.php";
?>
