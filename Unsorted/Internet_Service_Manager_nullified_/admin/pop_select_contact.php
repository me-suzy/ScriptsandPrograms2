<HTML>
<HEAD>
<TITLE>Assign a contact to a support request..</TITLE>
<script language="javascript">
     function changeclient(id)
     {
        window.location="pop_select_contact.php?client="+id;
     }

     function changecontact(id)
     {
              window.location="pop_select_contact.php?contactchosen="+id
     }
     
</script>
</HEAD>
<BODY bgcolor="#efefef">
<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="support";
include "../conf.php";
include "auth.php";

if($contactchosen){
    $c=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contactchosen'"));
    echo '<script language="javascript">
        window.opener.document.newticket.name.value="'.$c[firstname].' '.$c[lastname].'"
        window.opener.document.newticket.email.value="'.$c[email].'"
    window.close()
    </script>';
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
