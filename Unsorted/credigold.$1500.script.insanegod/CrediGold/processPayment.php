<?

// Delete Reoccuring Payment Module

include("prepend.php3");

page_open(array("sess" => "User_Session", "auth" => "Credigold_Auth"));

global $dc, $rc, $id, $action;

$dc->query("SELECT user_id FROM ".$_Config["database_payments"]." WHERE id='$id' AND user_id='".$auth->auth["userNumber"]."';");

if ($dc->num_rows() == 1)

	{

		if ($action == "start")

			{

				$dc->query("UPDATE ".$_Config["database_payments"]." SET status='Running' WHERE id='$id';");		

			}

		else if ($action == "stop")

			{

				$dc->query("UPDATE ".$_Config["database_payments"]." SET status='Stopped' WHERE id='$id';");		

			}

		

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<p align=center class=head style=color:green>Reoccuring Payment <?=($action == "start")?"Started":"Stopped";?> Successfully!

</p>

<script>

<!--

function close()

	{

		setTimeout("parent.opener.location.href=parent.opener.location.href;top.window.close()",3000);

	}

window.onload = close;

//-->

</script>

<?		

	}

else

	{

?>

<link rel=stylesheet href=modules/styles.css>

<script language=JavaScript src=modules/mod_gen.js></script>

<body bgcolor=white leftmargin=10 topmargin=10 marginwidth=10 marginheight=10>

<br>

<p align=center class=head style=color:red>Unauthorized Access to Module!

</p>

<script>

<!--

function close()

	{

		setTimeout("parent.opener.location.href=parent.opener.location.href;top.window.close()",3000);

	}

window.onload = close;

//-->

</script>

<?		

	}

// End of Delete Module

?>