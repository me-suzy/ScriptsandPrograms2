<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html;CHARSET=iso-8859-1">
	<TITLE>phpPeanuts pnt/unit</TITLE>
	<LINK rel="stylesheet" href="../style/pntUnit.css" type="text/css" id="css">
	<script src="../scripts/general.js"></script>
</HEAD>

<BODY>
<TABLE WIDTH="100%" CELLPADDING="3" CELLSPACING="3" BORDER="0" class=pntPageTitle>
			<TR>
				<TD class=pntPageTitle><H1>phpPeanuts pnt/unit</H1></TD>
				<TD ALIGN="RIGHT"><IMG id=peanutImage SRC="../images/pntUnit/broken.gif" HEIGHT="50" ALIGN="BOTTOM" BORDER="0"></TD>
			</TR>
</TABLE>	
<FORM ACTION="index.php" METHOD="POST" ENCTYPE="application/x-www-form-urlencoded">

<TABLE class=maintable BORDER="0" WIDTH="100%" CELLPADDING="0" CELLSPACING="6">
	<TR><TD ALIGN="center">
		<?php $this->printScriptPart() ?>
	</TD></TR>
	<?php $this->printTestRunPart() ?>

<?php //footer printed event-driven ?>