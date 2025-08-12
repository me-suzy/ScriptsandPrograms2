<?php
// -------------------------------------------------------------
//
// $Id: index.php,v 1.3 2005/04/03 10:09:33 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

if (is_dir('./install'))
{
	echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>GENU - Home</title>
</head>
<body><p>Please remove <span style="font-weight: bold">install</span> folder from your server.</p></body>
</html>';
}
else
{
	header('Location: ./news/index.php');
}

?>