<?

/*----------------[      Address Book for crediGold.com (PHP)      ]-----------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 05/22/2002                                                             */

/*  Version : 1.7                                                                    */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth"));

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=0 topmargin=10 marginwidth=0 marginheight=10>



<div align=center><img src="images/logos/address.jpg" width="232" height="48" border=0 alt="Address Book"></div>

<br>

<?

if ($action == "addAccount")

   {

      $dc->query("SELECT user_id FROM ".$_Config["database_addressbook"]." WHERE user_id='".$auth->auth["uid"]."' AND account='".$id."';");

      if ($dc->num_rows() > 0)

         {

?>

<div align=center class=head style=color:orange>Address Already Added In AddressBook!</div>

<br>

<?

         }

      else

         {

            $dc->query("INSERT INTO ".$_Config["database_addressbook"]." SET user_id='".$auth->auth["uid"]."', address='".$resolvedName."', account='".$id."';");

?>

<div align=center class=head style=color:green>Address Added Successfully!</div>

<br>

<?

         }

?>

<table border=0 width=350 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td bgColor=F9F9F9 align=center class=little width=5%><img src="images/point.gif" width="9" height="9"></td>

<td bgColor=F9F9F9 align=left class=little width=75%><b>Person's Name</td>

<td bgColor=F9F9F9 align=center class=little width=20%><b>Account</td>

</tr>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center class=little width=5%>&nbsp;</td>

<td align=left class=little width=75%>&nbsp;<?=$resolvedName;?></td>

<td align=center class=little width=20%><?=$id;?></td>

</tr>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

</table>

<script>

<!--

function close()

   {

      setTimeout("top.window.close()",5000);

   }

window.onload = close;

//-->

</script>



<?

      exit;

   }

if ($helpMeOut)

   {

      $rules = "";

      for ($i=0;$i<$helpMeOut;$i++)

         {

            $tempAdv = "address".$i;

            if ($$tempAdv != "")

               {

                  $rules .= ($rules == "")?"(account='".$$tempAdv."' ":"OR account='".$$tempAdv."' ";

               }

         }

      $rules .= ($rules == "")?"":")";

      $dc->query("DELETE FROM ".$_Config["database_addressbook"]." WHERE user_id='".$auth->auth["uid"]."' AND $rules;");

?>

<div align=center class=head style=color:green>Entries deleted successfully!</div>

<?

   }

?>

<form name=book action=address.php method=POST>

<table border=0 width=350 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=center class=text colspan=3 bgColor=F9F9F9><b>Saved Payable Addresses</td>

</tr>

<tr>

<td colspan=3 bgcolor=D0D0D0><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td bgColor=F9F9F9 align=center class=little width=5%><img src="images/point.gif" width="9" height="9"></td>

<td bgColor=F9F9F9 align=left class=little width=75%><b>Person's Name</td>

<td bgColor=F9F9F9 align=center class=little width=20%><b>Account</td>

</tr>

<tr>

<td colspan=3 bgcolor=F3F3F3><img src=images/dot.gif width=1 height=1></td>

</tr>

<?

$dc->query("SELECT * FROM ".$_Config["database_addressbook"]." WHERE user_id='".$auth->auth["uid"]."';");

if ($dc->num_rows() == 0)

   {

?>

<tr>

<td align=center colspan=3 class=little height=28><font color=darkred>No addresses added in book!</font></td>

</tr>

<?

   }

else

   {

      for ($i=0;$i<$dc->num_rows();$i++)

         {

            $dc->next_record();

?>

<tr>

<td align=center class=little width=5%><input type=checkbox name=address<?=$i?> value="<?=$dc->get("account");?>" id=person<?=$i?>></td>

<td align=left class=little width=75%>&nbsp;<label for=person<?=$i?> onmouseover="this.style.color='darkred';this.style.cursor='hand'" onmouseout="this.style.color='black'"><?=$dc->get("address");?></label></td>

<td align=center class=little width=20%><?=$dc->get("account");?></td>

</tr>

<?

         }

?>

<tr>

<td align=center colspan=3 class=little height=28><input type=button value="<?=($cmd == "request")?"Send Request":"Pay Selected People"?>" onClick="pay()" class=box> <input type=button value="Delete People" onClick="del()" name=deletePeople class=box></td>

</tr>

<input type=hidden name=helpMeOut value="<?=$dc->num_rows()?>">

<script language=JavaScript>

<!--

<?

print "var entries = '".$dc->num_rows()."';\n\n";

?>

f = document.book;

function pay()

   {

      t = parent.opener.send.to;

      m = 0;

      for (i=0;i<entries;i++)

         {

            if (eval("f.address"+i+".checked") == true)

               {

                  s = eval("f.address"+i+".value");

                  if (t.value.length == 0)

                     {

                        t.value += s;

                        m++;

                     }

                  else

                     {

                        t.value += ","+s;

                        m++;

                     }

               }

         }

      if (m == 0)

         {

            alert("You forgot to check which people you want to pay to. Please, do so before you click on this button");

         }

      else

         {

            top.window.close();

         }

   }

function del()

   {

      if (confirm("Are you sure you want to delete these people from your address book?"))

         {

            f.submit();

         }

   }

//-->

</script>

<?

   }

?>

</table>

</form>