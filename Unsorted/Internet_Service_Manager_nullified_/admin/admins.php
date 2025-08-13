<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="management";
include "../conf.php";
include "auth.php";
include "header.php";

?>
<script language="javascript">
     function selectadmin(id)
     {
        window.location="admins.php?admin="+id;
     }
	 
	 function updatepassword(){
	 if(document.newadmin.username.value==""){
	 document.newadmin.username.value=document.newadmin.firstname.value
	 }
	 }

</script>
</HEAD>
<BODY bgcolor="#efefef">
<?

if($admin){

if($save){
foreach($privelages as $pr=>$go){
$pri.=",$pr";
}
$pri.=",";
$privelages=$pri;

if($admin=="NEWADMIN"){
mysql_query("INSERT INTO admins SET privelages='$privelages', firstname='$firstname', lastname='$lastname', email='$email', title='$title', phone='$phone', username='$ausername', password='$apassword', mail_server='$mail_server', mail_password='$mail_password', mail_username='$mail_username', signature='$signature'");
echo '<script language=javascript>
window.location="admins.php?admin='.mysql_insert_id().'"
</script>';
}else{
mysql_query("UPDATE admins SET privelages='$privelages', firstname='$firstname', lastname='$lastname', email='$email', title='$title', phone='$phone', username='$ausername', password='$apassword', mail_server='$mail_server', mail_password='$mail_password', mail_username='$mail_username', signature='$signature' WHERE id='$admin'");
}

}

$res=mysql_fetch_array(mysql_query("SELECT * FROM admins WHERE id='$admin'"));

$firstname=$res[firstname];if($admin=="NEWADMIN"){$firstname="New Administrator";}
echo '<font face="'.$admin_font.'" size="2"><B>Editing Admin: '.$firstname." ".$res[lastname].'</B>';

echo '<P><table><form action="admins.php?admin='.$admin.'" name="newadmin" method="post">';

echo '<tr><td><font face="'.$admin_font.'" size="2">Firstname: </td><td><input type=text onChange="updatepassword()" name=firstname value="'.$res[firstname].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Lastname: </td><td><input type=text name=lastname value="'.$res[lastname].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Title: </td><td><input type=text name=title value="'.$res[title].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Email: </td><td><input type=text name=email value="'.$res[email].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Phone: </td><td><input type=text name=phone value="'.$res[phone].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Username: </td><td><input type=text name=ausername value="'.$res[username].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Password: </td><td><input type=text name=apassword value="'.$res[password].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Email Server: </td><td><input type=text name=mail_server value="'.$res[mail_server].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Email Username: </td><td><input type=text name=mail_username value="'.$res[mail_username].'"></td></tr>';
echo '<tr><td><font face="'.$admin_font.'" size="2">Email Password: </td><td><input type=text name=mail_password value="'.$res[mail_password].'"></td></tr>';
echo '<tr><td valign=top><font face="'.$admin_font.'" size="2">Email Signature: </td><td><textarea cols=30 rows=4 name=signature>'.$res[signature].'</textarea></td></tr>';
echo '<tr><td valign=top><font face="'.$admin_font.'" size="2">Privelages: </td><td><font face="'.$admin_font.'" size="2">';

$p=array("billing"=>0,"editclients"=>0,"support"=>0,"editprojects"=>0,"developer"=>0,"export"=>0,"management"=>0);
$cp=explode(",", $res[privelages]);
foreach($cp as $c){
if($c){$p[$c]=1;}
}

foreach($p as $pr=>$state){
$ce="";if($state==1){$ce="CHECKED";}
echo '<input '.$ce.' type=checkbox name=privelages['.$pr.']>'.$pr.'<BR>';
}

echo '<P></td></tr>';

echo '<tr><td valign=top></td><td><input type=submit name="save" value="Save It Now!"></td></tr>';

echo '</table>';
// ,billing,editclients,support,editprojects,developer,export,management, 
}




 echo '<P><hr color="'.$admin_color.'"><font face="'.$admin_font.'" size="2">Select an admin to edit:<P> ';
  $clients=mysql_query("SELECT * FROM admins ORDER BY firstname");
       echo '<select name="clients" onChange="selectadmin(this.value)"><option value="">-------</option><option value="NEWADMIN">Create New</option>';
       while($c=mysql_fetch_array($clients)){
            echo '<option '.$sel.' value="'.$c[id].'">'.$c[firstname]." ".$c[lastname].'</option>';
       }
       echo '</select>';





include "footer.php";
?>
