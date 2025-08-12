<?

/*----------------[      Admin Rights for crediGold.com (PHP)      ]-----------------*/

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

<link rel=stylesheet href=../../modules/styles.css>

<script language=JavaScript src=../../modules/mod_gen.js></script>

<body bgcolor=white leftmargin=0 topmargin=10 marginwidth=0 marginheight=10>

<br>

<?

$dc->query("UPDATE ".$_Config["database_auth"]." SET perms='$cmd' WHERE user_number='".$id."';");

if ($cmd == "admin")

   {

?>

<div align=center class=head style=color:orange>Admin Rights Granted To This User!</div>

<br>

<?

   }

else

   {

?>

<div align=center class=head style=color:darkred>User's Admin Rights Removed!</div>

<br>

<?

   }

?>

<script>

<!--

function close()

   {

      setTimeout("top.window.close()",5000);

   }

window.onload = close;

//-->

</script>



