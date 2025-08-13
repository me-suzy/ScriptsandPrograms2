<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
?>
<HTML>
<HEAD>
<TITLE>Assign a contact to a support request..</TITLE>
<script language="javascript">
     function changeclient(id)
     {
        window.location="assign_support_contact.php?client="+id+"&support_request=<?echo $support_request;?>";
     }

     function changecontact(id)
     {
        var ok=confirm("Set this contact as the one that submitted the support request?");
        if(ok==true)
                    window.location="assign_support_contact.php?contact="+id+"&client=<?echo $client;?>&support_request=<?echo $support_request;?>";
     }
     
</script>
</HEAD>
<BODY bgcolor="#efefef">
<?
$section="support";
include "../conf.php";
include "auth.php";

if($contact){
    mysql_query("UPDATE support_tickets SET contact_id='$contact' WHERE id='$support_request'");
    echo '<script language="javascript">
    window.close()
    </script>';;
exit;
}

  echo '<font face="'.$admin_font.'" size="2">Assign to a contact from client:<P> ';
  $clients=mysql_query("SELECT * FROM clients ORDER BY name");
       echo '<select name="clients" onChange="changeclient(this.value)"><option value="">-------</option>';
       while($c=mysql_fetch_array($clients)){
            $sel="";if($c[id]==$client){$sel="SELECTED";}
            echo '<option '.$sel.' value="'.$c[id].'">'.$c[name].'</option>';
       }
       echo '</select>';
  
   if($client){
          echo '<P>Select Contact:<P><select name="contacts" onChange="changecontact(this.value)"><option value="">-------</option>';
          $contacts=mysql_query("SELECT * FROM contacts WHERE client_id='$client' ORDER BY firstname");
       while($c=mysql_fetch_array($contacts)){
            $sel="";if($c[id]==$contact){$sel="SELECTED";}
            echo '<option '.$sel.' value="'.$c[id].'">'.$c[firstname].' '.$c[lastname].' - '.$c[title].'</option>';
       }
       echo '</select>';
   }
   
?>
</BODY>
</HTML>
