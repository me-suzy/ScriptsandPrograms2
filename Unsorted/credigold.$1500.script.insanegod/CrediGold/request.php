<?

/*----------------[      New Request for crediGold.com (PHP)      ]------------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 05/18/2002                                                             */

/*  Version : 1.0                                                                    */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth"));

global $dc, $id, $request_id;

$id = (!$id)?$request_id:$id;

$dc->query("SELECT ".$_Config["database_requests"].".*, ".$_Config["database_auth"].".real_name

            FROM  ".$_Config["database_requests"].", ".$_Config["database_auth"]."

            WHERE ".$_Config["database_requests"].".id='$id' AND ".$_Config["database_auth"].".user_number=".$_Config["database_requests"].".target_id;");

$dc->next_record();

if ($dc->get("request_id") == $auth->auth["userNumber"])

   {

      if (empty($request))

         {

            set_session("request_id",$id);

            set_session("name",$dc->get("real_name"));

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=0 marginheight=10>

<div align=center>

<p align=justify class=little style=width:520px><?=$auth->auth["real_name"]?>, you are about to make a new request to <?=$dc->get("real_name");?>. To do so just click on the button "Make New Request".</p>

</div>

<form name=requestIt action=request.php method=POST>

<table border=0 width=530 cellspacing=3 cellpadding=0 align=center>

<tr>

<td colspan=2 bgcolor=DFDFDF><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=left class=text colspan=2 bgColor=F9F9F9>&nbsp;<img src=images/point.gif width=9 height=9>&nbsp;<b style=color:darkred>Make New Request</td>

</tr>

<tr>

<td colspan=2 bgcolor=DFDFDF><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td align=left class=little bgColor=FCFCFC width=70% height=20>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To <?=$dc->get("real_name");?></td>

<td align=center class=little bgColor=FCFCFC width=30%><font color=orange><?=$dc->get("amount")?> <img src=images/gold.gif></font></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

<tr>

<td colspan=2 height=26 align=center><input type=submit value="Make New Request" name=request class=box> <input type=button name=close value="Close Window" class=box onClick="top.window.close();"></td>

</tr>

<tr>

<td colspan=2 bgcolor=F6F6F6><img src=images/dot.gif width=1 height=1></td>

</tr>

</table>

</form>

<?

         }

      else

         {

            $newDate = date("YmdHis", time());

            $dc->query("UPDATE ".$_Config["database_requests"]." SET status='Pending', request_date='$newDate' WHERE id='$request_id';");

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<br>

<br>

<p align=center class=head style=color:green><?=$_Config["masterSign"]?> Request Created Successfully!<br>

<font class=text color=black><?=$name?> has just received a new request from you in his/her account.</font>

</p>

<script>

<!--

function close()

   {

      setTimeout("parent.opener.location.href=parent.opener.location.href;top.window.close()",5000);

   }

window.onload = close;

//-->

</script>

<?

            session_destroy();

         }

   }

else

   {

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<br>

<br>

<p align=center class=head style=color:red>Warning! You are not authorized to have access!<br>

<font class=text color=black>This <?=$_Config["masterSign"]?> Request has been binded to a different account.</font>

</p>

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

   }

?>