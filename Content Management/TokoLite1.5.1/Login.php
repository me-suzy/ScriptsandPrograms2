<?php

if ( !session_id() )
{
	session_start();
}

include( "LoadConfig.php" );
$error = "";

if ( isset( $_POST[ "userName" ] ) &&
	 isset( $_POST[ "password" ] ) )
{
	for ( $counter = 0; $counter < count( $usersPasswords ); $counter++ )
	{
		if ( $_POST[ "userName" ] == $usersPasswords[ $counter ][ 0 ] &&
			 $_POST[ "password" ] == $usersPasswords[ $counter ][ 1 ] )
		{
			if ( $counter > 0 )
			{
				$error = "Only one user is supported in the Toko Lite Version";
				break;
			}
			else
			{
				$_SESSION[ "loggedin" ] = true;
				header( "Location: Frames.html" );
				exit;
			}
		}
	}

	if ( $counter == count( $usersPasswords ) )
	{
		$error = "Invalid user name or password";
	}
}

?>
<html>
	<head>
		<link rel="stylesheet" href="toko.css" type="text/css" />
		<script language="javascript">

			if ( top.location.href != document.location.href )
			{
				top.location.href = document.location.href
			}

		</script>
	</head>
	<body onload="loginForm.userName.focus();">
		<form id="loginForm" action="Login.php" method="post">
			<table width="100%" height="100%">
				<tr>
					<td width="100%" height="100%" align="center" valign="center">
                <table style="border-collapse:collapse;" cellspacing="0" width="597">
                    <tr>
                        <td width="593" height="32" align="center" valign="middle" style="border-top-width:3; border-right-width:3; border-bottom-width:0; border-left-width:3; border-color:black; border-style:solid;" colspan="3">
                            <p class="Content" align="left"><font face="Verdana"><span style="font-size:14pt;"><i>&nbsp;</i></span></font></p>
                        </td>
                    </tr>
                    <tr>
                        <td width="593" height="186" align="center" valign="middle" style="border-top-width:0; border-right-width:3; border-bottom-width:0; border-left-width:3; border-color:black; border-style:solid;" colspan="3">
                        <table width="300" class="Content" bgcolor="#dcd9c8">
							<tr>
								<td class="Title">
									<p align="left"><font size=4><b>Toko Lite v1.5.2 </b></font></p>
								</td>
							</tr>
							<tr>
								<td>
                                        <div align="left">
									<table>
										<tr>
											<td colspan="2">
												<p align="left"><font color="red"><?= $error ?></font>
											</td>
										</tr>
										<tr>
											<td>
												<p align="left"><b>User name:</b>
											</td>
											<td>
												<p align="left"><input type="text" name="userName" />
											</td>
										</tr>
										<tr>
											<td>
												<p align="left"><b>Password:</b>
											</td>
											<td>
												<p align="left"><input type="password" name="password" />
											</td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<p align="left"><input type="submit" value="Login" />
											</td>
										</tr>
									</table>
                                        </div>
								</td>
							</tr>
							<tr>
								<td class="miniTitle">
                                        <p align="left"><iframe height="20" width="100%" src="Str.php" frameborder="0"></iframe></td>
							</tr>
						</table>
                        </td>
                    </tr>
                    <tr>
                        <td width="593" height="13" align="center" valign="middle" style="border-top-width:0; border-right-width:3; border-bottom-width:0; border-left-width:3; border-color:black; border-style:solid;" colspan="3">

        <hr>

                        </td>
                    </tr>
                    <tr>
                        <td width="364" height="75" align="left" valign="top" style="border-top-width:0; border-right-width:0; border-bottom-width:0; border-left-width:3; border-color:black; border-style:solid;">
                            <p align="left"><span style="font-size:10pt;">This Program is free, i.e you can
                            use this program for any personal use. If you intend
                            to use it for commercial use, please
                            <a href="http://toko-contenteditor.pageil.net/page002suuporttoko.shtml">contribute</a> to
                            the free version development.<br>
                            Toko Web Services have additional editor versions, you may check what we have to

                            <a href="http://toko-contenteditor.pageil.net">offer

                            </a>.
                            </span></p>
                        </td>
                        <td width="17" height="114" align="right" valign="bottom" style="border-top-width:0; border-right-width:0; border-bottom-width:3; border-left-width:0; border-color:black; border-style:solid;" rowspan="2">
                            <p align="right">&nbsp;</p>
                        </td>
                        <td width="207" height="114" align="left" valign="top" style="border-top-width:0; border-right-width:3; border-bottom-width:3; border-left-width:0; border-color:black; border-style:solid;" rowspan="2">
                            <p align="left"><font color="#CC0000"><b><span style="font-size:10pt;">Toko Visual</span></b></font><span style="font-size:10pt;">
                            </span><font color="#CC0000"><span style="font-size:10pt;">A Visual Wysiwyg
                            version
                            of Toko Content Editor.<br>
                            Find out more.&nbsp;<br></span></font></p>
                            <p align="left">
			    <a href="http://toko-contenteditor.pageil.net/page003-visual.shtml"><i><span style="font-size:10pt;"><font color="#CC0000"><img src="Images/demoicon.gif" width="94" height="44" border="0"></font></span></i></a><font color="#CC0000"><i><span style="font-size:10pt;">
                            &nbsp;</span></i></font><a href="http://toko-contenteditor.pageil.net/page002-comparisonf.shtml"><i><span style="font-size:10pt;"><font color="#CC0000"><img src="Images/compareicon.gif" width="94" height="44" border="0"></font></span></i></a></p>
                        </td>
                    </tr>
                    <tr>
                        <td width="364" height="5" align="left" valign="bottom" style="border-top-width:0; border-right-width:0; border-bottom-width:3; border-left-width:3; border-color:black; border-style:solid;">
                            <p align="left"><span style="font-size:8pt;"><i>Copyright &copy; 2004-2005 Toko
                            Web Services. All rights reserved.</i></span></p>
                        </td>
                    </tr>
                </table>
                <p class="Content">&nbsp;</p>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>