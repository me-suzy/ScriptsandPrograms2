<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>DoorwayEagle - An Online Doorway Page Generator script</title>
<meta name="keywords" content="domaineagle.com,domaineagle,doorwayeagle,doorway,page,generator,doorway page generator,doorway page creator,doorway page maker,doorway pages,doorway page,website promotion,increase traffic">
<meta name="description" content="The DoorwayEagle is an Online Doorway Page Generator script written in PHP, which website owners can can easily download and install on their server. Own your own personal doorway page generator, DoorwayEagle!">
</head>
<body bgcolor="#ffffff" text="#000000" link="#000099" alink="#0000ff" vlink="#000099" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">

<!-- header -->
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td width="1" bgcolor="#336699">
			<img src="images/px.gif" width="1" height="100" border="0"><br>
		</td>
		<td bgcolor="#336699">
			<a href="index.php"><img src="images/logo.gif" width="165" height="68" border="0" alt="Welcome to our website"></a><br>
		</td>
		<td bgcolor="#336699">

		</td>
	</tr>
	<tr>
		<td bgcolor="#000000" colspan="3">
		</td>
	</tr>
	<tr>
		<td bgcolor="#cccccc" colspan="3">&nbsp;
		  
		</td>
	</tr>
	<tr>
		<td bgcolor="#000000" valign="top" colspan="3">
			<img src="images/px.gif" width="1" height="1" border="0"><br>
		</td>
	</tr>
</table>
<!-- end header -->
<table cellspacing="0" cellpadding="0" border="0" width="700">
	<tr>
<!-- left column -->
		<td bgcolor="whitesmoke" valign="top">
			<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
				<tr>
					<td valign="top">
						<img src="images/px.gif" width="175" height="7" border="0"><br>
													
								<table border="0" width="100%">
									<tr>
										<td>
											<hr size="1" align="center" noshade>
											&raquo; <a href="javascript: var popUp=open('help.html','help','width=400,height=200,scrollbars=yes,resizable=no');"><font face="Arial" size="2"><b>Helpful Tips</b></font></a><br>
                  &raquo; <a href="index.php"><font face="Arial" size="2"><b>Homepage</b></font></a><br>
											<hr size="1" align="center" noshade>
											<img src="images/px.gif" width="1" height="10" border="0"><br>
											<br>							
										</td>
									</tr>
								</table>
					</td>
				</tr>
			</table>
		</td>
<!-- end left column -->
<!-- left divider -->
		<td bgcolor="#000000" width="1" rowspan="2" background="images/checkerboard.gif">
			<img src="images/px.gif" width="1" height="1" border="0"><br>
		</td>
<!-- end left divider -->
<!-- body -->
		<td valign="top" rowspan="2">
		<img src="images/px.gif" width="523" height="1" border="0"><br>
			<table cellpadding="3" cellspacing="0" border="0" align="center" width="600">
				<tr>
					<td>
						<?php
						
						if ($doorwaymaker) {
								include ("makedoorway.inc");
						} else {
								include("index.inc");
						}
						
						?>
					</td>
				</tr>
			</table>
		</td>
<!-- end body -->
	</tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
  <tr> 
    <td bgcolor="#000000" colspan="2"> <img src="images/px.gif" height="1" width="1" border="0"><br> 
    </td>
  </tr>
  <tr> 
    <td bgcolor="#336699" colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td bgcolor="#000000" colspan="2"> <img src="images/px.gif" height="1" width="1" border="0"><br> 
    </td>
  </tr>
  <tr> 
    <td bgcolor="#cccccc"> <img src="images/px.gif" border="0" width="1" height="40"><br> 
    </td>
    <td bgcolor="#cccccc" valign="middle"> <font face="verdana,arial,helvetica" size="1">&nbsp; 
      DoorwayEagle 1.0 - Copyright &copy; My Great Company.&nbsp; All rights reserved. 
      [+W+h+a+t++D+o++Y+o+u++L+o+o+k+i+n+g+]</font><br> </td>
  </tr>
</table>

</body>
</html>
